<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;

/**
 * This is the model class for table "maintainance_service_request".
 *
 * @property integer $id
 * @property integer $property_id
 * @property string $request_type
 * @property string $title
 * @property string $description
 * @property string $created_date
 * @property integer $status
 * @property string $attachment
 * @property double $charges
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $operated_by
 * @property string $updated_date
 */
class ServiceRequestAttachment extends \yii\db\ActiveRecord {
    
    public $imageFiles;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'service_attachment';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            // [['attachment'], 'file', 'skipOnEmpty' => true, 'extensions' => 'ppt, rtf, wpd, wp, wp7, txt, doc, docx, pdf, png, jpg, gif, bmp', 'maxFiles' => 0]
            [['attachment'], 'file', 'skipOnEmpty' => true, 'maxFiles' => 0]
        ];
    }
    
    public function upload($lastInsert) {
        foreach ($this->imageFiles as $file) {
            $name = date('ymdHis') . str_replace(' ', '', $file->name);
            $file->saveAs('uploads/requests/' . $name);
            $command = Yii::$app->db->createCommand();
            $command->insert('service_attachment',array(
                'attachment' => 'uploads/requests/' . $name,
                'service_id' => $lastInsert
            ))->execute();
        }
        return true;
    }
    
    public function getAllRequest ($id) {
        $result = Yii::$app->db->createCommand(
                'SELECT sr.id, sr.property_id, sr.title, sr.description, sr.created_date, reqst.name as status, r.name as request_type FROM service_request sr '
                . 'LEFT JOIN request_status reqst ON reqst.id = sr.status LEFT JOIN request r ON '
                . 'sr.request_type = r.id WHERE sr.client_id = '.$id.' ORDER BY sr.created_date DESC '
        )->queryAll();
        
        return $result;
    }
    
    public function getAllRequestByProId ($id, $proId) {
        $result = Yii::$app->db->createCommand(
                'SELECT sr.id, sr.property_id, sr.title, sr.description, sr.created_date, reqst.name as status, r.name as request_type FROM service_request sr '
                . 'LEFT JOIN request_status reqst ON reqst.id = sr.status LEFT JOIN request r ON '
                . 'sr.request_type = r.id WHERE sr.client_id = '.$id.' AND sr.property_id = "'.$proId.'" ORDER BY sr.created_date DESC '
        )->queryAll();
        
        return $result;
    }
    
    public function getAllRequestByOperator ($id) {
        $userType = Yii::$app->user->identity->user_type;
        $where = '';
        $where2 = '';
        if ($userType == 7) {
            $opsProfileModel = \app\models\OperationsProfile::findOne(['operations_id' => Yii::$app->user->id]);
            $currentRoleCode = $opsProfileModel->role_code;
            $currentRoleValue = $opsProfileModel->role_value;
            
            if ($currentRoleValue == 'ELHQ') {
                $where = " 1 ";
                $where2 = " 1 ";
            } else if ($currentRoleCode == 'OPSTMG') {
                $where = ' lo.lead_state = "'.$currentRoleValue.'" ';
                $where2 = ' lt.lead_state = "'.$currentRoleValue.'" ';
            } else if ($currentRoleCode == 'OPCTMG') {
                $where = ' lo.lead_city = "'.$currentRoleValue.'" ';
                $where2 = ' lt.lead_city = "'.$currentRoleValue.'" ';
            } else if ($currentRoleCode == 'OPBRMG') {
                $where = ' lo.branch_code = "'.$currentRoleValue.'" ';
                $where2 = ' lt.branch_code = "'.$currentRoleValue.'" ';
            } else if ($currentRoleCode == 'OPEXE') {
                $where = ' sr.operated_by = '.$id.' ';
                $where2 = ' sr.operated_by = '.$id.' ';
            }
        } else {
            $opsProfileModel = \app\models\SalesProfile::findOne(['sale_id' => Yii::$app->user->id]);
            $currentRoleCode = $opsProfileModel->role_code;
            $currentRoleValue = $opsProfileModel->role_value;
            
            if ($currentRoleValue == 'ELHQ') {
                $where = " 1 ";
                $where2 = " 1 ";
            } else if ($currentRoleCode == 'SLSTMG') {
                $where = ' lo.lead_state = "'.$currentRoleValue.'" ';
                $where2 = ' lt.lead_state = "'.$currentRoleValue.'" ';
            } else if ($currentRoleCode == 'SLCTMG') {
                $where = ' lo.lead_city = "'.$currentRoleValue.'" ';
                $where2 = ' lt.lead_city = "'.$currentRoleValue.'" ';
            } else if ($currentRoleCode == 'SLBRMG') {
                $where = ' lo.branch_code = "'.$currentRoleValue.'" ';
                $where2 = ' lt.branch_code = "'.$currentRoleValue.'" ';
            } else if ($currentRoleCode == 'SLEXE') {
                $where = ' sr.operated_by = '.$id.' ';
                $where2 = ' sr.operated_by = '.$id.' ';
            }
        }
        
        $result = Yii::$app->db->createCommand(
                'SELECT '
                . 'sr.id, sr.property_id, sr.client_type, sr.client_id, sr.operated_by, sr.title, sr.description, '
                . 'sr.created_date as created_date, reqst.name as status, r.name as request_type FROM service_request sr '
                . 'INNER JOIN owner_profile op ON op.owner_id = sr.client_id '
                . 'INNER JOIN users u ON u.id = sr.client_id '
                . 'INNER JOIN leads_owner lo ON lo.email_id = u.login_id '
                . 'INNER JOIN operations_profile ops ON ops.operations_id = op.operation_id '
                . 'INNER JOIN sales_profile sp ON sp.sale_id = op.sales_id '
                . 'LEFT JOIN request_status reqst ON reqst.id = sr.status '
                . 'LEFT JOIN request r ON sr.request_type = r.id '
                . 'WHERE  '.$where.' '
                . ''
                . ''
                . 'UNION ALL '
                . ''
                . ''
                . 'SELECT '
                . 'sr.id, sr.property_id, sr.client_type, sr.client_id, sr.operated_by, sr.title, sr.description, '
                . 'sr.created_date as created_date, reqst.name as status, r.name as request_type FROM service_request sr '
                . 'INNER JOIN tenant_profile tp ON tp.tenant_id = sr.client_id '
                . 'INNER JOIN users u ON u.id = sr.client_id '
                . 'INNER JOIN leads_tenant lt ON lt.email_id = u.login_id '
                . 'INNER JOIN operations_profile ops ON ops.operations_id = tp.operation_id '
                . 'INNER JOIN sales_profile sp ON sp.sale_id = tp.sales_id '
                . 'LEFT JOIN request_status reqst ON reqst.id = sr.status '
                . 'LEFT JOIN request r ON sr.request_type = r.id '
                . 'WHERE  '.$where2.' '
                . ''
                . ''
                . 'ORDER BY created_date DESC '
        )->queryAll();
        
        return $result;
    }
}