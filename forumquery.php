<?php
//////////////////////////////////////////////
//             Forum Query file             //
//------------------------------------------//
// This file teaches the IPN how to query   //
// your web forum database. You can teach   //
// it how to query any database, as long as //
// you know how to do it. It can be tricky, //
// and requires some knowledge of PHP and   //
// MySQL, as well as how your forum works.  //
//------------------------------------------//
// Included in this file by default is      //
// query instructions for MyBB 1.6.x and    //
// SimpleMachine Forums. Use these as       //
// examples for making your own, and ensure //
// you alter the active forum config in the //
// config.php to select your query code.    //
//                                          //
// ~[HDG] Scorn™                            //
//   Developer at Higher Dimensions Gaming  //
//   August 10th 2014 @ 8:10pm              //
//////////////////////////////////////////////
// Version: Pre-Alpha 0.2                   //
//////////////////////////////////////////////
// BASIC REQUIREMENTS!                      //
//  \/ \/ \/ \/ \/ \/ \/ \/ \/ \/ \/ \/ \/  //
// 1. The end query must be saved as        //
//    $ForumQuery and it must be a global   //
//    See the MyBB instructions for example //
//                                          //
// 2. The variable that determines which    //
//    query to use is $ForumType. Use it.   //
//                                          //
// 3. The query used to set a rank must be  //
//    saved as a global and must use        //
//    :steamid to return the steam id of a  //
//    donator and :forumrank to return the  //
//    correct rank. See the MyBB            //
//    instructions for example              //
//  /\ /\ /\ /\ /\ /\ /\ /\ /\ /\ /\ /\ /\  //
// Follow these requirements and nothing    //
// will break. Thank you! ~[HDG] Scorn™     //
//////////////////////////////////////////////

//////////////////////////////////////////////
// HANDY TIPS                               //
//////////////////////////////////////////////
// When setting up your custom fields on    //
// your forum, if it supports Regex (aka    //
// Regular Expression), use the following   //
// expression to validate the 32 bit Steam2 //
// ID:                                      //
//                                          //
// STEAM_0:(0|1):\d{1,8}                    //
//////////////////////////////////////////////

// Do not edit these next lines unless you know what you are doing.
// If you aren't sure you know what you are doing, you probably don't.
if(!defined('IS_INTERNAL')) die('Direct initialization of this file is not allowed.<br /><br />Please make sure your brain cells defined.');
require 'config.php'; // We need the config.php for $ForumType AND LITERALLY NOTHING ELSE.
// Define globals.
global $usernamefield, $forumranks, $staffranks, $rankcolumn, $ForumQuery, $ForumUpdate;
// Go ahead and edit lines after here.
if($config["forum"]["Forum Type"] == "MyBB"){ // MyBB 1.6.x (Courtesy of [HDG] Scorn™)
	/* Instructions for end user:
	1. Enter your Admin CP on MyBB.
	2. Go to "Configuration" and click on "Custom Profile Fields", normally located as the third option (second clickable) on the left hand panel.
	3. Click on Add New Profile Field.
	4a. Enter anything you want into the title, but I recommend "Steam ID"
	4b. Enter anything in the short description, but I recommend "Your Steam ID. Used for automated promotions. Example: STEAM_0:1:23456789"
	4c. Select Textbox for Field Type.
	4d. Maximum Lenght should be 17. This wont need to be increased for years, until steam gets past Steam ID STEAM_0:1:99999999 (that's 9 repeated 8 times.)
	4e. Display order should be 0.
	4f. Required should be 'Yes' to ensure it is entered on registration.
	4g. Editable by user: Yes.
	4h. Do not Hide on Profile.
	4i. Leave minimum post count blank.
	5. Save this field.
	6. In the table visible to you, find the entry you just made and click on it.
	7. In the url is a value you need...
		.../admin/index.php?module=config-profile_fields&action=edit&fid=4
		At the end you can see fid=4. This means your steamidfield in the database is fid4.
	8. Include your info below.
	
	To get your forum prefix:
	1. 
	2. Go to Configuration 
	*/
	// Configurable
	$forumprefix = "mybb_"; 
	$steamidfield = "fid4";
	$usernamefield = "username";
	$forumranks = array("1","2","3"); // ID of the ranks for the group. To get this, goto your AdminCP, and goto edit the group. The number you need will appear in the url next to gid=. EG: gid=2 = registered.
	$staffranks = array("1","2","3"); // Ranks of staff. If your donator is in these ranks, they will not be promoted.
	$rankcolumn = "usergroup";
	// For forum ranks, ensure you have the same number available as prices.
	// END Configurable
	$ForumQuery = "SELECT * FROM ".$forumprefix."users LEFT JOIN ".$forumprefix."userfields ON ".$forumprefix."users.uid = ".$forumprefix."userfields.ufid WHERE ".$forumprefix."userfields.".$steamidfield." = :steamid";
	$ForumUpdate = "UPDATE ".$forumprefix."users LEFT JOIN ".$forumprefix."userfields ON mybb_users.uid = ".$forumprefix."userfields.ufid SET ".$forumprefix."users.usergroup = :forumrank WHERE ".$forumprefix."userfields.".$steamidfield." = :steamid";
}
if($config["forum"]["Forum Type"] == "SMF"){ // Simple Machine Forums (Courtesy of [HDG] Scorn™)
	// Configurable
	$forumprefix = "smf_";
	$steamidfield = "2";
	$forumranks = array("1","2","3");
	$staffranks = array("1","2","3");
	$rankcolumn = "id_group";
	// Fucking hell SMF, do you have to be so difficult with your custom fields?
	$ForumQuery = "SELECT * FROM `".$forumprefix."members` LEFT JOIN (`".$forumprefix."themes`,`".$forumprefix."custom_fields`) ON (`".$forumprefix."members`.id_member = `".$forumprefix."themes`.id_member AND `".$forumprefix."custom_fields`.id_field = '".$steamidfield."') WHERE variable = col_name AND value = :steamid";
	$ForumUpdate = "UPDATE `".$forumprefix."members` LEFT JOIN (`".$forumprefix."themes`,`".$forumprefix."custom_fields`) ON (`".$forumprefix."members`.id_member = `".$forumprefix."themes`.id_member AND `".$forumprefix."custom_fields`.id_field = '".$steamidfield."') SET `".$forumprefix."members`.id_group = :forumrank WHERE variable = col_name AND value = :steamid";
}
// For any additional forum configurations, send an email to scorn@derpymail.org and for a small fee I will set up a query for you.
?>