<?php

namespace app\controllers\api\v2;

use Yii;
use yii\rest\ActiveController;
use app\models\Users;


class OtpCheckException extends \Exception {}

/**
 * UsersController implements the CRUD actions for Users model.
 */

class ReferenceController extends ActiveController {
    public $modelClass = 'app\models\States';
    
    protected function verbs()
    {
        return [
            'statelist' => ['GET'],
            'prooftypes' => ['GET']
        ];
    }
    
    public function actionStatelist() {
        $data = \app\models\States::find()->where(['status' => 1])->orderBy("name")->all();
        $respArr = [];
        if (count($data) > 0) {
            
            $respArr['states'] = [];
            foreach ($data as $key => $val) {
                $respArr['states'][$key]['name'] = $val->name;
                $respArr['states'][$key]['code'] = $val->code;
            }
            $respArr['status'] = True;
            $respArr['message'] = 'Check states array for list of states';
        } else {
            $respArr['status'] = FALSE;
            $respArr['states'] = [];
            $respArr['message'] = 'No states found';
        }
        
        echo Yii::$app->userdata->restResponse($respArr);
    }
    
    public function actionProoftypes() {
        $data = \app\models\ProofType::find()->all();
        $respArr = [];
        if (count($data) > 0) {
            
            $respArr['proofTypes'] = [];
            foreach ($data as $key => $val) {
                $respArr['proofTypes'][$key]['name'] = $val->name;
                $respArr['proofTypes'][$key]['id'] = $val->id;
            }
            $respArr['status'] = True;
            $respArr['message'] = 'Check prrofTypes array for list of proof types';
        } else {
            $respArr['status'] = FALSE;
            $respArr['states'] = [];
            $respArr['message'] = 'No proof type found';
        }
        
        echo Yii::$app->userdata->restResponse($respArr);
    }
    
    public function actionPaymentmodes () {
        $data = \app\models\PaymentModeType::find()->where(['offline_mode' => 1])->all();
        $respArr = [];
        if (count($data) > 0) {
            
            $respArr['modes'] = [];
            foreach ($data as $key => $val) {
                $respArr['modes'][$key]['name'] = $val->name;
                $respArr['modes'][$key]['id'] = $val->id;
            }
            $respArr['status'] = True;
            $respArr['message'] = 'Found ' .count($data). ' payment modes.';
        } else {
            $respArr['status'] = FALSE;
            $respArr['states'] = [];
            $respArr['message'] = 'No payment mode found';
        }
        
        echo Yii::$app->userdata->restResponse($respArr);
    }
}