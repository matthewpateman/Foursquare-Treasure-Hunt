<?php 

$db_server = 'sql1.udmedia.de';
$db_user = 'l3s7328';
$db_password = '28414.9';
$db_database = 'usr_l3s7328_2';

$cxn = mysqli_connect($db_server, $db_user, $db_password, $db_database);


$query = "SELECT * FROM merchants";

$result = mysqli_query($cxn, $query);


while($row = mysqli_fetch_assoc($result))
{
	extract($row);
	echo $merchantid;
	
	echo "<hr>";
	
}
 
?>