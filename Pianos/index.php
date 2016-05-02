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

$maxRows_rsProducts = 10;
$pageNum_rsProducts = 0;
if (isset($_GET['pageNum_rsProducts'])) {
  $pageNum_rsProducts = $_GET['pageNum_rsProducts'];
}
$startRow_rsProducts = $pageNum_rsProducts * $maxRows_rsProducts;

mysql_select_db($database_piano, $piano);
$query_rsProducts = "SELECT products.ProductID, ProductName ,brand.BrandName, pianotype.TypeName, ProductModal, ProductPrice, products.Status FROM products, brand, pianotype WHERE brand.BrandID = products.BrandID and pianotype.TypeID=products.TypeID";
$query_limit_rsProducts = sprintf("%s LIMIT %d, %d", $query_rsProducts, $startRow_rsProducts, $maxRows_rsProducts);
$rsProducts = mysql_query($query_limit_rsProducts, $piano) or die(mysql_error());
$row_rsProducts = mysql_fetch_assoc($rsProducts);

if (isset($_GET['totalRows_rsProducts'])) {
  $totalRows_rsProducts = $_GET['totalRows_rsProducts'];
} else {
  $all_rsProducts = mysql_query($query_rsProducts);
  $totalRows_rsProducts = mysql_num_rows($all_rsProducts);
}
$totalPages_rsProducts = ceil($totalRows_rsProducts/$maxRows_rsProducts)-1;

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
<title>Piano Street - Admin- PRODUCTS</title>
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
     
      <p>Admin :<!-- InstanceBeginEditable name="Section Name" --> Products Listing<!-- InstanceEndEditable --></p>
      <!-- InstanceBeginEditable name="Body Content" -->
      <p>&nbsp;</p>
      <p><a href="../Pianos/add.php">Add Products</a></p>
      <form id="form1" name="form1" method="post" action="">
        <p>&nbsp;</p>
        <table border="1">
          <tr>
            <td>ProductName</td>
            <td>BrandName</td>
            <td>TypeName</td>
            <td>ProductModal</td>
            <td>ProductPrice</td>
            <td>Status</td>
          </tr>
          <?php do { ?>
            <tr>
              <td><a href="../Pianos/edit.php?ProductID=<?php echo $row_rsProducts['ProductID']; ?>"><?php echo $row_rsProducts['ProductName']; ?></a></td>
              <td><?php echo $row_rsProducts['BrandName']; ?></td>
              <td><?php echo $row_rsProducts['TypeName']; ?></td>
              <td><?php echo $row_rsProducts['ProductModal']; ?></td>
              <td><?php echo $row_rsProducts['ProductPrice']; ?></td>
              <td><?php if($row_rsProducts['Status']==1)
			  {
				  echo "Sold";}
				  else
				  {
					  echo "Ready";
				  }?></td>
            </tr>
            <?php } while ($row_rsProducts = mysql_fetch_assoc($rsProducts)); ?>
        </table>
      </form>
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
?>
