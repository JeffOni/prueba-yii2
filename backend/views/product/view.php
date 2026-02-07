<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Product;

/** @var yii\web\View $this */
/** @var common\models\Product $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Productos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="product-view">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= Html::encode($this->title) ?></h1>

        <div class="btn-group" role="group">
            <?php if (Yii::$app->user->can('updateProduct')): ?>
                <?= Html::a('<i class="bi bi-pencil"></i> Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?php endif; ?>

            <?php if (Yii::$app->user->can('deleteProduct')): ?>
                <?= Html::a('<i class="bi bi-trash"></i> Eliminar', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => '¿Estás seguro de que deseas eliminar este producto?',
                        'method' => 'post',
                    ],
                ]) ?>
            <?php endif; ?>

            <?= Html::a('<i class="bi bi-arrow-left"></i> Volver', ['index'], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <?= DetailView::widget([
                'model' => $model,
                'options' => ['class' => 'table table-striped table-bordered detail-view'],
                'attributes' => [
                    'id',
                    'name',
                    'description:ntext',
                    'sku',
                    [
                        'attribute' => 'price',
                        'format' => 'html',
                        'value' => function ($model) {
                        return '<span class="badge bg-primary fs-5">' . $model->getFormattedPrice() . '</span>';
                    },
                    ],
                    [
                        'attribute' => 'stock',
                        'format' => 'html',
                        'value' => function ($model) {
                        if ($model->stock == 0) {
                            return '<span class="badge bg-danger fs-6">Sin stock (0 unidades)</span>';
                        } elseif ($model->stock <= 5) {
                            return '<span class="badge bg-warning text-dark fs-6">Stock bajo (' . $model->stock . ' unidades)</span>';
                        } else {
                            return '<span class="badge bg-success fs-6">' . $model->stock . ' unidades</span>';
                        }
                    },
                    ],
                    [
                        'attribute' => 'status',
                        'format' => 'html',
                        'value' => function ($model) {
                        if ($model->status == Product::STATUS_ACTIVE) {
                            return '<span class="badge bg-success fs-6">Activo</span>';
                        } else {
                            return '<span class="badge bg-secondary fs-6">Inactivo</span>';
                        }
                    },
                    ],
                    [
                        'attribute' => 'created_at',
                        'format' => ['datetime', 'php:d/m/Y H:i:s'],
                    ],
                    [
                        'attribute' => 'updated_at',
                        'format' => ['datetime', 'php:d/m/Y H:i:s'],
                    ],
                ],
            ]) ?>
        </div>
    </div>

</div>