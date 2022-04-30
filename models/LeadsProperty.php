<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "leads_owner".
 *
 * @property integer $id
 * @property string $full_name
 * @property string $email_id
 * @property string $contact_number
 * @property string $address
 * @property string $city
 * @property string $state
 * @property string $region
 * @property string $pincode
 * @property string $communication
 * @property string $ref_status
 * @property integer $reffered_by
 * @property integer $updated_by
 * @property string $created_date
 * @property string $updated_date
 */
class LeadsProperty extends \yii\db\ActiveRecord {

    public $search;
    public $city2;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'leads_property';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['property_name', 'property_id', 'owner_id', 'address', 'lead_city', 'lead_state', 'branch_code', 'pincode'], 'required'],
            [['created_by', 'updated_by'], 'integer'],
            [['created_date', 'updated_date'], 'safe'],
            [['address', 'address2'], 'string', 'max' => 255],
            [['property_name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'property_name' => 'Property Name',
            'property_id' => 'Property Id',
            'owner_id' => 'Owner Id',
            'address' => 'Address line 1',
            'address2' => 'Address line 2',
            'lead_city' => 'City',
            'lead_state' => 'State',
            'branch_code' => 'Branch',
            'updated_by' => 'Updated By',
            'updated_date' => 'Updated Date',
            'created_by' => 'Created By',
            'created_date' => 'Created Date'
        ];
    }

    public function searchCommon($params) {
        $userType = Yii::$app->user->identity->user_type;
        if ($userType == 6) {
            return $this->search($params);
        } else {
            return $this->searchOperation($params);
        }
    }

    public function search($params) {
        
        if (isset($params['LeadsProperty']['sort'])) {
            if ($params['LeadsProperty']['sort'] == '') {
                $sort = 'leads_property.id desc';
            } else {
                $sort = $params['LeadsProperty']['sort'];
            }
        } else {
            $sort = 'leads_property.id desc';
        }
        
        $userAssignments = \app\models\Users::find()->where(['id' => Yii::$app->user->id])->one();
        $opsProfileModel = \app\models\SalesProfile::findOne(['sale_id' => Yii::$app->user->id]);
        $currentRoleCode = $opsProfileModel->role_code;
        $currentRoleValue = $opsProfileModel->role_value;
        
        if ($currentRoleValue == 'ELHQ') {
            $assinee = \app\models\LeadsProperty::find()
                ->leftJoin('properties', 'properties.id = leads_property.property_id');
        } else if ($currentRoleCode == 'SLSTMG') {
            $assinee = \app\models\LeadsProperty::find()
                ->leftJoin('properties', 'properties.id = leads_property.property_id')
                ->where(['leads_property.lead_state' => $currentRoleValue]);
        } else if ($currentRoleCode == 'SLCTMG') {
            $assinee = \app\models\LeadsProperty::find()
                ->leftJoin('properties', 'properties.id = leads_property.property_id')
                ->where(['leads_property.lead_city' => $currentRoleValue]);
        } else if ($currentRoleCode == 'SLBRMG') {
            $assinee = \app\models\LeadsProperty::find()
                ->leftJoin('properties', 'properties.id = leads_property.property_id')
                ->where(['leads_property.branch_code' => $currentRoleValue]);
        } else if ($currentRoleCode == 'SLEXE') {
            $assinee = \app\models\LeadsProperty::find() 
                ->leftJoin('properties', 'properties.id = leads_property.property_id')
                ->where(['leads_property.branch_code' => $currentRoleValue])
                ->andWhere(['leads_property.lead_city' => $currentRoleValue])
                ->andWhere(['properties.sales_id' => Yii::$app->user->id]);
        }
        
        $dataProvider = new ActiveDataProvider([
            'query' => $assinee,
            'sort' => false,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        
        if (isset($params['LeadsProperty']['search'])) {
            $searchParam = $params['LeadsProperty']['search'];

            $assinee->andFilterWhere(['or',
                ['like', 'leads_property.property_name', trim($searchParam)],
                ['like', 'leads_property.address', trim($searchParam)],
                ['like', 'leads_property.address2', trim($searchParam)],
                ['like', 'leads_property.branch_code', trim($searchParam)],
                ['like', 'properties.unit', trim($searchParam)],
                    ]
            );
        }


        $assinee->orderBy($sort);

        return $dataProvider;
    }

    public function searchOperation($params) {
        if (isset($params['LeadsProperty']['sort'])) {
            if ($params['LeadsProperty']['sort'] == '') {
                $sort = 'leads_property.id desc';
            } else {
                $sort = $params['LeadsProperty']['sort'];
            }
        } else {
            $sort = 'leads_property.id desc';
        }
        
        $userAssignments = \app\models\Users::find()->where(['id' => Yii::$app->user->id])->one();
        $opsProfileModel = \app\models\OperationsProfile::findOne(['operations_id' => Yii::$app->user->id]);
        $currentRoleCode = $opsProfileModel->role_code;
        $currentRoleValue = $opsProfileModel->role_value;
        
        if ($currentRoleValue == 'ELHQ') {
            $assinee = \app\models\LeadsProperty::find()
                ->leftJoin('properties', 'properties.id = leads_property.property_id')
                ->leftJoin('users', 'users.id = properties.owner_id');
        } else if ($currentRoleCode == 'OPSTMG') {
            $assinee = \app\models\LeadsProperty::find()
                ->leftJoin('properties', 'properties.id = leads_property.property_id')
                ->leftJoin('users', 'users.id = properties.owner_id')
                ->where(['leads_property.lead_state' => $currentRoleValue]);
        } else if ($currentRoleCode == 'OPCTMG') {
            $assinee = \app\models\LeadsProperty::find()
                ->leftJoin('properties', 'properties.id = leads_property.property_id')
                ->leftJoin('users', 'users.id = properties.owner_id')
                ->where(['leads_property.lead_city' => $currentRoleValue]);
        } else if ($currentRoleCode == 'OPBRMG') {
            $assinee = \app\models\LeadsProperty::find()
                ->leftJoin('properties', 'properties.id = leads_property.property_id')
                ->leftJoin('users', 'users.id = properties.owner_id')
                ->where(['leads_property.branch_code' => $currentRoleValue]);
        } else if ($currentRoleCode == 'OPEXE') {
            $branchModel = \app\models\Branches::find()->where(['branch_code' => $currentRoleValue])->one();
            $assinee = \app\models\LeadsProperty::find() 
                ->leftJoin('properties', 'properties.id = leads_property.property_id')
                ->leftJoin('users', 'users.id = properties.owner_id')
                ->where(['leads_property.branch_code' => $currentRoleValue])
                ->andWhere(['leads_property.lead_city' => $branchModel->city_code])
                ->andWhere(['properties.operations_id' => Yii::$app->user->id]);
        }
        
        if (isset($params['LeadsProperty']['search'])) {
            $searchParam = $params['LeadsProperty']['search'];

            $assinee->andFilterWhere(['or',
                ['like', 'leads_property.property_name', trim($searchParam)],
                ['like', 'leads_property.address', trim($searchParam)],
                ['like', 'leads_property.address2', trim($searchParam)],
                ['like', 'leads_property.branch_code', trim($searchParam)],
                ['like', 'properties.unit', trim($searchParam)],
                    ]
            );
        }
        
        $dataProvider = new ActiveDataProvider([
            'query' => $assinee,
            'sort' => false,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $assinee->orderBy($sort);

        return $dataProvider;
    }

}
