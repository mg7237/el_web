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
<script src="//maps.googleapis.com/maps/api/js?key=AIzaSyDCFkcmtCqHONWxjbZn3bftmAqQ0A2YSKw&amp;libraries=places" type="text/javascript"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="<?php echo Url::home(true); ?>css/css/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo Url::home(true); ?>css/css/mdb.min.css" rel="stylesheet">
<link href="<?php echo Url::home(true); ?>css/css/style.css" rel="stylesheet">
<link href="<?php echo Url::home(true); ?>css/css/newstyle/style1.css" rel="stylesheet">
<link href="<?php echo Url::home(true); ?>css/css/newstyle/style2.css" rel="stylesheet">
<link href="<?php echo Url::home(true); ?>css/css/newstyle/style3.css" rel="stylesheet">
<link href="<?php echo Url::home(true); ?>css/css/newstyle/style6.css" rel="stylesheet">
<link href="<?php echo Url::home(true); ?>css/css/home.css" rel="stylesheet">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<script type="text/javascript">
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
            if (document.getElementById("locationsearch")) {
                var slider_html = '<div class="tag filteroption"><span class="tag-label">' + place.formatted_address + '</span><span class="tag-close" onclick="removelocation();"> × </span></div>';
                $("#locationsearch").html(slider_html);
            } else {
                var sliderhtml = '<div class="inline-block" id="locationsearch"><div class="tag filteroption"><span class="tag-label">' + place.formatted_address + '</span><span class="tag-close" onclick="removelocation();"> × </span></div></div>';
                var prehtml = $(".filters").html();
                var finalhtml = prehtml + sliderhtml;
                $(".filters").html(finalhtml);
            }
        });
    }
    google.maps.event.addDomListener(window, 'load', initialize);



</script>

<style>


    html{position: relative;
         min-height: 100%;
    }

    .no-padd {
        padding-left: 0px !important;
        padding-right: 0px !important;
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
        top: 30px;
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
        padding: 6px 10px;
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
        float: right;
        width: 100%;
    }
    .spancalcel
    {
        cursor: pointer;
        border-radius: 17px !important;
        padding: 6px 15px;
        float: left;
        width: 100%;
        margin-top: 2px;
    }
    .tagactive {
        padding: 5px 8px;
        text-align: center;
        color: #fff;
        border-radius: 17px;
        background: #59abe3;
        cursor:pointer;
        /*margin-right: 5px;*/
        float: left;
        width:100%;
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
        float: left;
        /* padding-left: 15px; */
    }
    .inline-block {
        display: inline-block;
        padding-top: 4px;
        padding-bottom: 0px;
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
    #favbutton{ position:absolute; top: 10px; left:0px; z-index:10; }
    a.btn.btn-blue-grey.waves-effect.waves-light {
        background-color: #59abe3!important;
    }
    footer {
        /* background: rgba(0, 0, 0, 0) url(../images/footer_bg.jpg) repeat scroll 0 0; */
        float: left;
        padding: 20px 0 20px;
        /* padding: 161px 0 123px; */
        width: 100%;        
        z-index: 9999;
        background-color: #000;
        height:auto;
        margin-top:10px;

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
        position: relative;
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
        width: 50%;
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
    }
    @media (min-width: 70.063em) and (max-width: 120em){
        .search-header{
            padding-top: 10px !important;
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
            margin-top: 20px;
        }	
        .house-label.col-6.div-boader2 {
            padding-left: 0px;
        }

    } 
    @media  (max-width:75em){
        .filter-tab{
            margin: 50px;
        }
    }

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
        /* margin-right: 5px;*/
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
        bottom: 0;
        width: 100%;
        z-index:1000;
        height : auto;
        margin-top :10px;       
        overflow: auto;
        position: inherit;
    }


    .tag {
        margin-right: 5px; 
    }
    .my-mt {
        margin-top: 5rem !important;
    }
    span.btn.mybtn {
        background-color: grey;
    }
    .ui-slider-horizontal .ui-slider-handle {
        top: -.4em;
        margin-left: -.6em !important;
    }
    .ui-slider .ui-slider-handle {
        position: absolute;
        z-index: 2;
        width: 1.0em !important;
        height: 1.0em !important;
        cursor: default;
        -ms-touch-action: none;
        touch-action: none;
    }
    .ui-slider-horizontal {
        height: 0.4em !important;
    }

    .modal-body{
        position: relative;
        padding: 0px;
        min-height: auto;
    }

    .my-mt {
        margin-top: 2rem !important;
    }

    .pad-botm20{padding-bottom:20px;}
    .pt-4{
        padding-top: 0.2rem!important;
    }
    /*.input-group-btn{width:125px;}*/
    .input-group-addon, .input-group-btn
    {
        width:50%;
    }
    @media screen and (max-width:480px){

        .mobi-margTop20{
            margin-top:20px;
        }

        .footer-bottom{position: inherit;}

    }
    .modal-header
    {	padding-left: 0px !important;
      padding-right: 0px !important;
    }
    .input-group-btn
    {
        padding-right: 15px !important;
    }
    input#searchTextField
    {
        padding-left: 0px;
    }

    @media screen and (max-width:667px){
        .footer-bottom{position: inherit;}
    }


</style>

<form action="<?php echo Url::home(true) . 'site/search?' . Yii::$app->request->queryString; ?>" method="GET">
    <div>
        <div class="container-fluid no-padd" role="document">
            <div class="container-fluid">
                <div class="modal-header">
                    <div class="col-sm-12 col-xs-12" style="padding-left: 0px;">

                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                                <div class="input-group"  style="border: 1px solid #e9ebf0;">
                                    <!--div class="tag">
                                       <span class="tag-label">developer</span>
                                       </div-->
                                    <div class="filters">
                                        <?php if ($searcharea != '') { ?>
                                            <div class="inline-block" id="locationsearch">
                                                <div class="tag filteroption">
                                                    <span class="tag-label"><?php echo $searcharea; ?></span>
                                                    <span class="tag-close" onclick="removelocation();"> × </span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if ($propertytypename != '') { ?>
                                            <div class="inline-block" id="filterpr">
                                                <div class="tag filteroption">
                                                    <span class="tag-label"><?php echo $propertytypename; ?></span>
                                                    <span class="tag-close" onclick="removepropertytype();"> × </span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if ($flat_type != '') { ?>
                                            <div class="inline-block" id="typeval"><div class="tag filteroption" style="margin-left: 7px;" ng-if="!search.isArray(value)" ng-class=""><span class="tag-label"><?php
                                                        if ($flat_type == 0) {
                                                            echo "Semi Furnished";
                                                        } else {
                                                            echo "Furnished";
                                                        }
                                                        ?></span><span class="tag-close" onclick="removetype();"> × </span></div></div>
                                                    <?php } ?>
                                                    <?php if ($min_amount != '' && $max_amount != '') { ?>
                                            <div class="inline-block" id="priceval"><div class="tag filteroption" ng-if="!search.isArray(value)" ng-class=""><span class="tag-label">Price : <?php echo $min_amount; ?> - <?php echo $max_amount; ?></span><span class="tag-close" onclick="resetSlider();"> × </span></div></div>
                                        <?php } ?>
                                        <?php if ($min_area != '' && $max_area != '') { ?>
                                            <div class="inline-block" id="areaval"><div class="tag filteroption" ng-if="!search.isArray(value)" ng-class=""><span class="tag-label">Area : <?php echo $min_area; ?> - <?php echo $max_area; ?></span><span class="tag-close" onclick="resetSlider2();"> × </span></div></div>
                                        <?php } ?>
                                        <?php if ($min_radius != '' && $max_radius != '') { ?>
                                            <div class="inline-block" id="radiusval"><div class="tag filteroption" ng-if="!search.isArray(value)" ng-class=""><span class="tag-label">Radius : <?php echo $min_radius; ?> - <?php echo $max_radius; ?></span><span class="tag-close" onclick="resetSlider1();"> × </span></div></div>
                                        <?php } ?>
                                    </div>			  

                                </div>
                            </div>


                            <div class="input-group-btn col-xs-6 col-sm-6 col-md-2 col-lg-2 mobi-margTop20">
                                <div class="tag" id="_tag-label1">
                                    <button class="tag-label1" id="filter_btn" type="submit" style="background: none;border: navajowhite;color: white;">Apply Filters</button>
                                </div>
                            </div>


                            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-6 text-center input-group-btn mobi-margTop20">
                                <span class="btn mybtn spancalcel" onclick="history.go(-1);">Cancel</span>
                            </div>

                        </div>

                    </div>

                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 col-offset-md-4 col-xs-12 p-5"style="padding-top: 15px !important;padding-bottom: 15px !important;">
                            <div class="row">
                                <div class=" col-md-6 col-xs-12 pl-1">
                                    <label class="col-xs-12" style="padding-left:0px;"><b>Location</b></label>
                                    <?= Html::input('text', 'search', '', ['class' => 'form-control form-control-lg mysearch', 'id' => 'searchTextField', 'placeholder' => 'Search The Flats By Entering The Location']) ?>

                                    <?= Html::hiddenInput('city', Yii::$app->getRequest()->getQueryParam('city')); ?>			
                                    <?= Html::hiddenInput('lat', '', ['value' => '', 'id' => 'properties-lat']); ?>
                                    <?= Html::hiddenInput('lon', '', ['value' => '', 'id' => 'properties-lon']); ?>
                                    <?= Html::hiddenInput('radius', 5); ?>
                                    <?= Html::hiddenInput('bhk', ''); ?>
                                    <div class="search-error-tooltip animated shake" id="locality-error" style="display: none;"></div>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-md-4 pt-4 pl-1">
                                    <div><b>Types Of Furnishing</b></div>
                                    <br/>
                                    <div>
                                        <div <?php if ($flat_type == 0 && $flat_type != '') { ?>class="tagactive"<?php } else { ?>class="tag"<?php } ?> id="semi_furnished" style="margin-bottom: 5px;">
                                            <span class="tag-label1" onclick="get_property_type(this);" id="0">Semi-Furnished</span>
                                        </div>
                                        <div <?php if ($flat_type == 1 && $flat_type != '') { ?>class="tagactive"<?php } else { ?>class="tag"<?php } ?> id="furnished">
                                            <span class="tag-label1" onclick="get_property_type(this);" id="1">Furnished</span>
                                        </div>
                                        <input type="hidden" name="flat_type" id="property_type" value="<?php echo $flat_type; ?>"></input>
                                        <input type="hidden" name="property_type" value="<?php echo $property_type; ?>" id="prtype"></input>
                                        <!--div class="tag">
                                           <span class="tag-label">developer</span>
                                        </div-->
                                    </div>
                                </div>
                                <!--div class="col-md-8">
               <div>Types Of Properties</div>
               <br/>
               <div>
                  <div class="tag">
                     <span class="tag-label1">Co-living for Boys</span>
                  </div>
                  <div class="tag">
                     <span class="tag-label1">Co-living for Girls</span>
                  </div>
                  <div class="tag">
                     <span class="tag-label1">Flat for Families</span>
                  </div>
               </div>
            </div-->
                            </div>
                        </div>
                    </div>
                    <div class="row pr-4 pl-4 pad-botm20">

                        <!--div data-role="rangeslider">
  <label for="range-1a">Rangeslider:</label>
  <input name="range-1a" id="range-1a" min="0" max="100" value="0" type="range" />
  <label for="range-1b">Rangeslider:</label>
  <input name="range-1b" id="range-1b" min="0" max="100" value="100" type="range" />
</div-->
                        <div class="col-md-4 col-sm-6" >

                            <span><b>Price range</b></span>
                            <div class="mt-3">

                                <p> <div id="slider-range"></div></p>

                                <div class="dollartextnew">
                                    <span id="amout11"><?= Yii::$app->getRequest()->getQueryParam('min_amount'); ?></span> 
                                    <?= Html::textInput('min_amount', Yii::$app->getRequest()->getQueryParam('min_amount'), ['id' => 'min_amount']); ?><span><b>&#x20B9;</b></span>
                                </div>
                                <div class="dollartextnew pull-right">
                                    <span id="amout22"><?= Yii::$app->getRequest()->getQueryParam('max_amount'); ?></span> 
                                    <?= Html::textInput('max_amount', Yii::$app->getRequest()->getQueryParam('max_amount'), ['id' => 'max_amount']); ?><span><b>&#x20B9;</b></span>
                                </div>

                            </div>

                        </div>
                        <div class="col-md-4 col-sm-6">

                            <span><b>Square Feet Area</b></span>
                            <div class="mt-3">
                                <p> <div id="slider-range2"></div></p>
                                <div class="dollartextnewbottom">
                                    <span id="amout13"><?= Yii::$app->getRequest()->getQueryParam('min_area'); ?></span> <b>sq.ft</b>
                                    <?= Html::hiddenInput('min_area', Yii::$app->getRequest()->getQueryParam('min_area'), ['id' => 'min_area']); ?>
                                </div>
                                <div class="dollartextnewbottom pull-right">
                                    <span id="amout24"><?= Yii::$app->getRequest()->getQueryParam('max_area'); ?></span> <b>sq.ft</b>
                                    <?= Html::hiddenInput('max_area', Yii::$app->getRequest()->getQueryParam('max_area'), ['id' => 'max_area']); ?>
                                </div>
                            </div>


                        </div>

                        <div class="col-md-4 col-sm-6" style="padding-right: 20px;">

                            <span><b>Radius - Kms</b></span>
                            <div class="mt-3">
                                <p> <div id="slider-range1"></div></p>
                                <div class="dollartextnewbottom">
                                    <span id="amout12"><?= Yii::$app->getRequest()->getQueryParam('min_radius'); ?></span> <b>Kms</b>
                                    <?= Html::hiddenInput('min_radius', Yii::$app->getRequest()->getQueryParam('min_radius'), ['id' => 'min_radius']); ?>
                                </div>
                                <div class="dollartextnewbottom pull-right">
                                    <span id="amout23"><?= Yii::$app->getRequest()->getQueryParam('max_radius'); ?></span> <b>Kms</b>
                                    <?= Html::hiddenInput('max_radius', Yii::$app->getRequest()->getQueryParam('max_radius'), ['id' => 'max_radius']); ?>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
                <!-- <div class="modal-footer">
                   <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                   <button type="button" class="btn btn-primary">Save changes</button>
                   </div> -->
            </div>
        </div>
    </div>
</form>
<div class="footer-bottom">
    <footer class="my-mt">
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
    </footer>
    <div class="copyright">© 2018 Easyleases Technologies Private Limited</div>
</div>

<script type="text/javascript" src="<?php echo Url::home(true); ?>/js/js/jquery-3.2.1.min.js"></script>
<!-- Bootstrap tooltips -->
<script type="text/javascript" src="<?php echo Url::home(true); ?>/js/js/popper.min.js"></script>
<!-- Bootstrap core JavaScript -->
<script type="text/javascript" src="<?php echo Url::home(true); ?>/js/js/bootstrap.min.js"></script>
<!-- MDB core JavaScript -->
<script type="text/javascript" src="<?php echo Url::home(true); ?>/js/js/mdb.min.js"></script>



<script>

                                                function get_property_type(target)
                                                {
                                                    var property_type = target.id;
                                                    $('#property_type').val(property_type);
                                                    if (property_type == 0)
                                                    {
                                                        if (document.getElementById("typeval")) {
                                                            var slider_html = '<div class="tag filteroption" ng-if="!search.isArray(value)" ng-class=""><span class="tag-label">Semi-Furnished</span><span class="tag-close" onclick="removetype();"> × </span></div>';
                                                            $("#typeval").html(slider_html);
                                                        } else {
                                                            var sliderhtml = '<div class="inline-block" id="typeval"><div class="tag filteroption" ng-if="!search.isArray(value)" ng-class=""><span class="tag-label">Semi-Furnished</span><span class="tag-close" onclick="removetype();"> × </span></div></div>';
                                                            var prehtml = $(".filters").html();
                                                            var finalhtml = prehtml + sliderhtml;
                                                            $(".filters").html(finalhtml);
                                                        }
                                                        $("#semi_furnished").removeClass("tag");
                                                        $("#semi_furnished").addClass("tagactive");
                                                        $("#furnished").removeClass("tagactive");
                                                        $("#furnished").addClass("tag");

                                                    } else {
                                                        if (document.getElementById("typeval")) {
                                                            var slider_html = '<div class="tag filteroption" ng-if="!search.isArray(value)" ng-class=""><span class="tag-label">Furnished</span><span class="tag-close" onclick="removetype();"> × </span></div>';
                                                            $("#typeval").html(slider_html);
                                                        } else {
                                                            var sliderhtml = '<div class="inline-block" id="typeval"><div class="tag filteroption" ng-if="!search.isArray(value)" ng-class=""><span class="tag-label">Furnished</span><span class="tag-close" onclick="removetype();"> × </span></div></div>';
                                                            var prehtml = $(".filters").html();
                                                            var finalhtml = prehtml + sliderhtml;
                                                            $(".filters").html(finalhtml);
                                                        }
                                                        $("#furnished").removeClass("tag");
                                                        $("#furnished").addClass("tagactive");
                                                        $("#semi_furnished").removeClass("tagactive");
                                                        $("#semi_furnished").addClass("tag");

                                                    }
                                                }

                                                function removetype()
                                                {
                                                    $('#property_type').val('');
                                                    $("#furnished").removeClass("tagactive");
                                                    $("#semi_furnished").removeClass("tagactive");
                                                    $("#semi_furnished").addClass("tag");
                                                    $("#furnished").addClass("tag");
                                                    $("#typeval").remove();
                                                }

                                                function removelocation()
                                                {
                                                    $("#locationsearch").remove();
                                                    $('#searchTextField').val('');
                                                    $('#properties-lat').val('');
                                                    $('#properties-lon').val('');
                                                }
                                                function removepropertytype()
                                                {
                                                    $("#filterpr").remove();
                                                    $('#prtype').val('');
                                                }

</script>
