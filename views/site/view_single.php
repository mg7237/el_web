<?php
$this->title = 'PMS - ' . $model->property_name;

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\PropertyTypes;
use app\models\ChildProperties;
use yii\widgets\ActiveForm;

$this->registerAssetBundle(yii\web\JqueryAsset::className(), \yii\web\View::POS_HEAD);
$namebhk = '';
?>

<link href="<?php echo Url::home(true); ?>css/css/mdb.min.css" rel="stylesheet">
<link href="<?php echo Url::home(true); ?>css/css/style.css" rel="stylesheet">
<link href="<?php echo Url::home(true); ?>css/css/newstyle/style1.css" rel="stylesheet">
<link href="<?php echo Url::home(true); ?>css/css/viewsingle.css" rel="stylesheet">

<style>
    a.btn.btn-primary.btn_mycolor {
        padding: 34px 34px;
    }
    h3#_second-page {
        width: 150px !important;
    }
    span.tag-label.click_operation.more_deatail {
        padding-right: 14px;
        padding-left: 0px
    }
    span#moredetail {
        padding-right: 9px;
    }
    .badge-success {
        background-color: #008000;
    }

    .badge-primary {
        background-color: #59abe3;
    }

    .allign_btn {
        margin-left: 35px;
    }

    @media screen and (max-width: 375px){
        .btn-danger{
            width: 80% !important;
        }
        .btn-grey{
            width: 80% !important;
        }
    }
    #visitsubmit{
        width: 95% !important;
        margin-left: 0;
    }
    .cust-grybtn{
        width: 95% !important;
        margin-left: 0;
    }

    .iconimage .row img {
        float: left;
        width: 35px !important;
        height: 100px !important;
        margin-top: -30px;
    }

    .headerDivider, .headerDivider1 {
        display: block;
    }
    .headerDivider{
        margin-top: -70px !important;
    }

    .modal-content {
        position: relative !important;
        background-color: #fff !important;
        border: 1px solid #999 !important;
        border: 1px solid rgba(0,0,0,0.2);
        border-radius: 6px !important;
        box-shadow: 0 3px 9px rgba(0,0,0,0.5) !important;
        background-clip: padding-box !important;
        outline: 0;
        padding: 50px;
    }
    button.btn_top {
        background: #33a507;
        border: 1px solid #33a507;
        color: #fff;
        font-size: 14px;
        margin-top: 9px;
        padding: 2px 10px;
    }

    .navbar-nav>li>a {
        padding-top: 10px !important;
        padding-bottom: 10px !important;
        line-height: 12px !important;
    }
    .no-margin {
        margin-bottom: 0px !important
            margin-top: 0px !important
    }

    span.carousel-control-prev-icon, span.carousel-control-next-icon {
        margin-top: 100px;
    }

    .d-block {
        display: block;
        width: 100%;
        margin-bottom: 2px;
        height: 650px;
    }

    #propertyvisits-visit_time {
        border-top: none;
        border-left: none;
        border-right: none;
    }

    #visits-book-form {
        display: none;
    }

    span.k_span {
        text-align: right;
        margin-left: 47px;
    }
    .col-img {
        margin-top: 10px;
    }
    #address_span:hover {
        color: #0056b3 !important;
    }
    .active {
        /*background-color:#d90000;*/
        color: #e24c3f !important
    }
    footer {
        /* background: rgba(0, 0, 0, 0) url(../images/footer_bg.jpg) repeat scroll 0 0; */
        float: left;
        padding: 20px 0 20px;
        /* padding: 161px 0 123px; */
        width: 100%;
        position: relative;
        z-index: 9999;
        background-color: #000;
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
        color: #fff !important;
    }
    a.nav-link.small.waves-effect.waves-light {
        margin-top: 10px;
    }
    a.navbar-brand {
        margin-top: 12px;
    }

    .no-repeat {
        padding-top: 0px;
        padding-bottom: 0px;
    }

    .fixed-top {
        position: fixed;
        top: 0;
        right: 0;
        left: 0;
        z-index: 1030;
    }
    .navbar.navbar-dark .breadcrumb .nav-item .nav-link, .navbar.navbar-dark .navbar-nav .nav-item .nav-link {
        color: rgb(82, 86, 89);
        -webkit-transition: .35s;
        transition: .35s;
        font-size: 14px;
        font-weight: 400;
    }
    .py-10{
        margin-top: -15px;
        text-align: justify !important;
    }
    .modal-dialog {
        width: 100%;
        height: 100%;
        /* margin: 0; */
        padding-top: 100px;
    }

    .modal-content {
        height: auto;
        min-height: 100%;
        border-radius: 0;
    }

    img.fav_icon {
        /* content: ''; */
        display: block;
        height: 30px;
        width: 30px;
        position: absolute;
        top: 115px;
        left: 15px;
        /* background: #F8E6AE url(ico.gif) no-repeat 0px 0px; */
    }
    .slider_arrow {
        opacity: 1;
        height: 20%;
        top: 235px;
    }

    .pad-right{
        padding-right:0px !important;
    }

    .md-form.md-form-edit.col-sm-12.col-xs-12 {
        margin-bottom: 12px;
    }

    .md-form.md-form-edit-2.col-sm-12.col-xs-12 {
        margin-bottom: 10px;
    }

    .md-form-edit {
        padding:0px !important;
        margin-top: 34px;
    }

    #location i{margin-right:10px;}

    #location ul li{
        border-bottom:1px solid #d8d8d8;
        margin:20px 0px;
    }
    .location-icon{
        padding-left:45px;
    }

    .md-form {
        /*      margin-bottom: 6px;
                margin-top: 25px;*/
        position: relative;
        padding: 0px 0px 0px 0px !important;
    }
    span.tag-label {
        border-radius: 0px;
    }
    .property_visit{
        margin-top: 30px !important;;
    }

    #moreDetailsSubmit{
        width: 95% !important;
        margin-left: 0;
    }

    @media screen and (max-width:480px){
        .prop-attr-bx {
            padding: 5px 25px;
            height: 50px !important;
        }

        .headerDivider, .headerDivider1 {
            display: none;
        }

        .md-form-edit{
            padding:0px !important;
            margin-top: 16px;
        }

        .md-form{
            margin-bottom: 1rem;
        }

        .smaller {
            margin-bottom: 17rem !important;
        }
        .d-block{ height:250px !important; }
        .carousel-inner{height:250px !important; margin-top: 250px !important;}


        .iconimage{ margin-bottom: 15px; }
        .iconimage img{width:80% !important; height:80% !important; }
        .location-icon{ margin-bottom: 25px; }

        #location ul li{
            border-bottom:none;
            margin:0px 14px !important;
            float:left;
        }

        #location ul li span{ display:none; }
        #map{height: 350px !important; }
        /*.slider_arrow{top: 270px;}*/
        .carousel-control-prev-icon, .carousel-control-next-icon { margin-top: 190px !important;}
        a.wishlist .fav_icon{top: 54px !important;}

        /*.navbar{ margin-left: -77px; }*/

    }


    @media screen and (max-width:667px){
        .prop-attr-bx {
            padding: 5px 25px;
            height: 50px !important;
        }

        .headerDivider, .headerDivider1 {
            display: none;
        }
        .smaller{ margin-bottom: 17rem  !important; }
        .location-icon{ margin-bottom: 5px;padding-right: 0px !important;
                        padding-left: 0px; padding-right: 0px !important;}
        .iconimage{ margin-bottom: 2px; }
        .container-fluid
        {
            padding-right: 0px !important;
        }
        .book_style
        {
            /*            margin-left: 15px !important;
                        margin-right: 15px !important;*/
        }
        .col-6.col-md-3.col-sm-6.col-xs-6.prop-attr-bx {
            margin-bottom: 10px;
        }
        [class*='col-']
        {
            padding-right:15px;
        }
        /*.slider_arrow{top: 300px;}
        a.wishlist .fav_icon{top: 225px;}*/
    }
    .book_style{
        margin-bottom: 0px !important;
    }
    @media screen and (max-width: 767px){
        .cust-btnhide{
            display: none !important;
        }
        .row{
            margin-right: 0 !important;
            margin-left: 0 !important;
            text-align: center;
        }
        span.tag-label.click_operation.more_deatail {
            padding-right: 79px;
            padding-left: 73px;
            text-align: center;
        }
        span#moredetail {
            padding-right: 96px;
            padding-left: 52px;
            text-align: center;
        }
        span.tag-label {
            padding-left: 81px;
            padding-right: 55px;
        }
        .allign_btn {
            margin-left: -10px;
        }
    }

    @media screen and (max-width:768px){
        .prop-attr-bx {
            padding: 5px 25px;
            height: 50px !important;
        }

        .headerDivider, .headerDivider1 {
            display: none;
        }
        .md-form-edit{
            padding:0px !important;
            margin-top: 16px;
            text-align: center;
        }
        /*
            .navbar-brand > .navbar-nav{
            display:none !important;
            }
        
            .navbar > b{display:none;}
        */
        .smaller{
            margin-bottom:0px  !important;
        }

        .fix-data1, .position-fixed{
            position: absolute !important;
        }

        a.wishlist .fav_icon{
            height: 20px;
            width: 20px;
            top: 16px !important;
        }

        .p_des{margin-top: 0.6rem !important; }



        #location ul li span{ display:none; }
        #location ul li{
            border-bottom:none;
            margin:0px 14px;
            float:left;
        }
        .iconimage img{width:60% !important; height:60% !important; }
        /*.navbar-collapse.collapse{display:none !important;}*/
        /*.collapse{display:none !important;}*/
    }
    .md-form-edit more_deatail{
        margin-top: 10px;
        margin-bottom: 6px !important;
    }
</style>
<script>
    // This example requires the Places library. Include the libraries=places
    // parameter when you first load the API. For example:
    var map;
    var infowindow;
    var param;


    // Regular map
    $(document).ready(
            //var pyrmont = {lat: 12.91488, lng: 77.58646};
                    function regular_map() {
                        var image = '<?php echo Url::home(true); ?>images/icons/home_map.ico';
                        var var_location = new google.maps.LatLng(<?php echo $model->lat; ?>, <?php echo $model->lon; ?>);

                        var var_mapoptions = {
                            center: var_location,
                            zoom: 15
                        };

                        var var_map = new google.maps.Map(document.getElementById("map"),
                                var_mapoptions);

                        var var_marker = new google.maps.Marker({
                            position: var_location,
                            map: var_map,
                            title: "<?php echo $model->property_name; ?>",
                            icon: image
                        });

                    }
            );

            function initMap(param) {
                var image = '<?php echo Url::home(true); ?>images/icons/home_map.ico';
                var pyrmont = {lat: <?php echo $model->lat; ?>, lng: <?php echo $model->lon; ?>};

                map = new google.maps.Map(document.getElementById('map'), {
                    center: pyrmont,
                    zoom: 15
                });

                infowindow = new google.maps.InfoWindow();
                var service = new google.maps.places.PlacesService(map);
                service.nearbySearch({
                    location: pyrmont,
                    radius: 5000,
                    type: [param]
                }, callback);


                var var_marker = new google.maps.Marker({
                    position: pyrmont,
                    map: map,
                    title: "<?php echo $model->property_name; ?>",
                    icon: image
                });

            }

            function callback(results, status) {
                if (status === google.maps.places.PlacesServiceStatus.OK) {
                    for (var i = 0; i < results.length; i++) {
                        createMarker(results[i]);
                    }
                }
            }

            function createMarker(place) {
                var placeLoc = place.geometry.location;
                var marker = new google.maps.Marker({
                    map: map,
                    position: place.geometry.location
                });

                google.maps.event.addListener(marker, 'mouseover', function () {
                    infowindow.setContent(place.name);
                    infowindow.open(map, this);
                });
            }
            function getMapLoc1(params) {
                initMap(params);
            }
</script>
<style>
    a.btn.btn-blue-grey.waves-effect.waves-light {
        background-color: #59abe3!important;
    }
    @media (min-width: 70.063em) and (max-width: 120em){
        .badge, .btn, .card:not([class*=card-outline-]), .chip, .jumbotron, .modal-dialog.cascading-modal .modal-c-tabs .nav-tabs, .modal-dialog.modal-notify .modal-header, .navbar, .pagination .active .page-link, .z-depth-1{
            box-shadow: none !important;
        }

        nav.navbar.navbar-expand-lg {
            margin-bottom: 0px;
        }
    }


</style>
<!--header section-->

<body class="hm-gradient">


    <!-- nab bar -->
    <nav class="navbar navbar-expand-lg navbar-dark indigo fixed-top no-repeat">
        <!-- Navbar brand -->
        <a class="navbar-brand" href="<?php echo Url::home(true); ?>"><img src="<?= Yii::$app->request->baseUrl ?>/images/newlogo1.png"></a>
        <!-- Collapse button -->
        <!--<div class="homebtn">-->

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
        <!--</div>-->

        <!-- Collapsible content -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Links -->
            <ul class="navbar-nav mr-auto">
                <li class="nav-item ">
                    <a class="nav-link small" onclick="targetSection('searchproperty')" href="javascript:void(0);">Overview <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link small " onclick="targetSection('propertyfeature')" href="javascript:void(0);">Property Features</a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link small" onclick="targetSection('amenities')" href="javascript:void(0);">Complex Amenities</a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link small" onclick="targetSection('location')" href="javascript:void(0);">Neighbourhood</a>
                </li>
                <!--                <li class="nav-item pull-right">
                                <a class="btn btn-primary btn_mycolor cust-btnhide" href="<?php echo Url::home(true); ?>users/owner">List Your Property</a>
                                </li>
                                <li class="nav-item ">
                                    <a class="btn btn-primary btn_mycolor cust-btnhide" href="<?php echo Url::home(true); ?>users/advisor">Partner With Us</a>
                                    </li>
                -->
                <li class="nav-item ">
                    <a class="btn btn-primary btn_mycolor" href="<?php echo Url::home(true); ?>site/page?slug=faq-s">FAQ</a>
                </li>
                <?php if (Yii::$app->user->isGuest) { ?>
                    <li class="nav-item ">
                        <a class="btn btn-primary btn_mycolor" href="<?php echo Url::home(true); ?>site/login">Sign In</a>
                    </li>
                <?php } ?>
                <?php
                if (!Yii::$app->user->isGuest) {
                    ?>
                    <li class="nav-item ">
                        <?php if (Yii::$app->userdata->usertype == 6) { ?>
                            <a class="btn btn-blue" href="<?php echo Url::home(true); ?>external/myprofile">My Profile</a>
                        <?php } else if (Yii::$app->userdata->usertype == 2) { ?>
                            <a class="btn btn-blue" href="<?php echo Url::home(true); ?>site/myprofile">My Profile</a>
                        <?php } else if (Yii::$app->userdata->usertype == 3) { ?>
                            <a class="btn btn-blue" href="<?php echo Url::home(true); ?>site/myprofile">My Profile</a>
                        <?php } else if (Yii::$app->userdata->usertype == 4) { ?>
                            <a class="btn btn-blue" href="<?php echo Url::home(true); ?>site/myprofile">My Profile</a>
                        <?php } else if (Yii::$app->userdata->usertype == 5) { ?>
                            <a class="btn btn-blue" href="<?php echo Url::home(true); ?>advisers/myprofile">My Profile</a>
                        <?php } else if (Yii::$app->userdata->usertype == 7) { ?>
                            <a class="btn btn-blue" href="<?php echo Url::home(true); ?>external/myprofile">My Profile</a>
                        <?php } ?>
                    </li>
                <?php }
                ?>
            </ul>
            <!-- Links -->
        </div>
        <!-- Collapsible content -->
    </nav>
    <hr>

    <!--
        <!- nab bar ->
        <nav class="navbar navbar-expand-lg navbar-dark indigo fixed-top no-repeat">
            <!- Navbar brand ->
            <a class="navbar-brand" href="<?php echo Url::home(true); ?>"><b> <img src="<?= Yii::$app->request->baseUrl ?>/images/newlogo1.png"></a>
    
    
    
                    <!- Collapse button ->
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                    <!- Collapsible content ->
                    <!- Edit toggle->
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!- Links ->
    
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item ">
                                <a class="nav-link small" onclick="targetSection('searchproperty')" href="javascript:void(0);">Overview <span class="sr-only">(current)</span></a>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link small " onclick="targetSection('propertyfeature')" href="javascript:void(0);">Property Features</a>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link small" onclick="targetSection('amenities')" href="javascript:void(0);">Complex Amenities</a>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link small" onclick="targetSection('location')" href="javascript:void(0);">Neighbourhood</a>
                            </li>
                            <!-li class="nav-item pull-right">
                                <a class="btn btn-primary btn_mycolor" href="<?php echo Url::home(true); ?>users/owner">List Your Property</a>
                            </li>
                            <li class="nav-item ">
                                <a class="btn btn-primary btn_mycolor" href="<?php echo Url::home(true); ?>users/advisor">Partner With Us</a>
                            </li->
                            <li class="nav-item ">
                                <a class="btn btn-primary btn_mycolor" href="<?php echo Url::home(true); ?>site/page?slug=faq-s">FAQ</a>
                            </li>
    <?php if (Yii::$app->user->isGuest) { ?>
                                                <li class="nav-item ">
                                                    <a class="btn btn-primary btn_mycolor" href="<?php echo Url::home(true); ?>site/login">Sign In</a>
                                                </li>
    <?php } ?>
    <?php
    if (!Yii::$app->user->isGuest) {
        ?>
                                                <li class="nav-item ">
        <?php if (Yii::$app->userdata->usertype == 6) { ?>
                                                                        <a class="btn btn-blue" href="<?php echo Url::home(true); ?>external/myprofile">My Profile</a>
        <?php } else if (Yii::$app->userdata->usertype == 2) { ?>
                                                                        <a class="btn btn-blue" href="<?php echo Url::home(true); ?>site/myprofile">My Profile</a>
        <?php } else if (Yii::$app->userdata->usertype == 3) { ?>
                                                                        <a class="btn btn-blue" href="<?php echo Url::home(true); ?>site/myprofile">My Profile</a>
        <?php } else if (Yii::$app->userdata->usertype == 4) { ?>
                                                                        <a class="btn btn-blue" href="<?php echo Url::home(true); ?>site/myprofile">My Profile</a>
        <?php } else if (Yii::$app->userdata->usertype == 5) { ?>
                                                                        <a class="btn btn-blue" href="<?php echo Url::home(true); ?>advisers/myprofile">My Profile</a>
        <?php } else if (Yii::$app->userdata->usertype == 7) { ?>
                                                                        <a class="btn btn-blue" href="<?php echo Url::home(true); ?>external/myprofile">My Profile</a>
        <?php } ?>
                                                </li>
    <?php }
    ?>
                        </ul>
                        <!- Links ->
                    </div>
                    <!- Collapsible content ->
                    </nav>
                    <hr>
    -->
    <!--main style="margin-bottom: 22rem;background: white;z-index: 1000;" class="normal"-->
    <main class="normal">
        <div id="test" class="position-fixed mt-10 mydata fix-data " style="z-index: 1000;background: white;width: 100%;margin-top: -5px;">
            <div class="container-fluid ">
                <!-- <div class="col-md-10 col-md-offset-0 ">

            </div>       -->
                <div class="row"  >
                    <div class="book_style no-margin ">
                        <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 " style="margin-top: 35px;" >
                            <div class="col-6 col-md-2 col-sm-6 col-xs-6 prop-attr-bx">
                                <b><?php echo $model->property_name; ?></b>
                                <br/>
                                <p >
                                    <i class="fa fa-home"></i>

                                    <span data-toggle="tooltip" data-placement="top" title="<?php echo $model->address_line_1; ?><?php
                                    if ($model->address_line_2 != '') {
                                        echo ", " . $model->address_line_2;
                                    }
                                    ?>"><span style="color:#48b39b;cursor: pointer;" id="address_span">Address</span>
                                    </span>
                                </p>
                                <div class="headerDivider"></div>
                            </div>

                            <div class="col-6 col-md-2 col-sm-6 col-xs-6 prop-attr-bx">
                                <b>Rs &nbsp; <?php echo Yii::$app->userdata->getFormattedMoney($model->propertyListing->rent); ?> </b>
                                <br/>
                                <span id="span_has">Rent / Month</span>
                                <!-- <div class="vl"></div> -->
                                <div class="headerDivider1"></div>
                            </div>

                            <div class="col-6 col-md-2 col-sm-6 col-xs-6 prop-attr-bx">
                                <b><?php echo ($model->propertyListing->maintenance == '0' || $model->propertyListing->maintenance == '') ? 'Included' : 'Rs &nbsp;' . Yii::$app->userdata->getFormattedMoney($model->propertyListing->maintenance); ?></b>

                                <br/>
                                <span id="span_has">Maintanance</span>
                                <div class="headerDivider1"></div>
                            </div>
                            <!-- <div class="col-3  col-xs-6 vl">
                                <b><?php echo Yii::$app->userdata->getFullNameById($model->owner_id); ?></b>
                                <br/>
                                <span id="span_has">Property Owner Name</span>
                            </div> -->

                            <div class="col-6 col-md-2 col-sm-6 col-xs-6 prop-attr-bx">
                                <b>Rs <?php echo Yii::$app->userdata->getFormattedMoney($model->propertyListing->deposit); ?></b>

                                <br/>
                                <span id="span_has">Deposit</span>
                                <div class="headerDivider1"></div>
                            </div>

                            <div class="col-6 col-md-2 col-sm-6 col-xs-6 prop-attr-bx">
                                <b>Rs <?php echo Yii::$app->userdata->getFormattedMoney($model->propertyListing->token_amount); ?></b>

                                <br/>
                                <span id="span_has">Token</span>
                                <div class="headerDivider1"></div>
                            </div>
                            <div class="col-6 col-md-2 col-sm-6 col-xs-6 prop-attr-bx">
                                <b><?php echo date('j M Y', strtotime($model->propertyListing->availability_from)) ?></b>
                                <br/>
                                <span id="span_has">Availability From</span>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                            <!-- <div class="md-form md-form-edit"> -->
                            <div class="allign_btn">
                                <div class="md-form ">
                                    <!--form id="myform" action="bookinginfo" method="GET">

                                 <input type="hidden" name="property_type" id="property_type" value="<?php echo $model->property_type; ?>">
                                 <input type="hidden" name="property_id" id="property_id" value="">
                                 <input type="hidden" name="parent_property_id" id="parent_property_id" value="<?php echo $model->id; ?>">
                                 <input type="hidden" value="0" name="rent" id="rent">
                                 <input type="hidden" value="<?php echo $model->propertyListing->deposit; ?>" name="deposit" id="deposit">
                                 <input type="hidden" value="<?php echo $model->propertyListing->token_amount; ?>" name="token_amount" id="token_amount">
                                 <input type="hidden" value="<?php echo $model->propertyListing->maintenance; ?>" name="maintenance" id="maintenance">
                                 <button id="btnVote" class="tag-label" type="submit" style="border-radius: 15px;background-color: #e24c3f !important;color: #fff;border: 6px solid #e24c3f !important;font-size: 12px;line-height: 1.2;">Book now</button>
                             </form-->
                                </div>
                                <div class="md-form md-form-edit col-sm-12 col-xs-12">
                                    <span class="tag-label click_operation property_visit" data-toggle="modal" data-target="#myModal111" attr-click="myModal111" style="cursor: pointer;">&nbsp;&nbsp;<i class="fa fa-calendar"></i> &nbsp;&nbsp;Arrange Property Visit&nbsp;</span>
                                </div>
                                <!-- <div class="md-form md-form-edit">
            <span class="tag-label">&nbsp;&nbsp;<?php echo date('j M Y', strtotime($model->propertyListing->availability_from)) ?>&nbsp;&nbsp;</span>
        </div> -->
                                <div class="md-form md-form-edit-2 col-sm-12 col-xs-12">
                                    <?php if (!Yii::$app->user->isGuest) { ?>
                                        <span id="moreDetailsSubmited" class="tag-label  click_operation more_deatail" style="cursor: pointer;">&nbsp;&nbsp;<i class="fa fa-question-circle" style=" font-size: 14px;  width: 10px; text-align: center; "></i>&nbsp;&nbsp; Want to Know More  &nbsp;</span>
                                    <?php } else if (!empty(Yii::$app->session['property_more_details'])) { ?>
                                        <span id="moreDetailsSubmited" class="tag-label  click_operation more_deatail" style="cursor: pointer;">&nbsp;&nbsp;<i class="fa fa-question-circle" style=" font-size: 14px;  width: 10px; text-align: center; "></i>&nbsp;&nbsp; Want to Know More  &nbsp;</span>
                                    <?php } else { ?>
                                        <span class="tag-label  click_operation more_deatail" id="moredetail" data-toggle="modal"  data-target="#myModal123" attr-click="myModal123" style="cursor: pointer;">&nbsp;&nbsp;<i class="fa fa-question-circle" style=" font-size: 14px;width: 10px; text-align: center;  "></i>&nbsp;&nbsp; Want to Know More  &nbsp;</span>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--div class="col-md-12">
                    <nav class="navbar navbar-expand-lg ">

                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <div class="collapse navbar-collapse" id="navbarSupportedContent">

                            <ul class="navbar-nav mr-auto">
                                <li class="nav-item ">
                                    <a class="nav-link medium">Over View
                                        <span class="sr-only">(current)</span>
                                    </a>
                                </li>
                                <li class="nav-item ">
                                    <a class="nav-link medium " href="#propertyfeature">Property Features</a>
                                </li>
                                <li class="nav-item ">
                                    <a class="nav-link medium" href="#amenities">Complex Amenties</a>
                                </li>
                                <li class="nav-item ">
                                    <a class="nav-link medium" href="#location">Neighbourhood</a>
                                </li>
                            </ul>

                        </div>

                    </nav>
                </div-->
            </div>
    </main>
    <!--MDB Carousels-->
    <div class="container-fluid ">
        <!--Carousel Wrapper-->
        <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
            <div class="row">
                <div id="carousel-example-1z" class="carousel slide carousel-fade " data-ride="carousel">
                    <!--Indicators-->
                    <ol class="carousel-indicators">
                        <?php
                        if (isset($dataImages)) {
                            $j = 0;
                            foreach ($dataImages as $image) {
                                ?>
                                <li data-target="#carousel-example-1z" data-slide-to="<?php echo $j; ?>" <?php if ($j == 0) { ?>class="active"<?php } ?>></li>
                                <?php
                                $j++;
                            }
                        }
                        ?>

                    </ol>
                    <!--/.Indicators-->
                    <!--Slides-->
                    <div class="carousel-inner" role="listbox">
                        <span class="badge badge-pill <?= (Yii::$app->userdata->getManagedById($model->managed_by) == 2) ? "badge-success" : "badge-primary" ?> " style="position: absolute;top: 22%;z-index: 1;right: 15px;font-size: 15px;box-shadow: none !important;font-weight: normal;padding: 8px 12px 8px 12px;">&nbsp;&nbsp; <?= Yii::$app->userdata->getManagedByString(($model->managed_by)) ?> Managed &nbsp;&nbsp;</span>
                        <span class="badge badge-pill badge-primary" style="position: absolute;bottom: 15px;z-index: 1;left: 15px;color: white;font-size: 15px; font-weight: normal;box-shadow: none !important;padding: 8px 12px 8px 12px;">&nbsp;&nbsp; For&nbsp;<?= Yii::$app->userdata->getAvailableForString(($model->gender)) ?> &nbsp;&nbsp;</span>
                        <?php
                        if (isset($dataImages)) {
                            $i = 0;
                            foreach ($dataImages as $image) {
                                ?>
                                <!--First slide-->
                                <div class="carousel-item <?php
                                if ($i == 0) {
                                    echo "active";
                                }
                                ?>">
                                    <p class="wishlist1">
                                        <a data-property="<?= Yii::$app->getRequest()->getQueryParam('id'); ?>" data-id="<?= $modelFav == 0 ? 1 : 0; ?>" title="<?= $modelFav == 0 ? 'Click to add in Favourite' : 'Click to delete from Favourite'; ?>"  class="wishlist" style="cursor:pointer;"> <img class="fav_icon" src="<?php echo Url::home(true); ?>images/<?= $modelFav == 0 ? 'dil_img.png' : 'dil_shape.png' ?>" alt=""> </a>
                                    </p>
                                    <img class="d-block w-100" src="../<?php echo $image->image_url; ?>" alt="First slide">
                                    <!--<img class="d-block w-100" src="../images/banner_slider.jpg" alt="First slide">-->
                                </div>
                                <?php
                                $i++;
                            }
                        }
                        ?>
                    </div>

                    <!--/.Slides-->
                    <!--Controls-->
                    <?php
                    if (isset($dataImages)) {

                        if (count($dataImages) > 1) {
                            ?>
                            <a class="carousel-control-prev slider_arrow"  role="button" data-slide="prev" onclick="$('#carousel-example-1z').carousel('prev')" >
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next slider_arrow"  role="button" data-slide="next" onclick="$('#carousel-example-1z').carousel('next')" >
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                            <?php
                        }
                    }
                    ?>
                    <!--/.Controls-->
                </div>
            </div>
        </div>


        <div class="col-md-5 p_des" >
            <h3 id="_second-page">Property Description</h3>
            <?php $des_len = strlen($model->property_description); ?>
            <?php if ($des_len <= 1500) { ?>
                <p class="py-10 text-center"><?php echo $model->property_description ?></p>
            <?php } else { ?>
                <p class="py-10 text-center"><?php echo substr($model->property_description, 0, 1500); ?>...<a href="" data-toggle="modal" data-target="#myModal2">See More</a></p>
            <?php } ?>
        </div>
    </div>
    <!--MDB Carousels-->
    <hr>

    <div class="modal fade" id="myModal2" role="dialog">

        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <center>
                        <h4 class="modal-title">Property Description</h4>
                    </center>
                </div>
                <div class="modal-body">
                    <p style="text-align: justify;"><?php echo $model->property_description ?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>



    <!--div class="col">
        <div class="col-6">
            <button type="button" class="btn btn-outline-danger waves-effect" style="border-radius: 17px;width: 117px;height: 26px;line-height: 3px;">Bed</button>
            <button type="button" class="btn btn-outline-danger waves-effect" style="border-radius: 17px; width: 117px;height: 26px;line-height: 3px;">Room</button>
        </div>
        <div class="col-6 ">
            <div class="col-3 pull-right">
                <b><?php echo Yii::$app->userdata->getFullNameById($model->owner_id); ?></b>
            </div>
            <div class="col-2 pull-right">
                <b><?= $model->flat_area; ?> sqft</b>
            </div>
        </div>
    </div-->
    <!--div class="container-fluid" style="margin-top: 75px;">
        <div class="col-12">
    <?php
    $dataBeds = array();
    $dataBeds = Yii::$app->userdata->getPropertyByPidBeds($model->id);
    ?>
    <?php
    $childProperties = ChildProperties::find()->where(['main' => $model->id, 'type' => 2])->all();
    if (count($childProperties) == 0) {
        ?>
                                                <b>NO Bed Is Available Right Now</b>
        <?php
    } else {
        $childPropertiesRooms = Array();
        $childPropertiesBeds = Array();
        foreach ($childProperties as $ky => $properties) {
            if (!in_array($properties->parent, $childPropertiesRooms)) {
                $childPropertiesRooms[] = $properties->parent;
            }
            if (isset($childPropertiesBeds[$properties->parent])) {
                if (!in_array($properties->id, $childPropertiesBeds[$properties->parent])) {
                    $childPropertiesBeds[$properties->parent][] = $properties->id;
                }
            } else {
                $childPropertiesBeds[$properties->parent][] = $properties->id;
            }
            $bedkey = array_keys($childPropertiesBeds[$properties->parent], $properties->id, true);
            $bedkey = $bedkey[0] + 1;
            $roomkey = array_keys($childPropertiesRooms, $properties->parent, true);
            $roomkey = $roomkey[0] + 1;
            $i = $ky + 1;
            ?>

                                                            <center>
                                                                <div class="col-3">
                                                                    <img src="../images/icons/assets_7-list-page_2018-02-19/ic-bedrooms@2x.png">
                                                                    <br/>
                                                                    <div>
                                                                        <br/>
                                                                                                 <b>Rs <?php if (!in_array($properties->id, $dataBeds) && $properties->status != 0) { ?><?= (Yii::$app->userdata->getPropertyPriceBeds($properties->id) != 2) ? Yii::$app->userdata->getPropertyPriceBeds($properties->id) : $model->propertyListing->rent; ?><?php } ?></b>
            <?php if (in_array($properties->id, $dataBeds)) { ?>
                                                                                                                         <p>Not Available</p>
                <?php
            } else {
                if ($properties->status == 1) {
                    ?>
                                                                                                                        <p>Available From</p>
                                                                                                                        <p><?php echo date('j M Y', strtotime(Yii::$app->userdata->getPropertyAvailabilityDate($properties->id))) ?></p>
                    <?php if ($properties->status == 1) { ?><input class="radiobtn" type="radio" value="<?= $properties->id; ?>" name="beds" /><?php } else { ?> <p>Not Available</p><?php
                    }
                }
            }
            ?>
                                                                        <b class="room-btn">Room <?= $roomkey ?> Bed <?= $bedkey ?></b>

                                                                    </div>
                                                                                        <div class="col-md-12">
                                                                                        <br/>
                                                                                        </div>
                                                                </div>

                                                            </center>
            <?php
        }
    }
    ?>
        </div>
        <br/>
    <hr>
    </div-->

    <div class="col-xs-12" id="propertyfeature">
        <span class="pull-left"> <b class="header-b">Property Features</b></span><br/><br/>
        <?php foreach ($features as $fea) { ?>
            <!--<div class="col-xs-12">-->
            <!--<div class="row">-->
            <div class="iconimage col-12 col-sm-12 col-md-6 col-lg-3  col-xs-12" style="padding-left: 25px; font-weight: bold;">

                <div class="col-md-3 col-sm-6 col-xs-3"><div class="row"><img src="../<?php echo trim($fea['icon']); ?>"></div></div>
                <div class="col-md-9 col-sm-6 col-xs-9 col-img">
                    <div class="row">
                        <?php echo $fea['name']; ?>  <?php echo (!empty(trim($fea['value']))) ? ' - ' . trim($fea['value']) : ''; ?>
                    </div>
                    <!--<div class="row"><span class="k_span">  </span></div>-->
                </div>
                <p>&nbsp;</p>
            </div>
            <!--</div>-->
            <!--</div>-->
        <?php } ?>
    </div>

    <div class="col-md-12"><hr></div>

    <div class="col-xs-12" id="amenities">
        <span class="pull-left"><b class="header-b">Complex / Building Amenities</b></span><br/><br/>
        <?php foreach ($amenities as $amenitie) {
            ?>
            <div class=" col-12 col-sm-12 col-md-6 col-lg-3  col-xs-12">
                <div class="row">
                    <div class="iconimage col-xs-12">
                        <div class="row">
                            <div class="col-md-3 col-sm-6 col-xs-3">
                                <div class="row" style="margin-left: 12px;">
                                    <img src="../<?php echo trim($amenitie['icon']); ?>">
                                </div>
                            </div>

                            <div class="col-md-9 col-sm-5 col-xs-9 col-img">
                                <div class="row" style="margin-left: -3px; font-weight: bold;">
                                    <?php echo $amenitie['name']; ?>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

                <br />
            </div>
            <?php
        }
        ?>

    </div>
    <div class="col-md-12">
        <hr>
    </div>


    <div class="col-md-12" id="location">
        <span class="mt-4">
            <b class="header-b">Location</b>
        </span><br/>
        <br/>


        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 pull-right location-icon">
            <div class="row">
                <ul>
                    <li><a onclick="getMapLoc1('bus_station');" id="bus_station"  role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                      <!--<i class="fa fa-plus-circle"></i>-->
                            <i class="fa fa-bus" aria-hidden="true"></i>
                            <span>Bus Stop</span></a>
                    </li>

                    <li> <a onclick="getMapLoc1('train_station');"  id="train_station" class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                      <!--<i class="fa fa-plus-circle"></i>-->
                            <i class="fa fa-train" aria-hidden="true"></i>
                            <span>Train Stations</span></a>
                    </li>
                    <li> <a onclick="getMapLoc1('park');"  id="park" class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                      <!--<i class="fa fa-plus-circle"></i>-->
                            <i class="fa fa-tree" aria-hidden="true"></i>
                            <span>Parks</span></a>
                    </li>
                    <li><a onclick="getMapLoc1('department_store');" class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                      <!--<i class="fa fa-plus-circle"></i>-->
                            <i class="fa fa-shopping-basket" aria-hidden="true"></i>
                            <span>Departmental Stores</span></a>
                    </li>
                    <li> <a onclick="getMapLoc1('bank');" id="bank" class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                      <!--<i class="fa fa-plus-circle"></i>-->
                            <i class="fa fa-money" aria-hidden="true"></i>
                            <span>Bank/ATMs</span></a>
                    </li>
                    <li><a onclick="getMapLoc1('restaurant');"  id="restaurant" class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                      <!--<i class="fa fa-plus-circle"></i>-->
                            <i class="fa fa-cutlery" aria-hidden="true"></i>
                            <span>Restaurants</span></a>
                    </li>

                </ul>
            </div>
        </div>



        <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
            <div class="row">
                <input type="hidden" id="currentType" style="display: none">
                <div id="map" class="z-depth-1"></div>
            </div>
        </div>





    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 imgvert">
<!--        <p>
            <a class="btn bookbut" id ="myModal1" style= "margin-top:0px;"><i class="fa fa-calendar-plus-o" aria-hidden="true"></i>&nbsp;Schedule a site visit
            </a>
        </p>-->

        <div class="modal fade" id="myModal111" role="dialog" data-backdrop="static">
            <div class="modal-dialog"  style="height: 400px;">

                <!-- Modal content-->


                <div class="modal-content" style="text-transform: none;">
                    <div id="schedule-visit-book-header" class="modal-header" style="border-bottom: none;">
                        <span class="sch-mess-box" style="width: 100%; font-weight: normal;"><b>Schedule Visit as per your convenience</b></span>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                    </div>
                    <div id="schedule-visit-book-body" class="modal-body" style="min-height: 50px;">
                        <?php $form = ActiveForm::begin(['id' => 'visits', 'action' => '']); ?>

                        <?= $form->field($modelvisit, 'property_id')->hiddenInput(['value' => $model->id])->label(false); ?>
                        <?= $form->field($modelvisit, 'child_properties')->hiddenInput(['value' => $model->id])->label(false); ?>
                        <?= $form->field($modelvisit, 'type')->hiddenInput(['value' => 3])->label(false); ?>
                        <div class="form-group col-md-6 col-sm-12">
                            <?= $form->field($modelvisit, 'visit_date')->textInput(['maxlength' => true, 'autocomplete' => 'off', 'placeholder' => 'Visit On', 'value' => (!empty($_SESSION['open_sch_popup_date'])) ? $_SESSION['open_sch_popup_date'] : ''/* $visitdate */])->label(false); ?>
                        </div>
                        <div class="form-group col-md-6 col-sm-12">
                            <select aria-required="true" name="PropertyVisits[visit_time]" id="propertyvisits-visit_time" class="form-control">
                                <option value=""> At </option>
                                <option <?= (!empty($_SESSION['open_sch_popup_time'])) ? ($_SESSION['open_sch_popup_time'] == '10:00') ? 'selected="selected"' : '' : '' ?> value="10:00"> 10:00 - 10:30</option>
                                <option <?= (!empty($_SESSION['open_sch_popup_time'])) ? ($_SESSION['open_sch_popup_time'] == '10:30') ? 'selected="selected"' : '' : '' ?> value="10:30"> 10:30 - 11:00</option>
                                <option <?= (!empty($_SESSION['open_sch_popup_time'])) ? ($_SESSION['open_sch_popup_time'] == '11:00') ? 'selected="selected"' : '' : '' ?> value="11:00"> 11:00 - 11:30</option>
                                <option <?= (!empty($_SESSION['open_sch_popup_time'])) ? ($_SESSION['open_sch_popup_time'] == '11:30') ? 'selected="selected"' : '' : '' ?> value="11:30"> 11:30 - 12:00</option>
                                <option <?= (!empty($_SESSION['open_sch_popup_time'])) ? ($_SESSION['open_sch_popup_time'] == '12:00') ? 'selected="selected"' : '' : '' ?> value="12:00"> 12:00 - 12:30</option>
                                <option <?= (!empty($_SESSION['open_sch_popup_time'])) ? ($_SESSION['open_sch_popup_time'] == '12:30') ? 'selected="selected"' : '' : '' ?> value="12:30"> 12:30 - 13:00</option>
                                <option <?= (!empty($_SESSION['open_sch_popup_time'])) ? ($_SESSION['open_sch_popup_time'] == '13:00') ? 'selected="selected"' : '' : '' ?> value="13:00"> 13:00 - 13:30</option>
                                <option <?= (!empty($_SESSION['open_sch_popup_time'])) ? ($_SESSION['open_sch_popup_time'] == '13:30') ? 'selected="selected"' : '' : '' ?> value="13:30"> 13:30 - 14:00</option>
                                <option <?= (!empty($_SESSION['open_sch_popup_time'])) ? ($_SESSION['open_sch_popup_time'] == '14:00') ? 'selected="selected"' : '' : '' ?> value="14:00"> 14:00 - 14:30</option>
                                <option <?= (!empty($_SESSION['open_sch_popup_time'])) ? ($_SESSION['open_sch_popup_time'] == '14:30') ? 'selected="selected"' : '' : '' ?> value="14:30"> 14:30 - 15:00</option>
                                <option <?= (!empty($_SESSION['open_sch_popup_time'])) ? ($_SESSION['open_sch_popup_time'] == '15:00') ? 'selected="selected"' : '' : '' ?> value="15:00"> 15:00 - 15:30</option>
                                <option <?= (!empty($_SESSION['open_sch_popup_time'])) ? ($_SESSION['open_sch_popup_time'] == '15:30') ? 'selected="selected"' : '' : '' ?> value="15:30"> 15:30 - 16:00</option>
                                <option <?= (!empty($_SESSION['open_sch_popup_time'])) ? ($_SESSION['open_sch_popup_time'] == '16:00') ? 'selected="selected"' : '' : '' ?> value="16:00"> 16:00 - 16:30</option>
                                <option <?= (!empty($_SESSION['open_sch_popup_time'])) ? ($_SESSION['open_sch_popup_time'] == '16:30') ? 'selected="selected"' : '' : '' ?> value="16:30"> 16:30 - 17:00</option>
                                <option <?= (!empty($_SESSION['open_sch_popup_time'])) ? ($_SESSION['open_sch_popup_time'] == '17:00') ? 'selected="selected"' : '' : '' ?> value="17:00"> 17:00 - 17:30</option>
                                <option <?= (!empty($_SESSION['open_sch_popup_time'])) ? ($_SESSION['open_sch_popup_time'] == '17:30') ? 'selected="selected"' : '' : '' ?> value="17:30"> 17:30 - 18:00</option>
                                <option <?= (!empty($_SESSION['open_sch_popup_time'])) ? ($_SESSION['open_sch_popup_time'] == '18:00') ? 'selected="selected"' : '' : '' ?> value="18:00"> 18:00 - 18:30</option>
                                <option <?= (!empty($_SESSION['open_sch_popup_time'])) ? ($_SESSION['open_sch_popup_time'] == '18:30') ? 'selected="selected"' : '' : '' ?> value="18:30"> 18:30 - 19:00</option>
                                <option <?= (!empty($_SESSION['open_sch_popup_time'])) ? ($_SESSION['open_sch_popup_time'] == '19:00') ? 'selected="selected"' : '' : '' ?> value="19:00"> 19:00 - 19:30</option>
                                <option <?= (!empty($_SESSION['open_sch_popup_time'])) ? ($_SESSION['open_sch_popup_time'] == '19:30') ? 'selected="selected"' : '' : '' ?> value="19:30"> 19:30 - 20:00</option>
                                <option <?= (!empty($_SESSION['open_sch_popup_time'])) ? ($_SESSION['open_sch_popup_time'] == '20:00') ? 'selected="selected"' : '' : '' ?> value="20:00"> 20:00 - 20:30</option>
                                <option <?= (!empty($_SESSION['open_sch_popup_time'])) ? ($_SESSION['open_sch_popup_time'] == '20:30') ? 'selected="selected"' : '' : '' ?> value="20:30"> 20:30 - 21:00</option>
                                <option <?= (!empty($_SESSION['open_sch_popup_time'])) ? ($_SESSION['open_sch_popup_time'] == '21:00') ? 'selected="selected"' : '' : '' ?> value="21:00"> 21:00 - 21:30</option>
                            </select>
                            <?php
                            unset($_SESSION['open_sch_popup_date']);
                            unset($_SESSION['open_sch_popup_time']);
                            ?>
                            <div class="help-block visit-time-help-block" style="display: none;">Visit time cannot be blank.</div>
                            <ul class='time_suggestion' style='display: none'>

                            </ul>
                        </div>


                        <div class="form-group col-md-12 col-sm-12">
                            <?= Html::Button($modelvisit->isNewRecord ? 'Confirm Schedule' : 'Confirm Schedule', ['id' => 'visitsubmit', 'class' => $modelvisit->isNewRecord ? 'btn btn-danger' : 'btn btn-danger', 'style' => 'width: 46%;']) ?>
                            <?= Html::Button('Cancel', ['class' => 'btn btn-grey cust-grybtn', 'onclick' => (!empty($_GET['opu'])) ? 'cancelSchedule2()' : 'cancelSchedule()', 'style' => 'width: 46%;']) ?>
                        </div>

                        <?php ActiveForm::end(); ?>

                        <?php $form = ActiveForm::begin(['id' => 'visits-book-form', 'action' => Url::home(true) . 'paytm/bookproperty']); ?>
                        <?= $form->field($modelvisit, 'property_id')->hiddenInput(['value' => $model->id])->label(false); ?>
                        <?= $form->field($modelvisit, 'child_properties')->hiddenInput(['value' => $model->id])->label(false); ?>
                        <?= $form->field($modelvisit, 'type')->hiddenInput(['value' => 3])->label(false); ?>
                        <input type="hidden" name="app_redirect_url" value="<?php echo Url::home(true) ?>site/view?id=<?= @$_GET['id'] ?>">
                        <?php echo $form->field($modelvisit, 'txn_amount')->hiddenInput(['value' => $model->propertyListing->token_amount])->label(false); ?>
                        <div class="form-group col-md-12 col-sm-12">
                            <?= Html::Button($modelvisit->isNewRecord ? 'Book Now' : 'Book Now', ['type' => 'submit', 'id' => 'visitsubmit-book', 'class' => $modelvisit->isNewRecord ? 'btn btn-danger' : 'btn btn-danger', 'style' => 'width: 46%;']) ?>
                            <?= Html::Button('Close', ['class' => 'btn btn-grey', 'onclick' => (!empty($_GET['opu'])) ? 'cancelBooking2()' : 'cancelBooking()', 'style' => 'width: 46%;']) ?>
                        </div>
                        <?php ActiveForm::end(); ?>

                    </div>
                </div>

            </div>
        </div>
    </div>
    <!--##########  More details Model ##################-->
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 imgvert">
<!--        <p>
            <a class="btn bookbut" id ="myModal1" style= "margin-top:0px;"><i class="fa fa-calendar-plus-o" aria-hidden="true"></i>&nbsp;Schedule a site visit
            </a>
        </p>-->

        <div class="modal fade" id="myModal123" role="dialog" data-backdrop="static">
            <div class="modal-dialog"  style="height: 400px;">

                <!-- Modal content-->


                <div class="modal-content" style="text-transform: none;">
                    <div id="schedule-visit-book-header" class="modal-header" style="border-bottom: none;">
                        <span class="sch-mess-box" style="width: 100%; font-weight: normal;"><b>Submit Your Details To Know More</b></span>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                    </div>
                    <div id="schedule-visit-book-body" class="modal-body" style="min-height: 50px;">
                        <?php $form = ActiveForm::begin(['id' => 'moreDeatailss', 'action' => 'moreDeatails', 'options' => ['onsubmit' => 'moreDetails()']]); ?>
                        <input type="hidden" name="more_detail_single" value="1" />
                        <?= $form->field($modelvisit, 'property_id')->hiddenInput(['value' => $model->id])->label(false); ?>
                        <?= $form->field($modelvisit, 'child_properties')->hiddenInput(['value' => $model->id])->label(false); ?>
                        <?= $form->field($modelvisit, 'type')->hiddenInput(['value' => 3])->label(false); ?>
                        <div class="form-group col-md-12 col-sm-12">
                            <input type="text" name="Visiter_Name" id="moreDetails_name" placeholder="Enter Your Name" >
                        </div>
                        <div class="form-group col-md-12 col-sm-12">
                            <input type="email" name="Visiter_Email" id="moreDetails_email" placeholder="Enter Your Email" >
                        </div>
                        <div class="form-group col-md-12 col-sm-12">
                            <input type="text" name="Visiter_Contact" id="moreDetails_contact" placeholder="Enter Your Phone" class="isPhone" maxlength="15">
                        </div>
                        <div class="form-group col-md-12 col-sm-12">
                            <?= Html::Button($modelvisit->isNewRecord ? ' Send' : 'Confirm Schedule', ['id' => 'moreDetailsSubmit', 'data-dismiss' => "modal", 'class' => 'custum.close', 'class' => $modelvisit->isNewRecord ? 'btn btn-danger' : 'btn btn-danger', 'style' => 'width: 46%;']) ?>
                            <?= Html::Button('Cancel', ['class' => 'btn btn-grey cust-grybtn', 'onclick' => (!empty($_GET['opu'])) ? 'cancelSchedule2()' : 'cancelKnowmore()', 'style' => 'width: 46%;']) ?>
                        </div>

                        <?php ActiveForm::end(); ?>

                        <?php $form = ActiveForm::begin(['id' => 'visits-book-form', 'action' => Url::home(true) . 'paytm/bookproperty']); ?>
                        <?= $form->field($modelvisit, 'property_id')->hiddenInput(['value' => $model->id])->label(false); ?>
                        <?= $form->field($modelvisit, 'child_properties')->hiddenInput(['value' => $model->id])->label(false); ?>
                        <?= $form->field($modelvisit, 'type')->hiddenInput(['value' => 3])->label(false); ?>
                        <input type="hidden" name="app_redirect_url" value="<?php echo Url::home(true) ?>site/view?id=<?= @$_GET['id'] ?>">
                        <?php echo $form->field($modelvisit, 'txn_amount')->hiddenInput(['value' => $model->propertyListing->token_amount])->label(false); ?>
                        <div class="form-group col-md-12 col-sm-12">
                            <?= Html::Button($modelvisit->isNewRecord ? 'Book Now' : 'Book Now', ['type' => 'submit', 'id' => 'visitsubmit-book', 'class' => $modelvisit->isNewRecord ? 'btn btn-danger' : 'btn btn-danger', 'style' => 'width: 46%;']) ?>
                            <?= Html::Button('Close', ['class' => 'btn btn-grey', 'onclick' => (!empty($_GET['opu'])) ? 'cancelBooking2()' : 'cancelBooking()', 'style' => 'width: 46%;']) ?>
                        </div>
                        <?php ActiveForm::end(); ?>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <script type="text/javascript">

                $(document).ready(function () {
                    $('#propertyvisits-visit_time').change(function () {
                        if ($(this).val() == '') {
                            $('.visit-time-help-block').css('display', 'block');
                        } else {
                            $('.visit-time-help-block').css('display', 'none');
                        }
                    });
                    // Add smooth scrolling to all links
                    $("a").on('click', function (event) {

                        // Make sure this.hash has a value before overriding default behavior
                        if (this.hash !== "") {
                            // Prevent default anchor click behavior
                            event.preventDefault();

                            // Store hash
                            var hash = this.hash;

                            // Using jQuery's animate() method to add smooth page scroll
                            // The optional number (800) specifies the number of milliseconds it takes to scroll to the specified area
                            $('html, body').animate({
                                scrollTop: $(hash).offset().top
                            }, 800, function () {

                                // Add hash (#) to URL when done scrolling (default click behavior)
                                window.location.hash = hash;
                            });
                        } // End if
                    });
                });

                $(document).ready(function () {
                    $('ul li a').click(function () {
                        $('li a').removeClass("active");
                        $(this).addClass("active");
                    });
                });


                $(function () {
                    $('[data-toggle="tooltip"]').tooltip()
                })

    </script>
    <script>
                var header = $("#test");
                $(window).scroll(function () {
                    var scroll = $(window).scrollTop();
                    if (scroll >= window.innerHeight) {
                        header.addClass("fix-data");
                    } else {
                        header.removeClass("fix-data");
                    }
                });
                $(function () {
                    // When the window is resized, check the size to determine your classes
                    $(window).resize(function () {
                        // When the width and height meet your specific requirements or lower
                        if (($(window).width() <= 1024) && ($(window).height() <= 768)) {
                            // If it is smaller or equal to 1024x768, apply your class
                            $(".normal").addClass('smaller');
                            //   $(".mt-10").addClass('fix-data');
                            // alert("<1024");
                        } else {
                            // Otherwise, reverse the classes
                            $(".smaller").removeClass('smaller').addClass('normal');
                            // alert("1024");
                        }
                    });
                });
                $(function () {
                    // When the window is resized, check the size to determine your classes
                    $(window).resize(function () {
                        // When the width and height meet your specific requirements or lower
                        if (($(window).width() <= 1024) && ($(window).height() <= 768)) {
                            // If it is smaller or equal to 1024x768, apply your class
                            //   $(".normal").addClass('smaller');
                            $(".mydata").addClass('fix-data1');
                            //alert("<1024");
                        } else {
                            // Otherwise, reverse the classes
                            $(".fix-data").removeClass('fix-data').addClass('.mt-10');
                            // alert("1024");
                        }
                    });
                });
    </script>
    <script type="text/javascript">


                $(document).ready(function () {
                    // Add smooth scrolling to all links
                    $("a").on('click', function (event) {

                        // Make sure this.hash has a value before overriding default behavior
                        if (this.hash !== "") {
                            // Prevent default anchor click behavior
                            event.preventDefault();

                            // Store hash
                            var hash = this.hash;

                            // Using jQuery's animate() method to add smooth page scroll
                            // The optional number (800) specifies the number of milliseconds it takes to scroll to the specified area
                            $('html, body').animate({
                                scrollTop: $(hash).offset().top
                            }, 800, function () {

                                // Add hash (#) to URL when done scrolling (default click behavior)
                                window.location.hash = hash;
                            });
                        } // End if
                    });

<?php if ($modelFav) { ?>
                        $(document).on('click', '.click_operation', function (e) {
                            $('#propertyvisits-visit_date').val('<?= Yii::$app->userdata->getScheduledDateById($_GET['id']) ?>');
                            $('#propertyvisits-visit_time').val('<?= Yii::$app->userdata->getScheduledTimeById($_GET['id']) ?>');
                        })
<?php } ?>
                });

                $(document).ready(function () {
                    $('ul li a').click(function () {
                        $('li a').removeClass("active");
                        $(this).addClass("active");
                    });
                });
                $(document).ready(function () {

                    $(document).on("scroll", onScroll);

                    //smoothscroll
                    $('a[href^="#"]').on('click', function (e) {
                        e.preventDefault();
                        $(document).off("scroll");

                        $('a').each(function () {
                            $(this).removeClass('active');
                        })
                        $(this).addClass('active');

                        var target = this.hash,
                                menu = target;
                        $target = $(target);
                        $('html, body').stop().animate({
                            'scrollTop': $target.offset().top + 2
                        }, 500, 'swing', function () {
                            window.location.hash = target;
                            $(document).on("scroll", onScroll);
                        });
                    });
                });

                function onScroll(event) {

                    var scrollPos = $(document).scrollTop();
                    $('#navbarSupportedContent a').each(function () {
                        var currLink = $(this);
                        var refElement = $(currLink.attr("href"));
                        if (refElement.position().top <= scrollPos && refElement.position().top + refElement.height() > scrollPos) {
                            $('#navbarSupportedContent ul li a').removeClass("active");
                            currLink.addClass("active");
                        } else {
                            currLink.removeClass("active");
                        }
                    });
                }

                $(document).ready(function () {
                    $("#moreDetailsSubmited").click(function () {
                        startLoader();
                        $.ajax({
                            url: 'moredetailssingle?id=<?php echo @$_GET['id'] ?>',
                            //url meaning 
                            type: 'post',
                            data: {},
                            dataType: 'text',
                            success: function (data) {
                                var res = data.toString();
                                res = res.replace(/\s+/g, '');
                                hideLoader();
                                if (res == '1') {
                                    //v$(function () {
                                    // $(".custom-close").on('click', function() {
                                    //$('#myModal123').modal('hide');
                                    //});
                                    //});
                                    alert('Thanks for showing interest in this property. Our team will contact you shortly.');
                                } else {
                                    alert('Please try after some time!');
                                }
                            },
                            error: function (a, b, c) {
                                hideLoader();
                                alert('Please check your internet connection.');
                            }
                        });
                    });

                    $("#moreDetailsSubmit").click(function () {
                        var name = $('#moreDetails_name').val();
                        var email = $('#moreDetails_email').val();
                        var contact = $('#moreDetails_contact').val();

                        if (name == '') {
                            alert('Please enter your name');
                            return false;
                        }

                        if (email == '') {
                            alert('Please enter your email');
                            return false;
                        }

                        if (IsEmail(email) == false) {
                            alert('Please enter correct email');
                            return false;
                        }

                        if (contact == '') {
                            alert('Please enter your contact number');
                            return false;
                        }
                        startLoader();
                        $.ajax({
                            url: 'moredetailssingle?id=<?php echo @$_GET['id'] ?>',
                            //url meaning 
                            type: 'post',
                            data: $('#moreDeatailss').serialize(),
                            dataType: 'text',
                            success: function (data) {
                                var res = data.toString();
                                res = res.replace(/\s+/g, '');
                                hideLoader();
                                if (res == '1') {
                                    //alert('Thanks for showing interest in this property. Our team will contact you shortly.');
                                    //  $(".closeModal").click(function () {
                                    //  $(".modal").modal("hide");
                                    //   });  
                                    alert('Thanks for showing interest in this property. Our team will contact you shortly.');
                                    window.location.reload();
                                } else {
                                    alert('Please try after some time!');
                                }
                            },
                            error: function (a, b, c) {
                                hideLoader();
                                alert('Please check your internet connection.');
                            }
                        });
                    });
                });

                function IsEmail(email) {
                    var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                    if (!regex.test(email)) {
                        return false;
                    } else {
                        return true;
                    }
                }
    </script>

    <script>
                $('.sch-mess-box').css('text-align', 'center');
                $(document).on('click', '#visitsubmit', function (e) {
                    var full = $('#propertyvisits-visit_date').val() + ' ' + $('#propertyvisits-visit_time').val() + ':00';
                    if ($('#propertyvisits-visit_time').val() == '') {
                        $('.visit-time-help-block').css('display', 'block');
                        return;
                    } else {
                        $('.visit-time-help-block').css('display', 'none');
                    }
                    var d = new Date(full);
                    var nowa = new Date();
                    nowa = new Date(nowa.setHours(nowa.getHours() + 4));
                    if (d <= nowa) {
                        alert('The schedule date/time should be 4 hours later than current date/time');
                    } else {
                        $.ajax({
                            url: 'view?id=<?php echo @$_GET['id'] ?>',
                            type: 'post',
                            data: $('#visits').serialize(),
                            success: function (data) {
                                if ($("#propertyvisits-txn_amount").val() == 0) {
                                    cancelSchedule(false);
                                } else {
                                    $('.sch-mess-box').css('text-align', 'justify');
                                    $('#schedule-visit-book-header span').html('<div class="font-weight: bold; text-align: center;" style="text-align: center;"><b>Schedule Confirmed</b> </div> <br /> Thanks for showing interest in property: <?php echo $model->property_name; ?>. We look forward to hosting you during the visit. Our representative would be in touch with you to co-ordinate the visit. <br /><br /> Would you also like to book this property by paying token amount?');
                                    $('#visits').css('display', 'none');
                                    $('#visits-book-form').css('display', 'block');
                                }
                            },
                            error: function (a, b, c) {
                                alert('Please login to schedule your visit.');
                            }
                        });
                    }
                });
                $(document).on('change', '#propertyvisits-visit_date', function () {
                    var time = $('#propertyvisits-visit_time').val();
                    //alert($(this).val()+' '+time);
                });
                $(document).on('focusout', '#propertyvisits-visit_time', function () {
                    var date = $('#propertyvisits-visit_date').val();
                    //alert(date+' '+$(this).val());
                });

                function cancelSchedule(a = true) {
                    if (a) {
                        var con = confirm("Do you really want to cancel the visit schedule?");
                        if (con) {
                            location.reload();
                        }
                    } else {
                        location.reload();
                }
                }
                function cancelKnowmore(a = true) {
                    if (a) {
                        var con = confirm("Do you really want to cancel know more details?");
                        if (con) {
                            location.reload();
                        }
                    } else {
                        location.reload();
                }
                }

                function cancelSchedule2() {
                    var con = confirm("Do you really want to cancel the visit schedule?");
                    if (con) {
                        $('#myModal111').modal('hide');
                    }
                }

                function cancelBooking() {
                    location.reload();
                }

                function cancelBooking2() {
                    //$('#myModal').modal('hide');
                    window.location.href = window.location.href.split("&")[0];
                }

    </script>
    <script>
                $(document).ready(function () {
<?php if (!empty($_GET['opu'])) { ?>
                        //$('.openpopup').click();
                        $('#myModal111').modal('show');
<?php } ?>
                });
    </script>
</body>