<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "advisor_agreements".
 *
 * @property integer $id
 * @property string $start_date
 * @property string $end_date
 * @property integer $created_by
 * @property integer $updated_by
 * @property string $created_date
 * @property string $updated_date
 * @property string $advisor_id
 * @property string $exit_confirm
 * @property string $agreement_doc
 */
class AdvisorAgreements extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'advisor_agreements';
    }

    /**
     * @inheritdoc 
     */
    public function rules() {
        return [
            [['start_date', 'end_date', 'advisor_id', 'agreement_doc'], 'required'],
            [['start_date', 'end_date', 'created_date', 'updated_date'], 'safe'],
            [['created_by', 'updated_by'], 'integer'],
            [['exit_confirm'], 'string'],
            [['advisor_id'], 'string', 'max' => 1000],
            [['agreement_doc'], 'string', 'max' => 10000],
            [['agreement_doc'], 'file', 'extensions' => 'docx,pdf,doc', 'skipOnEmpty' => true],
            ['end_date', 'compDate'],
            ['start_date', 'minDate'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'start_date' => 'Start date',
            'end_date' => 'End date',
            'created_by' => 'Created by',
            'updated_by' => 'Updated by',
            'created_date' => 'Created date',
            'updated_date' => 'Updated date',
            'advisor_id' => 'Advisor ID',
            'exit_confirm' => 'Exit confirm',
            'agreement_doc' => 'Agreement doc',
        ];
    }

    public function minDate($attribute, $params) {
        if ($this->start_date != '') {
            $currDate = strtotime(Date('Y-m-d'));
            $enteredDate = strtotime(Date('Y-m-d', strtotime($this->start_date)));
            if ($currDate > $enteredDate) {
                $this->addError($attribute, 'Date should be greater than current date');
            }
        }
    }

    public function compDate($attribute, $params) {
        $endDate = strtotime($this->end_date);
        $startDate = strtotime($this->start_date);
        if ($endDate < $startDate) {
            $this->addError($attribute, 'Date should be greater than start date');
        }
    }

    public function beforeSave($insert) {
        if (isset($this->start_date)) {
            $this->start_date = date('Y-m-d', strtotime($this->start_date));
        }
        if (isset($this->end_date)) {
            $this->end_date = date('Y-m-d', strtotime($this->end_date));
        }
        if (isset($this->agreement_doc)) {
            $this->agreement_doc = $this->agreement_doc;
        }

        return true;
    }

    public function afterFind() {
        if (isset($this->start_date)) {
            $this->start_date = date('d-M-Y', strtotime($this->start_date));
        }
        if (isset($this->end_date)) {
            $this->end_date = date('d-M-Y', strtotime($this->end_date));
        }
        if (isset($this->agreement_doc)) {
            $this->agreement_doc = $this->agreement_doc;
        }

        return true;
    }

    public function afterSave($insert, $changed) {
        if (isset($this->start_date)) {
            $this->start_date = date('d-M-Y', strtotime($this->start_date));
        }
        if (isset($this->end_date)) {
            $this->end_date = date('d-M-Y', strtotime($this->end_date));
        }
        if (isset($this->agreement_doc)) {
            $this->agreement_doc = $this->agreement_doc;
        }

        return true;
    }

}
