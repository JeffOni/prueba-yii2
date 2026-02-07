<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Product;

/** @var yii\web\View $this */
/** @var common\models\Product $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="product-form">

    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'needs-validation'],
    ]); ?>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Información del Producto</h5>
                </div>
                <div class="card-body">
                    <?= $form->field($model, 'name')->textInput([
                        'maxlength' => true,
                        'placeholder' => 'Ej: Laptop Dell Inspiron 15',
                        'class' => 'form-control'
                    ]) ?>

                    <?= $form->field($model, 'sku')->textInput([
                        'maxlength' => true,
                        'placeholder' => 'Ej: TECH-001',
                        'class' => 'form-control'
                    ])->hint('Código único de identificación del producto') ?>

                    <?= $form->field($model, 'description')->textarea([
                        'rows' => 4,
                        'placeholder' => 'Descripción detallada del producto...',
                        'class' => 'form-control'
                    ]) ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Precio e Inventario</h5>
                </div>
                <div class="card-body">
                    <?= $form->field($model, 'price')->textInput([
                        'type' => 'number',
                        'step' => '0.01',
                        'min' => '0.01',
                        'placeholder' => '0.00',
                        'class' => 'form-control'
                    ])->hint('Precio en dólares') ?>

                    <?= $form->field($model, 'stock')->textInput([
                        'type' => 'number',
                        'min' => '0',
                        'placeholder' => '0',
                        'class' => 'form-control'
                    ])->hint('Cantidad disponible en inventario') ?>

                    <?= $form->field($model, 'status')->dropDownList(
                        Product::getStatusList(),
                        ['prompt' => '-- Seleccionar --', 'class' => 'form-select']
                    ) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between">
        <?= Html::a('<i class="bi bi-x-circle"></i> Cancelar', ['index'], [
            'class' => 'btn btn-secondary'
        ]) ?>

        <?= Html::submitButton('<i class="bi bi-check-circle"></i> ' . ($model->isNewRecord ? 'Crear Producto' : 'Guardar Cambios'), [
            'class' => 'btn btn-success'
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>