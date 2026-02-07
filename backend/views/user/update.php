<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\User $model */
/** @var string|null $currentRole */

$this->title = 'Actualizar Usuario: ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="user-update">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-pencil-fill"></i> <?= Html::encode($this->title) ?></h1>
        <?= Html::a('<i class="bi bi-eye-fill"></i> Ver Detalles', ['view', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
    </div>

    <div class="alert alert-warning" role="alert">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <strong>Atención:</strong> Está modificando la información de un usuario existente.
        <?php if ($model->id == 1): ?>
            <br><strong>Este es el usuario administrador principal del sistema.</strong>
        <?php endif; ?>
        Cualquier cambio en el rol será registrado en el sistema de auditoría.
    </div>

    <?= $this->render('_form', [
        'model' => $model,
        'currentRole' => $currentRole,
    ]) ?>

</div>
