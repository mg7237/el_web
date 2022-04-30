<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cities".
 *
 * @property integer $id
 * @property integer $state_id
 * @property string $city_name
 */
class PropertyIncome extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'property_income';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['property_id', 'client_name', 'income_type', 'total_income_amount', 'attachment', 'received_by', 'paid_on', 'due_date', 'approved_by', 'created_date', 'created_by'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'property_id' => 'Property id',
            'client_name' => 'Client name',
            'income_type' => 'Income type',
            'total_income_amount' => 'Total income',
            'attachment' => 'Attachment',
            'paid_on' => 'Paid on',
            'due_date' => 'Due date',
            'approved_by' => 'Approved by',
            'created_date' => 'Created date',
            'created_by' => 'Created by',
            'attachment' => 'Attachment'
        ];
    }
}
