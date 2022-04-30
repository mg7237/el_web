<?php
/* @var $this yii\web\View */

$this->title = 'PMS - Tenant Details';

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\PropertyTypes;
use app\models\PropertyAttributeMap;
use app\models\PropertiesAddress;

/* echo  "<pre>";
  print_r($model );
  echo  "</pre>"; */
?>
<div id="page-wrapper">
    <div class="row">
        <h3>tenant details</h3>
    </div>
    <p>&nbsp;</p>
    <div class="row">
        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 col-xs-offset-2">
            <div class="tenentdetaillist">
                <ul>
                    <li>Tenant name</li>
                    <li><?= Yii::$app->userdata->getFullNameById($myAgreements->tenant_id); ?></li>
                </ul>
            </div>
            <div class="tenentdetaillistnew">
                <h1>Contact details</h1>
                <ul>
                    <li>Email</li>
                    <li><?= Yii::$app->userdata->getUserEmailById($myAgreements->tenant_id); ?></li>
                    <li>Phone number</li>
                    <li><?= Yii::$app->userdata->getPhoneNumber($myAgreements->tenant_id); ?></li>
                </ul>

            </div>
            <div class="tenentdetaillistnew tenentdetailComplete">
                <h1>Permanent address of tenant </h1>
                <ul>
                    <li>
                        # <?= Yii::$app->userdata->getCompleteAddressById($myAgreements->tenant_id); ?>
                    </li>

                </ul>

            </div>
            <div class="tenentdetaillistnew">
                <h1>Id proof of tenant</h1>

                <!--
                                        <li>PAN number</li>
                                    <li>5456523121</li>
                -->
                <div class="row">
                    <div class="uploadbox">
                        <?php
                        $identity = Yii::$app->userdata->getAllUserProofs($myAgreements->tenant_id, 'identity');
                        if ($identity) {
                            foreach ($identity as $key => $value) {
                                ?>
                                <div class="col-md-6">

                                    <img width=100 src="../<?= $value->proof_document_url; ?>" alt="pancard" class="preview">

                                    <p><?= Yii::$app->userdata->getProofType($value->proof_type); ?></p>
                                </div>

                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>  
                </ul>
            </div>
            <div class="tenentdetaillistnew">
                <h1>Employment details</h1>
                <ul>
                    <li>Employer name</li>
                    <li><?= ($tenantProfile) ? $tenantProfile->employer_name : ""; ?></li>
                    <li>Employee id</li>
                    <li><?= ($tenantProfile) ? $tenantProfile->employee_id : ""; ?></li>
                    <li>Employee email</li>
                    <li><?= ($tenantProfile) ? $tenantProfile->employment_email : ""; ?></li>
                    <li>employment start date</li>
                    <li><?= ($tenantProfile) ? $tenantProfile->employment_start_date : ""; ?></li>
                </ul>
            </div>
            <div class="tenentdetaillistnew">
                <h1>Lease Details</h1>
                <ul>
                    <li>Monthly rent</li>
                    <li>Rs. <?= $myAgreements->rent ?></li>
                    <li>Maintenance</li>
                    <li>Rs. <?= $myAgreements->maintainance ?></li>
                    <li>Deposit</li>
                    <li>Rs. <?= $myAgreements->deposit ?></li>
                </ul>
                <ul>
                    <li>Lease start date</li>
                    <li><?= date('d-M-Y', strtotime($myAgreements->lease_start_date)); ?></li>
                    <li>Lease end date</li>
                    <li><?= date('d-M-Y', strtotime($myAgreements->lease_end_date)); ?></li>
                </ul>

            </div>
        </div>

        <!--        <div class="col-xs-2 col-md-2 col-lg-2 col-sm-2" style="padding:10px;">
                    <a href="#" class="thumbnail">
                        <img src="<?= !empty(Yii::$app->userdata->getImageById($myAgreements->tenant_id)) ? "../" . Yii::$app->userdata->getImageById($myAgreements->tenant_id) : Url::home(true) . 'images/photo.jpg' ?>" alt="...">
                    </a>
                </div>-->
        <!-- /.col-lg-12 -->
    </div>
</div>

