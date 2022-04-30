<?php

namespace app\controllers\api\v2;

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
            'getdocuments' => ['GET'],
            'generateotp'  => ['POST'],
           // 'deletedocument' => ['DEL']
        ];
    }
    
    public function beforeAction($action) {
        $requireAuth = [
            'getdocuments',
            'deletedocument',
            'createdocument', 'getsupportperson','updatefcm',
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
//                $model = \app\models\Users::find()->where(['login_id' => $user])->andWhere(['password' => md5($password), 'user_type' => 3])->one();
                $model = \app\models\Users::find()->where(['login_id' => $user])->andWhere(['password' => md5($password)])->one();

                if (count($model) > 0) {
                    $xApiKey = Yii::$app->restbasicauth->generateUserToken($model->login_id, $model->password);
                    $respArr['authResult'] = TRUE;
                    $respArr['userType'] = $model->user_type;
                    $respArr['forceChangePassword'] = Yii::$app->userdata->forcePassChange($model->id);
                    $respArr['message'] = "Success";
                    $respArr['x-api-key'] = $xApiKey;
                    header('x-api-key: '.$xApiKey);
                    echo Yii::$app->userdata->restResponse($respArr);
                } else {
                    $respArr['authResult'] = FALSE;
                    $respArr['userType'] = "";
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
    
    public function actionGenerateotp() {
        $input = Yii::$app->userdata->restRequest();
        $respArr = [];
        $respArr['OTP'] = "";
        if (!empty($input)) {
            $phone = @$input->phone;
            $validErr = FALSE;
            $smsSuccess = False;
            $emailSuccess = False;
            
            if (empty($phone)) {
                $validErr = TRUE;
                $respArr['message'] = 'Username is required';
            }
            
            $modelUsers = \app\models\Users::findOne(['phone' => $phone]);
            if (empty($modelUsers)) {
                $validErr = TRUE;
                $respArr['message'] = 'Phone number not found';
            } else {
                $email = $modelUsers->login_id;
                $OTP = mt_rand(1111, 9999);
                try {
                    $txtMess = "Hi There! As requesed by you, your 4 digit OTP for Easyleases login is: " . $OTP;
                    $subject = "Easyleases login OTP: " . $OTP;
                    $sendEmail = Yii::$app->userdata->doMail($modelUsers->login_id, $subject, $txtMess);
                    $emailSuccess = TRUE;

                } catch (\Exception $ex) {
                    $emailSuccess = False;
                }
                $txtMess = "Hi There! Your 4 digit OTP for Easyleases login is " . $OTP;
                if (Yii::$app->userdata->sendSms([$phone], $txtMess)) {
                    $smsSuccess = True;
                } else {
                    $smsSuccess = False;
                }
                
                if (!($smsSuccess OR $emailSuccess)) {
                    $validErr = True;
                    $respArr['message'] = 'Failed to send OTP to either phone or email. Please try again later.';
                } else {
                    $validErr = False;
                    $respArr['OTP'] = $OTP;
                    $respArr['message'] = "OTP sent successfully.";
                    $respArr['userType'] = $modelUsers->user_type;
                    $respArr['forceChangePassword'] = Yii::$app->userdata->forcePassChange($modelUsers->id);
                    $xApiKey = Yii::$app->restbasicauth->generateUserToken($modelUsers->login_id, $modelUsers->password);
                    $respArr['x-api-key'] = $xApiKey;
                    $respArr['user_id'] = $modelUsers->id;
                }
            }
            
            $respArr['status'] = !($validErr);
            $respArr['emailStatus'] = $emailSuccess;
            $respArr['smsStatus'] = $smsSuccess;
        }
        echo Yii::$app->userdata->restResponse($respArr);
     }
    
    public function actionUpdatefcm() {
        $input = Yii::$app->userdata->restRequest();
        $userId = Yii::$app->restbasicauth->getUserId();
       
        $respArr = [];
        $respArr['status'] = False;
        if (!empty($input)) {
            $deviceId = Yii::$app->restbasicauth->getDeviceId();
            if ($deviceId <> "") {
                $fcm = @$input->fcmToken;

                $existingDeviceModel = \app\models\UserDevices::find()->where(['user_id' => $userId])->andWhere(['device_id' => $deviceId])->andWhere(['fcm_token' => $fcm])->all();
                if (count($existingDeviceModel) == 0) {
                    $userDevices = new \app\models\UserDevices();
                    $userDevices->user_id =  $userId;
                    $userDevices->device_id = $deviceId;
                    $userDevices->fcm_token = $fcm;
                    
                    if ($userDevices->save(false)) {
                        $respArr['status'] = True;
                        $respArr['message'] = "FCM Token saved successfully"; 

                    } else {
                        $respArr['status'] = false;
                        $respArr['message'] = "Save failed"; 
                    }
                } else {
                    $respArr['status'] = True;
                    $respArr['message'] = "Token already exists";
                }
            } else {
                $respArr['status'] = false;
                $respArr['message'] = "Device id not provided"; 
            }
        } else {
            $respArr['status'] = false;
            $respArr['message'] = "FCM token not provided";
        }
        echo Yii::$app->userdata->restResponse($respArr);
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
                    if (Yii::$app->restbasicauth->getAPPID() == Yii::$app->params['APPID']) {
                        $model = \app\models\Users::find()->where(['login_id' => Yii::$app->restbasicauth->getEmail()])->andWhere(['otp' => $securityCode])->andWhere(['user_type' => 3])->one();
                    } else if (Yii::$app->restbasicauth->getAPPID() == Yii::$app->params['PG_OPS_APPID']) {
                        $model = \app\models\Users::find()->where(['login_id' => Yii::$app->restbasicauth->getEmail()])->andWhere(['otp' => $securityCode])->andWhere(['IN', 'user_type', [6,7,9]])->one();
                    } else {
                        $model = [];
                    }
                    
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
            if (Yii::$app->restbasicauth->getAPPID() == Yii::$app->params['APPID']) {
                $model = \app\models\Users::find()->where(['login_id' => $email])->andWhere(['user_type' => 3])->one();
            } else if (Yii::$app->restbasicauth->getAPPID() == Yii::$app->params['PG_OPS_APPID']) {
                $model = \app\models\Users::find()->where(['login_id' => $email])->andWhere(['IN', 'user_type', [6,7,9]])->one();
            } else {
                $model = [];
            }
            
            if (count($model) > 0) {
                $otp = mt_rand(1111, 9999);
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
                
                $respArr['email'] = $email;
                $respArr['textmess'] = $txtMess;
                $respArr['Mobile'] = $model->phone;
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
                $respArr['message'] = "User not registered, please get in touch with Easyleases Support at support@easyleases.in";
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
    
    public function actionGetdocuments() {
        $respArr = [];
        $userId = Yii::$app->restbasicauth->getUserId();
        $userModel = \app\models\Users::find()->where(['id' => $userId])->one();
        if (count($userModel) > 0) {
            $userdocumentModel = \app\models\UserIdProofs::find()->where(['user_id' => $userId])->all();
            $userDocuments=[];
            $i = 0;
            if (count($userdocumentModel)>0) {
                foreach ($userdocumentModel as $key => $row) {
                    $userDocuments[$i]['id'] = $row->id;
                    $userDocuments[$i]['proofType'] = $row->proof_type;
                    $userDocuments[$i]['proofURL'] = ($row->proof_document_url <> "") ?  \yii\helpers\Url::home(TRUE) . $row->proof_document_url:  "";
                    $i++;
                }
            }
            
            $respArr['status'] = TRUE;
            $respArr['message'] = "Successfully fetched details";
            $respArr['userDocuments'] = $userDocuments;
            
        } else {
            $respArr['status'] = FALSE;
            $respArr['message'] = "User not found";
        }

        echo Yii::$app->userdata->restResponse($respArr);
    }

    public function actionDeletedocument($documentId) {
        $respArr = [];
        $userId = Yii::$app->restbasicauth->getUserId();
        $userModel = \app\models\Users::find()->where(['id' => $userId])->one();
        if (count($userModel) > 0) {
            $documentModel = \app\models\UserIdProofs::find()->where(['user_id' => $userId])->andwhere(['id' => $documentId])->one();
            if (count($documentModel)>0) {
                $transaction = Yii::$app->db->beginTransaction();
                try {

                     $delDocument = \app\models\UserIdProofs::deleteAll('id = '. $documentId);

                     $respArr["status"] = True;
                     $respArr["message"] = "Success";                          
                     $transaction->commit();
                 } catch (\Exception $ex) {
                     $transaction->rollBack();
                     $respArr["status"] = False;
                     $respArr["message"] = "Exception:" . $ex->getMessage();

                 }
   
            } else {
                $respArr["status"] = False;
                $respArr["message"] = "Document Id provided doesn't belong to the user";

            }
                
          
        } else {
            $respArr['status'] = FALSE;
            $respArr['message'] = "User not found";
        }

        echo Yii::$app->userdata->restResponse($respArr);
    }

    public function actionCreatedocument() {
        $respArr = [];
        $validErr = False;
        $userId = Yii::$app->restbasicauth->getUserId();
        $userModel = \app\models\Users::find()->where(['id' => $userId])->one();
        if (count($userModel)>0) {
            if (!empty(Yii::$app->request->post())) {
                $input = Yii::$app->request->post();
                
                $proofType = @$input['documentType'];
                
                $documentTypeModel = \app\models\ProofType::find()->where(['id' => $proofType])->one();
                if (!$documentTypeModel) {
                    $validErr = TRUE;
                    $respArr['status'] = False;
                    $respArr['message'] = 'Invalid document type';
                }
                
                if (!empty($_FILES['documentFile']['name'])) {
                    $isProfilePic = true;
                    $imageTypes = array('image/jpeg', 'image/jpg', 'image/png','application/pdf');
                    $documentName = $_FILES['documentFile']['name'];
                    $documentType = $_FILES['documentFile']['type'];
                    $documentTempName = $_FILES['documentFile']['tmp_name'];
                    $documentError = $_FILES['documentFile']['error'];
                    $documentPicSize = $_FILES['documentFile']['size'];

 
                    
                    if (!in_array($documentType, $imageTypes)) {
                        $validErr = TRUE;
                        $respArr['status'] = False;
                        $respArr['message'] = 'Only PDF, PNG and JPG files are allowed';
                    }

                    if ($documentError != 0) {
                        $validErr = TRUE;
                        $respArr['status'] = False;
                        $respArr['message'] = 'Please try uploading your file again';
                    }

                    if ($documentError == 1) {
                        $validErr = TRUE;
                        $respArr['status'] = False;
                        $respArr['message'] = 'File size exceeded, limit is ' . ini_get('upload_max_filesize') . 'B';
                    }

                    if ($documentError == 2) {
                        $validErr = TRUE;
                        $respArr['status'] = False;
                        $respArr['message'] = 'File size exceeded, limit is ' . ini_get('upload_max_filesize') . 'B';
                    }

                    if ($documentError == 3) {
                        $validErr = TRUE;
                        $respArr['status'] = False;
                        $respArr['message'] = 'File was uploaded partially';
                    }
                    
                    if (!$validErr) {
                        $targetDir = "uploads/proofs/";
                        $targetFile = $targetDir.date('Ymd') . time('YmdHisu').str_replace(' ', '-', basename($documentName));;
                        $moveStatus = move_uploaded_file($documentTempName, $targetFile);
                        if($moveStatus) {
                            $transaction = Yii::$app->db->beginTransaction();
                            try {
                                $userProofModel = new \app\models\UserIdProofs();
                                $userProofModel->user_id = $userId;
                                $userProofModel->created_by = $userId;
                                $userProofModel->proof_type = $proofType;
                                $userProofModel->proof_document_url = $targetFile;

                                if (!$userProofModel->save(false)) {
                                    throw \Exception('Something went wrong');
                                }
                                
                                $transaction->commit();
                                $respArr['status'] = TRUE;
                                $respArr['message'] = "Document Saved";
                                $respArr['documentId'] = $userProofModel->id;

                            } catch (Exception $ex) {
                                $transaction->rollBack();
                                $respArr['status'] = FALSE;
                                $respArr['message'] = $ex->getMessage();

                            }

 
                       } else {
                        $respArr['status'] = FALSE;
                        $respArr['message'] = "Technical error while moving file";
  
                       }
                    } else {
                    $respArr['status'] = FALSE;
                    //$respArr['message'] = "Invalid";
                    }
                        
                    
                } else {
                    $respArr['status'] = FALSE;
                    $respArr['message'] = "File not passed";
                }


            }  else {
            $respArr['status'] = FALSE;
            $respArr['message'] = "Missing parameters, please check your data form.";
            }  
        } else {
            $respArr['status'] = FALSE;
            $respArr['message'] = "User not found";
        }
        echo Yii::$app->userdata->restResponse($respArr);
    }


    public function actionGetsupportperson() {
        $respArr = [];
        $respArr['status']=True;
        $supportPersonId ="";
        $userId = Yii::$app->restbasicauth->getUserId();

        $userModel = \app\models\Users::find()->where(['id' => $userId])->one();
        if (count($userModel) > 0) {
            
            if ($userModel->user_type == 2) {
                $profileModel= \app\models\ApplicantProfile::find()->where(['applicant_id' => $userId])->one();
                $supportPersonId = $profileModel->operation_id;
            } elseif($userModel->user_type == 3) {
                $profileModel= \app\models\TenantProfile::find()->where(['tenant_id' => $userId])->one();
                $supportPersonId = $profileModel->operation_id;
            } elseif($userModel->user_type == 4) {
                $profileModel= \app\models\OwnerProfile::find()->where(['owner_id' => $userId])->one();
                $supportPersonId = $profileModel->operation_id;
            } else {
                $respArr['status'] = False;
                $respArr['message'] = "Invalid User type";
            }

            if ($respArr['status']) {
                $operationsProfileModel = \app\models\OperationsProfile::find()->where(['operations_id' => $supportPersonId])->one();
                $operationsUsersModel = \app\models\Users::find()->where(['id' => $supportPersonId])->one();
                $respArr['name'] = $operationsUsersModel->full_name;
                $respArr['email'] = $operationsUsersModel->login_id;
                $respArr['phone'] = $operationsProfileModel->phone;
                $respArr['profileImageURL'] = "";
                if ($operationsProfileModel->profile_image <> "") {
                    $respArr['profileImageURL'] = \yii\helpers\Url::home(TRUE) . $operationsProfileModel->profile_image;
                }
                    
                }
                $respArr['status'] = TRUE;
                $respArr['message'] = "Successfully fetched details";
                

            } else {
                $respArr['status'] = FALSE;
                $respArr['message'] = "User not found";
            }

        echo Yii::$app->userdata->restResponse($respArr);
    }
}
