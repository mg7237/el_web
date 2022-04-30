<?php

namespace app\components;

use Yii;

/**
 * Implementation for REST API Authentication using basic auth
 */

class Role extends \yii\web\User {
    
    public function getOpRoleCode ($id) {
        $model = \app\models\OperationsProfile::findOne(['operations_id' => $id]);
        if ($model)
            return $model->role_code;
        else
            return $model;
    }

    public function getOpRoleValue ($id) {
        $model = \app\models\OperationsProfile::findOne(['operations_id' => $id]);
        if ($model)
            return $model->role_value;
        else
            return $model;
    }

    public function getSalesRoleCode ($id) {
        $model = \app\models\SalesProfile::findOne(['sale_id' => $id]);
        if ($model)
            return $model->role_code;
        else
            return $model;
    }

    public function getSalesRoleValue ($id) {
        $model = \app\models\SalesProfile::findOne(['sale_id' => $id]);
        if ($model)
            return $model->role_value;
        else
            return $model;
    }

    public function getRoleName ($id) {
        $model = \app\models\Roles::findOne($id);
        if ($model)
            return $model->role_name;
        else
            return $model;
    }

    public function getRoleId ($id) {
        $model = \app\models\Roles::find()->where(['role_code' => $id])->one();
        if ($model)
            return $model->id;
        else
            return 0;
    }
    
    public function getBranchesByRole ($id, $userType) {
        $branches = [];
        if ($userType == 7) {
            $opsModel = \app\models\OperationsProfile::findOne(['operations_id' => $id]);
            if ($opsModel->role_code == 'OPEXE' && $opsModel->role_value == 'ELHQ') {
                $branches = \app\models\Branches::find()->where('branch_code != :branch_code', ['branch_code' => 'NA'])->all();
            } else if ($opsModel->role_code == 'OPSTMG') {
                $branches = \app\models\Branches::find()->where('branch_code != :branch_code AND state_code = :state_code', ['branch_code' => 'NA', 'state_code' => $opsModel->role_value])->all();
            } else if ($opsModel->role_code == 'OPCTMG') {
                $branches = \app\models\Branches::find()->where('branch_code != :branch_code AND city_code = :city_code', ['branch_code' => 'NA', 'city_code' => $opsModel->role_value])->all();
            } else if ($opsModel->role_code == 'OPBRMG') {
                $branches = \app\models\Branches::find()->where('branch_code = :branch_code', ['branch_code' => $opsModel->role_value])->all();
            } else if ($opsModel->role_code == 'OPEXE') {
                $branches = \app\models\Branches::find()->where('branch_code = :branch_code', ['branch_code' => $opsModel->role_value])->all();
            }
        } else if ($userType == 6) {
            $saleModel = \app\models\SalesProfile::findOne(['sale_id' => $id]);
            if ($saleModel->role_code == 'SLEXE' && $saleModel->role_value == 'ELHQ') {
                $branches = \app\models\Branches::find()->where('branch_code != :branch_code', ['branch_code' => 'NA'])->all();
            } else if ($saleModel->role_code == 'SLSTMG') {
                $branches = \app\models\Branches::find()->where('branch_code != :branch_code AND state_code = :state_code', ['branch_code' => 'NA', 'state_code' => $saleModel->role_value])->all();
            } else if ($saleModel->role_code == 'SLCTMG') {
                $branches = \app\models\Branches::find()->where('branch_code != :branch_code AND city_code = :city_code', ['branch_code' => 'NA', 'city_code' => $saleModel->role_value])->all();
            } else if ($saleModel->role_code == 'SLBRMG') {
                $branches = \app\models\Branches::find()->where('branch_code = :branch_code', ['branch_code' => $saleModel->role_value])->all();
            } else if ($saleModel->role_code == 'SLEXE') {
                $branches = \app\models\Branches::find()->where('branch_code = :branch_code', ['branch_code' => $saleModel->role_value])->all();
            }
        }
        
        return $branches;
    }
}