<?php

include 'secret/database.php';
$cxn = mysqli_connect($db_server, $db_user, $db_password, $db_database);

// Query Merchant List
$query2 = "SELECT * FROM merchants";

$result2 = mysqli_query($cxn, $query2);

// Create Array
$merchantlist = array();

// Add each merchant to the array.
while($row = mysqli_fetch_assoc($result2))
{
	extract($row);
	
	
	$merchantlist[] = $merchantid;
	
	
}
 
// Foursquare

$userid = $_GET["userid"];

$service = "foursquare";
$query = "SELECT * FROM oauth2 where userid = '$userid'";

$result = mysqli_query($cxn, $query);

if ($result == false)
	{
		echo "Error";
	}
elseif(@mysqli_num_rows($result) == 0)
	{
		$keyactive = 'no';
	}	
else 
	{
	
	$row = mysqli_fetch_row($result); 
		
	//****************************
	// Check to see if the user has removed Token Access using Curl
	
	$url =  'https://api.foursquare.com/v2/users/'.$userid.'/badges?oauth_token='. $row[1];
	$handle = curl_init($url);
	curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);

	/* Get the HTML or whatever is linked in $url. */
	$response = curl_exec($handle);

	/* Check for 401 (file not found). */
	$httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
	if($httpCode == 401)
		{
			$keyactive = 'no';
			$service = 'foursquare';	
			$query = "DELETE FROM oauth2";
			$result = mysqli_query($cxn, $query);
		}
	else
		{ 
			$keyactive = 'yes';	
		}
	
	curl_close($handle);
	}


$oauthlink = $keyactive;



// check if the user has linked their foursquare account
if ($oauthlink != "yes")
{
	
	include 'secret/foursquare.php';

	require_once('foursquare/EpiCurl.php');
	require_once('foursquare/EpiSequence.php');
	require_once('foursquare/EpiFoursquare.php');

	//And some code!
	session_start();
	try
		{
			$login = 'yes';
			include 'header.php';
							
			$foursquareObj = new EpiFoursquare($clientId, $clientSecret);
			$results = $foursquareObj->getAuthorizeUrl($redirectUrl);
			
			?>
            <p>Log in to take part. Visit as many participating venues.<br/>
			<a href="<?php echo $results; ?>"><span class="connect4sq"></span></a>
			</p>
		<?php
        } 
		catch (Execption $e)
		{
		//If there is a problem throw an exception
		}
	}
	else
 
	{

// get the users last 250 check-ins	
	$checkinjson = file_get_contents('https://api.foursquare.com/v2/users/'.$userid.'/checkins?oauth_token='. $row[1] .'&limit=250' . '&v=20140504');
	$checkarray = json_decode($checkinjson,true);

	$login = 'no';
			include 'header.php';
			
	?>
    
    
    <p>Take part and visit participating shops in your local area and unlock specials.</p>
    
    <?php
	echo "<h2>London Location</h2>";
	
	
	$checkin_arr = array();

	
// add users check-ins to array.
   			foreach ($checkarray['response']['checkins']['items'] as $value) 
				{
// checking to see if a venue ID is in the array and if not adding the new venue to the checkins array					
					if (in_array($value['venue']['id'], $checkin_arr)) {}
					else  {	$checkin_arr[] = $value['venue']['id']; }
									
				}
		


// checking to see if the selected venues are in the users checkins array	


echo "<ul class='merchant'>";

$count = 0;

$merchantArray = array();

foreach($merchantlist as $value)
 {
	 
	$venuejson = file_get_contents('https://api.foursquare.com/v2/venues/'.$value . '?oauth_token='. $row[1] .'&v=20140501');
	$venuearray = json_decode($venuejson,true);

	array_push ($merchantArray, $venuearray['response']);

}	

	foreach($merchantArray as $venue)
	{
		if(in_array($venue['venue']['id'], $checkin_arr)) {
			$count++;
			echo "<li class='merchant' style='background-image:url(" . $venue['venue']['categories']['0']['icon']['prefix'] . "44.png);'>" . $venue['venue']['name'] . "</li>";
		}
		else {
			echo "<li class='merchant' style='background-image:url(" . $venue['venue']['categories']['0']['icon']['prefix'] . "44.png); background-color:#CBCED1;'>" . $venue['venue']['name'] . "</li>";
		}
	}

echo "</ul>";	
 
	if ($venuecount == count($merchantlist)) {
		
		?>
		<h2>Congratulations, you have visited all venues.</h2>
        <p>Please enter your email address to enter a £50 Amazon voucher</p>
        <input type="text" placeholder="aexp email address" /><br />
        <input type="submit" />
		<?php
	}
	else
		{
			?>
		<h2>Not quite there yet</h2>
        <p>You still have a few more venues to visit... Good luck!</p>
       <?php 
		}
		
		
	
	
	
	}
	
	
	

?>
</div>
</body>
</html>