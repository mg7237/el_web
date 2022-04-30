<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "tenant_payments".
 *
 * @property integer $id
 * @property integer $tenant_id
 * @property integer $property_id
 * @property double $original_amount
 * @property string $payment_des
 * @property string $payment_mode
 * @property string $payment_type
 * @property string $remarks
 * @property integer $payment_status
 * @property string $created_date
 * @property integer $created_by
 * @property integer $updated_by
 * @property double $penalty_amount
 * @property double $total_amount
 * @property string $adhoc
 * @property string $updated_date
 */
class TenantPayments extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tenant_payments';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['tenant_id', 'property_id', 'parent_id', 'original_amount', 'created_by', 'total_amount'], 'required'],
            [['tenant_id', 'payment_status', 'created_by', 'updated_by'], 'integer'],
            [['original_amount', 'penalty_amount', 'total_amount'], 'number'],
            [['payment_mode', 'adhoc'], 'string'],
            [['created_date', 'updated_date'], 'safe'],
            [['payment_des', 'property_id', 'remarks'], 'string', 'max' => 255],
            [['payment_type'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'tenant_id' => 'Tenant ID',
            'property_id' => 'Property ID',
            'parent_id' => 'Parent ID',
            'owner_amount' => 'Owner amount',
            'original_amount' => 'Original amount',
            'maintenance' => 'Maintenance',
            'payment_des' => 'Payment des',
            'payment_mode' => 'Payment mode',
            'payment_type' => 'Payment type',
            'remarks' => 'Remarks',
            'payment_status' => 'Payment status',
            'created_date' => 'Created date',
            'created_by' => 'Created by',
            'updated_by' => 'Updated by',
            'penalty_amount' => 'Penalty amount',
            'total_amount' => 'Total amount',
            'adhoc' => 'Adhoc',
            'updated_date' => 'Updated date',
            'due_date' => 'Due date',
            'transaction_id' => 'Transaction id',
            'payment_date' => 'Payment date',
            'calculated' => 'Calculated',
            'payment_id' => 'Payment id',
            'agreement_id' => 'Agreement id'
        ];
    }

    public function beforeSave($insert) {
        if (isset($this->due_date)) {
            $this->due_date = date('Y-m-d', strtotime($this->due_date));
        }
        if (isset($this->payment_date)) {
            $this->payment_date = date('Y-m-d', strtotime($this->payment_date));
        }


        return true;
    }

    public function afterFind() {
        if (isset($this->due_date)) {
            $this->due_date = date('d-M-Y', strtotime($this->due_date));
        }
        if (isset($this->payment_date)) {
            $this->payment_date = date('d-M-Y', strtotime($this->payment_date));
        }

        return true;
    }

    public function afterSave($insert, $changed) {
        if (isset($this->due_date)) {
            $this->due_date = date('d-M-Y', strtotime($this->due_date));
        }
        if (isset($this->payment_date)) {
            $this->payment_date = date('d-M-Y', strtotime($this->payment_date));
        }

        return true;
    }

    public function search($params) {
        $query = TenantPayments::find()->where('payment_amount != 0')->andWhere(['property_id' => Yii::$app->getRequest()->getQueryParam('id')]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        if (!($this->load($params))) {
            return $dataProvider;
        }

        $data = explode('-', $this->payment_month);

        $query->where('YEAR(payment_month) = ' . $data[0] . ' AND MONTH(payment_month) =' . $data[1]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $dataProvider;
    }

    public function search1($params, $post) {
        // $where='';
        // if($post['start_date']!=''){
        //     $where='created_date >= "'.$post['start_date'].'"';
        // }
        // if($post['end_date']!=''){
        //     if($where==''){
        //         $where='created_date <= "'.$post['end_date'].'"';
        //     }
        //     else{
        //         $where='and created_date <= "'.$post['end_date'].'"';
        //     }
        // }
        // if($where!=''){
        //     $query = TenantPayments::find()->where($where)->all();
        // }
        // else{
        //     $query = TenantPayments::find()->where($where)->all();
        // }
        // print_r($query);
        // die;
    }
    
    public function search2($params) {
        $query = TenantPayments::find()->where('total_amount != 0')->andWhere(['property_id' => Yii::$app->getRequest()->getQueryParam('id')]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        if (!($this->load($params))) {
            return $dataProvider;
        }

        $data = explode('-', $this->payment_month);

        $query->where('YEAR(payment_month) = ' . $data[0] . ' AND MONTH(payment_month) =' . $data[1]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $dataProvider;
    }

}
