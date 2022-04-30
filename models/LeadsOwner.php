<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "leads_owner".
 *
 * @property integer $id
 * @property string $full_name
 * @property string $email_id
 * @property string $contact_number
 * @property string $address
 * @property string $city
 * @property string $state
 * @property string $pincode
 * @property string $communication
 * @property string $ref_status
 * @property integer $reffered_by
 * @property integer $updated_by
 * @property string $created_date
 * @property string $updated_date
 */
class LeadsOwner extends \yii\db\ActiveRecord {

    public $search;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'leads_owner';
    }

    public function relations() {
        return array(
            'status' => array(self::HAS_ONE, 'ref_status', 'ref_status'),
        );
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            //[['full_name', 'email_id', 'contact_number', 'address', 'city', 'state', 'pincode', 'communication', 'follow_up_date_time'], 'required'],
            [['full_name', 'email_id', 'contact_number', 'address', 'pincode', 'communication', 'lead_city', 'lead_state', 'branch_code'], 'required'],
            [['communication'], 'string'],
            [['pincode'], 'string', 'max' =>15],
            [['reffered_by', 'updated_by'], 'integer'],
            [['created_date', 'updated_date'], 'safe'],
            [['full_name', 'email_id', 'contact_number', 'ref_status'], 'string', 'max' => 100],
            [['address', 'address_line_2'], 'string', 'max' => 255],
            //['contact_number', 'match', 'pattern' => '/^\d{10,15}$/', 'message' => 'Phone must contain Minimum 10 digits.'],
            [['contact_number'], 'string', 'max' =>15],
            ['email_id', 'email'],
            ['email_id', 'unique'],
            ['email_id', 'emailCheck'],
            ['contact_number', 'contactCheck'],
            [['full_name', 'email_id', 'contact_number'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function emailCheck($attribute, $params) {
        if (empty($this->id)) {
            $model = Users::find()->where(['login_id' => $this->email_id])->one();

            if ($model)
                $this->addError($attribute, Yii::t('app', '"' . $this->email_id . '" is already in use'));
        } else {
            $leadsOwner = \app\models\LeadsOwner::find()->where(['email_id' => $this->email_id])->andWhere(['id' => $this->id])->one();
            if (empty($leadsOwner)) {
                $model = Users::find()->where(['login_id' => $this->email_id])->one();
                if ($model) {
                    $this->addError($attribute, Yii::t('app', '"' . $this->email_id . '" is already in use'));
                }
            }
        }
    }
    
    public function contactCheck($attribute, $params) {
        $phone = Yii::$app->userdata->trimPhone($this->contact_number);
        
        if (empty($this->id)) {
            $model = Users::find()->where(['phone' => $phone])->one();

            if ($model)
                $this->addError($attribute, Yii::t('app', '"' . $this->contact_number . '" is already in use'));
        } else {
            $leadsOwner = \app\models\LeadsOwner::find()->where(['contact_number' => $phone])->andWhere(['id' => $this->id])->one();
            if (empty($leadsOwner)) {
                $model = Users::find()->where(['phone' => $phone])->one();
                if ($model) {
                    $this->addError($attribute, Yii::t('app', '"' . $this->contact_number . '" is already in use'));
                }
            }
        }
        
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'full_name' => 'Owner name',
            'email_id' => 'Email id',
            'contact_number' => 'Contact number',
            'address' => 'Address line 1',
            'address_line_2' => 'Address line 2',
            'lead_city' => 'Property City',
            'lead_state' => 'State',
            'branch_code' => 'Branch code',
            'pincode' => 'Pincode',
            'communication' => 'Comments',
            'ref_status' => 'Lead status',
            'reffered_by' => 'Reffered by',
            'updated_by' => 'Updated by',
            'created_date' => 'Created date',
            'updated_date' => 'Updated date'
        ];
    }

    public function afterFind() {
        if (isset($this->follow_up_date_time)) {
            if (Date('Y-m-d', strtotime($this->follow_up_date_time)) == '1970-01-01') {
                $this->follow_up_date_time = '';
            } else {
                $this->follow_up_date_time = date('d-M-Y H:i', strtotime($this->follow_up_date_time));
            }
        }

        return true;
    }

    public function beforeSave($insert) {
        if (isset($this->follow_up_date_time)) {
            $this->follow_up_date_time = date('Y-m-d H:i:s', strtotime($this->follow_up_date_time));
        }
        
        if (!empty($this->contact_number)) {
            $this->contact_number = Yii::$app->userdata->trimPhone($this->contact_number);
        }
        return true;
    }

    public function afterSave($insert, $changed) {
        if (isset($this->follow_up_date_time)) {
            if (Date('Y-m-d', strtotime($this->follow_up_date_time)) == '1970-01-01') {
                $this->follow_up_date_time = '';
            } else {
                $this->follow_up_date_time = date('d-M-Y H:i', strtotime($this->follow_up_date_time));
            }
        }


        return true;
    }

    public function getUsersinfo() {
        return $this->hasOne(Users::className(), ['login_id' => 'email_id']);
    }
    
    public function searchCommon($params) {
        $userType = Yii::$app->user->identity->user_type;
        if ($userType == 6) {
            return $this->search($params);
        } else {
            return $this->searchOperation($params);
        }
    }

    public function search($params) {
        $assinee = LeadsOwner::find()->joinWith('usersinfo', true);
        if (isset($params['LeadsTenant']['sort'])) {
            if ($params['LeadsTenant']['sort'] == '') {
                $sort = 'leads_owner.id desc';
            } else {
                $sort = $params['LeadsTenant']['sort'];
            }
        } else {
            $sort = 'leads_owner.id desc';
        }
        
        $userAssignments = \app\models\Users::find()->where(['id' => Yii::$app->user->id])->one();
        $opsProfileModel = \app\models\SalesProfile::findOne(['sale_id' => Yii::$app->user->id]);
        $currentRoleCode = $opsProfileModel->role_code;
        $currentRoleValue = $opsProfileModel->role_value;
        
        if ($currentRoleValue == 'ELHQ') {
            $assinee = \app\models\LeadsOwner::find()
                ->leftJoin('users', 'users.login_id = email_id')
                ->leftJoin('owner_profile', 'users.id = owner_profile.owner_id');
                //->andWhere(['owner_profile.sales_id' => Yii::$app->user->id]);
        } else if ($currentRoleCode == 'SLSTMG') {
            $assinee = \app\models\LeadsOwner::find()
                ->leftJoin('users', 'users.login_id = email_id')
                ->leftJoin('owner_profile', 'users.id = owner_profile.owner_id')
                ->where(['leads_owner.lead_state' => $currentRoleValue]);
                //->andWhere(['owner_profile.sales_id' => Yii::$app->user->id]);
        } else if ($currentRoleCode == 'SLCTMG') {
            $assinee = \app\models\LeadsOwner::find()
                ->leftJoin('users', 'users.login_id = email_id')
                ->leftJoin('owner_profile', 'users.id = owner_profile.owner_id')
                ->where(['leads_owner.lead_city' => $currentRoleValue]);
                //->andWhere(['owner_profile.sales_id' => Yii::$app->user->id]);
        } else if ($currentRoleCode == 'SLBRMG') {
            $assinee = \app\models\LeadsOwner::find()
                ->leftJoin('users', 'users.login_id = email_id')
                ->leftJoin('owner_profile', 'users.id = owner_profile.owner_id')
                ->where(['owner_profile.branch_code' => $currentRoleValue]);
                //->andWhere(['owner_profile.sales_id' => Yii::$app->user->id]);
        } else if ($currentRoleCode == 'SLEXE') {
            $branchModel = \app\models\Branches::find()->where(['branch_code' => $currentRoleValue])->one();
            $assinee = \app\models\LeadsOwner::find()
                ->leftJoin('users', 'users.login_id = email_id')
                ->leftJoin('owner_profile', 'users.id = owner_profile.owner_id')
                ->where(['owner_profile.branch_code' => $currentRoleValue])
                ->andWhere(['leads_owner.lead_city' => $branchModel->city_code])
                ->andWhere(['owner_profile.sales_id' => Yii::$app->user->id]);
        }
        
        $dataProvider = new ActiveDataProvider([
            'query' => $assinee,
            'sort' => false,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        if (isset($params['LeadsOwner']['search'])) {
            $searchParam = $params['LeadsOwner']['search'];

            $assinee->andFilterWhere(['or',
                ['like', 'leads_owner.full_name', trim($searchParam)],
                ['like', 'leads_owner.email_id', trim($searchParam)],
                ['like', 'leads_owner.contact_number', trim($searchParam)],
                ['like', 'leads_owner.address', trim($searchParam)],
                ['like', 'leads_owner.address_line_2', trim($searchParam)],
                    ]
            );
        }


        $assinee->orderBy($sort);

        return $dataProvider;
    }

    public function searchOperation($params) {
        $assinee = LeadsOwner::find()->joinWith('usersinfo', true);
        if (isset($params['LeadsTenant']['sort'])) {
            if ($params['LeadsTenant']['sort'] == '') {
                $sort = 'leads_owner.id desc';
            } else {
                $sort = $params['LeadsTenant']['sort'];
            }
        } else {
            $sort = 'leads_owner.id desc';
        }
        
        $userAssignments = \app\models\Users::find()->where(['id' => Yii::$app->user->id])->one();
        $opsProfileModel = \app\models\OperationsProfile::findOne(['operations_id' => Yii::$app->user->id]);
        $currentRoleCode = $opsProfileModel->role_code;
        $currentRoleValue = $opsProfileModel->role_value;
        
        if ($currentRoleValue == 'ELHQ') {
            $assinee = \app\models\LeadsOwner::find()
                ->leftJoin('users', 'users.login_id = leads_owner.email_id')
                ->leftJoin('owner_profile', 'users.id = owner_profile.owner_id');
        } else if ($currentRoleCode == 'OPSTMG') {
            $assinee = \app\models\LeadsOwner::find()
                ->leftJoin('users', 'users.login_id = email_id')
                ->leftJoin('owner_profile', 'users.id = owner_profile.owner_id')
                ->where(['leads_owner.lead_state' => $currentRoleValue]);
        } else if ($currentRoleCode == 'OPCTMG') {
            $assinee = \app\models\LeadsOwner::find()
                ->leftJoin('users', 'users.login_id = email_id')
                ->leftJoin('owner_profile', 'users.id = owner_profile.owner_id')
                ->where(['leads_owner.lead_city' => $currentRoleValue]);
        } else if ($currentRoleCode == 'OPBRMG') {
            $assinee = \app\models\LeadsOwner::find()
                ->leftJoin('users', 'users.login_id = email_id')
                ->leftJoin('owner_profile', 'users.id = owner_profile.owner_id')
                ->where(['owner_profile.branch_code' => $currentRoleValue]);
        } else if ($currentRoleCode == 'OPEXE') {
            $branchModel = \app\models\Branches::find()->where(['branch_code' => $currentRoleValue])->one();
            $assinee = \app\models\LeadsOwner::find()
                ->leftJoin('users', 'users.login_id = email_id')
                ->leftJoin('owner_profile', 'users.id = owner_profile.owner_id')
                ->where(['owner_profile.branch_code' => $currentRoleValue])
                ->andWhere(['leads_owner.lead_city' => $branchModel->city_code])
                ->andWhere(['owner_profile.operation_id' => Yii::$app->user->id]);
        }
        
        if (isset($params['LeadsOwner']['search'])) {
            $searchParam = $params['LeadsOwner']['search'];

            $assinee->andFilterWhere(['or',
                ['like', 'users.full_name', trim($searchParam)],
                ['like', 'users.login_id', trim($searchParam)],
                ['like', 'leads_owner.contact_number', trim($searchParam)],
                ['like', 'leads_owner.address', trim($searchParam)],
                ['like', 'leads_owner.address_line_2', trim($searchParam)],
                    ]
            );
        }

        $assinee->orderBy($sort);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $assinee
        ]);
        
        return $dataProvider;
    }

}
