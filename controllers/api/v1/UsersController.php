<?php

namespace app\controllers\api\v1;

use Yii;
use yii\rest\ActiveController;
use app\models\Users;


class OtpCheckException extends \Exception {}

/**
 * UsersController implements the CRUD actions for Users model.
 */

class UsersController extends ActiveController {
    public $modelClass = 'app\models\User';
    
    protected function verbs() {
        return [
            'login' => ['POST'],
            'changepassword' => ['PATCH'],
            'changepasswordinit' => ['POST'],
        ];
    }
    
    public function beforeAction($action) {
        $requireAuth = [
            'logout'
        ];
        
        if (in_array($action->id, $requireAuth)) {
            Yii::$app->restbasicauth->checkBaseAuth();
        }
        
        return parent::beforeAction($action);
    }
    
    public function actionLogin() {
        $input = Yii::$app->userdata->restRequest();
        if (!empty($input)) {
            $respArr = [];
            $user = @$input->user;
            $password = @$input->password;
            $validErr = FALSE;
            
            if (empty($user)) {
                $validErr = TRUE;
                $respArr['message'] = 'Username is required';
            }
            
            if (empty($password)) {
                $validErr = TRUE;
                $respArr['message'] = 'Password is required';
            }
            
            if (empty($user) && empty($password)) {
                $validErr = TRUE;
                $respArr['message'] = 'Username and password is required';
            }
            
            if (!$validErr) {
                $model = \app\models\Users::find()->where(['login_id' => $user])->andWhere(['password' => md5($password), 'user_type' => 3])->one();
                if (count($model) > 0) {
                    $xApiKey = Yii::$app->restbasicauth->generateUserToken($model->login_id, $model->password);
                    $respArr['authResult'] = TRUE;
                    $respArr['userType'] = 3;
                    $respArr['forceChangePassword'] = Yii::$app->userdata->forcePassChange($model->id);
                    $respArr['message'] = "Success";
                    $respArr['x-api-key'] = $xApiKey;
                    header('x-api-key: '.$xApiKey);
                    echo Yii::$app->userdata->restResponse($respArr);
                } else {
                    $respArr['authResult'] = FALSE;
                    $respArr['userType'] = 3;
                    $respArr['forceChangePassword'] = FALSE;
                    $respArr['message'] = "Username or password is incorrect";
                    echo Yii::$app->userdata->restResponse($respArr);
                }
            } else {
                $respArr['authResult'] = FALSE;
                $respArr['userType'] = 3;
                $respArr['forceChangePassword'] = FALSE;
                echo Yii::$app->userdata->restResponse($respArr);
            }
        }
    }
    
    public function actionChangepassword() {
        $input = Yii::$app->userdata->restRequest();
        if (!empty($input)) {
            $respArr = [];
            $securityCode = @$input->securityCode;
            $newPassword = @$input->newPassword1;
            $confirmPassword = @$input->newPassword2;
            $validErr = FALSE;
            
            if (empty($securityCode)) {
                $validErr = TRUE;
                $respArr['message'] = 'OTP code is required';
            }
            
            if (empty($newPassword)) {
                $validErr = TRUE;
                $respArr['message'] = 'New password is required';
            }
            
            if (empty($confirmPassword)) {
                $validErr = TRUE;
                $respArr['message'] = 'Confirm password is required';
            }
            
            if ($newPassword !== $confirmPassword) {
                $validErr = TRUE;
                $respArr['message'] = 'Password mismatch';
            }
            
            if (!$validErr) {
                try {
                    $model = \app\models\Users::find()->where(['login_id' => Yii::$app->restbasicauth->getEmail()])->andWhere(['otp' => $securityCode])->andWhere(['user_type' => 3])->one();
                    if (count($model) > 0) {
                        if ($securityCode != $model->otp) {
                            throw new OtpCheckException();
                        }
                        $model->password = md5($newPassword);
                        $model->pass_up_date = date('Y-m-d H:i:s');
                        $model->updated_date = date('Y-m-d H:i:s');
                        $model->save(FALSE);
                        $respArr['updateStatus'] = TRUE;
                        $respArr['message'] = "Password changed";
                        echo Yii::$app->userdata->restResponse($respArr);
                    } else {
                        $respArr['updateStatus'] = FALSE;
                        $respArr['message'] = "Incorrect OTP";
                        echo Yii::$app->userdata->restResponse($respArr);
                    }
                } catch (OtpCheckException $e) {
                    $respArr['updateStatus'] = FALSE;
                    $respArr['message'] = "Incorrect OTP";
                    echo Yii::$app->userdata->restResponse($respArr);
                }
            } else {
                $respArr['updateStatus'] = FALSE;
                echo Yii::$app->userdata->restResponse($respArr);
            }
        }
    }
    
    public function actionChangepasswordinit () {
        $input = Yii::$app->userdata->restRequest();
        if (!empty($input->user)) {
            $email = @$input->user;
            $respArr = [];
            $respArr['initStatus'] = FALSE;
            $respArr['message'] = "";
            $emailSuccess = False;
            $otpSuccess = False;
            // Shobhit to do: Make below condition for user type dependent on Header.AppId (if tenantapp then usertype = 3) ...
            $model = \app\models\Users::find()->where(['login_id' => $email])->andWhere(['user_type' => 3])->one();
            if (count($model) > 0) {
                $otp = mt_rand(111111, 999999);
                $model->otp = $otp;
                $model->save(FALSE);
                $txtMess1 = 'Your OTP for change password request for Easyleases account is ' . $otp . '.' 
                 . ' If this was not initiated by you, please report to support@easyleases.in';
                $txtMess = stripcslashes($txtMess1);
                $subject = "Easyleases forgot password OTP";
                // Try sending email
                try {
                    $sendEmail = Yii::$app->userdata->doMail($model->login_id, $subject, $txtMess);
                    $emailSuccess = TRUE;

                } catch (\Exception $ex) {
                    $emailSuccess = False;
                }

                $profileModel = "";
                if ($model->user_type == 3) {
                    $profileModel = \app\models\LeadsTenant::find()->where(['email_id' => $email])->one();
                }

                if ($model->user_type == 4) {
                    $profileModel = \app\models\LeadsOwner::find()->where(['email_id' => $email])->one();
                }

                if ($model->user_type == 5) {
                    $profileModel = \app\models\LeadsAdvisor::find()->where(['email_id' => $email])->one();
                }

                if (count($profileModel) > 0) {
                    $mobileNo = $profileModel->contact_number; 
                }
                $respArr['email'] = $email;
                $respArr['textmess'] = $txtMess;
                $respArr['Mobile'] = $mobileNo;
                try {
                    if (!empty($mobileNo)) {

                        if (Yii::$app->userdata->sendSms([$mobileNo], $txtMess)) {
                            $respArr['SMS'] = TRUE;
                            $respArr['Mobile'] = $mobileNo;
                            $respArr['Message'] = $txtMess;
                            $otpSuccess = TRUE;
                            echo PHP_EOL;
                            echo 'Text Sent To ' . $ownerPhone . ' with message ' . $txtMess;
                            echo PHP_EOL;
                        } else {
                                 $respArr['SMS'] = False;
                       
                        }
                        

                    }

                } catch (\Exception $ex) {

                    $otpSuccess = FALSE;

                }

                if (($emailSuccess) || ($otpSuccess))  {
                    $respArr['initStatus'] = TRUE;
                    $respArr['message'] = "OTP sent successfully";
                } else {
                    $respArr['initStatus'] = FALSE;
                    $respArr['message'] = "OTP could not be sent either via email or sms";
                }

                echo Yii::$app->userdata->restResponse($respArr);
            } else {
                $respArr['initStatus'] = FALSE;
                $respArr['message'] = "System Exception: Please try again later. (User not found)";
                echo Yii::$app->userdata->restResponse($respArr);
            }
        } else {
            $respArr['initStatus'] = FALSE;
            $respArr['message'] = "System Exception: Please try again letter. (Input param missing) ";
            echo Yii::$app->userdata->restResponse($respArr);
        }

        
    }
    
    public function actionLogout () {
        $userModel = Yii::$app->restbasicauth->getUserModel();
        $respArr = [];
        if (!empty($userModel)) {
            // $userModel->rest_auth_key = NULL;
            // $userModel->save(false);
            $respArr['status'] = TRUE;
            $respArr['message'] = "Logged out successfully";
        } else {
            $respArr['status'] = FALSE;
            $respArr['message'] = "Something went wrong";
        }
        
        echo Yii::$app->userdata->restResponse($respArr);
    }
}