<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "favourite_properties".
 *
 * @property integer $id
 * @property integer $property_id
 * @property string $applicant_id
 * @property string $created_by
 * @property string $updated_by
 * @property string $created_date
 * @property string $status
 */
class FavouriteProperties extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'favourite_properties';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'property_id', 'applicant_id', 'created_by', 'updated_by', 'created_date', 'status'], 'required'],
            [['id', 'property_id'], 'integer'],
            [['created_date'], 'safe'],
            [['status'], 'string'],
            [['applicant_id'], 'string', 'max' => 255],
            [['created_by', 'updated_by'], 'string', 'max' => 1100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'property_id' => 'Property name',
            'applicant_id' => 'Applicant',
            'created_by' => 'Created by',
            'updated_by' => 'Updated by',
            'created_date' => 'Created date',
            'status' => 'Status',
            'rent' => 'Rent',
            'deposite' => 'Deposite',
            'token_amount' => 'Token amount',
            'child_properties' => 'Child properties',
        ];
    }

    public function beforeSave($insert) {
        if (isset($this->visit_date)) {
            $this->visit_date = date('Y-m-d', strtotime($this->visit_date));
        }
        return true;
    }

    public function afterFind() {
        if (isset($this->visit_date)) {
            $this->visit_date = date('d-M-Y', strtotime($this->visit_date));
            $this->visit_time = date('H:i', strtotime($this->visit_time));
        }
        return true;
    }

    public function afterSave($insert, $changed) {
        if (isset($this->visit_date)) {
            $this->visit_date = date('d-M-Y', strtotime($this->visit_date));
            $this->visit_time = date('H:i', strtotime($this->visit_time));
        }
        return true;
    }

}
