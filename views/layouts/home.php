<?php

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

AppAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <link rel="canonical" href="<?= Yii::$app->request->absoluteUrl; ?>" />
        <script>
            (function (w, d, s, l, i) {
                w[l] = w[l] || [];
                w[l].push({'gtm.start':
                            new Date().getTime(), event: 'gtm.js'});
                var f = d.getElementsByTagName(s)[0],
                        j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
                j.async = true;
                j.src =
                        'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
                f.parentNode.insertBefore(j, f);
            })(window, document, 'script', 'dataLayer', 'GTM-WHMGT8D');
        </script>
        <meta name="google-site-verification" content="uKtGStTmPBl5f6s9kJQ-o4C6Gk31Im0BtbHhBhhLGnI" />
        <meta name="geo.region" content="IN-KA" />
        <meta name="geo.placename" content="Bengaluru, Karnataka, India" />
        <meta name="geo.position" content="77.6617983; 13.0013648" />
        <meta name="robots" CONTENT="INDEX, FOLLOW"> 
        <meta name="revisit-after" content="1 days"/> 
        <meta name="author" content="Easyleases">
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="Families, singles and co-living furnished rental properties apartments villas independent">
        <meta name="author" content="Easyleases Technologies Pvt Ltd">
        <?= Html::csrfMetaTags() ?>
        <title><?= $this->params['head_title'] ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="<?php echo $this->params['meta_desc'] ?>">
        <?php $this->head() ?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <style>
            nav.navbar.navbar-expand-lg.navbar-dark.indigo.fixed-top.no-repeat {
                background: white !important;
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
        <style>
            footer {
                background: black;
                margin-bottom: 0px !important;
                color: #6c757d!important;
            }
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
                width: 75%;
                padding-top: 15px;
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
                width: 140px;
                height: 40px;
                border-radius: 3px;
                font-size: 13px;
                font-weight: bold;
                font-style: normal;
                font-stretch: normal;
            }

            .identity_bg {
                background-color: #f5f5f5;
                margin-top: 15px;
            }

            .adar_img {
                width: 286px;
                height: 288px;
            }

            .tbl_padd {
                padding-top: 35px !important;
                width: 20%;
            }

            .table>tbody>tr>td,
            .table>tbody>tr>th,
            .table>tfoot>tr>td,
            .table>tfoot>tr>th,
            .table>thead>tr>td,
            .table>thead>tr>th {
                padding: 8px;
                line-height: 1.42857143;
                vertical-align: top;
                border-top: 1px solid #fff !important;
            }

            form div.waves-input-wrapper.waves-effect.waves-light {
                width: 100%;
            }
        </style>
    </head>
    <body>
<?php $this->beginBody() ?>

        <!-- Notification box for Booking and Rent payment response [STARTS HERE] -->
<?php if (!empty(Yii::$app->session->getFlash('noti-success')) || !empty(Yii::$app->session->getFlash('noti-error'))) { ?>
            <div id="pop-up-notification-cover" style="height: 100%; width: 100%; background: black !important; position: fixed; z-index: 9999; opacity: 0.8;">&nbsp;</div>
            <div id="pop-up-notification" style="position: fixed; min-height: 100px; z-index: 9999; padding: 26px; background: white;
                 border: 2px solid lightgrey; top: 5%; box-shadow: 0px 5px 25px 5px black; <?= (!empty(Yii::$app->session->getFlash('noti-error'))) ? 'min-width: 470px; left: 35%; right: 35%;' : 'min-width: 600px; left: 25%; right: 25%;'; ?>">
                <div class="container">
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
        <?php $form = ActiveForm::begin(['action' => 'site/createnotidownload', 'options' => ['enctype' => 'multipart/form-data']]); ?>
                                <input type="hidden" name="noti-download" value="<?= (!empty(Yii::$app->session->getFlash('noti-download'))) ? base64_encode(Yii::$app->session->getFlash('noti-download')) : ''; ?>" />
                                <button type="submit" style="width: 100%; font-weight: bold; font-size: 16px;" class="btn btn-md btn-primary btn_mycolor">
                                    Download
                                </button>
        <?php ActiveForm::end(); ?>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6 col-xl-6">
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
                                    <input type="hidden" name="prop_book_retry" value="1" />
                                    <input type="hidden" name="order_id" value="<?= Yii::$app->session->getFlash('noti-retry'); ?>" />
                                    <input class="btn btn-md btn-primary btn_mycolor" style="width: 96%; font-weight: bold; font-size: 16px;" type="submit" name="submit_retry" value="Retry" />
                                </form>
                            </div>
                            <div style="margin: auto;" class="col-xs-6 col-sm-6 col-md-6 col-xl-6">
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

<?= $content ?>

        <script src="<?php echo Yii::$app->userdata->autoVersion(Url::home(true) . 'js/validate.js'); ?>"></script>

        <!-- Footer starts from here -->
        <footer class="pt-4 border-top px-4">
            <div class="row mr-0">
                <div class="col-3">
                    <div class="py-1"><a style="margin: 0px 10px 0px 10px;" href="https://www.facebook.com/Easyleases/" target="_blank"><img alt="Easyleases/Facebook" style="width: 45px; height: 45px; border-radius: 7px;" src="<?php echo Url::home(true) ?>images/icons/facebook-icon.png"></a></div>
                    <div class="py-1"><a style="margin: 0px 10px 0px 10px;" href="https://www.linkedin.com/company/easyleases/" target="_blank"><img alt="Easyleases/Linkedin" style="width: 45px; height: 45px; border-radius: 7px;" src="<?php echo Url::home(true) ?>images/icons/linkedin-icon.png"></a></div>
                    <div class="py-1"><a style="margin: 0px 10px 0px 10px;" href="https://twitter.com/EasyleasesTech" target="_blank"><img alt="Easyleases/Twitter" style="width: 45px; height: 45px; border-radius: 7px;" src="<?php echo Url::home(true) ?>images/icons/twitter-icon.png"></a></div>
                </div>
                <div class="col-3">
                    <h5>Resources</h5>
                    <ul class="list-unstyled text-small">
                        <li><a target="_blank" class="text-muted" href="<?php echo Url::home(true) ?>blog">Blog</a></li>
                        <li><a class="text-muted" href="<?php echo Url::home(true) ?>users/owner">List Your Property</a></li>
                        <li><a class="text-muted" href="<?php echo Url::home(true) ?>users/advisor">Partner With Us</a></li>
                    </ul>
                </div>
                <div class="col-3">
                    <h5>Easyleases</h5>
                    <ul class="list-unstyled text-small">
                        <li><a target="_blank" class="text-muted" href="<?php echo Url::home(true) ?>about-us">About Us</a></li>
                        <li><a target="_blank" class="text-muted" href="<?php echo Url::home(true) ?>terms-and-condition">Terms and Condition</a></li>
                        <li><a target="_blank" class="text-muted" href="<?php echo Url::home(true) ?>refund-policy">Refund Policy</a></li>
                        <li><a target="_blank" class="text-muted" href="<?php echo Url::home(true) ?>faq">FAQ's</a></li>
                        <li><a target="_blank" class="text-muted" href="<?php echo Url::home(true) ?>privacy-policy">Privacy Policy</a></li>
                        <li><a target="_blank" class="text-muted" href="<?php echo Url::home(true) ?>contact">Contact Us</a></li>
                    </ul>
                </div>
                <div class="col-3">
                    <h5>Download the app</h5>
                    <ul class="list-unstyled text-small">
                        <li><div class="py-2"><a target="_blank" href="https://apps.apple.com/us/app/easyleases-tenant/id1474443593?ls=1"><img alt="Apps Store" style="width: 180px;" src="<?php echo Url::home(true) ?>media/icons/appstore.png"></a></div></li>
                        <li><div class="py-2"><a target="_blank" href="https://play.google.com/store/apps/details?id=com.easyleases.app"><img alt="Play Store" style="width: 180px;" src="<?php echo Url::home(true) ?>media/icons/googlestore.png"></a></div></li>
                    </ul>
                </div>
            </div>
            <div class="row mr-0">
                <div class="col-12">
                    <p class="text-center"><small>&copf; 2018 Easyleases Technologies Private Limited</small></p>
                </div>
            </div>
        </footer>

        <!--copyright-->

<?php $this->endBody() ?>

        <script>
            var setElementHeight = function () {
                var height = $(window).height();
                $('.autoheight').css('min-height', height);
            };

            $(document).ready(function () {
                setElementHeight();
            });
        </script>
        <style>
            .autoheight{
                height: 100%;
            }
        </style>
        <script type="text/javascript">
            window.smartlook || (function (d) {
                var o = smartlook = function () {
                    o.api.push(arguments)
                }, h = d.getElementsByTagName('head')[0];
                var c = d.createElement('script');
                o.api = new Array();
                c.async = true;
                c.type = 'text/javascript';
                c.charset = 'utf-8';
                c.src = 'https://rec.smartlook.com/recorder.js';
                h.appendChild(c);
            })(document);
            smartlook('init', '760b710efd963e8e7d1e3163baf792ac99e4f5bb');
        </script>
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
<?php die; ?>