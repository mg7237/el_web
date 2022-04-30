<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "leads_advisor".
 *
 * @property integer $id
 * @property string $full_name
 * @property string $email_id
 * @property string $contact_number
 * @property string $address
 * @property string $city
 * @property string $state
 * @property string $region
 * @property string $pincode
 * @property string $communication
 * @property string $ref_status
 * @property integer $reffered_by
 * @property integer $updated_by
 * @property string $created_date
 * @property string $schedule_date_time
 * @property string $follow_up_date_time
 * @property string $updated_date
 * @property integer $created_by
 */
class LeadsAdvisor extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public $leads_advisor;

    public static function tableName() {
        return 'leads_advisor';
    }

    /**
     * @inheritdoc
     */
    public function rules() {


        return [
            [['full_name', 'email_id', 'contact_number', 'address', 'address_line_2', 'lead_city', 'lead_state', 'branch_code', 'pincode', 'communication'], 'required'],
            [['communication'], 'string'],
            [['ref_status'], 'integer'],
            [['pincode'], 'string', 'max' => 15],
            [['created_date', 'schedule_date_time', 'follow_up_date_time', 'updated_date'], 'safe'],
            [['full_name', 'email_id', 'contact_number', 'lead_city', 'lead_state', 'branch_code'], 'string', 'max' => 100],
            [['address', 'address_line_2'], 'string', 'max' => 255],
            //['contact_number', 'match', 'pattern' => '/^\d{10}$/', 'message' => 'Contact number must contain exactly 10 digits.'],
            //['end_date', 'compareIt'],
            [['contact_number'],'string', 'max' => 15],
            ['email_id', 'email'],
            ['email_id', 'unique'],
            ['email_id', 'emailCheck'],
            ['contact_number', 'contactCheck'],
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
            $leadsAdvisor = \app\models\LeadsAdvisor::find()->where(['email_id' => $this->email_id])->andWhere(['id' => $this->id])->one();
            if (empty($leadsAdvisor)) {
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

            if ($model) {
                $this->addError($attribute, Yii::t('app', '"' . $this->contact_number . '" is already in use'));
            }
        } else {
            $leadsAdvisor = \app\models\LeadsAdvisor::find()->where(['contact_number' => $phone])->andWhere(['id' => $this->id])->one();
            if (empty($leadsAdvisor)) {
                $model = Users::find()->where(['phone' => $phone])->one();
                if ($model) {
                    $this->addError($attribute, Yii::t('app', '"' . $this->contact_number . '" is already in use'));
                }
            }
        }
    }
    
    public function compareIt($attribute, $params) {
        if (isset($this->end_date) && $this->end_date != '' && isset($this->start_date)) {
            if (strtotime($this->end_date) <= strtotime($this->start_date)) {
                $this->addError($attribute, 'Contract End Date must be later than contract start date');
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'full_name' => 'Advisor name',
            'email_id' => 'Email id',
            'contact_number' => 'Contact number',
            'address' => 'Address line 1',
            'address_line_2' => 'Address line 2',
            'lead_city' => 'City',
            'lead_state' => 'State',
            'branch_code' => 'Branch',
            'pincode' => 'Pincode',
            'communication' => 'Comments',
            'ref_status' => 'Lead status',
            'reffered_by' => 'Reffered by',
            'updated_by' => 'Updated by',
            'created_date' => 'Created date',
            'schedule_date_time' => 'Schedule date time',
            'follow_up_date_time' => 'Follow up date time',
            'updated_date' => 'Updated date',
            'created_by' => 'Created by',
        ];
    }

    public function beforeSave($insert) {
        if (isset($this->schedule_date_time)) {
            $this->schedule_date_time = date('Y-m-d H:i:s', strtotime($this->schedule_date_time));
        }
        if (isset($this->follow_up_date_time)) {
            $this->follow_up_date_time = date('Y-m-d H:i:s', strtotime($this->follow_up_date_time));
        }
        if (isset($this->start_date)) {
            $this->start_date = date('Y-m-d', strtotime($this->start_date));
        }
        if (isset($this->end_date)) {
            $this->end_date = date('Y-m-d', strtotime($this->end_date));
        }
       
        if (!empty($this->contact_number)) {
            $this->contact_number = Yii::$app->userdata->trimPhone($this->contact_number);
        }


        return true;
    }

    public function afterFind() {
        if (isset($this->schedule_date_time)) {
            $this->schedule_date_time = date('d-M-Y H:i', strtotime($this->schedule_date_time));
        }
        if (isset($this->follow_up_date_time)) {
            $this->follow_up_date_time = date('d-M-Y H:i', strtotime($this->follow_up_date_time));
        }

        return true;
    }

    public function afterSave($insert, $changed) {
        if (isset($this->schedule_date_time)) {
            $this->schedule_date_time = date('d-M-Y H:i', strtotime($this->schedule_date_time));
        }
        if (isset($this->follow_up_date_time)) {
            $this->follow_up_date_time = date('d-M-Y H:i', strtotime($this->follow_up_date_time));
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
        
        if (isset($params['LeadsAdvisor']['sort'])) {
            if ($params['LeadsAdvisor']['sort'] == '') {
                $sort = 'leads_advisor.id desc';
            } else {
                $sort = $params['LeadsAdvisor']['sort'];
            }
        } else {
            $sort = 'leads_advisor.id desc';
        }
        
        $userAssignments = \app\models\Users::find()->where(['id' => Yii::$app->user->id])->one();
        $opsProfileModel = \app\models\SalesProfile::findOne(['sale_id' => Yii::$app->user->id]);
        $currentRoleCode = $opsProfileModel->role_code;
        $currentRoleValue = $opsProfileModel->role_value;
        
        if ($currentRoleValue == 'ELHQ') {
            $assinee = \app\models\LeadsAdvisor::find()
                ->leftJoin('users', 'users.login_id = leads_advisor.email_id')
                ->leftJoin('advisor_profile', 'users.id = advisor_profile.advisor_id');
        } else if ($currentRoleCode == 'SLSTMG') {
            $assinee = \app\models\LeadsAdvisor::find()
                ->leftJoin('users', 'users.login_id = email_id')
                ->leftJoin('advisor_profile', 'users.id = advisor_profile.advisor_id')
                ->where(['leads_advisor.lead_state' => $currentRoleValue]);
        } else if ($currentRoleCode == 'SLCTMG') {
            $assinee = \app\models\LeadsAdvisor::find()
                ->leftJoin('users', 'users.login_id = email_id')
                ->leftJoin('advisor_profile', 'users.id = advisor_profile.advisor_id')
                ->where(['leads_advisor.lead_city' => $currentRoleValue]);
        } else if ($currentRoleCode == 'SLBRMG') {
            $assinee = \app\models\LeadsAdvisor::find()
                ->leftJoin('users', 'users.login_id = email_id')
                ->leftJoin('advisor_profile', 'users.id = advisor_profile.advisor_id')
                ->where(['advisor_profile.branch_code' => $currentRoleValue]);
        } else if ($currentRoleCode == 'SLEXE') {
            $branchModel = \app\models\Branches::find()->where(['branch_code' => $currentRoleValue])->one();
            $assinee = \app\models\LeadsAdvisor::find()
                ->leftJoin('users', 'users.login_id = email_id')
                ->leftJoin('advisor_profile', 'users.id = advisor_profile.advisor_id')
                ->where(['advisor_profile.branch_code' => $currentRoleValue])
                ->andWhere(['leads_advisor.lead_city' => $branchModel->city_code])
                ->andWhere(['advisor_profile.sales_id' => Yii::$app->user->id]);
        }
        
        if (isset($params['LeadsAdvisor']['search'])) {
            $searchParam = $params['LeadsAdvisor']['search'];

            $assinee->andFilterWhere(['or',
                ['like', 'leads_advisor.full_name', trim($searchParam)],
                ['like', 'leads_advisor.email_id', trim($searchParam)],
                ['like', 'leads_advisor.contact_number', trim($searchParam)],
                ['like', 'leads_advisor.address', trim($searchParam)],
                ['like', 'leads_advisor.address_line_2', trim($searchParam)],
                    ]
            );
        }

        $assinee->orderBy($sort);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $assinee
        ]);
        
        return $dataProvider;
    }
    
    public function searchOperation($params) {
        
        if (isset($params['LeadsAdvisor']['sort'])) {
            if ($params['LeadsAdvisor']['sort'] == '') {
                $sort = 'leads_advisor.id desc';
            } else {
                $sort = $params['LeadsAdvisor']['sort'];
            }
        } else {
            $sort = 'leads_advisor.id desc';
        }
        
        $userAssignments = \app\models\Users::find()->where(['id' => Yii::$app->user->id])->one();
        $opsProfileModel = \app\models\OperationsProfile::findOne(['operations_id' => Yii::$app->user->id]);
        $currentRoleCode = $opsProfileModel->role_code;
        $currentRoleValue = $opsProfileModel->role_value;
        
        if ($currentRoleValue == 'ELHQ') {
            $assinee = \app\models\LeadsAdvisor::find()
                ->leftJoin('users', 'users.login_id = leads_advisor.email_id')
                ->leftJoin('advisor_profile', 'users.id = advisor_profile.advisor_id');
        } else if ($currentRoleCode == 'OPSTMG') {
            $assinee = \app\models\LeadsAdvisor::find()
                ->leftJoin('users', 'users.login_id = email_id')
                ->leftJoin('advisor_profile', 'users.id = advisor_profile.advisor_id')
                ->where(['leads_advisor.lead_state' => $currentRoleValue]);
        } else if ($currentRoleCode == 'OPCTMG') {
            $assinee = \app\models\LeadsAdvisor::find()
                ->leftJoin('users', 'users.login_id = email_id')
                ->leftJoin('advisor_profile', 'users.id = advisor_profile.advisor_id')
                ->where(['leads_advisor.lead_city' => $currentRoleValue]);
        } else if ($currentRoleCode == 'OPBRMG') {
            $assinee = \app\models\LeadsAdvisor::find()
                ->leftJoin('users', 'users.login_id = email_id')
                ->leftJoin('advisor_profile', 'users.id = advisor_profile.advisor_id')
                ->where(['advisor_profile.branch_code' => $currentRoleValue]);
        } else if ($currentRoleCode == 'OPEXE') {
            $branchModel = \app\models\Branches::find()->where(['branch_code' => $currentRoleValue])->one();
            $assinee = \app\models\LeadsAdvisor::find()
                ->leftJoin('users', 'users.login_id = email_id')
                ->leftJoin('advisor_profile', 'users.id = advisor_profile.advisor_id')
                ->where(['advisor_profile.branch_code' => $currentRoleValue])
                ->andWhere(['leads_advisor.lead_city' => $branchModel->city_code])
                ->andWhere(['advisor_profile.operation_id' => Yii::$app->user->id]);
        }
        
        if (isset($params['LeadsAdvisor']['search'])) {
            $searchParam = $params['LeadsAdvisor']['search'];

            $assinee->andFilterWhere(['or',
                ['like', 'leads_advisor.full_name', trim($searchParam)],
                ['like', 'leads_advisor.email_id', trim($searchParam)],
                ['like', 'leads_advisor.contact_number', trim($searchParam)],
                ['like', 'leads_advisor.address', trim($searchParam)],
                ['like', 'leads_advisor.address_line_2', trim($searchParam)],
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
