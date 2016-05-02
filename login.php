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

if (isset($_POST['username'])) {
  $loginUsername=$_POST['username'];
  $password=$_POST['password'];
  $MM_fldUserAuthorization = "Level";
  $MM_redirectLoginSuccess = "../admin/index.php";
  $MM_redirectLoginFailed = "../admin/loginfailed.php";
  $MM_redirecttoReferrer = true;
  mysql_select_db($database_piano, $piano);
  	
  $LoginRS__query=sprintf("SELECT Username, Password, Level FROM `admin` WHERE Username=%s AND Password=%s",
  GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $piano) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
    
    $loginStrGroup  = mysql_result($LoginRS,0,'Level');
    
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
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/Admin Layout.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Piano Street - Admin-Login</title>
<!-- InstanceEndEditable -->
<link href="/assets/css/admin.css" rel="stylesheet" type="text/css" />
<?php require_once('includes/head.php'); ?>




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
<?php require_once('includes/navigation.php'); ?>

  <tr>
    <td id="tdContent" bgcolor="#FFFFCC"><h1 align="center"> The Piano Street</h1>
     
      <p>Admin :<!-- InstanceBeginEditable name="Section Name" --> Login <!-- InstanceEndEditable --></p>
      <!-- InstanceBeginEditable name="Body Content" -->
      <p>&nbsp;</p>
      <form action="<?php echo $loginFormAction; ?>" method="POST" name="form1" id="form1" onsubmit="MM_validateForm('username','','R','password','','R');return document.MM_returnValue">
        <p>&nbsp;</p>
        <table width="244">
          <tr>
            <td width="83">Username</td>
            <td width="313"><input type="text" name="username" id="username" /></td>
          </tr>
          <tr>
            <td><label for="password">Password</label></td>
            <td><input type="text" name="password" id="password" /></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><input type="submit" name="button" id="button" value="Login" /></td>
          </tr>
        </table>
        <p>&nbsp;</p>
      </form>
      <p>&nbsp;</p>
      <!-- InstanceEndEditable --></td>
    <td id="tdSidebar"><p><a href="PianoType/index.php"><img src="../assets/images/navigation/bullet.gif" alt="" width="12" height="7" />Piano Types</a></p>
      <p><a href="Pianos/index.php"><img src="../assets/images/navigation/bullet.gif" alt="" width="12" height="7" />Products </a></p>
      <p><a href="brand/index.php"><img src="../assets/images/navigation/bullet.gif" alt="" width="12" height="7" />Brands</a></p>
      <p><a href="Users/index.php"><img src="../assets/images/navigation/bullet.gif" alt="" width="12" height="7" />Users</a></p>
      <p><a href="Ad/index.php"><img src="../assets/images/navigation/bullet.gif" alt="" width="12" height="7" />Admin </a></p>
      <p><a href="Account/Accounts.php"><img src="../assets/images/navigation/bullet.gif" alt="" width="12" height="7" />Daily Accounts</a></p>
    <p><a href="logout.php"><img src="../assets/images/navigation/bullet.gif" alt="" width="12" height="7" />Logout</a></p></td>
  </tr>
<?php require_once('includes/footer.php'); ?>



</body>
<!-- InstanceEnd --></html>
