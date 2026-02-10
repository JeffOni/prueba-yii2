<?php

namespace common\modules\api\v1\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\web\Response;
use common\models\Product;

/**
 * Controlador REST para Productos
 *
 * Endpoints:
 * - GET    /api/v1/products       - Listar productos
 * - GET    /api/v1/products/{id}  - Ver un producto
 * - POST   /api/v1/products       - Crear producto
 * - PUT    /api/v1/products/{id}  - Actualizar producto
 * - DELETE /api/v1/products/{id}  - Eliminar producto
 */
class ProductController extends ActiveController
{
    public $modelClass = 'common\models\Product';

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

        // Personalizar las acciones si es necesario
        // Por ejemplo, agregar verificación de permisos RBAC

        return $actions;
    }

    /**
     * {@inheritdoc}
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        // Verificar permisos RBAC según la acción
        switch ($action) {
            case 'index':
            case 'view':
                if (!Yii::$app->user->can('viewProduct')) {
                    throw new \yii\web\ForbiddenHttpException('No tienes permiso para ver productos.');
                }
                break;
            case 'create':
                if (!Yii::$app->user->can('createProduct')) {
                    throw new \yii\web\ForbiddenHttpException('No tienes permiso para crear productos.');
                }
                break;
            case 'update':
                if (!Yii::$app->user->can('updateProduct')) {
                    throw new \yii\web\ForbiddenHttpException('No tienes permiso para actualizar productos.');
                }
                break;
            case 'delete':
                if (!Yii::$app->user->can('deleteProduct')) {
                    throw new \yii\web\ForbiddenHttpException('No tienes permiso para eliminar productos.');
                }
                break;
        }
    }
}
