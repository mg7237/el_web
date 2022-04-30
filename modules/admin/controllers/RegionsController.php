<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\Roles;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use app\models\States;
use app\models\Cities;
use app\models\Regions;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RolesController implements the CRUD actions for Roles model.
 */
class RegionsController extends Controller {

    public $layout = 'main';

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            /* 'verbs' => [
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
                        'actions' => ['cities', 'states', 'index', 'create', 'createregion', 'updateregion', 'createcity', 'updatecity', 'createstate', 'updatestate', 'update', 'view', 'delete', 'getcities', 'deletestate', 'deletecity', 'deleteregion'],
                        'roles' => ['@'],
                    ],
                // everything else is denied
                ],
            ],
        ];
    }

    public function actionGetcities() {

        $id = Yii::$app->request->post('val');
        $model = \app\models\Cities::find()->where(['state_id' => $id])->all();
        $listData = \yii\helpers\ArrayHelper::map($model, 'id', 'city_name');
        $html = '';
        foreach ($listData as $key => $list) {
            $html .= '<option value="' . $key . '">' . $list . '</option>';
        }
        echo $html;
        die;
    }

    /**
     * Lists all Roles models.
     * @return mixed
     */
    public function actionIndex() {
        $dataProviderTitle = 'Branch Management';
//        $dataProviderStates = new ActiveDataProvider([
//            'query' => \app\models\States::find(),
//        ]);
        
        $dataProviderStates = '';

//        $dataProviderCities = new ActiveDataProvider([
//            'query' => \app\models\Cities::find(),
//        ]);
        
        $dataProviderCities = '';
        
        $dataProviderRegions = new ActiveDataProvider([
            'query' => \app\models\Regions::find(),
        ]);
        
        return $this->render('index', [
            'dataProviderStates' => $dataProviderStates, 
            'dataProviderCities' => $dataProviderCities, 
            'dataProviderRegions' => $dataProviderRegions,
            'dataProviderTitle' => $dataProviderTitle,
        ]);
    }
    
    public function actionStates() {
        $dataProviderTitle = 'State Management';
        
        $dataProviderStates = new ActiveDataProvider([
            'query' => \app\models\States::find(),
        ]);

//        $dataProviderCities = new ActiveDataProvider([
//            'query' => \app\models\Cities::find(),
//        ]);
//        $dataProviderRegions = new ActiveDataProvider([
//            'query' => \app\models\Regions::find(),
//        ]);
        
        $dataProviderCities = '';
        $dataProviderRegions = '';
        return $this->render('index', [
            'dataProviderStates' => $dataProviderStates, 
            'dataProviderCities' => $dataProviderCities, 
            'dataProviderRegions' => $dataProviderRegions,
            'dataProviderTitle' => $dataProviderTitle,
        ]);
    }
    
    public function actionCities() {
        $dataProviderTitle = 'City Management';
        
        $dataProviderCities = new ActiveDataProvider([
            'query' => \app\models\Cities::find(),
        ]);
        
//        $dataProviderStates = new ActiveDataProvider([
//            'query' => \app\models\States::find(),
//        ]);
//        
//        $dataProviderRegions = new ActiveDataProvider([
//            'query' => \app\models\Regions::find(),
//        ]);
        
        $dataProviderStates = '';
        $dataProviderRegions = '';

        return $this->render('index', [
            'dataProviderStates' => $dataProviderStates, 
            'dataProviderCities' => $dataProviderCities, 
            'dataProviderRegions' => $dataProviderRegions,
            'dataProviderTitle' => $dataProviderTitle,
        ]);
    }

    /**
     * Displays a single Roles model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Roles model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Roles();

        if ($model->load(Yii::$app->request->post())) {

            $model->create_time = date('Y-m-d H:i:s');

            if ($model->validate()) {

                $model->save();
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                            'model' => $model,
                ]);
            }
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    public function actionCreatestate() {
        $model = new \app\models\States;

        if ($model->load(Yii::$app->request->post())) {

            $model->created_date = date('Y-m-d H:i:s');
            $model->created_by = 0;
            $model->updated_date = date('Y-m-d H:i:s');

            if ($model->validate()) {

                $model->save();
                return $this->redirect(['index']);
            } else {
                return $this->render('createstate', [
                            'model' => $model,
                ]);
            }
        } else {
            return $this->render('createstate', [
                        'model' => $model,
            ]);
        }
    }

    public function actionCreatecity() {
        $model = new \app\models\Cities;

        if ($model->load(Yii::$app->request->post())) {

            //	$model->created_date = date('Y-m-d H:i:s');

            if ($model->validate()) {
                $model->state_code = Yii::$app->userdata->getStateCodeById($model->state_id);
                $model->save();
                return $this->redirect(['cities']);
            } else {
                return $this->render('createcity', [
                            'model' => $model,
                ]);
            }
        } else {
            return $this->render('createcity', [
                        'model' => $model,
            ]);
        }
    }

    public function actionCreateregion() {
        $model = new \app\models\Regions;

        if ($model->load(Yii::$app->request->post())) {
            //echo "<pre>"; print_r($model); die;

            $model->created_date = date('Y-m-d H:i:s');
            $model->updated_date = date('Y-m-d H:i:s');
            $model->created_by = 0;
            $model->updated_by = 0;

            if ($model->validate()) {

                $model->save(false);
                return $this->redirect(['index']);
            } else {
                return $this->render('createregion', [
                            'model' => $model,
                ]);
            }
        } else {
            return $this->render('createregion', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Roles model.
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

    public function actionUpdatecity($id) {
        $model = $this->findModelCity($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['cities']);
        } else {
            return $this->render('updatecity', [
                        'model' => $model,
            ]);
        }
    }

    public function actionUpdateregion($id) {
        $model = $this->findModelRegion($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('updateregion', [
                        'model' => $model,
            ]);
        }
    }

    public function actionUpdatestate($id) {
        $model = $this->findModelStates($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('updatestate', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Roles model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionDeletestate($id) {
        $this->findModel_state($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel_state($id) {
        if (($model = States::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionDeletecity($id) {
        $this->findModel_city($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel_city($id) {
        if (($model = Cities::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionDeleteregion($id) {
        $this->findModel_region($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel_region($id) {
        if (($model = Regions::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Finds the Roles model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Roles the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Roles::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findModelStates($id) {
        if (($model = \app\models\States::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findModelCity($id) {
        if (($model = \app\models\Cities::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findModelRegion($id) {
        if (($model = \app\models\Regions::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
