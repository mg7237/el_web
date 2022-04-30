<?php

namespace app\models;

use Yii;
use \app\models\Users;

/**
 * This is the model class for table "tenant_agreements".
 *
 * @property integer $id
 * @property integer $tenant_id
 * @property integer $property_id
 * @property string $lease_start_date
 * @property string $lease_end_date
 * @property double $rent
 * @property double $maintainance
 * @property integer $created_by
 * @property integer $updated_by
 * @property double $deposit
 * @property string $min_stay
 * @property string $notice_period
 * @property string $created_date
 * @property double $late_penalty_percent
 * @property double $min_penalty
 * @property string $updated_date
 * @property string $end_date
 */
class TenantAgreements extends \yii\db\ActiveRecord {

    public $token_amount;
    
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tenant_agreements';
    }

    public function relations() {
        return array(
            'users' => array(self::HAS_ONE, 'Users', array('tenant_id' => 'id')),
        );
    }

    /**
     * @inheritdoc 
     */
    public function rules() {
        return [
            [['tenant_id', 'property_id', 'parent_id', 'lease_start_date', 'lease_end_date', 'rent', 'maintainance', 'created_by', 'updated_by', 'deposit', 'min_stay', 'notice_period', 'created_date', 'updated_date', 'end_date', 'property_type','min_penalty','late_penalty_percent'], 'required'],
            [['tenant_id', 'created_by', 'updated_by'], 'integer'],
            [['lease_start_date', 'lease_end_date', 'created_date', 'updated_date', 'end_date'], 'safe'],
            [['rent', 'maintainance', 'deposit'], 'number'],
            [['min_stay', 'notice_period'], 'string', 'max' => 100],
            [['exit_reason'], 'string', 'max' => 200],
            [['property_id'], 'string', 'max' => 255],
            [['agreement_url'], 'file', 'extensions' => 'pdf', 'skipOnEmpty' => true],
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
            'lease_start_date' => 'Lease start date',
            'lease_end_date' => 'Lease end date',
            'rent' => 'Rent',
            'maintainance' => 'Maintenance',
            'created_by' => 'Created by',
            'updated_by' => 'Updated by',
            'deposit' => 'Deposit',
            'min_stay' => 'Min stay',
            'notice_period' => 'Notice period',
            'created_date' => 'Created date',
            'late_penalty_percent' => 'Late penalty percent',
            'min_penalty' => 'Min penalty',
            'updated_date' => 'Updated date',
            'end_date' => 'End date',
            'agreement_url' => 'Agreement',
            'property_type' => 'Property type',
            'token_amount' => 'Token Amount (In INR)',
            'pg_room_name' => 'PG Room Name',
            'exit_reason' => 'Exit Reason',
            'profit_share_ratio' => 'Profit Share EL:Partner',
        ];
    }

    public function beforeSave($insert) {
        if (isset($this->lease_start_date)) {
            $this->lease_start_date = date('Y-m-d', strtotime($this->lease_start_date));
        }
        if (isset($this->lease_end_date)) {
            $this->lease_end_date = date('Y-m-d', strtotime($this->lease_end_date));
        }
        if (isset($this->end_date)) {
            $this->end_date = date('Y-m-d', strtotime($this->end_date));
        }

        return true;
    }

    public function afterFind() {
        if (isset($this->lease_start_date)) {
            $this->lease_start_date = date('d-M-Y', strtotime($this->lease_start_date));
        }
        if (isset($this->lease_end_date)) {
            $this->lease_end_date = date('d-M-Y', strtotime($this->lease_end_date));
        }
        if (isset($this->end_date)) {
            $this->end_date = date('d-M-Y', strtotime($this->end_date));
        }

        return true;
    }

    public function afterSave($insert, $changed) {
        if (isset($this->lease_start_date)) {
            $this->lease_start_date = date('d-M-Y', strtotime($this->lease_start_date));
        }
        if (isset($this->lease_end_date)) {
            $this->lease_end_date = date('d-M-Y', strtotime($this->lease_end_date));
        }
        if (isset($this->end_date)) {
            $this->end_date = date('d-M-Y', strtotime($this->end_date));
        }

        return true;
    }

    public function getName() {
        return Yii::$app->userdata->getPropertyNameById($this->parent_id);
    }

    public function getTenantDetails() {
        return $this->hasOne(User::className(), ['id' => 'id']);
    }
    
    public function getTenantsByPropertyId () {
        
    }

    public function searchCommon($params, $pastOrCurr) {
        $userType = Yii::$app->user->identity->user_type;
        if ($userType == 7) {
            return $this->searchOps($params, $pastOrCurr);
        } else {
            return $this->search($params);
        }
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

    public function searchOps($params, $pastOrCurr) {
        $select = ' '
                . 'tenant_agreements.tenant_id, users.full_name, properties.property_name, properties.address_line_1 AS address_line_1, properties.address_line_2, '
                . 'property_types.property_type_name as property_type, property_types.id as property_type_id, '
                . 'tenant_agreements.property_id as rented_prop, tenant_agreements.rent, tenant_agreements.maintainance, tenant_agreements.deposit, '
                . 'tenant_agreements.lease_start_date, tenant_agreements.lease_end_date, u1.full_name as sales_name, '
                . 'u2.full_name as operations_name'
                . ' ';
        
        if (isset($params['LeadsTenant']['sort'])) {
            if ($params['LeadsTenant']['sort'] == '') {
                $sort = 'leads_tenant.id desc';
            } else {
                $sort = $params['LeadsTenant']['sort'];
            }
        } else {
            $sort = 'leads_tenant.id desc';
        }
        
        $assinee = TenantAgreements::find()->joinWith('usersinfo', true);

        $userAssignments = \app\models\Users::find()->where(['id' => Yii::$app->user->id])->one();
        $opsProfileModel = \app\models\OperationsProfile::findOne(['operations_id' => Yii::$app->user->id]);
        $currentRoleCode = $opsProfileModel->role_code;
        $currentRoleValue = $opsProfileModel->role_value;
        
        $today = date('Y-m-d');
        
        $pastOrCurrSign = '>=';
        
        if ($pastOrCurr != 1) {
            $pastOrCurrSign = '<';
        }
        
        if ($currentRoleValue == 'ELHQ') {
            $assinee = \app\models\TenantAgreements::find()
                ->select($select)
                ->leftJoin('users', 'users.id = tenant_agreements.tenant_id')
                ->leftJoin('tenant_profile', 'tenant_profile.tenant_id = tenant_agreements.tenant_id')
                ->leftJoin('leads_tenant', 'leads_tenant.email_id = users.login_id')
                ->leftJoin('properties', 'tenant_agreements.parent_id = properties.id')
                ->leftJoin('property_types', 'properties.property_type = property_types.id')
                ->leftJoin('users u1', 'tenant_profile.sales_id = u1.id')
                ->leftJoin('users u2', 'tenant_profile.operation_id = u2.id')
                ->where([$pastOrCurrSign,'tenant_agreements.lease_end_date', $today]);
        } else if ($currentRoleCode == 'OPSTMG') {
            $assinee = \app\models\TenantAgreements::find()
                ->select($select)
                ->leftJoin('users', 'users.id = tenant_agreements.tenant_id')
                ->leftJoin('tenant_profile', 'tenant_profile.tenant_id = tenant_agreements.tenant_id')
                ->leftJoin('leads_tenant', 'leads_tenant.email_id = users.login_id')
                ->leftJoin('properties', 'tenant_agreements.parent_id = properties.id')
                ->leftJoin('property_types', 'properties.property_type = property_types.id')
                ->where(['leads_tenant.lead_state' => $currentRoleValue]);
        } else if ($currentRoleCode == 'OPCTMG') {
            $assinee = \app\models\TenantAgreements::find()
                ->select($select)
                ->leftJoin('users', 'users.id = tenant_agreements.tenant_id')
                ->leftJoin('tenant_profile', 'tenant_profile.tenant_id = tenant_agreements.tenant_id')
                ->leftJoin('leads_tenant', 'leads_tenant.email_id = users.login_id')
                ->leftJoin('properties', 'tenant_agreements.parent_id = properties.id')
                ->leftJoin('property_types', 'properties.property_type = property_types.id')
                ->where(['leads_tenant.lead_city' => $currentRoleValue]);
        } else if ($currentRoleCode == 'OPBRMG') {
            $assinee = \app\models\TenantAgreements::find()
                ->select($select)
                ->leftJoin('users', 'users.id = tenant_agreements.tenant_id')
                ->leftJoin('tenant_profile', 'tenant_profile.tenant_id = tenant_agreements.tenant_id')
                ->leftJoin('leads_tenant', 'leads_tenant.email_id = users.login_id')
                ->leftJoin('properties', 'tenant_agreements.parent_id = properties.id')
                ->leftJoin('property_types', 'properties.property_type = property_types.id')
                ->where(['tenant_profile.branch_code' => $currentRoleValue]);
        } else if ($currentRoleCode == 'OPEXE') {
            $branchModel = \app\models\Branches::find()->where(['branch_code' => $currentRoleValue])->one();
            $assinee = \app\models\TenantAgreements::find()
                ->select($select)
                ->leftJoin('users', 'users.id = tenant_agreements.tenant_id')
                ->leftJoin('tenant_profile', 'tenant_profile.tenant_id = tenant_agreements.tenant_id')
                ->leftJoin('leads_tenant', 'leads_tenant.email_id = users.login_id')
                ->leftJoin('properties', 'tenant_agreements.parent_id = properties.id')
                ->leftJoin('property_types', 'properties.property_type = property_types.id')
                ->where(['tenant_profile.branch_code' => $currentRoleValue])
                ->andWhere(['leads_tenant.lead_city' => $branchModel->city_code])
                ->andWhere(['tenant_profile.operation_id' => Yii::$app->user->id]);
        }
        
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

        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $assinee,
            'sort' => false,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $assinee->orderBy($sort);

        return $dataProvider;
    }
}
