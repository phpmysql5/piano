
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO products (BrandID, TypeID, ProductName, Summary, ProductDesc, IMAGE, ProductModal, ProductPrice) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['BrandID'], "int"),
                       GetSQLValueString($_POST['TypeID'], "int"),
                       GetSQLValueString($_POST['ProductName'], "text"),
                       GetSQLValueString($_POST['Summary'], "text"),
                       GetSQLValueString($_POST['ProductDesc'], "text"),
                       GetSQLValueString($_POST['IMAGE'], "text"),
                       GetSQLValueString($_POST['ProductModal'], "text"),
                       GetSQLValueString($_POST['ProductPrice'], "int"));

  mysql_select_db($database_piano, $piano);
  $Result1 = mysql_query($insertSQL, $piano) or die(mysql_error());

  $insertGoTo = "../Pianos/index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_piano, $piano);
$query_rsProducts = "SELECT ProductName,brand.BrandName, ProductModal, ProductPrice, products.ProductID, pianotype.TypeName FROM products, brand, pianotype WHERE brand.BrandID = products.BrandID and pianotype.TypeID=products.TypeID";
$rsProducts = mysql_query($query_rsProducts, $piano) or die(mysql_error());
$row_rsProducts = mysql_fetch_assoc($rsProducts);
$totalRows_rsProducts = mysql_num_rows($rsProducts);

mysql_select_db($database_piano, $piano);
$query_rsBrand = "SELECT * FROM brand";
$rsBrand = mysql_query($query_rsBrand, $piano) or die(mysql_error());
$row_rsBrand = mysql_fetch_assoc($rsBrand);
$totalRows_rsBrand = mysql_num_rows($rsBrand);

mysql_select_db($database_piano, $piano);
$query_rsPianoType = "SELECT * FROM pianotype";
$rsPianoType = mysql_query($query_rsPianoType, $piano) or die(mysql_error());
$row_rsPianoType = mysql_fetch_assoc($rsPianoType);
$totalRows_rsPianoType = mysql_num_rows($rsPianoType);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/Admin Layout.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Piano Street - Admin-Add Product</title>
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
     
      <p>Admin :<!-- InstanceBeginEditable name="Section Name" --> Add Product<!-- InstanceEndEditable --></p>
      <!-- InstanceBeginEditable name="Body Content" -->
      <p>Complete the form below to add a piano product</p>
      <p>&nbsp;</p>
      <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" onsubmit="MM_validateForm('ProductName','','R','IMAGE','','R','ProductModal','','R','ProductPrice','','RinRange1100:44500','Summary','','R','ProductDesc','','R');return document.MM_returnValue">
        <table align="center">
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">BrandID:</td>
            <td><select name="BrandID">
              <?php 
do {  
?>
              <option value="<?php echo $row_rsBrand['BrandID']?>" <?php if (!(strcmp($row_rsBrand['BrandID'], 1))) {echo "SELECTED";} ?>><?php echo $row_rsBrand['BrandName']?></option>
              <?php
} while ($row_rsBrand = mysql_fetch_assoc($rsBrand));
?>
            </select></td>
          </tr>
          <tr> </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">TypeID:</td>
            <td><select name="TypeID">
              <?php 
do {  
?>
              <option value="<?php echo $row_rsPianoType['TypeID']?>" <?php if (!(strcmp($row_rsPianoType['TypeID'], 1))) {echo "SELECTED";} ?>><?php echo $row_rsPianoType['TypeName']?></option>
              <?php
} while ($row_rsPianoType = mysql_fetch_assoc($rsPianoType));
?>
            </select></td>
          </tr>
          <tr> </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">Product Name:</td>
            <td><input name="ProductName" type="text" required="required" id="ProductName" pattern="[a-zA-Z0-9\s]{3,30}" value="" size="32" maxlength="13" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right" valign="top">Summary:</td>
            <td><textarea name="Summary" cols="32" rows="5" required="required" id="Summary"></textarea></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right" valign="top">ProductDesc:</td>
            <td><textarea name="ProductDesc" cols="32" rows="5" required="required" id="ProductDesc"></textarea></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">IMAGE:</td>
            <td><input name="IMAGE" type="text" required="required" id="IMAGE" pattern="[a-zA-Z0-9\s]*[/.]([a-z]+)" value="" size="32" maxlength="10" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">Series:</td>
            <td><input name="ProductModal" type="text" id="ProductModal" pattern="[a-zA-Z0-9\s]{3,10}" value="" size="32" maxlength="10" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">Price:</td>
            <td><input name="ProductPrice" type="text" id="ProductPrice" value="" size="32" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">&nbsp;</td>
            <td><input type="submit" value="Insert record" /></td>
          </tr>
        </table>
        <input type="hidden" name="MM_insert" value="form1" />
      </form>
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

mysql_free_result($rsProducts);

mysql_free_result($rsBrand);

mysql_free_result($rsPianoType);
?>
