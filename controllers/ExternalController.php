<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use app\models\Operations;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\Url;
use app\models\LeadsTenant;
use app\models\PasswordForm;
use app\models\Users;
use app\models\Advisors;
use app\models\Owner;
use app\models\LeadsAdvisor;
use app\models\LeadsOwner;
use app\models\ApplicantProfile;
use app\models\AdvisorProfile;
use app\models\Register;
use app\models\Forget;
use yii\data\ActiveDataProvider;
use app\models\ForgotPassword;
use app\models\AdvisorAgreements;
use app\models\TenantProfile;
use app\models\OwnerProfile;
use app\models\Properties;
use app\models\AdhocRequests;
use app\models\ChildProperties;
use app\models\ReportParameters;
use app\models\TenantPayments;

/**
 * PropertiesController implements the CRUD actions for Properties model.
 */
class ExternalController extends Controller
{

    public $layout = 'external_dashboard';

    // public function __construct()
    // {
    //   header('location:/pmstest/web');
    // }
    // public function init(){
    //   if(!Yii::$app->user->id){
    //     header('location:'.Yii::$app->userdata->getDefaultUrl());
    //     die;
    //   }
    //   $request = Yii::$app->request;
    //   $action=explode("/",$this->module->requestedRoute);
    //   $newAction=Array();
    //   foreach($action as $link)
    //     {
    //         if(trim($link) != '')
    //         {
    //             $newAction[]=$link;
    //         }
    //     }
    //   $action=implode("/",$newAction);
    //
    //   if(!$request->isAjax){
    //     if(Yii::$app->user->id){
    //       $checked = Yii::$app->userdata->checkvalid($action,Yii::$app->user->identity->user_type);
    //       if($checked['status']=='0'){
    //         header('location:'.$checked['action']);
    //         die;
    //       }
    //     }
    //
    //   }
    // }



    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['propertyleads', 'changepassword', 'addproperty', 'editproperties', 'editproperty', 'ownerssdetails', 'getsalesname', 'index', 'myprofile', 'hotleads', 'details', 'editprofile', 'applicants', 'adviser', 'getproperty', 'owners', 'createapplicant', 'createadviser', 'applicantsdetails', 'createowner', 'addfollowup', 'createservice', 'financialreporting', 'requestmaintenanceapproval', 'maintenancedetails', 'owner', 'pgitxnlist', 'tenants', 'properties', 'propertieslisting', 'servicerequests', 'adhocrequests', 'financialreporting', 'paymentreceipt', 'myaccount'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionCreateowner()
    {
        $model = new LeadsOwner;
        $comments = new \app\models\Comments;
        $modelUser = new \app\models\Users;
        $modelOwnerProfile = new \app\models\OwnerProfile;
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = 'json';
            return \yii\bootstrap\ActiveForm::validate($model);
        } else if ($model->load(Yii::$app->request->post())) {
            if (!$model->validate()) {
                return $this->redirect(['owners']);
            }
            try {
                $modelOwnerProfile->load(Yii::$app->request->post());
                $model->created_by = Yii::$app->user->id;
                $model->created_date = date('Y-m-d H:i:s');
                $ownerState = Yii::$app->userdata->getStateCodeByStateId($model->lead_state);

                $model->lead_state = Yii::$app->userdata->getStateCodeByCityId($model->lead_city);
                $model->lead_city = Yii::$app->userdata->getCityCodeById($model->lead_city);

                $userType = Yii::$app->user->identity->user_type;
                if ($userType == 7) {
                    $opUser = \app\models\OperationsProfile::findOne(['operations_id' => Yii::$app->user->identity->id]);
                    $roleCode = $opUser->role_code;
                    $roleValue = $opUser->role_value;
                    if ($roleCode == 'OPEXE' && $roleValue == 'ELHQ') {
                        //$model->branch_code = $roleValue;
                        $modelOwnerProfile->operation_id = Yii::$app->user->identity->id;
                        $modelOwnerProfile->sales_id = null;
                    } else if ($roleCode == 'OPCTMG') {
                        $modelOwnerProfile->operation_id = $opUser->operations_id;
                        $salesUser = \app\models\SalesProfile::findOne(['role_code' => 'SLCTMG', 'role_value' => $roleValue]);
                        $modelOwnerProfile->sales_id = $salesUser->sale_id;
                    } else if ($roleCode == 'OPBRMG' || $roleCode == 'OPEXE') {
                        //$model->branch_code = $opUser->role_value;
                        $modelOwnerProfile->operation_id = $opUser->operations_id;
                        $salesUser = \app\models\SalesProfile::findOne(['role_code' => 'SLBRMG', 'role_value' => $roleValue]);
                        $modelOwnerProfile->sales_id = $salesUser->sale_id;
                    }
                } else if ($userType == 6) {
                    $salesUser = \app\models\SalesProfile::findOne(['sale_id' => Yii::$app->user->identity->id]);
                    $roleCode = $salesUser->role_code;
                    $roleValue = $salesUser->role_value;
                    if ($roleCode == 'SLCTMG') {
                        $modelOwnerProfile->sales_id = $salesUser->sale_id;
                        $opUser = \app\models\OperationsProfile::findOne(['role_code' => 'OPCTMG', 'role_value' => $roleValue]);
                        $modelOwnerProfile->operation_id = $opUser->operations_id;
                    } else if ($roleCode == 'SLBRMG' || $roleCode == 'SLEXE') {
                        //$model->branch_code = $salesUser->role_value;
                        $modelOwnerProfile->sales_id = $salesUser->sale_id;
                        $opUser = \app\models\OperationsProfile::findOne(['role_code' => 'OPBRMG', 'role_value' => $roleValue]);
                        $modelOwnerProfile->operation_id = $opUser->operations_id;
                    }
                }
                $rand = Yii::$app->userdata->passwordGenerate();

                $modelUser->login_id = $model->email_id;
                $modelUser->full_name = $model->full_name;
                $modelUser->username = $model->email_id;
                $modelUser->password = md5($rand);
                $modelUser->user_type = 4;
                $modelUser->created_by = Yii::$app->user->id;
                $modelUser->created_date = date('Y-m-d H:i:s');
                $modelUser->auth_key = base64_encode($model->email_id . time() . rand(1000, 9999));
                $modelUser->phone = $model->contact_number;

                if ($modelUser->validate(['login_id', 'full_name', 'phone'])) {

                    $model->save(false);
                    $modelUser->save(false);

                    $modelOwnerProfile->owner_id = $modelUser->id;
                    $modelOwnerProfile->branch_code = $model->branch_code;
                    $modelOwnerProfile->address_line_1 = $model->address;
                    $modelOwnerProfile->address_line_2 = $model->address_line_2;
                    $modelOwnerProfile->state = $ownerState;
                    $modelOwnerProfile->phone = $model->contact_number;
                    $modelOwnerProfile->pincode = $model->pincode;
                    $modelOwnerProfile->region = $model->region;
                    $modelOwnerProfile->save(false);

                    $modelAllUsers = new \app\models\Users;
                    $query_model = Users::find()
                        ->select('login_id')
                        ->asArray()
                        ->where('user_type between 6 and 7')
                        ->all();
                    $allemails = "";
                    $countemail = 0;
                    foreach ($query_model as $userEmailID) {
                        $allemails .= $userEmailID['login_id'] . ",";
                    }
                    $allemails = rtrim($allemails, ',');
                    /*
                      echo $allemails;
                      die();
                      echo "<pre>";
                      print_r($query_model);
                      echo "</pre>";
                      die(); */

                    $comments->description = $model->communication;
                    $comments->user_id = $modelUser->id;
                    $comments->created_by = Yii::$app->user->id;
                    $comments->save(false);
                    $subject = 'New property owner lead';
                    $msg = "Hello,<br/><br/>New property owner lead for $model->full_name has been created, please verify and assign the branch and sales person to this lead.<br/><br/>Contact details of property owner are $model->contact_number,$modelUser->login_id<br/>With Regards,<br/>Easyleases Team";
                    @$email = Yii::$app->userdata->doMail($allemails, $subject, $msg);
                    if ($email) {
                        Yii::$app->getSession()->setFlash('success', 'Property owner has been created successfully.');
                    } else {
                        Yii::$app->getSession()->setFlash('success', 'Property owner has been created successfully');
                    }
                    return $this->redirect('ownerssdetails?id=' . base64_encode($model->id));
                } else {
                    Yii::$app->getSession()->setFlash(
                        'success',
                        'Owner is not created'
                    );
                    return $this->redirect(['owners']);
                }
            } catch (\Exception $e) {
                Yii::$app->getSession()->setFlash(
                    'success',
                    "{$e->getMessage()}"
                );
                return $this->renderAjax('_formowner', [
                    'model' => $model,
                    'modelOwnerProfile' => $modelOwnerProfile
                ]);
            }
        } else {
            return $this->renderAjax('_formowner', [
                'model' => $model,
                'modelOwnerProfile' => $modelOwnerProfile
            ]);
        }
    }

    public function actionRemovefavrow()
    {
        if (!empty(Yii::$app->request->post('fv_id'))) {
            $model = \app\models\FavouriteProperties::find()->where(['id' => Yii::$app->request->post('fv_id')])->one();
            $model->delete(false);
        }
    }

    public function actionCreatepropertylead()
    {
        $model = new \app\models\LeadsProperty;
        $propertiesModel = new \app\models\Properties;
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = 'json';
            return \yii\bootstrap\ActiveForm::validate($model);
        } else if ($model->load(Yii::$app->request->post())) {
            try {
                $propertiesModel->load(Yii::$app->request->post());
                $model->created_by = Yii::$app->user->id;
                $model->created_date = date('Y-m-d H:i:s');
                $model->lead_city = Yii::$app->userdata->getCityCodeById($model->lead_city);

                if (!empty($model->lead_state)) {
                    $model->lead_state = Yii::$app->userdata->getStateCodeByStateId($model->lead_state);
                } else {
                    $model->lead_state = Yii::$app->userdata->getStateCodeByCityId($model->lead_city);
                }

                $userType = Yii::$app->user->identity->user_type;
                if ($userType == 7) {
                    $opUser = \app\models\OperationsProfile::findOne(['operations_id' => Yii::$app->user->identity->id]);
                    $roleCode = $opUser->role_code;
                    $roleValue = $opUser->role_value;
                    if ($roleCode == 'OPEXE' && $roleValue == 'ELHQ') {
                        $model->branch_code = $roleValue;
                        $propertiesModel->operations_id = Yii::$app->user->identity->id;
                        $propertiesModel->sales_id = null;
                    } else if ($roleCode == 'OPCTMG') {
                        $propertiesModel->operations_id = $opUser->operations_id;
                        $salesUser = \app\models\SalesProfile::findOne(['role_code' => 'SLCTMG', 'role_value' => $roleValue]);
                        $propertiesModel->sales_id = $salesUser->sale_id;
                    } else if ($roleCode == 'OPBRMG' || $roleCode == 'OPEXE') {
                        $model->branch_code = $opUser->role_value;
                        $propertiesModel->operations_id = $opUser->operations_id;
                        $salesUser = \app\models\SalesProfile::findOne(['role_code' => 'SLBRMG', 'role_value' => $roleValue]);
                        $propertiesModel->sales_id = $salesUser->sale_id;
                    }
                } else if ($userType == 6) {
                    $salesUser = \app\models\SalesProfile::findOne(['sale_id' => Yii::$app->user->identity->id]);
                    $roleCode = $salesUser->role_code;
                    $roleValue = $salesUser->role_value;
                    if ($roleCode == 'SLCTMG') {
                        $propertiesModel->sales_id = $salesUser->sale_id;
                        $opUser = \app\models\OperationsProfile::findOne(['role_code' => 'OPCTMG', 'role_value' => $roleValue]);
                        $propertiesModel->operations_id = $opUser->operations_id;
                    } else if ($roleCode == 'SLBRMG' || $roleCode == 'SLEXE') {
                        $model->branch_code = $salesUser->role_value;
                        $propertiesModel->sales_id = $salesUser->sale_id;
                        $opUser = \app\models\OperationsProfile::findOne(['role_code' => 'OPBRMG', 'role_value' => $roleValue]);
                        $propertiesModel->operations_id = $opUser->operations_id;
                    }
                }

                $model->lead_city = Yii::$app->request->post('LeadsProperty')['lead_city'];
                $model->lead_state = Yii::$app->request->post('LeadsProperty')['lead_state'];

                $propertiesModel->property_name = $model->property_name;
                $propertiesModel->owner_id = $model->owner_id;
                $propertiesModel->branch_code = $model->branch_code;
                $propertiesModel->address_line_1 = $model->address;
                $propertiesModel->address_line_2 = $model->address2;
                $propertiesModel->city = $model->lead_city;
                $propertiesModel->state = $model->lead_state;
                $propertiesModel->pincode = $model->pincode;
                $propertiesModel->branch_code = $model->branch_code;
                $propertiesModel->created_date = date('Y:m:d');
                $propertiesModel->created_by = Yii::$app->user->id;
                $leadOwnModel = \app\models\LeadsOwner::find(['email_id' => Yii::$app->userdata->getUserEmailById($model->owner_id)])->one();
                if ($leadOwnModel) {
                    $propertiesModel->owner_lead_id = $leadOwnModel->id;
                } else {
                    $propertiesModel->owner_lead_id = 0;
                }

                if ($propertiesModel->save(false)) {
                    $model->property_id = Yii::$app->db->getLastInsertID();
                    $model->updated_date = date('Y:m:d');
                    $model->save(false);

                    Yii::$app->getSession()->setFlash('success', 'Property Lead created successfully!');
                    return $this->redirect('editproperty?id=' . $model->property_id);
                } else {
                    Yii::$app->getSession()->setFlash(
                        'success',
                        'Property lead is not created'
                    );
                    return $this->redirect(['propertyleads']);
                }
            } catch (\Exception $e) {
                Yii::$app->getSession()->setFlash(
                    'success',
                    "{$e->getMessage()}"
                );
                return $this->renderAjax('_formpropertyleads', [
                    'model' => $model,
                    'propertiesModel' => $propertiesModel
                ]);
            }
        } else {
            return $this->renderAjax('_formpropertyleads', [
                'model' => $model,
                'propertiesModel' => $propertiesModel
            ]);
        }
    }

    public function actionGetsalesname($term)
    {

        $data = $_REQUEST['term'];

        $searchVal = Yii::$app->getRequest()->getQueryParam('term');
        $users = \app\models\Users::find()->where(['user_type' => 6])->andFilterWhere(['like', 'full_name', $searchVal])->all();
        $description_arr = [];
        foreach ($users as $key => $user) {
            $description_arr[$key]['value'] = $user->id;
            $description_arr[$key]['label'] = $user->full_name;
        }
        echo json_encode($description_arr);
    }

    public function actionGetoperationsname($term)
    {

        $data = $_REQUEST['term'];

        $searchVal = Yii::$app->getRequest()->getQueryParam('term');
        $users = \app\models\Users::find()->where(['user_type' => 7])->andFilterWhere(['like', 'full_name', $searchVal])->all();
        $description_arr = [];
        foreach ($users as $key => $user) {
            $description_arr[$key]['value'] = $user->id;
            $description_arr[$key]['label'] = $user->full_name;
            $description_arr[$key]['email'] = $user->login_id;
            $description_arr[$key]['phone'] = Yii::$app->userdata->getPhoneById($user->id, 7);
        }
        echo json_encode($description_arr);
    }

    public function actionDeleteimage()
    {

        $modelImages = \app\models\PropertyImages::findOne($_POST['id']);
        if ($modelImages) {
            $modelImages->delete();
        }
    }

    public function actionGetownernamebypropertyid()
    {
        $proId = Yii::$app->request->post('item')['value'];
        $ownId = \app\models\Properties::find()->where(['id' => $proId])->one()->owner_id;
        $res = [];
        $userDetail = \app\models\Users::find()->select(['id', 'full_name'])->where(['id' => $ownId])->one();
        $res[0]['id'] = $userDetail['id'];
        $res[0]['full_name'] = $userDetail['full_name'];
        echo (json_encode($res));
    }

    public function actionGettenantnamebypropertyid()
    {
        $proId = Yii::$app->request->post('item')['value'];
        $tenants = \app\models\TenantAgreements::find()->select(['tenant_id'])->distinct()->where(['property_id' => $proId])->all();
        $res = [];
        foreach ($tenants as $key => $tenant) {
            $userDetail = \app\models\Users::find()->select(['id', 'full_name'])->where(['id' => $tenant['tenant_id']])->one();
            $res[$key]['id'] = $userDetail['id'];
            $res[$key]['full_name'] = $userDetail['full_name'];
        }
        echo (json_encode($res));
    }

    public function actionGetproperty1($term)
    {
        $searchVal = Yii::$app->request->get('term');
        $userType = Yii::$app->user->identity->user_type;
        $where = "";

        if ($userType == 7) {
            $opsProfileModel = \app\models\OperationsProfile::findOne(['operations_id' => Yii::$app->user->id]);
            $currentRoleCode = $opsProfileModel->role_code;
            $currentRoleValue = $opsProfileModel->role_value;

            if ($currentRoleValue == 'ELHQ') {
                $assinee = \app\models\Properties::find()
                    ->where(''
                        . 'property_name like "%' . $term . '%" OR address_line_1 like "%' . $term . '%" OR property_description like "%' . $term . '%"')
                    ->limit(15)
                    ->all();
            } else if ($currentRoleCode == 'OPSTMG') {
                $assinee = \app\models\Properties::find()
                    ->where(' (state = "' . $currentRoleValue . '") '
                        . 'AND '
                        . '(property_name like "%' . $term . '%" OR address_line_1 like "%' . $term . '%" OR property_description like "%' . $term . '%") ')
                    ->limit(15)
                    ->all();
            } else if ($currentRoleCode == 'OPCTMG') {
                $assinee = \app\models\Properties::find()
                    ->where(' (city = "' . $currentRoleValue . '") '
                        . 'AND '
                        . '(property_name like "%' . $term . '%" OR address_line_1 like "%' . $term . '%" OR property_description like "%' . $term . '%") ')
                    ->limit(15)
                    ->all();
            } else if ($currentRoleCode == 'OPBRMG') {
                $assinee = \app\models\Properties::find()
                    ->where(' (branch_code = "' . $currentRoleValue . '") '
                        . 'AND '
                        . '(property_name like "%' . $term . '%" OR address_line_1 like "%' . $term . '%" OR property_description like "%' . $term . '%") ')
                    ->limit(15)
                    ->all();
            } else if ($currentRoleCode == 'OPEXE') {
                $branchModel = \app\models\Branches::find()->where(['branch_code' => $currentRoleValue])->one();
                $assinee = \app\models\Properties::find()
                    ->where(' (branch_code = "' . $currentRoleValue . '" '
                        . 'AND city = "' . $branchModel->city_code . '"'
                        . 'AND operations_id = "' . Yii::$app->user->id . '" ) '
                        . 'AND '
                        . '(property_name like "%' . $term . '%" OR address_line_1 like "%' . $term . '%" OR property_description like "%' . $term . '%") ')
                    ->limit(15)
                    ->all();
            }
        } else {
            $opsProfileModel = \app\models\SalesProfile::findOne(['sale_id' => Yii::$app->user->id]);
            $currentRoleCode = $opsProfileModel->role_code;
            $currentRoleValue = $opsProfileModel->role_value;

            if ($currentRoleValue == 'ELHQ') {
                $assinee = \app\models\Properties::find()
                    ->where(''
                        . 'property_name like "%' . $term . '%" OR address_line_1 like "%' . $term . '%" OR property_description like "%' . $term . '%"')
                    ->limit(15)
                    ->all();
            } else if ($currentRoleCode == 'SLSTMG') {
                $assinee = \app\models\Properties::find()
                    ->where(' (state = "' . $currentRoleValue . '") '
                        . 'AND '
                        . '(property_name like "%' . $term . '%" OR address_line_1 like "%' . $term . '%" OR property_description like "%' . $term . '%") ')
                    ->limit(15)
                    ->all();
            } else if ($currentRoleCode == 'SLCTMG') {
                $assinee = \app\models\Properties::find()
                    ->where(' (city = "' . $currentRoleValue . '") '
                        . 'AND '
                        . '(property_name like "%' . $term . '%" OR address_line_1 like "%' . $term . '%" OR property_description like "%' . $term . '%") ')
                    ->limit(15)
                    ->all();
            } else if ($currentRoleCode == 'SLBRMG') {
                $assinee = \app\models\Properties::find()
                    ->where(' (branch_code = "' . $currentRoleValue . '") '
                        . 'AND '
                        . '(property_name like "%' . $term . '%" OR address_line_1 like "%' . $term . '%" OR property_description like "%' . $term . '%") ')
                    ->limit(15)
                    ->all();
            } else if ($currentRoleCode == 'SLEXE') {
                $branchModel = \app\models\Branches::find()->where(['branch_code' => $currentRoleValue])->one();
                $where = " op.branch_code = '" . $currentRoleValue . "' "
                    . " AND lo.city = '" . $branchModel->city_code . "' "
                    . " AND op.sales_id = '" . Yii::$app->user->id . "' ";
                $assinee = \app\models\Properties::find()
                    ->where(' (branch_code = "' . $currentRoleValue . '" '
                        . 'AND city = "' . $branchModel->city_code . '"'
                        . 'AND sales_id = "' . Yii::$app->user->id . '" ) '
                        . 'AND '
                        . '(property_name like "%' . $term . '%" OR address_line_1 like "%' . $term . '%" OR property_description like "%' . $term . '%") ')
                    ->limit(15)
                    ->all();
            }
        }

        $property_listing = array();
        if (count($assinee) != 0) {
            foreach ($assinee as $key => $value) {
                $label = $value->property_name . ", " . $value->address_line_1 . ", " . $value->address_line_2 . ", " . Yii::$app->userdata->getCityName($value->city);
                $property_listing[$key]['value'] = $value->id;
                $property_listing[$key]['name'] = $value->property_name;
                $property_listing[$key]['label'] = (strlen($label) > 150) ? substr($label, 0, 150) . " ....." : substr($label, 0, 150);
            }
        }

        echo json_encode($property_listing);
        die;
    }

    public function actionGetownersearch()
    {
        $searchVal = Yii::$app->request->get('term');
        $userType = Yii::$app->user->identity->user_type;
        $where = "";
        if ($userType == 7) {
            $opsProfileModel = \app\models\OperationsProfile::findOne(['operations_id' => Yii::$app->user->id]);
            $currentRoleCode = $opsProfileModel->role_code;
            $currentRoleValue = $opsProfileModel->role_value;

            if ($currentRoleValue == 'ELHQ') {
                $where = " 1 ";
            } else if ($currentRoleCode == 'OPSTMG') {
                $where = " lo.lead_state = '" . $currentRoleValue . "' ";
            } else if ($currentRoleCode == 'OPCTMG') {
                $where = " lo.lead_city = '" . $currentRoleValue . "' ";
            } else if ($currentRoleCode == 'OPBRMG') {
                $where = " op.branch_code = '" . $currentRoleValue . "' ";
            } else if ($currentRoleCode == 'OPEXE') {
                $branchModel = \app\models\Branches::find()->where(['branch_code' => $currentRoleValue])->one();
                $where = " op.branch_code = '" . $currentRoleValue . "' "
                    . " AND lo.lead_city = '" . $branchModel->city_code . "' "
                    . " AND op.operation_id = '" . Yii::$app->user->id . "' ";
            }
        } else {
            $opsProfileModel = \app\models\SalesProfile::findOne(['sale_id' => Yii::$app->user->id]);
            $currentRoleCode = $opsProfileModel->role_code;
            $currentRoleValue = $opsProfileModel->role_value;

            if ($currentRoleValue == 'ELHQ') {
                $where = " 1 ";
            } else if ($currentRoleCode == 'SLSTMG') {
                $where = " lo.state = '" . $currentRoleValue . "' ";
            } else if ($currentRoleCode == 'SLCTMG') {
                $where = " lo.city = '" . $currentRoleValue . "' ";
            } else if ($currentRoleCode == 'SLBRMG') {
                $where = " op.branch_code = '" . $currentRoleValue . "' ";
            } else if ($currentRoleCode == 'SLEXE') {
                $branchModel = \app\models\Branches::find()->where(['branch_code' => $currentRoleValue])->one();
                $where = " op.branch_code = '" . $currentRoleValue . "' "
                    . " AND lo.city = '" . $branchModel->city_code . "' "
                    . " AND op.sales_id = '" . Yii::$app->user->id . "' ";
            }
        }

        $connection = Yii::$app->getDb();
        $command = $connection->createCommand(""
            . "SELECT u.id, u.full_name, u.login_id, op.phone, op.address_line_1, op.address_line_2, op.city_name FROM users u "
            . "INNER JOIN owner_profile op ON op.owner_id = u.id "
            . "INNER JOIN leads_owner lo ON lo.email_id = u.login_id "
            . "WHERE (u.full_name LIKE '%" . $searchVal . "%' OR "
            . "u.login_id LIKE '%" . $searchVal . "%' OR "
            . "op.address_line_1 LIKE '%" . $searchVal . "%' OR "
            . "op.address_line_2 LIKE '%" . $searchVal . "%' OR "
            . "op.city_name LIKE '%" . $searchVal . "%' OR "
            . "op.branch_code LIKE '%" . $searchVal . "%' OR "
            . "op.phone LIKE '%" . $searchVal . "%') AND "
            //. "(u.user_type = 4 AND u.status = 1 AND op.status = 1) "
            . "(u.user_type = 4 AND u.status = 1) AND "
            . " (" . $where . ") "
            . "");
        $ownerData = $command->queryAll();

        $description_arr = [];
        foreach ($ownerData as $key => $row) {
            $label = $row['full_name'] . " [ " . $row['phone'] . ", " . $row['login_id'] . ", " . $row['address_line_1'] . " , " . $row['address_line_2'] . ", " . $row['city_name'] . " ]";
            $description_arr[$key]['label'] = (strlen($label) > 150) ? substr($label, 0, 150) . " ....." : substr($label, 0, 150);
            $description_arr[$key]['value'] = $row['id'];
        }

        echo json_encode($description_arr);
        exit;
    }

    public function actionGetpropertysearch()
    {
        $searchVal = Yii::$app->request->get('term');
        $userType = Yii::$app->user->identity->user_type;
        $where = "";
        $sqlExec = false;
        if ($userType == 7) {
            $opsProfileModel = \app\models\OperationsProfile::findOne(['operations_id' => Yii::$app->user->id]);
            $currentRoleCode = $opsProfileModel->role_code;
            $currentRoleValue = $opsProfileModel->role_value;

            if ($currentRoleValue == 'ELHQ') {
                $where = " 1 ";
            } else if ($currentRoleCode == 'OPSTMG') {
                $where = " state = '" . $currentRoleValue . "' ";
            } else if ($currentRoleCode == 'OPCTMG') {
                $where = " city = '" . $currentRoleValue . "' ";
            } else if ($currentRoleCode == 'OPBRMG') {
                $where = " branch_code = '" . $currentRoleValue . "' ";
            } else if ($currentRoleCode == 'OPEXE') {
                $branchModel = \app\models\Branches::find()->where(['branch_code' => $currentRoleValue])->one();
                $where = " branch_code = '" . $currentRoleValue . "' "
                    . " AND city = '" . $branchModel->city_code . "' "
                    . " AND operations_id = '" . Yii::$app->user->id . "' ";
            }

            $sqlExec = true;
        } else if ($userType == 6) {
            $opsProfileModel = \app\models\SalesProfile::findOne(['sale_id' => Yii::$app->user->id]);
            $currentRoleCode = $opsProfileModel->role_code;
            $currentRoleValue = $opsProfileModel->role_value;

            if ($currentRoleValue == 'ELHQ') {
                $where = " 1 ";
            } else if ($currentRoleCode == 'SLSTMG') {
                $where = " state = '" . $currentRoleValue . "' ";
            } else if ($currentRoleCode == 'SLCTMG') {
                $where = " city = '" . $currentRoleValue . "' ";
            } else if ($currentRoleCode == 'SLBRMG') {
                $where = " branch_code = '" . $currentRoleValue . "' ";
            } else if ($currentRoleCode == 'SLEXE') {
                $branchModel = \app\models\Branches::find()->where(['branch_code' => $currentRoleValue])->one();
                $where = " branch_code = '" . $currentRoleValue . "' "
                    . " AND city = '" . $branchModel->city_code . "' "
                    . " AND sales_id = '" . Yii::$app->user->id . "' ";
            }
            $sqlExec = true;
        } else if ($userType == 9) {
            $opsProfileModel = \app\models\InvestorsProfile::findOne(['investor_id' => Yii::$app->user->id]);
            if ($opsProfileModel) {
                $where = ' pia.investor_id = ' . Yii::$app->user->id . ' ';
                $connection = Yii::$app->getDb();
                $command = $connection->createCommand(""
                    . "SELECT p.id, p.property_name FROM property_investor_agreements pia "
                    . "JOIN properties p ON pia.property_id = p.id "
                    . "WHERE (p.property_name LIKE '%" . $searchVal . "%' OR "
                    . "p.address_line_1 LIKE '%" . $searchVal . "%' OR "
                    . "p.address_line_2 LIKE '%" . $searchVal . "%') AND "
                    . "(" . $where . ") "
                    . "");
                $ownerData = $command->queryAll();

                $description_arr = [];
                foreach ($ownerData as $key => $row) {
                    $label = $row['property_name'];
                    $description_arr[$key]['label'] = (strlen($label) > 150) ? substr($label, 0, 150) . " ....." : substr($label, 0, 150);
                    $description_arr[$key]['value'] = $row['id'];
                }

                echo json_encode($description_arr);
            }
        }

        if ($sqlExec) {
            $connection = Yii::$app->getDb();
            $command = $connection->createCommand(""
                . "SELECT * FROM properties "
                . "WHERE (property_name LIKE '%" . $searchVal . "%' OR "
                . "address_line_1 LIKE '%" . $searchVal . "%' OR "
                . "address_line_2 LIKE '%" . $searchVal . "%') AND "
                . "(" . $where . ") "
                . "");
            $ownerData = $command->queryAll();

            $description_arr = [];
            foreach ($ownerData as $key => $row) {
                $label = $row['property_name'];
                $description_arr[$key]['label'] = (strlen($label) > 150) ? substr($label, 0, 150) . " ....." : substr($label, 0, 150);
                $description_arr[$key]['value'] = $row['id'];
            }

            echo json_encode($description_arr);
        }
        exit;
    }

    public function actionGettenantsearch()
    {
        $searchVal = Yii::$app->request->get('term');
        $userType = Yii::$app->user->identity->user_type;
        $where = "";
        $sqlExec = false;
        if ($userType == 7) {
            $opsProfileModel = \app\models\OperationsProfile::findOne(['operations_id' => Yii::$app->user->id]);
            $currentRoleCode = $opsProfileModel->role_code;
            $currentRoleValue = $opsProfileModel->role_value;

            if ($currentRoleValue == 'ELHQ') {
                $where = " 1 ";
            } else if ($currentRoleCode == 'OPSTMG') {
                $where = " ap.state = '" . $currentRoleValue . "' ";
            } else if ($currentRoleCode == 'OPCTMG') {
                $where = " ap.city = '" . $currentRoleValue . "' ";
            } else if ($currentRoleCode == 'OPBRMG') {
                $where = " ap.branch_code = '" . $currentRoleValue . "' ";
            } else if ($currentRoleCode == 'OPEXE') {
                $branchModel = \app\models\Branches::find()->where(['branch_code' => $currentRoleValue])->one();
                $where = " ap.branch_code = '" . $currentRoleValue . "' "
                    . " AND ap.city = '" . $branchModel->city_code . "' "
                    . " AND ap.operation_id = '" . Yii::$app->user->id . "' ";
            }

            $sqlExec = true;
        } else if ($userType == 6) {
            $opsProfileModel = \app\models\SalesProfile::findOne(['sale_id' => Yii::$app->user->id]);
            $currentRoleCode = $opsProfileModel->role_code;
            $currentRoleValue = $opsProfileModel->role_value;

            if ($currentRoleValue == 'ELHQ') {
                $where = " 1 ";
            } else if ($currentRoleCode == 'SLSTMG') {
                $where = " ap.state = '" . $currentRoleValue . "' ";
            } else if ($currentRoleCode == 'SLCTMG') {
                $where = " ap.city = '" . $currentRoleValue . "' ";
            } else if ($currentRoleCode == 'SLBRMG') {
                $where = " ap.branch_code = '" . $currentRoleValue . "' ";
            } else if ($currentRoleCode == 'SLEXE') {
                $branchModel = \app\models\Branches::find()->where(['branch_code' => $currentRoleValue])->one();
                $where = " ap.branch_code = '" . $currentRoleValue . "' "
                    . " AND ap.city = '" . $branchModel->city_code . "' "
                    . " AND ap.sales_id = '" . Yii::$app->user->id . "' ";
            }
            $sqlExec = true;
        }

        if ($sqlExec) {
            $connection = Yii::$app->getDb();
            $command = $connection->createCommand(""
                . "SELECT u.id, u.full_name FROM users u INNER JOIN applicant_profile ap ON u.id = ap.applicant_id "
                . "WHERE (u.full_name LIKE '%" . $searchVal . "%' OR "
                . "u.login_id LIKE '%" . $searchVal . "%' OR "
                . "u.phone LIKE '%" . $searchVal . "%') AND "
                . "(" . $where . ") "
                . "");
            $ownerData = $command->queryAll();

            $description_arr = [];
            foreach ($ownerData as $key => $row) {
                $label = $row['full_name'];
                $description_arr[$key]['label'] = (strlen($label) > 150) ? substr($label, 0, 150) . " ....." : substr($label, 0, 150);
                $description_arr[$key]['value'] = $row['id'];
            }

            echo json_encode($description_arr);
        }
        exit;
    }

    public function actionGetfavpropertysearch()
    {
        $searchVal = Yii::$app->request->get('term');
        $userType = Yii::$app->user->identity->user_type;
        $where = "";
        $sqlExec = false;
        if ($userType == 7) {
            $opsProfileModel = \app\models\OperationsProfile::findOne(['operations_id' => Yii::$app->user->id]);
            $currentRoleCode = $opsProfileModel->role_code;
            $currentRoleValue = $opsProfileModel->role_value;

            if ($currentRoleValue == 'ELHQ') {
                $where = " 1 ";
            } else if ($currentRoleCode == 'OPSTMG') {
                $where = " ap.state = '" . $currentRoleValue . "' ";
            } else if ($currentRoleCode == 'OPCTMG') {
                $where = " ap.city = '" . $currentRoleValue . "' ";
            } else if ($currentRoleCode == 'OPBRMG') {
                $where = " ap.branch_code = '" . $currentRoleValue . "' ";
            } else if ($currentRoleCode == 'OPEXE') {
                $branchModel = \app\models\Branches::find()->where(['branch_code' => $currentRoleValue])->one();
                $where = " ap.branch_code = '" . $currentRoleValue . "' "
                    . " AND ap.city = '" . $branchModel->city_code . "' "
                    . " AND ap.operation_id = '" . Yii::$app->user->id . "' ";
            }

            $sqlExec = true;
        } else if ($userType == 6) {
            $opsProfileModel = \app\models\SalesProfile::findOne(['sale_id' => Yii::$app->user->id]);
            $currentRoleCode = $opsProfileModel->role_code;
            $currentRoleValue = $opsProfileModel->role_value;

            if ($currentRoleValue == 'ELHQ') {
                $where = " 1 ";
            } else if ($currentRoleCode == 'SLSTMG') {
                $where = " p.state = '" . $currentRoleValue . "' ";
            } else if ($currentRoleCode == 'SLCTMG') {
                $where = " p.city = '" . $currentRoleValue . "' ";
            } else if ($currentRoleCode == 'SLBRMG') {
                $where = " p.branch_code = '" . $currentRoleValue . "' ";
            } else if ($currentRoleCode == 'SLEXE') {
                $branchModel = \app\models\Branches::find()->where(['branch_code' => $currentRoleValue])->one();
                $where = " p.branch_code = '" . $currentRoleValue . "' "
                    . " AND p.city = '" . $branchModel->city_code . "' "
                    . " AND p.sales_id = '" . Yii::$app->user->id . "' ";
            }
            $sqlExec = true;
        }

        if ($sqlExec) {
            $connection = Yii::$app->getDb();
            $command = $connection->createCommand(""
                . "SELECT p.id, p.property_name FROM properties p "
                . "INNER JOIN users u ON u.id = p.owner_id "
                //. "INNER JOIN favourite_properties fp ON fp.property_id = p.id "
                . "WHERE (u.full_name LIKE '%" . $searchVal . "%' OR "
                . "p.property_name LIKE '%" . $searchVal . "%' OR "
                . "p.address_line_1 LIKE '%" . $searchVal . "%' OR "
                . "u.login_id LIKE '%" . $searchVal . "%' OR "
                . "u.phone LIKE '%" . $searchVal . "%') AND "
                . "(" . $where . ") "
                . "");
            $ownerData = $command->queryAll();

            $description_arr = [];
            foreach ($ownerData as $key => $row) {
                $label = $row['property_name'];
                $description_arr[$key]['label'] = (strlen($label) > 150) ? substr($label, 0, 150) . " ....." : substr($label, 0, 150);
                $description_arr[$key]['value'] = $row['id'];
            }

            echo json_encode($description_arr);
        }
        exit;
    }

    public function actionGetproperty()
    {

        $modelFav = \app\models\FavouriteProperties::find()->where(['applicant_id' => $_GET['applicant_id']])->all();

        $property_id = [];
        foreach ($modelFav as $fav) {
            $property_id[] = $fav->property_id;
        }

        $searchVal = Yii::$app->getRequest()->getQueryParam('term');
        $properties = new \app\models\Properties();
        $properties = $properties->getPropertyListByListingStatus($searchVal);
        $description_arr = [];
        foreach ($properties as $key => $property) {
            $label = $property['property_name'] . " | " . $property['full_name'] . " | " . $property['address_line_1'] . ", " . $property['address_line_2'] . ", " . Yii::$app->userdata->getCityName($property['city']) . " | R:" . $property['rent'] . " | M:" . $property['maintenance'] . " | D:" . $property['deposit'];
            if (!in_array($property['id'], $property_id)) {
                $description_arr[$key]['value'] = $property['id'];
                $description_arr[$key]['label'] = (strlen($label) > 150) ? substr($label, 0, 150) . " ....." : substr($label, 0, 150);
            }
        }
        echo json_encode($description_arr);

        die;
    }

    public function actionIndex()
    {
        if (Yii::$app->user->identity->user_type == 7) {
            $days90 = date('Y-m-d H:i:s', strtotime("+90 days"));
            $userAssignments = \app\models\Users::find()->where(['id' => Yii::$app->user->id])->one();
            $opsProfileModel = \app\models\OperationsProfile::findOne(['operations_id' => Yii::$app->user->id]);
            $currentRoleCode = $opsProfileModel->role_code;
            $currentRoleValue = $opsProfileModel->role_value;

            if ($currentRoleValue == 'ELHQ') {
                //////////////// Total Outstanding Request ///////////////////////////////////
                $where = " 1 ";
                $where2 = " 1 ";
                $where3 = " AND sr.status <> 5 ";
                $result = Yii::$app->db->createCommand(
                    'SELECT '
                        . 'sr.id, sr.property_id, sr.client_type, sr.client_id, sr.operated_by, sr.title, sr.description, '
                        . 'sr.created_date as created_date, reqst.name as status, r.name as request_type FROM service_request sr '
                        . 'INNER JOIN owner_profile op ON op.owner_id = sr.client_id '
                        . 'INNER JOIN users u ON u.id = sr.client_id '
                        . 'INNER JOIN leads_owner lo ON lo.email_id = u.login_id '
                        . 'INNER JOIN operations_profile ops ON ops.operations_id = op.operation_id '
                        . 'INNER JOIN sales_profile sp ON sp.sale_id = op.sales_id '
                        . 'LEFT JOIN request_status reqst ON reqst.id = sr.status '
                        . 'LEFT JOIN request r ON sr.request_type = r.id '
                        . 'WHERE  ' . $where . $where3 . ' '
                        . ''
                        . ''
                        . 'UNION ALL '
                        . ''
                        . ''
                        . 'SELECT '
                        . 'sr.id, sr.property_id, sr.client_type, sr.client_id, sr.operated_by, sr.title, sr.description, '
                        . 'sr.created_date as created_date, reqst.name as status, r.name as request_type FROM service_request sr '
                        . 'INNER JOIN tenant_profile tp ON tp.tenant_id = sr.client_id '
                        . 'INNER JOIN users u ON u.id = sr.client_id '
                        . 'INNER JOIN leads_tenant lt ON lt.email_id = u.login_id '
                        . 'INNER JOIN operations_profile ops ON ops.operations_id = tp.operation_id '
                        . 'INNER JOIN sales_profile sp ON sp.sale_id = tp.sales_id '
                        . 'LEFT JOIN request_status reqst ON reqst.id = sr.status '
                        . 'LEFT JOIN request r ON sr.request_type = r.id '
                        . 'WHERE  ' . $where2 . $where3 . ' '
                        . ''
                        . ''
                        . 'ORDER BY created_date DESC '
                )->queryAll();
                $osr = count($result);
                //////////////////////////////////////////////////////////////////////////////

                $days90 = date('Y-m-d H:i:s', strtotime("+90 days"));
                $pae = \app\models\PropertyAgreements::find()
                    ->where(['<=', 'contract_end_date', $days90])
                    ->andWhere(['>', 'contract_end_date', date('Y-m-d')])
                    ->count();

                $tae = \app\models\TenantAgreements::find()
                    ->where(['<=', 'lease_end_date', $days90])
                    ->andWhere(['>', 'lease_end_date', date('Y-m-d')])
                    ->count();

                $aae = \app\models\AdvisorAgreements::find()
                    ->where(['<=', 'end_date', $days90])
                    ->andWhere(['>', 'end_date', date('Y-m-d')])
                    ->count();

                $tt = \app\models\TenantAgreements::find()
                    ->select('tenant_agreements.id')
                    ->innerJoin('tenant_profile', '`tenant_agreements`.`tenant_id` = `tenant_profile`.`tenant_id`')
                    ->where(['>=', 'tenant_agreements.lease_end_date', date('Y-m-d')])
                    ->count();

                $tp = \app\models\Properties::find()->where(['status' => '1'])->count();

                $ta = \app\models\Users::find()->where(['user_type' => '5'])->count();

                $tpv = 0;
                $result2 = Yii::$app->db->createCommand(
                    'SELECT id '
                        . ' FROM properties '
                        . ' WHERE id NOT IN '
                        . ' (SELECT property_id FROM tenant_agreements WHERE lease_end_date >= ' . date('Y-m-d') . ') '
                        . ' AND status = "1" '
                )->queryAll();
                $tpv = count($result2);

                $tar = \app\models\users::find()->where('user_type ="2" and created_date like "' . date('Y-m-d') . '%"')->count();

                $tpol = \app\models\LeadsOwner::find()->where('created_date like "%' . date('Y-m-d') . '%"')->count();

                $tal = \app\models\LeadsAdvisor::find()->where('created_date like "%' . date('Y-m-d') . '%"')->count();

                $plt = \app\models\Properties::find()
                    ->leftJoin('child_properties', '`child_properties`.`main` = `properties`.`id`')
                    ->where(['like', 'child_properties.updated_date', date('Y-m-d')])
                    ->andWhere(['child_properties.status' => 1])
                    ->count();

                return $this->render('dashboard', ['osr' => $osr, 'pae' => $pae, 'tae' => $tae, 'aae' => $aae, 'tt' => $tt, 'tp' => $tp, 'ta' => $ta, 'tpv' => $tpv, 'tar' => $tar, 'tpol' => $tpol, 'tal' => $tal, 'plt' => $plt]);
            } else if ($currentRoleCode == 'OPSTMG') {
                //////////////// Total Outstanding Request ///////////////////////////////////
                $where = ' lo.lead_state = "' . $currentRoleValue . '" ';
                $where2 = ' lt.lead_state = "' . $currentRoleValue . '" ';
                $where3 = " AND sr.status <> 5 ";
                $result = Yii::$app->db->createCommand(
                    'SELECT '
                        . 'sr.id, sr.property_id, sr.client_type, sr.client_id, sr.operated_by, sr.title, sr.description, '
                        . 'sr.created_date as created_date, reqst.name as status, r.name as request_type FROM service_request sr '
                        . 'INNER JOIN owner_profile op ON op.owner_id = sr.client_id '
                        . 'INNER JOIN users u ON u.id = sr.client_id '
                        . 'INNER JOIN leads_owner lo ON lo.email_id = u.login_id '
                        . 'INNER JOIN operations_profile ops ON ops.operations_id = op.operation_id '
                        . 'INNER JOIN sales_profile sp ON sp.sale_id = op.sales_id '
                        . 'LEFT JOIN request_status reqst ON reqst.id = sr.status '
                        . 'LEFT JOIN request r ON sr.request_type = r.id '
                        . 'WHERE  ' . $where . $where3 . ' '
                        . ''
                        . ''
                        . 'UNION ALL '
                        . ''
                        . ''
                        . 'SELECT '
                        . 'sr.id, sr.property_id, sr.client_type, sr.client_id, sr.operated_by, sr.title, sr.description, '
                        . 'sr.created_date as created_date, reqst.name as status, r.name as request_type FROM service_request sr '
                        . 'INNER JOIN tenant_profile tp ON tp.tenant_id = sr.client_id '
                        . 'INNER JOIN users u ON u.id = sr.client_id '
                        . 'INNER JOIN leads_tenant lt ON lt.email_id = u.login_id '
                        . 'INNER JOIN operations_profile ops ON ops.operations_id = tp.operation_id '
                        . 'INNER JOIN sales_profile sp ON sp.sale_id = tp.sales_id '
                        . 'LEFT JOIN request_status reqst ON reqst.id = sr.status '
                        . 'LEFT JOIN request r ON sr.request_type = r.id '
                        . 'WHERE  ' . $where2 . $where3 . ' '
                        . ''
                        . ''
                        . 'ORDER BY created_date DESC '
                )->queryAll();
                $osr = count($result);
                //////////////////////////////////////////////////////////////////////////////

                $days90 = date('Y-m-d H:i:s', strtotime("+90 days"));

                $pae = \app\models\PropertyAgreements::find()
                    ->select('property_agreements.id')
                    ->leftJoin('properties', '`property_agreements`.`property_id` = `properties`.`id`')
                    ->where(['<=', 'property_agreements.contract_end_date', $days90])
                    ->andWhere(['>', 'property_agreements.contract_end_date', date('Y-m-d')])
                    ->andWhere(['properties.state' => $currentRoleValue])
                    ->count();

                $tae = \app\models\TenantAgreements::find()
                    ->select('tenant_agreements.id')
                    ->leftJoin('users', '`tenant_agreements`.`tenant_id` = `users`.`id`')
                    ->leftJoin('leads_tenant', '`users`.`login_id` = `leads_tenant`.`email_id`')
                    ->where(['<=', 'tenant_agreements.lease_end_date', $days90])
                    ->andWhere(['>', 'tenant_agreements.lease_end_date', date('Y-m-d')])
                    ->andWhere(['leads_tenant.lead_state' => $currentRoleValue])
                    ->count();

                $aae = \app\models\AdvisorAgreements::find()
                    ->select('advisor_agreements.id')
                    ->leftJoin('users', '`advisor_agreements`.`advisor_id` = `users`.`id`')
                    ->leftJoin('leads_advisor', '`users`.`login_id` = `leads_advisor`.`email_id`')
                    ->where(['<=', 'advisor_agreements.end_date', $days90])
                    ->andWhere(['>', 'advisor_agreements.end_date', date('Y-m-d')])
                    ->andWhere(['leads_advisor.lead_state' => $currentRoleValue])
                    ->count();

                $tt = \app\models\TenantAgreements::find()
                    ->select('tenant_agreements.id')
                    ->innerJoin('tenant_profile', '`tenant_agreements`.`tenant_id` = `tenant_profile`.`tenant_id`')
                    ->where(['>=', 'tenant_agreements.lease_end_date', date('Y-m-d')])
                    ->andWhere(['tenant_profile.state' => $currentRoleValue])
                    ->count();

                $tp = \app\models\Properties::find()
                    ->where(['status' => '1', 'state' => $currentRoleValue])
                    ->count();

                $ta = \app\models\Users::find()
                    ->select('users.id')
                    ->leftJoin('leads_advisor', '`users`.`login_id` = `leads_advisor`.`email_id`')
                    ->where(['users.user_type' => '5', 'users.status' => '1'])
                    ->andWhere(['leads_advisor.lead_state' => $currentRoleValue])
                    ->count();

                $tpv = 0;
                $result2 = Yii::$app->db->createCommand(
                    'SELECT id '
                        . ' FROM properties '
                        . ' WHERE id NOT IN '
                        . ' (SELECT property_id FROM tenant_agreements WHERE lease_end_date >= ' . date('Y-m-d') . ') '
                        . ' AND status = "1" AND state = "' . $currentRoleValue . '" '
                )->queryAll();
                $tpv = count($result2);

                $tar = \app\models\Users::find()
                    ->select('users.*')
                    ->leftJoin('applicant_profile', '`users`.`id` = `applicant_profile`.`applicant_id`')
                    ->where(['users.user_type' => '2'])
                    ->andWhere(['like', 'users.created_date', date('Y-m-d')])
                    ->andWhere(['applicant_profile.state' => $currentRoleValue])
                    ->count();

                $tpol = \app\models\Users::find()
                    ->select('users.*')
                    ->leftJoin('leads_owner', '`users`.`login_id` = `leads_owner`.`email_id`')
                    ->where(['users.user_type' => '4'])
                    ->andWhere(['like', 'users.created_date', date('Y-m-d')])
                    ->andWhere(['leads_owner.lead_state' => $currentRoleValue])
                    ->count();

                $tal = \app\models\Users::find()
                    ->select('users.*')
                    ->leftJoin('leads_advisor', '`users`.`login_id` = `leads_advisor`.`email_id`')
                    ->where(['users.user_type' => '5'])
                    ->andWhere(['like', 'users.created_date', date('Y-m-d')])
                    ->andWhere(['leads_advisor.lead_state' => $currentRoleValue])
                    ->count();

                $plt = \app\models\Properties::find()
                    ->leftJoin('child_properties', '`child_properties`.`main` = `properties`.`id`')
                    ->where(['properties.state' => $currentRoleValue])
                    ->andWhere(['like', 'child_properties.updated_date', date('Y-m-d')])
                    ->andWhere(['child_properties.status' => 1])
                    ->count();

                return $this->render('dashboard', ['osr' => $osr, 'pae' => $pae, 'tae' => $tae, 'aae' => $aae, 'tt' => $tt, 'tp' => $tp, 'ta' => $ta, 'tpv' => $tpv, 'tar' => $tar, 'tpol' => $tpol, 'tal' => $tal, 'plt' => $plt]);
            } else if ($currentRoleCode == 'OPCTMG') {
                //////////////// Total Outstanding Request ///////////////////////////////////
                $where = ' lo.lead_city = "' . $currentRoleValue . '" ';
                $where2 = ' lt.lead_city = "' . $currentRoleValue . '" ';
                $where3 = " AND sr.status <> 5 ";
                $result = Yii::$app->db->createCommand(
                    'SELECT '
                        . 'sr.id, sr.property_id, sr.client_type, sr.client_id, sr.operated_by, sr.title, sr.description, '
                        . 'sr.created_date as created_date, reqst.name as status, r.name as request_type FROM service_request sr '
                        . 'INNER JOIN owner_profile op ON op.owner_id = sr.client_id '
                        . 'INNER JOIN users u ON u.id = sr.client_id '
                        . 'INNER JOIN leads_owner lo ON lo.email_id = u.login_id '
                        . 'INNER JOIN operations_profile ops ON ops.operations_id = op.operation_id '
                        . 'INNER JOIN sales_profile sp ON sp.sale_id = op.sales_id '
                        . 'LEFT JOIN request_status reqst ON reqst.id = sr.status '
                        . 'LEFT JOIN request r ON sr.request_type = r.id '
                        . 'WHERE  ' . $where . $where3 . ' '
                        . ''
                        . ''
                        . 'UNION ALL '
                        . ''
                        . ''
                        . 'SELECT '
                        . 'sr.id, sr.property_id, sr.client_type, sr.client_id, sr.operated_by, sr.title, sr.description, '
                        . 'sr.created_date as created_date, reqst.name as status, r.name as request_type FROM service_request sr '
                        . 'INNER JOIN tenant_profile tp ON tp.tenant_id = sr.client_id '
                        . 'INNER JOIN users u ON u.id = sr.client_id '
                        . 'INNER JOIN leads_tenant lt ON lt.email_id = u.login_id '
                        . 'INNER JOIN operations_profile ops ON ops.operations_id = tp.operation_id '
                        . 'INNER JOIN sales_profile sp ON sp.sale_id = tp.sales_id '
                        . 'LEFT JOIN request_status reqst ON reqst.id = sr.status '
                        . 'LEFT JOIN request r ON sr.request_type = r.id '
                        . 'WHERE  ' . $where2 . $where3 . ' '
                        . ''
                        . ''
                        . 'ORDER BY created_date DESC '
                )->queryAll();
                $osr = count($result);
                //////////////////////////////////////////////////////////////////////////////

                $days90 = date('Y-m-d H:i:s', strtotime("+90 days"));

                $pae = \app\models\PropertyAgreements::find()
                    ->select('property_agreements.id')
                    ->leftJoin('properties', '`property_agreements`.`property_id` = `properties`.`id`')
                    ->where(['<=', 'property_agreements.contract_end_date', $days90])
                    ->andWhere(['>', 'property_agreements.contract_end_date', date('Y-m-d')])
                    ->andWhere(['properties.city' => $currentRoleValue])
                    ->count();

                $tae = \app\models\TenantAgreements::find()
                    ->select('tenant_agreements.id')
                    ->leftJoin('users', '`tenant_agreements`.`tenant_id` = `users`.`id`')
                    ->leftJoin('leads_tenant', '`users`.`login_id` = `leads_tenant`.`email_id`')
                    ->where(['<=', 'tenant_agreements.lease_end_date', $days90])
                    ->andWhere(['>', 'tenant_agreements.lease_end_date', date('Y-m-d')])
                    ->andWhere(['leads_tenant.lead_city' => $currentRoleValue])
                    ->count();

                $aae = \app\models\AdvisorAgreements::find()
                    ->select('advisor_agreements.id')
                    ->leftJoin('users', '`advisor_agreements`.`advisor_id` = `users`.`id`')
                    ->leftJoin('leads_advisor', '`users`.`login_id` = `leads_advisor`.`email_id`')
                    ->where(['<=', 'advisor_agreements.end_date', $days90])
                    ->andWhere(['>', 'advisor_agreements.end_date', date('Y-m-d')])
                    ->andWhere(['leads_advisor.lead_city' => $currentRoleValue])
                    ->count();

                $tt = \app\models\TenantAgreements::find()
                    ->select('tenant_agreements.id')
                    ->innerJoin('tenant_profile', '`tenant_agreements`.`tenant_id` = `tenant_profile`.`tenant_id`')
                    ->where(['>=', 'tenant_agreements.lease_end_date', date('Y-m-d')])
                    ->andWhere(['tenant_profile.city' => $currentRoleValue])
                    ->count();

                $tp = \app\models\Properties::find()
                    ->where(['status' => '1', 'city' => $currentRoleValue])
                    ->count();

                $ta = \app\models\Users::find()
                    ->select('users.id')
                    ->leftJoin('leads_advisor', '`users`.`login_id` = `leads_advisor`.`email_id`')
                    ->where(['users.user_type' => '5', 'users.status' => '1'])
                    ->andWhere(['leads_advisor.lead_city' => $currentRoleValue])
                    ->count();

                $tpv = 0;
                $result2 = Yii::$app->db->createCommand(
                    'SELECT id '
                        . ' FROM properties '
                        . ' WHERE id NOT IN '
                        . ' (SELECT property_id FROM tenant_agreements WHERE lease_end_date >= ' . date('Y-m-d') . ') '
                        . ' AND status = "1" AND city = "' . $currentRoleValue . '" '
                )->queryAll();
                $tpv = count($result2);

                $tar = \app\models\Users::find()
                    ->select('users.*')
                    ->leftJoin('applicant_profile', '`users`.`id` = `applicant_profile`.`applicant_id`')
                    ->where(['users.user_type' => '2'])
                    ->andWhere(['like', 'users.created_date', date('Y-m-d')])
                    ->andWhere(['applicant_profile.city' => $currentRoleValue])
                    ->count();

                $tpol = \app\models\Users::find()
                    ->select('users.*')
                    ->leftJoin('leads_owner', '`users`.`login_id` = `leads_owner`.`email_id`')
                    ->where(['users.user_type' => '4'])
                    ->andWhere(['like', 'users.created_date', date('Y-m-d')])
                    ->andWhere(['leads_owner.lead_city' => $currentRoleValue])
                    ->count();

                $tal = \app\models\Users::find()
                    ->select('users.*')
                    ->leftJoin('leads_advisor', '`users`.`login_id` = `leads_advisor`.`email_id`')
                    ->where(['users.user_type' => '5'])
                    ->andWhere(['like', 'users.created_date', date('Y-m-d')])
                    ->andWhere(['leads_advisor.lead_city' => $currentRoleValue])
                    ->count();

                $plt = \app\models\Properties::find()
                    ->leftJoin('child_properties', '`child_properties`.`main` = `properties`.`id`')
                    ->where(['properties.city' => $currentRoleValue])
                    ->andWhere(['like', 'child_properties.updated_date', date('Y-m-d')])
                    ->andWhere(['child_properties.status' => 1])
                    ->count();

                return $this->render('dashboard', ['osr' => $osr, 'pae' => $pae, 'tae' => $tae, 'aae' => $aae, 'tt' => $tt, 'tp' => $tp, 'ta' => $ta, 'tpv' => $tpv, 'tar' => $tar, 'tpol' => $tpol, 'tal' => $tal, 'plt' => $plt]);
            } else if ($currentRoleCode == 'OPBRMG') {

                //////////////// Total Outstanding Request ///////////////////////////////////
                $where = ' lo.branch_code = "' . $currentRoleValue . '" ';
                $where2 = ' lt.branch_code = "' . $currentRoleValue . '" ';
                $where3 = " AND sr.status <> 5 ";
                $result = Yii::$app->db->createCommand(
                    'SELECT '
                        . 'sr.id, sr.property_id, sr.client_type, sr.client_id, sr.operated_by, sr.title, sr.description, '
                        . 'sr.created_date as created_date, reqst.name as status, r.name as request_type FROM service_request sr '
                        . 'INNER JOIN owner_profile op ON op.owner_id = sr.client_id '
                        . 'INNER JOIN users u ON u.id = sr.client_id '
                        . 'INNER JOIN leads_owner lo ON lo.email_id = u.login_id '
                        . 'INNER JOIN operations_profile ops ON ops.operations_id = op.operation_id '
                        . 'INNER JOIN sales_profile sp ON sp.sale_id = op.sales_id '
                        . 'LEFT JOIN request_status reqst ON reqst.id = sr.status '
                        . 'LEFT JOIN request r ON sr.request_type = r.id '
                        . 'WHERE  ' . $where . $where3 . ' '
                        . ''
                        . ''
                        . 'UNION ALL '
                        . ''
                        . ''
                        . 'SELECT '
                        . 'sr.id, sr.property_id, sr.client_type, sr.client_id, sr.operated_by, sr.title, sr.description, '
                        . 'sr.created_date as created_date, reqst.name as status, r.name as request_type FROM service_request sr '
                        . 'INNER JOIN tenant_profile tp ON tp.tenant_id = sr.client_id '
                        . 'INNER JOIN users u ON u.id = sr.client_id '
                        . 'INNER JOIN leads_tenant lt ON lt.email_id = u.login_id '
                        . 'INNER JOIN operations_profile ops ON ops.operations_id = tp.operation_id '
                        . 'INNER JOIN sales_profile sp ON sp.sale_id = tp.sales_id '
                        . 'LEFT JOIN request_status reqst ON reqst.id = sr.status '
                        . 'LEFT JOIN request r ON sr.request_type = r.id '
                        . 'WHERE  ' . $where2 . $where3 . ' '
                        . ''
                        . ''
                        . 'ORDER BY created_date DESC '
                )->queryAll();
                $osr = count($result);
                //////////////////////////////////////////////////////////////////////////////

                $days90 = date('Y-m-d H:i:s', strtotime("+90 days"));

                $pae = \app\models\PropertyAgreements::find()
                    ->select('property_agreements.id')
                    ->leftJoin('properties', '`property_agreements`.`property_id` = `properties`.`id`')
                    ->where(['<=', 'property_agreements.contract_end_date', $days90])
                    ->andWhere(['>', 'property_agreements.contract_end_date', date('Y-m-d')])
                    ->andWhere(['properties.branch_code' => $currentRoleValue])
                    ->count();

                $tae = \app\models\TenantAgreements::find()
                    ->select('tenant_agreements.id')
                    ->leftJoin('users', '`tenant_agreements`.`tenant_id` = `users`.`id`')
                    ->leftJoin('leads_tenant', '`users`.`login_id` = `leads_tenant`.`email_id`')
                    ->where(['<=', 'tenant_agreements.lease_end_date', $days90])
                    ->andWhere(['>', 'tenant_agreements.lease_end_date', date('Y-m-d')])
                    ->andWhere(['leads_tenant.branch_code' => $currentRoleValue])
                    ->count();

                $aae = \app\models\AdvisorAgreements::find()
                    ->select('advisor_agreements.id')
                    ->leftJoin('users', '`advisor_agreements`.`advisor_id` = `users`.`id`')
                    ->leftJoin('leads_advisor', '`users`.`login_id` = `leads_advisor`.`email_id`')
                    ->where(['<=', 'advisor_agreements.end_date', $days90])
                    ->andWhere(['>', 'advisor_agreements.end_date', date('Y-m-d')])
                    ->andWhere(['leads_advisor.branch_code' => $currentRoleValue])
                    ->count();


                $tt = \app\models\TenantAgreements::find()
                    ->select('tenant_agreements.id')
                    ->innerJoin('tenant_profile', '`tenant_agreements`.`tenant_id` = `tenant_profile`.`tenant_id`')
                    ->where(['>=', 'tenant_agreements.lease_end_date', date('Y-m-d')])
                    ->andWhere(['tenant_profile.branch_code' => $currentRoleValue])
                    ->count();

                $tp = \app\models\Properties::find()
                    ->where(['status' => '1', 'branch_code' => $currentRoleValue])
                    ->count();

                $ta = \app\models\Users::find()
                    ->select('users.id')
                    ->leftJoin('leads_advisor', '`users`.`login_id` = `leads_advisor`.`email_id`')
                    ->where(['users.user_type' => '5', 'users.status' => '1'])
                    ->andWhere(['leads_advisor.branch_code' => $currentRoleValue])
                    ->count();

                $tpv = 0;
                $result2 = Yii::$app->db->createCommand(
                    'SELECT id '
                        . ' FROM properties '
                        . ' WHERE id NOT IN '
                        . ' (SELECT property_id FROM tenant_agreements WHERE lease_end_date >= ' . date('Y-m-d') . ') '
                        . ' AND status = "1" AND branch_code = "' . $currentRoleValue . '" '
                )->queryAll();
                $tpv = count($result2);

                $tar = \app\models\Users::find()
                    ->select('users.*')
                    ->leftJoin('applicant_profile', '`users`.`id` = `applicant_profile`.`applicant_id`')
                    ->where(['users.user_type' => '2'])
                    ->andWhere(['like', 'users.created_date', date('Y-m-d')])
                    ->andWhere(['applicant_profile.branch_code' => $currentRoleValue])
                    ->count();

                $tpol = \app\models\Users::find()
                    ->select('users.*')
                    ->leftJoin('leads_owner', '`users`.`login_id` = `leads_owner`.`email_id`')
                    ->where(['users.user_type' => '4'])
                    ->andWhere(['like', 'users.created_date', date('Y-m-d')])
                    ->andWhere(['leads_owner.branch_code' => $currentRoleValue])
                    ->count();

                $tal = \app\models\Users::find()
                    ->select('users.*')
                    ->leftJoin('leads_advisor', '`users`.`login_id` = `leads_advisor`.`email_id`')
                    ->where(['users.user_type' => '5'])
                    ->andWhere(['like', 'users.created_date', date('Y-m-d')])
                    ->andWhere(['leads_advisor.branch_code' => $currentRoleValue])
                    ->count();

                $plt = \app\models\Properties::find()
                    ->leftJoin('child_properties', '`child_properties`.`main` = `properties`.`id`')
                    ->where(['properties.branch_code' => $currentRoleValue])
                    ->andWhere(['like', 'child_properties.updated_date', date('Y-m-d')])
                    ->andWhere(['child_properties.status' => 1])
                    ->count();

                return $this->render('dashboard', ['osr' => $osr, 'pae' => $pae, 'tae' => $tae, 'aae' => $aae, 'tt' => $tt, 'tp' => $tp, 'ta' => $ta, 'tpv' => $tpv, 'tar' => $tar, 'tpol' => $tpol, 'tal' => $tal, 'plt' => $plt]);
            } else if ($currentRoleCode == 'OPEXE') {

                //////////////// Total Outstanding Request ///////////////////////////////////
                $where = ' sr.operated_by = ' . Yii::$app->user->id . ' ';
                $where2 = ' sr.operated_by = ' . Yii::$app->user->id . ' ';
                $where3 = " AND sr.status <> 5 ";
                $result = Yii::$app->db->createCommand(
                    'SELECT '
                        . 'sr.id, sr.property_id, sr.client_type, sr.client_id, sr.operated_by, sr.title, sr.description, '
                        . 'sr.created_date as created_date, reqst.name as status, r.name as request_type FROM service_request sr '
                        . 'INNER JOIN owner_profile op ON op.owner_id = sr.client_id '
                        . 'INNER JOIN users u ON u.id = sr.client_id '
                        . 'INNER JOIN leads_owner lo ON lo.email_id = u.login_id '
                        . 'INNER JOIN operations_profile ops ON ops.operations_id = op.operation_id '
                        . 'INNER JOIN sales_profile sp ON sp.sale_id = op.sales_id '
                        . 'LEFT JOIN request_status reqst ON reqst.id = sr.status '
                        . 'LEFT JOIN request r ON sr.request_type = r.id '
                        . 'WHERE  ' . $where . $where3 . ' '
                        . ''
                        . ''
                        . 'UNION ALL '
                        . ''
                        . ''
                        . 'SELECT '
                        . 'sr.id, sr.property_id, sr.client_type, sr.client_id, sr.operated_by, sr.title, sr.description, '
                        . 'sr.created_date as created_date, reqst.name as status, r.name as request_type FROM service_request sr '
                        . 'INNER JOIN tenant_profile tp ON tp.tenant_id = sr.client_id '
                        . 'INNER JOIN users u ON u.id = sr.client_id '
                        . 'INNER JOIN leads_tenant lt ON lt.email_id = u.login_id '
                        . 'INNER JOIN operations_profile ops ON ops.operations_id = tp.operation_id '
                        . 'INNER JOIN sales_profile sp ON sp.sale_id = tp.sales_id '
                        . 'LEFT JOIN request_status reqst ON reqst.id = sr.status '
                        . 'LEFT JOIN request r ON sr.request_type = r.id '
                        . 'WHERE  ' . $where2 . $where3 . ' '
                        . ''
                        . ''
                        . 'ORDER BY created_date DESC '
                )->queryAll();
                $osr = count($result);
                //////////////////////////////////////////////////////////////////////////////

                $days90 = date('Y-m-d H:i:s', strtotime("+90 days"));

                $pae = \app\models\PropertyAgreements::find()
                    ->select('property_agreements.id')
                    ->leftJoin('properties', '`property_agreements`.`property_id` = `properties`.`id`')
                    ->where(['<=', 'property_agreements.contract_end_date', $days90])
                    ->andWhere(['>', 'property_agreements.contract_end_date', date('Y-m-d')])
                    ->andWhere(['properties.operations_id' => Yii::$app->user->id])
                    ->count();

                $tae = \app\models\TenantAgreements::find()
                    ->select('tenant_agreements.id')
                    ->leftJoin('users', '`tenant_agreements`.`tenant_id` = `users`.`id`')
                    ->leftJoin('leads_tenant', '`users`.`login_id` = `leads_tenant`.`email_id`')
                    ->leftJoin('tenant_profile', '`users`.`id` = `tenant_profile`.`tenant_id`')
                    ->where(['<=', 'tenant_agreements.lease_end_date', $days90])
                    ->andWhere(['>', 'tenant_agreements.lease_end_date', date('Y-m-d')])
                    ->andWhere(['tenant_profile.operation_id' => Yii::$app->user->id])
                    ->count();

                $aae = \app\models\AdvisorAgreements::find()
                    ->select('advisor_agreements.id')
                    ->leftJoin('users', '`advisor_agreements`.`advisor_id` = `users`.`id`')
                    ->leftJoin('leads_advisor', '`users`.`login_id` = `leads_advisor`.`email_id`')
                    ->leftJoin('advisor_profile', '`users`.`id` = `advisor_profile`.`advisor_id`')
                    ->where(['<=', 'advisor_agreements.end_date', $days90])
                    ->andWhere(['>', 'advisor_agreements.end_date', date('Y-m-d')])
                    ->andWhere(['advisor_profile.operation_id' => Yii::$app->user->id])
                    ->count();

                $tt = \app\models\TenantAgreements::find()
                    ->select('tenant_agreements.id')
                    ->innerJoin('tenant_profile', '`tenant_agreements`.`tenant_id` = `tenant_profile`.`tenant_id`')
                    ->where(['>=', 'tenant_agreements.lease_end_date', date('Y-m-d')])
                    ->andWhere(['tenant_profile.operation_id' => Yii::$app->user->id])
                    ->count();

                $tp = \app\models\Properties::find()
                    ->where(['status' => '1', 'operations_id' => Yii::$app->user->id])
                    ->count();

                $ta = \app\models\Users::find()
                    ->select('users.id')
                    ->leftJoin('leads_advisor', '`users`.`login_id` = `leads_advisor`.`email_id`')
                    ->leftJoin('advisor_profile', '`users`.`id` = `advisor_profile`.`advisor_id`')
                    ->where(['users.user_type' => '5', 'users.status' => '1'])
                    ->andWhere(['advisor_profile.operation_id' => Yii::$app->user->id])
                    ->count();

                $tpv = 0;
                $result2 = Yii::$app->db->createCommand(
                    'SELECT id '
                        . ' FROM properties '
                        . ' WHERE id NOT IN '
                        . ' (SELECT property_id FROM tenant_agreements WHERE lease_end_date >= ' . date('Y-m-d') . ') '
                        . ' AND status = "1" AND operations_id = "' . Yii::$app->user->id . '" '
                )->queryAll();
                $tpv = count($result2);

                $tar = \app\models\Users::find()
                    ->select('users.*')
                    ->leftJoin('applicant_profile', '`users`.`id` = `applicant_profile`.`applicant_id`')
                    ->where(['users.user_type' => '2'])
                    ->andWhere(['like', 'users.created_date', date('Y-m-d')])
                    ->andWhere(['applicant_profile.operation_id' => Yii::$app->user->id])
                    ->count();

                $tpol = \app\models\Users::find()
                    ->select('users.*')
                    ->leftJoin('leads_owner', '`users`.`login_id` = `leads_owner`.`email_id`')
                    ->leftJoin('owner_profile', '`users`.`id` = `owner_profile`.`owner_id`')
                    ->where(['users.user_type' => '4'])
                    ->andWhere(['like', 'users.created_date', date('Y-m-d')])
                    ->andWhere(['owner_profile.operation_id' => Yii::$app->user->id])
                    ->count();

                $tal = \app\models\Users::find()
                    ->select('users.*')
                    ->leftJoin('leads_advisor', '`users`.`login_id` = `leads_advisor`.`email_id`')
                    ->leftJoin('advisor_profile', '`users`.`id` = `advisor_profile`.`advisor_id`')
                    ->where(['users.user_type' => '5'])
                    ->andWhere(['like', 'users.created_date', date('Y-m-d')])
                    ->andWhere(['advisor_profile.operation_id' => Yii::$app->user->id])
                    ->count();

                $plt = \app\models\Properties::find()
                    ->leftJoin('child_properties', '`child_properties`.`main` = `properties`.`id`')
                    ->where(['properties.operations_id' => Yii::$app->user->id])
                    ->andWhere(['like', 'child_properties.updated_date', date('Y-m-d')])
                    ->andWhere(['child_properties.status' => 1])
                    ->count();

                return $this->render('dashboard', ['osr' => $osr, 'pae' => $pae, 'tae' => $tae, 'aae' => $aae, 'tt' => $tt, 'tp' => $tp, 'ta' => $ta, 'tpv' => $tpv, 'tar' => $tar, 'tpol' => $tpol, 'tal' => $tal, 'plt' => $plt]);
            }
        } else {
            $connection = Yii::$app->getDb();
            $command = $connection->createCommand('select * from leads_tenant where DATE(`follow_up_date_time`)=CURDATE()');
            $countApplicant = count($command->queryAll());

            $command1 = $connection->createCommand('select * from leads_owner where DATE(`follow_up_date_time`)=CURDATE()');
            $countOwner = count($command1->queryAll());

            $command2 = $connection->createCommand('select * from leads_advisor where DATE(`follow_up_date_time`)=CURDATE() OR DATE(`schedule_date_time`)=CURDATE()');
            $countAdviser = count($command2->queryAll());

            return $this->render('sales_dashboard', array('applicant' => $countApplicant, 'adviser' => $countAdviser, 'owner' => $countOwner));
        }
    }

    public function actionAddcomment()
    {

        $model = new \app\models\Comments;
        $model->user_id = $_POST['user_id'];
        $model->description = $_POST['comments'];
        $model->created_by = Yii::$app->user->id;
        $model->save(false);
        if ($model->save(false)) {
            echo "done";
        }
    }

    public function actionAddcomment1()
    {

        $model = new \app\models\Comments;
        $model->user_id = $_POST['Comments']['user_id'];
        $model->description = $_POST['Comments']['description'];
        $model->created_by = Yii::$app->user->id;
        $model->save(false);
        if ($model->save(false)) {
            echo "done";
        }
    }

    public function actionCreateapplicant()
    {
        $model = new LeadsTenant;
        $modelusers = new Users;
        $modelprofile = new ApplicantProfile;
        $comments = new \app\models\Comments;
        $modelWallet = new \app\models\Wallets();

        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = 'json';
            return \yii\bootstrap\ActiveForm::validate($model);
        } else if ($model->load(Yii::$app->request->post()) && $modelprofile->load(Yii::$app->request->post())) {
            if ($model->validate() && $modelprofile->validate(['city_name'])) {
                try {
                    $date = $_POST['follow_up_date'];
                    $time = $_POST['follow_up_time'];
                    $date_formed = Date('Y-m-d h:i:s', strtotime($date . " " . $time));

                    $rand = Yii::$app->userdata->passwordGenerate();
                    $modelusers->full_name = $model->full_name;
                    $modelusers->login_id = $model->email_id;
                    $modelusers->username = $model->email_id;
                    $modelusers->password = md5($rand);
                    $modelusers->phone = $model->contact_number;
                    $modelusers->user_type = 2;
                    $modelusers->status = 1;
                    $modelusers->created_by = Yii::$app->user->id;
                    $modelusers->auth_key = base64_encode($model->email_id . time() . rand(1000, 9999));
                    $modelusers->save(false);

                    $modelWallet->amount = 0;
                    $modelWallet->user_id = $modelusers->id;
                    $modelWallet->created_by = $modelusers->id;
                    $modelWallet->created_date = Date('Y-m-d H:i:s');
                    $modelWallet->save(false);

                    $modelprofile->applicant_id = $modelusers->id;
                    if (Yii::$app->user->identity->user_type == 6) {
                        $modelprofile->sales_id = Yii::$app->user->id;
                    } else {
                        $modelprofile->operation_id = Yii::$app->user->id;
                    }

                    $modelprofile->email_id = $model->email_id;
                    $modelprofile->address_line_1 = $model->address;
                    $modelprofile->address_line_2 = $model->address_line_2;
                    $modelprofile->branch_code = $model->branch_code;
                    $modelprofile->pincode = $model->pincode;
                    $modelprofile->phone = $modelusers->phone;
                    $modelprofile->save(false);


                    $model->lead_state = Yii::$app->userdata->getStateCodeByCityCode($model->lead_city);
                    $model->created_by = Yii::$app->user->id;
                    $model->created_date = date('Y-m-d H:i:s');
                    $model->follow_up_date_time = $date_formed;

                    $comments->description = $model->communication;
                    $comments->user_id = $modelusers->id;
                    $comments->created_by = Yii::$app->user->id;
                    $comments->save(false);

                    if ($model->save(false)) {
                        $to = $model->email_id;
                        $subject = "Account Created";
                        $msg = "Hello " . $model->full_name . ",<br /><br />Greetings from Easyleases!!!<br/><br/>Your login credentials are provided below. You may search for properties on our website 'www.easyleases.in'. Our sales representative will also be in touch with you to assist you with finding your dream home.<br/>   <b>User Id:</b>:" . $model->email_id . "<br/> <b>Password</b>:" . $rand . "<br/><br/>With Regards,<br/>Easyleases Team<br/><img src='" . Url::home(true) . "images/property_logo.png' alt='' width='150px' height='40px'>";

                        $email = Yii::$app->userdata->doMail($to, $subject, $msg);
                        //$email = 1;
                        if ($email) {
                            Yii::$app->getSession()->setFlash('success', 'Applicant Registered Successfully');
                        } else {
                            Yii::$app->getSession()->setFlash('success', 'Email not sent, Applicant Registered Successfully');
                        }
                        return $this->redirect(['applicantsdetails?id=' . base64_encode($model->id)]);
                    } else {
                        Yii::$app->getSession()->setFlash(
                            'success',
                            'Applicant is not created'
                        );
                        return $this->redirect(['applicantsdetails?id=' . base64_encode($model->id)]);
                    }
                } catch (\Exception $e) {
                    Yii::$app->getSession()->setFlash(
                        'success',
                        "{$e->getMessage()}"
                    );
                    return $this->renderAjax('_form', [
                        'model' => $model,
                        'modelprofile' => $modelprofile
                    ]);
                }
            } else {
                return $this->renderAjax('_form', [
                    'model' => $model,
                    'modelprofile' => $modelprofile
                ]);
            }
        } else {
            return $this->renderAjax('_form', [
                'model' => $model,
                'modelprofile' => $modelprofile
            ]);
        }
    }

    public function actionAddagreement($id)
    {

        Yii::$app->assetManager->bundles['yii\web\JqueryAsset'] = false;
        Yii::$app->assetManager->bundles['yii\web\YiiAsset'] = false;

        $model = \app\models\Properties::findOne($id);
        $modelImages = \app\models\PropertyImages::find()->where(['property_id' => $id])->all();
        $modelPropertyAgreements = \app\models\PropertyAgreements::findOne(['property_id' => $id]);
        if (empty($modelPropertyAgreements)) {
            $modelPropertyAgreements = new \app\models\PropertyAgreements;
        }
        if ($modelPropertyAgreements->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = 'json';
            return \yii\bootstrap\ActiveForm::validate($modelPropertyAgreements);
        } /* else if($modelPropertyAgreements->load(Yii::$app->request->post())){

          try{
          $agree_url = UploadedFile::getInstance($modelPropertyAgreements, 'agreement_url');
          if($agree_url){
          $agreement = date('ymdHis').$agree_url;
          $agree_url->saveAs('uploads/' . $agreement );

          $modelPropertyAgreements->agreement_url =  Url::home(true). 'uploads/'.$agreement ;
          }
          $modelPropertyAgreements->updated_by = Yii::$app->user->id ;
          $modelPropertyAgreements->updated_date = date('Y-m-d H:i:s');

          $modelPropertyAgreements->save(false);


          Yii::$app->getSession()->setFlash(
          'success','Agreement updated successfully'
          );

          $ids  = Yii::$app->userdata->getUserEmailById($model->owner_id) ;
          $idLead  = Yii::$app->userdata->getLeadByEmailId($ids) ;
          if($_POST['redirection']==0){
          return $this->redirect(['ownerssdetails','id' => base64_encode($idLead)]);
          }
          else{
          return $this->redirect('properties');
          }




          }catch(Exception $e){

          Yii::$app->getSession()->setFlash(
          'success','Property updated successfully'
          );

          $ids  = Yii::$app->userdata->getUserEmailById($model->owner_id) ;
          $idLead  = Yii::$app->userdata->getLeadByEmailId($ids) ;
          if($_POST['redirection']==0){
          return $this->redirect(['ownerssdetails','id' => base64_encode($idLead)]);
          }
          else{
          return $this->redirect('properties');
          }


          // return $this->redirect(['ownerssdetails','id' => base64_encode($idLead)]);

          }



          } */ else {

            return $this->renderAjax('edit_property', [
                'model' => $model,
                'modelImages' => $modelImages,
                'modelPropertyAgreements' => $modelPropertyAgreements,
            ]);
        }
        //  die($id);
    }

    public function actionAddagreementajax($id)
    {
        $model = \app\models\Properties::findOne($id);
        $modelImages = \app\models\PropertyImages::find()->where(['property_id' => $id])->all();
        $modelPropertyAgreements = \app\models\PropertyAgreements::findOne(['property_id' => $id]);

        if ($modelPropertyAgreements == null) {
            $modelPropertyAgreements = new \app\models\PropertyAgreements;
        }

        if ($modelPropertyAgreements->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            if ($modelPropertyAgreements->validate()) {
                if ($_FILES['PropertyAgreements']['size']['agreement_url'] != 0) {
                    $name_address_proof = (string) date('ymdHis') . rand() . str_replace(" ", "", $_FILES['PropertyAgreements']['name']['agreement_url']);
                    move_uploaded_file($_FILES['PropertyAgreements']['tmp_name']['agreement_url'], 'uploads/' . $name_address_proof);
                    $modelPropertyAgreements->agreement_url = 'uploads/' . $name_address_proof;
                } else {
                    if (\app\models\PropertyAgreements::find(['property_id' => $id]) == null) {
                    } else {
                        $agreement_urls = \app\models\PropertyAgreements::findOne(['property_id' => $id]);

                        if ($agreement_urls != null) {
                            $modelPropertyAgreements->agreement_url = $agreement_urls->agreement_url;
                        }
                    }
                }

                foreach ($_POST['PropertyAgreements'] as $key => $value) {
                    if ($key != 'agreement_url' && $key != 'id') {
                        $modelPropertyAgreements->$key = $_POST['PropertyAgreements'][$key];
                    }
                }

                $modelPropertyAgreements->updated_by = Yii::$app->user->id;
                $modelPropertyAgreements->updated_date = date('Y-m-d H:i:s');

                $modelPropertyAgreements->save(false);

                echo json_encode(array('success' => 1, 'url' => $modelPropertyAgreements->agreement_url));
            } else {
                if ($_POST['agreement1'] == "1") {
                    echo json_encode($modelPropertyAgreements->getErrors());
                } else {
                    echo json_encode(array('success' => 1, 'url' => $modelPropertyAgreements->agreement_url));
                }
            }
        }
    }

    public function actionSetasdefault($id)
    {
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("UPDATE property_images SET is_main= 0 where property_id=$_POST[property_id]");
        $command->execute();
        $command2 = $connection->createCommand("UPDATE property_images SET is_main= 1 where id=$id");
        $command2->execute();
    }

    public function actionAddnewagreement($id)
    {



        $modelPropertyAgreements = new \app\models\PropertyAgreements;

        if ($modelPropertyAgreements->load(Yii::$app->request->post())) {
            $model = \app\models\Properties::findOne($modelPropertyAgreements->property_id);
            $agree_url = UploadedFile::getInstance($modelPropertyAgreements, 'agreement_url');
            if ($agree_url) {
                $agreement = date('ymdHis') . $agree_url;
                $agree_url->saveAs('uploads/' . $agreement);

                // $modelPropertyAgreements->agreement_url =  Url::home(true). 'uploads/'.$agreement ;
                $modelPropertyAgreements->agreement_url = 'uploads/' . $agreement;
            }
            $modelPropertyAgreements->created_by = Yii::$app->user->id;
            $modelPropertyAgreements->created_date = date('Y-m-d H:i:s');
            $modelPropertyAgreements->save(false);

            // $PropertyListing = new \app\models\PropertyListing ;
            // $PropertyListing->property_id = $modelPropertyAgreements->property_id;
            // $PropertyListing->rent = $modelPropertyAgreements->rent;
            // $PropertyListing->deposit = $modelPropertyAgreements->deposit;
            // $PropertyListing->maintenance = $modelPropertyAgreements->manteinance;
            // $PropertyListing->maintenance_included = $modelPropertyAgreements->manteinance == 0 ?  1 : 0 ;
            // $PropertyListing->save(false) ;

            $owner_email = \app\models\Users::find()->where(['id' => $model->owner_id])->one();

            $lead_id = \app\models\LeadsOwner::find()->where(['email_id' => $owner_email->login_id])->one();


            Yii::$app->getSession()->setFlash(
                'success',
                'Agreement added successfully'
            );

            return $this->redirect(['ownerssdetails', 'id' => base64_encode($lead_id->id)]);
        } else {

            Yii::$app->getSession()->setFlash(
                'success',
                'there is some error'
            );
            return $this->redirect(['ownerssdetails', 'id' => base64_encode($lead_id->id)]);
        }
    }

    public function actionSaveimage()
    {

        $model = new \app\models\PropertyImages;

        if (Yii::$app->request->isAjax) {

            $image_url = UploadedFile::getInstance($model, 'image_url');
            $image = date('ymdHis') . $image_url;
            $image_url->saveAs('uploads/' . $image);

            $model->property_id = $_POST["property_id"];
            // $model->image_url =  Url::home(true). 'uploads/'.$image ;
            $model->image_url = 'uploads/' . $image;
            $model->image_uploaded_date = date('Y-m-d H:i:s');
            $model->save(false);
            echo $model->id;
        }
    }

    public function actionEditpropertyajax($id)
    {

        if (Yii::$app->userdata->getPropertyBookedStatus($id)) {
            Yii::$app->getSession()->setFlash(
                'notice',
                'Property Can Not Be Edited'
            );
            //return "error";
        }
        $model = \app\models\Properties::findOne($id);
        $modelImages = \app\models\PropertyImages::find()->where(['property_id' => $id])->all();
        $modelPropertyAgreements = \app\models\PropertyAgreements::findOne(['property_id' => $id]);
        if (empty($modelPropertyAgreements)) {
            $modelPropertyAgreements = new \app\models\PropertyAgreements;
        }

        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            if ($model->validate()) {
                if (isset($_POST['Properties']['region'])) {
                    $model->region = $_POST['Properties']['region'];
                }

                $model->save(false);

                if ($model->status == '0') {
                    if ($model->property_type == 3) {

                        $today = date("Ymd");
                        $rand = strtoupper(substr(uniqid(sha1(time())), 0, 4));
                        $unique = $today . $rand;
                        \app\models\ChildProperties::deleteAll('main = :pid', [':pid' => $model->id]);
                        \app\models\ChildPropertiesListing::deleteAll('main = :pid', [':pid' => $model->id]);
                        $modelChild = new \app\models\ChildProperties;
                        $modelChild->property_code = $unique;
                        $modelChild->parent = $model->id;
                        $modelChild->status = '0';
                        $modelChild->sub_parent = 0;
                        $modelChild->type = 0;
                        $modelChild->main = $model->id;
                        $modelChild->save(false);

                        \app\models\PropertyListing::deleteAll(['property_id' => $model->id]);
                        $modelListing = new \app\models\PropertyListing;
                        $modelListing->property_id = $model->id;
                        $modelListing->rent = '0';
                        $modelListing->deposit = '0';
                        $modelListing->token_amount = '0';
                        $modelListing->maintenance = '0';
                        $modelListing->maintenance_included = 0;
                        $modelListing->save(false);
                    } else {


                        $today = date("Ymd");
                        $rand = strtoupper(substr(uniqid(sha1(time())), 0, 4));
                        $unique = $today . $rand;
                        \app\models\ChildProperties::deleteAll('main = :pid', [':pid' => $model->id]);
                        \app\models\ChildPropertiesListing::deleteAll('main = :pid', [':pid' => $model->id]);

                        $modelChild = new \app\models\ChildProperties;
                        $modelChild->property_code = $unique;
                        $modelChild->parent = $model->id;
                        $modelChild->status = '0';
                        $modelChild->sub_parent = 0;
                        $modelChild->type = 0;
                        $modelChild->main = $model->id;
                        $modelChild->save(false);
                        \app\models\PropertyListing::deleteAll(['property_id' => $model->id]);

                        $modelListing = new \app\models\PropertyListing;
                        $modelListing->property_id = $model->id;
                        $modelListing->rent = '0';
                        $modelListing->deposit = '0';
                        $modelListing->token_amount = '0';
                        $modelListing->maintenance = '0';
                        $modelListing->maintenance_included = 0;
                        $modelListing->save(false);

                        for ($i = 0; $i < $model['beds_room']; $i++) {

                            $rand1 = strtoupper(substr(uniqid(sha1(time())), 0, 5));
                            $unique1 = $today . $rand1;
                            $modelChildRooms = new \app\models\ChildProperties;
                            $modelChildRooms->sub_unit_name = Yii::$app->request->post('Properties')['sub_unit_name'][$i];
                            $modelChildRooms->property_code = $unique1;
                            $modelChildRooms->parent = $model->id;
                            $modelChildRooms->status = '0';
                            $modelChildRooms->sub_parent = 1;
                            $modelChildRooms->type = 1;
                            $modelChildRooms->main = $model->id;
                            $modelChildRooms->save(false);

                            $child_propertiess = new \app\models\ChildPropertiesListing;
                            $child_propertiess->child_id = $modelChildRooms->id;
                            $child_propertiess->parent_id = $modelChildRooms->id;
                            $child_propertiess->main = $model->id;
                            $child_propertiess->rent = '0';
                            $child_propertiess->deposit = '0';
                            $child_propertiess->token_amount = '0';
                            $child_propertiess->maintenance = '0';
                            $child_propertiess->availability_from = Date('Y-m-d');
                            $child_propertiess->save(false);
                            for ($j = 0; $j < $_POST['Properties']['beds'][$i]; $j++) {
                                $rand2 = strtoupper(substr(uniqid(sha1(time())), 0, 6));
                                $unique2 = $today . $rand2;
                                $modelChildBeds = new \app\models\ChildProperties;
                                $modelChildBeds->sub_unit_name = Yii::$app->request->post('Properties')['sub_unit_name'][$i] . "-" . ($j + 1);
                                $modelChildBeds->property_code = $unique2;
                                $modelChildBeds->parent = $modelChildRooms->id;
                                $modelChildBeds->status = '0';
                                $modelChildBeds->sub_parent = 0;
                                $modelChildBeds->type = 2;
                                $modelChildBeds->main = $model->id;
                                $modelChildBeds->save(false);


                                $child_propertiess = new \app\models\ChildPropertiesListing;
                                $child_propertiess->child_id = $modelChildBeds->id;
                                $child_propertiess->parent_id = $modelChildRooms->id;
                                $child_propertiess->main = $model->id;
                                $child_propertiess->rent = '0';
                                $child_propertiess->deposit = '0';
                                $child_propertiess->token_amount = '0';
                                $child_propertiess->maintenance = '0';
                                $child_propertiess->availability_from = Date('Y-m-d');
                                $child_propertiess->save(false);
                            }
                        }
                    }
                }
                $model->updated_by = Yii::$app->user->id;
                $model->updated_date = date('Y-m-d H:i:s');



                Yii::$app->getSession()->setFlash(
                    'success',
                    'Property updated successfully'
                );
                $ids = Yii::$app->userdata->getUserEmailById($model->owner_id);
                $idLead = Yii::$app->userdata->getLeadByEmailId($ids);
                return "success";
            } else {
                return $this->render('add_property', [
                    'model' => $model,
                    'modelImages' => $modelImages,
                    'modelPropertyAgreements' => $modelPropertyAgreements,
                    'redirection' => 0,
                    'edit' => '1',
                ]);
            }
        }
    }

    public function actionSaveattributes()
    {
        Yii::$app->controller->enableCsrfValidation = false;
        if (isset($_POST['property_id'])) {
            \app\models\PropertyAttributeMap::deleteAll('property_id = :pid', [':pid' => $_POST['property_id']]);

            $attribute_vals = array();
            if (trim($_POST['property_map']) != '') {
                $attr_vals = explode(",", substr($_POST['property_map'], 1));
                if (count($attr_vals) != 0) {
                    foreach ($attr_vals as $key => $value) {
                        $map_values = explode("-", $value);
                        $attribute_vals[$map_values[0]] = $map_values[1];
                    }
                }
            }


            if (isset($_POST['attributes'])) {
                $attribute = trim($_POST['attributes']);
                $attributeArray = explode(",", $attribute);
                if (count($attributeArray) != 0) {
                    foreach ($attributeArray as $vlue) {
                        if (trim($vlue) != '') {
                            $model = new \app\models\PropertyAttributeMap;
                            $model->attr_id = $vlue;
                            if (isset($attribute_vals[$vlue])) {
                                $model->value = $attribute_vals[$vlue];
                            } else {
                                $model->value = 1;
                            }

                            $model->property_id = $_POST['property_id'];
                            $model->save(false);
                        }
                    }
                }
            }
        }



        die;
    }

    public function actionOwnersdetailsajax($id)
    {
        $id = base64_decode($id);
        $proofType = \app\models\ProofType::find()->all();
        $modelComments = new \app\models\Comments();
        if (($models = LeadsOwner::findOne($id)) !== null) {
            $ids = Yii::$app->userdata->getUserIdByEmail($models->email_id);
            $address_proofs = \app\models\UserIdProofs::find()->where(['user_id' => $ids])->all();
            if ($ids) {
                $modelUser = Users::findOne($ids);
                if (($model = \app\models\OwnerProfile::find()->where(['owner_id' => $ids])->one()) != null) {
                    $bankCheck = $model->cancelled_check;
                    $profileimage = $model->profile_image;
                    if ($models->load(Yii::$app->request->post())) {
                        $models->follow_up_date_time = date('Y-m-d H:i:s', strtotime($_POST['LeadsOwner']['follow_up_date_time']));
                        $models->save(false);
                        $model->status = $models->ref_status;
                        $model->sales_id = $_POST['OwnerProfile']['sales_id'];
                        $model->save(false);
                        $return = array('success' => 1);
                        // Yii::$app->getSession()->setFlash(
                        // 'leadssuccess','Leads information is saved successfully.'
                        // );
                    }
                    if ($model->load(Yii::$app->request->post()) && $modelUser->load(Yii::$app->request->post())) {
                        $models->address = $model->address_line_1;
                        $models->address_line_2 = $model->address_line_2;
                        $models->save(false);
                        $cancelled_check = UploadedFile::getInstance($model, 'cancelled_check');
                        $profile_image = UploadedFile::getInstance($model, 'profile_image');

                        if (!empty($cancelled_check)) {
                            $name_cancelled_check = date('ymdHis') . $cancelled_check->name;
                            $cancelled_check->saveAs('uploads/proofs/' . $name_cancelled_check);
                            $model->cancelled_check = 'uploads/proofs/' . $name_cancelled_check;
                        } else {
                            $model->cancelled_check = $bankCheck;
                        }

                        if (isset($_FILES['address_proof'])) {
                            $address_proof = $_FILES['address_proof'];
                        } else {
                            $address_proof = array();
                        }

                        if (!empty($profile_image)) {
                            $name_profile_image = date('ymdHis') . $profile_image->name;
                            $profile_image->saveAs('uploads/profiles/' . $name_profile_image);
                            $model->profile_image = 'uploads/profiles/' . $name_profile_image;
                        } else {
                            $model->profile_image = $profileimage;
                        }
                        $model->operation_id = $_POST['OwnerProfile']['operation_id'];
                        $model->save(false);

                        $modelUser->save(false);

                        $models->email_id = $modelUser->login_id;
                        $models->contact_number = $model->phone;
                        $models->address = $model->address_line_1;
                        $models->address_line_2 = $model->address_line_2;
                        $models->state = $model->state;
                        $models->city = $model->city;
                        $models->region = $model->region;
                        $models->pincode = $model->pincode;
                        $models->updated_date = date('Y-m-d H:i:s');
                        $models->save(false);

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
                                        $address_proofobj->user_id = $ids;
                                        $address_proofobj->created_by = $ids;
                                        $address_proofobj->created_date = date('Y-m-d H:i:s');
                                        $address_proofobj->save(false);
                                    }
                                }
                            }
                        }
                        // Yii::$app->getSession()->setFlash('success','Your profile is saved successfully.');
                        // return $this->redirect(['ownerssdetails','id'=>base64_encode($id)]);
                        $return = array('succeess' => 1);
                    }
                } else {
                    $return = array('success' => '0', 'msg' => 'No such user exist');
                }
            } else {
                $return = array('success' => '0', 'msg' => 'No such user exist');
            }
        } else {
            $return = array('success' => '0', 'msg' => 'No such lead exists');
        }
        return json_encode($return);
    }

    public function actionGetopsbycitycode()
    {
        if (!empty($_POST['city_code'])) {
            $opModel = \app\models\OperationsProfile::findAll(['role_code' => 'OPCTMG', 'role_value' => $_POST['city_code']]);
            //echo '<option value="">Select Operation Member</option>';
            foreach ($opModel as $row) {
                echo '<option value="' . $row->operations_id . '">' . $row->email . ' [' . $row->role_code . '] [' . $row->role_value . '] </option>';
            }
        }
    }

    public function actionGetsalebycitycode()
    {
        if (!empty($_POST['city_code'])) {
            $slModel = \app\models\SalesProfile::findAll(['role_code' => 'SLCTMG', 'role_value' => $_POST['city_code']]);
            //echo '<option value="">Select Sales Member</option>';
            foreach ($slModel as $row) {
                echo '<option value="' . $row->sale_id . '">' . $row->email . ' [' . $row->role_code . '] [' . $row->role_value . '] </option>';
            }
        }
    }

    public function actionGetbranchbycitycode()
    {
        if (!empty($_POST['city_code'])) {
            $city = \app\models\Cities::findOne(['code' => $_POST['city_code']]);
            $branches = \app\models\Branches::findAll(['city_id' => $city->id]);
            echo '<option value="">Select Branch</option>';
            foreach ($branches as $branch) {
                echo '<option value="' . $branch->branch_code . '">' . $branch->name . '</option>';
            }
        }
    }

    public function actionGetopsbybranchcode()
    {
        if (!empty($_POST['branch'])) {
            $opModel = \app\models\OperationsProfile::findAll(['role_value' => $_POST['branch']]);
            echo '<option value="">Select Operation Member</option>';
            foreach ($opModel as $row) {
                echo '<option value="' . $row->operations_id . '">' . $row->email . ' [' . $row->role_code . '] [' . $row->role_value . '] </option>';
            }
        }
    }

    public function actionGetsalesbybranchcode()
    {
        if (!empty($_POST['branch'])) {
            $slModel = \app\models\SalesProfile::findAll(['role_value' => $_POST['branch']]);
            echo '<option value="">Select Sales Member</option>';
            foreach ($slModel as $row) {
                echo '<option value="' . $row->sale_id . '">' . $row->email . ' [' . $row->role_code . '] [' . $row->role_value . '] </option>';
            }
        }
    }

    public function actionAddleadassignmentproperty()
    {
        if (!empty(Yii::$app->request->post('owner_id')) && !empty(Yii::$app->request->post('property_id')) && !empty(Yii::$app->request->post('lead_assignment'))) {
            $leadPropertyModel = \app\models\LeadsProperty::find()->where(['owner_id' => Yii::$app->request->post('owner_id'), 'property_id' => Yii::$app->request->post('property_id')])->one();
            $propertyProfileModel = \app\models\Properties::find()->where(['id' => Yii::$app->request->post('property_id')])->one();
            $userModel = \app\models\Users::find()->where(['id' => Yii::$app->request->post('owner_id')])->one();

            if ($propertyProfileModel->status == 1) {
                //Yii::$app->getSession()->setFlash('error', 'Lead already assigned to "' . $propertyProfileModel->branch_code . '" branch');
                //return $this->redirect(['addproperty', 'id' => Yii::$app->request->post('redirect')]);
            }

            if ($leadPropertyModel && $propertyProfileModel && $userModel) {
                if ($_POST['assigned_level'] == 1) {
                    $propertyProfileModel->branch_code = null;
                    $propertyProfileModel->operations_id = Yii::$app->request->post('assigned_operation');
                    $propertyProfileModel->sales_id = Yii::$app->request->post('assigned_sale');
                    $propertyProfileModel->save(false);

                    $leadPropertyModel->branch_code = null;
                    $leadPropertyModel->save(false);
                } else if ($_POST['assigned_level'] == 2) {
                    $propertyProfileModel->branch_code = Yii::$app->request->post('assigned_branch');
                    $propertyProfileModel->operations_id = Yii::$app->request->post('assigned_operation');
                    $propertyProfileModel->sales_id = Yii::$app->request->post('assigned_sale');
                    $propertyProfileModel->save(false);

                    $leadPropertyModel->branch_code = Yii::$app->request->post('assigned_branch');
                    $leadPropertyModel->save(false);
                } else if ($_POST['assigned_level'] == 3) {
                    $propertyProfileModel->branch_code = 'ELHQ';
                    $opModel = \app\models\OperationsProfile::findOne(['role_value' => 'ELHQ']);
                    $propertyProfileModel->operations_id = $opModel->operations_id;

                    $slModel = \app\models\SalesProfile::findOne(['role_value' => 'ELHQ']);
                    if ($slModel) {
                        $propertyProfileModel->sales_id = $slModel->sale_id;
                    } else {
                        $propertyProfileModel->sales_id = null;
                    }

                    $propertyProfileModel->save(false);

                    $leadPropertyModel->branch_code = 'ELHQ';
                    $leadPropertyModel->lead_city = '';
                    $leadPropertyModel->lead_state = '';
                    $leadPropertyModel->save(false);

                    Yii::$app->getSession()->setFlash(
                        'leadssuccess',
                        'Property assignment is saved successfully.'
                    );

                    return $this->redirect(['external/propertyleads']);
                }

                //Yii::$app->getSession()->setFlash(
                //        'leadssuccess', 'Property assignment is saved successfully.'
                //);

                echo 1;

                //return $this->redirect(['addproperty', 'id' => Yii::$app->request->post('redirect')]);
            } else {
                echo 0;
                //$this->redirect(['external/propertyleads']);
            }
        } else {
            echo 0;
            //$this->redirect(['external/propertyleads']);
        }

        exit;
    }

    public function actionEditleadassignmentproperty()
    {
        if (!empty(Yii::$app->request->post('owner_id')) && !empty(Yii::$app->request->post('property_id')) && !empty(Yii::$app->request->post('lead_assignment'))) {
            $leadPropertyModel = \app\models\LeadsProperty::find()->where(['owner_id' => Yii::$app->request->post('owner_id'), 'property_id' => Yii::$app->request->post('property_id')])->one();
            $propertyProfileModel = \app\models\Properties::find()->where(['id' => Yii::$app->request->post('property_id')])->one();
            $userModel = \app\models\Users::find()->where(['id' => Yii::$app->request->post('owner_id')])->one();

            if ($propertyProfileModel->status == 1) {
                Yii::$app->getSession()->setFlash(
                    'error',
                    'Lead already assigned to "' . $propertyProfileModel->branch_code . '" branch'
                );

                return $this->redirect(['editproperty', 'id' => Yii::$app->request->post('redirect')]);
            }

            if ($leadPropertyModel && $propertyProfileModel && $userModel) {
                if ($_POST['assigned_level'] == 1) {
                    $propertyProfileModel->branch_code = null;
                    $propertyProfileModel->operations_id = Yii::$app->request->post('assigned_operation');
                    $propertyProfileModel->sales_id = Yii::$app->request->post('assigned_sale');
                    $propertyProfileModel->save(false);

                    $leadPropertyModel->branch_code = null;
                    $leadPropertyModel->save(false);
                } else if ($_POST['assigned_level'] == 2) {
                    $propertyProfileModel->branch_code = Yii::$app->request->post('assigned_branch');
                    $propertyProfileModel->operations_id = Yii::$app->request->post('assigned_operation');
                    $propertyProfileModel->sales_id = Yii::$app->request->post('assigned_sale');
                    $propertyProfileModel->save(false);

                    $leadPropertyModel->branch_code = Yii::$app->request->post('assigned_branch');
                    $leadPropertyModel->save(false);
                } else if ($_POST['assigned_level'] == 3) {
                    $propertyProfileModel->branch_code = 'ELHQ';
                    $opModel = \app\models\OperationsProfile::findOne(['role_value' => 'ELHQ']);
                    $propertyProfileModel->operations_id = $opModel->operations_id;

                    $slModel = \app\models\SalesProfile::findOne(['role_value' => 'ELHQ']);
                    if ($slModel) {
                        $propertyProfileModel->sales_id = $slModel->sale_id;
                    } else {
                        $propertyProfileModel->sales_id = null;
                    }

                    $propertyProfileModel->save(false);

                    $leadPropertyModel->branch_code = 'ELHQ';
                    $leadPropertyModel->lead_city = '';
                    $leadPropertyModel->lead_state = '';
                    $leadPropertyModel->save(false);

                    //Yii::$app->getSession()->setFlash('leadssuccess', 'Leads assignment is saved successfully.');

                    return $this->redirect(['external/propertyleads']);
                }

                //Yii::$app->getSession()->setFlash('leadssuccess', 'Leads assignment is saved successfully.');

                return $this->redirect(['editproperty', 'id' => Yii::$app->request->post('redirect'), 't' => 4]);
            } else {
                $this->redirect(['external/propertyleads']);
            }
        } else {
            $this->redirect(['external/propertyleads']);
        }

        exit;
    }

    public function actionLeadassignmentadvisor()
    {
        if (!empty(Yii::$app->request->post('advisor_id')) && !empty(Yii::$app->request->post('lead_assignment'))) {
            $leadAdvisorModel = \app\models\LeadsAdvisor::find()->where(['email_id' => Yii::$app->userdata->getEmailById(Yii::$app->request->post('advisor_id'))])->one();
            $advisorProfileModel = \app\models\AdvisorProfile::find()->where(['advisor_id' => Yii::$app->request->post('advisor_id')])->one();
            $userModel = \app\models\Users::find()->where(['id' => Yii::$app->request->post('advisor_id')])->one();

            if ($advisorProfileModel->status == 1) {
                Yii::$app->getSession()->setFlash(
                    'error',
                    'Lead already assigned to "' . $advisorProfileModel->branch_code . '" branch'
                );

                return $this->redirect(['advisersdetails', 'id' => Yii::$app->request->post('redirect')]);
            }

            if ($leadAdvisorModel && $advisorProfileModel && $userModel) {
                if ($_POST['assigned_level'] == 1) {
                    $advisorProfileModel->branch_code = null;
                    $advisorProfileModel->operation_id = Yii::$app->request->post('assigned_operation');
                    $advisorProfileModel->sales_id = Yii::$app->request->post('assigned_sale');
                    $advisorProfileModel->save(false);

                    $leadAdvisorModel->branch_code = null;
                    $leadAdvisorModel->save(false);
                } else if ($_POST['assigned_level'] == 2) {
                    $advisorProfileModel->branch_code = Yii::$app->request->post('assigned_branch');
                    $advisorProfileModel->operation_id = Yii::$app->request->post('assigned_operation');
                    $advisorProfileModel->sales_id = Yii::$app->request->post('assigned_sale');
                    $advisorProfileModel->save(false);

                    $leadAdvisorModel->branch_code = Yii::$app->request->post('assigned_branch');
                    $leadAdvisorModel->save(false);
                } else if ($_POST['assigned_level'] == 3) {
                    $advisorProfileModel->branch_code = 'ELHQ';
                    $opModel = \app\models\OperationsProfile::findOne(['role_value' => 'ELHQ']);
                    $advisorProfileModel->operation_id = $opModel->operations_id;

                    $slModel = \app\models\SalesProfile::findOne(['role_value' => 'ELHQ']);
                    if ($slModel) {
                        $advisorProfileModel->sales_id = $slModel->sale_id;
                    } else {
                        $advisorProfileModel->sales_id = null;
                    }

                    $advisorProfileModel->save(false);

                    $leadAdvisorModel->branch_code = 'ELHQ';
                    $leadAdvisorModel->lead_city = '';
                    $leadAdvisorModel->lead_state = '';
                    $leadAdvisorModel->save(false);

                    Yii::$app->getSession()->setFlash(
                        'leadssuccess',
                        'Leads assignment is saved successfully.'
                    );

                    return $this->redirect(['external/advisors']);
                }

                Yii::$app->getSession()->setFlash(
                    'leadssuccess',
                    'Leads assignment is saved successfully.'
                );

                return $this->redirect(['advisersdetails', 'id' => Yii::$app->request->post('redirect')]);
            } else {
                $this->redirect(['external/advisors']);
            }
        } else {
            $this->redirect(['external/advisors']);
        }

        exit;
    }

    public function actionLeadassignmentapplicant()
    {
        if (!empty(Yii::$app->request->post('applicant_id')) && !empty(Yii::$app->request->post('lead_assignment'))) {
            $leadTenantModel = \app\models\LeadsTenant::find()->where(['email_id' => Yii::$app->userdata->getEmailById(Yii::$app->request->post('applicant_id'))])->one();
            $applicantProfileModel = \app\models\ApplicantProfile::find()->where(['applicant_id' => Yii::$app->request->post('applicant_id')])->one();
            $userModel = \app\models\Users::find()->where(['id' => Yii::$app->request->post('applicant_id')])->one();

            if ($applicantProfileModel->status == 1) {
                Yii::$app->getSession()->setFlash(
                    'error',
                    'Lead already assigned to "' . $applicantProfileModel->branch_code . '" branch'
                );

                return $this->redirect(['applicantsdetails', 'id' => Yii::$app->request->post('redirect')]);
            }

            if ($leadTenantModel && $applicantProfileModel && $userModel) {
                if ($_POST['assigned_level'] == 1) {
                    $applicantProfileModel->branch_code = null;
                    $applicantProfileModel->operation_id = Yii::$app->request->post('assigned_operation');
                    $applicantProfileModel->sales_id = Yii::$app->request->post('assigned_sale');
                    $applicantProfileModel->save(false);

                    $leadTenantModel->branch_code = null;
                    $leadTenantModel->save(false);
                } else if ($_POST['assigned_level'] == 2) {
                    $applicantProfileModel->branch_code = Yii::$app->request->post('assigned_branch');
                    $applicantProfileModel->operation_id = Yii::$app->request->post('assigned_operation');
                    $applicantProfileModel->sales_id = Yii::$app->request->post('assigned_sale');
                    $applicantProfileModel->save(false);

                    $leadTenantModel->branch_code = Yii::$app->request->post('assigned_branch');
                    $leadTenantModel->save(false);
                } else if ($_POST['assigned_level'] == 3) {
                    $applicantProfileModel->branch_code = 'ELHQ';
                    $opModel = \app\models\OperationsProfile::findOne(['role_value' => 'ELHQ']);
                    $applicantProfileModel->operation_id = $opModel->operations_id;

                    $slModel = \app\models\SalesProfile::findOne(['role_value' => 'ELHQ']);
                    if ($slModel) {
                        $applicantProfileModel->sales_id = $slModel->sale_id;
                    } else {
                        $applicantProfileModel->sales_id = null;
                    }

                    $applicantProfileModel->save(false);

                    $leadTenantModel->branch_code = 'ELHQ';
                    $leadTenantModel->lead_city = '';
                    $leadTenantModel->lead_state = '';
                    $leadTenantModel->save(false);

                    Yii::$app->getSession()->setFlash(
                        'leadssuccess',
                        'Leads assignment is saved successfully.'
                    );

                    return $this->redirect(['external/applicants']);
                }

                Yii::$app->getSession()->setFlash(
                    'leadssuccess',
                    'Leads assignment is saved successfully.'
                );

                return $this->redirect(['applicantsdetails', 'id' => Yii::$app->request->post('redirect')]);
            } else {
                $this->redirect(['external/applicants']);
            }
        } else {
            $this->redirect(['external/applicants']);
        }

        exit;
    }

    public function actionLeadassignmentowner()
    {
        if (!empty(Yii::$app->request->post('owner_id')) && !empty(Yii::$app->request->post('lead_assignment'))) {
            $leadOwnerModel = \app\models\LeadsOwner::find()->where(['email_id' => Yii::$app->userdata->getEmailById(Yii::$app->request->post('owner_id'))])->one();
            $ownerProfileModel = \app\models\OwnerProfile::find()->where(['owner_id' => Yii::$app->request->post('owner_id')])->one();
            $userModel = \app\models\Users::find()->where(['id' => Yii::$app->request->post('owner_id')])->one();

            if ($ownerProfileModel->status == 1) {
                Yii::$app->getSession()->setFlash(
                    'error',
                    'Lead already assigned to "' . $ownerProfileModel->branch_code . '" branch'
                );

                return $this->redirect(['ownerssdetails', 'id' => Yii::$app->request->post('redirect')]);
            }

            if ($leadOwnerModel && $ownerProfileModel && $userModel) {
                if ($_POST['assigned_level'] == 1) {
                    $ownerProfileModel->branch_code = null;
                    $ownerProfileModel->operation_id = Yii::$app->request->post('assigned_operation');
                    $ownerProfileModel->sales_id = Yii::$app->request->post('assigned_sale');
                    $ownerProfileModel->save(false);

                    $leadOwnerModel->branch_code = null;
                    $leadOwnerModel->save(false);
                } else if ($_POST['assigned_level'] == 2) {
                    $ownerProfileModel->branch_code = Yii::$app->request->post('assigned_branch');
                    $ownerProfileModel->operation_id = Yii::$app->request->post('assigned_operation');
                    $ownerProfileModel->sales_id = Yii::$app->request->post('assigned_sale');
                    $ownerProfileModel->save(false);

                    $leadOwnerModel->branch_code = Yii::$app->request->post('assigned_branch');
                    $leadOwnerModel->save(false);
                } else if ($_POST['assigned_level'] == 3) {
                    $ownerProfileModel->branch_code = 'ELHQ';
                    $opModel = \app\models\OperationsProfile::findOne(['role_value' => 'ELHQ']);
                    $ownerProfileModel->operation_id = $opModel->operations_id;

                    $slModel = \app\models\SalesProfile::findOne(['role_value' => 'ELHQ']);
                    if ($slModel) {
                        $ownerProfileModel->sales_id = $slModel->sale_id;
                    } else {
                        $ownerProfileModel->sales_id = null;
                    }

                    $ownerProfileModel->save(false);

                    $leadOwnerModel->branch_code = 'ELHQ';
                    $leadOwnerModel->lead_city = '';
                    $leadOwnerModel->lead_state = '';
                    $leadOwnerModel->save(false);

                    Yii::$app->getSession()->setFlash(
                        'leadssuccess',
                        'Leads assignment is saved successfully.'
                    );

                    return $this->redirect(['external/owners']);
                }

                Yii::$app->getSession()->setFlash(
                    'leadssuccess',
                    'Leads assignment is saved successfully.'
                );

                return $this->redirect(['ownerssdetails', 'id' => Yii::$app->request->post('redirect')]);
            } else {
                $this->redirect(['external/owners']);
            }
        } else {
            $this->redirect(['external/owners']);
        }

        exit;
    }

    public function actionOwnerssdetails($id)
    {
        $id = base64_decode($id);
        $id1 = $id;
        //print_r($id1); exit;
        $proofType = \app\models\ProofType::find()->all();
        $modelComments = new \app\models\Comments();
        if (($models = LeadsOwner::findOne($id)) !== null) {
            $ids = Yii::$app->userdata->getUserIdByEmail($models->email_id);
            // echo($ids); exit();
            $address_proofs = \app\models\UserIdProofs::find()->where(['user_id' => $ids])->all();
            if ($ids) {
                $comments = \app\models\Comments::find()->where(['user_id' => $ids])->orderBy(['id' => SORT_DESC])->all();
                $properties = \app\models\Properties::find()->where(['owner_id' => $ids])->joinWith('propertyListing')->all();

                if (($model = \app\models\OwnerProfile::find()->where(['owner_id' => $ids])->one()) != null) {
                    $bankCheck = $model->cancelled_check;
                    $profileimage = $model->profile_image;

                    $modelUser = Users::findOne($ids);
                    $modelUser->full_name = $models->full_name;
                    $modelUser->save(false);
                    $models->save(false);
                    //print_r($models); exit;

                    if ($models->load(Yii::$app->request->post())) {
                        if (isset($_POST['LeadsOwner']['follow_up_date_time'])) {
                            $models->follow_up_date_time = date('Y-m-d H:i:s', strtotime($_POST['LeadsOwner']['follow_up_date_time']));
                        }
                        //print_r($_POST); exit;

                        $models->save(false);

                        if ($model->load(Yii::$app->request->post())) {
                            $model->status = $models->ref_status;
                            $model->save(false);
                        }


                        Yii::$app->getSession()->setFlash(
                            'leadssuccess',
                            'Leads information is saved successfully.'
                        );

                        //return $this->render('owner_details', ['model' => $model, 'modelUser' => $modelUser, 'modelLeads' => $models, 'modelComments' => $modelComments, 'comments' => $comments, 'properties' => $properties, 'proofType' => $proofType, 'address_proofs' => $address_proofs, 'id1' => $id1]);
                    }
                    if ($model->load(Yii::$app->request->post()) && $modelUser->load(Yii::$app->request->post())) {
                        $model->phone = Yii::$app->userdata->trimPhone($model->phone);
                        $modelUser->full_name = $models->full_name;
                        $modelUser->phone = $model->phone;
                        $models->email_id = $modelUser->login_id;
                        $modelUser->username = $modelUser->login_id;

                        if ($model->validate() && $modelUser->validate(['login_id', 'phone', 'full_name'])) {

                            $models->address = $model->address_line_1;
                            $models->address_line_2 = $model->address_line_2;
                            $models->email_id = $modelUser->login_id;
                            $models->contact_number = $model->phone;
                            $models->region = $model->region;
                            $models->pincode = $model->pincode;
                            $models->updated_date = date('Y-m-d H:i:s');
                            $models->save(false);

                            $cancelled_check = UploadedFile::getInstance($model, 'cancelled_check');
                            $profile_image = UploadedFile::getInstance($model, 'profile_image');

                            if (!empty($cancelled_check)) {
                                $name_cancelled_check = date('ymdHis') . $cancelled_check->name;
                                $cancelled_check->saveAs('uploads/proofs/' . $name_cancelled_check);
                                $model->cancelled_check = 'uploads/proofs/' . $name_cancelled_check;
                            } else {
                                $model->cancelled_check = $bankCheck;
                            }

                            if (isset($_FILES['address_proof'])) {
                                $address_proof = $_FILES['address_proof'];
                            } else {
                                $address_proof = array();
                            }

                            if (!empty($profile_image)) {
                                $name_profile_image = date('ymdHis') . $profile_image->name;
                                $profile_image->saveAs('uploads/profiles/' . $name_profile_image);
                                $model->profile_image = 'uploads/profiles/' . $name_profile_image;
                            } else {
                                $model->profile_image = $profileimage;
                            }
                            //$model->operation_id = $_POST['OwnerProfile']['operation_id'];
                            //$model->sales_id = $_POST['OwnerProfile']['sales_id'];
                            $model->save(false);
                            $modelUser->save(false);



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
                                            $address_proofobj->user_id = $ids;
                                            $address_proofobj->created_by = $ids;
                                            $address_proofobj->created_date = date('Y-m-d H:i:s');
                                            $address_proofobj->save(false);
                                        }
                                    }
                                }
                            }
                            Yii::$app->getSession()->setFlash('success', 'Property Owner Information saved successfully.');
                            return $this->redirect(['ownerssdetails', 'id' => base64_encode($id)]);
                        } else {
                            return $this->render('owner_details', [
                                'model' => $model, 'modelUser' => $modelUser, 'modelLeads' => $models, 'modelComments' => $modelComments,
                                'comments' => $comments, 'properties' => $properties, 'proofType' => $proofType, 'address_proofs' => $address_proofs, 'id1' => $id1
                            ]);
                        }
                    } else {
                        return $this->render('owner_details', [
                            'model' => $model, 'modelUser' => $modelUser, 'modelLeads' => $models, 'modelComments' => $modelComments,
                            'comments' => $comments, 'properties' => $properties, 'proofType' => $proofType, 'address_proofs' => $address_proofs, 'id1' => $id1
                        ]);
                    }
                } else {
                    throw new NotFoundHttpException('The requested page does not exist.');
                }
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionAddproperty($id)
    {
        $id = base64_decode($id);
        $model = new \app\models\Properties;
        $modelImages = new \app\models\PropertyImages;
        $modelPropertyAgreements = new \app\models\PropertyAgreements;
        $addProperty = true;
        $proGenModel = new \app\models\PropertyGender();
        $manByModel = new \app\models\ManagedBy();

        return $this->render('add_property', [
            'model' => $model,
            'proGenModel' => $proGenModel,
            'manByModel' => $manByModel,
            'addProperty' => $addProperty,
            'manByModel' => $manByModel,
            'modelImages' => $modelImages,
            'modelPropertyAgreements' => $modelPropertyAgreements,
        ]);
        // }
    }

    public function actionAddpropertylisting($id)
    {
        $id = base64_decode($id);
        //print_r(); 
        $model = new \app\models\Properties;
        $leadsModel = new \app\models\LeadsProperty;
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            $model->created_by = Yii::$app->user->id;
            $model->created_date = date('Y-m-d H:i:s');
            $model->owner_id = $id;
            $model->owner_lead_id = Yii::$app->userdata->getLeadByEmailId(Yii::$app->userdata->getUserEmailById($id));
            if ($model->property_type == 3) {
                $model->is_room = 0;
            }

            if ($model->validate()) {

                if (isset($_POST['property_id'])) {

                    $property_get = \app\models\Properties::findOne(['id' => $_POST['property_id']]);


                    $connection = Yii::$app->getDb();
                    $command = $connection->createCommand('UPDATE `properties` SET `property_name`="' . $model->property_name . '",`property_type`="' . $model->property_type . '",`flat_type`="0",`property_description`="' . $model->property_description . '",`address_line_1`="' . $model->address_line_1 . '",`lat`="' . $model->lat . '",`lon`="' . $model->lon . '",`city`="' . $model->city . '",`state`="' . $model->state . '",`branch_code`="' . $_POST['Properties']['branch_code'] . '",`pincode`="' . $model->pincode . '",`flat_bhk`="' . $model->flat_bhk . '",`flat_area`="' . $model->flat_area . '",`address_line_2`="' . $model->address_line_2 . '" WHERE id="' . $_POST['property_id'] . '"');
                    $command->query();

                    if ($model->property_type == 3) {
                        if ($model->status == '0') {
                            $today = date("Ymd");
                            $rand = strtoupper(substr(uniqid(sha1(time())), 0, 4));
                            $unique = $today . $rand;
                            \app\models\ChildProperties::deleteAll('main = :pid', [':pid' => $_POST['property_id']]);
                            \app\models\PropertyListing::deleteAll(['property_id' => $_POST['property_id']]);
                            $modelChild = new \app\models\ChildProperties;
                            $modelChild->property_code = $unique;
                            $modelChild->parent = $_POST['property_id'];
                            $modelChild->status = '0';
                            $modelChild->sub_parent = 0;
                            $modelChild->type = 0;
                            $modelChild->main = $_POST['property_id'];
                            $modelChild->save(false);

                            $modelListing = new \app\models\PropertyListing;
                            $modelListing->property_id = $_POST['property_id'];
                            $modelListing->rent = '0';
                            $modelListing->deposit = '0';
                            $modelListing->token_amount = '0';
                            $modelListing->maintenance = '0';
                            $modelListing->maintenance_included = 0;
                            $modelListing->availability_from = Date('Y-m-d');
                            $modelListing->save(false);
                        }
                    } else {
                        if ($model->status == '0') {
                            $today = date("Ymd");
                            $rand = strtoupper(substr(uniqid(sha1(time())), 0, 4));
                            $unique = $today . $rand;
                            $child = \app\models\ChildProperties::deleteAll('main = :pid', [':pid' => $_POST['property_id']]);


                            $modelChildRooms = new \app\models\ChildProperties;
                            $modelChildRooms->property_code = $unique;
                            $modelChildRooms->parent = $_POST['property_id'];
                            $modelChildRooms->status = '0';
                            $modelChildRooms->sub_parent = 0;
                            $modelChildRooms->type = 0;
                            $modelChildRooms->main = $_POST['property_id'];
                            $modelChildRooms->save(false);

                            \app\models\PropertyListing::deleteAll(['property_id' => $_POST['property_id']]);
                            \app\models\ChildPropertiesListing::deleteAll(['main' => $_POST['property_id']]);
                            $modelListing = new \app\models\PropertyListing;
                            $modelListing->property_id = $_POST['property_id'];
                            $modelListing->rent = '0';
                            $modelListing->deposit = '0';
                            $modelListing->token_amount = '0';
                            $modelListing->maintenance = '0';
                            $modelListing->maintenance_included = 0;
                            $modelListing->availability_from = Date('Y-m-d');
                            $modelListing->save(false);

                            for ($i = 0; $i < $model['beds_room']; $i++) {

                                $rand1 = strtoupper(substr(uniqid(sha1(time())), 0, 5));
                                $unique1 = $today . $rand1;
                                $modelChildRooms = new \app\models\ChildProperties;
                                $modelChildRooms->sub_unit_name = Yii::$app->request->post('Properties')['sub_unit_name'][$i];
                                $modelChildRooms->property_code = $unique1;
                                $modelChildRooms->parent = $_POST['property_id'];
                                $modelChildRooms->status = '0';
                                $modelChildRooms->sub_parent = 1;
                                $modelChildRooms->type = 1;
                                $modelChildRooms->main = $_POST['property_id'];
                                $modelChildRooms->save(false);

                                $child_propertiess = new \app\models\ChildPropertiesListing;
                                $child_propertiess->child_id = $modelChildRooms->id;
                                $child_propertiess->parent_id = $modelChildRooms->id;
                                $child_propertiess->main = $_POST['property_id'];
                                $child_propertiess->rent = '0';
                                $child_propertiess->deposit = '0';
                                $child_propertiess->token_amount = '0';
                                $child_propertiess->maintenance = '0';
                                $child_propertiess->availability_from = Date('Y-m-d');
                                $child_propertiess->save(false);

                                for ($j = 0; $j < $_POST['Properties']['beds'][$i]; $j++) {
                                    $rand2 = strtoupper(substr(uniqid(sha1(time())), 0, 6));
                                    $unique2 = $today . $rand2;
                                    $modelChildBeds = new \app\models\ChildProperties;
                                    $modelChildBeds->sub_unit_name = Yii::$app->request->post('Properties')['sub_unit_name'][$i] . "-" . ($j + 1);
                                    $modelChildBeds->property_code = $unique2;
                                    $modelChildBeds->parent = $modelChildRooms->id;
                                    $modelChildBeds->status = '0';
                                    $modelChildBeds->sub_parent = 0;
                                    $modelChildBeds->type = 2;
                                    $modelChildBeds->main = $_POST['property_id'];
                                    $modelChildBeds->save(false);

                                    $child_propertiess = new \app\models\ChildPropertiesListing;
                                    $child_propertiess->child_id = $modelChildBeds->id;
                                    $child_propertiess->parent_id = $modelChildRooms->id;
                                    $child_propertiess->main = $_POST['property_id'];
                                    $child_propertiess->rent = '0';
                                    $child_propertiess->deposit = '0';
                                    $child_propertiess->token_amount = '0';
                                    $child_propertiess->maintenance = '0';
                                    $child_propertiess->availability_from = Date('Y-m-d');
                                    $child_propertiess->save(false);
                                }
                            }
                        }
                    }
                    return $_POST['property_id'];
                } else {

                    $model->save(false);
                    $propertyId = Yii::$app->db->getLastInsertID();

                    $leadsModel->property_id = $propertyId;
                    $leadsModel->property_name = $model->property_name;
                    $leadsModel->owner_id = $model->owner_id;
                    $leadsModel->address = $model->address_line_1;
                    $leadsModel->address2 = $model->address_line_2;
                    $leadsModel->lead_city = $model->city;
                    $leadsModel->lead_state = $model->state;
                    $leadsModel->pincode = $model->pincode;
                    $leadsModel->branch_code = $model->branch_code;
                    $leadsModel->created_by = Yii::$app->user->id;
                    $leadsModel->updated_by = Yii::$app->user->id;
                    $leadsModel->created_date = date('Y-m-d H:i:s');
                    $leadsModel->updated_date = date('Y-m-d H:i:s');
                    $leadsModel->save(false);

                    if ($model->property_type == 3) {

                        $today = date("Ymd");
                        $rand = strtoupper(substr(uniqid(sha1(time())), 0, 4));
                        $unique = $today . $rand;
                        \app\models\ChildProperties::deleteAll('main = :pid', [':pid' => $model->id]);

                        $modelChild = new \app\models\ChildProperties;
                        $modelChild->property_code = $unique;
                        $modelChild->parent = $model->id;
                        $modelChild->status = '0';
                        $modelChild->sub_parent = 0;
                        $modelChild->type = 0;
                        $modelChild->main = $model->id;
                        $modelChild->save(false);

                        $modelListing = new \app\models\PropertyListing;
                        $modelListing->property_id = $model->id;
                        $modelListing->rent = '0';
                        $modelListing->deposit = '0';
                        $modelListing->token_amount = '0';
                        $modelListing->maintenance = '0';
                        $modelListing->maintenance_included = 0;
                        $modelListing->availability_from = Date('Y-m-d');
                        $modelListing->save(false);
                    } else {


                        $today = date("Ymd");
                        $rand = strtoupper(substr(uniqid(sha1(time())), 0, 4));
                        $unique = $today . $rand;
                        \app\models\ChildProperties::deleteAll('main = :pid', [':pid' => $model->id]);

                        $modelChild = new \app\models\ChildProperties;
                        $modelChild->property_code = $unique;
                        $modelChild->parent = $model->id;
                        $modelChild->status = '0';
                        $modelChild->sub_parent = 0;
                        $modelChild->type = 0;
                        $modelChild->main = $model->id;
                        $modelChild->save(false);

                        $modelListing = new \app\models\PropertyListing;
                        $modelListing->property_id = $model->id;
                        $modelListing->rent = '0';
                        $modelListing->deposit = '0';
                        $modelListing->token_amount = '0';
                        $modelListing->maintenance = '0';
                        $modelListing->availability_from = Date('Y-m-d');
                        $modelListing->maintenance_included = 0;

                        $modelListing->save(false);
                        for ($i = 0; $i < $model['beds_room']; $i++) {

                            $rand1 = strtoupper(substr(uniqid(sha1(time())), 0, 5));
                            $unique1 = $today . $rand1;
                            $modelChildRooms = new \app\models\ChildProperties;
                            $modelChildRooms->sub_unit_name = Yii::$app->request->post('Properties')['sub_unit_name'][$i];
                            $modelChildRooms->property_code = $unique1;
                            $modelChildRooms->parent = $model->id;
                            $modelChildRooms->status = '0';
                            $modelChildRooms->sub_parent = 1;
                            $modelChildRooms->type = 1;
                            $modelChildRooms->main = $model->id;
                            $modelChildRooms->save(false);

                            $child_propertiess = new \app\models\ChildPropertiesListing;
                            $child_propertiess->child_id = $modelChildRooms->id;
                            $child_propertiess->parent_id = $modelChildRooms->id;
                            $child_propertiess->main = $model->id;
                            $child_propertiess->rent = '0';
                            $child_propertiess->deposit = '0';
                            $child_propertiess->token_amount = '0';
                            $child_propertiess->maintenance = '0';
                            $child_propertiess->availability_from = Date('Y-m-d');
                            $child_propertiess->save(false);

                            for ($j = 0; $j < $_POST['Properties']['beds'][$i]; $j++) {

                                $rand2 = strtoupper(substr(uniqid(sha1(time())), 0, 6));
                                $unique2 = $today . $rand2;
                                $modelChildBeds = new \app\models\ChildProperties;
                                $modelChildBeds->sub_unit_name = Yii::$app->request->post('Properties')['sub_unit_name'][$i] . "-" . ($j + 1);
                                $modelChildBeds->property_code = $unique2;
                                $modelChildBeds->parent = $modelChildRooms->id;
                                $modelChildBeds->status = '0';
                                $modelChildBeds->sub_parent = 0;
                                $modelChildBeds->type = 2;
                                $modelChildBeds->main = $model->id;
                                $modelChildBeds->save(false);

                                $child_propertiess = new \app\models\ChildPropertiesListing;
                                $child_propertiess->child_id = $modelChildBeds->id;
                                $child_propertiess->parent_id = $modelChildRooms->id;
                                $child_propertiess->main = $model->id;
                                $child_propertiess->rent = '0';
                                $child_propertiess->deposit = '0';
                                $child_propertiess->token_amount = '0';
                                $child_propertiess->maintenance = '0';
                                $child_propertiess->availability_from = Date('Y-m-d');
                                $child_propertiess->save(false);
                            }
                        }
                    }


                    return $model->id;
                }
            } else {
                //print_r($model->errors); exit;
                return 'error';
            }
        }
    }

    public function actionEditproperty($id, $redirection = null)
    {

        $model = \app\models\Properties::findOne($id);

        $proGenModel = new \app\models\PropertyGender();
        $manByModel = new \app\models\ManagedBy();

        Yii::$app->assetManager->bundles['yii\web\JqueryAsset'] = false;
        Yii::$app->assetManager->bundles['yii\web\YiiAsset'] = false;
        $modelImages = \app\models\PropertyImages::find()->where(['property_id' => $id])->all();
        $modelPropertyAgreements = \app\models\PropertyAgreements::findOne(['property_id' => $id]);
        if (empty($modelPropertyAgreements)) {
            $modelPropertyAgreements = new \app\models\PropertyAgreements;
        }
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = 'json';
            return \yii\bootstrap\ActiveForm::validate($model);
        } else if ($model->load(Yii::$app->request->post())) {
            try {
                if ($model->property_type == 3) {
                    $today = date("Ymd");
                    $PropertyAgreements[furniture_rent] = 0;
                    $rand = strtoupper(substr(uniqid(sha1(time())), 0, 4));
                    $unique = $today . $rand;
                    \app\models\ChildProperties::deleteAll('main = :pid', [':pid' => $model->id]);

                    $modelChild = new \app\models\ChildProperties;
                    $modelChild->property_code = $unique;
                    $modelChild->parent = $model->id;
                    $modelChild->status = 0;
                    $modelChild->sub_parent = 0;
                    $modelChild->type = 0;
                    $modelChild->main = $model->id;
                    $modelChild->save(false);

                    $modelListing = new \app\models\PropertyListing;
                    $modelListing->property_id = $model->id;
                    $modelListing->rent = '0';
                    $modelListing->deposit = '0';
                    $modelListing->token_amount = '0';
                    $modelListing->maintenance = '0';
                    $modelListing->maintenance_included = 0;
                    $modelListing->save(false);
                } else {
                    $today = date("Ymd");
                    $rand = strtoupper(substr(uniqid(sha1(time())), 0, 4));
                    $unique = $today . $rand;
                    \app\models\ChildProperties::deleteAll('main = :pid', [':pid' => $model->id]);

                    $modelChild = new \app\models\ChildProperties;
                    $modelChild->property_code = $unique;
                    $modelChild->parent = $model->id;
                    $modelChild->status = 0;
                    $modelChild->sub_parent = 0;
                    $modelChild->type = 0;
                    $modelChild->main = $model->id;
                    $modelChild->save(false);


                    $modelListing = new \app\models\PropertyListing;
                    $modelListing->property_id = $model->id;
                    $modelListing->rent = '0';
                    $modelListing->deposit = '0';
                    $modelListing->token_amount = '0';
                    $modelListing->maintenance = '0';
                    $modelListing->maintenance_included = 0;
                    $modelListing->save(false);

                    for ($i = 0; $i < count($model['beds']); $i++) {

                        $rand1 = strtoupper(substr(uniqid(sha1(time())), 0, 5));
                        $unique1 = $today . $rand1;
                        $modelChildRooms = new \app\models\ChildProperties;
                        $modelChildRooms->property_code = $unique1;
                        $modelChildRooms->parent = $model->id;
                        $modelChildRooms->status = 0;
                        $modelChildRooms->sub_parent = 1;
                        $modelChildRooms->type = 1;
                        $modelChildRooms->main = $model->id;
                        $modelChildRooms->save(false);

                        $child_propertiess = new \app\models\ChildPropertiesListing;
                        $child_propertiess->child_id = $modelChildRooms->id;
                        $child_propertiess->parent_id = $modelChildRooms->id;
                        $child_propertiess->main = $model->id;
                        $child_propertiess->rent = '0';
                        $child_propertiess->deposit = '0';
                        $child_propertiess->token_amount = '0';
                        $child_propertiess->maintenance = '0';
                        $child_propertiess->availability_from = Date('Y-m-d');
                        $child_propertiess->save(false);

                        for ($j = 0; $j < $model['beds'][$i]; $j++) {
                            $rand2 = strtoupper(substr(uniqid(sha1(time())), 0, 6));
                            $unique2 = $today . $rand2;
                            $modelChildBeds = new \app\models\ChildProperties;
                            $modelChildBeds->property_code = $unique2;
                            $modelChildBeds->parent = $modelChildRooms->id;
                            $modelChildBeds->status = 0;
                            $modelChildBeds->sub_parent = 0;
                            $modelChildBeds->type = 2;
                            $modelChildBeds->main = $model->id;
                            $modelChildBeds->save(false);

                            $child_propertiess = new \app\models\ChildPropertiesListing;
                            $child_propertiess->child_id = $modelChildBeds->id;
                            $child_propertiess->parent_id = $modelChildRooms->id;
                            $child_propertiess->main = $model->id;
                            $child_propertiess->rent = '0';
                            $child_propertiess->deposit = '0';
                            $child_propertiess->token_amount = '0';
                            $child_propertiess->maintenance = '0';
                            $child_propertiess->availability_from = Date('Y-m-d');
                            $child_propertiess->save(false);
                        }
                    }
                }
                $model->updated_by = Yii::$app->user->id;
                $model->updated_date = date('Y-m-d H:i:s');

                $model->save(false);

                Yii::$app->getSession()->setFlash(
                    'success',
                    'Property updated successfully'
                );
                $ids = Yii::$app->userdata->getUserEmailById($model->owner_id);
                $idLead = Yii::$app->userdata->getLeadByEmailId($ids);

                return $this->redirect(['ownerssdetails', 'id' => base64_encode($idLead)]);
            } catch (\Exception $e) {

                Yii::$app->getSession()->setFlash(
                    'success',
                    'Property updated successfully'
                );
                $ids = Yii::$app->userdata->getUserEmailById($model->owner_id);
                $idLead = Yii::$app->userdata->getLeadByEmailId($ids);

                return $this->redirect(['ownerssdetails', 'id' => base64_encode($idLead)]);
            }
        } else {
            if ($redirection !== null) {
                $redirection = 1;
            } else {
                $redirection = 0;
            }
            return $this->render('add_property', [
                'model' => $model,
                'proGenModel' => $proGenModel,
                'manByModel' => $manByModel,
                'modelImages' => $modelImages,
                'modelPropertyAgreements' => $modelPropertyAgreements,
                'redirection' => $redirection,
                'edit' => '1',
            ]);
        }
        //  die($id);
    }

    public function actionOwnersdetails($id)
    {
        $ids = base64_decode($id);
        $proofType = \app\models\ProofType::find()->all();
        $modelComments = new \app\models\Comments();
        if (($models = Users::findOne($ids)) !== null) {
            $modelLeads = \app\models\LeadsOwner::findOne(['email_id' => $models->login_id]);
            $address_proofs = \app\models\UserIdProofs::find()->where(['user_id' => $ids])->all();
            $comments = \app\models\Comments::find()->where(['user_id' => $ids])->orderBy(['id' => SORT_DESC])->all();
            $properties = \app\models\Properties::find()->where(['owner_id' => $ids])->joinWith('propertyListing')->all();

            if (($model = \app\models\OwnerProfile::find()->where(['owner_id' => $ids])->one()) != null) {
                if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
                    Yii::$app->response->format = 'json';
                    return \yii\bootstrap\ActiveForm::validate($model);
                } else if ($model->load(Yii::$app->request->post()) && $models->load(Yii::$app->request->post())) {
                    $model->phone = Yii::$app->userdata->trimPhone($model->phone);
                    $models->phone = $model->phone;
                    $models->username = $models->login_id;
                    if ($model->validate() && $models->validate(['full_name', 'login_id', 'phone'])) {
                        $models->save(false);
                        $model->save(false);
                        if (count($modelLeads) != 0) {
                            $modelLeads->full_name = $models->full_name;
                            $modelLeads->email_id = $models->login_id;
                            $modelLeads->address = $model->address_line_1;
                            $modelLeads->address_line_2 = $model->address_line_2;
                            $modelLeads->region = $model->region;
                            $modelLeads->pincode = $model->pincode;
                            $modelLeads->contact_number = $model->phone;
                            $modelLeads->branch_code = $model->branch_code;
                            $modelLeads->save(false);
                        }
                        if (isset($_FILES['address_proof'])) {
                            $address_proof = $_FILES['address_proof'];
                        } else {
                            $address_proof = array();
                        }
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
                                        $address_proofobj->user_id = $ids;
                                        $address_proofobj->created_by = $ids;
                                        $address_proofobj->created_date = date('Y-m-d H:i:s');
                                        // print_r($address_proofobj);

                                        $address_proofobj->save(false);
                                    }
                                }
                            }
                            // die;
                        }
                        Yii::$app->getSession()->setFlash('success', 'Saved Successfully');
                        return $this->render('ownersdetails', ['model' => $model, 'modelUser' => $models, 'modelComments' => $modelComments, 'comments' => $comments, 'properties' => $properties, 'proofType' => $proofType, 'address_proofs' => $address_proofs,]);
                        //$this->redirect('owner');
                    } else {
                        Yii::$app->getSession()->setFlash('error', 'Profile not saved');
                        return $this->render('ownersdetails', ['model' => $model, 'modelUser' => $models, 'modelComments' => $modelComments, 'comments' => $comments, 'properties' => $properties, 'proofType' => $proofType, 'address_proofs' => $address_proofs,]);
                    }
                } else {
                    return $this->render('ownersdetails', ['model' => $model, 'modelUser' => $models, 'modelComments' => $modelComments, 'comments' => $comments, 'properties' => $properties, 'proofType' => $proofType, 'address_proofs' => $address_proofs,]);
                }
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionApplicants()
    {

        $searchModelOwnerLeads = new LeadsTenant();
        // print_r($searchModelOwnerLeads);die;

        $dataProviderOwner = $searchModelOwnerLeads->searchCommon(Yii::$app->request->queryParams);

        return $this->render('applicants', [
            'searchModelOwnerLeads' => $searchModelOwnerLeads,
            'dataProviderOwner' => $dataProviderOwner,
        ]);
    }

    public function actionTenantdetails($id)
    {
        $proofType = \app\models\ProofType::find()->all();
        $ids = base64_decode($id);
        //echo $ids;die;
        $modelComments = new \app\models\Comments();
        $properties_fav = array();
        if (($models = Users::findOne($ids)) !== null) {

            $emergency_proofs = \app\models\EmergencyProofs::find()->where(['user_id' => $ids])->all();
            $address_proofs = \app\models\UserIdProofs::find()->where(['user_id' => $ids])->all();
            $comments = \app\models\Comments::find()->where(['user_id' => $ids])->orderBy(['id' => SORT_DESC])->all();
            $tenantAgreements = \app\models\TenantAgreements::find()->where(['tenant_id' => $ids])->one();
            $preAgreeFile = $tenantAgreements->agreement_url;

            $leadsTenantModel = \app\models\LeadsTenant::find()->where(['email_id' => Yii::$app->userdata->getEmailById($ids)])->one();
            $applicantProfileModel = \app\models\ApplicantProfile::find()->where(['applicant_id' => $ids])->one();

            if (($model = TenantProfile::findOne(['tenant_id' => $ids])) !== null) {
                $bankCheck = $model->cancelled_check;
                $employmnetProof = $model->employment_proof_url;
                $profileimage = $model->profile_image;
                if ($tenantAgreements->load(Yii::$app->request->post())) {
                    $agreementFile = UploadedFile::getInstance($tenantAgreements, 'agreement_url');
                    if (!empty($agreementFile->name)) {
                        $agreFileName = str_replace(' ', '', 'uploads/' . time() . $agreementFile->name);
                        $agreementFile->saveAs($agreFileName);
                        $tenantAgreements->agreement_url = $agreFileName;
                    } else {
                        $tenantAgreements->agreement_url = $preAgreeFile;
                    }

                    if (!empty(Yii::$app->request->post('TenantAgreements')['lease_terminate_date'])) {
                        $terminateDate = Yii::$app->request->post('TenantAgreements')['ext_lease_end_date'];
                        $terminateRent = $tenantAgreements->rent;
                        $terminateMaintenance = $tenantAgreements->maintainance;
                        $terminateLeaseEndDate = $tenantAgreements->lease_end_date;
                        $terminateParentId = $tenantAgreements->parent_id;
                        $tpRow = Yii::$app->db->createCommand(''
                            . 'SELECT * FROM tenant_payments WHERE tenant_id = ' . $ids . ' '
                            . 'AND parent_id = ' . $terminateParentId . ' AND `due_date` > "' . date('Y-m-d', strtotime("last day of " . $terminateDate)) . '" AND `amount_paid` = 0 '
                            . 'ORDER BY `due_date` DESC, `id` DESC '
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
                            . 'SELECT * FROM tenant_payments WHERE tenant_id = ' . $ids . ' '
                            . 'AND parent_id = ' . $terminateParentId . ' AND `month` = "' . date('m-Y', strtotime($terminateDate)) . '" '
                            . 'ORDER BY `due_date` DESC, `id` DESC '
                            . '')->queryAll();

                        if (count($tpRow) > 0) {
                            foreach ($tpRow as $key => $row) {
                                if ($key == 0) {
                                    $tpRowOne = \app\models\TenantPayments::find()->where(['id' => $row['id']])->one();
                                    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($terminateDate)), date('Y', strtotime($terminateDate)));
                                    $rentPerDay = (float) $terminateRent / $daysInMonth;
                                    $maintenancePerDay = (float) $terminateMaintenance / $daysInMonth;
                                    $coverageDays = (int) date('d', strtotime($terminateDate));
                                    $rentForMonth = (float) round(($rentPerDay * $coverageDays), 2);
                                    $maintenanceForMonth = (float) round(($maintenancePerDay * $coverageDays), 2);
                                    $terminateTotalAmount = round(($rentForMonth + $maintenanceForMonth), 2);

                                    $tpRowOne->original_amount = $rentForMonth;
                                    $tpRowOne->owner_amount = $rentForMonth;
                                    $tpRowOne->maintenance = $maintenanceForMonth;
                                    $tpRowOne->total_amount = (float) ($rentForMonth + $maintenanceForMonth);
                                    $tpRowOne->tenant_amount = $rentForMonth;
                                    $tpRowOne->days_of_rent = $coverageDays;
                                    $tpRowOne->updated_by = Yii::$app->user->id;
                                    $tpRowOne->updated_date = date('Y-m-d H:i:s');
                                    $tpRowOne->save(false);
                                } else {
                                    $tpRowOne = \app\models\TenantPayments::find()->where(['id' => $row['id']])->one();
                                    $tpRowOne->delete();
                                }
                            }
                        }
                        $tenantAgreements->lease_end_date = $terminateDate;
                        $tenantAgreements->updated_by = Yii::$app->user->id;
                        $tenantAgreements->updated_date = date('Y-m-d H:i:s');
                        unset($tpRow);
                        unset($tpRowOne);
                    } else if (!empty(Yii::$app->request->post('TenantAgreements')['lease_extended_date'])) {
                        $extendedDate = date('Y-m-d', strtotime(Yii::$app->request->post('TenantAgreements')['ext_lease_end_date']));
                        $extRent = Yii::$app->request->post('TenantAgreements')['rent'];
                        $extMaintenance = Yii::$app->request->post('TenantAgreements')['maintainance'];
                        $extLeaseEndDate = Yii::$app->request->post('TenantAgreements')['lease_end_date'];
                        $extParentId = $tenantAgreements->parent_id;
                        if (strtotime($extendedDate) > strtotime($extLeaseEndDate)) {
                            $tpRow = Yii::$app->db->createCommand(''
                                . 'SELECT * FROM tenant_payments WHERE tenant_id = ' . $ids . ' '
                                . 'AND parent_id = ' . $extParentId . ' ORDER BY `due_date` DESC, `id` DESC '
                                . '')->queryOne();
                            if (!empty($tpRow) > 0) {
                                $tpRowId = $tpRow['id'];
                                $date1 = new \DateTime($extendedDate);
                                $date2 = new \DateTime($extLeaseEndDate);
                                $diff = $date1->diff($date2);
                                $diffMonth = (($diff->format('%y') * 12) + $diff->format('%m'));
                                $diffDays = $diff->format('%d');
                                if ($diffMonth > 0) {
                                    if (((int) date('Y', strtotime($extendedDate))) == ((int) date('Y', strtotime($extLeaseEndDate)))) {
                                        $extendedMonth = (int) date('m', strtotime($extendedDate)) - (int) date('m', strtotime($extLeaseEndDate));
                                    } else {
                                        $extendedMonth = (int) $diffMonth;
                                    }
                                    $tpRowData = (object) [];
                                    for ($i = 0; $i < $extendedMonth; $i++) {
                                        if ($i === 0) {
                                            $tpRow1 = \app\models\TenantPayments::find()->where(['id' => $tpRowId])->one();
                                            $tpRow = new \app\models\TenantPayments();
                                            $tpRowData = $tpRow1;

                                            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($tpRow1->due_date)), date('Y', strtotime($tpRow1->due_date)));
                                            $rentPerDay = (float) $extRent / $daysInMonth;
                                            $maintenancePerDay = (float) $extMaintenance / $daysInMonth;

                                            $date1 = new \DateTime(date('y-m-t', strtotime($tpRow1->due_date)));
                                            $date2 = new \DateTime($extLeaseEndDate);
                                            $diff2 = $date1->diff($date2);
                                            $coverageDays = $diff2->format('%d');

                                            $rentForMonth = (float) round(($rentPerDay * $coverageDays), 2);
                                            $maintenanceForMonth = (float) round(($maintenancePerDay * $coverageDays), 2);
                                            $extTotalAmount = round(($rentForMonth + $maintenanceForMonth), 2);

                                            $tpRow->tenant_id = $tpRow1->tenant_id;
                                            $tpRow->property_id = $tpRow1->property_id;
                                            $tpRow->parent_id = $tpRow1->parent_id;
                                            $tpRow->room_id = $tpRow1->room_id;
                                            $tpRow->bed_id = $tpRow1->bed_id;
                                            $tpRow->payment_des = $tpRow1->payment_des;
                                            $tpRow->payment_mode = $tpRow1->payment_mode;
                                            $tpRow->payment_type = $tpRow1->payment_type;
                                            $tpRow->remarks = $tpRow1->remarks;
                                            $tpRow->payment_status = $tpRow1->payment_status;
                                            $tpRow->created_date = $tpRow1->created_date;
                                            $tpRow->created_by = $tpRow1->created_by;
                                            $tpRow->penalty_amount = $tpRow1->penalty_amount;
                                            $tpRow->adhoc = $tpRow1->adhoc;
                                            $tpRow->due_date = $tpRow1->due_date;
                                            $tpRow->transaction_id = $tpRow1->transaction_id;
                                            $tpRow->agreement_id = $tpRow1->agreement_id;
                                            $tpRow->calculated = $tpRow1->calculated;
                                            $tpRow->month = $tpRow1->month;
                                            $tpRow->amount_paid = $tpRow1->amount_paid;
                                            $tpRow->original_amount = $rentForMonth;
                                            $tpRow->owner_amount = $rentForMonth;
                                            $tpRow->maintenance = $maintenanceForMonth;
                                            $tpRow->total_amount = $extTotalAmount;
                                            $tpRow->tenant_amount = $rentForMonth;
                                            $tpRow->days_of_rent = $coverageDays;
                                            $tpRow->updated_by = Yii::$app->user->id;
                                            $tpRow->updated_date = date('Y-m-d H:i:s');
                                            if ($extTotalAmount != 0 && $extTotalAmount != 0.00) {
                                                $tpRow->save(false);
                                            }
                                        }

                                        $dueDate = date('Y-m-d', strtotime($tpRowData->due_date . ' +1 month'));
                                        $tpRowData->due_date = $dueDate;
                                        if (strtotime(date('Y-m', strtotime($extendedDate))) == strtotime(date('Y-m', strtotime($dueDate)))) {
                                            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($dueDate)), date('Y', strtotime($dueDate)));
                                            $rentPerDay = (float) $extRent / $daysInMonth;
                                            $maintenancePerDay = (float) $extMaintenance / $daysInMonth;
                                            $coverageDays = (int) date('d', strtotime($extendedDate));
                                            $rentForMonth = (float) round(($rentPerDay * $coverageDays), 2);
                                            $maintenanceForMonth = (float) round(($maintenancePerDay * $coverageDays), 2);
                                            $extTotalAmount = round(($rentForMonth + $maintenanceForMonth), 2);
                                            $tpRow2 = new \app\models\TenantPayments();
                                            $tpRow2->tenant_id = $tpRowData->tenant_id;
                                            $tpRow2->property_id = $tpRowData->property_id;
                                            $tpRow2->parent_id = $tpRowData->parent_id;
                                            $tpRow2->room_id = $tpRowData->room_id;
                                            $tpRow2->bed_id = $tpRowData->bed_id;
                                            $tpRow2->original_amount = $rentForMonth;
                                            $tpRow2->owner_amount = $rentForMonth;
                                            $tpRow2->maintenance = $maintenanceForMonth;
                                            $propertyName = Yii::$app->userdata->getPropertyNameById($tpRowData->parent_id);
                                            if ((!empty($tpRowData->room_id)) && (empty($tpRowData->bed_id))) {
                                                $tpRow2->payment_des = 'Rent for ' . $propertyName . ' Room For ' . Date('m-Y', strtotime($extendedDate));
                                            } else if (!empty($tpRowData->bed_id)) {
                                                $tpRow2->payment_des = 'Rent for ' . $propertyName . ' Bed For ' . Date('m-Y', strtotime($extendedDate));
                                            } else {
                                                $tpRow2->payment_des = 'Rent ' . $propertyName . ' For ' . Date('m-Y', strtotime($extendedDate));
                                            }
                                            $tpRow2->payment_mode = $tpRowData->payment_mode;
                                            $tpRow2->payment_type = $tpRowData->payment_type;
                                            $tpRow2->remarks = $tpRowData->remarks;
                                            $tpRow2->payment_status = 0;
                                            $tpRow2->created_date = date('Y-m-d');
                                            $tpRow2->created_by = Yii::$app->user->id;
                                            $tpRow2->updated_by = Yii::$app->user->id;
                                            $tpRow2->penalty_amount = $tpRowData->penalty_amount;
                                            $tpRow2->total_amount = $extTotalAmount;
                                            $tpRow2->adhoc = $tpRowData->adhoc;
                                            $tpRow2->updated_date = date('Y-m-d H:i:s');
                                            $tpRow2->due_date = $dueDate;
                                            $tpRow2->days_of_rent = $coverageDays;
                                            $tpRow2->transaction_id = $tpRowData->transaction_id;
                                            $tpRow2->agreement_id = $tpRowData->agreement_id;
                                            $tpRow2->calculated = $tpRowData->calculated;
                                            $tpRow2->month = Date('m-Y', strtotime($dueDate));
                                            $tpRow2->tenant_amount = $rentForMonth;
                                            $tpRow2->amount_paid = 0;
                                            if ($extTotalAmount != 0 && $extTotalAmount != 0.00) {
                                                $tpRow2->save(false);
                                            }
                                        } else {
                                            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($dueDate)), date('Y', strtotime($dueDate)));
                                            $rentPerDay = (float) $extRent / $daysInMonth;
                                            $maintenancePerDay = (float) $extMaintenance / $daysInMonth;
                                            $coverageDays = (int) $daysInMonth;
                                            $rentForMonth = (float) round(($rentPerDay * $coverageDays), 2);
                                            $maintenanceForMonth = (float) round(($maintenancePerDay * $coverageDays), 2);
                                            $extTotalAmount = round(($rentForMonth + $maintenanceForMonth), 2);
                                            $tpRow2 = new \app\models\TenantPayments();
                                            $tpRow2->tenant_id = $tpRowData->tenant_id;
                                            $tpRow2->property_id = $tpRowData->property_id;
                                            $tpRow2->parent_id = $tpRowData->parent_id;
                                            $tpRow2->room_id = $tpRowData->room_id;
                                            $tpRow2->bed_id = $tpRowData->bed_id;
                                            $tpRow2->original_amount = $rentForMonth;
                                            $tpRow2->owner_amount = $rentForMonth;
                                            $tpRow2->maintenance = $maintenanceForMonth;
                                            $propertyName = Yii::$app->userdata->getPropertyNameById($tpRowData->parent_id);
                                            if ((!empty($tpRowData->room_id)) && (empty($tpRowData->bed_id))) {
                                                $tpRow2->payment_des = 'Rent for ' . $propertyName . ' Room For ' . Date('m-Y', strtotime($dueDate));
                                            } else if (!empty($tpRowData->bed_id)) {
                                                $tpRow2->payment_des = 'Rent for ' . $propertyName . ' Bed For ' . Date('m-Y', strtotime($dueDate));
                                            } else {
                                                $tpRow2->payment_des = 'Rent ' . $propertyName . ' For ' . Date('m-Y', strtotime($dueDate));
                                            }
                                            $tpRow2->payment_mode = $tpRowData->payment_mode;
                                            $tpRow2->payment_type = $tpRowData->payment_type;
                                            $tpRow2->remarks = $tpRowData->remarks;
                                            $tpRow2->payment_status = 0;
                                            $tpRow2->created_date = date('Y-m-d');
                                            $tpRow2->created_by = Yii::$app->user->id;
                                            $tpRow2->updated_by = Yii::$app->user->id;
                                            $tpRow2->penalty_amount = $tpRowData->penalty_amount;
                                            $tpRow2->total_amount = $extTotalAmount;
                                            $tpRow2->adhoc = $tpRowData->adhoc;
                                            $tpRow2->updated_date = date('Y-m-d H:i:s');
                                            $tpRow2->due_date = $dueDate;
                                            $tpRow2->days_of_rent = $coverageDays;
                                            $tpRow2->transaction_id = $tpRowData->transaction_id;
                                            $tpRow2->agreement_id = $tpRowData->agreement_id;
                                            $tpRow2->calculated = $tpRowData->calculated;
                                            $tpRow2->month = Date('m-Y', strtotime($dueDate));
                                            $tpRow2->tenant_amount = $rentForMonth;
                                            $tpRow2->amount_paid = 0;
                                            if ($extTotalAmount != 0 && $extTotalAmount != 0.00) {
                                                $tpRow2->save(false);
                                            }
                                        }
                                    }

                                    $tenantAgreements->lease_end_date = $extendedDate;
                                    $tenantAgreements->updated_by = Yii::$app->user->id;
                                    $tenantAgreements->updated_date = date('Y-m-d H:i:s');
                                } else if ($diffDays > 0) {
                                    $tpRow1 = \app\models\TenantPayments::find()->where(['id' => $tpRowId])->one();
                                    $tpRow = new \app\models\TenantPayments();
                                    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($extLeaseEndDate)), date('Y', strtotime($extLeaseEndDate)));
                                    $rentPerDay = (float) $extRent / $daysInMonth;
                                    $maintenancePerDay = (float) $extMaintenance / $daysInMonth;
                                    if (strtotime(date('Y-m', strtotime($extendedDate))) == strtotime(date('Y-m', strtotime($tpRow1->due_date)))) {
                                        $rentForMonth = (float) round(($rentPerDay * $diffDays), 2);
                                        $maintenanceForMonth = (float) round(($maintenancePerDay * $diffDays), 2);
                                        $extTotalAmount = round(($rentForMonth + $maintenanceForMonth), 2);
                                        $tpRow->tenant_id = $tpRow1->tenant_id;
                                        $tpRow->property_id = $tpRow1->property_id;
                                        $tpRow->parent_id = $tpRow1->parent_id;
                                        $tpRow->room_id = $tpRow1->room_id;
                                        $tpRow->bed_id = $tpRow1->bed_id;
                                        $tpRow->payment_des = $tpRow1->payment_des;
                                        $tpRow->payment_mode = $tpRow1->payment_mode;
                                        $tpRow->payment_type = $tpRow1->payment_type;
                                        $tpRow->remarks = $tpRow1->remarks;
                                        $tpRow->payment_status = $tpRow1->payment_status;
                                        $tpRow->created_date = $tpRow1->created_date;
                                        $tpRow->created_by = $tpRow1->created_by;
                                        $tpRow->penalty_amount = $tpRow1->penalty_amount;
                                        $tpRow->adhoc = $tpRow1->adhoc;
                                        $tpRow->due_date = $tpRow1->due_date;
                                        $tpRow->transaction_id = $tpRow1->transaction_id;
                                        $tpRow->agreement_id = $tpRow1->agreement_id;
                                        $tpRow->calculated = $tpRow1->calculated;
                                        $tpRow->month = $tpRow1->month;
                                        $tpRow->amount_paid = $tpRow1->amount_paid;
                                        $tpRow->original_amount = $rentForMonth;
                                        $tpRow->owner_amount = $rentForMonth;
                                        $tpRow->maintenance = $maintenanceForMonth;
                                        $tpRow->total_amount = $extTotalAmount;
                                        $tpRow->tenant_amount = $rentForMonth;
                                        $tpRow->days_of_rent = $diffDays;
                                        $tpRow->updated_by = Yii::$app->user->id;
                                        $tpRow->updated_date = date('Y-m-d H:i:s');
                                        if ($extTotalAmount != 0 && $extTotalAmount != 0.00) {
                                            $tpRow->save(false);
                                        }
                                    } else {
                                        $date1 = new \DateTime(date('y-m-t', strtotime($tpRow1->due_date)));
                                        $date2 = new \DateTime($extLeaseEndDate);
                                        $diff = $date1->diff($date2);
                                        $currCoverageDays = $diff->format('%d');
                                        if ($currCoverageDays > 0) {
                                            $rentForMonth = (float) round(($rentPerDay * $currCoverageDays), 2);
                                            $maintenanceForMonth = (float) round(($maintenancePerDay * $currCoverageDays), 2);
                                            $extTotalAmount = round(($rentForMonth + $maintenanceForMonth), 2);
                                            $tpRow->tenant_id = $tpRow1->tenant_id;
                                            $tpRow->property_id = $tpRow1->property_id;
                                            $tpRow->parent_id = $tpRow1->parent_id;
                                            $tpRow->room_id = $tpRow1->room_id;
                                            $tpRow->bed_id = $tpRow1->bed_id;
                                            $tpRow->payment_des = $tpRow1->payment_des;
                                            $tpRow->payment_mode = $tpRow1->payment_mode;
                                            $tpRow->payment_type = $tpRow1->payment_type;
                                            $tpRow->remarks = $tpRow1->remarks;
                                            $tpRow->payment_status = $tpRow1->payment_status;
                                            $tpRow->created_date = $tpRow1->created_date;
                                            $tpRow->created_by = $tpRow1->created_by;
                                            $tpRow->penalty_amount = $tpRow1->penalty_amount;
                                            $tpRow->adhoc = $tpRow1->adhoc;
                                            $tpRow->due_date = $tpRow1->due_date;
                                            $tpRow->transaction_id = $tpRow1->transaction_id;
                                            $tpRow->agreement_id = $tpRow1->agreement_id;
                                            $tpRow->calculated = $tpRow1->calculated;
                                            $tpRow->month = $tpRow1->month;
                                            $tpRow->amount_paid = $tpRow1->amount_paid;
                                            $tpRow->original_amount = $rentForMonth;
                                            $tpRow->owner_amount = $rentForMonth;
                                            $tpRow->maintenance = $maintenanceForMonth;
                                            $tpRow->total_amount = (float) ($rentForMonth + $maintenanceForMonth);
                                            $tpRow->tenant_amount = $rentForMonth;
                                            $tpRow->days_of_rent = $currCoverageDays;
                                            $tpRow->updated_by = Yii::$app->user->id;
                                            $tpRow->updated_date = date('Y-m-d H:i:s');
                                            if ($tpRow->total_amount != 0 && $tpRow->total_amount != 0.00) {
                                                $tpRow->save(false);
                                            }
                                        }

                                        $coverageDays = (int) ($diffDays - $currCoverageDays);
                                        $rentForMonth = (float) round(($rentPerDay * $coverageDays), 2);
                                        $maintenanceForMonth = (float) round(($maintenancePerDay * $coverageDays), 2);
                                        $extTotalAmount = round(($rentForMonth + $maintenanceForMonth), 2);

                                        $tpRow2 = new \app\models\TenantPayments();
                                        $tpRow2->tenant_id = $tpRow1->tenant_id;
                                        $tpRow2->property_id = $tpRow1->property_id;
                                        $tpRow2->parent_id = $tpRow1->parent_id;
                                        $tpRow2->room_id = $tpRow1->room_id;
                                        $tpRow2->bed_id = $tpRow1->bed_id;
                                        $tpRow2->original_amount = $rentForMonth;
                                        $tpRow2->owner_amount = $rentForMonth;
                                        $tpRow2->maintenance = $maintenanceForMonth;
                                        $propertyName = Yii::$app->userdata->getPropertyNameById($tpRow1->parent_id);
                                        if ((!empty($tpRow1->room_id)) && (empty($tpRow1->bed_id))) {
                                            $tpRow2->payment_des = 'Rent for ' . $propertyName . ' Room For ' . Date('m-Y', strtotime($extendedDate));
                                        } else if (!empty($tpRow1->bed_id)) {
                                            $tpRow2->payment_des = 'Rent for ' . $propertyName . ' Bed For ' . Date('m-Y', strtotime($extendedDate));
                                        } else {
                                            $tpRow2->payment_des = 'Rent ' . $propertyName . ' For ' . Date('m-Y', strtotime($extendedDate));
                                        }
                                        $tpRow2->payment_mode = $tpRow1->payment_mode;
                                        $tpRow2->payment_type = $tpRow1->payment_type;
                                        $tpRow2->remarks = $tpRow1->remarks;
                                        $tpRow2->payment_status = 0;
                                        $tpRow2->created_date = date('Y-m-d');
                                        $tpRow2->created_by = Yii::$app->user->id;
                                        $tpRow2->updated_by = Yii::$app->user->id;
                                        $tpRow2->penalty_amount = $tpRow1->penalty_amount;
                                        $tpRow2->total_amount = $extTotalAmount;
                                        $tpRow2->adhoc = $tpRow1->adhoc;
                                        $tpRow2->updated_date = date('Y-m-d H:i:s');
                                        $tpRow2->due_date = date('Y-m-d', strtotime($tpRow1->due_date . ' +1 month'));
                                        $tpRow2->days_of_rent = $coverageDays;
                                        $tpRow2->transaction_id = $tpRow1->transaction_id;
                                        $tpRow2->agreement_id = $tpRow1->agreement_id;
                                        $tpRow2->calculated = $tpRow1->calculated;
                                        $tpRow2->month = Date('m-Y', strtotime($extendedDate));
                                        $tpRow2->tenant_amount = $rentForMonth;
                                        $tpRow2->amount_paid = 0;
                                        if ($extTotalAmount != 0 && $extTotalAmount != 0.00) {
                                            $tpRow2->save(false);
                                        }
                                    }

                                    $tenantAgreements->lease_end_date = $extendedDate;
                                    $tenantAgreements->updated_by = Yii::$app->user->id;
                                    $tenantAgreements->updated_date = date('Y-m-d H:i:s');
                                }
                            }
                        }
                    }

                    $tenantAgreements->late_penalty_percent = Yii::$app->request->post('TenantAgreements')['late_penalty_percent'];
                    $tenantAgreements->min_penalty = Yii::$app->request->post('TenantAgreements')['min_penalty'];

                    if ($tenantAgreements->save(false)) {
                        Yii::$app->getSession()->setFlash(
                            'success',
                            'Contract is saved successfully.'
                        );
                    }
                    $teAg = \app\models\TenantAgreements::find()->where(['tenant_id' => $ids])->one();
                    return $this->render('tenantdetails', [
                        'model' => $model, 'tenantAgreements' => $teAg, 'modelComments' => $modelComments, 'modelLeads' => $models, 'comments' => $comments, 'proofType' => $proofType,
                        'emergency_proofs' => $emergency_proofs,
                        'address_proofs' => $address_proofs,
                    ]);
                } else if ($model->load(Yii::$app->request->post())) {
                    $models->load(Yii::$app->request->post());
                    $models->username = $models->login_id;
                    $model->phone = Yii::$app->userdata->trimPhone($model->phone);
                    $models->phone = $model->phone;
                    $valErrorProfile = $model->validate();
                    $valErrorUser = $models->validate(['full_name', 'login_id', 'phone']);
                    if ($valErrorProfile && $valErrorUser) {

                        //$tenantAgreements->save(false);
                        $models->save(false);
                        $profile_image = UploadedFile::getInstance($model, 'profile_image');
                        if (isset($_FILES['address_proof'])) {
                            $address_proof = $_FILES['address_proof'];
                        } else {
                            $address_proof = array();
                        }
                        $cancelled_check = UploadedFile::getInstance($model, 'cancelled_check');
                        $employmnet_proof_url = UploadedFile::getInstance($model, 'employment_proof_url');


                        if (isset($_FILES['emergency_proof'])) {
                            $emer_contact_address = $_FILES['emergency_proof'];
                        } else {
                            $emer_contact_address = array();
                        }
                        if (!empty($emer_contact_address)) {
                            foreach ($emer_contact_address['tmp_name'] as $key => $value) {

                                foreach ($value as $key1 => $value1) {
                                    $emergency_proofobj = new \app\models\EmergencyProofs;
                                    $name_address_proof = (string) date('ymdHis') . rand() . $emer_contact_address['name'][$key][$key1];
                                    $tmp_file = (string) $value1;
                                    move_uploaded_file($value1, 'uploads/proofs/' . $name_address_proof);
                                    $emergency_proofobj->proof_type = $key;
                                    // $emergency_proofobj->proof_document_url = Url::home(true).'uploads/proofs/' .$name_address_proof ;
                                    $emergency_proofobj->proof_document_url = 'uploads/proofs/' . $name_address_proof;
                                    $emergency_proofobj->user_id = $ids;
                                    $emergency_proofobj->created_by = $ids;
                                    $emergency_proofobj->created_date = date('Y-m-d H:i:s');
                                    $emergency_proofobj->save(false);
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
                        } else {
                            $model->employment_proof_url = $employmnetProof;
                        }
                        //print_r($model);exit;

                        if (!empty($applicantProfileModel)) {
                            $applicantProfileModel->emergency_contact_name = $model->emergency_contact_name;
                            $applicantProfileModel->emergency_contact_number = $model->emergency_contact_number;
                            $applicantProfileModel->emer_contact_indentity = $model->emer_contact_indentity;
                            $applicantProfileModel->emer_contact_address = $model->emer_contact_address;
                            $applicantProfileModel->emergency_contact_email = $model->emer_contact;
                            $applicantProfileModel->status = $model->status;
                            $applicantProfileModel->sales_id = $model->sales_id;
                            $applicantProfileModel->operation_id = $model->operation_id;
                            $applicantProfileModel->address_line_1 = $model->address_line_1;
                            $applicantProfileModel->address_line_2 = $model->address_line_2;
                            $applicantProfileModel->state = $model->state;
                            $applicantProfileModel->city = $model->city;
                            $applicantProfileModel->city_name = $model->city_name;
                            $applicantProfileModel->region = $model->region;
                            $applicantProfileModel->branch_code = $model->branch_code;
                            $applicantProfileModel->pincode = $model->pincode;
                            $applicantProfileModel->phone = $model->phone;
                            $applicantProfileModel->proof_type = $model->proof_type;
                            $applicantProfileModel->proof_document_url = $model->proof_document_url;
                            $applicantProfileModel->account_holder_name = $model->account_holder_name;
                            $applicantProfileModel->bank_name = $model->bank_name;
                            $applicantProfileModel->bank_branchname = $model->bank_branchname;
                            $applicantProfileModel->bank_ifcs = $model->bank_ifcs;
                            $applicantProfileModel->pan_number = $model->pan_number;
                            $applicantProfileModel->cancelled_check = $model->cancelled_check;
                            $applicantProfileModel->employer_name = $model->employer_name;
                            $applicantProfileModel->employee_id = $model->employee_id;
                            $applicantProfileModel->employment_start_date = $model->employment_start_date;
                            $applicantProfileModel->employment_email = $model->employment_email;
                            $applicantProfileModel->employmnet_proof_url = $model->employment_proof_url;
                            $applicantProfileModel->employmnet_proof_type = $model->employment_proof_type;
                            $applicantProfileModel->bank_account_number = $model->account_number;
                            $applicantProfileModel->profile_image = $model->profile_image;
                            $applicantProfileModel->email_id = $models->login_id;
                            $applicantProfileModel->save(false);
                        }

                        if (!empty($leadsTenantModel)) {
                            $leadsTenantModel->full_name = $models->full_name;
                            $leadsTenantModel->email_id = $models->login_id;
                            $leadsTenantModel->contact_number = $models->phone;
                            $leadsTenantModel->address = $model->address_line_1;
                            $leadsTenantModel->address_line_2 = $model->address_line_2;
                            $leadsTenantModel->pincode = $model->pincode;
                            $leadsTenantModel->branch_code = $model->branch_code;
                            $leadsTenantModel->save(false);
                        }


                        $model->save(false);

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
                                        $address_proofobj->user_id = $ids;
                                        $address_proofobj->created_by = $ids;
                                        $address_proofobj->created_date = date('Y-m-d H:i:s');
                                        $address_proofobj->save(false);
                                    }
                                }
                            }
                            // die;
                        }

                        $emergency_proofs = \app\models\EmergencyProofs::find()->where(['user_id' => $ids])->all();
                        $address_proofs = \app\models\UserIdProofs::find()->where(['user_id' => $ids])->all();
                        Yii::$app->getSession()->setFlash(
                            'success',
                            'Profile is saved successfully.'
                        );

                        return $this->render('tenantdetails', [
                            'model' => $model, 'tenantAgreements' => $tenantAgreements, 'modelComments' => $modelComments, 'modelLeads' => $models, 'comments' => $comments, 'proofType' => $proofType,
                            'emergency_proofs' => $emergency_proofs,
                            'address_proofs' => $address_proofs,
                        ]);
                    } else {

                        Yii::$app->getSession()->setFlash(
                            'error',
                            'Some error occured, Profile not saved.'
                        );

                        return $this->render('tenantdetails', [
                            'model' => $model,
                            'modelLeads' => $models,
                            'tenantAgreements' => $tenantAgreements,
                            'modelComments' => $modelComments,
                            'comments' => $comments,
                            'proofType' => $proofType,
                            'emergency_proofs' => $emergency_proofs,
                            'address_proofs' => $address_proofs,
                        ]);
                    }
                } else {
                    // print_r([
                    //   'model' => $model,
                    //   'modelLeads' => $models ,
                    //   'modelComments' => $modelComments,
                    //   'comments' => $comments,
                    //   'proofType'=>$proofType,
                    //   'emergency_proofs'=>$emergency_proofs,
                    //   'address_proofs'=>$address_proofs,
                    // ]);
                    // die;
                    return $this->render('tenantdetails', [
                        'model' => $model,
                        'modelLeads' => $models,
                        'modelComments' => $modelComments,
                        'tenantAgreements' => $tenantAgreements,
                        'comments' => $comments,
                        'proofType' => $proofType,
                        'emergency_proofs' => $emergency_proofs,
                        'address_proofs' => $address_proofs,
                    ]);
                }
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionApplicantsdetails($id)
    {
        $proofType = \app\models\ProofType::find()->all();
        $id = base64_decode($id);
        $modelComments = new \app\models\Comments();
        $properties_fav = array();

        if (($models = LeadsTenant::findOne($id)) !== null) {
            $ids = Yii::$app->userdata->getUserIdByEmail($models->email_id);
            if ($ids) {
                $emergency_proofs = \app\models\EmergencyProofs::find()->where(['user_id' => $ids])->all();
                $address_proofs = \app\models\UserIdProofs::find()->where(['user_id' => $ids])->all();
                $comments = \app\models\Comments::find()->where(['user_id' => $ids])->orderBy(['id' => SORT_DESC])->all();

                $tenantAgreements = \app\models\TenantAgreements::find()->where(['tenant_id' => $ids])->all();
                $pIds = [];
                foreach ($tenantAgreements as $agreements) {
                    $pIds[] = $agreements->parent_id;
                }

                $fav = \app\models\FavouriteProperties::find()->where(['applicant_id' => $models->email_id])->andWhere(['NOT IN', 'property_id', $pIds])->all();

                $connection = Yii::$app->getDb();
                if (count($pIds) != 0) {
                    $command = $connection->createCommand('select p.id,p.property_name from favourite_properties as f left join properties as p on f.property_id=p.id where f.applicant_id="' . $models->email_id . '" and f.property_id NOT IN (' . implode(",", $pIds) . ')');
                } else {
                    $command = $connection->createCommand('select p.id,p.property_name from favourite_properties as f left join properties as p on f.property_id=p.id where f.applicant_id="' . $models->email_id . '"');
                }

                $properties_fav = $command->queryAll();

                if (($model = ApplicantProfile::findOne(['applicant_id' => $ids])) !== null) {
                    $bankCheck = $model->cancelled_check;
                    $employmnetProof = $model->employmnet_proof_url;
                    $profileimage = $model->profile_image;



                    //                    if ($models->load(Yii::$app->request->post())) {
                    //                        if (($usermodels = Users::findOne(['id' => $ids])) !== null) {
                    //                            $usermodels->full_name = $models->full_name;
                    //                            $usermodels->save(false);
                    //                            $models->full_name = $models->full_name;
                    //                            if (isset($_POST['LeadsTenant']['follow_up_date_time'])) {
                    //                                $models->follow_up_date_time = date('Y-m-d H:i:s', strtotime($_POST['LeadsTenant']['follow_up_date_time']));
                    //                            }
                    //                            $models->save(false);
                    //
                    //                            $model->status = $models->ref_status;
                    //                            $model->save(false);
                    //                        }
                    //                    }
                    if ($model->load(Yii::$app->request->post())) {
                        $model->phone = Yii::$app->userdata->trimPhone($model->phone);
                        $model->employment_start_date = (!empty($model->employment_start_date)) ? $model->employment_start_date : null;
                        if ($model->validate()) {
                            if (isset($_FILES['emergency_proof'])) {
                                $emer_contact_address = $_FILES['emergency_proof'];
                            } else {
                                $emer_contact_address = array();
                            }
                            if (!empty($emer_contact_address)) {
                                foreach ($emer_contact_address['tmp_name'] as $key => $value) {

                                    foreach ($value as $key1 => $value1) {
                                        $emergency_proofobj = new \app\models\EmergencyProofs;
                                        $name_address_proof = (string) date('ymdHis') . rand() . $emer_contact_address['name'][$key][$key1];
                                        $tmp_file = (string) $value1;
                                        move_uploaded_file($value1, 'uploads/proofs/' . $name_address_proof);
                                        $emergency_proofobj->proof_type = $key;
                                        $emergency_proofobj->proof_document_url = 'uploads/proofs/' . $name_address_proof;
                                        $emergency_proofobj->user_id = $ids;
                                        $emergency_proofobj->created_by = $ids;
                                        $emergency_proofobj->created_date = date('Y-m-d H:i:s');
                                        $emergency_proofobj->save(false);
                                    }
                                }
                            }

                            $models->load(Yii::$app->request->post());
                            if (isset($_POST['LeadsTenant']['follow_up_date_time'])) {
                                $models->follow_up_date_time = date('Y-m-d H:i:s', strtotime($_POST['LeadsTenant']['follow_up_date_time']));
                            }

                            if (($usermodels = Users::findOne(['id' => $ids])) !== null) {

                                $usermodels->full_name = $models->full_name;
                                $usermodels->login_id = $model->email_id;
                                $usermodels->username = $model->email_id;
                                $usermodels->phone = $model->phone;
                                $usermodels->save(false);
                            }

                            $models->address = $model->address_line_1;
                            $models->address_line_2 = $model->address_line_2;
                            $models->email_id = $model->email_id;
                            $models->contact_number = $model->phone;
                            $models->pincode = $model->pincode;
                            $models->save(false);

                            $profile_image = UploadedFile::getInstance($model, 'profile_image');
                            if (isset($_FILES['address_proof'])) {
                                $address_proof = $_FILES['address_proof'];
                            } else {
                                $address_proof = array();
                            }

                            $cancelled_check = UploadedFile::getInstance($model, 'cancelled_check');
                            $employmnet_proof_url = UploadedFile::getInstance($model, 'employmnet_proof_url');

                            if (isset($_FILES['emergency_proof'])) {
                                $emer_contact_address = $_FILES['emergency_proof'];
                            } else {
                                $emer_contact_address = array();
                            }

                            if (!empty($emer_contact_address)) {
                                foreach ($emer_contact_address['tmp_name'] as $key => $value) {
                                    foreach ($value as $key1 => $value1) {
                                        $emergency_proofobj = new \app\models\EmergencyProofs;
                                        $name_address_proof = (string) date('ymdHis') . rand() . $emer_contact_address['name'][$key][$key1];
                                        $tmp_file = (string) $value1;
                                        move_uploaded_file($value1, 'uploads/proofs/' . $name_address_proof);
                                        $emergency_proofobj->proof_type = $key;
                                        $emergency_proofobj->proof_document_url = 'uploads/proofs/' . $name_address_proof;
                                        $emergency_proofobj->user_id = $ids;
                                        $emergency_proofobj->created_by = $ids;
                                        $emergency_proofobj->created_date = date('Y-m-d H:i:s');
                                        $emergency_proofobj->save(false);
                                    }
                                }
                            }

                            if (!empty($cancelled_check)) {
                                $name_cancelled_check = date('ymdHis') . $cancelled_check->name;
                                $cancelled_check->saveAs('uploads/proofs/' . $name_cancelled_check);
                                $model->cancelled_check = 'uploads/proofs/' . $name_cancelled_check;
                            } else {
                                $model->cancelled_check = $bankCheck;
                            }

                            if (!empty($profile_image)) {
                                $name_profile_image = date('ymdHis') . $profile_image->name;
                                $profile_image->saveAs('uploads/profiles/' . $name_profile_image);
                                $model->profile_image = 'uploads/profiles/' . $name_profile_image;
                            } else {
                                $model->profile_image = $profileimage;
                            }

                            if (!empty($employmnet_proof_url)) {
                                $name_employmnet_proof_url = date('ymdHis') . $employmnet_proof_url->name;
                                $employmnet_proof_url->saveAs('uploads/proofs/' . $name_employmnet_proof_url);
                                $model->employmnet_proof_url = 'uploads/proofs/' . $name_employmnet_proof_url;
                            } else {
                                $model->employmnet_proof_url = $employmnetProof;
                            }
                            $model->save(false);

                            //                            $modelUsers = Users::findOne($ids);
                            //                            $modelUsers->login_id = $model->email_id;
                            //                            $modelUsers->save(false);

                            $tenantProModel = TenantProfile::findOne(['tenant_id' => $model->applicant_id]);
                            if (!empty($tenantProModel)) {
                                $tenantProModel->emergency_contact_name = $model->emergency_contact_name;
                                $tenantProModel->emergency_contact_number = $model->emergency_contact_number;
                                $tenantProModel->emer_contact_indentity = $model->emer_contact_indentity;
                                $tenantProModel->emer_contact_address = $model->emer_contact_address;
                                $tenantProModel->emer_contact = $model->emergency_contact_email;
                                $tenantProModel->status = $model->status;
                                $tenantProModel->sales_id = $model->sales_id;
                                $tenantProModel->operation_id = $model->operation_id;
                                $tenantProModel->address_line_1 = $model->address_line_1;
                                $tenantProModel->address_line_2 = $model->address_line_2;
                                $tenantProModel->state = $model->state;
                                $tenantProModel->city = $model->city;
                                $tenantProModel->city_name = $model->city_name;
                                $tenantProModel->region = $model->region;
                                $tenantProModel->branch_code = $model->branch_code;
                                $tenantProModel->pincode = $model->pincode;
                                $tenantProModel->phone = $model->phone;
                                $tenantProModel->proof_type = $model->proof_type;
                                $tenantProModel->proof_document_url = $model->proof_document_url;
                                $tenantProModel->account_holder_name = $model->account_holder_name;
                                $tenantProModel->bank_name = $model->bank_name;
                                $tenantProModel->bank_branchname = $model->bank_branchname;
                                $tenantProModel->bank_ifcs = $model->bank_ifcs;
                                $tenantProModel->pan_number = $model->pan_number;
                                $tenantProModel->cancelled_check = $model->cancelled_check;
                                $tenantProModel->employer_name = $model->employer_name;
                                $tenantProModel->employee_id = $model->employee_id;
                                $tenantProModel->employment_start_date = $model->employment_start_date;
                                $tenantProModel->employment_email = $model->employment_email;
                                $tenantProModel->employment_proof_url = $model->employmnet_proof_url;
                                $tenantProModel->employment_proof_type = $model->employmnet_proof_type;
                                $tenantProModel->account_number = $model->bank_account_number;
                                $tenantProModel->profile_image = $model->profile_image;
                                $tenantProModel->save(false);
                            }

                            if (!empty($address_proof)) {
                                foreach ($address_proof['tmp_name'] as $key => $value) {

                                    foreach ($value as $key1 => $value1) {
                                        $address_proofobj = new \app\models\UserIdProofs;
                                        $name_address_proof = (string) date('ymdHis') . rand() . $address_proof['name'][$key][$key1];
                                        $tmp_file = (string) $value1;
                                        move_uploaded_file($value1, 'uploads/proofs/' . $name_address_proof);
                                        $address_proofobj->proof_type = $key;
                                        $address_proofobj->proof_document_url = 'uploads/proofs/' . $name_address_proof;
                                        $address_proofobj->user_id = $ids;
                                        $address_proofobj->created_by = $ids;
                                        $address_proofobj->created_date = date('Y-m-d H:i:s');
                                        $address_proofobj->save(false);
                                    }
                                }
                            }

                            $emergency_proofs = \app\models\EmergencyProofs::find()->where(['user_id' => $ids])->all();
                            $address_proofs = \app\models\UserIdProofs::find()->where(['user_id' => $ids])->all();
                            Yii::$app->getSession()->setFlash(
                                'success',
                                'Profile is saved successfully.'
                            );

                            return $this->render('applicant_details', [
                                'model' => $model, 'fav' => $fav, 'modelComments' => $modelComments, 'modelLeads' => $models, 'comments' => $comments,
                                'proofType' => $proofType,
                                'props' => $properties_fav,
                                'emergency_proofs' => $emergency_proofs,
                                'address_proofs' => $address_proofs,
                            ]);
                        } else {

                            return $this->render('applicant_details', [
                                'model' => $model,
                                'modelLeads' => $models,
                                'modelComments' => $modelComments,
                                'comments' => $comments,
                                'fav' => $fav,
                                'props' => $properties_fav,
                                'proofType' => $proofType,
                                'emergency_proofs' => $emergency_proofs,
                                'address_proofs' => $address_proofs,
                            ]);
                        }
                    } else {
                        return $this->render('applicant_details', [
                            'model' => $model,
                            'modelLeads' => $models,
                            'modelComments' => $modelComments,
                            'comments' => $comments,
                            'fav' => $fav,
                            'props' => $properties_fav,
                            'proofType' => $proofType,
                            'emergency_proofs' => $emergency_proofs,
                            'address_proofs' => $address_proofs,
                        ]);
                    }
                } else {
                    throw new NotFoundHttpException('The requested page does not exist.');
                }
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionCreatefav()
    {
        $model = new \app\models\FavouriteProperties;
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = 'json';
            return \yii\bootstrap\ActiveForm::validate($model);
        } else if ($model->load(Yii::$app->request->post())) {
            try {
                $model->created_by = Yii::$app->user->id;
                if ($_POST['FavouriteProperties']['status'] == 2) {
                    $model->visit_time = date('H:i:s', strtotime($_POST['FavouriteProperties']['visit_time']));
                    $model->visit_date = date('Y-m-d', strtotime($_POST['FavouriteProperties']['visit_date']));
                }
                $model->created_date = date('Y-m-d H:i:s');
                $property_type = \app\models\Properties::find()->select('property_type')->where(['id' => $model->property_id])->one();
                $model->type = $property_type->property_type;
                $model->child_properties = $model->property_id;
                $model->save(false);
                $email = $model->applicant_id;

                $userdata = \app\models\LeadsTenant::find()->select('id')->where(['email_id' => $email])->one();

                Yii::$app->getSession()->setFlash(
                    'success',
                    'Property added successfully'
                );
                return $this->redirect(['applicantsdetails?id=' . base64_encode($userdata->id)]);
            } catch (\Exception $e) {
                Yii::$app->getSession()->setFlash(
                    'success',
                    "{$e->getMessage()}"
                );
                return $this->renderAjax('_addfav', [
                    'model' => $model
                ]);
            }
        } else {
            return $this->renderAjax('_addfav', [
                'model' => $model
            ]);
        }
    }

    public function actionCreatelease()
    {
        $arrr = ['tenantagreements-lease_start_date' => ['Lease start date cannot be blank.']];
        $applicantdetails = \app\models\ApplicantProfile::findOne(['applicant_id' => $_GET['applicant_id']]);

        $model = new \app\models\TenantAgreements;

        $leads_Applicant = \app\models\LeadsTenant::findOne(['email_id' => Yii::$app->userdata->getUserEmailById($_GET['applicant_id'])]);

        $cutOffDate = \app\models\SystemConfig::find()->where(['name' => 'CUTOFF-DATE'])->one()->value;
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            if ($applicantdetails->account_holder_name != '' && $applicantdetails->bank_name != '' && $applicantdetails->bank_branchname != '' && $applicantdetails->bank_ifcs != '' && $applicantdetails->bank_account_number != '' && $applicantdetails->pan_number != '') {
                $numDaysInMonth = 0;
                Yii::$app->response->format = 'json';
                $asd = \yii\bootstrap\ActiveForm::validate($model);
                if (!empty($asd)) {
                    return $asd;
                } else {

                    $transaction = Yii::$app->db->beginTransaction();
                    $leaseType = 1; // 1 stands for family and 2 stands for coliving.
                    $adjustedWalletAmount = (float) 0.00;
                    try {
                        $valTenModel = \app\models\ApplicantProfile::find()->where(['applicant_id' => Yii::$app->request->post('TenantAgreements')['tenant_id']])->one();
                        $valProModel = \app\models\Properties::find()->where(['id' => Yii::$app->request->post('TenantAgreements')['property_id']])->one();
                        if ($valTenModel && $valProModel) {
                            $tenOps = $valTenModel->operation_id;
                            $tenSal = $valTenModel->sales_id;
                            $proOps = $valProModel->operations_id;
                            $proSal = $valProModel->sales_id;

                            //                            if ($tenOps != $proOps) {
                            //                                $resObj = new \stdClass;
                            //                                $resObj->success = 0;
                            //                                $resObj->error = "Tenant's Operation Executive should be same as Property Operation Executive";
                            //                                echo json_encode($resObj); exit;
                            //                            }
                            //                            
                            //                            if ($tenSal != $proSal) {
                            //                                $resObj = new \stdClass;
                            //                                $resObj->success = 0;
                            //                                $resObj->error = "Tenant's Sales Executive should be same as Property Sales Executive";
                            //                                echo json_encode($resObj); exit;
                            //                            }
                            //                            
                            //                            $valSalModel = \app\models\SalesProfile::find()->where(['sale_id' => $tenSal])->one();
                            //                            if ($valSalModel->role_code != 'SLEXE' && $valSalModel->role_code != 'SLBRMG') {
                            //                                $resObj = new \stdClass;
                            //                                $resObj->success = 0;
                            //                                $resObj->error = "Assigned Sales member should be Executive or Branch Manager";
                            //                                echo json_encode($resObj); exit;
                            //                            }
                        } else {
                            $resObj = new \stdClass;
                            $resObj->success = 0;
                            $resObj->error = "Something went wrong at server side.";
                            echo json_encode($resObj);
                            exit;
                        }

                        if (!empty(Yii::$app->request->post('TenantAgreements')['token_amount']) && !empty($_GET['applicant_id'])) {
                            $userId = trim($_GET['applicant_id']);
                            $tokenAmount = (float) Yii::$app->request->post('TenantAgreements')['token_amount'];
                            $walletModel = \app\models\Wallets::find()->where(['user_id' => $userId])->one();
                            if ($walletModel) {
                                $walletModel->amount = (float) $tokenAmount + (float) $walletModel->amount;
                                $walletModel->property_id = (int) Yii::$app->request->post('TenantAgreements')['property_id'];
                                $walletModel->updated_by = Yii::$app->userdata->id;
                                if (!$walletModel->save(false)) {
                                    throw new \Exception('Exception');
                                }
                            } else {
                                $walletModel = new \app\models\Wallets();
                                $walletModel->user_id = $userId;
                                $walletModel->property_id = (int) Yii::$app->request->post('TenantAgreements')['property_id'];
                                $walletModel->amount = (float) $tokenAmount;
                                $walletModel->created_by = Yii::$app->userdata->id;
                                $walletModel->created_date = date('Y-m-d H:i:s');
                                if (!$walletModel->save(false)) {
                                    throw new \Exception('Exception');
                                }
                            }

                            $walletHistoryModel = new \app\models\WalletsHistory();
                            $walletHistoryModel->user_id = $userId;
                            $walletHistoryModel->transaction_type = 1;
                            $walletHistoryModel->property_id = (int) Yii::$app->request->post('TenantAgreements')['property_id'];

                            if (Yii::$app->request->post('for_bed_room') && Yii::$app->request->post('rooms_id')) {
                                if (Yii::$app->request->post('for_bed_room') == 1) {
                                    $walletHistoryModel->child_id = Yii::$app->request->post('rooms_id');
                                } else if (Yii::$app->request->post('for_bed_room') == 2) {
                                    $walletHistoryModel->child_id = Yii::$app->request->post('beds_id');
                                }
                            }

                            $walletHistoryModel->amount = (float) $tokenAmount;
                            $walletHistoryModel->operation_type = 0;
                            $walletHistoryModel->created_by = Yii::$app->userdata->id;
                            $walletHistoryModel->created_date = date('Y-m-d H:i:s');
                            if (!$walletHistoryModel->save(false)) {
                                throw new \Exception('Exception');
                            }

                            unset($userId);
                            unset($tokenAmount);
                            unset($walletModel);
                            unset($walletHistoryModel);
                        }

                        if ($_FILES['TenantAgreements']['size']['agreement_url'] > '0') {
                            $url = 'uploads/' . time() . '_' . $_FILES['TenantAgreements']['name']['agreement_url'];
                            if (move_uploaded_file($_FILES['TenantAgreements']['tmp_name']['agreement_url'], $url)) {
                                $model->agreement_url = $url;
                            }
                        }

                        if (Yii::$app->request->post('for_bed_room') && Yii::$app->request->post('rooms_id')) {
                            if (Yii::$app->request->post('for_bed_room') == 1) {
                                $childListing = \app\models\ChildProperties::findOne(['id' => Yii::$app->request->post('rooms_id'), 'status' => '1']);
                                if ($childListing) {
                                    $leaseType = 2;
                                    $childListing->status = 0;
                                    if (!$childListing->save(false)) {
                                        throw new \Exception('Exception');
                                    }
                                    $model->room_id = Yii::$app->request->post('rooms_id');
                                }
                            } else if (Yii::$app->request->post('for_bed_room') == 2) {
                                $childListing = \app\models\ChildProperties::findOne(['id' => Yii::$app->request->post('beds_id'), 'status' => '1']);
                                if ($childListing) {
                                    $leaseType = 2;
                                    $childListing->status = 0;
                                    if (!$childListing->save(false)) {
                                        throw new \Exception('Exception');
                                    }
                                    $model->room_id = Yii::$app->request->post('rooms_id');
                                    $model->bed_id = Yii::$app->request->post('beds_id');
                                }
                            }

                            Yii::$app->userdata->checkCoProListingStatus($model->parent_id);
                        }

                        if (Yii::$app->request->post('for_bed_room') && Yii::$app->request->post('rooms_id')) {
                            if (Yii::$app->request->post('for_bed_room') == 1) {
                                $model->room_id = Yii::$app->request->post('rooms_id');
                            } else if (Yii::$app->request->post('for_bed_room') == 2) {
                                $model->room_id = Yii::$app->request->post('rooms_id');
                                $model->bed_id = Yii::$app->request->post('beds_id');
                            }
                        }

                        $model->created_by = Yii::$app->user->id;
                        $model->created_date = date('Y-m-d H:i:s');
                        $model->updated_date = date('Y-m-d H:i:s');
                        $model->late_penalty_percent = Yii::$app->request->post('TenantAgreements')['late_penalty_percent'];
                        $model->min_penalty = Yii::$app->request->post('TenantAgreements')['min_penalty'];
                        // UNCOMMENT THIS LINE [DONE BY SHOBHIT]
                        if (!$model->save(false)) {
                            throw new \Exception('Exception');
                        }

                        $checkListed = \app\models\ChildProperties::findAll(['main' => $model->parent_id, 'status' => '1']);

                        if (count($checkListed) == '0') {
                            $ParentListing = \app\models\PropertyListing::findOne(['property_id' => $model->parent_id]);
                            $ParentListing->status = '0';
                            if (!$ParentListing->save(false)) {
                                throw new \Exception('Exception');
                            }
                        }

                        $modelFav = \app\models\FavouriteProperties::find()->where(
                            [
                                'property_id' => $model->parent_id,
                                'child_properties' => $model->property_id
                            ]
                        )->all();
                        $modelFav1 = \app\models\FavouriteProperties::findAll([
                            'applicant_id' => Yii::$app->userdata->getUserEmailById($model->tenant_id)
                        ]);
                        foreach ($modelFav1 as $Key => $value) {
                            $value->delete(FALSE);
                        }

                        $walletAmount = \app\models\Wallets::findOne(['user_id' => $model->id]);



                        $modelUser = \app\models\Users::findOne(['id' => $model->tenant_id]);
                        $modelUser->user_type = 3;
                        // UNCOMMENT THIS LINE [DONE BY SHOBHIT]
                        if (!$modelUser->save(false)) {
                            throw new \Exception('Exception');
                        }

                        //   $leads_Applicant->delete(false);

                        $leads_Applicant->branch_code = $valProModel->branch_code;
                        $leads_Applicant->lead_city = $valProModel->city;
                        $leads_Applicant->lead_state = $valProModel->state;
                        // UNCOMMENT THIS LINE [DONE BY SHOBHIT]
                        if (!$leads_Applicant->save(false)) {
                            throw new \Exception('Exception');
                        }

                        $modelUserProfile = \app\models\ApplicantProfile::findOne(['applicant_id' => $model->tenant_id]);
                        $modelUserProfile->sales_id = $valProModel->sales_id;
                        $modelUserProfile->operation_id = $valProModel->operations_id;
                        $modelUserProfile->branch_code = $valProModel->branch_code;
                        $modelUserProfile->state = $valProModel->state;
                        // UNCOMMENT THIS LINE [DONE BY SHOBHIT]
                        if (!$modelUserProfile->save(false)) {
                            throw new \Exception('Exception');
                        }

                        /*                         * ******************************** Payment Calculations ************************************** */
                        $fixedDueDate = $cutOffDate;
                        $rentProperty = [];
                        $propertyAgreements = \app\models\PropertyAgreements::find()->where(['property_id' => $model->parent_id])->one();

                        if (!$propertyAgreements) {
                            Yii::$app->getSession()->setFlash(
                                'success',
                                'Something went wrong, no agreement found for this property.'
                            );
                            throw new \Exception('Exception');
                        }

                        if (($propertyAgreements->agreement_type == 2) && (strtotime($model->lease_start_date) < $propertyAgreements->rent_start_date)) {
                            $propertyAgreements->rent_start_date = $model->lease_start_date;
                        }

                        if ($leaseType == 2) {
                            $model->agreement_type = 3;
                        } else {
                            $model->agreement_type = $propertyAgreements->agreement_type;
                        }

                        // UNCOMMENT THIS LINE [DONE BY SHOBHIT]
                        if (!$model->save(false)) {
                            throw new \Exception('Exception');
                        }

                        $propertyDetail = \app\models\OwnerProfile::findOne(['owner_id' => $propertyAgreements->owner_id]);
                        $agreement_type = $propertyAgreements->agreement_type;

                        $leaseBeginDate = (new \DateTime($model->lease_start_date))->modify('first day of this month');
                        $leaseEndDate = (new \DateTime($model->lease_end_date))->modify('first day of next month');
                        $leaseInterval = \DateInterval::createFromDateString('1 month');
                        $leasePeriod = new \DatePeriod($leaseBeginDate, $leaseInterval, $leaseEndDate);
                        $months = iterator_count($leasePeriod);



                        $startDateObject = date_create($model->lease_start_date);
                        $endDateObject = date_create($model->lease_end_date);
                        $startDateLease = $startDateObject->format('Y') . "-" . (int) $startDateObject->format('m') . "-" . (int) $startDateObject->format('d');
                        $rentArray = array();
                        $maintenanceArray = array();
                        $rentArray1 = array();
                        $maintenanceArray1 = array();

                        for ($i = 0; $i < $months; $i++) {
                            if (/* $agreement_type == 2 */false) { // Changes done on 15-feb-2017
                                $rentProperty[] = $propertyAgreements->rent;
                                $maintenanceProperty[] = $propertyAgreements->manteinance;
                            } else {

                                $rentProperty[] = $model->rent;
                                $maintenanceProperty[] = $model->maintainance;
                            }
                            $rentArray[] = $model->rent;
                            $maintenanceArray[] = $model->maintainance;
                            $rentArray1[] = $model->rent;
                            $maintenanceArray1[] = $model->maintainance;
                        }

                        // UNCOMMENT THIS LINE [DONE BY SHOBHIT]
                        if (!$propertyAgreements->save(false)) {
                            throw new \Exception('Exception');
                        }

                        $countr = count($rentArray);
                        $countm = count($maintenanceArray);
                        $days = cal_days_in_month(CAL_GREGORIAN, $startDateObject->format('m'), $startDateObject->format('Y'));

                        $rentArray[0] = round(((int) $model->rent / (int) $days) * ((int) $days - (int) $startDateObject->format('d') + 1), 2);
                        $maintenanceArray[0] = round(((int) $model->maintainance / (int) $days) * ((int) $days - (int) $startDateObject->format('d') + 1), 2);
                        $rentArray1[0] = round(((int) $model->rent / (int) $days) * ((int) $days - (int) $startDateObject->format('d') + 1), 2);
                        $maintenanceArray1[0] = round(((int) $model->maintainance / (int) $days) * ((int) $days - (int) $startDateObject->format('d') + 1), 2);
                        $rentProperty[0] = round(((int) $rentProperty[0] / (int) $days) * ((int) $days - (int) $startDateObject->format('d')), 2);
                        $maintenanceProperty[0] = round(((int) $maintenanceProperty[0] / (int) $days) * ((int) $days - (int) $startDateObject->format('d')), 2);

                        if ($months > 1) {
                            $enddays = cal_days_in_month(CAL_GREGORIAN, $endDateObject->format('m'), $endDateObject->format('Y'));
                            $rentArray[count($rentArray) - 1] = round(((int) $model->rent / (int) $enddays) * ((int) $endDateObject->format('d')), 2);
                            $maintenanceArray[count($maintenanceArray) - 1] = round(((int) $model->maintainance / (int) $enddays) * ((int) $endDateObject->format('d')), 2);
                            $rentArray1[count($rentArray) - 1] = round(((int) $model->rent / (int) $enddays) * ((int) $endDateObject->format('d')));
                            $maintenanceArray1[count($maintenanceArray) - 1] = round(((int) $model->maintainance / (int) $enddays) * ((int) $endDateObject->format('d')), 2);
                            $rentProperty[count($maintenanceArray) - 1] = round(((int) $rentProperty[count($maintenanceArray) - 1] / (int) $enddays) * ((int) $endDateObject->format('d')), 2);
                            $maintenanceProperty[count($maintenanceArray) - 1] = round(((int) $maintenanceProperty[count($maintenanceArray) - 1] / (int) $enddays) * ((int) $endDateObject->format('d')), 2);
                        }

                        if ($rentArray[0] == '0') {
                            unset($maintenanceArray[0]);
                            unset($rentArray[0]);
                            unset($rentProperty[0]);
                            unset($maintenanceProperty[0]);
                        }
                        if ($rentArray[$countr - 1] == '0') {
                            unset($maintenanceArray[$countm - 1]);
                            unset($rentArray[$countr - 1]);
                            unset($rentProperty[$countm - 1]);
                            unset($maintenanceProperty[$countm - 1]);
                        }

                        $rentArray = array_values($rentArray);
                        $maintenanceArray = array_values($maintenanceArray);
                        $rentProperty = array_values($rentProperty);
                        $maintenanceProperty = array_values($maintenanceProperty);

                        $is = 0;
                        $rentArrayCount = count($rentArray);

                        foreach ($rentArray as $key => $value) {
                            $tenantPayments = new \app\models\TenantPayments;
                            if (Yii::$app->request->post('for_bed_room') == 1) {
                                $tenantPayments->room_id = Yii::$app->request->post('rooms_id');
                            } else if (Yii::$app->request->post('for_bed_room') == 2) {
                                $tenantPayments->room_id = Yii::$app->request->post('rooms_id');
                                $tenantPayments->bed_id = Yii::$app->request->post('beds_id');
                            }
                            $tenantPayments->agreement_id = $model->id;
                            $tenantPayments->tenant_id = $modelUserProfile->applicant_id;

                            $walletAdj = \app\models\Wallets::find()->where([
                                'user_id' => $modelUserProfile->applicant_id
                            ])->one();

                            $walletCalcFlag = false;
                            $tempWalletAmount = $walletAdj->amount;
                            $tempTpOriginalAmount = $value;
                            $tempTpMaintenance = $maintenanceArray[$key];
                            $tempTpTotalAmount = (float) ($value + $maintenanceArray[$key]);

                            if ($walletAdj->amount == 0) {
                                $walletCalcFlag = false;
                                $tenantPayments->original_amount = $value;
                                $tenantPayments->maintenance = $maintenanceArray[$key];
                                $tenantPayments->total_amount = (float) ($value + $maintenanceArray[$key]);
                            } else if ($walletAdj->amount > 0) {
                                if ($walletAdj->amount < ($value + $maintenanceArray[$key])) {
                                    $walletCalcFlag = true;
                                    $tenantPayments2 = new \app\models\TenantPayments;
                                    if ($walletAdj->amount >= $maintenanceArray[$key]) {
                                        $walletAdj->amount = (float) ($walletAdj->amount - $maintenanceArray[$key]);
                                        $adjustedWalletAmount = (float) $adjustedWalletAmount + $maintenanceArray[$key];
                                        $tenantPayments2->maintenance = $maintenanceArray[$key];
                                        $tenantPayments->maintenance = 0;
                                        if ($walletAdj->amount >= $value) {
                                            $walletAdj->amount = (float) ($walletAdj->amount - $value);
                                            $tenantPayments2->original_amount = $value;
                                            $tenantPayments->original_amount = 0;
                                            $adjustedWalletAmount = (float) $adjustedWalletAmount + $value;
                                        } else {
                                            $tenantPayments2->original_amount = (float) $walletAdj->amount;
                                            $tenantPayments->original_amount = (float) ($value - $walletAdj->amount);
                                            $adjustedWalletAmount = (float) $adjustedWalletAmount + $walletAdj->amount;
                                            $walletAdj->amount = 0;
                                            $walletCalcFlag = false;
                                        }
                                        $tenantPayments2->total_amount = (float) ($tenantPayments2->original_amount + $tenantPayments2->maintenance);
                                        $tenantPayments->total_amount = (float) ($tenantPayments->original_amount + $tenantPayments->maintenance);
                                    } else if ($walletAdj->amount < $maintenanceArray[$key]) {
                                        $adjustedWalletAmount = (float) $adjustedWalletAmount + $walletAdj->amount;
                                        $tenantPayments2->maintenance = (float) $walletAdj->amount;
                                        $tenantPayments->maintenance = (float) ($maintenanceArray[$key] - $walletAdj->amount);
                                        $walletAdj->amount = (float) 0;
                                        $tenantPayments2->original_amount = (float) 0;
                                        $tenantPayments->original_amount = (float) $value;
                                        $tenantPayments2->total_amount = (float) ($tenantPayments2->original_amount + $tenantPayments2->maintenance);
                                        $tenantPayments->total_amount = (float) ($tenantPayments->original_amount + $tenantPayments->maintenance);
                                    }

                                    if (Yii::$app->request->post('for_bed_room') == 1) {
                                        $tenantPayments2->room_id = Yii::$app->request->post('rooms_id');
                                    } else if (Yii::$app->request->post('for_bed_room') == 2) {
                                        $tenantPayments2->room_id = Yii::$app->request->post('rooms_id');
                                        $tenantPayments2->bed_id = Yii::$app->request->post('beds_id');
                                    }
                                    $tenantPayments2->agreement_id = $model->id;
                                    $tenantPayments2->tenant_id = $modelUserProfile->applicant_id;

                                    $tenantPayments2->payment_mode = 3;
                                    $tenantPayments2->payment_date = date('Y-m-d H:i:s');
                                    $tenantPayments2->payment_status = 1;
                                    $tenantPayments2->property_id = $model->property_id;
                                    $tenantPayments2->parent_id = $model->parent_id;
                                    $tenantPayments2->tenant_amount = $rentArray1[$key];
                                    //$tenantPayments2->owner_amount = $rentProperty[$key];
                                    $tenantPayments2->owner_amount = $tenantPayments2->original_amount;
                                    $tenantPayments2->amount_paid = $adjustedWalletAmount;
                                    $tenantPayments2->payment_type = '2';
                                    $tenantPayments2->created_by = Yii::$app->user->id;

                                    if (Yii::$app->request->post('for_bed_room') == 1) {
                                        $tenantPayments2->payment_des = 'Rent for ' . Yii::$app->userdata->getPropertyNameById($model->parent_id) . ' Room For ' . Date('m-Y', strtotime($startDateLease));
                                    } else if (Yii::$app->request->post('for_bed_room') == 2) {
                                        $tenantPayments2->payment_des = 'Rent for ' . Yii::$app->userdata->getPropertyNameById($model->parent_id) . ' Bed For ' . Date('m-Y', strtotime($startDateLease));
                                    } else {
                                        $tenantPayments2->payment_des = 'Rent ' . Yii::$app->userdata->getPropertyNameById($model->parent_id) . ' For ' . Date('m-Y', strtotime($startDateLease));
                                    }

                                    $wallAdjDayRent = (float) 0;

                                    if ($is == 0) {
                                        $tenantPayments2->due_date = Date('Y-m-d', (strtotime($model->lease_start_date) + (60 * 60 * 24 * 5)));
                                        $numDaysInMonth = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($model->lease_start_date)), date('Y', strtotime($model->lease_start_date)));
                                        $wallAdjDayRent = (float) ($tenantPayments->total_amount / $numDaysInMonth);
                                        //$tenantPayments->days_of_rent = ($numDaysInMonth - date('d', strtotime($model->lease_start_date))) + 1;
                                        $tenantPayments->days_of_rent = (float) (($adjustedWalletAmount / $wallAdjDayRent));
                                    } else if (($is + 1) == $months) {
                                        $tenantPayments2->due_date = Date('Y-m-d', strtotime(Date('Y-m', strtotime($model->lease_end_date)) . '-' . $fixedDueDate));
                                    } else {
                                        $tenantPayments2->due_date = Date('Y-m-d', strtotime(Date('Y-m', strtotime($startDateLease)) . '-' . $fixedDueDate));
                                    }

                                    $tenantPayments2->month = date('m-Y', strtotime($tenantPayments2->due_date));
                                    $wallAdjDayRent = (float) 0;

                                    if ($is == 0) {
                                        $numDaysInMonth = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($model->lease_start_date)), date('Y', strtotime($model->lease_start_date)));
                                        $wallAdjDayRent = (float) ($tenantPayments2->total_amount / $numDaysInMonth);
                                        $tenantPayments2->days_of_rent = (float) ((($numDaysInMonth - date('d', strtotime($model->lease_start_date))) + 1) - $tenantPayments->days_of_rent);
                                    } else if ($is == ($rentArrayCount - 1)) {
                                        $numDaysInMonth = date('d', strtotime($model->lease_end_date));
                                        $tenantPayments2->days_of_rent = $numDaysInMonth;
                                    } else {
                                        $numDaysInMonth = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($startDateLease)), date('Y', strtotime($startDateLease)));
                                        $wallAdjDayRent = (float) (($value + $maintenanceArray[$key]) / $numDaysInMonth);
                                        $tenantPayments2->days_of_rent = (float) ($tenantPayments2->total_amount / $wallAdjDayRent);
                                    }

                                    // UNCOMMENT THIS LINE [DONE BY SHOBHIT]
                                    if (!$tenantPayments2->save(false)) {
                                        throw new \Exception('Exception');
                                    }
                                } else if ($walletAdj->amount > ($value + $maintenanceArray[$key])) {
                                    $adjustedWalletAmount = (float) ($adjustedWalletAmount + $value + $maintenanceArray[$key]);
                                    $walletAdj->amount = (float) ($walletAdj->amount - $maintenanceArray[$key]);
                                    $tenantPayments->maintenance = $maintenanceArray[$key];
                                    $walletAdj->amount = (float) ($walletAdj->amount - $value);
                                    $tenantPayments->original_amount = $value;
                                    $tenantPayments->payment_mode = 3;
                                    $tenantPayments->payment_date = date('Y-m-d H:i:s');
                                    $tenantPayments->total_amount = (float) ($tenantPayments->original_amount + $tenantPayments->maintenance);
                                    $tenantPayments->payment_status = 1;
                                }

                                if (!$walletAdj->save(false)) {
                                    throw new \Exception('Exception');
                                }

                                $walletHistoryAdj = \app\models\WalletsHistory::find()->where([
                                    'user_id' => $modelUserProfile->applicant_id,
                                    //'property_id' => $model->property_id,
                                    'transaction_type' => 1,
                                ])->all();

                                if ($walletHistoryAdj) {
                                    foreach ($walletHistoryAdj as $walletHistoryAdjRow) {
                                        $walletHistoryAdjRow->transaction_type = 3;
                                        if (!$walletHistoryAdjRow->update(false)) {
                                            throw new \Exception('Exception');
                                        }
                                    }
                                }
                            }

                            $tenantPayments->property_id = $model->property_id;
                            $tenantPayments->parent_id = $model->parent_id;
                            $tenantPayments->tenant_amount = $rentArray1[$key];
                            //$tenantPayments->owner_amount = $rentProperty[$key];
                            $tenantPayments->owner_amount = $tenantPayments->original_amount;
                            $tenantPayments->payment_type = '2';
                            $tenantPayments->created_by = Yii::$app->user->id;

                            if (Yii::$app->request->post('for_bed_room') == 1) {
                                $tenantPayments->payment_des = 'Rent for ' . Yii::$app->userdata->getPropertyNameById($model->parent_id) . ' Room For ' . Date('m-Y', strtotime($startDateLease));
                            } else if (Yii::$app->request->post('for_bed_room') == 2) {
                                $tenantPayments->payment_des = 'Rent for ' . Yii::$app->userdata->getPropertyNameById($model->parent_id) . ' Bed For ' . Date('m-Y', strtotime($startDateLease));
                            } else {
                                $tenantPayments->payment_des = 'Rent ' . Yii::$app->userdata->getPropertyNameById($model->parent_id) . ' For ' . Date('m-Y', strtotime($startDateLease));
                            }

                            if ($is == 0) {
                                $tenantPayments->due_date = Date('Y-m-d', (strtotime($model->lease_start_date) + (60 * 60 * 24 * 5)));
                            } else if (($is + 1) == $months) {
                                $tenantPayments->due_date = Date('Y-m-d', strtotime(Date('Y-m', strtotime($model->lease_end_date)) . '-' . $fixedDueDate));
                            } else {
                                $tenantPayments->due_date = Date('Y-m-d', strtotime(Date('Y-m', strtotime($startDateLease)) . '-' . $fixedDueDate));
                            }

                            $tenantPayments->month = date('m-Y', strtotime($tenantPayments->due_date));

                            if (!$walletCalcFlag) {
                                if ($is == 0) {
                                    $numDaysInMonth = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($model->lease_start_date)), date('Y', strtotime($model->lease_start_date)));
                                    $tenantPayments->days_of_rent = ($numDaysInMonth - date('d', strtotime($model->lease_start_date))) + 1;
                                } else if ($is == ($rentArrayCount - 1)) {
                                    $numDaysInMonth = date('d', strtotime($model->lease_end_date));
                                    $tenantPayments->days_of_rent = $numDaysInMonth;
                                } else {
                                    $numDaysInMonth = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($startDateLease)), date('Y', strtotime($startDateLease)));
                                    $wallAdjDayRent = (float) (($value + $maintenanceArray[$key]) / $numDaysInMonth);
                                    $tenantPayments->days_of_rent = (float) ($tenantPayments->total_amount / $wallAdjDayRent);
                                }
                            }

                            // UNCOMMENT THIS LINE [DONE BY SHOBHIT]
                            if (!$tenantPayments->save(false)) {
                                throw new \Exception('Exception');
                            }

                            $startDateLease = Date('Y-m-d', strtotime('+1 month', strtotime($startDateLease)));
                            $is++;
                        }
                        /*                         * ********************************************************************************************* */


                        $modelTenantProfile = \app\models\TenantProfile::findOne(['tenant_id' => $modelUserProfile->applicant_id]);
                        if (count($modelTenantProfile) == 0) {
                            $modelTenantProfile = new \app\models\TenantProfile;
                            $modelTenantProfile->tenant_id = $modelUserProfile->applicant_id;
                            $modelTenantProfile->emer_contact = $modelUserProfile->emergency_contact_email;
                            $modelTenantProfile->emer_contact_indentity = $modelUserProfile->emer_contact_indentity;
                            $modelTenantProfile->emer_contact_address = $modelUserProfile->emer_contact_address;
                            $modelTenantProfile->emergency_contact_name = $modelUserProfile->emergency_contact_name;
                            $modelTenantProfile->emergency_contact_number = $modelUserProfile->emergency_contact_number;
                            $modelTenantProfile->status = $modelUserProfile->status;
                            $modelTenantProfile->sales_id = $valProModel->sales_id;
                            $modelTenantProfile->operation_id = $valProModel->operations_id;
                            $modelTenantProfile->branch_code = $valProModel->branch_code;
                            $modelTenantProfile->state = $valProModel->state;
                            $modelTenantProfile->address_line_1 = $modelUserProfile->address_line_1;
                            $modelTenantProfile->address_line_2 = $modelUserProfile->address_line_2;
                            $modelTenantProfile->city_name = $modelUserProfile->city_name;
                            $modelTenantProfile->region = $modelUserProfile->region;
                            $modelTenantProfile->pincode = $modelUserProfile->pincode;
                            $modelTenantProfile->phone = $modelUserProfile->phone;
                            $modelTenantProfile->proof_type = $modelUserProfile->proof_type;
                            $modelTenantProfile->proof_document_url = $modelUserProfile->proof_document_url;
                            $modelTenantProfile->account_holder_name = $modelUserProfile->account_holder_name;
                            $modelTenantProfile->bank_name = $modelUserProfile->bank_name;
                            $modelTenantProfile->bank_branchname = $modelUserProfile->bank_branchname;
                            $modelTenantProfile->bank_ifcs = $modelUserProfile->bank_ifcs;
                            $modelTenantProfile->pan_number = $modelUserProfile->pan_number;
                            $modelTenantProfile->cancelled_check = $modelUserProfile->cancelled_check;
                            $modelTenantProfile->employer_name = $modelUserProfile->employer_name;
                            $modelTenantProfile->employee_id = $modelUserProfile->employee_id;
                            $modelTenantProfile->employment_start_date = $modelUserProfile->employment_start_date;
                            $modelTenantProfile->employment_email = $modelUserProfile->employment_email;
                            $modelTenantProfile->employment_proof_url = $modelUserProfile->employmnet_proof_url;
                            $modelTenantProfile->employment_proof_type = $modelUserProfile->employmnet_proof_type;
                            $modelTenantProfile->account_number = $modelUserProfile->bank_account_number;
                            $modelTenantProfile->profile_image = $modelUserProfile->profile_image;
                            // UNCOMMENT THIS LINE [DONE BY SHOBHIT]
                            if (!$modelTenantProfile->save(false)) {
                                throw new \Exception('Exception');
                            }
                        }
                        // $operationPerson=Yii::$app->userdata->getOwnerIdByProperty($model->parent_id);
                        $operationDetail = Yii::$app->userdata->getOperationsDetailByUserId($modelUserProfile->applicant_id);
                        $subject = 'Congratulations:  Your new home is ready to move in on ' . date('d-M-Y', strtotime($model->lease_start_date));
                        if ($adjustedWalletAmount > 0) {
                            $msg = "Hello $modelUser->full_name<br/><br/>Your leave and license agreement has been setup and you are all "
                                . "set to move in to your new home on " . date('d-M-Y', strtotime($model->lease_start_date)) . ".<br/>"
                                . "Key terms of your agreement:<br/><br/>Rent: &#x20b9; $model->rent<br/>"
                                . "Maintenance: &#x20b9; $model->maintainance<br/>Deposit: &#x20b9; $model->deposit<br/><br/>"
                                . "The token amount of &#x20b9; " . $adjustedWalletAmount . " has been adjusted against the rent. "
                                . "A copy of the agreement has been attached for your quick reference which has all terms and conditions applicable.<br /><br />"
                                . "In case of any issues/concerns, you may raise a service request through Easyleases portal or directly contact "
                                . "$operationDetail[name] at $operationDetail[email] or $operationDetail[phone].  <br/><br />With Regards,<br />EasyLeases Team<br/>"
                                . "<Attachment: Tenant Agreement>";
                        } else {
                            $msg = "Hello $modelUser->full_name<br/><br/>Your leave and license agreement has been setup and you are all "
                                . "set to move in to your new home on " . date('d-M-Y', strtotime($model->lease_start_date)) . ".<br/>"
                                . "Key terms of your agreement:<br/><br/>Rent: &#x20b9; $model->rent<br/>"
                                . "Maintenance: &#x20b9; $model->maintainance<br/>Deposit: &#x20b9; $model->deposit<br/><br/>"
                                . "A copy of the agreement has been attached for your quick reference which has all terms and conditions applicable.<br /><br />"
                                . "In case of any issues/concerns, you may raise a service request through Easyleases portal or directly contact "
                                . "$operationDetail[name] at $operationDetail[email] or $operationDetail[phone].  <br/><br />With Regards,<br />EasyLeases Team<br/>"
                                . "<Attachment: Tenant Agreement>";
                        }

                        //$modelUserProfile->delete();
                        if (count($modelFav) != 0) {
                            foreach ($modelFav as $fav) {
                                // UNCOMMENT THIS LINE [DONE BY SHOBHIT]
                                $fav->delete(FALSE);
                            }
                        }

                        Yii::$app->getSession()->setFlash(
                            'success',
                            'Agreement created successfully'
                        );

                        $transaction->commit();

                        // UNCOMMENT THIS LINE [DONE BY SHOBHIT]
                        @$email = Yii::$app->userdata->doMailWithAttachment(strtolower(trim($modelUser->login_id)), $subject, $msg, $model->agreement_url);
                        $tenantPhone = Yii::$app->userdata->getPhoneNumberById($modelUserProfile->applicant_id, 3);
                        if (!empty($tenantPhone)) {
                            $txtMess = ''
                                . 'You are all set to move in to your new home on ' . date('d-M-Y', strtotime($model->lease_start_date)) . '. '
                                . 'Please check your registered email for key terms of your agreement.';
                            Yii::$app->userdata->sendSms([$tenantPhone], $txtMess);
                        }

                        $property_name = Yii::$app->userdata->getPropertyNameById($model->parent_id);
                        $owner_name = Yii::$app->userdata->getFullNameById(Yii::$app->userdata->getOwnerIdByProperty($model->parent_id));
                        $to = Yii::$app->userdata->getLoginIdById(Yii::$app->userdata->getOwnerIdByProperty($model->parent_id));
                        $subject1 = "Congratulations, your property $property_name has now been let out";
                        $msg1 = "Hello $owner_name<br/><br/>Congratulations, your property $property_name has been let out to $modelUser->full_name.<br/><br/>The tenant will be moving in on $model->lease_start_date<br/><br/>With Regards,<br/>EasyLeases Team<br/><img src='" . Url::home(true) . "images/property_logo.png' alt='' width='150px' height='40px'>";

                        // UNCOMMENT THIS LINE [DONE BY SHOBHIT]
                        @Yii::$app->userdata->doMail($to, $subject1, $msg1);
                        $ownerPhone = Yii::$app->userdata->getPhoneNumberById(Yii::$app->userdata->getOwnerIdByProperty($model->parent_id), 4);
                        if (!empty($ownerPhone)) {
                            $txtMess = ''
                                . 'Congratulations your property ' . $property_name . ' '
                                . 'has been let out to ' . $modelUser->full_name . ' starting from ' . $model->lease_start_date . '';
                            Yii::$app->userdata->sendSms([$ownerPhone], $txtMess);
                        }

                        $resObj = new \stdClass;
                        $resObj->success = 1;
                        $resObj->error = "";
                        echo json_encode($resObj);
                        exit;
                    } catch (\Exception $e) {
                        $transaction->rollBack();
                        $errorMsg = 'Error on line ' . $e->getLine() . ' in ' . $e->getFile() . ': <b>' . $e->getMessage() . '</b>';
                        Yii::$app->getSession()->setFlash(
                            'success',
                            $errorMsg
                        );
                        return $this->renderAjax('_formlease', [
                            'model' => $model
                        ]);
                    }
                }
            } else {
                $errorMsg = 'Please Fill all the mandatory fields for applicant before setting up the lease';
                //echo $errorMsg;die;
                Yii::$app->getSession()->setFlash(
                    'success',
                    $errorMsg
                );
                return $this->redirect(['applicantsdetails?id=' . base64_encode($leads_Applicant->id)]);
            }
        } else {

            $propertyModel1 = \app\models\Properties::findOne(['id' => Yii::$app->request->get('parent_id'), 'property_type' => 4]);
            $propertyModel2 = \app\models\Properties::findOne(['id' => Yii::$app->request->get('parent_id'), 'property_type' => 5]);
            $listing = \app\models\ChildPropertiesListing::findAll(['main' => Yii::$app->request->get('parent_id')]);
            if ($propertyModel1 || $propertyModel2) {
                return $this->renderAjax('_formlease_coliving', [
                    'model' => $model,
                    'propertyModel' => ($propertyModel1) ? $propertyModel1 : $propertyModel2,
                    'listing' => $listing
                ]);
            } else {
                return $this->renderAjax('_formlease', [
                    'model' => $model
                ]);
            }
        }
    }

    public function actionDaysremainingforpass()
    {
        $ids = Yii::$app->user->id;
        $modelUser = \app\models\Users::find()->where(['id' => $ids])->one();
        $passUpDate = $modelUser->pass_up_date;
        $datetime1 = new \DateTime($passUpDate);
        $today_date = new \DateTime(date('Y-m-d H:i:s'));
        $daysInterval = $datetime1->diff($today_date);
        echo 90 - $daysInterval->days;
    }

    public function actionRuncustomsql()
    {
        if (!empty($_GET['code']) && $_GET['code'] == 'sks') {
            $tenantAgreeModel = \app\models\TenantAgreements::find()->all();
            $fixFlag = 0;
            foreach ($tenantAgreeModel as $row) {
                $tenantId = $row->tenant_id;
                $propertyId = $row->property_id;
                $propertyModel = \app\models\Properties::find()->where(['id' => $propertyId])->one();
                $tenantProModel = \app\models\TenantProfile::find()->where(['tenant_id' => $tenantId])->one();
                $appProModel = \app\models\ApplicantProfile::find()->where(['applicant_id' => $tenantId])->one();
                $leadTenantModel = \app\models\LeadsTenant::find()->where(['email_id' => Yii::$app->userdata->getEmailById($tenantId)])->one();

                if ($propertyModel && $tenantProModel && $appProModel && $leadTenantModel) {
                    // For Tenant Profile
                    $tenantProModel->sales_id = $propertyModel->sales_id;
                    $tenantProModel->operation_id = $propertyModel->operations_id;
                    $tenantProModel->branch_code = $propertyModel->branch_code;
                    $tenantProModel->state = $propertyModel->state;
                    $tenantProModel->save(false);

                    // For Applicant Profile
                    $appProModel->sales_id = $propertyModel->sales_id;
                    $appProModel->operation_id = $propertyModel->operations_id;
                    $appProModel->branch_code = $propertyModel->branch_code;
                    $appProModel->state = $propertyModel->state;
                    $appProModel->save(false);

                    // For Leads Tenant
                    $leadTenantModel->branch_code = $propertyModel->branch_code;
                    $leadTenantModel->lead_city = $propertyModel->city;
                    $leadTenantModel->lead_state = $propertyModel->state;
                    $leadTenantModel->save(false);
                    $fixFlag++;
                } else {
                    echo "Failed for Property ID - " . $propertyId . "<br />";
                    echo "Failed for Tenant ID - " . $tenantId . "<br />";
                    echo "<br />";
                    echo "<br />";
                    echo "<br />";
                    echo "<br />";
                    echo "<br />";
                }
            }

            if ($fixFlag > 0) {
                echo 'Done';
            }
        }
    }

    public function actionGetavailablebeds()
    {
        if (Yii::$app->request->post('roomId') && Yii::$app->request->isAjax) {
            extract(Yii::$app->request->post());
            $sql = "SELECT "
                . "cp.id as cp_id, cp.property_code"
                . ", cp.status, cpl.id as cpl_id"
                . ", cpl.rent, cpl.deposit"
                . ", cpl.token_amount "
                . "FROM child_properties cp "
                . "INNER JOIN child_property_listing cpl "
                . "ON cp.id = cpl.child_id "
                . "WHERE "
                //. "cp.status = 1 AND cp.main = ".$mainId." AND cp.parent = ".$roomId;
                . "cp.main = " . $mainId . " AND cp.parent = " . $roomId;
            $res = Yii::$app->db->createCommand($sql)->queryAll();
            if (count($res) > 0) {
                $i = 1;
?>
                <false class="required">
                    <label class="control-label" for="">Select Bed</label>
                    <!--<select onchange="fillValues()" name="beds_id" id="beds_id" class="form-control" aria-required="true" style="width: 60%;">-->
                    <select name="beds_id" id="beds_id" class="form-control" aria-required="true" style="width: 60%;">
                        <option value="">Select Bed</option>
                        <?php foreach ($res as $value) { ?>
                            <option value="<?= $value['cp_id'] ?>"><?= Yii::$app->userdata->getRoomName($value['cp_id']); ?></option>
                        <?php
                            $i++;
                        }
                        ?>
                    </select>
                </false>
            <?php } else { ?>
                <false class="required">
                    <label class="control-label" for="">Select Bed</label>
                    <select class="form-control" aria-required="true" style="width: 60%;">
                        <option value="">No Beds Available</option>
                    </select>
                </false>
<?php
            }
        } else {
            echo 'error';
        }
    }

    public function actionMyprofile()
    {
        $users = \app\models\User::find()->where(['id' => Yii::$app->user->id])->one();
        if (Yii::$app->user->identity->user_type == 7) {
            $models = \app\models\OperationsProfile::find()->where(['operations_id' => Yii::$app->user->id])->one();
        } else {
            $models = \app\models\SalesProfile::find()->where(['sale_id' => Yii::$app->user->id])->one();
        }

        $city = \app\models\Cities::find()->where(['id' => $models->city])->one();
        $state = \app\models\States::find()->where(['id' => $models->state])->one();
        $main_id = Yii::$app->user->id;
        $role = \app\models\Users::find()->select('role')->where(['id' => $main_id])->one();
        if (Yii::$app->user->identity->user_type == 7) {
            switch ($role->role) {
                case 5:
                    $roles = \app\models\OperationsProfile::find()->select('region')->where(['operations_id' => $main_id])->one();
                    $branches = \app\models\Regions::find()->where(['id' => $roles->region])->All();
                    break;
                case 6:
                    $roles = \app\models\OperationsProfile::find()->select('region')->where(['operations_id' => $main_id])->one();
                    $branches = \app\models\Regions::find()->where(['id' => $roles->region])->All();
                    break;
                case 7:
                    $roles = \app\models\OperationsProfile::find()->select('city')->where(['operations_id' => $main_id])->one();
                    $branches = \app\models\Regions::find()->where(['city_id' => $roles->city])->All();
                    break;
                case 8:
                    $roles = \app\models\OperationsProfile::find()->select('state')->where(['operations_id' => $main_id])->one();
                    $branches = \app\models\Regions::find()->where(['state_id' => $roles->state])->All();
                    break;
            }
        } else {
            switch ($role->role) {
                case 1:
                    $roles = \app\models\SalesProfile::find()->select('region')->where(['sale_id' => $main_id])->one();
                    $branches = \app\models\Regions::find()->where(['id' => $roles->region])->All();
                    break;
                case 2:
                    $roles = \app\models\SalesProfile::find()->select('region')->where(['sale_id' => $main_id])->one();
                    $branches = \app\models\Regions::find()->where(['id' => $roles->region])->All();
                    break;
                case 3:
                    $roles = \app\models\SalesProfile::find()->select('city')->where(['sale_id' => $main_id])->one();
                    $branches = \app\models\Regions::find()->where(['city_id' => $roles->city])->All();
                    break;
                case 4:
                    $roles = \app\models\SalesProfile::find()->select('state')->where(['sale_id' => $main_id])->one();
                    $branches = \app\models\Regions::find()->where(['state_id' => $roles->state])->All();
                    break;
            }
        }

        $model = new PasswordForm;
        return $this->render('myprofile', ['users' => $users, 'models' => $models, 'city' => (empty($city->city_name)) ? '' : $city->city_name, 'state' => (empty($state->name)) ? '' : $state->name, 'model' => $model, 'branches' => $branches]);
    }

    //public function actionOwnerleads(){
    // $user = Yii::$app->db->createCommand('select l.*,r.name as ref_name,u.full_name as reffered_name,u2.full_name as sales_name from leads_owner as l left join ref_status as r on l.ref_status=r.id left join users as u on l.reffered_by=u.id left join users as u1 on l.email_id=u1.login_id left join owner_profile as ap on u1.id=ap.owner_id left join  users as u2 on ap.sales_id=u2.id')
    //   ->queryAll();
    // return $this->render('ownerLeads',['data'=>$user,'total'=>count($user)]);
    //     $searchModelOwnerLeads = new LeadsOwner();
    //     $dataProviderOwner = $searchModelOwnerLeads->search(Yii::$app->request->queryParams);
    //     print_r($dataProviderOwner);
    //     return $this->render('owners',[
    //           'searchModelOwnerLeads' => $searchModelOwnerLeads,
    //           'dataProviderOwner' => $dataProviderOwner,
    //     ]);
    // }

    public function actionAdvisorleads()
    {

        $user = Yii::$app->db->createCommand('select l.*,r.name as ref_name,u.full_name as reffered_name,u2.full_name as sales_name from leads_advisor as l left join ref_status as r on l.ref_status=r.id left join users as u on l.reffered_by=u.id left join users as u1 on l.email_id=u1.login_id left join advisor_profile as ap on u1.id=ap.advisor_id left join  users as u2 on ap.sales_id=u2.id')
            ->queryAll();
        // print_r($user);
        return $this->render('advisorLeads', ['data' => $user, 'total' => count($user)]);
    }

    public function actionAplicantleads()
    {

        $user = Yii::$app->db->createCommand('select l.*,r.name as ref_name,u.full_name as reffered_name,u2.full_name as sales_name from leads_tenant as l left join ref_status as r on l.ref_status=r.id left join users as u on l.reffered_by=u.id left join users as u1 on l.email_id=u1.login_id left join applicant_profile as ap on u1.id=ap.applicant_id left join  users as u2 on ap.sales_id=u2.id')
            ->queryAll();
        // print_r($user);
        return $this->render('applicantLeads', ['data' => $user, 'total' => count($user)]);
    }

    public function actionTenants()
    {
        $today = date('Y-m-d');
        $search = "";
        $where = " where (ta.lease_end_date >= '" . $today . "' OR ta.lease_end_date is null) AND u.`status`='1' ";

        $userAssignments = \app\models\Users::find()->where(['id' => Yii::$app->user->id])->one();
        $opsProfileModel = \app\models\OperationsProfile::findOne(['operations_id' => Yii::$app->user->id]);
        $currentRoleCode = $opsProfileModel->role_code;
        $currentRoleValue = $opsProfileModel->role_value;

        $leftJoin = 'left join tenant_profile as tp on ta.tenant_id = tp.tenant_id '
            . 'left join leads_tenant as lt on lt.email_id = u.login_id '
            . 'left join properties as p on ta.parent_id = p.id '
            . 'left join property_types as pt on p.property_type = pt.id '
            . 'left join users as u1 on tp.sales_id = u1.id '
            . 'left join users as u2 on tp.operation_id = u2.id';


        if ($currentRoleValue == 'ELHQ') {
        } else if ($currentRoleCode == 'OPSTMG') {
            $where .= " AND lt.lead_state = '" . $currentRoleValue . "' ";
        } else if ($currentRoleCode == 'OPCTMG') {
            $where .= " AND lt.lead_city = '" . $currentRoleValue . "' ";
        } else if ($currentRoleCode == 'OPBRMG') {
            $where .= " AND tp.branch_code = '" . $currentRoleValue . "' ";
        } else if ($currentRoleCode == 'OPEXE') {
            $branchModel = \app\models\Branches::find()->where(['branch_code' => $currentRoleValue])->one();
            $where .= " AND tp.branch_code = '" . $currentRoleValue . "' ";
            $where .= " AND lt.lead_city = '" . $branchModel->city_code . "' ";
            $where .= " AND tp.operation_id = '" . Yii::$app->user->id . "' ";
        }

        if (isset($_GET['search'])) {
            $search = str_replace(" ", "%%", trim($_GET['search']));
        }

        if ($search != '') {
            $search = "%" . $search . "%";
            $where .= ' and (p.property_name like "' . $search . '" or u.full_name like "' . $search . '" or p.address_line_1 like "' . $search . '" or p.address_line_2 like "' . $search . '" or u1.full_name  like "' . $search . '" or pt.property_type_name  like "' . $search . '")';
        }

        $user = Yii::$app->db->createCommand('select '
            . 'ta.tenant_id, u.full_name, p.property_name, p.address_line_1, p.address_line_2, '
            . 'pt.property_type_name as property_type, pt.id as property_type_id, '
            . 'ta.property_id as rented_prop, ta.rent, ta.maintainance, ta.deposit, '
            . 'ta.lease_start_date, ta.lease_end_date, u1.full_name as sales_name, '
            . 'u2.full_name as operations_name '
            . 'from tenant_agreements as ta left join users as u on ta.tenant_id = u.id '
            . '' . $leftJoin . $where . ' '
            . 'ORDER BY ta.id DESC ')->queryAll();

        return $this->render('tenants', ['data' => $user, 'total' => count($user)]);
    }

    public function actionPasttenants()
    {
        $today = date('Y-m-d');
        $search = "";
        $where = " where ta.lease_end_date < '" . $today . "' AND u.`status`='1' ";

        $userAssignments = \app\models\Users::find()->where(['id' => Yii::$app->user->id])->one();
        $opsProfileModel = \app\models\OperationsProfile::findOne(['operations_id' => Yii::$app->user->id]);
        $currentRoleCode = $opsProfileModel->role_code;
        $currentRoleValue = $opsProfileModel->role_value;

        $leftJoin = 'left join tenant_profile as tp on ta.tenant_id = tp.tenant_id '
            . 'left join leads_tenant as lt on lt.email_id = u.login_id '
            . 'left join properties as p on ta.parent_id = p.id '
            . 'left join property_types as pt on p.property_type = pt.id '
            . 'left join users as u1 on tp.sales_id = u1.id '
            . 'left join users as u2 on tp.operation_id = u2.id';

        if ($currentRoleValue == 'ELHQ') {
        } else if ($currentRoleCode == 'OPSTMG') {
            $where .= " AND lt.lead_state = '" . $currentRoleValue . "' ";
        } else if ($currentRoleCode == 'OPCTMG') {
            $where .= " AND lt.lead_city = '" . $currentRoleValue . "' ";
        } else if ($currentRoleCode == 'OPBRMG') {
            $where .= " AND tp.branch_code = '" . $currentRoleValue . "' ";
        } else if ($currentRoleCode == 'OPEXE') {
            $branchModel = \app\models\Branches::find()->where(['branch_code' => $currentRoleValue])->one();
            $where .= " AND tp.branch_code = '" . $currentRoleValue . "' ";
            $where .= " AND lt.lead_city = '" . $branchModel->city_code . "' ";
            $where .= " AND tp.operation_id = '" . Yii::$app->user->id . "' ";
        }

        if (isset($_GET['search'])) {
            $search = str_replace(" ", "%%", trim($_GET['search']));
        }

        if ($search != '') {
            $search = "%" . $search . "%";
            $where .= ' and (p.property_name like "' . $search . '" or u.full_name like "' . $search . '" or p.address_line_1 like "' . $search . '" or p.address_line_2 like "' . $search . '" or u1.full_name  like "' . $search . '" or pt.property_type_name  like "' . $search . '")';
        }

        $user = Yii::$app->db->createCommand('select '
            . 'ta.tenant_id, u.full_name, p.property_name, p.address_line_1, p.address_line_2, '
            . 'pt.property_type_name as property_type, pt.id as property_type_id, '
            . 'ta.property_id as rented_prop, ta.rent, ta.maintainance, ta.deposit, '
            . 'ta.lease_start_date, ta.lease_end_date, u1.full_name as sales_name, '
            . 'u2.full_name as operations_name '
            . 'from tenant_agreements as ta left join users as u on ta.tenant_id = u.id '
            . '' . $leftJoin . $where . ' '
            . 'ORDER BY ta.id DESC ')->queryAll();
        return $this->render('tenants', ['data' => $user, 'total' => count($user)]);



        //        $searchModelTenants = new \app\models\TenantAgreements();
        //        $dataProviderTenant = $searchModelTenants->searchCommon(Yii::$app->request->queryParams, 2);
        //        $dataProviderTenant->pagination->defaultPageSize = 10;
        //        $dataProviderTenant->pagination->pageSize = 10;
        //        $dataProviderTenant->pagination->pageSizeLimit = false;
        //        
        //        return $this->render('tenants', [
        //            'searchModelTenants' => $searchModelTenants, 
        //            'dataProviderTenant' => $dataProviderTenant
        //        ]);
    }

    public function actionOwners()
    {

        $searchModelOwnerLeads = new LeadsOwner();
        $dataProviderOwner = $searchModelOwnerLeads->searchCommon(Yii::$app->request->queryParams);
        //print_r($dataProviderOwner->getCount()); exit;
        $dataProviderOwner->pagination->defaultPageSize = 10;
        $dataProviderOwner->pagination->pageSize = 10;
        $dataProviderOwner->pagination->pageSizeLimit = false;

        return $this->render('owners', [
            'searchModelOwnerLeads' => $searchModelOwnerLeads,
            'dataProviderOwner' => $dataProviderOwner,
        ]);
    }

    public function actionOwner()
    {
        $searchModelOwner = new OwnerProfile();
        $dataProviderOwner = $searchModelOwner->getPropertyOwnerManageList(Yii::$app->request->queryParams);
        $dataProviderOwner->pagination->defaultPageSize = 10;
        $dataProviderOwner->pagination->pageSize = 10;
        $dataProviderOwner->pagination->pageSizeLimit = false;
        return $this->render('owner', [
            'searchModelOwnerLeads' => $searchModelOwner,
            'dataProviderOwner' => $dataProviderOwner,
        ]);
    }

    public function actionCreateadviser()
    {


        $model = new LeadsAdvisor;
        $comments = new \app\models\Comments;
        // $model->end_date = '';
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = 'json';
            return \yii\bootstrap\ActiveForm::validate($model);
        } else if ($model->load(Yii::$app->request->post())) {

            try {

                $model->created_by = Yii::$app->user->id;
                $model->created_date = date('Y-m-d H:i:s');
                $model->ref_status = 6;
                $model->created_by = Yii::$app->user->identity->id;
                $model->created_date = date('Y-m-d');

                if ($model->save(false)) {
                    $rand = Yii::$app->userdata->passwordGenerate();
                    $modelUser = new \app\models\Users;
                    $modelUser->login_id = $model->email_id;
                    $modelUser->full_name = $model->full_name;
                    $modelUser->username = $model->email_id;
                    $modelUser->password = md5($rand);
                    $modelUser->user_type = 5;
                    $modelUser->created_by = Yii::$app->user->id;
                    $modelUser->created_date = date('Y-m-d H:i:s');
                    $modelUser->auth_key = base64_encode($model->email_id . time() . rand(1000, 9999));
                    $modelUser->phone = $model->contact_number;
                    $modelUser->save(false);

                    $modelAdvisorProfile = new \app\models\AdvisorProfile;
                    $modelAdvisorProfile->advisor_id = $modelUser->id;
                    $modelAdvisorProfile->address_line_1 = $model->address;
                    $modelAdvisorProfile->address_line_2 = $model->address_line_2;
                    if (Yii::$app->user->identity->user_type == 7) {
                        $modelAdvisorProfile->operation_id = Yii::$app->user->id;
                    } else {
                        $modelAdvisorProfile->sales_id = Yii::$app->user->id;
                    }
                    $modelAdvisorProfile->state = $model->lead_state;
                    $modelAdvisorProfile->phone = $model->contact_number;
                    $modelAdvisorProfile->city = $model->lead_city;
                    $modelAdvisorProfile->pincode = $model->pincode;
                    $modelAdvisorProfile->branch_code = $model->branch_code;
                    $modelAdvisorProfile->email_id = $model->email_id;
                    $modelAdvisorProfile->save(false);

                    $comments->description = $model->communication;
                    $comments->user_id = $modelUser->id;
                    $comments->created_by = Yii::$app->user->id;
                    $comments->save(false);


                    // $email = Yii::$app->userdata->doMail(trim($modelUser->login_id),'Advisor Confirmation',"Hello $model->full_name <br /><br />
                    // Your Login Credentials Are:<br/>
                    // <b>Username</b>:".$modelUser->login_id."
                    // <br/>
                    // <b>Password</b>:".$rand."
                    // <br/><br/>
                    // Thanks for registering as Adviser. Your login to easyleases is one step away. Please verify your email by clicking on the link sent by us <br /> ".\yii\helpers\Html::a('confirm',Yii::$app->urlManager->createAbsoluteUrl(['users/confirm','id'=>base64_encode($modelUser->id),'key'=>$modelUser->auth_key])));
                    $email = 1;
                    if ($email) {
                        Yii::$app->getSession()->setFlash('success', 'Advisor Registered Successully');
                    } else {
                        Yii::$app->getSession()->setFlash('success', 'Email not sent, Advisor Registered Successfully');
                    }
                    // Yii::$app->getSession()->setFlash(
                    //     'success','Adviser created successfully'
                    // );
                    return $this->redirect('advisersdetails?id=' . base64_encode($model->id));
                } else {
                    Yii::$app->getSession()->setFlash(
                        'success',
                        'Adviser is not created'
                    );
                    return $this->redirect(['advisors']);
                }
            } catch (\Exception $e) {
                Yii::$app->getSession()->setFlash(
                    'success',
                    "{$e->getMessage()}"
                );
                return $this->renderAjax('_formadviser', [
                    'model' => $model
                ]);
            }
        } else {
            return $this->renderAjax('_formadviser', [
                'model' => $model
            ]);
        }
    }

    public function actionAdvisors()
    {

        $searchModelOwnerLeads = new LeadsAdvisor();

        $dataProviderOwner = $searchModelOwnerLeads->searchCommon(Yii::$app->request->queryParams);

        return $this->render('advisers', [
            'searchModelOwnerLeads' => $searchModelOwnerLeads,
            'dataProviderOwner' => $dataProviderOwner,
        ]);
    }

    public function actionPaymentreceipt()
    {
        //$dueDate = Date('Y-t-d', strtotime('+1 month'));
        $dueDate = Date('Y-m-t');
        $rowSet = [];

        $userType = Yii::$app->user->identity->user_type;
        if ($userType == 7) {
            $opsProfileModel = \app\models\OperationsProfile::findOne(['operations_id' => Yii::$app->user->id]);
            $currentRoleCode = $opsProfileModel->role_code;
            $currentRoleValue = $opsProfileModel->role_value;

            if ($currentRoleValue == 'ELHQ') {
                $where = "1";
            } else if ($currentRoleCode == 'OPSTMG') {
                $where = " lt.lead_state = '" . $currentRoleValue . "' ";
            } else if ($currentRoleCode == 'OPCTMG') {
                $where = " lt.lead_city = '" . $currentRoleValue . "' ";
            } else if ($currentRoleCode == 'OPBRMG') {
                $where = " lt.branch_code = '" . $currentRoleValue . "' ";
            } else if ($currentRoleCode == 'OPEXE') {
                $branchModel = \app\models\Branches::find()->where(['branch_code' => $currentRoleValue])->one();
                $where = " lt.branch_code = '" . $currentRoleValue . "' "
                    . " AND lt.lead_city = '" . $branchModel->city_code . "' "
                    . " AND tpro.operation_id = '" . Yii::$app->user->id . "' ";
            }
        } else {
            $opsProfileModel = \app\models\SalesProfile::findOne(['sale_id' => Yii::$app->user->id]);
            $currentRoleCode = $opsProfileModel->role_code;
            $currentRoleValue = $opsProfileModel->role_value;

            if ($currentRoleValue == 'ELHQ') {
                $where = "1";
            } else if ($currentRoleCode == 'SLSTMG') {
                $where = " lt.lead_state = '" . $currentRoleValue . "' ";
            } else if ($currentRoleCode == 'SLCTMG') {
                $where = " lt.lead_city = '" . $currentRoleValue . "' ";
            } else if ($currentRoleCode == 'SLBRMG') {
                $where = " lt.branch_code = '" . $currentRoleValue . "' ";
            } else if ($currentRoleCode == 'SLEXE') {
                $branchModel = \app\models\Branches::find()->where(['branch_code' => $currentRoleValue])->one();
                $where = " lt.branch_code = '" . $currentRoleValue . "' "
                    . " AND lt.lead_city = '" . $branchModel->city_code . "' "
                    . " AND tpro.sales_id = '" . Yii::$app->user->id . "' ";
            }
        }

        if (Yii::$app->request->get('search')) {
            $query = 'SELECT tp.id, tp.property_id, tp.parent_id, tp.payment_des, tp.due_date, tp.maintenance, tp.original_amount, tp.penalty_amount, '
                . 'tp.total_amount, tp.payment_mode, tp.neft_reference, tp.amount_paid, tp.tenant_id, ta.late_penalty_percent, '
                . 'ta.min_penalty '
                . 'FROM tenant_payments tp '
                . 'INNER JOIN properties p ON tp.property_id = p.id '
                . 'INNER JOIN users u ON u.id = tp.tenant_id '
                . 'INNER JOIN leads_tenant lt ON u.login_id = lt.email_id '
                . 'INNER JOIN tenant_profile tpro ON u.id = tpro.tenant_id '
                . 'INNER JOIN tenant_agreements ta ON (ta.tenant_id = tp.tenant_id AND ta.property_id = tp.parent_id) '
                . 'WHERE tp.payment_status IN (0, 2) AND tp.due_date <= "' . $dueDate . '" '
                . 'AND (p.property_name LIKE "%' . Yii::$app->request->get('search') . '%" '
                . 'OR u.full_name LIKE "%' . Yii::$app->request->get('search') . '%" '
                . 'OR tp.payment_des LIKE "%' . Yii::$app->request->get('search') . '%" '
                . 'OR "' . Yii::$app->request->get('search') . '" = ' . "'ALL'" . ') '
                . 'AND  (' . $where . ') ';
        } else {
        }

        if (Yii::$app->request->get('search')) {
            $rowSet = Yii::$app->db->createCommand($query)->queryAll();
        } else {
            //$rowSet = Yii::$app->db->createCommand($query)->queryAll();
            $rowSet = [];
        }

        return $this->render('payment_receipt', [
            'rowSet' => $rowSet
        ]);
    }

    public function actionPgitxnlist()
    {
        $dueDate = Date('Y-m-d', strtotime('+1 month'));
        $query = '';
        $rowSet = [];

        $searchStr = (!empty(Yii::$app->request->get('search'))) ? addslashes(Yii::$app->request->get('search')) : '';
        $sortBy = (!empty(Yii::$app->request->get('sort_by'))) ? addslashes(Yii::$app->request->get('sort_by')) : '';
        $responseTimeFrom = (!empty(Yii::$app->request->get('response_time_from'))) ? addslashes(Yii::$app->request->get('response_time_from')) : '';
        $responseTimeTo = (!empty(Yii::$app->request->get('response_time_to'))) ? addslashes(Yii::$app->request->get('response_time_to')) : '';
        $amountFrom = (!empty(Yii::$app->request->get('amount_from'))) ? (int) Yii::$app->request->get('amount_from') : '';
        $amountTo = (!empty(Yii::$app->request->get('amount_to'))) ? (int) Yii::$app->request->get('amount_to') : '';

        $searchQuery = '';
        $sortQuery = '';
        $resTimeFrmToQuery = '';
        $amountFrmToQuery = '';

        if (!empty($searchStr)) {
            $searchQuery = ' '
                . '(customer_id LIKE "%' . $searchStr . '%" '
                . 'OR txn_reference_no LIKE "%' . $searchStr . '%" '
                . 'OR bank_reference_no LIKE "%' . $searchStr . '%" '
                . 'OR bank_id LIKE "%' . $searchStr . '%" '
                . 'OR auth_status LIKE "%' . $searchStr . '%" '
                . ') ';
        }

        if (!empty($sortBy)) {
            $sortQuery = ' '
                . ' ORDER BY '
                . ' ' . $sortBy . ' ASC ';
        }

        if (!empty($responseTimeFrom)) {
            $resTimeFrmToQuery = ' '
                . ' response_time =  '
                . ' "' . $responseTimeFrom . '" ';
            if (!empty($searchQuery)) {
                $resTimeFrmToQuery = ' AND ' . $resTimeFrmToQuery;
            }
        }

        if (!empty($responseTimeTo)) {
            $resTimeFrmToQuery = ' '
                . ' response_time = '
                . ' "' . $responseTimeTo . '" ';
            if (!empty($searchQuery)) {
                $resTimeFrmToQuery = ' AND ' . $resTimeFrmToQuery;
            }
        }

        if (!empty($responseTimeFrom) && !empty($responseTimeTo)) {
            $resTimeFrmToQuery = ' '
                . ' response_time BETWEEN '
                . ' "' . $responseTimeFrom . '" AND "' . $responseTimeTo . '" ';
            if (!empty($searchQuery)) {
                $resTimeFrmToQuery = ' AND ' . $resTimeFrmToQuery;
            }
        }

        if (!empty($amountFrom)) {
            $amountFrmToQuery = ' '
                . ' amount = '
                . ' "' . $amountFrom . '" ';
            if (!empty($searchQuery) || !empty($resTimeFrmToQuery)) {
                $amountFrmToQuery = ' AND ' . $amountFrmToQuery;
            }
        }

        if (!empty($amountTo)) {
            $amountFrmToQuery = ' '
                . ' amount = '
                . ' "' . $amountTo . '" ';
            if (!empty($searchQuery) || !empty($resTimeFrmToQuery)) {
                $amountFrmToQuery = ' AND ' . $amountFrmToQuery;
            }
        }

        if (!empty($amountFrom) && !empty($amountTo)) {
            $amountFrmToQuery = ' '
                . ' amount BETWEEN '
                . ' "' . $amountFrom . '" AND "' . $amountTo . '" ';
            if (!empty($searchQuery) || !empty($resTimeFrmToQuery)) {
                $amountFrmToQuery = ' AND ' . $amountFrmToQuery;
            }
        }

        if (!empty($searchQuery) || !empty($resTimeFrmToQuery) || !empty($amountFrmToQuery) || !empty($sortQuery)) {
            $sql = 'SELECT full_name,'
                . 'customer_id, amount, request_time, response_time, txn_reference_no, bank_reference_no, '
                . 'bank_id, txn_type, currency_name, auth_status, description '
                . 'FROM pgi_interface a join users b on a.customer_id = b.id WHERE ' . $searchQuery . $resTimeFrmToQuery . $amountFrmToQuery . $sortQuery;
            $rowSet = Yii::$app->db->createCommand($sql)->queryAll();
        } else {
            $sql = 'SELECT full_name,'
                . 'customer_id, amount, request_time, response_time, txn_reference_no, bank_reference_no, '
                . 'bank_id, txn_type, currency_name, auth_status, description '
                . 'FROM pgi_interface a join users b on a.customer_id = b.id ';
            //$rowSet = Yii::$app->db->createCommand($sql)->queryAll();
        }

        return $this->render('pgi_txn_list', [
            'rowSet' => $rowSet
        ]);
    }

    public function actionUnpaynow()
    {
        if (!Yii::$app->user->isGuest) {
            if (!empty(Yii::$app->request->post('etc'))) {
                $model = \app\models\TenantPayments::find()->where(['id' => Yii::$app->request->post('etc')])->one();
                $model->payment_status = 0;
                $model->neft_reference = null;
                $model->amount_paid = 0;
                $model->save(false);
                return $this->redirect(Yii::$app->request->post('app_redirect_url'));
            }
        }
    }

    public function actionSavemoderation()
    {
        if (!empty(Yii::$app->request->post('id'))) {
            $tenantPayModel = \app\models\TenantPayments::find()->where(['id' => Yii::$app->request->post('id')])->one();
            $tenantPayModel->payment_mode = Yii::$app->request->post('payment_mode');
            $tenantPayModel->payment_date = date('Y-m-d', strtotime(Yii::$app->request->post('payment_date')));
            $tenantPayModel->amount_paid = Yii::$app->request->post('amount_paid');
            $payable_amount = Yii::$app->request->post('payable_amount');
            $actualPenalty = $tenantPayModel->amount_paid - $payable_amount;
            $tenantPayModel->penalty_amount = $actualPenalty;
            $tenantPayModel->neft_reference = (Yii::$app->request->post('payment_mode') == 1) ? Yii::$app->request->post('neft_reference') : "";
            if (Yii::$app->request->post('amount_paid') > 0 && !empty(Yii::$app->request->post('neft_reference'))) {
                $tenantPayModel->payment_status = 1;
                $tenantPayModel->save(false);
            }
        }
    }

    public function actionAlterpenaltyamount()
    {
        if (!empty(Yii::$app->request->post('id')) && !empty(Yii::$app->request->post('penalty_amount'))) {
            $query = 'SELECT tp.id, tp.property_id, tp.parent_id, tp.payment_des, tp.due_date, tp.original_amount, tp.penalty_amount, '
                . 'tp.total_amount, tp.payment_mode, tp.neft_reference, tp.amount_paid, tp.tenant_id, ta.late_penalty_percent, '
                . 'ta.min_penalty '
                . 'FROM tenant_payments tp '
                . 'INNER JOIN tenant_agreements ta ON (ta.tenant_id = tp.tenant_id AND ta.property_id = tp.parent_id) '
                . 'WHERE tp.id = ' . Yii::$app->request->post('id') . ' ';

            $row = Yii::$app->db->createCommand($query)->queryOne();

            $currentDate = Date('Y-m-d', strtotime(Yii::$app->request->post('payment_date')));
            $totalPenalty = 0;
            $penalty = 0;
            $dateDiff = (date_diff(date_create($row['due_date']), date_create($currentDate))->days) + 1;
            if (empty($row['payment_date']) && ($dateDiff > 0 && $row['due_date'] < date('Y-m-d'))) {
                $penalty = round(($dateDiff / date("z", (mktime(0, 0, 0, 12, 31, date('Y'))) + 1)) * ($row['late_penalty_percent'] / 100) * $row['total_amount']);
                $totalPenalty = ($penalty < $row['min_penalty']) ? $row['min_penalty'] : $penalty;
            }
            echo $totalPenalty;
        }
    }

    public function actionAdvisersdetailsajax($id)
    {
        $id = base64_decode($id);
        print_r($_POST['LeadsAdvisor']);
        $modelComments = new \app\models\Comments();
        $modelAgreements1 = new \app\models\AdvisorAgreements();
        $proofType = \app\models\ProofType::find()->all();
        if (($models = LeadsAdvisor::findOne($id)) !== null) {
            $ids = Yii::$app->userdata->getUserIdByEmail($models->email_id);
            $address_proofs = \app\models\UserIdProofs::find()->where(['user_id' => $ids])->all();
            if ($ids) {
                $comments = \app\models\Comments::find()->where(['user_id' => $ids])->orderBy(['id' => SORT_DESC])->all();
                $modelAgreements = AdvisorAgreements::findOne(['advisor_id' => $ids]);
                if (($model = AdvisorProfile::findOne(['advisor_id' => $ids])) !== null) {
                    if ($models->load(Yii::$app->request->post())) {
                        if (!empty($_POST['LeadsAdvisor']['follow_up_date_time']) && !empty($_POST['LeadsAdvisor']['schedule_date_time']) && !empty($_POST['LeadsAdvisor']['ref_status'])) {
                            $models->follow_up_date_time = date('Y-m-d H:i:s', strtotime($_POST['LeadsAdvisor']['follow_up_date_time']));
                            $models->schedule_date_time = date('Y-m-d H:i:s', strtotime($_POST['LeadsAdvisor']['schedule_date_time']));
                            $models->ref_status = $_POST['LeadsAdvisor']['ref_status'];
                            $models->save(false);
                        }

                        //$model->sales_id = $model->sales_id;
                        //$model->save(false);
                        if (isset($_FILES['AdvisorAgreements'])) {
                            if (!empty($_FILES['AdvisorAgreements']) && $_FILES['AdvisorAgreements']['size']['agreement_doc'] != 0) {
                                $name_agreement_doc = date('ymdHis') . $_FILES['AdvisorAgreements']['name']['agreement_doc'];
                                move_uploaded_file($_FILES['AdvisorAgreements']['tmp_name']['agreement_doc'], 'uploads/profiles/' . $name_agreement_doc);
                                $agreemnets_doc = 'uploads/profiles/' . $name_agreement_doc;
                            } else {
                                $agreemnets_doc = "";
                            }
                        } else {
                            $agreemnets_doc = "";
                        }

                        if (count($modelAgreements) != 0) {
                            $modelAgreements->start_date = date('Y-m-d H:i:s', strtotime(@$_POST['AdvisorAgreements']['start_date']));
                            $modelAgreements->end_date = date('Y-m-d H:i:s', strtotime(@$_POST['AdvisorAgreements']['end_date']));
                            $modelAgreements->updated_by = Yii::$app->user->id;
                            if ($agreemnets_doc != '') {
                                $modelAgreements->agreement_doc = $agreemnets_doc;
                            }
                            $modelAgreements->save(false);
                        } else {
                            $modelAgreements = new \app\models\AdvisorAgreements;
                            $modelAgreements->start_date = date('Y-m-d H:i:s', strtotime(@$_POST['AdvisorAgreements']['start_date']));
                            $modelAgreements->end_date = date('Y-m-d H:i:s', strtotime(@$_POST['AdvisorAgreements']['end_date']));
                            $modelAgreements->created_by = Yii::$app->user->id;
                            $modelAgreements->advisor_id = @$_POST['AdvisorAgreements']['advisor_id'];
                            $modelAgreements->agreement_doc = @$agreemnets_doc;
                            $modelAgreements->save(false);
                        }
                    }


                    if ($model->load(Yii::$app->request->post())) {
                        $model->phone = Yii::$app->userdata->trimPhone($model->phone);
                        // if($model->validate()){
                        $profile_image = UploadedFile::getInstance($model, 'profile_image');

                        if (!empty($profile_image)) {
                            $name_profile_image = date('ymdHis') . $profile_image->name;
                            $profile_image->saveAs('uploads/profiles/' . $name_profile_image);
                            $model->profile_image = 'uploads/profiles/' . $name_profile_image;
                        }

                        if (isset($_FILES['address_proof'])) {
                            $address_proof = $_FILES['address_proof'];
                        } else {
                            $address_proof = array();
                        }

                        $model->save(false);

                        $modelUsers = Users::findOne($ids);
                        $modelUsers->login_id = $model->email_id;
                        $modelUsers->username = $model->email_id;
                        $modelUsers->username = $model->phone;
                        $modelUsers->save(false);



                        $models->email_id = $model->email_id;
                        $models->contact_number = $model->phone;
                        $models->address = $model->address_line_1;
                        $models->address_line_2 = $model->address_line_2;
                        $models->lead_state = $model->state;
                        $models->lead_city = $model->city;
                        $models->pincode = $model->pincode;
                        $models->updated_date = date('Y-m-d H:i:s');
                        $models->save(false);

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
                                        $address_proofobj->user_id = $ids;
                                        $address_proofobj->created_by = $ids;
                                        $address_proofobj->created_date = date('Y-m-d H:i:s');
                                        $address_proofobj->save(false);
                                    }
                                }
                            }
                        }
                        // $this->redirect('advisersdetails?id='.base64_encode($id));
                        // }
                        // else{
                        // $this->redirect('advisersdetails?id='.base64_encode($id));
                        // }
                    }
                } else {
                    $return = array('success' => 0, 'User profile does not exist');
                }
            } else {
                $return = array('success' => 0, 'No such user found');
            }
        } else {
            $return = array('success' => 0, 'msg' => 'No such lead found');
        }
    }

    public function actionAdvisersdetails($id)
    {
        $id = base64_decode($id);
        $modelComments = new \app\models\Comments();
        $modelAgreements1 = new \app\models\AdvisorAgreements();
        $model = new \app\models\AdvisorProfile();
        $proofType = \app\models\ProofType::find()->all();
        if (($models = LeadsAdvisor::findOne($id)) !== null) {
            $ids = Yii::$app->userdata->getUserIdByEmail($models->email_id);
            $address_proofs = \app\models\UserIdProofs::find()->where(['user_id' => $ids])->all();

            if ($ids) {
                $comments = \app\models\Comments::find()->where(['user_id' => $ids])->orderBy(['id' => SORT_DESC])->all();
                $modelAgreements = AdvisorAgreements::findOne(['advisor_id' => $ids]);
                if (($model = AdvisorProfile::findOne(['advisor_id' => $ids])) !== null) {
                    $bankCheck = $model->cancelled_check;
                    if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
                        Yii::$app->response->format = 'json';
                        return \yii\bootstrap\ActiveForm::validate($model);
                    }
                    $model->phone = Yii::$app->userdata->trimPhone($model->phone);
                    $profileimage = $model->profile_image;
                    if ($model->sales_id != 0) {
                        $salesperson = \app\models\OperationsProfile::find()->where(['operations_id' => $model->operation_id])->one();
                        $salesname = \app\models\User::find()->where(['id' => $model->operation_id])->one();
                    } else {
                        $salesperson = array();
                        $salesname = array();
                    }




                    if ($models->load(Yii::$app->request->post())) {
                        // if($model->validate){

                        $models->leads_advisor = 1;
                        $models->full_name = $models->full_name;
                        if (isset($_POST['LeadsAdvisor']['follow_up_date_time']) && isset($_POST['LeadsAdvisor']['schedule_date_time']) && isset($_POST['LeadsAdvisor']['ref_status'])) {
                            $models->follow_up_date_time = date('Y-m-d H:i:s', strtotime($_POST['LeadsAdvisor']['follow_up_date_time']));
                            $models->schedule_date_time = date('Y-m-d H:i:s', strtotime($_POST['LeadsAdvisor']['schedule_date_time']));
                            $models->ref_status = $_POST['LeadsAdvisor']['ref_status'];
                        }

                        //$models->save(false);
                        //$model->sales_id = $model->sales_id;
                        //$model->save(false);

                        if (isset($_FILES['AdvisorAgreements']['size']['agreement_doc'])) {
                            if ($_FILES['AdvisorAgreements']['size']['agreement_doc'] != 0) {
                                $name_agreement_doc = date('ymdHis') . $_FILES['AdvisorAgreements']['name']['agreement_doc'];
                                move_uploaded_file($_FILES['AdvisorAgreements']['tmp_name']['agreement_doc'], 'uploads/profiles/' . $name_agreement_doc);
                                $agreemnets_doc = 'uploads/profiles/' . $name_agreement_doc;
                            } else {
                                $agreemnets_doc = "";
                            }

                            if (count($modelAgreements) != 0) {
                                if ($_POST['AdvisorAgreements']['start_date'] != '') {
                                    $modelAgreements->start_date = date('d-M-Y', strtotime($_POST['AdvisorAgreements']['start_date']));
                                }
                                if ($_POST['AdvisorAgreements']['end_date'] != '') {
                                    $modelAgreements->end_date = date('d-M-Y', strtotime($_POST['AdvisorAgreements']['end_date']));
                                }
                                if ($agreemnets_doc != '') {
                                    $modelAgreements->agreement_doc = $agreemnets_doc;
                                }
                                $modelAgreements->updated_by = Yii::$app->user->id;
                                if ($modelAgreements->validate()) {



                                    $modelAgreements->save(false);
                                    Yii::$app->getSession()->setFlash(
                                        'success',
                                        'Advisor profile updated successfully'
                                    );
                                } else {
                                    return $this->render('adviser_details', [
                                        'model' => $model,
                                        'modelLeads' => $models,
                                        'modelComments' => $modelComments,
                                        'comments' => $comments,
                                        'operationperson' => $salesname,
                                        'operationProfile' => $salesperson,
                                        'agreements' => $modelAgreements,
                                        'proofType' => $proofType,
                                        'address_proofs' => $address_proofs,
                                        'agreements1' => $modelAgreements1,
                                    ]);
                                }
                            } else {

                                $modelAgreements = new \app\models\AdvisorAgreements;
                                if ($_POST['AdvisorAgreements']['start_date'] != '') {
                                    $modelAgreements->start_date = date('d-M-Y', strtotime($_POST['AdvisorAgreements']['start_date']));
                                }
                                if ($_POST['AdvisorAgreements']['end_date'] != '') {
                                    $modelAgreements->end_date = date('d-M-Y', strtotime($_POST['AdvisorAgreements']['end_date']));
                                }

                                $modelAgreements->created_by = Yii::$app->user->id;
                                $modelAgreements->advisor_id = $_POST['AdvisorAgreements']['advisor_id'];
                                $modelAgreements->agreement_doc = $agreemnets_doc;
                                if ($modelAgreements->validate()) {
                                    $modelAgreements->save(false);
                                } else {
                                    return $this->render('adviser_details', [
                                        'model' => $model,
                                        'modelLeads' => $models,
                                        'modelComments' => $modelComments,
                                        'comments' => $comments,
                                        'operationperson' => $salesname,
                                        'operationProfile' => $salesperson,
                                        'agreements' => $modelAgreements,
                                        'proofType' => $proofType,
                                        'address_proofs' => $address_proofs,
                                        'agreements1' => $modelAgreements1,
                                    ]);
                                }
                            }
                        }
                    }


                    if ($model->load(Yii::$app->request->post())) {
                        if ($model->validate()) {
                            $cancelled_check = UploadedFile::getInstance($model, 'cancelled_check');
                            $profile_image = UploadedFile::getInstance($model, 'profile_image');

                            if (!empty($profile_image)) {
                                $name_profile_image = date('ymdHis') . $profile_image->name;
                                $profile_image->saveAs('uploads/profiles/' . $name_profile_image);
                                $model->profile_image = 'uploads/profiles/' . $name_profile_image;
                            } else {
                                $model->profile_image = $profileimage;
                            }

                            if (!empty($cancelled_check)) {
                                $name_cancelled_check = date('ymdHis') . $cancelled_check->name;
                                $cancelled_check->saveAs('uploads/proofs/' . $name_cancelled_check);
                                $model->cancelled_check = 'uploads/proofs/' . $name_cancelled_check;
                            } else {
                                $model->cancelled_check = $bankCheck;
                            }

                            if (isset($_FILES['address_proof'])) {
                                $address_proof = $_FILES['address_proof'];
                            } else {
                                $address_proof = array();
                            }

                            $model->save(false);

                            $modelUsers = Users::findOne($ids);
                            $modelUsers->login_id = $model->email_id;
                            $modelUsers->username = $model->email_id;
                            $modelUsers->phone = $model->phone;
                            $modelUsers->save(false);



                            $models->email_id = $model->email_id;
                            $models->contact_number = $model->phone;
                            $models->address = $model->address_line_1;
                            $models->address_line_2 = $model->address_line_2;
                            $models->lead_state = $model->state;
                            $models->lead_city = $model->city;
                            $models->branch_code = $model->branch_code;
                            $models->pincode = $model->pincode;
                            $models->updated_date = date('Y-m-d H:i:s');
                            $models->save(false);

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
                                            $address_proofobj->user_id = $ids;
                                            $address_proofobj->created_by = $ids;
                                            $address_proofobj->created_date = date('Y-m-d H:i:s');
                                            $address_proofobj->save(false);
                                        }
                                    }
                                }
                            }
                            Yii::$app->getSession()->setFlash(
                                'success',
                                'Advisor profile updated successfully'
                            );
                            $this->redirect('advisersdetails?id=' . base64_encode($id));
                        } else {

                            return $this->render('adviser_details', [
                                'model' => $model,
                                'modelLeads' => $models,
                                'modelComments' => $modelComments,
                                'comments' => $comments,
                                'operationperson' => $salesname,
                                'operationProfile' => $salesperson,
                                'agreements' => $modelAgreements,
                                'proofType' => $proofType,
                                'address_proofs' => $address_proofs,
                                'agreements1' => $modelAgreements1,
                            ]);
                        }
                    } else {
                        return $this->render('adviser_details', [
                            'model' => $model,
                            'modelLeads' => $models,
                            'modelComments' => $modelComments,
                            'comments' => $comments,
                            'operationperson' => $salesname,
                            'operationProfile' => $salesperson,
                            'agreements' => $modelAgreements,
                            'proofType' => $proofType,
                            'address_proofs' => $address_proofs,
                            'agreements1' => $modelAgreements1,
                        ]);
                    }
                } else {
                    throw new NotFoundHttpException('The requested page does not exist.');
                }
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionPropertieslisting()
    {

        $searchModelOwnerLeads = new Properties();

        $dataProviderOwner = $searchModelOwnerLeads->getListing(Yii::$app->request->queryParams);
        $dataProviderOwner->pagination->defaultPageSize = 10;
        $dataProviderOwner->pagination->pageSize = 10;
        $dataProviderOwner->pagination->pageSizeLimit = false;
        return $this->render('propertieslisting', [
            'searchModelOwnerLeads' => $searchModelOwnerLeads,
            'dataProviderOwner' => $dataProviderOwner,
        ]);
    }

    public function actionProperties()
    {
        $searchModelOwnerLeads = new Properties();
        $dataProviderOwner = $searchModelOwnerLeads->getListing(Yii::$app->request->queryParams);
        return $this->render('properties', [
            'searchModelOwnerLeads' => $searchModelOwnerLeads,
            'dataProviderOwner' => $dataProviderOwner,
        ]);
    }

    public function actionPropertyleads()
    {

        $searchModelPropertyLeads = new \app\models\LeadsProperty();

        $dataProviderProperty = $searchModelPropertyLeads->searchCommon(Yii::$app->request->queryParams);

        return $this->render('property_leads', [
            'searchModelPropertyLeads' => $searchModelPropertyLeads,
            'dataProviderProperty' => $dataProviderProperty,
        ]);
    }

    public function actionPropertiesdetail($id)
    {
        $id = base64_decode($id);
        if (Yii::$app->userdata->getPropertyBookedStatus($id)) {
            Yii::$app->getSession()->setFlash(
                'notice',
                'Property Can Not Be Edited'
            );
            return $this->redirect('properties');
        }


        $model = \app\models\Properties::findOne($id);
        $modelImages = \app\models\PropertyImages::find()->where(['property_id' => $id])->all();
        $modelPropertyAgreements = \app\models\PropertyAgreements::findOne(['property_id' => $id]);
        if (empty($modelPropertyAgreements)) {
            $modelPropertyAgreements = new \app\models\PropertyAgreements;
        }
        if ($model->load(Yii::$app->request->post())) {
            /*  echo  "<pre>";print_r($model['beds']);
              echo  "<pre>";print_r($model);
              die ; */

            $model->created_by = Yii::$app->user->id;
            $model->created_date = date('Y-m-d H:i:s');
            // $model->owner_id = $id;
            if ($model->property_type == 3) {
                $model->is_room = 0;
            }

            if ($model->validate()) {
                $model->save(false);

                if ($model->property_type == 3) {

                    $today = date("Ymd");
                    $rand = strtoupper(substr(uniqid(sha1(time())), 0, 4));
                    $unique = $today . $rand;
                    \app\models\ChildProperties::deleteAll('main = :pid', [':pid' => $model->id]);

                    $modelChild = new \app\models\ChildProperties;
                    $modelChild->property_code = $unique;
                    $modelChild->parent = $model->id;
                    $modelChild->status = 1;
                    $modelChild->sub_parent = 0;
                    $modelChild->type = 0;
                    $modelChild->main = $model->id;
                    $modelChild->save(false);
                } else {

                    if ($model->is_room == 1) {
                        $today = date("Ymd");
                        $rand = strtoupper(substr(uniqid(sha1(time())), 0, 4));
                        $unique = $today . $rand;
                        \app\models\ChildProperties::deleteAll('main = :pid', [':pid' => $model->id]);

                        $modelChild = new \app\models\ChildProperties;
                        $modelChild->property_code = $unique;
                        $modelChild->parent = $model->id;
                        $modelChild->status = 1;
                        $modelChild->sub_parent = 0;
                        $modelChild->type = 0;
                        $modelChild->main = $model->id;
                        $modelChild->save(false);

                        for ($i = 0; $i < $model->rooms; $i++) {
                            $rand1 = strtoupper(substr(uniqid(sha1(time())), 0, 5));
                            $unique1 = $today . $rand1;
                            $modelChildRooms = new \app\models\ChildProperties;
                            $modelChildRooms->property_code = $unique1;
                            $modelChildRooms->parent = $model->id;
                            $modelChildRooms->status = 1;
                            $modelChildRooms->sub_parent = 0;
                            $modelChildRooms->type = 1;
                            $modelChildRooms->main = $model->id;
                            $modelChildRooms->save(false);
                        }
                    } else {

                        $today = date("Ymd");
                        $rand = strtoupper(substr(uniqid(sha1(time())), 0, 4));
                        $unique = $today . $rand;
                        \app\models\ChildProperties::deleteAll('main = :pid', [':pid' => $model->id]);

                        $modelChild = new \app\models\ChildProperties;
                        $modelChild->property_code = $unique;
                        $modelChild->parent = $model->id;
                        $modelChild->status = 1;
                        $modelChild->sub_parent = 0;
                        $modelChild->type = 0;
                        $modelChild->main = $model->id;
                        $modelChild->save(false);

                        for ($i = 0; $i < count($model['beds']); $i++) {

                            $rand1 = strtoupper(substr(uniqid(sha1(time())), 0, 5));
                            $unique1 = $today . $rand1;
                            $modelChildRooms = new \app\models\ChildProperties;
                            $modelChildRooms->property_code = $unique1;
                            $modelChildRooms->parent = $model->id;
                            $modelChildRooms->status = 1;
                            $modelChildRooms->sub_parent = 1;
                            $modelChildRooms->type = 1;
                            $modelChildRooms->main = $model->id;
                            $modelChildRooms->save(false);

                            for ($j = 0; $j < $model['beds'][$i]; $j++) {

                                $rand2 = strtoupper(substr(uniqid(sha1(time())), 0, 6));
                                $unique2 = $today . $rand2;
                                $modelChildBeds = new \app\models\ChildProperties;
                                $modelChildBeds->property_code = $unique2;
                                $modelChildBeds->parent = $modelChildRooms->id;
                                $modelChildBeds->status = 1;
                                $modelChildBeds->sub_parent = 0;
                                $modelChildBeds->type = 2;
                                $modelChildBeds->main = $model->id;
                                $modelChildBeds->save(false);
                            }
                        }
                    }
                }


                Yii::$app->getSession()->setFlash(
                    'success',
                    'Property added successfully'
                );
                return $this->render('editproperties', [
                    'model' => $model,
                    'modelImages' => $modelImages,
                    'modelPropertyAgreements' => $modelPropertyAgreements,
                ]);
            } else {
                return $this->render('editproperties', [
                    'model' => $model,
                    'modelImages' => $modelImages,
                    'modelPropertyAgreements' => $modelPropertyAgreements,
                ]);
            }
        } else {
            return $this->render('editproperties', [
                'model' => $model,
                'modelImages' => $modelImages,
                'modelPropertyAgreements' => $modelPropertyAgreements,
            ]);
        }
    }

    public function actionAdviser()
    {
        $searchModelOwnerLeads = new AdvisorProfile();

        $dataProviderOwner = $searchModelOwnerLeads->getListing(Yii::$app->request->queryParams);

        return $this->render('adviser', [
            'searchModelOwnerLeads' => $searchModelOwnerLeads,
            'dataProviderOwner' => $dataProviderOwner,
        ]);
    }

    public function actionAdviserdetails($id)
    {
        $ids = base64_decode($id);
        $proofType = \app\models\ProofType::find()->all();
        $modelComments = new \app\models\Comments;
        if (($models = Users::findOne($ids)) !== null) {
            $modelLeads = \app\models\LeadsAdvisor::findOne(['email_id' => $models->login_id]);
            $address_proofs = \app\models\UserIdProofs::find()->where(['user_id' => $ids])->all();

            if (($model = \app\models\AdvisorProfile::find()->where(['advisor_id' => $ids])->one()) != null) {
                if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
                    Yii::$app->response->format = 'json';
                    return \yii\bootstrap\ActiveForm::validate($model);
                } else if ($model->load(Yii::$app->request->post())) {
                    $model->phone = Yii::$app->userdata->trimPhone($model->phone);
                    $models->full_name = $_POST['Users']['full_name'];
                    $models->login_id = $_POST['Users']['login_id'];
                    $models->username = $_POST['Users']['login_id'];
                    $models->phone = Yii::$app->userdata->trimPhone($model->phone);
                    $model->email_id = $models->login_id;
                    $userValidation = $models->validate(['login_id', 'full_name', 'phone']);
                    $userProfileValidation = $model->validate();
                    if ($userValidation && $userProfileValidation) {

                        $profile_image = UploadedFile::getInstance($model, 'profile_image');

                        if (!empty($profile_image)) {
                            $name_profile_image = date('ymdHis') . $profile_image->name;
                            $profile_image->saveAs('uploads/profiles/' . $name_profile_image);
                            // $model->profile_image =  Url::home(true).'uploads/profiles/'.$name_profile_image ;
                            $model->profile_image = 'uploads/profiles/' . $name_profile_image;
                        } else {
                            // $model->profile_image  = $profileimage  ;
                        }

                        $models->save(false);
                        $model->save(false);
                        if (count($modelLeads) != 0) {
                            $modelLeads->email_id = $models->login_id;
                            $modelLeads->address = $model->address_line_1;
                            $modelLeads->address_line_2 = $model->address_line_2;
                            $modelLeads->lead_state = $model->state;
                            $modelLeads->lead_city = $model->city;
                            $modelLeads->branch_code = $model->region;
                            $modelLeads->pincode = $model->pincode;
                            $modelLeads->contact_number = $model->phone;
                            $modelLeads->save(false);
                        }
                        if (isset($_FILES['address_proof'])) {
                            $address_proof = $_FILES['address_proof'];
                        } else {
                            $address_proof = array();
                        }

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
                                        $address_proofobj->user_id = $ids;
                                        $address_proofobj->created_by = $ids;
                                        $address_proofobj->created_date = date('Y-m-d H:i:s');
                                        $address_proofobj->save(false);
                                    }
                                }
                            }
                        }
                        //$this->redirect('adviser');
                        return $this->render('adviserdetails', ['model' => $model, 'modelUser' => $models, 'modelComments' => $modelComments, 'proofType' => $proofType, 'address_proofs' => $address_proofs,]);
                    } else {
                        return $this->render('adviserdetails', ['model' => $model, 'modelUser' => $models, 'modelComments' => $modelComments, 'proofType' => $proofType, 'address_proofs' => $address_proofs,]);
                    }
                } else {
                    return $this->render('adviserdetails', ['model' => $model, 'modelUser' => $models, 'modelComments' => $modelComments, 'proofType' => $proofType, 'address_proofs' => $address_proofs,]);
                }
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionServicerequests()
    {
        $model = new \app\models\ServiceRequestAttachment;
        $getAllRequest = $model->getAllRequestByOperator(Yii::$app->user->id);
        return $this->render('servicerequests', [
            'getAllRequest' => $getAllRequest
        ]);
    }

    public function actionCreateservice()
    {
        $model = new \app\models\PropertyServiceRequest;
        $status = \app\models\RequestStatus::find()->All();
        $requestType = \app\models\RequestType::find()->All();
        $requestComments = new \app\models\PropertyServiceComment();
        $modelAttach = new \app\models\ServiceRequestAttachment;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $modelAttach->imageFiles = UploadedFile::getInstances($modelAttach, 'attachment');
                $model->created_by = Yii::$app->user->id;
                $model->client_id = Yii::$app->request->post('PropertyServiceRequest')['owner_tenant_name'];
                $model->client_type = Yii::$app->request->post('PropertyServiceRequest')['client_type'];
                $model->property_id = Yii::$app->request->post('PropertyServiceRequest')['property_id'];
                $model->status = Yii::$app->request->post('PropertyServiceRequest')['status'];
                $model->title = Yii::$app->request->post('PropertyServiceRequest')['title'];
                $model->description = Yii::$app->request->post('PropertyServiceRequest')['description'];
                $model->operated_by = Yii::$app->user->id;
                $model->save(false);
                $lastInsert = $model->id;

                if (count($modelAttach->imageFiles) > 0) {
                    $modelAttach->upload($lastInsert);
                }

                $requestComments->service_request_id = $model->id;
                $requestComments->user_type = Yii::$app->user->identity->user_type;
                $requestComments->user_id = Yii::$app->user->id;
                $requestComments->message = Yii::$app->request->post('PropertyServiceComment')['message'];
                $requestComments->save(false);

                $toEmail = [];
                $ccEmail = [];

                if ($model->client_type == 3) {
                    $modelAppProfile = \app\models\ApplicantProfile::find()->where(['applicant_id' => $model->client_id])->one();
                    $toEmail[] = Yii::$app->userdata->getEmailById($modelAppProfile->applicant_id);
                    $msg = "<html>"
                        . "<head>"
                        . "</head>"
                        . "<body>"
                        . "We have created a service request numbered " . $model->id . " on your behalf by your designated support person '" . Yii::$app->userdata->getName() . "' who will be happy to help you in resolution of your " . Yii::$app->userdata->getRequestType(Yii::$app->request->post('PropertyServiceRequest')['request_type']) . " request. '" . Yii::$app->userdata->getName() . "' is available to support you at " . Yii::$app->userdata->getEmail() . " or " . Yii::$app->userdata->getPhoneById(Yii::$app->user->id, Yii::$app->userdata->getusertype()) . ". <br />"
                        . "Thanks and Regards,<br />Easyleases Support Team<br />"
                        . "<img src='" . Url::home(true) . "images/property_logo.png' alt='' width='150px' height='40px'>"
                        . "</body>"
                        . "</html>";
                }

                if ($model->client_type == 4) {
                    $modelAppProfile = \app\models\OwnerProfile::find()->where(['owner_id' => $model->client_id])->one();
                    $toEmail[] = Yii::$app->userdata->getEmailById($modelAppProfile->owner_id);
                    $msg = "<html>"
                        . "<head>"
                        . "</head>"
                        . "<body>"
                        . "We have created a service request numbered " . $model->id . " on your behalf by your designated support person '" . Yii::$app->userdata->getName() . "' who will be happy to help you in resolution of your " . Yii::$app->userdata->getRequestType(Yii::$app->request->post('PropertyServiceRequest')['request_type']) . " request. '" . Yii::$app->userdata->getName() . "' is available to support you at " . Yii::$app->userdata->getEmail() . " or " . Yii::$app->userdata->getPhoneById(Yii::$app->user->id, Yii::$app->userdata->getusertype()) . ". <br />"
                        . "Thanks and Regards,<br />Easyleases Support Team<br />"
                        . "<img src='" . Url::home(true) . "images/property_logo.png' alt='' width='150px' height='40px'>"
                        . "</body>"
                        . "</html>";
                }

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

                if (!empty($toEmail)) {
                    Yii::$app->userdata->doMailWithCC(implode(',', $toEmail), implode(',', $ccEmail), '', $subject, $msg, []);
                    $disMsg = "Thanks !!! We have received your service request, type - '" . Yii::$app->userdata->getRequestType(Yii::$app->request->post('PropertyServiceRequest')['request_type']) . "' and the same has been assigned to '" . Yii::$app->userdata->getName() . "', available at '" . Yii::$app->userdata->getEmail() . "' and '" . Yii::$app->userdata->getPhoneById(Yii::$app->user->id, Yii::$app->userdata->getusertype()) . "'";
                    Yii::$app->getSession()->setFlash(
                        'success',
                        $disMsg
                    );
                }

                return $this->redirect('servicerequests');
            } else {
                return $this->render('newrequest', ['model' => $model, 'requestType' => $requestType, 'comment' => $requestComments]);
            }
        } else {
            return $this->render('newrequest', [
                'model' => $model,
                'status' => $status,
                'attachment' => $modelAttach,
                'requestType' => $requestType,
                'comment' => $requestComments
            ]);
        }
    }

    public function actionServicedetail($id)
    {
        $id = base64_decode($id);
        $requestType = \app\models\RequestType::find()->All();
        $requestStatus = \app\models\RequestStatus::find()->All();
        $modelComments = new \app\models\PropertyServiceComment;
        $modelAttach = new \app\models\ServiceRequestAttachment;
        if ($model = \app\models\PropertyServiceRequest::findOne(['id' => $id])) {
            $comments = \app\models\PropertyServiceComment::find()->where(['service_request_id' => $id])->orderBy('id desc')->limit(5)->all();
            if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
                Yii::$app->response->format = 'json';
                return \yii\bootstrap\ActiveForm::validate($model);
            } else if ($model->load(Yii::$app->request->post())) {
                if ($model->validate()) {
                    $profile_image = UploadedFile::getInstance($model, 'attachment');
                    $profile_image2 = UploadedFile::getInstance($model, 'attachment2');

                    if (!empty($profile_image)) {
                        $name_profile_image = date('ymdHis') . $profile_image->name;
                        $profile_image->saveAs('uploads/requests/' . $name_profile_image);
                        // $model->attachment =  Url::home(true).'uploads/requests/'.$name_profile_image ;
                        $model->attachment = 'uploads/requests/' . $name_profile_image;
                    }
                    if (!empty($profile_image2)) {
                        $name_profile_image = date('ymdHis') . $profile_image2->name;
                        $profile_image2->saveAs('uploads/requests/' . $name_profile_image);
                        // $model->attachment =  Url::home(true).'uploads/requests/'.$name_profile_image ;
                        $model->attachment2 = 'uploads/requests/' . $name_profile_image;
                    }

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

                    $comments = \app\models\PropertyServiceComment::find()->where(['service_request_id' => $model->id])->orderBy("created_datetime DESC")->all();
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
                        . "An update on your service request numbered " . $model->id . " was done by " . Yii::$app->userdata->getFullNameById($model->operated_by) . ".  The latest details of your service request are: <br /><br />"
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
                        'success',
                        $disMsg
                    );

                    return $this->redirect('servicerequests');
                } else {
                    return $this->render('oprequestdetail', ['model' => $model, 'requestType' => $requestType, 'comment' => $modelComments, 'status' => $requestStatus, 'comments' => $comments]);
                }
            } else {
                $model->updated_date = date('d-m-Y / H:i', strtotime($model->updated_date));
                $attachModel = \app\models\PropertyServiceRequest::getServiceRequestDetailsForOperation($id);
                return $this->render('oprequestdetail', [
                    'model' => $model,
                    'attachModel' => $attachModel,
                    'attachment' => $modelAttach,
                    'requestType' => $requestType,
                    'comment' => $modelComments,
                    'status' => $requestStatus,
                    'comments' => $comments
                ]);
            }
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionRequestdetails($id)
    {
        $id = base64_decode($id);
        $this->layout = false;
        $requestType = \app\models\RequestType::find()->All();
        $requestStatus = \app\models\RequestStatus::find()->All();
        $modelComments = new \app\models\PropertyServiceComment;
        $modelAttach = new \app\models\ServiceRequestAttachment;

        $modelAttach->imageFiles = UploadedFile::getInstances($modelAttach, 'attachment');
        if (!empty($modelAttach->imageFiles)) {
            $modelAttach->upload($id);
            $res = ['status' => 'success'];
        } else {
            $res = ['status' => 'error'];
        }
        echo json_encode($res);
    }

    public function actionRequestattachremove($id, $rid)
    {
        $id = base64_decode($id);
        $model = \app\models\ServiceRequestAttachment::findOne($id);
        $model->delete();
        return $this->redirect(['servicedetail?id=' . $rid . "#attach-box"]);
    }

    public function actionAddservicecomment()
    {
        $requestComments = new \app\models\PropertyServiceComment;
        $requestComments->service_request_id = base64_decode($_POST['service_id']);
        $requestComments->user_type = Yii::$app->user->identity->user_type;
        $requestComments->user_id = Yii::$app->user->id;
        $requestComments->message = $_POST['message'];
        $requestComments->save(false);
        $currentDateTime = date('Y-m-d H:i:s');
        $formatedDate = date('d-m-Y / H:i', strtotime($currentDateTime));
        $sql = "UPDATE `service_request` SET updated_date = '" . $currentDateTime . "' WHERE id = " . base64_decode($_POST['service_id']);
        Yii::$app->db->createCommand($sql)->execute();
        $data = \app\models\PropertyServiceComment::find()->where(['service_request_id' => base64_decode($_POST['service_id'])])->orderBy('id Desc')->limit(5)->all();
        $result = array();
        foreach ($data as $key => $value) {
            $result[] = array('user_id' => $value->user_id, 'message' => $value->message, 'created_date' => date('d/m/Y', strtotime($value->created_datetime)), 'created_time' => date('h:i a', strtotime($value->created_datetime)), 'username' => Yii::$app->userdata->getFullNameById($value->user_id), 'updated_date' => $formatedDate);
        }
        return json_encode($result);
    }

    public function actionAddadhoccomment()
    {
        $requestComments = new \app\models\AdhocComments;
        $requestComments->adhoc_id = base64_decode($_POST['service_id']);
        //$requestComments->user_type=Yii::$app->user->identity->user_type;
        $requestComments->user_id = Yii::$app->user->id;
        $requestComments->message = $_POST['message'];
        $requestComments->save(false);
        $data = \app\models\AdhocComments::find()->where(['adhoc_id' => base64_decode($_POST['service_id'])])->orderBy('id Desc')->limit(5)->all();
        $result = array();
        foreach ($data as $key => $value) {
            $result[] = array('user_id' => $value->user_id, 'message' => $value->message, 'created_date' => date('d/m/Y', strtotime($value->created_date)), 'created_time' => date('h:i a', strtotime($value->created_date)), 'username' => Yii::$app->userdata->getFullNameById($value->user_id));
        }
        return json_encode($result);
    }

    public function actionAdhocrequests()
    {
        $searchModelOwnerLeads = new \app\models\AdhocRequests();
        //print_r(Yii::$app->request->queryParams);die;
        $dataProviderOwner = $searchModelOwnerLeads->search(Yii::$app->request->queryParams);

        return $this->render('adhocrequests', [
            'searchModelOwnerLeads' => $searchModelOwnerLeads,
            'dataProviderOwner' => $dataProviderOwner,
        ]);
    }

    public function actionCreateadhoc()
    {
        $model = new AdhocRequests;
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = 'json';
            return \yii\bootstrap\ActiveForm::validate($model);
        } else if ($model->load(Yii::$app->request->post())) {

            try {
                $model->request_status = 0;
                $model->payment_status = 0;
                $model->created_by = Yii::$app->user->id;
                if ($model->save(false)) {
                    Yii::$app->getSession()->setFlash(
                        'success',
                        'Adhoc Request created successfully'
                    );
                    return $this->redirect('adhocrequests');
                } else {
                    Yii::$app->getSession()->setFlash(
                        'success',
                        'Adhoc Request is not created'
                    );
                    return $this->redirect(['advisors']);
                }
            } catch (\Exception $e) {
                Yii::$app->getSession()->setFlash(
                    'success',
                    "{$e->getMessage()}"
                );
                return $this->renderAjax('_createadhoc', [
                    'model' => $model
                ]);
            }
        } else {
            return $this->renderAjax('_createadhoc', [
                'model' => $model
            ]);
        }
    }

    public function actionGetalltenants()
    {
        $str = str_replace(" ", "%%", $_POST['str']);
        $userAssignments = \app\models\Users::find()->where(['id' => Yii::$app->user->id])->one();
        if ($userAssignments) {

            $condition1 = '';
            $role = $userAssignments->role;
            switch ($role) {
                case 5:
                    $roles = \app\models\OperationsProfile::find()->select('region')->where(['operations_id' => Yii::$app->user->id])->one();
                    $properties = \app\models\Properties::find()->select('id')->where(['region' => $roles->region])->all();
                    break;
                case 6:
                    $roles = \app\models\OperationsProfile::find()->select('region')->where(['operations_id' => Yii::$app->user->id])->one();
                    $properties = \app\models\Properties::find()->select('id')->where(['region' => $roles->region])->all();
                    break;
                case 7:
                    $roles = \app\models\OperationsProfile::find()->select('city')->where(['operations_id' => Yii::$app->user->id])->one();
                    $properties = \app\models\Properties::find()->select('id')->where(['city' => $roles->city])->all();
                    break;
                case 8:
                    $roles = \app\models\OperationsProfile::find()->select('state')->where(['operations_id' => Yii::$app->user->id])->one();
                    $properties = \app\models\Properties::find()->select('id')->where(['state' => $roles->state])->all();
                    break;
            }
        } else {

            $assinee1 = \app\models\OwnerProfile::find()->select('owner_id')->where(['operation_id' => Yii::$app->user->id])->all();
            $listt = '';
            foreach ($assinee1 as $key => $value) {
                if ($listt == '') {
                    $listt = $value->owner_id;
                } else {
                    $listt .= ',' . $value->owner_id;
                }
            }
            $properties = \app\models\Properties::find()->select('id')->where(['owner_id IN (' . $listt . ')'])->all();
        }
        if (count($properties) != 0) {
            $property_list = '';
            foreach ($properties as $key => $value) {
                if ($property_list == '') {
                    $property_list = $value->id;
                } else {
                    $property_list = $property_list . ',' . $value->id;
                }
            }

            //$tenants=\app\models\TenantAgreements::find()->select('tenant_id')->where('parent_id IN ('.$property_list.')')->all();
            $tenants = \app\models\TenantAgreements::find()->select('tenant_id')->all();
            if (count($tenants) != 0) {
                $tenant_list = '';
                foreach ($tenants as $key => $value) {
                    if ($tenant_list == '') {
                        $tenant_list = $value->tenant_id;
                    } else {
                        $tenant_list = $tenant_list . ',' . $value->tenant_id;
                    }
                }
                $assinee = \app\models\Users::find()->where('id IN (' . $tenant_list . ')')->andWhere('full_name LIKE "%' . $str . '%"')->all();
            } else {
                $assinee = \app\models\Users::find()->where(['id' => 0])->all();
            }
        } else {
            $assinee = \app\models\Users::find()->where(['id' => 0])->all();
        }
        $tenant_array = array();
        foreach ($assinee as $key => $value) {
            $tdetails = \app\models\LeadsTenant::find()->where(['email_id' => $value->login_id])->one();
            if ($tdetails->address == '') {
                $taddress = 'NA';
            } else {
                $taddress = $tdetails->address;
            }
            $tenant_array[] = array('id' => $value->id, 'name' => $value->full_name, 'email' => $tdetails->email_id, 'phone' => $tdetails->contact_number, 'address' => $taddress);
        }
        echo json_encode($tenant_array);
    }

    public function actionGetallproperties()
    {

        $str = str_replace(" ", "%%", $_POST['str']);
        $userAssignments = \app\models\Users::find()->where(['id' => Yii::$app->user->id])->one();
        if ($userAssignments) {

            $condition1 = '';
            $role = $userAssignments->role;
            switch ($role) {
                case 5:
                    $roles = \app\models\OperationsProfile::find()->select('region')->where(['operations_id' => Yii::$app->user->id])->one();
                    $properties = \app\models\Properties::find()->select('id,property_name')->where(['region' => $roles->region])->andWhere('property_name like "%' . $str . '%"')->all();
                    break;
                case 6:
                    $roles = \app\models\OperationsProfile::find()->select('region')->where(['operations_id' => Yii::$app->user->id])->one();
                    $properties = \app\models\Properties::find()->select('id,property_name')->where(['region' => $roles->region])->andWhere('property_name like "%' . $str . '%"')->all();
                    break;
                case 7:
                    $roles = \app\models\OperationsProfile::find()->select('city')->where(['operations_id' => Yii::$app->user->id])->one();
                    $properties = \app\models\Properties::find()->select('id,property_name')->where(['city' => $roles->city])->andWhere('property_name like "%' . $str . '%"')->all();
                    break;
                case 8:
                    $roles = \app\models\OperationsProfile::find()->select('state')->where(['operations_id' => Yii::$app->user->id])->one();
                    $properties = \app\models\Properties::find()->select('id,property_name')->where(['state' => $roles->state])->andWhere('property_name like "%' . $str . '%"')->all();
                    break;
            }
        } else {

            $assinee1 = \app\models\OwnerProfile::find()->select('owner_id')->where(['operation_id' => Yii::$app->user->id])->andWhere('property_name like "%' . $str . '%"')->all();
            $listt = '';
            foreach ($assinee1 as $key => $value) {
                if ($listt == '') {
                    $listt = $value->owner_id;
                } else {
                    $listt .= ',' . $value->owner_id;
                }
            }
            $properties = \app\models\Properties::find()->select('id,property_name')->where(['owner_id IN (' . $listt . ')'])->all();
        }
        $property_array = array();
        foreach ($properties as $key => $value) {
            $property_array = array('id' => $value->id, 'name' => $value->property_name);
        }
        echo json_encode($property_array);
    }

    public function actionAdhocdetails($id)
    {
        $id = base64_decode($id);
        $model = \app\models\AdhocRequests::findOne(['id' => $id]);
        $modelComments = \app\models\AdhocComments::find()->where(['adhoc_id' => $id])->orderBy('id Desc')->limit(5)->all();
        $payment_types = \app\models\PaymentModeType::find()->all();
        $modelPayment = \app\models\AdhocPayments::findOne(['adhoc_id' => $id]);
        $modelAttachments = \app\models\AdhocAttachments::findAll(['adhoc_id' => $id]);
        $modelTenantPayments = \app\models\TenantPayments;
        $tenant_id = $model->tenant_id;
        if ($modelPayment === null) {
            $modelPayment = new \app\models\AdhocPayments;
        }
        if ($model !== null) {
            if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
                Yii::$app->response->format = 'json';
                return \yii\bootstrap\ActiveForm::validate($model);
            } else if ($model->load(Yii::$app->request->post())) {
                $model->payment_status = $_POST['AdhocRequests']['payment_status'];
                $model->created_date = date("Y-m-d h:i:s", strtotime($_POST['AdhocRequests']['created_date']));;
                $model->save();

                if (isset($_FILES['images'])) {
                    $address_proof = $_FILES['images'];
                } else {
                    $address_proof = array();
                }
                if (!empty($address_proof)) {

                    foreach ($address_proof['tmp_name'] as $key => $value) {

                        $address_proofobj = new \app\models\AdhocAttachments;
                        $name_address_proof = (string) date('ymdHis') . rand() . $address_proof['name'][$key];
                        $tmp_file = (string) $value;
                        move_uploaded_file($value, 'uploads/adhoc/' . $name_address_proof);
                        $address_proofobj->url = 'uploads/adhoc/' . $name_address_proof;
                        $address_proofobj->adhoc_id = $id;
                        $address_proofobj->save(false);
                    }
                }


                if ($_POST['AdhocPayments']['type_of_payment'] != '' && $_POST['AdhocPayments']['amount'] != 0 && $_POST['AdhocPayments']['transaction_id'] != '' && $_POST['AdhocPayments']['amount'] != '') {
                    if (\app\models\AdhocPayments::findOne(['adhoc_id' => $id]) === null) {
                        $adhocPayments = $_POST['AdhocPayments'];
                        foreach ($adhocPayments as $key => $value) {
                            $modelPayment->$key = $value;
                        }
                    }


                    $modelPayment->adhoc_id = $id;
                    $modelPayment->created_date = date('Y-m-d h:i:s', strtotime($_POST['AdhocPayments']['created_date']));
                    $modelPayment->type_of_payment = $_POST['AdhocPayments']['type_of_payment'];
                    $modelPayment->amount = $_POST['AdhocPayments']['amount'];
                    $modelPayment->transaction_id = $_POST['AdhocPayments']['transaction_id'];

                    $modelPayment->save(false);
                }

                // return $this->redirect('adhocrequests');
                return $this->render('adhocdetails', [
                    'model' => $model,
                    'comments' => $modelComments,
                    'payment_type' => $payment_types,
                    'modelPayment' => $modelPayment,
                    'modelAttachments' => $modelAttachments,
                ]);
            } else {
                return $this->render('adhocdetails', [
                    'model' => $model,
                    'comments' => $modelComments,
                    'payment_type' => $payment_types,
                    'modelPayment' => $modelPayment,
                    'modelAttachments' => $modelAttachments,
                ]);
            }
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionFinancialreporting($start_date = null, $end_date = null, $range = null)
    {
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand('
      Select * FROM properties WHERE status = "1"');
        $properties = $command->queryAll();

        $commanda = $connection->createCommand('Select * FROM users WHERE user_type = "5" AND status = "1"');
        $advisors = $commanda->queryAll();
        //echo "<pre>";print_r($properties);echo "</pre>";die;
        if ($start_date == null) {
            $start_date = Date('Y-m-d');
        } else {
            $start_date = Date('Y-m-d', strtotime($start_date));
        }
        if ($end_date == null) {
            $end_date = Date('Y-m-d');
        } else {
            $end_date = Date('Y-m-d', strtotime($end_date));
        }
        if ($range == null) {
            $range = 'daily';
        }

        $duration = '';
        switch ($range) {
            case 'daily':
                $duration = 1;
                break;
            case 'weakly':
                $duration = 7;
                break;
            case 'monthly':
                $duration = 30;
                break;
            case '6 month':
                $duration = 180;
                break;
        }

        $startDateObject = date_create($start_date);
        $datePayments = array();
        $tenantPayments = \app\models\TenantPayments::find()->where("(payment_date>='$start_date' and payment_date<='$end_date') and payment_status=1")->all();
        $ownerPayments = \app\models\OwnerPayments::find()->where("DATE_FORMAT(created_date, '%Y-%m-%d')>='$start_date' and DATE_FORMAT(created_date, '%Y-%m-%d')<='$end_date' and payment_status=1")->all();
        $advisorPayments = \app\models\AdvisorPayments::find()->where("payment_date>='$start_date' and payment_date<='$end_date' and payment_status=1")->all();
        $reporttypes = \app\models\ReportType::find()->all();
        //echo "<pre>";print_r($reporttypes);echo "</pre>";die;
        $tenantPaidRent = Yii::$app->db->createCommand("select count(distinct `tenant_id`) as count, due_date from tenant_payments where due_date>='$start_date' and due_date<='$end_date' and payment_status='1'")->queryAll();
        $tenantUnpaidRent = Yii::$app->db->createCommand("select count(distinct `tenant_id`) as count, due_date from tenant_payments where due_date>='$start_date' and due_date<='$end_date' and payment_status='0'")->queryAll();

        foreach ($tenantPayments as $key => $value) {
            $datePayments["$value->payment_date"]['tenant_payment'][] = $value->total_amount;
        }
        foreach ($ownerPayments as $key => $value) {
            $datePayments[Date('d-M-Y', strtotime($value->created_date))]['owner_payment'][] = $value->payment_amount;
            $datePayments[Date('d-M-Y', strtotime($value->created_date))]['pms_commission'][] = $value->pms_commission;
        }
        foreach ($tenantPaidRent as $key => $value) {
            $datePayments[Date('d-M-Y', strtotime($value['due_date']))]['tenantPaidRent'][] = $value['count'];
        }
        foreach ($tenantUnpaidRent as $key => $value) {
            $datePayments[Date('d-M-Y', strtotime($value['due_date']))]['tenantUnpaidRent'][] = $value['count'];
        }
        foreach ($advisorPayments as $key => $value) {
            $datePayments[Date('d-M-Y', strtotime($value->payment_date))]['advisor_payment'][] = $value->payable_amount;
        }
        $finalData = array();


        foreach ($datePayments as $key => $value) {
            $totalOwnerPayment = 0;
            $totalAdvisorPayment = 0;
            $totalTenantPayment = 0;
            $totalPmsProfit = 0;
            $tenantsPaidRent = 0;
            $tenantsUnpaidRent = 0;
            if (isset($value['tenant_payment'])) {
                foreach ($value['tenant_payment'] as $keytenant => $valuetenant) {
                    $totalTenantPayment += $valuetenant;
                }
            }
            if (isset($value['owner_payment'])) {
                foreach ($value['owner_payment'] as $keyowner => $valueowner) {
                    $totalAdvisorPayment += $valueowner;
                }
            }
            if (isset($value['advisor_payment'])) {
                foreach ($value['advisor_payment'] as $keyadvisor => $valueadvisor) {
                    $totalAdvisorPayment += $valueadvisor;
                }
            }
            if (isset($value['tenantPaidRent'])) {
                foreach ($value['tenantPaidRent'] as $keypaid => $valuepaid) {
                    $tenantsPaidRent += $valuepaid;
                }
            }
            if (isset($value['tenantUnpaidRent'])) {
                foreach ($value['tenantUnpaidRent'] as $keyunpaid => $valueunpaid) {
                    $tenantsUnpaidRent += $valueunpaid;
                }
            }
            if (isset($value['pms_commission'])) {
                foreach ($value['pms_commission'] as $keypms => $valuepms) {
                    $totalPmsProfit += $valuepms;
                }
            }
            $keyTempObject = date_create($key);
            $diff = date_diff($startDateObject, $keyTempObject);
            $keyDiff = floor($diff->days / $duration);

            if (isset($finalData[$keyDiff])) {
                $finalData[$keyDiff]['tenant_payment'] += $totalTenantPayment;
                $finalData[$keyDiff]['owner_payment'] += $totalOwnerPayment;
                $finalData[$keyDiff]['advisor_payment'] += $totalAdvisorPayment;
                $finalData[$keyDiff]['pms_commission'] += $totalPmsProfit;
                $finalData[$keyDiff]['tenantPaidRent'] += $tenantsPaidRent;
                $finalData[$keyDiff]['tenantUnpaidRent'] += $tenantsUnpaidRent;
            } else {
                $finalData[$keyDiff]['tenant_payment'] = $totalTenantPayment;
                $finalData[$keyDiff]['owner_payment'] = $totalOwnerPayment;
                $finalData[$keyDiff]['advisor_payment'] = $totalAdvisorPayment;
                $finalData[$keyDiff]['pms_commission'] = $totalPmsProfit;
                $finalData[$keyDiff]['tenantPaidRent'] = $tenantsPaidRent;
                $finalData[$keyDiff]['tenantUnpaidRent'] = $tenantsUnpaidRent;
            }
        }

        return $this->render('financialreporting', array('finalData' => $finalData, 'reporttypes' => $reporttypes, 'properties' => $properties, 'advisors' => $advisors));
    }

    public function actionPropertylistingdetails($id)
    {
        $id = base64_decode($id);
        $propertyName = Yii::$app->userdata->getPropertyNameById($id);
        $property_type = Yii::$app->userdata->getPropertyTypeId($id);
        if ($property_type == '3') {
            $listing = \app\models\PropertyListing::findOne(['property_id' => $id]);
        } else {
            $listing = \app\models\ChildPropertiesListing::findAll(['main' => $id]);
        }

        return $this->render('propertylistingdetail', array('Name' => $propertyName, 'Type' => $property_type, 'listing' => $listing));
    }

    public function actionEditlisting($property_id, $type, $main)
    {

        if ($type == 3) {
            $model = \app\models\PropertyListing::findOne(['property_id' => $property_id]);
            $modelListing = \app\models\Properties::findOne(['id' => $property_id]);
            $return = [
                'model' => $model,
                'type' => $type,
                'modelListing' => $modelListing
            ];
        } else {


            $model = \app\models\ChildPropertiesListing::findOne(['child_id' => $property_id]);

            $modelListing = \app\models\ChildProperties::findOne(['id' => $property_id]);

            $return = [
                'model' => $model,
                'type' => $type,
                'modelListing' => $modelListing
            ];
        }

        if ($model !== null) {
            if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
                Yii::$app->response->format = 'json';
                return \yii\bootstrap\ActiveForm::validate($model);
            } else if ($model->load(Yii::$app->request->post())) {
                if ($model->save(false)) {
                    if ($type != 3) {
                        $modelListing->status = $_POST['ChildProperties']['status'];
                        $status = $_POST['ChildProperties']['status'];
                        if ($status == '1') {
                            $model2 = \app\models\PropertyListing::findOne(['property_id' => $model->main]);
                            $model2->status = '1';
                            $model2->save(false);
                        } else {
                            $model3 = \app\models\ChildProperties::find()->where(['main' => $model->main, 'status' => '1'])->all();
                            if (count($model3) == 1) {
                                $model2 = \app\models\PropertyListing::findOne(['property_id' => $model->main]);
                                $model2->status = '0';
                                $model2->save(false);
                            }

                            ///////////////////////////////////////////////////////////////////////////
                            $parentPropChngStatus = 1;
                            $parentPropId = $model->main;

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
                                    $mainProp->save(false);
                                }
                            }
                            ///////////////////////////////////////////////////////////////////////////////
                        }
                        $modelListing->save(false);
                        Yii::$app->userdata->checkCoProListingStatus($model->main);
                    } else {
                        $model->status = $_POST['PropertyListing']['status'];
                        $model->save(false);
                    }


                    return $this->redirect('propertylistingdetails?id=' . $main);
                } else {
                    return $this->renderAjax('_formlisting', $return);
                }
            } else {
                return $this->renderAjax('_formlisting', $return);
            }
        } else {

            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionEditlisting2($property_id, $type, $main)
    {

        if ($type == 3) {
            $model = \app\models\PropertyListing::findOne(['property_id' => $property_id]);
            $modelListing = \app\models\Properties::findOne(['id' => $property_id]);
            $return = [
                'model' => $model,
                'type' => $type,
                'modelListing' => $modelListing
            ];
        } else {
            $model = \app\models\ChildPropertiesListing::findOne(['child_id' => $property_id]);
            $modelListing = \app\models\ChildProperties::findOne(['id' => $property_id]);
            $return = [
                'model' => $model,
                'type' => $type,
                'modelListing' => $modelListing
            ];
        }

        if ($model !== null) {
            if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
                if ($model->save(false)) {
                    if ($type == 4) {
                        $modelListing->status = $_POST['ChildProperties']['status'];
                        $status = $_POST['ChildProperties']['status'];
                        if ($status == '1') {
                            $model2 = \app\models\PropertyListing::findOne(['property_id' => $model->main]);
                            $model2->status = '1';
                            $model2->save(false);
                        } else {
                            $model3 = \app\models\ChildProperties::find()->where(['main' => $model->main, 'status' => '1'])->all();
                            if (count($model3) == 1) {
                                $model2 = \app\models\PropertyListing::findOne(['property_id' => $model->main]);
                                $model2->status = '0';
                                $model2->save(false);
                            }

                            ///////////////////////////////////////////////////////////////////////////
                            $parentPropChngStatus = 1;
                            $parentPropId = $model->main;

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
                                    $mainProp->save(false);
                                }
                            }
                            ///////////////////////////////////////////////////////////////////////////////
                        }
                        $modelListing->save(false);
                        Yii::$app->userdata->checkCoProListingStatus($model->main);
                    }
                    if ($type == 5) {
                        $modelListing->status = $_POST['ChildProperties']['status'];
                        $status = $_POST['ChildProperties']['status'];
                        if ($status == '1') {
                            $model2 = \app\models\PropertyListing::findOne(['property_id' => $model->main]);
                            $model2->status = '1';
                            $model2->save(false);
                        } else {
                            $model3 = \app\models\ChildProperties::find()->where(['main' => $model->main, 'status' => '1'])->all();
                            if (count($model3) == 1) {
                                $model2 = \app\models\PropertyListing::findOne(['property_id' => $model->main]);
                                $model2->status = '0';
                                $model2->save(false);
                            }
                        }
                        $modelListing->save(false);
                        Yii::$app->userdata->checkPgProListingStatus($model->main);
                    } else {
                        $model->status = $_POST['PropertyListing']['status'];
                        $model->save(false);
                    }
                }
            }
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionActivateowner($ownerid = false)
    {
        if (isset($_POST['id'])) {
            $ownerid = $_POST['id'];
        }

        $users1 = \app\models\Users::findOne(['id' => $ownerid]);

        $profileOwner = \app\models\OwnerProfile::findOne(['owner_id' => $ownerid]);

        $profile = array('bank_name' => 'Bank Name', 'bank_branchname' => 'Bank Branchname', 'bank_ifcs' => 'Bank IFSC', 'bank_account_number' => 'Bank Account Number', 'pan_number' => 'Pan Number', 'cancelled_check' => 'Cancelled Cheque', 'address_line_1' => 'Address Line 1', 'address_line_2' => 'Address Line 2');
        $profileNumeric = array('city_name' => 'City name', 'state' => 'State', 'phone' => 'Mobile Number');
        $assignValid = array('branch_code' => 'Branch', 'operation_id' => 'Support Person', 'sales_id' => 'Sales Person');
        $users = array('full_name' => 'Full Name');
        $error = 0;

        $countEmer = \app\models\EmergencyProofs::find()->where(['user_id' => $ownerid])->all();
        $countEmer = count($countEmer);

        $countId = \app\models\UserIdProofs::find()->where(['user_id' => $ownerid])->all();
        $countId = count($countId);

        foreach ($profile as $key1 => $value1) {
            if (trim($profileOwner->$key1) == '') {
                $error = 1;
                $message = $value1 . ' is required to activate owner';
            }
        }
        foreach ($profileNumeric as $key1 => $value1) {
            if (trim($profileOwner->$key1) == '' || trim($profileOwner->$key1) == '0') {
                $error = 1;
                $message = $value1 . ' is required to activate owner';
            }
        }

        foreach ($users as $key => $value) {

            if (trim($users1->$key) == '') {
                $error = 1;
                $message = $value . ' is required to activate owner';
            }
        }

        foreach ($assignValid as $key1 => $value1) {
            if (trim($profileOwner->$key1) == '' || trim($profileOwner->$key1) == '0') {
                $error = 1;
                $message = 'Please assign a valid Branch, Sales & Operations person before activating the lead.';
            }
        }

        if ($countId == 0) {
            if (!empty($_FILES)) {

                if (isset($_FILES['address_proof'])) {
                    $address_proof = $_FILES['address_proof'];
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
                                    $address_proofobj->user_id = $ids;
                                    $address_proofobj->created_by = $ids;
                                    $address_proofobj->created_date = date('Y-m-d H:i:s');
                                    $address_proofobj->save(false);
                                }
                            }
                        }
                    }
                } else {
                    $error = 1;
                    $message = 'Please upload a valid id proof before activating property owner';
                }
            } else {
                $error = 1;
                $message = 'Please upload a valid id proof before activating property owner';
                //$message = 'empty';
            }
        }


        if ($error == 1) {
            return json_encode(array('success' => 0, 'msg' => $message));
        } else {
            $userss = \app\models\Users::findOne(['id' => $ownerid]);
            $userss->status = 1;
            $profileOwner->status = 1;
            $profileOwner->save(false);
            if ($userss->save(false)) {
                if ($userss->mail_status == 0) {
                    $password = Yii::$app->userdata->passwordGenerate();
                    $command1 = Yii::$app->db->createCommand('update users SET password="' . md5($password) . '" where id="' . $ownerid . '"');
                    $command1->execute();
                    $to = Yii::$app->userdata->getUserEmailById($ownerid);
                    $subject = "Your Easyleases Credentials";
                    $msg = "Hello " . Yii::$app->userdata->getFullNameById($ownerid) . "<br/><br/>Congratulations, your profile has been setup on Easylease website.<br/><br/>Please use following credentials to login to Easyleases site:<br/><br/>User Name: $to<br/>Password: $password<br/><br/>With Regards,<br/>EasyLeases Team <br/><img src='" . Url::home(true) . "images/property_logo.png' alt='' width='150px' height='40px'>";
                    $mail = Yii::$app->userdata->doMail($to, $subject, $msg);
                    $userss->mail_status = '1';
                    $userss->save(false);
                }
                return json_encode(array('success' => 1, 'msg' => 'success'));
            } else {
                return json_encode(array('success' => 0, 'msg' => 'Please save before activation'));
            }
        }
    }

    public function actionDeactivateowner()
    {
        $ownerid = $_POST['id'];
        $profileOwner = \app\models\OwnerProfile::findOne(['owner_id' => $ownerid]);
        $profileOwner->status = 0;
        $profileOwner->save(false);
        if (!Yii::$app->userdata->getOwnerAllPropertyStatus($ownerid)) {
            $command = Yii::$app->db->createCommand('update users SET status="0" where id="' . $ownerid . '"');
            if ($command->execute()) {
                Yii::$app->userdata->deactivateallproperty($ownerid);
            }
            return true;
        } else {
            return false;
        }
    }

    public function actionActivateproperty()
    {
        $property = \app\models\Properties::findOne(['id' => $_POST['val']]);
        // print_r($property);
        // die;
        //return json_encode(Array('success'=>'1','msg'=>$_POST['status']));
        $property->status = $_POST['status'];
        if ($_POST['status'] == 0) {
            if (!Yii::$app->userdata->getPropertyBookedStatus($_POST['val'])) {
                $property->save(false);
                if (!Yii::$app->userdata->checkAllActiveProperties($_POST['val'])) {
                    $user = \app\models\Users::findOne(['id' => $property->owner_id]);
                    $user->status = 0;
                    $user->save(false);
                }
                return json_encode(array('success' => '1', 'msg' => 'success'));
            } else {
                return json_encode(array('success' => '0', 'msg' => 'Some Booked Property Is there So Property Can\'t be deactivated'));
            }
        } else {
            $error = 0;
            $agreements = \app\models\PropertyAgreements::findOne(['property_id' => $_POST['val']]);
            $properties = array('property_name' => 'Property Name', 'property_description' => 'Property Description', 'address_line_1' => 'Address', 'address_line_2' => 'Address');
            $propertyNumer = array('property_type' => 'Property Type', 'lat' => 'Latitude', 'lon' => 'Longitude', 'branch_code' => 'Branch', 'city' => 'City', 'state' => 'State', 'pincode' => 'Pincode');
            $agreement = array('agreement_type' => 'Agreement Type', 'notice_peroid' => 'Notice Period', 'min_contract_peroid' => 'Minimum Contract Period', 'pms_commission' => 'PMS Fees', 'contract_start_date' => 'Contract Start Date', 'contract_end_date' => 'Contract End Date', 'agreement_url' => 'Please attach agreement copy before activating the property');

            /* $agreement=Array('agreement_type'=>'Agreement Type','notice_peroid'=>'Notice Period','min_contract_peroid'=>'Minimum Contract Period','furniture_rent'=>'Furniture Rent','pms_commission'=>'PMS Fees','contract_start_date'=>'Contract Start Date','contract_end_date'=>'Contract End Date','agreement_url'=>'Please attach agreement copy before activating the property');
              echo $agreement;die; */

            $date_check = array('contract_start_date' => 'Contract Start Date', 'contract_end_date' => 'Contract End Date');
            $connection = Yii::$app->getDb();
            $command = $connection->createCommand('select pm.id from property_attribute_map as pm left join properties_attributes as pa on pm.attr_id=pa.id where pm.property_id=' . $_POST['val'] . ' and pa.type="1"');
            $countSimple = count($command->queryAll());

            $command1 = $connection->createCommand('select pm.id from property_attribute_map as pm left join properties_attributes as pa on pm.attr_id=pa.id where pm.property_id=' . $_POST['val'] . ' and pa.type="2"');
            // print_r($$command1);
            $countComplex = count($command1->queryAll());

            $images = \app\models\PropertyImages::findAll(['property_id' => $_POST['val']]);
            $images = count($images);

            if ($property->property_type == '3') {
                $properties['flat_bhk'] = 'Flat BHK';
                $properties['flat_area'] = 'Flat Area';
            } else {
                $childProperties = \app\models\ChildProperties::findAll(['main' => $_POST['val']]);
            }

            foreach ($properties as $key => $value) {
                if (trim($property->$key) == '') {
                    $error = 1;
                    $msg = $value . " Is Required";
                }
            }
            foreach ($propertyNumer as $key => $value) {
                if (trim($property->$key) == '' || $property->$key == '0') {
                    $error = 1;
                    $msg = $value . " Is Required";
                }
            }

            if (count($agreements) == 0) {
                $modelPropertyAgreements = new \app\models\PropertyAgreements;
                if ($modelPropertyAgreements->load(Yii::$app->request->post())) {
                    $model = \app\models\Properties::findOne($modelPropertyAgreements->property_id);
                    $agree_url = UploadedFile::getInstance($modelPropertyAgreements, 'agreement_url');
                    if ($agree_url) {
                        $agreement = date('ymdHis') . $agree_url;
                        $agree_url->saveAs('uploads/' . $agreement);

                        // $modelPropertyAgreements->agreement_url =  Url::home(true). 'uploads/'.$agreement ;
                        $modelPropertyAgreements->agreement_url = 'uploads/' . $agreement;
                    }
                    $modelPropertyAgreements->created_by = Yii::$app->user->id;
                    $modelPropertyAgreements->created_date = date('Y-m-d H:i:s');
                    $modelPropertyAgreements->save(false);
                    $error = 0;
                } else {
                    $error = 1;
                    $msg = 'Agreement Need To Be Created First';
                }
            } else {
                if ($agreements->agreement_type == 2) {
                    $agreement['rent_start_date'] = 'Rent Start Date';
                }
                foreach ($agreement as $key1 => $value1) {
                    if (trim($agreements->$key1) == '') {
                        $error = 1;
                        if ($key1 == 'agreement_url') {
                            $msg = $value1;
                        } else {
                            $msg = $value1 . " Is Required123";
                        }
                    }
                }
                foreach ($date_check as $key2 => $value2) {
                    if (trim($agreements->$key2) == '' || trim($agreements->$key2) == '00-00-0000' || trim($agreements->$key2) == '01-01-1970') {
                        $error = 1;
                        $msg = "Invalid Value For " . $value2;
                    }
                }
            }





            if ($countSimple == 0) {
                $error = 1;
                $msg = "Atleast One Simple Attribute Required";
            }

            if ($countComplex == 0) {
                $error = 1;
                $msg = "Atleast One Complex Attribute Required";
            }

            if ($images == 0) {
                $error = 1;
                $msg = "Atleast One Property Image Required";
            }

            if ($property->property_type == '1' || $property->property_type == '2') {
                if (count($childProperties) < 2) {

                    $msg = "No Bed Or Room Added";
                }
            }



            if ($error == 1) {

                // print_r(Array( $error,$msg));
                return json_encode(array('success' => '0', 'msg' => $msg));
            } else {
                if (!Yii::$app->userdata->getOwnerStatusByProperty($_POST['val'])) {
                    $getOwner = \app\models\Properties::findOne(['id' => $_POST['val']]);

                    $data = json_decode($this->actionActivateowner($getOwner->owner_id));
                    if ($data->success == 1) {
                        $property_name = Yii::$app->userdata->getPropertyNameById($_POST['val']);
                        $owner_name = Yii::$app->userdata->getFullNameById($property->owner_id);
                        $subject = "Your property " . $property_name . " has been activated and will be listed on the website soon";
                        $msg = "Hello " . $owner_name . "<br/><br/>Congratulations, your property " . $property_name . " has been setup on Easyleases website and will be listed soon.<br/><br/>Your Service Agreement for this property has been attached for your reference.<br/><br/>With Regards,<br/>EasyLeases Team<br/><img src='" . Url::home(true) . "images/property_logo.png' alt='' width='150px' height='40px'>";
                        Yii::$app->userdata->doMail(Yii::$app->userdata->getLoginIdById($property->owner_id), $subject, $msg);
                        $property->save(false);
                        return json_encode(array('success' => '1', 'msg' => 'success'));
                    } else {
                        return json_encode(array('success' => '0', 'msg' => 'Please activate property owner before activating this property.'));
                    }
                } else {
                    $property_name = Yii::$app->userdata->getPropertyNameById($_POST['val']);
                    $owner_name = Yii::$app->userdata->getFullNameById($property->owner_id);
                    $subject = "Your property " . $property_name . " has been activated and will be listed on the website soon";
                    $msg = "Hello " . $owner_name . "<br/><br/>Congratulations, your property " . $property_name . " has been setup on Easyleases website and will be listed soon.<br/><br/>Your Service Agreement for this property has been attached for your reference.<br/><br/>With Regards,<br/>EasyLeases Team<br/><img src='" . Url::home(true) . "images/property_logo.png' alt='' width='150px' height='40px'>";
                    Yii::$app->userdata->doMail(Yii::$app->userdata->getLoginIdById($property->owner_id), $subject, $msg);
                    $property->save(false);
                    return json_encode(array('success' => '1', 'msg' => 'success'));
                }
            }
        }
    }

    public function actionActivateadvisor()
    {
        $id = $_POST['id'];
        $user = \app\models\Users::findOne(['id' => $id]);
        $action = $_POST['action'];
        if ($action == '1') {
            $profileOwner = \app\models\AdvisorProfile::findOne(['advisor_id' => $_POST['id']]);
            $profile = array('bank_name' => 'Bank Name', 'bank_branchname' => 'Bank Branchname', 'bank_ifcs' => 'Bank IFSC', 'account_number' => 'Bank Account Number', 'pan_number' => 'Pan Number', 'address_line_1' => 'Address Line 1', 'address_line_2' => 'Address Line 2');
            $number = array('city' => 'City', 'state' => 'State', 'phone' => 'Mobile Number');
            $users = array('full_name' => 'Full Name');
            $assignValid = array('branch_code' => 'Branch', 'operation_id' => 'Support Person', 'sales_id' => 'Sales Person');
            $agreements = array('start_date' => 'Contract start date', 'end_date' => 'Contract end date', 'agreement_doc' => 'Agreement Document');
            $error = 0;
            $agreementAdvsior = \app\models\AdvisorAgreements::findOne(['advisor_id' => $_POST['id']]);

            $countId = \app\models\UserIdProofs::find()->where(['user_id' => $_POST['id']])->all();
            $countId = count($countId);

            foreach ($profile as $key1 => $value1) {
                if (trim($profileOwner->$key1) == '') {
                    $error = 1;
                    $message = $value1 . ' is required to activate Advisor';
                }
            }
            foreach ($agreements as $key1 => $value1) {
                if (trim($agreementAdvsior->$key1) == '') {
                    $error = 1;
                    $message = $value1 . ' is required to activate Advisor';
                }
            }

            foreach ($users as $key => $value) {
                if (trim($user->$key) == '') {
                    $error = 1;
                    $message = $value . ' is required to activate Advisor';
                }
            }

            foreach ($number as $key2 => $value2) {
                if (trim($profileOwner->$key2) == '') {
                    $error = 1;
                    $message = $value2 . ' is required to activate Advisor';
                }
            }

            foreach ($assignValid as $key1 => $value1) {
                if (trim($profileOwner->$key1) == '' || trim($profileOwner->$key1) == '0') {
                    $error = 1;
                    $message = 'Please assign a valid Branch, Sales & Operations person before activating the lead.';
                }
            }

            if (count($agreements) != '0') {
                foreach ($agreements as $key3 => $value3) {
                    if (date('Y-m-d', strtotime($agreementAdvsior->$key3)) == '') {
                        $error = 1;
                        $message = $value3 . ' is not a valid date';
                    }
                }

                if (strtotime($agreementAdvsior->end_date) <= strtotime($agreementAdvsior->start_date)) {
                    $error = 1;
                    $message = 'End date should be greater than start date';
                }
            } else {
                $error = 1;
                $message = 'Advisor contract need to be created first';
            }


            if ($countId == 0) {
                $error = 1;
                $message = 'Advisor cannot be activated as address proof document is not updated.';
            }
            if ($error == 0) {
                $user->status = $action;

                if ($user->save(false)) {
                    if ($user->mail_status == '0') {
                        $password = Yii::$app->userdata->passwordGenerate();
                        $command1 = Yii::$app->db->createCommand('update users SET password="' . md5($password) . '" where id="' . $id . '"');
                        $command1->execute();
                        $to = Yii::$app->userdata->getUserEmailById($_POST['id']);
                        $subject = "Welcome to Easyleases";
                        $msg = "Hello " . Yii::$app->userdata->getFullNameById($_POST['id']) . " <br /><br />Greetings from Easyleases!!!<br/><br/>Congratulation on becoming a valued partner with Easyleases. Your login credentials are provided below. You may start referring clients and earn referral fees.<br/><br/>For your easy reference, your contract with Easyleases has been attached. <br/><br/>  <br/>   <b>Username</b>:" . $to . "<br/> <b>Password</b>:" . $password . "<br/><br/>With Regards,<br/>Easyleases Team<br/><img src='" . Url::home(true) . "images/property_logo.png' alt='' width='150px' height='40px'>";

                        $mail = Yii::$app->userdata->doMailWithAttachment($to, $subject, $msg, $agreementAdvsior->agreement_doc);
                        $user->mail_status = '1';
                        $user->save(false);
                    }

                    // return json_encode(Array('success'=>1,'msg'=>'success'));
                }
                return json_encode(array('success' => 1, 'msg' => 'Success'));
            } else {
                return json_encode(array('success' => 0, 'msg' => $message));
            }
        } else {
            $user->status = $action;
            $user->save(false);
            $users = \app\models\Users::findAll(['refered_by' => $id]);
            if (count($users) != 0) {
                foreach ($users as $key => $value) {
                    $value->refered_by = 0;
                    $value->save(false);
                }
            }
            return json_encode(array('success' => 1, 'msg' => 'Success'));
        }
    }

    public function actionRequestmaintenanceapproval()
    {
        $searchModelOwnerLeads = new \app\models\MaintenanceRequest();

        $dataProviderOwner = $searchModelOwnerLeads->search(Yii::$app->request->queryParams);

        return $this->render('maintenance_approval', [
            'searchModelOwnerLeads' => $searchModelOwnerLeads,
            'dataProviderOwner' => $dataProviderOwner,
        ]);
    }

    public function actionCreatemaintenance()
    {
        $model = new \app\models\MaintenanceRequest();
        $requestStatus = \app\models\RequestStatus::find()->All();
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = 'json';
            return \yii\bootstrap\ActiveForm::validate($model);
        } else if ($model->load(Yii::$app->request->post())) {


            $model->created_by = Yii::$app->user->id;
            if ($model->save(false)) {

                if (isset($_FILES['attachment'])) {

                    if ($_FILES['attachment']['size'] != 0) {
                        $name_profile_image = date('ymdHis') . $_FILES['attachment']['name'];
                        if (move_uploaded_file($_FILES['attachment']['tmp_name'], 'uploads/requests/' . $name_profile_image)) {
                            $modelAttachment = new \app\models\MaintenanceAttachment();
                            $modelAttachment->service_id = $model->id;
                            // $modelAttachment->attachment=Url::home(true).'uploads/requests/'.$name_profile_image;
                            $modelAttachment->attachment = 'uploads/requests/' . $name_profile_image;
                            $modelAttachment->save(false);
                        }
                    }
                }




                return $this->redirect('requestmaintenanceapproval');
            } else {
                return $this->renderAjax('create_maintenance', ['model' => $model, 'request' => $requestStatus]);
            }
        } else {
            return $this->renderAjax('create_maintenance', ['model' => $model, 'request' => $requestStatus]);
        }
    }

    public function actionMaintenancedetails($id)
    {
        $id = base64_decode($id);
        $model = \app\models\MaintenanceRequest::findOne(['id' => $id]);
        $requestStatus = \app\models\RequestStatus::find()->All();
        $attachments = \app\models\MaintenanceAttachment::findAll(['service_id' => $id]);
        $maintenancePayments = \app\models\MaintenancePayments::findOne(['service_id' => $id]);
        $MaintenanceRequestConversation = \app\models\MaintenanceRequestConversation::find()->where(['service_request_id' => $id])->orderBy('id desc')->limit(5)->All();
        if (!$maintenancePayments) {
            $maintenancePayments = new \app\models\MaintenancePayments();
            $new = 1;
        }
        if ($model) {
            if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
                Yii::$app->response->format = 'json';
                return \yii\bootstrap\ActiveForm::validate($model);
            } else if ($model->load(Yii::$app->request->post())) {

                if ($model->save(false)) {
                    if (isset($_FILES['attachment'])) {
                        foreach ($_FILES['attachment']['tmp_name'] as $key => $value) {

                            if ($_FILES['attachment']['size'][$key] != 0) {
                                $name_profile_image = date('ymdHis') . $_FILES['attachment']['name'][$key];
                                if (move_uploaded_file($value, 'uploads/requests/' . $name_profile_image)) {
                                    $modelAttachment = new \app\models\MaintenanceAttachment();
                                    $modelAttachment->service_id = $model->id;
                                    // $modelAttachment->attachment=Url::home(true).'uploads/requests/'.$name_profile_image;
                                    $modelAttachment->attachment = 'uploads/requests/' . $name_profile_image;
                                    $modelAttachment->save(false);
                                }
                            }
                        }
                    }
                    if (isset($new)) {
                        $maintenancePayments->service_id = $id;
                    }
                    $maintenancePayments->amount = $_POST['MaintenancePayments']['amount'];
                    $maintenancePayments->transaction_id = $_POST['MaintenancePayments']['transaction_id'];
                    $maintenancePayments->type_of_payment = $_POST['MaintenancePayments']['type_of_payment'];
                    $maintenancePayments->date_of_payment = Date('Y-m-d', strtotime($_POST['MaintenancePayments']['date_of_payment']));

                    $maintenancePayments->save(false);
                } else {
                    return $this->render('edit_maintenance', ['model' => $model, 'request' => $requestStatus, 'attachments' => $attachments, 'payment' => $maintenancePayments, 'Comments' => $MaintenanceRequestConversation]);
                }
                return $this->redirect('requestmaintenanceapproval');
            } else {
                return $this->render('edit_maintenance', ['model' => $model, 'request' => $requestStatus, 'attachments' => $attachments, 'payment' => $maintenancePayments, 'Comments' => $MaintenanceRequestConversation]);
            }
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionAddmaintenancecomment()
    {
        $maintenanceComments = new \app\models\MaintenanceRequestConversation();
        $maintenanceComments->user_id = Yii::$app->user->id;
        $maintenanceComments->message = $_POST['val'];
        $maintenanceComments->service_request_id = $_POST['service'];
        $comments = array();
        if ($maintenanceComments->save(false)) {
            $MaintenanceRequestConversation = \app\models\MaintenanceRequestConversation::find()->where(['service_request_id' => $_POST['service']])->orderBy('id desc')->limit(5)->All();
            foreach ($MaintenanceRequestConversation as $key => $value) {
                $comments[] = array('message' => $value->message, 'user' => Yii::$app->userdata->getFullNameById($value->user_id), 'date' => Date('d/m/Y', strtotime($value->created_datetime)), 'time' => Date('h:i A', strtotime($value->created_datetime)));
            }
            echo json_encode(array('success' => 1, 'comments' => $comments));
        } else {
            echo json_encode(array('success' => 0));
        }
    }

    public function actionHotleads()
    {
        $connection = Yii::$app->getDb();

        $main_id = Yii::$app->user->id;
        $role = \app\models\Users::find()->select('role')->where(['id' => $main_id])->one();
        $condition1 = '';
        switch ($role->role) {
            case 1:
                $roles = \app\models\SalesProfile::find()->select('region')->where(['sale_id' => $main_id])->one();
                $condition1 = "`region`=$roles->region";
                break;
            case 2:
                $roles = \app\models\SalesProfile::find()->select('region')->where(['sale_id' => $main_id])->one();
                $condition1 = "`region`=$roles->region";
                break;
            case 3:
                $roles = \app\models\SalesProfile::find()->select('city')->where(['sale_id' => $main_id])->one();
                $condition1 = "`city`=$roles->city";
                break;
            case 4:
                $roles = \app\models\SalesProfile::find()->select('state')->where(['sale_id' => $main_id])->one();
                $condition1 = "`state`=$roles->state";
                break;
        }

        $data = array();

        $search = "";
        $requestData = Yii::$app->request->queryParams;
        if (isset($requestData['search'])) {
            $search = str_replace(" ", "%%", trim($requestData['search']));
        }
        //echo $search;die;

        $command = $connection->createCommand('
      Select l.`id`, l.`full_name`, l.`email_id`, l.`contact_number`, l.`address`, l.`follow_up_date_time`,c.city_name ,s.name as state_name,r.name as ref_name,ut.user_type_name from leads_tenant  as l left join cities as c on l.city=c.id left join states as s on l.state=s.id left join ref_status as r on l.ref_status=r.id left join user_types as ut on l.user_type=ut.id where ' . $condition1 . ' and (DATE(l.`created_date`)=CURDATE() OR DATE(l.`created_date`)=CURDATE() - INTERVAL 1 DAY) and (`full_name` like "%' . $search . '%" or `email_id` like "%' . $search . '%" or `address` like "%' . $search . '%" or `contact_number` like "%' . $search . '%" or c.city_name like "%' . $search . '%" or s.name like "%' . $search . '%" or r.name like "%' . $search . '%")

       UNION

       Select l.`id`, l.`full_name`, l.`email_id`, l.`contact_number`, l.`address`, l.`follow_up_date_time`,c.city_name ,s.name as state_name,r.name as ref_name,ut.user_type_name from leads_owner  as l left join cities as c on l.city=c.id left join states as s on l.state=s.id left join ref_status as r on l.ref_status=r.id left join user_types as ut on l.user_type=ut.id where ' . $condition1 . ' and (DATE(l.`created_date`)=CURDATE() OR DATE(l.`created_date`)=CURDATE() - INTERVAL 1 DAY) and (`full_name` like "%' . $search . '%" or `email_id` like "%' . $search . '%" or `address` like "%' . $search . '%" or `contact_number` like "%' . $search . '%" or c.city_name like "%' . $search . '%" or s.name like "%' . $search . '%" or r.name like "%' . $search . '%")

       UNION

       Select l.`id`, l.`full_name`, l.`email_id`, l.`contact_number`, l.`address`, l.`follow_up_date_time`,c.city_name ,s.name as state_name,r.name as ref_name,ut.user_type_name from leads_advisor  as l left join cities as c on l.city=c.id left join states as s on l.state=s.id left join ref_status as r on l.ref_status=r.id left join user_types as ut on l.user_type=ut.id where ' . $condition1 . ' and (DATE(l.`created_date`)=CURDATE() OR DATE(l.`created_date`)=CURDATE() - INTERVAL 1 DAY) and (`full_name` like "%' . $search . '%" or `email_id` like "%' . $search . '%" or `address` like "%' . $search . '%" or `contact_number` like "%' . $search . '%" or c.city_name like "%' . $search . '%" or s.name like "%' . $search . '%" or r.name like "%' . $search . '%" )
       ');

        $applicant_leads = $command->queryAll();
        foreach ($applicant_leads as $key => $value) {
            $data[] = array('id' => $value['id'], 'type' => $value['user_type_name'], 'name' => $value['full_name'], 'email' => $value['email_id'], 'contact' => $value['contact_number'], 'address' => $value['address'], 'city' => $value['city_name'], 'state' => $value['state_name'], 'ref_status' => $value['ref_name'], 'follow_up_date_time' => $value['follow_up_date_time']);
        }

        return $this->render('hotleads', ['data' => $data]);
    }

    public function actionMyaccount()
    {
        $connection = Yii::$app->getDb();
        $main_id = Yii::$app->user->id;
        $userType = \app\models\Users::findOne(['id' => $main_id]);
        if ($userType->user_type == '6') {
            $role = \app\models\Users::find()->select('role')->where(['id' => $main_id])->one();
            $condition1 = '';
            switch ($role->role) {
                case 1:
                    $roles = \app\models\SalesProfile::find()->select('region')->where(['sale_id' => $main_id])->one();
                    $condition1 = "up.`region`=$roles->region";
                    break;
                case 2:
                    $roles = \app\models\SalesProfile::find()->select('region')->where(['sale_id' => $main_id])->one();
                    $condition1 = "up.`region`=$roles->region";
                    break;
                case 3:
                    $roles = \app\models\SalesProfile::find()->select('city')->where(['sale_id' => $main_id])->one();
                    $condition1 = "up.`city`=$roles->city";
                    break;
                case 4:
                    $roles = \app\models\SalesProfile::find()->select('state')->where(['sale_id' => $main_id])->one();
                    $condition1 = "up.`state`=$roles->state";
                    break;
            }
            $data = array();

            $search = "";
            $requestData = Yii::$app->request->queryParams;
            if (isset($requestData['search'])) {
                $search = str_replace(" ", "%%", trim($requestData['search']));
            }

            $command = $connection->createCommand('
      Select u.`id`, u.`full_name`, u.`login_id`, up.`phone`,ut.user_type_name,a.lease_start_date as start_date,a.lease_end_date as end_date,p.property_name from tenant_agreements  as a left join users as u on a.tenant_id=u.id left join user_types as ut on u.user_type=ut.id left join tenant_profile as up on u.id=up.tenant_id left join properties as p on a.property_id=p.id  where ' . $condition1 . ' and( `full_name` like "%' . $search . '%" or `login_id` like "%' . $search . '%" or `phone` like "%' . $search . '%")

       UNION

       Select u.`id`, u.`full_name`, u.`login_id`, up.`phone`,ut.user_type_name,a.contract_start_date as start_date,a.contract_end_date,p.property_name from property_agreements  as a left join users as u on a.owner_id=u.id left join user_types as ut on u.user_type=ut.id left join owner_profile as up on u.id=up.owner_id left join properties as p on a.property_id=p.id where ' . $condition1 . ' and (`phone` like "%' . $search . '%" or `login_id` like "%' . $search . '%" or `phone` like "%' . $search . '%")


       ');


            $payments = $command->queryAll();
            return $this->render('myaccount', ['data' => $payments]);
        } else {
            /* $owners = \app\models\OwnerProfile::find()->where(['operation_id' => $main_id])->all();
              $advisors = \app\models\AdvisorProfile::find()->where(['operation_id' => $main_id])->all();
              $tenants = \app\models\TenantProfile::find()->where(['operation_id' => $main_id])->all(); */
            $role = \app\models\Users::find()->select('role')->where(['id' => $main_id])->one();
            //echo "<pre>";print_r($role);echo "</pre>";die;
            $condition1 = '';
            switch ($role->role) {
                case 5:
                    $roles = \app\models\OperationsProfile::find()->select('region')->where(['operations_id' => $main_id])->one();
                    $condition1 = "up.`region`=$roles->region";
                    break;
                case 6:
                    $roles = \app\models\OperationsProfile::find()->select('region')->where(['operations_id' => $main_id])->one();
                    $condition1 = "up.`region`=$roles->region";
                    break;
                case 7:
                    $roles = \app\models\OperationsProfile::find()->select('city')->where(['operations_id' => $main_id])->one();
                    $condition1 = "up.`city`=$roles->city";
                    break;
                case 8:
                    $roles = \app\models\OperationsProfile::find()->select('state')->where(['operations_id' => $main_id])->one();
                    $condition1 = "up.`state`=$roles->state";
                    break;
            }
            //echo $condition1;die;
            $data = array();

            $search = "";
            $requestData = Yii::$app->request->queryParams;
            if (isset($requestData['search'])) {
                $search = str_replace(" ", "%%", trim($requestData['search']));
            }

            $command = $connection->createCommand('
      Select u.`id`, u.`full_name`, u.`login_id`, up.`phone`,ut.user_type_name,a.lease_start_date as start_date,a.lease_end_date as end_date,p.property_name from tenant_agreements  as a left join users as u on a.tenant_id=u.id left join user_types as ut on u.user_type=ut.id left join tenant_profile as up on u.id=up.tenant_id left join properties as p on a.property_id=p.id  where ' . $condition1 . ' and( `full_name` like "%' . $search . '%" or `login_id` like "%' . $search . '%" or `phone` like "%' . $search . '%")

       UNION

       Select u.`id`, u.`full_name`, u.`login_id`, up.`phone`,ut.user_type_name,a.contract_start_date as start_date,a.contract_end_date,p.property_name from property_agreements  as a left join users as u on a.owner_id=u.id left join user_types as ut on u.user_type=ut.id left join owner_profile as up on u.id=up.owner_id left join properties as p on a.property_id=p.id where ' . $condition1 . ' and (`phone` like "%' . $search . '%" or `login_id` like "%' . $search . '%" or `phone` like "%' . $search . '%")


       ');


            $payments = $command->queryAll();
            //echo "<pre>";print_r($payments);echo "</pre>";die;
            return $this->render('myaccount', ['data' => $payments]);
        }
    }

    public function actionSaveschdule()
    {

        $model = \app\models\FavouriteProperties::findOne($_POST['fav_id']);

        if ($model && Yii::$app->request->isAjax) {

            $model->visit_date = date('Y-m-d', strtotime($_POST['datesch']));
            $model->visit_time = date('H:i:s', strtotime($_POST['timesch']));
            $model->updated_by = Yii::$app->user->id;
            if ($model->status != '2') {
                $model->status = 3;
            }
            $model->save(false);
            echo "done";
        }
    }

    public function actionWalletmanagement()
    {
        $searchModelOwnerLeads = new \app\models\Wallets();

        $dataProviderOwner = $searchModelOwnerLeads->searchOperation(Yii::$app->request->queryParams);
        return $this->render('wallets', [
            'searchModelOwnerLeads' => $searchModelOwnerLeads,
            'dataProviderOwner' => $dataProviderOwner,
        ]);
    }

    public function actionWalletdetails($id)
    {
        $searchModelOwnerLeads = new \app\models\WalletsHistory();
        $user = \app\models\Wallets::findOne(['id' => base64_decode($id)]);
        $user_id = $user->user_id;
        $userType = \app\models\Users::findOne(['id' => $user_id]);
        if ($searchModelOwnerLeads->load(Yii::$app->request->post())) {
            $searchModelOwnerLeads->user_id = $user_id;
            $searchModelOwnerLeads->email_id = Yii::$app->userdata->getUserEmailById($user_id);
            $searchModelOwnerLeads->created_by = Yii::$app->user->id;
            $searchModelOwnerLeads->save(false);


            $wallet = \app\models\Wallets::findOne(['user_id' => $user_id]);
            $wallet->amount = (int) $wallet->amount - (int) $searchModelOwnerLeads->amount;
            $wallet->save(false);
            $searchModelOwnerLeads = new \app\models\WalletsHistory();
            // if($searchModelOwnerLeads->transaction_type=='2'){
            //
            // }
            // if($searchModelOwnerLeads->transaction_type=='3'){
            //
            // }
        }


        if ($userType->user_type == '2') {
            $accountDetails = \app\models\ApplicantProfile::findOne(['applicant_id' => $user_id]);
        } else {
            $accountDetails = \app\models\TenantProfile::findOne(['tenant_id' => $user_id]);
        }
        $dataProviderOwner = $searchModelOwnerLeads->searchOperation(Yii::$app->request->queryParams, $user_id);


        //
        return $this->render('walletsdetail', [
            'searchModelOwnerLeads' => $searchModelOwnerLeads,
            'dataProviderOwner' => $dataProviderOwner,
            'accountDetails' => $accountDetails,
            'userType' => $userType->user_type,
        ]);
    }

    public function actionCheckifsc()
    {
        $ifsc = Yii::$app->request->post('ifsc');
        $ifsc = Yii::$app->userdata->getIFSCValidate($ifsc);
        echo $ifsc;
        exit;
    }

    public function actionRemoveidentityowner()
    {
        $id = $_POST['id'];
        if (Yii::$app->request->isAjax) {
            $proof = \app\models\UserIdProofs::findOne(['id' => $id]);
            if ($proof) {
                $proof->delete();
            }
            return true;
        }
    }

    public function actionGetreportparams()
    {
        //echo "hello";die;
        $report_id = $_POST['report_id'];
        $report_params = \app\models\ReportParameters::find()->where(['type_id' => $report_id])->all();
        //echo "<pre>";print_r($report_params);echo "</pre>";die;
        $fromhtml = "";
        $fromhtml .= "<div>";
        foreach ($report_params as $rp) {
            $fromhtml .= "<div>" . $rp->param_name;
            $fromhtml .= $rp->param_name . "</div>";
        }
        $fromhtml .= "</div>";
        echo $fromhtml;
        die;
        //echo $report_id;die;
    }

    public function actionGetreports()
    {
        //echo "<pre>";print_r($_POST);echo "</pre>";die;
        //echo "hello";die;
        $today = date("Y-m-d");
        //echo $today;die;
        $report_type = $_POST['report_type'];
        $property_id = $_POST['property_id'];
        $advisor_id = $_POST['advisor_id'];
        //echo $report_type;die;
        //$fromdate = $_POST['start_date'];
        $fdate = date_create($_POST['start_date']);
        $fromdate = date_format($fdate, "Y-m-d");
        $tdate = date_create($_POST['end_date']);
        $todate = date_format($tdate, "Y-m-d");
        $report_summary = $_POST['report_summary'];

        //echo $report_type;die;
        $connection = Yii::$app->getDb();
        if ($report_type == 1) {
            $command = $connection->createCommand('
      Select * FROM tenant_payments WHERE property_id = ' . $property_id . ' AND due_date >= "' . $fromdate . '" AND due_date <= "' . $todate . '"');
            //echo 'Select * FROM tenant_payments WHERE property_id = '.$property_id.' AND due_date >= "'.$fromdate.'" AND due_date <= "'.$todate.'"';die;
            $reportperproperty = $command->queryAll();

            if (!empty($reportperproperty)) {
                $commandp = $connection->createCommand('
      Select * FROM properties WHERE id = ' . $property_id);
                //echo 'Select * FROM tenant_payments WHERE property_id = '.$property_id.' AND due_date >= "'.$fromdate.'" AND due_date <= "'.$todate.'"';die;
                $propertydetails = $commandp->queryOne();


                if ($report_summary == 'c') {
                    $result_table = "<thead><tr><th>Property Id</th><th>Property Name</th><th>Property Type</th><th>Owner Name</th><th>Tenant Name</th><th>Rent Received</th><th>Paid to Property Owner</th><th>Paid to Property Advisor</th><th>Paid to Payment Gateway</th><th>GST</th><th>TDS</th><th>PMS Profit</th><th>Sales Executive</th><th>Operations Executive</th><th>Branch</th><th>Branch Manager Sales</th><th>Branch Manager Operations</th><th></th></tr></thead><tbody>";
                    foreach ($reportperproperty as $rp) {
                        $tenantid = $rp['tenant_id'];
                        $ptype = $propertydetails['property_type'];
                        $commandpt = $connection->createCommand('
      Select * FROM property_types WHERE id = ' . $ptype);
                        //echo 'Select * FROM tenant_payments WHERE property_id = '.$property_id.' AND due_date >= "'.$fromdate.'" AND due_date <= "'.$todate.'"';die;
                        $propertydetailst = $commandpt->queryOne();

                        $commandpt = $connection->createCommand('
      Select * FROM property_types WHERE id = ' . $ptype);
                        //echo 'Select * FROM tenant_payments WHERE property_id = '.$property_id.' AND due_date >= "'.$fromdate.'" AND due_date <= "'.$todate.'"';die;
                        $propertydetailst = $commandpt->queryOne();

                        $commandu = $connection->createCommand('
      Select * FROM users WHERE id = ' . $tenantid);
                        //echo 'Select * FROM tenant_payments WHERE property_id = '.$property_id.' AND due_date >= "'.$fromdate.'" AND due_date <= "'.$todate.'"';die;
                        $propertydetailsu = $commandu->queryOne();



                        $result_table .= "<tr><td>" . $rp['property_id'] . "</td><td>" . $propertydetails['property_name'] . "</td><td>" . $propertydetailst['property_type_name'] . "</td><td></td><td>" . $propertydetailsu['full_name'] . "</td><td>" . $rp['owner_amount'] . "</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>";
                        $result_table .= "</tr>";
                    }
                    $result_table .= "</tbody>";
                } else {
                    $result_table = "<thead><tr><th>Branch Name</th><th>Property Advisor</th><th>Count by Property Type</th><th>Total Rent Received</th><th>Total Paid to Property Owner</th><th>Total Paid to Property Advisor</th><th>Total Paid to Payment Gateway</th><th>GST</th><th>TDS</th><th>PMS Profit</th><th>Branch Manager Sales</th><th>Branch Manager Operations</th></tr></thead><tbody>";
                    foreach ($reportperproperty as $rp) {
                        $result_table .= "<tr><td></td><td></td><td></td><td></td><td>" . $rp['owner_amount'] . "</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
                    }
                    $result_table .= "</tbody>";
                }
            } else {
                $result_table = "No records found";
            }
        } else if ($report_type == 2) {
            $command = $connection->createCommand('
      Select * FROM tenant_agreements WHERE property_id = ' . $property_id . ' AND lease_end_date >= "' . $fromdate . '" AND lease_start_date <= "' . $todate . '"');
            $reportperproperty = $command->queryAll();
            if (!empty($reportperproperty)) {
                $commandp = $connection->createCommand('
      Select * FROM properties WHERE id = ' . $property_id);
                //echo 'Select * FROM tenant_payments WHERE property_id = '.$property_id.' AND due_date >= "'.$fromdate.'" AND due_date <= "'.$todate.'"';die;
                $propertydetails = $commandp->queryOne();

                $ptype = $propertydetails['property_type'];

                $commandpt = $connection->createCommand('
      Select * FROM property_types WHERE id = ' . $ptype);
                //echo 'Select * FROM tenant_payments WHERE property_id = '.$property_id.' AND due_date >= "'.$fromdate.'" AND due_date <= "'.$todate.'"';die;
                $propertydetailst = $commandpt->queryOne();

                if ($report_summary == 'c') {
                    $result_table = "<thead><tr><th>Property Id</th><th>Property Advisor</th><th>Property Name</th><th>Property Type</th><th>Is Vacant</th><th>Since When</th><th>Sales Executive</th><th>Operations Executive</th><th>Branch</th><th>Branch Manager Sales</th><th>Branch Manager Operations</th><th>Reporting Month</th></tr></thead><tbody>";
                    foreach ($reportperproperty as $rp) {
                        $result_table .= "<tr><td>" . $rp['property_id'] . "</td><td></td><td>" . $propertydetails['property_name'] . "</td><td>" . $propertydetailst['property_type_name'] . "</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>";
                        $result_table .= "</tr>";
                    }
                    $result_table .= "</tbody>";
                } else {
                    $result_table = "<thead><tr><th>Branch Name</th><th>Advisor Name</th><th>Property Type</th><th>Count Vacant</th><th>By Type</th><th>Branch Manager Sales</th><th>Branch Manager Operations</th></tr></thead><tbody>";
                    foreach ($reportperproperty as $rp) {
                        $result_table .= "<tr><td></td><td></td><td>" . $propertydetailst['property_type_name'] . "</td><td></td><td></td><td></td><td></td></tr>";
                    }
                    $result_table .= "</tbody>";
                }
            } else {
                $result_table = "No records found";
            }
        } else if ($report_type == 3) {
            $command = $connection->createCommand('
      Select * FROM tenant_payments WHERE due_date < "' . $today . '" AND payment_status = "0"');
            $reportperproperty = $command->queryAll();
            // echo "<pre>";print_r($reportperproperty);echo "</pre>";die;
            if (!empty($reportperproperty)) {


                $result_table = "<thead><tr><th>Property Id</th><th>Property Type</th><th>Property Name</th><th>Property Owner</th><th>Property Tenant</th><th>Property Advisor</th><th>Payment Due Date</th><th>Ageing</th><th>Payment Amount</th><th>Payment Description</th></tr></thead><tbody>";
                foreach ($reportperproperty as $rp) {
                    $propertyid = $rp['property_id'];

                    $commandp = $connection->createCommand('
      Select * FROM properties WHERE id = ' . $propertyid);
                    //echo 'Select * FROM tenant_payments WHERE property_id = '.$property_id.' AND due_date >= "'.$fromdate.'" AND due_date <= "'.$todate.'"';die;
                    $propertydetails = $commandp->queryOne();
                    //$ptype = $propertydetails['property_type'];
                    /* $commandpt = $connection->createCommand('
                      Select * FROM property_types WHERE id = '.$ptype); */
                    //echo 'Select * FROM tenant_payments WHERE property_id = '.$property_id.' AND due_date >= "'.$fromdate.'" AND due_date <= "'.$todate.'"';die;
                    //$propertydetailst = $commandpt->queryOne();
                    $tenantid = $rp['tenant_id'];
                    $commandu = $connection->createCommand('
      Select * FROM users WHERE id = ' . $tenantid);
                    //echo 'Select * FROM tenant_payments WHERE property_id = '.$property_id.' AND due_date >= "'.$fromdate.'" AND due_date <= "'.$todate.'"';die;
                    $propertydetailsu = $commandu->queryOne();

                    $result_table .= "<tr><td>" . $rp['property_id'] . "</td><td></td><td>" . $propertydetails['property_name'] . "</td><td></td><td>" . $propertydetailsu['full_name'] . "</td><td></td><td>" . $rp['due_date'] . "</td><td></td><td>" . $rp['total_amount'] . "</td><td>" . $rp['payment_des'] . "</td></tr>";
                }
                $result_table .= "</tbody>";
            } else {
                $result_table = "No records found";
            }
        } else if ($report_type == 4) {
            $command = $connection->createCommand('
      Select * FROM advisorpayments WHERE advisor_id = ' . $advisor_id . ' AND payment_date >= "' . $fromdate . '" AND payment_date <= "' . $todate . '"');
            $reportperproperty = $command->queryAll();
            if (!empty($reportperproperty)) {

                if ($report_summary == 'c') {
                    $result_table = "<thead><tr><th>Advisor Name</th><th>Property Name</th><th>Referred Client Name</th><th>Referred Client Type</th><th>Property Type</th><th>Rent Received</th><th>PMS Earning</th><th>Applicable Fee %</th><th>Advisor Fee</th><th>TDS</th><th>Net Paid to Property Advisor</th><th>Month</th><th>Sales Executive</th><th>Operations Executive</th><th>Branch</th><th>Branch Manager Sales</th><th>Branch Manager Operations</th></tr></thead><tbody>";
                    foreach ($reportperproperty as $rp) {
                        $result_table .= "<tr><td>Advisor Name</td><td>Property Name</td><td>Referred Client Name</td><td>Referred Client Type</td><td>Property Type</td><td>Rent Received</td><td>PMS Earning</td><td>Applicable Fee %</td><td>Advisor Fee</td><td>TDS</td><td>Net Paid to Property Advisor</td><td>Month</td><td>Sales Executive</td><td>Operations Executive</td><td>Branch</td><td>Branch Manager Sales</td><td>Branch Manager Operations</td>";
                        $result_table .= "</tr>";
                    }
                    $result_table .= "</tbody>";
                } else {
                    $result_table = "<thead><tr><th>Advisor Name</th><th>Count by Property Type</th><th>Total Rent Received</th><th>Total PMS Earning</th><th>Total Paid to Property Advisor</th><th>Branch Manager Sales</th><th>Branch Manager Operations</th></tr></thead><tbody>";
                    foreach ($reportperproperty as $rp) {
                        $result_table .= "<tr><td>Advisor Name</td><td>Count by Property Type</td><td>Total Rent Received</td><td>Total PMS Earning</td><td>Total Paid to Property Advisor</td><td>Branch Manager Sales</td><td>Branch Manager Operations</td></tr>";
                    }
                    $result_table .= "</tbody>";
                }
            } else {
                $result_table = "No records found";
            }
        } else if ($report_type == 5) {
            $command = $connection->createCommand('
      Select * FROM users WHERE created_date >= "' . $fromdate . '" AND created_date <= "' . $todate . '" AND user_type = "2" AND status = "1"');
            $reportperproperty = $command->queryAll();

            if (!empty($reportperproperty)) {


                $result_table = "<thead><tr><th>Applicant Name</th><th>Branch Name</th><th>Sales Person</th><th>Operations Person</th><th>Ageing</th><th>Number of Shortlist Properties</th></tr></thead><tbody>";
                foreach ($reportperproperty as $rp) {
                    $result_table .= "<tr><td>" . $rp['full_name'] . "</td><td></td><td></td><td></td><td></td><td></td></tr>";
                }
                $result_table .= "</tbody>";
            } else {
                $result_table = "No records found";
            }
        } else if ($report_type == 6) {
            $command = $connection->createCommand('Select * FROM properties WHERE created_date >= "' . $fromdate . '" AND created_date <= "' . $todate . '" AND status = "1"');
            $reportperproperty = $command->queryAll();
            //echo "<pre>";print_r($reportperproperty);echo "</pre>";die;
            if (!empty($reportperproperty)) {


                $result_table = "<thead><tr><th>Property Name</th><th>Owner Name</th><th>Branch Name</th><th>Sales Person</th><th>Operations Person</th><th>Ageing</th></tr></thead><tbody>";
                foreach ($reportperproperty as $rp) {
                    $ownerid = $rp['owner_id'];
                    $commandu = $connection->createCommand('
      Select * FROM users WHERE id = ' . $ownerid);
                    //echo 'Select * FROM tenant_payments WHERE property_id = '.$property_id.' AND due_date >= "'.$fromdate.'" AND due_date <= "'.$todate.'"';die;
                    $propertydetailsu = $commandu->queryOne();
                    $result_table .= "<tr><td>" . $rp['property_name'] . "</td><td>" . $propertydetailsu['full_name'] . "</td><td>" . $rp['branch_code'] . "</td><td>" . $rp['sales_id'] . "</td><td>" . $rp['operations_id'] . "</td><td></td></tr>";
                }
                $result_table .= "</tbody>";
            } else {
                $result_table = "No records found";
            }
        } else if ($report_type == 7) {
            $command = $connection->createCommand('Select * FROM users WHERE created_date >= "' . $fromdate . '" AND created_date <= "' . $todate . '" AND user_type = "5" AND status = "1"');
            $reportperproperty = $command->queryAll();
            //echo "<pre>";print_r($reportperproperty);echo "</pre>";die;
            if (!empty($reportperproperty)) {


                $result_table = "<thead><tr><th>Advisor Name</th><th>Branch Name</th><th>Sales Person</th><th>Operations Person</th><th>Ageing</th></tr></thead><tbody>";
                foreach ($reportperproperty as $rp) {
                    $result_table .= "<tr><td>" . $rp['full_name'] . "</td><td></td><td></td><td></td><td></td></tr>";
                }
                $result_table .= "</tbody>";
            } else {
                $result_table = "No records found";
            }
        }

        echo $result_table;
        die;
    }

    public function actionExpense()
    {
        $expenseType = \app\models\ExpenseType::find()->all();
        $this->view->params['expenseTypes'] = $expenseType;
        return $this->render('expense');
    }

    public function actionGetcreatebyexpense()
    {
        $searchVal = Yii::$app->request->get('term');
        $userType = Yii::$app->user->identity->user_type;
        $where = "";

        $expenseTable = \app\models\PropertyExpense::tableName();
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand(""
            . "SELECT DISTINCT u.id, u.full_name FROM users u INNER JOIN " . $expenseTable . " ep ON u.id = ep.created_by "
            . "WHERE u.full_name LIKE '%" . $searchVal . "%' "
            . "");
        $ownerData = $command->queryAll();

        $description_arr = [];
        foreach ($ownerData as $key => $row) {
            $label = $row['full_name'];
            $description_arr[$key]['label'] = (strlen($label) > 150) ? substr($label, 0, 150) . " ....." : substr($label, 0, 150);
            $description_arr[$key]['value'] = $row['id'];
        }

        echo json_encode($description_arr);
        exit;
    }

    public function actionGetexpensevendor()
    {
        $searchVal = Yii::$app->request->get('term');
        $userType = Yii::$app->user->identity->user_type;
        $where = "";

        $expenseTable = \app\models\PropertyExpense::tableName();
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("SELECT DISTINCT vendor FROM " . $expenseTable . " WHERE vendor LIKE '%" . $searchVal . "%' ");
        $ownerData = $command->queryAll();

        $description_arr = [];
        foreach ($ownerData as $key => $row) {
            $label = $row['vendor'];
            $description_arr[$key]['label'] = $label;
        }

        echo json_encode($description_arr);
        exit;
    }

    public function actionGetexpensepaidby()
    {
        $searchVal = Yii::$app->request->get('term');
        $userType = Yii::$app->user->identity->user_type;
        $where = "";

        $expenseTable = \app\models\PropertyExpense::tableName();
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("SELECT DISTINCT paid_by FROM " . $expenseTable . " WHERE paid_by LIKE '%" . $searchVal . "%' ");
        $ownerData = $command->queryAll();

        $description_arr = [];
        foreach ($ownerData as $key => $row) {
            $label = $row['paid_by'];
            $description_arr[$key]['label'] = $label;
        }

        echo json_encode($description_arr);
        exit;
    }

    public function actionGetexpenseapprovedby()
    {
        $searchVal = Yii::$app->request->get('term');
        $userType = Yii::$app->user->identity->user_type;
        $where = "";

        $expenseTable = \app\models\PropertyExpense::tableName();
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand(""
            . " SELECT DISTINCT u.id, u.full_name FROM " . $expenseTable . " pi "
            . " JOIN users u ON u.id = pi.approved_by "
            . " WHERE u.full_name LIKE '%" . $searchVal . "%' "
            . "");
        $ownerData = $command->queryAll();

        $description_arr = [];
        foreach ($ownerData as $key => $row) {
            $label = $row['full_name'];
            $value = $row['id'];
            $description_arr[$key]['label'] = $label;
            $description_arr[$key]['value'] = $value;
        }

        echo json_encode($description_arr);
        exit;
    }

    public function actionGenerateexpensecsv()
    {
        $this->layout = false;
        $fileContent = [];
        $headers = [];

        $headers = $this->getExpenseHeaders();
        $headers[] = 'Attachment';

        $totalColumns = count($headers);
        $rowIds = [];

        return $this->render('query-expense-html', ['fileContent' => $fileContent, 'headers' => $headers, 'property_id' => Yii::$app->request->post('property_id'), 'totalColumns' => $totalColumns, 'rowIds' => $rowIds]);
    }

    public function actionParseexpensecsv()
    {
        $this->layout = false;
        $fileContent = [];
        $headers = [];
        $headers = $this->getExpenseHeaders();
        $totalColumns = count($headers);
        $totalRows = 0;
        if ($_FILES['expense_csv']['type'] != 'text/csv' && $_FILES['expense_csv']['type'] != 'application/octet-stream') {
            echo 'Invalid';
            exit;
        }

        if (!empty($_FILES['expense_csv']['tmp_name'])) {
            $i = 0;
            $file = fopen($_FILES['expense_csv']['tmp_name'], "r");
            while (!feof($file)) {
                if ($i > 0) {
                    $fileContent[] = fgetcsv($file);
                } else {
                    fgetcsv($file);
                }
                $i++;
            }

            fclose($file);
            foreach ($fileContent as $key => $row) {
                if (!empty($row)) {
                    $totalRows++;
                    $expenseModel = new \app\models\PropertyExpense();
                    $expenseModel->property_id = Yii::$app->request->post('property_id');
                    $expenseModel->expense_type = Yii::$app->userdata->getExpenseCodeByName($fileContent[$key][0]);
                    $expenseModel->total_expense_amount = $fileContent[$key][1];
                    $expenseModel->paid_on = (!empty(strtotime($fileContent[$key][2]))) ? date('Y-m-d', strtotime($fileContent[$key][2])) : '';
                    $expenseModel->paid_by = $fileContent[$key][3];
                    $expenseModel->paid_by_entity = $fileContent[$key][4];
                    $expenseModel->vendor = $fileContent[$key][5];
                    $expenseModel->invoice_date = (!empty(strtotime($fileContent[$key][6]))) ? date('Y-m-d', strtotime($fileContent[$key][6])) : '';
                    $expenseModel->gst_invoice = $fileContent[$key][7];
                    $expenseModel->vendor_gst = $fileContent[$key][8];
                    $expenseModel->total_gst = $fileContent[$key][9];
                    $expenseModel->month = (!empty(strtotime($fileContent[$key][10]))) ? date('Y-m-d', strtotime('01-' . $fileContent[$key][10])) : null;
                    $expenseModel->remarks = $fileContent[$key][11];
                    $expenseModel->created_by = Yii::$app->user->id;
                    $expenseModel->created_date = date('Y-m-d H:i:s');
                    $expenseModel->save(false);
                }
            }

            $expenseTable = \app\models\PropertyExpense::tableName();
            $connection = \Yii::$app->db;
            $model = $connection->createCommand('SELECT * FROM ' . $expenseTable . ' ORDER BY id DESC LIMIT ' . $totalRows . ' ');
            $expenseData = $model->queryAll();
            $rowIds = [];
            $fileContent = [];
            $headers[] = 'Attachment';
            $totalColumns = count($headers);

            foreach ($expenseData as $key => $row) {
                $rowIds[$key] = $row['id'];
                $fileContent[$key][] = $row['expense_type'];
                $fileContent[$key][] = $row['total_expense_amount'];
                $fileContent[$key][] = (!empty(strtotime($row['paid_on']))) ? date('d-M-Y', strtotime($row['paid_on'])) : '';
                $fileContent[$key][] = $row['paid_by'];
                $fileContent[$key][] = $row['paid_by_entity'];
                $fileContent[$key][] = $row['vendor'];
                $fileContent[$key][] = (!empty(strtotime($row['invoice_date']))) ? date('d-M-Y', strtotime($row['invoice_date'])) : '';
                $fileContent[$key][] = $row['gst_invoice'];
                $fileContent[$key][] = $row['vendor_gst'];
                $fileContent[$key][] = $row['total_gst'];
                $fileContent[$key][] = (!empty($row['month'])) ? date('M-Y', strtotime($row['month'])) : '';
                $fileContent[$key][] = Yii::$app->userdata->getUserNameById($row['approved_by']);
                $fileContent[$key][] = $row['remarks'];
                $fileContent[$key][] = $row['attachment'];
            }

            krsort($fileContent);
            $fileContent = array_values($fileContent);
            krsort($rowIds);
            $rowIds = array_values($rowIds);
        }

        return $this->render('query-expense-html', ['fileContent' => $fileContent, 'headers' => $headers, 'property_id' => Yii::$app->request->post('property_id'), 'totalColumns' => $totalColumns, 'rowIds' => $rowIds]);
    }

    public function actionQueryexpensedata()
    {
        $this->layout = false;
        if (!empty(Yii::$app->request->post())) {
            $fileContent = [];
            $propertyId = 0;
            $headers = [];
            $headers = $this->getExpenseHeaders();
            $headers[] = "Attachment";

            $totalColumns = count($headers);
            extract(Yii::$app->request->post());

            $expenseTypeSql = $expense_type;
            $isAttachedSql = (!empty($is_attached)) ? $is_attached : '';
            $createdFromSql = (!empty($created_from)) ? date('Y-m-d', strtotime($created_from)) : '';
            $createdToSql = (!empty($created_to)) ? date('Y-m-d', strtotime($created_to)) : '';
            $expenseAmountFromSql = $expense_amount_from;
            $expenseAmountToSql = $expense_amount_to;
            $dueDateFromSql = (!empty($due_date_from)) ? date('Y-m-d', strtotime($due_date_from)) : '';
            $dueDateToSql = (!empty($due_date_to)) ? date('Y-m-d', strtotime($due_date_to)) : '';
            $invoiceDateFromSql = (!empty($invoice_date_from)) ? date('Y-m-d', strtotime($invoice_date_from)) : '';
            $invoiceDateToSql = (!empty($invoice_date_to)) ? date('Y-m-d', strtotime($invoice_date_to)) : '';
            $paidOnFromSql = (!empty($paid_on_from)) ? date('Y-m-d', strtotime($paid_on_from)) : '';
            $paidOnToSql = (!empty($paid_on_to)) ? date('Y-m-d', strtotime($paid_on_to)) : '';
            $vendorNameSql = $vendor_name;
            $createdBySql = $created_by;
            $paidBySql = $paid_by;
            $approvedBySql = $approved_by;
            $monthSql = $month;

            if (!empty($isAttachedSql)) {
                $isAttachedSql = ' ( attachment IS NOT NULL ) AND ';
            }

            if (!empty($createdBySql)) {
                $createdBySql = ' ( created_by = ' . $createdBySql . ' ) AND ';
            }

            if (!empty($expenseTypeSql)) {
                $expenseTypeSql = ' ( expense_type = "' . $expenseTypeSql . '" ) AND ';
            }

            if (!empty($approvedBySql)) {
                $approvedBySql = ' ( approved_by LIKE "%' . $approvedBySql . '%" ) AND ';
            }

            if (!empty($monthSql)) {
                $monthSql = ' ( month = "' . date('Y-m-d', strtotime('01-' . $monthSql)) . '" ) AND ';
            }

            if (!empty($paidBySql)) {
                $paidBySql = ' ( paid_by = "' . $paidBySql . '" ) AND ';
            }

            $createdDateSQL = '';

            if (!empty($createdFromSql) && !empty($createdToSql)) {
                $createdDateSQL = ' ( DATE(created_date) BETWEEN "' . $createdFromSql . '" AND "' . $createdToSql . '" ) AND ';
            } else if (!empty($createdFromSql)) {
                $createdDateSQL = ' ( DATE(created_date) >= "' . $createdFromSql . '" ) AND ';
            } else if (!empty($createdToSql)) {
                $createdDateSQL = ' ( DATE(created_date) <= "' . $createdToSql . '" ) AND ';
            }

            $invoiceDateSQL = '';

            if (!empty($invoiceDateFromSql) && !empty($invoiceDateToSql)) {
                $invoiceDateSQL = ' ( DATE(invoice_date) BETWEEN "' . $invoiceDateFromSql . '" AND "' . $invoiceDateToSql . '" ) AND ';
            } else if (!empty($invoiceDateFromSql)) {
                $invoiceDateSQL = ' ( DATE(invoice_date) >= "' . $invoiceDateFromSql . '" ) AND ';
            } else if (!empty($invoiceDateToSql)) {
                $invoiceDateSQL = ' ( DATE(invoice_date) <= "' . $invoiceDateToSql . '" OR invoice_date IS NULL ) AND ';
            }

            $expenseAmountSQL = '';

            if (!empty($expenseAmountFromSql) && !empty($expenseAmountToSql)) {
                $expenseAmountSQL = ' ( total_expense_amount >= ' . $expenseAmountFromSql . ' AND total_expense_amount <= ' . $expenseAmountToSql . ' ) AND ';
            } else if (!empty($expenseAmountFromSql)) {
                $expenseAmountSQL = ' ( total_expense_amount >= ' . $expenseAmountFromSql . ' ) AND ';
            } else if (!empty($expenseAmountToSql)) {
                $expenseAmountSQL = ' ( total_expense_amount <= ' . $expenseAmountToSql . ' ) AND ';
            }

            $paidOnSQL = '';

            if (!empty($paidOnFromSql) && !empty($paidOnToSql)) {
                $paidOnSQL = ' ( DATE(paid_on) BETWEEN "' . $paidOnFromSql . '" AND "' . $paidOnToSql . '" ) AND ';
            } else if (!empty($paidOnFromSql)) {
                $paidOnSQL = ' ( DATE(paid_on) >= "' . $paidOnFromSql . '" ) AND ';
            } else if (!empty($paidOnToSql)) {
                $paidOnSQL = ' ( DATE(paid_on) <= "' . $paidOnToSql . '" OR paid_on IS NULL ) AND ';
            }

            $vendorSQL = '';

            if (!empty($vendorNameSql)) {
                $vendorSQL = ' ( vendor LIKE "%' . $vendorNameSql . '%" ) AND ';
            }

            $expenseTable = \app\models\PropertyExpense::tableName();
            $connection = \Yii::$app->db;
            $model = $connection->createCommand('SELECT * FROM ' . $expenseTable . ' WHERE '
                . ' ' . $expenseAmountSQL . ' '
                . ' ' . $paidOnSQL . ' '
                . ' ' . $vendorSQL . ' '
                . ' ' . $invoiceDateSQL . ' '
                . ' ' . $paidBySql . ' '
                . ' ' . $approvedBySql . ' '
                . ' ' . $expenseTypeSql . ' '
                . ' ' . $isAttachedSql . ' '
                . ' ' . $createdBySql . ' '
                . ' ' . $createdDateSQL . ' '
                . ' ' . $monthSql . ' '
                . ' ( property_id = ' . $property_id . ' ) '
                . ' ORDER BY id DESC '
                . '');

            if ((empty($created_by) && !empty($created_by_placeholder)) || (empty($approved_by) && !empty($approved_by_placeholder))) {
                $expenseData = [];
            } else {
                $expenseData = $model->queryAll();
            }

            $rowIds = [];
            foreach ($expenseData as $key => $row) {
                $rowIds[$key] = $row['id'];
                $fileContent[$key][] = $row['expense_type'];
                $fileContent[$key][] = $row['total_expense_amount'];
                $fileContent[$key][] = (!empty(strtotime($row['paid_on']))) ? date('d-M-Y', strtotime($row['paid_on'])) : '';
                $fileContent[$key][] = $row['paid_by'];
                $fileContent[$key][] = $row['paid_by_entity'];
                $fileContent[$key][] = $row['vendor'];
                $fileContent[$key][] = (!empty(strtotime($row['invoice_date']))) ? date('d-M-Y', strtotime($row['invoice_date'])) : '';
                $fileContent[$key][] = $row['gst_invoice'];
                $fileContent[$key][] = $row['vendor_gst'];
                $fileContent[$key][] = $row['total_gst'];
                $fileContent[$key][] = (!empty($row['month'])) ? date('M-Y', strtotime($row['month'])) : '';
                $fileContent[$key][] = Yii::$app->userdata->getUserNameById($row['approved_by']);
                $fileContent[$key][] = $row['remarks'];
                $fileContent[$key][] = $row['attachment'];
            }

            krsort($fileContent);
            $fileContent = array_values($fileContent);
            krsort($rowIds);
            $rowIds = array_values($rowIds);

            return $this->render('query-expense-html', ['fileContent' => $fileContent, 'headers' => $headers, 'property_id' => $property_id, 'totalColumns' => $totalColumns, 'rowIds' => $rowIds]);
        }
    }

    public function actionProcessexpensecsv()
    {
        $response = 0;
        $this->layout = false;
        $perm = true;
        $commitChanges = true;
        $postData = Yii::$app->request->post('PropertyExpense');
        $rowId = [];
        if (!empty(Yii::$app->request->post('row_id'))) {
            $rowId = Yii::$app->request->post('row_id');
        }
        if (!empty(Yii::$app->request->post())) {
            $userType = Yii::$app->user->identity->user_type;
            if ($userType == 7) {
                $opUser = \app\models\OperationsProfile::findOne(['operations_id' => Yii::$app->user->identity->id]);
                if ($opUser->role_code == 'OPEXE' && $opUser->role_value != 'ELHQ') {
                    $perm = false;
                }
            } else if ($userType == 6) {
                $salesUser = \app\models\SalesProfile::findOne(['sale_id' => Yii::$app->user->identity->id]);
                if ($salesUser->role_code == 'SLEXE') {
                    $perm = false;
                }
            }
            $totalColumns = Yii::$app->request->post('total_columns');
            $totalRows = 0;
            if (!empty($postData)) {
                foreach ($postData as $key => $row) {
                    if (!empty($row)) {
                        $totalRows++;
                        $count = count($row);
                        if ($count == $totalColumns) {
                            if (!empty($rowId[$key])) {
                                $expenseModel = \app\models\PropertyExpense::find()->where(['id' => $rowId[$key]])->one();
                                if (!$perm && !empty($expenseModel->approved_by)) {
                                    $commitChanges = false;
                                } else {
                                    $commitChanges = true;
                                }
                                $expenseModel->property_id = Yii::$app->request->post('property_id');
                                $expenseModel->expense_type = $postData[$key][0];
                                $expenseModel->total_expense_amount = $postData[$key][1];
                                $expenseModel->paid_on = (!empty(strtotime($postData[$key][2]))) ? date('Y-m-d', strtotime($postData[$key][2])) : '';
                                $expenseModel->paid_by = $postData[$key][3];
                                $expenseModel->paid_by_entity = Yii::$app->userdata->getEntityTypeName($postData[$key][4]);
                                $expenseModel->vendor = $postData[$key][5];
                                $expenseModel->invoice_date = (!empty(strtotime($postData[$key][6]))) ? date('Y-m-d', strtotime($postData[$key][6])) : '';
                                $expenseModel->gst_invoice = $postData[$key][7];
                                $expenseModel->vendor_gst = $postData[$key][8];
                                $expenseModel->total_gst = $postData[$key][9];
                                $expenseModel->month = (!empty(strtotime($postData[$key][10]))) ? date('Y-m-d', strtotime('01-' . $postData[$key][10])) : null;
                                $expenseModel->remarks = $postData[$key][11];

                                if (!empty($_FILES['ExpenseAttachment']['name'][$key])) {
                                    $path = 'uploads/expense/';
                                    $name = date('ymdHis') . str_replace(' ', '', $_FILES['ExpenseAttachment']['name'][$key]);
                                    $tempName = $_FILES['ExpenseAttachment']['tmp_name'][$key];
                                    move_uploaded_file($tempName, $path . $name);
                                    $expenseModel->attachment = $path . $name;
                                }

                                if ($commitChanges) {
                                    $expenseModel->save(false);
                                }
                            } else {
                                $expenseModel = new \app\models\PropertyExpense();
                                $expenseModel->property_id = Yii::$app->request->post('property_id');
                                $expenseModel->expense_type = $postData[$key][0];
                                $expenseModel->total_expense_amount = $postData[$key][1];
                                $expenseModel->paid_on = (!empty(strtotime($postData[$key][2]))) ? date('Y-m-d', strtotime($postData[$key][2])) : '';
                                $expenseModel->paid_by = $postData[$key][3];
                                $expenseModel->paid_by_entity = Yii::$app->userdata->getEntityTypeName($postData[$key][4]);
                                $expenseModel->vendor = $postData[$key][5];
                                $expenseModel->invoice_date = (!empty(strtotime($postData[$key][6]))) ? date('Y-m-d', strtotime($postData[$key][6])) : '';
                                $expenseModel->gst_invoice = $postData[$key][7];
                                $expenseModel->vendor_gst = $postData[$key][8];
                                $expenseModel->total_gst = $postData[$key][9];
                                $expenseModel->month = (!empty(strtotime($postData[$key][10]))) ? date('Y-m-d', strtotime('01-' . $postData[$key][10])) : null;
                                $expenseModel->remarks = $postData[$key][11];
                                $expenseModel->created_by = Yii::$app->user->id;
                                $expenseModel->created_date = date('Y-m-d H:i:s');

                                if (!empty($_FILES['ExpenseAttachment']['name'][$key])) {
                                    $path = 'uploads/expense/';
                                    $name = date('ymdHis') . str_replace(' ', '', $_FILES['ExpenseAttachment']['name'][$key]);
                                    $tempName = $_FILES['ExpenseAttachment']['tmp_name'][$key];
                                    move_uploaded_file($tempName, $path . $name);
                                    $expenseModel->attachment = $path . $name;
                                }

                                $expenseModel->save(false);
                                $rowId[] = \Yii::$app->db->lastInsertID;
                            }
                        }
                    }
                }
            }

            if (!empty(Yii::$app->request->post('delete_property_expense'))) {
                $toDelete = json_decode(Yii::$app->request->post('delete_property_expense'));
                if (!empty($toDelete)) {
                    foreach ($toDelete as $key => $row) {
                        $pExp = \app\models\PropertyExpense::findOne(['id' => $row]);
                        $pExp->delete();
                    }
                }
            }

            $headers = [];
            $headers = $this->getExpenseHeaders();
            $headers[] = 'Attachment';

            $totalColumns = count($headers);

            $expenseTable = \app\models\PropertyExpense::tableName();
            $connection = \Yii::$app->db;
            $expenseData = [];
            foreach ($rowId as $key => $id) {
                $model = $connection->createCommand('SELECT * FROM ' . $expenseTable . ' WHERE property_id = ' . Yii::$app->request->post('property_id') . ' AND id = ' . $id . ' ORDER BY id DESC ');
                $expenseData[] = $model->queryOne();
            }
            $rowIds = [];
            $fileContent = [];

            foreach ($expenseData as $key => $row) {
                $rowIds[$key] = $row['id'];
                $fileContent[$key][] = $row['expense_type'];
                $fileContent[$key][] = $row['total_expense_amount'];
                $fileContent[$key][] = (!empty(strtotime($row['paid_on']))) ? date('d-M-Y', strtotime($row['paid_on'])) : '';
                $fileContent[$key][] = $row['paid_by'];
                $fileContent[$key][] = $row['paid_by_entity'];
                $fileContent[$key][] = $row['vendor'];
                $fileContent[$key][] = (!empty(strtotime($row['invoice_date']))) ? date('d-M-Y', strtotime($row['invoice_date'])) : '';
                $fileContent[$key][] = $row['gst_invoice'];
                $fileContent[$key][] = $row['vendor_gst'];
                $fileContent[$key][] = $row['total_gst'];
                $fileContent[$key][] = (!empty($row['month'])) ? date('M-Y', strtotime($row['month'])) : '';
                $fileContent[$key][] = Yii::$app->userdata->getUserNameById($row['approved_by']);
                $fileContent[$key][] = $row['remarks'];
                $fileContent[$key][] = $row['attachment'];
            }

            $fileContent = array_values($fileContent);
            $rowIds = array_values($rowIds);

            return $this->render('query-expense-html', ['fileContent' => $fileContent, 'headers' => $headers, 'property_id' => Yii::$app->request->post('property_id'), 'totalColumns' => $totalColumns, 'rowIds' => $rowIds]);
        }
    }

    public function actionExportexpensetocsv()
    {
        $this->layout = false;
        $response = 0;
        $headers = [];
        $headers = $this->getExpenseHeaders();
        unset($headers[11]);
        $postData = Yii::$app->request->post('PropertyExpense');
        if (!empty(Yii::$app->request->post())) {
            $propName = Yii::$app->userdata->getPropertyNameById(Yii::$app->request->post('property_id'));
            $now = microtime();
            $fileName = str_replace(' ', '-', $propName . '-' . $now . '.csv');
            $path = 'uploads/expense/temp/';
            $file = fopen($path . $fileName, 'w+');
            fputcsv($file, $headers);
            if (!empty($postData)) {
                foreach ($postData as $key => $row) {
                    if (!empty($row)) {
                        if (!empty($row[0])) {
                            $row[0] = Yii::$app->userdata->getExpenseNameByCode($row[0]);
                        }
                        unset($row[11]);
                        fputcsv($file, $row);
                    }
                }
            }

            fclose($file);

            echo $fileName;
            exit;
        }
    }

    public function actionDownloadexpensecsv($d)
    {
        $fileName = $d;
        $this->layout = false;
        $path = 'uploads/expense/temp/';
        if (!empty($fileName) && !empty(@file_get_contents($path . $fileName))) {
            $content = file_get_contents($path . $fileName);
            unlink($path . $fileName);
            header("Content-Type: text/csv");
            header("Content-Disposition: attachment;filename={$fileName}");
            header("Content-Transfer-Encoding: binary");
            echo $content;
        }
        exit;
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function actionIncome()
    {
        $incomeType = \app\models\IncomeType::find()->all();
        $this->view->params['incomeTypes'] = $incomeType;
        return $this->render('income');
    }

    public function getIncomeHeaders()
    {
        $headers[] = 'Customer Id';
        $headers[] = 'Customer Name';
        $headers[] = 'Income Type';
        $headers[] = 'Income Amount';
        $headers[] = 'Transaction No.';
        $headers[] = 'Transaction Mode';
        $headers[] = 'Paid On';
        $headers[] = 'Received by person';
        $headers[] = 'Received by entity';
        $headers[] = 'Month';
        $headers[] = 'Approved By';
        $headers[] = 'Remarks';
        return $headers;
    }

    public function getExpenseHeaders()
    {
        $headers[] = 'Expense Type';
        $headers[] = 'Expense Amount';
        $headers[] = 'Paid On';
        $headers[] = 'Paid By';
        $headers[] = 'Paid by entity';
        $headers[] = 'Vendor Name';
        $headers[] = 'Invoice Date';
        $headers[] = 'Invoice Number';
        $headers[] = 'Vendor GST';
        $headers[] = 'GST Amount';
        $headers[] = 'Month';
        $headers[] = 'Approved By';
        $headers[] = 'Remarks';
        return $headers;
    }

    public function actionGetcreatebyincome()
    {
        $searchVal = Yii::$app->request->get('term');
        $userType = Yii::$app->user->identity->user_type;
        $where = "";

        $expenseTable = \app\models\PropertyIncome::tableName();
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand(""
            . "SELECT DISTINCT u.id, u.full_name FROM users u INNER JOIN " . $expenseTable . " ep ON u.id = ep.created_by "
            . "WHERE u.full_name LIKE '%" . $searchVal . "%' "
            . "");
        $ownerData = $command->queryAll();

        $description_arr = [];
        foreach ($ownerData as $key => $row) {
            $label = $row['full_name'];
            $description_arr[$key]['label'] = (strlen($label) > 150) ? substr($label, 0, 150) . " ....." : substr($label, 0, 150);
            $description_arr[$key]['value'] = $row['id'];
        }

        echo json_encode($description_arr);
        exit;
    }

    public function actionGetincomeentitytypes()
    {
        $searchVal = Yii::$app->request->get('term');
        $userType = Yii::$app->user->identity->user_type;
        $where = "";

        $table = \app\models\EntityTypes::tableName();
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand(""
            . "SELECT name FROM " . $table . " "
            . "WHERE name LIKE '%" . $searchVal . "%' "
            . "");
        $ownerData = $command->queryAll();

        $description_arr = [];
        foreach ($ownerData as $key => $row) {
            $label = $row['name'];
            $description_arr[$key]['label'] = (strlen($label) > 150) ? substr($label, 0, 150) . " ....." : substr($label, 0, 150);
        }

        echo json_encode($description_arr);
        exit;
    }

    public function actionGetincomepaidby()
    {
        $searchVal = Yii::$app->request->get('term');
        $userType = Yii::$app->user->identity->user_type;
        $where = "";

        $table = \app\models\PropertyIncome::tableName();
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("SELECT DISTINCT paid_by FROM " . $table . " WHERE paid_by LIKE '%" . $searchVal . "%' ");
        $ownerData = $command->queryAll();

        $description_arr = [];
        foreach ($ownerData as $key => $row) {
            $label = $row['paid_by'];
            $description_arr[$key]['label'] = $label;
        }

        echo json_encode($description_arr);
        exit;
    }

    public function actionGetincomeapprovedby()
    {
        $searchVal = Yii::$app->request->get('term');
        $userType = Yii::$app->user->identity->user_type;
        $where = "";

        $expenseTable = \app\models\PropertyIncome::tableName();
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand(""
            . " SELECT DISTINCT u.id, u.full_name FROM " . $expenseTable . " pi "
            . " JOIN users u ON u.id = pi.approved_by "
            . " WHERE u.full_name LIKE '%" . $searchVal . "%' "
            . "");
        $ownerData = $command->queryAll();

        $description_arr = [];
        foreach ($ownerData as $key => $row) {
            $label = $row['full_name'];
            $value = $row['id'];
            $description_arr[$key]['label'] = $label;
            $description_arr[$key]['value'] = $value;
        }

        echo json_encode($description_arr);
        exit;
    }

    public function actionGetincometransactionmode()
    {
        $searchVal = Yii::$app->request->get('term');
        $userType = Yii::$app->user->identity->user_type;
        $where = "";

        $table = \app\models\PaymentModeType::tableName();
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand(""
            . " SELECT name FROM " . $table . " "
            . " WHERE name LIKE '%" . $searchVal . "%' AND name != 'Adjusted Against Wallet' "
            . "");
        $ownerData = $command->queryAll();

        $description_arr = [];
        foreach ($ownerData as $key => $row) {
            $label = $row['name'];
            $description_arr[$key]['label'] = $label;
        }

        echo json_encode($description_arr);
        exit;
    }

    public function actionGenerateincomecsv()
    {
        $this->layout = false;
        $fileContent = [];
        $headers = [];

        $headers = $this->getIncomeHeaders();
        $headers[] = 'Attachment';

        $totalColumns = count($headers);
        $rowIds = [];

        return $this->render('query-income-html', ['fileContent' => $fileContent, 'headers' => $headers, 'property_id' => Yii::$app->request->post('property_id'), 'totalColumns' => $totalColumns, 'rowIds' => $rowIds]);
    }

    public function actionParseincomecsv()
    {
        $this->layout = false;
        $fileContent = [];
        $headers = [];
        $headers = $this->getIncomeHeaders();
        $totalColumns = count($headers);
        $totalRows = 0;
        if ($_FILES['income_csv']['type'] != 'text/csv' && $_FILES['income_csv']['type'] != 'application/octet-stream') {
            echo 'Invalid';
            exit;
        }

        if (!empty($_FILES['income_csv']['tmp_name'])) {
            $i = 0;
            $file = fopen($_FILES['income_csv']['tmp_name'], "r");
            while (!feof($file)) {
                if ($i > 0) {
                    $fileContent[] = fgetcsv($file);
                } else {
                    fgetcsv($file);
                }
                $i++;
            }

            fclose($file);
            foreach ($fileContent as $key => $row) {
                if (!empty($row)) {
                    $totalRows++;
                    $expenseModel = new \app\models\PropertyIncome();
                    $expenseModel->property_id = Yii::$app->request->post('property_id');
                    $expenseModel->customer_id = $fileContent[$key][0];
                    if (!empty($expenseModel->customer_id)) {
                        $expenseModel->customer_name = Yii::$app->userdata->getFullNameById($expenseModel->customer_id);
                    } else {
                        $expenseModel->customer_name = $fileContent[$key][1];
                    }
                    $expenseModel->income_type = Yii::$app->userdata->getIncomeCodeByName($fileContent[$key][2]);
                    $expenseModel->total_income_amount = $fileContent[$key][3];
                    $expenseModel->transaction_num = $fileContent[$key][4];
                    $expenseModel->transaction_mode = Yii::$app->userdata->getPaymentModeId($fileContent[$key][5]);
                    $expenseModel->paid_on = (!empty(strtotime($fileContent[$key][6]))) ? date('Y-m-d', strtotime($fileContent[$key][6])) : '';
                    $expenseModel->received_by_person = $fileContent[$key][7];
                    $expenseModel->received_by_entity = Yii::$app->userdata->getEntityTypeName($fileContent[$key][8]);
                    $expenseModel->month = (!empty(strtotime($fileContent[$key][9]))) ? date('Y-m-d', strtotime('01-' . $fileContent[$key][9])) : null;
                    $expenseModel->remarks = $fileContent[$key][10];
                    $expenseModel->created_by = Yii::$app->user->id;
                    $expenseModel->created_date = date('Y-m-d H:i:s');
                    $expenseModel->save(false);
                }
            }

            $expenseTable = \app\models\PropertyIncome::tableName();
            $connection = \Yii::$app->db;
            $model = $connection->createCommand('SELECT * FROM ' . $expenseTable . ' ORDER BY id DESC LIMIT ' . $totalRows . ' ');
            $expenseData = $model->queryAll();
            $rowIds = [];
            $fileContent = [];
            $headers[] = 'Attachment';
            $totalColumns = count($headers);

            foreach ($expenseData as $key => $row) {
                $rowIds[$key] = $row['id'];
                $fileContent[$key][] = $row['customer_id'];
                $fileContent[$key][] = $row['customer_name'];
                $fileContent[$key][] = $row['income_type'];
                $fileContent[$key][] = $row['total_income_amount'];
                $fileContent[$key][] = $row['transaction_num'];
                $fileContent[$key][] = Yii::$app->userdata->getPaymentModeType($row['transaction_mode']);
                $fileContent[$key][] = (!empty(strtotime($row['paid_on']))) ? date('d-M-Y', strtotime($row['paid_on'])) : '';
                $fileContent[$key][] = $row['received_by_person'];
                $fileContent[$key][] = $row['received_by_entity'];
                $fileContent[$key][] = (!empty($row['month'])) ? date('M-Y', strtotime($row['month'])) : '';
                $fileContent[$key][] = Yii::$app->userdata->getUserNameById($row['approved_by']);
                $fileContent[$key][] = $row['remarks'];
                $fileContent[$key][] = $row['attachment'];
            }

            krsort($fileContent);
            $fileContent = array_values($fileContent);
            krsort($rowIds);
            $rowIds = array_values($rowIds);
        }

        return $this->render('query-income-html', ['fileContent' => $fileContent, 'headers' => $headers, 'property_id' => Yii::$app->request->post('property_id'), 'totalColumns' => $totalColumns, 'rowIds' => $rowIds]);
    }

    public function actionQueryincomedata()
    {
        $this->layout = false;
        if (!empty(Yii::$app->request->post())) {
            $fileContent = [];
            $propertyId = 0;
            $headers = [];
            $headers = $this->getIncomeHeaders();

            $headers[] = "Attachment";
            $totalColumns = count($headers);
            extract(Yii::$app->request->post());

            $incomeTypeSql = $income_type;
            $isAttachedSql = (!empty($is_attached)) ? $is_attached : '';
            $incomeAmountFromSql = $income_amount_from;
            $incomeAmountToSql = $income_amount_to;
            $paidOnFromSql = (!empty($paid_on_from)) ? date('Y-m-d', strtotime($paid_on_from)) : '';
            $paidOnToSql = (!empty($paid_on_to)) ? date('Y-m-d', strtotime($paid_on_to)) : '';
            $createdBySql = $created_by;
            $approvedBySql = $approved_by;
            $monthSql = $month;

            if (!empty($isAttachedSql)) {
                $isAttachedSql = ' ( attachment IS NOT NULL ) AND ';
            }

            if (!empty($createdBySql)) {
                $createdBySql = ' ( created_by = ' . $createdBySql . ' ) AND ';
            }

            if (!empty($incomeTypeSql)) {
                $incomeTypeSql = ' ( income_type = "' . $incomeTypeSql . '" ) AND ';
            }

            if (!empty($approvedBySql)) {
                $approvedBySql = ' ( approved_by LIKE "%' . $approvedBySql . '%" ) AND ';
            }

            if (!empty($monthSql)) {
                $monthSql = ' ( month = "' . date('Y-m-d', strtotime('01-' . $monthSql)) . '" ) AND ';
            }

            $incomeAmountSQL = '';

            if (!empty($incomeAmountFromSql) && !empty($incomeAmountToSql)) {
                $incomeAmountSQL = ' ( total_income_amount >= ' . $incomeAmountFromSql . ' AND total_income_amount <= ' . $incomeAmountToSql . ' ) AND ';
            } else if (!empty($incomeAmountFromSql)) {
                $incomeAmountSQL = ' ( total_income_amount >= ' . $incomeAmountFromSql . ' ) AND ';
            } else if (!empty($incomeAmountToSql)) {
                $incomeAmountSQL = ' ( total_income_amount <= ' . $incomeAmountToSql . ' ) AND ';
            }

            $paidOnSQL = '';

            if (!empty($paidOnFromSql) && !empty($paidOnToSql)) {
                $paidOnSQL = ' ( DATE(paid_on) BETWEEN "' . $paidOnFromSql . '" AND "' . $paidOnToSql . '" ) AND ';
            } else if (!empty($paidOnFromSql)) {
                $paidOnSQL = ' ( DATE(paid_on) >= "' . $paidOnFromSql . '" ) AND ';
            } else if (!empty($paidOnToSql)) {
                $paidOnSQL = ' ( DATE(paid_on) <= "' . $paidOnToSql . '" OR paid_on IS NULL ) AND ';
            }

            $incomeTable = \app\models\PropertyIncome::tableName();
            $connection = \Yii::$app->db;
            $model = $connection->createCommand('SELECT * FROM ' . $incomeTable . ' WHERE '
                . ' ' . $incomeAmountSQL . ' '
                . ' ' . $paidOnSQL . ' '
                . ' ' . $approvedBySql . ' '
                . ' ' . $monthSql . ' '
                . ' ' . $incomeTypeSql . ' '
                . ' ' . $isAttachedSql . ' '
                . ' ' . $createdBySql . ' '
                . ' ( property_id = ' . $property_id . ' ) '
                . ' ORDER BY id DESC '
                . '');

            if ((empty($created_by) && !empty($created_by_placeholder)) || (empty($approved_by) && !empty($approved_by_placeholder))) {
                $incomeData = [];
            } else {
                $incomeData = $model->queryAll();
            }
            $rowIds = [];
            foreach ($incomeData as $key => $row) {
                $rowIds[$key] = $row['id'];
                $fileContent[$key][] = $row['customer_id'];
                $fileContent[$key][] = $row['customer_name'];
                $fileContent[$key][] = $row['income_type'];
                $fileContent[$key][] = $row['total_income_amount'];
                $fileContent[$key][] = $row['transaction_num'];
                $fileContent[$key][] = Yii::$app->userdata->getPaymentModeType($row['transaction_mode']);
                $fileContent[$key][] = (!empty(strtotime($row['paid_on']))) ? date('d-M-Y', strtotime($row['paid_on'])) : '';
                $fileContent[$key][] = $row['received_by_person'];
                $fileContent[$key][] = $row['received_by_entity'];
                $fileContent[$key][] = (!empty($row['month'])) ? date('M-Y', strtotime($row['month'])) : '';
                $fileContent[$key][] = Yii::$app->userdata->getUserNameById($row['approved_by']);
                $fileContent[$key][] = $row['remarks'];
                $fileContent[$key][] = $row['attachment'];
            }

            krsort($fileContent);
            $fileContent = array_values($fileContent);
            krsort($rowIds);
            $rowIds = array_values($rowIds);

            return $this->render('query-income-html', ['fileContent' => $fileContent, 'headers' => $headers, 'property_id' => $property_id, 'totalColumns' => $totalColumns, 'rowIds' => $rowIds]);
        }
    }

    public function actionProcessincomecsv()
    {
        $response = 0;
        $this->layout = false;
        $perm = true;
        $commitChanges = true;
        $postData = Yii::$app->request->post('PropertyIncome');
        $rowId = [];
        if (!empty(Yii::$app->request->post('row_id'))) {
            $rowId = Yii::$app->request->post('row_id');
        }
        if (!empty(Yii::$app->request->post())) {
            $userType = Yii::$app->user->identity->user_type;
            if ($userType == 7) {
                $opUser = \app\models\OperationsProfile::findOne(['operations_id' => Yii::$app->user->identity->id]);
                if ($opUser->role_code == 'OPEXE' && $opUser->role_value != 'ELHQ') {
                    $perm = false;
                }
            } else if ($userType == 6) {
                $salesUser = \app\models\SalesProfile::findOne(['sale_id' => Yii::$app->user->identity->id]);
                if ($salesUser->role_code == 'SLEXE') {
                    $perm = false;
                }
            }
            $totalColumns = Yii::$app->request->post('total_columns');
            if (!empty($postData)) {
                foreach ($postData as $key => $row) {
                    if (!empty($row)) {
                        $count = count($row);
                        if ($count == $totalColumns) {
                            if (!empty($rowId[$key])) {
                                $expenseModel = \app\models\PropertyIncome::find()->where(['id' => $rowId[$key]])->one();
                                if (!$perm && !empty($expenseModel->approved_by)) {
                                    $commitChanges = false;
                                } else {
                                    $commitChanges = true;
                                }
                                $expenseModel->property_id = Yii::$app->request->post('property_id');
                                $expenseModel->customer_id = $postData[$key][0];
                                if (!empty($expenseModel->customer_id)) {
                                    $expenseModel->customer_name = Yii::$app->userdata->getFullNameById($expenseModel->customer_id);
                                } else {
                                    $expenseModel->customer_name = $postData[$key][1];
                                }
                                //$expenseModel->income_type = Yii::$app->userdata->getIncomeCodeByName($postData[$key][2]);
                                $expenseModel->income_type = $postData[$key][2];
                                $expenseModel->total_income_amount = $postData[$key][3];
                                $expenseModel->transaction_num = $postData[$key][4];
                                $expenseModel->transaction_mode = Yii::$app->userdata->getPaymentModeId($postData[$key][5]);
                                $expenseModel->paid_on = (!empty(strtotime($postData[$key][6]))) ? date('Y-m-d', strtotime($postData[$key][6])) : '';
                                $expenseModel->received_by_person = $postData[$key][7];
                                $expenseModel->received_by_entity = Yii::$app->userdata->getEntityTypeName($postData[$key][8]);
                                $expenseModel->month = (!empty(strtotime($postData[$key][9]))) ? date('Y-m-d', strtotime('01-' . $postData[$key][9])) : null;
                                $expenseModel->remarks = $postData[$key][11];
                                $expenseModel->created_by = Yii::$app->user->id;
                                $expenseModel->created_date = date('Y-m-d H:i:s');

                                if (!empty($_FILES['IncomeAttachment']['name'][$key])) {
                                    $path = 'uploads/income/';
                                    $name = date('ymdHis') . str_replace(' ', '', $_FILES['IncomeAttachment']['name'][$key]);
                                    $tempName = $_FILES['IncomeAttachment']['tmp_name'][$key];
                                    move_uploaded_file($tempName, $path . $name);
                                    $expenseModel->attachment = $path . $name;
                                }

                                if ($commitChanges) {
                                    $expenseModel->save(false);
                                }
                            } else {
                                $expenseModel = new \app\models\PropertyIncome();
                                $expenseModel->property_id = Yii::$app->request->post('property_id');
                                $expenseModel->customer_id = $postData[$key][0];
                                if (!empty($expenseModel->customer_id)) {
                                    $expenseModel->customer_name = Yii::$app->userdata->getFullNameById($expenseModel->customer_id);
                                } else {
                                    $expenseModel->customer_name = $postData[$key][1];
                                }
                                //$expenseModel->income_type = Yii::$app->userdata->getIncomeCodeByName($postData[$key][2]);
                                $expenseModel->income_type = $postData[$key][2];
                                $expenseModel->total_income_amount = $postData[$key][3];
                                $expenseModel->transaction_num = $postData[$key][4];
                                $expenseModel->transaction_mode = Yii::$app->userdata->getPaymentModeId($postData[$key][5]);
                                $expenseModel->paid_on = (!empty(strtotime($postData[$key][6]))) ? date('Y-m-d', strtotime($postData[$key][6])) : '';
                                $expenseModel->received_by_person = $postData[$key][7];
                                $expenseModel->received_by_entity = Yii::$app->userdata->getEntityTypeName($postData[$key][8]);
                                $expenseModel->month = (!empty(strtotime($postData[$key][9]))) ? date('Y-m-d', strtotime('01-' . $postData[$key][9])) : null;
                                $expenseModel->remarks = $postData[$key][11];
                                $expenseModel->created_by = Yii::$app->user->id;
                                $expenseModel->created_date = date('Y-m-d H:i:s');

                                if (!empty($_FILES['IncomeAttachment']['name'][$key])) {
                                    $path = 'uploads/income/';
                                    $name = date('ymdHis') . str_replace(' ', '', $_FILES['IncomeAttachment']['name'][$key]);
                                    $tempName = $_FILES['IncomeAttachment']['tmp_name'][$key];
                                    move_uploaded_file($tempName, $path . $name);
                                    $expenseModel->attachment = $path . $name;
                                }

                                $expenseModel->save(false);
                                $rowId[] = \Yii::$app->db->lastInsertID;
                            }
                        }
                    }
                }
            }

            if (!empty(Yii::$app->request->post('delete_property_income'))) {
                $toDelete = json_decode(Yii::$app->request->post('delete_property_income'));
                if (!empty($toDelete)) {
                    foreach ($toDelete as $key => $row) {
                        $pExp = \app\models\PropertyIncome::findOne(['id' => $row]);
                        $pExp->delete();
                    }
                }
            }

            $headers = [];
            $headers = $this->getIncomeHeaders();
            $headers[] = 'Attachment';

            $totalColumns = count($headers);

            $incomeTable = \app\models\PropertyIncome::tableName();
            $connection = \Yii::$app->db;
            $expenseData = [];
            foreach ($rowId as $key => $id) {
                $model = $connection->createCommand('SELECT * FROM ' . $incomeTable . ' WHERE property_id = ' . Yii::$app->request->post('property_id') . ' AND id = ' . $id . ' ORDER BY id DESC ');
                $expenseData[] = $model->queryOne();
            }
            $rowIds = [];
            $fileContent = [];

            foreach ($expenseData as $key => $row) {
                $rowIds[$key] = $row['id'];
                $fileContent[$key][] = $row['customer_id'];
                $fileContent[$key][] = $row['customer_name'];
                $fileContent[$key][] = $row['income_type'];
                $fileContent[$key][] = $row['total_income_amount'];
                $fileContent[$key][] = $row['transaction_num'];
                $fileContent[$key][] = Yii::$app->userdata->getPaymentModeType($row['transaction_mode']);
                $fileContent[$key][] = (!empty(strtotime($row['paid_on']))) ? date('d-M-Y', strtotime($row['paid_on'])) : '';
                $fileContent[$key][] = $row['received_by_person'];
                $fileContent[$key][] = $row['received_by_entity'];
                $fileContent[$key][] = (!empty($row['month'])) ? date('M-Y', strtotime($row['month'])) : '';
                $fileContent[$key][] = Yii::$app->userdata->getUserNameById($row['approved_by']);
                $fileContent[$key][] = $row['remarks'];
                $fileContent[$key][] = $row['attachment'];
            }

            $fileContent = array_values($fileContent);
            $rowIds = array_values($rowIds);
            return $this->render('query-income-html', ['fileContent' => $fileContent, 'headers' => $headers, 'property_id' => Yii::$app->request->post('property_id'), 'totalColumns' => $totalColumns, 'rowIds' => $rowIds]);
        }
    }

    public function actionApproveincomerows()
    {
        $response = 0;
        $this->layout = false;
        $postData = Yii::$app->request->post('PropertyIncome');
        $rowId = Yii::$app->request->post('row_id');
        if (!empty(Yii::$app->request->post())) {
            $totalColumns = Yii::$app->request->post('total_columns');
            $totalRows = count($postData);
            if (!empty(Yii::$app->request->post('approve_property_income_rows'))) {
                $toApprove = json_decode(Yii::$app->request->post('approve_property_income_rows'));
                if (!empty($toApprove)) {
                    foreach ($toApprove as $key => $row) {
                        $pInc = \app\models\PropertyIncome::findOne(['id' => $row]);
                        if (!empty($pInc->customer_name) && !empty($pInc->income_type) && !empty($pInc->total_income_amount) && !empty($pInc->received_by_entity) && !empty($pInc->month)) {
                            $pInc->approved_by = Yii::$app->user->id;
                            $pInc->save(false);
                        }
                    }
                }
            }

            $headers = [];
            $headers = $this->getIncomeHeaders();
            $headers[] = 'Attachment';

            $totalColumns = count($headers);

            $incomeTable = \app\models\PropertyIncome::tableName();
            $connection = \Yii::$app->db;
            $incomeData = [];
            foreach ($rowId as $key => $id) {
                $model = $connection->createCommand('SELECT * FROM ' . $incomeTable . ' WHERE property_id = ' . Yii::$app->request->post('property_id') . ' AND id = ' . $id . ' ORDER BY id DESC ');
                $incomeData[] = $model->queryOne();
            }

            $rowIds = [];
            $fileContent = [];
            foreach ($incomeData as $key => $row) {
                $rowIds[$key] = $row['id'];
                $fileContent[$key][] = $row['customer_id'];
                $fileContent[$key][] = $row['customer_name'];
                $fileContent[$key][] = $row['income_type'];
                $fileContent[$key][] = $row['total_income_amount'];
                $fileContent[$key][] = $row['transaction_num'];
                $fileContent[$key][] = Yii::$app->userdata->getPaymentModeId($row['transaction_mode']);
                $fileContent[$key][] = (!empty(strtotime($row['paid_on']))) ? date('d-M-Y', strtotime($row['paid_on'])) : '';
                $fileContent[$key][] = $row['received_by_person'];
                $fileContent[$key][] = $row['received_by_entity'];
                $fileContent[$key][] = (!empty($row['month'])) ? date('M-Y', strtotime($row['month'])) : '';
                $fileContent[$key][] = Yii::$app->userdata->getUserNameById($row['approved_by']);
                $fileContent[$key][] = $row['remarks'];
                $fileContent[$key][] = $row['attachment'];
            }

            $fileContent = array_values($fileContent);
            $rowIds = array_values($rowIds);

            return $this->render('query-income-html', ['fileContent' => $fileContent, 'headers' => $headers, 'property_id' => Yii::$app->request->post('property_id'), 'totalColumns' => $totalColumns, 'rowIds' => $rowIds]);
        }
    }

    public function actionApproveexpenserows()
    {
        $response = 0;
        $this->layout = false;
        $postData = Yii::$app->request->post('PropertyExpense');
        $rowId = Yii::$app->request->post('row_id');
        if (!empty(Yii::$app->request->post())) {
            $totalColumns = Yii::$app->request->post('total_columns');
            $totalRows = count($postData);
            if (!empty(Yii::$app->request->post('approve_property_expense_rows'))) {
                $toApprove = json_decode(Yii::$app->request->post('approve_property_expense_rows'));
                if (!empty($toApprove)) {
                    foreach ($toApprove as $key => $row) {
                        $pExp = \app\models\PropertyExpense::findOne(['id' => $row]);
                        if (!empty($pExp->expense_type) && !empty($pExp->total_expense_amount) && !empty($pExp->paid_by_entity) && !empty($pExp->month)) {
                            $pExp->approved_by = Yii::$app->user->id;
                            $pExp->save(false);
                        }
                    }
                }
            }

            $headers = [];
            $headers = $this->getExpenseHeaders();
            $headers[] = 'Attachment';

            $totalColumns = count($headers);

            $expTable = \app\models\PropertyExpense::tableName();
            $connection = \Yii::$app->db;
            $incomeData = [];
            foreach ($rowId as $key => $id) {
                $model = $connection->createCommand('SELECT * FROM ' . $expTable . ' WHERE property_id = ' . Yii::$app->request->post('property_id') . ' AND id = ' . $id . ' ORDER BY id DESC ');
                $incomeData[] = $model->queryOne();
            }

            $rowIds = [];
            $fileContent = [];
            foreach ($incomeData as $key => $row) {
                $rowIds[$key] = $row['id'];
                $fileContent[$key][] = $row['expense_type'];
                $fileContent[$key][] = $row['total_expense_amount'];
                $fileContent[$key][] = (!empty(strtotime($row['paid_on']))) ? date('d-M-Y', strtotime($row['paid_on'])) : '';
                $fileContent[$key][] = $row['paid_by'];
                $fileContent[$key][] = $row['paid_by_entity'];
                $fileContent[$key][] = $row['vendor'];
                $fileContent[$key][] = (!empty(strtotime($row['invoice_date']))) ? date('d-M-Y', strtotime($row['invoice_date'])) : '';
                $fileContent[$key][] = $row['gst_invoice'];
                $fileContent[$key][] = $row['vendor_gst'];
                $fileContent[$key][] = $row['total_gst'];
                $fileContent[$key][] = (!empty($row['month'])) ? date('M-Y', strtotime($row['month'])) : '';
                $fileContent[$key][] = Yii::$app->userdata->getUserNameById($row['approved_by']);
                $fileContent[$key][] = $row['remarks'];
                $fileContent[$key][] = $row['attachment'];
            }

            $fileContent = array_values($fileContent);
            $rowIds = array_values($rowIds);

            return $this->render('query-expense-html', ['fileContent' => $fileContent, 'headers' => $headers, 'property_id' => Yii::$app->request->post('property_id'), 'totalColumns' => $totalColumns, 'rowIds' => $rowIds]);
        }
    }

    public function actionExportincometocsv()
    {
        $this->layout = false;
        $response = 0;
        $headers = [];
        $headers = $this->getIncomeHeaders();
        unset($headers[10]);
        $postData = Yii::$app->request->post('PropertyIncome');
        if (!empty(Yii::$app->request->post())) {
            $propName = Yii::$app->userdata->getPropertyNameById(Yii::$app->request->post('property_id'));
            $now = microtime();
            $fileName = str_replace(' ', '-', $propName . '-' . $now . '.csv');
            $path = 'uploads/income/temp/';
            $file = fopen($path . $fileName, 'w+');
            fputcsv($file, $headers);
            if (!empty($postData)) {
                foreach ($postData as $key => $row) {
                    if (!empty($row)) {
                        if (!empty($row[2])) {
                            $row[2] = Yii::$app->userdata->getIncomeNameByCode($row[2]);
                        }
                        unset($row[10]);
                        fputcsv($file, $row);
                    }
                }
            }

            fclose($file);

            echo $fileName;
            exit;
        }
    }

    public function actionDownloadincomecsv($d)
    {
        $fileName = $d;
        $this->layout = false;
        $path = 'uploads/income/temp/';
        if (!empty($fileName) && !empty(@file_get_contents($path . $fileName))) {
            $content = file_get_contents($path . $fileName);
            unlink($path . $fileName);
            header("Content-Type: text/csv");
            header("Content-Disposition: attachment;filename={$fileName}");
            header("Content-Transfer-Encoding: binary");
            echo $content;
        }
        exit;
    }

    public function actionPronloss()
    {
        $incomeType = \app\models\IncomeType::find()->all();
        $this->view->params['incomeTypes'] = $incomeType;
        return $this->render('profitnloss');
    }

    public function actionGetpronlossreport()
    {
        $this->layout = false;

        if (!empty(Yii::$app->request->post())) {
            extract(Yii::$app->request->post());

            $propertyId = $property_id;
            $statementType = $pnl_statement_type;
            $expenseFromMonth = (int) $pnl_statement_from_month;
            $expenseFromQuarterly = (int) $pnl_statement_from_quarterly;
            $expenseFromFY = (int) $pnl_statement_from_fy;
            $expenseFromYear = (int) $pnl_statement_from_year;

            $statementTypeSQL = '';
            if ($statementType == 1) {
                $paidOnSQL = ' MONTH(month) = ' . $expenseFromMonth . ' AND '
                    . 'YEAR(month) = ' . $expenseFromYear . ' AND ';
            }

            if ($statementType == 2) {
                $quarterArr = [
                    1 => 1,
                    2 => 4,
                    3 => 7,
                    4 => 10,
                ];
                $paidOnSQL = ' (MONTH(month) BETWEEN "' . $quarterArr[$expenseFromQuarterly] . '" AND '
                    . '"' . ($quarterArr[$expenseFromQuarterly] + 2) . '") AND '
                    . '(YEAR(month) = ' . $expenseFromYear . ') AND ';
            }

            if ($statementType == 3) {
                $paidOnSQL = ' YEAR(month) = "' . $expenseFromYear . '" AND ';
            }

            if ($statementType == 4) {
                $currentYear = $expenseFromFY;
                $nextYear = $expenseFromFY + 1;
                $paidOnSQL = 'month BETWEEN "' . $currentYear . '-04-01" AND "' . $nextYear . '-03-01" AND ';
            }

            $profitShareRatio = Yii::$app->userdata->getProfitShareRatio($property_id);

            ////////////// INCOME AND TRANSFER FOR EASYLEASES ////////////////////////////////

            $incomeTable = \app\models\PropertyIncome::tableName();
            $connection = \Yii::$app->db;
            $model = $connection->createCommand('SELECT SUM(total_income_amount) AS total_income FROM ' . $incomeTable . ' WHERE '
                . ' ' . $paidOnSQL . ' '
                . ' property_id = ' . $property_id . ' AND '
                . ' received_by_entity = "Easyleases" AND '
                . ' approved_by IS NOT NULL '
                . ' ORDER BY id DESC '
                . '');

            $incomeData = $model->queryOne();
            $totalEasyIncome = $incomeData['total_income']; ////////////////////// TOTAL EASYLEASES INCOME

            $model = $connection->createCommand('SELECT SUM(total_income_amount) AS total_income FROM ' . $incomeTable . ' WHERE '
                . ' ' . $paidOnSQL . ' '
                . ' property_id = ' . $property_id . ' AND '
                . ' received_by_entity = "Easyleases" AND '
                . ' approved_by IS NOT NULL AND '
                . ' (income_type = "TRI" OR income_type = "TRO") '
                . ' ORDER BY id DESC '
                . '');

            $incomeData = $model->queryOne();
            $totalEasyTrans = $incomeData['total_income']; /////////////////////// TOTAL EASYLEASES TRANSFER
            ////////////// INCOME AND TRANSFER FOR PG OPERATOR ////////////////////////////////

            $model = $connection->createCommand('SELECT SUM(total_income_amount) AS total_income FROM ' . $incomeTable . ' WHERE '
                . ' ' . $paidOnSQL . ' '
                . ' property_id = ' . $property_id . ' AND '
                . ' received_by_entity = "PG Operator" AND '
                . ' approved_by IS NOT NULL '
                . ' ORDER BY id DESC '
                . '');

            $incomeData = $model->queryOne();
            $totalPGIncome = $incomeData['total_income']; //////////////////////// TOTAL PG INCOME

            $model = $connection->createCommand('SELECT SUM(total_income_amount) AS total_income FROM ' . $incomeTable . ' WHERE '
                . ' ' . $paidOnSQL . ' '
                . ' property_id = ' . $property_id . ' AND '
                . ' received_by_entity = "PG Operator" AND '
                . ' approved_by IS NOT NULL AND '
                . ' (income_type = "TRI" OR income_type = "TRO") '
                . ' ORDER BY id DESC '
                . '');

            $incomeData = $model->queryOne();
            $totalPGTrans = $incomeData['total_income']; ///////////////////////// TOTAL PG TRANSFER
            ////////////// EXPENSE FOR EASYLEASES ////////////////////////////////

            $expenseTable = \app\models\PropertyExpense::tableName();
            $connection = \Yii::$app->db;
            $model = $connection->createCommand('SELECT SUM(total_expense_amount) AS total_expense FROM ' . $expenseTable . ' WHERE '
                . ' ' . $paidOnSQL . ' '
                . ' property_id = ' . $property_id . ' AND '
                . ' paid_by_entity = "Easyleases" AND '
                . ' approved_by IS NOT NULL '
                . ' ORDER BY id DESC '
                . '');

            $expenseData = $model->queryOne();
            $totalEasyExpense = $expenseData['total_expense']; ////////////////////// TOTAL EASYLEASES EXPENSE
            ////////////// EXPENSE FOR PG OPERATOR ////////////////////////////////

            $model = $connection->createCommand('SELECT SUM(total_expense_amount) AS total_expense FROM ' . $expenseTable . ' WHERE '
                . ' ' . $paidOnSQL . ' '
                . ' property_id = ' . $property_id . ' AND '
                . ' paid_by_entity = "PG Operator" AND '
                . ' approved_by IS NOT NULL '
                . ' ORDER BY id DESC '
                . '');

            $expenseData = $model->queryOne();
            $totalPGExpense = $expenseData['total_expense']; //////////////////////// TOTAL PG EXPENSE

            return $this->render('pnl-reporting-html', [
                'property_id' => $property_id,
                'paidOnSQL' => $paidOnSQL,
                'totalEasyIncome' => $totalEasyIncome,
                'totalEasyTrans' => $totalEasyTrans,
                'totalPGIncome' => $totalPGIncome,
                'totalPGTrans' => $totalPGTrans,
                'totalEasyExpense' => $totalEasyExpense,
                'totalPGExpense' => $totalPGExpense,
                'profitShareRatio' => $profitShareRatio,
            ]);
        }
    }

    public function actionGetexpensepnlrows()
    {
        $this->layout = false;
        $headers = [];
        $headers = $this->getExpenseHeaders();

        if (!empty(Yii::$app->request->post())) {
            extract(Yii::$app->request->post());

            $propertyId = $property_id;

            $expenseTable = \app\models\PropertyExpense::tableName();
            $connection = \Yii::$app->db;
            $model = $connection->createCommand('SELECT * FROM ' . $expenseTable . ' WHERE '
                . ' ' . $paidOnSQL . ' '
                . ' property_id = ' . $property_id . ' '
                . ' ORDER BY id DESC '
                . '');

            $expenseData = $model->queryAll();

            //print_r($expenseData);exit;

            return $this->render('pnl-view-transaction-html', [
                'property_id' => $property_id,
                'expenseData' => $expenseData,
                'totalColumns' => count($headers),
                'headers' => $headers
            ]);
        }
    }

    public function actionGetincomepnlrows()
    {
        $this->layout = false;
        $headers = [];
        $headers = $this->getIncomeHeaders();

        if (!empty(Yii::$app->request->post())) {
            extract(Yii::$app->request->post());

            $propertyId = $property_id;

            $incomeTable = \app\models\PropertyIncome::tableName();
            $connection = \Yii::$app->db;
            $model = $connection->createCommand('SELECT * FROM ' . $incomeTable . ' WHERE '
                . ' ' . $paidOnSQL . ' '
                . ' property_id = ' . $property_id . ' '
                . ' ORDER BY id DESC '
                . '');

            $incomeData = $model->queryAll();

            return $this->render('pnl-view-transaction-html', [
                'property_id' => $property_id,
                'incomeData' => $incomeData,
                'totalColumns' => count($headers),
                'headers' => $headers
            ]);
        }
    }

    public function actionPgonboard()
    {
        $model = new \app\models\TenantRegistration();
        $this->layout = 'operations_console';
        return $this->render('pg_onboard', [
            'model' => $model
        ]);
    }

    public function actionTenantpayrecord()
    {
        $subData = [];
        $paymentData = [];
        if (!empty(Yii::$app->request->post('tenant_id')) && !empty(Yii::$app->request->post('record_type'))) {
            $subData['record_type'] = Yii::$app->request->post('record_type');
            $subData['tenant_id'] = Yii::$app->request->post('tenant_id');
            $subData['tenant_list'] = Yii::$app->request->post('tenant_list');
            if (Yii::$app->request->post('record_type') == 1) {
                $datedue = Date('Y-m-d', strtotime('+1 month'));
                $sql = 'SELECT tp.id, tp.parent_id, tp.payment_des, tp.payment_status, tp.total_amount, tp.due_date, tp.payment_type as payment_for, tp.penalty_amount, tp.neft_reference,ta.late_penalty_percent, ta.min_penalty FROM tenant_payments tp INNER JOIN tenant_agreements ta ON tp.agreement_id = ta.id WHERE tp.tenant_id="' . $subData['tenant_id'] . '" and tp.due_date <"' . $datedue . '" and tp.payment_status IN (0, 2) ORDER BY due_date';
                $connection = \Yii::$app->db;
                $model = $connection->createCommand($sql);
                $paymentData = $model->queryAll();
            }

            if (Yii::$app->request->post('record_type') == 2) {
                $sql = 'SELECT `id`, `parent_id`, `payment_des`, `payment_status`,`total_amount`,`due_date`, "Rent" as `payment_for` FROM `tenant_payments` WHERE tenant_id="' . $subData['tenant_id'] . '" and due_date >="' . date('Y-m-d', strtotime('+30 days', strtotime(Date('Y-m-d')))) . '" and payment_status=0 UNION ALL SELECT ac.`id`, ta.`parent_id`, ac.`title` as payment_des, ac.`payment_status`, ac.`charge_to_tenant` as total_amount, `payment_due_date` as due_date, "Adhoc Charges" as `payment_for` FROM `adhoc_charge` as ac left join tenant_agreements as ta on ac.tenant_id=ta.tenant_id WHERE ac.tenant_id="' . $subData['tenant_id'] . '" and ac.payment_due_date >="' . date('Y-m-d', strtotime('+30 days', strtotime(Date('Y-m-d')))) . '" and ac.payment_status=0 ORDER BY due_date';
                $connection = \Yii::$app->db;
                $model = $connection->createCommand($sql);
                $paymentData = $model->queryAll();
            }

            if (Yii::$app->request->post('record_type') == 3) {
                $sql = 'SELECT tp.id, tp.payment_date, tp.parent_id, tp.payment_des, tp.payment_status, tp.total_amount, tp.due_date, "Rent" as payment_for, tp.penalty_amount, tp.amount_paid, ta.late_penalty_percent, ta.min_penalty FROM tenant_payments tp INNER JOIN tenant_agreements ta ON tp.agreement_id = ta.id WHERE tp.tenant_id="' . $subData['tenant_id'] . '" AND tp.payment_status = 1 ORDER BY payment_date desc';
                $connection = \Yii::$app->db;
                $model = $connection->createCommand($sql);
                $paymentData = $model->queryAll();
            }
        }

        return $this->render('internal_tenant_payment', [
            'paymentData' => $paymentData,
            'subData' => $subData
        ]);
    }

    public function actionApplicantsinterested()
    {
        $subData = [];
        $favProData = [];
        if (!empty(Yii::$app->request->post('property_id')) && !empty(Yii::$app->request->post('property_list'))) {
            $subData['property_id'] = Yii::$app->request->post('property_id');
            $subData['property_list'] = Yii::$app->request->post('property_list');
            $datedue = Date('Y-m-d', strtotime('+1 month'));
            $sql = 'SELECT fp.id, u.full_name, fp.status as shortlist_type, fp.visit_date, fp.visit_time FROM favourite_properties fp INNER JOIN users u ON u.login_id = fp.applicant_id WHERE fp.property_id="' . $subData['property_id'] . '" ';
            $connection = \Yii::$app->db;
            $model = $connection->createCommand($sql);
            $favProData = $model->queryAll();
            //print_r($sql);exit;
        }

        return $this->render('applicant_interested', [
            'favProData' => $favProData,
            'subData' => $subData
        ]);
    }

    public function actionStopagreenotification()
    {
        if (Yii::$app->request->post('type') == 't') {
            $model = \app\models\TenantAgreements::find()->where(['id' => Yii::$app->request->post('id')])->one();
            if (Yii::$app->request->post('action') == 'start') {
                $model->notice_status = 0;
            } else {
                $model->notice_status = 1;
            }
            $model->save(false);
            echo $model->notice_status;
        } else if ($_POST['type'] == 'p') {
            $model = \app\models\PropertyAgreements::find()->where(['id' => Yii::$app->request->post('id')])->one();
            if (Yii::$app->request->post('action') == 'start') {
                $model->notice_status = 0;
            } else {
                $model->notice_status = 1;
            }
            $model->save(false);
            echo $model->notice_status;
        } else {
            return false;
        }
    }
}
