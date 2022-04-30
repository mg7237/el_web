<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "maintainance_service_request".
 *
 * @property integer $id
 * @property integer $property_id
 * @property string $request_type
 * @property string $title
 * @property string $description
 * @property string $created_date
 * @property integer $status
 * @property string $attachment
 * @property double $charges
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $operated_by
 * @property string $updated_date
 */
class PropertyServiceComment extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'service_conversation';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['message'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'service_request_id' => 'Service Request Id',
            'user_type' => 'User type',
            'user_id' => 'User Id',
            'message' => 'Message',
            'created_date_time' => 'Created date',
        ];
    }

}
