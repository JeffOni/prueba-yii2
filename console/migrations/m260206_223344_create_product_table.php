<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%product}}`.
 */
class m260206_223344_create_product_table extends Migration
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

        $this->createTable('{{%product}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull()->comment('Nombre del producto'),
            'description' => $this->text()->comment('Descripción del producto'),
            'sku' => $this->string(100)->notNull()->unique()->comment('Código SKU único'),
            'price' => $this->decimal(10, 2)->notNull()->comment('Precio del producto'),
            'stock' => $this->integer()->notNull()->defaultValue(0)->comment('Cantidad en stock'),
            'status' => $this->smallInteger()->notNull()->defaultValue(1)->comment('1=activo, 0=inactivo'),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        // Crear índices para búsquedas rápidas (RF-07)
        $this->createIndex('idx-product-name', '{{%product}}', 'name');
        $this->createIndex('idx-product-sku', '{{%product}}', 'sku');
        $this->createIndex('idx-product-status', '{{%product}}', 'status');

        // Añadir constraint para stock no negativo (RF-06 - validación de negocio)
        $this->execute('ALTER TABLE {{%product}} ADD CONSTRAINT chk_stock_positive CHECK (stock >= 0)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%product}}');
    }
}
