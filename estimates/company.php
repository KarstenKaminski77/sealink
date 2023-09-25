<?php 
session_start();

require_once('../Connections/seavest.php'); 
require_once('../functions/functions.php');

require_once("../dropdown/dbcontroller.php");
$db_handle = new DBController();

$query ="SELECT * FROM tbl_companies ORDER BY Name ASC";
$results = $db_handle->runQuery($query);

if(isset($_POST['update']) && !empty($_POST['company']) && !empty($_POST['site'])){
	
	$query = mysqli_query($con, "SELECT AreaId FROM tbl_sites WHERE Id = '". $_POST['site'] ."'")or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	
	$form_data = array();
	
	if(!empty($_POST['company'])){
		
		$form_data['CompanyId'] = $_POST['company'];
	}
	
	if(!empty($_POST['site'])){
		
		$form_data['SiteId'] = $_POST['site'];
		$form_data['AreaId'] = $row['AreaId'];
	}
	
	dbUpdate('tbl_qs', $form_data, $where_clause="QuoteNo = '". $_GET['Id'] ."'",$con);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <title>Untitled Document</title>
  
  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

  <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
  
  <script>
  
    function getSites(val) {
        $.ajax({
        type: "POST",
        url: "../dropdown/get-sites.php",
        data:'company_id='+val,
        success: function(data){
            $("#site-list").html(data);
        }
        });
    }

  </script>
  
  <style type="text/css">
  
	.td-left {
		font-family: Arial, Helvetica, sans-serif;
		font-size: 12px;
		line-height: 20px;
		font-weight: bold;
		text-transform: capitalize;
		color: #818284;
		background-color: #f7f7f7;
		border: 1px solid #DFDFDF;
		padding-left: 5px;
		font-style: italic;
	}
	
	.td-right {
		font-family: Arial, Helvetica, sans-serif;
		font-size: 12px;
		line-height: 20px;
		font-weight: normal;
		/* [disabled]text-transform: capitalize; */
		color: #818284;
		border: 1px solid #DFDFDF;
		padding: 5px;
		background-color: #FFF;
	}
	
	#list-border{
		border: 1px solid #DDDDDD;
		padding: 1px;
	}
	
	.tarea-100 {
		font-family: Arial, Helvetica, sans-serif;
		font-size: 12px;
		color: #808185;
		width: 100%;
		border-top-style: none;
		border-right-style: none;
		border-bottom-style: none;
		border-left-style: none;
		outline:none;
		resize: none;
		background:none;
	}
	
	.btn-new {
		background: #18519b; /* Old browsers */
		background: -moz-linear-gradient(top,  #18519b 0%, #3c87c4 100%); /* FF3.6+ */
		background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#18519b), color-stop(100%,#3c87c4)); /* Chrome,Safari4+ */
		background: -webkit-linear-gradient(top,  #18519b 0%,#3c87c4 100%); /* Chrome10+,Safari5.1+ */
		background: -o-linear-gradient(top,  #18519b 0%,#3c87c4 100%); /* Opera 11.10+ */
		background: -ms-linear-gradient(top,  #18519b 0%,#3c87c4 100%); /* IE10+ */
		background: linear-gradient(to bottom,  #18519b 0%,#3c87c4 100%); /* W3C */
	
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#18519b', endColorstr='#3c87c4',GradientType=0 ); /* IE6-9 */
		
		-webkit-border-radius: 5px;
		-moz-border-radius: 5px;
		border-radius: 5px;
		font-family: Arial, Helvetica, sans-serif;
		font-size: 12px;
		font-weight: bold;
		color: #FFF;
		margin-top: 10px;
		border: none;
		padding-top: 5px;
		padding-right: 10px;
		padding-bottom: 5px;
		padding-left: 10px;
		cursor: pointer;
		display: inline-block;
		text-decoration: none;
	}
  
  </style>
  
</head>

<body>
<form id="form1" name="form1" method="post" action="">

  <table width="400" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td>
      
        <div id="list-border">
          <table width="100%" border="0" align="center" cellpadding="2" cellspacing="1">
            <tr>
              <td width="140" nowrap class="td-left">Company                      </td>
              <td nowrap class="td-right">
              
                <select name="company" id="country-list" class="tarea-100" onChange="getSites(this.value);">
                  <option value="">Oil Company</option>
                    <?php foreach($results as $company){ ?>
                    <option value="<?php echo $company["Id"]; ?>" <?php if($company["Id"] == $_POST['company']){ echo 'selected="selected"'; } ?>><?php echo $company["Name"]; ?></option>
                    <?php } ?>
                </select>
        
                
              </td>
            </tr>
            <tr>
              <td nowrap class="td-left">Site</td>
              <td nowrap class="td-right"><select name="site" id="site-list" class="tarea-100">
                <option value="">Site...</option>
              </select></td>
            </tr>
          </table>
        </div>
        
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td align="right"><input name="update" type="submit" class="btn-new" id="update" value="Update" /></td>
          </tr>
      </table></td>
    </tr>
  </table>

</form>
</body>
</html>