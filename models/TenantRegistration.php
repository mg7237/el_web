<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tenant_registration".
 *
 * @property integer $id
 * @property string $full_name
 * @property string $address_line_1
 * @property string $address_line_2
 * @property string $city_name
 * @property string $state_code
 * @property string $pin_code
 * @property string $email
 * @property string $mobile
 * @property double $rent
 * @property double $maintenance
 * @property double $deposit
 * @property string $check_in_date
 * @property integer $property_id
 * @property string $room_no
 * @property string $emergency_contact_name
 * @property string $emergency_contact_number
 * @property string $emergency_contact_email
 * @property string $employer_details
 * @property string $exit_date
 * @property string $photo
 * @property integer $id_document_type_1
 * @property string $id_document_1
 * @property integer $id_document_type_2
 * @property string $id_document_2
 * @property integer $user_id
 */
class TenantRegistration extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tenant_registration';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['full_name', 'address_line_1', 'city_name', 'state_code', 'pin_code', 'email', 'mobile', 'rent', 'maintenance', 'deposit', 'check_in_date', 'property_id', 'emergency_contact_email'], 'required'],
            [['rent', 'maintenance', 'deposit'], 'number'],
            [['check_in_date', 'exit_date'], 'safe'],
            [['property_id', 'id_document_type_1', 'id_document_type_2', 'user_id'], 'integer'],
            [['full_name', 'address_line_1', 'address_line_2', 'city_name', 'email', 'emergency_contact_name', 'emergency_contact_email', 'employer_details', 'photo', 'id_document_1', 'id_document_2'], 'string', 'max' => 999],
            [['state_code'], 'string', 'max' => 3],
            [['pin_code', 'mobile', 'emergency_contact_number'], 'string', 'max' => 15],
            [['room_no'], 'string', 'max' => 99],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'full_name' => 'Full Name',
            'address_line_1' => 'Address Line 1',
            'address_line_2' => 'Address Line 2',
            'city_name' => 'City Name',
            'state_code' => 'State Code',
            'pin_code' => 'Pin Code',
            'email' => 'Email',
            'mobile' => 'Mobile',
            'rent' => 'Rent',
            'maintenance' => 'Maintenance',
            'deposit' => 'Deposit',
            'check_in_date' => 'Check In Date',
            'property_id' => 'Property ID',
            'room_no' => 'Room No',
            'emergency_contact_name' => 'Emergency Contact Name',
            'emergency_contact_number' => 'Emergency Contact Number',
            'emergency_contact_email' => 'Emergency Contact Email',
            'employer_details' => 'Employer Details',
            'exit_date' => 'Exit Date',
            'photo' => 'Photo',
            'id_document_type_1' => 'Id Document Type 1',
            'id_document_1' => 'Id Document 1',
            'id_document_type_2' => 'Id Document Type 2',
            'id_document_2' => 'Id Document 2',
            'user_id' => 'User ID',
        ];
    }
}
