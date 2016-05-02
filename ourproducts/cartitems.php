<?php require_once('../Connections/piano.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "../ourproducts/customer.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO cart (CustID, CartItemID, TotalCost) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['CustID'], "int"),
                       GetSQLValueString($_POST['CartItemID'], "int"),
                       GetSQLValueString($_POST['TotalCost'], "int"));

  mysql_select_db($database_piano, $piano);
  $Result1 = mysql_query($insertSQL, $piano) or die(mysql_error());

  $insertGoTo = "../ourproducts/payment.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_GET['CartItemID'])) && ($_GET['CartItemID'] != "") && (isset($_POST['Delete']))) {
  $deleteSQL = sprintf("DELETE FROM cartitem WHERE CartItemID=%s",
                       GetSQLValueString($_GET['CartItemID'], "int"));

  mysql_select_db($database_piano, $piano);
  $Result1 = mysql_query($deleteSQL, $piano) or die(mysql_error());

  $deleteGoTo = "../ourproducts/products.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}

$_SESSION['CartID']= mysql_insert_id();

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

$Piano_rsProducts = "1";
if (isset($_GET['ProductID'])) {
  $Piano_rsProducts = $_GET['ProductID'];
}
mysql_select_db($database_piano, $piano);
$query_rsProducts = sprintf("SELECT ProductID, TypeID, ProductName, Summary, ProductDesc, IMAGE, ProductModal, ProductPrice, brand.BrandName, products.BrandID FROM products, brand WHERE brand.BrandID=products.BrandID and products.ProductID=%s", GetSQLValueString($Piano_rsProducts, "int"));
$rsProducts = mysql_query($query_rsProducts, $piano) or die(mysql_error());
$row_rsProducts = mysql_fetch_assoc($rsProducts);
$totalRows_rsProducts = mysql_num_rows($rsProducts);

mysql_select_db($database_piano, $piano);
$query_rsBrands = "SELECT * FROM brand";
$rsBrands = mysql_query($query_rsBrands, $piano) or die(mysql_error());
$row_rsBrands = mysql_fetch_assoc($rsBrands);
$totalRows_rsBrands = mysql_num_rows($rsBrands);

$colname_rsCustomer = "1";
if (isset($_SESSION['CustID'])) {
  $colname_rsCustomer = $_SESSION['CustID'];
}
mysql_select_db($database_piano, $piano);
$query_rsCustomer = sprintf("SELECT FirstName, LastName, Address, City, `State`, ZIP, Phone, EmaiID FROM customer WHERE CustID = %s", GetSQLValueString($colname_rsCustomer, "int"));
$rsCustomer = mysql_query($query_rsCustomer, $piano) or die(mysql_error());
$row_rsCustomer = mysql_fetch_assoc($rsCustomer);
$totalRows_rsCustomer = mysql_num_rows($rsCustomer);

$maxRows_rsCartItem = 10;
$pageNum_rsCartItem = 0;
if (isset($_GET['pageNum_rsCartItem'])) {
  $pageNum_rsCartItem = $_GET['pageNum_rsCartItem'];
}
$startRow_rsCartItem = $pageNum_rsCartItem * $maxRows_rsCartItem;

$colname_rsCartItem = "10";
if (isset($_GET['CartItemID'])) {
  $colname_rsCartItem = $_GET['CartItemID'];
}
mysql_select_db($database_piano, $piano);
$query_rsCartItem = sprintf("SELECT CartItemID , Quantity, TotalCost, cartitem.ProductID, products.ProductName FROM cartitem, products WHERE CartItemID = %s and products.ProductID=cartitem.ProductID", GetSQLValueString($colname_rsCartItem, "int"));
$query_limit_rsCartItem = sprintf("%s LIMIT %d, %d", $query_rsCartItem, $startRow_rsCartItem, $maxRows_rsCartItem);
$rsCartItem = mysql_query($query_limit_rsCartItem, $piano) or die(mysql_error());
$row_rsCartItem = mysql_fetch_assoc($rsCartItem);

if (isset($_GET['totalRows_rsCartItem'])) {
  $totalRows_rsCartItem = $_GET['totalRows_rsCartItem'];
} else {
  $all_rsCartItem = mysql_query($query_rsCartItem);
  $totalRows_rsCartItem = mysql_num_rows($all_rsCartItem);
}
$totalPages_rsCartItem = ceil($totalRows_rsCartItem/$maxRows_rsCartItem)-1;

$custo_rsPaid = "2";
if (isset($_SESSION['MM_CustID'])) {
  $custo_rsPaid = $_SESSION['MM_CustID'];
}
mysql_select_db($database_piano, $piano);
$query_rsPaid = sprintf("SELECT products.ProductName, payment.`Date`, cartitem.ProductID, payment.AMOUNT FROM cart , customer, payment, products, cartitem WHERE payment.CartID=Cart.CartID and cart.CartItemID=cartitem.CartItemID and cartitem.ProductID=products.ProductID and  cart.CustID=%s and cart.CustID= customer.CustID ORDER BY payment.`Date` DESC", GetSQLValueString($custo_rsPaid, "int"));
$rsPaid = mysql_query($query_rsPaid, $piano) or die(mysql_error());
$row_rsPaid = mysql_fetch_assoc($rsPaid);
$totalRows_rsPaid = mysql_num_rows($rsPaid);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<script>
function myFunction()
{

Cost.value=<?php echo $row_rsProducts['ProductPrice']; ?> *select.value;



}
</script>
<?php require_once('../includes/head.php'); ?>

<script src="/SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<link href="/SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<title>Piano Street-Cart Confirmation</title>
<body>
<tr>
   <?php require_once('../includes/navigation.php'); ?>

  </tr>
  <tr>
    <td id="tdContent" ><h1 align="center">The Piano Street</h1>
      <p>&nbsp;</p>
      <h2>Cart Details      </h2>
      <table border="1">
        <tr>
          <td>Quantity</td>
          <td>TotalCost</td>
          <td>ProductName</td>
        </tr>
        <?php do { ?>
          <tr>
            <td><?php echo $row_rsCartItem['Quantity']; ?></td>
            <td><?php echo $row_rsCartItem['TotalCost']; ?></td>
            <td><?php echo $row_rsCartItem['ProductName']; ?></td>
          </tr>
          <?php } while ($row_rsCartItem = mysql_fetch_assoc($rsCartItem)); ?>
      </table>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <form id="form2" name="form2" method="post" action="">
Want to change your order
        <input type="submit" name="Delete" id="Delete" value="Click Here" />
      </form>
      <p>&nbsp;</p>
      <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
        <table align="center">
          <tr valign="baseline">
            <td><input type="submit" value="Confirm your order" /></td>
          </tr>
      </table>
        <input type="hidden" name="CustID" value="<?php echo $_SESSION['MM_CustID']; ?>" />
        <input type="hidden" name="CartItemID" value="<?php echo $_SESSION['CartItemID']; ?>" />
        <input type="hidden" name="MM_insert" value="form1" />
        <input type="hidden" name="TotalCost" value="<?php echo $row_rsProducts['ProductPrice']; ?>" size="32" />
      </form>
      <p>&nbsp;</p>
<p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <h2>&nbsp;</h2>
      <p>&nbsp;</p></td>
    <td id="tdSidebar"><p>
      <?php do { ?>
        <a href="../ourproducts/products.php?BrandID=<?php echo $row_rsBrands['BrandID']; ?>" class="current"><?php echo $row_rsBrands['BrandName']; ?></a>
        <?php } while ($row_rsBrands = mysql_fetch_assoc($rsBrands)); ?>
    </p>
      <p><a href="../ourproducts/logout.php">Logout</a></p>
      <p>&nbsp; </p>
      <div id="TabbedPanels1" class="TabbedPanels">
        <ul class="TabbedPanelsTabGroup">
                <br />
          </li>
</ul>
        <div class="TabbedPanelsContentGroup">
          <div class="TabbedPanelsContent">
            <p>Products Bought :</p>
            <?php do { ?>
              <p><a href="../ourproducts/product.php?ProductID=<?php echo $row_rsPaid['ProductID']; ?>"><?php echo $row_rsPaid['ProductName']; ?></a></p>
              <?php } while ($row_rsProducts = mysql_fetch_assoc($rsProducts)); ?>
            <p>&nbsp; </p>
            <p>&nbsp;</p>
          </div>
</div>
      </div>
      <p>&nbsp;</p></td>
  </tr>
  <tr>
   <?php require_once('../includes/footer.php'); ?>
  <script type="text/javascript">
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
  </script>
</body><?php echo $_SESSION['MM_CustID']; ?>
</html>
<?php
mysql_free_result($rsProducts);

mysql_free_result($rsBrands);

mysql_free_result($rsCustomer);

mysql_free_result($rsCartItem);

mysql_free_result($rsPaid);
?>
