<?php
mysql_connect('localhost', 'username', 'password') or die(mysql_error());
mysql_select_db('databasename') or die(mysql_error());

session_start();
//If your session isn't valid, it returns you to the login screen for protection
if(empty($_SESSION['user_id'])){
 header("location:index.php");
}
?>
<head>
<link href="default.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php

if (isset($_GET["x"])) {
    $x = explode(":",$_GET["x"]);

	if($x[0] == "packages")
	{
		packages();
	}
	elseif($x[0] == "home")
	{
		home();
	}
	else
	{
		home();
	}
}
else { home(); }

//Main Admin Homepage
function home()
{
  echo '<div id="fulladmin">';
  echo '<div id="adminleft">';
  //Add a function and change this line to it.
  echo '<br><center><a href="admin.php?x=home"><font color=orange>Home</font></a></center><br>';
  echo '<br><center><a href="admin.php?x=next"><font color=white>Test Page</font></a></center><br></div>';

echo '<div id="adminright"><center><h1>Administrator Control Panel</h1><br><br>';
echo 'Welcome to your control panel. Click a link on the left side to continue.<br><br>';
echo '</center></div></div>';
 }
 
 
//A Blank second page
function packages()
{
	echo '<div id="fulladmin">';
	echo '<div id="adminleft">';
	//Add a function and change this line to it.
	echo '<br><center><a href="admin.php?x=home"><font color=white>Home</font></a></center><br>';
	echo '<br><center><a href="admin.php?x=next"><font color=orange>Test Page</font></a></center><br></div>';
	// Yay I get to set up sql shit here.

echo '<div id="adminright"><center><h1>Administrator Control Panel</h1><br><br>';
echo 'This is the second page.<br><br>';
echo '</center></div></div>';
 }

?>
<div id="adminright"><center><br><br><br><br>Return to main <a href="admin.php"><font color="red">Control Panel</font></a>, or you can <a href="logout.php"><font color="red">Log Out</font></a></center></div>
</body>