<?php 

//    error_reporting(E_ALL);
//    ini_set('display_errors', 1);

    require_once('../../Connections/inv.php');

    $query_Recordset1 = "SELECT * FROM tbl_companies ORDER BY Name ASC";
    $Recordset1 = mysqli_query($con, $query_Recordset1) or die(mysqli_error($con));
    $row_Recordset1 = mysqli_fetch_assoc($Recordset1);
    $totalRows_Recordset1 = mysqli_num_rows($Recordset1);

    $sites = [];
    
    $query_Recordset2 = "SELECT * FROM tbl_sites ORDER BY Name ASC";
    $Recordset2 = mysqli_query($con, $query_Recordset2) or die(mysqli_error($con));
    while($row_Recordset2 = mysqli_fetch_assoc($Recordset2)){
        
        if(!empty($row_Recordset2['Name'])){
        
            $sites[] = $row_Recordset2;
        }
    }
    $totalRows_Recordset2 = mysqli_num_rows($Recordset2);

    $query_Recordset3 = "SELECT * FROM tbl_far_high_risk_classification ORDER BY Risk ASC";
    $Recordset3 = mysqli_query($con, $query_Recordset3) or die(mysqli_error($con));
    $row_Recordset3 = mysqli_fetch_assoc($Recordset3);
    $totalRows_Recordset3 = mysqli_num_rows($Recordset3);

    $query_Recordset4 = "SELECT * FROM tbl_hes_documents";
    $Recordset4 = mysqli_query($con, $query_Recordset4) or die(mysqli_error($con));
    $row_Recordset4 = mysqli_fetch_assoc($Recordset4);
    $totalRows_Recordset4 = mysqli_num_rows($Recordset4);
?>
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

    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

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
        <td><p>&nbsp;
          </p>          
            <form name="form2" method="post" action="new-process.php" style="margin-left:30px">
            <div id="list-brdr">
              <table border="0" cellpadding="3" cellspacing="1">
                <tr class="td-header">
                  <td width="150" align="left"><strong>&nbsp; Company</strong></td>
                  <td width="150" align="left"><strong>&nbsp;Site</strong></td>
                  <td width="150" align="left"><strong>&nbsp;Reference</strong></td>
                  <td align="left">&nbsp;&nbsp;Date&nbsp;</td>
                  </tr>
                <tr class="even">
                  <td width="150" align="left"><select name="company" class="tarea-hes" id="company">
                    <option value="">Select one...</option>
                    <?php
do {  
?>
                    <option value="<?php echo $row_Recordset1['Id']?>"><?php echo $row_Recordset1['Name']?></option>
                    <?php
} while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1));
  $rows = mysqli_num_rows($Recordset1);
  if($rows > 0) {
      mysqli_data_seek($Recordset1, 0);
	  $row_Recordset1 = mysqli_fetch_assoc($Recordset1);
  }
?>
                  </select></td>
                  <td width="150" align="left" id="site">
                      <select name="site" class="tarea-hes">
                        <option value="">Select one...</option>
                        <?php foreach($sites as $site){ ?>
                            <option value="<?php echo $site['Id']; ?>"><?php echo $site['Name']; ?></option>
                        <?php } ?>
                      </select>
                  </td>
                  <td width="150" align="left" nowrap><input name="ref" type="text" class="tarea-hes" id="ref"></td>
                  <td align="left" nowrap>
                      <input type="date" name="date" class="tarea-hes" id="date" value="<?php echo date('Y-m-d'); ?>">
                  </td>
                  </tr>
                <tr class="odd">
                  <td colspan="4" class="td-header">HES Documents</td>
                </tr>
                <tr class="odd">
                  <td colspan="4">
                      <table border="0" cellpadding="2" cellspacing="3" class="comb-sms">
                        <tr>
						<?php do { ?>
                          <td><input name="documents[]" type="checkbox" id="documents[]" value="<?php echo $row_Recordset4['Id']; ?>"></td>
                          <td><div style="margin-right:15px"><?php echo $row_Recordset4['Document']; ?></div></td>
                          <?php } while ($row_Recordset4 = mysqli_fetch_assoc($Recordset4)); ?>
                          </tr>
                      </table>
                      </td>
                </tr>
                <tr class="odd">
                  <td colspan="4" class="td-header">&nbsp;JSA Type</td>
                </tr>
                <tr class="even">
                  <td colspan="4"><table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <?php
                        do { // horizontal looper version 3
                        ?>
                        <td><table border="0" cellpadding="2" cellspacing="3" class="comb-sms">
                          <tr>
                            <td><input name="jsa-risk[]" type="checkbox" id="jsa-risk[]" value="<?php echo $row_Recordset3['Id']; ?>"></td>
                            <td><?php echo $row_Recordset3['Risk']; ?></td>
                            <td>&nbsp;</td>
                            </tr>
                        </table></td>
                        <?php
                        $row_Recordset3 = mysqli_fetch_assoc($Recordset3);
                            if (!isset($nested_Recordset3)) {
                              $nested_Recordset3= 1;
                            }
                            if (isset($row_Recordset3) && is_array($row_Recordset3) && $nested_Recordset3++ % 6==0) {
                              echo "</tr><tr>";
                            }
                          } while ($row_Recordset3); //end horizontal looper version 3
                        ?>
                    </tr>
                  </table></td>
                </tr>
                <tr class="even">
                  <td colspan="4" class="td-header">The risk matrix is as follows</td>
                </tr>
                <tr class="even">
                  <td colspan="4" class="odd"><table border="0" cellpadding="0" cellspacing="0" class="comb-sms">
                    <tr>
                      <td width="150">Scope of work</td>
                      <td><input name="scope" type="text" class="tarea-jla-matrix" id="scope"></td>
                    </tr>
                  </table></td>
                </tr>
                <tr class="even">
                  <td colspan="4"><table border="0" cellpadding="0" cellspacing="0" class="comb-sms">
                    <tr>
                      <td width="150">Equipment to be used</td>
                      <td><input name="equipment" type="text" class="tarea-jla-matrix" id="equipment"></td>
                    </tr>
                  </table></td>
                </tr>
                <tr class="even">
                  <td colspan="4" class="odd"><table border="0" cellpadding="0" cellspacing="0" class="comb-sms">
                    <tr>
                      <td width="150">Risk Ranking of job</td>
                      <td><select name="risk" class="tarea-jla-matrix" id="risk">
                        <option value="" <?php if (!(strcmp("", $row_Recordset4['RiskRanking']))) {echo "selected=\"selected\"";} ?>>Select Risk...</option>
                        <option value="H/H" <?php if (!(strcmp("H/H", $row_Recordset4['RiskRanking']))) {echo "selected=\"selected\"";} ?>>H/H</option>
                        <option value="M/H" <?php if (!(strcmp("M/H", $row_Recordset4['RiskRanking']))) {echo "selected=\"selected\"";} ?>>M/H</option>
                        <option value="L/H" <?php if (!(strcmp("L/H", $row_Recordset4['RiskRanking']))) {echo "selected=\"selected\"";} ?>>L/H</option>
                      </select></td>
                    </tr>
                  </table></td>
                </tr>
                <tr class="even">
                  <td colspan="4"><table border="0" cellpadding="0" cellspacing="0" class="comb-sms">
                    <tr>
                      <td width="150">Contractor doing job</td>
                      <td><input name="contractor" type="text" class="tarea-jla-matrix" id="contractor" value="Seavest Africa"></td>
                    </tr>
                  </table></td>
                </tr>
                <tr class="even">
                  <td colspan="4" class="odd"><table border="0" cellpadding="0" cellspacing="0" class="comb-sms">
                    <tr>
                      <td width="150">Contractor CHESM Rating</td>
                      <td><input name="chesm" type="text" class="tarea-jla-matrix" id="chesm"></td>
                    </tr>
                  </table></td>
                </tr>
                <tr class="even">
                  <td colspan="4"><table border="0" cellpadding="0" cellspacing="0" class="comb-sms">
                    <tr>
                      <td width="150">Permit Issuer</td>
                      <td><input name="issuer" type="text" class="tarea-jla-matrix" id="issuer" value="Self Permitting"></td>
                    </tr>
                  </table></td>
                </tr>
                <tr class="even">
                  <td colspan="4" class="odd"><table border="0" cellpadding="0" cellspacing="0" class="comb-sms">
                    <tr>
                      <td width="150">Safety Officer</td>
                      <td><input name="so" type="text" class="tarea-jla-matrix" id="so" value="Self Permitting"></td>
                    </tr>
                  </table></td>
                </tr>
                <tr class="even">
                  <td colspan="4" align="right" class="td-header"><input name="button2" type="submit" class="btn-blue-generic" id="button2" value="Create New"></td>
                </tr>
              </table>
            </div>
          </form>
          <p>
          </p>
          </p>
</td>
      </tr>
    </table></td>
  </tr>
</table>
    
    <script>
        
        $(document).ready(function(){
            
            $("#company").on("change", function(e) {

                var company_id = $('#company').val();        
                $.ajax({
                    url: "get-sites.php",
                    type: "post",
                    data: {company_id:company_id},
                    success: function (response) {
                        $('#site').empty();
                        $('#site').append(response);
                    },
                    error: function() {
                        alert("Error!");
                    }
                });
            });
        });
        
    </script>
</body>
</html>
<?php
mysqli_free_result($Recordset1);

mysqli_free_result($Recordset2);

mysqli_free_result($Recordset3);

mysqli_free_result($Recordset4);
?>
