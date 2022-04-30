<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\PropertyTypes;
use app\models\ChildProperties;
use yii\widgets\ActiveForm;

$defaultTokenNull = false;
?>
<div class="container">
    <section id="property-slide-show">
        <div class="row">
            <div class="col-lg-8 bg-white">
                <div class="row">
                    <div id="property-detail-crousel-1" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <?php
                            if (isset($dataImages)) {
                                $j = 0;
                                foreach ($dataImages as $image) {
                                    ?>
                                    <li data-target="#property-detail-crousel-1" data-slide-to="<?php echo $j; ?>" <?php if ($j == 0) { ?>class="active"<?php } ?>></li>
                                    <?php
                                    $j++;
                                }
                            }
                            ?>
                        </ol>
                        <div class="carousel-inner">
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
                                        <!--<p class="wishlist1">-->
                                            <!--<a data-property="<?= Yii::$app->getRequest()->getQueryParam('id'); ?>" data-id="<?= $modelFav == 0 ? 1 : 0; ?>" title="<?= $modelFav == 0 ? 'Click to add in Favourite' : 'Click to delete from Favourite'; ?>"  class="wishlist" style="cursor:pointer;"> <img class="fav_icon" src="<?php //echo Url::home(true);   ?>images/<?= $modelFav == 0 ? 'dil_img.png' : 'dil_shape.png' ?>" alt=""> </a>-->
                                        <!--</p>-->
                                        <img class="d-block w-100" src="<?= yii\helpers\Url::home(true); ?><?php echo $image->image_url; ?>" alt="First slide">
                                    </div>
                                    <?php
                                    $i++;
                                }
                            }
                            ?>
                        </div>
                        <a class="carousel-control-prev" href="#property-detail-crousel-1" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#property-detail-crousel-1" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 right-box-parent">
                <div class="right-box-child">
                    <div class="card mb-4 rounded-0">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link scheduled-visit-link active" data-toggle="tab" href="#scheduled-visit-tab">Schedule Visit</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link book-tab-link" data-toggle="tab" href="#book-property-tab">Book</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#know-more-tab">Know More</a>
                            </li>
                        </ul>
                        <div class="tab-content p-3">
                            <div class="tab-pane fade show active" id="scheduled-visit-tab" role="tabpanel" aria-labelledby="home-tab">
                                <div class="pb-0">
                                    <div class="pick-slot-box">
                                        <div class="row">
                                            <div class="col-lg-12">
<?php if (empty($visitdate)) { ?>
                                                    <img class="show-biz-icons" src="<?php echo Url::home() ?>media/icons/visit_confirmed.svg" />
                                                    <p class="text-center">When would you like to visit?</p>
                                                    <p class="text-center sub-text">FREE guided tour.</p>
                                                <?php } else { ?>
                                                    <img class="show-biz-icons" src="<?php echo Url::home() ?>media/icons/visit_confirmed.svg" />
                                                    <p class="text-center">Scheduled at <?= date('d-M-Y h:i A', strtotime($visitdate . ' ' . $visittime)) ?></p>
<?php } ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <?php if (empty($visitdate)) { ?>
                                                    <button class="btn btn-danger my-2 my-sm-0 pick-slot-btn" type="submit">Pick a slot</button>
                                                <?php } else { ?>
                                                    <button class="btn btn-danger my-2 my-sm-0 reschedule-slot-btn" type="submit">Reschedule visit</button>
<?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="select-slot-box" style="display: none;">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="date-time-slot-box">
                                                    <div class="selectDate">
                                                        <div class="datePickerComponent">
                                                            <div id="selectDateFromDatePicker" class="selectDateComponent hideScroll">
                                                                <?php $form = ActiveForm::begin(['id' => 'visits', 'action' => '']); ?>

                                                                <?= $form->field($modelvisit, 'property_id')->hiddenInput(['value' => $model->id])->label(false); ?>
                                                                <?= $form->field($modelvisit, 'child_properties')->hiddenInput(['value' => $model->id])->label(false); ?>
                                                                <?= $form->field($modelvisit, 'type')->hiddenInput(['value' => $model->property_type])->label(false); ?>
                                                                <?= $form->field($modelvisit, 'visit_date')->hiddenInput(['maxlength' => true, 'autocomplete' => 'off', 'placeholder' => 'Visit On', 'value' => (!empty($_SESSION['open_sch_popup_date'])) ? $_SESSION['open_sch_popup_date'] : ''])->label(false); ?>
                                                                <?= $form->field($modelvisit, 'visit_time')->hiddenInput(['maxlength' => true, 'autocomplete' => 'off', 'placeholder' => 'Visit On', 'value' => (!empty($_SESSION['open_sch_popup_date'])) ? $_SESSION['open_sch_popup_date'] : ''])->label(false); ?>
                                                                <?php
                                                                if (!empty($_SESSION['open_sch_popup_date'])) {
                                                                    unset($_SESSION['open_sch_popup_date']);
                                                                    unset($_SESSION['open_sch_popup_time']);
                                                                }
                                                                ?>

                                                                <?php ActiveForm::end(); ?>

<?php $date = new DateTime(); ?>
                                                                <div id="day0" data-date-slot="<?= $date->format("Y-m-d") ?>" class="datePicker datePicker-select active">
                                                                    <h6 class="month"><?= date('M', strtotime($date->format("Y-m-d"))) ?></h6>
                                                                    <h4 class="day"><?= date('d', strtotime($date->format("Y-m-d"))) ?></h4>
                                                                    <h6 class="weekDay"><?= date('D', strtotime($date->format("Y-m-d"))) ?></h6>
                                                                </div>
                                                                <?php
                                                                $date = new DateTime($date->format("Y-m-d"));
                                                                $date->modify("+1 day");
                                                                ?>
                                                                <div id="day1" data-date-slot="<?= $date->format("Y-m-d") ?>" class="datePicker datePicker-select">
                                                                    <h6 class="month"><?= date('M', strtotime($date->format("Y-m-d"))) ?></h6>
                                                                    <h4 class="day"><?= date('d', strtotime($date->format("Y-m-d"))) ?></h4>
                                                                    <h6 class="weekDay"><?= date('D', strtotime($date->format("Y-m-d"))) ?></h6>
                                                                </div>
                                                                <?php
                                                                $date = new DateTime($date->format("Y-m-d"));
                                                                $date->modify("+1 day");
                                                                ?>
                                                                <div id="day2" data-date-slot="<?= $date->format("Y-m-d") ?>" class="datePicker datePicker-select">
                                                                    <h6 class="month"><?= date('M', strtotime($date->format("Y-m-d"))) ?></h6>
                                                                    <h4 class="day"><?= date('d', strtotime($date->format("Y-m-d"))) ?></h4>
                                                                    <h6 class="weekDay"><?= date('D', strtotime($date->format("Y-m-d"))) ?></h6>
                                                                </div>
                                                                <?php
                                                                $date = new DateTime($date->format("Y-m-d"));
                                                                $date->modify("+1 day");
                                                                ?>
                                                                <div id="day3" data-date-slot="<?= $date->format("Y-m-d") ?>" class="datePicker datePicker-select">
                                                                    <h6 class="month"><?= date('M', strtotime($date->format("Y-m-d"))) ?></h6>
                                                                    <h4 class="day"><?= date('d', strtotime($date->format("Y-m-d"))) ?></h4>
                                                                    <h6 class="weekDay"><?= date('D', strtotime($date->format("Y-m-d"))) ?></h6>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="selectTime">
                                                        <div id="selectTimeFromTimePicker" class="selectTimeComponent customScroll">
                                                            <div id="time0" data-time-slot="11:00 AM" class="timePicker timePicker-select">
                                                                <div class="time">11:00</div>
                                                                <h6 class="meridiem">AM</h6>
                                                            </div>
                                                            <div id="time1" data-time-slot="11:30 AM" class="timePicker timePicker-select">
                                                                <div class="time">11:30</div>
                                                                <h6 class="meridiem">AM</h6>
                                                            </div>
                                                            <div id="time2" data-time-slot="12:00 PM" class="timePicker timePicker-select">
                                                                <div class="time">12:00</div>
                                                                <h6 class="meridiem">PM</h6>
                                                            </div>
                                                            <div id="time3" data-time-slot="12:30 PM" class="timePicker timePicker-select">
                                                                <div class="time">12:30</div>
                                                                <h6 class="meridiem">PM</h6>
                                                            </div>
                                                            <div id="time4" data-time-slot="01:00 PM" class="timePicker timePicker-select">
                                                                <div class="time">01:00</div>
                                                                <h6 class="meridiem">PM</h6>
                                                            </div>
                                                            <div id="time5" data-time-slot="01:30 PM" class="timePicker timePicker-select">
                                                                <div class="time">01:30</div>
                                                                <h6 class="meridiem">PM</h6>
                                                            </div>
                                                            <div id="time6" data-time-slot="02:00 PM" class="timePicker timePicker-select">
                                                                <div class="time">02:00</div>
                                                                <h6 class="meridiem">PM</h6>
                                                            </div>
                                                            <div id="time7" data-time-slot="02:30 PM" class="timePicker timePicker-select">
                                                                <div class="time">02:30</div>
                                                                <h6 class="meridiem">PM</h6>
                                                            </div>
                                                            <div id="time8" data-time-slot="03:00 PM" class="timePicker timePicker-select">
                                                                <div class="time">03:00</div>
                                                                <h6 class="meridiem">PM</h6>
                                                            </div>
                                                            <div id="time9" data-time-slot="03:30 PM" class="timePicker timePicker-select">
                                                                <div class="time">03:30</div>
                                                                <h6 class="meridiem">PM</h6>
                                                            </div>
                                                            <div id="time10" data-time-slot="04:00 PM" class="timePicker timePicker-select">
                                                                <div class="time">04:00</div>
                                                                <h6 class="meridiem">PM</h6>
                                                            </div>
                                                            <div id="time11" data-time-slot="04:30 PM" class="timePicker timePicker-select">
                                                                <div class="time">04:30</div>
                                                                <h6 class="meridiem">PM</h6>
                                                            </div>
                                                            <div id="time12" data-time-slot="05:00 PM" class="timePicker timePicker-select">
                                                                <div class="time">05:00</div>
                                                                <h6 class="meridiem">PM</h6>
                                                            </div>
                                                            <div id="time13" data-time-slot="05:30 PM" class="timePicker timePicker-select">
                                                                <div class="time">05:30</div>
                                                                <h6 class="meridiem">PM</h6>
                                                            </div>
                                                            <div id="time14" data-time-slot="06:00 PM" class="timePicker timePicker-select">
                                                                <div class="time">06:00</div>
                                                                <h6 class="meridiem">PM</h6>
                                                            </div>
                                                            <div id="time15" data-time-slot="06:30 PM" class="timePicker timePicker-select">
                                                                <div class="time">06:30</div>
                                                                <h6 class="meridiem">PM</h6>
                                                            </div>
                                                            <div id="time16" data-time-slot="07:00 PM" class="timePicker timePicker-select">
                                                                <div class="time">07:00</div>
                                                                <h6 class="meridiem">PM</h6>
                                                            </div>
                                                            <div id="time17" data-time-slot="07:30 PM" class="timePicker timePicker-select">
                                                                <div class="time">07:30</div>
                                                                <h6 class="meridiem">PM</h6>
                                                            </div>
                                                            <div id="time18" data-time-slot="08:00 PM" class="timePicker timePicker-select">
                                                                <div class="time">08:00</div>
                                                                <h6 class="meridiem">PM</h6>
                                                            </div>
                                                            <div id="time19" data-time-slot="08:30 PM" class="timePicker timePicker-select">
                                                                <div class="time">08:30</div>
                                                                <h6 class="meridiem">PM</h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="pb-0 p-2 scheduled-and-book" style="display: none;">
                                    <div class="row">
                                        <div class="col-12">
                                            <img class="show-biz-icons" src="<?php echo Url::home() ?>media/icons/visit_confirmed.svg" />
                                            <p class="text-center"><?php
                                                if (empty($visitdate)) {
                                                    echo 'Visit Confirmed';
                                                } else {
                                                    echo 'Visit rescheduled';
                                                }
                                                ?></p>
                                            <p class="sub-text">
                                                Thanks for showing interest in property: <b><?php echo $model->property_name; ?></b>. We look forward to hosting you during the visit. Our representative would be in touch with you to co-ordinate the visit.
                                            </p>
                                            <p class="sub-text would-you-book">
                                                Would you also like to book this property by paying token amount?
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <button class="btn btn-danger book-property-btn w-100">Book Now</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="pb-0 p-2 text-center" style="display: none;">
                                    <hr style="width: 100%;" />
                                    <div class="row">
                                        <div class="col-lg-4"><h6 class="property-pricing-title">Rent</h6><span class="property-pricing-val">&#8377;<?php echo Yii::$app->userdata->getFormattedMoney($model->propertyListing->rent); ?></span></div>
                                        <div class="col-lg-4 px-0"><h6 class="property-pricing-title">Maintenance</h6><span class="property-pricing-val"><?php echo ($model->propertyListing->maintenance == '0' || $model->propertyListing->maintenance == '') ? 'Included' : '&#8377;' . Yii::$app->userdata->getFormattedMoney($model->propertyListing->maintenance); ?></span></div>
                                        <div class="col-lg-4"><h6 class="property-pricing-title">Deposit</h6><span class="property-pricing-val">&#8377;<?php echo Yii::$app->userdata->getFormattedMoney($model->propertyListing->deposit); ?></span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="book-property-tab" role="tabpanel" aria-labelledby="profile-tab">
                                <?php
                                $roomTokens = Yii::$app->userdata->getMinTokenForRooms($model->id);
                                ?>
<?php if ($model->propertyListing->token_amount != 0) { ?>
    <?php if (!$booked) { ?>
                                        <img class="show-biz-icons" src="<?php echo Url::home() ?>media/icons/book-online.svg" />
                                        <p class="text-center">
                                            To ensure you don't miss out on this one!
                                        </p>
                                        <p class="text-center">
                                            Book this one by paying &#8377;<span class="token-amount"><?= $model->propertyListing->token_amount ?></span>
                                        </p>
                                        <?php if (empty($visitdate)) { ?>
                                            <p class="sub-text book-desc">
                                                When would you like to visit the property? We recommend scheduling your visit first, before you book a property. Hit the below button and pick your slot for visit.
                                            </p>
                                        <?php } ?>
                                        <?php $form = ActiveForm::begin(['id' => 'visits-book-form', 'action' => Url::home(true) . 'paytm/bookproperty']); ?>
                                        <?= $form->field($modelvisit, 'property_id')->hiddenInput(['value' => $model->id])->label(false); ?>
                                        <?= $form->field($modelvisit, 'child_properties')->hiddenInput(['value' => $model->id])->label(false); ?>
                                        <?= $form->field($modelvisit, 'type')->hiddenInput(['value' => $model->property_type])->label(false); ?>
                                        <input type="hidden" name="app_redirect_url" value="<?php echo Url::home(true) ?>site/view?id=<?= @$_GET['id'] ?>">
                                        <?php echo $form->field($modelvisit, 'txn_amount')->hiddenInput(['value' => $model->propertyListing->token_amount])->label(false); ?>
                                        <?php ActiveForm::end(); ?>
                                        <?php if (empty($visitdate)) { ?>
                                            <button class="btn btn-danger my-2 my-sm-0 pick-slot-btn-2" type="submit">Pick a slot</button>
        <?php } else { ?>
                                            <button class="btn btn-danger my-2 my-sm-0 book-property w-100" type="submit">Book Now</button>
        <?php } ?>
    <?php } else { ?>
                                        <img class="show-biz-icons" src="<?php echo Url::home(true) ?>media/icons/excited_emoji.svg" />
                                        <p class="text-center">
                                            Great you already booked it.
                                        </p>
                                        <p class="text-center sub-text">
                                            This home is reserve for you.
                                        </p>
    <?php } ?>
<?php } else {
    $defaultTokenNull = true;
    ?>
                                    <div id="pick-slot-nbook" style="display: none;">
                                        <img class="show-biz-icons" src="<?php echo Url::home() ?>media/icons/book-online.svg" />
                                        <p class="text-center">
                                            To ensure you don't miss out on this one!
                                        </p>
                                        <p class="text-center">
                                            Book this one by paying &#8377;<span class="token-amount"><?= $model->propertyListing->token_amount ?></span>
                                        </p>
                                        <?php if (empty($visitdate)) { ?>
                                            <p class="sub-text book-desc">
                                                When would you like to visit the property? We recommend scheduling your visit first, before you book a property. Hit the below button and pick your slot for visit.
                                            </p>
                                        <?php } ?>
                                        <?php $form = ActiveForm::begin(['id' => 'visits-book-form', 'action' => Url::home(true) . 'paytm/bookproperty']); ?>
                                        <?= $form->field($modelvisit, 'property_id')->hiddenInput(['value' => $model->id])->label(false); ?>
                                        <?= $form->field($modelvisit, 'child_properties')->hiddenInput(['value' => $model->id])->label(false); ?>
                                        <?= $form->field($modelvisit, 'type')->hiddenInput(['value' => $model->property_type])->label(false); ?>
                                        <input type="hidden" name="app_redirect_url" value="<?php echo Url::home(true) ?>site/view?id=<?= @$_GET['id'] ?>">
                                        <?php echo $form->field($modelvisit, 'txn_amount')->hiddenInput(['value' => $model->propertyListing->token_amount])->label(false); ?>
                                        <?php ActiveForm::end(); ?>
    <?php if (empty($visitdate)) { ?>
                                            <button class="btn btn-danger my-2 my-sm-0 pick-slot-btn-2" type="submit">Pick a slot</button>
    <?php } else { ?>
                                            <button class="btn btn-danger my-2 my-sm-0 book-property w-100" type="submit">Book Now</button>
    <?php } ?>
                                    </div>
                                    <div id="booking-not-required">
                                        <img class="show-biz-icons" src="<?php echo Url::home(true) ?>media/icons/excited_emoji.svg" />
                                        <p class="text-center">
                                            Hurrah booking not required.
                                        </p>
                                    </div>
<?php } ?>
                            </div>
                            <div class="tab-pane fade" id="know-more-tab" role="tabpanel" aria-labelledby="contact-tab">
                                <img class="show-biz-icons" src="<?php echo Url::home(true) ?>media/icons/info-icon.svg" />
                                <p class="text-center">
                                    Looking for more details?
                                </p>
                                <?php if (!Yii::$app->user->isGuest) { ?>
                                    <p class="text-center sub-text">
                                        Hit the below button
                                    </p>
                                    <button type="button" id="moreDetailsSubmited" class="btn btn-danger w-100" data-dismiss="modal" style="width: 46%;">Know more</button>
                                <?php } else if (!empty(Yii::$app->session['property_more_details'])) { ?>
                                    <p class="text-center sub-text">
                                        Hit the below button
                                    </p>
                                    <button type="button" id="moreDetailsSubmited" class="btn btn-danger w-100" data-dismiss="modal" style="width: 46%;">Know more</button>
<?php } else { ?>
                                    <p class="text-center sub-text">
                                        Fill in the details below
                                    </p>
    <?php $form = ActiveForm::begin(['id' => 'moreDeatailss', 'action' => 'moreDeatails', 'options' => ['onsubmit' => 'moreDetails()']]); ?>
                                    <input type="hidden" name="more_detail_single" value="1">
                                    <div class="form-group field-propertyvisits-property_id">
                                        <input type="hidden" id="propertyvisits-property_id" class="form-control" name="PropertyVisits[property_id]" value="<?php echo @$_GET['id'] ?>">
                                        <div class="help-block"></div>
                                    </div>
                                    <div class="form-group field-propertyvisits-child_properties">
                                        <input type="hidden" id="propertyvisits-child_properties" class="form-control" name="PropertyVisits[child_properties]" value="10">
                                        <div class="help-block"></div>
                                    </div>
                                    <div class="form-group field-propertyvisits-type">
                                        <input type="hidden" id="propertyvisits-type" class="form-control" name="PropertyVisits[type]" value="3">
                                        <div class="help-block"></div>
                                    </div>
                                    <div class="form-group col-md-12 col-sm-12">
                                        <input type="text" name="Visiter_Name" id="moreDetails_name" class="w-100" placeholder="Name">
                                    </div>
                                    <div class="form-group col-md-12 col-sm-12">
                                        <input type="email" name="Visiter_Email" id="moreDetails_email" class="w-100" placeholder="Email">
                                    </div>
                                    <div class="form-group col-md-12 col-sm-12">
                                        <input type="text" name="Visiter_Contact" id="moreDetails_contact" placeholder="Phone" class="isPhone w-100" maxlength="15">
                                    </div>
                                    <div class="form-group col-md-12 col-sm-12 moreDetailsSubmitBtnBox">
                                        <button type="button" id="moreDetailsSubmit" class="btn btn-danger w-100" data-dismiss="modal" style="width: 46%;">Send</button>
                                    </div>
    <?php ActiveForm::end(); ?>
<?php } ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="property-macros">
        <div class="row">
            <div class="col-lg-8 mt-3 bg-white p-4">
                <div class="row">
                    <div class="col-lg-11">
                        <h4 class="property-name"><?php echo $model->property_name; ?></h4>
                    </div>
                    <div class="col-lg-1 pr-3">
                        <a target="_blank" href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($model->address_line_1) ?>,<?php echo urlencode($model->property_name) ?>/<?php echo $model->lat ?>,<?php echo $model->lon ?>">
                            <img class="map-icon float-right" src="<?php echo \yii\helpers\Url::home(true) ?>media/icons/map-icon.svg" />
                        </a>
                    </div>
                </div>
                <div class="row">
                    <?php
                    if ($model->property_type == 4) {
                        $roomDetails = Yii::$app->userdata->getRoomsForColive($model->id);
                        $lowestRentRoom = Yii::$app->userdata->getLowestRentRoomColive($roomDetails);
                        $bedDetails = Yii::$app->userdata->getBedsForColive($model->id);
                    } else if ($model->property_type == 5) {
                        $roomDetails = Yii::$app->userdata->getRoomsForPg($model->id);
                        $lowestRentRoom = Yii::$app->userdata->getLowestRentRoomPg($roomDetails);
                    }

                    $lowestRentId = (!empty($lowestRentRoom)) ? $lowestRentRoom['id'] : 0;
                    ?>
                    <div class="col-lg-3"><h4 class="sub-text">Rent</h4><p><span class="header-line property-rent">&#8377;<?php echo Yii::$app->userdata->getFormattedMoney($lowestRentRoom['rent']); ?></span></p></div>
                    <div class="col-lg-3 px-0"><h4 class="sub-text">Maintenance</h4><p><span class="header-line property-maintenance"><?php echo ($lowestRentRoom['maintenance'] == '0' || $lowestRentRoom['maintenance'] == '') ? 'Included' : $lowestRentRoom['maintenance']; ?></span></p></div>
                    <div class="col-lg-3"><h4 class="sub-text">Deposit</h4><p><span class="header-line property-deposit">&#8377;<?php echo Yii::$app->userdata->getFormattedMoney($lowestRentRoom['deposit']); ?></span></p></div>
                    <div class="col-lg-3 p-2">
                        <p class="small-bold-font">
<?= Yii::$app->userdata->getManagedByString(($model->managed_by)) ?> Managed
                        </p>
                        <p class="small-bold-font">
                            For&nbsp;<?= Yii::$app->userdata->getAvailableForString(($model->gender)) ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4"></div>
        </div>
    </section>

    <section id="property-overview">
        <div class="row">
            <div class="col-lg-8 mt-3 bg-white p-4">
                <h4 class="property-name">Overview</h4>
                <p>
<?= $model->property_description ?>
                </p>
            </div>
            <div class="col-lg-4"></div>
        </div>
    </section>

    <section id="property-details">
        <?php
        if ($model->property_type == 4) {
            $lastKeyCount = 0;
            foreach ($roomDetails as $ky => $properties) {
                $lastKeyCount = $ky;
                $totalBeds = Yii::$app->userdata->getBedCount($properties->id);
                $roomAvailability = Yii::$app->userdata->getAvailDateForRoom($properties->id);
                $roomCharges = Yii::$app->userdata->getRoomCharges($properties->id);
                if ($roomAvailability <= date('Y-m-d')) {
                    $roomAvailability = 'Available';
                } else {
                    $roomAvailability = 'Available from ' . $roomAvailability;
                }
                ?>
                <div class="row">
                    <div class="col-lg-8 mt-3 bg-white p-4">
                        <div class="row pb-3">
                            <div class="col-lg-1">
                                <div class="radio">
                                    <label><input onclick="updatePropertyCharges(<?= $ky ?>)" id="option-radio-<?= $ky ?>" class="custom-radio-size-2 select-radio-<?= $properties->id; ?>" type="radio" name="optradio"></label>
                                </div>
                            </div>
                            <div class="col-lg-11">
                                <p class="small-bold-dark-font">Room</p>
        <?php for ($start = 0; $start < $totalBeds; $start++) { ?>
                                        <!--<img class="sharing-icon" src="<?php echo \yii\helpers\Url::home(true) ?>media/icons/single-person.svg" >-->
        <?php } ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-5 pr-0"><h6 class="property-pricing-title"><?= $properties->sub_unit_name; ?></h6><span class="property-pricing-val"><?= $roomAvailability; ?></span></div>
                            <div class="col-lg-2 pr-0"><h6 class="property-pricing-title">Rent</h6><span id="room-rent-<?= $ky ?>" class="property-pricing-val">&#8377;<?= Yii::$app->userdata->getFormattedMoney($roomCharges['rent']) ?></span></div>
                            <div class="col-lg-3 pr-0"><h6 class="property-pricing-title">Maintenance</h6><span id="room-maintenance-<?= $ky ?>" class="property-pricing-val"><?= $roomCharges['maintenance'] ?></span></div>
                            <div class="col-lg-2 pr-0"><h6 class="property-pricing-title">Deposit</h6><span id="room-deposit-<?= $ky ?>" class="property-pricing-val">&#8377;<?= Yii::$app->userdata->getFormattedMoney($roomCharges['deposit']) ?></span></div>
                            <input id="room-token-amount-<?= $ky ?>" type="hidden" name="token_amount" value="<?= $roomCharges['token_amount'] ?>" />
                        </div>
                    </div>
                    <div class="col-lg-4"></div>
                </div>
                <?php
            }
            foreach ($bedDetails as $ky => $properties) {
                $lastKeyCount++;
                $ky = $lastKeyCount;
                $totalBeds = Yii::$app->userdata->getBedCount($properties->parent);
                $roomAvailability = Yii::$app->userdata->getAvailDateForRoom($properties->id);
                $roomCharges = Yii::$app->userdata->getRoomCharges($properties->id);
                if ($roomAvailability <= date('Y-m-d')) {
                    $roomAvailability = 'Available';
                } else {
                    $roomAvailability = 'Available from ' . $roomAvailability;
                }
                ?>
                <div class="row">
                    <div class="col-lg-8 mt-3 bg-white p-4">
                        <div class="row pb-3">
                            <div class="col-lg-1">
                                <div class="radio">
                                    <label><input onclick="updatePropertyCharges(<?= $ky ?>)" id="option-radio-<?= $ky ?>" class="custom-radio-size-2" type="radio" name="optradio"></label>
                                </div>
                            </div>
                            <div class="col-lg-11">
                                <p class="small-bold-dark-font">Bed - <?= $totalBeds ?> sharing</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-5 pr-0"><h6 class="property-pricing-title"><?= $properties->sub_unit_name; ?></h6><span class="property-pricing-val"><?= $roomAvailability; ?></span></div>
                            <div class="col-lg-2 pr-0"><h6 class="property-pricing-title">Rent</h6><span id="room-rent-<?= $ky ?>" class="property-pricing-val">&#8377;<?= Yii::$app->userdata->getFormattedMoney($roomCharges['rent']) ?></span></div>
                            <div class="col-lg-3 pr-0"><h6 class="property-pricing-title">Maintenance</h6><span id="room-maintenance-<?= $ky ?>" class="property-pricing-val"><?= $roomCharges['maintenance'] ?></span></div>
                            <div class="col-lg-2 pr-0"><h6 class="property-pricing-title">Deposit</h6><span id="room-deposit-<?= $ky ?>" class="property-pricing-val">&#8377;<?= Yii::$app->userdata->getFormattedMoney($roomCharges['deposit']) ?></span></div>
                            <input id="room-token-amount-<?= $ky ?>" type="hidden" name="token_amount" value="<?= $roomCharges['token_amount'] ?>" />
                        </div>
                    </div>
                    <div class="col-lg-4"></div>
                </div>
            <?php } ?>

        <?php } ?>

        <?php if ($model->property_type == 5) { ?>
            <?php foreach ($roomDetails as $ky => $properties) { ?>
                <?php
                $totalBeds = Yii::$app->userdata->getBedCount($properties->id);
                $roomAvailability = Yii::$app->userdata->getAvailDateForRoom($properties->id);
                $roomCharges = Yii::$app->userdata->getRoomCharges($properties->id);
                if ($roomAvailability <= date('Y-m-d')) {
                    $roomAvailability = 'Available';
                } else {
                    $roomAvailability = 'Available from ' . $roomAvailability;
                }
                ?>
                <div class="row">
                    <div class="col-lg-8 mt-3 bg-white p-4">
                        <div class="row pb-3">
                            <div class="col-lg-1">
                                <div class="radio">
                                    <label><input onclick="updatePropertyCharges(<?= $ky ?>)" id="option-radio-<?= $ky ?>" class="custom-radio-size select-radio-<?= $properties->id; ?>" type="radio" name="optradio"></label>
                                </div>
                            </div>
                            <div class="col-lg-11">
        <?php for ($start = 0; $start < $totalBeds; $start++) { ?>
                                    <img class="sharing-icon" src="<?php echo \yii\helpers\Url::home(true) ?>media/icons/single-person.svg" >
        <?php } ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-5 pr-0"><h6 class="property-pricing-title"><?= $properties->sub_unit_name; ?></h6><span class="property-pricing-val"><?= $roomAvailability; ?></span></div>
                            <div class="col-lg-2 pr-0"><h6 class="property-pricing-title">Rent</h6><span id="room-rent-<?= $ky ?>" class="property-pricing-val">&#8377;<?= Yii::$app->userdata->getFormattedMoney($roomCharges['rent']) ?></span></div>
                            <div class="col-lg-3 pr-0"><h6 class="property-pricing-title">Maintenance</h6><span id="room-maintenance-<?= $ky ?>" class="property-pricing-val"><?= $roomCharges['maintenance'] ?></span></div>
                            <div class="col-lg-2 pr-0"><h6 class="property-pricing-title">Deposit</h6><span id="room-deposit-<?= $ky ?>" class="property-pricing-val">&#8377;<?= Yii::$app->userdata->getFormattedMoney($roomCharges['deposit']) ?></span></div>
                            <input id="room-token-amount-<?= $ky ?>" type="hidden" name="token_amount" value="<?= $roomCharges['token_amount'] ?>" />
                        </div>
                    </div>
                    <div class="col-lg-4"></div>
                </div>
    <?php } ?>
<?php } ?>
    </section>

    <section id="property-features">
        <div class="row">
            <div class="col-lg-8 mt-3 bg-white p-4">
                <h4 class="property-name">Property Features</h4>
                <div class="row">
<?php foreach ($features as $fea) { ?>
                        <div class="col-lg-2 mt-3 text-center">
                            <img class="property-features" src="<?php echo \yii\helpers\Url::home(true) ?><?php echo trim($fea['icon']); ?>" ><br />
                            <span class="icon-desc">
    <?php echo $fea['name']; ?> <?php echo (!empty(trim($fea['value']))) ? ' <br /> ' . trim($fea['value']) : ''; ?>
                            </span>
                        </div>
<?php } ?>
                </div>
            </div>
            <div class="col-lg-4"></div>
        </div>
    </section>

    <section id="property-amenties">
        <div class="row">
            <div class="col-lg-8 mt-3 bg-white p-4">
                <h4 class="property-name">Property Amenities</h4>
                <div class="row">
<?php foreach ($amenities as $amenitie) { ?>
                        <div class="col-lg-2 mt-3 text-center">
                            <img class="property-features" src="<?php echo \yii\helpers\Url::home(true) ?><?php echo trim($amenitie['icon']); ?>" ><br />
                            <span class="icon-desc">
    <?php echo $amenitie['name']; ?>
                            </span>
                        </div>
<?php } ?>
                    <div class="col-lg-2 mt-3"></div>
                </div>
            </div>
            <div class="col-lg-4"></div>
        </div>
    </section>

    <section id="neighborhood">
        <div class="row">
            <div class="col-lg-8 mt-3 bg-white p-4">
                <h4 class="property-name">Neighbourhood</h4><p></p>
                <input type="hidden" id="currentType" style="display: none">
                <div id="map" class="z-depth-1"></div>
            </div>
            <div class="col-lg-4 mt-3 bg-white p-4">
                <ul>
                    <li>
                        <a onclick="getMapLoc1('bus_station');" id="bus_station"  role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            <i class="fa fa-bus" aria-hidden="true"></i>
                            <span class="map-helpers">Bus Stop</span>
                        </a>
                    </li>

                    <li>
                        <a onclick="getMapLoc1('train_station');"  id="train_station" class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            <i class="fa fa-train" aria-hidden="true"></i>
                            <span class="map-helpers">Train Stations</span>
                        </a>
                    </li>
                    <li>
                        <a onclick="getMapLoc1('park|amusement_park');"  id="park" class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            <i class="fa fa-tree" aria-hidden="true"></i>
                            <span class="map-helpers">Parks</span>
                        </a>
                    </li>
                    <li>
                        <a onclick="getMapLoc1('convenience_store|home_goods_store');" class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            <i class="fa fa-shopping-basket" aria-hidden="true"></i>
                            <span class="map-helpers">Convenience Stores</span>
                        </a>
                    </li>
                    <li>
                        <a onclick="getMapLoc1('bank|atm');" id="bank" class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                            <i class="fa fa-money" aria-hidden="true"></i>
                            <span class="map-helpers">Bank/ATMs</span>
                        </a>
                    </li>
                    <li>
                        <a onclick="getMapLoc1('restaurant|food|bar');"  id="restaurant" class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                            <i class="fa fa-cutlery" aria-hidden="true"></i>
                            <span class="map-helpers">Restaurants</span>
                        </a>
                    </li>

                </ul>
            </div>
        </div>
    </section>
    <p>&nbsp;</p>
</div>
<script>
    var propertyName = '<?php echo $model->property_name ?>';
    var lat = parseFloat('<?php echo $model->lat ?>');
    var lon = parseFloat('<?php echo $model->lon ?>');
    var propertyId = <?php echo @$_GET['id'] ?>;
    var navHeight = 67;
    var leftHidePos = 450;
    var date = 0;
    var time = 0;
    var year = <?= date('y') ?>;
    var scheduleDateTime = 0;
    var lowestRentId = <?= $lowestRentId; ?>;
    var openPickSlot = <?php
if (!empty($_GET['opu'])) {
    echo 1;
} else {
    echo 0;
}
?>;
    var _csrf = '<?= Yii::$app->request->csrfToken; ?>';
<?php if ($defaultTokenNull) { ?>
        var defaultTokenNull = true;
<?php } else { ?>
        var defaultTokenNull = false;
<?php } ?>
</script>