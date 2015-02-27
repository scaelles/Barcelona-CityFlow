<?php
	//Host Miki proves2.mybluemix.net
	// $hostname = 'us-cdbr-iron-east-01.cleardb.net';
	// $username = 'b8be3578d06bd2';
	// $password = '8d45e044';
	// $db = "ad_06d3edcdc42d9c2";
	
	//Host Caelles cityflow.mybluemix.net
	$hostname = 'us-cdbr-iron-east-01.cleardb.net';
	$username = 'b7e861b3df9994';
	$password = 'e625f2f5';
	$db = "ad_c851349ed74ad49";
	
	$con = mysqli_connect($hostname,$username,$password,$db) or die("There are problems accessing the database. Please notify the administrator of the place of the error<br>".mysql_error());
	// Check connection
	if (mysqli_connect_errno())
	{
	 echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
?>