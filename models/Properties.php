<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\PropertiesAttributes;
use app\models\PropertyAttributeMap;
use app\models\PropertyListing;
use app\models\Cities;
use app\models\TenantPayments;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "properties".
 *
 * @property integer $id
 * @property integer $owner_id
 * @property string $property_name
 * @property integer $property_type
 * @property string $property_description
 * @property string $address_line_1
 * @property string $lat
 * @property string $lon
 * @property string $city
 * @property string $state
 * @property integer $pincode
 * @property integer $created_by
 * @property integer $updated_by
 * @property string $created_date
 * @property string $updated_date
 * @property integer $flat_bhk
 * @property double $flat_area
 * @property string $address_line_2
 * @property string $status
 * @property string $presale
 * @property integer $owner_lead_id
 */
class Properties extends \yii\db\ActiveRecord {

    public $availability_from;
    public $beds;
    public $rooms;
    public $beds_room;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'properties';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['owner_id', 'property_name', 'unit', 'managed_by', 'gender', 'unit'], 'required'],
            [['owner_id', 'is_room', 'property_type', 'created_by', 'updated_by', 'flat_bhk', 'owner_lead_id', 'beds_room'], 'integer'],
            [['created_date', 'updated_date'], 'safe'],
            [[ 'pincode'], 'string', 'max'=>15 ],
            [['flat_area'], 'number'],
            [['flat_type'], 'number'],
            [['status', 'presale'], 'string'],
            // ['flat_bhk', 'required', 'when' => function($model) {
            // return $model->property_type == 3;
            // }, 'enableClientValidation' => false],           
            // ['flat_area', 'required', 'when' => function($model) {
            // return $model->property_type == 3;
            // }, 'enableClientValidation' => false], 
            /*  ['flat_type', 'required', 'when' => function($model) {
              return $model->property_type == 3;
              }, 'enableClientValidation' => false], */

            // ['is_room', 'required', 'when' => function($model) {
            // return $model->property_type == 1;
            // }, 'enableClientValidation' => false], 
            // ['is_room', 'required', 'when' => function($model) {
            // return $model->property_type == 2;
            // }, 'enableClientValidation' => false], 
            // ['beds_room', 'required', 'when' => function($model) {
            // return $model->is_room == 0 && $model->is_room != '' && ($model->property_type == 1 || $model->property_type == 2);
            // }, 'enableClientValidation' => false], 
            // ['rooms', 'required', 'when' => function($model) {
            // return $model->is_room == 1 && ( $model->property_type == 1 || $model->property_type == 2);
            // }, 'enableClientValidation' => false], 
            //    ['beds','checkIsArray'],
            [['property_name', 'property_description', 'address_line_1', 'city', 'state'], 'string', 'max' => 10000],
            [['lat', 'lon'], 'string', 'max' => 255],
            [['address_line_2'], 'string', 'max' => 1100],
        ];
    }

    public function checkIsArray() {
        if (is_array($this->beds)) {
            if ($this->is_room == 0) {
                $this->addError('beds', 'beds is must have value');
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'owner_id' => 'Owner ID',
            'property_name' => 'Property name',
            'property_type' => 'Property type',
            'property_description' => 'Property description',
            'address_line_1' => 'Address line 1',
            'lat' => 'Lat',
            'lon' => 'Lon',
            'region' => 'Branch',
            'city' => 'City',
            'state' => 'State',
            'pincode' => 'Pincode',
            'is_room' => 'Is Room',
            'created_by' => 'Created by',
            'updated_by' => 'Updated by',
            'created_date' => 'Created date',
            'updated_date' => 'Updated date',
            'flat_bhk' => 'Flat bhk',
            'flat_area' => 'Flat area',
            'flat_type' => 'Type',
            'address_line_2' => 'Address line 2',
            'status' => 'Status',
            'presale' => 'Presale',
            'beds_room' => 'No of Rooms',
            'owner_lead_id' => 'Owner lead ID',
            'beds' => 'Beds',
            'gender' => 'Gender',
            'managed_by' => 'Managed By',
            'unit' => 'Unit',
        ];
    }

    public function getPropertyListing() {
        return $this->hasOne(PropertyListing::className(), ['property_id' => 'id']);
    }

    public function getPropertiesTypes() {
        return $this->hasOne(PropertyTypes::className(), ['id' => 'property_type']);
    }

    public function getPayments() {
        return $this->hasMany(TenantPayments::className(), ['property_id' => 'id'])->where(['email_id' => Yii::$app->userdata->email])->andWhere('create_time < CURDATE()');
    }

    public function getAttributesValue() {
        return $this->hasMany(PropertyAttributeMap::className(), ['property_id' => 'property_id']);
    }

    public static function getCityName($id) {
        $cities = Cities::find()->where(['id' => $id])->one();
        if ($cities)
            return $cities->city_name;
    }

    public static function getAttributeName($attr_id) {
        $propertiesAttri = PropertiesAttributes::find()->where(['id' => $attr_id])->one();
        if ($propertiesAttri)
            return $propertiesAttri->name;
    }

    public static function getAttributeTotal($attr_id) {
        $propertiesAttri = PropertiesAttributes::find()->where(['id' => $attr_id])->one();
        if ($propertiesAttri)
            return $propertiesAttri;
    }

    public static function getAttributeNameValue($id, $attr_id) {
        $propertiesAttri = PropertyAttributeMap::find()->where(['property_id' => $id, 'attr_id' => $attr_id])->one();
        if ($propertiesAttri)
            return $propertiesAttri->value;
    }
    
    public function getListing($params) {
        $userType = Yii::$app->user->identity->user_type;
        if ($userType == 7) {
            return $this->listForOps($params);
        } else {
            return $this->listForSales($params);
        }
    }
    
    public function listForSales($params) {
        $userAssignments = \app\models\Users::find()->where(['id' => Yii::$app->user->id])->one();
        $opsProfileModel = \app\models\SalesProfile::findOne(['sale_id' => Yii::$app->user->id]);
        $currentRoleCode = $opsProfileModel->role_code;
        $currentRoleValue = $opsProfileModel->role_value;
        
        if ($currentRoleValue == 'ELHQ') {
            $assinee = \app\models\Properties::find()->where(['status' => '1'])->orderBy('created_date DESC');
        } else if ($currentRoleCode == 'SLSTMG') {
            $assinee = \app\models\Properties::find()
                ->where(['state' => $currentRoleValue])
                ->andWhere(['status' => '1'])
                ->orderBy('created_date DESC');
        } else if ($currentRoleCode == 'SLCTMG') {
            $assinee = \app\models\Properties::find()
                ->where(['city' => $currentRoleValue])
                ->andWhere(['status' => '1'])
                ->orderBy('created_date DESC');
        } else if ($currentRoleCode == 'SLBRMG') {
            $assinee = \app\models\Properties::find()
                ->where(['branch_code' => $currentRoleValue])
                ->andWhere(['status' => '1'])
                ->orderBy('created_date DESC');
        } else if ($currentRoleCode == 'SLEXE') {
            $branchModel = \app\models\Branches::find()->where(['branch_code' => $currentRoleValue])->one();
            $assinee = \app\models\Properties::find()
                ->where(['branch_code' => $currentRoleValue])
                ->andWhere(['city' => $branchModel->city_code])
                ->andWhere(['sales_id' => Yii::$app->user->id])
                ->andWhere(['status' => '1'])
                ->orderBy('created_date DESC');
        }
        
        $dataProvider = new ActiveDataProvider([
            'query' => $assinee,
            'sort' => false
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
    
    public function listForOps($params) {
        $userAssignments = \app\models\Users::find()->where(['id' => Yii::$app->user->id])->one();
        $opsProfileModel = \app\models\OperationsProfile::findOne(['operations_id' => Yii::$app->user->id]);
        $currentRoleCode = $opsProfileModel->role_code;
        $currentRoleValue = $opsProfileModel->role_value;
        
        if ($currentRoleValue == 'ELHQ') {
            $assinee = \app\models\Properties::find()->where(['status' => '1'])->orderBy('created_date DESC');
        } else if ($currentRoleCode == 'OPSTMG') {
            $assinee = \app\models\Properties::find()
                ->where(['state' => $currentRoleValue])
                ->andWhere(['status' => '1'])
                ->orderBy('created_date DESC');
        } else if ($currentRoleCode == 'OPCTMG') {
            $assinee = \app\models\Properties::find()
                ->where(['city' => $currentRoleValue])
                ->andWhere(['status' => '1'])
                ->orderBy('created_date DESC');
        } else if ($currentRoleCode == 'OPBRMG') {
            $assinee = \app\models\Properties::find()
                ->where(['branch_code' => $currentRoleValue])
                ->andWhere(['status' => '1'])
                ->orderBy('created_date DESC');
        } else if ($currentRoleCode == 'OPEXE') {
            $branchModel = \app\models\Branches::find()->where(['branch_code' => $currentRoleValue])->one();
            $assinee = \app\models\Properties::find()
                ->where(['branch_code' => $currentRoleValue])
                ->andWhere(['city' => $branchModel->city_code])
                ->andWhere(['operations_id' => Yii::$app->user->id])
                ->andWhere(['status' => '1'])
                ->orderBy('created_date DESC');
        }
        
        $dataProvider = new ActiveDataProvider([
            'query' => $assinee,
            'sort' => false
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

    public function searchCommon($params) {
        $userType = Yii::$app->user->identity->user_type;
		
        if ($userType == 7) {
            $userAssignments = \app\models\Users::find()->where(['id' => Yii::$app->user->id])->one();

            $assinee = \app\models\Properties::find()->where(['status' => '1'])->orderBy('created_date DESC');


            
            $dataProvider = new ActiveDataProvider([
                'query' => $assinee,
                'sort' => false
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
        } else {
            return $this->searchSales($params);
        }
    }

    public function search($params) {
		
        $userAssignments = \app\models\Users::find()->where(['id' => Yii::$app->user->id])->one();

        $assinee = \app\models\Properties::find()->where(['status' => '1']);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $assinee,
            'sort' => false
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

    public function searchSales($params) {
        $userAssignments = \app\models\Users::find()->where(['id' => Yii::$app->user->id])->one();
        /* if($userAssignments){

          $condition1='';
          $role=$userAssignments->role;
          switch($role){
          case 1:
          $roles = \app\models\SalesProfile::find()->select('region')->where(['sale_id'=>Yii::$app->user->id])->one();
          $assinee = \app\models\Properties::find()->where(['region'=>$roles->region]);
          break;
          case 2:
          $roles = \app\models\SalesProfile::find()->select('region')->where(['sale_id'=>Yii::$app->user->id])->one();
          $assinee = \app\models\Properties::find()->where(['region'=>$roles->region]);
          break;
          case 3:
          $roles = \app\models\SalesProfile::find()->select('city')->where(['sale_id'=>Yii::$app->user->id])->one();
          $assinee = \app\models\Properties::find()->where(['city'=>$roles->city]);
          break;
          case 4:
          $roles = \app\models\SalesProfile::find()->select('state')->where(['sale_id'=>Yii::$app->user->id])->one();
          $assinee = \app\models\Properties::find()->where(['state'=>$roles->state]);
          break;

          }



          }
          else{

          $assinee1 = \app\models\OwnerProfile::find()->select('owner_id')->where(['sale_id'=>Yii::$app->user->id])->all();
          $listt='';
          if(count($assinee1)!=0){
          foreach($assinee1 as $key=>$value){
          if($listt=='')
          {
          $listt=$value->owner_id;
          }
          else{
          $listt.=','.$value->owner_id;
          }
          }
          $assinee = \app\models\Properties::find()->where(['owner_id IN ('.$listt.')']);
          }
          else{
          $assinee = \app\models\Properties::find()->where(['owner_id = 0']);
          }
          } */

        $assinee = \app\models\Properties::find()->orderBy('created_date DESC');
        $dataProvider = new ActiveDataProvider([
            'query' => $assinee,
            'sort' => false
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
    
    public function getPropertyListByListingStatus ($term) {
        $userType = Yii::$app->user->identity->user_type;
        if ($userType == 7) {
            $opsProfileModel = \app\models\OperationsProfile::findOne(['operations_id' => Yii::$app->user->id]);
            $currentRoleCode = $opsProfileModel->role_code;
            $currentRoleValue = $opsProfileModel->role_value;
            
            if ($currentRoleValue == 'ELHQ') {
                $where = "1";
            } else if ($currentRoleCode == 'OPSTMG') {
                $where = " p.state = '".$currentRoleValue."' ";
            } else if ($currentRoleCode == 'OPCTMG') {
                $where = " p.city = '".$currentRoleValue."' ";
            } else if ($currentRoleCode == 'OPBRMG') {
                $where = " p.branch_code = '".$currentRoleValue."' ";
            } else if ($currentRoleCode == 'OPEXE') {
                $branchModel = \app\models\Branches::find()->where(['branch_code' => $currentRoleValue])->one();
                $where = " p.branch_code = '".$currentRoleValue."' "
                        . " AND p.city = '".$branchModel->city_code."' "
                        . " AND p.operations_id = '".Yii::$app->user->id."' ";
            }
        } else {
            $opsProfileModel = \app\models\SalesProfile::findOne(['sale_id' => Yii::$app->user->id]);
            $currentRoleCode = $opsProfileModel->role_code;
            $currentRoleValue = $opsProfileModel->role_value;
            
            if ($currentRoleValue == 'ELHQ') {
                $where = "1";
            } else if ($currentRoleCode == 'SLSTMG') {
                $where = " p.state = '".$currentRoleValue."' ";
            } else if ($currentRoleCode == 'SLCTMG') {
                $where = " p.city = '".$currentRoleValue."' ";
            } else if ($currentRoleCode == 'SLBRMG') {
                $where = " p.branch_code = '".$currentRoleValue."' ";
            } else if ($currentRoleCode == 'SLEXE') {
                $branchModel = \app\models\Branches::find()->where(['branch_code' => $currentRoleValue])->one();
                $where = " p.branch_code = '".$currentRoleValue."' "
                        . " AND p.city = '".$branchModel->city_code."' "
                        . " AND p.sales_id = '".Yii::$app->user->id."' ";
            }
        }
        
        $sql = ''
                . 'SELECT '
                . 'p.id, p.owner_id, p.property_name, p.property_type, p.flat_type, p.address_line_1, p.city, p.state, p.address_line_2, '
                . 'pl.rent, pl.deposit, pl.maintenance, u.full_name '
                . 'FROM properties p '
                . 'INNER JOIN property_listing pl ON pl.property_id = p.id '
                . 'INNER JOIN users u ON u.id = p.owner_id '
                . 'WHERE p.status = "1" AND pl.status = "1" AND ( p.property_name LIKE "%'.$term.'%" || p.property_description LIKE "%'.$term.'%" || p.address_line_1 LIKE "%'.$term.'%" || p.address_line_2 LIKE "%'.$term.'%" || u.full_name LIKE "%'.$term.'%" ) '
                . 'AND '.$where;
        $conn = Yii::$app->db->createCommand($sql);
        $result = $conn->queryAll();
        return $result;
    }
    
    public function getPropertyListByListingStatus2 ($term) {
        $sql = ''
                . 'SELECT '
                . 'p.id, p.owner_id, p.property_name, p.property_type, p.flat_type, p.address_line_1, p.city, p.state, p.address_line_2, '
                . 'pl.rent, pl.deposit, pl.maintenance '
                . 'FROM properties p '
                . 'INNER JOIN property_listing pl ON pl.property_id = p.id '
                . 'INNER JOIN users u ON u.id = p.owner_id '
                . 'WHERE ( p.property_name LIKE "%'.$term.'%" || p.property_description LIKE "%'.$term.'%" || p.address_line_1 LIKE "%'.$term.'%" || p.address_line_2 LIKE "%'.$term.'%" || u.full_name LIKE "%'.$term.'%" ) ';
        $conn = Yii::$app->db->createCommand($sql);
        $result = $conn->queryAll();
        return $result;
    }

}
