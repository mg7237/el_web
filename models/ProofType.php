<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ref_status".
 *
 * @property integer $id
 * @property string $name
 */
class ProofType extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'proof_types';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        // return [
        //     [['name'], 'required'],
        //     [['name'], 'string', 'max' => 1100],
        // ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    public static function Type($id) {
        $data = static::find()->select('name')->One($id);
        return $data->name;
    }

}
