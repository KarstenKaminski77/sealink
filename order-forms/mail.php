<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../Connections/inv.php');
require_once('../Connections/seavest.php');
require_once('../functions/functions.php');
require '../PHPMailer/PHPMailerAutoload.php';

$query_Recordset1 = "
	SELECT
		tbl_orders.OrderNo,
		tbl_orders.Supplier,
		tbl_order_details.Qty,
		tbl_order_details.Description,
		tbl_orders.TechnicianId,
		tbl_orders.Date,
		tbl_orders.Account,
		tbl_orders.SiteId,
		tbl_orders.JobNo,
		tbl_orders.`Issuer`,
		tbl_order_relation.OrderId,
		tbl_orders.IssuerId,
		tbl_orders.RequestorId,
		tbl_technicians.Cell AS RequestorTel,
		tbl_users.Telephone AS IssuerTel
	FROM
		(
			(
				tbl_orders
				LEFT JOIN tbl_order_relation ON tbl_order_relation.OrderId = tbl_orders.Id
			)
			LEFT JOIN tbl_order_details ON tbl_order_details.Id = tbl_order_relation.ItemId
		)
	LEFT JOIN tbl_technicians ON tbl_orders.RequestorId = tbl_technicians.Id
	LEFT JOIN tbl_users ON tbl_orders.IssuerId = tbl_users.Id
	WHERE
		tbl_order_relation.OrderId = '". intval($_GET['Id']) ."'";

select_db();
$Recordset1 = mysqli_query($con, $query_Recordset1) or die(mysqli_error($con));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);

$KTColParam1_Recordset2 = "0";
if (isset($_GET["Id"])) {
  $KTColParam1_Recordset2 = $_GET["Id"];
}

$query_Recordset2 = "SELECT tbl_orders.OrderNo, tbl_order_details.Qty, tbl_order_details.Description, tbl_order_details.Amount, tbl_orders.TechnicianId, tbl_orders.SiteId, tbl_orders.JobNo, tbl_orders.Issuer, tbl_orders.Account, tbl_order_relation.OrderId FROM (((tbl_orders LEFT JOIN tbl_order_relation ON tbl_order_relation.OrderId=tbl_orders.Id) LEFT JOIN tbl_order_details ON tbl_order_details.Id=tbl_order_relation.ItemId) LEFT JOIN tbl_suppliers ON tbl_suppliers.Id=tbl_orders.SupplierId) WHERE tbl_order_relation.OrderId = $KTColParam1_Recordset2";
$Recordset2 = mysqli_query($con,$query_Recordset2) or die(mysqli_error($con));
$row_Recordset2 = mysqli_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysqli_num_rows($Recordset2);

$query = mysqli_query($con, "SELECT Address FROM tbl_suppliers WHERE Name = '". $row_Recordset1['Supplier'] ."'")or die(mysqli_error($con));
$row = mysqli_fetch_array($query);


$email = $_POST['email'];
$from = "Seavest Africa <control@seavest.co.za>";
$subject = "Seavest Order Form ".$row_Recordset1['OrderNo'];
$amount = '';

$items = '';

do {

    $amount = '';

    if($row_Recordset2['Amount'] > 0.00){

        $amount = $row_Recordset2['Amount'];
    }

    $items .= '
    <tr>
      <td width="30" align="center" class="td-order-form-qty"><div id="field-padding">'. $row_Recordset2['Qty'] .'</div></td>
      <td class="td-order-form"><div id="field-padding" style="padding-left:5px">'. $row_Recordset2['Description'] .'</div></td>
      <td class="td-order-form"><span style="padding-left:5px">
        '. $amount .'
      </span></td>
    </tr>';
} while ($row_Recordset2 = mysqli_fetch_assoc($Recordset2));


$message = '<table width="660" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td><form name="form2" method="post" action="order-form.php?Id='. $_GET['Id'] .'">
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="combo">
          <tr>
            <td>
              <table width="660" border="0" cellpadding="0" cellspacing="0" class="combo">
                <tr>
                  <td colspan="3" valign="top"><img src="images/quote-banner.jpg" width="660" height="155" /></td>
                  </tr>
                <tr>
                  <td valign="top">&nbsp;</td>
                  <td valign="top">&nbsp;</td>
                  <td valign="top">&nbsp;</td>
                  </tr>
                <tr>
                  <td colspan="3" align="center" valign="top">&nbsp;</td>
                  </tr>
                <tr>
                  <td colspan="3" align="center" valign="top"><strong class="logout">ORDER NO: '. $row_Recordset1['OrderNo'] .'</strong></td>
                  </tr>
                <tr>
                  <td valign="top">&nbsp;</td>
                  <td valign="top">&nbsp;</td>
                  <td valign="top">&nbsp;</td>
                  </tr>
                <tr>
                  <td width="33%" valign="top">&nbsp;</td>
                  <td width="33%" valign="top">&nbsp;</td>
                  <td width="33%" valign="top">&nbsp;</td>
                  </tr>
                <tr>
                  <td colspan="3" valign="top">
                    <div style="border-bottom:solid 1px #A6CAF0; width:660px">
                      <table width="660" border="0" cellpadding="0" cellspacing="0" class="combo">
                        <tr>
                          <td width="67" class="td-order-form-qty"><strong>Supplier</strong></td>
                          <td width="547" class="td-order-form"><div style="padding-left:5px">'. $row_Recordset1['Supplier'] . $row_Recordset1['Account'] .'</div></td>
                          </tr>
                        <tr>
                          <td valign="top" class="td-order-form-qty"><strong>Address</strong></td>
                          <td class="td-order-form"><div style="padding-left:5px">'. nl2br($row['Address']) .'</div></td>
                          </tr>
                        <tr>
                          <td class="td-order-form-qty"><strong>Site</strong></td>
                          <td class="td-order-form"><div style="padding-left:5px">'. $row_Recordset1['SiteId'] .'</div></td>
                          </tr>
                        <tr>
                          <td class="td-order-form-qty"><strong>Job No.</strong></td>
                          <td class="td-order-form"><div style="padding-left:5px">'. $row_Recordset1['JobNo'] .'</div></td>
                          </tr>
                        <tr>
                          <td class="td-order-form-qty"><strong>Date</strong></td>
                          <td class="td-order-form"><div style="padding-left:5px">'. $row_Recordset1['Date'] .'</div></td>
                          </tr>
                        </table>
                      </div>
                    </td>
                  </tr>
              </table></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td height="17">&nbsp;</td>
          </tr>
          <tr>
            <td><div id="request">Please supply  the following without any additions or alterations:</div></td>
          </tr>
          <tr>
            <td><div style="border-bottom:solid 1px #A6CAF0; width:660px">
              <table width="660" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="30" align="center" class="td-order-form-qty"><strong>Qty</strong></td>
                  <td width="555" class="td-order-form"><strong style="padding-left:5px">Description</strong></td>
                  <td width="75" class="td-order-form"><strong>Amount</strong></td>
                </tr>
                '. $items .'
              </table>
            </div></td>
          </tr>
        </table>
        <table width="660" border="0" cellpadding="0" cellspacing="1" class="combo">
          <tr>
            <td>&nbsp;</td>
            <td colspan="2">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            <td colspan="2">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            <td colspan="2">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            <td colspan="2">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            <td colspan="2">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td><div id="field-padding"><strong>Requestor</strong></div></td>
            <td><div id="field-padding" style="padding-left:5px">'. $row_Recordset1['TechnicianId'] .'</div></td>
            <td valign="bottom"><div style="padding-left:5px">..................................................</div></td>
            <td><div id="field-padding" style="padding-left:10px"><strong>Issuer</strong></div></td>
            <td><div id="field-padding" style="padding-left:5px">'. $row_Recordset1['Issuer'] .'</div></td>
            <td valign="bottom"><div style="padding-left:5px">..................................................</div></td>
            </tr>
            <tr>
              <td><div id="field-padding"><strong>Telephone</strong></div></td>
              <td><div id="field-padding" style="padding-left:5px">'. $row_Recordset1['RequestorTel'] .'</div></td>
              <td valign="bottom">&nbsp;</td>
              <td><div id="field-padding"><strong>Telephone</strong></div></td>
              <td><div id="field-padding" style="padding-left:5px">'. $row_Recordset1['IssuerTel'] .'</div></td>
              <td valign="bottom">&nbsp;</td>
            </tr>
        </table>
      </form></td>
    </tr>
  </table>';


//Create a new PHPMailer instance
$mail = new PHPMailer;
//Tell PHPMailer to use SMTP
$mail->isSMTP();
//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 0;
//Ask for HTML-friendly debug output
$mail->Debugoutput = 'html';
//Set the hostname of the mail server
$mail->Host = "dedi133.jnb2.host-h.net";
//Set the SMTP port number - likely to be 25, 465 or 587
$mail->Port = 587;
//Whether to use SMTP authentication
$mail->SMTPAuth = true;
//Username to use for SMTP authentication
$mail->Username = "control@seavest.co.za";
//Password to use for SMTP authentication
$mail->Password = "Durbanu2062";
//Set who the message is to be sent from
$mail->setFrom('control@seavest.co.za', 'Seavest Africa');
//Set an alternative reply-to address
$mail->addReplyTo('control@seavest.co.za', 'Seavest Africa');
//Set who the message is to be sent to
$mail->addAddress($email);
//$mail->addCC('marcus.abrahams@seavest.co.za', 'Seavest Africa');
//Set the subject line
$mail->Subject = $subject;
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$mail->msgHTML($html = $message);
//Replace the plain text body with one created manually
$mail->AltBody = '';
//Attach an image file
//$mail->addAttachment($_POST['file'][$i]);

//send the message, check for errors
if($mail->send()) {

    echo  '<div id="banner-success">mail sent!..</div>';

} else {

        echo  '<div id="banner-error">mail could not be sent!..</div>';

}


?>
