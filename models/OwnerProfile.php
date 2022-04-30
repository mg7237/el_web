<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "owner_profile".
 *
 * @property integer $id
 * @property integer $owner_id
 * @property string $ow_interested
 * @property string $ow_comments
 * @property string $emer_contact
 * @property string $emergency_contact_number
 * @property string $emergency_contact_email
 * @property string $account_holder_name
 * @property string $bank_name
 * @property string $bank_branchname
 * @property string $bank_ifcs
 * @property string $bank_account_number
 * @property string $pan_number
 * @property string $cancelled_check
 * @property string $status
 * @property integer $sales_id
 * @property integer $operation_id
 * @property string $phone
 * @property string $address_line_1
 * @property integer $state
 * @property integer $city
 * @property integer $region
 * @property integer $pincode
 */
class OwnerProfile extends \yii\db\ActiveRecord {

    public $address_proof;
    public $identity_proof;
    public $email_id;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'owner_profile';
    }

    /**
     * @inheritdocai
     */
    public function rules() {
        return [
            //    [['owner_id', 'ow_interested', 'ow_comments', 'ler_contact', 'emergency_contact_number', 'emergency_contact_email', 'account_holder_name', 'bank_name', 'bank_branchname', 'bank_ifcs', 'bank_account_number', 'pan_number', 'address_line_1','address_line_2', 'state', 'city', 'region', 'pincode'], 'required'],
            [['owner_id', 'account_holder_name', 'bank_name', 'bank_branchname', 'bank_ifcs', 'bank_account_number', 'pan_number', 'address_line_1', 'address_line_2', 'state', 'pincode', 'phone', 'operation_id', 'city_name'], 'required'],
            [['owner_id', 'sales_id', 'operation_id'], 'integer'],
            [['ow_comments',  ], 'string'],
            ['pincode', 'match', 'pattern' => '/^\w{6,15}$/'],
            [['profile_image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpeg, png, jpg', 'maxFiles' => 1],
            [['ow_interested', 'emer_contact', 'emergency_contact_number', 'emergency_contact_email', 'account_holder_name', 'bank_name', 'bank_branchname', 'bank_ifcs', 'bank_account_number', 'pan_number', 'cancelled_check'], 'string', 'max' => 255],
            [['address_line_1', 'address_line_2'], 'string', 'max' => 1000],
           // ['phone', 'match', 'pattern' => '/^\d{10,15}$/', 'message' => 'Phone must contain Minimum 10 digits.'],
            [['phone'], 'string', 'max' => 15],
            ['phone', 'contactCheck'],
            ['emergency_contact_email', 'email'],
            ['operation_id', 'integer', 'min' => 0, 'tooSmall' => 'operation person cannot be blank'],
            ['bank_ifcs', 'checkIfsc']
        ];
    }

    public function contactCheck($attribute, $params) {
        $phone = Yii::$app->userdata->trimPhone($this->phone);
        
        if (empty($this->id)) {
            $model = Users::find()->where(['phone' => $phone])->one();

            if ($model)
                $this->addError($attribute, Yii::t('app', '"' . $this->phone . '" is already in use'));
        } else {
            $ownerProfile = \app\models\OwnerProfile::find()->where(['phone' => $phone])->andWhere(['id' => $this->id])->one();
            if (empty($ownerProfile)) {
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

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'owner_id' => 'Owner ID',
            'ow_interested' => 'Lease Options',
            'ow_comments' => 'Owner comments',
            'emer_contact' => 'Contact name',
            'emergency_contact_number' => 'Contact number',
            'emergency_contact_email' => 'Contact email',
            'account_holder_name' => 'Account holder name',
            'bank_name' => 'Bank name',
            'bank_branchname' => 'Bank branchname',
            'bank_ifcs' => 'Bank IFSC code',
            'bank_account_number' => 'Bank account number',
            'pan_number' => 'Pan number',
            'cancelled_check' => 'Cancelled cheque',
            'status' => 'Status',
            'sales_id' => 'Sales ID',
            'operation_id' => 'Operation Person',
            'phone' => 'Phone number',
            'address_line_1' => 'Address line 1',
            'state' => 'State',
            'city_name' => 'Owner City',
            'region' => 'Branch',
            'pincode' => 'Pincode',
        ];
    }

    public function getUsersinfo() {
        return $this->hasOne(Users::className(), ['id' => 'owner_id']);
    }
    
    public function getPropertyOwnerManageList ($params) {
        $userType = Yii::$app->user->identity->user_type;
        if ($userType == 6) {
            return $this->search($params);
        } else {
            return $this->searchOperation($params);
        }
    }

    public function search($params) {
        $assinee = '';
        $userAssignments = \app\models\Users::find()->where(['id' => Yii::$app->user->id])->one();
        $opsProfileModel = \app\models\SalesProfile::findOne(['sale_id' => Yii::$app->user->id]);
        $currentRoleCode = $opsProfileModel->role_code;
        $currentRoleValue = $opsProfileModel->role_value;
        
        if ($currentRoleValue == 'ELHQ') {
            $assinee = \app\models\OwnerProfile::find()
                ->leftJoin('users', 'users.id = owner_profile.owner_id')
                ->where(['users.status' => 1]);
        } else if ($currentRoleCode == 'SLSTMG') {
            $assinee = \app\models\OwnerProfile::find()
                ->leftJoin('users', 'users.id = owner_profile.owner_id')
                ->where(['owner_profile.state' => $currentRoleValue])
                ->andWhere(['users.status' => 1]);
        } else if ($currentRoleCode == 'SLCTMG') {
            $assinee = \app\models\OwnerProfile::find()
                ->leftJoin('users', 'users.id = owner_profile.owner_id')
                ->leftJoin('leads_owner', 'users.login_id = leads_owner.email_id')
                ->where(['leads_owner.lead_city' => $currentRoleValue])
                ->andWhere(['users.status' => 1]);
        } else if ($currentRoleCode == 'SLBRMG') {
            $assinee = \app\models\OwnerProfile::find()
                ->leftJoin('users', 'users.id = owner_profile.owner_id')
                ->where(['owner_profile.branch_code' => $currentRoleValue])
                ->andWhere(['users.status' => 1]);
        } else if ($currentRoleCode == 'SLEXE') {
            $branchModel = \app\models\Branches::find()->where(['branch_code' => $currentRoleValue])->one();
            $assinee = \app\models\OwnerProfile::find()
                ->leftJoin('users', 'users.id = owner_profile.owner_id')
                ->leftJoin('leads_owner', 'users.login_id = leads_owner.email_id')
                ->where(['owner_profile.branch_code' => $currentRoleValue])
                ->andWhere(['leads_owner.lead_city' => $branchModel->city_code])
                ->andWhere(['owner_profile.sales_id' => Yii::$app->user->id])
                ->andWhere(['users.status' => 1]);
        }
        
        $dataProvider = new ActiveDataProvider([
            'query' => $assinee,
            'sort' => false
        ]);


        $this->load($params);

        $assinee->andFilterWhere([
            'id' => $this->id,
            'phone' => $this->phone,
        ]);


        return $dataProvider;
    }

    public function searchOperation($params) {
        $assinee = OwnerProfile::find()->joinWith('usersinfo', true);
        if (isset($params['LeadsTenant']['sort'])) {
            if ($params['LeadsTenant']['sort'] == '') {
                $sort = 'leads_owner.id desc';
            } else {
                $sort = $params['LeadsTenant']['sort'];
            }
        } else {
            $sort = 'owner_profile.id desc';
        }
        
        $userAssignments = \app\models\Users::find()->where(['id' => Yii::$app->user->id])->one();
        $opsProfileModel = \app\models\OperationsProfile::findOne(['operations_id' => Yii::$app->user->id]);
        $currentRoleCode = $opsProfileModel->role_code;
        $currentRoleValue = $opsProfileModel->role_value;
        
        if ($currentRoleValue == 'ELHQ') {
            $assinee = \app\models\OwnerProfile::find()
                ->leftJoin('users', 'users.id = owner_profile.owner_id')
                ->leftJoin('leads_owner', 'users.login_id = leads_owner.email_id')
                ->where(['users.status' => 1]);
        } else if ($currentRoleCode == 'OPSTMG') {
            $assinee = \app\models\OwnerProfile::find()
                ->leftJoin('users', 'users.id = owner_profile.owner_id')
                ->leftJoin('leads_owner', 'users.login_id = leads_owner.email_id')
                ->where(['owner_profile.state' => $currentRoleValue])
                ->andWhere(['users.status' => 1]);
        } else if ($currentRoleCode == 'OPCTMG') {
            $assinee = \app\models\OwnerProfile::find()
                ->leftJoin('users', 'users.id = owner_profile.owner_id')
                ->leftJoin('leads_owner', 'users.login_id = leads_owner.email_id')
                ->where(['leads_owner.lead_city' => $currentRoleValue])
                ->andWhere(['users.status' => 1]);
        } else if ($currentRoleCode == 'OPBRMG') {
            $assinee = \app\models\OwnerProfile::find()
                ->leftJoin('users', 'users.id = owner_profile.owner_id')
                ->leftJoin('leads_owner', 'users.login_id = leads_owner.email_id')
                ->where(['owner_profile.branch_code' => $currentRoleValue])
                ->andWhere(['users.status' => 1]);
        } else if ($currentRoleCode == 'OPEXE') {
            $branchModel = \app\models\Branches::find()->where(['branch_code' => $currentRoleValue])->one();
            $assinee = \app\models\OwnerProfile::find()
                ->leftJoin('users', 'users.id = owner_profile.owner_id')
                ->leftJoin('leads_owner', 'users.login_id = leads_owner.email_id')
                ->where(['owner_profile.branch_code' => $currentRoleValue])
                ->andWhere(['leads_owner.lead_city' => $branchModel->city_code])
                ->andWhere(['owner_profile.operation_id' => Yii::$app->user->id])
                ->andWhere(['users.status' => 1]);
        }

        if (isset($params['LeadsOwner']['search'])) {
            $searchParam = $params['LeadsOwner']['search'];

            $assinee->andFilterWhere(
                ['or',
                ['like', 'users.full_name', trim($searchParam)],
                ['like', 'leads_owner.email_id', trim($searchParam)],
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
