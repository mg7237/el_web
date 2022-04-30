<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Properties;

/**
 * PropertiesSearch represents the model behind the search form about `app\models\Properties`.
 */
class PropertiesSearch extends Properties {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'rent', 'deposit', 'token_amount', 'maintenance', 'maintenance_included', 'status', 'no_of_rooms', 'is_room', 'flat_bhk', 'flat_area', 'created_by', 'updated_by', 'no_of_beds'], 'integer'],
            [['property_code', 'owner_id', 'property_type', 'flat_type', 'availability_from', 'property_description', 'created_time', 'update_time'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
        $query = Properties::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'rent' => $this->rent,
            'deposit' => $this->deposit,
            'token_amount' => $this->token_amount,
            'maintenance' => $this->maintenance,
            'maintenance_included' => $this->maintenance_included,
            'status' => $this->status,
            'no_of_rooms' => $this->no_of_rooms,
            'is_room' => $this->is_room,
            'flat_bhk' => $this->flat_bhk,
            'flat_area' => $this->flat_area,
            'availability_from' => $this->availability_from,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_time' => $this->created_time,
            'update_time' => $this->update_time,
            'no_of_beds' => $this->no_of_beds,
        ]);

        $query->andFilterWhere(['like', 'property_code', $this->property_code])
                ->andFilterWhere(['like', 'owner_id', $this->owner_id])
                ->andFilterWhere(['like', 'property_type', $this->property_type])
                ->andFilterWhere(['like', 'flat_type', $this->flat_type])
                ->andFilterWhere(['like', 'property_description', $this->property_description]);

        return $dataProvider;
    }

}
