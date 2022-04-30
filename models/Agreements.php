<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "agreements".
 *
 * @property integer $id
 * @property string $email_id
 * @property integer $property_id
 * @property integer $rent
 * @property integer $rent_date
 * @property integer $manteinace
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deposit
 * @property string $min_stay
 * @property string $notice_period
 * @property string $create_date
 * @property string $late_penalty_percent
 * @property string $min_penalty
 * @property string $update_time
 */
class Agreements extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'agreements';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['rent_date', 'min_stay', 'notice_period', 'late_penalty_percent', 'min_penalty'], 'required'],
            [['rent_date', 'min_stay', 'notice_period', 'late_penalty_percent', 'min_penalty', 'created_by', 'updated_by'], 'integer'],
            [['create_date', 'update_time'], 'safe'],
            [['email_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'email_id' => 'Email ID',
            'property_id' => 'Property ID',
            'rent' => 'Rent',
            'rent_date' => 'Rent date',
            'manteinace' => 'Manteinace',
            'created_by' => 'Created by',
            'updated_by' => 'Updated by',
            'deposit' => 'Deposit',
            'min_stay' => 'Minimum stay (in months)',
            'notice_period' => 'Notice period  (in months)',
            'create_date' => 'Create date',
            'late_penalty_percent' => 'Late penalty percent',
            'min_penalty' => 'Minimum penalty (in rupees)',
            'update_time' => 'Update time',
        ];
    }

    public function beforeSave($insert) {
        if (isset($this->rent_date)) {
            $this->rent_date = date('Y-m-d', strtotime($this->rent_date));
        }



        return true;
    }

    public function afterFind() {
        if (isset($this->rent_date)) {
            $this->rent_date = date('d-M-Y', strtotime($this->rent_date));
        }

        return true;
    }

    public function afterSave($insert, $changed) {
        if (isset($this->rent_date)) {
            $this->rent_date = date('d-M-Y', strtotime($this->rent_date));
        }

        return true;
    }

}
