<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\authclient\widgets\AuthChoice;

$this->title = 'Tenant';


/* @var $this yii\web\View */
/* @var $model app\models\Users */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="signup_conatiner">

    <header id="myCarousel" class="carousel slide">
        <!-- Wrapper for Slides -->
        <div class="carousel-inner">
            <div class="item active">
                <!-- Set the first background image using inline CSS below. -->
                <div class="fill" style="background-image:url('../../web/images/headerimg.jpg');"></div>
                <div class="carousel-caption">
                    <h1 class="brand-heading"><img src="../../web/images/easy_lease.png" alt="" pagespeed_url_hash="2213764189" onload="pagespeed.CriticalImages.checkImageForCriticality(this);"></h1>
                </div>
                <a href="#tenant" class="btn btn-circle page-scroll"> <img src="../../web/images/arrow_down.png" alt="" pagespeed_url_hash="1740732048" onload="pagespeed.CriticalImages.checkImageForCriticality(this);"> </a> 
            </div>
        </div>
        <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans" />
    </header>
    <section id="tenant" class="content-section text-center" style="margin-top: 1%;">
        <div class="container">
            <div class="row">
                <!--tenant-->
                <div class="col-lg-12 col-xs-12 headingtenant">Tenant</div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="leftbox">
                        <div class="circleimg"><img src="../../web/images/tenenticon1.png" alt="" pagespeed_url_hash="761903604" onload="pagespeed.CriticalImages.checkImageForCriticality(this);"></div>
                        <h1>Flats for families</h1>
                        <p>Ready to move in Semi or fully furnished flats 
                            professionally managed by Easy Leases</p>
                        <div class="circlebox"></div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="leftbox pull-right">
                        <div class="circleimg"><img src="../../web/images/tenenticon2.png" alt="" pagespeed_url_hash="1056403525" onload="pagespeed.CriticalImages.checkImageForCriticality(this);"></div>
                        <h1>Co-living for boys &amp; girls</h1>
                        <p>Ready to move in shared living spaces ideal for <br>
                            bachelors, young workers that are fully furnished and <br>
                            come with DTH, wifi and branded furnishing </p>
                        <div class="circleboxright"></div>
                    </div>
                </div>
            </div>
            <!--benefits-->
            <div class="row">
                <div class="col-lg-12 benfitsboxnew">
                    <p><img src="../../web/images/icon3.png" alt="" pagespeed_url_hash="418475756" onload="pagespeed.CriticalImages.checkImageForCriticality(this);">Benefits</p>
                    <ul>
                        <li><a href="javascript:;"><img src="../../web/images/benifit_icon1.png" alt="" pagespeed_url_hash="2641326486" onload="pagespeed.CriticalImages.checkImageForCriticality(this);"><span>Zero brokerage</span></a></li>
                        <li><a href="javascript:;"><img src="../../web/images/benifit_icon2.png" alt="" pagespeed_url_hash="2935826407" onload="pagespeed.CriticalImages.checkImageForCriticality(this);"><span>Fair and fixed pricing based on market research, apartment facilities and not on negotiation</span></a></li>
                        <li><a href="javascript:;"><img src="../../web/images/benifit_icon3.png" alt="" pagespeed_url_hash="3230326328" onload="pagespeed.CriticalImages.checkImageForCriticality(this);"><span>Online listings with comprehensive property features, photos and geo location allowing you to make informed choice</span></a></li>
                        <li><a href="javascript:;"><img src="../../web/images/benifit_icon4.png" alt="" pagespeed_url_hash="3524826249" onload="pagespeed.CriticalImages.checkImageForCriticality(this);"><span>Online search and scheduling as per your convenience</span></a></li>
                        <li><a href="javascript:;"><img src="../../web/images/benifit_icon5.png" alt="" pagespeed_url_hash="3819326170" onload="pagespeed.CriticalImages.checkImageForCriticality(this);"><span>Instant online booking by paying token amount and avoid regrets on missing out on a great property of your choice</span></a></li>
                        <li><a href="javascript:;"><img src="../../web/images/benifit_icon6.png" alt="" pagespeed_url_hash="4113826091" onload="pagespeed.CriticalImages.checkImageForCriticality(this);"><span>Easy contract process: Fair &amp; standardized documentation</span></a></li>
                        <li><a href="javascript:;"><img src="../../web/images/benifit_icon7.png" alt="" pagespeed_url_hash="113358716" onload="pagespeed.CriticalImages.checkImageForCriticality(this);"><span>Easy move in: Properties are in our possession and ready to move in on committed date</span></a></li>
                        <li><a href="javascript:;"><img src="../../web/images/benifit_icon8.png" alt="" pagespeed_url_hash="407858637" onload="pagespeed.CriticalImages.checkImageForCriticality(this);"><span>Technology platform providing transparent view of all past and future rental forecast</span></a></li>
                        <li><a href="javascript:;"><img src="../../web/images/benifit_icon9.png" alt="" pagespeed_url_hash="702358558" onload="pagespeed.CriticalImages.checkImageForCriticality(this);"><span>Hassle free living: Interact directly with Easy Leases for all maintenance issues through lease term hence eliminating dependency on property owners</span></a></li>
                    </ul>
                </div>
                <!--how to work-->
                <div class="col-lg-12 benfitsbox">
                    <p style= "font-size: 20px !important;">How it works</p>
                    <div class="col-md-4 col-sm-4 boxshpae1">
                        <div class="rectbox">
                            <div class="circleboxshape">1</div>
                        </div>
                        <div id="triangle-down"></div>
                        <p>Search property</p>
                    </div>
                    <div class="col-md-4 col-sm-4 boxshpae1">
                        <div class="rectbox2">
                            <div class="circleboxshape2">2</div>
                        </div>
                        <div id="triangle-down2"></div>
                        <p>Schedule a visit or book online. Book 
                            online will reserve the property for you 
                            and will not be rented out to others</p>
                    </div>
                    <div class="col-md-4 col-sm-4 boxshpae1">
                        <div class="rectbox3">
                            <div class="circleboxshape3">3</div>
                        </div>
                        <div id="triangle-down3"></div>
                        <p>Visit the property. If you booked the 
                            property and you  didn�t like it then 
                            booking amount is refunded to you</p>
                    </div>
                    <div class="col-md-4 col-sm-4 boxshpae1">
                        <div class="rectbox4">
                            <div class="circleboxshape4">4</div>
                        </div>
                        <div id="triangle-down4"></div>
                        <p>Complete Documentation: Provide 
                            all KYC documentations and pay 
                            deposit amount</p>
                    </div>
                    <div class="col-md-4 col-sm-4 boxshpae1">
                        <div class="rectbox5">
                            <div class="circleboxshape5">5</div>
                        </div>
                        <div id="triangle-down5"></div>
                        <p>Move in on the agreed move in date</p>
                    </div>

                    <div class="col-md-4 col-sm-4 boxshpae1">
                        <div class="rectbox6">
                            <div class="arrowimgdown"><img src="../../web/images/arrowimg.png" pagespeed_url_hash="4293311546" onload="pagespeed.CriticalImages.checkImageForCriticality(this);"></div>
                            <div class="circleboxshape6">6</div>
                        </div>
                        <div id="triangle-down6"></div>
                        <p>Ongoing support and relationship management </p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <style>
        @media only screen and (min-width:768px) and (max-width:1023px){
            .arrowimgdown {
                position: absolute;
                right: -45px !important;
                top: -91px !important;
                width: 100% !important;
            }
            .benfitsboxnew ul li a img {
                width: 50px;
            }
            .circleimg {
                background: #4b0082 none repeat scroll 0 0;
                border: 2px solid #dcbcf4;
                border-radius: 100%;
                height: 90px !important;
                margin: auto auto 20px !important; 
                padding: 14px !important;
                width: 90px !important;
            }
            .leftbox > h1 {
                color: #545454;
                font-family: "Open Sans",sans-serif;
                font-size: 16px !important;
                margin-bottom: 9px;
                text-transform: capitalize;
            }
            .leftbox > p {
                color: #919191;
                font-size: 14px !important;
                font-family: "Open Sans",sans-serif;
            }
            .headingtenant {
                color: #347c17;
                font-family: "Open Sans",sans-serif;
                font-size: 20px !important;
                padding-bottom: 20px;
            }
            .benfitsboxnew p {
                color: #347c17;
                font-family: "Open Sans",sans-serif;
                font-size: 20px !important;
                text-align: left;
                clear: both;
                margin-bottom: 20px;
            }
            .fill {
                background-size: 100%;
                height: 360px !important;
                background-repeat: no-repeat;
            }
            .carousel-caption {
                bottom: inherit !important;
                color: #ffffff;
                left: 0% !important;
                padding-bottom: 20px;
                padding-top: 20px;
                position: absolute;
                right: 0% !important;
                text-align: center;
                text-shadow: 0 1px 2px rgba(0, 0, 0, 0.6);
                top: 0% !important;
                z-index: 10;
            }
            .brand-heading img {
                width: 100%;
            }
            .leftbox {
                background: #f6f6f6 none repeat scroll 0 0;
                border: 1px solid #c9c9c9;
                min-height: 270px !important;
                padding: 20px 0 0 !important;
                position: relative;
                width: 93%;
                margin: 0 auto;
                margin-left: auto;
            }
            .container {
                margin-right: auto;
                margin-left: auto;
                padding-left: 15px;
                padding-right: 15px;
            }
            .benfitsboxnew li {
                color: #d3d3d3;
                font-family: "Open Sans",sans-serif;
                font-size: 12px !important;
                list-style: outside none none;
            }
            .benfitsboxnew li {
                border: 1px solid #cccccc;
                color: #d3d3d3;
                float: left;
                font-family: "Open Sans",sans-serif;
                font-size: 12px;
                list-style: outside none none;
                margin-bottom: 11px;
                margin-right: 13px;
                min-height: 125px !important;
                padding: 13px 0 10px 10px;
                padding-top: 13px;
                width: 32.0% !important;
            }
            .col-lg-12.benfitsboxnew p img {
                width: 40px;
            }
            .boxshpae1 p {
                color: #919191;
                font-family: "Open Sans",sans-serif;
                font-size: 12px !important;
                margin: auto;
                padding-top: 11px;
                text-align: center;
                width: 80%;
            }

        }

        .fill {
            background-size: 100%;
            height: 660px;
        }
        .carousel-caption {
            bottom: inherit !important;
            color: #ffffff;
            left: 15%;
            padding-bottom: 20px;
            padding-top: 20px;
            position: absolute;
            right: 15%;
            text-align: center;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.6);
            top: 33%;
            z-index: 10;
        }
        .btn-circle {
            background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
            border: 2px solid #838383;
            border-radius: 100% !important;
            bottom: 6%;
            color: #ffffff;
            font-size: 40px;
            height: 70px;
            left: 0;
            margin: auto;
            padding: 0 16px;
            position: absolute;
            right: 0;
            text-align: center;
            transition: background 0.3s ease-in-out 0s;
            width: 70px;
        }
        .headingtenant {
            color: #347c17;
            font-family: "Open Sans",sans-serif;
            font-size: 28px;
            padding-bottom: 20px;
        }
        .leftbox {
            background: #f6f6f6 none repeat scroll 0 0;
            border: 1px solid #c9c9c9;
            min-height: 452px;
            padding: 85px 0 0;
            position: relative;
            width: 93%;
            margin: 0 auto;
        }
        .circleimg {
            background: #4b0082 none repeat scroll 0 0;
            border: 2px solid #dcbcf4;
            border-radius: 100%;
            height: 140px;
            margin: auto auto 47px;
            padding: 38px;
            width: 140px;
        }
        .leftbox > h1 {
            color: #545454;
            font-family: "Open Sans",sans-serif;
            font-size: 25px;
            margin-bottom: 9px;
            text-transform: capitalize;
        }
        .text-center {
            text-align: center;
        }
        .benfitsboxnew p {
            color: #347c17;
            font-family: "Open Sans",sans-serif;
            font-size: 28px;
            text-align: left;
            clear: both;
            margin-bottom: 20px;
        }
        .leftbox > p {
            color: #919191;
            font-size: 19px;
            font-family: "Open Sans",sans-serif;
        }
        .benfitsboxnew > ul {
            margin: 0;
            padding: 0;
            text-align: left;
        }
        .benfitsboxnew li:nth-child(1) {
            padding-top: 31px;
        }
        .benfitsboxnew li {
            border: 1px solid #cccccc;
            color: #d3d3d3;
            float: left;
            font-family: "Open Sans",sans-serif;
            font-size: 14px;
            list-style: outside none none;
            margin-bottom: 11px;
            margin-right: 9px;
            min-height: 110px;
            padding: 13px 0 10px 10px;
            width: 32.8%;
        }
        .benfitsboxnew a {
            color: #919191;
            text-decoration: none;
        }
        .leftbox {
            background: #f6f6f6 none repeat scroll 0 0;
            border: 1px solid #c9c9c9;
            min-height: 452px;
            padding: 85px 0 0;
            position: relative;
            width: 93%;
            margin: 0 auto;
            margin-left: 0px !important;
        }
        .benfitsboxnew li img {
            float: left;
            height: auto;
            padding: 1px 12px 22px 8px;
        }
        .benfitsboxnew li:nth-child(1) span {
            float: left;
            padding-top: 10px;
        }
        .benfitsboxnew li:nth-child(2) {
            padding-top: 25px;
        }
        .benfitsboxnew li span {
            padding-right: 8px;
        }
        .benfitsboxnew li:nth-child(3) img {
            padding-top: 14px;
        }
        .benfitsboxnew li:nth-child(4) {
            padding-top: 29px;
        }
        .benfitsboxnew li:nth-child(5) {
            padding-right: 18px;
            padding-top: 23px;
        }
        .benfitsboxnew li:nth-child(6) {
            padding-top: 34px;
        }
        .benfitsboxnew li:nth-child(7) {
            padding-top: 24px;
        }
        .benfitsboxnew li:nth-child(8) {
            padding-top: 31px;
        }
        .benfitsboxnew li:nth-child(8) img {
            padding-top: 9px;
        }
        .benfitsboxnew li:nth-child(9) img {
            padding-top: 19px;
        }
        .benfitsboxnew li:nth-child(3n+3) {
            margin-right: 0px;
        }
        .benfitsboxnew {
            padding-top: 20px;
        }
        .benfitsboxnew > p img {
            padding-right: 11px;
        }
        .benfitsbox p {
            color: #347c17;
            font-family: "Open Sans",sans-serif;
            font-size: 28px;
            text-align: left;
            padding-top: 20px;
        }
        .boxshpae1 p {
            color: #919191;
            font-family: "Open Sans",sans-serif;
            font-size: 14px;
            margin: auto;
            padding-top: 11px;
            text-align: center;
            width: 80%;
        }
        .circleboxshape {
            background: #ececec none repeat scroll 0 0;
            border: 6px solid #9f25fa;
            border-radius: 100%;
            color: #9f25fa;
            font-size: 50px;
            height: 100px;
            left: 0;
            margin: auto;
            position: absolute;
            right: 0;
            top: -45px;
            width: 100px;
            font-family: "Open Sans",sans-serif;
        }
        .rectbox {
            background: #9f25fa none repeat scroll 0 0;
            height: 107px;
            margin: auto;
            position: relative;
            width: 185px;
        }
        #triangle-down {
            border-left: 100px solid rgba(0, 0, 0, 0);
            border-right: 100px solid rgba(0, 0, 0, 0);
            border-top: 73px solid #9f25fa;
            height: 0;
            margin: auto;
            top: 0;
            width: 0;
        }
        .boxshpae1 {
            margin: 60px 0 0;
            min-height: 290px;
        }
        .circleboxshape2 {
            background: #ececec none repeat scroll 0 0;
            border: 6px solid #8501e7;
            border-radius: 100%;
            color: #8501e7;
            font-size: 50px;
            height: 100px;
            left: 0;
            margin: auto;
            position: absolute;
            right: 0;
            top: -45px;
            width: 100px;
            font-family: "Open Sans",sans-serif;
        }
        .rectbox2 {
            background: #8501e7 none repeat scroll 0 0;
            height: 107px;
            margin: auto;
            position: relative;
            width: 185px;
        }
        #triangle-down2 {
            border-left: 100px solid rgba(0, 0, 0, 0);
            border-right: 100px solid rgba(0, 0, 0, 0);
            border-top: 73px solid #8501e7;
            height: 0;
            margin: auto;
            top: 0;
            width: 0;
        }
        .circleboxshape3 {
            background: #ececec none repeat scroll 0 0;
            border: 6px solid #7100c5;
            border-radius: 100%;
            color: #7100c5;
            font-size: 50px;
            height: 100px;
            left: 0;
            margin: auto;
            position: absolute;
            right: 0;
            top: -45px;
            width: 100px;
            font-family: "Open Sans",sans-serif;
        }
        .rectbox3 {
            background: #7100c5 none repeat scroll 0 0;
            height: 107px;
            margin: auto;
            position: relative;
            width: 185px;
        }
        #triangle-down3 {
            border-left: 100px solid rgba(0, 0, 0, 0);
            border-right: 100px solid rgba(0, 0, 0, 0);
            border-top: 73px solid #7100c5;
            height: 0;
            margin: auto;
            top: 0;
            width: 0;
        }
        .boxshpae1 p {
            color: #919191;
            font-family: "Open Sans",sans-serif;
            font-size: 14px;
            margin: auto;
            padding-top: 11px;
            text-align: center;
            width: 80%;
        }
        .circleboxshape4 {
            background: #ececec none repeat scroll 0 0;
            border: 6px solid #6401ad;
            border-radius: 100%;
            color: #6401ad;
            font-size: 50px;
            height: 100px;
            left: 0;
            margin: auto;
            position: absolute;
            right: 0;
            top: -45px;
            width: 100px;
            font-family: "Open Sans",sans-serif;
        }
        .rectbox4 {
            background: #6401ad none repeat scroll 0 0;
            height: 107px;
            margin: auto;
            position: relative;
            width: 185px;
        }
        #triangle-down4 {
            border-left: 100px solid rgba(0, 0, 0, 0);
            border-right: 100px solid rgba(0, 0, 0, 0);
            border-top: 73px solid #6401ad;
            height: 0;
            margin: auto;
            top: 0;
            width: 0;
        }
        .circleboxshape5 {
            background: #ececec none repeat scroll 0 0;
            border: 6px solid #5a0499;
            border-radius: 100%;
            color: #5a0499;
            font-size: 50px;
            height: 100px;
            left: 0;
            margin: auto;
            position: absolute;
            right: 0;
            top: -45px;
            width: 100px;
            font-family: "Open Sans",sans-serif;
        }
        .rectbox5 {
            background: #5a0499 none repeat scroll 0 0;
            height: 107px;
            margin: auto;
            position: relative;
            width: 185px;
        }
        #triangle-down5 {
            border-left: 100px solid rgba(0, 0, 0, 0);
            border-right: 100px solid rgba(0, 0, 0, 0);
            border-top: 73px solid #5a0499;
            height: 0;
            margin: auto;
            top: 0;
            width: 0;
        }
        .circleboxshape6 {
            background: #ececec none repeat scroll 0 0;
            border: 6px solid #4b0082;
            border-radius: 100%;
            color: #4b0082;
            font-family: "Open Sans",sans-serif;
            font-size: 50px;
            height: 100px;
            left: 0;
            margin: auto;
            position: absolute;
            right: 0;
            top: -45px;
            width: 100px;
            line-height: 82px;
        }
        .arrowimgdown {
            position: absolute;
            right: -78px;
            top: -75px;
            width: 74%;
        }
        .rectbox6 {
            background: #4b0082 none repeat scroll 0 0;
            height: 107px;
            margin: auto;
            position: relative;
            width: 185px;
        }
        #triangle-down6 {
            border-left: 100px solid rgba(0, 0, 0, 0);
            border-right: 100px solid rgba(0, 0, 0, 0);
            border-top: 73px solid #4b0082;
            height: 0;
            margin: auto;
            top: 0;
            width: 0;
        }
        .circlebox {
            background: #f6f6f6 none repeat scroll 0 0;
            border-radius: 100%;
            border-right: 1px solid #cccccc;
            height: 30px;
            position: absolute;
            right: -13px;
            top: 50%;
            width: 30px;
            z-index: 999;
        }
        .circleboxright {
            background: #f6f6f6 none repeat scroll 0 0;
            border-left: 1px solid #cccccc;
            border-radius: 100%;
            height: 30px;
            left: -13px;
            position: absolute;
            top: 50%;
            width: 30px;
            z-index: 999;
        }
        .leftbox {
            background: #f6f6f6 none repeat scroll 0 0;
            border: 1px solid #c9c9c9;
            min-height: 452px;
            padding: 85px 0 0;
            position: relative;
            width: 93%;
            margin: 0 auto;
        }
        .btn.btn-circle.page-scroll > img {
            margin: 11px 0 0 5px;
            opacity: 0.7;
            text-align: center;
            width: 26px;
        }
        .navbar {
            margin-top: 0px !important;
        }
    </style>

</div>