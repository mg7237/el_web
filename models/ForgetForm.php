<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ForgetForm extends Model {

    public $email;
    public $name = 'tester';
    public $subject = 'tester';
    public $body = 'http://localhost/pms/web/users/forgetpassword';
	
	
    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            // name, email, subject and body are required
            [['email'], 'required'],
            ['email', 'findUsers'],
            // email has to be a valid email address
            ['email', 'email'],
                // verifyCode needs to be entered correctly
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function findUsers($attribute, $params) {
        $user = Users::find()->where([
                    'login_id' => $this->email
                ])->one();

//        if (empty($user))
//            $this->addError($attribute, 'No user found, Please try with your registered email.');
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param string $email the target email address
     * @return bool whether the model passes validation
     */
    public function contact($email) {
        echo $this->body = 'http://localhost/pms/web/users/forgetpassword?id=' . base64_encode($this->email);
        die;
        $this->subject = 'Forget Passwrod';
        if ($this->validate()) {
            Yii::$app->mailer->compose()
                    ->setTo($email)
                    ->setFrom([$this->email => $this->name])
                    ->setSubject($this->subject)
                    ->setTextBody($this->body)
                    ->send();

            return true;
        }
        return false;
    }

}
