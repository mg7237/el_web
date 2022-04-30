<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "operations_profile".
 *
 * @property integer $id
 * @property integer $operations_id
 * @property string $emer_contact
 * @property string $address_line_1
 * @property string $address_line_2
 * @property integer $state
 * @property integer $city
 * @property integer $pincode
 * @property string $phone
 */
class Operations extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'users';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['operations_id', 'emer_contact', 'address_line_1', 'address_line_2', 'state', 'city', 'pincode', 'phone'], 'required'],
            [['operations_id', 'state', 'city', 'pincode'], 'integer'],
            [['emer_contact'], 'string', 'max' => 255],
            [['address_line_1', 'address_line_2'], 'string', 'max' => 1000],
            [['phone'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'operations_id' => 'Operations ID',
            'emer_contact' => 'Emergency contact',
            'address_line_1' => 'Address line 1',
            'address_line_2' => 'Address line 2',
            'state' => 'State',
            'city' => 'City',
            'pincode' => 'Pincode',
            'phone' => 'Phone',
        ];
    }

}
