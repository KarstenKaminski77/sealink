<?php 
session_start();

require_once('../Connections/seavest.php'); 
require_once('../functions/functions.php');

$quoteno = $_GET['Quote'];

$query_hes = mysqli_query($con, "SELECT * FROM tbl_qs_hes WHERE QuoteNo = '$quoteno'")or die(mysqli_error($con));
$numrows_hes = mysqli_num_rows($query_hes);

$query_labour = mysqli_query($con, "SELECT * FROM tbl_qs WHERE QuoteNo = '$quoteno' AND Labour = '1' ORDER BY Id ASC") or die(mysqli_error($con));

$query_material = mysqli_query($con, "SELECT * FROM tbl_qs WHERE QuoteNo = '$quoteno' AND Material = '1' ORDER BY Id ASC") or die(mysqli_error($con));

$query_eq = mysqli_query($con, "SELECT * FROM tbl_qs_equipment WHERE QuoteNo = '$quoteno'") or die(mysqli_error($con));

$query_transport = mysqli_query($con, "SELECT * FROM tbl_qs WHERE QuoteNo = '$quoteno' AND Transport = '1' ORDER BY Id ASC") or die(mysqli_error($con));

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

  <link href="../styles/fonts.css" rel="stylesheet" type="text/css" />
  <link href="../styles/layout.css" rel="stylesheet" type="text/css" />

  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Untitled Document</title>
</head>

<body>
<div>
  <table width="100%" border="0" cellpadding="4" cellspacing="1">
    <tr>
      <td class="td-header">Description</td>
      <td width="50" align="center" class="td-header">Unit</td>
      <td width="50" align="center" class="td-header">Qty.</td>
      <td width="75" colspan="2" align="center" class="td-header">Unit Price </td>
      <td width="75" align="right" class="td-header">Total</td>
    </tr>
    <tr>
      <td colspan="6"><table width="100%" border="0" cellpadding="2" cellspacing="3" class="td-mail">
        <tr>
          <td>Health &amp; Safety Compliance</td>
          <td align="right">&nbsp;</td>
        </tr>
      </table></td>
    </tr>
    
    <?php while($row_hes = mysqli_fetch_array($query_hes)){ ?>
    <tr>
      <td><input name="desc-hes[]" type="text" class="tarea-100per" id="material" value="Risk assessment, safety documents, barricades and total health &amp; safety compliance." /></td>
      <td width="50" align="center"><input name="unit-hes[]" type="text" class="tarea-100per" id="unit_m" value="<?php echo $row_hes['Unit']; ?>" size="6" /></td>
      <td width="50" align="center"><input name="qty-hes[]" type="text" class="tarea-100per" id="qty_m" value="<?php echo $row_hes['Qty']; ?>" size="6" /></td>
      <td width="75" colspan="2" align="center"><input name="price-hes[]" type="text" class="tarea-100per" id="Price" value="<?php echo $row_hes['UnitPrice']; ?>" size="11" /></td>
      <td align="center"><input name="total-hes[]" type="text" disabled="disabled" class="tarea-100per" id="total_m" value="<?php echo $row_hes['Total']; ?>" size="7" style="text-align:right" /></td>
    </tr>
    <?php } // close loop ?>
    <tr>
      <td colspan="6">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="6"><table width="100%" border="0" cellpadding="2" cellspacing="3" class="td-mail">
        <tr>
          <td>Labour</td>
          <td align="right">&nbsp;</td>
        </tr>
      </table></td>
    </tr>
    
	<?php while($row_labour = mysqli_fetch_array($query_labour)){ ?>
    <tr>
      <td valign="bottom"><textarea name="labour[]" rows="5" class="tarea-100per" id="labour"><?php  echo $row_labour['Description']; ?>
                  </textarea></td>
      <td width="50" align="center" valign="bottom"><input name="unit_l[]" type="text" class="tarea-100per" id="unit_l" value="hours" size="6" /></td>
      <td width="50" align="center" valign="bottom"><input name="qty_l[]" type="text" class="tarea-100per" id="qty_l" value="<?php echo $row_labour['Qty']; ?>" size="6" /></td>
      <td width="75" colspan="2" align="center" valign="bottom"><input name="price_l[]" type="text" class="tarea-100per" id="Price" value="<?php echo $row_labour['Price']; ?>" size="11" /></td>
      <td align="right" valign="bottom">
        <?php if($row_labour['Parent'] != 1){ ?>
        <input name="total_l[]" type="text" disabled="disabled" class="tarea-100per" id="total_l" value="<?php echo $row_labour['Total1']; ?>" size="7" style="text-align:right" />
        <?php } ?></td>
    </tr>
    <?php } // close loop ?>
    <tr>
      <td colspan="6">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="6"><table width="100%" border="0" cellpadding="2" cellspacing="3" class="td-mail">
        <tr>
          <td>Material</td>
          <td align="right">&nbsp;</td>
        </tr>
      </table></td>
    </tr>
    
	<?php while($row_material = mysqli_fetch_array($query_material)){ ?>
    <tr>
      <td><input name="material[]" type="text" class="tarea-100per" id="material" value="<?php echo $row_material['Description']; ?>" /></td>
      <td width="50" align="center"><input name="unit_m[]" type="text" class="tarea-100per" id="unit_m" value="<?php echo $row_material['Unit']; ?>" size="6" /></td>
      <td width="50" align="center"><input name="qty_m[]" type="text" class="tarea-100per" id="qty_m" value="<?php echo $row_material['Qty']; ?>" size="6" /></td>
      <td width="75" colspan="2" align="center"><input name="price_m[]" type="text" class="tarea-100per" id="Price" value="<?php echo $row_material['Price']; ?>" size="11" /></td>
      <td align="right"><input name="total_m[]" type="text" disabled="disabled" class="tarea-100per" id="total_m" value="<?php echo $row_material['Total1']; ?>" size="7" style="text-align:right" /></td>
    </tr>
    <?php } // close loop ?>
    <tr>
      <td colspan="6" align="right">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="6"><table width="100%" border="0" cellpadding="2" cellspacing="3" class="td-mail">
        <tr>
          <td>Equipment &amp; Machinery</td>
          <td align="right">&nbsp;</td>
        </tr>
      </table></td>
    </tr>
    <?php while($row_eq = mysqli_fetch_array($query_eq)){ ?>
    <tr>
      <td><input name="desc-e[]" type="text" class="tarea-100per" id="material" value="<?php echo $row_eq['Description']; ?>" /></td>
      <td width="50" align="center"><input name="unit-e[]" type="text" class="tarea-100per" id="unit_m" value="<?php echo $row_eq['Unit']; ?>" size="6" /></td>
      <td width="50" align="center"><input name="qty-e[]" type="text" class="tarea-100per" id="qty_m" value="<?php echo $row_eq['Qty']; ?>" size="6" /></td>
      <td width="75" colspan="2" align="center"><input name="price-e[]" type="text" class="tarea-100per" id="Price" value="<?php echo $row_eq['UnitPrice']; ?>" size="11" /></td>
      <td align="right"><input name="total-e[]" type="text" disabled="disabled" class="tarea-100per" id="total_m" value="<?php echo $row_eq['Total']; ?>" size="7" style="text-align:right" /></td>
    </tr>
    <?php } // close loop ?>
    <tr>
      <td colspan="6">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="6" align="right"><table width="100%" border="0" cellpadding="2" cellspacing="3" class="td-mail">
        <tr>
          <td>Transport</td>
          <td align="right">&nbsp;</td>
        </tr>
      </table></td>
    </tr>
    <?php while($row_transport = mysqli_fetch_array($query_transport)){ ?>
    <tr>
      <td align="right"><input name="t_comment[]" type="text" class="tarea-100per" id="t_comment[]" value="<?php echo $row_transport['TransportComment']; ?>" size="85" /></td>
      <td width="50" align="right"><input name="unit_t[]" type="text" class="tarea-100per" id="unit_t" value="km" size="5" /></td>
      <td width="50" align="right"><input name="transport[]" type="text" class="tarea-100per" id="transport" size="5" value="<?php echo $row_transport['Description']; ?>" /></td>
      <td width="36" align="right" nowrap="nowrap"><input name="qty_t[]" type="text" class="tarea-100per" id="qty_t" value="<?php echo $row_transport['Qty']; ?>" size="7" /></td>
      <td width="36" align="right" nowrap="nowrap"><input name="price_t[]" type="text" class="tarea-100per" id="price_t" value="<?php echo $row_transport['Price']; ?>" size="6" /></td>
      <td align="right"><input name="total_t[]" type="text" disabled="disabled" class="tarea-100per" id="total_t" value="<?php echo $row_transport['Total1']; ?>" size="7" style="text-align:right" /></td>
    </tr>
    <?php } // close loop ?>
  </table>
  <table width="100%" border="0" cellpadding="2" cellspacing="3" bordercolor="#FFFFFF">
    <tr>
      <td align="right">&nbsp;</td>
    </tr>
  </table>
</div>
</body>
</html>