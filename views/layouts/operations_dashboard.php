<?php

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

        <?php $this->head() ?>
        <link href="<?php echo Url::home(true); ?>css/sales.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="http://fontawesome.io/assets/font-awesome/css/font-awesome.css">
        <!-- <script src="<?php echo Url::home(true); ?>js/app/jquery.min.js"></script>
        -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <link href="<?php echo Url::home(true); ?>css/all.css" rel="stylesheet"></head>
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans" />
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
                        ['label' => 'Dashboard', 'url' => ['/operations/']],
                        ['label' => 'My Profile', 'url' => ['/operations/myprofile']],
                        ['label' => 'Applicant Leads', 'url' => ['/operations/applicants']],
                        ['label' => 'Property Owner Leads', 'url' => ['/operations/owners']],
                        ['label' => 'Advisor Leads', 'url' => ['/operations/advisors']],
                        ['label' => 'Tenant Management', 'url' => ['/operations/tenants']],
                        ['label' => 'Property Owner Management', 'url' => ['/operations/owner']],
                        ['label' => 'Property Management', 'url' => ['/operations/properties']],
                        ['label' => 'Advisor Management', 'url' => ['/operations/adviser']],
                        ['label' => 'Property Listing Management', 'url' => ['/operations/propertieslisting']],
                        ['label' => 'Service Request Management', 'url' => ['/operations/servicerequests']],
                        ['label' => 'Request For Approval Of Maintenance Request', 'url' => ['/operations/requestmaintenanceapproval']],
                        ['label' => 'Ad-Hoc Charges For Tenants', 'url' => ['/operations/adhocrequests']],
                        ['label' => 'Wallet Management', 'url' => ['/site/mydashboard']],
                        ['label' => 'Financial Reporting', 'url' => ['/site/mydashboard']]
                    ],
                ]);
                ?>
            </div>

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

</div>
<?php $this->endBody() ?>





<script src="<?php echo Url::home(true); ?>js/app/bootstrap.min.js"></script>
<script src="<?php echo Url::home(true); ?>js/admin.js"></script>
<script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>

<script src="<?php echo Url::home(true); ?>js/sales.js"></script>
<script src="<?php echo Url::home(true); ?>js/jquery.datetimepicker.js"></script>
<script src="<?php echo Url::home(true); ?>js/validate.js"></script>

<script>
            $(document).ready(function () {
                $('#example-table').dataTable();
                $('#datetimepicker1').datetimepicker({
                    datepicker: true,
                    minDate: '-1970/01/1',
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


                $(document).on("click", "#advisorprofile-city", function (e) {
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


                $(document).on("click", "#advisorprofile-state", function (e) {
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
// console.log();
                $(".datepicker").datepicker({
                    minDate: 0,
                    changeMonth: true,
                    changeYear: true,
                    yearRange: "-0:+1",
                    numberOfMonths: 1,
//dateFormat:'yy-mm-dd'
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
                    alert('Property Can\'t be Deactivated');
                }

                location.reload();
            },
            error: function (data) {
                //    console.log(data.responseText);
                alert(data.responseText);
            }
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
</script>
</body>
</html>
<?php $this->endPage() ?>

