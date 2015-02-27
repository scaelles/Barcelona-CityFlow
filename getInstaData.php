<?php
	require_once("include/settings.php");
	//Parameters
	$periodUpdate = 120; //In minutes
	
	// //Structure JSON file
	// [{
        // "idcell": 1,
        // "name": "Gràcia",
		// "lat": XXX,
		// "long": YYY,
		// "rad": ZZZ,
		// "posts": [{
			// "lat": lat,
			// "long": long,
			// "im_link": link,
			// "tags": tags
			// },{}]
        // "numLastPosts": #number
        // }
    // },
    // {}]
	$outputJSONarray = array();
	$c=0;
	$resCells = mysqli_query($con, "SELECT * FROM cells") or die("Error in cells query<br>".mysqli_error($con));
	while($rowCells=mysqli_fetch_array($resCells)){
		//For each cell, we have to look for the last 3 posts in the social network, and count posts in last period of time.
		$idCell = $rowCells['idcells'];
		$nomCell = $rowCells['name'];
		$latCell = $rowCells['lat'];
		$longCell = $rowCells['long'];
		$radCell = $rowCells['rad'];
		
		//Count posts in last X minutes
		$resNumPosts=mysqli_query($con,"SELECT COUNT(idposts) FROM posts WHERE date BETWEEN DATE_SUB(NOW(),INTERVAL 120 MINUTE) AND NOW() AND idcells='$idCell'") or die(mysqli_error($con));
		if(mysqli_num_rows($resNumPosts)>0){
			$numPostsCell = mysqli_fetch_array($resNumPosts)[0];
		}else{
			$numPostsCell=0;
		}
		//echo $numPostsCell;
		
		//Retrieve 3 last images in neighbourhood
		$cPosts=0;
		$arrayPosts=array();
		$resPosts=mysqli_query($con,"SELECT im_link, tags, lat, posts.long FROM posts WHERE idcells='$idCell' ORDER BY date DESC LIMIT 3") or die(mysqli_error($con));
		while($rowPosts=mysqli_fetch_array($resPosts)){ 
			$tagsPost=$rowPosts['tags'];
			$linkPost=$rowPosts['im_link'];
			$latPost=$rowPosts['lat'];
			$longPost=$rowPosts['long'];
			
			$arrayPosts[$cPosts]=array('lat'=>$latPost, 'long'=>$longPost, 'link'=>$linkPost, 'tags'=>$tagsPost);
			$cPosts++;
		}
		
		//Write output array
		$arrayCell=array('idCell'=>$idCell,'nameCell'=>utf8_encode($nomCell), 'lat'=>$latCell, 'long'=>$longCell, 'rad'=>$radCell, 'numLastPosts'=>$numPostsCell, 'posts'=>$arrayPosts);
		
		$outputJSONarray[$c]=$arrayCell;
		$c++;
	}
	$plainJSON = json_encode($outputJSONarray);
	echo $plainJSON;
			
?>