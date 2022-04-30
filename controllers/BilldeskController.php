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

class BilldeskController extends Controller {

    public $layout = false;

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['paymentstatus', 'paynow'],
                'rules' => [
                    [
                        'actions' => ['paymentstatus', 'paynow'],
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

    public function actionPaynow($action = 'PROPERTY_BOOKING') {
        $this->layout = false;

        if (!empty($_POST['payment_action'])) {
            $action = $_POST['payment_action'];
        }

        $billdeskStatus = \app\models\SystemConfig::find()->where(['name' => 'BILLDESK-STATUS'])->one();
        $pgStatus = 0;
        if (!empty($billdeskStatus)) {
            $pgStatus = $billdeskStatus->value;
        }

        if (YII_ENV != 'prod' && $pgStatus == 0) {
            $this->layout = 'app_dashboard';
            if (!empty(Yii::$app->request->post('dummy_pgi_request'))) {
                $secretPgKey = 'XXXXX';
                $url = 'https://paymentgateway.dev/dummy/test';
                $customerId = Yii::$app->userdata->generateCustomerId(Yii::$app->userdata->id);
                $merchantId = 'XXXXX';
                $currencyType = 'XXXXX';
                $typeField1 = 'XXXXX';
                $typeField2 = 'XXXXX';
                $securityID = 'XXXXX';
                $referUrl = Url::home(true) . 'site/paynow';

                if ($action == 'PROPERTY_BOOKING') {
                    $txnAmount = Yii::$app->request->post('PropertyVisits')['txn_amount'];
                    $propertyId = Yii::$app->request->post('PropertyVisits')['property_id'];
                    $customerId = $customerId . 'ELPBI' . $propertyId;
                    $returnUrl = Url::home(true) . 'dev/test';
                    if (!empty(Yii::$app->request->post('app_redirect_url'))) {
                        //$str = $merchantId . '|' . $customerId . '|NA|' . $txnAmount . '|NA|NA|NA|' . $currencyType . '|NA|' . $typeField1 . '|' . $securityID . '|NA|NA|' . $typeField2 . '|'.Yii::$app->request->post('app_redirect_url').'|NA|NA|NA|NA|NA|NA|' . $returnUrl;
                        $_SESSION['app_redirect_url'] = Yii::$app->request->post('app_redirect_url');
                    } else {
                        //$str = $merchantId . '|' . $customerId . '|NA|' . $txnAmount . '|NA|NA|NA|' . $currencyType . '|NA|' . $typeField1 . '|' . $securityID . '|NA|NA|' . $typeField2 . '|NA|NA|NA|NA|NA|NA|NA|' . $returnUrl;
                    }

                    $str = $merchantId . '|' . $customerId . '|NA|' . $txnAmount . '|NA|NA|NA|' . $currencyType . '|NA|' . $typeField1 . '|' . $securityID . '|NA|NA|' . $typeField2 . '|NA|NA|NA|NA|NA|NA|NA|' . $returnUrl;

                    $checksum = hash_hmac('sha256', $str, $secretPgKey, false);
                    $checksum = strtoupper($checksum);
                    $msg = $str . '|' . $checksum;

                    $transaction = Yii::$app->db->beginTransaction();

                    try {
                        $model = new \app\models\PaymentGateway();
                        $model->customer_id = $customerId;
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
                                $bedroom_intel = json_decode(stripcslashes($_POST['bedroom_info']));
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

                        // check here is it for successfull or for cancellation ///////////////
                        if (Yii::$app->request->post('payment_status') == 1) {
                            $responseMode = "";
                            $patt = "/(?:^|[^a-zA-Z])" . preg_quote("ELPBI", '/') . "(?:$|[^a-zA-Z])/i";
                            if (preg_match($patt, $customerId)) {
                                $responseMode = "ELPBI";
                                $propertyId = explode('ELPBI', $customerId)[1];
                            }

                            $patt = "/(?:^|[^a-zA-Z])" . preg_quote("ELTPI", '/') . "(?:$|[^a-zA-Z])/i";
                            if (preg_match($patt, $customerId)) {
                                $responseMode = "ELTPI";
                                $tenantPaymentId = explode('ELTPI', $customerId)[1];
                            }

                            $walletModel = \app\models\Wallets::find()->where(['user_id' => Yii::$app->userdata->id])->one();
                            if ($walletModel) {
                                $walletModel->amount = (double) $txnAmount + (double) $walletModel->amount;
                                $walletModel->property_id = $propertyId;
                                $walletModel->updated_by = Yii::$app->userdata->id;
                                if (!$walletModel->save(false)) {
                                    throw new \Exception('Exception');
                                }
                            } else {
                                $walletModel = new \app\models\Wallets();
                                $walletModel->user_id = Yii::$app->userdata->id;
                                $walletModel->property_id = $propertyId;
                                $walletModel->amount = (double) $txnAmount;
                                $walletModel->created_by = Yii::$app->userdata->id;
                                $walletModel->created_date = date('Y-m-d H:i:s');
                                if (!$walletModel->save(false)) {
                                    throw new \Exception('Exception');
                                }
                            }

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

                            $pgiModel = \app\models\PaymentGateway::find()->where(['customer_id' => $customerId])->one();
                            $walletHistoryModel = new \app\models\WalletsHistory();
                            $walletHistoryModel->user_id = Yii::$app->userdata->id;
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

                            $propDetail = Properties::find()->where([
                                        'id' => $propertyId
                                    ])->one();

                            if ($propDetail->property_type != 3) {
                                $parentPropId = 0;
                                $parentPropChngStatus = 1;
                                $propList = \app\models\ChildProperties::find()->where([
                                            'id' => $childPropertyId
                                        ])->one();
                                $propList->status = 0;

                                $parentPropId = $propList->main;

                                if (!$propList->save(false)) {
                                    throw new \Exception('Exception');
                                }

                                $propLists = \app\models\ChildProperties::find()->where([
                                            'main' => $parentPropId,
                                            'type' => 2
                                        ])->all();

                                if ($propLists) {
                                    foreach ($propLists as $propList) {
                                        if ($propList->status == 1) {
                                            $parentPropChngStatus = 0;
                                            break;
                                        }
                                    }

                                    if ($parentPropChngStatus === 1) {
                                        $mainProp = \app\models\PropertyListing::find()->where([
                                                    'property_id' => $parentPropId
                                                ])->one();
                                        $mainProp->status = '0';

                                        if (!$mainProp->save(false)) {
                                            throw new \Exception('Exception');
                                        }
                                    }
                                }
                            } else {
                                $propList = \app\models\PropertyListing::find()->where([
                                            'property_id' => $propertyId
                                        ])->one();
                                $propList->status = 0;
                                if (!$propList->save(false)) {
                                    throw new \Exception('Exception');
                                }
                            }

                            Yii::$app->session->setFlash('noti-success', "Payment Successful!");
                            Yii::$app->session->setFlash('noti-amount', $txnAmount);
                            Yii::$app->session->setFlash('noti-paydesc', "Property Booked - " . Yii::$app->userdata->getPropertyNameById($propertyId));
                            Yii::$app->session->setFlash('noti-txndate', date('d-m-Y'));
                            Yii::$app->session->setFlash('noti-txnid', 'XXXXXXX');
                            $downloadJson = [];
                            $downloadJson['rs_mode'] = $responseMode;
                            $downloadJson['response'] = 'Payment Successful!';
                            $downloadJson['amount'] = $txnAmount;
                            $downloadJson['paydesc'] = "Property Booked - " . Yii::$app->userdata->getPropertyNameById($propertyId);
                            $downloadJson['txndate'] = date('d-m-Y');
                            $downloadJson['txnid'] = 'XXXXXXX';
                            Yii::$app->session->setFlash('noti-download', json_encode($downloadJson));
                        }
                        $transaction->commit();
                    } catch (\Exception $e) {
                        exit($e->getMessage());
                        $transaction->rollBack();
                        Yii::$app->getSession()->setFlash(
                                'success', "{$e->getMessage()}"
                        );
                    }
                }

                if ($action == 'TENANT_PAYMENT') {
                    $id = Yii::$app->request->post('TenantPayments')['id'];
                    $totalamount = Yii::$app->request->post('TenantPayments')['totalamount'];
                    $txnAmount = $totalamount;
                    $propertyId = Yii::$app->request->post('TenantPayments')['property_id'];
                    $tenantPaymentId = Yii::$app->request->post('TenantPayments')['id'];
                    $returnUrl = Url::home(true) . 'site/paymentstatus';
                    $customerId = $customerId . 'ELTPI' . $tenantPaymentId;
                    if (!empty(Yii::$app->request->post('app_redirect_url'))) {
                        //$str = $merchantId . '|' . $customerId . '|NA|' . $txnAmount . '|NA|NA|NA|' . $currencyType . '|NA|' . $typeField1 . '|' . $securityID . '|NA|NA|' . $typeField2 . '|'.Yii::$app->request->post('app_redirect_url').'|NA|NA|NA|NA|NA|NA|' . $returnUrl;
                        $_SESSION['app_redirect_url'] = Yii::$app->request->post('app_redirect_url');
                    } else {
                        //$str = $merchantId . '|' . $customerId . '|NA|' . $txnAmount . '|NA|NA|NA|' . $currencyType . '|NA|' . $typeField1 . '|' . $securityID . '|NA|NA|' . $typeField2 . '|NA|NA|NA|NA|NA|NA|NA|' . $returnUrl;
                    }

                    $str = $merchantId . '|' . $customerId . '|NA|' . $txnAmount . '|NA|NA|NA|' . $currencyType . '|NA|' . $typeField1 . '|' . $securityID . '|NA|NA|' . $typeField2 . '|NA|NA|NA|NA|NA|NA|NA|' . $returnUrl;

                    $checksum = hash_hmac('sha256', $str, $secretPgKey, false);
                    $checksum = strtoupper($checksum);
                    $msg = $str . '|' . $checksum;

                    $transaction = Yii::$app->db->beginTransaction();

                    try {
                        if (empty(Yii::$app->request->post('neft-referer-' . $_POST['serial']))) {
                            $model = new \app\models\PaymentGateway();
                            $model->customer_id = $customerId;
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
                        }

                        if (!empty($tenantPaymentId) && !empty(Yii::$app->request->post('TenantPayments')['penalty_amount'])) {
                            if (Yii::$app->request->post('TenantPayments')['penalty_amount'] > 0) {
                                $model = \app\models\TenantPayments::findOne($id);
                                $model->penalty_amount = Yii::$app->request->post('TenantPayments')['penalty_amount'];
                                $model->payment_status = 1;
                                if (!$model->save(false)) {
                                    throw new \Exception('Exception');
                                }
                                unset($model);
                            }
                        }

                        $lastInsertedId = Yii::$app->db->getLastInsertID();
                        $model = \app\models\TenantPayments::findOne($id);

                        if ($model->load(Yii::$app->request->post())) {
                            $model->payment_date = date('Y-m-d');
                            $model->updated_date = date('Y-m-d H:i:s');
                            if (empty(Yii::$app->request->post('neft-referer-' . $_POST['serial']))) {
                                $model->payment_mode = 2;
                                $model->pgi_reference = $lastInsertedId;
                                $model->save(false);
                            } else {
                                $model->payment_mode = 1;
                                $model->payment_status = 2;
                                $model->neft_reference = Yii::$app->request->post('neft-referer-' . $_POST['serial']);
                                $model->penalty_amount = Yii::$app->request->post('TenantPayments')['penalty_amount'];
                                $model->save(false);
                            }
                        }
                        $transaction->commit();
                    } catch (\Exception $e) {
                        $transaction->rollBack();
                        Yii::$app->getSession()->setFlash(
                                'success', "{$e->getMessage()}"
                        );
                    }

                    if (empty(Yii::$app->request->post('neft-referer-' . $_POST['serial']))) {
                        //return $this->render('book_now', ['msg' => $msg, 'url' => $url]);
                    } else {
                        Yii::$app->getSession()->setFlash(
                                //'success', "Your payment request via NEFT has been recorded, however the payment status will be updated once we have verified the payment against our bank statement. The verification normally takes 2-3 business days"
                                'success', "Thanks for making payment via NEFT, we will update the payment status to 'Paid' once we have verified the payment data against our bank records. The verification normally takes 2-3 business days. In case of any mismatch, our operations team will reach out to you for any further clarifications."
                        );
                    }
                }

                if (!empty(Yii::$app->request->post('app_redirect_url'))) {
                    return $this->redirect(Yii::$app->request->post('app_redirect_url'));
                } else {
                    $this->redirect(['./']);
                }
            } else if (Yii::$app->request->post()) {
                if (!empty($_POST['TenantPayments'])) {
                    $_POST['PropertyVisits'] = $_POST['TenantPayments'];
                    $_POST['PropertyVisits']['txn_amount'] = $_POST['TenantPayments']['totalamount'];
                }
                return $this->render('dummy_paygate', ['data' => $_POST]);
            }
        } else {
            if (!empty(Yii::$app->request->post('prop_book_retry')) && !empty(Yii::$app->request->post('customer_id'))) {
                $url = 'https://pgi.billdesk.com/pgidsk/PGIMerchantPayment';
                $pgiModel = \app\models\PaymentGateway::find()->where(['customer_id' => Yii::$app->request->post('customer_id')])->one();
                $secretPgKey = '44OZKnwByoYq';
                $url = 'https://pgi.billdesk.com/pgidsk/PGIMerchantPayment';
                $customerId = Yii::$app->userdata->generateCustomerId($pgiModel->user_id);
                $merchantId = 'EASYLTECH';
                $currencyType = 'INR';
                $typeField1 = 'R';
                $typeField2 = 'F';
                $securityID = 'easyltech';
                $referUrl = Url::home(true) . 'site/paynow';

                if ($action == 'PROPERTY_BOOKING') {
                    $txnAmount = $pgiModel->amount;
                    $propertyId = $pgiModel->property_id;
                    $customerId = $customerId . 'ELPBI' . $propertyId;
                    $returnUrl = Url::home(true) . 'site/paymentstatus';
                    if (!empty(Yii::$app->request->post('app_redirect_url'))) {
                        //$str = $merchantId . '|' . $customerId . '|NA|' . $txnAmount . '|NA|NA|NA|' . $currencyType . '|NA|' . $typeField1 . '|' . $securityID . '|NA|NA|' . $typeField2 . '|'.Yii::$app->request->post('app_redirect_url').'|NA|NA|NA|NA|NA|NA|' . $returnUrl;
                        $_SESSION['app_redirect_url'] = Yii::$app->request->post('app_redirect_url');
                    } else {
                        //$str = $merchantId . '|' . $customerId . '|NA|' . $txnAmount . '|NA|NA|NA|' . $currencyType . '|NA|' . $typeField1 . '|' . $securityID . '|NA|NA|' . $typeField2 . '|NA|NA|NA|NA|NA|NA|NA|' . $returnUrl;
                    }

                    $str = $merchantId . '|' . $customerId . '|NA|' . $txnAmount . '|NA|NA|NA|' . $currencyType . '|NA|' . $typeField1 . '|' . $securityID . '|NA|NA|' . $typeField2 . '|NA|NA|NA|NA|NA|NA|NA|' . $returnUrl;

                    $checksum = hash_hmac('sha256', $str, $secretPgKey, false);
                    $checksum = strtoupper($checksum);
                    $msg = $str . '|' . $checksum;

                    $transaction = Yii::$app->db->beginTransaction();

                    try {
                        $model = new \app\models\PaymentGateway();
                        $model->customer_id = $customerId;
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

                    return $this->render('book_now', ['msg' => $msg, 'url' => $url]);
                }
                exit;
            }

            if (!empty(Yii::$app->request->post('tenant_pay_retry')) && !empty(Yii::$app->request->post('customer_id'))) {
                $url = 'https://pgi.billdesk.com/pgidsk/PGIMerchantPayment';
                $pgiModel = \app\models\PaymentGateway::find()->where(['customer_id' => Yii::$app->request->post('customer_id')])->one();
                $secretPgKey = '44OZKnwByoYq';
                $url = 'https://pgi.billdesk.com/pgidsk/PGIMerchantPayment';
                $customerId = Yii::$app->userdata->generateCustomerId($pgiModel->user_id);
                $merchantId = 'EASYLTECH';
                $currencyType = 'INR';
                $typeField1 = 'R';
                $typeField2 = 'F';
                $securityID = 'easyltech';
                $referUrl = Url::home(true) . 'site/paynow';

                $id = Yii::$app->request->post('TenantPayments')['id'];
                $totalamount = Yii::$app->request->post('TenantPayments')['totalamount'];
                $txnAmount = $totalamount;
                $propertyId = Yii::$app->request->post('TenantPayments')['property_id'];
                $tenantPaymentId = Yii::$app->request->post('TenantPayments')['id'];
                $returnUrl = Url::home(true) . 'site/paymentstatus';
                $customerId = $customerId . 'ELTPI' . $tenantPaymentId;
                if (!empty(Yii::$app->request->post('app_redirect_url'))) {
                    //$str = $merchantId . '|' . $customerId . '|NA|' . $txnAmount . '|NA|NA|NA|' . $currencyType . '|NA|' . $typeField1 . '|' . $securityID . '|NA|NA|' . $typeField2 . '|'.Yii::$app->request->post('app_redirect_url').'|NA|NA|NA|NA|NA|NA|' . $returnUrl;
                    $_SESSION['app_redirect_url'] = Yii::$app->request->post('app_redirect_url');
                } else {
                    //$str = $merchantId . '|' . $customerId . '|NA|' . $txnAmount . '|NA|NA|NA|' . $currencyType . '|NA|' . $typeField1 . '|' . $securityID . '|NA|NA|' . $typeField2 . '|NA|NA|NA|NA|NA|NA|NA|' . $returnUrl;
                }

                $str = $merchantId . '|' . $customerId . '|NA|' . $txnAmount . '|NA|NA|NA|' . $currencyType . '|NA|' . $typeField1 . '|' . $securityID . '|NA|NA|' . $typeField2 . '|NA|NA|NA|NA|NA|NA|NA|' . $returnUrl;

                $checksum = hash_hmac('sha256', $str, $secretPgKey, false);
                $checksum = strtoupper($checksum);
                $msg = $str . '|' . $checksum;

                $transaction = Yii::$app->db->beginTransaction();

                try {
                    $model = new \app\models\PaymentGateway();
                    $model->customer_id = $customerId;
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

                    $model = \app\models\TenantPayments::findOne($id);

                    if ($model->load(Yii::$app->request->post())) {
                        //$model->payment_type = 1;
                        $model->updated_date = date('Y-m-d H:i:s');
                        if (empty(Yii::$app->request->post('neft-referer-' . $_POST['serial']))) {
                            $model->payment_mode = 2;
                            $model->pgi_reference = Yii::$app->db->getLastInsertID();
                            $model->save(false);
                        } else {
                            $model->payment_mode = 1;
                            $model->neft_reference = Yii::$app->request->post('neft-referer-' . $_POST['serial']);
                            $model->save(false);
                        }
                    }

                    $transaction->commit();
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->getSession()->setFlash(
                            'success', "{$e->getMessage()}"
                    );
                }

                return $this->render('book_now', ['msg' => $msg, 'url' => $url]);
                exit;
            }

            //////////////////////////////////////////////////////////////////////////////////////

            if (Yii::$app->request->post()) {

                $secretPgKey = '44OZKnwByoYq';
                $url = 'https://pgi.billdesk.com/pgidsk/PGIMerchantPayment';
                $customerId = Yii::$app->userdata->generateCustomerId(Yii::$app->userdata->id);
                $merchantId = 'EASYLTECH';
                $currencyType = 'INR';
                $typeField1 = 'R';
                $typeField2 = 'F';
                $securityID = 'easyltech';
                $referUrl = Url::home(true) . 'site/paynow';

                if ($action == 'PROPERTY_BOOKING') {
                    $txnAmount = Yii::$app->request->post('PropertyVisits')['txn_amount'];
                    $propertyId = Yii::$app->request->post('PropertyVisits')['property_id'];
                    $customerId = $customerId . 'ELPBI' . $propertyId;
                    $returnUrl = Url::home(true) . 'site/paymentstatus';
                    if (!empty(Yii::$app->request->post('app_redirect_url'))) {
                        //$str = $merchantId . '|' . $customerId . '|NA|' . $txnAmount . '|NA|NA|NA|' . $currencyType . '|NA|' . $typeField1 . '|' . $securityID . '|NA|NA|' . $typeField2 . '|'.Yii::$app->request->post('app_redirect_url').'|NA|NA|NA|NA|NA|NA|' . $returnUrl;
                        $_SESSION['app_redirect_url'] = Yii::$app->request->post('app_redirect_url');
                    } else {
                        //$str = $merchantId . '|' . $customerId . '|NA|' . $txnAmount . '|NA|NA|NA|' . $currencyType . '|NA|' . $typeField1 . '|' . $securityID . '|NA|NA|' . $typeField2 . '|NA|NA|NA|NA|NA|NA|NA|' . $returnUrl;
                    }

                    $str = $merchantId . '|' . $customerId . '|NA|' . $txnAmount . '|NA|NA|NA|' . $currencyType . '|NA|' . $typeField1 . '|' . $securityID . '|NA|NA|' . $typeField2 . '|NA|NA|NA|NA|NA|NA|NA|' . $returnUrl;

                    $checksum = hash_hmac('sha256', $str, $secretPgKey, false);
                    $checksum = strtoupper($checksum);
                    $msg = $str . '|' . $checksum;

                    $transaction = Yii::$app->db->beginTransaction();

                    try {
                        $model = new \app\models\PaymentGateway();
                        $model->customer_id = $customerId;
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

                    return $this->render('book_now', ['msg' => $msg, 'url' => $url]);
                }

                if ($action == 'TENANT_PAYMENT') {
                    $id = Yii::$app->request->post('TenantPayments')['id'];
                    $totalamount = Yii::$app->request->post('TenantPayments')['totalamount'];
                    $txnAmount = $totalamount;
                    $propertyId = Yii::$app->request->post('TenantPayments')['property_id'];
                    $tenantPaymentId = Yii::$app->request->post('TenantPayments')['id'];
                    $returnUrl = Url::home(true) . 'site/paymentstatus';
                    $customerId = $customerId . 'ELTPI' . $tenantPaymentId;
                    if (!empty(Yii::$app->request->post('app_redirect_url'))) {
                        //$str = $merchantId . '|' . $customerId . '|NA|' . $txnAmount . '|NA|NA|NA|' . $currencyType . '|NA|' . $typeField1 . '|' . $securityID . '|NA|NA|' . $typeField2 . '|'.Yii::$app->request->post('app_redirect_url').'|NA|NA|NA|NA|NA|NA|' . $returnUrl;
                        $_SESSION['app_redirect_url'] = Yii::$app->request->post('app_redirect_url');
                    } else {
                        //$str = $merchantId . '|' . $customerId . '|NA|' . $txnAmount . '|NA|NA|NA|' . $currencyType . '|NA|' . $typeField1 . '|' . $securityID . '|NA|NA|' . $typeField2 . '|NA|NA|NA|NA|NA|NA|NA|' . $returnUrl;
                    }

                    $str = $merchantId . '|' . $customerId . '|NA|' . $txnAmount . '|NA|NA|NA|' . $currencyType . '|NA|' . $typeField1 . '|' . $securityID . '|NA|NA|' . $typeField2 . '|NA|NA|NA|NA|NA|NA|NA|' . $returnUrl;

                    $checksum = hash_hmac('sha256', $str, $secretPgKey, false);
                    $checksum = strtoupper($checksum);
                    $msg = $str . '|' . $checksum;

                    $transaction = Yii::$app->db->beginTransaction();

                    try {
                        if (empty(Yii::$app->request->post('neft-referer-' . $_POST['serial']))) {
                            $model = new \app\models\PaymentGateway();
                            $model->customer_id = $customerId;
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
                        }

                        if (!empty($tenantPaymentId) && !empty(Yii::$app->request->post('TenantPayments')['penalty_amount'])) {
                            if (Yii::$app->request->post('TenantPayments')['penalty_amount'] > 0) {
                                $model = \app\models\TenantPayments::findOne($id);
                                $model->penalty_amount = Yii::$app->request->post('TenantPayments')['penalty_amount'];
                                if (!$model->save(false)) {
                                    throw new \Exception('Exception');
                                }
                                unset($model);
                            }
                        }

                        $lastInsertedId = Yii::$app->db->getLastInsertID();
                        $model = \app\models\TenantPayments::findOne($id);

                        if ($model->load(Yii::$app->request->post())) {
                            $model->payment_date = date('Y-m-d');
                            $model->updated_date = date('Y-m-d H:i:s');
                            if (empty(Yii::$app->request->post('neft-referer-' . $_POST['serial']))) {
                                $model->payment_mode = 2;
                                $model->pgi_reference = $lastInsertedId;
                                $model->save(false);
                            } else {
                                $model->payment_mode = 1;
                                $model->payment_status = 2;
                                $model->neft_reference = Yii::$app->request->post('neft-referer-' . $_POST['serial']);
                                $model->penalty_amount = Yii::$app->request->post('TenantPayments')['penalty_amount'];
                                $model->save(false);
                            }
                        }

                        $transaction->commit();
                    } catch (\Exception $e) {
                        $transaction->rollBack();
                        Yii::$app->getSession()->setFlash(
                                'success', "{$e->getMessage()}"
                        );
                    }
                    if (empty(Yii::$app->request->post('neft-referer-' . $_POST['serial']))) {
                        return $this->render('book_now', ['msg' => $msg, 'url' => $url]);
                    } else {
                        Yii::$app->getSession()->setFlash(
                                //'success', "Your payment request via NEFT has been recorded, however the payment status will be updated once we have verified the payment against our bank statement. The verification normally takes 2-3 business days"
                                'success', "Thanks for making payment via NEFT, we will update the payment status to 'Paid' once we have verified the payment data against our bank records. The verification normally takes 2-3 business days. In case of any mismatch, our operations team will reach out to you for any further clarifications."
                        );
                        if (!empty(Yii::$app->request->post('app_redirect_url'))) {
                            return $this->redirect(Yii::$app->request->post('app_redirect_url'));
                        }
                    }
                }
            } else {
                throw new \yii\web\ForbiddenHttpException('You are not allowed to directly access this page.');
            }
        }
    }
    
    public function actionPaymentstatus() {
        if (Yii::$app->request->post()) {
            $response = Yii::$app->request->post('msg');
            $response = explode('|', $response);
            //print_r($response); exit;
            $merchantId = $response[0];
            $customerId = $response[1];
            $txnRefNo = $response[2];
            $bankRefNo = $response[3];
            $txnAmount = $response[4];
            $bankId = $response[5];
            $bin = $response[6];
            $txnType = $response[7];
            $currencyName = $response[8];
            $itemCode = $response[9];
            $securityType = $response[10];
            $securityId = $response[11];
            $securityPass = $response[12];
            $txnDate = $response[13];
            $authStatus = $response[14];
            $settlementType = $response[15];
            $additionalInfo1 = $response[16];
            $additionalInfo2 = $response[17];
            $additionalInfo3 = $response[18];
            $additionalInfo4 = $response[19];
            $additionalInfo5 = $response[20];
            $additionalInfo6 = $response[21];
            $additionalInfo7 = $response[22];
            $resStatus = $response[23];
            $resDesc = $response[24];
            $checkSum = $response[25];

            $model = \app\models\PaymentGateway::find()->where(['customer_id' => $customerId])->one();
            $pgiReference = $model->id;
            $model->payment_status = ($authStatus == '0300') ? 1 : 0;
            $model->response_time = date('Y-m-d H:i:s', strtotime($txnDate));
            $model->security_id = $securityId;
            $model->txn_reference_no = $txnRefNo;
            $model->bank_reference_no = $bankRefNo;
            $model->bank_id = $bankId;
            $model->txn_type = $txnType;
            $model->currency_name = $currencyName;
            $model->auth_status = $authStatus;
            $model->description = $resDesc;
            $model->save();

            Yii::$app->session->setFlash('noti-retry', $customerId);

            if ($authStatus == '0300') {
                Yii::$app->session->setFlash('noti-success', 'You have successfully booked the property.');

                /////////////////////////// Adding to wallet [Starts Here] //////////////////////////

                $pgiModel = \app\models\PaymentGateway::find()->where(['customer_id' => $customerId])->one();
                $userId = $pgiModel->user_id;
                $responseMode = "";
                $patt = "/(?:^|[^a-zA-Z])" . preg_quote("ELPBI", '/') . "(?:$|[^a-zA-Z])/i";
                if (preg_match($patt, $customerId)) {
                    $responseMode = "ELPBI";
                    $propertyId = explode('ELPBI', $customerId)[1];
                }

                $patt = "/(?:^|[^a-zA-Z])" . preg_quote("ELTPI", '/') . "(?:$|[^a-zA-Z])/i";
                if (preg_match($patt, $customerId)) {
                    $responseMode = "ELTPI";
                    $tenantPaymentId = explode('ELTPI', $customerId)[1];
                }

                // Starting transaction
                $transaction = Yii::$app->db->beginTransaction();

                try {

                    if ($responseMode === "ELPBI") {
                        // Adding to wallets table ////
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

                        $propDetail = Properties::find()->where([
                                    'id' => $propertyId
                                ])->one();

                        if ($propDetail->property_type != 3) {
                            $parentPropId = 0;
                            $parentPropChngStatus = 1;
                            $propList = \app\models\ChildProperties::find()->where([
                                        'id' => $childPropertyId
                                    ])->one();
                            $propList->status = 0;

                            $parentPropId = $propList->main;

                            if (!$propList->save(false)) {
                                throw new \Exception('Exception');
                            }

                            $propLists = \app\models\ChildProperties::find()->where([
                                        'main' => $parentPropId,
                                        'type' => 2
                                    ])->all();

                            if ($propLists) {
                                foreach ($propLists as $propList) {
                                    if ($propList->status == 1) {
                                        $parentPropChngStatus = 0;
                                        break;
                                    }
                                }

                                if ($parentPropChngStatus === 1) {
                                    $mainProp = \app\models\PropertyListing::find()->where([
                                                'property_id' => $parentPropId
                                            ])->one();
                                    $mainProp->status = '0';

                                    if (!$mainProp->save(false)) {
                                        throw new \Exception('Exception');
                                    }
                                }
                            }
                        } else {
                            $propList = \app\models\PropertyListing::find()->where([
                                        'property_id' => $propertyId
                                    ])->one();
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
                        $downloadJson['rs_mode'] = $responseMode;
                        $downloadJson['response'] = 'Payment Successful!';
                        $downloadJson['amount'] = $txnAmount;
                        $downloadJson['paydesc'] = "Property Booked - " . Yii::$app->userdata->getPropertyNameById($propertyId);
                        $downloadJson['txndate'] = $txnDate;
                        $downloadJson['txnid'] = $txnRefNo;
                        Yii::$app->session->setFlash('noti-download', json_encode($downloadJson));
                    }

                    if ($responseMode === "ELTPI") {
                        $tenantPayment = \app\models\TenantPayments::find()->where([
                                    'id' => $tenantPaymentId
                                ])->one();
                        $tenantPayment->amount_paid = (double) $txnAmount;
                        $tenantPayment->pgi_reference = $pgiReference;
                        $tenantPayment->payment_status = 1;
                        if (!$tenantPayment->save(false)) {
                            throw new \Exception('Exception');
                        }

                        Yii::$app->session->setFlash('noti-success', "Payment Successful!");
                        Yii::$app->session->setFlash('noti-amount', $txnAmount);
                        Yii::$app->session->setFlash('noti-paydesc', Yii::$app->userdata->getTenantPaymentDescByTpId($tenantPaymentId));
                        Yii::$app->session->setFlash('noti-txndate', $txnDate);
                        Yii::$app->session->setFlash('noti-txnid', $txnRefNo);
                        $downloadJson = [];
                        $downloadJson['rs_mode'] = $responseMode;
                        $downloadJson['response'] = 'Payment Successful!';
                        $downloadJson['amount'] = $txnAmount;
                        $downloadJson['paydesc'] = Yii::$app->userdata->getTenantPaymentDescByTpId($tenantPaymentId);
                        $downloadJson['txndate'] = $txnDate;
                        $downloadJson['txnid'] = $txnRefNo;
                        Yii::$app->session->setFlash('noti-download', json_encode($downloadJson));
                    }

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

            //if (!empty($additionalInfo1) && $additionalInfo1 != 'NA') {
            if (!empty($_SESSION['app_redirect_url']) && $_SESSION['app_redirect_url'] != 'NA') {
                $redirectTo = $_SESSION['app_redirect_url'];
                unset($_SESSION['app_redirect_url']);
                return $this->redirect($redirectTo);
            } else {
                $this->redirect(['./']);
            }
            // Load View according to requirement.
            //return $this->render('book_now_status', ['msg' => $msg, 'url' => $url]);
        } else {
            throw new \yii\web\ForbiddenHttpException('You are not allowed to directly access this page.');
        }
    }
}
