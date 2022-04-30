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

class SiteController extends Controller {

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
                'only' => ['firstloginpasschange', 'logout', 'myagreements', 'ownerlease', 'editowner', 'ownerrequests', 'ownerleads', 'myprofile', 'editprofile', 'myfavlist', 'wallet', 'payrent', 'payrentadvance', 'bankdetails', 'bookinginfo', 'payment', 'addmoney', 'booknow', 'confirmvisits', 'mypayments', 'mypaymentsdue', 'mypaymentsforcast', 'mypaymentsnew', 'mylease', 'editprofiletenant', 'myrequests', 'createrequests', 'createownerrequests', 'dashboard', 'mydashboard', 'mytenants', 'tenantdetails', 'ownerpayments', 'confirmation', 'onboarding', 'tenantpenaltycalculate', 'filterpopup', 'editpersonalinfo'],
                'rules' => [
                    [
                        'actions' => ['firstloginpasschange', 'tenantpenaltycalculate', 'logout', 'myagreements', 'ownerlease', 'editowner', 'ownerrequests', 'ownerleads', 'myprofile', 'editprofile', 'payrent', 'payrentadvance', 'myfavlist', 'wallet', 'bankdetails', 'editprofiletenant', 'bookinginfo', 'payment', 'addmoney', 'createownerrequests', 'booknow', 'mypaymentsdue', 'mypaymentsforcast', 'confirmvisits', 'mypayments', 'mypaymentsnew', 'mylease', 'myrequests', 'createrequests', 'dashboard', 'mydashboard', 'mytenants', 'tenantdetails', 'ownerpayments', 'confirmation', 'onboarding', 'filterpopup', 'editpersonalinfo'],
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
    
    public function beforeAction($action) {
        $methods = [
            'payrentneftapp', 'createnotidownload'
        ];
        if (in_array($action->id, $methods)) {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    public function actionGotohome() {
        //echo '<script>location.replace("//www.easyleases.in");</script>';
    }

    public function actionMyfavlist() {

        $this->layout = 'applicant_dashboard';
        $modelvisit = new \app\models\PropertyVisits;
        $list = FavouriteProperties::find()->where(['applicant_id' => Yii::$app->userdata->email])->all();
        return $this->render('myfavlist', ['model' => $list, 'modelvisit' => $modelvisit]);
    }

    public function actionUpdatevisitajax() {
        $visitsPost = Yii::$app->request->post('PropertyVisits');
        $modelVisitGet = \app\models\FavouriteProperties::findOne(['id' => $visitsPost['schedule_id']]);
        if ($modelVisitGet->status == '1') {
            $modelVisitGet->status = 3;
        }
        $modelVisitGet->visit_date = date('Y-m-d', strtotime($visitsPost['visit_date']));
        $modelVisitGet->visit_time = $visitsPost['visit_time'];
        $checkDateTime = \app\models\PropertyVisits::find()->where(['visit_date' => date('Y-m-d', strtotime($visitsPost['visit_date'])), 'visit_time' => $visitsPost['visit_time'] . ':00', 'applicant_id' => Yii::$app->userdata->email])->andWhere('id !=' . $visitsPost['schedule_id'])->one();

        // $command = Yii::$app->db->createCommand('update favourite_properties SET visit_date="'.date('Y-m-d',strtotime($visitsPost['visit_date'])).'", visit_time="'.$visitsPost['visit_time'].'",status="3" where id="'.$visitsPost['schedule_id'].'"');
        if (count($checkDateTime) == '0') {
            if ($modelVisitGet->save(false)) {
                //Yii::$app->getSession()->setFlash(
                // 'success','Property Scheduled Successfully'
                // );

                echo json_encode(Array('success' => 1, 'msg' => 'Property Scheduled Successfully'));
            } else {
                //Yii::$app->getSession()->setFlash(
                // 'error','Failure!'
                // );
                echo json_encode(Array('success' => 0, 'msg' => 'Failure'));
            }
        } else {
            //Yii::$app->getSession()->setFlash(
            // 'error','Visit With This Date Time Already Exist.'
            // );
            echo json_encode(Array('success' => 0, 'msg' => 'Visit With This Date Time Already Exist.'));
        }
    }

    public function actionAddmoney() {

        $this->layout = 'app_dashboard';
        $amountWallet = 0;
        $models = Wallets::find()->where(['user_id' => Yii::$app->user->id])->one();
        if ($models) {
            $amountWallet = $models->amount;
        }
        $model = new Wallets;

        $modelHistory = new WalletsHistory;

        if ($model->load(Yii::$app->request->post())) {

            if (empty($models)) {
                $model->user_id = Yii::$app->user->id;
                $model->created_by = Yii::$app->user->id;
                $model->created_date = date('Y-m-d H:i:s');
                $model->save(false);

                $modelHistory->amount = $model->amount;
                $modelHistory->email_id = Yii::$app->userdata->email;
                $modelHistory->transaction_type = 1;
                $modelHistory->created_by = Yii::$app->user->id;
                $modelHistory->created_date = date('Y-m-d H:i:s');
                $modelHistory->save(false);
            } else {


                $models->amount = $model->amount + $models->amount;
                $models->updated_by = Yii::$app->user->id;
                $models->updated_date = date('Y-m-d H:i:s');
                $models->save(false);


                $modelHistory->amount = $model->amount;
                $modelHistory->email_id = Yii::$app->userdata->email;
                $modelHistory->transaction_type = 1;
                $modelHistory->created_by = Yii::$app->user->id;
                $modelHistory->created_date = date('Y-m-d H:i:s');
                $modelHistory->save(false);
            }

            Yii::$app->getSession()->setFlash(
                    'success', 'Your money is added in wallet'
            );
            return $this->redirect(['wallet']);
        } else {

            return $this->render('_form_money', [
                        'model' => $model, 'amountWallet' => $amountWallet
            ]);
        }

        return $this->render('_form_money', ['model' => $model, 'amountWallet' => $amountWallet]);
    }

    public function actionPenaltycalculate($id, $for) {
        $query = 'SELECT tp.id, tp.payment_date, tp.tenant_id, tp.parent_id, tp.payment_des, tp.payment_status, tp.total_amount, tp.due_date, "Rent" as payment_for, tp.penalty_amount, ta.late_penalty_percent, ta.min_penalty FROM tenant_payments tp INNER JOIN tenant_agreements ta ON tp.agreement_id = ta.id WHERE tp.id="' . $id . '"';
        $connection = \Yii::$app->db;
        $model = $connection->createCommand($query);
        $duePayments = $model->queryOne();
        //print_r($duePayments); exit;

        return $this->renderAjax('_penalty_percent.php', Array('payments' => $duePayments, 'for' => $for));
    }

    public function actionMyprofile() {

        //$proofType = \app\models\ProofType::find()->all();
        //echo "<pre>";print_r(Yii::$app->user);echo "</pre>";die;
        if (($model = \app\models\TenantProfile::find()->where(['tenant_id' => Yii::$app->user->id])->one()) !== null) {
            $this->layout = 'tenant_dashboard';
            $UsersModel = \app\models\Users::find()->where(['id' => Yii::$app->user->id])->one();
            $OperationModel = \app\models\Users::find()->where(['id' => $model->operation_id])->one();
            $OperationsProfile = \app\models\OperationsProfile::find()->where(['operations_id' => $model->operation_id])->one();
            $proofType = \app\models\ProofType::find()->all();
            $address_proofs = \app\models\UserIdProofs::find()->where(['user_id' => Yii::$app->user->id])->all();
            $emergency_proofs = \app\models\EmergencyProofs::find()->where(['user_id' => Yii::$app->user->id])->all();

            //	$bankDetails = BankDetails::findOne(['email_id'=>Yii::$app->userdata->email])	;

            if (@$_REQUEST['type'] == 'p') {
                return $this->render('personal_info_tenant', ['model' => $model, 'UsersModel' => $UsersModel, 'OperationModel' => $OperationModel, 'OperationsProfile' => $OperationsProfile]);
            }
            if (@$_REQUEST['type'] == 'i') {
                return $this->render('identity_info_tenant', ['model' => $model, 'UsersModel' => $UsersModel, 'OperationModel' => $OperationModel, 'OperationsProfile' => $OperationsProfile, 'proofType' => $proofType, 'address_proofs' => $address_proofs]);
            } else if (@$_REQUEST['type'] == 'b') {
                return $this->render('bank_detail_tenant', ['model' => $model, 'UsersModel' => $UsersModel, 'OperationModel' => $OperationModel, 'OperationsProfile' => $OperationsProfile, 'emergency_proofs' => $emergency_proofs]);
            } else if (@$_REQUEST['type'] == 'e' || @$_REQUEST['type'] == 'ee') {
                return $this->render('employment_data_tenant', ['model' => $model, 'UsersModel' => $UsersModel, 'OperationModel' => $OperationModel, 'OperationsProfile' => $OperationsProfile, 'emergency_proofs' => $emergency_proofs]);
            } else if (@$_REQUEST['type'] == 'em') {
                return $this->render('emergency_data_tenant', ['model' => $model, 'UsersModel' => $UsersModel, 'OperationModel' => $OperationModel, 'OperationsProfile' => $OperationsProfile, 'emergency_proofs' => $emergency_proofs, 'proofType' => $proofType, 'address_proofs' => $address_proofs]);
            } else if (@$_REQUEST['type'] == 'sp') {
                return $this->render('support_data_tenant', ['model' => $model]);
            } else {
                return $this->render('personal_info_tenant', ['model' => $model, 'UsersModel' => $UsersModel, 'OperationModel' => $OperationModel, 'OperationsProfile' => $OperationsProfile]);
            }
        } else if (($model = \app\models\ApplicantProfile::find()->where(['applicant_id' => Yii::$app->user->id])->one()) !== null) {
            $this->layout = 'applicant_dashboard';

            $emergency_proofs = \app\models\EmergencyProofs::find()->where(['user_id' => Yii::$app->user->id])->all();

            $proofType = \app\models\ProofType::find()->all();
            $address_proofs = \app\models\UserIdProofs::find()->where(['user_id' => Yii::$app->user->id])->all();

            if (@$_REQUEST['type'] == 'p') {
                return $this->render('personal_info', ['model' => $model, 'emergency_proofs' => $emergency_proofs]);
            }
            if (@$_REQUEST['type'] == 'i') {
                return $this->render('identity_info', ['model' => $model, 'emergency_proofs' => $emergency_proofs, 'proofType' => $proofType, 'address_proofs' => $address_proofs]);
            } else if (@$_REQUEST['type'] == 'b') {
                return $this->render('bank_detail', ['model' => $model, 'emergency_proofs' => $emergency_proofs]);
            } else if (@$_REQUEST['type'] == 'e' || @$_REQUEST['type'] == 'ee') {
                return $this->render('employment_data', ['model' => $model, 'emergency_proofs' => $emergency_proofs]);
            } else if (@$_REQUEST['type'] == 'em') {
                return $this->render('emergency_data', ['model' => $model, 'emergency_proofs' => $emergency_proofs, 'proofType' => $proofType, 'address_proofs' => $address_proofs]);
            } else {
                return $this->render('personal_info', ['model' => $model, 'emergency_proofs' => $emergency_proofs]);
            }

            //return $this->render('myprofile_applicant', ['model' => $model, 'emergency_proofs' => $emergency_proofs, 'proofType' => $proofType]);
        } else if (($model = \app\models\OwnerProfile::find()->where(['owner_id' => Yii::$app->user->id])->one()) !== null) {
            $proofType = \app\models\ProofType::find()->all();
            $address_proofs = \app\models\UserIdProofs::find()->where(['user_id' => Yii::$app->user->id])->all();
            $properties = Properties::find()->where(['owner_id' => Yii::$app->user->id, 'status' => '1'])->all();
            $tototProperties = count($properties);

            $ids = array();
            foreach ($properties as $property) {
                $ids[] = $property->id;
            }
            if ($tototProperties > 1 || $tototProperties < 1) {
                $this->layout = 'owner_dashboard_main';
            } else {
                $this->layout = 'owner_dashboard_single';
                //print_r($ids); exit;
                $this->view->params['propertyid'] = (empty($ids[0])) ? '' : $ids[0];
            }
            if (@$_REQUEST['type'] == 'op') {
                return $this->render('personal_info_o', ['model' => $model]);
            } else if (@$_REQUEST['type'] == 'ob') {
                return $this->render('bank_detail_o', ['model' => $model]);
            } else if (@$_REQUEST['type'] == 'oem') {
                return $this->render('emergency_data_o', ['model' => $model]);
            } else if (@$_REQUEST['type'] == 'oi') {
                return $this->render('identity_info_o', ['model' => $model, 'proofType' => $proofType, 'address_proofs' => $address_proofs]);
            } else if (@$_REQUEST['type'] == 'os') {
                return $this->render('support_data', ['model' => $model]);
            } else {
                return $this->render('personal_info_o', ['model' => $model]);
            }
            //return $this->render('myprofile_owner', ['model' => $model]);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionRemoveidentity() {
        $id = $_POST['id'];
        if (Yii::$app->request->isAjax) {
            $proof = \app\models\UserIdProofs::findOne(['id' => $id]);
            $proof->delete();
            return true;
        }
    }

    public function actionRemoveemergency() {
        $id = $_POST['id'];
        if (Yii::$app->request->isAjax) {
            $proof = \app\models\EmergencyProofs::findOne(['id' => $id]);
            $proof->delete();
            return true;
        }
    }

    public function actionEditprofiletenant() {

        $this->layout = 'app_dashboard';

        $proofType = \app\models\ProofType::find()->all();
        $UsersModel = \app\models\Users::find()->where(['id' => Yii::$app->user->id])->one();
        if (($model = \app\models\TenantProfile::find()->where(['tenant_id' => Yii::$app->user->id])->one()) != null) {

            $bankCheck = $model->cancelled_check;
            $employmnetProof = $model->employment_proof_url;
            $profileimage = $model->profile_image;
            $emergency_proofs = \app\models\EmergencyProofs::find()->where(['user_id' => Yii::$app->user->id])->all();
            $address_proofs = \app\models\UserIdProofs::find()->where(['user_id' => Yii::$app->user->id])->all();
            if ($model->load(Yii::$app->request->post())) {
                $employStartDate = (empty(Yii::$app->request->post('TenantProfile')['employment_start_date'])) ? null : Yii::$app->request->post('TenantProfile')['employment_start_date'];
                $model->employment_start_date = $employStartDate;
                if ($model->validate()) {
                    if (isset($_FILES['address_proof'])) {
                        $address_proof = $_FILES['address_proof'];
                    } else {
                        $address_proof = Array();
                    }


                    if (isset($_FILES['emergency_proof'])) {
                        $emer_contact_address = $_FILES['emergency_proof'];
                    } else {
                        $emer_contact_address = Array();
                    }
                    $profile_image = UploadedFile::getInstance($model, 'profile_image');
                    $cancelled_check = UploadedFile::getInstance($model, 'cancelled_check');
                    $employmnet_proof_url = UploadedFile::getInstance($model, 'employment_proof_url');

                    if (!empty($emer_contact_address)) {
                        foreach ($emer_contact_address['tmp_name'] as $key => $value) {

                            foreach ($value as $key1 => $value1) {
                                if ($value1 != '') {
                                    $emergency_proofobj = new \app\models\EmergencyProofs;
                                    $name_address_proof = (string) date('ymdHis') . rand() . $emer_contact_address['name'][$key][$key1];
                                    $tmp_file = (string) $value1;
                                    move_uploaded_file($value1, 'uploads/proofs/' . $name_address_proof);
                                    $emergency_proofobj->proof_type = $key;
                                    $emergency_proofobj->proof_document_url = 'uploads/proofs/' . $name_address_proof;
                                    $emergency_proofobj->user_id = Yii::$app->user->id;
                                    $emergency_proofobj->created_by = Yii::$app->user->id;
                                    $emergency_proofobj->created_date = date('Y-m-d H:i:s');
                                    $emergency_proofobj->save(true);
                                }
                            }
                        }
                    }


                    if (!empty($cancelled_check)) {
                        $name_cancelled_check = date('ymdHis') . $cancelled_check->name;
                        $cancelled_check->saveAs('uploads/proofs/' . $name_cancelled_check);
                        // $model->cancelled_check =  Url::home(true).'uploads/proofs/'.$name_cancelled_check ;
                        $model->cancelled_check = 'uploads/proofs/' . $name_cancelled_check;
                    } else {
                        $model->cancelled_check = $bankCheck;
                    }
                    if (!empty($profile_image)) {
                        $name_profile_image = date('ymdHis') . $profile_image->name;
                        $profile_image->saveAs('uploads/profiles/' . $name_profile_image);
                        // $model->profile_image =  Url::home(true).'uploads/profiles/'.$name_profile_image ;
                        $model->profile_image = 'uploads/profiles/' . $name_profile_image;
                    } else {
                        $model->profile_image = $profileimage;
                    }
                    if (!empty($employmnet_proof_url)) {
                        $name_employmnet_proof_url = date('ymdHis') . $employmnet_proof_url->name;
                        $employmnet_proof_url->saveAs('uploads/proofs/' . $name_employmnet_proof_url);
                        // $model->employment_proof_url =  Url::home(true).'uploads/proofs/'.$name_employmnet_proof_url ;
                        $model->employment_proof_url = 'uploads/proofs/' . $name_employmnet_proof_url;
                        $model->employment_proof_url = 'uploads/proofs/' . $name_employmnet_proof_url;
                    } else {
                        $model->employment_proof_url = $employmnetProof;
                    }
                    $model->save(false);
                    /*
                      $modelUsers =  Users::findOne(Yii::$app->user->id) ;
                      $modelUsers->login_id = $model->email_id ;
                      $modelUsers->save(false); */

                    $modelLeadsTenant = LeadsTenant::find(['email_id' => $model->employment_email])->one();
                    $modelLeadsTenant->contact_number = $model->phone;
                    $modelLeadsTenant->address = $model->address_line_1;
                    $modelLeadsTenant->state = $model->state;
                    $modelLeadsTenant->city = $model->city;
                    //$modelLeadsTenant->region  =  $model->region ;
                    $modelLeadsTenant->pincode = $model->pincode;
                    $modelLeadsTenant->updated_date = date('Y-m-d H:i:s');
                    $modelLeadsTenant->save(false);


                    if (!empty($address_proof)) {
                        foreach ($address_proof['tmp_name'] as $key => $value) {

                            foreach ($value as $key1 => $value1) {
                                if (trim($value1) != '') {
                                    $address_proofobj = new \app\models\UserIdProofs;
                                    $name_address_proof = (string) date('ymdHis') . rand() . $address_proof['name'][$key][$key1];
                                    $tmp_file = (string) $value1;
                                    move_uploaded_file($value1, 'uploads/proofs/' . $name_address_proof);
                                    $address_proofobj->proof_type = $key;
                                    $address_proofobj->proof_document_url = 'uploads/proofs/' . $name_address_proof;
                                    $address_proofobj->user_id = Yii::$app->user->id;
                                    $address_proofobj->created_by = Yii::$app->user->id;
                                    $address_proofobj->created_date = date('Y-m-d H:i:s');
                                    $address_proofobj->save(false);
                                }
                            }
                        }
                    }
                    //	echo "<pre>";	print_r($model->address_proof );die;
                    // if(!empty($address_proof)){
                    // 	$address =   \app\models\UserIdProofs::find()->where(['user_id'=>Yii::$app->user->id,'proof_type'=>'address'])->one() ;
                    // 	 if( $address != null){
                    // 		 $address->delete();
                    // 	 }
                    //      $address_proofobj = new \app\models\UserIdProofs ;
                    //  	$name_address_proof = date('ymdHis').$address_proof->name ;
                    // 	$address_proof->saveAs('uploads/proofs/' . $name_address_proof );
                    //  	$address_proofobj->proof_type = 'address';
                    //  	$address_proofobj->proof_document_url = Url::home(true).'uploads/proofs/' .$name_address_proof ;
                    //  	$address_proofobj->user_id = Yii::$app->user->id;
                    //  	$address_proofobj->created_by = Yii::$app->user->id;
                    //  	$address_proofobj->created_date = date('Y-m-d H:i:s');
                    //  	$address_proofobj->save(false);
                    //  }
                    // if(!empty($identity_proof)){
                    // 	 $identity =   \app\models\UserIdProofs::find()->where(['user_id'=>Yii::$app->user->id,'proof_type'=>'identity'])->one() ;
                    // 	 if( $identity != null){
                    // 		 $identity->delete();
                    // 	 }
                    //      $identity_proofobj = new \app\models\UserIdProofs ;
                    //  	$name_identity_proof = date('ymdHis').$identity_proof->name ;
                    //  	$identity_proof->saveAs('uploads/proofs/' . $name_identity_proof );
                    //  	$identity_proofobj->proof_type = 'identity';
                    //  	$identity_proofobj->proof_document_url =  Url::home(true).'uploads/proofs/' .$name_identity_proof ;
                    //  	$identity_proofobj->user_id = Yii::$app->user->id;
                    // 	$identity_proofobj->created_by = Yii::$app->user->id;
                    //  	$identity_proofobj->created_date = date('Y-m-d H:i:s');
                    //  	$identity_proofobj->save(false);
                    //   }

                    Yii::$app->getSession()->setFlash(
                            'success', 'Your profile is saved successfully.'
                    );
                    return $this->redirect(['myprofile']);
                } else {
                    return $this->render('editprofile_tenant', [
                                'model' => $model,
                                'proofType' => $proofType,
                                'emergency_proofs' => $emergency_proofs,
                                'address_proofs' => $address_proofs,
                                'UsersModel' => $UsersModel,
                    ]);
                }
            } else {
                //echo "string"; die;

                return $this->render('editprofile_tenant', [
                            'model' => $model,
                            'proofType' => $proofType,
                            'emergency_proofs' => $emergency_proofs,
                            'address_proofs' => $address_proofs,
                            'UsersModel' => $UsersModel,
                ]);
            }
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionEditprofile() {

//echo Yii::$app->user->id;die;
        $this->layout = 'app_dashboard';
        $proofType = \app\models\ProofType::find()->all();

        if (($model = \app\models\ApplicantProfile::find()->where(['applicant_id' => Yii::$app->user->id])->one()) != null) {


            $modelUsers = Users::findOne(Yii::$app->user->id);
            $modelLeadsTenant = LeadsTenant::find(['email_id' => $modelUsers->login_id])->one();
            $emergency_proofs = \app\models\EmergencyProofs::find()->where(['user_id' => Yii::$app->user->id])->all();
            $address_proofs = \app\models\UserIdProofs::find()->where(['user_id' => Yii::$app->user->id])->all();
            $bankCheck = $model->cancelled_check;
            $employmnetProof = $model->employmnet_proof_url;
            $profileimage = $model->profile_image;

            $contactaddress = $model->emer_contact_address;
            $contactindentity = $model->emer_contact_indentity;



            if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {

                Yii::$app->response->format = 'json';
                return \yii\bootstrap\ActiveForm::validate($model);
            } else if ($model->load(Yii::$app->request->post())) {

//echo "<pre>";print_r(Yii::$app->request->post());echo "</pre>";die;
                if ($model->validate()) {
                    //echo "hello";die;
                    // echo "hello";
                    // die;

                    $profile_image = UploadedFile::getInstance($model, 'profile_image');
                    if (isset($_FILES['address_proof'])) {
                        $address_proof = $_FILES['address_proof'];
                    } else {
                        $address_proof = Array();
                    }


                    $cancelled_check = UploadedFile::getInstance($model, 'cancelled_check');
                    $employmnet_proof_url = UploadedFile::getInstance($model, 'employmnet_proof_url');

                    if (isset($_FILES['emergency_proof'])) {
                        $emer_contact_address = $_FILES['emergency_proof'];
                    } else {
                        $emer_contact_address = Array();
                    }

                    // print_r($emer_contact_address);
                    // die;
                    if (!empty($emer_contact_address)) {
                        foreach ($emer_contact_address['tmp_name'] as $key => $value) {

                            foreach ($value as $key1 => $value1) {
                                if ($value1 != '') {
                                    $emergency_proofobj = new \app\models\EmergencyProofs;
                                    $name_address_proof = (string) date('ymdHis') . rand() . $emer_contact_address['name'][$key][$key1];
                                    $tmp_file = (string) $value1;
                                    move_uploaded_file($value1, 'uploads/proofs/' . $name_address_proof);
                                    $emergency_proofobj->proof_type = $key;
                                    // $emergency_proofobj->proof_document_url = 'uploads/proofs/' .$name_address_proof ;
                                    $emergency_proofobj->proof_document_url = 'uploads/proofs/' . $name_address_proof;
                                    $emergency_proofobj->user_id = Yii::$app->user->id;
                                    $emergency_proofobj->created_by = Yii::$app->user->id;
                                    $emergency_proofobj->created_date = date('Y-m-d H:i:s');
                                    $emergency_proofobj->save(false);
                                }
                            }
                        }
                    }


                    if (!empty($cancelled_check)) {
                        $name_cancelled_check = date('ymdHis') . $cancelled_check->name;
                        $cancelled_check->saveAs('uploads/proofs/' . $name_cancelled_check);
                        // $model->cancelled_check =  Url::home(true).'uploads/proofs/'.$name_cancelled_check ;
                        $model->cancelled_check = 'uploads/proofs/' . $name_cancelled_check;
                    } else {
                        $model->cancelled_check = $bankCheck;
                    }

                    if (!empty($profile_image)) {
                        $name_profile_image = date('ymdHis') . $profile_image->name;
                        $profile_image->saveAs('uploads/profiles/' . $name_profile_image);
                        // $model->profile_image =  Url::home(true).'uploads/profiles/'.$name_profile_image ;
                        $model->profile_image = 'uploads/profiles/' . $name_profile_image;
                    } else {
                        $model->profile_image = $profileimage;
                    }

                    if (!empty($employmnet_proof_url)) {
                        $name_employmnet_proof_url = date('ymdHis') . $employmnet_proof_url->name;
                        $employmnet_proof_url->saveAs('uploads/proofs/' . $name_employmnet_proof_url);
                        // $model->employmnet_proof_url =  Url::home(true).'uploads/proofs/'.$name_employmnet_proof_url ;
                        $model->employmnet_proof_url = 'uploads/proofs/' . $name_employmnet_proof_url;
                    } else {
                        $model->employmnet_proof_url = $employmnetProof;
                    }
                    if ($_POST['ApplicantProfile']['employment_start_date'] != '') {
                        $model->employment_start_date = date('Y-m-d', strtotime($_POST['ApplicantProfile']['employment_start_date']));
                    }


                    $model->save(false);


                    $modelUsers->login_id = $model->email_id;
                    $modelUsers->save(false);


                    $modelLeadsTenant->email_id = $modelUsers->login_id;
                    $modelLeadsTenant->contact_number = $model->phone;
                    $modelLeadsTenant->address = $model->address_line_1;
                    $modelLeadsTenant->address_line_2 = $model->address_line_2;
                    $modelLeadsTenant->state = $model->state;
                    $modelLeadsTenant->city = $model->city;
                    $modelLeadsTenant->pincode = $model->pincode;
                    $modelLeadsTenant->updated_date = date('Y-m-d H:i:s');
                    $modelLeadsTenant->save(false);


                    if (!empty($address_proof)) {
                        foreach ($address_proof['tmp_name'] as $key => $value) {

                            foreach ($value as $key1 => $value1) {
                                if (trim($value1) != '') {
                                    $address_proofobj = new \app\models\UserIdProofs;
                                    $name_address_proof = (string) date('ymdHis') . rand() . $address_proof['name'][$key][$key1];
                                    $tmp_file = (string) $value1;
                                    move_uploaded_file($value1, 'uploads/proofs/' . $name_address_proof);
                                    $address_proofobj->proof_type = $key;
                                    // $address_proofobj->proof_document_url = Url::home(true).'uploads/proofs/' .$name_address_proof ;
                                    $address_proofobj->proof_document_url = 'uploads/proofs/' . $name_address_proof;
                                    $address_proofobj->user_id = Yii::$app->user->id;
                                    $address_proofobj->created_by = Yii::$app->user->id;
                                    $address_proofobj->created_date = date('Y-m-d H:i:s');
                                    $address_proofobj->save(false);
                                }
                            }
                        }
                    }

                    Yii::$app->getSession()->setFlash(
                            'success', 'Your profile is saved successfully.'
                    );
                    return $this->redirect(['myprofile']);
                } else {
                    return $this->render('editprofile_applicant', [
                                'model' => $model,
                                'proofType' => $proofType,
                                'emergency_proofs' => $emergency_proofs,
                                'address_proofs' => $address_proofs,
                    ]);
                }
            } else {

                return $this->render('editprofile_applicant', [
                            'model' => $model,
                            'proofType' => $proofType,
                            'emergency_proofs' => $emergency_proofs,
                            'address_proofs' => $address_proofs,
                ]);
                //echo "hello3";die;
            }
        } else {

            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionEditowner() {

        $this->layout = 'owner_dashboard_main';
        $proofType = \app\models\ProofType::find()->all();
        if (($model = \app\models\OwnerProfile::find()->where(['owner_id' => Yii::$app->user->id])->one()) != null) {
            $bankCheck = $model->cancelled_check;
            $profile_image_get = $model->profile_image;
            $address_proofs = \app\models\UserIdProofs::find()->where(['user_id' => Yii::$app->user->id])->all();
            $modelUser = Users::findOne(Yii::$app->user->id);
            $modelLeadsTenant = LeadsOwner::find(['email_id' => $modelUser->login_id])->one();

            if ($model->load(Yii::$app->request->post()) && $modelUser->load(Yii::$app->request->post())) {

                if ($model->validate() && $modelUser->validate(['login_id'])) {

                    // $address_proof = UploadedFile::getInstance($model, 'address_proof');
                    // $identity_proof = UploadedFile::getInstance($model, 'identity_proof');
                    if (isset($_FILES['address_proof'])) {
                        $address_proof = $_FILES['address_proof'];
                    } else {
                        $address_proof = Array();
                    }
                    $cancelled_check = UploadedFile::getInstance($model, 'cancelled_check');

                    if (!empty($cancelled_check)) {
                        $name_cancelled_check = date('ymdHis') . $cancelled_check->name;
                        $cancelled_check->saveAs('uploads/proofs/' . $name_cancelled_check);
                        // $model->cancelled_check =  Url::home(true).'uploads/proofs/'.$name_cancelled_check ;
                        $model->cancelled_check = 'uploads/proofs/' . $name_cancelled_check;
                    } else {
                        $model->cancelled_check = $bankCheck;
                    }

                    $profile_image = UploadedFile::getInstance($model, 'profile_image');
                    if (!empty($profile_image)) {
                        $name_profile_image = date('ymdHis') . $profile_image->name;
                        $profile_image->saveAs('uploads/proofs/' . $name_profile_image);
                        // $model->profile_image =  Url::home(true).'uploads/proofs/'.$name_profile_image ;
                        $model->profile_image = 'uploads/proofs/' . $name_profile_image;
                    } else {
                        $model->profile_image = $profile_image_get;
                    }

                    $model->save(false);

                    $modelUser->save(false);

                    if (count($modelLeadsTenant) != 0) {
                        $modelLeadsTenant->email_id = $modelUser->login_id;
                        $modelLeadsTenant->contact_number = $model->phone;
                        $modelLeadsTenant->address = $model->address_line_1;
                        $modelLeadsTenant->address_line_2 = $model->address_line_1;
                        $modelLeadsTenant->state = $model->state;
                        $modelLeadsTenant->city = $model->city;
                        $modelLeadsTenant->region = $model->region;
                        $modelLeadsTenant->pincode = $model->pincode;
                        $modelLeadsTenant->updated_date = date('Y-m-d H:i:s');
                        $modelLeadsTenant->save(false);
                    }



                    if (!empty($address_proof)) {
                        foreach ($address_proof['tmp_name'] as $key => $value) {

                            foreach ($value as $key1 => $value1) {
                                if ($value1 != '') {
                                    $address_proofobj = new \app\models\UserIdProofs;
                                    $name_address_proof = (string) date('ymdHis') . rand() . $address_proof['name'][$key][$key1];
                                    $tmp_file = (string) $value1;
                                    move_uploaded_file($value1, 'uploads/proofs/' . $name_address_proof);
                                    $address_proofobj->proof_type = $key;
                                    // $address_proofobj->proof_document_url = Url::home(true).'uploads/proofs/' .$name_address_proof ;
                                    $address_proofobj->proof_document_url = 'uploads/proofs/' . $name_address_proof;
                                    $address_proofobj->user_id = Yii::$app->user->id;
                                    $address_proofobj->created_by = Yii::$app->user->id;
                                    $address_proofobj->created_date = date('Y-m-d H:i:s');
                                    $address_proofobj->save(false);
                                }
                            }
                        }
                    }
                    // if(!empty($address_proof)){
                    // 	$address =   \app\models\UserIdProofs::find()->where(['user_id'=>Yii::$app->user->id,'proof_type'=>'address'])->one() ;
                    // 	 if( $address != null){
                    // 		 $address->delete();
                    // 	 }
                    //     $address_proofobj = new \app\models\UserIdProofs ;
                    // 	$name_address_proof = date('ymdHis').$address_proof->name ;
                    // 	$address_proof->saveAs('uploads/proofs/' . $name_address_proof );
                    // 	$address_proofobj->proof_type = 'address';
                    // 	$address_proofobj->proof_document_url = Url::home(true).'uploads/proofs/' .$name_address_proof ;
                    // 	$address_proofobj->user_id = Yii::$app->user->id;
                    // 	$address_proofobj->created_by = Yii::$app->user->id;
                    // 	$address_proofobj->created_date = date('Y-m-d H:i:s');
                    // 	$address_proofobj->save(false);
                    //  }
                    // if(!empty($identity_proof)){
                    // 	$identity =   \app\models\UserIdProofs::find()->where(['user_id'=>Yii::$app->user->id,'proof_type'=>'identity'])->one() ;
                    // 	 if( $identity != null){
                    // 		 $identity->delete();
                    // 	 }
                    //     $identity_proofobj = new \app\models\UserIdProofs ;
                    // 	$name_identity_proof = date('ymdHis').$identity_proof->name ;
                    // 	$identity_proof->saveAs('uploads/proofs/' . $name_identity_proof );
                    // 	$identity_proofobj->proof_type = 'identity';
                    // 	$identity_proofobj->proof_document_url =  Url::home(true).'uploads/proofs/' .$name_identity_proof ;
                    // 	$identity_proofobj->user_id = Yii::$app->user->id;
                    // 	$identity_proofobj->created_by = Yii::$app->user->id;
                    // 	$identity_proofobj->created_date = date('Y-m-d H:i:s');
                    // 	$identity_proofobj->save(false);
                    //  }

                    Yii::$app->getSession()->setFlash(
                            'success', 'Your profile is saved successfully.'
                    );
                    return $this->redirect(['myprofile']);
                } else {
                    $profile_image_get = $model->profile_image;
                    $profile_image = UploadedFile::getInstance($model, 'profile_image');
                    if (!empty($profile_image)) {
                        $name_profile_image = date('ymdHis') . $profile_image->name;
                        $profile_image->saveAs('uploads/proofs/' . $name_profile_image);
                        // $model->profile_image =  Url::home(true).'uploads/proofs/'.$name_profile_image ;
                        $model->profile_image = 'uploads/proofs/' . $name_profile_image;
                    } else {
                        $model->profile_image = $profile_image_get;
                    }
                    // echo "<pre>";print_r($profile_image);echo "</pre>";die;
                    //echo "hello";die;
                    // print_r($model->getErrors());
                    // die;
                    return $this->render('editprofile_owner', [
                                'model' => $model, 'modelUser' => $modelUser,
                                'proofType' => $proofType,
                                'address_proofs' => $address_proofs
                    ]);
                }
            } else {

                return $this->render('editprofile_owner', [
                            'model' => $model, 'modelUser' => $modelUser,
                            'proofType' => $proofType,
                            'address_proofs' => $address_proofs,
                ]);
            }
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionBankdetails() {

        $this->layout = 'app_dashboard';

        if ($modelBankDetails = BankDetails::findOne(['email_id' => Yii::$app->userdata->email]) !== null) {
            $modelBankDetails = BankDetails::findOne(['email_id' => Yii::$app->userdata->email]);

            if ($modelBankDetails->load(Yii::$app->request->post())) {

                $modelBankDetails->email_id = Yii::$app->userdata->email;
                $modelBankDetails->created_by = Yii::$app->user->id;
                $modelBankDetails->updated_by = Yii::$app->user->id;
                $modelBankDetails->create_time = date('Y-m-d H:i:s');
                $modelBankDetails->update_time = date('Y-m-d H:i:s');

                $modelBankDetails->save(false);
                Yii::$app->getSession()->setFlash(
                        'success', 'Your Bank details is updated successfully.'
                );
                return $this->redirect(['myprofile']);
            } else {

                return $this->render('bankdeails', [
                            'model' => $modelBankDetails,
                ]);
            }
        } else {
            $modelBankDetails = new BankDetails;

            if ($modelBankDetails->load(Yii::$app->request->post())) {

                $modelBankDetails->email_id = Yii::$app->userdata->email;
                $modelBankDetails->created_by = Yii::$app->user->id;
                $modelBankDetails->updated_by = Yii::$app->user->id;
                $modelBankDetails->create_time = date('Y-m-d H:i:s');
                $modelBankDetails->update_time = date('Y-m-d H:i:s');

                $modelBankDetails->save();
                Yii::$app->getSession()->setFlash(
                        'success', 'Your Bank is saved successfully.'
                );
                return $this->redirect(['myprofile']);
            } else {

                return $this->render('bankdeails', [
                            'model' => $modelBankDetails,
                ]);
            }
        }
    }

    public function actionMytenants($id) {
        // if(Yii::$app->userdata->getPropertyAgreementType($id)=='2'){
        // $this->redirect('mydashboard');
        // }
        //echo $rid;die;
        $properties = Properties::find()->where(['owner_id' => Yii::$app->user->id, 'status' => '1'])->all();

        $tototProperties = count($properties);

        $ids = array();
        foreach ($properties as $property) {
            $ids[] = $property->id;
        }
        if ($tototProperties > 1 || $tototProperties < 1) {
            $this->layout = 'owner_dashboard';
            $rid = base64_decode($id);
        } else {
            $this->layout = 'owner_dashboard_single';
            //print_r($ids); exit;
            $this->view->params['propertyid'] = (empty($ids[0])) ? '' : $ids[0];
            $rid = $ids[0];
        }
        //echo "<pre>";print_r($bookingsPresent);echo "</pre>";die;
        $bookingsPresent = TenantAgreements::find()->where(['parent_id' => $rid])->andWhere('lease_end_date >= "' . date('Y-m-d H:i:s') . '"')->andWhere('agreement_type !=2')->all();
        $bookingsPast = TenantAgreements::find()->where(['parent_id' => $rid])->andWhere('lease_end_date < "' . date('Y-m-d') . '"')->andWhere('agreement_type !=2')->all();
        // print_r($bookingsPast); exit;
        return $this->render('mytenants', ['presentBookings' => $bookingsPresent, 'bookingsPast' => $bookingsPast, 'pid' => $rid]);
    }

    public function actionTenantdetails($type, $user, $prop_id, $id) {
        $id = $prop_id;
        $this->layout = 'owner_dashboard';

        $myAgreements = TenantAgreements::findOne(['property_id' => "$id", 'tenant_id' => $user]);
        $tenantProfile = \app\models\TenantProfile::find()->where(['tenant_id' => $user])->one();

        $rent = Yii::$app->userdata->getAgreement($id);
        //  echo "<pre>"; print_r($rent); echo "</pre>"; die;
        if ($type == 'p') {
            return $this->render('personal_info_t', ['rent' => $rent, 'myAgreements' => $myAgreements, 'tenantProfile' => $tenantProfile]);
        } else if ($type == 'i') {
            return $this->render('identity_info_t', ['rent' => $rent, 'myAgreements' => $myAgreements, 'tenantProfile' => $tenantProfile]);
        } else if ($type == 'em') {
            return $this->render('employment_data_t', ['rent' => $rent, 'myAgreements' => $myAgreements, 'tenantProfile' => $tenantProfile]);
        } else if ($type == 'l') {
            return $this->render('tenantlease', ['rent' => $rent, 'myAgreements' => $myAgreements, 'tenantProfile' => $tenantProfile]);
        } else {
            return $this->render('tenantdetails', ['rent' => $rent, 'myAgreements' => $myAgreements, 'tenantProfile' => $tenantProfile]);
        }
    }

    public function actionMyagreements() {

        $this->layout = 'tenant_dashboard';
        $tenantAgreements = \app\models\TenantAgreements::find()->where(['tenant_id' => Yii::$app->user->id])->orderBy('created_date DESC')->all();

        return $this->render('tenant_agreements', ['tenantAgreements' => $tenantAgreements]);
    }

    public function actionOwnerpayments($id) {

        $pid = base64_decode($id);
        //echo $pid;die;
        $properties1 = Properties::find()->where(['owner_id' => Yii::$app->user->id, 'status' => '1'])->all();
        $tototProperties = count($properties1);
        $ids = array();
        foreach ($properties1 as $property) {
            $ids[] = $property->id;
        }
        if ($tototProperties > 1 || $tototProperties < 1) {
            $this->layout = 'owner_dashboard';
            $rid = $pid;
        } else {
            $this->layout = 'owner_dashboard_single';
            $this->view->params['propertyid'] = (empty($ids[0])) ? '' : $ids[0];
            $rid = $ids[0];
        }
        //echo $rid;die; 
        if (!empty(Yii::$app->request->get('search_acc_date'))) {
            //echo $rid;die;
            $paymentsummary = OwnerPaymentSummary::find()->where(['owner_id' => Yii::$app->user->id, 'property_id' => $rid, 'payment_month' => Yii::$app->request->get('month'), 'payment_year' => Yii::$app->request->get('year')])->one();


            //echo "<pre>";print_r($paymentsummary);echo "</pre>";die;
        } else {
            $paymentsummary = OwnerPaymentSummary::find()->where(['owner_id' => Yii::$app->user->id, 'property_id' => $rid])->orderBy('id DESC')->one();
        }
        // echo "<pre>";print_r($paymentsummary);echo "</pre>";die;
        $year = Yii::$app->request->get('year');
        $month = Yii::$app->request->get('month');
        $currMonth = date('F');

        $query = PropertyAgreements::find()->where(['owner_id' => Yii::$app->user->id])->all();
        $propertyagreement = PropertyAgreements::find()->where(['property_id' => $rid])->one();
        if ($propertyagreement) {
            $pmspercentage = $propertyagreement->pms_commission;
        } else {
            $pmspercentage = 0;
        }

        if ($paymentsummary) {
            $properties = ['payment_amount' => '0', 'pms_commission' => $paymentsummary->pms_commission, 'tds' => $paymentsummary->tds, 'service_tax' => '0', 'gst' => $paymentsummary->gst];
        } else {
            $properties = ['payment_amount' => '0', 'pms_commission' => '0', 'tds' => '0', 'service_tax' => '0', 'gst' => '0'];
        }

        $ids = [];
        $amount = 0;

        if (empty($year)) {
            $year = Date('Y');
        }
        if (empty($month)) {
            $month = Date('m');
        }
        $date = $year . '-' . $month . '-01';

        return $this->render('ownerpayments', [
                    'tenantpayments' => $properties,
                    'id' => $rid,
                    'paymentsummary' => $paymentsummary,
                    'pms_percentage' => $pmspercentage,
        ]);
    }

    public function actionOwnerlease($id) {
        if (isset(Yii::$app->user->id)) {
            $properties = Properties::find()->where(['owner_id' => Yii::$app->user->id, 'status' => '1'])->all();

            $tototProperties = count($properties);
//echo $tototProperties;die;
            $ids = array();
            foreach ($properties as $property) {
                $ids[] = $property->id;
            }
            if ($tototProperties > 1 || $tototProperties < 1) {

                $this->layout = 'owner_dashboard';
                $rid = base64_decode($id);
            } else {
                $this->layout = 'owner_dashboard_single';
                //print_r($ids); exit;
                $this->view->params['propertyid'] = (empty($ids[0])) ? '' : $ids[0];
                $rid = $ids[0];
            }
            //echo $rid;die;
            $query = PropertyAgreements::find()->where(['property_id' => $rid])->one();
//echo "<pre>";print_r($query);echo "</pre>";die;
            return $this->render('ownerlease', [
                        'agreement' => $query,
                        'prop_id' => $rid
            ]);
        } else {

            return $this->redirect(['site/login']);
        }
    }

    public function actionOwnerleads($id) {
        $rid = base64_decode($id);
        $this->layout = 'owner_dashboard';
        $fav = \app\models\FavouriteProperties::find()->where(['property_id' => $rid])->all();
        // $query = MyAgreements::findOne(['property_id'=>$id]);

        /* if($query->agreement_type=='2'){
          $tenants=\app\models\FavouriteProperties::find()->where(['id'=>0]);
          $dataProvider = new ActiveDataProvider([
          'query' => $tenants,
          ]);
          }

          else{
          $tenants=\app\models\FavouriteProperties::find()->where(['property_id'=>$id])->andWhere('status != "1"');
          $dataProvider = new ActiveDataProvider([
          'query' => $tenants,
          ]);
          } */

        return $this->render('ownerleads', [
                    'dataProvider' => $fav,
                    'property_id' => $id
        ]);
    }

    public function actionOwnerrequests($id) {
        if (isset(Yii::$app->user->id)) {
            // $this->layout = 'owner_dashboard';
            // $propertyServiceRequest = \app\models\ServiceRequest::find()->where(['created_by' => Yii::$app->user->id, 'property_id' => Yii::$app->request->get('id')])->all();
            $properties = Properties::find()->where(['owner_id' => Yii::$app->user->id, 'status' => '1'])->all();

            $tototProperties = count($properties);

            $ids = array();
            foreach ($properties as $property) {
                $ids[] = $property->id;
            }
            if ($tototProperties > 1 || $tototProperties < 1) {
                $this->layout = 'owner_dashboard';
                $rid = base64_decode($id);
            } else {
                $this->layout = 'owner_dashboard_single';
                //print_r($ids); exit;
                $this->view->params['propertyid'] = (empty($ids[0])) ? '' : $ids[0];
                $rid = $ids[0];
            }
            $model = new ServiceRequestAttachment;
            $getAllRequest = $model->getAllRequestByProId(Yii::$app->user->id, $rid);
            return $this->render('ownerrequests', ['propertyServiceRequest' => $getAllRequest, 'prop_id' => $rid]);
        } else {

            return $this->redirect(['site/login']);
        }
    }

    public function actionMydashboard() {
        if (isset(Yii::$app->user->id)) {
            $properties = Properties::find()->where(['owner_id' => Yii::$app->user->id, 'status' => '1'])->all();
            $paymentsummary = OwnerPaymentSummary::find()->where(['owner_id' => Yii::$app->user->id, 'payment_status' => '1'])->all();
            $tototProperties = count($properties);

            $ids = array();
            $bookings = [];

            foreach ($properties as $property) {
                $ids[] = $property->id;
                $propAgreeModel = \app\models\PropertyAgreements::find()->where(['owner_id' => $property->owner_id, 'property_id' => $property->id])->one();
                if ($propAgreeModel) {
                    if ($propAgreeModel->agreement_type == 1) {
                        $bookings[] = \app\models\TenantAgreements::find()->where(['parent_id' => $property->id])->andWhere('lease_end_date > "' . date('Y-m-d') . '" AND lease_start_date < "' . date('Y-m-d') . '"')->one();
                    } else if ($propAgreeModel->agreement_type == 2) {
                        $bookings[] = $propAgreeModel;
                    }
                }
            }

            $ids_booking = array();
            $rent = 0;
            $deposit = 0;

            foreach ($bookings as $booking) {
                if (!empty($booking)) {
                    $ids_booking[] = (!isset($booking->parent_id)) ? $booking->property_id : $booking->parent_id;
                    if (!isset($booking->maintainance)) {
                        $rent += (int) $booking->rent + (int) $booking->manteinance;
                    } else {
                        $rent += (int) $booking->rent + (int) $booking->maintainance;
                    }

                    $deposit += $booking->deposit;
                }
            }

            $netrental = 0;
            if ($paymentsummary) {
                foreach ($paymentsummary as $ps) {
                    $netrental += $ps->net_amount;
                }
            }
            if ($tototProperties > 1 || $tototProperties < 1) {
                $this->layout = 'owner_dashboard_main';
            } else {
                $this->layout = 'owner_dashboard_single';
                $this->view->params['propertyid'] = (empty($ids[0])) ? '' : $ids[0];
            }
            
            $sql = 'SELECT au.* FROM advetisement_user_type as au LEFT JOIN advertisement_banners as ab ON au.advetisement_id=ab.id WHERE ab.status = "1" AND au.owner = "1"';

            $connection = \Yii::$app->db;
            $model = $connection->createCommand($sql);
            $advertisement = $model->queryAll();

            if (!empty($advertisement)) {
                $ad_id = $advertisement[0]['advetisement_id'];
                $advertisements = \app\models\Advertisements::find()->where(['id' => $ad_id])->one();
            } else {
                $advertisements = array();
            }

            return $this->render('mydashboard', array('properties' => $properties, 'rent' => $rent, 'ids_booking' => $ids_booking, 'tototProperties' => $tototProperties, 'advertisements' => $advertisements, 'deposit' => $deposit, 'netrental' => $netrental));
        } else {

            return $this->redirect(['site/login']);
        }
    }

    
     public function actionDaysremainingforpass () {
        $ids = Yii::$app->user->id;
        $modelUser = \app\models\Users::find()->where(['id' => $ids])->one();
        $passUpDate = $modelUser->pass_up_date;
        $datetime1 = new \DateTime($passUpDate);
        $today_date = new \DateTime(date('Y-m-d H:i:s'));
        $daysInterval = $datetime1->diff($today_date);
        echo 90 - $daysInterval->days;
    }
    
    public function actionFirstloginpasschange () {
        $ids = Yii::$app->user->id;
        $modelUser = \app\models\Users::find()->where(['id' => $ids])->one();
        $createdby = (int) $modelUser->created_by;
        $pass_up_date = $modelUser->pass_up_date;
        $data = [];
        $data['createdBy'] = $createdby;
        $data['passUpDate'] = $pass_up_date;
        header('Content-Type: application/json');
        echo json_encode($data);exit;
    }
    
    
    public function actionDashboard() {
        $this->layout = 'tenant_dashboard';
        if (($model = Users::findOne(Yii::$app->user->id)) !== null) {
            $bookings = \app\models\TenantAgreements::find()->where(['tenant_id' => Yii::$app->user->id])->all();
            $dueAmount = 0;
            $nxtAmount = 0;
            $rent = 0;
            $sumofmoney = 0;
            $lease_name = '';
            $total_remaining = 0;
            $propertynames = array();
            $i = 0;
            foreach ($bookings as $wish) {
                //echo $wish->property_id;die;
                $propertyname = \app\models\Properties::find()->where(['id' => $wish->property_id])->one();
                //echo "<pre>";print_r($propertyname);echo "</pre>";die;
                if (!empty($propertyname)) {
                    $propertynames[$i] = $propertyname->property_name;
                } else {
                    $propertynames[$i] = '';
                }
                $rent = $wish->rent + $wish->maintainance;
                /*
                 * get over due payments
                 * */
                $sql = 'SELECT sum(total_amount) as sumofmoney FROM tenant_payments where tenant_id="' . Yii::$app->user->id . '" AND payment_status = 0 AND due_date < "' . date('Y-m-d') . '" ';
//echo $sql;die;
                $connection = \Yii::$app->db;
                $model = $connection->createCommand($sql);
                $duePayments = $model->queryAll();
                // echo "<pre>";print_r($duePayments);echo "</pre>"; 
                if (!empty($duePayments)) {
                    foreach ($duePayments as $duePayment) {
                        $sumofmoney += $duePayment['sumofmoney'];
                    }
                }
                
                $lastdate = \app\models\TenantAgreements::find()->where(['tenant_id' => Yii::$app->user->id])->andWhere('`lease_end_date` > "' . Date('Y-m-d h:i:s') . '"')->one();
                if (!empty($lastdate)) {
                    $contract_end_date = $lastdate->lease_end_date;
                    $difference = date_diff(date_create($contract_end_date), date_create(Date('Y-m-d h:i:s')));
                    $total_remaining = $difference->days + 1;
                    $lease_name = yii::$app->userdata->getPropertyNameById($lastdate->parent_id);
                } else {
                    $total_remaining = 0;
                }
                /*
                 * get over due payments  ends
                 * */

                /*
                 * get payments due next 30 days
                 * */
                //$sql = 'SELECT tenant_payments.* , YEAR(created_date), MONTH(created_date),   sum(total_amount) as sumofmoney FROM tenant_payments where tenant_id="' . Yii::$app->user->id . '" AND property_id ="' . $wish->property_id . '" AND payment_status = 0 AND (created_date >= NOW() AND created_date <= NOW() + INTERVAL 10 DAY)  GROUP BY YEAR(created_date), MONTH(created_date)';
                $sql = 'SELECT sum(total_amount) as sumofmoney FROM tenant_payments where tenant_id="' . Yii::$app->user->id . '" AND payment_status = 0 AND (due_date <= "' . date("Y-m-d") . '" + INTERVAL 30 DAY) AND  due_date > "' . date("Y-m-d") . '"';

                $connection = \Yii::$app->db;
                $model = $connection->createCommand($sql);
                $nextPayments = $model->queryAll();
                $nextsumofmoney = 0;

                if (!empty($nextPayments)) {
                    foreach ($nextPayments as $nextPayment) {
                        $nextsumofmoney += $nextPayment['sumofmoney'];
                        //$nextsumofmoney += $nextPayment['total_amount'];
                    }
                    
                    $nxtAmount += $nextsumofmoney;
                }
            }

            $sql1 = 'SELECT au.* FROM advetisement_user_type as au LEFT JOIN advertisement_banners as ab ON au.advetisement_id=ab.id WHERE ab.status = "1" AND au.tenant = "1"';
//echo $sql;die;
            $connection1 = \Yii::$app->db;
            $model1 = $connection1->createCommand($sql1);
            $advertisement = $model1->queryAll();



            //$advertisement = \app\models\AdvertisementUserType::find()->where(['owner' => '1'])->all();
            //echo "<pre>";print_r($advertisement);echo "</pre>";die;
            //echo $advertisement[0]->advetisement_id;die;
            if (!empty($advertisement)) {
                $ad_id = $advertisement[0]['advetisement_id'];
                $advertisements = \app\models\Advertisements::find()->where(['id' => $ad_id])->one();
                //echo "<pre>";print_r($advertisements);echo "</pre>";die;
            } else {
                $advertisements = array();
            }

            //echo "<pre>";print_r($propertynames);echo "</pre>";die;
            return $this->render('dashboard', array('model' => $model, 'dueAmount' => $sumofmoney, 'nxtAmount' => $nxtAmount, 'remaining_days' => $total_remaining, 'lease_name' => $lease_name, 'advertisements' => $advertisements, 'propertynames' => $propertynames));
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * This function will be triggered when user is successfuly authenticated using some oAuth client.
     *
     * @param yii\authclient\ClientInterface $client
     * @return boolean|yii\web\Response
     */
    public function oAuthSuccess($client) {
        // get user data from client

        $userAttributes = $client->getUserAttributes();
        /* echo  "<pre>";print_r($userAttributes);
          die; */
        $authclient = Yii::$app->request->get('authclient');
        if ($authclient == 'google') {

            $user = User::findByUsername($userAttributes['emails'][0]['value']);
            /*  echo  "<pre>";print_r($user);
              die; */
            // $user = Users::find()->where(['email'=>$userAttributes['emails'][0]['value']])->one();
        } else {
            $user = User::findByUsername($userAttributes['email']);
            //$user = Users::find()->where(['email'=>$userAttributes['email']])->one();
        }
        if (!empty($user)) {

            Yii::$app->user->login($user);
        } else {
            //Simpen disession attribute user dari Google
            $session = Yii::$app->session;
            $session['attributes'] = $userAttributes;
            Yii::$app->getSession()->setFlash(
                    'registerFormSubmitted', 'You are not registered with us, please SignUp here.'
            );
            // redirect ke form signup, dengan mengset nilai variabell global successUrl
            return $this->redirect(['users/register']);
        }
        // do some thing with user data. for example with $userAttributes['email']
    }

    /**

      /**
     * This function will be triggered when user is successfuly authenticated using some oAuth client.
     *
     * @param yii\authclient\ClientInterface $client
     * @return boolean|yii\web\Response
     */
    public function oAuthSuccess2($client) {
        // get user data from client

        $userAttributes = $client->getUserAttributes();
        $authclient = Yii::$app->request->get('authclient');


        if ($authclient == 'google') {

            $user = Users::find()->where(['email' => $userAttributes['emails'][0]['value']])->one();

            $email = $userAttributes['emails'][0]['value'];
            $name = $userAttributes['displayName'];

            $_SESSION['attributes']['email'] = $email;
            $_SESSION['attributes']['name'] = $name;
        } else {
            $user = Users::find()->where(['email' => $userAttributes['email']])->one();

            $_SESSION['attributes']['email'] = $userAttributes['email'];
            $_SESSION['attributes']['name'] = $userAttributes['name'];
        }

        if (empty($user)) {
            return $this->redirect(['users/register']);
        } else {

            Yii::$app->getSession()->setFlash(
                    'success', 'You are already registered with us, please LogIn with your deatils.'
            );
            // redirect ke form signup, dengan mengset nilai variabell global successUrl
            return $this->redirect(['site/login']);
        }
        // do some thing with user data. for example with $userAttributes['email']
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex() {
        $this->layout = 'home';
        $this->view->params['head_title'] = "Property Management | Property Management Services Bangalore";
        $this->view->params['meta_desc'] = "Property Management, Property Management Services Bangalore, Property Management Companies in Bangalore, Rental Websites, Real Estate Management, Easyleases.";
        return $this->render('index');
    }

    public function actionPaymydue() {
        $this->layout = 'signup';
        if (!empty(Yii::$app->request->post('email_mobile'))) {
            $userModel = User::find()->where(['login_id' => Yii::$app->request->post('email_mobile')])->orWhere(['phone' => Yii::$app->request->post('email_mobile')])->andWhere(['user_type' => 3])->one();
            if (!empty($userModel)) {
                $ID = $userModel->id;
                $this->layout = 'paymydues_layout';
                $datedue = Date('Y-m-d', strtotime('+1 month'));
                $sql = 'SELECT tp.id, tp.parent_id, tp.payment_des, tp.payment_status, tp.total_amount, tp.due_date, tp.payment_type as payment_for, tp.penalty_amount, tp.neft_reference,ta.late_penalty_percent, ta.min_penalty FROM tenant_payments tp INNER JOIN tenant_agreements ta ON tp.agreement_id = ta.id WHERE tp.tenant_id="' . $ID . '" and tp.due_date <"' . $datedue . '" and tp.payment_status IN (0, 2) ORDER BY due_date';
                $connection = \Yii::$app->db;
                $model = $connection->createCommand($sql);
                $pastPayment = $model->queryAll();
                return $this->render('paymydues_list', ['pastPayment' => $pastPayment, 'ID' => $ID]);
            } else {
                Yii::$app->getSession()->setFlash('error', 'No record found for given details.');
            }
        }
        
        return $this->render('paymydue');
    }
    
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionBookinginfo() {
        $this->layout = 'booking';

        $property_type = Yii::$app->request->get('property_type');
        $property_id = Yii::$app->request->get('property_id');
        $rent = Yii::$app->request->get('rent');
        $deposit = Yii::$app->request->get('deposit');
        $token_amount = Yii::$app->request->get('token_amount');
        $parent_property_id = Yii::$app->request->get('parent_property_id');
        $maintenance = Yii::$app->request->get('maintenance');
        $modelvisit = new \app\models\PropertyVisits;
        if (($model = \app\models\TenantProfile::findOne(['tenant_id' => Yii::$app->user->id])) !== null) {

            $modelUserIdProofs = UserIdProofs::findOne(['user_id' => Yii::$app->user->id, 'proof_type' => 'identity']);
            if (empty($modelUserIdProofs)) {
                $modelUserIdProofs = new UserIdProofs;
            }

            if ($model->load(Yii::$app->request->post())) {

                $fileInstance = UploadedFile::getInstance($modelUserIdProofs, 'proof_document_url');

                if ($fileInstance) {
                    $name = date('ymdHis') . str_replace(' ', '', $fileInstance->name);
                    $fileInstance->saveAs('uploads/proofs/' . $name);

                    // $modelUserIdProofs->proof_document_url = Url::home(true).'uploads/proofs/'.$name ;
                    $modelUserIdProofs->proof_document_url = 'uploads/proofs/' . $name;
                    $modelUserIdProofs->created_by = Yii::$app->user->id;
                    $modelUserIdProofs->updated_by = Yii::$app->user->id;
                    $modelUserIdProofs->proof_type = 'identity';
                    $modelUserIdProofs->create_time = date('Y-m-d H:i:s');
                    $modelUserIdProofs->update_time = date('Y-m-d H:i:s');
                    $modelUserIdProofs->save(false);
                }
                $model->save(false);
                // die;
                Yii::$app->getSession()->setFlash(
                        'success', 'Your address and Bank information is saved.'
                );

                return $this->redirect(['payment', 'property_type' => $property_type, 'property_id' => $property_id, 'deposit' => $deposit, 'rent' => $rent, 'token_amount' => $token_amount, 'parent_property_id' => $parent_property_id, 'maintenance' => $maintenance]);
            }
            return $this->render('booking_personal_info', ['model' => $model, 'modelvisit' => $modelvisit, 'modelIdProofs' => $modelUserIdProofs]);
        } else if (($model = \app\models\ApplicantProfile::findOne(['applicant_id' => Yii::$app->user->id])) !== null) {

            $modelUserIdProofs = UserIdProofs::findOne(['user_id' => Yii::$app->user->id, 'proof_type' => 'identity']);
            if (empty($modelUserIdProofs)) {
                $modelUserIdProofs = new UserIdProofs;
            }

            //if ($model->load(Yii::$app->request->post())) {
            if ($modelvisit->load(Yii::$app->request->post())) {
                $modelvisit = \app\models\PropertyVisits::findOne(['applicant_id' => Yii::$app->userdata->getEmailById(Yii::$app->user->id), 'property_id' => Yii::$app->request->post('property_id')]);
                if ($modelvisit) {
                    $modelvisit->visit_date = Yii::$app->request->post('PropertyVisits')['visit_date'];
                    $modelvisit->visit_time = Yii::$app->request->post('PropertyVisits')['visit_time'];
                } else {
                    $modelvisit = new \app\models\PropertyVisits();
                    $modelvisit->visit_date = Yii::$app->request->post('PropertyVisits')['visit_date'];
                    $modelvisit->visit_time = Yii::$app->request->post('PropertyVisits')['visit_time'];
                    $modelvisit->applicant_id = Yii::$app->userdata->getEmailById(Yii::$app->user->id);
                    $modelvisit->property_id = Yii::$app->request->post('property_id');
                    $modelvisit->created_by = Yii::$app->user->id;
                    //$modelvisit->updated_by = 0;
                    $modelvisit->created_date = date('Y-m-d H:i:s');
                    $modelvisit->status = 3;
                    $modelvisit->type = Yii::$app->request->get('property_type');
                    $modelvisit->confirm = 0;
                }

                $fileInstance = UploadedFile::getInstance($modelUserIdProofs, 'proof_document_url');

                if ($fileInstance) {
                    $name = date('ymdHis') . str_replace(' ', '', $fileInstance->name);
                    $fileInstance->saveAs('uploads/proofs/' . $name);

                    // $modelUserIdProofs->proof_document_url = Url::home(true).'uploads/proofs/'.$name ;
                    $modelUserIdProofs->proof_document_url = 'uploads/proofs/' . $name;
                    $modelUserIdProofs->created_by = Yii::$app->user->id;
                    $modelUserIdProofs->updated_by = Yii::$app->user->id;
                    $modelUserIdProofs->proof_type = 'identity';
                    $modelUserIdProofs->create_time = date('Y-m-d H:i:s');
                    $modelUserIdProofs->update_time = date('Y-m-d H:i:s');
                    $modelUserIdProofs->save(false);
                }
                //$model->save(false);
                $modelvisit->save();
                // die;
                if (Yii::$app->request->post('sch_book') == 1) {
                    return $this->redirect(['payment', 'property_type' => $property_type, 'property_id' => $property_id, 'deposit' => $deposit, 'rent' => $rent, 'token_amount' => $token_amount, 'parent_property_id' => $parent_property_id, 'maintenance' => $maintenance]);
                } else {
                    // create new view page for saying thank you or something according to requirement.
                    return $this->render('booking_personal_info', ['model' => $model, 'modelvisit' => $modelvisit, 'modelIdProofs' => $modelUserIdProofs]);
                }
                Yii::$app->getSession()->setFlash(
                        'success', 'You have successfully scheduled this property.'
                );
            }
            return $this->render('booking_personal_info', ['model' => $model, 'modelvisit' => $modelvisit, 'modelIdProofs' => $modelUserIdProofs]);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionChangepassword() {


        $model = new \app\models\PasswordForm;
        $modeluser = Users::find()->where([
                    'id' => Yii::$app->user->id
                ])->one();

        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = 'json';
            return \yii\bootstrap\ActiveForm::validate($model);
        } else if ($model->load(Yii::$app->request->post())) {

            try {
                $modeluser->password = md5($_POST['PasswordForm']['newpass']);
                $modeluser->pass_up_date = date('Y-m-d H:i:s');
                //print_r($today_date);exit;
                if ($modeluser->save(false)) {
                    Yii::$app->getSession()->setFlash(
                            'success', 'Password changed'
                    );
                    if ($modeluser->user_type == 6 || $modeluser->user_type == 7) {
                        return $this->redirect('../external/myprofile');
                    } else if ($modeluser->user_type == 5) {
                        return $this->redirect('../advisers/myprofile');
                    } else {
                        return $this->redirect(['myprofile']);
                    }
                } else {
                    Yii::$app->getSession()->setFlash(
                            'success', 'Password not changed'
                    );
                    if ($modeluser->user_type == 6 || $modeluser->user_type == 7) {
                        return $this->redirect('../external/myprofile');
                    } else if ($modeluser->user_type == 5) {
                        return $this->redirect('../advisers/myprofile');
                    } else {
                        return $this->redirect(['myprofile']);
                    }
                }
            } catch (\Exception $e) {
                Yii::$app->getSession()->setFlash(
                        'success', "{$e->getMessage()}"
                );
                // if($modeuser->type==6){
                //     	return $this->redirect(['/sales/myprofile']);
                //     }
                //     else{
                return $this->renderAjax('change_password', [
                            'model' => $model
                ]);
                // }
            }
        } else {

            return $this->renderAjax('change_password', [
                        'model' => $model
            ]);
        }
    }

    public function actionChangedate() {
        $tenantAgreement = \app\models\TenantAgreements::findOne($_POST['agr_id']);
        if ($tenantAgreement) {
            $res = Yii::$app->userdata->datediff($tenantAgreement->lease_start_date, $_POST['date'], $tenantAgreement->min_stay);

            if ($res == 'no') {
                echo 'Please check minimum stay time';
                die;
            } else {
                $tenantAgreement->lease_end_date = date('Y-m-d H:i:d', strtotime($_POST['date']));
                $tenantAgreement->save(false);
                die;
            }
        }
    }

    public function actionCreatepdf($id) {
        $tenantAgreement = \app\models\TenantAgreements::findOne($id);
        $html = "<html><head> <title> Agreement </title><link href='../web/css/applicant.css' rel='stylesheet'>
</head><body><div class='formtexttable'>
                	<ul>
                    	<li>Rent amount<span>Rs. " . $tenantAgreement->rent . "</span></li>
                        <li>Maintenance amount<span>Rs.  " . $tenantAgreement->maintainance . "</span></li>
                        <li>Deposit amount<span>Rs.  " . $tenantAgreement->deposit . ".</span></li>
                        <li>Minimum stay period (in months)<span>  " . $tenantAgreement->min_stay . "</span></li>
                        <li>Notice period (in months)<span>  " . $tenantAgreement->notice_period . "</span></li>
                        <li>Late fee penalty (%)<span>  " . $tenantAgreement->late_penalty_percent . "</span></li>
						<li>Lease start date<span>  " . $tenantAgreement->lease_start_date . "</span></li>
						<li>Lease termination date<span>  " . $tenantAgreement->lease_end_date . "</span></li>
                        <li>Late fee minimum penalty<span>Rs. " . $tenantAgreement->min_penalty . "</span></li>

                    </ul>
                	</div></body> </html>
                 ";
        $this->PDF($html, date('ymdhis'));
    }

    public function actionCreatepdfagreement($id) {
        $agreement = \app\models\PropertyAgreements::findOne($id);
        $html = "<html><head> <title> Agreement </title>
		<link href='../web/css/owner.css' rel='stylesheet'>
		<link href='../web/css/bootstrap.min.css' rel='stylesheet'>
</head><body><div class='col-lg-7 col-xs-offset-2 myprofile'>

				<div class='mybankdetailnew mt'>
					<h2>" . Yii::$app->userdata->getPropertyNameById($agreement->property_id) . "</h2>
				</div>
					<div class='mybankdetailnew'>
					<h2>Key Terms</h2>
					</div>
					<div class='col-lg-4'>
					<div class='boxtenentnew'>
								Rent
								 <span>Rs. " . $agreement->rent . "</span>
								 </div>
					</div>
					<div class='col-lg-4'>
					<div class='boxtenentnew'>
								Maintenance
								 <span>Rs. " . $agreement->manteinance . " </span>
								 </div>
					</div>
					<div class='col-lg-4'>
					<div class='boxtenentnew'>
								Deposit
								 <span>Rs.  " . $agreement->deposit . "</span>
								 </div>
					</div>
					<div class='col-lg-4'>
					<div class='boxtenentnew'>
								Notice Period
								 <span>" . $agreement->notice_peroid . " month</span>
								 </div>
					</div>
					<div class='col-lg-4'>
					<div class='boxtenentnew'>
								Minimum contract period
								 <span>" . $agreement->min_contract_peroid . " Month</span>
								 </div>
					</div>
					<div class='col-lg-4'>
					<div class='boxtenentnew'>Furniture Amount
								  <span>Rs. " . $agreement->furniture_rent . "</span>
								 </div>
					</div>
					<div class='col-lg-4'>
					<div class='boxtenentnew'>
								 PMS Commission %
								 <span>" . $agreement->pms_commission . "%</span>
								 </div>
					</div>
					<div class='col-lg-4'>
					<div class='boxtenentnew'>
								Contract Start date
								 <span>" . date('d-m-Y', strtotime($agreement->contract_start_date)) . "</span>
								 </div>
					</div>
					<div class='col-lg-4'>
					<div class='boxtenentnew'>
								Contract End date
								 <span>" . date('d-m-Y', strtotime($agreement->contract_end_date)) . "</span>
								 </div>
					</div>
					</body> </html>
                 ";
        $this->PDF($html, date('ymdhis'));
    }

    public function actionCreatepaymentpdf($id, $for) {
    
        $pdf = Yii::$app->userdata->CreatePaymentReceipt($id,"WEB");
        header("Content-Type: application/pdf");
        header("Cache-Control: max-age=0");
        header("Accept-Ranges: none");
        header("Content-Disposition: attachment; filename=\"" . date('ymdhis') . ".pdf\"");

        echo $pdf;
        
        exit();
        
    }


    public function actionCompletepayment() {
        $this->layout = 'booking';
        $parent_property_id = Yii::$app->request->get('parent_property_id');
        $model = Properties::find()->where(['properties.id' => $parent_property_id])->joinWith('propertyListing')->one();
        $modelTenantPayments = new TenantPayments; //
        $modelPropertyBooking = new BookProperties;
        $model = Properties::find()->where(['properties.id' => $parent_property_id])->joinWith('propertyListing')->one();
        $property_id = Yii::$app->request->get('property_id');
        $parent_property_id = Yii::$app->request->get('parent_property_id');
        $property_type = Yii::$app->request->get('property_type');
        $deposit = Yii::$app->request->get('deposit');
        $token_amount = Yii::$app->request->get('token_amount');
        $rent = Yii::$app->request->get('rent');
        if (isset($_POST['payment_mode'])) {

            $wallet = \app\models\Wallets::findone(['user_id' => Yii::$app->user->id]);

            if (count($wallet) == 0) {

                $wallet = new \app\models\Wallets();
                $wallet->user_id = Yii::$app->user->id;
                $wallet->amount = $token_amount;
                $wallet->created_by = Yii::$app->user->id;
                $wallet->save(false);
            } else {

                $wallet->amount = (int) $wallet->amount + (int) $token_amount;

                $wallet->updated_date = Date('Y-m-d h:i:s');

                $wallet->save(false);
            }

            $walletHistory = new \app\models\WalletsHistory();

            $walletHistory->email_id = Yii::$app->userdata->getUserEmailById(Yii::$app->user->id);

            $walletHistory->amount = $token_amount;

            $walletHistory->property_id = Yii::$app->request->get('parent_property_id');

            $walletHistory->created_by = Yii::$app->user->id;

            $walletHistory->user_id = Yii::$app->user->id;

            $walletHistory->transaction_type = '1';

            $walletHistory->save(false);

            $property_name = Yii::$app->userdata->getPropertyNameById(Yii::$app->request->get('parent_property_id'));

            $subject = "Your booking confirmation";
            $msg = "Hello <Applicant Name><br/><br/>We confirm receipt of Rs. $token_amount payment towards token amount for $property_name. The amount has been added to your wallet.<br/><br/>Next step for you is to visit the property and confirm or reject the property within next 3 days.<br/><br/>Please note that property booking is held for 3 days and if there is no confirmation then the booking will be released and your payment would be automatically refunded to your bank<br/><br/>With Regards,<br/>EasyLeases Team<br/><img src='" . Url::home(true) . "images/property_logo.png' alt=''>";

            //Yii::$app->userdata->doMail(Yii::$app->userdata->getEmail(),$subject,$msg);

            return $this->redirect(['visit', 'model' => $model, 'parent_property_id' => $parent_property_id, 'property_type' => $property_type, 'property_id' => $property_id, 'deposit' => $deposit, 'token_amount' => $token_amount, 'payment_id' => '123123123']);
        }
        return $this->render('completepayment', ['model' => $model, 'PropertyBooking' => $modelPropertyBooking]);
    }

    public function actionPayment() {

        $this->layout = 'booking';
        $parent_property_id = Yii::$app->request->get('parent_property_id');
        $model = Properties::find()->where(['properties.id' => $parent_property_id])->joinWith('propertyListing')->one();
        $modelTenantPayments = new TenantPayments;
        $modelPropertyBooking = new BookProperties;
        $model = Properties::find()->where(['properties.id' => $parent_property_id])->joinWith('propertyListing')->one();
        $property_id = Yii::$app->request->get('property_id');
        $parent_property_id = Yii::$app->request->get('parent_property_id');
        $property_type = Yii::$app->request->get('property_type');
        $deposit = Yii::$app->request->get('deposit');
        $token_amount = Yii::$app->request->get('token_amount');
        $rent = Yii::$app->request->get('rent');

        if ($modelPropertyBooking->load(Yii::$app->request->post())) {
            // if(count($_POST)!=0){
            $bookingsData = FavouriteProperties::find()->where(['property_id' => $parent_property_id, 'applicant_id' => Yii::$app->userdata->email, 'status' => '1'])->one();
            if (count($bookingsData) == 1) {
                $bookingsData->delete();
            }
            if ($property_type != '3') {
                $booking_data = FavouriteProperties::find()->where(['child_properties' => $property_id, 'applicant_id' => Yii::$app->userdata->email])->one();
            } else {
                $booking_data = FavouriteProperties::find()->where(['child_properties' => $parent_property_id, 'applicant_id' => Yii::$app->userdata->email])->one();
            }

            if (count($booking_data) == 0) {
                $modelPropertyBooking->property_id = $parent_property_id;
                if ($property_type != '3') {
                    $modelPropertyBooking->child_properties = $property_id;
                } else {
                    $modelPropertyBooking->child_properties = $parent_property_id;
                }

                $modelPropertyBooking->applicant_id = Yii::$app->userdata->email;
                $modelPropertyBooking->created_by = Yii::$app->user->id;
                $modelPropertyBooking->updated_by = Yii::$app->user->id;
                $modelPropertyBooking->created_date = date('Y-m-d H:i:s');
                $modelPropertyBooking->status = 2;
                $modelPropertyBooking->type = $property_type;

                $inserted_data = $modelPropertyBooking->save(false);
                if ($inserted_data) {
                    return $this->redirect(['completepayment', 'model' => $model, 'parent_property_id' => $parent_property_id, 'property_type' => $property_type, 'property_id' => $property_id, 'deposit' => $deposit, 'token_amount' => $token_amount]);
                } else {
                    Yii::$app->getSession()->setFlash(
                            'error', 'Some Error Occured Please Try Again.'
                    );
                }
            } else {
                if ($booking_data != '2') {
                    $booking_data->status = 2;
                }
                $booking_data->save(false);
                return $this->redirect(['completepayment', 'model' => $model, 'parent_property_id' => $parent_property_id, 'property_type' => $property_type, 'property_id' => $property_id, 'deposit' => $deposit, 'token_amount' => $token_amount]);
            }
        }


        /*
          $parent_property_id = Yii::$app->request->get('parent_property_id');
          $property_type = Yii::$app->request->get('property_type');
          $property_id = Yii::$app->request->get('property_id');
          $deposit = Yii::$app->request->get('deposit');
          $token_amount = Yii::$app->request->get('token_amount');
          $rent = Yii::$app->request->get('rent');

          $model = Properties::find()->where(['properties.id'=>$parent_property_id])->joinWith('propertyListing')->one();
          //echo "<pre>"; print_r($model);die;
          $modelTenantPayments = new TenantPayments ;
          $amountWallet = 0;
          $walletAmount = Wallets::findOne(['user_id'=>Yii::$app->user->id]);

          if($walletAmount){
          $amountWallet = $walletAmount->amount ;
          }
          $myagreements =  new \app\models\TenantAgreements ;
          $agreements =  \app\models\PropertyAgreements::findOne(['property_id'=>$parent_property_id]);


          if ($modelTenantPayments->load(Yii::$app->request->post())  && $myagreements->load(Yii::$app->request->post())  ) {

          $modelTenantPayments->created_by = Yii::$app->user->id ;
          $modelTenantPayments->updated_by = Yii::$app->user->id ;
          $modelTenantPayments->payment_type = 4 ;
          $modelTenantPayments->parent_id = $parent_property_id ;
          $modelTenantPayments->original_amount = $modelTenantPayments->total_amount ;
          $modelTenantPayments->remarks = 'added a visit';
          $modelTenantPayments->payment_des = 'token amount added';
          $modelTenantPayments->tenant_id =  Yii::$app->user->id  ;
          $modelTenantPayments->property_id = $property_id ;
          $modelTenantPayments->created_date = date('Y-m-d H:i:s');

          if($modelTenantPayments->save(false)){

          echo $d1 = strtotime(date('Y-m-01',strtotime($myagreements->lease_start_date)));
          echo $d2 = strtotime(date('Y-m-01',strtotime($myagreements->lease_end_date)));
          die;
          $min_date = min($d1, $d2);
          $max_date = max($d1, $d2);
          $i = 0;


          $tenantPayments = new TenantPayments ;
          $tenantPayments->tenant_id =  Yii::$app->user->id  ;
          $tenantPayments->created_by = Yii::$app->user->id ;
          $tenantPayments->updated_by = Yii::$app->user->id ;
          $tenantPayments->payment_type = 2 ;
          $tenantPayments->remarks = 'not';
          $tenantPayments->payment_des = 'Rent';
          $tenantPayments->parent_id = $parent_property_id ;
          $tenantPayments->original_amount = 0 ;
          $tenantPayments->total_amount = 0 ;
          $tenantPayments->property_id = $property_id ;
          $tenantPayments->created_date = date('Y-m-01 H:i:s',$d1);
          $tenantPayments->save(false);

          while (($min_date = strtotime("+1 MONTH", $min_date)) <= $max_date) {

          $tenantPayments = new TenantPayments ;
          $tenantPayments->tenant_id =  Yii::$app->user->id  ;
          $tenantPayments->created_by = Yii::$app->user->id ;
          $tenantPayments->updated_by = Yii::$app->user->id ;
          $tenantPayments->payment_type = 2 ;
          $tenantPayments->remarks = 'not';
          $tenantPayments->payment_des = 'Rent';
          $tenantPayments->parent_id = $parent_property_id ;
          $tenantPayments->original_amount = 0 ;
          $tenantPayments->total_amount = 0 ;
          $tenantPayments->property_id = $property_id ;
          $tenantPayments->created_date = date('Y-m-d H:i:s',$min_date);
          $tenantPayments->save(false);
          $i++;
          }

          if( $min_date ==  $max_date){

          $tenantPayments = new TenantPayments ;
          $tenantPayments->tenant_id =  Yii::$app->user->id  ;
          $tenantPayments->created_by = Yii::$app->user->id ;
          $tenantPayments->updated_by = Yii::$app->user->id ;
          $tenantPayments->payment_type = 2 ;
          $tenantPayments->remarks = 'not';
          $tenantPayments->payment_des = 'Rent';
          $tenantPayments->parent_id = $parent_property_id ;
          $tenantPayments->original_amount = 0 ;
          $tenantPayments->total_amount = 0 ;
          $tenantPayments->property_id = $property_id ;
          $tenantPayments->created_date = date('Y-m-d H:i:s',$min_date);
          $tenantPayments->save(false);

          }

          $modelHistory = new WalletsHistory ;
          $modelHistory->amount =  $modelTenantPayments->total_amount ;
          $modelHistory->email_id = Yii::$app->userdata->email ;
          $modelHistory->transaction_type = 4 ;
          $modelHistory->created_by = Yii::$app->user->id ;
          $modelHistory->created_date = date('Y-m-d H:i:s');
          $modelHistory->save(false);

          $walletAmount->amount =  $amountWallet  -   $modelTenantPayments->total_amount ;
          $walletAmount->updated_by = Yii::$app->user->id ;
          $walletAmount->updated_date = date('Y-m-d H:i:s') ;
          $walletAmount->save(false);


          $myagreements->tenant_id =  Yii::$app->user->id  ;
          $myagreements->parent_id = $parent_property_id ;
          $myagreements->property_id = $property_id ;
          $myagreements->rent = $rent ;
          $myagreements->maintainance =  $agreements->manteinance ;
          $myagreements->deposit = $agreements->deposit;
          $myagreements->min_stay = $agreements->min_contract_peroid ;
          $myagreements->notice_period = $agreements->notice_peroid ;
          $myagreements->created_by = Yii::$app->user->id ;
          $myagreements->updated_by = Yii::$app->user->id ;
          $myagreements->late_penalty_percent = Yii::$app->userdata->getPenalty()  ;
          $myagreements->min_penalty = Yii::$app->userdata->getMinPenalty()  ;
          $myagreements->lease_start_date = date('Y-m-d',strtotime($myagreements->lease_start_date)) ;
          $myagreements->lease_end_date = date('Y-m-d',strtotime($myagreements->lease_end_date)) ;
          $myagreements->end_date = date('Y-m-d',strtotime($myagreements->lease_end_date)) ;
          $myagreements->created_date = date('Y-m-d H:i:s') ;
          $myagreements->updated_date = date('Y-m-d H:i:s') ;
          $myagreements->save(false);

          Yii::$app->getSession()->setFlash(
          'success','Your property is booked not confirmed, Please enter date and time for visit to confirm booking.'
          );

          return $this->redirect(['visit','model' => $model, 'parent_property_id' => $parent_property_id ,'property_type' => $property_type ,'property_id' => $property_id ,'deposit' => $deposit ,'token_amount' => $token_amount,'agreements'=>$agreements,'agreement_id'=>$myagreements->id]);
          }
          } */

        return $this->render('payment', ['model' => $model, 'tenantPayments' => $modelTenantPayments, 'PropertyBooking' => $modelPropertyBooking]);
    }

    public function actionPropertydetails() {
        $request = Yii::$app->request->post();
        $id = $request['id'];
        $connection = \Yii::$app->db;
        $model = $connection->createCommand("select properties.property_name,property_listing.`property_id`,property_listing.`deposit`,property_listing.`rent`,property_listing.`token_amount`,favourite_properties.`child_properties`,favourite_properties.type from favourite_properties left join property_listing on favourite_properties.property_id=property_listing.property_id left join properties on properties.id = property_listing.property_id where favourite_properties.id=$id");
        $properties = $model->queryOne();
        echo json_encode($properties);
    }

    public function actionVisit() {
        $this->layout = 'booking';

        $beds = Yii::$app->request->get('beds');
        $parent_property_id = Yii::$app->request->get('parent_property_id');
        $property_type = Yii::$app->request->get('property_type');
        $property_id = Yii::$app->request->get('property_id');
        $deposit = Yii::$app->request->get('deposit');
        $token_amount = Yii::$app->request->get('token_amount');
        $property_name = Yii::$app->request->get('property_name');
        $rooms = Yii::$app->request->get('rooms');
        $agreement_id = Yii::$app->request->get('agreement_id');
        $model = Properties::findOne($property_id);
        if (!Yii::$app->request->get('payment_id')) {
            return $this->redirect(['completepayment', 'model' => $model, 'parent_property_id' => $parent_property_id, 'property_type' => $property_type, 'property_id' => $property_id, 'deposit' => $deposit, 'token_amount' => $token_amount]);
        }


        $visitDateTime = $this->findVisitDateTime($parent_property_id);
        if (count($visitDateTime) == '') {
            $visitdate = '';
            $visittime = '';
        } else {
            $visitdate = $visitDateTime['visit_date'];
            $visittime = $visitDateTime['visit_time'];
        }
        $modelPropertyVisits = new PropertyVisits;
        if ($modelPropertyVisits->load(Yii::$app->request->post())) {
            if ($modelPropertyVisits->validate()) {

                $visits = \app\models\FavouriteProperties::find()->where(['property_id' => $parent_property_id, 'applicant_id' => Yii::$app->userdata->email])->one();
                $PropertyVisitsData = Yii::$app->request->post('PropertyVisits');
                if (count($visits) == 0) {
                    $modelPropertyVisits->property_id = $parent_property_id;
                    if (Yii::$app->request->get('property_type') == '3') {
                        $modelPropertyVisits->child_properties = $parent_property_id;
                    } else {
                        $modelPropertyVisits->child_properties = $property_id;
                    }
                    // $modelPropertyVisits->child_properties =  $property_id ;
                    $modelPropertyVisits->applicant_id = Yii::$app->userdata->email;
                    $modelPropertyVisits->created_by = Yii::$app->user->id;
                    $modelPropertyVisits->updated_by = Yii::$app->user->id;
                    $modelPropertyVisits->created_date = date('Y-m-d H:i:s');
                    $modelPropertyVisits->status = 3;
                    $modelPropertyVisits->visit_date = date('Y-m-d', strtotime($modelPropertyVisits->visit_date));
                    $modelPropertyVisits->visit_time = $modelPropertyVisits->visit_time;
                    $modelPropertyVisits->type = Yii::$app->request->get('property_type');
                    $modelPropertyVisits->save(false);

                    Yii::$app->getSession()->setFlash(
                            'success', "Thanks for scheduling visit to property " . Yii::$app->userdata->getPropertyNameById($parent_property_id)
                    );
                } else {

                    $id = $visits->id;
                    $visited = \app\models\FavouriteProperties::find()->where(['id' => $id])->one();
                    if ($visited->status == '1') {
                        if ($visited->type == '3') {
                            $visited->child_properties = $parent_property_id;
                        } else {
                            $visited->child_properties = $property_id;
                        }
                    }

                    $visited->created_by = Yii::$app->user->id;
                    $visited->updated_by = Yii::$app->user->id;
                    $modelPropertyVisits->updated_by = Yii::$app->user->id;
                    $visited->visit_date = date('Y-m-d', strtotime($modelPropertyVisits->visit_date));
                    $visited->visit_time = $modelPropertyVisits->visit_time . ':00';
                    // print_r($visited);
                    // die;
                    if ($visited->save(false)) {
                        if ($visited->type == '3') {
                            /* Commented on 12 - March - 2018 */
                            //$model1 = \app\models\Properties::findOne(['id' => $visited->property_id]);
                            //$model1->status = '0';
                            //$model1->save(false);
                            $model2 = \app\models\PropertyListing::findOne(['property_id' => $visited->property_id]);
                            $model2->status = '0';

                            $model2->save(false);
                        } else {
                            /* Commented on 12 - March - 2018 */
                            //$connection = \Yii::$app->db;
                            //$model1 = $connection->createCommand("UPDATE child_properties SET status='0' where id IN ($visited->child_properties)");
                            //$model1->execute();
                        }
                        echo "done";
                        Yii::$app->getSession()->setFlash(
                                'success', "Thanks for scheduling visit to property " . Yii::$app->userdata->getPropertyNameById($parent_property_id)
                        );
                    } else {

                        echo "not";
                        Yii::$app->getSession()->setFlash(
                                'success', 'Visit not created.'
                        );
                    }
                }


                $property_name = Yii::$app->userdata->getPropertyNameById($parent_property_id);
                $property_address = Yii::$app->userdata->getPropertyAddrres($parent_property_id);

                $salesDetail = Yii::$app->userdata->getSalesDetailByUserId(Yii::$app->user->id);
                $applicant_name = Yii::$app->userdata->getName();
                $subject = "Your appointment for site visit to $property_name is setup for " . date('d-M-Y', strtotime($modelPropertyVisits->visit_date)) . " at " . $modelPropertyVisits->visit_time;
                $msg = "Hello $applicant_name <br/><br/>A site Visit for $property_name at $property_address has been scheduled for " . date('d-M-Y', strtotime($modelPropertyVisits->visit_date)) . " at " . $modelPropertyVisits->visit_time . ".<br/><br/>Your contact person for the visit is $salesDetail[name] who can be contacted at $salesDetail[phone]<br/><br/>In case you are unable to visit the site at the scheduled date/time, please reschedule the visit on Easyleases website or inform your relationship manager directly.<br/><br/>With Regards,<br/>EasyLeases Team<br/><img src='" . Url::home(true) . "images/property_logo.png' alt=''>";
                //Yii::$app->userdata->doMail(Yii::$app->userdata->getEmail(),$subject,$msg);


                $subject1 = "Appointment for site visit to $property_name is setup for " . date('d-M-Y', strtotime($modelPropertyVisits->visit_date)) . " at " . $modelPropertyVisits->visit_time;
                $msg1 = "Hello $salesDetail[name]<br/><br/>Site visit for $property_name has been scheduled by $applicant_name on " . date('d-M-Y', strtotime($modelPropertyVisits->visit_date)) . " at " . $modelPropertyVisits->visit_time . "<br/><br/>Contact details of applicant are: " . Yii::$app->userdata->getPhoneNumber(Yii::$app->user->id) . ", " . Yii::$app->userdata->email . "<br/><br/>Please plan accordingly.<br/><br/>With Regards,<br/>EasyLeases Team<br/><img src='" . Url::home(true) . "images/property_logo.png' alt=''>";
                //Yii::$app->userdata->doMail($salesDetail['email'],$subject1,$msg1);
                //return $this->redirect(['myprofile','agreement_id'=>$agreement_id]);
                return $this->goHome();
                //	return $this->redirect(['confirmation','agreement_id'=>$agreement_id]);
            } else {

                return $this->render('visit', [
                            'modelPropertyVisits' => $modelPropertyVisits,
                            'visitdate' => $visitdate,
                            'visittime' => $visittime,
                ]);
            }
        } else {

            return $this->render('visit', [
                        'modelPropertyVisits' => $modelPropertyVisits,
                        'visitdate' => $visitdate,
                        'visittime' => $visittime,
            ]);
        }

        // return $this->render('visit', ['modelPropertyVisits' => $modelPropertyVisits,'visitdate'=>$visitdate,'visittime'=>$visittime]);
    }

    function actionThankyou() {
        $this->layout = 'search';
        return $this->render('thankyou');
    }

    function actionConfirmation() {

        $agreement_id = Yii::$app->request->get('agreement_id');
        $this->layout = 'booking';

        return $this->render('confirm', ['agreement_id' => $agreement_id]);
    }

    function actionOnboarding() {

        $agreement_id = Yii::$app->request->get('agreement_id');
        $this->layout = 'booking';

        return $this->render('onboarding', ['agreement_id' => $agreement_id]);
    }

    public function actionConfirmvisits() {
        $this->layout = 'app_dashboard';

        $propertyVisits = PropertyVisits::find()->where(['email_id' => Yii::$app->userdata->email])->all();

        return $this->render('confirmvisit', ['propertyVisits' => $propertyVisits]);
    }

    public function actionChangeprop() {

        $propertyVisits = PropertyVisits::findOne($_POST['id']);

        if ($propertyVisits) {
            $propertyVisits->visit_confirm = $_POST['status'];
            $propertyVisits->updated_by = Yii::$app->user->id;
            $propertyVisits->updated_date = date('Y-m-d H:i:s');
            $propertyVisits->save(false);
            if ($_POST['status'] == 2) {
                $amount = 0;
                $modelTenantPayments = \app\models\TenantPayments::find()->where(['tenant_id' => Yii::$app->user->id, 'parent_id' => $_POST['propertyid']])->all();
                foreach ($modelTenantPayments as $tamount) {
                    $amount += $tamount->total_amount;
                }

                \app\models\TenantPayments::deleteAll('parent_id = :p_id AND tenant_id = :a_id', [':p_id' => $_POST['propertyid'], ':a_id' => Yii::$app->user->id]);


                \app\models\TenantAgreements::model()->updateAll(array('status' => 0), 'parent_id = "' . $_POST['propertyid'] . '" AND tenant_id = ' . Yii::$app->user->id . '');

                $walletAmount = Wallets::findOne(['user_id' => Yii::$app->user->id]);

                if ($walletAmount) {
                    $walletAmount->amount = $walletAmount->amount + $amount;
                    $walletAmount->updated_date = date('Y-m-d H:i:s');
                    $walletAmount->save(false);
                }

                $modelHistory = new WalletsHistory;
                $modelHistory->amount = $amount;
                $modelHistory->email_id = Yii::$app->userdata->email;
                $modelHistory->transaction_type = 3;
                $modelHistory->created_by = Yii::$app->user->id;
                $modelHistory->created_date = date('Y-m-d H:i:s');
                $modelHistory->user_id = Yii::$app->user->id;
                $modelHistory->save(false);
            } else {

                /* $id =  Yii::$app->userdata->getUserIdByEmail($_POST['email']) ;
                  $users =  Users::findOne($id );
                  $users->user_type = 3 ;
                  $users->save(false);

                  $bookings =  Bookings::findOne(['tenant_id'=>$id ,'property_id'=>$_POST['propertyid'] ]);
                  $bookings->booking_status = 1 ;
                  $bookings->save(false); */
            }
        }

        die;
    }

    public function actionWallet() {
        $this->layout = 'applicant_dashboard';
        if (Yii::$app->request->post('op_level') == 1) {
            $data = json_decode(Yii::$app->request->post('data'));
            //print_r($data); exit;
            foreach ($data as $row) {
                $property_id = $row->property_id;
                $child_id = $row->child_id;
                $amount = $row->amount;

                $transaction = Yii::$app->db->beginTransaction();

                try {

                    $walletHistory = WalletsHistory::find()->where([
                                'user_id' => Yii::$app->user->id,
                                'property_id' => $property_id,
                                'transaction_type' => 1
                            ])->one();

                    $walletHistory->transaction_type = 2;
                    $walletHistory->operation_type = 1;

                    if (!$walletHistory->save(false)) {
                        throw new \Exception('Exception');
                    }

                    $walletOper = new \app\models\WalletOperations();
                    $walletOper->user_id = Yii::$app->user->id;
                    $walletOper->created_by = Yii::$app->user->id;
                    $walletOper->amount = $amount;
                    $walletOper->property_id = $property_id;
                    $walletOper->operation_type = 2;
                    $walletOper->wallet_history_id = $walletHistory->id;
                    if (!$walletOper->save(false)) {
                        throw new \Exception('Exception');
                    }

                    $wallet = Wallets::find()->where([
                                'user_id' => Yii::$app->user->id
                            ])->one();

                    if ((double) $wallet->amount >= (double) $amount) {
                        $wallet->amount = ((double) $wallet->amount - (double) $amount);
                    }

                    if (!$wallet->save(false)) {
                        throw new \Exception('Exception');
                    }

                    $favProp = FavouriteProperties::find()->where([
                                'applicant_id' => Yii::$app->userdata->getUserEmailById(Yii::$app->user->id),
                                'property_id' => $property_id
                            ])->one();

                    if (!$favProp->delete()) {
                        throw new \Exception('Exception');
                    }

                    $propList = \app\models\PropertyListing::find()->where([
                                'property_id' => $property_id
                            ])->one();
                    $propList->status = 1;
                    if (!$propList->save(false)) {
                        throw new \Exception('Exception');
                    }

                    if ($child_id != 0) {
                        $childProperty = \app\models\ChildProperties::find()->where([
                                    'id' => $child_id
                                ])->one();
                        $childProperty->status = 1;
                        if (!$childProperty->save(false)) {
                            throw new \Exception('Exception');
                        }
                    }

                    Yii::$app->getSession()->setFlash(
                            'success', 'Your request for refund is initiated.'
                    );
                    $transaction->commit();
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->getSession()->setFlash(
                            'error', 'Your request for refund is failed. Please try after some time'
                    );
                }
            }
        } else {
            if (!empty(Yii::$app->request->post('Wallet')['property_id'])) {
                $property_id = Yii::$app->request->post('Wallet')['property_id'];
                $amount = Yii::$app->request->post('Wallet')['amount'];

                $transaction = Yii::$app->db->beginTransaction();

                try {

                    $walletHistory = WalletsHistory::find()->where([
                                'user_id' => Yii::$app->user->id,
                                'property_id' => $property_id,
                                'transaction_type' => 1
                            ])->one();

                    $walletHistory->transaction_type = 2;
                    $walletHistory->operation_type = 1;

                    if (!$walletHistory->save(false)) {
                        throw new \Exception('Exception');
                    }

                    $walletOper = new \app\models\WalletOperations();
                    $walletOper->user_id = Yii::$app->user->id;
                    $walletOper->created_by = Yii::$app->user->id;
                    $walletOper->amount = $amount;
                    $walletOper->property_id = $property_id;
                    $walletOper->operation_type = 2;
                    $walletOper->wallet_history_id = $walletHistory->id;
                    if (!$walletOper->save(false)) {
                        throw new \Exception('Exception');
                    }

                    $wallet = Wallets::find()->where([
                                'user_id' => Yii::$app->user->id
                            ])->one();

                    if ((double) $wallet->amount >= (double) $amount) {
                        $wallet->amount = ((double) $wallet->amount - (double) $amount);
                    }

                    if (!$wallet->save(false)) {
                        throw new \Exception('Exception');
                    }

                    $favProp = FavouriteProperties::find()->where([
                                'applicant_id' => Yii::$app->userdata->getUserEmailById(Yii::$app->user->id),
                                'property_id' => $property_id
                            ])->one();

                    if (!$favProp->delete()) {
                        throw new \Exception('Exception');
                    }

                    $propList = \app\models\PropertyListing::find()->where([
                                'property_id' => $property_id
                            ])->one();
                    $propList->status = 1;
                    if (!$propList->save(false)) {
                        throw new \Exception('Exception');
                    }

                    Yii::$app->getSession()->setFlash(
                            'success', 'Your request for refund is initiated.'
                    );
                    $transaction->commit();
                } catch (\Exception $e) {
                    $transaction->rollBack();
                }
            }
        }

        $walletAmount = Wallets::findone(['user_id' => Yii::$app->user->id]);
        $walletHistory = WalletsHistory::find()->where(['user_id' => Yii::$app->user->id])->orderBy('id DESC')->all();
        return $this->render('wallets', ['walletAmount' => $walletAmount, 'walletHistory' => $walletHistory]);
    }

    public function actionMylease() {
        $this->layout = 'app_dashboard';

        $bookings = Bookings::find()->where(['tenant_id' => Yii::$app->user->id])->andWhere('exit_date > "' . date('Y-m-d H:i:s') . '"')->all();

        $ids_booking = array();
        foreach ($bookings as $booking) {
            $ids_booking[] = $booking->property_id;
        }


        $agreements = Agreements::find()->where(['IN', 'property_id', $ids_booking])->all();


        return $this->render('mylease', ['agreements' => $agreements]);
    }

    public function actionMyrequests() {
        $this->layout = 'tenant_dashboard';
        $model = new ServiceRequestAttachment;
        $getAllRequest = $model->getAllRequest(Yii::$app->user->id);
        return $this->render('myrequests', ['propertyServiceRequest' => $getAllRequest]);
    }

    public function actionMoredetailssingle($id) {

        if (!Yii::$app->user->isGuest) {
            $name = Yii::$app->userdata->getName();
            $email = Yii::$app->userdata->getEmail();
            $contact = Yii::$app->userdata->getPhoneNumberById(Yii::$app->user->id, Yii::$app->user->identity->user_type);
            $subject = 'Inquiry regarding property ' . Yii::$app->userdata->getPropertyNameById($id);
            $messBody = '';
            $messBody .= '<p>Hello,</p>';
            $messBody .= '<p>An inquiry has been raised against the property ' . Yii::$app->userdata->getPropertyNameById($id) . ' which is owned by ' . Yii::$app->userdata->getUserNameByProperty($id) . '.Details of the inquiring person:</p>';
            $messBody .= '<table>';
            $messBody .= '<tr>';
            $messBody .= '<td>Name: </td><td>' . $name . '</td>';
            $messBody .= '</tr>';
            $messBody .= '<tr>';
            $messBody .= '<td>Email: </td><td>' . $email . '</td>';
            $messBody .= '</tr>';
            $messBody .= '<tr>';
            $messBody .= '<td>Phone: </td><td>' . $contact . '</td>';
            $messBody .= '</tr>';
            $messBody .= '</table>';
            $messBody .= '<p>EasyLeases Team</p>'
                    . "<img src='" . Url::home(true) . "images/property_logo.png' alt='' width='150px' height='40px'>";
            @$email = Yii::$app->userdata->doMail(Yii::$app->params['supportEmail'], $subject, $messBody);
            if ($email) {
                echo '1';
                exit;
            } else {
                echo '0';
                exit;
            }
        }
        
        if (!empty(Yii::$app->session['property_more_details']) && Yii::$app->user->isGuest) {
            $name = Yii::$app->session['property_more_details_name'];
            $email = Yii::$app->session['property_more_details_email'];
            $contact = Yii::$app->session['property_more_details_contact'];
            $subject = 'Inquiry regarding property ' . Yii::$app->userdata->getPropertyNameById($id);
            $messBody = '';
            $messBody .= '<p>Hello,</p>';
            $messBody .= '<p>An inquiry has been raised against the property ' . Yii::$app->userdata->getPropertyNameById($id) . ' which is owned by ' . Yii::$app->userdata->getUserNameByProperty($id) . '.Details of the inquiring person:</p>';
            $messBody .= '<table>';
            $messBody .= '<tr>';
            $messBody .= '<td>Name: </td><td>' . $name . '</td>';
            $messBody .= '</tr>';
            $messBody .= '<tr>';
            $messBody .= '<td>Email: </td><td>' . $email . '</td>';
            $messBody .= '</tr>';
            $messBody .= '<tr>';
            $messBody .= '<td>Phone: </td><td>' . $contact . '</td>';
            $messBody .= '</tr>';
            $messBody .= '</table>';
            $messBody .= '<p>EasyLeases Team</p>'
                    . "<img src='" . Url::home(true) . "images/property_logo.png' alt='' width='150px' height='40px'>";
            @$email = Yii::$app->userdata->doMail(Yii::$app->params['supportEmail'], $subject, $messBody);
            if ($email) {
                echo '1';
                exit;
            } else {
                echo '0';
                exit;
            }
        }

        if (!empty(Yii::$app->request->post('more_detail_single'))) {
            $name = Yii::$app->request->post('Visiter_Name');
            $email = Yii::$app->request->post('Visiter_Email');
            $contact = Yii::$app->request->post('Visiter_Contact');
            $subject = 'Inquiry regarding property ' . Yii::$app->userdata->getPropertyNameById($id);
            $messBody = '';
            $messBody .= '<p>Hello,</p>';
            $messBody .= '<p>An inquiry has been raised against the property ' . Yii::$app->userdata->getPropertyNameById($id) . ' which is owned by ' . Yii::$app->userdata->getUserNameByProperty($id) . '.Details of the inquiring person:</p>';
            $messBody .= '<table>';
            $messBody .= '<tr>';
            $messBody .= '<td>Name: </td><td>' . $name . '</td>';
            $messBody .= '</tr>';
            $messBody .= '<tr>';
            $messBody .= '<td>Email: </td><td>' . $email . '</td>';
            $messBody .= '</tr>';
            $messBody .= '<tr>';
            $messBody .= '<td>Phone: </td><td>' . $contact . '</td>';
            $messBody .= '</tr>';
            $messBody .= '</table>';
            $messBody .= '<p>EasyLeases Team</p>'
                    . "<img src='" . Url::home(true) . "images/property_logo.png' alt='' width='150px' height='40px'>";
            @$mailStatus = Yii::$app->userdata->doMail(Yii::$app->params['supportEmail'],$subject, $messBody);
            if ($mailStatus) {
                Yii::$app->session['property_more_details'] = 1;
                Yii::$app->session['property_more_details_name'] = $name;
                Yii::$app->session['property_more_details_email'] = $email;
                Yii::$app->session['property_more_details_contact'] = $contact;
                echo '1';
                exit;
            } else {
                echo '0';
                exit;
            }
        } else {
            echo '0';
            exit;
        }
    }

    public function actionCreateownerrequests() {
        $model = new ServiceRequest;
        $modelAttach = new ServiceRequestAttachment;
        $request_type = \app\models\RequestType::find()->All();

        if ($model->load(Yii::$app->request->post())) {
            $modelOwnerProfile = \app\models\OwnerProfile::find('operation_id')->where(['owner_id' => Yii::$app->user->id])->one();
            $name = '';
            $modelAttach->imageFiles = UploadedFile::getInstances($modelAttach, 'attachment');
            $model->created_by = Yii::$app->user->id;
            $model->client_id = Yii::$app->user->id;
            $model->operated_by = $modelOwnerProfile->operation_id;
            $model->status = 1;
            $model->property_id = base64_decode(Yii::$app->request->get('id'));
            $model->request_type = Yii::$app->request->post('ServiceRequest')['request_type'];
            $model->client_type = 4;
            $model->save(false);
            $lastInsert = $model->id;

            if (count($modelAttach->imageFiles) > 0) {
                $modelAttach->upload($lastInsert);
            }

            $toEmail = [];
            $ccEmail = [];

            $modelOwnerProfile = \app\models\OwnerProfile::find()->where(['owner_id' => Yii::$app->user->id])->one();
            if (!empty($modelOwnerProfile->operation_id)) {
                $ccEmail[] = Yii::$app->userdata->getEmailById($modelOwnerProfile->operation_id);
            }

            if (!empty($modelOwnerProfile->sales_id)) {
                $ccEmail[] = Yii::$app->userdata->getEmailById($modelOwnerProfile->sales_id);
            }

            if (!empty($modelOwnerProfile->branch_code)) {
                $modelBranch = \app\models\OperationsProfile::find()->where(['role_code' => 'OPBRMG', 'role_value' => $modelOwnerProfile->branch_code])->one();
                $ccEmail[] = Yii::$app->userdata->getEmailById($modelBranch->operations_id);
            }

            $ccEmail[] = Yii::$app->params['supportEmail'];
            $subject = "New Service Request: # $model->id";

            $msg = "<html>"
                    . "<head>"
                    . "</head>"
                    . "<body>"
                    . "Thanks for using our ticketing platform for raising a service request numbered " . $model->id . ". Your designated support person '" . Yii::$app->userdata->getFullNameById($modelOwnerProfile->operation_id) . "' will be happy to help you in resolution of your " . Yii::$app->userdata->getRequestType($model->request_type) . " request. '" . Yii::$app->userdata->getFullNameById($modelOwnerProfile->operation_id) . "' is available to support you at " . Yii::$app->userdata->getEmailById($modelOwnerProfile->operation_id) . " or " . Yii::$app->userdata->getPhoneById($modelOwnerProfile->operation_id, 7) . ". <br />"
                    . "Thanks and Regards,<br />Easyleases Support Team<br />"
                    . "<img src='" . Url::home(true) . "images/property_logo.png' alt='' width='150px' height='40px'>"
                    . "</body>"
                    . "</html>";

            $disMsg = "Thanks !!! We have received your service request, type - '" . Yii::$app->userdata->getRequestType($model->request_type) . "' and the same has been assigned to '" . Yii::$app->userdata->getFullNameById($modelOwnerProfile->operation_id) . "', available at '" . Yii::$app->userdata->getEmailById($modelOwnerProfile->operation_id) . "' and '" . Yii::$app->userdata->getPhoneById($modelOwnerProfile->operation_id, 7) . "'";
            Yii::$app->getSession()->setFlash(
                    'success', $disMsg
            );

            if (!empty($toEmail)) {
                Yii::$app->userdata->doMailWithCC(implode(',', $toEmail), implode(',', $ccEmail), '', $subject, $msg, []);
            }

            return $this->redirect(['ownerrequests?id=' . Yii::$app->request->get('id')]);
        } else {
            return $this->renderAjax('createownerrequests', [
                        'propertyServiceRequest' => $model,
                        'requestType' => $request_type,
                        'attachment' => $modelAttach,
            ]);
        }
        return $this->renderAjax('createownerrequests', ['propertyServiceRequest' => $model, 'requestType' => $request_type,]);
    }

    public function actionCreaterequests() {
        $model = new ServiceRequest;
        $modelAttach = new ServiceRequestAttachment;
        $request_type = \app\models\RequestType::find()->All();
        $bookings = \app\models\TenantAgreements::find()
                ->where(['tenant_id' => Yii::$app->user->id, 'status' => 1])
                ->all();
        $allbooking = array();
        $i = 0;
        foreach ($bookings as $book) {
            $propertyname = \app\models\Properties::find('property_name')->where(['id' => $book->property_id])->one();
            $allbooking[$i]['property_id'] = $book->property_id;
            $allbooking[$i]['property_name'] = $propertyname->property_name;
        }
        if ($model->load(Yii::$app->request->post())) {
            $modelTenantProfile = \app\models\TenantProfile::find('operation_id')->where(['tenant_id' => Yii::$app->user->id])->one();
            $name = '';
            $modelAttach->imageFiles = UploadedFile::getInstances($modelAttach, 'attachment');
            $model->client_id = Yii::$app->user->id;
            $model->operated_by = $modelTenantProfile->operation_id;
            $model->created_by = Yii::$app->user->id;
            $model->status = 1;
            $model->created_date = date('Y-m-d H:i:s');
            $model->request_type = Yii::$app->request->post('ServiceRequest')['request_type'];
            $model->client_type = 3;
            $model->property_id = $_POST['property_id'];
            $model->save(false);
            $lastInsert = $model->id;

            if (count($modelAttach->imageFiles) > 0) {
                $modelAttach->upload($lastInsert);
            }

            $toEmail = [];
            $ccEmail = [];

            $modelAppProfile = \app\models\ApplicantProfile::find()->where(['applicant_id' => Yii::$app->user->id])->one();
            $toEmail[] = Yii::$app->userdata->getEmailById($modelAppProfile->applicant_id);
            if (!empty($modelAppProfile->operation_id)) {
                $ccEmail[] = Yii::$app->userdata->getEmailById($modelAppProfile->operation_id);
            }

            if (!empty($modelAppProfile->sales_id)) {
                $ccEmail[] = Yii::$app->userdata->getEmailById($modelAppProfile->sales_id);
            }

            if (!empty($modelAppProfile->branch_code)) {
                $modelBranch = \app\models\OperationsProfile::find()->where(['role_code' => 'OPBRMG', 'role_value' => $modelAppProfile->branch_code])->one();
                $ccEmail[] = Yii::$app->userdata->getEmailById($modelBranch->operations_id);
            }

            $ccEmail[] = Yii::$app->params['supportEmail'];
            $subject = "New Service Request: # $model->id";

            // Sending mail starts here
            $msg = "<html>"
                    . "<head>"
                    . "</head>"
                    . "<body>"
                    . "Thanks for using our ticketing platform for raising a service request numbered " . $model->id . ". Your designated support person '" . Yii::$app->userdata->getFullNameById($modelAppProfile->operation_id) . "' will be happy to help you in resolution of your " . Yii::$app->userdata->getRequestType($model->request_type) . " request. '" . Yii::$app->userdata->getFullNameById($modelAppProfile->operation_id) . "' is available to support you at " . Yii::$app->userdata->getEmailById($modelAppProfile->operation_id) . " or " . Yii::$app->userdata->getPhoneById($modelAppProfile->operation_id, 7) . ". <br />"
                    . "Thanks and Regards,<br />Easyleases Support Team<br />"
                    . "<img src='" . Url::home(true) . "images/property_logo.png' alt='' width='150px' height='40px'>"
                    . "</body>"
                    . "</html>";
            $disMsg = 'Please try after sometime';
            if (!empty($toEmail)) {
                Yii::$app->userdata->doMailWithCC(implode(',', $toEmail), implode(',', $ccEmail), '', $subject, $msg, []);
                $disMsg = "Thanks !!! We have received your service request, type - '" . Yii::$app->userdata->getRequestType(Yii::$app->request->post('ServiceRequest')['request_type']) . "' and the same has been assigned to '" . Yii::$app->userdata->getFullNameById($modelAppProfile->operation_id) . "', available at '" . Yii::$app->userdata->getEmailById($modelAppProfile->operation_id) . "' and '" . Yii::$app->userdata->getPhoneById($modelAppProfile->operation_id, 7) . "'";
            }
            // Sending mail ends here

            Yii::$app->getSession()->setFlash(
                    'success', $disMsg
            );

            return $this->redirect(['myrequests']);
        } else {
            return $this->renderAjax('createrequest', [
                        'propertyServiceRequest' => $model,
                        'requestType' => $request_type,
                        'attachment' => $modelAttach,
                        'allbookings' => $allbooking
            ]);
        }

        return $this->renderAjax('createrequest', ['propertyServiceRequest' => $model, 'requestType' => $request_type, 'allbookings' => $allbooking]);
    }

    public function actionMypaymentsold() {
        $this->layout = 'app_dashboard';

        $bookings = Bookings::find()->where(['tenant_id' => Yii::$app->user->id])->one();
        $property = Properties::findOne($bookings->property_id);

        $agreements = Agreements::findOne(['property_id' => $bookings->property_id]);

        $rent = 0;
        if ($property) {
            if ($property->maintenance_included == 0) {
                $rent = $property->rent + $property->maintenance;
            } else {
                $rent = $property->rent;
            }
        }

        $amount = 0;
        $tenantPaymentsTotal = TenantPayments::find()->where(['email_id' => Yii::$app->userdata->email, 'property_id' => $bookings->property_id])->andWhere('monthname(create_time) = "' . date('F') . '" and create_time >= CURDATE()')->all();

        foreach ($tenantPaymentsTotal as $amounts) {
            $amount += $amounts->payment_amount;
        }

        $amount = $rent - $amount;

        $rent_date = $agreements->rent_date;
        $dateCurrent = date('d');
        $penalty = $agreements->late_penalty_percent;
        $penaltyAmount = 0;

        if ($dateCurrent > $rent_date) {
            $penaltyAmount = round($amount * $penalty / 100);
        }




        $walletAmount = Wallets::findone(['email_id' => Yii::$app->userdata->email]);

        $tenantPayments = TenantPayments::find()->where(['email_id' => Yii::$app->userdata->email])->andWhere('create_time <= "' . date('Y-m-d H:i:s') . '"')->all();




        $tenantPaymentsDue = TenantPayments::find()->where('monthname(create_time) = "' . date('F') . '" and create_time >= CURDATE()')->all();


        return $this->render('mypayments', ['walletAmount' => $walletAmount, 'tenantPayments' => $tenantPayments, 'amount' => $amount, 'penaltyAmount' => $penaltyAmount, 'agreements' => $agreements]);
    }

    public function actionMypayments() {
        $this->layout = 'tenant_dashboard';

        $sql1 = 'SELECT tp.id, tp.payment_date, tp.parent_id, tp.payment_des, tp.payment_status, tp.total_amount, tp.due_date, "Rent" as payment_for, tp.penalty_amount, tp.amount_paid, ta.late_penalty_percent, ta.min_penalty FROM tenant_payments tp INNER JOIN tenant_agreements ta ON tp.agreement_id = ta.id WHERE tp.tenant_id="' . Yii::$app->user->id . '" AND tp.payment_status = 1 ORDER BY due_date';

        $connection = \Yii::$app->db;
        $model = $connection->createCommand($sql1);
        $pastPayment = $model->queryAll();

        return $this->render('mypaymentsnew', ['pastPayment' => $pastPayment]);
    }

    public function actionMypaymentsdue() {
        $this->layout = 'tenant_dashboard';
        $datedue = Date('Y-m-d', strtotime('+1 month'));

        $sql1 = 'SELECT tp.id, tp.parent_id, tp.payment_des, tp.payment_status, tp.total_amount, tp.due_date, tp.payment_type as payment_for, tp.penalty_amount, tp.neft_reference,ta.late_penalty_percent, ta.min_penalty FROM tenant_payments tp INNER JOIN tenant_agreements ta ON tp.agreement_id = ta.id WHERE tp.tenant_id="' . Yii::$app->user->id . '" and tp.due_date <"' . $datedue . '" and tp.payment_status IN (0, 2) ORDER BY due_date';
        $connection = \Yii::$app->db;
        $model = $connection->createCommand($sql1);
        $pastPayment = $model->queryAll();

        // Old Code.
        // $tenantAgreements = \app\models\TenantAgreements::find()->where(['tenant_id' => Yii::$app->user->id])->andWhere('lease_end_date >= "' . date('Y-m-d') . '"')->all();
        $tenantAgreements = \app\models\TenantAgreements::find()->where(['tenant_id' => Yii::$app->user->id])->all();
        $ids = array();
        $ids_parent = array();
        foreach ($tenantAgreements as $agreements) {
            $ids[] = $agreements->property_id;
            $ids_parent[] = $agreements->parent_id;
        }



        return $this->render('mypaymentsdue', ['pastPayment' => $pastPayment]);
    }

    public function actionMypaymentsforcast() {
        $this->layout = 'tenant_dashboard';
        $sql = 'SELECT `id`, `parent_id`, `payment_des`, `payment_status`,`total_amount`,`due_date`, "Rent" as `payment_for` FROM `tenant_payments` WHERE tenant_id="' . Yii::$app->user->id . '" and due_date >="' . date('Y-m-d', strtotime('+30 days', strtotime(Date('Y-m-d')))) . '" and payment_status=0 UNION ALL SELECT ac.`id`, ta.`parent_id`, ac.`title` as payment_des, ac.`payment_status`, ac.`charge_to_tenant` as total_amount, `payment_due_date` as due_date, "Adhoc Charges" as `payment_for` FROM `adhoc_charge` as ac left join tenant_agreements as ta on ac.tenant_id=ta.tenant_id WHERE ac.tenant_id="' . Yii::$app->user->id . '" and ac.payment_due_date >="' . date('Y-m-d', strtotime('+30 days', strtotime(Date('Y-m-d')))) . '" and ac.payment_status=0 ORDER BY due_date';
        // die;


        $connection = \Yii::$app->db;
        $model = $connection->createCommand($sql);
        $forePayments = $model->queryAll();
        // $ids = array();
        // $ids_parent = array();
        //    foreach( $tenantAgreements as $agreements ){
        //  $ids[] = $agreements->property_id ;
        //  $ids_parent[] = $agreements->parent_id ;
        // }
        //$sql = 'SELECT tenant_payments.* , YEAR(created_date), MONTH(created_date), sum(total_amount) as sumofmoney FROM tenant_payments where tenant_id="'.Yii::$app->user->id.'" AND  created_date >= "'.date('Y-m-01 00:00:00',strtotime('+1 months')).'"GROUP BY YEAR(created_date), tenant_payments.property_id, MONTH(created_date) ORDER BY created_date ASC';
        // $connection =\Yii::$app->db;
        // $model = $connection->createCommand($sql);
        // $forePayments = $model->queryAll();
        // die;
        return $this->render('mypaymentsforcast', ['forePayments' => $forePayments,]);
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionAddvisit() {

        $model = new \app\models\PropertyVisits;

        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            $checkDateTime = \app\models\PropertyVisits::find()->where(['visit_date' => date('Y-m-d', strtotime($model->visit_date)), 'visit_time' => $model->visit_time . ':00', 'applicant_id' => Yii::$app->userdata->email])->one();
            if (count($checkDateTime) == '0') {
                $visits = \app\models\PropertyVisits::find()->where(['property_id' => $model->property_id, 'applicant_id' => Yii::$app->userdata->email])->one();
                $checkDateTime = \app\models\PropertyVisits::find()->where(['visit_date' => date('Y-m-d', strtotime($model->visit_date)), 'visit_time' => $model->visit_time . ':00']);
                $PropertyVisitsData = Yii::$app->request->post('PropertyVisits');
                if (count($visits) == 0) {


                    $model->property_id = $model->property_id;
                    $model->child_properties = $PropertyVisitsData['child_properties'];
                    $model->applicant_id = Yii::$app->userdata->email;
                    $model->created_by = Yii::$app->user->id;
                    $model->updated_by = Yii::$app->user->id;
                    $model->created_date = date('Y-m-d H:i:s');
                    $model->status = 3;
                    $model->visit_date = date('Y-m-d', strtotime($model->visit_date));
                    $model->visit_time = $model->visit_time . ':00';
                    $model->type = $PropertyVisitsData['type'];
                    if ($model->save()) {
                        $property_name = Yii::$app->userdata->getPropertyNameById($model->property_id);
                        $property_address = Yii::$app->userdata->getPropertyAddrres($model->property_id);

                        $salesDetail = Yii::$app->userdata->getSalesDetailByUserId(Yii::$app->user->id);
                        $applicant_name = Yii::$app->userdata->getName();
                        $subject = "Your appointment for site visit to $property_name is setup for " . date('d-M-Y', strtotime($model->visit_date)) . " at " . $model->visit_time;
                        $msg = "Hello $applicant_name <br/><br/>A site Visit for $property_name at $property_address has been scheduled for " . date('d-M-Y', strtotime($model->visit_date)) . " at " . $model->visit_time . ".<br/><br/>Your contact person for the visit is $salesDetail[name] who can be contacted at $salesDetail[phone]<br/><br/>In case you are unable to visit the site at the scheduled date/time, please reschedule the visit on Easyleases website or inform your relationship manager directly.<br/><br/>With Regards,<br/>EasyLeases Team<br/><img src='" . Url::home(true) . "images/property_logo.png' alt=''>";
                        Yii::$app->userdata->doMail(Yii::$app->userdata->getEmail(), $subject, $msg);


                        $subject1 = "Appointment for site visit to $property_name is setup for " . date('d-M-Y', strtotime($model->visit_date)) . " at " . $model->visit_time;
                        $msg1 = "Hello $salesDetail[name]<br/><br/>Site visit for $property_name has been scheduled by $applicant_name on " . date('d-M-Y', strtotime($model->visit_date)) . " at " . $model->visit_time . "<br/><br/>Contact details of applicant are: " . Yii::$app->userdata->getPhoneNumber(Yii::$app->user->id) . ", " . Yii::$app->userdata->email . "<br/><br/>Please plan accordingly.<br/><br/>With Regards,<br/>EasyLeases Team<br/><img src='" . Url::home(true) . "images/property_logo.png' alt=''>";
                        Yii::$app->userdata->doMail($salesDetail['email'], $subject1, $msg1);
                        echo "done";
                        Yii::$app->getSession()->setFlash(
                                'success', 'Visit created successfully.'
                        );
                    } else {
                        // print_r($model->getErrors());
                        echo "not";
                        Yii::$app->getSession()->setFlash(
                                'success', 'Visit not created.'
                        );
                    }
                } else {

                    $id = $visits->id;
                    // if($visits->child_properties==''){
                    // 	$child_properties=Array();
                    // }
                    // else{
                    // 	$child_properties=explode(",",$visits->child_properties);
                    // }
                    //$child_properties=implode(",",array_unique(array_merge($child_properties,explode(",",$PropertyVisitsData['child_properties']))));
                    $visited = \app\models\PropertyVisits::find()->where(['id' => $id])->one();
                    $visited->child_properties = $PropertyVisitsData['child_properties'];
                    $visited->created_by = Yii::$app->user->id;
                    $visited->updated_by = Yii::$app->user->id;
                    $visited->visit_date = date('Y-m-d', strtotime($model->visit_date));
                    $visited->visit_time = $model->visit_time . ':00';
                    $property_name = Yii::$app->userdata->getPropertyNameById($model->property_id);
                    $property_address = Yii::$app->userdata->getPropertyAddrres($model->property_id);

                    $salesDetail = Yii::$app->userdata->getSalesDetailByUserId(Yii::$app->user->id);
                    $applicant_name = Yii::$app->userdata->getName();
                    $subject = "Your appointment for site visit to $property_name is setup for " . date('d-M-Y', strtotime($model->visit_date)) . " at " . $model->visit_time;
                    $msg = "Hello $applicant_name <br/><br/>A site Visit for $property_name at $property_address has been scheduled for " . date('d-M-Y', strtotime($model->visit_date)) . " at " . $model->visit_time . ".<br/><br/>Your contact person for the visit is $salesDetail[name] who can be contacted at $salesDetail[phone]<br/><br/>In case you are unable to visit the site at the scheduled date/time, please reschedule the visit on Easyleases website or inform your relationship manager directly.<br/><br/>With Regards,<br/>EasyLeases Team<br/><img src='" . Url::home(true) . "images/property_logo.png' alt=''>";
                    Yii::$app->userdata->doMail(Yii::$app->userdata->getEmail(), $subject, $msg);


                    $subject1 = "Appointment for site visit to $property_name is setup for " . date('d-M-Y', strtotime($model->visit_date)) . " at " . $model->visit_time;
                    $msg1 = "Hello $salesDetail[name]<br/><br/>Site visit for $property_name has been scheduled by $applicant_name on " . date('d-M-Y', strtotime($model->visit_date)) . " at " . $model->visit_time . "<br/><br/>Contact details of applicant are: " . Yii::$app->userdata->getPhoneNumber(Yii::$app->user->id) . ", " . Yii::$app->userdata->email . "<br/><br/>Please plan accordingly.<br/><br/>With Regards,<br/>EasyLeases Team<br/><img src='" . Url::home(true) . "images/property_logo.png' alt=''>";
                    Yii::$app->userdata->doMail($salesDetail['email'], $subject1, $msg1);


                    if ($visited->status == '1') {
                        $visited->status = 3;
                    }

                    if ($visited->save()) {
                        // if($visited->type=='3'){
                        // $model1=\app\models\Properties::findOne(['id'=>$visited->property_id]);
                        // $model1->status='0';
                        // $model1->save(false);
                        // }
                        // else{
                        // $connection =\Yii::$app->db;
                        // $model1 = $connection->createCommand("UPDATE child_properties SET status='0' where id IN ($visited->child_properties)");
                        // $model1->execute();
                        // }
                        echo "done";
                        Yii::$app->getSession()->setFlash(
                                'success', 'Thanks for scheduling visit to property ' . $property_name
                        );
                    } else {

                        echo "not";
                        Yii::$app->getSession()->setFlash(
                                'success', 'Visit not created.'
                        );
                    }
                }
            } else {
                echo "nott";
                Yii::$app->getSession()->setFlash(
                        'success', 'Visit With This Date Time Already Exist.'
                );
            }
        }
    }

    public function actionAddvisitajax() {

        $model = new \app\models\PropertyVisits;

        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            $checkvisitdate = \app\models\PropertyVisits::find()->where(['applicant_id' => Yii::$app->userdata->email, 'visit_date' => date('Y-m-d', strtotime($model->visit_date)), 'visit_time' => $model->visit_time . ':00'])->one();
            if (count($checkvisitdate) == '0') {
                $visits = \app\models\PropertyVisits::find()->where(['property_id' => $model->property_id, 'applicant_id' => Yii::$app->userdata->email])->one();
                $PropertyVisitsData = Yii::$app->request->post('PropertyVisits');
                if (count($visits) == 0) {


                    $model->property_id = $model->property_id;
                    $model->child_properties = $PropertyVisitsData['child_properties'];
                    $model->applicant_id = Yii::$app->userdata->email;
                    $model->created_by = Yii::$app->user->id;
                    $model->updated_by = Yii::$app->user->id;
                    $model->created_date = date('Y-m-d H:i:s');
                    $model->status = 3;
                    $model->visit_date = date('Y-m-d', strtotime($model->visit_date));
                    $model->visit_time = $model->visit_time . ':00';
                    $model->type = $PropertyVisitsData['type'];
                    if ($model->save()) {
                        echo "done";
                        Yii::$app->getSession()->setFlash(
                                'success', 'Visit created successfully.'
                        );
                    } else {
                        // print_r($model->getErrors());
                        echo "not";
                        Yii::$app->getSession()->setFlash(
                                'success', 'Visit not created.'
                        );
                    }
                } else {



                    $id = $visits->id;

                    $visited = \app\models\PropertyVisits::find()->where(['id' => $id])->one();
                    $visited->child_properties = $PropertyVisitsData['child_properties'];
                    $visited->created_by = Yii::$app->user->id;
                    $visited->updated_by = Yii::$app->user->id;
                    $visited->visit_date = date('Y-m-d', strtotime($model->visit_date));
                    $visited->visit_time = $model->visit_time . ':00';
                    if ($visited->status == '1') {
                        $visited->status = 3;
                    }

                    if ($visited->save()) {
                        echo "done";
                        Yii::$app->getSession()->setFlash(
                                'success', 'Visit created successfully.'
                        );
                    } else {

                        echo "not";
                        Yii::$app->getSession()->setFlash(
                                'success', 'Visit not created.'
                        );
                    }
                }
            } else {
                echo "not";
                Yii::$app->getSession()->setFlash(
                        'success', 'Visit for this date alreay created.'
                );
            }
        }
    }

    public function actionAddfav() {
        $request = Yii::$app->request;
        $test = 0;
        if ($request->isAjax) {
            if (Yii::$app->user->isGuest) {
                $test = 2;
            } else {
                $status = Yii::$app->request->post('status');
                $property = Yii::$app->request->post('property');

                if ($status == 0) {
                    $rows = (new \yii\db\Query())
                            ->select(['property_id'])
                            ->from('favourite_properties')
                            ->where(['property_id' => $property, 'status' => 1, 'created_by' => Yii::$app->user->id])
                            ->all();
                    if (count($rows) > 0) {
                        FavouriteProperties::deleteAll('property_id = :p_id AND applicant_id = :a_id', [':p_id' => $property, ':a_id' => Yii::$app->userdata->email]);

                        $test = 0;
                    }
                } else {
                    $model = new FavouriteProperties();
                    $model->property_id = $property;
                    $model->applicant_id = Yii::$app->userdata->email;
                    $model->created_by = Yii::$app->user->id;
                    $model->updated_by = Yii::$app->user->id;
                    $model->created_date = date('Y-m-d H:i:s');
                    $model->type = Yii::$app->userdata->getPropertyTypeId($property);
                    $model->save(false);
                    $test = 1;
                }
            }

            if ($test == 2) {
                Yii::$app->session->set('addfav_redirect', Yii::$app->request->referrer . '&addfav=1&status=' . Yii::$app->request->post('status') . '&property=' . Yii::$app->request->post('property'));
            }

            return \yii\helpers\Json::encode($test);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionAddfavAfterRedirect($status, $property) {
        if ($status == 0) {
            $rows = (new \yii\db\Query())
                    ->select(['property_id'])
                    ->from('favourite_properties')
                    ->where(['property_id' => $property, 'status' => 1, 'created_by' => Yii::$app->user->id])
                    ->all();
            if (count($rows) > 0) {
                FavouriteProperties::deleteAll('property_id = :p_id AND applicant_id = :a_id', [':p_id' => $property, ':a_id' => Yii::$app->userdata->email]);

                $test = 0;
            }
        } else {
            $model = new FavouriteProperties();
            $model->property_id = $property;
            $model->applicant_id = Yii::$app->userdata->email;
            $model->created_by = Yii::$app->user->id;
            $model->updated_by = Yii::$app->user->id;
            $model->created_date = date('Y-m-d H:i:s');
            $model->type = Yii::$app->userdata->getPropertyTypeId($property);
            $model->save(false);
            $test = 1;
        }
    }

    public function actionAddfavajax() {
        $status = Yii::$app->request->post('status');
        $property = Yii::$app->request->post('property');
        if ($status == 0) {
            FavouriteProperties::deleteAll('property_id = :p_id AND applicant_id = :a_id', [':p_id' => $property, ':a_id' => Yii::$app->userdata->email]);
            $test = 0;
        } else {
            $model = new FavouriteProperties();
            $model->property_id = $property;
            $model->applicant_id = Yii::$app->userdata->email;
            $model->created_by = Yii::$app->user->id;
            $model->updated_by = Yii::$app->user->id;
            $model->created_date = date('Y-m-d H:i:s');
            $model->type = Yii::$app->userdata->getPropertyTypeId($property);
            $model->save(false);
            $test = 1;
        }
    }

    public function actionDeletefav() {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            if (Yii::$app->user->isGuest) {
                $test = 0;
            } else {
                $id = Yii::$app->request->post('id');
                $type = Yii::$app->request->post('type');
                $properties = FavouriteProperties::findOne(['id' => $id]);
                $childID = 0;

                if ($properties) {

                    $transaction = Yii::$app->db->beginTransaction();

                    try {

                        if ($properties->type == 3) {
                            $model = \app\models\Properties::findOne(['id' => $properties->property_id]);
                            $model->status = 1;
                            if (!$model->save(false)) {
                                throw new \Exception('Exception');
                            }

                            $property = \app\models\PropertyListing::findOne(['property_id' => $properties->property_id]);
                            $property->status = '1';
                            if (!$property->save(false)) {
                                throw new \Exception('Exception');
                            }

                            $property_id = $properties->property_id;

                            $walletHistory = WalletsHistory::find()->where([
                                        'user_id' => Yii::$app->user->id,
                                        'property_id' => $property_id,
                                        'transaction_type' => 1
                                    ])->one();

                            if ($walletHistory) {
                                $walletHistory->transaction_type = 2;
                                $walletHistory->operation_type = 1;

                                $amount = $walletHistory->amount;
                                $childID = (empty($walletHistory->child_id)) ? 0 : $walletHistory->child_id;

                                $childProperty = \app\models\ChildProperties::findOne(['id' => $childID]);
                                if ($childProperty) {
                                    $childProperty->status = 1;
                                    if (!$childProperty->save(false)) {
                                        throw new \Exception('Exception');
                                    }
                                }

                                if (!$walletHistory->save(false)) {
                                    throw new \Exception('Exception');
                                }

                                $walletOper = new \app\models\WalletOperations();
                                $walletOper->user_id = Yii::$app->user->id;
                                $walletOper->created_by = Yii::$app->user->id;
                                $walletOper->amount = $amount;
                                $walletOper->property_id = $property_id;
                                $walletOper->operation_type = 2;
                                $walletOper->wallet_history_id = $walletHistory->id;
                                if (!$walletOper->save(false)) {
                                    throw new \Exception('Exception');
                                }

                                $wallet = Wallets::find()->where([
                                            'user_id' => Yii::$app->user->id
                                        ])->one();

                                if ((double) $wallet->amount >= (double) $amount) {
                                    $wallet->amount = ((double) $wallet->amount - (double) $amount);
                                }

                                if (!$wallet->save(false)) {
                                    throw new \Exception('Exception');
                                }

                                $favProp = FavouriteProperties::find()->where([
                                            'applicant_id' => Yii::$app->userdata->getUserEmailById(Yii::$app->user->id),
                                            'property_id' => $property_id
                                        ])->one();

                                if (!$favProp->delete()) {
                                    throw new \Exception('Exception');
                                }

                                $propList = \app\models\PropertyListing::find()->where([
                                            'property_id' => $property_id
                                        ])->one();
                                $propList->status = 1;
                                if (!$propList->save(false)) {
                                    throw new \Exception('Exception');
                                }

                                Yii::$app->getSession()->setFlash(
                                        'success', 'Your request for refund is initiated.'
                                );
                            } else {
                                FavouriteProperties::findOne(['id' => $id])->delete();
                                Yii::$app->getSession()->setFlash(
                                        'success', 'Property removed successfully'
                                );
                            }
                        } else {

                            // Write code for coliving [Starts Here]



                            $model = \app\models\Properties::findOne(['id' => $properties->property_id]);
                            $model->status = 1;
                            if (!$model->save(false)) {
                                throw new \Exception('Exception');
                            }

                            $property = \app\models\PropertyListing::findOne(['property_id' => $properties->property_id]);
                            $property->status = '1';
                            if (!$property->save(false)) {
                                throw new \Exception('Exception');
                            }

                            $property_id = $properties->property_id;

                            $walletHistory = WalletsHistory::find()->where([
                                        'user_id' => Yii::$app->user->id,
                                        'property_id' => $property_id,
                                        'transaction_type' => 1
                                    ])->one();

                            if ($walletHistory) {
                                $walletHistory->transaction_type = 0;
                                $walletHistory->operation_type = 1;

                                $amount = $walletHistory->amount;

                                if (!$walletHistory->save(false)) {
                                    throw new \Exception('Exception');
                                }

                                $walletOper = new \app\models\WalletOperations();
                                $walletOper->user_id = Yii::$app->user->id;
                                $walletOper->created_by = Yii::$app->user->id;
                                $walletOper->amount = $amount;
                                $walletOper->property_id = $property_id;
                                $walletOper->operation_type = 2;
                                $walletOper->wallet_history_id = $walletHistory->id;
                                if (!$walletOper->save(false)) {
                                    throw new \Exception('Exception');
                                }

                                $wallet = Wallets::find()->where([
                                            'user_id' => Yii::$app->user->id
                                        ])->one();

                                if ((double) $wallet->amount >= (double) $amount) {
                                    $wallet->amount = ((double) $wallet->amount - (double) $amount);
                                }

                                if (!$wallet->save(false)) {
                                    throw new \Exception('Exception');
                                }

                                $favProp = FavouriteProperties::find()->where([
                                            'id' => $id
                                        ])->one();

                                if (!$favProp->delete()) {
                                    throw new \Exception('Exception');
                                }

                                $propList = \app\models\PropertyListing::find()->where([
                                            'property_id' => $property_id
                                        ])->one();
                                $propList->status = 1;
                                if (!$propList->save(false)) {
                                    throw new \Exception('Exception');
                                }

                                Yii::$app->getSession()->setFlash(
                                        'success', 'Your request for refund is initiated.'
                                );
                            } else {
                                FavouriteProperties::findOne(['id' => $id])->delete();
                                Yii::$app->getSession()->setFlash(
                                        'success', 'Property removed successfully'
                                );
                            }



                            // Write code for coliving [Ends Here]
                        }

                        $transaction->commit();
                    } catch (\Exception $e) {
                        $transaction->rollBack();
                        Yii::$app->getSession()->setFlash(
                                'error', 'Something went wrong. Please try after some time'
                        );
                    }
                }

                $test = 1;
            }
            return \yii\helpers\Json::encode($test);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionConfirmbook() {
        $request = Yii::$app->request;
        $id = Yii::$app->request->post('id');

        $leadInfo = \app\models\LeadsTenant::findOne(['email_id' => Yii::$app->userdata->getUserEmailById(Yii::$app->user->id)]);
        $leadInfo->ref_status = '3';
        $leadInfo->save(false);

        $FavList = \app\models\FavouriteProperties::findOne(['id' => $id]);

        $FavList->confirm = 1;
        $FavList->save(false);
        $user = Yii::$app->user->id;
        $applicant_name = Yii::$app->userdata->getName();
        $property_name = Yii::$app->userdata->getPropertyNameById($FavList->property_id);
        $salesPerson = Yii::$app->userdata->getSalesDetailByUserId($user);
        $subject = "Congratulations on finding your new home";
        $msg = "Hello $applicant_name<br/><br/>Congratulation and thanks for confirming your interest in renting the $property_name.<br/><br/>Our sales team will be in touch with you to process your application, collect the required documentation and agreement signup.<br/><br/>In case of any clarification you may reach out to your relationship manager $salesPerson[name] at $salesPerson[phone] or $salesPerson[email]<br/><br/>With Regards,<br/>EasyLeases Team<br/><img src='" . Url::home(true) . "images/property_logo.png' alt=''>";
        Yii::$app->userdata->doMail(Yii::$app->userdata->getEmail(), $subject, $msg);
        $subject1 = "Applicant $applicant_name has confirmed the property $property_name";
        $msg1 = "Hello $salesPerson[name]<br/><br/>Applicant $applicant_name has confirmed the property $property_name.<br/><br/>Please coordinate with the applicant to complete the documentation and onboard the applicant.<br/><br/>Contact details of applicant are: " . Yii::$app->userdata->getEmail() . ", " . Yii::$app->userdata->getPhoneNumber($user) . "<br/><br/>Please plan accordingly.<br/><br/>With Regards,<br/>EasyLeases Team<br/><img src='" . Url::home(true) . "images/property_logo.png' alt=''>";

        if (!empty($salesPerson['email'])) {
            Yii::$app->userdata->doMail($salesPerson['email'], $subject1, $msg1);
        }

        $favlist = \app\models\FavouriteProperties::find()->where(['applicant_id' => Yii::$app->userdata->getUserEmailById($user)])->andWhere("id != $id")->all();
        foreach ($favlist as $key => $value) {

            if ($value->status == '2') {
                $refunds = new \app\models\Refund;
                $favItem = \app\models\FavouriteProperties::findOne(['id' => $id]);
                $refunds->user_id = Yii::$app->userdata->getUserIdByEmail($favItem->applicant_id);
                $refunds->property_id = $favItem->property_id;
                $refunds->child_properties = $favItem->child_properties;
                $refunds->property_type = $favItem->type;
                $refunds->status = 0;
                $refunds->save(false);

                if ($value->type == '3') {
                    $model = \app\models\Properties::findOne(['id' => $value->property_id]);
                    $model->status = '1';
                    $model->save(false);

                    $model1 = \app\models\PropertyListing::findOne(['property_id' => $value->property_id]);
                    $model1->status = '1';
                    $model1->save(false);
                } else {
                    $model = \app\models\Properties::findOne(['id' => $value->property_id]);
                    $model->status = '1';
                    $model->save(false);

                    $model1 = \app\models\PropertyListing::findOne(['property_id' => $value->property_id]);
                    $model1->status = '1';
                    $model1->save(false);

                    $childProperties = \app\models\ChildProperties::find()->where('id IN (' . $value->child_properties . ')')->all();
                    foreach ($childProperties as $keyChild => $child) {
                        $child->status = '1';
                        $child->save(false);
                    }
                }
            }

            $value->delete();
        }
        echo true;
    }

    /**
     * search records
     * */
    public function actionPayrent($payment_mode = 0) {
        //$this->layout = 'app_dashboard';
        $id = Yii::$app->request->post('TenantPayments')['id'];
        $totalamount = Yii::$app->request->post('TenantPayments')['totalamount'];
        //$charges = Yii::$app->request->post('charges');
        //$rent = Yii::$app->request->post('rent');
        //$date = Yii::$app->request->post('date');

        $model = \app\models\TenantPayments::findOne($id);

        $amountWallet = 0;
        $walletAmount = Wallets::findOne(['user_id' => Yii::$app->user->id]);

        if ($walletAmount) {
            $amountWallet = $walletAmount->amount;
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->payment_mode = $payment_mode;
            $model->updated_date = date('Y-m-d H:i:s');

            if ($model->validate()) {

                $model->save(false);
                $modelHistory = new WalletsHistory;
                $modelHistory->amount = $model->total_amount;
                $modelHistory->email_id = Yii::$app->userdata->email;
                $modelHistory->transaction_type = 4;
                $modelHistory->created_by = Yii::$app->user->id;
                $modelHistory->created_date = date('Y-m-d H:i:s');
                $modelHistory->save(false);

                $walletAmount->amount = $amountWallet - $model->total_amount;
                $walletAmount->updated_by = Yii::$app->user->id;
                $walletAmount->updated_date = date('Y-m-d H:i:s');
                $walletAmount->save(false);

                Yii::$app->getSession()->setFlash(
                        'success', 'Your have paid rent successfully.'
                );
                //return $this->redirect(['mypaymentsdue']);
            }
        }
    }

    public function actionPayrent2() {
        $this->layout = 'app_dashboard';
        $id = Yii::$app->request->get('id');
        $totalamount = Yii::$app->request->get('totalamount');
        $charges = Yii::$app->request->get('charges');
        $rent = Yii::$app->request->get('rent');
        $date = Yii::$app->request->get('date');

        $model = \app\models\TenantPayments::findOne($id);

        $amountWallet = 0;
        $walletAmount = Wallets::findOne(['user_id' => Yii::$app->user->id]);

        if ($walletAmount) {
            $amountWallet = $walletAmount->amount;
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->payment_mode = 2;
            $model->updated_date = date('Y-m-d H:i:s');

            if ($model->validate()) {

                $model->save(false);
                $modelHistory = new WalletsHistory;
                $modelHistory->amount = $model->total_amount;
                $modelHistory->email_id = Yii::$app->userdata->email;
                $modelHistory->transaction_type = 4;
                $modelHistory->created_by = Yii::$app->user->id;
                $modelHistory->created_date = date('Y-m-d H:i:s');
                $modelHistory->save(false);

                $walletAmount->amount = $amountWallet - $model->total_amount;
                $walletAmount->updated_by = Yii::$app->user->id;
                $walletAmount->updated_date = date('Y-m-d H:i:s');
                $walletAmount->save(false);

                Yii::$app->getSession()->setFlash(
                        'success', 'Your have paid rent successfully.'
                );
                return $this->redirect(['mypaymentsdue']);
            }
        }

        return $this->render('payrent', [
                    'model' => $model, 'amountWallet' => $amountWallet
        ]);
    }

    public function actionPayrentadvance() {


        $this->layout = 'app_dashboard';

        $id = Yii::$app->request->get('id');


        $model = \app\models\TenantPayments::findOne($id);

        $amountWallet = 0;
        $walletAmount = Wallets::findOne(['user_id' => Yii::$app->user->id]);

        if ($walletAmount) {
            $amountWallet = $walletAmount->amount;
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->payment_mode = 2;
            $model->updated_date = date('Y-m-d H:i:s');

            if ($model->validate()) {

                $model->save(false);
                $modelHistory = new WalletsHistory;
                $modelHistory->amount = $model->total_amount;
                $modelHistory->email_id = Yii::$app->userdata->email;
                $modelHistory->transaction_type = 4;
                $modelHistory->created_by = Yii::$app->user->id;
                $modelHistory->created_date = date('Y-m-d H:i:s');
                $modelHistory->save(false);

                $walletAmount->amount = $amountWallet - $model->total_amount;
                $walletAmount->updated_by = Yii::$app->user->id;
                $walletAmount->updated_date = date('Y-m-d H:i:s');
                $walletAmount->save(false);

                Yii::$app->getSession()->setFlash(
                        'success', 'Your have paid rent successfully.'
                );
                return $this->redirect(['mypaymentsforcast']);
            }
        }

        return $this->render('payrentadvance', [
                    'model' => $model, 'amountWallet' => $amountWallet
        ]);
    }

    /**
     * search records
     * */
    public function search_quer() {
        echo "hello";
    }

    public function actionSearch() {
        $this->layout = 'search';

        $excluded_properties = Yii::$app->userdata->getExcludedProperties();


        /*         * ******************************************* start From Here Only *********************************** */

        $sort_flat = Yii::$app->request->get('sort_flat');
        $lat = Yii::$app->request->get('lat');
        $lon = Yii::$app->request->get('lon');
        $radius = 5;
        $property_type = Yii::$app->request->get('property_type');
        $bhk = Yii::$app->request->get('bhk');
        $min_amount = Yii::$app->request->get('min_amount');
        $max_amount = Yii::$app->request->get('max_amount');
        $min_area = Yii::$app->request->get('min_area');
        $max_area = Yii::$app->request->get('max_area');
        $area = Yii::$app->request->get('area');
        $min_radius = Yii::$app->request->get('min_radius');
        $max_radius = Yii::$app->request->get('max_radius');

        if (Yii::$app->request->get('property_facilities')) {
            $property_facilities1 = Yii::$app->request->get('property_facilities');
        } else {
            $property_facilities1 = Array();
        }

        if (Yii::$app->request->get('complex_facilities')) {
            $complex_facilities = Yii::$app->request->get('complex_facilities');
        } else {
            $complex_facilities = Array();
        }

        $flat_type = Yii::$app->request->get('flat_type');
        $property_id = Yii::$app->request->get('id');
        $property_facilities = array_merge($property_facilities1, $complex_facilities);
        if (isset($property_facilities) && is_array($property_facilities)) {
            $property_facilities = array_filter($property_facilities);
        }
        $searcharea = Yii::$app->request->get('search');
        $orderby = '';
        $origLat = $lat;
        $origLon = $lon;
        $dist = $radius;
        $property_typesql = '';
        $property_bhk = '';
        $property_flat_type = '';
        $property_id_filter = '';
        $amountQuery = '';
        $areaQuery = '';
        $propertiesFac = '';
        $slectData = '';
        $property_bhkJoin = '';
        $cityQuery = '';
        $latlonQuery = '';
        $latlonQuerySelect = '';
        $properQuery = '';
        $having = '';
        $bookingSql = '';
        $dataPropId = [];
        $dataProp = '';
        $property_amountJoin = '';
        $slectAmout = 'property_listing.*,';
        if ($origLat != '' && $origLon != '') {
            $latlonQuery = " 3956 * 2 *
                            ASIN(SQRT( POWER(SIN(($origLat - lat)*pi()/180/2),2)
                            +COS($origLat*pi()/180 )*COS(lat*pi()/180)
                            *POWER(SIN(($origLon-lon)*pi()/180/2),2)))
                            as distance,";
            if ($max_radius != '' && $min_radius != '') {
                $having = " having distance between " . $min_radius . " AND " . $max_radius;
            } else {
                $having = " having distance < $dist  ";
            }
        } else {
            $city = Yii::$app->userdata->getCityCodeById(Yii::$app->request->get('city'));
            if ($city != '') {
                $cityQuery = 'AND (properties.city ="' . $city . '") ';
            }
        }

        $sortQuery = '';
        if ($sort_flat == 'name') {
            $sortQuery = 'order by  properties.property_name ASC';
        } else if ($sort_flat == 'rent_low') {
            $sortQuery = 'order by  property_listing.rent ASC';
        } else if ($sort_flat == 'rent_high') {
            $sortQuery = 'order by  property_listing.rent DESC';
        } else if ($sort_flat == 'area_low') {
            $sortQuery = 'order by  properties.flat_area ASC';
        } else if ($sort_flat == 'area_high') {
            $sortQuery = 'order by  properties.flat_area DESC';
        } else if ($sort_flat == 'bhk_asc') {
            $sortQuery = 'order by  properties.flat_bhk ASC';
        } else if ($sort_flat == 'bhk_desc') {
            $sortQuery = 'order by  properties.flat_bhk DESC';
        }



        if (!empty($property_facilities)) {

            $property_facilities = implode(',', $property_facilities);
            $property_facilities = trim($property_facilities, ',');
            $slectData = ' property_attribute_map.*, ';
            $propertiesFac = ' AND property_attribute_map.attr_id IN (' . $property_facilities . ') ';
            $property_bhkJoin = 'LEFT JOIN property_attribute_map ON properties.id = property_attribute_map.property_id';
        }

        if (!empty($area)) {

            $areaQuery = ' AND  properties.flat_area > 5000 ';
        } else if (!empty($min_area) && !empty($max_area)) {

            $areaQuery = ' AND (properties.flat_area between ' . $min_area . ' AND ' . $max_area . ')  ';
        }

        $slectAmout = '';
        if (!empty($min_amount) && !empty($max_amount)) {
            $slectAmout = 'property_listing.*,';
            $amountQuery = ' AND (property_listing.rent between ' . $min_amount . ' AND ' . $max_amount . ')  ';
            $property_amountJoin = 'LEFT JOIN property_listing ON properties.id = property_listing.property_id';
        }

        if (is_array($property_type)) {
            $property_type = implode(',', $property_type);
            $property_type = trim($property_type, ',');
            $property_typesql = ' AND properties.property_type IN (' . $property_type . ')';
        } elseif ($property_type != '') {
            $property_typesql = ' AND  properties.property_type =' . $property_type;
        }

        if (is_array($bhk)) {
            $bhk = implode(',', $bhk);
            $bhk = trim($bhk, ',');

            $property_bhk = ' AND properties.flat_bhk IN (' . $bhk . ') ';
        }

        if ($flat_type) {
            $property_flat_type = ' AND properties.flat_type = ' . $flat_type;
        }

        $adjacents = 3;

        $limit = 10;         //how many items to show per page
        $page = isset($_GET['page']) ? $_GET['page'] : '';
        if ($page)
            $start = ($page - 1) * $limit;    //first item to display on this page
        else
            $start = 0;        //if no page var is given, set start to 0

        $modelcount = Properties::findBySql("SELECT $slectAmout $slectData $latlonQuery  properties.* FROM property_listing LEFT JOIN properties ON property_listing.property_id=properties.id
			            $property_bhkJoin
			             WHERE property_listing.status='1'  $cityQuery  $latlonQuerySelect
			             $property_typesql $property_bhk $property_flat_type $amountQuery $areaQuery $propertiesFac $properQuery GROUP BY properties.id  $having")->all();

        $total_pages = count($modelcount);

        if ($latlonQuery != '') {
            if ($total_pages == 0) {

                $modelcount = Properties::findBySql("SELECT $slectAmout $slectData $latlonQuery  properties.* FROM property_listing LEFT JOIN properties ON property_listing.property_id=properties.id
			            $property_bhkJoin
			             WHERE property_listing.status='1'  $cityQuery  
			             $property_typesql $property_bhk $property_flat_type $amountQuery $areaQuery $propertiesFac $properQuery GROUP BY properties.id  $having")->all();
                $total_pages = count($modelcount);
            }
        }

        $total_pages1 = $total_pages;
        if ($total_pages == '0') {
            $modelcount = Properties::findBySql("SELECT $slectAmout $slectData $latlonQuery  properties.* FROM property_listing LEFT JOIN properties ON property_listing.property_id=properties.id
			            $property_bhkJoin
			             WHERE property_listing.status='1'  $cityQuery  
			             $property_typesql $property_bhk $property_flat_type $amountQuery $areaQuery $propertiesFac $properQuery GROUP BY properties.id")->all();
            $total_pages = count($modelcount);
            $max_distance = 0;
        }

        if ($total_pages1 != '0') {
            $sql = "SELECT $slectAmout $slectData $latlonQuery  properties.* FROM property_listing LEFT JOIN properties ON property_listing.property_id=properties.id
				$property_bhkJoin
				WHERE property_listing.status='1'  $cityQuery 
				$property_typesql $property_bhk $property_flat_type  $amountQuery $areaQuery $propertiesFac $properQuery GROUP BY properties.id  $having $sortQuery LIMIT $start, $limit";
        } else {
            $sql = "SELECT $slectAmout $slectData $latlonQuery  properties.* FROM property_listing LEFT JOIN properties ON property_listing.property_id=properties.id
				$property_bhkJoin
				WHERE property_listing.status='1'  $cityQuery 
				$property_typesql $property_bhk $property_flat_type  $amountQuery $areaQuery $propertiesFac $properQuery GROUP BY properties.id  $sortQuery LIMIT $start, $limit";
        }

        $model = Properties::findBySql($sql)->with('propertyListing')->all();

        $lat_sum = 0;
        $lng_sum = 0;
        $count_sum = 0;
        foreach ($model as $value_model) {
            $lat_sum += $value_model->lat;
            $lng_sum += $value_model->lon;
            $count_sum++;
        }
        $max_distance = 0;
        $lat_avg = 0;
        $lng_avg = 0;
        if ($count_sum != '0') {
            $lat_avg = $lat_sum / $count_sum;
            $lng_avg = $lng_sum / $count_sum;


            foreach ($model as $key_model => $value_model) {
                $theta = $lng_avg - $value_model->lon;
                $dist = sin(deg2rad($lat_avg)) * sin(deg2rad($value_model->lat)) + cos(deg2rad($lat_avg)) * cos(deg2rad($value_model->lat)) * cos(deg2rad($theta));
                $dist = acos($dist);
                $dist = rad2deg($dist);
                $miles = $dist * 60 * 1.1515 * 1.609344;

                if ($miles > $max_distance) {
                    $max_distance = $miles;
                }
            }
        }

        $variable = Yii::$app->request->absoluteUrl;
        $targetpage = strpos($variable, "&page") ? substr($variable, 0, strpos($variable, "&page")) : $variable;
        /**/
        /* Setup page vars for display. */
        if ($page == 0)
            $page = 1;     //if no page var is given, default to 1.
        $prev = $page - 1;       //previous page is page - 1
        $next = $page + 1;       //next page is page + 1
        $lastpage = ceil($total_pages / $limit);  //lastpage is = total pages / items per page, rounded up.
        $lpm1 = $lastpage - 1;      //last page minus 1

        /*
          Now we apply our rules and draw the pagination object.
          We're actually saving the code to a variable in case we want to draw it more than once.
         */
        $pagination = "";
        if ($lastpage > 1) {
            $pagination .= "<ul class=\"pagination\">";
            //previous button
            if ($page > 1)
                $pagination .= "<li><a href=\"$targetpage&page=$prev\"> previous</a></li>";
            else
                $pagination .= "<li class=\"disabled\"><span>previous</span></li>";

            //pages
            if ($lastpage < 7 + ($adjacents * 2)) { //not enough pages to bother breaking it up
                for ($counter = 1; $counter <= $lastpage; $counter++) {
                    if ($counter == $page)
                        $pagination .= "<li class=\"active\"><span >$counter</span></li>";
                    else
                        $pagination .= "<li><a href=\"$targetpage&page=$counter\">$counter</a></li>";
                }
            }
            elseif ($lastpage > 5 + ($adjacents * 2)) { //enough pages to hide some
                //close to beginning; only hide later pages
                if ($page < 1 + ($adjacents * 2)) {
                    for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                        if ($counter == $page)
                            $pagination .= "<li class=\"active\"><span >$counter</span></li>";
                        else
                            $pagination .= "<li><a href=\"$targetpage&page=$counter\">$counter</a></li>";
                    }
                    $pagination .= "...";
                    $pagination .= "<li><a href=\"$targetpage&page=$lpm1\">$lpm1</a></li>";
                    $pagination .= "<li><a href=\"$targetpage&page=$lastpage\">$lastpage</a></li>";
                }
                //in middle; hide some front and some back
                elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                    $pagination .= "<li><a href=\"$targetpage&page=1\">1</a>";
                    $pagination .= "<li><a href=\"$targetpage&page=2\">2</a>";
                    $pagination .= "...";
                    for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                        if ($counter == $page)
                            $pagination .= "<li class=\"active\"><span>$counter</span></li>";
                        else
                            $pagination .= "<li><a href=\"$targetpage&page=$counter\">$counter</a></li>";
                    }
                    $pagination .= "...";
                    $pagination .= "<li><a href=\"$targetpage&page=$lpm1\">$lpm1</a></li>";
                    $pagination .= "<li><a href=\"$targetpage&page=$lastpage\">$lastpage</a></li>";
                }
                //close to end; only hide early pages
                else {
                    $pagination .= "<li><a href=\"$targetpage&page=1\">1</a></li>";
                    $pagination .= "<li><a href=\"$targetpage&page=2\">2</a></li>";
                    $pagination .= "...";
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                        if ($counter == $page)
                            $pagination .= "<li><span class=\"active\">$counter</span></li>";
                        else
                            $pagination .= "<li><a href=\"$targetpage&page=$counter\">$counter</a></li>";
                    }
                }
            }

            //next button
            if ($page < $counter - 1)
                $pagination .= "<li><a href=\"$targetpage&page=$next\">next </a></li>";
            else
                $pagination .= "<li  class=\"disabled\"><span>next </span></li>";
            $pagination .= "</ul>\n";
        }

        $dataMaps = array();
        foreach ($model as $key => $address) {
            $dataMaps[$key][] = $address->property_name;
            $dataMaps[$key][] = $address->lat;
            $dataMaps[$key][] = $address->lon;
            $dataMaps[$key][] = $address->id;
        }
        $locations = json_encode($dataMaps);

        $propertyFacility = [];

        $propertyAttrFacility = PropertiesAttributes::find()->where(['type' => '1'])->all();
        foreach ($propertyAttrFacility as $key => $propertyData) {
            $propertyFacility[$propertyData->id] = $propertyData->name;
        }

        $propertyComplex = [];
        $propertyAttrComplex = PropertiesAttributes::find()->where(['type' => '2'])->all();
        foreach ($propertyAttrComplex as $key => $propertyData) {
            $propertyComplex[$propertyData->id] = $propertyData->name;
        }

        $propertytypename = Yii::$app->userdata->getPropertyTypeName($property_type);
        return $this->render('search', [
                    'model' => $model, 'searcharea' => $searcharea, 'locations' => $locations, 'propertyComplex' => $propertyComplex, 'propertyFacility' => $propertyFacility, 'propertytypename' => $propertytypename,
                    'pagination' => $pagination, 'per_page' => $limit, 'total_pages' => $total_pages, 'flat_type' => $flat_type,
                    'min_area' => $min_area, 'max_area' => $max_area, 'min_amount' => $min_amount, 'max_amount' => $max_amount, 'min_radius' => $min_radius, 'max_radius' => $max_radius, 'center' => ['lat' => $lat_avg, 'lng' => $lng_avg, 'distance' => $max_distance], 'property_type' => $property_type
        ]);
    }
    
    public function actionPayrentneft () {
        $this->layout = false;
        if (!empty(Yii::$app->request->post('neft-referer-' . Yii::$app->request->post('serial')))) {
            $tpId = Yii::$app->getSecurity()->decryptByKey(base64_decode(Yii::$app->request->post('TenantPayments')['id']), Yii::$app->params['hash_key']);
            $penaltyAmount = Yii::$app->userdata->calculatePenalty($tpId);
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model = \app\models\TenantPayments::findOne($tpId);

                if ($model->load(Yii::$app->request->post())) {
                    $model->payment_date = date('Y-m-d');
                    $model->updated_date = date('Y-m-d H:i:s');
                    $model->payment_mode = 1;
                    $model->payment_status = 2;
                    $model->neft_reference = Yii::$app->request->post('neft-referer-' . Yii::$app->request->post('serial'));
                    $model->penalty_amount = $penaltyAmount;
                    $model->save(false);
                }

                $transaction->commit();
                
                Yii::$app->getSession()->setFlash(
                    'success', "Thanks for making payment via NEFT, we will update the payment status to 'Paid' once we have verified the payment data against our bank records. The verification normally takes 2-3 business days. In case of any mismatch, our operations team will reach out to you for any further clarifications."
                );
                if (!empty(Yii::$app->request->post('app_redirect_url'))) {
                    return $this->redirect(Yii::$app->request->post('app_redirect_url'));
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->getSession()->setFlash('success', "{$e->getMessage()}");
            }
        } else {
            throw new \yii\web\ForbiddenHttpException('You are not allowed to directly access this page.');
        }
    }
    
    public function actionPayrentneftapp () {
        $this->layout = false;
        if (
            empty(Yii::$app->request->post('x-api-key')) || 
            empty(Yii::$app->request->post('tpRowId')) || 
            empty(Yii::$app->request->post('neft-reference-no'))
        ) {
            return $this->redirect(Url::home(true).'api/v1/tenant/rentpaymentfailed');
        }
        
        $userModel = Yii::$app->restbasicauth->getUserModelFromToken(Yii::$app->request->post('x-api-key'));
        if (count($userModel) == 0) {
            return $this->redirect(Url::home(true).'api/v1/tenant/rentpaymentfailed');
        }
        
        $tpId = Yii::$app->getSecurity()->decryptByKey(base64_decode(Yii::$app->request->post('tpRowId')), Yii::$app->params['hash_key']);
        $model = \app\models\TenantPayments::find()->where(['id' => $tpId])->one();
        if (empty($model)) {
            return $this->redirect(Url::home(true).'api/v1/tenant/rentpaymentfailed');
        }
        
        $penaltyAmount = Yii::$app->userdata->calculatePenalty($tpId);
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            $model->payment_date = date('Y-m-d');
            $model->updated_date = date('Y-m-d H:i:s');
            $model->payment_mode = 1;
            $model->payment_status = 2;
            $model->neft_reference = Yii::$app->request->post('neft-reference-no');
            $model->penalty_amount = $penaltyAmount;
            $model->save(false);

            $transaction->commit();
            return $this->redirect(Url::home(true).'api/v1/tenant/rentpaymentsuccessneft');
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->redirect(Url::home(true).'api/v1/tenant/rentpaymentfailed');
        }
    }

    public function actionUnpaynow() {
        if (!Yii::$app->user->isGuest) {
            if (!empty(Yii::$app->request->post('etc'))) {
                $model = \app\models\TenantPayments::find()->where(['id' => Yii::$app->request->post('etc')])->one();
                $model->payment_status = 0;
                $model->payment_date = null;
                $model->payment_mode = 0;
                $model->penalty_amount = 0;
                $model->neft_reference = null;
                $model->save(false);
                return $this->redirect(Yii::$app->request->post('app_redirect_url'));
            }
        }
    }

    public function actionCreatenotidownload() {
        if (!empty(Yii::$app->request->post('noti-download'))) {
            $data = json_decode(base64_decode(Yii::$app->request->post('noti-download')));
            $paymentStatus = $data->response;
            $amount = $data->amount;
            $paydesc = $data->paydesc;
            $txndate = $data->txndate;
            $txnid = $data->txnid;
            
            $userId = (!empty(Yii::$app->request->post('noti-download-user'))) ? Yii::$app->userdata->getUserNameById(Yii::$app->request->post('noti-download-user')) : Yii::$app->userdata->getUserNameById(Yii::$app->user->id) ;

            $html = "Date: " . date('d-M-Y') . " <br/>";

            $html = ' '
                    . 'Date: ' . date('d-M-Y') . ' <br />'
                    . '<center> ' . $paymentStatus . ' </center> <br />'
                    . '<br />'
                    . 'Client Name: ' . $userId . '<br />'
                    . ' <br />'
                    . 'Payment Amount: ' . $amount . ' INR <br />'
                    . ' <br />'
                    . 'Payment Description: ' . $paydesc . ' <br />'
                    . ' <br />'
                    . 'Transaction Date: ' . $txndate . ' <br />'
                    . ' <br />'
                    . 'Transaction Id: ' . $txnid . ' <br />'
                    . ' <br />'
            ;
            $this->PDF($html, date('ymdhis'));
        }
    }
    
    public function PDF($html, $name) {

        try {
            // create an API client instance
            $client = new Pdfcrowd("SAM_15YFA", "c1ff61bd70d577deec0db16940fa8edc");
            $client->usePrintMedia(true);
            $client->setPageWidth("8.5in");
            $client->setPageHeight("11in");
            $client->setVerticalMargin("1.7in");

            $header = '<br />'
                    . '<div style="text-align: right;"><img style="height: 53px; width: 200px;" src="http://www.easyleases.in/images/newlogo1.png" /></div>'
                    . '<center>'
                    . '<span><b>Easyleases Technologies Private Limited</b></span><br>'
                    . '<span>Registered Address: RG-708, Purva Riviera, Varthur Road, Bangalore – 560037</span><br>'
                    . '<span>CIN: U70109KA2017PTC100691</span>'
                    . '</center> <br />'
                    . '<hr style="border-color: black;" /> <br />'
                    . '';
            $client->setHeaderHtml($header);

            $footer = '<br />'
                    . '<br />'
                    . '<br />'
                    . '<div style="text-align: center;">'
                    . '<span>Note: This is a computer-generated receipt and doesn’t require signature. For any queries, please</span> <br />'
                    . '<span>email your query to suppport@easyleases.in</span> <br />'
                    . '<br />'
                    . '<span style="color: green;">www.easyleases.in</span>';
            $client->setFooterHtml($footer);
            // convert a web page and store the generated PDF into a $pdf variable
            $pdf = $client->convertHtml($html);

            // set HTTP response headers
            header("Content-Type: application/pdf");
            header("Cache-Control: max-age=0");
            header("Accept-Ranges: none");
            header("Content-Disposition: attachment; filename=\"" . $name . ".pdf\"");

            // send the generated PDF
            echo $pdf;
        } catch (PdfcrowdException $why) {
            echo "Pdfcrowd Error: " . $why;
        }
    }

    /**
     * Displays a single Properties model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        //$this->layout = 'single_page';
        $this->layout = 'property_detail';
        $modelvisit = new \app\models\PropertyVisits;
        $excluded_properties = Yii::$app->userdata->getExcludedProperties();

        if (($model = Properties::findOne($id)) !== null) {
            if (Yii::$app->request->post()) {
                if (Yii::$app->user->isGuest) {
                    Yii::$app->session->set('open_sch_popup', Yii::$app->request->referrer . '&opu=1');
                    $_SESSION['open_sch_popup_date'] = Yii::$app->request->post('PropertyVisits')['visit_date'];
                    $_SESSION['open_sch_popup_time'] = Yii::$app->request->post('PropertyVisits')['visit_time'];
                    return $this->redirect('login');
                }

                $modelFavProp = FavouriteProperties::findOne(['property_id' => $id, 'applicant_id' => Yii::$app->userdata->email]);
                if ($modelFavProp) {
                    $modelFavProp->visit_date = Yii::$app->request->post('PropertyVisits')['visit_date'];
                    $modelFavProp->visit_time = Yii::$app->request->post('PropertyVisits')['visit_time'];
                    $modelFavProp->child_properties = Yii::$app->request->post('PropertyVisits')['child_properties'];
                    $modelFavProp->save(false);
                } else {
                    $modelFavProp = new \app\models\FavouriteProperties;
                    $modelFavProp->property_id = $id;
                    $modelFavProp->child_properties = Yii::$app->request->post('PropertyVisits')['child_properties'];
                    $modelFavProp->applicant_id = Yii::$app->userdata->email;
                    $modelFavProp->created_by = Yii::$app->userdata->id;
                    $modelFavProp->created_date = date('Y-m-d H:i:s');
                    $modelFavProp->status = '3';
                    $modelFavProp->visit_date = Yii::$app->request->post('PropertyVisits')['visit_date'];
                    $modelFavProp->visit_time = Yii::$app->request->post('PropertyVisits')['visit_time'];
                    $modelFavProp->type = Yii::$app->request->post('PropertyVisits')['type'];
                    $modelFavProp->save(false);
                    Yii::$app->session->setFlash('Visit Scheduled.');
                }
            }

            if (Yii::$app->user->isGuest) {
                $modelFav = 0;
            } else {
                $modelFav = FavouriteProperties::find()->where(['property_id' => $id, 'applicant_id' => Yii::$app->userdata->email])->count();
            }

            $attributesFeat = array();
            $modelPropertiesAttributes = PropertiesAttributes::find()->where('type ="1"')->all();
            foreach ($modelPropertiesAttributes as $modelAttributes) {
                $attributesFeat[] = $modelAttributes->id;
            }

            $attri = array();
            $propertyAttributeMap = PropertyAttributeMap::find()->where(['property_id' => $id])->andWhere(['IN', 'attr_id', $attributesFeat])->all();

            foreach ($propertyAttributeMap as $propertyData) {
                $name = PropertiesAddress::getAttributeName($propertyData->attr_id);
                if ($propertyData->value != 0) {
                    $attri[] = ' ' . $name;
                }
            }

            $attributesInputs = array();
            $modelPropertiesInputs = PropertiesAttributes::find()->where('type ="1"')->all();
            foreach ($modelPropertiesInputs as $modelInputs) {
                $attributesInputs[] = $modelInputs->id;
            }

            $attriBox = array();
            $propertyAttributeMap = PropertyAttributeMap::find()->where(['property_id' => $id])->andWhere(['IN', 'attr_id', $attributesInputs])->all();
            foreach ($propertyAttributeMap as $propertyData) {
                $name = PropertiesAddress::getAttributeName($propertyData->attr_id);
                $attriBox[$name] = $propertyData->value;
            }

            $attributesComplex = array();
            $modelPropertiesComplex = PropertiesAttributes::find()->where('type ="2"')->all();
            foreach ($modelPropertiesComplex as $modelComplex) {
                $attributesComplex[] = $modelComplex->id;
            }

            $amenities = array();
            $propertyAttributeMap = PropertyAttributeMap::find()->where(['property_id' => $id])->andWhere(['IN', 'attr_id', $attributesComplex])->all();
            foreach ($propertyAttributeMap as $key => $propertyData) {
                $name = PropertiesAddress::getAttributeTotal($propertyData->attr_id);
                if ($name) {
                    $amenities[$key]['name'] = ' ' . $name->name;
                    $amenities[$key]['icon'] = ' ' . $name->icon;
                }
            }


            $features = array();
            $propertyAttributeMap = PropertyAttributeMap::find()->where(['property_id' => $id])->andWhere(['IN', 'attr_id', $attributesInputs])->all();
            foreach ($propertyAttributeMap as $key => $propertyData) {
                $name = PropertiesAddress::getAttributeTotal($propertyData->attr_id);
                if ($name) {
                    $features[$key]['name'] = ' ' . $name->name;
                    $features[$key]['icon'] = ' ' . $name->icon;
                    $features[$key]['value'] = ' ' . $propertyData->value;
                }
            }

            $bookingsTenant = [];
            $bookings = [];
            $dataImages = $this->findPropertyImages($id);
            $mainImage = $this->findMainImages($id);
            $visitDateTime = $this->findVisitDateTime($id);
            $visitDateTimeBooked = $this->findVisitDateTimeIfBooked($id);
            $booked = false;
            if (!empty($visitDateTimeBooked)) {
                $visitDateTime = $visitDateTimeBooked;
                $booked = true;
            }
            
            if (count($visitDateTime) == '') {
                $visitdate = '';
                $visittime = '';
            } else {
                $visitdate = $visitDateTime['visit_date'];
                $visittime = $visitDateTime['visit_time'];
            }
            $rooms = 0;
            $beds = 0;
            if ($model->property_type == 3) {
                //return $this->render('view_single', [
                return $this->render('property_detail', [
                            'model' => $model, 'feathureList' => $attri, 'modelFav' => $modelFav, 'bookings' => $bookings, 'attriBox' => $attriBox, 'amenities' => $amenities, 'dataImages' => $dataImages, 'modelvisit' => $modelvisit, 'rooms' => $rooms, 'beds' => $beds, 'features' => $features,
                            'bookingsTenant' => count($bookingsTenant), 'mainImage' => $mainImage, 'visitdate' => $visitdate, 'visittime' => $visittime, 'booked' => $booked]);
            } else {

                $minimum_rent = \app\models\ChildPropertiesListing::find()->select('MIN(rent) as rent')->where(['main' => $id])->andWhere('rent != "0"')->one();

                if (count($minimum_rent) == '0') {
                    $minimum_rent = 'NA';
                } else {
                    $minimum_rent = $minimum_rent['rent'];
                }

                $minimum_maintenance = \app\models\ChildPropertiesListing::find()->select('MIN(maintenance) as maintenance')->where(['main' => $id])->andWhere('maintenance != "0"')->one();
                if (count($minimum_rent) == '0') {
                    $minimum_maintenance = 'NA';
                } else if (count($minimum_maintenance) == '0') {
                    $minimum_maintenance = 'included';
                } else {
                    $minimum_maintenance = $minimum_maintenance['maintenance'];
                }

                $minimum_token = \app\models\ChildPropertiesListing::find()->select('MIN(token_amount) as token_amount')->where(['main' => $id])->andWhere('token_amount != "0"')->one();
                if (count($minimum_token) == '0') {
                    $minimum_token = 'NA';
                } else {
                    $minimum_token = $minimum_token['token_amount'];
                }

                $minimum_deposit = \app\models\ChildPropertiesListing::find()->select('MIN(deposit) as deposit')->where(['main' => $id])->andWhere('deposit != "0"')->one();
                if (count($minimum_deposit) == '0') {
                    $minimum_deposit = 'NA';
                } else {
                    $minimum_deposit = $minimum_deposit['deposit'];
                }

                //return $this->render('view_single_coliving', [
                return $this->render('property_detail_shared', [
                            'model' => $model, 'feathureList' => $attri, 'modelFav' => $modelFav, 'bookings' => $bookings, 'attriBox' => $attriBox, 'amenities' => $amenities, 'dataImages' => $dataImages, 'modelvisit' => $modelvisit, 'rooms' => $rooms, 'beds' => $beds, 'features' => $features,
                            'bookingsTenant' => count($bookingsTenant), 'mainImage' => $mainImage, 'visitdate' => $visitdate, 'visittime' => $visittime, 'booked' => $booked, 'minimum_rent' => $minimum_rent, 'minimum_maintenance' => $minimum_maintenance, 'minimum_deposit' => $minimum_deposit, 'minimum_token' => $minimum_token]);
            }
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findPropertyImages($id) {
        $model = PropertyImages::find()->where(['property_id' => $id])->all();
        if ($model !== null) {
            return $model;
        }
    }

    protected function findMainImages($id) {
        $model = PropertyImages::find()->where(['property_id' => $id, 'is_main' => '1'])->one();
        if ($model !== null) {
            return $model['image_url'];
        } else {
            $model1 = PropertyImages::find()->where(['property_id' => $id])->one();
            return $model1['image_url'];
        }
    }

    public function findVisitDateTime($id) {
        $model = FavouriteProperties::find()->select('visit_date,visit_time')->where(['property_id' => $id, 'applicant_id' => Yii::$app->userdata->email, 'status' => 3])->one();
        if ($model !== null) {
            return $model;
        } else {
            return Array();
        }
    }
    
    public function findVisitDateTimeIfBooked($id) {
        $model = FavouriteProperties::find()->select('visit_date,visit_time')->where(['property_id' => $id, 'applicant_id' => Yii::$app->userdata->email, 'status' => 2])->one();
        if ($model !== null) {
            return $model;
        } else {
            return Array();
        }
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin() {
        $this->view->params['head_title'] = "Rental Websites | Residential Property Management | Rental Management";
        $this->view->params['meta_desc'] = "Rental Websites, Residential Property Management, Rental Management, Residential Property Management Services, Property Maintenance Company, House Maintenance";
        $this->layout = 'signup';
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        
        if (empty($_SESSION['login_referer'])) {
            $_SESSION['login_referer'] = Yii::$app->request->referrer;
        }
        
        $model = new LoginForm();
        
        if (!empty(Yii::$app->request->post('login')) && !empty(Yii::$app->request->post('phone'))) {
            $userModel = User::find()->where(['phone' => Yii::$app->request->post('phone')])->one();
            if (empty($userModel)) {
                exit('failed');
            }
            $loginID = $userModel->login_id;
            $loginArray['LoginForm']['username'] = $loginID;
            $loginArray['LoginForm']['password'] = 'XXXXXXX';
            $model->load($loginArray);
            if ($model->loginWithPhone()) {
                if (Yii::$app->user->identity->user_type == 1) {
                    return $this->redirect(['/admin/users']);
                } else {
                    $referUrl = $_SESSION['login_referer'];
                    unset($_SESSION['login_referer']);
                    switch (Yii::$app->user->identity->user_type) {
                        case 2:
                            if (Yii::$app->session->get('open_sch_popup')) {
                                $this->redirect(Yii::$app->session->get('open_sch_popup'));
                            } else if (Yii::$app->session->get('addfav_redirect')) {
                                $redirectUrl = Yii::$app->session->get('addfav_redirect');
                                $queryStr = parse_url(Yii::$app->session->get('addfav_redirect'));
                                parse_str($queryStr['query'], $parsedStr);
                                $this->actionAddfavAfterRedirect($parsedStr['status'], $parsedStr['property']);
                                Yii::$app->session->remove('addfav_redirect');
                                $this->redirect($redirectUrl);
                            } else {
                                if (!empty($referUrl)) {
                                    return $this->goBack($referUrl);
                                } else {
                                    $this->goBack();
                                }
                            }
                            break;
                        case 3:
                            if (!empty(Yii::$app->request->get('pmyd'))) {
                                $this->redirect('/site/mypaymentsdue');
                            } else if (Yii::$app->session->get('open_sch_popup')) {
                                $this->redirect(Yii::$app->session->get('open_sch_popup'));
                            } else if (Yii::$app->session->get('addfav_redirect')) {
                                $redirectUrl = Yii::$app->session->get('addfav_redirect');
                                $queryStr = parse_url(Yii::$app->session->get('addfav_redirect'));
                                parse_str($queryStr['query'], $parsedStr);
                                $this->actionAddfavAfterRedirect($parsedStr['status'], $parsedStr['property']);
                                Yii::$app->session->remove('addfav_redirect');
                                $this->redirect($redirectUrl);
                            } else {
                                if (!empty($referUrl)) {
                                    return $this->goBack($referUrl);
                                } else {
                                    $this->goBack();
                                }
                                // removed on 9th sep 2019.
                                // return $this->redirect(['/site/dashboard']);
                            }
                            break;
                        case 4:
                            return $this->redirect(['/site/mydashboard']);
                            break;
                        case 5:
                            return $this->redirect(['/advisers/myprofile']);
                            break;
                        case 6:
                            return $this->redirect(['/external']);
                            break;
                        case 7:
                            return $this->redirect(['/external']);
                            break;
                        case 8:
                            return $this->redirect(['/external/pgitxnlist']);
                            break;
                        case 9:
                            return $this->redirect(['/investor/index']);
                            break;
                    }
                }
            }
        }
        
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            if (Yii::$app->user->identity->user_type == 1) {
                return $this->redirect(['/admin/users']);
            } else {
                $referUrl = $_SESSION['login_referer'];
                unset($_SESSION['login_referer']);
                switch (Yii::$app->user->identity->user_type) {
                    case 2:
                        if (Yii::$app->session->get('open_sch_popup')) {
                            $this->redirect(Yii::$app->session->get('open_sch_popup'));
                        } else if (Yii::$app->session->get('addfav_redirect')) {
                            $redirectUrl = Yii::$app->session->get('addfav_redirect');
                            $queryStr = parse_url(Yii::$app->session->get('addfav_redirect'));
                            parse_str($queryStr['query'], $parsedStr);
                            $this->actionAddfavAfterRedirect($parsedStr['status'], $parsedStr['property']);
                            Yii::$app->session->remove('addfav_redirect');
                            $this->redirect($redirectUrl);
                        } else {
                            if (!empty($referUrl)) {
                                return $this->goBack($referUrl);
                            } else {
                                $this->goBack();
                            }
                        }
                        break;
                    case 3:
                        if (!empty(Yii::$app->request->get('pmyd'))) {
                                $this->redirect('/site/mypaymentsdue');
                        } else if (Yii::$app->session->get('open_sch_popup')) {
                            $this->redirect(Yii::$app->session->get('open_sch_popup'));
                        } else if (Yii::$app->session->get('addfav_redirect')) {
                            $redirectUrl = Yii::$app->session->get('addfav_redirect');
                            $queryStr = parse_url(Yii::$app->session->get('addfav_redirect'));
                            parse_str($queryStr['query'], $parsedStr);
                            $this->actionAddfavAfterRedirect($parsedStr['status'], $parsedStr['property']);
                            Yii::$app->session->remove('addfav_redirect');
                            $this->redirect($redirectUrl);
                        } else {
                            if (!empty($referUrl)) {
                                return $this->goBack($referUrl);
                            } else {
                                $this->goBack();
                            }
                            // removed on 9th sep 2019.
                            // return $this->redirect(['/site/dashboard']);
                        }
                        break;
                    case 4:
                        return $this->redirect(['/site/mydashboard']);
                        break;
                    case 5:
                        return $this->redirect(['/advisers/myprofile']);
                        break;
                    case 6:
                        return $this->redirect(['/external']);
                        break;
                    case 7:
                        return $this->redirect(['/external']);
                        break;
                    case 8:
                        return $this->redirect(['/external/pgitxnlist']);
                        break;
                    case 9:
                        return $this->redirect(['/investor/index']);
                        break;
                }
            }
        }
        return $this->render('login', [
                    'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout() {
        session_destroy();
        Yii::$app->user->logout();
        return $this->goHome();
    }

    function actionDatediff() {
        $move_in = $_POST['move_in'];
        $exit_date = $_POST['exit_date'];
        $minStay = $_POST['min_stay'];
        $d1 = strtotime($move_in);
        $d2 = strtotime($exit_date);
        $min_date = min($d1, $d2);
        $max_date = max($d1, $d2);
        $i = 0;

        while (($min_date = strtotime("+1 MONTH", $min_date)) <= $max_date) {
            $i++;
        }
        if (strtotime($_POST['availability_from']) > strtotime($move_in)) {
            echo 'not';
            die;
        }
        if ($i >= $minStay) {
            echo 'yes';
        } else {
            echo 'no';
        }
        die;
    }

    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionContact() {

        $model = new \app\models\ContactForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $model->name = Yii::$app->request->post('ContactForm')['name'];
                $model->email = Yii::$app->request->post('ContactForm')['email'];
                $model->phone = Yii::$app->request->post('ContactForm')['phone'];
                $model->message = Yii::$app->request->post('ContactForm')['message'];
                $model->user_type = Yii::$app->request->post('ContactForm')['user_type'];
                $model->created_date = date('Y-m-d H:i:s');
                $model->save(false);

                $subject = 'User Contacted From EasyLeases.In';

                $messBody = '';
                $messBody .= '<p>Hello Admin,</p>';
                $messBody .= '<p>A user has tried contacting with us from www.easyleases.in, please check the below details.</p>';
                $messBody .= '<table>';
                $messBody .= '<tr>';
                $messBody .= '<td>Name: </td><td>' . Yii::$app->request->post('ContactForm')['name'] . '</td>';
                $messBody .= '</tr>';
                $messBody .= '<tr>';
                $messBody .= '<td>Email: </td><td>' . Yii::$app->request->post('ContactForm')['email'] . '</td>';
                $messBody .= '</tr>';
                $messBody .= '<tr>';
                $messBody .= '<td>Phone: </td><td>' . Yii::$app->request->post('ContactForm')['phone'] . '</td>';
                $messBody .= '</tr>';
                $messBody .= '<tr>';
                $messBody .= '<td>Message: </td><td>' . Yii::$app->request->post('ContactForm')['message'] . '</td>';
                $messBody .= '</tr>';
                $messBody .= '</table>';
                $messBody .= '<p>EasyLeases Team</p>';

                @$email = Yii::$app->userdata->doMail(Yii::$app->params['supportEmail'], $subject, $messBody);
                if ($email) {
                    Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
                } else {
                    Yii::$app->getSession()->setFlash('success', 'Email not sent, Please try again');
                }
            }

            return $this->refresh();
        }
        return $this->render('contact', [
                    'model' => $model,
        ]);
    }

    public function actionForget() {

        $model = new ForgetForm();
        $this->view->params['head_title'] = "Property Management Services | Property Management";
        $this->view->params['meta_desc'] = "Property Management Services, Property Management, Best Property Management Services in Bangalore, Apartment Management, Bangalore Property Management for NRIs.";

        if ($model->load(Yii::$app->request->post())) {
            $modeluser = Users::find()->where([
                        'login_id' => $model->email
                    ])->andWhere('password !=""')->one();

            if ($model->validate()) {
                if ($modeluser) {

                    $href = Yii::$app->urlManager->createAbsoluteUrl(
                            ['users/forgetpassword', 'key' => base64_encode($model->email)]
                    );
                    $link = "<a href='" . $href . "'>Click Here</a>";
                    $subject1 = "Forgot Password";
                    $msg1 = " Hello " . $modeluser->full_name . ", <br />
								We have received a request to reset your password. <br />
								 " . $link . " to reset your password. <br /><br /> Thanks, <br />Easyleases Team<br/><img src='" . Url::home(true) . "images/property_logo.png' alt=''>";
                    @$email = Yii::$app->userdata->doMail($model->email, $subject1, $msg1);
                    if ($email) {
                        Yii::$app->getSession()->setFlash('success', 'Please check your email, we have sent a link to reset your password.');
                    } else {
                        Yii::$app->getSession()->setFlash('success', 'Email not sent, Please try again');
                    }
                } else {

                    Yii::$app->getSession()->setFlash(
                            'error', 'No user found, please try with your registered email.'
                    );
                    return $this->render('forget', [
                                'model' => $model,
                    ]);
                    die;
                }
            } else {
                return $this->render('forget', [
                            'model' => $model,
                ]);
            }
        }
        return $this->render('forget', [
                    'model' => $model,
        ]);
    }

    public function actionFeedback() {
        $model = new Feedback();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['supportEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('feedback', [
                    'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout() {
        return $this->render('about');
    }

    public function proofType($id) {
        $types = \app\models\ProofType::find()->select('name')->where(['id' => $id])->one();
        return $types->name;
    }

    public function refType($id) {
        $types = \app\models\RefStatus::find()->select('name')->where(['id' => $id])->one();
        if ($types) {
            return $types->name;
        } else {
            return '';
        }
    }

    public function actionGetroomtotalpricevals() {
        $property = $_POST['property'];
        $price = Yii::$app->userdata->getRoomTotal($property, $_POST['main']);
        return $price;
    }

    public function actionGetbedtotalpricevals() {
        $property = $_POST['property'];
        $price = Yii::$app->userdata->getBedTotal($property, $_POST['main']);
        return $price;
    }

    public function actionGetrequestforapproval() {
        $this->layout = 'owner_dashboard';
        $searchModelOwnerLeads = new \app\models\MaintenanceRequest();

        $dataProviderOwner = $searchModelOwnerLeads->searchOwnerProperties(Yii::$app->request->queryParams);

        return $this->render('maintenance_requests', [
                    'searchModelOwnerLeads' => $searchModelOwnerLeads,
                    'dataProviderOwner' => $dataProviderOwner,
        ]);
    }

    public function actionRequestdetail($rid, $id) {
        if (isset(Yii::$app->user->id)) {
            $this->layout = 'owner_dashboard';
            $rid1 = base64_decode($rid);
            $requestType = \app\models\RequestType::find()->All();
            $requestStatus = \app\models\RequestStatus::find()->All();
            $modelComments = new \app\models\PropertyServiceComment;
            $modelAttach = new ServiceRequestAttachment;
            if ($model = \app\models\PropertyServiceRequest::findOne(['id' => $rid1])) {
                $comments = \app\models\PropertyServiceComment::find()->where(['service_request_id' => $rid1])->orderBy('id desc')->limit(5)->all();
                if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
                    Yii::$app->response->format = 'json';
                    return \yii\bootstrap\ActiveForm::validate($model);
                } else if ($model->load(Yii::$app->request->post())) {
                    if ($model->validate()) {
                        $deletedfiles = $_POST['deletedfile'];
                        if ($deletedfiles != '') {
                            $filesarr = explode(",", $deletedfiles);
                            foreach ($filesarr as $fs) {
                                $modeldelete = \app\models\ServiceRequestAttachment::findOne(['id' => $fs]);
                                $modeldelete->delete();
                            }
                        }
                        $modelAttach->imageFiles = UploadedFile::getInstances($modelAttach, 'attachment');

                        if (!empty($modelAttach->imageFiles)) {
                            $modelAttach->upload($rid1);
                        }

                        $model->title = Yii::$app->request->post('PropertyServiceRequest')['title'];
                        $model->description = Yii::$app->request->post('PropertyServiceRequest')['description'];
                        $model->request_type = Yii::$app->request->post('PropertyServiceRequest')['request_type'];
                        $model->status = Yii::$app->request->post('PropertyServiceRequest')['status'];
                        $model->updated_by = Yii::$app->user->id;
                        $model->save(false);

                        $toEmail = [];
                        $ccEmail = [];

                        if ($model->client_type == 3) {
                            $modelAppProfile = \app\models\ApplicantProfile::find()->where(['applicant_id' => $model->client_id])->one();
                            $toEmail[] = Yii::$app->userdata->getEmailById($modelAppProfile->applicant_id);
                            if (!empty($modelAppProfile->operation_id)) {
                                $ccEmail[] = Yii::$app->userdata->getEmailById($modelAppProfile->operation_id);
                            }

                            if (!empty($modelAppProfile->sales_id)) {
                                $ccEmail[] = Yii::$app->userdata->getEmailById($modelAppProfile->sales_id);
                            }

                            if (!empty($modelAppProfile->branch_code)) {
                                $modelBranch = \app\models\OperationsProfile::find()->where(['role_code' => 'OPBRMG', 'role_value' => $modelAppProfile->branch_code])->one();
                                $ccEmail[] = Yii::$app->userdata->getEmailById($modelBranch->operations_id);
                            }
                        } else if ($model->client_type == 4) {
                            $modelAppProfile = \app\models\OwnerProfile::find()->where(['owner_id' => $model->client_id])->one();
                            $toEmail[] = Yii::$app->userdata->getEmailById($modelAppProfile->owner_id);
                            if (!empty($modelAppProfile->operation_id)) {
                                $ccEmail[] = Yii::$app->userdata->getEmailById($modelAppProfile->operation_id);
                            }

                            if (!empty($modelAppProfile->sales_id)) {
                                $ccEmail[] = Yii::$app->userdata->getEmailById($modelAppProfile->sales_id);
                            }

                            if (!empty($modelAppProfile->branch_code)) {
                                $modelBranch = \app\models\OperationsProfile::find()->where(['role_code' => 'OPBRMG', 'role_value' => $modelAppProfile->branch_code])->one();
                                $ccEmail[] = Yii::$app->userdata->getEmailById($modelBranch->operations_id);
                            }
                        } else {
                            
                        }

                        $ccEmail[] = Yii::$app->params['supportEmail'];
                        $subject = "Update Service Request: # $model->id";

                        $comments = \app\models\PropertyServiceComment::find()->where(['service_request_id' => $model->id])->all();
                        $commHtml = "";
                        if (count($comments) > 0) {
                            foreach ($comments as $comment) {
                                $commHtml .= "<br>" . date("d-M-Y H:i", strtotime($comment->created_datetime)) . " - " . Yii::$app->userdata->getUserNameById($comment->user_id) . ' - "' . $comment->message . '"';
                            }
                        }

                        $commentBox = '';

                        if (!empty($commHtml)) {
                            $commentBox = "<br>Comments:<br> " . $commHtml;
                        }

                        // Sending mail starts here
                        $msg = "<html>"
                                . "<head>"
                                . "</head>"
                                . "<body>"
                                . "An update on your service request numbered " . $model->id . " was done.  The latest details of your service request are: <br /><br />"
                                . "Request Status - " . Yii::$app->userdata->getStatusNameById($model->status) . "<br>"
                                . "Request Type - " . Yii::$app->userdata->getRequestTypeName($model->request_type) . "<br>"
                                . "Description - " . ($model->description) . ""
                                . "" . $commentBox
                                . "<br><br> In case of any queries, please reach out to your designated support person " . Yii::$app->userdata->getFullNameById($model->operated_by) . " at " . Yii::$app->userdata->getEmailById($model->operated_by) . " or " . Yii::$app->userdata->getPhoneById($model->operated_by, 7) . ". "
                                . "<br><br>Thanks and Regards,<br />Easyleases Support Team<br />"
                                . "<img src='" . Url::home(true) . "images/property_logo.png' alt='Easyleases Technologies Private Limited' width='150px' height='40px'>"
                                . "</body>"
                                . "</html>";
                        $disMsg = 'Please try after sometime';
                        if (!empty($toEmail)) {
                            Yii::$app->userdata->doMailWithCC(implode(',', $toEmail), implode(',', $ccEmail), '', $subject, $msg, []);
                            $disMsg = "Thanks for your update !!! ";
                        }

                        Yii::$app->getSession()->setFlash(
                                'success', $disMsg
                        );

                        return $this->redirect("requestdetail?rid=" . $rid . "&id=" . $id);
                    } else {
                        $attachModel = \app\models\PropertyServiceRequest::getServiceRequestDetails($rid1);
                        return $this->render('/external/requestdetail', ['model' => $model, 'requestType' => $requestType, 'comment' => $modelComments, 'status' => $requestStatus, 'comments' => $comments, 'attachModel' => $attachModel, 'prop_id' => $id]);
                    }
                } else {
                    $attachModel = \app\models\PropertyServiceRequest::getServiceRequestDetails($rid1);
                    $model->updated_date = date('d-m-Y / H:i', strtotime($model->updated_date));
                    return $this->render('/external/requestdetail', [
                                'model' => $model,
                                'attachModel' => $attachModel,
                                'attachment' => $modelAttach,
                                'requestType' => $requestType,
                                'comment' => $modelComments,
                                'status' => $requestStatus,
                                'comments' => $comments,
                                'prop_id' => $id]);
                }
            } else {
                //echo "hello";die;
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        } else {

            return $this->redirect(['site/login']);
        }
    }

    public function actionRequesttenantdetail($id) {
        $this->layout = 'tenant_dashboard';
        $requestType = \app\models\RequestType::find()->All();
        $requestStatus = \app\models\RequestStatus::find()->All();
        $modelComments = new \app\models\PropertyServiceComment;
        $modelAttach = new ServiceRequestAttachment;
        if ($model = \app\models\PropertyServiceRequest::findOne(['id' => $id])) {

            $propertynamequery = \app\models\Properties::find('property_name')->where(['id' => $model->property_id])->one();
            $property_name = $propertynamequery->property_name;
            //echo $property_name;die;
            $comments = \app\models\PropertyServiceComment::find()->where(['service_request_id' => $id])->orderBy('id desc')->limit(5)->all();
            if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
                Yii::$app->response->format = 'json';
                return \yii\bootstrap\ActiveForm::validate($model);
            } else if ($model->load(Yii::$app->request->post())) {
                if ($model->validate()) {
                    $modelAttach->imageFiles = UploadedFile::getInstances($modelAttach, 'attachment');
                    if (!empty($modelAttach->imageFiles)) {
                        $modelAttach->upload($id);
                    }
                    $model->title = Yii::$app->request->post('PropertyServiceRequest')['title'];
                    $model->description = Yii::$app->request->post('PropertyServiceRequest')['description'];
                    $model->request_type = Yii::$app->request->post('PropertyServiceRequest')['request_type'];
                    $model->status = Yii::$app->request->post('PropertyServiceRequest')['status'];
                    $model->updated_by = Yii::$app->user->id;
                    $model->save();

                    $toEmail = [];
                    $ccEmail = [];

                    if ($model->client_type == 3) {
                        $modelAppProfile = \app\models\ApplicantProfile::find()->where(['applicant_id' => $model->client_id])->one();
                        $toEmail[] = Yii::$app->userdata->getEmailById($modelAppProfile->applicant_id);
                        if (!empty($modelAppProfile->operation_id)) {
                            $ccEmail[] = Yii::$app->userdata->getEmailById($modelAppProfile->operation_id);
                        }

                        if (!empty($modelAppProfile->sales_id)) {
                            $ccEmail[] = Yii::$app->userdata->getEmailById($modelAppProfile->sales_id);
                        }

                        if (!empty($modelAppProfile->branch_code)) {
                            $modelBranch = \app\models\OperationsProfile::find()->where(['role_code' => 'OPBRMG', 'role_value' => $modelAppProfile->branch_code])->one();
                            $ccEmail[] = Yii::$app->userdata->getEmailById($modelBranch->operations_id);
                        }
                    } else if ($model->client_type == 4) {
                        $modelAppProfile = \app\models\OwnerProfile::find()->where(['owner_id' => $model->client_id])->one();
                        $toEmail[] = Yii::$app->userdata->getEmailById($modelAppProfile->owner_id);
                        if (!empty($modelAppProfile->operation_id)) {
                            $ccEmail[] = Yii::$app->userdata->getEmailById($modelAppProfile->operation_id);
                        }

                        if (!empty($modelAppProfile->sales_id)) {
                            $ccEmail[] = Yii::$app->userdata->getEmailById($modelAppProfile->sales_id);
                        }

                        if (!empty($modelAppProfile->branch_code)) {
                            $modelBranch = \app\models\OperationsProfile::find()->where(['role_code' => 'OPBRMG', 'role_value' => $modelAppProfile->branch_code])->one();
                            $ccEmail[] = Yii::$app->userdata->getEmailById($modelBranch->operations_id);
                        }
                    } else {
                        
                    }

                    $ccEmail[] = Yii::$app->params['supportEmail'];
                    $subject = "Update Service Request: # $model->id";

                    $comments = \app\models\PropertyServiceComment::find()->where(['service_request_id' => $model->id])->all();
                    $commHtml = "";
                    if (count($comments) > 0) {
                        foreach ($comments as $comment) {
                            $commHtml .= "<br>" . date("d-M-Y H:i", strtotime($comment->created_datetime)) . " - " . Yii::$app->userdata->getUserNameById($comment->user_id) . ' - "' . $comment->message . '"';
                        }
                    }

                    $commentBox = '';

                    if (!empty($commHtml)) {
                        $commentBox = "<br>Comments:<br> " . $commHtml;
                    }

                    // Sending mail starts here
                    $msg = "<html>"
                            . "<head>"
                            . "</head>"
                            . "<body>"
                            . "An update on your service request numbered " . $model->id . " was done.  The latest details of your service request are: <br /><br />"
                            . "Request Status - " . Yii::$app->userdata->getStatusNameById($model->status) . "<br>"
                            . "Request Type - " . Yii::$app->userdata->getRequestTypeName($model->request_type) . "<br>"
                            . "Description - " . ($model->description) . ""
                            . "" . $commentBox
                            . "<br><br> In case of any queries, please reach out to your designated support person " . Yii::$app->userdata->getFullNameById($model->operated_by) . " at " . Yii::$app->userdata->getEmailById($model->operated_by) . " or " . Yii::$app->userdata->getPhoneById($model->operated_by, 7) . ". "
                            . "<br><br>Thanks and Regards,<br />Easyleases Support Team<br />"
                            . "<img src='" . Url::home(true) . "images/property_logo.png' alt='Easyleases Technologies Private Limited' width='150px' height='40px'>"
                            . "</body>"
                            . "</html>";
                    $disMsg = 'Please try after sometime';
                    if (!empty($toEmail)) {
                        Yii::$app->userdata->doMailWithCC(implode(',', $toEmail), implode(',', $ccEmail), '', $subject, $msg, []);
                        $disMsg = "Thanks for your update !!! ";
                    }

                    Yii::$app->getSession()->setFlash(
                            'success', $disMsg
                    );

                    return $this->redirect("myrequests");
                } else {
                    return $this->render('/site/requesttenantdetail', ['model' => $model, 'requestType' => $requestType, 'comment' => $modelComments, 'status' => $requestStatus, 'comments' => $comments, 'property_name' => $property_name]);
                }
            } else {
                $model->updated_date = date('d-m-Y / H:i', strtotime($model->updated_date));
                $attachModel = \app\models\PropertyServiceRequest::getServiceRequestDetails($id);
                return $this->render('/site/requesttenantdetail', [
                            'model' => $model,
                            'attachModel' => $attachModel,
                            'attachment' => $modelAttach,
                            'requestType' => $requestType,
                            'comment' => $modelComments,
                            'status' => $requestStatus,
                            'comments' => $comments,
                            'property_name' => $property_name
                ]);
            }
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionRequesttenantdetails($id) {
        $this->layout = false;
        $requestType = \app\models\RequestType::find()->All();
        $requestStatus = \app\models\RequestStatus::find()->All();
        $modelComments = new \app\models\PropertyServiceComment;
        $modelAttach = new ServiceRequestAttachment;

        $modelAttach->imageFiles = UploadedFile::getInstances($modelAttach, 'attachment');
        if (!empty($modelAttach->imageFiles)) {
            $modelAttach->upload($id);
            $res = ['status' => 'success'];
        } else {
            $res = ['status' => 'error'];
        }
        echo json_encode($res);
    }

    public function actionRequestdetails($rid) {
        $id = base64_decode($rid);
        $this->layout = false;
        $requestType = \app\models\RequestType::find()->All();
        $requestStatus = \app\models\RequestStatus::find()->All();
        $modelComments = new \app\models\PropertyServiceComment;
        $modelAttach = new ServiceRequestAttachment;

        $modelAttach->imageFiles = UploadedFile::getInstances($modelAttach, 'attachment');
        if (!empty($modelAttach->imageFiles)) {
            $modelAttach->upload($id);
            $res = ['status' => 'success'];
        } else {
            $res = ['status' => 'error'];
        }
        echo json_encode($res);
    }

    public function actionRequesttenantattachremove($id, $rid) {
        $id = base64_decode($id);
        $rid = base64_decode($rid);
        $model = ServiceRequestAttachment::findOne($id);
        $model->delete();
        return $this->redirect(['requesttenantdetail?id=' . $rid . "#attach-box"]);
    }

    public function actionRequestattachremove($iid, $id, $rid) {
        $iid = base64_decode($iid);
        $model = ServiceRequestAttachment::findOne($iid);
        $model->delete();
        return $this->redirect(['requestdetail?id=' . $id . '&rid=' . $rid . "&#attach-box"]);
    }

    public function actionAddservicecomment() {
        $service_request_id = base64_decode(Yii::$app->request->get('rid'));
        $requestComments = new \app\models\PropertyServiceComment;
        $requestComments->service_request_id = $service_request_id;
        $requestComments->user_type = Yii::$app->user->identity->user_type;
        $requestComments->user_id = Yii::$app->user->id;
        $requestComments->message = $_POST['message'];
        $requestComments->save(false);
        $currentDateTime = date('Y-m-d H:i:s');
        $formatedDate = date('d-m-Y / H:i', strtotime($currentDateTime));
        $sql = "UPDATE `service_request` SET updated_date = '" . $currentDateTime . "' WHERE id = " . $service_request_id;
        Yii::$app->db->createCommand($sql)->execute();
        $data = \app\models\PropertyServiceComment::find()->where(['service_request_id' => $service_request_id])->orderBy('id Desc')->limit(5)->all();
        $result = Array();
        foreach ($data as $key => $value) {
            $result[] = Array('service_request_id' => $service_request_id, 'user_id' => $value->user_id, 'message' => $value->message, 'created_date' => date('d/m/Y', strtotime($value->created_datetime)), 'created_time' => date('h:i a', strtotime($value->created_datetime)), 'username' => Yii::$app->userdata->getFullNameById($value->user_id), 'updated_date' => $formatedDate);
        }
        return json_encode($result);
    }

    public function actionAddservicecomment2() {
        $service_request_id = base64_decode(Yii::$app->request->get('id'));
        $requestComments = new \app\models\PropertyServiceComment;
        $requestComments->service_request_id = $service_request_id;
        $requestComments->user_type = Yii::$app->user->identity->user_type;
        $requestComments->user_id = Yii::$app->user->id;
        $requestComments->message = $_POST['message'];
        $requestComments->save(false);
        $currentDateTime = date('Y-m-d H:i:s');
        $formatedDate = date('d-m-Y / H:i', strtotime($currentDateTime));
        $sql = "UPDATE `service_request` SET updated_date = '" . $currentDateTime . "' WHERE id = " . $service_request_id;
        Yii::$app->db->createCommand($sql)->execute();
        $data = \app\models\PropertyServiceComment::find()->where(['service_request_id' => $service_request_id])->orderBy('id Desc')->limit(5)->all();
        $result = Array();
        foreach ($data as $key => $value) {
            $result[] = Array('service_request_id' => $service_request_id, 'user_id' => $value->user_id, 'message' => $value->message, 'created_date' => date('d/m/Y', strtotime($value->created_datetime)), 'created_time' => date('h:i a', strtotime($value->created_datetime)), 'username' => Yii::$app->userdata->getFullNameById($value->user_id), 'updated_date' => $formatedDate);
        }
        return json_encode($result);
    }

    /*     * *********************************************************************** Cron Jobs ***************************************************************** */

    public function actionCheckfavs() {
        $favoritesProperty = \app\models\FavouriteProperties::findAll(['DATEDIFF(NOW(),`created_date`)' => 2, 'status' => 2]);
        if (count($favoritesProperty) != 0) {
            foreach ($favoritesProperty as $key => $value) {
                if ($value->type == 3) {
                    $model = \app\models\Properties::findOne(['id' => $value->property_id]);
                    $model->status = 1;
                    $model->save(false);
                } else {

                    $connection = \Yii::$app->db;
                    $model = $connection->createCommand("UPDATE child_properties SET status='1' where id IN ($value->child_properties)");
                    $model->execute();
                }
                $value->delete();
            }
        }
    }

    public function actionTenantpenaltycalculator() {
        $tenantPayments = \app\models\TenantPayments::find()->where('due_date < "' . Date('Y-m-d') . '"')->all();
        foreach ($tenantPayments as $key => $value) {
            $penalty_percent = \app\models\TenantAgreements::findOne(['tenant_id' => $value->tenant_id, 'property_id' => $value->property_id]);
            $min_penalty = $penalty_percent->min_penalty;
            $penaltypercent = $penalty_percent->late_penalty_percent;
            $amount = (int) $value->original_amount + (int) $value->maintenance;
            $currentDate = date_create(Date('Y-m-d'));
            $dueDate = date_create($value->due_date);
            $diff = date_diff($currentDate, $dueDate);
            $days = $diff->y * 365.25 + $diff->m * 30 + $diff->d + $diff->h / 24 + $diff->i / 60;
            $penalty = Round(((int) ($days) / 365) * ($penaltypercent / 100) * $amount);
            if ($penalty < $min_penalty) {
                $penalty = $min_penalty;
            }
            $value->penalty_amount = $penalty;
            $value->total_amount = (int) $value->total_amount + (int) $penalty;
            $value->save(false);
        }

        $AdhocCharges = \app\models\AdhocRequests::find()->where('payment_due_date < "' . Date('Y-m-d') . '"')->all();
        foreach ($AdhocCharges as $key => $value) {
            $penalty_percent = \app\models\TenantAgreements::findOne(['tenant_id' => $value->tenant_id]);
            $min_penalty = $penalty_percent->min_penalty;
            $penaltypercent = $penalty_percent->late_penalty_percent;
            $amount = (int) $value->charge_to_tenant;
            $currentDate = date_create(Date('Y-m-d'));
            $dueDate = date_create($value->payment_due_date);
            $diff = date_diff($currentDate, $dueDate);
            $days = $diff->y * 365.25 + $diff->m * 30 + $diff->d + $diff->h / 24 + $diff->i / 60;
            $penalty = Round(((int) ($days) / 365) * ($penaltypercent / 100) * $amount);
            if ($penalty < $min_penalty) {
                $penalty = $min_penalty;
            }
            $value->penalty_amount = $penalty;
            $value->charge_to_tenant = (int) $value->charge_to_tenant + (int) $penalty;
            $value->save(false);
        }
    }

    public function actionOwnerpenaltycalculate($id) {
        $agreements = \app\models\PropertyAgreements::findOne(['property_id' => $id]);
        $ownerPayments = \app\models\OwnerPayments::find()->select('distinct `property_id`')->where(['parent_id' => $id])->all();

        $agreementNoticeStatus = $agreements->notice_status;
        $minstay = (int) $agreements->min_contract_peroid;

        $currentDate = Date('Y-m-d');
        $currentDateObject = date_create($currentDate);

        $noticeStartDate = $agreements->notice_start_date;
        $noticeStartDateObject = date_create($noticeStartDate);

        $rentProperty = $agreements->rent;
        $maintenanceProperty = $agreements->manteinance;

        $minStayDate = Date('Y-m-d', strtotime($agreements->contract_start_date . " +" . $minstay . " month"));
        $minStayDateObject = date_create($minStayDate);


        $servedPeriod = date_diff($currentDateObject, $minStayDateObject);

        $leftDays = $servedPeriod->days + 1;


        $total = 0;
        $totalNotice = 0;
        foreach ($ownerPayments as $key => $value) {
            $ownerPaymentTemp = \app\models\OwnerPayments::find()->where(['parent_id' => $id])->andWhere(['property_id' => $value])->orderBy('id desc')->offset(0)->limit(1)->one();

            $month = Date('m', strtotime('01-' . $ownerPaymentTemp->month));
            $year = Date('Y', strtotime('01-' . $ownerPaymentTemp->month));
            $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $perdayComission = $ownerPaymentTemp->pms_commission / $days;
            $pms_profit_total = $perdayComission * $leftDays;
            $total += $pms_profit_total;

            switch ($agreementNoticeStatus) {
                case 0:
                    /*                     * ****************************** Notice Period Not Served ***************************** */
                    $totalNotice1 = $ownerPaymentTemp->pms_commission * $agreements->notice_peroid;
                    /*                     * *************************************** End ***************************************** */
                    break;

                case 1:
                    /*                     * ****************************** Notice Period Initiated ***************************** */
                    $tempDateDiff = date_diff($currentDateObject, $noticeStartDateObject);
                    $tempDaysCalculate = $tempDateDiff->days + 1;
                    $totalNotice1 = $perdayComission * $tempDaysCalculate;
                    /*                     * *************************************** End ***************************************** */
                    break;

                case 2:
                    $totalNotice1 = -1 * $ownerPaymentTemp->pms_commission * $agreements->notice_peroid;
                    break;
                case 3:
                    $totalNotice1 = -1 * $ownerPaymentTemp->pms_commission * $agreements->notice_peroid;
                    break;
            }
            $totalNotice += $totalNotice1;
        }

        $total = ceil($total);
        if ($total != 0) {
            $total = $total + $totalNotice;
        }
        if ($agreements->penalty_calculate_id != 0) {
            $Owner = \app\models\OwnerPayments::findOne(['id' => $agreements->penalty_calculate_id]);
            $Owner->payment_amount = -1 * $total;
            $Owner->save(false);
        } else {
            $Owner = new \app\models\OwnerPayments();
            $Owner->owner_id = $agreements->owner_id;
            $Owner->property_id = $agreements->property_id;
            $Owner->payment_amount = -1 * $total;
            $Owner->service_tax = 0;
            $Owner->tds = 0;
            $Owner->gst = 0;
            $Owner->payment_des = 'Early Exit Penalty';
            $Owner->save(false);
            $agreements->penalty_calculate_id = $Owner->id;
            $agreements->save(false);
        }
        die;
    }

    public function actionTenantpenaltycalculate($id) {
        $agreements = \app\models\TenantAgreements::findOne(['id' => $id]);

        $currentDate = Date('Y-m-d');
        $currentDateObject = date_create($currentDate);

        $noticeStartDate = $agreements->notice_start_date;
        $noticeStartDateObject = date_create($noticeStartDate);


        /*         * *********************************************** calculate Monthly values For Rent ********** */
        $PropertyAgreements = \app\models\PropertyAgreements::findOne(['property_id' => $agreements->parent_id]);
        $minstay = (int) $agreements->min_stay;
        if ($PropertyAgreements->agreement_type == '2') {
            $rentProperty = $propertyAgreements->rent;
            $maintenanceProperty = $propertyAgreements->manteinance;
        } else {
            $rentProperty = $agreements->rent;
            $maintenanceProperty = $agreements->maintainance;
        }

        $rentOriginal = $agreements->rent;
        $maintenanceOriginal = $agreements->maintainance;
        /*         * ************************************************************ End ****************************** */


        $minstay = (int) $agreements->min_stay;
        $noticePeriod = (int) $agreements->notice_period;

        /*         * ******************* Exit Date Value(Change It For Some Specific Date)************************* */
        $exitDate = Date('Y-m-d');
        $exitDateObject = date_create($exitDate);
        /*         * ************************************** End **************************************** */

        /*         * ******************************* Exit Date Due ************************************* */
        $exitDateDueMonth = Date('m', strtotime($exitDate));
        $exitDateDueYear = Date('Y', strtotime($exitDate));
        $exitDateDueDay = cal_days_in_month(CAL_GREGORIAN, $exitDateDueMonth, $exitDateDueYear);
        $exitDueDate = $exitDateDueYear . '-' . $exitDateDueMonth . '-' . $exitDateDueDay;
        $exitDueDate1 = Date('Y-m-d', strtotime($exitDueDate . " +1 month"));
        /*         * ************************************ End ****************************************** */


        /*         * ******************************** Min Stay Date Value *************************** */
        $minStayDate = Date('Y-m-d', strtotime($agreements->lease_start_date . " +" . $minstay . " month"));
        $minStayDateObject = date_create($minStayDate);

        $minDateDueMonth = Date('m', strtotime($minStayDate));
        $minDateDueYear = Date('Y', strtotime($minStayDate));
        $minDateDueDay = cal_days_in_month(CAL_GREGORIAN, $minDateDueMonth, $minDateDueYear);
        $minDueDate = $minDateDueYear . '-' . $minDateDueMonth . '-' . $minDateDueDay;
        /*         * ************************************** End ************************************* */

        $tenantPayments = \app\models\TenantPayments::find()->where(['agreement_id' => $id])->andWhere('due_date > "' . $exitDueDate . '"')->andWhere('due_date < "' . $minDueDate . '"')->all();

        $totalPaymentsRecord = count($tenantPayments);

        $lastChild = $tenantPayments[$totalPaymentsRecord - 1];

        $lastDueDate = $lastChild->due_date;
        $monthLastDueDate = date('m', strtotime($lastDueDate));
        $yearLastDueDate = date('Y', strtotime($lastDueDate));
        $dayLastDueDate = cal_days_in_month(CAL_GREGORIAN, $monthLastDueDate, $yearLastDueDate);
        $dayOfMinStayDay = (int) date('d', strtotime($minStayDate));

        /*         * ************************************* Last Payment Date Calculate ******************* */
        $lastid = $totalPaymentsRecord - 1;
        $lastTenantPayments = $tenantPayments[$totalPaymentsRecord - 1];
        $lastTenantPaymentDueDate = $lastTenantPayments->due_date;

        /*         * ************************************************ End ********************************* */


        /*         * ************************** Calculation Of Penalty For Early Exit ******************** */

        $Calculate['original'][] = ($dayOfMinStayDay / $dayLastDueDate) * $lastChild->original_amount;
        $Calculate['owner'][] = ($dayOfMinStayDay / $dayLastDueDate) * $lastChild->owner_amount;
        $Calculate['maintenance'][] = ($dayOfMinStayDay / $dayLastDueDate) * $lastChild->maintenance;

        for ($i = 0; $i < $lastid; $i++) {
            $Calculate['original'][] = $tenantPayments[$i]->original_amount;
            $Calculate['owner'][] = $tenantPayments[$i]->owner_amount;
            $Calculate['maintenance'][] = $tenantPayments[$i]->maintenance;
        }

        $totalOriginal = 0;
        $totalOwner = 0;
        $totalMaintenance = 0;

        for ($i = 0; $i < count($Calculate['original']); $i++) {
            $totalOriginal += $Calculate['original'][$i];
            $totalOwner += $Calculate['owner'][$i];
            $totalMaintenance += $Calculate['maintenance'][$i];
        }
        /*         * **************************************** End **************************************** */

        /*         * **************************************** Calculation Of Notice Penalty ************* */



        $agreementNoticeStatus = $agreements->notice_status;
        $totalNoticeOriginal = 0;
        $totalNoticeMaintenance = 0;
        $totalNoticeOwner = 0;
        $totalNoticeTotal = 0;

        switch ($agreementNoticeStatus) {
            case 0:
                /*                 * ****************************** Notice Period Not Served ***************************** */
                for ($i = 1; $i <= $noticePeriod; $i++) {
                    $totalNoticeOriginal += $rentOriginal;
                    $totalNoticeMaintenance += $maintenanceProperty;
                    $totalNoticeOwner += $rentProperty;
                    $totalNoticeTotal += $rentOriginal + $maintenanceProperty;
                }
                /*                 * *************************************** End ***************************************** */
                break;

            case 1:
                /*                 * ****************************** Notice Period Initiated ***************************** */
                $tempDateDiff = date_diff($currentDateObject, $noticeStartDateObject);
                $tempDaysCalculate = $tempDateDiff->days;
                // $total_days=0;
                for ($i = 1; $i <= $noticePeriod; $i++) {
                    $tempDate = Date('Y-m-d', strtotime($lastTenantPaymentDueDate . " +" . $i . " month"));
                    $monthTempDate = Date('m', strtotime($tempDate));
                    $yearTempDate = Date('Y', strtotime($tempDate));
                    $dayTempDate = cal_days_in_month(CAL_GREGORIAN, $monthTempDate, $yearTempDate);
                    // $total_days+=$dayTempDate;
                    if ($tempDaysCalculate != 0) {
                        if ($tempDaysCalculate > $dayTempDate) {
                            $totalNoticeOriginal += $rentOriginal;
                            $totalNoticeMaintenance += $maintenanceProperty;
                            $totalNoticeOwner += $rentProperty;
                            $totalNoticeTotal += $rentOriginal + $maintenanceProperty;

                            $tempDaysCalculate -= $dayTempDate;
                        } else {
                            $totalNoticeOriginal += (($tempDaysCalculate / $dayTempDate) * $rentOriginal);
                            $totalNoticeMaintenance += (($tempDaysCalculate / $dayTempDate) * $maintenanceProperty);
                            $totalNoticeOwner += (($tempDaysCalculate / $dayTempDate) * $rentProperty);
                            $totalNoticeTotal += (($tempDaysCalculate / $dayTempDate) * $rentOriginal + $maintenanceProperty);

                            $tempDaysCalculate = 0;
                        }
                    }
                }
                /*                 * *************************************** End ***************************************** */
                break;

            case 2:
                if ($totalOriginal != 0 && $totalMaintenance != 0 && $totalOwner != 0) {
                    for ($i = 1; $i <= $noticePeriod; $i++) {
                        $totalNoticeOriginal -= $rentOriginal;
                        $totalNoticeMaintenance -= $maintenanceProperty;
                        $totalNoticeOwner -= $rentProperty;
                        $totalNoticeTotal -= $rentOriginal + $maintenanceProperty;
                    }
                }
                break;
            case 3:
                if ($totalOriginal != 0 && $totalMaintenance != 0 && $totalOwner != 0) {
                    for ($i = 1; $i <= $noticePeriod; $i++) {
                        $totalNoticeOriginal -= $rentOriginal;
                        $totalNoticeMaintenance -= $maintenanceProperty;
                        $totalNoticeOwner -= $rentProperty;
                        $totalNoticeTotal -= $rentOriginal + $maintenanceProperty;
                    }
                }
                break;
        }


        echo "Notice";
        echo "<br>";
        echo $totalNoticeOriginal;
        echo "<br>";
        echo $totalNoticeMaintenance;
        echo "<br>";
        echo $totalNoticeOwner;
        echo "<br>";
        echo $totalNoticeTotal;
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";


        echo "Penalty";
        echo "<br>";
        echo $totalOriginal;
        echo "<br>";
        echo $totalMaintenance;
        echo "<br>";
        echo $totalOwner;
        echo "<br>";
        echo $totalOriginal + $totalMaintenance;
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";


        echo "total";
        echo "<br>";
        echo $totalNoticeOriginal + $totalOriginal;
        echo "<br>";
        echo $totalNoticeMaintenance + $totalMaintenance;
        echo "<br>";
        echo $totalNoticeOwner + $totalOwner;
        echo "<br>";
        echo $totalNoticeTotal + $totalOriginal + $totalMaintenance;
        echo "<br>";
    }

    public function actionDeactivatetenants() {
        $date = Date('Y-m-d');
        $tenantAgreements = \app\models\TenantAgreements::findAll(['lease_end_date' => $date]);
        if (count($tenantAgreements) != 0) {
            $tenantListArray = Array();
            foreach ($tenantAgreements as $key => $value) {
                $tenantListArray[] = $value->tenant_id;
            }
            $tenantList = implode("','", $tenantListArray);
            $tenantRecords = \app\models\Users::findAll("id IN ('" . $tenantList . "')");
            foreach ($tenantRecords as $key1 => $tenant) {
                $tenant->status = '0';
                $tenant->save(false);
            }
        }
    }

    public function actionDeactivateadvisercron() {
        $date = Date('Y-m-d');
        $advisorAgreements = \app\models\AdvisorAgreements::findAll(['end_date' => $date]);
        if (count($advisorAgreements) != 0) {
            $advisorListArray = Array();
            foreach ($advisorAgreements as $key => $value) {
                $advisorListArray[] = $value->advisor_id;
            }
            $advisorList = implode("','", $advisorListArray);
            $advisorRecords = \app\models\Users::findAll("id IN ('" . $advisorList . "')");
            foreach ($advisorRecords as $key1 => $advisor) {
                $advisor->status = '0';
                $advisor->save(false);
            }

            \app\models\LeadsTenant::model()->updateAll("reffered_by IN ('" . $advisorList . "')");
            \app\models\LeadsOwner::model()->updateAll("reffered_by IN ('" . $advisorList . "')");
        }
    }

    public function actionAdvisorpaymentcalculate() {
        $currentDate = date('m-Y');
        $internalUserArray = Array();
        $internalUser = \app\models\Users::find()->where("user_type IN ('6','7')")->all();
        foreach ($internalUser as $key => $value) {
            $internalUserArray[] = $value->id;
        }

        $internalUsers = implode(",", $internalUserArray);

        $ownerAdvisorMap = Array(); // owner id as Key and advisor Id as value
        $ownerArray = Array();

        /*         * ************************************ Owner calculation *********************************** */
        $leadsOwner = \app\models\LeadsOwner::find()->where('reffered_by IS NOT NULL and reffered_by!="" and reffered_by!=0 and reffered_by NOT IN("' . $internalUsers . '")')->all();
        foreach ($leadsOwner as $key => $value) {
            $ownerAdvisorMap[Yii::$app->userdata->getUserIdByEmail($value->email_id)] = $value->reffered_by;
            $ownerArray[] = Yii::$app->userdata->getUserIdByEmail($value->email_id);
        }
        $AdvisorPaymentConfigration = \app\models\AdvisorsDefaultPaymentConfig::findAll(['config_type' => 1]);
        $configrationArray = Array();
        $configrationArray1 = Array();
        foreach ($AdvisorPaymentConfigration as $key => $value) {
            for ($i = $value->min; $i <= $value->max; $i++) {
                $configrationArray[$i] = $value->advisor_fees;
                $configrationArray1[$i] = $value->id;
            }
        }

        if (count($ownerArray) != 0) {
            $ownerList = implode(",", $ownerArray);
            $ownerProperties = \app\models\OwnerPayments::find()->select('distinct `parent_id`')->where(['month' => $currentDate])->andWhere(['advisor_calculation' => '0'])->andWhere("owner_id IN (" . $ownerList . ")")->all();
            $total_list = count($ownerProperties);
            foreach ($ownerProperties as $key => $value) {
                $numberKey = $key + 1;
                $intrest = $configrationArray[$numberKey];
                $ownerPayments = \app\models\OwnerPayments::find()->where(['month' => $currentDate])->andWhere(['advisor_calculation' => '0'])->andWhere(["parent_id" => $value->parent_id])->all();
                $tempPmsCommission = 0;
                $payment_id = "";
                foreach ($ownerPayments as $key1 => $Payment) {
                    $tempPmsCommission += $Payment->pms_commission;
                    if ($payment_id == '') {
                        $payment_id = $Payment->id;
                    } else {
                        $payment_id = $payment_id + "," + $Payment->id;
                    }
                }
                $advisorCommission = ($intrest / 100) * $tempPmsCommission;
                $tds = 0.1 * $advisorCommission;
                $advisorPaymentsModel = new \app\models\AdvisorPayments();
                $advisorPaymentsModel->advisor_id = $ownerAdvisorMap[$ownerPayments[0]->owner_id];
                $advisorPaymentsModel->source_id = $value->parent_id;
                $advisorPaymentsModel->source_type = 1;
                $advisorPaymentsModel->comission_amount = $advisorCommission;
                $advisorPaymentsModel->tds = $tds;
                $advisorPaymentsModel->slab = $configrationArray1[$numberKey];
                $advisorPaymentsModel->pms_comission = $tempPmsCommission;
                $advisorPaymentsModel->applicable_fees = $intrest;
                $advisorPaymentsModel->payable_amount = $advisorCommission - $tds;
                $advisorPaymentsModel->month = $currentDate;
                $advisorPaymentsModel->payment_id = "," . $payment_id . ",";
                $advisorPaymentsModel->save(false);
                foreach ($ownerPayments as $key2 => $value1) {
                    $value1->advisor_calculation = '1';
                    $value1->save(false);
                }
            }
        }



        /*         * **************************************** End ********************************************* */

        /*         * ************************************** Tenant Payment Calculation ************************ */
        $leadsTenant = \app\models\Users::find()->where('refered_by IS NOT NULL and refered_by!="" and refered_by!=0 and refered_by NOT IN("' . $internalUsers . '")')->all();
        $tenantAdvisorMap = Array(); // owner id as Key and advisor Id as value
        $tenantArray = Array();
        foreach ($leadsTenant as $key => $value) {
            $tenantAdvisorMap[$value->id] = $value->refered_by;
            $tenantArray[] = $value->id;
        }
        $tenantList = implode(",", $tenantArray);
        $TenantPaymentConfigration = \app\models\AdvisorsDefaultPaymentConfig::findAll(['config_type' => 2]);
        $ConfigrationArray = Array();
        $ConfigrationArray1 = Array();
        foreach ($TenantPaymentConfigration as $key => $value) {
            for ($i = $value->min; $i <= $value->max; $i++) {
                $ConfigrationArray[$i] = $value->advisor_fees;
                $ConfigrationArray1[$i] = $value->id;
            }
        }


        $tenantList = \app\models\TenantPayments::find()->select('distinct `tenant_id`')->where(['month' => $currentDate])->andWhere(['advisor_calculation' => '0'])->andWhere("tenant_id IN (" . $tenantList . ")")->all();
        foreach ($tenantList as $key2 => $value2) {
            $tenantProperties = \app\models\TenantPayments::find()->where(['month' => $currentDate])->andWhere(['advisor_calculation' => '0'])->andWhere(["tenant_id" => $value2->tenant_id])->all();
            $numberKey2 = $key2 + 1;
            $Intrest = $ConfigrationArray[$numberKey2];
            $slab = $ConfigrationArray1[$numberKey2];

            $tempPmsCommission1 = 0;
            $payment_id = "";
            foreach ($tenantProperties as $key => $value) {
                $comissionTotalObject = Yii::$app->userdata->getPmsCommission($value->id);
                // print_r($comissionTotalObject);
                // die;
                $comissionTotal = $comissionTotalObject->pms_commission;
                $paymentId = Yii::$app->userdata->getListToArray($comissionTotalObject->payment_id);
                if (count($paymentId) == 1) {
                    $tempPmsCommission1 += $comissionTotal;
                } else {
                    $tempPmsCommission1 += (Yii::$app->userdata->getPerTenantComission($comissionTotalObject->payment_id, $value2->tenant_id) * $comissionTotal);
                }
                if ($payment_id == '') {
                    $payment_id = implode(",", $paymentId);
                } else {
                    $payment_id = $payment_id + "," + implode(",", $paymentId);
                }
            }
            $advisorCommission1 = ($Intrest / 100) * $tempPmsCommission1;
            $tds1 = 0.1 * $advisorCommission1;
            $advisorPaymentsModel1 = new \app\models\AdvisorPayments();
            $advisorPaymentsModel1->advisor_id = $tenantAdvisorMap[$value2->tenant_id];
            $advisorPaymentsModel1->source_id = $value2->tenant_id;
            $advisorPaymentsModel1->source_type = 2;
            $advisorPaymentsModel1->comission_amount = $advisorCommission1;
            $advisorPaymentsModel1->tds = $tds1;
            $advisorPaymentsModel1->payable_amount = $advisorCommission1 - $tds1;
            $advisorPaymentsModel1->slab = $slab;
            $advisorPaymentsModel1->pms_comission = $tempPmsCommission1;
            $advisorPaymentsModel1->applicable_fees = $Intrest;
            $advisorPaymentsModel1->month = $currentDate;
            $advisorPaymentsModel1->payment_id = "," . $payment_id . ",";
            $advisorPaymentsModel1->save(false);
            foreach ($tenantProperties as $key5 => $value5) {
                $value5->advisor_calculation = '1';
                $value5->save(false);
            }
        }

        /*         * ******************************************** End ***************************************** */
    }

    public function actionMailintimations() {
        $date5 = Date('Y-m-d', strtotime('+5 days'));
        $date3 = Date('Y-m-d', strtotime('+3 days'));
        $date1 = Date('Y-m-d', strtotime('+1 days'));
        $date = Date('Y-m-d');

        $recordsdate5 = \app\models\TenantPayments::findAll(['due_date' => $date5, 'payment_status' => '0']);
        $recordsdate3 = \app\models\TenantPayments::findAll(['due_date' => $date3, 'payment_status' => '0']);
        $recordsdate1 = \app\models\TenantPayments::findAll(['due_date' => $date1, 'payment_status' => '0']);
        $recordsdate = \app\models\TenantPayments::findAll(['due_date' => $date, 'payment_status' => '0']);
        $recordsdatepast = \app\models\TenantPayments::find()->where('due_date < "' . $date . '"')->andWhere(['payment_status' => '0'])->all();

        if (count($recordsdate5) != 0) {
            foreach ($recordsdate5 as $key => $value) {
                echo $value->id . "<br/>";
                $tenant_id = Yii::$app->userdata->getLoginIdById($value->tenant_id);
                $tenantname = Yii::$app->userdata->getFullNameById($value->tenant_id);
                $subject = 'Payment Due in next 5 days';
                $msg = "Hello $tenantname<br/><br/>Please note that a payment of $value->total_amount is due on $value->due_date. Please process the payment at the earliest.<br/><br/>Please ignore this message if you have already made the payment.<br/><br/>With Regards,<br/>EasyLeases Team<br/><img src='" . Url::home(true) . "images/property_logo.png' alt=''>";
                Yii::$app->userdata->doMail($tenant_id, $subject, $msg);
            }
        }
        if (count($recordsdate3) != 0) {
            foreach ($recordsdate3 as $key => $value) {
                echo $value->id . "<br/>";
                $tenant_id = Yii::$app->userdata->getLoginIdById($value->tenant_id);
                $tenantname = Yii::$app->userdata->getFullNameById($value->tenant_id);
                $subject = 'Payment Due in next 3 days';
                $msg = "Hello $tenantname<br/><br/>Please note that a payment of $value->total_amount is due on $value->due_date. Please process the payment at the earliest.<br/><br/>Please ignore this message if you have already made the payment.<br/><br/>With Regards,<br/>EasyLeases Team<br/><img src='" . Url::home(true) . "images/property_logo.png' alt=''>";
                Yii::$app->userdata->doMail($tenant_id, $subject, $msg);
            }
        }
        if (count($recordsdate1) != 0) {
            foreach ($recordsdate1 as $key => $value) {
                echo $value->id . "<br/>";
                $tenant_id = Yii::$app->userdata->getLoginIdById($value->tenant_id);
                $tenantname = Yii::$app->userdata->getFullNameById($value->tenant_id);
                $subject = 'Payment Due in next 1 day';
                $msg = "Hello $tenantname<br/><br/>Please note that a payment of $value->total_amount is due on $value->due_date. Please process the payment at the earliest.<br/><br/>Please ignore this message if you have already made the payment.<br/><br/>With Regards,<br/>EasyLeases Team<br/><img src='" . Url::home(true) . "images/property_logo.png' alt=''>";
                Yii::$app->userdata->doMail($tenant_id, $subject, $msg);
            }
        }
        if (count($recordsdate) != 0) {
            foreach ($recordsdate as $key => $value) {
                echo $value->id . "<br/>";
                $tenant_id = Yii::$app->userdata->getLoginIdById($value->tenant_id);
                $tenantname = Yii::$app->userdata->getFullNameById($value->tenant_id);
                $subject = 'Urgent Attention: Payment Due Today';
                // $msg="Hello $tenantname<br/><br/>Please note that a payment of $value->total_amount is due on $value->due_date. Please process the payment at the earliest.<br/><br/>Please ignore this message if you have already made the payment.<br/><br/>With Regards,<br/>EasyLeases Team";
                $msg = "Hello $tenantname<br/><br/>Please note that a payment of $value->total_amount is due today. Please process the payment today.<br/><br/>Note that a penalty of Rs 200 or 24% per annum on rent (whichever is higher) is applicable from tomorrow onwards. Pay today to avoid the penalty.<br/><br/>Please ignore this message if you have already made the payment.<br/><br/>With Regards,<br/>EasyLeases Team<br/><img src='" . Url::home(true) . "images/property_logo.png' alt=''>";
                Yii::$app->userdata->doMail($tenant_id, $subject, $msg);
            }
        }

        if (count($recordsdatepast) != 0) {
            foreach ($recordsdatepast as $key => $value) {
                echo $value->id . "<br/>";
                $tenant_id = Yii::$app->userdata->getLoginIdById($value->tenant_id);
                $tenantname = Yii::$app->userdata->getFullNameById($value->tenant_id);
                $subject = 'Payment Overdue';
                // $msg="Hello $tenantname<br/><br/>Please note that a payment of $value->total_amount is due on $value->due_date. Please process the payment at the earliest.<br/><br/>Please ignore this message if you have already made the payment.<br/><br/>With Regards,<br/>EasyLeases Team";
                $msg = "Hello $tenantname<br/><br/>Please note that a payment of $value->total_amount is overdue. Please process the payment at the earliest.<br/><br/>Note that a penalty of Rs 200 or 24% per annum on rent (whichever is higher) is applicable now. Pay today to avoid the penalty.<br/><br/>Please ignore this message if you have already made the payment.<br/><br/>With Regards,<br/>EasyLeases Team<br/><img src='" . Url::home(true) . "images/property_logo.png' alt=''>";
                Yii::$app->userdata->doMail($tenant_id, $subject, $msg);
            }
        }
    }

    /*     * ******************************************************************** End Cron Jobs ************************************************************************* */

    public function actionCheck() {
        $walletHistory = \app\models\WalletsHistory::find()->all();
        foreach ($walletHistory as $key => $value) {
            $user_id = Yii::$app->userdata->getUserIdByEmail($value->email_id);
            if (trim($user_id) == '') {
                $value->delete(false);
            } else {
                $value->user_id = $user_id;
                $value->save(false);
            }
        }
    }

    public function actionRemovecheck($id) {
        $userType = \app\models\Users::find()->select('user_type')->where(['id' => $id])->one();
        $userType = $userType['user_type'];
        if ($userType == '2') {
            $ownerProfile = \app\models\ApplicantProfile::findOne(['applicant_id' => $id]);
            $ownerProfile->cancelled_check = '';
            $ownerProfile->save(false);
            return true;
        } elseif ($userType == '3') {
            $ownerProfile = \app\models\TenantProfile::findOne(['tenant_id' => $id]);
            $ownerProfile->cancelled_check = '';
            $ownerProfile->save(false);
            return true;
        } elseif ($userType == '4') {
            $ownerProfile = \app\models\OwnerProfile::findOne(['owner_id' => $id]);
            $ownerProfile->cancelled_check = '';
            $ownerProfile->save(false);
            return true;
        }
    }

    public function actionPage($slug) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            echo "<pre>";
            print_r($_POST);
            echo "</pre>";
            die;
        }
        $this->layout = 'home';
        $page = \app\models\Pages::find()->where(['slug' => $slug])->One();
        return $this->render('page', ['data' => $page]);
    }

    public function actionRefund_amount_bank() {
        $connection = \Yii::$app->db;
        $model = $connection->createCommand("UPDATE wallets_history SET transaction_type='0' where user_id=" . \Yii::$app->user->id . " and `transaction_type` IN ('1','2')");
        $model->execute();
        echo true;
    }

    public function actionCheckifsc() {
        $ifsc = Yii::$app->request->post('ifsc');
        $ifsc = Yii::$app->userdata->getIFSCValidate($ifsc);
        echo $ifsc;
        exit;
    }

    public function actionFilterpopup1() {

        $this->layout = 'search';

        $excluded_properties = Yii::$app->userdata->getExcludedProperties();


        /*         * ******************************************* start From Here Only *********************************** */

        $sort_flat = Yii::$app->request->get('sort_flat');
        $lat = Yii::$app->request->get('lat');
        $lon = Yii::$app->request->get('lon');
        $radius = 5;
        $property_type = Yii::$app->request->get('property_type');
        $bhk = Yii::$app->request->get('bhk');
        $min_amount = Yii::$app->request->get('min_amount');
        $max_amount = Yii::$app->request->get('max_amount');
        $min_area = Yii::$app->request->get('min_area');
        $max_area = Yii::$app->request->get('max_area');
        $area = Yii::$app->request->get('area');
        $min_radius = Yii::$app->request->get('min_radius');
        $max_radius = Yii::$app->request->get('max_radius');

        if (Yii::$app->request->get('property_facilities')) {
            $property_facilities1 = Yii::$app->request->get('property_facilities');
        } else {
            $property_facilities1 = Array();
        }

        if (Yii::$app->request->get('complex_facilities')) {
            $complex_facilities = Yii::$app->request->get('complex_facilities');
        } else {
            $complex_facilities = Array();
        }

        $flat_type = Yii::$app->request->get('flat_type');

        $property_id = Yii::$app->request->get('id');
        $property_facilities = array_merge($property_facilities1, $complex_facilities);
        if (isset($property_facilities) && is_array($property_facilities)) {
            $property_facilities = array_filter($property_facilities);
        }
        $searcharea = Yii::$app->request->get('search');
        $orderby = '';
        $origLat = $lat;
        $origLon = $lon;
        $dist = $radius;
        $property_typesql = '';
        $property_bhk = '';
        $property_flat_type = '';
        $property_id_filter = '';
        $amountQuery = '';
        $areaQuery = '';
        $propertiesFac = '';
        $slectData = '';
        $property_bhkJoin = '';
        $cityQuery = '';
        $latlonQuery = '';
        $latlonQuerySelect = '';
        $properQuery = '';
        $having = '';
        $bookingSql = '';
        $dataPropId = [];
        $dataProp = '';
        $property_amountJoin = '';
        $slectAmout = 'property_listing.*,';
        if ($origLat != '' && $origLon != '') {


            $latlonQuery = " 3956 * 2 *
        ASIN(SQRT( POWER(SIN(($origLat - lat)*pi()/180/2),2)
        +COS($origLat*pi()/180 )*COS(lat*pi()/180)
        *POWER(SIN(($origLon-lon)*pi()/180/2),2)))
        as distance,";

            if ($max_radius != '' && $min_radius != '') {
                $having = " having distance between " . $min_radius . " AND " . $max_radius;
            } else {
                $having = " having distance < $dist  ";
            }
        } else {
            $city = Yii::$app->request->get('city');
            if ($city != '') {
                $cityQuery = 'AND (properties.city ="' . $city . '") ';
            }
        }

        $sortQuery = '';
        if ($sort_flat == 'name') {
            $sortQuery = 'order by  properties.property_name ASC';
        } else if ($sort_flat == 'rent_low') {
            $sortQuery = 'order by  property_listing.rent ASC';
        } else if ($sort_flat == 'rent_high') {
            $sortQuery = 'order by  property_listing.rent DESC';
        } else if ($sort_flat == 'area_low') {

            $sortQuery = 'order by  properties.flat_area ASC';
        } else if ($sort_flat == 'area_high') {

            $sortQuery = 'order by  properties.flat_area DESC';
        } else if ($sort_flat == 'bhk_asc') {

            $sortQuery = 'order by  properties.flat_bhk ASC';
        } else if ($sort_flat == 'bhk_desc') {

            $sortQuery = 'order by  properties.flat_bhk DESC';
        }

        if (!empty($property_facilities)) {

            $property_facilities = implode(',', $property_facilities);
            $property_facilities = trim($property_facilities, ',');
            $slectData = ' property_attribute_map.*, ';
            $propertiesFac = ' AND property_attribute_map.attr_id IN (' . $property_facilities . ') ';
            $property_bhkJoin = 'LEFT JOIN property_attribute_map ON properties.id = property_attribute_map.property_id';
        }

        if (!empty($area)) {

            $areaQuery = ' AND  properties.flat_area > 5000 ';
        } else if (!empty($min_area) && !empty($max_area)) {

            $areaQuery = ' AND (properties.flat_area between ' . $min_area . ' AND ' . $max_area . ')  ';
        }

        $slectAmout = '';
        if (!empty($min_amount) && !empty($max_amount)) {
            $slectAmout = 'property_listing.*,';
            $amountQuery = ' AND (property_listing.rent between ' . $min_amount . ' AND ' . $max_amount . ')  ';
            $property_amountJoin = 'LEFT JOIN property_listing ON properties.id = property_listing.property_id';
        }

        if (is_array($property_type)) {
            $property_type = implode(',', $property_type);
            $property_type = trim($property_type, ',');

            $property_typesql = ' AND properties.property_type IN (' . $property_type . ')';
        } elseif ($property_type != '') {
            $property_typesql = ' AND  properties.property_type =' . $property_type;
        }

        if (is_array($bhk)) {
            $bhk = implode(',', $bhk);
            $bhk = trim($bhk, ',');

            $property_bhk = ' AND properties.flat_bhk IN (' . $bhk . ') ';
        }

        if ($flat_type) {
            $property_flat_type = ' AND properties.flat_type = ' . $flat_type;
        }

        /* if (count($excluded_properties) != 0) {
          $property_id_filter = 'AND properties.id NOT IN (' . implode(",", $excluded_properties) . ')';
          } */

        $adjacents = 3;

        $limit = 30;         //how many items to show per page
        $page = isset($_GET['page']) ? $_GET['page'] : '';
        if ($page)
            $start = ($page - 1) * $limit;    //first item to display on this page
        else
            $start = 0;        //if no page var is given, set start to 0

        $modelcount = Properties::findBySql("SELECT $slectAmout $slectData $latlonQuery  properties.* FROM property_listing LEFT JOIN properties ON property_listing.property_id=properties.id
                                $property_bhkJoin
                                 WHERE property_listing.status='1'  $cityQuery  $latlonQuerySelect
                                 $property_typesql $property_bhk $property_flat_type $amountQuery $areaQuery $propertiesFac $properQuery GROUP BY properties.id  $having")->all();
        $total_pages = count($modelcount);

        if ($latlonQuery != '') {
            if ($total_pages == 0) {

                $modelcount = Properties::findBySql("SELECT $slectAmout $slectData $latlonQuery  properties.* FROM property_listing LEFT JOIN properties ON property_listing.property_id=properties.id
                                $property_bhkJoin
                                 WHERE property_listing.status='1'  $cityQuery  
                                 $property_typesql $property_bhk $property_flat_type $amountQuery $areaQuery $propertiesFac $properQuery GROUP BY properties.id  $having")->all();
                $total_pages = count($modelcount);
            }
        }

        $total_pages1 = $total_pages;
        if ($total_pages == '0') {
            $modelcount = Properties::findBySql("SELECT $slectAmout $slectData $latlonQuery  properties.* FROM property_listing LEFT JOIN properties ON property_listing.property_id=properties.id
                                $property_bhkJoin
                                 WHERE property_listing.status='1'  $cityQuery  
                                 $property_typesql $property_bhk $property_flat_type $amountQuery $areaQuery $propertiesFac $properQuery GROUP BY properties.id")->all();
            $total_pages = count($modelcount);
            $max_distance = 0;
        }

        if ($total_pages1 != '0') {
            $sql = "SELECT $slectAmout $slectData $latlonQuery  properties.* FROM property_listing LEFT JOIN properties ON property_listing.property_id=properties.id
                            $property_bhkJoin
                            WHERE property_listing.status='1'  $cityQuery 
                            $property_typesql $property_bhk $property_flat_type  $amountQuery $areaQuery $propertiesFac $properQuery GROUP BY properties.id  $having $sortQuery LIMIT $start, $limit";
        } else {
            $sql = "SELECT $slectAmout $slectData $latlonQuery  properties.* FROM property_listing LEFT JOIN properties ON property_listing.property_id=properties.id
                            $property_bhkJoin
                            WHERE property_listing.status='1'  $cityQuery 
                            $property_typesql $property_bhk $property_flat_type  $amountQuery $areaQuery $propertiesFac $properQuery GROUP BY properties.id  $sortQuery LIMIT $start, $limit";
        }

        $model = Properties::findBySql($sql)->with('propertyListing')->all();

        $lat_sum = 0;
        $lng_sum = 0;
        $count_sum = 0;
        foreach ($model as $value_model) {
            $lat_sum += $value_model->lat;
            $lng_sum += $value_model->lon;
            $count_sum++;
        }
        $max_distance = 0;
        $lat_avg = 0;
        $lng_avg = 0;
        if ($count_sum != '0') {
            $lat_avg = $lat_sum / $count_sum;
            $lng_avg = $lng_sum / $count_sum;


            foreach ($model as $key_model => $value_model) {
                $theta = $lng_avg - $value_model->lon;
                $dist = sin(deg2rad($lat_avg)) * sin(deg2rad($value_model->lat)) + cos(deg2rad($lat_avg)) * cos(deg2rad($value_model->lat)) * cos(deg2rad($theta));
                $dist = acos($dist);
                $dist = rad2deg($dist);
                $miles = $dist * 60 * 1.1515 * 1.609344;

                if ($miles > $max_distance) {
                    $max_distance = $miles;
                }
            }
        }

        $variable = Yii::$app->request->absoluteUrl;
        $targetpage = strpos($variable, "&page") ? substr($variable, 0, strpos($variable, "&page")) : $variable;
        /**/
        /* Setup page vars for display. */
        if ($page == 0)
            $page = 1;     //if no page var is given, default to 1.
        $prev = $page - 1;       //previous page is page - 1
        $next = $page + 1;       //next page is page + 1
        $lastpage = ceil($total_pages / $limit);  //lastpage is = total pages / items per page, rounded up.
        $lpm1 = $lastpage - 1;      //last page minus 1

        /*
          Now we apply our rules and draw the pagination object.
          We're actually saving the code to a variable in case we want to draw it more than once.
         */
        $pagination = "";
        if ($lastpage > 1) {
            $pagination .= "<ul class=\"pagination\">";
            //previous button
            if ($page > 1)
                $pagination .= "<li><a href=\"$targetpage&page=$prev\"> previous</a></li>";
            else
                $pagination .= "<li class=\"disabled\"><span>previous</span></li>";

            //pages
            if ($lastpage < 7 + ($adjacents * 2)) { //not enough pages to bother breaking it up
                for ($counter = 1; $counter <= $lastpage; $counter++) {
                    if ($counter == $page)
                        $pagination .= "<li class=\"active\"><span >$counter</span></li>";
                    else
                        $pagination .= "<li><a href=\"$targetpage&page=$counter\">$counter</a></li>";
                }
            }
            elseif ($lastpage > 5 + ($adjacents * 2)) { //enough pages to hide some
                //close to beginning; only hide later pages
                if ($page < 1 + ($adjacents * 2)) {
                    for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                        if ($counter == $page)
                            $pagination .= "<li class=\"active\"><span >$counter</span></li>";
                        else
                            $pagination .= "<li><a href=\"$targetpage&page=$counter\">$counter</a></li>";
                    }
                    $pagination .= "...";
                    $pagination .= "<li><a href=\"$targetpage&page=$lpm1\">$lpm1</a></li>";
                    $pagination .= "<li><a href=\"$targetpage&page=$lastpage\">$lastpage</a></li>";
                }
                //in middle; hide some front and some back
                elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                    $pagination .= "<li><a href=\"$targetpage&page=1\">1</a>";
                    $pagination .= "<li><a href=\"$targetpage&page=2\">2</a>";
                    $pagination .= "...";
                    for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                        if ($counter == $page)
                            $pagination .= "<li class=\"active\"><span>$counter</span></li>";
                        else
                            $pagination .= "<li><a href=\"$targetpage&page=$counter\">$counter</a></li>";
                    }
                    $pagination .= "...";
                    $pagination .= "<li><a href=\"$targetpage&page=$lpm1\">$lpm1</a></li>";
                    $pagination .= "<li><a href=\"$targetpage&page=$lastpage\">$lastpage</a></li>";
                }
                //close to end; only hide early pages
                else {
                    $pagination .= "<li><a href=\"$targetpage&page=1\">1</a></li>";
                    $pagination .= "<li><a href=\"$targetpage&page=2\">2</a></li>";
                    $pagination .= "...";
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                        if ($counter == $page)
                            $pagination .= "<li><span class=\"active\">$counter</span></li>";
                        else
                            $pagination .= "<li><a href=\"$targetpage&page=$counter\">$counter</a></li>";
                    }
                }
            }

            //next button
            if ($page < $counter - 1)
                $pagination .= "<li><a href=\"$targetpage&page=$next\">next </a></li>";
            else
                $pagination .= "<li  class=\"disabled\"><span>next </span></li>";
            $pagination .= "</ul>\n";
        }

        /*         * */


        $dataMaps = array();
        foreach ($model as $key => $address) {
            $dataMaps[$key][] = $address->property_name;
            $dataMaps[$key][] = $address->lat;
            $dataMaps[$key][] = $address->lon;
            $dataMaps[$key][] = $address->id;
        }
        $locations = json_encode($dataMaps);

        $propertyFacility = [];

        $propertyAttrFacility = PropertiesAttributes::find()->where(['type' => '1'])->all();
        foreach ($propertyAttrFacility as $key => $propertyData) {
            $propertyFacility[$propertyData->id] = $propertyData->name;
        }

        $propertyComplex = [];
        $propertyAttrComplex = PropertiesAttributes::find()->where(['type' => '2'])->all();
        foreach ($propertyAttrComplex as $key => $propertyData) {
            $propertyComplex[$propertyData->id] = $propertyData->name;
        }

        $propertytypename = Yii::$app->userdata->getPropertyTypeName($property_type);

        return $this->render('search_filter', [
                    'model' => $model, 'searcharea' => $searcharea, 'locations' => $locations, 'propertyComplex' => $propertyComplex, 'propertyFacility' => $propertyFacility, 'propertytypename' => $propertytypename,
                    'pagination' => $pagination, 'flat_type' => $flat_type,
                    'min_area' => $min_area, 'max_area' => $max_area, 'min_amount' => $min_amount, 'max_amount' => $max_amount, 'min_radius' => $min_radius, 'max_radius' => $max_radius, 'center' => ['lat' => $lat_avg, 'lng' => $lng_avg, 'distance' => $max_distance], 'property_type' => $property_type
        ]);
    }

    public function actionAgreement1($id) {
        $tenantAgreements = \app\models\TenantAgreements::find()->where(['id' => $id])->all();
        header("Content-Type: application/octet-stream");

        $file = $tenantAgreements[0]->agreement_url . ".pdf";
        header("Content-Disposition: attachment; filename=" . urlencode($file));
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Description: File Transfer");
        header("Content-Length: " . filesize($file));
        flush(); // this doesn't really matter.
        $fp = fopen($file, "r");
        while (!feof($fp)) {
            echo fread($fp, 65536);
            flush(); // this is essential for large downloads
        }
        fclose($fp);
    }

    public function actionEditpersonalinfo() {

        if (isset(Yii::$app->user->id)) {
            //echo "<pre>";print_r($_POST);echo "</pre>";die;

            $userid = Yii::$app->user->id;
            $applicantmodel = \app\models\ApplicantProfile::find()->where(['applicant_id' => $userid])->one();
            $usermodel = \app\models\Users::find()->where(['id' => $userid])->one();
            $tenantmodel = \app\models\LeadsTenant::find()->where(['email_id' => $usermodel->login_id])->one();
            
            $usermodel->full_name = $_POST['name'];
            $usermodel->login_id = $_POST['email'];
            $usermodel->phone = $_POST['mobile'];
            
            if ($usermodel->validate(['full_name','phone','login_id'])) {
                $applicantmodel->address_line_1 = $_POST['address1'];
                $applicantmodel->address_line_2 = $_POST['address2'];
                $applicantmodel->pincode = $_POST['pincode'];
                $applicantmodel->phone = $_POST['mobile'];
                $applicantmodel->state = $_POST['state'];
                $applicantmodel->city_name = $_POST['city_name'];
                $applicantmodel->email_id = $_POST['email'];
                $applicantmodel->phone = $_POST['mobile'];

                if ($_FILES['fileupload']['name'] != '') {
                    $profile_image = $_FILES['fileupload'];

                    //print_r($profile_image);die;
                    $target_dir = "uploads/profiles/";
                    $file_tmp = $profile_image['tmp_name'];
                    $file_name = $profile_image['name'];
                    $target_file = $target_dir . basename($profile_image["name"]);
                    move_uploaded_file($file_tmp, $target_dir . $file_name);
                    $applicantmodel->profile_image = "uploads/profiles/" . $profile_image['name'];
                }
                
                $tenantmodel->address = $_POST['address1'];
                $tenantmodel->address_line_2 = $_POST['address2'];
                $tenantmodel->pincode = $_POST['pincode'];
                $tenantmodel->contact_number = $_POST['mobile'];
                $tenantmodel->full_name = $_POST['name'];
                $tenantmodel->email_id = $_POST['email'];
                
                if ($_POST['dltimg'] == 0) {
                    $applicantmodel->profile_image = "";
                }
                
                $tenantmodel->save(false);
                $applicantmodel->save(false);
                $usermodel->save(false);
            }
        } else {

            return $this->redirect(['site/login']);
        }
    }

    public function actionEditbankdetail() {
        if (isset(Yii::$app->user->id)) {
            $userid = Yii::$app->user->id;

            $applicantmodel = \app\models\ApplicantProfile::find()->where(['applicant_id' => $userid])->one();
            $applicantmodel->account_holder_name = $_POST['account_holder_name'];
            $applicantmodel->bank_name = $_POST['bank_name'];
            $applicantmodel->bank_branchname = $_POST['bank_branchname'];
            $applicantmodel->bank_ifcs = $_POST['bank_ifcs'];
            $applicantmodel->bank_account_number = $_POST['bank_account_number'];
            $applicantmodel->pan_number = $_POST['pan_number'];
            if ($_FILES['fileupload']['name'] != '') {
                $cancelled_check = $_FILES['fileupload'];
                $target_dir = "uploads/proofs/";
                $file_tmp = $cancelled_check['tmp_name'];
                $file_name = $cancelled_check['name'];
                $target_file = $target_dir . basename($cancelled_check["name"]);
                move_uploaded_file($file_tmp, $target_dir . $file_name);
                $applicantmodel->cancelled_check = "uploads/proofs/" . $cancelled_check['name'];
            }
            $applicantmodel->save(false);
            return $this->redirect(['myprofile?type=b']);
        } else {

            return $this->redirect(['site/login']);
        }
    }

    public function actionEditemergencydetail() {
        //echo "<pre>";print_r($_FILES);echo "</pre>";die;
        if (isset(Yii::$app->user->id)) {
            $userid = Yii::$app->user->id;
            $applicantmodel = \app\models\ApplicantProfile::find()->where(['applicant_id' => $userid])->one();
            $applicantmodel->emergency_contact_name = $_POST['em_name'];
            $applicantmodel->emergency_contact_email = $_POST['em_email'];
            $applicantmodel->emergency_contact_number = $_POST['em_mobile'];
            $emer_contact_address = $_FILES['address_proof'];
            if (!empty($emer_contact_address)) {
                foreach ($emer_contact_address['tmp_name'] as $key => $value) {

                    foreach ($value as $key1 => $value1) {
                        if ($value1 != '') {
                            $emergency_proofobj = new \app\models\EmergencyProofs;
                            $name_address_proof = (string) date('ymdHis') . rand() . $emer_contact_address['name'][$key][$key1];
                            $tmp_file = (string) $value1;
                            move_uploaded_file($value1, 'uploads/proofs/' . $name_address_proof);
                            $emergency_proofobj->proof_type = $key;
                            // $emergency_proofobj->proof_document_url = 'uploads/proofs/' .$name_address_proof ;
                            $emergency_proofobj->proof_document_url = 'uploads/proofs/' . $name_address_proof;
                            $emergency_proofobj->user_id = Yii::$app->user->id;
                            $emergency_proofobj->created_by = Yii::$app->user->id;
                            $emergency_proofobj->created_date = date('Y-m-d H:i:s');
                            $emergency_proofobj->save(false);
                        }
                    }
                }
            }
            $applicantmodel->save(false);
            return $this->redirect(['myprofile?type=em']);
        } else {

            return $this->redirect(['site/login']);
        }
    }

    public function actionEditemployeedetail() {
        //echo "<pre>";print_r($_FILES);echo "</pre>";die;
        if (isset(Yii::$app->user->id)) {
            $userid = Yii::$app->user->id;

            $applicantmodel = \app\models\ApplicantProfile::find()->where(['applicant_id' => $userid])->one();
            $applicantmodel->employer_name = $_POST['employer_name'];
            $applicantmodel->employee_id = $_POST['employee_id'];
            if ($_POST['employment_start_date'] != '') {
                //echo "hello";die;
                $applicantmodel->employment_start_date = $_POST['employment_start_date'];
            } else {
                //echo "hello 1";die;
                $applicantmodel->employment_start_date = NULL;
            }
            $applicantmodel->employment_email = $_POST['employment_email'];
            if ($_FILES['employment_proof']['name'] != '') {
                $employeeproof = $_FILES['employment_proof'];
                $target_dir = "uploads/proofs/";
                $file_tmp = $employeeproof['tmp_name'];
                $file_name = $employeeproof['name'];
                $target_file = $target_dir . basename($employeeproof["name"]);
                move_uploaded_file($file_tmp, $target_dir . $file_name);
                $applicantmodel->employmnet_proof_url = "uploads/proofs/" . $employeeproof["name"];
            }
            $applicantmodel->save(false);
            return $this->redirect(['myprofile?type=e']);
        } else {

            return $this->redirect(['site/login']);
        }
    }

    public function actionEditidentityinfo() {
        if (isset(Yii::$app->user->id)) {
            $userid = Yii::$app->user->id;

            $address_proof = $_FILES['address_proof'];
            //echo "<pre>";print_r($_FILES);echo "</pre>";die;
            if (!empty($address_proof)) {
                foreach ($address_proof['tmp_name'] as $key => $value) {

                    foreach ($value as $key1 => $value1) {
                        if (trim($value1) != '') {
                            $address_proofobj = new \app\models\UserIdProofs;
                            $name_address_proof = (string) date('ymdHis') . rand() . $address_proof['name'][$key][$key1];
                            $tmp_file = (string) $value1;
                            move_uploaded_file($value1, 'uploads/proofs/' . $name_address_proof);
                            $address_proofobj->proof_type = $key;
                            // $address_proofobj->proof_document_url = Url::home(true).'uploads/proofs/' .$name_address_proof ;
                            $address_proofobj->proof_document_url = 'uploads/proofs/' . $name_address_proof;
                            $address_proofobj->user_id = Yii::$app->user->id;
                            $address_proofobj->created_by = Yii::$app->user->id;
                            $address_proofobj->created_date = date('Y-m-d H:i:s');
                            $address_proofobj->save(false);
                        }
                    }
                }
            }

            return $this->redirect(['myprofile?type=i']);
        } else {

            return $this->redirect(['site/login']);
        }
    }

    public function actionGetcities() {
        $state = $_POST['state'];
        $query = 'SELECT * FROM cities WHERE state_id=' . $state;
        $connection = \Yii::$app->db;
        $model = $connection->createCommand($query);
        $cities = $model->queryAll();
        //print_r($duePayments); exit;
        $cityhtml = '';
        foreach ($cities as $city) {
            $cityname = $city['city_name'];
            $cityid = $city['id'];
            $cityhtml .= '<option label="' . $cityname . '" id="' . $cityid . '" value="' . $cityid . '">' . $cityname . '</option>';
        }
        echo $cityhtml;
        die;
    }

    public function actionCheckoldpass() {
        $userid = Yii::$app->user->id;
        $oldpass = $_POST['cp'];
        $query = 'SELECT * FROM users WHERE id=' . $userid;
        $connection = \Yii::$app->db;
        $model = $connection->createCommand($query);
        $users = $model->queryOne();
        if ($users['password'] == md5($oldpass)) {
            echo '1';
            die;
        } else {
            echo '0';
            die;
        }
    }

    public function actionGetcurrentcity() {
        $city = $_POST['city'];
        //echo $city;die;
        $query = 'SELECT * FROM cities WHERE id=' . $city;
        $connection = \Yii::$app->db;
        $model = $connection->createCommand($query);
        $cities = $model->queryOne();
        //print_r($cities); exit;
        $cityhtml = '';

        $cityname = $cities['city_name'];
        $cityid = $cities['id'];
        $cityhtml .= '<option label="' . $cityname . '" id="' . $cityid . '" value="' . $cityid . '" selected>' . $cityname . '</option>';

        echo $cityhtml;
        die;
    }

    public function actionEditpersonalowner() {
        $modelUser = Users::findOne(Yii::$app->user->id);

        //echo "<pre>";print_r($_POST);echo "</pre>";die;

        if (isset(Yii::$app->user->id)) {
            //echo "<pre>";print_r($_POST);echo "</pre>";die;

            $userid = Yii::$app->user->id;
            $ownermodel = \app\models\OwnerProfile::find()->where(['owner_id' => $userid])->one();
            $ownermodel->address_line_1 = $_POST['address1'];
            $ownermodel->address_line_2 = $_POST['address2'];
            $ownermodel->pincode = $_POST['pincode'];
            $ownermodel->phone = $_POST['mobile'];
            $ownermodel->state = $_POST['state'];
            $ownermodel->city_name = $_POST['city_name'];
            
            if ($_POST['dltimg'] == 0) {
                $ownermodel->profile_image = "";
            }

            if ($_FILES['fileupload']['name'] != '') {
                $profile_image = $_FILES['fileupload'];

                //print_r($profile_image);die;
                $target_dir = "uploads/profiles/";
                $file_tmp = $profile_image['tmp_name'];
                $file_name = $profile_image['name'];
                $target_file = $target_dir . basename($profile_image["name"]);
                move_uploaded_file($file_tmp, $target_dir . $file_name);
                $ownermodel->profile_image = "uploads/profiles/" . $profile_image['name'];
            }
            
            $usermodel = \app\models\Users::find()->where(['id' => $userid])->one();
            $modelLeadsOwner = LeadsOwner::find()->where(['email_id' => $modelUser->login_id])->one();
            
            
            $modelLeadsOwner->full_name = $_POST['name'];
            $modelLeadsOwner->email_id = $_POST['email'];
            $modelLeadsOwner->contact_number = $_POST['mobile'];
            $modelLeadsOwner->address = $_POST['address1'];
            $modelLeadsOwner->address_line_2 = $_POST['address2'];
            $modelLeadsOwner->pincode = $_POST['pincode'];
            
            $usermodel->full_name = $_POST['name'];
            $usermodel->login_id = $_POST['email'];
            $usermodel->username = $_POST['email'];
            $usermodel->phone = $_POST['mobile'];
            if ($ownermodel->validate(['full_name','login_id','phone'])) {
                $ownermodel->save(false);        
                $usermodel->save(false);
                $modelLeadsOwner->save(false);
                
            } else {
                Yii::$app->getSession()->setFlash(
                            'error', 'Validation error, data not saved');
            }
            return $this->redirect(['myprofile?type=op']);
        } else {

            return $this->redirect(['site/login']);
        }
    }

    public function actionEditidentityowner() {
        //echo "<pre>";print_r($_FILES);echo "</pre>";die;
        if (isset(Yii::$app->user->id)) {
            $userid = Yii::$app->user->id;

            $address_proof = $_FILES['address_proof'];
            //echo "<pre>";print_r($_FILES);echo "</pre>";die;
            if (!empty($address_proof)) {
                foreach ($address_proof['tmp_name'] as $key => $value) {

                    foreach ($value as $key1 => $value1) {
                        if (trim($value1) != '') {
                            $address_proofobj = new \app\models\UserIdProofs;
                            $name_address_proof = (string) date('ymdHis') . rand() . $address_proof['name'][$key][$key1];
                            $tmp_file = (string) $value1;
                            move_uploaded_file($value1, 'uploads/proofs/' . $name_address_proof);
                            $address_proofobj->proof_type = $key;
                            // $address_proofobj->proof_document_url = Url::home(true).'uploads/proofs/' .$name_address_proof ;
                            $address_proofobj->proof_document_url = 'uploads/proofs/' . $name_address_proof;
                            $address_proofobj->user_id = Yii::$app->user->id;
                            $address_proofobj->created_by = Yii::$app->user->id;
                            $address_proofobj->created_date = date('Y-m-d H:i:s');
                            $address_proofobj->save(false);
                        }
                    }
                }
            }

            return $this->redirect(['myprofile?type=oi']);
        } else {

            return $this->redirect(['site/login']);
        }
    }

    public function actionEditbankowner() {
        //echo "<pre>";print_r($_POST);echo "</pre>";die;
        if (isset(Yii::$app->user->id)) {
            $userid = Yii::$app->user->id;

            $ownermodel = \app\models\OwnerProfile::find()->where(['owner_id' => $userid])->one();
            $ownermodel->account_holder_name = $_POST['account_holder_name'];
            $ownermodel->bank_name = $_POST['bank_name'];
            $ownermodel->bank_branchname = $_POST['bank_branchname'];
            $ownermodel->bank_ifcs = $_POST['bank_ifcs'];
            $ownermodel->bank_account_number = $_POST['bank_account_number'];
            $ownermodel->pan_number = $_POST['pan_number'];
            if ($_FILES['fileupload']['name'] != '') {
                $cancelled_check = $_FILES['fileupload'];
                $target_dir = "uploads/proofs/";
                $file_tmp = $cancelled_check['tmp_name'];
                $file_name = $cancelled_check['name'];
                $target_file = $target_dir . basename($cancelled_check["name"]);
                move_uploaded_file($file_tmp, $target_dir . $file_name);
                $ownermodel->cancelled_check = "uploads/proofs/" . $cancelled_check['name'];
            }
            $ownermodel->save(false);
            return $this->redirect(['myprofile?type=ob']);
        } else {

            return $this->redirect(['site/login']);
        }
    }

    public function actionEditemergencyowner() {
        if (isset(Yii::$app->user->id)) {
            $userid = Yii::$app->user->id;
            $ownermodel = \app\models\OwnerProfile::find()->where(['owner_id' => $userid])->one();
            $ownermodel->emer_contact = $_POST['em_name'];
            $ownermodel->emergency_contact_email = $_POST['em_email'];
            $ownermodel->emergency_contact_number = $_POST['em_mobile'];
            $ownermodel->save(false);
            return $this->redirect(['myprofile?type=oem']);
        } else {

            return $this->redirect(['site/login']);
        }
    }

    public function actionRemoveidentityowner() {
        $id = $_POST['id'];
        if (Yii::$app->request->isAjax) {
            $proof = \app\models\UserIdProofs::findOne(['id' => $id]);
            if ($proof) {
                $proof->delete();
            }
            return true;
        }
    }

    public function actionDeleteservicerequest($rid, $id) {
        //echo $rid;die;
        $sid = base64_decode($rid);
        $model = \app\models\PropertyServiceRequest::findOne(['id' => $sid]);
        $model->delete();
        $pid = base64_encode($id);
        return $this->redirect(['ownerrequests?id=' . $id]);
    }

    public function actionRequestextension() {
        $modelOwnerProfile = \app\models\OwnerProfile::find('operation_id')->where(['owner_id' => Yii::$app->user->id])->one();
        $model = new ServiceRequest;
        $model->created_by = Yii::$app->user->id;
        $model->client_id = Yii::$app->user->id;
        $model->operated_by = $modelOwnerProfile->operation_id;
        $model->status = 1;
        $model->property_id = $_POST['pid'];
        $model->request_type = 5;
        $model->client_type = 4;
        $model->title = "SLA extension requested";
        $model->description = "SLA expiring on " . $_POST['end_date'] . ", extension has been requested";
        $model->save(false);
        $lastInsert = $model->id;

        $operationDetails = Yii::$app->userdata->getOperationsDetailByUserId(Yii::$app->user->id);
        $subject = "Service Request $model->id Created ";
        $name = Yii::$app->userdata->getName();
        $msg = "Hello $operationDetails[name]<br/><br/>" . $name . " has requested for SLA extension, a service request no. " . $lastInsert . " has been generated for same. Please get in touch with the client and do the needful.<br/><br/>With Regards,<br/>EasyLeases Team<br/><img src='" . Url::home(true) . "images/property_logo.png' alt=''>";
        @Yii::$app->userdata->doMail($operationDetails['email'], $subject, $msg);

        echo "Your request for SLA extension has been registered, our operations team will be in touch with you shortly.";
        die;
    }

    public function actionRequesttermination() {
        $modelOwnerProfile = \app\models\OwnerProfile::find('operation_id')->where(['owner_id' => Yii::$app->user->id])->one();
        $model = new ServiceRequest;
        $model->created_by = Yii::$app->user->id;
        $model->client_id = Yii::$app->user->id;
        $model->operated_by = $modelOwnerProfile->operation_id;
        $model->status = 1;
        $model->property_id = $_POST['pid'];
        $model->request_type = 5;
        $model->client_type = 4;
        $model->title = "SLA termination requested";
        $model->description = "SLA expiring on " . $_POST['end_date'] . ", termination has been requested";
        $model->save(false);
        $lastInsert = $model->id;

        $operationDetails = Yii::$app->userdata->getOperationsDetailByUserId(Yii::$app->user->id);
        $subject = "Service Request $model->id Created ";
        $name = Yii::$app->userdata->getName();
        $msg = "Hello $operationDetails[name]<br/><br/>" . $name . " has requested for SLA termination, a service request no. " . $lastInsert . " has been generated for same. Please get in touch with the client and do the needful.<br/><br/>With Regards,<br/>EasyLeases Team<br/><img src='" . Url::home(true) . "images/property_logo.png' alt=''>";
        @Yii::$app->userdata->doMail($operationDetails['email'], $subject, $msg);

        echo "Your request for SLA termination has been registered, our operations team will be in touch with you shortly.";
        die;
    }

    public function actionDeleteattach() {
        $aid = $_POST['aid'];
        $model = \app\models\ServiceRequestAttachment::findOne(['id' => $aid]);
        $model->delete();
    }

    public function actionEditpersonalinfotenant() {
        if (isset(Yii::$app->user->id)) {
            $userid = Yii::$app->user->id;
            $usermodel = \app\models\Users::find()->where(['id' => $userid])->one();
            $tenantmodel = \app\models\LeadsTenant::find()->where(['email_id' => $usermodel->login_id])->one();
            $applicantprofilemodel = \app\models\ApplicantProfile::find()->where(['applicant_id' => $userid])->one();
            $tenantprofilemodel = \app\models\TenantProfile::find()->where(['tenant_id' => $userid])->one();
                    
            $usermodel->full_name = $_POST['name'];
            $usermodel->login_id = $_POST['email'];
            $usermodel->username = $usermodel->login_id;
            $usermodel->phone = $_POST['mobile'];
            if ($usermodel->validate(['full_name','phone','login_id'])) {
            
                $tenantmodel->address = $_POST['address1'];
                $tenantmodel->address_line_2 = $_POST['address2'];
                $tenantmodel->pincode = $_POST['pincode'];
                $tenantmodel->contact_number = $_POST['mobile'];
                $tenantmodel->email_id = $_POST['email'];
                $tenantmodel->full_name = $_POST['name'];

                $applicantprofilemodel->address_line_1 = $_POST['address1'];
                $applicantprofilemodel->address_line_2 = $_POST['address2'];
                $applicantprofilemodel->pincode = $_POST['pincode'];
                $applicantprofilemodel->phone = $_POST['mobile'];
                $applicantprofilemodel->state = $_POST['state'];
                $applicantprofilemodel->city_name = $_POST['city_name'];
                $applicantprofilemodel->email_id = $_POST['email'];
                
                $tenantprofilemodel->address_line_1 = $_POST['address1'];
                $tenantprofilemodel->address_line_2 = $_POST['address2'];
                $tenantprofilemodel->pincode = $_POST['pincode'];
                $tenantprofilemodel->phone = $_POST['mobile'];
                $tenantprofilemodel->state = $_POST['state'];
                $tenantprofilemodel->city_name = $_POST['city_name'];
                
                if ($_FILES['fileupload']['name'] != '') {
                    $profile_image = $_FILES['fileupload'];
                    $timestamp = date('ymdHis');
                    //print_r($profile_image);die;
                    $target_dir = "uploads/profiles/";
                    $file_tmp = $profile_image['tmp_name'];
                    $file_name = $profile_image['name'];
                    $target_file = $target_dir. basename($profile_image["name"]);
                    //print_r($userid);die;
                    move_uploaded_file($file_tmp, $target_dir.$timestamp.$file_name);
                    $tenantmodel->profile_image = $target_dir.$timestamp.$profile_image['name'];
                }
                
                if ($_POST['dltimg'] == 0) {
                    $tenantmodel->profile_image = "";
                }
                
                $tenantmodel->save(false);
                $tenantprofilemodel->save(false);
                $applicantprofilemodel->save(false);
                $usermodel->save(false);
            }
        } else {

            return $this->redirect(['site/login']);
        }
    }

    public function actionEditidentityinfotenant() {
        //echo "<pre>";print_r($_FILES);echo "</pre>";die;
        if (isset(Yii::$app->user->id)) {
            $userid = Yii::$app->user->id;
            if (!empty($_FILES)) {
                $address_proof = $_FILES['address_proof'];
                //echo "<pre>";print_r($_FILES);echo "</pre>";die;
                if (!empty($address_proof)) {
                    foreach ($address_proof['tmp_name'] as $key => $value) {

                        foreach ($value as $key1 => $value1) {
                            if (trim($value1) != '') {
                                $address_proofobj = new \app\models\UserIdProofs;
                                $name_address_proof = (string) date('ymdHis') . rand() . $address_proof['name'][$key][$key1];
                                $tmp_file = (string) $value1;
                                move_uploaded_file($value1, 'uploads/proofs/' . $name_address_proof);
                                $address_proofobj->proof_type = $key;
                                // $address_proofobj->proof_document_url = Url::home(true).'uploads/proofs/' .$name_address_proof ;
                                $address_proofobj->proof_document_url = 'uploads/proofs/' . $name_address_proof;
                                $address_proofobj->user_id = Yii::$app->user->id;
                                $address_proofobj->created_by = Yii::$app->user->id;
                                $address_proofobj->created_date = date('Y-m-d H:i:s');
                                $address_proofobj->save(false);
                            }
                        }
                    }
                }
            }
            return $this->redirect(['myprofile?type=i']);
        } else {

            return $this->redirect(['site/login']);
        }
    }

    public function actionEditbankdetailtenant() {
        if (isset(Yii::$app->user->id)) {
            $userid = Yii::$app->user->id;

            $tenantmodel = \app\models\TenantProfile::find()->where(['tenant_id' => $userid])->one();
            $tenantmodel->account_holder_name = $_POST['account_holder_name'];
            $tenantmodel->bank_name = $_POST['bank_name'];
            $tenantmodel->bank_branchname = $_POST['bank_branchname'];
            $tenantmodel->bank_ifcs = $_POST['bank_ifcs'];
            $tenantmodel->account_number = $_POST['bank_account_number'];
            $tenantmodel->pan_number = $_POST['pan_number'];
            if ($_FILES['fileupload']['name'] != '') {
                $cancelled_check = $_FILES['fileupload'];
                $target_dir = "uploads/proofs/";
                $file_tmp = $cancelled_check['tmp_name'];
                $file_name = $cancelled_check['name'];
                $target_file = $target_dir . basename($cancelled_check["name"]);
                move_uploaded_file($file_tmp, $target_dir . $file_name);
                $tenantmodel->cancelled_check = "uploads/proofs/" . $cancelled_check['name'];
            }
            $tenantmodel->save(false);
            return $this->redirect(['myprofile?type=b']);
        } else {

            return $this->redirect(['site/login']);
        }
    }

    public function actionEditemergencydetailtenant() {
        //echo "<pre>";print_r($_FILES);echo "</pre>";die;
        if (isset(Yii::$app->user->id)) {
            $userid = Yii::$app->user->id;
            $tenantmodel = \app\models\TenantProfile::find()->where(['tenant_id' => $userid])->one();
            $tenantmodel->emergency_contact_name = $_POST['em_name'];
            $tenantmodel->emer_contact_address = $_POST['em_email'];
            $tenantmodel->emergency_contact_number = $_POST['em_mobile'];
            if (!empty($_FILES['address_proof'])) {
                $emer_contact_address = $_FILES['address_proof'];
            } else {
                $emer_contact_address = array();
            }
            if (!empty($emer_contact_address)) {
                foreach ($emer_contact_address['tmp_name'] as $key => $value) {
                    foreach ($value as $key1 => $value1) {
                        if ($value1 != '') {
                            $emergency_proofobj = new \app\models\EmergencyProofs;
                            $name_address_proof = (string) date('ymdHis') . rand() . $emer_contact_address['name'][$key][$key1];
                            $tmp_file = (string) $value1;
                            move_uploaded_file($value1, 'uploads/proofs/' . $name_address_proof);
                            $emergency_proofobj->proof_type = $key;
                            // $emergency_proofobj->proof_document_url = 'uploads/proofs/' .$name_address_proof ;
                            $emergency_proofobj->proof_document_url = 'uploads/proofs/' . $name_address_proof;
                            $emergency_proofobj->user_id = Yii::$app->user->id;
                            $emergency_proofobj->created_by = Yii::$app->user->id;
                            $emergency_proofobj->created_date = date('Y-m-d H:i:s');
                            $emergency_proofobj->save(false);
                        }
                    }
                }
            }
            $tenantmodel->save(false);
            return $this->redirect(['myprofile?type=em']);
        } else {

            return $this->redirect(['site/login']);
        }
    }

    public function actionEditemployeedetailtenant() {
        //echo "<pre>";print_r($_POST);echo "</pre>";die;
        if (isset(Yii::$app->user->id)) {
            $userid = Yii::$app->user->id;

            $tenantmodel = \app\models\TenantProfile::find()->where(['tenant_id' => $userid])->one();
            $tenantmodel->employer_name = $_POST['employer_name'];
            $tenantmodel->employee_id = $_POST['employee_id'];
            if ($_POST['employment_start_date'] != '') {
                //echo "hello";die;
                $tenantmodel->employment_start_date = $_POST['employment_start_date'];
            } else {
                //echo "hello 1";die;
                $tenantmodel->employment_start_date = NULL;
            }
            $tenantmodel->employment_email = $_POST['employment_email'];
            if ($_FILES['employment_proof']['name'] != '') {
                $employeeproof = $_FILES['employment_proof'];
                $target_dir = "uploads/proofs/";
                $file_tmp = $employeeproof['tmp_name'];
                $file_name = $employeeproof['name'];
                $target_file = $target_dir . basename($employeeproof["name"]);
                move_uploaded_file($file_tmp, $target_dir . $file_name);
                $tenantmodel->employment_proof_url = "uploads/proofs/" . $employeeproof["name"];
            }
            $tenantmodel->save(false);
            return $this->redirect(['myprofile?type=e']);
        } else {

            return $this->redirect(['site/login']);
        }
    }

    public function actionRequestextensiontenant() {

        $extension = Yii::$app->userdata->requestTenantLeaseExtn(Yii::$app->user->id,$_POST['pid'],$_POST['end_date']);
        if ($extension != "SUCCESS") {
            echo "Error: " . $extension;
        } else {
            echo "Your request for lease agreement extension has been registered, our operations team will be in touch with you shortly.";
        }
        die;
    }

    public function actionRequestterminationtenant() {
        $extension = Yii::$app->userdata->requestTenantLeaseTerm(Yii::$app->user->id,$_POST['pid'],$_POST['end_date']);
        if ($extension != "SUCCESS") {
            echo "Error: " . $extension;
        } else {
            echo "Your request for lease agreement termination has been registered, our operations team will be in touch with you shortly.";
        }
 
        die;
    }

    public function actionDeleterequest($id) {
        $model = \app\models\PropertyServiceRequest::findOne(['id' => $id]);
        $model->delete();
        $pid = base64_encode($id);
        return $this->redirect(['myrequests']);
    }

    public function actionRemoveidentitytenant() {
        $id = $_POST['id'];
        if (Yii::$app->request->isAjax) {
            $proof = \app\models\UserIdProofs::findOne(['id' => $id]);
            if ($proof) {
                $proof->delete();
            }
            return true;
        }
    }

    public function actionLaunchoffers() {
        header("Cache-Control: max-age=6480000"); //30days (60sec * 60min * 24hours * 30days)
        $this->view->params['head_title'] = "Launch Offers - Easyleases | Residential Property Management";
        $this->view->params['meta_desc'] = "Launch Offers - Easyleases, Property Management Services, Property Management Services Bangalore, Tenant Management, Home Management, Real Estate Management";
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
                
                return $this->render('launch_offers', ['postData' => $postData, 'thanks' => true]);
            }
        }
        
        return $this->render('launch_offers', ['postData' => $postData]);
    }

    public function actionNrioffers() {
        header("Cache-Control: max-age=6480000"); //30days (60sec * 60min * 24hours * 30days)
        $this->view->params['head_title'] = "Rental Property Management Service For NRI's";
        $this->view->params['meta_desc'] = "Rental Property Management Service For NRI's, Property Management Services Bangalore, Tenant Management, Home Management, Real Estate Management, NRI";
        $this->view->params['meta_keywords'] = "";
        $this->layout = 'nri_campaign';
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
                
                return $this->render('nri_campaign', ['postData' => $postData, 'thanks' => true]);
            }
        }

        return $this->render('nri_campaign', ['postData' => $postData]);
    }

    public function actionTermsncondition() {
        $this->layout = 'camp_layout';
        return $this->render('camp_tnc');
    }
    
    public function actionPropertydetailspage () {
        $this->layout = false;
        return $this->render('property_detail');
    }

}
