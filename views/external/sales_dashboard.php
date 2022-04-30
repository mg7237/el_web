<?php
/* @var $this yii\web\View */

$this->title = 'PMS Dashboard';

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use app\models\PropertyTypes;
use app\models\PropertyAttributeMap;

/* echo  "<pre>";
  print_r($model );
  echo  "</pre>";
  die; */
?>  
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> 
<div id="page-wrapper">
    <div class="row">
        &nbsp;
        <!-- <div class="parmentaddress"> -->
        <!-- <h1>Total scheduled applicant</h1> -->
        <!-- </div> -->
    </div> 

    <div class="row">
        &nbsp;
        <div class="col-lg-12 ">
            <a href="external/applicants"><div class="col-lg-4">
                    <div class="boxtenent1">
                        Total scheduled applicant
                        <span><?= $applicant; ?></span>
                    </div>
                </div></a>
            <a href="external/owners"><div class="col-lg-4">
                    <div class="boxtenent1">
                        Total Scheduled Property Owner Visit For Today 
                        <span><?= $owner; ?></span>
                    </div>
                </div></a>
            <a href="external/advisors"><div class="col-lg-4">
                    <div class="boxtenent1">
                        Total Scheduled Advisor Visits For Today
                        <span><?= $adviser; ?></span>
                    </div>
                </div></div>


    </div>

    <!-- /.col-lg-12 -->
</div>

<!-- /.col-lg-12 -->
</div>

