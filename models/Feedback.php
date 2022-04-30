<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class Feedback extends Model {

    public $name;
    public $email;
    public $message;
    public $body = ' user feedback';

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            // name, email, subject and body are required
            [['name', 'email', 'message'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param string $email the target email address
     * @return bool whether the model passes validation
     */
    public function contact($email) {
        if ($this->validate()) {
            Yii::$app->mailer->compose()
                    ->setTo($email)
                    ->setFrom([$this->email => $this->name])
                    ->setSubject($this->body)
                    ->setTextBody($this->message)
                    ->send();

            return true;
        }
        return false;
    }

}
