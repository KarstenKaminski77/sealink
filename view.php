<?php

    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    session_start();

    require_once('Connections/seavest.php'); 
    require_once('functions/functions.php');


    $query_Recordset1 = "SELECT * FROM tbl_companies";
    $Recordset1 = mysqli_query($con, $query_Recordset1) or die(mysqli_error($con));
    $row_Recordset1 = mysqli_fetch_assoc($Recordset1);
    $totalRows_Recordset1 = mysqli_num_rows($Recordset1);


    $query_Recordset2 = "SELECT * FROM tbl_sites";
    $Recordset2 = mysqli_query($con, $query_Recordset2) or die(mysqli_error($con));
    $row_Recordset2 = mysqli_fetch_assoc($Recordset2);
    $totalRows_Recordset2 = mysqli_num_rows($Recordset2);

    $colname_Recordset3 = "-1";
    if (isset($_SESSION['quote_no'])) {
      $colname_Recordset3 = (get_magic_quotes_gpc()) ? $_SESSION['quote_no'] : addslashes($_SESSION['quote_no']);
    }

    $query_Recordset3 = sprintf("SELECT * FROM tbl_photos WHERE QuoteNo = '%s' AND Attach = '1'", $colname_Recordset3);
    $Recordset3 = mysqli_query($con, $query_Recordset3) or die(mysqli_error($con));
    $row_Recordset3 = mysqli_fetch_assoc($Recordset3);
    $totalRows_Recordset3 = mysqli_num_rows($Recordset3);

    $colname_Recordset4 = "-1";
    if (isset($_SESSION['quote_no'])) {
      $colname_Recordset4 = (get_magic_quotes_gpc()) ? $_SESSION['quote_no'] : addslashes($_SESSION['quote_no']);
    }

    $query_Recordset4 = sprintf("SELECT * FROM tbl_reports WHERE QuoteNo = '%s'", $colname_Recordset4);
    $Recordset4 = mysqli_query($con, $query_Recordset4) or die(mysqli_error($con));
    $row_Recordset4 = mysqli_fetch_assoc($Recordset4);
    $totalRows_Recordset4 = mysqli_num_rows($Recordset4);

?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://www.interaktonline.com/MXWidgets">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SEAVEST AFRICA TRADING CC</title>
<link href="styles/layout.css" rel="stylesheet" type="text/css" />
<link href="styles/fonts.css" rel="stylesheet" type="text/css">
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
.img_border {
	margin: 0px;
	padding: 2px;
	border: 1px solid #0067AA;
}
-->
</style>
<link href="styles/fonts.css" rel="stylesheet" type="text/css">
<link href="styles/layout.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td valign="top"><table width="750" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="100%"cellpadding="0" cellspacing="1">
          <tr>
            <td width="200" colspan="4" align="center">&nbsp;</td>
            </tr>
            
        </table></td>
      </tr>
      <tr>
        <td align="center"><div id="add_row">
          <table width="100%" border="0" cellspacing="1" cellpadding="0">
              <tr>
                <td width="9%" class="tb_border"><form name="form2" method="post" action="view.php?photos">
                  <input name="Submit2" type="submit" class="tarea2" value="View Photos">
                                      </form>                </td>
                <td width="91%" class="tb_border"><form name="form3" method="post" action="view.php">
                  <input name="Submit3" type="submit" class="tarea2" value="View Report">
                          </form>                </td>
              </tr>
              </table>
        </div>
          
          <p>
            <?php if(isset($_GET['photos'])){ ?>
                <div style="width:780px">
                    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                        <tr>
                            <?php
                              do { // horizontal looper version 3
                            ?>
                                <td align="center">
                                    <div style="margin-bottom:40px">
                                        <img src="photos/<?php echo $row_Recordset3['Image']; ?>" width="600" class="img_border">
                                    </div>
                                </td>
                            <?php
                              $row_Recordset3 = mysqli_fetch_assoc($Recordset3);
                              if (!isset($nested_Recordset3)) {
                                $nested_Recordset3= 1;
                              }
                              if (isset($row_Recordset3) && is_array($row_Recordset3) && $nested_Recordset3++ % 1==0) {
                                echo "</tr><tr>";
                              }
                            } while ($row_Recordset3); //end horizontal looper version 3
                          ?>
                        </tr>
                    </table>
                </div>
            <?php } else { ?>
          </p>

            <div style="width:700px; margin-right:30px; text-align:left"><span class="combo"><?php echo nl2br($row_Recordset4['Report']); ?></span></div>
        <?php } ?>          
        </td>
      </tr>
    </table>
	
	</td>
  </tr>
</table>
</body>
</html>
