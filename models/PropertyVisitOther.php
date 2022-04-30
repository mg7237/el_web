<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "property_visits".
 *
 * @property integer $id
 * @property integer $property_id
 * @property string $email_id
 * @property string $visit_date
 * @property string $visit_time
 * @property integer $visit_confirm
 * @property integer $created_by
 * @property integer $updated_by
 * @property string $created_date
 * @property string $updated_date
 */
class PropertyVisitsOther extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'property_visits';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['property_id', 'email_id', 'visit_date', 'visit_time', 'created_by', 'created_date'], 'required'],
            [['property_id', 'visit_confirm', 'created_by', 'updated_by'], 'integer'],
            [['visit_date', 'visit_time', 'created_date', 'updated_date'], 'safe'],
            [['email_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'property_id' => 'Property ID',
            'email_id' => 'Email ID',
            'visit_date' => 'Visit date',
            'visit_time' => 'Visit time',
            'visit_confirm' => 'Visit confirm',
            'created_by' => 'Created by',
            'updated_by' => 'Updated by',
            'created_date' => 'Created date',
            'updated_date' => 'Updated date',
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
