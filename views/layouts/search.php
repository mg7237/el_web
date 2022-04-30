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
        <link href="<?php echo Yii::$app->userdata->autoVersion(Url::home(true) . 'css/tenant.css'); ?>" rel="stylesheet">
        <link href="<?php echo Yii::$app->userdata->autoVersion(Url::home(true) . 'css/all.css'); ?>" rel="stylesheet"></head>
    <link rel="stylesheet" type="text/css" href="http://fontawesome.io/assets/font-awesome/css/font-awesome.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="<?php echo Url::home(true); ?>js/validate.js"></script>
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans" />
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
<?php $this->beginBody() ?>


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
                                <img src="<?php echo Url::home(true); ?>images/location_icon.png" alt=""><input class="search-field-progression typeahead tt-query" name="search" autocomplete="off" spellcheck="false" id="searchTextField" placeholder="Search The Flats By Entering The Location" value="" style="width: 90%;border:none;" type="text">
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
    <?php
    if (isset($_GET['min_amount'])) {
        $minAm = (int) $_GET['min_amount'];
    } else {
        $minAm = 200;
    }



    if (isset($_GET['max_amount'])) {
        $maxAm = (int) $_GET['max_amount'];
    } else {
        $maxAm = 500;
    }

    if (isset($_GET['min_area'])) {
        $minAr = (int) $_GET['min_area'];
    } else {
        $minAr = 200;
    }

    if (isset($_GET['max_area'])) {
        $maxAr = (int) $_GET['max_area'];
    } else {
        $maxAr = 200;
    }
    if (isset($_GET['min_radius'])) {
        $minRa = (int) $_GET['min_radius'];
    } else {
        $minRa = 0;
    }

    if (isset($_GET['max_radius'])) {
        $maxRa = (int) $_GET['max_radius'];
    } else {
        $maxRa = 0;
    }
    ?>


    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?php echo Url::home(true); ?>js/app/bootstrap.min.js"></script>
    <!-- Plugin JavaScript -->
    <script src="<?php echo Url::home(true); ?>js/app/jquery.easing.min.js"></script>
    <!-- Theme JavaScript -->
    <script src="<?php echo Url::home(true); ?>js/app/agency.min.js"></script>
    <!--dorpdown js-->
    <script  type="text/javascript" src="<?php echo Url::home(true); ?>js/app/typeahead.bundle.js"></script>
    <!--<script  type="text/javascript" src="<?php echo Url::home(true); ?>js/app/jquery.ui.js"></script>-->
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
    <script>$('#widget').draggable();</script>
    <script>
        $("ul.optionkm li").click(function () {
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
        $(document).ready(function () {
            $(function () {
                $("#slider-range").slider({
                    range: true,
                    min: 3000,
                    max: 100000,
                    values: [<?php echo $minAm ?>, <?php echo $maxAm ?>],
                    slide: function (event, ui) {
                        console.log(ui.handle.attributes);
                        $("#amount").html("Price Range : $" + ui.values[ 0 ] + " - $" + ui.values[ 1 ]);
                        $("#min_amount").val(ui.values[ 0 ]);
                        $("#max_amount").val(ui.values[ 1 ]);
                        $("#amout11").text(ui.values[ 0 ]);
                        $("#amout22").text(ui.values[ 1 ]);

                    }

                });
                /* $( "#amount" ).html( "Price Range : $" + $( "#slider-range" ).slider( "values", 0 ) +
                 " - $" + $( "#slider-range" ).slider( "values", 1 ) );*/

            });
            $("#slider-range").on("slidestop", function (event, ui) {

                if (document.getElementById("priceval")) {
                    var slider_html = '<div class="tag filteroption"><span class="tag-label">Price : ' + ui.values[ 0 ] + ' - ' + ui.values[ 1 ] + '</span><span class="tag-close" onclick="resetSlider();"> × </span></div>';
                    $("#priceval").html(slider_html);
                } else {
                    var sliderhtml = '<div class="inline-block" id="priceval"><div class="tag filteroption"><span class="tag-label">Price : ' + ui.values[ 0 ] + ' - ' + ui.values[ 1 ] + '</span><span class="tag-close" onclick="resetSlider();"> × </span></div></div>';
                    var prehtml = $(".filters").html();
                    var finalhtml = prehtml + sliderhtml;
                    $(".filters").html(finalhtml);
                }
            });


            $(function () {
                $("#slider-range2").slider({
                    range: true,
                    min: 200,
                    max: 50000,
                    values: [<?php echo $minAr ?>, <?php echo $maxAr ?>],
                    slide: function (event, ui) {
                        $("#amount").html("Square feet area : Sqft" + ui.values[ 0 ] + " - Sqft" + ui.values[ 1 ]);
                        $("#min_area").val(ui.values[ 0 ]);
                        $("#max_area").val(ui.values[ 1 ]);
                        $("#amout13").text(ui.values[ 0 ]);
                        $("#amout24").text(ui.values[ 1 ]);
                    }
                });
                /*  $( "#amount" ).html( "Square feet area : $" + $( "#slider-range2" ).slider( "values", 0 ) +
                 " - $" + $( "#slider-range2" ).slider( "values", 1 ) );
                 */

            });
            $("#slider-range2").on("slidestop", function (event, ui) {
                if (document.getElementById("areaval")) {
                    var slider_html = '<div class="tag filteroption" ng-if="!search.isArray(value)" ng-class=""><span class="tag-label">Area : ' + ui.values[ 0 ] + ' - ' + ui.values[ 1 ] + '</span><span class="tag-close" onclick="resetSlider2();"> × </span></div>';
                    $("#areaval").html(slider_html);
                } else {
                    var sliderhtml = '<div class="inline-block" id="areaval"><div class="tag filteroption" ng-if="!search.isArray(value)" ng-class=""><span class="tag-label">Area : ' + ui.values[ 0 ] + ' - ' + ui.values[ 1 ] + '</span><span class="tag-close" onclick="resetSlider2();"> × </span></div></div>';
                    var prehtml = $(".filters").html();
                    var finalhtml = prehtml + sliderhtml;
                    $(".filters").html(finalhtml);
                }
            });
            $(function () {
                $("#slider-range1").slider({
                    range: true,
                    min: 0,
                    max: 10,
                    values: [<?php echo $minRa ?>, <?php echo $maxRa ?>],
                    slide: function (event, ui) {
                        $("#amount").html("Radius : Kms" + ui.values[ 0 ] + " - Kms" + ui.values[ 1 ]);
                        $("#min_radius").val(ui.values[ 0 ]);
                        $("#max_radius").val(ui.values[ 1 ]);
                        $("#amout12").text(ui.values[ 0 ]);
                        $("#amout23").text(ui.values[ 1 ]);
                    }
                });
                /*  $( "#amount" ).html( "Square feet area : $" + $( "#slider-range2" ).slider( "values", 0 ) +
                 " - $" + $( "#slider-range2" ).slider( "values", 1 ) );
                 */

            });




        });

        $("#slider-range1").on("slidestop", function (event, ui) {
            if (document.getElementById("radiusval")) {
                var slider_html = '<div class="tag filteroption" ng-if="!search.isArray(value)" ng-class=""><span class="tag-label">Radius : ' + ui.values[ 0 ] + ' - ' + ui.values[ 1 ] + '</span><span class="tag-close" onclick="resetSlider1();"> × </span></div>';
                $("#radiusval").html(slider_html);
            } else {
                var sliderhtml = '<div class="inline-block" id="radiusval"><div class="tag filteroption" ng-if="!search.isArray(value)" ng-class=""><span class="tag-label">Radius : ' + ui.values[ 0 ] + ' - ' + ui.values[ 1 ] + '</span><span class="tag-close" onclick="resetSlider1();"> × </span></div></div>';
                var prehtml = $(".filters").html();
                var finalhtml = prehtml + sliderhtml;
                $(".filters").html(finalhtml);
            }

        });

        function resetSlider()
        {
            var $slider = $("#slider-range");
            $slider.slider("values", 0, <?php echo $minAm ?>);
            $slider.slider("values", 1, <?php echo $minAm ?>);
            $("#min_amount").val('');
            $("#max_amount").val('');
            $("#priceval").remove();
        }

        function resetSlider2()
        {
            var $slider = $("#slider-range2");
            $slider.slider("values", 0, <?php echo $minAr ?>);
            $slider.slider("values", 1, <?php echo $minAr ?>);
            $("#min_area").val('');
            $("#max_area").val('');
            $("#areaval").remove();
        }
        function resetSlider1()
        {
            var $slider = $("#slider-range1");
            $slider.slider("values", 0, <?php echo $minRa ?>);
            $slider.slider("values", 1, <?php echo $maxRa ?>);
            $("#min_radius").val('');
            $("#max_radius").val('');
            $("#radiusval").remove();
        }
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
    <!--scrolljs-->
    <script src="<?php echo Url::home(true); ?>js/app/jquery.mCustomScrollbar.concat.min.js"></script>
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

            google.maps.event.addDomListener(this, 'click', function () {

                initMap(params);
            });

            //	$('#map').load(' #map');
        }

    </script>

    <script>
        $(document).ready(function () {

            var csrfToken = $('meta[name="csrf-token"]').attr("content");
            $(document).on("click", ".wishlist", function (e) {
                startLoader();
                var element = $(this);
                e.preventDefault();
                $.ajax({
                    url: '<?= \Yii::$app->getUrlManager()->createUrl('site/addfav') ?>',
                    type: 'POST',
                    data: {status: $(this).attr('data-id'), property: $(this).attr('data-property'), _csrf: csrfToken},
                    success: function (data) {
                        hideLoader();
                        if (data == 2) {

                            setCookie('operation', 'site/addfavajax?status=' + thiselement.attr('data-id') + '&property=' + thiselement.attr('data-property'));
                            setCookie('load_url', window.location.href);
                            alert('Please login to add to wishlist');
                            window.location = '<?php echo Url::home(true); ?>site/login';
                        } else {

                            $('.wishlist').load('.wishlist');
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

        $(document).ready(function () {
            startLoader();
            var csrfToken = $('meta[name="csrf-token"]').attr("content");
            $(document).on("click", ".wishlistsearch", function (e) {
                var thiselement = $(this);
                e.preventDefault();
                $.ajax({
                    url: '<?= \Yii::$app->getUrlManager()->createUrl('site/addfav') ?>',
                    type: 'POST',
                    data: {status: $(this).attr('data-id'), property: $(this).attr('data-property'), _csrf: csrfToken},
                    success: function (data) {
                        hideLoader();
                        if (data == 2) {
                            setCookie('operation', 'site/addfavajax?status=' + thiselement.attr('data-id') + '&property=' + thiselement.attr('data-property'));
                            setCookie('load_url', window.location.href);
                            alert('Please login to add to wishlist');
                            window.location = '<?php echo Url::home(true); ?>site/login';
                        } else {

                            if (thiselement.attr('data-id') == 0) {
                                thiselement.attr('data-id', '1');
                                thiselement.attr('title', 'Click to add in Favourite');
                                thiselement.children('img').attr('src', '<?php echo Url::home(true); ?>images/dil_img.png');
                            } else {
                                thiselement.attr('data-id', '0');
                                thiselement.attr('title', 'Click to delete from Favourite');
                                thiselement.children('img').attr('src', '<?php echo Url::home(true); ?>images/dil_shape.png');
                            }
                        }
                    },
                    error: function (data) {
                        //    console.log(data.responseText);
                        hideLoader();
                        aler('Failure');
                        // alert(data.responseText);
                    }
                });
            });
        });
        $(document).ready(function () {

            $('.changeorder').on('change', function () {
                val = this.value;
                var url = document.location.href + "&sort_flat=" + val;

                document.location = url;
                // $(this).closest('form').submit();
            });
        });


        $(document).on('click', '.open_facility', function () {
            $('.property_facilities_val').toggle();
            $('.property_complex_val').hide();
        })

        $(document).on('click', '.open_complex', function () {
            $('.property_complex_val').toggle();
            $('.property_facilities_val').hide();
        })
    </script> 
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
exit;
?>

