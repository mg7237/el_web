<?php
$this->title = 'PMS- My Request';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\models\RefStatus;
use yii\grid\GridView;
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> 
<!-- Modal signup -->
<!-- Modal signup -->
<style>
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
    .input-group
    {
        width:100% !important;
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
    .modal-dialog {
        min-height: 240px;
    }

    .modal-body .form-check {
        margin-left: 3%;
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
    @media screen and (max-width: 767px){
        #content{
            width: 100% !important;
        }
        #cust-createrequest{
            margin-right: 0 !important;
        }
        p#table_heading{
            margin-left: -15px;
        }
        .crd-style{
            color: white;
            border-bottom: 1px solid white;
        }
    }
    @media screen and (max-width: 320px){
        .cust-formcontrol{
            width: 80%;
        }
        select#servicerequest-request_type {
            width: 90% !important;
        }
        .cust-grp{
            margin-left: 0 !important;
        }
        select..cust-formcontrol, input..cust-formcontrol
        {
            background-color: #59abe3 !important
        }
        #cust-createrequest{
            margin-right: -15px !important;
            width: 120px !important;
            padding: 11px 2px !important;
        }
        .cust-modaldialog{
            margin-top: 15px;
            width: 90%;
        }
        input#servicerequest-description ,input#servicerequest-title,select#servicerequest-request_type{
            width: 90% !important;
        }
        #property_id, #submit_id{
            width: 90% !important;
        }
    }
</style>
<div id="content">
    <button type="button" id="sidebarCollapse" class="btn btn-info navbar-btn">
        <i class="glyphicon glyphicon-align-left"></i>
        <span></span>
    </button>
    <div class="container" style="margin-top: -42px;">
        <div class="col-md-4 col-sm-6">
            <h4>SERVICE REQUEST</h4>
        </div>
        <div class="col-md-8 col-sm-6" style="margin-right: -30px;">
            <a href="<?= Url::home(true) ?>site/createrequests" type="button" style="float:right;" id="cust-createrequest" class="btn btn-info btn-lg cust_btn1" data-toggle="modal" data-target="#myModal" >Create Request</a>
        </div>

    </div>

    <hr style="width: 100%;margin-left: 0px;"/>
    <div class="container">
        <div class="col-md-12">
            <?php if (Yii::$app->session->hasFlash('success')) { ?>
                <div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                    <?= Yii::$app->session->getFlash('success'); ?>
                </div>
            <?php } ?>
            <br/>
            <p id="table_heading" style="color: #323a47;font-weight: 600;font-size: 14px;"> Request Details</p>
            <div class="">
                <table class="table table-bordered tbl_style hide-mobile">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Create/Update Date</th>
                            <th style="text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        if ($propertyServiceRequest) {
                            foreach ($propertyServiceRequest as $request) {
                                if ($i % 2 != 0) {
                                    $dclass = "dclass";
                                } else {
                                    $dclass = "";
                                }
                                ?>  
                                <tr class="<?php echo $dclass; ?>">
                                    <td width="25%"><?= $request['title']; ?></td>
                                    <td width="25%"><?= $request['request_type']; ?></td>
                                    <td width="20%"><?= $request['status'] ?></td>
                                    <td width="15%"><?= date('d-M-Y', strtotime($request['created_date'])); ?></td>
                                    <td width="15%" style="text-align: center;">
                                        <a href="<?= Url::home(true) . 'site/requesttenantdetail?id=' . $request['id'] ?>" title="Edit Request"><img src="<?= Url::home(true) ?>images/icons/edit.png" style="width: 15%;" /></a>
                                        <a href="<?= Url::home(true) . 'site/deleterequest?id=' . $request['id'] ?>" title="Delete Request" onclick="return confirm('Are you sure you want to delete?')" ><img src="<?= Url::home(true) ?>images/icons/delete.png" style="width: 15%;margin-left: 15px;" /></a>

                                    </td>
                                </tr>
                                <?php
                                $i++;
                            }
                        } else {
                            ?>     
                            <tr> <td colspan="6"> No Request found</td></tr>
                        <?php } ?>   
                    </tbody>
                </table>

                <!-- mobile view -->
                <section class="show-mobile mar1">
                    <div class="continer">
                        <?php
                        $i = 1;
                        if ($propertyServiceRequest) {
                            foreach ($propertyServiceRequest as $request) {
                                if ($i % 2 != 0) {
                                    $dclass = "dclass";
                                } else {
                                    $dclass = "";
                                }
                                ?> 
                                <div class="row card-bacolor">
                                    <div class="cust-card">

                                        <form>
                                            <div class="form-row cust-form">
                                                <div class="form-group cust-grp col-md-12">
                                                    <label for="inputEmail4">Title : </label>
                                                    <p class="crd-style"><?= $request['title']; ?></p>
                                                    <!-- <input type="text" class="form-control cust-formcontrol" id="inputEmail4" placeholder="<?= $request['title']; ?>"> -->
                                                </div>
                                                <div class="form-group cust-grp col-md-12">
                                                    <label for="inputPassword4">Type : </label>
                                                    <p class="crd-style"><?= $request['request_type']; ?></p>
                                                    <!-- <input type="text" class="form-control cust-formcontrol" id="inputPassword4" placeholder="<?= $request['request_type']; ?>"> -->
                                                </div>

                                                <div class="form-group cust-grp col-md-12">
                                                    <label for="inputPassword4">Status : </label>
                                                    <p class="crd-style"><?= $request['status'] ?></p>
                                                    <!-- <input type="text" class="form-control cust-formcontrol" id="inputPassword4" placeholder="<?= $request['status'] ?>"> -->
                                                </div>
                                                <div class="form-group cust-grp col-md-12">
                                                    <label for="inputPassword4">Create/Update Date	 : </label>
                                                    <p class="crd-style"><?= date('d-M-Y', strtotime($request['created_date'])); ?></p>
                                                    <!-- <input type="text" class="form-control cust-formcontrol" id="inputPassword4" placeholder="<?= date('d-M-Y', strtotime($request['created_date'])); ?>"> -->
                                                </div>
                                            </div>
                                        </form>
                                        <div class="action-text">
                                            <h5>Action</h5>
                                        </div>
                                        <div class="action">
                                            <div class="action-img">

                                                <a href="<?= Url::home(true) . 'site/requesttenantdetail?id=' . $request['id'] ?>" title="Edit Request"><i class="fa fa-pencil cst-pen" aria-hidden="true"></i></a>

                                            </div>
                                            <div class="action-img">
                                                <a href="<?= Url::home(true) . 'site/deleterequest?id=' . $request['id'] ?>" title="Delete Request" onclick="return confirm('Are you sure you want to delete?')" ><i class="fa fa-trash-o cst-pen" aria-hidden="true"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div> <br />
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
    </div>
</div>
<script>
    $(document).ready(function () {
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        $(document).on("click", ".delete", function (e) {
            e.preventDefault();
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
                },
                error: function (data) {
                    //    console.log(data.responseText);
                    alert(data.responseText);
                }
            });
        });
    });
</script> 
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog cust-modaldialog" role="document" style="margin-left: 17%;">
        <div class="modal-content cust-modalcontent" style="width: 60%;">
        </div>
    </div>
</div>

<style>
    .modal_popup{
        width: 35% !important;
    }

    .action-img img{
        width: 40%;
    }
    .cst-pen{
        font-size: 20px;
        color: red;
    }

    .cust_btn1 {
        width: 180px;
        background: #e24c3f;
        height: 40px;
        /* padding-top: 6px; */
        border: 1px solid #e24c3f;
        border-radius: 2px;
        color: white;
        font-size: 13px;
        font-weight: bold;
        font-style: normal;
        font-stretch: normal;
        line-height: 1.23;
        letter-spacing: 0.9px;
        /* text-align: left; */
        color: #ffffff;
        margin-right: 5px;
    }
    .modal-content
    {
        background: #fafafa !important;
    }
    #content {
        width: 80%;   
    }       
    .container
    {
        width:100%;
        margin-left:0px;
    }

    input#servicerequest-description ,input#servicerequest-title,select#servicerequest-request_type{
        height: 45px;
        padding: 10px;
    }
    select#servicerequest-request_type {
        width: 100%;
    }
</style>
<style>
    /* table {border: none;} */
    /* th.td_style {
        border-bottom: 1px solid white !important;
    }
    td.td_style {
        border: 1px solid white;
    } */
    table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%;
        border: 1px solid #dddddd; 
    }

    td, th {
        /* border: 1px solid #dddddd; */
        text-align: left;
        padding-left: 30px;
        height:64px;
    }

    .dclass {
        background-color: #f9f9f9;
    }



</style>