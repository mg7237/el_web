<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property integer $id
 * @property string $full_name
 * @property string $password
 * @property string $login_id
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
class Usersupdate extends \yii\db\ActiveRecord {

    public $manager_id;
    public $address_line_1;
    public $address_line_2;
    public $state;
    public $city;
    public $branch;
    public $profile_image;

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
            [['full_name', 'login_id', 'role'], 'required'],
            //[['ow_comments'], 'string'],
            [['role', 'user_type', 'status'], 'required'],
            [['created_date'], 'safe'],
            ['password', 'required', 'on' => 'insert'],
            [['username', 'login_id', 'password'], 'trim'],
            ['login_id', 'email'],
            ['login_id', 'unique'],
            [['full_name'], 'string', 'max' => 255],
                //[['phone'], 'string', 'max' => 20],
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
            'login_id' => 'Email',
            //'phone' => 'Mobile Number',
            'address' => 'Address',
            'lat' => 'Latitude',
            'lon' => 'Longitude',
            'state' => 'State',
            'city' => 'City',
            'pincode' => 'pincode',
            'ad_company' => 'Ad company',
            'ad_properties' => 'Ad properties',
            'ow_interested' => 'Ow interested',
            //'ow_comments' => 'Ow Comments',
            'auth_key' => 'Auth Key',
            'user_type' => 'User Type',
            'password_reset_token' => 'Password Reset Token',
            'role' => 'Role',
            'created_date' => 'Date of creation
',
        ];
    }

}
