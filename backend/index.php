<?php
session_start();

if(!isset($_SESSION['userid'])){
header('Location: login.php');
}
if($_SESSION['kt_login_id'] == 9){
header('Location: ../jc_select.php');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Seavest</title>
<link href="../insurance/search/fonts.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style3 {font-family: Arial; font-size: 18px; font-weight: bold; color: #0EA1B1; }
.btns {
	font-family: Arial;
	font-size: 12px;
	font-weight: bold;
	text-transform: capitalize;
	color: #1E3D5B;
	background-color: #6699CC;
	text-decoration:none;
}
.style12 {color: #1E3D5B}
-->
</style>
</head>

<body>
<p>&nbsp;</p>
<p>&nbsp;</p>
<table width="600" border="1" align="center" cellpadding="3" cellspacing="3" bordercolor="#0EA1B1" bgcolor="#FFFFFF" style="vertical-align:middle">
  <tr bordercolor="#184392" bgcolor="#8EA2F9">
    <td height="30" align="center" valign="middle" bordercolor="#6699CC" bgcolor="#A6CAF0" class="style3 style12">Welcome to the 
      Administration Section</td>
  </tr>
  <tr>
    <td align="center" valign="top" bordercolor="#3973AC" bgcolor="#6699CC" class="text">&nbsp;</td>
  </tr>
  <tr bordercolor="#184392" bgcolor="#8EA2F9">
    <td align="center" valign="middle" bordercolor="#6699CC" bgcolor="#A6CAF0" class="text"><input name="btn2362233223" type="button" class="btns" onclick="javascript:document.location='appraisals.php';" value="Appraisals" style="width:180px" /></td>
  </tr>
  <tr bordercolor="#184392" bgcolor="#8EA2F9">
    <td align="center" valign="middle" bordercolor="#6699CC" bgcolor="#A6CAF0" class="text"><input name="btn2362233225" type="button" class="btns" onclick="javascript:document.location='appraisal_ques.php';" value="Appraisal Questions" style="width:180px" /></td>
  </tr>
  <tr bordercolor="#184392" bgcolor="#8EA2F9">
    <td align="center" valign="middle" bordercolor="#6699CC" bgcolor="#A6CAF0" class="text"><input name="btn236223322" type="button" class="btns" onclick="javascript:document.location='companies.php';" value="Companies" style="width:180px" /></td>
  </tr>
  <tr bordercolor="#184392" bgcolor="#8EA2F9">
    <td align="center" valign="middle" bordercolor="#6699CC" bgcolor="#A6CAF0" class="text"><input name="btn23622332222" type="button" class="btns" onclick="javascript:document.location='emergency-cell.php';" value="Emergency Cell Numbers" style="width:180px" /></td>
  </tr>
  <tr bordercolor="#184392" bgcolor="#8EA2F9">
    <td align="center" valign="middle" bordercolor="#6699CC" bgcolor="#A6CAF0" class="text"><input name="btn23622332222" type="button" class="btns" onclick="javascript:document.location='emergency-history.php';" value="Emergency History" style="width:180px" /></td>
  </tr>
  
  <tr bordercolor="#184392" bgcolor="#8EA2F9">
    <td align="center" valign="middle" bordercolor="#6699CC" bgcolor="#A6CAF0" class="text"><input name="btn23622332222" type="button" class="btns" onclick="javascript:document.location='employees.php';" value="Employees" style="width:180px" /></td>
  </tr>
  <tr bordercolor="#184392" bgcolor="#8EA2F9">
    <td align="center" valign="middle" bordercolor="#6699CC" bgcolor="#A6CAF0" class="text"><input name="btn2362233222" type="button" class="btns" onclick="javascript:document.location='far-classification.php';" value="FAR Risk Classification" style="width:180px" /></td>
  </tr>
  <tr bordercolor="#184392" bgcolor="#8EA2F9">
    <td align="center" valign="middle" bordercolor="#6699CC" bgcolor="#A6CAF0" class="text"><input name="btn2362233222" type="button" class="btns" onclick="javascript:document.location='far-high-risk-classification.php';" value="FAR Risk High Classification" style="width:180px" /></td>
  </tr>
  <tr bordercolor="#184392" bgcolor="#8EA2F9">
    <td align="center" valign="middle" bordercolor="#6699CC" bgcolor="#A6CAF0" class="text"><input name="btn2362233222" type="button" class="btns" onclick="javascript:document.location='far-compulsary-equipment.php';" value="FAR High Risk Tools" style="width:180px" /></td>
  </tr>
  <tr bordercolor="#184392" bgcolor="#8EA2F9">
    <td align="center" valign="middle" bordercolor="#6699CC" bgcolor="#A6CAF0" class="text"><input name="btn2362233222" type="button" class="btns" onclick="javascript:document.location='far-competenance.php';" value="FAR Competenance" style="width:180px" /></td>
  </tr>
  <tr bordercolor="#184392" bgcolor="#8EA2F9">
    <td width="50%" align="center" valign="middle" bordercolor="#6699CC" bgcolor="#A6CAF0" class="text"><input name="btn2362233222" type="button" class="btns" onclick="javascript:document.location='sites.php';" value="Sites" style="width:180px" /></td>
  </tr>
  <tr bordercolor="#184392" bgcolor="#8EA2F9">
    <td align="center" valign="middle" bordercolor="#6699CC" bgcolor="#A6CAF0" class="text"><input name="btn2362233224" type="button" class="btns" onclick="javascript:document.location='rates.php';" value="Labour Rates" style="width:180px" /></td>
  </tr>
  <tr bordercolor="#184392" bgcolor="#8EA2F9">
    <td align="center" valign="middle" bordercolor="#6699CC" bgcolor="#A6CAF0" class="text"><input name="btn2362233222322" type="button" class="btns" onclick="javascript:document.location='fuel.php';" value="Fuel Rates" style="width:180px" /></td>
  </tr>
  <tr bordercolor="#184392" bgcolor="#8EA2F9">
    <td align="center" valign="middle" bordercolor="#6699CC" bgcolor="#A6CAF0" class="text"><input name="btn2362233222322332" type="button" class="btns" onclick="javascript:document.location='mod-users.php';" value="Progress Users" style="width:180px" /></td>
  </tr>
  <tr bordercolor="#184392" bgcolor="#8EA2F9">
    <td align="center" valign="middle" bordercolor="#6699CC" bgcolor="#A6CAF0" class="text"><input name="btn236223322232233" type="button" class="btns" onclick="javascript:document.location='sms.php';" value="SMS's" style="width:180px" /></td>
  </tr>
  <tr bordercolor="#184392" bgcolor="#8EA2F9">
    <td align="center" valign="middle" bordercolor="#6699CC" bgcolor="#A6CAF0" class="text"><input name="btn23622332223223" type="button" class="btns" onclick="javascript:document.location='techs.php';" value="Technicians" style="width:180px" /></td>
  </tr>
  <tr bordercolor="#184392" bgcolor="#8EA2F9">
    <td align="center" valign="middle" bordercolor="#6699CC" bgcolor="#A6CAF0" class="text"><input name="btn2362233222322322" type="button" class="btns" onclick="javascript:document.location='tools_add.php';" value="Tools" style="width:180px" /></td>
  </tr>
  <tr bordercolor="#184392" bgcolor="#8EA2F9">
    <td align="center" valign="middle" bordercolor="#6699CC" bgcolor="#A6CAF0" class="text"><input name="btn236223322232232" type="button" class="btns" onclick="javascript:document.location='tools.php';" value="Tool Quantities" style="width:180px" /></td>
  </tr>
  <tr>
    <td align="center" valign="top" bordercolor="#3973AC" bgcolor="#6699CC" class="text">&nbsp;</td>
  </tr>
  <tr>
    <td height="30" align="center" valign="middle" bordercolor="#6699CC" bgcolor="#A6CAF0">&nbsp;Logout</td>
  </tr>
  <tr>
    <td align="center" valign="middle" bordercolor="#3973AC" bgcolor="#6699CC" class="smalltext">&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>