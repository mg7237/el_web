<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "owner_payments".
 *
 * @property integer $id
 * @property integer $owner_id
 * @property string $property_id
 * @property integer $parent_id
 * @property double $payment_amount
 * @property double $pms_commission
 * @property double $service_tax
 * @property double $tds
 * @property string $payment_des
 * @property string $payment_mode
 * @property string $remarks
 * @property integer $payment_status
 * @property integer $created_by
 * @property integer $updated_by
 * @property string $created_date
 * @property string $updated_date
 */
class OwnerPayments extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'owner_payments';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['owner_id', 'property_id', 'parent_id', 'payment_amount', 'pms_commission', 'tds', 'payment_des', 'payment_mode', 'remarks', 'created_by', 'updated_by', 'created_date', 'updated_date'], 'required'],
            [['owner_id', 'parent_id', 'payment_status', 'created_by', 'updated_by'], 'integer'],
            [['payment_amount', 'pms_commission', 'service_tax', 'tds'], 'number'],
            [['payment_mode'], 'string'],
            [['created_date', 'updated_date'], 'safe'],
            [['property_id', 'payment_des', 'remarks'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'owner_id' => 'Owner ID',
            'property_id' => 'Property ID',
            'parent_id' => 'Parent ID',
            'payment_amount' => 'Payment amount',
            'pms_commission' => 'Pms commission',
            'tds' => 'Tds',
            'gst' => 'GST',
            'payment_des' => 'Payment des',
            'payment_mode' => 'Payment mode',
            'remarks' => 'Remarks',
            'payment_status' => 'Payment status',
            'created_by' => 'Created by',
            'updated_by' => 'Updated by',
            'created_date' => 'Created date',
            'updated_date' => 'Updated date',
        ];
    }

}
