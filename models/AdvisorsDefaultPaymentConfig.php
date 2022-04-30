<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "advisors_payment_configuration".
 *
 * @property integer $id
 * @property integer $min
 * @property integer $max
 * @property integer $pay_type
 * @property integer $created_by
 * @property integer $updated_by
 * @property string $create_time
 * @property string $update_time
 */
class AdvisorsDefaultPaymentConfig extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'advisors_default_payment_config';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['min', 'max', 'advisor_fees', 'config_type'], 'required'],
            [['min', 'max', 'created_by', 'updated_by'], 'integer'],
            ['min', 'minCheck'],
            ['max', 'maxCheck'],
            [['create_time', 'update_time'], 'safe'],
        ];
    }

    public function minCheck($attribute, $params) {
        $model = AdvisorsDefaultPaymentConfig::find()->where(['config_type' => $this->config_type])->all();
        if ($model) {
            foreach ($model as $modelData) {
                if ($this->min >= $modelData->min && $this->min <= $modelData->max) {
                    if ($this->id != $modelData->id) {
                        $this->addError($attribute, Yii::t('app', '"' . $this->min . '" is already in a range'));
                        break;
                    }
                }
            }
        }
    }

    public function maxCheck($attribute, $params) {
        $model = AdvisorsDefaultPaymentConfig::find()->where(['config_type' => $this->config_type])->all();
        if ($model) {
            foreach ($model as $modelData) {
                if ($this->max >= $modelData->min && $this->max <= $modelData->max) {
                    if ($this->id != $modelData->id) {
                        $this->addError($attribute, Yii::t('app', '"' . $this->max . '" is already in a range'));
                        break;
                    }
                }
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'min' => 'Minimum',
            'max' => 'Maximum',
            'config_type' => 'Config type',
            'advisor_fees' => '% Fees',
            'created_by' => 'Created by',
            'updated_by' => 'Updated by',
            'create_time' => 'Create time',
            'update_time' => 'Update time',
        ];
    }

}
