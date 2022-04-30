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
class AdvertisementUserType extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'advetisement_user_type';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['adverstisement_id'], 'required']
        ];
    }


}
