<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = 'Easyleases';
?>

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
            APARTMENT MANAGEMENT COMPANIES IN BANGALORE
        </h1>
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="card-body">
                <p>
                    With the rapid growth of Bangalore city in almost every sector, the demand for properties is at an all-time high. The city is growing at a rapid rate with one of the most diverse populaces in the country. With this progression, many property owners feel challenged without any help to lease out and manage their properties. Apartment management companies in Bangalore play a major role in making this process efficient and smooth for property owners. Easyleases is one of the leading <a href="<?= Url::home(true) ?>">Property Management Services</a> company in Bangalore that helps out the owners in not just renting their properties but also in day-to-day maintenance & management of the properties.
                </p>
                <p>
                    Quite often, the property owners are at sea in determining the fair rental price and in reaching out to potential tenants for their houses. To add to this is the demand on their time and effort to address day to day maintenance issues raised by the tenants & apartment associations. The key role of apartment management companies in Bangalore is not only helping out the owners with the property management aspect, but also assisting them in various processes which the owners might not be familiar with – determining the right rental, finding the right tenant, drafting a water-tight lease agreement to name a few. Easyleases assists you in all of these aspects and more, with partnership programs for taking care of not only apartment <a href="<?= Url::home(true) ?>">property management in Bangalore</a> city but also maximizing your rental income in the process.
                </p>
                <p>
                    Apartment management companies have a lot to live up for when one considers the colossal set of responsibilities that come on their shoulders when it comes to apartment property management in Bangalore. Easyleases is at the forefront of property management, as we offer a comprehensive set of apartment <a href="<?= Url::home(true) ?>">Property Management Services</a> using technology as the enabler. At Easyleases, we always strive to exceed your expectations and be a trusted partner in managing your precious rental property.
                </p>

            </div>
        </div>
        <h2 class="text-center">
            Best Property Management Services in Bangalore Offered By Easyleases
        </h2>
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="card-body">
                <ul>
                    <li>
                        Furnishing your property: high quality interiors & painting. We provide multiple options and designs for you to choose from.
                    </li>
                    <li>
                        Property Marketing & Tenant Acquisition: We work with owners to understand their tenant preferences, determine the fair rental asks and then start the tenant acquisition process. We market the property online and offline.
                    </li>
                    <li>
                        Tenant Placement & Documentation: Once the tenant is finalized, we work with the owner & the tenant to execute the lease agreement. We also fulfill tenant verification requirements. We take over the move-in process and ensure that the tenant settles in their new found rental home.
                    </li>
                    <li>
                        Rent Management: Tenants are offered multiple rent payment options including NEFT, Credit Card, Debit Card, UPI etc. Tenants can simply open the Easyleases App on their mobile phone and pay their rent.
                    </li>
                    <li>
                        Property Maintenance: We become the one point contact for the owner & the tenant for all day-to-day maintenance of the property including plumbing, electrical works, carpentry, installing pigeon nets, cleaning, etc.
                    </li>
                    <li>
                        Property Audit: Once a year we audit the property to make sure that it is maintained well and the assets are in good condition. This helps avoid surprises for the owners & the tenants at the time of lease re-negotiation or tenant’s exit.
                    </li>
                    <li>
                        Tenant Exit & Re-furbishing: When the tenant exits the property, we take over the exit formalities and ensure a smooth transition. We also undertake property refurbishing like re-painting, deep cleaning, etc. to help acquire a new tenant.
                    </li>
                    <li>
                        Online Dashboard: Both the owners & tenants are provided an online dashboard that lets them track the lease history, rental payments & dues, maintenance issues, etc. in real time.
                    </li>
                </ul>
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