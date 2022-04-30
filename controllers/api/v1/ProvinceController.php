<?php

namespace app\controllers\api\v1;

use Yii;
use yii\rest\ActiveController;
use app\models\Users;


class OtpCheckException extends \Exception {}

/**
 * UsersController implements the CRUD actions for Users model.
 */

class ProvinceController extends ActiveController {
    public $modelClass = 'app\models\States';
    
    protected function verbs()
    {
        return [
            'list' => ['GET']
        ];
    }
    
    public function actionList() {
        $data = \app\models\States::find()->where(['status' => 1])->all();
        $respArr = [];
        if (count($data) > 0) {
            $respArr['status'] = TRUE;
            $respArr['states'] = [];
            foreach ($data as $key => $val) {
                $respArr['states'][$key]['name'] = $val->name;
                $respArr['states'][$key]['code'] = $val->code;
            }
            $respArr['message'] = ['Check states array for list of states'];
        } else {
            $respArr['status'] = FALSE;
            $respArr['states'] = [];
            $respArr['message'] = 'No province found';
        }
        
        echo Yii::$app->userdata->restResponse($respArr);
    }
}