<?php
$this->title = 'PMS- Owner Referred';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

// echo "<pre>";
// print_r($model);
// print_r($vacancy);
//Yii::$app->userdata->getStatusByProperty(1);
// die;
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<style>
    #content{
        width: 100%;
    }

    @media screen and (max-width: 767px){
        .tbl_style{
            margin-left: -15px;
        }
        .cust-formcontrol::placeholder {
            color: white !important;
        }
        .buttonSubmit{
            width: 100% !important;
            margin-top: 10px;
        }
        .navbar-search{
            padding-right: 0;
        }
        .cust-colmd{
            padding-right: 0 !important;
        }
        .crd-style{
            color: white;
            border-bottom: 1px solid white;
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
    .feed-details-tbl
    {
        /*border: 1px solid #dddddd !important;*/
    }
    .col-md-3,.col-md-2,.col-md-4
    {
        padding-right: 15px;
        padding-left: 0px;
    }
    .col-md-3.col-sm-4.col-xs-4 {
        padding: 0px 4px;
    }
    .col-md-6.col-sm-4.col-xs-4 {
        padding: 0px 4px;
    }
    .selectyear
    {
        height:40px;
    }
    .form-control
    {
        height:40px;
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
    .btn-select {
        position: relative;
        padding: 0;
        min-width: 146px;
        width: 100%;
        border-radius: 0;
        margin-bottom: 20px;
        height: 32px;
    }
    .navbar-search .input-group {
        width: 100%;
    }
    @media only screen and (min-width:768px) and (max-width:1023px){
        #page-wrapper {
            margin: -27px 0 0 200px;
        }
        #header > :first-child, aside {
            width: 210px;
        }
        .sidebar {
            background: #26344B none repeat scroll 0 0;
            margin-top: 21px;
            position: absolute;
            width: 210px;
            z-index: 1;
        }
        .buttonSubmit{
            width: 100% !important;
            margin-top: 10px;
        }
        .navbar-search{
            padding-right: 0;
        }
        .cust-colmd{
            padding-right: 0 !important;
        }
        .navbar-form .form-control{
            width: 100%;
        }
    }
</style>
<div id="content">
    <button type="button" id="sidebarCollapse" class="btn btn-info navbar-btn">
        <i class="glyphicon glyphicon-align-left"></i>
        <span></span>
    </button>
    <div class="container" style="margin-top: -42px;">

        <div class="col-md-6">
            <h4>MY REFERRED PROPERTY OWNERS</h4>
        </div>
        <!-- <div class="col-md-6">
            <button id="first_button" class="btn btn-info btn-lg pull-right">Edit</button>
        </div -->


    </div>

    <hr>

    <div class="container">

        <div class="form-group col-md-12" style="padding-left: 0px !important;">
            <form class="navbar-form navbar-search" role="search" method="GET">
                <div class="col-md-3 cust-colmd">
                    <input type="hidden" class="btn-select-input" id="status_val" name="status_val" value="<?= (isset($_GET['status_val']) ? $_GET['status_val'] : '') ?>" />
                    <select class="form-control selectyear status_val" onchange="getstatus(this.value)">
                        <option value="" class="" selected="selected">Select Occupancy Status</option>
                        <option label="All" value="All">All</option>
                        <option label="1" value="1">Fully Occupied</option>
                        <option label="0" value="0">Partially Occupied</option>
                        <option label="-1" value="-1">Vacant</option>
                    </select>
                </div>

                <div class="col-md-3 cust-colmd">
                    <input type="hidden" class="btn-select-input" id="sort_val" name="sort_val" value="<?= (isset($_GET['sort_val']) ? $_GET['sort_val'] : '') ?>" />
                    <select class="form-control selectyear sort_val" onchange="getsort(this.value)">
                        <option value="" class="" selected="selected">Sort By</option>
                        <!--option label="p.id" value="p.id">Property Code</option-->
                        <option label="u.full_name" value="u.full_name">Owner Name</option>
                        <option label="u.login_id" value="u.login_id">Email</option>
                        <option label="up.phone" value="up.phone">Phone</option>
                        <option label="p.address_line_1" value="p.address_line_1">Address</option>
                        <option label="c.city_name" value="c.city_name">City</option>
                        <option label="s.name" value="s.name">State</option>
                        <option label="a.contract_start_date" value="a.contract_start_date">Agreement Start Date</option>
                        <option label="a.contract_end_date" value="a.contract_end_date">Agreement End Date</option>
                    </select>
                </div>
                <div class="col-md-4 cust-colmd cust-srch">
                    <input type="text" class="form-control" name="search" placeholder="Type here" id="search" value="<?= (isset($_GET['search']) ? $_GET['search'] : '') ?>">
                </div>
                <div class="col-md-2 cust-colmd">
                    <button type="submit" class="btn btn-primary btn-lg savebutton buttonSubmit"> Search</button>
                </div>
            </form>
        </div>

        <div class="col-md-12">

            <table class="table table-bordered tbl_style hide-mobile" frame="box">
                <thead>
                    <tr>
                        <!--th>Property code</th-->
                        <th>Property Owner</th>
                        <!--th>Name</th-->
                        <th>Owner Contact</th>
                        <th>Property Name</th>
                        <th>Property Address</th>
                        <th>Agreement Start Date</th>
                        <th>Agreement End Date</th>
                        <th>Occupancy Status</th>

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
                            <td><?= $value['address_line_1'] ?><?= (trim($value['address_line_2']) != '') ? "," . $value['address_line_2'] : '' ?><?= (trim($value['city_name']) != '') ? "," . $value['city_name'] : '' ?><?= (trim($value['state_name']) != '') ? "," . $value['state_name'] : '' ?></td>
                            <td><?= Date('d-M-Y', strtotime($value['contract_start_date'])) ?></td>
                            <td><?= Date('d-M-Y', strtotime($value['contract_end_date'])) ?></td>
                            <td><?= $vacancy[$value['id']] ?></td>

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
                                        <div class="form-group col-md-12">
                                            <label for="inputEmail4">Property Owner : </label>
                                            <!-- <input type="text" class="form-control cust-formcontrol" id="inputEmail4" placeholder="<?= $value['full_name'] ?>"> -->
                                            <p class="crd-style"><?= $value['full_name'] ?></p>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="inputPassword4">Owner Contact : </label>
                                            <!-- <input type="text" class="form-control cust-formcontrol" id="inputPassword4" placeholder="card"> -->
                                            <p class="crd-style"><?= $value['phone'] ?>/<br/><?= $value['login_id'] ?></p>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="inputPassword4">Property Name : </label>
                                            <!-- <input type="text" class="form-control cust-formcontrol" id="inputPassword4" placeholder="<?= $value['property_name'] ?>"> -->
                                            <p class="crd-style"><?= $value['property_name'] ?></p>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="inputPassword4">Property Address : </label>
                                            <!-- <input type="text" class="form-control cust-formcontrol" id="inputPassword4" placeholder="<?= $value['address_line_1'] ?><?= (trim($value['address_line_2']) != '') ? "," . $value['address_line_2'] : '' ?><?= (trim($value['city_name']) != '') ? "," . $value['city_name'] : '' ?><?= (trim($value['state_name']) != '') ? "," . $value['state_name'] : '' ?>"> -->
                                            <p class="crd-style"><?= $value['address_line_1'] ?><?= (trim($value['address_line_2']) != '') ? "," . $value['address_line_2'] : '' ?><?= (trim($value['city_name']) != '') ? "," . $value['city_name'] : '' ?><?= (trim($value['state_name']) != '') ? "," . $value['state_name'] : '' ?></p>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="inputPassword4">Agreement Start Date : </label>
                                            <!-- <input type="text" class="form-control cust-formcontrol" id="inputPassword4" placeholder="<?= Date('d-M-Y', strtotime($value['contract_start_date'])) ?>"> -->
                                            <p class="crd-style"><?= Date('d-M-Y', strtotime($value['contract_start_date'])) ?></p>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="inputPassword4">Agreement End Date : </label>
                                            <p class="crd-style"><?= Date('d-M-Y', strtotime($value['contract_end_date'])) ?></p>
                                            <!-- <input type="text" class="form-control cust-formcontrol" id="inputPassword4" placeholder="<?= Date('d-M-Y', strtotime($value['contract_end_date'])) ?>"> -->
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="inputPassword4">Occupancy Status : </label>
                                            <p class="crd-style"><?= $vacancy[$value['id']] ?></p>
                                            <!-- <input type="text" class="form-control cust-formcontrol" id="inputPassword4" placeholder="<?= $vacancy[$value['id']] ?>"> -->
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


    </div></div>





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
                    //    console.log(data.responseText);
                    // alert(data.responseText);
                    hideLoader();
                    alert('Failure');
                }
            });
        });

    });



    $(document).on('click', 'ul.sort_ul li', function () {
        var value = $(this).attr('value');
        $('#sort_val').val(value);
        $('.sort_select_val').text($('ul.sort_ul li[value="' + value + '"]').text());
        $('.navbar-search').submit();
    })
    $(document).ready(function () {
        if ($('#sort_val').val() != '') {
            $('ul.sort_ul li').removeClass('selected');
            $('ul.sort_ul li[value="' + $('#sort_val').val() + '"]').addClass('selected');
            $('.sort_select_val').text($('ul.sort_ul li[value="' + $('#sort_val').val() + '"]').text());
        }

    })




    $(document).on('click', 'ul.status_ul li', function () {
        var value = $(this).attr('value');
        $('#status_val').val(value);
        $('.status_select_val').text($('ul.status_ul li[value="' + value + '"]').text());
        $('.navbar-search').submit();
    })
    $(document).ready(function () {
        if ($('#status_val').val() != '') {
            $('ul.status_ul li').removeClass('selected');
            $('ul.status_ul li[value="' + $('#status_val').val() + '"]').addClass('selected');
            $('.status_select_val').text($('ul.status_ul li[value="' + $('#status_val').val() + '"]').text());
        }

    })


    $(document).on('click', '.btn-select', function () {
        $(this).children('ul').toggle();
    })

    function getstatus(target)
    {
        $("#status_val").val(target);
    }
    function getsort(target)
    {
        $("#sort_val").val(target);
    }
</script> 
