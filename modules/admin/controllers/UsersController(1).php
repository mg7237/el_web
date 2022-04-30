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
                        'actions' => ['index', 'update', 'export', 'create', 'view', 'ajaxstatus', 'updateadmin', 'getroles', 'delete', 'admin', 'createadmin'],
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
        $dataProvider = new ActiveDataProvider([
            'query' => Users::find()->andWhere(['IN', 'user_type', ['2', '3', '4', '5']]),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('index', [
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Users models.
     * @return mixed
     */
    public function actionAdmin() {
        $dataProvider = new ActiveDataProvider([
            'query' => Users::find()->andWhere(['IN', 'user_type', ['6', '7']]),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

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
        $listData = ArrayHelper::map($model, 'id', 'role_name');

        $html = '';
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
        $modelTenant = new \app\models\TenantProfile();
        $modelAdvisor = new \app\models\AdvisorProfile();
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
            if ($model->validate()) {

                $model->password = md5($model->password);
                $model->save(false);
                if ($model->user_type == 2) {
                    $modelApplicant->load(Yii::$app->request->post());
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
                    $modelApplicant->save(false);
                } elseif ($model->user_type == 4) {
                    echo '<pre>';
                    print_r($_POST);
                    die;
                    $modelOwner->load(Yii::$app->request->post());
                    $modelOwner->owner_id = $model->id;
                    $modelOwner->ow_interested = $model->login_id;
                    $modelOwner->ow_comments = '';
                    $modelOwner->emer_contact = '';
                    $modelOwner->status = '0';
                    $modelOwner->sales_id = 0;
                    $modelOwner->operation_id = 0;
                    //$modelOwner->phone = '';
                    //$modelOwner->address_line_1 = '';
                    $modelOwner->save(false);
                } elseif ($model->user_type == 5) {
                    $modelAdvisor->load(Yii::$app->request->post());
                    $modelAdvisor->advisor_id = $model->id;
                    $modelAdvisor->ad_properties = $model->login_id;
                    $modelAdvisor->ad_company = '';
                    $modelAdvisor->emer_contact = '';
                    $modelAdvisor->sales_id = 0;
                    $modelAdvisor->operation_id = 0;
                    // $modelAdvisor->address_line_1 ='';
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

        if ($model->load(Yii::$app->request->post())) {
            $model->created_date = date('Y-m-d H:i:s');
            $model->status = 1;
            $model->auth_key = '0';
            $model->password_reset_token = '0';
            $model->identification = '0';
            $model->occupation = '0';
            $model->updated_date = '1970-01-01 00:00:00';
            $model->username = $model->login_id;

            if ($model->validate()) {
                $model->password = md5($model->password);

                $model->save(false);
                if ($model->user_type == '7') {
                    $modelOperations->load(Yii::$app->request->post());
                    $modelOperations->operations_id = $model->id;
                    $modelOperations->emer_contact = $model->login_id;
                    $modelOperations->address_line_1 = '';
                    $modelOperations->address_line_2 = '';
                    $modelOperations->state = 0;
                    $modelOperations->city = 0;
                    $modelOperations->pincode = 0;
                    $modelOperations->save(false);
                } else {
                    $modelSales->load(Yii::$app->request->post());
                    $modelSales->sale_id = $model->id;
                    $modelSales->emer_contact = $model->login_id;
                    $modelSales->status = '0';
                    $modelSales->address_line_1 = '';
                    $modelSales->address_line_2 = '';
                    $modelSales->state = 0;
                    $modelSales->city = 0;
                    $modelSales->pincode = 0;
                    $modelSales->save(false);
                }
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
    //  public function actionUpdateadmin($id,$type)
    //  {
    //      $model = $this->findModelUpdate($id);
    //      if ($type == 6) {
    //          $modelSales = $this->findModelSalesUpdate($id);
    //      } else if($type == 7) {
    //          $modelSales = $this->findModelOperationsUpdate($id);
    //      }
    //      if ($model->load(Yii::$app->request->post()) && $modelSales->load(Yii::$app->request->post())) {
    // $model->created_date = date('Y-m-d H:i:s') ;
    // $model->status = 1 ;
    // $model->username = $model->login_id ;
    // if($model->validate()){				
    //              $model->save(false);
    // 	$modelSales->save(false);
    // 	return $this->redirect(['view', 'id' => $model->id]);
    // } else {
    // 	return $this->render('updateadmin', [
    // 		'model' => $model,'modelSales'=>$modelSales,
    // 	]);
    // }
    //      } else {
    //          return $this->render('updateadmin', [
    //              'model' => $model,'modelSales'=>$modelSales,
    //          ]);
    //      }
    //  }


    public function actionUpdateadmin($id, $type) {
        $model = $this->findModelUpdate($id);
        if ($type == 6) {
            $modelSales = $this->findModelSalesUpdate($id);
        } else if ($type == 7) {
            $modelSales = $this->findModelOperationsUpdate($id);
        }
        if ($model->load(Yii::$app->request->post()) && $modelSales->load(Yii::$app->request->post())) {

            $model->created_date = date('Y-m-d H:i:s');
            $model->status = 1;
            $model->username = $model->login_id;

            if ($model->validate()) {
                $model->save(false);
                $modelSales->save(false);

                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('updateadmin', [
                            'model' => $model, 'modelSales' => $modelSales,
                ]);
            }
        } else {
            return $this->render('updateadmin', [
                        'model' => $model, 'modelSales' => $modelSales,
            ]);
        }
    }

    /**
     * Updates an existing Users model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $type) {
        $model = $this->findModelUpdate($id);
        if ($type == 5) {
            $advisorPhoneModel = $this->findModelUpdate_phone($id);
            //echo '<pre>'; print_r($advisorPhoneModel); echo "advisor"; die;
        } else if ($type == 4) {
            $advisorPhoneModel = $this->findOwnerUpdate_phone($id);
        } if ($type == 3) {
            $advisorPhoneModel = $this->findTenantUpdate_phone($id);
        } if ($type == 2) {
            $advisorPhoneModel = $this->findapplicantUpdate_phone($id);
        }

        if ($model->load(Yii::$app->request->post()) && $advisorPhoneModel->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $model->save(false);
                $advisorPhoneModel->save(false);
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                            'model' => $model, 'advisorPhoneModel' => $advisorPhoneModel,
                ]);
            }
        } else {
            return $this->render('update', [
                        'model' => $model, 'advisorPhoneModel' => $advisorPhoneModel,
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
