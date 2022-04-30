<?php

namespace app\models;

use Yii;

class ExpenseType extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'expense_type';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['expense_code', 'expense_name'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'expense_code' => 'Expense code',
            'expense_name' => 'Expense name'
        ];
    }
}