<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "operations_profile".
 *
 * @property integer $id
 * @property integer $operations_id
 * @property string $emer_contact
 * @property string $address_line_1
 * @property string $address_line_2
 * @property integer $state
 * @property integer $city
 * @property integer $pincode
 * @property string $phone
 */
class OperationsData extends \yii\db\ActiveRecord {
    // public function osr(){
    // 	return "hello";
    // }
}

?>