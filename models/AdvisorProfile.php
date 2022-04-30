<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "advisor_profile".
 *
 * @property integer $id
 * @property integer $advisor_id
 * @property string $ad_properties
 * @property string $ad_company
 * @property string $emer_contact
 * @property integer $sales_id
 * @property integer $operation_id
 * @property string $address_line_1
 * @property string $address_line_2
 * @property integer $state
 * @property integer $city
 * @property integer $pincode
 * @property string $phone
 */
class AdvisorProfile extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    
    //public $ad_properties;
    
    public static function tableName() {
        return 'advisor_profile';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [ 
            [['advisor_id', 'account_holder_name', 'bank_name', 'bank_branchname', 'bank_ifcs', 'account_number', 'pan_number', 'address_line_1', 'address_line_2', 'state', 'city', 'pincode', 'phone', 'service_tax_number', 'email_id', 'ad_properties'], 'required'],
            [['advisor_id', 'sales_id', 'operation_id'], 'integer'],
            [['pincode'], 'string', 'max' => 15],
            [['ad_properties', 'ad_company'], 'string', 'max' => 100],
            [['emer_contact', 'account_holder_name', 'bank_name', 'bank_branchname', 'bank_ifcs', 'account_number', 'pan_number',], 'string', 'max' => 255],
            [['address_line_1', 'address_line_2'], 'string', 'max' => 1000],
            //['phone', 'match', 'pattern' => '/^\d{10,15}$/', 'message' => 'Phone must contain Minimum 10 digits.'],
            [['phone'], 'string', 'max' => 15],
            ['email_id', 'email'],
            [['profile_image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpeg, png, jpg', 'maxFiles' => 1],
            ['operation_id', 'integer', 'min' => 1, 'tooSmall' => 'operation person cannot be blank'],
            ['bank_ifcs', 'checkIfsc1'],
            ['email_id', 'emailCheck'],
            ['phone', 'contactCheck'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function emailCheck($attribute, $params) {
        if (empty($this->id)) {
            $model = Users::find()->where(['login_id' => $this->email_id])->one();

            if ($model) {
                $this->addError($attribute, Yii::t('app', '"' . $this->email_id . '" is already in use'));
            }
        } else {
            $adProfile = \app\models\AdvisorProfile::find()->where(['email_id' => $this->email_id])->andWhere(['id' => $this->id])->one();
            
            if (empty($adProfile)) {
                $model = Users::find()->where(['login_id' => $this->email_id])->one();
                if ($model) {
                    $this->addError($attribute, Yii::t('app', '"' . $this->email_id . '" is already in use'));
                }
            }
        }
    }

    public function contactCheck($attribute, $params) {
        $phone = Yii::$app->userdata->trimPhone($this->phone);
        
        if (empty($this->id)) {
            $model = Users::find()->where(['phone' => $phone])->one();

            if ($model) {
                $this->addError($attribute, Yii::t('app', '"' . $this->phone . '" is already in use'));
            }
        } else {
            $adProfile = \app\models\AdvisorProfile::find()->where(['phone' => $phone])->andWhere(['id' => $this->id])->one();
            if (empty($adProfile)) {
                $model = Users::find()->where(['phone' => $phone])->one();
                if ($model) {
                    $this->addError($attribute, Yii::t('app', '"' . $this->phone . '" is already in use'));
                }
            }
        }
    }
    
    public function checkIfsc($attribute, $params) {
        $ifsc = Yii::$app->userdata->getIFSCValidate($this->bank_ifcs);
        if ($ifsc == 'error') {
            $this->addError($attribute, 'Invalid IFSC code');
        }
    }

    public function checkIfsc1($attribute, $params) {
        $ifsc = Yii::$app->userdata->getIFSCValidate($this->bank_ifcs);
        if ($ifsc == 'error') {
            $this->addError($attribute, 'Invalid IFSC code');
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'advisor_id' => 'Advisor ID',
            'ad_properties' => 'Properties Held',
            'ad_company' => 'Ad company',
            'emer_contact' => 'contact number',
            'sales_id' => 'Sales ID',
            'operation_id' => 'Operation ID',
            'address_line_1' => 'Address line 1',
            'address_line_2' => 'Address line 2',
            'state' => 'State',
            'city' => 'City',
            'pincode' => 'Pincode',
            'phone' => 'Phone',
            'account_holder_name' => 'Account holder name',
            'bank_name' => 'Bank name',
            'bank_branchname' => 'Branch name',
            'bank_ifcs' => 'Bank IFSC code',
            'account_number' => 'Account number',
            'service_tax_number' => 'Service tax  number',
            'pan_number' => 'Pan number',
            'region' => 'Branch',
        ];
    }

    public function getUsersinfo() {
        return $this->hasOne(Users::className(), ['id' => 'advisor_id']);
    }
    
    public function getListing($params) {
        $userType = Yii::$app->user->identity->user_type;
        if ($userType == 7) {
            return $this->listForOps($params);
        } else {
            return $this->listForSales($params);
        }
    }
    
    public function listForSales($params) {
        $userAssignments = \app\models\Users::find()->where(['id' => Yii::$app->user->id])->one();
        $opsProfileModel = \app\models\SalesProfile::findOne(['sale_id' => Yii::$app->user->id]);
        $currentRoleCode = $opsProfileModel->role_code;
        $currentRoleValue = $opsProfileModel->role_value;
        
        if ($currentRoleValue == 'ELHQ') {
            $assinee = \app\models\AdvisorProfile::find()
                ->leftJoin('users', 'users.id = advisor_profile.advisor_id')
                ->where(['users.status' => '1']);
        } else if ($currentRoleCode == 'SLSTMG') {
            $assinee = \app\models\AdvisorProfile::find()
                ->leftJoin('users', 'users.id = advisor_profile.advisor_id')
                ->where(['advisor_profile.state' => $currentRoleValue])
                ->andWhere(['users.status' => '1']);
        } else if ($currentRoleCode == 'SLCTMG') {
            $assinee = \app\models\AdvisorProfile::find()
                ->leftJoin('users', 'users.id = advisor_profile.advisor_id')
                ->where(['advisor_profile.city' => $currentRoleValue])
                ->andWhere(['users.status' => '1']);
        } else if ($currentRoleCode == 'SLBRMG') {
            $assinee = \app\models\AdvisorProfile::find()
                ->leftJoin('users', 'users.id = advisor_profile.advisor_id')
                ->where(['advisor_profile.branch_code' => $currentRoleValue])
                ->andWhere(['users.status' => '1']);
        } else if ($currentRoleCode == 'SLEXE') {
            $branchModel = \app\models\Branches::find()->where(['branch_code' => $currentRoleValue])->one();
            $assinee = \app\models\AdvisorProfile::find()
                ->leftJoin('users', 'users.id = advisor_profile.advisor_id')
                ->where(['advisor_profile.branch_code' => $currentRoleValue])
                ->andWhere(['advisor_profile.city' => $branchModel->city_code])
                ->andWhere(['advisor_profile.sales_id' => Yii::$app->user->id])
                ->andWhere(['users.status' => '1']);
        }
        
        $dataProvider = new ActiveDataProvider([
            'query' => $assinee,
            'sort' => false,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);


        if (!isset($params['LeadsTenant']['search'])) {
            return $dataProvider;
        }

        $searchParam = $params['LeadsTenant']['search'];
        $assinee->andFilterWhere(['or',
            ['like', 'users.full_name', trim($searchParam)],
            ['like', 'users.login_id', trim($searchParam)],
            ['like', 'advisor_profile.phone', trim($searchParam)],
            ['like', 'advisor_profile.address_line_1', trim($searchParam)],
            ['like', 'advisor_profile.address_line_2', trim($searchParam)],
                ]
        );

        return $dataProvider;
    }
    
    public function listForOps($params) {
        $userAssignments = \app\models\Users::find()->where(['id' => Yii::$app->user->id])->one();
        $opsProfileModel = \app\models\OperationsProfile::findOne(['operations_id' => Yii::$app->user->id]);
        $currentRoleCode = $opsProfileModel->role_code;
        $currentRoleValue = $opsProfileModel->role_value;
        
        if ($currentRoleValue == 'ELHQ') {
            $assinee = \app\models\AdvisorProfile::find()
                ->leftJoin('users', 'users.id = advisor_profile.advisor_id')
                ->where(['users.status' => '1']);
        } else if ($currentRoleCode == 'OPSTMG') {
            $assinee = \app\models\AdvisorProfile::find()
                ->leftJoin('users', 'users.id = advisor_profile.advisor_id')
                ->where(['advisor_profile.state' => $currentRoleValue])
                ->andWhere(['users.status' => '1']);
        } else if ($currentRoleCode == 'OPCTMG') {
            $assinee = \app\models\AdvisorProfile::find()
                ->leftJoin('users', 'users.id = advisor_profile.advisor_id')
                ->where(['advisor_profile.city' => $currentRoleValue])
                ->andWhere(['users.status' => '1']);
        } else if ($currentRoleCode == 'OPBRMG') {
            $assinee = \app\models\AdvisorProfile::find()
                ->leftJoin('users', 'users.id = advisor_profile.advisor_id')
                ->where(['advisor_profile.branch_code' => $currentRoleValue])
                ->andWhere(['users.status' => '1']);
        } else if ($currentRoleCode == 'OPEXE') {
            $branchModel = \app\models\Branches::find()->where(['branch_code' => $currentRoleValue])->one();
            $assinee = \app\models\AdvisorProfile::find()
                ->leftJoin('users', 'users.id = advisor_profile.advisor_id')
                ->where(['advisor_profile.branch_code' => $currentRoleValue])
                ->andWhere(['advisor_profile.city' => $branchModel->city_code])
                ->andWhere(['advisor_profile.operation_id' => Yii::$app->user->id])
                ->andWhere(['users.status' => '1']);
        }
        
        $dataProvider = new ActiveDataProvider([
            'query' => $assinee,
            'sort' => false,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);


        if (!isset($params['LeadsTenant']['search'])) {
            return $dataProvider;
        }

        $searchParam = $params['LeadsTenant']['search'];
        $assinee->andFilterWhere(['or',
            ['like', 'users.full_name', trim($searchParam)],
            ['like', 'users.login_id', trim($searchParam)],
            ['like', 'advisor_profile.phone', trim($searchParam)],
            ['like', 'advisor_profile.address_line_1', trim($searchParam)],
            ['like', 'advisor_profile.address_line_2', trim($searchParam)],
                ]
        );

        return $dataProvider;
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    

    public function search($params) {
        $assinee = OwnerProfile::find()->joinWith('usersinfo', true);

        $userAssignments = \app\models\Users::find()->where(['id' => Yii::$app->user->id, 'status' => '1'])->one();

        $assinee = \app\models\AdvisorProfile::find()
                ->leftJoin('users', 'users.id = advisor_id')
                ->where(['users.status' => '1'])
        ;
        
        $dataProvider = new ActiveDataProvider([
            'query' => $assinee,
            'sort' => false,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);


        if (!isset($params['LeadsTenant']['search'])) {
            return $dataProvider;
        }

        $searchParam = $params['LeadsTenant']['search'];
        $assinee->andFilterWhere(['or',
            ['like', 'users.full_name', trim($searchParam)],
            ['like', 'users.login_id', trim($searchParam)],
            ['like', 'advisor_profile.phone', trim($searchParam)],
            ['like', 'advisor_profile.address_line_1', trim($searchParam)],
            ['like', 'advisor_profile.address_line_2', trim($searchParam)],
                ]
        );

        return $dataProvider;
    }

}
