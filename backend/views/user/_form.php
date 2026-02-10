<?php

use common\models\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\User $model */
/** @var yii\widgets\ActiveForm $form */
/** @var string|null $currentRole */

// Obtener lista de roles disponibles
$auth = Yii::$app->authManager;
$roles = $auth->getRoles();
$roleList = [];
foreach ($roles as $role) {
    $roleList[$role->name] = $role->name;
}

?>

<div class="user-form">

    <?php $form = ActiveForm::begin([
        'id' => 'user-form',
        'options' => ['class' => 'needs-validation'],
    ]); ?>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-person-fill"></i> Información Básica</h5>
                </div>
                <div class="card-body">
                    <?= $form->field($model, 'username')->textInput([
                        'maxlength' => true,
                        'placeholder' => 'Ingrese el nombre de usuario',
                        'class' => 'form-control',
                    ])->label('<i class="bi bi-person-badge"></i> Nombre de Usuario') ?>

                    <?= $form->field($model, 'email')->textInput([
                        'maxlength' => true,
                        'type' => 'email',
                        'placeholder' => 'usuario@ejemplo.com',
                        'class' => 'form-control',
                    ])->label('<i class="bi bi-envelope"></i> Correo Electrónico') ?>

                    <?= $form->field($model, 'password')->passwordInput([
                        'maxlength' => true,
                        'placeholder' => $model->isNewRecord ? 'Mínimo 6 caracteres' : 'Dejar en blanco para mantener la actual',
                        'class' => 'form-control',
                    ])->label('<i class="bi bi-key-fill"></i> Contraseña')
                        ->hint($model->isNewRecord ? 'Mínimo 6 caracteres' : 'Dejar en blanco si no desea cambiarla') ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-shield-fill-check"></i> Rol y Permisos</h5>
                </div>
                <div class="card-body">
                    <?php
                    // Campo de selección de rol (radio buttons para mejor UX)
                    echo '<div class="form-group">';
                    echo Html::label('<i class="bi bi-shield-fill"></i> Rol del Usuario', 'user-role', ['class' => 'form-label']);

                    $selectedRole = $currentRole ?? null;
                    if ($model->isNewRecord && !$selectedRole) {
                        $selectedRole = 'Viewer'; // Rol por defecto para nuevos usuarios
                    }

                    echo Html::radioList('User[role]', $selectedRole, $roleList, [
                        'item' => function($index, $label, $name, $checked, $value) {
                            $checkedAttr = $checked ? 'checked' : '';
                            $badgeClass = [
                                'Admin' => 'bg-danger',
                                'Editor' => 'bg-warning text-dark',
                                'Viewer' => 'bg-info',
                            ][$value] ?? 'bg-secondary';

                            $descriptions = [
                                'Admin' => 'Acceso completo al sistema (productos + usuarios + roles)',
                                'Editor' => 'Puede crear y modificar productos',
                                'Viewer' => 'Solo puede ver productos',
                            ];

                            return '<div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="' . $name . '" id="role-' . $value . '" value="' . $value . '" ' . $checkedAttr . '>
                                <label class="form-check-label w-100" for="role-' . $value . '">
                                    <span class="badge ' . $badgeClass . ' fs-6">' . Html::encode($label) . '</span>
                                    <br><small class="text-muted">' . $descriptions[$value] . '</small>
                                </label>
                            </div>';
                        },
                    ]);

                    echo '<div class="form-text">
                        <small class="text-muted">
                            <i class="bi bi-info-circle"></i> Los roles son jerárquicos y mutuamente exclusivos.
                        </small>
                    </div>';
                    echo '</div>';
                    ?>

                    <?= $form->field($model, 'status')->dropDownList(User::getStatusList(), [
                        'class' => 'form-select',
                    ])->label('<i class="bi bi-toggle-on"></i> Estado del Usuario') ?>

                    <div class="alert alert-info mt-3" role="alert">
                        <i class="bi bi-info-circle-fill"></i>
                        <strong>Nota:</strong> Los cambios de rol serán registrados en el sistema de auditoría.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <?= Html::a(
                '<i class="bi bi-x-circle-fill"></i> Cancelar',
                ['index'],
                ['class' => 'btn btn-secondary']
            ) ?>
            <?= Html::submitButton(
                $model->isNewRecord
                ? '<i class="bi bi-check-circle-fill"></i> Crear Usuario'
                : '<i class="bi bi-check-circle-fill"></i> Guardar Cambios',
                ['class' => 'btn btn-success']
            ) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
// JavaScript para validación del lado del cliente
$this->registerJs("
    // Validación de contraseña
    $('#user-form').on('beforeSubmit', function(e) {
        var password = $('#user-password').val();
        var isNewRecord = " . ($model->isNewRecord ? 'true' : 'false') . ";

        if (isNewRecord && password.length < 6) {
            alert('La contraseña debe tener al menos 6 caracteres.');
            return false;
        }

        if (!isNewRecord && password.length > 0 && password.length < 6) {
            alert('Si desea cambiar la contraseña, debe tener al menos 6 caracteres.');
            return false;
        }

        return true;
    });
");
?>