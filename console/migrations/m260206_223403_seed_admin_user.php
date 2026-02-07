<?php

use yii\db\Migration;
use common\models\User;

/**
 * Seed usuario administrador por defecto (RF-01)
 * Credenciales:
 * - Username: admin
 * - Email: admin@example.com
 * - Password: Admin123!
 */
class m260206_223403_seed_admin_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Verificar si ya existe el usuario admin
        if (User::findOne(['username' => 'admin'])) {
            echo "Usuario admin ya existe. Saltando...\n";
            return true;
        }

        // Crear usuario admin
        $user = new User();
        $user->username = 'admin';
        $user->email = 'admin@example.com';
        $user->setPassword('Admin123!'); // Password encriptado con bcrypt
        $user->generateAuthKey();
        $user->status = User::STATUS_ACTIVE; // 10 = activo

        if ($user->save()) {
            echo "Usuario admin creado exitosamente.\n";
            echo "Username: admin\n";
            echo "Password: Admin123!\n";
            echo "Email: admin@example.com\n";
        } else {
            echo "Error al crear usuario admin:\n";
            print_r($user->errors);
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%user}}', ['username' => 'admin']);
        echo "Usuario admin eliminado.\n";
        return true;
    }
}
