<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\User $model */

$this->title = 'Crear Usuario';
$this->params['breadcrumbs'][] = ['label' => 'Usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-person-plus-fill"></i> <?= Html::encode($this->title) ?></h1>
    </div>

    <div class="alert alert-info" role="alert">
        <i class="bi bi-info-circle-fill"></i>
        <strong>Información:</strong> Está creando un nuevo usuario en el sistema.
        Asegúrese de asignar el rol apropiado según las responsabilidades del usuario.
    </div>

    <?= $this->render('_form', [
        'model' => $model,
        'currentRole' => null,
    ]) ?>

</div>
