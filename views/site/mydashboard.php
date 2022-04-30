<?php
/* @var $this yii\web\View */

$this->title = 'PMS - My Dashboard';

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\PropertyTypes;
use app\models\PropertyAttributeMap;
use app\models\PropertiesAddress;

/* echo  "<pre>";
  print_r($model );
  echo  "</pre>"; */
$namebhk = '';
//echo "<pre>";print_r($advertisements);echo "</pre>";die;
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> 
<link href="https://fonts.googleapis.com/css?family=Roboto:700" rel="stylesheet">
<style>
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
<div id="content">
    <div class="">
        <button type="button" id="sidebarCollapse" class="btn btn-info navbar-btn">
            <i class="glyphicon glyphicon-align-left"></i>
            <span></span>
        </button>
        <div class="container cust-mydashboard" id="cst-dash" style="margin-top: -42px;">
            <div class="col-md-4">
                <h4>My Dashboard</h4>
            </div>
            <!--div class="col-md-6">
                <button id="first_button" class="btn btn-info btn-lg pull-right">Edit</button>
            </div-->
        </div>

        <hr>
        <div class="col-md-11 cust-mydashboard" id="cst-dash">
            <div class="col-md-12">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-3 col-sm-6 blue-bg cust-bluebg">
                            <div class="card custom_card" style="width: 19rem;">
                                <img src="<?php echo Url::home(true); ?>images/icons/dashboard_home.svg" style="margin-top: 10px;">
                                <hr class="hr_style">
                                <div class="cust_span">
                                    <span>
                                        Total No. Of Properties
                                    </span>
                                    <br/>

                                </div>
                                <div class="cust_span1">
                                    <span>
                                        <?= $tototProperties; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 red-bg cust-bluebg">
                            <div class="card custom_card" style="width: 19rem;">
                                <img src="<?php echo Url::home(true); ?>images/icons/dashboard-rupee-indian.svg" style="margin-top: 10px;">
                                <hr class="hr_style">
                                <div class="cust_span">
                                    <span>
                                        Total Rent Per Month
                                    </span>
                                </div>
                                <div class="cust_span1">
                                    <span>
                                        Rs. <?= Yii::$app->userdata->getFormattedMoney($rent); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 orange-bg cust-bluebg">
                            <div class="card custom_card" style="width: 22rem;">
                                <img src="<?php echo Url::home(true); ?>images/icons/dashboard-rupee-indian.svg" style="margin-top: 10px;">
                                <hr class="hr_style">
                                <div class="cust_span" >
                                    <span>
                                        Net Rental Earning Till <br><?php echo date("F j, Y"); ?>
                                    </span>
                                    <br/>

                                </div>
                                <div class="cust_span1" style="margin-top: 26px !important;">
                                    <span>
                                        Rs. <?= Yii::$app->userdata->getFormattedMoney(round($netrental)); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 green-bg cust-bluebg">
                            <div class="card custom_card" style="width: 19rem;">
                                <img src="<?php echo Url::home(true); ?>images/icons/dashboard-rupee-indian.svg" style="margin-top: 10px;">
                                <hr class="hr_style">
                                <div class="cust_span">
                                    <span>
                                        Total Security Deposit
                                    </span>
                                    <br/>

                                </div>
                                <div class="cust_span1">
                                    <span>
                                        Rs. <?= Yii::$app->userdata->getFormattedMoney($deposit); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 no_margin">
                    <br/>
                    <hr>
                </div>
                <div class="col-md-12 no_margin" style="margin-left: -25px;">
                    <?php foreach ($properties as $property) { ?>
                        <?php $propertiesImages = \app\models\PropertyImages::find()->where(['property_id' => $property->id])->one(); ?>

                        <?php
                        $propertiesImageso = \app\models\PropertyImages::find()->where(['property_id' => $property->id])->one();
                        $propertiesImages = str_replace(" ", "%20", $propertiesImages->image_url);

                        if (!empty($propertiesImages)) {
                            $imgarr = (explode("/", $propertiesImages));
                            $filename = Url::home(true) . $propertiesImages;
                            //$headers = get_headers($filename, 1);
                            $headers = @file_get_contents($filename);
                        } else {
                            $filename = '';
                            $headers = array();
                            $headers[0] = '';
                        }
                        ?>
                        <div class="col-md-6 col-sm-6 sec_boarder" id="cust-scroll">

                            <div class="col-md-6 col-sm-6 no_margin no_margin2">
                                <a href="<?= Url::home(true); ?>site/ownerlease?id=<?= base64_encode($property->id); ?>"> 
                                    <?php
                                    //if (isset($imgarr[1]) && $headers[0] == "HTTP/1.1 200 OK") {
                                    if (isset($imgarr[1]) && !empty($headers)) {
                                        ?>
                                        <img width=225 height=190 src="<?php echo Url::home(true) . $propertiesImages; ?>" alt="image" style="margin-left: -15px;" class"cust-img">	

                                         <?php } else { ?>
                                             <img width=225 height=190 src="<?php echo Url::home(true); ?>images/oimg/no-image.jpg" alt="image" style="margin-left: -15px;">
                                         <?php } ?>


                                </a>
                            </div>
                            <?php
                            $namelen = strlen($property->property_name);
                            if ($namelen <= 25) {
                                $propertyname = $property->property_name;
                            } else {
                                $propertyname = substr($property->property_name, 0, 24) . "...";
                            }
                            ?>
                            <div class="col-md-6 col-sm-6 cust-propdetails" style="margin-left: 13px;">
                                <span class="cust-marpro" style="word-wrap: break-word; white-space: nowrap; width: 50px; overflow: hidden; text-overflow: ellipsis;" data-toggle="tooltip" title="<?php echo $property->property_name; ?>"><b style="margin-left: -2px;line-height: 4;"><?php echo $propertyname; ?></b></span>
                                <div class="col-md-12 " style="margin-top: -15px;">
                                    <br/>
                                    <div class="img_div">
                                        <img src="<?php echo Url::home(true); ?>images/oimg/ic-address.png" class="img-responsive">
                                    </div>
                                    <?php /* $addlen = strlen($property->address_line_1);
                                      if($addlen<=10)
                                      {
                                      $propertyadd = $property->address_line_1;
                                      }
                                      else{
                                      $propertyadd = substr($property->address_line_1,0,6)."...";
                                      } */
                                    ?>
                                    <div class="cust-divht" style="width: 170px;height: 60px;overflow: hidden;padding-left: 10px;">
                                        <span class="cst-addrs" style="word-wrap: break-word;word-wrap: break-word;color: #aaaeb2;font-family: Lato-Regular;letter-spacing: 0.9;size: 13px;font-family: 'Roboto', sans-serif;font-size: 13px;font-weight: 700;font-style: normal;font-stretch: normal;/* line-height: 1.85; */letter-spacing: 0.9px;" data-toggle="tooltip" title="<?php echo $property->address_line_1; ?> , <?php echo $property->address_line_2; ?> , <?php echo Yii::$app->userdata->getCityName($property->city); ?>">
                                            <?= $property->address_line_1 . " , " . $property->address_line_2 . " , " . Yii::$app->userdata->getCityName($property->city); ?>
                                        </span>
                                    </div>
                                    <div class="cust-status" style="width: 241px;margin-top: 36px;margin-left: -35px;background: #fafafa;height: 31px;padding-top: 6px;padding-left: 17px;">
                                        <b class="cust-b">Status:</b>
                                        <?php if (in_array($property->id, $ids_booking)) { ?>
                                            <b style="color: #5bc0de;">Rented</b>
                                        <?php } else { ?>
                                            <b style="color: red;">Vacant</b>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>

                        </div>
                    <?php } ?>  </div>
            </div>
            <!--div class="col-md-3"-->
            <?php //foreach($advertisements as $ad) {  ?>
            <!--div class="col-md-12">
                <a href="http://<?php //echo $ad->link;  ?>"><img src="<?php //echo Url::home(true).$ad->banner;   ?>" class="img-responsive"></a>
            </div-->
            <?php //} ?>

            <!--/div-->
        </div>
    </div>
</div>
<div id="toast"><div id="img"><span id="remaining_day_pass"></span></div>
    <div id="desc">Days remaining to change password</div>   
</div>


<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal" style="display:none;" id="adbutton">Open Modal</button>

<!-- Modal -->

<!-- The Modal -->
<div id="myModal" class="modal">

    <!-- Modal content -->
    <div class="modal-content">
      <!-- <span class="close">&times;</span> -->
        <button type="button" class="close cust-close" id="cust-cls" data-dismiss="modal" style="color: red;z-index: 10000;opacity: 1;margin-top: 130px;text-align: center;margin-right: 330px;position: absolute;top:0;right:0;">&times;</button>
        <?php
        if (!empty($advertisements)) {
            ?>
            <div class="col-md-12">

                <a href="http://<?php echo $advertisements->link; ?>" target="_blank"><img src="<?php echo Url::home(true) . $advertisements->banner; ?>" class="img-responsive pop-img"></a>
            </div>
        <?php } ?>
    </div>

</div>


<script type="text/javascript">
    $(document).ready(function () {
        debugger
        $('#sidebarCollapse').on('click', function () {
            $('#sidebar').toggleClass('active');
        });
    });
    $(document).ready(function () {

        var session_savedata = sessionStorage.getItem("popvalid");
        if (session_savedata)
        {

        } else {
<?php if (!empty($advertisements)) { ?>
                $("#adbutton").click();
                sessionStorage.setItem("popvalid", 1);
<?php } ?>
        }

    });
</script>
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
                $("#close").css("display", "none");
                //$('#myModal').modal({backdrop: 'static', keyboard: false});
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

<style>
    #content{
        width: 100% !important;
    }
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
    @media screen and (max-width: 360px){
        .pop-img{
            left: 19%;
        }
        #cust-cls{
            right: -92% !important;
        }
        .no_margin img{
            height: auto;
        }
    }
    @media screen and (max-width: 320px){
        .cust-status{
            width: 160% !important;
        }
    }
    @media screen and (max-width: 767px){
        button.cust-close{
            right: -83% !important;
        }
        .cust-propdetails{
            margin-left: 0 !important;
        }
        .img_div{
            margin-top: -15px;
        }
        .pop-img{
            width: 96% !important;
            left: 9% !important;
        }
        .cust-divht{
            /* height: 30px !important;  */
            width: 125% !important;
            margin-top: -15px;
        }
        #cst-dash {
            margin-left: 0px !important;
        }
        .no_margin img{
            width: 100% !important;
            margin: 0 !important;
        }
        .no_margin2{
            margin-left: 0 !important;
            padding: 0;
        }
        #cust-scroll{
            padding: 4px;
        }
    }

    @media (min-width: 568px) and (max-width: 767.98px) { 
        #cust-cls{
            right: -42% !important;
        }
        .no_margin img:last-child{
            width: 300px !important;
            height: 220px !important;
        }
        .cust-marpro{
            margin-left: 50px !important;
        }
        .img_div {
            margin-left: 10px !important;
            display: none;
        }
        .cust-b{
            margin-left: 50px
        }
        .pop-img{
            width: 90%;
            left: 9%;
        }

    }
    @media (min-width: 768px) and (max-width: 991.98px) { 
        #cust-cls{
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
        margin-top: 48px;
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
    @media screen and (max-width: 767px){
        .cust-status{
            margin-top: -15px !important;
            width: 118% !important;
            background: transparent !important;
        }
        .cust-bluebg{
            width: 100% !important;
            background-repeat: no-repeat;
            background-position: unset;
        }
    }
</style>