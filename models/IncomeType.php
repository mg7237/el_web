<?php

namespace app\models;

use Yii;

class IncomeType extends \yii\db\ActiveRecord {
    
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'income_type';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['income_code', 'income_name'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'income_code' => 'Income code',
            'income_name' => 'Income name'
        ];
    }
}