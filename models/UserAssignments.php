<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_assignments".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $manager_id
 * @property integer $assigned_by
 * @property string $assigned_time
 * @property string $update_time
 */
class UserAssignments extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'user_assignments';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['user_id', 'manager_id', 'assigned_by', 'assigned_time'], 'required'],
            [['user_id', 'manager_id', 'assigned_by'], 'integer'],
            [['assigned_time', 'update_time'], 'safe'],
        ];
    }

    public function getRegion() {
        return $this->hasOne(RegionAssignment::className(), ['user_id' => 'user_id']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'manager_id' => 'Manager ID',
            'assigned_by' => 'Assigned By',
            'assigned_time' => 'Assigned Time',
            'update_time' => 'Update Time',
        ];
    }

}
