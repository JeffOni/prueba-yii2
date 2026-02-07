<?php

use yii\db\Migration;

class m260206_223424_seed_sample_products extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $now = time();

        $products = [
            [
                'name' => 'Laptop Dell Inspiron 15',
                'description' => 'Laptop para trabajo y estudio, procesador Intel Core i5, 8GB RAM, 256GB SSD',
                'sku' => 'TECH-001',
                'price' => 799.99,
                'stock' => 15,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Mouse Inalámbrico Logitech',
                'description' => 'Mouse inalámbrico ergonómico con conexión USB, batería de larga duración',
                'sku' => 'TECH-002',
                'price' => 25.99,
                'stock' => 50,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Teclado Mecánico RGB',
                'description' => 'Teclado mecánico gaming con iluminación RGB personalizable',
                'sku' => 'TECH-003',
                'price' => 89.99,
                'stock' => 0,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Monitor Samsung 24 pulgadas',
                'description' => 'Monitor Full HD 1080p, 75Hz, panel VA, ideal para oficina',
                'sku' => 'OFF-004',
                'price' => 159.99,
                'stock' => 8,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Silla Ergonómica de Oficina',
                'description' => 'Silla con soporte lumbar ajustable, reposabrazos y altura regulable',
                'sku' => 'OFF-005',
                'price' => 249.99,
                'stock' => 12,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Auriculares Bluetooth Sony',
                'description' => 'Auriculares con cancelación de ruido activa, 30 horas de batería',
                'sku' => 'AUDIO-006',
                'price' => 199.99,
                'stock' => 25,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Webcam HD Logitech C920',
                'description' => 'Cámara web Full HD 1080p con micrófono estéreo integrado',
                'sku' => 'TECH-007',
                'price' => 79.99,
                'stock' => 3,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Impresora HP LaserJet',
                'description' => 'Impresora láser monocromática, velocidad 28 ppm, conexión WiFi',
                'sku' => 'OFF-008',
                'price' => 299.99,
                'stock' => 5,
                'status' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Disco Duro Externo 2TB',
                'description' => 'Disco duro portátil USB 3.0, compatible con Windows y Mac',
                'sku' => 'STOR-009',
                'price' => 69.99,
                'stock' => 40,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Router WiFi 6 TP-Link',
                'description' => 'Router de última generación WiFi 6, doble banda, 4 antenas',
                'sku' => 'NET-010',
                'price' => 129.99,
                'stock' => 18,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Cable HDMI 2.1 Premium',
                'description' => 'Cable HDMI 2.1 de alta velocidad, soporte 4K@120Hz, 2 metros',
                'sku' => 'ACC-011',
                'price' => 15.99,
                'stock' => 100,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Laptop Gamer ASUS ROG',
                'description' => 'Laptop gaming con RTX 3060, Intel i7, 16GB RAM, 512GB SSD',
                'sku' => 'GAME-012',
                'price' => 1299.99,
                'stock' => 2,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Mousepad Gaming XXL',
                'description' => 'Alfombrilla de ratón grande 90x40cm, superficie suave',
                'sku' => 'ACC-013',
                'price' => 19.99,
                'stock' => 60,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Hub USB-C 7 en 1',
                'description' => 'Adaptador multipuerto con HDMI, USB 3.0, lector SD, carga PD',
                'sku' => 'ACC-014',
                'price' => 45.99,
                'stock' => 0,
                'status' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Limpiador de Pantallas Kit',
                'description' => 'Kit de limpieza para pantallas con spray y microfibra',
                'sku' => 'CLEAN-015',
                'price' => 9.99,
                'stock' => 75,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($products as $product) {
            $this->insert('{{%product}}', $product);
        }

        echo "15 productos de ejemplo creados exitosamente.\n";
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Eliminar los productos seed por sus SKUs únicos
        $skus = [
            'TECH-001',
            'TECH-002',
            'TECH-003',
            'OFF-004',
            'OFF-005',
            'AUDIO-006',
            'TECH-007',
            'OFF-008',
            'STOR-009',
            'NET-010',
            'ACC-011',
            'GAME-012',
            'ACC-013',
            'ACC-014',
            'CLEAN-015'
        ];

        foreach ($skus as $sku) {
            $this->delete('{{%product}}', ['sku' => $sku]);
        }

        echo "15 productos de ejemplo eliminados.\n";
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260206_223424_seed_sample_products cannot be reverted.\n";

        return false;
    }
    */
}
