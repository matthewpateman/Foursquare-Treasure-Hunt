<?php

//Put in the key and secret for YOUR Foursquare app, callback URL and receive the _GET code.

include 'secret/foursquare.php';

$settoken=$_GET['code'];

//Includes the foursquare-async library files
require_once('foursquare/EpiCurl.php');
require_once('foursquare/EpiSequence.php');
require_once('foursquare/EpiFoursquare.php');

//Start session and make some magic…
session_start();
$foursquareObj = new EpiFoursquare($clientId, $clientSecret);
$token = $foursquareObj->getAccessToken($settoken, $redirectUrl);
$foursquareObj->setAccessToken($token["access_token"]);

//You should save the $token in your $_SESSION and in your database for further use, but we don’t need it right now. You will need to setAccessToken($token["access_token"]); the first time you want to make a call in a certain page.


// Get Foursquare User details
$userjson = file_get_contents('https://api.foursquare.com/v2/users/self?oauth_token='. $token["access_token"] .'&v=20140504' );//Or however you what it
$userarray = json_decode($userjson, true);

$userid = $userarray['response']['user']['id'];


$oauthtoken = $token["access_token"];
$service = 'foursquare';

$con = mysql_connect("sql1.udmedia.de","l3s7328","28414.9");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
mysql_select_db("usr_l3s7328_2", $con);
$sql="INSERT INTO oauth2 (token, userid)
VALUES
('$oauthtoken','$userid')";



if (!mysql_query($sql,$con))
  {
  die('Error: ' . mysql_error());
  }

  mysql_close($con);  
  header( 'Location: http://www.matthewpateman.com/4sq/index.php?userid=' . $userid ) ;


?>
