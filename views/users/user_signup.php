<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\authclient\widgets\AuthChoice;

$this->title = 'Applicant Signup';


/* @var $this yii\web\View */
/* @var $model app\models\Users */
/* @var $form yii\widgets\ActiveForm */
?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="<?php echo Url::home(true); ?>css/css/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo Url::home(true); ?>css/css/mdb.min.css" rel="stylesheet">
<link href="<?php echo Yii::$app->userdata->autoVersion(Url::home(true) . 'css/css/style.css'); ?>" rel="stylesheet">
<link href="<?php echo Yii::$app->userdata->autoVersion(Url::home(true) . 'css/css/register.css'); ?>" rel="stylesheet">

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
    <!-- <div style="height: 100vh;"> -->
    <div>
        <div class="flex-center"> 
            <div class="container">
                <div class="row">
                    <div class="offset-md-3 col-md-6">

                        <?php
                        $form = ActiveForm::begin(['options' => [
                                        'class' => 'sign-up'
                                    ]
                        ]);
                        ?>


                        <h1 class="sign-up-title">SIGN UP</h1>
                        <?php if (Yii::$app->session->hasFlash('registerFormSubmitted')) {
                            ?>
                            <center>
                                <div id="sucess_msg">
                                    <?php //echo "hello";die; ?>
                                    <?= Yii::$app->session->getFlash('registerFormSubmitted'); ?>
                                </div>   </center>
                            <?php
                        }
//echo "<pre>" ; print_r($_SESSION['attributes']); die;
                        ?>
                        <?= $form->field($model, 'full_name')->textInput(['value' => isset($_SESSION['attributes']['name']) ? $_SESSION['attributes']['name'] : $model->full_name, 'maxlength' => true, 'placeholder' => 'Full Name*', 'class' => 'isCharacter sign-up-input', 'autofocus' => true])->label(false) ?>
                        <?= $form->field($model, 'login_id')->textInput(['value' => isset($_SESSION['attributes']['email']) ? $_SESSION['attributes']['email'] : $model->login_id, 'maxlength' => true, 'placeholder' => 'Email*', 'class' => 'sign-up-input'])->label(false) ?>
                        <?= $form->field($model, 'phone')->textInput(['maxlength' => 15, 'placeholder' => 'Mobile Number*', 'class' => 'sign-up-input'])->label(false) ?>
                        <?= $form->field($model, 'password')->passwordInput(['maxlength' => true, 'placeholder' => 'Password*', 'class' => 'sign-up-input', 'onblur' => 'checkpassword', 'id' => 'pass_field'])->label(false) ?>
                        <?= $form->field($model, 'password_repeat')->passwordInput(['placeholder' => 'Confirm password*', 'class' => 'sign-up-input'])->label(false); ?>
                        <?php
                        $cities = \app\models\Cities::find()->where(['status' => 1])->all();
                        $cityData = ArrayHelper::map($cities, 'code', 'city_name');
                        ?>
                        <?= $form->field($modelTenant, 'lead_city')->dropDownList($cityData, ['prompt' => 'Property Location', 'class' => 'dropdown-toggle mydropdown', 'style' => 'color: grey; margin: 0; padding: 12px;'])->label(false); ?>
                        <?= Html::submitButton('SignUp', ['class' => 'btn savechanges_submit_login no-margin', 'class' => 'sign-up-button']) ?>
                        <?= Html::Button('Cancel', ['class' => 'btn savechanges_submit_login sign-up-button no-margin', 'name' => 'cancel-button', 'style' => 'background-color: #6e6666;border-bottom: #6e6666;box-shadow: inset 0 -2px #6e6666;', 'onclick' => "history.go(-1);"]) ?>
                        <div class="form-group">
                            <label class="checkbox">
                                <span id="login_span"> <p>Already Have an Account? <a href="<?php echo Url::home(true); ?>site/login">Sign In</a> </p></span>
                            </label>
                        </div>
                        <hr>
                        <p id="login_span">* Password should contain at least one numeric, alphanumeric, special character each.</p>
                        <?php ActiveForm::end(); ?>
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
    #sucess_msg {
        margin-top: -30px !important;
        background-color: transparent !important; 
        color: grey;
        margin-bottom: 15px;
        color: #0d5c92;
    }
</style>
