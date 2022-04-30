<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property integer $id
 * @property string $full_name
 * @property string $password
 * @property string $email
 * @property string $phone
 * @property string $address
 * @property string $lat
 * @property string $lon
 * @property string $state
 * @property string $city
 * @property string $pincode
 * @property string $ad_company
 * @property string $ad_properties
 * @property string $ow_interested
 * @property string $ow_comments
 * @property string $auth_key
 * @property string $password_reset_token
 * @property integer $role
 * @property string $created
 */
class Advisors extends \yii\db\ActiveRecord {

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
            [['full_name', 'email', 'phone', 'address', 'state', 'city', 'region', 'pincode', 'ad_company', 'ad_properties', 'created'], 'required'],
            [['role'], 'integer'],
            [['created'], 'safe'],
            [['full_name', 'password', 'email', 'address'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 20],
            [['pincode'], 'string', 'max' => 10],
            ['email', 'email'],
            ['email', 'unique'],
            [['state', 'city'], 'string', 'max' => 100],
            [['ad_company', 'ad_properties'], 'string', 'max' => 150],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'full_name' => 'Advisor name',
            'email' => 'Advisor email',
            'phone' => 'Advisor phone number',
            'address' => 'Advisor address',
            'state' => 'State',
            'city' => 'City',
            'pincode' => 'pincode',
            'ad_company' => 'Advisor company name',
            'ad_properties' => 'Ad properties',
        ];
    }

}
