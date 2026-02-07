<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Product;

/**
 * ProductSearch representa el modelo de búsqueda para `Product` (RF-07)
 */
class ProductSearch extends Product
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'stock', 'status'], 'integer'],
            [['name', 'description', 'sku'], 'safe'],
            [['price'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Crea el data provider con búsqueda y filtros
     *
     * @param array $params Parámetros de búsqueda desde GET
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Product::find();

        // Configurar el data provider
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20, // 20 productos por página (RF-07)
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC, // Más recientes primero
                ]
            ],
        ]);

        // Cargar los parámetros de búsqueda
        $this->load($params);

        if (!$this->validate()) {
            // Si no valida, retornar todos los productos sin filtrar
            return $dataProvider;
        }

        // Aplicar filtros a la consulta
        $query->andFilterWhere([
            'id' => $this->id,
            'stock' => $this->stock,
            'status' => $this->status,
        ]);

        // Filtros con LIKE para búsqueda por texto (RF-07)
        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'sku', $this->sku]);

        // Filtro de rango de precio (opcional)
        if ($this->price !== null && $this->price !== '') {
            $query->andFilterWhere(['<=', 'price', $this->price]);
        }

        return $dataProvider;
    }
}
