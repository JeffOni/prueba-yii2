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
            [
                'name' => 'Memoria RAM DDR4 16GB',
                'description' => 'Memoria RAM Corsair Vengeance DDR4 3200MHz 16GB (2x8GB)',
                'sku' => 'COMP-016',
                'price' => 79.99,
                'stock' => 30,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'SSD NVMe 1TB Samsung',
                'description' => 'Disco SSD NVMe M.2 1TB, velocidades hasta 3500MB/s',
                'sku' => 'STOR-017',
                'price' => 119.99,
                'stock' => 22,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Fuente de Poder 650W',
                'description' => 'Fuente modular 80 Plus Gold, certificada para gaming',
                'sku' => 'COMP-018',
                'price' => 89.99,
                'stock' => 15,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Tarjeta Gráfica RTX 3070',
                'description' => 'GPU NVIDIA GeForce RTX 3070 8GB GDDR6',
                'sku' => 'GAME-019',
                'price' => 599.99,
                'stock' => 4,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Micrófono USB Profesional',
                'description' => 'Micrófono condensador USB con brazo articulado y antipop',
                'sku' => 'AUDIO-020',
                'price' => 129.99,
                'stock' => 18,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Tablet Android 10 pulgadas',
                'description' => 'Tablet Samsung Galaxy Tab con S-Pen, 64GB',
                'sku' => 'MOBILE-021',
                'price' => 349.99,
                'stock' => 10,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Smart Watch Deportivo',
                'description' => 'Reloj inteligente con GPS, monitor cardíaco y resistencia al agua',
                'sku' => 'WEAR-022',
                'price' => 199.99,
                'stock' => 25,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Cargador Inalámbrico Rápido',
                'description' => 'Base de carga inalámbrica Qi 15W, compatible con iPhone y Android',
                'sku' => 'ACC-023',
                'price' => 29.99,
                'stock' => 50,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Bocina Bluetooth Portátil',
                'description' => 'Altavoz Bluetooth resistente al agua, 12 horas de batería',
                'sku' => 'AUDIO-024',
                'price' => 59.99,
                'stock' => 35,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Soporte para Laptop Ajustable',
                'description' => 'Soporte ergonómico de aluminio para laptop, altura ajustable',
                'sku' => 'ACC-025',
                'price' => 39.99,
                'stock' => 45,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($products as $product) {
            $this->insert('{{%product}}', $product);
        }

        echo "25 productos de ejemplo creados exitosamente (para demostrar paginación).\n";
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
            'CLEAN-015',
            'COMP-016',
            'STOR-017',
            'COMP-018',
            'GAME-019',
            'AUDIO-020',
            'MOBILE-021',
            'WEAR-022',
            'ACC-023',
            'AUDIO-024',
            'ACC-025'
        ];

        foreach ($skus as $sku) {
            $this->delete('{{%product}}', ['sku' => $sku]);
        }

        echo "25 productos de ejemplo eliminados.\n";
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
