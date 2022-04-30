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
class ContactController extends Controller {

    public $layout = 'home';

    public function behaviors() {
        return [
            
        ];
    }

    public function actionIndex() {
        $model = new \app\models\ContactForm();
		//echo "hello";die;
        $this->view->params['head_title'] = "Contact Us - Easyleases | Rental Property Management Companies";
        $this->view->params['meta_desc'] = "Contact Us - Easyleases, Property Management. Easyleases Technologies Pvt. Ltd. Co-work 247, 1st AA Cross, 2nd Main Road, Kasturi Nagar, Bengaluru - 560043";

        if ($model->load(Yii::$app->request->post())) {
			//echo "hello1";die;
            if ($model->validate()) {
                $model->name = Yii::$app->request->post('ContactForm')['name'];
                $model->email = Yii::$app->request->post('ContactForm')['email'];
                $model->phone = Yii::$app->request->post('ContactForm')['phone'];
                $model->message = Yii::$app->request->post('ContactForm')['message'];
                $model->user_type = Yii::$app->request->post('ContactForm')['user_type'];
                $model->created_date = date('Y-m-d H:i:s');
                $model->save(false);
                
                $subject = 'User Contacted From EasyLeases.In';
                
                $messBody = '';
                $messBody .= '<p>Hello Admin,</p>';
                $messBody .= '<p>A user has tried contacting with us from www.easyleases.in, please check the below details.</p>';
                $messBody .= '<table>';
                $messBody .= '<tr>';
                $messBody .= '<td>Name: </td><td>'.Yii::$app->request->post('ContactForm')['name'].'</td>';
                $messBody .= '</tr>';
                $messBody .= '<tr>';
                $messBody .= '<td>Email: </td><td>'.Yii::$app->request->post('ContactForm')['email'].'</td>';
                $messBody .= '</tr>';
                $messBody .= '<tr>';
                $messBody .= '<td>Phone: </td><td>'.Yii::$app->request->post('ContactForm')['phone'].'</td>';
                $messBody .= '</tr>';
                $messBody .= '<tr>';
                $messBody .= '<td>Message: </td><td>'.Yii::$app->request->post('ContactForm')['message'].'</td>';
                $messBody .= '</tr>';
                $messBody .= '</table>';
                $messBody .= '<p>EasyLeases Team</p>';
                
                @$email = Yii::$app->userdata->doMail(Yii::$app->params['supportEmail'], $subject, $messBody);
                if ($email) {
                    Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
                } else {
                    Yii::$app->getSession()->setFlash('success', 'Email not sent, Please try again');
                }
                
            }

            return $this->redirect(['/thankyou']);
        }
		//echo "hello2";die;
        return $this->render('contact', [
                    'model' => $model,
        ]);
    }
}

?>