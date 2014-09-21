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
if(isset($_GET["progress"]) && $_GET["progress"] == 1){
{
	$createquery = "CREATE TABLE packages
		(
		ID INT NOT NULL AUTO_INCREMENT
		PRIMARY KEY(PID),
		name VARCHAR(250),
		description VARCHAR(250),
		price VARCHAR(250),
		command VARCHAR(250),
		rank VARCHAR(250),
		grouping VARCHAR(250)
		)";
}
	/* TODO */
	// TODO: This. All of this. -Scorn
?>