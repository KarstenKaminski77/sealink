<?php
require_once('functions/functions.php');

$query_list = "
  SELECT
	  tbl_products.Id,
	  tbl_products.ItemNumber,
	  tbl_products.Price,
	  tbl_products.ProductName,
	  tbl_products.ProductDescription,
	  tbl_products.ProductGraphic,
	  tbl_products.Manufacturer,
	  tbl_products.Category1,
	  tbl_products.Ia_Value1,
	  tbl_products.ManufacturerLogo $comma
	  $match
  FROM
	  tbl_products
  WHERE tbl_products.Id = '". $_GET['Product'] ."'";

$query_list = mysqli_query($con, $query_list)or die(mysqli_error($con));
$rows = mysqli_num_rows($query_list);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>

<body>

        <!-- Details -->
        <div id="row<?php echo $i; ?>" class="row2">
        
          <div id="row2-left">
            <img src="<?php echo $row_list['ManufacturerLogo']; ?>" class="img-manu" />
            <img src="<?php echo $row_list['ProductGraphic']; ?>" class="img-product" />
          </div>
          
          <div id="row2-centre">
            <a name="<?php echo $row_list['Id']; ?>" id="<?php echo $row_list['Id']; ?>"></a>
            <span class="product-title"><?php echo $row_list['ProductName']; ?></span>
            <span class="sku">SKU: <?php echo $row_list['ItemNumber']; ?></span>
            <?php echo $row_list['ProductDescription']; ?>
            <?php product_detail_values($con, $row_list['Id']) ?>
          </div>
          
          <div id="row2-right">
            <span class="price-title">AED <?php echo $row_list['Price']; ?></span>
            <script>
              function Total<?php echo $i; ?>(qty<?php echo $i; ?>,ud,total<?php echo $i; ?>,value){
               qty<?php echo $i; ?>=document.getElementById(qty<?php echo $i; ?>);
               ud>0?qty<?php echo $i; ?>.value++:qty<?php echo $i; ?>.value--;
               qty<?php echo $i; ?>.value=Math.max(qty<?php echo $i; ?>.value,0);
               document.getElementById(total<?php echo $i; ?>).value=(qty<?php echo $i; ?>.value*value).toFixed(2);
              }
                 
            </script>
            <div id="qry-header">Quantity</div>
            <input type='text' name='qty' id='qty<?php echo $i; ?>' readonly=true value="0" class="qty-field"/>
            <input type='button' name='subtract' onclick='Total<?php echo $i; ?>("qty<?php echo $i; ?>",-1,"total<?php echo $i; ?>",<?php echo $row_list['Price']; ?>);' value='' class="qty-sub"/>
            <input type='button' name='add' onclick='Total<?php echo $i; ?>("qty<?php echo $i; ?>",1,"total<?php echo $i; ?>",<?php echo $row_list['Price']; ?>);' value='' class="qty-add"/>
            <div id="total-container">
              Sub total: AED <input name='total<?php echo $i; ?>' type='text' class="sub-field" id='total<?php echo $i; ?>' value="0.00" />
              <input name="basket-add-<?php echo $i; ?>" type="submit" value="ADD TO ORDER" class="order-add" />
            </div>
          </div>
          
        </div>
      </form>
      <!-- Close Details -->

</body>
</html>