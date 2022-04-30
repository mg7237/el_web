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
        <link href="<?php echo Url::home(true); ?>css/applicant.css" rel="stylesheet"></head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
    <link href="<?php echo Url::home(true); ?>css/all.css" rel="stylesheet"></head>
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
<body>
<?php $this->beginBody() ?>

    <nav id="mainNav" class="navbar navbar-default navbar-fixed-top affix-top">
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
                . '</a>';
                ?>

            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>

    <div class="signup_conatiner">
        <div tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="">
                    <div class="modal-header loginheader">
                        <h4 class="modal-title modal_signin signuptext" id="myModalLabel">Book now</h4>
                    </div>

<?= $content ?>


                </div>
            </div>
        </div>
    </div>

<?php $this->endBody() ?>
    <script src="<?php echo Url::home(true); ?>js/app/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>

    <script src="<?php echo Url::home(true); ?>js/validate.js"></script>
    <script>
            $(document).ready(function () {
                removeCookie('redirect');
            })
            $(function () {
                var dateFormat = 'dd-M-yy';
                from = $("#from")
                        .datepicker({
                            dateFormat: dateFormat,
                            minDate: new Date(),
                            changeMonth: true,
                            changeYear: true,
                            yearRange: "-0:+1",
                            numberOfMonths: 1
                        })
                        .on("change", function () {
                            to.datepicker("option", "minDate", getDate(this));
                        }),
                        to = $("#to").datepicker({
                    defaultDate: "+1m",
                    dateFormat: dateFormat,
                    changeMonth: true,
                    changeYear: true,
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
                    dateFormat: dateFormat,
                    changeYear: true,
                    yearRange: "-0:+1",
                    numberOfMonths: 1,
                    maxDate: "+1w",
                    // currentDate: new Date().getDate(),
                    beforeShowDay: function (date) {

                        // if (date.getDay() == 3 ) {
                        // return [false, ''];
                        // }
                        return [true, ''];
                    }
                });
                var currentdate = new Date();
                if (currentdate.getDay() != 3) {
                    $("#visit_date").datepicker("setDate", currentdate);
                } else {
                    currentdate.setDate(currentdate.getDate() + 1);
                    $("#visit_date").datepicker("setDate", currentdate);
                }
            });
            function datasubmit() {
                // var csrfToken = $('meta[name="csrf-token"]').attr("content");  

                // Get the Login Name value and trim it
                //var min_stay = $.trim($('#propertyagreements-min_contract_peroid').val());
                //var move_in = $.trim($('#from').val());
                //var exit_date = $.trim($('#to').val());
                //var availability_from = $.trim($('#properties-availability_from').val());

                // $.ajax({
                //    url: '<?//= \Yii::$app->getUrlManager()->createUrl('site/datediff') ?>',
                //    type: 'POST',
                //    data: {_csrf : csrfToken},
                //    success: function(data) {

                // 		 if( data == 'yes' ){
                $("#payment").submit();

                // } else if(data =='no') {

                //  alert('Minimum stay is '+min_stay+' months');
                //  return false 
                // } else {
                //  alert('Please change move in date , Availability is from '+availability_from);
                //  return false 

                // }
                //    },
                //    error: function(data) {
                // 	//    console.log(data.responseText);
                // 		alert(data);
                //    }
                // });
            }




    </script>

</body>
</html>
<?php $this->endPage() ?>
