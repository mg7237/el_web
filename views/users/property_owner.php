<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Cities;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$this->title = 'Property Owner';


/* @var $this yii\web\View */
/* @var $model app\models\Users */
/* @var $form yii\widgets\ActiveForm */
?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="<?php echo Url::home(true); ?>css/css/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo Url::home(true); ?>css/css/mdb.min.css" rel="stylesheet">
<link href="<?php echo Yii::$app->userdata->autoVersion(Url::home(true) . 'css/css/style.css'); ?>" rel="stylesheet">
<link href="<?php echo Yii::$app->userdata->autoVersion(Url::home(true) . 'css/css/property_owner.css'); ?>" rel="stylesheet">

<style>
    select#leadsowner-lead_city {
        height: 50px;
    }
    .bantext{float:left;}
    .bantexth1{color: #fff !important;position:relative;top:262px;text-align: center;}


    .bantexth2{font-size:20px !important;font-weight: 500 !important;letter-spacing: 1px !important;color:#ffffff !important;
               position:relative;top:272px;text-align:center;}
    .bgimgclass{float:left;}


    @media screen and (max-width:480px){

        .bantexth1{color: #fff !important;position:relative;top:110px; text-align: center; font-size:23px; text-shadow: 2px 2px 4px #5F5F5F;}	
        .bantexth2{font-size:17px !important;font-weight:500 !important;letter-spacing: 1px !important;color:#ffffff !important;
                   position:relative;top:116px;text-align:center; text-shadow: 2px 2px 4px #5F5F5F;}	





        .easyadtest{font-size: 16px !important;padding-top:70px !important;}	
        .easyadtest2 {font-size:18px !important;padding-top:0px !important;}
        .dark-grey-text {font-size: 14px;}	
        .ourserv{margin:0px !important;}
        .col-border-pad {padding:10px !important;margin:10px !important;}

        .listmypropbox{position: relative;top: 67px;width: 100%;height: auto !important;}
        .img-bg{background-repeat: no-repeat !important;background-size: cover  !important;background-attachment:unset !important;}


        .bgimgclass{z-index:1413;height:170px !important;}	

        .frmsignup{margin:0px !important;padding:10px !important;/*top:-265px !important;*/}

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

        .rightlistbtns{width:100% !important;padding:8px !important;height:40px !important;font-size: 16px;font-weight:bold;margin: 0px 0px 11px 4px !important;}

        a.nav-link.small.waves-effect.waves-light {
            margin-top:-22px;}

        .no-repeat {
            padding-top: 0px;
            padding-bottom: 15px !important;z-index: 10000;}


        .text-center{font-size:13px !important;}

        .mydropdown {

            width: 100%;
            height: 38px !important;
            padding: 3px 5px !important;}

        .help-block {

            color: #b93131 !important;
            font-size: 13px !important;
            margin-left: 8px !important;
            margin-top: 5px !important;}	

        .mr-auto{margin-top:10px !important;}	
        .sign-up-button {width: 50% !important; display:inline-block !important;}	

        .mt-3{margin-top: 0.5rem !important;}
    }







</style>


<body>
    <!-- nab bar -->
    <nav class="navbar navbar-expand-lg navbar-dark indigo fixed-top no-repeat">
        <!-- Navbar brand -->
        <a title="Easyleases Technologies Private Limited" class="navbar-brand" href="<?php echo Url::home(true); ?>"><img alt="Easyleases Property Management Services" src="<?= Yii::$app->request->baseUrl ?>/images/newlogo1.png"></a>
        <!-- Collapse button -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
        <!-- Collapsible content -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Links -->
            <ul class="navbar-nav mr-auto">
                <li class="nav-item ">
                    <a class="nav-link small" href="#property">List Property <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link small " href="#service">Our Services</a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link small" href="#advantage">Advantages</a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link small" href="#transparent">How it works</a>
                </li>
                <li class="nav-item pull-right ">
                    <a class="btn btn-primary btn_mycolor active rightlistbtns" href="<?php echo Url::home(true); ?>users/owner">List Your Property</a>
                </li>
                <li class="nav-item ">
                    <a class="btn btn-primary btn_mycolor rightlistbtns" href="<?php echo Url::home(true); ?>users/advisor">Partner With Us</a>
                </li>
                <?php if (Yii::$app->user->isGuest) { ?>
                    <li class="nav-item ">
                        <a class="btn btn-primary btn_mycolor rightlistbtns" href="<?php echo Url::home(true); ?>site/login">Sign In</a>
                    </li>
                <?php }
                ?>
            </ul>
            <!-- Links -->
        </div>
        <!-- Collapsible content -->
    </nav>

    <!-- Start your project here-->
    <section id="property"  class="listmypropbox">
        <div class="container-fluid justify-content-md-center img-bg" title="Rental Property Management Services">

            <div class="row">

                <div class="col-lg-8 col-md-12 col-sm-12 bantext">
                    <h1 class="bantexth1"><strong>Maximize Your Property Rental.Hassle-free Property Management</strong></h1>
                    <h2 class="bantexth2">Real-time dashboard offering Complete Transparency</h2>
                </div>


                <div class="col-lg-4 col-md-12 col-sm-12 bgimgclass">
                    <!--First row-->

                    <div class="row wow fadeIn" data-wow-delay="0.4s">
                        <div class="col-sm-12 frmstart">
                            <!--Product-->

                            <div class="menu">
                                <ul class="slimmenu slidingDiv">
                                    <li><div class="slimli">



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



                                            <div class="col-lg-12 col-md-12 col-sm-12 bgimgclass">
                                                <!--First row-->

                                                <div class="row wow fadeIn" data-wow-delay="0.4s">
                                                    <div class="col-sm-12 frmstart">
                                                        <!--Product-->

                                                        <div class="menu">
                                                            <ul class="slimmenu slidingDiv">
                                                                <li><div class="slimli">



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
//echo "<pre>" ; print_r($_SESSION['attributes']); die;
                                                                            ?>





                                                                            <?= $form->field($model, 'full_name')->textInput(['maxlength' => true, 'placeholder' => 'Full Name*', 'class' => 'sign-up-input isCharacter', 'autofocus' => true])->label(false) ?>
                                                                            <?= $form->field($model, 'login_id')->textInput(['maxlength' => true, 'placeholder' => 'Email*', 'class' => 'sign-up-input'])->label(false); ?>    
                                                                            <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'placeholder' => 'Contact Number *', 'class' => 'sign-up-input isPhone'])->label(false); ?>
                                                                            <?php
                                                                            $cities = \app\models\Cities::find()->where(['status' => 1])->all();
                                                                            $cityData = ArrayHelper::map($cities, 'id', 'city_name');
                                                                            ?>

                                                                            <?= $form->field($modelLeadsOwner, 'lead_city')->dropDownList($cityData, ['prompt' => 'City of your property', 'class' => 'btn btn-primary dropdown-toggle mydropdown', 'style' => 'margin: 0; padding: 12px;'])->label(false); ?>
                                                                            <div style="margin-top: 15px;">
                                                                                <?php echo $form->field($modelOwnerProfile, 'ow_comments')->textInput(['maxlength' => true, 'placeholder' => 'Additional info you wish to share', 'class' => 'sign-up-input'])->label(false); ?>
                                                                            </div>
                                                                            <input id="sales_id" class="sign-up-input" placeholder="Name of the Sales Person (optional)" name="sales_email" type="email" pattern="^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$">
                                                                            <?php if (!empty(Yii::$app->getSession()->getFlash('sales_email_error'))) { ?>
                                                                                <div class="help-block"><?= Yii::$app->getSession()->getFlash('sales_email_error') ?></div>
                                                                            <?php } ?>
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




                                                                    <!--First row-->
                                                                    <section id="service" class="mt-3 md-3">
                                                                        <div class="container service1">
                                                                            <center>
                                                                                <h4 id="easy_lease" class="easyadtest">OUR SERVICES</h4>


                                                                            </center>
                                                                            <div classs="container" class="mt-5 ourserv">
                                                                                <div class="row">
                                                                                    <div class="col col-border-pad">
                                                                                        <div class=" wow fadeIn text-center" data-wow-delay="0.2s">
                                                                                            <!--Card-->
                                                                                            <!--Card image-->
                                                                                            <img class="icon-img" src="../images/icons/ic-simple-leasing@3x.png" alt="Simple Leasing Model (Lease PMS)">
                                                                                            <!--Card content-->
                                                                                            <div class="card-body">
                                                                                                <!--Title-->
                                                                                                <h4 class="card-title dark-grey-text">
                                                                                                    <strong>Simple Leasing Model (Lease PMS)</strong>
                                                                                                </h4>
                                                                                                <!--Text-->
                                                                                                <ul class="fa-ul">
                                                                                                    <li>
                                                                                                        <i class="fa-li fa fa-check blue"></i>
                                                                                                        <p class="card-text">Tenant Acquisition, documentation and monthly rental collection</p>
                                                                                                    </li>
                                                                                                    <li>
                                                                                                        <i class="fa-li fa fa-check blue"></i>
                                                                                                        <p class="card-text"> Professional property management services of semi / fully furnished flats</p>
                                                                                                    </li>
                                                                                                    <li>
                                                                                                        <i class="fa-li fa fa-check blue"></i>
                                                                                                        <p class="card-text">Professional tenant support throughout the tenant lifecycle</p>
                                                                                                    <li>
                                                                                                        <i class="fa-li fa fa-check blue"></i>
                                                                                                        <p class="card-text">Lowest PMS charges!</p>
                                                                                                    </li>
                                                                                                </ul>
                                                                                            </div>
                                                                                            <!--/.Card-->
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col col-border-pad">
                                                                                        <div class=" wow fadeIn text-center" data-wow-delay="0.2s">
                                                                                            <!--Card-->
                                                                                            <!--Card image-->
                                                                                            <img class="icon-img" src="../images/icons/ic-guaranteed-leasing@3x.png" alt="Guaranteed Rental Model(Lease PMS+)">
                                                                                            <!--Card content-->
                                                                                            <div class="card-body">
                                                                                                <!--Title-->
                                                                                                <h4 class="card-title dark-grey-text">
                                                                                                    <strong>Guaranteed Rental Model(Lease PMS+)</strong>
                                                                                                </h4>
                                                                                                <!--Text-->
                                                                                                <ul class="fa-ul">
                                                                                                    <li>
                                                                                                        <i class="fa-li fa fa-check blue"></i>
                                                                                                        <p class="card-text">Guaranteed monthly rental in addition to all the benefits listed under the Simple
                                                                                                            Leasing Model (Lease PMS)
                                                                                                    </li>
                                                                                                </ul>
                                                                                            </div>
                                                                                            <!--/.Card-->
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col col-border-pad">
                                                                                        <div class=" wow fadeIn text-center" data-wow-delay="0.2s">
                                                                                            <!--Card-->
                                                                                            <!--Card image-->
                                                                                            <img class="icon-img" src="../images/icons/ic-coliving@3x.png" alt="Easyleases Managed Co-Living Spaces">
                                                                                            <!--Card content-->
                                                                                            <div class="card-body">
                                                                                                <!--Title-->
                                                                                                <h4 class="card-title dark-grey-text">
                                                                                                    <strong>Co-Living Spaces</strong>
                                                                                                </h4>
                                                                                                <!--Text-->
                                                                                                <ul class="fa-ul">
                                                                                                    <li>
                                                                                                        <i class="fa-li fa fa-check blue"></i>
                                                                                                        <p class="card-text">Upgradation of the property to a fully furnished space ideal for singles
                                                                                                    </li>
                                                                                                    <li>
                                                                                                        <i class="fa-li fa fa-check blue"></i>
                                                                                                        <p class="card-text">Converting to multiple smaller units of 1 bed each, resulting up to 30% more rentals
                                                                                                    </li>
                                                                                                    <li>
                                                                                                        <i class="fa-li fa fa-check blue"></i>
                                                                                                        <p class="card-text">Professional rental management and tenant support services
                                                                                                    </li>
                                                                                                </ul>
                                                                                            </div>
                                                                                            <!--/.Card-->
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col col-border-pad">
                                                                                        <div class=" wow fadeIn text-center" data-wow-delay="0.2s">
                                                                                            <!--Card-->
                                                                                            <!--Card image-->
                                                                                            <img class="icon-img" src="../images/icons/ic-ondemand@3x.png" alt="On-Demand Services">
                                                                                            <!--Card content-->
                                                                                            <div class="card-body">
                                                                                                <!--Title-->
                                                                                                <h4 class="card-title dark-grey-text">
                                                                                                    <strong>On-Demand Services</strong>
                                                                                                </h4>
                                                                                                <!--Text-->
                                                                                                <ul class="fa-ul">
                                                                                                    <li>
                                                                                                        <i class="fa-li fa fa-check blue"></i>
                                                                                                        <p class="card-text">Property possession from builder
                                                                                                    </li>
                                                                                                    <li>
                                                                                                        <i class="fa-li fa fa-check blue"></i>
                                                                                                        <p class="card-text">Major & minor maintenance by qualified vendors
                                                                                                    </li>
                                                                                                    <li>
                                                                                                        <i class="fa-li fa fa-check blue"></i>
                                                                                                        <p class="card-text">Home Furnishing, Painting, Cleaning & Other Home Maintenance Services
                                                                                                    </li>
                                                                                                    <li>
                                                                                                        <i class="fa-li fa fa-check blue"></i>
                                                                                                        <p class="card-text">Registration, Khatha & Property Tax
                                                                                                    </li>
                                                                                                </ul>
                                                                                            </div>
                                                                                            <!--/.Card-->
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row mt-3">
                                                                                    <div class="offset-md-2 col-md-8 text-center">
                                                                                        <p class="text-center">Can’t decide? <a href="../contact">Just Call / Email Us</a>. We will help you choose the best option for you</p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </section>
                                                                    <section id="advantage" style="background: white;">
                                                                        <div class="container mt-3">
                                                                            <center>
                                                                                <h4 id="easy_lease" class="easyadtest2">THE EASYLEASES ADVANTAGES</h4>
                                                                            </center>
                                                                            <div classs="container text-center">
                                                                                <div class="row mt-5 wow easylessr">
                                                                                    <!--First column-->
                                                                                    <div class="col wow fadeIn text-center col-border-pad-ad" data-wow-delay="0.2s">
                                                                                        <!--Card-->
                                                                                        <!--Card image-->
                                                                                        <img class="icon-img" src="../images/icons/ic-hassle-free@3x.png"  alt="Best Property Management Services">
                                                                                        <!--Card content-->
                                                                                        <div class="card-body">
                                                                                            <!--Title-->
                                                                                            <h4 class="card-title dark-grey-text">
                                                                                                <strong>Hassle Free</strong>
                                                                                            </h4>
                                                                                            <!--Text-->
                                                                                            <p class="card-text card-text-aling">End-to-End Property Management Services incl. tenant acquisition & support </p>
                                                                                        </div>
                                                                                        <!--/.Card-->
                                                                                    </div>
                                                                                    <!--/.First column-->
                                                                                    <!--Second column-->
                                                                                    <div class="col wow fadeIn text-center col-border-pad-ad" data-wow-delay="0.4s">
                                                                                        <!--Card-->
                                                                                        <!--Card image-->
                                                                                        <img class="icon-img" src="../images/icons/ic-clock@3x.png"  alt="Guaranteed Rental Payments">
                                                                                        <!--Card content-->
                                                                                        <div class="card-body">
                                                                                            <!--Title-->
                                                                                            <h4 class="card-title dark-grey-text">
                                                                                                <strong>On-time Rentals</strong>
                                                                                            </h4>
                                                                                            <!--Text-->
                                                                                            <p class="card-text card-text-aling">Guaranteed on-time rental payments month on month</p>
                                                                                        </div>
                                                                                        <!--/.Card-->
                                                                                    </div>
                                                                                    <!--/.Second column-->
                                                                                    <!--Third column-->
                                                                                    <div class="col wow fadeIn text-center col-border-pad-ad" data-wow-delay="0.6s">
                                                                                        <!--Card-->
                                                                                        <!--Card image-->
                                                                                        <img class="icon-img" src="../images/icons/ic-maximize-rental@3x.png" alt="Maximize Property Rental Incomes">
                                                                                        <!--Card content-->
                                                                                        <div class="card-body">
                                                                                            <!--Title-->
                                                                                            <h4 class="card-title dark-grey-text">
                                                                                                <strong>Maximize Rentals</strong>
                                                                                            </h4>
                                                                                            <!--Text-->
                                                                                            <p class="card-text card-text-aling">Maximize Rentals - Potentially earn 30-50% higher rental on Co-Living Properties</p>
                                                                                        </div>
                                                                                        <!--/.Card-->
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row wow">
                                                                                    <div class="col wow fadeIn text-center col-border-pad-ad" data-wow-delay="0.2s">
                                                                                        <!--Card-->
                                                                                        <!--Card image-->
                                                                                        <img class="icon-img" src="../images/icons/ic-quality-maintanance@3x.png"  alt="Quality Home Maintenance">
                                                                                        <!--Card content-->
                                                                                        <div class="card-body">
                                                                                            <!--Title-->
                                                                                            <h4 class="card-title dark-grey-text">
                                                                                                <strong>Quality Maintenance</strong>
                                                                                            </h4>
                                                                                            <!--Text-->
                                                                                            <p class="card-text card-text-aling">High quality maintenance of your property through certified 3rd party vendors</p>
                                                                                        </div>
                                                                                        <!--/.Card-->
                                                                                    </div>
                                                                                    <!--/.First column-->
                                                                                    <!--Second column-->
                                                                                    <div class="col wow fadeIn text-center col-border-pad-ad" data-wow-delay="0.4s">
                                                                                        <!--Card-->
                                                                                        <!--Card image-->
                                                                                        <img class="icon-img" src="../images/icons/ic-transparency@3x.png"  alt="Real Time Property Tracking">
                                                                                        <!--Card content-->
                                                                                        <div class="card-body">
                                                                                            <!--Title-->
                                                                                            <h4 class="card-title dark-grey-text">
                                                                                                <strong>Complete Transparency</strong>
                                                                                            </h4>
                                                                                            <!--Text-->
                                                                                            <p class="card-text card-text-aling">Transparent, real-time tracking of rentals and maintenance expenses</p>
                                                                                        </div>
                                                                                        <!--/.Card-->
                                                                                    </div>
                                                                                    <!--/.Second column-->
                                                                                    <!--Third column-->
                                                                                    <div class="col wow fadeIn text-center col-border-pad-ad" data-wow-delay="0.6s">
                                                                                        <!--Card-->
                                                                                        <!--Card image-->
                                                                                        <img class="icon-img" src="../images/icons/ic-minimal-vacancies@3x.png"  alt="Get Tenants Faster">
                                                                                        <!--Card content-->
                                                                                        <div class="card-body">
                                                                                            <!--Title-->
                                                                                            <h4 class="card-title dark-grey-text">
                                                                                                <strong>Minimal Vacancies</strong>
                                                                                            </h4>
                                                                                            <!--Text-->
                                                                                            <p class="card-text card-text-aling">Pro-active follow-ups on expiring leases to make sure you do not lose rentals</p>
                                                                                        </div>
                                                                                        <!--/.Card-->
                                                                                    </div>
                                                                                    <!--/.Third column-->
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </section>
                                                                    <!--/.THE EASYLEASES ADVANTAGES end-->
                                                                    <!--/.EASY & TRANSPARENT PROCESS start-->
                                                                    <section id="transparent" class="mt-3">
                                                                        <div class="container">
                                                                            <center class="p-3">
                                                                                <h4 id="easy_lease" class="easyadtest2">SIMPLE & HASSLE-FREE PROCESS</h4>
                                                                            </center>
                                                                            <div class="row mt-5 wow easylessr">
                                                                                <!--First column-->
                                                                                <div class="col wow fadeIn col-border-pad text-center" data-wow-delay="0.2s">
                                                                                    <!--Card-->
                                                                                    <!--Card image-->
                                                                                    <img class="icon-img" src="../images/icons/ic-submit@3x.png"  alt="Submit Documents">
                                                                                    <!--Card content-->
                                                                                    <div class="card-body">
                                                                                        <!--Title-->
                                                                                        <h4 class="card-title dark-grey-text">
                                                                                            <strong>Submit</strong>
                                                                                        </h4>
                                                                                        <!--Text-->
                                                                                        <p class="card-text card-text-aling">Submit property details</p>
                                                                                    </div>
                                                                                    <div><img src="../images/icons/arrows.ico" class="Shape"></div>
                                                                                    <!--/.Card-->
                                                                                </div>
                                                                                <!--/.First column-->
                                                                                <!--Second column-->
                                                                                <div class="col wow fadeIn col-border-pad text-center" data-wow-delay="0.4s">
                                                                                    <!--Card-->
                                                                                    <!--Card image-->
                                                                                    <img class="icon-img" src="../images/icons/ic-property-live@3x.png"  alt="Visit" >
                                                                                    <!--Card content-->
                                                                                    <div class="card-body">
                                                                                        <!--Title-->
                                                                                        <h4 class="card-title dark-grey-text">
                                                                                            <strong>Visit</strong>
                                                                                        </h4>
                                                                                        <!--Text-->
                                                                                        <p class="card-text card-text-aling">Site visit by Easyleases Team, within 3 business days</p>
                                                                                    </div>
                                                                                    <div><img src="../images/icons/arrows.ico" class="Shape" style="margin-top: -145px;"></div>
                                                                                    <!--/.Card-->
                                                                                </div>
                                                                                <!--/.Second column-->
                                                                                <!--Third column-->
                                                                                <div class="col wow fadeIn col-border-pad text-center" data-wow-delay="0.6s">
                                                                                    <!--Card-->
                                                                                    <!--Card image-->
                                                                                    <img class="icon-img" src="../images/icons/ic-lease-model@3x.png"  alt="Lease Model">
                                                                                    <!--Card content-->
                                                                                    <div class="card-body">
                                                                                        <!--Title-->
                                                                                        <h4 class="card-title dark-grey-text">
                                                                                            <strong>Lease Model</strong>
                                                                                        </h4>
                                                                                        <!--Text-->
                                                                                        <p class="card-text card-text-aling">Choose your lease model
                                                                                        </p>
                                                                                    </div>
                                                                                    <div><img src="../images/icons/arrows.ico" class="Shape"></div>
                                                                                    <!--/.Card-->
                                                                                </div>
                                                                            </div>
                                                                            <div>
                                                                                <div class="row wow">
                                                                                    <div class="col col-border-pad text-center wow fadeIn" data-wow-delay="0.2s">
                                                                                        <!--Card-->
                                                                                        <!--Card image-->
                                                                                        <img class="icon-img" src="../images/icons/ic-documentation@3x.png"  alt="Paper Work">
                                                                                        <!--Card content-->
                                                                                        <div class="card-body">
                                                                                            <!--Title-->
                                                                                            <h4 class="card-title dark-grey-text">
                                                                                                <strong>Paper Work</strong>
                                                                                            </h4>
                                                                                            <!--Text-->
                                                                                            <p class="card-text card-text-aling">Paper work taken care of
                                                                                            </p>
                                                                                        </div>
                                                                                        <div><img src="../images/icons/arrows.ico" class="Shape"></div>
                                                                                        <!--/.Card-->
                                                                                    </div>
                                                                                    <!--/.First column-->
                                                                                    <!--Second column-->
                                                                                    <div class="col col-border-pad text-center wow fadeIn" data-wow-delay="0.4s">
                                                                                        <!--Card-->
                                                                                        <!--Card image-->
                                                                                        <img class="icon-img" src="../images/icons/ic-property-live@3x.png"  alt="Take Property Live">
                                                                                        <!--Card content-->
                                                                                        <div class="card-body">
                                                                                            <!--Title-->
                                                                                            <h4 class="card-title dark-grey-text">
                                                                                                <strong>Property Live</strong>
                                                                                            </h4>
                                                                                            <!--Text-->
                                                                                            <p class="card-text card-text-aling">Property goes live on 
                                                                                                Easyleases
                                                                                            </p>
                                                                                        </div>
                                                                                        <div><img src="../images/icons/arrows.ico" class="Shape"></div>
                                                                                        <!--/.Card-->
                                                                                    </div>
                                                                                    <!--3rd column-->
                                                                                    <div class="col col-border-pad text-center wow fadeIn" data-wow-delay="0.4s">
                                                                                        <!--Card-->
                                                                                        <!--Card image-->
                                                                                        <img class="icon-img" src="../images/icons/ic-ongoing-support@3x.png"  alt="Tenant Onboarding Support">
                                                                                        <!--Card content-->
                                                                                        <div class="card-body">
                                                                                            <!--Title-->
                                                                                            <h4 class="card-title dark-grey-text">
                                                                                                <strong>Ongoing Support</strong>
                                                                                            </h4>
                                                                                            <!--Text-->
                                                                                            <p class="card-text card-text-aling">Tenant onboarding & ongoing support</p>
                                                                                        </div>
                                                                                        <!-- <div><img src="http://13.229.194.243/pmsdev/web/images/icons/arrows.ico" class="Shape"></div> -->
                                                                                        <!--/.Card-->
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </section>
                                                                    <br>
                                                                    <br>
                                                                    <!--/.THE EASYLEASES ADVANTAGES end-->
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
                                                                                function login() {
                                                                                    window.location.href = "signup.html";
                                                                                }

                                                                                function listproperty() {
                                                                                    window.location.href = "listproperty.html";
                                                                                }

                                                                                function partner() {
                                                                                    window.location.href = "partnerwithus.html";
                                                                                }
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
                                                                                    $("#leadsowner-city").removeClass("waves-effect waves-light");
                                                                                });
                                                                                $(document).ready(function () {
                                                                                    $("#leadsowner-state").removeClass("waves-effect waves-light");
                                                                                });
                                                                                function cancelform()
                                                                                {

                                                                                    var $menu_collapser = $('body').find('.collapse-button');

                                                                                    $menu_collapser.click();
                                                                                }
                                                                    </script>




                                                                    <style type="text/css">
                                                                        .bantexth1{text-shadow: 1px 2px 30px black;}
                                                                        .bantexth2{text-shadow: 1px 2px 30px black;}


                                                                        #first_button{display:none;}

                                                                        .mydropdown {
                                                                            width: 100%;
                                                                            height: 45px;
                                                                        }
                                                                        .active {
                                                                            /*background-color:#d90000;*/
                                                                            color: #e24c3f !important
                                                                        }
                                                                        a.btn.btn-primary.btn_mycolor.active.waves-effect.waves-light {
                                                                            border: 1px solid #59abe3;
                                                                            color: #59abe3 !important;
                                                                        }
                                                                        .Shape {
                                                                            width: 32px;
                                                                            height: 32px;
                                                                            opacity: 0.4;
                                                                            float: right;
                                                                            margin-top: -121px;
                                                                        }
                                                                        .card-text:last-child {            
                                                                            text-align: left;
                                                                            height: auto !important;
                                                                        }
                                                                        .card-text-aling    {
                                                                            text-align: center !important;
                                                                        }
                                                                        .icon-img {
                                                                            width: 63px;
                                                                            height: 63px;
                                                                        }
                                                                        .col-border-pad {
                                                                            /* border: 1px solid red; */
                                                                            padding: 15px;
                                                                            margin: 15px;
                                                                            background: white;
                                                                        }
                                                                        .col-border-pad-ad{
                                                                            padding: 15px;
                                                                            margin: 15px;
                                                                            background: #fafafa;
                                                                        }
                                                                        section#service {
                                                                            background-color: #fafafa;
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
                                                                            right: 0;
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
                                                                        and (max-device-width:  736px)
                                                                        and (-webkit-min-device-pixel-ratio: 2)
                                                                        and (orientation: portrait) {

                                                                            .banfrmhome {width: 89% !important;}	
                                                                            .collapse-button {
                                                                                display:block !important;
                                                                                right: 0 !important;
                                                                                top:65% !important;
                                                                                z-index:9998;
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
                                                                                top: -124px;
                                                                                z-index: 9999;
                                                                            }

                                                                            .slimli{
                                                                                position: fixed;
                                                                                width: 100%;
                                                                                z-index: 9999;
                                                                                /*height: 489px;*/
                                                                                height: 87%;
                                                                                overflow-x: scroll;
                                                                            }

                                                                            .mt-5{z-index:2;}

                                                                            .copyright{z-index:850;}

                                                                            #first_button{display:block;}
                                                                        }

                                                                        .collapse-button{
                                                                            font-size: 15px;
                                                                            padding: 6px 5px 28px 0px !important;
                                                                            width: 37%;
                                                                            font-weight: bolder;
                                                                        }

                                                                        /* Landscape */
                                                                        @media only screen 
                                                                        and (min-device-width: 320px) 
                                                                        and (max-device-width: 736px)
                                                                        and (-webkit-min-device-pixel-ratio: 2)
                                                                        and (orientation: landscape) {

                                                                            #first_button{display:block;}
                                                                            .listmypropbox{ height: 374px; }

                                                                            .img-bg{height:100%;}


                                                                            .bantexth1{top:125px !important; font-size: 22px; text-shadow: 2px 2px 4px #5F5F5F;}
                                                                            .bantexth2{top:140px; font-size: 18px !important; text-shadow: 2px 2px 4px #5F5F5F;}
                                                                            .collapse-button{
                                                                                /*top: 271px !important;*/
                                                                                top: 88% !important; /* Server side issue alignment*/
                                                                                z-index:9998;
                                                                            }

                                                                            ul.slimmenu{
                                                                                margin-left:-15px !important;
                                                                            }

                                                                            ul.slimmenu.collapsed li{top: -23px; z-index: 9999;}
                                                                            .sign-up{ margin:0px;}
                                                                            .sign-up-button{width:50%; display:inline-block !important;}

                                                                            a.btn_mycolor.active{
                                                                                width:100%;
                                                                            }

                                                                            .btn_mycolor{width:100%;}
                                                                            ul.slimmenu{margin-left: -15px;}
                                                                            .slimli{
                                                                                position: fixed;
                                                                                height: 82%;    
                                                                                overflow-x: scroll;
                                                                                width:100%;            
                                                                            }

                                                                            .mt-5{z-index:2;}

                                                                            .copyright{z-index:850;}

                                                                        }

                                                                        /*
                                                                        
                                                                        @media only screen 
                                                                          and (min-device-width: 414px) 
                                                                          and (max-device-width: 738px)
                                                                          and (-webkit-min-device-pixel-ratio: 2)
                                                                          and (orientation: portrait) {
                                                                            .slimli{            
                                                                                    height: 489px;           
                                                                                }
                                                                          }
                                                                        */
                                                                        /*
                                                                         @media all and (max-width:451px) and (min-width:360px) {    
                                                                            .collapse-button {top:382px !important;}
                                                                         }
                                                                        */


                                                                        /* Portrait */
                                                                        @media only screen 
                                                                        and (min-device-width: 768px) 
                                                                        and (max-device-width: 1024px) 
                                                                        and (orientation: portrait) 
                                                                        and (-webkit-min-device-pixel-ratio: 1) {

                                                                            .collapse-button {
                                                                                display:block !important; 
                                                                                /*top: 90% !important;*/
                                                                                z-index:9998;
                                                                            }
                                                                            /*.bantexthome1{
                                                                                left: 40px;
                                                                                font-weight: 500;
                                                                                 text-shadow: 2px 2px 4px #5F5F5F;
                                                                            }
                                                                            .bantexthome2{
                                                                                left:15px;
                                                                                top: 272px;
                                                                            }
                                                                            .banfrmhome{left:26px; top: 80px;}
                                                                            #first_button{display:none !important;}*/

                                                                            .bantexth1{
                                                                                text-shadow: 2px 2px 4px #5F5F5F;
                                                                                top: 185px;
                                                                            }
                                                                            .bantexth2{
                                                                                top: 199px;
                                                                                text-shadow: 2px 2px 4px #5F5F5F;
                                                                            }

                                                                            .img-bg{
                                                                                height:550px !important;
                                                                                margin-bottom: 80px;
                                                                            }

                                                                            .sign-up{
                                                                                margin: -182px auto;
                                                                                z-index: 9999;
                                                                                padding: 33px 15px 29px;
                                                                            }

                                                                            .bgimgclass{
                                                                                top: -64px;
                                                                            }
                                                                            .sign-up-button{
                                                                                width:50%;
                                                                                display:inline-block !important;
                                                                            }

                                                                            .sign-up-input2{
                                                                                margin-bottom: 18px;

                                                                            }
                                                                            /*.slimmenu {           
                                                                              overflow-x: scroll;
                                                                               height: 505px;    
                                                                           }*/

                                                                            a.btn_mycolor.active{
                                                                                width:100%;
                                                                            }

                                                                            .btn_mycolor{width:100%;}   

                                                                            .slimli{
                                                                                position: fixed;
                                                                                width: 96%;
                                                                                z-index: 9999;
                                                                            }     

                                                                            .mt-5{z-index:2;}

                                                                            .copyright{z-index:850;}

                                                                        }

                                                                        /* Landscape */
                                                                        @media only screen 
                                                                        and (min-device-width: 768px) 
                                                                        and (max-device-width: 1024px) 
                                                                        and (orientation: landscape) 
                                                                        and (-webkit-min-device-pixel-ratio: 1) {

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






                                                                    </body>