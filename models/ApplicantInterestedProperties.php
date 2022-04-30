<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "applicant_interested_properties".
 *
 * @property integer $id
 * @property integer $property_id
 * @property integer $applicant_id
 * @property integer $created_by
 * @property integer $updated_by
 * @property string $created_date
 * @property string $updated_date
 * @property string $status
 */
class ApplicantInterestedProperties extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'applicant_interested_properties';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['property_id', 'applicant_id', 'created_by', 'updated_by', 'created_date', 'updated_date', 'status'], 'required'],
            [['property_id', 'applicant_id', 'created_by', 'updated_by'], 'integer'],
            [['created_date', 'updated_date'], 'safe'],
            [['status'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'property_id' => 'Property id',
            'applicant_id' => 'Applicant id',
            'created_by' => 'Created by',
            'updated_by' => 'Updated by',
            'created_date' => 'Created date',
            'updated_date' => 'Updated date',
            'status' => 'Status',
        ];
    }

}
