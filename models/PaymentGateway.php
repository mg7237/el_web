<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;

use Yii;

class PaymentGateway extends \yii\db\ActiveRecord {
    
    public static function tableName() {
        return 'pgi_interface';
    }
    
}