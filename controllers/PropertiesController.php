<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use app\models\Properties;
use app\models\Agreements;
use app\models\PropertiesSearch;
use app\models\PropertiesAddress;
use app\models\PropertyImages;
use app\models\PropertiesAttributes;
use app\models\PropertyAttributeMap;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * PropertiesController implements the CRUD actions for Properties model.
 */
class PropertiesController extends Controller {

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
                'only' => ['create', 'deleteproperty', 'update'],
                'rules' => [
                    [
                        'actions' => ['create', 'deleteproperty', 'update'],
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
        $searchModel = new PropertiesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
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
     * Creates a new Properties model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->layout = 'home';
        $model = new Properties();
        $modelAddress = new PropertiesAddress();
        $modelImages = new PropertyImages();
        $modelAgreements = new Agreements();
        $propertyAttributeMap = new PropertyAttributeMap();
        $modelPropertiesAttributes = PropertiesAttributes::find()->where('type ="0" OR type ="2"')->all();

        $modelPropertiesAttributesInputs = PropertiesAttributes::find()->where(['type' => '1'])->all();


        if ($model->load(Yii::$app->request->post()) && $modelAddress->load(Yii::$app->request->post()) && $propertyAttributeMap->load(Yii::$app->request->post()) && $modelAgreements->load(Yii::$app->request->post())) {

            if ($model->maintenance_included == 1) {
                $model->maintenance = 0;
            }

            if ($model->property_type == 3) {
                $model->is_room = 0;
                $model->no_of_beds = 0;
                $model->no_of_rooms = 0;
            } else {
                $model->flat_bhk = 0;
                $model->flat_type = '0';
                $model->flat_area = 0;
                if ($model->is_room == 0) {
                    $model->no_of_rooms = 0;
                } else {
                    $model->no_of_beds = 0;
                }
            }
            $model->owner_id = Yii::$app->userdata->email;
            $model->property_code = '#t' . $model->property_type . 'u' . Yii::$app->user->id . 't' . date('YmdHis');
            $model->created_by = Yii::$app->user->id;
            $model->created_time = date('Y-m-d H:i:s');
            $modelImages->image_url = UploadedFile::getInstances($modelImages, 'image_url');


            $isValid = $model->validate();
            $isValids = $modelImages->validate();
            $isValidmodelAgreements = $modelAgreements->validate();
            $isValid = $modelAddress->validate() && $isValid;
            $isValid = $isValids && $isValid;
            $isValid = $isValid && $isValidmodelAgreements;

            if ($isValid) {
                //	echo "<pre>";print_r($propertyAttributeMap);echo "</pre>";
                $model->availability_from = date('Y-m-d H:i:s', strtotime($model->availability_from));
                if ($model->save(false)) {

                    $modelAddress->property_id = $model->id;
                    $modelAddress->save(false);

                    $modelAgreements->email_id = Yii::$app->userdata->email;
                    $modelAgreements->property_id = $model->id;
                    $modelAgreements->rent = $model->rent;
                    $modelAgreements->manteinace = isset($model->manteinace) ? $model->manteinace : 0;
                    $modelAgreements->deposit = $model->deposit;
                    $modelAgreements->created_by = Yii::$app->user->id;
                    $modelAgreements->create_date = date('Y-m-d H:i:s');
                    $modelAgreements->save(false);

                    if (isset($_POST['PropertyAttributeMap']['value'])) {
                        foreach ($_POST['PropertyAttributeMap']['value'] as $key => $propertyMap) {
                            $propertyAttributeMap = new PropertyAttributeMap();
                            $propertyAttributeMap->property_id = $model->id;
                            $propertyAttributeMap->attr_id = $key;
                            $propertyAttributeMap->value = 1;
                            $propertyAttributeMap->save(false);
                        }
                    }
                    if (isset($_POST['PropertyAttributeMap']['value2'])) {

                        foreach ($_POST['PropertyAttributeMap']['value2'] as $key => $dataValue) {
                            if (!empty($dataValue)) {
                                $propertyAttributeMap = new PropertyAttributeMap();
                                $propertyAttributeMap->property_id = $model->id;
                                $propertyAttributeMap->attr_id = $key;
                                $propertyAttributeMap->value = $dataValue;
                                $propertyAttributeMap->save(false);
                            }
                        }
                    }

                    $this->upload($model->id, $modelImages);
                }

                return $this->redirect(['site/mydashboard']);
            } else {
                return $this->render('create', [
                            'model' => $model, 'modelAddress' => $modelAddress, 'modelAgreements' => $modelAgreements, 'modelImages' => $modelImages, 'modelPropertiesAttributes' => $modelPropertiesAttributes, 'propertyAttributeMap' => $propertyAttributeMap, 'modelPropertiesAttributesInputs' => $modelPropertiesAttributesInputs
                ]);
            }
        } else {
            return $this->render('create', [
                        'model' => $model, 'modelAddress' => $modelAddress, 'modelAgreements' => $modelAgreements, 'modelImages' => $modelImages, 'modelPropertiesAttributes' => $modelPropertiesAttributes, 'propertyAttributeMap' => $propertyAttributeMap, 'modelPropertiesAttributesInputs' => $modelPropertiesAttributesInputs
            ]);
        }
    }

    /**
     * Updates an existing Properties model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->layout = 'home';

        $modelImages = new PropertyImages();
        $model = $this->findModel($id);
        $modelAddress = $this->findModelAddress($id);
        $modelAgreements = $this->findModelAgreements($id);
        $propertyAttributeMap = new PropertyAttributeMap();
        $modelPropertiesAttributes = PropertiesAttributes::find()->where('type ="0" OR type ="2"')->all();

        $dataImages = $this->findPropertyImages($id);

        $modelPropertiesAttributesInputs = PropertiesAttributes::find()->where(['type' => '1'])->all();


        if ($model->load(Yii::$app->request->post()) && $modelAddress->load(Yii::$app->request->post()) && $propertyAttributeMap->load(Yii::$app->request->post()) && $modelAgreements->load(Yii::$app->request->post())) {

            if ($model->maintenance_included == 1) {
                $model->maintenance = 0;
            }

            if ($model->property_type == 3) {
                $model->is_room = 0;
                $model->no_of_beds = 0;
                $model->no_of_rooms = 0;
            } else {
                $model->flat_bhk = 0;
                $model->flat_type = '0';
                $model->flat_area = 0;
                if ($model->is_room == 0) {
                    $model->no_of_rooms = 0;
                } else {
                    $model->no_of_beds = 0;
                }
            }

            $model->updated_by = Yii::$app->user->id;
            $model->update_time = date('Y-m-d H:i:s');
            $model->availability_from = date('Y-m-d H:i:s', strtotime($model->availability_from));

            $modelImages->image_url = UploadedFile::getInstances($modelImages, 'image_url');

            $isValid = $model->validate();
            $isValids = $modelImages->validate();
            $isValidmodelAgreements = $modelAgreements->validate();
            $isValid = $modelAddress->validate() && $isValid;
            $isValid = $isValids && $isValid;
            $isValid = $isValid && $isValidmodelAgreements;
            //die('hello');
            if ($isValid) {

                if ($model->save(false)) {
                    $modelAddress->property_id = $model->id;
                    $modelAddress->save(false);


                    $modelAgreements->rent = $model->rent;
                    $modelAgreements->manteinace = isset($model->manteinace) ? $model->manteinace : 0;
                    $modelAgreements->deposit = $model->deposit;
                    $modelAgreements->updated_by = Yii::$app->user->id;
                    $modelAgreements->update_time = date('Y-m-d H:i:s');
                    $modelAgreements->save(false);

                    if (isset($_POST['PropertyAttributeMap']['value'])) {

                        PropertyAttributeMap::deleteAll('property_id = :p_id', [':p_id' => $model->id]);
                        foreach ($_POST['PropertyAttributeMap']['value'] as $key => $propertyMap) {
                            $propertyAttributeMap = new PropertyAttributeMap();
                            $propertyAttributeMap->property_id = $model->id;
                            $propertyAttributeMap->attr_id = $key;
                            $propertyAttributeMap->value = 1;
                            $propertyAttributeMap->save(false);
                        }
                    }

                    if (isset($_POST['PropertyAttributeMap']['value2'])) {

                        foreach ($_POST['PropertyAttributeMap']['value2'] as $key => $dataValue) {
                            if (!empty($dataValue)) {
                                $propertyAttributeMap = new PropertyAttributeMap();
                                $propertyAttributeMap->property_id = $model->id;
                                $propertyAttributeMap->attr_id = $key;
                                $propertyAttributeMap->value = $dataValue;
                                $propertyAttributeMap->save(false);
                            }
                        }
                    }
                    //	echo "<pre>";print_r($_POST['PropertyAttributeMap']);echo"</pre>";die;

                    $this->upload($model->id, $modelImages);
                }

                return $this->redirect(['site/mydashboard']);
            } else {
                return $this->render('update', [
                            'model' => $model, 'modelAddress' => $modelAddress, 'dataImages' => $dataImages, 'modelAgreements' => $modelAgreements, 'modelImages' => $modelImages, 'modelPropertiesAttributes' => $modelPropertiesAttributes, 'propertyAttributeMap' => $propertyAttributeMap
                            , 'modelPropertiesAttributesInputs' => $modelPropertiesAttributesInputs
                ]);
            }
        } else {
            return $this->render('update', [
                        'model' => $model, 'modelAddress' => $modelAddress, 'dataImages' => $dataImages, 'modelAgreements' => $modelAgreements, 'modelImages' => $modelImages, 'modelPropertiesAttributes' => $modelPropertiesAttributes, 'propertyAttributeMap' => $propertyAttributeMap
                        , 'modelPropertiesAttributesInputs' => $modelPropertiesAttributesInputs
            ]);
        }
    }

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

    public function actionDeleteproperty($id) {
        $this->findModel($id)->delete();
        $address = $this->findModelAddress($id);
        if ($address) {
            $address->delete();
        }

        return $this->redirect(['site/mydashboard']);
    }

    /**
     * Finds the Properties model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Properties the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Properties::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findModelAddress($id) {
        if (($model = PropertiesAddress::find()->where(['property_id' => $id])->one()) !== null) {
            return $model;
        } else {
            return false;
        }
    }

    protected function findModelAgreements($id) {
        if (($model = Agreements::find()->where(['property_id' => $id])->one()) !== null) {
            return $model;
        } else {
            return false;
        }
    }

    public function upload($propertyID, $data) {

        if (!empty($data)) {
            foreach ($data->image_url as $file) {
                $modelImages = new PropertyImages();

                $name = date('ymdHis') . $file->name;
                $file->saveAs('uploads/' . $name);

                $modelImages->image_url = $name;
                $modelImages->property_id = $propertyID;
                $modelImages->image_upload_time = date('Y-m-d H:i:s');
                $modelImages->save(false);
            }
            return true;
        } else {
            return false;
        }
    }

    private function findPropertyImages($id) {

        $model = PropertyImages::find()->where(['property_id' => $id])->all();

        if ($model !== null) {
            return $model;
        }
    }

}
