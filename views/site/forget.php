<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = 'Forget Password';
$this->params['breadcrumbs'][] = $this->title;
?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="<?php echo Url::home(true); ?>css/css/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo Url::home(true); ?>css/css/mdb.min.css" rel="stylesheet">
<link href="<?php echo Yii::$app->userdata->autoVersion(Url::home(true) . 'css/css/style.css'); ?>" rel="stylesheet">
<link href="<?php echo Yii::$app->userdata->autoVersion(Url::home(true) . 'css/css/forgot.css'); ?>" rel="stylesheet">
<style>
    .navbar {
        margin-top: 0px !important;
    }
    .sign-up {
        /*margin: 100px auto;*/
        width: 484px; 
        width: 100%;
        padding: 19px 23px 18px;
        background-color: #c4c4c4c2;
        border-bottom: 1px solid #c4c4c4;
        box-shadow: 0 1px 5px rgba(0, 0, 0, 0.25);
    }
    .mt-5, .my-5 {
        margin-top: -1rem!important;
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

    #res_error {
        color: #b93131;
        margin-top: -35px;
        margin-bottom: 15px;
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
    <div>
        <div class="flex-center">
            <div class="container">
                <div class="row">
                    <div class="offset-md-3 col-md-6">
                        <?php
                        $form = ActiveForm::begin([
                                    'id' => 'contact-form', 'options' => [
                                        'class' => 'sign-up'
                                    ]
                        ]);
                        ?>

                        <h1 class="sign-up-title">FORGOT PASSWORD</h1>
                        <?php if (Yii::$app->session->hasFlash('success')) {
                            ?>
                            <center>
                                <div id="sucess_msg">
                                    <?= Yii::$app->session->getFlash('success'); ?>
                                </div>   
                            </center>
                            <?php
                        }
                        ?>
                        <?php if (Yii::$app->session->hasFlash('error')) {
                            ?>
                            <center>
                                <div id="res_error">
                                    <?= Yii::$app->session->getFlash('error'); ?>
                                </div>   
                            </center>
                            <?php
                        }
                        ?>
                        <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'placeholder' => 'E-mail *', 'class' => 'sign-up-input', 'autofocus' => false])->label(false) ?>
                        <?= Html::submitButton('Submit', ['class' => 'btn savechanges_submit_login sign-up-button no-margin', 'name' => 'contact-button', 'id' => 'button']) ?>
                        <?= Html::Button('Cancel', ['class' => 'btn savechanges_submit_login sign-up-button no-margin', 'name' => 'cancel-button', 'style' => 'background-color: #6e6666;border-bottom: #6e6666;box-shadow: inset 0 -2px #6e6666;', 'onclick' => "history.go(-1);"]) ?>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>sign-up
</body>
<!-- JQuery -->
<script type="text/javascript" src="<?php echo Url::home(true); ?>/js/js/jquery-3.2.1.min.js"></script>
<!-- Bootstrap tooltips -->
<script type="text/javascript" src="<?php echo Url::home(true); ?>/js/js/popper.min.js"></script>
<!-- Bootstrap core JavaScript -->
<script type="text/javascript" src="<?php echo Url::home(true); ?>/js/js/bootstrap.min.js"></script>
<!-- MDB core JavaScript -->
<script type="text/javascript" src="<?php echo Url::home(true); ?>/js/js/mdb.min.js"></script>
<!--            <script>
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

</script>-->

<style>

</style>