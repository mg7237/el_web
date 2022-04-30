<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAssetLogin;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

AppAssetLogin::register($this);
?>
<?= Html::csrfMetaTags() ?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <link rel="canonical" href="<?= Yii::$app->request->absoluteUrl; ?>" />
        <script>(function (w, d, s, l, i) {
                w[l] = w[l] || [];
                w[l].push({'gtm.start':
                            new Date().getTime(), event: 'gtm.js'});
                var f = d.getElementsByTagName(s)[0],
                        j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
                j.async = true;
                j.src =
                        'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
                f.parentNode.insertBefore(j, f);

            })(window, document, 'script', 'dataLayer', 'GTM-WHMGT8D');</script>

        <meta name="google-site-verification" content="uKtGStTmPBl5f6s9kJQ-o4C6Gk31Im0BtbHhBhhLGnI" />

        <meta name="geo.region" content="IN-KA" />
        <meta name="geo.placename" content="Bengaluru, Karnataka, India" />
        <meta name="geo.position" content="77.6617983; 13.0013648" />
        <meta name="robots" CONTENT="INDEX, FOLLOW"> 
        <meta name="revisit-after" content="1 days"/> 
        <meta name="author" content="Easyleases"> 
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-121786083-1"></script>
        <?php if (YII_ENV == 'prod') { ?>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag() {
                    dataLayer.push(arguments);
                }
                gtag('js', new Date());
                gtag('config', 'UA-121786083-1');
            </script>
<?php } ?>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
<?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>

<?php $this->head() ?>
        <script src="http://easyleases.in/web/js/app/jquery.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

        <link rel="stylesheet" href="<?php echo Yii::$app->userdata->autoVersion(Url::home(true) . 'css/css/newstyle_admin/style.css'); ?>">
        <script type="text/javascript" src="<?php echo Url::home(true); ?>js/js/pdf.worker.js"></script>
        <script type="text/javascript" src="<?php echo Url::home(true); ?>js/js/pdf.js"></script>
        <script type="text/javascript" src="<?php echo Yii::$app->userdata->autoVersion(Url::home(true) . 'js/admin/script/myscript.js'); ?>"></script>
        <script src="http://easyleases.in/web/js/app/jquery.min.js"></script>
        <script src="<?php echo Url::home(true); ?>js/validate.js"></script>


        <style>
            input.form-control {
                border-top: none;
                border-right: navajowhite;
                background: #fafafa;
                box-shadow: none;
                border-left: navajowhite;
                padding-left: 0;
            }

            button#first_button {
                background-color: #F44336;
                border-color: #f44336;
                width: 39%;
                /* margin-top: -15px; */
                /* margin-top: -42px; */
                width: 140px;
                height: 40px;
                border-radius: 3px;
                font-size: 13px;
                font-weight: bold;
                font-style: normal;
                font-stretch: normal;
            }

            .bgstyle {
                background-color: #fafafa !important;
                background-color: var(--white);
                margin-left: 15px;
            }

            .form-control {
                border-radius: 0px !important;
            }

            select.form-control {
                width: 100% !important;
                border-top: none;
                border-right: none;
                background: #fafafa;
                box-shadow: none;
                border-left: none;
                padding-left: 0;
            }

            #name1 {
                margin-top: -5px !important;
            }
            /* custom css for dropdown and change password */

            .custom-toggle {
                width: 125px;
            }

            .dropdown-menu.userdropdown {
                background: white;
                border: 1px solid #cccccc;
                border-radius: 0;
                padding: 0px;
            }

            .dropdown-menu {
                background-clip: padding-box;
                background-color: #ffffff;
                border: 1px solid #cccccc;
                border-radius: 0;
                box-shadow: 0 6px 12px rgba(0, 0, 0, 0.176);
                display: none;
                float: left;
                font-size: 14px;
                left: 0;
                list-style: outside none none;
                margin: 2px 0 0;
                /* min-width: 160px; */
                min-width: 115px;
                padding: 5px 0;
                position: absolute;
                text-align: left;
                top: 100%;
                z-index: 1000;
            }

            .dropdown-menu>.active>a,
            .dropdown-menu>.active>a:hover,
            .dropdown-menu>.active>a:focus {
                text-decoration: none;
                outline: 0;
                background-color: #1f2b3d !important;
                color: #fff !important;
            }

            .userdropdown.dropdown-menu>li>a {
                clear: both;
                color: #000;
                display: block;
                font-size: 14px;
                font-weight: normal;
                line-height: 1.42857;
                padding: 11px 38px 11px 11px;
                white-space: nowrap;
                padding-left: 15px !important;
                background: white;
            }

            .dropdown-menu.userdropdown>li {
                border-bottom: 1px solid #ffffff;
                background: white !important;
            }

            ul ul a {
                font-size: 0.9em !important;
                padding-left: 30px !important;
                /* background: #ffffff; */
            }

            .navbar-right .dropdown-menu {
                left: -70px;
                /* right: 73px; */
                width: 203px;
                /* top: 55px; */
            }

            @media (min-width: 768px) {
                .navbar-right .dropdown-menu {
                    left: auto;
                    right: 0;
                }
            }

            .modal {
                text-align: center;
                padding: 0!important;
            }

            .modal:before {
                content: '';
                display: inline-block;
                height: 100%;
                vertical-align: middle;
                margin-right: -4px;
            }

            .modal-dialog {
                display: inline-block;
                text-align: left;
                vertical-align: middle;
            }

            a {
                cursor: pointer;
            }

            .modal-header {
                text-align: center;
            }

            .modal-body {
                position: relative;
                padding: 15px;
                height: 300px;
                background-color: #fafafa;
                background-color: var(--white);
                box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.5);
            }

            input#current_password {
                width: 100%;
                height: 46px;
                border-radius: 5px;
                border: 1px solid #d5cdcd;
                padding: 15px;
            }

            input#new_password {
                width: 100%;
                height: 46px;
                border-radius: 5px;
                border: 1px solid #d5cdcd;
                padding: 15px;
            }

            input#confirm_password {
                width: 100%;
                height: 46px;
                border-radius: 5px;
                border: 1px solid #d5cdcd;
                padding: 15px;
            }
            .tooltip_span
            {
                width: 155px;
                float: right;
            }
            .tooltip_s{
                width: 310px;
                float: right;
            }
            .model_css
            {
                height: 350px !important;
            }
            /* custom css for dropdown and change password end */
        </style>
        <style>
            img#preview_image {
                height: 100%;
                width: 100%;
                margin: 0 auto;
                width: 46em;
            }
            a.active {
                border: 1px solid #5ba9dd;
                /* margin: -15px; */
                /* margin: 110px; */
                /* border-radius: 11px; */
            }

            .logout {
                clear: both !important;
                color: #000 !important;
                display: block !important;
                font-size: 13px !important;
                font-weight: normal !important;
                line-height: 1.42857 !important;
                padding: 11px 38px 11px 11px !important;
                white-space: nowrap !important;
                padding-left: 15px !important;
                background: white !important;
            }

            .index_img {
                background: rgba(235, 235, 235, 0.1) none repeat scroll 0 0;
                border: 1px solid #2b3b55;
                /* border-radius: 100%; */
                float: left;
                height: 45px;
                text-align: center;
                width: 45px;
                overflow: hidden;
            }
            .sidenav {
                background-color: #59abe3;
                / margin-top: 36px; /
                margin: -15px;
            }
            @media (min-width: 768px){
                .container-fluid>.navbar-collapse, .container-fluid>.navbar-header, .container>.navbar-collapse, .container>.navbar-header {
                    margin-right: 35px;
                    margin-left: 12px;
                }
            }
            .dclass
            {
                background-color: #eeeded;
            }

            .nameclass {
                margin-right: 35px !important;
            }
            .error {
                border: 1px solid red;
            }
            .error1 {
                border: 1px solid red !important;
            }
            .error11 {
                color: red !important;
                display: block !important;
            }
            .cus_hide{
                display: none;
            }
            .cus_show{
                display: block;
            }
            .edit_input_bg{
                background: #ffffff !important;
            }
            .h4, h4 {
                font-size: 16px !important;
                font-weight: bold;
            }
        </style>


    </head>
    <body>

<?php $this->beginBody(); ?>


        <!-- Notification box for Booking and Rent payment response [STARTS HERE] -->
<?php if (!empty(Yii::$app->session->getFlash('noti-success')) || !empty(Yii::$app->session->getFlash('noti-error'))) { ?>
            <div id="pop-up-notification-cover" style="height: 100%; width: 100%; background: black !important; position: fixed; z-index: 9999; opacity: 0.8;">&nbsp;</div>
            <div id="pop-up-notification" style="position: fixed; min-height: 100px; z-index: 9999; padding: 26px; background: white;
                 border: 2px solid lightgrey; top: 5%; box-shadow: 0px 5px 25px 5px black; <?= (!empty(Yii::$app->session->getFlash('noti-error'))) ? 'min-width: 470px; left: 35%; right: 35%;' : 'min-width: 600px; left: 25%; right: 25%;'; ?>">
                <div class="container" style="width: 100%;">
                    <div class="row">
                        <div style="
                             font-weight: bold;
                             background-color: #ECECEC;
                             <?php echo (!empty(Yii::$app->session->getFlash('noti-success'))) ? 'color: #155724;' : ''; ?>
                                 <?php echo (!empty(Yii::$app->session->getFlash('noti-error'))) ? 'color: #E24C3F;' : ''; ?>
                             " class="col-xs-12 col-sm-12 col-md-12 col-xl-12 alert text-center" role="alert">
                                 <?php echo (!empty(Yii::$app->session->getFlash('noti-success'))) ? Yii::$app->session->getFlash('noti-success') : ''; ?>
    <?php echo (!empty(Yii::$app->session->getFlash('noti-error'))) ? Yii::$app->session->getFlash('noti-error') : ''; ?>
                        </div>
                    </div>
    <?php if (!empty(Yii::$app->session->getFlash('noti-success'))) { ?>
                        <div class="row">
                            <div style="font-weight: bold;" class="col-xs-6 col-sm-6 col-md-6 col-xl-6">
                                Client Name:
                            </div>
                            <div style="font-size: 14px; color: rgb(82, 86, 89);" class="col-xs-6 col-sm-6 col-md-6 col-xl-6">
        <?= Yii::$app->userdata->getUserNameById(Yii::$app->user->id) ?>
                            </div>
                        </div>
                        <hr />
                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-6 col-xl-6">
                                <div class="row">
                                    <div style="font-weight: bold;" class="col-xs-12 col-sm-12 col-md-12 col-xl-12">
                                        Payment Amount
                                    </div>
                                </div>
                                <div class="row">
                                    <div style="font-size: 14px; color: rgb(82, 86, 89);" class="col-xs-12 col-sm-12 col-md-12 col-xl-12">
        <?php echo (!empty(Yii::$app->session->getFlash('noti-amount'))) ? Yii::$app->session->getFlash('noti-amount') : ''; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6 col-xl-6">
                                <div class="row">
                                    <div style="font-weight: bold;" class="col-xs-12 col-sm-12 col-md-12 col-xl-12">
                                        Payment Description
                                    </div>
                                </div>
                                <div class="row">
                                    <div style="font-size: 14px; color: rgb(82, 86, 89);" class="col-xs-12 col-sm-12 col-md-12 col-xl-12">
        <?php echo (!empty(Yii::$app->session->getFlash('noti-paydesc'))) ? Yii::$app->session->getFlash('noti-paydesc') : ''; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-6 col-xl-6">
                                <div class="row">
                                    <div style="font-weight: bold;" class="col-xs-12 col-sm-12 col-md-12 col-xl-12">
                                        Transaction Date
                                    </div>
                                </div>
                                <div class="row">
                                    <div style="font-size: 14px; color: rgb(82, 86, 89);" class="col-xs-12 col-sm-12 col-md-12 col-xl-12">
        <?php echo (!empty(Yii::$app->session->getFlash('noti-txndate'))) ? Yii::$app->session->getFlash('noti-txndate') : ''; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6 col-xl-6">
                                <div class="row">
                                    <div style="font-weight: bold;" class="col-xs-12 col-sm-12 col-md-12 col-xl-12">
                                        Transaction Id
                                    </div>
                                </div>
                                <div class="row">
                                    <div style="font-size: 14px; color: rgb(82, 86, 89);" class="col-xs-12 col-sm-12 col-md-12 col-xl-12">
        <?php echo (!empty(Yii::$app->session->getFlash('noti-txnid'))) ? Yii::$app->session->getFlash('noti-txnid') : ''; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-6 col-xl-6">
        <?php $form = ActiveForm::begin(['action' => 'createnotidownload', 'options' => ['enctype' => 'multipart/form-data']]); ?>
                                <input type="hidden" name="noti-download" value="<?= (!empty(Yii::$app->session->getFlash('noti-download'))) ? base64_encode(Yii::$app->session->getFlash('noti-download')) : ''; ?>" />
                                <button type="submit" style="width: 100%; font-weight: bold; font-size: 16px;" class="btn btn-md btn-primary btn_mycolor">
                                    Download
                                </button>
        <?php ActiveForm::end(); ?>
                            </div>
                            <div style="margin: auto;" class="col-xs-6 col-sm-6 col-md-6 col-xl-6">
                                <button type="button" id="pop-up-notification-close" style="width: 100%; font-weight: bold; font-size: 16px;" class="btn btn-md btn-primary btn_mycolor">
                                    Close
                                </button>
                            </div>
                        </div>
    <?php } else { ?>
                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-6 col-xl-6">
                                <form action="<?php echo Url::home(true) . 'paytm/bookproperty'; ?>" method="post">
                                    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken; ?>" />
                                    <input type="hidden" name="prop_book_retry" value="1" />
                                    <input type="hidden" name="order_id" value="<?= Yii::$app->session->getFlash('noti-retry'); ?>" />
                                    <input class="btn btn-md btn-primary btn_mycolor" style="width: 96%; font-weight: bold; font-size: 16px;" type="submit" name="submit_retry" value="Retry" />
                                </form>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6 col-xl-6">
                                <button id="pop-up-notification-close" style="width: 100%; font-weight: bold; font-size: 16px;" class="btn btn-md btn-primary btn_mycolor">
                                    Close
                                </button>
                            </div>
                        </div>
    <?php } ?>
                </div>
            </div>
<?php } ?>
        <!-- Notification box for Booking and Rent payment response [ENDS HERE] -->


        <?php
        $action = $this->context->action->id;
        if (isset($_REQUEST['type'])) {
            $type = $_REQUEST['type'];
        } else {
            $type = '';
        }
        ?>
        <div class="">
            <nav class="navbar navbar-default">
                <div class="container-fluid">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header cust-navbarheader">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <li>
                            <a href="<?php echo Url::home(true); ?>"><img src="<?php echo Url::home(true); ?>images/newlogo1.png"></a>
                        </li>
                    </div>
                    <?php
                    $lencount = strlen(Yii::$app->user->identity->full_name);
                    if ($lencount >= 15) {
                        $nameclass = "nameclass";
                    } else {
                        $nameclass = "";
                    }
                    ?>
                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav navbar-right custom_nav_right <?php echo $nameclass; ?>">
                            <?php
                            $dataImage = Yii::$app->userdata->getProfileImageById(Yii::$app->user->id, Yii::$app->user->identity->user_type);
                            if ($dataImage != '') {
                                $imgarr = (explode("/", $dataImage));
                                $filename = Url::home(true) . $dataImage;
                                $headers = get_headers($filename, 1);
                            } else {
                                $filename = '';
                                $headers = array();
                                $headers[0] = '';
                            }
                            ?>
                            <li>


                                <?php if (isset($imgarr[2]) && $headers[0] == "HTTP/1.1 200 OK") { ?>
                                    <img src="<?php echo Url::home(true) . $dataImage; ?>" width="30%" alt="" style="float: right;" id="index_img" class="index_img">
                                <?php } else { ?>
                                    <img src="<?php echo Url::home(true); ?>uploads/profiles/no-image.png" width="25px" alt="" style="float: right;" id="index_img">
<?php } ?>

<!--<span id="remaining_day_pass" ></span>-->	
                            </li>                                                                                                                                                
                            <li style="margin-top: 5px;">

                                <div class="dropdown user-dropdown">
                                    <button type="button" class="btn btn-default dropdown-toggle custom-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" id="passdropbtn">
                                        <span class="custom_span_name"><?php echo Yii::$app->user->identity->full_name; ?> </span>
                                        <span class="caret"></span>
                                    </button>
                                    <ul id="w0" class="dropdown-menu userdropdown nav">
                                        <li class="active"><a href="<?php echo Url::home(true); ?>site/myprofile?type=p">My Profile</a></li>
                                        <li><a href="<?php echo Url::home(true); ?>site/myfavlist">My Shortlist Properties</a></li>
                                        <li><a href="<?php echo Url::home(true); ?>site/wallet">My Wallet</a></li>


                                        <li><a data-toggle="modal" data-target="#myModalc" id="fchangepassword">Change Password</a></li>
                                        <li>
                                            <form action="<?php echo Url::home(true); ?>site/logout" method="POST">
                                                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>" /> 
                                                <button type="submit" class="btn btn-link logout" style="color:#000;">Logout</button>
                                            </form> 
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <!-- /.navbar-collapse -->
                </div>
                <!-- /.container-fluid -->
            </nav>
        </div>
        <div class="wrapper">
            <!-- Sidebar Holder -->
            <nav id="sidebar">
                <!-- <div class="sidebar-header">
                </div> -->
                <ul class="list-unstyled components">
                    <li class="open" id="myprofile_dropdown" >
                        <a href="#homeSubmenu" id="homeSubmenutab" data-toggle="collapse" aria-expanded="false" class="collapsed">
                            <img src="<?php echo Url::home(true); ?>images/img/ic-myprofile.png" width="8%" class="collapsed"> My Profile</a>
                        <ul class="collapse list-unstyled" id="homeSubmenu" aria-expanded="true">
                            <li>
                                <a href="<?php echo Url::home(true); ?>site/myprofile?type=p" onclick="mainpage();" <?php if (($type == 'p') || ($action == 'myprofile' && $type == '')) { ?>class="active"<?php } ?>>Personal Information</a>
                            </li>
                            <li>
                                <a href="<?php echo Url::home(true); ?>site/myprofile?type=i" onclick="getaddressproof();" <?php if ($type == 'i') { ?>class="active"<?php } ?>>Identity and address proof</a>
                            </li>
                            <li>
                                <a href="<?php echo Url::home(true); ?>site/myprofile?type=b" onclick="getbankdetail();" <?php if ($type == 'b') { ?>class="active"<?php } ?>>My Bank details</a>
                            </li>
                            <li>
                                <a href="<?php echo Url::home(true); ?>site/myprofile?type=e" onclick="employedeatil();" <?php if ($type == 'e') { ?>class="active"<?php } ?>>My Employment Data</a>
                            </li>
                            <li>
                                <a href="<?php echo Url::home(true); ?>site/myprofile?type=em" onclick="emergencycontact();" <?php if ($type == 'em') { ?>class="active"<?php } ?>>My Emergency Contact Details</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="<?php echo Url::home(true); ?>site/myfavlist" <?php if ($action == 'myfavlist') { ?>class="active"<?php } ?>>
                            <img src="<?php echo Url::home(true); ?>images/img/ic-shortlist-properties.png" width="8%"> My Shortlist Properties</a>
                    </li>
                    <li>
                        <a href="<?php echo Url::home(true); ?>site/wallet" onclick="getwallet();" <?php if ($action == 'wallet') { ?>class="active"<?php } ?>>
                            <img src="<?php echo Url::home(true); ?>images/img/ic-wallet.png" width="8%"> My Wallet</a>
                    </li>
                    <li>
                        <a href="<?php echo Url::home(true); ?>" onclick="searchproperty();">
                            <img src="<?php echo Url::home(true); ?>images/img/ic-search-property.png" width="8%"> Search Property</a>
                    </li>
                </ul>
            </nav>


<?= $content ?>

        </div>
        <footer class="mt-5">
            <div class="container">

                <div class="footer-links" style="float: left; width: 80%;">
                    <?php
                    echo Yii::$app->userdata->getFooter();
                    ?>
                </div>
                <div class="social-media-links" style="float: right; width: 20%;">
                    <?php
                    echo Yii::$app->userdata->getSocialMediaLinks();
                    ?>
                </div>
            </div>
        </footer>
        <!--copyright-->
        <div class="copyright">© 2018 Easyleases Technologies Private Limited</div>
        <!-- Modal start -->
        <div class="modal fade" id="myModalc" role="dialog" data-keyboard="false" data-backdrop="static" >
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" id="close"data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Change Password</h4>
                        <h5>Please fill out the following fields to change password</h5>
                    </div>
                    <div class="modal-body" id="modal_body">
                        <form method="post" id="passwordForm" name="passwordForm" action="<?php echo Url::home(true); ?>users/changepassword">
                            <div class="form-group">
                                <div class="controls">
                                    <input type="password" name="current_password" id="current_password" placeholder="Old Password *" onblur="checkoldpass()">
                                    <div id="error_op" style="color:red;" class="cus_hide">a</div>
                                    <input type="hidden" id="_csrf" class="form-control name1" value="<?= Yii::$app->request->getCsrfToken() ?>" name="_csrf">
                                    <input type="hidden" id="actionname" class="form-control name1" value="<?php echo $action; ?>" name="actionname">
                                    <input type="hidden" id="typename" class="form-control name1" value="<?php echo $type; ?>" name="typename">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="controls">
                                    <input type="password" name="new_password" id="new_password" placeholder="New Password *" onblur="checkpass();">
                                    <div id="new_pass" style="display:none;">Password must not be same as old password.</div><div id="error_np" style="color:red;" class="cus_hide">b</div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="controls">
                                    <input type="password" name="confirm_password" id="confirm_password" placeholder="Repeat New Password *" onblur="checkconfirm()">
                                    <div id="error_cp" style="color:red;" class="cus_hide">c</div>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="submit" class="col-xs-12 btn btn-danger btn-load btn-lg" data-loading-text="Changing Password..." value="Change Password" onclick="changepassword();">
                            </div>

                            <a id="forgot" style="color: blue; float: right; margin-top: 10px;" href="<?php echo Url::home(true); ?>site/forget">Forgot Password?</a>
                        </form>
                    </div>
                </div>

            </div>
        </div>
        <!-- Modal End -->
<?php $this->endBody() ?>
            <!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>-->
        <script src="<?php echo Url::home(true); ?>js/app/bootstrap.min.js"></script>
        <script src="<?php echo Url::home(true); ?>js/app/jquery.mCustomScrollbar.concat.min.js"></script>
        <script src="<?php echo Yii::$app->userdata->autoVersion(Url::home(true) . 'js/applicant.js'); ?>"></script>
        <!--script src="<?php echo Url::home(true); ?>js/validate.js"></script-->
        <!-- Plugin JavaScript -->
        <script src="<?php echo Url::home(true); ?>js/app/jquery.easing.min.js"></script>
        <!-- Theme JavaScript -->
        <script src="<?php echo Url::home(true); ?>js/app/agency.min.js"></script>

        <!--dorpdown js-->
        <script  type="text/javascript" src="<?php echo Url::home(true); ?>js/app/typeahead.bundle.js"></script>
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script type="text/javascript" src="../js/moment.js"></script>
        <script type="text/javascript" src="../js/timepicker.js"></script>
        <link rel="stylesheet" href="../css/timepicker.css" />
        <script>
                                    $(function () {

                                        $(".datepicker").datepicker({
                                            minDate: new Date(),
                                            changeMonth: true,
                                            changeYear: true,
                                            // yearRange: "-0:+1",
                                            //numberOfMonths: 1,
                                            dateFormat: 'dd-M-yy',
                                        });

                                        $(".datepicker_default").datetimepicker({
                                            useCurrent: false,
                                            format: 'DD-MMM-YYYY',
                                            sideBySide: true,

                                        });

                                    });
        </script>

        <script>
            $(function () {
                var dateFormat = 'dd-M-yy';
                from = $("#from")
                        .datepicker({
                            minDate: new Date(),
                            changeMonth: true,
                            changeYear: true,
                            dateFormat: dateFormat,
                            yearRange: "-0:+1",
                            numberOfMonths: 1
                        })
                        .on("change", function () {
                            to.datepicker("option", "minDate", getDate(this));
                        }),
                        to = $("#to").datepicker({
                    defaultDate: "+1m",
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: dateFormat,
                    yearRange: "-0:+2",
                    numberOfMonths: 1
                })
                        .on("change", function () {
                            from.datepicker("option", "maxDate", getDate(this));
                        });

                function getDate(element) {
                    var date;
                    try {
                        date = $.datepicker.parseDate(dateFormat, element.value);
                    } catch (error) {
                        date = null;
                    }

                    return date;
                }

                // var maxDate = $( "#visit_date" ).datepicker( "option", "maxDate" );
                // $('#propertyvisits-visit_time').timepicker({
                //     timeFormat: 'h:mm p',
                //     interval: 60,
                //     minTime: '10',
                //     maxTime: '6:00pm',
                //     defaultTime: '11',
                //     startTime: '10:00',
                //     dynamic: false,
                //     dropdown: true,
                //     scrollbar: true
                // });

                $("#visit_date").datepicker({
                    minDate: new Date(),
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: dateFormat,
                    yearRange: "-0:+1",
                    numberOfMonths: 1,
                    maxDate: "+4d",
                    // currentDate: new Date().getDate(),
                    // beforeShowDay: function (date) {

                    // if (date.getDay() == 3 ) {
                    // return [false, ''];
                    // }
                    // return [true, ''];
                    // }
                });
                var currentdate = new Date();
                if (currentdate.getDay() != 3) {
                    $("#visit_date").datepicker("setDate", currentdate);
                } else {
                    currentdate.setDate(currentdate.getDate() + 1);
                    $("#visit_date").datepicker("setDate", currentdate);
                }

                $("#visit_date2").datepicker({
                    minDate: new Date(),
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: dateFormat,
                    yearRange: "-0:+1",
                    numberOfMonths: 1,
                    maxDate: "+4d"
                });
            });



            $(document).on('click', '.close_time_picker', function () {
                $('.time_suggestion').html();
                $('.time_suggestion').hide();
                $('.field-propertyvisits-visit_time .help-block').show();
                $('.close_time_picker').remove();
            });
            $(document).on('click', '.apply_time', function () {
                $('.visit_time_class').val($(this).text());
                $('.time_suggestion').html();
                $('.time_suggestion').hide();
                $('.field-propertyvisits-visit_time .help-block').show();
                $('.close_time_picker').remove();

            });
            $(document).ready(function () {



                $('.visit_time_class').on('focus', function () {
                    var list = '';
                    var hh = '';
                    var mm = '';
                    for (var i = 11; i <= 21; i++) {
                        if (i <= 9) {
                            hh = '0' + i;
                        } else {
                            hh = '' + i;
                        }
                        var j = 0;
                        while (j <= 55) {
                            if (j < 30) {
                                mm = '0' + j;
                            } else {
                                mm = '' + j;
                            }
                            j = j + 30;
                            if (hh == 21 && mm == 30) {

                            } else {
                                list = list + '<li class="apply_time">' + hh + ':' + mm + '</li>';
                            }
                        }


                        // for(var j=0;j<=60){
                        //  console.log(j);
                        //  j=j+5;

                        // }
                    }
                    $('.time_suggestion').html(list);
                    $('.close_time_picker').remove();
                    $('.time_suggestion').show();
                    $('.field-propertyvisits-visit_time .help-block').hide();
                    $('.field-propertyvisits-visit_time').append('<span class="close_time_picker">X</span>')
                });
            });


        </script>




        <script>
            $(document).ready(function () {
                $('[data-toggle="popover"]').popover({

                    placement: 'right',

                    trigger: 'hover'

                });
            });

            $(document).ready(function () {
                $(".select-change-onload").change(function () {
                    $(this).find("option:selected").each(function () {
                        var optionValue = $(this).attr("value");
                        if (optionValue) {
                            $(".box").not("." + optionValue).hide();
                            $("." + optionValue).show();
                        } else {
                            $(".box").hide();
                        }
                    });
                }).change();
            });


            $(document).click(function (e) {
                if (!$('#propertyvisits-visit_time').is(e.target) || !$('#propertyvisits-visit_time').has(e.target)) { // check if the click is inside a div or outside
                    // if it is outside then hide all of them
                    $('.time_suggestion').hide();
                }
            });
            var setElementHeight = function () {
                var height = $(window).height();
                $('.autoheight').css('min-height', height);
            };

            $(document).ready(function () {
                setElementHeight();
            });
            $(document).ready(function () {
                $(".clickable-row").click(function () {
                    window.location = $(this).data("href");
                });

                $(".clickable-owner").click(function () {
                    window.location = $(this).data("href");
                });
            });
        </script>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#sidebarCollapse').on('click', function () {
                    $('#sidebar').toggleClass('active');
                });
            });
            function changepassword()
            {
                var cp = $("#current_password").val();
                var np = $("#new_password").val();
                var rp = $("#confirm_password").val();
                if (cp == '')
                {
                    $("#current_password").addClass('error1');
                    event.preventDefault();
                } else if (np == cp)
                {
                    $("#new_pass").addClass('error11');

                    event.preventDefault();
                } else if (np == '')
                {
                    $("#new_password").addClass('error1');
                    event.preventDefault();
                } else if (rp == '')
                {
                    $("#confirm_password").addClass('error1');
                    event.preventDefault();

                } else {
                    $("#passwordForm").submit();
                }
            }


            $(function () {
                if ((window.location.href == "<?php echo Url::home(true); ?>site/myprofile?type=p") || (window.location.href == "<?php echo Url::home(true); ?>site/myprofile?type=b") || (window.location.href == "<?php echo Url::home(true); ?>site/myprofile?type=i") || (window.location.href == "<?php echo Url::home(true); ?>site/myprofile?type=e") || (window.location.href == "<?php echo Url::home(true); ?>site/myprofile?type=em") || (window.location.href == "<?php echo Url::home(true); ?>site/myprofile")) {
                    $("#homeSubmenutab").trigger("click");
                }
            });

            function checkconfirm()
            {
                var cp = $("#current_password").val();
                var np = $("#new_password").val();
                var rp = $("#confirm_password").val();
                if (np != rp)
                {

                    $("#error_cp").html("Confirm Password must be equal to New Password");
                    $("#error_cp").addClass("cus_show").removeClass("cus_hide");
                    $("#confirm_password").val('');
                    $("#modal_body").addClass("model_css");

                } else {
                    $("#error_cp").html("");
                    $("#error_cp").addClass("cus_hide").removeClass("cus_show");
                    $("#modal_body").removeClass("model_css");
                }
            }

            function checkpass()
            {

                var np = $("#new_password").val();

                if (!np.match(/(?=.*[A-Za-z])(?=.*\d)(?=.*[$@$!%*#?&])[A-Za-z\d$@$!%*#?&]{6,12}$/))
                {
                    $("#error_np").html("Password must be at least 6 characters long and contain at least one alphabet, numeric and special character each");
                    $("#error_np").addClass("cus_show").removeClass("cus_hide");
                    $("#new_password").val('');
                    $("#modal_body").addClass("model_css");

                } else {
                    $("#error_np").html("");
                    $("#error_np").addClass("cus_hide").removeClass("cus_show");
                    $("#modal_body").removeClass("model_css");
                }
            }

            function checkoldpass()
            {
                var cp = $("#current_password").val();
                var csrf = "<?= Yii::$app->request->getCsrfToken() ?>";
                $.ajax({
                    url: "<?= \Yii::$app->getUrlManager()->createUrl('site/checkoldpass') ?>",
                    type: "POST",
                    data: {"cp": cp, "_csrf": csrf},
                    success: function (html) {
                        if (html == '0')
                        {
                            //alert("hello");
                            $("#error_op").html("Old Password is incorrect");
                            $("#error_op").addClass("cus_show").removeClass("cus_hide");
                            $("#current_password").val('');
                            $("#modal_body").addClass("model_css");


                        } else {
                            $("#error_op").html("");
                            $("#error_op").addClass("cus_hide").removeClass("cus_show");
                            $("#modal_body").removeClass("model_css");
                        }
                    }
                });
            }





        </script>

        <style type="text/css">
            footer {
                /* background: rgba(0, 0, 0, 0) url(../images/footer_bg.jpg) repeat scroll 0 0; */
                float: left;
                padding: 33px 0 33px;
                width: 100%;
                background: #000;
                z-index: 998;
                position: relative;
            }
            .menulist > ul {
                margin: 0;
                padding: 0;
            }
            .menulist li {
                color: #ffffff;
                font-size: 13px;
                list-style: outside none none;
                font-family: "Open Sans",sans-serif;
            }
            .menulist a {
                color: #949494;
                line-height: 30px;
                text-decoration:none;
            }
            .menulist li a:hover { color:#2196f3;}
            .menulist {
                padding-left: 56px;
            }
            .copyright {
                /*  background: rgba(61, 61, 61, 0.6) none repeat scroll 0 0 url(../images/footer_bg.jpg) repeat;
                */  color: #949494;
                float: left;
                font-family: "Open Sans",sans-serif;
                font-size: 13px;
                padding:7px;
                text-align: center;
                width: 100%;
                background:url(../images/copyright_bg.jpg) repeat;
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
            .no-repeat{
                padding-top: 0px;
                padding-bottom: 0px;
            }

        </style>

    </body>
</html>
<?php $this->endPage() ?>
<script>
    $(document).ready(function () {
        $('#pop-up-notification-close').click(function () {
            document.getElementById("pop-up-notification-cover").style.display = "none";
            document.getElementById("pop-up-notification").style.display = "none";
        });
    });
</script>