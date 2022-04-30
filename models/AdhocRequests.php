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
class AdhocRequests extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'adhoc_charge';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['title', 'actual_charge', 'charge_to_tenant', 'payment_due_date'], 'required'],
            [['actual_charge', 'charge_to_tenant'], 'integer'],
			 ['tenant_id', 'required', 'message' => 'Invalid Tenant Name.'], 
			 ['payment_due_date', 'date', 'format'=>'dd-M-yy', 'message' => 'Invalid Date.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'tenant_id' => 'Tenant Id',
            'title' => 'Title',
            'actual_charge' => 'Actual charge',
            'penalty_amount' => 'Penalty amount',
            'charge_to_tenant' => 'Charge to tenant',
            'request_status' => 'Request status',
            'payment_status' => 'Payment status',
            'payment_due_date' => 'Payment due date',
            'created_date' => 'Created date',
            'updated_date' => 'Updated date',
            'created_by' => 'Created by',
            'updated_by' => 'Updated by',
            'transaction_id' => 'Transaction id',
            'payment_date' => 'Payment date',
            'payment_type' => 'Payment type'
        ];
    }

    public function beforeSave($insert) {
        if (isset($this->payment_due_date)) {
            $this->payment_due_date = date('Y-m-d', strtotime($this->payment_due_date));
        }
        if (isset($this->payment_date)) {
            $this->payment_date = date('Y-m-d', strtotime($this->payment_date));
        }


        return true;
    }

    public function afterFind() {
        if (isset($this->payment_due_date)) {
            $this->payment_due_date = date('d-M-Y', strtotime($this->payment_due_date));
        }
        if (isset($this->payment_date)) {
            $this->payment_date = date('d-M-Y', strtotime($this->payment_date));
        }

        return true;
    }

    public function afterSave($insert, $changed) {
        if (isset($this->payment_due_date)) {
            $this->payment_due_date = date('d-M-Y', strtotime($this->payment_due_date));
        }
        if (isset($this->payment_date)) {
            $this->payment_date = date('d-M-Y', strtotime($this->payment_date));
        }

        return true;
    }

    public function search($params) {
		//print_r($params);die;
        $userAssignments = \app\models\Users::find()->where(['id' => Yii::$app->user->id])->one();
        if ($userAssignments) {

            $condition1 = '';
            $role = $userAssignments->role;
            switch ($role) {
                case 5:
                    $roles = \app\models\OperationsProfile::find()->select('region')->where(['operations_id' => Yii::$app->user->id])->one();
                    $properties = \app\models\Properties::find()->select('id')->where(['region' => $roles->region])->all();
                    break;
                case 6:
                    $roles = \app\models\OperationsProfile::find()->select('region')->where(['operations_id' => Yii::$app->user->id])->one();
                    $properties = \app\models\Properties::find()->select('id')->where(['region' => $roles->region])->all();
                    break;
                case 7:
                    $roles = \app\models\OperationsProfile::find()->select('city')->where(['operations_id' => Yii::$app->user->id])->one();
                    $properties = \app\models\Properties::find()->select('id')->where(['city' => $roles->city])->all();
                    break;
                case 8:
                    $roles = \app\models\OperationsProfile::find()->select('state')->where(['operations_id' => Yii::$app->user->id])->one();
                    $properties = \app\models\Properties::find()->select('id')->where(['state' => $roles->state])->all();
                    break;
            }
        } else {

            $assinee1 = \app\models\OwnerProfile::find()->select('owner_id')->where(['operation_id' => Yii::$app->user->id])->all();
            $listt = '';
            foreach ($assinee1 as $key => $value) {
                if ($listt == '') {
                    $listt = $value->owner_id;
                } else {
                    $listt .= ',' . $value->owner_id;
                }
            }
            $properties = \app\models\Properties::find()->select('id')->where(['owner_id IN (' . $listt . ')'])->all();
        }
        if (count($properties) != 0) {
            $property_list = '';
            foreach ($properties as $key => $value) {
                if ($property_list == '') {
                    $property_list = $value->id;
                } else {
                    $property_list = $property_list . ',' . $value->id;
                }
            }

            $tenants = \app\models\TenantAgreements::find()->select('tenant_id')->where('parent_id IN (' . $property_list . ')')->all();

            if (count($tenants) != 0) {
                $tenant_list = '';
                foreach ($tenants as $key => $value) {
                    if ($tenant_list == '') {
                        $tenant_list = $value->tenant_id;
                    } else {
                        $tenant_list = $tenant_list . ',' . $value->tenant_id;
                    }
                }
                $assinee = \app\models\AdhocRequests::find()->where('tenant_id IN (' . $tenant_list . ')')->leftJoin('users', 'users.id = tenant_id');
            } else {
                $assinee = \app\models\AdhocRequests::find()->where(['tenant_id' => 0])->leftJoin('users', 'users.id = tenant_id');
            }
        } else {
            $assinee = \app\models\AdhocRequests::find()->where(['tenant_id' => 0])->leftJoin('users', 'users.id = tenant_id');
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $assinee,
            'sort' => false
        ]);

//$searchParam = $params['Request']['search'];	

       /* if (!$this->load($params)) {
            return $dataProvider;
        }*/
		
if(isset($params['Request']['search']))
{
$searchParam = $params['Request']['search'];
if($searchParam == 'Paid' || $searchParam == 'paid')
{
$searchParam = '1';	
$assinee->andFilterWhere(['or',
            ['like', 'adhoc_charge.payment_status', trim($searchParam)]
        ]);
return $dataProvider; 
}
if($searchParam == 'Unpaid' || $searchParam == 'unpaid')
{
$searchParam = '0';	
$assinee->andFilterWhere(['or',
            ['like', 'adhoc_charge.payment_status', trim($searchParam)]
        ]);
return $dataProvider; 
}	
//echo  trim($searchParam);  die;
}
else{
$searchParam = '';		
}
     

        $assinee->andFilterWhere(['or',
            ['like', 'users.full_name', trim($searchParam)],
            ['like', 'adhoc_charge.title', trim($searchParam)],
            ['like', 'adhoc_charge.actual_charge', trim($searchParam)],
            ['like', 'adhoc_charge.charge_to_tenant', trim($searchParam)],
            ['like', 'adhoc_charge.payment_status', trim($searchParam)]
        ]);

        return $dataProvider; 
    }

}
