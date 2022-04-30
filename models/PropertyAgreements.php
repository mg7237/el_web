<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "property_agreements".
 *
 * @property integer $id
 * @property integer $owner_id
 * @property integer $property_id
 * @property double $rent
 * @property double $manteinance
 * @property double $deposit
 * @property string $rent_start_date
 * @property string $notice_peroid
 * @property string $min_contract_peroid
 * @property string $contract_start_date
 * @property string $contract_end_date
 * @property integer $furniture_rent
 * @property double $pms_commission
 * @property integer $created_by
 * @property integer $updated_by
 * @property string $created_date
 * @property integer $updated_date
 */
class PropertyAgreements extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'property_agreements';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['owner_id', 'property_id', 'rent', 'manteinance', 'deposit', 'rent_start_date', 'notice_peroid', 'min_contract_peroid', 'contract_start_date', 'contract_end_date', 'pms_commission', 'agreement_type', 'pms_commission'], 'required'],
            [['owner_id', 'property_id', 'furniture_rent', 'created_by', 'updated_by', 'updated_date'], 'integer'],
            [['rent', 'manteinance', 'deposit', 'pms_commission'], 'number'],
            [['rent_start_date', 'contract_start_date', 'contract_end_date', 'created_date'], 'safe'],
            [['notice_peroid', 'min_contract_peroid'], 'string', 'max' => 100],
            //[['agreement_url'], 'string', 'max' => 255],
            [['agreement_url'], 'file', 'extensions' => 'docx,pdf,doc', 'skipOnEmpty' => true],
            ['contract_end_date', 'compareIt'],
            ['rent_start_date', 'compareIt1', 'when' => function($model) {
                    return $model->agreement_type == 2;
                }],
            ['rent_start_date', 'compareIt2', 'when' => function($model) {
                    return $model->agreement_type == 2;
                }]
        ];
    }

    public function compareIt($attribute, $params) {
        if (strtotime($this->contract_end_date) <= strtotime($this->contract_start_date)) {
            $this->addError($attribute, 'Contract End Date must be later than Contract Start Date');
        }
    }

    public function compareIt1($attribute, $params) {
        if (strtotime($this->rent_start_date) <= strtotime($this->contract_start_date)) {
            $this->addError($attribute, 'Rent Start Date must be between Contract Start and End Dates');
        }
    }

    public function compareIt2($attribute, $params) {
        if (strtotime($this->rent_start_date) >= strtotime($this->contract_end_date)) {
            $this->addError($attribute, 'Contract End Date must be Later Contract Start Date');
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'owner_id' => 'Owner ID',
            'property_id' => 'Property ID',
            'rent' => 'Rent',
            'manteinance' => 'Maintenance',
            'deposit' => 'Deposit',
            'rent_start_date' => 'Rent start date',
            'notice_peroid' => 'Notice peroid',
            'min_contract_peroid' => 'Min contract peroid',
            'contract_start_date' => 'Contract start date',
            'contract_end_date' => 'Contract end date',
            'pms_commission' => 'Pms commission',
            'created_by' => 'Created by',
            'agreement_url' => 'Agreement',
            'updated_by' => 'Updated by',
            'created_date' => 'Created date',
            'updated_date' => 'Updated date',
            'agreement_type' => 'Agreement type',
            'pms_commission' => 'PMS fees',
        ];
    }

    public function beforeSave($insert) {
        if (isset($this->rent_start_date)) {
            $this->rent_start_date = date('Y-m-d', strtotime($this->rent_start_date));
        }
        if (isset($this->contract_start_date)) {
            $this->contract_start_date = date('Y-m-d', strtotime($this->contract_start_date));
        }
        if (isset($this->contract_end_date)) {
            $this->contract_end_date = date('Y-m-d', strtotime($this->contract_end_date));
        }

        return true;
    }

    public function afterFind() {
        if (isset($this->rent_start_date)) {
            $this->rent_start_date = date('d-M-Y', strtotime($this->rent_start_date));
        }
        if (isset($this->contract_start_date)) {
            $this->contract_start_date = date('d-M-Y', strtotime($this->contract_start_date));
        }
        if (isset($this->contract_end_date)) {
            $this->contract_end_date = date('d-M-Y', strtotime($this->contract_end_date));
        }

        return true;
    }

    public function afterSave($insert, $changed) {
        if (isset($this->rent_start_date)) {
            $this->rent_start_date = date('d-M-Y', strtotime($this->rent_start_date));
        }
        if (isset($this->contract_start_date)) {
            $this->contract_start_date = date('d-M-Y', strtotime($this->contract_start_date));
        }
        if (isset($this->contract_end_date)) {
            $this->contract_end_date = date('d-M-Y', strtotime($this->contract_end_date));
        }

        return true;
    }

}
