<?php

namespace app\models;

use app\models\PropertiesAttributes;
use app\models\PropertyAttributeMap;
use app\models\Cities;
use Yii;

/**
 * This is the model class for table "properties_address".
 *
 * @property integer $id
 * @property integer $property_id
 * @property string $property_name
 * @property string $address
 * @property string $lat
 * @property string $lon
 * @property string $city
 * @property string $state
 * @property string $pincode
 */
class PropertiesAddress extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'properties_address';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['property_name', 'address', 'city', 'state', 'pincode'], 'required'],
            [['property_id'], 'integer'],
            [['property_name', 'lat', 'lon', 'city', 'state', 'pincode'], 'string', 'max' => 100],
            [['address'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'property_id' => 'Property ID',
            'property_name' => 'Property name',
            'address' => 'Address',
            'lat' => 'Lat',
            'lon' => 'Lon',
            'city' => 'City',
            'state' => 'State',
            'pincode' => 'Pincode',
        ];
    }

    public function getProperty() {
        return $this->hasOne(Properties::className(), ['id' => 'property_id']);
    }

    public function getAttributesValue() {
        return $this->hasMany(PropertyAttributeMap::className(), ['property_id' => 'property_id']);
    }

    public static function getCityName($id) {
        $cities = Cities::find()->where(['id' => $id])->one();
        if ($cities)
            return $cities->city_name;
    }

    public static function getAttributeName($attr_id) {
        $propertiesAttri = PropertiesAttributes::find()->where(['id' => $attr_id])->one();
        if ($propertiesAttri)
            return $propertiesAttri->name;
    }

    public static function getAttributeTotal($attr_id) {
        $propertiesAttri = PropertiesAttributes::find()->where(['id' => $attr_id])->one();
        if ($propertiesAttri)
            return $propertiesAttri;
    }

    public static function getAttributeNameValue($id, $attr_id) {
        $propertiesAttri = PropertyAttributeMap::find()->where(['property_id' => $id, 'attr_id' => $attr_id])->one();
        if ($propertiesAttri)
            return $propertiesAttri->value;
    }

}
