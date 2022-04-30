<?php

namespace app\models;

use yii\helpers\Security;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use Yii;

/**
 * This is the model class for table "users".
 *
 * @property integer $id
 * @property string $full_name
 * @property string $login_id
 * @property string $password
 * @property string $user_type
 * @property string $type_register
 * @property integer $status
 * @property string $auth_key
 * @property string $password_reset_token
 * @property integer $role
 * @property string $identification
 * @property string $occupation
 * @property string $created_date
 * @property string $updated_date
 */
class Users extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public $password_repeat;
    public $address_line_1;
    public $address_line_2;
    public $state;
    public $city;
    public $branch;
    public $profile_image;

    public static function tableName() {
        return 'users';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['full_name', 'login_id', 'password'], 'required'],
            ['phone', 'required', 'message' => "Please enter valid phone number"],
            [['status', 'user_type'], 'integer'],
            [['role'], 'integer'],
            ['password', 'required', 'on' => 'insert'],
            [['login_id', 'password','phone'], 'trim'],
            ['login_id', 'email', 'message' => "Invalid Email. Please enter valid email address"],
            ['login_id','unique'],
            ['phone', 'contactCheck'],
            ['login_id', 'emailCheck'],
            //['password', 'match' , 'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[#$^+=!*()@_%&]).{8,20}$/', 'message' => "Password must be minimum eight characters, at least one letter, one number and one special character"],
            ['password', 'match' , 'pattern' => '/^(?=.*[a-z])(?=.*\d)(?=.*[#$^+=!*()@_%&]).{8,20}$/', 'message' => "Password must be minimum eight characters, at least one letter, one number and one special character"],
            ['password_repeat', 'required', 'message' => "Please confirm your password"],

           // ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => "Password does not match. Please provide the correct password"],
            
            [['created_date', 'updated_date', 'pass_up_date'], 'safe'],
            [['full_name', 'password', 'auth_key', 'password_reset_token'], 'string', 'max' => 255],
            [['login_id'], 'string', 'max' => 150],
            [['phone'], 'string', 'max' => 15],
            [['identification', 'occupation'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'full_name' => 'Full Name',
            'login_id' => 'Email',
            'password' => 'Password',
            'phone' => 'Phone Number',
            'user_type' => 'User type',
            'type_register' => 'Type register',
            'status' => 'Status',
            'auth_key' => 'Auth Key',
            'password_reset_token' => 'Password Reset Token',
            'role' => 'Role',
            'identification' => 'Identification',
            'occupation' => 'Occupation',
            'created_date' => 'Created date',
            'updated_date' => 'Updated date',
            'pass_up_date' => 'Password updated date',
            'phone' => 'Phone number',
            'address_line_1' => 'Address Line 1',
            'address_line_2' => 'Address Line 2',
            'city' => 'City',
            'state' => 'State'
        ];
    }

    public function getUsersinfo() {
        return $this->hasOne(Users::className(), ['login_id' => 'login_id'])
                        ->viaTable('tenant_profile', ['tenant_id' => 'Usersid']);
        ;
    }

    public function searchOperation($params) {
        $assinee = Users::find()->joinWith('usersinfo', true);
        if (isset($params['LeadsTenant']['sort'])) {
            if ($params['LeadsTenant']['sort'] == '') {
                $sort = 'users.id';
            } else {
                $sort = $params['LeadsTenant']['sort'];
            }
        } else {
            $sort = 'users.id';
        }

        $userAssignments = \app\models\Users::find()->where(['id' => Yii::$app->user->id])->one();
        if ($userAssignments) {

            if ($userAssignments->role == 5) {
                $roles = \app\models\OperationsProfile::find()->select('region')->where(['operations_id' => Yii::$app->user->id])->one();
                $assinee = \app\models\TenantAgreements::find()->select('tenant_agreements.tenant_id,users.full_name,properties.property_name,properties.address_line_1,properties.address_line_2,property_types.property_type_name as property_type,property_types.id as property_type_id,tenant_agreements.property_id as rented_prop,tenant_agreements.rent,tenant_agreements.maintainance,tenant_agreements.deposit,tenant_agreements.lease_start_date,tenant_agreements.lease_end_date,tenant_profile.sales_id,tenant_profile.operation_id')->where(['tenant_profile.region' => $roles->region])
                        ->leftJoin('users', 'tenant_agreements.tenant_id=users.id')
                        ->leftJoin('tenant_profile', 'users.id = tenant_profile.tenant_id')
                        ->leftJoin('properties', 'tenant_agreements.parent_id=properties.id')
                        ->leftJoin('property_type', 'properties.property_type=property_types.id');
            } else if ($userAssignments->role == 6) {
                $roles = \app\models\OperationsProfile::find()->select('region')->where(['operations_id' => Yii::$app->user->id])->one();
                $assinee = \app\models\TenantAgreements::find()->select('tenant_agreements.tenant_id,users.full_name,properties.property_name,properties.address_line_1,properties.address_line_2,property_types.property_type_name as property_type,property_types.id as property_type_id,tenant_agreements.property_id as rented_prop,tenant_agreements.rent,tenant_agreements.maintainance,tenant_agreements.deposit,tenant_agreements.lease_start_date,tenant_agreements.lease_end_date,tenant_profile.sales_id,tenant_profile.operation_id')->where(['tenant_profile.region' => $roles->region])
                        ->leftJoin('users', 'tenant_agreements.tenant_id=users.id')
                        ->leftJoin('tenant_profile', 'users.id = tenant_profile.tenant_id')
                        ->leftJoin('properties', 'tenant_agreements.parent_id=properties.id')
                        ->leftJoin('property_type', 'properties.property_type=property_types.id');
            } else if ($userAssignments->role == 7) {
                $roles = \app\models\OperationsProfile::find()->select('city')->where(['operations_id' => Yii::$app->user->id])->one();

                $assinee = \app\models\TenantAgreements::find()->select('tenant_agreements.tenant_id,users.full_name,properties.property_name,properties.address_line_1,properties.address_line_2,property_types.property_type_name as property_type,property_types.id as property_type_id,tenant_agreements.property_id as rented_prop,tenant_agreements.rent,tenant_agreements.maintainance,tenant_agreements.deposit,tenant_agreements.lease_start_date,tenant_agreements.lease_end_date,tenant_profile.sales_id,tenant_profile.operation_id')->where(['tenant_profile.city' => $roles->city])
                        ->leftJoin('users', 'tenant_agreements.tenant_id=users.id')
                        ->leftJoin('tenant_profile', 'users.id = tenant_profile.tenant_id')
                        ->leftJoin('properties', 'tenant_agreements.parent_id=properties.id')
                        ->leftJoin('property_type', 'properties.property_type=property_types.id');
            } else if ($userAssignments->role == 8) {
                $roles = \app\models\OperationsProfile::find()->select('state')->where(['operations_id' => Yii::$app->user->id])->one();
                $assinee = \app\models\TenantAgreements::find()->select('tenant_agreements.tenant_id,users.full_name,properties.property_name,properties.address_line_1,properties.address_line_2,property_types.property_type_name as property_type,property_types.id as property_type_id,tenant_agreements.property_id as rented_prop,tenant_agreements.rent,tenant_agreements.maintainance,tenant_agreements.deposit,tenant_agreements.lease_start_date,tenant_agreements.lease_end_date,tenant_profile.sales_id,tenant_profile.operation_id')->where(['tenant_profile.state' => $roles->state])
                        ->leftJoin('users', 'tenant_agreements.tenant_id=users.id')
                        ->leftJoin('tenant_profile', 'users.id = tenant_profile.tenant_id')
                        ->leftJoin('properties', 'tenant_agreements.parent_id=properties.id')
                        ->leftJoin('property_types', 'properties.property_type=property_types.id')
                ;
            }
        } else {
            $assinee = \app\models\TenantAgreements::find()->select('tenant_agreements.tenant_id,users.full_name,properties.property_name,properties.address_line_1,properties.address_line_2,property_types.property_type_name as property_type,property_types.id as property_types_id,tenant_agreements.property_id as rented_prop,tenant_agreements.rent,tenant_agreements.maintainance,tenant_agreements.deposit,tenant_agreements.lease_start_date,tenant_agreements.lease_end_date,tenant_profile.sales_id,tenant_profile.operation_id')->where(['tenant_profile.operation_id' => Yii::$app->user->id])
                    ->leftJoin('users', 'tenant_agreements.tenant_id=users.id')
                    ->leftJoin('tenant_profile', 'users.id = tenant_profile.tenant_id')
                    ->leftJoin('properties', 'tenant_agreements.parent_id=properties.id')
                    ->leftJoin('property_types', 'properties.property_type=property_types.id');
        }


        $dataProvider = new ActiveDataProvider([
            'query' => $assinee,
            'sort' => false
        ]);


        if (!$this->load($params)) {
            return $dataProvider;
        }

        $searchParam = $params['LeadsAdvisor']['search'];

        $assinee->andFilterWhere(['or',
            ['like', 'users.full_name', trim($searchParam)],
            ['like', 'users.login_id', trim($searchParam)],
            ['like', 'tenant_profile.phone', trim($searchParam)],
            ['like', 'tenant_profile.address_line_1', trim($searchParam)],
                ]
        );

        $assinee->orderBy($sort);

        return $dataProvider;
    }

    public function searchUser($params) {

        if (isset($params['sort'])) {
            if ($params['sort'] == '') {
                $sort = 'users.id desc';
            } else {
                $sort = $params['sort'];
            }
        } else {
            $sort = 'users.id desc';
        }
        $assinee = \app\models\Users::find()->where(['user_type' => 3, 'refered_by' => Yii::$app->user->id])
                ->leftJoin('tenant_agreements', 'users.id=tenant_agreements.tenant_id')
                ->leftJoin('tenant_profile', 'users.id=tenant_profile.tenant_id');
        $dataProvider = new ActiveDataProvider([
            'query' => $assinee,
            'sort' => false
        ]);


        if (!isset($params['search'])) {
            return $dataProvider;
        }

        $searchParam = $params['search'];
        $assinee->andFilterWhere(['or',
            ['like', 'users.full_name', trim($searchParam)],
            ['like', 'users.login_id', trim($searchParam)],
            ['like', 'tenant_profile.phone', trim($searchParam)],
            ['like', 'tenant_profile.address_line_1', trim($searchParam)],
            ['like', 'tenant_profile.phone', trim($searchParam)],
            ['like', 'tenant_agreements.lease_start_date', trim($searchParam)],
            ['like', 'tenant_agreements.lease_end_date', trim($searchParam)],
                ]
        );

        $assinee->orderBy($sort);
        return $dataProvider;
    }

    /* public function search($params){
      $assinee = Users::find()->joinWith('usersinfo', true);
      if(isset($params['LeadsTenant']['sort'])){
      if($params['LeadsTenant']['sort']==''){
      $sort='users.id';
      }
      else{
      $sort=$params['LeadsTenant']['sort'];
      }
      }
      else{
      $sort='users.id';
      }
      $assinee = \app\models\Users::find()->select('users.*,sales_profile.phone as sales_phone,operations.phone as operations_phone')
      ->leftJoin('sales_profile','(users.user_type=6 AND users.id= sales_profile.sale_id)')
      ->leftJoin('operations_profile','(users.user_type=7 AND users.id= operations_profile.operations_id)');
      print_r($assinee);
      // $dataProvider = new ActiveDataProvider([
      //      'query' => $assinee,
      //       'sort' =>false
      //  ]);


      //   if (!$this->load($params)) {
      //      return $dataProvider;
      //  }

      //  $searchParam = $params['LeadsAdvisor']['search'];

      //  $assinee->andFilterWhere(['or',
      //      ['like','users.full_name' , trim($searchParam) ],
      //      ['like','users.login_id' , trim($searchParam) ],
      //      ['like','operations_profile.phone' , trim($searchParam) ],
      //      ['like','sales_profile.phone' , trim($searchParam) ],
      //      ]
      //  );

      //  $assinee->orderBy($sort);

      //  return $dataProvider;
      } */
	  
    public function beforeSave($insert) {
        if (!empty($this->phone)) {
            $this->phone = Yii::$app->userdata->trimPhone($this->phone);
        }
        return true;
    }
    
    public function contactCheck($attribute, $params) {
        $phone = Yii::$app->userdata->trimPhone($this->phone);
        
        if (empty($this->id)) {
            $model = Users::find()->where(['phone' => $phone])->one();

            if ($model) {
                $this->addError($attribute, Yii::t('app', '"' . $this->phone . '" is already in use'));
            }
        } else {
            $model = Users::find()->where(['phone' => $phone])->andWhere(['id' => $this->id])->one();
            if (empty($model)) {
                $model = Users::find()->where(['phone' => $phone])->one();
                if ($model) {
                    $this->addError($attribute, Yii::t('app', '"' . $this->phone . '" is already in use'));
                }
            }
        }
    }
    
    public function emailCheck($attribute, $params) {
        if (empty($this->id)) {
            $model = Users::find()->where(['login_id' => $this->login_id])->one();

            if ($model) {
                $this->addError($attribute, Yii::t('app', '"' . $this->login_id . '" is already in use'));
            }
        } else {
            $model = Users::find()->where(['login_id' => $this->login_id])->andWhere(['id' => $this->id])->one();
            if (empty($model)) {
                $model = Users::find()->where(['login_id' => $this->login_id])->one();
                if ($model) {
                    $this->addError($attribute, Yii::t('app', '"' . $this->login_id . '" is already in use'));
                }
            }
        }
        
    }
	  
}
