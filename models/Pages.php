<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pages".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $create_time
 * @property string $update_time
 * @property string $slug
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $status
 */
class Pages extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'pages';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['title', 'description', 'status'], 'required'],
            [['description'], 'string'],
            [['create_time', 'update_time'], 'safe'],
            [['created_by', 'updated_by', 'status'], 'integer'],
            [['title', 'slug'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'slug' => 'Slug',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'status' => 'Status',
        ];
    }

}
