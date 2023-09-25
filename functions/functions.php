<?php
header("Location: https://sealink.reimaginedstudios.co.za/inv/login.php");
require_once($_SERVER['DOCUMENT_ROOT'] . '/inv/functions/constants.php');

if (ENVIRONMENT == 'PRD') {

	$host = 'sql15.jnb1.host-h.net';
	$user = 'kwdaco_333';
	$pass  = 'SBbB38c8Qh8';
	$db = 'seavest_db333';

	$domain = '.seavest.co.za';

	function select_db()
	{

		mysqli_connect('sql15.jnb1.host-h.net', 'kwdaco_333', 'SBbB38c8Qh8', 'seavest_db333');
	}
} else if (ENVIRONMENT == 'STG') {

	$host = '127.0.0.1';
	$user = 'laraveluser';
	$pass  = '#@$$F@CE123';
	$db = 'seavest';

	$domain = '.reimaginedstudios.co.za';

	function select_db()
	{

		mysqli_connect('127.0.0.1', 'laraveluser', '#@$$F@CE123', 'seavest') or die(mysql_error());
	}
} else {

	$host = '127.0.0.1';
	$user = 'root';
	$pass  = 'root';
	$db = 'seavest';

	$domain = '.127.0.0.1';

	function select_db()
	{

		mysqli_connect('127.0.0.1', 'root', 'root', 'seavest') or die(mysql_error());
	}
}

global $con;
$con = mysqli_connect($host, $user, $pass, $db);

function sum_labour($jobid)
{
	$query = mysqli_query($con, "SELECT SUM(Total1) FROM tbl_jc WHERE JobId = '$jobid' AND Labour = '1'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	$sumlabour = $row['SUM(Total1)'];
	echo $sumlabour;
}
function sum_labour_quote($jobid)
{
	$query = mysqli_query($con, "SELECT SUM(Total3) FROM tbl_jc WHERE JobId = '$jobid' AND Labour1 = '1'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	$sumlabour = $row['SUM(Total3)'];
	echo $sumlabour;
}
function sum_material($jobid)
{
	$query = mysqli_query($con, "SELECT SUM(Total1) FROM tbl_jc WHERE JobId = '$jobid' AND Material = '1'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	$summaterial = $row['SUM(Total1)'];
	echo $summaterial;
}
function sum_material_quote($jobid)
{
	$query = mysqli_query($con, "SELECT SUM(Total3) FROM tbl_jc WHERE JobId = '$jobid' AND Material1 = '1'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	$summaterial = $row['SUM(Total3)'];
	echo $summaterial;
}

function sum_transport($jobid, $companyid)
{

	if ($companyid == 1) {

		$query = mysqli_query($con, "SELECT SUM(Total1) FROM tbl_travel WHERE JobId = '$jobid'") or die(mysqli_error($con));
		$row = mysqli_fetch_array($query);
		$sumtransport = $row['SUM(Total1)'];

		echo $sumtransport;
	}

	if ($companyid == 2) {

		$query = mysqli_query($con, "SELECT SUM(TotalPragma) FROM tbl_travel WHERE JobId = '$jobid'") or die(mysqli_error($con));
		$row = mysqli_fetch_array($query);
		$sumtransport = $row['SUM(TotalPragma)'];

		echo $sumtransport;
	}

	if ($companyid == 3) {

		$query = mysqli_query($con, "SELECT SUM(Total1) FROM tbl_travel WHERE JobId = '$jobid'") or die(mysqli_error($con));
		$row = mysqli_fetch_array($query);
		$sumtransport = $row['SUM(Total1)'];

		echo $sumtransport;
	}

	if ($companyid == 4) {

		$query = mysqli_query($con, "SELECT SUM(Total1) FROM tbl_travel WHERE JobId = '$jobid'") or die(mysqli_error($con));
		$row = mysqli_fetch_array($query);
		$sumtransport = $row['SUM(Total1)'];

		echo $sumtransport;
	}

	if ($companyid == 5) {

		$query = mysqli_query($con, "SELECT SUM(Total1) FROM tbl_travel WHERE JobId = '$jobid'") or die(mysqli_error($con));
		$row = mysqli_fetch_array($query);
		$sumtransport = $row['SUM(Total1)'];

		echo $sumtransport;
	}

	if ($companyid == 6) {

		$query = mysqli_query($con, "SELECT SUM(Total1) FROM tbl_travel WHERE JobId = '$jobid'") or die(mysqli_error($con));
		$row = mysqli_fetch_array($query);
		$sumtransport = $row['SUM(Total1)'];

		echo $sumtransport;
	}

	if ($companyid == 10) {

		$query = mysqli_query($con, "SELECT SUM(Total1) FROM tbl_travel WHERE JobId = '$jobid'") or die(mysqli_error($con));
		$row = mysqli_fetch_array($query);
		$sumtransport = $row['SUM(Total1)'];

		echo $sumtransport;
	}
}

function sum_transport_quote($jobid)
{
	$query = mysqli_query($con, "SELECT SUM(SubTotal1) FROM tbl_jc WHERE JobId = '$jobid' AND Transport1 = '1'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	$sumtransport = $row['SUM(SubTotal1)'];
	echo $sumtransport;
}

function sum_total($jobid, $companyid)
{

	$query = mysqli_query($con, "SELECT SUM(Total1) FROM tbl_jc WHERE JobId = '$jobid' AND Labour = '1'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);

	$subtotal_l = $row['SUM(Total1)'];

	$query = mysqli_query($con, "SELECT SUM(Total1) FROM tbl_jc WHERE JobId = '$jobid' AND Material = '1'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);

	$subtotal_m = $row['SUM(Total1)'];

	if ($companyid == 1) {

		$query = mysqli_query($con, "SELECT SUM(Total1) FROM tbl_travel WHERE JobId = '$jobid'") or die(mysqli_error($con));
		$row = mysqli_fetch_array($query);

		$subtotal_t = $row['SUM(Total1)'];
		$subtotal = $subtotal_l + $subtotal_m + $subtotal_t;

		echo $subtotal;
	}

	if ($companyid == 2) {

		$query = mysqli_query($con, "SELECT SUM(TotalPragma) FROM tbl_travel WHERE JobId = '$jobid'") or die(mysqli_error($con));
		$row = mysqli_fetch_array($query);

		$subtotal_t = $row['SUM(TotalPragma)'];
		$subtotal = $subtotal_l + $subtotal_m + $subtotal_t;

		echo $subtotal;
	}

	if ($companyid == 3) {

		$query = mysqli_query($con, "SELECT SUM(Total1) FROM tbl_travel WHERE JobId = '$jobid'") or die(mysqli_error($con));
		$row = mysqli_fetch_array($query);

		$subtotal_t = $row['SUM(Total1)'];
		$subtotal = $subtotal_l + $subtotal_m + $subtotal_t;

		echo $subtotal;
	}

	if ($companyid == 4) {

		$query = mysqli_query($con, "SELECT SUM(Total1) FROM tbl_travel WHERE JobId = '$jobid'") or die(mysqli_error($con));
		$row = mysqli_fetch_array($query);

		$subtotal_t = $row['SUM(Total1)'];
		$subtotal = $subtotal_l + $subtotal_m + $subtotal_t;

		echo $subtotal;
	}

	if ($companyid == 5) {

		$query = mysqli_query($con, "SELECT SUM(Total1) FROM tbl_travel WHERE JobId = '$jobid'") or die(mysqli_error($con));
		$row = mysqli_fetch_array($query);

		$subtotal_t = $row['SUM(Total1)'];
		$subtotal = $subtotal_l + $subtotal_m + $subtotal_t;

		echo $subtotal;
	}

	if ($companyid == 6) {

		$query = mysqli_query($con, "SELECT SUM(Total1) FROM tbl_travel WHERE JobId = '$jobid'") or die(mysqli_error($con));
		$row = mysqli_fetch_array($query);

		$subtotal_t = $row['SUM(Total1)'];
		$subtotal = $subtotal_l + $subtotal_m + $subtotal_t;

		echo $subtotal;
	}

	if ($companyid == 10) {

		$query = mysqli_query($con, "SELECT SUM(Total1) FROM tbl_travel WHERE JobId = '$jobid'") or die(mysqli_error($con));
		$row = mysqli_fetch_array($query);

		$subtotal_t = $row['SUM(Total1)'];
		$subtotal = $subtotal_l + $subtotal_m + $subtotal_t;

		echo $subtotal;
	}
}

function jc_current($con, $style = true)
{

	if ($_SESSION['kt_login_level'] != 0) {

		if (isset($_SESSION['areaid'])) {

			$areaid = $_SESSION['areaid'];
		} else {

			$areaid = 1;
		}
	} else {

		$areaid = $_SESSION['kt_AreaId'];
	}

	$where = ($areaid != 5) ? "AND AreaId = '" . $areaid . "'" : '';

	if (isset($_SESSION['system'])) {

		$where .= ($_SESSION['system'] != 3) ? ' AND SystemId = ' . $_SESSION['system'] : '';
	}

	$query = mysqli_query($con, "SELECT * FROM tbl_jc WHERE Status = '2' $where GROUP BY JobId") or die(mysqli_error($con));
	$numrows = mysqli_num_rows($query);

	if ($style) {
		echo '<span class="counter-bg">' . $numrows . '</span>';
	} else {
		return $numrows;
	}
}

function jc_costing($con)
{

	$query = mysqli_query($con, "SELECT * FROM tbl_jc WHERE Status = '3' GROUP BY JobId") or die(mysqli_error($con));
	$numrows = mysqli_num_rows($query);

	echo '<span class="counter-bg">' . $numrows . '</span>';
}

function jc_paperwork($con, $style = true)
{

	$where = '';

	if (isset($_SESSION['system'])) {

		$where .= ($_SESSION['system'] != 3) ? ' AND SystemId = ' . $_SESSION['system'] : '';
	}

	$query = mysqli_query($con, "SELECT * FROM tbl_jc WHERE Status = '20' $where GROUP BY JobId") or die(mysqli_error($con));
	$numrows = mysqli_num_rows($query);

	if ($style) {
		echo '<span class="counter-bg">' . $numrows . '</span>';
	} else {
		echo $numrows;
	}
}

function jc_awaiting_on($con)
{

	$query = mysqli_query($con, "SELECT * FROM tbl_jc WHERE Status = '17' GROUP BY JobId") or die(mysqli_error($con));
	echo '<span class="counter-bg">' . mysqli_num_rows($query) . '</span>';
}
function jc_awaiting_pre_work_on($con, $style = true)
{

	$where = '';

	if (isset($_SESSION['system'])) {

		$where .= ($_SESSION['system'] != 3) ? ' AND SystemId = ' . $_SESSION['system'] : '';
	}

	$query = mysqli_query($con, "SELECT * FROM tbl_jc WHERE Status = '19' $where GROUP BY JobId") or die(mysqli_error($con));

	if ($style) {
		echo '<span class="counter-bg">' . mysqli_num_rows($query) . '</span>';
	} else {
		echo mysqli_num_rows($query);
	}
}

function jc_rework($con, $style = true)
{

	$where = '';

	if (isset($_SESSION['system'])) {

		$where .= ($_SESSION['system'] != 3) ? ' AND SystemId = ' . $_SESSION['system'] : '';
	}

	$query = mysqli_query($con, "SELECT * FROM tbl_jc WHERE Status = '21' $where GROUP BY JobId") or die(mysqli_error($con));

	if ($style) {
		echo '<span class="counter-bg">' . mysqli_num_rows($query) . '</span>';
	} else {
		echo mysqli_num_rows($query);
	}
}

function awaiting_estimate($con, $style = true)
{

	$where = '';

	if (isset($_SESSION['system'])) {

		$where .= ($_SESSION['system'] != 3) ? ' AND SystemId = ' . $_SESSION['system'] : '';
	}

	$query = mysqli_query($con, "SELECT * FROM tbl_jc WHERE Status = 24 $where GROUP BY JobId") or die(mysqli_error($con));

	if ($style) {
		echo '<span class="counter-bg">' . mysqli_num_rows($query) . '</span>';
	} else {
		echo mysqli_num_rows($query);
	}
}

function jc_total_costing($con)
{

	$where = '';

	if (isset($_SESSION['system'])) {

		$where .= ($_SESSION['system'] != 3) ? ' AND SystemId = ' . $_SESSION['system'] : '';
	}

	$query = mysqli_query($con, "SELECT * FROM tbl_jc WHERE Status = '18' $where GROUP BY JobId") or die(mysqli_error($con));

	echo '<span class="counter-bg">' . mysqli_num_rows($query) . '</span>';
}

function jc_onhold($con, $style = true)
{

	if ($_SESSION['kt_login_level'] != 0) {

		if (isset($_SESSION['areaid'])) {

			$areaid = $_SESSION['areaid'];
		} else {

			$areaid = 1;
		}
	} else {

		$areaid = $_SESSION['kt_AreaId'];
	}

	$where = ($areaid != 5) ? "AND AreaId = '" . $areaid . "'" : '';

	if (isset($_SESSION['system'])) {

		$where .= ($_SESSION['system'] != 3) ? ' AND SystemId = ' . $_SESSION['system'] : '';
	}

	$query = mysqli_query($con, "SELECT * FROM tbl_jc WHERE Status = '2' $where GROUP BY JobId") or die(mysqli_error($con));
	$numrows = mysqli_num_rows($query);

	if ($numrows >= 1) {

		if ($style) {
			echo '<span class="counter-bg">' . $numrows . '</span>';
		} else {
			echo $numrows;
		}
	}
}

function jc_archives($con, $style = true)
{

	if ($_SESSION['kt_login_level'] != 0) {

		if (isset($_SESSION['areaid'])) {

			$areaid = $_SESSION['areaid'];
		} else {

			$areaid = 1;
		}
	} else {

		$areaid = $_SESSION['kt_AreaId'];
	}

	$where = ($areaid != 5) ? "AND AreaId = '" . $areaid . "'" : '';

	if (isset($_SESSION['system'])) {

		$where .= ($_SESSION['system'] != 3) ? ' AND SystemId = ' . $_SESSION['system'] : '';
	}

	$query = mysqli_query($con, "SELECT Count(*) AS Count FROM tbl_jc WHERE Status >= '6' $where GROUP BY JobId") or die(mysqli_error($con));
	$numrows = mysqli_num_rows($query);

	if ($style) {
		echo '<span class="counter-bg">' . $numrows . '</span>';
	} else {
		echo $numrows;
	}
}

function pending_inv($con)
{
	$query = mysqli_query($con, "SELECT Count(*) AS Count FROM tbl_jc WHERE Status = '7' GROUP BY JobId") or die(mysqli_error($con));
	$numrows = mysqli_num_rows($query);
	if ($numrows >= 1) {
		echo '<span class="counter-bg">' . $numrows . '</span>';
	}
}
function approved_inv($con)
{
	$query = mysqli_query($con, "SELECT Count(*) AS Count FROM tbl_jc WHERE Status = '8' GROUP BY JobId") or die(mysqli_error($con));
	$numrows = mysqli_num_rows($query);
	if ($numrows >= 1) {
		echo '<span class="counter-bg">' . $numrows . '</span>';
	}
}
function archived_inv($con)
{
	$query = mysqli_query($con, "SELECT Count(*) AS Count FROM tbl_jc WHERE Status = '10' GROUP BY JobId") or die(mysqli_error($con));
	$numrows = mysqli_num_rows($query);
	if ($numrows >= 1) {
		echo '<span class="counter-bg">' . $numrows . '</span>';
	}
}
function paid_inv($con)
{
	$query = mysqli_query($con, "SELECT Count(*) AS Count FROM tbl_jc WHERE Status = 10 GROUP BY JobId") or die(mysqli_error($con));
	$numrows = mysqli_num_rows($query);
	if ($numrows >= 1) {
		echo '<span class="counter-bg">' . $numrows . '</span>';
	}
}
function awiting_order_inv($con)
{
	$query = mysqli_query($con, "SELECT Count(*) AS Count FROM tbl_jc WHERE Status = '7' GROUP BY JobId") or die(mysqli_error($con));
	$numrows = mysqli_num_rows($query);
	if ($numrows >= 1) {
		echo '<span class="counter-bg">' . $numrows . '</span>';
	}
}

function outbox($con)
{

	$sql_where = '';

	if (isset($_SESSION['system'])) {

		$sql_where .= ($_SESSION['system'] != 3) ? ' AND SystemId = ' . $_SESSION['system'] : '';
	}

	$query = mysqli_query($con, "SELECT Count(*) AS Count FROM tbl_jc WHERE Status = '11' $sql_where GROUP BY JobId") or die(mysqli_error($con));
	$numrows = mysqli_num_rows($query);

	if ($numrows >= 1) {

		echo '<span class="counter-bg">' . $numrows . '</span>';
	}
}
function sent($con)
{
	$query = "
		SELECT Count(*) AS Count 
		FROM tbl_sent_invoices 
		LEFT JOIN tbl_jc ON tbl_jc.JobId = tbl_sent_invoices.JobId 
		LEFT JOIN tbl_sites ON tbl_sites.Id = tbl_jc.SiteId 
		LEFT JOIN tbl_companies ON tbl_companies.Id = tbl_jc.CompanyId 
		LEFT JOIN tbl_areas ON tbl_areas.Id = tbl_jc.AreaId
		WHERE tbl_jc.Status = '12'
		AND tbl_jc.Total2 > '0'
		AND tbl_sent_invoices.InvoiceNo != '0'
		GROUP BY tbl_jc.JobId
	";
	$query = mysqli_query($con, $query) or die(mysqli_error($con));
	// $query = mysqli_query($con, "SELECT Count(*) AS Count FROM tbl_jc WHERE Status = '12' GROUP BY JobId") or die(mysqli_error($con));
	$numrows = mysqli_num_rows($query);
	if ($numrows >= 1) {
		echo '<span class="counter-bg">' . $numrows . '</span>';
	}
}

function quote_outbox($con)
{

	$sql_where = '';

	if (isset($_SESSION['system'])) {

		$sql_where .= ($_SESSION['system'] != 3) ? ' AND SystemId = ' . $_SESSION['system'] : '';
	}

	$query = mysqli_query($con, "SELECT SUM(QuoteNo) FROM tbl_qs WHERE Status = '1' $sql_where GROUP BY QuoteNo") or die(mysqli_error($con));
	$numrows = mysqli_num_rows($query);

	if ($numrows >= 1) {

		echo '<span class="counter-bg">' . $numrows . '</span>';
	}
}


function support_pending($con)
{

	$query = mysqli_query($con, "SELECT Count(*) AS Count FROM tbl_support WHERE Status = 'Pending'") or die(mysqli_error($con));
	$numrows = mysqli_num_rows($query);

	if ($numrows >= 1) {

		echo '<span class="counter-bg">' . $numrows . '</span>';
	}
}


function support_ack($con)
{

	$query = mysqli_query($con, "SELECT Count(*) AS Count FROM tbl_support WHERE Status = 'Acknowledged'") or die(mysqli_error($con));
	$numrows = mysqli_num_rows($query);

	if ($numrows >= 1) {

		echo '<span class="counter-bg">' . $numrows . '</span>';
	}
}

function support_current($con)
{

	$query = mysqli_query($con, "SELECT Count(*) AS Count FROM tbl_support WHERE Status = 'In Progress'") or die(mysqli_error($con));
	$numrows = mysqli_num_rows($query);

	if ($numrows >= 1) {

		echo '<span class="counter-bg">' . $numrows . '</span>';
	}
}


function support_archives($con)
{

	$query = mysqli_query($con, "SELECT Count(*) AS Count FROM tbl_support WHERE Status = 'Resolved'") or die(mysqli_error($con));
	$numrows = mysqli_num_rows($query);

	if ($numrows >= 1) {

		echo '<span class="counter-bg">' . $numrows . '</span>';
	}
}

function sm_bp_qued($con)
{

	$query = mysqli_query($con, "SELECT Count(*) AS Count FROM tbl_scheduled_maintenance WHERE Status = 'Qued'") or die(mysqli_error($con));
	$numrows = mysqli_num_rows($query);

	if ($numrows >= 1) {

		echo '<span class="counter-bg">' . $numrows . '</span>';
	}
}

function sm_bp_in_progress($con)
{

	$query = mysqli_query($con, "SELECT Count(*) AS Count FROM tbl_scheduled_maintenance WHERE Status = 'In Progress'") or die(mysqli_error($con));
	$numrows = mysqli_num_rows($query);

	if ($numrows >= 1) {

		echo '<span class="counter-bg">' . $numrows . '</span>';
	}
}

function sm_bp_complete($con)
{

	$query = mysqli_query($con, "SELECT Count(*) AS Count FROM tbl_scheduled_maintenance WHERE Status = 'Complete'") or die(mysqli_error($con));
	$numrows = mysqli_num_rows($query);

	if ($numrows >= 1) {

		echo '<span class="counter-bg">' . $numrows . '</span>';
	}
}


function schedule($jobid)
{

	$con = mysqli_connect('sql15.jnb1.host-h.net', 'kwdaco_333', 'SBbB38c8Qh8', 'seavest_db333');

	$query = mysqli_query($con, "SELECT Count(*) AS Count FROM tbl_jc WHERE JobId = '$jobid'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);

	$date1 = date("Y-m-d", time());
	$date2 = $row['Date2'];

	$difference = abs(strtotime($date2) - strtotime($date1));

	$days = round(((($difference / 60) / 60) / 24), 0);

	if (strtotime($date1) <= strtotime($date2)) {

		echo '<span class="combo"> ' . $days . ' days to go.</span>';
	}

	if (strtotime($date1) > strtotime($date2)) {

		echo '<span class="combo" style="color: #FF0000">You are running ' . $days . ' days behind!</span>';
	}
}

function prefix($company)
{
	$query = mysqli_query($con, "SELECT * FROM tbl_companies WHERE Id = '$company'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	$prefix = $row['Prefix'];

	$query = mysqli_query($con, "SELECT * FROM tbl_jobnumbers WHERE Prefix = '$prefix' ORDER BY Id DESC LIMIT 1") or die(mysqli_error($con));
	$numrows = mysqli_num_rows($query);
	$row = mysqli_fetch_array($query);
	if ($numrows == 0) {
		$jobnumber = $prefix . " 1";
		mysqli_query($con, "INSERT INTO tbl_jobnumbers (JobNo,Prefix) VALUES ('$jobnumber','$prefix')") or die(mysqli_error($con));
		header('Location: jc_ini.php?Id=1');
	} else {
		$jobnumber = $row['JobNo'];
		$old_jobno = explode(" ", $jobnumber);
		$new_jobno = $old_jobno[0] . " " . ($old_jobno[1] + 1);
		mysqli_query($con, "INSERT INTO tbl_jobnumbers (JobNo,Prefix) VALUES ('$new_jobno','$prefix')") or die(mysqli_error($con));
		header('Location: jc_ini.php?Id=' . ($old_jobno[1] + 1) . '');
	}
}

function vat($jobid)
{
	$query = mysqli_query($con, "SELECT tbl_sites.Id, tbl_sites.Name, tbl_jc.SubTotal, tbl_sites.VAT
FROM (tbl_jc
LEFT JOIN tbl_sites ON tbl_sites.Id=tbl_jc.SiteId)
WHERE JobId = '$jobid' AND InvoiceQ = '0'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	if ($row['VAT'] == 1) {
		$subtotal = $row['SubTotal'];
		$vat = ($subtotal / 100) * 14;
		mysqli_query($con, "UPDATE tbl_jc SET VAT = '$vat' WHERE JobId = '$jobid'") or die(mysqli_error($con));
		$query = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '$jobid'") or die(mysqli_error($con));
		$row = mysqli_fetch_array($query);
		$vat = $row['VAT'];
		echo 'R' . $vat;
	} else {
		echo '';
	}
}

function vat_quote($jobid)
{
	$query = mysqli_query($con, "SELECT SUM(VAT1) FROM tbl_jc WHERE JobId = '$jobid' AND InvoiceQ = '1'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	$vat = $row['SUM(VAT1)'];
	echo 'R' . $vat;
}

function subtotal_quote($jobid)
{
	$query = mysqli_query($con, "SELECT SUM(SubTotal1) FROM tbl_jc WHERE JobId = '$jobid' AND InvoiceQ = '1'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	$vat = $row['SUM(SubTotal1)'];
	echo 'R' . $vat;
}

function total_quote($jobid)
{
	$query = mysqli_query($con, "SELECT SUM(Total3) FROM tbl_jc WHERE JobId = '$jobid' AND InvoiceQ = '1'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	$subtotal = $row['SUM(Total3)'];

	$query = mysqli_query($con, "SELECT SUM(VAT1) FROM tbl_jc WHERE JobId = '$jobid' AND InvoiceQ = '1'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	$vat = $row['SUM(VAT1)'];

	$total = $subtotal + $vat;

	echo 'R' . $total;
}

function total($jobid)
{
	$query = mysqli_query($con, "SELECT tbl_sites.Id, tbl_sites.Name, tbl_jc.SubTotal, tbl_sites.VAT
FROM (tbl_jc
LEFT JOIN tbl_sites ON tbl_sites.Id=tbl_jc.SiteId)
WHERE JobId = '$jobid'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	$subtotal = $row['SubTotal'];
	if ($row['VAT'] == 1) {
		$vat = ($subtotal / 100) * 14;
	}
	$total = $subtotal + $vat;
	mysqli_query($con, "UPDATE tbl_jc SET Total2 = '$total' WHERE JobId = '$jobid'") or die(mysqli_error($con));
	$query = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '$jobid'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	$total = $row['Total2'];
	echo 'R' . $total;
}

function sum_outstanding($where)
{

	$query = mysqli_query($con, "SELECT * FROM tbl_jc $where GROUP BY JobId") or die(mysqli_error($con));
	while ($row = mysqli_fetch_array($query)) {

		if ($row['Total2'] != 0.00) {

			$subtotal = $row['Total2'];
		} else {

			$subtotal = $row['VatIncl'];
		}

		if (isset($_SESSION['subtotal'])) {

			$_SESSION['subtotal'] = $_SESSION['subtotal'] + $subtotal;
		} else {

			$_SESSION['subtotal'] = $subtotal;
		}
	}

	$subtotal = $_SESSION['subtotal'];

	echo 'R' . number_format($subtotal, 2, ".", ",");

	unset($_SESSION['subtotal']);
}

function sum_stats($con, $where)
{

	$_SESSION['subtotal'] = '';

	$query = mysqli_query($con, "SELECT * FROM tbl_jc WHERE (tbl_jc.Status >= '9' AND tbl_jc.Status <= '12') $where GROUP BY JobId") or die(mysqli_error($con));
	while ($row = mysqli_fetch_array($query)) {

		$subtotal = $row['Total2'];

		$_SESSION['subtotal'] = $_SESSION['subtotal'] + $subtotal;
	}

	$subtotal = $_SESSION['subtotal'];

	echo 'R' . number_format($subtotal, 2, ".", ",");

	unset($_SESSION['subtotal']);
}

function sum_approved($con)
{

	$query = mysqli_query($con, "SELECT Total2 FROM tbl_jc WHERE Status = '11' GROUP BY JobId") or die(mysqli_error($con));
	while ($row = mysqli_fetch_array($query)) {

		$subtotal = $row['Total2'];

		if (isset($_SESSION['subtotal'])) {

			$_SESSION['subtotal'] = $_SESSION['subtotal'] + $subtotal;
		} else {

			$_SESSION['subtotal'] = $subtotal;
		}
	}

	$subtotal = $_SESSION['subtotal'];

	echo 'R' . number_format($subtotal, 2, ".", ",");

	unset($_SESSION['subtotal']);
}

function sum_remittance($con)
{
	$subtotal = 0;
	$query = mysqli_query($con, "SELECT SUM(Amount) AS `Amount` FROM `tbl_remittance`") or die(mysqli_error($con));

	while ($row = mysqli_fetch_array($query)) {
		$subtotal += $row['Amount'];
	}
	
	echo 'R' . number_format($subtotal, 2, ".", ",");
}

function check_invoice($jobid)
{
	$query = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '$jobid' AND InvoiceQ = '1'") or die(mysqli_error($con));
	$numrows = mysqli_num_rows($query);
	if ($numrows >= 1) {
		echo 'invQ_approved.php?Id=' . $jobid . '';
	} else {
		echo 'inv_approved.php?Id=' . $jobid . '';
	}
}

function check_invoice_old($jobid)
{
	$query = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '$jobid' AND InvoiceQ = '1'") or die(mysqli_error($con));
	$numrows = mysqli_num_rows($query);
	if ($numrows >= 1) {
		echo 'inv_old_q.php?Id=' . $jobid . '';
	} else {
		echo 'inv_old.php?Id=' . $jobid . '';
	}
}

function fmc($con, $quoteno)
{

	$query = mysqli_query($con, "SELECT * FROM tbl_qs WHERE QuoteNo = '$quoteno' LIMIT 1") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);

	$companyid = $row['CompanyId'];

	$query2 = mysqli_query($con, "SELECT * FROM tbl_companies WHERE Id = '$companyid'") or die(mysqli_error($con));
	$row2 = mysqli_fetch_array($query2);

	$pm = preg_replace("/[^0-9,.]/", "", $row['FMC']);


	if (is_numeric($row['FMC'])) {

		echo $row2['Prefix'] . $row['FMC'];
	} else {

		echo $row['FMC'];
	}
}

function quotes_qued($con)
{

	if (isset($_SESSION['areaid'])) {

		$areaid = $_SESSION['areaid'];
	} else {

		$areaid = 1;
	}

	$where = "AND AreaId = '" . $areaid . "'";

	if (isset($_SESSION['system'])) {

		$where .= ($_SESSION['system'] != 3) ? ' AND SystemId = ' . $_SESSION['system'] : '';
	}

	$query = mysqli_query($con, "SELECT COUNT(QuoteNo) FROM tbl_qs WHERE Status = '4' $where GROUP BY QuoteNo") or die(mysqli_error($con));
	$numrows = mysqli_num_rows($query);
	echo '<span class="counter-bg">' . $numrows . '</span>';
}

function quotes_pending($con)
{

	$sql_where = '';

	if (isset($_SESSION['system'])) {

		$sql_where .= ($_SESSION['system'] != 3) ? ' AND SystemId = ' . $_SESSION['system'] : '';
	}

	$query = mysqli_query($con, "SELECT SUM(QuoteNo) FROM tbl_qs WHERE Status = '0' $sql_where GROUP BY QuoteNo") or die(mysqli_error($con));
	$numrows = mysqli_num_rows($query);
	echo '<span class="counter-bg">' . $numrows . '</span>';
}

function quotes_submitted($con)
{

	if (isset($_SESSION['areaid'])) {

		$areaid = $_SESSION['areaid'];
	} else {

		$areaid = 1;
	}

	$where = "AND AreaId = " . $areaid;

	if (isset($_SESSION['system'])) {

		$where .= ($_SESSION['system'] != 3) ? ' AND SystemId = ' . $_SESSION['system'] : '';
	}

	$query = mysqli_query($con, "SELECT SUM(tbl_qs.QuoteNo), tbl_qs.AreaId, tbl_qs.JobDescription, tbl_sent_quotes.CompanyId, tbl_sent_quotes.SiteId, tbl_sent_quotes.PDF, tbl_sent_quotes.DateSent, tbl_sent_quotes.Sent FROM tbl_sent_quotes JOIN tbl_qs ON tbl_qs.QuoteNo=tbl_sent_quotes.QuoteNo WHERE Status = 2 $where GROUP BY tbl_qs.QuoteNo") or die(mysqli_error($con));

	$numrows = mysqli_num_rows($query);

	echo '<span class="counter-bg">' . $numrows . '</span>';
}

function quotes_archives($con)
{

	if (isset($_SESSION['areaid'])) {

		$areaid = $_SESSION['areaid'];
	} else {

		$areaid = 1;
	}

	$where = "AND AreaId = '" . $areaid . "'";

	if (isset($_SESSION['system'])) {

		$where .= ($_SESSION['system'] != 3) ? ' AND SystemId = ' . $_SESSION['system'] : '';
	}

	$query = mysqli_query($con, "SELECT SUM(Status) FROM tbl_qs WHERE Status = '3' $where GROUP BY QuoteNo") or die(mysqli_error($con));
	$numrows = mysqli_num_rows($query);

	echo '<span class="counter-bg">' . $numrows . '</span>';
}

function jc_que($con, $style = true)
{
	if ($_SESSION['kt_login_level'] != 0) {

		if (isset($_SESSION['areaid'])) {

			$areaid = $_SESSION['areaid'];
		} else {

			$areaid = 1;
		}
	} else {

		$areaid = $_SESSION['kt_AreaId'];
	}

	$where = ($areaid != 5) ? "AND AreaId = '" . $areaid . "'" : '';

	if (isset($_SESSION['system'])) {

		$where .= ($_SESSION['system'] != 3) ? ' AND SystemId = ' . $_SESSION['system'] : '';
	}

	$query = mysqli_query($con, "SELECT * FROM tbl_jc WHERE Status = '1' $where GROUP BY JobId") or die(mysqli_error($con));
	$numrows = mysqli_num_rows($query);

	if ($style) {
		echo '<span class="counter-bg">' . $numrows . '</span>';
	} else {
		return $numrows;
	}
}

function jc_rejected($con, $style = true)
{

	if ($_SESSION['kt_login_level'] != 0) {

		if (isset($_SESSION['areaid'])) {

			$areaid = $_SESSION['areaid'];
		} else {

			$areaid = 1;
		}
	} else {

		$areaid = $_SESSION['kt_AreaId'];
	}

	$where = ($areaid != 5) ? "AND AreaId = '" . $areaid . "'" : '';

	if (isset($_SESSION['system'])) {

		$where .= ($_SESSION['system'] != 3) ? ' AND SystemId = ' . $_SESSION['system'] : '';
	}

	$query = mysqli_query($con, "SELECT * FROM tbl_jc WHERE Status = '5' $where GROUP BY JobId") or die(mysqli_error($con));
	$numrows = mysqli_num_rows($query);

	if ($style) {
		echo '<span class="counter-bg">' . $numrows . '</span>';
	} else {
		echo $numrows;
	}
}

function invoice_status($con, $jobno)
{


	$query = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobNo = '$jobno' AND tbl_jc.CompanyId != '0'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);

	if ($row['Status'] == 1) {
		echo 'Jobcards - Qued';
	} elseif ($row['Status'] == 2) {
		echo 'Jobcards - In Progress';
	} elseif ($row['Status'] == 3) {
		echo 'Jobcards - Costing';
	} elseif ($row['Status'] == 4) {
		echo 'Jobcards - Pending';
	} elseif ($row['Status'] == 5) {
		echo 'Jobcards - Rejected';
	} elseif ($row['Status'] == 6) {
		echo 'Jobcards - Archives';
	} elseif ($row['Status'] == 7) {
		echo 'Invoices - Pending';
	} elseif ($row['Status'] == 8) {
		echo 'Invoices - Approved';
	} elseif ($row['Status'] == 9) {
		echo 'Invoices - Debtors';
	} elseif ($row['Status'] == 10) {
		echo 'Invoices - Archived';
	} else {
	}
}

function colour_bank($areaid)
{

	$query = mysqli_query($con, "SELECT SUM(TransactionAmount) FROM tbl_budget WHERE TransactionType = '0' AND AreaId = '$areaid'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	$transactions = $row['SUM(TransactionAmount)']; // 500

	$query2 = mysqli_query($con, "SELECT * FROM tbl_budget WHERE AreaId = '$areaid' ORDER BY Id DESC LIMIT 1") or die(mysqli_error($con));
	$row2 = mysqli_fetch_array($query2);

	$bank_balance = $row2['BankBalance']; // 5400

	$query3 = mysqli_query($con, "SELECT * FROM tbl_budget WHERE TransactionType = '1' AND AreaId = '$areaid' ORDER BY Id DESC LIMIT 1") or die(mysqli_error($con));
	$row3 = mysqli_fetch_array($query3);

	$deposits = $row3['AccumulatedDeposits']; // 6000

	if ($deposits == ($transactions + $bank_balance)) {
		echo "#009933";
	} else {
		echo "#FF0000";
	}
}

function balance_out($areaid)
{
	//$areaid = $_SESSION['AreaId'];

	$query = mysqli_query($con, "SELECT SUM(TransactionAmount) FROM tbl_budget WHERE TransactionType = '0' AND AreaId = '$areaid'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);

	$transactions = $row['SUM(TransactionAmount)']; // 0

	$query2 = mysqli_query($con, "SELECT * FROM tbl_budget WHERE AreaId = '$areaid' ORDER BY Id DESC LIMIT 1") or die(mysqli_error($con));
	$row2 = mysqli_fetch_array($query2);

	$bank_balance = $row2['BankBalance']; // 7 5000

	$query3 = mysqli_query($con, "SELECT * FROM tbl_budget WHERE TransactionType = '1' AND AreaId = '$areaid' ORDER BY Id DESC LIMIT 1") or die(mysqli_error($con));
	$row3 = mysqli_fetch_array($query3);

	$deposits = $row3['AccumulatedDeposits']; // 10 000

	if ($deposits != ($transactions + $bank_balance)) {
		$total_out = $deposits - ($transactions + $bank_balance);
		echo "(R" . $total_out . ")";
	}
}

function balanced($areaid)
{

	$query = mysqli_query($con, "SELECT SUM(TransactionAmount) FROM tbl_budget WHERE TransactionType = '0' AND AreaId = '$areaid'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	$transactions = $row['SUM(TransactionAmount)']; // 0

	$query2 = mysqli_query($con, "SELECT * FROM tbl_budget WHERE AreaId = '$areaid' ORDER BY Id DESC LIMIT 1") or die(mysqli_error($con));
	$row2 = mysqli_fetch_array($query2);

	$bank_balance = $row2['BankBalance']; // 10 000

	$query3 = mysqli_query($con, "SELECT * FROM tbl_budget WHERE TransactionType = '1' AND AreaId = '$areaid' ORDER BY Id DESC LIMIT 1") or die(mysqli_error($con));
	$row3 = mysqli_fetch_array($query3);

	$deposits = $row3['AccumulatedDeposits']; // 10 000

	if ($deposits == ($transactions + $bank_balance)) {
		echo "<input name=\"Complete\" type=\"submit\" class=\"combo_bold\" value=\"Complete\" id=\"Complete\" style=\"color:#093\">";
	} else {
		echo "<input name=\"Submit\" type=\"submit\" class=\"combo_bold\" value=\"Save\" style=\"color:#FF0000\">";
	}
}

function randomPrefix($length)
{
	$random = "";

	srand((float)microtime() * 1000000);

	$data = "AbcDE123IJKLMN67QRSTUVWXYZ";
	$data .= "aBCdefghijklmn123opq45rs67tuv89wxyz";
	$data .= "0FGH45OP89";

	for ($i = 0; $i < $length; $i++) {
		$random .= substr($data, (rand() % (strlen($data))), 1);
	}

	return $random;
}

function area_id_jc($master_area)
{
	$areaid = $_POST['mater_area'];
	return $areaid;
}


function time_schedule($jobid)
{

	$con = mysqli_connect('sql15.jnb1.host-h.net', 'kwdaco_333', 'SBbB38c8Qh8', 'seavest_db333');

	$query = mysqli_query($con, "SELECT DATEDIFF(now(), Days) AS Days FROM tbl_jc WHERE JobId = '$jobid'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);

	$days = $row['Days'] + 1;
	echo '<span> ' . $days . '</span>';
}

function time_schedule_quotes($quoteno)
{

	$con = mysqli_connect('sql15.jnb1.host-h.net', 'kwdaco_333', 'SBbB38c8Qh8', 'seavest_db333');

	$query = mysqli_query($con, "SELECT DATEDIFF(now(), Days) AS Days FROM tbl_qs WHERE QuoteNo = '$quoteno'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);

	$days = $row['Days'] + 1;
	echo '<span> ' . $days . '</span>';

	$row['Days'] = NULL;
	$days = NULL;
}

function follow_up($quoteno)
{

	$con = mysqli_connect('sql15.jnb1.host-h.net', 'kwdaco_333', 'SBbB38c8Qh8', 'seavest_db333');

	$query = mysqli_query($con, "SELECT * FROM tbl_qs_mail_history WHERE QuoteNo = '$quoteno' ORDER BY Id DESC") or die(mysqli_error($con));
	$numrows = mysqli_num_rows($query);
	$row = mysqli_fetch_array($query);

	if ($numrows >= 2) {

		$query2 = mysqli_query($con, "SELECT * FROM tbl_qs WHERE QuoteNo = '$quoteno'") or die(mysqli_error($con));
		$row2 = mysqli_fetch_array($query2);

		$date1 = date("Y-m-d", time());
		$date2 = date('Y-m-d', strtotime($row['Date']));

		$difference = abs(strtotime($date2) - strtotime($date1));
		$days = round((((($difference / 60) / 60) / 24) + 1), 0);

		if ($days >= 2) {

			$plural = 's';
		}

		echo '<span class="combo"> ' . $days . ' day' . $plural . '</span>';
	} else {

		echo 'None';
	}
}

function debtors_overdue($jobid)
{

	$con = mysqli_connect('sql15.jnb1.host-h.net', 'kwdaco_333', 'SBbB38c8Qh8', 'seavest_db333');

	$query = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '$jobid'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);

	$date1 = date("Y-m-d", time());
	$date2 = $row['Days'];
	$id = $row['CompanyId'];

	$query2 = mysqli_query($con, "SELECT * FROM tbl_companies WHERE Id = '$id'") or die(mysqli_error($con));
	$row2 = mysqli_fetch_array($query2);

	$payment = $row2['Payment'];

	$difference = abs(strtotime($date2) - strtotime($date1));

	$days = round((((($difference / 60) / 60) / 24) + 1), 0);

	if ($days > $payment) {

		echo ' style="color:#FF0000"';
	}
}

function quote_import($jobid, $quoteno, $jobno)
{

	$con = mysqli_connect('sql15.jnb1.host-h.net', 'kwdaco_333', 'SBbB38c8Qh8', 'seavest_db333');

	$query_jc = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '$jobid'") or die(mysqli_error($con));
	$row_jc = mysqli_fetch_array($query_jc);

	$query = mysqli_query($con, "SELECT * FROM tbl_qs WHERE QuoteNo = '$quoteno'") or die(mysqli_error($con));
	while ($row = mysqli_fetch_array($query)) {


		$jc_data = array(

			'JobId' => $jobid,
			'Status' => $row_jc['Status'],
			'AreaId' => $row['AreaId'],
			'companyId' => $row['CompanyId'],
			'SiteId' => $row['SiteId'],
			'JobNo' => $jobno,
			'SlaCatId' => $row_jc['SlaCatId'],
			'SlaSubCatId' => $row_jc['SlaSubCatId'],
			'FacilityFirstContact' => $row_jc['FacilityFirstContact'],
			'Reference' => $row_jc['Reference'],
			'JobCardPDF' => $row_jc['JobCardPDF'],
			'JcDate' => $row_jc['JcDate'],
			'LabourType' => $row_jc['LabourType'],
			'Description' => addslashes($row['Description']),
			'Unit' => $row['Unit'],
			'Qty' => $row['Qty'],
			'Price' => $row['Price'],
			'Total1' => $row['Total1'],
			'SubTotal' => $row['SubTotal'],
			'VAT' => $row['VAT'],
			'Total2' => $row['Total2'],
			'Labour' => $row['Labour'],
			'Material' => $row['Material'],
			'Total' => $row['Total'],
			'Date' => $row['Date'],
			'Days' => date('Y-m-d'),
			'TransportComment' => addslashes($row['TransportComment']),
			'Date1' => $row_jc['Date1'],
			'Date2' => $row_jc['Date2'],
			'SearchDate' => $row_jc['SearchDate'],
			'InvoiceNo' => $row_jc['InvoiceNo'],
			'InvoiceDate' => $row_jc['InvoiceDate'],
			'NewInvoiceDate' => $row_jc['NewInvoiceDate'],
			'Reference' => $row_jc['Reference'],
			'CustomerFeedBack' => addslashes($row['CustomerFeedBack']),
			'JobDescription' => addslashes($row['JobDescription']),
			'Import' => '1',
			'Labour' => $row['Labour'],
			'Material' => $row['Material'],
		);

		if ($row['Transport'] != 1) {

			dbInsert('tbl_jc', $jc_data, $con);
		}

		if ($row['Transport'] == 1) {

			$transport_data = array(

				'JobNo' => $jobno,
				'JobId' => $jobid,
				'Description' => $row['Description'],
				'Unit' => $row['Unit'],
				'Qty' => $row['Qty'],
				'Price' => $row['Price'],
				'TransportComment' => $row['TransportComment'],
			);

			$transport_data['Total1'] = $row['Description'] * $row['Qty'] * $row['Price'];

			dbInsert('tbl_travel', $transport_data, $con);
		}
	}

	mysqli_query($con, "DELETE FROM tbl_jc WHERE JobId = '$jobid' AND Import = '0' AND (Labour = '1' OR Material = '1' OR Transport = '1')") or die(mysqli_error($con));
}

function fuel_rate($jobid)
{

	$con = mysqli_connect('sql15.jnb1.host-h.net', 'kwdaco_333', 'SBbB38c8Qh8', 'seavest_db333');

	$query = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '$jobid'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);

	$companyid = $row['CompanyId'];

	$query = mysqli_query($con, "SELECT * FROM tbl_fuel WHERE Company = '$companyid'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);

	echo number_format($row['Rate'], 2);
}

function hes_risk_types($hesid, $docid)
{

	$con = mysqli_connect('sql15.jnb1.host-h.net', 'kwdaco_333', 'SBbB38c8Qh8', 'seavest_db333');

	$query = mysqli_query($con, "SELECT * FROM tbl_hes_documents_relation WHERE HESId = '$hesid' AND DocumentId = '$docid' AND Active = '1'") or die(mysqli_error($con));
	$numrows = mysqli_num_rows($query);

	if ($numrows == 1) {

		echo 'checked="checked"';
	}
}

function createThumbnail($img, $imgPath, $suffix, $newWidth, $newHeight, $quality)
{
	// Open the original image.
	$original = imagecreatefromjpeg("$imgPath/$img") or die("Error Opening original");
	list($width, $height, $type, $attr) = getimagesize("$imgPath/$img");

	$ratio = $width / $height;
	$newHeight = (int)$ratio * 120;

	// Resample the image.
	$tempImg = imagecreatetruecolor($newWidth, $newHeight) or die("Cant create temp image");
	imagecopyresized($tempImg, $original, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height) or die("Cant resize copy");

	// Create the new file name.
	$newNameE = explode(".", $img);
	$newName = '' . $newNameE[0] . '' . $suffix . '.' . $newNameE[1] . '';

	// Save the image.
	imagejpeg($tempImg, "$imgPath/$newName", $quality) or die("Cant save image");

	// Clean up.
	imagedestroy($original);
	imagedestroy($tempImg);
	return true;
}

function resize($img, $w, $h, $newfilename)
{

	//Check if GD extension is loaded
	if (!extension_loaded('gd') && !extension_loaded('gd2')) {
		trigger_error("GD is not loaded", E_USER_WARNING);
		return false;
	}

	//Get Image size info
	$imgInfo = getimagesize($img);
	switch ($imgInfo[2]) {
		case 1:
			$im = imagecreatefromgif($img);
			break;
		case 2:
			$im = imagecreatefromjpeg($img);
			break;
		case 3:
			$im = imagecreatefrompng($img);
			break;
		default:
			trigger_error('Unsupported filetype!', E_USER_WARNING);
			break;
	}

	//If image dimension is smaller, do not resize
	if ($imgInfo[0] <= $w && $imgInfo[1] <= $h) {
		$nHeight = $imgInfo[1];
		$nWidth = $imgInfo[0];
	} else {
		//yeah, resize it, but keep it proportional
		if ($w / $imgInfo[0] > $h / $imgInfo[1]) {
			$nWidth = $w;
			$nHeight = $imgInfo[1] * ($w / $imgInfo[0]);
		} else {
			$nWidth = $imgInfo[0] * ($h / $imgInfo[1]);
			$nHeight = $h;
		}
	}
	$nWidth = round($nWidth);
	$nHeight = round($nHeight);

	$newImg = imagecreatetruecolor($nWidth, $nHeight);

	/* Check if this image is PNG or GIF, then set if Transparent*/
	if (($imgInfo[2] == 1) or ($imgInfo[2] == 3)) {
		imagealphablending($newImg, false);
		imagesavealpha($newImg, true);
		$transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
		imagefilledrectangle($newImg, 0, 0, $nWidth, $nHeight, $transparent);
	}
	imagecopyresampled($newImg, $im, 0, 0, 0, 0, $nWidth, $nHeight, $imgInfo[0], $imgInfo[1]);

	//Generate the file, and rename it to $newfilename
	switch ($imgInfo[2]) {
		case 1:
			imagegif($newImg, $newfilename);
			break;
		case 2:
			imagerotate(imagejpeg($newImg, $newfilename), 90, 0);
			break;
		case 3:
			imagepng($newImg, $newfilename);
			break;
		default:
			trigger_error('Failed resize image!', E_USER_WARNING);
			break;
	}
	return $newfilename;
}

function zone_charge($trips, $return_distance, $subtotal, $jobid)
{

	$con = mysqli_connect('sql15.jnb1.host-h.net', 'kwdaco_333', 'SBbB38c8Qh8', 'seavest_db333');

	$vat_rate = getVatRate($con, $jobid) / 100;

	if ($return_distance <= 99.99) {

		$zone_charge = '0.00';

		$_SESSION['zone-charge'] = $zone_charge;

		echo number_format($zone_charge, 2);
	} elseif ($return_distance >= 200 && $return_distance <= 399.99) {

		$zone_charge = $subtotal * 0.1;

		$_SESSION['zone-charge'] = $zone_charge;

		echo number_format($zone_charge, 2);
	} elseif ($return_distance >= 400) {

		$zone_charge = $subtotal * $vat_rate;

		$_SESSION['zone-charge'] = $zone_charge;

		echo number_format($zone_charge, 2);
	}
}

function report_alert($report_id)
{

	$con = mysqli_connect('sql15.jnb1.host-h.net', 'kwdaco_333', 'SBbB38c8Qh8', 'seavest_db333');

	$query = mysqli_query($con, "SELECT tbl_management_reports.Id, tbl_management_report_details.Date, tbl_management_reports.Frequency
FROM ((tbl_management_reports
LEFT JOIN tbl_management_report_details ON tbl_management_report_details.ReportId=tbl_management_reports.Id)
LEFT JOIN tbl_management_report_frequencies ON tbl_management_report_frequencies.Id=tbl_management_reports.Frequency) WHERE tbl_management_reports.Id = '$report_id' AND tbl_management_report_details.Old = '0' ORDER BY Id DESC") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);

	$first_monday = date('Y-m-d', strtotime('First Monday of ' . date('F o')));
	$first_day_week = date('Y-m-d', strtotime('Last Monday', time()));
	$today = date('Y-m-d');

	// Daily
	if ($row['Frequency'] == 1) {

		if ($today > $row['Date']) {

			echo '<img src="' . DOMAIN . '/images/icons/report-late.png" width="25" height="25" />';
		} else {

			echo '<img src="' . DOMAIN . '/images/icons/report-good.png" width="25" height="25" />';
		}
	}

	// Weekly
	if ($row['Frequency'] == 2) {

		if ($first_day_week > $row['Date']) {

			echo '<img src="' . DOMAIN . '/images/icons/report-late.png" width="25" height="25" />';
		} else {

			echo '<img src="' . DOMAIN . '/images/icons/report-good.png" width="25" height="25" />';
		}
	}

	// Monthly
	if ($row['Frequency'] == 3) {

		if ($first_monday > $row['Date']) {

			echo '<img src="' . DOMAIN . '/images/icons/report-late.png" width="25" height="25" />';
		} else {

			echo '<img src="' . DOMAIN . '/images/icons/report-good.png" width="25" height="25" />';
		}
	}
}

function createThumbs($pathToImages, $pathToThumbs, $thumbWidth, $image)
{
	if (ENVIRONMENT == 'PRD') {
		// open the directory
		$dir = opendir($pathToImages);

		// loop through it, looking for any/all JPG files:

		while (false !== ($fname = readdir($dir))) {
			// parse path for the extension
			$info = pathinfo($pathToImages . $fname);
			// continue only if this is a JPEG image
			if ($fname == $image) {

				if (strtolower($info['extension']) == 'jpg') {

					// load image and get image size
					$img = imagecreatefromjpeg("{$pathToImages}{$fname}");
					$width = imagesx($img);
					$height = imagesy($img);

					// calculate thumbnail size
					$new_width = $thumbWidth;
					$new_height = floor($height * ($thumbWidth / $width));

					// create a new temporary image
					$tmp_img = imagecreatetruecolor($new_width, $new_height);

					// copy and resize old image into new image
					imagecopyresized($tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

					// save thumbnail into a file
					imagejpeg($tmp_img, "{$pathToThumbs}{$fname}");
				}
			}
			if ($fname == $image) {

				if (strtolower($info['extension']) == 'gif') {

					// load image and get image size
					$img = imagecreatefromgif("{$pathToImages}{$fname}");
					$width = imagesx($img);
					$height = imagesy($img);

					// calculate thumbnail size
					$new_width = $thumbWidth;
					$new_height = floor($height * ($thumbWidth / $width));

					// create a new temporary image
					$tmp_img = imagecreatetruecolor($new_width, $new_height);

					// copy and resize old image into new image
					imagecopyresized($tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

					// save thumbnail into a file
					imagegif($tmp_img, "{$pathToThumbs}{$fname}");
				}
			}
		}
		// close the directory
		closedir($dir);
	}
}

function on_status($con, $jobid)
{

	$query = mysqli_query($con, "SELECT OrderNoStatus FROM tbl_jc WHERE JobId = '$jobid'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);

	if ($row['OrderNoStatus'] == '0') {

		echo 'Pending';
	} elseif ($row['OrderNoStatus'] == '1') {

		echo 'Sent';
	} elseif ($row['OrderNoStatus'] == '2') {

		echo 'Eish';
	}
}


function dbInsert($table_name, $form_data, $con, $prntOut = false)
{

	// retrieve the keys of the array (column titles)
	$fields = array_keys($form_data);

	// build the query
	$query = "INSERT INTO " . $table_name . "
	(" . implode(',', $fields) . ")
	VALUES ('" . implode("','", $form_data) . "')";

	if ($prntOut) {
		echo '<pre>';
		print_r($query);
		die;
	}
	
	mysqli_query($con, $query) or die(mysqli_error($con));
}

function dbUpdate($table_name, $form_data, $where_clause = '', $con)
{
	// check for optional where clause
	$whereSQL = '';
	if (!empty($where_clause)) {
		// check to see if the 'where' keyword exists
		if (substr(strtoupper(trim($where_clause)), 0, 5) != 'WHERE') {
			// not found, add key word
			$whereSQL = " WHERE " . $where_clause;
		} else {
			$whereSQL = " " . trim($where_clause);
		}
	}
	// start the actual SQL statement
	$sql = "UPDATE " . $table_name . " SET ";

	// loop and build the column /
	$sets = array();
	foreach ($form_data as $column => $value) {
		if (!empty($value) || $value == 0) {

			$sets[] = "`" . $column . "` = '" . $value . "'";
		}
	}
	$sql .= implode(', ', $sets);

	// append the where statement
	$sql .= $whereSQL;

	// run and return the query result
	//echo $sql .'<br>';
	return mysqli_query($con, $sql) or die(mysqli_error($con));
}

function dbDelete($table_name, $where_clause = '', $con)
{
	// check for optional where clause
	$whereSQL = '';
	if (!empty($where_clause)) {
		// check to see if the 'where' keyword exists
		if (substr(strtoupper(trim($where_clause)), 0, 5) != 'WHERE') {
			// not found, add keyword
			$whereSQL = " WHERE " . $where_clause;
		} else {
			$whereSQL = " " . trim($where_clause);
		}
	}
	// build the query
	$sql = "DELETE FROM " . $table_name . $whereSQL;

	// run and return the query result resource
	return mysqli_query($con, $sql) or die(mysqli_error($con));
}

function login($con)
{

	if (isset($_POST['login'])) {

		$username = mysqli_real_escape_string($con, $_POST['username']);
		$password = mysqli_real_escape_string($con, $_POST['password']);

		$query = "
		SELECT tbl_users.*, tbl_area_relation.AreaId AS Region, tbl_category_relation.CatId AS `Type`, tbl_system_relation.SystemId AS `Category`
		FROM tbl_users 
		INNER JOIN tbl_area_relation ON tbl_users.Id = tbl_area_relation.UserId
		LEFT JOIN tbl_category_relation ON tbl_users.Id = tbl_category_relation.UserId
		LEFT JOIN tbl_system_relation ON tbl_users.Id = tbl_system_relation.UserId
		WHERE  Username = '$username' AND Password = '$password' AND tbl_area_relation.Def = 1 AND tbl_category_relation.Def = 1 AND tbl_system_relation.Def = 1;
		";

		$query = mysqli_query($con, $query) or die(mysqli_error($con));
		$row = mysqli_fetch_array($query);

		if (mysqli_num_rows($query) >= 1) {

			// Set User Login Cookies
			setcookie("userid", $row['Id'], 60 * 60 * 24 * 365 + time(), '/', $domain);
			setcookie("username", $row['Username'], 60 * 60 * 24 * 365 + time(), '/', $domain);
			setcookie("userlevel", $row['UserLevel'], 60 * 60 * 24 * 365 + time(), '/', $domain);
			setcookie("areaid", $row['Region'], 60 * 60 * 24 * 365 + time(), '/', $domain);
			setcookie("name", $row['Name'], 60 * 60 * 24 * 365 + time(), '/', $domain);
			setcookie("telephone", $row['Telephone'], 60 * 60 * 24 * 365 + time(), '/', $domain);
			setcookie("lastlogin", $row['LastLogin'], 60 * 60 * 24 * 365 + time(), '/', $domain);
			setcookie("invoiceFromQuote", $row['QuoteInv'], 60 * 60 * 24 * 365 + time(), '/', $domain);
			setcookie("contractor", $row['Contractor'], 60 * 60 * 24 * 365 + time(), '/', $domain);

			$_SESSION["kt_login_id"] = $row['Id'];
			$_SESSION["kt_login_user"] = $row['Username'];
			$_SESSION["kt_login_level"] = $row['UserLevel'];
			$_SESSION['history'] = $row['EditHistory'];
			$_SESSION['sla'] = $row['OverrideSLA'];
			$_SESSION['ChangeSystems'] = $row['ChangeSystems'];
			// $_SESSION['system'] = $row['SystemId'];
			$_SESSION['type'] = $row['Type'];
			$_SESSION['system'] = $row['Category'];

			if ($row['Region'] >= 1) {

				$_SESSION["kt_AreaId"] = $row['AreaId'];

				// Old System
				$_SESSION['areaid'] = $row['Region'];
			} else {

				$_SESSION["kt_AreaId"] = 1;

				// Old System
				$_SESSION['areaid'] = 1;
			}

			$domain = DOMAIN;
			header('Location: ' . $domain . '/welcome.php');

			exit();
		} else {

			// Trim the URL for any unwanted parameters
			$url = explode('?', $_SERVER['HTTP_REFERER']);

			// Trigger the error message
			header('Location: ' . $url[0] . '?Error');

			exit();
		}
	}
}

function login_engineer($con)
{

	if (isset($_POST['login'])) {

		$username = mysqli_real_escape_string($con, $_POST['username']);
		$password = mysqli_real_escape_string($con, $_POST['password']);

		$query = mysqli_query($con, "SELECT * FROM tbl_engineers WHERE Email = '$username' AND Password = '$password'") or die(mysqli_error($con));
		$row = mysqli_fetch_array($query);
		$numrows = mysqli_num_rows($query);

		if ($numrows >= 1) {

			// Set User Login Cookies
			setcookie("userid", $row['Id'], 60 * 60 * 24 * 365 + time(), '/', $domain);
			setcookie("username", $row['Email'], 60 * 60 * 24 * 365 + time(), '/', $domain);
			setcookie("name", $row['Name'], 60 * 60 * 24 * 365 + time(), '/', $domain);

			header('Location: ' . DOMAIN . '/Reports/Engineers/index.php');

			exit();
		} else {

			// Trim the URL for any unwanted parameters
			$url = explode('?', $_SERVER['HTTP_REFERER']);

			// Trigger the error message
			header('Location: ' . $url[0] . '?Error');

			exit();
		}
	}
}

function login_engineer_bp($con)
{

	if (isset($_POST['login'])) {

		$username = mysqli_real_escape_string($con, $_POST['username']);
		$password = mysqli_real_escape_string($con, $_POST['password']);

		$query = mysqli_query($con, "SELECT * FROM tbl_engineers WHERE Email = '$username' AND Password = '$password'") or die(mysqli_error($con));
		$row = mysqli_fetch_array($query);
		$numrows = mysqli_num_rows($query);

		if ($numrows >= 1) {

			// Set User Login Cookies
			setcookie("userid", $row['Id'], 60 * 60 * 24 * 365 + time(), '/', $domain);
			setcookie("username", $row['Email'], 60 * 60 * 24 * 365 + time(), '/', $domain);
			setcookie("name", $row['Name'], 60 * 60 * 24 * 365 + time(), '/', $domain);

			header('Location: ' . DOMAIN . '/Reports/Engineers/BP/index.php');

			exit();
		} else {

			// Trim the URL for any unwanted parameters
			$url = explode('?', $_SERVER['HTTP_REFERER']);

			// Trigger the error message
			header('Location: ' . $url[0] . '?Error');

			exit();
		}
	}
}

function login_engineer_bp_sched($con)
{

	if (isset($_POST['login'])) {

		$username = mysqli_real_escape_string($con, $_POST['username']);
		$password = mysqli_real_escape_string($con, $_POST['password']);

		$query = mysqli_query($con, "SELECT * FROM tbl_engineers WHERE Email = '$username' AND Password = '$password'") or die(mysqli_error($con));
		$row = mysqli_fetch_array($query);
		$numrows = mysqli_num_rows($query);

		if ($numrows >= 1) {

			// Set User Login Cookies
			setcookie("userid", $row['Id'], 60 * 60 * 24 * 365 + time(), '/', $domain);
			setcookie("username", $row['Email'], 60 * 60 * 24 * 365 + time(), '/', $domain);
			setcookie("name", $row['Name'], 60 * 60 * 24 * 365 + time(), '/', $domain);

			header('Location: ' . DOMAIN . '/scheduled-maintenance/bp/bp-calendar.php');

			exit();
		} else {

			// Trim the URL for any unwanted parameters
			$url = explode('?', $_SERVER['HTTP_REFERER']);

			// Trigger the error message
			header('Location: ' . $url[0] . '?Error');

			exit();
		}
	}
}

function logout($con)
{

	if (isset($_GET['Logout'])) {

		$time = date('Y-m-d H:i');
		$id = $_COOKIE['userid'];

		mysqli_query($con, "UPDATE tbl_users SET LastLogin = '$time' WHERE Id = '$id'") or die(mysqli_error($con));

		setcookie("userid", '0', time() - 3600, '/', $domain);
		setcookie("username", '0', time() - 3600, '/', $domain);
		setcookie("name", '0', time() - 3600, '/', $domain);
		setcookie("userlevel", '0', time() - 3600, '/', $domain);
		setcookie("areaid", '0', time() - 3600, '/', $domain);
		setcookie("lastlogin", '0', time() - 3600, '/', $domain);
		session_destroy();

		header('Location: ' . DOMAIN . '/login.php');
	}
}

function logout_engineer($con)
{

	if (isset($_GET['Logout'])) {

		$time = date('Y-m-d H:i');
		$id = $_COOKIE['userid'];

		mysqli_query($con, "UPDATE tbl_users SET LastLogin = '$time' WHERE Id = '$id'") or die(mysqli_error($con));

		setcookie("userid", '0', time() - 3600, '/', $domain);
		setcookie("username", '0', time() - 3600, '/', $domain);
		setcookie("name", '0', time() - 3600, '/', $domain);
		session_destroy();

		header('Location: login.php');

		exit();
	}
}

function logout_link()
{

	$url  = explode('?', $_SERVER['REQUEST_URI']);
	echo '<a class="close" href="' . $url[0] . '?Logout" title="Logout"></a>';
}

function engineer_area_select($con)
{

	$query = mysqli_query($con, "SELECT * FROM tbl_areas ORDER BY Area ASC") or die(mysqli_error($con));

	echo '<form name="form" id="form">';
	echo '<select name="jumpMenu" id="tab-user" onchange="MM_jumpMenu(\'parent\',this,0)">';
	echo '<option value="' . DOMAIN . '/functions/engineer-sessions.php?Area=All">All Provinces</option>';

	while ($row = mysqli_fetch_array($query)) {

		$selected = '';

		if ($_SESSION["areaid"] == $row['Id']) {

			$selected = 'selected="selected"';
		}

		echo '<option ' . $selected . ' value="' . DOMAIN . '/functions/engineer-sessions.php?Area=' . $row['Id'] . '">' . $row['Area'] . '</option>';
	}

	echo '</select>';
	echo '</form>';

	mysqli_free_result($query);
}

function bp_qtr_select($con)
{

	$query = mysqli_query($con, "SELECT * FROM tbl_areas ORDER BY Area ASC") or die(mysqli_error($con));

	if ($_SESSION['qrt'] == '1') {

		$selected1 = 'selected="selected"';
	}

	if ($_SESSION['qrt'] == '2') {

		$selected2 = 'selected="selected"';
	}

	if ($_SESSION['qrt'] == '3') {

		$selected3 = 'selected="selected"';
	}

	if ($_SESSION['qrt'] == '4') {

		$selected4 = 'selected="selected"';
	}

	echo '<form name="form" id="form">';
	echo '<select name="jumpMenu" id="tab-user" onchange="MM_jumpMenu(\'parent\',this,0)">';
	echo '<option ' . $selected . ' value="' . DOMAIN . '/functions/engineer-sessions.php?Qrt=All">Entire Year</option>';
	echo '<option ' . $selected1 . ' value="' . DOMAIN . '/functions/engineer-sessions.php?Qrt=1">1st Quarter</option>';
	echo '<option ' . $selected2 . ' value="' . DOMAIN . '/functions/engineer-sessions.php?Qrt=2">2nd Quarter</option>';
	echo '<option ' . $selected3 . ' value="' . DOMAIN . '/functions/engineer-sessions.php?Qrt=3">3rd Quarter</option>';
	echo '<option ' . $selected4 . ' value="' . DOMAIN . '/functions/engineer-sessions.php?Qrt=4">4th Quarter</option>';
	echo '</select>';
	echo '</form>';

	mysqli_free_result($query);
}

function engineer_logout_link($con)
{

	$url  = explode('?', $_SERVER['REQUEST_URI']);
	echo '<a class="close" href="' . $url[0] . '?Logout" title="Logout"></a>';
}

function area_select($con)
{

	$query = "
		SELECT
		  tbl_area_relation.UserId,
		  tbl_area_relation.AreaId,
		  tbl_area_relation.Def,
		  tbl_areas.Area
		FROM
		  tbl_area_relation
		  INNER JOIN tbl_areas
			ON (
			  tbl_area_relation.AreaId = tbl_areas.Id
			)
		WHERE (tbl_area_relation.UserId = '" . $_COOKIE['userid'] . "')
		ORDER BY Area ASC";

	$query = mysqli_query($con, $query) or die(mysqli_error($con));

	echo '<form name="form" id="form">';
	echo '<select name="jumpMenu" class="area-dd" id="jumpMenu" onchange="MM_jumpMenu(\'parent\',this,0)">';

	while ($row = mysqli_fetch_array($query)) {

		$selected = '';

		if ($_SESSION['areaid'] == $row['AreaId']) {

			$selected = 'selected="selected"';
		}

		echo '<option ' . $selected . ' value="' . DOMAIN . '/functions/sessions.php?Area=' . $row['AreaId'] . '">' . $row['Area'] . '</option>';
	}

	echo '</select>';
	echo '</form>';

	mysqli_free_result($query);
}


function last_id($con, $tbl)
{

	$query = mysqli_query($con, "SELECT * FROM " . $tbl . " ORDER BY Id DESC LIMIT 1") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);

	return $row['Id'];
}

function fault_types($con, $cat, $subcat)
{

	if (isset($cat)) {

		$query = "
	  SELECT
		  tbl_sla_subcat.SubCat,
		  tbl_jc.SlaSubCatId
	  FROM
		  tbl_jc
	  INNER JOIN tbl_sla_subcat ON tbl_sla_subcat.Id = tbl_jc.SlaSubCatId
	  WHERE
		  tbl_jc.SlaSubCatId = '$cat'";

		$query = mysqli_query($con, $query) or die(mysqli_error($con));
		$row = mysqli_fetch_array($query);

		echo $row['SubCat'] . ' ';
	}

	if (isset($subcat)) {

		$query = mysqli_query($con, "SELECT * FROM tbl_menu_links WHERE Status = '$subcat'") or die(mysqli_error($con));
		$row = mysqli_fetch_array($query);

		echo '-  ' . $row['Menu'];
	}

	if (!isset($_GET['Cat']) || !isset($_GET['Status'])) {

		echo 'All ';
	}
}

function logged_today($con, $cat, $subcat)
{

	$query = "
		SELECT
			Count(tbl_jc.Id) AS Faults,
			Date(tbl_jc.Date1)
		FROM
			tbl_jc
		WHERE
			(tbl_jc.Status = '1'
			OR tbl_jc.Status = '2'
			OR tbl_jc.Status = '4'
			OR tbl_jc.Status = '17'
			OR tbl_jc.Status = '19'
			OR tbl_jc.Status = '20')
		AND
			DATE(tbl_jc.Date1) = DATE(NOW())
		AND
			tbl_jc.SlaCatId > '0'
		AND
		    CompanyId = '6'";

	if (!empty($cat)) {

		$query .= "AND tbl_jc.SlaCatId = '$cat' ";
	}

	if (!empty($subcat)) {

		$query .= "AND tbl_jc.SlaSubCatId = '$subcat' ";
	}

	$query = mysqli_query($con, $query) or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);

	echo $row['Faults'];

	mysqli_free_result($query);
}

function logged_today_bp($con, $cat, $subcat)
{

	if (isset($_SESSION['areaid'])) {

		$where = " AND tbl_jc.AreaId = '" . $_SESSION['areaid'] . "'";
	}

	$query = "
		SELECT
			tbl_jc.Id AS Faults,
			Date(tbl_jc.Date1),
			tbl_jc.`Status`,
			tbl_jc.JobId,
			tbl_jc.JobNo
		FROM
			tbl_jc
		WHERE
			CompanyId = '14'
		AND
		    DATE(tbl_jc.Date1) = DATE(NOW())
		AND
		    tbl_jc.SlaSubCatId >= '1'

			$where

		AND (
			tbl_jc. STATUS = '1'
			OR tbl_jc. STATUS = '2'
			OR tbl_jc. STATUS = '4'
		)
		";

	if (!empty($cat)) {

		$query .= "AND tbl_jc.SlaSubCatId = '$cat' ";
	}

	if (!empty($subcat)) {

		$query .= "AND tbl_jc.SlaSubCatId = '$subcat' ";
	}

	$query .= "GROUP BY
			         tbl_jc.JobId";


	$query = mysqli_query($con, $query) or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);

	echo mysqli_num_rows($query);

	mysqli_free_result($query);
}

function total_faults($con, $cat, $subcat)
{

	$query = "
		SELECT
			tbl_jc.Id
		FROM
			tbl_jc
		WHERE
		   (tbl_jc.`Status` = '1'
		OR tbl_jc.`Status` = '2'
		OR tbl_jc.`Status` = '4'
		OR tbl_jc.`Status` = '17'
		OR tbl_jc.`Status` = '19'
		OR tbl_jc.`Status` = '20')
		AND
			tbl_jc.SlaCatId > '0'
		AND
		    CompanyId = '6'
		GROUP BY
		    tbl_jc.JobId";

	if (!empty($cat)) {

		$query .= " AND SlaCatId = '$cat' ";
	}

	if (!empty($subcat)) {

		$query .= " AND tbl_SlaSubCatId = '$subcat' ";
	}

	$query = mysqli_query($con, $query) or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);

	echo mysqli_num_rows($query);

	mysqli_free_result($query);
}

function total_faults_bp($con, $cat, $subcat)
{

	if (isset($_SESSION['areaid'])) {

		$where = " AND tbl_jc.AreaId = '" . $_SESSION['areaid'] . "'";
	}

	$query = "
	  SELECT
		  tbl_jc.Id
	  FROM
		  tbl_jc
	  WHERE
		  (
			  tbl_jc.`Status` = '1'
			  OR tbl_jc.`Status` = '2'
			  OR tbl_jc.`Status` = '4'

		  )
	  AND tbl_jc.SlaCatId > '0'
	  AND CompanyId = '14'
	  $where";

	if (!empty($cat)) {

		$query .= " AND SlaSubCatId = '$cat' ";
	}

	if (!empty($subcat)) {

		$query .= " AND tbl_SlaSubCatId = '$subcat' ";
	}

	$query .= "
	  GROUP BY
		  tbl_jc.JobId";

	$query = mysqli_query($con, $query) or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);

	echo mysqli_num_rows($query);

	mysqli_free_result($query);
}

function due_today($con, $cat, $subcat)
{

	$query = "
		SELECT
			Count(*) AS Faults,
			Date(Date1)
		FROM
			tbl_jc
		WHERE
		(tbl_jc.Status = '1'
		OR tbl_jc.Status = '2'
		OR tbl_jc.Status = '4'
		OR tbl_jc.Status = '17'
		OR tbl_jc.Status = '19'
		OR tbl_jc.Status = '20')
		AND
			tbl_jc.SlaCatId > '0'
		AND
		    CompanyId = '6'
		AND
			DATE(Date2) = DATE(NOW())";

	if (!empty($cat)) {

		$query .= "AND SlaCatId = '$cat' ";
	}

	if (!empty($subcat)) {

		$query .= "AND tbl_SlaSubCatId = '$subcat' ";
	}

	$query = mysqli_query($con, $query) or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);

	echo $row['Faults'];

	mysqli_free_result($query);
}

function due_today_bp($con, $cat, $subcat)
{

	if (isset($_SESSION['areaid'])) {

		$where = " AND tbl_jc.AreaId = '" . $_SESSION['areaid'] . "'";
	}

	$query = "
	  SELECT
		  tbl_jc.Id AS Faults,
		  Date(Date1),
		  tbl_jc.JobId,
		  tbl_jc.JobNo
	  FROM
		  tbl_jc
	  WHERE
		  (
			  tbl_jc. STATUS = '1'
			  OR tbl_jc. STATUS = '2'
			  OR tbl_jc. STATUS = '4'
		  )
	  AND tbl_jc.SlaCatId > '0'
	  AND CompanyId = '14'
	  AND DATE(Date2) = DATE(NOW())
	  $where";

	if (!empty($cat)) {

		$query .= "AND SlaSubCatId = '$cat' ";
	}

	if (!empty($subcat)) {

		$query .= "AND tbl_SlaSubCatId = '$subcat' ";
	}

	$query .= "GROUP BY
	             tbl_jc.JobId";


	$query = mysqli_query($con, $query) or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);

	echo mysqli_num_rows($query);

	mysqli_free_result($query);
}

function overdue($con, $cat, $subcat)
{

	$query = "
		SELECT
			Count(tbl_jc.Id) AS Faults,
			Date(tbl_jc.Date2)
		FROM
			tbl_jc
		WHERE
		(tbl_jc.Status = '1'
		OR tbl_jc.Status = '2'
		OR tbl_jc.Status = '4'
		OR tbl_jc.Status = '17'
		OR tbl_jc.Status = '19'
		OR tbl_jc.Status = '20')
		AND
			tbl_jc.SlaCatId > '0'
		AND
		    CompanyId = '6'
		AND
		DATE(tbl_jc.Date2) < DATE(NOW())
		GROUP BY tbl_jc.JobId ";

	if (!empty($cat)) {

		$query .= "AND tbl_jc.SlaCatId = '$cat' ";
	}

	if (!empty($subcat)) {

		$query .= "AND tbl_jc.SlaSubCatId = '$subcat' ";
	}

	$query = mysqli_query($con, $query) or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);

	echo $row['Faults'];

	mysqli_free_result($query);
}

function overdue_bp($con, $cat, $subcat)
{

	$query = "
		SELECT
			tbl_jc.Id AS Faults,
			Date(tbl_jc.Date2)
		FROM
			tbl_jc
		WHERE
			(
				tbl_jc. STATUS = '1'
				OR tbl_jc. STATUS = '2'
				OR tbl_jc. STATUS = '4'
			)
		AND tbl_jc.SlaSubCatId > '0'
		AND CompanyId = '14'
		AND DATE(tbl_jc.Date2) < DATE(NOW())";

	if (!empty($cat)) {

		$query .= "AND tbl_jc.SlaSubCatId = '$cat' ";
	}

	if (!empty($subcat)) {

		$query .= "AND tbl_jc.SlaSubCatId = '$subcat' ";
	}

	$query .= "GROUP BY
		             tbl_jc.JobId";


	$query = mysqli_query($con, $query) or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);

	echo mysqli_num_rows($query);

	mysqli_free_result($query);
}

function hex2rgb($hex)
{

	$hex = str_replace("#", "", $hex);

	if (strlen($hex) == 3) {

		$r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
		$g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
		$b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
	} else {

		$r = hexdec(substr($hex, 0, 2));
		$g = hexdec(substr($hex, 2, 2));
		$b = hexdec(substr($hex, 4, 2));
	}

	$rgb = array($r, $g, $b);
	return implode(",", $rgb); // returns the rgb values separated by commas
}

/* draws a calendar */
function draw_calendar($con, $month, $year)
{

	/* draw table */
	$calendar = '<table cellpadding="0" cellspacing="0" class="calendar">';

	/* table headings */
	$headings = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
	$calendar .= '<tr class="calendar-row"><td class="calendar-day-head">' . implode('</td><td class="calendar-day-head">', $headings) . '</td></tr>';

	/* days and weeks vars now ... */
	$running_day = date('w', mktime(0, 0, 0, $month, 1, $year));
	$days_in_month = date('t', mktime(0, 0, 0, $month, 1, $year));
	$days_in_this_week = 1;
	$day_counter = 0;
	$dates_array = array();

	/* row for week one */
	$calendar .= '<tr class="calendar-row">';

	/* print "blank" days until the first of the current week */
	for ($x = 0; $x < $running_day; $x++) :
		$calendar .= '<td class="calendar-day-np"> </td>';
		$days_in_this_week++;
	endfor;

	/* keep going with days.... */
	for ($list_day = 1; $list_day <= $days_in_month; $list_day++) :
		$calendar .= '<td class="calendar-day">';
		$calendar .= '<a href="events.php?Day=' . $list_day . '&Month=' . $month . '&Year=' . $year . '" class="cal-event various3">';
		/* add in the day number */
		$calendar .= '<div class="day-number">' . $list_day . '</div>';

		$date = date('Y-m-d', strtotime($list_day . '-' . $month . '-' . $year));

		$query = "
				SELECT
					tbl_scheduled_maintenance.Date,
					tbl_scheduled_maintenance.`Status`
				FROM
					tbl_scheduled_maintenance
				WHERE
					tbl_scheduled_maintenance.Date = '$date'
				AND
				   (tbl_scheduled_maintenance.`Status` = 'Qued' OR tbl_scheduled_maintenance.`Status` = 'In Progress')
				AND
				    DATE(tbl_scheduled_maintenance.Date) < DATE(NOW())";

		$query = mysqli_query($con, $query) or die(mysqli_error($con));
		$row = mysqli_fetch_array($query);

		if (mysqli_num_rows($query) >= 1) {

			$calendar .= '<div class="day-alert"></div>';
		}

		if (mysqli_num_rows($query) == 0 && $date < date('Y-m-d')) {

			$query_check = mysqli_query($con, "SELECT * FROM tbl_scheduled_maintenance WHERE Date = '$date'") or die(mysqli_error($con));

			if (mysqli_num_rows($query_check) >= 1) {

				$calendar .= '<div class="day-complete"></div>';
			}
		}


		$calendar .= '<div class="cal-job-container">';

		$date = date('Y-m-d', strtotime($list_day . '-' . $month . '-' . $year));

		$query = "
				SELECT
					tbl_sites.`Name`,
					tbl_scheduled_maintenance.Description,
					tbl_scheduled_maintenance.Date,
					tbl_scheduled_maintenance.`Quarter`,
					tbl_scheduled_maintenance.`Status`
				FROM
					tbl_scheduled_maintenance
				INNER JOIN tbl_sites ON tbl_scheduled_maintenance.SiteId = tbl_sites.Id
				WHERE
					tbl_scheduled_maintenance.Date = '$date'";

		$query = mysqli_query($con, $query) or die(mysqli_error($con));

		while ($row = mysqli_fetch_array($query)) {

			$dots = '';

			if (strlen($row['Name']) > 14) {

				$dots .= '...';
			}

			$class = '';

			if ($row['Date'] < date('Y-m-d') && ($row['Status'] == 'Qued' || $row['Status'] == 'In Progress')) {

				$class = 'red';
			}

			$calendar .= '<span class="cal-job ' . $class . '" title="' . $row['Name'] . ' - ' . $row['Description'] . '">' . substr($row['Name'], 0, 14) . $dots . '</span>';
		}

		/** QUERY THE DATABASE FOR AN ENTRY FOR THIS DAY !!  IF MATCHES FOUND, PRINT THEM !! **/
		$calendar .= '</div>';

		$calendar .= '</a></td>';
		if ($running_day == 6) :
			$calendar .= '</tr>';
			if (($day_counter + 1) != $days_in_month) :
				$calendar .= '<tr class="calendar-row">';
			endif;
			$running_day = -1;
			$days_in_this_week = 0;
		endif;
		$days_in_this_week++;
		$running_day++;
		$day_counter++;
	endfor;

	/* finish the rest of the days in the week */
	if ($days_in_this_week < 8 && $days_in_this_week != 1) :
		for ($x = 1; $x <= (8 - $days_in_this_week); $x++) :
			$calendar .= '<td class="calendar-day-np"> </td>';
		endfor;
	endif;

	/* final row */
	$calendar .= '</tr>';

	/* end the table */
	$calendar .= '</table>';

	/* all done, return result */
	return $calendar;

	mysqli_free_result($query);
}

/* draws a calendar */
function draw_calendar_company($con, $month, $year)
{

	/* draw table */
	$calendar = '<table cellpadding="0" cellspacing="0" class="calendar">';

	/* table headings */
	$headings = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
	$calendar .= '<tr class="calendar-row"><td class="calendar-day-head">' . implode('</td><td class="calendar-day-head">', $headings) . '</td></tr>';

	/* days and weeks vars now ... */
	$running_day = date('w', mktime(0, 0, 0, $month, 1, $year));
	$days_in_month = date('t', mktime(0, 0, 0, $month, 1, $year));
	$days_in_this_week = 1;
	$day_counter = 0;
	$dates_array = array();

	/* row for week one */
	$calendar .= '<tr class="calendar-row">';

	/* print "blank" days until the first of the current week */
	for ($x = 0; $x < $running_day; $x++) :
		$calendar .= '<td class="calendar-day-np"> </td>';
		$days_in_this_week++;
	endfor;

	/* keep going with days.... */
	for ($list_day = 1; $list_day <= $days_in_month; $list_day++) :
		$calendar .= '<td class="calendar-day">';
		$calendar .= '<a href="bp-events.php?Day=' . $list_day . '&Month=' . $month . '&Year=' . $year . '" class="cal-event fancybox">';
		/* add in the day number */
		$calendar .= '<div class="day-number">' . $list_day . '</div>';

		$date = date('Y-m-d', strtotime($list_day . '-' . $month . '-' . $year));

		$query = "
				SELECT
					tbl_scheduled_maintenance.Date,
					tbl_scheduled_maintenance.`Status`
				FROM
					tbl_scheduled_maintenance
				WHERE
					tbl_scheduled_maintenance.Date = '$date'
				AND
				   (tbl_scheduled_maintenance.`Status` = 'Qued' OR tbl_scheduled_maintenance.`Status` = 'In Progress')
				AND
				    DATE(tbl_scheduled_maintenance.Date) < DATE(NOW())";

		$query = mysqli_query($con, $query) or die(mysqli_error($con));
		$row = mysqli_fetch_array($query);

		if (mysqli_num_rows($query) >= 1) {

			$calendar .= '<div class="day-alert"></div>';
		}

		if (mysqli_num_rows($query) == 0 && $date < date('Y-m-d')) {

			$query_check = mysqli_query($con, "SELECT * FROM tbl_scheduled_maintenance WHERE Date = '$date'") or die(mysqli_error($con));

			if (mysqli_num_rows($query_check) >= 1) {

				$calendar .= '<div class="day-complete"></div>';
			}
		}


		$calendar .= '<div class="cal-job-container">';

		$date = date('Y-m-d', strtotime($list_day . '-' . $month . '-' . $year));

		$query = "
				SELECT
					tbl_sites.`Name`,
					tbl_scheduled_maintenance.Description,
					tbl_scheduled_maintenance.Date,
					tbl_scheduled_maintenance.`Quarter`,
					tbl_scheduled_maintenance.`Status`
				FROM
					tbl_scheduled_maintenance
				INNER JOIN tbl_sites ON tbl_scheduled_maintenance.SiteId = tbl_sites.Id
				WHERE
					tbl_scheduled_maintenance.Date = '$date'";

		$query = mysqli_query($con, $query) or die(mysqli_error($con));

		while ($row = mysqli_fetch_array($query)) {

			$dots = '';

			if (strlen($row['Name']) > 14) {

				$dots .= '...';
			}

			$class = '';

			if ($row['Date'] < date('Y-m-d') && ($row['Status'] == 'Qued' || $row['Status'] == 'In Progress')) {

				$class = 'red';
			}

			$calendar .= '<span class="cal-job ' . $class . '" title="' . $row['Name'] . ' - ' . $row['Description'] . '">' . substr($row['Name'], 0, 14) . $dots . '</span>';
		}

		/** QUERY THE DATABASE FOR AN ENTRY FOR THIS DAY !!  IF MATCHES FOUND, PRINT THEM !! **/
		$calendar .= '</div>';

		$calendar .= '</a></td>';
		if ($running_day == 6) :
			$calendar .= '</tr>';
			if (($day_counter + 1) != $days_in_month) :
				$calendar .= '<tr class="calendar-row">';
			endif;
			$running_day = -1;
			$days_in_this_week = 0;
		endif;
		$days_in_this_week++;
		$running_day++;
		$day_counter++;
	endfor;

	/* finish the rest of the days in the week */
	if ($days_in_this_week < 8 && $days_in_this_week != 1) :
		for ($x = 1; $x <= (8 - $days_in_this_week); $x++) :
			$calendar .= '<td class="calendar-day-np"> </td>';
		endfor;
	endif;

	/* final row */
	$calendar .= '</tr>';

	/* end the table */
	$calendar .= '</table>';

	/* all done, return result */
	return $calendar;

	mysqli_free_result($query);
}

function work_hours_diff($date1, $date2)
{
	if ($date1 > $date2) {
		$tmp = $date1;
		$date1 = $date2;
		$date2 = $tmp;
		unset($tmp);
		$sign = -1;
	} else $sign = 1;
	if ($date1 == $date2) return 0;

	$days = 0;
	$working_days = array(1, 2, 3, 4, 5); // Monday-->Friday
	$holidays = array(
		'2015-01-01', '2015-03-21', '2015-04-03', '2015-04-06', '2015-04-27', '2015-05-01',
		'2015-06-16', '2015-08-10', '2015-09-24', '2015-12-16', '2015-12-25', '2015-12-26'
	);
	$working_hours = array(8.0, 16.5); // from 8:30(am) to 17:30
	$current_date = $date1;
	$beg_h = floor($working_hours[0]);
	$beg_m = ($working_hours[0] * 60) % 60;
	$end_h = floor($working_hours[1]);
	$end_m = ($working_hours[1] * 60) % 60;

	// setup the very next first working timestamp

	if (!in_array(date('w', $current_date), $working_days)) {
		// the current day is not a working day

		// the current timestamp is set at the begining of the working day
		$current_date = mktime($beg_h, $beg_m, 0, date('n', $current_date), date('j', $current_date), date('Y', $current_date));
		// search for the next working day
		while (!in_array(date('w', $current_date), $working_days)) {
			$current_date += 24 * 3600; // next day
		}
	} else {
		// check if the current timestamp is inside working hours

		$date0 = mktime($beg_h, $beg_m, 0, date('n', $current_date), date('j', $current_date), date('Y', $current_date));
		// it's before working hours, let's update it
		if ($current_date < $date0) $current_date = $date0;

		$date3 = mktime($end_h, $end_m, 59, date('n', $current_date), date('j', $current_date), date('Y', $current_date));
		if ($date3 < $current_date) {
			// outch ! it's after working hours, let's find the next working day
			$current_date += 24 * 3600; // the day after
			// and set timestamp as the begining of the working day
			$current_date = mktime($beg_h, $beg_m, 0, date('n', $current_date), date('j', $current_date), date('Y', $current_date));
			while (!in_array(date('w', $current_date), $working_days)) {
				$current_date += 24 * 3600; // next day
			}
		}
	}

	// so, $current_date is now the first working timestamp available...

	// calculate the number of seconds from current timestamp to the end of the working day
	$date0 = mktime($end_h, $end_m, 59, date('n', $current_date), date('j', $current_date), date('Y', $current_date));
	$seconds = $date0 - $current_date + 1;

	//printf("\nFrom %s To %s : %d hours\n",date('d/m/y H:i',$date1),date('d/m/y H:i',$date0),$seconds/3600);

	// calculate the number of days from the current day to the end day

	$date3 = mktime($beg_h, $beg_m, 0, date('n', $date2), date('j', $date2), date('Y', $date2));
	while ($current_date < $date3) {
		$current_date += 24 * 3600; // next day
		if (in_array(date('w', $current_date), $working_days)) $days++; // it's a working day
	}
	if ($days > 0) $days--; //because we've allready count the first day (in $seconds)

	//printf("\nFrom %s To %s : %d working days\n",date('d/m/y H:i',$date1),date('d/m/y H:i',$date3),$days);

	// check if end's timestamp is inside working hours
	$date0 = mktime($beg_h, 0, 0, date('n', $date2), date('j', $date2), date('Y', $date2));
	if ($date2 < $date0) {
		// it's before, so nothing more !
	} else {
		// is it after ?
		$date3 = mktime($end_h, $end_m, 59, date('n', $date2), date('j', $date2), date('Y', $date2));
		if ($date2 > $date3) $date2 = $date3;
		// calculate the number of seconds from current timestamp to the final timestamp
		$tmp = $date2 - $date0 + 1;
		$seconds += $tmp;
		//printf("\nFrom %s To %s : %d hours\n",date('d/m/y H:i',$date2),date('d/m/y H:i',$date3),$tmp/3600);
	}

	// calculate the working days in seconds

	$seconds += 3600 * ($working_hours[1] - $working_hours[0]) * $days;

	//printf("\nFrom %s To %s : %d hours\n",date('d/m/y H:i',$date1),date('d/m/y H:i',$date2),$seconds/3600);

	//return $sign * $seconds/3600; // to get hours

	$sla = $sign * $seconds / 3600; // to get hours

	for ($i = 0; $i < count($holidays); $i++) {

		if (date('Y-m-d', strtotime(date('Y-m-d', $date1))) <= $holidays[$i] && date('Y-m-d', strtotime(date('Y-m-d', $date2))) >= $holidays[$i]) {

			if (date('w', strtotime($holidays[$i])) != 6 && date('w', strtotime($holidays[$i])) != 7) {

				$sla -= 8.5;
			}
		}
	}

	return $sla;
}

function addWorkingHours($timestamp, $hoursToAdd, $skipWeekends = false)
{
	// Set constants
	$dayStart = 8;
	$dayEnd = 16;

	// For every hour to add
	for ($i = 0; $i < $hoursToAdd; $i++) {
		// Add the hour
		$timestamp += 3600;

		// If the time is between 1800 and 0800
		if ((date('G', $timestamp) >= $dayEnd && date('i', $timestamp) >= 0 && date('s', $timestamp) > 0) || (date('G', $timestamp) < $dayStart)) {
			// If on an evening
			if (date('G', $timestamp) >= $dayEnd) {
				// Skip to following morning at 08XX
				$timestamp += 3600 * ((24 - date('G', $timestamp)) + $dayStart);
			}
			// If on a morning
			else {
				// Skip forward to 08XX
				$timestamp += 3600 * ($dayStart - date('G', $timestamp));
			}
		}

		// If the time is on a weekend
		if ($skipWeekends && (date('N', $timestamp) == 6 || date('N', $timestamp) == 7)) {
			// Skip to Monday
			$timestamp += 3600 * (24 * (8 - date('N', $timestamp)));
		}
	}

	// Return
	return $timestamp;
}

function addRollover($givenDate, $addtime, $dayStart, $dayEnd, $weekDaysOnly)
{
	//Break the working day start and end times into hours, minuets
	$dayStart = explode(':', $dayStart);
	$dayEnd = explode(':', $dayEnd);
	//Create required datetime objects and hours interval
	$datetime = new DateTime($givenDate);
	$endofday = clone $datetime;
	$endofday->setTime($dayEnd[0], $dayEnd[1]); //set end of working day time
	$interval = 'PT' . $addtime . 'H';
	//Add hours onto initial given date
	$datetime->add(new DateInterval($interval));
	//if initial date + hours is after the end of working day
	if ($datetime > $endofday) {
		//get the difference between the initial date + interval and the end of working day in seconds
		$seconds = $datetime->getTimestamp() - $endofday->getTimestamp();

		//Loop to next day
		while (true) {
			$endofday->add(new DateInterval('PT24H')); //Loop to next day by adding 24hrs
			$nextDay = $endofday->setTime($dayStart[0], $dayStart[1]); //Set day to working day start time
			$holidays = array(
				'2015-01-01', '2015-03-21', '2015-04-03', '2015-04-06', '2015-04-27', '2015-05-01',
				'2015-06-16', '2015-08-10', '2015-09-24', '2015-12-16', '2015-12-25', '2015-12-26'
			);

			//If the next day is on a weekend and the week day only param is true continue to add days
			if ((in_array($nextDay->format('l'), array('Sunday', 'Saturday')) || in_array($endofday->format('Y-m-d'), $holidays)) && $weekDaysOnly) {
				continue;
			} else //If not a weekend
			{
				$tmpDate = clone $nextDay;
				$tmpDate->setTime($dayEnd[0], $dayEnd[1]); //clone the next day and set time to working day end time
				$nextDay->add(new DateInterval('PT' . $seconds . 'S')); //add the seconds onto the next day
				//if the next day time is later than the end of the working day continue loop
				if ($nextDay > $tmpDate) {
					$seconds = $nextDay->getTimestamp() - $tmpDate->getTimestamp();
					$endofday = clone $tmpDate;
					$endofday->setTime($dayStart[0], $dayStart[1]);
				} else //else return the new date.
				{
					return $endofday;
				}
			}
		}
	}
	return $datetime;
}

function convertTime($dec)
{

	// start by converting to seconds
	$seconds = ($dec * 3600);
	// we're given hours, so let's get those the easy way
	$hours = floor($dec);
	// since we've "calculated" hours, let's remove them from the seconds variable
	$seconds -= $hours * 3600;
	// calculate minutes left
	$minutes = floor($seconds / 60);
	// remove those from seconds as well
	$seconds -= $minutes * 60;
	// return the time formatted HH:MM:SS
	return lz($hours) . ":" . lz($minutes) . ":" . lz($seconds);
}

// lz = leading zero
function lz($num)
{

	return (strlen($num) < 2) ? "0{$num}" : $num;
}

function tech_name($con, $techid)
{

	$query = mysqli_query($con, "SELECT * FROM tbl_technicians WHERE Id = '$techid'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);

	if (mysqli_num_rows($query) >= 1) {

		echo $row['Name'];
	} else {

		echo 'None Selected';
	}
}

function field_val($post, $default)
{

	if (empty($post) || $post == '0') {

		echo 0;
	} else {

		echo $post;
	}
}

function word_limit($string, $limit)
{

	$words = explode(" ", $string);

	for ($i = 0; $i <= $limit; $i++) {

		if ($i == 0) {

			$description = $words[$i];
		} else {

			$description .= ' ' . $words[$i];
		}
	}

	$count = count($words);

	if ($count > $limit) {

		$dots = '....';
	}

	echo strip_tags($description . $dots);
}

function char_limit($string, $limit)
{

	$dots = '...';

	$string = strip_tags($string);

	if (strlen($string) > $limit) {

		// truncate string
		$stringCut = substr($string, 0, $limit) . $dots;
	} else {

		$stringCut = $string;
	}

	return $stringCut;
}

function non_conformance_reports($con, $techid, $month, $year, $i)
{

	$query = "
		SELECT
			*
		FROM
			tbl_profile_ncr
		WHERE
			TechId = '$techid'
		AND MONTH (`Date`) = '$month'
		AND YEAR (`Date`) = '$year'";

	$query = mysqli_query($con, $query) or die(mysqli_error($con));

	if (mysqli_num_rows($query) >= 1) {

		$_SESSION['row' . $i] = $i;

		echo '<span class="red"><b>' . mysqli_num_rows($query) . '</b></span>';
	} else {

		echo mysqli_num_rows($query);
	}

	mysqli_free_result($query);
}

function non_conformance_totals($con, $techid, $year)
{

	$query = "
		SELECT
			*
		FROM
			tbl_profile_ncr
		WHERE
			TechId = '$techid'
		AND YEAR (`Date`) = '$year'";

	$query = mysqli_query($con, $query) or die(mysqli_error($con));

	if (mysqli_num_rows($query) >= 1) {

		echo '<span class="red"><b>' . mysqli_num_rows($query) . '</b></span>';
	} else {

		echo mysqli_num_rows($query);
	}

	mysqli_free_result($query);
}

function profile_name($con, $profileid)
{

	$query = mysqli_query($con, "SELECT Name FROM tbl_technicians WHERE Id = '$profileid'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);

	echo $row['Name'];

	mysqli_free_result($query);
}

function rotateImage($sourceFile, $destImageName, $degreeOfRotation)
{
	//function to rotate an image in PHP
	//developed by Roshan Bhattara (http://roshanbh.com.np)

	//get the detail of the image
	$imageinfo = getimagesize($sourceFile);
	switch ($imageinfo['mime']) {
			//create the image according to the content type
		case "image/jpg":
		case "image/jpeg":
		case "image/pjpeg": //for IE
			$src_img = imagecreatefromjpeg("$sourceFile");
			break;
		case "image/gif":
			$src_img = imagecreatefromgif("$sourceFile");
			break;
		case "image/png":
		case "image/x-png": //for IE
			$src_img = imagecreatefrompng("$sourceFile");
			break;
	}
	//rotate the image according to the spcified degree
	$src_img = imagerotate($src_img, $degreeOfRotation, 0);
	//output the image to a file
	imagejpeg($src_img, $destImageName);
}

function sla_indicator_icon($start, $end)
{

	$start = strtotime($start);
	$end = strtotime($end);

	$total_seconds = $end - $start;

	$alert_seconds = ($total_seconds / 100) * 70;

	$alert_date = date('Y-m-d H:i:s', ($start + $alert_seconds));

	if (strtotime(date('Y-m-d H:i:s')) > $end) {

		echo '<img src="../images/icons/sla-red.png">';
	}

	if (date('Y-m-d H:i:s') > $alert_date && strtotime(date('Y-m-d H:i:s')) < $end) {

		echo '<img class="blink_me" src="../images/icons/sla-amber.png">';
	}

	if (date('Y-m-d H:i:s') < $alert_date) {

		echo '<img src="../images/icons/sla-green.png">';
	}
}

function sla_indicator_blink($start, $end)
{

	$start = strtotime($start);
	$end = strtotime($end);

	$total_seconds = $end - $start;

	$alert_seconds = ($total_seconds / 100) * 70;

	$alert_date = date('Y-m-d H:i:s', ($start + $alert_seconds));

	if (date('Y-m-d H:i:s') > $alert_date && date('Y-m-d H:i:s') < date('Y-m-d H:i:s', $end)) {

		echo ' blink_me';
	}

	if (date('Y-m-d H:i:s') > date('Y-m-d H:i:s', $end)) {

		echo ' sla-expired';
	}
}

function sla_indicator_blink2($start, $end)
{

	$start = strtotime($start);
	$end = strtotime($end);

	$total_seconds = $end - $start;

	$alert_seconds = ($total_seconds / 100) * 70;

	$alert_date = date('Y-m-d H:i:s', ($start + $alert_seconds));

	if (date('Y-m-d H:i:s') > $alert_date && date('Y-m-d H:i:s') < date('Y-m-d H:i:s', $end)) {

		return ' blink_me';
	}

	if (date('Y-m-d H:i:s') > date('Y-m-d H:i:s', $end)) {

		return ' sla-expired';
	}
}

function profile_mail($to, $subject, $body)
{

	date_default_timezone_set('Etc/UTC');

	require  DOMAIN . '/PHPMailer/PHPMailerAutoload.php';

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
	$mail->Host = "www27.jnb1.host-h.net";
	//Set the SMTP port number - likely to be 25, 465 or 587
	$mail->Port = 587;
	//Whether to use SMTP authentication
	$mail->SMTPAuth = true;
	//Username to use for SMTP authentication
	$mail->Username = "test@kwd.co.za";
	//Password to use for SMTP authentication
	$mail->Password = "K4rsten001";
	//Set who the message is to be sent from
	$mail->setFrom('test@kwd.co.za', 'Seavest Africa');
	//Set an alternative reply-to address
	$mail->addReplyTo('test@kwd.co.za', 'Seavest Africa');
	//Set who the message is to be sent to
	$mail->addAddress($to, 'Seavest Africa');
	$mail->addCC('marcus.abrahams@seavest.co.za', 'Seavest Africa');
	//Set the subject line
	$mail->Subject = $subject;
	//Read an HTML message body from an external file, convert referenced images to embedded,
	//convert HTML into a basic plain-text alternative body
	$mail->msgHTML($html = $body);
	//Replace the plain text body with one created manually
	$mail->AltBody = 'This is a plain-text message body';
	//Attach an image file
	$mail->addAttachment('images/phpmailer_mini.png');

	//send the message, check for errors
	if (!$mail->send()) {
	} else {
	}
}

function sent_invoices($con, $jobid, $pdf)
{

	$query = "
	  SELECT
		  `tbl_companies`.`Name` AS CompanyName
		  , `tbl_sites`.`Name` AS SiteName
		  , `tbl_jc`.`JobId`
		  , `tbl_jc`.`InvoiceNo`
	  FROM
		  `tbl_jc`
		  INNER JOIN `tbl_companies`
			  ON (`tbl_jc`.`CompanyId` = `tbl_companies`.`Id`)
		  INNER JOIN `tbl_sites`
			  ON (`tbl_jc`.`SiteId` = `tbl_sites`.`Id`)
	  WHERE tbl_jc.JobId = '$jobid'";

	$query = mysqli_query($con, $query) or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);

	$company = $row['CompanyName'];
	$site = $row['SiteName'];
	$inv = $row['InvoiceNo'];
	$date = date('d M Y H:i:s');

	mysqli_query($con, "INSERT INTO tbl_sent_invoices (JobId,InvoiceNo,CompanyId,SiteId,Pdf,DateSent,Sent)
	VALUES ('$jobid','$inv','$company','$site','$pdf','$date','1')") or die(mysqli_error($con));
}

function batch_labour_totals($con, $jobid, $col)
{

	$query = mysqli_query($con, "SELECT SUM($col) AS $col FROM tbl_jc WHERE Labour = '1' AND JobId = '$jobid'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);

	return $row[$col];
}

function batch_material_totals($con, $jobid, $cat)
{

	$query = mysqli_query($con, "SELECT SUM(Total1), Description FROM tbl_jc WHERE Material = '1' AND JobId = '$jobid' GROUP BY JobId") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);

	if ($cat == 'Description') {

		return $row['Description'];
	} else {

		return $row['SUM(Total1)'];
	}
}

function batch_transport_totals($con, $jobid, $cat)
{

	$query = mysqli_query($con, "SELECT SUM(Description) AS Description, SUM(Qty) AS Qty, SUM(Price) AS Price FROM tbl_travel WHERE JobId = '$jobid' GROUP BY JobId") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);

	$total = $row['Description'] * $row['Qty'] * $row['Price'];
	$mileage = $row['Description'] * $row['Qty'];

	if ($cat == 'Total') {

		return $total;
	} elseif ($cat == 'Mileage') {

		return $mileage;
	}
}

function invno($con)
{

	mysqli_query($con, "INSERT INTO tbl_invoice_numbers (JobNo) VALUES ('1')") or die(mysql_error());

	$query_invno = mysqli_query($con, "SELECT * FROM tbl_invoice_numbers ORDER BY Id DESC LIMIT 1") or die(mysqli_error($con));
	$row_invno = mysqli_fetch_array($query_invno);

	return $row_invno['Id'] + 1;
}

function getVatRate($con, $date)
{

	$sql = "
		SELECT
			VatRate
		FROM
			tbl_vat
		WHERE
			StartDate <= '$date'
		ORDER BY
			Id DESC
		LIMIT 1
	";

	$query = mysqli_query($con, $sql) or die(mysqli_error($con));
	$row_vat = mysqli_fetch_array($query);

	return $row_vat['VatRate'];
}

function getInvVatRate($con, $jobid)
{

	if (empty($jobid)) {

		$jobid = -1;
	}

	$sql = "
		SELECT
			NewInvoiceDate
		FROM
			tbl_jc
		WHERE
			JobId = $jobid
	";

	$query = mysqli_query($con, $sql) or die(mysqli_error($con));
	$row_date = mysqli_fetch_array($query);

	if (mysqli_num_rows($query) >= 1) {

		$date = $row_date['NewInvoiceDate'];

		$sql = "
			SELECT
				VatRate
			FROM
				tbl_vat
			WHERE
				'$date' >= StartDate
			AND
				'$date' <= EndDate
			ORDER BY
				Id DESC
			LIMIT 1
		";

		$query_vat = mysqli_query($con, $sql) or die(mysqli_error($con));
		$row_vat = mysqli_fetch_array($query_vat);

		return $row_vat['VatRate'];
	}
}

function system_dd($con)
{

	if ($_SESSION['ChangeSystems'] == 1) {
		$type = '';
		if (isset($_GET['Type'])) {
			$type = '&Type=' . $_GET['Type'];
		}

		echo '<select name="system" class="area-dd" onchange="MM_jumpMenu(\'parent\',this,0)">';

		$sql = "
			SELECT
				Id,
				Name
			FROM
				tbl_systems
			ORDER BY
				Id ASC
		";

		$sql = mysqli_query($con, $sql) or die(mysqli_error($con));
		while ($row = mysqli_fetch_array($sql)) {

			$selected = '';

			if (!isset($_SESSION['system']) && $row['Id'] == 3) {
				$selected = ' selected="selected"';
			} else if ($_SESSION['system'] == $row['Id']) {

				$selected = ' selected="selected"';
			}

			echo '<option value="' . $_SERVER['PHP_SELF'] . '?System=' . $row['Id'] . $type . '"' . $selected . '>' . $row['Name'] . '</option>';
		}

		echo '</select>';
	}
}

function type_select($con)
{

	if ($_SESSION['ChangeSystems'] == 1) {
		$system = '';
		if (isset($_GET['System'])) {
			$system = '&System=' . $_GET['System'];
		}

		echo '<select name="type" class="area-dd" onchange="MM_jumpMenu(\'parent\',this,0)">';

		$sql = "
			SELECT
				Id,
				Category
			FROM
				tbl_sla_cat
			WHERE
				Module <> 'Est'
			ORDER BY
				Id ASC
		";

		$sql = mysqli_query($con, $sql) or die(mysqli_error($con));
		while ($row = mysqli_fetch_array($sql)) {

			$selected = '';

			if ($_SESSION['type'] == $row['Id']) {

				$selected = ' selected="selected"';
			}

			echo '<option value="' . $_SERVER['PHP_SELF'] . '?Type=' . $row['Id'] . $system . '"' . $selected . '>' . $row['Category'] . '</option>';
		}
		echo '</select>';
	}
}

function system_select()
{

	if (isset($_GET['System'])) {

		if ($_GET['System'] == 3) {

			unset($_SESSION['system']);
		} else {

			$_SESSION['system'] = $_GET['System'];
		}

		// $start = strpos($_SERVER['REQUEST_URI'], 'System');
		// $url = substr($_SERVER['REQUEST_URI'], 0, -9);

		// header('Location:' . $url);
	} else {

		// unset($_SESSION['system']);
	}
}

function type_getter()
{
	if (isset($_GET['Type'])) {
		if ($_GET['Type'] == 8) {
			unset($_SESSION['type']);
		}
		$_SESSION['type'] = $_GET['Type'];
	}
}

function system_parameters($tbl = NULL)
{
	if ($tbl != NULL) {
		$parameter = '';

		if (isset($_SESSION['system'])) {

			$parameter = ($_SESSION['system'] != 3) ? "  AND " . $tbl . ".SystemId = " . $_SESSION['system'] . " " : '';
		}

		if (isset($_SESSION['type'])) {

			$parameter .= ($_SESSION['type'] != 8) ? " AND " . $tbl . ".SlaCatId = " . $_SESSION['type'] . " " : '';
		}

		return $parameter;
	}
}

function sendSms($message, $to)
{
	$apiKey = 'VqZr0yHTSSeAnN0sf75vIQ==';
	$content = str_replace(' ', '+', $message);
	$curl = curl_init();

	curl_setopt_array($curl, [
		CURLOPT_URL => "https://platform.clickatell.com/messages/http/send?apiKey=" . $apiKey . "&to=" . $to . "&content=" . $content,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET",
	]);

	$response = curl_exec($curl);

	curl_close($curl);
	return $response;
}
