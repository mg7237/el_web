<?php
$this->title = 'PMS- My Shortlisted Properties';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

<!-- Modal signup -->
<!-- Modal signup -->

<div id="content">
    <button type="button" id="sidebarCollapse" class="btn btn-info navbar-btn">
        <i class="glyphicon glyphicon-align-left"></i>
        <span></span>
    </button>
    <div class="container" style="margin-top: -42px;">
        <div class="col-md-4">
            <div class="cust-favourite" id="newfnt">
                <h4>Favourite / Scheduled / Booked list</h4>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-11" id="favlistmain">
            <div class="table-responsive cust-tableresponsive2 hide-mobile">
                <table class="table fixed sturdy feed-details-tbl" frame="box" id="favlist">
                    <thead>
                        <tr>
                            <th  class="text-left">Property Name</th>
                            <th class="text-center">Shortlist Type</th>
                            <th class="text-center">Site Visit Scheduled On</th>
                            <th class="text-center">Property Image</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($model) {

                            $_data = array();
                            foreach ($model as $v) {
                                if (isset($_data[$v['property_id']])) {
                                    continue;
                                }
                                $_data[$v['property_id']] = $v;
                            }
                            // if you need a zero-based array, otheriwse work with $_data
                            $model = array_values($_data);


                            $i = 1;
                            foreach ($model as $key => $wish) {
                                if ($i % 2 == 0) {
                                    $dclass = "dclass";
                                } else {
                                    $dclass = "";
                                }
                                ?>

                                <tr class="<?php echo $dclass; ?>">
                                    <td width="30%" class="textaling">
                                        <!--<div class="th_width" style="line-height: 1.4 !important;">-->
                                        <div class="th_width" style="">
                                            <a href="view?id=<?= $wish->property_id ?>"><?= Yii::$app->userdata->getPropertyNameById($wish->property_id); ?></a>
                                        </div>	
                                    </td>
                                    <td width="13%" class="text-center">
                                        <?php
                                        switch ($wish->status) {
                                            case 1:
                                                echo "Favourite";
                                                break;
                                            case 2:
                                                echo "Booked";
                                                break;
                                            case 3:
                                                echo "Scheduled";
                                                break;
                                        }
                                        ?>
                                    </td>
                                    <td width="18%" class="text-center"><?php
                                        switch ($wish->status) {
                                            case 3:
                                                echo $wish->visit_date . " " . $wish->visit_time;
                                                break;
                                            case 2:
                                                if ($wish->visit_date != '01-Jan-1970') {
                                                    echo $wish->visit_date . " " . $wish->visit_time;
                                                }
                                                break;
                                        }
                                        ?></td>
                                    <td width="15%" class="text-center">
                                        <?php $propertiesImages = \app\models\PropertyImages::find()->where(['property_id' => $wish->property_id])->one(); ?>
                                        <a href="javascript:;">
                                            <img src="<?php echo ($propertiesImages) ? "../" . $propertiesImages->image_url : Url::home(true) . 'images/noAvatar.png'; ?>" alt="image" class="img_style">
                                        </a>
                                    </td>
                                    <td width="14%" class="text-center">



                                        <form id="bookingForm" action="bookinginfo" method="GET" style="display:none;">

                                            <input type="hidden" name="property_type" id="property_type" value="">
                                            <input type="hidden" name="property_id" id="property_id" value=""> 
                                            <input type="hidden" name="parent_property_id" id="parent_property_id" value="">
                                            <input type="hidden" value="" name="rent" id="rent"> 
                                            <input type="hidden" value="" name="deposit" id="deposit"> 
                                            <input type="hidden" value="" name="token_amount" id="token_amount"> 
                                        </form>
                                        <?php
                                        if ($wish->confirm != 1) {
                                            switch ($wish->status) {
                                                case 1:
                                                    ?>
                                                    <a style="cursor:pointer" data-id="<?= $wish->id; ?>" data-type="<?= $wish->status ?>" data-status="<?= $wish->status ?>"  class="ScheduleProperty3" data-toggle="modal" data-target="#myModal"><img src="<?php echo Url::home(true); ?>images/img/ic-calendar_3.png" data-toggle="tooltip" title="Schedule/Reschedule"></a>	
                                                    <?php if (Yii::$app->userdata->getPropertyAttrById($wish->property_id) === 0) { ?>
                                                        <a data-case-1-1="" style="cursor:pointer" data-id="<?= $wish->id; ?>" data-type="<?= $wish->status ?>" data-status="<?= $wish->status ?>" onclick="alert('The selected property is not available for booking, please shedule visit and confirm once you have visited the property and ready to lease '); return false;" class="BookProperty" alt="Book"><img src="<?php echo Url::home(true); ?>images/img/ic-payment.png" data-toggle="tooltip" title="Book Now"></a>
                                                    <?php } else if (Yii::$app->userdata->getPropertyAttrById($wish->property_id) === 'co') { ?>
                                                        <a data-case-1-2="" style="cursor:pointer" data-id="<?= $wish->id; ?>" data-type="<?= $wish->status ?>" data-status="<?= $wish->status ?>" onclick="showCoMess(<?= $wish->property_id; ?>)" class="BookProperty" alt="Book"><img src="<?php echo Url::home(true); ?>images/img/ic-payment.png" data-toggle="tooltip" title="Book Now"></a>
                                                    <?php } else { ?>
                                                        <?php if (Yii::$app->userdata->getPropertyTokenAmountById($wish->property_id)) { ?>
                                                            <a style="cursor:pointer" data-id="<?= $wish->id; ?>" data-type="<?= $wish->status ?>" data-status="<?= $wish->status ?>"  class="ScheduleProperty2" data-toggle="modal" data-target="#myModal2"><img src="<?php echo Url::home(true); ?>images/img/ic-payment.png" data-toggle="tooltip" title="Book Now"></a>
                                                            <?php $form = ActiveForm::begin(['id' => 'visits-book-form-' . $wish->id, 'action' => Url::home(true) . 'paytm/bookproperty', 'options' => ['style' => 'position: absolute;visibility: hidden;']]); ?>
                                                            <input type="hidden" id="propertyvisits-property_id" name="PropertyVisits[property_id]" value="<?= $wish->property_id ?>" />
                                                            <input type="hidden" id="propertyvisits-txn_amount" name="PropertyVisits[txn_amount]" value="<?= Yii::$app->userdata->getPropertyTokenAmountById($wish->property_id) ?>" />
                                                            <input type="hidden" name="app_redirect_url" value="<?php echo Url::home(true) ?>site/myfavlist" />
                                                            <button style="cursor:pointer;background: none;border: none;" alt="Book"><img src="<?php echo Url::home(true); ?>images/img/ic-payment.png" data-toggle="tooltip" title="Book Now"></button>
                                                            <?php ActiveForm::end(); ?>
                                                        <?php } else { ?>
                                                            <button type="button" onclick="alert('Online booking facility is not available for this property, you may visit the property and our sales representative will help you guide with next steps on booking this property.'); return false;" style="cursor:pointer;background: none;border: none;" alt="Book"><img src="<?php echo Url::home(true); ?>images/img/ic-payment.png" title="Book Now"></button>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <a style="cursor:pointer" data-id="<?= $wish->id; ?>" data-type="<?= $wish->status ?>" data-status="<?= $wish->status ?>"  class="confirmProperty" alt="Confirm"><img src="<?php echo Url::home(true); ?>images/img/ic-approved.png" data-toggle="tooltip" title="Confirm Property"></a>
                                                    <?php
                                                    break;
                                                case 2:
                                                    ?>
                                                    <a style="cursor:pointer" data-id="<?= $wish->id; ?>" data-type="<?= $wish->status ?>" data-status="<?= $wish->status ?>"  class="ScheduleProperty" alt="Schedule" onclick="alert('Property Already Booked, you may confirm or delete the property.'); return false;"><img src="<?php echo Url::home(true); ?>images/img/ic-calendar_3.png" data-toggle="tooltip" title="Schedule/Reschedule"></a>													
                                                    <button type="button" onclick="alert('Property Already Booked, you may confirm or delete the property.'); return false;" style="cursor:pointer;background: none;border: none;" alt="Book"><img src="<?php echo Url::home(true); ?>images/img/ic-payment.png" title="Book Now"></button>
                                                    <a style="cursor:pointer" data-id="<?= $wish->id; ?>" data-type="<?= $wish->status ?>" data-status="<?= $wish->status ?>"  class="confirmProperty" alt="Confirm"><img src="<?php echo Url::home(true); ?>images/img/ic-approved.png" data-toggle="tooltip" title="Confirm Property"></a>
                                                    <?php
                                                    break;
                                                case 3:
                                                    ?>
                                                    <a style="cursor:pointer" data-id="<?= $wish->id; ?>" data-type="<?= $wish->status ?>" data-status="<?= $wish->status ?>"  class="ScheduleProperty" data-toggle="modal" data-target="#myModal" alt="reSchedule" data-visitdate="<?= $wish->visit_date ?>" data-visittime="<?= $wish->visit_time ?>"><img src="<?php echo Url::home(true); ?>images/img/ic-calendar_3.png" data-toggle="tooltip" title="Schedule/Reschedule"></a>
                                                    <?php if (Yii::$app->userdata->getPropertyAttrById($wish->property_id) === 0) { ?>
                                                        <a data-case-3-1="" style="cursor:pointer" data-id="<?= $wish->id; ?>" data-type="<?= $wish->status ?>" data-status="<?= $wish->status ?>"  onclick="alert('The selected property is not available for booking, please shedule visit and confirm once you have visited the property and ready to lease '); return false;" class="BookProperty" alt="Book"><img src="<?php echo Url::home(true); ?>images/img/ic-payment.png" data-toggle="tooltip" title="Book Now"></a>
                                                    <?php } else if (Yii::$app->userdata->getPropertyAttrById($wish->property_id) === 'co') { ?>
                                                        <a data-case-3-2="" style="cursor:pointer" data-id="<?= $wish->id; ?>" data-type="<?= $wish->status ?>" data-status="<?= $wish->status ?>" onclick="showCoMess(<?= $wish->property_id ?>)" class="BookProperty" alt="Book"><img src="<?php echo Url::home(true); ?>images/img/ic-payment.png" data-toggle="tooltip" title="Book Now"></a>
                                                    <?php } else { ?>
                                                        <?php if (Yii::$app->userdata->getPropertyTokenAmountById($wish->property_id)) { ?>
                                                            <?php $form = ActiveForm::begin(['id' => 'visits-book-form', 'action' => Url::home(true) . 'paytm/bookproperty', 'options' => ['style' => 'display: initial;']]); ?>
                                                            <input type="hidden" id="propertyvisits-property_id" name="PropertyVisits[property_id]" value="<?= $wish->property_id ?>" />
                                                            <input type="hidden" id="propertyvisits-txn_amount" name="PropertyVisits[txn_amount]" value="<?= Yii::$app->userdata->getPropertyTokenAmountById($wish->property_id) ?>" />
                                                            <input type="hidden" name="app_redirect_url" value="<?php echo Url::home(true) ?>site/myfavlist" />    
                                                            <button style="cursor:pointer;background: none;border: none;" alt="Book"><img src="<?php echo Url::home(true); ?>images/img/ic-payment.png" data-toggle="tooltip" title="Book Now"></button>
                                                            <?php ActiveForm::end(); ?>
                                                        <?php } else { ?>
                                                            <button type="button" onclick="alert('Online booking facility is not available for this property, you may visit the property and our sales representative will help you guide with next steps on booking this property.'); return false;" style="cursor:pointer;background: none;border: none;" alt="Book"><img src="<?php echo Url::home(true); ?>images/img/ic-payment.png" title="Book Now"></button>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <a style="cursor:pointer" data-id="<?= $wish->id; ?>" data-type="<?= $wish->status ?>"  data-status="<?= $wish->status ?>" class="confirmProperty data_submit" alt="Confirm"><img src="<?php echo Url::home(true); ?>images/img/ic-approved.png" data-toggle="tooltip" title="Confirm Property"></a>
                                                    <?php
                                                    break;
                                            }
                                        }
                                        ?>
                                        <?php if ($wish->status == 2) { ?>
                                            <a style="cursor:pointer" data-id="<?= $wish->id; ?>" data-type="<?= $wish->status ?>" data-status="<?= $wish->status ?>"  class="delete"><img src="<?php echo Url::home(true); ?>images/img/ic-delete.png" data-toggle="tooltip" title="Delete"></a>
                                        <?php } else { ?>
                                            <a style="cursor:pointer" data-id="<?= $wish->id; ?>" data-type="<?= $wish->status ?>" data-status="<?= $wish->status ?>"  class="delete2"><img src="<?php echo Url::home(true); ?>images/img/ic-delete.png" data-toggle="tooltip" title="Delete"></a>
                                        <?php } ?> 

                                    </td>
                                </tr>
                                <?php
                                $i++;
                            }
                        } else {
                            ?> 
                            <tr> <td colspan="6">No shortlisted property</td> </tr>
                            <?php
                        }
                        ?> 
                    </tbody>
                </table>
            </div>

            <!-- mobile view -->
            <section class="show-mobile">
                <div class="continer">
                    <?php
                    if ($model) {

                        $_data = array();
                        foreach ($model as $v) {
                            if (isset($_data[$v['property_id']])) {
                                continue;
                            }
                            $_data[$v['property_id']] = $v;
                        }
                        // if you need a zero-based array, otheriwse work with $_data
                        $model = array_values($_data);


                        $i = 1;
                        foreach ($model as $key => $wish) {
                            if ($i % 2 == 0) {
                                $dclass = "dclass";
                            } else {
                                $dclass = "";
                            }
                            ?>
                            <div class="row card-bacolor">
                                <div class="cust-card">
                                    <?php $propertiesImages = \app\models\PropertyImages::find()->where(['property_id' => $wish->property_id])->one(); ?>
                                    <img class="card-image" src="<?php echo ($propertiesImages) ? "../" . $propertiesImages->image_url : Url::home(true) . 'images/noAvatar.png'; ?>" alt="property image">
                                    <form>
                                        <div class="form-row cust-form">
                                            <div class="form-group col-md-12">
                                                <label for="inputEmail4">Property Name: </label>
                                                <p class="crd-style"><?= Yii::$app->userdata->getPropertyNameById($wish->property_id); ?></p>
                                                <!-- <input type="text" class="form-control cust-formcontrol" id="inputEmail4" placeholder="<?= Yii::$app->userdata->getPropertyNameById($wish->property_id); ?>"> -->
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label for="inputPassword4">Shortlist Type: </label>
                                                <input type="text" class="form-control cust-formcontrol" id="inputPassword4" placeholder="<?php
                                                switch ($wish->status) {
                                                    case 1:
                                                        echo "Favourite";
                                                        break;
                                                    case 2:
                                                        echo "Booked";
                                                        break;
                                                    case 3:
                                                        echo "Scheduled";
                                                        break;
                                                }
                                                ?>">
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label for="inputPassword4">Site Visit Scheduled On: </label>
                                                <input type="text" class="form-control cust-formcontrol" id="inputPassword4" placeholder="<?php
                                                switch ($wish->status) {
                                                    case 3:
                                                        echo $wish->visit_date . " " . $wish->visit_time;
                                                        break;
                                                    case 2:
                                                        if ($wish->visit_date != '01-Jan-1970') {
                                                            echo $wish->visit_date . " " . $wish->visit_time;
                                                        }
                                                        break;
                                                }
                                                ?>">
                                            </div>
                                        </div>
                                    </form>
                                    <div class="action-text">
                                        <h5>Action</h5>
                                    </div>
                                    <div class="action">
                                        <?php
                                        if ($wish->confirm != 1) {
                                            switch ($wish->status) {
                                                case 1:
                                                    ?>

                                                    <div class="action-img">
                                                        <a style="cursor:pointer" data-id="<?= $wish->id; ?>" data-type="<?= $wish->status ?>" data-status="<?= $wish->status ?>"  class="ScheduleProperty3" data-toggle="modal" data-target="#myModal"><img src="<?php echo Url::home(true); ?>images/img/ic-calendar_3.png" data-toggle="tooltip" title="Schedule/Reschedule"></a>
                                                    </div>
                                                    <?php if (Yii::$app->userdata->getPropertyAttrById($wish->property_id) === 0) { ?>
                                                        <div class="action-img">
                                                            <a data-case-1-1="" style="cursor:pointer" data-id="<?= $wish->id; ?>" data-type="<?= $wish->status ?>" data-status="<?= $wish->status ?>" onclick="alert('The selected property is not available for booking, please shedule visit and confirm once you have visited the property and ready to lease '); return false;" class="BookProperty" alt="Book"><img src="<?php echo Url::home(true); ?>images/img/ic-payment.png" data-toggle="tooltip" title="Book Now"></a>
                                                        </div>
                                                    <?php } else if (Yii::$app->userdata->getPropertyAttrById($wish->property_id) === 'co') { ?>
                                                        <div class="action-img">
                                                            <a data-case-1-2="" style="cursor:pointer" data-id="<?= $wish->id; ?>" data-type="<?= $wish->status ?>" data-status="<?= $wish->status ?>" onclick="showCoMess(<?= $wish->property_id; ?>)" class="BookProperty" alt="Book"><img src="<?php echo Url::home(true); ?>images/img/ic-payment.png" data-toggle="tooltip" title="Book Now"></a>
                                                        </div>
                                                    <?php } else { ?>
                                                        <?php if (Yii::$app->userdata->getPropertyTokenAmountById($wish->property_id)) { ?>
                                                            <div class="action-img">
                                                                <a style="cursor:pointer" data-id="<?= $wish->id; ?>" data-type="<?= $wish->status ?>" data-status="<?= $wish->status ?>"  class="ScheduleProperty2" data-toggle="modal" data-target="#myModal2"><img src="<?php echo Url::home(true); ?>images/img/ic-payment.png" data-toggle="tooltip" title="Book Now"></a>
                                                                <?php $form = ActiveForm::begin(['id' => 'visits-book-form-' . $wish->id, 'action' => Url::home(true) . 'paytm/bookproperty', 'options' => ['style' => 'position: absolute;visibility: hidden;']]); ?>
                                                                <input type="hidden" id="propertyvisits-property_id" name="PropertyVisits[property_id]" value="<?= $wish->property_id ?>" />
                                                                <input type="hidden" id="propertyvisits-txn_amount" name="PropertyVisits[txn_amount]" value="<?= Yii::$app->userdata->getPropertyTokenAmountById($wish->property_id) ?>" />
                                                                <input type="hidden" name="app_redirect_url" value="<?php echo Url::home(true) ?>site/myfavlist" />
                                                                <button style="cursor:pointer;background: none;border: none;" alt="Book"><img src="<?php echo Url::home(true); ?>images/img/ic-payment.png" data-toggle="tooltip" title="Book Now"></button>
                                                            </div>
                                                            <?php ActiveForm::end(); ?>
                                                        <?php } else { ?>
                                                            <div class="action-img">
                                                                <button type="button" onclick="alert('Online booking facility is not available for this property, you may visit the property and our sales representative will help you guide with next steps on booking this property.'); return false;" style="cursor:pointer;background: none;border: none;" alt="Book"><img src="<?php echo Url::home(true); ?>images/img/ic-payment.png" title="Book Now"></button>
                                                            </div>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <div class="action-img">
                                                        <a style="cursor:pointer" data-id="<?= $wish->id; ?>" data-type="<?= $wish->status ?>" data-status="<?= $wish->status ?>"  class="confirmProperty" alt="Confirm"><img src="<?php echo Url::home(true); ?>images/img/ic-approved.png" data-toggle="tooltip" title="Confirm Property"></a>
                                                    </div>

                                                    <?php
                                                    break;
                                                case 2:
                                                    ?>

                                                    <div class="action-img">
                                                        <a style="cursor:pointer" data-id="<?= $wish->id; ?>" data-type="<?= $wish->status ?>" data-status="<?= $wish->status ?>"  class="ScheduleProperty" alt="Schedule" onclick="alert('Property Already Booked, you may confirm or delete the property.'); return false;"><img src="<?php echo Url::home(true); ?>images/img/ic-calendar_3.png" data-toggle="tooltip" title="Schedule/Reschedule"></a>	
                                                    </div>
                                                    <div class="action-img">
                                                        <button type="button" onclick="alert('Property Already Booked, you may confirm or delete the property.'); return false;" style="cursor:pointer;background: none;border: none;" alt="Book"><img src="<?php echo Url::home(true); ?>images/img/ic-payment.png" title="Book Now"></button>
                                                        <a style="cursor:pointer" data-id="<?= $wish->id; ?>" data-type="<?= $wish->status ?>" data-status="<?= $wish->status ?>"  class="confirmProperty" alt="Confirm"><img src="<?php echo Url::home(true); ?>images/img/ic-approved.png" data-toggle="tooltip" title="Confirm Property"></a>
                                                    </div>
                                                </div>
                                                <?php
                                                break;
                                            case 3:
                                                ?>

                                                <div class="action-img">
                                                    <a style="cursor:pointer" data-id="<?= $wish->id; ?>" data-type="<?= $wish->status ?>" data-status="<?= $wish->status ?>"  class="ScheduleProperty" data-toggle="modal" data-target="#myModal" alt="reSchedule" data-visitdate="<?= $wish->visit_date ?>" data-visittime="<?= $wish->visit_time ?>"><img src="<?php echo Url::home(true); ?>images/img/ic-calendar_3.png" data-toggle="tooltip" title="Schedule/Reschedule"></a>									
                                                </div>
                                                <?php if (Yii::$app->userdata->getPropertyAttrById($wish->property_id) === 0) { ?>
                                                    <div class="action-img">
                                                        <a data-case-3-1="" style="cursor:pointer" data-id="<?= $wish->id; ?>" data-type="<?= $wish->status ?>" data-status="<?= $wish->status ?>"  onclick="alert('The selected property is not available for booking, please shedule visit and confirm once you have visited the property and ready to lease '); return false;" class="BookProperty" alt="Book"><img src="<?php echo Url::home(true); ?>images/img/ic-payment.png" data-toggle="tooltip" title="Book Now"></a>
                                                    </div>	
                                                <?php } else if (Yii::$app->userdata->getPropertyAttrById($wish->property_id) === 'co') { ?>	
                                                    <div class="action-img">
                                                        <a data-case-3-2="" style="cursor:pointer" data-id="<?= $wish->id; ?>" data-type="<?= $wish->status ?>" data-status="<?= $wish->status ?>" onclick="showCoMess(<?= $wish->property_id ?>)" class="BookProperty" alt="Book"><img src="<?php echo Url::home(true); ?>images/img/ic-payment.png" data-toggle="tooltip" title="Book Now"></a>
                                                    </div>	
                                                <?php } else { ?>
                                                    <?php if (Yii::$app->userdata->getPropertyTokenAmountById($wish->property_id)) { ?>
                                                        <div class="action-img">
                                                            <?php $form = ActiveForm::begin(['id' => 'visits-book-form', 'action' => Url::home(true) . 'paytm/bookproperty', 'options' => ['style' => 'display: initial;']]); ?>
                                                            <input type="hidden" id="propertyvisits-property_id" name="PropertyVisits[property_id]" value="<?= $wish->property_id ?>" />
                                                            <input type="hidden" id="propertyvisits-txn_amount" name="PropertyVisits[txn_amount]" value="<?= Yii::$app->userdata->getPropertyTokenAmountById($wish->property_id) ?>" />
                                                            <input type="hidden" name="app_redirect_url" value="<?php echo Url::home(true) ?>site/myfavlist" />    
                                                            <button style="cursor:pointer;background: none;border: none;" alt="Book"><img src="<?php echo Url::home(true); ?>images/img/ic-payment.png" data-toggle="tooltip" title="Book Now"></button>
                                                            <?php ActiveForm::end(); ?>




                                                        </div>

                                                    <?php } else { ?>
                                                        <div class="action-img">
                                                            <button type="button" onclick="alert('Online booking facility is not available for this property, you may visit the property and our sales representative will help you guide with next steps on booking this property.'); return false;" style="cursor:pointer;background: none;border: none;" alt="Book"><img src="<?php echo Url::home(true); ?>images/img/ic-payment.png" title="Book Now"></button>
                                                        </div>
                                                    <?php } ?>
                                                <?php } ?>

                                                <div class="action-img"><a style="cursor:pointer" data-id="<?= $wish->id; ?>" data-type="<?= $wish->status ?>"  data-status="<?= $wish->status ?>" class="confirmProperty data_submit" alt="Confirm"><img src="<?php echo Url::home(true); ?>images/img/ic-approved.png" data-toggle="tooltip" title="Confirm Property"></a></div>


                                                <?php
                                                break;
                                        }
                                    }
                                    ?>
                                    <?php if ($wish->status == 2) { ?>

                                        <div class="action-img"><a style="cursor:pointer" data-id="<?= $wish->id; ?>" data-type="<?= $wish->status ?>" data-status="<?= $wish->status ?>"  class="delete"><img src="<?php echo Url::home(true); ?>images/img/ic-delete.png" data-toggle="tooltip" title="Delete"></a></div>

                                    <?php } else { ?>

                                        <div class="action-img"><a style="cursor:pointer" data-id="<?= $wish->id; ?>" data-type="<?= $wish->status ?>" data-status="<?= $wish->status ?>"  class="delete2"><img src="<?php echo Url::home(true); ?>images/img/ic-delete.png" data-toggle="tooltip" title="Delete"></a></div>

                                    <?php } ?> 
                                </div>
                            </div>


                        </div>
                        <br />
                        <?php
                        $i++;
                    }
                } else {
                    ?> 
                    <tr> <td colspan="6">No shortlisted property</td> </tr>
                    <?php
                }
                ?> 
        </div>
        </section>
        <!-- ./mobile view -->

    </div>
</div>
</div>


<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog cust-modaldialog" id="mdl-dialog">

        <!-- Modal content-->
        <div class="modal-content">

            <style>
                table#favlist {
                    border: 1px solid white;
                    box-shadow: 1px;
                    box-shadow: 0px 1px 2px 2px rgba(0,0,0,0.10);
                }
                p#table_heading {
                    /* font-weight: bold; */
                    font-style: normal;
                    font-stretch: normal;
                    line-height: 3;
                    letter-spacing: 0.9px;
                    text-align: left;
                    color: #323a47;
                    /* margin-top: -42px; */
                    /* padding-top: 46px; */
                }
                .modal-content {
                    position: relative !important;
                    background-color: #fff !important;
                    border: 1px solid #999 !important;
                    border: 1px solid rgba(0,0,0,0.2);
                    box-shadow: 0 3px 9px rgba(0,0,0,0.5) !important;
                    background-clip: padding-box !important;
                    outline: 0;
                    padding: 20px;
                }
                .modal_title h3 {
                    font-size: 17px;
                    padding: 0px 15px;
                }
                input#visit_date {
                    width: 98%;
                }
                input#propertyvisits-visit_time {
                    width: 98%;
                }
                .modal-footer {
                    padding: 5px;
                }

                .btn-success {
                    color: #fff;
                    background-color: #347c17;
                    border-color: #347c17;
                    border-radius: 0px;
                    padding: 4px 25px;
                }

                .modal-body {
                    position: relative;
                    padding: 15px;
                    height: 150px;
                    box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.5);
                }

            </style>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class= "modal_title">
                    <h3>When would you like to visit this property?</h3>
                </div>


            </div>
            <div class="modal-body">		
                <?php $form = ActiveForm::begin(['id' => 'visits', 'action' => '']); ?>
                <?= $form->field($modelvisit, 'schedule_id')->hiddenInput(['value' => 0, 'id' => 'schedule_id'])->label(false); ?>
                <?= $form->field($modelvisit, 'status')->hiddenInput(['id' => 'property_status_f'])->label(false); ?>

                <div class="col-md-6 col-sm-12">
                    <?= $form->field($modelvisit, 'visit_date')->textInput(['maxlength' => true, 'placeholder' => 'Visit Date', 'id' => 'visit_date', 'autocomplete' => 'off'])->label(false); ?>
                </div>
                <div class="col-md-6 col-sm-12">
                    <select aria-required="true" name="PropertyVisits[visit_time]" id="propertyvisits-visit_time" class="form-control">
                        <option value=""> At </option>
                        <option value="10:00"> 10:00 - 10:30</option>
                        <option value="10:30"> 10:30 - 11:00</option>
                        <option value="11:00"> 11:00 - 11:30</option>
                        <option value="11:30"> 11:30 - 12:00</option>
                        <option value="12:00"> 12:00 - 12:30</option>
                        <option value="12:30"> 12:30 - 13:00</option>
                        <option value="13:00"> 13:00 - 13:30</option>
                        <option value="13:30"> 13:30 - 14:00</option>
                        <option value="14:00"> 14:00 - 14:30</option>
                        <option value="14:30"> 14:30 - 15:00</option>
                        <option value="15:00"> 15:00 - 15:30</option>
                        <option value="15:30"> 15:30 - 16:00</option>
                        <option value="16:00"> 16:00 - 16:30</option>
                        <option value="16:30"> 16:30 - 17:00</option>
                        <option value="17:00"> 17:00 - 17:30</option>
                        <option value="17:30"> 17:30 - 18:00</option>
                        <option value="18:00"> 18:00 - 18:30</option>
                        <option value="18:30"> 18:30 - 19:00</option>
                        <option value="19:00"> 19:00 - 19:30</option>
                        <option value="19:30"> 19:30 - 20:00</option>
                        <option value="20:00"> 20:00 - 20:30</option>
                        <option value="20:30"> 20:30 - 21:00</option>
                        <option value="21:00"> 21:00 - 21:30</option>
                    </select>
                    <div class="help-block visit-time-help-block" style="display: none; color: #A94442;">Visit time cannot be blank.</div>
                    <ul class='time_suggestion' style='display: none;'>

                    </ul>
                </div>

                <div class="col-sm-12 col-md-12 text-center">
                    <?= Html::submitButton('Schedule', ['id' => 'visitsubmit', 'class' => 'btn btn-primary', 'style' => 'width: 25%; background-color: #59ABE3;']) ?>
                    <button type="button" class="btn btn-grey cust-cls" data-dismiss="modal" style="width: 25%;">Close</button>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
            <div class="modal-footer">

            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="myModal2" role="dialog">
    <div class="modal-dialog cust-modaldialog" id="mdl-dialog">

        <!-- Modal content-->
        <div class="modal-content">

            <style>
                table#favlist {
                    border: 1px solid white;
                    box-shadow: 1px;
                    box-shadow: 0px 1px 2px 2px rgba(0,0,0,0.10);
                }
                p#table_heading {
                    /* font-weight: bold; */
                    font-style: normal;
                    font-stretch: normal;
                    line-height: 3;
                    letter-spacing: 0.9px;
                    text-align: left;
                    color: #323a47;
                    /* margin-top: -42px; */
                    /* padding-top: 46px; */
                }
                .modal-content {
                    position: relative !important;
                    background-color: #fff !important;
                    border: 1px solid #999 !important;
                    border: 1px solid rgba(0,0,0,0.2);
                    box-shadow: 0 3px 9px rgba(0,0,0,0.5) !important;
                    background-clip: padding-box !important;
                    outline: 0;
                    padding: 20px;
                }
                .modal_title h3 {
                    font-size: 17px;
                    padding: 0px 15px;
                }
                input#visit_date {
                    width: 98%;
                }
                input#propertyvisits-visit_time {
                    width: 98%;
                }
                .modal-footer {
                    padding: 5px;
                }

                .btn-success {
                    color: #fff;
                    background-color: #347c17;
                    border-color: #347c17;
                    border-radius: 0px;
                    padding: 4px 25px;
                }

                .modal-body {
                    position: relative;
                    padding: 15px;
                    height: 300px;
                    box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.5);
                }

            </style>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class= "modal_title">
                    <h3>Please schedule a property visit before booking.</h3>
                </div>


            </div>
            <div class="modal-body">		
                <?php $form = ActiveForm::begin(['id' => 'visits2', 'action' => '']); ?>
                <?= $form->field($modelvisit, 'schedule_id')->hiddenInput(['value' => 0, 'id' => 'schedule_id2'])->label(false); ?>
                <?= $form->field($modelvisit, 'status')->hiddenInput(['id' => 'property_status_f2'])->label(false); ?>

                <div class="col-md-6 col-sm-12">
                    <?= $form->field($modelvisit, 'visit_date')->textInput(['maxlength' => true, 'placeholder' => 'Visit Date', 'id' => 'visit_date2', 'autocomplete' => 'off'])->label(false); ?>
                </div>
                <div class="col-md-6 col-sm-12">
                    <select aria-required="true" name="PropertyVisits[visit_time]" id="propertyvisits-visit_time2" class="form-control">
                        <option value=""> At </option>
                        <option value="10:00"> 10:00 - 10:30</option>
                        <option value="10:30"> 10:30 - 11:00</option>
                        <option value="11:00"> 11:00 - 11:30</option>
                        <option value="11:30"> 11:30 - 12:00</option>
                        <option value="12:00"> 12:00 - 12:30</option>
                        <option value="12:30"> 12:30 - 13:00</option>
                        <option value="13:00"> 13:00 - 13:30</option>
                        <option value="13:30"> 13:30 - 14:00</option>
                        <option value="14:00"> 14:00 - 14:30</option>
                        <option value="14:30"> 14:30 - 15:00</option>
                        <option value="15:00"> 15:00 - 15:30</option>
                        <option value="15:30"> 15:30 - 16:00</option>
                        <option value="16:00"> 16:00 - 16:30</option>
                        <option value="16:30"> 16:30 - 17:00</option>
                        <option value="17:00"> 17:00 - 17:30</option>
                        <option value="17:30"> 17:30 - 18:00</option>
                        <option value="18:00"> 18:00 - 18:30</option>
                        <option value="18:30"> 18:30 - 19:00</option>
                        <option value="19:00"> 19:00 - 19:30</option>
                        <option value="19:30"> 19:30 - 20:00</option>
                        <option value="20:00"> 20:00 - 20:30</option>
                        <option value="20:30"> 20:30 - 21:00</option>
                        <option value="21:00"> 21:00 - 21:30</option>
                    </select>
                    <div class="help-block visit-time-help-block2" style="display: none; color: #A94442;">Visit time cannot be blank.</div>
                    <ul class='time_suggestion' style='display: none;'>

                    </ul>
                </div>

                <div class="col-sm-12 col-md-12 text-center">
                    <?= Html::submitButton('Next', ['id' => 'visitsubmit2', 'class' => 'btn btn-primary', 'style' => 'width: 25%; background-color: #59ABE3;']) ?>
                    <button type="button" class="btn btn-grey" data-dismiss="modal" style="width: 25%;">Cancel</button>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
            <div class="modal-footer">

            </div>
        </div>

    </div>
</div>

<script>

    function showCoMess(eleId) {
        alert('Please select bed/room before booking');
        window.location = "view?id=" + eleId;
    }

    $(function () {
//        $('.visit_time_class').datetimepicker({
//            format: 'HH:mm'
//        });
    });

    $(document).ready(function () {

        $('#visits').submit(function (e) {
            e.preventDefault();
            var full = $('#visit_date').val() + ' ' + $('#propertyvisits-visit_time').val() + ':00';
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
                startLoader();
                var actionurl = e.currentTarget.action;
                $.ajax({
                    url: '<?php echo Url::home(true); ?>site/updatevisitajax',
                    type: 'POST',
                    cache: false,
                    dataType: 'JSON',
                    data: $("#visits").serialize(),
                    success: function (data) {
                        hideLoader();
                        alert(data.msg);
                        if (data.success == '1') {
                            location.reload();
                        }
                    }
                });
                return false;
            }
        });

        $('#visits2').submit(function (e) {
            e.preventDefault();
            var full = $('#visit_date2').val() + ' ' + $('#propertyvisits-visit_time2').val() + ':00';
            if ($('#propertyvisits-visit_time2').val() == '') {
                $('.visit-time-help-block2').css('display', 'block');
                return;
            } else {
                $('.visit-time-help-block2').css('display', 'none');
            }
            var d = new Date(full);
            var nowa = new Date();
            nowa = new Date(nowa.setHours(nowa.getHours() + 4));
            if (d <= nowa) {
                alert('The schedule date/time should be 4 hours later than current date/time');
            } else {
                startLoader();
                var actionurl = e.currentTarget.action;
                $.ajax({
                    url: '<?php echo Url::home(true); ?>site/updatevisitajax',
                    type: 'POST',
                    cache: false,
                    dataType: 'JSON',
                    data: $("#visits2").serialize(),
                    success: function (data) {
                        //hideLoader();
                        //alert(data.msg);
                        //alert(window.clickedEleId);
                        if (window.clickedEleId > 0) {
                            $('#visits-book-form-' + window.clickedEleId).submit();
                        } else if (data.success == '1') {
                            location.reload();
                        }
                    }
                });
                return false;
            }
        });

        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        $(document).on("click", ".delete", function (e) {
            e.preventDefault();
            var confirm_var = confirm('Deleting a booked property will cancel your booking and initiate refund of booking amount paid by you against this property, do you wish to proceed?');
            if (confirm_var == true) {
                // alert($(this).attr('data-id'));

                $.ajax({
                    url: '<?= \Yii::$app->getUrlManager()->createUrl('site/deletefav') ?>',
                    type: 'POST',
                    data: {id: $(this).attr('data-id'), type: $(this).attr('data-type'), _csrf: csrfToken},
                    success: function (data) {
                        if (data == 0) {
                            alert('Please login');
                        } else {

                            $('#favlistmain').load(' #favlist');
                        }
                    },
                    error: function (data) {
                        //    console.log(data.responseText);
                        alert(data.responseText);
                    }
                });
            }
        });

        $(document).on("click", ".delete2", function (e) {
            e.preventDefault();
            var confirm_var = confirm('Are you sure you want to remove this from your shortlist?');
            if (confirm_var == true) {
                // alert($(this).attr('data-id'));

                $.ajax({
                    url: '<?= \Yii::$app->getUrlManager()->createUrl('site/deletefav') ?>',
                    type: 'POST',
                    data: {id: $(this).attr('data-id'), type: $(this).attr('data-type'), _csrf: csrfToken},
                    success: function (data) {
                        if (data == 0) {
                            alert('Please login');
                        } else {

                            $('#favlistmain').load(' #favlist');
                        }
                    },
                    error: function (data) {
                        //    console.log(data.responseText);
                        alert(data.responseText);
                    }
                });
            }
        });

        $(document).on('click', ".confirmProperty", function (e) {
            e.preventDefault();
            // startLoader();
            $.ajax({
                url: '<?= \Yii::$app->getUrlManager()->createUrl('site/confirmbook') ?>',
                type: 'POST',
                data: {id: $(this).attr('data-id'), type: $(this).attr('data-type'), _csrf: csrfToken},
                success: function (data) {
                    //hideLoader();
                    if (data == 0) {
                        alert('Please login to Confirm');
                    } else {

                        $('#favlistmain').load(' #favlist');

                    }
                    alert('Property confirmed');
                },
                error: function (data) {
                    alert(data.responseText);
                }
            });

        });

        $(document).on('click', '.ScheduleProperty', function (e) {
            $('#myModal #schedule_id').val($(this).attr('data-id'));
            $('#myModal #property_status_f').val($(this).attr('data-type'));
            if ($(this).attr('data-type') == 3) {
                $('#myModal #visitsubmit').text('Schedule');
            }
            if ($(this).attr('alt') == 'reSchedule') {
                $('#visits #visit_date').val($(this).attr('data-visitdate'));
                $('#visits #propertyvisits-visit_time').val($(this).attr('data-visittime'));
            }

        })

        $(document).on('click', '.ScheduleProperty3', function (e) {
            $('#visit_date').val('');
            $('#propertyvisits-visit_time').val('');
            $('#myModal #schedule_id').val($(this).attr('data-id'));
            $('#myModal #property_status_f').val($(this).attr('data-type'));
            if ($(this).attr('data-type') == 3) {
                $('#myModal #visitsubmit').text('Schedule');
            }
        })

        window.clickedEleId = 0;

        $(document).on('click', '.ScheduleProperty2', function (e) {
            window.clickedEleId = $(this).attr('data-id');
            $('#myModal2 #schedule_id2').val($(this).attr('data-id'));
            $('#myModal2 #property_status_f2').val($(this).attr('data-type'));
            if ($(this).attr('data-type') == 3) {
                $('#myModal2 #visitsubmit2').text('Schedule');
            }
            if ($(this).attr('alt') == 'reSchedule') {
                $('#visits2 #visit_date2').val($(this).attr('data-visitdate'));
                $('#visits2 #propertyvisits-visit_time2').val($(this).attr('data-visittime'));
            }

        })

    });

    $(document).on('click', '.BookProperty', function (e) {
        return;
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        var this_e = $(this);
        var id = this_e.attr('data-id');
        e.preventDefault();
        $.ajax({
            url: '<?= \Yii::$app->getUrlManager()->createUrl('site/propertydetails') ?>',
            type: 'POST',
            data: {id: $(this).attr('data-id'), type: $(this).attr('data-type'), _csrf: csrfToken},
            success: function (data) {
                var data = $.parseJSON(data);
                $('#property_type').val(data.type);
                $('#property_id').val(data.child_properties);
                $('#parent_property_id').val(data.property_id);
                $('#rent').val(data.rent);
                $('#deposit').val(data.deposit);
                $('#token_amount').val(data.token_amount);
                $('#bookingForm').submit();
            }
        });
    })




    /*$(document).on('click','.clickable-row',function(){
     location.href=$(this).attr('data-href');
     // console.log($(this).attr('data_href'));
     })*/

</script> 
<style>
    #content {
        width: 80%;   
    }
    @media (min-width: 568px) and (max-width: 767.98px) { 
        #mdl-dialog{
            margin-top: 0px;
        }
    }
    @media screen and (max-width: 767px){
        #content{
            width: 100%;
        }
        .crd-style{
            color: white;
            border-bottom: 1px solid white;
        }
        .action-img{
            float: left;
            margin-left: 15px;
            margin-bottom: 15px;
        }
        .cust-modaldialog{
            margin-top: -60%;
            margin-left: 10px !important;
        }
        #visitsubmit{
            width: 35% !important;
        }
        .cust-cls{
            width: 35% !important;
        }
    }
    @media screen and (max-width: 320px){
        #newfnt h4{
            font-size: 12px !important;
        }
        #visitsubmit{
            width: 40% !important;
        }
        .cust-cls{
            width: 40% !important;
        }
    }
    @media screen and (max-width: 375px){
        .cust-favourite h4 {
            font-size: 14px !important;
        }
    }
    .feed-details-tbl tr th
    {			

        font-size: 12px;
        font-weight: 600;
    }
    .feed-details-tbl tr td
    {
        font-size: 12px;

    }
    div#favlistmain {
        margin-left: 55px;

    }
    th.textcenter {
        font-weight: 600;
        font-size: 14px;
    }
    td.textaling {
        font-size: 15px;
        line-height: 2.5 !important;
        /* margin: 106px; */
        /* padding-top: 59px; */
    }
    .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
        padding: 8px;
        line-height: 1.42857143;
        vertical-align: top;
        border-top: 1px solid #fff !important;
    }
    .tr-style{
        background: red;
    }
    .img_style{
        width: 85px !important;
        height: 48px !important;
    }

    table {
        margin: 15px 0;
        border: 1px solid black;
        table-layout: auto;
        width: 100%; /* must have this set */
    }

    .sturdy td:nth-child(1),
    .sturdy td:nth-child(3) {
        /* width: 30%;  */
        word-wrap:break-word;
        line-height: 1.5;
    }
    .sturdy td:nth-child(2) {
        /* width: 50%; */
        word-wrap:break-word;
        line-height: 1.5;
    } 
    .th_width {
        width: 180px;
    }

</style>