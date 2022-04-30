<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "leads_references".
 *
 * @property integer $id
 * @property string $full_name
 * @property string $email
 * @property string $contact_number
 * @property string $address
 * @property string $city
 * @property string $state
 * @property string $pincode
 * @property string $communication
 * @property string $reference_type
 * @property string $ref_status
 * @property integer $reffered_to
 * @property integer $created_by
 * @property integer $updated_by
 * @property string $create_time
 */
class LeadsReferences extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'leads_references';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['full_name', 'email', 'contact_number', 'address', 'city', 'state', 'region', 'pincode', 'communication', 'reference_type'], 'required'],
            [['communication', 'reference_type'], 'string'],
            [['reffered_to', 'created_by', 'updated_by'], 'integer'],
            [['create_time'], 'safe'],
            [['full_name', 'email', 'contact_number', 'city', 'state', 'pincode'], 'string', 'max' => 100],
            [['address'], 'string', 'max' => 255],
            [['email'], 'email'],
            ['email', 'unique'],
            ['email', 'emailCheck'],
            ['contact_number', 'match', 'pattern' => '/^\d{10}$/', 'message' => 'Contact Number must contain exactly 10 digits.'],
        ];
    }

    public function emailCheck($attribute, $params) {
        $model = Users::find()->where(['email' => $this->email])->one();

        if ($model)
            $this->addError($attribute, Yii::t('app', '"' . $this->email . '" is already in use'));
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'full_name' => 'Full name',
            'email' => 'Email',
            'contact_number' => 'Contact number',
            'address' => 'Address',
            'city' => 'City',
            'state' => 'State',
            'pincode' => 'Pincode',
            'communication' => 'Communication',
            'reference_type' => 'Reference type',
            'ref_status' => 'Ref status',
            'reffered_to' => 'Reffered to',
            'created_by' => 'Created by',
            'updated_by' => 'Updated by',
            'create_time' => 'Create time',
        ];
    }

}
