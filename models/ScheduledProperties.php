<?php

namespace app\models;

use Yii;

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
class ScheduledProperties extends \yii\db\ActiveRecord {

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
            [['id', 'property_id', 'applicant_id', 'created_by', 'updated_by', 'created_date', 'status', 'visit_date', 'visit_time'], 'required'],
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
            'property_id' => 'Property ID',
            'applicant_id' => 'Applicant ID',
            'created_by' => 'Created by',
            'updated_by' => 'Updated by',
            'created_date' => 'Created date',
            'status' => 'Status',
            'visit_date' => 'Visit date',
            'visit_time' => 'Visit time',
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
        }
        return true;
    }

    public function afterSave($insert, $changed) {
        if (isset($this->visit_date)) {
            $this->visit_date = date('d-M-Y', strtotime($this->visit_date));
        }
        return true;
    }

}
