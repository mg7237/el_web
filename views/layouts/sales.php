<?php
/* @var $this \yii\web\View */
/* @var $content string */

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
        <link rel="stylesheet" type="text/css" href="http://fontawesome.io/assets/font-awesome/css/font-awesome.css">

        <?php $this->head() ?>
        <link href="<?php echo Url::home(true); ?>css/sales.css" rel="stylesheet">
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
                    '<div style= "color: #26344B;">Logout</div>', ['class' => 'btn btn-link logout', 'style' => 'color:#000;']
            )
            . Html::endForm()
            . '</a>'
            ?>
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
                        ['label' => '<span><i class="fa fa-tachometer" aria-hidden="true"></i></span>Dashboard', 'url' => ['/sales'], 'visible' => Yii::$app->userdata->usertype == 6],
                        ['label' => '<span><i class="fa fa-male" aria-hidden="true"></i></span>My Profile', 'url' => ['/sales/myprofile'], 'visible' => Yii::$app->userdata->usertype == 6],
                        ['label' => '<span><i class="fa fa-male" aria-hidden="true"></i></span>Applicant Leads', 'url' => ['/sales/applicants'], 'visible' => Yii::$app->userdata->usertype == 6],
                        ['label' => '<span><i class="fa fa-male" aria-hidden="true"></i></span>Property Owner Leads', 'url' => ['/sales/owners'], 'visible' => Yii::$app->userdata->usertype == 6],
                        ['label' => '<span><i class="fa fa-male" aria-hidden="true"></i></span>Advisor Leads', 'url' => ['/sales/advisers'], 'visible' => Yii::$app->userdata->usertype == 6],
                        ['label' => '<span><i class="fa fa-male" aria-hidden="true"></i></span>Hot Leads/Follows', 'url' => ['/sales/hotleads'], 'visible' => Yii::$app->userdata->usertype == 6],
                        ['label' => '<span><i class="fa fa-male" aria-hidden="true"></i></span>My Account', 'url' => ['/sales/myaccount'], 'visible' => Yii::$app->userdata->usertype == 6],
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
                    <li><a href="javascritp:;">Terms &amp; Conditions</a></li-->
                    <?php
                    echo Yii::$app->userdata->getFooter();
                    ?>
                </ul>
            </div>
        </div>
    </footer>
    <div class="copyright">&copy; 2018 Easyleases Technologies Private Limited</div>

</div><!-- /.modal -->
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<!-- jQuery -->
<?php $this->endBody() ?>

<!--
    <script src="<?php echo Url::home(true); ?>js/app/jquery.min.js"></script>
-->

<script src="<?php echo Url::home(true); ?>js/app/bootstrap.min.js"></script>
<script src="<?php echo Url::home(true); ?>js/admin.js"></script>
<script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
<!--
         <link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">
-->
<script src="<?php echo Url::home(true); ?>js/sales.js"></script>
<script src="<?php echo Url::home(true); ?>js/jquery.datetimepicker.js"></script>
<script src="<?php echo Url::home(true); ?>js/validate.js"></script>

<script>
            /*$('select#properties-property_type').change(function(){
             var val=$(this).val();
             if(val.trim()==''){
             $('#colive').hide();
             $('#no_of_beds').hide();
             $('#no_of_rooms').hide();
             $('#flat').hide();
             }
             if(val.trim()=='3'){
             $('#flat').show();
             $('#colive').hide();
             $('#no_of_beds').hide();
             $('#no_of_rooms').hide();
             }
             if(val.trim()=='1' || val.trim()=='2'){
             $('#no_of_beds').show();
             $('#no_of_rooms').hide();
             $('#flat').hide();
             $('#colive').hide();
             }
             });
             
             $('#properties-beds_room').keypress(function(e){
             if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57))) {
             e.preventDefault();
             }
             else{
             
             }
             });
             
             
             $('#properties-beds_room').blur(function(){
             // $('#newFields .bedsMain').length();
             // if(length==0){
             // $('#newFields').html('<div class="bedsMain"><input type="text" class="form-control" name="Properties[beds][]"></div>');
             // }
             // else{
             var maxlength=$('#properties-beds_room').val();
             for(var i=1; i<=maxlength; i++){
             $('#newFields').append('<div class="bedsMain"><div class="col-md-3 col-sm-3">Room #'+i+'</div><div class="col-md-9 col-sm-9"><input type="text" class="form-control" placeholder="No Of Beds" name="Properties[beds][]"></div></div>');
             }
             
             // }
             });*/


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
                            //    console.log(data.responseText);
                            alert(data.responseText);
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
                            $('#properties-region').html(data);
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
<script>
            $(function () {


                $(".scheduledDatePicker").datepicker({
                    minDate: 0,
                    changeMonth: true,
                    changeYear: true,
                    yearRange: "-0:+1",
                    numberOfMonths: 1,
                    beforeShowDay: function (date) {

                        if (date.getDay() == 3) {
                            return [false, ''];
                        }
                        return [true, ''];
                    }
                });

// console.log();
                $(".datepicker").datepicker({
                    minDate: 0,
                    changeMonth: true,
                    changeYear: true,
                    yearRange: "-0:+1",
                    numberOfMonths: 1,
//dateFormat:'yy-mm-dd'
                });
                $(".datepicker1").datepicker({
                    minDate: 0,
                    changeMonth: true,
                    changeYear: true,
                    yearRange: "-0:+1",
                    numberOfMonths: 1,
                    beforeShowDay: function (date) {

                        if (date.getDay() == 3) {
                            return [false, ''];
                        }
                        return [true, ''];
                    }
                });

                $("#leadstenant-follow_up_date_time").datepicker({
                    minDate: 0,
                    changeMonth: true,
                    changeYear: true,
                    yearRange: "-0:+1",
                    numberOfMonths: 1
                });


                $("#leadsadvisor-follow_up_date_time").datepicker({
                    minDate: 0,
                    changeMonth: true,
                    changeYear: true,
                    yearRange: "-0:+1",
                    numberOfMonths: 1
                });

                $("#advisoragreements-start_date").datepicker({
                    minDate: 0,
                    changeMonth: true,
                    changeYear: true,
                    yearRange: "-0:+1",
                    numberOfMonths: 1
                });
                $("#advisoragreements-end_date").datepicker({
                    minDate: 0,
                    changeMonth: true,
                    changeYear: true,
                    yearRange: "-0:+1",
                    numberOfMonths: 1
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

    $(document).on('click', '.savechanges_submit_previous', function () {
        $('a[href="' + $(this).attr('data-click') + '"]').click();
    })

    $(document).on('click', '.activate_property', function (e) {
        e.preventDefault();
        var thiselement = $(this);
        $.ajax({
            url: 'activateproperty',
            type: 'POST',
            data: {'val': $('#propertyagreements-property_id').val(), 'status': thiselement.attr('data-status')},
            success: function (data) {
                if (data) {
                    if (thiselement.attr('data-status') == 0) {
                        thiselement.attr('data-status', '1');
                        thiselement.text('Activate Property');
                        thiselement.css('font-size', '22px');
                        thiselement.css('padding', '11px');
                    } else {
                        thiselement.attr('data-status', '0');
                        thiselement.text('Deactivate Property');
                        thiselement.css('font-size', '20px');
                        thiselement.css('padding', '12.5px');
                    }
                } else {
                    if (thiselement.attr('data-status') == 0) {
                        alert('Property Can\'t be Deactivated');
                    } else {
                        alert('Property Can\'t be Activated');
                    }
                }

                location.reload();
            },
            error: function (data) {
                //    console.log(data.responseText);
                alert(data.responseText);
            }
        });
    });

</script>
</body>
</html>
<?php $this->endPage() ?>

