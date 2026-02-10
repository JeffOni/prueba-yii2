<?php

namespace common\modules\api\v1\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\web\Response;
use common\models\User;

/**
 * Controlador REST para Usuarios
 *
 * Endpoints:
 * - GET    /api/v1/users       - Listar usuarios (solo Admin)
 * - GET    /api/v1/users/{id}  - Ver un usuario (solo Admin)
 * - POST   /api/v1/users       - Crear usuario (solo Admin)
 * - PUT    /api/v1/users/{id}  - Actualizar usuario (solo Admin)
 * - DELETE /api/v1/users/{id}  - Eliminar usuario (solo Admin)
 */
class UserController extends ActiveController
{
    public $modelClass = 'common\models\User';

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // Autenticación JWT
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];

        // Negociación de contenido
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::class,
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];

        return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        $actions = parent::actions();

        // Personalizar serialización para ocultar campos sensibles
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        return $actions;
    }

    /**
     * Prepara el data provider con filtros personalizados
     */
    public function prepareDataProvider()
    {
        return new \yii\data\ActiveDataProvider([
            'query' => User::find()->select(['id', 'username', 'email', 'status', 'created_at', 'updated_at']),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        // Solo administradores pueden gestionar usuarios vía API
        switch ($action) {
            case 'index':
            case 'view':
                if (!Yii::$app->user->can('viewUser')) {
                    throw new \yii\web\ForbiddenHttpException('No tienes permiso para ver usuarios.');
                }
                break;
            case 'create':
                if (!Yii::$app->user->can('createUser')) {
                    throw new \yii\web\ForbiddenHttpException('No tienes permiso para crear usuarios.');
                }
                break;
            case 'update':
                if (!Yii::$app->user->can('updateUser')) {
                    throw new \yii\web\ForbiddenHttpException('No tienes permiso para actualizar usuarios.');
                }
                break;
            case 'delete':
                if (!Yii::$app->user->can('deleteUser')) {
                    throw new \yii\web\ForbiddenHttpException('No tienes permiso para eliminar usuarios.');
                }
                break;
        }
    }
}
