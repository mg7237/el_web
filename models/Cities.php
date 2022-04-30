<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cities".
 *
 * @property integer $id
 * @property integer $state_id
 * @property string $city_name
 */
class Cities extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'cities';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['code', 'state_id', 'city_name'], 'required'],
            [['state_id'], 'integer'],
            [['city_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'state_id' => 'State ID',
            'city_name' => 'City name',
            'code' => 'City code',
        ];
    }

}
