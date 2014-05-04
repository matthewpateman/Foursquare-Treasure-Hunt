<?php

include 'secret/database.php';
$con = mysql_connect($db_server, $db_user, $db_password);

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
mysql_select_db($db_database, $con);
$sql="INSERT INTO merchants (merchantid) VALUES ('$_POST[merchantid]')";



if (!mysql_query($sql,$con))
  {
  die('Error: ' . mysql_error());
  }
echo "1 record added";
mysql_close($con)
?>