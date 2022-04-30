<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "region_assignment".
 *
 * @property integer $id
 * @property integer $type
 * @property integer $user_id
 * @property integer $assign_region_id
 * @property integer $created_by
 * @property integer $updated_by
 * @property string $create_time
 * @property string $update_time
 */
class RegionAssignment extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'region_assignment';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['type', 'user_id', 'assign_region_id'], 'required'],
            [['type', 'user_id', 'assign_region_id', 'created_by', 'updated_by'], 'integer'],
            ['user_id', 'unique', 'message' => '{attribute} is already in use for some region assignments'],
            ['assign_region_id', 'checkAssign'],
            [['create_time', 'update_time'], 'safe'],
        ];
    }

    public function checkAssign($attribute, $params) {
        $model = \app\models\RegionAssignment::find()->where(['type' => $this->type, 'assign_region_id' => $this->assign_region_id])->one();
        if ($model) {
            if ($this->type == 1) {
                $top = 'State';
            } elseif ($this->type == 2) {
                $top = 'City';
            } else {
                $top = 'Region';
            }
            $this->addError($attribute, Yii::t('app', 'This ' . $top . ' is already assigned'));
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'type' => 'Branch type',
            'user_type' => 'User type',
            'user_id' => 'User',
            'assign_region_id' => 'Assign branch',
            'created_by' => 'Created by',
            'updated_by' => 'Updated by',
            'create_time' => 'Create time',
            'update_time' => 'Update time',
        ];
    }

}
