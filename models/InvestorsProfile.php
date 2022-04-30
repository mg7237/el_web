<?php

namespace app\models;

use Yii;

class InvestorsProfile extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'investors_profile';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['investor_id', 'email', 'address_line_1', 'address_line_2', 'state', 'city', 'pincode', 'phone'], 'required'],
            [['investor_id', 'state', 'city' ], 'integer'],
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
            'investor_id' => 'Investor ID',
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
