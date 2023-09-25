<?php 

//    error_reporting(E_ALL);
//    ini_set('display_errors', 1);

    session_start();
    
    require_once('../../Connections/inv.php');
    require_once('../../Connections/seavest.php');
    require_once('../../functions/functions.php');
    require_once('../../Connections/inv.php');

    select_db();

    if(isset($_POST['submit'])){

        $date = date('Y-m-d');
        $techician = $_POST['technician'];
        $item = $_POST['item'];
        $qty = $_POST['qty'];

        mysqli_query($con, "INSERT INTO tbl_ppe (Date, Technician, Item, Qty) VALUES ('$date','$technician','$item','$qty')")or die(mysqli_error($con));
    }

    $query_Recordset1 = "SELECT * FROM tbl_technicians ORDER BY Name ASC";
    $Recordset1 = mysqli_query($con, $query_Recordset1) or die(mysqli_error($con));
    $row_Recordset1 = mysqli_fetch_assoc($Recordset1);
    $totalRows_Recordset1 = mysqli_num_rows($Recordset1);

    $string = "WHERE"; 

    if(isset($_POST['search'])){

            if(!empty($_POST['tech'])){

                    $tech = $_POST['tech'];
                    $string .= ' Technician = "'. $tech .'" AND';

            }

            if(!empty($_POST['item2'])){

                    $item2 = $_POST['item2'];
                    $string .= ' Item LIKE "%'. $item2 .'%" AND';

            }

    if(!empty($_POST['date1'])){

            $string .= ' `Date` >= "'. $_POST['date1'] .'" AND';
    }

    if(!empty($_POST['date2'])){

            $string .= ' `Date` <= "'. $_POST['date2'] .'" AND';
    }

    }
            $string .= ' 1=1';

    
    $query_Recordset2 = "SELECT * FROM tbl_ppe $string ORDER BY `Date` DESC";
    $Recordset2 = mysqli_query($con, $query_Recordset2) or die(mysqli_error($con));
    $row_Recordset2 = mysqli_fetch_assoc($Recordset2);
    $totalRows_Recordset2 = mysqli_num_rows($Recordset2);

    
    $query_Recordset3 = "SELECT * FROM tbl_technicians ORDER BY Name ASC";
    $Recordset3 = mysqli_query($con, $query_Recordset3) or die(mysqli_error($con));
    $row_Recordset3 = mysqli_fetch_assoc($Recordset3);
    $totalRows_Recordset3 = mysqli_num_rows($Recordset3);
?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://ns.adobe.com/addt">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SEAVEST AFRICA TRADING CC</title>
<link href="../styles/layout.css" rel="stylesheet" type="text/css" />
<link href="../styles/fonts.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
body,td,th {
	font-family: Arial;
}
a {
	font-family: Arial;
	font-size: 11px;
	color: #FFFFFF;
	font-weight: bold;
}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #FFFFFF;
}
a:hover {
	text-decoration: none;
	color: #CCCCCC;
}
a:active {
	text-decoration: none;
	color: #FFFFFF;
}
.test {	padding: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	margin-left: 10px;
}
-->
</style>
<script type="text/javascript" src="../../includes/common/js/sigslot_core.js"></script>
<script src="../../includes/common/js/base.js" type="text/javascript"></script>
<script src="../../includes/common/js/utility.js" type="text/javascript"></script>
<script type="text/javascript" src="../../includes/wdg/classes/MXWidgets.js"></script>
<script type="text/javascript" src="../../includes/wdg/classes/MXWidgets.js.php"></script>
<script type="text/javascript" src="Calendar.js"></script>
<script type="text/javascript" src="../../includes/wdg/classes/SmartDate.js"></script>
<script type="text/javascript" src="../../includes/wdg/calendar/calendar_stripped.js"></script>
<script type="text/javascript" src="../../includes/wdg/calendar/calendar-setup_stripped.js"></script>
<script src="../../includes/resources/calendar.js"></script>
<link href="../../includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top">
        <?php
include('../../menu.php'); ?>
    </td>
    <td valign="top"><table width="823" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><img src="../../images/banner.jpg" width="823" height="151"></td>
      </tr>
      <tr>
        <td><p><br>
          </p>
          <div style="padding-left:30px">
            <form name="form2" method="post" action="ppe.php">
              <table border="0" cellpadding="2" cellspacing="3" class="combo">
                <tr>
                  <td>Technician</td>
                  <td><select name="technician" class="td-mail" id="technician">
                  <option value="">Select one...</option>
                    <?php
do {  
?>
                    <option value="<?php echo $row_Recordset1['Name']?>"><?php echo $row_Recordset1['Name']?></option>
                    <?php
} while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1));
  $rows = mysqli_num_rows($Recordset1);
  if($rows > 0) {
      mysqli_data_seek($Recordset1, 0);
	  $row_Recordset1 = mysqli_fetch_assoc($Recordset1);
  }
?>
                  </select></td>
                </tr>
                <tr>
                  <td>Item</td>
                  <td><input name="item" type="text" class="td-mail" id="item" style="width:300px;"></td>
                </tr>
                <tr>
                  <td valign="top">Quantity</td>
                  <td><input name="qty" type="text" class="td-mail" id="qty" style="width:300px;"></td>
                </tr>
                <tr>
                  <td valign="top">&nbsp;</td>
                  <td align="right"><input name="submit" type="submit" class="btn-submit" id="submit" value=""></td>
                </tr>
              </table>
            </form>
            <br>
            <br>
            <table border="0" cellpadding="3" cellspacing="1" class="combo">
              <tr>
                <td colspan="4"><form name="form3" method="post" action="ppe.php">
                  <table border="0" cellpadding="2" cellspacing="3" class="combo">
                    <tr>
                      <td><strong>Technician</strong></td>
                      <td><strong>Item</strong></td>
                      <td><span class="test"><strong>From</strong></span></td>
                      <td><span class="test"><strong>To</strong></span></td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td><span class="test">
                        <select name="tech" class="combo" id="tech">
                          <option value="">Select one...</option>
                          <?php
do {  
?>
                          <option value="<?php echo $row_Recordset3['Name']?>"><?php echo $row_Recordset3['Name']?></option>
                          <?php
} while ($row_Recordset3 = mysqli_fetch_assoc($Recordset3));
  $rows = mysqli_num_rows($Recordset3);
  if($rows > 0) {
      mysqli_data_seek($Recordset3, 0);
	  $row_Recordset3 = mysqli_fetch_assoc($Recordset3);
  }
?>
                          </select>
                      </span></td>
                      <td><input type="text" name="item2" id="item2" style="width:200px"></td>
                      <td><span class="test">
                        <input name="date1" class="combo" id="date1" style="width:60px" value="" wdg:mondayfirst="true" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format; ?>" wdg:type="widget" wdg:singleclick="true" wdg:restricttomask="no">
                      </span></td>
                      <td><span class="test">
                        <input name="date2" class="combo" id="date2" style="width:60px" value="" wdg:mondayfirst="true" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format; ?>" wdg:type="widget" wdg:singleclick="true" wdg:restricttomask="no">
                      </span></td>
                      <td><span class="test">
                        <input type="submit" name="search" id="search" value="" class="btn_search">
                      </span></td>
                    </tr>
                  </table>
                </form></td>
              </tr>
              <?php if(isset($_POST['search'])){ ?>
              <tr>
                <td colspan="4">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="4"><?php echo $totalRows_Recordset2 .' Results'; ?></td>
              </tr>
              <?php } ?>
              <tr>
                <td colspan="4">&nbsp;</td>
                </tr>
              <tr class="td-header">
                <td width="100">Date</td>
                <td width="200">Technician</td>
                <td width="300">Item</td>
                <td width="100">Qty</td>
              </tr>
              <?php do { ?>
<tr class="<?php echo ($ac_sw1++%2==0)?"odd":"even"; ?>" onMouseOver="this.oldClassName = this.className; this.className='list-over';" onMouseOut="this.className = this.oldClassName;">
                  <td width="100"><?php echo $row_Recordset2['Date']; ?></td>
                  <td width="200"><?php echo $row_Recordset2['Technician']; ?></td>
                  <td width="300"><?php echo $row_Recordset2['Item']; ?></td>
                  <td width="100"><?php echo $row_Recordset2['Qty']; ?></td>
              </tr>
                  <?php } while ($row_Recordset2 = mysqli_fetch_assoc($Recordset2)); ?>
            </table>
          </div>
          <p><br>
            <br>
          </p></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
    mysqli_free_result($Recordset1);
    mysqli_free_result($Recordset2);
    mysqli_free_result($Recordset3);
?>