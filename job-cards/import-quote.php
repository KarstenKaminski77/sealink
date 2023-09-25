<?php
require_once('../Connections/seavest.php'); 
require_once('../functions/functions.php');

// Import Quote
if(isset($_POST['quote']) && !empty($_POST['quote'])) {

	$quoteno = $_POST['quote'];
	
	quote_import($_GET['JobId'], $_POST['quote'], $_GET['JobNo']);

}
// End Import Quote
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>

  <link href="../styles/fonts.css" rel="stylesheet" type="text/css" />
  <link href="../styles/layout.css" rel="stylesheet" type="text/css" />
  
  <style>
  
	#banner-success {
		background-color: #DDEAF7;
		background-image: url(../images/icons/success.png);
		padding-top: 30px;
		padding-right: 20px;
		padding-bottom: 30px;
		padding-left: 60px;
		border: 2px solid #1A446F;
		-moz-border-radius: 5px;
		-webkit-border-radius: 5 px;
		border-radius: 5px; /* future proofing */
		-khtml-border-radius: 5px;
		background-repeat: no-repeat;
		background-position: 15px center;
		font-family: Arial, Helvetica, sans-serif;
		font-size: 12px;
		color: #333;
		line-height: 20px;
		margin-top: 0px;
		margin-right: 0px;
		margin-bottom: 20px;
		margin-left: 0px;
	}
  
  </style>

</head>

<body>
<table width="500" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="500" valign="middle">
      <form id="form1" name="form1" method="post" action="">
        <?php 
		
		if(isset($_POST['quote']) && !empty($_POST['quote'])){
			
			echo '<div id="banner-success">Quotation Successfully Imported</div>';
			
		} else { 
		
		?>
		
		?>
        <div align="center" class="big2">Import Quote</div>
        <br />
        <table width="375" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td>
              <div id="list-brdr">
                <table width="375" border="0" align="center" cellpadding="0" cellspacing="1">
                  <tr>
                    <td width="75" class="td-left">Job&nbsp;No</td>
                    <td width="300" class="td-right"><?php echo $_GET['JobNo']; ?></td>
                  </tr>
                  <tr>
                    <td width="75" class="td-left">Quote No</td>
                    <td align="right" class="td-right">
                    <input name="quote" class="tarea-new-100" id="quote" value="<?php echo $inv_date; ?>" />
                    
					<script type="text/javascript">
                    $('#inv_date').datepicker({
                    dateFormat: "yy-mm-dd"
                    });
                    </script>
                    
                    </td>
                  </tr>
                </table>
              </div>
            </td>
          </tr>
        </table>
        <table width="375" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td align="right"><input name="Submit" type="submit" class="btn-new" value="Submit" /></td>
          </tr>
        </table>
        
        <?php } ?>
        
      </form>
    </td>
  </tr>
</table>
</body>
</html>