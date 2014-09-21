<?php  
/////////////////////////////////////////////////////////////////////////
/*  Higher Dimensions Gaming Automated Donation System for Garry's Mod 13.
    Copyright (C) 2014 Nathan Davies-Lee

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
	
	Project Version: Version 1.0
	File Version: 2014.09.15
*/
//////////////////////////////////////////////////////////////////////////////////////////////////
define('IS_INTERNAL',true);
require "paypal.class.php";
require "rcon_code.php";
require "config.php";
require "./libraries/TeamSpeak3/TeamSpeak3.php";
require "forumquery.php";
	
$p = new paypal_class;
if($config["paypal"]["Sandbox Mode"])
{
	$p->paypal_url = $config["paypal"]["Sandbox API Link"];
}
else
{
	$p->paypal_url = $config["paypal"]["API Link"];
}
function WrtiteLog($type, $line)
{
	if($config["logs"]["Is Used"])
	{
		if($type == "IPN")
		{
			//$file = $config["logs"]["Generic Logs Path"].'log.txt';
			if(!file_exists($config["logs"]["IPN Logs File"]."_".date($config["logs"]["Date Format"] = "d-m-Y").".txt")
			{
				fopen($config["logs"]["IPN Logs File"]."_".date($config["logs"]["Date Format"] = "d-m-Y").".txt", 'w');
				fclose($config["logs"]["IPN Logs File"]."_".date($config["logs"]["Date Format"] = "d-m-Y").".txt");
				file_put_contents($config["logs"]["IPN Logs File"]."_".date($config["logs"]["Date Format"] = "d-m-Y").".txt", date($config["logs"]["Time Format"]).": Log file created!");
			}
			$old = file_get_contents($config["logs"]["IPN Logs File"]."_".date($config["logs"]["Date Format"] = "d-m-Y").".txt");
			$new = $old."\n".$line;
			file_put_contents($config["logs"]["IPN Logs File"]."_".date($config["logs"]["Date Format"] = "d-m-Y").".txt", $new);
		}
		if($type == "GEN")
		{
			if(!file_exists($config["logs"]["Generic Logs File"]."_".date($config["logs"]["Date Format"] = "d-m-Y").".txt")
			{
				fopen($config["logs"]["Generic Logs File"]."_".date($config["logs"]["Date Format"] = "d-m-Y").".txt", 'w');
				fclose($config["logs"]["Generic Logs File"]."_".date($config["logs"]["Date Format"] = "d-m-Y").".txt");
				file_put_contents($config["logs"]["Generic Logs File"]."_".date($config["logs"]["Date Format"] = "d-m-Y").".txt", date($config["logs"]["Time Format"]).": Log file created!");
			}
			$old = file_get_contents($config["logs"]["Generic Logs File"]."_".date($config["logs"]["Date Format"] = "d-m-Y").".txt");
			$new = $old."\n".$line;
			file_put_contents($config["logs"]["Generic Logs File"]."_".date($config["logs"]["Date Format"] = "d-m-Y").".txt", $new);
		}
	}
}
//Database stuff
if($config["database"]["Is Used"])
{	 
	$db=mysqli_connect($config["database"]["Host"],$config["database"]["Username"],$config["database"]["Password"],$config["database"]["Database Name"]);
	$current = "Connected to database at ".$config["database"]["Host"]." using password ";
	if($config["database"]["Password"])
	{
		$current .= "YES";
	}
	else
	{
		$current .= "NO";
	}
	WriteLog("GEN", $current);
	// Check connection
	if (mysqli_connect_errno($db))
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		WriteLog("GEN", "Failed to connect to database: ".mysqli_connect_error());
	} 
	$config["database"]["Table"] = mysqli_real_escape_string($db, $config["database"]["Table"]);
	$result = mysqli_query($db,"SHOW TABLES LIKE '".$config["database"]["Table"]."'");
	if (!$result)
	{
		WriteLog("GEN", 'Error: '.mysqli_error($db).'. Please correct or disable your database configuration.');
		die("A fatal error has occured. Please refer to ".$config["logs"]["Generic Logs File"]."_".date($config["logs"]["Date Format"] = "d-m-Y").".txt"." for more information.");
	}
	$tableExists = mysqli_num_rows($result) > 0;
		
	if($tableExists)
	{
		//connect to the table
		WriteLog("GEN", "Table exists, Connecting to table.");
		mysqli_select_db($db, $config["database"]["Table"]);
	}
	else
	{
		//Create table
		WriteLog("GEN", "Table does not exist, creating table.");
		$sql = "CREATE TABLE ".$config["database"]["Table"]." 
		(
		PID INT NOT NULL AUTO_INCREMENT, 
		PRIMARY KEY(PID),
		email VARCHAR(250),
		steamid VARCHAR(250),
		name VARCHAR(250),
		rank VARCHAR(250),
		amount VARCHAR(250)
		)";
		mysqli_query($db,$sql);	
		mysqli_select_db($db, $config["database"]["Table"]);
	}	
}
		
if ($p->validate_ipn())
{
	WriteLog("GEN", "IPN Validated.");
	$fee = $p->ipn_data['mc_gross'];
	$email = $p->ipn_data['payer_email']; 
	$name = $p->ipn_data['option_selection1'];
	$steamid = $p->ipn_data['option_selection2'];
	$remail = $p->ipn_data['option_selection3'];
	if(!$remail or $remail == "")
	{
		$remail = $email;
	}
		
		/*if (is_array($prices)){
			foreach($prices as $key => $val){
					$i++;
					if($val == $fee){
						$rank = $ranks[$i - 1];
						$command = $commands[$i - 1] .' '. $steamid.' '.$rank;
						$forumrank = $forumranks[$i - 1];
						$ts3rank = $ts3ranks[$i - 1];
					}
			}
		} else {
			$current .='$prices is an not array.\n';
			file_put_contents($file, $current);
		}
		We know its a fucking array.
		*/
	$selectedpackage;
	$nicetry;
	if($config["packages"]["Is Used"])
	{
		foreach($config["packages"]["Package"] as $package => $val)
		{
			if($val["Price"] == $fee)
			{
				$selectpackage = $val;
				$config["packages"]["Package"][$selectedpackage]["RunCommand"] = $val["Command"].' '.$steamid.' '.$val["Rank"];
				$rank = $val["Rank"]; // I'll fucking kick myself for this.
			}
		}
		if(!$selectedpackage)
		{
			$nicetry = true;
			WriteLog("GEN", "WARNING!!! A donation that did not match known packages was detected! Promotion will not occur!");
		}
	}
	else
	{
		if($fee != $config["packages"]["No Package"]["Price"])
		{
			$nicetry = true;
			WriteLog("GEN", "WARNING!!! A donation that did not match known packages was detected! Promotion will not occur!");
		}
		else
		{
			$selectedpackage = "None";
			$config["packages"]["No Package"]["RunCommand"] = $config["packages"]["No Package"]["Command"].' '.$steamid.' '.$config["packages"]["No Package"]["Rank"];
			$rank = $config["packages"]["No Package"]["Rank"];
		}
	}			
	if(is_array($selectedpackage) && !$nicetry)
	{
		WriteLog("GEN", "Donation incoming:\nEmail: "$email.'\nName: '.$name.'\nAmmount: '.$fee .'\nSteam ID: '.$steamid.'\nRank '.$config["packages"]["Package"][$selectedpackage]["Rank"]);
	}
	elseif(!is_array($selectedpackage) && !$nicetry)
	{
		WriteLog("GEN", "Donation incoming:\nEmail: "$email.'\nName: '.$name.'\nAmmount: '.$fee .'\nSteam ID: '.$steamid.'\nRank '.$config["packages"]["No Package"]["Rank"]);
	}
		
		//Add user donation to database.
	if($config["database"]["Is Used"])
	{
		$sql
		if(is_array($selectedpackage) && !$nicetry)
		{
			$sql = 	'INSERT INTO '.$config["database"]["Table"].' VALUES (NULL , "'.mysqli_real_escape_string($db, $email).'", "'.mysqli_real_escape_string($db, $steamid).'", "'.mysqli_real_escape_string($db, $name).'", "'.mysqli_real_escape_string($db, $config["packages"]["Package"][$selectedpackage]["Rank"]).'" , "'.mysqli_real_escape_string($db, $fee).'")';
		}
		elseif(!is_array($selectedpackage) && !$nicetry)
		{
			$sql = 	'INSERT INTO '.$config["database"]["Table"].' VALUES (NULL , "'.mysqli_real_escape_string($db, $email).'", "'.mysqli_real_escape_string($db, $steamid).'", "'.mysqli_real_escape_string($db, $name).'", "'.mysqli_real_escape_string($db, $config["packages"]["No Package"]]["Rank"]).'" , "'.mysqli_real_escape_string($db, $fee).'")';
		}elseif($nicetry)
		{
			$sql = 	'INSERT INTO '.$config["database"]["Table"].' VALUES (NULL , "'.mysqli_real_escape_string($db, $email).'", "'.mysqli_real_escape_string($db, $steamid).'", "'.mysqli_real_escape_string($db, $name).'", "'.mysqli_real_escape_string($db, "INVALID DONATION").'" , "'.mysqli_real_escape_string($db, $fee).'")';
		}
		mysqli_query($db,$sql);	
		WriteLog("GEN", "Added to database.";		
	}
	if($config["rcon"]["Is Used"])
	{
		foreach ($config["rcon"]["Servers"] as &$SERVER)
		{
			if($SERVER["Active"])
			{
				$srcds_rcon = new srcds_rcon();
				if(is_array($selectedpackage) && !$nicetry)
				{
					$OUTPUT = $srcds_rcon->rcon_command($SERVER["IP"], $SERVER["Port"], $SERVER["Rcon Password"], $config["packages"]["Package"][$selectedpackage]["RunCommand"]);
					WriteLog("GEN", 'IP: '.$SERVER["IP"].' Port: '.$SERVER["Port"].' Password: HIDDEN Command: '.$command);
					if( $OUTPUT == 'Unable to connect!' || $OUTPUT == '' )
					{ 
						if($OUTPUT == "")
						{
							$OUTPUT = "No response from server (invalid command?).";
						}
						WriteLog("GEN", $OUTPUT);
						WriteLog("GEN", "Unable to connect to Rcon, please check your configuration. (".$SERVER["IP"].")");
						$SERVER["Status"] = $SERVER["Server Name"]." (".$SERVER["IP"].":".$SERVER["Port"].") - Failed: ".$OUTPUT;
						if(!$failuretopromote)
						{
							$failuretopromote = true;
						}
					}
					else
					{
						$SERVER["Status"] = $SERVER["Server Name"]." (".$SERVER["IP"].":".$SERVER["Port"].") - Success";
					}
				}
			}
		}
		if($config["forum"]["Is Used"] && !$nicetry)
		{
			$fdb = new PDO('msyql:host=$config["forum"]["Host"];dbname=$config["forum"]["Database Name"];charset=utf8', '$config["forum"]["Username"]', '$config["forum"]["Password"]');
			$query = $ForumQuery;
			$query_params = array(
				':steamid' => $steamid
			);
			$promote = $ForumUpdate;
			$promote_params = array(
				':steamid' => $steamid,
				':forumrank' => $forumrank
			);
			
			try
			{
				$stmt = $fdb->prepare($query);
				$result = $stmt->execute($query_params);
			}
			catch(PDOException $ex)
			{
				$forumstatus = "Couldn't find any forum username with ".$steamid." as their SteamID.";
				$forumfailed = true;
				if(!$failuretopromote)
				{
					$failuretopromote = true;
				}
			}
			if(!$forumfailed)
			{
				$rowq = $stmt->fetch();
				if($rowq)
				{
					$fusername = $rowq[$usernamefield];
				}
				try
				{
					$stmt2 = $fdb->prepare($promote);
					$result2 = $stmt2->execute($promote_params);
				}
				catch(PDOException $ex)
				{
					$forumstatus = "We know your username is ".$fusername." but for some reason we couldn't promote you to your rank.<br>Our coder has been notified via email and your rank will be applied within 24 hours.";
					$forumfailed = true;
					$forumfailed2 = true;
					if(!$failuretopromote)
					{
						$failuretopromote = true;
					}
				}
			}
			
			if($fusername or !$fusername == "" or !$fusername == null)
			{
				$forumstatus = "Success! User ".$fusername." was promoted to ".$rank." on the forums!";
			}
			else
			{
				$forumstatus = "Failure! No user with the SteamID ".$steamid." could be found!";
				$forumfailed = true;
			}
		}
		elseif($config["forum"]["Is Used"] && $nicetry)
		{
			$forumfailed = true;
			$forumfailed2 = true;
			$forumstatus = "Invalid donation detected! No queries were sent to the forum.";
		}
		
		
		if($failuretopromote)
		{
			$subject = $communityname.' - Donation Complete - Partial failure: '.$rank.'';  
			$messagesummary = "One or more of our servers have reported a failure to promote you to your rank.<br>Please report this to us immediately!";
		}
		else
		{
			$subject = $communityname.' - Donation Complete - Full Promotion: '.$rank.'';
			$messagesummary = "All of our servers have reported a successful promotion to your rank.";
		}
		if($forumfailed)
		{
			$forumsummary = "Our forums have failed to promote you to your proper rank.<br>This COULD be because you didn't add your SteamID to your profile.<br>Either way, we have been notified.";
		}
		else
		{
			$forumsummary = "";
		}
		if($config["teamspeak"]["Is Used"] && !$nicetry)
		{
			try
			{
				require_once("libraries/TeamSpeak3/TeamSpeak3.php");
				$ts3_VirtualServer = TeamSpeak3::factory("serverquery://".$config["teamspeak"]["Username"].":".$config["teamspeak"]["Password"]."@".$config["teamspeak"]["IP"].":".$config["teamspeak"]["Query Port"]."/?server_port=".$config["teamspeak"]["Port"]);
				if(is_array($selectedpackage)
				{
					$arr_ServerGroup = $ts3_VirtualServer->serverGroupGetByName($config["packages"]["Package"][$selectedpackage]["TS3Rank"]);
				}
				elseif($selectedpackage == "None")
				{
					$arr_ServerGroup = $ts3_VirtualServer->serverGroupGetByName($config["packages"]["No Package"]["TS3Rank"]);
				}
				$ts3_PrivilegeKey = $arr_ServerGroup->privilegeKeyCreate($name." (".$steamid.")");
				if(!$ts3_PrivilegeKey)
				{
					$ts3failed = true;
					$ts3summary = "We were unable to generate a Privilege Key for the TeamSpeak 3 server.";
				}
				else
				{
					$ts3failed = false;
					$ts3summary = "We have successfully generated a TeamSpeak 3 Privilege Key for you to use on the server.<br>This key is <strong><u><i>".$ts3_PrivilegeKey."</i></u></strong><br>Please enter this key when connected to our TeamSpeak 3 server by clicking on Permissions, then on Use Privilege Key.";
				}
			}
			catch(Exception $e)
			{
				$ts3failed = true;
				$ts3summary = "TeamSpeak 3: Error: ".$e->getMessage()."<br>We will email you a Privilege Key at ".$remail." when we have made one.";
			}
		}
		
		
		
		if($config["emails"]["Is Used"])
		{
			$to      = $remail;
			$headers = 'From: '.$config["emails"]["Sender"].' <'.$config["emails"]["Reply To"].'> '. "\r\n" .
				'Reply-To: '.$config["emails"]["Reply To"].' ' . "\r\n" .
				'X-Mailer: PHP/' . phpversion() . " \r\n" .
				'MIME-Version: 1.0' . "\r\n" .
				'Content-Type: text/html; charset=iso-8859-1' . "\r\n";
			
			include("templates/success.php");
			
			mail($to, $config["emails"]["Community Name"]." - Donation Notification", $successmessage, $headers);
			foreach($config["emails"]["Log Email"] as $logemailn)
			{
				mail($logemailn, $config["emails"]["Community Name"]." - Donation Notification", "<center>--Carbon Copy of Email sent to Donator--</center>\n\n".$message, $headers);
			}
		}
	}
	else 
	{
		if($config["emails"]["Is Used"])
		{
			$to      = $remail;	
			$subject = $config["emails"]["Community Name"].' - Donation Failed:';  
			$headers = 'From: '.$config["emails"]["Sender"].' <'.$config["emails"]["Reply To"].'> '. "\r\n" .
				'Reply-To: '.$config["emails"]["Reply To"].' ' . "\r\n" .
				'X-Mailer: PHP/' . phpversion() . " \r\n" .
				'MIME-Version: 1.0' . "\r\n" .
				'Content-Type: text/html; charset=iso-8859-1' . "\r\n"; 
	
			if(!$name)
			{
				$name = "Donator";
			}
			include("templates/failure.php");
			mail($to, $subject, $failedmessage, $headers);
			foreach($config["emails"]["Log Email"] as $logemailn)
			{
				mail($logemailn, $subject, "<center>--Carbon Copy of Email sent to Donator--</center>\n\n".$failedmessage, $headers);
			}
		}
	}
	if($config["database"]["Is Used"] == "true")
	{
		mysqli_close($db);  
	}
}
else
{
	die("IPN Not Validated"); //TODO: Email when IPN validation fails.
}
?>  