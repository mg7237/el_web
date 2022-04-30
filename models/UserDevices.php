<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_devices".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $device_id
 * @property string $fcm_token
 */
class UserDevices extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_devices';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['fcm_token'], 'required'],
            [['device_id', 'fcm_token'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'device_id' => 'Device ID',
            'fcm_token' => 'Fcm Token',
        ];
    }
}
