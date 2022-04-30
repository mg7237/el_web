<?php

namespace app\controllers;

use yii\web\NotFoundHttpException;
use Yii;
use yii\web\UploadedFile;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\PropertyServiceRequest;
use app\models\LoginForm;
use app\models\MyAgreements;
use app\models\LeadsTenant;
use app\models\LeadsOwner;
use app\models\PropertyAgreements;
use app\models\Agreements;
use app\models\Users;
use app\models\Profiles;
use app\models\User;
use app\models\PropertyVisits;
use app\models\TenantAgreements;
use app\models\ContactForm;
use app\models\ForgetForm;
use app\models\Feedback;
use app\models\RegisterForm;
use app\models\Properties;
use app\models\PropertiesAddress;
use app\models\PropertyAttributeMap;
use app\models\PropertyImages;
use app\models\Bookings;
use app\models\BookProperties;
use app\models\PropertiesAttributes;
use yii\helpers\Url;
use app\models\FavouriteProperties;
use app\models\Wallets;
use app\models\WalletsHistory;
use app\models\BankDetails;
use app\models\UserIdProofs;
use app\models\Stepinfo;
use app\models\TenantPayments;
use yii\data\Pagination;
use app\components\Pdfcrowd;
use app\models\ServiceRequest;
use app\models\ServiceRequestAttachment;
use app\models\ApplicantProfile;
use app\models\Advertisements;
use app\models\OwnerPaymentSummary;

class TinyurlController extends Controller {

    public $layout = false;
    
    public function beforeAction($action) {
        $methods = [
            'decodetinyurl'
        ];
        if (in_array($action->id, $methods)) {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }
    
    public function actionDecodetinyurl ($code) {
        try {
            $tinyModel = \app\models\TinyUrl::find()->where(['tiny_url' => $code])->one();
            $this->redirect($tinyModel->actual_url);
        } catch (\Exception $e) {
            
        }
    }
}
