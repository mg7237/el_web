<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "users".
 *
 * @property integer $id
 * @property string $full_name
 * @property string $password
 * @property string $email
 * @property string $phone
 * @property string $address
 * @property string $lat
 * @property string $lon
 * @property string $state
 * @property string $city
 * @property string $pincode
 * @property string $ad_company
 * @property string $ad_properties
 * @property string $ow_interested
 * @property string $ow_comments
 * @property string $auth_key
 * @property string $password_reset_token
 * @property integer $role
 * @property string $created
 */
class AdhocPayments extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'adhoc_payments';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                // [['amount', 'transaction_id', 'adhoc_id', 'type_of_payment'],'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'adhoc_id' => 'Adhoc Id',
            'transaction_id' => 'Transaction id',
            'amount' => 'Amount',
            'type_of_payment' => 'Type of payment',
            'created_date' => 'Created date',
        ];
    }

}

?>
