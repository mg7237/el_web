<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "wallets_history".
 *
 * @property integer $id
 * @property string $email_id
 * @property double $amount
 * @property string $transaction_type
 * @property integer $created_by
 * @property integer $updated_by
 * @property string $created_date
 * @property string $updated_date
 */
class WalletsHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'wallets_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['email_id', 'amount', 'transaction_type', 'created_by'], 'required'],
            [['amount'], 'number'],
            [['transaction_type'], 'string'],
            [['created_by', 'updated_by'], 'integer'],
            [['created_date', 'updated_date'], 'safe'],
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
            'amount' => 'Amount',
            'transaction_type' => 'Transaction type',
            'created_by' => 'Created by',
            'updated_by' => 'Updated by',
            'created_date' => 'Created date',
            'updated_date' => 'Updated date',
        ];
    }

    public function searchOperation($params, $user_id) {
        if (isset($params['LeadsTenant']['sort'])) {
            if ($params['LeadsTenant']['sort'] == '') {
                $sort = 'leads_tenant.id desc';
            } else {
                $sort = $params['LeadsTenant']['sort'];
            }
        } else {
            $sort = 'id asc';
        }
        //  $user=\app\models\Wallets::findOne(['id'=>base64_decode($id)]);

        $assinee = \app\models\WalletsHistory::find()
                ->where(['user_id' => $user_id]);
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
