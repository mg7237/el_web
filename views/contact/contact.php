<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use yii\helpers\Url;

$this->title = 'Contact';
$this->params['breadcrumbs'][] = $this->title;
?>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="<?php echo Url::home(true); ?>css/css/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo Url::home(true); ?>css/css/mdb.min.css" rel="stylesheet">
<link href="<?php echo Yii::$app->userdata->autoVersion(Url::home(true) . 'css/css/style.css'); ?>" rel="stylesheet">
<link href="<?php echo Yii::$app->userdata->autoVersion(Url::home(true) . 'css/css/page.css'); ?>" rel="stylesheet">


<nav class="navbar navbar-expand-lg navbar-dark indigo fixed-top no-repeat">

    <a title="Easyleases Technologies Private Limited" class="navbar-brand" href="<?php echo Url::home(true); ?>"><img alt="Easyleases Property Management Services" src="<?= Yii::$app->request->baseUrl ?>/images/newlogo1.png"></a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">

        <ul class="navbar-nav mr-auto">

            <li class="nav-item pull-right">
                <a class="btn btn-primary btn_mycolor" href="<?php echo Url::home(true); ?>users/owner">List Your Property</a>
            </li>
            <li class="nav-item ">
                <a class="btn btn-primary btn_mycolor" href="<?php echo Url::home(true); ?>users/advisor">Partner With Us</a>
            </li>

            <?php if (Yii::$app->user->isGuest) { ?>
                <li class="nav-item ">
                    <a class="btn btn-primary btn_mycolor" href="<?php echo Url::home(true); ?>site/login">Sign In</a>
                </li>
            <?php } ?>
            <?php
            if (!Yii::$app->user->isGuest) {
                ?>
                <li class="nav-item ">
                    <a class="btn btn-blue-grey" href="<?php echo Url::home(true); ?>site/myprofile">My Profile</a>
                </li>
            <?php }
            ?>
        </ul>

    </div>

</nav>

<div id="container" class="autoheight page_con">
    <div class="row cst-row">
        <div class="col-lg-12 cst-clm">
            <header class="contactbg" title="Easyleases Property Management Services">
                <p class="text-center">
                    <!--<img src="../images/icons/contact_text.png" alt="" />-->&nbsp;
                </p>
            </header>
            <section id="propertyowner" class="content-section">
                <div class="container">
                    <div class="contactbox">
                        <div class="contactinner">
                            <div id="diamond">&nbsp;</div>
                            <div class="col-lg-8 contactleft">
                                <h1 class="headingcont" style="color: #59abe3 !important;">Send us a message <span class="iconright"><img src="../images/icons/ic-send-message.svg" alt="" /></span></h1>
                                <?php if (!empty(Yii::$app->session->getFlash('success'))) { ?>
                                    <div class="alert alert-success">
                                        <?= Yii::$app->session->getFlash('success'); ?>
                                    </div>
                                <?php } ?>
                                <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>
                                <div class="form-group">
                                    <div class="row">
                                        <!--<div class="col-xs-6">
                                                <div class="custom-select">
                                                    <button class="btn btn-transparent btn-grey" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <span class="filter-option">Customer Type</span>
                                                        <span class="caret fix-right"></span>
                                                    </button>
                                                    <input class="selected-value" value="" id="customerType" type="hidden">
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li><a href="#" data-value="new">New customer</a></li>
                                                        <li><a href="#" data-value="existing">Existing customer</a></li>
                                                    </ul>
                                            </div>
                                            <!--</div><!--.col-xs-6--> <!--div class="custom-select"><button id="categoryButton" class="btn btn-transparent btn-grey" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <span class="filter-option">You are a</span> </button> <input id="customerCategory" class="selected-value" name="customerCategory" type="hidden" value="" />
                                    <ul id="category_list" class="dropdown-menu" role="menu">
                                    <li><a data-value="tenant">Tenant</a></li>
                                    <li><a data-value="owner">Owner</a></li>
                                    <li><a data-value="Advisor">Advisor</a></li>
                                    </ul>
                                    </div-->
                                        <div class="col-lg">
                                            <div class="dropdown"><!--Trigger-->
                                                <div><select name="ContactForm[user_type]" class="btn btn-primary dropdown-toggle mydropdown" style="margin-left: 0px; margin-right: 0px; margin-bottom: 0px; border-radius: 4px; background-color: white !important; color: #888f95 !important;height: 10%;" aria-required="true" aria-invalid="true" id="optiondrop">
                                                        <option value="">You are a</option>
                                                        <option value="2">Rental Property Seeker</option>
                                                        <option value="4">Property Owner</option>
                                                        <option value="5">Partner</option>
                                                    </select></div>
                                            </div>
                                            <!--/Dropdown primary--> <!--custom-select--></div>
                                        <!--.col-xs-6--></div>
                                    <!--.row--></div>
                                <!--.form-group-->
                                <div class="form-group">
                                    <div class="input-main"><!--input id="contactName" class="form-control" style="padding-left: 35px;" name="name" required="required" type="text" placeholder="Name" /--> 

                                        <?= $form->field($model, 'name')->textInput(['autofocus' => true, 'id' => 'contactName', 'class' => 'form-control', 'placeholder' => 'Name', 'style' => 'padding-left: 35px;', 'label' => false]) ?>

<!--<img src="../images/icons/user_icon1.jpg" alt="" />-->



                                    </div>
                                    <!--.input-main--></div>
                                <!--.form-group-->
                                <div class="form-group">
                                    <div class="input-main formleft"><!--input id="contactPhone" class="form-control" style="padding-left: 35px;" name="contact" required="" type="text" placeholder="Phone Number" /--> 

                                        <?= $form->field($model, 'phone')->textInput(['id' => 'contactPhone', 'class' => 'form-control', 'placeholder' => 'Phone Number', 'style' => 'padding-left: 35px;', 'label' => false]) ?>
<!--<img src="../images/icons/user_icon4.jpg" alt="" />-->
                                    </div>
                                    <!--.input-main-->
                                    <div class="input-main formright"><!--input id="contactEmail" class="form-control" style="padding-left: 35px;" name="mail_from" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" required="required" type="email" placeholder="Email" /-->

                                        <?= $form->field($model, 'email')->textInput(['id' => 'contactEmail', 'class' => 'form-control', 'placeholder' => 'Email', 'style' => 'padding-left: 35px;', 'label' => false, 'type' => 'email', 'pattern' => '[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$']) ?>
 <!--<img src="../images/icons/user_icon2.jpg" alt="" />-->
                                    </div>
                                </div>
                                <!--.form-group--> <!--.form-group-->
                                <div class="form-group">
                                    <div class="input-main textareatop"><!--textarea id="contactMessage" class="form-control" style="padding-left: 35px;" name="msg" placeholder="Message"></textarea--> 

                                        <?= $form->field($model, 'message')->textarea(['rows' => 6, 'id' => 'contactMessage', 'class' => 'form-control', 'placeholder' => 'Message', 'style' => 'padding-left: 35px;']) ?>

<!--<img src="../images/icons/user_icon3.jpg" alt="" />-->
                                    </div>

                                    <div class="input-main textareatop">

                                        <?=
                                        $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                                            'template' => '<div class="row"><div class="col-lg-9">{input}</div><div class="col-lg-3">{image}</div></div>',
                                        ])
                                        ?></div>
                                    <!--.input-main--></div>
                                <!--.form-group--> <input id="contactSubmit" class="btn font-bold btn-big" style="background-color: #59abe3 !important;" name="send_msg" type="submit" value="SEND" /><input id="contactReset" class="btn font-bold btn-bigtwo" style="background-color: #e14c3e !important;" name="reset_msg" type="reset" value="RESET" /> <!--<a class="btn btn-orange font-bold btn-bigtwo" name="send_msg" href="index.php" id="contactSubmit">CANCEL</a>--> <?php ActiveForm::end(); ?></div>
                            <div class="col-lg-4 contactright">
                                <h2>Contact EasyLeases</h2>
                                <p><strong>Easyleases Technologies Pvt. Ltd.</strong><br />Co-work 247, 1st AA Cross,<br />2nd Main Road, Kasturi Nagar,<br />Bengaluru - 560043</p>
                                <p><img src="../images/icons/phone-icon_white.png" alt="" /><a style="color: white;" href="tel:+91 63646 75551">+91 63646 75551</a></p>
                                <p><img src="../images/icons/telephone-1.png" alt="" /><a style="color: white;" href="tel:080 4853 3307">080 4853 3307</a></p>
                                <p><img src="../images/icons/email_iconwhite.png" alt="" /><a style="color: white !important;" href="mailto:query@easyleases.in">query@easyleases.in</a></p>

                                <h2>About EasyLeases</h2>
                                <p>Easyleases is a DIPP-recognized, tech-led Start-up providing comprehensive Property Management Services for rental property owners & quality living spaces for tenants looking for rental houses. 
                                </p>
                                <p>We comprehensively manage rental properties right from finding the right tenant, providing property maintenance & ad-hoc services as cleaning, painting, etc, rental collection & tenant management, etc in a transparent fashion. Our Online Dashboard lets the Owners & Tenants to view in real-time their transactions, lease history, status of maintenance issues, etc. 
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<!-- JQuery -->
<script type="text/javascript" src="<?php echo Url::home(true); ?>/js/js/jquery-3.2.1.min.js"></script>
<!-- Bootstrap tooltips -->
<script type="text/javascript" src="<?php echo Url::home(true); ?>/js/js/popper.min.js"></script>
<!-- Bootstrap core JavaScript -->
<script type="text/javascript" src="<?php echo Url::home(true); ?>/js/js/bootstrap.min.js"></script>
<!-- MDB core JavaScript -->
<script type="text/javascript" src="<?php echo Url::home(true); ?>/js/js/mdb.min.js"></script>
<script>
    $(document).ready(function () {
        $("#optiondrop").focus();
    });
    function onScroll(event) {

        var scrollPos = $(document).scrollTop();
        $('#navbarSupportedContent a').each(function () {
            var currLink = $(this);
            var refElement = $(currLink.attr("href"));
            if (refElement.position().top <= scrollPos && refElement.position().top + refElement.height() > scrollPos) {
                $('#navbarSupportedContent ul li a').removeClass("active");
                currLink.addClass("active");
            } else {
                currLink.removeClass("active");
            }
        });
    }

</script>
<style>

    @media screen and (max-width: 767px){
        .contactright{
            width: 100%;
            min-height: auto;
            margin-top: 15px;
        }
        .contactbg p{
            padding-top: 150px !important;
        }
        .contactleft{
            width: 100%;
            margin-left: 0;
        }
        .page_con {
            margin-top: 66px;
        }
        .cst-row{
            margin-right: 0 !important;
            margin-left: 0 !important;
        }
        .cst-clm{
            padding-right: 0 !important;
            padding-left: 0 !important;
        }
    }

    .navbar {
        margin-top: 0px !important;
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
    #contactEmail {
        background-image: url(../images/icons/user_icon2.jpg) !important;
        background-position: 7px 7px;
        background-repeat: no-repeat;
    }
    #contactName {
        background-image: url(../images/icons/user_icon1.jpg) !important;
        background-position: 7px 7px;
        background-repeat: no-repeat;
    }
    #contactPhone {
        background-image: url(../images/icons/user_icon4.jpg) !important;
        background-position: 7px 7px;
        background-repeat: no-repeat;
    }
    #contactMessage {
        background-image: url(../images/icons/user_icon3.jpg) !important;
        background-position: 7px 7px;
        background-repeat: no-repeat;
    }
</style>
