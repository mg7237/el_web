<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "properties_attributes".
 *
 * @property integer $id
 * @property string $name
 * @property string $icon
 */
class PropertiesAttributes extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'properties_attributes';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'icon'], 'required'],
            [['name'], 'string', 'max' => 1000],
            [['icon'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'icon' => 'Icon',
            'type' => 'Type',
            'binaryfield' => 'Binary field'
        ];
    }

}
