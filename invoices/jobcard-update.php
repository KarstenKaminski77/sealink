<?php
session_start();

require_once('../Connections/seavest.php');
require_once('../functions/functions.php');

logout($con);

if (isset($_POST['jc_changes'])) {
    $sq = [' Status = 11 '];
    $sql_query = "UPDATE tbl_jc SET ";

    if (isset($_POST['InvoiceNo']) && !empty($_POST['InvoiceNo'])) {
        $sq[] = " InvoiceNo = '" . $_POST['InvoiceNo'] . "'";
    }

    if (isset($_POST['JobNo']) && !empty($_POST['JobNo'])) {
        $sq[] = " JobNo = '" . $_POST['JobNo'] . "'";
    }

    if (isset($_POST['RefNo']) && !empty($_POST['RefNo'])) {
        $sq[] = " RefNo = '" . $_POST['RefNo'] . "'";
    }

    if (isset($_POST['SubTotal']) && !empty($_POST['SubTotal'])) {
        $sq[] = " SubTotal = '" . $_POST['SubTotal'] . "'";
    }

    if (isset($_POST['VAT']) && !empty($_POST['VAT'])) {
        $sq[] = " VAT = '" . $_POST['VAT'] . "'";
    }

    if (isset($_POST['Total2']) && !empty($_POST['Total2'])) {
        $sq[] = " Total2 = '" . $_POST['Total2'] . "'";
    }

    $q = implode(', ', $sq);

    $sql_query .= $q . " WHERE JobId = '" . $_POST['JobId'] . "'";

    mysqli_query($con, $sql_query) or die(mysqli_error($con));

    header('location: /inv/invoices/jobcard-update.php');
}

if (isset($_GET['delete'])) {
    $jobid = $_GET['delete'];
    mysqli_query($con, "UPDATE tbl_jc SET Status = '999' WHERE JobId = '$jobid'") or die(mysqli_error($con));
}

$view = 1;

if (isset($_GET['edit'])) {
    $jobid = $_GET['edit'];
    $sql_query = "
        SELECT
        tbl_jc.JobId,
        tbl_jc.InvoiceNo,
        tbl_jc.JobNo,
        tbl_jc.RefNo,
        tbl_jc.SubTotal,
        tbl_jc.VAT,
        tbl_jc.Total2,
        tbl_jc.InvoiceDate AS date_for_sort
        FROM tbl_jc
        WHERE
        tbl_jc.JobId = " . $jobid;
    $results = mysqli_query($con, $sql_query) or die(mysqli_error($con));
    $jobcardData = mysqli_fetch_assoc($results);
    $view = 0;
} else {
    $query_Recordset3 = "
        SELECT
        tbl_jc.JobId,
        tbl_jc.InvoiceNo,
        tbl_jc.JobNo,
        tbl_jc.RefNo,
        tbl_jc.SubTotal,
        tbl_jc.VAT,
        tbl_jc.Total2,
        tbl_jc.InvoiceDate AS date_for_sort
        FROM tbl_jc
        WHERE
        STATUS IN ('11', '7')
        GROUP BY
        tbl_jc.JobId
        ORDER BY
        date_for_sort ASC";

    $Recordset3 = mysqli_query($con, $query_Recordset3) or die(mysqli_error($con));
    $row_Recordset3 = mysqli_fetch_assoc($Recordset3);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Seavest Asset Management</title>
    <link href="../css/layout.css" rel="stylesheet" type="text/css" />
    <link href="../css/fonts.css" rel="stylesheet" type="text/css" />
    <link href="../css/breadcrumbs.css" rel="stylesheet" type="text/css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../menu/styles.css">
    <script src="../menu/script.js"></script>
    <script type="text/javascript">
        function MM_jumpMenu(targ, selObj, restore) { //v3.0
            eval(targ + ".location='" + selObj.options[selObj.selectedIndex].value + "'");
            if (restore) selObj.selectedIndex = 0;
        }
    </script>
    <script>
        !window.jQuery && document.write('<script src="jquery-1.4.3.min.js"><\/script>');
    </script>
    <script type="text/javascript" src="../fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
    <script type="text/javascript" src="../fancybox/jquery.fancybox-1.3.4.pack.js"></script>
    <link rel="stylesheet" type="text/css" href="../fancybox/jquery.fancybox-1.3.4.css" media="screen" />
    <script type="text/javascript">
        jQuery(document).ready(function() {
            $(".various3").fancybox({
                'width': 500,
                'height': 230,
                'autoScale': true,
                'transitionIn': 'none',
                'transitionOut': 'none',
                'type': 'iframe',
                'padding': 0,
                'overlayOpacity': '0.8',
                'overlayColor': 'black',
            });
        });
    </script>
    <link href="//cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    <script src="//cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#myTable').DataTable({
                "bFilter": false,
                "order": [0, 'asc'],
                "lengthMenu": [10, 25, 50, 100, 150, 200, 250, 500]
            });
        });
    </script>
    <style>
        .btn-delete {
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
            background-color: #f44336;
        }

        .form-input {
            width: 60%;
            padding: 12px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
    </style>
</head>

<body>
    <!-- Banner -->
    <div id="logo">
        <?php logout_link(); ?>
        <div id="tab-user"><?php echo $_COOKIE['name']; ?></div>
        <?php area_select($con); ?>
    </div>
    <!-- End Banner -->
    <!-- Navigatiopn -->
    <?php include('../menu/menu.php'); ?>
    <!-- End Navigation -->
    <!-- Breadcrumbs -->
    <div class="td-bread">
        <ul class="breadcrumb">
            <li><a href="#">Seavest Asset Management</a></li>
            <li><a href="#">Accounts</a></li>
            <li><a href="#">Invoices</a></li>
            <li><a href="#">Jobcard Update</a></li>
            <li></li>
        </ul>
    </div>
    <!-- End Breadcrumbs -->
    <!-- Main Form -->
    <div id="main-wrapper">
        <div id="list-border">
            <?php if ($view != 1) : ?>
                <form action="" method="POST">
                    <input type="hidden" name="JobId" value="<?= $jobcardData['JobId']; ?>">
                    <table width=" 100%">
                        <thead>
                            <tr>
                                <th width="30%"></th>
                                <th width="70%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <label for="InvoiceNo">Invoice #</label>
                                </td>
                                <td>
                                    <input class="form-input" type="number" name="InvoiceNo" id="InvoiceNo" value="<?= $jobcardData['InvoiceNo']; ?>">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="JobNo">Job #</label>
                                </td>
                                <td>
                                    <input class="form-input" type="text" name="JobNo" id="JobNo" value="<?= $jobcardData['JobNo']; ?>">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="RefNo">Order #</label>
                                </td>
                                <td>
                                    <input class="form-input" type="text" name="RefNo" id="RefNo" value="<?= $jobcardData['RefNo']; ?>">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="SubTotal">Sub-Total</label>
                                </td>
                                <td>
                                    <input class="form-input" type="number" step="0.01" name="SubTotal" id="SubTotal" value="<?= $jobcardData['SubTotal']; ?>">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="VAT">VAT</label>
                                </td>
                                <td>
                                    <input class="form-input" type="number" step="0.01" name="VAT" id="VAT" value="<?= $jobcardData['VAT']; ?>">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="Total2">Total</label>
                                </td>
                                <td>
                                    <input class="form-input" type="number" step="0.01" name="Total2" id="Total2" value="<?= $jobcardData['Total2']; ?>">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                </td>
                                <td>
                                    <a href="/inv/invoices/jobcard-update.php" class="btn-new">Cancel</a>
                                    <button type="submit" name="jc_changes" class="btn-new">Submit Jobcard Changes</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            <?php else : ?>
                <table id="myTable" class="display" width="100%">
                    <thead>
                        <tr>
                            <th>Inv #</th>
                            <th>Job #</th>
                            <th>Order #</th>
                            <th>Sub-Total</th>
                            <th>VAT</th>
                            <th>Total</th>
                            <th width="15%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($Recordset3)) : ?>
                            <tr>
                                <td>
                                    <?= $row['InvoiceNo']; ?>
                                </td>
                                <td>
                                    <?= $row['JobNo']; ?>
                                </td>
                                <td>
                                    <?= $row['RefNo']; ?>
                                </td>
                                <td>
                                    R <?= $row['SubTotal']; ?>
                                </td>
                                <td>
                                    R <?= $row['VAT']; ?>
                                </td>
                                <td>
                                    R <?= $row['Total2']; ?>
                                </td>
                                <td>
                                    <a href="?edit=<?= $row['JobId']; ?>" class="btn-new">Edit Jobcard</a>
                                    <a href="?delete=<?= $row['JobId']; ?>" class="btn-delete">Delete Jobcard</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
    <!-- End Main Form -->
</body>

</html>
<?php
mysqli_close($con);
mysqli_free_result($rs_totals);
mysqli_free_result($Recordset3);
?>