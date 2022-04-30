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
<link href="<?php echo Yii::$app->userdata->autoVersion(Url::home(true) . 'css/css/style.css'); ?>" rel="stylesheet">
<link href="<?php echo Yii::$app->userdata->autoVersion(Url::home(true) . 'css/css/login.css'); ?>" rel="stylesheet">

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
                                    'id' => 'login-form', 'options' => [
                                        'class' => 'sign-up'
                                    ]
                        ]);
                        ?>
                        <!--<h1 class="sign-up-title">SIGN IN</h1>-->
                        <ul class="nav nav-tabs">
                            <li style="border-right: 1px solid white; margin-left: 10px;"><a data-toggle="tab" href="#login-with-email" class="active show">E-mail Login</a></li>
                            <li style="margin-right: 0px !important;"><a data-toggle="tab" href="#login-with-mobile">Phone Login</a></li>
                        </ul>
                        <br />
                        <div class="tab-content">
                            <div id="login-with-email" class="tab-pane active">

                                <?php if (isset($_REQUEST['ac']) && $_REQUEST['ac'] == 'cp') {
                                    ?>
                                    <center>
                                        <div id="sucess_msg">
                                            <?php echo Yii::$app->getSession()->getFlash('success'); ?>

                                        </div>
                                    </center>
                                <?php } else if (isset($_REQUEST['ac']) && $_REQUEST['ac'] == 'cn') {
                                    ?>
                                    <center>
                                        <div id="sucess_msg">
                                            <?php echo Yii::$app->getSession()->getFlash('error'); ?>

                                        </div>
                                    </center>
                                <?php } ?>
                                <?= $form->field($model, 'username')->textInput(['autofocus' => false, 'placeholder' => 'E-mail *', 'class' => 'sign-up-input'])->label(false) ?>
                                <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Password*', 'class' => 'sign-up-input'])->label(false) ?>
                                <?= Html::submitButton('Login', ['class' => 'btn savechanges_submit_login sign-up-button no-margin', 'name' => 'login-button']) ?>
                                <?= Html::Button('Cancel', ['class' => 'btn savechanges_submit_login sign-up-button no-margin', 'name' => 'cancel-button', 'style' => 'background-color: #6e6666;border-bottom: #6e6666;box-shadow: inset 0 -2px #6e6666;', 'onclick' => "history.go(-1);"]) ?>
                                <div class="form-group">
                                    <label class="checkbox">
                                        <span id="login_span"> <p>New User?<a href="<?php echo Url::home(true); ?>users/register">Sign Up</a><a id="forgot" href="<?php echo Url::home(true); ?>site/forget">Forgot Password?</a></p></span>
                                    </label>
                                </div>
                            </div>

                            <div id="login-with-mobile" class="tab-pane">

                                <?php if (isset($_REQUEST['ac']) && $_REQUEST['ac'] == 'cp') {
                                    ?>
                                    <center>
                                        <div id="sucess_msg">
                                            <?php echo Yii::$app->getSession()->getFlash('success'); ?>

                                        </div>
                                    </center>
                                <?php } else if (isset($_REQUEST['ac']) && $_REQUEST['ac'] == 'cn') {
                                    ?>
                                    <center>
                                        <div id="sucess_msg">
                                            <?php echo Yii::$app->getSession()->getFlash('error'); ?>

                                        </div>
                                    </center>
                                <?php } ?>

                                <?= $form->field($model, 'phone')->textInput(['autofocus' => false, 'placeholder' => 'Mobile number *', 'class' => 'sign-up-input isPhone'])->label(false) ?>
                                <input style="display: none;" type="text" id="loginform-otp" class="sign-up-input" maxlength="4" name="LoginForm[otp]" placeholder="Enter OTP *">
                                <div id="resend-otp" style="display: none;">
                                    <small>OTP valid for 5 minutes</small>
                                    <button id="resend-otp-btn" style="padding: 4px; padding-bottom: 0px; padding-top: 0px; background: #e24c3f !important;" class="btn btn-primary btn-xs" type="button">Resend OTP</button>
                                    <button id="another-otp-num" style="padding: 4px; padding-bottom: 0px; padding-top: 0px; float: right; background: #e24c3f !important;" class="btn btn-primary btn-xs" type="button">Try Other Phone</button>
                                </div>
                                <div id="sending-otp" style="display: none;"><small>Sending OTP ...</small></div>
                                <?= Html::submitButton('Send OTP', ['id' => 'send-otp', 'class' => 'btn savechanges_submit_login sign-up-button no-margin', 'name' => 'login-button', 'onclick' => 'sendOTP();']) ?>
                                <?= Html::submitButton('Login', ['id' => 'check-otp', 'style' => 'display: none;', 'class' => 'btn savechanges_submit_login sign-up-button no-margin', 'name' => 'otp-check', 'onclick' => 'checkOTP();']) ?>
                                <?= Html::Button('Cancel', ['class' => 'btn savechanges_submit_login sign-up-button no-margin', 'name' => 'cancel-button', 'style' => 'background-color: #6e6666;border-bottom: #6e6666;box-shadow: inset 0 -2px #6e6666;', 'onclick' => "history.go(-1);"]) ?>
                                <div class="form-group">
                                    <label class="checkbox">
                                        <span id="login_span"> <p>New User?<a href="<?php echo Url::home(true); ?>users/register">Sign Up</a><a id="forgot" href="<?php echo Url::home(true); ?>site/forget">Forgot Password?</a></p></span>
                                    </label>
                                </div>
                            </div>
                        </div>
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
    .form-group.field-loginform-phone {
        margin-bottom: 5px;
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
    a.btn.btn-primary.btn_mycolor.active.waves-effect.waves-light {
        border: 1px solid #59abe3;
        color: #59abe3 !important;
    }
    /*    .active {
            background-color:#d90000;
            color: #e24c3f !important
        }*/
</style>
<script>
    var a = '';
    var m = 0;
    function sendOTP() {
        event.preventDefault();
        var mobile = $('#loginform-phone').val();
        if (mobile == '' || mobile == "undefined") {
            alert('Mobile number is required');
        }
        m = mobile;
        var formData = {"phone": mobile};
        startLoader();
        $('#sending-otp').show();
        $.ajax({
            url: '<?= Url::home(true) ?>api/v2/users/generateotp',
            type: 'POST',
            data: JSON.stringify(formData),
            processData: false,
            contentType: 'application/json',
            success: function (data) {
                if (data != '' && data.status == true) {
                    $('#sending-otp').hide();
                    $('#resend-otp').show();
                    a = data.OTP;
                    $('#loginform-phone').hide();
                    $('#send-otp').hide();
                    $('#loginform-otp').show();
                    $('#check-otp').show();
                    setTimeout(function () {
                        a = '';
                    }, 300000);
                } else {
                    $('#sending-otp').hide();
                    alert(data.message);
                }
                hideLoader();
            },
            error: function () {
                hideLoader();
                alert('Something went wrong, please try after some time!');
            }
        });
    }

    $('#check-otp').click(function (e) {
        e.preventDefault();
<?php if (!empty($_GET['pmyd'])) { ?>
            var pmyd = '?pmyd=1';
<?php } else { ?>
            var pmyd = '';
<?php } ?>
        var formData = {"phone": m, "login": "phone"};
        var otp = $('#loginform-otp').val();
        if (otp == '') {
            alert('Please enter OTP');
            return false;
        }
        if (otp == a) {
            $.ajax({
                url: '<?= Url::home(true) ?>site/login' + pmyd,
                type: 'POST',
                data: JSON.stringify(formData),
                processData: false,
                contentType: 'application/json',
                success: function (data) {
                    if (data == 'failed') {
                        alert('Incorrect OTP, please try again.');
                    }
                    hideLoader();
                }
            });
        } else {
            alert("Incorrect OTP");
        }
    });

    $('#resend-otp-btn').click(function () {
        $('#resend-otp').hide();
        sendOTP();
    });

    $('#another-otp-num').click(function () {
        $('#check-otp').hide();
        $('#resend-otp').hide();
        $('#loginform-otp').hide();
        $('#loginform-phone').show();
        $('#send-otp').show();
    });
</script>