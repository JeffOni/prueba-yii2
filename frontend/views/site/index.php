<?php

/** @var yii\web\View $this */

$this->title = 'Sistema de Inventario - Yii2';
?>
<div class="site-index">
    <div class="p-5 mb-4 bg-primary bg-gradient rounded-3 text-white">
        <div class="container-fluid py-5 text-center">
            <h1 class="display-4 fw-bold">
                <i class="bi bi-box-seam"></i> Sistema de Inventario
            </h1>
            <p class="fs-5 fw-light">Sistema de gestión de productos con control de acceso basado en roles (RBAC)</p>
            <p class="mt-4">
                <a class="btn btn-lg btn-light me-2" href="/backend/web">
                    <i class="bi bi-speedometer2"></i> Panel de Administración
                </a>
                <a class="btn btn-lg btn-outline-light" href="<?= \yii\helpers\Url::to(['/site/about']) ?>">
                    <i class="bi bi-info-circle"></i> Acerca del Sistema
                </a>
            </p>
        </div>
    </div>

    <div class="body-content">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h2 class="card-title">
                            <i class="bi bi-shield-check text-success"></i> Control de Acceso (RBAC)
                        </h2>
                        <p class="card-text">
                            Sistema de autenticación con tres niveles de roles: <strong>Admin</strong> (acceso
                            completo),
                            <strong>Editor</strong> (gestión de productos) y <strong>Viewer</strong> (solo lectura).
                        </p>
                        <ul class="list-unstyled">
                            <li><i class="bi bi-check-circle text-success"></i> Gestión de usuarios y roles</li>
                            <li><i class="bi bi-check-circle text-success"></i> Auditoría de acciones</li>
                            <li><i class="bi bi-check-circle text-success"></i> Protección CSRF</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h2 class="card-title">
                            <i class="bi bi-database text-primary"></i> Gestión de Productos
                        </h2>
                        <p class="card-text">
                            CRUD completo de productos con filtros avanzados, paginación y validaciones.
                            Interfaz responsive construida con Bootstrap 5.
                        </p>
                        <ul class="list-unstyled">
                            <li><i class="bi bi-check-circle text-success"></i> Filtros por nombre, SKU, precio y stock
                            </li>
                            <li><i class="bi bi-check-circle text-success"></i> Validación de datos en servidor</li>
                            <li><i class="bi bi-check-circle text-success"></i> Paginación optimizada (20 items/página)
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h2 class="card-title">
                            <i class="bi bi-code-square text-danger"></i> REST API (JWT)
                        </h2>
                        <p class="card-text">
                            API RESTful con autenticación JWT para integración con aplicaciones externas.
                            Documentación completa disponible.
                        </p>
                        <ul class="list-unstyled">
                            <li><i class="bi bi-check-circle text-success"></i> Autenticación con tokens JWT</li>
                            <li><i class="bi bi-check-circle text-success"></i> Endpoints para productos y usuarios</li>
                            <li><i class="bi bi-check-circle text-success"></i> Respuestas en formato JSON</li>
                        </ul>
                        <a href="/API_DOCUMENTATION.md" class="btn btn-sm btn-outline-danger mt-2" target="_blank">
                            <i class="bi bi-file-text"></i> Ver Documentación API
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <div class="card bg-light">
                    <div class="card-body">
                        <h3 class="card-title">
                            <i class="bi bi-gear"></i> Tecnologías Implementadas
                        </h3>
                        <div class="row text-center mt-3">
                            <div class="col-md-3 col-6 mb-3">
                                <div class="p-3 border rounded">
                                    <i class="bi bi-layers-fill fs-1 text-primary"></i>
                                    <p class="mb-0 mt-2"><strong>Yii2 Advanced</strong></p>
                                    <small class="text-muted">Framework PHP</small>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="p-3 border rounded">
                                    <i class="bi bi-database-fill fs-1 text-success"></i>
                                    <p class="mb-0 mt-2"><strong>MySQL 8.0</strong></p>
                                    <small class="text-muted">Base de Datos</small>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="p-3 border rounded">
                                    <i class="bi bi-boxes fs-1 text-info"></i>
                                    <p class="mb-0 mt-2"><strong>Docker</strong></p>
                                    <small class="text-muted">Contenedores</small>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="p-3 border rounded">
                                    <i class="bi bi-bootstrap-fill fs-1 text-purple"></i>
                                    <p class="mb-0 mt-2"><strong>Bootstrap 5</strong></p>
                                    <small class="text-muted">UI Framework</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12 text-center">
                <p class="text-muted">
                    <i class="bi bi-github"></i> Proyecto desarrollado con Yii2 Framework •
                    Prueba Técnica de Desarrollo
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    .text-purple {
        color: #7952b3;
    }
</style>