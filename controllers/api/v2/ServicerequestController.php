<?php

namespace app\controllers\api\v2;

use Yii;
use yii\rest\ActiveController;
use app\models\ServiceRequest;
use app\models\ServiceRequestAttachment;


class SRException extends \Exception {}

/**
 * ServiceController implements the CRUD actions for Service Request model.
 */

class ServicerequestController extends ActiveController {
    public $modelClass = 'app\models\ServiceRequest';
    
    protected function verbs()
    {
        return [
            'list' => ['GET']
        ];
    }
    
    public function beforeAction($action) {
        $requireAuth = [
            'typeslist', 'statuslist', 'srlist', 'srdetail', 'createrequest', 'propertylist', 'deleterequest', 'deleteattachment', 'createattachment', 'createcomment', 'updaterequest'
        ];
        
        if (in_array($action->id, $requireAuth)) {
            Yii::$app->restbasicauth->checkBaseAuth();
        }
        
        return parent::beforeAction($action);
    }

    public function actionTypeslist() {
        $respArr = [];
        $typesList = [];
        $ServiceTypeModel = \app\models\RequestType::find()->all();
        if (count($ServiceTypeModel) <> 0) {
            $i=0;
            foreach ($ServiceTypeModel as $key => $row) {
                $typesList[$i]["id"] = $row->id;
                $typesList[$i]["name"] = $row->name;
                $i++;
            }
            $respArr["status"] = True;
            $respArr["message"] = "Success";
        } else {
            $respArr["status"] = FALSE;
            $respArr["message"] = "Oops something went wrong; request type list not available";
        }
        $respArr["typesList"] = $typesList;
        
        echo Yii::$app->userdata->restResponse($respArr);
    }
    
    public function actionStatuslist() {
        $respArr = [];
        $statusList = [];
        $ServiceStatusModel = \app\models\RequestStatus::find()->all();
        if (count($ServiceStatusModel) <> 0) {
            $i=0;
            foreach ($ServiceStatusModel as $key => $row) {
                $statusList[$i]["id"] = $row->id;
                $statusList[$i]["name"] = $row->name;
                $i++;
            }
            $respArr["status"] = TRUE;
            $respArr["message"] = "Success";
        } else {
            $respArr["status"] = FALSE;
            $respArr["message"] = "Oops something went wrong; status list not available";
        }
        $respArr["statusList"] = $statusList;
        
        echo Yii::$app->userdata->restResponse($respArr);
    }

    
    
    public function actionSrlist() {
        $clientId = Yii::$app->restbasicauth->getUserId();
        $respArr = [];
        $serviceRequests = [];
 
        $ServiceRequestModel = \app\models\ServiceRequest::find()->where(['client_id' => $clientId])->orderBy("id desc")->all();
                
        if (count($ServiceRequestModel) > 0) {
            $i = 0;
            foreach ($ServiceRequestModel as $key => $row) {
                $serviceRequests[$i]['srId'] = $row->id;
                $serviceRequests[$i]['title'] = $row->title;
                $serviceRequests[$i]['updateDate'] = $row->updated_date;
                $serviceRequests[$i]['status'] = \app\models\RequestStatus::find('name')->where(['id' => $row->status] )->one()->name;
                $serviceRequests[$i]['type'] = \app\models\RequestType::find('name')->where(['id' => $row->request_type])->one()->name;
                
                $serviceRequests[$i]['supportName'] =Yii::$app->userdata->getFullNameById($row->operated_by);
                
                $operationsModel = \app\models\OperationsProfile::find()->where(['operations_id' => $row->operated_by])->one();
                if (count($operationsModel) > 0) {
                    $serviceRequests[$i]['supportEmail'] = $operationsModel->email;
                    $serviceRequests[$i]['supportContact'] = $operationsModel->phone;
                } else {
                    $serviceRequests[$i]['supportEmail'] = "";
                    $serviceRequests[$i]['supportContact'] = "";
                }
                                
                $i++;
            }
            $respArr['status'] = TRUE;
            $respArr['message'] = "";
            $respArr['srList'] = $serviceRequests;
        } else {
            $respArr['status'] = TRUE;
            $respArr['message'] = "No Service Request yet; please use + icon to create a new request";
            $respArr['srList'] = $serviceRequests;
       
        }
        
        echo Yii::$app->userdata->restResponse($respArr);
        
    }
    
    public function actionSrdetail($SRId) {
        $respArr = [];
        $ServiceRequestModel = \app\models\ServiceRequest::find()->where(['id' => $SRId])->one();
        if (count($ServiceRequestModel) > 0) {
            $respArr['title'] = $ServiceRequestModel->title;
            $respArr['description'] = $ServiceRequestModel->description;
            $respArr['requestType'] = $ServiceRequestModel->request_type;
            $respArr['requestStatus'] = $ServiceRequestModel->status;
            $respArr['title'] = $ServiceRequestModel->title;
            $respArr['supportName'] =Yii::$app->userdata->getFullNameById($ServiceRequestModel->operated_by);
            $operationsModel = \app\models\OperationsProfile::find()->where(['operations_id' => $ServiceRequestModel->operated_by])->one();
            if (count($operationsModel) > 0) {
                $respArr['supportEmail'] = $operationsModel->email;
                $respArr['supportContact'] = $operationsModel->phone;
            } else {
                $respArr['supportEmail'] = "";
                $respArr['supportContact'] = "";
            }
            
            $attachments = [];
            $serviceAttachmentModel = \app\models\ServiceRequestAttachment::find()->where(['service_id' => $SRId])->all();
            
            if (count($serviceAttachmentModel) > 0 ) {
                $i = 0;
                foreach ($serviceAttachmentModel as $key => $row) {
                    $attachments[$i]['id'] = $row->id;
                    $attachments[$i]['attachmentURL'] = \yii\helpers\Url::home(TRUE) . $row->attachment;
                    $i++;
                }
             }
            $respArr['attachments'] = $attachments;
   
            $comments = [];
            $serviceCommentsModel = \app\models\ServiceConversation::find()->where(['service_request_id' => $SRId])->orderBy("id desc")->all();
            
            if (count($serviceCommentsModel) > 0 ) {
                $i = 0;
                foreach ($serviceCommentsModel as $key => $row) {
                    $comments[$i]['id'] = $row->id;
                    $comments[$i]['comment'] = $row->message;
                    $comments[$i]['comment_by'] = Yii::$app->userdata->getFullNameById($row->user_id);
                    $comments[$i]['createdDate'] = $row->created_datetime;
                    
                    $i++;
                }
             }
             $respArr['comments'] = $comments;
                 
            
        } else {
            $respArr['status'] = FALSE;
            $respArr['Message'] = "Invalid Service Request Id";
            
        }
        
        echo Yii::$app->userdata->restResponse($respArr);
       
    }
    
    public function actionCreaterequest () {
        $respArr = [];
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $input = Yii::$app->request->post();
            $requestTitle = @$input['requestTitle'];
            $requestDescription = @$input['requestDescription'];
            $requestStatus = @$input['requestStatus'];
            $requestType = @$input['requestType'];
            $userId = Yii::$app->restbasicauth->getUserId();
            $propertyId = @$input['propertyId'];

            $srModel = new \app\models\ServiceRequest();
            
            $srModel->title =  $requestTitle;
            $srModel->description =  $requestDescription;
            $srModel->request_type =  $requestType;
            $srModel->status =  $requestStatus;
            $srModel->property_id = $propertyId;
            $srModel->client_id = $userId;
            $srModel->client_type = Yii::$app->restbasicauth->getUserType();
            $srModel->status = $requestStatus;
            $srModel->created_by = $userId;
            if ($srModel->client_type == 3) {
                $srModel->operated_by = \app\models\TenantProfile::find('operation_id')->where(['tenant_id' => $userId])->one()->operation_id;
            } elseif ($srModel->client_type == 4) {
                $srModel->operated_by = \app\models\OwnerProfile::find('operation_id')->where(['owner_id' => $userId])->one()->operation_id;          
            }
            $srModel->created_date = Date("Y-m-d H:m:s");
            
            if (!$srModel->save(false)) {
                throw new \Exception('Exception while saving service request, please try again later');
            }
            
            if (!empty($_FILES['attachments']['name'])) {
                foreach ($_FILES['attachments']['name'] as $key => $values) {
                    $attachmentTypes = array('image/jpeg', 'image/jpg', 'image/png');
                    $attachmentName = $values;
                    $attachmentType = $_FILES['attachments']['type'][$key];
                    $attachmentTempName = $_FILES['attachments']['tmp_name'][$key];
                    $attachmentError = $_FILES['attachments']['error'][$key];
                    $attachmentSize = $_FILES['attachments']['size'][$key];

                    if ($attachmentSize < 1) {
                        throw new \Exception('The attached file seems to be corrupted');
                    }

                    /*if (!in_array($profilePicType, $imageTypes)) {
                        throw new \Exception('Only PNG and JPEG files are allowed');
                    }*/

                    if ($attachmentError != 0) {
                        throw new \Exception('Something wrong with file, please try again attaching it.');
                    }

                    /*if ($attachmentError == 1) {
                        throw new \Exception('File size exceeded, limit is ' . ini_get('upload_max_filesize') . 'B');
                    }*/

                    /*if ($attachmentError == 2) {
                        throw new \Exception('File size exceeded, limit is ' . ini_get('upload_max_filesize') . 'B');
                    }*/

                    /*if ($attachmentError == 3) {
                        throw new \Exception('File was uploaded partially');
                    }*/
                    
                    $targetDir = "uploads/requests/";
                    $targetFile = $targetDir.time('YmdHisu').str_replace(' ', '-', basename($attachmentName));
                    $uploadStatus = move_uploaded_file($attachmentTempName, $targetFile);
                    
                    if ($uploadStatus) {
                        $srAttachmentModel = new \app\models\ServiceRequestAttachment();
                        $srAttachmentModel->attachment = $targetFile;
                        $srAttachmentModel->service_id = $srModel->id;
                        $srAttachmentModel->save(false);
                    }
                }
            }
            
            $transaction->commit();
            
            $respArr['srId'] = $srModel->id;
            $respArr['status'] = TRUE;
            $respArr['message'] = "Service Request Created";
 
        } catch (\Exception $ex) {
            $transaction->rollBack();
            $respArr['status'] = FALSE;
            $respArr['message'] = $ex->getMessage();
        }
     
        echo Yii::$app->userdata->restResponse($respArr);
              
    }
    
    public function actionUpdaterequest ($SRId) {
        $respArr = [];
        $transaction = Yii::$app->db->beginTransaction();
        $input = Yii::$app->userdata->restRequest();
        try {
            $requestTitle = @$input->requestTitle;
            $requestDescription = @$input->requestDescription;
            $requestStatus = @$input->requestStatus;
            $requestType = @$input->requestType;
            $userId = Yii::$app->restbasicauth->getUserId();

            $srModel = \app\models\ServiceRequest::find()->where(['id' => $SRId])->one();
            if (count($srModel) == 0) {
                throw new \Exception('Invalid parameter sent');
            }
            
            $srModel->title =  $requestTitle;
            $srModel->description =  $requestDescription;
            $srModel->request_type =  $requestType;
            $srModel->status =  $requestStatus;
            $srModel->client_id = $userId;
            $srModel->client_type = Yii::$app->restbasicauth->getUserType();
            $srModel->updated_by = $userId;
            $srModel->updated_date = Date("Y-m-d H:m:s");
            
            if (!$srModel->save(false)) {
                throw new \Exception('Exception while saving service request, please try again later');
            }
            
            $transaction->commit();
            $respArr['status'] = TRUE;
            $respArr['message'] = "Service Request Updated";
 
        } catch (\Exception $ex) {
            $transaction->rollBack();
            $respArr['status'] = FALSE;
            $respArr['message'] = $ex->getMessage();
        }
     
        echo Yii::$app->userdata->restResponse($respArr);
              
    }
    
    
    public function actionPropertylist() {
        $userId = Yii::$app->restbasicauth->getUserId();
        $respArr = [];
        $propertyList = [];
        $userType = Yii::$app->restbasicauth->getUserType();
        
        if ($userType == 3) {
            $strSQL = "Select a.id,a.property_name from properties a join tenant_agreements b on a.id = b.property_id where b.tenant_id = " . $userId;
        } elseif ($userType == 4) {
            $strSQL = "Select id, property_name from properties where owner_id = " . $userId;
        }
        
        $recordSet = Yii::$app->db->createCommand($strSQL)->queryAll();
        
        if (count($recordSet) > 0) {
            $i=0;
            foreach ($recordSet as $key => $value) {
                
                $propertyList[$i]["id"] = $value['id'];
                $propertyList[$i]["name"] = $value['property_name'];
                $i++;
            }
            
            $respArr["status"] = True;
            $respArr["message"] = "Success";
                
        } else {
            $respArr["status"] = False;
            $respArr["message"] = "Oops something went wrong; property list not available";
        }
        $respArr["propertyList"] = $propertyList;
        
        echo Yii::$app->userdata->restResponse($respArr);
    }
    
    public function actionDeleterequest($SRId) {
        $respArr = [];
        $transaction = Yii::$app->db->beginTransaction();
        try {
            //$sql = "delete from service_conversation where service_request_id = " . $SRId;
            $delConversation = \app\models\ServiceConversation::deleteAll('service_request_id = '.$SRId);
            $delAttachment = \app\models\ServiceRequestAttachment::deleteAll('service_id = '.$SRId);
            $delServiceRequest = \app\models\ServiceRequest::deleteAll('id = '.$SRId);
  
            $respArr["status"] = True;
            $respArr["message"] = "Success";                          
            $transaction->commit();
        } catch (\Exception $ex) {
            $transaction->rollBack();
            $respArr["status"] = False;
            $respArr["message"] = "Exception:" . $ex->getMessage();
            
        }
        echo Yii::$app->userdata->restResponse($respArr);

    }

    public function actionDeleteattachment($attachmentId) {
        $respArr = [];
        $transaction = Yii::$app->db->beginTransaction();
        try {
 
            $delAttachment = \app\models\ServiceRequestAttachment::deleteAll('id = '.$attachmentId);
  
            $respArr["status"] = True;
            $respArr["message"] = "Success";                          
            $transaction->commit();
        } catch (\Exception $ex) {
            $transaction->rollBack();
            $respArr["status"] = False;
            $respArr["message"] = "Exception:" . $ex->getMessage();
            
        }
        echo Yii::$app->userdata->restResponse($respArr);

    }

    public function actionCreateattachment ($SRId) {
        $respArr = [];
        if (!empty($_FILES['attachment'])) {
            $path = 'uploads/requests/';
            $fileName = time('YmdHisu').str_replace(' ', '-', $_FILES['attachment']['name']);
            $tempName = $_FILES['attachment']['tmp_name'];
            $mimeType = $_FILES['attachment']['type'];
            $error = $_FILES['attachment']['error'];
            $size = $_FILES['attachment']['size'];
            $absoluteFile = $path.$fileName;
            
            if ($size > 0 && $error == 0) {
                $uploadStatus = move_uploaded_file($tempName, $absoluteFile);
                if ($uploadStatus) {
                    $srRequestModel = \app\models\ServiceRequest::find()->where(['id' => $SRId])->one();
                    $srAttachmentModel = new \app\models\ServiceRequestAttachment();
                    if (count($srRequestModel) > 0) {
                        $srAttachmentModel->attachment = $absoluteFile;
                        $srAttachmentModel->service_id = $SRId;
                        $srAttachmentModel->save(false);
                        $respArr['status'] = TRUE;
                        $respArr['message'] = "Attachment Saved";
                        $respArr['attachmentId'] = $srAttachmentModel->id;
                    } else {
                        $respArr['status'] = FALSE;
                        $respArr['message'] = "Invalid path parameter";
                        $respArr['attachmentId'] = '';
                    }
                }
            } else {
                $respArr['status'] = FALSE;
                $respArr['message'] = "Seems invalid or corrupt file";
                $respArr['attachmentId'] = '';
            }
            
        } else {
            $respArr['status'] = FALSE;
            $respArr['message'] = "No file attached";
            $respArr['attachmentId'] = '';
        }
        
        echo Yii::$app->userdata->restResponse($respArr);

    }
    
    public function actionCreatecomment ($SRId) {
        $respArr = [];
        $userId = Yii::$app->restbasicauth->getUserId();
        $userModel = \app\models\Users::find()->where(['id' => $userId])->one();

        if (count($userModel)>0) {
            $userType = $userModel->user_type;


            $input = Yii::$app->request->post();
            $requestComment = @$input['comment'];

            try {
                $srRequestModel = \app\models\ServiceRequest::find()->where(['id' => $SRId])->one();
                if (count($srRequestModel) > 0) {
                    $srCommentModel = new \app\models\ServiceConversation();

                    $srCommentModel->message = $requestComment;
                    $srCommentModel->service_request_id = $SRId;
                    $srCommentModel->user_id = $userId;
                    $srCommentModel->user_type = $userType;
                    $srCommentModel->created_datetime = Date("Y-m-d H:i:s");
                    $srCommentModel->save(FALSE);
                    $respArr['status'] = True;
                    $respArr['message'] = "Comment Added";

                } else {
                    $respArr['status'] = False;
                    $respArr['message'] = "Service Request Id not found";
                   }
                
            } catch (\Exception $ex) {
                    $respArr['status'] = False;
                    $respArr['message'] = $ex->getMessage();
                
            }
        
        } else {
            $respArr['status'] = False;
            $respArr['message'] = "Invalid User Id";

        }
        
        echo Yii::$app->userdata->restResponse($respArr);

    }

}