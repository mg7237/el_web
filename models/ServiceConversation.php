<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "service_conversation".
 *
 * @property integer $id
 * @property integer $service_request_id
 * @property integer $user_type
 * @property integer $user_id
 * @property string $message
 * @property string $attachment
 * @property string $attachment_type
 * @property string $created_datetime
 */
class ServiceConversation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'service_conversation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['service_request_id', 'user_type', 'user_id'], 'integer'],
            [['created_datetime'], 'safe'],
            [['message', 'attachment'], 'string', 'max' => 10000],
            [['attachment_type'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'service_request_id' => 'Service Request ID',
            'user_type' => 'User Type',
            'user_id' => 'User ID',
            'message' => 'Message',
            'attachment' => 'Attachment',
            'attachment_type' => 'Attachment Type',
            'created_datetime' => 'Created Datetime',
        ];
    }
}
