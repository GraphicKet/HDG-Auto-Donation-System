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
/*
This configuration file is simple to use. Simply read the variable, and it will tell you what it does.
For example:

	$config["packages"]["No Package"]["Name"] = "VIP";
	
$config is the variable array. Do not change this.
["packages"] is the catagory. These are lowercase, to indicate they are a category.
["No Package"] is a group of variables, associated with a setting. In this case, if you don't use packages, the values for No Package are used instead.
["Name"] is the individual setting. In this case, it is the name of your package, as the donator will see it.
 = "VIP"; is the part you get to change.
 
 
	$config["packages"]["Package"][0]["Name"] = "Basic VIP";
	
This is exactly the same as the above example, except you will notice one thing...
[0] is a number. This is used when you have lots of the same thing (IE: packages, servers)
Increment this by one every time.




*/
//////////////////////////////////
///Security///////////////////////
//////////////////////////////////
if(!defined('IS_INTERNAL')) die('Direct initialization of this file is not allowed.<br /><br />Please make sure your brain cells are defined.');
//////////////////////////////////
///Donation Ranks & prices////////
//////////////////////////////////
$config["packages"]["Is Used"] = true; // If false, we use just the first package in the array.
// To do: Set false to be a custom amount placed by the individual.
$config["packages"]["No Package"]["Name"] = "VIP";
$config["packages"]["No Package"]["Command"] = "say";
$config["packages"]["No Package"]["Rank"] = "vip";
$config["packages"]["No Package"]["Price"] = "15";
$config["packages"]["No Package"]["TS3Rank"] = "1";

$config["packages"]["Package"][0]["Name"] = "Basic VIP";
$config["packages"]["Package"][0]["Command"] = "say";
$config["packages"]["Package"][0]["Rank"] = "vip";
$config["packages"]["Package"][0]["Price"] = "15";
$config["packages"]["Package"][0]["TS3Rank"] = "1"; // Only used if TS3 is used.

$config["packages"]["Package"][1]["Name"] = "Moderator";
$config["packages"]["Package"][1]["Command"] = "say";
$config["packages"]["Package"][1]["Rank"] = "moderator";
$config["packages"]["Package"][1]["Price"] = "30";
$config["packages"]["Package"][1]["TS3Rank"] = "2";

$config["packages"]["Package"][2]["Name"] = "Admin";
$config["packages"]["Package"][2]["Command"] = "say";
$config["packages"]["Package"][2]["Rank"] = "admin";
$config["packages"]["Package"][2]["Price"] = "45";
$config["packages"]["Package"][2]["TS3Rank"] = "3";

//////////////////////////////////
// RCON config                  //
//////////////////////////////////
$config["rcon"]["Is Used"] = true; // Set to false if you do not use RCON.
$config["rcon"]["Servers"][0]["Active"] = true; // If false, no attempt will be made at communicating with this server.
$config["rcon"]["Servers"][0]["IP"] = "127.0.0.1"; // IP address of the server.
$config["rcon"]["Servers"][0]["Port"] = "27015"; // Port of the server. You may need to contact your host's provider and get them to open this port for you. Tell them it needs to be opened outgoing, and it's UDP.
$config["rcon"]["Servers"][0]["Rcon Password"] = "password"; // RCON password to the server. Don't worry, this is never displayed or stored anywhere else.
$config["rcon"]["Servers"][0]["Server Name"] = "My First Server"; // Server name. This is used to pretty up the email.'
$config["rcon"]["Servers"][0]["Ban Command"] = "banid"; // Incase a hacked donation is detected.
//////////////////////////////////
// Paypal config                //
//////////////////////////////////
$config["paypal"]["Sandbox Mode"] = true; 
$config["paypal"]["API Link"] = "https://www.paypal.com/cgi-bin/webscr";
$config["paypal"]["Email"] = "paypal@yourdomain.com";
$config["paypal"]["Sandbox API Link"] = "https://www.sandbox.paypal.com/cgi-bin/webscr";
$config["paypal"]["Sandbox Email"] = "testpaypal@yourdomain.com";
$config["paypal"]["Return URL"] = "http://www.yourdomain.com/Thanks/";
$config["paypal"]["IPN"] = "http://www.yourdomain.com/ipn.php";
$config["paypal"]["Currency"] = "AUD";
$config["paypal"]["Community Name"] = "Higher Dimensions Gaming";
$config["paypal"]["Terms and Conditions"] = "http://www.yourdomain.com/Terms/";

//////////////////////////////////
// Email Config                 //
//////////////////////////////////
$config["emails"]["Is Used"] = true; // Experimental feature, may not work.

$config["emails"]["Sender"] = "Sender";
$config["emails"]["Reply To"] = "replyto@yourdomain.com";
$config["emails"]["Contact Us"] = "contactus@yourdomain.com";
$config["emails"]["Community Name"] = "Your Community";
$config["emails"]["Log Email"][0] = "logs@yourdomain.com";
$config["emails"]["Log Email"][1] = "logs@anotherdomain.com";
$config["emails"]["Email Template"] = "./Emails/default.php";
$config["emails"]["Website"] = "http://yourdomain.com";
$config["emails"]["Logo"] = "http://placehold.it/200x50/";

//////////////////////////////////
// Database Config              //
//////////////////////////////////
$config["database"]["Is Used"] = true;

$config["database"]["Host"] = "127.0.0.1";
$config["database"]["Username"] = "root";
$config["database"]["Password"] = "toor"; // False if no password.
$config["database"]["Database Name"] = "donate";
$config["database"]["Table"] = "donations"; // This gets messed with by the IPN later.

//////////////////////////////////
// Forum Config                 //
//////////////////////////////////

$config["forum"]["Is Used"] = true; // Incomplete.

$config["forum"]["Forum Type"] = "MyBB"; // Defined in forumquery.php
$config["forum"]["Host"] = "127.0.0.1";
$config["forum"]["Username"] = "root";
$config["forum"]["Password"] = "toor";
$config["forum"]["Database Name"] = "forum";

//////////////////////////////////
// Teamspeak Config             //
//////////////////////////////////
$config["teamspeak"]["Is Used"] = false;
$config["teamspeak"]["Username"] = "Admin"; // Admin Username for TS3. Generally your one in TS3
$config["teamspeak"]["Password"] = "password"; // This is the serverquery password generated in TS3.
$config["teamspeak"]["IP"] = "127.0.0.1"; // IP of the server
$config["teamspeak"]["Port"] = "1234"; // Port of your server
$config["teamspeak"]["Query Port"] = "10011"; // Query port of the server.

//////////////////////////////////
// Logging Config               //
//////////////////////////////////
$config["logs"]["Is Used"] = true; // Text logs...
$config["logs"]["Generic Logs File"] = "./logs/logs"; // We handle the extension =D
$config["logs"]["IPN Logs File"] = "./logs/ipn_logs";
$config["logs"]["Date Format"] = "d-m-Y"; // Refer to http://php.net/manual/en/function.date.php for a valid entry.
$config["logs"]["Time Format"] = "g:i.s A"; // Refer to http://php.net/manual/en/function.date.php for a valid entry.

?>
