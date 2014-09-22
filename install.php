<?php
/*
|----------------------
| HDG Auto Donation System
|----------------------
Thats right, we are getting an install script. How fancy.
*/
// Credit to MyBB for teaching me with their install script.
// Step 0: Handy defines.
define('ROOT', dirname(__FILE__)."/");
define('IS_INTERNAL', 1);
define('VERSION', 1.1);
// Step 1: Have we already installed it?
$installed = false;
if(file_exists(ROOT."/core/settings.php"))
{
	require ROOT."/core/settings.php";
	if(is_array($config))
	{
		$installed = true;
	}
}

if($installed)
{
	die("Error: It appears that this system is already installed.<br><br>If you believe this to be incorrect, delete the settings file found in /core/.");
}
if(!isset($_POST["progress"])
{
	echo "Test time! I have no idea if this works. Database info GO<br>";
	echo "<form action=\"./install.php\" method=\"POST\">";
	echo "<input type=\"hidden\" name=\"progress\"	value=\"1\">";
	echo "IP: <input type=\"textbox\" name=\"ip\" ><br>";
	echo "Username: <input type=\"textbox\" name=\"user\" ><br>";
	echo "Password: <input type=\"textbox\" name=\"pass\" ><br>";
	echo "DB: <input type=\"textbox\" name=\"db\" ><br>";
}
if(isset($_POST["progress"]) && $_POST["progress"] == "1")
{
	mysql_connect($_POST["ip"],$_POST["user"],$_POST["pass"]) or die(mysql_error()."<br><a href=\"./install.php\">Click here to start again</a>");
	mysql_select_db($_POST["db"]) or die(mysql_error()."<br><a href=\"./install.php\">Click here to start again</a>");
	$packages = "CREATE TABLE IF NOT EXISTS packages
		(
		id INT NOT NULL AUTO_INCREMENT,
		PRIMARY KEY(id),
		name VARCHAR(250),
		description VARCHAR(250),
		price VARCHAR(250),
		command VARCHAR(250),
		rank VARCHAR(250)
		)";
	mysql_query($packages) or die( mysql_error() );
	$admin = "CREATE TABLE IF NOT EXISTS admin
		(
		id INT NOT NULL AUTO_INCREMENT,
		PRIMARY KEY(id),
		username VARCHAR(250),
		password VARCHAR(250),
		email VARCHAR(250)
		)";
	mysql_query($admin) or die( mysql_error() );
	$server = "CREATE TABLE IF NOT EXISTS servers
		(
		id INT NOT NULL AUTO_INCREMENT,
		PRIMARY KEY(id),
		active BOOLEAN(),
		ip VARCHAR(250),
		port VARCHAR(250),
		rcon VARCHAR(250),
		name VARCHAR(250),
		ban VARCHAR(250)
		)"
	mysql_query($server) or die( mysql_error() );
	$emails = "CREATE TABLE IF NOT EXISTS email
		(
		id INT NOT NULL AUTO_INCREMENT,
		PRIMARY KEY(id),
		email VARCHAR(250)
		)";
	mysql_query($emails) or die( mysql_error() );
	header("location:./admin/setup.php");
}
	/* TODO */
	// TODO: This. All of this. -Scorn
?>