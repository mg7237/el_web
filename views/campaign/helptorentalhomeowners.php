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
            Rental Management Companies in Bangalore
        </h1>
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="card-body">
                <p>
                    Rental property owners often grapple with the choice of either managing their properties on their own or to partner with a professional rental or tenant management company. Many factors weigh in while the owners decide on this, from trust, effectiveness, cost, etc. Lack of awareness is equally a significant factor why many rental property owners try to manage their properties on their own.
                </p>
                <p>
                    Property owners end up spending their valuable time and efforts trying to manage their properties. They are often inundated with maintenance issues or tenant related issues. Owners also find the task of placing a tenant at their properties a daunting task, given their other priorities. Owners should consider hiring a professional rental <a href="<?= Url::home(true) ?>">property management company</a> in bangalore if any or all of these are a reality:
                </p>
                <h2 class="text-center">
                    Rental Property Management Services in Bangalore
                </h2>
                <div class="card-body">
                    <ul>
                        <li>
                            Time is limited – finding & managing a tenant, and day-to-day management of the property is not a part-time job. This needs focused, consistent attention which a professional property manager can provide.
                        </li>
                        <li>
                            Owner lives away – if the owner does not live near his/her rental property, then it is all the more a reason for him/her to partner with a professional <a href="<?= Url::home(true) ?>rental-property-management-companies-in-bangalore">rental property management companies</a> in bangalore. There is significant effort involved in just making sure the right tenant is placed in the property, which the owners will find demanding particularly if they are not living near the property.
                        </li>
                        <li>
                            Day-to-day management is daunting – quite often, everyday management of the tenant’s needs can be demanding. Imagine getting a call from your tenant in the middle of your busy day complaining of a faulty appliance. Your day would be spent in just making sure you find a handyman and the issue is taken care of before the tenant escalates. Most owners simply don’t have the time or patience to deal with the daily maintenance or other issues that their tenants may have. Lack of attention may result in tenant dissatisfaction or even attrition, which again has its cost & effort impact for having to find a replacement.
                        </li>
                        <li>
                            Timely rent collection is challenged – when the owners always struggle with collecting rent from their tenants on time, it’s a wise decision to hire a rental management company.
                        </li>
                        <li>
                            Multiple rental properties – when you have more than one rental property, it’s again a challenge to manage these. Hiring a rental management company in Bangalore is the right decision in such cases.
                        </li>
                    </ul>
                </div>
                <!--####################-->
                <p class="text-center"> 
                    <b>But the moot question about trust still remains. Here are some quick tips on how owners can find the right rental <a href="<?= Url::home(true) ?>">property management company</a>:</b>
                </p>
                <div class="card-body">
                    <ul>
                        <li>
                            Do your research – scan the internet and the company’s web page, be sure you understand what they offer and what the terms are. See what others are saying as recommendations or comments on social media.
                        </li>
                        <li>
                            Talk to them – meet the rental management company representative and ask them to go over their programs & processes. Ask questions and make sure you fully understand their offerings.
                        </li>
                        <li>
                            Clear air around trust & fairness – talk to the rental management company about how they plan to ensure transparency and trust. At Easyleases, transparency is paramount. We provide the owners and tenants with an Online Dashboard that helps them track lease history, rental payments & dues, status of maintenance issues, etc. in real time.
                        </li>
                        <li>
                            Easyleases, a DIPP-recognized, full-spectrum <a href="<?= Url::home(true) ?>">Property Management Company</a> in Bangalore places immense emphasis in making sure the rental properties are managed and maintained properly. We firmly believe that rental yield maximization follows when the property & tenants are managed professionally.
                        </li>
                    </ul>
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