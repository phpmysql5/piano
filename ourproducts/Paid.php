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



if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
  $insertSQL = sprintf("INSERT INTO payment (CartID, CardID, PaymentType, AMOUNT) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['CartID'], "int"),
                       GetSQLValueString($_POST['CardID'], "int"),
                       GetSQLValueString($_POST['PaymentType'], "text"),
                       GetSQLValueString($_POST['AMOUNT'], "int"));

  mysql_select_db($database_piano, $piano);
  $Result1 = mysql_query($insertSQL, $piano) or die(mysql_error());
  $_SESSION['PaymentID'] =mysql_insert_id();

  $insertGoTo = "../ourproducts/final.php?PaymentID=" . $_SESSION['PaymentID'] . "";
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

mysql_select_db($database_piano, $piano);
$query_rsProducts = "SELECT ProductID, TypeID, ProductName, Summary, ProductDesc, IMAGE, ProductModal, ProductPrice, brand.BrandName, products.BrandID FROM products, brand WHERE brand.BrandID=products.BrandID";
$rsProducts = mysql_query($query_rsProducts, $piano) or die(mysql_error());
$row_rsProducts = mysql_fetch_assoc($rsProducts);
$Piano_rsProducts = "1";
if (isset($_GET['ProductID'])) {
  $Piano_rsProducts = $_GET['ProductID'];
}
mysql_select_db($database_piano, $piano);
$query_rsProducts = sprintf("SELECT ProductID, TypeID, ProductName, Summary, ProductDesc, IMAGE, ProductModal, ProductPrice, brand.BrandName, products.BrandID FROM products, brand WHERE brand.BrandID=products.BrandID  AND products.ProductID=%s", GetSQLValueString($Piano_rsProducts, "int"));
$rsProducts = mysql_query($query_rsProducts, $piano) or die(mysql_error());
$row_rsProducts = mysql_fetch_assoc($rsProducts);
$totalRows_rsProducts = mysql_num_rows($rsProducts);

mysql_select_db($database_piano, $piano);
$query_rsBrands = "SELECT * FROM brand";
$rsBrands = mysql_query($query_rsBrands, $piano) or die(mysql_error());
$row_rsBrands = mysql_fetch_assoc($rsBrands);
$totalRows_rsBrands = mysql_num_rows($rsBrands);

mysql_select_db($database_piano, $piano);
$query_rsCustomer = "SELECT * FROM customer";
$rsCustomer = mysql_query($query_rsCustomer, $piano) or die(mysql_error());
$row_rsCustomer = mysql_fetch_assoc($rsCustomer);
$totalRows_rsCustomer = mysql_num_rows($rsCustomer);

mysql_select_db($database_piano, $piano);
$query_rsCartItem = "SELECT * FROM cartitem";
$rsCartItem = mysql_query($query_rsCartItem, $piano) or die(mysql_error());
$row_rsCartItem = mysql_fetch_assoc($rsCartItem);
$totalRows_rsCartItem = mysql_num_rows($rsCartItem);

$colname_rsCart = "-1";
if (isset($_GET['CartID'])) {
  $colname_rsCart = $_GET['CartID'];
}
mysql_select_db($database_piano, $piano);
$query_rsCart = sprintf("SELECT * FROM cart WHERE CartID = %s", GetSQLValueString($colname_rsCart, "int"));
$rsCart = mysql_query($query_rsCart, $piano) or die(mysql_error());
$row_rsCart = mysql_fetch_assoc($rsCart);
$totalRows_rsCart = mysql_num_rows($rsCart);
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

<script type="text/javascript">
function MM_validateForm() { //v4.0
  if (document.getElementById){
    var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
    for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=document.getElementById(args[i]);
      if (val) { nm=val.name; if ((val=val.value)!="") {
        if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
          if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
        } else if (test!='R') { num = parseFloat(val);
          if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
          if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
            min=test.substring(8,p); max=test.substring(p+1);
            if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
      } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
    } if (errors) alert('The following error(s) occurred:\n'+errors);
    document.MM_returnValue = (errors == '');
} }
</script>
<title>Piano Street - Payment</title>
<body onload="MM_preloadImages('../assets/images/navigation/ourproducts-over.gif','../assets/images/navigation/abouttea-over.gif','../assets/images/navigation/brewingtea-over.gif')"><table width="700" align="center" id="tableLayout">
<tr>
   <?php require_once('../includes/navigation.php'); ?>

  </tr>
  <tr>
    <td align="left" id="tdContent" ><h1>&nbsp;</h1><h1  align="center"> The Piano Street</h1>
      <p>&nbsp;</p>
      <h2>Click  insert to make a transaction</h2>
      <p>&nbsp; </p>
      <form action="<?php echo $editFormAction; ?>" method="post" name="form2" id="form2">
        <table align="center">
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">PaymentType:</td>
            <td><input name="PaymentType" type="text" id="PaymentType" value="Online" size="32" readonly="readonly" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">AMOUNT:</td>
            <td><input name="AMOUNT" type="text" id="AMOUNT" value="<?php echo $row_rsProducts['ProductPrice']; ?>" size="32" readonly="readonly" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">&nbsp;</td>
            <td><input type="submit" value="Procees to final page" /></td>
          </tr>
        </table>
        <input type="hidden" name="CartID" value="<?php echo $_SESSION['CartID']; ?>" />
        <input name="CardID" type="hidden" value="<?php echo $_SESSION['CardID']; ?>" />
        <input type="hidden" name="MM_insert" value="form2" />
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
      <h2>&nbsp;</h2>
      <p>&nbsp;</p></td>
    <td id="tdSidebar"><p>
      <?php do { ?>
        <a href="../ourproducts/products.php?BrandID=<?php echo $row_rsBrands['BrandID']; ?>" class="current"><?php echo $row_rsBrands['BrandName']; ?></a>
        <?php } while ($row_rsBrands = mysql_fetch_assoc($rsBrands)); ?>
    </p>
      <p><a href="../ourproducts/logout.php">Logout</a></p></td>
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

mysql_free_result($rsCart);
?>
