<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tenant_profile".
 *
 * @property integer $id
 * @property integer $tenant_id
 * @property string $emer_contact
 * @property integer $status
 * @property integer $sales_id
 * @property integer $operation_id
 * @property string $address_line_1
 * @property string $address_line_2
 * @property integer $state
 * @property integer $city
 * @property integer $region
 * @property integer $pincode
 * @property string $phone
 * @property string $proof_type
 * @property string $proof_document_url
 * @property string $account_holder_name
 * @property string $bank_name
 * @property string $bank_branchname
 * @property string $bank_ifcs
 * @property string $pan_number
 * @property string $cancelled_check
 * @property string $employer_name
 * @property string $employee_id
 * @property string $employment_start_date
 * @property string $employment_email
 * @property string $employment_proof_url
 * @property string $employment_proof_type
 * @property string $account_number
 */
class TenantProfile extends \yii\db\ActiveRecord {

    public $address_proof;
    public $identity_proof;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tenant_profile';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['tenant_id', 'account_holder_name', 'bank_name', 'bank_branchname', 'bank_ifcs', 'account_number', 'pan_number', 'address_line_1', 'address_line_2', 'state', 'city_name', 'pincode', 'phone', 'operation_id'], 'required'],
            [['tenant_id', 'emergency_contact_number'], 'integer'],
            [['pincode'], 'string', 'max' => 15],
            [['employment_start_date'], 'safe'],
            [['profile_image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpeg, png, jpg', 'maxFiles' => 1],
            [['address_proof'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpeg, png, jpg', 'maxFiles' => 1],
            [['identity_proof'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpeg, png, jpg', 'maxFiles' => 1],
            [['cancelled_check'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpeg, png, jpg', 'maxFiles' => 1],
            //[['employment_proof_url'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpeg, png, jpg', 'maxFiles' => 1],
            ['bank_ifcs', 'checkIfsc'],
            ['phone', 'contactCheck'],
            [['emer_contact', 'emergency_contact_name', 'bank_branchname', 'bank_ifcs', 'account_number', 'pan_number', 'employer_name', 'employee_id'], 'string', 'max' => 255],
            [['emer_contact', 'proof_type', 'account_holder_name', 'bank_name', 'employment_email', 'employment_proof_type', 'address_line_1', 'address_line_2'], 'string', 'max' => 1000],
            //[['proof_document_url', 'employment_proof_url'], 'string', 'max' => 10000],
            //[['phone'], 'string', 'max' => 110],
            //['phone', 'match', 'pattern' => '/^\d{10,15}$/', 'message' => 'Phone must contain Minimum 10 digits.'],
            [['phone'], 'string', 'max' => 15],
            ['operation_id', 'integer', 'min' => 1, 'tooSmall' => 'operation person cannot be blank'],
                // ['bank_ifcs','checkIsArray'],
        ];
    }

    public function contactCheck($attribute, $params) {
        $phone = Yii::$app->userdata->trimPhone($this->phone);
        
        if (empty($this->id)) {
            $model = Users::find()->where(['phone' => $phone])->one();

            if ($model) {
                $this->addError($attribute, Yii::t('app', '"' . $this->phone . '" is already in use'));
            }
        } else {
            $adProfile = \app\models\TenantProfile::find()->where(['phone' => $phone])->andWhere(['id' => $this->id])->one();
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

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'tenant_id' => 'Tenant ID',
            'emer_contact' => 'Contact Email',
            'emergency_contact_name' => 'Contact name',
            'emergency_contact_email' => 'Contact email',
            'status' => 'Status',
            'sales_id' => 'Sales ID',
            'operation_id' => 'Operation ID',
            'address_line_1' => 'Address line 1',
            'address_line_2' => 'Address line 2',
            'state' => 'State',
            'city_name' => 'City',
            'region' => 'Branch',
            'pincode' => 'Pincode',
            'phone' => 'Phone',
            'proof_type' => 'Proof type',
            'proof_document_url' => 'Proof document url',
            'account_holder_name' => 'Account holder name',
            'bank_name' => 'Bank name',
            'bank_branchname' => 'Bank branchname',
            'bank_ifcs' => 'Bank IFSC code',
            'pan_number' => 'Pan number',
            'cancelled_check' => 'Cancelled cheque',
            'employer_name' => 'Employer name',
            'employee_id' => 'Employee ID',
            'employment_start_date' => 'Employment start date',
            'employment_email' => 'Employment email',
            'employment_proof_url' => 'Employment proof',
            'employment_proof_type' => 'Employment proof type',
            'account_number' => 'Account number',
            'profile_image' => 'Profile image',
        ];
    }

    public function beforeSave($insert) {
        if (isset($this->employment_start_date)) {
            $this->employment_start_date = date('Y-m-d', strtotime($this->employment_start_date));
        }
        return true;
    }

    public function afterFind() {
        if (isset($this->employment_start_date)) {
            $this->employment_start_date = date('d-M-Y', strtotime($this->employment_start_date));
        }
        return true;
    }

    public function afterSave($insert, $changed) {
        if (isset($this->employment_start_date)) {
            $this->employment_start_date = date('d-M-Y', strtotime($this->employment_start_date));
        }
        return true;
    }

    public function getUsersinfo() {
        return $this->hasOne(Users::className(), ['id' => 'tenant_id']);
    }
}
