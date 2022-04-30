<?php
$this->title = 'PMS- Property Leads';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\grid\GridView;

/* echo "<pre>";
  print_r($model);
  die; */
if (isset($_GET['LeadsProperty']['sort'])) {
    $sort = $_GET['LeadsProperty']['sort'];
} else {
    $sort = '';
}
?>
<style>
    .filters{
        display:none ; 
    }
    .pull-right a.btn.savechanges_submit {
        width: auto;
        padding: 2px 13px;
    }
</style>


<div id="page-wrapper">
    <div class="row">
        <?php
        $currProType = Yii::$app->user->identity->user_type;
        $currProId = Yii::$app->user->id;
        if ($currProType == 7) {
            $currProModel = \app\models\OperationsProfile::find()->where(['operations_id' => $currProId])->one();
        } else if ($currProType == 6) {
            $currProModel = \app\models\SalesProfile::find()->where(['sale_id' => $currProId])->one();
        }
        ?>

        <?php if ($currProModel->role_code != 'OPSTMG' && $currProModel->role_code != 'SLSTMG') { ?>
            <div class="col-lg-4 col-sm-9 pull-right">
                <!--<a href="<?php echo Url::home(true); ?>external/createowner" class="btn savechanges_submit" data-toggle="modal" data-target="#myModal" style= "margin-top:15px;">Create Property Owner Lead</a>-->
                <a href="<?php echo Url::home(true); ?>external/createpropertylead" class="btn savechanges_submit" data-toggle="modal" data-target="#myModal" style= "margin-top:15px;">Create Property Lead</a>
            </div>
        <?php } ?>

        <div class="col-lg-12 col-sm-12">
            <div class="col-lg-3 col-sm-6 selectleft"> <a class="btn btn-default btn-select">
                    <input type="hidden" class="btn-select-input" id="" name="" value="" />
                    <span class="btn-select-value">Sort by</span> <span class='btn-select-arrow glyphicon glyphicon-chevron-down'></span>
                    <ul class="sort_ul">
                        <li class="selected">Sort by</li>
                        <li value="users.full_name">Owner name</li>
                        <li value="users.login_id">Owner email</li>
                        <li value="properties.address_line_1">Address</li>
                    </ul>
                </a> </div>

            <div class="col-lg-8 col-sm-5 selectleft">
                <form class="navbar-form navbar-search" action="" method="GET" role="search">
                    <div class="input-group">
                        <?php
                        $search = '';
                        if (isset($_REQUEST['LeadsProperty']['search'])) {
                            $search = $_REQUEST['LeadsProperty']['search'];
                        }
                        ?>
                        <input type="hidden" name="LeadsProperty[sort]" id="sort_val" value="<?php echo $sort; ?>">
                        <input type="text" name="LeadsProperty[search]" class="form-control" value="<?= $search; ?>" placeholder="Search By Owner Name, Owner Email, Owner Phone Number, Address">
                        <div class="input-group-btn">
                            <button type="submit" class="btn btn-search btn-danger selecttext"> <span class="glyphicon glyphicon-search"></span> <span class="label-icon"></span> </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>







        <div class="col-lg-12 col-sm-12">
            <?php if (Yii::$app->session->hasFlash('success')) { ?>
                <div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                    <?= Yii::$app->session->getFlash('success'); ?>
                </div>   
            <?php } ?>

            <?php
            echo GridView::widget([
                'dataProvider' => $dataProviderProperty,
                'filterModel' => $searchModelPropertyLeads,
                'summary' => '',
                'rowOptions' => function ($model, $key, $index, $grid) {
                    $class = 'clickable-owner clickable-row';
                    //return array('key' => $key, 'data-href' => Url::home(true) . 'external/editproperty?id=' . base64_encode($model->property_id), 'class' => $class);
                    return array('key' => $key, 'data-href' => Url::home(true) . 'external/editproperty?id=' . ($model->property_id), 'class' => $class);
                },
                'tableOptions' => ['class' => 'table table-bordered tableheadingrow'],
                'columns' => [
                    [
                        'attribute' => 'leads_property.property_name',
                        'label' => 'Property',
                        'format' => 'html',
                        'content' => function($model) {
                            return ($model->property_name);
                        }
                    ],
                    [
                        'attribute' => 'leads_property.address',
                        'label' => 'Address',
                        'format' => 'html',
                        'content' => function($model) {
                            return ($model->address);
                        }
                    ],
                    [
                        'attribute' => 'leads_property.owner_id',
                        'label' => 'Owner Details',
                        'format' => 'html',
                        'content' => function($model) {
                            $name = Yii::$app->userdata->getFullNameById($model->owner_id);
                            $phone = Yii::$app->userdata->getOwnerPhoneById($model->owner_id);
                            if (!empty($phone)) {
                                $detail = $name . ' [' . $phone . ']';
                            } else {
                                $detail = $name;
                            }
                            return $detail;
                        }
                    ],
                    [
                        'label' => 'Type',
                        'format' => 'html',
                        'content' => function($model) {
                            return Yii::$app->userdata->getPropertyTypeByPropertyId($model->property_id);
                        }
                    ],
                    [
                        'label' => (Yii::$app->user->identity->user_type == 7) ? 'Operation Executive' : 'Sales Executive',
                        'format' => 'html',
                        'content' => function($model) {
                            if (Yii::$app->user->identity->user_type == 7) {
                                $ownerProModel = \app\models\Properties::find(['owner_id' => $model->owner_id])->one();
                                return (empty(Yii::$app->userdata->getLoginIdById($ownerProModel->operations_id))) ? 'N/A' : Yii::$app->userdata->getLoginIdById($ownerProModel->operations_id);
                            } else if (Yii::$app->user->identity->user_type == 6) {
                                $ownerProModel = \app\models\Properties::find(['owner_id' => $model->owner_id])->one();
                                return (empty(Yii::$app->userdata->getLoginIdById($ownerProModel->sales_id))) ? 'N/A' : Yii::$app->userdata->getLoginIdById($ownerProModel->sales_id);
                            }
                        }
                    ],
                    [
                        'label' => 'Status',
                        'format' => 'html',
                        'content' => function($model) {
                            if (Yii::$app->userdata->getPropertyStausByPropertyId2($model->property_id, 1) == 1) {
                                return 'Active';
                            } else if (Yii::$app->userdata->getPropertyStausByPropertyId2($model->property_id, 1) == 0) {
                                return 'Deactive';
                            }
                        }
                    ],
                ]
            ]);
            ?>



        </div>

        <div style="clear:both;"></div>

        <!-- /.col-lg-12 -->
    </div>
</div>





<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modalbg modalbgpopup">

        <!-- Modal content-->
        <div class="modal-content">


        </div>

    </div>
</div>

