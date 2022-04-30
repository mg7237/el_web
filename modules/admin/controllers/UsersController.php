<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\Users;
use app\models\Advisors;
use app\models\Owner;
use app\models\Register;
use app\models\ApplicantProfile;
use app\models\TenantProfile;
use app\models\AdvisorProfile;
use app\models\OwnerProfile;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\CsvExport;
use yii\helpers\ArrayHelper;

/**
 * UsersController implements the CRUD actions for Users model.
 */
class UsersController extends Controller {

    public $layout = 'main';

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            /*    'verbs' => [
              'class' => VerbFilter::className(),
              'actions' => [
              'delete' => ['post'],
              ],
              ], */
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'actions' => ['index', 'update', 'export', 'create', 'view', 'ajaxstatus', 'updateadmin', 'getroles', 'getstates', 'getcities', 'getbranches', 'delete', 'admin', 'createadmin'],
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            if ($this->isAdmin()) {
                                return true;
                            }
                            return false;
                        }
                    ],
                    // allow authenticated users
                    [
                        'allow' => true,
                        'actions' => ['index', 'update', 'export', 'create', 'view'],
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            if ($this->isUserAuthor()) {
                                return true;
                            }
                            return false;
                        }
                    ],
                // everything else is denied
                ],
            ],
        ];
    }

    protected function findModelUserType($id) {
        if (($model = Users::findOne($id)) !== null) {
            return $model->user_type;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function isUserAuthor() {
        return in_array($this->findModelUserType(Yii::$app->user->id), ['6', '7']);
    }

    protected function isAdmin() {
        return in_array($this->findModelUserType(Yii::$app->user->id), ['1']);
    }

    /**
     * Lists all Users models.
     * @return mixed
     */
    public function actionIndex() {
        $params = Yii::$app->request->queryParams;

        if (isset($params['LeadsTenant']['sort'])) {
            if ($params['LeadsTenant']['sort'] == '') {
                $sort = 'users.id';
            } else {
                $sort = $params['LeadsTenant']['sort'];
            }
        } else {
            $sort = 'users.id';
        }

        $searchModelOwnerLeads = new \app\models\Users();
        $assinee = \app\models\Users::find()->select('users.*')
                ->where('users.user_type IN (2,3,4,5)')
                ->leftJoin('applicant_profile', '(users.user_type=2 AND users.id= applicant_profile.applicant_id)')
                ->leftJoin('owner_profile', '(users.user_type=4 AND users.id= owner_profile.owner_id)')
                ->leftJoin('tenant_profile', '(users.user_type=3 AND users.id= tenant_profile.tenant_id)')
                ->leftJoin('advisor_profile', '(users.user_type=5 AND users.id= advisor_profile.advisor_id)')
        ;

        $dataProvider = new ActiveDataProvider([
            'query' => $assinee,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => false
        ]);
        if (isset($params['LeadsAdvisor'])) {
            $searchParam = $params['LeadsAdvisor']['search'];

            $assinee->andFilterWhere(['or',
                ['like', 'users.full_name', trim($searchParam)],
                ['like', 'users.login_id', trim($searchParam)],
                ['like', 'applicant_profile.phone', trim($searchParam)],
                ['like', 'owner_profile.phone', trim($searchParam)],
                ['like', 'tenant_profile.phone', trim($searchParam)],
                ['like', 'advisor_profile.phone', trim($searchParam)],
                    ]
            );
        }

        $assinee->orderBy($sort);

        return $this->render('index', [
                    'dataProvider' => $dataProvider,
                    'searchModelOwnerLeads' => $searchModelOwnerLeads,
        ]);
    }

    /**
     * Lists all Users models.
     * @return mixed
     */
    public function actionAdmin() {
        $params = Yii::$app->request->queryParams;

        if (isset($params['LeadsTenant']['sort'])) {
            if ($params['LeadsTenant']['sort'] == '') {
                $sort = 'users.id';
            } else {
                $sort = $params['LeadsTenant']['sort'];
            }
        } else {
            $sort = 'users.id';
        }

        $assinee = \app\models\Users::find()->select('users.*')
                ->where('users.user_type IN (1,6,7)')
                ->andWhere('users.id !=' . Yii::$app->user->id)
                ->leftJoin('sales_profile', '(users.user_type=6 AND users.id= sales_profile.sale_id)')
                ->leftJoin('admin_profile', '(users.user_type=1 AND users.id= admin_profile.admin_id)')
                ->leftJoin('operations_profile', '(users.user_type=7 AND users.id= operations_profile.operations_id)')
        ;
        $dataProvider = new ActiveDataProvider([
            'query' => $assinee,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => false
        ]);

        if (isset($params['LeadsAdvisor'])) {
            $searchParam = $params['LeadsAdvisor']['search'];

            $assinee->andFilterWhere(['or',
                ['like', 'users.full_name', trim($searchParam)],
                ['like', 'users.login_id', trim($searchParam)],
                ['like', 'sales_profile.phone', trim($searchParam)],
                ['like', 'operations_profile.phone', trim($searchParam)],
                ['like', 'admin_profile.phone', trim($searchParam)],
                    ]
            );
        }


        $assinee->orderBy($sort);

        return $this->render('admin', [
                    'dataProvider' => $dataProvider,
        ]);
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
     * Displays a single Users model.
     * @param integer $id
     * @return mixed
     */
    public function actionGetroles() {

        $id = Yii::$app->request->post('val');
        $model = \app\models\Roles::find()->where(['user_type' => $id])->all();
        //$listData = ArrayHelper::map($model, 'id', 'role_name');
        $listData = ArrayHelper::map($model, 'role_code', 'role_name');

        $html = '';
        $html .= '<option value="">Select role</option>';
        foreach ($listData as $key => $list) {
            $html .= '<option value="' . $key . '">' . $list . '</option>';
        }
        echo $html;
        die;
    }
    
    public function actionGetstates () {
        $id = Yii::$app->request->post('val');
        $model = \app\models\States::find()->all();
        $listData = ArrayHelper::map($model, 'code', 'name');

        $html = '';
        $html .= '<option value="">Select state</option>';
        foreach ($listData as $key => $list) {
            $html .= '<option value="' . $key . '">' . $list . '</option>';
        }
        echo $html;
        die;
    }
    
    public function actionGetcities () {
        $id = Yii::$app->request->post('val');
        $model = \app\models\Cities::find()->where(['state_code' => $id])->all();
        $listData = ArrayHelper::map($model, 'code', 'city_name');

        $html = '';
        $html .= '<option value="">Select city</option>';
        foreach ($listData as $key => $list) {
            $html .= '<option value="' . $key . '">' . $list . '</option>';
        }
        echo $html;
        die;
    }
    
    public function actionGetbranches () {
        $state = Yii::$app->request->post('state');
        $city = Yii::$app->request->post('city');
        $model = \app\models\Branches::find()->where(['state_code' => $state])->andWhere(['city_code' => $city])->all();
        $listData = ArrayHelper::map($model, 'branch_code', 'name');
        $html = '';
        $html .= '<option value="">Select branch</option>';
        foreach ($listData as $key => $list) {
            $html .= '<option value="' . $key . '">' . $list . '</option>';
        }
        echo $html;
        die;
    }

    /**
     * Displays a single Users model.
     * @param integer $id
     * @return mixed
     */
    public function actionAjaxstatus() {

        $id = Yii::$app->request->post('id');
        $status = Yii::$app->request->post('status');

        $model = $this->findModel($id);

        if (isset($model) && !empty($model)) {

            if ($status == 1) {

                $model->status = 0;
                $model->save(false);
                $test = "deactive";
            } else {

                $model->status = 1;
                $model->save(false);
                $test = "active";
            }
        } else {
            $test = "Ajax failed";
        }
        return \yii\helpers\Json::encode($test);
    }

    /**
     * Creates a new Users model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Users();
        $modelApplicant = new \app\models\ApplicantProfile();
        $modelOwner = new \app\models\OwnerProfile();
        //$modelTenant = new \app\models\TenantProfile();
        $modelAdvisor = new \app\models\AdvisorProfile();
        $modelLeadsAdvisor = new \app\models\LeadsAdvisor();
        $modelLeadsOwner = new \app\models\LeadsOwner();
        $modelLeadsTenant = new \app\models\LeadsTenant();
        $model->scenario = 'insert';

        if ($model->load(Yii::$app->request->post())) {

            $model->created_date = date('Y-m-d H:i:s');
            $model->status = 1;
            $model->role = 0;
            $model->auth_key = '0';
            $model->password_reset_token = '0';
            $model->identification = '0';
            $model->occupation = '0';
            $model->updated_date = '1970-01-01 00:00:00';
            $model->username = $model->login_id;
            $model->password = Yii::$app->userdata->passwordGenerate();
            $model->password_repeat = $model->password;
            if ($model->validate()) {

                $model->password = md5($model->password);
                $model->save(false);
                if ($model->user_type == 2) {


                    $modelApplicant->load(Yii::$app->request->post());

                    $modelLeadsTenant->email_id = $model->login_id;
                    $modelLeadsTenant->full_name = $model->full_name;
                    $modelLeadsTenant->contact_number = $modelApplicant->phone;
                    $modelLeadsTenant->address = "";
                    $modelLeadsTenant->address_line_2 = "";
                    $modelLeadsTenant->city = "0";
                    $modelLeadsTenant->state = "0";
                    $modelLeadsTenant->region = "0";
                    $modelLeadsTenant->pincode = "0";
                    $modelLeadsTenant->communication = "";
                    $modelLeadsTenant->ref_status = "";
                    $modelLeadsTenant->reffered_by = "";
                    $modelLeadsTenant->user_type = "2";
                    $modelLeadsTenant->save(false);

                    $modelApplicant->applicant_id = $model->id;
                    $modelApplicant->email_id = $model->login_id;
                    $modelApplicant->emergency_contact_name = '';
                    $modelApplicant->emergency_contact_email = '';
                    $modelApplicant->proof_type = '';
                    $modelApplicant->proof_document_url = '';
                    $modelApplicant->account_holder_name = '';
                    $modelApplicant->bank_name = '';
                    $modelApplicant->employment_email = '';
                    $modelApplicant->employmnet_proof_url = '';
                    $modelApplicant->employmnet_proof_type = '';
                    $modelApplicant->emergency_contact_number = '';
                    $modelApplicant->address_line_1 = '';
                    $modelApplicant->address_line_2 = '';
                    $modelApplicant->state = 0;
                    $modelApplicant->city = 0;
                    $modelApplicant->pincode = 0;
                    $modelApplicant->bank_branchname = '';
                    $modelApplicant->bank_ifcs = '';
                    $modelApplicant->bank_account_number = '';
                    $modelApplicant->pan_number = '';
                    $modelApplicant->cancelled_check = '';
                    $modelApplicant->employer_name = '';
                    $modelApplicant->employee_id = '';
                    $modelApplicant->employment_start_date = date('Y-m-d');
                    $modelApplicant->employment_email = '';
                    $modelApplicant->employmnet_proof_url = '';
                    $modelApplicant->employmnet_proof_type = '';
                    $modelApplicant->emergency_contact_number = '';
                    $modelApplicant->pan_number = '';
                    $modelApplicant->region = 0;
                    $modelApplicant->save(false);
                } elseif ($model->user_type == 4) {
                    $modelOwner->load(Yii::$app->request->post());
                    $modelOwner->owner_id = $model->id;
                    $modelOwner->ow_interested = $model->login_id;
                    $modelOwner->ow_comments = '';
                    $modelOwner->emer_contact = '';
                    $modelOwner->status = '0';
                    $modelOwner->sales_id = 0;
                    $modelOwner->operation_id = 0;
                    $modelOwner->save(false);
                } elseif ($model->user_type == 5) {
                    $modelAdvisor->load(Yii::$app->request->post());
                    $modelAdvisor->advisor_id = $model->id;
                    $modelAdvisor->ad_properties = $model->login_id;
                    $modelAdvisor->ad_company = '';
                    $modelAdvisor->emer_contact = '';
                    $modelAdvisor->sales_id = 0;
                    $modelAdvisor->operation_id = 0;
                    $modelAdvisor->address_line_2 = '';
                    $modelAdvisor->state = 0;
                    $modelAdvisor->city = 0;
                    $modelAdvisor->pincode = 0;
                    $modelAdvisor->save(false);
                }

                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                            'model' => $model, 'modelApplicant' => $modelApplicant, 'modelAdvisor' => $modelAdvisor, 'modelOwner' => $modelOwner,
                ]);
            }
        } else {
            return $this->render('create', [
                        'model' => $model, 'modelApplicant' => $modelApplicant, 'modelAdvisor' => $modelAdvisor, 'modelOwner' => $modelOwner,
            ]);
        }
    }

    /**
     * Creates a new Users model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateadmin() {
        $model = new Users();
        $modelSales = new \app\models\SalesProfile();
        $modelOperations = new \app\models\OperationsProfile();
        $model->scenario = 'insert';
        $error = false;

        if ($model->load(Yii::$app->request->post())) {
            $model->created_date = date('Y-m-d H:i:s');
            $model->status = 1;
            $model->auth_key = '0';
            $model->password_reset_token = '0';
            $model->identification = '0';
            $model->occupation = '0';
            $model->updated_date = '1970-01-01 00:00:00';
            $model->username = $model->login_id;
            $model->created_by = Yii::$app->user->id;
            $model->password = trim(Yii::$app->userdata->passwordGenerate());
            $orgPass = $model->password;
            $model->password_repeat = $model->password;
            $validator = new \yii\validators\RequiredValidator();
            if (!$model->validate() || !$validator->validate(Yii::$app->request->post('Users')['user_type'], $error)) {
                $model->addError('user_type', 'User Type is required');
                $error = true;
            }
            
            if (!$validator->validate(Yii::$app->request->post('Users')['role'], $error)) {
                $model->addError('role', 'Role is required');
                $error = true;
            }
            
            if (!$validator->validate(Yii::$app->request->post('Users')['state'], $error)) {
                $model->addError('state', 'State is required');
                $error = true;
            }
            
            if ((Yii::$app->request->post('Users')['role'] != 'SLSTMG' && Yii::$app->request->post('Users')['role'] != 'OPSTMG') || (Yii::$app->request->post('Users')['role'] == '')) {
                if (!$validator->validate(Yii::$app->request->post('Users')['city'], $error)) {
                    $model->addError('city', 'City is required');
                    $error = true;
                }
            }
            
            if (!$validator->validate(Yii::$app->request->post('Users')['address_line_1'], $error)) {
                $model->addError('address_line_1', 'Address line 1 is required');
                $error = true;
            }
            
            if (!$validator->validate(Yii::$app->request->post('Users')['address_line_2'], $error)) {
                $model->addError('address_line_2', 'Address line 2 is required');
                $error = true;
            }
            
            if (!$error) {
                $model->password = md5($model->password);
                $roleCode = Yii::$app->request->post('Users')['role'];
                $model->role = Yii::$app->userdata->getRoleIdByRoleCode($roleCode);
                $model->save(false);
                $stateId = \app\models\States::find()->where(['code' => Yii::$app->request->post('Users')['state']])->one();
                $cityId = \app\models\Cities::find()->where(['code' => Yii::$app->request->post('Users')['city']])->one();
                if ($model->user_type == '7') {
                    $modelOperations->load(Yii::$app->request->post());
                    $modelOperations->operations_id = $model->id;
                    $modelOperations->email = $model->login_id;
                    $modelOperations->address_line_1 = Yii::$app->request->post('Users')['address_line_1'];
                    $modelOperations->address_line_2 = Yii::$app->request->post('Users')['address_line_2'];
                    $modelOperations->state = Yii::$app->request->post('Users')['state'];
                    $modelOperations->city = Yii::$app->request->post('Users')['city'];
                    $modelOperations->pincode = 0;
                    $modelOperations->role_code = $roleCode;
                    if ($roleCode == 'SLEXE' || $roleCode == 'SLBRMG' || $roleCode == 'OPEXE' || $roleCode == 'OPBRMG') {
                        $modelOperations->role_value = Yii::$app->request->post('Users')['branch'];
                    } else if ($roleCode == 'SLCTMG' || $roleCode == 'OPCTMG') {
                        $modelOperations->role_value = Yii::$app->request->post('Users')['city'];
                    } else if ($roleCode == 'SLSTMG' || $roleCode == 'OPSTMG') {
                        $modelOperations->role_value = Yii::$app->request->post('Users')['state'];
                    }
                    if ($_FILES['Users']['size']['profile_image'] > '0') {
                        $profileImgPath = str_replace(' ', '', 'uploads/' . time() . '_' . $_FILES['Users']['name']['profile_image']);
                        if (move_uploaded_file($_FILES['Users']['tmp_name']['profile_image'], $profileImgPath)) {
                            $modelOperations->profile_image = $profileImgPath;
                        }
                    }
                    $modelOperations->save(false);
                } else {
                    $modelSales->load(Yii::$app->request->post());
                    $modelSales->sale_id = $model->id;
                    $modelSales->email = $model->login_id;
                    $modelSales->status = '0';
                    $modelSales->address_line_1 = Yii::$app->request->post('Users')['address_line_1'];
                    $modelSales->address_line_2 = Yii::$app->request->post('Users')['address_line_2'];

                    $modelSales->state = Yii::$app->request->post('Users')['state'];
                    $modelSales->city = Yii::$app->request->post('Users')['city'];
                    $modelSales->pincode = 0;
                    $modelSales->role_code = $roleCode;
                    if ($roleCode == 'SLEXE' || $roleCode == 'SLBRMG' || $roleCode == 'OPEXE' || $roleCode == 'OPBRMG') {
                        $modelSales->role_value = Yii::$app->request->post('Users')['branch'];
                    } else if ($roleCode == 'SLCTMG' || $roleCode == 'OPCTMG') {
                        $modelSales->role_value = Yii::$app->request->post('Users')['city'];
                    } else if ($roleCode == 'SLSTMG' || $roleCode == 'OPSTMG') {
                        $modelSales->role_value = Yii::$app->request->post('Users')['state'];
                    }
                    if ($_FILES['Users']['size']['profile_image'] > '0') {
                        $profileImgPath = str_replace(' ', '', 'uploads/' . time() . '_' . $_FILES['Users']['name']['profile_image']);
                        if (move_uploaded_file($_FILES['Users']['tmp_name']['profile_image'], $profileImgPath)) {
                            $modelSales->profile_image = $profileImgPath;
                        }
                    }
                    $modelSales->save(false);
                }
                
                $subject = "Succesfully registered at Easyleases Technologies Pvt Ltd";
                $msg = ''
                        . 'Dear '.$model->full_name
                        . '<br /><br />'
                        . 'You have been successfully regitered at http://www.easyleases.in/ your login details are given below.'
                        . '<br />'
                        . 'Username: '. strtolower($model->login_id)
                        . '<br />'
                        . 'Password: '.$orgPass
                        . '<br />'
                        . 'For any support/help please try to connect with us at support@easyleases.in'
                        . '<br /><br />'
                        . 'Warm Regards! <br />'
                        . 'Easyleases Team';
                
                Yii::$app->userdata->doMail(strtolower($model->login_id), $subject, $msg);
                
                return $this->redirect(['admin']);
            } else {
                return $this->render('createadmin', [
                            'model' => $model, 'modelSales' => $modelSales, 'modelOperations' => $modelOperations
                ]);
            }
        } else {
            return $this->render('createadmin', [
                        'model' => $model, 'modelSales' => $modelSales, 'modelOperations' => $modelOperations
            ]);
        }
    }

    /**
     * Creates a new Users model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionUpdateadmin($id, $type) {
        $model = $this->findModelUpdate($id);
        if ($type == 6) {
            $modelSales = $this->findModelSalesUpdate($id);
            $profile = \app\models\SalesProfile::findOne(['sale_id' => $id]);
        } else if ($type == 7) {
            $modelSales = $this->findModelOperationsUpdate($id);
            $profile = \app\models\OperationsProfile::findOne(['operations_id' => $id]);
        }
        

        if ($model->load(Yii::$app->request->post())) {
            //print_r($type); echo '-------';  print_r(Yii::$app->request->post('Usersupdate')['user_type']); exit;
            $stateId = \app\models\States::find()->where(['code' => Yii::$app->request->post('Usersupdate')['state']])->one();
            $cityId = \app\models\Cities::find()->where(['code' => Yii::$app->request->post('Usersupdate')['city']])->one();
            $roleCode = Yii::$app->request->post('Usersupdate')['role'];
            
            if ($_FILES['Usersupdate']['size']['profile_image'] > '0') {
                $profileImgPath = str_replace(' ', '', 'uploads/' . time() . '_' . $_FILES['Usersupdate']['name']['profile_image']);
                if (move_uploaded_file($_FILES['Usersupdate']['tmp_name']['profile_image'], $profileImgPath)) {
                    $modelSales->profile_image = $profileImgPath;
                }
            }
            
            if ($type == 6 && Yii::$app->request->post('Usersupdate')['user_type'] == 7) {
                $profile = \app\models\SalesProfile::findOne(['sale_id' => $id]);
                $opModel = new \app\models\OperationsProfile();
                $opModel->operations_id = $profile->sale_id;
                $opModel->email = Yii::$app->request->post('Usersupdate')['login_id'];
                $opModel->status = $profile->status;
                $opModel->address_line_1 = Yii::$app->request->post('Usersupdate')['address_line_1'];
                $opModel->address_line_2 = Yii::$app->request->post('Usersupdate')['address_line_2'];
                $opModel->state = Yii::$app->request->post('Usersupdate')['state'];
                $opModel->city = Yii::$app->request->post('Usersupdate')['city'];
                $opModel->role_code = Yii::$app->request->post('Usersupdate')['role'];
                if ($roleCode == 'SLEXE' || $roleCode == 'SLBRMG' || $roleCode == 'OPEXE' || $roleCode == 'OPBRMG') {
                    $opModel->role_value = Yii::$app->request->post('Usersupdate')['branch'];
                } else if ($roleCode == 'SLCTMG' || $roleCode == 'OPCTMG') {
                    $opModel->role_value = Yii::$app->request->post('Usersupdate')['city'];
                } else if ($roleCode == 'SLSTMG' || $roleCode == 'OPSTMG') {
                    $opModel->role_value = Yii::$app->request->post('Usersupdate')['state'];
                }
                $opModel->pincode = $profile->pincode;
                $opModel->phone = $profile->phone;
                if ($_FILES['Usersupdate']['size']['profile_image'] > '0') {
                    $opModel->profile_image = $profileImgPath;
                } else {
                    $opModel->profile_image = $profile->profile_image;
                }
                $profile->delete();
                $opModel->save(false);
                $profile = \app\models\OperationsProfile::findOne(['operations_id' => $id]);
            } else if ($type == 7 && Yii::$app->request->post('Usersupdate')['user_type'] == 6) {
                $profile = \app\models\OperationsProfile::findOne(['operations_id' => $id]);
                $opModel = new \app\models\SalesProfile();
                $opModel->sale_id = $profile->operations_id;
                $opModel->email = Yii::$app->request->post('Usersupdate')['login_id'];
                $opModel->status = $profile->status;
                $opModel->address_line_1 = Yii::$app->request->post('Usersupdate')['address_line_1'];
                $opModel->address_line_2 = Yii::$app->request->post('Usersupdate')['address_line_2'];
                $opModel->state = Yii::$app->request->post('Usersupdate')['state'];
                $opModel->city = Yii::$app->request->post('Usersupdate')['city'];
                $opModel->role_code = Yii::$app->request->post('Usersupdate')['role'];
                if ($roleCode == 'SLEXE' || $roleCode == 'SLBRMG' || $roleCode == 'OPEXE' || $roleCode == 'OPBRMG') {
                    $opModel->role_value = Yii::$app->request->post('Usersupdate')['branch'];
                } else if ($roleCode == 'SLCTMG' || $roleCode == 'OPCTMG') {
                    $opModel->role_value = Yii::$app->request->post('Usersupdate')['city'];
                } else if ($roleCode == 'SLSTMG' || $roleCode == 'OPSTMG') {
                    $opModel->role_value = Yii::$app->request->post('Usersupdate')['state'];
                }
                $opModel->pincode = $profile->pincode;
                $opModel->phone = $profile->phone;
                if ($_FILES['Usersupdate']['size']['profile_image'] > '0') {
                    $opModel->profile_image = $profileImgPath;
                } else {
                    $opModel->profile_image = $profile->profile_image;
                }
                $profile->delete();
                $opModel->save(false);
                $profile = \app\models\SalesProfile::findOne(['sale_id' => $id]);
            } else {
                $modelSales->load(Yii::$app->request->post());
                $modelSales->role_code = $roleCode;
                if ($roleCode == 'SLEXE' || $roleCode == 'SLBRMG' || $roleCode == 'OPEXE' || $roleCode == 'OPBRMG') {
                    $modelSales->role_value = Yii::$app->request->post('Usersupdate')['branch'];
                } else if ($roleCode == 'SLCTMG' || $roleCode == 'OPCTMG') {
                    $modelSales->role_value = Yii::$app->request->post('Usersupdate')['city'];
                } else if ($roleCode == 'SLSTMG' || $roleCode == 'OPSTMG') {
                    $modelSales->role_value = Yii::$app->request->post('Usersupdate')['state'];
                }
            }
            
            $model->created_date = date('Y-m-d H:i:s');
            $model->status = 1;
            $roleObj = \app\models\Roles::findOne(['role_code' => $roleCode]);
            $model->role = $roleObj->id;
            $model->username = $model->login_id;

            if ($model->validate()) {
                $model->save(false);
                $modelSales->save(false);

                return $this->redirect(['admin']);
            } else {
                return $this->render('updateadmin', [
                            'model' => $model, 'modelSales' => $modelSales, 'profile' => $profile
                ]);
            }
        } else {
            $model->setAttribute('role', $profile->role_code);
            $model->setOldAttribute('role', $profile->role_code);
            return $this->render('updateadmin', [
                        'model' => $model, 'modelSales' => $modelSales, 'profile' => $profile
            ]);
        }
    }

    /**
     * Updates an existing Users model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    //  public function actionUpdate($id,$type)
    //  {
    //      $model = $this->findModelUpdate($id);
    //      if ($type == 5) {
    //          $advisorPhoneModel = $this->findModelUpdate_phone($id);
    //      } else if ($type == 4) {
    //          $advisorPhoneModel = $this->findOwnerUpdate_phone($id);
    //      } if ($type == 3) {
    //          $advisorPhoneModel = $this->findTenantUpdate_phone($id);
    //      } if ($type == 2) {
    //          $advisorPhoneModel = $this->findapplicantUpdate_phone($id);
    //      } 
    //      if ($model->load(Yii::$app->request->post()) && $advisorPhoneModel->load(Yii::$app->request->post())) {
    //          echo '<pre>'; print_r($model); die;
    // if($model->validate()){		
    //              $model->save(false);
    // 	$advisorPhoneModel->save(false);
    // 	return $this->redirect(['view', 'id' => $model->id]);
    // } else {
    // 	return $this->render('update', [
    // 		'model' => $model,'advisorPhoneModel' => $advisorPhoneModel,
    // 	]);
    // }
    //      } else {
    //          return $this->render('update', [
    //              'model' => $model,'advisorPhoneModel' => $advisorPhoneModel,
    //          ]);
    //      }
    //  }


    public function actionUpdate($id, $type) {
        $model = $this->findModelUpdate($id);
        //print_r($model); die;
        if ($type == 5) {
            $advisorPhoneModel = \app\models\AdvisorProfile::find()->where(['advisor_id' => $id])->one();
            $advisorNameModel = \app\models\LeadsAdvisor::find()->where(['email_id' => $model->username])->one();
			 
        } else if ($type == 4) {
            $advisorPhoneModel = \app\models\OwnerProfile::find()->where(['owner_id' => $id])->one();
			$advisorNameModel = \app\models\LeadsOwner::find()->where(['email_id' => $model->username])->one();
            //print_r($advisorPhoneModel); die;
        } else if ($type == 3) {
            $advisorPhoneModel = \app\models\TenantProfile::find()->where(['tenant_id' => $id])->one();
			$advisorNameModel = \app\models\LeadsTenant::find()->where(['email_id' => $model->username])->one();
        } else if ($type == 2) {
            $advisorPhoneModel = \app\models\ApplicantProfile::find()->where(['applicant_id' => $id])->one();
			$advisorNameModel = \app\models\LeadsTenant::find()->where(['email_id' => $model->username])->one();
        }

        if ($model->load(Yii::$app->request->post()) && $advisorPhoneModel->load(Yii::$app->request->post())) {
			
            if ($model->validate()) {
				//echo $model->full_name;die;
                $model->save(false);
                $advisorPhoneModel->save(false);
               // $advisorNameModel->save(false);
			   //echo $model->full_name;die;
			   $advisorNameModel->full_name = $model->full_name;
			   $advisorNameModel->contact_number = $advisorPhoneModel->phone;
$advisorNameModel->save(false);
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                            'model' => $model, 'advisorPhoneModel' => $advisorPhoneModel, 'advisorNameModel' => $advisorNameModel
                ]);
            }
        } else {
            // echo  '<pre>';print_r($model); 
            // echo  '<pre>';print_r($advisorPhoneModel); die;
            return $this->render('update', [
                        'model' => $model, 'advisorPhoneModel' => $advisorPhoneModel, 'advisorNameModel' => $advisorNameModel
            ]);
        }
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

    protected function findModelUpdate($id) {
        if (($model = \app\models\Usersupdate::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findModelUpdate_phone($id) {
        if (($model = \app\models\AdvisorProfile::find()->where(['advisor_id' => $id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findapplicantUpdate_phone($id) {
        if (($model = \app\models\ApplicantProfile::find()->where(['applicant_id' => $id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findOwnerUpdate_phone($id) {
        if (($model = \app\models\OwnerProfile::find()->where(['owner_id' => $id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findTenantUpdate_phone($id) {
        if (($model = \app\models\TenantProfile::find()->where(['tenant_id' => $id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findModelOperationsUpdate($id) {
        if (($model = \app\models\OperationsProfile::find()->where(['operations_id' => $id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findModelSalesUpdate($id) {
        if (($model = \app\models\SalesProfile::find()->where(['sale_id' => $id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionExport() {
        // 1->admin,2->applicant,4->owner,3->tenant,5->adviser,6->sales,7 -> operation
        $data = "Sr.No; Name; Username; Email; Phone; Date; Address; \r\n";
        $model = Users::find();
        $dataProvider = Users::find()->all();
        echo $data;
        $i = 1;
        foreach ($dataProvider as $value) {
            if ($value['user_type'] == '2') {
                $model = ApplicantProfile::find();
                $dataProvider = ApplicantProfile::find()->where(['applicant_id' => $value['id']])->all();
                foreach ($dataProvider as $key => $val) {
                    $phone = $val['phone'];
                    $address = $val['address_line_1'];
                }
            } else if ($value['user_type'] == '3') {
                $model = TenantProfile::find();
                $dataProvider = TenantProfile::find()->where(['tenant_id' => $value['id']])->all();
                foreach ($dataProvider as $key => $val) {
                    $phone = $val['phone'];
                    $address = $val['address_line_1'];
                }
            } else if ($value['user_type'] == '5') {
                $model = AdvisorProfile::find();
                $dataProvider = AdvisorProfile::find()->where(['advisor_id' => $value['id']])->all();
                foreach ($dataProvider as $key => $val) {
                    $phone = $val['phone'];
                    $address = $val['address_line_1'];
                }
            } else if ($value['user_type'] == '4') {
                $model = OwnerProfile::find();
                $dataProvider = OwnerProfile::find()->where(['owner_id' => $value['id']])->all();
                foreach ($dataProvider as $key => $val) {
                    $phone = $val['phone'];
                    $address = $val['address_line_1'];
                }
            } else {
                $phone = 0;
                $address = '';
            }
            //print_r($dataProvider); die;


            echo $i . ';';

            echo $value->full_name . ';' . $value->username . ';' . $value->login_id . ';' . $phone . ';' . $value->created_date . ';' . $address . ';' .
            /* ';' . $value['article'].
              ';' . $value['cost'] .
              ';' . $value['description'] .
              ';' . $value['Amount'] .
              ';' . $value['Manufacturer'] . */
            "\r\n";
            $i++;
        }
        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename="export_' . date('d.m.Y') . '.csv"');
        //echo iconv('utf-8', 'windows-1251', $data); //If suddenly in Windows will gibberish
        die;
    }

}
