<?php
session_start();

require_once('../functions/functions.php');

$Recordset1 = mysqli_query($con, "SELECT * FROM tbl_companies") or die(mysqli_error($con));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);

$Recordset2 = mysqli_query($con, "SELECT * FROM tbl_sites") or die(mysqli_error($con));
$row_Recordset2 = mysqli_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysqli_num_rows($Recordset2);

if (isset($_GET['Company'])) {

    $where = 'WHERE tbl_remittance.CompanyId = ' . $_GET['Company'];
}

if (isset($_POST['submit'])) {

    $var = $_POST['search'];
    $date = $_POST['date'];

    if ($_POST['search'] != 'Search') {

        $where = "WHERE tbl_remittance.Amount = '$var' OR tbl_remittance_details.InvoiceNo = '$var' OR tbl_remittance_details.JobNo = '$var' OR tbl_remittance_details.InvoiceNo = '$var' OR tbl_remittance_details.Amount = '$var'";
    }

    if (!empty($_POST['date'])) {

        $where = "WHERE tbl_remittance.Date = '$date'";
    }
}

$query_Recordset3 = "
	SELECT 
	  tbl_users.Name AS Name_2,
	  tbl_sites.Name AS Name_1,
	  tbl_remittance_details.Amount AS Amount_1,
	  tbl_remittance_details.JobNo,
	  tbl_remittance_details.InvoiceNo,
	  tbl_remittance.Amount,
	  tbl_remittance_details.InvoiceDate,
	  tbl_companies.Name,
	  tbl_remittance.Date,
	  tbl_remittance.Id,
	  tbl_remittance.UserId 
	FROM
	  (
		(
		  (
			(
			  tbl_remittance 
			  LEFT JOIN tbl_remittance_details 
				ON tbl_remittance_details.RemittanceId = tbl_remittance.Id
			) 
			LEFT JOIN tbl_companies 
			  ON tbl_companies.Id = tbl_remittance.CompanyId
		  ) 
		  LEFT JOIN tbl_sites 
			ON tbl_sites.Id = tbl_remittance_details.SiteId
		) 
		LEFT JOIN tbl_users 
		  ON tbl_users.Id = tbl_remittance.UserId
	  ) $where 
	GROUP BY tbl_remittance_details.RemittanceId 
	ORDER BY tbl_remittance.Date DESC";

$Recordset3 = mysqli_query($con, $query_Recordset3) or die(mysqli_error($con));
$row_Recordset3 = mysqli_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysqli_num_rows($Recordset3);

$Recordset4 = mysqli_query($con, "SELECT * FROM tbl_companies ORDER BY Name ASC") or die(mysqli_error($con));
$row_Recordset4 = mysqli_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysqli_num_rows($Recordset4);

$id = $_GET['Id'];

$query_remittance = mysqli_query($con, "SELECT * FROM tbl_remittance WHERE Id = '$id'") or die(mysqli_error($con));
$row_remittance = mysqli_fetch_array($query_remittance);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Seavest Asset Management</title>

    <link href="../css/layout.css" rel="stylesheet" type="text/css" />
    <link href="../css/fonts.css" rel="stylesheet" type="text/css" />
    <link href="../css/breadcrumbs.css" rel="stylesheet" type="text/css" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

    <link rel="stylesheet" href="../menu/styles.css">
    <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
    <script src="../menu/script.js"></script>

    <link rel="stylesheet" media="all" type="text/css" href="../jquery-ui.css" />
    <link rel="stylesheet" media="all" type="text/css" href="../jquery-ui-timepicker-addon.css" />

    <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
    <script type="text/javascript" src="../jquery-ui-timepicker-addon.js"></script>
    <script type="text/javascript" src="../jquery-ui-sliderAccess.js"></script>

    <script type="text/javascript" src="../highslide/highslide-with-html.js"></script>
    <link rel="stylesheet" type="text/css" href="../highslide/highslide.css" />


    <script type="text/javascript">
        hs.graphicsDir = '../highslide/graphics/';
        hs.outlineType = 'rounded-white';
    </script>

    <script type="text/javascript">
        function MM_jumpMenu(targ, selObj, restore) { //v3.0
            eval(targ + ".location='" + selObj.options[selObj.selectedIndex].value + "'");
            if (restore) selObj.selectedIndex = 0;
        }
    </script>

</head>

<body>

    <!-- Banner -->
    <div id="logo">
        <?php logout_link(); ?>
        <div id="tab-user"><?php echo $_COOKIE['name']; ?></div>
        <?php area_select($con); ?>
    </div>
    <!-- End Banner -->

    <!-- Navigatiopn -->
    <?php include('../menu/menu.php'); ?>
    <!-- End Navigation -->

    <!-- Breadcrumbs -->
    <div class="td-bread">
        <ul class="breadcrumb">
            <li><a href="#">Seavest Asset Management</a></li>
            <li><a href="#">Accounts</a></li>
            <li><a href="#">Invoices</a></li>
            <li><a href="#">Remittance History</a></li>
            <li></li>
        </ul>
    </div>
    <!-- End Breadcrumbs -->

    <!-- Search -->
    <div class="search-container">
        <form id="form1" name="form1" method="post" action="">
            <input name="textfield" type="text" class="search-top" id="textfield" value="Search..." />
            <input name="button" type="submit" class="search-top-btn" id="button" value="" />
        </form>
    </div>
    <!-- End Search -->

    <!-- Main Form -->
    <div id="main-wrapper">
        <form action="" method="post" enctype="multipart/form-data" name="form2">
            <div id="list-border">
                <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
                    <tr>
                        <td colspan="7" align="left" nowrap class="td-right">
                            <?php
                            if (isset($_GET['Id'])) {

                                $to = $row_remittance['Email'];
                            } else {

                                $to = 'To';
                            }
                            ?>
                            <input name="email" type="text" class="tarea-100" id="email" value="<?php echo $to; ?>" onfocus="if (this.value=='To') this.value='';" onblur="if (this.value.replace(/\s/g,'')=='') this.value='To';">
                        </td>
                    </tr>

                    <tr>
                        <td colspan="7" align="left" nowrap class="td-right">
                            <?php
                            if (isset($_GET['Id'])) {

                                $var = $row_remittance['Message'];
                            } else {

                                $var = 'Message';
                            }
                            ?>
                            <textarea name="message" rows="5" class="tarea-100" id="message" onfocus="if (this.value=='Message') this.value='';" onblur="if (this.value.replace(/\s/g,'')=='') this.value='Message';"><?php echo $var; ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="7" align="right" nowrap class="td-right">
                            <?php
                            if (isset($_GET['Id'])) {

                                $var = $row_remittance['Amount'];
                            } else {

                                $var = 'Amount';
                            }
                            ?>
                            <input name="amount" type="text" class="tarea-100" id="amount" value="<?php echo $var; ?>" onfocus="if (this.value=='Amount') this.value='';" onblur="if (this.value.replace(/\s/g,'')=='') this.value='Amount';">
                        </td>
                    </tr>
                </table>
            </div>
            <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
                <tr>
                    <td width="173" colspan="7" align="right" nowrap class="combo"><input name="Submit2" type="submit" class="btn-new" value="Send" /></td>
                </tr>
            </table>
            <table width="100%" border="0" cellpadding="3" cellspacing="1">
                <tr>
                    <td width="270">
                        <select name="jumpMenu" class="select-2-dd" id="jumpMenu" onchange="MM_jumpMenu('parent',this,0)">
                            <?php do { ?>
                                <option value="remittance.php?Company=<?php echo $row_Recordset4['Id']; ?>" <?php if ($_GET['Company'] == $row_Recordset4['Id']) {
                                                                                                                echo 'selected="selected"';
                                                                                                            } ?>><?php echo $row_Recordset4['Name']; ?></option>
                            <?php } while ($row_Recordset4 = mysqli_fetch_assoc($Recordset4)); ?>
                        </select>
                    </td>
                    <td width="150">
                        <input name="date" class="select-2" id="date" value="Date" onfocus="if(this.value=='Date') this.value='';" onblur="if(this.value.replace(/\s/g,'')=='') this.value='Date';" />

                        <script type="text/javascript">
                            $('#date').datepicker({
                                dateFormat: "yy-mm-dd"
                            });
                        </script>
                    </td>
                    <td><input name="search" type="text" class="select-2" id="search" value="Search" onfocus="if(this.value=='Search') this.value='';" onblur="if(this.value.replace(/\s/g,'')=='') this.value='Search';" /></td>
                    <td width="30" align="right"><input name="submit" type="submit" class="btn-new-2" id="submit" value="Search" /></td>
                </tr>
            </table>
        </form>
        <table width="100%" border="0" cellpadding="3" cellspacing="1">
            <tr>
                <td colspan="10" align="right"><span class="total-container">Total: <?php sum_remittance($con); ?></span></td>
            </tr>
        </table>
        <table width="100%" cellpadding="3" cellspacing="1">
            <tr>
                <td width="50" align="center" class="td-header">No.</td>
                <td align="left" class="td-header"><strong>Company</strong></td>
                <td width="173" align="left" class="td-header"><strong>Date</strong></td>
                <td width="75" align="left" class="td-header">Total</td>
                <td width="20" align="center" class="td-header-right">&nbsp;</td>
                <td width="20" align="center" class="td-header-right">&nbsp;</td>
                <td width="20" align="center" class="td-header-right">&nbsp;</td>
            </tr>
            <?php
            $i = 0;
            do {

                $i++;

                $jobid = $row_Recordset3['JobId'];
            ?>
                <tr class="<?php echo ($ac_sw1++ % 2 == 0) ? " odd" : "even"; ?>
                  " onMouseOver="this.oldClassName = this.className; this.className='over';" onMouseOut="this.className = this.oldClassName;">
                    <td align="center" nowrap class="combo"><a href="remittance.php?Id=<?php echo $row_Recordset3['Id']; ?>" class="menu"><?php echo $row_Recordset3['Id']; ?></a></td>
                    <td nowrap class="combo"><a href="remittance.php?Id=<?php echo $row_Recordset3['Id']; ?>" class="menu"><?php echo $row_Recordset3['Name']; ?></a></td>
                    <td class="combo"><a href="remittance.php?Id=<?php echo $row_Recordset3['Id']; ?>" class="menu"><?php echo $row_Recordset3['Date']; ?></a></td>
                    <td nowrap class="combo"><a href="remittance.php?Id=<?php echo $row_Recordset3['Id']; ?>" class="menu">R<?php echo $row_Recordset3['Amount']; ?></a></td>
                    <td align="center">
                        <a href="pdf/pdf/Seavest Remittance <?php echo $row_Recordset3['Id']; ?>.pdf" onclick="return hs.htmlExpand(this, { contentId: 'highslide-html<?php echo $i; ?>' } )" class="highslide view"></a>
                        <?php
                        $id = $row_Recordset3['Id'];

                        $query = mysqli_query($con, "SELECT * FROM tbl_remittance_details WHERE RemittanceId = '$id'") or die(mysqli_error($con));

                        $query2 = "
						  SELECT 
							tbl_companies.Name,
							tbl_remittance.Date,
							tbl_remittance.DateSubmitted,
							tbl_remittance.Amount,
							tbl_remittance.Discount,
							tbl_users.Name AS Name_2,
							tbl_remittance.Id 
						  FROM
							tbl_remittance 
							INNER JOIN tbl_companies 
							  ON (
								tbl_remittance.CompanyId = tbl_companies.Id
							  ) 
							INNER JOIN tbl_users 
							  ON (
								tbl_remittance.UserId = tbl_users.Id
							  ) 
						  WHERE (tbl_remittance.Id = '$id')";

                        $query2 = mysqli_query($con, $query2) or die(mysqli_error($con));
                        $row2 = mysqli_fetch_array($query2);

                        ?>
                        <div class="highslide-html-content" id="highslide-html<?php echo $i; ?>">
                            <div class="highslide-header">
                                <ul>
                                    <li class="highslide-close">
                                        <a href="#" onclick="return hs.close(this)" class="close"></a>
                                    </li>
                                </ul>
                            </div>
                            <div class="highslide-body">
                                <table width="600" border="0" cellpadding="4" cellspacing="1">
                                    <tr>
                                        <td colspan="5">
                                            <table border="0" class="comb-sms">
                                                <tr>
                                                    <td width="120" class="td-header"><strong>Client</strong></td>
                                                    <td class="even" width="200"><?php echo $row2['Name']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td width="120" class="td-header"><strong>Payment Received</strong></td>
                                                    <td class="odd"><?php echo $row2['Date']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td width="120" class="td-header"><strong>Date Submitted</strong></td>
                                                    <td class="even"><?php echo $row2['DateSubmitted']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td width="120" class="td-header"><strong>Batch Amount</strong></td>
                                                    <td class="odd">R<?php echo $row2['Amount']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td width="120" class="td-header"><strong>Remitted By</strong></td>
                                                    <td class="even"><?php echo $row2['Name_2']; ?></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5">&nbsp;</td>
                                    </tr>
                                    <tr class="td-header">
                                        <td width="50">Inv No</td>
                                        <td width="50">Job No</td>
                                        <td>Site</td>
                                        <td width="100">Date</td>
                                        <td width="75" align="right">Total</td>
                                    </tr>
                                    <?php while ($row = mysqli_fetch_array($query)) { ?>

                                        <?php

                                        if ($row['SiteId'] == 'Batch Invoice') {

                                            $site = 'Batch Invoice';
                                            $jobno = ' ';
                                        } else {

                                            $query_site = mysqli_query($con, "SELECT * FROM tbl_sites WHERE Id = '" . $row['SiteId'] . "'") or die(mysqli_error($con));
                                            $row_site = mysqli_fetch_array($query_site);

                                            $site = $row_site['Name'];
                                            $jobno = $row['JobNo'];
                                        }

                                        ?>

                                        <tr class="<?php echo ($ac_sw1++ % 2 == 0) ? " even" : "odd"; ?>" onMouseOver="this.oldClassName = this.className; this.className='over';" onMouseOut="this.className = this.oldClassName;">
                                            <td width="50"><?php echo $row['InvoiceNo']; ?></td>
                                            <td width="50"><?php echo $jobno; ?></td>
                                            <td><?php echo $site; ?></td>
                                            <td width="100"><?php echo $row['InvoiceDate']; ?></td>
                                            <td width="75" align="right">R<?php echo $row['Amount']; ?></td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td colspan="3" rowspan="3">&nbsp;</td>
                                        <td align="right" class="td-header">Total</td>
                                        <td align="right" class="even"><b><?php echo 'R' . number_format($row2['Amount'] + $row2['Discount'], 2); ?></b></td>
                                    </tr>
                                    <tr>
                                        <td align="right" class="td-header">Discount</td>
                                        <td align="right" class="odd"><b><?php echo 'R' . number_format($row2['Discount'], 2); ?></b></td>
                                    </tr>
                                    <tr>
                                        <td align="right" class="td-header">Total Paid</td>
                                        <td align="right" class="even"><b><?php echo 'R' . number_format($row2['Amount'], 2); ?></b></td>
                                    </tr>
                                </table>
                                <div>
                                </div>
                            </div>
                            <div class="highslide-footer">
                                <div>
                                    <span class="highslide-resize" title="Resize">
                                        <span></span>
                                    </span>
                                </div>
                            </div>
                        </div>

                    </td>
                    <td align="center"><a href="../pdf/pdf/Seavest Remittance <?php echo $row_Recordset3['Id']; ?>.pdf" target="_blank" class="download"></a></td>
                    <td align="center">
                        <?php

                        // Check if Pragma and send XL format

                        $companyid = $row_Recordset3['CompanyId'];

                        if ($companyid == 2) {

                            $value = $row_Recordset3['JobId'];
                        } else {

                            $value = $row_Recordset3['PDF'];
                        }

                        ?>
                        <input name="remittanceid" type="radio" id="file[]" value="<?php echo $row_Recordset3['Id']; ?>" <?php if ($_GET['Id'] == $row_Recordset3['Id']) {
                                                                                                                                echo 'checked="checked"';
                                                                                                                            } ?>>


                    </td>
                </tr>
            <?php } while ($row_Recordset3 = mysqli_fetch_assoc($Recordset3)); ?>
        </table>
    </div>
    <!-- End Main Form -->

    <!-- Footer -->
    <div id="footer"><a href="../support/index.php"><img src="../images/KWD-SS.png" width="200" height="24" /></a></div>
    <!-- End Footer -->

</body>

</html>
<?php
mysqli_close($con);
mysqli_free_result($query);
mysqli_free_result($query_areas);
mysqli_free_result($query_list);
mysqli_free_result($query_form);
mysqli_free_result($query_user_menu);
?>