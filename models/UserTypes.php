<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_types".
 *
 * @property integer $id
 * @property string $user_type_name
 * @property integer $created_by
 * @property string $created_time
 */
class UserTypes extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'user_types';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'user_type_name', 'created_by', 'created_time'], 'required'],
            [['id', 'created_by'], 'integer'],
            [['created_time'], 'safe'],
            [['user_type_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'user_type_name' => 'User Type Name',
            'created_by' => 'Created by',
            'created_time' => 'Created time',
        ];
    }

}
