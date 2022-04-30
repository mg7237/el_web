<?php
/* @var $this yii\web\View */

$this->title = 'PMS Dashboard';

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\PropertyTypes;
use app\models\Cities;
?>
<style>
    .modal:before {
        height:auto;
    }
    .blue-bg {
        background-image: url(../images/oimg/blue.png);
        /* background-position: right top; */
        /* background-position: right bottom; */
        /* height: 180px; */
        height: 208px !important;
        width: 224px !important;
        margin-top: 20px;
        /* width: auto; */
        margin-right: 25px;
    }
    .orange-bg {
        background-image: url(../images/oimg/orange.png);
        /* background-position: right top; */
        /* background-position: right bottom; */
        height: 208px !important;
        width: 224px !important;
        margin-top: 20px;
        /* width: auto; */
        margin-right: 25px;
    }
    .red-bg {
        background-image: url(../images/oimg/red.png);
        /* background-position: right top; */
        /* background-position: right bottom; */
        /* height: 180px; */
        height: 208px !important;
        width: 224px !important;
        margin-top: 20px;
        /* width: auto; */
        margin-right: 25px;
    }
    /*############################################# 90days password change css   ################################################*/
    #toast {
        visibility: hidden;
        max-width: 50px;
        height: 50px;
        margin-left: -125px;
        margin: auto;
        background-color: #59abe3;
        color: #fff;
        text-align: center;
        border-radius: 2px;
        position: fixed;
        z-index:999;
        left: 70%;right:0;
        bottom: 30px;
        font-size: 15px;
        white-space: nowrap;
    }
    #toast #img{
        width: 50px;
        height: 50px;
        float: left;
        padding-top: 16px;
        padding-bottom: 16px;
        box-sizing: border-box;
        background-color: #e24c3f;
        color: #fff;
    }
    #toast #desc{
        color: #fff;
        padding: 16px;
        overflow: hidden;
        white-space: nowrap;
    }
    #toast.show {
        visibility: visible;
        -webkit-animation: fadein 0.5s, expand 0.5s 0.5s,stay 3s 1s, shrink 0.5s 2s, fadeout 0.5s 2.5s;
        animation: fadein 0.5s, expand 0.5s 0.5s,stay 3s 1s, shrink 0.5s 4s, fadeout 0.5s 4.5s;
    }
    @-webkit-keyframes fadein {
        from {bottom: 0; opacity: 0;} 
        to {bottom: 30px; opacity: 1;}
    }
    @keyframes fadein {
        from {bottom: 0; opacity: 0;}
        to {bottom: 30px; opacity: 1;}
    }
    @-webkit-keyframes expand {
        from {min-width: 50px} 
        to {min-width: 350px}
    }
    @keyframes expand {
        from {min-width: 50px}
        to {min-width: 350px}
    }
    @-webkit-keyframes stay {
        from {min-width: 350px} 
        to {min-width: 350px}
    }
    @keyframes stay {
        from {min-width: 350px}
        to {min-width: 350px}
    }
    @-webkit-keyframes shrink {
        from {min-width: 350px;} 
        to {min-width: 50px;}
    }
    @keyframes shrink {
        from {min-width: 350px;} 
        to {min-width: 50px;}
    }
    @-webkit-keyframes fadeout {
        from {bottom: 30px; opacity: 1;} 
        to {bottom: 60px; opacity: 0;}
    }
    @keyframes fadeout {
        from {bottom: 30px; opacity: 1;}
        to {bottom: 60px; opacity: 0;}
    }
</style>
<!-- Modal signup -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<div id="content">
    <button type="button" id="sidebarCollapse" class="btn btn-info navbar-btn">
        <i class="glyphicon glyphicon-align-left"></i>
        <span></span>
    </button>
    <div class="container" style="margin-top: -42px;">
        <div class="col-md-4 cust-mydashboard">
            <h4>My Dashboard</h4>

        </div>
        <!--div class="col-md-6">
            <button id="first_button" class="btn btn-info btn-lg pull-right">Edit</button>
        </div-->
    </div>
    <hr>
    <div class="container">
        <div class="container-fluid">
            <h5>

                <?php
                $cpn = count($propertynames);
                $i = 1;
                foreach ($propertynames as $pn) {
                    ?>
                    <?php
                    echo $pn;
                    if ($i < $cpn) {
                        echo ",";
                    }
                    ?>
                    <?php
                    $i++;
                }
                ?>
            </h5>
        </div>
        <div class="col-md-11">

            <div class="col-md-12 cust-mydashboard">
                <div class="row">
                    <div class="col-md-4 col-sm-4 blue-bg">
                        <div class="card custom_card " style="width: 19rem;">
                            <img src="<?php echo Url::home(true); ?>images/icons/tenant-dashboard-credit-card.svg" style="width: 25px;margin-top: 10px;height: 26px;">
                            <hr class="hr_style">
                            <div class="cust_span">
                                <span>
                                    Total Payment Over Due
                                </span>
                                <div class="cust_span1">
                                    <span>
                                        &#8377 <?= Yii::$app->userdata->getformat($dueAmount); ?>
                                    </span>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 orange-bg">
                        <div class="card custom_card" style="width: 19rem;">
                            <img src="<?php echo Url::home(true); ?>images/icons/tenant-dashboard-calendar.svg" style="width: 25px;margin-top: 10px;">
                            <hr class="hr_style">
                            <div class="cust_span">
                                <span>
                                    Upcoming Payment in next 30 days
                                </span>
                                <div class="cust_span1">
                                    <span>
                                        &#8377 <?= Yii::$app->userdata->getformat($nxtAmount); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 red-bg">
                        <div class="card custom_card " style="width: 19rem;">
                            <img src="<?php echo Url::home(true); ?>images/icons/tenant-dashboard-wall-clock.svg" style="width: 25px;margin-top: 10px;">
                            <hr class="hr_style">
                            <div class="cust_span">
                                <span>
                                    Days remaining for expiry
                                </span>
                                <div class="cust_span1">
                                    <span>
                                        <?= $remaining_days ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="toast"><div id="img"><span id="remaining_day_pass"></span></div>
                <div id="desc">Days remaining to change password</div></div>


            <!--div class="col-md-3">
                <div class="col-md-12">
                    <img src="<?php echo Url::home(true); ?>images/oimg/ad-1.png" class="img-responsive">
                </div>
                <div class="col-md-12">
                    <img src="<?php echo Url::home(true); ?>images/oimg/ad-2.png" class="img-responsive">
                </div>
                <div class="col-md-12">
                    <img src="<?php echo Url::home(true); ?>images/oimg/ad-3.png" class="img-responsive">
                </div>
                <div class="col-md-12">
                    <img src="<?php echo Url::home(true); ?>images/oimg/ad-4.png" class="img-responsive">
                </div>
            </div-->
        </div>
    </div>
</div>
<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal" style="display:none;" id="adbutton">Open Modal</button>
<?php //echo "<pre>";print_r($advertisements);echo "</pre>";die; ?>
<div id="myModal" class="modal">

    <!-- Modal content -->
    <div class="modal-content">
      <!-- <span class="close">&times;</span> -->
        <button type="button" class="close" id="close2" data-dismiss="modal" style="color: red;z-index: 10000;opacity: 1;margin-top: 130px;text-align: center;margin-right: 330px;position: absolute;top:0;right:0;">&times;</button>
        <?php
        if (!empty($advertisements)) {
            ?>
            <div class="col-md-12">

                <a href="http://<?php echo $advertisements->link; ?>" target="_blank"><img src="<?php echo Url::home(true) . $advertisements->banner; ?>" class="img-responsive pop-img"></a>
            </div>
        <?php } ?>
    </div>

</div>

<style>
    .pop-img {
        width: 50%;
        text-align: center;
        /* width: 50px; */
        height: 400px;
        position: absolute;
        left: 28%;
        top: 67%;
        margin-top: 125px;
        margin-left: -25px;
    }
    #content{
        width: 100%;
    }
    @media screen and (min-width: 320px) and (max-width: 390px){
        .cust-mydashboard{
            margin-left: 0 !important;
        }
    }
    @media screen and (max-width: 320px){
        #close2{
            right: -83% !important;
        }
    }
    @media screen and (max-width: 360px){
        #close2{
            right: -89% !important;
        }
    }
    @media screen and (max-width: 767px){
        .pop-img{
            width: 95%;
            margin-left: -25%;
        }
        .close{
            right: -79% !important;
        }
    }

    @media (min-width: 568px) and (max-width: 767.98px) { 
        .close {
            right: -39% !important;
        }
    }

    @media (min-width: 768px) and (max-width: 991.98px) { 
        .close{
            right: -16% !important;
        }
    }
    /* The Close Button */
    .close {
        color: #aaaaaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: #000;
        text-decoration: none;
        cursor: pointer;
    }

</style>

<script type="text/javascript">
    $(document).ready(function () {
        //alert("hello");
        var session_savedata = sessionStorage.getItem("popvalid");
        //alert(session_savedata);
        if (session_savedata)
        {

        } else {
            //alert("hello");
<?php if (!empty($advertisements)) { ?>
                $("#adbutton").click();
                sessionStorage.setItem("popvalid", 1);
<?php } ?>
        }

    });
</script>	
<style>
    .orange-bg {
        background-image: url(../images/oimg/orange.png);
        /* background-position: right top; */
        /* background-position: right bottom; */
        height: 208px !important;
        width: 224px !important;
        margin-top: 20px;
        /* width: auto; */
        margin-right: 25px;
    }
    .green-bg {
        background-image: url(../images/oimg/green.png);
        /* background-position: right top; */
        /* background-position: right bottom; */
        /* height: 180px; */
        height: 208px !important;
        width: 224px !important;
        margin-top: 20px;
        /* width: auto; */
        margin-right: 25px;
    }
    .blue-bg {
        background-image: url(../images/oimg/blue.png);
        /* background-position: right top; */
        /* background-position: right bottom; */
        /* height: 180px; */
        height: 208px !important;
        width: 224px !important;
        margin-top: 20px;
        /* width: auto; */
        margin-right: 25px;
    }
    .red-bg {
        background-image: url(../images/oimg/red.png);
        /* background-position: right top; */
        /* background-position: right bottom; */
        /* height: 180px; */
        height: 208px !important;
        width: 224px !important;
        margin-top: 20px;
        /* width: auto; */
        margin-right: 25px;
    }
    .cust_span>span {
        color: white;
        font-size: 16px;
    }
    .cust_span1 {
        margin-top: 30px;
    }
    .cust_span1>span {
        color: white;
        font-size: 30px;
        font-weight: bold;
        font-family: loto-bold;
    }
    .hr_style {
        width: 117% !important;
        margin-left: -14px !important;
        /* margin-top: 0px; */
    }
    .blue-bg {
        background-image: url(../images/oimg/blue.png);
        /* background-position: right top; */
        /* background-position: right bottom; */
        /* height: 180px; */
        height: 208px !important;
        width: 224px !important;
        margin-top: 20px;
        /* width: auto; */
        margin-right: 22px !important;
    }
    .red-bg {
        background-image: url(../images/oimg/red.png);
        /* background-position: right top; */
        /* background-position: right bottom; */
        /* height: 180px; */
        height: 208px !important;
        width: 224px !important;
        margin-top: 20px;
        /* width: auto; */
        margin-right: 22px !important;
    }
    .orange-bg {
        background-image: url(../images/oimg/orange.png);
        /* background-position: right top; */
        /* background-position: right bottom; */
        height: 208px !important;
        width: 224px !important;
        margin-top: 20px;
        /* width: auto; */
        margin-right: 22px !important;
    }
    .green-bg {
        background-image: url(../images/oimg/green.png);
        /* background-position: right top; */
        /* background-position: right bottom; */
        /* height: 180px; */
        height: 208px !important;
        width: 224px !important;
        margin-top: 20px;
        /* width: auto; */
        margin-right: 22px !important;
    }
</style>
<script>
    $(document).ready(function () {
        $.get("<?= Yii::$app->urlManager->baseUrl ?>/external/daysremainingforpass", function (data) {
            var numDays = parseInt(data);
            if (numDays < 1) {
                $('#remaining_day_pass').text('Your password expired  ');
                //<?php if (empty($_GET['cp'])) { ?>
                    //window.location = '<?= Yii::$app->urlManager->baseUrl ?>/external/myprofile?cp=1';
                    //<?php } ?>
                document.getElementById("passdropbtm").click();
                document.getElementById("fchangepassword").click();
                $(".close").css("display", "none");
                $('#myModal').modal({backdrop: 'static', keyboard: false});
            } else {
                $('#remaining_day_pass').text(data);
                var x = document.getElementById("toast")
                x.className = "show";
                setTimeout(function () {
                    x.className = x.className.replace("show", "");
                }, 5000);
            }
        });
    });
</script>