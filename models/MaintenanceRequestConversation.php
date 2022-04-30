<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

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
class MaintenanceRequestConversation extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'maintainance_service_conversation';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['service_request_id', 'user_id', 'message'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'service_request_id' => 'Service Request Id',
            'message' => 'Message',
            'created_datetime' => 'Created Date Time',
            'user_id' => 'User Id'
        ];
    }

}