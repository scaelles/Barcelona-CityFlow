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
	
	//PROVA

		//Funcio query Instagram
		var arrayJSON;
		var map;
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

		function posarInfo(){
			var heatmap;
			var postsDistrict;
			var postsHeatMap=[];

			for(var i=0; i<arrayJSON.length; i++){
				postsDistrict=0;
				var district = arrayJSON[i];
	
				for(var j=0; j<district.neighbs.length; j++){
					var neighb = district.neighbs[j];					
											
					for(var k=0; k<neighb.posts.length; k++){
						var post = neighb.posts[k];
     					postsHeatMap.push(new google.maps.LatLng(parseFloat(post.lat), parseFloat(post.long)));
					}
				}
			}
			var pointArray = new google.maps.MVCArray(postsHeatMap);
			var heatmap = new google.maps.visualization.HeatmapLayer({
				data: pointArray
			});
			heatmap.setMap(map);
		}
		
		function initialize() {
			var myLatlng = new google.maps.LatLng(41.392918, 2.180317);
			var markerPos = new google.maps.LatLng(41.397555, 2.191187);

			var mapOptions = {
				zoom: 13,
				center: myLatlng
			};
			queryInsta();
			map = new google.maps.Map(document.getElementById('mapa'),mapOptions);

		}
		
		function zoomChanged(){
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
						<li><a href="javascript:cargarseccion('Contact')">Contact</a></li> 	   
					
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
