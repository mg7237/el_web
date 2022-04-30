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
class PropertyExpense extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'property_expense';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['property_id', 'client_name', 'gst_invoice', 'expense_type', 'vendor', 'total_expense_amount', 'invoice_date', 'total_gst', 'vendor_gst', 'attachment', 'paid_by', 'paid_on', 'approved_by', 'created_date', 'created_by'], 'required']
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
            'gst_invoice' => 'GST invoice',
            'comission_amount' => 'Comission amount',
            'expense_type' => 'Expense type',
            'vendor' => 'Vendor',
            'total_expense_amount' => 'Total expense',
            'invoice_date' => 'Invoice date',
            'total_gst' => 'Total GST',
            'vendor_gst' => 'Vendor GST',
            'attachment' => 'Attachment',
            'paid_by' => 'Paid by',
            'paid_on' => 'Paid on',
            'approved_by' => 'Approved by',
            'created_date' => 'Created date',
            'created_by' => 'Created by',
            'attachment' => 'Attachment'
        ];
    }
}
