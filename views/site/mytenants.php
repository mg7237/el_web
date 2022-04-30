<?php
/* @var $this yii\web\View */

$this->title = 'PMS - My Tenants';

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\PropertyTypes;
use app\models\PropertyAttributeMap;
use app\models\PropertiesAddress;

/* echo  "<pre>";
  print_r($model );
  echo  "</pre>"; */
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

<div id="content">
    <button type="button" id="sidebarCollapse" class="btn btn-info navbar-btn">
        <i class="glyphicon glyphicon-align-left"></i>
        <span></span>
    </button>
    <div class="container" style="margin-top: -42px;">
        <div class="col-md-12">
            <h4 class="profile_heading"><?= strtoupper(Html::encode(Yii::$app->userdata->getPropertyNameById($pid))) ?> - TENANT DETAILS</h4>
        </div>

    </div>
    <hr>

    <div class="col-md-7 col-sm-7" style="margin-bottom: 22px !important;">
        <h4>Present Tenants</h4>
    </div>
    <div class="col-md-12 cust-rpad">
        <?php if (empty($presentBookings)) { ?>
            <p> No Present Tenants</p>
        <?php } ?>	
        <?php
        foreach ($presentBookings as $prstBookings) {
            $dataImage = Yii::$app->userdata->getImageById($prstBookings->tenant_id);

            if ($dataImage != '') {
                $imgarr = (explode("/", $dataImage));
                $filename = Url::home(true) . $dataImage;
                $headers = get_headers($filename, 1);
            } else {
                $filename = '';
                $headers = array();
                $headers[0] = '';
            }
            //echo $dataImage;die;
            //echo  "<pre>"; print_r($imgarr); echo "</pre>"; die;
            ?>	 
            <a href="<?php echo Url::home(true); ?>site/tenantdetails?type=p&user=<?= $prstBookings->tenant_id; ?>&prop_id=<?= $prstBookings->property_id; ?>&id=<?= $_GET['id'] ?>">
                <div class="col-md-5 col-sm-5 bgstyle" >
                    <div class="col-sm-6 sidenav">


                        <?php
                        if (isset($imgarr[2]) && $headers[0] == "HTTP/1.1 200 OK") {
                            ?>
                            <img src="<?php echo Url::home(true) . $dataImage; ?>" id="van-pill-image" for="fileupload" disabled="">
                        <?php } else { ?>
                            <img src="<?php echo Url::home(true); ?>uploads/profiles/no-image.png" id="van-pill-image" for="fileupload" disabled="">
                        <?php } ?>
                        </label>
                        <div class="row clearfix" style="margin-left: 0px;">
                            <div class="col-md-12">
                                <b id="bold-text">Name</b>
                                <div class="input-group colorpicker colorpicker-element">
                                    <div class="form-line">
                                        <input type="text" id="name" class="form-control name1" value="<?= Yii::$app->userdata->getFullNameById($prstBookings->tenant_id); ?>" readonly="" name="name">
                                        <input type="hidden" id="_csrf" class="form-control name1" value="" name="_csrf">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <b id="bold-text">Email</b>
                                <div class="input-group colorpicker colorpicker-element">
                                    <div class="form-line focused">
                                        <input type="text" id="email" class="form-control name1" value="<?= Yii::$app->userdata->getEmailById($prstBookings->tenant_id); ?>" readonly="" name="email">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <b id="bold-text">Mobile No</b>
                                <div class="input-group colorpicker colorpicker-element">
                                    <div class="form-line focused">
                                        <input type="text" id="mobile" class="form-control name1" value="<?= Yii::$app->userdata->getPhoneNumber($prstBookings->tenant_id) ?>" readonly="" name="property">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                    </div>
                    <div class="col-sm-6">
                        <div class="row cust-totalpaid">
                            <div class="col-md-12">
                                <b id="bold">Total Rent Paid</b>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="address1" value="Rs. <?= Yii::$app->userdata->getTotalRentByPid($prstBookings->property_id, $prstBookings->tenant_id); ?>" readonly="" name="rent">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <b id="bold">Move In Date</b>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="address2" value="<?= date('d-M-Y', strtotime($prstBookings->lease_start_date)); ?>" readonly="" name="move_in">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <b id="bold">Contract End Date</b>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="pincode" value="<?= date('d-M-Y', strtotime($prstBookings->lease_end_date)); ?>" readonly="" name="end_date">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a><?php } ?>  
    </div>
    <br/>
    <div class="col-md-7 col-sm-7" style="margin-bottom: 22px !important;">
        <h4>Past Tenants</h4>
    </div>
    <div class="col-md-12 cust-rpad">
        <?php if (empty($bookingsPast)) { ?>
            <p> No Past Tenants</p>
        <?php } ?>	 
        <?php
        foreach ($bookingsPast as $pastBookings) {

            $dataImagep = Yii::$app->userdata->getImageById($pastBookings->tenant_id);
            if ($dataImagep != '') {
                $imgarrp = (explode("/", $dataImagep));
                $filenamep = Url::home(true) . $dataImagep;
                $headersp = get_headers($filenamep, 1);
            } else {
                $filenamep = '';
                $headersp = array();
                $headersp[0] = '';
            }
            ?>
            <a href="<?php echo Url::home(true); ?>site/tenantdetails?type=p&user=<?= $pastBookings->tenant_id; ?>&prop_id=<?= $pastBookings->property_id; ?>&id=<?= $_GET['id'] ?>">					
                <div class="col-md-5 col-sm-5 bgstyle">
                    <div class="col-sm-6 sidenav" style="background-color: #e24c3f !important;">
                        <?php
                        if (isset($imgarrp[2]) && $headersp[0] == "HTTP/1.1 200 OK") {
                            ?>
                            <img src="<?php echo Url::home(true) . $dataImagep; ?>" id="van-pill-image" for="fileupload" disabled="">
                        <?php } else { ?>
                            <img src="<?php echo Url::home(true); ?>uploads/profiles/no-image.png" id="van-pill-image" for="fileupload" disabled="">
                        <?php } ?>
                        </label>
                        <div class="row clearfix" style="margin-left: 0px;">
                            <div class="col-md-12">
                                <b id="bold-text">Name</b>
                                <div class="input-group colorpicker colorpicker-element">
                                    <div class="form-line">
                                        <input type="text" id="namep" class="form-control name1" value="<?= Yii::$app->userdata->getFullNameById($pastBookings->tenant_id); ?>" readonly="" name="name">
                                        <input type="hidden" id="_csrf" class="form-control name1" value="WnIxQnJRSUQKGFAVRWMWIhYCYXEAaTMAAwFXCCASIgIuR1cXRScKEw==" name="_csrf">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <b id="bold-text">Email</b>
                                <div class="input-group colorpicker colorpicker-element">
                                    <div class="form-line focused">
                                        <input type="text" id="emailp" class="form-control name1" value="<?= Yii::$app->userdata->getEmailById($pastBookings->tenant_id); ?>" readonly="" name="email">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <b id="bold-text">Mobile No</b>
                                <div class="input-group colorpicker colorpicker-element">
                                    <div class="form-line focused">
                                        <input type="text" id="mobilep" class="form-control name1" value="<?= Yii::$app->userdata->getPhoneNumber($pastBookings->tenant_id) ?>" readonly="" name="mobile" maxlength="10">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                    </div>
                    <div class="col-sm-6 cust-pasttenante">
                        <div class="col-md-12">
                            <b id="bold">Total Rent Paid</b>
                            <div class="form-group">
                                <input type="text" class="form-control" id="address1" value="Rs. <?= Yii::$app->userdata->getTotalRentByPid($pastBookings->property_id, $pastBookings->tenant_id); ?>" readonly="" name="address1">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <b id="bold">Move In Date</b>
                            <div class="form-group">
                                <input type="text" class="form-control" id="address2" value="<?= date('d-M-Y', strtotime($pastBookings->lease_start_date)); ?>" readonly="" name="address2">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <b id="bold">Contract End Date</b>
                            <div class="form-group">
                                <input type="text" class="form-control" id="pincode" value="<?= date('d-M-Y', strtotime($pastBookings->lease_end_date)); ?>" readonly="" name="pincode" maxlength="6">
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        <?php } ?>  
    </div>

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
    .bgstyle {
        margin-right: 25px !important;
        padding-top: 15px;
        /* padding-left: 25px; */
        margin-bottom: 25px;
    }
    img#van-pill-image {
        padding: 15px;
        width: 200px !important;
        margin-top: 10px !important ;
        height: 186px;
        margin-left: -2px !important;
    }
    @media screen and (max-width: 767px){
        .cust-pasttenante{
            padding: 0;
            margin-left: -30px;
            margin-top: 15px;
        }
        .bgstyle{
            width: 100%;
            padding-right: 0;
        }
        .cust-rpad{
            padding-right: 0;
        }
    }
</style>