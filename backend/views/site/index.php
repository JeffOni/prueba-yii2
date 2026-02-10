<?php

use yii\helpers\Html;

/** @var yii\web\View $this */

$this->title = 'Panel de Administración';
?>
<div class="site-index">
    <!-- Header de Bienvenida -->
    <div class="p-4 mb-4 bg-light rounded-3 border">
        <div class="container-fluid">
            <h1 class="display-5 fw-bold">
                <i class="bi bi-speedometer2"></i> Panel de Administración
            </h1>
            <p class="fs-5">Bienvenido al sistema de gestión de inventario</p>
            <p class="text-muted">
                <i class="bi bi-person-circle"></i> Usuario: <strong><?= Yii::$app->user->identity->username ?></strong>
            </p>
        </div>
    </div>

    <!-- Accesos Rápidos -->
    <div class="row g-4 mb-4">
        <?php if (Yii::$app->user->can('viewProduct')): ?>
            <div class="col-md-6 col-lg-3">
                <div class="card text-center h-100 shadow-sm border-primary">
                    <div class="card-body">
                        <i class="bi bi-box-seam display-1 text-primary"></i>
                        <h5 class="card-title mt-3">Productos</h5>
                        <p class="card-text text-muted">Gestión de inventario</p>
                        <?= Html::a('Ver Productos <i class="bi bi-arrow-right"></i>', ['/product/index'], [
                            'class' => 'btn btn-primary btn-sm'
                        ]) ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (Yii::$app->user->can('viewUser')): ?>
            <div class="col-md-6 col-lg-3">
                <div class="card text-center h-100 shadow-sm border-success">
                    <div class="card-body">
                        <i class="bi bi-people display-1 text-success"></i>
                        <h5 class="card-title mt-3">Usuarios</h5>
                        <p class="card-text text-muted">Gestión de usuarios</p>
                        <?= Html::a('Ver Usuarios <i class="bi bi-arrow-right"></i>', ['/user/index'], [
                            'class' => 'btn btn-success btn-sm'
                        ]) ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (Yii::$app->user->can('createProduct')): ?>
            <div class="col-md-6 col-lg-3">
                <div class="card text-center h-100 shadow-sm border-warning">
                    <div class="card-body">
                        <i class="bi bi-plus-circle display-1 text-warning"></i>
                        <h5 class="card-title mt-3">Nuevo Producto</h5>
                        <p class="card-text text-muted">Agregar al inventario</p>
                        <?= Html::a('Crear Producto <i class="bi bi-arrow-right"></i>', ['/product/create'], [
                            'class' => 'btn btn-warning btn-sm'
                        ]) ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (Yii::$app->user->can('createUser')): ?>
            <div class="col-md-6 col-lg-3">
                <div class="card text-center h-100 shadow-sm border-info">
                    <div class="card-body">
                        <i class="bi bi-person-plus display-1 text-info"></i>
                        <h5 class="card-title mt-3">Nuevo Usuario</h5>
                        <p class="card-text text-muted">Registrar usuario</p>
                        <?= Html::a('Crear Usuario <i class="bi bi-arrow-right"></i>', ['/user/create'], [
                            'class' => 'btn btn-info btn-sm'
                        ]) ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Estadísticas Rápidas -->
    <?php if (Yii::$app->user->can('viewProduct')): ?>
        <?php
        // Consultas corregidas según especificaciones
        $totalProducts = \common\models\Product::find()->count();

        // Activos en stock: productos activos (status=1) con stock > 0
        $activeInStock = \common\models\Product::find()
            ->where(['status' => 1])
            ->andWhere(['>', 'stock', 0])
            ->count();

        // Stock bajo: productos activos (status=1) con stock <= 5 y > 0
        $lowStock = \common\models\Product::find()
            ->where(['status' => 1])
            ->andWhere(['>', 'stock', 0])
            ->andWhere(['<=', 'stock', 5])
            ->count();

        // Sin stock: todos los productos con stock = 0
        $outOfStock = \common\models\Product::find()
            ->where(['stock' => 0])
            ->count();

        // Inactivos: productos con status = 0
        $inactiveProducts = \common\models\Product::find()
            ->where(['status' => 0])
            ->count();
        ?>
        <div class="row g-3 mb-4">
            <div class="col-12">
                <h4><i class="bi bi-bar-chart"></i> Estadísticas del Inventario</h4>
            </div>
            <div class="col-lg col-md-4 col-sm-6">
                <div class="card bg-primary text-white shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="text-center">
                            <i class="bi bi-box-seam fs-1 mb-2"></i>
                            <h6 class="mb-2">Total</h6>
                            <h3 class="mb-0"><?= $totalProducts ?></h3>
                            <small>productos</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg col-md-4 col-sm-6">
                <div class="card bg-success text-white shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="text-center">
                            <i class="bi bi-check-circle fs-1 mb-2"></i>
                            <h6 class="mb-2">Activos en Stock</h6>
                            <h3 class="mb-0"><?= $activeInStock ?></h3>
                            <small>disponibles</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg col-md-4 col-sm-6">
                <div class="card bg-warning text-dark shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="text-center">
                            <i class="bi bi-exclamation-triangle fs-1 mb-2"></i>
                            <h6 class="mb-2">Stock Bajo (5)</h6>
                            <h3 class="mb-0"><?= $lowStock ?></h3>
                            <small>reabastecer</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg col-md-4 col-sm-6">
                <div class="card bg-danger text-white shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="text-center">
                            <i class="bi bi-x-circle fs-1 mb-2"></i>
                            <h6 class="mb-2">Sin Stock</h6>
                            <h3 class="mb-0"><?= $outOfStock ?></h3>
                            <small>agotados</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg col-md-4 col-sm-6">
                <div class="card bg-secondary text-white shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="text-center">
                            <i class="bi bi-dash-circle fs-1 mb-2"></i>
                            <h6 class="mb-2">Inactivos</h6>
                            <h3 class="mb-0"><?= $inactiveProducts ?></h3>
                            <small>deshabilitados</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Información del Sistema -->
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-shield-check"></i> Tu Rol y Permisos</h5>
                </div>
                <div class="card-body">
                    <?php
                    $auth = Yii::$app->authManager;
                    $roles = $auth->getRolesByUser(Yii::$app->user->id);
                    $permissions = [];
                    foreach ($roles as $role) {
                        $rolePermissions = $auth->getPermissionsByRole($role->name);
                        foreach ($rolePermissions as $permission) {
                            $permissions[$permission->name] = $permission->description ?: $permission->name;
                        }
                    }
                    ?>
                    <p><strong>Rol:</strong>
                        <?php foreach ($roles as $role): ?>
                            <?php
                            $badgeClass = [
                                'Admin' => 'bg-danger',
                                'Editor' => 'bg-warning text-dark',
                                'Viewer' => 'bg-info',
                            ][$role->name] ?? 'bg-secondary';
                            ?>
                            <span class="badge <?= $badgeClass ?>"><?= $role->name ?></span>
                        <?php endforeach; ?>
                    </p>
                    <?php if (!empty($permissions)): ?>
                        <p class="mb-1"><strong>Permisos:</strong></p>
                        <ul class="list-unstyled">
                            <?php foreach (array_slice($permissions, 0, 5) as $permName => $permDesc): ?>
                                <li><i class="bi bi-check-circle text-success"></i> <?= $permName ?></li>
                            <?php endforeach; ?>
                            <?php if (count($permissions) > 5): ?>
                                <li class="text-muted"><small>+ <?= count($permissions) - 5 ?> más...</small></li>
                            <?php endif; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Información del Sistema</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="bi bi-hdd-stack text-primary"></i>
                            <strong>Framework:</strong> Yii <?= Yii::getVersion() ?>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-filetype-php text-success"></i>
                            <strong>PHP:</strong> <?= PHP_VERSION ?>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-database text-info"></i>
                            <strong>Base de Datos:</strong> MySQL
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-shield-lock text-warning"></i>
                            <strong>Autenticación:</strong> RBAC + JWT API
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-file-earmark-code text-danger"></i>
                            <strong>Documentación API:</strong>
                            <a href="/API_DOCUMENTATION.md" target="_blank">Ver docs</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>