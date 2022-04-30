<?php
/* @var $this yii\web\View */

$this->title = 'PMS search';

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use app\models\PropertyTypes;
use app\models\PropertyAttributeMap;

if (isset($_GET['property_facilities'])) {
    $property_facilities1 = $_GET['property_facilities'];
} else {
    $property_facilities1 = Array();
}
if (isset($_GET['complex_facilities'])) {
    $complex_facilities1 = $_GET['complex_facilities'];
} else {
    $complex_facilities1 = Array();
}
?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="<?php echo Url::home(true); ?>css/css/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo Url::home(true); ?>css/css/mdb.min.css" rel="stylesheet">
<link href="<?php echo Yii::$app->userdata->autoVersion(Url::home(true) . 'css/css/style.css'); ?>" rel="stylesheet">
<link href="<?php echo Yii::$app->userdata->autoVersion(Url::home(true) . 'css/css/newstyle/style1.css'); ?>" rel="stylesheet">
<link href="<?php echo Yii::$app->userdata->autoVersion(Url::home(true) . 'css/css/newstyle/style2.css'); ?>" rel="stylesheet">
<link href="<?php echo Yii::$app->userdata->autoVersion(Url::home(true) . 'css/css/newstyle/style3.css'); ?>" rel="stylesheet">
<link href="<?php echo Yii::$app->userdata->autoVersion(Url::home(true) . 'css/css/newstyle/style6.css'); ?>" rel="stylesheet">
<script type="text/javascript" src="<?php echo Yii::$app->userdata->autoVersion(Url::home(true) . 'js/script/script1.js'); ?>"></script>
<script type="text/javascript" src="<?php echo Yii::$app->userdata->autoVersion(Url::home(true) . 'js/script/script2.js'); ?>"></script>
<script src="//maps.googleapis.com/maps/api/js?key=AIzaSyDCFkcmtCqHONWxjbZn3bftmAqQ0A2YSKw&amp;libraries=places" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo Yii::$app->userdata->autoVersion(Url::home(true) . 'js/script/script3.js'); ?>"></script>
<script type="text/javascript" src="<?php echo Yii::$app->userdata->autoVersion(Url::home(true) . 'js/script/script4.js'); ?>"></script>
<script type="text/javascript" src="<?php echo Yii::$app->userdata->autoVersion(Url::home(true) . 'js/script/script5.js'); ?>"></script>
<script type="text/javascript" src="<?php echo Yii::$app->userdata->autoVersion(Url::home(true) . 'js/script/script6.js'); ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/10.1.0/nouislider.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<style>
    footer {
        background: black !important;
        margin-bottom: 0px !important;
        color: #6c757d!important;
    }

    footer h5 {
        color: #6c757d!important;
        font-size: 21px;
        padding-bottom: 5px;
        font-weight: 100;
        margin-top: 0px;
    }

    .badge-success {
        /*background-color: #00C851;*/
        background-color: #008000;
    }

    .badge-primary {
        background-color: #59abe3;
    }

    .bot-section > p > img{
        margin-left: 9px;
        margin-right: 5px;
        border-left: 1px solid #d3d3d3;
        padding-left: 8px;
        width: 30px;
        height: 20px;
    }

    .bot-section{
        margin-top: 23px;
    }

    .pagination .active span {
        padding: 7px 12px;
    }

    .pagination .disabled span {
        padding: 7px;
    }

    .pagination li a {
        padding: 6.4px 12px !important;
    }

    .modal-dialog {
        width: 100%;
        height: 100%;
        margin: 0;
        padding: 0; 
    }

    .modal-content {
        height: auto;
        min-height: 100%;
        border-radius: 0;
    }
    .search-header {
        width: 100%;
        position: relative !important;
        /*top: 35px;*/
        top:5px;
        background: #fff;
        z-index: 10;
        padding: 37px 20px;
        padding-bottom: 9px;
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        font-size: 13px;
        color: #484848;
        border-bottom: 1px solid #eee;
        margin-bottom: 40px;
    }
    .search-header select {
        padding: 7px 10px;
        border: 1px solid;
        border-radius: 16px;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        font-size: 14px;
        margin-bottom: 0 !important;
        background-color: transparent;
        position: relative;
        color: #484848;
    }
    button.btn.btn-blue-grey.waves-effect.waves-light {
        background-color: #59abe3!important;
    }
    .tag {
        padding: 5px 8px;
        text-align: center;
        border-radius: 17px;
        cursor:pointer;
        background: white;
        border: 1px solid grey;
        color: grey;

    }
    .tagactive {
        padding: 5px 8px;
        text-align: center;
        color: #fff;
        border-radius: 17px;
        background: #59abe3;
        cursor:pointer;
        margin-right: 5px;
        float: left;
    }
    /* .search-new-widget {
     width: 100%;
     position: absolute;
     top: 25px;
     z-index: 2;
     }*/
    .search-new-widget {
        width: 100%;
        /*position: absolute;*/
        top: 25px;
        z-index: 2;
        margin-bottom: 250px;
        min-height: 245px;
    }
    .filter-search-widget {
        width: 100%;
        position: absolute;
        top: 25px;
        z-index: 2;
    }
    .locality-search-widget {
        width: 100%;
        border: 1px solid gray;
        /* position: absolute;
        top: 25px;
        z-index: 2; */
    }
    .filter-pop-search {
        /* border: 1px solid red; */
    }
    .tag {
        float: right;
        /* padding-left: 15px; */
    }
    .inline-block {
        display: inline-block;
        padding-top: 4px;
        padding-bottom: 0px;
        float:left;
    }
    .filter-pop-search-btn {
        border-radius: 14px;
        border: #59abe3;
        margin-top: 4px;
        padding: 5px;
        background: #e24c3f;
        color: white;
        float: right;
        font-family: lato;
        /* margin-left: 40px; */
    }
    .inline-block1 {
        display: inline-block;
        margin-left: -36px;
    }




    @media screen and (max-width: 800px) {
        .media1 {
            width: 70% !important;
            /*display: none;*/
            float: left;
        }
        .media2 {
            width: 100% !important;
        }
    }
    #favbutton{ position:absolute; top: 10px; left:7px; z-index:10; }
    a.btn.btn-blue-grey.waves-effect.waves-light {
        background-color: #59abe3!important;
    }
    footer {
        /* background: rgba(0, 0, 0, 0) url(../images/footer_bg.jpg) repeat scroll 0 0; */
        float: left;
        padding: 20px 0 20px;
        /* padding: 161px 0 123px; */
        width: 100%;
        position: relative;
        bottom: 0;
        z-index: 9999;
        background-color: #000;
        margin-top: 6rem !important;
    }
    ul.footer-links {
        list-style: none;
        /* display: inline-flex; */
        width: 100%;
        height: auto;
        word-wrap: break-word;
    }
    .footer-links li a {
        color: #949494;
        font-size: 13px;
        text-decoration: none;
    }
    .footer-links li {
        width: 25%;
        float: left;
        padding: 5px 50px;
        /* text-align: center; */
    }
    .copyright {
        color: #ffffff;
        float: left;
        font-family: "Open Sans",sans-serif;
        font-size: 13px;
        padding: 7px;
        text-align: center;
        width: 100%;
        background: rgba(0, 0, 0, 0) url(../images/copyright_bg.jpg) repeat scroll 0 0;
        background: #333;
        z-index: 9999;
        position: absolute;
        bottom: 0;
    }
    .text-set {
        float: left;
        color: white !important;
    }
    .text-set > a {
        color: white;
        font-size: 12px !important;
    }
    .no-repeat{
        padding-top: 0px;
        padding-bottom: 0px;
    }
    .btn_mycolor{
        background-color: rgb(89, 171, 227) !important;
        color: #fff!important !important;
    }
    a.nav-link.small.waves-effect.waves-light {
        margin-top: 10px;
    }
    a.navbar-brand {
        margin-top: 12px;
    }
    ul#header_menu_id {
        margin-top: -14px ;
    }
    .search-new-widget header .header-search {
        /*width: 50%;*/
        height: 42px;
        float: left;
        padding: 2px 8px;
        padding-left: 35px;
        position: relative;
        overflow: hidden;
        cursor: pointer;
        vertical-align: top;
        border: 1px solid #dbdbdb;
        border-radius: 3px;
        box-shadow: 0 1px 3px 0px rgba(0, 0, 0, 0.08);
        overflow-y: scroll;
    }
    @media (min-width: 70.063em) and (max-width: 120em){
        .search-header{
            /*padding-top: 5px !important;*/

        } 
        .bot-section {
            /*margin-top: 71px;*/
            background-color: #fafafa;
            width: 100% !important;
            width: 100% !important;
            padding-left: 8px;
            padding-right: 6px;
            height: 41px;
            /* margin-top: 15px; */
        }

        .carousel-title.ng-binding {
            /* margin-top: 20px; */
            margin-top: 5px;
        }	
        .cust-marp{
            margin-top: -20px;
        }
        .house-label.col-6.div-boader2 {
            padding-left: 0px;
        }

    } 
    @media screen and (max-width: 767px){
        .cust-marp{
            margin-top: -20px;
        }
    }
    /*@media  (max-width:75em){
        .filter-tab{
            margin: 50px;
        }
    }*/

    .col-8-12.selector-wrapper {
        margin-top: 4px;
        width: 160px;
    }
    span#filter_btn {
        margin-top: -12px;
        cursor: pointer;
        width: 160px;
        margin-left: -9px;
    }
    div#_tag-label {
        padding: 16px;
    }
    div#_tag-label1 {
        background-color: #e24c3f!important;
        border-color: #e24c3f!important;
    }
    span.tag-label1 {
        /* padding: 15px;
        margin-right: 15px; */
    }
    .tag {
        margin-right: 5px;
    }
    /*.search-header.col-md-6.col-sm-12 {
        max-height: 847px!important;
        overflow: auto;
    } */
    /* @media screen and (max-width: 800px)  */
    @media screen and  (max-width: 1024px) {
        /* ADD YOUR CSS ADJUSTMENTS BELOW HERE */
        .search-new-widget header .header-search {
            width: 56%;
            height: 42px;
            float: left;
            padding: 2px 8px;
            padding-left: 35px;
            position: relative;
        }
        .header-nav-right{
            display:block !important;
        }
    }

    .filteroption {
        background-color: #59abe3 !important;
        color: white !important;
        border: none !important;
    }

    input#min_amount {
        font-family: "Open Sans", sans-serif;
        font-size: 12px;
        color: #b1b1b1;
        padding: 5px;
        font-weight: bold;
    }

    input#max_amount {
        font-family: "Open Sans", sans-serif;
        font-size: 12px;
        color: #b1b1b1;
        padding: 5px;
        font-weight: bold;
    }
    .dollartextnewbottom span {
        float: left;
        font-family: "Open Sans",sans-serif;
        font-size: 12px;
        line-height: 34px;
        font-weight: bold;
    }
    .footer-bottom{
        top:40px;
        bottom: 0;
        width: 100%;
        z-index:1000;
    }

    @media screen and (max-width:480px){

        .city-dropdown{
            width:100% !important;
        }

        .mobile{
            display:block;
        }

        .filter-tab{
            margin-bottom:0px !important;
        }
        .second-sec{
            margin-bottom:5px;
        }

        .search-header .breadcrumb-header .header-tittle h1{
            text-align: center;
            margin-top: 10px
        }

        .bot-section p img{
            margin-right:5px;
        }
        .carousel-title{padding-left:0px;}

        /*.second-sec .sort-map {width:100% !important;}
        .second-sec > div{ width: 95% !important; }*/

        .navbar-toggle{background: #000;}
        .navbar-toggle .icon-bar{background: #fff;}
        .sort-map{padding-right:0px; margin-bottom:10px;}
        .breadcrumb-header.ellipsify{
            padding-top: 0px; 
        }
    }


    @media screen and (max-width:767px){
        .div-boader1
        {
            border-right: 1px solid #cfcbcb;

            border-bottom: none;
        }
        .div-boader2
        {
            border-left: 1px solid #cfcbcb;

            border-top: none;
        }
    }
    @media screen and (max-width:1024px){
        .filter-tab{
            margin-bottom:10px;
        }
        .second-sec{
            margin-bottom:5px;
        }
        .navbar{top:0px !important; right:10px !important; margin-right: -30px !important;}
        #map{
            display:none;
        }
        .search-new-widget header .city-dropdown {
            width: 100% !important;
            margin: 9px 0px !important;
            display: none;
        }
        .search-new-widget header .header-search {
            width: 100% !important;
            display: none;
        }
        header [class*='col-'] {
            padding-left: 0px !important;
            padding-right: 0
        }
        .search-header
        {
            /*top: 100px;*/
            top: 5px;
        }
        .search-new-widget .tag
        {
            /*width:100%;*/
            border-radius: 0px;
        }
        .div-boader2
        {
            padding-left: 0px;
        }
        .col-md-6
        {
            max-width: 100% !important;
            width:100%;
        }

        .second-sec .sort-map{   
            width:100% ;
            float: left;
        }
        .second-sec > div {margin-right: 10px; width:35%; margin-right: 10px;}

        .carousel-title >p>b{
            word-wrap: break-word;
        }

        .header-nav-right{
            display:block !important;
        }
        .search-header .selector-wrapper
        {
            margin-right: 0px !important;
            width: 70% !important;
        }

        .breadcrumb-header.ellipsify
        {
            /*padding-top:0px !important;*/
            padding-left: 0px;
        }
        .mr-auto {margin-top: 10px !important;}	
        .rightlistbtns{width:100% !important;padding:8px !important;height:40px !important;font-size: 16px;font-weight:bold;margin: 0px 0px 11px 4px !important;}	
    }


    .search-header .selector-wrapper
    {
        margin-right: 39px;
        width: 46%;
    }
    .breadcrumb-header.ellipsify {   
        margin-left: 0px;
        padding-top:15px;

    }

    .navbar{
        padding:0px;
        min-height: 0px; 
        margin-bottom: 0px;
        box-shadow:none;
    }

    .second-sec > div{ margin-right:0px;}
    .search-header > .row{ margin-bottom:10px; }
    header nav{
        margin-top: 0px;
    }
    .gm-style{
        top: 40px !important;
    }

    .navbar-nav>li>a{
        padding: 6px 17px;;
    }

    /*.tag{ border-radius: 0px !important; }*/

    footer{margin-top: 0rem !important;}
    .tag-label {width:auto !important; margin:0px 10px;}
    .search-new-widget .tag {padding: 14px 9px 18px 29px !important;}
    .second-sec > div{width:100%;}
    .second-sec{overflow: hidden;}
    .search-header .selector-wrapper{
        margin-right: 0px; 
        width: 80%;
        padding-top: 0px;
    }
    .header-search .tag{
        padding:7px  2px 18px 19px !important    
    }

    .header-search .tag-label{
        margin: 0px;
    }
    nav.navbar.navbar-expand-lg.navbar-dark.indigo.fixed-top.no-repeat {
        background: white !important;
    }
</style>


<script>
    function initialize() {
        var options = {
            componentRestrictions: {country: "IN"}
        };
        var input = document.getElementById('searchTextField');
        var autocomplete = new google.maps.places.Autocomplete(input, options);
        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            var place = autocomplete.getPlace();
            document.getElementById('properties-lat').value = place.geometry.location.lat();
            document.getElementById('properties-lon').value = place.geometry.location.lng();
        });
    }
    google.maps.event.addDomListener(window, 'load', initialize);
    element = window.opener.document.getElementById("exampleModal");
    if (window.opener.document.getElementById("exampleModal")) {
        //alert("hi");
    }
</script>
<div class="search-new-widget">
    <div>	
        <!-- header -->
        <header>
            <div class="grid">               
                <div class="header-logo">
                    <a href="<?php echo Url::home(true); ?>" target="_self">
                        <img src="../images/newlogo1.png">
                    </a>
                </div>
                <div class="header-nav-right" >			
                    <nav class="navbar navbar-expand-lg navbar-dark indigo fixed-top no-repeat">
                        <!-- Navbar brand -->

                        <!-- Collapse button -->
                        <!--<div class="homebtn">-->

                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                        <!--</div>-->

                        <!-- Collapsible content -->
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <!-- Links -->
                            <ul class="navbar-nav mr-auto">
                                <li class="nav-item ">
                                    <a class="btn btn-primary btn_mycolor rightlistbtns" href="<?php echo Url::home(true); ?>site/page?slug=faq-s">FAQ</a>
                                </li>
                                <?php if (Yii::$app->user->isGuest) { ?>
                                    <li class="nav-item ">
                                        <a class="btn btn-primary btn_mycolor rightlistbtns" href="<?php echo Url::home(true); ?>site/login">Sign In</a>
                                    </li>
                                <?php } ?>
                                <?php
                                if (!Yii::$app->user->isGuest) {
                                    ?>
                                    <li class="nav-item ">
                                        <?php if (Yii::$app->userdata->usertype == 6) { ?>
                                            <a class="btn btn-blue-grey" href="<?php echo Url::home(true); ?>external/myprofile">My Profile</a>
                                        <?php } else if (Yii::$app->userdata->usertype == 2) { ?>
                                            <a class="btn btn-blue-grey" href="<?php echo Url::home(true); ?>site/myprofile">My Profile</a>
                                        <?php } else if (Yii::$app->userdata->usertype == 3) { ?>
                                            <a class="btn btn-blue-grey" href="<?php echo Url::home(true); ?>site/myprofile">My Profile</a>
                                        <?php } else if (Yii::$app->userdata->usertype == 4) { ?>
                                            <a class="btn btn-blue-grey" href="<?php echo Url::home(true); ?>site/myprofile">My Profile</a>
                                        <?php } else if (Yii::$app->userdata->usertype == 5) { ?>
                                            <a class="btn btn-blue-grey" href="<?php echo Url::home(true); ?>advisers/myprofile">My Profile</a>
                                        <?php } else if (Yii::$app->userdata->usertype == 7) { ?>
                                            <a class="btn btn-blue-grey" href="<?php echo Url::home(true); ?>external/myprofile">My Profile</a>
                                        <?php } ?>
                                    </li>
                                <?php }
                                ?>
                            </ul>
                            <!-- Links -->
                        </div>
                        <!-- Collapsible content -->
                    </nav>
                </div>
                <div class="city-dropdown">
                    <div class="header-selector">
                        <select id="top-city-dropdown">
                            <?php $cityModels = \app\models\Cities::find()->where(['status' => 1])->all(); ?>
                            <?php foreach ($cityModels as $cityModel) { ?>
                                    <!--<option <?= ($cityModel->id == Yii::$app->request->get('city')) ? 'selected' : '' ?> value="<?= $cityModel->id; ?>" selected> Rent in <?= $cityModel->city_name; ?></option>-->
                            <?php } ?>
                            <option value="" selected> Rent in <?= Yii::$app->userdata->getCityNameById(Yii::$app->request->get('city')) ?></option>
                        </select>
                    </div>
                </div>


                <form action="<?php echo Url::home(true) . 'site/search?' . Yii::$app->request->queryString; ?>" method="GET" id="filterform">
                    <div class="header-search" style="background-color: #fafafa;">
                        <?php if ($searcharea != '') { ?>
                            <div>
                                <div class="tag filteroption" style="padding-top:7px !important;">
                                    <span class="tag-label"><?php echo $searcharea; ?></span>
                                    <span class="tag-close" onclick="removelocation();"> × </span>
                                </div>
                            </div>

                            Flat for families 

                        <?php } ?>
                        <input type="hidden" name="search" value="<?php echo Yii::$app->getRequest()->getQueryParam('search'); ?>" id="formsearch"/>
                        <input type="hidden" name="city" value="<?php echo Yii::$app->getRequest()->getQueryParam('city'); ?>" id="formcity"/>
                        <input type="hidden" name="lat" value="<?php echo Yii::$app->getRequest()->getQueryParam('lat'); ?>" id="formlat"/>
                        <input type="hidden" name="lon" value="<?php echo Yii::$app->getRequest()->getQueryParam('lon'); ?>" id="formlon"/>
                        <input type="hidden" name="radius" value="<?php echo Yii::$app->getRequest()->getQueryParam('radius'); ?>" id="formradius"/>
                        <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>" id="csrf"/>
                        <?php
                        if ($propertytypename != '') {
                            if (is_array(Yii::$app->getRequest()->getQueryParam('property_type'))) {
                                $property_type = implode(',', Yii::$app->getRequest()->getQueryParam('property_type'));
                                $property_type = trim($property_type, ',');
                            }
                            ?>
                            <div class="inline-block"style="float: left;">
                                <div class="tag">
                                    <span class="tag-label"><?php echo $propertytypename; ?></span>
                                    <span class="tag-close" onclick="removepropertytype();"> × </span>
                                </div>
                            </div>
                            <input type="hidden" name="property_type" value="<?php echo $property_type; ?>" id="formprtype"/>
                        <?php } ?>
                        <?php if ($flat_type != '') { ?>
                            <div class="inline-block">
                                <div class="tag">
                                    <span class="tag-label"><?php
                                        if ($flat_type == 0) {
                                            echo "Semi Furnished";
                                        } else {
                                            echo "Furnished";
                                        }
                                        ?></span>
                                    <span class="tag-close" onclick="removeflattype();"> × </span>
                                </div>
                            </div>
                            <input type="hidden" name="flat_type" value="<?php echo $flat_type; ?>" id="formflat"/>
                        <?php } ?>
                        <?php if ($min_amount != '' && $max_amount != '') { ?>
                            <div class="inline-block" id="priceval"><div class="tag filteroption"><span class="tag-label">Price : <?php echo $min_amount; ?> - <?php echo $max_amount; ?></span><span class="tag-close" onclick="removeamount();"> × </span></div></div>
                            <input type="hidden" name="min_amount" value="<?php echo $min_amount; ?>" id="formminamt"/> 
                            <input type="hidden" name="max_amount" value="<?php echo $max_amount; ?>" id="formmaxamt"/>
                        <?php } ?>
                        <?php if ($min_area != '' && $max_area != '') { ?>
                            <div class="inline-block" id="areaval"><div class="tag filteroption" ng-if="!search.isArray(value)" ng-class=""><span class="tag-label">Area : <?php echo $min_area; ?> - <?php echo $max_area; ?></span><span class="tag-close" onclick="removearea();"> × </span></div></div>
                            <input type="hidden" name="min_area" value="<?php echo $min_area; ?>" id="formminarea"/> 
                            <input type="hidden" name="max_area" value="<?php echo $max_area; ?>" id="formmaxarea"/>
                        <?php } ?>
                        <?php if ($min_radius != '' && $max_radius != '') { ?>
                            <div class="inline-block" id="radiusval"><div class="tag filteroption"><span class="tag-label">Radius : <?php echo $min_radius; ?> - <?php echo $max_radius; ?></span><span class="tag-close" onclick="removeradius();"> × </span></div></div>
                            <input type="hidden" name="min_radius" value="<?php echo $min_radius; ?>" id="formminrad"/> 
                            <input type="hidden" name="max_radius" value="<?php echo $max_radius; ?>" id="formmaxrad"/>
                            <input type="hidden" name="radius" value="5"/>
                        <?php } ?>
                    </div>
                </form>



            </div>
        </header>
        <!-- //start -->
        <div class="col-md-12">
            <div class="search-header col-md-6 col-sm-12">
                <div class="row">

                    <div class="col-md-9 col-sm-12 filter-tab" style="padding-left: 0px;padding-right: 0px;">



                        <!--<div class="col-md-9 col-sm-9">
                            <!--<span class="col-4-12">
                                <span class="sort-text">Sort By:</span> 
                            </span>-->

                        <form action="<?php echo Url::home(true) . 'site/search?' . Yii::$app->request->queryString; ?>" method="GET">
                            <div class="selector-wrapper inline-block">
                                <select class="selecter_basic ng-pristine ng-valid ng-touched changeorder" name="sort_flat">
                                    <option value="">Sort By</option>
                                    <option <?= Yii::$app->getRequest()->getQueryParam('sort_flat') == 'name' ? ' selected="selected"' : ''; ?> value="name">A to Z</option>
                                    <option <?= Yii::$app->getRequest()->getQueryParam('sort_flat') == 'rent_low' ? ' selected="selected"' : ''; ?> value="rent_low">Rent - Low to High</option>
                                    <option <?= Yii::$app->getRequest()->getQueryParam('sort_flat') == 'rent_high' ? ' selected="selected"' : ''; ?> value="rent_high">Rent - High to Low</option>
                                    <option <?= Yii::$app->getRequest()->getQueryParam('sort_flat') == 'area_low' ? ' selected="selected"' : ''; ?> value="area_low">Size - Low to High</option>
                                    <option <?= Yii::$app->getRequest()->getQueryParam('sort_flat') == 'area_high' ? ' selected="selected"' : ''; ?> value="area_high">Size - High to Low</option>
                                    <option <?= Yii::$app->getRequest()->getQueryParam('sort_flat') == 'bhk_asc' ? ' selected="selected"' : ''; ?> value="bhk_asc">Asc no of Rooms</option>
                                    <option <?= Yii::$app->getRequest()->getQueryParam('sort_flat') == 'bhk_desc' ? ' selected="selected"' : ''; ?> value="bhk_desc">Desc no of Rooms</option>
                                </select>
                            </div>
                            <?= Html::hiddenInput('search', Yii::$app->getRequest()->getQueryParam('search')); ?>
                            <?= Html::hiddenInput('city', Yii::$app->getRequest()->getQueryParam('city')); ?>
                            <?= Html::hiddenInput('lat', Yii::$app->getRequest()->getQueryParam('lat')); ?>
                            <?= Html::hiddenInput('lon', Yii::$app->getRequest()->getQueryParam('lon')); ?>
                            <?= Html::hiddenInput('radius', Yii::$app->getRequest()->getQueryParam('radius')); ?>
                        </form>
                        <!--</div>

                        <!--<div class="col-md-3 col-sm-3">-->
                        <div class="tag" id="_tag-label" style="border-radius:0px !important;">
                            <span class="tag-label" id="filter_btn" onclick="openfilter();">Filter</span>
                        </div>
                        <!--</div>-->




                    </div>
                    <div class="col-md-3 col-sm-12 breadcrumb-header ellipsify">
                        <div class="header-tittle ellipsify">
                            <h1 class="ng-binding">
                                <b class="ng-binding">
                                    Result 
                                    <?php //echo count($model);  ?>
                                    <?php
                                    if (isset($_GET['page'])) {
                                        if ($_GET['page'] < 2) {
                                            echo '1 to ';
                                            echo count($model);
                                            echo ' of ';
                                            echo $total_pages;
                                        } else {
                                            echo (($_GET['page'] * $per_page) + 1) - $per_page;
                                            echo ' to ';
                                            echo count($model) + $per_page;
                                            echo ' of ';
                                            echo $total_pages;
                                        }
                                    } else {
                                        echo '1 to ';
                                        echo count($model);
                                        echo ' of ';
                                        echo $total_pages;
                                    }
                                    ?>
                                </b> 
                                <!-- Result of --> <?php //echo $total_pages   ?>
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <?= $pagination; ?>
                    </div>
                </div>
                <?php
                $properties = PropertyTypes::find()->all();
                $j = 0;
                foreach ($model as $property) {
                    $allPropertyImages = Yii::$app->userdata->getPropertyImages($property->id);
                    //echo "<pre>";print_r($allPropertyImages);echo "</pre>";die;
                    ?>
                    <div class="row" style="background: #ffffff !important;background-color: white;border-bottom: 15px solid #fafafa;">
                        <div class="house-label col-md-6 col-sm-12 col-xs-12 div-boader1" style="padding-left: 4px;padding-right: 4px;padding-top: 4px;">
                            <!--Carousel Wrapper-->
                            <div id="carousel-example-<?php echo $j; ?>" class="carousel slide carousel-fade" data-ride="carousel">
                                <?php
                                $modelFav = Yii::$app->userdata->getFavData($property->id);
                                if (!Yii::$app->user->isGuest) {
                                    ?>
                                    <div id="favbutton">
                                        <a data-property="<?= $property->id; ?>" data-id="<?= $modelFav == 0 ? 1 : 0; ?>" title="<?= $modelFav == 0 ? 'Click to add in Favourite' : 'Click to delete from Favourite'; ?>" class="wishlistsearch" style="cursor:pointer;"> <img src="<?php echo Url::home(true); ?>images/<?= $modelFav == 0 ? 'dil_img.png' : 'dil_shape.png' ?>" alt=""> </a> 
                                    </div>
                                <?php } else { ?>
                                    <div id="favbutton">
                                        <a data-property="<?= $property->id; ?>" data-id="<?= $modelFav == 0 ? 1 : 0; ?>" title="<?= $modelFav == 0 ? 'Click to add in Favourite' : 'Click to delete from Favourite'; ?>" class="wishlistsearch" style="cursor:pointer;"> <img src="<?php echo Url::home(true); ?>images/dil_img.png" alt=""> </a> 
                                    </div>
                                <?php } ?>


                                <div class="carousel-inner col-md-12 col-sm-12 col-xs-12" role="listbox" style="padding-left: 0px;">
                                    <?php
                                    if (count($allPropertyImages) > 0) {
                                        $i = 0;
                                        foreach ($allPropertyImages as $images) {
                                            $url = Url::toRoute(['site/view', 'id' => $property->id]);
                                            ?>
                                            <div class="carousel-item <?php
                                            if ($i == 0) {
                                                echo "active";
                                            }
                                            ?>">
                                                <div class="view hm-black-light">
                                                    <?php
                                                    if (!empty($images)) {
                                                        $imgurl = $images->image_url;
                                                    } else {
                                                        $imgurl = '';
                                                    }
                                                    ?>
                                                    <a href="<?= $url ?>" target="_blank">
                                                        <span class="badge badge-pill <?= (Yii::$app->userdata->getManagedById($property->managed_by) == 2) ? "badge-success" : "badge-primary" ?> " style="position: absolute;top: 5px;z-index: 1;right: 5px;font-weight: normal;padding: 8px 12px 8px 12px; box-shadow: none;"><?= Yii::$app->userdata->getManagedByString(($property->managed_by)) ?> Managed</span>
                                                        <span class="badge badge-pill badge-primary" style="position: absolute; bottom: 5px; z-index: 1; left: 5px; color: white; font-weight: normal; padding: 8px 12px 8px 12px; box-shadow: none;">For&nbsp;<?= Yii::$app->userdata->getAvailableForString(($property->gender)) ?></span>
                                                        <img class="d-block w-100 img-caro" src="../<?php echo $imgurl; ?>">
                                                    </a>

                                                    <!--div class="mask"></div-->
                                                </div>
                                            </div>
                                            <?php
                                            $i++;
                                        }
                                    }
                                    ?>
                                </div>
                                <!--/.Slides-->
                                <!--Controls-->
                                <?php if (count($allPropertyImages) > 1) { ?>
                                    <a class="carousel-control-prev" href="#carousel-example-<?php echo $j; ?>" role="button" data-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="carousel-control-next" href="#carousel-example-<?php echo $j; ?>" role="button" data-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                <?php } ?>
                                <!--/.Controls-->
                            </div>
                            <!--/.Carousel Wrapper-->
                        </div>
                        <?php $propertyprices = Yii::$app->userdata->getPropertyRentListing($property->id);
                        ?>

                        <div class="house-label col-md-6 col-sm-12 col-xs-12 div-boader2">



                            <div class="carousel-title ng-binding " style="padding-left: 10px; ">
                                <p style="overflow: hidden;height: 20px; word-break: break-all;"> <b><?php echo $property->property_name; ?></b></p>
                                <p style="height: 55px;overflow: hidden;"> <i class="fa fa-home"></i><span>&nbsp;<?php echo $property->address_line_1; ?> , <?php echo $property->address_line_2; ?></span></p>
                                <p class="cust-marp"><span>Rent &nbsp;:&nbsp; </span><span><b>&#x20B9; &nbsp;<?= Yii::$app->userdata->getFormattedMoney(round($propertyprices->rent)); ?></b> <i class="fa fa-rupee-sign"></i>/ Month</span></p>
                                <p><span>Maintenance &nbsp;:&nbsp; </span><span><b>&nbsp;<?= ($propertyprices->maintenance == '0' || $propertyprices->maintenance == '') ? 'Included' : '&#x20B9; ' . Yii::$app->userdata->getFormattedMoney(round($propertyprices->maintenance)); ?></b> </span></p>
                                <p><span>Deposit &nbsp;:&nbsp; </span><span><b>&#x20B9; &nbsp;<?= Yii::$app->userdata->getFormattedMoney(round($propertyprices->deposit)) ?></b></span></p>
                            </div>


                            <div class="bot-section" style="padding-top: 9px;padding: 0px !important;">
                                <p style="display: flex; align-items: center; text-align: center; line-height: 15px;">
                                    <?php if (!empty(Yii::$app->request->get('property_type')[0]) && Yii::$app->request->get('property_type')[0] == 3) { ?>
                                            <img src="../images/icons/assets_7-list-page_2018-02-19/ic-sqft.png">&nbsp;<span>  <?php echo $property->flat_area; ?> sft</span> <!--<span> &nbsp;| &nbsp;</span>-->
                                            <img src="../images/icons/assets_7-list-page_2018-02-19/ic-bedrooms.png">&nbsp;<span><?php echo $property->flat_bhk; ?> Bedrooms</span> <!--<span>&nbsp;|&nbsp;</span>-->
                                    <?php } else { ?>
                                            <img src="../images/icons/assets_7-list-page_2018-02-19/ic-bedrooms.png">&nbsp;<span><?php echo Yii::$app->userdata->getTotalRoomsInProperty($property->id); ?> Bedrooms</span> <!--<span>&nbsp;|&nbsp;</span>-->
                                    <?php } ?>
                                    <?php
                                    if ($property->flat_type == 0) {
                                        $flattype = "Semi Furnished";
                                    } else {
                                        $flattype = "Furnished";
                                    }
                                    ?>
                                    <img src="../images/icons/assets_7-list-page_2018-02-19/ic-furnished.png">&nbsp;<span><?php echo $flattype; ?></span>
                                </p>
                            </div>


                        </div>
                    </div>
                    <?php
                    $j++;
                }
                ?>

                <!-- ------------------ ------------------ -------------------- -------------------- ------------- -->
                <!-- ------------------ ------------------ -------------------- -------------------- ------------- -->
                <!-- ------------------ ------------------ -------------------- -------------------- ------------- -->
                <!-- ------------------ ------------------ -------------------- -------------------- ------------- -->
                <!-- ------------------ ------------------ -------------------- -------------------- ------------- -->
                <!-- ------------------ Below code is for maintaining FOOTER'S position. STARTS HERE! ------------ -->
                <!-- ------------------ ------------------ -------------------- -------------------- ------------- -->
                <!-- ------------------ ------------------ -------------------- -------------------- ------------- -->
                <!-- ------------------ ------------------ -------------------- -------------------- ------------- -->
                <!-- ------------------ ------------------ -------------------- -------------------- ------------- -->
                <!-- ------------------ ------------------ -------------------- -------------------- ------------- -->

                <?php
                if (count($model) == 1) {
                    $properties = PropertyTypes::find()->all();
                    $j = 0;
                    foreach ($model as $property) {
                        $allPropertyImages = Yii::$app->userdata->getPropertyImages($property->id);
                        //echo "<pre>";print_r($allPropertyImages);echo "</pre>";die;
                        ?>
                        <div class="row" style="visibility: hidden; background: #ffffff !important; background-color: white; border-bottom: 15px solid #fafafa;">
                            <div class="house-label col-md-6 col-sm-12 col-xs-12 div-boader1" style="padding-left: 4px;padding-right: 4px;padding-top: 4px;">
                                <!--Carousel Wrapper-->
                                <div id="carousel-example-<?php echo $j; ?>" class="carousel slide carousel-fade" data-ride="carousel">
                                    <?php
                                    $modelFav = Yii::$app->userdata->getFavData($property->id);
                                    if (!Yii::$app->user->isGuest) {
                                        ?>
                                        <div id="favbutton">
                                            <a data-property="<?= $property->id; ?>" data-id="<?= $modelFav == 0 ? 1 : 0; ?>" title="<?= $modelFav == 0 ? 'Click to add in Favourite' : 'Click to delete from Favourite'; ?>" class="wishlistsearch" style="cursor:pointer;"> <img src="<?php echo Url::home(true); ?>images/<?= $modelFav == 0 ? 'dil_img.png' : 'dil_shape.png' ?>" alt=""> </a> 
                                        </div>
                                    <?php } else { ?>
                                        <div id="favbutton">
                                            <a data-property="<?= $property->id; ?>" data-id="<?= $modelFav == 0 ? 1 : 0; ?>" title="<?= $modelFav == 0 ? 'Click to add in Favourite' : 'Click to delete from Favourite'; ?>" class="wishlistsearch" style="cursor:pointer;"> <img src="<?php echo Url::home(true); ?>images/dil_img.png" alt=""> </a> 
                                        </div>
                                    <?php } ?>


                                    <div class="carousel-inner col-md-12 col-sm-12 col-xs-12" role="listbox" style="padding-left: 0px;">
                                        <?php
                                        if (count($allPropertyImages) > 0) {
                                            $i = 0;
                                            foreach ($allPropertyImages as $images) {
                                                $url = Url::toRoute(['site/view', 'id' => $property->id]);
                                                ?>
                                                <div class="carousel-item <?php
                                                if ($i == 0) {
                                                    echo "active";
                                                }
                                                ?>">
                                                    <div class="view hm-black-light">
                                                        <?php
                                                        if (!empty($images)) {
                                                            $imgurl = $images->image_url;
                                                        } else {
                                                            $imgurl = '';
                                                        }
                                                        ?>
                                                        <a href="<?= $url ?>" target="_blank">
                                                            <span class="badge badge-pill <?= (Yii::$app->userdata->getManagedById($property->managed_by) == 2) ? "badge-success" : "badge-primary" ?> " style="position: absolute;top: 5px;z-index: 1;right: 5px;font-weight: normal;padding: 8px 12px 8px 12px; box-shadow: none;"><?= Yii::$app->userdata->getManagedByString(($property->managed_by)) ?> Managed</span>
                                                            <span class="badge badge-pill badge-primary" style="position: absolute; bottom: 5px; z-index: 1; left: 5px; color: white; font-weight: normal; padding: 8px 12px 8px 12px; box-shadow: none;">For&nbsp;<?= Yii::$app->userdata->getAvailableForString(($property->gender)) ?></span>
                                                            <img class="d-block w-100 img-caro" src="../<?php echo $imgurl; ?>">
                                                        </a>

                                                        <!--div class="mask"></div-->
                                                    </div>
                                                </div>
                                                <?php
                                                $i++;
                                            }
                                        }
                                        ?>
                                    </div>
                                    <!--/.Slides-->
                                    <!--Controls-->
                                    <?php if (count($allPropertyImages) > 1) { ?>
                                        <a class="carousel-control-prev" href="#carousel-example-<?php echo $j; ?>" role="button" data-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                        <a class="carousel-control-next" href="#carousel-example-<?php echo $j; ?>" role="button" data-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    <?php } ?>
                                    <!--/.Controls-->
                                </div>
                                <!--/.Carousel Wrapper-->
                            </div>
                            <?php $propertyprices = Yii::$app->userdata->getPropertyRentListing($property->id); ?>

                            <div class="house-label col-md-6 col-sm-12 col-xs-12 div-boader2">



                                <div class="carousel-title ng-binding " style="padding-left: 10px; ">
                                    <p style="overflow: hidden;height: 20px; word-break: break-all;"> <b><?php echo $property->property_name; ?></b></p>
                                    <p style="height: 55px;overflow: hidden;"> <i class="fa fa-home"></i><span>&nbsp;<?php echo $property->address_line_1; ?> , <?php echo $property->address_line_2; ?></span></p>
                                    <p class="cust-marp"><span>Rent &nbsp;:&nbsp; </span><span><b>&#x20B9; &nbsp;<?= Yii::$app->userdata->getFormattedMoney(round($propertyprices->rent)); ?></b> <i class="fa fa-rupee-sign"></i>/ Month</span></p>
                                    <p><span>Maintenance &nbsp;:&nbsp; </span><span><b>&nbsp;<?= ($propertyprices->maintenance == '0' || $propertyprices->maintenance == '') ? 'Included' : '&#x20B9; ' . Yii::$app->userdata->getFormattedMoney(round($propertyprices->maintenance)); ?></b> </span></p>
                                    <p><span>Deposit &nbsp;:&nbsp; </span><span><b>&#x20B9; &nbsp;<?= Yii::$app->userdata->getFormattedMoney(round($propertyprices->deposit)) ?></b></span></p>
                                </div>


                                <div class="bot-section" style="padding-top: 9px;padding: 0px !important;">
                                    <p style="display: flex; align-items: center; text-align: center; line-height: 15px;">
                                        <?php if (!empty(Yii::$app->request->get('property_type')[0]) && Yii::$app->request->get('property_type')[0] == 3) { ?>
                                                <img src="../images/icons/assets_7-list-page_2018-02-19/ic-sqft.png">&nbsp;<span>  <?php echo $property->flat_area; ?> sft</span> <!--<span> &nbsp;| &nbsp;</span>-->
                                                <img src="../images/icons/assets_7-list-page_2018-02-19/ic-bedrooms.png">&nbsp;<span><?php echo $property->flat_bhk; ?> Bedrooms</span> <!--<span>&nbsp;|&nbsp;</span>-->
                                        <?php } else { ?>
                                                <img src="../images/icons/assets_7-list-page_2018-02-19/ic-bedrooms.png">&nbsp;<span><?php echo Yii::$app->userdata->getTotalRoomsInProperty($property->id); ?> Bedrooms</span> <!--<span>&nbsp;|&nbsp;</span>-->
                                        <?php } ?>
                                        <?php
                                        if ($property->flat_type == 0) {
                                            $flattype = "Semi Furnished";
                                        } else {
                                            $flattype = "Furnished";
                                        }
                                        ?>
                                        <img src="../images/icons/assets_7-list-page_2018-02-19/ic-furnished.png">&nbsp;<span><?php echo $flattype; ?></span>
                                    </p>
                                </div>


                            </div>
                        </div>
                        <?php
                        $j++;
                    }
                }
                ?>


                <!-- ------------------ ------------------ -------------------- -------------------- ------------- -->
                <!-- ------------------ ------------------ -------------------- -------------------- ------------- -->
                <!-- ------------------ ------------------ -------------------- -------------------- ------------- -->
                <!-- ------------------ ------------------ -------------------- -------------------- ------------- -->
                <!-- ------------------ ------------------ -------------------- -------------------- ------------- -->
                <!-- ------------------ Above code is for maintaining FOOTER'S position. ENDS HERE!  ------------- -->
                <!-- ------------------ ------------------ -------------------- -------------------- ------------- -->
                <!-- ------------------ ------------------ -------------------- -------------------- ------------- -->
                <!-- ------------------ ------------------ -------------------- -------------------- ------------- -->
                <!-- ------------------ ------------------ -------------------- -------------------- ------------- -->
                <!-- ------------------ ------------------ -------------------- -------------------- ------------- -->

                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <?= $pagination; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <!--Google map-->
                <div style="position: fixed;width: 49%;">
                    <div id="map" class="z-depth-1" style="height: 480px"></div>
                </div>
            </div>
        </div>
        <!-- //end -->
    </div>
    <br/>
    <br/>

    <!--copyright-->



</div>

<?php if ((count($model)) < 1) { ?>
    <style>
        footer {
            position: fixed;
        }
    </style>
<?php }
?>

<!--<div class="footer-bottom">-->
<!--    <footer>
        <div class="container">
            <div class="menulist">
                <div class="footer-links" style="float: left; width: 80%;">
<?php
echo Yii::$app->userdata->getFooter();
?>
            </div>
            <div class="social-media-links" style="float: right; width: 20%;">
<?php
echo Yii::$app->userdata->getSocialMediaLinks();
?>
            </div>
            </div>
        </div>
        <div class="copyright">© 2018 Easyleases Technologies Private Limited</div> <br />
    </footer>-->
<footer class="pt-4 border-top px-4">
    <div class="row mr-0">
        <div class="col-3">
            <div class="py-1"><a style="margin: 0px 10px 0px 10px;" href="https://www.facebook.com/Easyleases/" target="_blank"><img alt="Easyleases/Facebook" style="width: 45px; height: 45px; border-radius: 7px;" src="<?php echo Url::home(true) ?>images/icons/facebook-icon.png"></a></div>
            <div class="py-1"><a style="margin: 0px 10px 0px 10px;" href="https://www.linkedin.com/company/easyleases/" target="_blank"><img alt="Easyleases/Linkedin" style="width: 45px; height: 45px; border-radius: 7px;" src="<?php echo Url::home(true) ?>images/icons/linkedin-icon.png"></a></div>
            <div class="py-1"><a style="margin: 0px 10px 0px 10px;" href="https://twitter.com/EasyleasesTech" target="_blank"><img alt="Easyleases/Twitter" style="width: 45px; height: 45px; border-radius: 7px;" src="<?php echo Url::home(true) ?>images/icons/twitter-icon.png"></a></div>
        </div>
        <div class="col-3">
            <h5>Resources</h5>
            <ul class="list-unstyled text-small">
                <li><a target="_blank" class="text-muted" href="<?php echo Url::home(true) ?>blog">Blog</a></li>
                <li><a class="text-muted" href="<?php echo Url::home(true) ?>users/owner">List Your Property</a></li>
                <li><a class="text-muted" href="<?php echo Url::home(true) ?>users/advisor">Partner With Us</a></li>
            </ul>
        </div>
        <div class="col-3">
            <h5>Easyleases</h5>
            <ul class="list-unstyled text-small">
                <li><a target="_blank" class="text-muted" href="<?php echo Url::home(true) ?>about-us">About Us</a></li>
                <li><a target="_blank" class="text-muted" href="<?php echo Url::home(true) ?>terms-and-condition">Terms and Condition</a></li>
                <li><a target="_blank" class="text-muted" href="<?php echo Url::home(true) ?>refund-policy">Refund Policy</a></li>
                <li><a target="_blank" class="text-muted" href="<?php echo Url::home(true) ?>faq">FAQ's</a></li>
                <li><a target="_blank" class="text-muted" href="<?php echo Url::home(true) ?>privacy-policy">Privacy Policy</a></li>
                <li><a target="_blank" class="text-muted" href="<?php echo Url::home(true) ?>contact">Contact Us</a></li>
            </ul>
        </div>
        <div class="col-3">
            <h5>Download the app</h5>
            <ul class="list-unstyled text-small">
                <li><div class="py-2"><a target="_blank" href="https://apps.apple.com/us/app/easyleases-tenant/id1474443593?ls=1"><img style="width: 180px;" src="<?php echo Url::home(true) ?>media/icons/appstore.png"></a></div></li>
                <li><div class="py-2"><a target="_blank" href="https://play.google.com/store/apps/details?id=com.easyleases.app"><img style="width: 180px;" src="<?php echo Url::home(true) ?>media/icons/googlestore.png"></a></div></li>
            </ul>
        </div>
    </div>
    <div class="row mr-0">
        <div class="col-12">
            <p class="text-center"><small>&copf; 2018 Easyleases Technologies Private Limited</small></p>
        </div>
    </div>
</footer>
<!--</div>-->

<!-- /Start your project here-->
<!-- Modal -->

<!-- SCRIPTS -->
<!-- JQuery -->
<script type="text/javascript" src="<?php echo Url::home(true); ?>/js/js/jquery-3.2.1.min.js"></script>
<!-- Bootstrap tooltips -->
<script type="text/javascript" src="<?php echo Url::home(true); ?>/js/js/popper.min.js"></script>
<!-- Bootstrap core JavaScript -->
<script type="text/javascript" src="<?php echo Url::home(true); ?>/js/js/bootstrap.min.js"></script>
<!-- MDB core JavaScript -->
<script type="text/javascript" src="<?php echo Url::home(true); ?>/js/js/mdb.min.js"></script>
<!--Google Maps-->
<!-- <script src="https://maps.google.com/maps/api/js"></script>   -->
<script>

                                // Regular map
                                /* function regular_map() {
                                 var var_location = new google.maps.LatLng(40.725118, -73.997699);
                                 
                                 var var_mapoptions = {
                                 center: var_location,
                                 zoom: 8
                                 };
                                 
                                 var var_map = new google.maps.Map(document.getElementById("map-container"),
                                 var_mapoptions);
                                 
                                 var var_marker = new google.maps.Marker({
                                 position: var_location,
                                 map: var_map,
                                 title: "New York"
                                 });
                                 }
                                 
                                 
                                 google.maps.event.addDomListener(window, 'load', regular_map);*/
                                function arraySearch(arr, val) {
                                    for (var i = 0; i < arr.length; i++)
                                        if (arr[i] === val)
                                            return i;
                                    return false;
                                }
                                function initMap() {
                                    var center_lat = <?php echo $center['lat']; ?>;
                                    var center_lng = <?php echo $center['lng']; ?>;
                                    var center_dis = <?php echo $center['distance']; ?>;
                                    // var 
                                    if (center_dis <= '0') {
                                        zoom_level = 14;
                                    } else {
                                        // zoom_level = Math.floor(8 - Math.log(1.6446 * (center_dis/2) / Math.sqrt(2 * ($('#map').width() * $('#map').height()))) / Math.log (2));
                                        //zoom_level = (16 - Math.log(center_dis/1000) / Math.log(2));
                                        // var zoom_level=0;
                                        // var distance_check=616313361;
                                        // while(distance_check>center_dis*1000){
                                        // 	distance_check=distance_check/2;
                                        // 	zoom_level++;
                                        // }
                                        zoom_level = Math.round(14 - Math.log(center_dis / 2) / Math.LN2);

                                    }
                                    console.log(zoom_level);
                                    var locations = <?php echo $locations; ?>;
                                    if (location.length != 0) {
                                        var centerLat = locations[0][1];
                                        var centerLong = locations[0][2];
                                    } else {
                                        var centerLat = <?php echo $center['lat']; ?>;
                                        var centerLong = <?php echo $center['lng']; ?>;
                                    }
                                    var map = new google.maps.Map(document.getElementById('map'), {
                                        zoom: zoom_level,
                                        center: new google.maps.LatLng(center_lat, center_lng),
                                        mapTypeId: google.maps.MapTypeId.ROADMAP
                                    });

                                    var infowindow = new google.maps.InfoWindow();

                                    var marker, i;
                                    var icons = '<?php echo Url::home(true); ?>images/icons/logo_icon.png';
                                    var array_locates = [];
                                    for (i = 0; i < locations.length; i++) {

                                        var match_string = locations[i][1] + "-" + locations[i][2];
                                        // console.log($.inArray(match_string,array_locates));
                                        if ($.inArray(match_string, array_locates) != -1) {
                                            var keyss = arraySearch(array_locates, match_string);
                                            var keyid = 'locate' + keyss;
                                            keyid = keyid.trim();
                                            // document.getElementById(keyid).innerHTML=document.getElementById(keyid).innerHTML+'<br/><a href="<?= Url::home(true); ?>site/view?id='+locations[i][3]+'">'+locations[i][0]+'</a>';
                                            // console.log('#map #'+keyid)
                                            // console.log($('#map').find('#'+keyid).html());
                                        } else {
                                            array_locates.push(match_string);
                                            marker = new google.maps.Marker({
                                                position: new google.maps.LatLng(locations[i][1], locations[i][2]),

                                                map: map,
                                                // icon: icons
                                            })
                                        }
                                        google.maps.event.addListener(marker, 'mouseover', (function (marker, i) {
                                            return function () {
                                                infowindow.setContent('<div id="locate' + arraySearch(array_locates, locations[i][1] + "-" + locations[i][2]) + '"><a target="_blank" href="<?= Url::home(true); ?>site/view?id=' + locations[i][3] + '">' + locations[i][0] + '</a></div>');
                                                infowindow.open(map, marker);
                                            }
                                        })(marker, i));
                                    }


                                }
                                google.maps.event.addDomListener(window, 'load', initMap);

                                function openfilter()
                                {
                                    document.location.href = '<?php echo Url::home(true) . 'site/filterpopup1?' . Yii::$app->request->queryString; ?>';
                                }

                                function removelocation()
                                {
                                    $("#formsearch").val('');
                                    $("#formlat").val('');
                                    $("#formlon").val('');
                                    $("#filterform").submit();
                                }
                                function removeflattype()
                                {
                                    $("#formflat").val('');
                                    $("#filterform").submit();
                                }
                                function removeamount()
                                {
                                    $("#formminamt").val('');
                                    $("#formmaxamt").val('');
                                    $("#filterform").submit();
                                }
                                function removearea()
                                {
                                    $("#formminarea").val('');
                                    $("#formmaxarea").val('');
                                    $("#filterform").submit();
                                }
                                function removeradius()
                                {
                                    $("#formminrad").val('');
                                    $("#formmaxrad").val('');
                                    $("#filterform").submit();
                                }
                                function removepropertytype()
                                {
                                    $("#formprtype").val('');
                                    $("#filterform").submit();
                                }

</script>
<?php if (count($model) <= 2) { ?>
    <style>
        footer {
            /*position: fixed !important;
            bottom: 0 !important;*/
        }
    </style>
<?php } ?>