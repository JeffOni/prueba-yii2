<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Modelo Product (RF-06)
 *
 * @property int $id
 * @property string $name Nombre del producto
 * @property string|null $description Descripción del producto
 * @property string $sku Código SKU único
 * @property float $price Precio del producto
 * @property int $stock Cantidad en stock
 * @property int $status 1=activo, 0=inactivo
 * @property int $created_at Timestamp de creación
 * @property int $updated_at Timestamp de actualización
 */
class Product extends ActiveRecord
{
    // Constantes para el campo status
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%product}}';
    }

    /**
     * Behaviors para auto-llenar created_at y updated_at
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * Reglas de validación (RF-06)
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // Campos obligatorios
            [['name', 'sku', 'price'], 'required'],

            // Tipos de datos
            [['description'], 'string'],
            [['price'], 'number', 'min' => 0.01],
            [['stock', 'status'], 'integer'],

            // Longitudes de string
            [['name'], 'string', 'max' => 255],
            [['sku'], 'string', 'max' => 100],

            // SKU debe ser único (excepto el registro actual al editar)
            [['sku'], 'unique', 'targetAttribute' => 'sku', 'filter' => function ($query) {
                if (!$this->isNewRecord) {
                    $query->andWhere(['!=', 'id', $this->id]);
                }
            }, 'message' => 'Este código SKU ya existe en el sistema.'],

            // Stock no puede ser negativo
            [['stock'], 'integer', 'min' => 0, 'message' => 'El stock no puede ser negativo.'],

            // Status por defecto es activo
            [['status'], 'default', 'value' => self::STATUS_ACTIVE],
            [['status'], 'in', 'range' => [self::STATUS_INACTIVE, self::STATUS_ACTIVE]],

            // Stock por defecto es 0
            [['stock'], 'default', 'value' => 0],
        ];
    }

    /**
     * Labels en español para los formularios
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Nombre del Producto',
            'description' => 'Descripción',
            'sku' => 'Código SKU',
            'price' => 'Precio',
            'stock' => 'Stock',
            'status' => 'Estado',
            'created_at' => 'Fecha de Creación',
            'updated_at' => 'Última Actualización',
        ];
    }

    /**
     * Obtener el texto del status
     * @return string
     */
    public function getStatusText()
    {
        return $this->status === self::STATUS_ACTIVE ? 'Activo' : 'Inactivo';
    }

    /**
     * Obtener array de opciones para el campo status
     * @return array
     */
    public static function getStatusList()
    {
        return [
            self::STATUS_ACTIVE => 'Activo',
            self::STATUS_INACTIVE => 'Inactivo',
        ];
    }

    /**
     * Formatear el precio con símbolo de dólar
     * @return string
     */
    public function getFormattedPrice()
    {
        return '$' . number_format($this->price, 2);
    }
}
