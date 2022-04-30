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
class EmergencyProofs extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'emergency_id_proofs';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['user_id', 'created_by', 'updated_by', 'proof_type', 'proof_document_url', 'created_date', 'updated_date'], 'required'],
            [['user_id', 'created_by', 'updated_by'], 'integer'],
            [['created_date', 'updated_date'], 'safe'],
            [['proof_type', 'proof_document_url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'created_by' => 'Created by',
            'updated_by' => 'Updated by',
            'proof_type' => 'Proof type',
            'proof_document_url' => 'Proof document url',
            'created_date' => 'Created date',
            'updated_date' => 'Updated date',
        ];
    }

}
