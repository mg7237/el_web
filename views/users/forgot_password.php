<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="<?php echo Url::home(true); ?>css/css/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo Url::home(true); ?>css/css/mdb.min.css" rel="stylesheet">
<link href="<?php echo Url::home(true); ?>css/css/style.css" rel="stylesheet">
<link href="<?php echo Url::home(true); ?>css/css/login.css" rel="stylesheet" >

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
                <!-- <li class="nav-item ">
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
                </li> -->
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
                        <a class="btn btn-primary btn_mycolor active" href="<?php echo Url::home(true); ?>site/login">Sign In</a>
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
    <div>
        <div class="flex-center"> 
            <div class="container">
                <div class="row">
                    <div class="offset-md-3 col-md-6">

                        <?php
                        $form = ActiveForm::begin([
                                    'id' => 'forgotpassword-form',
                                    'options' => ['class' => 'sign-up'],
                        ]);
                        ?>
                        <h1 class="sign-up-title">SIGN IN</h1>
                        <?= $form->field($model, 'email')->hiddenInput(['value' => $email])->label(false); ?>


                        <?=
                        $form->field($model, 'newpass', ['inputOptions' => [
                                'placeholder' => 'New Password'
                    ]])->passwordInput(['autofocus' => false, 'class' => 'sign-up-input'])->label(false);
                        ?>


                        <?=
                        $form->field($model, 'repeatnewpass', ['inputOptions' => [
                                'placeholder' => 'Repeat New Password'
                    ]])->passwordInput(['class' => 'sign-up-input'])->label(false);
                        ?>


                        <?=
                        Html::submitButton('Change password', [
                            'class' => 'btn savechanges_submit_login sign-up-button no-margin'
                        ])
                        ?>

                        <!--div class="form-group">
                            <label class="checkbox">
                                <span id="login_span"> <p>New User?<a href="<?php echo Url::home(true); ?>users/register">Sign Up</a><a id="forgot" href="<?php echo Url::home(true); ?>site/forget">Forgot Password?</a></p></span>
                            </label>
                        </div-->
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 
<!-- SCRIPTS -->
<!-- JQuery -->
<script type="text/javascript" src="<?php echo Url::home(true); ?>/js/js/jquery-3.2.1.min.js"></script>
<!-- Bootstrap tooltips -->
<script type="text/javascript" src="<?php echo Url::home(true); ?>/js/js/popper.min.js"></script>
<!-- Bootstrap core JavaScript -->
<script type="text/javascript" src="<?php echo Url::home(true); ?>/js/js/bootstrap.min.js"></script>
<!-- MDB core JavaScript -->
<script type="text/javascript" src="<?php echo Url::home(true); ?>/js/js/mdb.min.js"></script>
</body>
<style>
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
    a.btn.btn-primary.btn_mycolor.active.waves-effect.waves-light {
        border: 1px solid red;
    }
    .active {
        /*background-color:#d90000;*/
        color: #e24c3f !important
    }
</style>