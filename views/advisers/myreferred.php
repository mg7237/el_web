<?php
$this->title = 'PMS- My Referred';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* echo "<pre>";
  print_r($model);
  die; */
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

<!-- Modal signup -->
<!-- Modal signup -->
<div id="content">
    <button type="button" id="sidebarCollapse" class="btn btn-info navbar-btn">
        <i class="glyphicon glyphicon-align-left"></i>
        <span></span>
    </button>
    <div class="container" style="margin-top: -42px;">

        <div class="col-md-4">
            <h4>CLIENT ONBOARDING STATUS</h4>
        </div>
    </div>
    <hr>
    <!--<div class="row">-->
    <div class="container">

        <div class="col-md-12">                
        <!--<p id="table_heading" style="color: #323a47;font-weight: 600;font-size: 14px;">My Referred List</p>
<p id="table_heading">Favourite / Scheduled / Booked list</p-->
            <div class="">
                <table class="table hide-mobile" frame="box">
                    <thead>
                        <tr>
                            <th style="width: 12%;" align="center">Type</th>
                            <th style="width: 15%;" align="center">Name</th>
                            <th style="width: 20%;" align="center">Client Contact</th>
                            <th style="width: 40%;" align="center">Address</th>
                            <th style="width: 13%;" align="center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($model) {

                            // die;
                            $i = 1;
                            foreach ($model as $key => $wish) {
                                if ($i % 2 == 0) {
                                    $dclass = "";
                                } else {
                                    $dclass = "dclass";
                                }
                                ?>
                                <tr class="<?php echo $dclass; ?>">
                                    <td><?= $wish['user_type_name'] ?></td>
                                    <td><?= $wish['full_name']; ?></td>
                                    <td><?= $wish['contact_number']; ?><br/><?= $wish['email_id']; ?></td>
                                    <td><?= $wish['address']; ?>,<?= $wish['address_line_2']; ?>,<?= $wish['city_name']; ?>,<?= $wish['state_name']; ?></td>
                                    <td><?php
                                        if ($wish['ref_name'] == '') {
                                            echo "New";
                                        } else {
                                            echo $wish['ref_name'];
                                        }
                                        ?></td>
                                </tr>
                                <?php
                                $i++;
                            }
                        } else {
                            ?> 
                            <tr> <td colspan="5">no record found</td> </tr>
                            <?php
                        }
                        ?> 
                    </tbody>
                </table>
            </div>

            <!-- mobile view -->
            <section class="show-mobile mar1">
                <div class="continer">
                    <?php
                    if ($model) {

                        // die;
                        $i = 1;
                        foreach ($model as $key => $wish) {
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
                                                <label for="inputEmail4">Type : </label>
                                                <p class="crd-style"><?= $wish['user_type_name'] ?></p>
                                                <!-- <input type="text" class="form-control cust-formcontrol" id="inputEmail4" placeholder="<?= $wish['user_type_name'] ?>"> -->
                                            </div>
                                            <div class="form-group cust-frmgrp col-md-12">
                                                <label for="inputPassword4">Name : </label>
                                                <p class="crd-style"><?= $wish['full_name']; ?></p>
                                                <!-- <input type="text" class="form-control cust-formcontrol" id="inputPassword4" placeholder="<?= $wish['full_name']; ?>"> -->
                                            </div>
                                            <div class="form-group cust-frmgrp col-md-12">
                                                <label for="inputPassword4">Client Contact : </label>
                                                <p class="crd-style"><?= $wish['contact_number']; ?>/<?= $wish['email_id']; ?></p>
                                                <!-- <input type="text" class="form-control cust-formcontrol" id="inputPassword4" placeholder="<?= $wish['contact_number']; ?>/<?= $wish['email_id']; ?>"> -->
                                            </div>
                                            <div class="form-group cust-frmgrp col-md-12">
                                                <label for="inputPassword4">Address : </label>
                                                <p class="crd-style"><?= $wish['address']; ?>,<?= $wish['address_line_2']; ?>,<?= $wish['city_name']; ?>,<?= $wish['state_name']; ?></p>
                                                <!-- <input type="text" class="form-control cust-formcontrol" id="inputPassword4" placeholder="<?= $wish['address']; ?>,<?= $wish['address_line_2']; ?>,<?= $wish['city_name']; ?>,<?= $wish['state_name']; ?>"> -->
                                            </div>
                                            <div class="form-group cust-frmgrp col-md-12">
                                                <label for="inputPassword4">Status : </label>
                                                <?php
                                                if ($wish['ref_name'] == '') {
                                                    $refname = "New";
                                                } else {
                                                    $refname = $wish['ref_name'];
                                                }
                                                ?>
                                                <p class="crd-style"><?php echo $refname; ?></p>
                                                        <!-- <input type="text" class="form-control cust-formcontrol" id="inputPassword4" placeholder="<?php
                                                if ($wish['ref_name'] == '') {
                                                    echo "New";
                                                } else {
                                                    echo $wish['ref_name'];
                                                }
                                                ?>"> -->
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div> 
                            <br />
                            <?php
                            $i++;
                        }
                    }
                    ?> 
                </div>
            </section>
            <!-- ./mobile view -->

        </div>


    </div>
    <!--</div>-->
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
                    alert('Failure');
                }
            });
        });
    });
</script> 
<style>
    @media screen and (max-width: 767px){
        .crd-style{
            color: white;
            border-bottom: 1px solid white;
        }
    }
    #content{
        width: 100%;
    }
    .cust-frmgrp p{
        overflow: scroll;
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
    .bgstyle {
        background-color: #fafafa !important;
        background-color: var(--white);
        margin-left: 0px !important;
        /*padding-top: 15px;*/
    }
    .dclass
    {
        background-color: #fafafa !important;
    }
    .feed-details-tbl
    {
        /*border: 1px solid #dddddd !important;*/
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
    div#favlistmain {
        margin-left: 55px;
        margin-top: -20px;
    }
    th.textcenter {
        font-weight: 600;
        font-size: 14px;
    }
    td.textaling {
        font-size: 15px;
        line-height: 2.5 !important;
        /* margin: 106px; */
        /* padding-top: 59px; */
    }
    .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
        padding: 8px;
        line-height: 1.42857143;
        vertical-align: top;
        border-top: 1px solid #fff !important;
    }
    .tr-style{
        background: red;
    }
    .img_style{
        width: 85px !important;
        height: 48px !important;
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
    .sturdy td:nth-child(1),
    .sturdy td:nth-child(3) {
        /* width: 30%;  */
        word-wrap:break-word;
        line-height: 1.5;
    }
    .sturdy td:nth-child(2) {
        /* width: 50%; */
        word-wrap:break-word;
        line-height: 1.5;
    } 
    .th_width {
        width: 180px;
    }

</style>