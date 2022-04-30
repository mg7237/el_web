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
class UserassignmentsController extends Controller {

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
                        'actions' => ['index', 'create', 'assignments', 'getmanager', 'update', 'view', 'delete'],
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
            'query' => \app\models\UserAssignments::find(),
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
    public function actionCreate() {
        $model = new Roles();

        if ($model->load(Yii::$app->request->post())) {

            $model->created_by = Yii::$app->user->getId();
            $model->created_time = date('Y-m-d H:i:s');

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

    /**
     * Creates a new Roles model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionAssignments() {
        $model = new \app\models\UserAssignments;
        $modelUserAssign = \app\models\UserAssignments::find()->all();
        $modelUsersTypes = \app\models\Users::find()->where(['IN', 'user_type', [6, 7]])->all();

        if ($model->load(Yii::$app->request->post())) {

            $model->assigned_by = Yii::$app->user->getId();
            $model->assigned_time = date('Y-m-d H:i:s');

            if ($model->validate()) {

                $model->save(false);
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

    public function actionGetmanager() {

        $id = Yii::$app->request->post('val');
        $listData = [];
        $user = \app\models\Users::findOne($id);
        if (in_array($user->role, [4, 8])) {
            $model = \app\models\Users::find()->where(['user_type' => 1])->all();
            $listData = \yii\helpers\ArrayHelper::map($model, 'id', 'username');
        } else if (in_array($user->role, [2, 6])) {
            $model = \app\models\Users::find()->where(['user_type' => 1])->all();
            $listData = \yii\helpers\ArrayHelper::map($model, 'id', 'username');
        }



        $html = '<option value=""> Select Manager </option>';
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

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {

                $model->update_time = date('Y-m-d H:i:s');
                $model->save();

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
        if (($model = \app\models\UserAssignments::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
