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
require("core/config.php");
require("core/steamapiv2.class.php");
require("core/steamlogin.php");

function GetSteamNorm($Steam64){
	$authserver = bcsub( $Steam64, '76561197960265728' ) & 1;
	//Get the third number of the steamid
	$authid = ( bcsub( $Steam64, '76561197960265728' ) - $authserver ) / 2;
	//Concatenate the STEAM_ prefix and the first number, which is always 0, as well as colons with the other two numbers
	$steamid = "STEAM_0:$authserver:$authid";
	return $steamid;
}
global $paypalurl;
global $paypalemail;
$paypalurl = $config["paypal"]["Sandbox API Link"];
$paypalemail;
if($config["paypal"]["Sandbox Mode"]){
	$paypalurl = $config["paypal"]["Sandbox API Link"];
	$paypalemail = $config["paypal"]["Sandbox Email"];
}else{
	$paypalurl = $config["paypal"]["API Link"];
	$paypalemail = $config["paypal"]["Email"];
}
$isBot = false;
$op = strtolower($_SERVER['HTTP_X_OPERAMINI_PHONE']);
$ua = strtolower($_SERVER['HTTP_USER_AGENT']);
$ac = strtolower($_SERVER['HTTP_ACCEPT']);
$ip = $_SERVER['REMOTE_ADDR'];
        $isBot =  $ip == '66.249.65.39' 
        || strpos($ua, 'googlebot') !== false 
        || strpos($ua, 'mediapartners') !== false 
        || strpos($ua, 'yahooysmcm') !== false 
        || strpos($ua, 'baiduspider') !== false
        || strpos($ua, 'msnbot') !== false
        || strpos($ua, 'slurp') !== false
        || strpos($ua, 'ask') !== false
        || strpos($ua, 'teoma') !== false
        || strpos($ua, 'spider') !== false 
        || strpos($ua, 'heritrix') !== false 
        || strpos($ua, 'attentio') !== false 
        || strpos($ua, 'twiceler') !== false 
        || strpos($ua, 'irlbot') !== false 
        || strpos($ua, 'fast crawler') !== false                        
        || strpos($ua, 'fastmobilecrawl') !== false 
        || strpos($ua, 'jumpbot') !== false
        || strpos($ua, 'googlebot-mobile') !== false
        || strpos($ua, 'yahooseeker') !== false
        || strpos($ua, 'motionbot') !== false
        || strpos($ua, 'mediobot') !== false
        || strpos($ua, 'chtml generic') !== false
        || strpos($ua, 'nokia6230i/. fast crawler') !== false;
?>
<html>
	<head>
		<title>Test</title>
	</head>
	<body>
		<div style="margin-top:3px;">
			<p>
				<div class="donationform">
					<form name="_xclick" action="<?php echo $paypalurl; ?>" method="post"> 
					
						<input name="cmd" value="_xclick" type="hidden" /> 
						<input name="business" value="<?php echo $paypalemail;?>" type="hidden" />
						<input name="item_name" value="<?php echo $config["paypal"]["Community Name"]; ?> - Game Server Donation" type="hidden" /> 
						<input name="no_shipping" value="1" type="hidden" />
						<input name="return" value="<?php echo $config["paypal"]["Return URL"]; ?>" type="hidden" />
						<input type="hidden" name="rm" value="2" /> 
						<input type="hidden" name="notify_url"value="<?php echo $config["paypal"]["IPN"]; ?>" />
						<input name="cn" value="Comments" type="hidden" /> 
						<input name="currency_code" value="<?php echo $config["paypal"]["Currency"]; ?>" type="hidden" />
						<input name="tax" value="0" type="hidden" /> 
						<input name="lc" value="GB" type="hidden" />					
						<p>
							Packages
						</p>
						<?php
						foreach($config["packages"]["Package"] as $package => $val)
						{
							$i++;
							if($i == 1)
							{
								echo "<input type=\"radio\" id=\"cost".$i."\" name=\"amount\" value=\"".$val["Price"]."\" checked>".$val["Name"]." ($".$val["Price"]." ".$val["Currency"].")<br>";
							} 
							else 
							{
								echo "<input type=\"radio\" id=\"cost".$i."\" name=\"amount\" value=\"".$val["Price"]."\">".$val["Name"]." ($".$val["Price"]." ".$val["Currency"].")<br>";
							}
						}
											
						$steam_login_verify = SteamSignIn::validate();
						if(!empty($steam_login_verify))
						{
							$steam64 = $steam_login_verify;								
							$steam = new SteamAPI($steam_login_verify);								
							$steamID = GetSteamNorm($steam_login_verify); //Get normal steamID		
							$friendlyName = $steam->getFriendlyName();  //Get players ingame name.	
									
							echo "<a href=\"".$steam_sign_in_url."\"><img src=\"http://cdn.steamcommunity.com/public/images/signinthroughsteam/sits_small.png\" /></a>";
							echo "<p> Successfully grabbed your details!</p>";
							echo "<input type=\"hidden\" name=\"on2\" value=\"Email Address\" maxlength=\"200\">Email Address:";
							echo "<input type=\"text\" id=\"emaildonate\" name=\"os2\" value=\"\"><br>";
							echo "<input type=\"hidden\" name=\"on0\" value=\"In-Game Name\" maxlength=\"200\">In-Game Name:"; // The player who donated\"s name, for your reference
							echo "<input type=\"text\" id=\"namedonate\"  name=\"os0\" value=\"".$friendlyName."\" readonly><br>"; //leave the name as "os0" players name is sent to paypal and used in the ipn script -->
							echo "<input type=\"hidden\" name=\"on1\" value=\"SteamID\" maxlength=\"200\">(STEAM_x:x:xxxxxxxx) SteamID: "; //The Players steamID, a correct ID is needed to apply the rank to the right person-->
							echo "<input type=\"text\" id=\"siddonate\"  name=\"os1\" value=\"".$steamID."\" readonly><br>"; // Leave the name as "os1" this is also sent to paypal and used in the ipn script. -->								
						}
						else
						{
							$steam_sign_in_url = SteamSignIn::genUrl();
							echo "<a href=\"".$steam_sign_in_url."\"><img src=\"http://cdn.steamcommunity.com/public/images/signinthroughsteam/sits_small.png\" /></a>";
							echo "<p> Sign in through Steam to automatically fill in your details.</p>";
							echo "<input type=\"hidden\" name=\"on2\" value=\"Email Address\" maxlength=\"200\">Email Address:";
							echo "<input type=\"text\" id=\"emaildonate\" name=\"os2\" value=\"\"><br>";
							echo "<input type=\"hidden\" name=\"on0\" value=\"In-Game Name\" maxlength=\"200\">In-Game Name:"; // The player who donated\"s name, for your reference
							echo "<input type=\"text\" id=\"namedonate\"  name=\"os0\" value=\"\"><br>"; //leave the name as "os0" players name is sent to paypal and used in the ipn script -->
							echo "<font color=\"#ff0000\">*</font><input type=\"hidden\" name=\"on1\" value=\"SteamID\" maxlength=\"200\"  >(STEAM_x:x:xxxxxxxx) SteamID: "; //The Players steamID, a correct ID is needed to apply the rank to the right person-->
							echo "<input type=\"text\" id=\"siddonate\"  name=\"os1\" value=\"\"><br>"; // Leave the name as "os1" this is also sent to paypal and used in the ipn script. -->	
						}
						?>
						<input type="image" src="./images/paypal-donate.gif" border="0" name="submit" id="submit" alt="PayPal - The safer, easier way to pay online!"><img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" /><br>
						<input type="submit" name="submit" id="submit" />
					</form>
				</div>
			</p>
		</div>
	</body>
</html>