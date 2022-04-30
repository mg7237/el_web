<?php
/* @var $this \yii\web\View */
/* @var $content string */

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
        <?php $this->head() ?>
        <!-- CSS Libraries -->
        <link href="<?php echo Url::home(true); ?>css/include.fonts.css" rel="stylesheet">
        <link href="<?php echo Url::home(true); ?>css/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo Url::home(true); ?>/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet">
        <link href="<?php echo Url::home(true); ?>/jquery-ui-1.12.1/jquery-ui.structure.min.css" rel="stylesheet">
        <link href="<?php echo Url::home(true); ?>/jquery-ui-1.12.1/jquery-ui.theme.min.css" rel="stylesheet">
        <link href="<?php echo Url::home(true); ?>css/tenant.css" rel="stylesheet">
        <link href="<?php echo Url::home(true); ?>css/all.css" rel="stylesheet"></head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans" />
    <link rel="stylesheet" type="text/css" href="<?php echo Url::home(true); ?>css/map-icons.css">
    <link rel="stylesheet" href="<?php echo Url::home(true); ?>/css/bootstrap-datetimepicker.min.css" />
    <!-- CSS Libraries -->
    <!-- Javascript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
    <script src="<?php echo Url::home(true); ?>/jquery-ui-1.12.1/jquery-ui.min.js"></script>
    <script type="text/javascript" src="<?php echo Url::home(true); ?>/js/moment.js"></script>
    <script type="text/javascript" src="<?php echo Url::home(true); ?>/js/affix.js"></script>
    <script type="text/javascript" src="<?php echo Url::home(true); ?>/js/collapse.js"></script>
    <script type="text/javascript" src="<?php echo Url::home(true); ?>/js/transition.js"></script>
    <script type="text/javascript" src="<?php echo Url::home(true); ?>/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript" src="<?php echo Url::home(true); ?>js/map-icons.js"></script>
    <script src="<?php echo Url::home(true); ?>js/validate.js"></script>
    <script src="<?php echo Url::home(true); ?>js/app/jquery.easing.min.js"></script>
    <script src="<?php echo Url::home(true); ?>js/jquery.mCustomScrollbar.js"></script>
    <script src="<?php echo Url::home(true); ?>js/app/agency.min.js"></script>
    <script src="<?php echo Url::home(true); ?>js/jquery.bxslider.min.js"></script>
    <script  type="text/javascript" src="<?php echo Url::home(true); ?>js/app/typeahead.bundle.js"></script>
    <!-- Javascript Libraries -->
    <!-- Global site tag (gtag.js) - Google Analytics -->
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
<body id="page-top" class="index">

    <!-- Notification box for Booking and Rent payment response [STARTS HERE] -->
<?php if (!empty(Yii::$app->session->getFlash('noti-success')) || !empty(Yii::$app->session->getFlash('noti-error'))) { ?>
        <div id="pop-up-notification-cover" style="height: 100%; width: 100%; background: black !important; position: fixed; z-index: 9999; opacity: 0.8; top: 0;">&nbsp;</div>
        <div id="pop-up-notification" style="position: fixed; min-height: 100px; z-index: 9999; padding: 26px; background: white;
             border: 2px solid lightgrey; top: 5%; box-shadow: 0px 5px 25px 5px black; <?= (!empty(Yii::$app->session->getFlash('noti-error'))) ? 'min-width: 470px; left: 35%; right: 35%;' : 'min-width: 600px; left: 25%; right: 25%;'; ?>">
            <div class="container" style="width: 100%;">
                <div class="row">
                    <div style="
                         font-weight: bold;
                         background-color: #ECECEC;
                         padding: .75rem 1.25rem;
                         border-radius: .25rem;
                         margin-bottom: 15px;
                         <?php echo (!empty(Yii::$app->session->getFlash('noti-success'))) ? 'color: #155724;' : ''; ?>
                             <?php echo (!empty(Yii::$app->session->getFlash('noti-error'))) ? 'color: #E24C3F;' : ''; ?>
                         " class="col-xs-12 col-sm-12 col-md-12 col-xl-12 text-center" role="">
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
                            <button type="submit" style="width: 100%; font-weight: bold; font-size: 16px;" class="btn btn-md btn-primary cust-btn_mycolor btn_mycolor">
                                Download
                            </button>
        <?php ActiveForm::end(); ?>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 col-xl-6">
                            <button type="button" id="pop-up-notification-close" style="width: 100%; font-weight: bold; font-size: 16px;" class="btn btn-md btn-primary cust-btn_mycolor btn_mycolor">
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
                                <input type="hidden" name="app_redirect_url" value="<?= Yii::$app->session->get('app_redirect_url'); ?>" />
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

    <div class="loaderMain">
        <img src="<?php echo Url::home(true); ?>images/loader/25.gif">
    </div>
<?php $this->beginBody() ?>

    <script type="text/javascript">
        function initialize() {
            var options = {
                componentRestrictions: {country: "IN"}
            };
            //	alert('hello');
            var input = document.getElementById('searchTextField');
            var autocomplete = new google.maps.places.Autocomplete(input, options);
            google.maps.event.addListener(autocomplete, 'place_changed', function () {
                var place = autocomplete.getPlace();
                document.getElementById('properties-lat').value = place.geometry.location.lat();
                document.getElementById('properties-lon').value = place.geometry.location.lng();
            });
        }

        google.maps.event.addDomListener(window, 'load', initialize);

    </script>
    <!--header-->
    <!--nav class="navbar navbar-default searchdetail">
        <div class="container-fluid">
            <div class="navbar-header leftlogo">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo Url::home(true); ?>"><img src="<?php echo Url::home(true); ?>images/property_logo.png" alt=""></a>
            </div>
            <div id="navbar" class="navbar-collapse collapse rightsearch">

                <div class="progression-search-home-container_detail">

                    <form method="get" id="form" class="home-advanced-searchform-property" action="search">


                        <div class="searchleftside searchleftsidenew">
                            <div class="selectdropdownnew">

    <?php
    $city = Yii::$app->getRequest()->getQueryParam('city');

    $cities = \app\models\Cities::find()->all();
    $cityData = ArrayHelper::map($cities, 'id', 'city_name');
    ?>
    <?=
    Html::dropDownList('city', $city, $cityData, [
        'class' => 'form-control',
        'style' => 'font-size: 14px;height:40px',
    ])
    ?>
                            </div>

                            <div class="searchnewbox">
                                <img src="<?php echo Url::home(true); ?>images/location_icon.png" alt=""><input class="search-field-progression typeahead tt-query" name="search" autocomplete="off" spellcheck="false" id="searchTextField" placeholder="Search The Flats By Entering The Location" value="<?php echo Yii::$app->getRequest()->getQueryParam('search'); ?>" style="width: 90%;border:none;" type="text">
                            </div>
                        </div>



                        <div class="selectdropdown newselect">

    <?php
    $p_id = Yii::$app->getRequest()->getQueryParam('property_type');
    $countries = PropertyTypes::find()->all();
    $listData = ArrayHelper::map($countries, 'id', 'property_type_name');
    ?>
    <?=
    Html::dropDownList('property_type[]', $p_id, $listData, [
        'class' => 'form-control',
        'style' => 'font-size: 14px;height:40px',
    ])
    ?>

                        </div>
                        <input type="hidden"  id="properties-lat" value="<?php echo Yii::$app->getRequest()->getQueryParam('lat'); ?>" name="lat" />
                        <input type="hidden"  id="properties-lon" value="<?php echo Yii::$app->getRequest()->getQueryParam('lon'); ?>"  name="lon" />
    <?= Html::hiddenInput('radius', 10); ?>
<?= Html::hiddenInput('bhk', ''); ?>

                        <button  style="width: 14%;margin-left: 0px;" class="btn searchbutnew" type="submit">Search</button>

                    </form>

                </div>
<?php if (Yii::$app->user->isGuest) { ?>
                            <ul class="nav navbar-nav navbar-right login_sign">
                                <li><a href="<?php echo Url::home(true); ?>site/login">Login</a></li>
                                <li><a href="<?php echo Url::home(true); ?>users/register">Sign Up</a></li>
                            </ul>
    <?php } else { ?>
                            <ul class="nav navbar-nav navbar-right login_sign">
        <?php
        echo '<li>'
        . Html::beginForm(['/site/logout'], 'post')
        . Html::submitButton(
                '<div style= "color: #26344B;">Logout</div>', ['class' => 'btn btn-link logout', 'style' => 'color:#000;']
        )
        . Html::endForm()
        . '</li>'
        ?>
                            </ul>

<?php } ?>
            </div>
        </div>
    </nav-->


<?= $content ?>

    <footer>
        <div class="container">

            <div class="footer-links">
                <!--li><a href="javascritp:;">FAQ's</a></li>
                   <li><a href="<?php echo Url::home(true); ?>/site/privacy_policy">Privacy Policy</a></li>
                
             
                 <li><a href="javascritp:;">Site Usage Terms</a></li>
                   <li><a href="javascritp:;">About Us</a></li>
            
             
                 <li><a href="javascritp:;">Contact Us</a></li>
                   <li><a href="javascritp:;">Social Media Links</a></li>
                
                 <li><a href="javascritp:;">Terms & Conditions</a></li-->
                <?php
                echo Yii::$app->userdata->getFooter();
                ?>
            </div>

        </div>

    </footer>
    <!--copyright-->
    <div class="copyright">© 2018 Easyleases Technologies Private Limited</div>

    <script>
        $(document).ready(function () {

            $('[data-toggle="popover"]').popover({

                placement: 'top',

                trigger: 'hover'

            });
        });
    </script>
    <script>
        $(document).ready(function () {
            var today = new Date();
            var day = today.getDate() + 4;
            var month = today.getMonth() + 1;
            var year = today.getYear() - 100;
            $("#propertyvisits-visit_date").datetimepicker({
                startDate: new Date(),
                endDate: "'" + day + "-" + month + "-" + year + "'",
                format: 'dd-M-yy',
                autoclose: true,
                minView: 2,
            });
        });
    </script>
    <script>
        $("ul.optionkm li").click(function () {
            // longer method using .attr()
            console.log($(this).attr("id"));
            $('#radius').val($(this).attr("id"));
        });

    </script>
    <script>
        function toggleIcon(e) {
            $(e.target)
                    .prev('.panel-heading')
                    .find(".more-less")
                    .toggleClass('glyphicon-plus glyphicon-minus');
        }
        $('.panel-group').on('hidden.bs.collapse', toggleIcon);
        $('.panel-group').on('shown.bs.collapse', toggleIcon);
    </script>

    <script>

        $(document).on('click', '.btn-select', function (e) {
            e.preventDefault();
            var ul = $(this).find("ul");
            if ($(this).hasClass("active")) {
                if (ul.find("li").is(e.target)) {
                    var target = $(e.target);
                    target.addClass("selected").siblings().removeClass("selected");
                    var value = target.html();
                    var id = target.attr("id");

                    $(this).find(".btn-select-input").val(id);
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
    </script>

    <script>
        (function ($) {
            $(window).on("load", function (e) {
                $("#content-6,#content-7").mCustomScrollbar({
                    axis: "x",
                    theme: "light-3",
                    advanced: {autoExpandHorizontalScroll: true}
                });
            });
        })(jQuery);
        function getMapLoc(params) {

            $('#currentType').val(params);
            google.maps.event.addDomListener(this, 'click', function () {
                initMap(params);
            });

            //	$('#map').load(' #map');
        }

    </script>

    <script>

        $(document).ready(function () {
            // var csrfToken = $('meta[name="csrf-token"]').attr("content");
            $(document).on("click", ".wishlist", function (e) {
                e.preventDefault();
                startLoader();
                var thiselement = $(this);
                $.ajax({
                    url: '<?= \Yii::$app->getUrlManager()->createUrl('site/addfav') ?>',
                    type: 'POST',
                    data: {status: $(this).attr('data-id'), property: $(this).attr('data-property')},
                    success: function (data) {
                        hideLoader();
                        if (data == 2) {

                            alert('Please login to add to wishlist');
                            setCookie('operation', 'site/addfavajax?status=' + thiselement.attr('data-id') + '&property=' + thiselement.attr('data-property'));
                            setCookie('load_url', window.location.href);
                            window.location = '<?php echo Url::home(true); ?>site/login';

                        } else {

                            $('.wishlist1').load(' .wishlist');
                        }
                    },
                    error: function (data) {
                        //    console.log(data.responseText);
                        hideLoader();
                        // alert(data.responseText);
                        alert('Failure');
                    }
                });
            });
        });
        $('.bxslider').bxSlider({
            minSlides: 0,
            maxSlides: 6,
            slideWidth: 200,
            slideMargin: 0,
            infiniteLoop: false
        });
        $(document).ready(function () {
            var csrfToken = $('meta[name="csrf-token"]').attr("content");
            $(document).on("click", ".wishlistsearch", function (e) {
                startLoader();
                e.preventDefault();
                $.ajax({
                    url: '<?= \Yii::$app->getUrlManager()->createUrl('site/addfav') ?>',
                    type: 'POST',
                    data: {status: $(this).attr('data-id'), property: $(this).attr('data-property'), _csrf: csrfToken},
                    success: function (data) {
                        hideLoader();
                        if (data == 2) {
                            alert('Please login to add to wishlist');
                        } else {
                            alert(data);
                            $('.dilspace').load(' .dilspace');

                        }
                    },
                    error: function (data) {
                        //    console.log(data.responseText);
                        hideLoader();
                        alert('Failure');
                        // alert(data.responseText);
                    }
                });
            });
        });


    </script>
<?php $this->endBody() ?>
    <style>

    </style>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAdCMdyhn49XUtPEG8wsv2ILsfzfjV3jK0&libraries=places&callback=initMap" async defer></script>
</body>
<script>
        var position_array = [];
        var positions = [];
        var changed = 0;
        var id = 0;
        var keyy = 0;
        $(document).ready(function () {
            var i = 0;
            $('#pageTabs ul li').each(function () {
                var href = $(this).children('a').attr('href');
                href = href.replace('#', '');
                var offset = $('#' + href).offset();
                var top = offset.top;
                if (i == 4) {
                    positions[i] = top - 200;
                } else {
                    positions[i] = top - 120;
                }

                position_array[i] = href;
                i++;

            })

            // console.log($(window).scrollTop()+','+positions[0]);
            if ($(window).scrollTop() >= positions[0] + 50) {
                $('.top_custom_menu').show();
            }

            $(window).scroll(function () {
                var j;
                if ($(window).scrollTop() >= positions[0] + 50) {
                    $('.top_custom_menu').slideDown("slow");
                } else {
                    $('.top_custom_menu').slideUp("slow");
                }
                $(positions).each(function (key, value) {
                    // if(id!=)
                    if ($(window).scrollTop() >= 0 && $(window).scrollTop() < positions[0]) {
                        $('.tabmenu ul li').removeClass('active');
                        $('.tabmenu ul li a[href="#' + position_array[0] + '"]').closest('li').addClass('active');
                        // console.log(position_array[0]);
                        var keyy = 0;
                    } else if ($(window).scrollTop() >= positions[1] && $(window).scrollTop() < positions[2]) {
                        $('.tabmenu ul li').removeClass('active');
                        $('.tabmenu ul li a[href="#' + position_array[1] + '"]').closest('li').addClass('active');
                        // console.log(position_array[1]);
                        var keyy = 1;
                    } else if ($(window).scrollTop() >= positions[2] && $(window).scrollTop() < positions[3]) {
                        $('.tabmenu ul li').removeClass('active');
                        $('.tabmenu ul li a[href="#' + position_array[2] + '"]').closest('li').addClass('active');
                        // console.log(position_array[2]);
                        var keyy = 2;
                    } else if ($(window).scrollTop() >= positions[3] && $(window).scrollTop() < positions[4]) {
                        $('.tabmenu ul li').removeClass('active');
                        $('.tabmenu ul li a[href="#' + position_array[3] + '"]').closest('li').addClass('active');
                        // console.log(position_array[3]);
                        var keyy = 3;
                    } else {
                        if ($(window).scrollTop() >= positions[4]) {
                            $('.tabmenu ul li').removeClass('active');
                            $('.tabmenu ul li a[href="#' + position_array[4] + '"]').closest('li').addClass('active');
                            var keyy = 4;
                        } else {
                            $('.tabmenu ul li').removeClass('active');
                            $('.tabmenu ul li a[href="#' + position_array[0] + '"]').closest('li').addClass('active');
                            var keyy = 1;
                        }

                    }

                });
                console.log(keyy);
                // console.log($(window).scrollTop());
            })
        })
</script>
</html>
<?php $this->endPage() ?>

<style>
    @media screen and (max-width: 768px){
        #pop-up-notification{
            min-width: 0 !important;
            left: 15px !important;
            right: 45px !important;
        }
    }
    @media screen and (max-width: 320px){
        .cust-btn_mycolor{
            font-size: 12px !important;
            padding: 10px !important;

        }
    }
    #savings_table tr td:first-child {
        width: 88%;
    }
    #savings_table tbody tr:first-child {
        font-weight: 600;
    }
</style>
<script>
    $(document).ready(function () {
        $('#pop-up-notification-close').click(function () {
            document.getElementById("pop-up-notification-cover").style.display = "none";
            document.getElementById("pop-up-notification").style.display = "none";
        });
    });
</script>