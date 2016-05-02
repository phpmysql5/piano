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

mysql_select_db($database_piano, $piano);
$query_rsCustomer = "SELECT CustID, FirstName, LastName, Address, City, `State`, ZIP, Phone, EmaiID FROM customer";
$rsCustomer = mysql_query($query_rsCustomer, $piano) or die(mysql_error());
$row_rsCustomer = mysql_fetch_assoc($rsCustomer);
$totalRows_rsCustomer = mysql_num_rows($rsCustomer);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/Admin Layout.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Piano Street  - Admin - Users Listing</title>
<!-- InstanceEndEditable -->
<link href="/assets/css/admin.css" rel="stylesheet" type="text/css" />
<?php require_once('../includes/head.php'); ?>




<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->
</head>
<body>
<?php require_once('../includes/navigation.php'); ?>

  <tr>
    <td id="tdContent" bgcolor="#FFFFCC"><h1 align="center"> The Piano Street</h1>
     
      <p>Admin :<!-- InstanceBeginEditable name="Section Name" --> Users Listing<!-- InstanceEndEditable --></p>
      <!-- InstanceBeginEditable name="Body Content" -->
      <p>The total number of users registered:<b><?php echo $totalRows_rsCustomer ?></b></p>
      <p>&nbsp;</p>
      <table border="1">
        <tr>
          <td>FirstName</td>
          <td>LastName</td>
          <td>Phone</td>
          <td>EmaiID</td>
        </tr>
        <?php do { ?>
          <tr>
            <td><?php echo $row_rsCustomer['FirstName']; ?></td>
            <td><?php echo $row_rsCustomer['LastName']; ?></td>
            <td><?php echo $row_rsCustomer['Phone']; ?></td>
            <td><?php echo $row_rsCustomer['EmaiID']; ?></td>
          </tr>
          <?php } while ($row_rsCustomer = mysql_fetch_assoc($rsCustomer)); ?>
      </table>
<p>&nbsp;</p>
      <!-- InstanceEndEditable --></td>
    <td id="tdSidebar"><p><a href="../PianoType/index.php"><img src="../../assets/images/navigation/bullet.gif" alt="" width="12" height="7" />Piano Types</a></p>
      <p><a href="../Pianos/index.php"><img src="../../assets/images/navigation/bullet.gif" alt="" width="12" height="7" />Products </a></p>
      <p><a href="../brand/index.php"><img src="../../assets/images/navigation/bullet.gif" alt="" width="12" height="7" />Brands</a></p>
      <p><a href="index.php"><img src="../../assets/images/navigation/bullet.gif" alt="" width="12" height="7" />Users</a></p>
      <p><a href="../Ad/index.php"><img src="../../assets/images/navigation/bullet.gif" alt="" width="12" height="7" />Admin </a></p>
      <p><a href="../Account/Accounts.php"><img src="../../assets/images/navigation/bullet.gif" alt="" width="12" height="7" />Daily Accounts</a></p>
    <p><a href="../logout.php"><img src="../../assets/images/navigation/bullet.gif" alt="" width="12" height="7" />Logout</a></p></td>
  </tr>
<?php require_once('../includes/footer.php'); ?>



</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($rsCustomer);
?>
