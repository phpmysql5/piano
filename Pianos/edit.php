<?php require_once('../../Connections/piano.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

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
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "../login.php";
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

if ((isset($_GET['ProductID'])) && ($_GET['ProductID'] != "") && (isset($_POST['Delete']))) {
  $deleteSQL = sprintf("DELETE FROM products WHERE ProductID=%s",
                       GetSQLValueString($_GET['ProductID'], "int"));

  mysql_select_db($database_piano, $piano);
  $Result1 = mysql_query($deleteSQL, $piano) or die(mysql_error());

  $deleteGoTo = "../Pianos/index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
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

  $updateGoTo = "../Pianos/index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_rsProducts = "1";
if (isset($_GET['ProductID'])) {
  $colname_rsProducts = $_GET['ProductID'];
}


$colname_rsProducts = "1";





$colname_rsProducts = "3";
if (isset($_GET['ProductID'])) {
  $colname_rsProducts = $_GET['ProductID'];
}
mysql_select_db($database_piano, $piano);
$query_rsProducts = sprintf("SELECT products.ProductID,ProductName,brand.BrandName, ProductModal , pianotype.TypeName, products.Summary, products.ProductDesc, products.IMAGE, products.ProductModal, products.ProductPrice, products.Status FROM products, brand, pianotype WHERE products.ProductID=%s AND brand.BrandID = products.BrandID and pianotype.TypeID=products.TypeID", GetSQLValueString($colname_rsProducts, "int"));
$rsProducts = mysql_query($query_rsProducts, $piano) or die(mysql_error());
$row_rsProducts = mysql_fetch_assoc($rsProducts);
$totalRows_rsProducts = mysql_num_rows($rsProducts);

$product_rsBrand = "1";
if (isset($_GET['ProductID'])) {
  $product_rsBrand = $_GET['ProductID'];
}
mysql_select_db($database_piano, $piano);
$query_rsBrand = sprintf("SELECT brand.BrandID, brand.BrandName FROM brand, products WHERE products.BrandID=brand.BrandID and products.ProductID=%s ", GetSQLValueString($product_rsBrand, "int"));
$rsBrand = mysql_query($query_rsBrand, $piano) or die(mysql_error());
$row_rsBrand = mysql_fetch_assoc($rsBrand);
$totalRows_rsBrand = mysql_num_rows($rsBrand);

$Product_rsPianoType = "4";
if (isset($_GET['ProductID'])) {
  $Product_rsPianoType = $_GET['ProductID'];
}
mysql_select_db($database_piano, $piano);
$query_rsPianoType = sprintf("SELECT  pianotype.TypeID, pianotype.TypeName FROM  products, pianotype WHERE products.ProductID=%s AND products.TypeID=pianotype.TypeID", GetSQLValueString($Product_rsPianoType, "int"));
$rsPianoType = mysql_query($query_rsPianoType, $piano) or die(mysql_error());
$row_rsPianoType = mysql_fetch_assoc($rsPianoType);
$totalRows_rsPianoType = mysql_num_rows($rsPianoType);

$Product_rsStatus = "1";
if (isset($_GET['ProductID'])) {
  $Product_rsStatus = $_GET['ProductID'];
}
mysql_select_db($database_piano, $piano);
$query_rsStatus = sprintf("SELECT products.Status, products.ProductID FROM products WHERE products.ProductID=%s", GetSQLValueString($Product_rsStatus, "int"));
$rsStatus = mysql_query($query_rsStatus, $piano) or die(mysql_error());
$row_rsStatus = mysql_fetch_assoc($rsStatus);
$totalRows_rsStatus = mysql_num_rows($rsStatus);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/Admin Layout.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Piano Street - Admin -Edit Product</title>
<!-- InstanceEndEditable -->
<link href="/assets/css/admin.css" rel="stylesheet" type="text/css" />
<?php require_once('../includes/head.php'); ?>




<!-- InstanceBeginEditable name="head" -->
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
<!-- InstanceEndEditable -->
</head>
<body>
<?php require_once('../includes/navigation.php'); ?>

  <tr>
    <td id="tdContent" bgcolor="#FFFFCC"><h1 align="center"> The Piano Street</h1>
     
      <p>Admin :<!-- InstanceBeginEditable name="Section Name" --> Edit Product<!-- InstanceEndEditable --></p>
      <!-- InstanceBeginEditable name="Body Content" -->
      <p>Edit the product by changing the details  given below</p>
      <p>&nbsp;</p>
      <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" onsubmit="MM_validateForm('ProductName','','R','IMAGE','','R','ProductModal','','R','ProductPrice','','RinRange1150:44000','Summary','','R','ProductDesc','','R');return document.MM_returnValue">
        <table align="center">
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">Brand</td>
            <td><select name="BrandID">
              <?php 
do {  
?>
              <option value="<?php echo $row_rsBrand['BrandID']?>" <?php if (!(strcmp($row_rsBrand['BrandID'], $row_rsBrand['BrandName']))) {echo "SELECTED";} ?>><?php echo $row_rsBrand['BrandName']?></option>
              <?php
} while ($row_rsBrand = mysql_fetch_assoc($rsBrand));
?>
            </select></td>
          </tr>
          <tr> </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">Type</td>
            <td><select name="TypeID">
              <?php 
do {  
?>
              <option value="<?php echo $row_rsPianoType['TypeID']?>" <?php if (!(strcmp($row_rsPianoType['TypeID'], $row_rsPianoType['TypeName']))) {echo "SELECTED";} ?>><?php echo $row_rsPianoType['TypeName']?></option>
              <?php
} while ($row_rsPianoType = mysql_fetch_assoc($rsPianoType));
?>
            </select></td>
          </tr>
          <tr> </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">ProductName:</td>
            <td><input name="ProductName" type="text" id="ProductName" pattern="[a-zA-Z0-9\s]{3,30}" value="<?php echo htmlentities($row_rsProducts['ProductName'], ENT_COMPAT, 'iso-8859-1'); ?>" size="32" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right" valign="top">Summary:</td>
            <td><textarea name="Summary" cols="50" rows="5" id="Summary"><?php echo htmlentities($row_rsProducts['Summary'], ENT_COMPAT, 'iso-8859-1'); ?></textarea></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right" valign="top">ProductDesc:</td>
            <td><textarea name="ProductDesc" cols="50" rows="5" id="ProductDesc"><?php echo htmlentities($row_rsProducts['ProductDesc'], ENT_COMPAT, 'iso-8859-1'); ?></textarea></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">IMAGE:</td>
            <td><input name="IMAGE" type="text" id="IMAGE" pattern="[a-zA-Z0-9\s]*[/.]([a-z]+)" value="<?php echo htmlentities($row_rsProducts['IMAGE'], ENT_COMPAT, 'iso-8859-1'); ?>" size="32" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">ProductModal:</td>
            <td><input name="ProductModal" type="text" id="ProductModal" pattern="[a-zA-Z0-9\s]{3,13}" value="<?php echo htmlentities($row_rsProducts['ProductModal'], ENT_COMPAT, 'iso-8859-1'); ?>" size="32" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">ProductPrice:</td>
            <td><input name="ProductPrice" type="text" id="ProductPrice" value="<?php echo htmlentities($row_rsProducts['ProductPrice'], ENT_COMPAT, 'iso-8859-1'); ?>" size="32" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">Status:</td>
            <td><select name="Status">
              <option value="0" <?php if (!(strcmp(0, htmlentities($row_rsStatus['Status'])))) {echo "SELECTED";} ?>>Ready</option>
              <option value="1" <?php if (!(strcmp(1, htmlentities($row_rsStatus['Status'])))) {echo "SELECTED";} ?>>Sold</option>
            </select></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">&nbsp;</td>
            <td><input type="submit" value="Update record" /></td>
          </tr>
        </table>
        <p>
          <input type="hidden" name="MM_update" value="form1" />
          <input type="hidden" name="ProductID" value="<?php echo $row_rsProducts['ProductID']; ?>" />
        </p>
        <p>
          <input type="submit" name="Delete" id="Delete" value="Delete" />
        </p>
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
      <!-- InstanceEndEditable --></td>
    <td id="tdSidebar"><p><a href="../PianoType/index.php"><img src="../../assets/images/navigation/bullet.gif" alt="" width="12" height="7" />Piano Types</a></p>
      <p><a href="index.php"><img src="../../assets/images/navigation/bullet.gif" alt="" width="12" height="7" />Products </a></p>
      <p><a href="../brand/index.php"><img src="../../assets/images/navigation/bullet.gif" alt="" width="12" height="7" />Brands</a></p>
      <p><a href="../Users/index.php"><img src="../../assets/images/navigation/bullet.gif" alt="" width="12" height="7" />Users</a></p>
      <p><a href="../Ad/index.php"><img src="../../assets/images/navigation/bullet.gif" alt="" width="12" height="7" />Admin </a></p>
      <p><a href="../Account/Accounts.php"><img src="../../assets/images/navigation/bullet.gif" alt="" width="12" height="7" />Daily Accounts</a></p>
    <p><a href="../logout.php"><img src="../../assets/images/navigation/bullet.gif" alt="" width="12" height="7" />Logout</a></p></td>
  </tr>
<?php require_once('../includes/footer.php'); ?>



</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($rsProducts);

mysql_free_result($rsBrand);

mysql_free_result($rsPianoType);

mysql_free_result($rsStatus);
?>
