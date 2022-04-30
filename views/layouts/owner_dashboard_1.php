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
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>PMS</title>
        <!-- Bootstrap CSS CDN -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <!-- Our Custom CSS -->
        <link rel="stylesheet" href="<?php echo Url::home(true); ?>css/css/newstyle_admin/style_o.css">
        <script type="text/javascript" src="<?php echo Url::home(true); ?>js/admin/script/myscript.js"></script>
        <style>
            .no_margin {
                margin-left: -15px;
            }

            .sec_boarder {
                border: solid 1px rgba(170, 174, 178, 0.3);
                margin: 10px;
                width: 340px;
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



            .hr_style {
                width: 115%;
                margin-left: -15px;
                /* margin-top: 0px; */
            }

            .custom_card {
                /* margin-top: 15px; */
            }

            .cust_span>span {
                color: white;
            }

            .sec_padd {
                padding-top: 15px;
            }

            .img_div {
                float: left;
                margin-left: -17px;
                margin-top: 2px;
            }
        </style>
    </head>
    <body>
        <?php $this->beginBody() ?>

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
                    echo '<a>'
                    . Html::beginForm(['/site/logout'], 'post')
                    . Html::submitButton(
                            '<div style= "color: #26344B;">Logout</div>', ['class' => 'btn btn-link logout', 'style' => 'color:#000;']
                    )
                    . Html::endForm()
                    . '</a>'
                    ?>

                </div>
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
                            ['label' => '<span><i class="fa fa-tachometer" aria-hidden="true"></i></span>Dashboard', 'url' => ['/site/mydashboard'], 'visible' => Yii::$app->userdata->usertype == 4],
                            ['label' => '<span><i class="fa fa-file-text-o" aria-hidden="true"></i></span>Lease Agreement', 'url' => ['/site/ownerlease?id=' . Yii::$app->getRequest()->getQueryParam('id')], 'visible' => Yii::$app->userdata->usertype == 4],
                            ['label' => '<span><i class="fa fa-credit-card-alt" aria-hidden="true"></i></span>Payment Statement', 'url' => ['/site/ownerpayments?id=' . Yii::$app->getRequest()->getQueryParam('id')], 'visible' => Yii::$app->userdata->usertype == 4],
                            ['label' => '<span><i class="fa fa-male" aria-hidden="true"></i></span>Tenant Details', 'url' => ['/site/mytenants?id=' . Yii::$app->getRequest()->getQueryParam('id')], 'visible' => (Yii::$app->userdata->usertype == 4 && Yii::$app->userdata->getPropertyAgreementType($_GET['id']) != 2)],
//                        ['label' => '<span><i class="fa fa-male" aria-hidden="true"></i></span>Tenant onboarding status', 'url' => ['/site/ownerleads?id=' . Yii::$app->getRequest()->getQueryParam('id')], 'visible' => Yii::$app->userdata->usertype == 4],
                            ['label' => '<span><i class="fa fa-wrench" aria-hidden="true"></i></span> Service Request', 'url' => ['/site/ownerrequests?id=' . Yii::$app->getRequest()->getQueryParam('id')], 'visible' => Yii::$app->userdata->usertype == 4],
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
        <script src="<?php echo Url::home(true); ?>js/owner.js"></script>
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


        </script>
        <style>
            .autoheight{
                height: 100%;
            }
        </style>

    </body>
</html>
<?php $this->endPage() ?>
