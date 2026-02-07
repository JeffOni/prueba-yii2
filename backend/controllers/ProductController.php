<?php

namespace backend\controllers;

use Yii;
use common\models\Product;
use backend\models\ProductSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ProductController implementa el CRUD de productos (RF-06)
 */
class ProductController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                // Control de acceso basado en RBAC (RF-03, RF-06)
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'actions' => ['index', 'view'],
                            'allow' => true,
                            'roles' => ['viewProduct'], // Viewer, Editor, Admin pueden ver
                        ],
                        [
                            'actions' => ['create'],
                            'allow' => true,
                            'roles' => ['createProduct'], // Editor, Admin pueden crear
                        ],
                        [
                            'actions' => ['update'],
                            'allow' => true,
                            'roles' => ['updateProduct'], // Editor, Admin pueden editar
                        ],
                        [
                            'actions' => ['delete'],
                            'allow' => true,
                            'roles' => ['deleteProduct'], // Solo Admin puede eliminar
                        ],
                    ],
                ],
                // Validación de verbos HTTP
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
     * Lista todos los productos con búsqueda, filtros y paginación (RF-07)
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Muestra el detalle de un producto específico
     * @param int $id ID del producto
     * @return string
     * @throws NotFoundHttpException si el producto no existe
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Crea un nuevo producto
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Product();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', 'Producto creado exitosamente.');
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('error', 'Error al crear el producto. Verifica los datos.');
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Actualiza un producto existente
     * @param int $id ID del producto
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException si el producto no existe
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Producto actualizado exitosamente.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Elimina un producto
     * @param int $id ID del producto
     * @return \yii\web\Response
     * @throws NotFoundHttpException si el producto no existe
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'Producto eliminado exitosamente.');
        } else {
            Yii::$app->session->setFlash('error', 'Error al eliminar el producto.');
        }

        return $this->redirect(['index']);
    }

    /**
     * Encuentra un producto por su ID
     * @param int $id ID del producto
     * @return Product el modelo cargado
     * @throws NotFoundHttpException si el producto no existe
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('El producto solicitado no existe.');
    }
}
