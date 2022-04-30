<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends \yii\db\ActiveRecord {

//    public $name;
//    public $email;
//    public $phone;
//    public $message;
//    public $created_date;
    public $verifyCode;
    
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'contact';
    }
    
    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            // name, email, phone and message are required
            [['name', 'email', 'phone', 'message'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            ['phone', 'match', 'pattern' => '/^\d{10}$/', 'message' => 'Mobile must contain exactly 10 digits.'],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels() {
        return [
            'verifyCode' => 'Verification Code',
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
                    ->setSubject($this->subject)
                    ->setTextBody($this->body)
                    ->send();

            return true;
        }
        return false;
    }

}
