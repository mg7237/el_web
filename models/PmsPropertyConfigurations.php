<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pms_property_configurations".
 *
 * @property integer $id
 * @property double $lease_percent
 * @property double $sub_lease_percent
 * @property double $penalty_percent
 * @property integer $min_penalty
 * @property integer $created_by
 * @property integer $updated_by
 * @property string $created_date
 * @property string $updated_date
 */
class PmsPropertyConfigurations extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'pms_property_configurations';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['lease_percent', 'sub_lease_percent', 'penalty_percent', 'min_penalty'], 'required'],
            [['lease_percent', 'sub_lease_percent', 'penalty_percent'], 'number'],
            [['min_penalty', 'created_by', 'updated_by'], 'integer'],
            [['created_date', 'updated_date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'lease_percent' => 'Lease percent',
            'sub_lease_percent' => 'Sub lease percent',
            'penalty_percent' => 'Penalty percent',
            'min_penalty' => 'Min penalty',
            'created_by' => 'Created by',
            'updated_by' => 'Updated by',
            'created_date' => 'Created date',
            'updated_date' => 'Updated date',
        ];
    }

}
