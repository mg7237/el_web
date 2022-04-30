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

class CampaignController extends Controller {

    public $layout = 'home';

    /**
     * @inheritdoc
     */
    public function init() {
        $request = Yii::$app->request;
        $action = explode("/", $this->module->requestedRoute);

        $newAction = Array();
        foreach ($action as $link) {
            if (trim($link) != '') {
                $newAction[] = $link;
            }
        }

        $action = implode("/", $newAction);
        if (!$request->isAjax) {
            if (Yii::$app->user->id) {
                $checked = Yii::$app->userdata->checkvalid($action, Yii::$app->user->identity->user_type);
                if ($checked['status'] == 0) {
                    $this->redirect($checked['action']);
                }
            }
        }
    }

    public function actionMpdfdemo1() {
        Yii::$app->Pdfcrowd->convertURI('http://112.196.17.29/pmstest/web/site');
    }

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'myagreements', 'ownerlease', 'editowner', 'ownerrequests', 'ownerleads', 'myprofile', 'editprofile', 'myfavlist', 'wallet', 'payrent', 'payrentadvance', 'bankdetails', 'bookinginfo', 'payment', 'addmoney', 'booknow', 'confirmvisits', 'mypayments', 'mypaymentsdue', 'mypaymentsforcast', 'mypaymentsnew', 'mylease', 'editprofiletenant', 'myrequests', 'createrequests', 'createownerrequests', 'dashboard', 'mydashboard', 'mytenants', 'tenantdetails', 'ownerpayments', 'confirmation', 'onboarding', 'tenantpenaltycalculate', 'filterpopup', 'editpersonalinfo'],
                'rules' => [
                    [
                        'actions' => ['tenantpenaltycalculate', 'logout', 'myagreements', 'ownerlease', 'editowner', 'ownerrequests', 'ownerleads', 'myprofile', 'editprofile', 'payrent', 'payrentadvance', 'myfavlist', 'wallet', 'bankdetails', 'editprofiletenant', 'bookinginfo', 'payment', 'addmoney', 'createownerrequests', 'booknow', 'mypaymentsdue', 'mypaymentsforcast', 'confirmvisits', 'mypayments', 'mypaymentsnew', 'mylease', 'myrequests', 'createrequests', 'dashboard', 'mydashboard', 'mytenants', 'tenantdetails', 'ownerpayments', 'confirmation', 'onboarding', 'filterpopup', 'editpersonalinfo'],
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

    public function accessRules() {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('filterpopup'),
                'users' => array('*'),
            ),
        );
    }

    /**
     * @inheritdoc
     */
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

    

    public function actionLaunchoffers() {
        header("Cache-Control: max-age=6480000"); //30days (60sec * 60min * 24hours * 30days)
        $this->view->params['head_title'] = "Launch Offers - Easyleases | Residential Property Management";
        $this->view->params['meta_desc'] = "Launch Offers - Easyleases, Property Management Services, Property Management Services Bangalore, Tenant Management, Home Management, Real Estate ManagementLaunch Offers - Easyleases, Property Management Services, Property Management Services Bangalore, Tenant Management, Home Management, Real Estate Management.";
        $this->layout = 'camp_layout';
        $postData = [];
        $postData['owner_name'] = null;
        $postData['owner_email'] = null;
        $postData['owner_phone'] = null;
        $postData['owner_city'] = null;
        if (!empty(Yii::$app->request->post('owner_phone')) && !empty(Yii::$app->request->post('owner_city'))) {
            $ownerName = Yii::$app->request->post('owner_name');
            $ownerEmail = Yii::$app->request->post('owner_email');
            $ownerPhone = Yii::$app->request->post('owner_phone');
            $ownerCity = Yii::$app->request->post('owner_city');

            $postData['owner_name'] = $ownerName;
            $postData['owner_email'] = $ownerEmail;
            $postData['owner_phone'] = $ownerPhone;
            $postData['owner_city'] = $ownerCity;

            $usersEmail = \app\models\Users::findAll(['LOWER(login_id)' => trim(strtolower($ownerEmail))]);
            //$usersPhone = \app\models\OwnerProfile::findAll(['phone' => trim($ownerPhone)]);
            if ($usersEmail) {
                Yii::$app->getSession()->setFlash('owner-form-submitted-err', 'Email Id Already Exists, Please Try With Different Email.');
                //} else if ($usersPhone) {
            } else if (false) {
                Yii::$app->getSession()->setFlash('owner-form-submitted-err', 'Phone Number Already Exists, Please Try With Different Number.');
            } else {
                $comments = new \app\models\Comments;
                $model = new \app\models\Users;
                $modelLeadsOwner = new \app\models\LeadsOwner;
                $modelOwnerProfile = new \app\models\OwnerProfile;

                $rand = Yii::$app->userdata->passwordGenerate();

                $model->full_name = $ownerName;
                $model->login_id = strtolower($ownerEmail);
                $model->username = strtolower($ownerEmail);
                $model->auth_key = base64_encode($ownerEmail . time() . rand(1000, 9999));
                $model->password = md5($rand);
                $model->user_type = 4;
                $model->created_date = date('Y-m-d h:i:s');
                $model->role = 0;
                $model->save(false);

                $ownerId = Yii::$app->db->getLastInsertID();

                $modelOwnerProfile->owner_id = $ownerId;
                $modelOwnerProfile->state = 0;
                $modelOwnerProfile->region = 0;
                $modelOwnerProfile->pincode = 0;
                $modelOwnerProfile->phone = $ownerPhone;
                $modelOwnerProfile->address_line_1 = " ";
                $modelOwnerProfile->address_line_2 = " ";
                $modelOwnerProfile->save(false);

                $comments->user_id = $ownerId;
                $comments->property_id = 0;
                $comments->created_by = $ownerId;
                $comments->save(false);

                $modelLeadsOwner->full_name = $ownerName;
                $modelLeadsOwner->email_id = $ownerEmail;
                $modelLeadsOwner->contact_number = $ownerPhone;
                $modelLeadsOwner->address = " ";
                $modelLeadsOwner->address_line_2 = " ";
                $modelLeadsOwner->lead_city = $ownerCity;
                $modelLeadsOwner->lead_state = Yii::$app->userdata->getStateCodeByCityCode($ownerCity);
                $modelLeadsOwner->region = 0;
                $modelLeadsOwner->pincode = 0;
                $modelLeadsOwner->created_date = date('Y-m-d H:i:s');

                $modelLeadsOwner->save(false);

                $users = \app\models\Users::findAll(['user_type' => '7']);
                $userList = Array();
                foreach ($users as $key => $value) {
                    $userList[] = $value->login_id;
                }

                $toEmail = [];
                $ccEmail = [];

                $toEmail[] = Yii::$app->params['supportEmail'];

                if (!empty($ownerCity)) {
                    $opsModel = \app\models\OperationsProfile::find()->where(['role_code' => 'OPCTMG', 'role_value' => $ownerCity])->one();
                    if ($opsModel) {
                        $ccEmail[] = $opsModel->email;
                    }

                    $salesModel = \app\models\SalesProfile::find()->where(['role_code' => 'SLCTMG', 'role_value' => $ownerCity])->one();
                    if ($salesModel) {
                        $ccEmail[] = $salesModel->email;
                    }
                }

                $subject = "New property Owner Lead";
                $msg = "Hello,<br/><br/>New property owner lead for '" . $ownerName . "' has been created, please verify and assign the branch and sales person to this lead.<br/><br/>Contact details of property owner are $ownerPhone, $ownerEmail<br/>With Regards,<br/>Easyleases Team";
                if (!empty($toEmail)) {
                    Yii::$app->userdata->doMailWithCC(implode(',', $toEmail), implode(',', $ccEmail), '', $subject, $msg, []);
                    Yii::$app->getSession()->setFlash('owner-form-submitted', 'Thanks for your interest in partnering with Easyleases as a property owner. We will get in touch with you in the next 2 business days.');
                } else {
                    Yii::$app->getSession()->setFlash('owner-form-submitted', 'Something went wrong, please try after sometime.');
                }

                $postData['owner_name'] = null;
                $postData['owner_email'] = null;
                $postData['owner_phone'] = null;
                $postData['owner_city'] = null;
            }
        }

        return $this->render('launch_offers', ['postData' => $postData]);
    }
    
    public function actionThankyou() {
        header("Cache-Control: max-age=6480000"); //30days (60sec * 60min * 24hours * 30days)
        $this->view->params['head_title'] = "Rental Property Management Service For NRI's";
        $this->view->params['meta_desc'] = "Rental Property Management Service For NRI's, Property Management Services Bangalore, Tenant Management, Home Management, Real Estate Management, NRI";
        $this->view->params['meta_keywords'] = "";
        $this->layout = 'camp_layout';

        return $this->render('thank_you');
    }

    public function actionNrioffers() {
        header("Cache-Control: max-age=6480000"); //30days (60sec * 60min * 24hours * 30days)
        $this->view->params['head_title'] = "Rental Property Management Service For NRI's";
        $this->view->params['meta_desc'] = "Rental Property Management Service For NRI's, Property Management Services Bangalore, Tenant Management, Home Management, Real Estate Management, NRI";
        $this->view->params['meta_keywords'] = "";
        $this->layout = 'camp_layout';
        $postData = [];
        $postData['owner_name'] = null;
        $postData['owner_email'] = null;
        $postData['owner_phone'] = null;
        $postData['owner_city'] = null;
        if (!empty(Yii::$app->request->post('owner_phone')) && !empty(Yii::$app->request->post('owner_city'))) {
            $ownerName = Yii::$app->request->post('owner_name');
            $ownerEmail = Yii::$app->request->post('owner_email');
            $ownerPhone = Yii::$app->request->post('owner_phone');
            $ownerCity = Yii::$app->request->post('owner_city');

            $postData['owner_name'] = $ownerName;
            $postData['owner_email'] = $ownerEmail;
            $postData['owner_phone'] = $ownerPhone;
            $postData['owner_city'] = $ownerCity;

            $usersEmail = \app\models\Users::findAll(['LOWER(login_id)' => trim(strtolower($ownerEmail))]);
            //$usersPhone = \app\models\OwnerProfile::findAll(['phone' => trim($ownerPhone)]);
            if ($usersEmail) {
                Yii::$app->getSession()->setFlash('owner-form-submitted-err', 'Email Id Already Exists, Please Try With Different Email.');
                //} else if ($usersPhone) {
            } else if (false) {
                Yii::$app->getSession()->setFlash('owner-form-submitted-err', 'Phone Number Already Exists, Please Try With Different Number.');
            } else {
                $comments = new \app\models\Comments;
                $model = new \app\models\Users;
                $modelLeadsOwner = new \app\models\LeadsOwner;
                $modelOwnerProfile = new \app\models\OwnerProfile;

                $rand = Yii::$app->userdata->passwordGenerate();

                $model->full_name = $ownerName;
                $model->login_id = strtolower($ownerEmail);
                $model->username = strtolower($ownerEmail);
                $model->auth_key = base64_encode($ownerEmail . time() . rand(1000, 9999));
                $model->password = md5($rand);
                $model->user_type = 4;
                $model->created_date = date('Y-m-d h:i:s');
                $model->role = 0;
                //$model->save(false);

                $ownerId = Yii::$app->db->getLastInsertID();

                $modelOwnerProfile->owner_id = $ownerId;
                $modelOwnerProfile->state = 0;
                $modelOwnerProfile->region = 0;
                $modelOwnerProfile->pincode = 0;
                $modelOwnerProfile->phone = $ownerPhone;
                $modelOwnerProfile->address_line_1 = " ";
                $modelOwnerProfile->address_line_2 = " ";
                //$modelOwnerProfile->save(false);

                $comments->user_id = $ownerId;
                $comments->property_id = 0;
                $comments->created_by = $ownerId;
                //$comments->save(false);

                $modelLeadsOwner->full_name = $ownerName;
                $modelLeadsOwner->email_id = $ownerEmail;
                $modelLeadsOwner->contact_number = $ownerPhone;
                $modelLeadsOwner->address = " ";
                $modelLeadsOwner->address_line_2 = " ";
                $modelLeadsOwner->lead_city = $ownerCity;
                $modelLeadsOwner->lead_state = Yii::$app->userdata->getStateCodeByCityCode($ownerCity);
                $modelLeadsOwner->region = 0;
                $modelLeadsOwner->pincode = 0;
                $modelLeadsOwner->created_date = date('Y-m-d H:i:s');

                //$modelLeadsOwner->save(false);

                $users = \app\models\Users::findAll(['user_type' => '7']);
                $userList = Array();
                foreach ($users as $key => $value) {
                    $userList[] = $value->login_id;
                }

                $toEmail = [];
                $ccEmail = [];

                $toEmail[] = Yii::$app->params['supportEmail'];

                if (!empty($ownerCity)) {
                    $opsModel = \app\models\OperationsProfile::find()->where(['role_code' => 'OPCTMG', 'role_value' => $ownerCity])->one();
                    if ($opsModel) {
                        $ccEmail[] = $opsModel->email;
                    }

                    $salesModel = \app\models\SalesProfile::find()->where(['role_code' => 'SLCTMG', 'role_value' => $ownerCity])->one();
                    if ($salesModel) {
                        $ccEmail[] = $salesModel->email;
                    }
                }

                $subject = "New property Owner Lead";
                $msg = "Hello,<br/><br/>New property owner lead for '" . $ownerName . "' has been created, please verify and assign the branch and sales person to this lead.<br/><br/>Contact details of property owner are $ownerPhone, $ownerEmail<br/>With Regards,<br/>Easyleases Team";
                if (!empty($toEmail)) {
                    Yii::$app->userdata->doMailWithCC(implode(',', $toEmail), implode(',', $ccEmail), '', $subject, $msg, []);
                    Yii::$app->getSession()->setFlash('owner-form-submitted', 'Thanks for your interest in partnering with Easyleases as a property owner. We will get in touch with you in the next 2 business days.');
                } else {
                    Yii::$app->getSession()->setFlash('owner-form-submitted', 'Something went wrong, please try after sometime.');
                }

                $postData['owner_name'] = null;
                $postData['owner_email'] = null;
                $postData['owner_phone'] = null;
                $postData['owner_city'] = null;
                
                return $this->redirect(['/thankyou']);
            }
        }

        return $this->render('nri_campaign', ['postData' => $postData]);
    }

    public function actionTermsncondition() {
        $this->layout = 'camp_layout';
        return $this->render('camp_tnc');
    }
    
    
//##########################    APARTMENT MANAGEMENT COMPANIES IN BANGALORE #################################//
    
    
     public function actionApartmentmanagement() {
        header("Cache-Control: max-age=6480000"); //30days (60sec * 60min * 24hours * 30days)
        $this->view->params['head_title'] = "Apartment Management Companies in Bangalore | Property Management";
        $this->view->params['meta_desc'] = "Apartment Management Companies in Bangalore, Best Property Management Services in Bangalore, Apartment Property Management in Bangalore, Apartment Management Company";
        $this->view->params['meta_keywords'] = "Apartment Management Companies in Bangalore, Best Property Management Services in Bangalore, Apartment Property Management in Bangalore, Apartment Management Company";
        $this->layout = 'camp_layout';
        $postData = [];
        $postData['owner_name'] = null;
        $postData['owner_email'] = null;
        $postData['owner_phone'] = null;
        $postData['owner_city'] = null;
        if (!empty(Yii::$app->request->post('owner_phone')) && !empty(Yii::$app->request->post('owner_city'))) {
            $ownerName = Yii::$app->request->post('owner_name');
            $ownerEmail = Yii::$app->request->post('owner_email');
            $ownerPhone = Yii::$app->request->post('owner_phone');
            $ownerCity = Yii::$app->request->post('owner_city');

            $postData['owner_name'] = $ownerName;
            $postData['owner_email'] = $ownerEmail;
            $postData['owner_phone'] = $ownerPhone;
            $postData['owner_city'] = $ownerCity;

            $usersEmail = \app\models\Users::findAll(['LOWER(login_id)' => trim(strtolower($ownerEmail))]);
            //$usersPhone = \app\models\OwnerProfile::findAll(['phone' => trim($ownerPhone)]);
            if ($usersEmail) {
                Yii::$app->getSession()->setFlash('owner-form-submitted-err', 'Email Id Already Exists, Please Try With Different Email.');
                //} else if ($usersPhone) {
            } else if (false) {
                Yii::$app->getSession()->setFlash('owner-form-submitted-err', 'Phone Number Already Exists, Please Try With Different Number.');
            } else {
                $comments = new \app\models\Comments;
                $model = new \app\models\Users;
                $modelLeadsOwner = new \app\models\LeadsOwner;
                $modelOwnerProfile = new \app\models\OwnerProfile;

                $rand = Yii::$app->userdata->passwordGenerate();

                $model->full_name = $ownerName;
                $model->login_id = strtolower($ownerEmail);
                $model->username = strtolower($ownerEmail);
                $model->auth_key = base64_encode($ownerEmail . time() . rand(1000, 9999));
                $model->password = md5($rand);
                $model->user_type = 4;
                $model->created_date = date('Y-m-d h:i:s');
                $model->role = 0;
                $model->save(false);

                $ownerId = Yii::$app->db->getLastInsertID();

                $modelOwnerProfile->owner_id = $ownerId;
                $modelOwnerProfile->state = 0;
                $modelOwnerProfile->region = 0;
                $modelOwnerProfile->pincode = 0;
                $modelOwnerProfile->phone = $ownerPhone;
                $modelOwnerProfile->address_line_1 = " ";
                $modelOwnerProfile->address_line_2 = " ";
                $modelOwnerProfile->save(false);

                $comments->user_id = $ownerId;
                $comments->property_id = 0;
                $comments->created_by = $ownerId;
                $comments->save(false);

                $modelLeadsOwner->full_name = $ownerName;
                $modelLeadsOwner->email_id = $ownerEmail;
                $modelLeadsOwner->contact_number = $ownerPhone;
                $modelLeadsOwner->address = " ";
                $modelLeadsOwner->address_line_2 = " ";
                $modelLeadsOwner->lead_city = $ownerCity;
                $modelLeadsOwner->lead_state = Yii::$app->userdata->getStateCodeByCityCode($ownerCity);
                $modelLeadsOwner->region = 0;
                $modelLeadsOwner->pincode = 0;
                $modelLeadsOwner->created_date = date('Y-m-d H:i:s');

                $modelLeadsOwner->save(false);

                $users = \app\models\Users::findAll(['user_type' => '7']);
                $userList = Array();
                foreach ($users as $key => $value) {
                    $userList[] = $value->login_id;
                }

                $toEmail = [];
                $ccEmail = [];

                $toEmail[] = Yii::$app->params['supportEmail'];

                if (!empty($ownerCity)) {
                    $opsModel = \app\models\OperationsProfile::find()->where(['role_code' => 'OPCTMG', 'role_value' => $ownerCity])->one();
                    if ($opsModel) {
                        $ccEmail[] = $opsModel->email;
                    }

                    $salesModel = \app\models\SalesProfile::find()->where(['role_code' => 'SLCTMG', 'role_value' => $ownerCity])->one();
                    if ($salesModel) {
                        $ccEmail[] = $salesModel->email;
                    }
                }

                $subject = "New property Owner Lead";
                $msg = "Hello,<br/><br/>New property owner lead for '" . $ownerName . "' has been created, please verify and assign the branch and sales person to this lead.<br/><br/>Contact details of property owner are $ownerPhone, $ownerEmail<br/>With Regards,<br/>Easyleases Team";
                if (!empty($toEmail)) {
                    Yii::$app->userdata->doMailWithCC(implode(',', $toEmail), implode(',', $ccEmail), '', $subject, $msg, []);
                    Yii::$app->getSession()->setFlash('owner-form-submitted', 'Thanks for your interest in partnering with Easyleases as a property owner. We will get in touch with you in the next 2 business days.');
                } else {
                    Yii::$app->getSession()->setFlash('owner-form-submitted', 'Something went wrong, please try after sometime.');
                }

                $postData['owner_name'] = null;
                $postData['owner_email'] = null;
                $postData['owner_phone'] = null;
                $postData['owner_city'] = null;
                
                return $this->redirect(['/thankyou']);
            }
        }

        return $this->render('apartment_management', ['postData' => $postData]);
    }
    
    
//    #############################    REAL ESTATE MANAGEMENT COMPANIES IN BANGALORE     ##############################//
    
      public function actionRealestatemanagement() {
        header("Cache-Control: max-age=6480000"); //30days (60sec * 60min * 24hours * 30days)
        $this->view->params['head_title'] = "Easyleases | Real Estate Management Companies In Bangalore";
        $this->view->params['meta_desc'] = "Launch Offers - Easyleases, Property Management Services, Property Management Services Bangalore, Tenant Management, Home Management, Real Estate Management, Apertment Mangement, Residential Management";
        $this->view->params['meta_keywords'] = "";
        $this->layout = 'camp_layout';
        $postData = [];
        $postData['owner_name'] = null;
        $postData['owner_email'] = null;
        $postData['owner_phone'] = null;
        $postData['owner_city'] = null;
        if (!empty(Yii::$app->request->post('owner_phone')) && !empty(Yii::$app->request->post('owner_city'))) {
            $ownerName = Yii::$app->request->post('owner_name');
            $ownerEmail = Yii::$app->request->post('owner_email');
            $ownerPhone = Yii::$app->request->post('owner_phone');
            $ownerCity = Yii::$app->request->post('owner_city');

            $postData['owner_name'] = $ownerName;
            $postData['owner_email'] = $ownerEmail;
            $postData['owner_phone'] = $ownerPhone;
            $postData['owner_city'] = $ownerCity;

            $usersEmail = \app\models\Users::findAll(['LOWER(login_id)' => trim(strtolower($ownerEmail))]);
            //$usersPhone = \app\models\OwnerProfile::findAll(['phone' => trim($ownerPhone)]);
            if ($usersEmail) {
                Yii::$app->getSession()->setFlash('owner-form-submitted-err', 'Email Id Already Exists, Please Try With Different Email.');
                //} else if ($usersPhone) {
            } else if (false) {
                Yii::$app->getSession()->setFlash('owner-form-submitted-err', 'Phone Number Already Exists, Please Try With Different Number.');
            } else {
                $comments = new \app\models\Comments;
                $model = new \app\models\Users;
                $modelLeadsOwner = new \app\models\LeadsOwner;
                $modelOwnerProfile = new \app\models\OwnerProfile;

                $rand = Yii::$app->userdata->passwordGenerate();

                $model->full_name = $ownerName;
                $model->login_id = strtolower($ownerEmail);
                $model->username = strtolower($ownerEmail);
                $model->auth_key = base64_encode($ownerEmail . time() . rand(1000, 9999));
                $model->password = md5($rand);
                $model->user_type = 4;
                $model->created_date = date('Y-m-d h:i:s');
                $model->role = 0;
                $model->save(false);

                $ownerId = Yii::$app->db->getLastInsertID();

                $modelOwnerProfile->owner_id = $ownerId;
                $modelOwnerProfile->state = 0;
                $modelOwnerProfile->region = 0;
                $modelOwnerProfile->pincode = 0;
                $modelOwnerProfile->phone = $ownerPhone;
                $modelOwnerProfile->address_line_1 = " ";
                $modelOwnerProfile->address_line_2 = " ";
                $modelOwnerProfile->save(false);

                $comments->user_id = $ownerId;
                $comments->property_id = 0;
                $comments->created_by = $ownerId;
                $comments->save(false);

                $modelLeadsOwner->full_name = $ownerName;
                $modelLeadsOwner->email_id = $ownerEmail;
                $modelLeadsOwner->contact_number = $ownerPhone;
                $modelLeadsOwner->address = " ";
                $modelLeadsOwner->address_line_2 = " ";
                $modelLeadsOwner->lead_city = $ownerCity;
                $modelLeadsOwner->lead_state = Yii::$app->userdata->getStateCodeByCityCode($ownerCity);
                $modelLeadsOwner->region = 0;
                $modelLeadsOwner->pincode = 0;
                $modelLeadsOwner->created_date = date('Y-m-d H:i:s');

                $modelLeadsOwner->save(false);

                $users = \app\models\Users::findAll(['user_type' => '7']);
                $userList = Array();
                foreach ($users as $key => $value) {
                    $userList[] = $value->login_id;
                }

                $toEmail = [];
                $ccEmail = [];

                $toEmail[] = Yii::$app->params['supportEmail'];

                if (!empty($ownerCity)) {
                    $opsModel = \app\models\OperationsProfile::find()->where(['role_code' => 'OPCTMG', 'role_value' => $ownerCity])->one();
                    if ($opsModel) {
                        $ccEmail[] = $opsModel->email;
                    }

                    $salesModel = \app\models\SalesProfile::find()->where(['role_code' => 'SLCTMG', 'role_value' => $ownerCity])->one();
                    if ($salesModel) {
                        $ccEmail[] = $salesModel->email;
                    }
                }

                $subject = "New property Owner Lead";
                $msg = "Hello,<br/><br/>New property owner lead for '" . $ownerName . "' has been created, please verify and assign the branch and sales person to this lead.<br/><br/>Contact details of property owner are $ownerPhone, $ownerEmail<br/>With Regards,<br/>Easyleases Team";
                if (!empty($toEmail)) {
                    Yii::$app->userdata->doMailWithCC(implode(',', $toEmail), implode(',', $ccEmail), '', $subject, $msg, []);
                    Yii::$app->getSession()->setFlash('owner-form-submitted', 'Thanks for your interest in partnering with Easyleases as a property owner. We will get in touch with you in the next 2 business days.');
                } else {
                    Yii::$app->getSession()->setFlash('owner-form-submitted', 'Something went wrong, please try after sometime.');
                }

                $postData['owner_name'] = null;
                $postData['owner_email'] = null;
                $postData['owner_phone'] = null;
                $postData['owner_city'] = null;
                
                return $this->redirect(['/thankyou']);
            }
        }

        return $this->render('Realestate_management', ['postData' => $postData]);
    }
    
//#############################    HOUSE RENTAL WEBSITES IN BANGALORE    ###################################//
    
    
    public function actionHouserental() {
        header("Cache-Control: max-age=6480000"); //30days (60sec * 60min * 24hours * 30days)
        $this->view->params['head_title'] = "House Rental Websites in Bangalore | Rental Management Companies";
        $this->view->params['meta_desc'] = "House Rental Websites in Bangalore, Rental Management Companies in Bangalore, Tenant Management Services Bangalore, House Rental Management Websites in Bangalore";
        $this->view->params['meta_keywords'] = "";
        $this->layout = 'camp_layout';
        $postData = [];
        $postData['owner_name'] = null;
        $postData['owner_email'] = null;
        $postData['owner_phone'] = null;
        $postData['owner_city'] = null;
        if (!empty(Yii::$app->request->post('owner_phone')) && !empty(Yii::$app->request->post('owner_city'))) {
            $ownerName = Yii::$app->request->post('owner_name');
            $ownerEmail = Yii::$app->request->post('owner_email');
            $ownerPhone = Yii::$app->request->post('owner_phone');
            $ownerCity = Yii::$app->request->post('owner_city');

            $postData['owner_name'] = $ownerName;
            $postData['owner_email'] = $ownerEmail;
            $postData['owner_phone'] = $ownerPhone;
            $postData['owner_city'] = $ownerCity;

            $usersEmail = \app\models\Users::findAll(['LOWER(login_id)' => trim(strtolower($ownerEmail))]);
            //$usersPhone = \app\models\OwnerProfile::findAll(['phone' => trim($ownerPhone)]);
            if ($usersEmail) {
                Yii::$app->getSession()->setFlash('owner-form-submitted-err', 'Email Id Already Exists, Please Try With Different Email.');
                //} else if ($usersPhone) {
            } else if (false) {
                Yii::$app->getSession()->setFlash('owner-form-submitted-err', 'Phone Number Already Exists, Please Try With Different Number.');
            } else {
                $comments = new \app\models\Comments;
                $model = new \app\models\Users;
                $modelLeadsOwner = new \app\models\LeadsOwner;
                $modelOwnerProfile = new \app\models\OwnerProfile;

                $rand = Yii::$app->userdata->passwordGenerate();

                $model->full_name = $ownerName;
                $model->login_id = strtolower($ownerEmail);
                $model->username = strtolower($ownerEmail);
                $model->auth_key = base64_encode($ownerEmail . time() . rand(1000, 9999));
                $model->password = md5($rand);
                $model->user_type = 4;
                $model->created_date = date('Y-m-d h:i:s');
                $model->role = 0;
                $model->save(false);

                $ownerId = Yii::$app->db->getLastInsertID();

                $modelOwnerProfile->owner_id = $ownerId;
                $modelOwnerProfile->state = 0;
                $modelOwnerProfile->region = 0;
                $modelOwnerProfile->pincode = 0;
                $modelOwnerProfile->phone = $ownerPhone;
                $modelOwnerProfile->address_line_1 = " ";
                $modelOwnerProfile->address_line_2 = " ";
                $modelOwnerProfile->save(false);

                $comments->user_id = $ownerId;
                $comments->property_id = 0;
                $comments->created_by = $ownerId;
                $comments->save(false);

                $modelLeadsOwner->full_name = $ownerName;
                $modelLeadsOwner->email_id = $ownerEmail;
                $modelLeadsOwner->contact_number = $ownerPhone;
                $modelLeadsOwner->address = " ";
                $modelLeadsOwner->address_line_2 = " ";
                $modelLeadsOwner->lead_city = $ownerCity;
                $modelLeadsOwner->lead_state = Yii::$app->userdata->getStateCodeByCityCode($ownerCity);
                $modelLeadsOwner->region = 0;
                $modelLeadsOwner->pincode = 0;
                $modelLeadsOwner->created_date = date('Y-m-d H:i:s');

                $modelLeadsOwner->save(false);

                $users = \app\models\Users::findAll(['user_type' => '7']);
                $userList = Array();
                foreach ($users as $key => $value) {
                    $userList[] = $value->login_id;
                }

                $toEmail = [];
                $ccEmail = [];

                $toEmail[] = Yii::$app->params['supportEmail'];

                if (!empty($ownerCity)) {
                    $opsModel = \app\models\OperationsProfile::find()->where(['role_code' => 'OPCTMG', 'role_value' => $ownerCity])->one();
                    if ($opsModel) {
                        $ccEmail[] = $opsModel->email;
                    }

                    $salesModel = \app\models\SalesProfile::find()->where(['role_code' => 'SLCTMG', 'role_value' => $ownerCity])->one();
                    if ($salesModel) {
                        $ccEmail[] = $salesModel->email;
                    }
                }

                $subject = "New property Owner Lead";
                $msg = "Hello,<br/><br/>New property owner lead for '" . $ownerName . "' has been created, please verify and assign the branch and sales person to this lead.<br/><br/>Contact details of property owner are $ownerPhone, $ownerEmail<br/>With Regards,<br/>Easyleases Team";
                if (!empty($toEmail)) {
                    Yii::$app->userdata->doMailWithCC(implode(',', $toEmail), implode(',', $ccEmail), '', $subject, $msg, []);
                    Yii::$app->getSession()->setFlash('owner-form-submitted', 'Thanks for your interest in partnering with Easyleases as a property owner. We will get in touch with you in the next 2 business days.');
                } else {
                    Yii::$app->getSession()->setFlash('owner-form-submitted', 'Something went wrong, please try after sometime.');
                }

                $postData['owner_name'] = null;
                $postData['owner_email'] = null;
                $postData['owner_phone'] = null;
                $postData['owner_city'] = null;
                
                return $this->redirect(['/thankyou']);
            }
        }

        return $this->render('house_rental', ['postData' => $postData]);
    }
    
    
//  ##################################  RENTAL MANAGEMENT COMPANES IN BANGALORE   ########################  //
    
    
    
    public function actionRentalmanagement() {
        header("Cache-Control: max-age=6480000"); //30days (60sec * 60min * 24hours * 30days)
        $this->view->params['head_title'] = "Easyleases | Rental Management Companes In Bangalore";
        $this->view->params['meta_desc'] = "Launch Offers - Easyleases, Property Management Services, Property Management Services Bangalore, Tenant Management, Home Management, Real Estate Management, Apertment Mangement, Residential Management";
        $this->view->params['meta_keywords'] = "";
        $this->layout = 'camp_layout';
        $postData = [];
        $postData['owner_name'] = null;
        $postData['owner_email'] = null;
        $postData['owner_phone'] = null;
        $postData['owner_city'] = null;
        if (!empty(Yii::$app->request->post('owner_phone')) && !empty(Yii::$app->request->post('owner_city'))) {
            $ownerName = Yii::$app->request->post('owner_name');
            $ownerEmail = Yii::$app->request->post('owner_email');
            $ownerPhone = Yii::$app->request->post('owner_phone');
            $ownerCity = Yii::$app->request->post('owner_city');

            $postData['owner_name'] = $ownerName;
            $postData['owner_email'] = $ownerEmail;
            $postData['owner_phone'] = $ownerPhone;
            $postData['owner_city'] = $ownerCity;

            $usersEmail = \app\models\Users::findAll(['LOWER(login_id)' => trim(strtolower($ownerEmail))]);
            //$usersPhone = \app\models\OwnerProfile::findAll(['phone' => trim($ownerPhone)]);
            if ($usersEmail) {
                Yii::$app->getSession()->setFlash('owner-form-submitted-err', 'Email Id Already Exists, Please Try With Different Email.');
                //} else if ($usersPhone) {
            } else if (false) {
                Yii::$app->getSession()->setFlash('owner-form-submitted-err', 'Phone Number Already Exists, Please Try With Different Number.');
            } else {
                $comments = new \app\models\Comments;
                $model = new \app\models\Users;
                $modelLeadsOwner = new \app\models\LeadsOwner;
                $modelOwnerProfile = new \app\models\OwnerProfile;

                $rand = Yii::$app->userdata->passwordGenerate();

                $model->full_name = $ownerName;
                $model->login_id = strtolower($ownerEmail);
                $model->username = strtolower($ownerEmail);
                $model->auth_key = base64_encode($ownerEmail . time() . rand(1000, 9999));
                $model->password = md5($rand);
                $model->user_type = 4;
                $model->created_date = date('Y-m-d h:i:s');
                $model->role = 0;
                $model->save(false);

                $ownerId = Yii::$app->db->getLastInsertID();

                $modelOwnerProfile->owner_id = $ownerId;
                $modelOwnerProfile->state = 0;
                $modelOwnerProfile->region = 0;
                $modelOwnerProfile->pincode = 0;
                $modelOwnerProfile->phone = $ownerPhone;
                $modelOwnerProfile->address_line_1 = " ";
                $modelOwnerProfile->address_line_2 = " ";
                $modelOwnerProfile->save(false);

                $comments->user_id = $ownerId;
                $comments->property_id = 0;
                $comments->created_by = $ownerId;
                $comments->save(false);

                $modelLeadsOwner->full_name = $ownerName;
                $modelLeadsOwner->email_id = $ownerEmail;
                $modelLeadsOwner->contact_number = $ownerPhone;
                $modelLeadsOwner->address = " ";
                $modelLeadsOwner->address_line_2 = " ";
                $modelLeadsOwner->lead_city = $ownerCity;
                $modelLeadsOwner->lead_state = Yii::$app->userdata->getStateCodeByCityCode($ownerCity);
                $modelLeadsOwner->region = 0;
                $modelLeadsOwner->pincode = 0;
                $modelLeadsOwner->created_date = date('Y-m-d H:i:s');

                $modelLeadsOwner->save(false);

                $users = \app\models\Users::findAll(['user_type' => '7']);
                $userList = Array();
                foreach ($users as $key => $value) {
                    $userList[] = $value->login_id;
                }

                $toEmail = [];
                $ccEmail = [];

                $toEmail[] = Yii::$app->params['supportEmail'];

                if (!empty($ownerCity)) {
                    $opsModel = \app\models\OperationsProfile::find()->where(['role_code' => 'OPCTMG', 'role_value' => $ownerCity])->one();
                    if ($opsModel) {
                        $ccEmail[] = $opsModel->email;
                    }

                    $salesModel = \app\models\SalesProfile::find()->where(['role_code' => 'SLCTMG', 'role_value' => $ownerCity])->one();
                    if ($salesModel) {
                        $ccEmail[] = $salesModel->email;
                    }
                }

                $subject = "New property Owner Lead";
                $msg = "Hello,<br/><br/>New property owner lead for '" . $ownerName . "' has been created, please verify and assign the branch and sales person to this lead.<br/><br/>Contact details of property owner are $ownerPhone, $ownerEmail<br/>With Regards,<br/>Easyleases Team";
                if (!empty($toEmail)) {
                    Yii::$app->userdata->doMailWithCC(implode(',', $toEmail), implode(',', $ccEmail), '', $subject, $msg, []);
                    Yii::$app->getSession()->setFlash('owner-form-submitted', 'Thanks for your interest in partnering with Easyleases as a property owner. We will get in touch with you in the next 2 business days.');
                } else {
                    Yii::$app->getSession()->setFlash('owner-form-submitted', 'Something went wrong, please try after sometime.');
                }

                $postData['owner_name'] = null;
                $postData['owner_email'] = null;
                $postData['owner_phone'] = null;
                $postData['owner_city'] = null;
            }
        }

        return $this->render('rental_management', ['postData' => $postData]);
    }
    
    
//################################# Residential Property Management Services Bangalore  ############################//
    
     public function actionResidentalpropertymanagement() {
        header("Cache-Control: max-age=6480000"); //30days (60sec * 60min * 24hours * 30days)
        $this->view->params['head_title'] = "Property Management in Bangalore | Property Maintenance Company";
        $this->view->params['meta_desc'] = "Property Management in Bangalore, Property Maintenance Company in Bangalore, Residential Property Management Services Bangalore, Realty Property Management Services";
        $this->view->params['meta_keywords'] = "";
        $this->layout = 'camp_layout';
        $postData = [];
        $postData['owner_name'] = null;
        $postData['owner_email'] = null;
        $postData['owner_phone'] = null;
        $postData['owner_city'] = null;
        if (!empty(Yii::$app->request->post('owner_phone')) && !empty(Yii::$app->request->post('owner_city'))) {
            $ownerName = Yii::$app->request->post('owner_name');
            $ownerEmail = Yii::$app->request->post('owner_email');
            $ownerPhone = Yii::$app->request->post('owner_phone');
            $ownerCity = Yii::$app->request->post('owner_city');

            $postData['owner_name'] = $ownerName;
            $postData['owner_email'] = $ownerEmail;
            $postData['owner_phone'] = $ownerPhone;
            $postData['owner_city'] = $ownerCity;

            $usersEmail = \app\models\Users::findAll(['LOWER(login_id)' => trim(strtolower($ownerEmail))]);
            //$usersPhone = \app\models\OwnerProfile::findAll(['phone' => trim($ownerPhone)]);
            if ($usersEmail) {
                Yii::$app->getSession()->setFlash('owner-form-submitted-err', 'Email Id Already Exists, Please Try With Different Email.');
                //} else if ($usersPhone) {
            } else if (false) {
                Yii::$app->getSession()->setFlash('owner-form-submitted-err', 'Phone Number Already Exists, Please Try With Different Number.');
            } else {
                $comments = new \app\models\Comments;
                $model = new \app\models\Users;
                $modelLeadsOwner = new \app\models\LeadsOwner;
                $modelOwnerProfile = new \app\models\OwnerProfile;

                $rand = Yii::$app->userdata->passwordGenerate();

                $model->full_name = $ownerName;
                $model->login_id = strtolower($ownerEmail);
                $model->username = strtolower($ownerEmail);
                $model->auth_key = base64_encode($ownerEmail . time() . rand(1000, 9999));
                $model->password = md5($rand);
                $model->user_type = 4;
                $model->created_date = date('Y-m-d h:i:s');
                $model->role = 0;
                $model->save(false);

                $ownerId = Yii::$app->db->getLastInsertID();

                $modelOwnerProfile->owner_id = $ownerId;
                $modelOwnerProfile->state = 0;
                $modelOwnerProfile->region = 0;
                $modelOwnerProfile->pincode = 0;
                $modelOwnerProfile->phone = $ownerPhone;
                $modelOwnerProfile->address_line_1 = " ";
                $modelOwnerProfile->address_line_2 = " ";
                $modelOwnerProfile->save(false);

                $comments->user_id = $ownerId;
                $comments->property_id = 0;
                $comments->created_by = $ownerId;
                $comments->save(false);

                $modelLeadsOwner->full_name = $ownerName;
                $modelLeadsOwner->email_id = $ownerEmail;
                $modelLeadsOwner->contact_number = $ownerPhone;
                $modelLeadsOwner->address = " ";
                $modelLeadsOwner->address_line_2 = " ";
                $modelLeadsOwner->lead_city = $ownerCity;
                $modelLeadsOwner->lead_state = Yii::$app->userdata->getStateCodeByCityCode($ownerCity);
                $modelLeadsOwner->region = 0;
                $modelLeadsOwner->pincode = 0;
                $modelLeadsOwner->created_date = date('Y-m-d H:i:s');

                $modelLeadsOwner->save(false);

                $users = \app\models\Users::findAll(['user_type' => '7']);
                $userList = Array();
                foreach ($users as $key => $value) {
                    $userList[] = $value->login_id;
                }

                $toEmail = [];
                $ccEmail = [];

                $toEmail[] = Yii::$app->params['supportEmail'];

                if (!empty($ownerCity)) {
                    $opsModel = \app\models\OperationsProfile::find()->where(['role_code' => 'OPCTMG', 'role_value' => $ownerCity])->one();
                    if ($opsModel) {
                        $ccEmail[] = $opsModel->email;
                    }

                    $salesModel = \app\models\SalesProfile::find()->where(['role_code' => 'SLCTMG', 'role_value' => $ownerCity])->one();
                    if ($salesModel) {
                        $ccEmail[] = $salesModel->email;
                    }
                }

                $subject = "New property Owner Lead";
                $msg = "Hello,<br/><br/>New property owner lead for '" . $ownerName . "' has been created, please verify and assign the branch and sales person to this lead.<br/><br/>Contact details of property owner are $ownerPhone, $ownerEmail<br/>With Regards,<br/>Easyleases Team";
                if (!empty($toEmail)) {
                    Yii::$app->userdata->doMailWithCC(implode(',', $toEmail), implode(',', $ccEmail), '', $subject, $msg, []);
                    Yii::$app->getSession()->setFlash('owner-form-submitted', 'Thanks for your interest in partnering with Easyleases as a property owner. We will get in touch with you in the next 2 business days.');
                } else {
                    Yii::$app->getSession()->setFlash('owner-form-submitted', 'Something went wrong, please try after sometime.');
                }

                $postData['owner_name'] = null;
                $postData['owner_email'] = null;
                $postData['owner_phone'] = null;
                $postData['owner_city'] = null;
                
                return $this->redirect(['/thankyou']);
            }
        }

        return $this->render('residental_property_management', ['postData' => $postData]);
    }
    
    
    //#################### How property management companies can be helps to rental home owners  ############################//
    
     public function actionHelptorentalhomeowners() {
        header("Cache-Control: max-age=6480000"); //30days (60sec * 60min * 24hours * 30days)
        $this->view->params['head_title'] = "Rental Management Companies in Bangalore | Property Management Service";
        $this->view->params['meta_desc'] = "Rental Management Companies in Bangalore, Rental Property Management Services in Bangalore, Rental Property Management Services, Residential Property Management Services";
        $this->view->params['meta_keywords'] = "Rental Management Companies in Bangalore, Rental Property Management Services in Bangalore, Rental Property Management Services, Residential Property Management Services";
        $this->layout = 'camp_layout';
        $postData = [];
        $postData['owner_name'] = null;
        $postData['owner_email'] = null;
        $postData['owner_phone'] = null;
        $postData['owner_city'] = null;
        if (!empty(Yii::$app->request->post('owner_phone')) && !empty(Yii::$app->request->post('owner_city'))) {
            $ownerName = Yii::$app->request->post('owner_name');
            $ownerEmail = Yii::$app->request->post('owner_email');
            $ownerPhone = Yii::$app->request->post('owner_phone');
            $ownerCity = Yii::$app->request->post('owner_city');

            $postData['owner_name'] = $ownerName;
            $postData['owner_email'] = $ownerEmail;
            $postData['owner_phone'] = $ownerPhone;
            $postData['owner_city'] = $ownerCity;

            $usersEmail = \app\models\Users::findAll(['LOWER(login_id)' => trim(strtolower($ownerEmail))]);
            //$usersPhone = \app\models\OwnerProfile::findAll(['phone' => trim($ownerPhone)]);
            if ($usersEmail) {
                Yii::$app->getSession()->setFlash('owner-form-submitted-err', 'Email Id Already Exists, Please Try With Different Email.');
                //} else if ($usersPhone) {
            } else if (false) {
                Yii::$app->getSession()->setFlash('owner-form-submitted-err', 'Phone Number Already Exists, Please Try With Different Number.');
            } else {
                $comments = new \app\models\Comments;
                $model = new \app\models\Users;
                $modelLeadsOwner = new \app\models\LeadsOwner;
                $modelOwnerProfile = new \app\models\OwnerProfile;

                $rand = Yii::$app->userdata->passwordGenerate();

                $model->full_name = $ownerName;
                $model->login_id = strtolower($ownerEmail);
                $model->username = strtolower($ownerEmail);
                $model->auth_key = base64_encode($ownerEmail . time() . rand(1000, 9999));
                $model->password = md5($rand);
                $model->user_type = 4;
                $model->created_date = date('Y-m-d h:i:s');
                $model->role = 0;
                $model->save(false);

                $ownerId = Yii::$app->db->getLastInsertID();

                $modelOwnerProfile->owner_id = $ownerId;
                $modelOwnerProfile->state = 0;
                $modelOwnerProfile->region = 0;
                $modelOwnerProfile->pincode = 0;
                $modelOwnerProfile->phone = $ownerPhone;
                $modelOwnerProfile->address_line_1 = " ";
                $modelOwnerProfile->address_line_2 = " ";
                $modelOwnerProfile->save(false);

                $comments->user_id = $ownerId;
                $comments->property_id = 0;
                $comments->created_by = $ownerId;
                $comments->save(false);

                $modelLeadsOwner->full_name = $ownerName;
                $modelLeadsOwner->email_id = $ownerEmail;
                $modelLeadsOwner->contact_number = $ownerPhone;
                $modelLeadsOwner->address = " ";
                $modelLeadsOwner->address_line_2 = " ";
                $modelLeadsOwner->lead_city = $ownerCity;
                $modelLeadsOwner->lead_state = Yii::$app->userdata->getStateCodeByCityCode($ownerCity);
                $modelLeadsOwner->region = 0;
                $modelLeadsOwner->pincode = 0;
                $modelLeadsOwner->created_date = date('Y-m-d H:i:s');

                $modelLeadsOwner->save(false);

                $users = \app\models\Users::findAll(['user_type' => '7']);
                $userList = Array();
                foreach ($users as $key => $value) {
                    $userList[] = $value->login_id;
                }

                $toEmail = [];
                $ccEmail = [];

                $toEmail[] = Yii::$app->params['supportEmail'];

                if (!empty($ownerCity)) {
                    $opsModel = \app\models\OperationsProfile::find()->where(['role_code' => 'OPCTMG', 'role_value' => $ownerCity])->one();
                    if ($opsModel) {
                        $ccEmail[] = $opsModel->email;
                    }

                    $salesModel = \app\models\SalesProfile::find()->where(['role_code' => 'SLCTMG', 'role_value' => $ownerCity])->one();
                    if ($salesModel) {
                        $ccEmail[] = $salesModel->email;
                    }
                }

                $subject = "New property Owner Lead";
                $msg = "Hello,<br/><br/>New property owner lead for '" . $ownerName . "' has been created, please verify and assign the branch and sales person to this lead.<br/><br/>Contact details of property owner are $ownerPhone, $ownerEmail<br/>With Regards,<br/>Easyleases Team";
                if (!empty($toEmail)) {
                    Yii::$app->userdata->doMailWithCC(implode(',', $toEmail), implode(',', $ccEmail), '', $subject, $msg, []);
                    Yii::$app->getSession()->setFlash('owner-form-submitted', 'Thanks for your interest in partnering with Easyleases as a property owner. We will get in touch with you in the next 2 business days.');
                } else {
                    Yii::$app->getSession()->setFlash('owner-form-submitted', 'Something went wrong, please try after sometime.');
                }

                $postData['owner_name'] = null;
                $postData['owner_email'] = null;
                $postData['owner_phone'] = null;
                $postData['owner_city'] = null;
                
                return $this->redirect(['/thankyou']);
            }
        }

        return $this->render('helptorentalhomeowners', ['postData' => $postData]);
    }
    
    
    //#################### Good Apartment Management Companies in Bangalore  ############################//
    
     public function actionGoodapartmentmanagementcompanies() {
        header("Cache-Control: max-age=6480000"); //30days (60sec * 60min * 24hours * 30days)
        $this->view->params['head_title'] = "Easyleases | Residential Property Management Service Bangalore";
        $this->view->params['meta_desc'] = "Launch Offers - Easyleases, Property Management Services, Property Management Services Bangalore, Tenant Management, Home Management, Real Estate Management, Apertment Mangement, Residential Management";
        $this->layout = 'camp_layout';
        $postData = [];
        $postData['owner_name'] = null;
        $postData['owner_email'] = null;
        $postData['owner_phone'] = null;
        $postData['owner_city'] = null;
        if (!empty(Yii::$app->request->post('owner_phone')) && !empty(Yii::$app->request->post('owner_city'))) {
            $ownerName = Yii::$app->request->post('owner_name');
            $ownerEmail = Yii::$app->request->post('owner_email');
            $ownerPhone = Yii::$app->request->post('owner_phone');
            $ownerCity = Yii::$app->request->post('owner_city');

            $postData['owner_name'] = $ownerName;
            $postData['owner_email'] = $ownerEmail;
            $postData['owner_phone'] = $ownerPhone;
            $postData['owner_city'] = $ownerCity;

            $usersEmail = \app\models\Users::findAll(['LOWER(login_id)' => trim(strtolower($ownerEmail))]);
            //$usersPhone = \app\models\OwnerProfile::findAll(['phone' => trim($ownerPhone)]);
            if ($usersEmail) {
                Yii::$app->getSession()->setFlash('owner-form-submitted-err', 'Email Id Already Exists, Please Try With Different Email.');
                //} else if ($usersPhone) {
            } else if (false) {
                Yii::$app->getSession()->setFlash('owner-form-submitted-err', 'Phone Number Already Exists, Please Try With Different Number.');
            } else {
                $comments = new \app\models\Comments;
                $model = new \app\models\Users;
                $modelLeadsOwner = new \app\models\LeadsOwner;
                $modelOwnerProfile = new \app\models\OwnerProfile;

                $rand = Yii::$app->userdata->passwordGenerate();

                $model->full_name = $ownerName;
                $model->login_id = strtolower($ownerEmail);
                $model->username = strtolower($ownerEmail);
                $model->auth_key = base64_encode($ownerEmail . time() . rand(1000, 9999));
                $model->password = md5($rand);
                $model->user_type = 4;
                $model->created_date = date('Y-m-d h:i:s');
                $model->role = 0;
                $model->save(false);

                $ownerId = Yii::$app->db->getLastInsertID();

                $modelOwnerProfile->owner_id = $ownerId;
                $modelOwnerProfile->state = 0;
                $modelOwnerProfile->region = 0;
                $modelOwnerProfile->pincode = 0;
                $modelOwnerProfile->phone = $ownerPhone;
                $modelOwnerProfile->address_line_1 = " ";
                $modelOwnerProfile->address_line_2 = " ";
                $modelOwnerProfile->save(false);

                $comments->user_id = $ownerId;
                $comments->property_id = 0;
                $comments->created_by = $ownerId;
                $comments->save(false);

                $modelLeadsOwner->full_name = $ownerName;
                $modelLeadsOwner->email_id = $ownerEmail;
                $modelLeadsOwner->contact_number = $ownerPhone;
                $modelLeadsOwner->address = " ";
                $modelLeadsOwner->address_line_2 = " ";
                $modelLeadsOwner->lead_city = $ownerCity;
                $modelLeadsOwner->lead_state = Yii::$app->userdata->getStateCodeByCityCode($ownerCity);
                $modelLeadsOwner->region = 0;
                $modelLeadsOwner->pincode = 0;
                $modelLeadsOwner->created_date = date('Y-m-d H:i:s');

                $modelLeadsOwner->save(false);

                $users = \app\models\Users::findAll(['user_type' => '7']);
                $userList = Array();
                foreach ($users as $key => $value) {
                    $userList[] = $value->login_id;
                }

                $toEmail = [];
                $ccEmail = [];

                $toEmail[] = Yii::$app->params['supportEmail'];

                if (!empty($ownerCity)) {
                    $opsModel = \app\models\OperationsProfile::find()->where(['role_code' => 'OPCTMG', 'role_value' => $ownerCity])->one();
                    if ($opsModel) {
                        $ccEmail[] = $opsModel->email;
                    }

                    $salesModel = \app\models\SalesProfile::find()->where(['role_code' => 'SLCTMG', 'role_value' => $ownerCity])->one();
                    if ($salesModel) {
                        $ccEmail[] = $salesModel->email;
                    }
                }

                $subject = "New property Owner Lead";
                $msg = "Hello,<br/><br/>New property owner lead for '" . $ownerName . "' has been created, please verify and assign the branch and sales person to this lead.<br/><br/>Contact details of property owner are $ownerPhone, $ownerEmail<br/>With Regards,<br/>Easyleases Team";
                if (!empty($toEmail)) {
                    Yii::$app->userdata->doMailWithCC(implode(',', $toEmail), implode(',', $ccEmail), '', $subject, $msg, []);
                    Yii::$app->getSession()->setFlash('owner-form-submitted', 'Thanks for your interest in partnering with Easyleases as a property owner. We will get in touch with you in the next 2 business days.');
                } else {
                    Yii::$app->getSession()->setFlash('owner-form-submitted', 'Something went wrong, please try after sometime.');
                }

                $postData['owner_name'] = null;
                $postData['owner_email'] = null;
                $postData['owner_phone'] = null;
                $postData['owner_city'] = null;
            }
        }

        return $this->render('good_apartment_management', ['postData' => $postData]);
    }

    
    //#################### Property Owners and Rental Management Companies In Bangalore ############################//
    
     public function actionOwnerrentalmanagement() {
        header("Cache-Control: max-age=6480000"); //30days (60sec * 60min * 24hours * 30days)
        $this->view->params['head_title'] = "Rental Property Management Companies in Bangalore | Tenant Management";
        $this->view->params['meta_desc'] = "Rental Property Management Companies in Bangalore, Property Management in Bangalore, Rental Property Management Services in Bangalore, Tenant Management Services Bangalore";
        $this->view->params['meta_keywords'] = "Rental Property Management Companies in Bangalore, Property Management in Bangalore, Rental Property Management Services in Bangalore, Tenant Management Services Bangalore";
        $this->layout = 'camp_layout';
        $postData = [];
        $postData['owner_name'] = null;
        $postData['owner_email'] = null;
        $postData['owner_phone'] = null;
        $postData['owner_city'] = null;
        if (!empty(Yii::$app->request->post('owner_phone')) && !empty(Yii::$app->request->post('owner_city'))) {
            $ownerName = Yii::$app->request->post('owner_name');
            $ownerEmail = Yii::$app->request->post('owner_email');
            $ownerPhone = Yii::$app->request->post('owner_phone');
            $ownerCity = Yii::$app->request->post('owner_city');

            $postData['owner_name'] = $ownerName;
            $postData['owner_email'] = $ownerEmail;
            $postData['owner_phone'] = $ownerPhone;
            $postData['owner_city'] = $ownerCity;

            $usersEmail = \app\models\Users::findAll(['LOWER(login_id)' => trim(strtolower($ownerEmail))]);
            //$usersPhone = \app\models\OwnerProfile::findAll(['phone' => trim($ownerPhone)]);
            if ($usersEmail) {
                Yii::$app->getSession()->setFlash('owner-form-submitted-err', 'Email Id Already Exists, Please Try With Different Email.');
                //} else if ($usersPhone) {
            } else if (false) {
                Yii::$app->getSession()->setFlash('owner-form-submitted-err', 'Phone Number Already Exists, Please Try With Different Number.');
            } else {
                $comments = new \app\models\Comments;
                $model = new \app\models\Users;
                $modelLeadsOwner = new \app\models\LeadsOwner;
                $modelOwnerProfile = new \app\models\OwnerProfile;

                $rand = Yii::$app->userdata->passwordGenerate();

                $model->full_name = $ownerName;
                $model->login_id = strtolower($ownerEmail);
                $model->username = strtolower($ownerEmail);
                $model->auth_key = base64_encode($ownerEmail . time() . rand(1000, 9999));
                $model->password = md5($rand);
                $model->user_type = 4;
                $model->created_date = date('Y-m-d h:i:s');
                $model->role = 0;
                $model->save(false);

                $ownerId = Yii::$app->db->getLastInsertID();

                $modelOwnerProfile->owner_id = $ownerId;
                $modelOwnerProfile->state = 0;
                $modelOwnerProfile->region = 0;
                $modelOwnerProfile->pincode = 0;
                $modelOwnerProfile->phone = $ownerPhone;
                $modelOwnerProfile->address_line_1 = " ";
                $modelOwnerProfile->address_line_2 = " ";
                $modelOwnerProfile->save(false);

                $comments->user_id = $ownerId;
                $comments->property_id = 0;
                $comments->created_by = $ownerId;
                $comments->save(false);

                $modelLeadsOwner->full_name = $ownerName;
                $modelLeadsOwner->email_id = $ownerEmail;
                $modelLeadsOwner->contact_number = $ownerPhone;
                $modelLeadsOwner->address = " ";
                $modelLeadsOwner->address_line_2 = " ";
                $modelLeadsOwner->lead_city = $ownerCity;
                $modelLeadsOwner->lead_state = Yii::$app->userdata->getStateCodeByCityCode($ownerCity);
                $modelLeadsOwner->region = 0;
                $modelLeadsOwner->pincode = 0;
                $modelLeadsOwner->created_date = date('Y-m-d H:i:s');

                $modelLeadsOwner->save(false);

                $users = \app\models\Users::findAll(['user_type' => '7']);
                $userList = Array();
                foreach ($users as $key => $value) {
                    $userList[] = $value->login_id;
                }

                $toEmail = [];
                $ccEmail = [];

                $toEmail[] = Yii::$app->params['supportEmail'];

                if (!empty($ownerCity)) {
                    $opsModel = \app\models\OperationsProfile::find()->where(['role_code' => 'OPCTMG', 'role_value' => $ownerCity])->one();
                    if ($opsModel) {
                        $ccEmail[] = $opsModel->email;
                    }

                    $salesModel = \app\models\SalesProfile::find()->where(['role_code' => 'SLCTMG', 'role_value' => $ownerCity])->one();
                    if ($salesModel) {
                        $ccEmail[] = $salesModel->email;
                    }
                }

                $subject = "New property Owner Lead";
                $msg = "Hello,<br/><br/>New property owner lead for '" . $ownerName . "' has been created, please verify and assign the branch and sales person to this lead.<br/><br/>Contact details of property owner are $ownerPhone, $ownerEmail<br/>With Regards,<br/>Easyleases Team";
                if (!empty($toEmail)) {
                    Yii::$app->userdata->doMailWithCC(implode(',', $toEmail), implode(',', $ccEmail), '', $subject, $msg, []);
                    Yii::$app->getSession()->setFlash('owner-form-submitted', 'Thanks for your interest in partnering with Easyleases as a property owner. We will get in touch with you in the next 2 business days.');
                } else {
                    Yii::$app->getSession()->setFlash('owner-form-submitted-err', 'Something went wrong, please try after sometime.');
                }

                $postData['owner_name'] = null;
                $postData['owner_email'] = null;
                $postData['owner_phone'] = null;
                $postData['owner_city'] = null;
                
                return $this->redirect(['/thankyou']);
            }
        }

        return $this->render('Owner_rentalmanagement', ['postData' => $postData]);
    }

     //#################### Significance of Home Management Companies For Home Owners In Bangalore ############################//
    
     public function actionHomemanagement() {
        header("Cache-Control: max-age=6480000"); //30days (60sec * 60min * 24hours * 30days)
        $this->view->params['head_title'] = "Home Maintenance Company in Bangalore | House Maintenance Services";
        $this->view->params['meta_desc'] = "Home Maintenance Company in Bangalore, Home Rental Management Companies in Bangalore, House Maintenance Services in Bangalore, Home Rental Companies in Bangalore";
        $this->view->params['meta_keywords'] = "Home Maintenance Company in Bangalore, Home Rental Management Companies in Bangalore, House Maintenance Services in Bangalore, Home Rental Companies in Bangalore";
        $this->layout = 'camp_layout';
        $postData = [];
        $postData['owner_name'] = null;
        $postData['owner_email'] = null;
        $postData['owner_phone'] = null;
        $postData['owner_city'] = null;
        if (!empty(Yii::$app->request->post('owner_phone')) && !empty(Yii::$app->request->post('owner_city'))) {
            $ownerName = Yii::$app->request->post('owner_name');
            $ownerEmail = Yii::$app->request->post('owner_email');
            $ownerPhone = Yii::$app->request->post('owner_phone');
            $ownerCity = Yii::$app->request->post('owner_city');

            $postData['owner_name'] = $ownerName;
            $postData['owner_email'] = $ownerEmail;
            $postData['owner_phone'] = $ownerPhone;
            $postData['owner_city'] = $ownerCity;

            $usersEmail = \app\models\Users::findAll(['LOWER(login_id)' => trim(strtolower($ownerEmail))]);
            //$usersPhone = \app\models\OwnerProfile::findAll(['phone' => trim($ownerPhone)]);
            if ($usersEmail) {
                Yii::$app->getSession()->setFlash('owner-form-submitted-err', 'Email Id Already Exists, Please Try With Different Email.');
                //} else if ($usersPhone) {
            } else if (false) {
                Yii::$app->getSession()->setFlash('owner-form-submitted-err', 'Phone Number Already Exists, Please Try With Different Number.');
            } else {
                $comments = new \app\models\Comments;
                $model = new \app\models\Users;
                $modelLeadsOwner = new \app\models\LeadsOwner;
                $modelOwnerProfile = new \app\models\OwnerProfile;

                $rand = Yii::$app->userdata->passwordGenerate();

                $model->full_name = $ownerName;
                $model->login_id = strtolower($ownerEmail);
                $model->username = strtolower($ownerEmail);
                $model->auth_key = base64_encode($ownerEmail . time() . rand(1000, 9999));
                $model->password = md5($rand);
                $model->user_type = 4;
                $model->created_date = date('Y-m-d h:i:s');
                $model->role = 0;
                $model->save(false);

                $ownerId = Yii::$app->db->getLastInsertID();

                $modelOwnerProfile->owner_id = $ownerId;
                $modelOwnerProfile->state = 0;
                $modelOwnerProfile->region = 0;
                $modelOwnerProfile->pincode = 0;
                $modelOwnerProfile->phone = $ownerPhone;
                $modelOwnerProfile->address_line_1 = " ";
                $modelOwnerProfile->address_line_2 = " ";
                $modelOwnerProfile->save(false);

                $comments->user_id = $ownerId;
                $comments->property_id = 0;
                $comments->created_by = $ownerId;
                $comments->save(false);

                $modelLeadsOwner->full_name = $ownerName;
                $modelLeadsOwner->email_id = $ownerEmail;
                $modelLeadsOwner->contact_number = $ownerPhone;
                $modelLeadsOwner->address = " ";
                $modelLeadsOwner->address_line_2 = " ";
                $modelLeadsOwner->lead_city = $ownerCity;
                $modelLeadsOwner->lead_state = Yii::$app->userdata->getStateCodeByCityCode($ownerCity);
                $modelLeadsOwner->region = 0;
                $modelLeadsOwner->pincode = 0;
                $modelLeadsOwner->created_date = date('Y-m-d H:i:s');

                $modelLeadsOwner->save(false);

                $users = \app\models\Users::findAll(['user_type' => '7']);
                $userList = Array();
                foreach ($users as $key => $value) {
                    $userList[] = $value->login_id;
                }

                $toEmail = [];
                $ccEmail = [];

                $toEmail[] = Yii::$app->params['supportEmail'];

                if (!empty($ownerCity)) {
                    $opsModel = \app\models\OperationsProfile::find()->where(['role_code' => 'OPCTMG', 'role_value' => $ownerCity])->one();
                    if ($opsModel) {
                        $ccEmail[] = $opsModel->email;
                    }

                    $salesModel = \app\models\SalesProfile::find()->where(['role_code' => 'SLCTMG', 'role_value' => $ownerCity])->one();
                    if ($salesModel) {
                        $ccEmail[] = $salesModel->email;
                    }
                }

                $subject = "New property Owner Lead";
                $msg = "Hello,<br/><br/>New property owner lead for '" . $ownerName . "' has been created, please verify and assign the branch and sales person to this lead.<br/><br/>Contact details of property owner are $ownerPhone, $ownerEmail<br/>With Regards,<br/>Easyleases Team";
                if (!empty($toEmail)) {
                    Yii::$app->userdata->doMailWithCC(implode(',', $toEmail), implode(',', $ccEmail), '', $subject, $msg, []);
                    Yii::$app->getSession()->setFlash('owner-form-submitted', 'Thanks for your interest in partnering with Easyleases as a property owner. We will get in touch with you in the next 2 business days.');
                } else {
                    Yii::$app->getSession()->setFlash('owner-form-submitted', 'Something went wrong, please try after sometime.');
                }

                $postData['owner_name'] = null;
                $postData['owner_email'] = null;
                $postData['owner_phone'] = null;
                $postData['owner_city'] = null;
                
                return $this->redirect(['/thankyou']);
            }
        }

        return $this->render('home_management', ['postData' => $postData]);
    }
    
    public function actionBestmngblr() {
        header("Cache-Control: max-age=6480000"); //30days (60sec * 60min * 24hours * 30days)
        $this->view->params['head_title'] = "Best Property Management Services Company In Bangalore";
        $this->view->params['meta_desc'] = "Property Management Services | Best Property Management Services Company In Bangalore | Rental Property Management | Property Management Companies";
        $this->view->params['meta_keywords'] = "";
        $this->layout = 'camp_layout';
        $postData = [];
        $postData['owner_name'] = null;
        $postData['owner_email'] = null;
        $postData['owner_phone'] = null;
        $postData['owner_city'] = null;
        if (!empty(Yii::$app->request->post('owner_phone')) && !empty(Yii::$app->request->post('owner_city'))) {
            $ownerName = Yii::$app->request->post('owner_name');
            $ownerEmail = Yii::$app->request->post('owner_email');
            $ownerPhone = Yii::$app->request->post('owner_phone');
            $ownerCity = Yii::$app->request->post('owner_city');

            $postData['owner_name'] = $ownerName;
            $postData['owner_email'] = $ownerEmail;
            $postData['owner_phone'] = $ownerPhone;
            $postData['owner_city'] = $ownerCity;

            $usersEmail = \app\models\Users::findAll(['LOWER(login_id)' => trim(strtolower($ownerEmail))]);
            //$usersPhone = \app\models\OwnerProfile::findAll(['phone' => trim($ownerPhone)]);
            if ($usersEmail) {
                Yii::$app->getSession()->setFlash('owner-form-submitted-err', 'Email Id Already Exists, Please Try With Different Email.');
                //} else if ($usersPhone) {
            } else if (false) {
                Yii::$app->getSession()->setFlash('owner-form-submitted-err', 'Phone Number Already Exists, Please Try With Different Number.');
            } else {
                $comments = new \app\models\Comments;
                $model = new \app\models\Users;
                $modelLeadsOwner = new \app\models\LeadsOwner;
                $modelOwnerProfile = new \app\models\OwnerProfile;

                $rand = Yii::$app->userdata->passwordGenerate();

                $model->full_name = $ownerName;
                $model->login_id = strtolower($ownerEmail);
                $model->username = strtolower($ownerEmail);
                $model->auth_key = base64_encode($ownerEmail . time() . rand(1000, 9999));
                $model->password = md5($rand);
                $model->user_type = 4;
                $model->created_date = date('Y-m-d h:i:s');
                $model->role = 0;
                //$model->save(false);

                $ownerId = Yii::$app->db->getLastInsertID();

                $modelOwnerProfile->owner_id = $ownerId;
                $modelOwnerProfile->state = 0;
                $modelOwnerProfile->region = 0;
                $modelOwnerProfile->pincode = 0;
                $modelOwnerProfile->phone = $ownerPhone;
                $modelOwnerProfile->address_line_1 = " ";
                $modelOwnerProfile->address_line_2 = " ";
                //$modelOwnerProfile->save(false);

                $comments->user_id = $ownerId;
                $comments->property_id = 0;
                $comments->created_by = $ownerId;
                //$comments->save(false);

                $modelLeadsOwner->full_name = $ownerName;
                $modelLeadsOwner->email_id = $ownerEmail;
                $modelLeadsOwner->contact_number = $ownerPhone;
                $modelLeadsOwner->address = " ";
                $modelLeadsOwner->address_line_2 = " ";
                $modelLeadsOwner->lead_city = $ownerCity;
                $modelLeadsOwner->lead_state = Yii::$app->userdata->getStateCodeByCityCode($ownerCity);
                $modelLeadsOwner->region = 0;
                $modelLeadsOwner->pincode = 0;
                $modelLeadsOwner->created_date = date('Y-m-d H:i:s');

                //$modelLeadsOwner->save(false);

                $users = \app\models\Users::findAll(['user_type' => '7']);
                $userList = Array();
                foreach ($users as $key => $value) {
                    $userList[] = $value->login_id;
                }

                $toEmail = [];
                $ccEmail = [];

                $toEmail[] = Yii::$app->params['supportEmail'];

                if (!empty($ownerCity)) {
                    $opsModel = \app\models\OperationsProfile::find()->where(['role_code' => 'OPCTMG', 'role_value' => $ownerCity])->one();
                    if ($opsModel) {
                        $ccEmail[] = $opsModel->email;
                    }

                    $salesModel = \app\models\SalesProfile::find()->where(['role_code' => 'SLCTMG', 'role_value' => $ownerCity])->one();
                    if ($salesModel) {
                        $ccEmail[] = $salesModel->email;
                    }
                }

                $subject = "New property Owner Lead";
                $msg = "Hello,<br/><br/>New property owner lead for '" . $ownerName . "' has been created, please verify and assign the branch and sales person to this lead.<br/><br/>Contact details of property owner are $ownerPhone, $ownerEmail<br/>With Regards,<br/>Easyleases Team";
                if (!empty($toEmail)) {
                    Yii::$app->userdata->doMailWithCC(implode(',', $toEmail), implode(',', $ccEmail), '', $subject, $msg, []);
                    Yii::$app->getSession()->setFlash('owner-form-submitted', 'Thanks for your interest in partnering with Easyleases as a property owner. We will get in touch with you in the next 2 business days.');
                } else {
                    Yii::$app->getSession()->setFlash('owner-form-submitted', 'Something went wrong, please try after sometime.');
                }

                $postData['owner_name'] = null;
                $postData['owner_email'] = null;
                $postData['owner_phone'] = null;
                $postData['owner_city'] = null;
                
                return $this->redirect(['/thankyou']);
            }
        }

        return $this->render('bestmngblr', ['postData' => $postData]);
    }
}
