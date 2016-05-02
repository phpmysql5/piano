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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE products SET BrandID=%s, TypeID=%s, ProductName=%s, Summary=%s, ProductDesc=%s, IMAGE=%s, ProductModal=%s, ProductPrice=%s, Status=%s WHERE ProductID=%s",
                       GetSQLValueString($_POST['BrandID'], "int"),
                       GetSQLValueString($_POST['TypeID'], "int"),
                       GetSQLValueString($_POST['ProductName'], "text"),
                       GetSQLValueString($_POST['Summary'], "text"),
                       GetSQLValueString($_POST['ProductDesc'], "text"),
                       GetSQLValueString($_POST['IMAGE'], "text"),
                       GetSQLValueString($_POST['ProductModal'], "text"),
                       GetSQLValueString($_POST['ProductPrice'], "int"),
                       GetSQLValueString($_POST['Status'], "int"),
                       GetSQLValueString($_POST['ProductID'], "int"));

  mysql_select_db($database_piano, $piano);
  $Result1 = mysql_query($updateSQL, $piano) or die(mysql_error());

  $updateGoTo = "../ourproducts/products.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO carddetails (CardNo, CardholderName, ExpiryDate, Bank, CardType) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['CardNo'], "int"),
                       GetSQLValueString($_POST['CardholderName'], "text"),
                       GetSQLValueString($_POST['ExpiryDate'], "date"),
                       GetSQLValueString($_POST['Bank'], "text"),
                       GetSQLValueString($_POST['CardType'], "int"));

  mysql_select_db($database_piano, $piano);
  $Result1 = mysql_query($insertSQL, $piano) or die(mysql_error());

$_SESSION['CardID']=mysql_insert_id();

  $insertGoTo = "../ourproducts/final.php";
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

$Piano_rsProducts = "2";
if (isset($_GET['ProductID'])) {
  $Piano_rsProducts = $_GET['ProductID'];
}
mysql_select_db($database_piano, $piano);
$query_rsProducts = sprintf("SELECT ProductID, TypeID, ProductName, Summary, ProductDesc, IMAGE, ProductModal, ProductPrice, brand.BrandName, products.BrandID, products.Status FROM products, brand WHERE brand.BrandID=products.BrandID AND products.ProductID=%s", GetSQLValueString($Piano_rsProducts, "int"));
$rsProducts = mysql_query($query_rsProducts, $piano) or die(mysql_error());
$row_rsProducts = mysql_fetch_assoc($rsProducts);
$totalRows_rsProducts = mysql_num_rows($rsProducts);

mysql_select_db($database_piano, $piano);
$query_rsBrands = "SELECT * FROM brand";
$rsBrands = mysql_query($query_rsBrands, $piano) or die(mysql_error());
$row_rsBrands = mysql_fetch_assoc($rsBrands);
$totalRows_rsBrands = mysql_num_rows($rsBrands);

$colname_rsCustomer = "12";
if (isset($_SESSION['CartID'])) {
  $colname_rsCustomer = $_SESSION['CartID'];
}
mysql_select_db($database_piano, $piano);
$query_rsCustomer = sprintf("SELECT * FROM customer WHERE CustID = %s", GetSQLValueString($colname_rsCustomer, "int"));
$rsCustomer = mysql_query($query_rsCustomer, $piano) or die(mysql_error());
$row_rsCustomer = mysql_fetch_assoc($rsCustomer);
$totalRows_rsCustomer = mysql_num_rows($rsCustomer);

mysql_select_db($database_piano, $piano);
$query_rsCartItem = "SELECT * FROM cartitem";
$rsCartItem = mysql_query($query_rsCartItem, $piano) or die(mysql_error());
$row_rsCartItem = mysql_fetch_assoc($rsCartItem);
$totalRows_rsCartItem = mysql_num_rows($rsCartItem);

$colname_rsBill = "31";
if (isset($_GET['PaymentID'])) {
  $colname_rsBill = $_GET['PaymentID'];
}
mysql_select_db($database_piano, $piano);
$query_rsBill = sprintf("SELECT PaymentID, PaymentType, AMOUNT, customer.FirstName, customer.LastName, products.ProductName, customer.Address, customer.City, customer.`State`, customer.ZIP, customer.EmaiID, customer.Phone FROM payment, cart, customer, cartitem, products WHERE PaymentID = %s and   payment.CartID=cart.CartID and cart.CustID=customer.CustID and cart.CartItemID=cartitem.CartItemID and cartitem.ProductID = products.ProductID", GetSQLValueString($colname_rsBill, "int"));
$rsBill = mysql_query($query_rsBill, $piano) or die(mysql_error());
$row_rsBill = mysql_fetch_assoc($rsBill);
$totalRows_rsBill = mysql_num_rows($rsBill);

$colname_rsBachau = "29";
if (isset($_GET['PaymentID'])) {
  $colname_rsBachau = $_GET['PaymentID'];
}
mysql_select_db($database_piano, $piano);
$query_rsBachau = sprintf("SELECT PaymentID, PaymentType, AMOUNT, customer.FirstName, customer.LastName, products.ProductName, customer.Address, customer.City, customer.`State`, customer.ZIP, customer.EmaiID, customer.Phone FROM payment, cart, customer, cartitem, products WHERE PaymentID = %s and   payment.CartID=cart.CartID and cart.CustID=customer.CustID and cart.CartItemID=cartitem.CartItemID and cartitem.ProductID = products.ProductID", GetSQLValueString($colname_rsBachau, "int"));
$rsBachau = mysql_query($query_rsBachau, $piano) or die(mysql_error());
$row_rsBachau = mysql_fetch_assoc($rsBachau);
$totalRows_rsBachau = mysql_num_rows($rsBachau);
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

<title>Piano Street - Confirmation</title>

<body onload="MM_preloadImages('../assets/images/navigation/ourproducts-over.gif','../assets/images/navigation/abouttea-over.gif','../assets/images/navigation/brewingtea-over.gif','../assets/images/navigation/aboutus-over.gif')">
<table width="700" align="center" id="tableLayout">
  <tr>
   <?php require_once('../includes/navigation.php'); ?>

  </tr>
  <tr>
    <td align="left" id="tdContent" >
      <p>&nbsp;</p><h1  align="center"> The Piano Street</h1>
      <h2>Payment</h2>
      <p>&nbsp;</p>
      <table border="1">
        <tr>
          <td>PaymentType</td>
          <td>AMOUNT</td>
          <td>FirstName</td>
          <td>LastName</td>
          <td>ProductName</td>
          </tr>
        <?php do { ?>
          <tr>
            <td><?php echo $row_rsBill['PaymentType']; ?></td>
            <td><?php echo $row_rsBill['AMOUNT']; ?></td>
            <td><?php echo $row_rsBill['FirstName']; ?></td>
            <td><?php echo $row_rsBill['LastName']; ?></td>
            <td><?php echo $row_rsBill['ProductName']; ?></td>
          </tr>
          <?php } while ($row_rsBill = mysql_fetch_assoc($rsBill)); ?>
      </table>
      <p>&nbsp;</p>
      <table border="1">
        <tr>
          <td>Address</td>
          <td>City</td>
          <td>State</td>
          <td>ZIP</td>
          <td>EmaiID</td>
          <td>Phone</td>
        </tr>
        <?php do { ?>
          <tr>
            <td><?php echo $row_rsBachau['Address']; ?></td>
            <td><?php echo $row_rsBachau['City']; ?></td>
            <td><?php echo $row_rsBachau['State']; ?></td>
            <td><?php echo $row_rsBachau['ZIP']; ?></td>
            <td><?php echo $row_rsBachau['EmaiID']; ?></td>
            <td><?php echo $row_rsBachau['Phone']; ?></td>
          </tr>
          <?php } while ($row_rsBachau = mysql_fetch_assoc($rsBachau)); ?>
    </table>
<p>&nbsp;</p>
<blockquote>
  <p>Click the button below to confirm your payment is done for this product and then logout otherwise we will not get  valid confirmation        </p>
  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
    <table align="center">
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">&nbsp;</td>
              <td><input type="submit" value="Check Out" /></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right"><input type="hidden" name="Status" value="1" size="32" /></td>
              <td>&nbsp;</td>
            </tr>
          </table>
          <input type="hidden" name="ProductID" value="<?php echo $row_rsProducts['ProductID']; ?>" />
          <input type="hidden" name="BrandID" value="<?php echo htmlentities($row_rsProducts['BrandID'], ENT_COMPAT, ''); ?>" />
          <input type="hidden" name="TypeID" value="<?php echo htmlentities($row_rsProducts['TypeID'], ENT_COMPAT, ''); ?>" />
          <input type="hidden" name="ProductName" value="<?php echo htmlentities($row_rsProducts['ProductName'], ENT_COMPAT, ''); ?>" />
          <input type="hidden" name="Summary" value="<?php echo htmlentities($row_rsProducts['Summary'], ENT_COMPAT, ''); ?>" />
          <input type="hidden" name="ProductDesc" value="<?php echo htmlentities($row_rsProducts['ProductDesc'], ENT_COMPAT, ''); ?>" />
          <input type="hidden" name="IMAGE" value="<?php echo htmlentities($row_rsProducts['IMAGE'], ENT_COMPAT, ''); ?>" />
          <input type="hidden" name="ProductModal" value="<?php echo htmlentities($row_rsProducts['ProductModal'], ENT_COMPAT, ''); ?>" />
          <input type="hidden" name="ProductPrice" value="<?php echo htmlentities($row_rsProducts['ProductPrice'], ENT_COMPAT, ''); ?>" />
          <input type="hidden" name="MM_update" value="form1" />
          <input type="hidden" name="ProductID" value="<?php echo $row_rsProducts['ProductID']; ?>" />
      </form>
        <p>&nbsp;</p>
<p>&nbsp;</p>
</blockquote>
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
      <p><a href="../ourproducts/logout.php"><img src="../assets/images/navigation/bullet.gif" alt="" width="12" height="7" />Logout</a></p>
      <p>&nbsp;</p>
      <p>&nbsp;</p></td>
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

mysql_free_result($rsBill);

mysql_free_result($rsBachau);
?>
