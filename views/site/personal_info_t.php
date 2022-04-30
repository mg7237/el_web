

<?php
$this->title = 'PMS- My profile';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

//$proofsAll = Yii::$app->userdata->getAllProofs($model->applicant_id);
//echo "<pre>";print_r($tenantProfile);echo "</pre>";die;
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<div id="content">
    <button type="button" id="sidebarCollapse" class="btn btn-info navbar-btn">
        <i class="glyphicon glyphicon-align-left"></i>
        <span></span>
    </button>
    <div class="container" style="margin-top: -42px;">
        <div class="col-md-12">
            <h4><?= strtoupper(Html::encode(Yii::$app->userdata->getPropertyNameById($_GET['prop_id']))) ?> - TENANT DETAILS</h4>
        </div>
        <!--div class="col-md-6" style="margin-left: 20px;">
            <button id="first_button" class="btn btn-info btn-lg pull-right editbutton" onclick="makeeditable()">Edit</button>
            <button id="first_button" class="btn btn-info btn-lg pull-right savebutton cus_hide" onclick="saveform()" >Save</button>
            <button id="first_button" class="btn btn-info btn-lg pull-right cancelbutton cus_hide" style="margin-right:5px !important;background-color: #aea1a0 !important;border-color: #aea1a0 !important;" onclick="cancelform()">Cancel</button>
        </div-->
    </div>
    <hr>
    <form method="post" enctype="multipart/form-data" action="<?= \Yii::$app->getUrlManager()->createUrl('site/editpersonalinfo') ?>" name="personal_info" id="personal_info"> 
        <div class="container">
            <div class="container-fluid">
                <h4 id="personal_info" style="margin-bottom: 22px !important;">PERSONAL INFORMATION</h4>
            </div>
            <div class="col-md-10 bgstyle "  >
                <div class="col-sm-3 sidenav">
                    <?php
                    $dataImage = Yii::$app->userdata->getImageById($_REQUEST['user']);
                    if ($dataImage != '') {
                        $imgarr = (explode("/", $dataImage));
                        $filename = Url::home(true) . $dataImage;
                        $headers = get_headers($filename, 1);
                    } else {
                        $filename = '';
                        $headers = array();
                        $headers[0] = '';
                    }
                    //$filename = "http://localhost:8018/pms/web/dfgfh.php";
                    //print_r($headers);die;
                    //echo $filename;die;
                    ?>
                    <input type="file" name="fileupload" value="fileupload" id="fileupload" class="disabled" data-multiple-caption="{count} files selected" multiple="" style="display: none;">
                    <!-- <input type="file" name="fileupload" value="fileupload" id="fileupload" class="cus_hide"> -->
                    <!--img id="van-pill-image" src="<?php echo Url::home(true); ?>images/img/Natasha.png"-->
                    <?php
                    if (isset($imgarr[2]) && $headers[0] == "HTTP/1.1 200 OK") {
                        //echo $filename;die;
                        ?>
                        <img src="<?php echo Url::home(true) . $dataImage; ?>" id="van-pill-image">
                    <?php } else { ?>
                        <img src="<?php echo Url::home(true); ?>uploads/profiles/no-image.png" id="van-pill-image">

                    <?php } ?>
                    <input type="hidden" id="dltimg" class="form-control name1" value="1" name="dltimg">
                    <div class="dlt_img"><a  href="javascript:void(0);"><img src="<?php echo Url::home(true); ?>images/trash.png" height="50%" onclick="deleteprofile();" id="img_delete" class="cus_hide" style="margin: 15px;" ></a></div>
                    <div class="row clearfix" style="margin-left: 0px;">
                        <div class="col-md-12" style="line-height: 1.5;">
                            <b id="bold-text">Name</b>
                            <div class="input-group colorpicker colorpicker-element">
                                <div class="form-line">
                                    <input type="text" id="name" class="form-control name1" value="<?= Yii::$app->userdata->getFullNameById($myAgreements->tenant_id); ?>" readonly name="name">
                                    <input type="hidden" id="_csrf" class="form-control name1" value="<?= Yii::$app->request->getCsrfToken() ?>" name="_csrf">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" style="line-height: 1.5;margin-top: 10px;">
                            <b id="bold-text">Email</b>
                            <div class="input-group colorpicker colorpicker-element">
                                <div class="form-line focused">
                                    <input type="text" id="email" class="form-control name1" value="<?= Yii::$app->userdata->getUserEmailById($myAgreements->tenant_id); ?>" readonly name="email">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" style="line-height: 1.5;margin-top: 10px;">
                            <b id="bold-text">Mobile No</b>
                            <div class="input-group colorpicker colorpicker-element">
                                <div class="form-line focused">
                                    <input type="text" id="mobile" class="form-control name1" value="<?= Yii::$app->userdata->getPhoneNumber($myAgreements->tenant_id); ?>" readonly name="mobile" maxlength="10">
                                </div>
                            </div>
                        </div>
                    </div>

                    <br>
                </div>

                <div class="col-sm-8 cust-permentaddr" >
                    <p style="margin-left: 15px;">Permanent Address</p>
                    <div class="col-md-12">
                        <b id="bold">Address Line 1</b>
                        <div class="form-group cust-group">
                            <input type="text" class="form-control" id="address1" value="<?= Yii::$app->userdata->getAddress1($myAgreements->tenant_id); ?>" readonly name="address1">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <b id="bold">Address Line 2</b>
                        <div class="form-group cust-group">
                            <input type="text" class="form-control" id="address2" value="<?= Yii::$app->userdata->getAddress2($myAgreements->tenant_id); ?>" readonly name="address2">
                        </div>
                    </div>

                    <?php $tenantemail = Yii::$app->userdata->getUserEmailById($myAgreements->tenant_id); ?>
                    <?php //echo $tenantemail;die;?>
                    <div class="col-md-12">
                        <b id="bold">State</b>
                        <div class="cust-group">
                            <input type="text" class="form-control" id="state" value="<?php echo Yii::$app->userdata->getTenantState($myAgreements->tenant_id); ?>" readonly name="state">
                        </div>
                    </div>
                    <?php //$cities = \app\models\Cities::find()->where(['id' => $model->city])->all();
                    ?>
                    <div class="col-md-12 city">
                        <b id="bold">City</b>
                        <div class="cust-group">
                            <input type="text" class="form-control" id="city" value="<?php echo Yii::$app->userdata->getTenantCity($myAgreements->tenant_id); ?>" readonly name="city">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <b id="bold">Pincode</b>
                        <div class="form-group">
                            <div class="cust-group">
                                <input type="text" class="form-control" id="pincode" value="<?= Yii::$app->userdata->getPincode($myAgreements->tenant_id); ?>" readonly name="pincode" maxlength="6">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#sidebarCollapse').on('click', function () {
            $('#sidebar').toggleClass('active');
        });
    });
</script>

<style>
    #content{
        width: 100%;
    }
    .disable_sec{
        z-index: -10000;
    }
    .cus_hide{
        display: none !important;
    }
    .cus_show{
        display: block !important;
    }
    .bgstyle {
        background-color: #fafafa !important;
        background-color: var(--white);
        margin-left: 15px;
        padding-top: 15px;
    }
    .input-group.colorpicker.colorpicker-element {
        margin-top: 10px;
        margin-bottom: 10px;
    }
    input#address1 , input#address2,select#state,select#city,input#pincode {
        background: #fafafa;
    }
    select#state {
        margin-bottom: 10px;
        width: 100% !important;
        margin-left: -4px;
    }
    select#city {
        margin-bottom: 10px;
        width: 100% !important;
        margin-left: -4px;
    }
    .dlt_img {
        width: 25px;
        height: 25px;
        float: right;
    }
    img#van-pill-image {
        padding: 15px;
        width: 200px !important;
        margin-top: 15px;
    }

    input#state,input#city {
        background-color: #fafafa !important;
        opacity: 1;
    }

    @media screen and (max-width: 767px){
        .cust-group{
            margin: -15px 0;
        }
    }
</style>

