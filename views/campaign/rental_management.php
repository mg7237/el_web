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

<section id="section-1" style="margin-top: 100px;">
    <div class="banner-cover" title="Easyleases Property Management Services Rental Homes">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <!--<h1 class="text-center wall-text-light">-->
                <h1 class="text-center">
                    Maximize Your Rental Property Income. Safely.
                </h1>
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

<section id="why-easyleases">
    <div class="row">
        <h2 class="text-center">
            <p>RENTAL MANAGEMENT COMPANES IN BANGALORE</p>
        </h2>
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="card-body">
                <p>
                    <span>
                        Rental home owners including NRIs with rental properties in Bangalore often struggle to manage their properties, which requires significant investment in terms of time, efforts & money. And if one has multiple properties to rent & manage, the task is even more onerous.
                    </span></p>
                <p><span><b>Rental Management Companies or Property Management Companies </b>like Easyleases are a blessing for property owners, offering end-to-end <b>Property and Tenant Management Services.</b>
                        Right from getting the right tenants for your home to tenant verification, on-time rent collection & deposit, resolution of<b>property maintenance</b>
                        issues, painting/cleaning of your house – the list of services is indeed comprehensive. 
                    </span></p>
                <p>
                    <span>
                        Companies as Easyleases also ensures tenant verification and on-time rent deposit month on month alleviating a major pain point for the rental property owners
                    </span>
                </p>
        <!--<p><span class="fa fa-check"></span> &nbsp; <span>We take care of your property end-to-end: property tax payments, rent collection &amp; deposit, furnishing, painting, ad-hoc maintenance issues, etc.</span></p>-->
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