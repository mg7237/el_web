<?php

include('../config.php');
error_reporting(0);
if (!isset($_REQUEST['user_id']) || empty($_REQUEST['user_id'])) {
    $dData['status'] = "false";
    $dData['message'] = "Please Provide user id";
    echo json_encode($dData);
    die;
} else if (!isset($_REQUEST['order_detail']) || empty($_REQUEST['order_detail'])) {
    $dData['status'] = "false";
    $dData['message'] = "Please Provide order_detail";
    echo json_encode($dData);
    die;
} else {
    $userId = $_REQUEST['user_id'];
    $productDetail = $_REQUEST['order_detail'];
    $datas = explode(",", $productDetail);
    $userData = getEmail($con, $userId);

    foreach ($datas as $value) {
        $bag = explode('-', $value);
        $bagId = $bag[1];
        $deleteData = "DELETE FROM user_bag WHERE id = '" . $bagId . "'";
        $runqueryS = mysqli_query($con, $deleteData);
    }
    //die;   
    $insert = "INSERT INTO order_detail SET user_id = '" . $userId . "',order_detail = '" . $productDetail . "'";
    $runQuery = mysqli_query($con, $insert);
    $orderId = mysqli_insert_id($con);
    $dData['status'] = "true";
    $dData['message'] = "succesfully Saved";
    $dData['order_id'] = $orderId;

    $product .= '<table border=1 CELLPADDING=5  style="background: rgb(217, 203, 215) none repeat scroll 0px 0px;">  <tr> <th> No.</th> <th>Department Name</th>  <th>Category Name</th>  <th>Sub Category Name</th>  <th>Style Description</th>  <th>Color/Size</th>  <th>MRP</th>  <th>Set Price</th>  <th>Quantity</th> <th>Total Price</th> </tr>';
    $count = 0;
    $productDetailAray = explode(',', $productDetail);
    foreach ($productDetailAray as $record) {
        $records = explode('-', $record);

        $subcatproduct = "SELECT * FROM  product WHERE id=" . $records[0];
        $sql = mysqli_query($con, $subcatproduct);
        if (mysqli_num_rows($sql) > 0) {

            $product .= '<tr>';
            $product .= "<td> $count </td>";
            $result = mysqli_fetch_object($sql);

            $product .= ' <td>  ' . getBrands($con, $result->department_id) . ' </td>';
            $product .= ' <td> ' . getproducts($con, $result->parent_category_id) . '  </td>';
            $product .= ' <td>  ' . getsubcat($con, $result->sub_category_id) . '  </td>';
            $product .= ' <td>  ' . $result->style_description . '  </td>';
            $product .= ' <td>  ' . $result->color . '/' . $result->size . '  </td>';
            $product .= ' <td>  ' . $result->MRP . '  </td>';
            $product .= ' <td>  ' . $result->basic_rate . '  </td> ';
            $product .= ' <td>  ' . $records[2] . '  </td>';
            $product .= ' <td>  ' . $records[2] * $result->basic_rate . '  </td>';
            $product .= "</tr>";

            $count ++;
        }
    }




    $body .= '<img src="http://ahujabrothers.insonix.com/img/logo_web.png"> <br /><br />';
    $body .= 'Hello Admin<br /><br />';
    $body .= 'You have got an order from ' . $userData->name . ' in your aplication: <br /><br /> <b>Order Details </b> <br /><br />';
    $body .= "Name : " . $userData->name . "  <br />Order Id :  #" . $orderId . "  <br />Phone Number :  " . $userData->phone . "  <br /> Email : " . $userData->email . "  <br /> Order Date : " . date('Y-m-d') . "    <br /> <br />  <br />";
    $body .= $product;

    $body .= "<br /> Thanks";


    $toadmin = getEmailOfAdmin($con);

    $subject = "Order Details";
    $headers = "From: $userData->email" . "\r\n";

    mail($toadmin, $subject, $body, $headers);



    /*
     * user email 
     * */

    $body1 .= '<img src="http://ahujabrothers.insonix.com/img/logo_web.png"> <br /><br />';
    $body1 .= 'Hello ' . $userData->name . '<br /><br />';
    $body1 .= 'Your order details in Ahuja Brothers : </b> <br /><br />';
    $body1 .= "Name : " . $userData->name . "  <br />Order Id :  #" . $orderId . "  <br />Phone Number :  " . $userData->phone . "  <br /> Email : " . $userData->email . "  <br /> Order Date : " . date('Y-m-d') . "    <br />   <br />  <br />";
    $body1 .= $product;

    $body1 .= "<br /> Thanks";


    $to = $userData->email;
    $subject = "Order Details";
    $headers = "From: support@insonix.com" . "\r\n";


    mail($to, $subject, $body1, $headers);



    echo json_encode($dData);
    die;
}

function getBrands($con, $id) {
    $depratment_list = "SELECT department_name  FROM  department WHERE id ='" . $id . "'";
    $query = mysqli_query($con, $depratment_list);
    $data = mysqli_fetch_object($query);
    return $data->department_name;
}

function getproducts($con, $id) {
    $product_list = "SELECT category_name  FROM  category WHERE id ='" . $id . "'";
    $sql = mysqli_query($con, $product_list);
    $result = mysqli_fetch_object($sql);
    return $result->category_name;
}

function getsubcat($con, $id) {
    $product_list = "SELECT sub_category_name  FROM  sub_category WHERE id ='" . $id . "'";
    $sql = mysqli_query($con, $product_list);
    $result = mysqli_fetch_object($sql);
    return $result->sub_category_name;
}

function getEmail($con, $user_id) {
    $product_list = "SELECT *  FROM  users WHERE id ='" . $user_id . "'";
    $sql = mysqli_query($con, $product_list);
    $result = mysqli_fetch_object($sql);
    return $result;
}

function getEmailOfAdmin($con) {
    $product_list = "SELECT *  FROM  users WHERE username ='admin'";
    $sql = mysqli_query($con, $product_list);
    $result = mysqli_fetch_object($sql);
    return $result->email;
}

function getVat($delivery_rate, $vat) {
    $rate = $delivery_rate * $vat;
    $vatValue = $rate / 100;
    return $vatValue;
}

?>
