<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="canonical" href="<?= Yii::$app->request->absoluteUrl; ?>" />
        <!-- Google Tag Manager -->
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
            })(window, document, 'script', 'dataLayer', 'GTM-WGTVKBL');</script>
        <!-- End Google Tag Manager -->

        <meta name="google-site-verification" content="uKtGStTmPBl5f6s9kJQ-o4C6Gk31Im0BtbHhBhhLGnI" />

        <meta name="geo.region" content="IN-KA" />
        <meta name="geo.placename" content="Bengaluru, Karnataka, India" />
        <meta name="geo.position" content="77.6617983; 13.0013648" />
        <meta name="robots" CONTENT="INDEX, FOLLOW"> 
        <meta name="revisit-after" content="1 days"/> 
        <meta name="author" content="Easyleases"> 
        <meta charset="UTF-8"/>
        <?= Html::csrfMetaTags() ?>
        <title><?php echo $this->params['head_title'] ?></title>
        <?php $this->head() ?>

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="<?= $this->params['meta_desc'] ?>">
        <meta name="keywords" content="<?= $this->params['meta_keywords'] ?>"/>
        <meta name="author" content="Easyleases Technologies Pvt Ltd" />
        <meta name="copyright" content="Easyleases" />
        <meta http-equiv="cache-control" content="no-cache"/>
        <meta http-equiv="cache-control" content="no-store" />

        <!-- Favicon Area -->
        <link rel="shortcut icon" type="image/png" href="<?php echo Url::home(true); ?>/favicon.ico"/>
        <!-- Favicon Area -->

        <!-- External Styling Starts From Here-->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="<?php echo Url::home(true); ?>camp_assets/css/bootstrap.min.css" rel="stylesheet">
        <!-- External Styling Ends Here-->

        <!-- Custom External Styling Starts From Here-->
        <link href="<?php echo Url::home(true); ?>camp_assets/css/maincamp.css?v=1.00" rel="stylesheet">
        <!-- Custom External Styling Ends Here-->

        <!-- Google Analytics Code Starts From Here -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-121786083-1"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());
            gtag('config', 'UA-121786083-1');
        </script>
        <!-- Google Analytics Code Ends Here -->

        <!-- Google Global Tag Code Starts From Here -->
        <!-- Global site tag (gtag.js) - Google Ads: 800087651 --> <script async src="https://www.googletagmanager.com/gtag/js?id=AW-800087651"></script> <script> window.dataLayer = window.dataLayer || [];
            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());
            gtag('config', 'AW-800087651');</script>
        <!-- Event snippet for Form Submission conversion page In your html page, add the snippet and call gtag_report_conversion when someone clicks on the chosen link or button. --> <script> function gtag_report_conversion(url) {
                var callback = function () {
                    if (typeof (url) != 'undefined') {
                        window.location = url;
                    }
                };
                gtag('event', 'conversion', {'send_to': 'AW-800087651/O01uCLeDt4kBEOO8wf0C', 'event_callback': callback});
                return false;
            }</script>
        <!-- Google Global Tag Code Ends Here -->
        <style>
            .heading-tag {
                font-size: 2rem;
                width: 100%;
                padding-top: 2%;
            }

            .title-tag {
                font-size: 2.5rem;
            }

            #why-easyleases li {
                list-style: none;
                padding-top: 5px;
                padding-bottom: 5px;
            }

            #why-easyleases ul {
                padding-left: 0px;
            }

            .navbar-dark .navbar-nav .nav-link {
                color: rgb(82, 86, 89);
                font-size: 13px;
            }

            nav.navbar.navbar-expand-lg.navbar-dark.indigo.fixed-top.no-repeat {
                background: white !important;
            }
            .badge, .btn, .card:not([class*=card-outline-]), .chip, .jumbotron, .modal-dialog.cascading-modal .modal-c-tabs .nav-tabs, .modal-dialog.modal-notify .modal-header, .navbar, .pagination .active .page-link, .z-depth-1 {
                box-shadow: 0 2px 5px 0 rgba(0, 0, 0, .16), 0 2px 10px 0 rgba(0, 0, 0, 0);
            }
            a.btn.btn-primary.btn_mycolor.rightlistbtns {
                font-size: 12.8px;
                padding: 13px 34px;
                margin: 6px;
            }
            a.navbar-brand {
                margin-top: 12px;
                margin-left: 11px;
            }
            a.nav-link.small {
                line-height: 42px;
                margin: 2px 17px;
            }

            nav li.nav-item {
                line-height: 42px;
                margin: 0px 1px;
            }

            ul.navbar-nav {
                margin-top: 6px;
            }

            .navbar.navbar-dark .breadcrumb .nav-item .nav-link:hover, .navbar.navbar-dark .navbar-nav .nav-item .nav-link:hover {
                color: rgb(224, 75, 63) !important;
            }
            .active {
                /* background-color: #d90000; */
                color: #e24c3f !important;
            }
            .btn-primary:not([disabled]):not(.disabled).active, .btn-primary:not([disabled]):not(.disabled):active, .show>.btn-primary.dropdown-toggle {
                background: white !important;
            }
            .navbar-toggler {
                padding: .25rem .75rem;
                font-size: 1.25rem;
                line-height: 1;
                background-color: #dc3545;
                border: 2px solid red;  
            }
            .navbar-toggler {
                padding: .25rem .75rem;
                font-size: 1.25rem;
                line-height: 1;  
            }
        </style>
    </head>
    <body>
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WGTVKBL"
                          height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
        <?php $this->beginBody() ?>

        <!-- Document Block Starts From here -->

        <div class="container-fluid">
            <header>
                <!-- nab bar -->
                <nav class="navbar navbar-expand-lg navbar-dark indigo fixed-top no-repeat">
                    <!-- Navbar brand -->
                    <a title="Easyleases Technologies Private Limited" class="navbar-brand" href="<?= Url::home(true) ?>"><img alt="Easyleases Property Management Services" src="<?php echo Url::home(true); ?>images/newlogo1.png"></a>
                    <!-- Collapse button -->
                    <!--<div class="homebtn">-->

                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                    <!--</div>-->

                    <!-- Collapsible content -->
                    <div class="collapse navbar-collapse cust-navcolps" id="navbarSupportedContent">
                        <!-- Links -->
                        <ul class="navbar-nav">
                            <li class="nav-item ">
                                <a class="nav-link small" href="<?php echo Url::home(true); ?>#searchproperty">Search Property <span class="sr-only">(current)</span></a>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link small" href="<?php echo Url::home(true); ?>#service">Our Services</a>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link small" href="<?php echo Url::home(true); ?>#advantage">Advantages</a>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link small" href="<?php echo Url::home(true); ?>#transparent">How it works</a>
                            </li>
                            <li class="nav-item pull-right ">
                                <a class="btn btn-primary btn_mycolor rightlistbtns" href="<?php echo Url::home(true); ?>users/owner">List Your Property</a>
                            </li>
                            <li class="nav-item ">
                                <a class="btn btn-primary btn_mycolor rightlistbtns" href="<?php echo Url::home(true); ?>users/advisor">Partner With Us</a>
                            </li>
                            <!--li class="nav-item ">
                               <button type="button" class="btn btn-blue-grey">FAQ</button>
                               </li-->
                            <li class="nav-item ">
                                <a class="btn btn-primary btn_mycolor rightlistbtns" href="<?php echo Url::home(true); ?>site/login">Sign In</a>
                            </li>
                        </ul>
                        <!-- Links -->
                    </div>
                    <!-- Collapsible content -->
                </nav>
            </header>
            <?= $content ?>
            <footer>
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <div class="footer-links" style="float: left; width: 80%;">
                            <div class="col-md-4 col-sm-4  col-xs-12 text-set">
                                <a onclick="return gtag_report_conversion('<?php echo Url::home(true); ?>about-us')" href="<?php echo Url::home(true); ?>about-us" target="_blank">About Us</a>
                            </div>
                            <div class="col-md-4 col-sm-4  col-xs-12 text-set">
                                <a onclick="return gtag_report_conversion('<?php echo Url::home(true); ?>terms-and-condition')" href="<?php echo Url::home(true); ?>terms-and-condition" target="_blank">Terms &amp; Conditions</a>
                            </div>
                            <div class="col-md-4 col-sm-4  col-xs-12 text-set">
                                <a onclick="return gtag_report_conversion('<?php echo Url::home(true); ?>refund-policy')" href="<?php echo Url::home(true); ?>refund-policy" target="_blank">Refund Policy</a>
                            </div>
                            <div class="col-md-4 col-sm-4  col-xs-12 text-set">
                                <a onclick="return gtag_report_conversion('<?php echo Url::home(true); ?>faq')" href="<?php echo Url::home(true); ?>faq" target="_blank">FAQ's</a>
                            </div>
                            <div class="col-md-4 col-sm-4  col-xs-12 text-set">
                                <a onclick="return gtag_report_conversion('<?php echo Url::home(true); ?>privacy-policy')" href="<?php echo Url::home(true); ?>privacy-policy" target="_blank">Privacy Policy</a>
                            </div>
                            <div class="col-md-4 col-sm-4  col-xs-12 text-set">
                                <a onclick="return gtag_report_conversion('<?php echo Url::home(true); ?>contact')" href="<?php echo Url::home(true); ?>contact" target="_blank">Contact Us</a>
                            </div>
                        </div>
                        <div class="social-media-links" style="float: right; width: 20%; padding: 1%;">
                            <?= Yii::$app->userdata->getSocialMediaLinks(); ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <div class="copyright">&copy; 2018 Easyleases Technologies Private Limited</div>
                    </div>
                </div>
            </footer>
        </div>

        <!-- Document Block Ends Here -->


        <!-- Core Javascript Libraries Starts From Here -->
        <script src="<?php echo Url::home(true); ?>camp_assets/js/jquery-3.3.1.min.js" ></script>
        <script src="<?php echo Url::home(true); ?>camp_assets/js/bootstrap.bundle.min.js" ></script>
        <script src="<?php echo Url::home(true); ?>camp_assets/js/jquery.easing.min.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAdCMdyhn49XUtPEG8wsv2ILsfzfjV3jK0&libraries=places&callback=initMap" async defer></script>
        <!-- Core Javascript Libraries Ends Here -->

        <!-- Custom External Javascript Code Starts From Here -->
        <script src="<?php echo Url::home(true); ?>camp_assets/js/maincamp.js"></script>
        <script src="<?php echo Url::home(true); ?>camp_assets/js/helper.js"></script>
        <!-- Custom External Javascript Code Ends Here -->

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
<script>

                                    $(document).ready(function () {
                                        $('ul li a').click(function () {
                                            $('li a').removeClass("active");
                                            $(this).addClass("active");
                                        });
                                    });
</script>