<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "branches".
 *
 * @property integer $id
 * @property integer $city_id
 * @property string $name
 * @property integer $created_by
 * @property string $created_date
 * @property string $updated_date
 * @property integer $updated_by
 */
class Regions extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'branches';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['branch_code', 'branch_type', 'city_id', 'name', 'created_by', 'created_date', 'updated_date', 'updated_by', 'state_id'], 'required'],
            [['city_id', 'created_by', 'updated_by', 'state_id'], 'integer'],
            [['created_date', 'updated_date'], 'safe'],
            [['name'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'city_id' => 'City ID',
            'state_id' => 'State ID',
            'name' => 'Name',
            'created_by' => 'Created by',
            'created_date' => 'Created date',
            'updated_date' => 'Updated date',
            'updated_by' => 'Updated by',
        ];
    }

}
