<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "applicant_profile".
 *
 * @property integer $id
 * @property string $emergency_contact_name
 * @property integer $applicant_id
 * @property string $emergency_contact_email
 * @property string $proof_type
 * @property string $proof_document_url
 * @property string $account_holder_name
 * @property string $bank_name
 * @property string $bank_branchname
 * @property string $bank_ifcs
 * @property string $bank_account_number
 * @property string $pan_number
 * @property string $employer_name
 * @property string $employee_id
 * @property string $employment_start_date
 * @property string $employment_email
 * @property string $employmnet_proof_url
 * @property string $employmnet_proof_type
 * @property string $emergency_contact_number
 * @property string $email_id
 * @property string $address_line_1
 * @property string $address_line_2
 * @property integer $state
 * @property integer $city
 * @property integer $pincode
 * @property string $phone
 */
class ApplicantProfile extends \yii\db\ActiveRecord {

    public $address_proof;
    public $identity_proof;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'applicant_profile';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['account_holder_name', 'bank_name', 'bank_branchname', 'bank_ifcs', 'bank_account_number', 'pan_number', 'email_id', 'address_line_1', 'address_line_2', 'pincode', 'phone', 'city_name', 'state'], 'required'],
            [['pincode'], 'string', 'max' => 15],
            [['employment_start_date'], 'safe'],
            [['profile_image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpeg, png, jpg', 'maxFiles' => 1],
            [['address_proof'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpeg, png, jpg', 'maxFiles' => 1],
            [['identity_proof'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpeg, png, jpg', 'maxFiles' => 1],
            [['cancelled_check'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpeg, png, jpg', 'maxFiles' => 1],
            [['employmnet_proof_url'], 'file', 'skipOnEmpty' => true, 'extensions' => 'pdf', 'maxFiles' => 1],
            [['emergency_contact_name', 'bank_branchname', 'bank_ifcs', 'bank_account_number', 'pan_number', 'employer_name', 'employee_id'], 'string', 'max' => 255],
            [['emergency_contact_email', 'proof_type', 'account_holder_name', 'bank_name', 'employment_email', 'employmnet_proof_type', 'emergency_contact_number', 'email_id', 'address_line_1', 'address_line_2'], 'string', 'max' => 1000],
            [['proof_document_url', 'employmnet_proof_url'], 'string', 'max' => 10000],
            [['phone'], 'string', 'max' => 15],
           // ['phone', 'match', 'pattern' => '^[0-9a-zA-Z0-9_]{10,15}$', 'message' => 'Phone must contain Minimum 10 digits.'],
            [['email_id', 'emergency_contact_email', 'employment_email'], 'email'],
            ['email_id', 'unique'],
            ['email_id', 'emailCheck'],
            ['phone', 'contactCheck'],
            ['bank_ifcs', 'checkIfsc']
        ];
    }

    public function checkIfsc($attribute, $params) {


        $ifsc = Yii::$app->userdata->getIFSCValidate($this->bank_ifcs);
        if ($ifsc == 'error') {
            $this->addError($attribute, 'Invalid IFSC code');
        }
    }

    public function emailCheck($attribute, $params) {
        if (empty($this->id)) {
            $model = Users::find()->where(['login_id' => $this->email_id])->one();

            if ($model) {
                $this->addError($attribute, Yii::t('app', '"' . $this->email_id . '" is already in use'));
            }
        } else {
            $adProfile = \app\models\ApplicantProfile::find()->where(['email_id' => $this->email_id])->andWhere(['id' => $this->id])->one();
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
            $adProfile = \app\models\ApplicantProfile::find()->where(['phone' => $phone])->andWhere(['id' => $this->id])->one();
            if (empty($adProfile)) {
                $model = Users::find()->where(['phone' => $phone])->one();
                if ($model) {
                    $this->addError($attribute, Yii::t('app', '"' . $this->phone . '" is already in use'));
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
            'emergency_contact_name' => 'Contact name',
            'emergency_contact_number' => 'Contact number',
            'applicant_id' => 'Applicant id',
            'emergency_contact_email' => 'Contact email',
            'proof_type' => 'Proof type',
            'proof_document_url' => 'Proof document url',
            'account_holder_name' => 'Account holder name',
            'bank_name' => 'Bank name',
            'bank_branchname' => 'Bank branchname',
            'bank_ifcs' => 'Bank IFSC code',
            'bank_account_number' => 'Bank account number',
            'pan_number' => 'Pan Number',
            'employer_name' => 'Employer Name',
            'employee_id' => 'Employee ID',
            'employment_start_date' => 'Employment Start Date',
            'employment_email' => 'Employment Email',
            'employmnet_proof_url' => 'Employment Proof',
            'employmnet_proof_type' => 'Employment Proof Type',
            'emergency_contact_number' => 'Emergency Contact Number',
            'email_id' => 'Email ID',
            'address_line_1' => 'Address line 1',
            'address_line_2' => 'Address line 2',
            'state' => 'State',
            'city' => 'City',
            'city_name' => 'Tenant City',
            'pincode' => 'Pincode',
            'phone' => 'Phone',
            'profile_image' => 'Profile Image',
            'cancelled_check' => 'Cancelled Cheque',
            'employment_email' => 'Employee Email',
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
        return $this->hasOne(Users::className(), ['id' => 'applicant_id']);
    }

}
