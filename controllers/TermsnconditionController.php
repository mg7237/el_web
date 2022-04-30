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

/**
 * PropertiesController implements the CRUD actions for Properties model.
 */
class TermsnconditionController extends Controller {

    public $layout = 'home';

    public function behaviors() {
        return [
            
        ];
    }

    public function actionIndex() {
        $this->view->params['head_title'] = "Terms and Conditions - Easyleases | Rental Management Companies";
        $this->view->params['meta_desc'] = "Terms and Conditions - Easyleases, Rental Management Companies, Professional Property Management, List Of Property Management Companies, Home Maintenance";
        $_GET['slug'] = 'terms-and-condition';
        $slug = $_GET['slug'];
        if($_SERVER["REQUEST_METHOD"] == "POST")
        {
            echo "<pre>";print_r($_POST);echo "</pre>";die;
        }
        $this->layout = 'home';
        $page = \app\models\Pages::find()->where(['slug' => $slug])->One();
        return $this->render('page', ['data' => $page]);
    }
}

?>