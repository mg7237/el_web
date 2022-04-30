<?php

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\models\PropertyTypes;
use app\models\Properties;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

AppAsset::register($this);
?>
<!doctype html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title><?= Html::encode($this->title) ?></title>
        <link rel="canonical" href="<?= Yii::$app->request->absoluteUrl; ?>" />
        <script>
            (function (w, d, s, l, i) {
                w[l] = w[l] || [];
                w[l].push({'gtm.start':
                            new Date().getTime(), event: 'gtm.js'});
                var f = d.getElementsByTagName(s)[0],
                        j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
                j.async = true;
                j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
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
        <!-- Bootstrap core CSS -->
        <link href="//stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
        <!-- Custom styles for this template -->
        <link href="<?php echo Yii::$app->userdata->autoVersion(Url::home(true) . 'css/detail.css'); ?>" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans">
        <link rel="stylesheet" type="text/css" href="<?php echo \yii\helpers\Url::home(true) ?>css/map-icons.css">

        <!-- NO CACHE -->
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
        <meta http-equiv="Pragma" content="no-cache" />
        <meta http-equiv="Expires" content="0" />

    </head>
    <body>
        <div class="loading">Loading&#8230;</div>
        <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
            <a class="navbar-brand" href="<?php echo \yii\helpers\Url::home(true) ?>">
                <img src="<?php echo \yii\helpers\Url::home(true) ?>images/newlogo1.png" />
            </a>
            <a class="nav-link active section-links" href="#property-overview">Overview</a>
            <a class="nav-link section-links" href="#property-features">Features</a>
            <a class="nav-link section-links" href="#property-amenties">Amenities</a>
            <a class="nav-link section-links" href="#neighborhood">Neighbourhood</a>
            <a style="display: none;" onclick="takeToTop()" id="top-button" class="nav-link active section-links" href="JavaScript:void(0);">Top</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul style="visibility: hidden;" class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Dropdown
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="#">Action</a>
                            <a class="dropdown-item" href="#">Another action</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Something else here</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" href="#">Disabled</a>
                    </li>
                </ul>
                <?php if (Yii::$app->user->isGuest) { ?>
                    <a class="btn btn-danger my-2 my-sm-0 signin-btn" href="<?php echo Url::home() ?>site/login">Sign In</a>
                <?php } else { ?>
                    <a class="btn btn-danger my-2 my-sm-0 signin-btn" href="<?php echo Url::home() ?>site/myprofile">My Profile</a>
                <?php } ?>
            </div>
        </nav>
        <h3>&nbsp;</h3><h3>&nbsp;</h3>
        <?= $content ?>
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
                        <li><div class="py-2"><a target="_blank" href="https://apps.apple.com/us/app/easyleases-tenant/id1474443593?ls=1"><img style="width: 180px;" src="<?php echo Url::home(true) ?>media/icons/appstore.png"></a></div></li>
                        <li><div class="py-2"><a target="_blank" href="https://play.google.com/store/apps/details?id=com.easyleases.app"><img style="width: 180px;" src="<?php echo Url::home(true) ?>media/icons/googlestore.png"></a></div></li>
                    </ul>
                </div>
            </div>
            <div class="row mr-0">
                <div class="col-12">
                    <p class="text-center"><small>&copf; 2018 Easyleases Technologies Private Limited</small></p>
                </div>
            </div>
        </footer>


        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script>
                var visitDate = '<?= date('Y-m-d'); ?>';
                var visitTime = '';
                function adjustWidth() {
                    var parentwidth = $(".right-box-parent").width();
                    $(".right-box-child").width(parentwidth);
                }

                adjustWidth();
        </script>
        <script src="<?php echo Yii::$app->userdata->autoVersion(Url::home(true) . 'js/helper.js'); ?>"></script>
        <script src="<?php echo Yii::$app->userdata->autoVersion(Url::home(true) . 'js/detail.js'); ?>"></script>
        <script>
                var map;
                var infowindow;
                var param;

                function regular_map() {
                    var image = '<?php echo Url::home(true); ?>images/icons/home_map.ico';
                    var var_location = new google.maps.LatLng(lat, lon);

                    var var_mapoptions = {
                        center: var_location,
                        zoom: 15
                    };

                    var var_map = new google.maps.Map(document.getElementById("map"),
                            var_mapoptions);

                    var var_marker = new google.maps.Marker({
                        position: var_location,
                        map: var_map,
                        title: propertyName,
                        icon: image
                    });
                }

                function initMap(param) {
                    var image = '<?php echo Url::home(true); ?>images/icons/home_map.ico';
                    var pyrmont = {lat: lat, lng: lon};
                    var distance = 5000;

                    map = new google.maps.Map(document.getElementById('map'), {
                        center: pyrmont,
                        zoom: 14
                    });

                    infowindow = new google.maps.InfoWindow();

                    if (param != undefined && param != '' && param != null) {
                        var paramArr = param.split("|");
                        for (i = 0; i < paramArr.length; i++) {
                            var service = new google.maps.places.PlacesService(map);
                            service.nearbySearch({
                                location: pyrmont,
                                radius: 5000,
                                rankby: distance,
                                sensor: true,
                                type: [paramArr[i]]
                            }, callback);
                        }
                    }

                    var marker = new google.maps.Marker({
                        position: pyrmont,
                        map: map,
                        title: propertyName,
                        icon: image
                    });
                }

                function callback(results, status) {
                    if (status === google.maps.places.PlacesServiceStatus.OK) {
                        for (var i = 0; i < results.length; i++) {
                            createMarker(results[i]);
                        }
                    }
                }

                function createMarker(place) {
                    var placeLoc = place.geometry.location;
                    var marker = new google.maps.Marker({
                        map: map,
                        position: place.geometry.location
                    });

                    google.maps.event.addListener(marker, 'mouseover', function () {
                        infowindow.setContent(place.name);
                        infowindow.open(map, this);
                    });
                }

                function getMapLoc1(params) {
                    initMap(params);
                }
        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAdCMdyhn49XUtPEG8wsv2ILsfzfjV3jK0&amp;libraries=places&amp;callback=initMap" async="" defer=""></script>
    </body>
</html>