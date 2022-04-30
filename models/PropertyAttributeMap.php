<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "property_attribute_map".
 *
 * @property integer $id
 * @property integer $property_id
 * @property integer $attr_id
 * @property string $value
 */
class PropertyAttributeMap extends \yii\db\ActiveRecord {

    public $value2;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'property_attribute_map';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['attr_id', 'value'], 'required'],
            [['property_id', 'attr_id'], 'integer'],
            [['value'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'property_id' => 'Property ID',
            'attr_id' => 'Attr ID',
            'value' => 'Value',
        ];
    }

}
