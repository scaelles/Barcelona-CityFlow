<?php
		$lat = $_GET['lat'];
		$lng = $_GET['lng'];
		$dist = $_GET['dist'];
		
		$ch = curl_init();
		$url = 'https://api.instagram.com/v1/media/search';
		$queryParams = '?' . urlencode('lat') . '=' . urlencode($lat) . '&' . urlencode('lng') . '=' . urlencode($lng)  
		. '&' . urlencode('distance') . '=' . urlencode($dist) .'&'. urlencode('min_timestamp') . '=' . urlencode(time()-900). '&'. urlencode('client_id') . '=' . urlencode('f270041f78bc441f9998a741db100261');
		curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

		$response = curl_exec($ch);
		curl_close($ch); 
		
		$json_var = json_decode($response);
		$dades= $json_var->data;
		
		foreach ($dades as $value){
			$tags = implode(",",$value->tags);
			$lat = $value->location->latitude;
			$long = $value->location->longitude;
			$imglink = $value->images->standard_resolution->url;
			$likes = $value->likes->count;
			$type = $value->type;
			$caption = $value->caption->text;
			$createdTime = $value->caption->created_time;
			
			echo $likes;
			
		}

		//http://localhost/getInstaData.php?lat=41.403313&lng=2.156337&dist=650
			
?>