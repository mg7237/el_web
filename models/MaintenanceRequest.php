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
class MaintenanceRequest extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'maintainance_service_request';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['property_id', 'request_type_id', 'title', 'description', 'charges', 'charges_to_owner', 'payment_due_date'], 'required'],
            [['charges', 'charges_to_owner'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'property_id' => 'Property Id',
            'title' => 'Title',
            'charges' => 'Actual Charge',
            'charges_to_owner' => 'Charge To Owner',
            'status' => 'Status',
            'request_type_id' => 'Request Type Id',
            'description' => 'Description',
            'payment_due_date' => 'Payment Due Date',
            'created_date' => 'Created Date',
            'updated_date' => 'Updated Date',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'operated_by' => 'Operated By',
        ];
    }

    public function beforeSave($insert) {
        if (isset($this->payment_due_date)) {
            $this->payment_due_date = date('Y-m-d', strtotime($this->payment_due_date));
        }
        if (isset($this->created_date)) {
            $this->created_date = date('Y-m-d', strtotime($this->created_date));
        }

        return true;
    }

    public function afterFind() {
        if (isset($this->payment_due_date)) {
            $this->payment_due_date = date('d-M-Y', strtotime($this->payment_due_date));
        }
        if (isset($this->created_date)) {
            $this->created_date = date('d-M-Y', strtotime($this->created_date));
        }

        return true;
    }

    public function afterSave($insert, $changed) {
        if (isset($this->payment_due_date)) {
            $this->payment_due_date = date('d-M-Y', strtotime($this->payment_due_date));
        }
        if (isset($this->created_date)) {
            $this->created_date = date('d-M-Y', strtotime($this->created_date));
        }

        return true;
    }

    public function search($params) {

        /*  $userAssignments = \app\models\Users::find()->where(['id'=>Yii::$app->user->id])->one() ;
          if($userAssignments){

          $condition1='';
          $role=$userAssignments->role;
          switch($role){
          case 5:
          $roles = \app\models\OperationsProfile::find()->select('region')->where(['operations_id'=>Yii::$app->user->id])->one();
          $properties = \app\models\Properties::find()->select('id')->where(['region'=>$roles->region])->all();
          break;
          case 6:
          $roles = \app\models\OperationsProfile::find()->select('region')->where(['operations_id'=>Yii::$app->user->id])->one();
          $properties = \app\models\Properties::find()->select('id')->where(['region'=>$roles->region])->all();
          break;
          case 7:
          $roles = \app\models\OperationsProfile::find()->select('city')->where(['operations_id'=>Yii::$app->user->id])->one();
          $properties = \app\models\Properties::find()->select('id')->where(['city'=>$roles->city])->all();
          break;
          case 8:
          $roles = \app\models\OperationsProfile::find()->select('state')->where(['operations_id'=>Yii::$app->user->id])->one();
          $properties = \app\models\Properties::find()->select('id')->where(['state'=>$roles->state])->all();
          break;

          }



          }
          else{

          $assinee1 = \app\models\OwnerProfile::find()->select('owner_id')->where(['operation_id'=>Yii::$app->user->id])->all();
          $listt='';
          foreach($assinee1 as $key=>$value){
          if($listt=='')
          {
          $listt=$value->owner_id;
          }
          else{
          $listt.=','.$value->owner_id;
          }
          }
          $properties = \app\models\Properties::find()->select('id')->where(['owner_id IN ('.$listt.')'])->all();

          }

          if(count($properties)!=0){
          $property_list='';
          foreach($properties as $key=>$value){
          if($property_list==''){
          $property_list=$value->id;
          }
          else{
          $property_list=$property_list.','.$value->id;
          }
          }

          $assinee=\app\models\MaintenanceRequest::find()->where('property_id IN ('.$property_list.')')
          ->leftJoin('properties','maintainance_service_request.property_id=properties.id')
          ->leftJoin('users','properties.owner_id=users.id')
          ->leftJoin('request_status','maintainance_service_request.status=request_status.id');


          }
          else{
          $assinee=\app\models\MaintenanceRequest::find()->where(['property_id'=>0])
          ->leftJoin('properties','maintainance_service_request.property_id=properties.id')
          ->leftJoin('users','properties.owner_id=users.id')
          ->leftJoin('request_status','maintainance_service_request.status=request_status.id');
          } */
        $assinee = \app\models\MaintenanceRequest::find()
                ->leftJoin('properties', 'maintainance_service_request.property_id=properties.id')
                ->leftJoin('users', 'properties.owner_id=users.id')
                ->leftJoin('request_status', 'maintainance_service_request.status=request_status.id');
        $dataProvider = new ActiveDataProvider([
            'query' => $assinee,
            'sort' => false,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        if (!isset($params['search'])) {
            // echo "hello";
            return $dataProvider;
        }

        $searchParam = $params['search'];

        $assinee->andFilterWhere(['or',
            ['like', 'maintainance_service_request.title', trim($searchParam)],
            ['like', 'request_status.name', trim($searchParam)],
            ['like', 'users.full_name', trim($searchParam)],
            ['like', 'maintainance_service_request.charges', trim($searchParam)],
            ['like', 'maintainance_service_request.charges_to_owner', trim($searchParam)]
        ]);
        return $dataProvider;
    }

    public function searchOwnerProperties($params) {
        $properties = \app\models\Properties::find()->select('id')->where(['owner_id' => Yii::$app->user->id])->all();
        if (count($properties) != 0) {
            $property_list = '';
            foreach ($properties as $key => $value) {
                if ($property_list == '') {
                    $property_list = $value->id;
                } else {
                    $property_list = $property_list . ',' . $value->id;
                }
            }

            $assinee = \app\models\MaintenanceRequest::find()->where('property_id IN (' . $property_list . ')');
        } else {
            $assinee = \app\models\MaintenanceRequest::find()->where(['property_id' => 0]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $assinee,
            'sort' => false
        ]);

        if (!$this->load($params)) {
            return $dataProvider;
        }

        $searchParam = $params['Request']['search'];

        // $assinee->andFilterWhere(['or',
        //     ['like','users.full_name' , trim($searchParam) ],
        //     ['like','adhoc_charge.title' , trim($searchParam) ],
        //     ['like','adhoc_charge.actual_charge' , trim($searchParam) ],
        //     ['like','adhoc_charge.tenant_charge' , trim($searchParam)]
        // ]);

        return $dataProvider;
    }

}
