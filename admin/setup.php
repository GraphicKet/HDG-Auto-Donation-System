<?php

mysql_connect('localhost', 'username', 'password') or die(mysql_error());
mysql_select_db('databasename') or die(mysql_error());

//Process
if (isset($_POST['submit']))
{

$myUsername = addslashes( $_POST['username'] ); //prevents types of SQL injection
$myPassword = $_POST['password'];
$myEmail = $_POST['email'];

$newpass = md5($myPassword); //This will make your password encrypted into md5, a high security hash

$sql = mysql_query( "INSERT INTO users (`id`, `username`, `password`, `email`) VALUES ('', '$myUsername','$newpass', '$myEmail')" )
        or die( mysql_error() );

die( "You have registered for an account.<br><br>Go to <a href=\"login.html\">Login</a>" );
}

echo "Register an account by filling in the needed information below.<br><br>";
echo '<form action="registeracc.php" method="post">';
echo '<table><tr><td>';
echo "<b>Username:</b></td><td><input type='text' style='background-color:#999999; font-weight:bold;' name='username' maxlength='15' value=''></td></tr>";
echo "<tr><td><b>Password:</b></td><td><input type='password' style='background-color:#999999; font-weight:bold;' name='password' maxlength='15' value=''></td></tr>";
echo "<tr><td><b>Email Address:</b></td><td><input type='text' style='background-color:#999999; font-weight:bold;' name='email' maxlength='100' value=''></td></tr></table>";
echo "<input type='submit' name='submit' value='Register Account'></form>";
?>