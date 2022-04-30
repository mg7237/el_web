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
class Branches extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'branches';
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
