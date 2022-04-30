<?php

namespace app\models;

use Yii;

class InvestorAgreements extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'property_investor_agreements';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['investor_id', 'property_id', 'EL_Fee_Percentage', 'agreement_url', 'created_date', 'created_by'], 'required'],
            [['investor_id', 'property_id'], 'integer'],
            [['created_date'], 'safe'],
            [['agreement_url'], 'file', 'extensions' => 'docx,pdf,doc', 'skipOnEmpty' => true]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'investor_id' => 'Investor ID',
            'property_id' => 'Property ID',
            'Start_Date' => 'Start date',
            'End_Date' => 'End date',
            'Min_Guaranteed_Income_Percent' => 'Minimum guaranteed income percent',
            'Min_Guaranteed_Income_Gross' => 'Minimum guaranteed income gross',
            'PnL_Share' => 'Profit & loss share',
            'Fixed_Gross' => 'Fixed groos',
            'Fixed_Percent' => 'Fixed percent',
            'EL_Fee_Gross' => 'EL fee gross',
            'EL_Fee_Percentage' => 'EL fee percentage',
            'agreement_url' => 'Agreement',
            'created_date' => 'Created date',
            'created_by' => 'Created by'
        ];
    }
}
