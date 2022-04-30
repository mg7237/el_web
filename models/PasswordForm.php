<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Users;

class PasswordForm extends Model {

    public $oldpass;
    public $newpass;
    public $repeatnewpass;

    public function rules() {
        return [
            [['oldpass', 'newpass', 'repeatnewpass'], 'required'],
            ['oldpass', 'findPasswords'],
            ['newpass', 'compare', 'compareAttribute' => 'oldpass', 'operator' => '!=', 'message' => 'New Password cannot be same as Old Password'],
            ['repeatnewpass', 'compare', 'compareAttribute' => 'newpass', 'message' => 'Repeat New Password must be same as New Password'],
            ['newpass', 'string', 'length' => [6, 12]],
        ];
    }

    public function findPasswords($attribute, $params) {
        $user = Users::find()->where([
                    'username' => Yii::$app->user->identity->username
                ])->one();
        $password = $user->password;

        if ($password != md5($this->oldpass))
            $this->addError($attribute, 'Old password is incorrect');
    }

    public function attributeLabels() {
        return [
            'oldpass' => 'Old password',
            'newpass' => 'New password',
            'repeatnewpass' => 'Repeat new password',
        ];
    }

}
