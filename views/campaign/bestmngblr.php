<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = 'Easyleases';
?>
<style>
    ul.navbar-nav {
        display: none;
    }
    
    video {
        border: 2px solid grey;
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
                        <input type="text" maxlength="10" class="form-control" id="exampleInputPhone" placeholder="Enter Your Phone Number">
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
    <section id="section-1" style="margin-top: 69px;">
        <div class="banner-cover-best-pro" title="Property management services in Bangalore for NRIs">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <!--<h1 class="text-center wall-text-light">-->
                    <h1 class="text-center" style="padding: 15px;">
                        Looking For The Best Property Management Services Company In Bangalore?
                    </h1>
                </div>
            </div>
            <div class="row" style="margin-bottom: -2%;">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <!--<p class="text-center wall-text-light sec-header">-->
                    <h2 class="text-center sec-header" style="font-size: 24px; padding-top: 0px;">
                        <!--Trusted & Transparent Property Management Services For NRIs!-->
                    </h2>
                    <p>&nbsp;</p>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-7 col-xl-7">
                    &nbsp;
                </div>
                <div class="col-12 col-sm-12 col-md-12 col-lg-5 col-xl-5 ours-benefits" style="background-color: #B5F1EF;">
                    <!--<div class="card">-->
                    <div class="">
                        <!--<div class="card-body">-->
                        <div class="">
                            <p><span class="fa fa-check"></span> &nbsp; <span>100% Transparency. Full access to tenant details via our online dashboard</span></p>
                            <p><span class="fa fa-check"></span> &nbsp; <span>Faster tenant acquisition & comprehensive tenant verification</span></p>
                            <p><span class="fa fa-check"></span> &nbsp; <span>Property audit & maintenance</span></p>
                            <p><span class="fa fa-check"></span> &nbsp; <span>Assured on-time rental payments month-on-month</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row offer-form-box">
                <div class="col-12 col-sm-12 col-md-12 col-lg-7 col-xl-7">
                    &nbsp;
                </div>
                <div class="col-12 col-sm-12 col-md-12 col-lg-5 col-xl-5 form-box-col ours-benefits" style="background-color: #B5F1EF;">
                    <!--<div class="card">-->
                    <div class="">
                        <!--<div class="card-body">-->
                        <div class="">
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
                                    <!--<input disabled="disabled" type="checkbox" class="form-check-input" id="exampleCheck1" checked="checked">-->
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
        <p style="padding: 38px; margin-bottom: -21px;">
            You have a rental property – a villa or an apartment or an independent house – in Bangalore, and you are looking for a reliable partner for property management of your rental property. If this is true, then you are amongst the thousands who are grappling with a multitude of questions with regards to making a decision and getting on-board a property management company.
        </p>
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="card-body">
                &nbsp; <h4>We will address the ‘Why’, ‘What’ and ‘How’ of Property Management.</h4>
                &nbsp; <h5 style="text-decoration: underline;">The Why – Why a rental property owner needs to think of hiring a professional property management company to manage his/her property:</h5>
                <p><span class="fa fa-check"></span> &nbsp; <span>Rental property owners are increasingly finding it difficult to manage their properties due to a variety of factors –</span></p>
                <p><span class="fa fa-check"></span> &nbsp; <span>The paucity of time – already caught up with their ever-growing work & family priorities, owners hardly have time to dedicate to managing their rental properties. Time is a crucial investment at every stage – from effectively marketing the property, acquiring the right tenant, attending to maintenance issues on time, making sure lease agreements don’t lapse, etc.</span></p>
                <p><span class="fa fa-check"></span> &nbsp; <span>Too much effort involved – managing a property means committing not just time but also effort. Right from showing around the house for prospective tenants to executing rental/lease agreements, tracking rental payments & following up with the tenant for any dues, being able to physically show up for property audits, finding & organizing trustworthy vendors to undertake maintenance work at the property, and the list goes on</span></p>
                <p><span class="fa fa-check"></span> &nbsp; <span>Lacking professional expertise – Rental property owners who choose to self-manage their properties often end up with some loose ends. Examples include: not making watertight lease agreements leading to disputes with the tenant at a later stage, not renewing lease agreements on time possibly leading to loss in rental revenues, inability to commit time & effort to address tenants’ fair requests resulting in dissatisfaction & churn of tenants, shoddy maintenance work impacting the overall life of the property, etc.</span></p>
                <p><span class="fa fa-check"></span> &nbsp; <span>Lack of expertise or patience for in-depth research & marketing – A professional property management company will have both the competence and time to undertake detailed market research to determine the right rental pricing and to acquire the right tenant.</span></p>
                <p><span class="fa fa-check"></span> &nbsp; <span style="text-decoration: underline;">The What – Now, knowing the value one would get from hiring a property management company would help in the decision-making process:</span></p>
                <p><span class="fa fa-check"></span> &nbsp; <span>Here is the gamut of activities a typical property management or rental management company would undertake:</span></p>
            </div>
        </div>
    </div>
</section>

<section id="why-easyleases">
    <div class="row">
        <h2 class="text-center">
            <p>Marketing & Tenant Acquisition</p>
        </h2>
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="card-body">
                <p><span class="fa fa-check"></span> &nbsp; <span>Where the rental property is vacant, the foremost priority for the property owner and the property management company would be to place a suitable tenant in the property. This will ensure that rental returns start kicking in from the property.</span></p>
                <p><span class="fa fa-check"></span> &nbsp; <span>A professional property manager would be better equipped to do solid research on the prevailing rental rates in the locality, give adequate yet cost effective marketing exposure for the property and screen potential tenants.</span></p>
            </div>
        </div>
    </div>
</section>

<section id="why-easyleases">
    <div class="row">
        <h2 class="text-center">
            <p>Legal documentation</p>
        </h2>
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="card-body">
                <p><span class="fa fa-check"></span> &nbsp; <span>What is often neglected or paid little attention to is the process of making a water-tight lease agreement with the tenant. Most owners end up hurrying through this and get into avoidable disputes with the tenant later on issues relating to house maintenance, unforeseen maintenance or external charges, late rent payments or delinquencies etc.</span></p>
            </div>
        </div>
    </div>
</section>

<section id="why-easyleases">
    <div class="row">
        <h2 class="text-center">
            <p>On-time rent collection</p>
        </h2>
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="card-body">
                <p><span class="fa fa-check"></span> &nbsp; <span>Professional property management companies as Easyleases ensure that rent is deposited in owner’s account on time, month after month. System driven processes ensure follow-up with the tenant on any overdues, and a well-drafted lease agreement ensures disciplined rental payments through application of penalties on late payments.</span></p>
            </div>
        </div>
    </div>
</section>

<section id="why-easyleases">
    <div class="row">
        <h2 class="text-center">
            <p>Property maintenance & audit</p>
        </h2>
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="card-body">
                <p><span class="fa fa-check"></span> &nbsp; <span>Again, one of the most critical aspects for any rental property is proper maintenance. Often owners lack the time to inspect and effectively rectify any maintenance issues. Property management companies have qualified vendors are best equipped to resolve maintenance issues.</span></p>
                <p><span class="fa fa-check"></span> &nbsp; <span>Now, making sure maintenance issues are addressed in time also goes a long way in ensuring tenant satisfaction & retention.</span></p>
            </div>
        </div>
    </div>
</section>

<section id="why-easyleases">
    <div class="row">
        <h2 class="text-center">
            <p>Tenant management</p>
        </h2>
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="card-body">
                <p><span class="fa fa-check"></span> &nbsp; <span>It can be overwhelming for the owners to attend to tenant requests on maintenance issues & negotiations on lease renewals, follow up on rent/maintenance dues, etc. All the more where the tenants are demanding and non-cooperative.</span></p>
                <p><span class="fa fa-check"></span> &nbsp; <span>Property management companies as Easyleases comprehensively address tenant management needs right from moving into the property to maintenance needs to tenant’s exit.</span></p>
            </div>
        </div>
    </div>
</section>

<section id="why-easyleases">
    <div class="row">
        <h2 class="text-center">
            <p>Refurbishing & ad-hoc work</p>
        </h2>
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="card-body">
                <p><span class="fa fa-check"></span> &nbsp; <span>Owners can rely on most property management companies, as Easyleases, to serve them as a “one-stop shop” for all their needs relating to property management. This includes re-painting, deep cleaning of the property when a tenant moves out, and also ad hoc tasks as payment of property taxes, electricity bills, etc.</span></p>
                <p><span class="fa fa-check"></span> &nbsp; <span style="text-decoration: underline;">And then comes the ‘How’. How does one select the right property management company? Here are a few quick pointers:</span></p>
                <p><span class="fa fa-check"></span> &nbsp; <span>Understand their scope of service. Make sure that your prospective partner offers full-suite property management services, so that you don’t have to run around for tasks not covered</span></p>
                <p><span class="fa fa-check"></span> &nbsp; <span>Scrutinize their commercial model to make sure there are no hidden charges. At Easyleases, for example, we only charge when a tenant is placed at the property. There are no one-time or any hidden charges. We also do not charge any commission or brokerage from the tenants</span></p>
                <p><span class="fa fa-check"></span> &nbsp; <span>Transparency is a key factor for property owners, particularly if one is away from the city. At Easyleases, we assure 100% transparency for property owners. Every owner, and even tenants for that matter, are provided with an online dashboard.  The dashboard lets them view in real time all the rental payments, rental dues falling due, status of maintenance issues at the property, lease agreement renewal dates & status, etc.</span></p>
            </div>
        </div>
    </div>
</section>

<section id="why-easyleases">
    <div class="row">
        <h2 class="text-center">
            <p>Here’s a quick video on the property management services offered by Easyleases</p>
        </h2>
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="card-body text-center">
                <video width="700" controls>
                    <source src="uploads/videos/Easyleases-Property-Management.mp4" type="video/mp4">
                    Your browser does not support HTML video.
                </video>
                <p></p>
                <p>
                    In summary, if you have a rental property, Easyleases offers you amongst the best property management services in Bangalore.
                </p>
            </div>
        </div>
    </div>
</section>

<section id="about-easyleases">
    <div class="row">
        <h2 class="text-center">
            <p>About Easyleases Technologies Pvt. Ltd</p>
        </h2>
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="card-body">
                <p><span class="fa fa-check"></span> &nbsp; <span>We are a DIPP-recognized Start-up in Bangalore offering end-to-end Property Management Services to rental property owners.</span></p>
                <p><span class="fa fa-check"></span> &nbsp; <span>We comprehensively manage rental properties right from finding the right tenant, providing property maintenance &amp; ad-hoc services as cleaning, painting, etc, rental collection &amp; tenant management, etc in a transparent fashion.</span></p>
                <p><span class="fa fa-check"></span> &nbsp; <span>Our Online Dashboard lets the Owners &amp; Tenants to view in real-time their transactions, lease history, status of maintenance issues, etc</span></p>
            </div>
        </div>
    </div>
</section>

<section id="reach-us-at">
    <div class="row">
        <h2 class="text-center">
            <p>Want to know more?</p>
        </h2>
        <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4" style="margin: auto;">
            <form id="camp_lead_form" method="post" action="" onsubmit="return valiForm2(this)">
                <!--<form id="camp_lead_form" method="post" action="">-->
                <div class="form-group">
                    <input value="<?= $postData['owner_name'] ?>" required="" name="owner_name" type="text" class="form-control" id="inputName2" aria-describedby="nameHelp" placeholder="Enter Your Name">
                </div>
                <div class="form-group">
                    <input value="<?= $postData['owner_email'] ?>" required="" name="owner_email" type="email" class="form-control" id="inputEmail2" aria-describedby="emailHelp" placeholder="Enter Your Email Address">
                    <!--<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>-->
                </div>
                <div class="form-group">
                    <input value="<?= $postData['owner_phone'] ?>" required="" name="owner_phone" type="text" maxlength="16" class="form-control" id="inputPhone2" placeholder="Enter Your Phone Number">
                </div>
                <?php
                $cities = \app\models\Cities::find()->where(['status' => 1])->all();
                $cityData = ArrayHelper::map($cities, 'code', 'city_name');
                ?>
                <div class="form-group">
                    <select required="" class="form-control" id="inputCity2"  name="owner_city">
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
                    <!--<input disabled="disabled" type="checkbox" class="form-check-input" id="exampleCheck1" checked="checked">-->
                    <label class="form-check-label" for="exampleCheck1">
                        <small id="emailHelp" class="form-text text-muted">By submitting this form, you authorize Easyleases Technologies Pvt Ltd to contact you regarding Property Management Services. You also agree to our Privacy Statement &amp; general terms &amp; conditions.</small>
                    </label>
                </div>
            </form>
        </div>
    </div>
</section>

<section id="reach-us-at">
    <div class="row">
        <h2 class="text-center">
            <p>Contact Us At</p>
        </h2>
        <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
            <div class="address-box">
                <p>Easyleases Technologies Pvt Ltd</p>
                <p>CoWork247, 2 nd Main Road, Kasturi Nagar</p>
                <p>Bangalore 560043</p>
                <p><a style="color: black;" href="mailto:query@easyleases.in">query@easyleases.in</a></p>
                <p>Tel: <a style="color: black;" href="tel:+91 63646 75551">+91 763646 75551</a> / <a style="color: black;" href="tel:+91 91485 38413">+91 91485 38413</a></p>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 map-parent">
            <div id="easyleases-map" style="height:100%; width:100%;"></div>
        </div>
    </div>
</section>

<script>
    function valiForm2() {
        var name = $('#inputName2').val();
        var email = $('#inputEmail2').val();
        var phone = $('#inputPhone2').val();
        var city = $('#inputCity2').val();
        var regEmail = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;

        if (name.trim() == '') {
            alert('Please Enter Your Name.');
            return false;
        } else if (email.trim() == '') {
            alert('Please Enter Your Email.');
            return false;
        } else if (!email.match(regEmail)) {
            alert('Invalid Email, Please Check Your Email.');
            return false;
        } else if (!isNaN(phone) && phone.toString().length != 10) {
            alert('Please Enter a Valid Phone Number.');
            return false;
        }

        dataLayer.push({
            'event': 'launch_offer_form',
            'action': 'Submitted Successfully By - '+phone
        });
        return true;
    }
</script>