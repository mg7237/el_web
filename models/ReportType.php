<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cities".
 *
 * @property integer $id
 * @property integer $state_id
 * @property string $city_name
 */
class ReportType extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'report_type';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            
        ];
    }

}
