<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "wallets".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $amount
 * @property integer $created_by
 * @property integer $updated_by
 * @property string $created_date
 * @property string $updated_date
 */
class Wallets extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'wallets';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['email_id', 'amount'], 'required'],
            [['user_id', 'amount', 'created_by', 'updated_by'], 'integer'],
            [['created_date', 'updated_date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'amount' => 'Amount',
            'created_by' => 'Created by',
            'updated_by' => 'Updated by',
            'created_date' => 'Created date',
            'updated_date' => 'Updated date',
        ];
    }

    public function searchOperation($params) {

        if (isset($params['LeadsTenant']['sort'])) {
            if ($params['LeadsTenant']['sort'] == '') {
                $sort = 'leads_tenant.id desc';
            } else {
                $sort = $params['LeadsTenant']['sort'];
            }
        } else {
            $sort = 'id asc';
        }

        $assinee = \app\models\Wallets::find()
                ->leftJoin('users', 'users.id = user_id')
                ->leftJoin('applicant_profile', 'users.id = applicant_profile.applicant_id');
        $dataProvider = new ActiveDataProvider([
            'query' => $assinee,
            'sort' => false,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);


        /* if (isset($params['LeadsTenant']['search'])) {
          $searchParam = $params['LeadsTenant']['search'];

          // $assinee->andFilterWhere(['or',
          //     ['like','leads_tenant.full_name' , trim($searchParam) ],
          //     ['like','leads_tenant.email_id' , trim($searchParam) ],
          //     ['like','leads_tenant.contact_number' , trim($searchParam) ],
          //     ['like','leads_tenant.address' , trim($searchParam) ],
          //     ]
          // );
          }
          // $assinee->orderBy($sort);
          // print_r($dataProvider); */
        return $dataProvider;
    }

}
