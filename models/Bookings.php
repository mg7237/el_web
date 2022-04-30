<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bookings".
 *
 * @property integer $id
 * @property integer $property_id
 * @property integer $tenant_id
 * @property integer $payment_id
 * @property string $type_of_booking
 * @property integer $booking_count
 * @property string $move_in
 * @property string $booking_time
 * @property integer $advisor_id
 * @property string $exit_date
 */
class Bookings extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'bookings';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'property_id', 'tenant_id', 'payment_id', 'type_of_booking', 'booking_count', 'move_in', 'booking_time', 'advisor_id', 'exit_date'], 'required'],
            [['id', 'property_id', 'tenant_id', 'payment_id', 'booking_count', 'advisor_id'], 'integer'],
            [['type_of_booking'], 'string'],
            [['move_in', 'booking_time', 'exit_date'], 'safe'],
        ];
    }

    public function getUsers() {
        return $this->hasMany(Users::className(), ['id' => 'tenant_id']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'property_id' => 'Property ID',
            'tenant_id' => 'Tenant ID',
            'payment_id' => 'Payment ID',
            'type_of_booking' => 'Type of booking',
            'booking_count' => 'Booking count',
            'move_in' => 'Move in',
            'booking_time' => 'Booking time',
            'advisor_id' => 'Advisor ID',
            'exit_date' => 'Exit date',
        ];
    }

    public function beforeSave($insert) {
        if (isset($this->exit_date)) {
            $this->exit_date = date('Y-m-d', strtotime($this->exit_date));
        }


        return true;
    }

    public function afterFind() {
        if (isset($this->exit_date)) {
            $this->exit_date = date('d-M-Y', strtotime($this->exit_date));
        }

        return true;
    }

    public function afterSave($insert, $changed) {
        if (isset($this->exit_date)) {
            $this->exit_date = date('d-M-Y', strtotime($this->exit_date));
        }

        return true;
    }

}
