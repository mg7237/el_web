<?php

namespace app\controllers\api\v2;

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
            'payrent', 'getbankdetails','updatebankdetails', 'getemergencydetails','updateemergencydetails','getemploymentdetails','updateemploymentdetails',
            'reverseneft', 'onboard', 'exittenant', 'pglist', 'pgtenantlist', 'pgtenantdetails', 'pgtenantupdate'
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
    
    // V1.1 code added by Manish Oct 19
    
    public function actionGetemergencydetails() {
        $respArr = [];
        $tenantId = Yii::$app->restbasicauth->getUserId();
        $userModel = \app\models\Users::find()->where(['id' => $tenantId])->andWhere(['user_type' => 3])->one();
        
        if (count($userModel) > 0) {
            $tenantModel = \app\models\TenantProfile::find()->where(['tenant_id' => $tenantId])->one();
            $respArr['name'] = $tenantModel->emergency_contact_name;
            $respArr['email'] = $tenantModel->emer_contact;
            $respArr['phone'] = $tenantModel->emergency_contact_number;
 
            $respArr['status'] = TRUE;
            $respArr['message'] = "Successfully fetched details";
        } else {
            $respArr['status'] = FALSE;
            $respArr['message'] = "User not found";
        }

        echo Yii::$app->userdata->restResponse($respArr);
    }

    public function actionUpdateemergencydetails() {
        $respArr = [];
        $isProfilePic = FALSE;
        $validErr = FALSE;

        if (!empty(Yii::$app->request->post())) {
            $input = Yii::$app->request->post();

            $userId = Yii::$app->restbasicauth->getUserId();
            $userModel = \app\models\Users::find()->where(['id' => $userId])->one();
            if (count($userModel)>0) {
            
                $contactName = @$input['contactName'];
                $contactEmail = @$input['contactEmail'];
                $contactPhone = @$input['contactPhone'];

                $transaction = Yii::$app->db->beginTransaction();

                try {
                    if (!$validErr) {


                        $tenantModel = \app\models\TenantProfile::find()->where(['tenant_id' => $userId])->one();

                        $tenantModel->emergency_contact_name = $contactName;
                        $tenantModel->emer_contact = $contactEmail;
                        $tenantModel->emergency_contact_number = $contactPhone;

   
                        $applicantModel = \app\models\ApplicantProfile::find()->where(['applicant_id' => $userId])->one();
                        $applicantModel->emergency_contact_name = $contactName;
                        $applicantModel->emergency_contact_email = $contactEmail;
                        $applicantModel->emergency_contact_number = $contactPhone;
  


                        if (!$tenantModel->save(false) || !$applicantModel->save(false)) {
                            throw \Exception('Something went wrong');
                        }


                        $transaction->commit();

                        $respArr['status'] = TRUE;
                        $respArr['message'] = "Emergency contact details updated successfully";
                    } else {
                        $respArr['status'] = FALSE;
                    }
                } catch (\Exception $exp) {
                    $transaction->rollBack();
                    $respArr['status'] = FALSE;
                    $respArr['message'] = "Exception: " . $exp->getMessage();
                }
            } else {
                    $respArr['status'] = FALSE;
                    $respArr['message'] = "Invalid tenant id";

            }
        } else {
        $respArr['status'] = FALSE;
        $respArr['message'] = "Missing parameters, please check your data form.";
        }

        echo Yii::$app->userdata->restResponse($respArr);
    }
    
    public function actionGetbankdetails() {
        $respArr = [];
        $tenantId = Yii::$app->restbasicauth->getUserId();
        $userModel = \app\models\Users::find()->where(['id' => $tenantId])->andWhere(['user_type' => 3])->one();
        
        if (count($userModel) > 0) {
            $tenantModel = \app\models\TenantProfile::find()->where(['tenant_id' => $tenantId])->one();
            $respArr['PAN'] = $tenantModel->pan_number;
            $respArr['accountHolderName'] = $tenantModel->account_holder_name;
            $respArr['bankName'] = $tenantModel->bank_name;
            $respArr['branchName'] = $tenantModel->bank_branchname;
            $respArr['IFSC'] = $tenantModel->bank_ifcs;
            $respArr['chequeImageURL'] = '';
            $respArr['accountNumber'] = $tenantModel->account_number;

            if ($tenantModel->cancelled_check != "") {
                $respArr['chequeImageURL'] = \yii\helpers\Url::home(TRUE) . $tenantModel->cancelled_check;
            }
            $respArr['status'] = TRUE;
            $respArr['message'] = "Successfully fetched details";
        } else {
            $respArr['status'] = FALSE;
            $respArr['message'] = "User not found";
        }

        echo Yii::$app->userdata->restResponse($respArr);
    }

    public function actionUpdatebankdetails() {
        $respArr = [];
        $isCheque = FALSE;
        $validErr = FALSE;

        if (!empty(Yii::$app->request->post())) {
            $input = Yii::$app->request->post();

            $userId = Yii::$app->restbasicauth->getUserId();
            $userModel = \app\models\Users::find()->where(['id' => $userId])->one();
            if (count($userModel)>0) {
            
                $PAN = @$input['PAN'];
                $accountHolderName = @$input['accountHolderName'];
                $bankName = @$input['bankName'];
                $branchName = @$input['branchName'];
                $IFSC = @$input['IFSC'];
                $accountNumber = @$input['accountNumber'];

                if (empty($accountHolderName)) {
                    $validErr = TRUE;
                    $respArr['message'] = 'Account Holder Name is required';
                }

                if (empty($PAN)) {
                    $validErr = TRUE;
                    $respArr['message'] = 'PAN is required';
                }

                if (empty($bankName)) {
                    $validErr = TRUE;
                    $respArr['message'] = 'Bank Name is required';
                }

                if (empty($branchName)) {
                    $validErr = TRUE;
                    $respArr['message'] = 'Branch Name is required';
                }

                if (empty($IFSC)) {
                    $validErr = TRUE;
                    $respArr['message'] = 'IFSC is required';
                } else {
                    $ifscResponse = Yii::$app->userdata->getIFSCValidate($IFSC);
                    if ($ifscResponse=="error") {
                        $validErr = TRUE;
                        $respArr['message'] = 'Invalid IFSC';
                        
                    }
                }
                
                if (!empty($_FILES['cancelledCheque']['name'])) {
                    $isCheque = true;
                    $imageTypes = array('image/jpeg', 'image/jpg', 'image/png','application/pdf');
                    $chequeImageName = $_FILES['cancelledCheque']['name'];
                    $chequeImageType = $_FILES['cancelledCheque']['type'];
                    $chequeImageTempName = $_FILES['cancelledCheque']['tmp_name'];
                    $chequeImageError = $_FILES['cancelledCheque']['error'];
                    $chequeImageSize = $_FILES['cancelledCheque']['size'];


                    
                    if (!in_array($chequeImageType, $imageTypes)) {
                        $validErr = TRUE;
                        $respArr['message'] = 'Only PNG, PDF and JPG files are allowed';
                    }

                    if ($chequeImageError != 0) {
                        $validErr = TRUE;
                        $respArr['message'] = 'Please try uploading your file again';
                    }

                    if ($chequeImageError == 1) {
                        $validErr = TRUE;
                        $respArr['message'] = 'File size exceeded, limit is ' . ini_get('upload_max_filesize') . 'B';
                    }

                    if ($chequeImageError == 2) {
                        $validErr = TRUE;
                        $respArr['message'] = 'File size exceeded, limit is ' . ini_get('upload_max_filesize') . 'B';
                    }

                    if ($chequeImageError == 3) {
                        $validErr = TRUE;
                        $respArr['message'] = 'File was uploaded partially';
                    }
                }

                $transaction = Yii::$app->db->beginTransaction();

                try {
                    if (!$validErr) {


                        $tenantModel = \app\models\TenantProfile::find()->where(['tenant_id' => $userId])->one();
                        $tenantModel->account_holder_name = $accountHolderName;
                        $tenantModel->pan_number = $PAN;
                        $tenantModel->bank_name = $bankName;
                        $tenantModel->bank_branchname = $branchName;
                        $tenantModel->bank_ifcs = $IFSC;
                        $tenantModel->account_number = $accountNumber;
   
                        $applicantModel = \app\models\ApplicantProfile::find()->where(['applicant_id' => $userId])->one();
                        $applicantModel->account_holder_name = $accountHolderName;
                        $applicantModel->pan_number = $PAN;
                        $applicantModel->bank_name = $bankName;
                        $applicantModel->bank_branchname = $branchName;
                        $applicantModel->bank_ifcs = $IFSC;
                        $applicantModel->bank_account_number = $accountNumber;
  

                        if ($isCheque) {
                            $targetDir = "uploads/proofs/";
                            $targetFile = $targetDir . date('ymd') . time() . basename($chequeImageName);
                            move_uploaded_file($chequeImageTempName, $targetFile);
                            $tenantModel->cancelled_check = $targetFile;
                            $applicantModel->cancelled_check = $targetFile;
                        }

                        if (!$tenantModel->save(false) || !$applicantModel->save(false)) {
                            throw \Exception('Something went wrong');
                        }


                        $transaction->commit();

                        $respArr['status'] = TRUE;
                        $respArr['message'] = "Bank details updated successfully";
                    } else {
                        $respArr['status'] = FALSE;
                    }
                } catch (\Exception $exp) {
                    $transaction->rollBack();
                    $respArr['status'] = FALSE;
                    $respArr['message'] = "Exception: " . $exp->getMessage();
                }
            } else {
                    $respArr['status'] = FALSE;
                    $respArr['message'] = "Invalid tenant id";

            }
        } else {
        $respArr['status'] = FALSE;
        $respArr['message'] = "Missing parameters, please check your data form.";
        }

        echo Yii::$app->userdata->restResponse($respArr);
    }
    
    public function actionGetemploymentdetails() {
        $respArr = [];
        $tenantId = Yii::$app->restbasicauth->getUserId();

        $userModel = \app\models\Users::find()->where(['id' => $tenantId])->andWhere(['user_type' => 3])->one();
        
        if (count($userModel) > 0) {
            $tenantModel = \app\models\TenantProfile::find()->where(['tenant_id' => $tenantId])->one();
            $respArr['employerName'] = $tenantModel->employer_name;
            $respArr['employerEmail'] = $tenantModel->employment_email;
            $respArr['employeeId'] = $tenantModel->employee_id;
            $respArr['employmentStartDate'] = $tenantModel->employment_start_date;
            $respArr['employmentProofURL'] = "";
            if ($tenantModel->employment_proof_url != "") {
                $respArr['employmentProofURL'] = \yii\helpers\Url::home(TRUE) . $tenantModel->employment_proof_url;
            }
            $respArr['status'] = TRUE;
            $respArr['message'] = "Successfully fetched details";
        } else {
            $respArr['status'] = FALSE;
            $respArr['message'] = "User not found";
        }

        echo Yii::$app->userdata->restResponse($respArr);
    }

    public function actionUpdateemploymentdetails() {
        $respArr = [];
        $isProof = FALSE;
        $validErr = FALSE;

        if (!empty(Yii::$app->request->post())) {
            $input = Yii::$app->request->post();

            $userId = Yii::$app->restbasicauth->getUserId();
            $userModel = \app\models\Users::find()->where(['id' => $userId])->one();
            if (count($userModel)>0) {
            
                $employerName = @$input['employerName'];
                $employerEmail = @$input['employerEmail'];
                $employeeId = @$input['employeeId'];
                $employmentStartDate = @$input['employmentStartDate'];

 
                if (!empty($_FILES['employmentProof']['name'])) {
                    $isProof = true;
                    $imageTypes = array('image/jpeg', 'image/jpg', 'image/png','application/pdf');
                    $proofImageName = $_FILES['employmentProof']['name'];
                    $proofImageType = $_FILES['employmentProof']['type'];
                    $proofImageTempName = $_FILES['employmentProof']['tmp_name'];
                    $proofImageError = $_FILES['employmentProof']['error'];
                    $proofImageSize = $_FILES['employmentProof']['size'];


                    
                    if (!in_array($proofImageType, $imageTypes)) {
                        $validErr = TRUE;
                        $respArr['message'] = 'Only PNG, PDF and JPG files are allowed';
                    }

                    if ($proofImageError != 0) {
                        $validErr = TRUE;
                        $respArr['message'] = 'Please try uploading your file again';
                    }

                    if ($proofImageError == 1) {
                        $validErr = TRUE;
                        $respArr['message'] = 'File size exceeded, limit is ' . ini_get('upload_max_filesize') . 'B';
                    }

                    if ($proofImageError == 2) {
                        $validErr = TRUE;
                        $respArr['message'] = 'File size exceeded, limit is ' . ini_get('upload_max_filesize') . 'B';
                    }

                    if ($proofImageError == 3) {
                        $validErr = TRUE;
                        $respArr['message'] = 'File was uploaded partially';
                    }
                }

                $transaction = Yii::$app->db->beginTransaction();

                try {
                    if (!$validErr) {


                        $tenantModel = \app\models\TenantProfile::find()->where(['tenant_id' => $userId])->one();
                        $tenantModel->employer_name = $employerName;
                        $tenantModel->employee_id = $employeeId;
                        $tenantModel->employment_start_date = $employmentStartDate;
                        $tenantModel->employment_email = $employerEmail;

   
                        $applicantModel = \app\models\ApplicantProfile::find()->where(['applicant_id' => $userId])->one();
                        $applicantModel->employer_name = $employerName;
                        $applicantModel->employee_id = $employeeId;
                        $applicantModel->employment_start_date = $employmentStartDate;
                        $applicantModel->employment_email = $employerEmail;
 

                        if ($isProof) {
                            $targetDir = "uploads/proofs/";
                            $targetFile = $targetDir . date('ymd') . time() . basename($proofImageName);
                            move_uploaded_file($proofImageTempName, $targetFile);
                            $tenantModel->employment_proof_url = $targetFile;
                            $applicantModel->employmnet_proof_url = $targetFile;
                        }

                        if (!$tenantModel->save(false) || !$applicantModel->save(false)) {
                            throw \Exception('Something went wrong');
                        }


                        $transaction->commit();

                        $respArr['status'] = TRUE;
                        $respArr['message'] = "Employment details updated successfully";
                    } else {
                        $respArr['status'] = FALSE;
                    }
                } catch (\Exception $exp) {
                    $transaction->rollBack();
                    $respArr['status'] = FALSE;
                    $respArr['message'] = "Exception: " . $exp->getMessage();
                }
            } else {
                    $respArr['status'] = FALSE;
                    $respArr['message'] = "Invalid tenant id";

            }
        } else {
        $respArr['status'] = FALSE;
        $respArr['message'] = "Missing parameters, please check your data form.";
        }

        echo Yii::$app->userdata->restResponse($respArr);
    }
    
    public function actionOnboard () {
        $respArr = [];
        $isProfilePic = FALSE;
        $isDocType1 = FALSE;
        $isDocType2 = FALSE;
        $employeeID = FALSE;
        $validErr = FALSE;
        $validErrMess = [];

        if (!empty(Yii::$app->request->post())) {
            $input = Yii::$app->request->post();
            
            ////////////////// COLLECTING ITEMS STARTS HERE  /////////////////////////////////////////////
            
            $fullName = @$input['full_name'];
            $addressLine1 = @$input['address_line_1'];
            $addressLine2 = @$input['address_line_2'];
            $cityName = @$input['city_name'];
            $stateCode = @$input['state_code'];
            $pinCode = @$input['pin_code'];
            $email = @$input['email'];
            $mobile = Yii::$app->userdata->trimPhone(@$input['mobile']);
            $rent = @$input['rent'];
            $maintenance = @$input['maintenance'];
            $deposit = @$input['deposit'];
            $checkInDate = @$input['check_in_date'];
            $propertyId = @$input['property_id'];
            $roomNo = @$input['room_no'];
            $emergencyContactName = @$input['emergency_contact_name'];
            $emergencyContactNumber = @$input['emergency_contact_number'];
            $emergencyContactEmail = @$input['emergency_contact_email'];
            $employerName = @$input['employer_name'];
            $exitDate = @$input['exit_date'];
            $idDocType1 = @$input['id_document_type_1'];
            $idDoc1 = @$_FILES['id_document_1']['name'];
            $idDocType2 = @$input['id_document_type_2'];
            $idDoc2 = @$_FILES['id_document_2']['name'];
            $photo = @$_FILES['photo']['name'];
            
            ////////////////// COLLECTING ITEMS ENDS HERE  /////////////////////////////////////////////
            
            //////////////////////  VALIDATION STARTS HERE  ////////////////////////////////////////////

            if (empty($fullName)) {
                $validErr = TRUE;
                $validErrMess[] = 'Name is required';
            }

            if (empty($addressLine1)) {
                $validErr = TRUE;
                $validErrMess[] = 'Address line 1 is required';
            }

            if (empty($cityName)) {
                $validErr = TRUE;
                $validErrMess[] = 'City Name is required';
            }

            if (empty($stateCode)) {
                $validErr = TRUE;
                $validErrMess[] = 'State is required';
            } else {
                $state = \app\models\States::find()->where(['code' => $stateCode])->one();
                if (!$state) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Invalid State';
                }
            }

            if (empty($pinCode)) {
                $validErr = TRUE;
                $validErrMess[] = 'Pincode is required';
            }
            
            if (empty($email)) {
                $validErr = TRUE;
                $validErrMess[] = 'Email is required';
            } else {
                $userModel = \app\models\User::find()->where(['login_id' => $email])->one();
                if (!empty($userModel)) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Email already exists';
                }
            }
            
            if (empty($mobile)) {
                $validErr = TRUE;
                $validErrMess[] = 'Mobile is required';
            } else {
                $userModel = \app\models\User::find()->where(['phone' => $mobile])->one();
                if (!empty($userModel)) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Mobile already exists';
                }
            }
            
            if (empty($rent)) {
                $validErr = TRUE;
                $validErrMess[] = 'Rent is required';
            }
            
            if (empty($maintenance)) {
                $maintenance = 0;
            }
            
            if (empty($deposit)) {
                $deposit = 0;
            }
            
            if (empty($checkInDate)) {
                $validErr = TRUE;
                $validErrMess[] = 'Check In Date is required';
            }
            
            if (empty($propertyId)) {
                $validErr = TRUE;
                $validErrMess[] = 'Property ID is required';
            }
            
            if (empty($idDocType1)) {
                $validErr = TRUE;
                $validErrMess[] = 'Document 1 is required';
            }
            
            if (empty($_FILES['id_document_1']['name'])) {
                $validErr = TRUE;
                $validErrMess[] = 'Document Type 1, file is required';
            }
            
            if (!empty($idDocType2) AND (empty($_FILES['id_document_2']['name']))) {
                $validErr = TRUE;
                $validErrMess[] = 'Document Type 2 is provided but corresponding document not attached';
            }

            if (empty($idDocType2) AND (!empty($_FILES['id_document_2']['name']))) {
                $validErr = TRUE;
                $validErrMess[] = 'Document Type 2 not provided but Document 2 has been attached';
            }
            
            //////////////////////  VALIDATION ENDS HERE  ////////////////////////////////////////////
            
            if (!empty($_FILES['photo']['name'])) {
                $isProfilePic = TRUE;
                $imageTypes = array('image/jpeg', 'image/jpg', 'image/png');
                $profilePicName = $_FILES['photo']['name'];
                $profilePicType = $_FILES['photo']['type'];
                $profilePicTempName = $_FILES['photo']['tmp_name'];
                $profilePicError = $_FILES['photo']['error'];
                $profilePicSize = $_FILES['photo']['size'];
                
                if ($profilePicSize < 10) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Profile Image is corrupted';
                }


                if (!in_array($profilePicType, $imageTypes)) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Only PNG and JPG files are allowed for Profile Image';
                }

                if ($profilePicError != 0) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Please try uploading your profile image again';
                }

                if ($profilePicError == 1) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Profile Image size exceeded, limit is ' . ini_get('upload_max_filesize') . 'B';
                }

                if ($profilePicError == 2) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Profile image size exceeded, limit is ' . ini_get('upload_max_filesize') . 'B';
                }

                if ($profilePicError == 3) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Profile Image was uploaded partially';
                }
            }
            
            if (!empty($_FILES['id_document_1']['name'])) {
                $isDocType1 = true;
                $imageTypes = array('image/jpeg', 'image/jpg', 'image/png', 'application/pdf');
                $documentName1 = $_FILES['id_document_1']['name'];
                $documentType1 = $_FILES['id_document_1']['type'];
                $documentTempName1 = $_FILES['id_document_1']['tmp_name'];
                $documentError1 = $_FILES['id_document_1']['error'];
                $documentSize1 = $_FILES['id_document_1']['size'];

                if ($documentSize1 < 10) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Document Type 1, File is corrupted';
                }


                if (!in_array($documentType1, $imageTypes)) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Document Type 1, only PNG, JPG and PDF files are allowed';
                }

                if ($documentError1 != 0) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Document Type 1, please try uploading your file again';
                }

                if ($documentError1 == 1) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Document Type 1, file size exceeded, limit is ' . ini_get('upload_max_filesize') . 'B';
                }

                if ($documentError1 == 2) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Document Type 1, file size exceeded, limit is ' . ini_get('upload_max_filesize') . 'B';
                }

                if ($documentError1 == 3) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Document Type 1, file was uploaded partially';
                }
            }
            
            if (!empty($_FILES['id_document_2']['name'])) {
                $isDocType2 = true;
                $imageTypes = array('image/jpeg', 'image/jpg', 'image/png', 'application/pdf');
                $documentName2 = $_FILES['id_document_2']['name'];
                $documentType2 = $_FILES['id_document_2']['type'];
                $documentTempName2 = $_FILES['id_document_2']['tmp_name'];
                $documentError2 = $_FILES['id_document_2']['error'];
                $documentSize2 = $_FILES['id_document_2']['size'];

                if ($documentSize2 < 10) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Document Type 2, File is corrupted';
                }


                if (!in_array($documentType2, $imageTypes)) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Document Type 2, only PNG, JPG and PDF files are allowed';
                }

                if ($documentError2 != 0) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Document Type 2, please try uploading your file again';
                }

                if ($documentError2 == 1) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Document Type 2, file size exceeded, limit is ' . ini_get('upload_max_filesize') . 'B';
                }

                if ($documentError2 == 2) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Document Type 2, file size exceeded, limit is ' . ini_get('upload_max_filesize') . 'B';
                }

                if ($documentError2 == 3) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Document Type 2, file was uploaded partially';
                }
            }
            
            if (!empty($_FILES['employee_id']['name'])) {
                $employeeID = TRUE;
                $imageTypes = array('image/jpeg', 'image/jpg', 'image/png', 'application/pdf');
                $employeeIDName = $_FILES['employee_id']['name'];
                $employeeIDType = $_FILES['employee_id']['type'];
                $employeeIDTempName = $_FILES['employee_id']['tmp_name'];
                $employeeIDError = $_FILES['employee_id']['error'];
                $employeeIDSize = $_FILES['employee_id']['size'];

                if ($employeeIDSize < 10) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Employee ID is corrupted';
                }


                if (!in_array($employeeIDType, $imageTypes)) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Only PNG, JPG and PDF files are allowed as Employee ID';
                }

                if ($employeeIDError != 0) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Please try uploading your Employee ID again';
                }

                if ($employeeIDError == 1) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Employee ID size exceeded, limit is ' . ini_get('upload_max_filesize') . 'B';
                }

                if ($employeeIDError == 2) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Employee ID size exceeded, limit is ' . ini_get('upload_max_filesize') . 'B';
                }

                if ($employeeIDError == 3) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Employee ID was uploaded partially';
                }
            }
            
            $transaction = Yii::$app->db->beginTransaction();
            
            try {
                if (!$validErr) {
                    $userId = 0;
                    $lastInsert = 0;
                    
                    $userModel = new \app\models\User();
                    $userModel->full_name = $fullName;
                    $userModel->login_id = $email;
                    $userModel->username = $email;
                    $userModel->phone = $mobile;
                    $userModel->password = Yii::$app->userdata->passwordGenerate();
                    $userModel->user_type = 3;
                    $userModel->type_register = 0;
                    $userModel->status = 1;
                    if (!$userModel->save(false)) {
                        throw \Exception('Something went wrong');
                    } else {
                        $lastInsert = Yii::$app->db->getLastInsertID();
                    }
                    
                    /// Setting tenant onboard data for tenant_registration table [STARTS HERE]
                    $userId = Yii::$app->restbasicauth->getUserId();
                    
                    $salesID = 0;
                    $opsID = 0;
                    $branchCode = 0;
                    $leadCity = 0;
                    $leadState= 0;
                    
                    $propertyModel = \app\models\Properties::find()->where(['id' => $propertyId])->one();
                    if (!empty($propertyModel)) {
                        $salesID = $propertyModel->sales_id;
                        $opsID = $propertyModel->operations_id;
                        $leadCity = $propertyModel->city;
                        $leadState = $propertyModel->state;
                        $branchCode = $propertyModel->branch_code;
                    }
                    
                    $applicantProfile = new \app\models\ApplicantProfile();
                    $applicantProfile->applicant_id = $lastInsert;
                    $applicantProfile->email_id = $email;
                    $applicantProfile->phone = $mobile;
                    $applicantProfile->sales_id = $salesID;
                    $applicantProfile->operation_id = $opsID;
                    $applicantProfile->status = 6;
                    $applicantProfile->address_line_1 = $addressLine1;
                    $applicantProfile->address_line_2 = $addressLine2;
                    $applicantProfile->state = $stateCode;
                    $applicantProfile->city_name = $cityName;
                    $applicantProfile->branch_code = $branchCode;
                    $applicantProfile->pincode = $pinCode;
                    $applicantProfile->emergency_contact_email = $emergencyContactEmail;
                    $applicantProfile->emergency_contact_name = $emergencyContactName;
                    $applicantProfile->emergency_contact_number = $emergencyContactNumber;
                    $applicantProfile->employer_name = $employerName;
 
                    
                    $tenantModel = new \app\models\TenantProfile();
                    $tenantModel->tenant_id = $lastInsert;
                    $tenantModel->emer_contact = $emergencyContactNumber;
                    $tenantModel->emergency_contact_name = $emergencyContactName;
                    $tenantModel->emergency_contact_number = $emergencyContactNumber;
                    $tenantModel->status = 6;
                    $tenantModel->sales_id = $salesID;
                    $tenantModel->operation_id = $opsID;
                    $tenantModel->address_line_1 = $addressLine1;
                    $tenantModel->address_line_2 = $addressLine2;
                    $tenantModel->state = $stateCode;
                    $tenantModel->city_name = $cityName;
                    $tenantModel->branch_code = $branchCode;
                    $tenantModel->pincode = $pinCode;
                    $tenantModel->phone = $mobile;
                    $tenantModel->employer_name = $employerName;
                    
                    $leadsTenantModel = new \app\models\LeadsTenant();
                    $leadsTenantModel->full_name = $fullName;
                    $leadsTenantModel->email_id = $email;
                    $leadsTenantModel->contact_number = $mobile;
                    $leadsTenantModel->address = $addressLine1;
                    $leadsTenantModel->address_line_2 = $addressLine2;
                    $leadsTenantModel->pincode = $pinCode;
                    $leadsTenantModel->ref_status = 0;
                    $leadsTenantModel->created_date = date('Y-m-d H:i:s');
                    $leadsTenantModel->created_by = $userId;
                    $leadsTenantModel->user_type = 3;
                    $leadsTenantModel->lead_city = $leadCity;
                    $leadsTenantModel->lead_state = $leadState;
                    $leadsTenantModel->branch_code = $branchCode;
                    
                    $tenantAgreementModel = new \app\models\TenantAgreements();
                    $tenantAgreementModel->tenant_id = $lastInsert;
                    $tenantAgreementModel->status = 1;
                    $tenantAgreementModel->property_id = $propertyId;
                    $tenantAgreementModel->parent_id = $propertyId;
                    $tenantAgreementModel->pg_room_name = $roomNo;
                    $tenantAgreementModel->lease_start_date = date('Y-m-d', strtotime($checkInDate));
                    $tenantAgreementModel->lease_end_date = ((!empty($exitDate)) ? date('Y-m-d', strtotime($exitDate)) : NULL );
                    $tenantAgreementModel->rent = $rent;
                    $tenantAgreementModel->maintainance = $maintenance;
                    $tenantAgreementModel->created_by = $userId;
                    $tenantAgreementModel->updated_by = $userId;
                    $tenantAgreementModel->deposit = $deposit;
                    $tenantAgreementModel->min_stay = 2;
                    $tenantAgreementModel->notice_period = 1;
                    $tenantAgreementModel->created_date = date('Y-m-d H:i:s');
                    $tenantAgreementModel->late_penalty_percent = 24;
                    $tenantAgreementModel->min_penalty = 200;
                    $tenantAgreementModel->agreement_url = NULL;
                    $tenantAgreementModel->updated_date = NULL;
                    $tenantAgreementModel->end_date = $exitDate;
                    $tenantAgreementModel->property_type = 5;
                    $tenantAgreementModel->notice_status = '0';
                    $tenantAgreementModel->agreement_type = 1;
                    
                    if ($isProfilePic) {
                        $targetDir = "uploads/documents/";
                        if(!is_dir($targetDir)) {
                            mkdir($targetDir, 0777);
                        }
                        $targetFile = $targetDir . time() . basename($profilePicName);
                        move_uploaded_file($profilePicTempName, $targetFile);
                        
                        
                        $applicantProfile->profile_image = $targetFile;
                        $tenantModel->profile_image = $targetFile;
                    }
                    
                    
                    if ($isDocType1) {
                        $docModel = new \app\models\UserIdProofs();
                        $targetDir = "uploads/documents/";
                        if(!is_dir($targetDir)) {
                            mkdir($targetDir, 0777);
                        }
                        $targetFile = $targetDir . time() . basename($documentName1);
                        move_uploaded_file($documentTempName1, $targetFile);
                        $docModel->user_id = $lastInsert;
                        $docModel->created_by = $userId;
                        $docModel->proof_type = $idDocType1;
                        $docModel->proof_document_url = $targetFile;
                        $docModel->save(false);
                    }
                    
                    if ($isDocType2) {
                        $docModel = new \app\models\UserIdProofs();
                        $targetDir = "uploads/documents/";
                        if(!is_dir($targetDir)) {
                            mkdir($targetDir, 0777);
                        }
                        $targetFile = $targetDir . time() . basename($documentName2);
                        move_uploaded_file($documentTempName2, $targetFile);
                        $docModel->user_id = $lastInsert;
                        $docModel->created_by = $userId;
                        $docModel->proof_type = $idDocType2;
                        $docModel->proof_document_url = $targetFile;
                        $docModel->save(false);
                    }
                    
                    if ($employeeID) {
                        $targetDir = "uploads/documents/";
                        if(!is_dir($targetDir)) {
                            mkdir($targetDir, 0777);
                        }
                        $targetFile = $targetDir . time() . basename($employeeIDName);
                        move_uploaded_file($employeeIDTempName, $targetFile);
                        
                        
                        $applicantProfile->employmnet_proof_url = $targetFile;
                        $tenantModel->employment_proof_url = $targetFile;
                    }
                    
                    if (!$applicantProfile->save(false) || !$tenantModel->save(false) || !$leadsTenantModel->save(false)) {
                        throw \Exception('Something went wrong, please try again later -1');
                    }
                    
                    if (!$tenantAgreementModel->save(false)) {
                        throw \Exception('Something went wrong, please try again later -2');
                    }
                    
                    $aggreementId = Yii::$app->db->getLastInsertID();
                    $startDate = date_create(date('Y-m-d', strtotime($checkInDate)));
                    $monthEndDate = date_create(date('Y-m-d', strtotime("last day of this month")));
                    
                    if ( empty($exitDate) or date('Y-m-d',strtotime($exitDate)) > date('Y-m-d', strtotime("last day of this month"))) {
                        $tpEndDate = $monthEndDate;
                    } else {
                        $tpEndDate = date_create(date('Y-m-d', strtotime($exitDate)));;
                    }
                    
                    $dateDiff = date_diff($startDate, $tpEndDate);
                    $daysInMonth = cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y'));
                   
                    if ($dateDiff->invert == 0) {
                        $daysOfRent = $dateDiff->days + 1;
                        $tenantPayments = new \app\models\TenantPayments();
                        $tenantPayments->tenant_id = $lastInsert;
                        $tenantPayments->property_id = $propertyId;
                        $tenantPayments->parent_id = $propertyId;
                        $tenantPayments->original_amount = $rent * $daysOfRent / $daysInMonth; 
                        $tenantPayments->owner_amount = $rent * $daysOfRent / $daysInMonth;
                        $tenantPayments->maintenance = $maintenance * $daysOfRent / $daysInMonth;
                        $tenantPayments->payment_des = "PG Rent for the month of " . date("M-Y");
                        $tenantPayments->payment_mode = 0;
                        $tenantPayments->payment_type = 2;
                        $tenantPayments->payment_status = 0;
                        $tenantPayments->created_date = date("Y-m-d");
                        $tenantPayments->created_by = $userId;
                        $tenantPayments->updated_by = $userId;
                        $tenantPayments->penalty_amount = 0;
                        $tenantPayments->total_amount= ($rent + $maintenance) * $daysOfRent / $daysInMonth;
                        $tenantPayments->adhoc = 0;
                        $tenantPayments->updated_date = date("Y-m-d");
                        $tenantPayments->due_date = date("Y-m-d");
                        $tenantPayments->days_of_rent = $daysOfRent;
                        $tenantPayments->agreement_id =$aggreementId;
                        $tenantPayments->calculated = 0;
                        $tenantPayments->advisor_calculation_prop = 0;
                        $tenantPayments->advisor_calculation_tenant =0;
                        $tenantPayments->month = date("m-Y");
                        $tenantPayments->tenant_amount = $rent * $daysOfRent / $daysInMonth;
                        $tenantPayments->processed = 0;
                        $tenantPayments->amount_paid = 0;
                               
                        if (!$tenantPayments->save(false)) {
                            throw \Exception('Something went wrong, please try again later -3');
                        }
                    }
                    
                    $transaction->commit();
                    
                    $propertyName = Yii::$app->userdata->getPropertyNameById($propertyId);
                    
                    /////////////////////////// SEND NOTIFICATIONS TO USER USING SMS AND EMAIL //////////////////////
                    
                    $txtMess = 'Congratulations, you have been on-boarded to ' .  $propertyName . 
                            ". Please download Easyleases Tenant app (Android & iOS) to manage your stay with us." ;
                    
                    
                    $webhomeURL = \yii\helpers\Url::home(true)."site/login";
                    $playStoreURL = Yii::$app->params['play_store_url'];
                    $appStoreURL = Yii::$app->params['app_store_url'];
                    Yii::$app->userdata->sendSms([$mobile], $txtMess);
                    
                    $maintenanceTxt = (!empty($maintenance)) ? "<b>Maintenance:</b> $maintenance <br>" : "";
                    $exitDateTxt = (!empty($exitDate)) ? "<b>Exit Date:</b> $exitDate <br>" : "";
                    
                    $subject = 'Congratulations, you have been on-boarded to ' .  $propertyName;
                    $msgContent = "Hi There,"
                            . "<br><br>"
                            . "You have been successfully on-boarded with us at " . $propertyName . 
                            ". <br><br>"
                            . "Your profile has been set up on Easyleases technology platform. " .
                            ". Your tenancy details are below:<br><br>"
                            . "<b>Rent:</b> $rent <br>"
                            . "".$maintenanceTxt.""
                            . "<b>Deposit:</b> $deposit <br>"
                            . "<b>Start Date:</b> ".date('d-m-Y', strtotime($checkInDate))." <br>"
                            . "".$exitDateTxt.""
                            . "<br>"
                            . "You can now access Easyleases Online Dashboard via web or Andriod/iOS mobile APPS (links below). The online dahsboard allows you to view details of your tenancy, raise service requests, pay rent using Credit Card/Debit Card, NetBanking, UPI and Paytm wallet.  <br><br> "
                            . "We hope you have a great experience while your stay with us. "
                            . "For any urgent issues you may reach out to your local property manager.<br><br>"
                            . "Best Regards,<br>"
                            . "Easyleases Team";
                    
                    $message = $this->renderPartial('pg-app-email-template', [
                        'msgContent' => $msgContent
                    ]);
                            

                    $email = Yii::$app->userdata->doMail($email, $subject, $message);
                    
                    /////////////////////////// SEND NOTIFICATIONS TO USER USING SMS AND EMAIL //////////////////////

                    $respArr['status'] = TRUE;
                    $respArr['message'] = "Guest Onboarded successfully";
                    $respArr['errors'] = "";
                } else {
                    $respArr['status'] = FALSE;
                    $respArr['message'] = "Validation failed";
                    $respArr['errors'] = $validErrMess;
                }
            } catch (\Exception $exp) {
                $transaction->rollBack();
                $respArr['status'] = FALSE;
                $respArr['message'] = $exp->getMessage();
                $respArr['errors'] = $exp->getLine();
            }
        } else {
            $respArr['status'] = FALSE;
            $respArr['message'] = "Validation failed";
            $respArr['errors'] = "Missing parameters, check your data form.";
        }

        echo Yii::$app->userdata->restResponse($respArr);
    }
    
    public function actionExittenant () {
        $respArr = [];
        $respArr['status'] = FALSE;
        $respArr['message'] = '';
        $validErr = FALSE;
        $validErrMess = [];
        $validWarnings = [];
        
        $input = Yii::$app->userdata->restRequest();
        
        if (!empty($input)) {
            $userID = $id = Yii::$app->restbasicauth->getUserId();
            $tenantID = @$input->tenant_id;
            $propertyID = @$input->property_id;
            $exitDate = @$input->exit_date;
            $exitReason = @$input->exit_reason;
            
            if (empty($tenantID)) {
                $validErr = TRUE;
                $validErrMess[] = 'Tenant ID is required';
            }
            
            if (empty($propertyID)) {
                $validErr = TRUE;
                $validErrMess[] = 'Property ID is required';
            }
            
            if (empty($exitDate)) {
                $validErr = TRUE;
                $validErrMess[] = 'Exit date is required';
            }
            $tenantAgreements = \app\models\TenantAgreements::find()->where(['tenant_id' => $tenantID])->andWhere(['property_id' => $propertyID])->one();
            
            if (!empty($tenantAgreements->lease_end_date) AND date('Ymd', strtotime($exitDate)) > date('Ymd',strtotime($tenantAgreements->lease_end_date))) {
                $validErr = TRUE;
                $validErrMess[] = 'Exit date can not be post-poned using this feature. Please use update tenant to extend tenant end date.';
            }
            
            $transaction = Yii::$app->db->beginTransaction();
            
            try {
                if (!$validErr) {
                    $tpRow = Yii::$app->db->createCommand(''
                                    . 'SELECT * FROM tenant_payments WHERE tenant_id = ' . $tenantID . ' '
                                    . 'AND parent_id = ' . $propertyID . ' AND `due_date` > "' . date('Y-m-d', strtotime("last day of " . $exitDate)) . '" AND `payment_status` = 0 '
                                    . 'AND Payment_type = 2 ORDER BY `due_date` DESC, `id` DESC '
                                    . '')->queryAll();

                    if (count($tpRow) > 0) {
                        foreach ($tpRow as $key => $row) {
                            $tpRowOne = \app\models\TenantPayments::find()->where(['id' => $row['id']])->one();
                            if (!$tpRowOne->delete(false)) {
                                throw new \Exception('Unable to terminate lease, Exception');
                            }
                        }
                    }

                    $tpRow = Yii::$app->db->createCommand(''
                                    . 'SELECT * FROM tenant_payments WHERE tenant_id = ' . $tenantID . ' '
                                    . 'AND property_id = ' . $propertyID . ' AND `month` = "' . date('m-Y', strtotime($exitDate)) . '" '
                                    . 'AND payment_type = 2 AND payment_status = 0 ORDER BY `id` DESC '
                                    . '')->queryAll();
                    
                    
                    if (!$tenantAgreements) {
                        throw new \Exception('Invalid Tenant ID or Property ID');
                    }
                    
                    $terminateDate = $exitDate;
                    $terminateRent = $tenantAgreements->rent;
                    $terminateMaintenance = $tenantAgreements->maintainance;
                    $terminateLeaseEndDate = $tenantAgreements->lease_end_date;
                    //$terminateParentId = $tenantAgreements->parent_id;
                    

                    if (count($tpRow) > 0) {
                        foreach ($tpRow as $key => $row) {
                            if ($key == 0) {
                                $tpRowOne = \app\models\TenantPayments::find()->where(['id' => $row['id']])->one();
                                $daysInMonth = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($terminateDate)), date('Y', strtotime($terminateDate)));
                                $rentPerDay = (double) $terminateRent / $daysInMonth;
                                $maintenancePerDay = (double) $terminateMaintenance / $daysInMonth;
                                $coverageDays = (int) date('d', strtotime($terminateDate));
                                $rentForMonth = (double) round(($rentPerDay * $coverageDays), 2);
                                $maintenanceForMonth = (double) round(($maintenancePerDay * $coverageDays), 2);
                                $terminateTotalAmount = round(($rentForMonth + $maintenanceForMonth), 2);

                                $tpRowOne->original_amount = $rentForMonth;
                                $tpRowOne->owner_amount = $rentForMonth;
                                $tpRowOne->maintenance = $maintenanceForMonth;
                                $tpRowOne->total_amount = (double) ($rentForMonth + $maintenanceForMonth);
                                $tpRowOne->tenant_amount = $rentForMonth;
                                $tpRowOne->days_of_rent = $coverageDays;
                                $tpRowOne->updated_by = Yii::$app->user->id;
                                $tpRowOne->updated_date = date('Y-m-d H:i:s');
                                $tpRowOne->save(false);
                            } else {
                                $tpRowOne = \app\models\TenantPayments::find()->where(['id' => $row['id']])->one();
                                $tpRowOne->delete(false);
                            }
                        }
                    }
                    
                    $tenantAgreements->lease_end_date = date('Y-m-d', strtotime($exitDate));
                    $tenantAgreements->updated_by = $userID;
                    $tenantAgreements->updated_date = date('Y-m-d H:i:s');
                    $tenantAgreements->exit_reason = $exitReason;
                    
                    if (!$tenantAgreements->save(false)) {
                        throw new \Exception('Unable to terminate lease, some thing went wrong.');
                    }
                    
                    $transaction->commit();
                    
                    $strSQL = 'SELECT SUM(total_amount) total_payment_due FROM tenant_payments '
                                    . 'group by tenant_id, property_id, payment_status having tenant_id = ' . $tenantID . ' '
                                    . 'AND property_id = ' . $propertyID . ' AND  payment_status = 0 ';
                    
//                    echo 'SQL Generated: ' . $strSQL;
                    
                    $tpRow = Yii::$app->db->createCommand($strSQL)->queryOne(); 
                    
                    if (count($tpRow)> 0 and $tpRow['total_payment_due'] > 0) {
                        $validWarnings = ['Tenant has outstanding payment of Rs ' . $tpRow['total_payment_due']];
                    }
                    
                    $propertyName = Yii::$app->userdata->getPropertyNameById($propertyID);
                    /////////////////////////// SEND NOTIFICATIONS TO USER USING SMS AND EMAIL //////////////////////
                    

                    $txtMess = 'Hello, your exit from ' . $propertyName ." is confirmed for " . date("d-m-Y", strtotime($exitDate)) . ". In case of any queries, please reach out to your property support team.";

                    Yii::$app->userdata->sendSms([Yii::$app->userdata->getTenantPhoneById($tenantID)], $txtMess);
                    
                    $subject = "Your exit from " . $propertyName;
                    $msgContent = "Hi There,"
                            . "<br><br>"
                            . "Quick note to confirm your exit on " . date("d-m-Y",strtotime($exitDate)) 
                            . ". We wish to serve you again in near future. "
                            . "<br><br>"
                            . "We request you to take couple of minutes to share your feeedback, suggestions which may help us improve upon our services. <br><br>"
                            . "With Regards, <br>"
                            . "Easyleases Team";
                    $message = $this->renderPartial('pg-app-email-template', [
                        'msgContent' => $msgContent
                    ]);
                    
                    $email = Yii::$app->userdata->doMail(Yii::$app->userdata->getEmailById($tenantID), $subject, $message);
                    
                    /////////////////////////// SEND NOTIFICATIONS TO USER USING SMS AND EMAIL //////////////////////
                    
                    $respArr['status'] = TRUE;
                    $respArr['message'] = "Agreement updated successfully";
                    $respArr['errors'] = $validErrMess;
                    $respArr['warnings'] = $validWarnings;
                    
                } else {
                    $respArr['status'] = FALSE;
                    $respArr['message'] = "Validation failed";
                    $respArr['errors'] = $validErrMess;
                }
            } catch (\Exception $exp) {
                $transaction->rollBack();
                $respArr['status'] = FALSE;
                $respArr['message'] = $exp->getMessage();
                $respArr['errors'] = $exp->getLine();
            }
        } else {
            $respArr['status'] = FALSE;
            $respArr['message'] = "Validation failed";
            $respArr['errors'] = "Missing parameters, check inputs.";
        }
        
        echo Yii::$app->userdata->restResponse($respArr);
    }
    
    public function actionPgtenantlist () {
        $respArr = [];
        $respArr['status'] = FALSE;
        $respArr['message'] = '';
        $validErr = FALSE;
        $validErrMess = [];
        $input = Yii::$app->userdata->restRequest();
        
        if (!empty($input)) {
            $userID = $id = Yii::$app->restbasicauth->getUserId();
            $propertyID = @$input->pg_id;
            $term = @$input->term;
            $status = (empty($input->status)) ? 1 : $input->status ;
            
            if (empty($propertyID)) {
                $validErr = TRUE;
                $validErrMess[] = 'Property ID is required';
            }
            
            try {
                if (!$validErr) {
                    $tenants = [];
                    $today = date('Y-m-d');
                    
                    if ($status == 1) {
                        $where = " AND (ta.lease_end_date is null or ta.lease_end_date >= '".$today."') AND u.`status`='1' ";
                    } else {
                        $where = " AND ta.lease_end_date < '".$today."' AND u.`status`='1' ";
                    }
                    
                    $taRow = Yii::$app->db->createCommand(''
                            . 'SELECT u.id, u.full_name, u.login_id, u.phone, ta.rent, ta.maintainance, '
                            . 'ta.pg_room_name, ta.deposit, ta.lease_start_date, ta.lease_end_date '
                            . 'FROM tenant_agreements ta INNER JOIN users u on ta.tenant_id = u.id '
                            . 'WHERE ta.property_id = ' . $propertyID . ' AND '
                            . '(u.full_name LIKE "%'.$term.'%" OR '
                            . 'u.login_id LIKE "%'.$term.'%" OR '
                            . 'u.phone LIKE "%'.$term.'%") '
                            . ' '.$where.' '
                            . '')->queryAll();
                    
                    if ($taRow > 0) {
                        foreach ($taRow as $key => $row) {
                            $tenants[$key]['id'] = $row['id'];
                            $tenants[$key]['name'] = $row['full_name'];
                            $tenants[$key]['phone'] = $row['phone'];
                            $tenants[$key]['email'] = $row['login_id'];
                            $tenants[$key]['rent'] = $row['rent'];
                            $tenants[$key]['maintenance'] = $row['maintainance'];
                            $tenants[$key]['deposit'] = $row['deposit'];
                            $tenants[$key]['member_since'] = $row['lease_start_date'];
                            $tenants[$key]['exit_date'] = $row['lease_end_date'];
                            $tenants[$key]['room_no'] = $row['pg_room_name'];

                            $dateDue = Date('Y-m-d', strtotime('+1 month'));
                            $sql = 'SELECT tp.id, tp.parent_id, tp.payment_des, tp.payment_status, tp.total_amount, tp.due_date, tp.payment_type as payment_for, tp.penalty_amount, tp.neft_reference,ta.late_penalty_percent, ta.min_penalty FROM tenant_payments tp INNER JOIN tenant_agreements ta ON tp.agreement_id = ta.id WHERE tp.tenant_id="' . $row['id'] . '" and tp.due_date <"' . $dateDue . '" and tp.payment_status IN (0, 2) ORDER BY due_date';
                            $connection = \Yii::$app->db;
                            $model = $connection->createCommand($sql);
                            $paymentDue = $model->queryAll();
                            //if ($key == 4) {print_r($pastPayment);exit;}
                            $tenants[$key]['overdue'] = FALSE;
                            $today = date('Y-m-d');
                            $totalDue = 0;
                            $earliestDue = "";
                            if (!empty($paymentDue)) {
                                foreach ($paymentDue as $payment) {
                                    $totalPenalty = Yii::$app->userdata->calculatePenalty($payment['id']);
                                    $totalAmount = $payment['total_amount'] + $totalPenalty;
                                    $totalDue = $totalDue + $totalAmount;
                                    if (empty($earliestDue) OR date('Ymd', strtotime($payment['due_date'])) < date('Ymd', strtotime($earliestDue))) {
                                        $earliestDue = $payment['due_date'];
                                    }
                                    $tenants[$key]['total_due'] = Yii::$app->userdata->getFormattedMoney($totalDue);
                                    if (date('Ymd',strtotime($payment['due_date'])) < date('Ymd',strtotime($today)) ) {
                                        $tenants[$key]['overdue'] = TRUE;
                                    }
                                    
                                }
                                $tenants[$key]['due_date'] = date('d-m-Y', strtotime($earliestDue));
                                
                            } else {
                                $tenants[$key]['total_due'] = "0";
                                $tenants[$key]['due_date'] = "";
                            
                            }
                        }
                    } else {
                        throw new \Exception('No tenant found for this PG');
                    }
                    
                    $respArr['status'] = TRUE;
                    $respArr['message'] = "Found ".count($tenants).((count($tenants) > 1) ? " tenants" : " tenant");
                    $respArr['data'] = $tenants;
                    $respArr['errors'] = '';
                    
                } else {
                    $respArr['status'] = FALSE;
                    $respArr['message'] = "Validation failed";
                    $respArr['errors'] = $validErrMess;
                }
            } catch (\Exception $exp) {
                $respArr['status'] = FALSE;
                $respArr['message'] = $exp->getMessage();
                $respArr['errors'] = $exp->getLine();
            }
        } else {
            $respArr['status'] = FALSE;
            $respArr['message'] = "Validation failed";
            $respArr['errors'] = "Missing parameters, check your inputs.";
        }
        
        echo Yii::$app->userdata->restResponse($respArr);
    }
    
    public function actionPglist () {
        $respArr = [];
        $respArr['status'] = FALSE;
        $respArr['message'] = '';
        $validErr = FALSE;
        $validErrMess = [];
        $input = Yii::$app->userdata->restRequest();
        
        $userID = Yii::$app->restbasicauth->getUserId();

        try {
            if (!$validErr) {
                $pgLists = [];
                
                $userType = Yii::$app->restbasicauth->getUserType();
                $where = "";
                if ($userType == 7) {
                    $opsProfileModel = \app\models\OperationsProfile::findOne(['operations_id' => $userID]);
                    $currentRoleCode = $opsProfileModel->role_code;
                    $currentRoleValue = $opsProfileModel->role_value;

                    if ($currentRoleValue == 'ELHQ') {
                        $where = " status = '1' AND property_type = 5 ORDER BY created_date DESC  "; 
                    } else if ($currentRoleCode == 'OPSTMG') {
                        $where = " status = '1' AND property_type = 5 AND state = '".$currentRoleValue."' ORDER BY created_date DESC ";
                    } else if ($currentRoleCode == 'OPCTMG') {
                        $where = " status = '1' AND property_type = 5 AND city = '".$currentRoleValue."' ORDER BY created_date DESC ";
                    } else if ($currentRoleCode == 'OPBRMG') {
                        $where = " status = '1' AND property_type = 5 AND branch_code = '".$currentRoleValue."' ORDER BY created_date DESC ";
                    } else if ($currentRoleCode == 'OPEXE') {
                        $branchModel = \app\models\Branches::find()->where(['branch_code' => $currentRoleValue])->one();
                        $where = " status = '1' AND property_type = 5 AND branch_code = '".$currentRoleValue."' AND city = '".$branchModel->city_code."' AND operations_id = '".$userID."' ORDER BY created_date DESC ";
                    }
                    
                    $pRow = Yii::$app->db->createCommand(''
                        . 'SELECT id, property_name FROM properties WHERE '.$where.' '
                        . '')->queryAll();
                    
                } else if ($userType == 6) {
                    $opsProfileModel = \app\models\SalesProfile::findOne(['sale_id' => $userID]);
                    $currentRoleCode = $opsProfileModel->role_code;
                    $currentRoleValue = $opsProfileModel->role_value;
                    
                    if ($currentRoleValue == 'ELHQ') {
                        $where = " status = '1' AND property_type = 5 ORDER BY created_date DESC ";
                    } else if ($currentRoleCode == 'SLSTMG') {
                        $where = " status = '1' AND property_type = 5 AND state = '".$currentRoleValue."' ORDER BY created_date DESC ";
                    } else if ($currentRoleCode == 'SLCTMG') {
                        $where = " status = '1' AND property_type = 5 AND city = '".$currentRoleValue."' ORDER BY created_date DESC ";
                    } else if ($currentRoleCode == 'SLBRMG') {
                        $where = " status = '1' AND property_type = 5 AND branch_code = '".$currentRoleValue."' ORDER BY created_date DESC ";
                    } else if ($currentRoleCode == 'SLEXE') {
                        $branchModel = \app\models\Branches::find()->where(['branch_code' => $currentRoleValue])->one();
                        $where = " status = '1' AND property_type = 5  AND branch_code = '".$currentRoleValue."' AND city = '".$branchModel->city_code."' AND sales_id = '".$userID."' ORDER BY created_date DESC ";
                    }
                    
                    $pRow = Yii::$app->db->createCommand(''
                        . 'SELECT id, property_name FROM properties WHERE '.$where.' '
                        . '')->queryAll();
       
                    } else if ($userType == 9) {
                    $pRow = Yii::$app->db->createCommand(''
                        . 'SELECT p.id, p.property_name FROM properties p INNER JOIN pg_map_operator po ON p.id = po.pg_id WHERE po.operator_id = '.$userID.' '
                        . " AND p.property_type = 5 AND p.status = '1' ORDER BY created_date DESC ")->queryAll();
                } else {
                    $pRow = [];
                }
                
                if (count($pRow) > 0) {
                    foreach ($pRow as $key => $row) {
                        $pgLists[$key]['id'] = $row['id'];
                        $pgLists[$key]['name'] = $row['property_name'];
                    }
                    
                    $respArr['status'] = TRUE;
                    $respArr['message'] = "Found ".count($pgLists)." PG";
                    $respArr['data'] = $pgLists;
                    $respArr['errors'] = '';
                } else {
                    $respArr['status'] = TRUE;
                    $respArr['message'] = "No PG found";
                    $respArr['data'] = $pgLists;
                    $respArr['errors'] = '';
                }

            } else {
                $respArr['status'] = FALSE;
                $respArr['message'] = "Validation failed";
                $respArr['errors'] = $validErrMess;
            }
        } catch (\Exception $exp) {
            $respArr['status'] = FALSE;
            $respArr['message'] = $exp->getMessage();
            $respArr['errors'] = $exp->getLine();
        }
        
        echo Yii::$app->userdata->restResponse($respArr);
    }
    
    public function actionPgtenantdetails () {
        $respArr = [];
        $respArr['status'] = FALSE;
        $respArr['message'] = '';
        $validErr = FALSE;
        $validErrMess = [];
        $input = Yii::$app->userdata->restRequest();
        
        if (!empty($input)) {
            $userID = $id = Yii::$app->restbasicauth->getUserId();
            $tenantID = @$input->tenant_id;
            $propertyID = @$input->pg_id;
            
            if (empty($propertyID)) {
                $validErr = TRUE;
                $validErrMess[] = 'Property ID is required';
            }
            
            if (empty($tenantID)) {
                $validErr = TRUE;
                $validErrMess[] = 'Tenant ID is required';
            }
            
            try {
                if (!$validErr) {
                    $tenants = [];
                    $taRow = Yii::$app->db->createCommand(''
                            . 'SELECT '
                            . 'u.id, u.full_name, u.login_id, u.phone, ta.rent, ta.maintainance, '
                            . 'ta.deposit, ta.lease_start_date, ta.lease_end_date, tp.address_line_1, '
                            . 'tp.address_line_2, tp.city_name, tp.state, tp.pincode, tp.proof_type, tp.proof_document_url, '
                            . 'tp.employment_proof_type, tp.employment_proof_url, ta.pg_room_name, tp.emergency_contact_name, '
                            . 'tp.emergency_contact_number, tp.emer_contact, tp.employer_name, tp.profile_image '
                            . 'FROM tenant_agreements ta '
                            . 'INNER JOIN users u ON ta.tenant_id = u.id '
                            . 'INNER JOIN tenant_profile tp ON u.id = tp.tenant_id '
                            . 'WHERE ta.property_id = ' . $propertyID . ' AND u.id = ' . $tenantID . ' '
                            . '')->queryOne();
                    
                    if ($taRow > 0) {
                        $tenants['id'] = $taRow['id'];
                        $tenants['name'] = $taRow['full_name'];
                        $tenants['email'] = $taRow['login_id'];
                        $tenants['mobile'] = $taRow['phone'];
                        $tenants['join_date'] = $taRow['lease_start_date'];
                        $tenants['exit_date'] = $taRow['lease_end_date'];
                        $tenants['address_line_1'] = $taRow['address_line_1'];
                        $tenants['address_line_2'] = $taRow['address_line_2'];
                        $tenants['city'] = $taRow['city_name'];
                        $tenants['state'] = $taRow['state'];
                        $tenants['pincode'] = $taRow['pincode'];
                        $tenants['room_name'] = $taRow['pg_room_name'];
                        $tenants['rent'] = $taRow['rent'];
                        $tenants['maintenance'] = $taRow['maintainance'];
                        $tenants['deposit'] = $taRow['deposit'];
                        $tenants['emergency_contact_name'] = $taRow['emergency_contact_name'];
                        $tenants['emergency_contact_number'] = $taRow['emergency_contact_number'];
                        $tenants['emergency_contact_email'] = $taRow['emer_contact'];
                        $tenants['employer_name'] = $taRow['employer_name'];
                       
                        $tenants['employee_id_proof'] = "";
                        $tenants['profile_image'] = "";
                        if ($taRow['employment_proof_url'] != "") {
                            $tenants['employee_id_proof'] = \yii\helpers\Url::home(TRUE) . $taRow['employment_proof_url'];
                        }
                        if ($taRow['profile_image'] != "") {
                            $tenants['profile_image'] = \yii\helpers\Url::home(TRUE) . $taRow['profile_image'];
                        }
                        
                        $docModel = \app\models\UserIdProofs::find()->where(['user_id' => $tenants['id']])->orderBy("id desc")->all();
                        $i = 0;
                        if (count($docModel)>0) {
                            foreach ($docModel as $key => $docRow) {
                                if ($i == 0 ) {
                                    $tenants['document_type_1'] = $docRow['proof_type'];
                                    $tenants['id_document_1'] = \yii\helpers\Url::home(TRUE) . $docRow['proof_document_url'];
                                } elseif ($i == 1) {
                                    $tenants['document_type_2'] = $docRow['proof_type'];
                                    $tenants['id_document_2'] = \yii\helpers\Url::home(TRUE) . $docRow['proof_document_url'];
                                }
                                
                                $i++;    

                            }
                        }
                        
                    } else {
                        throw new \Exception('No tenant details found.');
                    }
                    
                    $respArr['status'] = TRUE;
                    $respArr['message'] = "1 Tenant Found ";
                    $respArr['data'] = $tenants;
                    $respArr['errors'] = '';
                    
                } else {
                    $respArr['status'] = FALSE;
                    $respArr['message'] = "Validation failed";
                    $respArr['errors'] = $validErrMess;
                }
            } catch (\Exception $exp) {
                $respArr['status'] = FALSE;
                $respArr['message'] = $exp->getMessage();
                $respArr['errors'] = $exp->getLine();
            }
        } else {
            $respArr['status'] = FALSE;
            $respArr['message'] = "Validation failed";
            $respArr['errors'] = "Missing parameters, check your inputs.";
        }
        
        echo Yii::$app->userdata->restResponse($respArr);
    }
    
    public function actionPgtenantupdate () {
        $respArr = [];
        $isProfilePic = FALSE;
        $isDocType1 = FALSE;
        $isDocType2 = FALSE;
        $employeeID = FALSE;
        $validErr = FALSE;
        $validErrMess = [];
        
        if (!empty(Yii::$app->request->post())) {
            $input = Yii::$app->request->post();
            
            ////////////////// COLLECTING ITEMS STARTS HERE  /////////////////////////////////////////////
            
            $tenantID = @$input['tenant_id'];
            $fullName = @$input['full_name'];
            $addressLine1 = @$input['address_line_1'];
            $addressLine2 = @$input['address_line_2'];
            $cityName = @$input['city_name'];
            $stateCode = @$input['state_code'];
            $pinCode = @$input['pin_code'];
            $email = @$input['email'];
            $mobile = Yii::$app->userdata->trimPhone(@$input['mobile']);
            $rent = @$input['rent'];
            $maintenance = @$input['maintenance'];
            $deposit = @$input['deposit'];
            $checkInDate = @$input['check_in_date'];
            $propertyId = @$input['property_id'];
            $roomNo = @$input['room_no'];
            $emergencyContactName = @$input['emergency_contact_name'];
            $emergencyContactNumber = @$input['emergency_contact_number'];
            $emergencyContactEmail = @$input['emergency_contact_email'];
            $employerName = @$input['employer_name'];
            $exitDate = @$input['exit_date'];
            $idDocType1 = @$input['id_document_type_1'];
            $idDoc1 = @$_FILES['id_document_1']['name'];
            $idDocType2 = @$input['id_document_type_2'];
            $idDoc2 = @$_FILES['id_document_2']['name'];
            $photo = @$_FILES['photo']['name'];
            $document1_del = @$input['document1_del'];
            $document2_del = @$input['document2_del'];
            
            ////////////////// COLLECTING ITEMS ENDS HERE  /////////////////////////////////////////////
            
            //////////////////////  VALIDATION STARTS HERE  ////////////////////////////////////////////
            
            if (empty($tenantID)) {
                $validErr = TRUE;
                $validErrMess[] = 'Tenant ID is required';
            }
            
            if (empty($fullName)) {
                $validErr = TRUE;
                $validErrMess[] = 'Name is required';
            }

            if (empty($addressLine1)) {
                $validErr = TRUE;
                $validErrMess[] = 'Address line 1 is required';
            }

            if (empty($cityName)) {
                $validErr = TRUE;
                $validErrMess[] = 'City Name is required';
            }

            if (empty($stateCode)) {
                $validErr = TRUE;
                $validErrMess[] = 'State is required';
            } else {
                $state = \app\models\States::find()->where(['code' => $stateCode])->one();
                if (!$state) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Invalid State';
                }
            }

            if (empty($pinCode)) {
                $validErr = TRUE;
                $validErrMess[] = 'Pincode is required';
            }
            
            if (empty($email)) {
                $validErr = TRUE;
                $validErrMess[] = 'Email is required';
            }
            
            if (empty($mobile)) {
                $validErr = TRUE;
                $validErrMess[] = 'Mobile is required';
            }
            
            if (empty($rent)) {
                $validErr = TRUE;
                $validErrMess[] = 'Rent is required';
            }
            
            if (empty($maintenance)) {
                $maintenance = 0;
            }
            
            if (empty($deposit)) {
                $deposit = 0;
            }
            
            if (empty($checkInDate)) {
                $validErr = TRUE;
                $validErrMess[] = 'Check In Date is required';
            }
            
            if (empty($propertyId)) {
                $validErr = TRUE;
                $validErrMess[] = 'Property ID is required';
            }
            
            
            if (empty($idDocType1) AND (!empty($_FILES['id_document_1']['name']))) {
                $validErr = TRUE;
                $validErrMess[] = 'Document Type 1 not provided but Document 1 has been attached';
            }
            if (empty($idDocType2) AND (!empty($_FILES['id_document_2']['name']))) {
                $validErr = TRUE;
                $validErrMess[] = 'Document Type 2 not provided but Document 2 has been attached';
            }

            $tenantModel = \app\models\TenantProfile::find()->where(['tenant_id' => @$tenantID])->one();
            
            
            //////////////////////  VALIDATION ENDS HERE  ////////////////////////////////////////////
            
            if (!empty($_FILES['photo']['name'])) {
                $isProfilePic = TRUE;
                $imageTypes = array('image/jpeg', 'image/jpg', 'image/png', 'image/PNG');
                $profilePicName = $_FILES['photo']['name'];
                $profilePicType = $_FILES['photo']['type'];
                $profilePicTempName = $_FILES['photo']['tmp_name'];
                $profilePicError = $_FILES['photo']['error'];
                $profilePicSize = $_FILES['photo']['size'];

                if ($profilePicSize < 10) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Profile Image is corrupted';
                }

                if (!in_array($profilePicType, $imageTypes)) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Only PNG, JPG  files are allowed as Profile Image';
                }

                if ($profilePicError != 0) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Please try uploading your Profile Image again';
                }

                if ($profilePicError == 1) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Profile Image size exceeded, limit is ' . ini_get('upload_max_filesize') . 'B';
                }

                if ($profilePicError == 2) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Profile Image size exceeded, limit is ' . ini_get('upload_max_filesize') . 'B';
                }

                if ($profilePicError == 3) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Profile Image was uploaded partially';
                }
            }
             
            if (!empty($_FILES['id_document_1']['name'])) {
                $isDocType1 = true;
                $imageTypes = array('image/jpeg', 'image/jpg', 'image/png', 'application/pdf');
                $documentName1 = $_FILES['id_document_1']['name'];
                $documentType1 = $_FILES['id_document_1']['type'];
                $documentTempName1 = $_FILES['id_document_1']['tmp_name'];
                $documentError1 = $_FILES['id_document_1']['error'];
                $documentSize1 = $_FILES['id_document_1']['size'];

                if ($documentSize1 < 10) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Document Type 1, File is corrupted';
                }


                if (!in_array($documentType1, $imageTypes)) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Document Type 1, only PNG, JPG and PDF files are allowed';
                }

                if ($documentError1 != 0) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Document Type 1, please try uploading your file again';
                }

                if ($documentError1 == 1) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Document Type 1, file size exceeded, limit is ' . ini_get('upload_max_filesize') . 'B';
                }

                if ($documentError1 == 2) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Document Type 1, file size exceeded, limit is ' . ini_get('upload_max_filesize') . 'B';
                }

                if ($documentError1 == 3) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Document Type 1, file was uploaded partially';
                }
            }
            
            if (!empty($_FILES['id_document_2']['name'])) {
                $isDocType2 = true;
                $imageTypes = array('image/jpeg', 'image/jpg', 'image/png', 'application/pdf');
                $documentName2 = $_FILES['id_document_2']['name'];
                $documentType2 = $_FILES['id_document_2']['type'];
                $documentTempName2 = $_FILES['id_document_2']['tmp_name'];
                $documentError2 = $_FILES['id_document_2']['error'];
                $documentSize2 = $_FILES['id_document_2']['size'];

                if ($documentSize2 < 10) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Document Type 2, File is corrupted';
                }


                if (!in_array($documentType2, $imageTypes)) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Document Type 2, only PNG, JPG and PDF files are allowed';
                }

                if ($documentError2 != 0) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Document Type 2, please try uploading your file again';
                }

                if ($documentError2 == 1) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Document Type 2, file size exceeded, limit is ' . ini_get('upload_max_filesize') . 'B';
                }

                if ($documentError2 == 2) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Document Type 2, file size exceeded, limit is ' . ini_get('upload_max_filesize') . 'B';
                }

                if ($documentError2 == 3) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Document Type 2, file was uploaded partially';
                }
            }
            
            if (!empty($_FILES['employee_id']['name'])) {
                $employeeID = TRUE;
                $imageTypes = array('image/jpeg', 'image/jpg', 'image/png');
                $employeeIDName = $_FILES['employee_id']['name'];
                $employeeIDType = $_FILES['employee_id']['type'];
                $employeeIDTempName = $_FILES['employee_id']['tmp_name'];
                $employeeIDError = $_FILES['employee_id']['error'];
                $employeeIDSize = $_FILES['employee_id']['size'];

                if ($employeeIDSize < 10) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Employee ID is corrupted';
                }


                if (!in_array($employeeIDType, $imageTypes)) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Only PNG, JPG and PDF files are allowed as Employee ID';
                }

                if ($employeeIDError != 0) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Please try uploading your Employee ID again';
                }

                if ($employeeIDError == 1) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Employee ID size exceeded, limit is ' . ini_get('upload_max_filesize') . 'B';
                }

                if ($employeeIDError == 2) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Employee ID size exceeded, limit is ' . ini_get('upload_max_filesize') . 'B';
                }

                if ($employeeIDError == 3) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Employee ID was uploaded partially';
                }
            }
            
            $exitDate2 = ((!empty($exitDate)) ? date('Y-m-d', strtotime($exitDate)) : 0 );
            $tenantAgreementModel = \app\models\TenantAgreements::find()->where(['tenant_id' => $tenantID])->andWhere(['property_id' => $propertyId])->one();
            if (empty($tenantAgreementModel)) {
                $validErr = TRUE;
                $validErrMess[] = 'Tenant Agreement not found';
            } else {
                $exitDatePrevious = $tenantAgreementModel->lease_end_date;
                if ((empty($exitDatePrevious) and !empty($exitDate))) {
                    $validErr = TRUE;
                    $validErrMess[] = "Exit date cannot be preponed using this feature. Please use Exit Tenant feature to prepone the exit date";
                } else if (!empty($exitDatePrevious) AND !empty($exitDate )) {
                    if (date('Y-m-d',strtotime($exitDatePrevious)) > date('Y-m-d',strtotime($exitDate))) {
                        $validErr = TRUE;
                        $validErrMess[] = "Exit date cannot be preponed using this feature. Please use Exit Tenant feature to prepone the exit date";
                    }
                }
            }
                
            $transaction = Yii::$app->db->beginTransaction();
            
            try {
                if (!$validErr) {
                    $userId = 0;
                    $userId = Yii::$app->restbasicauth->getUserId();
                    $leadsTenantModel = \app\models\LeadsTenant::find()->where(['email_id' => Yii::$app->userdata->getEmailById($tenantID)])->one();
                    $userModel = \app\models\User::find()->where(['id' => $tenantID])->one();
                    $userModel->full_name = $fullName;
                    $userModel->login_id = $email;
                    $userModel->username = $email;
                    $userModel->phone = $mobile;
                    if (!$userModel->save(false)) {
                        throw \Exception('Something went wrong');
                    }
                    $applicantProfile = \app\models\ApplicantProfile::find()->where(['applicant_id' => $tenantID])->one();
                    $applicantProfile->email_id = $email;
                    $applicantProfile->phone = $mobile;
                    $applicantProfile->address_line_1 = $addressLine1;
                    $applicantProfile->address_line_2 = $addressLine2;
                    $applicantProfile->state = $stateCode;
                    $applicantProfile->city_name = $cityName;
                    $applicantProfile->pincode = $pinCode;
                    $applicantProfile->emergency_contact_email = $emergencyContactEmail;
                    $applicantProfile->emergency_contact_name = $emergencyContactName;
                    $applicantProfile->emergency_contact_number = $emergencyContactNumber;
                    $applicantProfile->employer_name = $employerName;
 
                    
                    $tenantModel = \app\models\TenantProfile::find()->where(['tenant_id' => $tenantID])->one();
                    $tenantModel->emer_contact = $emergencyContactNumber;
                    $tenantModel->emergency_contact_name = $emergencyContactName;
                    $tenantModel->emergency_contact_number = $emergencyContactNumber;
                    $tenantModel->address_line_1 = $addressLine1;
                    $tenantModel->address_line_2 = $addressLine2;
                    $tenantModel->state = $stateCode;
                    $tenantModel->city_name = $cityName;
                    $tenantModel->pincode = $pinCode;
                    $tenantModel->phone = $mobile;
                    $tenantModel->employer_name = $employerName;
                    
                    $leadsTenantModel->full_name = $fullName;
                    $leadsTenantModel->email_id = $email;
                    $leadsTenantModel->contact_number = $mobile;
                    $leadsTenantModel->address = $addressLine1;
                    $leadsTenantModel->address_line_2 = $addressLine2;
                    $leadsTenantModel->pincode = $pinCode;
                    $leadsTenantModel->updated_date = date('Y-m-d H:i:s');
                    
                    $tenantAgreementModel->property_id = $propertyId;
                    $tenantAgreementModel->parent_id = $propertyId;
                    $tenantAgreementModel->pg_room_name = $roomNo;
                    $tenantAgreementModel->lease_start_date = date('Y-m-d', strtotime($checkInDate));
                    $tenantAgreementModel->rent = $rent;
                    $tenantAgreementModel->maintainance = $maintenance;
                    $tenantAgreementModel->deposit = $deposit;
                    $tenantAgreementModel->lease_end_date = ((!empty($exitDate)) ? date('Y-m-d', strtotime($exitDate)) : NULL );
                    $tenantAgreementModel->agreement_url = NULL;
                    $tenantAgreementModel->updated_date = date('Y-m-d H:i:s');
                    $tenantAgreementModel->end_date = $exitDate;
                    $tenantAgreementModel->updated_by = $userId;
                    
                    if ($isProfilePic) {
                        $targetDir = "uploads/documents/";
                        if(!is_dir($targetDir)) {
                            mkdir($targetDir, 0777);
                        }
                        $targetFile = $targetDir . time() . basename($profilePicName);
                        move_uploaded_file($profilePicTempName, $targetFile);
                        $applicantProfile->profile_image = $targetFile;
                        $tenantModel->profile_image = $targetFile;
                    }
                    
                    if ($isDocType1) {
                        $docModel = new \app\models\UserIdProofs();
                        $targetDir = "uploads/documents/";
                        if(!is_dir($targetDir)) {
                            mkdir($targetDir, 0777);
                        }
                        $targetFile = $targetDir . time() . basename($documentName1);
                        move_uploaded_file($documentTempName1, $targetFile);
                        $docModel->user_id = $tenantID;
                        $docModel->created_by = $userId;
                        $docModel->proof_type = $idDocType1;
                        $docModel->proof_document_url = $targetFile;
                        $docModel->save(false);
                    }
                    
                    if ($isDocType2) {
                        $docModel = new \app\models\UserIdProofs();
                        $targetDir = "uploads/documents/";
                        if(!is_dir($targetDir)) {
                            mkdir($targetDir, 0777);
                        }
                        $targetFile = $targetDir . time() . basename($documentName2);
                        move_uploaded_file($documentTempName2, $targetFile);
                        $docModel->user_id = $tenantID;
                        $docModel->created_by = $userId;
                        $docModel->proof_type = $idDocType2;
                        $docModel->proof_document_url = $targetFile;
                        $docModel->save(false);
                    }
                    
                    if ($employeeID) {
                        $targetDir = "uploads/documents/";
                        if(!is_dir($targetDir)) {
                            mkdir($targetDir, 0777);
                        }
                        $targetFile = $targetDir . time() . basename($employeeIDName);
                        move_uploaded_file($employeeIDTempName, $targetFile);
                        $applicantProfile->employmnet_proof_url = $targetFile;
                        $tenantModel->employment_proof_url = $targetFile;
                    }
                    
                    if (!empty($document1_del)) {
                        $doc1Model = \app\models\UserIdProofs::find()->where(['proof_document_url' => $document1_del])->andWhere(['user_id' => $tenantID])->one();
                        if (!empty($doc1Model)) {
                            $doc1Model->delete(FALSE);
                        }
                    }
                    
                    if (!empty($document2_del)) {
                        $doc2Model = \app\models\UserIdProofs::find()->where(['proof_document_url' => $document2_del])->andWhere(['user_id' => $tenantID])->one();
                        if (!empty($doc2Model)) {
                            $doc2Model->delete(FALSE);
                        }
                    }
                    
                    if (!$applicantProfile->save(false) || !$tenantModel->save(false) || !$leadsTenantModel->save(false)) {
                        throw \Exception('Something went wrong, please try again later');
                    }
                    
                    if (!$tenantAgreementModel->save(false)) {
                        throw \Exception('Something went wrong, please try again later');
                    }
                    // if previousExitDate != newExitDate OR both are null then only there is need to add TP row for previousExitDate to month end or new end date which ever is earlier
                    if ( date('Y-m-d', strtotime($exitDatePrevious)) != date('Y-m-d', strtotime($exitDate)) ) {
                        $monthEndDate = date('Y-m-d', strtotime("last day of this month"));
                        if (date('Y-m-d', strtotime($exitDatePrevious)) < $monthEndDate) { 
                            if (date('Y-m-d',strtotime($exitDate)) > $monthEndDate or empty($exitDate)) {
                                $tpEndDate = $monthEndDate;
                            } else {
                                $tpEndDate = $exitDate;
                            }

                            $startDate = date_create(date('Y-m-d', strtotime("+1 day", strtotime($exitDatePrevious))));

                            $endDate = date_create(date('Y-m-d', strtotime($tpEndDate)));
                            $dateDiff = date_diff($startDate, $endDate);
                            $daysInMonth = cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y'));
                            // If start date > month end date then do nothing 
                            // If start date <= month end the perform action

                            if ($dateDiff->invert == 0) {
                                $daysOfRent = $dateDiff->days + 1;
                                $tenantPayments = new \app\models\TenantPayments();

                                $tenantPayments->tenant_id = $tenantID;
                                $tenantPayments->property_id = $propertyId;
                                $tenantPayments->parent_id = $propertyId;
                                $tenantPayments->original_amount = $rent * $daysOfRent / $daysInMonth; 
                                $tenantPayments->owner_amount = $rent * $daysOfRent / $daysInMonth;
                                $tenantPayments->maintenance = $maintenance * $daysOfRent / $daysInMonth;
                                $tenantPayments->payment_des = "PG Rent for the month of " . date("M-Y");
                                $tenantPayments->payment_mode = 0;
                                $tenantPayments->payment_type = 2;
                                $tenantPayments->payment_status = 0;
                                $tenantPayments->created_date = date("Y-m-d");
                                $tenantPayments->created_by = $userId;
                                $tenantPayments->updated_by = $userId;
                                $tenantPayments->penalty_amount = 0;
                                $tenantPayments->total_amount= ($rent + $maintenance) * $daysOfRent / $daysInMonth;
                                $tenantPayments->adhoc = 0;
                                $tenantPayments->updated_date = date("Y-m-d");
                                $tenantPayments->due_date = date("Y-m-d");
                                $tenantPayments->days_of_rent = $daysOfRent;
                                $tenantPayments->agreement_id =$tenantAgreementModel->id;
                                $tenantPayments->calculated = 0;
                                $tenantPayments->advisor_calculation_prop = 0;
                                $tenantPayments->advisor_calculation_tenant =0;
                                $tenantPayments->month = date("m-Y");
                                $tenantPayments->tenant_amount = $rent * $daysOfRent / $daysInMonth;
                                $tenantPayments->processed = 0;
                                $tenantPayments->amount_paid = 0;
                                if (!$tenantPayments->save(false)) {
                                        throw \Exception('Something went wrong, please try again later');
                                }
                            }
                            
                        }
                    }                  
                    
                    
                    $transaction->commit();
                    
                    /////////////////////////// SEND NOTIFICATIONS TO USER USING SMS AND EMAIL //////////////////////
                    $webURL= \yii\helpers\Url::home(true)."site/login";
                    $txtMess = 'Hello, Your profile and/or agreement details have been updated with Easyleases. Verify on ' . $webURL . ' Or Easyleases Tenant App.';
                    Yii::$app->userdata->sendSms([$mobile], $txtMess);
                    
                    $subject = "Tenancy details updated";
                    $msgContent = ""
                            ."Hello ".$fullName.","
                            . "<br><br>"
                            . "Quick note to confirm your profile or tenancy details have been updated. "
                            . "You may review the changes via our Web platform or mobile apps on Android/iOS. "
                            . "<br><br>"
                            . "With Regards, <br>"
                            . "Easyleases Team";
                    $message = $this->renderPartial('pg-app-email-template', [
                        'msgContent' => $msgContent
                    ]);
                    $email = Yii::$app->userdata->doMail($email, $subject, $message);
                    
                    /////////////////////////// SEND NOTIFICATIONS TO USER USING SMS AND EMAIL //////////////////////

                    $respArr['status'] = TRUE;
                    $respArr['message'] = "Profile updated successfully";
                    $respArr['errors'] = "";
                } else {
                    $respArr['status'] = FALSE;
                    $respArr['message'] = "Validation failed";
                    $respArr['errors'] = $validErrMess;
                }
            } catch (\Exception $exp) {
                $transaction->rollBack();
                $respArr['status'] = FALSE;
                $respArr['message'] = $exp->getMessage();
                $respArr['errors'] = $exp->getLine();
            }
        } else {
            $respArr['status'] = FALSE;
            $respArr['message'] = "Validation failed";
            $respArr['errors'] = "Missing parameters, check your data form.";
        }

        echo Yii::$app->userdata->restResponse($respArr);
    }
    
    public function actionTenantpaymentreceipt () {
        $respArr = [];
        $isPaymentProof = FALSE;
        $validErr = FALSE;
        $validErrMess = [];

        if (!empty(Yii::$app->request->post())) {
            $input = Yii::$app->request->post();
            
            ////////////////// COLLECTING ITEMS STARTS HERE  /////////////////////////////////////////////
            
            $tenantID = @$input['tenant_id'];
            $propertyID = @$input['property_id'];
            $amountPaid = @$input['amount_paid'];
            $paymentMode = @$input['payment_mode'];
            $transRef = @$input['transaction_reference'];
            $paymentProof = @$_FILES['payment_proof']['name'];
            $remarks = @$input['remarks'];
            
            ////////////////// COLLECTING ITEMS ENDS HERE  /////////////////////////////////////////////
            
            //////////////////////  VALIDATION STARTS HERE  ////////////////////////////////////////////

            if (empty($tenantID)) {
                $validErr = TRUE;
                $validErrMess[] = 'Tenant ID is required';
            }
            
            if (empty($propertyID)) {
                $validErr = TRUE;
                $validErrMess[] = 'Property ID is required';
            }

            if (empty($amountPaid)) {
                $validErr = TRUE;
                $validErrMess[] = 'Amount paid is required';
            }

            if (empty($paymentMode)) {
                $validErr = TRUE;
                $validErrMess[] = 'Payment mode is required';
            }

            
            //////////////////////  VALIDATION ENDS HERE  ////////////////////////////////////////////
            
            if (!empty($_FILES['payment_proof']['name'])) {
                $isPaymentProof = TRUE;
                $imageTypes = array('image/jpeg', 'image/jpg', 'image/png');
                $paymentProofName = $_FILES['payment_proof']['name'];
                $paymentProofType = $_FILES['payment_proof']['type'];
                $paymentProofTempName = $_FILES['payment_proof']['tmp_name'];
                $paymentProofError = $_FILES['payment_proof']['error'];
                $paymentProofSize = $_FILES['payment_proof']['size'];
                
                if ($paymentProofSize < 10) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Payment proof is corrupted';
                }

                if (!in_array($paymentProofType, $imageTypes)) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Only PNG and JPG files are allowed for Payment proof';
                }

                if ($paymentProofError != 0) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Please try uploading your payment proof again';
                }

                if ($paymentProofError == 1) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Payment proof size exceeded, limit is ' . ini_get('upload_max_filesize') . 'B';
                }

                if ($paymentProofError == 2) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Payment proof size exceeded, limit is ' . ini_get('upload_max_filesize') . 'B';
                }

                if ($paymentProofError == 3) {
                    $validErr = TRUE;
                    $validErrMess[] = 'Payment proof was uploaded partially, please try again';
                }
            }
            
            $transaction = Yii::$app->db->beginTransaction();
            
            try {
                if (!$validErr) {
                    $datedue = Date('Y-m-d', strtotime('+1 month'));
                    $sql = 'SELECT tp.id, tp.parent_id, tp.payment_des, tp.payment_status,'
                            . ' tp.total_amount, tp.due_date, tp.payment_type as payment_for,'
                            . ' tp.penalty_amount, tp.neft_reference,ta.late_penalty_percent, '
                            . 'ta.min_penalty FROM tenant_payments tp INNER JOIN tenant_agreements '
                            . 'ta ON tp.agreement_id = ta.id WHERE tp.tenant_id= "' . $tenantID . '" '
                            . 'and tp.property_id = "' . $propertyID . '" and tp.due_date <"' . $datedue 
                            . '" and tp.payment_status <> 1 ORDER BY due_date';
                    
                    $connection = \Yii::$app->db;
                    $model = $connection->createCommand($sql);
                    $pastPayment = $model->queryAll();
                    
                    $totalAmount = 0;
                    $totalOutstanding = 0;
                    $rentOff = 0;
                    $tpIDS = [];
                    if (!empty($pastPayment)) {
                        foreach ($pastPayment as $payment) {
                            $totalAmount = $totalAmount + $payment['total_amount'];
                            $tpIDS[] = $payment['id'];
                            $rentOff++;
                        }
                    }
                    
                    if ($amountPaid > $totalAmount) {
                        $respArr['status'] = FALSE;
                        $respArr['message'] = "Validation failed";
                        $respArr['errors'] = ['Amount paid cannot be greater than due amount'];
                        echo Yii::$app->userdata->restResponse($respArr);exit;
                    }
                   
                    $totalOutstanding = $totalAmount - $amountPaid;
                    
                    if ($rentOff > 0) {
                        if (round($totalAmount) == round($amountPaid)) {
                            foreach ($tpIDS as $tpID) {
                                $tpModel = \app\models\TenantPayments::find()->where(['id' => $tpID])->one();
                                $tpModel->payment_mode = $paymentMode;
                                $tpModel->payment_status = 1;
                                $tpModel->neft_reference = $transRef;
                                $tpModel->amount_paid = ($amountPaid/(count($tpIDS)));
                                $tpModel->payment_date = date('Y-m-d');
                                $tpModel->save(false);
                            }
                        } else {
                            $totalAmountPro = $amountPaid;
                            foreach ($tpIDS as $tpID) {
                                $tpModel = \app\models\TenantPayments::find()->where(['id' => $tpID])->one();
                                if ($totalAmountPro >= $tpModel->total_amount) {
                                    $totalAmountPro = $totalAmountPro - $tpModel->total_amount;
                                    $tpModel->payment_mode = $paymentMode;
                                    $tpModel->payment_status = 1;
                                    $tpModel->neft_reference = $transRef;
                                    $tpModel->amount_paid = $tpModel->total_amount;
                                    $tpModel->payment_date = date('Y-m-d');
                                    $tpModel->save(false);
                                } else if ($totalAmountPro > 0) {
                                    $totalAmtPerDay = $tpModel->total_amount/$tpModel->days_of_rent;
                                    $originalAmountPerDay = $tpModel->original_amount/$tpModel->days_of_rent;
                                    $maintenancePerDay = $tpModel->maintenance/$tpModel->days_of_rent;
                                    $tenantAmountperDay = $tpModel->tenant_amount/$tpModel->days_of_rent;
                                    $ownerAmountperDay = $tpModel->owner_amount/$tpModel->days_of_rent;
                                    $newDaysOfRent = $totalAmountPro/$totalAmtPerDay;
                                    $insertDaysOfRent = $tpModel->days_of_rent - $newDaysOfRent;
                                    //$newTotalAmount = ($totalAmtPerDay * $newDaysOfRent) + ($maintenancePerDay * $newDaysOfRent);
                                    
                                    $tpModel->payment_mode = $paymentMode;
                                    $tpModel->payment_status = 1;
                                    $tpModel->neft_reference = $transRef;
                                    $tpModel->original_amount = $originalAmountPerDay * $newDaysOfRent;
                                    $tpModel->total_amount = $totalAmtPerDay * $newDaysOfRent;
                                    $tpModel->maintenance = $maintenancePerDay * $newDaysOfRent;
                                    $tpModel->owner_amount = $ownerAmountperDay * $newDaysOfRent;
                                    $tpModel->tenant_amount = $tenantAmountperDay * $newDaysOfRent;
                                    $tpModel->amount_paid = $totalAmountPro;
                                    $tpModel->days_of_rent = $newDaysOfRent;
                                    $tpModel->payment_date = date('Y-m-d');
                                    
                                    
                                    $tpModel2 = new \app\models\TenantPayments();
                                    $tpModel2->attributes = $tpModel->attributes;
                                    
                                    //$tpModel2->room_id = $tpModel->room_id;
                                    //$tpModel2->bed_id = $tpModel->bed_id;
                                    $tpModel2->original_amount = $originalAmountPerDay * $insertDaysOfRent;
                                    $tpModel2->total_amount = $totalAmtPerDay * $insertDaysOfRent;
                                    $tpModel2->owner_amount = $ownerAmountperDay * $insertDaysOfRent;
                                    $tpModel2->maintenance = $maintenancePerDay * $insertDaysOfRent;
                                    $tpModel2->tenant_amount = $tenantAmountperDay * $insertDaysOfRent;
                                    
                                    $tpModel2->payment_mode = 0;
                                    $tpModel2->payment_status = 0;
                                    $tpModel2->remarks = $tpModel->remarks;
                                    $tpModel2->due_date = $tpModel->due_date;
                                    $tpModel2->days_of_rent = $insertDaysOfRent;
                                    $tpModel2->agreement_id = $tpModel->agreement_id;
                                    $tpModel2->month = $tpModel->month;
                                    
//                                    if ($totalAmountPro >= $tpModel->maintenance) {
//                                        $tpModel2->total_amount = $tpModel->total_amount;
//                                        $tpModel2->maintenance = $totalAmountPro - $tpModel->maintenance;
//                                    } else {
//                                        $tpModel2->total_amount = $tpModel->total_amount - $totalAmountPro;
//                                        $tpModel2->maintenance = $tpModel->maintenance;
//                                    }
                                    $tpModel->save(false);
                                    $tpModel2->save(FALSE);
                                    
                                    
                                    $totalAmountPro = $totalAmountPro - $tpModel->total_amount;
                                }
                            }
                        }
                        
                        $propertyIncomeModel = new \app\models\PropertyIncome();
                        $propertyIncomeModel->property_id = $propertyID;
                        $propertyIncomeModel->customer_id = $tenantID;
                        $propertyIncomeModel->customer_name = Yii::$app->userdata->getFullNameById($tenantID);
                        $propertyIncomeModel->income_type = 'RNT';
                        $propertyIncomeModel->total_income_amount = (double) $amountPaid;
                        $propertyIncomeModel->transaction_num = $transRef;
                        $propertyIncomeModel->transaction_mode = $paymentMode;
                        $propertyIncomeModel->paid_on = date('Y-m-d');
                        $propertyIncomeModel->remarks = $remarks;
                        
                        if (Yii::$app->restbasicauth->getUserType() == 9) {
                            $propertyIncomeModel->received_by_entity = 'PG Operator';
                        } else if (Yii::$app->restbasicauth->getUserType() == 7 || Yii::$app->restbasicauth->getUserType() == 6) {
                            $propertyIncomeModel->received_by_entity = 'Easyleases';
                        }
                        $propertyIncomeModel->received_by_person = Yii::$app->restbasicauth->getFullName();
                        $propertyIncomeModel->month = date('Y-m-01');
                        $opsProfile = \app\models\OperationsProfile::find()->where(['role_value' => 'ELHQ'])->one();
                        $propertyIncomeModel->approved_by = @$opsProfile->operations_id;
                        $propertyIncomeModel->created_by = @$opsProfile->operations_id;
                        $propertyIncomeModel->created_date = date('Y-m-d');
                        
                        if ($isPaymentProof) {
                            $targetDir = "uploads/documents/";
                            if(!is_dir($targetDir)) {
                                mkdir($targetDir, 0777);
                            }
                            $targetFile = $targetDir . time() . basename($paymentProofName);
                            move_uploaded_file($paymentProofTempName, $targetFile);
                            $propertyIncomeModel->attachment = $targetFile;
                        }
                        
                        if (!$propertyIncomeModel->save(false)) {
                            throw new \Exception('Exception');
                        }
                    }
                    
                    
                    
                    //exit('----- THE END -----');
                    
                    $transaction->commit();
                    
                    /////////////////////////// SEND NOTIFICATIONS TO USER USING SMS AND EMAIL //////////////////////
                    $txtMess = 'Hi '.Yii::$app->userdata->getFullNameById($tenantID).', We confirm receipt of Rs '.$amountPaid.' using payment mode: '.Yii::$app->userdata->getPaymentModeType($paymentMode);
                    $mobile = Yii::$app->userdata->getPhoneNumberById($tenantID, 3);
                    $webhomeURL = \yii\helpers\Url::home(true)."site/login";
                    $playStoreURL = Yii::$app->params['play_store_url'];
                    $appStoreURL = Yii::$app->params['app_store_url'];
                    Yii::$app->userdata->sendSms([$mobile], $txtMess);
                    
                    $maintenanceTxt = (!empty($maintenance)) ? "<b>Maintenance:</b> $maintenance, <br>" : "";
                    $exitDateTxt = (!empty($exitDate)) ? "<b>Exit Date:</b> $exitDate, <br>" : "";
                    
                    $subject = 'Payment Receipt Confirmation';
                    $msgContent = "Hi ".Yii::$app->userdata->getFullNameById($tenantID).","
                            . "<br><br>"
                            . "We confirm receipt of &#8377;" . $amountPaid . " using payment mode: " . Yii::$app->userdata->getPaymentModeType($paymentMode) . ""
                            . ". <br><br>"
                            . "Outstanding After the Payment: &#8377; ".round($totalOutstanding)."."
                            . "<br><br>"
                            . "In case of any clarification, please reach out to your property manager. "
                            . "Rent receipt for each month can be downloaded from our Web or APP dashboard."
                            . "Best Regards,<br>"
                            . "Easyleases Team";
                    
                    $message = $this->renderPartial('pg-app-email-template', [
                        'msgContent' => $msgContent
                    ]);
                            

                    $email = Yii::$app->userdata->doMail(Yii::$app->userdata->getEmailById($tenantID), $subject, $message);
                    
                    /////////////////////////// SEND NOTIFICATIONS TO USER USING SMS AND EMAIL //////////////////////

                    $respArr['status'] = TRUE;
                    $respArr['message'] = "Payment updated successfully";
                    $respArr['errors'] = "";
                } else {
                    $respArr['status'] = FALSE;
                    $respArr['message'] = "Validation failed";
                    $respArr['errors'] = $validErrMess;
                }
            } catch (\Exception $exp) {
                $transaction->rollBack();
                $respArr['status'] = FALSE;
                $respArr['message'] = $exp->getMessage();
                $respArr['errors'] = $exp->getLine();
            }
        } else {
            $respArr['status'] = FALSE;
            $respArr['message'] = "Validation failed";
            $respArr['errors'] = "Missing parameters, check your data form.";
        }

        echo Yii::$app->userdata->restResponse($respArr);
    }
}