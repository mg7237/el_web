<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "property_images".
 *
 * @property integer $id
 * @property string $image_url
 * @property integer $property_id
 * @property string $image_upload_time
 */
class PropertyImages extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'property_images';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [[], 'required'],
            [['image_url'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'maxFiles' => 10],
            [['property_id'], 'integer'],
            [['image_upload_time'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'image_url' => 'Image Url',
            'property_id' => 'Property ID',
            'image_upload_time' => 'Image upload time',
        ];
    }

}
