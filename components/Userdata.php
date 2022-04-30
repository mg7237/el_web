<?php

namespace app\components;

use Yii;
use app\models\Users;
use app\models\PropertiesAddress;
use app\models\PropertyTypes;
use app\models\Properties;
use app\models\Bookings;
use app\models\PropertyAttributeMap;
use app\models\Agreements;
use app\models\TenantPayments;
use app\models\FavouriteProperties;
use app\models\States;
use app\models\LeadsTenant;
use app\models\Cities;
use app\models\AdvertisementUserType;
use yii\helpers\Url;

/**
 * Extended yii\web\User
 *
 * This allows us to do "Yii::$app->user->something" by adding getters
 * like "public function getSomething()"
 *
 * So we can use variables and functions directly in `Yii::$app->user`
 */
class Userdata extends \yii\web\User {

    public function getUsername() {
        return \Yii::$app->user->identity->login_id;
    }

    public function getName() {
        return \Yii::$app->user->identity->full_name;
    }

    public function getEmail() {
        if (Yii::$app->user->isGuest) {
            return '';
        } else {
            return \Yii::$app->user->identity->login_id;
        }
    }

    public function getusertype() {
        if (Yii::$app->user->isGuest) {
            return '';
        } else {
            return Yii::$app->user->identity->user_type;
        }
    }

    public function getUserById($id) {
        $model = Users::findOne($id);
        return $model;
    }

    public function passwordGenerate() {

        $characters1 = '0123456789';
        $characters2 = 'abcdefghijklmnopqrstuvwxyz';
        $characters3 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characters4 = '()?_!@#$%^&*-=+';
        $randstring = Array();
        for ($i = 0; $i < 2; $i++) {
            $randstring[] = $characters2[rand(0, strlen($characters2) - 1)];
            $randstring[] = $characters2[rand(0, strlen($characters2) - 1)];
            $randstring[] = $characters2[rand(0, strlen($characters2) - 1)];
            $randstring[] = $characters3[rand(0, strlen($characters3) - 1)];
            $randstring[] = $characters3[rand(0, strlen($characters3) - 1)] . '_@';
            if ($i == 0) {
                $randstring[] = $characters4[rand(0, strlen($characters4) - 1)];
                $randstring[] = $characters1[rand(0, strlen($characters1) - 1)];
            } else {
                $randstring[] = $characters1[rand(0, strlen($characters1) - 1)];
                $randstring[] = $characters2[rand(0, strlen($characters2) - 1)];
                $randstring[] = $characters3[rand(0, strlen($characters3) - 1)];
            }
        }
        return implode("", $randstring);
    }

    public function getLeadByEmailId($id) {
        $model = \app\models\LeadsOwner::findOne(['email_id' => $id]);
        if ($model)
            return $model->id;
        else
            return false;
    }

    public function getPropertyImages($id) {
        //echo $id;die;
        //$model = \app\models\PropertyImages::find()->where(['property_id' => $id])->all();
        $model[0] = \app\models\PropertyImages::find()->where(['property_id' => $id, 'is_main' => 1])->one();

        return $model;
    }

    public function getPropertiesAttributes($id) {
        $model = \app\models\PropertiesAttributes::findOne($id);
        if ($model)
            return $model->name;
    }

    public function getFavData($id) {
        $model = \app\models\FavouriteProperties::find()->where(['property_id' => $id, 'applicant_id' => Yii::$app->userdata->email])->all();
        return count($model);
    }

    public function getManagerId($region, $id) {
        $idParent = 0;
        if ($id == 1) {

            $model = \app\models\Users::find()->where(['username' => 'admin'])->one();
            $idParent = $model->id;
        } elseif ($id == 2) {

            $model = \app\models\Cities::findOne($region);
            $modelAssign = \app\models\RegionAssignment::find()->where(['assign_region_id' => $model->state_id])->one();

            if ($modelAssign) {
                $idParent = $modelAssign->user_id;
            } else {
                $model = \app\models\Users::find()->where(['username' => 'admin'])->one();
                $idParent = $model->id;
            }
        } elseif ($id == 3) {

            $model = \app\models\Regions::findOne($region);

            $modelAssign = \app\models\RegionAssignment::find()->where(['assign_region_id' => $model->city_id])->one();
            $modelAssignState = \app\models\RegionAssignment::find()->where(['assign_region_id' => $model->state_id])->one();

            if ($modelAssign) {
                $idParent = $modelAssign->user_id;
            } elseif ($modelAssignState) {
                $idParent = $modelAssignState->user_id;
            } else {
                $model = \app\models\Users::find()->where(['username' => 'admin'])->one();
                $idParent = $model->id;
            }
        }

        return $idParent;
    }

    public function getRoleCodeByUserIdOps($id) {
        $model = \app\models\OperationsProfile::findOne(['operations_id' => $id]);
        if ($model)
            return $model->role_code;
        else
            return $model;
    }

    public function getRoleValueByUserIdOps($id) {
        $model = \app\models\OperationsProfile::findOne(['operations_id' => $id]);
        if ($model)
            return $model->role_value;
        else
            return $model;
    }

    public function getRoleCodeByUserIdSales($id) {
        $model = \app\models\SalesProfile::findOne(['sale_id' => $id]);
        if ($model)
            return $model->role_code;
        else
            return $model;
    }

    public function getRoleValueByUserIdSales($id) {
        $model = \app\models\SalesProfile::findOne(['sale_id' => $id]);
        if ($model)
            return $model->role_value;
        else
            return $model;
    }

    public function getRoleByroleId($id) {
        $model = \app\models\Roles::findOne($id);
        if ($model)
            return $model->role_name;
        else
            return $model;
    }

    public function getRoleIdByRoleCode($id) {
        $model = \app\models\Roles::find()->where(['role_code' => $id])->one();
        if ($model)
            return $model->id;
        else
            return 0;
    }

    public function getBranchNameByCode($id) {
        $model = \app\models\Branches::find()->where(['branch_code' => $id])->one();
        if ($model)
            return $model->name;
        else
            return 'N/A';
    }

    public function getAttrVal($id, $attr) {
        $model = PropertyAttributeMap::find()->where(['attr_id' => $attr, 'property_id' => $id])->one();
        if ($model)
            return $model->value;
        else
            return false;
    }

    public function getFollowByLeadId($id) {
        $model = \app\models\LeadsTenant::find()->where(['email_id' => $id])->one();
        if ($model)
            return $model->follow_up_date_time;
        else
            return false;
    }

    public function getFollowByLeadIdOwner($id) {
        $model = \app\models\LeadsOwner::find()->where(['email_id' => $id])->one();
        if ($model)
            return $model->follow_up_date_time;
        else
            return false;
    }

    public function getUserEmailById($id) {
        $model = Users::findOne($id);
        if ($model)
            return $model->login_id;
        else
            return false;
    }

    public function getUsertypeByValue($id) {
        $model = \app\models\UserTypes::findOne($id);
        if ($model)
            return $model->user_type_name;
        else
            return false;
    }

    public function getRegionByType($type, $id) {
        $name = '';
        if ($type == 1) {

            $model = \app\models\States::findOne($id);
            $name = $model->name;
        } elseif ($type == 2) {

            $model = \app\models\Cities::findOne($id);
            $name = $model->city_name;
        } elseif ($type == 3) {

            $model = \app\models\Regions::findOne($id);
            $name = $model->name;
        }

        if ($name)
            return $name;
        else
            return false;
    }

    public function getUserIdByEmail($id) {
        $model = Users::findOne(['login_id' => $id]);
        // echo $model['id'];
        // echo $model->id;
        // die;
        return $model['id'];
    }

    public function getProofs($id, $proof) {
        $model = \app\models\UserIdProofs::find()->where(['user_id' => $id, 'proof_type' => $proof])->one();
        if ($model)
            return $model->proof_document_url;
        else
            return false;
    }

    public function getAllUserProofs($id) {
        $model = \app\models\UserIdProofs::find()->where(['user_id' => $id])->all();
        if ($model)
            return $model;
        else
            return false;
    }

    public function getProofType($id) {
        $model = \app\models\ProofType::findOne(['id' => $id]);

        if ($model) {
            return $model->name;
        } else {
            return '';
        }
    }

    public function getCityName($id) {
        $model = \app\models\Cities::find()->where(['code' => $id])->one();
        if ($model)
            return $model->city_name;
        else
            return false;
    }
    
    public function getCityNameById ($id) {
        $model = \app\models\Cities::find()->where(['id' => $id])->one();
        if ($model)
            return $model->city_name;
        else
            return false;
    }

    public function getCityNameByCityCode($id) {
        $model = \app\models\Cities::findOne(['status' => 1, 'code' => $id]);
        if ($model)
            return $model->city_name;
        else
            return false;
    }

    public function getCityNameByCode($id) {
        $model = \app\models\Cities::findOne(['code' => $id]);
        if ($model)
            return $model->city_name;
        else
            return false;
    }

    public function getOwnersPan($id) {
        $model = \app\models\OwnerProfile::find()->where(['owner_id' => $id])->one();
        if ($model)
            return $model->pan_number;
        else
            return false;
    }

    public function getOwnerPhoneById($id) {
        $model = \app\models\OwnerProfile::find()->where(['owner_id' => $id])->one();
        if ($model)
            return $model->phone;
        else
            return false;
    }

    public function getCityCodeById($id) {
        $model = \app\models\Cities::findOne($id);
        if ($model)
            return $model->code;
        else
            return false;
    }

    public function getCityIdByCode($id) {
        $model = \app\models\Cities::findOne(['code' => $id]);
        if ($model)
            return $model->id;
        else
            return false;
    }

    public function getStateCodeById($id) {
        $model = \app\models\States::findOne($id);

        if ($model)
            return $model->code;
        else
            return false;
    }

    public function getStateName($id) {
        $model = \app\models\States::find($id)->where(['code' => $id])->one();

        if ($model)
            return $model->name;
        else
            return false;
    }

    public function getStateNameByStateCode($id) {
        $model = \app\models\States::findOne(['code' => $id]);

        if ($model)
            return $model->name;
        else
            return false;
    }

    public function getStateIdByCityId($id) {
        $model = \app\models\Cities::findOne($id);
        if ($model)
            return $model->state_id;
        else
            return false;
    }

    public function getStateCodeByCityId($id) {
        $model = \app\models\Cities::findOne($id);
        if ($model) {
            $stModel = \app\models\States::findOne(['id' => $model->state_id]);
            return $stModel->code;
        } else {
            return false;
        }
    }

    public function getStateCodeByCityCode($id) {
        $model = \app\models\Cities::findOne(['code' => $id]);
        if ($model) {
            $stModel = \app\models\States::findOne(['id' => $model->state_id]);
            return $stModel->code;
        } else {
            return false;
        }
    }

    public function getStateCodeByStateId($id) {
        $model = \app\models\States::findOne(['id' => $id]);
        if ($model) {
            return $model->code;
        } else {
            return false;
        }
    }

    public function getRegionName($id) {
        $model = \app\models\Regions::findOne($id);

        if ($model)
            return $model->name;
        else
            return false;
    }

    public function getPhoneNumber($id) {
        $modelTenant = \app\models\TenantProfile::find()->where(['tenant_id' => $id])->one();
        $model = \app\models\ApplicantProfile::find()->where(['applicant_id' => $id])->one();
        return $model ? $model->phone : ($modelTenant ? $modelTenant->phone : '');
    }

    public function getNumberMobile($id) {
        $model = \app\models\OwnerProfile::find()->where(['owner_id' => $id])->one();
        if ($model['phone']) {
            return $model['phone'];
        } else {
            return "";
        }

        // return $id;
    }

    public function getLeaseStartDate($id) {
        $model = \app\models\TenantAgreements::findOne(['tenant_id' => $id]);
        if ($model) {
            return $model->lease_start_date;
        } else {
            return '';
        }
    }

    public function getLeaseEndDate($id) {
        $model = \app\models\TenantAgreements::findOne(['tenant_id' => $id]);
        if ($model) {
            return $model->lease_end_date;
        } else {
            return '';
        }
    }

    public function getContractStartDate($id) {
        $model = \app\models\AdvisorAgreements::find()->where(['advisor_id' => $id])->one();
        return $model['start_date'];
    }

    public function getContractEndDate($id) {
        $model = \app\models\AdvisorAgreements::find()->where(['advisor_id' => $id])->one();
        return $model['end_date'];
    }

    public function getPropertyStartDate($id) {
        $model = \app\models\PropertyAgreements::find()->where(['property_id' => $id])->one();
        if (count($model) != 0) {
            return $model->contract_start_date;
        } else {
            return '';
        }
    }

    public function getPropertyEndDate($id) {
        $model = \app\models\PropertyAgreements::find()->where(['property_id' => $id])->one();
        if (count($model) != 0) {
            return $model->contract_end_date;
        } else {
            return '';
        }
    }

    public function getOwnerAddress($id) {
        $model = \app\models\OwnerProfile::find()->where(['owner_id' => $id])->one();
        if (count($model) == '1') {
            return $model->address_line_1 . ' ' . $model->address_line_2;
        } else {
            return "";
        }
    }

    public function getStatus($id) {

        $array = [0 => 'New', 1 => 'Verified', 2 => 'Waiting for decision', 3 => 'Verbal confirmation', 4 => 'Sign Off', 5 => 'Cancelled'];
        return $array[$id];
    }

    public function getUserNameByEmail($id) {
        $model = Users::findOne(['login_id' => $id]);
        if ($model) {
            return $model->full_name;
        } else {
            return false;
        }
    }

    public function getFullNameById($id) {
        $model = Users::findOne($id);
        if ($model) {
            return $model->full_name;
        } else {
            return false;
        }
    }

    public function getScheduledDateById($id) {
        $model = FavouriteProperties::findOne(['property_id' => $id, 'applicant_id' => Yii::$app->userdata->email]);
        if ($model) {
            return $model->visit_date;
        } else {
            return false;
        }
    }

    public function getScheduledTimeById($id) {
        $model = FavouriteProperties::findOne(['property_id' => $id, 'applicant_id' => Yii::$app->userdata->email]);
        if ($model) {
            return $model->visit_time;
        } else {
            return false;
        }
    }

    public function getLoginIdById($id) {
        $model = Users::findOne($id);
        if ($model) {
            return $model->login_id;
        } else {
            return false;
        }
    }

    function datediffdays($move_in, $exit_date) {
        $datetime1 = date_create($move_in);
        $datetime2 = date_create($exit_date);
        $interval = date_diff($datetime1, $datetime2);
        return $interval->days;
        die;
    }

    function datediff($move_in, $exit_date, $minStay) {
        $d1 = strtotime($move_in);
        $d2 = strtotime($exit_date);
        $min_date = min($d1, $d2);
        $max_date = max($d1, $d2);
        $i = 0;

        while (($min_date = strtotime("+1 MONTH", $min_date)) <= $max_date) {
            $i++;
        }
        if ($i >= $minStay) {
            return 'yes';
        } else {
            return 'no';
        }
        die;
    }

    public function getManagerForUser($id) {
        $model = \app\models\UserAssignments::find()->where(['user_id' => $id])->one();
        if ($model) {
            $data = $this->getFullNameById($model->manager_id);
            return $data;
        } else {
            return false;
        }
    }

    public function getPenalty() {
        $model = \app\models\PmsPropertyConfigurations::find()->one();
        if ($model) {
            $data = $model->penalty_percent;
            return $data;
        } else {
            return false;
        }
    }

    /* public function getPropertyAvailabilityBeds($idp,$childsr){
      /*$implodeData =[];
      $date = '';
      $beds = \app\models\TenantAgreements::find()->where(['parent_id'=>$idp])->all();
      if($beds){
      foreach( $beds as $bed ){
      $ids = explode(',', $bed->property_id ) ;
      foreach( $ids as  $id ){
      if($this->checkParent($id) == 1  ){
      $childs = \app\models\ChildProperties::find()->where(['parent'=>$id ])->all();
      foreach( $childs as  $child ){

      if( $childsr  == $child->id ){

      $beds = \app\models\TenantAgreements::find()->where("FIND_IN_SET($id,property_id)")->andWhere(['parent_id'=>$idp])->andWhere('lease_end_date > "'.date('Y-m-d H:i:s').'" AND status = 1')->one();
      if($beds){
      return $beds->lease_end_date  ;
      } else {
      return $date;
      }
      }
      }
      } elseif( $this->checkParent($id) == 2 ) {
      $beds = \app\models\TenantAgreements::find()->where("FIND_IN_SET($id,property_id)")->andWhere(['parent_id'=>$idp])->andWhere('lease_end_date > "'.date('Y-m-d H:i:s').'" AND status = 1')->one();
      if($beds){
      return $beds->lease_end_date  ;
      } else {
      return $date;
      }
      }
      }
      }


      return $date  ;
      } else {
      return $date;
      }
      } */

    public function getPropertyRentById($id, $child) {
        $date = '';
        $beds = \app\models\TenantAgreements::find()->where("property_id = '" . $child . "'")->andWhere(['parent_id' => $id])->andWhere('lease_end_date > "' . date('Y-m-d H:i:s') . '" AND status = 1')->one();
        if ($beds) {
            return $beds;
        } else {
            return $date;
        }
    }

    public function getPropertyAttrById($id) {
        $type = \app\models\Properties::findOne(['id' => $id]);
        if ($type->property_type == '3') {
            $listing = \app\models\PropertyListing::findOne(['property_id' => $id]);
            return $listing->token_amount;
        } else {
            return 'co';
        }
    }

    public function getPropertyTokenAmountById($id) {
        $prop = \app\models\Properties::findOne(['id' => $id]);
        if ($prop) {
            $listing = \app\models\PropertyListing::findOne(['property_id' => $id]);
            if ($listing->token_amount > 0) {
                return $listing->token_amount;
            }
        }
        return false;
    }

    public function getPropertyRentListing($id) {
        $type = \app\models\Properties::findOne(['id' => $id]);
        if ($type->property_type == '3') {
            $listing = \app\models\PropertyListing::findOne(['property_id' => $id]);
        } else if ($type->property_type == 4) {
            $sql = (' SELECT cpl.* FROM child_property_listing cpl JOIN child_properties cp ON cpl.child_id = cp.id WHERE cpl.main = ' . $id . ' AND cpl.rent != 0 AND cpl.availability_from <= "' . Date('Y-m-d') . '" AND cp.status = 1 AND cp.type = 2 ORDER BY cpl.rent ASC ');
            $minimum_rent = \Yii::$app->db->createCommand($sql)->queryOne();
            $rowData = $minimum_rent;
            if (count($minimum_rent) == '0') {
                $minimum_rent = 'NA';
            } else {
                $minimum_rent = $minimum_rent['rent'];
            }

            $minimum_maintenance = $rowData;
            if (count($minimum_maintenance) == '0') {
                $minimum_maintenance = 'included';
            } else {
                $minimum_maintenance = $minimum_maintenance['maintenance'];
            }

            $minimum_token = $rowData;
            if (count($minimum_token) == '0') {
                $minimum_token = 'NA';
            } else {
                $minimum_token = $minimum_token['token_amount'];
            }

            $minimum_deposit = $rowData;
            if (count($minimum_deposit) == '0') {
                $minimum_deposit = 'NA';
            } else {
                $minimum_deposit = $minimum_deposit['deposit'];
            }

            $listing = (object) Array('rent' => $minimum_rent, 'maintenance' => $minimum_maintenance, 'deposit' => $minimum_deposit, 'token' => $minimum_token);
        }  else {
            $sql = (' SELECT cpl.* FROM child_property_listing cpl JOIN child_properties cp ON cpl.child_id = cp.id WHERE cpl.main = ' . $id . ' AND cpl.rent != 0 AND cpl.availability_from <= "' . Date('Y-m-d') . '" AND cp.status = 1 AND cp.type = 1 ORDER BY cpl.rent ASC ');
            $minimum_rent = \Yii::$app->db->createCommand($sql)->queryOne();
            $rowData = $minimum_rent;
            if (count($minimum_rent) == '0') {
                $minimum_rent = 'NA';
            } else {
                $minimum_rent = $minimum_rent['rent'];
            }

            $minimum_maintenance = $rowData;
            if (count($minimum_maintenance) == '0') {
                $minimum_maintenance = 'included';
            } else {
                $minimum_maintenance = $minimum_maintenance['maintenance'];
            }

            $minimum_token = $rowData;
            if (count($minimum_token) == '0') {
                $minimum_token = 'NA';
            } else {
                $minimum_token = $minimum_token['token_amount'];
            }

            $minimum_deposit = $rowData;
            if (count($minimum_deposit) == '0') {
                $minimum_deposit = 'NA';
            } else {
                $minimum_deposit = $minimum_deposit['deposit'];
            }

            $listing = (object) Array('rent' => $minimum_rent, 'maintenance' => $minimum_maintenance, 'deposit' => $minimum_deposit, 'token' => $minimum_token);
        }

        return $listing;
    }

    public function extraCharges($pel, $rent, $days) {
        $amount = 0;
        $amount = ($rent * $pel / 100 ) * $days;
        return $amount;
    }

    public function getPropertyAvailabilityRooms($id, $child) {
        $date = '';
        $beds = \app\models\TenantAgreements::find()->where("FIND_IN_SET($child,property_id)")->andWhere(['parent_id' => $id])->andWhere('lease_end_date > "' . date('Y-m-d H:i:s') . '" AND status = 1')->one();
        if ($beds) {
            return $beds->lease_end_date;
        } else {
            return $date;
        }
    }

    public function checkParent($id) {
        $pro = \app\models\ChildProperties::findOne($id);
        if ($pro) {
            return $pro->type;
        } else {
            return false;
        }
    }

    public function getPropertyByPidHome($id) {
        $pro = \app\models\FavouriteProperties::find()->where(['property_id' => $id])->one();
        //print_r($id); exit;
        if ($pro) {
            return $pro->status;
            if ($pro->status == 1) {
                return 1;
            } else {
                return 2;
            }
        } else {
            return 3;
        }
    }

    public function getPropertyByPidBeds($id) {
        $implodeData = [];
        $beds = \app\models\TenantAgreements::find(['property_id'])->where(['parent_id' => $id])->andWhere('lease_end_date > "' . date('Y-m-d H:i:s') . '" AND status = 1')->all();
        //$beds2 = FavouriteProperties::find(['property_id'])->where(['property_id'=>$id])->andwhere('status=2')->all();
        // if($beds || $beds2){
        if ($beds) {
            foreach ($beds as $bed) {
                $ids = explode(',', $bed->property_id);
                foreach ($ids as $id) {
                    if ($this->checkParent($id) == 1) {
                        $implodeData[] = $id;
                        $childs = \app\models\ChildProperties::find()->where(['parent' => $id])->all();
                        foreach ($childs as $child) {
                            $implodeData[] = $child->id;
                        }
                    } else if ($this->checkParent($id) == 2) {
                        $implodeData[] = $id;
                    }
                }
            }

            // foreach ($beds2 as $bed2) {
            //  $ids2 = explode(',', $bed2->child_properties ) ;
            //   foreach( $ids2 as  $id2 ){
            //       if($this->checkParent($id2) == 1  ){
            //          $implodeData[] = $id2;
            //          $childs = \app\models\ChildProperties::find()->where(['parent'=> $id2 ])->all();
            //          foreach( $childs as  $child2 ){
            //            $implodeData[] = $child2->id ;
            //          }
            //       } elseif( $this->checkParent($id2) == 2 ) {
            //        $implodeData[] = $id2 ;
            //       }
            //   }
            // }
            return $implodeData;
        } else {
            return $implodeData;
        }
    }

    public function getPropertyByPidBeds2($id) {
        $implodeData = [];
        $beds = \app\models\TenantAgreements::find(['property_id'])->where(['parent_id' => $id])->andWhere('lease_end_date > "' . date('Y-m-d H:i:s') . '" AND status = 1')->all();
        if ($beds) {
            foreach ($beds as $bed) {
                $ids = explode(',', $bed->property_id);
                foreach ($ids as $id) {
                    if ($this->checkParent($id) == 1) {
                        $implodeData[] = $id;
                        $childs = \app\models\ChildProperties::find()->where(['parent' => $id])->all();
                        foreach ($childs as $child) {
                            $implodeData[] = $child->id;
                        }
                    } else if ($this->checkParent($id) == 2) {
                        //$implodeData[] = $id;
                    }
                }
            }
            return $implodeData;
        } else {
            return $implodeData;
        }
    }

    public function getPropertyByPidRooms($id) {
        $implodeData = [];
        $implodeData1 = [];

        $beds = \app\models\TenantAgreements::find()->where(['parent_id' => $id])->andWhere('lease_end_date > "' . date('Y-m-d H:i:s') . '" AND status = 1')->all();

        $beds2 = \app\models\FavouriteProperties::find()->where(['property_id' => $id])->andWhere('status = 2')->all();
        $beds3 = \app\models\ChildProperties::find()->where(['main' => $id])->andWhere('status = 0')->all();
        if ($beds3) {
            foreach ($beds3 as $bed3) {
                $ids3 = explode(',', $bed3->id);
                foreach ($ids3 as $id3) {
                    if ($this->checkParent($id3) == 1) {
                        $implodeData[] = $id3;
                    } else if ($this->checkParent($id3) == 2) {
                        $child = \app\models\ChildProperties::findOne($id3);
                        $implodeData[] = $child->parent;
                    }
                }
            }
        }
        if ($beds2) {
            foreach ($beds2 as $bed2) {
                $ids2 = explode(',', $bed2->child_properties);
                foreach ($ids2 as $id2) {
                    if ($this->checkParent($id2) == 1) {
                        $implodeData[] = $id2;
                    } else if ($this->checkParent($id2) == 2) {
                        $child = \app\models\ChildProperties::findOne($id2);
                        $implodeData[] = $child->parent;
                    }
                }
            }
        }
        if ($beds) {
            foreach ($beds as $bed) {
                $ids = explode(',', $bed->property_id);
                foreach ($ids as $id) {
                    if ($this->checkParent($id) == 1) {
                        $implodeData[] = $id;
                    } else if ($this->checkParent($id) == 2) {
                        $child = \app\models\ChildProperties::findOne($id);
                        $implodeData[] = $child->parent;
                    }
                }
            }

            return $implodeData;
        } else {
            return $implodeData;
        }
    }

    public function getRoomsForPg ($id) {
        $rooms = \app\models\ChildProperties::find()->where(['main' => $id])->andWhere('status = 1')->andWhere('type = 1')->all();
        if ($rooms) {
            return $rooms;
        } else {
            return [];
        }
    }
    
    public function getRoomsForColive ($id) {
        $parentIds = [];
        $getRooms = [];
        $rooms = \app\models\ChildProperties::find()->where(['main' => $id])->andWhere('status = 1')->andWhere('type = 1')->all();
        if ($rooms) {
            foreach ($rooms as $room) {
                $totalBeds = \app\models\ChildProperties::find()->where(['parent' => $room->id])->andWhere('type = 2')->all();
                $totalBedCount = count($totalBeds);
                $activeBeds = \app\models\ChildProperties::find()->where(['parent' => $room->id])->andWhere('status = 1')->andWhere('type = 2')->all();
                $totalActiveBedCount = count($activeBeds);
                if ($totalBedCount == $totalActiveBedCount) {
                    $parentIds[] = $room->id;
                }
            }

            foreach ($parentIds as $parentId) {
                $getRooms[] = \app\models\ChildProperties::find()->where(['id' => $parentId])->one();
            }
            
            return $getRooms;
        } else {
            return [];
        }
    }
    
    public function getBedsForColive ($id) {
        $getBeds = [];
        $rooms = \app\models\ChildProperties::find()->where(['main' => $id])->andWhere('status = 1')->andWhere('type = 1')->all();
        if (!empty($rooms)) {
            foreach ($rooms as $room) {
                $beds = \app\models\ChildProperties::find()->where(['parent' => $room->id])->andWhere('status = 1')->andWhere('type = 2')->all();
                if (!empty($beds)) {
                    foreach ($beds as $bed) {
                        $getBeds[] = $bed;
                    }
                }
            }
            return $getBeds;
        } else {
            return [];
        }
    }
    
    public function getLowestRentRoomPg ($rooms) {
        $roomRents = [];
        if (!empty($rooms)) {
            foreach ($rooms as $room) {
                $data = \app\models\ChildPropertiesListing::find()->where(['child_id' => $room->id])->one();
                $roomRents[$room->id] = $data->rent;
            }
        }
        
        asort($roomRents);
        foreach ($roomRents as $key => $value) {
            $roomCharges = $this->getRoomCharges($key);
            break;
        }
        
        return $roomCharges;
    }
    
    public function getLowestRentRoomColive ($rooms) {
        $roomRents = [];
        if (!empty($rooms)) {
            foreach ($rooms as $room) {
                $data = \app\models\ChildPropertiesListing::find()->where(['child_id' => $room->id])->one();
                $roomRents[$room->id] = $data->rent;
            }
        }
        
        asort($roomRents);
        foreach ($roomRents as $key => $value) {
            $roomCharges = $this->getRoomCharges($key);
            break;
        }
        
        return $roomCharges;
    }

    public function getMinPenalty() {
        $model = \app\models\PmsPropertyConfigurations::find()->one();
        if ($model) {
            $data = $model->min_penalty;
            return $data;
        } else {
            return false;
        }
    }

    public function getChildIdForFlat($id) {
        $childProperties = \app\models\ChildProperties::find()->where(['main' => $id])->one();
        if ($childProperties) {
            return $childProperties->id;
        } else {
            return false;
        }
    }

    public function getUserNameByProperty($id) {
        $modelProperties = Properties::findOne($id);
        if ($modelProperties) {
            $model = Users::findOne(['id' => $modelProperties->owner_id]);
            if ($model) {
                return $model->full_name;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getCodeByProperty($id) {
        $modelProperties = Properties::findOne($id);
        if ($modelProperties) {
            return $modelProperties->property_code;
        } else {
            return false;
        }
    }

    public function getAddressById($id) {
        $modelTenant = \app\models\TenantProfile::find()->where(['tenant_id' => $id])->one();
        $model = \app\models\ApplicantProfile::find()->where(['applicant_id' => $id])->one();
        return $model ? $model->address_line_1 . ' ' . $model->address_line_2 : ($modelTenant ? $modelTenant->address_line_1 . ' ' . $modelTenant->address_line_2 : '');
    }

    public function getCompleteAddressById($id) {
        $modelTenant = \app\models\TenantProfile::find()->where(['tenant_id' => $id])->one();
        $model = \app\models\ApplicantProfile::find()->where(['applicant_id' => $id])->one();
        if ($model) {
            return $model->address_line_1 . ', ' . $model->address_line_2 . ", " . $this->getCityName($model->city) . ", " . $this->getStateName(($model->state)) . ", " . $model->pincode;
        }
    }

    public function getEmployerNameById($id) {
        $modelTenant = \app\models\TenantProfile::find()->where(['tenant_id' => $id])->one();
        $model = \app\models\ApplicantProfile::find()->where(['applicant_id' => $id])->one();
        return $model ? $model->employer_name : ($modelTenant ? $modelTenant->employer_name : '');
    }

    public function getEmployeeIdById($id) {
        $modelTenant = \app\models\TenantProfile::find()->where(['tenant_id' => $id])->one();
        $model = \app\models\ApplicantProfile::find()->where(['applicant_id' => $id])->one();
        return $model ? $model->employee_id : ($modelTenant ? $modelTenant->employee_id : '');
    }

    public function getEmployementById($id) {
        $modelTenant = \app\models\TenantProfile::find()->where(['tenant_id' => $id])->one();
        $model = \app\models\ApplicantProfile::find()->where(['applicant_id' => $id])->one();
        return $model ? $model->employment_start_date : ($modelTenant ? $modelTenant->employment_start_date : '');
    }

    public function getImageById($id) {
        $modelTenant = \app\models\TenantProfile::find()->where(['tenant_id' => $id])->one();
        $model = \app\models\ApplicantProfile::find()->where(['applicant_id' => $id])->one();
        //return $model ? $model->profile_image : ($modelTenant ? $modelTenant->profile_image : '');
        if (isset($modelTenant)) {
            return $modelTenant->profile_image;
        } else {
            return '';
        }
    }

    public function getAddressByProperty($id) {
        $modelProperties = PropertiesAddress::find()->where(['property_id' => $id])->one();
        //  print_r($modelProperties);die;
        if ($modelProperties) {
            return $modelProperties->address;
        } else {
            return false;
        }
    }

    public function getRoomRent($id, $rent) {
        /* $totol = 0 ;
          $properties = \app\models\ChildProperties::find()->where(['parent'=>$id])->all() ;
          if($properties){
          $allchilds=Array();
          foreach($properties as $key=>$value){
          $allchilds[]=$value->id;
          }
          $childPropertieList=\app\models\ChildPropertiesListing::find()->where('child_id IN ('.implode(",",$allchilds).')')->All();
          if(count($childPropertieList)!=0){
          foreach($childPropertieList as $key=>$value){
          $totol=$totol+$value->rent;
          }
          return $totol;
          }
          else{
          return $rent * count($properties)     ;
          }

          } else {
          return $totol ;
          } */

        $childPropertieList = \app\models\ChildPropertiesListing::find()->where(['child_id' => $id])->one();
        if (count($childPropertieList) != '0') {
            return $childPropertieList->rent;
        } else {
            return '0';
        }
    }

    public function getPropertyAvailabilityDate($id) {
        $childPropertieList = \app\models\ChildPropertiesListing::find()->where(['child_id' => $id])->one();
        if (count($childPropertieList) != 0) {
            return $childPropertieList->availability_from;
        } else {
            return "";
        }
    }

    public function getStatusByProperty($id) {

        $bookings = Bookings::find()->where(['property_id' => $id])->all();
        $properties = Properties::findOne($id);

        $type = $properties->property_type;
        $rooms = $properties->no_of_rooms;
        $beds = $properties->no_of_beds;

        $left = 0;

        if ($bookings) {
            foreach ($bookings as $booking) {
                if ($booking->type_of_booking == '1') {
                    $rooms = $rooms - $booking->booking_count;
                    $left = $rooms;
                } elseif ($booking->type_of_booking == '2') {

                    $beds = $beds - $booking->booking_count;
                    $left = $beds;
                } else {
                    $left = 0;
                }
            }

            return $left;
        } else {
            $left = '';
            return $left;
        }
    }

    public function getBookingDate($id) {
        $model = Bookings::findOne(['property_id' => $id, 'tenant_id' => Yii::$app->user->id]);
        return $model->move_in;
    }

    public function getMoveinDate($id) {
        $model = Bookings::findOne($id);
        return $model->move_in;
    }

    public function getExitDate($id) {
        $model = Bookings::findOne($id);
        return $model->exit_date;
    }

    public function getTotalRentByPid($id, $tenant_id) {
        $total = 0;
        $payments = TenantPayments::find()->where(['property_id' => "$id", 'tenant_id' => $tenant_id, 'payment_status' => 1])->all();
        foreach ($payments as $payment) {
            $total += $payment->total_amount;
        }
        return $total;
    }

    public function getTotalRoomsInProperty($id) {
        $total = 0;
        $payments = \app\models\ChildProperties::find()->where(['main' => "$id", 'type' => 1])->all();
        return count($payments);
    }

    public function getTotalRentByPidPMS($id, $email) {
        $total = 0;
        $payments = TenantPayments::find()->where(['property_id' => $id, 'email_id' => "$email"])->all();
        foreach ($payments as $payment) {
            $total += $payment->payment_amount;
        }
        return $total * 25 / 100;
    }

    public function getTotalRentByPidPMSAdviser($id, $email, $count) {
        $total = 0;
        $payments = TenantPayments::find()->where(['property_id' => $id, 'email_id' => "$email"])->all();
        foreach ($payments as $payment) {
            $total += $payment->payment_amount;
        }
        $total = $total * 25 / 100;
        $txt = 0;
        if ($count > 0 || $count <= 5) {
            $txt = 10;
        } elseif ($count > 5 || $count <= 25) {
            $txt = 15;
        } elseif ($count > 26 || $count <= 50) {
            $txt = 20;
        } elseif ($count > 50 || $count <= 100) {
            $txt = 25;
        } else {
            $txt = 35;
        }
        return $total * $txt / 100;
    }

    public function getTotalRentByPidPMSAdviserTenant($id, $email, $count) {
        $total = 0;
        $payments = TenantPayments::find()->where(['property_id' => $id, 'email_id' => "$email"])->all();
        foreach ($payments as $payment) {
            $total += $payment->payment_amount;
        }
        $total = $total * 25 / 100;
        $txt = 0;
        if ($count > 0 || $count <= 25) {
            $txt = 2;
        } elseif ($count > 25 || $count <= 100) {
            $txt = 4;
        } elseif ($count > 100 || $count <= 500) {
            $txt = 7.35;
        } else {
            $txt = 10;
        }
        return $total * $txt / 100;
    }

    public function getSlab($count) {
        $txt = '';
        if ($count > 0 || $count <= 5) {
            $txt = '1 to 5';
        } elseif ($count > 5 || $count <= 25) {
            $txt = '6 to 25';
        } elseif ($count > 26 || $count <= 50) {
            $txt = '26 to 50';
        } elseif ($count > 50 || $count <= 100) {
            $txt = '51 to 100';
        } else {
            $txt = 'greater than 100';
        }

        return $txt;
    }

    public function getSlabTenant($count) {
        $txt = '';
        if ($count > 0 || $count <= 25) {
            $txt = '0 to 25';
        } elseif ($count > 25 || $count <= 100) {
            $txt = '26 to 100';
        } elseif ($count > 100 || $count <= 500) {
            $txt = '101 to 500';
        } elseif ($count > 500 || $count <= 1000) {
            $txt = '501 to 1000';
        } else {
            $txt = 'greater than 1000';
        }

        return $txt;
    }

    public function getAgreement($id) {
        $model = \app\models\TenantAgreements::find()->where(['property_id' => $id])->one();
        return $model;
    }

    public function getReferredBy($id) {

        $model = \app\models\LeadsOwner::find()->where(['email_id' => $id])->one();
        if ($model)
            return $model->reffered_by;
    }

    public function getReferredByTenant($id) {

        $model = \app\models\LeadsTenant::find()->where(['email_id' => $id])->one();
        if ($model)
            return $model->reffered_by;
    }

    public function getAgreementByPk($id) {

        $model = \app\models\TenantAgreements::findOne($id);
        return $model;
    }

    public function getPropertyNameById($id) {
        if (!empty($id)) {
            $property = Properties::findOne($id);
            if ($property) {
                return $property->property_name;
            }
        }
    }
    
    public function getUnitNumberByPropertyId($id) {
        if (!empty($id)) {
            $property = Properties::findOne($id);
            if ($property) {
                return $property->unit;
            }
        }
    }

    public function getSalesFullNameById($type, $id) {
        if ($type == 2) {
            $uid = $this->getUserIdByEmail($id);
            $model = \app\models\ApplicantProfile::find()->where(['applicant_id' => $uid])->one();
            return $model ? $model->sales_id : '';
        } else if ($type == 4) {
            $uid = $this->getUserIdByEmail($id);
            $model = \app\models\OwnerProfile::find()->where(['owner_id' => $uid])->one();
            return $model ? $model->sales_id : '';
        } else if ($type == 5) {
            $uid = $this->getUserIdByEmail($id);
            $model = \app\models\AdvisorProfile::find()->where(['advisor_id' => $uid])->one();
            return $model ? $model->sales_id : '';
        }
    }

    public function getSalesEmailById($id) {
        $uid = $this->getUserIdByEmail($id);
        $model = \app\models\ApplicantProfile::find()->where(['applicant_id' => $uid])->one();
        $modelTenant = \app\models\TenantProfile::find()->where(['tenant_id' => $uid])->one();
        $sales = 0;
        if ($model) {
            $sales = $model->sales_id;
        } else {
            $sales = $modelTenant->sales_id;
        }
        if ($sales == 0) {
            return "";
        } else {
            return $this->getUserEmailById($model->sales_id);
        }
    }

    public function getPropertyNameByIds($id) {
        $tenantAgreements = \app\models\TenantAgreements::find()->where(['property_id' => $id])->one();
        if ($tenantAgreements) {

            $property = $this->getPropertyNameById($tenantAgreements->parent_id);
            return $property;
        } else {
            return false;
        }
    }

    public function getOwnerIdByProperty($id) {
        $tenantAgreements = \app\models\Properties::find()->where(['id' => $id])->one();
        if ($tenantAgreements) {

            // $owner =  \app\models\Users::findOne(['id'=>$tenantAgreements->owner_id);
            return $tenantAgreements->owner_id;
        } else {
            return false;
        }
    }

    public function getOperationsDetailByUserId($id) {
        $user = \app\models\Users::findOne(['id' => $id]);
        if ($user->user_type == '2') {
            $operations = \app\models\ApplicantProfile::findOne(['applicant_id' => $id]);
        }
        if ($user->user_type == '3') {
            $operations = \app\models\TenantProfile::findOne(['tenant_id' => $id]);
        }
        if ($user->user_type == '4') {
            $operations = \app\models\OwnerProfile::findOne(['owner_id' => $id]);
        }
        if ($user->user_type == '5') {
            $operations = \app\models\AdvisorProfile::findOne(['advisor_id' => $id]);
        }
        if (count($operations) == '0') {
            return Array('name' => '', 'email' => '', 'phone' => '');
        } else {
            $operationDetail = \app\models\Users::findOne(['id' => $operations->operation_id]);
            if (count($operationDetail) == 0) {
                return Array('name' => '', 'email' => '', 'phone' => '');
            } else {
                $return = Array('name' => $operationDetail->full_name, 'email' => $operationDetail->login_id, 'phone' => '');
                $phone = \app\models\OperationsProfile::findOne(['operations_id' => $operations->operation_id]);
                if (count($phone) != 0) {
                    $return['phone'] = $phone->phone;
                    return $return;
                }
            }
        }
    }

    public function getSalesDetailByUserId($id) {
        $user = \app\models\Users::findOne(['id' => $id]);

        if ($user->user_type == '2') {
            $operations = \app\models\ApplicantProfile::findOne(['applicant_id' => $id]);
        }
        if ($user->user_type == '3') {
            $operations = \app\models\TenantProfile::findOne(['tenant_id' => $id]);
        }
        if ($user->user_type == '4') {
            $operations = \app\models\OwnerProfile::findOne(['owner_id' => $id]);
        }
        if ($user->user_type == '5') {
            $operations = \app\models\AdvisorProfile::findOne(['advisor_id' => $id]);
        }
        if (count($operations) == '0') {
            return Array('name' => '', 'email' => '', 'phone' => '');
        } else {
            $operationDetail = \app\models\Users::findOne(['id' => $operations->sales_id]);
            if (count($operationDetail) == 0) {
                return Array('name' => '', 'email' => '', 'phone' => '');
            } else {
                $return = Array('name' => $operationDetail->full_name, 'email' => $operationDetail->login_id, 'phone' => '');
                $phone = \app\models\SalesProfile::findOne(['sale_id' => $operations->sales_id]);
                if (count($phone) != 0) {
                    $return['phone'] = $phone->phone;
                    return $return;
                }
            }
        }
    }

    public function getProfileImageById($id, $type) {

        // $model =  \app\models\ApplicantProfile::find()->where(['applicant_id'=>$id])->one();
        // echo '<pre>'; print_r($model); die;
        // return $model->phone;
        if ($type == '2') {
            $model = \app\models\ApplicantProfile::find()->where(['applicant_id' => $id])->one();
            return $model ? $model->profile_image : '';
        } else if ($type == '3') {
            $model = \app\models\TenantProfile::find()->where(['tenant_id' => $id])->one();
            return $model ? $model->profile_image : '';
        } else if ($type == '4') {
            $model = \app\models\OwnerProfile::find()->where(['owner_id' => $id])->one();
            return $model ? $model->profile_image : '';
        } else if ($type == '5') {
            $model = \app\models\AdvisorProfile::find()->where(['advisor_id' => $id])->one();

            return $model ? $model->profile_image : '';
        } else if ($type == '6') {
            $model = \app\models\SalesProfile::find()->where(['sale_id' => $id])->one();
            return $model ? $model->profile_image : '';
        } else if ($type == '7') {
            $model = \app\models\OperationsProfile::find()->where(['operations_id' => $id])->one();
            return $model ? $model->profile_image : '';
        } else if ($type == '8') {
            $model = \app\models\OperationsProfile::find()->where(['operations_id' => $id])->one();
            return $model ? $model->profile_image : '';
        } else if ($type == '1') {
            $model = \app\models\AdminProfile::find()->where(['admin_id' => $id])->one();
            return '';
        } else {
            return $model;
        }
    }

    public function getPhoneNumberById($id, $type) {
        // $model =  \app\models\ApplicantProfile::find()->where(['applicant_id'=>$id])->one();
        // echo '<pre>'; print_r($model); die;
        // return $model->phone;
        if ($type == '2') {
            $model = \app\models\ApplicantProfile::find()->where(['applicant_id' => $id])->one();
            return $model ? $model->phone : '';
        } else if ($type == '3') {
            $model = \app\models\TenantProfile::find()->where(['tenant_id' => $id])->one();
            return $model ? $model->phone : '';
        } else if ($type == '4') {
            $model = \app\models\OwnerProfile::find()->where(['owner_id' => $id])->one();
            return $model ? $model->phone : '';
        } else if ($type == '5') {
            $model = \app\models\AdvisorProfile::find()->where(['advisor_id' => $id])->one();

            return $model ? $model->phone : '';
        } else if ($type == '1') {
            $model = \app\models\AdminProfile::find()->where(['admin_id' => $id])->one();

            return $model ? $model->phone : '';
        } else if ($type == '6') {
            $model = \app\models\SalesProfile::find()->where(['sale_id' => $id])->one();

            return $model ? $model->phone : '';
        } else if ($type == '7') {
            $model = \app\models\OperationsProfile::find()->where(['operations_id' => $id])->one();

            return $model ? $model->phone : '';
        } else {
            return $model;
        }
    }

    public function getPhoneById($id, $type) {
        if ($type == '6') {
            $model = \app\models\SalesProfile::find()->where(['sale_id' => $id])->one();
            return $model ? $model->phone : '';
        } else if ($type == '7') {
            $model = \app\models\OperationsProfile::find()->where(['operations_id' => $id])->one();
            return $model ? $model->phone : '';
        } else if ($type == '3') {
            $model = \app\models\ApplicantProfile::find()->where(['applicant_id' => $id])->one();
            return $model ? $model->phone : '';
        } else {
            return '';
        }
    }

    public function getPropertyType($id) {
        $modelProperties = Properties::findOne($id);
        if ($modelProperties) {
            $model = PropertyTypes::findOne($modelProperties->property_type);
            return $model->property_type_name;
        } else {
            return false;
        }
    }

    public function getPropertyNum($id) {
        $modelProperties = Properties::findOne($id);
        if ($modelProperties) {
            return $modelProperties->property_type;
        } else {
            return false;
        }
    }

    public function getExcludedProperties() {
        $booked_properties = \app\models\FavouriteProperties::find()->select('property_id,child_properties,type')->where(['status' => '2'])->all();

        $excluded_properties = Array();
        $properties_parent = Array();
        $child_properties = "";
        $array_properties = Array();
        $beds_get = Array();
        foreach ($booked_properties as $key => $value) {
            if ($value['type'] == 1 || $value["type"] == 2) {
                if (trim($value['child_properties']) != '') {
                    $checkRooms = \app\models\ChildProperties::find()->where('id IN (' . $value['child_properties'] . ')')->all();
                    if (count($checkRooms) != 0) {
                        foreach ($checkRooms as $key1 => $value1) {
                            if ($value1['sub_parent'] == 1 && $value1['type'] == 1) {
                                $beds = \app\models\childProperties::find()->where(['parent' => $value1['id']])->andWhere(['type' => 2])->all();
                                foreach ($beds as $key3 => $value3) {
                                    $beds_get[] = $value3['id'];
                                }
                            } else {
                                $beds_get[] = $value1['id'];
                            }
                            # code...
                            $array_properties[] = $value['property_id'];
                        }
                    }
                }
            } else {
                $excluded_properties[] = $value['property_id'];
            }
        }

        $tenant_agreements_rooms = \app\models\TenantAgreements::find()->all();
        foreach ($tenant_agreements_rooms as $key => $value) {
            if ($value['property_type'] == 1 || $value["property_type"] == 2) {
                if (trim($value['property_id']) != '') {
                    $checkRooms = \app\models\ChildProperties::find()->where('id IN (' . $value['property_id'] . ')')->all();
                    foreach ($checkRooms as $key1 => $value1) {
                        if ($value1['sub_parent'] == 1 && $value1['type'] == 1) {
                            $beds = \app\models\childProperties::find()->where(['parent' => $value1['id']])->andWhere(['type' => 2])->all();
                            foreach ($beds as $key3 => $value3) {
                                $beds_get[] = $value3['id'];
                            }
                        } else {
                            $beds_get[] = $value1['id'];
                        }
                        # code...
                        $array_properties[] = $value['parent_id'];
                    }
                }
            } else {
                $excluded_properties[] = $value['parent_id'];
            }
        }

        if (count($array_properties) != 0) {
            $array_properties_list = implode(",", array_unique($array_properties));


            $allchilds = \app\models\ChildProperties::find()->where('main IN (' . $array_properties_list . ')')->andWhere(['type' => 2])->all();
            foreach ($allchilds as $key => $value) {
                $properties_parent[] = $value['id'];
            }
            $beds_get = array_unique($beds_get);
        } else {
            $beds_get = Array();
        }

        $included_properties = implode(",", array_unique(array_diff($properties_parent, $beds_get)));
        $array_properties1 = Array();
        if (count(array_unique(array_diff($properties_parent, $beds_get))) != 0) {
            $discover_parent_properties = \app\models\ChildProperties::find()->select('main')->where('id IN (' . $included_properties . ')')->all();

            foreach ($discover_parent_properties as $key => $value) {
                $array_properties1[] = $value['main'];
            }
        }


        $excluded_properties1 = array_diff($array_properties, $array_properties1);
        return array_unique(array_merge($excluded_properties1, $excluded_properties));
    }

    public function getPropertyTypeId($id) {
        $modelProperties = Properties::findOne($id);
        if ($modelProperties) {
            // $model =  PropertyTypes::findOne($modelProperties->property_type);
            return $modelProperties->property_type;
        } else {
            return false;
        }
    }

    public function getPropertyTypeByPropertyId($id) {
        $modelProperties = Properties::findOne($id);
        $modelProperties = PropertyTypes::findOne(['id' => $modelProperties->property_type]);
        if ($modelProperties) {
            return $modelProperties->property_type_name;
        } else {
            return false;
        }
    }

    public function getPropertyBook($id, $type) {
        if ($type == 3) {
            $modelAgreements = \app\models\TenantAgreements::find()->where(['parent_id' => $id])->andWhere('lease_end_date > NOW()')->all();
            if (count($modelAgreements) == 0) {
                return false;
            } else {
                return true;
            }
        } else {
            $modelAgreements = \app\models\TenantAgreements::find()->where(['parent_id' => $id])->andWhere('lease_end_date > NOW()')->all();
            $allproperties = Array();
            if (count($modelAgreements) == 0) {
                return false;
            } else {
                foreach ($modelAgreements as $key => $value) {
                    $allproperties[] = $value['property_id'];
                }
                $list = implode(",", $allproperties);
                $newList = explode(",", $list);
                $list1 = \app\models\ChildProperties::find()->select('id')->where('parent IN (' . $list . ')')->andWhere(['sub_parent' => '0'])->all();
                if (count($list1) != 0) {
                    foreach ($list1 as $key => $value) {
                        $newList[] = $value->id;
                    }
                }
                $newList = array_unique($newList);
                $allList = Array();
                $allchild = \app\models\ChildProperties::find()->select('id')->where(['main' => $id])->andWhere(['sub_parent' => '0'])->andWhere('type != 0')->all();
                foreach ($allchild as $key => $value) {
                    $allList[] = $value->id;
                }

                if (count(array_diff($allList, $newList)) == 0) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }

    public function getPropertyBookedStatus($id) {
        $modelAgreements = \app\models\TenantAgreements::find()->where(['parent_id' => $id])->andWhere('lease_end_date > NOW()')->all();
        if (count($modelAgreements) == 0) {
            $modelBooked = \app\models\PropertyVisits::find()->where(['property_id' => $id])->all();
            if (count($modelBooked) == 0) {
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }

    public function checkAllActiveProperties($id) {
        $properties = \app\models\Properties::findAll(['owner_id' => $id]);

        $propertyList = Array();
        $status = 0;
        foreach ($properties as $key => $value) {
            $propertyList[] = $value->id;
            if ($value->status == 1) {
                $status = 1;
            }
        }

        if (count($propertyList) != 0 && $status == 0) {
            $active = \app\models\ChildProperties::find()->where(['status' => '1'])->andWhere('main IN (' . implode(",", $propertyList) . ')')->num_rows();
            if ($active == 0) {
                return false;
            } else {
                return true;
            }
        } else {
            if ($status == 0) {
                return false;
            } else {
                return true;
            }
        }
    }

    public function getOwnerStatusByProperty($id) {

        $ownerData = \app\models\Properties::findOne(['id' => $id]);
        $userStatus = \app\models\Users::findOne(['id' => $ownerData->owner_id]);
        return $userStatus->status;
    }

    public function getPropertyStausByPropertyId($id, $type) {
        if ($type == 1) {
            $property = \app\models\Properties::findOne(['id' => $id]);
        } else {
            $property = \app\models\ChildProperties::findOne(['id' => $id]);
        }

        if ($property->status == '0') {
            return 'Unlisted';
        } elseif ($property->status == '1') {
            return 'Listed';
        } else {
            return '';
        }
    }

    public function getPropertyStausByPropertyId2($id, $type) {
        if ($type == 1) {
            $property = \app\models\Properties::findOne(['id' => $id]);
        } else {
            $property = \app\models\ChildProperties::findOne(['id' => $id]);
        }
        
        if ($property) {
            return $property->status;
        }
    }

    public function getOwnerAllPropertyStatus($id) {
        $booked = 0;
        $properties = \app\models\Properties::findAll(['owner_id' => $id]);

        $propertyList = Array();
        foreach ($properties as $key => $value) {
            if ($value->status == 1) {
                $booked = 1;
            }
            $propertyList[] = $value->id;
        }

        if (count($propertyList) != 0) {

            $modelAgreements = \app\models\TenantAgreements::find()->where('parent_id IN (' . implode(",", $propertyList) . ')')->andWhere('lease_end_date > NOW()')->all();

            if (count($modelAgreements) != 0) {
                $booked = 1;
            }

            $modelBooked = \app\models\PropertyVisits::find()->where('property_id IN (' . implode(",", $propertyList) . ')')->all();
            if (count($modelBooked) != 0) {
                $booked = 1;
            }
        }

        if ($booked == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function deactivateallproperty($id) {
        $properties = \app\models\Properties::findAll(['owner_id' => $id]);

        $propertyList = Array();
        foreach ($properties as $key => $value) {
            $propertyListing = \app\models\PropertyListing::findOne(['id' => $value->id]);
            $propertyListing->status = 0;
            $propertyListing->save(false);
            $propertyList[] = $value->id;
            $value->status = 0;
            $value->save(false);
        }
        if (count($propertyList) != 0) {
            $childPropertyListing = \app\models\ChildProperties::find()->Where('main IN (' . implode(",", $propertyList) . ')')->all();

            foreach ($childPropertyListing as $key1 => $value1) {
                $value1->status = 0;
                $value1->save(false);
            }
        }
    }

    public function getPropertyTypeById($id) {
        $modelProperties = PropertyTypes::findOne($id);

        if ($modelProperties) {
            // $model =  PropertyTypes::findOne($modelProperties->property_type);
            return $modelProperties->property_type_name;
        } else {
            return false;
        }
    }

    public function getFacilityValue($attr, $property) {
        $modelAttribute = \app\models\propertyAttributeMap::find()->select('value')->where(['attr_id' => $attr])->andWhere(['property_id' => $property])->one();
        return $modelAttribute->value;
    }

    public function getPropertyRent($id) {
        $modelProperties = Properties::findOne($id);
        if ($modelProperties) {
            return $modelProperties->rent;
        } else {
            return false;
        }
    }

    public function getRequestType($id) {
        $request = \app\models\RequestType::findOne(['id' => $id]);
        return $request->name;
    }

    public function getPropertyLeaseType($id) {
        $lease = \app\models\PropertyAgreements::findOne(['property_id' => $id]);
        if (count($lease) != 0) {
            $name = \app\models\AgreementType::findOne(['id' => $lease->agreement_type]);
            return $name->agreement_type;
        } else {
            return '<span style="color:red">No Agreement Is There</span>';
        }
    }

    public function getAllProofs($id) {
        $address_proofs = \app\models\UserIdProofs::find()->where(['user_id' => $id])->all();
        $emergency_proofs = \app\models\EmergencyProofs::find()->where(['user_id' => $id])->all();
        return Array('address' => $address_proofs, 'emergency' => $emergency_proofs);
    }

    public function getUserTypeByUserId($id) {
        $userType = \app\models\Users::find()->select('user_type')->where(['id' => $id])->one();
        $userTypeName = \app\models\UserTypes::find()->select('user_type_name')->where(['id' => $userType->user_type])->one();
        return $userTypeName->user_type_name;
    }
    
    public function getUserTypeIdByUserId($id) {
        $userType = \app\models\Users::find()->select('user_type')->where(['id' => $id])->one();
        return $userType->user_type;
    }

    public function getRequestTypeName($id) {
        $data = \app\models\RequestType::findOne(['id' => $id]);
        return $data->name;
    }

    public function getStatusNameById($id) {
        $data = \app\models\RequestStatus::findOne(['id' => $id]);
        if (count($data) != 0) {
            return $data->name;
        } else {
            return '';
        }
    }

    public function getAssignedSalesPerson($id) {
        $userType = \app\models\Users::find()->select('user_type')->where(['id' => $id])->one();
        switch ($userType->user_type) {
            case 2:
                $operation_id = \app\models\ApplicantProfile::find()->select('operation_id')->where(['applicant_id' => $id])->one();
                break;
            case 3:
                $operation_id = \app\models\TenantProfile::find()->select('operation_id')->where(['tenant_id' => $id])->one();
                break;
            case 4:
                $operation_id = \app\models\OwnerProfile::find()->select('operation_id')->where(['owner_id' => $id])->one();
                break;
            case 5:
                $operation_id = \app\models\AdvisorProfile::find()->select('operation_id')->where(['advisor_id' => $id])->one();
                break;
            case 7:
                return Yii::$app->userdata->getFullNameById($id);
                break;
            default:
                return '';
                break;
        }
        if (isset($operation_id->operation_id)) {
            return Yii::$app->userdata->getFullNameById($operation_id->operation_id);
        } else {
            return '';
        }
    }

    public function getPropertyPriceBeds($id) {
        $childPropertieList = \app\models\ChildPropertiesListing::find()->where(['child_id' => $id])->one();
        if (count($childPropertieList) != 0) {
            return $childPropertieList->rent;
        } else {
            return '2';
        }
    }
    
    public function getMinTokenForRooms($id) {
        $data = [];
        $rooms = \app\models\ChildProperties::find()->where(['main' => $id])->andWhere('status = 1')->andWhere('type = 1')->all();
        if ($rooms) {
            foreach ($rooms as $key => $room) {
                $childPropertieList = \app\models\ChildPropertiesListing::find()->where(['parent_id' => $room->id])->one();
                $data[$key]['room_id'] = $room->id;
                $data[$key]['token_amount'] = $childPropertieList->token_amount;
            }
            return $data;
        } else {
            return [];
        }
    }

    public function getRoomTotal($id, $main) {
        $childPropertieList = \app\models\ChildPropertiesListing::find()->where(['child_id' => $id])->all();
        if (count($childPropertieList) != 0) {
            $deposite = 0;
            $token_amount = 0;
            $rent = 0;
            $maintenance = 0;
            foreach ($childPropertieList as $key => $value) {
                $deposite = $deposite + $value->deposit;
                $token_amount = $token_amount + $value->token_amount;
                $rent = $rent + $value->rent;
                $maintenance = $maintenance + $value->maintenance;
            }
        } else {
            $count = count(explode(",", $id));
            $propertyListing = \app\models\PropertyListing::findOne(['property_id' => $main]);
            $deposite = $count * $propertyListing->deposit;
            $token_amount = $count * $propertyListing->token_amount;
            $rent = $count * $propertyListing->rent;
            $maintenance = $count + $propertyListing->maintenance;
        }
        return json_encode(Array('rent' => $rent, 'deposit' => $deposite, 'token_amount' => $token_amount, 'maintenance' => $maintenance));
    }

    public function getBedTotal($id, $main) {
        $arraychild = Array();
        $deposite = 0;
        $token_amount = 0;
        $rent = 0;
        $maintenance = 0;
        if ($id != '') {
            $childPropertieList = \app\models\ChildPropertiesListing::find()->where('child_id IN (' . $id . ')')->all();
            if (count($childPropertieList) != 0) {
                foreach ($childPropertieList as $key => $value) {
                    $deposite = $deposite + $value->deposit;
                    $token_amount = $token_amount + $value->token_amount;
                    $rent = $rent + $value->rent;
                    $maintenance = $maintenance + $value->maintenance;
                }
            }
        }

        return json_encode(Array('rent' => $rent, 'deposit' => $deposite, 'token_amount' => $token_amount));
    }
    
    public function getBedCount($id) {
        $bedCount = 0;
        if ($id != '') {
            $childPropertieList = \app\models\ChildProperties::find()->where(['parent' => $id])->andWhere(['type' => 2])->all();
            $bedCount = count($childPropertieList);
        }

        return $bedCount;
    }

    public function getRentBreakUpColiv($id) {
        $count = \app\models\ChildPropertiesListing::findOne(['child_id' => $id]);
        $data = [];
        $data['property_id'] = $count->main;
        $data['room_id'] = $count->parent_id;
        $data['bed_id'] = $count->child_id;
        $data['rent'] = $count->rent;
        $data['deposit'] = $count->deposit;
        $data['token_amount'] = $count->token_amount;
        $data['maintenance'] = $count->maintenance;
        $data['avail_from'] = $count->availability_from;
        return $data;
    }

    public function getRentBreakUpColivForRoom($id) {
        $count = \app\models\ChildPropertiesListing::findOne(['child_id' => $id]);
        $data = [];
        $data['property_id'] = $count->main;
        $data['room_id'] = $count->parent_id;
        $data['bed_id'] = $count->child_id;
        $data['rent'] = $count->rent;
        $data['deposit'] = $count->deposit;
        $data['token_amount'] = $count->token_amount;
        $row = \app\models\ChildPropertiesListing::find()->select('MAX(availability_from) AS availability_from')->where(['parent_id' => $id])->one();
        $data['maintenance'] = $count->maintenance;
        $data['avail_from'] = @$row->availability_from;
        return $data;
    }
    
    public function getRoomCharges($id) {
        $count = \app\models\ChildPropertiesListing::findOne(['child_id' => $id]);
        $data = [];
        $data['id'] = $count->child_id;
        $data['rent'] = $count->rent;
        $data['deposit'] = $count->deposit;
        $data['token_amount'] = $count->token_amount;
        $data['maintenance'] = ($count->maintenance == 0) ? 'Included' : '&#8377;'. $this->getFormattedMoney($count->maintenance);
        return $data;
    }

    public function getAvailDateForRoom($id) {
        $row = \app\models\ChildPropertiesListing::find()->select('MAX(availability_from) AS availability_from')->where(['parent_id' => $id])->one();
        if (!empty($row)) {
            return $row->availability_from;
        } else {
            return false;
        }
    }

    public function getMaintenanceIncluded($id) {
        $count = \app\models\PropertyListing::findOne(['property_id' => $id]);
        return $count->maintenance_included;
    }

    public function getUserStatusById($id) {
        $user = \app\models\Users::findOne(['id' => $id]);
        return $user->status;
    }

    public function getChildPropertyType($id) {

        $type = \app\models\ChildProperties::findOne(['id' => $id]);
        if (count($type) != 0) {
            return $type->type;
        } else {
            return "";
        }
    }

    public function getAvailChildPropertyList($id, $main) {

        $type = \app\models\ChildPropertiesListing::findOne(['child_id' => $id, 'main' => $main]);
        if (count($type) != 0) {
            return $type->availability_from;
        } else {
            return "";
        }
    }

    public function getChildPropertyTypeWithActiveStatus($id) {

        $type = \app\models\ChildProperties::findOne(['id' => $id, 'status' => 1]);
        if (count($type) != 0) {
            return $type->type;
        } else {
            return "";
        }
    }

    public function getChildPropertyTypeWithActiveStatus2($id) {

        $type = \app\models\ChildProperties::findOne(['id' => $id, 'type' => 1]);
        if (count($type) != 0) {
            return $type->type;
        } else {
            return "";
        }
    }

    public function getApplicantWalletAmount($id) {
        $amount = \app\models\Wallets::findOne(['user_id' => $id]);
        if (count($amount) != 0) {
            return $amount->amount;
        } else {
            return '0';
        }
    }

    public function doMail($to, $subject, $msg) {
        $tos = explode(',', $to);
        if (count($tos) > 0) {
            foreach ($tos as $val) {
                Yii::$app->mailer->compose()
                        ->setFrom(Yii::$app->params['supportEmail'])
                        ->setTo($val)
                        ->setSubject($subject)
                        ->setHtmlBody($msg)
                        ->send();
            }
        }

        return 1;
    }

    public function doMailWithAttachment($to, $subject, $msg, $files) {
        $tos = explode(',', $to);
        $attachments = explode(',', $files);
        if (count($tos) > 0) {
            foreach ($tos as $val) {
                //Yii::$app->mailer->compose()
                //->setFrom(Yii::$app->params['supportEmail'])
                //->setTo($val)
                //->setSubject($subject)
                //->setTextBody($msg)
                //->setHtmlBody($msg)
                //->send();

                $messsage = Yii::$app->mailer->compose();
                $messsage->setFrom(Yii::$app->params['supportEmail']);
                $messsage->setTo($val);
                $messsage->setSubject($subject);
                $messsage->setHtmlBody($msg);

                if (count($attachments) > 0) {
                    foreach ($attachments as $attachment) {
                        $messsage->attach($attachment);
                    }
                }
            }

            $messsage->send();
        }

        return 1;
    }

    public function doMailWithRole($to, $cc, $bcc, $subject, $msg, $files) {
        $tos = explode(',', $to);
        //$attachments = explode(',', $files);

        if (count($tos) >= 1) {
            $messsage = Yii::$app->mailer->compose();
            $messsage->setFrom(Yii::$app->params['supportEmail']);
            $messsage->setTo($tos);
            $ccs = explode(',', $cc);
            if (count($ccs) > 0) {
                if (!empty($ccs[0])) {
                    $messsage->setCc($ccs);
                }
            }
            $bccs = explode(',', $bcc);
            if (count($bccs) > 0) {
                if (!empty($bccs[0])) {
                    $messsage->setBcc($bccs);
                }
            }
            $messsage->setSubject($subject);
            $messsage->setHtmlBody($msg);

            if (count($files) > 0) {
                foreach ($files as $file) {
                    $messsage->attach($file);
                }
            }

            $messsage->send();
        }

        return 1;
    }

    public function doMailWithCC($to, $cc, $bcc, $subject, $msg, $files) {
        $tos = explode(',', $to);
        if (count($tos) >= 1) {
            $messsage = Yii::$app->mailer->compose();
            $messsage->setFrom(Yii::$app->params['supportEmail']);
            $messsage->setTo($tos);
            $ccs = explode(',', $cc);
            if (count($ccs) > 0) {
                if (!empty($ccs[0])) {
                    $messsage->setCc($ccs);
                }
            }
            $bccs = explode(',', $bcc);
            if (count($bccs) > 0) {
                if (!empty($bccs[0])) {
                    $messsage->setBcc($bccs);
                }
            }
            $messsage->setSubject($subject);
            $messsage->setHtmlBody($msg);

            if (count($files) > 0) {
                foreach ($files as $file) {
                    $messsage->attach($file);
                }
            }

            $messsage->send();
        }

        return 1;
    }

    public function doMail1($to, $subject, $msg) {
        $from = Yii::$app->params['supportEmail'];
        /* $s = curl_init();
          curl_setopt($s,CURLOPT_URL,'112.196.17.29:3003');
          curl_setopt($s,CURLOPT_RETURNTRANSFER,true);
          curl_setopt($s,CURLOPT_FOLLOWLOCATION,true);
          curl_setopt($s,CURLOPT_POST,true);
          curl_setopt($s, CURLOPT_POSTFIELDS, 'from='.$from.'&to='.$to.'&subject='.$subject.'&msg='.urlencode($msg));
          $return = curl_exec($s);
          curl_close($s);
          return $return; */

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: Easyleases Team<' . $from . '>' . "\r\n";
        mail($to, $subject, $msg, $headers);
        return 1;
    }

    public function getRefStatusById($id) {
        $ref_status = \app\models\RefStatus::findOne(['id' => $id]);
        if (count($ref_status) != 0) {
            return $ref_status->name;
        } else {
            return "";
        }
    }

    public function getRefStatus($id) {
        $ref_status = \app\models\RefStatus::findOne(['id' => $id]);
        if (count($ref_status) != 0) {
            return $ref_status->name;
        } else {
            return "";
        }
    }

    /*     * **************************************************************************** Cron Job ***************************************************************************************** */

    public function getPMSProfit($owner_amount, $pms_commission) {
        $gatewaycharges = 0.01 * (float) $owner_amount;
        $pmsfees = (((float) $owner_amount * (float) $pms_commission) / 100) - $gatewaycharges;
        return $pmsfees;
    }

    /*     * ******************************************************************************* End ******************************************************************************************* */

    public function checkvalid($action, $user_type) {

        $common_array = Array(
            '',
            'users/login',
            'users/signup',
            'site/logout',
            'users/owner',
            'users/advisor',
            'site/contact',
            'site/login',
            'site/forget',
            'users/register',
            'site/search',
            'site/view',
            'site/bookinginfo',
            'site/payment',
            'site/completepayment',
            'site/visit',
            'users/changepassword',
            'site/addfav',
            'site/changepassword',
            'site/calculateownerpayments',
            'site/penaltycalculator',
            'site/mpdfdemo1',
            'users/mailcheck',
            'site/checkfavs',
            'site/addvisit',
            'site/tenantpenaltycalculate',
            'site/deactivatetenants',
            'site/view',
            'site/advisorpaymentcalculate',
            'site/page'
        );
        $allowed_array = Array(
            '1' => Array(
            ),
            '2' => Array(
                'site/myprofile',
                'site/editprofile',
                'site/wallet',
                'site/myfavlist',
            ),
            '3' => Array(
                'site/myprofile',
                'site/dashboard',
                'site/editprofiletenant',
                'site/myagreements',
                'site/mypayments',
                'site/mypaymentsdue',
                'site/mypaymentsforcast',
                'site/createrequests',
                'site/myrequests',
                'site/requesttenantdetail'
            ),
            '4' => Array(
                'site/mydashboard',
                'site/myprofile',
                'site/editowner',
                'site/ownerpayments',
                'site/tenantpayments',
                'site/ownerlease',
                'site/mytenants',
                'site/tenantdetails',
                'site/ownerleads',
                'site/ownerrequests',
                'site/getrequestforapproval',
                'site/requestdetail',
            ),
            '5' => Array(
                'advisers/myprofile',
                'advisers/editprofile',
                'advisers/owner',
                'advisers/applicant',
                'advisers/myreferred',
                'advisers/ownerreferred',
                'advisers/tenantreferred',
                'advisers/feesdetails',
            ),
            '6' => Array(
                'external/index',
                'external',
                'external/myprofile',
                'external/applicants',
                'external/applicantsdetails',
                'external/owners',
                'external/ownerssdetails',
                'external/advisors',
                'external/advisersdetails',
                'external/hotleads',
                'external/myaccount',
                'external/editproperty',
                'external/addproperty',
                'external/propertieslisting',
                'external/propertylistingdetails',
                'external/editlisting',
                'external/createowner'
            ),
            '7' => Array(
                'external/index',
                'external',
                'external/myprofile',
                'external/applicants',
                'external/applicantsdetails',
                'external/owners',
                'external/ownerssdetails',
                'external/advisors',
                'external/advisersdetails',
                'external/editproperty',
                'external/addproperty',
                'external/propertieslisting',
                'external/servicerequests',
                'external/servicedetail',
                'external/createservice',
                'external/adhocrequests',
                'external/owner',
                'external/propertylistingdetails',
                'external/tenants',
                'external/properties',
                'external/adviser',
                'external/adviserdetails',
                'external/requestmaintenanceapproval',
                'external/tenantdetails',
                'external/editlisting',
                'external/createowner',
                'external/walletmanagement'
            ),
            '8' => array(
                '8' => 'external/pgitxnlist',
            ),
            '9' => array(
                '9' => 'investor/index',
            )
        );

        $default_array = Array(
            '1' => 'admin/index',
            '2' => 'site/myprofile',
            '3' => 'site/myprofile',
            '4' => 'site/mydashboard',
            '5' => 'advisers/myprofile',
            '6' => 'external/index',
            '7' => 'external/index',
            '8' => 'external/pgitxnlist',
            '9' => 'investor/index',
        );

        $get_array = array_merge($common_array, $allowed_array[$user_type]);
        if (!in_array($action, $get_array)) {
            return Array('status' => 0, 'action' => '../' . $default_array[$user_type]);
        }
    }

    public function getDefaultUrl() {
        return '/pmstest/web/site/login';
    }

    public function getPmsCommission($id) {
        $commission = \app\models\OwnerPayments::find()->where("payment_id like '%$id%'")->one();
        return $commission;
    }

    public function getPmsCommissionPercentage($owner_id, $property_id) {
        $commission = \app\models\PropertyAgreements::find()->where(["owner_id" => $owner_id, 'property_id' => $property_id])->one();
        return $commission;
    }

    public function getPaymentModeType($id) {
        $payment = \app\models\PaymentModeType::findOne(['id' => $id]);
        if (count($payment) == 1) {
            return $payment->name;
        } else {
            return "";
        }
    }
    
    public function getPaymentModeId($name) {
        $payment = \app\models\PaymentModeType::findOne(['name' => $name]);
        if (count($payment) == 1) {
            return $payment->id;
        } else {
            return "";
        }
    }

    public function getListToArray($string) {
        return explode(",", substr(substr($string, 1), 0, strlen(substr($string, 1)) - 1));
    }

    public function getPerTenantComission($string, $tenant) {
        $listId = implode(",", $this->getListToArray($string));
        $allPayments = \app\models\TenantPayments::find()->where("id IN ($listId)")->all();
        $total = 0;
        $totalPer = 0;
        foreach ($allPayments as $key => $value) {
            $total += $value->total_amount;
        }

        $perPayments = \app\models\TenantPayments::find()->where("id IN ($listId)")->andWhere(['tenant_id' => $tenant])->all();
        foreach ($perPayments as $key2 => $value2) {
            $totalPer += $value->total_amount;
        }

        return $totalPer / $total;
    }

    public function getPropertyAgreementType($id) {
        $apps = \app\models\PropertyAgreements::findOne(['property_id' => $id]);
        if (count($apps) == 0) {
            return 1;
        } else {
            return $apps->agreement_type;
        }
    }

    public function getIFSCValidate($ifsc) {

        $headers = get_headers('https://ifsc.razorpay.com/' . $ifsc);
        $headers = substr($headers[0], 9, 3);
        if ($headers != 200) {
            return "error";
        } else {
            return "no";
        }
        // $ifsc = json_decode(file_get_contents('https://ifsc-api.herokuapp.com/'.$ifsc));
        // return $ifsc;
        // if(file_get_contents('https://ifsc-api.herokuapp.com/'.$ifsc)){
        // return "no";
        // }
        // else{
        // return "error";
        // }
    }

    public function getListingStatus($id) {
        $listing = \app\models\PropertyListing::findOne(['property_id' => $id]);
        if (count($listing) == 0) {
            return "";
        } else {
            return $listing->status;
        }
    }

    public function getRefStatusByUserId($user_id, $type) {

        if ($type == '2') {
            $leads = \app\models\LeadsTenant::findOne(['email_id' => $user_id]);
            // return $leads->ref_status;
        } else if ($type == '4') {
            $leads = \app\models\LeadsOwner::findOne(['email_id' => $user_id]);
        } else if ($type == '5') {
            $leads = \app\models\LeadsAdvisor::findOne(['email_id' => $user_id]);
        } else {
            $leads = Array();
        }

        if (count($leads) != 0) {
            return $this->getRefStatus($leads->ref_status);
        } else {
            return "";
        }
    }

    public function getSalesRemark($user_id, $property_id) {

        $salesPerson = \app\models\Users::findAll(['user_type' => 6]);
        $sales_array = Array();
        foreach ($salesPerson as $key => $value) {
            $sales_array[] = $value->id;
        }
        $salesList = implode(",", $sales_array);
        $comment = \app\models\Comments::find()->where('created_by IN (' . $salesList . ')')->andWhere(['user_id' => $user_id, 'property_id' => $property_id])->one();

        if (count($comment) == '0') {
            return "";
        } else {
            return $comment->description;
        }
    }

    public function getPropertyAddrres($id) {
        $property = \app\models\Properties::findOne(['id' => $id]);
        $address = "";
        if (trim($property->address_line_1) != '') {
            $address = $property->address_line_1;
        }
        if (trim($property->address_line_2) != '') {
            if (trim($address) == '') {
                $address = $property->address_line_2;
            } else {
                $address = $address . ", " . $property->address_line_2;
            }
        }
        if (trim($property->city) != '' && $property->city != '0') {
            $address = $address . ", " . $this->getCityname($property->city);
        }
        if (trim($property->state) != '' && $property->state != '0') {
            $address = $address . ", " . $this->getStatename($property->state);
        }
        if (trim($property->region) != '' && $property->region != '0') {
            $address = $address . ", " . $this->getregionname($property->region);
        }
        if (trim($property->pincode) != '' && $property->pincode != '0') {
            $address = $address . ", " . $property->pincode;
        }
        return $address;
    }

    public function getFooter() {

        $links = \app\models\Pages::findBySql("SELECT * FROM pages WHERE status = 1 ORDER BY footer_order")->all();
        $list = "";
        foreach ($links as $key => $value) {

            if ($value->slug != 'contact-us') {
                $list .= "<div class='col-md-4 col-sm-4  col-xs-6 text-set'><a href='" . Url::home(true) . "" . $value->slug . "' target='_blank'>" . $value->title . "</a></div>";
            } else {
                $list .= "<div class='col-md-4 col-sm-4  col-xs-6 text-set'><a href='" . Url::home(true) . "" . 'contact' . "' target='_blank'>" . $value->title . "</a></div>";
            }
        }
        echo $list;
    }

    public function getSocialMediaLinks() {

        $links = \app\models\Pages::findBySql("SELECT * FROM pages WHERE slug = 'social-media-links' ")->one();

        echo $links->description;
    }

    public function getChildPropertyMaintenance($id) {
        $maintenance = \app\models\ChildPropertiesListing::find()->where(['child_id' => $id])->one();
        if (count($maintenance) == 0) {
            return '0';
        } else {
            return $maintenance['maintenance'];
        }
    }

    public function getUserTypeByTypeId($id) {
        if (!empty($id)) {
            $userTypeName = \app\models\UserTypes::find()->select('user_type_name')->where(['id' => $id])->one();
            return $userTypeName->user_type_name;
        }
    }

    public function getUserNameById($id) {
        if (!empty($id)) {
            $model = Users::findOne(['id' => $id]);
            if ($model) {
                return $model->full_name;
            }
        }
    }

    public function getEmailById($id) {
        if (!empty($id)) {
            $model = Users::findOne(['id' => $id]);
            if ($model) {
                return $model->login_id;
            }
        }
    }
    
    public function getTenantPhoneById($id) {
        if (!empty($id)) {
            $model = Users::findOne(['id' => $id]);
            if ($model) {
                return $model->phone;
            }
        }
    }
    
    public function getEntityTypeName($name) {
        if (!empty($name)) {
            $model = \app\models\EntityTypes::findOne(['name' => $name]);
            if ($model) {
                return $model->name;
            }
        }
    }

    public function getTenantPaymentDescByTpId($id) {
        if (!empty($id)) {
            $model = TenantPayments::findOne(['id' => $id]);
            if ($model) {
                return $model->payment_des;
            }
        }
    }

    public function getPropertyTypeName($id) {

        $model = PropertyTypes::findOne($id);
        if ($model) {
            return $model->property_type_name;
        } else {
            return false;
        }
    }

    public function generateCustomerId($id) {
        // return md5('EL' . '@' . $id);
        return $id;
    }
    
    public function generateOrderId($id) {
        return time() . 'EU' . $id . '@' . mt_rand(1111111111, mt_getrandmax());
    }

    public function statusModerationForColivProp() {
        
    }

    public function getAgreementTypeName($id) {
        $model = \app\models\AgreementType::find()->where(['id' => $id])->one();
        return $model->agreement_type;
    }

    public function getTenantState($tenantid) {

        $modeltenant = \app\models\TenantProfile::findOne(['tenant_id' => $tenantid]);
        if ($modeltenant) {
            $sid = $modeltenant->state;
        } else {
            $sid = NULL;
        }

        $name = '';
        if ($sid != NULL) {
            $model = \app\models\States::find()->where(['code' => $sid])->one();
            $name = $model->name;
        }
        return $name;
    }

    public function getTenantCity($tenantid) {

        $modeltenant = \app\models\TenantProfile::findOne(['tenant_id' => $tenantid]);
        if ($modeltenant) {
            $cid = $modeltenant->city;
        } else {
            $cid = NULL;
        }
        $name = '';
        if ($cid != NULL) {

            $model = \app\models\Cities::findOne($cid);
            $name = $model->city_name;
        }
        return $name;
    }

    public function getAddress1($id) {
        $modelTenant = \app\models\TenantProfile::find()->where(['tenant_id' => $id])->one();
        if ($modelTenant) {
            return $modelTenant->address_line_1;
        }
    }

    public function getAddress2($id) {
        $modelTenant = \app\models\TenantProfile::find()->where(['tenant_id' => $id])->one();

        if ($modelTenant) {
            return $modelTenant->address_line_2;
        }
    }

    public function getPincode($id) {
        $modelTenant = \app\models\TenantProfile::find()->where(['tenant_id' => $id])->one();
        if ($modelTenant) {
            return $modelTenant->pincode;
        }
    }

    public function getformat($number) {
        if (PHP_OS != 'WINNT') {
            setlocale(LC_MONETARY, "en_IN.UTF-8");
            return money_format("%!.0n", $number);
        } else {
            return number_format($number);
        }
    }

    public function getFormattedMoney($number) {
        if (PHP_OS != 'WINNT') {
            setlocale(LC_MONETARY, "en_IN.UTF-8");
            return money_format("%!.0n", $number);
        } else {
            return number_format($number);
        }
    }

    public function getManagedByString($val) {
        $model = \app\models\ManagedBy::find()->where(['id' => $val])->one();
        if ($model) {
            return $model->name;
        } else {
            return 'Easyleases';
        }
    }

    public function getManagedById($val) {
        $model = \app\models\ManagedBy::find()->where(['id' => $val])->one();
        if ($model) {
            return $model->id;
        } else {
            return 2;
        }
    }

    public function getAvailableForString($val) {
        $model = \app\models\PropertyGender::find()->where(['id' => $val])->one();
        if ($model) {
            return $model->name;
        } else {
            return 'Family';
        }
    }

    public function getAvailableForId($val) {
        $model = \app\models\PropertyGender::find()->where(['id' => $val])->one();
        if ($model) {
            return $model->id;
        } else {
            return 1;
        }
    }

    public function getPropertyObjById($val) {
        $model = \app\models\Properties::find()->where(['id' => $val])->one();
        if ($model) {
            return $model;
        } else {
            return 1;
        }
    }

    public function deletefavFromConsole($favId, $status = 2) {

        $id = $favId;
        $type = $status;
        $properties = FavouriteProperties::findOne(['id' => $id]);

        $user_id = $this->getUserIdByEmail($properties->applicant_id);

        $childID = 0;

        if (!empty($properties)) {

            $transaction = Yii::$app->db->beginTransaction();
            echo PHP_EOL;
            echo "Begin Transaction - ID" . $id . PHP_EOL;
            try {

                $property = \app\models\PropertyListing::findOne(['property_id' => $properties->property_id]);
                $property->status = '1';
                if (!$property->save(false)) {
                    throw new Exception('Exception');
                }

                $childId = 0;

                if ($properties->child_properties != null && $properties->child_properties != '' && $properties->child_properties != 0) {
                    $childProperty = \app\models\ChildProperties::findOne(['id' => $properties->child_properties]);
                    $childId = $childProperty->id;
                    $childProperty->status = 1;
                    if (!$childProperty->save(false)) {
                        throw new Exception('Exception');
                    }
                }

                echo "Saved properties listing status = 1" . PHP_EOL;

                $property_id = $properties->property_id;

                $walletHistory = \app\models\WalletsHistory::find()->where([
                            'user_id' => $user_id,
                            'property_id' => $property_id,
                            'child_id' => $childId,
                            'transaction_type' => 1
                        ])->one();

                if ($walletHistory) {
                    $walletHistory->transaction_type = 2;
                    $walletHistory->operation_type = 1;

                    $amount = $walletHistory->amount;

                    echo "Saved properties status 3" . PHP_EOL;

                    if (!$walletHistory->save(false)) {
                        throw new Exception('Exception');
                    }

                    $walletOper = new \app\models\WalletOperations();
                    $walletOper->user_id = $user_id;
                    $walletOper->created_by = 1;
                    $walletOper->amount = $amount;
                    $walletOper->property_id = $property_id;
                    $walletOper->operation_type = 2;
                    $walletOper->wallet_history_id = $walletHistory->id;
                    if (!$walletOper->save(false)) {
                        throw new Exception('Exception');
                    }

                    echo "Saved properties status 4" . PHP_EOL;

                    $wallet = \app\models\Wallets::find()->where([
                                'user_id' => $user_id
                            ])->one();

                    if ((double) $wallet->amount >= (double) $amount) {
                        $wallet->amount = ((double) $wallet->amount - (double) $amount);
                    }

                    if (!$wallet->save(false)) {
                        throw new Exception('Exception');
                    }

                    echo "Saved properties status 5" . PHP_EOL;

                    $favProp = \app\models\FavouriteProperties::find()->where([
                                'id' => $id
                            ])->one();

                    if (!$favProp->delete(FALSE)) {
                        throw new Exception('Exception');
                    }

                    echo "Saved properties status 6" . PHP_EOL;
                } else {
                    \app\models\FavouriteProperties::findOne(['id' => $id])->delete(FALSE);
                }

                echo "Saved properties status 11" . PHP_EOL;

                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
            }
        }
    }

    public function checkCoProListingStatus($parentId) {
        $model = \app\models\ChildProperties::find()->where(['main' => $parentId, 'type' => '2', 'status' => '1'])->all();
        if (count($model) == 0) {
            $model2 = \app\models\PropertyListing::findOne(['property_id' => $parentId]);
            $model2->status = 0;
            $model2->save(false);
        }
    }
    
    public function checkPgProListingStatus($parentId) {
        $model = \app\models\ChildProperties::find()->where(['main' => $parentId, 'type' => '1', 'status' => '1'])->all();
        if (count($model) == 0) {
            $model2 = \app\models\PropertyListing::findOne(['property_id' => $parentId]);
            $model2->status = 0;
            $model2->save(false);
        }
    }

    public function sendSms($numbers, $message) {
    /*    $smsStatus = \app\models\SystemConfig::find()->where(['name' => 'TEXT-LOCAL-STATUS'])->one();
        if (empty($smsStatus)) {
            throw new \Exception('System config missing for TEXT-LOCAL');
        }
        $status = (int) $smsStatus->value;
        if ($status) {
            $apiKey = urlencode(Yii::$app->params['sms_api_key']);
            $sender = urlencode(Yii::$app->params['sender']);
            $message = rawurlencode($message);
            $numbers = implode(',', $numbers);
            $data = array('apikey' => $apiKey, 'numbers' => $numbers, "sender" => $sender, "message" => $message);
            $ch = curl_init(Yii::$app->params['send_sms_url']);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            //$error = curl_error($ch);
            curl_close($ch);
            $response = json_decode($response);
            
            if (!empty($response) && $response->status == 'success') {
                return true;
            } else {
                return false;
            }
        }
*/
        return true;
    }

    public function getSmsTemplate($numbers, $message) {
        $apiKey = urlencode(Yii::$app->params['sms_api_key']);
        $data = array('apikey' => $apiKey);
        $ch = curl_init(Yii::$app->params['sms_template_url']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response);
        if ($response->status == 'success') {
            return $response->templates;
        } else {
            return false;
        }
    }

    public function getAdvertisementType($adid) {
        $adtypes = \app\models\AdvertisementUserType::find()->where([
                    'advetisement_id' => $adid
                ])->one();
        $advertisementtype = '';
        if ($adtypes) {
            if ($adtypes->tenant == '1') {
                if ($advertisementtype == '') {
                    $advertisementtype = 'Tenant';
                } else {
                    $advertisementtype = $advertisementtype . ',Tenant';
                }
            }
            if ($adtypes->advisor == '1') {
                if ($advertisementtype == '') {
                    $advertisementtype = 'Advisor';
                } else {
                    $advertisementtype = $advertisementtype . ',Advisor';
                }
            }
            if ($adtypes->owner == '1') {
                if ($advertisementtype == '') {
                    $advertisementtype = 'Owner';
                } else {
                    $advertisementtype = $advertisementtype . ',Owner';
                }
            }
        }
        return $advertisementtype;
    }
    
    public function getRoomName ($roomId) {
        $row = \app\models\ChildProperties::find()->where(['id' => $roomId])->one();
        return $row->sub_unit_name;
    }
    
    public function getBedName ($bedId) {
        $row = \app\models\ChildProperties::find()->where(['id' => $bedId])->one();
        if ($row) {
            return $row->sub_unit_name;
        }
    }
    
    public function forcePassChange ($ids) {
        $modelUser = \app\models\Users::find()->where(['id' => $ids])->one();
        $passUpDate = $modelUser->pass_up_date;
        $datetime1 = new \DateTime($passUpDate);
        $today_date = new \DateTime(date('Y-m-d H:i:s'));
        $daysInterval = $datetime1->diff($today_date);
        $daysRemaning = 180 - $daysInterval->days;
        if ($daysRemaning <= 0) {
            return true;
        } else {
            return false;
        }
    }
    
    public function restRequest () {
        $inputJSON = file_get_contents('php://input');
        $inputObj = json_decode($inputJSON);
        return (!empty($inputObj)) ? $inputObj : false;
    }
    
    public function restResponse ($data) {
        $outputJSON = json_encode($data);
        return (!empty($outputJSON)) ? $outputJSON : false;
    }
    
    public function calculatePenalty ($tpId) {
        $tpModel = \app\models\TenantPayments::find()->where(['id' => $tpId])->one();
        if (!empty($tpModel)) {
            $taModel = \app\models\TenantAgreements::find('late_penalty_percent, min_penalty')->where(['tenant_id' => $tpModel->tenant_id])->andWhere(['property_id' => $tpModel->property_id])->one();
            $currentDate = date('Y-m-d');
            $dueDate = date('Y-m-d', strtotime($tpModel->due_date));
            $totalPenalty = 0;
            $penalty = 0;
            $dateDiff = date_diff(date_create($dueDate), date_create($currentDate))->days;
            if (($dateDiff > Yii::$app->params['penalty_waiver']) && ($dueDate < date('Y-m-d'))) {
                $penalty = round(($dateDiff / (date("z", (mktime(0, 0, 0, 12, 31, date('Y')))) + 1)) * ($taModel->late_penalty_percent / 100) * $tpModel->total_amount);
                $totalPenalty = ( $penalty < $taModel->min_penalty ) ? $taModel->min_penalty : $penalty;
                return $totalPenalty;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }
    
    public function requestTenantLeaseExtn($tenantId,$propertyId,$leaseEndDate) {
        try {
            $modelTenantProfile = \app\models\TenantProfile::find('operation_id')->where(['tenant_id' => $tenantId])->one();
            $model = new \app\models\ServiceRequest;
            $model->created_by = $tenantId;
            $model->client_id = $tenantId;
            $model->operated_by = $modelTenantProfile->operation_id;
            $model->status = 1;
            $model->created_date = date('Y-m-d H:i:s'); 
            $model->property_id = $propertyId;
            $model->request_type = 5;
            $model->client_type = 3;
            $model->title = "Lease extension requested";
            $model->description = "Lease agreement expiring on " . $leaseEndDate . ", extension has been requested";
            $model->save(false);
            $lastInsert = $model->id;

            $operationDetails = $this->getOperationsDetailByUserId($tenantId);

            $subject = "Service Request $model->id Created ";
            $name = $this->getUserNameById($tenantId);
            $msg = "Hello " . $operationDetails['name'] . "<br/><br/>" . $name . " has requested for lease agreement extension, a service request no. " . $lastInsert . " has been generated for same. Please get in touch with the client and do the needful.<br/><br/>With Regards,<br/>EasyLeases Team<br/><img src='" . Url::home(true) . "images/property_logo.png' alt=''>";
            $this->doMail($operationDetails['email'], $subject, $msg);
            return "SUCCESS";
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
 
   }
    
       public function requestTenantLeaseTerm($tenantId,$propertyId,$leaseEndDate) {
        try {
            $modelTenantProfile = \app\models\TenantProfile::find('operation_id')->where(['tenant_id' => $tenantId])->one();
            $model = new \app\models\ServiceRequest;
            $model->created_by = $tenantId;
            $model->client_id = $tenantId;
            $model->operated_by = $modelTenantProfile->operation_id;
            $model->status = 1;
            $model->created_date = date('Y-m-d H:i:s');
            $model->property_id = $propertyId;
            $model->request_type = 5;
            $model->client_type = 3;
            $model->title = "Lease termination requested";
            $model->description = "Lease agreement expiring on " . $leaseEndDate . ", termination has been requested";
            $model->save(false);
            $lastInsert = $model->id;

            $operationDetails = $this->getOperationsDetailByUserId($tenantId);

            $subject = "Service Request $model->id Created ";
            $name = $this->getUserNameById($tenantId);
            $msg = "Hello " . $operationDetails['name'] . "<br/><br/>" . $name . " has requested for lease termination, a service request no. " . $lastInsert . " has been generated for same. Please get in touch with the client and do the needful.<br/><br/>With Regards,<br/>EasyLeases Team<br/><img src='" . Url::home(true) . "images/property_logo.png' alt=''>";
            $this->doMail($operationDetails['email'], $subject, $msg);
     
            return "SUCCESS";
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }
       
    public function CreatePaymentReceipt($id,$caller) {
        $payments = \app\models\TenantPayments::findOne(['id' => $id]);
        $property = \app\models\Properties::findOne(['id' => $payments->parent_id]);
        $tenant = \app\models\Users::findOne(['id' => $payments->tenant_id]);
        $owner = \app\models\Users::findOne(['id' => $property->owner_id]);
        $tenantProfile = \app\models\TenantProfile::findOne(['tenant_id' => $payments->tenant_id]);
        $payment_date = Date('d-M-Y', strtotime($payments->payment_date));
        $payment_description = $payments->payment_des;
        $payment_reference_no = $id . '-' . Date('dmY', strtotime($payments->payment_date));
        $payment_type = Yii::$app->userdata->getPaymentModeType($payments->payment_mode);
        $payment_amount = round($payments->amount_paid, 2);

        $panNumber = (!empty(Yii::$app->userdata->getOwnersPan($property->owner_id))) ? Yii::$app->userdata->getOwnersPan($property->owner_id) : 'N/A';

        $html = ' '
                . 'Date: ' . $payment_date . ' <br />'
                . 'Receipt No: ' . $payment_reference_no . ' <br />'
                . '<br />'
                . '<br />'
                . '' . $tenant->full_name . ' <br />'
                . ((!empty($tenantProfile->address_line_1)) ? $tenantProfile->address_line_1.' <br />' : '').''
                . ((!empty($tenantProfile->address_line_2)) ? $tenantProfile->address_line_2.' <br />' : '').''
                . ((!empty($tenantProfile->city_name)) ? $tenantProfile->city_name.' <br />' : '').''
                . '' . Yii::$app->userdata->getStateName($tenantProfile->state) . ' <br />'
                . '' . $tenantProfile->pincode . ' <br />'
                . '<br />'
                . '<br />'
                . '<b>Sub: Payment Received towards ' . $payment_description . '</b> <br />'
                . '<br />'
                . '<br />'
                . 'Received with thanks from ' . $tenant->full_name . ', a sum of Rs. ' . $payment_amount . ' towards ' . $payment_description . ' on ' . $payment_date . ' by "' . $payment_type . '". <br />'
                . '<br />'
                . 'Address of the rented property: <br />'
                . '<br />'
                . ((!empty($property->unit)) ? '# '. $property->unit.' <br />' : '').''
                . ((!empty($property->property_name)) ? $property->property_name.' <br />' : '').''
                . ((!empty($property->address_line_1)) ? $property->address_line_1.' <br />' : '').''
                . ((!empty($property->address_line_2)) ? $property->address_line_2.' <br />' : '').''
                . '' . Yii::$app->userdata->getCityName($property->city) . ' <br />'
                . '' . Yii::$app->userdata->getStateName($property->state) . ' <br />'
                . '' . $property->pincode . ' <br />'
                . '<br />'
                . '<br />'
                . 'With Regards, <br />'
                . 'Easyleases Technologies Pvt. Ltd. on behalf of ' . $owner['full_name'] . ' (PAN no. ' . $panNumber . ') <br />'
        ;
        $fileName = "receipt/Payment_Receipt_" .$id. '.pdf';
        $pdf = $this->PDFReceipt($html, $fileName);
        if ($caller == "API") {
            $pdfFile = fopen($fileName,'w');
            fwrite($pdfFile,$pdf);
            fclose($pdfFile);
            return $fileName;
        } else {
            return $pdf;
        }
    }

    public function PDFReceipt($html, $fileName) {

        try {
            // create an API client instance
            $client = new Pdfcrowd("SAM_15YFA", "c1ff61bd70d577deec0db16940fa8edc");
            $client->usePrintMedia(true);
            $client->setPageWidth("8.5in");
            $client->setPageHeight("11in");
            $client->setVerticalMargin("1.7in");

            $header = '<br />'
                    . '<div style="text-align: right;"><img style="height: 53px; width: 200px;" src="http://www.easyleases.in/images/newlogo1.png" /></div>'
                    . '<center>'
                    . '<span><b>Easyleases Technologies Private Limited</b></span><br>'
                    . '<span>Registered Address: RG-708, Purva Riviera, Varthur Road, Bangalore – 560037</span><br>'
                    . '<span>CIN: U70109KA2017PTC100691</span>'
                    . '</center> <br />'
                    . '<hr style="border-color: black;" /> <br />'
                    . '';
            $client->setHeaderHtml($header);

            $footer = '<br />'
                    . '<br />'
                    . '<br />'
                    . '<div style="text-align: center;">'
                    . '<span>Note: This is a computer-generated receipt and doesn’t require signature. For any queries, please</span> <br />'
                    . '<span>email to suppport@easyleases.in</span> <br />'
                    . '<br />'
                    . '<span style="color: green;">www.easyleases.in</span>';
            $client->setFooterHtml($footer);
            // convert a web page and store the generated PDF into a $pdf variable
            $pdf = $client->convertHtml($html);

            return $pdf;
         } catch (PdfcrowdException $why) {
            echo "Pdfcrowd Error: " . $why;
        }
    }
    
    public function getTinyUrl($url)  {
        
        $currentTime = mt_rand(1111111, 9999999);
        
        $list= [
            'A' => 0,
            'B' => 1,
            'C' => 2,
            'D' => 3,
            'E' => 4,
            'F' => 5,
            'G' => 6,
            'H' => 7,
            'I' => 8,
            'J' => 9
        ];

        $character = "";
        $arr_num = str_split ($currentTime);
        foreach($arr_num as $data) {
            $character .= array_search($data,$list);
        }

        $character = strtolower($character);
        
        $tinyModel = new \app\models\TinyUrl();
        $tinyModel->tiny_url = $character;
        $tinyModel->actual_url = $url;
        $tinyModel->created_date = date('Y-m-d H:i:s');
        $tinyModel->save(false);
        
        return Url::home(true).'t/'.$character;
        
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch,CURLOPT_URL,'http://tinyurl.com/api-create.php?url='.$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
	$data = curl_exec($ch);
        //$error = curl_error($ch);
	curl_close($ch);
	return $data;
    }
    
    public function generatePaymentAlert($tpID)  {
	$ch = curl_init();
	$timeout = 10;
	curl_setopt($ch,CURLOPT_URL, Url::base(). '/paytm/sendtenantpaymentmail/'.$tpID);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
	$data = curl_exec($ch);
        //$error = curl_error($ch);
	curl_close($ch);
	return $data;
    }
    
    public function getExpenseCodeByName($name)  {
	$expModel = \app\models\ExpenseType::find()->where(['expense_name' => $name])->one();
        if (!empty($expModel->expense_code)) {
            return $expModel->expense_code;
        } else {
            return '';
        }
    }
    
    public function getExpenseNameByCode($code)  {
	$expModel = \app\models\ExpenseType::find()->where(['expense_code' => $code])->one();
        if (!empty($expModel->expense_name)) {
            return $expModel->expense_name;
        } else {
            return '';
        }
    }
    
    public function getIncomeCodeByName($name)  {
	$expModel = \app\models\IncomeType::find()->where(['income_name' => $name])->one();
        if (!empty($expModel->income_code)) {
            return $expModel->income_code;
        } else {
            return '';
        }
    }
    
    public function getIncomeNameByCode($code)  {
	$expModel = \app\models\IncomeType::find()->where(['income_code' => $code])->one();
        if (!empty($expModel->income_name)) {
            return $expModel->income_name;
        } else {
            return '';
        }
    }
    
    public function getProfitShareRatio($id)  {
	$model = \app\models\PropertyAgreements::find(['profit_share_ratio'])->where(['property_id' => $id])->one();
        if (!empty($model->profit_share_ratio)) {
            return $model->profit_share_ratio;
        } else {
            return 0;
        }
    }
    
    public function trimPhone($phone) {
        $phoneNumber = str_replace(" ", "", $phone);
        
        if (substr($phoneNumber,0,4) == '0091') {
            return substr($phoneNumber,4);
        } elseif (substr($phoneNumber,0,3) == '+91') {
            return substr($phoneNumber,3);
        }
        elseif (substr($phoneNumber,0,2) == '00') {
                return $phoneNumber; 
        } elseif ((substr($phoneNumber,0,1) == '0') && (strlen($phoneNumber) == 11)) {
            return substr($phoneNumber,1);
        } else return $phoneNumber;
    }
    
    public function autoVersion($file) {
        $file = str_replace('http://', '', $file);
        $file = str_replace('https://', '', $file);
        $file = str_replace($_SERVER['SERVER_NAME'], '', $file);
        $full_file = $file;
        if (strpos($file, '/') !== 0 || !file_exists($full_file)) {
            $full_file = substr($_SERVER['SCRIPT_FILENAME'], 0, -strlen($_SERVER['SCRIPT_NAME']));
            $full_file .= $file;
            if (!file_exists($full_file)) {
                return $file;
            }
        }
        
        $mtime = filemtime($full_file);
        $new_file = preg_replace('{\\.([^./]+)$}', ".\$1".'?v='.$mtime, $file);
        return $new_file;
    }
}
