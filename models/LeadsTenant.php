<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "leads_tenant".
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
 * @property integer $created_by
 */
class LeadsTenant extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'leads_tenant';
    }

    // public function relations() {
    //     return array(
    //         'status' => array(self::HAS_ONE, 'ref_status', 'ref_status'),
    //     );
    // }
    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['full_name', 'email_id', 'contact_number', 'address', 'communication', 'lead_city'], 'required'],
            [['communication'], 'string'],
            [['pincode'], 'string'],
            [['reffered_by', 'updated_by', 'created_by'], 'integer'],
            [['created_date', 'updated_date'], 'safe'],
            [['full_name', 'email_id', 'contact_number', 'lead_city', 'lead_state', 'branch_code', 'ref_status'], 'string', 'max' => 100],
            [['pincode'], 'string', 'max' => 15],
            [['address', 'address_line_2'], 'string', 'max' => 255],
            [['contact_number'], 'string', 'max' => 15],
            //['contact_number', 'match', 'pattern' => '/^\d{10,15}$/', 'message' => 'Phone must contain Minimum 10 digits.'],
            ['email_id', 'email'],
            ['email_id', 'unique'],
            ['email_id', 'emailCheck'],
            ['contact_number', 'contactCheck'],
        ];
    }

    public function emailCheck($attribute, $params) {
        if (empty($this->id)) {
            $model = Users::find()->where(['login_id' => $this->email_id])->one();

            if ($model) {
                $this->addError($attribute, Yii::t('app', '"' . $this->email_id . '" is already in use'));
            }
        } else {
            $leadsTenant = \app\models\LeadsTenant::find()->where(['email_id' => $this->email_id])->andWhere(['id' => $this->id])->one();
            if (empty($leadsTenant)) {
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
            $leadsTenant = \app\models\LeadsTenant::find()->where(['contact_number' => $phone])->andWhere(['id' => $this->id])->one();
            if (empty($leadsTenant)) {
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
            'full_name' => 'Full name',
            'email_id' => 'Email id',
            'contact_number' => 'Contact number',
            'address' => 'Address',
            'address_line_2' => 'Address line 2',
            'branch_code' => 'Branch',
            'pincode' => 'Pincode',
            'communication' => 'Communication',
            'lead_city' => 'Property city',
            'lead_state' => 'Property state',
            'ref_status' => 'Lead status',
            'reffered_by' => 'Reffered by',
            'updated_by' => 'Updated by',
            'created_date' => 'Created date',
            'updated_date' => 'Updated date',
            'created_by' => 'Created by'
        ];
    }

    public function searchCommon($params) {
        $userType = Yii::$app->user->identity->user_type;
        if ($userType == 7) {
            return $this->search1($params);
        } else {
            return $this->search($params);
        }
    }

    public function getUsersinfo() {
        return $this->hasOne(Users::className(), ['login_id' => 'email_id']);
        // ->viaTable('applicant_profile', ['applicant_id' => 'Usersid']);;
    }

    public function afterFind() {
        if (isset($this->follow_up_date_time)) {
            $this->follow_up_date_time = date('d-M-Y H:i', strtotime($this->follow_up_date_time));
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
            $this->follow_up_date_time = date('d-M-Y H:i', strtotime($this->follow_up_date_time));
        }

        return true;
    }

    public function search($params) {

        $assinee = LeadsTenant::find()->joinWith('usersinfo', true);
        if (isset($params['LeadsTenant']['sort'])) {
            if ($params['LeadsTenant']['sort'] == '') {
                $sort = 'leads_tenant.id desc';
            } else {
                $sort = $params['LeadsTenant']['sort'];
            }
        } else {
            $sort = 'leads_tenant.id desc';
        }

        $userAssignments = \app\models\Users::find()->where(['id' => Yii::$app->user->id])->one();
        $opsProfileModel = \app\models\SalesProfile::findOne(['sale_id' => Yii::$app->user->id]);
        $currentRoleCode = $opsProfileModel->role_code;
        $currentRoleValue = $opsProfileModel->role_value;
        
        if ($currentRoleValue == 'ELHQ') {
            $assinee = \app\models\LeadsTenant::find()
                ->leftJoin('users', 'users.login_id = email_id')
                ->leftJoin('applicant_profile', 'users.id = applicant_profile.applicant_id');
        } else if ($currentRoleCode == 'SLSTMG') {
            $assinee = \app\models\LeadsTenant::find()
                ->leftJoin('users', 'users.login_id = email_id')
                ->leftJoin('applicant_profile', 'users.id = applicant_profile.applicant_id')
                ->where(['leads_tenant.lead_state' => $currentRoleValue]);
        } else if ($currentRoleCode == 'SLCTMG') {
            $assinee = \app\models\LeadsTenant::find()
                ->leftJoin('users', 'users.login_id = email_id')
                ->leftJoin('applicant_profile', 'users.id = applicant_profile.applicant_id')
                ->where(['leads_tenant.lead_city' => $currentRoleValue]);
        } else if ($currentRoleCode == 'SLBRMG') {
            $assinee = \app\models\LeadsTenant::find()
                ->leftJoin('users', 'users.login_id = email_id')
                ->leftJoin('applicant_profile', 'users.id = applicant_profile.applicant_id')
                ->where(['applicant_profile.branch_code' => $currentRoleValue]);
        } else if ($currentRoleCode == 'SLEXE') {
            $branchModel = \app\models\Branches::find()->where(['branch_code' => $currentRoleValue])->one();
            $assinee = \app\models\LeadsTenant::find()
                ->leftJoin('users', 'users.login_id = email_id')
                ->leftJoin('applicant_profile', 'users.id = applicant_profile.applicant_id')
                ->where(['applicant_profile.branch_code' => $currentRoleValue])
                ->andWhere(['leads_tenant.lead_city' => $branchModel->city_code])
                ->andWhere(['applicant_profile.sales_id' => Yii::$app->user->id]);
        }
        
        $dataProvider = new ActiveDataProvider([
            'query' => $assinee,
            'sort' => false,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);


        if (isset($params['LeadsTenant']['search'])) {
            $searchParam = $params['LeadsTenant']['search'];

            $assinee->andFilterWhere(['or',
                ['like', 'leads_tenant.full_name', trim($searchParam)],
                ['like', 'leads_tenant.email_id', trim($searchParam)],
                ['like', 'leads_tenant.contact_number', trim($searchParam)],
                ['like', 'leads_tenant.address', trim($searchParam)],
                    ]
            );
        }
        $assinee->orderBy($sort);
        return $dataProvider;
    }

    public function search1($params) {
        if (isset($params['LeadsTenant']['sort'])) {
            if ($params['LeadsTenant']['sort'] == '') {
                $sort = 'leads_tenant.id desc';
            } else {
                $sort = $params['LeadsTenant']['sort'];
            }
        } else {
            $sort = 'leads_tenant.id desc';
        }
        $assinee = LeadsTenant::find()->joinWith('usersinfo', true);

        $userAssignments = \app\models\Users::find()->where(['id' => Yii::$app->user->id])->one();
        $opsProfileModel = \app\models\OperationsProfile::findOne(['operations_id' => Yii::$app->user->id]);
        $currentRoleCode = $opsProfileModel->role_code;
        $currentRoleValue = $opsProfileModel->role_value;
        
        if ($currentRoleValue == 'ELHQ') {
            $assinee = \app\models\LeadsTenant::find()
                ->leftJoin('users', 'users.login_id = email_id')
                ->leftJoin('applicant_profile', 'users.id = applicant_profile.applicant_id');
        } else if ($currentRoleCode == 'OPSTMG') {
            $assinee = \app\models\LeadsTenant::find()
                ->leftJoin('users', 'users.login_id = email_id')
                ->leftJoin('applicant_profile', 'users.id = applicant_profile.applicant_id')
                ->where(['leads_tenant.lead_state' => $currentRoleValue]);
        } else if ($currentRoleCode == 'OPCTMG') {
            $assinee = \app\models\LeadsTenant::find()
                ->leftJoin('users', 'users.login_id = email_id')
                ->leftJoin('applicant_profile', 'users.id = applicant_profile.applicant_id')
                ->where(['leads_tenant.lead_city' => $currentRoleValue]);
        } else if ($currentRoleCode == 'OPBRMG') {
            $assinee = \app\models\LeadsTenant::find()
                ->leftJoin('users', 'users.login_id = email_id')
                ->leftJoin('applicant_profile', 'users.id = applicant_profile.applicant_id')
                ->where(['applicant_profile.branch_code' => $currentRoleValue]);
        } else if ($currentRoleCode == 'OPEXE') {
            $branchModel = \app\models\Branches::find()->where(['branch_code' => $currentRoleValue])->one();
            $assinee = \app\models\LeadsTenant::find()
                ->leftJoin('users', 'users.login_id = email_id')
                ->leftJoin('applicant_profile', 'users.id = applicant_profile.applicant_id')
                ->where(['applicant_profile.branch_code' => $currentRoleValue])
                ->andWhere(['leads_tenant.lead_city' => $branchModel->city_code])
                ->andWhere(['applicant_profile.operation_id' => Yii::$app->user->id]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $assinee,
            'sort' => false,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        if (isset($params['LeadsTenant']['search'])) {
            $searchParam = $params['LeadsTenant']['search'];

            $assinee->andFilterWhere(['or',
                ['like', 'leads_tenant.full_name', trim($searchParam)],
                ['like', 'leads_tenant.email_id', trim($searchParam)],
                ['like', 'leads_tenant.contact_number', trim($searchParam)],
                ['like', 'leads_tenant.address', trim($searchParam)],
                    ]
            );
        }

        $assinee->orderBy($sort);

        return $dataProvider;
    }

}
