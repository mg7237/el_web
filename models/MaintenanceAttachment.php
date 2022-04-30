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
class MaintenanceAttachment extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'maintenance_service_attachment';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['service_id', 'attachment'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'service_id' => 'Service Id',
            'attachment' => 'Maintenance Attachment',
        ];
    }

}

?>