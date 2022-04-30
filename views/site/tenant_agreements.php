<?php
$this->title = 'PMS- My Lease';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<style>
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
    select.form-control,input.form-control
    {
        /*border: 1px solid #ccc;*/
        background-color:transparent !important;
    }
    select.form-control {
        width: 89% !important;
        border-top: none;
        border-right: none;
        background: #f5f5f5;
        box-shadow: none;
        border-left: none;
        padding-left: 0;
        border: 1px solid #f5f5f5;
        border-bottom: 1px solid rgba(50, 58, 71, 0.2);
    }

    .bgstyle {
        background-color: #fafafa !important;
        background-color: var(--white);
    }

    .box+.box {
        margin-top: 2.5rem;
    }

    .well {
        min-height: 20px;
        padding-top: 19px;
        margin-bottom: 20px;
        background-color: transparent !important;
        border: none !important;
        border-radius: 0px !important;
        -webkit-box-shadow: none !important;
        box-shadow: none !important;
        padding-left: 0px;
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

    .identity_bg {
        /* background-color: #ffffff !important; */
        background-color: #f5f5f5;
        margin-top: 15px;
        margin-left: 2px;
        width: 99%;
    }

    .adar_img {
        width: 286px;
        height: 288px;
    }

    #content {
        width: 100%;
    }

    input.form-control {
        background: whitesmoke;
    }
    @media screen and (max-width: 375px){
        .cust-b{
            margin-right: -40px;
        }
        .bgstyle{
            width: 100%;
            margin-left: 15px !important;
        }
        .cust-btn2{
            padding-left: 0;
            width: 111%;
        }
    }

    @media screen and (max-width: 320px){
        #cust-termination{
            letter-spacing: 0.6px !important;
        }
    }

    @media screen and (min-width: 400px) and (max-width: 414px){
        .bgstyle{
            width: 88%;
        }
        .cust-btn2{
            padding-right: 10px !important;
            margin-left: -15px;
        }
    }

    @media screen and (max-width: 767px){
        #cust-btn1 {
            margin-top: 0 !important;
            width: 100% !important;
            float: left !important; 
        }
        #cust-extension{
            margin-right: -8px !important;
            width: 90% !important;
        }
        #cust-termination{
            width: 90% !important;
            margin-right: 10px !important;
        }
        .cust-lease{

            margin-top: -15px;
        }
        .bgstyle{
            margin-left: 15px !important;
            padding-top: 15px;
        }
        .cust-frmcntrl{
            margin-bottom: 5px !important;
        }
    }
    @media (min-width: 576px) and (max-width: 767px){
        #cust-extension{
            margin-top: 0 !important;
        }
    }

    @media (min-width: 769px) and (max-width: 991px){
        #cust-extension{
            margin: 15px 0 0 0 !important;
        }
    }
</style>
<!-- Modal signup -->
<!-- Modal signup -->
<div id="content">
    <button type="button" id="sidebarCollapse" class="btn btn-info navbar-btn">
        <i class="glyphicon glyphicon-align-left"></i>
        <span></span>
    </button>

    <?php
    if ($tenantAgreements) {
        foreach ($tenantAgreements as $tenantAgreement) {
            ?> 
            <div class="fullagreement" style="margin-bottom: 68px;">
                <div class="container" style="margin-top: -42px;">
                    <div class="col-md-10">
                        <h4><?= strtoupper(Yii::$app->userdata->getPropertyNameById($tenantAgreement->parent_id)); ?> <?php
                            if (!empty($tenantAgreement->bed_id)) {
                                echo " - (" . Yii::$app->userdata->getRoomName($tenantAgreement->bed_id) . ")";
                            } else if (!empty($tenantAgreement->room_id) && empty($tenantAgreement->bed_id)) {
                                echo " - (" . Yii::$app->userdata->getRoomName($tenantAgreement->room_id) . ")";
                            }
                            ?> - LEASE AGREEMENT</h4>
                    </div>

                </div>
                <!--<div class="row">
                    <div class="col-md-7">
                        <h4 style="font-size: 14px !important;font-weight: bold;font-style: normal;font-stretch: normal;line-height: 1.14;letter-spacing: 0.9px;text-align: left;color: #323a47;">AGREEMENT DETAILS</h4>
                    </div>
                </div>-->
                <hr>
                <div class="container">
                    <div class="container-fluid">
                        <div class="col-md-10 col-sm-8" style="padding-left: 0px;">
                            <h4 id="personal_info" style="margin-bottom: 22px !important;">AGREEMENT DETAILS</h4>
                        </div>
                        <div class="col-md-2 col-sm-4 cust-btn2" style="padding-right: 0px;">

                            <a target="_blank" type="button" id="cust-btn1" href="<?= (trim($tenantAgreement->agreement_url)) != '' ? '../' . $tenantAgreement->agreement_url : 'javascript:' ?>" class="btn btn-info btn-lg pull-right cust_btn1" download="agreement"><?= (trim($tenantAgreement->agreement_url)) != '' ? 'Download PDF' : 'No PDF' ?></a>

                            <!--div class="col-md-3">
                                <button id="first_button" class="btn btn-info btn-lg pull-right">Cancle</button>
                            </div-->
                        </div>
                    </div>
                    <div>&nbsp;</div>
                    <div class="col-md-12 bgstyle" style="margin: 5px;">
                        <div class="row">
                            <div class="col-md-4">
                                <b id="bold">Rent</b>
                                <div class="input-group colorpicker colorpicker-element">
                                    <div class="form-line focused">
                                        <input type="text" class="form-control cust-frmcntrl" value="&#8377; <?= Yii::$app->userdata->getformat($tenantAgreement->rent); ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <b id="bold">Maintenance</b>
                                <div class="input-group colorpicker colorpicker-element">
                                    <div class="form-line focused">
                                        <input type="text" class="form-control cust-frmcntrl" value="&#8377; <?= Yii::$app->userdata->getformat($tenantAgreement->maintainance); ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <b id="bold">Deposit</b>
                                <div class="input-group colorpicker colorpicker-element">
                                    <div class="form-line">
                                        <input type="text" class="form-control" value="&#8377; <?= Yii::$app->userdata->getformat($tenantAgreement->deposit); ?>" readonly>
                                    </div>
                                </div>
                            </div>


                        </div>
                        <br/>
                        <div class="row cust-lease">
                            <div class="col-md-4">
                                <b id="bold">Lease Start Date</b>
                                <div class="input-group colorpicker colorpicker-element">
                                    <div class="form-line focused">
                                        <input type="text" class="form-control cust-frmcntrl" value="<?= $tenantAgreement->lease_start_date; ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <b id="bold">Lease End Date</b>
                                <div class="input-group colorpicker colorpicker-element">
                                    <div class="form-line">
                                        <input type="text" class="form-control cust-frmcntrl" value="<?= $tenantAgreement->lease_end_date; ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <b id="bold">Minimum Stay Period (In Months)</b>
                                <div class="input-group colorpicker colorpicker-element">
                                    <div class="form-line focused">
                                        <?php
                                        if ($tenantAgreement->min_stay <= 1) {
                                            $month = "Month";
                                        } else {
                                            $month = "Months";
                                        }
                                        ?>
                                        <input type="text" class="form-control" value="<?= $tenantAgreement->min_stay . " " . $month; ?>" readonly>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <br/>
                        <div class="row cust-lease">


                            <div class="col-md-4">
                                <b id="bold">Notice Period (In Months)</b>
                                <div class="input-group colorpicker colorpicker-element">
                                    <div class="form-line focused">
                                        <?php
                                        if ($tenantAgreement->notice_period <= 1) {
                                            $month = "Month";
                                        } else {
                                            $month = "Months";
                                        }
                                        ?>
                                        <input type="text" class="form-control cust-frmcntrl" value="<?= $tenantAgreement->notice_period . " " . $month; ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <b id="bold">Late Fee Charges (%)</b>
                                <div class="input-group colorpicker colorpicker-element">
                                    <div class="form-line">
                                        <input type="text" class="form-control cust-frmcntrl" value="<?= $tenantAgreement->late_penalty_percent . " %"; ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <b id="bold">Late Fee Minimum Amount</b>
                                <div class="input-group colorpicker colorpicker-element">
                                    <div class="form-line focused">
                                        <input type="text" class="form-control" value="&#8377; <?= Yii::$app->userdata->getformat($tenantAgreement->min_penalty); ?>" readonly>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!--br/>
        <div class="row">
        <div class="col-md-4">
            <b id="bold">Property</b>
            <div class="input-group colorpicker colorpicker-element">
                <div class="form-line">
                    <input type="text" class="form-control" value="<?= Yii::$app->userdata->getPropertyNameById($tenantAgreement->parent_id); ?> <?php
                                        if (!empty($tenantAgreement->bed_id)) {
                                            echo '[Bed]';
                                        } else if (!empty($tenantAgreement->room_id) && empty($tenantAgreement->bed_id)) {
                                            echo '[Room]';
                                        }
                                        ?>">
                </div>
            </div>
        </div> 


        </div-->
                        <div>&nbsp;</div>
                    </div>

                </div>
                <br/>
                <div class="container">
                    <div class="col-md-12 cust-b" style="margin-left: -26px;">
                        <button id="cust-termination" class="btn btn-danger btn-lg pull-right cust_btn" onclick="requesttermination()">Request Agreement Termination</button>
                        <button id="cust-extension" class="btn btn-primary btn-lg pull-right cust_btn_gr" onclick="requestextension()" style="margin-right: 10px !important;">Request Agreement Extension </button>



                    </div>
                </div>
            </div>
        <?php
    }
} else {
    ?>
        <div class="row"> <p> No agreements found </p></div> 
<?php } ?>	


</div>

<script>
    function changedate(id) {
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        startLaoder();
        $.ajax({
            url: '<?= \Yii::$app->getUrlManager()->createUrl('site/changedate') ?>',
            type: 'POST',
            data: {date: $('#changed').val(), agr_id: id, _csrf: csrfToken},
            success: function (data) {
                hideLoader();
                if (data) {
                    alert(data);
                    location.reload();
                } else {
                    alert('Lease end date changed successfully');
                    location.reload();

                }
            },
            error: function (data) {
                //    console.log(data.responseText);
                hideLoader();
                alert('Failure');
                // alert(data.responseText);
            }
        });
    }

    function requestextension()
    {
        var pid = "<?php echo $tenantAgreement->parent_id; ?>";
        var end_date = "<?php echo $tenantAgreement->lease_end_date; ?>";
        var csrfToken = "<?= Yii::$app->request->getCsrfToken() ?>";
        $.ajax({
            url: "requestextensiontenant",
            type: "POST",
            data: {'pid': pid, '_csrf': csrfToken, 'end_date': end_date},
            success: function (data) {
                alert(data);
            }

        });
    }

    function requesttermination()
    {
        var pid = "<?php echo $tenantAgreement->parent_id; ?>";
        var end_date = "<?php echo $tenantAgreement->lease_end_date; ?>";
        var csrfToken = "<?= Yii::$app->request->getCsrfToken() ?>";
        $.ajax({
            url: "requestterminationtenant",
            type: "POST",
            data: {'pid': pid, '_csrf': csrfToken, 'end_date': end_date},
            success: function (data) {
                alert(data);
            }

        });
    }

</script> 
<style>

    .cust_btn1 {
        width: 180px !important;
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

    .cust_btn_gr {
        background: #59abe3;
        height: 40px;
        padding-top: 6px;
        border: 1px solid #59abe3;
        color: white;
        border-radius: 2px;
        font-size: 13px;
        font-weight: bold;
        font-size: 13px;
        font-weight: bold;
        font-style: normal;
        font-stretch: normal;
        letter-spacing: 0.9px;
        margin-right: -19px !important;
        width: 256px !important;
    }

    .cust_btn {
        background: #e24c3f;
        height: 40px;
        padding-top: 6px;
        border: 1px solid #e24c3f;
        color: white;
        border-radius: 2px;
        font-size: 13px;
        font-weight: bold;
        /* width: 152px; */
        /* height: 16px; */
        /* font-family: Lato; */
        font-size: 13px;
        font-weight: bold;
        font-style: normal;
        font-stretch: normal;
        /* line-height: 1.23; */
        letter-spacing: 0.9px;
        /* text-align: left; */
        /* color: #ffffff; */
        margin-right: -21px !important;
        width: 275px !important;
    }
    @media screen and (max-width: 767px){
        .colorpicker-element{
            padding-top: 0 !important;
        }
    }
</style>
