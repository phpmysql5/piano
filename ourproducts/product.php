<?php require_once('../Connections/piano.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$Product_rsProducts = "3";
if (isset($_GET['ProductID'])) {
  $Product_rsProducts = $_GET['ProductID'];
}
mysql_select_db($database_piano, $piano);
$query_rsProducts = sprintf("SELECT  ProductName, Summary, ProductDesc, IMAGE, ProductModal, ProductPrice, brand.BrandName, products.BrandID, products.Status, pianotype.TypeName, products.TypeID FROM products, brand, pianotype WHERE brand.BrandID=products.BrandID AND products.ProductID=%s  AND pianotype.TypeID=products.TypeID", GetSQLValueString($Product_rsProducts, "int"));
$rsProducts = mysql_query($query_rsProducts, $piano) or die(mysql_error());
$row_rsProducts = mysql_fetch_assoc($rsProducts);
$totalRows_rsProducts = mysql_num_rows($rsProducts);

mysql_select_db($database_piano, $piano);
$query_rsBrands = "SELECT * FROM brand";
$rsBrands = mysql_query($query_rsBrands, $piano) or die(mysql_error());
$row_rsBrands = mysql_fetch_assoc($rsBrands);
$totalRows_rsBrands = mysql_num_rows($rsBrands);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php require_once('../includes/head.php'); ?>

<title>Piano Street</title>

<body>
<table width="700" align="center" id="tableLayout">
  <tr>
   <?php require_once('../includes/navigation.php'); ?>

  </tr>
  <tr>
    <td align="left" id="tdContent" ><h1>Our Products:<?php echo $row_rsProducts['ProductName']; ?></h1>
      <h1> <?php echo $row_rsProducts['BrandName']; ?>   <a href="../abouttea.php?TypeID=<?php echo $row_rsProducts['TypeID']; ?>"><?php echo $row_rsProducts['TypeName']; ?></a></h1>
      <h1><?php echo $row_rsProducts['ProductModal']; ?> Series</h1>
      <p>&nbsp;</p>
      <h2><?php echo $row_rsProducts['ProductName']; ?></h2>
      <p><strong><img src="../assets/images/products/<?php echo $row_rsProducts['IMAGE']; ?>" alt="<?php echo $row_rsProducts['ProductName']; ?>" /></strong></p>
      <p><strong>Price: </strong> <?php echo $row_rsProducts['ProductPrice']; ?></p>
      <p><?php echo $row_rsProducts['ProductDesc']; ?></p>
      <h2>
      <?php if($row_rsProducts['Status']==1)
			  {
				  echo "Sold";}
				  else
				  { ?><a href="../ourproducts/cart.php?ProductID=<?php echo $_GET['ProductID']; ?>">Add to cart</a>
				  <?php }?></h2>
      <p>&nbsp;</p></td>
    <td id="tdSidebar"><?php do { ?>
      <a href="../ourproducts/products.php?BrandID=<?php echo $row_rsBrands['BrandID']; ?>" class="current"><?php echo $row_rsBrands['BrandName']; ?></a>
      <?php } while ($row_rsBrands = mysql_fetch_assoc($rsBrands)); ?></td>
  </tr>
  <tr>
   <?php require_once('../includes/footer.php'); ?>

</body>
</html>
<?php
mysql_free_result($rsProducts);

mysql_free_result($rsBrands);
?>
