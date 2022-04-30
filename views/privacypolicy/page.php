<?php

use yii\helpers\Html;
use yii\helpers\Url;
?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="<?php echo Url::home(true); ?>css/css/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo Url::home(true); ?>css/css/mdb.min.css" rel="stylesheet">
<link href="<?php echo Yii::$app->userdata->autoVersion(Url::home(true) . 'css/css/style.css'); ?>" rel="stylesheet">
<?php
if ($_GET['slug'] == "faq-s") {
    ?>
    <link href="<?php echo Yii::$app->userdata->autoVersion(Url::home(true) . 'css/css/faq.css'); ?>" rel="stylesheet"> 
    <?php
} else {
    ?>
    <link href="<?php echo Yii::$app->userdata->autoVersion(Url::home(true) . 'css/css/page.css'); ?>" rel="stylesheet"> 

<?php }
?>

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
        text-align: center;
    }
    .text-set > a {
        color: white;
        font-size: 12px;
    }
    .no-repeat{
        padding-top: 0px;
        padding-bottom: 0px;
    }
    .text-center {
        text-align: center!important;
        margin-top: 10px !important;
    }
</style>

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
            <?php
            if (count($data) != 0) {
                echo $data['description'];
            }
            ?>
        </div>
    </div>
</div>
