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
class Advertisements extends \yii\db\ActiveRecord { 

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'advertisement_banners';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['banner', 'link','type'], 'required']
        ];
    }


}
