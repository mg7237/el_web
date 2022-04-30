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
class PropertyListing extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'property_listing';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['property_id', 'rent', 'deposit', 'token_amount', 'maintenance', 'maintenance_included', 'availability_from'], 'required'],
            [['property_id'], 'integer'],
            [['rent', 'deposit', 'token_amount', 'maintenance'], 'number'],
            [['maintenance_included'], 'string'],
            [['availability_from'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'property_id' => 'Property ID',
            'rent' => 'Rent',
            'deposit' => 'Deposit',
            'token_amount' => 'Token amount',
            'maintenance' => 'Maintenance',
            'maintenance_included' => 'Maintenance included',
            'availability_from' => 'Availability from',
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
