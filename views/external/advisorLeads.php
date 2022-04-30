<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
?>


<div id="page-wrapper">
    <div class="row">
        <div class="col-md-3">
            <div class="parmentaddress">
                <h1>Property Advisor Leads</h1>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-lg-12">
            <!--  <div class="col-lg-3 col-sm-3 selectleft"> <a class="btn btn-default btn-select">
              
               <span class="btn-select-value">Sort by</span> <span class="btn-select-arrow glyphicon glyphicon-chevron-down"></span>
               <ul>
                 <li class="selected">Sort by</li>
                 <li>Applicant name</li>
                 <li>Applicant email</li>
                 <li>Applicant phone number</li>
                 <li>Address</li>
                 <li>Status</li>
               </ul>
               </a> </div> -->
            <div class="col-lg-7 col-sm-7 selectright mtnew">

                <form class="navbar-form navbar-search" role="search" method="post" id="filterform">
                    <input type="hidden" class="btn-select-input" id="sortby" name="sortby" value="Sort by" style="display: none">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" placeholder="Search By Advisor Name, Advisor Email, Advisor Phone Number, Address">
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-search btn-danger selecttext"> <span class="glyphicon glyphicon-search"></span> <span class="label-icon"></span> </button>
                        </div>
                    </div>
                    <input type="hidden" style="display: none;" name="pagen">
                </form>
            </div>

            <div class="col-lg-3 col-sm-3" style="float: right;">
                <button type="button" class="btn savechanges_submit" data-toggle="modal" data-target="#myModal">Create Advisor Lead</button>
            </div>
        </div>
        <div class="col-lg-12 col-sm-12">
            <table class="table table-bordered tableheadingrow" cellpadding="5">
                <thead>
                    <tr>
                        <th width="10%">Advisor Name</th>
                        <th width="11%">Advisor address </th>
                        <th width="12%">Status</th>
                        <th width="19%">Schedule date/Time</th>
                        <th width="16%">Next follow up date/time</th>
                        <th width="17%">Assigned sales person ID</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($data as $key => $value) {
                        ?>
                        <tr>
                            <td><?= $value['full_name'] ?></td>
                            <td><?= $value['address'] ?></td>
                            <td><?= $value['ref_name'] ?></td>
                            <td><?= date('d-M-Y', strtotime($value['schedule_date_time'])) ?><br><?= date('h:i:sa', strtotime($value['schedule_date_time'])) ?></td>
                            <td><?= date('d-M-Y', strtotime($value['follow_up_date_time'])) ?><br><?= date('h:i:sa', strtotime($value['follow_up_date_time'])) ?></td>
                            <td><?= $value['sales_name'] ?></td>
                        </tr>
                        <?php
                    }
                    ?>


                </tbody>
            </table>
        </div>
        <div class="col-lg-12 col-sm-12 col-md-12">
            <?php
            if ($total > 1) {
                $pages = ceil($total / 1);
                ?>
                <ul class='pagination-box'>
                    <?php
                    for ($i = 1; $i <= $pages; $i++) {
                        ?>
                        <li><?= $i ?></li>
                        <?php
                    }
                    ?>
                </ul>
                <?php
            }
            ?>
        </div>
        <!-- /.col-lg-12 -->
    </div>




    <!-- /.col-lg-12 -->
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('.btn-select-value, .btn-select-arrow').click(function () {
            $('.btn-select ul').toggle();
        });
    });

    $(document).on('click', 'a.btn-select ul li', function () {
        $('.btn-select-input').val($(this).text());
        $(this).closest('ul').hide();
        $('.btn-select-value').text($(this).text());
    });

    $(document).click(function (e) {
        if (!$('.btn-select ul li').is(e.target) && $('.btn-select ul li').has(e.target).length === 0 && !$('.btn-select-value').is(e.target) && !$('.btn-select-arrow').is(e.target))
        {
            $('.btn-select ul').hide();
        }
    });

    $(document).on('click', 'ul.pagination-box li', function () {
        $('input[name="pagen"]').val($(this).text());
    });
</script>
<style type="text/css">
    a.btn-select ul li:hover{
        background-color: grey;
        color: white;
    }
    ul.pagination-box {
        cursor: pointer;
        list-style: none;
        display: inline-flex;
        float: right;
    }

    ul.pagination-box li {
        width: 30px;
        height: 30px;
        text-align: center;
        vertical-align: middle;
        padding-top: 3px;
        border: 2px solid gray;
        margin-left: 3px;
    }
    ul.pagination-box li:hover {
        background-color: gray;
        color: white;
    }
</style>