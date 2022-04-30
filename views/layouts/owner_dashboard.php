<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAssetLogin;
use yii\helpers\Url;

AppAssetLogin::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>

    <head>
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
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>PMS</title>
        <!-- Bootstrap CSS CDN -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <!-- Our Custom CSS -->
        <link rel="stylesheet" href="<?php echo Yii::$app->userdata->autoVersion(Url::home(true) . 'css/css/newstyle_admin/style_o.css'); ?>">

        <script type="text/javascript" src="<?php echo Yii::$app->userdata->autoVersion(Url::home(true) . 'js/admin/script/myscript.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo Url::home(true); ?>js/validate.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

        <style>
            select.form-control {
                width: 89% !important;
                border-top: none;
                border-right: none;
                background: #f5f5f5;
                box-shadow: none;
                border-left: none;
                padding-left: 0;
                border: 1px solid #f5f5f5;
                border-bottom: 1px solid rgba(50, 58, 71, 0.2);
            }

            .bgstyle {
                background-color: #fafafa !important;
                background-color: var(--white);
            }

            .box+.box {
                margin-top: 2.5rem;
            }

            .well {
                min-height: 20px;
                padding-top: 19px;
                margin-bottom: 20px;
                background-color: transparent !important;
                border: none !important;
                border-radius: 0px !important;
                -webkit-box-shadow: none !important;
                box-shadow: none !important;
                padding-left: 0px;
            }

            .box {
                background-color: #e9e9e9;
                padding: 6.25rem 1.25rem;
            }

            .inputfile {
                width: 0.1px;
                height: 0.1px;
                opacity: 0;
                overflow: hidden;
                position: absolute;
                z-index: -1;
            }

            .input-group {
                position: relative;
                display: table;
                border-collapse: separate;
            }

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

            .identity_bg {
                /* background-color: #ffffff !important; */
                background-color: #f5f5f5;
                margin-top: 15px;
            }

            .adar_img {
                width: 286px;
                height: 288px;
            }

            .Mask {
                width: 225px;
                height: 280px;
                background-color: #e9e9e9;
            }

            div#bank_well {
                margin-top: 40px !important;
            }
            footer {
                /* background: rgba(0, 0, 0, 0) url(../images/footer_bg.jpg) repeat scroll 0 0; */
                float: left;
                padding: 33px 0 33px;
                width: 100%;
                background: #000;
                z-index: 9999;
                position: relative;
            }

            .menulist>ul {
                margin: 0;
                padding: 0;
            }

            .menulist li {
                color: #ffffff;
                font-size: 13px;
                list-style: outside none none;
                font-family: "Open Sans", sans-serif;
            }

            .menulist a {
                color: #949494;
                line-height: 30px;
                text-decoration: none;
            }

            .menulist li a:hover {
                color: #2196f3;
            }

            .menulist {
                padding-left: 56px;
            }

            .copyright {
                /*  background: rgba(61, 61, 61, 0.6) none repeat scroll 0 0 url(../images/footer_bg.jpg) repeat;
                */
                color: #949494;
                float: left;
                font-family: "Open Sans", sans-serif;
                font-size: 13px;
                padding: 7px;
                text-align: center;
                width: 100%;
                background: url(../images/copyright_bg.jpg) repeat;
                background: #333;
                z-index: 9999;
                position: relative;
            }

            .text-set {
                float: left;
                color: white !important;
            }

            .text-set>a {
                color: white;
                font-size: 12px;
            }

            .no-repeat {
                padding-top: 0px;
                padding-bottom: 0px;
            }

            .sidenav {
                background-color: #59abe3;
                margin-top: -15px;
                margin-left: -15px;
            }

            input#name,
            input#email,
            input#mobile {
                background-color: #59abe3;
                border-color: #ffff;
                border-top: 0;
                border-right: 0;
                border-left: 0;
                padding: 1px;
                color: #fff;
            }

            b#bold-text {
                font-size: 12px;
                font-weight: normal;
                font-style: normal;
                font-stretch: normal;
                line-height: 1.33;
                letter-spacing: 0.8px;
                text-align: left;
                color: #ffffff;
            }

            input.form-control {
                border-top: none;
                border-right: navajowhite;
                background: #fafafa;
                box-shadow: none;
                border-left: navajowhite;
                padding-left: 0;
            }

            .form-control {
                border-radius: 0px !important;
            }

            .disable_sec {
                z-index: -10000;
            }

            .cus_hide {
                display: none !important;
            }

            .cus_show {
                display: block !important;
            }

            .bgstyle {
                background-color: #fafafa !important;
                background-color: var(--white);
                /* margin-left: 15px; */
                padding-top: 15px;
            }

            input#address1,
            input#address2,
            select#state,
            select#city,
            input#pincode {
                background: #fafafa;
            }

            select#state {
                margin-bottom: 10px;
            }

            select#city {
                margin-bottom: 10px;
            }
            .profile_heading{
                margin-left: -15px;
            }
            input#namep,
            input#emailp,
            input#mobilep {
                background-color: #e24c3f;
                border-color: #ffff;
                border-top: 0;
                border-right: 0;
                border-left: 0;
                padding: 1px;
                color: #fff;
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
            .hide{
                display:none;  
            }
            ul#w0 > li>a {
                /* padding: 1px; */
                font-size: 0.9em !important;
                padding-left: 13px !important;
                background: #ffffff;
            }

            .dropdown-menu>.active>a,
            .dropdown-menu>.active>a:hover,
            .dropdown-menu>.active>a:focus {
                text-decoration: none;
                outline: 0;
                background-color: #1f2b3d !important;
                color: #fff !important;
            }
            a.active {
                border: 1px solid #5ba9dd;
                /* margin: -15px; */
                /* margin: 110px; */
                /* border-radius: 11px; */
            }
            .error1 {
                border: 1px solid red !important;
            }
            .cus_hide{
                display: none;
            }
            .cus_show{
                display: block;
            }
        </style>
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
    </head>
    <body>
<?php $this->beginBody() ?>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <div class="">
            <nav class="navbar navbar-default">
                <div class="container-fluid">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
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



                            </li>
                            <li style="margin-top: 5px;">

                                <div class="dropdown user-dropdown">
                                    <button type="button" class="btn btn-default dropdown-toggle custom-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                        <span class="custom_span_name"><?php echo Yii::$app->user->identity->full_name; ?> </span>
                                        <span class="caret"></span>
                                    </button>
                                    <ul id="w0" class="dropdown-menu userdropdown nav">
                                        <li class="active"><a href="<?php echo Url::home(true); ?>site/myprofile?type=op">My Profile</a></li>
                                        <li><a href="<?php echo Url::home(true); ?>site/mydashboard">My Dashboard</a></li>

                                        <li><a data-toggle="modal" data-target="#myModalc">Change Password</a></li>
                                        <li>
                                            <form action="<?php echo Url::home(true); ?>site/logout" method="POST">
                                                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>" /> 
                                                <button type="submit" class="btn btn-link logout" style="color:#000;" onclick="removesession()">Logout</button>
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


        <?php
        $action = $this->context->action->id;
        if (isset($_REQUEST['type'])) {
            $type = $_REQUEST['type'];
        } else {
            $type = '';
        }
        ?>
        <!--leftside-->


        <div class="wrapper">
<?php $paramid = Yii::$app->getRequest()->getQueryParam('id'); ?>
            <nav id="sidebar">
                <!-- <div class="sidebar-header">
                </div> -->
                <ul class="list-unstyled components">
                    <li>
                        <a href="<?php echo Url::home(true); ?>/site/mydashboard" <?php if ($action == 'mydashboard') { ?>class="active"<?php } ?>>
                            <img src="<?php echo Url::home(true); ?>images/icons/ic-dashboard.svg" width="8%"> My Dashboard</a>
                    </li>
                    <li>
                        <a href="<?php echo Url::home(true); ?>/site/ownerlease?id=<?php echo $paramid; ?>" <?php if ($action == 'ownerlease') { ?>class="active"<?php } ?>>
                            <img src="<?php echo Url::home(true); ?>images/icons/ic-my-agreement.svg" width="8%"> Service Level Agreement</a>
                    </li>
                    <li>
                        <a href="<?php echo Url::home(true); ?>/site/ownerpayments?id=<?php echo $paramid; ?>" <?php if ($action == 'ownerpayments') { ?>class="active"<?php } ?>>
                            <img src="<?php echo Url::home(true); ?>images/icons/ic-payment.svg" width="8%"> Payment Statement</a>
                    </li>
                    <!-- <li>
                        <a href="<?php echo Url::home(true); ?>/site/mytenants?id=<?php echo $paramid; ?>" <?php if ($action == 'ownerpayments') { ?>class="active"<?php } ?> >
                        <img src="<?php echo Url::home(true); ?>images/img/ic-shortlist-properties.png" width="8%"> Tenant Details</a> 
                    </li> -->
                    <li class="">
                        <a href="<?php echo Url::home(true); ?>/site/mytenants?id=<?php echo $paramid; ?>" <?php if ($action == 'mytenants') { ?>class="active"<?php } ?> aria-expanded="false" class="collapsed">
                            <img src="<?php echo Url::home(true); ?>images/icons/ic-myprofile.svg" width="8%"> Tenant Details</a> 

<?php if ($action == "tenantdetails") { ?>
                            <ul class="collapse in list-unstyled" id="homeSubmenut" aria-expanded="true">

                                <li>
                                    <a href="<?php echo Url::home(true); ?>site/tenantdetails?type=p&user=<?php echo $_GET['user']; ?>&prop_id=<?= $_GET['prop_id']; ?>&id=<?= $_GET['id'] ?>" onclick="mainpage();" <?php if (($type == 'op') || ($type == 'p')) { ?>class="active"<?php } ?>>Personal Information</a>
                                </li>
                                <li>
                                    <a href="<?php echo Url::home(true); ?>site/tenantdetails?type=i&user=<?php echo $_GET['user']; ?>&prop_id=<?= $_GET['prop_id']; ?>&id=<?= $_GET['id'] ?>" onclick="getaddressproof();" <?php if ($type == 'i') { ?>class="active"<?php } ?>>Identity and address proof</a>
                                </li>
                                <li>
                                    <a href="<?php echo Url::home(true); ?>site/tenantdetails?type=em&user=<?php echo $_GET['user']; ?>&prop_id=<?= $_GET['prop_id']; ?>&id=<?= $_GET['id'] ?>" onclick="getbankdetail();" <?php if ($type == 'em') { ?>class="active"<?php } ?>>Employment Details</a>
                                </li>
                                <li>
                                    <a href="<?php echo Url::home(true); ?>site/tenantdetails?type=l&user=<?php echo $_GET['user']; ?>&prop_id=<?= $_GET['prop_id']; ?>&id=<?= $_GET['id'] ?>" onclick="emergencycontact();" <?php if ($type == 'l') { ?>class="active"<?php } ?>>Lease Information</a>
                                </li>

                            </ul> 
<?php } ?>	
                    </li>
                    <li>
                        <a href="<?php echo Url::home(true); ?>/site/ownerrequests?id=<?php echo $paramid; ?>" <?php if ($action == 'ownerrequests') { ?>class="active"<?php } ?>>
                            <img src="<?php echo Url::home(true); ?>images/icons/ic-service-request.svg" width="8%"> Service Request</a>
                    </li>
                </ul>
            </nav>







<?= $content ?>
        </div>
        <div class="modal fade" id="myModalc" role="dialog">
            <div class="modal-dialog" style="width: 494px;height: 392px;">
                <!-- Modal content-->
                <div class="modal-content" style="background-color: #fafafa;">
                    <div class="modal-header" style="border-bottom: 1px solid #fafafa;">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title" style="font-size: 20px;text-align: center;">Change Password</h4>
                        <h5 style="font-size: 13px;text-align: center;">Please fill out the following fields to change password</h5>
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
                                    <div id="error_np" style="color:red;" class="cus_hide">b</div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="controls">
                                    <input type="password" name="confirm_password" id="confirm_password" placeholder="Repeat New Password *" onblur="checkconfirm()">
                                    <div id="error_cp" style="color:red;" class="cus_hide">c</div>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="submit" class="col-xs-12 btn btn-danger btn-load btn-lg" data-loading-text="Changing Password..." value="Change Password" onclick="changepassword();" style="width: 414px;height: 48px;border-radius: 3px;margin-left: 25px;">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <footer>
            <div class="container">
                <div class="menulist">
                    <ul class="footer-links">
                        <!--li><a href="javascritp:;">FAQ's</a></li>
                          <li><a href="javascritp:;">Privacy Policy</a></li>
                        </ul>
                            </div>
                    <div class="col-md-3 col-sm-3 menulist">
                            <ul>
                        <li><a href="javascritp:;">Site Usage Terms</a></li>
                          <li><a href="javascritp:;">About Us</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3 col-sm-3 menulist">
                            <ul>
                        <li><a href="javascritp:;">Contact Us</a></li>
                          <li><a href="javascritp:;">Social Media Links</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3 col-sm-3 menulist">
                            <ul>
                        <li><a href="javascritp:;">Terms &amp; Conditions</a></li>
                        </ul-->
                        <?php
                        echo Yii::$app->userdata->getFooter();
                        ?>
                </div>
            </div>

        </footer>

        <div class="copyright">&copy; 2018 Easyleases Technologies Private Limited</div>
<?php $this->endBody() ?>

        <script src="<?php echo Url::home(true); ?>js/app/bootstrap.min.js"></script>
        <script src="<?php echo Url::home(true); ?>js/app/jquery.mCustomScrollbar.concat.min.js"></script>
        <script src="<?php echo Yii::$app->userdata->autoVersion(Url::home(true) . 'js/owner.js'); ?>"></script>
        <script src="<?php echo Url::home(true); ?>js/validate.js"></script>
        <script type="text/javascript" src="../js/moment.js"></script>        
        <script type="text/javascript" src="../js/timepicker.js"></script>
        <link rel="stylesheet" href="../css/timepicker.css" />

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
                                    var setElementHeight = function () {
                                        var height = $(window).height();
                                        $('.autoheight').css('min-height', height);
                                    };

                                    $(document).ready(function () {
                                        setElementHeight();
                                    });

                                    $(".datetimepicker").datetimepicker({
                                        useCurrent: false,
                                        format: 'MM/YYYY',
                                        sideBySide: true,
                                        minDate: new Date(),
                                    })
                                    function changepassword()
                                    {
                                        var cp = $("#current_password").val();
                                        var np = $("#new_password").val();
                                        var rp = $("#confirm_password").val();
                                        if (cp == '')
                                        {
                                            $("#current_password").addClass('error1');
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
                                    function checkconfirm()
                                    {
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

                                    function removesession()
                                    {
                                        sessionStorage.removeItem('popvalid');
                                    }




        </script>

        <style>
            .autoheight{
                height: 100%;
            }
            div#myModalc {
                top: 100px;
                position: absolute;
                -moz-box-shadow: 0 0 10px rgba(0,0,0,0.4);
            }
            div#modal_body {
                height: 270px;
            }
            input#current_password, input#new_password, input#confirm_password {
                width: 414px;
                height: 48px;
                margin-left: 25px;
                background-color: #ffffff;
                border: solid 1px #e9ebf0;
                padding: 10px;
            }
        </style>

    </body>
</html>
<?php $this->endPage() ?>
