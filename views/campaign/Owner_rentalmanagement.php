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
            Rental Property Management Companies In Bangalore</p>
        </h1>
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="card-body">
                <p>
                    With the rapidly rising demand for housing in Bangalore, <a href="<?= Url::home(true) ?>">tenant management</a> services are literally becoming essential for rental property owners. <a href="<?= Url::home(true) ?>">Tenant management</a> is a very vast and generic term that includes a plethora of services as tenant acquisition, tenant move-in and move-out management, day to day maintenance fixes at the property, rental collection, etc.
                </p>
                <p>
                    At Easyleases we offer full scope of tenant & <a href="<?= Url::home(true) ?>">rental property management services</a> in Bangalore for property owners – right from finding the right tenant, collecting & depositing monthly rental on time, working with the tenant to resolve day-to-day maintenance issues, managing tenant’s move-in & move-out, conducting property audit to make sure the property health is good, etc. Easyleases acts as the single point of contact or the owners’ representatives when it comes to tenant interactions.
                </p>
                <p> 
                    As part of <a href="<?= Url::home(true) ?>">rental property management services</a>, Easyleases also partners with rental property owners to help determine the optimum rental ask for the property via market research. Further, rental income maximization is an important aspect of <a href="<?= Url::home(true) ?>rental-management-companies-in-bangalore">rental management companies</a>. This means driving up property occupancy, avoiding vacancies and negotiating with the tenant on annual rental increases.
                </p>
                <p> 
                    One of the key tools we leverage to drive up rental yield maximation is tenant satisfaction and retention at the property. Retaining a tenant is always easier and better than having to acquire a new tenant. Making sure that the property including the appliances, furniture & fixtures are in good condition, avoiding surprises to the tenant at the time of move-in, offering transparent view on rental payments & dues, etc are dome sure shot ways to grow tenant satisfaction.
                </p>
                <p> 
                <h2 class="text-center">
                    Why Partner With Easyleases For Tenant Management Services – Top 5 Reasons:
                </h2>

                <div class="bulletalign" > 
                    <p> 
                        <span>
                            <b> 1.</b>	Comprehensive, end-to-end property management services under one roof. You do not have to sign up with multiple partners/agencies to manage your rental home.
                        </span></p>
                    <p> 
                        <span>
                            <b> 2.</b>	Relentless focus on property upkeep & health. We believe that other benefits as rent yield growth will follow if the property is maintained well.
                        </span></p>
                    <p> 
                        <span>
                            <b> 3.</b>	 100% transparency assured. Real-time dashboard lets you view the lease history & expiry alerts, tenant details, rent collections & dues, status of maintenance issues, etc.
                        </span></p>
                    <p> 
                        <span>
                            <b> 4.</b>	On-time rent deposit in owner’s account month on month.
                        </span></p>
                    <p> 
                        <span>
                            <b> 5.</b>	Maximize rental yield through tenant satisfaction & reduced vacancies, and understanding of the market pricing.
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