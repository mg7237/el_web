<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "roles".
 *
 * @property integer $id
 * @property string $role_name
 * @property integer $user_type
 * @property integer $created_by
 * @property integer $updated_by
 * @property string $created_time
 */
class Roles extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'roles';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['role_name', 'user_type'], 'required'],
            [['user_type', 'created_by', 'updated_by'], 'integer'],
            [['created_time'], 'safe'],
            [['role_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'role_name' => 'Role name',
            'user_type' => 'User type',
            'created_by' => 'Created by',
            'updated_by' => 'Updated by',
            'created_time' => 'Created time',
        ];
    }

}
