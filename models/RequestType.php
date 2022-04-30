<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class RequestType extends \yii\db\ActiveRecord {

    /**
     * @return array the validation rules.
     */
    public static function tableName() {
        return 'request';
    }

    public function rules() {
        return [
            // name, email, subject and body are required
            [['id', 'name'], 'required'],
                // email has to be a valid email address
        ];
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

}
