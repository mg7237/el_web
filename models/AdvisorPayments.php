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
class AdvisorPayments extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'advisorpayments';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['advisor_id', 'source_id', 'source_type', 'comission_amount', 'tds', 'payable_amount', 'month'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'advisor_id' => 'Advisor id',
            'source_id' => 'Source id',
            'source_type' => 'Source type',
            'comission_amount' => 'Comission amount',
            'tds' => 'TDS',
            'payable_amount' => 'Payable Amount',
            'month' => 'Month'
        ];
    }

    public function afterFind() {
        if (isset($this->follow_up_date_time)) {
            $this->created_date = date('d-M-Y H:i', strtotime($this->created_date));
        }

        return true;
    }

}
