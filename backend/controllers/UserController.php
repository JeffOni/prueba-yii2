<?php

namespace backend\controllers;

use Yii;
use common\models\User;
use backend\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * UserController implementa las acciones CRUD para el modelo User.
 * Solo accesible para usuarios con rol de Administrador.
 */
class UserController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                // Control de acceso basado en RBAC
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'actions' => ['index', 'view'],
                            'allow' => true,
                            'roles' => ['viewUser'], // Solo Admin
                        ],
                        [
                            'actions' => ['create'],
                            'allow' => true,
                            'roles' => ['createUser'], // Solo Admin
                        ],
                        [
                            'actions' => ['update'],
                            'allow' => true,
                            'roles' => ['updateUser'], // Solo Admin
                        ],
                        [
                            'actions' => ['delete'],
                            'allow' => true,
                            'roles' => ['deleteUser'], // Solo Admin
                        ],
                    ],
                ],
                // Control de verbos HTTP
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lista todos los usuarios con filtros y paginación.
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Muestra los detalles de un usuario específico.
     * @param int $id ID del usuario
     * @return string
     * @throws NotFoundHttpException si el usuario no existe
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        // Obtener roles asignados al usuario
        $auth = Yii::$app->authManager;
        $roles = $auth->getRolesByUser($id);
        $roleNames = array_keys($roles);

        return $this->render('view', [
            'model' => $model,
            'roleNames' => $roleNames,
        ]);
    }

    /**
     * Crea un nuevo usuario.
     * Si la creación es exitosa, redirige a la página de vista.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new User();
        $model->scenario = 'create';

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                // Establecer contraseña encriptada
                $model->setPassword($model->password);
                $model->generateAuthKey();
                $model->status = User::STATUS_ACTIVE;

                if ($model->save()) {
                    // Asignar roles si fueron seleccionados (múltiples)
                    $roles = $this->request->post('User')['roles'] ?? [];
                    if (!empty($roles)) {
                        $auth = Yii::$app->authManager;
                        $assignedRoles = [];

                        foreach ($roles as $roleName) {
                            $roleObject = $auth->getRole($roleName);
                            if ($roleObject) {
                                $auth->assign($roleObject, $model->id);
                                $assignedRoles[] = $roleName;
                            }
                        }

                        // Registrar en audit log todos los roles asignados
                        if (!empty($assignedRoles)) {
                            $this->logAudit(
                                'assign_roles',
                                'user',
                                $model->id,
                                null,
                                "Roles asignados: " . implode(', ', $assignedRoles) . " al usuario {$model->username}"
                            );
                        }
                    }

                    Yii::$app->session->setFlash('success', 'Usuario creado exitosamente.');
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    Yii::$app->session->setFlash('error', 'Error al crear el usuario.');
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Actualiza un usuario existente.
     * Si la actualización es exitosa, redirige a la página de vista.
     * @param int $id ID del usuario
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException si el usuario no existe
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'update';

        // Obtener roles actuales del usuario (todos)
        $auth = Yii::$app->authManager;
        $currentRolesObjects = $auth->getRolesByUser($id);
        $currentRoles = array_keys($currentRolesObjects);

        if ($this->request->isPost && $model->load($this->request->post())) {
            // Solo actualizar contraseña si se ingresó una nueva
            if (!empty($model->password)) {
                $model->setPassword($model->password);
                $model->generateAuthKey();
            } else {
                // Mantener la contraseña anterior
                unset($model->password);
            }

            if ($model->save()) {
                // Actualizar roles si cambiaron
                $newRoles = $this->request->post('User')['roles'] ?? [];

                // Determinar qué roles agregar y cuáles revocar
                $rolesToRevoke = array_diff($currentRoles, $newRoles);
                $rolesToAssign = array_diff($newRoles, $currentRoles);

                // Revocar roles que ya no están seleccionados
                foreach ($rolesToRevoke as $roleName) {
                    $roleObject = $auth->getRole($roleName);
                    if ($roleObject) {
                        $auth->revoke($roleObject, $model->id);
                    }
                }

                // Asignar nuevos roles
                foreach ($rolesToAssign as $roleName) {
                    $roleObject = $auth->getRole($roleName);
                    if ($roleObject) {
                        $auth->assign($roleObject, $model->id);
                    }
                }

                // Registrar cambio en audit log si hubo cambios
                if (!empty($rolesToRevoke) || !empty($rolesToAssign)) {
                    $oldRolesText = !empty($currentRoles) ? implode(', ', $currentRoles) : 'Sin roles';
                    $newRolesText = !empty($newRoles) ? implode(', ', $newRoles) : 'Sin roles';

                    $this->logAudit(
                        'change_roles',
                        'user',
                        $model->id,
                        "Roles anteriores: {$oldRolesText}",
                        "Nuevos roles: {$newRolesText}"
                    );
                }

                Yii::$app->session->setFlash('success', 'Usuario actualizado exitosamente.');
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('error', 'Error al actualizar el usuario.');
            }
        }

        return $this->render('update', [
            'model' => $model,
            'currentRoles' => $currentRoles,
        ]);
    }

    /**
     * Elimina un usuario (soft delete cambiando status a DELETED).
     * @param int $id ID del usuario
     * @return \yii\web\Response
     * @throws NotFoundHttpException si el usuario no existe
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        // Prevenir que el usuario se elimine a sí mismo
        if ($model->id === Yii::$app->user->id) {
            Yii::$app->session->setFlash('error', 'No puedes eliminar tu propia cuenta.');
            return $this->redirect(['index']);
        }

        // Soft delete: cambiar status a DELETED
        $model->status = User::STATUS_DELETED;
        if ($model->save(false)) {
            // Registrar en audit log
            $this->logAudit(
                'delete_user',
                'user',
                $model->id,
                "Usuario activo: {$model->username}",
                "Usuario eliminado (soft delete)"
            );

            Yii::$app->session->setFlash('success', 'Usuario eliminado exitosamente.');
        } else {
            Yii::$app->session->setFlash('error', 'Error al eliminar el usuario.');
        }

        return $this->redirect(['index']);
    }

    /**
     * Encuentra el modelo User basado en su ID.
     * Si no se encuentra, lanza una excepción 404.
     * @param int $id ID del usuario
     * @return User el modelo cargado
     * @throws NotFoundHttpException si el modelo no existe
     */
    protected function findModel($id)
    {
        if (($model = User::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('El usuario solicitado no existe.');
    }

    /**
     * Registra una acción en el audit log.
     * @param string $action Acción realizada
     * @param string $tableName Tabla afectada
     * @param int $recordId ID del registro afectado
     * @param string|null $oldValue Valor anterior
     * @param string|null $newValue Nuevo valor
     */
    protected function logAudit($action, $tableName, $recordId, $oldValue, $newValue)
    {
        Yii::$app->db->createCommand()->insert('audit_log', [
            'user_id' => Yii::$app->user->id,
            'action' => $action,
            'table_name' => $tableName,
            'record_id' => $recordId,
            'old_value' => $oldValue,
            'new_value' => $newValue,
            'ip_address' => Yii::$app->request->userIP,
            'user_agent' => Yii::$app->request->userAgent,
            'created_at' => time(),
        ])->execute();
    }
}
