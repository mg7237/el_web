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
        <script src="<?php echo Url::home(true); ?>js/app/jquery.min.js"></script>
        <link href="<?php echo Url::home(true); ?>css/applicant.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="<?php echo Url::home(true); ?>css/all.css" rel="stylesheet"></head>
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans" />
    <!--script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script-->
    <script src="../assets/3eeaab70/yii.js"></script>

    <script src="<?php echo Url::home(true); ?>js/validate.js"></script>

</head>
<body>
    <div class="loaderMain">
        <img src="<?php echo Url::home(true); ?>images/loader/25.gif">
    </div>
<?php $this->beginBody() ?>

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
                            <form action="<?php echo Url::home(true) . 'paytm/payrent'; ?>" method="post">
                                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken; ?>" />
                                <input type="hidden" name="tenant_pay_retry" value="1" />
                                <input type="hidden" name="app_redirect_url" value="<?php echo Url::home(true) ?>site/mypaymentsdue" />
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

    <nav id="mainNav" class="navbar navbar-default">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span> Menu <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand page-scroll" href="<?php echo Url::home(true); ?>"><img src="<?php echo Url::home(true); ?>images/property_logo.png" alt=""></a>
            </div>
            <div class="navbar-right butlogout">
                <?php
                if (!Yii::$app->user->isGuest) {

                    $dataImage = Yii::$app->userdata->getProfileImageById(Yii::$app->user->id, Yii::$app->user->identity->user_type);
                    ?>
                    <div class="login_but">
                        <div class="userimg"><img width="28" src="<?php echo $dataImage ? "../" . $dataImage : Url::home(true) . 'images/user_icon.png' ?>" alt=""></div>
                        <div class="btn-group selectduser" role="group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <?php echo Yii::$app->user->identity->full_name; ?>
                                <span class="caret"></span>
                            </button>

                            <?php
                            echo Nav::widget([
                                'options' => ['class' => 'dropdown-menu userdropdown'],
                                'items' => [
                                    ['label' => 'My Dashboard', 'url' => ['/external'], 'visible' => Yii::$app->userdata->usertype == 6],
                                    ['label' => 'My Dashboard', 'url' => ['/site/mydashboard'], 'visible' => Yii::$app->userdata->usertype == 4],
                                    ['label' => 'My Profile', 'url' => ['/advisers/myprofile'], 'visible' => Yii::$app->userdata->usertype == 5],
                                    ['label' => 'My profile', 'url' => ['/site/myprofile'], 'visible' => Yii::$app->userdata->usertype == 2 || Yii::$app->userdata->usertype == 3],
                                    ['label' => 'My profile', 'url' => ['/external/myprofile'], 'visible' => Yii::$app->userdata->usertype == 7],
                                    ['label' => 'My Agreements', 'url' => ['/site/myagreements'], 'visible' => Yii::$app->userdata->usertype == 3],
                                    ['label' => 'Payments', 'url' => ['/site/mypaymentsdue'], 'visible' => Yii::$app->userdata->usertype == 3],
                                    ['label' => 'Service Request', 'url' => ['/site/myrequests'], 'visible' => Yii::$app->userdata->usertype == 3],
                                    ['label' => 'My Shortlisted Properties', 'url' => ['/site/myfavlist'], 'visible' => Yii::$app->userdata->usertype == 2],
                                    ['label' => 'Wallet', 'url' => ['/site/wallet'], 'visible' => Yii::$app->userdata->usertype == 2],
                                    ['label' => 'Change Password', 'url' => ['/users/changepassword'], 'visible' => Yii::$app->userdata->usertype == 2 || Yii::$app->userdata->usertype == 3],
                                    ['label' => 'Change Password', 'url' => ['/users/ownerpassword'], 'visible' => Yii::$app->userdata->usertype == 4],
                                    ['label' => 'Change Password', 'url' => ['/users/advisorpassword'], 'visible' => Yii::$app->userdata->usertype == 5],
                                    Yii::$app->user->isGuest ? (
                                            ['label' => 'Login', 'url' => ['/site/login']]
                                            ) : (
                                            '<li>'
                                            . Html::beginForm(['/site/logout'], 'post')
                                            . Html::submitButton(
                                                    'Logout', ['class' => 'btn btn-link logout', 'style' => 'color:#000;']
                                            )
                                            . Html::endForm()
                                            . '</li>'
                                            )
                                ],
                            ]);
                            ?>
                        </div>
                    </div>

<?php } ?>
            </div>
            <!--  <div class="navbar-right butlogout">
            <?php
//  echo '<a>'
// . Html::beginForm(['/site/logout'], 'post')
// . Html::submitButton(
//     '<img src="'.Url::home(true).'images/logout_but.png" alt="">',
//     ['class' => 'btn btn-link logout','style'=>'color:#000;']
// )
// . Html::endForm()
// . '</a>'
            ?>
        
                 </div> -->
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>

    <!--leftside-->
    <aside id="left-panel">
        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse">

                <?php
                echo Nav::widget([
                    'options' => ['class' => 'nav in', 'id' => 'side-menu'],
                    'encodeLabels' => false,
                    'items' => [
                        ['label' => '<span><i class="fa fa-tachometer" aria-hidden="true"></i></span>My Dashboard', 'url' => ['/site/dashboard'], 'visible' => Yii::$app->userdata->usertype == 3],
                        ['label' => '<span><i class="fa fa-user-o" aria-hidden="true"></i></span>My profile', 'url' => ['/site/myprofile'], 'visible' => Yii::$app->userdata->usertype == 2 || Yii::$app->userdata->usertype == 3],
                        ['label' => '<span><i class="fa fa-credit-card" aria-hidden="true"></i></span>My wallet', 'url' => ['/site/wallet'], 'visible' => Yii::$app->userdata->usertype == 2],
                        ['label' => '<span><i class="fa fa-file-text-o" aria-hidden="true"></i></span>My Agreements', 'url' => ['/site/myagreements'], 'visible' => Yii::$app->userdata->usertype == 3],
                        ['label' => '<span><i class="fa fa-building-o" aria-hidden="true"></i></span>My Shortlisted Properties', 'url' => ['/site/myfavlist'], 'visible' => Yii::$app->userdata->usertype == 2],
                        //~ ['label' => '<span><img src="'. Url::home(true).'images/my_profile.png" alt=""></span>My Visits', 'url' => ['/site/confirmvisits'],'visible' => Yii::$app->userdata->usertype == 2 || Yii::$app->userdata->usertype == 3],
                        ['label' => '<span><i class="fa fa-credit-card" aria-hidden="true"></i></span>Payments', 'url' => ['/site/mypaymentsdue'], 'visible' => Yii::$app->userdata->usertype == 3],
                        ['label' => '<span><i class="fa fa-wrench" aria-hidden="true"></i></span>Service Request', 'url' => ['/site/myrequests'], 'visible' => Yii::$app->userdata->usertype == 3],
                        //~ ['label' => '<span><img src="'. Url::home(true).'images/my_profile.png" alt=""></span>Change Password', 'url' => ['/users/changepassword']],
                        ['label' => '<span><i class="fa fa-search" aria-hidden="true"></i></span>Search Property', 'url' => ['/'], 'visible' => Yii::$app->userdata->usertype == 2],
                    ],
                ]);
                ?>
            </div>
            <!-- /.sidebar-collapse -->
        </div>
    </aside>


<?= $content ?>

    <footer>
        <div class="container">
            <div class="menulist">
                <ul class="footer-links">
                    <!--<li><a href="javascritp:;">FAQ's</a></li>
                      <li><a href="javascritp:;">Privacy Policy</a></li>
                    </ul>
                        </div>
                <div class="col-md-3 menulist">
                        <ul>
                    <li><a href="javascritp:;">Site Usage Terms</a></li>
                      <li><a href="javascritp:;">About Us</a></li>
                    </ul>
                </div>
                <div class="col-md-3 menulist">
                        <ul>
                    <li><a href="javascritp:;">Contact Us</a></li>
                      <li><a href="javascritp:;">Social Media Links</a></li>
                    </ul>
                </div>
                <div class="col-md-3 menulist">
                        <ul>
                    <li><a href="javascritp:;">Terms & Conditions</a></li>-->
                    <?php
                    echo Yii::$app->userdata->getFooter();
                    ?>
                </ul>
            </div>
        </div>

    </footer>

    <div class="copyright">&copy; 2018 Easyleases Technologies Private Limited</div>
<?php $this->endBody() ?>
        <!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>-->
    <script src="<?php echo Url::home(true); ?>js/app/bootstrap.min.js"></script>
    <script src="<?php echo Url::home(true); ?>js/app/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="<?php echo Url::home(true); ?>js/applicant.js"></script>
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
                maxDate: "+1w",
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
    <style type="text/css">
        .autoheight{
            height: 100%;
        }
        ul.time_suggestion {
            height: 119px;
            position: absolute;
            background-color: white;
            overflow-y: scroll;
            width: 100%;
            margin-top: -14px;
            /* display: inline-flex; */
            padding-left: 5px;
            z-index: 9999;
        }
        li.apply_time {
            display: inline-flex;
            width: 100%;
            padding-left: 42%;
            cursor: pointer;
        }

        li.apply_time:hover {
            background-color: grey;
        }

        span.close_time_picker {
            position: absolute;
            z-index: 10000;
            position: absolute;
            right: 9px;
            background: red;
            padding-left: 5px;
            border-radius: 16px;
            width: 20px;
            height: 20px;
            color: white;
            cursor: pointer;
            margin-top: -25px;
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