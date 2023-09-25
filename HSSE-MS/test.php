<?php
require_once('../Connections/seavest.php'); 
require_once('../functions/functions.php');

$id = 19;

header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=report_".date("d_m_Y_G_i").".doc");

$query = mysqli_query($con, "SELECT * FROM tbl_op WHERE Id = '$id'")or die(mysqli_error($con));
$row = mysqli_fetch_array($query);

$fp = fopen($row['Name'].'.doc', 'w+');

?>
<html
    xmlns:o='urn:schemas-microsoft-com:office:office'
    xmlns:w='urn:schemas-microsoft-com:office:word'
    xmlns='http://www.w3.org/TR/REC-html40'>
    <head><title>Time</title>
    <xml>
        <w:worddocument xmlns:w="#unknown">
            <w:view>Print</w:view>
            <w:zoom>90</w:zoom>
            <w:donotoptimizeforbrowser />
        </w:worddocument>
    </xml>
    <style>
        @page Section1
        {size:8.5in 11.0in;
         margin:1.0in 1.25in 1.0in 1.25in ;
         mso-header-margin:.5in;
         mso-footer-margin:.5in; mso-paper-source:0;}
        div.Section1
        {page:Section1;}
    </style>
</head>
<body lang=EN-US style='tab-interval:.5in' onLoad="window.close">
    <div class="Section1">
      <?php echo $row['OperationalProcedure']; ?>
    </div>
</body>
</html>