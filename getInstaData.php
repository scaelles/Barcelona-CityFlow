<?php
	$hostname = 'us-cdbr-iron-east-01.cleardb.net';
	$username = 'b8be3578d06bd2';
	$password = '8d45e044';
	$db = "ad_06d3edcdc42d9c2";
	$con = mysqli_connect($hostname,$username,$password,$db) or die("Caca doble");
	// Check connection
	if (mysqli_connect_errno())
	{
	 echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	$idcells = $_GET['idcell'];
	
	$res=mysqli_query($con,"SELECT COUNT(idposts) FROM posts WHERE date BETWEEN DATE_SUB(NOW(),INTERVAL 120 MINUTE) AND NOW() AND idcells='$idcells'") or die("BETWEEN . mysqli_error()");
	$rresu=mysqli_fetch_array($res);

	$numPosts = $rresu[0];
	$text=$numPosts."<p>Number of posts in last 5 minutes: ".$numPosts."<br>";
	
	$result=mysqli_query($con,"SELECT im_link FROM posts WHERE idcells='$idcells' ORDER BY date DESC LIMIT 3") or die(mysql_error());
	while($rowCell=mysqli_fetch_array($result)){ 
		$imgLink = $rowCell['im_link'];
		$text=$text."<img src='".$imgLink."' width=100>";
	}
	
	$text=$text."</p>";
	echo $text;
		//http://localhost/getInstaData.php?lat=41.403313&lng=2.156337&dist=650
	mysqli_close($con);
			
?>