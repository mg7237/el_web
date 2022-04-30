<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\Roles;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Advertisements;
use app\models\AdvertisementUserType;

/**
 * ConfigurationController implements the CRUD actions for Configuration model.
 */
class AdvertisementsController extends Controller {

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
                        'actions' => ['index', 'create', 'update', 'view', 'ajaxstatus', 'delete', 'createadvisers', 'pmsconfig', 'updateconfig'],
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
            'query' => Advertisements::find(),
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
		  $advertisementtype = \app\models\AdvertisementUserType::find()->where(['advetisement_id' => $id])->one();
        return $this->render('view', [
                    'model' => $this->findModel($id),
					'advertisementtype' => $advertisementtype
        ]);
    }

    
   
   
    
	public function actionCreate()
	{
		 $model = new \app\models\Advertisements;
		 /*if($model->load(Yii::$app->request->post()))
		 {
			 echo "<pre>";print_r($model);echo "</pre>";die;
		 }*/
		 
		 
		 
		 
		 if ($_POST) {
			//print_r($_POST['Advertisements']['type']);die;
			 //$bannerimg = UploadedFile::getInstance($model, 'banner');
			 if($_FILES['Advertisements']['name'] != '')
		{
		$banner_image = $_FILES['Advertisements'];
		
		//print_r($banner_image);die;
		$target_dir = "images/";
		 $file_tmp =$banner_image['tmp_name']['banner'];
		 $file_name =$banner_image['name']['banner'];
$target_file = $target_dir . basename($banner_image["name"]['banner']);
move_uploaded_file($file_tmp,$target_dir.$file_name);



$model->banner = "images/".$banner_image['name']['banner'];
$model->link = $_POST['Advertisements']['link'];
//$model->type = $_POST['Advertisements']['type'];
$model->save(false);
$insertid =  $model->id;
$modeltype = new \app\models\AdvertisementUserType;
$modeltype->advetisement_id = $insertid;
if(!empty($_POST['Advertisements']['type']))
{
if(in_array("o", $_POST['Advertisements']['type']))
{
	$modeltype->owner = 1;
}
else{
	$modeltype->owner = 0;
}
if(in_array("t", $_POST['Advertisements']['type']))
{
	$modeltype->tenant = 1;
}
else{
	$modeltype->tenant = 0;
}
if(in_array("a", $_POST['Advertisements']['type'])){
	$modeltype->advisor = 1;
}
else{
	$modeltype->advisor = 0;
}
}
else{
	$modeltype->owner = 0;
	$modeltype->tenant = 0;
	$modeltype->advisor = 0;
}
$modeltype->save(false);
		}
		Yii::$app->getSession()->setFlash(
                            'success', 'Your have successfully created the advertisement.'
                    );
					return $this->redirect(['index']);
		 }
		return $this->render('create', [
                'model' => $model,
            ]);
	}

	
	 public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
	
	protected function findModel($id) {
		//echo $id;die;
        if (($model = Advertisements::findOne($id)) !== null) {
			//print_r($model);die;
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	 public function actionUpdate($id) {
        $model = $this->findModel($id);
		$previousbanner = $model->banner;
		//echo $previousbanner;die;
       
            $advertisementtype = \app\models\AdvertisementUserType::find()->where(['advetisement_id' => $id])->one();
			 
        

        if ($_POST) {
			//echo "<pre>";print_r($_FILES['Advertisements']);echo "</pre>";die;
            if($_FILES['Advertisements']['name']['banner'] != '')
		{
			//echo "not empty";die;
		$banner_image = $_FILES['Advertisements'];
		
		//print_r($banner_image);die;
		$target_dir = "images/";
		 $file_tmp =$banner_image['tmp_name']['banner'];
		 $file_name =$banner_image['name']['banner'];
$target_file = $target_dir . basename($banner_image["name"]['banner']);
move_uploaded_file($file_tmp,$target_dir.$file_name);



$model->banner = "images/".$banner_image['name']['banner'];
		}
		else{
			//echo "empty";die;
		$model->banner = $previousbanner;
		}
$model->link = $_POST['Advertisements']['link'];
//$model->type = $_POST['Advertisements']['type'];
$model->save(false);
//$modeltype = new \app\models\AdvertisementUserType;
$advertisementtype->owner = 0;
	$advertisementtype->tenant = 0;
	$advertisementtype->advisor = 0;
if(!empty($_POST['Advertisements']['type']))
{
	$typearr = $_POST['Advertisements']['type'];
	$countarr = count($typearr);
	for ($x = 0; $x < $countarr; $x++) {
    if($typearr[$x] == 'o')
	{
	$advertisementtype->owner = 1;	
	}
	if($typearr[$x] == 't')
	{
	$advertisementtype->tenant = 1;	
	}
	if($typearr[$x] == 'a')
	{
	$advertisementtype->advisor = 1;	
	}
} 
}

$advertisementtype->save(false);
		Yii::$app->getSession()->setFlash(
                            'success', 'Your have successfully updated the advertisement.'
                    );
					return $this->redirect(['index']);
        } else {
            // echo  '<pre>';print_r($model); 
            // echo  '<pre>';print_r($advisorPhoneModel); die;
            return $this->render('update', [
                        'model' => $model, 'advertisementtype' => $advertisementtype
            ]);
        }
    }
	
	public function actionAjaxstatus($id,$status) {

       
        $model = $this->findModel($id);

        if (isset($model) && !empty($model)) {

            if ($status == 1) {

                $model->status = 0;
                $model->save(false);
                $test = "Disabled";
				$dataProvider = new ActiveDataProvider([
            'query' => Advertisements::find(),
        ]);

        return $this->redirect('index');
            } else {

                $model->status = 1;
                $model->save(false);
                $test = "Enabled";
				$dataProvider = new ActiveDataProvider([
            'query' => Advertisements::find(),
        ]);

        return $this->redirect('index');
            }
        } else {
            $test = "Failed";
			$dataProvider = new ActiveDataProvider([
            'query' => Advertisements::find(),
        ]);

        return $this->redirect('index');
        }
        
    }
	
	

}
