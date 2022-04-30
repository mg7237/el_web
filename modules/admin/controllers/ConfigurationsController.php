<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\Roles;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ConfigurationController implements the CRUD actions for Configuration model.
 */
class ConfigurationsController extends Controller {

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
                        'actions' => ['index', 'create', 'update', 'view', 'delete', 'createadvisers', 'pmsconfig', 'updateconfig'],
                        'roles' => ['@'],
                    ],
                // everything else is denied
                ],
            ],
        ];
    }

    /**
     * Lists all Roles models.
     * @return mixed
     */
    public function actionIndex() {
        $dataProvider = new ActiveDataProvider([
            'query' => \app\models\AdvisorsDefaultPaymentConfig::find(),
        ]);

        $pmsdataProvider = new ActiveDataProvider([
            'query' => \app\models\PmsPropertyConfigurations::find(),
        ]);
        $countConfig = count(\app\models\PmsPropertyConfigurations::find()->all());
        return $this->render('index', [
                    'dataProvider' => $dataProvider,
                    'pmsdataProvider' => $pmsdataProvider,
                    'countConfig' => $countConfig,
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
    public function actionCreateadvisers() {
        $model = new \app\models\AdvisorsDefaultPaymentConfig;

        if ($model->load(Yii::$app->request->post())) {

            $model->created_by = Yii::$app->user->getId();
            $model->created_date = date('Y-m-d H:i:s');

            if ($model->validate()) {

                $model->save();
                return $this->redirect(['index']);
            } else {
                return $this->render('createadvisers', [
                            'model' => $model,
                ]);
            }
        } else {
            return $this->render('createadvisers', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Creates a new Roles model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionPmsconfig() {
        $model = new \app\models\PmsPropertyConfigurations;

        if ($model->load(Yii::$app->request->post())) {

            $model->created_by = Yii::$app->user->getId();
            $model->create_time = date('Y-m-d H:i:s');

            if ($model->validate()) {

                $model->save();
                return $this->redirect(['index']);
            } else {
                return $this->render('_form', [
                            'model' => $model,
                ]);
            }
        } else {
            return $this->render('_form', [
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

        if ($model->load(Yii::$app->request->post())) {

            $model->updated_by = Yii::$app->user->getId();
            $model->update_time = date('Y-m-d H:i:s');

            if ($model->validate()) {

                $model->save(false);
                return $this->redirect(['index']);
            } else {
                return $this->render('createadvisers', [
                            'model' => $model,
                ]);
            }
        } else {
            return $this->render('createadvisers', [
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
    public function actionUpdateconfig($id) {
        $model = $this->findModelPms($id);
        if ($model->load(Yii::$app->request->post())) {

            $model->updated_by = Yii::$app->user->getId();
            $model->update_time = date('Y-m-d H:i:s');

            if ($model->validate()) {

                $model->save(false);
                return $this->redirect(['index']);
            } else {
                return $this->render('_form', [
                            'model' => $model,
                ]);
            }
        } else {
            return $this->render('_form', [
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

    /**
     * Finds the Roles model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Roles the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = \app\models\AdvisorsDefaultPaymentConfig::findOne($id)) !== null) {
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
    protected function findModelPms($id) {
        if (($model = \app\models\PmsPropertyConfigurations::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
