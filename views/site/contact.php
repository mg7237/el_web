<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use yii\helpers\Url;

$this->title = 'Contact Us - Easyleases | Rental Property Management Companies';
$this->view = 'Contact Us - Easyleases, Property Management. Easyleases Technologies Pvt. Ltd. Co-work 247, 1st AA Cross, 2nd Main Road, Kasturi Nagar, Bengaluru - 560043';
$this->params['breadcrumbs'][] = $this->title;
$this->params['meta_desc'][] = $this->view;
?>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="<?php echo Url::home(true); ?>css/css/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo Url::home(true); ?>css/css/mdb.min.css" rel="stylesheet">
<link href="<?php echo Url::home(true); ?>css/css/style.css" rel="stylesheet">
<link href="<?php echo Url::home(true); ?>css/css/page.css" rel="stylesheet"> 


<nav class="navbar navbar-expand-lg navbar-dark indigo fixed-top no-repeat">
    <!-- Navbar brand -->
    <a class="navbar-brand" href="<?php echo Url::home(true); ?>"><img src="<?= Yii::$app->request->baseUrl ?>/images/newlogo1.png"></a>
    <!-- Collapse button -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
    <!-- Collapsible content -->
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <!-- Links -->
        <ul class="navbar-nav mr-auto">
            <!--li class="nav-item ">
                <a class="nav-link small" href="#searchproperty">Search Property <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item ">
                <a class="nav-link small " href="#ourservice">Our Services</a>
            </li>
            <li class="nav-item ">
                <a class="nav-link small" href="#advantage">Advantages</a>
            </li>
            <li class="nav-item ">
                <a class="nav-link small" href="#">How it works</a>
            </li-->
            <li class="nav-item pull-right">
                <a class="btn btn-primary btn_mycolor" href="<?php echo Url::home(true); ?>users/owner">List Your Property</a>
            </li>
            <li class="nav-item ">
                <a class="btn btn-primary btn_mycolor" href="<?php echo Url::home(true); ?>users/advisor">Partner With Us</a>
            </li>
            <!--li class="nav-item ">
                <button type="button" class="btn btn-blue-grey">FAQ</button>
            </li-->
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
        <!-- Links -->
    </div>
    <!-- Collapsible content -->
</nav>
<div id="container" class="autoheight page_con">
    <div class="row">
        <div class="col-lg-12">
            <header class="contactbg">
                <p class="text-center"><img src="../images/icons/contact_text.png" alt="" /></p>
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
                                                <div><select name="ContactForm[user_type]" class="btn btn-primary dropdown-toggle mydropdown" style="margin-left: 0px; margin-right: 0px; margin-bottom: 0px; border-radius: 4px; background-color: white !important; color: #888f95 !important;" aria-required="true" aria-invalid="true">
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
                                <p><img src="../images/icons/phone-icon_white.png" alt="" />+91 63646 75551 +91 97418 26005</p>
                                <p><img src="../images/icons/email_iconwhite.png" alt="" /><a style="color: white !important;" href="mailto:query@easyleases.in">query@easyleases.in</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<style>
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