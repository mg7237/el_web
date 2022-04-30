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
        <link href="<?php echo Url::home(true); ?>css/all.css" rel="stylesheet">
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
        <link href="<?php echo Url::home(true); ?>css/sales.css" rel="stylesheet">
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
    <?php if (Yii::$app->userdata->usertype == 9) { ?>
                                        <li class="<?= (Yii::$app->request->pathInfo == 'investor/index') ? 'active' : ''; ?>">
                                            <a href="<?= Url::home(true) ?>investor/index">Dashboard</a>
                                        </li>
                                    <?php } ?>

    <?php if (Yii::$app->userdata->usertype == 9) { ?>
                                        <li class="<?= (Yii::$app->request->pathInfo == 'investor/incomestatements') ? 'active' : ''; ?>">
                                            <a href="<?= Url::home(true) ?>investor/incomestatements">Income Statements</a>
                                        </li>
                                    <?php } ?>

    <?php if (Yii::$app->userdata->usertype == 9) { ?>
                                        <li class="<?= (Yii::$app->request->pathInfo == 'investor/expensestatements') ? 'active' : ''; ?>">
                                            <a href="<?= Url::home(true) ?>investor/expensestatements">Expense Statements</a>
                                        </li>
                                    <?php } ?>

    <?php if (Yii::$app->userdata->usertype == 9) { ?>
                                        <li class="<?= (Yii::$app->request->pathInfo == 'investor/calculatepnl') ? 'active' : ''; ?>">
                                            <a href="<?= Url::home(true) ?>investor/calculatepnl">Calculate P&L</a>
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
                            $('#properties-branch_code').html(data);
                        },
                        error: function (data) {
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
                    }
                })
            }

            $(document).on('click', '#form_property_listing', function (e) {
                e.preventDefault();
                second_submit = 0;
                formPropertyListing(1);
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
        </script>
    </body>
</html>
<?php $this->endPage() ?>
<script>
    $(document).ready(function () {
        $('#sidebarCollapse').on('click', function () {
            $('#left-panel').toggleClass('toggle-side-nav');
            $('#page-wrapper').toggleClass('toggle-page-wrapper');
        });

        //$('#sidebarCollapse').click();

        $("#search-property-expense").autocomplete({
            source: "<?= Url::home(true) ?>external/getpropertysearch",
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
            }
        });

        $('#generate-report').off().on('click', function () {
            var formData = new FormData($('#expense-reporting-form')[0]);
            $.ajax({
                url: 'getexpensereporting',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (data) {
                    if (data != '') {
                        $('.expense-reporting-list').html(data);
                        $('#download-expense-report').show();
                    } else {
                        $('#download-expense-report').hide();
                    }
                },
                error: function () {
                    alert('Something went wrong, please try after some time!');
                }
            })
        });

        $('#expense-statement-type').change(function () {
            if (this.value == 1) {
                $('#expense-statement-from-monthly').show();
                $('#expense-statement-from-quarterly').hide();
                $('#expense-statement-from-fy').hide();
            } else if (this.value == 2) {
                $('#expense-statement-from-monthly').hide();
                $('#expense-statement-from-quarterly').show();
                $('#expense-statement-from-fy').hide();
            } else if (this.value == 3) {
                $('#expense-statement-from-monthly').hide();
                $('#expense-statement-from-quarterly').hide();
                $('#expense-statement-from-fy').show();
            }
        });

        $('#download-expense-report').off().on('click', function () {
            var formData = new FormData();
            var htmlData = $('.expense-reporting-list').html();
            formData.append('htmldata', htmlData);
            $.ajax({
                url: 'exportexpensereport',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (data) {
                    if (data != '') {
                        window.location.href = 'downloadexpensereport?d=' + data;
                    }
                },
                error: function () {
                    alert('Something went wrong, please try after some time!');
                }
            });
        });
    });
</script>