<?php
	require_once("include/settings.php");
	//Parameters
	
	// //Structure JSON file
	// [{
        // "idDistrict": 1,
        // "name": "Gràcia",
		// "bounds": XXX,
		// "barris": [{
			//	"idBarri": 1,
			//	"name": "Vallcarca",
			//	"bounds": XXX,
			//	"posts": [{
				// "lat": lat,
				// "long": long,
				// "im_link": link,
				// "tags": tags
				// }],
			// "numLastPosts": #number
        // }],
    // }]
	$outputJSONarray = array();
	$c=0;
	$arrayDistr=array();
	$resDistr = mysqli_query($con, "SELECT * FROM districts") or die("Error in districts query<br>".mysqli_error($con));
	while($rowDistr=mysqli_fetch_array($resDistr)){
		//For each district, we get all the neighbourhoods
		$idDistrict = $rowDistr['idDistrict'];
		$nameDistrict = $rowDistr['name'];
		$boundsDistrictStr = explode('|',$rowDistr['bounds']);
		//echo $nameDistrict.'<br>';
		
		$boundsDistrict=array();
		for($i=0; $i<count($boundsDistrictStr);$i++){
			$boundsDistrict[$i]=explode(',',$boundsDistrictStr[$i]);
		}
			
		$centerDistrict=explode(',',$rowDistr['center']);
		
		$cNeigh=0;
		$resNeigh = mysqli_query($con, "SELECT * FROM neighbourhoods WHERE idDistrict='$idDistrict'") or die("Error in neighbourhoods query<br>".mysqli_error($con));
		while($rowNeigh=mysqli_fetch_array($resNeigh)){
			//we have to look for the last 3 posts in the social network, and count posts in last period of time.
			$idNeigh = $rowNeigh['idNeighb'];
			$nameNeigh = $rowNeigh['name'];
			$boundsNeighStr = explode('|',$rowNeigh['bounds']);
			//echo "--".$nameNeigh."<br>";
					
			$boundsNeigh=array();
			for($i=0; $i<count($boundsNeighStr);$i++){
				$boundsNeigh[$i]=explode(',',$boundsNeighStr[$i]);
			}
		
			//Count posts in last X minutes
			// $resNumPosts=mysqli_query($con,"SELECT COUNT(idPost) FROM posts WHERE date BETWEEN DATE_SUB(NOW(),INTERVAL 120 MINUTE) AND NOW() AND idNeighb='$idNeigh'") or die(mysqli_error($con));
			// if(mysqli_num_rows($resNumPosts)>0){
				// $numPostsNeigh = mysqli_fetch_array($resNumPosts);
				// $numPostsNeigh=$numPostsNeigh[0];
			// }else{
				// $numPostsNeigh=0;
			// }
			
			//Retrieve 3 last images in neighbourhood
			$cPosts=0;
			$arrayPosts=array();
			$resPosts=mysqli_query($con,"SELECT im_link, tags, lat, posts.lng FROM posts WHERE idNeighb='$idNeigh' AND DATE_SUB(date,INTERVAL 1 HOUR) BETWEEN DATE_SUB(NOW(), INTERVAL 15 MINUTE) AND NOW() ORDER BY date DESC");
			//echo mysqli_num_rows($resPosts);
			while($rowPosts=mysqli_fetch_array($resPosts)){ 
				$tagsPost=htmlspecialchars($rowPosts['tags']);
				$linkPost=$rowPosts['im_link'];
				$latPost=$rowPosts['lat'];
				$longPost=$rowPosts['lng'];
				
				//echo "-- --".$linkPost.'<br>';
				$arrayPosts[$cPosts]=array('lat'=>$latPost, 'long'=>$longPost, 'link'=>$linkPost, 'tags'=>$tagsPost);
				$cPosts++;
			}
			//Write output array
			$arrayNeigh[$cNeigh]=array('idNeigh'=>$idNeigh,'name'=>utf8_encode($nameNeigh), 'bounds'=>$boundsNeigh, 'numLastPosts'=>mysqli_num_rows($resPosts), 'posts'=>$arrayPosts);
			$cNeigh++;
		}
		$arrayDistr[$c]=array('idDistrict'=>$idDistrict, 'nameDistrict'=>$nameDistrict,'boundsDistrict'=>$boundsDistrict,'neighbs'=>$arrayNeigh,'centerDistrict'=>$centerDistrict);
		$c++;
	}
	$plainJSON = json_encode($arrayDistr);
	echo $plainJSON;
			
?>