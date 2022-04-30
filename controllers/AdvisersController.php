<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use app\models\Properties;
use app\models\Agreements;
use app\models\LeadsReferences;
use app\models\PropertiesSearch;
use app\models\PropertiesAddress;
use app\models\PropertyImages;
use app\models\PropertiesAttributes;
use app\models\PropertyAttributeMap;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\Url;

/**
 * PropertiesController implements the CRUD actions for Properties model.
 */
class AdvisersController extends Controller {

    public $layout = 'advisor_dashboard';

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

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['owner', 'applicant', 'myreferred', 'ownerreferred', 'tenantreferred', 'myprofile', 'bankdetails', 'earnings', 'feesdetails'],
                'rules' => [
                    [
                        'actions' => ['owner', 'applicant', 'myreferred', 'ownerreferred', 'tenantreferred', 'myprofile', 'bankdetails', 'earnings', 'feesdetails'],
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

    /**
     * Lists all Properties models.
     * @return mixed
     */
    public function actionIndex() {

        return $this->render('dashboard');
    }

    /**
     * Displays a single Properties model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Displays a single Properties model.
     * @param integer $id
     * @return mixed
     */
    public function actionMyreferred() {
        $id = Yii::$app->user->id;
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand('
			Select l.`id`, l.`full_name`, l.`email_id`, l.`contact_number`, l.`address`,l.`address_line_2`, l.`follow_up_date_time`,c.city_name ,s.name as state_name,r.name as ref_name,ut.user_type_name from leads_tenant  as l left join cities as c on l.lead_city=c.id left join states as s on l.lead_state=s.id left join ref_status as r on l.ref_status=r.id left join user_types as ut on l.user_type=ut.id where `reffered_by`=' . $id . '

                        UNION

                        Select l.`id`, l.`full_name`, l.`email_id`, l.`contact_number`, l.`address`,l.`address_line_2`, l.`follow_up_date_time`,c.city_name ,s.name as state_name,r.name as ref_name,ut.user_type_name from leads_owner  as l left join cities as c on l.lead_city=c.id left join states as s on l.lead_state=s.id left join ref_status as r on l.ref_status=r.id left join user_types as ut on l.user_type=ut.id  where `reffered_by`=' . $id . '
		   ');


        $applicant_leads = $command->queryAll();
        //echo "<pre>";print_r($applicant_leads);echo "</pre>";die;
        return $this->render('myreferred', [
                    'model' => $applicant_leads,
        ]);
    }

    public function actionOwnerreferred() {
        $id = Yii::$app->user->id;

        $users = \app\models\Users::find()->select('id,login_id')->where(['refered_by' => $id, 'user_type' => 4])->all();
        $filter_val = 3;
        $search1 = '';
        if (isset($_GET['search'])) {
            if (trim($_GET['search']) != '') {
                $search = str_replace(" ", "%%", $_GET['search']);
                $search1 = ' and (p.address_line_1 like "%' . $search . '%" or p.address_line_2 like "%' . $search . '%" or p.property_name like "%' . $search . '%" or c.city_name like "%' . $search . '%" or s.name like "%' . $search . '%" or up.phone like "%' . $search . '%" or u.login_id like "%' . $search . '%" or u.full_name like "%' . $search . '%" or p.id="%' . $search . '%")';
            }
        }
        if (isset($_GET['sort_val'])) {

            if ($_GET['sort_val'] == '') {
                $sort = 'p.id';
            } else {
                $sort = $_GET['sort_val'];
            }
        } else {
            $sort = 'p.id';
        }

        if (isset($_GET['filter'])) {
            $filter_val = $_GET['filter'];
        }
        $myUsers = [];
        $myUsersProperties = [];
        $property_status = Array();
        $include_properties = Array();
        foreach ($users as $key => $user) {
            $myUsers[] = $user->id;

            $properties = \app\models\Properties::find()->select('id,property_type')->where(['owner_id' => $user->id])->all();

            foreach ($properties as $property) {
                $myUsersProperties[] = $property->id;
                if ($property->property_type == 1 || $property->property_type == 2) {
                    $rooms = \app\models\ChildProperties::find('id')->where(['type' => 1, 'main' => 2])->all();
                    $bedList = [];
                    $roomList = [];
                    $newBedList = [];
                    $check = 0;
                    foreach ($rooms as $room) {
                        $tenant_agreements_rooms = \app\models\TenantAgreements::find()->where(['parent_id' => $property->id, 'property_id' => $room->id])->count();
                        if ($tenant_agreements_rooms == 0) {
                            $beds = \app\models\ChildProperties::find('id')->where(['type' => 2, 'parent' => $room->id, 'main' => 2])->all();
                            foreach ($beds as $bed) {
                                $bedList[] = $bed->id;
                            }
                        } else {
                            $check = 1;
                            $roomList[] = $room->id;
                        }
                    }

                    if (count($roomList) == 0) {
                        $tenant_agreements_beds = \app\models\TenantAgreements::find()->select('property_id')->where(['parent_id' => $property->id])->all();
                    } else {
                        $tenant_agreements_beds = \app\models\TenantAgreements::find()->select('property_id')->where(['parent_id' => $property->id])->andWhere(' `property_id` NOT IN (' . implode(",", $roomList) . ')')->all();
                    }
                    foreach ($tenant_agreements_beds as $tbeds) {
                        $newBedList[] = $tbeds->property_id;
                    }

                    $newBedList = count(array_unique($newBedList));
                    if ($newBedList == 0) {
                        if ($check == 1) {
                            if ($filter_val == 1) {
                                $include_properties[] = $property->id;
                            }
                            $property_status[$property->id] = 'Partially Occupied';
                        } else {
                            if ($filter_val == 2) {
                                $include_properties[] = $property->id;
                            }
                            $property_status[$property->id] = 'Vacant';
                        }
                    }
                    if ($newBedList == count(array_unique($bedList))) {
                        if ($check == 1) {
                            if ($filter_val == 0) {
                                $include_properties[] = $property->id;
                            }
                            $property_status[$property->id] = 'Completely Occupied';
                        } else {
                            if ($filter_val == 1) {
                                $include_properties[] = $property->id;
                            }
                            $property_status[$property->id] = 'Partially Occupied';
                        }
                    } else {
                        if ($filter_val == 1) {
                            $include_properties[] = $property->id;
                        }
                        $property_status[$property->id] = 'Partially Occupied';
                    }
                } else {
                    $tenant_agreements = \app\models\TenantAgreements::find()->where(['parent_id' => $property->id])->count();
                    if ($tenant_agreements == 0) {
                        if ($filter_val == 2) {
                            $include_properties[] = $property->id;
                        }
                        $property_status[$property->id] = 'Vacant';
                    } else {
                        if ($filter_val == 0) {
                            $include_properties[] = $property->id;
                        }
                        $property_status[$property->id] = 'Fully Occupied';
                    }
                }
            }
        }


        if (isset($_GET['status_val'])) {
            if ($_GET['status_val'] != '') {
                switch ($_GET['status_val']) {
                    CASE "1":
                        $status = "Fully Occupied";
                        break;
                    CASE "0":
                        $status = "Partially Occupied";
                        break;
                    CASE "-1":
                        $status = "Vacant";
                        break;
                }

                if (isset($status)) {
                    $myUsersProperties1 = Array();
                    foreach ($myUsersProperties as $key => $value) {
                        if ($property_status[$value] == $status) {
                            $myUsersProperties1[] = $value;
                        }
                    }
                    $myUsersProperties = $myUsersProperties1;
                }
            }
        }

        if (count($myUsersProperties) != 0) {
            $connection = Yii::$app->getDb();
            if (isset($_GET['filter'])) {
                if ($_GET['filter'] == 3) {
                    $command = $connection->createCommand('select p.id,p.address_line_1,p.address_line_2,p.property_name,c.city_name,u.full_name,u.login_id,up.phone,s.name as state_name,a.contract_start_date,a.contract_end_date from property_agreements as a left join properties as p on a.property_id=p.id left join users as u on a.owner_id=u.id left join owner_profile as up on u.id=up.owner_id left join cities as c on up.city_name=c.id left join states as s on up.state=s.id where a.`property_id` IN (' . implode(",", $myUsersProperties) . ')' . $search1 . ' ORDER BY ' . $sort);
                    $agreements = $command->queryAll();
                } else {
                    if (count($include_properties) != 0) {
                        $command = $connection->createCommand('select p.id,p.address_line_1,p.address_line_2,p.property_name,c.city_name,u.full_name,u.login_id,up.phone,s.name as state_name,a.contract_start_date,a.contract_end_date from property_agreements as a left join properties as p on a.property_id=p.id left join users as u on a.owner_id=u.id left join owner_profile as up on u.id=up.owner_id left join cities as c on up.city_name=c.id left join states as s on up.state=s.id where a.`property_id` IN (' . implode(",", $include_properties) . ')' . $search1 . ' ORDER BY ' . $sort);
                        $agreements = $command->queryAll();
                    } else {
                        $agreements = Array();
                    }
                }
            } else {
                $command = $connection->createCommand('select p.id,p.address_line_1,p.address_line_2,p.property_name,c.city_name,u.full_name,u.login_id,up.phone,s.name as state_name,a.contract_start_date,a.contract_end_date from property_agreements as a left join properties as p on a.property_id=p.id left join users as u on a.owner_id=u.id left join owner_profile as up on u.id=up.owner_id left join cities as c on up.city_name=c.id left join states as s on up.state=s.id where a.`property_id` IN (' . implode(",", $myUsersProperties) . ')' . $search1 . ' ORDER BY ' . $sort);
                $agreements = $command->queryAll();
            }
        } else {
            $agreements = Array();
        }

        return $this->render('ownerreferred', [
                    'model' => $agreements,
                    'vacancy' => $property_status,
        ]);
    }

    public function actionGetregions() {

        $id = Yii::$app->request->post('val');

        $model = \app\models\Branches::find()->where(['city_code' => $id])->all();
        $listData = \yii\helpers\ArrayHelper::map($model, 'branch_code', 'branch_code');

        $html = '<option value=""> Select Branch </option>';
        $html = '';
        foreach ($listData as $key => $list) {
            $html .= '<option value="' . $key . '">' . $list . '</option>';
        }
        echo $html;
        die;
    }

    public function actionTenantreferred() {
        $searchModelOwnerLeads = new \app\models\Users();

        /* $dataProviderOwner = $searchModelOwnerLeads->searchUser(Yii::$app->request->queryParams);



          return $this->render('tenantreferred', [
          'searchModelOwnerLeads' => $searchModelOwnerLeads,
          'dataProviderOwner' => $dataProviderOwner,
          ]); */
        $search1 = '';
        if (isset($_GET['search'])) {
            if (trim($_GET['search']) != '') {
                $search = str_replace(" ", "%%", $_GET['search']);
                $search1 = ' and (u.full_name like "%' . $search . '%" or u.login_id like "%' . $search . '%" or p.phone like "%' . $search . '%") ';
            }
        }
        $id = Yii::$app->user->id;
        $users = \app\models\Users::find()->select('id,login_id')->where(['refered_by' => $id, 'user_type' => 3])->all();

        $myUsers = [];
        $myUsersProperties = [];

        foreach ($users as $key => $user) {
            $myUsers[] = $user->id;
        }
        // $agreements = \app\models\MyAgreements::find()->where(['IN', 'email_id', $myUsers])->all();
        if (count($myUsers) != 0) {
            $connection = Yii::$app->getDb();
            $command = $connection->createCommand('select u.id,u.full_name,u.login_id,p.phone,a.lease_start_date,pr.property_name,pr.address_line_1,pr.pincode,pr.state,pr.city,s.name as state_name,c.city_name, a.lease_end_date from tenant_agreements as a left join users as u on a.tenant_id=u.id left join properties as pr on a.property_id=pr.id left join states as s on pr.state = s.id left join cities as c on pr.city = c.id left join tenant_profile as p on u.id=p.tenant_id where a.tenant_id IN (' . implode(",", $myUsers) . ')' . $search1);
            $agreements = $command->queryAll();
        } else {
            $agreements = Array();
        }

        /* echo "<pre>";
          print_r($agreements);
          echo  "</pre>";
          die; */
        return $this->render('tenantreferred', [
                    'model' => $agreements,
        ]);
    }

    public function actionFeesdetails($month = null, $year = null) {
        /* if ($month && $year) {
          $month = $month . "-" . $year;
          } else {
          $month = Date('m-Y');
          } */
        $id = Yii::$app->user->id;
        $tenants = Array();
        if (isset($_POST['month']) && isset($_POST['year'])) {
            //echo "hello";die;
            $month = $_POST['month'];
            $year = $_POST['year'];
            //echo $id;die;
            if ($month != '' && $year != '') {
                $advisorFees = \app\models\AdvisorPayments::find()->where(['advisor_id' => $id])->andWhere(['month' => $month])->andWhere(['year' => $year])->all();
            } else if ($month != '' && $year == '') {
                $advisorFees = \app\models\AdvisorPayments::find()->where(['advisor_id' => $id])->andWhere(['month' => $month])->all();
            } else if ($month == '' && $year != '') {
                $advisorFees = \app\models\AdvisorPayments::find()->where(['advisor_id' => $id])->andWhere(['year' => $year])->all();
            } else {
                $advisorFees = \app\models\AdvisorPayments::find()->where(['advisor_id' => $id])->all();
            }
        } else {
            $month = '';
            $year = '';
            $advisorFees = \app\models\AdvisorPayments::find()->where(['advisor_id' => $id])->all();
        }

        return $this->render('feedetail', ['feedetail' => $advisorFees, 'month' => $month, 'year' => $year]);
    }

    public function actionMyprofile() {
        $proofType = \app\models\ProofType::find()->all();
        $address_proofs = \app\models\UserIdProofs::find()->where(['user_id' => Yii::$app->user->id])->all();
        if (($model = \app\models\AdvisorProfile::findOne(['advisor_id' => Yii::$app->user->id])) !== null) {
            //$this->layout = 'app_dashboard';
            $advisorAgreements = \app\models\AdvisorAgreements::findOne(['advisor_id' => Yii::$app->user->id]);
            if (@$_REQUEST['type'] == 'p') {


                return $this->render('personal_info_adviser', [
                            'model' => $model,
                            'advisorAgreements' => $advisorAgreements,
                            'proofType' => $proofType,
                            'address_proofs' => $address_proofs]);
            } else if (@$_REQUEST['type'] == 'i') {
                return $this->render('identity_info_adviser', [
                            'model' => $model,
                            'advisorAgreements' => $advisorAgreements,
                            'proofType' => $proofType,
                            'address_proofs' => $address_proofs]);
            } else if (@$_REQUEST['type'] == 'b') {
                return $this->render('bank_detail_adviser', [
                            'model' => $model,
                            'advisorAgreements' => $advisorAgreements,
                            'proofType' => $proofType,
                            'address_proofs' => $address_proofs]);
            } else if (@$_REQUEST['type'] == 's') {
                return $this->render('support_data_adviser', [
                            'model' => $model,
                            'advisorAgreements' => $advisorAgreements,
                            'proofType' => $proofType,
                            'address_proofs' => $address_proofs]);
            } else if (@$_REQUEST['type'] == 'a') {
                return $this->render('agreement_detail_adviser', [
                            'model' => $model,
                            'advisorAgreements' => $advisorAgreements,
                            'proofType' => $proofType,
                            'address_proofs' => $address_proofs]);
            } else {
                $sql1 = 'SELECT au.* FROM advetisement_user_type as au LEFT JOIN advertisement_banners as ab ON au.advetisement_id=ab.id WHERE ab.status = "1" AND au.advisor = "1"';
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

                return $this->render('personal_info_adviser', [
                            'model' => $model,
                            'advisorAgreements' => $advisorAgreements,
                            'proofType' => $proofType,
                            'address_proofs' => $address_proofs,
                            'advertisements' => $advertisements]);
            }
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionEditprofile() {
        $proofType = \app\models\ProofType::find()->all();
        $address_proofs = \app\models\UserIdProofs::find()->where(['user_id' => Yii::$app->user->id])->all();
        if (($model = \app\models\AdvisorProfile::findOne(['advisor_id' => Yii::$app->user->id])) !== null) {
            $profileimage = $model->profile_image;

            if ($model->load(Yii::$app->request->post())) {
                if ($model->validate()) {

                    $profile_image = UploadedFile::getInstance($model, 'profile_image');
                    if (!empty($profile_image)) {
                        $name_profile_image = date('ymdHis') . $profile_image->name;
                        $profile_image->saveAs('uploads/profiles/' . $name_profile_image);
                        // $model->profile_image =  Url::home(true).'uploads/profiles/'.$name_profile_image ;
                        $model->profile_image = 'uploads/profiles/' . $name_profile_image;
                    } else {
                        $model->profile_image = $profileimage;
                    }

                    $model->save(false);

                    if (isset($_FILES['address_proof'])) {
                        $address_proof = $_FILES['address_proof'];
                    } else {
                        $address_proof = Array();
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
                                    $address_proofobj->proof_document_url = 'uploads/proofs/' . $name_address_proof;
                                    $address_proofobj->user_id = Yii::$app->user->id;
                                    $address_proofobj->created_by = Yii::$app->user->id;
                                    $address_proofobj->created_date = date('Y-m-d H:i:s');
                                    $address_proofobj->save(false);
                                }
                            }
                        }
                    }

                    $modelLeadsTenant = \app\models\LeadsAdvisor::find(['email_id' => Yii::$app->userdata->getUserEmailById(Yii::$app->user->id)])->one();
                    $modelLeadsTenant->contact_number = $model->phone;
                    $modelLeadsTenant->address = $model->address_line_1 . ' ' . $model->address_line_2;
                    $modelLeadsTenant->state = $model->state;
                    $modelLeadsTenant->city = $model->city;
                    $modelLeadsTenant->region = $model->region;
                    $modelLeadsTenant->pincode = $model->pincode;
                    $modelLeadsTenant->updated_date = date('Y-m-d H:i:s');
                    $modelLeadsTenant->save(false);

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

                return $this->render('editprofile', [
                            'model' => $model,
                            'proofType' => $proofType,
                            'address_proofs' => $address_proofs,
                ]);
            }
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionBankdetails() {


        if ($modelBankDetails = \app\models\BankDetails::findOne(['email_id' => Yii::$app->userdata->email]) !== null) {
            $modelBankDetails = \app\models\BankDetails::findOne(['email_id' => Yii::$app->userdata->email]);

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
            $modelBankDetails = new \app\models\BankDetails;

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

    /**
     * Creates a new Properties model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionOwner() {
        $model = new \app\models\LeadsOwner();
        $modelUsers = new \app\models\Users();
        $modelProfile = new \app\models\OwnerProfile();
        $comments = new \app\models\Comments();

        if ($model->load(Yii::$app->request->post()) && $modelProfile->load(Yii::$app->request->post())) {
            $model->created_by = Yii::$app->user->id;
            $model->reffered_by = Yii::$app->user->id;
            $model->created_date = date('Y-m-d H:i:s');
            $model->follow_up_date_time = '';
            $model->region = '0';
            $isValid = $model->validate(['full_name', 'email_id', 'address', 'lead_state', 'lead_city', 'communication']);
            $isValid2 = $modelProfile->validate(['city_name']);

            if ($isValid && $isValid2) {
                $userType = Yii::$app->user->identity->user_type;
                if ($userType == 5) {
                    $adUser = \app\models\AdvisorProfile::findOne(['advisor_id' => Yii::$app->user->identity->id]);
                    $branchCode = $adUser->branch_code;
                    $cityModel = \app\models\Cities::findOne(['id' => Yii::$app->request->post('LeadsOwner')['lead_city']]);
                    $cityCode = $cityModel->code;
                    $salesUser = \app\models\SalesProfile::findOne(['role_code' => 'SLCTMG', 'role_value' => $cityCode]);
                    $modelProfile->sales_id = $salesUser->sale_id;
                    $modelProfile->city_name = Yii::$app->request->post('OwnerProfile')['city_name'];
                    $opUser = \app\models\OperationsProfile::findOne(['role_code' => 'OPCTMG', 'role_value' => $cityCode]);
                    $modelProfile->operation_id = $opUser->operations_id;
                    //$modelProfile->branch_code = $branchCode;
                }

                $model->lead_city = Yii::$app->userdata->getCityCodeById(Yii::$app->request->post('LeadsOwner')['lead_city']);
                $model->lead_state = Yii::$app->userdata->getStateCodeByCityId(Yii::$app->request->post('LeadsOwner')['lead_city']);
                $model->save(false);

                $modelUsers->login_id = $model->email_id;
                $modelUsers->user_type = 4;
                $modelUsers->full_name = $model->full_name;
                $modelUsers->username = $model->email_id;
                $modelUsers->created_date = date('Y-m-d H:i:s');
                $modelUsers->refered_by = Yii::$app->user->id;
                $modelUsers->save(false);

                $modelProfile->owner_id = $modelUsers->id;
                $modelProfile->phone = $model->contact_number;
                $modelProfile->address_line_1 = $model->address;
                $modelProfile->address_line_2 = $model->address_line_2;
                $modelProfile->state = null;
                $modelProfile->region = $model->region;
                $modelProfile->pincode = $model->pincode;
                $modelProfile->save(false);

                $comments->description = $model->communication;
                $comments->user_id = $modelUsers->id;
                $comments->property_id = 0;
                $comments->created_by = Yii::$app->user->id;
                $comments->save(false);
                $users = \app\models\Users::findAll(['user_type' => '7']);
                $userList = Array();
                foreach ($users as $key => $value) {
                    $userList[] = $value->login_id;
                }
                $subject = 'New property owner lead';
                $msg = "Hello,<br/><br/>New property owner lead for $modelUsers->full_name has been created, please verify and assign the branch and sales person to this lead.<br/><br/>Contact details of property owner are $model->contact_number,$model->email_id<br/>With Regards,<br/>Easyleases Team";
                // Yii::$app->userdata->doMail(implode(",", $userList), $subject, $msg);

                Yii::$app->getSession()->setFlash(
                        'success', 'Thank you for referring a client. Our support team will be in touch with the client shortly. You can view the status of your referred client conversion under Client Onboarding Status Menu'
                );
                return $this->redirect(['advisers/owner']);
            } else {
                return $this->render('owner', [
                            'model' => $model,
                            'modelProfile' => $modelProfile
                ]);
            }
        } else {
            return $this->render('owner', [
                        'model' => $model,
                        'modelProfile' => $modelProfile
            ]);
        }
    }

    /**
     * Creates a new Properties model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionApplicant() {
        $model = new \app\models\LeadsTenant();
        $modelUsers = new \app\models\Users();
        $modelWallet = new \app\models\Wallets();
        $modelProfile = new \app\models\ApplicantProfile();
        $comments = new \app\models\Comments();
        $usermodel = \app\models\Users::find()->where(['id' => Yii::$app->user->id])->one();

        if ($model->load(Yii::$app->request->post()) && $modelProfile->load(Yii::$app->request->post())) {
            $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-=+?";
            $password = substr(str_shuffle($chars), 0, 8);

            $model->created_by = Yii::$app->user->id;
            $model->city = NULL;
            $model->state = NULL;
            $model->lead_state = Yii::$app->userdata->getStateCodeByCityId($model->lead_city);
            $model->lead_city = Yii::$app->userdata->getCityCodeById($model->lead_city);
            $model->reffered_by = Yii::$app->user->id;
            $model->created_date = date('Y-m-d H:i:s');
            $model->region = '0';
            $isValid = $model->validate(['full_name', 'email_id', 'contact_number', 'address', 'pincode', 'lead_city', 'communication']);
            $isValid2 = $modelProfile->validate(['city_name']);
            if ($isValid && $isValid2) {
                $userType = Yii::$app->user->identity->user_type;
                if ($userType == 5) {
                    $adUser = \app\models\AdvisorProfile::findOne(['advisor_id' => Yii::$app->user->identity->id]);
                    $branchCode = $adUser->branch_code;
                    $cityModel = \app\models\Cities::findOne(['id' => Yii::$app->request->post('LeadsTenant')['lead_city']]);
                    $cityCode = $cityModel->code;
                    $salesUser = \app\models\SalesProfile::findOne(['role_code' => 'SLCTMG', 'role_value' => $cityCode]);
                    $modelProfile->sales_id = $salesUser->sale_id;
                    $opUser = \app\models\OperationsProfile::findOne(['role_code' => 'OPCTMG', 'role_value' => $cityCode]);
                    $modelProfile->operation_id = $opUser->operations_id;
                }

                $model->save(false);

                $modelUsers->login_id = $model->email_id;
                $modelUsers->user_type = 2;
                $modelUsers->full_name = $model->full_name;
                $modelUsers->username = $model->email_id;
                $modelUsers->created_date = date('Y-m-d H:i:s');
                $modelUsers->refered_by = Yii::$app->user->id;
                $modelUsers->status = 1;
                $modelUsers->password = md5($password);
                $modelUsers->save(false);


                $modelWallet->amount = 0;
                $modelWallet->user_id = $modelUsers->id;
                $modelWallet->created_by = $modelUsers->id;
                $modelWallet->created_date = Date('Y-m-d H:i:s');
                $modelWallet->save(false);

                $modelProfile->applicant_id = $modelUsers->id;
                $modelProfile->phone = $model->contact_number;
                $modelProfile->address_line_1 = $model->address;
                $modelProfile->address_line_2 = $model->address_line_2;
                $modelProfile->state = null;
                $modelProfile->city = null;
                $modelProfile->region = null;
                $modelProfile->email_id = $model->email_id;
                $modelProfile->pincode = $model->pincode;
                $modelProfile->save(false);

                $comments->description = $model->communication;
                $comments->user_id = $modelUsers->id;
                $comments->property_id = 0;
                $comments->created_by = Yii::$app->user->id;
                $comments->save(false);

                $users = \app\models\Users::findAll(['user_type' => '7']);
                $userList = Array();
                foreach ($users as $key => $value) {
                    $userList[] = $value->login_id;
                }

                $msg = "Hello " . $model->full_name . ",<br/><br/>Greetings from Easyleases!!!<br/><br/> You have been referred by " . $usermodel->full_name . " to Easyleases. Your login credentials are provided below. You may search for properties on our website 'www.easyleases.in'. Our  sales representative will also be in touch with you to assist you with finding your dream home. <br/><br/>
				User id : " . $model->email_id . "<br/>
				Password : " . $password . "<br/><br/>
				With Regards,<br/>
Easyleases Team</br/>
<div style='text-align: right;'><img style='height: 53px; width: 200px;' src='http://www.easyleases.in/images/newlogo1.png' /></div>	";

                @Yii::$app->userdata->doMail($model->email_id, 'New Applicant Lead', $msg);

                Yii::$app->getSession()->setFlash(
                        'success', 'Thank you for referring a client. Our support team will be in touch with the client shortly. You can view the status of your referred client conversion under Client Onboarding Status Menu'
                );
                return $this->redirect(['advisers/applicant']);
            } else {
                return $this->render('applicant', [
                            'model' => $model, 'modelUsers' => $modelUsers, 'modelProfile' => $modelProfile
                ]);
            }
        } else {
            return $this->render('applicant', [
                        'model' => $model, 'modelUsers' => $modelUsers, 'modelProfile' => $modelProfile
            ]);
        }
    }

    /**
     * Updates an existing Properties model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */

    /**
     * Deletes an existing Properties model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Properties model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Properties the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (( $model = LeadsReferences::find()->where(['created_by' => $id])->all() ) !== null) {
            return $model;
        } else {
            return false;
        }
    }

    public function actionCheckifsc() {
        $ifsc = Yii::$app->request->post('ifsc');
        $ifsc = Yii::$app->userdata->getIFSCValidate($ifsc);
        echo $ifsc;
        exit;
    }

    public function actionEditpersonalinfoadviser() {
        if (isset(Yii::$app->user->id)) {
            //echo "<pre>";print_r($_POST);echo "</pre>";die;

            $userid = Yii::$app->user->id;
            
            $usermodel = \app\models\Users::find()->where(['id' => $userid])->one();
            $leadsAdvisor = \app\models\LeadsAdvisor::find()->where(['email_id' => $usermodel->login_id])->one();
            $advisormodel = \app\models\AdvisorProfile::find()->where(['advisor_id' => $userid])->one();
            $advisormodel->address_line_1 = $_POST['address1'];
            $advisormodel->address_line_2 = $_POST['address2'];
            $advisormodel->pincode = $_POST['pincode'];
            $advisormodel->phone = $_POST['mobile'];
            $advisormodel->state = $_POST['state'];
            $advisormodel->city_name = $_POST['city_name'];
            
            $usermodel->full_name = $_POST['name'];
            $usermodel->login_id = $_POST['email'];
            $usermodel->username = $usermodel->login_id;
            $usermodel->phone = $_POST['mobile'];
            
            if ($usermodel->validate(['full_name','phone','login_id'])) {
                if ($_FILES['fileupload']['name'] != '') {
                    $profile_image = $_FILES['fileupload'];
                    $target_dir = "uploads/profiles/";
                    $file_tmp = $profile_image['tmp_name'];
                    $file_name = $profile_image['name'];
                    $target_file = $target_dir . basename($profile_image["name"]);
                    move_uploaded_file($file_tmp, $target_dir . $file_name);
                    $advisormodel->profile_image = "uploads/profiles/" . $profile_image['name'];
                }



                $advisormodel->save(false);
                
                $leadsAdvisor->email_id = $usermodel->login_id;
                
                $usermodel->save(false);
                
                if ($_POST['dltimg'] == 0) {
                    $leadsAdvisor->profile_image = "";
                }
                $leadsAdvisor->save(false);
            }
        } else {

            return $this->redirect(['site/login']);
        }
    }

    public function actionEditidentityinfoadviser() {
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

    public function actionEditbankdetailadviser() {
        if (isset(Yii::$app->user->id)) {
            $userid = Yii::$app->user->id;

            $advisormodel = \app\models\AdvisorProfile::find()->where(['advisor_id' => $userid])->one();
            $advisormodel->account_holder_name = $_POST['account_holder_name'];
            $advisormodel->bank_name = $_POST['bank_name'];
            $advisormodel->bank_branchname = $_POST['bank_branchname'];
            $advisormodel->bank_ifcs = $_POST['bank_ifcs'];
            $advisormodel->account_number = $_POST['bank_account_number'];
            $advisormodel->pan_number = $_POST['pan_number'];
            $advisormodel->service_tax_number = $_POST['service_tax_number'];
            if ($_FILES['fileupload']['name'] != '') {
                $cancelled_check = $_FILES['fileupload'];
                $target_dir = "uploads/proofs/";
                $file_tmp = $cancelled_check['tmp_name'];
                $file_name = $cancelled_check['name'];
                $target_file = $target_dir . basename($cancelled_check["name"]);
                move_uploaded_file($file_tmp, $target_dir . $file_name);
                $advisormodel->cancelled_check = "uploads/proofs/" . $cancelled_check['name'];
            }
            $advisormodel->save(false);
            return $this->redirect(['myprofile?type=b']);
        } else {

            return $this->redirect(['site/login']);
        }
    }

}
