<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Product;

/** @var yii\web\View $this */
/** @var backend\models\ProductSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Gestión de Productos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= Html::encode($this->title) ?></h1>

        <?php if (Yii::$app->user->can('createProduct')): ?>
            <?= Html::a('<i class="bi bi-plus-circle"></i> Crear Producto', ['create'], ['class' => 'btn btn-success']) ?>
        <?php endif; ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-striped table-hover'],
        'pager' => [
            'class' => 'yii\bootstrap5\LinkPager',
            'options' => ['class' => 'pagination justify-content-center'],
            'linkOptions' => ['class' => 'page-link'],
            'activePageCssClass' => 'active',
            'disabledPageCssClass' => 'disabled',
            'maxButtonCount' => 5,
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // ID (ordenable, sin filtro)
            [
                'attribute' => 'id',
                'headerOptions' => ['style' => 'width: 80px'],
                'filter' => false,
            ],

            // Nombre (ordenable, con filtro de búsqueda)
            [
                'attribute' => 'name',
                'format' => 'html',
                'value' => function ($model) {
                        return Html::a(Html::encode($model->name), ['view', 'id' => $model->id], [
                            'class' => 'text-decoration-none fw-semibold'
                        ]);
                    },
            ],

            // SKU (ordenable, con filtro de búsqueda)
            [
                'attribute' => 'sku',
                'headerOptions' => ['style' => 'width: 150px'],
            ],

            // Precio (ordenable, sin filtro)
            [
                'attribute' => 'price',
                'format' => 'html',
                'value' => function ($model) {
                        return '<span class="badge bg-primary fs-6">' . $model->getFormattedPrice() . '</span>';
                    },
                'headerOptions' => ['style' => 'width: 130px'],
                'filter' => false,
            ],

            // Stock (ordenable, con filtro numérico)
            [
                'attribute' => 'stock',
                'format' => 'html',
                'value' => function ($model) {
                        if ($model->stock == 0) {
                            return '<span class="badge bg-danger">Sin stock</span>';
                        } elseif ($model->stock <= 5) {
                            return '<span class="badge bg-warning text-dark">' . $model->stock . '</span>';
                        } else {
                            return '<span class="badge bg-success">' . $model->stock . '</span>';
                        }
                    },
                'headerOptions' => ['style' => 'width: 120px'],
            ],

            // Status (ordenable, con dropdown filtro)
            [
                'attribute' => 'status',
                'format' => 'html',
                'value' => function ($model) {
                        if ($model->status == Product::STATUS_ACTIVE) {
                            return '<span class="badge bg-success">Activo</span>';
                        } else {
                            return '<span class="badge bg-secondary">Inactivo</span>';
                        }
                    },
                'filter' => Product::getStatusList(),
                'headerOptions' => ['style' => 'width: 120px'],
            ],

            // Acciones (Ver, Editar, Eliminar según permisos RBAC)
            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['style' => 'width: 120px'],
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                            if (Yii::$app->user->can('viewProduct')) {
                                return Html::a('<i class="bi bi-eye"></i>', $url, [
                                    'title' => 'Ver',
                                    'class' => 'btn btn-sm btn-info',
                                ]);
                            }
                            return '';
                        },
                    'update' => function ($url, $model) {
                            if (Yii::$app->user->can('updateProduct')) {
                                return Html::a('<i class="bi bi-pencil"></i>', $url, [
                                    'title' => 'Editar',
                                    'class' => 'btn btn-sm btn-primary',
                                ]);
                            }
                            return '';
                        },
                    'delete' => function ($url, $model) {
                            if (Yii::$app->user->can('deleteProduct')) {
                                return Html::a('<i class="bi bi-trash"></i>', $url, [
                                    'title' => 'Eliminar',
                                    'class' => 'btn btn-sm btn-danger',
                                    'data' => [
                                        'confirm' => '¿Estás seguro de eliminar este producto?',
                                        'method' => 'post',
                                    ],
                                ]);
                            }
                            return '';
                        },
                ],
            ],
        ],
        'summary' => '<div class="d-flex justify-content-between align-items-center my-3">
            <span class="text-muted"><i class="bi bi-info-circle"></i> Mostrando {begin}-{end} de {totalCount} productos</span>
        </div>',
        'emptyText' => '<div class="alert alert-info"><i class="bi bi-inbox"></i> No se encontraron productos.</div>',
    ]); ?>

</div>