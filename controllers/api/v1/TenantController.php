<?php

namespace app\controllers\api\v1;

use Yii;
use yii\rest\ActiveController;

/**
 * UsersController implements the CRUD actions for Users model.
 */
class TenantController extends ActiveController {

    public $modelClass = 'app\models\User';

    protected function verbs() {
        return [
            'dashboard' => ['GET'],
        ];
    }
    
    public function beforeAction($action) {
        $requireAuth = [
            'dashboard', 'getprofile', 'updateprofile', 'paymentsdue', 'pastpayments', 'agreements', 'agreementextendterminate', 'paymentreceipt', 
            'payrent', 
            'reverseneft'
        ];

        if (in_array($action->id, $requireAuth)) {
            Yii::$app->restbasicauth->checkBaseAuth();
        }
        
        return parent::beforeAction($action);
    }

    public function actionDashboard () {
        $id = Yii::$app->restbasicauth->getUserId();
        $sumofmoney = 0;
        $sql = 'SELECT sum(total_amount) as sumofmoney FROM tenant_payments where tenant_id="' . $id . '" AND payment_status = 0 AND due_date < "' . date('Y-m-d') . '" ';
        $connection = \Yii::$app->db;
        $model = $connection->createCommand($sql);
        $duePayments = $model->queryAll();
        if (!empty($duePayments)) {
            foreach ($duePayments as $duePayment) {
                $sumofmoney += $duePayment['sumofmoney'];
            }
        }

        $nxtAmount = 0;
        $sql = 'SELECT sum(total_amount) as sumofmoney FROM tenant_payments where tenant_id = "' . $id . '" AND payment_status = 0 AND (due_date <= "' . date("Y-m-d") . '" + INTERVAL 30 DAY) AND  due_date >= "' . date("Y-m-d") . '"';
        $connection = \Yii::$app->db;
        $model = $connection->createCommand($sql);
        $nextPayments = $model->queryAll();
        $nextsumofmoney = 0;

        if (!empty($nextPayments)) {
            foreach ($nextPayments as $nextPayment) {
                $nextsumofmoney += $nextPayment['sumofmoney'];
            }

            $nxtAmount += $nextsumofmoney;
        }

        $total_remaining = 0;
        $lastdate = \app\models\TenantAgreements::find()->where(['tenant_id' => $id])->andWhere('`lease_end_date` > "' . Date('Y-m-d h:i:s') . '"')->orderBy('lease_end_date')->one();
        if ($lastdate) {
            $contract_end_date = $lastdate->lease_end_date;
            $difference = date_diff(date_create($contract_end_date), date_create(Date('Y-m-d h:i:s')));
            $total_remaining = $difference->days + 1;
       } else {
            $total_remaining = 0;
        }
        $depositAmt= 0;
        $lease_name = '';

        //$propertyNames = \app\models\TenantAgreements::find()->where(['tenant_id' => $id])->andWhere('`lease_end_date` > "' . Date('Y-m-d h:i:s') . '"')->orderBy('lease_end_date')->all();
        
        $strProperties = "Select a.property_name, b.deposit from properties a join tenant_agreements b on a.id = b.property_id " 
                . " where tenant_id = " . $id . " and lease_end_date > '" . Date("Y-m-d") . "' order by a.id";
        
        $propertyNames = Yii::$app->db->createCommand($strProperties)->queryAll();
        
        if (count($propertyNames) > 0) {
            $i=0;
            foreach ($propertyNames as $key => $row) {
                $depositAmt = $depositAmt + $row['deposit'];
                if ($i==0) {
                   $lease_name = $row['property_name'];
                   } else {
                    $lease_name = $lease_name . ', ' . $row['property_name'];
                }
                $i++;
            }
        } 

        
        $respArr = [];
        $respArr['paymentOverdue'] = round($sumofmoney);
        $respArr['upcomingPayment'] = round($nxtAmount);
        $respArr['dayesToExpiry'] = $total_remaining;
        $respArr['propertyName'] = $lease_name;
        $respArr['depositAmount'] = $depositAmt;

        echo Yii::$app->userdata->restResponse($respArr);
    }

    public function actionGetprofile() {
        $respArr = [];
        $tenantId = Yii::$app->restbasicauth->getUserId();
        $userModel = \app\models\Users::find()->where(['id' => $tenantId])->andWhere(['user_type' => 3])->one();
        if (count($userModel) > 0) {
            $tenantModel = \app\models\TenantProfile::find()->where(['tenant_id' => $tenantId])->one();
            $respArr['status'] = TRUE;
            $respArr['name'] = $userModel->full_name;
            $respArr['email'] = $userModel->login_id;
            $respArr['phoneNumber'] = $tenantModel->phone;
            $respArr['profilePhotoURL'] = '';
            if ($tenantModel->profile_image != "") {
                $respArr['profilePhotoURL'] = \yii\helpers\Url::home(TRUE) . $tenantModel->profile_image;
            }
            $respArr['addressLine1'] = $tenantModel->address_line_1;
            $respArr['addressLine2'] = $tenantModel->address_line_2;
            $respArr['city'] = $tenantModel->city_name;
            $respArr['stateCode'] = $tenantModel->state;
            $respArr['pincode'] = $tenantModel->pincode;
            $respArr['message'] = "Successfully fetched details";
        } else {
            $respArr['status'] = FALSE;
            $respArr['message'] = "User not found";
        }

        echo Yii::$app->userdata->restResponse($respArr);
    }

    public function actionUpdateprofile() {
        $respArr = [];
        $isProfilePic = FALSE;
        $validErr = FALSE;

        if (!empty(Yii::$app->request->post())) {
            $input = Yii::$app->request->post();

            $userId = Yii::$app->restbasicauth->getUserId();
            $name = @$input['name'];
            $phoneNumber = @$input['phoneNumber'];
            $unitNo = @$input['unitNo'];
            $addressLine1 = @$input['addressLine1'];
            $addressLine2 = @$input['addressLine2'];
            $city = @$input['city'];
            $stateCode = @$input['stateCode'];
            $pincode = @$input['pincode'];

            if (empty($name)) {
                $validErr = TRUE;
                $respArr['message'] = 'Name is required';
            }

            if (empty($phoneNumber)) {
                $validErr = TRUE;
                $respArr['message'] = 'Phone number is required';
            }

            if (empty($addressLine1)) {
                $validErr = TRUE;
                $respArr['message'] = 'Address line 1 is required';
            }

            if (empty($addressLine2)) {
                $validErr = TRUE;
                $respArr['message'] = 'Address line 2 is required';
            }

            if (empty($city)) {
                $validErr = TRUE;
                $respArr['message'] = 'City is required';
            }

            if (empty($stateCode)) {
                $validErr = TRUE;
                $respArr['message'] = 'State code is required';
            } else {
                $state = \app\models\States::find()->where(['code' => $stateCode])->one();
                if (!$state) {
                    $validErr = TRUE;
                    $respArr['message'] = 'Invalid state code';
                }
            }

            if (empty($pincode)) {
                $validErr = TRUE;
                $respArr['message'] = 'Pincode is required';
            }

            if (!empty($_FILES['profilePic']['name'])) {
                $isProfilePic = true;
                $imageTypes = array('image/jpeg', 'image/jpg', 'image/png');
                $profilePicName = $_FILES['profilePic']['name'];
                $profilePicType = $_FILES['profilePic']['type'];
                $profilePicTempName = $_FILES['profilePic']['tmp_name'];
                $profilePicError = $_FILES['profilePic']['error'];
                $profilePicSize = $_FILES['profilePic']['size'];

                if ($profilePicSize < 10) {
                    $validErr = TRUE;
                    $respArr['message'] = 'File is corrupted';
                }


                if (!in_array($profilePicType, $imageTypes)) {
                    $validErr = TRUE;
                    $respArr['message'] = 'Only PNG and JPG files are allowed';
                }

                if ($profilePicError != 0) {
                    $validErr = TRUE;
                    $respArr['message'] = 'Please try uploading your file again';
                }

                if ($profilePicError == 1) {
                    $validErr = TRUE;
                    $respArr['message'] = 'File size exceeded, limit is ' . ini_get('upload_max_filesize') . 'B';
                }

                if ($profilePicError == 2) {
                    $validErr = TRUE;
                    $respArr['message'] = 'File size exceeded, limit is ' . ini_get('upload_max_filesize') . 'B';
                }

                if ($profilePicError == 3) {
                    $validErr = TRUE;
                    $respArr['message'] = 'File was uploaded partially';
                }
            }
            
            $transaction = Yii::$app->db->beginTransaction();
            
            try {
                if (!$validErr) {
                    $userModel = \app\models\Users::find()->where(['id' => $userId])->one();
                    $userModel->full_name = $name;
                    if (!$userModel->save(false)) {
                        throw \Exception('Something went wrong');
                    }
                    
                    $tenantModel = \app\models\TenantProfile::find()->where(['tenant_id' => $userId])->one();
                    $tenantModel->address_line_1 = $addressLine1;
                    $tenantModel->address_line_2 = $addressLine2;
                    $tenantModel->pincode = $pincode;
                    $tenantModel->phone = $phoneNumber;
                    $tenantModel->state = $stateCode;
                    $tenantModel->city_name = $city;
                    
                    $applicantModel = \app\models\ApplicantProfile::find()->where(['applicant_id' => $userId])->one();
                    $applicantModel->address_line_1 = $addressLine1;
                    $applicantModel->address_line_2 = $addressLine2;
                    $applicantModel->pincode = $pincode;
                    $applicantModel->phone = $phoneNumber;
                    $applicantModel->state = $stateCode;
                    $applicantModel->city_name = $city;
                    
                    if ($isProfilePic) {
                        $targetDir = "uploads/profiles/";
                        $targetFile = $targetDir . time() . basename($profilePicName);
                        move_uploaded_file($profilePicTempName, $targetFile);
                        $tenantModel->profile_image = $targetFile;
                        $applicantModel->profile_image = $targetFile;
                    }

                    if (!$tenantModel->save(false) || !$applicantModel->save(false)) {
                        throw \Exception('Something went wrong');
                    }
                    
                    $leadsModel = \app\models\LeadsTenant::find()->where(['email_id' => Yii::$app->userdata->getUserEmailById($userId)])->one();
                    $leadsModel->full_name = $name;
                    $leadsModel->address = $addressLine1;
                    $leadsModel->address_line_2 = $addressLine2;
                    $leadsModel->pincode = $pincode;
                    $leadsModel->contact_number = $phoneNumber;
                    $leadsModel->updated_by = Yii::$app->restbasicauth->getUserId();
                    $leadsModel->updated_date = date('Y-m-d H:i:s');
                    
                    if (!$leadsModel->save(false)) {
                        throw \Exception('Something went wrong');
                    }
                    
                    $transaction->commit();

                    $respArr['status'] = TRUE;
                    $respArr['message'] = "Profile updated successfully";
                } else {
                    $respArr['status'] = FALSE;
                }
            } catch (\Exception $exp) {
                $transaction->rollBack();
                $respArr['status'] = FALSE;
                $respArr['message'] = $exp->getMessage();
            }
        } else {
            $respArr['status'] = FALSE;
            $respArr['message'] = "Missing parameters, please check your data form.";
        }

        echo Yii::$app->userdata->restResponse($respArr);
    }

    public function actionPaymentsdue() {
        $tenantId = Yii::$app->restbasicauth->getUserId();
        $respArr = [];
        $userModel = \app\models\Users::find()->where(['id' => $tenantId])->andWhere(['user_type' => 3])->one();

        if (count($userModel) > 0) {

            $recordSetPaymentsDue = \app\models\TenantPayments::find()->where(['tenant_id' => $tenantId])->andWhere(['<=', 'due_date', date('Y-m-d', strtotime('+ 30 days'))])->andWhere(['!=', 'payment_status', 1])->all();

            if (count($recordSetPaymentsDue) > 0) {
                $paymentsDue = [];
                $i = 0;
                foreach ($recordSetPaymentsDue as $key => $row) {
                    $penalty = Yii::$app->userdata->calculatePenalty($row->id); 
                    $paymentsDue[$i]['paymentRowid'] = $row->id;
                    $paymentsDue[$i]['dueDate'] = $row->due_date;
                    $paymentsDue[$i]['paymentType'] = $row->payment_type;
                    $paymentsDue[$i]['title'] = $row->payment_des;
                    $paymentsDue[$i]['originalAmount'] = $row->total_amount;
                    $paymentsDue[$i]['penaltyAmount'] = $penalty;
                    $paymentsDue[$i]['totalAmount'] = $row->total_amount + $penalty;
                    $paymentsDue[$i]['paymentStatus'] = $row->payment_status;
                    $i++;
                }
                $respArr['status'] = TRUE;
                $respArr['message'] = "Successfully fetched details";
                $respArr['paymentsDueList'] = $paymentsDue;
            } else {
                $respArr['status'] = FALSE;
                $respArr['message'] = "Cool!!! Nothing to pay here";
                $respArr['paymentsDueList'] = [];
            }
        } else {
            $respArr['status'] = FALSE;
            $respArr['message'] = "OOPS something went wrong (User not found)";
            $respArr['paymentsDueList'] = [];
        }

        echo Yii::$app->userdata->restResponse($respArr);
    }

    public function actionPastpayments() {
        $tenantId = Yii::$app->restbasicauth->getUserId();
        $respArr = [];
        $userModel = \app\models\Users::find()->where(['id' => $tenantId])->andWhere(['user_type' => 3])->one();

        if (count($userModel) > 0) {

            $recordSetPastPayments = \app\models\TenantPayments::find()->where(['tenant_id' => $tenantId])->andWhere(['payment_status' => '1'])->all();

            if (count($recordSetPastPayments) > 0) {
                $pastPayments = [];
                $i = 0;
                foreach ($recordSetPastPayments as $key => $row) {
                    $pastPayments[$i]['paymentRowid'] = $row->id;
                    $pastPayments[$i]['dueDate'] = $row->due_date;
                    $pastPayments[$i]['paymentType'] = $row->payment_type;
                    $pastPayments[$i]['title'] = $row->payment_des;
                    $pastPayments[$i]['originalAmount'] = $row->total_amount;
                    $pastPayments[$i]['penaltyAmount'] = $row->penalty_amount;
                    $pastPayments[$i]['totalAmount'] = $row->amount_paid;
                    $i++;
                }
                $respArr['status'] = TRUE;
                $respArr['message'] = "Successfully fetched details";
                $respArr['paymentsDueList'] = $pastPayments;
            } else {
                $respArr['status'] = FALSE;
                $respArr['message'] = "No payments made yet.";
                $respArr['paymentsDueList'] = [];
            }
        } else {
            $respArr['status'] = FALSE;
            $respArr['message'] = "OOPS something went wrong (User not found)";
            $respArr['paymentsDueList'] = [];
        }

        echo Yii::$app->userdata->restResponse($respArr);
    }
    
    public function actionAgreements() {
        $tenantId = Yii::$app->restbasicauth->getUserId();
        $respArr = [];
        $userModel = \app\models\Users::find()->where(['id' => $tenantId])->andWhere(['user_type' => 3])->one();
        $tenantId = Yii::$app->restbasicauth->getUserId();
        if (count($userModel) > 0) {
            $recordSetAgreements = \app\models\TenantAgreements::find()->where(['tenant_id' => $tenantId])->orderBy("id desc")->all();
            if (count($recordSetAgreements) > 0) {
                $agreements = [];
                $i = 0;
                foreach ($recordSetAgreements as $key => $row) {
                    $agreements[$i]['rent'] = $row->rent;
                    $agreements[$i]['maintenance'] = $row->maintainance;
                    $agreements[$i]['deposit'] = $row->deposit;
                    $agreements[$i]['leaseStartDate'] = $row->lease_start_date;
                    $agreements[$i]['leaseEndDate'] = $row->lease_end_date;
                    $agreements[$i]['minimumStay'] = $row->min_stay;
                    $agreements[$i]['noticePeriod'] = $row->notice_period;
                    $agreements[$i]['lateFeeCharges'] = $row->late_penalty_percent;
                    $agreements[$i]['lateFeeMinAmount'] = $row->min_penalty;
                    $agreements[$i]['propertyName'] = Yii::$app->userdata->getPropertyNameById($row->property_id);
                    $agreements[$i]['propertyId'] = $row->property_id;
                    $agreements[$i]['agreementURL'] = "";
                    if ($row->agreement_url != "") {
                        $agreements[$i]['agreementURL'] =  \yii\helpers\Url::home(TRUE) .  $row->agreement_url;
                    }

                    $i++;
                }
                
                $respArr['status'] = TRUE;
                $respArr['message'] = "Count of agreements : " . count($recordSetAgreements) ;
                $respArr['agreements'] = $agreements;

                }  else {
                $respArr['status'] = FALSE;
                $respArr['message'] = "No Agreements Found";
                $respArr['agreements'] = [];
            }
            
            
        } else {
            $respArr['status'] = FALSE;
            $respArr['message'] = "OOPS something went wrong (User not found)";
            $respArr['agreements'] = [];        
        }
        echo Yii::$app->userdata->restResponse($respArr);
    }
    
    public function actionAgreementextendterminate() {
        $tenantId = Yii::$app->restbasicauth->getUserId();
        $respArr = [];
        if (!empty(Yii::$app->request->post())) {
            $input = Yii::$app->request->post();

            $propertyId = @$input['propertyId'];
            $extendOrTerminate = @$input['extendOrTerminate'];
 
            $agreementModel = \app\models\TenantAgreements::find()->where(['tenant_id' => $tenantId])->andWhere(['property_id' => $propertyId])->one();

            if (count($agreementModel) > 0) {
                if ($extendOrTerminate == 1) {
                    $label = "extension";
                    $extendOrTerm = Yii::$app->userdata->requestTenantLeaseExtn($tenantId,$propertyId,$agreementModel["lease_end_date"]); 
                } else {
                    $label = "termination";
                    $extendOrTerm = Yii::$app->userdata->requestTenantLeaseTerm($tenantId,$propertyId,$agreementModel["lease_end_date"]); 
                }
                
                if ($extendOrTerm == "SUCCESS") {
                    $respArr['status'] = TRUE;
                    $respArr['message'] = "Your request for lease agreement " . $label . " has been registered, our operations team will be in touch with you shortly.";

                } else { 
                    $respArr['status'] = False;
                    $respArr['message'] = $extendOrTerm;
                }

            } else {
                $respArr['status'] = FALSE;
                $respArr['message'] = "OOPS something went wrong (Agreement not found)";
             }
            
            
        } else {
            $respArr['status'] = FALSE;
            $respArr['message'] = "OOPS something went wrong (Empty Request Body)";
        }  
       
        echo Yii::$app->userdata->restResponse($respArr);
    }

    public function actionPaymentreceipt($tpID) {
        $respArr = [];
        if (!empty(Yii::$app->request->post())) {
            $input = Yii::$app->request->post();
            $downloadOrEmail = @$input['downloadOrEmail'];
            $tenantPaymentsModel = \app\models\TenantPayments::find()->where(['id' => $tpID])->andWhere(['payment_status' => '1'])->one();
            if (count($tenantPaymentsModel) > 0) {
                $pdfFile = Yii::$app->userdata->CreatePaymentReceipt($tpID,"API");
                $respArr['docURL'] = \yii\helpers\Url::home(true) . $pdfFile; 
                if ($downloadOrEmail == '1') {
                    $respArr['status'] = TRUE;
                    $respArr['message'] = "Download Successful";
                } else {
                    $userModel = \app\models\Users::findOne(['id' => $tenantPaymentsModel->tenant_id]);
                    $to = $userModel->login_id;
                    $sub = "Your payment receipt towards - " . $tenantPaymentsModel->payment_des;
                    $msg = "Hello " . $userModel->full_name . ",<br/><br/>Greetings from Easyleases !!! <br/><br/> As requested, please find attached payment receipt copy for your reference. <br/><br/>With Regards,<br/>EasyLeases Team<br/><img src='" . \yii\helpers\Url::home(true) . "images/property_logo.png' alt=''>";

                    Yii::$app->userdata->doMailWithAttachment($to,$sub,$msg,$respArr['docURL']);
                    
                    $respArr['status'] = TRUE;
                    $respArr['message'] = "email Successful";
                }

            } else {
                $respArr['status'] = FALSE;
                $respArr['message'] = "OOPS something went wrong (Tenant Payments not found)";
             }
            
            
        } else {
            $respArr['status'] = FALSE;
            $respArr['message'] = "OOPS something went wrong (Empty Request Body)";
        }  
       
        echo Yii::$app->userdata->restResponse($respArr);
    }
    
    public function actionPayrent ($tpID) {
        $this->layout = FALSE;
        $token = Yii::$app->restbasicauth->getUserToken();
        $tpRowId = base64_encode(Yii::$app->getSecurity()->encryptByKey($tpID, Yii::$app->params['hash_key']));
        header('Content-Type: text/html; charset=UTF-8');
        echo $this->render('payrent', ['token' => $token, 'tpRowId' => $tpRowId]);exit;
    }
    
    public function actionReverseneft ($tpID) {
        $respArr = [];
        try {
            $model = \app\models\TenantPayments::find()->where(['id' => $tpID])->andWhere(['payment_status' => 2])->one();
            if (count($model) == 0) {throw new \Exception('Something went wrong');}
            $model->payment_status = 0;
            $model->payment_date = null;
            $model->payment_mode = 0;
            $model->penalty_amount = 0;
            $model->neft_reference = null;
            $model->save(false);
            $respArr['status'] = TRUE;
            $respArr['message'] = "NEFT reversed successfully";
            $respArr['paymentRowid'] = $tpID;
        } catch (\Exception $exp) {
            $respArr['status'] = FALSE;
            $respArr['message'] = $exp->getMessage();
        }
        
        echo Yii::$app->userdata->restResponse($respArr);
    }
    
    public function actionRentpaymentfailed () {
        $this->layout = FALSE;
        header('Content-Type: text/html; charset=UTF-8');
        echo $this->render('payment-failed');exit;
    }
    
    public function actionRentpaymentsuccess () {
        $this->layout = FALSE;
        header('Content-Type: text/html; charset=UTF-8');
        echo $this->render('payment-success');exit;
    }
    
    public function actionRentpaymentsuccessneft () {
        $this->layout = FALSE;
        header('Content-Type: text/html; charset=UTF-8');
        echo $this->render('payment-success-neft');exit;
    }
    
}
