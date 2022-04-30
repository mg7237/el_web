<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "operations_profile".
 *
 * @property integer $id
 * @property integer $operations_id
 * @property string $email
 * @property string $address_line_1
 * @property string $address_line_2
 * @property integer $state
 * @property integer $city
 * @property integer $pincode
 * @property string $phone
 */
class OperationsProfile extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'operations_profile';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['operations_id', 'email', 'address_line_1', 'address_line_2', 'state', 'city', 'pincode', 'phone'], 'required'],
            [['operations_id', 'state', 'city' ], 'integer'],
            [['email'], 'string', 'max' => 255],
            [['address_line_1', 'address_line_2'], 'string', 'max' => 1000],
            [['phone'], 'string', 'max' => 15],
            [['pincode'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'operations_id' => 'Operations ID',
            'email' => 'Email',
            'address_line_1' => 'Address line 1',
            'address_line_2' => 'Address line 2',
            'state' => 'State',
            'city' => 'City',
            'pincode' => 'Pincode',
            'phone' => 'Phone',
        ];
    }

}
