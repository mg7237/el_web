<?php
$this->title = 'PMS- Financial Reporting';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\grid\GridView;

//echo "<pre>";
//print_r($reporttypes);
//die;
// if(isset($_GET['LeadsTenant']['sort'])){
//   $sort=$_GET['LeadsTenant']['sort'];
// }
// else{
//   $sort='';
// }
?>
<?php
if (isset($_GET['range'])) {
    $range = $_GET['range'];
} else {
    $range = 'daily';
}

if (isset($_GET['start_date'])) {
    $startDate = $_GET['start_date'];
} else {
    $startDate = date('d-M-Y');
}

if (isset($_GET['end_date'])) {
    $endDate = $_GET['end_date'];
} else {
    $endDate = date('d-M-Y');
}
?>
<style>

</style>
<!-- <div class="col-lg-12 selectright"> -->

<div id="page-wrapper">
    <div class="row">
        <!-- =======html start here ========= -->
        <br/>
        <br/>


        <span><b>Financial Reporting<b/></span>
        <div class="col-lg-12 col-sm-12">
            <form id="fr_form" name="fr_form" action="<?php echo Url::home(true); ?>external/getreports" method="POST">
                <div class="col-lg-12 col-sm-12">
                    <div class="col-lg-3 col-md-3 col-sm-6 selectleft">
                        <div class="form-group">
                            <select id="report_name" name="report_type" class="form-control" onchange="getpropertyname()">
                                <option value="" selected>Select Report Type</option>
                                <?php foreach ($reporttypes as $rt) { ?>
                                    <option value="<?php echo $rt->id; ?>"><?php echo $rt->report_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-sm-12" id="propertyname">
                    <div class="col-lg-3 col-md-3 col-sm-6 selectleft">
                        <div class="form-group">
                            <select id="property_id" name="property_id" class="form-control">
                                <option value="" selected>Select Property</option>
                                <?php foreach ($properties as $pr) { ?>
                                    <option value="<?php echo $pr['id']; ?>"><?php echo $pr['property_name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-sm-12" id="advisorname">
                    <div class="col-lg-3 col-md-3 col-sm-6 selectleft">
                        <div class="form-group">
                            <select id="advisor_id" name="advisor_id" class="form-control">
                                <option value="" selected>Select Advisor</option>
                                <?php foreach ($advisors as $ad) { ?>
                                    <option value="<?php echo $ad['id']; ?>"><?php echo $ad['full_name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!--div class="col-lg-12 col-sm-12">
          <div class="col-lg-3 col-md-3 col-sm-6 selectleft">
             <div class="form-group">
                <select id="property_id" name="property_id" class="form-control">
                   <option value="" selected>Select Property</option>
                <?php foreach ($reporttypes as $rt) { ?>
                              <option value="<?php echo $rt->id; ?>"><?php echo $rt->report_name; ?></option>
                <?php } ?>
                </select>
             </div>
          </div>
       </div-->

                <div class="col-lg-12 col-sm-12" id="datefields">
                    <div class="col-lg-3 col-md-3 col-sm-6 selectleft">
                        <div class="form-group">
                            <div class='input-group date' id='datetimepicker1'>
                                <input type='text' class="form-control" placeholder="Start Date" name="start_date" id="start_date"/>
                                <input type='hidden' class="form-control" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 selectleft">
                        <div class="form-group">
                            <div class='input-group date' id='datetimepicker2'>
                                <input type='text' class="form-control" placeholder="End Date" name="end_date" id="end_date"/>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 col-sm-12">
                    <div class="col-lg-3 col-md-3 col-sm-6 selectleft">
                        <div class="form-group">
                            <select id="report_summary" name="report_summary" class="form-control">
                                <option value="" selected>Select Report Type</option>

                                <option value="c">Comprehensive</option>
                                <option value="s">Summarized</option>

                            </select>
                        </div>
                    </div>
                    <!--div class="col-lg-3 col-md-3 col-sm-6 selectleft">
                       <div class="form-group">
                          <select id="report_format" name="report_format" class="form-control">
                              <option value="" selected>Select Report Format</option>
                                                        
                                <option value="xls">XLS</option>
                                <option value="pdf">PDF</option>
                                
                          </select>
                       </div>
                    </div-->
                </div>

                <div class="col-lg-12 col-sm-12">
                    <div class="col-lg-3 col-md-3 col-sm-6 selectleft">
                        <div class="form-group">
                            <button type="button" class="btn btn-primary form-control" onclick="formsubmit();">Submit</button>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 selectleft">
                        <div class="form-group">
                            <button type="button" class="btn btn-primary form-control">Cancel</button>
                        </div>
                    </div>
                </div>
            </form>
            <div id="paramdiv"><br/></div>
            <div class="col-lg-12 col-sm-12">
                <div class="col-lg-12 col-md-12 col-sm-12 ">
                    <br/>
                    <div class="col-lg-12 col-md-12 col-sm-12 ">
                        <!--div class="col-lg-6 col-sm-6" style="margin-top: 15px;">
                            <span><b>Report</b></span>
                        </div-->
                        <div class="col-lg-6 col-sm-6 text-right">

                            <div class="col-lg-3 col-md-3 col-sm-6 selectleft_div" id="exportdiv">
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary form-control" id="mytblId" onclick="ExportToExcel()">Export</button>
                                </div>
                            </div>
                        </div>
                    </div>    
                    <table class="table table-bordered" id="resultdiv">

                    </table>
                </div>
            </div>
            <div id="paramdiv"></div>
        </div>
        <!-- =======html end here ========= -->
    </div>
</div>
<!-- </div> -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modalbg modalbgpopup">
        <!-- Modal content-->
        <div class="modal-content">
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#propertyname').hide();
        $('#exportdiv').hide();
        $('#advisorname').hide();
    });
    $(document).ready(function () {
        $('.btn-select-value').text('<?= $range ?>');
        $('#end_date').change(function () {
            $('#form_filter').submit();
        });
        $('#start_date').change(function () {
            $('#form_filter').submit();
        })
        $('.range_list li').click(function () {
            $('.btn-select-value').text($(this).text());
            $('.btn-select-input[name="range"]').val($(this).text())
            $('#form_filter').submit();
        })
    });
    $(function () {
        $('#datetimepicker1').datetimepicker();
        $('#datetimepicker2').datetimepicker();
    });

    function ExportToExcel() {
        var htmltable = document.getElementById('resultdiv');
        var html = htmltable.outerHTML;
        window.open('data:application/vnd.ms-excel,' + encodeURIComponent(html));
    }
</script>
<style>
    .btn-select.btn-default .btn-select-value1 {
        background-color: white;
        border: #ccc 1px solid;
    }
    .btn-select .btn-select-value1 {
        padding: 3px 12px;
        display: block;
        position: absolute;
        left: 0;
        right: 34px;
        text-align: left;
        text-overflow: ellipsis;
        overflow: hidden;
        border-top: none !important;
        border-bottom: none !important;
        border-left: none !important;
        height: 28px;
    }
    .selectleft_div {
        margin-right: 10px;
        margin-top: 15px;
        float: right;
    }
    .selectleft_div1 {
        margin-right: 0px;
        margin-top: 15px;
        float: right;
    }
</style>


<style>
    .btn-select.btn-default .btn-select-value1 {
        background-color: white;
        border: #ccc 1px solid;
    }
    .btn-select .btn-select-value1 {
        padding: 3px 12px;
        display: block;
        position: absolute;
        left: 0;
        right: 34px;
        text-align: left;
        text-overflow: ellipsis;
        overflow: hidden;
        border-top: none !important;
        border-bottom: none !important;
        border-left: none !important;
        height: 28px;
    }
</style>

<script>
    function formsubmit()
    {
        var report_name = $('#report_name').val();
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        var report_summary = $('#report_summary').val();
        var report_format = $('#report_format').val();
        var property_id = $('#property_id').val();
        var advisor_id = $('#advisor_id').val();
        var _csrf = "<?= Yii::$app->request->getCsrfToken() ?>";
        $.ajax({
            url: 'getreports',
            type: 'post',
            data: {'report_type': report_name, 'start_date': start_date, 'end_date': end_date, 'report_summary': report_summary, 'report_format': report_format, '_csrf': _csrf, 'property_id': property_id, 'advisor_id': advisor_id},
            success: function (res) {
                $('#exportdiv').show();
                $("#resultdiv").html(res);
            },
        });
    }
    function getreportfields()
    {
        var report_id = $("#report_name").val();
        var _csrf = "<?= Yii::$app->request->getCsrfToken() ?>";
        $.ajax({
            url: 'getreportparams',
            type: 'post',
            data: {'report_id': report_id, '_csrf': _csrf},
            success: function (res) {
                $("#paramdiv").html(res);
            },
        });
        //alert(report_id);
    }

    function getpropertyname()
    {
        var report_id = $("#report_name").val();
        if (report_id == 1 || report_id == 2)
        {
            $("#propertyname").show();
        } else {
            $("#propertyname").hide();
        }
        if (report_id == 3)
        {
            $("#datefields").hide();
        } else {
            $("#datefields").show();
        }
        if (report_id == 4)
        {
            $("#advisorname").show();
        } else {
            $("#advisorname").hide();
        }
    }
</script>
