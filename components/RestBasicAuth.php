<?php

namespace app\components;

use Yii;

/**
 * Implementation for REST API Authentication using basic auth
 */

class RestBasicAuth extends \yii\web\User {
    
    private $user;
    private $token;
    
    public function checkBaseAuth () {
        $respArr = [];
        $headers = Yii::$app->request->headers;
        if (empty($headers->get('x-api-key')) || empty($headers->get('APPID')) || empty($headers->get('DeviceID')) || empty($headers->get('Platform'))) {
            $respArr['status'] = FALSE;
            $respArr['message'] = 'Header information missing. x-api-key, APPID, DeviceID, Platform are mandatory headers.';
            header('Content-Type: application/json; charset=UTF-8');
            echo Yii::$app->userdata->restResponse($respArr);exit;
        } else if (!in_array($headers->get('APPID'), Yii::$app->params['APPID_ARR'])) {
            $respArr['status'] = FALSE;
            $respArr['message'] = 'Invalid APP ID in header';
            header('Content-Type: application/json; charset=UTF-8');
            echo Yii::$app->userdata->restResponse($respArr);exit;
        } else {
            $userCredentials = $this->decodeXapiKey($headers->get('x-api-key'));
            if (count($userCredentials) == 2) {
                $userAuth = \app\models\Users::find()->where(['login_id' => $userCredentials[0]])->andWhere(['password' => $userCredentials[1]])->one();
                if (count($userAuth) == 0) {
                    $respArr['status'] = FALSE;
                    $respArr['message'] = "x-api-key mismatch. Your given token is '".$headers->get('x-api-key')."'";
                    header('Content-Type: application/json; charset=UTF-8');
                    echo Yii::$app->userdata->restResponse($respArr);exit;
                } else {$this->user = $userAuth; $this->token = $headers->get('x-api-key');}
            } else {
                $respArr['status'] = FALSE;
                $respArr['message'] = "x-api-key mismatch. Your given token is '".$headers->get('x-api-key')."'";
                header('Content-Type: application/json; charset=UTF-8');
                echo Yii::$app->userdata->restResponse($respArr);exit;
            }
        }
    }
    
    public function getUserModel () {
        if (!empty($this->user)) {
            return $this->user;
        } else {
            return FALSE;
        }
    }
    
    public function getUserModelFromToken ($xApiKey) {
        $userCredentials = $this->decodeXapiKey($xApiKey);
        if (count($userCredentials) == 2) {
            $userModel = \app\models\Users::find()->where(['login_id' => $userCredentials[0]])->andWhere(['password' => $userCredentials[1]])->one();
            if (count($userModel) == 0) {
                return FALSE;
            } else {
                return $userModel;
            }
        } else {return FALSE;}
    }
    
    public function getUserToken () {
        if (!empty($this->token)) {
            return $this->token;
        } else {
            return FALSE;
        }
    }
    
    public function getUserId () {
        if (!empty($this->user)) {
            return $this->user->id;
        } else {
            return false;
        }
    }
    
    public function getFullName () {
        if (!empty($this->user)) {
            return $this->user->full_name;
        } else {
            return false;
        }
    }
    
    public function getEmail () {
        if (!empty($this->user)) {
            return $this->user->login_id;
        } else {
            return false;
        }
    }
    
    public function getUserType () {
        if (!empty($this->user)) {
            return $this->user->user_type;
        } else {
            return false;
        }
    }
    
    public function generateUserToken ($user, $password) {
        if (!empty($user) && !empty($password)) {
            return base64_encode($user.'[:]'.$password);
        } else {
            return false;
        }
    }
    
    public function getDeviceId () {
        $headers = Yii::$app->request->headers;
        
        if (!empty($headers->get('DeviceID'))) {
            return $headers->get('DeviceID');
        } else {
            return "";
        }
    }
    
    public function getAPPID () {
        $headers = Yii::$app->request->headers;
        
        if (!empty($headers->get('APPID'))) {
            return $headers->get('APPID');
        } else {
            return "";
        }
    }
    
    public function decodeXapiKey ($xApiKey) {
        if (!empty($xApiKey)) {
            $xApiKey = base64_decode($xApiKey);
            $userCredentials = explode('[:]', $xApiKey);
            return $userCredentials;
        } else {
            return false;
        }
    }
}
