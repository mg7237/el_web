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
 * @property string $user_type
 * @property string $password_reset_token
 * @property integer $role
 * @property integer $status
 * @property string $created
 */
class Stepinfo extends \yii\db\ActiveRecord {

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
            [['address', 'occupation', 'phone'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'full_name' => 'Name',
            'password' => 'Password',
            'email' => 'Email',
            'phone' => 'Mobile number',
            'address' => 'Address',
            'lat' => 'Latitude',
            'lon' => 'Longitude',
            'state' => 'State',
            'city' => 'City',
            'pincode' => 'pincode',
            'ad_company' => 'Ad company',
            'ad_properties' => 'Ad properties',
            'ow_interested' => 'Ow interested',
            'ow_comments' => 'Ow comments',
            'auth_key' => 'Auth Key',
            'user_type' => 'User Type',
            'password_reset_token' => 'Password Reset Token',
            'role' => 'Role',
            'created' => 'Date of creation
',
        ];
    }

}
