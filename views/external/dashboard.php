<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<div id="page-wrapper">
    <div class="row">
        <p>&nbsp;</p>
        <div class="col-lg-12 ">
            <div class="col-lg-3 col-md-3">
                <div class="boxtenent">
                    Number of outstanding 
                    service requests
                    <span><?= $osr ?></span>
                </div>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="boxtenent">
                    Property Agreements Expiring In Next 90 Days 
                    <span><?= $pae ?></span>
                </div>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="boxtenent">
                    Tenant Agreements Expiring In Next 90 Days
                    <span><?= $tae ?></span>
                </div>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="boxtenent">
                    Advisor Agreements Expiring In Next 90 Days
                    <span><?= $aae ?></span>
                </div>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="boxtenent">
                    Total Number of Active Tenants
                    <span><?= $tt ?></span>
                </div>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="boxtenent">
                    Total Number of properties <span><?= $tp ?></span>
                </div>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="boxtenent">
                    Total Advisor Available
                    <span><?= $ta ?></span>
                </div>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="boxtenent">
                    Total Number Of Property Units Vacant
                    <span><?= $tpv ?></span>
                </div>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="boxtenent">
                    Total Applicants Registered Today
                    <span><?= $tar ?></span>
                </div>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="boxtenent">
                    Total Property owner leads 
                    for today
                    <span><?= $tpol ?></span>
                </div>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="boxtenent">
                    Total Advisor leads for today
                    <span><?= $tal ?></span>
                </div>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="boxtenent">
                    Total Properties Listed Today
                    <span><?= $plt ?></span>
                </div>
            </div>
        </div>

        <!-- /.col-lg-12 -->
    </div>
</div>
