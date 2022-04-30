<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "branches".
 *
 * @property integer $id
 * @property integer $city_id
 * @property string $name
 * @property integer $created_by
 * @property string $created_date
 * @property string $updated_date
 * @property integer $updated_by
 */
class OwnerPaymentSummary extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'owner_payment_summary';
    }

    /**
     * @inheritdoc
     */
   

}
