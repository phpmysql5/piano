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
  $insertSQL = sprintf("INSERT INTO cartitem (ProductID, Quantity, TotalCost) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['ProductID'], "int"),
                       GetSQLValueString($_POST['Quantity'], "int"),
                       GetSQLValueString($_POST['TotalCost'], "int"));

  mysql_select_db($database_piano, $piano);
  $Result1 = mysql_query($insertSQL, $piano) or die(mysql_error());

  $insertGoTo = "../ourproducts/cartitems.php?CartItemID=" . $_GET['CartItemID'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO cartitem (ProductID, Quantity, TotalCost) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['ProductID'], "int"),
                       GetSQLValueString($_POST['Quantity'], "int"),
                       GetSQLValueString($_POST['TotalCost'], "int"));

  mysql_select_db($database_piano, $piano);
  $Result1 = mysql_query($insertSQL, $piano) or die(mysql_error());
  

$_SESSION['CartItemID'] =mysql_insert_id();
  $insertGoTo = "../ourproducts/cartitems.php?CartItemID=" . $_SESSION['CartItemID'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

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

$piano_rsProducts = "1";
if (isset($_GET['ProductID'])) {
  $piano_rsProducts = $_GET['ProductID'];
}
mysql_select_db($database_piano, $piano);
$query_rsProducts = sprintf("SELECT ProductID, TypeID, ProductName, Summary, ProductDesc, IMAGE, ProductModal, ProductPrice, brand.BrandName, products.BrandID FROM products, brand WHERE brand.BrandID=products.BrandID and products.ProductID=%s", GetSQLValueString($piano_rsProducts, "int"));
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
$query_rsCustomer = sprintf("SELECT * FROM customer WHERE CustID = %s", GetSQLValueString($colname_rsCustomer, "int"));
$rsCustomer = mysql_query($query_rsCustomer, $piano) or die(mysql_error());
$row_rsCustomer = mysql_fetch_assoc($rsCustomer);
$totalRows_rsCustomer = mysql_num_rows($rsCustomer);

$colname_rsCartItem = "2";
if (isset($_GET['ProductID'])) {
  $colname_rsCartItem = $_GET['ProductID'];
}
mysql_select_db($database_piano, $piano);
$query_rsCartItem = sprintf("SELECT CartItemID, ProductID, Quantity, TotalCost FROM cartitem WHERE ProductID = %s", GetSQLValueString($colname_rsCartItem, "int"));
$rsCartItem = mysql_query($query_rsCartItem, $piano) or die(mysql_error());
$row_rsCartItem = mysql_fetch_assoc($rsCartItem);
$totalRows_rsCartItem = mysql_num_rows($rsCartItem);

$colname_rsCust = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rsCust = $_SESSION['MM_Username'];
}
mysql_select_db($database_piano, $piano);
$query_rsCust = sprintf("SELECT CustID FROM customer WHERE Username = %s", GetSQLValueString($colname_rsCust, "text"));
$rsCust = mysql_query($query_rsCust, $piano) or die(mysql_error());
$row_rsCust = mysql_fetch_assoc($rsCust);
$totalRows_rsCust = mysql_num_rows($rsCust);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">


<?php require_once('../includes/head.php'); ?>

<title>Piano Street - CartItems</title>

<body>
<table width="700" align="center" id="tableLayout">
  <tr>
   <?php require_once('../includes/navigation.php'); ?>

  </tr>
  <tr>
    <td align="left" id="tdContent" ><h1  align="center"> The Piano Street</h1>&nbsp;      </h1>
      <h2>Cart details</h2>
      <h2>
  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
    <p>&nbsp;</p>
    
    <table align="center">
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Name</td>
        <td><?php echo $row_rsProducts['ProductName']; ?></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Quantity:</td>
        <td><input name="Quantity" type="text" value="1" size="32" readonly="readonly" /></td>
        </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">TotalCost:</td>
        <td><input name="TotalCost" type="text" value="<?php echo $row_rsProducts['ProductPrice']; ?>" size="32" readonly="readonly" /></td>
        </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">&nbsp;</td>
        <td><input type="submit" value="Insert record" /></td>
        </tr>
      </table>
    <input type="hidden" name="ProductID" value="<?php echo $_GET['ProductID']; ?>" />
    <input type="hidden" name="MM_insert" value="form1" />
  </form>
        <p><?php $_SESSION['MM_CustID']=$row_rsCust['CustID']; ?>
        </p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
      </h2>
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
      <p><a href="../ourproducts/logout.php"><img src="../assets/images/navigation/bullet.gif" alt="" width="12" height="7" />Logout</a></p></td>
  </tr>
  <tr>
   <?php require_once('../includes/footer.php'); ?>
</body>
</html>
<?php
mysql_free_result($rsProducts);

mysql_free_result($rsBrands);

mysql_free_result($rsCustomer);

mysql_free_result($rsCartItem);

mysql_free_result($rsCust);
?>
