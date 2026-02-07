<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%audit_log}}`.
 */
class m260206_223353_create_audit_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%audit_log}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->comment('Usuario que realizó la acción'),
            'action' => $this->string(100)->notNull()->comment('Acción realizada (assign_role, revoke_role, etc)'),
            'table_name' => $this->string(100)->comment('Tabla afectada'),
            'record_id' => $this->integer()->comment('ID del registro afectado'),
            'old_value' => $this->text()->comment('Valor anterior (JSON)'),
            'new_value' => $this->text()->comment('Valor nuevo (JSON)'),
            'ip_address' => $this->string(45)->comment('IP del usuario'),
            'user_agent' => $this->string(255)->comment('Navegador/dispositivo'),
            'created_at' => $this->integer()->notNull(),
        ], $tableOptions);

        // Índices para consultas rápidas
        $this->createIndex('idx-audit-user', '{{%audit_log}}', 'user_id');
        $this->createIndex('idx-audit-action', '{{%audit_log}}', 'action');
        $this->createIndex('idx-audit-table', '{{%audit_log}}', 'table_name');
        $this->createIndex('idx-audit-created', '{{%audit_log}}', 'created_at');

        // Foreign key a users
        $this->addForeignKey(
            'fk-audit-user',
            '{{%audit_log}}',
            'user_id',
            '{{%user}}',
            'id',
            'SET NULL',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%audit_log}}');
    }
}
