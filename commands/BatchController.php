<?php

namespace app\commands;

set_time_limit(0);
ini_set('max_execution_time', '0');

use Yii;
use app\components\Userdata;
use yii\console\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;
use app\components\Pdfcrowd;

class BatchController extends Controller {

    public $layout = false;
    const DUE_INTERVAL = 5;

    public function behaviors() {
        return [];
    }

    public function actionCalculateownerpayments() {
        /*
         * BELOW CODE FOR SIMPLE AGREEMENT TYPE
         */

        $gstValue = \app\models\SystemConfig::find()->where(['name' => 'GST'])->one()->value;
        $tdsNriWitPanValue = \app\models\SystemConfig::find()->where(['name' => 'TDS-NRI-WITH-PAN'])->one()->value;
        $tdsNriWitOtPanValue = \app\models\SystemConfig::find()->where(['name' => 'TDS-NRI-WITHOUT-PAN'])->one()->value;
        $tdsIndianValue = \app\models\SystemConfig::find()->where(['name' => 'TDS-INDIAN'])->one()->value;
        $tdsThreshold = \app\models\SystemConfig::find()->where(['name' => 'TDS-ABOVE-THRESHOLD'])->one()->value;
        $tdsValues = [];
        $tdsValues['tdsNriWitPanValue'] = $tdsNriWitPanValue;
        $tdsValues['tdsNriWitOtPanValue'] = $tdsNriWitOtPanValue;
        $tdsValues['tdsIndianValue'] = $tdsIndianValue;
        $tdsValues['tdsThreshold'] = $tdsThreshold;
        // Assumption that this method will be executed on 1st of every month, calculating data for previous month
        $month = date('m',strtotime('first day of previous month'));
        $year = date('Y',strtotime('first day of previous month'));
        $firstDayOfMonth = Date("Y-m-d",  strtotime('first day of previous month')); 
        $lastDayOfMonth = Date("Y-m-d",strtotime("last day of last month"));
        
        echo "Month: " . $month . ">Year:" . $year . ">First:" . $firstDayOfMonth . ">Last:" . $lastDayOfMonth;
        
        $recordSet = Yii::$app->db->createCommand(''
                        . 'SELECT '
                        . 'tp.id, tp.tenant_id, tp.parent_id, tp.original_amount, tp.maintenance, tp.payment_des, '
                        . 'tp.total_amount, tp.due_date, tp.days_of_rent, tp.month,  '
                        . 'pa.property_id, pa.owner_id, pa.pms_commission, pa.agreement_type  FROM tenant_payments tp '
                        . 'INNER JOIN property_agreements pa ON tp.parent_id = pa.property_id '
                        . 'WHERE tp.due_date <= "' . $lastDayOfMonth . '" ' 
                        . 'AND tp.total_amount != 0 '
                        . 'AND tp.processed = 0 '
                        . 'AND pa.agreement_type = 1 '
                        . 'AND tp.payment_type = 2')->queryAll();

        echo             'SELECT '
                        . 'tp.id, tp.tenant_id, tp.parent_id, tp.original_amount, tp.maintenance, tp.payment_des, '
                        . 'tp.total_amount, tp.due_date, tp.days_of_rent, tp.month,  '
                        . 'pa.property_id, pa.owner_id, pa.pms_commission, pa.agreement_type  FROM tenant_payments tp '
                        . 'INNER JOIN property_agreements pa ON tp.parent_id = pa.property_id '
                        . 'WHERE tp.due_date <= "' . $lastDayOfMonth . '" ' 
                        . 'AND tp.total_amount != 0 '
                        . 'AND tp.processed = 0 '
                        . 'AND pa.agreement_type = 1 '
                        . 'AND tp.payment_type = 2';
        
        
        //echo '<br /><h2>(#'.count($recordSet).')Fetching records for simple leasing.</h2><p style="color: green;">'; print_r($recordSet); echo '</p><br /><br />';
        echo PHP_EOL;
        echo '(#' . count($recordSet) . ')Fetching records for simple leasing. ' . PHP_EOL;
        //print_r($recordSet);
        echo PHP_EOL;
        
        $transaction = Yii::$app->db->beginTransaction();

        try {
            if (count($recordSet) != 0) {
                foreach ($recordSet as $key => $value) {
                    $tds = 0;
                    $ownerPayments = new \app\models\OwnerPayments;
                    $rent = $value['original_amount'];
                    $pmsfees = (((double) $rent * (double) $value['pms_commission']) / 100);
                    $gst = ($gstValue * $pmsfees) / 100;
                    $tds = $this->calculateTDSForRent($rent, $value['owner_id'], $tdsValues);

                    $netRentPayable = (double) ($rent + $value['maintenance']) - ($pmsfees + $gst + $tds);
                    $ownerPayments->owner_id = $value['owner_id'];
                    $ownerPayments->property_id = $value['property_id'];
                    $ownerPayments->parent_id = $value['parent_id'];
                    $ownerPayments->total_rent = (double) ($rent + $value['maintenance']);
                    $ownerPayments->net_amount = $netRentPayable;
                    $ownerPayments->pms_commission = $pmsfees;
                    $ownerPayments->tds = $tds;
                    $ownerPayments->gst = $gst;
                    $ownerPayments->coverage_days = $value['days_of_rent'];
                    $ownerPayments->payment_id = $value['id'];
                    $ownerPayments->month = $month;
                    $ownerPayments->year = $year;

                    //echo '<br /><h2>('.count($recordSet).'#'.$key.') Saving data for simple leasing</h2><p style="color: green;">'; print_r($ownerPayments); echo '</p><br /><br />';
                    echo PHP_EOL;
                    echo '(' . count($recordSet) . '#' . $key . ') Saving data for simple leasing ' . PHP_EOL;
                    print_r($ownerPayments);
                    echo PHP_EOL;

                    if (!$ownerPayments->save(false)) {
                        throw new \Exception('Exception');
                    }

                    $tenantPayment = \app\models\TenantPayments::find()->where(['id' => $value['id']])->one();
                    $tenantPayment->processed = '1';

                    if (!$tenantPayment->save(false)) {
                        throw new \Exception('Exception');
                    }
                }
            }

            $recordSet = Yii::$app->db->createCommand(''
                            . 'SELECT * FROM property_agreements '
                            . 'WHERE contract_end_date >= "' . $firstDayOfMonth . '" '         
                            . 'AND rent_start_date <= "' . $lastDayOfMonth . '" '
                            . 'AND agreement_type = 2 ')->queryAll();

            echo PHP_EOL;
            echo '(#' . count($recordSet) . ')Fetching records for guaranteed leasing. ' . PHP_EOL;
            print_r($recordSet);
            echo PHP_EOL;
            if (count($recordSet) != 0) {
                foreach ($recordSet as $key => $value) {
                    $coverageDays = 0;
                    $coverageDays1 = 0;
                    $coverageDays2 = 0;
                    $coverageDays3 = 0;
                    $minDate = '';
                    $maxDate = '';
                    if (strtotime($value['rent_start_date']) < strtotime($firstDayOfMonth)) {

                        /*  Calculating Max date */
                        $date1 = date_create($value['contract_end_date']);
                        $date2 = date_create($lastDayOfMonth);
                        $dateDiff = date_diff($date1, $date2);

                        if ($dateDiff->invert == 1) {
                            $maxDate = $date2;
                        } else {
                            $maxDate = $date1;
                        }

                        $coverageDays1 = (date_diff(date_create($maxDate->format('Y-m-d')), date_create($firstDayOfMonth))->days) + 1;
                    
                        $recordSet2 = Yii::$app->db->createCommand(''
                                        . 'SELECT * FROM owner_payments '
                                        . 'WHERE owner_id = ' . $value['owner_id'] . ' '
                                        . 'AND property_id = ' . $value['property_id'] . ' ')->queryAll();
                        
                        if (count($recordSet2) == 0) {
                            $coverageDays2 = (date_diff(date_create(date("Y-m-d", strtotime($firstDayOfMonth. " -1 day"))), date_create(date('Y-m-d', strtotime($value['rent_start_date']))))->days) + 1;
                        }
                        
                    } elseif (strtotime($value['rent_start_date']) >= strtotime($firstDayOfMonth) && strtotime($value['rent_start_date']) <= strtotime($lastDayOfMonth)) {


                            /*  Calculating Max date */
                            $date1 = date_create($value['contract_end_date']);
                            $date2 = date_create(date("Y-m-d", strtotime($lastDayOfMonth)));
                            $dateDiff = date_diff($date1, $date2);

                            if ($dateDiff->invert == 1) {
                                $maxDate = $date2;
                            } else {
                                $maxDate = $date1;
                            }

                            $coverageDays3 = (date_diff(date_create($maxDate->format('Y-m-d')), date_create($value['rent_start_date']))->days) + 1;

                        }

                    
                    $coverageDays = $coverageDays1 + $coverageDays2 + $coverageDays3;

                    $tds = 0;
                    $tds01 = 0;
                    $tdsThisMonth = 0;
                    $ownerPayments = new \app\models\OwnerPayments;

                    $numberOfDaysInMon = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($firstDayOfMonth)), date('Y', strtotime($firstDayOfMonth)));

                    $rent = $value['rent'] / $numberOfDaysInMon * $coverageDays;
                    $maintenance = $value['manteinance'] / $numberOfDaysInMon * $coverageDays;
                    $pmsfees = 0; // Not applicable for guarenteed leased
                    $gst =0;    // Not applicable for guarenteed leased
                    $tdsThisMonth = $this->calculateTDSForRent($value['rent'], $value['owner_id'], $tdsValues);
                    $tds01 = $tdsThisMonth  / $numberOfDaysInMon * $coverageDays;
                    $netRentPayable = (double) ($rent + $value['manteinance']) - ($pmsfees + $gst + $tds01);
                    $ownerPayments->owner_id = $value['owner_id'];
                    $ownerPayments->property_id = $value['property_id'];
                    $ownerPayments->parent_id = $value['property_id'];
                    $ownerPayments->total_rent = (double) $rent + $maintenance;
                    $ownerPayments->net_amount = (double) $netRentPayable;
                    $ownerPayments->pms_commission = $pmsfees;
                    $ownerPayments->tds = $tds01;
                    $ownerPayments->gst = $gst;
                    $ownerPayments->coverage_days = $coverageDays;
                    $ownerPayments->payment_id = $value['id'];
                    $ownerPayments->month = $month;
                    $ownerPayments->year = $year;

                    echo PHP_EOL;
                    echo '(' . count($recordSet) . '#' . $key . ') Saving data for guaranteed leasing ' . PHP_EOL;
                    print_r($ownerPayments);
                    echo PHP_EOL;

                    if (!$ownerPayments->save(false)) {
                        throw new \Exception('Exception');
                    }
                }
            }

            
            $sql = 'SELECT `owner_id`, `property_id`, `year`, `month`, Round(SUM(total_rent)) AS total_rent, '
                    . 'Round(SUM(net_amount)) AS net_amount, Round(SUM(pms_commission)) AS pms_commission, Round(SUM(tds)) AS tds, '
                    . 'Round(SUM(gst)) AS gst, Round(SUM(`coverage_days`)) AS coverage_days '
                    . 'FROM owner_payments Where year = ' . date("Y", strtotime($firstDayOfMonth)) . ' AND month = ' . date("m", strtotime($firstDayOfMonth)) . '  GROUP BY owner_id, property_id,year,month ;';
            echo $sql;
            $recordSet = Yii::$app->db->createCommand($sql)->queryAll();

            echo PHP_EOL;
            echo '(#' . count($recordSet) . ')Fetching records for calculating owner payments, and pushing to owner_payment_summary. ' . PHP_EOL;
            print_r($recordSet);
            echo PHP_EOL;
            $emailDataSets = [];
            if (count($recordSet) > 0) {
                foreach ($recordSet as $key => $value) {
                    if ($value['net_amount'] > 0) {

                        $furniture_sql = 'SELECT furniture_rent from property_agreements where property_id = ' . $value['property_id'];
                        $furniture_recordSet = Yii::$app->db->createCommand($furniture_sql)->queryOne();

                        $ownerPaymentSummary = new \app\models\OwnerPaymentSummary;
                        $ownerPaymentSummary->owner_id = $value['owner_id'];
                        $ownerPaymentSummary->property_id = $value['property_id'];
                        $ownerPaymentSummary->payment_year = $value['year'];
                        $ownerPaymentSummary->payment_month = $value['month'];
                        $ownerPaymentSummary->furniture_rental = (double) $furniture_recordSet['furniture_rent'];
                        $ownerPaymentSummary->total_rent = $value['total_rent'];
                        $ownerPaymentSummary->net_amount = $value['net_amount'];
                        $ownerPaymentSummary->pms_commission = $value['pms_commission'];
                        $ownerPaymentSummary->tds = $value['tds'];
                        $ownerPaymentSummary->gst = $value['gst'];
                        $ownerPaymentSummary->coverage_days = $value['coverage_days'];

                        // Adding below temporarily till we have automated mechanism to update payment data
                        $ownerPaymentSummary->payment_mode = 1;
                        $ownerPaymentSummary->payment_status = 1;
                        $ownerPaymentSummary->payment_date = date('Y-m-d');
                        // End of temp data assignment //


                        echo PHP_EOL;
                        echo '(' . count($recordSet) . '#' . $key . ') Saving data to owner_payment_summary ' . PHP_EOL;
                        print_r($ownerPaymentSummary);
                        echo PHP_EOL;
                        if (!$ownerPaymentSummary->save(false)) {
                            throw new \Exception('Exception');
                        }
                    }

                    $emailDataSets[$key]['owner_id'] = $value['owner_id'];
                    $emailDataSets[$key]['property_id'] = $value['property_id'];
                    $emailDataSets[$key]['year'] = $value['year'];
                    $emailDataSets[$key]['month'] = $value['month'];
                    $emailDataSets[$key]['total_rent'] = $value['total_rent'];
                    $emailDataSets[$key]['net_amount'] = $value['net_amount'];
                    $emailDataSets[$key]['pms_commission'] = $value['pms_commission'];
                    $emailDataSets[$key]['tds'] = $value['tds'];
                    $emailDataSets[$key]['gst'] = $value['gst'];
                    $emailDataSets[$key]['coverage_days'] = $value['coverage_days'];
                }
            }

            //echo '<br /><h2>Transaction Committed.</h2><p style="color: green;">'; echo '</p><br /><br />';
            echo PHP_EOL;
            //echo 'Transaction Committed.';
            echo PHP_EOL;
            echo PHP_EOL;
            echo 'Creating report to bank for payment instruction.';
            echo PHP_EOL;
            $this->createPaymentInstruction();

            if (count($emailDataSets) > 0) {
                foreach ($emailDataSets as $emailDataSet) {
                    /* For template 1 */
                    $recordSet = Yii::$app->db->createCommand(''
                                    . 'SELECT '
                                    . 'u.full_name as owner_name, op.owner_id, op.address_line_1, op.address_line_2, op.city_name, '
                                    . 'op.pincode, op.state '
                                    . 'FROM owner_profile op '
                                    . 'INNER JOIN users u ON u.id = op.owner_id '
                                    . 'WHERE op.owner_id = "' . $emailDataSet['owner_id'] . '" ')->queryOne();

                    $recordSet['property_id'] = $emailDataSet['property_id'];
                    $recordSet['pms_commission'] = $emailDataSet['pms_commission'];
                    $recordSet['tds'] = $emailDataSet['tds'];
                    $recordSet['gst'] = $emailDataSet['gst'];
                    $recordSet['total_rent'] = $emailDataSet['total_rent'];
                    $recordSet['net_amount'] = $emailDataSet['net_amount'];
                    $recordSet['coverage_days'] = $emailDataSet['coverage_days'];
                    $recordSet['year'] = $emailDataSet['year'];
                    $recordSet['month'] = $emailDataSet['month'];
                    echo PHP_EOL;
                    echo 'Creating PDF1. - ' . $emailDataSet['property_id'];
                    echo PHP_EOL;
                    $pdf1 = $this->createPdf($recordSet, 1, 'owner-tax-invoice-pdf');

                    $subject = "Payment Statement & Invoice: " . $emailDataSet['property_id'];

                    /* For template 2 */
                    echo PHP_EOL;
                    echo 'Creating PDF2.- ' . $emailDataSet['property_id'];
                    echo PHP_EOL;
                    $pdf2 = $this->createPdf($recordSet, 2, 'owner-payment-statement-pdf');
                    // Start work from here. By collecting email ids and file name.
                    $to = strtolower(Yii::$app->userdata->getEmailById($recordSet['owner_id']));
                    $msg = ''
                            . 'Hello ' . $recordSet['owner_name'] . ' <br /><br />'
                            . 'Greetings from Easyleases!!! <br /><br />'
                            . 'Please find attached Payment Statement and Tax Invoice for PMS services for your property '
                            . ' <b>' . Yii::$app->userdata->getPropertyNameById($recordSet['property_id']) . '</b> '
                            . 'for the month of <b>' . date('M', strtotime($recordSet['year'] . '-' . $recordSet['month'])) . '-' . $recordSet['year'] . '</b> <br /><br />'
                            . 'For any queries, please reachout to support@easyleases.in <br /><br />'
                            . 'Thanks, <br />'
                            . '<b>Easyleases Team</b> ';
                    Yii::$app->userdata->doMailWithAttachment($to, $subject, $msg, $pdf1 . ',' . $pdf2);
                    $ownerPhone = Yii::$app->userdata->getPhoneNumberById($recordSet['owner_id'], 4);
                    if (!empty($ownerPhone)) {
                        $txtMess = ''
                                . 'Your Net Rent for month of '
                                . '' . date('M', strtotime($recordSet['year'] . '-' . $recordSet['month'])) . '-' . $recordSet['year'] . ' '
                                . 'towards your property ' . Yii::$app->userdata->getPropertyNameById($recordSet['property_id']) . ' '
                                . 'is Rs ' . $emailDataSet['net_amount'] . '';
                        echo 'Text Sent To ' . $ownerPhone . ' with message ' . $txtMess;
                        if (Yii::$app->userdata->sendSms([$ownerPhone], $txtMess)) {
                            echo PHP_EOL;
                            echo 'Text Sent To ' . $ownerPhone . ' with message ' . $txtMess;
                            echo PHP_EOL;
                        }
                    }
                }
            }
            $transaction->commit();
            echo 'Transaction Committed.';
            
            echo PHP_EOL;
            
            echo 'Processing Done.';
            
            echo PHP_EOL;
        } catch (\Exception $e) {
            $transaction->rollBack();
            $errorMsg = 'Error on line ' . $e->getLine() . ' in ' . $e->getFile() . ': <b>' . $e->getMessage() . '</b>';
            echo $errorMsg;
        }
    }

    private function getPdfTemplate1($data) {
        $ownerId = $data['owner_id'];
        $propertyId = $data['property_id'];
        $propertyName = Yii::$app->userdata->getPropertyNameById($data['property_id']);
        $ownerName = $data['owner_name'];
        $addressLine1 = $data['address_line_1'];
        $addressLine2 = $data['address_line_2'];
        //$city = Yii::$app->userdata->getCityName($data['city']);
        $city = ($data['city_name']);
        $state = Yii::$app->userdata->getStateNameByStateCode($data['state']);
        $pincode = $data['pincode'];
        $invoiceNumber = $ownerId . '-' . $propertyId . '-' . date('dmYHis');
        $pmsFee = $data['pms_commission'];
        $gst = $data['gst'];
        $cgst = $gst / 2;
        $sgst = $gst / 2;

        $html = "";

        $html = ' '
                . '<!DOCTYPE html>'
                . '<html>'
                . '<head>'
                . '<style>'
                . '#table2 {border-collapse: collapse; width: 100%; padding: 0px;}'
                . '#table2, #table2 th, #table2 td {border: 1px solid black;}'
                . '#table2 th, #table2 td {padding: 10px}'
                . '</style>'
                . '</head>'
                . '<body>'
                . '<center><b style="font-size: 20px;"><u>Tax Invoice</u></b></center>'
                . '<br/ ><br/ >'
                . '<table width="100%">'
                . '<tr>'
                . '<td width="100%">'
                . '<table width="100%">'
                . '<tr width="100%">'
                . '<td align="left">'
                . '<b>Customer Details:</b>'
                . '</td>'
                . '<td align="right">'
                . 'Date: ' . date('d-m-Y') . ''
                . '</td>'
                . '</tr>'
                . '</table>'
                . '</td>'
                . '</tr>'
                . '<tr><td>' . $ownerName . '</td></tr>'
                . '<tr><td>' . $addressLine1 . '</td></tr>'
                . '<tr><td>' . $addressLine2 . '</td></tr>'
                . '<tr><td>' . $city . '</td></tr>'
                . '<tr><td>' . $state . '</td></tr>'
                . '<tr><td>' . $pincode . '</td></tr>'
                . '</table>'
                . '<br />'
                . '<br />'
                . '<b>Invoice Number: ' . $invoiceNumber . '</b><br />'
                . '<br />'
                . '<br />'
                . '<table id="table2">'
                . '<tr>'
                . '<td><b>#</b></td>'
                . '<td><b>Description of Services</b></td>'
                . '<td><b>Amount</b></td>'
                . '</tr>'
                . '<tr>'
                . '<td>1</td>'
                . '<td>Property Management Services for "' . $propertyName . '" &nbsp;&nbsp; </td>'
                . '<td>Rs ' . Yii::$app->userdata->getFormattedMoney(round($pmsFee, 2)) . '</td>'
                . '</tr>'
                . '<tr>'
                . '<td>2</td>'
                . '<td>CGST</td>'
                . '<td>Rs ' . Yii::$app->userdata->getFormattedMoney(round($cgst, 2)) . '</td>'
                . '</tr>'
                . '<tr>'
                . '<td>3</td>'
                . '<td>SGST</td>'
                . '<td>Rs ' . Yii::$app->userdata->getFormattedMoney(round($sgst, 2)) . '</td>'
                . '</tr>'
                . '<tr>'
                . '<td>&nbsp;</td>'
                . '<td><b>Total</b></td>'
                . '<td><b>Rs ' . Yii::$app->userdata->getFormattedMoney(round(($gst + $pmsFee), 2)) . '</b></td>'
                . '</tr>'
                . '</table>'
                . '</body>'
                . '</html>'
        ;
        return $html;
    }

    private function getPdfTemplate2($data) {

        $ownerId = $data['owner_id'];
        $propertyId = $data['property_id'];
        $propertyName = Yii::$app->userdata->getPropertyNameById($data['property_id']);
        $ownerName = $data['owner_name'];
        $addressLine1 = $data['address_line_1'];
        $addressLine2 = $data['address_line_2'];
        //$city = Yii::$app->userdata->getCityName($data['city']);
        $city = ($data['city_name']);
        $state = Yii::$app->userdata->getStateNameByStateCode($data['state']);
        $pincode = $data['pincode'];
        $invoiceNumber = $ownerId . '-' . $propertyId . '-' . date('dmYHis');
        $pmsFee = $data['pms_commission'];
        $gst = $data['gst'];
        $cgst = $gst / 2;
        $sgst = $gst / 2;
        $coverageDays = $data['coverage_days'];
        $totalRent = $data['total_rent'];
        $furnitureRent = Yii::$app->userdata->getPmsCommissionPercentage($data['owner_id'], $data['property_id'])->furniture_rent;
        $pmsFeePercen = Yii::$app->userdata->getPmsCommissionPercentage($data['owner_id'], $data['property_id'])->pms_commission;
        $tds = $data['tds'];
        $netPayDue = $data['net_amount'];
        $paymentMonth = date('M', strtotime($data['year'] . '-' . $data['month']));
        $paymentYear = $data['year'];

        $html = "Date: " . date('d-M-Y') . "<br/>";

        $html = ' '
                . '<!DOCTYPE html>'
                . '<html>'
                . '<head>'
                . '<style>'
                . '#table2 {border-collapse: collapse; width: 100%; padding: 0px;}'
                . '#table2, #table2 th, #table2 td {border: 1px solid black;}'
                . '#table2 th, #table2 td {padding: 10px}'
                . '</style>'
                . '</head>'
                . '<body>'
                . '<center><b style="font-size: 20px;">' . $propertyName . ' - Payment statement for the month of ' . $paymentMonth . '-' . $paymentYear . '</b></center>'
                . '<br /><br />'
                . '<table width="100%">'
                . '<tr>'
                . '<td width="100%">'
                . '<table width="100%">'
                . '<tr width="100%">'
                . '<td align="left">'
                . '<b>To:</b>'
                . '</td>'
                . '<td align="right">'
                . 'Date: ' . date('d-m-Y') . ''
                . '</td>'
                . '</tr>'
                . '</table>'
                . '</td>'
                . '</tr>'
                . '<tr><td>' . $ownerName . '</td></tr>'
                . '<tr><td>' . $addressLine1 . '</td></tr>'
                . '<tr><td>' . $addressLine2 . '</td></tr>'
                . '<tr><td>' . $city . '</td></tr>'
                . '<tr><td>' . $state . '</td></tr>'
                . '<tr><td>' . $pincode . '</td></tr>'
                . '</table>'
                . '<br />'
                . '<br />'
                . '<table id="table2">'
                . '<tr>'
                . '<th>'
                . '#'
                . '</th>'
                . '<th>'
                . 'Description'
                . '</th>'
                . '<th>'
                . 'Value'
                . '</th>'
                . '</tr>'
                . '<tr>'
                . '<td>'
                . '1'
                . '</td>'
                . '<td>'
                . 'Rent Coverage Days '
                . '</td>'
                . '<td>'
                . '' . $coverageDays . ''
                . '</td>'
                . '<tr>'
                . '<td>'
                . '2'
                . '</td>'
                . '<td>'
                . 'Total Rent'
                . '</td>'
                . '<td>'
                . 'Rs ' . Yii::$app->userdata->getFormattedMoney(round($totalRent, 2)) . ' '
                . '</td>'
                . '</tr>'
                . '<tr>'
                . '<td>'
                . '3'
                . '</td>'
                . '<td>'
                . 'PMS Fee @ ' . $pmsFeePercen . '%'
                . '</td>'
                . '<td>'
                . 'Rs ' . Yii::$app->userdata->getFormattedMoney(round($pmsFee, 2)) . ' '
                . '</td>'
                . '</tr>'
                . '<tr>'
                . '<td>'
                . '4'
                . '</td>'
                . '<td>'
                . 'Furniture Rent'
                . '</td>'
                . '<td>'
                . 'Rs ' . Yii::$app->userdata->getFormattedMoney(round($furnitureRent, 2)) . ' '
                . '</td>'
                . '</tr>'
                . '<tr>'
                . '<td>'
                . '5'
                . '</td>'
                . '<td>'
                . 'TDS'
                . '</td>'
                . '<td>'
                . 'Rs ' . Yii::$app->userdata->getFormattedMoney(round($tds, 2)) . ' '
                . '</td>'
                . '</tr>'
                . '<tr>'
                . '<td>'
                . '6'
                . '</td>'
                . '<td>'
                . 'GST'
                . '</td>'
                . '<td>'
                . 'Rs ' . Yii::$app->userdata->getFormattedMoney(round($gst, 2)) . ' '
                . '</td>'
                . '</tr>'
                . '<tr>'
                . '<td>'
                . '&nbsp;'
                . '</td>'
                . '<td>'
                . '<b>Net Payment Due</b>'
                . '</td>'
                . '<td>'
                . '<b>Rs ' . Yii::$app->userdata->getFormattedMoney(round($netPayDue, 2)) . '</b> '
                . '</td>'
                . '</tr>'
                . '</table>'
                . '<br />'
                . '<br />'
                . 'The net amount due is being credited to your bank account (as per our records). Please expect credit within 3 to 4 business days.'
                . '</body>'
                . '</html>'
        ;
        return $html;
    }

    private function createPdf($data, $template = 1, $fileName = 'owner-payment-pdf') {
        if ($template == 1) {
            $html = $this->getPdfTemplate1($data);
        } else if ($template == 2) {
            $html = $this->getPdfTemplate2($data);
        }
        return $this->PDF($html, $fileName);
    }

    private function PDF($html, $fileName) {

        try {
            // create an API client instance
            $client = new Pdfcrowd("SAM_15YFA", "c1ff61bd70d577deec0db16940fa8edc");
            $client->usePrintMedia(true);
            $client->setPageWidth("8.5in");
            $client->setPageHeight("11in");
            $client->setVerticalMargin("2.0in");

            $header = '<br />'
                    . '<div style="text-align: right;"><img style="height: 45px; width: 185px;" src="http://www.easyleases.in/images/newlogo1.png" /></div>'
                    . '<center>'
                    . '<span><b>Easyleases Technologies Private Limited</b></span><br>'
                    . '<span>Registered Address: RG-708, Purva Riviera, Varthur Road, Bangalore - 560037</span><br>'
                    . '<span>CIN: U70109KA2017PTC100691</span><br />'
                    . '<span>GSTIN: 29AAECE5303R1Z7</span>'
                    . '</center> <br />'
                    . '<hr style="border-color: black;" /> <br />'
                    . '';
            $client->setHeaderHtml($header);

            $footer = '<br />'
                    . '<br />'
                    . '<br />'
                    . '<div style="text-align: center;">'
                    . '<span>Note: This is a computer-generated receipt and doesn’t require signature. For any queries, please</span> <br />'
                    . '<span>email your query to suppport@easyleases.in</span> <br />'
                    . '<br />'
                    . '<span style="color: green;">www.easyleases.in</span>';
            $client->setFooterHtml($footer);
            // convert a web page and store the generated PDF into a $pdf variable
            $pdf = $client->convertHtml($html);

            // Generate PDF
            $path = './output/owner-pay-pdf/';
            if (!is_dir($path)) {
                if (!mkdir($path, 0777, true)) {
                    throw new \Exception('Unable to create directory');
                }
            }

            $pdfName = $fileName . '-' . date('d-m-Y') . '-' . time() . '.pdf';
            $pdfFile = fopen($path . $pdfName, 'w');
            //file_put_contents($pdfFile, $pdf);
            fwrite($pdfFile, $pdf);
            fclose($pdfFile);
            return $path . $pdfName;
        } catch (PdfcrowdException $why) {
            echo "Pdfcrowd Error: " . $why;
        }
    }

    public function actionCreatecsv() {
        $this->createPaymentInstruction();
    }

    private function createPaymentInstruction() {
        $path = './output/pay-ins-bank/';
        if (!is_dir($path)) {
            if (!mkdir($path, 0777, true)) {
                throw new \Exception('Unable to create directory');
            }
        }
        $recordSet = Yii::$app->db->createCommand(''
                        . 'SELECT '
                        . 'op.account_holder_name, op.bank_name, op.bank_branchname, op.bank_ifcs, '
                        . 'op.bank_account_number, ops.net_amount '
                        . 'FROM owner_profile op '
                        . 'INNER JOIN owner_payment_summary ops ON op.owner_id = ops.owner_id '
                        . 'WHERE ops.payment_month = ' . date('m') . ' '
                        . 'AND ops.payment_year = ' . date('Y') . ' ')->queryAll();

        $csvName = 'payment-instructions-' . date('d-m-Y') . '-' . time() . '.csv';
        $csvFile = fopen($path . $csvName, 'w');
        $csvRowTitle = [];
        $csvRowTitle[] = 'Account Holder Name';
        $csvRowTitle[] = 'Bank Name';
        $csvRowTitle[] = 'Bank Branch Name';
        $csvRowTitle[] = 'Bank IFSC';
        $csvRowTitle[] = 'Bank Account Number';
        $csvRowTitle[] = 'Net Amount';
        $csvRowTitle[] = 'Remark';
        fputcsv($csvFile, $csvRowTitle);
        foreach ($recordSet as $key => $row) {
            $csvRowSet = [];
            $csvRowSet[] = $row['account_holder_name'];
            $csvRowSet[] = $row['bank_name'];
            $csvRowSet[] = $row['bank_branchname'];
            $csvRowSet[] = $row['bank_ifcs'];
            $csvRowSet[] = $row['bank_account_number'];
            $csvRowSet[] = round($row['net_amount'], 2);
            $csvRowSet[] = 'Rent Payment for Month of: ' . date('M', strtotime(date('d-m-Y'))) . '/' . date('Y', strtotime(date('d-m-Y')));
            fputcsv($csvFile, $csvRowSet);
            unset($csvRowSet);
        }
        fclose($csvFile);
    }

    private function calculateTDSForRent($rent, $ownerId, $tdsValues) {
        $tdsNriWitPanValue = $tdsValues['tdsNriWitPanValue'];
        $tdsNriWitOtPanValue = $tdsValues['tdsNriWitOtPanValue'];
        $tdsThreshold = $tdsValues['tdsThreshold'];
        $tdsIndianValue = $tdsValues['tdsIndianValue'];
        $ownerProfile = \app\models\OwnerProfile::find()->where(['owner_id' => $ownerId])->one();
        $ownerStateName = Yii::$app->userdata->getStateName($ownerProfile->state);
        $ownerPanCard = $ownerProfile->pan_number;
        $tds = 0;
        if (strtoupper($ownerStateName) == 'INTERNATIONAL') {
            if (!empty(trim($ownerPanCard))) {
                $tds = $tdsNriWitPanValue * $rent / 100;
            } else {
                $tds = $tdsNriWitOtPanValue * $rent / 100;
            }
        } else {
            if ($rent >= $tdsThreshold) {
                $tds = $tdsIndianValue * $rent / 100;
            }
        }
        return $tds;
    }

    public function actionPaymentGatewayPush() {

        // Step 1. Generate settlement file before sending to bill desk
        date_default_timezone_set('Asia/Calcutta');

        $beginningOfPreviousDay = date('Y-m-d H:i:s', strtotime('yesterday'));
        $endOfPreviousDay = date('Y-m-d H:i:s', strtotime('Today -1 second'));


        $pathSettlement = './output/billdesksettlement/';
        if (!is_dir($pathSettlement)) {
            if (!mkdir($pathSettlement, 0777, true)) {
                throw new \Exception('Unable to create directory');
            }
        }


        $csvSettlementName = 'EASYLTECH_Settlement_' . date('YmdHis') . '.txt';
        $csvSettlement = fopen($pathSettlement . $csvSettlementName, 'w');


        $SettlementSql = 'SELECT txn_reference_no,customer_id,amount,response_time from pgi_interface where auth_status = "0300" '
                . 'and response_time between "' . $beginningOfPreviousDay . '" AND "' . $endOfPreviousDay . '"';


        $recordSet = Yii::$app->db->createCommand($SettlementSql)->queryAll();
        echo PHP_EOL;
        echo '(#' . count($recordSet) . ')Fetching records for settlement  ' . PHP_EOL;
        print_r($recordSet);
        echo PHP_EOL;
        if (count($recordSet) > 0) {
            foreach ($recordSet as $key => $row) {

                $csvRowSet = [];
                $csvRowSet[] = $row['txn_reference_no'];
                $csvRowSet[] = $row['customer_id'];
                $csvRowSet[] = number_format($row['amount'], 2, '.', '');
                $csvRowSet[] = date('Ymd', strtotime($row['response_time']));
                fputcsv($csvSettlement, $csvRowSet);
                unset($csvRowSet);
            }
            fclose($csvSettlement);
        }


        if (filesize($pathSettlement . $csvSettlementName) == 0) {
            $csvSettlementName = 'NOFILE';
        }

        // Step 2. Generate refund file before sending to bill desk
        $pathRefund = './output/billdeskrefund/';
        if (!is_dir($pathRefund)) {
            if (!mkdir($pathRefund, 0777, true)) {
                throw new \Exception('Unable to create directory');
            }
        }

        $csvRefundName = 'EASYLTECH_Refund_' . date('YmdHis') . '.txt';
        $csvRefund = fopen($pathRefund . $csvRefundName, 'w');
        $RefundSql = 'SELECT wh.id, pgi.txn_reference_no, pgi.customer_id, pgi.amount, pgi.response_time from pgi_interface as pgi INNER JOIN wallets_history as wh '
                . 'on wh.pgi_reference = pgi.id where wh.processed = 0 and wh.transaction_type = 2';

        $recordSet = Yii::$app->db->createCommand($RefundSql)->queryAll();

        echo PHP_EOL;
        echo '(#' . count($recordSet) . ')Fetching records for refund  ' . PHP_EOL;
        print_r($recordSet);
        echo PHP_EOL;

        if (count($recordSet) > 0) {
            foreach ($recordSet as $key => $row) {

                $csvRowSet = [];
                $csvRowSet[] = $row['txn_reference_no'];
                $csvRowSet[] = date('Ymd', strtotime($row['response_time']));
                $csvRowSet[] = $row['customer_id'];
                $csvRowSet[] = number_format($row['amount'], 2, '', '');
                $csvRowSet[] = number_format($row['amount'], 2, '', '');

                $wallets_history = \app\models\WalletsHistory::find()->where(['id' => $row['id']])->one();
                $wallets_history->processed = 1;
                if (!$wallets_history->save(false)) {
                    throw new \Exception('Exception');
                }

                fputcsv($csvRefund, $csvRowSet);
                unset($csvRowSet);
            }
            fclose($csvRefund);
        }

        if (filesize($pathRefund . $csvRefundName) == 0) {
            $csvRefundName = 'NOFILE';
        }


        // Step 3. email both files to bill desk
        $CC = '';
        $BCC = '';

        $to = \app\models\SystemConfig::find()->where(['name' => 'PGEMAILID'])->one()->value;

        try {
            $CC = \app\models\SystemConfig::find()->where(['name' => 'PGICCLIST'])->one()->value;
        } catch (Exception $exc) {
            $CC = '';
        }

        try {
            $BCC = \app\models\SystemConfig::find()->where(['name' => 'PGICCLIST'])->one()->value;
        } catch (Exception $exc) {
            $BCC = '';
        }

        $subject = "EASYLTECH Settlement and Refund files for: " . date('Ymd', strtotime($beginningOfPreviousDay));

        $msg = "In case of any queries, please contact Easyleses Technologies Pvt. Ltd. support at support@easyleases.in ";

        $attachments = [];

        if ($csvSettlementName != 'NOFILE') {
            $attachments[] = $pathSettlement . $csvSettlementName;
        }

        if ($csvRefundName != 'NOFILE') {
            $attachments[] = $pathRefund . $csvRefundName;
        }

        if ($csvSettlementName != 'NOFILE' or $csvRefundName != 'NOFILE') {
            Yii::$app->userdata->doMailWithCC($to, $CC, $BCC, $subject, $msg, $attachments);
        }
    }

    public function actionRemoveBookings() {

        //  Step 1: Remove booking of property on completion of 4 days (round up to 12 AM/PM)

        $maxBooking = \app\models\SystemConfig::find()->where(['name' => 'MAXBOOKINGDAYS'])->one()->value;

        $CutOffDate = date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - $maxBooking, date("Y")));

        echo $CutOffDate;

        $strSQLOverBooked = "SELECT * from  favourite_properties where (visit_date <= '$CutOffDate') and status = '2' ";

        echo $strSQLOverBooked;
        $recordSet = Yii::$app->db->createCommand($strSQLOverBooked)->queryAll();

        echo PHP_EOL;
        echo '(#' . count($recordSet) . ')Fetching bookings for cancellation of booked properties above threshold days  ' . PHP_EOL;
        print_r($recordSet);
        echo PHP_EOL;

        if (count($recordSet) > 0) {
            foreach ($recordSet as $key => $row) {

                echo 'Under Process--->' . $row['id'] . '<---- ' . PHP_EOL;

                Yii::$app->userdata->deletefavFromConsole($row['id']);
            }
        }

        //  Step 4: Payment Confirmation from bank (ON HOLD AWAITING integreation discussion with YES Bank)
    }

    public function actionPaymentReminder() {
        // Upcoming/Overdue Payments Reminder
        
        //$today = date('Y-m-01');
        $today = date('Y-m-d');
        $firstDateMonth = date('Y-m-01');
        $timeInterval = 60;
        if (strtotime($today) == strtotime($firstDateMonth)) {
            $iterate = true;
            $timeFlag = 0;
            $timeThreshold = 28800;
            while ($iterate) {
                if ($timeFlag >= $timeThreshold) {
                    exit;
                }
                $paramsModel = \app\models\SystemConfig::find()->where(['name' => 'PGPROCESS'])->one();
                if ($paramsModel->value == $firstDateMonth) {
                    $iterate = false;
                } else {
                    echo 'Waiting for Tenant Pay row.' . PHP_EOL;
                    if ($timeFlag >= $timeInterval) {
                        echo 'Script running from last '.($timeFlag/60).' minute.' . PHP_EOL . PHP_EOL;
                    }
                    $timeFlag = $timeFlag + $timeInterval;
                    sleep($timeInterval);
                }
            }
        }
        
        //$today = "2019-10-21";
        $to = '';
        $msg = '';
        $Notfnmsg ='';
        $subject = '';
        $notificationTitle = "Rent Payment Reminder";
        //$PAYMENT_REMINDER = \app\models\SystemConfig::find()->where(['name' => 'PAYMENT_REMINDER'])->one()->value;
        //$CutOffDate = date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") + $PAYMENT_REMINDER, date("Y")));

        
        // Manish to do: Add notification for all cases; stream line code
        
        $strSQLUpcomingPayment = " SELECT *, datediff(due_date,'" . $today . "') due_diff from  tenant_payments where "
                . "(due_date <= '" . $today . "' OR "
                . "( due_date  > '" . $today . "' AND month('" . $today . "') = month(due_date))) and payment_status = 0 ";
        
        echo PHP_EOL;
        echo PHP_EOL;
        echo PHP_EOL;
        
        echo "Starting payment reminder, today - ".date('d-M-Y');
        echo PHP_EOL;

        echo $strSQLUpcomingPayment;

        $recordSet = Yii::$app->db->createCommand($strSQLUpcomingPayment)->queryAll();

        echo '\$$$$$) Upcoming payment reminders :' . count($recordSet);
        print_r($recordSet);
        echo "------------";

        if (count($recordSet) > 0) {
            foreach ($recordSet as $key => $row) {
                $msg = '';
                $subject = '';
                $Notfnmsg = '';
                $sendNotification = False;
                $generateMail=False;
                $to = Yii::$app->userdata->getEmailById($row['tenant_id']);
                $monthstart = date("Y-m-d", (strtotime("first day of this month")));

                if (strtotime($today) == strtotime($monthstart)) {
                    echo "1st of month execution" . PHP_EOL;
                    echo "Tenant ID:" . $row['tenant_id'] .  "- Row Id:" . $row['id'] . PHP_EOL;
                    $res = Yii::$app->userdata->generatePaymentAlert($row['id']);
                    $Notfnmsg = "Rent Invoice for your rental property: " . Yii::$app->userdata->getPropertyNameById($row['property_id']) . " has been generated. Click now to pay the rent.";;
                    $sendNotification = True;
                }
                
                else if ((strtotime($row['due_date']) == strtotime($today))) {
                    $generateMail = True;
                    $sendNotification = True;
                    echo "0 days " . $row['due_date'] . " Date Diff " . $row['due_diff'] . PHP_EOL;
                    $subject = "Urgent payment towards your rental property: " . Yii::$app->userdata->getPropertyNameById($row['property_id']) . " is due today.";
                    $msg = "Just to let you know that the payment of Rs. " . $row['total_amount'] . " towards " . $row['payment_des'] . ' is due today. Please pay today to avoid any penalty charges.';
                }
                
                else if (strtotime($row['due_date']) < strtotime($today) && ($row['due_diff'] / self::DUE_INTERVAL == round($row['due_diff'] / self::DUE_INTERVAL,0) ) ) {
                    $generateMail = True;
                    $sendNotification = True;
                    echo "Even days overdue " . $row['due_date'] . " Date Diff " . $row['due_diff'] . PHP_EOL;
                    $subject = "Urgent payment overdue for your rental property: " . Yii::$app->userdata->getPropertyNameById($row['property_id']);
                    $msg = "Your payment of Rs. " . $row['total_amount'] . " towards " . $row['payment_des'] . " is overdue. Please pay at the earliest to minimize late payment penalty charges.";
                }
                
                If ($sendNotification) {
                    // Manish added notifcation code below
                    $notificationBody = $Notfnmsg; 
                    $imageURL = "";
                    
                    $tenantTokens = \app\models\UserDevices::find()->where(['user_id' => $row['tenant_id']])->all();
                    if (count($tenantTokens) > 0) {
                        $i=0;
                        foreach ($tenantTokens as $key2 => $token) {
                            
                            //print_r($token); exit;
                            $fcmToken = $token["fcm_token"];
                            
                            $notification = $this->sendNotification(array('title' => $notificationTitle ,'body'=> $notificationBody , 
                                'fcmToken' => $fcmToken, 'imageURL' => $imageURL, 'clickAction' => 'app',
                                'clickTarget' => '4' ));
                            
                            $i++;
                        }
                    }
                }
                
                if ($generateMail) {
                    $HTMLmsg = ' '
                            . '<!DOCTYPE html>'
                            . '<html>'
                            . '<head>'
                            . '<style>'
                            . '</style>'
                            . '</head>'
                            . '<body>'
                            . 'Hey There,'
                            . '<br /><br />'
                            . 'Greetings from Easyleases!!!<br />'
                            . $msg
                            . '<br /><br />'
                            . 'With Regards, <br />'
                            . 'Easyleases Team <br />'
                            . '<img style="height: 45px;width: 185px;" src="http://www.easyleases.in/images/newlogo1.png" />'
                            . '<br /> <br /> '
                            . 'P.S: Please ignore if paid already.'
                            . '<br />'
                            . '</body>'
                            . '</html>'
                    ;


                    echo "MESSAGE Value -" . $row['due_diff'] . PHP_EOL;
                    // echo $msg . PHP_EOL;
                    // echo $HTMLmsg . PHP_EOL;

                    if (!empty($msg)) {

                        echo "INSIDE MESSAGE" . $row['due_diff'] . PHP_EOL;
                        echo $to . PHP_EOL;
                        echo $subject . PHP_EOL;
                        echo $msg . PHP_EOL;

                        Yii::$app->userdata->doMail($to, $subject, $HTMLmsg);

                        $tenantPhone = Yii::$app->userdata->getPhoneNumberById($row['tenant_id'], 3);
                        if (!empty($tenantPhone)) {
                            echo $tenantPhone . PHP_EOL;
                            echo $msg;
                            if (Yii::$app->userdata->sendSms([$tenantPhone], $msg)) {
                                echo PHP_EOL;
                                echo 'Text Sent To ' . $tenantPhone . ' with message ' . $msg;
                                echo PHP_EOL;
                            }
                        }
                    }
                }
            }
        }
    }

    // To be tested Deactivations    
    public function actionDeactivations() {
        // Step 3: Intimate all owners, tenants regarding upcoming closure of their agreement 
        // 3a: Deactivate Property

        $today = date('Y-m-d');
        $alertStartDate = date("Y-m-d", strtotime('-16 days'));
        $strSQLOverBooked = "SELECT * from  properties where id in (Select property_id from property_agreements "
                . "where contract_end_date <= '$today'  and contract_end_date >= '$alertStartDate' )";

        echo $strSQLOverBooked;
        $recordSet = Yii::$app->db->createCommand($strSQLOverBooked)->queryAll();

        echo PHP_EOL;
        echo '(#' . count($recordSet) . ')Fetching properties for deactivation  ' . PHP_EOL;
        print_r($recordSet);
        echo PHP_EOL;

        if (count($recordSet) > 0) {
            foreach ($recordSet as $key => $row) {

                echo 'Under Process--->' . $row['id'] . '<---- ' . PHP_EOL;

                $to = strtolower(Yii::$app->userdata->getEmailById($recordSet['owner_id']));
                $subject = 'Deactivation of your property: ' . Yii::$app->userdata->getPropertyNameById($recordSet['property_id']);
                $msg = 'Hey There, <br /><br />'
                        . 'Greetings from Easyleases!!! <br /><br />'
                        . 'We are sad to see you go. Your property : ' . Yii::$app->userdata->getPropertyNameById($recordSet['property_id'])
                        . ' has been deactivated. <br /><br />If you feel this is an error then please reach out to'
                        . ' your support executive or at support@easyleases.in'
                        . '<br /><br />'
                        . 'Thanks, <br />'
                        . '<b>Easyleases Team</b> ';

                Yii::$app->userdata->doMail($to, $subject, $msg);
                $ownerPhone = Yii::$app->userdata->getPhoneNumberById($recordSet['owner_id'], 4);
                if (!empty($ownerPhone)) {
                    $txtMess = ''
                            . 'Your property : ' . Yii::$app->userdata->getPropertyNameById($recordSet['property_id']) . ', '
                            . 'has been deactivated. '
                            . 'If you feel this is an error then please reach out to '
                            . 'your support executive or at support@easyleases.in';
                    if (Yii::$app->userdata->sendSms([$ownerPhone], $txtMess)) {
                        echo PHP_EOL;
                        echo 'Text Sent To ' . $ownerPhone . ' with message ' . $txtMess;
                        echo PHP_EOL;
                    }
                }
                $commandSQL = "Update properties set status = 0 where id = " . $row['id'];
                $command = Yii::$app->db->createCommand($commandSQL);
                Yii::$app->db->execute;
            }
        }


        // 3b: Deactivate Owner
        $yesterday = date('YYYY-mm-dd', strtotime('-1 Day'));
        $strSQLOverBooked = "SELECT * from  properties where id in (Select pa.property_id from property_agreements pa LEFT JOIN tenant_agreements  ta "
                . "ON pa.property_id= ta.parent_id where pa.contract_end_date < '$today' and COALESCE(ta.lease_end_date, '$yesterday') < '$today') ";

        echo $strSQLOverBooked;
        $recordSet = Yii::$app->db->createCommand($strSQLOverBooked)->queryAll();

        echo PHP_EOL;
        echo '(#' . count($recordSet) . ')Fetching properties for deactivation  ' . PHP_EOL;
        print_r($recordSet);
        echo PHP_EOL;

        if (count($recordSet) > 0) {
            foreach ($recordSet as $key => $row) {

                echo 'Under Process--->' . $row['id'] . '<---- ' . PHP_EOL;

                $to = strtolower(Yii::$app->userdata->getEmailById($row['owner_id']));
                $subject = 'Deactivation of your property: ' . Yii::$app->userdata->getPropertyNameById($row['id']);
                $msg = 'Hey There, <br /><br />'
                        . 'Greetings from Easyleases!!! <br /><br />'
                        . 'We are sad to see you go. Your property : ' . Yii::$app->userdata->getPropertyNameById($row['id'])
                        . ' has been deactivated. <br /><br />If you feel this is an error then please reach out to'
                        . ' your support executive or at support@easyleases.in'
                        . '<br /><br />'
                        . 'Thanks, <br />'
                        . '<b>Easyleases Team</b> ';

                Yii::$app->userdata->doMail($to, $subject, $msg);
                $ownerPhone = Yii::$app->userdata->getPhoneNumberById($row['owner_id'], 4);
                if (!empty($ownerPhone)) {
                    $txtMess = ''
                            . 'Your property : ' . Yii::$app->userdata->getPropertyNameById($row['property_id']) . ', '
                            . 'has been deactivated. '
                            . 'If you feel this is an error then please reach out to '
                            . 'your support executive or at support@easyleases.in';
                    if (Yii::$app->userdata->sendSms([$ownerPhone], $txtMess)) {
                        echo PHP_EOL;
                        echo 'Text Sent To ' . $ownerPhone . ' with message ' . $txtMess;
                        echo PHP_EOL;
                    }
                }
                $commandSQL = "Update properties set status = 0 where id = " . $row['id'];
                $command = Yii::$app->db->createCommand($commandSQL);
                $command->execute();
            }
        }
    }

    public function actionAdvisorCommission() {

        $today = date('Y-m-d');
        $monthstart = date("Y-m-d", (strtotime("first day of this month")));
        $monthend = date("Y-m-d", (strtotime("last day of this month")));
        $tdsIndianValue = \app\models\SystemConfig::find()->where(['name' => 'TDS-INDIAN'])->one()->value;

        echo $monthstart;
        echo $monthend;
        $strSQLAdvisorsConfig = "Select a.advisor_id,b.advisor_fees,b.min,b.max,b.slab,b.config_type from advisor_agreements a "
                . "join advisors_payment_config b on a.advisor_id = b.advisor_id "
                . " where start_date <= '" . $monthend . "' and end_date>='" . $monthstart . "' and b.config_type = 1";

        $strSQLDefaultConfig = "Select a.advisor_id,b.advisor_fees,b.min,b.max,b.slab,b.config_type from advisor_agreements a, "
                . "advisors_default_payment_config b where a.advisor_Id Not In "
                . "(select advisor_id from advisors_payment_config) and  start_date <= '" . $monthend . "' and end_date>='" . $monthstart . "' and b.config_type = 1"
                . " Order by advisor_id,config_type,min";

        $strSQLUnion = $strSQLAdvisorsConfig . " Union " . $strSQLDefaultConfig;

        // echo $strSQLUnion;

        $recordSet = Yii::$app->db->createCommand($strSQLUnion)->queryAll();

//        echo '\$$$$$) Active Advisors :' . count($recordSet) . PHP_EOL;
        //print_r($recordSet);
        echo "------------" . PHP_EOL;
        $arrOwnerAdvisorSlabs = [];
        if (count($recordSet) > 0) {
            $keyPreVal = 0;
            $ii = 0;
            foreach ($recordSet as $key => $row) {
                if ($keyPreVal != $row['advisor_id']) {
                    $ii = 0;
                }
                $arrOwnerAdvisorSlabs[$row['advisor_id']][$ii][] = $row['min'];
                $arrOwnerAdvisorSlabs[$row['advisor_id']][$ii][] = $row['max'];
                $arrOwnerAdvisorSlabs[$row['advisor_id']][$ii][] = $row['slab'];
                $arrOwnerAdvisorSlabs[$row['advisor_id']][$ii][] = $row['advisor_fees'];
                $keyPreVal = $row['advisor_id'];
                $ii++;
            }
        }

//        print_r($arrOwnerAdvisorSlabs);

        $strSQLAdvisorsConfig = "Select a.advisor_id,b.advisor_fees,b.min,b.max,b.slab,b.config_type from advisor_agreements a "
                . "join advisors_payment_config b on a.advisor_id = b.advisor_id "
                . " where start_date <= '" . $monthend . "' and end_date>='" . $monthstart . "' and b.config_type = 2";

        $strSQLDefaultConfig = "Select a.advisor_id,b.advisor_fees,b.min,b.max,b.slab,b.config_type from advisor_agreements a, "
                . "advisors_default_payment_config b where a.advisor_Id Not In "
                . "(select advisor_id from advisors_payment_config) and  start_date <= '" . $monthend . "' and end_date>='" . $monthstart . "' and b.config_type = 2"
                . " Order by advisor_id,config_type,min";

        $strSQLUnion = $strSQLAdvisorsConfig . " Union " . $strSQLDefaultConfig;

        // echo $strSQLUnion;

        $recordSet = Yii::$app->db->createCommand($strSQLUnion)->queryAll();

//        echo '\$$$$$) Active Advisors :' . count($recordSet) . PHP_EOL;
        //print_r($recordSet);
        echo "------------" . PHP_EOL;
        $arrTenantAdvisorSlabs = [];
        if (count($recordSet) > 0) {
            $keyPreVal = 0;
            $ii = 0;
            foreach ($recordSet as $key => $row) {
                if ($keyPreVal != $row['advisor_id']) {
                    $ii = 0;
                }
                $arrTenantAdvisorSlabs[$row['advisor_id']][$ii][] = $row['min'];
                $arrTenantAdvisorSlabs[$row['advisor_id']][$ii][] = $row['max'];
                $arrTenantAdvisorSlabs[$row['advisor_id']][$ii][] = $row['slab'];
                $arrTenantAdvisorSlabs[$row['advisor_id']][$ii][] = $row['advisor_fees'];
                $keyPreVal = $row['advisor_id'];
                $ii++;
            }
        }
        echo "Owner Slab" . PHP_EOL;
//        print_r($arrOwnerAdvisorSlabs);
        echo "Tenant Slab" . PHP_EOL;
        print_r($arrTenantAdvisorSlabs);
        $txn = Yii::$app->db->beginTransaction();
        try {
            $strAdvsiorProperties = 'select distinct lo.reffered_by,pr.id,pr.owner_id, us.full_name as owner_name, pr.property_name, prt.property_type_name,'
                    . ' pa.pms_commission from leads_owner lo join users us on lo.email_id = us.login_id join properties pr on us.id = pr.owner_id '
                    . ' join tenant_agreements ta on pr.id = ta.property_id join property_agreements pa '
                    . " on pr.id = pa.property_id join property_types prt on pr.property_type = prt.id "
                    . "where  lo.reffered_by is not null and pr.status = '1'  order by lo.reffered_by,pr.id;";

            echo $strAdvsiorProperties;

            echo '------ADV Properties Record set ----';

            $recordSet = Yii::$app->db->createCommand($strAdvsiorProperties)->queryAll();

            //        print_r($recordSet);

            if (count($recordSet) > 0) {

                $advProperties = 0;
                $prevAdvisor = '';
                $prevSlab = -1;
                $currentSlab = 1;
                $index = 0;
                foreach ($recordSet as $key => $rowOwner) {
                    if (array_key_exists($rowOwner['reffered_by'], $arrOwnerAdvisorSlabs)) {
                        echo PHP_EOL . '-' . $prevAdvisor . $rowOwner['reffered_by'];
                        if ($prevAdvisor <> $rowOwner['reffered_by']) {

                            $advProperties = 1;
                            $currentSlab = 1;
                            $index = 0;
                        } else {
                            $advProperties = $advProperties + 1;
                        }

                        $prevAdvisor = $rowOwner['reffered_by'];
                        if ($advProperties > $arrOwnerAdvisorSlabs[$rowOwner['reffered_by']][$index][1]) {
                            $index = $index + 1;
                            $currentSlab = $arrOwnerAdvisorSlabs[$rowOwner['reffered_by']][$index][2];
                        }


                        $strAdvCommCalc = "Select * from tenant_payments where property_id = " . $rowOwner['id'] . " and payment_status = 1 "
                                . " and advisor_calculation_prop = '0' and payment_type = 2";

                        $recordSetCommCalc = Yii::$app->db->createCommand($strAdvCommCalc)->queryAll();

                        // echo PHP_EOL ;
                        //               print_r($recordSetCommCalc);

                        if (count($recordSetCommCalc) > 0) {

                            foreach ($recordSetCommCalc as $keyComm => $rowCommOwner) {

                                $advisorPayments = new \app\models\AdvisorPayments();
                                $advisorPayments->advisor_id = $rowOwner['reffered_by'];
                                $advisorPayments->source_id = $rowOwner['owner_id'];
                                $advisorPayments->source_type = '1';
                                $advisorPayments->source_name = $rowOwner['owner_name'];
                                $advisorPayments->property_id = $rowCommOwner['property_id'];
                                $advisorPayments->property_name = $rowOwner['property_name'];
                                $advisorPayments->child_id = "";
                                if ($rowCommOwner['bed_id'] <> "") {
                                    $advisorPayments->child_id = $rowCommOwner['bed_id'];
                                    $advisorPayments->property_type = $rowOwner['property_type_name'] . " - Bed";
                                    // echo PHP_EOL . "PROPERTY_TYPE " . $advisorPayments->property_type;
                                } else {
                                    if ($rowCommOwner['room_id'] <> "") {
                                        $advisorPayments->child_id = $rowCommOwner['room_id'];
                                        $advisorPayments->property_type = $rowOwner['property_type_name'] . " - Room";
                                        // echo PHP_EOL . "PROPERTY_TYPE " . $advisorPayments->property_type;
                                    } else {
                                        $advisorPayments->property_type = $rowOwner['property_type_name'];
                                        // echo PHP_EOL . "PROPERTY_TYPE " . $advisorPayments->property_type;
                                    }
                                }

                                $commissionAmount = $rowCommOwner['original_amount'] * ($rowOwner['pms_commission'] / 100) * ($arrOwnerAdvisorSlabs[$rowOwner['reffered_by']][$index][3] / 100);
                                $advisorPayments->comission_amount = $commissionAmount;
                                $advisorPayments->commission_percentage = $arrOwnerAdvisorSlabs[$rowOwner['reffered_by']][$index][3];
                                $advisorPayments->tds = $commissionAmount * $tdsIndianValue / 100;
                                $advisorPayments->payable_amount = $commissionAmount * ( 1 - $tdsIndianValue / 100);
                                $advisorPayments->slab = $arrOwnerAdvisorSlabs[$rowOwner['reffered_by']][$index][2];
                                $advisorPayments->pms_comission = $rowCommOwner['original_amount'] * ($rowOwner['pms_commission'] / 100);
                                $advisorPayments->month = date("m");
                                $advisorPayments->year = date("Y");
                                if (!$advisorPayments->save(false)) {
                                    throw new \Exception('Exception');
                                }
                                echo PHP_EOL . "Create row success";
                                $commandSQL = "Update tenant_payments set advisor_calculation_prop = '1' where id = " . $rowCommOwner['id'];
                                $command = Yii::$app->db->createCommand($commandSQL);
                                if (!$command->execute()) {
                                    throw new \Exception('Exception');
                                }
                                echo PHP_EOL . "Update row success id = " . $rowCommOwner['id'];
                            }
                        }
                    }
                    //loop for each advisor property through tenant_payments where payment_status = 1 and advisor_flag = 0
                    // Calculate advsior commission based on the property number
                    //Insert into advisor payments row corresponding to tenant_payment
                    // end loop
                    // If prev_advisor = current_advior then adv_properties = adv_properties+ 1 else adv_property=1   
                    else {
                        $commandSQL = "Update tenant_payments set advisor_calculation_prop = '1' where property_id = " . $rowOwner['id'];
                        $command = Yii::$app->db->createCommand($commandSQL);
                        if (!$command->execute()) {
                            throw new \Exception('Exception');
                        }
                    }
                }
            }

            $strAdvsiorTenants = 'select distinct lt.reffered_by,ta.tenant_id, us.full_name as tenant_name,pr.id,pr.owner_id, pr.property_name, prt.property_type_name,'
                    . ' pa.pms_commission from leads_tenant lt join users us on lt.email_id = us.login_id  '
                    . ' join tenant_agreements ta on us.id = ta.tenant_id join properties pr on pr.id = ta.property_id join '
                    . ' property_agreements pa  on pr.id = pa.property_id join property_types prt on pr.property_type = prt.id '
                    . " where  lt.reffered_by is not null and pr.status = '1' order by lt.reffered_by,ta.tenant_id;";

            echo $strAdvsiorTenants;

            echo '------ADV Tenant Record set ----';

            $recordSet = Yii::$app->db->createCommand($strAdvsiorTenants)->queryAll();

            print_r($recordSet);

            if (count($recordSet) > 0) {

                echo PHP_EOL . 'Count' . '-' . count($recordSet);
                $advTenants = 0;
                $prevAdvisor = '';
                $prevSlab = -1;
                $currentSlab = 1;
                $index = 0;

                foreach ($recordSet as $key => $rowTenant) {
                    if (array_key_exists($rowTenant['reffered_by'], $arrTenantAdvisorSlabs)) {

                        if ($prevAdvisor <> $rowTenant['reffered_by']) {

                            $advTenants = 1;
                            $currentSlab = 1;
                            $index = 0;
                        } else {
                            $advTenants = $advTenants + 1;
                        }
                        echo PHP_EOL . " Here 2";
                        $prevAdvisor = $rowTenant['reffered_by'];
                        echo PHP_EOL . " Here 3";
                        if ($advProperties > $arrTenantAdvisorSlabs[$rowTenant['reffered_by']][$index][1]) {
                            echo PHP_EOL . " Here 4";
                            $index = $index + 1;
                            $currentSlab = $arrOwnerAdvisorSlabs[$rowTenant['reffered_by']][$index][2];
                        }
                        // echo PHP_EOL;

                        echo $rowTenant['reffered_by'] . '-' . $advProperties . '-' . $prevSlab . '-' . $currentSlab;


                        $strAdvCommCalc = "Select * from tenant_payments where tenant_id = " . $rowTenant['tenant_id'] . " and payment_status = 1 "
                                . " and advisor_calculation_tenant = '0' and payment_type = 2";

                        $recordSetCommCalc = Yii::$app->db->createCommand($strAdvCommCalc)->queryAll();
                        // echo PHP_EOL ;
                        print_r($recordSetCommCalc);

                        if (count($recordSetCommCalc) > 0) {

                            foreach ($recordSetCommCalc as $keyComm => $rowCommTenant) {

                                $advisorPayments = new \app\models\AdvisorPayments();
                                $advisorPayments->advisor_id = $rowTenant['reffered_by'];
                                $advisorPayments->source_id = $rowTenant['tenant_id'];
                                $advisorPayments->source_type = '2';
                                $advisorPayments->source_name = $rowTenant['tenant_name'];
                                $advisorPayments->property_id = $rowCommTenant['property_id'];
                                $advisorPayments->property_name = $rowTenant['property_name'];
                                $advisorPayments->child_id = "";
                                if ($rowCommTenant['bed_id'] <> "") {
                                    $advisorPayments->child_id = $rowCommTenant['bed_id'];
                                    $advisorPayments->property_type = $rowTenant['property_type_name'] . " - Bed";
                                    // echo PHP_EOL . "PROPERTY_TYPE " . $advisorPayments->property_type;
                                } else {
                                    if ($rowCommTenant['room_id'] <> "") {
                                        $advisorPayments->child_id = $rowCommTenant['room_id'];
                                        $advisorPayments->property_type = $rowTenant['property_type_name'] . " - Room";
                                        // echo PHP_EOL . "PROPERTY_TYPE " . $advisorPayments->property_type;
                                    } else {
                                        $advisorPayments->property_type = $rowTenant['property_type_name'];
                                        // echo PHP_EOL . "PROPERTY_TYPE " . $advisorPayments->property_type;
                                    }
                                }

                                $commissionAmount = $rowCommTenant['original_amount'] * ($rowTenant['pms_commission'] / 100) * ($arrTenantAdvisorSlabs[$rowTenant['reffered_by']][$index][3] / 100);
                                $advisorPayments->comission_amount = $commissionAmount;
                                $advisorPayments->commission_percentage = $arrTenantAdvisorSlabs[$rowTenant['reffered_by']][$index][3];
                                $advisorPayments->tds = $commissionAmount * $tdsIndianValue / 100;
                                $advisorPayments->payable_amount = $commissionAmount * ( 1 - $tdsIndianValue / 100);
                                $advisorPayments->slab = $arrOwnerAdvisorSlabs[$rowTenant['reffered_by']][$index][2];
                                $advisorPayments->pms_comission = $rowCommTenant['original_amount'] * ($rowTenant['pms_commission'] / 100);
                                $advisorPayments->month = date("m");
                                $advisorPayments->year = date("Y");
                                if (!$advisorPayments->save(false)) {
                                    throw new \Exception('Exception');
                                }
                                echo PHP_EOL . "Create Tenant row success";
                                $commandSQL = "Update tenant_payments set advisor_calculation_tenant = '1' where id = " . $rowCommTenant['id'];
                                $command = Yii::$app->db->createCommand($commandSQL);
                                if (!$command->execute()) {
                                    throw new \Exception('Exception');
                                }
                                echo PHP_EOL . "Update tenant row success id = " . $rowCommTenant['id'];
                            }


                            //loop for each advisor property through tenant_payments where payment_status = 1 and advisor_flag = 0
                            // Calculate advsior commission based on the property number
                            //Insert into advisor payments row corresponding to tenant_payment
                            // end loop
                            // If prev_advisor = current_advior then adv_properties = adv_properties+ 1 else adv_property=1   
                        } else {
                            $commandSQL = "Update tenant_payments set advisor_calculation_tenant = '1' where tenant_id = " . $rowTenant['tenant_id'];
                            $command = Yii::$app->db->createCommand($commandSQL);
                            if (!$command->execute()) {
                                echo "Exception 1409";
                                throw new \Exception('Exception');
                            }
                        }
                    }
                }
            }
            $txn->commit();
        } catch (\Exception $e) {
            echo "Exception : " . $e->getLine() . '-' . $e->getMessage();
            $txn->rollBack();
        }
    }

    public function actionDailyExpiryNotifications() {
        // MG: 26 Apr
        // Expiry related notifications sent via email for upcoming expiry of Property Agreement, Rent agreement, Advisor Agreement
        $today = date('Y-m-d');
        $t90 = date('Y-m-d', strtotime($today . "+ 90 days"));
        $t60 = date('Y-m-d',strtotime($today . "+ 60 days"));
        $t30 = date('Y-m-d',strtotime($today . "+ 30 days"));
        $t10 = date('Y-m-d',strtotime($today . "+ 10 days"));
        $t01 = date('Y-m-d',strtotime($today . "+ 1 day"));

//        $arrTimePeriod[0] = $t00; Currently will not send any more reminders from here for on the day of expiry 
        $arrTimePeriod[1] = $t01;
        $arrTimePeriod[2] = $t10;
        $arrTimePeriod[3] = $t30;
        $arrTimePeriod[4] = $t60;
        $arrTimePeriod[5] = $t90;

//        echo date_format($t90,"y-m-d") . PHP_EOL;
        echo $arrTimePeriod[1] . PHP_EOL;
        echo $arrTimePeriod[2] . PHP_EOL;
        echo $arrTimePeriod[3] . PHP_EOL;
        echo $arrTimePeriod[4] . PHP_EOL;
        echo $arrTimePeriod[5] . PHP_EOL;

        
        $i = 0;

        for ($i = 1; $i <= 5; $i++) {

            // 1.0 Property Agreement Notification Check
            $strPropertyExp = "Select b.*, a.* from property_agreements a join properties b on a.property_id = b.id where a.notice_status = '0' AND contract_end_date = '" . $arrTimePeriod[$i] . "'";

            $recordSetPropertyExp = Yii::$app->db->createCommand($strPropertyExp)->queryAll();
            // echo PHP_EOL ;
            //print_r($recordSetPropertyExp);

 
            if (count($recordSetPropertyExp) > 0) {

                foreach ($recordSetPropertyExp as $keyComm => $rowExpiry) {
                    $ops_person_email = strtolower(Yii::$app->userdata->getEmailById($rowExpiry['operations_id']));
                    $sales_person_email = strtolower(Yii::$app->userdata->getEmailById($rowExpiry['sales_id']));

                    $sub = 'Upcoming Property Agreement Expiry notification';
                    $to = strtolower(Yii::$app->userdata->getEmailById($rowExpiry['owner_id']));
                    $cc = $ops_person_email . ',' . $sales_person_email . ', ' . Yii::$app->params['supportEmail'];

                    $msg = "Hey There, <br /><br />"
                    . "Greetings from Easyleases !!! <br /><br />"
                    . "Your property agreement for '" . $rowExpiry['property_name'] . "' is up for renewal on: " . date('d-M-y', strtotime($arrTimePeriod[$i])) . " <br />"
                    . "A member of our support team (copied on this email) will be in touch with you to fecilitate extension or closure of the service level agreement. <br /><br />"
                    . "With Regards, <br />"
                    . "Easyleases Support Team<br />"
                    . "<img style='height: 45px;width: 185px;' src='http://www.easyleases.in/images/newlogo1.png' />";
                   
                    echo "SUCC";
                    echo $to . PHP_EOL;
                    echo $cc . PHP_EOL;
                    echo $sub . PHP_EOL;
                    echo $msg . PHP_EOL;
                     
                    yii::$app->userdata->doMailWithCC($to, $cc, "", $sub, $msg, []);
                    
                }
            }
            
            // 2.0 Tenant Agreement Notification Check
            $strLeaseExp = "Select b.*, a.*,c.property_name, c.owner_id OWN_ID, c.sales_id Prop_Sales, c.operations_id Prop_OPS  from tenant_agreements a join tenant_profile b on a.tenant_id = b.tenant_id join properties c on a.property_id = c.id  where a.notice_status = '0' AND lease_end_date = '" . $arrTimePeriod[$i] . "'";

            $recordSetLeaseExp = Yii::$app->db->createCommand($strLeaseExp)->queryAll();
            // echo PHP_EOL ;
            //print_r($recordSetLeaseExp);

 
            if (count($recordSetLeaseExp) > 0) {

                foreach ($recordSetLeaseExp as $keyComm => $rowExpiry) {
                    $ops_person_email = strtolower(Yii::$app->userdata->getEmailById($rowExpiry['operation_id']));
                    $sales_person_email = strtolower(Yii::$app->userdata->getEmailById($rowExpiry['sales_id']));

                    $sub = 'Upcoming Rental Agreement Expiry notification';
                    $to = strtolower(Yii::$app->userdata->getEmailById($rowExpiry['tenant_id']));
                    $cc = $ops_person_email . ',' . $sales_person_email . ', ' . Yii::$app->params['supportEmail'];

                    $msg = "Hey There, <br /><br />"
                    . "Greetings from Easyleases !!! <br /><br />"
                    . "Your Leave & License agreement for '" . $rowExpiry['property_name'] . "' is up for renewal on: " . date('d-M-y', strtotime($arrTimePeriod[$i])) . " <br />"
                    . "A member of our support team (copied on this email) will be in touch with you to fecilitate extension or closure of the service level agreement. <br /><br />"
                    . "With Regards, <br />"
                    . "Easyleases Support Team<br />"
                   . "<img style='height: 45px;width: 185px;' src='http://www.easyleases.in/images/newlogo1.png' />";
                                     
 
                    yii::$app->userdata->doMailWithCC($to, $cc, "", $sub, $msg, []);
 
                     

                    $ops_person_email = strtolower(Yii::$app->userdata->getEmailById($rowExpiry['Prop_OPS']));
                    $sales_person_email = strtolower(Yii::$app->userdata->getEmailById($rowExpiry['Prop_Sales']));

                    $to = strtolower(Yii::$app->userdata->getEmailById($rowExpiry['OWN_ID']));
                    $cc = $ops_person_email . ',' . $sales_person_email . ', ' . Yii::$app->params['supportEmail'];

                    echo $to . PHP_EOL;
                    echo $cc . PHP_EOL;
 
                    yii::$app->userdata->doMailWithCC($to, $cc, "", $sub, $msg, []);
                    
                }
            }
            // 3.0 Advisor Agreement Notification Check
            $strAdvisorExp = "Select a.advisor_id adv, b.*, a.end_date from advisor_agreements a join advisor_profile b on a.advisor_id = b.advisor_id where end_date = '" . $arrTimePeriod[$i] . "'";

            $recordSetAdvisorExp = Yii::$app->db->createCommand($strAdvisorExp)->queryAll();
            // echo PHP_EOL ;
            //print_r($recordSetAdvisorExp);

 
            if (count($recordSetLeaseExp) > 0) {

                foreach ($recordSetAdvisorExp as $keyComm => $rowExpiry) {
                    $ops_person_email = strtolower(Yii::$app->userdata->getEmailById($rowExpiry['operation_id']));
                    $sales_person_email = strtolower(Yii::$app->userdata->getEmailById($rowExpiry['sales_id']));

                    $sub = 'Upcoming Advisor Agreement Expiry notification';
                    $to = strtolower(Yii::$app->userdata->getEmailById($rowExpiry['adv']));
                    $cc = $ops_person_email . ',' . $sales_person_email . ', ' . Yii::$app->params['supportEmail'];

                    $msg = "Hey There, <br /><br />"
                    . "Greetings from Easyleases !!! <br /><br />"
                    . "Your Advisor agreement with Easyleases is up for renewal on: " . date('d-M-y', strtotime($arrTimePeriod[$i])) . " <br />"
                    . "A member of our support team (copied on this email) will be in touch with you to fecilitate extension or closure of the service level agreement. <br /><br />"
                    . "With Regards, <br />"
                    . "Easyleases Support Team<br />"
                   . "<img style='height: 45px;width: 185px;' src='http://www.easyleases.in/images/newlogo1.png' />";
                   
                    
                    echo $to . PHP_EOL;
                    echo $cc . PHP_EOL;
                    echo $sub . PHP_EOL;
                    echo $msg . PHP_EOL;
                     
                  yii::$app->userdata->doMailWithCC($to, $cc, "", $sub, $msg, []);
                   
                }
            }

        }
    }
    private function sendNotification($arrNotifnParams) {
        echo "Inside Nitifaction send";
        print_r($arrNotifnParams); // exit;
        if (!empty($arrNotifnParams)) {
      
            
//            $notification = $this->sendNotification(array('title' => $notificationTitle ,'body'=> $notificationBody , 
//                                'sendTo' => $fcmToken, 'imageURL' => $imageURL, 'clickAction' => 'app',
//                                'clickTarget' => '4' ));
////            $notificationTitle ="Test :" . Date ("Y-m-d");
//            $notificationBody = "Body :" . time();
//            $fcmToken = "doTrQKEwJIs:APA91bFcxNKBxg9NrY8okfiKiUdhHVHoI5I2j_PVVAxU7yFgU8Z5BtvTtfCXhWOmm67nS4m5QB32aQo5NOHklxWCz2kKXPYGg6pi_LRDFu3NCrY_YHzf0fiS5FgGOzWWaXI2uBm79CVq";
            $fcmDestination = $arrNotifnParams["fcmToken"];
            $notificationTitle = $arrNotifnParams["title"];
            $notificationBody = $arrNotifnParams["body"];
            $image = $arrNotifnParams["imageURL"];
            $clickAction = $arrNotifnParams["clickAction"];  // Values allowed 'url' or 'app' 
            $clickTarget = $arrNotifnParams["clickTarget"];
  
            
            
            
            $serverKey = Yii::$app->params['fcm_server_key'];

            $headers = array('Authorization: key=' . $serverKey,'Content-Type: application/json');
            $notification = array('title' => $notificationTitle ,'body'=> $notificationBody);
            $requestData = array("click_action" => "FLUTTER_NOTIFICATION_CLICK", 
                "clickActionType" => $clickAction, 
                "clickTarget" => $clickTarget);

            $fields = array('to' => $fcmDestination,'notification' => $notification,'data' => $requestData);

            $url = Yii::$app->params['fcm_url']; 

            // Open connection
            $ch = curl_init();

            // Set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $url);

            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Disabling SSL Certificate support temporarily
            //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);

            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

            // Execute post
            echo '<h2>Result</h2><hr/><h3>Request </h3><p><pre>';
            echo json_encode($fields,JSON_PRETTY_PRINT);
            echo '</pre></p><h3>Response </h3><p><pre>';

            $result = curl_exec($ch);
            if($result === FALSE){
                    //die('Curl failed: ' . curl_error($ch));
                echo "Curl Failed";
                return false;
            } else {
              echo $result;
              return true;
          
            }

            // Close connection
            curl_close($ch);


          
        } else {
            echo "Invalid Parameters";
            return false;
        }
    }
    
    // Manish to do: add scheduled messages method
    public function actionScheduledNotifications() {
        
        
        $notificationTitle ="Test :" . Date ("Y-m-d");
        $notificationBody = "Body :" . time();
        $fcmToken = "doTrQKEwJIs:APA91bFcxNKBxg9NrY8okfiKiUdhHVHoI5I2j_PVVAxU7yFgU8Z5BtvTtfCXhWOmm67nS4m5QB32aQo5NOHklxWCz2kKXPYGg6pi_LRDFu3NCrY_YHzf0fiS5FgGOzWWaXI2uBm79CVq";
        $image = "https://www.easyleases.in/images/newlogo1.png";
        $clickAction = "url";
        $clickTarget = "https://timesofindia.indiatimes.com";
        $serverKey = Yii::$app->params['fcm_server_key'];
        
        $headers = array('Authorization: key=' . $serverKey,'Content-Type: application/json');
        $notification = array('title' => $notificationTitle ,'body'=> $notificationBody);
        $requestData = array("click_action" => "FLUTTER_NOTIFICATION_CLICK",
            "clickActionType" => $clickAction,
            "clickTarget" => $clickTarget);
        
        $fields = array('to' => $fcmToken,'notification' => $notification,'data' => $requestData);
        
        $url = 'https://fcm.googleapis.com/fcm/send';
        
        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarily
        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);
        if($result === FALSE){
                die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);

        echo '<h2>Result</h2><hr/><h3>Request </h3><p><pre>';
        echo json_encode($fields,JSON_PRETTY_PRINT);
        echo '</pre></p><h3>Response </h3><p><pre>';
        echo $result;
        echo '</pre></p>';

        
    }
    
    public function actionSendNotificationTest() {
        $notificationTitle ="Test :" . Date ("Y-m-d");
        $notificationBody = "Body :" . time();
        $fcmToken = "doTrQKEwJIs:APA91bFcxNKBxg9NrY8okfiKiUdhHVHoI5I2j_PVVAxU7yFgU8Z5BtvTtfCXhWOmm67nS4m5QB32aQo5NOHklxWCz2kKXPYGg6pi_LRDFu3NCrY_YHzf0fiS5FgGOzWWaXI2uBm79CVq";
        $image = "https://www.easyleases.in/images/newlogo1.png";
        $clickAction = "url";
        $clickTarget = "https://timesofindia.indiatimes.com";
        $serverKey = Yii::$app->params['fcm_server_key'];
        
        $headers = array('Authorization: key=' . $serverKey,'Content-Type: application/json');
        $notification = array('title' => $notificationTitle ,'body'=> $notificationBody);
        $requestData = array("click_action" => "FLUTTER_NOTIFICATION_CLICK",
            "clickActionType" => $clickAction,
            "clickTarget" => $clickTarget);
        
        $fields = array('to' => $fcmToken,'notification' => $notification,'data' => $requestData);
        
        $url = 'https://fcm.googleapis.com/fcm/send';
        
        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarily
        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);
        if($result === FALSE){
                die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);

        echo '<h2>Result</h2><hr/><h3>Request </h3><p><pre>';
        echo json_encode($fields,JSON_PRETTY_PRINT);
        echo '</pre></p><h3>Response </h3><p><pre>';
        echo $result;
        echo '</pre></p>';

        
    }
    
    public function actionCreateTenantPaymentForPg () {
//        $today = '2020-03-01';
        $today = date("Y-m-d");
        $daysInMonth = date('t');
        $monthstart = date("Y-m-d", (strtotime("first day of this month")));
        if (strtotime($today) <> strtotime($monthstart)) {
            //echo "Warning: Job execution must me executed on first day of the month";
            //exit;
        }
        $tenantAgreementModel = \app\models\TenantAgreements::find()->where('lease_start_date < :lease_start_date', [':lease_start_date' => $today])  
            ->andWhere('property_type = :property_type', [':property_type' => 5])
            ->andWhere('lease_end_date >= :lease_end_date OR lease_end_date IS NULL',[':lease_end_date' => $today])
            ->all();
                
        //->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql;
        echo 'Count Recordset: ' . count($tenantAgreementModel); 
        
        if (count($tenantAgreementModel) > 0) {
            foreach ($tenantAgreementModel as $row) {
                $month = date('m-Y', strtotime($today));
                $tenantPaymentRow = \app\models\TenantPayments::find()->where(['tenant_id' => $row->tenant_id])
                    ->andWhere(['property_id' => $row->property_id])
                    ->andWhere(['month' => $month])
                    ->one();
                if (empty($tenantPaymentRow)) {
                    $tpRow = new \app\models\TenantPayments();
     
                    if ( empty($row->lease_end_date) || (date('Ymd', strtotime($row->lease_end_date)) >= date('Ymt', strtotime($today)))) {
                        $coverageDays = date('t', strtotime($today));
                    } else { 
                        $coverageDays = date("d",strtotime($row->lease_end_date));
                    }
                    $rentForMonth = (double) round($row->rent * $coverageDays / $daysInMonth, 2);
                    $maintenanceForMonth = (double) round($row->maintainance * $coverageDays / $daysInMonth, 2);
                    $totalAmount = round(($rentForMonth + $maintenanceForMonth), 2);
                    
                    $tpRow->tenant_id = $row->tenant_id;
                    $tpRow->property_id = $row->property_id;
                    $tpRow->parent_id = $row->property_id;
                    $tpRow->payment_des = "PG Rent for the month of " . date("M-Y");;
                    $tpRow->payment_type = 2;
                    $tpRow->payment_status = 0;
                    $tpRow->created_date = $today;
                    $tpRow->created_by = 1;
                    $tpRow->penalty_amount = 0;
                    $tpRow->adhoc = 0;
                    $tpRow->due_date = date('Y-m-', strtotime($today)) . Yii::$app->params['penalty_waiver'];
                    $tpRow->agreement_id = $row->id;
                    $tpRow->month = $month;
                    $tpRow->original_amount = $rentForMonth;
                    $tpRow->owner_amount = $rentForMonth;
                    $tpRow->maintenance = $maintenanceForMonth;
                    $tpRow->total_amount = $totalAmount;
                    $tpRow->tenant_amount = $rentForMonth;
                    $tpRow->days_of_rent = $coverageDays;
                    $tpRow->updated_by = 1;
                    $tpRow->updated_date = date('Y-m-d H:i:s');
                    $tpRow->save(false);
                    
                }
            }
        }
        echo "Finish Process" . PHP_EOL;
        $paramsModel = \app\models\SystemConfig::find()->where(['name' => 'PGPROCESS'])->one();
        if (!empty($paramsModel)) {
            echo "Update PARAMS" . PHP_EOL;
            $paramsModel->value = date("Y-m-01");
            $paramsModel->update(false);
        }
    }
    
    public function actionGetPaytmTransactionStatus () {
        $tranStatusUrl = (Yii::$app->params['paytm_env'] == 'dev') ? Yii::$app->params['paytm_txn_status_dev'] : Yii::$app->params['paytm_txn_status_prod'];
        $paytmParams = array();
        
        $comp_date = date('Y-m-d H:i:s', strtotime("-2 days"));
        
        $sql = 'SELECT order_id from pgi_interface where auth_status IS NULL AND order_id != "" AND request_time < "' . $comp_date . '" ';

        $recordSet = Yii::$app->db->createCommand($sql)->queryAll();
        
        if (!empty($recordSet)) {
            foreach ($recordSet as $row) {
                if (empty($row['order_id'])) {
                    continue;
                }
                /* body parameters */
                $paytmParams["body"] = array(
                    "mid" => Yii::$app->params['paytm_merchant_id'],
                    "orderId" => $row['order_id'],
                );

                $checksum = Yii::$app->paytm->getChecksumFromArray($paytmParams["body"], Yii::$app->params['paytm_merchant_key']);

                /* head parameters */
                $paytmParams["head"] = array(
                    "signature"	=> $checksum
                );

                $post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);

                $ch = curl_init($tranStatusUrl);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                $response = curl_exec($ch);
                $error = curl_error($ch);
                curl_close($ch);

                $error_code = [
                    400, 402, 501
                ];

                $resp_array = json_decode($response);
                if (!empty($resp_array) && empty($error)) {
                    if (!in_array($resp_array->body->resultInfo->resultCode, $error_code)) {
                        //SAVE to DB
                        $model = \app\models\PaymentGateway::find()->where(['order_id' => $row['order_id']])->one();
                        if ($resp_array->body->resultInfo->resultStatus = "TXN_FAILURE") {
                            $model->auth_status = $resp_array->body->resultInfo->resultStatus;
                            $model->description = $resp_array->body->resultInfo->resultMsg;
                            $model->update(false);
                        } else if ($resp_array->body->resultInfo->resultStatus = "TXN_SUCCESS") {
                            $model->payment_status = 1;
                            $model->response_time = $resp_array->body->txnDate;
                            $model->txn_reference_no = $resp_array->body->txnId;
                            $model->bank_reference_no = $resp_array->body->bankTxnId;
                            $model->bank_id = $resp_array->body->bankName;
                            $model->currency_name = "INR";
                            $model->auth_status = $resp_array->body->resultInfo->resultStatus;
                            $model->description = $resp_array->body->resultInfo->resultMsg;
                            $model->payment_mode = $resp_array->body->paymentMode;
                            $model->gateway_name = $resp_array->body->gatewayName;
                            $model->update(false);
                        }
                    }
                }

                echo PHP_EOL;
                echo date('Y-m-d H:i:s') . " FOR ORDER ID: " . $paytmParams["body"]["orderId"];
                echo PHP_EOL;
                echo (empty($response)) ? $error : $response;
                echo PHP_EOL;
                echo PHP_EOL;
            }
        } else {
            echo PHP_EOL;
            echo "No record found today " . date('Y-m-d H:i:s');
            echo PHP_EOL;
            echo PHP_EOL;
        }
    }
}
