<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "property_listing".
 *
 * @property integer $id
 * @property integer $property_id
 * @property double $rent
 * @property double $deposit
 * @property double $token_amount
 * @property double $maintenance
 * @property string $maintenance_included
 * @property string $availability_from
 */
class SystemConfig extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'system_config';
    }
    
}
