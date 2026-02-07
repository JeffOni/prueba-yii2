<?php

use common\models\User;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\User $model */
/** @var array $roleNames */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-person-circle"></i> <?= Html::encode($this->title) ?></h1>
        <div class="btn-group" role="group">
            <?php if (Yii::$app->user->can('updateUser')): ?>
                <?= Html::a('<i class="bi bi-pencil-fill"></i> Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?php endif; ?>
            <?php if (Yii::$app->user->can('deleteUser') && $model->id != 1): ?>
                <?= Html::a('<i class="bi bi-trash-fill"></i> Eliminar', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => '¿Está seguro de eliminar este usuario?',
                        'method' => 'post',
                    ],
                ]) ?>
            <?php endif; ?>
            <?= Html::a('<i class="bi bi-arrow-left-circle-fill"></i> Volver', ['index'], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle-fill"></i> Información del Usuario</h5>
                </div>
                <div class="card-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            [
                                'attribute' => 'username',
                                'format' => 'html',
                                'value' => '<i class="bi bi-person-fill"></i> ' . Html::encode($model->username),
                            ],
                            [
                                'attribute' => 'email',
                                'format' => 'html',
                                'value' => '<i class="bi bi-envelope-fill"></i> ' . Html::encode($model->email),
                            ],
                            [
                                'attribute' => 'status',
                                'format' => 'html',
                                'value' => function ($model) {
                                    $statusLabels = [
                                        User::STATUS_ACTIVE => '<span class="badge bg-success"><i class="bi bi-check-circle-fill"></i> Activo</span>',
                                        User::STATUS_INACTIVE => '<span class="badge bg-warning"><i class="bi bi-pause-circle-fill"></i> Inactivo</span>',
                                        User::STATUS_DELETED => '<span class="badge bg-danger"><i class="bi bi-x-circle-fill"></i> Eliminado</span>',
                                    ];
                                    return $statusLabels[$model->status] ?? '<span class="badge bg-secondary">Desconocido</span>';
                                },
                            ],
                            [
                                'attribute' => 'created_at',
                                'format' => ['datetime', 'php:d/m/Y H:i:s'],
                                'label' => 'Fecha de Creación',
                            ],
                            [
                                'attribute' => 'updated_at',
                                'format' => ['datetime', 'php:d/m/Y H:i:s'],
                                'label' => 'Última Actualización',
                            ],
                        ],
                    ]) ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-shield-fill-check"></i> Roles y Permisos</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($roleNames)): ?>
                        <div class="alert alert-warning" role="alert">
                            <i class="bi bi-exclamation-triangle-fill"></i> Este usuario no tiene ningún rol asignado.
                        </div>
                    <?php else: ?>
                        <h6>Roles asignados:</h6>
                        <ul class="list-group mb-3">
                            <?php foreach ($roleNames as $roleName): ?>
                                <?php
                                $badgeClass = [
                                    'Admin' => 'bg-danger',
                                    'Editor' => 'bg-warning',
                                    'Viewer' => 'bg-info',
                                ];
                                $class = $badgeClass[$roleName] ?? 'bg-secondary';
                                ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?= Html::encode($roleName) ?>
                                    <span class="badge <?= $class ?> rounded-pill"><?= Html::encode($roleName) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>

                        <?php
                        // Mostrar permisos del usuario
                        $auth = Yii::$app->authManager;
                        $permissions = [];
                        foreach ($roleNames as $roleName) {
                            $role = $auth->getRole($roleName);
                            if ($role) {
                                $rolePermissions = $auth->getPermissionsByRole($roleName);
                                foreach ($rolePermissions as $permission) {
                                    $permissions[$permission->name] = $permission->description ?: $permission->name;
                                }
                            }
                        }
                        ?>

                        <?php if (!empty($permissions)): ?>
                            <h6>Permisos heredados:</h6>
                            <div class="list-group">
                                <?php foreach ($permissions as $permissionName => $description): ?>
                                    <div class="list-group-item list-group-item-action">
                                        <small class="text-muted"><i class="bi bi-key-fill"></i> <?= Html::encode($permissionName) ?></small>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (Yii::$app->user->can('manageRoles')): ?>
                <div class="card shadow-sm mt-3">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="mb-0"><i class="bi bi-gear-fill"></i> Acciones Rápidas</h6>
                    </div>
                    <div class="card-body">
                        <?= Html::a('<i class="bi bi-shield-fill-plus"></i> Cambiar Rol', ['update', 'id' => $model->id], ['class' => 'btn btn-sm btn-warning btn-block w-100']) ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>
