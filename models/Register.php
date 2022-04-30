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
 * @property string $zipcode
 * @property string $ad_company
 * @property string $ad_properties
 * @property string $ow_interested
 * @property string $ow_comments
 * @property string $auth_key
 * @property string $password_reset_token
 * @property integer $role
 * @property string $created
 */
class Register extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public $password_repeat;

    public static function tableName() {
        return 'users';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['full_name', 'password', 'login_id'], 'required'],
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => "Passwords don't match"],
            [['role'], 'integer'],
            [['login_id', 'password'], 'trim'],
            ['login_id', 'email'],
            ['login_id', 'unique'],
            ['password', 'string', 'length' => [6, 12]],
            [['full_name', 'password', 'login_id'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'full_name' => 'Full name',
            'password' => 'Password',
            'login_id' => 'Email',
            'password_repeat' => 'Confirm password',
            'role' => 'Role',
        ];
    }

}
