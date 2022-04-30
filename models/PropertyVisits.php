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
class PropertyVisits extends \yii\db\ActiveRecord {

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
            [['applicant_id', 'visit_date', 'visit_time'], 'required'],
            [['id', 'property_id'], 'integer'],
            [['created_date'], 'safe'],
            [['status'], 'integer'],
            [['applicant_id'], 'string', 'max' => 255],
            [['created_by', 'updated_by'], 'integer', 'max' => 1100],
            ['visit_time', 'visitcheck']
        ];
    }

    public function visitcheck($attribute, $params) {

        $applicant_id = $this->applicant_id;
        $visit_date = date('Y-m-d', strtotime($this->visit_date));
        $visit_time = $this->visit_time . ':00';

        $checkvisitdate = \app\models\PropertyVisits::find()->where(['applicant_id' => Yii::$app->userdata->getUserEmailById($applicant_id), 'visit_date' => $visit_date, 'visit_time' => $visit_time])->one();

        if (count($checkvisitdate) != '0') {
            $this->addError($attribute, 'visit with date and time already exist');
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'property_id' => 'Property ID',
            'child_properties' => 'Child properties',
            'applicant_id' => 'Applicant ID',
            'created_by' => 'Created by',
            'updated_by' => 'Updated by',
            'created_date' => 'Created date',
            'status' => 'Status',
            'visit_date' => 'Visit date',
            'visit_time' => 'Visit time',
            'type' => 'Type',
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
