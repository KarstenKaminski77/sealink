<?php
session_start();
//https://thisinterestsme.com/php-export-excel/
require_once('../Connections/seavest.php');
require_once('../functions/functions.php');

logout($con);

if (isset($_POST['submit'])) {

  if (isset($_POST['year']) && $_POST['year'] != 'Select year...' && isset($_POST['month']) && $_POST['month'] != 'Select month...') {
    $kpi = false;
    //Define the separator line
    $separator = "\t";
    $date = date('Y-m', strtotime('1-' . $_POST['month'] . '-' . $_POST['year']));
    $where = "NewInvoiceDate LIKE '" . $date . "%'";

    if ($_POST['submit'] == 'Export Data') {
      //The name of the Excel file that we want to force the
      //browser to download.
      $filename = 'InvoiceSatements-' . $_POST['month'] . '-' . $_POST['year'] . '.xls';
      $heading = ['InvoiceNo', 'JobNo', 'SiteName', 'Date', 'SubTotal', 'VAT', 'Total'];
      $query = "
      SELECT
        tbl_jc.InvoiceNo,
        tbl_jc.JobNo,
        tbl_sites.Name AS SiteName,
        tbl_jc.Date,
        tbl_jc.SubTotal,
        tbl_jc.VAT,
        tbl_jc.Total2 AS Total
      FROM
        (
          (
            tbl_jc
            LEFT JOIN tbl_companies ON tbl_companies.Id = tbl_jc.CompanyId
          )
          LEFT JOIN tbl_sites ON tbl_sites.Id = tbl_jc.SiteId
        )
      WHERE $where
      GROUP BY
        JobId
      ORDER BY
        tbl_jc.Id ASC
    ";
    }

    if ($_POST['submit'] == 'Export KPI Data') {
      $kpi = true;
      //The name of the Excel file that we want to force the
      //browser to download.
      $filename = 'DailyInvoiceKPI-' . $_POST['month'] . '-' . $_POST['year'] . '.xls';
      $heading = ['Date', 'DBN - BM Quoted', 'DBN - BM', 'DBN - S', 'DBN - E', 'DBN - PROJECTS', 'JHB - BM Quoted', 'JHB - BM', 'JHB - S', 'JHB - E', 'JHB - PROJECTS', 'Daily Total'];
      $query = "
       SELECT
        tbl_jc.InvoiceQ,
        tbl_jc.JobNo,
        tbl_jc.Date,
        tbl_jc.SubTotal,
        tbl_jc.VAT,
        tbl_jc.AreaId AS Region,
        tbl_jc.SystemId As Category,
        tbl_jc.Total2
      FROM
        tbl_jc
      WHERE $where
      GROUP BY
        JobId
      ORDER BY
        tbl_jc.Date ASC
    ";
    }

    $rows = mysqli_query($con, $query);

    //Send the correct headers to the browser so that it knows
    //it is downloading an Excel file.
    header("Content-Type: application/xls");
    header("Content-Disposition: attachment; filename=$filename");
    header("Pragma: no-cache");
    header("Expires: 0");


    //If our query returned rows
    if (!empty($rows)) {

      //Dynamically print out the column names as the first row in the document.
      //This means that each Excel column will have a header.
      echo implode($separator, $heading) . "\n";
      if ($kpi) {
        $data = [];
        while ($row = mysqli_fetch_assoc($rows)) {

          $date = strtotime(str_replace(["\r", "\n"], '', $row['Date']));
          if ($row['Region'] == 1) {
            //Build M.
            if ($row['Category'] == 1) {
              if ($row['InvoiceQ'] == 1) {
                $data[$date]['DBN - BM Quoted'] = $row['Total2'];
              } else {
                $data[$date]['DBN - BM'] = $row['Total2'];
              }
            }
            //Sign
            if ($row['Category'] == 2) {
              $data[$date]['DBN - S'] = $row['Total2'];
            }
            //Elect
            if ($row['Category'] == 4) {
              $data[$date]['DBN - E'] = $row['Total2'];
            }
            //Struct
            if ($row['Category'] == 5) {
              $data[$date]['DBN - PROJECTS'] = $row['Total2'];
            }
          }

          if ($row['Region'] == 3) {
            //Build M.
            if ($row['Category'] == 1) {
              if ($row['InvoiceQ'] == 1) {
                $data[$date]['JHB - BM Quoted'] = $row['Total2'];
              } else {
                $data[$date]['JHB - BM'] = $row['Total2'];
              }
            }
            //Sign
            if ($row['Category'] == 2) {
              $data[$date]['JHB - S'] = $row['Total2'];
            }
            //Elect
            if ($row['Category'] == 4) {
              $data[$date]['JHB - E'] = $row['Total2'];
            }
            //Struct
            if ($row['Category'] == 5) {
              $data[$date]['JHB - PROJECTS'] = $row['Total2'];
            }
          }
        }

        $day = 1;
        $row = [];
        while ($day <= cal_days_in_month(CAL_GREGORIAN, $_POST['month'], $_POST['year'])) {
          $timestamp[] = strtotime($day . '-' . $_POST['month'] . '-' . $_POST['year']);
          $day++;
        }

        foreach ($timestamp as $value) {
          $row[] = [
            'Date' => $value,
            'DBN - BM Quoted' => !empty($data[$value]['DBN - BM Quoted']) ? $data[$value]['DBN - BM Quoted'] : 0,
            'DBN - BM' => !empty($data[$value]['DBN - BM']) ? $data[$value]['DBN - BM'] : 0,
            'DBN - S' => !empty($data[$value]['DBN - S']) ? $data[$value]['DBN - S'] : 0,
            'DBN - E' => !empty($data[$value]['DBN - E']) ? $data[$value]['DBN - E'] : 0,
            'DBN - PROJECTS' => !empty($data[$value]['DBN - PROJECTS']) ? $data[$value]['DBN - PROJECTS'] : 0,
            'JHB - BM Quoted' => !empty($data[$value]['JHB - BM Quoted']) ? $data[$value]['JHB - BM Quoted'] : 0,
            'JHB - BM' => !empty($data[$value]['JHB - BM']) ? $data[$value]['JHB - BM'] : 0,
            'JHB - S' => !empty($data[$value]['JHB - S']) ? $data[$value]['JHB - S'] : 0,
            'JHB - E' => !empty($data[$value]['JHB - E']) ? $data[$value]['JHB - E'] : 0,
            'JHB - PROJECTS' => !empty($data[$value]['JHB - PROJECTS']) ? $data[$value]['JHB - PROJECTS'] : 0,
            'Daily Total' => 0
          ];
        }


        foreach ($row as $rd) {
          
          //Clean the data and remove any special characters that might conflict
          foreach ($rd as $k => $v) {
            $rd[$k] = str_replace($separator . "$", "", $rd[$k]);
            $rd[$k] = preg_replace("/\r\n|\n\r|\n|\r/", " ", $rd[$k]);
            $rd[$k] = trim($rd[$k]);
          }

          //Implode and print the columns out using the 
          //$separator as the glue parameter
          echo implode($separator, $rd) . "\n";
        }
      } else {
        //Loop through the rows
        while ($row = mysqli_fetch_assoc($rows)) {

          //Clean the data and remove any special characters that might conflict
          foreach ($row as $k => $v) {
            $row[$k] = str_replace($separator . "$", "", $row[$k]);
            $row[$k] = preg_replace("/\r\n|\n\r|\n|\r/", " ", $row[$k]);
            $row[$k] = trim($row[$k]);
          }

          //Implode and print the columns out using the 
          //$separator as the glue parameter
          echo implode($separator, $row) . "\n";
        }
      }
    }
  }
  header('Location: /inv/invoices/export-invoices.php');
}
