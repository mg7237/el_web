<?php
$this->title = 'PMS- My Request';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Modal signup -->
<!-- Modal signup -->
<div id="content" >
    <button type="button" id="sidebarCollapse" class="btn btn-info navbar-btn">
        <i class="glyphicon glyphicon-align-left"></i>
        <span></span>
    </button>
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="col-md-6 col-sm-6">
                <h4><?= Yii::$app->userdata->getPropertyNameById($prop_id); ?> - Service Request</h4>
            </div>

            <div class="col-md-6 col-sm-6">
                <div class="col-md-8 col-sm-8">
                    <!-- <button id="first_button" class="btn btn-info btn-lg pull-right">Save </button> -->
                </div>
                <div class="col-md-4 col-sm-4 cust-nopad">
                    <a href="<?= Url::home(true) ?>site/createownerrequests?id=<?= base64_encode($prop_id); ?>" type="button" class="btn renew_contracttwo btn-info cust-createreq" data-toggle="modal" data-target="#myModal" >Create Request</a>
                    <!-- <button id="first_button" class="btn btn-info btn-lg pull-right">Cancle</button> -->
                </div>
            </div>
        </div>
    </div>
    <hr style="width: 95%;margin-left: 20px;"/>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered tbl_style hide-mobile" cellpadding="5">
                <thead>
                    <tr>
                        <th class="table_td_style" style="width: 30% !important;">Title</th>
                        <th class="table_td_style" style="width: 23% !important;text-align: center;">Type</th>
                        <th class="table_td_style" style="width: 17% !important;text-align: center;">Status</th>
                        <th class="table_td_style" style="width: 10% !important;text-align: center;">Create/Update Date</th>
                        <!--<th>Property Name</th>-->

                        <th class="table_td_style" style="text-align: center;">Action</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    if ($propertyServiceRequest) {
                        foreach ($propertyServiceRequest as $request) {
                            $titlelen = strlen($request['title']);
                            if ($titlelen <= 30) {
                                $requesttitle = $request['title'];
                            } else {
                                $requesttitle = substr($request['title'], 0, 29) . "...";
                            }
                            ?>

                            <tr>
                                <td><?= $requesttitle; ?></td>

                                <td style="text-align: center;"><?= $request['request_type']; ?></td>
                                <td style="text-align: center;"><?= $request['status']; ?></td>
                                <td style="text-align: center;"><?= date('Y-m-d', strtotime($request['created_date'])); ?></td>
                                <!--<td><?php // echo (isset($request['property_id'])) ? $request['property_id'] : '' ;     ?></td>-->
                                <!--<td><?php //echo Yii::$app->userdata->getPropertyNameById($request['property_id']);   ?></td>-->
                                <td style="text-align: center;">
                                    <a href="<?= Url::home(true) ?>site/deleteservicerequest?rid=<?= base64_encode($request['id']) ?>&id=<?= base64_encode($prop_id) ?>"><img src="<?= Url::home(true) ?>images/icons/delete.png" style="width: 10%;" title="Delete Request" onclick="return confirm('Do you really want to delete the service request?')"/></a>
                                    <a href="<?= Url::home(true) ?>site/requestdetail?rid=<?= base64_encode($request['id']) ?>&id=<?= base64_encode($prop_id) ?>"><img src="<?= Url::home(true) ?>images/icons/edit.png" style="width: 10%;margin-left: 15px;" title="Edit Request"/></a>
                                </td>


                            </tr>
                            <?php
                            $i++;
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="6"> No Request found</td>
                        </tr>
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
                            $titlelen = strlen($request['title']);
                            if ($titlelen <= 30) {
                                $requesttitle = $request['title'];
                            } else {
                                $requesttitle = substr($request['title'], 0, 29) . "...";
                            }
                            ?>
                            <div class="row card-bacolor">
                                <div class="cust-card">

                                    <form>
                                        <div class="form-row cust-form">
                                            <div class="form-group cust-group col-md-12">
                                                <label for="inputEmail4">Title : </label>
                                                <p class="crd-style"><?= $requesttitle; ?></p>
                                                <!-- <input type="text" class="form-control cust-formcontrol" id="inputEmail4" placeholder="<?= $requesttitle; ?>"> -->
                                            </div>
                                            <div class="form-group cust-group col-md-12">
                                                <label for="inputPassword4">Type : </label>
                                                <p class="crd-style"><?= $request['request_type']; ?></p>
                                                <!-- <input type="text" class="form-control cust-formcontrol" id="inputPassword4" placeholder="<?= $request['request_type']; ?>"> -->
                                            </div>

                                            <div class="form-group cust-group col-md-12">
                                                <label for="inputPassword4">Status : </label>
                                                <p class="crd-style"><?= $request['status']; ?></p>
                                                <!-- <input type="text" class="form-control cust-formcontrol" id="inputPassword4" placeholder="<?= $request['status']; ?>"> -->
                                            </div>
                                            <div class="form-group cust-group col-md-12">
                                                <label for="inputPassword4">Create/Update Date	 : </label>
                                                <p class="crd-style"><?= date('Y-m-d', strtotime($request['created_date'])); ?></p>
                                                <!-- <input type="text" class="form-control cust-formcontrol" id="inputPassword4" placeholder="<?= date('Y-m-d', strtotime($request['created_date'])); ?>"> -->
                                            </div>
                                        </div>
                                    </form>
                                    <div class="action-text">
                                        <h5>Action</h5>
                                    </div>
                                    <div class="action">
                                        <div class="action-img">
                                            <a href="<?= Url::home(true) ?>site/requestdetail?rid=<?= base64_encode($request['id']) ?>&id=<?= base64_encode($prop_id) ?>"><img src="../../../ic-edit2.png" title="Edit Request"/></a>
                                        </div>
                                        <div class="action-img">
                                            <a href="<?= Url::home(true) ?>site/deleteservicerequest?rid=<?= base64_encode($request['id']) ?>&id=<?= base64_encode($prop_id) ?>"><img src="../../../ic-delete2.png" onclick="return confirm('Do you really want to delete the service request?')"/></a>
                                        </div>
                                    </div>
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
    <!-- /.col-lg-12 -->
</div>
</div>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal_popup">

        <!-- Modal content-->
        <div class="modal-content">

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
                    hideLoader();
                    if (data == 0) {
                        alert('Please login to add to wishlist');
                    } else {

                        $('#favlist').load(' #favlist');
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
    $(document).on('click', '.clickable-row', function () {
        var href = $(this).attr('data-href');
        window.location = href;
    })
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#sidebarCollapse').on('click', function () {
            $('#sidebar').toggleClass('active');
        });
    });
</script>	
<style>

</style>
<style>
    .modal-header {
        padding: 15px;
        border-bottom: 1px solid #fafafa !important;
    }
    .modal-content {
        position: relative;
        background-color: #fafafa;
        /*border: 1px solid #999;
        border: 1px solid rgba(0,0,0,0.2);*/
        border-radius: 0px;
        -webkit-box-shadow: 0 3px 9px rgba(0,0,0,0.5);
        box-shadow: 0 3px 9px rgba(0,0,0,0.5);
        -webkit-background-clip: padding-box;
        background-clip: padding-box;
        outline: 0
    }
    #content {
        width: 100%;
    }       
    .table_td_style{
        width: 20%;
    } 
    .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
        padding: 8px;
        line-height: 1.42857143;
        vertical-align: top;
        border-top: 1px solid #fff !important;
    } 
    .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
        border: 1px solid #fff !important;
    }
    .tbl_style{
        width: 95%;
        margin-left: 20px;
        border: 2px solid #ddd;
        box-shadow: 0px 2px 2px black;
        -moz-box-shadow: 0px 2px 2px black,;
        -webkit-box-shadow: 1px 1px 1px 1px #ddd;
    }
    input#servicerequest-description ,input#servicerequest-title,select#servicerequest-request_type{
        height: 45px;
        padding: 10px;

    }
    select#servicerequest-request_type {
        width: 100% !important;
    }
    @media screen and (max-width: 767px){
        .crd-style{
            color: white;
            border-bottom: 1px solid white;
        }
        .action-text{
            margin-left: 15px
        }
        .action{
            margin-left: 15px;
        }
    }
    @media screen and (max-width: 768px){
        select#servicerequest-request_type {
            width: 45% !important;
        }
        input#servicerequest-description ,input#servicerequest-title,select#servicerequest-request_type{
            width: 45%;
        }
        .sbu_btn{
            width: 45% !important;
        }
        .show-mobile{
            width: 80%;
        }
        .mar1 {
            margin-left: 30px !important;
        }
        .cust-group{
            width: 97%;
        }
        .modal-content{
            width: 100%;
        }
        .cust-createreq{
            /* width: 100%; */
            width: 133px !important;
            padding: 12px 3px !important;
            background: #e24c3f;

        }
        .cust-nopad{
            padding-right: 0;
        }
    }

</style>