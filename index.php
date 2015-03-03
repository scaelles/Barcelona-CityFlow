<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <title>City Flow</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <link href="http://fonts.googleapis.com/css?family=Raleway:400,200,500,600,700,800,300" rel="stylesheet" />
  <link href="fonts.css" rel="stylesheet" type="text/css" media="all" />
  <script src="http://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true"></script>
  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=visualization&sensor=true_or_false"> </script>
	<!--link rel="stylesheet/less" href="less/bootstrap.less" type="text/css" /-->
	<!--link rel="stylesheet/less" href="less/responsive.less" type="text/css" /-->
	<!--script src="js/less-1.3.3.min.js"></script-->
	<!--append ‘#!watch’ to the browser URL, then refresh the page. -->
	
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">
	  <style>
		#mapa
			{
				position: relative;
				overflow: hidden;
				padding: 0px;
				height:500px;
			}
		#headermenu
			{
				text-transform: uppercase;
				background: #16a085;
				font-family: 'Raleway', sans-serif;
				font-size: 11pt;
				font-weight: 400;
				color: #363636;
				list-style: none;
				line-height: normal;
				text-align: center;
			}
			
		#footer
			{
				text-transform: uppercase;
				background: #333333;
				font-family: 'Raleway', sans-serif;
				font-size: 11pt;
				font-weight: 400;
				color: #363636;
				list-style: none;
				line-height: normal;
				text-align: center;
				height: 200px;
				overflow: hidden;
				background-size: 100%;
				text-transform: uppercase
				letter-spacing: 0.10em;
				font-size: 11pt;
				color: #FFF;
			}
			
		#headermenu ul 
			{
				list-style: none;
				line-height: normal;
				text-align: center;
			}

		#headermenu li 	
			{
				display: inline-block;
			}
		#headermenu a 
			{
				display: block;
				letter-spacing: 1px;
				padding: 0px 15px;
				text-decoration: none;
				text-align: center;
				text-transform: uppercase;
				font-size: 1em;
				line-height: 50px;
				border: none;
				color: #FFF;
			}
			
			#headermenu a:hover
			{
				
				background-color: #637D4D;
			}
			#headermenu a:active
			{
				background-color: #637D4D;
			} 
			
		#title
			{
				overflow: hidden;
				background-size: 100%;
				background: #333333;
				text-transform: uppercase;
				font-family: 'Raleway', sans-serif;
			}
		#title h3
			{
				letter-spacing: 0.10em;
				font-size: 3em;
				color: #FFF;
			}
							
	</style>

  <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
  <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
  <![endif]-->

  <!-- Fav and touch icons -->
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="img/apple-touch-icon-144-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="img/apple-touch-icon-114-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="img/apple-touch-icon-72-precomposed.png">
  <link rel="apple-touch-icon-precomposed" href="img/apple-touch-icon-57-precomposed.png">
  <link rel="shortcut icon" href="img/favicon.png">
  
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/scripts.js"></script>
	<script type="text/javascript" src="funcion.js"></script>
	<script type="text/javascript" src="prototype.js"></script>
	<script type="text/javascript">
		//Funcio query Instagram
		var arrayJSON;
		var map;
		var heatmap;
		var statusHeat=false;
		function queryInsta(){
			if (window.XMLHttpRequest){
				// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			}else{// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			
			xmlhttp.onreadystatechange=function(){
				if (xmlhttp.readyState==4 && xmlhttp.status==200){
					JSONstring=xmlhttp.responseText;
					arrayJSON = JSON.parse(JSONstring);
					posarInfo();
				}
			}
			xmlhttp.open("GET","getInstaData.php",true);
			xmlhttp.send();
		}

		function createPolygon(coords, strokeColorVar, fillColorVar, numLastPosts){
			//strokeColor: '#FF0000'
			//fillColor: '#F00000'
			var polygonCharacteristics = {
					paths: coords,
					strokeColor: strokeColorVar, //AIXO HA DE DEPENDRE DEL district.lastPosts o algo aixi, JA SIGUI COLOR O OPACITAT (DEL FILL)
					strokeOpacity: 0.8,
					strokeWeight: 1,
					fillColor: fillColorVar, 
					fillOpacity: 0.05+ numLastPosts/10
			};
			
			return new google.maps.Polygon(polygonCharacteristics);
		}
		
		function posarInfo(){
			//Els camps disponibles estan en comentari.
			var postsDistrict;
			var postsHeatMap=[];
			var fillColor = ["#DC143C", "#C71585", "#FF8C00", "#8A2BE2", "#228B22", "#6495ED", "#F4A460", "#708090", "#FF1493", "#4B0082"];

			for(var i=0; i<arrayJSON.length; i++){
				postsDistrict=0;
				var district = arrayJSON[i];
				//district.boundsDistrict
				//district.centerDistrict
				
				var districtCoords = [];
				for (var myindex in district.boundsDistrict){
					//alert(district.boundsDistrict[myindex]);
					districtCoords[myindex]= new google.maps.LatLng(parseFloat(district.boundsDistrict[myindex][0]), parseFloat(district.boundsDistrict[myindex][1]));
				}
				//alert(districtCoords);
				arrayJSON[i].boundsDistrictFloat=districtCoords;
				arrayJSON[i].centerDistrictFloat=new google.maps.LatLng(parseFloat(districtCoords[0]), parseFloat(districtCoords[1]));
				
				//document.getElementById('footer').innerHTML ="<h2>"+arrayJSON[i].nameDistrict+"</h2>";
				//TODO: Dibuixar poligon si estas en nivell de zoom que toca
				//TODO: Fer infoWindow corresponent si estàs al nivell de zoom
				//TODO: Afegir listener si cal
				
				for(var j=0; j<district.neighbs.length; j++){
					var neighb = district.neighbs[j];					
					//alert(neighb.name)
					//neighb.bounds
					//neighb.numLastPosts
						
					var neighbCoords = [];
					for (var myindex in neighb.bounds){
						//alert(district.boundsDistrict[myindex]);
						neighbCoords[myindex]= new google.maps.LatLng(parseFloat(neighb.bounds[myindex][0]), parseFloat(neighb.bounds[myindex][1]));
					}
					neighb.boundsNeighbFloat=neighbCoords;
				  
					neighb.polygonNeighb = createPolygon(neighbCoords,fillColor[i],fillColor[i],neighb.numLastPosts); 
					neighb.polygonNeighb.setMap(null);
					

					
					//TODO: Dibuixar poligon si estas en nivell de zoom que toca
					//TODO: Fer infoWindow corresponent si estàs al nivell de zoom
					//TODO: Afegir listener

					//alert (neighb.posts.length);
					postsDistrict = postsDistrict + neighb.numLastPosts;
					
					for(var k=0; k<neighb.posts.length; k++){
						var post = neighb.posts[k];
						//alert(parseFloat(post.lat));
						//alert(post.tags)
						//post.im_link
						//post.lat
						//post.long
						
						postsHeatMap.push(new google.maps.LatLng(parseFloat(post.lat), parseFloat(post.long)));
					}
					district.neighbs[j]=neighb;
				}
				
				//alert (postsDistrict); 
				polygDist = createPolygon(districtCoords,fillColor[i],fillColor[i],postsDistrict); 
				arrayJSON[i].polygonDistrict=polygDist;
				arrayJSON[i].polygonDistrict.setMap(map);
				
				var pointArray = new google.maps.MVCArray(postsHeatMap);
				heatmap = new google.maps.visualization.HeatmapLayer({
					data: pointArray
				});
				heatmap.setMap(null);
				
				google.maps.event.addListener(arrayJSON[i].polygonDistrict, 'mouseover', make_callback(arrayJSON[i].nameDistrict));
				
			}
			google.maps.event.addListener(map, 'zoom_changed', zoomChanged);
		}
		function initialize() {
			var myLatlng = new google.maps.LatLng(41.392918, 2.180317);
			var markerPos = new google.maps.LatLng(41.397555, 2.191187);

			var mapOptions = {
				zoom: 13,
				center: myLatlng
			};

			map = new google.maps.Map(document.getElementById('mapa'),mapOptions);
			queryInsta();
		}
		
		function zoomChanged(){
			if(statusHeat==false){
				if (map.getZoom()>=14){
					//Amagar districtes i mostrar barris
					for(var i=0; i<arrayJSON.length; i++){
						arrayJSON[i].polygonDistrict.setMap(null);
						for(var j=0; j<arrayJSON[i].neighbs.length; j++){
							arrayJSON[i].neighbs[j].polygonNeighb.setMap(map);
							google.maps.event.addListener(arrayJSON[i].neighbs[j].polygonNeighb, 'mouseover', make_callback(arrayJSON[i].neighbs[j].name));

						}
					}
				}else{
					//Amagar barris i mostrar districtes
					for(var i=0; i<arrayJSON.length; i++){
						arrayJSON[i].polygonDistrict.setMap(map);
						for(var j=0; j<arrayJSON[i].neighbs.length; j++){
							arrayJSON[i].neighbs[j].polygonNeighb.setMap(null);
						}
					}
				}
			}
		}
		
		function toggleHeatMap(){
			//Amagar districtes i mostrar barris
			if(statusHeat==false){
				for(var i=0; i<arrayJSON.length; i++){
					arrayJSON[i].polygonDistrict.setMap(null);
					for(var j=0; j<arrayJSON[i].neighbs.length; j++){
						arrayJSON[i].neighbs[j].polygonNeighb.setMap(null);
					}
				}
				heatmap.setMap(map);
				statusHeat=true;
			}else{
				heatmap.setMap(null);
				if (map.getZoom()>=14){
					//Amagar districtes i mostrar barris
					for(var i=0; i<arrayJSON.length; i++){
						arrayJSON[i].polygonDistrict.setMap(null);
						for(var j=0; j<arrayJSON[i].neighbs.length; j++){
							arrayJSON[i].neighbs[j].polygonNeighb.setMap(map);
							google.maps.event.addListener(arrayJSON[i].neighbs[j].polygonNeighb, 'mouseover', make_callback(arrayJSON[i].neighbs[j].name));

						}
					}
				}else{
					//Amagar barris i mostrar districtes
					for(var i=0; i<arrayJSON.length; i++){
						arrayJSON[i].polygonDistrict.setMap(map);
						for(var j=0; j<arrayJSON[i].neighbs.length; j++){
							arrayJSON[i].neighbs[j].polygonNeighb.setMap(null);
						}
					}
				}
				statusHeat=false;
			}
		}
		
		function make_callback(zonaTXT) {
			return function() {
				document.getElementById('footer').innerHTML ="<h2>"+decodeURIComponent(escape(zonaTXT))+"</h2>";
			};
		}

		google.maps.event.addDomListener(window, 'load', initialize);
		google.maps.event.addDomListener(map,'zoom_changed', zoomChanged);
	</script>
</head>

<body>
<div id="global" class="container">
	<div id="headermenu" class="row clearfix">
			<div class"col-md-12 column">
			<ul>
					
			
						<li><a href="javascript:cargarseccion('Homepage')">Homepage</a></li>  
						<li><a href="javascript:cargarseccion('Trends')">Trends</a></li>  
						<li><a href="javascript:cargarseccion('About')">About</a></li> 
						<li><a href="javascript:toggleHeatMap()">Toggle Heat Map</a></li> 	   
					
			</ul>
			</div>
	</div>
	<div class="row clearfix">
		<div id="title" class="col-md-12 column">
			<h3 class="text-center">
				City Flow
			</h3>
		</div>
	</div>
	<div class="row clearfix">
		<div id="mapa" class="col-md-12 column">
			
		</div>
	</div>
	<div class="row clearfix">
		<div id="footer" class="col-md-12 column">
						Homepage 
		</div>
	</div>
	
</div>
</body>
</html>
