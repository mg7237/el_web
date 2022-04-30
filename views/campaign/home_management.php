<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = 'Easyleases';
?>
<style>
    .card-body {
        text-align: justify;
    }
    .bulletalign{
        margin-left: 90px; text-align: justify;
    }
    /* On screens that are 992px or less, set the background color to blue */
    @media screen and (max-width: 992px) {
        .bulletalign {
            margin-left: 46px;
            text-align: justify;
        }
    }

    /* On screens that are 600px or less, set the background color to olive */
    @media screen and (max-width: 600px) {
        .bulletalign {
            margin-left: 26px;
            text-align: justify;
        }
    }
</style>

<?php if (!empty(Yii::$app->session->getFlash('owner-form-submitted'))) { ?>
    <div class="alert alert-success text-center" role="alert">
        <?= Yii::$app->session->getFlash('owner-form-submitted'); ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php } ?>

<?php if (!empty(Yii::$app->session->getFlash('owner-form-submitted-err'))) { ?>
    <div class="alert alert-danger text-center" role="alert">
        <?= Yii::$app->session->getFlash('owner-form-submitted-err'); ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php } ?>

<!-- Modal Area Starts From Here -->
<!-- NOTE: KEEP ALL MODAL OVER HERE IN THIS BLOCK! [RECOMMENDED] -->

<div class="modal fade" id="iaminterestedModal" tabindex="-1" role="dialog" aria-labelledby="iaminterestedModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center w-100" id="iaminterestedModalLabel">
                    Limited Period Offer<br />
                    Get Your First 3 Months Absolutely FREE!<br />
                    (t&amp;c apply)
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="exampleInputName">Name</label>
                        <input type="text" class="form-control" id="exampleInputName" aria-describedby="nameHelp" placeholder="Enter Your Name">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Email</label>
                        <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Your Email Address">
                        <!--<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>-->
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPhone">Phone</label>
                        <input type="text" maxlength="16" class="form-control" id="exampleInputPhone" placeholder="Enter Your Phone Number">
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="exampleCheck1" checked="checked">
                        <label class="form-check-label" for="exampleCheck1">
                            <small id="emailHelp" class="form-text text-muted">By submitting this form, you authorize Easyleases Technologies Pvt Ltd to contact you regarding Property Management Services. You also agree to our Privacy Statement &amp; general terms &amp; conditions.</small>
                        </label>
                    </div>
                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary">I WANT TO KNOW MORE</button>
                    </div>
                </form>
            </div>
            <!--<div class="modal-footer">-->
            <!--<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>-->
            <!--<button type="button" class="btn btn-primary">Save changes</button>-->
            <!--</div>-->
        </div>
    </div>
</div>

<!-- Modal Area Ends Here -->


<!-- Section Blocks Starts From here -->
<?php if (!empty($thanks)) { ?>
    <section id="section-1" style="margin-top: 30px;">
        <div style="padding: 35px 500px 50px 500px;" title="Property management services in Bangalore for NRIs">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <h1 class="text-center">
                        Thank You…
                    </h1>
                    <p>
                        For taking the first step towards awesomely managing your rental property. Your details have been recorded and we will get back to you within 3 business days.
                    </p>
                    <p>
                        Looking forward to be your PMS Partner!
                    </p>
                    <p>
                        Regards,
                    </p>
                    <p>
                        Team Easyleases
                    </p>
                </div>
            </div>
        </div>
    </section>
<?php } else { ?>
    <section id="section-1" style="margin-top: 100px;">
        <div class="banner-cover" title="Easyleases Property Management Services Rental Homes">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <!--<h1 class="text-center wall-text-light">-->
                    <h3 class="text-center title-tag">
                        Maximize Your Rental Property Income. Safely.
                    </h3>
                </div>
            </div>
            <div class="row" style="margin-bottom: -2%;">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <!--<p class="text-center wall-text-light sec-header">-->
                    <h4 class="text-center sec-header">
                        A Trusted Partner To Manage Your Rental Property.
                    </h4>
                    <p>&nbsp;</p>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                    &nbsp;
                </div>
                <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 ours-benefits">
                    <!--<div class="card">-->
                    <div class="">
                        <!--<div class="card-body">-->
                        <div class="">
                            <p><span class="fa fa-check"></span> &nbsp; <span>100% Transparency. Real-time Dashboard for Owners</span></p>
                            <p><span class="fa fa-check"></span> &nbsp; <span>Property Marketing & Tenant Acquisition</span></p>
                            <p><span class="fa fa-check"></span> &nbsp; <span>End-to-end Property Management</span></p>
                            <p><span class="fa fa-check"></span> &nbsp; <span>Low, Monthly Fee Structure</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row offer-form-box">
                <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                    &nbsp;
                </div>
                <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 form-box-col ours-benefits">
                    <!--<div class="card">-->
                    <div class="">
                        <!--<div class="card-body">-->
                        <div class="">
                            <!--                        <h5 class="modal-title text-center w-100 font-weight-bold">
                                                        Limited Period Offer<br />
                                                        FREE Property Management Services For First 3 Months<br />
                                                        <a style="font-size: 15px;" onclick="return gtag_report_conversion('termsncondition')" target="_blank" href="termsncondition">(t&amp;c apply)</a>
                                                    </h5> <br />-->
                            <form id="camp_lead_form" method="post" action="" onsubmit="return valiForm(this)">
                                <!--<form id="camp_lead_form" method="post" action="">-->
                                <div class="form-group">
                                    <input value="<?= $postData['owner_name'] ?>" required="" name="owner_name" type="text" class="form-control" id="inputName" aria-describedby="nameHelp" placeholder="Enter Your Name">
                                </div>
                                <div class="form-group">
                                    <input value="<?= $postData['owner_email'] ?>" required="" name="owner_email" type="email" class="form-control" id="inputEmail" aria-describedby="emailHelp" placeholder="Enter Your Email Address">
                                    <!--<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>-->
                                </div>
                                <div class="form-group">
                                    <input value="<?= $postData['owner_phone'] ?>" required="" name="owner_phone" type="text" maxlength="16" class="form-control" id="inputPhone" placeholder="Enter Your Phone Number">
                                </div>
                                <?php
                                $cities = \app\models\Cities::find()->where(['status' => 1])->all();
                                $cityData = ArrayHelper::map($cities, 'code', 'city_name');
                                ?>
                                <div class="form-group">
                                    <select required="" class="form-control" id="inputCity"  name="owner_city">
                                        <option value="">Select City Of Your Property</option>
                                        <?php foreach ($cityData as $key => $city) { ?>
                                            <option <?php
                                            if ($key == $postData['owner_city']) {
                                                echo 'selected';
                                            }
                                            ?> value="<?= $key ?>"><?= $city ?></option>
    <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group text-center">
                                    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>" />
                                    <button id="camp_lead_sub" type="submit" class="btn btn-primary">I WANT TO KNOW MORE</button>
                                </div>
                                <div class="form-group form-check">
                                    <input disabled="disabled" type="checkbox" class="form-check-input" id="exampleCheck1" checked="checked">
                                    <label class="form-check-label" for="exampleCheck1">
                                        <small id="emailHelp" class="form-text text-muted">By submitting this form, you authorize Easyleases Technologies Pvt Ltd to contact you regarding Property Management Services. You also agree to our Privacy Statement &amp; general terms &amp; conditions.</small>
                                    </label>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php } ?>

<section id="why-easyleases">
    <div class="row">
        <h1 class="text-center heading-tag">
            Home Maintenance Company in Bangalore
        </h1>
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="card-body">
                <p>
                    Bangalore is now amongst the fastest-growing cities in India. With this growth, there is naturally an increase in number of people looking to buy or lease properties in key areas in the city. Property owners, limited by their lack of time & availability, are often challenged in rising up to this demand for well maintained & well-appointed rental properties. Therefore, property owners are increasingly looking for a reliable home maintenance company in Bangalore to partner with them in managing their rental properties.
                </p>
                <h2 class="text-center">
                    House Maintenance Services in Bangalore
                </h2>
                <p>
                    Home management companies in Bangalore play a vital role in easing the life of rental property owners. There is a lot on their plates as they not only need to help the owners in property maintenance and in growing rental yield, but also to make sure that the property meets the expectations of the tenants.
                </p>
                <p> 
                    <span>
                        Easyleases is a one-stop solution for home owners looking for a reliable, long term partner in managing their rental properties. Easyleases is an end to end home property management service provider offering transparent services to the owners & tenants. We help in filing proper documentation for renting/leasing, maximizing rental yield via market research & tenant retention, providing full-spectrum home care services as painting, cleaning, etc. This is what makes us one of the best home management companies and home rental companies in Bangalore.
                </p>
                <p> 
                    We believe that any home <a href="<?= Url::home(true) ?>rental-property-management-companies-in-bangalore">rental property management</a> company in Bangalore should be focused on just one thing – being a trusted partner for the property owners and taking their load off. With our relentless focus on maintaining your rental property, we firmly believe that Easyleases is your perfect house property management partner.
                </p><br>
                <p> 
                    <b>About Easyleases:</b>
                </p>

                <div class="bulletalign" > 
                    <p> 
                        <span>
                            Easyleases is a DIPP-recognized, tech-led Start-up providing comprehensive <a href="<?= Url::home(true) ?>">Property Management Services</a> for rental property owners & quality living spaces for tenants looking for a rental home.
                        </span></p>
                    <p> 
                        <span>
                            We comprehensively manage rental properties right from finding the right tenant, providing property maintenance & ad-hoc services as cleaning, painting, etc., rental collection & tenant management, etc. in a transparent fashion. Our Online Dashboard lets the Owners & Tenants to view in real-time their transactions, lease history, status of maintenance issues, etc.
                        </span></p>
                    <p> 
                        <span>
                            Easyleases was recently recognized as amongst the Top 10 Property Management Companies in India by Silicon India Magazine. 
                            <a href="https://enterprise-services.siliconindia.com/vendor/property-management-service-providers-2019-easyleases-technologies-cid-9341.html">Read here &#187;</a>
                        </span></p>
                </div>

            </div>
        </div>
    </div>
</section>

<section id="reach-us-at">
    <div class="row">
        <h2 class="text-center">
            <p>Reach Us At</p>
        </h2>
        <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
            <div class="address-box">
                <p>Easyleases Technologies Pvt Ltd</p>
                <p>CoWork247, 2 nd Main Road, Kasturi Nagar</p>
                <p>Bangalore 560043</p>
                <p><a style="color: black;" href="mailto:query@easyleases.in">query@easyleases.in</a></p>
                <p>Tel: <a style="color: black;" href="tel:+91 63646 75551">+91 63646 75551</a> / <a style="color: black;" href="tel:+91 91485 38413">+91 91485 38413</a></p>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 map-parent">
            <div id="easyleases-map" style="height:100%; width:100%;"></div>
        </div>
    </div>
</section>