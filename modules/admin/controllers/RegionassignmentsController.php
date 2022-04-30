<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\Roles;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RolesController implements the CRUD actions for Roles model.
 */
class RegionassignmentsController extends Controller {

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
                        'actions' => ['index', 'create', 'getusers', 'choice', 'assignuser', 'update', 'view', 'delete'],
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
            'query' => \app\models\RegionAssignment::find(),
        ]);

        return $this->render('index', [
                    'dataProvider' => $dataProvider,
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
    public function actionAssignuser() {
        $model = new \app\models\RegionAssignment;
        $modelUserAssignments = new \app\models\UserAssignments;

        if ($model->load(Yii::$app->request->post())) {

            $model->created_by = Yii::$app->user->getId();
            $model->create_time = date('Y-m-d H:i:s');

            $modelUserAssignments->user_id = $model->user_id;
            $modelUserAssignments->manager_id = Yii::$app->userdata->getManagerId($model->assign_region_id, $model->type);
            $modelUserAssignments->assigned_time = date('Y-m-d H:i:s');
            $modelUserAssignments->assigned_by = Yii::$app->user->getId();
            ;

            if ($model->validate()) {

                $model->save();
                $modelUserAssignments->save(false);
                return $this->redirect(['index']);
            } else {
                return $this->render('assignments', [
                            'model' => $model,
                ]);
            }
        } else {
            return $this->render('assignments', [
                        'model' => $model,
            ]);
        }
    }

    public function actionChoice() {

        echo $id = Yii::$app->request->post('val');
        if ($id == 1) {

            $model = \app\models\States::find()->all();
            $listData = \yii\helpers\ArrayHelper::map($model, 'id', 'name');
        } elseif ($id == 2) {

            $model = \app\models\Cities::find()->all();
            $listData = \yii\helpers\ArrayHelper::map($model, 'id', 'city_name');
        } elseif ($id == 3) {

            $model = \app\models\Regions::find()->all();
            $listData = \yii\helpers\ArrayHelper::map($model, 'id', 'name');
        } else {
            $listData = [];
        }

        $html = '<option value=""> Select Region </option>';
        foreach ($listData as $key => $list) {
            $html .= '<option value="' . $key . '">' . $list . '</option>';
        }
        echo $html;
        die;
    }

    public function actionGetusers() {

        $id = Yii::$app->request->post('type');
        $user_type = Yii::$app->request->post('user_type');

        if ($user_type == 1) {

            if ($id == 1) {
                $role = 4;
            } else if ($id == 2) {
                $role = 3;
            } else {
                $role = 2;
            }
            $model = \app\models\Users::find()->where(['user_type' => 6, 'role' => $role])->all();
            $listData = \yii\helpers\ArrayHelper::map($model, 'id', 'full_name');
        } else {

            if ($id == 1) {
                $role = 8;
            } else if ($id == 2) {
                $role = 7;
            } else {
                $role = 6;
            }

            $model = \app\models\Users::find()->where(['user_type' => 7, 'role' => $role])->all();
            $listData = \yii\helpers\ArrayHelper::map($model, 'id', 'full_name');
        }


        $html = '<option value=""> Select User </option>';
        foreach ($listData as $key => $list) {
            $html .= '<option value="' . $key . '">' . $list . '</option>';
        }
        echo $html;
        die;
    }

    /**
     * Updates an existing Roles model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $modelUserAssignments = $this->findModelUserAssignments($model->user_id);

        if ($model->load(Yii::$app->request->post())) {

            $model->updated_by = Yii::$app->user->getId();
            $model->update_time = date('Y-m-d H:i:s');

            $modelUserAssignments->user_id = $model->user_id;
            $modelUserAssignments->manager_id = Yii::$app->userdata->getManagerId($model->assign_region_id, $model->type);
            $modelUserAssignments->update_time = date('Y-m-d H:i:s');

            if ($model->validate()) {

                $model->save(false);
                $modelUserAssignments->save(false);
                return $this->redirect(['index']);
            } else {
                return $this->render('assignments', [
                            'model' => $model,
                ]);
            }
        } else {
            return $this->render('assignments', [
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
        if (($model = \app\models\RegionAssignment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findModelUserAssignments($id) {
        if (($model = \app\models\UserAssignments::find()->where(['user_id' => $id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
