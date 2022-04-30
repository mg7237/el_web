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
class ManagedBy extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'managed_by';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Name'
        ];
    }

}
