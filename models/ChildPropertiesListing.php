<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "property_listing".
 *
 * @property integer $id
 * @property integer $property_id
 * @property double $rent
 * @property double $deposit
 * @property double $token_amount
 * @property double $maintenance
 * @property string $maintenance_included
 * @property string $availability_from
 */
class ChildPropertiesListing extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'child_property_listing';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['child_id', 'rent', 'deposit', 'token_amount', 'maintenance', 'parent_id', 'main', 'availability_from'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'child_id' => 'Property id',
            'parent_id' => 'Parent id',
            'main' => 'Main',
            'rent' => 'Rent',
            'deposit' => 'Deposit',
            'token_amount' => 'Token amount',
            'maintenance' => 'Maintenance',
            'availability_from' => 'Availability from',
            'created_date' => 'Created date',
        ];
    }

    public function beforeSave($insert) {
        if (isset($this->availability_from)) {
            $this->availability_from = date('Y-m-d', strtotime($this->availability_from));
        }


        return true;
    }

    public function afterFind() {
        if (isset($this->availability_from)) {
            $this->availability_from = date('d-M-Y', strtotime($this->availability_from));
        }

        return true;
    }

    public function afterSave($insert, $changed) {
        if (isset($this->availability_from)) {
            $this->availability_from = date('d-M-Y', strtotime($this->availability_from));
        }

        return true;
    }

}
