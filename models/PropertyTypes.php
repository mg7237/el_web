<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "property_types".
 *
 * @property integer $id
 * @property string $property_type_name
 * @property string $created_time
 */
class PropertyTypes extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'property_types';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['property_type_name', 'created_time'], 'required'],
            [['created_time'], 'safe'],
            [['property_type_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'property_type_name' => 'Property type name',
            'created_time' => 'Created time',
        ];
    }

}
