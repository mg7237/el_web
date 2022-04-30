<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bank_details".
 *
 * @property integer $id
 * @property string $account_holder_name
 * @property string $bank_name
 * @property string $account_number
 * @property string $ifsc_code
 * @property string $email_id
 * @property integer $created_by
 * @property integer $updated_by
 * @property string $create_time
 * @property string $update_time
 */
class BankDetails extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'bank_details';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['account_holder_name', 'bank_name', 'account_number', 'ifsc_code'], 'required'],
            [['created_by', 'updated_by'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['account_holder_name', 'bank_name', 'email_id'], 'string', 'max' => 255],
            [['account_number', 'ifsc_code'], 'string', 'max' => 100],
            ['ifsc_code', 'checkIfsc'],
        ];
    }

    public function checkIfsc($attribute, $params) {
        $ifsc = Yii::$app->userdata->getIFSCValidate($this->ifsc_code);
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
            'account_holder_name' => 'Account holder name',
            'bank_name' => 'Bank name',
            'account_number' => 'Account number',
            'ifsc_code' => 'Ifsc code',
            'email_id' => 'Email id',
            'created_by' => 'Created by',
            'updated_by' => 'Updated by',
            'create_time' => 'Create time',
            'update_time' => 'Update time',
        ];
    }

}
