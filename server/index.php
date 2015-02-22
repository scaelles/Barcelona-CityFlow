<html>
	<head>
		
	</head>
	<body>
		

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
	$result=mysqli_query($con,"SELECT * FROM cells") or die("Caelles paquet");
	//$row=mysqli_fetch_array($result,MYSQLI_NUM);
	while($rowCell=mysqli_fetch_array($result)){
		$lat = $rowCell['lat'];
		$long = $rowCell['long'];
		$rad = $rowCell['rad'];
		$idcells = $rowCell['idcells'];
		//var_dump($rowCell);
		//Entrar API instagram i fer cerca
		//Obtenir imatges dins area
		$ch = curl_init();
		$url = 'https://api.instagram.com/v1/media/search';
		$queryParams = '?' . urlencode('lat') . '=' . urlencode($lat) . '&' . urlencode('lng') . '=' . urlencode($long)  
		. '&' . urlencode('distance') . '=' . urlencode($rad) .'&'. urlencode('min_timestamp') . '=' . urlencode(time()-300). '&'. urlencode('client_id') . '=' . urlencode('f270041f78bc441f9998a741db100261');
		curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

		$response = curl_exec($ch);
		curl_close($ch); 
		
		//echo $response;
		
		$json_var = json_decode($response);
		$dades= $json_var->data;
		$numInstas = count($dades);
		//echo "Hi ha $numInstas a $idcells\n";
		foreach ($dades as $value){
			//var_dump($value);
			$tags = implode(",",$value->tags);
			$latImg = $value->location->latitude;
			$longImg = $value->location->longitude;
			$imglink = $value->images->standard_resolution->url;
			$likes = $value->likes->count;
			$typeMedia = $value->type;
			$caption = $value->caption;
			if ($caption!=NULL){
				$caption = str_replace("'", "", $caption->text);
			}else{
				$caption='';
			}
			//echo $caption;
			$timeMedia = $value->created_time;
			$timeMedia = gmdate("Y-m-d\TH:i:s\Z", $timeMedia);
			
			//Insert into DB
			$query = "INSERT INTO `posts` (`type_media`, `date`, `caption`, `tags`,`im_link`,`lat`,`long`,`likes`,`idcells`) ".
			"VALUES ('$typeMedia', '$timeMedia','$caption','$tags','$imglink','$latImg','$longImg','$likes','$idcells')";
			//echo $query.'<br>';
			mysqli_query($con,$query) or die("Error making insert query of images into DB\n".mysqli_error($con).'<br>');
			echo "Register added<br>\n";
			
		}
		
		
		//$res=mysqli_query($con,"SELECT COUNT(idposts) FROM posts WHERE date BETWEEN DATE_SUB(NOW(),INTERVAL 5 MINUTE) AND NOW() AND idcells=$idcells") or die("BETWEEN . mysqli_error()");
		//$rresu=mysqli_fetch_array($res);
		//echo $rresu[0] . " posts in last 5 minutes in cell ".$idcells."<br>";
		
		
		//Actualitzar sensors
		$resSens=mysqli_query($con,"SELECT * FROM sensors WHERE idcells='$idcells'") or die("Error fetching sensors".mysql_error());
		while($rowSens=mysqli_fetch_array($resSens)){
			//var_dump($rowSens);
			$idSensor = $rowSens['idsensors'];
			$longSens = $rowSens['long'];
			$latSens = $rowSens['lat'];
			$propSens = $rowSens['property'];
			
			$ch = curl_init();
			$url = 'http://icity-gw.icityproject.com:8080/developer/api/observations/last';
			$queryParams = '?' . urlencode('apikey') . '=' . urlencode('l7xx066ba3d49349419d9d0a9b5d1a34055b').'&'.urlencode('id') . '=' . urlencode($idSensor).'&'.urlencode('n').'='.urlencode(1).'&'.urlencode('property').'='.urlencode('urn:people_flow');
			curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_HEADER, FALSE);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
			$respSen = curl_exec($ch);
			curl_close($ch);
			
			$json_var = json_decode($respSen);
			//For each observation of sensor
			foreach ($json_var as $valueSen){
				var_dump($valueSen);
				$time=$valueSen->time;
				$valSen = $valueSen->value;
				
				//Insert into DB
				$query = "INSERT INTO `sensor_values` (`idsensor`, `date`, `value`) ".
				"VALUES ('$idSensor', '$time','$valSen')";
				
				mysqli_query($con,$query) or die("Error making insert query of sensors into DB\n".mysqli_error($con).'<br>');
				echo "Sensor register added\n";
			}
		}
		
	}

	//$query = "DELETE FROM posts WHERE date < DATE_SUB(NOW(),INTERVAL 1 HOUR)";
	//mysqli_query($con,$query) or die("Error deleting.".mysqli_error($con));
	
	
	
	mysqli_close($con)
	
	
?>


	</body>

	<meta http-equiv="refresh" content="600" >
</html>
