<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;

/**
 * This is the model class for table "maintainance_service_request".
 *
 * @property integer $id
 * @property integer $property_id
 * @property string $request_type
 * @property string $title
 * @property string $description
 * @property string $created_date
 * @property integer $status
 * @property string $attachment
 * @property double $charges
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $operated_by
 * @property string $updated_date
 */
class ServiceRequest extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'service_request';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['property_id', 'request_type', 'title', 'description', 'charges'], 'required'],
            [['property_id', 'status', 'created_by', 'updated_by', 'operated_by'], 'integer'],
            [['description'], 'string'],
            [['created_date', 'updated_date'], 'safe'],
            [['request_type'], 'string', 'max' => 255],
            [['title'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'property_id' => 'Property ID',
            'user_id' => 'User Id',
            'request_type' => 'Request type',
            'title' => 'Title',
            'description' => 'Description',
            'created_date' => 'Created date',
            'status' => 'Status',
            'created_by' => 'Created by',
            'updated_by' => 'Updated by',
            'operated_by' => 'Operated by',
            'updated_date' => 'Updated date',
        ];
    }

    public function beforeSave($insert) {
        if (isset($this->created_date)) {
            $this->created_date = date('Y-m-d H:i:s', strtotime($this->created_date));
        }

        return true;
    }

    public function afterFind() {
        if (isset($this->created_date)) {
            $this->created_date = date('d-M-Y', strtotime($this->created_date));
        }

        return true;
    }

    public function afterSave($insert, $changed) {
        if (isset($this->created_date)) {
            $this->created_date = date('d-M-Y', strtotime($this->created_date));
        }

        return true;
    }

    public function search($params) {
        $userAssignments = \app\models\Users::find()->where(['id' => Yii::$app->user->id])->one();
        if ($userAssignments) {

            $condition1 = '';
            $role = $userAssignments->role;
            switch ($role) {
                case 5:
                    $roles = \app\models\OperationsProfile::find()->select('region')->where(['operations_id' => Yii::$app->user->id])->one();
                    $properties = \app\models\Properties::find()->where(['region' => $roles->region])->all();
                    break;
                case 6:
                    $roles = \app\models\OperationsProfile::find()->select('region')->where(['operations_id' => Yii::$app->user->id])->one();
                    $properties = \app\models\Properties::find()->where(['region' => $roles->region])->all();
                    break;
                case 7:
                    $roles = \app\models\OperationsProfile::find()->select('city')->where(['operations_id' => Yii::$app->user->id])->one();
                    $properties = \app\models\Properties::find()->where(['city' => $roles->city])->all();
                    break;
                case 8:
                    $roles = \app\models\OperationsProfile::find()->select('state')->where(['operations_id' => Yii::$app->user->id])->one();
                    $properties = \app\models\Properties::find()->where(['state' => $roles->state])->all();
                    break;
            }
        } else {

            // $assinee1 = \app\models\OwnerProfile::find()->select('owner_id')->where(['operation_id'=>Yii::$app->user->id])->all();
            $listt = '';
            if (count($assinee1) != 0) {
                foreach ($assinee1 as $key => $value) {
                    if ($listt == '') {
                        $listt = $value->owner_id;
                    } else {
                        $listt .= ',' . $value->owner_id;
                    }
                }
                $properties = \app\models\Properties::find()->where(['owner_id IN (' . $listt . ')'])->all();
            } else {
                $properties = Array();
            }
        }

        if (count($properties) == 0) {
            $assinee = \app\models\PropertyServiceRequest::find([0]);
        } else {
            $properties_id = Array();
            foreach ($properties as $key => $value) {
                $properties_id[] = $value->id;
            }
        }
        $property_lisitng = implode(",", array_unique($properties_id));

        // $assinee = \app\models\PropertyServiceRequest::find()->where("maintainance_service_request.property_id IN ($property_lisitng)")
        //                       ->leftJoin('properties','properties.id = property_id');
        // ->leftJoin('owner_profile','users.id = owner_profile.owner_id') ;
        $assinee = \app\models\PropertyServiceRequest::find()
                ->leftJoin('properties', 'properties.id = property_id');
        $dataProvider = new ActiveDataProvider([
            'query' => $assinee,
            'sort' => false,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        if (!$this->load($params)) {
            return $dataProvider;
        }

        $searchParam = $params['Properties']['search'];

        $assinee->andFilterWhere(['or',
            ['like', 'property_name', trim($searchParam)],
            ['like', 'id', trim($searchParam)],
            ['like', 'address_line_1', trim($searchParam)],
            ['like', 'address_line_2', trim($searchParam)],
        ]);

        return $dataProvider;
    }
    
    public static function doUpdateServiceRequest ($id) {
        $tblName = self::tableName();
        $res = Yii::$app->db->createCommand();
    }
}
