<?php

use yii\db\Migration;

/**
 * Seeder de roles y permisos para RBAC (RF-03)
 *
 * ESTRUCTURA:
 *
 * PERMISOS (lo que se puede hacer):
 * - Productos: createProduct, updateProduct, deleteProduct, viewProduct
 * - Usuarios: createUser, updateUser, deleteUser, viewUser
 * - Roles: manageRoles
 *
 * ROLES (quién tiene qué permisos):
 * - Admin: TODOS los permisos
 * - Editor: Puede gestionar productos (crear, editar, ver) pero NO eliminar
 * - Viewer: Solo puede VER productos
 */
class m260206_223415_seed_rbac_data extends Migration
{
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        // ==============================================================
        // 1. CREAR PERMISOS PARA PRODUCTOS (RF-06)
        // ==============================================================

        // Permiso: Ver productos
        $viewProduct = $auth->createPermission('viewProduct');
        $viewProduct->description = 'Ver listado y detalle de productos';
        $auth->add($viewProduct);

        // Permiso: Crear productos
        $createProduct = $auth->createPermission('createProduct');
        $createProduct->description = 'Crear nuevos productos';
        $auth->add($createProduct);

        // Permiso: Actualizar productos
        $updateProduct = $auth->createPermission('updateProduct');
        $updateProduct->description = 'Editar productos existentes';
        $auth->add($updateProduct);

        // Permiso: Eliminar productos
        $deleteProduct = $auth->createPermission('deleteProduct');
        $deleteProduct->description = 'Eliminar productos';
        $auth->add($deleteProduct);

        // ==============================================================
        // 2. CREAR PERMISOS PARA USUARIOS (RF-02)
        // ==============================================================

        $viewUser = $auth->createPermission('viewUser');
        $viewUser->description = 'Ver listado de usuarios';
        $auth->add($viewUser);

        $createUser = $auth->createPermission('createUser');
        $createUser->description = 'Crear nuevos usuarios';
        $auth->add($createUser);

        $updateUser = $auth->createPermission('updateUser');
        $updateUser->description = 'Editar usuarios existentes';
        $auth->add($updateUser);

        $deleteUser = $auth->createPermission('deleteUser');
        $deleteUser->description = 'Eliminar usuarios';
        $auth->add($deleteUser);

        // ==============================================================
        // 3. CREAR PERMISO PARA GESTIONAR ROLES (RF-03, RF-04)
        // ==============================================================

        $manageRoles = $auth->createPermission('manageRoles');
        $manageRoles->description = 'Gestionar roles y asignarlos a usuarios';
        $auth->add($manageRoles);

        // ==============================================================
        // 4. CREAR ROL: VIEWER (Solo puede VER)
        // ==============================================================

        $viewer = $auth->createRole('Viewer');
        $viewer->description = 'Usuario que solo puede visualizar productos';
        $auth->add($viewer);

        // Asignar permisos al Viewer: SOLO ver productos
        $auth->addChild($viewer, $viewProduct);

        // ==============================================================
        // 5. CREAR ROL: EDITOR (Puede gestionar productos)
        // ==============================================================

        $editor = $auth->createRole('Editor');
        $editor->description = 'Usuario que puede crear y editar productos';
        $auth->add($editor);

        // Asignar permisos al Editor:
        $auth->addChild($editor, $viewProduct);    // Puede ver
        $auth->addChild($editor, $createProduct);  // Puede crear
        $auth->addChild($editor, $updateProduct);  // Puede editar
        // NOTA: NO puede eliminar productos ni gestionar usuarios

        // ==============================================================
        // 6. CREAR ROL: ADMIN (Puede hacer TODO)
        // ==============================================================

        $admin = $auth->createRole('Admin');
        $admin->description = 'Administrador con acceso total al sistema';
        $auth->add($admin);

        // Admin hereda TODOS los permisos del Editor
        $auth->addChild($admin, $editor);

        // Agregar permisos adicionales exclusivos del Admin:
        $auth->addChild($admin, $deleteProduct);  // Puede eliminar productos
        $auth->addChild($admin, $viewUser);       // Gestión de usuarios
        $auth->addChild($admin, $createUser);
        $auth->addChild($admin, $updateUser);
        $auth->addChild($admin, $deleteUser);
        $auth->addChild($admin, $manageRoles);    // Gestión de roles

        // ==============================================================
        // 7. ASIGNAR ROL ADMIN AL USUARIO ADMIN (RF-01)
        // ==============================================================

        // Buscar el usuario admin que creamos en la migration anterior
        $adminUser = \common\models\User::findOne(['username' => 'admin']);

        if ($adminUser) {
            $auth->assign($admin, $adminUser->id);
            echo "✓ Rol 'Admin' asignado al usuario 'admin'\n";
        } else {
            echo "⚠ Usuario 'admin' no encontrado. Debes asignar el rol manualmente.\n";
        }

        echo "\n=== RBAC inicializado correctamente ===\n";
        echo "Roles creados: Admin, Editor, Viewer\n";
        echo "Permisos creados: 9 permisos en total\n\n";

        return true;
    }

    public function safeDown()
    {
        $auth = Yii::$app->authManager;

        // Eliminar roles (esto también elimina las asignaciones)
        $auth->remove($auth->getRole('Admin'));
        $auth->remove($auth->getRole('Editor'));
        $auth->remove($auth->getRole('Viewer'));

        // Eliminar permisos
        $auth->remove($auth->getPermission('viewProduct'));
        $auth->remove($auth->getPermission('createProduct'));
        $auth->remove($auth->getPermission('updateProduct'));
        $auth->remove($auth->getPermission('deleteProduct'));
        $auth->remove($auth->getPermission('viewUser'));
        $auth->remove($auth->getPermission('createUser'));
        $auth->remove($auth->getPermission('updateUser'));
        $auth->remove($auth->getPermission('deleteUser'));
        $auth->remove($auth->getPermission('manageRoles'));

        echo "RBAC eliminado.\n";
        return true;
    }
}
