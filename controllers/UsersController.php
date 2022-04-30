<?php

namespace app\controllers;

use Yii;
use app\models\Users;
use app\models\Advisors;
use app\models\Owner;
use app\models\Register;
use app\models\Forget;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\PasswordForm;
use app\models\ForgotPassword;
use yii\base\Security;
use yii\web\IdentityInterface;

/**
 * UsersController implements the CRUD actions for Users model.
 */

class UsersController extends Controller {

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
            // else{
            //        header('location:'.Yii::$app->userdata->getDefaultUrl());
            //        die;
            // }
        }
    }

    public function behaviors() {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['changepassword', 'index', 'update', 'Ownerpassword', 'advisorpassword'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionGetcity() {

        $id = (empty(Yii::$app->request->post('state'))) ? @Yii::$app->request->post('val') : @Yii::$app->request->post('state') ;
        $model = \app\models\Cities::find()->where(['state_code' => $id])->all();
        $listData = \yii\helpers\ArrayHelper::map($model, 'code', 'city_name');

        $html = '<b id="bold">City *</b><select name="city" class="form-control"><option value=""> Select city </option>';
        foreach ($listData as $key => $list) {
            $html .= '<option value="' . $key . '">' . $list . '</option></select>';
        }
        echo $html;
        die;
    }

    /**
     * Lists all Users models.
     * @return mixed
     */
	 
    public function actionIndex() {
        $dataProvider = new ActiveDataProvider([
            'query' => Users::find(),
        ]);

        return $this->render('index', [
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionTenant() {
        $this->layout = 'home';
        return $this->render('tenant');
    }

    /**
     * Displays a single Users model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Users model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Users();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Users model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Users model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionConfirm($id, $key) {

        $modeluser = Users::find()->where(['id' => base64_decode($id), 'status' => 0, 'auth_key' => $key])->one();
        $model = new \app\models\LoginForm();
        if (!empty(Yii::$app->request->get('d'))) {
            $encData = Yii::$app->request->get('d');
            $result = Yii::$app->getSecurity()->decryptByKey($encData, Yii::$app->params['hash_key']);
            $userArr = json_decode($result);
            $formArr['LoginForm']['username'] = $userArr->username;
            $formArr['LoginForm']['password'] = $userArr->password;
            $model->load($formArr);
        }
        if (!empty($modeluser)) {
            $modeluser->status = 1;
            $modeluser->save(false);
            if ($modeluser->user_type == 5 || $modeluser->user_type == 4) {
                Yii::$app->getSession()->setFlash('success', 'Success!, Your account is verified.');
                $model->login();
            } else if ($modeluser->user_type == 2) {
                Yii::$app->getSession()->setFlash('success', 'Success!, Your account is verified , it is activated.');
                $model->login();
                return $this->redirect(['site/login']);
                exit;
            }
        } else {
            Yii::$app->getSession()->setFlash('success', 'Sorry! no account found with this token.');
        }
        return $this->goHome();

        die;
    }

    /**
     * Deletes an existing Users model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Users model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Users the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Users::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /* public function actionPropertyadvisor(){
      //die('testere');
      $model = new Advisor();
      $this->render('property_advisor', [
      'model' => $model,
      ]);

      } */
    /*
     * * function for user property advisor registration
     */

    public function actionAdvisor() {
        $this->view->params['head_title'] = "Property Management Bangalore | Property Management Companies";
        $this->view->params['meta_desc'] = "Property Management Bangalore, Property Management Companies, Top Property Management Companies in Bangalore, Best Property Management Services in Bangalore.";
        $this->layout = 'signup_advisor';

        $model = new \app\models\Users;
        $modelLeadsAdvisor = new \app\models\LeadsAdvisor;
        $modelAdvisorProfile = new \app\models\AdvisorProfile;
        
        if ($model->load(Yii::$app->request->post()) && $modelLeadsAdvisor->load(Yii::$app->request->post()) && $modelAdvisorProfile->load(Yii::$app->request->post())) {
            $post = Yii::$app->request->post();
            $model->phone = Yii::$app->userdata->trimPhone($model->phone);
            $rand = Yii::$app->userdata->passwordGenerate();
            $model->auth_key = base64_encode($model->login_id . time() . rand(1000, 9999));
            $model->created_date = date('Y-m-d H:i:s');
            $model->password = md5($rand);
            $model->username = $model->login_id;
            $model->user_type = 5;
            $model->role = 0;
            
            if ($model->validate(['full_name', 'login_id', 'phone']) && $modelLeadsAdvisor->validate(['lead_city']) && $modelAdvisorProfile->validate(['ad_properties'])) {
                $model->save(false);
                $model->id = Yii::$app->db->getLastInsertID();
                $cityCode = Yii::$app->userdata->getCityCodeById($modelLeadsAdvisor->lead_city);
                if (!empty(Yii::$app->request->post('sales_email'))) {
                    $salesget = \app\models\Users::findOne(['login_id' => $_POST['sales_email'], 'user_type' => '6']);
                    if (count($salesget) != 0) {
                        $salesUser = \app\models\SalesProfile::findOne(['sale_id' => $salesget->id]);
                        if (count($salesUser) != 0) {
                            if ($salesUser->role_code == 'SLCTMG') {
                                $modelAdvisorProfile->branch_code = null;
                                $opUser = \app\models\OperationsProfile::findOne(['role_code' => 'OPCTMG', 'role_value' => $cityCode]);
                                if (count($opUser) != 0) {
                                    $modelAdvisorProfile->operation_id = $opUser->operations_id;
                                } else {
                                    $modelAdvisorProfile->operation_id = null;
                                }
                            } else if ($salesUser->role_code == 'SLBRMG' || $salesUser->role_code == 'SLEXE') {
                                $modelAdvisorProfile->branch_code = $salesUser->role_value;
                                $opUser = \app\models\OperationsProfile::findOne(['role_code' => 'OPBRMG', 'role_value' => $salesUser->role_value]);
                                if (count($opUser) != 0) {
                                    $modelAdvisorProfile->operation_id = $opUser->operations_id;
                                } else {
                                    $modelAdvisorProfile->operation_id = null;
                                }
                            }
                        }
                        
                        $modelAdvisorProfile->sales_id = $salesget->id;
                    } else {
                        Yii::$app->getSession()->setFlash('sales_email_error', 'Please enter a valid sales email.');
                        return $this->refresh();
                    }
                } else {
                    $salesUser = \app\models\SalesProfile::findOne(['role_code' => 'SLCTMG', 'role_value' => $cityCode]);
                    if (count($salesUser) != 0) {
                        $modelAdvisorProfile->sales_id = $salesUser->sale_id;
                    } else {
                        $modelAdvisorProfile->sales_id = null;
                    }
                    
                    $modelAdvisorProfile->branch_code = null;
                    $opUser = \app\models\OperationsProfile::findOne(['role_code' => 'OPCTMG', 'role_value' => $cityCode]);
                    //print_r($opUser);exit();
                    if (count($opUser) != 0) {
                        $modelAdvisorProfile->operation_id = $opUser->operations_id;
                    } else {
                        $modelAdvisorProfile->operation_id = null;
                    }
                }
		
                $modelLeadsAdvisor->full_name = $model->full_name;
                $modelLeadsAdvisor->email_id = $model->login_id;
                $modelLeadsAdvisor->created_date = date('Y-m-d h:i:s');
                $modelLeadsAdvisor->lead_state = Yii::$app->userdata->getStateCodeByCityId($modelLeadsAdvisor->lead_city);
                $modelLeadsAdvisor->lead_city = Yii::$app->userdata->getCityCodeById($modelLeadsAdvisor->lead_city);
                $modelLeadsAdvisor->pincode = 0;
                $modelLeadsAdvisor->ref_status = 6;
                $modelLeadsAdvisor->contact_number = $model->phone;
                $modelLeadsAdvisor->created_by = $model->id;
                $modelLeadsAdvisor->branch_code = $modelAdvisorProfile->branch_code;
                $modelLeadsAdvisor->save(false);
                
               
                $modelAdvisorProfile->state = $modelLeadsAdvisor->lead_state;
                $modelAdvisorProfile->city = $modelLeadsAdvisor->lead_city;
                $modelAdvisorProfile->pincode = 0;
                $modelAdvisorProfile->comment = null;

                $modelAdvisorProfile->email_id = $model->login_id;
                $modelAdvisorProfile->address_line_1 = null;
                $modelAdvisorProfile->address_line_2 = null;
                $modelAdvisorProfile->advisor_id = $model->id;
                $modelAdvisorProfile->phone = $model->phone;
                $modelAdvisorProfile->save(false);

                $userList = Array();
                $userList[] = $opUser->email;
                $userList[] = Yii::$app->params['queryEmail'];
                $subject = 'New property advisor lead';
                $msg = "Hello,<br/><br/>New property advisor lead for $model->full_name has been created, please verify and assign the branch and sales person to this lead.<br/><br/>Contact details of property owner are $modelLeadsAdvisor->contact_number,$model->login_id<br/>With Regards,<br/>Easyleases Team";
                @Yii::$app->userdata->doMail(implode(",", $userList), $subject, $msg);

                Yii::$app->getSession()->setFlash('ownerFormSubmitted', 'Thanks for showing your interest in partnering with Easyleases as property advisor. Our Sales team will be in touch with you within next 24 hours.');

                //return $this->refresh();
                return $this->redirect(['/thankyou']);
            } else {
                // print_r($modelAdvisorProfile->getErrors());
                // die;
                return $this->render('property_advisor', [
                            'model' => $model,
                            'modelLeadsAdvisor' => $modelLeadsAdvisor,
                            'modelAdvisorProfile' => $modelAdvisorProfile,
                ]);
            }
        }
        return $this->render('property_advisor', [
                    'model' => $model,
                    'modelLeadsAdvisor' => $modelLeadsAdvisor,
                    'modelAdvisorProfile' => $modelAdvisorProfile,
        ]);
    }

    /*
     * * function for user property owner registration
     */

    public function actionOwner() {
        $this->view->params['head_title'] = "Property Management Services | Rental Management | Property Management";
        $this->view->params['meta_desc'] = "Property Management Services, Rental Management, Property Management, Rental Property Management, Rental Management Companies, Tenant Management.";
        $this->layout = 'signup';
        $comments = new \app\models\Comments;
        $model = new \app\models\Users;
        $modelLeadsOwner = new \app\models\LeadsOwner;
        $modelOwnerProfile = new \app\models\OwnerProfile;
        
        //if ($model->load(Yii::$app->request->post()) && $modelLeadsOwner->load(Yii::$app->request->post()) && $modelOwnerProfile->load(Yii::$app->request->post())) {
        if ($model->load(Yii::$app->request->post()) && $modelLeadsOwner->load(Yii::$app->request->post()) && $modelOwnerProfile->load(Yii::$app->request->post())) {
            //$transaction = Yii::$app->db->beginTransaction();
            $modelLeadsOwner->contact_number = Yii::$app->userdata->trimPhone($modelLeadsOwner->contact_number);
            $rand = Yii::$app->userdata->passwordGenerate();
            $model->auth_key = base64_encode($model->login_id . time() . rand(1000, 9999));
            $model->username = $model->login_id;
            $model->password = md5($rand);
            $model->user_type = 4;
            $model->created_date = date('Y-m-d h:i:s');
            $model->role = 0;
            
            //if ($model->validate(['full_name', 'login_id']) && $modelLeadsOwner->validate(['state', 'city', 'region', 'pincode']) && $modelOwnerProfile->validate(['ow_interested', 'ow_comments', 'sales_id'])) {
            if ($model->validate(['full_name', 'login_id','phone']) && $modelLeadsOwner->validate(['lead_city'])) {
                $model->save(false);
                $cityCode = Yii::$app->userdata->getCityCodeById($modelLeadsOwner->lead_city);
                if (!empty(Yii::$app->request->post('sales_email'))) {
                    $salesget = \app\models\Users::findOne(['login_id' => $_POST['sales_email'], 'user_type' => '6']);
                    if (count($salesget) != 0) {
                        $salesUser = \app\models\SalesProfile::findOne(['sale_id' => $salesget->id]);
                        if (count($salesUser) != 0) {
                            if ($salesUser->role_code == 'SLCTMG') {
                                $modelOwnerProfile->branch_code = null;
                                $opUser = \app\models\OperationsProfile::findOne(['role_code' => 'OPCTMG', 'role_value' => $cityCode]);
                                if (count($opUser) != 0) {
                                    $modelOwnerProfile->operation_id = $opUser->operations_id;
                                } else {
                                    $modelOwnerProfile->operation_id = null;
                                }
                            } else if ($salesUser->role_code == 'SLBRMG' || $salesUser->role_code == 'SLEXE') {
                                $modelOwnerProfile->branch_code = $salesUser->role_value;
                                $opUser = \app\models\OperationsProfile::findOne(['role_code' => 'OPBRMG', 'role_value' => $salesUser->role_value]);
                                if (count($opUser) != 0) {
                                    $modelOwnerProfile->operation_id = $opUser->operations_id;
                                } else {
                                    $modelOwnerProfile->operation_id = null;
                                }
                            }
                        }
                        
                        $modelOwnerProfile->sales_id = $salesget->id;
                    } else {
                        Yii::$app->getSession()->setFlash('sales_email_error', 'Please enter a valid sales email.');
                        return $this->refresh();
                    }
                } else {
                    $salesUser = \app\models\SalesProfile::findOne(['role_code' => 'SLCTMG', 'role_value' => $cityCode]);
                    if (count($salesUser) != 0) {
                        $modelOwnerProfile->sales_id = $salesUser->sale_id;
                    } else {
                        $modelOwnerProfile->sales_id = null;
                    }
                    
                    $modelOwnerProfile->branch_code = null;
                    
                    $opUser = \app\models\OperationsProfile::findOne(['role_code' => 'OPCTMG', 'role_value' => $cityCode]);
                    if (count($opUser) != 0) {
                        $modelOwnerProfile->operation_id = $opUser->operations_id;
                    } else {
                        $modelOwnerProfile->operation_id = null;
                    }
                }
                
                $modelOwnerProfile->owner_id = $model->id;
                $modelOwnerProfile->state = null;
                $modelOwnerProfile->region = 0;
                $modelOwnerProfile->pincode = 0;
                $modelOwnerProfile->phone = $modelLeadsOwner->contact_number;
                $modelOwnerProfile->address_line_1 = null;
                $modelOwnerProfile->address_line_2 = null;
                $modelOwnerProfile->phone = $model->Phone;
                $modelOwnerProfile->save(false);

                $modelLeadsOwner->full_name = $model->full_name;
                $modelLeadsOwner->email_id = $model->login_id;
                $modelLeadsOwner->contact_number = $model->phone;
                $modelLeadsOwner->lead_state = Yii::$app->userdata->getStateCodeByCityId($modelLeadsOwner->lead_city);
                $modelLeadsOwner->lead_city = Yii::$app->userdata->getCityCodeById($modelLeadsOwner->lead_city);
                $modelLeadsOwner->branch_code = $modelOwnerProfile->branch_code;
                $modelLeadsOwner->pincode = $modelOwnerProfile->pincode;
                $modelLeadsOwner->created_date = date('Y-m-d H:i:s');
                $modelLeadsOwner->save(false);
                
                $userList = Array();
                $userList[] = $opUser->email;
                $userList[] = Yii::$app->params['queryEmail'];
                $subject = 'New property owner lead';
                $msg = "Hello,<br/><br/>New property owner lead for $model->full_name has been created, please verify and assign the branch and sales person to this lead.<br/><br/>Contact details of property owner are $modelLeadsOwner->contact_number,$model->login_id<br/>With Regards,<br/>Easyleases Team";
                @Yii::$app->userdata->doMail(implode(",", $userList), $subject, $msg);
                Yii::$app->getSession()->setFlash('ownerFormSubmitted', 'Thanks for showing your interest in partnering with Easyleases as property owner. Our Sales team will be in touch with you within next 24 hours.');
                //return $this->refresh();
                return $this->redirect(['/thankyou']);
            } else {
                return $this->render('property_owner', [
                            'model' => $model,
                            'modelLeadsOwner' => $modelLeadsOwner,
                            'modelOwnerProfile' => $modelOwnerProfile,
                ]);
            }
        }
        return $this->render('property_owner', [
                    'model' => $model,
                    'modelLeadsOwner' => $modelLeadsOwner,
                    'modelOwnerProfile' => $modelOwnerProfile,
        ]);
    }

    /*
     * * function for user Registration
     */

    public function actionRegister() {

        $this->layout = 'signup';
        $transaction = Yii::$app->db->beginTransaction();
        $model = new Users();
        $modelApplicant = new \app\models\ApplicantProfile();
        $modelTenant = new \app\models\LeadsTenant();
        $modelWallet = new \app\models\Wallets();
        
        if ($model->load(Yii::$app->request->post()) && $modelTenant->load(Yii::$app->request->post())) {
            try {
                $modelTenant->contact_number = Yii::$app->userdata->trimPhone($modelTenant->contact_number);
                $model->role = 0;
                $model->user_type = 2;
                $model->username = $model->login_id;
                $model->created_date = date('Y-m-d H:i:s');
                $userPassword = '';
                $modelApplicant->phone = $model->phone;
                
                if ($model->validate(['full_name', 'login_id', 'city', 'phone']) && $modelTenant->validate(['lead_city'])) {
                    $model->auth_key = base64_encode($model->login_id . time() . rand(1000, 9999));
                    $userPassword = $model->password;
                    $model->password = md5($model->password);
                    if( !$model->save(false) ){
                        throw new Exception('Exception');
                    }

                    $modelWallet->amount = 0;
                    $modelWallet->user_id = $model->id;
                    $modelWallet->created_by = $model->id;
                    $modelWallet->created_date = Date('Y-m-d H:i:s');
                    if( !$modelWallet->save(false) ){
                        throw new Exception('Exception');
                    }

                    $applicantProfile = new \app\models\ApplicantProfile;
                    $applicantProfile->applicant_id = $model->id;
                    $applicantProfile->email_id = $model->login_id;
                    $applicantProfile->phone = $model->phone;
                    
                    $saleUser = \app\models\SalesProfile::find()->where(['role_code' => 'SLCTMG', 'role_value' => $modelTenant->lead_city])->one();
                    if ($saleUser) {
                        $applicantProfile->sales_id = $saleUser->sale_id;
                    }
                    
                    $opUser = \app\models\OperationsProfile::find()->where(['role_code' => 'OPCTMG', 'role_value' => $modelTenant->lead_city])->one();
                    if ($opUser) {
                        $applicantProfile->operation_id = $opUser->operations_id;
                    }
                    
                    if( !$applicantProfile->save(false) ){
                        throw new Exception('Exception');
                    }

                    $modelTenant->full_name = $model->full_name;
                    $modelTenant->email_id = $model->login_id;
                    $modelTenant->lead_state = Yii::$app->userdata->getStateCodeByCityCode($modelTenant->lead_city);
                    $modelTenant->contact_number = $applicantProfile->phone;
                    if( !$modelTenant->save(false) ){
                        throw new Exception('Exception');
                    }
                    
                    $urlUserCred = [];
                    $urlUserCred['username'] = $model->login_id;
                    $urlUserCred['password'] = $userPassword;
                    
                    $urlUserCred = json_encode($urlUserCred);
                    
                    $encData = Yii::$app->getSecurity()->encryptByKey($urlUserCred, Yii::$app->params['hash_key']);

                    $href = Yii::$app->urlManager->createAbsoluteUrl(['users/confirm', 'id' => base64_encode($model->id), 'key' => $model->auth_key, 'd' => $encData]);
                    $link = '<a href="' . $href . '">confirm</a>';

                    $emailbody = "Hello " . $model->full_name . "<br/><br/>Thank You for signing up with EasyLeases. Your dream home is just a click away. <br/>
                    Click " . $link . " to activate your account. <br/><br/>With Regards,<br/>EasyLeases Team";
                    
                    if( !Yii::$app->userdata->doMail(trim($model->login_id), 'Your Easyleases Account Verification', $emailbody) ){
                        Yii::$app->getSession()->setFlash('registerFormSubmitted', 'Something went wrong, please try after sometime.');
                        throw new Exception('Exception');
                    } else {
                        Yii::$app->getSession()->setFlash('registerFormSubmitted', 'Thank you for registering as a applicant! Please check your email and verify your account.');
                    }

                    $users = \app\models\Users::findAll(['user_type' => '7']);
                    $userList = Array();


                    $msg = "Hello,<br/><br/>A new applicant has signed up. Please verify the applicant details and requirements and assign the lead to appropriate branch and sales person.<br/><br/>With Regards,<br/>EasyLeases Team<br/>";
                    foreach ($users as $key => $value) {
                        $userList[] = $value->login_id;
                        if( !Yii::$app->userdata->doMail(trim($value->login_id), 'New Applicant Lead', $msg) ){
                            throw new Exception('Exception');
                        }
                    }
                    
                    $transaction->commit();

                    return $this->refresh();
                } else {
                    return $this->render('user_signup', [
                                'model' => $model, 'modelApplicant' => $modelApplicant, 'modelTenant' => $modelTenant
                    ]);
                }
                
                //$transaction->commit();
            } catch (\Exception $e) {
                print_r($e->getMessage()); exit;
                $transaction->rollBack();
                Yii::$app->getSession()->setFlash('registerFormSubmitted', 'Something went wrong, please try after sometime.');
                return $this->render('user_signup', [
                    'model' => $model, 'modelApplicant' => $modelApplicant, 'modelTenant' => $modelTenant
                ]);
            }
        }
        return $this->render('user_signup', [
                    'model' => $model, 'modelApplicant' => $modelApplicant, 'modelTenant' => $modelTenant
        ]);
    }

    /*
     * * function for user forget password
     */

    public function actionForgetpassword($key) {
        $this->layout = 'signup';

        $model = new ForgotPassword;
        $modeluser = Users::find()->where([
                    'login_id' => base64_decode($key)
                ])->one();

        if ($modeluser) {
            if ($model->load(Yii::$app->request->post())) {

                $model->email = base64_decode($key);
                if ($model->validate()) {
                    try {

                        $modeluser->password = md5($_POST['ForgotPassword']['newpass']);
                        if ($modeluser->save(false)) {
                            Yii::$app->getSession()->setFlash(
                                    'success', 'Your password changed successfully'
                            );
                            return $this->redirect(['site/login?ac=cp']);
                        } else {
                            Yii::$app->getSession()->setFlash(
                                    'error', 'Password not changed, please try again'
                            );
                            return $this->redirect(['site/login?ac=cn']);
                        }
                    } catch (Exception $e) {
                        Yii::$app->getSession()->setFlash(
                                'success', "{$e->getMessage()}"
                        );
                        return $this->render('forgot_password', [
                                    'model' => $model, 'email' => base64_decode($key)
                        ]);
                    }
                } else {
                    return $this->render('forgot_password', [
                                'model' => $model, 'email' => base64_decode($key)
                    ]);
                }
            } else {
                return $this->render('forgot_password', [
                            'model' => $model, 'email' => base64_decode($key)
                ]);
            }
        } else {
            Yii::$app->getSession()->setFlash(
                    'registerFormSubmitted', 'No user found,Please register here.'
            );
            return $this->redirect(['register']);
        }
    }

    /*
     * * function for user feedback
     */

    public function actionFeedback() {

        $model = new Feedback();
        echo '<pre>';
        print_r($model);
        die('testere');
    }

    public function actionChangepassword() {
		//echo "hello";die;
		$userid = Yii::$app->user->id;
		$cpsd = $_POST['current_password'];

		 $query = "Select * from users where password = '".md5($cpsd)."'";
        $connection = \Yii::$app->db;
        $model = $connection->createCommand($query);
        $users = $model->queryOne();
        $action = $_POST['actionname'];
        $type = $_POST['typename'];
		
		if($users)
		{
		$usersmodel = \app\models\Users::find()->where(['id' => $userid])->one();
		$usersmodel->password = md5($_POST['new_password']);
                $usersmodel->pass_up_date = date('Y-m-d H:i:s');
                $usersmodel->save(false);
		// session_destroy();
                //Yii::$app->user->logout();
        return $this->redirect(['site/login?type=c']);
		}
		else{
			if($type=='')
			{
				 return $this->redirect(['site/'.$action]);
			}
			else{
				 return $this->redirect(['site/'.$action.'?type='.$type]);
			}
		}
		

    }

    public function actionAdvisorpassword() {

        $this->layout = 'advisor_dashboard';


        $model = new PasswordForm;
        $modeluser = Users::find()->where([
                    'username' => Yii::$app->user->identity->username
                ])->one();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                try {
                    $modeluser->password = md5($_POST['PasswordForm']['newpass']);
                    if ($modeluser->save(false)) {
                        Yii::$app->getSession()->setFlash(
                                'success', 'Password changed'
                        );
                        return $this->redirect(['changepassword']);
                    } else {
                        Yii::$app->getSession()->setFlash(
                                'success', 'Password not changed'
                        );
                        return $this->redirect(['changepassword']);
                    }
                } catch (Exception $e) {
                    Yii::$app->getSession()->setFlash(
                            'success', "{$e->getMessage()}"
                    );
                    return $this->render('change_password', [
                                'model' => $model
                    ]);
                }
            } else {
                return $this->render('change_password', [
                            'model' => $model
                ]);
            }
        } else {
            return $this->render('change_password', [
                        'model' => $model
            ]);
        }
    }

    public function actionOwnerpassword() {

        $this->layout = 'owner_dashboard';


        $model = new PasswordForm;
        $modeluser = Users::find()->where([
                    'username' => Yii::$app->user->identity->username
                ])->one();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                try {
                    $modeluser->password = md5($_POST['PasswordForm']['newpass']);
                    if ($modeluser->save(false)) {
                        Yii::$app->getSession()->setFlash(
                                'success', 'Password changed'
                        );
                        return $this->redirect(['changepassword']);
                    } else {
                        Yii::$app->getSession()->setFlash(
                                'success', 'Password not changed'
                        );
                        return $this->redirect(['changepassword']);
                    }
                } catch (Exception $e) {
                    Yii::$app->getSession()->setFlash(
                            'success', "{$e->getMessage()}"
                    );
                    return $this->render('change_password', [
                                'model' => $model
                    ]);
                }
            } else {
                return $this->render('change_password', [
                            'model' => $model
                ]);
            }
        } else {
            return $this->render('change_password', [
                        'model' => $model
            ]);
        }
    }

    public function actionGetsales() {
        $value = str_replace(" ", "%%", trim($_POST['val']));
        $sales = \app\models\Users::find()->where('full_name like "%' . $value . '%"')->andWhere(['user_type' => 6])->all();
        $sales_array = Array();
        if (count($sales) != 0) {
            foreach ($sales as $key => $value1) {
                $sales_array[] = Array('id' => $value1->id, 'name' => $value1->full_name);
            }
        }
        return json_encode($sales_array);
    }
	public function actionGetcities() {

        $id = Yii::$app->request->post('state');
        $model = \app\models\Cities::find()->where(['state_id' => $id])->all();
        $listData = \yii\helpers\ArrayHelper::map($model, 'id', 'city_name');

        $html = '<select id="city" class="btn btn-primary dropdown-toggle mydropdown" name="LeadsAdvisor[city]"><option value="">Select City</option>';
        foreach ($listData as $key => $list) {
            $html .= '<option value="' . $key . '">' . $list . '</option></select>';
        }
        echo $html;
        exit;
    }
	
	public function actionGetcityt() {

        $id = (empty(Yii::$app->request->post('state'))) ? @Yii::$app->request->post('val') : @Yii::$app->request->post('state') ;
        $model = \app\models\Cities::find()->where(['state_id' => $id])->all();
        $listData = \yii\helpers\ArrayHelper::map($model, 'id', 'city_name');

        $html = '<b id="bold">City *</b><select name="LeadsTenant[city]" class="form-control"><option value=""> Select city </option>';
        foreach ($listData as $key => $list) {
            $html .= '<option value="' . $key . '">' . $list . '</option></select>';
        }
        echo $html;
        die;
    }
	
	public function actionGetcityp() {

        $id = (empty(Yii::$app->request->post('state'))) ? @Yii::$app->request->post('val') : @Yii::$app->request->post('state') ;
        $model = \app\models\Cities::find()->where(['state_id' => $id])->all();
        $listData = \yii\helpers\ArrayHelper::map($model, 'id', 'city_name');

        $html = '<b id="bold">City *</b><select name="LeadsOwner[city]" class="form-control"><option value=""> Select city </option>';
        foreach ($listData as $key => $list) {
            $html .= '<option value="' . $key . '">' . $list . '</option></select>';
        }
        echo $html;
        die;
    }
    
}
