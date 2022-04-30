<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\Url;

Class AdminController extends Controller {

    public $layout = 'admin_dashboard';

    public function init() {
        if (!Yii::$app->user->id) {
            header('location:' . Yii::$app->userdata->getDefaultUrl());
            die;
        }
        $action = explode("/", $this->module->requestedRoute);
        $newAction = Array();
        foreach ($action as $link) {
            if (trim($link) != '') {
                $newAction[] = $link;
            }
        }

        $action = implode("/", $newAction);
        if (Yii::$app->user->id) {
            $checked = Yii::$app->userdata->checkvalid($action, Yii::$app->user->identity->user_type);
            if ($checked['status'] == 0) {
                //$this->redirect($checked['action']);
            }
        }
    }

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['innerusers', 'externalusers', 'editinternalusers', 'editexternalusers'],
                'rules' => [
                    [
                        'actions' => [],
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

    public function actionExternalusers() {
        $searchModelOwnerLeads = new \app\models\Users();

        $dataProviderOwner = $searchModelOwnerLeads->search(Yii::$app->request->queryParams);
        print_r($dataProviderOwner);
        // return $this->render('servicerequests',[
        //         'searchModelOwnerLeads' => $searchModelOwnerLeads,
        //         'dataProviderOwner' => $dataProviderOwner,
        // ]);
    }

}
