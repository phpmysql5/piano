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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rsKawai = 10;
$pageNum_rsKawai = 0;
if (isset($_GET['pageNum_rsKawai'])) {
  $pageNum_rsKawai = $_GET['pageNum_rsKawai'];
}
$startRow_rsKawai = $pageNum_rsKawai * $maxRows_rsKawai;

$colname_rsKawai = "1";
if (isset($_GET['BrandID'])) {
  $colname_rsKawai = $_GET['BrandID'];
}
mysql_select_db($database_piano, $piano);
$query_rsKawai = sprintf("SELECT ProductID, TypeID, ProductName, IMAGE FROM products WHERE BrandID = %s", GetSQLValueString($colname_rsKawai, "int"));
$query_limit_rsKawai = sprintf("%s LIMIT %d, %d", $query_rsKawai, $startRow_rsKawai, $maxRows_rsKawai);
$rsKawai = mysql_query($query_limit_rsKawai, $piano) or die(mysql_error());
$row_rsKawai = mysql_fetch_assoc($rsKawai);

if (isset($_GET['totalRows_rsKawai'])) {
  $totalRows_rsKawai = $_GET['totalRows_rsKawai'];
} else {
  $all_rsKawai = mysql_query($query_rsKawai);
  $totalRows_rsKawai = mysql_num_rows($all_rsKawai);
}
$totalPages_rsKawai = ceil($totalRows_rsKawai/$maxRows_rsKawai)-1;

$maxRows_rsBrands = 10;
$pageNum_rsBrands = 0;
if (isset($_GET['pageNum_rsBrands'])) {
  $pageNum_rsBrands = $_GET['pageNum_rsBrands'];
}
$startRow_rsBrands = $pageNum_rsBrands * $maxRows_rsBrands;

mysql_select_db($database_piano, $piano);
$query_rsBrands = "SELECT * FROM brand";
$query_limit_rsBrands = sprintf("%s LIMIT %d, %d", $query_rsBrands, $startRow_rsBrands, $maxRows_rsBrands);
$rsBrands = mysql_query($query_limit_rsBrands, $piano) or die(mysql_error());
$row_rsBrands = mysql_fetch_assoc($rsBrands);

if (isset($_GET['totalRows_rsBrands'])) {
  $totalRows_rsBrands = $_GET['totalRows_rsBrands'];
} else {
  $all_rsBrands = mysql_query($query_rsBrands);
  $totalRows_rsBrands = mysql_num_rows($all_rsBrands);
}
$totalPages_rsBrands = ceil($totalRows_rsBrands/$maxRows_rsBrands)-1;

$queryString_rsKawai = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsKawai") == false && 
        stristr($param, "totalRows_rsKawai") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsKawai = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsKawai = sprintf("&totalRows_rsKawai=%d%s", $totalRows_rsKawai, $queryString_rsKawai);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php require_once('../includes/head.php'); ?>

<title>Piano Street</title>

<body>
<?php require_once('../includes/navigation.php'); ?>

  </tr>
  <tr>
    <td id="tdContent"  ><h1 align="center" > The Piano Street</h1>
      <p>&nbsp;</p>
      <table border="" align="center">
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <?php do { ?>
          <tr>
            <td><h1><a href="../ourproducts/product.php?ProductID=<?php echo $row_rsKawai['ProductID']; ?>"></a><h1><a href="../ourproducts/product.php?ProductID=<?php echo $row_rsKawai['ProductID']; ?>"><?php echo $row_rsKawai['ProductName']; ?></a></h1></a></td>
           <td><img src="../assets/images/products/<?php echo $row_rsKawai['IMAGE']; ?>" alt="<?php echo $row_rsKawai['ProductName']; ?>" /></td>
        </tr>
          <?php } while ($row_rsKawai = mysql_fetch_assoc($rsKawai)); ?>
      </table>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
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
mysql_free_result($rsKawai);

mysql_free_result($rsBrands);
?>
