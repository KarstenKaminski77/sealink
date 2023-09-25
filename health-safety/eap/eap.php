<?php 

//    error_reporting(E_ALL);
//    ini_set('display_errors', 1);

    session_start();

    require_once('../../Connections/inv.php');
    require_once('../../includes/wdg/WDG.php');
    require_once('../../functions/functions.php');

    select_db();

    $id = $_GET['Id'];

    // Save Changes

    if(isset($_POST['save']) || isset($_POST['close']) || isset($_POST['preview'])){

            $ambulance = $_POST['ambulance'];
            $fire = $_POST['fire'];
            $police = $_POST['police'];
            $hospital = $_POST['hospital'];
            $hospital_tel = $_POST['hospital-tel'];
            $rmc = $_POST['rmc'];
            $ches = $_POST['ches'];
            $client = $_POST['client'];
            $water = $_POST['water'];
            $gas = $_POST['gas'];
            $electric = $_POST['electric'];
            $telephone = $_POST['telephone'];
            $sewer = $_POST['sewer'];
            $directions = $_POST['directions'];
            $ms = $_POST['ms'];


            mysqli_query($con, "UPDATE tbl_eap SET MaintenanceSpecialist = '$ms', Ambulance = '$ambulance', Fire = '$fire', Police = '$police', HospitalName = '$hospital', HospitalPhone = '$hospital_tel', ResponsibleRMC = '$rmc', SafetySpecialist = '$ches', ClientContact = '$client', Water = '$water', Gas = '$gas', Electric = '$electric', TelephoneCable = '$telephone', Sewer = '$sewer', Directions = '$directions' WHERE SiteId = '$id'")or die(mysqli_error($con));

    }

    if (isset($_GET["Id"])) {
      $KTColParam1_Recordset1 = $_GET["Id"];
    }

    $query_Recordset1 = "SELECT tbl_sites.Name AS Name_1, tbl_companies.Name, tbl_sites.Address, tbl_sites.Suburb, tbl_sites.Id FROM (tbl_companies LEFT JOIN tbl_sites ON tbl_sites.Company=tbl_companies.Id) WHERE tbl_sites.Id = $KTColParam1_Recordset1 ";
    $Recordset1 = mysqli_query($con, $query_Recordset1) or die(mysqli_error($con));
    $row_Recordset1 = mysqli_fetch_assoc($Recordset1);
    $totalRows_Recordset1 = mysqli_num_rows($Recordset1);


    $query_Recordset2 = "SELECT * FROM tbl_sites ORDER BY Name ASC";
    $Recordset2 = mysqli_query($con, $query_Recordset2) or die(mysqli_error($con));
    $row_Recordset2 = mysqli_fetch_assoc($Recordset2);
    $totalRows_Recordset2 = mysqli_num_rows($Recordset2);


    $query_Recordset3 = "SELECT * FROM tbl_far_high_risk_classification ORDER BY Risk ASC";
    $Recordset3 = mysqli_query($con, $query_Recordset3) or die(mysqli_error($con));
    $row_Recordset3 = mysqli_fetch_assoc($Recordset3);
    $totalRows_Recordset3 = mysqli_num_rows($Recordset3);

    $colname_Recordset4 = "-1";
    if (isset($_GET['Id'])) {
      $colname_Recordset4 = $_GET['Id'];
    }

    $query_Recordset4 = "SELECT * FROM tbl_eap WHERE SiteId = $colname_Recordset4";
    $Recordset4 = mysqli_query($con, $query_Recordset4) or die(mysqli_error($con));
    $row_Recordset4 = mysqli_fetch_assoc($Recordset4);
    $totalRows_Recordset4 = mysqli_num_rows($Recordset4);


    $query_Recordset5 = "SELECT * FROM tbl_hes_documents";
    $Recordset5 = mysqli_query($con, $query_Recordset5) or die(mysqli_error($con));
    $row_Recordset5 = mysqli_fetch_assoc($Recordset5);
    $totalRows_Recordset5 = mysqli_num_rows($Recordset5);

    $jobno = $_SESSION['jobno'];

    if(isset($_POST['save'])){

            header('Location: ../../fpdf16/pdf-eap.php?Id='. $_GET['Id'] .'&HESId='. $_GET['HESId']);
    }

    if(isset($_POST['jsa'])){

            header('Location: ../jsa/jsa.php?Id='. $_GET['Id']);
    }

    if(isset($_POST['jms'])){

            header('Location: ../jms/jms.php?Id='. $_GET['Id']);
    }

    if(isset($_POST['close'])){

            // Save and Close

            header('Location: ../../fpdf16/pdf-eap.php?Id='. $_GET['Id'] .'&HESId='. $_GET['HESId']. '&Close');

    }

    if(isset($_POST['preview'])){
	
?><script type="text/javascript" language="Javascript">window.open('../../fpdf16/pdf-eap.php?Id=<?php echo $_GET['Id']; ?>&Preview');</script>
    
<?php } ?>

<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://ns.adobe.com/addt">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>SEAVEST AFRICA TRADING CC</title>
    <link href="../../styles/layout.css" rel="stylesheet" type="text/css" />
    <link href="../../styles/fonts.css" rel="stylesheet" type="text/css">
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
    -->
    </style>
    <script type="text/javascript" src="../../includes/common/js/sigslot_core.js"></script>
    <script src="../../includes/common/js/base.js" type="text/javascript"></script>
    <script src="../../includes/common/js/utility.js" type="text/javascript"></script>

</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top"><?php include('../../menu.php'); ?></td>
    <td valign="top"><table width="750" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="761" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="200" colspan="4" align="center"><img src="../../images/banner.jpg" width="823" height="151"></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>
          <form name="form2" method="post" action="eap.php?Id=<?php echo $_GET['Id'] .'&HESId='. $_GET['HESId']; ?>" style="margin-left:30px">
        <div>
          <table width="100%" border="0" cellpadding="3" cellspacing="1" class="td-header">
            <tr>
              <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><table border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><input name="jsa" type="submit" class="btn-green-generic" id="jsa" value="JSA Document"></td>
                      <td><input name="jms" type="submit" class="btn-green-generic" id="jms" value="JMS Document"></td>
                    </tr>
                  </table></td>
                  <td align="right"><table border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><input name="preview" type="submit" class="btn-blue-generic" id="preview" value="Preview"></td>
                      <td><input name="save" type="submit" class="btn-blue-generic" id="save" value="Save"></td>
                      <td><input name="close" type="submit" class="btn-blue-generic" id="close" value="Save &amp; Close"></td>
                    </tr>
                  </table></td>
                </tr>
              </table></td>
              </tr>
          </table>
        </div>

        <div style="border:solid 1px #A6CAF0; width:753px; background-color:#EEE; padding: 20px; margin-top:5px; color: #333; margin-bottom:5px">
          <table width="100%" border="0" cellpadding="3" cellspacing="0">
            <tr>
              <td><table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
                <tr>
                  <td width="50%" valign="top"><table border="0" cellpadding="2" cellspacing="3" class="combo-grey">
                    <tr>
                      <td valign="top"><strong><?php echo $row_Recordset1['Name']; ?></strong></td>
                      <td width="50">&nbsp;</td>
                      <td nowrap><strong>&nbsp;<?php echo $row_Recordset1['Address']; ?></strong></td>
                    </tr>
                    <tr>
                      <td><strong><?php echo $row_Recordset1['Name_1']; ?></strong></td>
                      <td width="50">&nbsp;</td>
                      <td nowrap><strong> &nbsp;<?php echo $row_Recordset1['Suburb']; ?></strong></td>
                    </tr>
                  </table></td>
                  <td width="50%" align="right" valign="top" class="combo">&nbsp;</td>
                </tr>
              </table></td>
            </tr>
          </table>
        </div>          
            <div id="list-brdr">
              <table border="0" cellpadding="3" cellspacing="1">
                <tr class="even">
                  <td width="450" class="td-header">Emergency Telephone Numbers</td>
                </tr>
                <tr class="even">
                  <td class="odd"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="comb-sms">
                    <tr>
                      <td width="140">Ambulance</td>
                      <td width="280"><input name="ambulance" type="text" class="tarea-epa" id="ambulance" value="<?php echo $row_Recordset4['Ambulance']; ?>"></td>
                      <td width="117">Fire</td>
                      <td><input name="fire" type="text" class="tarea-epa" id="fire" value="<?php echo $row_Recordset4['Fire']; ?>"></td>
                    </tr>
                  </table></td>
                </tr>
                <tr class="even">
                  <td><table width="100%" border="0" cellpadding="0" cellspacing="0" class="comb-sms">
                    <tr>
                      <td width="140">Police</td>
                      <td width="280"><input name="police" type="text" class="tarea-epa" id="police" value="<?php echo $row_Recordset4['Police']; ?>"></td>
                      <td width="117">Hospital Name</td>
                      <td><input name="hospital" type="text" class="tarea-epa" id="hospital" value="<?php echo $row_Recordset4['HospitalName']; ?>"></td>
                    </tr>
                  </table></td>
                </tr>
                <tr class="even">
                  <td class="odd"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="comb-sms">
                    <tr>
                      <td width="140">Hospital Phone</td>
                      <td width="280"><input name="hospital-tel" type="text" class="tarea-epa" id="hospital-tel" value="<?php echo $row_Recordset4['HospitalPhone']; ?>"></td>
                      <td width="117">&nbsp;Responsible RMC</td>
                      <td><input name="rmc" type="text" class="tarea-epa" id="rmc" value="<?php echo $row_Recordset4['ResponsibleRMC']; ?>"></td>
                    </tr>
                  </table></td>
                </tr>
                <tr class="even">
                  <td><table width="100%" border="0" cellpadding="0" cellspacing="0" class="comb-sms">
                    <tr>
                      <td width="140">CHES Specialist</td>
                      <td width="280"><input name="ches" type="text" class="tarea-epa" id="ches" value="<?php echo $row_Recordset4['SafetySpecialist']; ?>"></td>
                      <td width="117">Client Contact</td>
                      <td><input name="client" type="text" class="tarea-epa" id="client" value="<?php echo $row_Recordset4['ClientContact']; ?>"></td>
                    </tr>
                  </table></td>
                </tr>
                <tr class="odd">
                  <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="140" class="comb-sms">Maintenance Specialist</td>
                      <td><input name="ms" type="text" class="tarea-epa" id="ms" value="<?php echo $row_Recordset4['MaintenanceSpecialist']; ?>"></td>
                      </tr>
                  </table></td>
                </tr>
                <tr class="even">
                  <td class="td-header">Utility Emergency Telephone Numbers</td>
                </tr>
                <tr class="even">
                  <td class="odd"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="comb-sms">
                    <tr>
                      <td width="140">Water</td>
                      <td width="280"><input name="water" type="text" class="tarea-epa" id="water" value="<?php echo $row_Recordset4['Water']; ?>"></td>
                      <td width="117">Gas</td>
                      <td><input name="gas" type="text" class="tarea-epa" id="gas" value="<?php echo $row_Recordset4['Gas']; ?>"></td>
                    </tr>
                  </table></td>
                </tr>
                <tr class="even">
                  <td class="even"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="comb-sms">
                    <tr>
                      <td width="140">Electric</td>
                      <td width="280"><input name="electric" type="text" class="tarea-epa" id="electric" value="<?php echo $row_Recordset4['Electric']; ?>"></td>
                      <td width="117">Telephone | Cable</td>
                      <td><input name="telephone" type="text" class="tarea-epa" id="telephone" value="<?php echo $row_Recordset4['TelephoneCable']; ?>"></td>
                    </tr>
                  </table></td>
                </tr>
                <tr class="even">
                  <td class="odd"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="comb-sms">
                    <tr>
                      <td width="140">Sewer</td>
                      <td width="280"><input name="sewer" type="text" class="tarea-epa" id="sewer" value="<?php echo $row_Recordset4['Sewer']; ?>"></td>
                      <td width="117">&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                  </table></td>
                </tr>
                <tr class="even">
                  <td class="td-header">Directions To Hospital</td>
                </tr>
                <tr class="even">
                  <td class="even"><textarea name="directions" cols="45" rows="10" class="tarea-jla-matrix" id="directions" style="width:777px"><?php echo $row_Recordset4['Directions']; ?></textarea></td>
                </tr>
                <tr class="even">
                  <td class="odd">&nbsp;</td>
                  </tr>
                <tr class="even">
                  <td align="right" class="td-header"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><table border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td><input name="jsa" type="submit" class="btn-green-generic" id="jsa" value="JSA Document"></td>
                          <td><input name="jms" type="submit" class="btn-green-generic" id="jms" value="JMS Document"></td>
                        </tr>
                      </table></td>
                      <td align="right"><table border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td><input name="preview" type="submit" class="btn-blue-generic" id="preview" value="Preview"></td>
                          <td><input name="save" type="submit" class="btn-blue-generic" id="save" value="Save"></td>
                          <td><input name="close" type="submit" class="btn-blue-generic" id="close" value="Save &amp; Close"></td>
                        </tr>
                      </table></td>
                    </tr>
                  </table></td>
                </tr>
              </table>
            </div>
          </form>
          </p>
</td>
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

mysqli_free_result($Recordset4);
?>
