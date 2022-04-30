<?php

use \yii\web\Request;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);

use yii\helpers\Url;
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
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <!-- Bootstrap Core CSS -->
        <link href="<?php echo Url::home(true); ?>css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo Url::home(true); ?>css/jquery.datetimepicker.css" rel="stylesheet">
        <link href="<?php echo Yii::$app->userdata->autoVersion(Url::home(true) . 'css/all.css'); ?>" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans" />
        <script type="text/javascript" src="<?php echo Url::home(true); ?>js/js/pdf.worker.js"></script>
        <script type="text/javascript" src="<?php echo Url::home(true); ?>js/js/pdf.js"></script>
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
<?php $this->head() ?>
        <link href="<?php echo Yii::$app->userdata->autoVersion(Url::home(true) . 'css/sales.css'); ?>" rel="stylesheet">
        <link href="<?php echo Url::home(true); ?>css/MonthPicker.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="http://fontawesome.io/assets/font-awesome/css/font-awesome.css">
        <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>

        <script src="../assets/3eeaab70/yii.js"></script>
        <script src="<?php echo Url::home(true); ?>js/validate.js"></script>
        <style>
            .commonbtnDiv3 .btn {
                width: 30%;
                float: left;
                margin-left: 7px;
            }
            .btn33 {
                width: 30%;
                float: left;
            }
            .wrapper {
                display: block;
            }

            #sidebar {
                min-width: 250px;
                max-width: 250px;
                height: 100vh;
                position: fixed;
                left: 0;
                z-index: 999;
                font-size: 18px;
            }

            .sidebar-header {
                margin-bottom: 29px;
            }

            .overlay {
                display: none;
                position: fixed;
                /* full screen */
                width: 100vw;
                height: 100vh;
                /* transparent black */
                background: rgba(0, 0, 0, 0.7);
                /* middle layer, i.e. appears below the sidebar */
                z-index: 998;
                opacity: 0;
                /* animate the transition */
                transition: all 0.5s ease-in-out;
            }
            /* display .overlay when it has the .active class */
            .overlay.active {
                display: block;
                opacity: 1;
            }

            #dismiss {
                width: 35px;
                height: 35px;
                position: absolute;
                /* top right corner of the sidebar */
                top: 10px;
                right: 10px;
            }
        </style>

    </head>

    <body>
        <div class="loaderMain">
            <img src="<?php echo Url::home(true); ?>images/loader/25.gif">
        </div>
<?php $this->beginBody() ?>

        <nav id="mainNav" class="navbar navbar-default">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"> <span class="sr-only">Toggle navigation</span> Menu <i class="fa fa-bars"></i> </button>
                    <a class="navbar-brand page-scroll" href="<?php echo Url::home(true); ?>"><img src="<?php echo Url::home(true); ?>images/property_logo.png" alt=""></a>

                </div>
                <?php
                echo '<a class="pull-right">'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                        '<div style= "color: #26344B;">Logout</div>', ['class' => 'btn-link logout', 'style' => 'color:#000;']
                )
                . Html::endForm()
                . '</a>'
                ?>
                <!-- /.navbar-collapse -->
                <!--<span id="remaining_day_pass" style="float: right; margin-top: 1%;"></span>-->        
            </div>
            <!-- /.container-fluid -->
        </nav>
        <!--leftside-->
<?php if (Yii::$app->userdata->usertype != 8) { ?>
            <aside id="left-panel">
                <div class="navbar-default sidebar" role="navigation">
                    <div class="sidebar-nav navbar-collapse">
                        <div class="wrapper">
                            <!-- Sidebar -->
                            <nav id="sidebar">
                                <div id="dismiss">
                                    <i class="fas fa-arrow-left"></i>
                                </div>
                                <div class="sidebar-header">
                                    <h3>&nbsp;</h3>
                                </div>
                                <ul class="list-unstyled components">
    <?php if (Yii::$app->userdata->usertype == 6 || Yii::$app->userdata->usertype == 7) { ?>
                                        <li class="<?= (Yii::$app->request->pathInfo == 'external') ? 'active' : ''; ?>">
                                            <a href="<?= Url::home(true) ?>external">Dashboard</a>
                                        </li>
                                    <?php } ?>

    <?php if (Yii::$app->userdata->usertype == 6 || Yii::$app->userdata->usertype == 7) { ?>
                                        <li class="<?= (Yii::$app->request->pathInfo == 'external/myprofile') ? 'active' : ''; ?>">
                                            <a href="<?= Url::home(true) ?>external/myprofile">Profile</a>
                                        </li>
                                    <?php } ?>

                                    <?php if (Yii::$app->userdata->usertype == 6 || Yii::$app->userdata->usertype == 7) { ?>
                                        <?php
                                        $ownSubLink = [];
                                        $ownSubLink[] = 'external/owners';
                                        $ownSubLink[] = 'external/owner';
                                        ?>
                                        <li>
                                            <a href="#homeSubmenu1" data-toggle="collapse" aria-expanded="false">Property Owner</a>
                                            <ul class="<?= (in_array(Yii::$app->request->pathInfo, $ownSubLink)) ? 'collapse in' : 'collapse'; ?> list-unstyled" id="homeSubmenu1">
                                                <li class="<?= (Yii::$app->request->pathInfo == 'external/owners') ? 'active' : ''; ?>">
                                                    <a href="<?= Url::home(true) ?>external/owners">Leads</a>
                                                </li>
        <?php if (Yii::$app->userdata->usertype == 7) { ?>
                                                    <li class="<?= (Yii::$app->request->pathInfo == 'external/owner') ? 'active' : ''; ?>">
                                                        <a href="<?= Url::home(true) ?>external/owner">Management</a>
                                                    </li>
        <?php } ?>
                                            </ul>
                                        </li>
                                    <?php } ?>

                                    <?php if (Yii::$app->userdata->usertype == 6 || Yii::$app->userdata->usertype == 7) { ?>
                                        <?php
                                        $propSubLink = [];
                                        $propSubLink[] = 'external/propertyleads';
                                        $propSubLink[] = 'external/properties';
                                        $propSubLink[] = 'external/propertieslisting';
                                        $propSubLink[] = 'external/applicantsinterested';
                                        ?>
                                        <li>
                                            <a href="#homeSubmenu2" data-toggle="collapse" aria-expanded="false">Property</a>
                                            <ul class="<?= (in_array(Yii::$app->request->pathInfo, $propSubLink)) ? 'collapse in' : 'collapse'; ?> list-unstyled" id="homeSubmenu2">
                                                <li class="<?= (Yii::$app->request->pathInfo == 'external/propertyleads') ? 'active' : ''; ?>">
                                                    <a href="<?= Url::home(true) ?>external/propertyleads">Leads</a>
                                                </li>
        <?php if (Yii::$app->userdata->usertype == 7) { ?>
                                                    <li class="<?= (Yii::$app->request->pathInfo == 'external/properties') ? 'active' : ''; ?>">
                                                        <a href="<?= Url::home(true) ?>external/properties">Management</a>
                                                    </li>
        <?php } ?>
                                                <li class="<?= (Yii::$app->request->pathInfo == 'external/propertieslisting') ? 'active' : ''; ?>">
                                                    <a href="<?= Url::home(true) ?>external/propertieslisting">Listing</a>
                                                </li>
        <?php if (Yii::$app->userdata->usertype == 6 || Yii::$app->userdata->usertype == 7) { ?>
                                                    <li class="<?= (Yii::$app->request->pathInfo == 'external/applicantsinterested') ? 'active' : ''; ?>">
                                                        <a href="<?= Url::home(true) ?>external/applicantsinterested">Applicant Interested</a>
                                                    </li>
        <?php } ?>
                                            </ul>
                                        </li>
                                    <?php } ?>

                                    <?php if (Yii::$app->userdata->usertype == 6 || Yii::$app->userdata->usertype == 7) { ?>
                                        <?php
                                        $tenSubLink = [];
                                        $tenSubLink[] = 'external/applicants';
                                        $tenSubLink[] = 'external/tenants';
                                        $tenSubLink[] = 'external/pasttenants';
                                        $tenSubLink[] = 'external/paymentreceipt';
                                        $tenSubLink[] = 'external/tenantpayrecord';
                                        ?>
                                        <li>
                                            <a href="#homeSubmenu3" data-toggle="collapse" aria-expanded="false">Tenant</a>
                                            <ul class="<?= (in_array(Yii::$app->request->pathInfo, $tenSubLink)) ? 'collapse in' : 'collapse'; ?> list-unstyled" id="homeSubmenu3">
                                                <li class="<?= (Yii::$app->request->pathInfo == 'external/applicants') ? 'active' : ''; ?>">
                                                    <a href="<?= Url::home(true) ?>external/applicants">Applicant Leads</a>
                                                </li>
        <?php if (Yii::$app->userdata->usertype == 7) { ?>
                                                    <li class="<?= (Yii::$app->request->pathInfo == 'external/tenants') ? 'active' : ''; ?>">
                                                        <a href="<?= Url::home(true) ?>external/tenants">Active Tenants</a>
                                                    </li>
                                                <?php } ?>
        <?php if (Yii::$app->userdata->usertype == 7) { ?>
                                                    <li class="<?= (Yii::$app->request->pathInfo == 'external/pasttenants') ? 'active' : ''; ?>">
                                                        <a href="<?= Url::home(true) ?>external/pasttenants">Past Tenants</a>
                                                    </li>
                                                <?php } ?>
        <?php if (Yii::$app->userdata->usertype == 7) { ?>
                                                    <li class="<?= (Yii::$app->request->pathInfo == 'external/paymentreceipt') ? 'active' : ''; ?>">
                                                        <a href="<?= Url::home(true) ?>external/paymentreceipt">Payment Receipt</a>
                                                    </li>
                                                <?php } ?>
        <?php if (Yii::$app->userdata->usertype == 6 || Yii::$app->userdata->usertype == 7) { ?>
                                                    <li class="<?= (Yii::$app->request->pathInfo == 'external/tenantpayrecord') ? 'active' : ''; ?>">
                                                        <a href="<?= Url::home(true) ?>external/tenantpayrecord">Payment History</a>
                                                    </li>
        <?php } ?>
                                            </ul>
                                        </li>
                                    <?php } ?>

                                    <?php if (Yii::$app->userdata->usertype == 6 || Yii::$app->userdata->usertype == 7) { ?>
                                        <?php
                                        $advSubLink = [];
                                        $advSubLink[] = 'external/advisors';
                                        $advSubLink[] = 'external/adviser';
                                        ?>
                                        <li>
                                            <a href="#homeSubmenu4" data-toggle="collapse" aria-expanded="false">Advisor</a>
                                            <ul class="<?= (in_array(Yii::$app->request->pathInfo, $advSubLink)) ? 'collapse in' : 'collapse'; ?> list-unstyled" id="homeSubmenu4">
                                                <li class="<?= (Yii::$app->request->pathInfo == 'external/advisors') ? 'active' : ''; ?>">
                                                    <a href="<?= Url::home(true) ?>external/advisors">Leads</a>
                                                </li>
        <?php if (Yii::$app->userdata->usertype == 7) { ?>
                                                    <li class="<?= (Yii::$app->request->pathInfo == 'external/adviser') ? 'active' : ''; ?>">
                                                        <a href="<?= Url::home(true) ?>external/adviser">Management</a>
                                                    </li>
        <?php } ?>
                                            </ul>
                                        </li>
                                    <?php } ?>

                                    <?php if (Yii::$app->userdata->usertype == 7) { ?>
                                        <?php
                                        $propFinLink = [];
                                        $propFinLink[] = 'external/income';
                                        $propFinLink[] = 'external/expense';
                                        $propFinLink[] = 'external/pronloss';
                                        ?>
                                        <li>
                                            <a href="#homeSubmenu5" data-toggle="collapse" aria-expanded="false">Property Financials</a>
                                            <ul class="<?= (in_array(Yii::$app->request->pathInfo, $propFinLink)) ? 'collapse in' : 'collapse'; ?> list-unstyled" id="homeSubmenu5">
                                                <li class="<?= (Yii::$app->request->pathInfo == 'external/income') ? 'active' : ''; ?>">
                                                    <a href="<?= Url::home(true) ?>external/income">Income</a>
                                                </li>
                                                <li class="<?= (Yii::$app->request->pathInfo == 'external/expense') ? 'active' : ''; ?>">
                                                    <a href="<?= Url::home(true) ?>external/expense">Expense</a>
                                                </li>
                                                <li class="<?= (Yii::$app->request->pathInfo == 'external/pronloss') ? 'active' : ''; ?>">
                                                    <a href="<?= Url::home(true) ?>external/pronloss">Profit & Loss</a>
                                                </li>
                                            </ul>
                                        </li>
                                    <?php } ?>

                                    <?php if (Yii::$app->userdata->usertype == 6 || Yii::$app->userdata->usertype == 7) { ?>
                                        <?php
                                        $myAccLink = [];
                                        $myAccLink[] = 'external/myaccount';
                                        $myAccLink[] = 'external/servicerequests';
                                        ?>
                                        <li>
                                            <a href="#homeSubmenu6" data-toggle="collapse" aria-expanded="false">My Account</a>
                                            <ul class="<?= (in_array(Yii::$app->request->pathInfo, $myAccLink)) ? 'collapse in' : 'collapse'; ?> list-unstyled" id="homeSubmenu6">
                                                <li class="<?= (Yii::$app->request->pathInfo == 'external/myaccount') ? 'active' : ''; ?>">
                                                    <a href="<?= Url::home(true) ?>external/myaccount">Clients</a>
                                                </li>
        <?php if (Yii::$app->userdata->usertype == 7) { ?>
                                                    <li class="<?= (Yii::$app->request->pathInfo == 'external/servicerequests') ? 'active' : ''; ?>">
                                                        <a href="<?= Url::home(true) ?>external/servicerequests">Service Request</a>
                                                    </li>
        <?php } ?>
                                            </ul>
                                        </li>
                                    <?php } ?>

    <?php if (Yii::$app->userdata->usertype == 6 || Yii::$app->userdata->usertype == 7) { ?>
                                        <li class="<?= (Yii::$app->request->pathInfo == 'external/pgitxnlist') ? 'active' : ''; ?>">
                                            <a href="<?= Url::home(true) ?>external/pgitxnlist">Payment Gateway Transactions</a>
                                        </li>
                                    <?php } ?>

    <?php if (Yii::$app->userdata->usertype == 7) { ?>
                                        <li class="<?= (Yii::$app->request->pathInfo == 'external/pgonboard') ? 'active' : ''; ?>">
                                            <a href="<?= Url::home(true) ?>external/pgonboard">PG Onboarding</a>
                                        </li>
    <?php } ?>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </aside>
        <?php } ?>

        <?= $content ?>

<?php $this->endBody() ?>

        <script src="<?php echo Url::home(true); ?>js/app/bootstrap.min.js"></script>
        <script src="<?php echo Url::home(true); ?>js/admin.js"></script>
        <script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>

        <script src="<?php echo Url::home(true); ?>js/sales.js"></script>
        <script type="text/javascript" src="../js/moment.js"></script>
        <script type="text/javascript" src="../js/timepicker.js"></script>
        <link rel="stylesheet" href="../css/timepicker.css" />

        <script>
            function formatMoneyIndian(x) {
                x = x.toString();
                var afterPoint = '';
                if (x.indexOf('.') > 0)
                    afterPoint = x.substring(x.indexOf('.'), x.length);
                x = Math.floor(x);
                x = x.toString();
                var lastThree = x.substring(x.length - 3);
                var otherNumbers = x.substring(0, x.length - 3);
                if (otherNumbers != '')
                    lastThree = ',' + lastThree;
                var res = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree + afterPoint;
                return res;
            }

            $(document).ready(function () {
                $('#example-table').dataTable();
                $('#datetimepicker1').datetimepicker({
                    datepicker: true,
                    minDate: new Date(),
                    format: 'd-m-Y H:i:s',
                    step: 30,
                    minDateTime: new Date()
                });
            });

            $(document.body).on('hidden.bs.modal', function () {
                $('#myModal').removeData('bs.modal');
            });

            $(document).ready(function () {
                $(".btn-select").each(function (e) {
                    var value = $(this).find("ul li.selected").html();
                    if (value != undefined) {
                        $(this).find(".btn-select-input").val(value);
                        $(this).find(".btn-select-value").html(value);
                    }
                });
            });

            $(document).on('click', '.btn-select', function (e) {
                e.preventDefault();
                var ul = $(this).find("ul");
                if ($(this).hasClass("active")) {
                    if (ul.find("li").is(e.target)) {
                        var target = $(e.target);
                        target.addClass("selected").siblings().removeClass("selected");
                        var value = target.html();
                        $(this).find(".btn-select-input").val(value);
                        $(this).find(".btn-select-value").html(value);
                    }
                    ul.hide();
                    $(this).removeClass("active");
                } else {
                    $('.btn-select').not(this).each(function () {
                        $(this).removeClass("active").find("ul").hide();
                    });
                    ul.slideDown(300);
                    $(this).addClass("active");
                }
            });

            $(document).on('click', function (e) {
                var target = $(e.target).closest(".btn-select");
                if (!target.length) {
                    $(".btn-select").removeClass("active").find("ul").hide();
                }
            });

            $(document).ready(function () {
                $(".clickable-row").click(function () {
                    window.location = $(this).data("href");
                });

                $(".clickable-owner").click(function () {
                    window.location = $(this).data("href");
                });


                var csrfToken = $('meta[name="csrf-token"]').attr("content");

                $(document).on("click", "#applicantprofile-state", function (e) {

                    e.preventDefault();
                    $.ajax({
                        url: $(this).attr('url'),
                        type: 'POST',
                        data: {'val': $(this).val(), _csrf: csrfToken},
                        success: function (data) {
                            $('#applicantprofile-city').html(data);

                        },
                        error: function (data) {
                            alert('Something went wrong, please try after some time!');
                        }
                    });
                });

                $(document).on("click", "#applicantprofile-city", function (e) {
                    e.preventDefault();
                    $.ajax({
                        url: $(this).attr('url'),
                        type: 'POST',
                        data: {'val': $(this).val(), _csrf: csrfToken},
                        success: function (data) {
                            $('#applicantprofile-region').html(data);
                        }
                    });
                });

                $(document).on("change", "#advisorprofile-city", function (e) {
                    e.preventDefault();
                    $.ajax({
                        url: $(this).attr('url'),
                        type: 'POST',
                        data: {'val': $(this).val(), _csrf: csrfToken},
                        success: function (data) {
                            $('#advisorprofile-region').html(data);
                        },
                        error: function (data) {
                            //    console.log(data.responseText);
                            alert(data.responseText);
                        }
                    });
                });

                $(document).on("change", "#advisorprofile-state", function (e) {
                    e.preventDefault();
                    $.ajax({
                        url: $(this).attr('url'),
                        type: 'POST',
                        data: {'val': $(this).val(), _csrf: csrfToken},
                        success: function (data) {
                            $('#advisorprofile-city').html(data);
                        },
                        error: function (data) {
                            //    console.log(data.responseText);
                            alert(data.responseText);
                        }
                    });
                });

                $(document).on("click", "#tenantprofile-city", function (e) {
                    e.preventDefault();
                    $.ajax({
                        url: $(this).attr('url'),
                        type: 'POST',
                        data: {'val': $(this).val(), _csrf: csrfToken},
                        success: function (data) {
                            $('#tenantprofile-region').html(data);
                        },
                        error: function (data) {
                            //    console.log(data.responseText);
                            alert(data.responseText);
                        }
                    });
                });

                $(document).on("click", "#tenantprofile-state", function (e) {
                    e.preventDefault();
                    $.ajax({
                        url: $(this).attr('url'),
                        type: 'POST',
                        data: {'val': $(this).val(), _csrf: csrfToken},
                        success: function (data) {
                            $('#tenantprofile-city').html(data);
                        },
                        error: function (data) {
                            //    console.log(data.responseText);
                            alert(data.responseText);
                        }
                    });
                });

                $(document).on("click", "#ownerprofile-state", function (e) {
                    e.preventDefault();
                    $.ajax({
                        url: $(this).attr('url'),
                        type: 'POST',
                        data: {'val': $(this).val(), _csrf: csrfToken},
                        success: function (data) {
                            $('#ownerprofile-city').html(data);
                        },
                        error: function (data) {
                            //    console.log(data.responseText);
                            alert(data.responseText);
                        }
                    });
                });

                $(document).on("click", "#ownerprofile-city", function (e) {
                    e.preventDefault();
                    $.ajax({
                        url: $(this).attr('url'),
                        type: 'POST',
                        data: {'val': $(this).val(), _csrf: csrfToken},
                        success: function (data) {
                            $('#ownerprofile-region').html(data);
                        },
                        error: function (data) {
                            //    console.log(data.responseText);
                            alert(data.responseText);
                        }
                    });
                });

                $(document).on("click", "#properties-state", function (e) {
                    e.preventDefault();
                    $.ajax({
                        url: $(this).attr('url'),
                        type: 'POST',
                        data: {'val': $(this).val(), _csrf: csrfToken},
                        success: function (data) {
                            $('#properties-city').html(data);
                        },
                        error: function (data) {
                            //    console.log(data.responseText);
                            alert(data.responseText);
                        }
                    });
                });

                $(document).on("click", "#properties-city", function (e) {
                    e.preventDefault();
                    $.ajax({
                        url: $(this).attr('url'),
                        type: 'POST',
                        data: {'val': $(this).val(), _csrf: csrfToken},
                        success: function (data) {
                            $('#properties-branch_code').html(data);
                        },
                        error: function (data) {
                            //    console.log(data.responseText);
                            alert(data.responseText);
                        }
                    });
                });
            });
        </script>
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script src="<?php echo Url::home(true); ?>js/MonthPicker.min.js"></script>
        <script>
            $(function () {
                $(".datepicker").datepicker({
                    minDate: new Date(),
                    changeMonth: true,
                    changeYear: true,
                    numberOfMonths: 1,
                    dateFormat: 'dd-M-yy',
                });

                $(".datepickerComplete").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    numberOfMonths: 1,
                    dateFormat: 'dd-M-yy',
                });

                $(".datepickerEndComplete").datepicker({
                    minDate: new Date($('input[datacomp="datepickerEndComplete"]').val()),

                    changeMonth: true,
                    changeYear: true,
                    numberOfMonths: 1,
                    dateFormat: 'dd-M-yy',
                });

                $("#leadstenant-follow_up_date_time").datetimepicker({
                    useCurrent: false,
                    format: 'DD-MMM-YYYY HH:mm',
                    sideBySide: true,
                    minDate: new Date(),

                });

                $("#leadsadvisor-schedule_date_time").datetimepicker({
                    useCurrent: false,
                    format: 'DD-MMM-YYYY HH:mm',
                    sideBySide: true,
                    minDate: new Date(),

                });
            });
        </script>
        <script>
            var second_submit = 1;
            $(document).ready(function () {
                $('[data-toggle="popover"]').popover({

                    placement: 'right',

                    trigger: 'hover'

                });
            });

            $(document).on('click', '.activate_property', function (e) {
                //$('#page-wrapper').css();
                e.preventDefault();
                if ($('.activate_property').attr('data-status') == 0) {
                    saveContract(0);
                    activatePropertyFunction($('.activate_property'));
                } else {
                    saveContract(2);
                }
            });

            function activatePropertyFunction(thiselement) {
                startLoader();
                $.ajax({
                    url: 'activateproperty',
                    type: 'POST',
                    data: {'val': $('#propertyagreements-property_id').val(), 'status': thiselement.attr('data-status'), '_csrf': csrfToken},
                    dataType: 'json',
                    timeout: 10000,
                    success: function (data) {
                        if (data.success == 1) {

                            if (thiselement.attr('data-status') == 0) {

                                thiselement.attr('data-status', '1');
                                thiselement.text('Activate Property');
                                thiselement.css('font-size', '22px');
                                thiselement.css('padding', '11px');
                                $('.isDisabledd').each(function () {
                                    $(this).removeAttr('disabled')
                                });
                                hideLoader();
                                alert('Property Deactivated Succesfully');

                            } else {
                                thiselement.attr('data-status', '0');
                                thiselement.text('Deactivate Property');
                                thiselement.css('font-size', '20px');
                                thiselement.css('padding', '12.5px');

                                $('.toBeDisabled').each(function () {
                                    $(this).attr('disabled', true)
                                })
                                $('.toBeDisabled').removeClass('isDisabledd');
                                $('.toBeDisabled').addClass('isDisabledd');
                                hideLoader();
                                alert('Property Activated Successfully');
                            }
                        } else {
                            hideLoader();
                            if (thiselement.attr('data-status') == 0) {
                                alert('Property Can\'t be Deactivated');
                            } else {
                                alert(data.msg);
                            }
                        }
                    },
                    error: function (x, textStatus, m) {
                        // console.log(textStatus);
                        hideLoader();
                        if (textStatus == "timeout") {
                            activatePropertyFunction($('.activate_property'));
                        }
                    }
                });
            }

            /*************************************************** Add property  ******************************************/
            $(document).on('click', 'li a.data_submit', function () {
                var length = $('#form_property_listing').length;
                if (length == 1) {
                    if (second_submit == 1) {
                        formPropertyListing(0);
                    }

                } else {
                    editFormPropertyListing(0);
                }
                saveFeatures($('#save_features'), 0);
                setDefault(0);
                saveContract(0);
                second_submit = 1;
            })




            $(document).on('click', 'a[data-click="#p_contract"]', function () {

                setDefault(1);

            });


            function setDefault(idd) {
                var length = $('#dataimages tbody tr').length;
                var property = $('#propertyagreements-property_id').val();
                var csrfToken = $('meta[name="csrf-token"]').attr("content");
                if (length > 0) {
                    var check = $('input[type="radio"][name="optradio"]:checked').length;
                    if (check == 0) {
                        var id = $('input[type="radio"][name="optradio"]:first-child').val();
                    } else {
                        var id = $('input[type="radio"][name="optradio"]:checked').val();
                    }
                    startLoader();
                    $.ajax({
                        url: 'setasdefault?id=' + id,
                        type: 'POST',
                        data: 'property_id=' + property + '&_csrf=' + csrfToken,
                        success: function (data) {
                            if (idd) {
                                $('a[data-next="p_contract"]').attr('href', '#p_contract');
                                $('a[data-next="p_contract"]').click();
                            }
                            hideLoader();
                        },
                        error: function () {
                            hideLoader();
                            //alert('Error Occured');
                        }
                    })
                } else {
                    if (idd) {
                        $('a[href="#p_contract"]').click();
                    }

                }
            }


            function saveContract(idd) {
                startLoader();
                var action = $('#property_agreement').attr('data-href');
                var fd = new FormData($('#property_agreement')[0]);
                $.ajax({
                    url: action + '?id=' + $('#propertyagreements-property_id').val(),
                    type: "POST",
                    data: fd,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function (data) {
                        console.log(data);
                        if (data.success == '1') {
                            if (idd == 2) {
                                activatePropertyFunction($('.activate_property'));
                            } else if (idd) {
                                //openPropLeadAssignTab();
                                alert('Agreement saved successfully');
                                hideLoader();
                                return 1;
                            } else {
                                return 1;
                            }
                            $('.has-error').removeClass('has-error');
                            $('.help-block').text('');
                        } else {
                            $.each(data, function (key, value) {
                                $('input').removeClass('has-error');
                                $("[name$='[" + key + "]']").closest('false').addClass('has-error');
                                $("[name$='[" + key + "]']").siblings('.help-block').text(value);
                            })
                        }
                        hideLoader();
                    },
                    error: function (data) {

                        hideLoader();
                        // alert(data.responseText);
                        alert('Something went wrong, please try after some time!')

                    }
                });
            }

            $(document).ready(function () {
                $('#saveContract').click(function (e) {
                    e.preventDefault();
                    saveContract(1);
                })
            });



            function saveFeatures(thiss, idd) {
                startLoader();
                var str = '';
                var csrfToken = $('meta[name="csrf-token"]').attr("content");
                $('#p_features .check_click:checked').each(function () {
                    str = str + ',' + $('#' + $(this).attr('data-id')).attr('data-name') + "-" + $('#' + $(this).attr('data-id')).val();
                })

                $.ajax({
                    url: 'saveattributes',
                    type: 'POST',
                    data: {property_id: $('#propertyagreements-property_id').val(), attributes: $("#attributes").val(), property_map: str, _csrf: csrfToken},
                    success: function (data) {
                        if (idd) {
                            $('a[data-next="p_image"]').attr('href', '#p_image');
                            click_enable = 0;
                            $('a[data-next="p_image"]').click();
                        }
                        hideLoader();

                    },
                    error: function (data) {
                        hideLoader();
                        //alert('Error Occured');
                    }
                });
            }

            $(document).on("click", "#save_features", function (e) {
                saveFeatures($(this), 1);
            });

            function formPropertyListing(idd) {
                startLoader();
                var csrfToken = $('meta[name="csrf-token"]').attr("content");
                var property = $('#propertyagreements-property_id').val();
                var serialize = $('#ownerform').serialize();
                if (property.trim() !== '') {
                    serialize += '&property_id=' + property;
                }
                $.ajax({
                    url: 'addpropertylisting/?id=<?= isset($_GET['id']) ? $_GET['id'] : "" ?>',
                    type: 'POST',
                    data: serialize,
                    success: function (data) {
                        if (data !== 'error') {
                            $('a[data-next="p_features"]').attr('href', '#p_features');
                            $('a[data-next="p_image"]').attr('href', '#p_image');
                            $('a[data-next="p_contract"]').attr('href', '#p_contract');
                            if (idd) {
                                $('a[data-next="p_features"]').click();
                            }
                            $('#propertyagreements-property_id').val(data);
                            $('#lead-assign-property-id').val(data);
                        }
                        hideLoader();
                    },
                    error: function () {
                        hideLoader();
                        //alert('Error Occured');
                    }
                })
            }

            $(document).on('click', '#form_property_listing', function (e) {
                e.preventDefault();
                second_submit = 0;
                formPropertyListing(1);
                // $('#p_info_submit').click();
            });


            function editFormPropertyListing(idd) {
                startLoader();
                var csrfToken = $('meta[name="csrf-token"]').attr("content");
                var property = $('#propertyagreements-property_id').val();
                var serialize = $('#ownerform').serialize();
                if (property.trim() !== '') {
                    serialize += '&property_id=' + property;
                }
                var action = $('#ownerform').attr('action');
                $.ajax({
                    url: 'editpropertyajax?id=' + property,
                    type: 'POST',
                    data: serialize,
                    success: function (data) {
                        if (data == 'success') {
                            click_enable = 0;
                            if (idd) {
                                $('a[href="#p_features"]').click();
                            }

                        }
                        hideLoader();
                    },
                    error: function () {
                        hideLoader();
                        alert('Something went wrong, please try after some time!');
                    }
                    // },
                    // error: function(){
                    //  alert('Error Occured');
                    // }
                })
            }

            $(document).on('click', '#edit_form_property_listing', function (e) {
                e.preventDefault();

                editFormPropertyListing(1);

            });



            function savePropertyFeatures(thiss) {
                startLoader();
                var csrfToken = $('meta[name="csrf-token"]').attr("content");
                var property = $('#propertyagreements-property_id').val();
                var str = ''
                $('#p_features .check_click:checked').each(function () {
                    if ($('#' + thiss.attr('data-id')).length != 0) {
                        str = str + ',' + $('#' + thiss.attr('data-id')).attr('data-name') + "-" + $('#' + thiss.attr('data-id')).val();
                    }
                })
                var attributes = $('#attributes').val();
                $.ajax({
                    url: 'saveattributes',
                    type: 'POST',
                    data: {'property_id': property, 'attributes': attributes, property_map: str, _csrf: csrfToken},
                    success: function (data) {
                        $('a[data-next="p_image"]').attr('href', '#p_image');
                        click_enable = 0;
                        $('a[data-next="p_image"]').click();
                        hideLoader();
                    },
                    error: function (data) {
                        hideLoader();
                        //alert('Some Error Occured!');
                    }
                });
            }

            $(document).on('click', '.save_property_features', function () {
                savePropertyFeatures($(this));
            });




            /**************************************************** Save Property ******************************************/


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


                $(document).on('click', 'ul.sort_ul li', function () {
                    var value = $(this).attr('value');
                    $('#sort_val').val(value);
                    $('.navbar-search').submit();
                })
                $(document).ready(function () {
                    if ($('#sort_val').val() != '') {
                        $('ul.sort_ul li').removeClass('selected');
                        $('ul.sort_ul li[value="' + $('#sort_val').val() + '"]').addClass('selected');
                        $('.btn-select-value').text($('ul.sort_ul li[value="' + $('#sort_val').val() + '"]').text());
                    }

                })
            });
            $(".datetimepickerss").datetimepicker({
                useCurrent: false,
                format: 'DD-MMM-YYYY HH:mm',
                sideBySide: true,
                minDate: new Date(),
            })
            // $(document).ready(function(){
            // var cook_val=getCookie('add_property_click');
            // if(cook_val!=''){
            // alert('perform');
            // }
            // else{
            // alert('dont');
            // }
            // })

        </script>
    </body>
</html>
<?php $this->endPage() ?>
<script>

    var radioSelected = [];
    var toBeDeleted = [];
    var toBeApproved = [];
    var savedElements = '';
    var propertyName = '';
    var enableUnload = true;
    var totalIncome = 0.0;
    var totalExpense = 0.0;

    function disableSaveBtn() {
        $('#save-csv').attr('disabled', 'disabled');
    }

    function disableIncomeSaveBtn() {
        $('#save-csv-income').attr('disabled', 'disabled');
    }

    function disableUndoBtn() {
        $('#discard-changes').attr('disabled', 'disabled');
    }

    function disableIncomeUndoBtn() {
        $('#discard-changes').attr('disabled', 'disabled');
    }

    function enableSaveBtn() {
        $('#save-csv').removeAttr('disabled');
    }

    function enableIncomeSaveBtn() {
        $('#save-csv-income').removeAttr('disabled');
    }

    function enableUndoBtn() {
        $('#discard-changes').removeAttr('disabled');
    }

    function enableIncomeUndoBtn() {
        $('#discard-changes').removeAttr('disabled');
    }

    function openPropLeadAssignTab() {
        hideLoader();
        $('#pro-contract-tab-li').removeClass('active');
        $('#p_contract').removeClass('active');
        $('#p_contract').removeClass('in');

        $('#pro-lead-assig-tab-li').addClass('active');
        $('#lead-assignment').addClass('active');
        $('#lead-assignment').addClass('in');
    }

    function changeExpenseProperty() {
        $('#property-id-expense').val('');
        //$('#save-csv').hide();
        //$('#discard-changes').hide();
        $('#summarized-expense').hide();
        $('.csv-list-items').html('');
        radioSelected = [];
        toBeDeleted = [];
        savedElements = '';
    }

    function changeIncomeProperty() {
        $('#property-id-income').val('');
        //$('#save-csv-income').hide();
        //$('#discard-changes').hide();
        $('#summarized-income').hide();
        $('.csv-list-items').html('');
        radioSelected = [];
        toBeDeleted = [];
        savedElements = '';
    }

    function displayTotalIncome() {
        $('.total-income-amount').each(function (item, index) {
            if (!isNaN(parseFloat($(this).val()))) {
                totalIncome += parseFloat($(this).val());
            }
        });
        $('#summarized-income-value').html('&#8377; ' + formatMoneyIndian(totalIncome.toFixed(2)));
        totalIncome = 0.0;

<?php if (!empty($this->params['incomeTypes'])) { ?>
    <?php foreach ($this->params['incomeTypes'] as $type) { ?>
                totalGroupIncome<?= $type->income_code ?> = 0.0;
                $('.total-grouped-income-amount-<?= $type->income_code ?>').each(function (item, index) {
                    if (!isNaN(parseFloat($(this).val()))) {
                        totalGroupIncome<?= $type->income_code ?> += parseFloat($(this).val());
                    }
                });
                $('#summarized-income-value-<?= $type->income_code ?>').html('&#8377; ' + formatMoneyIndian(totalGroupIncome<?= $type->income_code ?>.toFixed(2)));
                if (totalGroupIncome<?= $type->income_code ?>.toFixed(2) == 0.0) {
                    $('#income-type-block-head-<?= $type->income_code ?>').hide();
                    $('#income-type-block-<?= $type->income_code ?>').hide();
                } else {
                    $('#income-type-block-head-<?= $type->income_code ?>').show();
                    $('#income-type-block-<?= $type->income_code ?>').show();
                }
                totalGroupIncome<?= $type->income_code ?> = 0.0;
    <?php } ?>
<?php } ?>

        $('#summarized-income').show();
    }

    function displayTotalExpense() {
        $('.total-expense-amount').each(function (item, index) {
            if (!isNaN(parseFloat($(this).val()))) {
                totalExpense += parseFloat($(this).val());
            }
        });
        $('#summarized-expense-value').html('&#8377; ' + formatMoneyIndian(totalExpense.toFixed(2)));
        totalExpense = 0.0;

<?php if (!empty($this->params['expenseTypes'])) { ?>
    <?php foreach ($this->params['expenseTypes'] as $type) { ?>
                totalGroupExpense<?= $type->expense_code ?> = 0.0;
                $('.total-grouped-expense-amount-<?= $type->expense_code ?>').each(function (item, index) {
                    if (!isNaN(parseFloat($(this).val()))) {
                        totalGroupExpense<?= $type->expense_code ?> += parseFloat($(this).val());
                    }
                });
                $('#summarized-expense-value-<?= $type->expense_code ?>').html('&#8377; ' + formatMoneyIndian(totalGroupExpense<?= $type->expense_code ?>.toFixed(2)));
                if (totalGroupExpense<?= $type->expense_code ?>.toFixed(2) == 0.0) {
                    $('#expense-type-block-<?= $type->expense_code ?>').hide();
                    $('#expense-type-block-head-<?= $type->expense_code ?>').hide();
                } else {
                    $('#expense-type-block-<?= $type->expense_code ?>').show();
                    $('#expense-type-block-head-<?= $type->expense_code ?>').show();
                }
                totalGroupExpense<?= $type->expense_code ?> = 0.0;
    <?php } ?>
<?php } ?>

        $('#summarized-expense').show();
    }

    function initDatePickerPlugin() {
        $(".income-query-date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-M-yy'
        });

        $('.income-query-month').MonthPicker({
            Button: false,
            MonthFormat: 'M-yy',
        });

//        $(".income-query-month").datepicker({
//            changeMonth: true,
//            changeYear: true,
//            showButtonPanel: true,
//            onClose: function(dateText, inst) {
//                var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
//                var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
//                $(this).val($.datepicker.formatDate('M-yy', new Date(year, month, 1)));
//            }
//        });
//        
//        $(".income-query-month").focus(function () {
//            $(".ui-datepicker-calendar").hide();
//            $(".ui-datepicker-current").hide();
//            $("#ui-datepicker-div").position({
//                my: "center top",
//                at: "center bottom",
//                of: $(this)
//            });
//        });
    }

    function initExpenseDatePickerPlugin() {
        $(".expense-query-date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-M-yy'
        });

        $('.expense-query-month').MonthPicker({
            Button: false,
            MonthFormat: 'M-yy',
        });

//        $(".expense-query-month").datepicker({
//            changeMonth: true,
//            changeYear: true,
//            showButtonPanel: true,
//            onClose: function(dateText, inst) {
//                var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
//                var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
//                $(this).val($.datepicker.formatDate('M-yy', new Date(year, month, 1)));
//            }
//        });
//        
//        $(".expense-query-month").focus(function () {
//            $(".ui-datepicker-calendar").hide();
//            $(".ui-datepicker-current").hide();
//            $("#ui-datepicker-div").position({
//                my: "center top",
//                at: "center bottom",
//                of: $(this)
//            });
//        });
    }

<?php if (!empty($_GET['cp'])) { ?>
        $(document).ready(function () {
            $(".close").css("display", "none");
            $(".savechanges_submit").css("display", "none");
            $(".savechanges_submit_contractaction").parent().css("width", "100%");
            $('#myModal').modal({backdrop: 'static', keyboard: false});
        });
<?php } ?>

    $("#search-property-expense").autocomplete({
        source: "getpropertysearch",
        select: function (event, ui) {
            event.preventDefault();
            if ($('#property-id-expense').val() != '') {
                if (confirm('Are you sure you want to change property?')) {
                    changeExpenseProperty();
                } else {
                    $("#search-property-expense").val(propertyName);
                    return false;
                }
            }

            propertyName = ui.item.label;
            $("#search-property-expense").val(ui.item.label);
            $("#property-id-expense").val(ui.item.value);
            $("#expense-query-property-id").val(ui.item.value);
            $('#upload-expense').show();
            $('#upload-expense-btn').show();
            $('#query-expense-btn').show();
            $('#add-expense-btn').show();
            $('#delete-expense-btn').show();
        }
    });

    $("#search-property-income").autocomplete({
        source: "getpropertysearch",
        select: function (event, ui) {
            event.preventDefault();
            if ($('#property-id-income').val() != '') {
                if (confirm('Are you sure you want to change property?')) {
                    changeIncomeProperty();
                } else {
                    $("#search-property-income").val(propertyName);
                    return false;
                }
            }

            propertyName = ui.item.label;
            $("#search-property-income").val(ui.item.label);
            $("#property-id-income").val(ui.item.value);
            $("#income-query-property-id").val(ui.item.value);
            $('#upload-income').show();
            $('#upload-income-btn').show();
            $('#query-income-btn').show();
            $('#add-income-btn').show();
            $('#delete-income-btn').show();
        }
    });

    $("#tenant-list-inner").autocomplete({
        source: "gettenantsearch",
        select: function (event, ui) {
            event.preventDefault();
            $("#tenant-list-inner").val(ui.item.label);
            $("#tenant-id-inner").val(ui.item.value);
            $('#record_type').show();
        }
    });

    $("#property-list-inner").autocomplete({
        source: "getfavpropertysearch",
        select: function (event, ui) {
            event.preventDefault();
            $("#property-list-inner").val(ui.item.label);
            $("#property-id-inner").val(ui.item.value);
        }
    });

    $("#query-expense-vendor").autocomplete({
        source: "getexpensevendor",
        select: function (event, ui) {
            event.preventDefault();
            $("#query-expense-vendor").val(ui.item.label);
        }
    });

    $("#query-expense-paid-by").autocomplete({
        source: "getexpensepaidby",
        select: function (event, ui) {
            event.preventDefault();
            $("#query-expense-paid-by").val(ui.item.label);
        }
    });

    $("#query-income-paid-by").autocomplete({
        source: "getincomepaidby",
        select: function (event, ui) {
            event.preventDefault();
            $("#query-income-paid-by").val(ui.item.label);
        }
    });

    $("#query-expense-approved-by").autocomplete({
        source: "getexpenseapprovedby",
        select: function (event, ui) {
            event.preventDefault();
            $("#query-expense-approved-by").val(ui.item.label);
            $("#query-expense-approved-by-value").val(ui.item.value);
        },
        response: function (event, ui) {
            $("#query-expense-approved-by-value").val('');
        }
    });

    $("#query-income-approved-by").autocomplete({
        source: "getincomeapprovedby",
        select: function (event, ui) {
            event.preventDefault();
            $("#query-income-approved-by").val(ui.item.label);
            $("#query-income-approved-by-value").val(ui.item.value);
        },
        response: function (event, ui) {
            $("#query-income-approved-by-value").val('');
        }
    });

    $("#search-created-by").autocomplete({
        source: "getcreatebyexpense",
        select: function (event, ui) {
            event.preventDefault();
            $("#search-created-by").val(ui.item.label);
            $("#search-created-by-value").val(ui.item.value);
        },
        response: function (event, ui) {
            $("#search-created-by-value").val('');
        }
    });

    $("#income-entity-from, #income-entity-to").autocomplete({
        source: "getincomeentitytypes",
        select: function (event, ui) {
            event.preventDefault();
            $(this).val(ui.item.label);
        }
    });

    $("#search-income-created-by").autocomplete({
        source: "getcreatebyincome",
        select: function (event, ui) {
            event.preventDefault();
            $("#search-income-created-by").val(ui.item.label);
            $("#search-income-created-by-value").val(ui.item.value);
        },
        response: function (event, ui) {
            $("#search-income-created-by-value").val('');
        }
    });

    function getIncomeTransMode(ele, val) {
        $(ele).autocomplete({
            source: "getincometransactionmode",
            select: function (event, ui) {
                event.preventDefault();
                $(ele).val(ui.item.label);
            }
        });
    }

    function getIncomeEntityType(ele, val) {
        $(ele).autocomplete({
            source: "getincomeentitytypes",
            select: function (event, ui) {
                event.preventDefault();
                $(ele).val(ui.item.label);
            }
        });
    }

    function getExpenseEntityType(ele, val) {
        $(ele).autocomplete({
            source: "getincomeentitytypes",
            select: function (event, ui) {
                event.preventDefault();
                $(ele).val(ui.item.label);
            }
        });
    }

    $('#upload-expense').off().on('click', function () {
        if ($('#property-id-expense').val() == '') {
            alert('Please select property first.');
            return false;
        }
    });

    $('#upload-income').off().on('click', function () {
        if ($('#property-id-income').val() == '') {
            alert('Please select property first.');
            return false;
        }
    });

    $('#query-expense-btn').off().on('click', function () {
        if ($('#property-id-expense').val() == '') {
            alert('Please select property first.');
            return false;
        }
        if (!$('#save-csv').prop('disabled')) {
            if (!confirm('You still have rows available in view, are you sure you want to query again? Click Ok to query again.')) {
                return false;
            }
        }
        initExpenseDatePickerPlugin();
    });

    $('#query-income-btn').off().on('click', function () {
        if ($('#property-id-income').val() == '') {
            alert('Please select property first.');
            return false;
        }
        if (!$('#save-csv-income').prop('disabled')) {
            if (!confirm('You still have rows available in view, are you sure you want to query again? Click Ok to query again.')) {
                return false;
            }
        }
        initDatePickerPlugin();
    });

    $('.transfer-income-terms').off().on('click', function (event) {
        event.preventDefault();
        var customerName = 'Inter Entity Transfer';

        var entityFrom = $('#income-entity-from').val();
        if (entityFrom == '') {
            alert('Entity From is required');
            return false;
        }

        var entityTo = $('#income-entity-to').val();
        if (entityTo == '') {
            alert('Entity To is required');
            return false;
        }

        var transAmount = $('#income-transfer-amount').val();
        if (transAmount == '') {
            alert('Amount is required');
            return false;
        }

        var transMonth = $('#income-transfer-month').val();
        if (transMonth == '') {
            alert('Month is required');
            return false;
        }

        var transDate = $('#income-transfer-date').val();
        if (transDate == '') {
            alert('Transfer Date is required');
            return false;
        }

        var transRemark = $('#income-transfer-remark').val();

        transAmount = parseInt(transAmount);
        var transAmount2 = ~parseInt(transAmount) + 1;
        addTempIncomeRows(1, 1, transAmount, entityTo, transMonth, transDate, transRemark);
        addTempIncomeRows(2, 2, transAmount2, entityFrom, transMonth, transDate, transRemark);
        $("#save-csv-income").trigger("click");
        $("#myModal2").modal("hide");
    });

    $('#save-csv').off().on('click', function () {
        var formData = new FormData($('#expense-list-form')[0]);
        startLoader();
        $.ajax({
            url: 'processexpensecsv',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                if (data != '') {
                    $('.csv-list-items').html(data);
                    alert('Saved Successfully');
                    //document.getElementById("property-expense-form").reset();
                    $('#upload-expense').val('');
                    toBeDeleted = [];
                    savedElements = data;
                    disableSaveBtn();
                    disableUndoBtn();
                    displayTotalExpense();
                }
                hideLoader();
            },
            error: function () {
                hideLoader();
                alert('Something went wrong, please try after some time!');
            }
        })
    });

    $('#approve-income-rows').off().on('click', function () {
        if (radioSelected.length == 0) {
            return false;
        }
        if (!confirm('Are you sure you want to approve?')) {
            return false;
        }
        radioSelected.forEach(approveSelectedIncome);
        var formData = new FormData($('#income-list-form')[0]);
        startLoader();
        $.ajax({
            url: 'approveincomerows',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                if (data != '') {
                    $('.csv-list-items').html(data);
                    savedElements = data;
                    toBeApproved = [];
                    radioSelected = [];
                    hideLoader();
                    alert('Approved Successfully');
                }
                hideLoader();
            },
            error: function () {
                hideLoader();
                alert('Something went wrong, please try after some time!');
            }
        })
    });

    $('#approve-expense-rows').off().on('click', function () {
        if (radioSelected.length == 0) {
            return false;
        }
        if (!confirm('Are you sure you want to approve?')) {
            return false;
        }
        radioSelected.forEach(approveSelectedExpense);
        var formData = new FormData($('#expense-list-form')[0]);
        startLoader();
        $.ajax({
            url: 'approveexpenserows',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                if (data != '') {
                    $('.csv-list-items').html(data);
                    savedElements = data;
                    toBeApproved = [];
                    radioSelected = [];
                    hideLoader();
                    alert('Approved Successfully');
                }
                hideLoader();
            },
            error: function () {
                hideLoader();
                alert('Something went wrong, please try after some time!');
            }
        })
    });

    $('#save-csv-income').off().on('click', function () {
        var formData = new FormData($('#income-list-form')[0]);
        startLoader();
        $.ajax({
            url: 'processincomecsv',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                if (data != '') {
                    $('.csv-list-items').html(data);
                    alert('Saved Successfully');
                    $('#upload-income').val('');
                    toBeDeleted = [];
                    savedElements = data;
                    disableIncomeSaveBtn();
                    disableIncomeUndoBtn();
                    displayTotalIncome();
                }
                hideLoader();
            },
            error: function () {
                hideLoader();
                alert('Something went wrong, please try after some time!');
            }
        })
    });

    $('#delete-expense-btn').off().on('click', function () {
        if (confirm('Are you sure you want to delete?')) {
            startLoader();
            radioSelected.forEach(deletedSelected);
            radioSelected = [];
            enableSaveBtn();
            enableUndoBtn();
            displayTotalExpense();
            $("#save-csv").trigger("click");
        }
    });

    $('#delete-income-btn').off().on('click', function () {
        if (confirm('Are you sure you want to delete?')) {
            startLoader();
            radioSelected.forEach(deletedSelectedIncome);
            radioSelected = [];
            enableIncomeSaveBtn();
            enableIncomeUndoBtn();
            displayTotalIncome();
            $("#save-csv-income").trigger("click");
        }
    });

    function deletedSelected(item, index) {
        if ($('#row-id-' + item).val() != '' && $('#row-id-' + item).val() != undefined) {
            toBeDeleted.push($('#row-id-' + item).val());
            var jsonData = JSON.stringify(toBeDeleted);
            $('#delete-property-expense').val(jsonData);
        }
        $('#data-row-id-' + item).remove();
    }

    function deletedSelectedIncome(item, index) {
        if ($('#row-id-' + item).val() != '' && $('#row-id-' + item).val() != undefined) {
            toBeDeleted.push($('#row-id-' + item).val());
            var jsonData = JSON.stringify(toBeDeleted);
            $('#delete-property-income').val(jsonData);
        }
        $('#data-row-id-' + item).remove();
    }

    function approveSelectedIncome(item, index) {
        if ($('#row-id-' + item).val() != '' && $('#row-id-' + item).val() != undefined) {
            toBeApproved.push($('#row-id-' + item).val());
            var jsonData = JSON.stringify(toBeApproved);
            $('#approve-property-income-rows').val(jsonData);
        }
    }

    function approveSelectedExpense(item, index) {
        if ($('#row-id-' + item).val() != '' && $('#row-id-' + item).val() != undefined) {
            toBeApproved.push($('#row-id-' + item).val());
            var jsonData = JSON.stringify(toBeApproved);
            $('#approve-property-expense-rows').val(jsonData);
        }
    }

    $(document).on('change, keyup', '.csv-inputs', function () {
        enableSaveBtn();
        enableUndoBtn();

        enableIncomeSaveBtn();
        enableIncomeUndoBtn();
    });

    $(document).on('keydown', 'inputs', function () {
        enableSaveBtn();
        enableUndoBtn();

        enableIncomeSaveBtn();
        enableIncomeUndoBtn();
    });

    $('#edit-expense-btn').on('click', function () {
        radioSelected.forEach(editSelected);
    });

    function editSelected(item, index) {
        $('.row-item-' + item).removeAttr('readonly');
        enableSaveBtn();
        enableUndoBtn();
    }

    function getExpTypeSelect() {
<?php
$expType = \app\models\ExpenseType::find()->all();
?>
        var columns = '';
        columns += '<option value="">None</option>';
<?php foreach ($expType as $type) { ?>
            columns += '<option value="<?= $type->expense_code ?>"><?= $type->expense_name ?></option>';
<?php } ?>
        return columns;
    }

    function getIncomeTypeSelect() {
<?php
$expType = \app\models\IncomeType::find()->all();
?>
        var columns = '';
        columns += '<option value="">None</option>';
<?php foreach ($expType as $type) { ?>
            columns += '<option value="<?= $type->income_code ?>"><?= $type->income_name ?></option>';
<?php } ?>
        return columns;
    }

    function getIncomeTypeSelectTrans(type = 1) {
<?php
$expType = \app\models\IncomeType::find()->where(['income_code' => 'TRI'])->one();
?>
        var columns = '';
        columns += '<option value="<?= $expType->income_code ?>"><?= $expType->income_name ?></option>';

<?php
$expType = \app\models\IncomeType::find()->where(['income_code' => 'TRO'])->one();
?>
        var columns2 = '';
        columns2 += '<option value="<?= $expType->income_code ?>"><?= $expType->income_name ?></option>';

        if (type == 1) {
            return columns;
        } else {
            return columns2;
    }
    }

    function addExpenseRows() {
        var startRow = parseInt($('#last-start-row').val());
        $('#last-start-row').val((startRow + 1));
        var totalColumns = parseInt($('#total-columns').val()) + 1;
        var rowStart = '<tr id="data-row-id-' + startRow + '">';
        var rowEnd = '</tr>';
        var column1 = '<td><input class="select-item" type="checkbox" onclick="selectSerial(this, ' + startRow + ')" name="RowId[' + startRow + ']" value="' + startRow + '" /></td>';
        var columns = '';
        for (var i = 0; i < totalColumns; i++) {
            if (i == 0) {
                columns += '<td>';
                columns += '<select class="row-item row-item-' + startRow + '" name="PropertyExpense[' + startRow + '][' + i + ']">';
                columns += getExpTypeSelect();
                columns += '</select>';
                columns += '</td>';
            } else if (i == 1) {
                columns += '<td><input style="width: 85px; text-align: center;" class="row-item row-item-' + i + '" type="text" name="PropertyExpense[' + startRow + '][' + i + ']" value="" /></td>';
            } else if (i == 2) {
                columns += '<td><input style="width: 85px;" class="row-item row-item-' + i + ' expense-query-date" type="text" name="PropertyExpense[' + startRow + '][' + i + ']" value="" /></td>';
            } else if (i == 3) {
                columns += '<td><input style="width: 90px;" class="row-item row-item-' + i + '" type="text" name="PropertyExpense[' + startRow + '][' + i + ']" value="" /></td>';
            } else if (i == 4) {
                columns += '<td><input style="width: 90px;" onkeydown="getExpenseEntityType(this, this.value)" class="row-item row-item-' + i + '" type="text" name="PropertyExpense[' + startRow + '][' + i + ']" value="" /></td>';
            } else if (i == 5) {
                columns += '<td><input style="width: 90px;" class="row-item row-item-' + i + '" type="text" name="PropertyExpense[' + startRow + '][' + i + ']" value="" /></td>';
            } else if (i == 6) {
                columns += '<td><input style="width: 90px;" class="row-item row-item-' + i + ' expense-query-date" type="text" name="PropertyExpense[' + startRow + '][' + i + ']" value="" /></td>';
            } else if (i == 7) {
                columns += '<td><input style="width: auto;" class="row-item row-item-' + i + '" type="text" name="PropertyExpense[' + startRow + '][' + i + ']" value="" /></td>';
            } else if (i == 8) {
                columns += '<td><input style="width: 100px;" class="row-item row-item-' + i + '" type="text" name="PropertyExpense[' + startRow + '][' + i + ']" value="" /></td>';
            } else if (i == 9) {
                columns += '<td><input style="width: 120px;" class="row-item row-item-' + i + '" type="text" name="PropertyExpense[' + startRow + '][' + i + ']" value="" /></td>';
            } else if (i == 10) {
                columns += '<td><input style="width: 70px; text-align: center;" class="row-item expense-query-month row-item-' + i + '" type="text" name="PropertyExpense[' + startRow + '][' + i + ']" value="" /></td>';
            } else if (i == (totalColumns - 1)) {
                columns += '<td><input required="required" class="row-item row-item-' + startRow + '" type="file" name="ExpenseAttachment[' + startRow + ']" /></td>';
            } else {
                columns += '<td><input class="row-item row-item-' + i + '" type="text" name="PropertyExpense[' + startRow + '][' + i + ']" value="" /></td>';
            }
        }
        if (columns != '') {
            $('#expense-table-body').append(rowStart + column1 + columns + rowEnd);
            enableSaveBtn();
            enableUndoBtn();
            $("html, body").animate({scrollTop: $(document).height() - $(window).height()});
            initExpenseDatePickerPlugin();
        }
    }

    function addTempIncomeRows(value, place = 1, transVal, entityVal, month, transDate, transRemark) {
        var startRow = parseInt($('#last-start-row').val());
        $('#last-start-row').val((startRow + 1));
        var totalColumns = parseInt($('#total-columns').val()) + 1;
        var rowStart = '<tr id="data-row-id-' + startRow + '">';
        var rowEnd = '</tr>';
        var column1 = '<td><input class="select-item" type="checkbox" onclick="selectSerial(this, ' + startRow + ')" name="RowId[' + startRow + ']" value="' + startRow + '" /></td>';
        var columns = '';
        for (var i = 0; i < totalColumns; i++) {
            if (i == 2) {
                columns += '<td>';
                columns += '<select style="width: 100%;" class="row-item row-item-' + startRow + '" name="PropertyIncome[' + startRow + '][' + i + ']">';
                columns += getIncomeTypeSelectTrans(value);
                columns += '</select>';
                columns += '</td>';
            } else if (i == 0) {
                columns += '<td><input style="width: 65px;" class="row-item row-item-' + i + '" type="text" name="PropertyIncome[' + startRow + '][' + i + ']" value="" /></td>';
            } else if (i == 1) {
                columns += '<td><input style="width: 85px; text-align: center;" class="row-item row-item-' + i + '" type="text" name="PropertyIncome[' + startRow + '][' + i + ']" value="Inter Entity Transfer" /></td>';
            } else if (i == 3) {
                columns += '<td><input style="width: 85px;" class="row-item row-item-' + i + '" type="text" name="PropertyIncome[' + startRow + '][' + i + ']" value="' + parseFloat(transVal) + '" /></td>';
            } else if (i == 4) {
                columns += '<td><input style="width: 85px;" class="row-item row-item-' + i + '" type="text" name="PropertyIncome[' + startRow + '][' + i + ']" value="" /></td>';
            } else if (i == 5) {
                columns += '<td><input style="width: 90px;" class="row-item row-item-' + i + '" onkeydown="getIncomeTransMode(this, this.value)" type="text" name="PropertyIncome[' + startRow + '][' + i + ']" value="" /></td>';
            } else if (i == 6) {
                columns += '<td><input style="width: 90px;" class="row-item income-query-date row-item-' + i + '" type="text" name="PropertyIncome[' + startRow + '][' + i + ']" value="' + transDate + '" /></td>';
            } else if (i == 8) {
                columns += '<td><input class="row-item row-item-' + i + '" onkeydown="getIncomeEntityType(this, this.value)" type="text" name="PropertyIncome[' + startRow + '][' + i + ']" value="' + entityVal + '" /></td>';
            } else if (i == 9) {
                columns += '<td><input style="width: 90px;" class="row-item income-query-month row-item-' + i + '" type="text" name="PropertyIncome[' + startRow + '][' + i + ']" value="' + month + '" /></td>';
            } else if (i == 10) {
                columns += '<td><input style="width: auto;" class="row-item row-item-' + i + '" type="text" name="PropertyIncome[' + startRow + '][' + i + ']" value="" /></td>';
            } else if (i == 11) {
                columns += '<td><input style="width: auto;" class="row-item row-item-' + i + '" type="text" name="PropertyIncome[' + startRow + '][' + i + ']" value="' + transRemark + '" /></td>';
            } else if (i == (totalColumns - 1)) {
                columns += '<td><input required="required" class="row-item row-item-' + startRow + '" type="file" name="IncomeAttachment[' + startRow + ']" /></td>';
            } else {
                columns += '<td><input class="row-item row-item-' + i + '" type="text" name="PropertyIncome[' + startRow + '][' + i + ']" value="" /></td>';
            }
        }

        if (columns != '') {
            $('#income-table-body').append(rowStart + column1 + columns + rowEnd);
            enableIncomeSaveBtn();
            enableIncomeUndoBtn();
            $("html, body").animate({scrollTop: $(document).height() - $(window).height()});
            initDatePickerPlugin();
    }
    }

    function addIncomeRows() {
        var startRow = parseInt($('#last-start-row').val());
        $('#last-start-row').val((startRow + 1));
        var totalColumns = parseInt($('#total-columns').val()) + 1;
        var rowStart = '<tr id="data-row-id-' + startRow + '">';
        var rowEnd = '</tr>';
        var column1 = '<td><input class="select-item" type="checkbox" onclick="selectSerial(this, ' + startRow + ')" name="RowId[' + startRow + ']" value="' + startRow + '" /></td>';
        var columns = '';
        for (var i = 0; i < totalColumns; i++) {
            if (i == 2) {
                columns += '<td>';
                columns += '<select class="row-item row-item-' + startRow + '" name="PropertyIncome[' + startRow + '][' + i + ']">';
                columns += getIncomeTypeSelect();
                columns += '</select>';
                columns += '</td>';
            } else if (i == 0) {
                columns += '<td><input style="width: 65px;" class="row-item row-item-' + i + '" type="text" name="PropertyIncome[' + startRow + '][' + i + ']" value="" /></td>';
            } else if (i == 1) {
                columns += '<td><input style="width: 85px; text-align: center;" class="row-item row-item-' + i + '" type="text" name="PropertyIncome[' + startRow + '][' + i + ']" value="" /></td>';
            } else if (i == 3 || i == 4) {
                columns += '<td><input style="width: 85px;" class="row-item row-item-' + i + '" type="text" name="PropertyIncome[' + startRow + '][' + i + ']" value="" /></td>';
            } else if (i == 5) {
                columns += '<td><input style="width: 90px;" class="row-item row-item-' + i + '" onkeydown="getIncomeTransMode(this, this.value)" type="text" name="PropertyIncome[' + startRow + '][' + i + ']" value="" /></td>';
            } else if (i == 6) {
                columns += '<td><input style="width: 90px;" class="row-item income-query-date row-item-' + i + '" type="text" name="PropertyIncome[' + startRow + '][' + i + ']" value="" /></td>';
            } else if (i == 8) {
                columns += '<td><input class="row-item row-item-' + i + '" onkeydown="getIncomeEntityType(this, this.value)" type="text" name="PropertyIncome[' + startRow + '][' + i + ']" value="" /></td>';
            } else if (i == 9) {
                columns += '<td><input style="width: 90px;" class="row-item income-query-month row-item-' + i + '" type="text" name="PropertyIncome[' + startRow + '][' + i + ']" value="" /></td>';
            } else if (i == 10) {
                columns += '<td><input style="width: auto;" class="row-item row-item-' + i + '" type="text" name="PropertyIncome[' + startRow + '][' + i + ']" value="" /></td>';
            } else if (i == 11) {
                columns += '<td><input style="width: auto;" class="row-item row-item-' + i + '" type="text" name="PropertyIncome[' + startRow + '][' + i + ']" value="" /></td>';
            } else if (i == (totalColumns - 1)) {
                columns += '<td><input required="required" class="row-item row-item-' + startRow + '" type="file" name="IncomeAttachment[' + startRow + ']" /></td>';
            } else {
                columns += '<td><input class="row-item row-item-' + i + '" type="text" name="PropertyIncome[' + startRow + '][' + i + ']" value="" /></td>';
            }
        }

        if (columns != '') {
            $('#income-table-body').append(rowStart + column1 + columns + rowEnd);
            enableIncomeSaveBtn();
            enableIncomeUndoBtn();
            $("html, body").animate({scrollTop: $(document).height() - $(window).height()});
            initDatePickerPlugin();
        }
    }

    $('#add-expense-btn').off().on('click', function () {
        if ($('#property-id-expense').val() == '') {
            alert('Please select property first.');
            return false;
        }
        if ($('#upload-expense').val() != '') {
            addExpenseRows();
        } else {
            if ($('#last-start-row').val() == '' || $('#last-start-row').val() == undefined) {
                var formData = new FormData();
                formData.append('property_id', $('#property-id-expense').val());
                $.ajax({
                    url: 'generateexpensecsv',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        $('.csv-list-items').html(data);
                        addExpenseRows();
                        $('.after-sel-csv').show();
                    },
                    error: function () {
                        alert('Something went wrong, please try after some time!');
                    }
                });
            } else {
                addExpenseRows();
            }
        }
    });

    $('#add-income-btn').off().on('click', function () {
        if ($('#property-id-income').val() == '') {
            alert('Please select property first.');
            return false;
        }
        if ($('#upload-income').val() != '') {
            //$('.csv-list-items').toggle();
            addIncomeRows();
        } else {
            if ($('#last-start-row').val() == '' || $('#last-start-row').val() == undefined) {
                var formData = new FormData();
                formData.append('property_id', $('#property-id-income').val());
                $.ajax({
                    url: 'generateincomecsv',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        $('.csv-list-items').html(data);
                        //$('.csv-list-items').toggle();
                        addIncomeRows();
                        $('.after-sel-csv').show();
                    },
                    error: function () {
                        alert('Something went wrong, please try after some time!');
                    }
                });
            } else {
                //$('.csv-list-items').toggle();
                addIncomeRows();
            }
        }
    });

    $('#upload-expense-btn').off().on('click', function () {
        if ($('#property-id-expense').val() == '') {
            alert('Please select property first.');
            return false;
        }
        if ($('#upload-expense').val() == '') {
            alert('Please select data file.');
            return false;
        }

        if (!confirm('Are you sure you want to upload the selected data file?')) {
            return false;
        }
        var formData = new FormData($('#property-expense-form')[0]);
        startLoader();
        $.ajax({
            url: 'parseexpensecsv',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                $('.csv-list-items').html(data);
                $('#upload-expense').val('');
                if (data != 'Invalid') {
                    $('.after-sel-csv').show();
                    savedElements = data;
                    displayTotalExpense();
                } else {
                    $('.csv-list-items').html('<p>Invalid CSV file</p>');
                    $('.after-sel-csv').hide();
                }
                hideLoader();
            },
            error: function () {
                hideLoader();
                alert('Something went wrong, please try after some time!');
            }
        })
    });

    $('#upload-income-btn').off().on('click', function () {
        if ($('#property-id-income').val() == '') {
            alert('Please select property first.');
            return false;
        }
        if ($('#upload-income').val() == '') {
            alert('Please select data file.');
            return false;
        }

        if (!confirm('Are you sure you want to upload the selected data file?')) {
            return false;
        }
        var formData = new FormData($('#property-income-form')[0]);
        startLoader();
        $.ajax({
            url: 'parseincomecsv',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                $('.csv-list-items').html(data);
                $('#upload-income').val('');
                if (data != 'Invalid') {
                    $('.after-sel-csv').show();
                    savedElements = data;
                    displayTotalIncome();
                } else {
                    $('.csv-list-items').html('<p>Invalid CSV file</p>');
                    $('.after-sel-csv').hide();
                }
                hideLoader();
            },
            error: function () {
                hideLoader();
                alert('Something went wrong, please try after some time!');
            }
        })
    });

    $('#discard-changes').click(function () {
        $('#csv-list-box').html(savedElements);
        toBeDeleted = [];
        disableUndoBtn();
        disableSaveBtn();
        displayTotalIncome();
        displayTotalExpense();
    });

    $('.query-expense-terms').off().on('click', function () {
        var formData = new FormData($('#expense-query-form')[0]);
        startLoader();
        $.ajax({
            url: 'queryexpensedata',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                $('.csv-list-items').html(data);
                if (data != 'Invalid') {
                    $('.after-sel-csv').show();
                    $('#myModal').modal('hide');
                    savedElements = data;
                    displayTotalExpense();
                } else {
                    $('.csv-list-items').html('<p>Invalid CSV file</p>');
                    $('.after-sel-csv').hide();
                }
                hideLoader();
            },
            error: function () {
                hideLoader();
                alert('Something went wrong, please try after some time!');
            }
        })
    });

    $('.query-income-terms').off().on('click', function () {
        startLoader();
        var formData = new FormData($('#income-query-form')[0]);
        $.ajax({
            url: 'queryincomedata',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                $('.csv-list-items').hide();
                $('.csv-list-items').html(data);
                if (data != 'Invalid') {
                    $('.after-sel-csv').show();
                    $('#myModal').modal('hide');
                    savedElements = data;
                    displayTotalIncome();
                } else {
                    $('.csv-list-items').html('<p>Invalid CSV file</p>');
                    $('.after-sel-csv').hide();
                }
                hideLoader();
            },
            error: function () {
                hideLoader();
                alert('Something went wrong, please try after some time!');
            }
        })
    });

    $('#download-csv').off().on('click', function () {
        enableUnload = false;
        var formData = new FormData($('#expense-list-form')[0]);
        $.ajax({
            url: 'exportexpensetocsv',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                if (data != '') {
                    window.location.href = 'downloadexpensecsv?d=' + data;
                }
            },
            error: function () {
                alert('Something went wrong, please try after some time!');
            }
        });
    });

    $('#download-income-csv').off().on('click', function () {
        enableUnload = false;
        var formData = new FormData($('#income-list-form')[0]);
        $.ajax({
            url: 'exportincometocsv',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                if (data != '') {
                    window.location.href = 'downloadincomecsv?d=' + data;
                }
            },
            error: function () {
                alert('Something went wrong, please try after some time!');
            }
        });
    });

    $("body").on("click", "#export-pnl-rows-expense", function () {
        enableUnload = false;
        var formData = new FormData($('#pnl-trans-list-form')[0]);
        $.ajax({
            url: 'exportexpensetocsv',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                if (data != '') {
                    window.location.href = 'downloadexpensecsv?d=' + data;
                }
            },
            error: function () {
                alert('Something went wrong, please try after some time!');
            }
        });
    });

    $("body").on("click", "#export-pnl-rows-income", function () {
        enableUnload = false;
        var formData = new FormData($('#pnl-trans-list-form')[0]);
        $.ajax({
            url: 'exportincometocsv',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                if (data != '') {
                    window.location.href = 'downloadincomecsv?d=' + data;
                }
            },
            error: function () {
                alert('Something went wrong, please try after some time!');
            }
        });
    });

    $('#toggle-table-view').off().on('click', function () {
        $('.csv-list-items').toggle();
    });

    function removeBeforeUnload(event) {
        event.returnValue = '\o/';
    }

    function selectSerial(ele, row) {
        var index = 0;
        if ($(ele).prop('checked') == true) {
            radioSelected.push(row);
        } else if ($(ele).prop('checked') == false) {
            index = radioSelected.indexOf(row);
            radioSelected.splice(index, 1);
        }
    }

    function checkAll() {
        //$('.select-item').click();
        if ($('input[name=select_all]').is(':checked')) {
            $(".select-item").each(function () {
                radioSelected.push($(this).val());
            });
            $(".select-item").prop('checked', true);
        } else {
            $(".select-item").each(function () {
                var index = radioSelected.indexOf($(this).val());
                radioSelected.splice(index, 1);
            });
            $(".select-item").prop('checked', false);
        }
    }

    function removeAttachment(ele, eleId) {
        $(ele).hide();
        $('#attachment-view-link-' + eleId).hide();
        $('#attach-file-' + eleId).show();
        enableSaveBtn();
        enableUndoBtn();
        enableIncomeSaveBtn();
        enableIncomeUndoBtn();
    }

    $(document).ready(function () {
        $(".expense-query-date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-M-yy'
        });

        $(".income-query-date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-M-yy'
        });
    });

    $(document).ready(function () {
        $('#sidebarCollapse').on('click', function () {
            $('#left-panel').toggleClass('toggle-side-nav');
            $('#page-wrapper').toggleClass('toggle-page-wrapper');
        });

        $('#sidebarCollapse').click();
    });

    $('#generate-pnl-report').off().on('click', function () {
        var propertyName = $('#search-property-expense').val();
        var propertyId = $('#property-id-expense').val();
        if (propertyName == '' || propertyName == "undefined" || propertyId == "" || propertyId == "undefined") {
            alert('Please select property.');
            return false;
        }
        var formData = new FormData($('#pnl-reporting-form')[0]);
        $.ajax({
            url: 'getpronlossreport',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                if (data != '') {
                    $('.pnl-reporting-list').html(data);
                    // $('#download-pnl-report').show();
                } else {
                    $('#download-pnl-report').hide();
                }
            },
            error: function () {
                alert('Something went wrong, please try after some time!');
            }
        })
    });

    $("body").on("click", "#expense-view-transaction", function () {
        var formData = new FormData($('#get-pnl-transaction-rows')[0]);
        $.ajax({
            url: 'getexpensepnlrows',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                if (data != '') {
                    $('.viewTransModalBox').html(data);
                }
            },
            error: function () {
                alert('Something went wrong, please try after some time!');
            }
        })
    });

    $("body").on("click", "#clear-view-transaction", function () {
        $('.viewTransModalBox').html('');
    });

    $("body").on("click", "#income-view-transaction", function () {
        var formData = new FormData($('#get-pnl-transaction-rows')[0]);
        $.ajax({
            url: 'getincomepnlrows',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                if (data != '') {
                    $('.viewTransModalBox').html(data);
                }
            },
            error: function () {
                alert('Something went wrong, please try after some time!');
            }
        })
    });

    $('#pnl-statement-type').change(function () {
        if (this.value == 1) {
            $('#pnl-statement-from-monthly').show();
            $('#pnl-statement-from-quarterly').hide();
            $('#pnl-statement-from-year').css('width', '51%');
            $('#pnl-statement-from-fy').hide();
            $('#pnl-statement-from-year').show();
        } else if (this.value == 2) {
            $('#pnl-statement-from-monthly').hide();
            $('#pnl-statement-from-quarterly').show();
            $('#pnl-statement-from-year').css('width', '51%');
            $('#pnl-statement-from-fy').hide();
            $('#pnl-statement-from-year').show();
        } else if (this.value == 3) {
            $('#pnl-statement-from-monthly').hide();
            $('#pnl-statement-from-quarterly').hide();
            $('#pnl-statement-from-year').css('width', '99%');
            $('#pnl-statement-from-fy').hide();
            $('#pnl-statement-from-year').show();
        } else if (this.value == 4) {
            $('#pnl-statement-from-monthly').hide();
            $('#pnl-statement-from-quarterly').hide();
            $('#pnl-statement-from-year').hide();
            $('#pnl-statement-from-fy').css('width', '99%');
            $('#pnl-statement-from-fy').show();
        }
    });

    $('#delete-fav-row').click(function () {
        if (!confirm('Are you sure you want to remove this record?')) {
            return false;
        }
        var formData = new FormData($('#property-fav-record-form')[0]);
        formData.append('fv_id', $(this).attr('data-fv-row'));
        $.ajax({
            url: 'removefavrow',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                $('#search-property-record').click();
            },
            error: function () {
                alert('Something went wrong, please try after some time!');
            }
        });
    });
</script>
<script>
    if (isUnload) {
        window.addEventListener('beforeunload', function (e) {
            if (enableUnload) {
                if ($('#save-csv-income').length) {
                    if (!$('#save-csv-income').prop('disabled')) {
                        e.preventDefault();
                        e.returnValue = '';
                    }
                }

                if ($('#save-csv').length) {
                    if (!$('#save-csv').prop('disabled')) {
                        e.preventDefault();
                        e.returnValue = '';
                    }
                }
            } else {
                enableUnload = true;
            }
        }, true);
    }
</script>