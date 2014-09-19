<?php
ini_set ("display_errors", "1");
error_reporting(E_ALL);

ob_start();
session_start();
$host="localhost"; // Host name
$username="username"; // Database username
$password="password"; // Database password
$db_name="databasename"; // Database name
$tbl_name="users"; // Table name

// This will connect you to your database
mysql_connect("$host", "$username", "$password")or die("cannot connect");
mysql_select_db("$db_name")or die("cannot select DB");

// Defining your login details into variables
$myusername=$_POST['myusername'];
$mypassword=$_POST['mypassword'];
$encrypted_mypassword=md5($mypassword); //MD5 Hash for security
// MySQL injection protections
$myusername = stripslashes($myusername);
$mypassword = stripslashes($mypassword);
$myusername = mysql_real_escape_string($myusername);
$mypassword = mysql_real_escape_string($mypassword);

$sql="SELECT * FROM $tbl_name WHERE username='$myusername' and password='$encrypted_mypassword'" or die(mysql_error());
$result=mysql_query($sql) or die(mysql_error());

// Checking table row
$count=mysql_num_rows($result);
// If username and password is a match, the count will be 1

if($count==1){
// If everything checks out, you will now be forwarded to admin.php
$user = mysql_fetch_assoc($result);
 $_SESSION['user_id'] = $user['id'];
header("location:admin.php");
}
//If the username or password is wrong, you will receive this message below.
else {
echo "Wrong Username or Password<br><br>Return to <a href=\"index.php\">login</a>";
}

ob_end_flush();

?>