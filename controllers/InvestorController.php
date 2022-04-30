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



class InvestorController extends Controller {

    public $layout = 'investor_dashboard';
    
    public function behaviors() {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex() {
        return $this->render('income_statement');
    }
    
    public function actionIncomestatements() {
        if (!empty(Yii::$app->request->post())) {
            
        }
        
        return $this->render('income_statement');
    }
    
    public function actionExpensestatements() {
        if (!empty(Yii::$app->request->post())) {
            
        }
        
        return $this->render('expense_statement');
    }
    
    public function actionGetexpensereporting () {
        $this->layout = false;
        $headers = [];
        $headers[] = 'Expense Type';
        $headers[] = 'Expense Amount';
        $headers[] = 'Paid On';
        $headers[] = 'Paid By';
        $headers[] = 'Approved By';
        $headers[] = 'Vendor Name';
        $headers[] = 'Invoice Date';
        $headers[] = 'Invoice Number';
        $headers[] = 'Vendor GST';
        $headers[] = 'GST Amount';
        $headers[] = 'Remarks';
        $headers[] = 'Attachment';
        if (!empty(Yii::$app->request->post())) {
            extract(Yii::$app->request->post());
            
            $propertyId = $property_id;
            $statementType = $expense_statement_type;
            $expenseFromMonth = (int) $expense_statement_from_month;
            $expenseFromQuarterly = (int) $expense_statement_from_quarterly;
            $expenseFromFY = (int) $expense_statement_from_fy;
            
            $statementTypeSQL = '';
            if ($statementType == 1) {
                $paidOnSQL = ' MONTH(paid_on) = '.$expenseFromMonth.' AND ';
            }
            
            if ($statementType == 2) {
                $paidOnSQL = ' MONTH(paid_on) BETWEEN "'.$expenseFromQuarterly.'" AND "'.($expenseFromQuarterly + 4).'" AND ';
            }
            
            if ($statementType == 3) {
                $paidOnSQL = ' YEAR(paid_on) BETWEEN "'.$expenseFromFY.'" AND "'.($expenseFromFY + 1).'" AND ';
            }
            
            $expenseTable = \app\models\PropertyExpense::tableName();
            $connection = \Yii::$app->db;
            $model = $connection->createCommand('SELECT * FROM '.$expenseTable.' WHERE '
                    . ' '.$paidOnSQL.' '
                    . ' property_id = '.$property_id.' '
                    . ' ORDER BY id DESC '
                    . '');
            
            $expenseData = $model->queryAll();
            
            return $this->render('expense-reporting-html', ['property_id' => $property_id, 'headers' => $headers, 'expenseData' => $expenseData]);
        }
    }
    
    public function actionExportexpensereport () {
        $this->layout = false;
        $postData = Yii::$app->request->post('htmldata');
        if (!empty($postData)) {
            $propName = Yii::$app->userdata->getPropertyNameById(Yii::$app->request->post('property_id'));
            $now = mt_rand(1000000, 10000000).time();
            $path = 'uploads/expense/reporting/';
            $fileName = $path.$now. '.pdf';
            $pdf = Yii::$app->userdata->PDFReceipt($postData, $fileName);
            $pdfFile = fopen($fileName,'w');
            fwrite($pdfFile,$pdf);
            fclose($pdfFile);
            
            echo $now.'.pdf';exit;
        }
    }
    
    public function actionDownloadexpensereport ($d) {
        $fileName = $d;
        $this->layout = false;
        $path = 'uploads/expense/reporting/';
        if (!empty($fileName) && !empty(@file_get_contents($path.$fileName))) {
            $content = file_get_contents($path.$fileName);
            //unlink($path.$fileName);
            header("Content-Type: application/pdf");
            header("Content-Disposition: attachment;filename={$fileName}");
            header("Content-Transfer-Encoding: binary");
            echo $content;
            //return $content;
        }
        exit;
    }
}