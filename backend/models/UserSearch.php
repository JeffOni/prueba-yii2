<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;

/**
 * UserSearch representa el modelo de búsqueda para `common\models\User`.
 */
class UserSearch extends User
{
    /**
     * Atributo virtual para filtrar por rol
     */
    public $role;

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'email', 'role'], 'safe'],
        ];
    }

    /**
     * @inheritDoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Crea una instancia de ActiveDataProvider con query de búsqueda aplicada.
     *
     * @param array $params Parámetros de búsqueda
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = User::find();

        // Configurar el data provider
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ],
        ]);

        // Cargar los parámetros de búsqueda y validar
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Filtrar usuarios eliminados por defecto
        $query->andWhere(['!=', 'status', User::STATUS_DELETED]);

        // Aplicar filtros
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
              ->andFilterWhere(['like', 'email', $this->email]);

        // Filtrar por rol si se especificó
        if ($this->role) {
            $auth = \Yii::$app->authManager;
            $userIds = [];

            // Obtener todos los usuarios con el rol especificado
            foreach ($auth->getUserIdsByRole($this->role) as $userId) {
                $userIds[] = $userId;
            }

            if (!empty($userIds)) {
                $query->andWhere(['id' => $userIds]);
            } else {
                // Si no hay usuarios con ese rol, no mostrar resultados
                $query->andWhere(['id' => -1]);
            }
        }

        return $dataProvider;
    }
}
