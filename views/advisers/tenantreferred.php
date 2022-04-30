<?php
$this->title = 'PMS- Tenant Referred';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\grid\GridView;

/* echo "<pre>";
  print_r($model);
  die; */
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<link rel="stylesheet" href="<?php echo Yii::$app->userdata->autoVersion(Url::home(true) . 'css/css/newstyle_admin/mystyle.css'); ?>">
<style>
    #content{
        width: 100%;
    }
    @media screen and (max-width: 320px){
        .show-mobile {
            margin-left: -6px !important;
        }
    }

    @media screen and (max-width: 767px){
        .card-bacolor{
            width: 100%;
        }

        .crd-style{
            color: white;
            border-bottom: 1px solid white;
        }
        .Tenanttab{
            padding-right: 0;
        }
        .savebutton{
            width: 100% !important;
        }
        .cust-lastpad{
            padding-right: 0;
        }
        .cust-mainpad{
            padding-left: 0;
        }

    }

    @media only screen and (min-width:768px) and (max-width:1023px){
        .savebutton{
            width: 100% !important;
        }
    }

    .table_td_style{
        width: 20%;
        font-size: 12px;
        font-weight: 600;
    } 
    .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
        padding: 8px;
        line-height: 1.42857143;
        vertical-align: top;
        border-top: 1px solid #fff !important;
        font-size: 12px;
    } 
    .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
        border: inherit;
    }
    .tbl_style{
        width: 100%;
        /*margin-left: 20px;*/
        border: 2px solid #ddd !important;
        box-shadow: 0px 2px 2px black;
        -moz-box-shadow: 0px 2px 2px black,;
        -webkit-box-shadow: 1px 1px 1px 1px #ddd;
    }
    .container
    {
        width:100%;
    }
    .dclass
    {
        background-color: #fafafa !important;
    }

    .feed-details-tbl tr:nth-child(even){ background-color:#fff !important;}
    .feed-details-tbl tr:nth-child(odd){background-color:#fafafa !important; }
    .feed-details-tbl thead tr:nth-child(odd){ background-color:#fff !important;}
    .feed-details-tbl
    {
        border: 1px solid #dddddd !important;
    }
    .dclass
    {
        background-color: transparent;
    }
    table {
        /*margin: 15px 0;
        border: 1px solid black;
        table-layout: auto;
        width: 100%;*/ /* must have this set */
        font-family: arial, sans-serif;
        border-collapse: inherit;
        width: 100%;

        table-layout: fixed;
    }
    td, th {
        /* border: 1px solid #dddddd; */
        text-align: left;
        padding-left: 30px;
        height:64px;
        word-wrap: break-word;
    }
    .bgstyle {
        background-color: #fafafa !important;
        background-color: var(--white);
    }

    .box+.box {
        margin-top: 2.5rem;
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

    .well {
        min-height: 236px;
        padding: 0px !important;
        margin-bottom: 20px;
        background-color: #f5f5f5;
        border: 1px solid #e3e3e3;
        border-radius: 4px;
        -webkit-box-shadow: inset 0 1px 1px #e9e9e9;
        box-shadow: inset 0 1px 1px #e9e9e9;
        width: 617px;
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
    .selectyear
    {
        height:40px;
    }
    .form-control
    {
        height:40px;
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
    .buttonSubmit,.buttonSubmit:hover
    {

        width: 39%;

        width: 140px;
        height: 40px;
        border-radius: 3px;
        font-size: 13px;
        font-weight: bold;
        font-style: normal;
        font-stretch: normal;
    }
    select.form-control,input.form-control
    {
        /* border: 1px solid #ccc;
        background-color:white; */
        padding-left:15px;
    }
    .feed-details-tbl tr th
    {			

        font-size: 12px;
        font-weight: 600;
    }
    .feed-details-tbl tr td
    {
        font-size: 12px;

    }
    .col-md-3,.col-md-7
    {
        padding-right: 0px;
    }

</style>
<div id="content">
    <button type="button" id="sidebarCollapse" class="btn btn-info navbar-btn">
        <i class="glyphicon glyphicon-align-left"></i>
        <span></span>
    </button>
    <div class="container" style="margin-top: -42px;">
        <div class="col-md-6">
            <h4>MY REFERRED TENANTS</h4>
        </div>
        <!-- <div class="col-md-6">
            <button id="first_button" class="btn btn-info btn-lg pull-right">Edit</button>
        </div -->
    </div>
    <hr>



    <div class="form-group col-md-12 cust-mainpad">

        <div class="col-md-3">
            <input type="hidden" class="btn-select-input" id="" name="" value="" />
            <select class="form-control selectyear">
                <option value="" class="" selected="selected">Sort By</option>
                <!--option value="users.id">Tenant Code</option-->
                <option value="users.full_name">Tenant name</option>
                <option value="users.login_id">Tenant email</option>
                <option value="tenant_profile.phone">Tenant phone number</option>
                <option value="tenant_agreements.lease_start_date">Lease Start Date</option>
                <option value="tenant_agreements.lease_end_date">Lease End Date</option>
            </select>
        </div>

        <div class="col-md-7">
            <!--input class="selectyear1" type="text" placeholder="Type here" -->
            <form class="" role="search" method="GET">
                <div>
                    <input type="hidden" name="sort" id="sort_val" value="<?= (isset($_GET['sort'])) ? $_GET['sort'] : ''; ?>">
                    <input type="text" class="form-control" placeholder="Type here" name="search" value="<?= (isset($_GET['search'])) ? $_GET['search'] : '' ?>">

                </div>

        </div>

        <div class="col-md-2 cust-lastpad">

            <button type="submit" class="btn btn-primary btn-lg savebutton buttonSubmit">Search</button> <!--span class="glyphicon glyphicon-search"></span--> <!--span class="label-icon"></span--> </button>

            <!--button type="button"  class="btn btn-primary">Search</button--> </div>


    </div>

    <?php //echo "<pre>";print_r($model);echo "</pre>";die;?>

    <div class="col-md-12 Tenanttab" style="">

        <!--table class="table tenant-tbl">
            <thead>
                <tr>
                    <th>Tenant code</th>
                    <th>Name</th>
                    <th>Owner Contact</th>
                    <th>Agreement Start Date</th>
                    <th>Agreement End Date</th>
                    
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>6</td>
                    <td>Melvin Hodges</td>
                    <td>9876543210</br>devin@gamil.com</td>
                    <td>21-Mar-2018</td>
                    <td>31-Mar-2018</td>
                   
                </tr>
            </tbody>
        </table-->


        <div class="col-md-12 Tenanttab1">

            <div class="row">
                <table class="table table-bordered tbl_style hide-mobile" frame="box">
                    <thead>
                        <tr>
                            <!--th>Property code</th-->
                            <th>Tenant Name</th>
                            <!--th>Name</th-->
                            <th>Tenant Contact</th>
                            <th>Property Name</th>
                            <th>Property Address</th>
                            <th>Agreement From Date</th>
                            <th>Agreement To Date</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        //echo "<pre>";print_r($model);echo "</pre>";die;
                        $i = 1;
                        foreach ($model as $key => $value) {
                            if ($i % 2 == 0) {
                                $dclass = "";
                            } else {
                                $dclass = "dclass";
                            }
                            ?>
                            <tr class="<?php echo $dclass; ?>">
                                <!--td><?php //echo $value['id']   ?></td-->
                                <td><?= $value['full_name'] ?></td>
                                <td><?= $value['phone'] ?><br/><?= $value['login_id'] ?></td>
                                <td><?= $value['property_name'] ?></td>
                                <td><?= $value['address_line_1'] ?>,<?= $value['pincode'] ?>,<?= $value['city_name'] ?>,<?= $value['state_name'] ?></td>
                                <td><?= $value['lease_start_date'] ?></td>
                                <td><?= $value['lease_end_date'] ?></td>

                            </tr>

                            <?php
                            $i++;
                        }
                        ?>


                    </tbody>
                </table>

                <!-- mobile view -->
                <section class="show-mobile mar1">
                    <div class="continer">
                        <?php
                        //echo "<pre>";print_r($model);echo "</pre>";die;
                        $i = 1;
                        foreach ($model as $key => $value) {
                            if ($i % 2 == 0) {
                                $dclass = "";
                            } else {
                                $dclass = "dclass";
                            }
                            ?>
                            <div class="row card-bacolor">
                                <div class="cust-card">
                                    <form>
                                        <div class="form-row cust-form">
                                            <div class="form-group cust-frmgrp col-md-12">
                                                <label for="inputEmail4">Tenant Name : </label>
                                                <p class="crd-style"><?= $value['full_name'] ?></p>
                                                <!-- <input type="text" class="form-control cust-formcontrol" id="inputEmail4" placeholder="<?= $value['full_name'] ?>"> -->
                                            </div>
                                            <div class="form-group cust-frmgrp col-md-12">
                                                <label for="inputPassword4">Tenant Contact	 : </label>
                                                <p class="crd-style"><?= $value['phone'] ?>/<?= $value['login_id'] ?></p>
                                                <!-- <input type="text" class="form-control cust-formcontrol" id="inputPassword4" placeholder="<?= $value['phone'] ?>/<?= $value['login_id'] ?>"> -->
                                            </div>
                                            <div class="form-group cust-frmgrp cust-frmgrp col-md-12">
                                                <label for="inputPassword4">Property Name : </label>
                                                <p class="crd-style"><?= $value['property_name'] ?></p>
                                                <!-- <input type="text" class="form-control cust-formcontrol" id="inputPassword4" placeholder="<?= $value['property_name'] ?>"> -->
                                            </div>
                                            <div class="form-group cust-frmgrp col-md-12">
                                                <label for="inputPassword4">Property Address : </label>
                                                <p class="crd-style"><?= $value['address_line_1'] ?>,<?= $value['pincode'] ?>,<?= $value['city_name'] ?>,<?= $value['state_name'] ?></p>
                                                <!-- <input type="text" class="form-control cust-formcontrol" id="inputPassword4" placeholder="<?= $value['address_line_1'] ?>,<?= $value['pincode'] ?>,<?= $value['city_name'] ?>,<?= $value['state_name'] ?>"> -->
                                            </div>
                                            <div class="form-group cust-frmgrp col-md-12">
                                                <label for="inputPassword4">Agreement From Date : </label>
                                                <p class="crd-style"><?= $value['lease_start_date'] ?></p>
                                                <!-- <input type="text" class="form-control cust-formcontrol" id="inputPassword4" placeholder="<?= $value['lease_start_date'] ?>"> -->
                                            </div>
                                            <div class="form-group cust-frmgrp col-md-12">
                                                <label for="inputPassword4">Agreement to Date : </label>
                                                <p class="crd-style"><?= $value['lease_end_date'] ?></p>
                                                <!-- <input type="text" class="form-control cust-formcontrol" id="inputPassword4" placeholder="<?= $value['lease_end_date'] ?>"> -->
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div> 
                            <br />
                            <?php
                            $i++;
                        }
                        ?>
                    </div>
                </section>
                <!-- ./mobile view -->
            </div>

        </div>   
    </div>





</div>



<script>
    $(document).ready(function () {
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        $(document).on("click", ".delete", function (e) {
            e.preventDefault();
            startLoader();
            $.ajax({
                url: '<?= \Yii::$app->getUrlManager()->createUrl('site/deletefav') ?>',
                type: 'POST',
                data: {id: $(this).attr('data-id'), _csrf: csrfToken},
                success: function (data) {
                    if (data == 0) {
                        alert('Please login to add to wishlist');
                    } else {

                        $('#favlist').load(' #favlist');
                    }
                    hideLoader();
                },
                error: function (data) {
                    hideLoader();
                    // alert(data.responseText);
                    alert('Failure')

                }
            });
        });
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
</script> 
