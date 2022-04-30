<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Users;

class ForgotPassword extends Model {

    public $email;
    public $newpass;
    public $repeatnewpass;

    public function rules() {
        return [
            [['newpass', 'repeatnewpass'], 'required'],
            ['newpass', 'findPasswords'],
            ['repeatnewpass', 'compare', 'compareAttribute' => 'newpass'],
            ['newpass', 'string', 'length' => [6, 12]],
        ];
    }

    public function findPasswords($attribute, $params) {
        $user = Users::find()->where([
                    'login_id' => $this->email
                ])->one();

        if (!$user)
            $this->addError($attribute, 'User not found');
    }

    public function attributeLabels() {
        return [
            'oldpass' => 'Old password',
            'newpass' => 'New password',
            'repeatnewpass' => 'Repeat new password',
        ];
    }

}
