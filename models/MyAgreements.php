<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "my_agreements".
 *
 * @property integer $id
 * @property string $email_id
 * @property integer $property_id
 * @property integer $agreement_id
 * @property string $start_date
 * @property string $end_date
 * @property string $create_date
 * @property string $update_time
 */
class MyAgreements extends \yii\db\ActiveRecord {

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
            [['owner_id', 'property_id', 'agreement_id', 'start_date', 'end_date', 'create_date', 'update_time'], 'required'],
            [['property_id', 'agreement_id'], 'integer'],
            [['start_date', 'end_date', 'create_date', 'update_time'], 'safe'],
        ];
    }

    public function beforeSave($insert) {
        if (isset($this->start_date)) {
            $this->start_date = date('Y-m-d', strtotime($this->start_date));
        }
        if (isset($this->end_date)) {
            $this->end_date = date('Y-m-d', strtotime($this->end_date));
        }


        return true;
    }

    public function afterFind() {
        if (isset($this->start_date)) {
            $this->start_date = date('d-M-Y', strtotime($this->start_date));
        }
        if (isset($this->end_date)) {
            $this->end_date = date('d-M-Y', strtotime($this->end_date));
        }

        return true;
    }

    public function afterSave($insert, $changed) {
        if (isset($this->start_date)) {
            $this->start_date = date('d-M-Y', strtotime($this->start_date));
        }
        if (isset($this->end_date)) {
            $this->end_date = date('d-M-Y', strtotime($this->end_date));
        }

        return true;
    }

    public function getAgreements() {
        return $this->hasOne(Agreements::className(), ['id' => 'agreement_id']);
    }

    // public function getUserdata()
    //    {
    //        return $this->hasOne(Users::className(), ['id' => 'owner_id']);
    //    }
    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'owner_id' => 'Owner ID',
            'property_id' => 'Property ID',
            'agreement_id' => 'Agreement ID',
            'start_date' => 'Start date',
            'end_date' => 'End date',
            'create_date' => 'Create date',
            'update_time' => 'Update time',
        ];
    }

}
