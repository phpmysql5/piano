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

// *** Redirect if username exists


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
	
	$MM_flag="MM_insert";
	
if (isset($_POST[$MM_flag])) {
  $MM_dupKeyRedirect="../ourproducts/userexists.php";
  $loginUsername = $_POST['Username'];
  $LoginRS__query = sprintf("SELECT Username FROM customer WHERE Username=%s", GetSQLValueString($loginUsername, "text"));
  mysql_select_db($database_piano, $piano);
  $LoginRS=mysql_query($LoginRS__query, $piano) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);

  //if there is a row in the database, the username was found - can not add the requested username
  if($loginFoundUser){
    $MM_qsChar = "?";
    //append the username to the redirect page
    if (substr_count($MM_dupKeyRedirect,"?") >=1) $MM_qsChar = "&";
    $MM_dupKeyRedirect = $MM_dupKeyRedirect . $MM_qsChar ."requsername=".$loginUsername;
    header ("Location: $MM_dupKeyRedirect");
    exit;
  }
}

  $insertSQL = sprintf("INSERT INTO customer (FirstName, LastName, Address, City, `State`, ZIP, Phone, EmaiID, Username, Password) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['FirstName'], "text"),
                       GetSQLValueString($_POST['LastName'], "text"),
                       GetSQLValueString($_POST['Address'], "text"),
                       GetSQLValueString($_POST['City'], "text"),
                       GetSQLValueString($_POST['State'], "text"),
                       GetSQLValueString($_POST['ZIP'], "int"),
                       GetSQLValueString($_POST['Phone'], "int"),
                       GetSQLValueString($_POST['EmaiID'], "text"),
                       GetSQLValueString($_POST['Username'], "text"),
                       GetSQLValueString($_POST['Password'], "text"));

  mysql_select_db($database_piano, $piano);
  $Result1 = mysql_query($insertSQL, $piano) or die(mysql_error());
}

mysql_select_db($database_piano, $piano);
$query_rsProducts = "SELECT ProductID, TypeID, ProductName, Summary, ProductDesc, IMAGE, ProductModal, ProductPrice, brand.BrandName, products.BrandID FROM products, brand WHERE brand.BrandID=products.BrandID";
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

$colname_rsCust = "Karthik";
if (isset($_SESSION['MM_Username'])) {
  $colname_rsCust = $_SESSION['MM_Username'];
}
mysql_select_db($database_piano, $piano);
$query_rsCust = sprintf("SELECT CustID FROM customer WHERE Username = %s", GetSQLValueString($colname_rsCust, "text"));
$rsCust = mysql_query($query_rsCust, $piano) or die(mysql_error());
$row_rsCust = mysql_fetch_assoc($rsCust);
$totalRows_rsCust = mysql_num_rows($rsCust);
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['Username'])) {
  $loginUsername=$_POST['Username'];
  $password=$_POST['Password'];
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "../ourproducts/cart.php";
  $MM_redirectLoginFailed = "../ourproducts/loginfailed.php";
  $MM_redirecttoReferrer = true;
  mysql_select_db($database_piano, $piano);
  
  $LoginRS__query=sprintf("SELECT Username, Password FROM customer WHERE Username=%s AND Password=%s",
    GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $piano) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && true) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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

<title>Piano Street -Customer</title>

<body onload="MM_preloadImages('../assets/images/navigation/ourproducts-over.gif','../assets/images/navigation/abouttea-over.gif','../assets/images/navigation/brewingtea-over.gif')">
<table width="700" align="center" id="tableLayout">
  <tr>
   <?php require_once('../includes/navigation.php'); ?>
	
  </tr>
  <tr>
    <td align="left" id="tdContent"><h1 align="center">The Piano Street</h1>
      <p>&nbsp;</p>
      <h2>Login form</h2>
      <form action="<?php echo $loginFormAction; ?>" method="POST" name="form1" id="form2" >
        <table>
          <tr>
            <td><label for="Password">Username</label></td>
            <td><input type="text" name="Username" id="Username" /></td>
          </tr>
          <tr>
            <td><label for="textfield3">Password</label></td>
            <td><input type="text" name="Password" id="Password" /></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><input type="submit" name="button" id="button" value="Login" /></td>
          </tr>
        </table>
      </form>
      <p>&nbsp;</p>
      <h2>Registration Form</h2>
      <p>&nbsp;</p>
      <form action="<?php echo $loginFormAction; ?>" method="post" name="form2" id="form2" onsubmit="MM_validateForm('FirstName','','R','LastName','','R','City','','R','State','','R','ZIP','','RinRange400000:490000','Phone','','RinRange7000000000:9987654321','EmaiID','','RisEmail','Address','','R');return document.MM_returnValue">
        <table align="left">
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">First Name:</td>
            <td><input name="FirstName" type="text" id="FirstName" pattern="[a-zA-Z]{6,13}" value="" size="32" maxlength="13" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">Last Name:</td>
            <td><input name="LastName" type="text" id="LastName" pattern="[a-zA-Z]{6,13}" value="" size="32" maxlength="13" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right" valign="top">Address:</td>
            <td><textarea name="Address" cols="30" rows="5" id="Address"></textarea></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">City:</td>
            <td><input name="City" type="text" id="City" pattern="[a-zA-Z]{3,20}" value="" size="32" maxlength="20" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">State:</td>
            <td><input name="State" type="text" id="State" pattern="[a-zA-Z]{3,15}" value="" size="32" maxlength="15" 15 /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">ZIP:</td>
            <td><input name="ZIP" type="text" id="ZIP" value="" size="32" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">Phone:</td>
            <td><input name="Phone" type="text" id="Phone" value="" size="32" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">EmailID:</td>
            <td><input name="EmaiID" type="text" id="EmaiID" value="" size="32"  /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">Username:</td>
            <td><input name="Username" type="text" id="Username" pattern="[a-zA-Z0-9]{5,13}" value="" size="32" maxlength="13" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">Password:</td>
            <td><input name="Password" type="text" id="Password" pattern="[a-zA-Z0-9]{5,13}" value="" size="32" maxlength="13" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">&nbsp;</td>
            <td><input type="submit" value="Insert Details" /></td>
          </tr>
        </table>
        <input type="hidden" name="MM_insert" value="form2" />
      </form>
      <p>&nbsp;</p>
<p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <h2>&nbsp;</h2>
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

mysql_free_result($rsCustomer);

mysql_free_result($rsCust);
?>
