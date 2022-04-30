<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sales_profile".
 *
 * @property integer $id
 * @property integer $sale_id
 * @property string $email
 * @property integer $status
 * @property string $address_line_1
 * @property string $address_line_2
 * @property integer $state
 * @property integer $city
 * @property integer $pincode
 * @property string $phone
 */
class SalesProfile extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'sales_profile';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['sale_id', 'email', 'address_line_1', 'address_line_2', 'state', 'city', 'pincode', 'phone'], 'required'],
            [['sale_id', 'status', 'state', 'city'], 'integer'],
            [['pincode'],'string', 'max' => 15],
            [['email'], 'string', 'max' => 255],
            [['address_line_1', 'address_line_2'], 'string', 'max' => 1000],
            [['phone'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'sale_id' => 'Sale ID',
            'email' => 'Email',
            'status' => 'Status',
            'address_line_1' => 'Address line 1',
            'address_line_2' => 'Address line 2',
            'state' => 'State',
            'city' => 'City',
            'pincode' => 'Pincode',
            'phone' => 'Phone',
        ];
    }

    public function getUsers() {
        return $this->hasOne(\app\models\Users::className(), ['id' => 'sale_id']);
    }

}
