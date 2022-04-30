<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "states".
 *
 * @property integer $id
 * @property string $name
 * @property integer $created_by
 * @property string $created_date
 * @property string $updated_date
 */
class States extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'states';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'created_by', 'created_date', 'updated_date'], 'required'],
            [['created_by'], 'integer'],
            [['created_date', 'updated_date'], 'safe'],
            [['name'], 'string', 'max' => 1100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'created_by' => 'Created by',
            'created_date' => 'Created date',
            'updated_date' => 'Updated date',
        ];
    }

}
