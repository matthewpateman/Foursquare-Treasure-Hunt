<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name = "viewport" content = "width = device-width, initial-scale = 1, user-scalable = no" />
<title>Check-ins Check</title>
<link href="style/style.css" rel="stylesheet" type="text/css" media="all" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>

	
function nav()  
  {  
 if ($('header').height() == "50")
 { 
$('header').css('height','170px');
$('header #arrow').addClass('rotate180');
 }
 else {
	 $('header').css('height','50px');
	 $('header #arrow').removeClass('rotate180');
	 
	 }
 }
</script>

</head>

<body>

<?php if ($login == "yes") { ?>

<header id="header">
<div>
<div id="title">Check-ins Check</div>
<div class="clear"></div>
</div>
</header>

<?php }

else { ?>
	
<header id="header">
<div>
<div id="title">Check-ins Check</div>
<div id="arrow" onclick="nav()"></div>
<div class="clear"></div>
<ul>
<li>London</li>
<li>Brighton</li>
<li>Newcastle</li>
</ul>
</div>
</header>

<?php } ?>

<div class="content">