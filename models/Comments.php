<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "comments".
 *
 * @property integer $id
 * @property string $description
 * @property integer $property_id
 * @property integer $created_by
 * @property string $created_date
 * @property integer $updated_by
 * @property string $updated_time
 */
class Comments extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'comments';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'description', 'property_id', 'created_by', 'created_date', 'updated_by', 'updated_time'], 'required'],
            [['id', 'property_id', 'created_by', 'user_id', 'updated_by'], 'integer'],
            [['description'], 'string'],
            [['created_date', 'updated_time'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'description' => 'Description',
            'property_id' => 'Property id',
            'created_by' => 'Created by',
            'created_date' => 'Created date',
            'updated_by' => 'Updated by',
            'updated_time' => 'Updated time',
        ];
    }

}
