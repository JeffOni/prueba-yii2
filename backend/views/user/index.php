<?php

use common\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\UserSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Gestión de Usuarios';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-people-fill"></i> <?= Html::encode($this->title) ?></h1>
        <?php if (Yii::$app->user->can('createUser')): ?>
            <?= Html::a('<i class="bi bi-person-plus-fill"></i> Crear Usuario', ['create'], ['class' => 'btn btn-success']) ?>
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

            [
                'attribute' => 'id',
                'headerOptions' => ['style' => 'width:80px'],
            ],
            [
                'attribute' => 'username',
                'format' => 'html',
                'value' => function ($model) {
                        return Html::a(
                            '<i class="bi bi-person-circle"></i> ' . Html::encode($model->username),
                            ['view', 'id' => $model->id],
                            ['class' => 'text-decoration-none']
                        );
                    },
            ],
            [
                'attribute' => 'email',
                'format' => 'html',
                'value' => function ($model) {
                        return '<i class="bi bi-envelope"></i> ' . Html::encode($model->email);
                    },
            ],
            [
                'attribute' => 'role',
                'label' => 'Rol',
                'format' => 'html',
                'value' => function ($model) {
                        $auth = Yii::$app->authManager;
                        $roles = $auth->getRolesByUser($model->id);

                        if (empty($roles)) {
                            return '<span class="badge bg-secondary">Sin Rol</span>';
                        }

                        $roleNames = array_keys($roles);
                        $roleName = $roleNames[0];

                        $badgeClass = [
                            'Admin' => 'bg-danger',
                            'Editor' => 'bg-warning',
                            'Viewer' => 'bg-info',
                        ];

                        $class = $badgeClass[$roleName] ?? 'bg-secondary';
                        return '<span class="badge ' . $class . '">' . Html::encode($roleName) . '</span>';
                    },
                'filter' => [
                    'Admin' => 'Admin',
                    'Editor' => 'Editor',
                    'Viewer' => 'Viewer',
                ],
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
                'filter' => User::getStatusList(),
            ],
            [
                'attribute' => 'created_at',
                'format' => 'datetime',
                'headerOptions' => ['style' => 'width:180px'],
            ],

            [
                'class' => ActionColumn::class,
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                            return Yii::$app->user->can('viewUser')
                                ? Html::a('<i class="bi bi-eye-fill"></i>', $url, [
                                    'class' => 'btn btn-sm btn-info',
                                    'title' => 'Ver',
                                    'data-bs-toggle' => 'tooltip',
                                ])
                                : '';
                        },
                    'update' => function ($url, $model, $key) {
                            return Yii::$app->user->can('updateUser')
                                ? Html::a('<i class="bi bi-pencil-fill"></i>', $url, [
                                    'class' => 'btn btn-sm btn-primary',
                                    'title' => 'Editar',
                                    'data-bs-toggle' => 'tooltip',
                                ])
                                : '';
                        },
                    'delete' => function ($url, $model, $key) {
                            // No permitir eliminar al usuario admin original (ID 1)
                            if ($model->id == 1) {
                                return '';
                            }

                            return Yii::$app->user->can('deleteUser')
                                ? Html::a('<i class="bi bi-trash-fill"></i>', $url, [
                                    'class' => 'btn btn-sm btn-danger',
                                    'title' => 'Eliminar',
                                    'data-bs-toggle' => 'tooltip',
                                    'data-confirm' => '¿Está seguro de eliminar este usuario?',
                                    'data-method' => 'post',
                                ])
                                : '';
                        },
                ],
                'urlCreator' => function ($action, User $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'id' => $model->id]);
                    }
            ],
        ],
        'summary' => '<div class="d-flex justify-content-between align-items-center my-3">
            <span class="text-muted"><i class="bi bi-info-circle"></i> Mostrando {begin}-{end} de {totalCount} usuarios</span>
        </div>',
        'emptyText' => '<div class="alert alert-info"><i class="bi bi-inbox"></i> No se encontraron usuarios.</div>',
    ]); ?>

</div>

<?php
// Habilitar tooltips de Bootstrap
$this->registerJs("
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle=\"tooltip\"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
");
?>