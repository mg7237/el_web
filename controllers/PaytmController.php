<?php

namespace app\controllers;

use yii\web\NotFoundHttpException;
use Yii;
use yii\web\UploadedFile;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\PropertyServiceRequest;
use app\models\LoginForm;
use app\models\MyAgreements;
use app\models\LeadsTenant;
use app\models\LeadsOwner;
use app\models\PropertyAgreements;
use app\models\Agreements;
use app\models\Users;
use app\models\Profiles;
use app\models\User;
use app\models\PropertyVisits;
use app\models\TenantAgreements;
use app\models\ContactForm;
use app\models\ForgetForm;
use app\models\Feedback;
use app\models\RegisterForm;
use app\models\Properties;
use app\models\PropertiesAddress;
use app\models\PropertyAttributeMap;
use app\models\PropertyImages;
use app\models\Bookings;
use app\models\BookProperties;
use app\models\PropertiesAttributes;
use yii\helpers\Url;
use app\models\FavouriteProperties;
use app\models\Wallets;
use app\models\WalletsHistory;
use app\models\BankDetails;
use app\models\UserIdProofs;
use app\models\Stepinfo;
use app\models\TenantPayments;
use yii\data\Pagination;
use app\components\Pdfcrowd;
use app\models\ServiceRequest;
use app\models\ServiceRequestAttachment;
use app\models\ApplicantProfile;
use app\models\Advertisements;
use app\models\OwnerPaymentSummary;

class PaytmController extends Controller {

    public $layout = false;

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['payrent', 'payrentpaytmnetbanking','rentpaymentresponse', 'bookproperty', 'bookingresponse'],
                'rules' => [
                    [
                        'actions' => ['payrent', 'payrentpaytmnetbanking','rentpaymentresponse', 'bookproperty', 'bookingresponse'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }
    
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'oAuthSuccess'],
                'cancelUrl' => Url::to(['site/login'])
            ],
            'auth2' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'oAuthSuccess2'],
                'cancelUrl' => Url::to(['site/login'])
            ],
        ];
    }
    
    public function beforeAction($action) {
        $methods = [
            'rentpaymentresponse',
            'rentpaymentresponsenouth',
            'bookingresponse',
            'payrentfromapp',
            'rentpaymentresponseapp',
            'sendtenantpaymentmail',
            'payrentfromweb',
            'rentpaymentresponseweb',
            'payrentfrompaytmnetbankingapp'
        ];
        if (in_array($action->id, $methods)) {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }
    
    public function actionBookproperty () {
        $this->layout = false;
        $referUrl = Url::home(true) . 'paytm/bookproperty';
        $returnUrl = Url::home(true).'paytm/bookingresponse';
        
        if (!empty(Yii::$app->request->post('order_id'))) {
            $transaction = Yii::$app->db->beginTransaction();
            
            try {
                $pgiModel = \app\models\PaymentGateway::find()->where(['order_id' => Yii::$app->request->post('order_id')])->one();

                if (count($pgiModel) == 0) {
                    throw new \Exception('Exception');
                }

                if ($pgiModel->txn_for != 1) {
                    throw new \Exception('Incorrect parameters');
                }
                
                $propertyId = $pgiModel->property_id;
                $merchantId = Yii::$app->params['paytm_merchant_id'];
                $orderId = Yii::$app->userdata->generateOrderId(Yii::$app->userdata->id);
                $customerId = Yii::$app->userdata->generateCustomerId(Yii::$app->userdata->id);
                $txnAmount = $pgiModel->amount;
                $transactionURL = (Yii::$app->params['paytm_env'] == 'dev') ? Yii::$app->params['paytm_txn_api_dev'] : Yii::$app->params['paytm_txn_api_prod'] ;

                $paytmParams = array();
                $paytmParams["MID"] = $merchantId;
                $paytmParams["ORDER_ID"] = $orderId;
                $paytmParams["CUST_ID"] = $customerId;
                $paytmParams["MOBILE_NO"] = Yii::$app->userdata->getPhoneNumber(Yii::$app->user->id);
                $paytmParams["EMAIL"] = Yii::$app->userdata->getEmailById(Yii::$app->user->id);
                $paytmParams["CHANNEL_ID"] = Yii::$app->params['paytm_channel_id'];
                $paytmParams["TXN_AMOUNT"] = $txnAmount;
                $paytmParams["WEBSITE"] = Yii::$app->params['paytm_merchant_website'];
                $paytmParams["INDUSTRY_TYPE_ID"] = Yii::$app->params['paytm_industry_type_id'];
                $paytmParams["CALLBACK_URL"] = $returnUrl;
                $paytmParams["PAYTM_CHECKSUM"] = Yii::$app->paytm->getChecksumFromArray($paytmParams, Yii::$app->params['paytm_merchant_key']);

                if (!empty(Yii::$app->request->post('app_redirect_url'))) {
                    Yii::$app->session->set('app_redirect_url', Yii::$app->request->post('app_redirect_url'));
                }

                $checksum = $paytmParams["PAYTM_CHECKSUM"];
                $msg = json_encode($paytmParams);
                unset($paytmParams["PAYTM_CHECKSUM"]);
                
                $model = new \app\models\PaymentGateway();
                $model->customer_id = $customerId;
                $model->order_id = $orderId;
                $model->property_id = $propertyId;
                $model->user_type = Yii::$app->userdata->getusertype();
                $model->user_id = Yii::$app->userdata->id;
                $model->amount = $txnAmount;
                $model->request_time = date('Y-m-d H:i:s');
                $model->merchant_id = $merchantId;
                $model->checksum = $msg;
                $model->refer_url = $referUrl;
                $model->txn_for = 1;
                if (!$model->save()) {
                    throw new \Exception('Exception');
                }

                $favouriteProperties = \app\models\FavouriteProperties::find()->where(['applicant_id' => Yii::$app->userdata->getEmailById(Yii::$app->userdata->id), 'property_id' => $propertyId])->one();
                if ($favouriteProperties) {
                    if (!empty($_POST['bedroom_info'])) {
                        $bedroom_intel = json_decode($_POST['bedroom_info']);
                        $favouriteProperties->child_properties = $bedroom_intel->bed_id;
                        $favouriteProperties->pgi_reference = Yii::$app->db->getLastInsertID();
                        Yii::$app->userdata->checkCoProListingStatus($propertyId);
                    } else {
                        $favouriteProperties->pgi_reference = Yii::$app->db->getLastInsertID();
                    }
                    if (!$favouriteProperties->save(false)) {
                        throw new \Exception('Exception');
                    }
                }

                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->getSession()->setFlash(
                        'success', "{$e->getMessage()}"
                );
            }
            
            return $this->render('paytm_pay', ['transactionURL' => $transactionURL, 'paytmParams' => $paytmParams, 'paytmChecksum' => $checksum]);
            exit;
        }
        
        if (Yii::$app->request->post()) {
            $propertyId = Yii::$app->request->post('PropertyVisits')['property_id'];
            $merchantId = Yii::$app->params['paytm_merchant_id'];
            $orderId = Yii::$app->userdata->generateOrderId(Yii::$app->userdata->id);
            $customerId = Yii::$app->userdata->generateCustomerId(Yii::$app->userdata->id);
            $txnAmount = Yii::$app->request->post('PropertyVisits')['txn_amount'];
            $transactionURL = (Yii::$app->params['paytm_env'] == 'dev') ? Yii::$app->params['paytm_txn_api_dev'] : Yii::$app->params['paytm_txn_api_prod'] ;

            $paytmParams = array();
            $paytmParams["MID"] = $merchantId;
            $paytmParams["ORDER_ID"] = $orderId;
            $paytmParams["CUST_ID"] = $customerId;
            $paytmParams["MOBILE_NO"] = Yii::$app->userdata->getPhoneNumber(Yii::$app->user->id);
            $paytmParams["EMAIL"] = Yii::$app->userdata->getEmailById(Yii::$app->user->id);
            $paytmParams["CHANNEL_ID"] = Yii::$app->params['paytm_channel_id'];
            $paytmParams["TXN_AMOUNT"] = $txnAmount;
            $paytmParams["WEBSITE"] = Yii::$app->params['paytm_merchant_website'];
            $paytmParams["INDUSTRY_TYPE_ID"] = Yii::$app->params['paytm_industry_type_id'];
            $paytmParams["CALLBACK_URL"] = $returnUrl;
            $paytmParams["PAYTM_CHECKSUM"] = Yii::$app->paytm->getChecksumFromArray($paytmParams, Yii::$app->params['paytm_merchant_key']);

            if (!empty(Yii::$app->request->post('app_redirect_url'))) {
                Yii::$app->session->set('app_redirect_url', Yii::$app->request->post('app_redirect_url'));
            }

            $checksum = $paytmParams["PAYTM_CHECKSUM"];
            $msg = json_encode($paytmParams);
            unset($paytmParams["PAYTM_CHECKSUM"]);


            $transaction = Yii::$app->db->beginTransaction();

            try {
                $model = new \app\models\PaymentGateway();
                $model->customer_id = $customerId;
                $model->order_id = $orderId;
                $model->property_id = $propertyId;
                $model->user_type = Yii::$app->userdata->getusertype();
                $model->user_id = Yii::$app->userdata->id;
                $model->amount = $txnAmount;
                $model->request_time = date('Y-m-d H:i:s');
                $model->merchant_id = $merchantId;
                $model->checksum = $msg;
                $model->refer_url = $referUrl;
                $model->txn_for = 1;
                if (!$model->save()) {
                    throw new \Exception('Exception');
                }

                $favouriteProperties = \app\models\FavouriteProperties::find()->where(['applicant_id' => Yii::$app->userdata->getEmailById(Yii::$app->userdata->id), 'property_id' => $propertyId])->one();
                if ($favouriteProperties) {
                    if (!empty($_POST['bedroom_info'])) {
                        $bedroom_intel = json_decode($_POST['bedroom_info']);
                        $favouriteProperties->child_properties = $bedroom_intel->bed_id;
                        $favouriteProperties->pgi_reference = Yii::$app->db->getLastInsertID();
                        Yii::$app->userdata->checkCoProListingStatus($propertyId);
                    } else {
                        $favouriteProperties->pgi_reference = Yii::$app->db->getLastInsertID();
                    }
                    if (!$favouriteProperties->save(false)) {
                        throw new \Exception('Exception');
                    }
                }

                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->getSession()->setFlash(
                        'success', "{$e->getMessage()}"
                );
            }

            return $this->render('paytm_pay', ['transactionURL' => $transactionURL, 'paytmParams' => $paytmParams, 'paytmChecksum' => $checksum]);
            
        } else {
            throw new \yii\web\ForbiddenHttpException('You are not allowed to directly access this page.');
        }
    }
    
    public function actionBookingresponse () {
        if (Yii::$app->request->post()) {
            $response = Yii::$app->request->post();
            $orderId = @$response['ORDERID'];
            $merchantId = @$response['MID'];
            $txnRefNo = @$response['TXNID'];
            $bankRefNo = @$response['BANKTXNID'];
            $txnAmount = @$response['TXNAMOUNT'];
            $bankId = @$response['BANKNAME'];
            $currencyName = @$response['CURRENCY'];
            $txnDate = @$response['TXNDATE'];
            $authStatus = @$response['STATUS'];
            $resStatus = @$response['RESPCODE'];
            $resDesc = @$response['RESPMSG'];
            $paymentMode = @$response['PAYMENTMODE'];
            $gatewayName = @$response['GATEWAYNAME'];
            $checkSum = @$response['CHECKSUMHASH'];

            $model = \app\models\PaymentGateway::find()->where(['order_id' => $orderId])->one();
            $propertyId = $model->property_id;
            $pgiReference = $model->id;
            $customerId = $model->customer_id;
            if ($authStatus == 'TXN_SUCCESS') {
                $model->payment_status = 1;
            } else {
                $model->payment_status = 2;
            }
            
            $model->response_time = date('Y-m-d H:i:s');
            $model->txn_reference_no = $txnRefNo;
            $model->bank_reference_no = $bankRefNo;
            $model->bank_id = $bankId;
            $model->currency_name = $currencyName;
            $model->auth_status = $authStatus;
            $model->description = $resDesc;
            $model->payment_mode = $paymentMode;
            $model->gateway_name = $gatewayName;
            $model->save();

            Yii::$app->session->setFlash('noti-retry', $orderId);

            if ($authStatus == 'TXN_SUCCESS') {
                /////////////////////////// Adding to wallet [Starts Here] //////////////////////////
                $pgiModel = \app\models\PaymentGateway::find()->where(['order_id' => $orderId])->one();
                $userId = $pgiModel->user_id;
                // Starting transaction
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    /* Adding to wallets table */
                    $walletModel = \app\models\Wallets::find()->where(['user_id' => $userId])->one();
                    if ($walletModel) {
                        $walletModel->amount = (double) $txnAmount + (double) $walletModel->amount;
                        $walletModel->property_id = $propertyId;
                        $walletModel->updated_by = Yii::$app->userdata->id;
                        if (!$walletModel->save(false)) {
                            throw new \Exception('Exception');
                        }
                    } else {
                        $walletModel = new \app\models\Wallets();
                        $walletModel->user_id = $userId;
                        $walletModel->property_id = $propertyId;
                        $walletModel->amount = (double) $txnAmount;
                        $walletModel->created_by = Yii::$app->userdata->id;
                        $walletModel->created_date = date('Y-m-d H:i:s');
                        if (!$walletModel->save(false)) {
                            throw new \Exception('Exception');
                        }
                    }
                    /////////////////////////// Adding to wallet [Ends Here] ////////////////////////////

                    $childPropertyId = 0;

                    $favProp = FavouriteProperties::find()->where([
                        'applicant_id' => Yii::$app->userdata->getUserEmailById(Yii::$app->user->id),
                        'property_id' => $propertyId
                    ])->one();
                    $childPropertyId = $favProp->child_properties;
                    $favProp->status = 2;
                    if (!$favProp->save(false)) {
                        throw new \Exception('Exception');
                    }

                    // Adding to wallets_history table /////

                    $walletHistoryModel = new \app\models\WalletsHistory();
                    $walletHistoryModel->user_id = $userId;
                    $walletHistoryModel->transaction_type = 1;
                    $walletHistoryModel->property_id = $propertyId;
                    $walletHistoryModel->child_id = (empty($childPropertyId)) ? 0 : $childPropertyId;
                    $walletHistoryModel->amount = (double) $txnAmount;
                    $walletHistoryModel->operation_type = 0;
                    $walletHistoryModel->pgi_reference = $pgiModel->id;
                    $walletHistoryModel->created_by = Yii::$app->userdata->id;
                    $walletHistoryModel->created_date = date('Y-m-d H:i:s');
                    if (!$walletHistoryModel->save(false)) {
                        throw new \Exception('Exception');
                    }

                    $propDetail = Properties::find()->where(['id' => $propertyId])->one();

                    if ($propDetail->property_type != 3) {
                        $parentPropId = 0;
                        $parentPropChngStatus = 1;
                        $propList = \app\models\ChildProperties::find()->where(['id' => $childPropertyId])->one();
                        $propList->status = 0;

                        $parentPropId = $propList->main;

                        if (!$propList->save(false)) {
                            throw new \Exception('Exception');
                        }

                        $propLists = \app\models\ChildProperties::find()->where(['main' => $parentPropId, 'type' => 2])->all();

                        if ($propLists) {
                            foreach ($propLists as $propList) {
                                if ($propList->status == 1) {
                                    $parentPropChngStatus = 0;
                                    break;
                                }
                            }

                            if ($parentPropChngStatus === 1) {
                                $mainProp = \app\models\PropertyListing::find()->where(['property_id' => $parentPropId])->one();
                                $mainProp->status = '0';

                                if (!$mainProp->save(false)) {
                                    throw new \Exception('Exception');
                                }
                            }
                        }
                    } else {
                        $propList = \app\models\PropertyListing::find()->where(['property_id' => $propertyId])->one();
                        $propList->status = 0;
                        if (!$propList->save(false)) {
                            throw new \Exception('Exception');
                        }
                    }

                    Yii::$app->session->setFlash('noti-success', "Payment Successful!");
                    Yii::$app->session->setFlash('noti-amount', $txnAmount);
                    Yii::$app->session->setFlash('noti-paydesc', "Property Booked - " . Yii::$app->userdata->getPropertyNameById($propertyId));
                    Yii::$app->session->setFlash('noti-txndate', $txnDate);
                    Yii::$app->session->setFlash('noti-txnid', $txnRefNo);
                    $downloadJson = [];
                    $downloadJson['response'] = 'Payment Successful!';
                    $downloadJson['amount'] = $txnAmount;
                    $downloadJson['paydesc'] = "Property Booked - " . Yii::$app->userdata->getPropertyNameById($propertyId);
                    $downloadJson['txndate'] = $txnDate;
                    $downloadJson['txnid'] = $txnRefNo;
                    Yii::$app->session->setFlash('noti-download', json_encode($downloadJson));

                    $transaction->commit();
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->getSession()->setFlash(
                            'noti-error', "{$e->getMessage()}"
                    );
                }
            } else {
                Yii::$app->session->setFlash('noti-error', $resDesc);
            }
            
            if (!empty(Yii::$app->session->get('app_redirect_url'))) {
                return $this->redirect(Yii::$app->session->get('app_redirect_url'));
            } else {
                $this->redirect(['./']);
            }
        } else {
            throw new \yii\web\ForbiddenHttpException('You are not allowed to directly access this page.');
        }
    }
    
    public function actionPayrent () {
        $this->layout = false;
        $referUrl = Url::home(true) . 'paytm/payrent';
        $returnUrl = Url::home(true)."paytm/rentpaymentresponse";
        
        if (!empty(Yii::$app->request->post('order_id'))) {
            $transaction = Yii::$app->db->beginTransaction();
            
            try {
                $pgiModel = \app\models\PaymentGateway::find()->where(['order_id' => Yii::$app->request->post('order_id')])->one();
                
                if (count($pgiModel) == 0) {
                    throw new \Exception('Exception');
                }
                
                if ($pgiModel->txn_for != 2) {
                    throw new \Exception('Incorrect parameters');
                }
                
                $tpId = $pgiModel->tp_id;
                $propertyId = $pgiModel->property_id;
                $merchantId = Yii::$app->params['paytm_merchant_id'];
                $orderId = Yii::$app->userdata->generateOrderId(Yii::$app->userdata->id);
                $customerId = $pgiModel->customer_id;
                $totalamount = $pgiModel->amount;
                $txnAmount = $totalamount;
                $transactionURL = (Yii::$app->params['paytm_env'] == 'dev') ? Yii::$app->params['paytm_txn_api_dev'] : Yii::$app->params['paytm_txn_api_prod'] ;
                
                $paytmParams = array();
                $paytmParams["MID"] = $merchantId;
                $paytmParams["ORDER_ID"] = $orderId;
                $paytmParams["CUST_ID"] = $customerId;
                $paytmParams["MOBILE_NO"] = Yii::$app->userdata->getPhoneNumber(Yii::$app->user->id);
                $paytmParams["EMAIL"] = Yii::$app->userdata->getEmailById(Yii::$app->user->id);
                $paytmParams["CHANNEL_ID"] = Yii::$app->params['paytm_channel_id'];
                $paytmParams["TXN_AMOUNT"] = $txnAmount;
                $paytmParams["WEBSITE"] = Yii::$app->params['paytm_merchant_website'];
                $paytmParams["INDUSTRY_TYPE_ID"] = Yii::$app->params['paytm_industry_type_id'];
                $paytmParams["CALLBACK_URL"] = $returnUrl;
                $paytmParams["PAYTM_CHECKSUM"] = Yii::$app->paytm->getChecksumFromArray($paytmParams, Yii::$app->params['paytm_merchant_key']);

                if (!empty(Yii::$app->request->post('app_redirect_url'))) {
                    Yii::$app->session->set('app_redirect_url', Yii::$app->request->post('app_redirect_url'));
                }
                
                $checksum = $paytmParams["PAYTM_CHECKSUM"];
                $msg = json_encode($paytmParams);
                unset($paytmParams["PAYTM_CHECKSUM"]);
                
                $model = new \app\models\PaymentGateway();
                $model->customer_id = $customerId;
                $model->order_id = $orderId;
                $model->tp_id = $tpId;
                $model->user_type = Yii::$app->userdata->getusertype();
                $model->user_id = Yii::$app->userdata->id;
                $model->property_id = $propertyId;
                $model->amount = $totalamount;
                $model->request_time = date('Y-m-d H:i:s');
                $model->merchant_id = $merchantId;
                $model->checksum = $msg;
                $model->refer_url = $referUrl;
                $model->txn_for = 2;
                if (!$model->save()) {
                    throw new \Exception('Exception');
                }

                $transaction->commit();
                
                return $this->render('paytm_pay', ['transactionURL' => $transactionURL, 'paytmParams' => $paytmParams, 'paytmChecksum' => $checksum]);
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->getSession()->setFlash('success', "{$e->getMessage()}");
            }
            
            exit;
        }
        
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $tpId = Yii::$app->getSecurity()->decryptByKey(base64_decode(Yii::$app->request->post('TenantPayments')['id']), Yii::$app->params['hash_key']);
                $tpModel = \app\models\TenantPayments::find()->where(['id' => $tpId])->one();
                if (empty($tpModel)) {
                    throw new \Exception('Unable to locate object, something went wrong at server.');
                }
                
                $propertyId = $tpModel->property_id;
                $merchantId = Yii::$app->params['paytm_merchant_id'];
                $orderId = Yii::$app->userdata->generateOrderId(Yii::$app->userdata->id);
                $customerId = Yii::$app->userdata->generateCustomerId(Yii::$app->userdata->id);
                $penaltyAmount = Yii::$app->userdata->calculatePenalty($tpId);
                $totalamount = $tpModel->total_amount + $penaltyAmount;
                $txnAmount = $totalamount;
                $transactionURL = (Yii::$app->params['paytm_env'] == 'dev') ? Yii::$app->params['paytm_txn_api_dev'] : Yii::$app->params['paytm_txn_api_prod'] ;
                
                $paytmParams = array();
                $paytmParams["MID"] = $merchantId;
                $paytmParams["ORDER_ID"] = $orderId;
                $paytmParams["CUST_ID"] = $customerId;
                $paytmParams["MOBILE_NO"] = Yii::$app->userdata->getPhoneNumber(Yii::$app->user->id);
                $paytmParams["EMAIL"] = Yii::$app->userdata->getEmailById(Yii::$app->user->id);
                $paytmParams["CHANNEL_ID"] = Yii::$app->params['paytm_channel_id'];
                $paytmParams["TXN_AMOUNT"] = $txnAmount;
                $paytmParams["WEBSITE"] = Yii::$app->params['paytm_merchant_website'];
                $paytmParams["INDUSTRY_TYPE_ID"] = Yii::$app->params['paytm_industry_type_id'];
                $paytmParams["CALLBACK_URL"] = $returnUrl;
                $paytmParams["PAYTM_CHECKSUM"] = Yii::$app->paytm->getChecksumFromArray($paytmParams, Yii::$app->params['paytm_merchant_key']);

                if (!empty(Yii::$app->request->post('app_redirect_url'))) {
                    Yii::$app->session->set('app_redirect_url', Yii::$app->request->post('app_redirect_url'));
                }
                
                $checksum = $paytmParams["PAYTM_CHECKSUM"];
                $msg = json_encode($paytmParams);
                unset($paytmParams["PAYTM_CHECKSUM"]);
                
                $model = new \app\models\PaymentGateway();
                $model->customer_id = $customerId;
                $model->order_id = $orderId;
                $model->tp_id = $tpId;
                $model->user_type = Yii::$app->userdata->getusertype();
                $model->user_id = Yii::$app->userdata->id;
                $model->property_id = $propertyId;
                $model->amount = $totalamount;
                $model->request_time = date('Y-m-d H:i:s');
                $model->merchant_id = $merchantId;
                $model->checksum = $msg;
                $model->refer_url = $referUrl;
                $model->txn_for = 2;
                if (!$model->save()) {
                    throw new \Exception('Exception');
                }

                $transaction->commit();
                
                return $this->render('paytm_pay', ['transactionURL' => $transactionURL, 'paytmParams' => $paytmParams, 'paytmChecksum' => $checksum]);
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->getSession()->setFlash('success', "{$e->getMessage()}");
            }
        } else {
            throw new \yii\web\ForbiddenHttpException('You are not allowed to directly access this page.');
        }
        
    }
    
    public function actionPayrentpaytmnetbanking () {
        $this->layout = false;
        $referUrl = Url::home(true) . 'paytm/payrentpaytmnetbanking';
        $returnUrl = Url::home(true)."paytm/rentpaymentresponse";
        
        if (!empty(Yii::$app->request->post('order_id'))) {
            $transaction = Yii::$app->db->beginTransaction();
            
            try {
                $pgiModel = \app\models\PaymentGateway::find()->where(['order_id' => Yii::$app->request->post('order_id')])->one();
                
                if (count($pgiModel) == 0) {
                    throw new \Exception('Exception');
                }
                
                if ($pgiModel->txn_for != 2) {
                    throw new \Exception('Incorrect parameters');
                }
                
                $tpId = $pgiModel->tp_id;
                $propertyId = $pgiModel->property_id;
                $merchantId = Yii::$app->params['paytm_netbanking_merchant_id'];
                $orderId = Yii::$app->userdata->generateOrderId(Yii::$app->userdata->id);
                $customerId = $pgiModel->customer_id;
                $totalamount = $pgiModel->amount;
                $txnAmount = $totalamount;
                $transactionURL = (Yii::$app->params['paytm_netbanking_env'] == 'dev') ? Yii::$app->params['paytm_netbanking_txn_api_dev'] : Yii::$app->params['paytm_netbanking_txn_api_prod'] ;
                
                $paytmParams = array();
                $paytmParams["MID"] = $merchantId;
                $paytmParams["ORDER_ID"] = $orderId;
                $paytmParams["CUST_ID"] = $customerId;
                $paytmParams["MOBILE_NO"] = Yii::$app->userdata->getPhoneNumber(Yii::$app->user->id);
                $paytmParams["EMAIL"] = Yii::$app->userdata->getEmailById(Yii::$app->user->id);
                $paytmParams["CHANNEL_ID"] = Yii::$app->params['paytm_netbanking_channel_id'];
                $paytmParams["TXN_AMOUNT"] = $txnAmount;
                $paytmParams["WEBSITE"] = Yii::$app->params['paytm_netbanking_merchant_website'];
                $paytmParams["INDUSTRY_TYPE_ID"] = Yii::$app->params['paytm_netbanking_industry_type_id'];
                $paytmParams["CALLBACK_URL"] = $returnUrl;
                $paytmParams["PAYTM_CHECKSUM"] = Yii::$app->paytm->getChecksumFromArray($paytmParams, Yii::$app->params['paytm_netbanking_merchant_key']);

                if (!empty(Yii::$app->request->post('app_redirect_url'))) {
                    Yii::$app->session->set('app_redirect_url', Yii::$app->request->post('app_redirect_url'));
                }
                
                $checksum = $paytmParams["PAYTM_CHECKSUM"];
                $msg = json_encode($paytmParams);
                unset($paytmParams["PAYTM_CHECKSUM"]);
                
                $model = new \app\models\PaymentGateway();
                $model->customer_id = $customerId;
                $model->order_id = $orderId;
                $model->tp_id = $tpId;
                $model->user_type = Yii::$app->userdata->getusertype();
                $model->user_id = Yii::$app->userdata->id;
                $model->property_id = $propertyId;
                $model->amount = $totalamount;
                $model->request_time = date('Y-m-d H:i:s');
                $model->merchant_id = $merchantId;
                $model->checksum = $msg;
                $model->refer_url = $referUrl;
                $model->txn_for = 2;
                if (!$model->save()) {
                    throw new \Exception('Exception');
                }

                $transaction->commit();
                
                return $this->render('paytm_pay', ['transactionURL' => $transactionURL, 'paytmParams' => $paytmParams, 'paytmChecksum' => $checksum]);
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->getSession()->setFlash('success', "{$e->getMessage()}");
            }
            
            exit;
        }
        
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $tpId = Yii::$app->getSecurity()->decryptByKey(base64_decode(Yii::$app->request->post('TenantPayments')['id']), Yii::$app->params['hash_key']);
                $tpModel = \app\models\TenantPayments::find()->where(['id' => $tpId])->one();
                if (empty($tpModel)) {
                    throw new \Exception('Unable to locate object, something went wrong at server.');
                }
                
                $propertyId = $tpModel->property_id;
                $merchantId = Yii::$app->params['paytm_netbanking_merchant_id'];
                $orderId = Yii::$app->userdata->generateOrderId(Yii::$app->userdata->id);
                $customerId = Yii::$app->userdata->generateCustomerId(Yii::$app->userdata->id);
                $penaltyAmount = Yii::$app->userdata->calculatePenalty($tpId);
                $totalamount = $tpModel->total_amount + $penaltyAmount;
                $txnAmount = $totalamount;
                $transactionURL = (Yii::$app->params['paytm_netbanking_env'] == 'dev') ? Yii::$app->params['paytm_netbanking_txn_api_dev'] : Yii::$app->params['paytm_netbanking_txn_api_prod'] ;
                
                $paytmParams = array();
                $paytmParams["MID"] = $merchantId;
                $paytmParams["ORDER_ID"] = $orderId;
                $paytmParams["CUST_ID"] = $customerId;
                $paytmParams["MOBILE_NO"] = Yii::$app->userdata->getPhoneNumber(Yii::$app->user->id);
                $paytmParams["EMAIL"] = Yii::$app->userdata->getEmailById(Yii::$app->user->id);
                $paytmParams["CHANNEL_ID"] = Yii::$app->params['paytm_netbanking_channel_id'];
                $paytmParams["TXN_AMOUNT"] = $txnAmount;
                $paytmParams["WEBSITE"] = Yii::$app->params['paytm_netbanking_merchant_website'];
                $paytmParams["INDUSTRY_TYPE_ID"] = Yii::$app->params['paytm_netbanking_industry_type_id'];
                $paytmParams["CALLBACK_URL"] = $returnUrl;
                $paytmParams["PAYTM_CHECKSUM"] = Yii::$app->paytm->getChecksumFromArray($paytmParams, Yii::$app->params['paytm_netbanking_merchant_key']);

                if (!empty(Yii::$app->request->post('app_redirect_url'))) {
                    Yii::$app->session->set('app_redirect_url', Yii::$app->request->post('app_redirect_url'));
                }
                
                $checksum = $paytmParams["PAYTM_CHECKSUM"];
                $msg = json_encode($paytmParams);
                unset($paytmParams["PAYTM_CHECKSUM"]);
                
                $model = new \app\models\PaymentGateway();
                $model->customer_id = $customerId;
                $model->order_id = $orderId;
                $model->tp_id = $tpId;
                $model->user_type = Yii::$app->userdata->getusertype();
                $model->user_id = Yii::$app->userdata->id;
                $model->property_id = $propertyId;
                $model->amount = $totalamount;
                $model->request_time = date('Y-m-d H:i:s');
                $model->merchant_id = $merchantId;
                $model->checksum = $msg;
                $model->refer_url = $referUrl;
                $model->txn_for = 2;
                if (!$model->save()) {
                    throw new \Exception('Exception');
                }

                $transaction->commit();
                
                return $this->render('paytm_pay', ['transactionURL' => $transactionURL, 'paytmParams' => $paytmParams, 'paytmChecksum' => $checksum]);
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->getSession()->setFlash('success', "{$e->getMessage()}");
            }
        } else {
            throw new \yii\web\ForbiddenHttpException('You are not allowed to directly access this page.');
        }
        
    }
    
    public function actionRentpaymentresponse () {
        if (Yii::$app->request->post()) {
            $response = Yii::$app->request->post();
            $orderId = @$response['ORDERID'];
            $merchantId = @$response['MID'];
            $txnRefNo = @$response['TXNID'];
            $bankRefNo = @$response['BANKTXNID'];
            $txnAmount = @$response['TXNAMOUNT'];
            $bankId = @$response['BANKNAME'];
            $currencyName = @$response['CURRENCY'];
            $txnDate = @$response['TXNDATE'];
            $authStatus = @$response['STATUS'];
            $resStatus = @$response['RESPCODE'];
            $resDesc = @$response['RESPMSG'];
            $paymentMode = @$response['PAYMENTMODE'];
            $gatewayName = @$response['GATEWAYNAME'];
            $checkSum = @$response['CHECKSUMHASH'];

            $pgModel = \app\models\PaymentGateway::find()->where(['order_id' => $orderId])->one();
            $pgiReference = $pgModel->id;
            $customerId = $pgModel->customer_id;
            $tpId = $pgModel->tp_id;
            if ($authStatus == 'TXN_SUCCESS') {
                $pgModel->payment_status = 1;
            } else {
                $pgModel->payment_status = 2;
            }
            
            $pgModel->response_time = date('Y-m-d H:i:s');
            $pgModel->txn_reference_no = $txnRefNo;
            $pgModel->bank_reference_no = $bankRefNo;
            $pgModel->bank_id = $bankId;
            $pgModel->currency_name = $currencyName;
            $pgModel->auth_status = $authStatus;
            $pgModel->description = $resDesc;
            $pgModel->payment_mode = $paymentMode;
            $pgModel->gateway_name = $gatewayName;
            $pgModel->save();

            Yii::$app->session->setFlash('noti-retry', $orderId);

            if ($authStatus == 'TXN_SUCCESS') {
                $userId = $pgModel->user_id;
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $tenantPayment = \app\models\TenantPayments::find()->where(['id' => $tpId])->one();
                    $tenantPayment->amount_paid = (double) $txnAmount;
                    $tenantPayment->payment_date = date('Y-m-d');
                    $tenantPayment->pgi_reference = $pgiReference;
                    $tenantPayment->payment_status = 1;
                    $tenantPayment->payment_mode = 2;
                    $tenantPayment->penalty_amount = Yii::$app->userdata->calculatePenalty($tpId);
                    $tenantPayment->updated_date = date('Y-m-d H:i:s');
                    
                    if (!$tenantPayment->save(false)) {
                        throw new \Exception('Exception');
                    }
                    
                    $propertyIncomeModel = new \app\models\PropertyIncome();
                    $propertyIncomeModel->property_id = $pgModel->property_id;
                    $propertyIncomeModel->customer_id = $pgModel->user_id;
                    $propertyIncomeModel->customer_name = Yii::$app->userdata->getFullNameById($pgModel->user_id);
                    $propertyIncomeModel->income_type = 'RNT';
                    $propertyIncomeModel->income_type = 'RNT';
                    $propertyIncomeModel->total_income_amount = (double) $txnAmount;
                    $propertyIncomeModel->transaction_num = $txnRefNo;
                    $propertyIncomeModel->transaction_mode = 2;
                    $propertyIncomeModel->paid_on = date('Y-m-d', strtotime($txnDate));
                    $propertyIncomeModel->received_by_entity = 'Easyleases';
                    $propertyIncomeModel->month = date('Y-m-01');
                    $opsProfile = \app\models\OperationsProfile::find()->where(['role_value' => 'ELHQ'])->one();
                    $propertyIncomeModel->approved_by = @$opsProfile->operations_id;
                    $propertyIncomeModel->created_by = @$opsProfile->operations_id;
                    $propertyIncomeModel->created_date = date('Y-m-d');
                    
                    if (!$propertyIncomeModel->save(false)) {
                        throw new \Exception('Exception');
                    }
                    
                    Yii::$app->session->setFlash('noti-amount', $txnAmount);
                    Yii::$app->session->setFlash('noti-paydesc', Yii::$app->userdata->getTenantPaymentDescByTpId($tpId));
                    Yii::$app->session->setFlash('noti-txndate', $txnDate);
                    Yii::$app->session->setFlash('noti-txnid', $txnRefNo);
                    $downloadJson = [];
                    $downloadJson['response'] = 'Payment Successful!';
                    $downloadJson['amount'] = $txnAmount;
                    $downloadJson['paydesc'] = Yii::$app->userdata->getTenantPaymentDescByTpId($tpId);
                    $downloadJson['txndate'] = $txnDate;
                    $downloadJson['txnid'] = $txnRefNo;
                    Yii::$app->session->setFlash('noti-download', json_encode($downloadJson));
                    $transaction->commit();
                    Yii::$app->session->setFlash('noti-success', "Payment Successful!");
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->getSession()->setFlash('noti-error', "{$e->getMessage()}");
                }
            } else {
                Yii::$app->session->setFlash('noti-error', $resDesc);
            }
            
            if (!empty(Yii::$app->session->get('app_redirect_url'))) {
                return $this->redirect(Yii::$app->session->get('app_redirect_url'));
            } else {
                $this->redirect(['./']);
            }
        } else {
            throw new \yii\web\ForbiddenHttpException('You are not allowed to directly access this page.');
        }
    }
    
    public function actionPayrentfromapp () {
        $this->layout = false;
        
        if (empty(Yii::$app->request->post('x-api-key')) || empty(Yii::$app->request->post('tpRowId'))) {
            return $this->redirect(Url::home(true).'api/v1/tenant/rentpaymentfailed');
        }
        
        $userModel = Yii::$app->restbasicauth->getUserModelFromToken(Yii::$app->request->post('x-api-key'));
        if (count($userModel) == 0) {
            return $this->redirect(Url::home(true).'api/v1/tenant/rentpaymentfailed');
        }
        
        $referUrl = Url::home(true) . 'paytm/payrentfromapp';
        $returnUrl = Url::home(true)."paytm/rentpaymentresponseapp";
        
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $tpId = Yii::$app->getSecurity()->decryptByKey(base64_decode(Yii::$app->request->post('tpRowId')), Yii::$app->params['hash_key']);
            $tpModel = \app\models\TenantPayments::find()->where(['id' => $tpId])->one();
            if (empty($tpModel)) {
                throw new \Exception('Unable to locate object, something went wrong at server.');
            }

            $propertyId = $tpModel->property_id;
            $merchantId = Yii::$app->params['paytm_merchant_id'];
            $orderId = Yii::$app->userdata->generateOrderId($userModel->id);
            $customerId = Yii::$app->userdata->generateCustomerId($userModel->id);
            $penaltyAmount = Yii::$app->userdata->calculatePenalty($tpId);
            $totalamount = $tpModel->total_amount + $penaltyAmount;
            $txnAmount = $totalamount;
            $transactionURL = (Yii::$app->params['paytm_env'] == 'dev') ? Yii::$app->params['paytm_txn_api_dev'] : Yii::$app->params['paytm_txn_api_prod'] ;

            $paytmParams = array();
            $paytmParams["MID"] = $merchantId;
            $paytmParams["ORDER_ID"] = $orderId;
            $paytmParams["CUST_ID"] = $customerId;
            $paytmParams["MOBILE_NO"] = Yii::$app->userdata->getPhoneNumber($userModel->id);
            $paytmParams["EMAIL"] = Yii::$app->userdata->getEmailById($userModel->id);
            $paytmParams["CHANNEL_ID"] = Yii::$app->params['paytm_channel_id'];
            $paytmParams["TXN_AMOUNT"] = $txnAmount;
            $paytmParams["WEBSITE"] = Yii::$app->params['paytm_merchant_website'];
            $paytmParams["INDUSTRY_TYPE_ID"] = Yii::$app->params['paytm_industry_type_id'];
            $paytmParams["CALLBACK_URL"] = $returnUrl;
            $paytmParams["PAYTM_CHECKSUM"] = Yii::$app->paytm->getChecksumFromArray($paytmParams, Yii::$app->params['paytm_merchant_key']);

            $checksum = $paytmParams["PAYTM_CHECKSUM"];
            $msg = json_encode($paytmParams);
            unset($paytmParams["PAYTM_CHECKSUM"]);

            $model = new \app\models\PaymentGateway();
            $model->customer_id = $customerId;
            $model->order_id = $orderId;
            $model->tp_id = $tpId;
            $model->user_type = $userModel->user_type;
            $model->user_id = $userModel->id;
            $model->property_id = $propertyId;
            $model->amount = $totalamount;
            $model->request_time = date('Y-m-d H:i:s');
            $model->merchant_id = $merchantId;
            $model->checksum = $msg;
            $model->refer_url = $referUrl;
            $model->txn_for = 2;
            if (!$model->save()) {
                throw new \Exception('Exception');
            }
            
            $transaction->commit();

            return $this->render('paytm_pay', ['transactionURL' => $transactionURL, 'paytmParams' => $paytmParams, 'paytmChecksum' => $checksum]);
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->redirect(Url::home(true).'api/v1/tenant/rentpaymentfailed');
        }
    }
    
    public function actionPayrentfrompaytmnetbankingapp () {
        $this->layout = false;
        
        if (empty(Yii::$app->request->post('x-api-key')) || empty(Yii::$app->request->post('tpRowId'))) {
            return $this->redirect(Url::home(true).'api/v1/tenant/rentpaymentfailed');
        }
        
        $userModel = Yii::$app->restbasicauth->getUserModelFromToken(Yii::$app->request->post('x-api-key'));
        if (count($userModel) == 0) {
            return $this->redirect(Url::home(true).'api/v1/tenant/rentpaymentfailed');
        }
        
        $referUrl = Url::home(true) . 'paytm/payrentfrompaytmnetbankingapp';
        $returnUrl = Url::home(true)."paytm/rentpaymentresponseapp";
        
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $tpId = Yii::$app->getSecurity()->decryptByKey(base64_decode(Yii::$app->request->post('tpRowId')), Yii::$app->params['hash_key']);
            $tpModel = \app\models\TenantPayments::find()->where(['id' => $tpId])->one();
            if (empty($tpModel)) {
                throw new \Exception('Unable to locate object, something went wrong at server.');
            }

            $propertyId = $tpModel->property_id;
            $merchantId = Yii::$app->params['paytm_netbanking_merchant_id'];
            $orderId = Yii::$app->userdata->generateOrderId($userModel->id);
            $customerId = Yii::$app->userdata->generateCustomerId($userModel->id);
            $penaltyAmount = Yii::$app->userdata->calculatePenalty($tpId);
            $totalamount = $tpModel->total_amount + $penaltyAmount;
            $txnAmount = $totalamount;
            $transactionURL = (Yii::$app->params['paytm_netbanking_env'] == 'dev') ? Yii::$app->params['paytm_netbanking_txn_api_dev'] : Yii::$app->params['paytm_netbanking_txn_api_prod'] ;

            $paytmParams = array();
            $paytmParams["MID"] = $merchantId;
            $paytmParams["ORDER_ID"] = $orderId;
            $paytmParams["CUST_ID"] = $customerId;
            $paytmParams["MOBILE_NO"] = Yii::$app->userdata->getPhoneNumber($userModel->id);
            $paytmParams["EMAIL"] = Yii::$app->userdata->getEmailById($userModel->id);
            $paytmParams["CHANNEL_ID"] = Yii::$app->params['paytm_netbanking_channel_id'];
            $paytmParams["TXN_AMOUNT"] = $txnAmount;
            $paytmParams["WEBSITE"] = Yii::$app->params['paytm_netbanking_merchant_website'];
            $paytmParams["INDUSTRY_TYPE_ID"] = Yii::$app->params['paytm_netbanking_industry_type_id'];
            $paytmParams["CALLBACK_URL"] = $returnUrl;
            $paytmParams["PAYTM_CHECKSUM"] = Yii::$app->paytm->getChecksumFromArray($paytmParams, Yii::$app->params['paytm_netbanking_merchant_key']);

            $checksum = $paytmParams["PAYTM_CHECKSUM"];
            $msg = json_encode($paytmParams);
            unset($paytmParams["PAYTM_CHECKSUM"]);

            $model = new \app\models\PaymentGateway();
            $model->customer_id = $customerId;
            $model->order_id = $orderId;
            $model->tp_id = $tpId;
            $model->user_type = $userModel->user_type;
            $model->user_id = $userModel->id;
            $model->property_id = $propertyId;
            $model->amount = $totalamount;
            $model->request_time = date('Y-m-d H:i:s');
            $model->merchant_id = $merchantId;
            $model->checksum = $msg;
            $model->refer_url = $referUrl;
            $model->txn_for = 2;
            if (!$model->save()) {
                throw new \Exception('Exception');
            }
            
            $transaction->commit();

            return $this->render('paytm_pay', ['transactionURL' => $transactionURL, 'paytmParams' => $paytmParams, 'paytmChecksum' => $checksum]);
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->redirect(Url::home(true).'api/v1/tenant/rentpaymentfailed');
        }
    }
    
    public function actionRentpaymentresponseapp () {
        if (Yii::$app->request->post()) {
            $response = Yii::$app->request->post();
            $orderId = @$response['ORDERID'];
            $merchantId = @$response['MID'];
            $txnRefNo = @$response['TXNID'];
            $bankRefNo = @$response['BANKTXNID'];
            $txnAmount = @$response['TXNAMOUNT'];
            $bankId = @$response['BANKNAME'];
            $currencyName = @$response['CURRENCY'];
            $txnDate = @$response['TXNDATE'];
            $authStatus = @$response['STATUS'];
            $resStatus = @$response['RESPCODE'];
            $resDesc = @$response['RESPMSG'];
            $paymentMode = @$response['PAYMENTMODE'];
            $gatewayName = @$response['GATEWAYNAME'];
            $checkSum = @$response['CHECKSUMHASH'];

            $pgModel = \app\models\PaymentGateway::find()->where(['order_id' => $orderId])->one();
            $pgiReference = $pgModel->id;
            $customerId = $pgModel->customer_id;
            $tpId = $pgModel->tp_id;
            if ($authStatus == 'TXN_SUCCESS') {
                $pgModel->payment_status = 1;
            } else {
                $pgModel->payment_status = 2;
            }
            
            $pgModel->response_time = date('Y-m-d H:i:s');
            $pgModel->txn_reference_no = $txnRefNo;
            $pgModel->bank_reference_no = $bankRefNo;
            $pgModel->bank_id = $bankId;
            $pgModel->currency_name = $currencyName;
            $pgModel->auth_status = $authStatus;
            $pgModel->description = $resDesc;
            $pgModel->payment_mode = $paymentMode;
            $pgModel->gateway_name = $gatewayName;
            $pgModel->save();

            if ($authStatus == 'TXN_SUCCESS') {
                $userId = $pgModel->user_id;
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $tenantPayment = \app\models\TenantPayments::find()->where(['id' => $tpId])->one();
                    $tenantPayment->amount_paid = (double) $txnAmount;
                    $tenantPayment->payment_date = date('Y-m-d');
                    $tenantPayment->pgi_reference = $pgiReference;
                    $tenantPayment->payment_status = 1;
                    $tenantPayment->payment_mode = 2;
                    $tenantPayment->penalty_amount = Yii::$app->userdata->calculatePenalty($tpId);
                    $tenantPayment->updated_date = date('Y-m-d H:i:s');
                    
                    if (!$tenantPayment->save(false)) {
                        throw new \Exception('Exception');
                    }
                    
                    $propertyIncomeModel = new \app\models\PropertyIncome();
                    $propertyIncomeModel->property_id = $pgModel->property_id;
                    $propertyIncomeModel->customer_id = $pgModel->user_id;
                    $propertyIncomeModel->customer_name = Yii::$app->userdata->getFullNameById($pgModel->user_id);
                    $propertyIncomeModel->income_type = 'RNT';
                    $propertyIncomeModel->income_type = 'RNT';
                    $propertyIncomeModel->total_income_amount = (double) $txnAmount;
                    $propertyIncomeModel->transaction_num = $txnRefNo;
                    $propertyIncomeModel->transaction_mode = 2;
                    $propertyIncomeModel->paid_on = date('Y-m-d', strtotime($txnDate));
                    $propertyIncomeModel->received_by_entity = 'Easyleases';
                    $propertyIncomeModel->month = date('Y-m-01');
                    $opsProfile = \app\models\OperationsProfile::find()->where(['role_value' => 'ELHQ'])->one();
                    $propertyIncomeModel->approved_by = @$opsProfile->operations_id;
                    $propertyIncomeModel->created_by = @$opsProfile->operations_id;
                    $propertyIncomeModel->created_date = date('Y-m-d');
                    
                    if (!$propertyIncomeModel->save(false)) {
                        throw new \Exception('Exception');
                    }
                    
                    $transaction->commit();
                    
                    $downloadJson = [];
                    $downloadJson['response'] = 'Payment Successful!';
                    $downloadJson['amount'] = $txnAmount;
                    $downloadJson['paydesc'] = Yii::$app->userdata->getTenantPaymentDescByTpId($tpId);
                    $downloadJson['txndate'] = $txnDate;
                    $downloadJson['txnid'] = $txnRefNo;
                    $receiptUrlData = base64_encode(json_encode($downloadJson));
                    
                    return $this->redirect(Url::home(true).'api/v1/tenant/rentpaymentsuccess?dr='.$receiptUrlData.'&i='.$tenantPayment->tenant_id);
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    return $this->redirect(Url::home(true).'api/v1/tenant/rentpaymentfailed');
                }
            } else {
                return $this->redirect(Url::home(true).'api/v1/tenant/rentpaymentfailed');
            }
            
        } else {
            return $this->redirect(Url::home(true).'api/v1/tenant/rentpaymentfailed');
        }
    }
    
    public function actionSendtenantpaymentmail ($tpID) {
        $tpModel = \app\models\TenantPayments::find()->where(['id' => $tpID])->andWhere(['payment_status' => 0])->one();
        if (count($tpModel) == 1) {
            $tenantData = [];
            $rowId = $tpModel->id;
            $tenantId = $tpModel->tenant_id;
            $tenantData['tenantName'] = Yii::$app->userdata->getFullNameById($tenantId);
            $tenantData['tenantPhone'] = Yii::$app->userdata->getPhoneNumberById($tenantId, Yii::$app->userdata->getUserTypeIdByUserId($tenantId));
            $tenantData['tenantEmail'] = Yii::$app->userdata->getEmailById($tenantId);
            $tenantData['propertyId'] = $tpModel->property_id;
            $tenantData['originalAmount'] = $tpModel->original_amount;
            $tenantData['dueDate'] = $tpModel->due_date;
            $tenantData['month'] = $tpModel->month;
            $tenantData['penaltyAmount'] = Yii::$app->userdata->calculatePenalty($tpID);
            $tenantData['totalAmount'] = $tpModel->original_amount + $tenantData['penaltyAmount'];
            
            $tenantData['data'] = base64_encode(Yii::$app->getSecurity()->encryptByKey($rowId, Yii::$app->params['hash_key']));
            //$tenantData['data'] = $rowId;
            
            $message = $this->renderPartial('paytm_invoice', ['model' => $tenantData]);
            $tinyUrl = Yii::$app->userdata->getTinyUrl(Url::home(true).'paytm/payrentfromweb?d='.$tenantData['data']);
            
            $smsMessage = "Your bill for month of ".date("My", strtotime($tpModel->due_date))." has been generated. "
                    . "Please click ".$tinyUrl." to make online payment";
            
            $emailStatus = Yii::$app->userdata->doMail($tenantData['tenantEmail'], 'Payment Due Alert From Easyleases.in', $message);
            //$emailStatus = Yii::$app->userdata->doMail('shobhit.singh@easyleases.in', 'Payment Due Alert From Easyleases.in', $message);
            //echo 'email:' . $tenantData['tenantEmail'] . ' - Status:' . $emailStatus . PHP_EOL;
            $smsStatus = Yii::$app->userdata->sendSms([$tenantData['tenantPhone']], $smsMessage);
            //$smsStatus = Yii::$app->userdata->sendSms(['9098326259'], $smsMessage);
            if ($emailStatus || $smsStatus) {
                echo 1;
            }
        } else {
            echo 0;
        }
    }
    
    public function actionPayrentfromweb () {
        $this->layout = false;
        
        if (empty(Yii::$app->request->get('d'))) {
            return $this->redirect(Url::home(true).'api/v1/tenant/rentpaymentfailed');
        }
        
        try {
            $tpId = Yii::$app->getSecurity()->decryptByKey(base64_decode(Yii::$app->request->get('d')), Yii::$app->params['hash_key']);
            //$tpId = Yii::$app->request->get('d');
            $tpModel = \app\models\TenantPayments::find()->where(['id' => $tpId])->andWhere(['payment_status' => 0])->one();
            if (empty($tpModel)) {
                throw new \Exception('Unable to locate object, something went wrong at server.');
            }
            
            $userModel = \app\models\Users::find()->where(['id' => $tpModel->tenant_id])->one();
            if (count($userModel) == 0) {
                return $this->redirect(Url::home(true).'api/v1/tenant/rentpaymentfailed');
            }
            
            $ch = curl_init();
            $timeout = 5;
            curl_setopt($ch,CURLOPT_URL, Url::home(true). 'api/v1/tenant/payrent/'.$tpId);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'APPID: '.Yii::$app->params['APPID'],
                'DeviceID: EASYLEASES-WEB',
                'Platform: WEB',
                'x-api-key: '.Yii::$app->restbasicauth->generateUserToken($userModel->login_id, $userModel->password)
            ));
            $data = curl_exec($ch);
            //$error = curl_error($ch);
            curl_close($ch);
            echo $data;
        } catch (\Exception $e) {
            return $this->redirect(Url::home(true).'api/v1/tenant/rentpaymentfailed');
        }
    }
    
    public function actionRentpaymentresponseweb () {
        if (Yii::$app->request->post()) {
            $response = Yii::$app->request->post();
            $orderId = @$response['ORDERID'];
            $merchantId = @$response['MID'];
            $txnRefNo = @$response['TXNID'];
            $bankRefNo = @$response['BANKTXNID'];
            $txnAmount = @$response['TXNAMOUNT'];
            $bankId = @$response['BANKNAME'];
            $currencyName = @$response['CURRENCY'];
            $txnDate = @$response['TXNDATE'];
            $authStatus = @$response['STATUS'];
            $resStatus = @$response['RESPCODE'];
            $resDesc = @$response['RESPMSG'];
            $paymentMode = @$response['PAYMENTMODE'];
            $gatewayName = @$response['GATEWAYNAME'];
            $checkSum = @$response['CHECKSUMHASH'];

            $pgModel = \app\models\PaymentGateway::find()->where(['order_id' => $orderId])->one();
            $pgiReference = $pgModel->id;
            $customerId = $pgModel->customer_id;
            $tpId = $pgModel->tp_id;
            if ($authStatus == 'TXN_SUCCESS') {
                $pgModel->payment_status = 1;
            } else {
                $pgModel->payment_status = 2;
            }
            
            $pgModel->response_time = date('Y-m-d H:i:s');
            $pgModel->txn_reference_no = $txnRefNo;
            $pgModel->bank_reference_no = $bankRefNo;
            $pgModel->bank_id = $bankId;
            $pgModel->currency_name = $currencyName;
            $pgModel->auth_status = $authStatus;
            $pgModel->description = $resDesc;
            $pgModel->payment_mode = $paymentMode;
            $pgModel->gateway_name = $gatewayName;
            $pgModel->save();

            if ($authStatus == 'TXN_SUCCESS') {
                $userId = $pgModel->user_id;
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $tenantPayment = \app\models\TenantPayments::find()->where(['id' => $tpId])->one();
                    $tenantPayment->amount_paid = (double) $txnAmount;
                    $tenantPayment->payment_date = date('Y-m-d');
                    $tenantPayment->pgi_reference = $pgiReference;
                    $tenantPayment->payment_status = 1;
                    $tenantPayment->payment_mode = 2;
                    $tenantPayment->penalty_amount = Yii::$app->userdata->calculatePenalty($tpId);
                    $tenantPayment->updated_date = date('Y-m-d H:i:s');
                    
                    if (!$tenantPayment->save(false)) {
                        throw new \Exception('Exception');
                    }
                    
                    $propertyIncomeModel = new \app\models\PropertyIncome();
                    $propertyIncomeModel->property_id = $pgModel->property_id;
                    $propertyIncomeModel->customer_id = $pgModel->user_id;
                    $propertyIncomeModel->customer_name = Yii::$app->userdata->getFullNameById($pgModel->user_id);
                    $propertyIncomeModel->income_type = 'RNT';
                    $propertyIncomeModel->income_type = 'RNT';
                    $propertyIncomeModel->total_income_amount = (double) $txnAmount;
                    $propertyIncomeModel->transaction_num = $txnRefNo;
                    $propertyIncomeModel->transaction_mode = 2;
                    $propertyIncomeModel->paid_on = date('Y-m-d', strtotime($txnDate));
                    $propertyIncomeModel->received_by_entity = 'Easyleases';
                    $propertyIncomeModel->month = date('Y-m-01');
                    $opsProfile = \app\models\OperationsProfile::find()->where(['role_value' => 'ELHQ'])->one();
                    $propertyIncomeModel->approved_by = @$opsProfile->operations_id;
                    $propertyIncomeModel->created_by = @$opsProfile->operations_id;
                    $propertyIncomeModel->created_date = date('Y-m-d');
                    
                    if (!$propertyIncomeModel->save(false)) {
                        throw new \Exception('Exception');
                    }
                    
                    $transaction->commit();
                    
                    $downloadJson = [];
                    $downloadJson['response'] = 'Payment Successful!';
                    $downloadJson['amount'] = $txnAmount;
                    $downloadJson['paydesc'] = Yii::$app->userdata->getTenantPaymentDescByTpId($tpId);
                    $downloadJson['txndate'] = $txnDate;
                    $downloadJson['txnid'] = $txnRefNo;
                    $receiptUrlData = base64_encode(json_encode($downloadJson));
                    
                    return $this->redirect(Url::home(true).'api/v1/tenant/rentpaymentsuccess?dr='.$receiptUrlData);
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    return $this->redirect(Url::home(true).'api/v1/tenant/rentpaymentfailed');
                }
            } else {
                return $this->redirect(Url::home(true).'api/v1/tenant/rentpaymentfailed');
            }
            
        } else {
            return $this->redirect(Url::home(true).'api/v1/tenant/rentpaymentfailed');
        }
    }
    
    public function actionPayrentnouth () {
        $this->layout = false;
        $referUrl = Url::home(true) . 'paytm/payrentnouth';
        $returnUrl = Url::home(true)."paytm/rentpaymentresponsenouth";
        $userID = Yii::$app->getSecurity()->decryptByKey(base64_decode(Yii::$app->request->post('tenant_id')), Yii::$app->params['hash_key']);
        if (!empty(Yii::$app->request->post('order_id'))) {
            $transaction = Yii::$app->db->beginTransaction();
            
            try {
                $pgiModel = \app\models\PaymentGateway::find()->where(['order_id' => Yii::$app->request->post('order_id')])->one();
                if (count($pgiModel) == 0) {
                    throw new \Exception('Exception');
                }
                
                if ($pgiModel->txn_for != 2) {
                    throw new \Exception('Incorrect parameters');
                }
                
                $tpId = $pgiModel->tp_id;
                $propertyId = $pgiModel->property_id;
                $merchantId = Yii::$app->params['paytm_merchant_id'];
                $orderId = Yii::$app->userdata->generateOrderId($userID);
                $customerId = $pgiModel->customer_id;
                $totalamount = $pgiModel->amount;
                $txnAmount = $totalamount;
                $transactionURL = (Yii::$app->params['paytm_env'] == 'dev') ? Yii::$app->params['paytm_txn_api_dev'] : Yii::$app->params['paytm_txn_api_prod'] ;
                
                $paytmParams = array();
                $paytmParams["MID"] = $merchantId;
                $paytmParams["ORDER_ID"] = $orderId;
                $paytmParams["CUST_ID"] = $customerId;
                $paytmParams["MOBILE_NO"] = Yii::$app->userdata->getPhoneNumber(Yii::$app->user->id);
                $paytmParams["EMAIL"] = Yii::$app->userdata->getEmailById(Yii::$app->user->id);
                $paytmParams["CHANNEL_ID"] = Yii::$app->params['paytm_channel_id'];
                $paytmParams["TXN_AMOUNT"] = $txnAmount;
                $paytmParams["WEBSITE"] = Yii::$app->params['paytm_merchant_website'];
                $paytmParams["INDUSTRY_TYPE_ID"] = Yii::$app->params['paytm_industry_type_id'];
                $paytmParams["CALLBACK_URL"] = $returnUrl;
                $paytmParams["PAYTM_CHECKSUM"] = Yii::$app->paytm->getChecksumFromArray($paytmParams, Yii::$app->params['paytm_merchant_key']);

                if (!empty(Yii::$app->request->post('app_redirect_url'))) {
                    Yii::$app->session->set('app_redirect_url', Yii::$app->request->post('app_redirect_url'));
                }
                
                $checksum = $paytmParams["PAYTM_CHECKSUM"];
                $msg = json_encode($paytmParams);
                unset($paytmParams["PAYTM_CHECKSUM"]);
                
                $model = new \app\models\PaymentGateway();
                $model->customer_id = $customerId;
                $model->order_id = $orderId;
                $model->tp_id = $tpId;
                $model->user_type = 3;
                $model->user_id = $userID;
                $model->property_id = $propertyId;
                $model->amount = $totalamount;
                $model->request_time = date('Y-m-d H:i:s');
                $model->merchant_id = $merchantId;
                $model->checksum = $msg;
                $model->refer_url = $referUrl;
                $model->txn_for = 2;
                if (!$model->save()) {
                    throw new \Exception('Exception');
                }

                $transaction->commit();
                
                return $this->render('paytm_pay', ['transactionURL' => $transactionURL, 'paytmParams' => $paytmParams, 'paytmChecksum' => $checksum]);
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->getSession()->setFlash('success', "{$e->getMessage()}");
            }
            
            exit;
        }
        
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $tpId = Yii::$app->getSecurity()->decryptByKey(base64_decode(Yii::$app->request->post('TenantPayments')['id']), Yii::$app->params['hash_key']);
                $tpModel = \app\models\TenantPayments::find()->where(['id' => $tpId])->one();
                if (empty($tpModel)) {
                    throw new \Exception('Unable to locate object, something went wrong at server.');
                }
                
                $propertyId = $tpModel->property_id;
                $merchantId = Yii::$app->params['paytm_merchant_id'];
                $orderId = Yii::$app->userdata->generateOrderId($userID);
                $customerId = Yii::$app->userdata->generateCustomerId($userID);
                $penaltyAmount = Yii::$app->userdata->calculatePenalty($tpId);
                $totalamount = $tpModel->total_amount + $penaltyAmount;
                $txnAmount = $totalamount;
                $transactionURL = (Yii::$app->params['paytm_env'] == 'dev') ? Yii::$app->params['paytm_txn_api_dev'] : Yii::$app->params['paytm_txn_api_prod'] ;
                
                $paytmParams = array();
                $paytmParams["MID"] = $merchantId;
                $paytmParams["ORDER_ID"] = $orderId;
                $paytmParams["CUST_ID"] = $customerId;
                $paytmParams["MOBILE_NO"] = Yii::$app->userdata->getPhoneNumber(Yii::$app->user->id);
                $paytmParams["EMAIL"] = Yii::$app->userdata->getEmailById(Yii::$app->user->id);
                $paytmParams["CHANNEL_ID"] = Yii::$app->params['paytm_channel_id'];
                $paytmParams["TXN_AMOUNT"] = $txnAmount;
                $paytmParams["WEBSITE"] = Yii::$app->params['paytm_merchant_website'];
                $paytmParams["INDUSTRY_TYPE_ID"] = Yii::$app->params['paytm_industry_type_id'];
                $paytmParams["CALLBACK_URL"] = $returnUrl;
                $paytmParams["PAYTM_CHECKSUM"] = Yii::$app->paytm->getChecksumFromArray($paytmParams, Yii::$app->params['paytm_merchant_key']);

                if (!empty(Yii::$app->request->post('app_redirect_url'))) {
                    Yii::$app->session->set('app_redirect_url', Yii::$app->request->post('app_redirect_url'));
                }
                
                $checksum = $paytmParams["PAYTM_CHECKSUM"];
                $msg = json_encode($paytmParams);
                unset($paytmParams["PAYTM_CHECKSUM"]);
                
                $model = new \app\models\PaymentGateway();
                $model->customer_id = $customerId;
                $model->order_id = $orderId;
                $model->tp_id = $tpId;
                $model->user_type = 3;
                $model->user_id = $userID;
                $model->property_id = $propertyId;
                $model->amount = $totalamount;
                $model->request_time = date('Y-m-d H:i:s');
                $model->merchant_id = $merchantId;
                $model->checksum = $msg;
                $model->refer_url = $referUrl;
                $model->txn_for = 2;
                if (!$model->save()) {
                    throw new \Exception('Exception');
                }

                $transaction->commit();
                
                return $this->render('paytm_pay', ['transactionURL' => $transactionURL, 'paytmParams' => $paytmParams, 'paytmChecksum' => $checksum]);
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->getSession()->setFlash('success', "{$e->getMessage()}");
            }
        } else {
            throw new \yii\web\ForbiddenHttpException('You are not allowed to directly access this page.');
        }
    }
    
    public function actionRentpaymentresponsenouth () {
        if (Yii::$app->request->post()) {
            $response = Yii::$app->request->post();
            $orderId = @$response['ORDERID'];
            $merchantId = @$response['MID'];
            $txnRefNo = @$response['TXNID'];
            $bankRefNo = @$response['BANKTXNID'];
            $txnAmount = @$response['TXNAMOUNT'];
            $bankId = @$response['BANKNAME'];
            $currencyName = @$response['CURRENCY'];
            $txnDate = @$response['TXNDATE'];
            $authStatus = @$response['STATUS'];
            $resStatus = @$response['RESPCODE'];
            $resDesc = @$response['RESPMSG'];
            $paymentMode = @$response['PAYMENTMODE'];
            $gatewayName = @$response['GATEWAYNAME'];
            $checkSum = @$response['CHECKSUMHASH'];

            $pgModel = \app\models\PaymentGateway::find()->where(['order_id' => $orderId])->one();
            $pgiReference = $pgModel->id;
            $customerId = $pgModel->customer_id;
            $tpId = $pgModel->tp_id;
            if ($authStatus == 'TXN_SUCCESS') {
                $pgModel->payment_status = 1;
            } else {
                $pgModel->payment_status = 2;
            }
            
            $pgModel->response_time = date('Y-m-d H:i:s');
            $pgModel->txn_reference_no = $txnRefNo;
            $pgModel->bank_reference_no = $bankRefNo;
            $pgModel->bank_id = $bankId;
            $pgModel->currency_name = $currencyName;
            $pgModel->auth_status = $authStatus;
            $pgModel->description = $resDesc;
            $pgModel->payment_mode = $paymentMode;
            $pgModel->gateway_name = $gatewayName;
            $pgModel->save();

            Yii::$app->session->setFlash('noti-retry', $orderId);

            if ($authStatus == 'TXN_SUCCESS') {
                $userId = $pgModel->user_id;
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $tenantPayment = \app\models\TenantPayments::find()->where(['id' => $tpId])->one();
                    $tenantPayment->amount_paid = (double) $txnAmount;
                    $tenantPayment->payment_date = date('Y-m-d');
                    $tenantPayment->pgi_reference = $pgiReference;
                    $tenantPayment->payment_status = 1;
                    $tenantPayment->payment_mode = 2;
                    $tenantPayment->penalty_amount = Yii::$app->userdata->calculatePenalty($tpId);
                    $tenantPayment->updated_date = date('Y-m-d H:i:s');
                    
                    if (!$tenantPayment->save(false)) {
                        throw new \Exception('Exception');
                    }
                    
                    $propertyIncomeModel = new \app\models\PropertyIncome();
                    $propertyIncomeModel->property_id = $pgModel->property_id;
                    $propertyIncomeModel->customer_id = $pgModel->user_id;
                    $propertyIncomeModel->customer_name = Yii::$app->userdata->getFullNameById($pgModel->user_id);
                    $propertyIncomeModel->income_type = 'RNT';
                    $propertyIncomeModel->income_type = 'RNT';
                    $propertyIncomeModel->total_income_amount = (double) $txnAmount;
                    $propertyIncomeModel->transaction_num = $txnRefNo;
                    $propertyIncomeModel->transaction_mode = 2;
                    $propertyIncomeModel->paid_on = date('Y-m-d', strtotime($txnDate));
                    $propertyIncomeModel->received_by_entity = 'Easyleases';
                    $propertyIncomeModel->month = date('Y-m-01');
                    $opsProfile = \app\models\OperationsProfile::find()->where(['role_value' => 'ELHQ'])->one();
                    $propertyIncomeModel->approved_by = @$opsProfile->operations_id;
                    $propertyIncomeModel->created_by = @$opsProfile->operations_id;
                    $propertyIncomeModel->created_date = date('Y-m-d');
                    
                    if (!$propertyIncomeModel->save(false)) {
                        throw new \Exception('Exception');
                    }
                    
                    Yii::$app->session->setFlash('noti-amount', $txnAmount);
                    Yii::$app->session->setFlash('noti-paydesc', Yii::$app->userdata->getTenantPaymentDescByTpId($tpId));
                    Yii::$app->session->setFlash('noti-txndate', $txnDate);
                    Yii::$app->session->setFlash('noti-txnid', $txnRefNo);
                    $downloadJson = [];
                    $downloadJson['response'] = 'Payment Successful!';
                    $downloadJson['amount'] = $txnAmount;
                    $downloadJson['paydesc'] = Yii::$app->userdata->getTenantPaymentDescByTpId($tpId);
                    $downloadJson['txndate'] = $txnDate;
                    $downloadJson['txnid'] = $txnRefNo;
                    Yii::$app->session->setFlash('noti-download', json_encode($downloadJson));
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', "Payment amount of &#8377;".Yii::$app->userdata->getFormattedMoney($txnAmount)." is successfully done! Please login to download recipt.");
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->getSession()->setFlash('noti-error', "{$e->getMessage()}");
                }
            } else {
                Yii::$app->session->setFlash('error', $resDesc);
            }
            
            if (!empty(Yii::$app->session->get('app_redirect_url'))) {
                return $this->redirect(Yii::$app->session->get('app_redirect_url'));
            } else {
                $this->redirect(['./']);
            }
        } else {
            throw new \yii\web\ForbiddenHttpException('You are not allowed to directly access this page.');
        }
    }
    
    public function actionPayrentpaytmnetbankingnouth () {
        $this->layout = false;
        $referUrl = Url::home(true) . 'paytm/payrentpaytmnetbankingnouth';
        $returnUrl = Url::home(true)."paytm/rentpaymentresponsenouth";
        $userID = Yii::$app->getSecurity()->decryptByKey(base64_decode(Yii::$app->request->post('tenant_id')), Yii::$app->params['hash_key']);
        if (!empty(Yii::$app->request->post('order_id'))) {
            $transaction = Yii::$app->db->beginTransaction();
            
            try {
                $pgiModel = \app\models\PaymentGateway::find()->where(['order_id' => Yii::$app->request->post('order_id')])->one();
                
                if (count($pgiModel) == 0) {
                    throw new \Exception('Exception');
                }
                
                if ($pgiModel->txn_for != 2) {
                    throw new \Exception('Incorrect parameters');
                }
                
                $tpId = $pgiModel->tp_id;
                $propertyId = $pgiModel->property_id;
                $merchantId = Yii::$app->params['paytm_netbanking_merchant_id'];
                $orderId = Yii::$app->userdata->generateOrderId($userID);
                $customerId = $pgiModel->customer_id;
                $totalamount = $pgiModel->amount;
                $txnAmount = $totalamount;
                $transactionURL = (Yii::$app->params['paytm_netbanking_env'] == 'dev') ? Yii::$app->params['paytm_netbanking_txn_api_dev'] : Yii::$app->params['paytm_netbanking_txn_api_prod'] ;
                
                $paytmParams = array();
                $paytmParams["MID"] = $merchantId;
                $paytmParams["ORDER_ID"] = $orderId;
                $paytmParams["CUST_ID"] = $customerId;
                $paytmParams["MOBILE_NO"] = Yii::$app->userdata->getPhoneNumber(Yii::$app->user->id);
                $paytmParams["EMAIL"] = Yii::$app->userdata->getEmailById(Yii::$app->user->id);
                $paytmParams["CHANNEL_ID"] = Yii::$app->params['paytm_netbanking_channel_id'];
                $paytmParams["TXN_AMOUNT"] = $txnAmount;
                $paytmParams["WEBSITE"] = Yii::$app->params['paytm_netbanking_merchant_website'];
                $paytmParams["INDUSTRY_TYPE_ID"] = Yii::$app->params['paytm_netbanking_industry_type_id'];
                $paytmParams["CALLBACK_URL"] = $returnUrl;
                $paytmParams["PAYTM_CHECKSUM"] = Yii::$app->paytm->getChecksumFromArray($paytmParams, Yii::$app->params['paytm_netbanking_merchant_key']);

                if (!empty(Yii::$app->request->post('app_redirect_url'))) {
                    Yii::$app->session->set('app_redirect_url', Yii::$app->request->post('app_redirect_url'));
                }
                
                $checksum = $paytmParams["PAYTM_CHECKSUM"];
                $msg = json_encode($paytmParams);
                unset($paytmParams["PAYTM_CHECKSUM"]);
                
                $model = new \app\models\PaymentGateway();
                $model->customer_id = $customerId;
                $model->order_id = $orderId;
                $model->tp_id = $tpId;
                $model->user_type = 3;
                $model->user_id = $userID;
                $model->property_id = $propertyId;
                $model->amount = $totalamount;
                $model->request_time = date('Y-m-d H:i:s');
                $model->merchant_id = $merchantId;
                $model->checksum = $msg;
                $model->refer_url = $referUrl;
                $model->txn_for = 2;
                if (!$model->save()) {
                    throw new \Exception('Exception');
                }

                $transaction->commit();
                
                return $this->render('paytm_pay', ['transactionURL' => $transactionURL, 'paytmParams' => $paytmParams, 'paytmChecksum' => $checksum]);
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->getSession()->setFlash('success', "{$e->getMessage()}");
            }
            
            exit;
        }
        
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $tpId = Yii::$app->getSecurity()->decryptByKey(base64_decode(Yii::$app->request->post('TenantPayments')['id']), Yii::$app->params['hash_key']);
                $tpModel = \app\models\TenantPayments::find()->where(['id' => $tpId])->one();
                if (empty($tpModel)) {
                    throw new \Exception('Unable to locate object, something went wrong at server.');
                }
                
                $propertyId = $tpModel->property_id;
                $merchantId = Yii::$app->params['paytm_netbanking_merchant_id'];
                $orderId = Yii::$app->userdata->generateOrderId($userID);
                $customerId = Yii::$app->userdata->generateCustomerId($userID);
                $penaltyAmount = Yii::$app->userdata->calculatePenalty($tpId);
                $totalamount = $tpModel->total_amount + $penaltyAmount;
                $txnAmount = $totalamount;
                $transactionURL = (Yii::$app->params['paytm_netbanking_env'] == 'dev') ? Yii::$app->params['paytm_netbanking_txn_api_dev'] : Yii::$app->params['paytm_netbanking_txn_api_prod'] ;
                
                $paytmParams = array();
                $paytmParams["MID"] = $merchantId;
                $paytmParams["ORDER_ID"] = $orderId;
                $paytmParams["CUST_ID"] = $customerId;
                $paytmParams["MOBILE_NO"] = Yii::$app->userdata->getPhoneNumber(Yii::$app->user->id);
                $paytmParams["EMAIL"] = Yii::$app->userdata->getEmailById(Yii::$app->user->id);
                $paytmParams["CHANNEL_ID"] = Yii::$app->params['paytm_netbanking_channel_id'];
                $paytmParams["TXN_AMOUNT"] = $txnAmount;
                $paytmParams["WEBSITE"] = Yii::$app->params['paytm_netbanking_merchant_website'];
                $paytmParams["INDUSTRY_TYPE_ID"] = Yii::$app->params['paytm_netbanking_industry_type_id'];
                $paytmParams["CALLBACK_URL"] = $returnUrl;
                $paytmParams["PAYTM_CHECKSUM"] = Yii::$app->paytm->getChecksumFromArray($paytmParams, Yii::$app->params['paytm_netbanking_merchant_key']);

                if (!empty(Yii::$app->request->post('app_redirect_url'))) {
                    Yii::$app->session->set('app_redirect_url', Yii::$app->request->post('app_redirect_url'));
                }
                
                $checksum = $paytmParams["PAYTM_CHECKSUM"];
                $msg = json_encode($paytmParams);
                unset($paytmParams["PAYTM_CHECKSUM"]);
                
                $model = new \app\models\PaymentGateway();
                $model->customer_id = $customerId;
                $model->order_id = $orderId;
                $model->tp_id = $tpId;
                $model->user_type = 3;
                $model->user_id = $userID;
                $model->property_id = $propertyId;
                $model->amount = $totalamount;
                $model->request_time = date('Y-m-d H:i:s');
                $model->merchant_id = $merchantId;
                $model->checksum = $msg;
                $model->refer_url = $referUrl;
                $model->txn_for = 2;
                if (!$model->save()) {
                    throw new \Exception('Exception');
                }

                $transaction->commit();
                
                return $this->render('paytm_pay', ['transactionURL' => $transactionURL, 'paytmParams' => $paytmParams, 'paytmChecksum' => $checksum]);
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->getSession()->setFlash('success', "{$e->getMessage()}");
            }
        } else {
            throw new \yii\web\ForbiddenHttpException('You are not allowed to directly access this page.');
        }
        
    }
}
