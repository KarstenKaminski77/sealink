<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
require_once('../functions/functions.php');

select_db();

$query = mysqli_query($con,"SELECT * FROM tbl_orders ORDER BY Id DESC LIMIT 1")or die(mysqli_error($con));
$row = mysqli_fetch_array($query);

$orderno = 'CV'.($row['Id'] + 1);

mysqli_query($con,"INSERT INTO tbl_orders (OrderNo,Issuer,IssuerId) VALUES ('$orderno','". $_COOKIE['name'] ."','". $_COOKIE['userid'] ."')")or die(mysqli_error($con));

		mysqli_query($con,"INSERT INTO tbl_order_details (Qty) VALUES ('1')")or die(mysqli_error($con));

	   $query2 = mysqli_query($con,"SELECT * FROM tbl_order_details ORDER BY Id DESC LIMIT 1")or die(mysqli_error($con));
	   $row2 = mysqli_fetch_array($query2);

	   $query3 = mysqli_query($con,"SELECT * FROM tbl_orders ORDER BY Id DESC LIMIT 1")or die(mysqli_error($con));
       $row3 = mysqli_fetch_array($query3);

       $id = $row3['Id'];


	   $item_id = $row2['Id'];
	   $order_id = $row3['Id'];

	   mysqli_query($con,"INSERT INTO tbl_order_relation (OrderId,ItemId) VALUES ('$order_id','$item_id')")or die(mysqli_error($con));

header('Location: order-form.php?Id='.$id);
?>
