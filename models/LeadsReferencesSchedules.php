<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "leads_references_schedules".
 *
 * @property integer $id
 * @property integer $leads_id
 * @property string $scheduled_date_time
 * @property integer $created_by
 * @property integer $updated_by
 * @property string $create_time
 * @property string $update_time
 */
class LeadsReferencesSchedules extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'leads_references_schedules';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['leads_id', 'scheduled_date_time'], 'required'],
            [['leads_id', 'created_by', 'updated_by'], 'integer'],
            [['scheduled_date_time', 'create_time', 'update_time'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'leads_id' => 'Leads ID',
            'scheduled_date_time' => 'Scheduled date time',
            'created_by' => 'Created by',
            'updated_by' => 'Updated by',
            'create_time' => 'Create time',
            'update_time' => 'Update time',
        ];
    }

}
