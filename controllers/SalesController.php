<?php

namespace app\controllers;

use Yii;
use app\models\Users;
use app\models\Advisors;
use app\models\Owner;
use app\models\LeadsAdvisor;
use app\models\LeadsOwner;
use app\models\LeadsTenant;
use app\models\ApplicantProfile;
use app\models\AdvisorProfile;
use app\models\Register;
use app\models\Forget;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\PasswordForm;
use app\models\ForgotPassword;
use yii\web\UploadedFile;
use yii\helpers\Url;
use app\models\AdvisorAgreements;

/**
 * UsersController implements the CRUD actions for Users model.
 */
class SalesController extends Controller {

    public $layout = 'sales';

    /**
     * @inheritdoc
     */
    public function init() {
        // $exclude_array=Array(
        // 	'sales/editproperty',
        // 	'sales/createapplicant',
        // ); 
        $request = Yii::$app->request;

        $action = explode("/", $this->module->requestedRoute);
        $newAction = Array();
        foreach ($action as $link) {
            if (trim($link) != '') {
                $newAction[] = $link;
            }
        }

        $action = implode("/", $newAction);
        // if(!in_array($action,$exclude_array)){
        if (!$request->isAjax) {
            if (Yii::$app->user->id) {
                $checked = Yii::$app->userdata->checkvalid($action, Yii::$app->user->identity->user_type);
                // print_r($checked);
                if ($checked['status'] == 0) {
                    $this->redirect($checked['action']);
                }
            }
        }
        // }
    }

    public function actionSendmail() {
        $message = new YiiMailMessage;
        $message->subject = 'My TestSubject';
        $message->setBody($params, 'text/html');
        $message->addTo('yourmail@domain.com');
        $message->from = 'admin@domain .com';
        Yii::app()->mail->send($message);
    }

    public function behaviors() {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['changepassword', 'addproperty', 'editproperties', 'editproperty', 'ownerssdetails', 'getsalesname', 'index', 'myprofile', 'hotleads', 'details', 'editprofile', 'applicants', 'advisers', 'getproperty', 'owners', 'createapplicant', 'createadviser', 'applicantsdetails', 'createowner', 'addfollowup'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
                /*  'verbs' => [
                  'class' => VerbFilter::className(),
                  'actions' => [
                  'delete' => ['POST'],
                  ],
                  ], */
        ];
    }

    public function actionGetproperty() {
        /*
         * sales admin data 
         * */

        $userAssignments = \app\models\RegionAssignment::find()->where(['user_id' => Yii::$app->user->id])->one();
        if ($userAssignments) {
            if ($userAssignments->type = 1) {
                $assinee = \app\models\OwnerProfile::find()->where(['state' => $userAssignments->assign_region_id])->all();
            } else if ($userAssignments->type = 2) {
                $assinee = \app\models\OwnerProfile::find()->where(['city' => $userAssignments->assign_region_id])->all();
            } else if ($userAssignments->type = 3) {
                $assinee = \app\models\OwnerProfile::find()->where(['region' => $userAssignments->assign_region_id])->all();
            }
        } else {
            $assinee = \app\models\OwnerProfile::find()->where(['sales_id' => Yii::$app->user->id])->all();
        }


        $ownersIds = [];
        foreach ($assinee as $owners) {
            $ownersIds[] = $owners->owner_id;
        }

        $modelFav = \app\models\FavouriteProperties::find()->where(['applicant_id' => base64_decode(Yii::$app->getRequest()->getQueryParam('applicant_id'))])->all();

        $property_id = [];
        foreach ($modelFav as $fav) {
            $property_id[] = $fav->property_id;
        }

        $searchVal = Yii::$app->getRequest()->getQueryParam('term');
        $properties = \app\models\Properties::find()->where(['IN', 'owner_id', $ownersIds])->andFilterWhere(['like', 'property_name', $searchVal])->all();

        $description_arr = [];
        foreach ($properties as $key => $property) {
            if (!in_array($property->id, $property_id)) {
                $description_arr[$key]['value'] = $property->id;
                $description_arr[$key]['label'] = $property->property_name;
            }
        }
        echo json_encode($description_arr);

        die;
    }

    public function actionMyaccount() {
        $connection = Yii::$app->getDb();

        $main_id = Yii::$app->user->id;
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
        $data = Array();

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
    }

    public function actionDetails($id) {

        if (($model = \app\models\LeadsReferences::findOne($id)) !== null) {

            return $this->render('details', ['model' => $model]);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionDeleteimage() {

        $modelImages = \app\models\PropertyImages::findOne($_POST['id']);
        if ($modelImages) {
            $modelImages->delete();
        }
    }

    public function actionGetoperationsname($term) {

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

    public function actionGetsalesname($term) {

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

    public function actionAddnewagreement($id) {



        $modelPropertyAgreements = new \app\models\PropertyAgreements;

        if ($modelPropertyAgreements->load(Yii::$app->request->post())) {
            $model = \app\models\Properties::findOne($modelPropertyAgreements->property_id);
            $agree_url = UploadedFile::getInstance($modelPropertyAgreements, 'agreement_url');
            if ($agree_url) {
                $agreement = date('ymdHis') . $agree_url;
                $agree_url->saveAs('uploads/' . $agreement);

                $modelPropertyAgreements->agreement_url = Url::home(true) . 'uploads/' . $agreement;
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
                    'success', 'Agreement added successfully'
            );

            return $this->redirect(['ownerssdetails', 'id' => base64_encode($lead_id->id)]);
        } else {

            Yii::$app->getSession()->setFlash(
                    'success', 'there is some error'
            );
            return $this->redirect(['ownerssdetails', 'id' => base64_encode($lead_id->id)]);
        }
    }

    public function actionAddagreement($id) {

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
        } else if ($modelPropertyAgreements->load(Yii::$app->request->post())) {


            $property_type = Yii::$app->userdata->getPropertyTypeId($model->id);


            try {
                $agree_url = UploadedFile::getInstance($modelPropertyAgreements, 'agreement_url');
                if ($agree_url) {
                    $agreement = date('ymdHis') . $agree_url;
                    $agree_url->saveAs('uploads/' . $agreement);

                    $modelPropertyAgreements->agreement_url = Url::home(true) . 'uploads/' . $agreement;
                }
                $modelPropertyAgreements->updated_by = Yii::$app->user->id;
                $modelPropertyAgreements->updated_date = date('Y-m-d H:i:s');

                $modelPropertyAgreements->save(false);


                Yii::$app->getSession()->setFlash(
                        'success', 'Agreement updated successfully'
                );
                $ids = Yii::$app->userdata->getUserEmailById($model->owner_id);
                $idLead = Yii::$app->userdata->getLeadByEmailId($ids);

                return $this->redirect(['ownerssdetails', 'id' => base64_encode($idLead)]);
            } catch (Exception $e) {

                Yii::$app->getSession()->setFlash(
                        'success', 'Property updated successfully'
                );
                $ids = Yii::$app->userdata->getUserEmailById($model->owner_id);
                $idLead = Yii::$app->userdata->getLeadByEmailId($ids);

                return $this->redirect(['ownerssdetails', 'id' => base64_encode($idLead)]);
            }
        } else {

            return $this->renderAjax('edit_property', [
                        'model' => $model,
                        'modelImages' => $modelImages,
                        'modelPropertyAgreements' => $modelPropertyAgreements,
            ]);
        }
    }

    public function actionEditproperties($id) {
        $id = base64_decode($id);

        $model = \app\models\Properties::findOne($id);
        $modelImages = \app\models\PropertyImages::find()->where(['property_id' => $id])->all();
        $modelPropertyAgreements = \app\models\PropertyAgreements::findOne(['property_id' => $id]);
        if (empty($modelPropertyAgreements)) {
            $modelPropertyAgreements = new \app\models\PropertyAgreements;
        }
        if ($model->load(Yii::$app->request->post())) {
            /* 	echo  "<pre>";print_r($model['beds']);
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
                if ($model->status == 0) {
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
                }



                Yii::$app->getSession()->setFlash(
                        'success', 'Property added successfully'
                );
                return $this->render('edit_properties', [
                            'model' => $model,
                            'modelImages' => $modelImages,
                            'modelPropertyAgreements' => $modelPropertyAgreements,
                ]);
            } else {
                return $this->render('edit_properties', [
                            'model' => $model,
                            'modelImages' => $modelImages,
                            'modelPropertyAgreements' => $modelPropertyAgreements,
                ]);
            }
        } else {
            return $this->render('edit_properties', [
                        'model' => $model,
                        'modelImages' => $modelImages,
                        'modelPropertyAgreements' => $modelPropertyAgreements,
            ]);
        }
    }

    public function actionEditpropertyajax($id) {
        if (Yii::$app->userdata->getPropertyBookedStatus($id)) {
            Yii::$app->getSession()->setFlash(
                    'notice', 'Property Can Not Be Edited'
            );
            return "error";
        }
        $model = \app\models\Properties::findOne($id);
        $modelImages = \app\models\PropertyImages::find()->where(['property_id' => $id])->all();
        $modelPropertyAgreements = \app\models\PropertyAgreements::findOne(['property_id' => $id]);
        if (empty($modelPropertyAgreements)) {
            $modelPropertyAgreements = new \app\models\PropertyAgreements;
        }

        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            try {
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
                    $modelChild->status = 1;
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
                    for ($i = 0; $i < $model['beds_room']; $i++) {

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
                            $modelChildBeds->property_code = $unique2;
                            $modelChildBeds->parent = $modelChildRooms->id;
                            $modelChildBeds->status = 1;
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
                        'success', 'Property updated successfully'
                );
                $ids = Yii::$app->userdata->getUserEmailById($model->owner_id);
                $idLead = Yii::$app->userdata->getLeadByEmailId($ids);
                return "success";
                //return $this->redirect(['ownerssdetails','id' => base64_encode($idLead)]);
            } catch (Exception $e) {

                Yii::$app->getSession()->setFlash(
                        'success', 'Property updated successfully'
                );
                $ids = Yii::$app->userdata->getUserEmailById($model->owner_id);
                $idLead = Yii::$app->userdata->getLeadByEmailId($ids);
                return "error";
                //return $this->redirect(['ownerssdetails','id' => base64_encode($idLead)]);
            }
        }
    }

    public function actionEditproperty($id) {
        $model = \app\models\Properties::findOne($id);
        if (Yii::$app->userdata->getPropertyBookedStatus($id)) {
            Yii::$app->getSession()->setFlash(
                    'notice', 'Property Can Not Be Edited'
            );
            $ids = Yii::$app->userdata->getUserEmailById($model->owner_id);
            $idLead = Yii::$app->userdata->getLeadByEmailId($ids);

            return $this->redirect(['ownerssdetails', 'id' => base64_encode($idLead)]);
        }
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
                if ($model->status == 0) {
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
                        $modelChild->status = 1;
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
                            $modelChildRooms->status = 1;
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
                                $modelChildBeds->status = 1;
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

                $model->save(false);

                Yii::$app->getSession()->setFlash(
                        'success', 'Property updated successfully'
                );
                $ids = Yii::$app->userdata->getUserEmailById($model->owner_id);
                $idLead = Yii::$app->userdata->getLeadByEmailId($ids);

                return $this->redirect(['ownerssdetails', 'id' => base64_encode($idLead)]);
            } catch (Exception $e) {

                Yii::$app->getSession()->setFlash(
                        'success', 'Property updated successfully'
                );
                $ids = Yii::$app->userdata->getUserEmailById($model->owner_id);
                $idLead = Yii::$app->userdata->getLeadByEmailId($ids);

                return $this->redirect(['ownerssdetails', 'id' => base64_encode($idLead)]);
            }
        } else {
            return $this->renderAjax('edit_property', [
                        'model' => $model,
                        'modelImages' => $modelImages,
                        'modelPropertyAgreements' => $modelPropertyAgreements,
            ]);
        }
        //  die($id);
    }

    public function actionAddproperty($id) {
        $id = base64_decode($id);
        $model = new \app\models\Properties;
        $modelImages = new \app\models\PropertyImages;
        $modelPropertyAgreements = new \app\models\PropertyAgreements;



        if ($model->load(Yii::$app->request->post())) {
            $model->created_by = Yii::$app->user->id;
            $model->created_date = date('Y-m-d H:i:s');
            $model->owner_id = $id;
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
                    for ($i = 0; $i < $model['beds_room']; $i++) {

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
                        for ($j = 0; $j < $_POST['Properties']['beds'][$i]; $j++) {

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

                // $image_url = UploadedFile::getInstance($modelImages, 'image_url');
                // $image = date('ymdHis').$image_url;
                // $image_url->saveAs('uploads/' . $image );
                //    $modelImages->property_id =  $_POST["property_id"] ;
                //    $modelImages->image_url =  Url::home(true). 'uploads/'.$image ;
                //    $modelImages->image_uploaded_date =  date('Y-m-d H:i:s') ;
                //    $modelImages->save(false);
                // Yii::$app->getSession()->setFlash(
                //      'success','Property added successfully'
                //  );

                return $this->redirect(['editproperties', 'id' => base64_encode($model->id)]);
            } else {
                return $this->render('add_property', [
                            'model' => $model,
                            'modelImages' => $modelImages,
                            'modelPropertyAgreements' => $modelPropertyAgreements,
                ]);
            }
        } else {
            return $this->render('add_property', [
                        'model' => $model,
                        'modelImages' => $modelImages,
                        'modelPropertyAgreements' => $modelPropertyAgreements,
            ]);
        }
    }

    public function actionAddpropertylisting($id) {
        $id = base64_decode($id);
        $model = new \app\models\Properties;
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            $model->created_by = Yii::$app->user->id;
            $model->created_date = date('Y-m-d H:i:s');
            $model->owner_id = $id;
            if ($model->property_type == 3) {
                $model->is_room = 0;
            }

            if ($model->validate()) {

                if (isset($_POST['property_id'])) {

                    $property_get = \app\models\Properties::findOne(['id' => $_POST['property_id']]);


                    $connection = Yii::$app->getDb();
                    $command = $connection->createCommand('UPDATE `properties` SET `property_name`="' . $model->property_name . '",`property_type`="' . $model->property_type . '",`flat_type`="' . $model->flat_type . '",`property_description`="' . $model->property_description . '",`address_line_1`="' . $model->address_line_1 . '",`lat`="' . $model->lat . '",`lon`="' . $model->lon . '",`city`="' . $model->city . '",`state`="' . $model->state . '",`region`="' . $model->region . '",`pincode`="' . $model->pincode . '",`flat_bhk`="' . $model->flat_bhk . '",`flat_area`="' . $model->flat_area . '",`address_line_2`="' . $model->address_line_2 . '",`status`="' . $model->status . '" WHERE id="' . $_POST['property_id'] . '"');
                    $command->query();


                    if ($model->property_type == 3) {

                        $today = date("Ymd");
                        $rand = strtoupper(substr(uniqid(sha1(time())), 0, 4));
                        $unique = $today . $rand;
                        \app\models\ChildProperties::deleteAll('main = :pid', [':pid' => $_POST['property_id']]);

                        $modelChild = new \app\models\ChildProperties;
                        $modelChild->property_code = $unique;
                        $modelChild->parent = $_POST['property_id'];
                        $modelChild->status = 0;
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
                        $modelListing->save(false);
                    } else {


                        $today = date("Ymd");
                        $rand = strtoupper(substr(uniqid(sha1(time())), 0, 4));
                        $unique = $today . $rand;
                        \app\models\ChildProperties::deleteAll('main = :pid', [':pid' => $model->id]);

                        $modelChild = new \app\models\ChildProperties;
                        $modelChild->property_code = $unique;
                        $modelChild->parent = $_POST['property_id'];
                        $modelChild->status = 0;
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
                        $modelListing->save(false);
                        for ($i = 0; $i < $model['beds_room']; $i++) {

                            $rand1 = strtoupper(substr(uniqid(sha1(time())), 0, 5));
                            $unique1 = $today . $rand1;
                            $modelChildRooms = new \app\models\ChildProperties;
                            $modelChildRooms->property_code = $unique1;
                            $modelChildRooms->parent = $_POST['property_id'];
                            $modelChildRooms->status = 0;
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
                                $modelChildBeds->property_code = $unique2;
                                $modelChildBeds->parent = $modelChildRooms->id;
                                $modelChildBeds->status = 0;
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
                    return $_POST['property_id'];
                } else {
                    $model->save(false);
                    if ($model->property_type == 3) {

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
                        for ($i = 0; $i < $model['beds_room']; $i++) {

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
                            for ($j = 0; $j < $_POST['Properties']['beds'][$i]; $j++) {

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


                    return $model->id;
                }
            } else {
                return 'error';
            }
        }
    }

    public function actionApplicantsdetails($id) {

        $proofType = \app\models\ProofType::find()->all();
        $id = base64_decode($id);
        $modelComments = new \app\models\Comments();
        $properties_fav = Array();
        // echo  $id ; die ;
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

                    // $contactaddress = 	$model->emer_contact_address  ;
                    // $contactindentity = 	$model->emer_contact_indentity  ;
                    if ($models->load(Yii::$app->request->post())) {
                        //	echo "<pre>";	print_r($_POST['LeadsTenant']['follow_up_date_time']);die;
                        $models->follow_up_date_time = date('Y-m-d H:i:s', strtotime($_POST['LeadsTenant']['follow_up_date_time']));
                        $models->save(false);

                        $model->status = $models->ref_status;
                        $model->sales_id = $_POST['ApplicantProfile']['sales_id'];
                        $model->save(false);


                        Yii::$app->getSession()->setFlash(
                                'leadssuccess', 'Leads information is saved successfully.'
                        );

                        return $this->render('applicant_details', ['model' => $model, 'modelLeads' => $models, 'modelComments' => $modelComments,
                                    'comments' => $comments,
                                    'fav' => $fav,
                                    'props' => $properties_fav,
                                    'proofType' => $proofType,
                                    'emergency_proofs' => $emergency_proofs,
                                    'address_proofs' => $address_proofs,
                        ]);
                    }

                    if ($model->load(Yii::$app->request->post())) {

                        if ($model->validate()) {

                            $profile_image = UploadedFile::getInstance($model, 'profile_image');
                            if (isset($_FILES['address_proof'])) {
                                $address_proof = $_FILES['address_proof'];
                            } else {
                                $address_proof = Array();
                            }
                            // $address_proof = UploadedFile::getInstance($model, 'address_proof');
                            // $identity_proof = UploadedFile::getInstance($model, 'identity_proof');
                            $cancelled_check = UploadedFile::getInstance($model, 'cancelled_check');
                            $employmnet_proof_url = UploadedFile::getInstance($model, 'employmnet_proof_url');
                            // $emer_contact_indentity = UploadedFile::getInstance($model, 'emer_contact_indentity');
                            // $emer_contact_address = UploadedFile::getInstance($model, 'emer_contact_address');
                            $model->operation_id = $_POST['ApplicantProfile']['operation_id'];
                            //	echo $_POST['ApplicantProfile']['operation_id']  ; die;
                            // if(!empty($emer_contact_indentity)){
                            // 	$name_emer_contact_indentity = date('ymdHis').$emer_contact_indentity->name ;
                            // 	$emer_contact_indentity->saveAs('uploads/proofs/' . $name_emer_contact_indentity );
                            // 	$model->emer_contact_indentity =  Url::home(true).'uploads/proofs/'.$name_emer_contact_indentity ;
                            // } else {
                            // 	   $model->emer_contact_indentity	= $contactindentity   ;
                            // }
                            if (isset($_FILES['emergency_proof'])) {
                                $emer_contact_address = $_FILES['emergency_proof'];
                            } else {
                                $emer_contact_address = Array();
                            }
                            if (!empty($emer_contact_address)) {
                                foreach ($emer_contact_address['tmp_name'] as $key => $value) {

                                    foreach ($value as $key1 => $value1) {
                                        $emergency_proofobj = new \app\models\EmergencyProofs;
                                        $name_address_proof = (string) date('ymdHis') . rand() . $emer_contact_address['name'][$key][$key1];
                                        $tmp_file = (string) $value1;
                                        move_uploaded_file($value1, 'uploads/proofs/' . $name_address_proof);
                                        $emergency_proofobj->proof_type = $key;
                                        $emergency_proofobj->proof_document_url = Url::home(true) . 'uploads/proofs/' . $name_address_proof;
                                        $emergency_proofobj->user_id = $ids;
                                        $emergency_proofobj->created_by = $ids;
                                        $emergency_proofobj->created_date = date('Y-m-d H:i:s');
                                        $emergency_proofobj->save(false);
                                    }
                                }
                            }
                            // if(!empty($emer_contact_address)){
                            // 	$name_emer_contact_address = date('ymdHis').$emer_contact_address->name ;
                            // 	$emer_contact_address->saveAs('uploads/proofs/' . $name_emer_contact_address );
                            // 	$model->emer_contact_address =  Url::home(true).'uploads/proofs/'.$name_emer_contact_address ;
                            // } else {
                            // 	   $model->emer_contact_address	= $contactaddress  ;
                            // }


                            if (!empty($cancelled_check)) {
                                $name_cancelled_check = date('ymdHis') . $cancelled_check->name;
                                $cancelled_check->saveAs('uploads/proofs/' . $name_cancelled_check);
                                $model->cancelled_check = Url::home(true) . 'uploads/proofs/' . $name_cancelled_check;
                            } else {
                                $model->cancelled_check = $bankCheck;
                            }

                            if (!empty($profile_image)) {
                                $name_profile_image = date('ymdHis') . $profile_image->name;
                                $profile_image->saveAs('uploads/profiles/' . $name_profile_image);
                                $model->profile_image = Url::home(true) . 'uploads/profiles/' . $name_profile_image;
                            } else {
                                $model->profile_image = $profileimage;
                            }

                            if (!empty($employmnet_proof_url)) {
                                $name_employmnet_proof_url = date('ymdHis') . $employmnet_proof_url->name;
                                $employmnet_proof_url->saveAs('uploads/proofs/' . $name_employmnet_proof_url);
                                $model->employmnet_proof_url = Url::home(true) . 'uploads/proofs/' . $name_employmnet_proof_url;
                            } else {
                                $model->employmnet_proof_url = $employmnetProof;
                            }
                            $model->save(false);

                            $modelUsers = Users::findOne($ids);
                            $modelUsers->login_id = $model->email_id;
                            $modelUsers->save(false);

                            $modelLeadsTenant = LeadsTenant::findOne(['email_id' => $model->email_id]);

                            $modelLeadsTenant->email_id = $model->email_id;
                            $modelLeadsTenant->contact_number = $model->phone;
                            $modelLeadsTenant->address = $model->address_line_1;
                            $modelLeadsTenant->address_line_2 = $model->address_line_2;
                            $modelLeadsTenant->state = $model->state;
                            $modelLeadsTenant->city = $model->city;
                            $modelLeadsTenant->region = $model->region;
                            $modelLeadsTenant->pincode = $model->pincode;
                            $modelLeadsTenant->updated_date = date('Y-m-d H:i:s');
                            $modelLeadsTenant->save(false);

                            // if(!empty($address_proof)){
                            // 	$address =   \app\models\UserIdProofs::find()->where(['user_id'=>$ids,'proof_type'=>'address'])->one() ;
                            // 	 if( $address != null){
                            // 		 $address->delete();
                            // 	 }
                            // 	$address_proofobj = new \app\models\UserIdProofs ;
                            // 	$name_address_proof = date('ymdHis').$address_proof->name ;
                            // 	$address_proof->saveAs('uploads/proofs/' . $name_address_proof );
                            // 	$address_proofobj->proof_type = 'address';
                            // 	$address_proofobj->proof_document_url = Url::home(true).'uploads/proofs/' .$name_address_proof ;
                            // 	$address_proofobj->user_id =  $ids ;
                            // 	$address_proofobj->created_by = Yii::$app->user->id;
                            // 	$address_proofobj->created_date = date('Y-m-d H:i:s');
                            // 	$address_proofobj->save(false);
                            //  }
                            // if(!empty($identity_proof)){
                            // 	$identity =   \app\models\UserIdProofs::find()->where(['user_id'=>$ids,'proof_type'=>'identity'])->one() ;
                            // 	 if( $identity != null){
                            // 		 $identity->delete();
                            // 	 }
                            // 	$identity_proofobj = new \app\models\UserIdProofs ;
                            // 	$name_identity_proof = date('ymdHis').$identity_proof->name ;
                            // 	$identity_proof->saveAs('uploads/proofs/' . $name_identity_proof );
                            // 	$identity_proofobj->proof_type = 'identity';
                            // 	$identity_proofobj->proof_document_url =  Url::home(true).'uploads/proofs/' .$name_identity_proof ;
                            // 	$identity_proofobj->user_id = $ids  ;
                            // 	$identity_proofobj->created_by = Yii::$app->user->id;
                            // 	$identity_proofobj->created_date = date('Y-m-d H:i:s');
                            // 	$identity_proofobj->save(false);
                            //  }
                            // echo "<pre/>";
                            if (!empty($address_proof)) {
                                foreach ($address_proof['tmp_name'] as $key => $value) {

                                    foreach ($value as $key1 => $value1) {
                                        $address_proofobj = new \app\models\UserIdProofs;
                                        $name_address_proof = (string) date('ymdHis') . rand() . $address_proof['name'][$key][$key1];
                                        $tmp_file = (string) $value1;
                                        move_uploaded_file($value1, 'uploads/proofs/' . $name_address_proof);
                                        $address_proofobj->proof_type = $key;
                                        $address_proofobj->proof_document_url = Url::home(true) . 'uploads/proofs/' . $name_address_proof;
                                        $address_proofobj->user_id = $ids;
                                        $address_proofobj->created_by = $ids;
                                        $address_proofobj->created_date = date('Y-m-d H:i:s');
                                        // print_r($address_proofobj);

                                        $address_proofobj->save(false);
                                    }
                                }
                                // die;								
                            }

                            Yii::$app->getSession()->setFlash(
                                    'success', 'Profile is saved successfully.'
                            );

                            return $this->render('applicant_details', ['model' => $model, 'fav' => $fav, 'modelComments' => $modelComments, 'modelLeads' => $models, 'comments' => $comments,
                                        'proofType' => $proofType,
                                        'props' => $properties_fav,
                                        'emergency_proofs' => $emergency_proofs,
                                        'address_proofs' => $address_proofs,]);
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

    public function actionOwnerssdetails($id) {

        $id = base64_decode($id);
        $proofType = \app\models\ProofType::find()->all();
        $modelComments = new \app\models\Comments();
        // echo  $id ; die ;
        if (($models = LeadsOwner::findOne($id)) !== null) {


            $ids = Yii::$app->userdata->getUserIdByEmail($models->email_id);
            $address_proofs = \app\models\UserIdProofs::find()->where(['user_id' => $ids])->all();
            if ($ids) {
                $comments = \app\models\Comments::find()->where(['user_id' => $ids])->orderBy(['id' => SORT_DESC])->all();
                $properties = \app\models\Properties::find()->where(['owner_id' => $ids])->joinWith('propertyListing')->all();

                if (($model = \app\models\OwnerProfile::find()->where(['owner_id' => $ids])->one()) != null) {
                    $bankCheck = $model->cancelled_check;
                    $profileimage = $model->profile_image;

                    $modelUser = Users::findOne($ids);
                    if ($models->load(Yii::$app->request->post())) {
                        //	echo "<pre>";	print_r($_POST['LeadsTenant']['follow_up_date_time']);die;
                        $models->follow_up_date_time = date('Y-m-d H:i:s', strtotime($_POST['LeadsOwner']['follow_up_date_time']));
                        $models->save(false);

                        $model->status = $models->ref_status;
                        $model->sales_id = $_POST['OwnerProfile']['sales_id'];
                        $model->save(false);


                        Yii::$app->getSession()->setFlash(
                                'leadssuccess', 'Leads information is saved successfully.'
                        );

                        return $this->render('owner_details', ['model' => $model, 'modelUser' => $modelUser, 'modelLeads' => $models, 'modelComments' => $modelComments, 'comments' => $comments, 'properties' => $properties, 'proofType' => $proofType, 'address_proofs' => $address_proofs,]);
                    }
                    if ($model->load(Yii::$app->request->post()) && $modelUser->load(Yii::$app->request->post())) {

                        if ($model->validate() && $modelUser->validate(['login_id'])) {
                            // $address_proof = UploadedFile::getInstance($model, 'address_proof');
                            // $identity_proof = UploadedFile::getInstance($model, 'identity_proof');
                            $cancelled_check = UploadedFile::getInstance($model, 'cancelled_check');
                            $profile_image = UploadedFile::getInstance($model, 'profile_image');

                            if (!empty($cancelled_check)) {
                                $name_cancelled_check = date('ymdHis') . $cancelled_check->name;
                                $cancelled_check->saveAs('uploads/proofs/' . $name_cancelled_check);
                                $model->cancelled_check = Url::home(true) . 'uploads/proofs/' . $name_cancelled_check;
                            } else {
                                $model->cancelled_check = $bankCheck;
                            }

                            if (isset($_FILES['address_proof'])) {
                                $address_proof = $_FILES['address_proof'];
                            } else {
                                $address_proof = Array();
                            }

                            if (!empty($profile_image)) {
                                $name_profile_image = date('ymdHis') . $profile_image->name;
                                $profile_image->saveAs('uploads/profiles/' . $name_profile_image);
                                $model->profile_image = Url::home(true) . 'uploads/profiles/' . $name_profile_image;
                            } else {
                                $model->profile_image = $profileimage;
                            }
                            $model->operation_id = $_POST['OwnerProfile']['operation_id'];
                            $model->save(false);

                            $modelUser->save(false);

                            $modelLeadsTenant = LeadsOwner::findOne(['email_id' => $modelUser->login_id]);
                            $modelLeadsTenant->email_id = $modelUser->login_id;
                            $modelLeadsTenant->contact_number = $model->phone;
                            $modelLeadsTenant->address = $model->address_line_1;
                            $modelLeadsTenant->state = $model->state;
                            $modelLeadsTenant->city = $model->city;
                            $modelLeadsTenant->region = $model->region;
                            $modelLeadsTenant->pincode = $model->pincode;
                            $modelLeadsTenant->updated_date = date('Y-m-d H:i:s');
                            $modelLeadsTenant->save(false);

                            if (!empty($address_proof)) {
                                foreach ($address_proof['tmp_name'] as $key => $value) {

                                    foreach ($value as $key1 => $value1) {
                                        $address_proofobj = new \app\models\UserIdProofs;
                                        $name_address_proof = (string) date('ymdHis') . rand() . $address_proof['name'][$key][$key1];
                                        $tmp_file = (string) $value1;
                                        move_uploaded_file($value1, 'uploads/proofs/' . $name_address_proof);
                                        $address_proofobj->proof_type = $key;
                                        $address_proofobj->proof_document_url = Url::home(true) . 'uploads/proofs/' . $name_address_proof;
                                        $address_proofobj->user_id = $ids;
                                        $address_proofobj->created_by = $ids;
                                        $address_proofobj->created_date = date('Y-m-d H:i:s');
                                        $address_proofobj->save(false);
                                    }
                                }
                            }
                            // if(!empty($address_proof)){
                            // 	$address =   \app\models\UserIdProofs::find()->where(['user_id'=> $ids ,'proof_type'=>'address'])->one() ;
                            // 	 if( $address != null){
                            // 		 $address->delete();
                            // 	 }
                            //     $address_proofobj = new \app\models\UserIdProofs ;
                            // 	$name_address_proof = date('ymdHis').$address_proof->name ;
                            // 	$address_proof->saveAs('uploads/proofs/' . $name_address_proof );
                            // 	$address_proofobj->proof_type = 'address';
                            // 	$address_proofobj->proof_document_url = Url::home(true).'uploads/proofs/' .$name_address_proof ;
                            // 	$address_proofobj->user_id =  $ids ;
                            // 	$address_proofobj->created_by = Yii::$app->user->id;
                            // 	$address_proofobj->created_date = date('Y-m-d H:i:s');
                            // 	$address_proofobj->save(false);
                            //  }
                            // if(!empty($identity_proof)){
                            // 	$identity =   \app\models\UserIdProofs::find()->where(['user_id'=> $ids ,'proof_type'=>'identity'])->one() ;
                            // 	 if( $identity != null){
                            // 		 $identity->delete();
                            // 	 }
                            //     $identity_proofobj = new \app\models\UserIdProofs ;
                            // 	$name_identity_proof = date('ymdHis').$identity_proof->name ;
                            // 	$identity_proof->saveAs('uploads/proofs/' . $name_identity_proof );
                            // 	$identity_proofobj->proof_type = 'identity';
                            // 	$identity_proofobj->proof_document_url =  Url::home(true).'uploads/proofs/' .$name_identity_proof ;
                            // 	$identity_proofobj->user_id =  $ids ;
                            // 	$identity_proofobj->created_by = Yii::$app->user->id;
                            // 	$identity_proofobj->created_date = date('Y-m-d H:i:s');
                            // 	$identity_proofobj->save(false);
                            //  }

                            Yii::$app->getSession()->setFlash('success', 'Your profile is saved successfully.');

                            // return $this->render('owner_details', ['model' => $model,'modelUser' => $modelUser,'modelLeads' => $models,'modelComments' => $modelComments,'comments' => $comments ,'properties'=>$properties,'proofType'=>$proofType,'address_proofs'=>$address_proofs,  ]);
                            return $this->redirect(['owners']);
                        } else {
                            return $this->render('owner_details', ['model' => $model, 'modelUser' => $modelUser, 'modelLeads' => $models, 'modelComments' => $modelComments,
                                        'comments' => $comments, 'properties' => $properties, 'proofType' => $proofType, 'address_proofs' => $address_proofs,
                            ]);
                        }
                    } else {
                        return $this->render('owner_details', ['model' => $model, 'modelUser' => $modelUser, 'modelLeads' => $models, 'modelComments' => $modelComments,
                                    'comments' => $comments, 'properties' => $properties, 'proofType' => $proofType, 'address_proofs' => $address_proofs,
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

    public function actionMyprofile() {
        $assinee = [];
        if (($model = \app\models\SalesProfile::findOne(['sale_id' => Yii::$app->user->id])) != null) {

            // $userAssignments = \app\models\RegionAssignment::find()->where(['user_id'=>Yii::$app->user->id])->one() ;
            $main_id = Yii::$app->user->id;
            $role = \app\models\Users::find()->select('role')->where(['id' => $main_id])->one();
            $condition1 = '';
            switch ($role->role) {
                case 1:
                    $roles = \app\models\SalesProfile::find()->select('region')->where(['sale_id' => $main_id])->all();
                    $roles_cond = [];
                    foreach ($roles as $key => $value) {
                        $roles_cond[] = $value['region'];
                    }
                    //$condition1="`region`=$roles->region";
                    break;
                case 2:
                    $roles = \app\models\SalesProfile::find()->select('region')->where(['sale_id' => $main_id])->all();
                    $roles_cond = [];
                    foreach ($roles as $key => $value) {
                        $roles_cond[] = $value['region'];
                    }
                    //$condition1="`region`=$roles->region";
                    break;
                case 3:
                    $cities = \app\models\SalesProfile::find()->select('city')->where(['sale_id' => $main_id])->one();
                    $roles = \app\models\Regions::find()->where(['city_id' => $cities->city])->all();
                    $roles_cond = [];
                    foreach ($roles as $key => $value) {
                        $roles_cond[] = $value['id'];
                    }
                    break;
                case 4:
                    $states = \app\models\SalesProfile::find()->select('state')->where(['sale_id' => $main_id])->one();
                    $cities = \app\models\Cities::find()->where(['state_id' => $states->state])->all();
                    $city_array = [];
                    foreach ($cities as $key => $value) {
                        $city_array[] = $value['id'];
                    }
                    $city_array = implode(",", $city_array);
                    $roles_cond = [];
                    $roles = \app\models\Regions::find()->where("city_id IN ($city_array)")->all();
                    foreach ($roles as $key => $value) {
                        $roles_cond[] = $value['id'];
                    }
                    break;
            }
            $regionss = \app\models\Regions::find()->select(['id', 'name'])->where('id in (' . implode(",", $roles_cond) . ')')->all();

            // if($userAssignments){
            // 	 $assinee = \app\models\UserAssignments::find()->where(['manager_id'=>Yii::$app->user->id])->orWhere(['user_id'=>Yii::$app->user->id])->with('region')->asArray()->all(); 
            // }

            return $this->render('myprofile', ['model' => $model, 'assinee' => $regionss]);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionEditprofile() {

        $this->layout = 'app_dashboard';

        if (($model = \app\models\Profiles::findOne(Yii::$app->user->id)) !== null) {

            if ($model->load(Yii::$app->request->post()) && $model->save()) {

                Yii::$app->getSession()->setFlash(
                        'success', 'Your profile is saved successfully.'
                );
                return $this->redirect(['myprofile']);
            } else {

                return $this->render('editprofile', [
                            'model' => $model,
                ]);
            }
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionGetcity() {

        $id = Yii::$app->request->post('val');

        $model = \app\models\Cities::find()->where(['state_id' => $id])->all();
        $listData = \yii\helpers\ArrayHelper::map($model, 'id', 'city_name');

        $html = '<option value=""> Select city </option>';
        foreach ($listData as $key => $list) {
            $html .= '<option value="' . $key . '">' . $list . '</option>';
        }
        echo $html;
        die;
    }

    public function actionIndex() {

        $connection = Yii::$app->getDb();
        $command = $connection->createCommand('select * from leads_tenant where DATE(`follow_up_date_time`)=CURDATE()');
        $countApplicant = count($command->queryAll());

        $command1 = $connection->createCommand('select * from leads_owner where DATE(`follow_up_date_time`)=CURDATE()');
        $countOwner = count($command1->queryAll());

        $command2 = $connection->createCommand('select * from leads_advisor where DATE(`follow_up_date_time`)=CURDATE() OR DATE(`schedule_date_time`)=CURDATE()');
        $countAdviser = count($command2->queryAll());


        return $this->render('index', Array('applicant' => $countApplicant, 'adviser' => $countAdviser, 'owner' => $countOwner));
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

    public function actionChangepassword() {


        $model = new PasswordForm;
        $modeluser = Users::find()->where([
                    'username' => Yii::$app->user->identity->username
                ])->one();

        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = 'json';
            return \yii\bootstrap\ActiveForm::validate($model);
        } else if ($model->load(Yii::$app->request->post())) {

            try {
                $modeluser->password = md5($_POST['PasswordForm']['newpass']);
                if ($modeluser->save(false)) {
                    Yii::$app->getSession()->setFlash(
                            'success', 'Password changed'
                    );
                    return $this->redirect(['myprofile']);
                } else {
                    Yii::$app->getSession()->setFlash(
                            'success', 'Password not changed'
                    );
                    return $this->redirect(['myprofile']);
                }
            } catch (Exception $e) {
                Yii::$app->getSession()->setFlash(
                        'success', "{$e->getMessage()}"
                );
                return $this->renderAjax('change_password', [
                            'model' => $model
                ]);
            }
        } else {
            return $this->renderAjax('change_password', [
                        'model' => $model
            ]);
        }
    }

    public function actionAddfollowup($id) {
        $this->layout = 'ajax';
        $model = new \app\models\LeadsReferencesSchedules;

        return $this->render('_followup', ['model' => $model]);
    }

    public function actionSavefollow() {
        $model = new \app\models\LeadsReferencesSchedules;
        if ($model->load(Yii::$app->request->post())) {

            $model->created_by = Yii::$app->user->id;
            $model->create_time = date('Y-m-d H:i:s');
            $model->save(false);
            Yii::$app->getSession()->setFlash('success', 'Follow Up created successfully');
        }
    }

    public function actionSaveattributes() {
        \app\models\PropertyAttributeMap::deleteAll('property_id = :pid', [':pid' => $_POST['property_id']]);

        $attribute_vals = Array();
        foreach (explode(",", substr($_POST['property_map'], 1)) as $key => $value) {
            $map_values = explode("-", $value);
            $attribute_vals[$map_values[0]] = $map_values[1];
        }

        $attribute = $_POST['attributes'];

        $attributeArray = explode(',', $attribute);

        foreach ($attributeArray as $vlue) {
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
        die;
    }

    public function actionCreateapplicant() {


        $model = new LeadsTenant;
        $modelusers = new Users;
        $modelprofile = new ApplicantProfile;
        $comments = new \app\models\Comments;
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = 'json';
            return \yii\bootstrap\ActiveForm::validate($model);
        } else if ($model->load(Yii::$app->request->post())) {

            try {
                $date = $_POST['follow_up_date'];
                $time = $_POST['follow_up_time'];
                $date_formed = Date('Y-m-d h:i:s', strtotime($date . " " . $time));


                $modelusers->full_name = $model->full_name;
                $modelusers->login_id = $model->email_id;
                $modelusers->username = $model->email_id;
                $modelusers->password = md5(Yii::$app->userdata->passwordGenerate());
                $modelusers->user_type = 2;
                $modelusers->save(false);


                $modelprofile->applicant_id = $modelusers->id;
                $modelprofile->sales_id = Yii::$app->user->id;
                $modelprofile->email_id = $model->email_id;
                $modelprofile->address_line_1 = $model->address;
                $modelprofile->address_line_2 = $model->address_line_2;
                $modelprofile->region = $_POST['LeadsTenant']['region'];
                $modelprofile->city = $model->city;
                $modelprofile->state = $model->state;
                $modelprofile->pincode = $model->pincode;
                $modelprofile->phone = $model->contact_number;
                $modelprofile->save(false);


                $model->created_by = Yii::$app->user->id;
                $model->created_date = date('Y-m-d H:i:s');
                $model->follow_up_date_time = $date_formed;

                $comments->description = $model->communication;
                $comments->user_id = $modelusers->id;
                $comments->created_by = Yii::$app->user->id;
                $comments->save(false);

                if ($model->save(false)) {
                    Yii::$app->getSession()->setFlash(
                            'success', 'Applicant created successfully'
                    );
                    return $this->redirect(['applicants']);
                } else {
                    Yii::$app->getSession()->setFlash(
                            'success', 'Applicant is not created'
                    );
                    return $this->redirect(['applicants']);
                }
            } catch (Exception $e) {
                Yii::$app->getSession()->setFlash(
                        'success', "{$e->getMessage()}"
                );
                return $this->renderAjax('_form', [
                            'model' => $model
                ]);
            }
        } else {
            return $this->renderAjax('_form', [
                        'model' => $model
            ]);
        }
    }

    public function actionSaveschdule() {

        $model = \app\models\FavouriteProperties::findOne($_POST['fav_id']);

        if ($model && Yii::$app->request->isAjax) {

            $model->visit_date = date('Y-m-d', strtotime($_POST['datesch']));
            $model->visit_time = date('H:i:s', strtotime($_POST['timesch']));
            $model->updated_by = Yii::$app->user->id;
            $model->status = 3;
            $model->save(false);
            echo "done";
        }
    }

    public function actionSetasdefault($id) {
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("UPDATE property_images SET is_main= 0 where property_id=$_POST[property_id]");
        $command->execute();
        $command2 = $connection->createCommand("UPDATE property_images SET is_main= 1 where id=$id");
        $command2->execute();
    }

    public function actionSaveimage() {

        $model = new \app\models\PropertyImages;

        if (Yii::$app->request->isAjax) {

            $image_url = UploadedFile::getInstance($model, 'image_url');
            $image = date('ymdHis') . $image_url;
            $image_url->saveAs('uploads/' . $image);

            $model->property_id = $_POST["property_id"];
            $model->image_url = Url::home(true) . 'uploads/' . $image;
            $model->image_uploaded_date = date('Y-m-d H:i:s');
            $model->save(false);
            echo $model->id;
        }
    }

    // public function actionSaveimagenew(){
    // 	$model =  new \app\models\PropertyImages;
    // 	if( Yii::$app->request->isAjax)
    // 	{
    // 		$image_url = UploadedFile::getInstance($model, 'image_url');
    // 		$image = date('ymdHis').$image_url;
    // 		$image_url->saveAs('uploads/' . $image );
    // 		echo Url::home(true). 'uploads/'.$image ;
    // 	}
    // }


    public function actionAddcomment() {

        $model = new \app\models\Comments;

        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            $model->description = $_POST['Comments']['description'];
            $model->created_by = Yii::$app->user->id;
            //$model->created_date = date('Y-m-d H:i:s');
            $model->user_id = $_POST['Comments']['user_id'];

            $model->save(false);
            echo "done";
        } else {
            $model->description = $_POST['comments'];
            $model->created_by = Yii::$app->user->id;
            //$model->created_date = date('Y-m-d H:i:s');
            $model->user_id = $_POST['user_id'];

            $model->save(false);
            echo "done";
        }
        // else{
        // 	echo "error";
        // }
    }

    public function actionAddcomplexfac($id) {

        $id = base64_decode($id);
        $model = new \app\models\PropertyAttributeMap;

        if (isset($_POST['attributes'])) {
            $attribute = $_POST['attributes'];
            $attributeArray = explode(',', $attribute);
            foreach ($attributeArray as $vlue) {
                $model = new \app\models\PropertyAttributeMap;
                $model->attr_id = $vlue;
                $model->value = 1;
                $model->property_id = $id;
                $model->save(false);
            }
        }
        return $this->render('_complex', [
                    'model' => $model, 'id' => $id
        ]);
    }

    public function actionCreatefav() {


        $model = new \app\models\FavouriteProperties;

        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = 'json';
            return \yii\bootstrap\ActiveForm::validate($model);
        } else if ($model->load(Yii::$app->request->post())) {

            try {
                $model->created_by = Yii::$app->user->id;
                $model->visit_time = date('H:i:s', strtotime($_POST['visit_time']));
                $model->visit_date = date('H:i:s', strtotime($_POST['visit_date']));
                $model->created_date = date('Y-m-d H:i:s');
                $property_type = \app\models\Properties::find()->select('property_type')->where(['id' => $model->property_id])->one();
                $model->type = $property_type->property_type;
                $model->child_properties = $model->property_id;
                $model->save(false);
                $email = $model->applicant_id;
                $userdata = \app\models\LeadsTenant::find()->select('id')->where(['email_id' => $email])->one();
                Yii::$app->getSession()->setFlash(
                        'success', 'Property added successfully'
                );
                return $this->redirect(['applicantsdetails?id=' . base64_encode($userdata->id)]);
            } catch (Exception $e) {
                Yii::$app->getSession()->setFlash(
                        'success', "{$e->getMessage()}"
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

    public function actionActivateproperty() {
        $property = \app\models\Properties::findOne(['id' => $_POST['val']]);
        $property->status = $_POST['status'];
        if ($_POST['status'] == 0) {
            if (!Yii::$app->userdata->getPropertyBookedStatus($_POST['val'])) {
                $property->save(false);
                if (!Yii::$app->userdata->checkAllActiveProperties($_POST['val'])) {
                    $user = \app\models\Users::findOne(['id' => $property->owner_id]);
                    $user->status = 0;
                    $user->save(false);
                }
                return true;
            } else {
                return false;
            }
        } else {
            if (Yii::$app->userdata->getOwnerStatusByProperty($_POST['val']) == 1) {
                $property->save(false);
                return true;
            } else {
                return false;
            }
        }
    }

    public function actionCreatelease() {
        $model = new \app\models\TenantAgreements;
        $leads_Applicant = \app\models\LeadsTenant::findOne(['email_id' => Yii::$app->userdata->getUserEmailById($model->tenant_id)]);
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = 'json';
            return \yii\bootstrap\ActiveForm::validate($model);
        } else if ($model->load(Yii::$app->request->post())) {


            try {
                $agree_url = UploadedFile::getInstance($model, 'agreement_url');
                if ($agree_url) {
                    $agreement = date('ymdHis') . $agree_url;
                    $agree_url->saveAs('uploads/' . $agreement);

                    $model->agreement_url = Url::home(true) . 'uploads/' . $agreement;
                }
                $model->created_by = Yii::$app->user->id;
                $model->created_date = date('Y-m-d H:i:s');
                $model->save(false);

                $modelFav = \app\models\FavouriteProperties::find()->where(['property_id' => $model->parent_id, 'child_properties' => $model->property_id])->one();
                $modelFav1 = \app\models\FavouriteProperties::findAll(['applicant_id' => Yii::$app->userdata->getUserEmailById($model->tenant_id)]);
                foreach ($modelFav1 as $Key => $value) {
                    $value->delete();
                }


                $walletAmount = \app\models\Wallets::findOne(['user_id' => $model->id]);



                $modelUser = \app\models\Users::findOne(['id' => $model->tenant_id]);
                $modelUser->user_type = 3;
                $modelUser->save(false);





                $modelUserProfile = \app\models\ApplicantProfile::findOne(['applicant_id' => $model->tenant_id]);

                /*                 * ******************************** Payment Calculations ************************************** */
                $propertyAgreements = \app\models\PropertyAgreements::findOne(['property_id' => $model->parent_id]);
                $agreement_type = $propertyAgreements->agreement_type;
                if ($agreement_type == 2) {
                    $months = ceil((strtotime($model->lease_end_date) - strtotime($model->lease_start_date)) / (60 * 60 * 24 * 30));
                    $startDateObject = date_create($model->lease_start_date);
                    $endDateObject = date_create($model->lease_end_date);
                    $startDateLease = $startDateObject->format('Y') . "-" . (int) $startDateObject->format('m') . "-" . (int) $startDateObject->format('d');
                    $rentArray = Array();
                    $maintenanceArray = Array();

                    for ($i = 0; $i < $months; $i++) {
                        $rentArray[] = $propertyAgreements->rent;
                        $rentArray1[] = $propertyAgreements->rent;
                        $maintenanceArray[] = $propertyAgreements->maintainance;
                        $tenant_rent_Array[] = $model->rent;
                        $tenant_maintenance_Array[] = $model->maintainance;
                    }

                    $days = cal_days_in_month(CAL_GREGORIAN, $startDateObject->format('m'), $startDateObject->format('Y'));
                    $rentArray[0] = ceil(((int) $propertyAgreements->rent / (int) $days) * ((int) $days - (int) $startDateObject->format('d')));
                    $rentArray1[0] = ceil(((int) $propertyAgreements->rent / (int) $days) * ((int) $days - (int) $startDateObject->format('d')));
                    $maintenanceArray[0] = ceil(((int) $propertyAgreements->maintainance / (int) $days) * ((int) $days - (int) $startDateObject->format('d')));
                    $tenant_rent_Array[0] = ceil(((int) $model->rent / (int) $days) * ((int) $days - (int) $startDateObject->format('d')));
                    $tenant_maintenance_Array[0] = ceil(((int) $model->maintainance / (int) $days) * ((int) $days - (int) $startDateObject->format('d')));


                    $lastdays = $endDateObject->format('d');
                    $rentArray[count($rentArray) - 1] = ceil(((int) $propertyAgreements->rent / (int) $days) * ($lastdays));
                    $rentArray1[count($rentArray1) - 1] = ceil(((int) $propertyAgreements->rent / (int) $days) * ($lastdays));
                    $maintenanceArray[count($maintenanceArray) - 1] = ceil(((int) $propertyAgreements->maintainance / (int) $days) * ($lastdays));
                    $tenant_rent_Array[count($tenant_rent_Array) - 1] = ceil(((int) $model->rent / (int) $days) * ((int) $days - (int) $startDateObject->format('d')));
                    $tenant_maintenance_Array[count($tenant_maintenance_Array) - 1] = ceil(((int) $model->maintainance / (int) $days) * ((int) $days - (int) $startDateObject->format('d')));

                    $walletAmount = \app\models\Wallets::findOne(['user_id' => $model->tenant_id]);
                    if (count($walletAmount) == 1 and $walletAmount->amount != 0) {
                        $wallet_amount = $walletAmount->amount;
                        $rent = $model->rent;
                        if ((int) $wallet_amount <= (int) $rentArray[0]) {
                            $rentArray[0] = (int) $rentArray[0] - (int) $wallet_amount;
                            $wallet_amount = 0;
                        } else {
                            $wallet_amount = (int) $wallet_amount - (int) $rentArray[0];
                            $rentArray[0] = 0;
                            $total_rent = 0;
                            for ($i = 1; $i < count($rentArray); $i++) {
                                $total_rent = (int) $total_rent + $rentArray[$i];
                            }

                            if ($wallet_amount <= (int) $total_rent) {
                                for ($i = 1; $i <= count($rentArray) - 1; $i++) {
                                    if ((int) $rentArray[$i] < (int) $wallet_amount) {
                                        $wallet_amount = (int) $wallet_amount - (int) $rentArray[$i];
                                        $rentArray[$i] = 0;
                                    } else {
                                        $rentArray[$i] = (int) $rentArray[$i] - (int) $wallet_amount;
                                        $wallet_amount = 0;
                                    }
                                }
                            } else {
                                for ($i = 1; $i < count($rentArray); $i++) {
                                    $rentArray[$i] = 0;
                                }
                                $wallet_amount = (int) $wallet_amount - $total_rent;
                            }
                        }
                    }
                    $is = 0;
                    foreach ($rentArray as $key => $value) {
                        $tenantPayments = new \app\models\TenantPayments;
                        $tenantPayments->tenant_id = $modelUserProfile->applicant_id;
                        $tenantPayments->property_id = $model->property_id;
                        $tenantPayments->parent_id = $model->parent_id;
                        $tenantPayments->owner_amount = $tenant_rent_Array[$key];
                        $tenantPayments->original_amount = $value;
                        $tenantPayments->maintenance = $tenant_maintenance_Array[$key];
                        $tenantPayments->payment_des = 'Rent Against Property For ' . Date('m-Y', strtotime($startDateLease));
                        $tenantPayments->payment_type = '2';
                        $tenantPayments->created_by = Yii::$app->user->id;
                        $tenantPayments->total_amount = (int) $value + (int) $maintenanceArray[$key];
                        if ($is == 0) {
                            $tenantPayments->due_date = Date('Y-m-d', (strtotime($model->lease_start_date) + (60 * 60 * 24 * 5)));
                        } else {
                            $tenantPayments->due_date = $startDateLease;
                        }

                        $tenantPayments->save(false);
                        $startDateLease = Date('Y-m-d', (strtotime($startDateLease) + (60 * 60 * 24 * 30)));
                        $d = date_parse_from_format("Y-m-d", $startDateLease);
                        $days1 = cal_days_in_month(CAL_GREGORIAN, $d['month'], $d['year']);
                        $startDateLease = Date('Y-m-d', strtotime($d['year'] . "-" . $d['month'] . "-" . $days1));
                        $is++;
                    }
                } else if ($agreement_type == 3) {
                    $months = ceil((strtotime($model->lease_end_date) - strtotime($model->lease_start_date)) / (60 * 60 * 24 * 30));
                    $startDateObject = date_create($model->lease_start_date);
                    $endDateObject = date_create($model->lease_end_date);
                    $startDateLease = $startDateObject->format('Y') . "-" . (int) $startDateObject->format('m') . "-" . (int) $startDateObject->format('d');
                    $rentArray = Array();
                    $maintenanceArray = Array();

                    for ($i = 0; $i < $months; $i++) {
                        $rentArray[] = $propertyAgreements->rent;
                        $rentArray1[] = $propertyAgreements->rent;
                        $maintenanceArray[] = $propertyAgreements->maintainance;
                        $tenant_rent_Array[] = $model->rent;
                        $tenant_maintenance_Array[] = $model->maintainance;
                    }

                    $days = cal_days_in_month(CAL_GREGORIAN, $startDateObject->format('m'), $startDateObject->format('Y'));
                    $rentArray[0] = ceil(((int) $propertyAgreements->rent / (int) $days) * ((int) $days - (int) $startDateObject->format('d')));
                    $rentArray1[0] = ceil(((int) $propertyAgreements->rent / (int) $days) * ((int) $days - (int) $startDateObject->format('d')));
                    $maintenanceArray[0] = ceil(((int) $propertyAgreements->maintainance / (int) $days) * ((int) $days - (int) $startDateObject->format('d')));
                    $tenant_rent_Array[0] = ceil(((int) $model->rent / (int) $days) * ((int) $days - (int) $startDateObject->format('d')));
                    $tenant_maintenance_Array[0] = ceil(((int) $model->maintainance / (int) $days) * ((int) $days - (int) $startDateObject->format('d')));


                    $lastdays = $endDateObject->format('d');
                    $rentArray[count($rentArray) - 1] = ceil(((int) $propertyAgreements->rent / (int) $days) * ($lastdays));
                    $rentArray1[count($rentArray1) - 1] = ceil(((int) $propertyAgreements->rent / (int) $days) * ($lastdays));
                    $maintenanceArray[count($maintenanceArray) - 1] = ceil(((int) $propertyAgreements->maintainance / (int) $days) * ($lastdays));
                    $tenant_rent_Array[count($tenant_rent_Array) - 1] = ceil(((int) $model->rent / (int) $days) * ((int) $days - (int) $startDateObject->format('d')));
                    $tenant_maintenance_Array[count($tenant_maintenance_Array) - 1] = ceil(((int) $model->maintainance / (int) $days) * ((int) $days - (int) $startDateObject->format('d')));

                    $walletAmount = \app\models\Wallets::findOne(['user_id' => $model->tenant_id]);
                    if (count($walletAmount) == 1 and $walletAmount->amount != 0) {
                        $wallet_amount = $walletAmount->amount;
                        $rent = $model->rent;
                        if ((int) $wallet_amount <= (int) $rentArray[0]) {
                            $rentArray[0] = (int) $rentArray[0] - (int) $wallet_amount;
                            $wallet_amount = 0;
                        } else {
                            $wallet_amount = (int) $wallet_amount - (int) $rentArray[0];
                            $rentArray[0] = 0;
                            $total_rent = 0;
                            for ($i = 1; $i < count($rentArray); $i++) {
                                $total_rent = (int) $total_rent + $rentArray[$i];
                            }

                            if ($wallet_amount <= (int) $total_rent) {
                                for ($i = 1; $i <= count($rentArray) - 1; $i++) {
                                    if ((int) $rentArray[$i] < (int) $wallet_amount) {
                                        $wallet_amount = (int) $wallet_amount - (int) $rentArray[$i];
                                        $rentArray[$i] = 0;
                                    } else {
                                        $rentArray[$i] = (int) $rentArray[$i] - (int) $wallet_amount;
                                        $wallet_amount = 0;
                                    }
                                }
                            } else {
                                for ($i = 1; $i < count($rentArray); $i++) {
                                    $rentArray[$i] = 0;
                                }
                                $wallet_amount = (int) $wallet_amount - $total_rent;
                            }
                        }
                    }
                    $is = 0;
                    foreach ($rentArray as $key => $value) {
                        $tenantPayments = new \app\models\TenantPayments;
                        $tenantPayments->tenant_id = $modelUserProfile->applicant_id;
                        $tenantPayments->property_id = $model->property_id;
                        $tenantPayments->parent_id = $model->parent_id;
                        $tenantPayments->owner_amount = $tenant_rent_Array[$key];
                        $tenantPayments->original_amount = $value;
                        $tenantPayments->maintenance = $tenant_maintenance_Array[$key];
                        $tenantPayments->payment_des = 'Rent Against Property For ' . Date('m-Y', strtotime($startDateLease));
                        $tenantPayments->payment_type = '2';
                        $tenantPayments->created_by = Yii::$app->user->id;
                        $tenantPayments->total_amount = (int) $value + (int) $maintenanceArray[$key];
                        if ($is == 0) {
                            $tenantPayments->due_date = Date('Y-m-d', (strtotime($model->lease_start_date) + (60 * 60 * 24 * 5)));
                        } else {
                            $tenantPayments->due_date = $startDateLease;
                        }

                        $tenantPayments->save(false);
                        $startDateLease = Date('Y-m-d', (strtotime($startDateLease) + (60 * 60 * 24 * 30)));
                        $d = date_parse_from_format("Y-m-d", $startDateLease);
                        $days1 = cal_days_in_month(CAL_GREGORIAN, $d['month'], $d['year']);
                        $startDateLease = Date('Y-m-d', strtotime($d['year'] . "-" . $d['month'] . "-" . $days1));
                        $is++;
                    }
                } else {
                    $months = ceil((strtotime($model->lease_end_date) - strtotime($model->lease_start_date)) / (60 * 60 * 24 * 30));
                    $startDateObject = date_create($model->lease_start_date);
                    $endDateObject = date_create($model->lease_end_date);
                    $startDateLease = $startDateObject->format('Y') . "-" . (int) $startDateObject->format('m') . "-" . (int) $startDateObject->format('d');
                    $rentArray = Array();
                    $maintenanceArray = Array();

                    for ($i = 0; $i < $months; $i++) {
                        $rentArray[] = $model->rent;
                        $rentArray1[] = $model->rent;
                        $maintenanceArray[] = $model->maintainance;
                    }

                    $days = cal_days_in_month(CAL_GREGORIAN, $startDateObject->format('m'), $startDateObject->format('Y'));
                    $rentArray[0] = ceil(((int) $model->rent / (int) $days) * ((int) $days - (int) $startDateObject->format('d')));
                    $rentArray1[0] = ceil(((int) $model->rent / (int) $days) * ((int) $days - (int) $startDateObject->format('d')));
                    $maintenanceArray[0] = ceil(((int) $model->maintainance / (int) $days) * ((int) $days - (int) $startDateObject->format('d')));

                    $lastdays = $endDateObject->format('d');
                    $rentArray[count($rentArray) - 1] = ceil(((int) $model->rent / (int) $days) * ($lastdays));
                    $rentArray1[count($rentArray1) - 1] = ceil(((int) $model->rent / (int) $days) * ($lastdays));
                    $maintenanceArray[count($maintenanceArray) - 1] = ceil(((int) $model->maintainance / (int) $days) * ($lastdays));

                    $walletAmount = \app\models\Wallets::findOne(['user_id' => $model->tenant_id]);
                    if (count($walletAmount) == 1 and $walletAmount->amount != 0) {
                        $wallet_amount = $walletAmount->amount;
                        $rent = $model->rent;
                        if ((int) $wallet_amount <= (int) $rentArray[0]) {
                            $rentArray[0] = (int) $rentArray[0] - (int) $wallet_amount;
                            $wallet_amount = 0;
                        } else {
                            $wallet_amount = (int) $wallet_amount - (int) $rentArray[0];
                            $rentArray[0] = 0;
                            $total_rent = 0;
                            for ($i = 1; $i < count($rentArray); $i++) {
                                $total_rent = (int) $total_rent + $rentArray[$i];
                            }

                            if ($wallet_amount <= (int) $total_rent) {
                                for ($i = 1; $i <= count($rentArray) - 1; $i++) {
                                    if ((int) $rentArray[$i] < (int) $wallet_amount) {
                                        $wallet_amount = (int) $wallet_amount - (int) $rentArray[$i];
                                        $rentArray[$i] = 0;
                                    } else {
                                        $rentArray[$i] = (int) $rentArray[$i] - (int) $wallet_amount;
                                        $wallet_amount = 0;
                                    }
                                }
                            } else {
                                for ($i = 1; $i < count($rentArray); $i++) {
                                    $rentArray[$i] = 0;
                                }
                                $wallet_amount = (int) $wallet_amount - $total_rent;
                            }
                        }
                    }
                    $is = 0;
                    foreach ($rentArray as $key => $value) {
                        $tenantPayments = new \app\models\TenantPayments;
                        $tenantPayments->tenant_id = $modelUserProfile->applicant_id;
                        $tenantPayments->property_id = $model->property_id;
                        $tenantPayments->parent_id = $model->parent_id;
                        $tenantPayments->owner_amount = $rentArray1[$key];
                        $tenantPayments->original_amount = $value;
                        $tenantPayments->maintenance = $maintenanceArray[$key];
                        $tenantPayments->payment_des = 'Rent Against Property For ' . Date('m-Y', strtotime($startDateLease));
                        $tenantPayments->payment_type = '2';
                        $tenantPayments->created_by = Yii::$app->user->id;
                        $tenantPayments->total_amount = (int) $value + (int) $maintenanceArray[$key];
                        if ($is == 0) {
                            $tenantPayments->due_date = Date('Y-m-d', (strtotime($model->lease_start_date) + (60 * 60 * 24 * 5)));
                        } else {
                            $tenantPayments->due_date = $startDateLease;
                        }

                        $tenantPayments->save(false);
                        $startDateLease = Date('Y-m-d', (strtotime($startDateLease) + (60 * 60 * 24 * 30)));
                        $d = date_parse_from_format("Y-m-d", $startDateLease);
                        $days1 = cal_days_in_month(CAL_GREGORIAN, $d['month'], $d['year']);
                        $startDateLease = Date('Y-m-d', strtotime($d['year'] . "-" . $d['month'] . "-" . $days1));
                        $is++;
                    }
                }



                /*                 * ********************************************************************************************* */

                $modelTenantProfile = new \app\models\TenantProfile;
                $modelTenantProfile->tenant_id = $modelUserProfile->applicant_id;
                $modelTenantProfile->emer_contact = $modelUserProfile->emergency_contact_email;
                $modelTenantProfile->emer_contact_indentity = $modelUserProfile->emer_contact_indentity;
                $modelTenantProfile->emer_contact_address = $modelUserProfile->emer_contact_address;
                $modelTenantProfile->status = $modelUserProfile->status;
                $modelTenantProfile->sales_id = $modelUserProfile->sales_id;
                $modelTenantProfile->operation_id = $modelUserProfile->operation_id;
                $modelTenantProfile->address_line_1 = $modelUserProfile->address_line_1;
                $modelTenantProfile->address_line_2 = $modelUserProfile->address_line_2;
                $modelTenantProfile->state = $modelUserProfile->state;
                $modelTenantProfile->city = $modelUserProfile->city;
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
                $modelTenantProfile->save(false);

                $modelUserProfile->delete();
                if (count($modelFav) != 0) {
                    $modelFav->delete();
                }


                // $leads_Applicant->ref_status=4;
                // $leads_Applicant->save(false);
                $leads_Applicant->delete();


                Yii::$app->getSession()->setFlash(
                        'success', 'Agreement created successfully'
                );
                return $this->redirect(['applicants']);
            } catch (Exception $e) {
                Yii::$app->getSession()->setFlash(
                        'success', "{$e->getMessage()}"
                );
                return $this->renderAjax('_formlease', [
                            'model' => $model
                ]);
            }
        } else {
            return $this->renderAjax('_formlease', [
                        'model' => $model
            ]);
        }
    }

    public function actionCreateadviser() {


        $model = new LeadsAdvisor;
        $comments = new \app\models\Comments;

        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = 'json';
            return \yii\bootstrap\ActiveForm::validate($model);
        } else if ($model->load(Yii::$app->request->post())) {

            try {

                $model->created_by = Yii::$app->user->id;
                $model->created_date = date('Y-m-d H:i:s');

                if ($model->save(false)) {

                    $modelUser = new \app\models\Users;
                    $modelUser->login_id = $model->email_id;
                    $modelUser->full_name = $model->full_name;
                    $modelUser->username = $model->email_id;
                    $modelUser->password = md5(Yii::$app->userdata->passwordGenerate());
                    $modelUser->user_type = 5;
                    $modelUser->created_date = date('Y-m-d H:i:s');
                    $modelUser->save(false);

                    $modelAdvisorProfile = new \app\models\AdvisorProfile;
                    $modelAdvisorProfile->advisor_id = $modelUser->id;
                    $modelAdvisorProfile->address_line_1 = $model->address;
                    $modelAdvisorProfile->address_line_2 = $model->address_line_2;
                    $modelAdvisorProfile->state = $model->state;
                    $modelAdvisorProfile->phone = $model->contact_number;
                    $modelAdvisorProfile->city = $model->city;
                    $modelAdvisorProfile->pincode = $model->pincode;
                    $modelAdvisorProfile->region = $model->region;
                    $modelAdvisorProfile->email_id = $model->email_id;
                    $modelAdvisorProfile->save(false);

                    $comments->description = $model->communication;
                    $comments->user_id = $modelUser->id;
                    $comments->created_by = Yii::$app->user->id;
                    $comments->save(false);

                    Yii::$app->getSession()->setFlash(
                            'success', 'Adviser created successfully'
                    );
                    return $this->redirect('advisersdetails?id=' . base64_encode($model->id));
                } else {
                    Yii::$app->getSession()->setFlash(
                            'success', 'Adviser is not created'
                    );
                    return $this->redirect(['advisers']);
                }
            } catch (Exception $e) {
                Yii::$app->getSession()->setFlash(
                        'success', "{$e->getMessage()}"
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

    public function actionCreateowner() {


        $model = new LeadsOwner;
        $comments = new \app\models\Comments;
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = 'json';
            return \yii\bootstrap\ActiveForm::validate($model);
        } else if ($model->load(Yii::$app->request->post())) {

            try {

                $model->created_by = Yii::$app->user->id;
                $model->created_date = date('Y-m-d H:i:s');

                if ($model->save(false)) {


                    $modelUser = new \app\models\Users;
                    $modelUser->login_id = $model->email_id;
                    $modelUser->full_name = $model->full_name;
                    $modelUser->username = $model->email_id;
                    $modelUser->password = md5(Yii::$app->userdata->passwordGenerate());
                    $modelUser->user_type = 4;
                    $modelUser->created_date = date('Y-m-d H:i:s');
                    $modelUser->save(false);

                    $modelOwnerProfile = new \app\models\OwnerProfile;
                    $modelOwnerProfile->owner_id = $modelUser->id;
                    $modelOwnerProfile->address_line_1 = $model->address;
                    $modelOwnerProfile->state = $model->state;
                    $modelOwnerProfile->phone = $model->contact_number;
                    $modelOwnerProfile->city = $model->city;
                    $modelOwnerProfile->pincode = $model->pincode;
                    $modelOwnerProfile->region = $model->region;
                    $modelOwnerProfile->save(false);

                    $comments->description = $model->communication;
                    $comments->user_id = $modelUser->id;
                    $comments->created_by = Yii::$app->user->id;
                    $comments->save(false);


                    Yii::$app->getSession()->setFlash(
                            'success', 'Owner created successfully'
                    );
                    return $this->redirect('ownerssdetails?id=' . base64_encode($model->id));
                } else {
                    Yii::$app->getSession()->setFlash(
                            'success', 'Owner is not created'
                    );
                    return $this->redirect(['owners']);
                }
            } catch (Exception $e) {
                Yii::$app->getSession()->setFlash(
                        'success', "{$e->getMessage()}"
                );
                return $this->renderAjax('_formowner', [
                            'model' => $model
                ]);
            }
        } else {
            return $this->renderAjax('_formowner', [
                        'model' => $model
            ]);
        }
    }

    public function actionApplicants() {

        $searchModelOwnerLeads = new LeadsTenant();

        $dataProviderOwner = $searchModelOwnerLeads->search(Yii::$app->request->queryParams);

        return $this->render('applicants', [
                    'searchModelOwnerLeads' => $searchModelOwnerLeads,
                    'dataProviderOwner' => $dataProviderOwner,
        ]);
    }

    public function actionOwners() {

        $searchModelOwnerLeads = new LeadsOwner();

        $dataProviderOwner = $searchModelOwnerLeads->search(Yii::$app->request->queryParams);



        return $this->render('owners', [
                    'searchModelOwnerLeads' => $searchModelOwnerLeads,
                    'dataProviderOwner' => $dataProviderOwner,
        ]);
    }

    public function actionAdvisers() {

        $searchModelOwnerLeads = new LeadsAdvisor();

        $dataProviderOwner = $searchModelOwnerLeads->search(Yii::$app->request->queryParams);



        return $this->render('advisers', [
                    'searchModelOwnerLeads' => $searchModelOwnerLeads,
                    'dataProviderOwner' => $dataProviderOwner,
        ]);
    }

    public function actionAdvisersdetails($id) {
        $id = base64_decode($id);
        // die;
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
                    $profileimage = $model->profile_image;
                    //$agreementdoc = $modelAgreements->agreement_doc;
                    if ($model->sales_id != 0) {
                        $salesperson = \app\models\OperationsProfile::find()->where(['operations_id' => $model->operation_id])->one();
                        $salesname = \app\models\User::find()->where(['id' => $model->operation_id])->one();
                    } else {
                        $salesperson = Array();
                        $salesname = Array();
                    }




                    if ($models->load(Yii::$app->request->post())) {

                        $models->follow_up_date_time = date('Y-m-d H:i:s', strtotime($_POST['LeadsAdvisor']['follow_up_date_time']));
                        $models->schedule_date_time = date('Y-m-d H:i:s', strtotime($_POST['LeadsAdvisor']['schedule_date_time']));
                        $models->ref_status = $_POST['LeadsAdvisor']['ref_status'];
                        $models->save(false);

                        $model->sales_id = $model->sales_id;
                        $model->save(false);

                        if ($_FILES['AdvisorAgreements']['size']['agreement_doc'] != 0) {
                            $name_agreement_doc = date('ymdHis') . $_FILES['AdvisorAgreements']['name']['agreement_doc'];
                            move_uploaded_file($_FILES['AdvisorAgreements']['tmp_name']['agreement_doc'], 'uploads/profiles/' . $name_agreement_doc);
                            $agreemnets_doc = Url::home(true) . 'uploads/agreements/' . $name_agreement_doc;
                        } else {
                            $agreemnets_doc = "";
                        }


                        if (count($modelAgreements) != 0) {
                            $modelAgreements->start_date = date('Y-m-d H:i:s', strtotime($_POST['AdvisorAgreements']['start_date']));
                            $modelAgreements->end_date = date('Y-m-d H:i:s', strtotime($_POST['AdvisorAgreements']['end_date']));
                            $modelAgreements->updated_by = Yii::$app->user->id;
                            if ($agreemnets_doc != '') {
                                $modelAgreements->agreement_doc = $agreemnets_doc;
                            }
                            $modelAgreements->save(false);
                        } else {
                            $modelAgreements = new \app\models\AdvisorAgreements;
                            $modelAgreements->start_date = date('Y-m-d H:i:s', strtotime($_POST['AdvisorAgreements']['start_date']));
                            $modelAgreements->end_date = date('Y-m-d H:i:s', strtotime($_POST['AdvisorAgreements']['end_date']));
                            $modelAgreements->created_by = Yii::$app->user->id;
                            $modelAgreements->advisor_id = $_POST['AdvisorAgreements']['advisor_id'];
                            $modelAgreements->agreement_doc = $agreemnets_doc;
                            $modelAgreements->save(false);
                        }
                    }


                    if ($model->load(Yii::$app->request->post())) {

                        if ($model->validate()) {
                            $profile_image = UploadedFile::getInstance($model, 'profile_image');
                            // $address_proof = UploadedFile::getInstance($model, 'address_proof');
                            // $identity_proof = UploadedFile::getInstance($model, 'identity_proof');

                            if (!empty($profile_image)) {
                                $name_profile_image = date('ymdHis') . $profile_image->name;
                                $profile_image->saveAs('uploads/profiles/' . $name_profile_image);
                                $model->profile_image = Url::home(true) . 'uploads/profiles/' . $name_profile_image;
                            } else {
                                $model->profile_image = $profileimage;
                            }

                            if (isset($_FILES['address_proof'])) {
                                $address_proof = $_FILES['address_proof'];
                            } else {
                                $address_proof = Array();
                            }

                            $model->save(false);

                            $modelUsers = Users::findOne($ids);
                            $modelUsers->login_id = $model->email_id;
                            $modelUsers->save(false);

                            $modelLeadsAdvisor = LeadsAdvisor::findOne(['email_id' => $model->email_id]);

                            $modelLeadsAdvisor->email_id = $model->email_id;
                            $modelLeadsAdvisor->contact_number = $model->phone;
                            $modelLeadsAdvisor->address = $model->address_line_1;
                            $modelLeadsAdvisor->state = $model->state;
                            $modelLeadsAdvisor->city = $model->city;
                            $modelLeadsAdvisor->region = $model->region;
                            $modelLeadsAdvisor->pincode = $model->pincode;
                            $modelLeadsAdvisor->updated_date = date('Y-m-d H:i:s');
                            $modelLeadsAdvisor->save(false);



                            // if(!empty($address_proof)){
                            // 	$address =   \app\models\UserIdProofs::find()->where(['user_id'=>$ids,'proof_type'=>'address'])->one() ;
                            // 	 if( $address != null){
                            // 		 $address->delete();
                            // 	 }
                            // 	$address_proofobj = new \app\models\UserIdProofs ;
                            // 	$name_address_proof = date('ymdHis').$address_proof->name ;
                            // 	$address_proof->saveAs('uploads/proofs/' . $name_address_proof );
                            // 	$address_proofobj->proof_type = 'address';
                            // 	$address_proofobj->proof_document_url = Url::home(true).'uploads/proofs/' .$name_address_proof ;
                            // 	$address_proofobj->user_id = $ids;
                            // 	$address_proofobj->created_by = Yii::$app->user->id;
                            // 	$address_proofobj->created_date = date('Y-m-d H:i:s');
                            // 	$address_proofobj->save(false);
                            //  }
                            // if(!empty($identity_proof)){
                            // 	$identity =   \app\models\UserIdProofs::find()->where(['user_id'=>$ids,'proof_type'=>'identity'])->one() ;
                            // 	 if( $identity != null){
                            // 		 $identity->delete();
                            // 	 }
                            // 	$identity_proofobj = new \app\models\UserIdProofs ;
                            // 	$name_identity_proof = date('ymdHis').$identity_proof->name ;
                            // 	$identity_proof->saveAs('uploads/proofs/' . $name_identity_proof );
                            // 	$identity_proofobj->proof_type = 'identity';
                            // 	$identity_proofobj->proof_document_url =  Url::home(true).'uploads/proofs/' .$name_identity_proof ;
                            // 	$identity_proofobj->user_id = $ids;
                            // 	$identity_proofobj->created_by = Yii::$app->user->id;
                            // 	$identity_proofobj->created_date = date('Y-m-d H:i:s');
                            // 	$identity_proofobj->save(false);	
                            // }

                            if (!empty($address_proof)) {
                                foreach ($address_proof['tmp_name'] as $key => $value) {

                                    foreach ($value as $key1 => $value1) {
                                        $address_proofobj = new \app\models\UserIdProofs;
                                        $name_address_proof = (string) date('ymdHis') . rand() . $address_proof['name'][$key][$key1];
                                        $tmp_file = (string) $value1;
                                        move_uploaded_file($value1, 'uploads/proofs/' . $name_address_proof);
                                        $address_proofobj->proof_type = $key;
                                        $address_proofobj->proof_document_url = Url::home(true) . 'uploads/proofs/' . $name_address_proof;
                                        $address_proofobj->user_id = $ids;
                                        $address_proofobj->created_by = $ids;
                                        $address_proofobj->created_date = date('Y-m-d H:i:s');
                                        $address_proofobj->save(false);
                                    }
                                }
                            }
                            // echo "hello1";
                            // print_r($modelAgreements);
                            // die;
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
                            Yii::$app->getSession()->setFlash(
                                    'success', 'Profile is saved successfully.'
                            );
                        } else {
                            // echo "hello2";
                            // print_r($modelAgreements);
                            // die;
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
                        // echo "hello3";
                        // print_r(count($modelAgreements));
                        // die;
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

    public function actionHotleads() {


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
        $data = Array();

        $search = "";
        $requestData = Yii::$app->request->queryParams;
        if (isset($requestData['search'])) {
            $search = str_replace(" ", "%%", trim($requestData['search']));
        }

        $command = $connection->createCommand('
			Select l.`id`, l.`full_name`, l.`email_id`, l.`contact_number`, l.`address`, l.`follow_up_date_time`,c.city_name ,s.name as state_name,r.name as ref_name,ut.user_type_name from leads_tenant  as l left join cities as c on l.city=c.id left join states as s on l.state=s.id left join ref_status as r on l.ref_status=r.id left join user_types as ut on l.user_type=ut.id where ' . $condition1 . ' and (DATE(l.`created_date`)=CURDATE() OR DATE(l.`created_date`)=CURDATE() - INTERVAL 1 DAY) and (`full_name` like "%' . $search . '%" or `email_id` like "%' . $search . '%" or `address` like "%' . $search . '%" or `contact_number` like "%' . $search . '%" or c.city_name like "%' . $search . '%" or s.name like "%' . $search . '%" or r.name like "%' . $search . '%")
			 
			 UNION 
			 
			 Select l.`id`, l.`full_name`, l.`email_id`, l.`contact_number`, l.`address`, l.`follow_up_date_time`,c.city_name ,s.name as state_name,r.name as ref_name,ut.user_type_name from leads_owner  as l left join cities as c on l.city=c.id left join states as s on l.state=s.id left join ref_status as r on l.ref_status=r.id left join user_types as ut on l.user_type=ut.id where ' . $condition1 . ' and (DATE(l.`created_date`)=CURDATE() OR DATE(l.`created_date`)=CURDATE() - INTERVAL 1 DAY) and (`full_name` like "%' . $search . '%" or `email_id` like "%' . $search . '%" or `address` like "%' . $search . '%" or `contact_number` like "%' . $search . '%" or c.city_name like "%' . $search . '%" or s.name like "%' . $search . '%" or r.name like "%' . $search . '%") 
			 
			 UNION 
			 
			 Select l.`id`, l.`full_name`, l.`email_id`, l.`contact_number`, l.`address`, l.`follow_up_date_time`,c.city_name ,s.name as state_name,r.name as ref_name,ut.user_type_name from leads_advisor  as l left join cities as c on l.city=c.id left join states as s on l.state=s.id left join ref_status as r on l.ref_status=r.id left join user_types as ut on l.user_type=ut.id where ' . $condition1 . ' and (DATE(l.`created_date`)=CURDATE() OR DATE(l.`created_date`)=CURDATE() - INTERVAL 1 DAY) and (`full_name` like "%' . $search . '%" or `email_id` like "%' . $search . '%" or `address` like "%' . $search . '%" or `contact_number` like "%' . $search . '%" or c.city_name like "%' . $search . '%" or s.name like "%' . $search . '%" or r.name like "%' . $search . '%" )
		   ');


        $applicant_leads = $command->queryAll();
        foreach ($applicant_leads as $key => $value) {
            $data[] = Array('id' => $value['id'], 'type' => $value['user_type_name'], 'name' => $value['full_name'], 'email' => $value['email_id'], 'contact' => $value['contact_number'], 'address' => $value['address'], 'city' => $value['city_name'], 'state' => $value['state_name'], 'ref_status' => $value['ref_name'], 'follow_up_date_time' => $value['follow_up_date_time']);
        }

        return $this->render('hotleads', ['data' => $data]);
    }

    public function deleteProperty() {
        $id = $_POST['id'];
        $model = \app\models\properties::find()->where(['id' => $id]);
        $Listing = \app\models\PropertyListing::find()->where(['property_id' => $id]);
        $agreements = \app\models\PropertyAgreements::find()->where(['property_id' => $id]);
        $child = \app\models\ChildProperties::find()->where(['main' => $id]);
        $attribute = \app\models\PropertyAttributeMap::find()->where(['property_id' => $id]);
        $images = \app\models\PropertyImages::find()->where(['property_id' => $id]);
    }

    public function actionActivateowner() {
        $command = Yii::$app->db->createCommand('update users SET status="1" where id="' . $_POST['id'] . '"');
        if ($command->execute()) {
            return true;
        }
    }

    public function actionDeactivateowner() {
        if (!Yii::$app->userdata->getOwnerAllPropertyStatus($_POST['id'])) {
            $command = Yii::$app->db->createCommand('update users SET status="0" where id="' . $_POST['id'] . '"');
            if ($command->execute()) {
                Yii::$app->userdata->deactivateallproperty($_POST['id']);
                return true;
            }
        } else {
            return false;
        }
    }

    public function actionActivateadvisor() {
        $id = $_POST['id'];
        $action = $_POST['action'];
        $user = \app\models\Users::findOne(['id' => $id]);
        $user->status = $action;
        $user->save(false);
        if ($action == 0) {
            $users = \app\models\Users::findAll(['refered_by' => $id]);
            if (count($users) != 0) {
                foreach ($users as $key => $value) {
                    $value->refered_by = 0;
                    $value->save(false);
                }
            }
        }
    }

}
