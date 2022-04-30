<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Cities;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$this->title = 'Property Advisor';


/* @var $this yii\web\View */
/* @var $model app\models\Users */
/* @var $form yii\widgets\ActiveForm */
?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="<?php echo Url::home(true); ?>css/css/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo Url::home(true); ?>css/css/mdb.min.css" rel="stylesheet">
<link href="<?php echo Yii::$app->userdata->autoVersion(Url::home(true) . 'css/css/style.css'); ?>" rel="stylesheet">
<link href="<?php echo Yii::$app->userdata->autoVersion(Url::home(true) . 'css/css/property_adviser.css'); ?>" rel="stylesheet">


<style>

    .bantext{float:left;}
    .advbantexth1{color: #fff !important;position:relative;top:300px;text-align:center;padding:0px 17px 0px 17px; text-shadow: 1px 2px 30px black !important ;}
    .bgimgclass{float:left;}

    #first_button{display:none;}

    @media screen and (max-width:480px){

        .advbantexth1{color: #fff !important;position:relative;top:138px;text-align:center;padding:0px 17px 0px 17px;font-size:25px; }
        #first_button{display:block;}
        .bgimgclass{z-index:1200;height:113px;}	
        .ricon{margin-left:10px !important;}

        .ourserv{margin:0px !important;}
        .service1{margin-top:0px !important;}
        .easyadtest1 {font-size: 16px !important;padding-top:90px !important;}	
        .easyadtest2 {font-size: 16px !important;padding-top:20px !important;}	
        .dark-grey-text {font-size: 14px;}

        .col-border-pad {padding:10px !important;margin:10px !important;}

        .listmypropbox{position: relative;top: 67px;width: 100%;height: auto !important;}
        .img-bg{background-repeat: no-repeat !important;background-size: cover  !important;background-attachment:unset !important;}


        .bgimgclass{z-index:1413;height:170px !important;}	

        .frmsignup{padding:10px !important;top:-55px  !important;}


        .service1{margin-top:0px !important;}
        .ricon{margin-left:10px !important;}
        .slimli{padding:2px !important;}
        .frmstart{padding:0px !important;}
        .easylessr{margin-top:0px !important;}
        .Shape {width: 16px !important;height: 16px !important;opacity: 0.4;float: right;margin-top: -102px !important;}

        .col-border-pad-ad {padding: 20px 0px 0px 0px !important;margin:10px !important;}

        input.sign-up-input {background-color: #f8f9fa;
                             height: 2.4rem !important;
                             width: 100%;
                             font-size: 12px;
                             box-shadow: none;}

        .md-form{padding: 10px 0px 0px 0px !important;margin-left: -20px !important;
                 margin-right: -8px !important;
                 margin-top: -20px !important;
                 margin-bottom:0px !important;}

        .md-form, .md-form .btn {margin-bottom:0px !important;}
        .form-group {margin-bottom: 0.6rem !important;}
        .col{flex-basis: inherit !important;flex-grow: inherit !important;}

        .rightlistbtns{width:100% !important;padding:8px !important;height:40px !important;font-size: 15px;font-weight:bold;margin: 0px 0px 11px 4px !important;}

        a.nav-link.small.waves-effect.waves-light {
            margin-top:-22px;}

        .no-repeat {
            padding-top: 0px;
            padding-bottom: 15px !important; z-index: 10000;}

        .easytratext{margin-top: 20px !important;}


        .mydropdown {

            width: 100%;
            height: 38px !important;
            padding: 3px 5px !important;}

        .help-block { color: #b93131 !important;
                      font-size: 13px !important;
                      margin-left: 8px !important;
                      margin-top: 5px !important;}

        .mr-auto{margin-top:10px !important;}
        .sign-up-button {width: 50% !important;}
        .mt-3{margin-top: 0.5rem !important;}





    }




</style>

<body>
    <!-- nab bar -->
    <nav class="navbar navbar-expand-lg navbar-dark indigo fixed-top no-repeat">
        <!-- Navbar brand -->
        <a class="navbar-brand" href="<?php echo Url::home(true); ?>"><img src="<?= Yii::$app->request->baseUrl ?>/images/newlogo1.png"></a>
        <!-- Collapse button -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
        <!-- Collapsible content -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Links -->
            <ul class="navbar-nav mr-auto">
                <li class="nav-item ">
                    <a class="nav-link small" href="#property">Partner with us <span class="sr-only">(current)</span></a>
                </li>
                <!--li class="nav-item ">
                    <a class="nav-link small " href="#ourservice">Our Services</a>
                </li-->
                <li class="nav-item ">
                    <a class="nav-link small" href="#service">Advantages</a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link small" href="#advantage">How it works</a>
                </li>
                <li class="nav-item pull-right">
                    <a class="btn btn-primary btn_mycolor rightlistbtns " href="<?php echo Url::home(true); ?>users/owner">List Your Property</a>
                </li>
                <li class="nav-item ">
                    <a class="btn btn-primary btn_mycolor active rightlistbtns" href="<?php echo Url::home(true); ?>users/advisor">Partner With Us</a>
                </li>
                <!--li class="nav-item ">
                    <button type="button" class="btn btn-blue-grey">FAQ</button>
                </li-->
                <?php if (Yii::$app->user->isGuest) { ?>
                    <li class="nav-item ">
                        <a class="btn btn-primary btn_mycolor rightlistbtns" href="<?php echo Url::home(true); ?>site/login">Sign In</a>
                    </li>
                <?php } ?>
                <?php
                if (!Yii::$app->user->isGuest) {
                    ?>
                    <li class="nav-item ">
                        <?php if (Yii::$app->userdata->usertype == 6) { ?>
                            <a class="btn btn-primary btn_mycolor rightlistbtns" href="<?php echo Url::home(true); ?>external/myprofile">My Profile</a>
                        <?php } else if (Yii::$app->userdata->usertype == 2) { ?>
                            <a class="btn btn-primary btn_mycolor rightlistbtns" href="<?php echo Url::home(true); ?>site/myprofile">My Profile</a>
                        <?php } else if (Yii::$app->userdata->usertype == 3) { ?>
                            <a class="btn btn-primary btn_mycolor rightlistbtns" href="<?php echo Url::home(true); ?>site/myprofile">My Profile</a>
                        <?php } else if (Yii::$app->userdata->usertype == 4) { ?>
                            <a class="btn btn-primary btn_mycolor rightlistbtns" href="<?php echo Url::home(true); ?>site/myprofile">My Profile</a>
                        <?php } else if (Yii::$app->userdata->usertype == 5) { ?>
                            <a class="btn btn-primary btn_mycolor rightlistbtns" href="<?php echo Url::home(true); ?>advisers/myprofile">My Profile</a>
                        <?php } else if (Yii::$app->userdata->usertype == 7) { ?>
                            <a class="btn btn-primary btn_mycolor rightlistbtns" href="<?php echo Url::home(true); ?>external/myprofile">My Profile</a>
                        <?php } ?>
                    </li>
                <?php }
                ?>
            </ul>
            <!-- Links -->
        </div>
        <!-- Collapsible content -->
    </nav>
    <!-- Start your project here-->
    <section id="property" class="mt-3 listmypropbox" >
        <div class="container-fluid justify-content-md-center img-bg">

            <div class="row">

                <div class="col-lg-8 col-md-12 col-sm-12 bantext">
                    <h1 class="advbantexth1"><strong>Partner With Us For Property Management Services & Start Earning Annuity Income</strong></h1>



                    <h1 class="advbantexth1">
                        <strong>Opportunity To Partner With One Of The Best Property Management Companies </strong>
                    </h1>

                </div>

                <div class="col-lg-4 col-md-12 col-sm-12  bgimgclass">
                    <!--First row-->
                    <div class="row wow fadeIn" data-wow-delay="0.4s">
                        <div class="col-md-12 frmstart">

                            <div class="menu">
                                <ul class="slimmenu">
                                    <li><div class="slimli">
                                            <!--Product-->
                                            <div class="product-wrapper">

                                                <!--Product data-->
                                                <?php
                                                $form = ActiveForm::begin([
                                                            'options' => [
                                                                'class' => 'sign-up frmsignup'
                                                            ]
                                                ]);
                                                ?>

                                                <?php if (Yii::$app->session->hasFlash('ownerFormSubmitted')) {
                                                    ?>
                                                    <center>
                                                        <div id="sucess_msg">
                                                            <?php //echo "hello";die;  ?>
                                                            <?= Yii::$app->session->getFlash('ownerFormSubmitted'); ?>
                                                        </div>   </center>
                                                    <?php
                                                }
                                                ?>

                                                <!--input type="text" class="sign-up-input" placeholder="Full Name *" autofocus-->

                                                <?= $form->field($model, 'full_name')->textInput(['maxlength' => true, 'placeholder' => 'Full Name*', 'class' => 'isCharacter sign-up-input', 'autofocus' => true])->label(false); ?>
                                                <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'placeholder' => 'Contact Number *', 'class' => 'sign-up-input isPhone', 'autofocus' => true])->label(false); ?>
                                                <?= $form->field($model, 'login_id')->textInput(['maxlength' => true, 'placeholder' => 'Email *', 'class' => 'sign-up-input'])->label(false); ?>
                                                <?php //echo $form->field($modelAdvisorProfile, 'comment')->textInput(['maxlength' => true, 'placeholder' => 'Any Remarks', 'class' => 'sign-up-input'])->label(false); ?>
                                                <?php echo $form->field($modelAdvisorProfile, 'ad_company')->textInput(['maxlength' => true, 'placeholder' => 'Organization Name', 'class' => 'sign-up-input'])->label(false); ?>
                                                <?php echo $form->field($modelAdvisorProfile, 'ad_properties')->textInput(['maxlength' => true, 'placeholder' => 'Approximate number of properties held by your client', 'class' => 'sign-up-input isNumber'])->label(false); ?>
                                                <?php //echo $form->field($modelLeadsAdvisor, 'address')->textInput(['maxlength' => true, 'placeholder' => 'Address Line 1 *', 'class' => 'sign-up-input'])->label(false); ?>
                                                <?php //$form->field($modelLeadsAdvisor, 'address_line_2')->textInput(['maxlength' => true, 'placeholder' => 'Address Line 2', 'class' => 'sign-up-input'])->label(false); ?>


                                                <?php
                                                $cities = \app\models\Cities::find()->where(['status' => 1])->all();
                                                $cityData = ArrayHelper::map($cities, 'id', 'city_name');
                                                ?>
                                                <?= $form->field($modelLeadsAdvisor, 'lead_city')->dropDownList($cityData, ['prompt' => 'Select city where you operate from', 'class' => 'btn btn-primary dropdown-toggle mydropdown', 'style' => 'margin: 0; padding: 12px;'])->label(false); ?>


                                                <div class="row md-form" style="display: none; margin-left: -20px;margin-right: -8px;margin-top: -20px;margin-bottom: -20px;">
                                                    <!--Dropdown primary-->
                                                    <div class="col-lg">
                                                        <div class="dropdown">
                                                            <!--Trigger-->
                                                            <!--button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width: 100%;">Select State</button>
                                                            <Menu>
                                                            <div class="dropdown-menu dropdown-primary">
                                                                <a class="dropdown-item" href="#">Action</a>
                                                                <a class="dropdown-item" href="#">Another action</a>
                                                                <a class="dropdown-item" href="#">Something else here</a>
                                                                <a class="dropdown-item" href="#">Something else here</a>
                                                            </div-->

                                                            <?php
                                                            $states = \app\models\States::find()->all();
                                                            $stateData = ArrayHelper::map($states, 'id', 'name');
                                                            ?>
                                                            <?php //echo $form->field($modelLeadsAdvisor, 'state')->dropDownList($stateData, ['prompt' => 'Select State', 'url' => \Yii::$app->getUrlManager()->createUrl('users/getcity'), 'class' => 'btn btn-primary dropdown-toggle mydropdown'])->label(false); ?>
                                                        </div>
                                                        <!--/Dropdown primary-->
                                                    </div>
                                                    <div class="col-lg">
                                                        <!--Dropdown primary-->
                                                        <div class="dropdown">
                                                            <!--Trigger-->
                                                            <!--button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width: 100%;">Select City</button>
                                                            <Menu>
                                                            <div class="dropdown-menu dropdown-primary">
                                                                <a class="dropdown-item" href="#">Action</a>
                                                                <a class="dropdown-item" href="#">Another action</a>
                                                                <a class="dropdown-item" href="#">Something else here</a>
                                                                <a class="dropdown-item" href="#">Something else here</a>
                                                            </div-->
                                                        </div>
                                                        <!--/Dropdown primary-->
                                                    </div>
                                                </div>
                                                <?php //echo $form->field($modelLeadsAdvisor, 'pincode')->textInput(['maxlength' => true, 'placeholder' => 'Pincode *', 'class' => 'isPin sign-up-input'])->label(false)  ?>

                                                <input class="sign-up-input" placeholder="Name of the Sales Person (Optional)" name="sales_email" type="email" pattern="^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$">


                                                <?= Html::submitButton($model->isNewRecord ? 'Submit' : 'Update', ['class' => 'sign-up-button no-margin mt-3']) ?>
                                                <button id="first_button" type="button" class="sign-up-button no-margin mt-3 pull-right" style="background-color: #aea1a0 !important;border-color: #aea1a0 !important;" onclick="cancelform()">Cancel</button>

                                                <?php ActiveForm::end(); ?>

                                                </a>
                                                </li>
                                                </ul>
                                            </div>


                                        </div>
                                        <!--Product-->
                                        </div>
                                        </div>
                                        <!--/.First row-->
                                        </div>
                                        </div>
                                        <!--/.Info-->

                                        </div>
                                        </section>
                                        <!--First row-->
                                        <section id="service" class="mt-3 service1">
                                            <div class="container">
                                                <center class="p-3">
                                                    <h4 id="easy_lease" class="easyadtest1" >THE EASYLEASES ADVANTAGES</h4>
                                                </center>
                                                <div classs="container mt-3">
                                                    <div class="row mt-5 wow ourserv">
                                                        <!--First column-->
                                                        <div class="col col-border-pad text-center wow fadeIn" data-wow-delay="0.2s">
                                                            <!--Card-->
                                                            <!--Card image-->
                                                            <img class="icon-img" src="../images/icons/ic-referral@3x.png" alt="Card image cap">
                                                            <!--Card content-->
                                                            <div class="card-body">
                                                                <!--Title-->
                                                                <h4 class="card-title dark-grey-text"><strong>Referral Annuity Income</strong></h4>
                                                                <!--Text-->
                                                                <p class="card-text">Get steady annual income from referred properties throughout engagement lifecycle</p>
                                                            </div>
                                                            <!--/.Card-->
                                                        </div>
                                                        <!--/.First column-->
                                                        <!--Second column-->
                                                        <div class="col col-border-pad text-center wow fadeIn" data-wow-delay="0.4s">
                                                            <!--Card-->
                                                            <!--Card image-->
                                                            <img class="icon-img" src="../images/icons/ic-hassle-free@3x.png"  alt="Card image cap">
                                                            <!--Card content-->
                                                            <div class="card-body">
                                                                <!--Title-->
                                                                <h4 class="card-title dark-grey-text"><strong>Hassle-free Business Model</strong></h4>
                                                                <!--Text-->
                                                                <p class="card-text">All property management issues handled by Easyleases. You get to focus entirely on your core business</p>
                                                            </div>
                                                            <!--/.Card-->
                                                        </div>
                                                        <!--/.Second column-->
                                                        <!--Third column-->
                                                        <div class="col col-border-pad wow fadeIn text-center" data-wow-delay="0.2s">
                                                            <!--Card-->
                                                            <!--Card image-->
                                                            <img class="icon-img" src="../images/icons/ic-transparency@3x.png"  alt="Card image cap">
                                                            <!--Card content-->
                                                            <div class="card-body">
                                                                <!--Title-->
                                                                <h4 class="card-title dark-grey-text"><strong>Complete Transparency</strong></h4>
                                                                <!--Text-->
                                                                <p class="card-text">View all referred properties & income in real-time via our technology platform. Anytime.</p>
                                                            </div>
                                                            <!--/.Card-->
                                                        </div>
                                                        <!--/.First column-->
                                                        <div class="col col-border-pad wow fadeIn text-center" data-wow-delay="0.2s">
                                                            <!--Card-->
                                                            <!--Card image-->
                                                            <img class="icon-img" src="../images/icons/ic-maximize-rental@3x.png"  alt="Card image cap">
                                                            <!--Card content-->
                                                            <div class="card-body">
                                                                <!--Title-->
                                                                <h4 class="card-title dark-grey-text"><strong>No Solicitation</strong></h4>
                                                                <!--Text-->
                                                                <p class="card-text">We strictly DO NOT solicit tenants referred by you for any purpose other than property management</p>
                                                            </div>
                                                            <!--/.Card-->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                        <br>
                                        <br>
                                        <!--/.THE EASYLEASES ADVANTAGES end-->
                                        <!--/.EASY & TRANSPARENT PROCESS start-->
                                        <section id="advantage" class="mt-3 ">
                                            <div class="container">
                                                <center>
                                                    <h4 id="easy_lease" class="easyadtest2">EASY & TRANSPARENT PROCESS</h4>
                                                </center>
                                                <div class="row mt-5 wow">
                                                    <!--First column-->
                                                    <div class="col-lg-4 text-center wow fadeIn " data-wow-delay="0.2s">
                                                        <!--Card-->
                                                        <!--Card image-->
                                                        <img class="icon-img" src="../images/icons/ic-submit@3x.png"  alt="Card image cap">
                                                        <!--Card content-->
                                                        <div class="card-body">
                                                            <!--Title-->
                                                            <h4 class="card-title dark-grey-text"><strong>Submit</strong></h4>
                                                            <!--Text-->
                                                            <p class="card-text">Submit your details on Easyleases site. We will call you within 3 business days and work with you on your client referrals
                                                            </p>
                                                        </div>
                                                        <!--/.Card-->
                                                        <div><img src="../images/icons/arrows.ico" class="Shape"></div>
                                                    </div>
                                                    <!--/.First column-->
                                                    <!--Second column-->
                                                    <div class="col-lg-4 text-center wow fadeIn easytratext" data-wow-delay="0.4s">
                                                        <!--Card-->
                                                        <!--Card image-->
                                                        <img class="icon-img" src="../images/icons/ic-ongoing-support@3x.png"  alt="Card image cap">
                                                        <!--Card content-->
                                                        <div class="card-body">
                                                            <!--Title-->
                                                            <h4 class="card-title dark-grey-text"><strong>Contact</strong></h4>
                                                            <!--Text-->
                                                            <p class="card-text">We will contact the referred clients and work on converting them. You will be kept fully informed on the progress, at every stage</p>
                                                        </div>
                                                        <!--/.Card-->
                                                        <div><img src="../images/icons/arrows.ico" class="Shape"></div>
                                                    </div>
                                                    <!--/.Second column-->
                                                    <!--Third column-->
                                                    <div class="col-lg-4 wow text-center fadeIn easytratext" data-wow-delay="0.6s">
                                                        <!--Card-->
                                                        <!--Card image-->
                                                        <img class="icon-img" src="../images/icons/ic-maximize-rental@3x.png"  alt="Card image cap">
                                                        <!--Card content-->
                                                        <div class="card-body">
                                                            <!--Title-->
                                                            <h4 class="card-title dark-grey-text"><strong>Successfully Converted</strong></h4>
                                                            <!--Text-->
                                                            <p class="card-text">Successfully converted clients are tagged to you, and you start receiving your commission month-on-month
                                                            </p>
                                                        </div>
                                                        <!--/.Card-->
                                                        <!-- <div><img src="http://13.229.194.243/pmsdev/web/images/icons/arrows.ico" class="Shape"></div> -->
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                        <!-- SCRIPTS -->
                                        <!-- JQuery -->
                                        <script type="text/javascript" src="<?php echo Url::home(true); ?>/js/js/jquery-3.2.1.min.js"></script>
                                        <!-- Bootstrap tooltips -->
                                        <script type="text/javascript" src="<?php echo Url::home(true); ?>/js/js/popper.min.js"></script>
                                        <!-- Bootstrap core JavaScript -->
                                        <script type="text/javascript" src="<?php echo Url::home(true); ?>/js/js/bootstrap.min.js"></script>
                                        <!-- MDB core JavaScript -->
                                        <script type="text/javascript" src="<?php echo Url::home(true); ?>/js/js/mdb.min.js"></script>
                                        <script type="text/javascript">

                                                    $(document).ready(function () {
                                                        // Add smooth scrolling to all links
                                                        $("a").on('click', function (event) {

                                                            // Make sure this.hash has a value before overriding default behavior
                                                            if (this.hash !== "") {
                                                                // Prevent default anchor click behavior
                                                                event.preventDefault();

                                                                // Store hash
                                                                var hash = this.hash;

                                                                // Using jQuery's animate() method to add smooth page scroll
                                                                // The optional number (800) specifies the number of milliseconds it takes to scroll to the specified area
                                                                $('html, body').animate({
                                                                    scrollTop: $(hash).offset().top
                                                                }, 800, function () {

                                                                    // Add hash (#) to URL when done scrolling (default click behavior)
                                                                    window.location.hash = hash;
                                                                });
                                                            } // End if
                                                        });

                                                    });

                                                    $(document).ready(function () {
                                                        $('ul li a').click(function () {
                                                            $('li a').removeClass("active");
                                                            $(this).addClass("active");
                                                        });
                                                    });
                                                    $(document).ready(function () {
                                                        $("#leadsadvisor-city").removeClass("waves-effect waves-light");
                                                    });
                                                    $(document).ready(function () {
                                                        $("#leadsadvisor-state").removeClass("waves-effect waves-light");
                                                    });

                                                    function cancelform()
                                                    {
                                                        var $menu_collapser = $('body').find('.collapse-button');

                                                        $menu_collapser.click();
                                                    }
                                        </script>

                                        </body>



                                        <style type="text/css">
                                            .active {
                                                /*background-color:#d90000;*/
                                                color: #e24c3f !important
                                            }
                                            a.btn.btn-primary.btn_mycolor.active.waves-effect.waves-light {
                                                border: 1px solid #59abe3;
                                                color: #59abe3 !important;
                                            }
                                            footer {
                                                /* background: rgba(0, 0, 0, 0) url(../images/footer_bg.jpg) repeat scroll 0 0; */
                                                float: left;
                                                padding: 20px 0 20px;
                                                /* padding: 161px 0 123px; */
                                                width: 100%;
                                                position: relative;
                                                z-index: 9999;
                                                background-color: #000;
                                            }
                                            ul.footer-links {
                                                list-style: none;
                                                /* display: inline-flex; */
                                                width: 100%;
                                                height: auto;
                                                word-wrap: break-word;
                                            }
                                            .footer-links li a {
                                                color: #949494;
                                                font-size: 13px;
                                                text-decoration: none;
                                            }
                                            .footer-links li {
                                                width: 25%;
                                                float: left;
                                                padding: 5px 50px;
                                                /* text-align: center; */
                                            }
                                            .copyright {
                                                color: #ffffff;
                                                float: left;
                                                font-family: "Open Sans",sans-serif;
                                                font-size: 13px;
                                                padding: 7px;
                                                text-align: center;
                                                width: 100%;
                                                background: rgba(0, 0, 0, 0) url(../images/copyright_bg.jpg) repeat scroll 0 0;
                                                background: #333;
                                                z-index: 9999;
                                                position: relative;
                                            }
                                            .text-set {
                                                float: left;
                                                color: white !important;
                                            }
                                            .text-set > a {
                                                color: white;
                                                font-size: 12px;
                                            }
                                            .mydropdown {
                                                width: 100%;
                                                height: 45px;
                                            }
                                            #sucess_msg {
                                                margin-top: 0px !important;
                                                background-color: transparent !important; 
                                                color: grey;
                                                margin-bottom: 15px;
                                                color: #0d5c92;
                                            }

                                            ul.slimmenu li{width:100% !important;}

                                            .collapse-button  {
                                                position: fixed !important;
                                                /*right: 0;*/
                                                top: 50%;
                                                width: 9em;
                                                margin-top: -2.5em;
                                                background:#4960DF;
                                                color:#fff;
                                                font-size:11px;
                                                border: 1px solid #8495EE;
                                                padding: 5px;        
                                            }


                                            /* Portrait */
                                            @media only screen 
                                            and (min-device-width: 320px) 
                                            and (max-device-width: 568px)
                                            and (-webkit-min-device-pixel-ratio: 2)
                                            and (orientation: portrait) {


                                                .banfrmhome {width: 89% !important;}	
                                                .collapse-button {
                                                    display:block !important;
                                                    font-size: 15px;
                                                    padding: 6px 5px 28px 0px !important;
                                                    width: 37%;
                                                    font-weight: bolder;
                                                    right: 0px !important;
                                                    top: 380px !important;
                                                    /*z-index:9998;*/
                                                }

                                                .img-bg{
                                                    height: 350px;
                                                    margin-bottom: 80px;
                                                }
                                                /*.sign-up{
                                                    overflow-x: scroll;
                                                    height: 350px;
                                                }*/

                                                ul.slimmenu.collapsed li{
                                                    top: -92px !important;
                                                }

                                            }

                                            /* Landscape */
                                            @media only screen 
                                            and (min-device-width: 320px) 
                                            and (max-device-width: 568px)
                                            and (-webkit-min-device-pixel-ratio: 2)
                                            and (orientation: landscape) {
                                                .img-bg{
                                                    height: 350px;
                                                    margin-bottom: 80px;
                                                }

                                                .collapse-button {
                                                    display:block !important;
                                                    font-size: 15px;
                                                    padding: 6px 5px 28px 0px !important;
                                                    width: 37%;
                                                    font-weight: bolder;
                                                    right: 0px !important;
                                                    top: 380px !important;
                                                    /*z-index:9998;*/
                                                }

                                                .banfrmhome {width: 89% !important;}	
                                                ul.slimmenu.collapsed li{
                                                    top: -99px;
                                                    z-index: 9999;
                                                }

                                                .advbantexth1{
                                                    top:107px !important;
                                                }
                                                .sign-up-button{
                                                    width:50%;
                                                }

                                            }


                                            /* Portrait */
                                            @media only screen 
                                            and (min-device-width: 768px) 
                                            and (max-device-width: 1024px) 
                                            and (orientation: portrait) 
                                            and (-webkit-min-device-pixel-ratio: 1) {

                                                a.btn_mycolor.active{width:100%;}
                                                .btn_mycolor{width:100%;}

                                                .collapse-button {
                                                    display:block !important;
                                                    font-size: 15px;
                                                    padding: 6px 5px 28px 0px !important;
                                                    width: 37%;
                                                    font-weight: bolder;
                                                    right: 0px !important;
                                                    top: 380px !important;
                                                    /*z-index:9998;*/
                                                }
                                                /*.bantexthome1{
                                                    left: 40px;            
                                                }      
                                                .bantexthome2{
                                                    left:15px;
                                                    top: 272px;
                                                }*/


                                                .banfrmhome{left:26px; top: 80px;}

                                                .img-bg{
                                                    height:550px;
                                                    margin-bottom: 80px;
                                                }

                                                .sign-up{
                                                    margin: -182px auto;
                                                    z-index: 9999;
                                                }

                                                .sign-up-button{
                                                    width:50%;
                                                }

                                                .bgimgclass{
                                                    top: -64px;
                                                }
                                                /*.slimmenu {
                                                    overflow-x: scroll;
                                                    height: 505px;    
                                                }*/

                                                .bantexth1{font-weight: 500;
                                                           text-shadow: 2px 2px 4px #5F5F5F;
                                                           top: 110px;
                                                }

                                                .advbantexth1{
                                                    top: 205px;
                                                }

                                            }
                                        </style>



                                        <!--slimmemnu JS-->

                                        <script type="text/javascript" src="<?php echo Url::home(true); ?>/js/jquery.slimmenu.js"></script>
                                        <script type="text/javascript" src="<?php echo Url::home(true); ?>/js/jquery.easing.min.js"></script>


                                        <script>
                                                    $('ul.slimmenu').slimmenu(
                                                            {
                                                                resizeWidth: '800',
                                                                collapserTitle: ' I am Interested',
                                                                easingEffect: 'easeInOutQuint',
                                                                animSpeed: 'medium',
                                                                indentChildren: true,
                                                                childrenIndenter: '&raquo;'
                                                            });
                                        </script>
                                        <!--slimmemnu JS-->








