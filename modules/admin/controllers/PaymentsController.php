<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\Pages;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PagesController implements the CRUD actions for Pages model.
 */
class PaymentsController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            /*  'verbs' => [
              'class' => VerbFilter::className(),
              'actions' => [
              'delete' => ['POST'],
              ],
              ], */
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'actions' => ['index', 'create', 'update', 'view', 'delete'],
                        'roles' => ['@'],
                    ],
                // everything else is denied
                ],
            ],
        ];
    }

    /**
     * Lists all Pages models.
     * @return mixed
     */
    public function actionIndex() {
        $date = date('Y-m-d H:i:s');
        $gst = Yii::$app->db->createCommand("select tax_percentage from  tax_percentage where tax_name='GST'")
                ->queryAll();
        $tax_percentage = $gst[0]['tax_percentage'];


        $properties = Yii::$app->db->createCommand("select * from  property_listing")
                ->queryAll();

        foreach ($properties as $pr) {
            $rent = $pr['rent'];
            $gross_pms_fee = ($rent * 10) / 100;
            $payment_gateway_fee = $gross_pms_fee / 100;
            $net_pms_fee = $gross_pms_fee - $payment_gateway_fee;
            $service_tax = ($net_pms_fee * $tax_percentage) / 100;
            //echo $service_tax;die;
            if ($rent <= 50000) {
                $tds = 0
            } else {
                $tds = ($rent * 5) / 100;
            }
            $owner_payment = $rent - ($gross_pms_fee + $service_tax + $tds);
        }
    }
    