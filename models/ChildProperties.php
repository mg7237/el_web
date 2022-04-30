<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "child_properties".
 *
 * @property integer $id
 * @property string $property_code
 * @property integer $status
 * @property integer $parent
 * @property string $sub_parent
 * @property integer $type
 * @property integer $main
 */
class ChildProperties extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'child_properties';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['property_code', 'status', 'sub_parent', 'type', 'main'], 'required'],
            [['status', 'parent', 'type', 'main'], 'integer'],
            [['sub_parent'], 'string'],
            [['property_code'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'property_code' => 'Property code',
            'status' => 'Status',
            'parent' => 'Parent',
            'sub_parent' => 'Sub parent',
            'type' => 'Type',
            'main' => 'Main',
        ];
    }

}
