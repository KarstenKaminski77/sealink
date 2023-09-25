<link href="paginate.css" rel="stylesheet" type="text/css" />

<?php
require_once('../functions/functions.php');

$rpp = 10; // results per page
$adjacents = 4;

$page = intval($_GET["page"]);
if($page<=0) $page = 1;

$reload = $_SERVER['PHP_SELF'];

// select appropriate results from DB:
$sql = "SELECT * FROM tbl_products";
$result = mysqli_query($con, $sql);

// count total number of appropriate listings:
$tcount = mysqli_num_rows($result);

// count number of pages:
$tpages = ($tcount) ? ceil($tcount/$rpp) : 1; // total pages, last page number

$count = 0;
$i = ($page-1)*$rpp;
while(($count<$rpp) && ($i<$tcount)) {
    mysqli_data_seek($result,$i);
    $query = mysqli_fetch_array($result);

    // output each row:
    echo "<p>" . $query['Manufacturer'] . ", " . $query['ProductName'] . "</p>\n";

    $i++;
    $count++;
}

// call pagination function from the appropriate file: pagination1.php, pagination2.php or pagination3.php
include("pagination3.php");
echo paginate_three($reload, $page, $tpages, $adjacents);
?>