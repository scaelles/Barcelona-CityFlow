<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>City Flow</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="http://fonts.googleapis.com/css?family=Raleway:400,200,500,600,700,800,300" rel="stylesheet" />
<link href="default.css" rel="stylesheet" type="text/css" media="all" />
<link href="fonts.css" rel="stylesheet" type="text/css" media="all" />
<script src="http://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true"></script>
<script type="text/javascript" src="funcion.js"></script>
<script type="text/javascript" src="prototype.js"></script>
<script>
  // This example displays a marker at the center of Australia.
// When the user clicks the marker, an info window opens.
// The maximum width of the info window is set to 200 pixels.

//Funcio query Instagram
	var variable_global;
	var numCoincidencies;
	function queryInsta(idcells){
		var txtResp;
		if (window.XMLHttpRequest)
		{// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		}else{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		
		xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				txtResp=xmlhttp.responseText;
				
				numCoincidencies=parseInt(txtResp.substr(0,txtResp.indexOf("<p>")));
				variable_global=txtResp.substr(txtResp.indexOf("<p>"))
			}
			
		}
		xmlhttp.open("GET","getInstaData.php?idcell="+idcells,false);
		xmlhttp.send();

	}

function initialize() {
	var myLatlng = new google.maps.LatLng(41.392918, 2.180317);
	var markerPos = new google.maps.LatLng(41.397555, 2.191187);

	var mapOptions = {
		zoom: 14,
		center: myLatlng
	};

	var citymap = {};
	citymap['s_2106'] = {
		  center: new google.maps.LatLng(41.385899, 2.182750),
		  radius: 0.001,
		  title: 'Sensor 1',
		  myLat: 41.385899,
		  myLong: 2.182750
	};
	citymap['s_2108'] = {
		  center: new google.maps.LatLng(41.385399, 2.183410),
		  radius: 0.001,
		  title: 'Sensor 2',
		  myLat: 41.385399,
		  myLong: 2.183410
	};
    citymap['s_2110'] = {
      center: new google.maps.LatLng(41.384998, 2.183940),
      radius: 0.001,
	  title: 'Sensor 3',
	  myLat: 41.384998,
	  myLong: 2.183940
	};
    citymap['gracia'] = {
      center: new google.maps.LatLng(41.403113, 2.156337),
      radius: 650,
	  title: 'Gracia',
	  myLat: 41.403113,
	  myLong: 2.156337,
	  idcell: 31
	};
    citymap['port'] = {
      center: new google.maps.LatLng(41.389207, 2.192742),
      radius: 850,
	  title: 'Port Olímpic',
	  myLat: 41.389207,
	  myLong: 2.192742,
	  idcell: 41	  
	};
    citymap['gotic_born'] = {
      center: new google.maps.LatLng(41.383005, 2.177425),
      radius: 600,
	  title: 'Barri Gòtic and Born',
	  myLat: 41.383005,
	  myLong: 2.177425,
	  idcell: 51	  	  
	};
    citymap['poblenou'] = {
      center: new google.maps.LatLng(41.399551, 2.201624),
      radius: 632.45,
	  title: 'Poblenou',
	  myLat: 41.399551,
	  myLong: 2.201624,
	  idcell: 71	  	  
	};
    citymap['marina'] = {
      center: new google.maps.LatLng(41.402117, 2.185833),
      radius: 707.1,
	  title: 'Marina',
	  myLat: 41.402117,
	  myLong: 2.185833,
	  idcell: 81	  	  
	};
    citymap['safa'] = {
      center: new google.maps.LatLng(41.405294, 2.170893),
      radius: 600,
	  title: 'Sagrada Família',
	  myLat: 41.405294,
	  myLong: 2.170893,
	  idcell: 91	  	  	  
	};
    citymap['arago'] = {
      center: new google.maps.LatLng(41.389263, 2.160840),
      radius: 920,
	  title: 'Passeig de Gràcia',
	  myLat: 41.389263,
	  myLong: 2.160840,
	  idcell: 101	  	  	  
	};
    citymap['tetuan'] = {
      center: new google.maps.LatLng(41.395284, 2.175774),
      radius: 650,
	  title: 'Tetuan',
	  myLat: 41.395284,
	  myLong: 2.175774,
	  idcell: 111	  	  	  	  
	};
    citymap['raval'] = {
      center: new google.maps.LatLng(41.377751, 2.164128),
      radius: 700,
	  title: 'Raval and Poble Sec',
	  myLat: 41.377751,
	  myLong: 2.164128,
	  idcell: 121	  	  	  	  
	};

	var cityCircle;
	

	var map = new google.maps.Map(document.getElementById('page'), mapOptions);
	var contentStrArr = {};
	var infowindowArr = {};
	for (var city in citymap) {
			
		//Query to Instagram
		queryInsta(citymap[city].idcell);
		var populationOptions = {
		  strokeColor: '#FF0000',
		  strokeOpacity: 0.8,
		  strokeWeight: 0,
		  fillColor: '#FAA000',
		  fillOpacity: numCoincidencies/20*0.6+0.05,
		  map: map,
		  center: citymap[city].center,
		  radius: citymap[city].radius
		};
		//alert(variable_global);
		contentStrArr[city]='<div id="content">'+
		  '<div id="siteNotice">'+
		  '</div>'+		  '<h1 id="firstHeading" class="firstHeading">'+citymap[city].title+'</h1>'+
		  '<div id="bodyContent">'+		  '<p><b>The most famous sight in Barcelona</b> ' +		  '</p>'+variable_global +
		  '</div>'+		  '</div>';
		
		infowindowArr[city] = new google.maps.InfoWindow({
			  content: contentStrArr[city]
		  }); //maxWidth: 250
		
		// Add the circle for this city to the map.
		cityCircle = new google.maps.Circle(populationOptions);
	}
	
	
  // var contentString = '<div id="content">'+
      // '<div id="siteNotice">'+
      // '</div>'+
      // '<h1 id="firstHeading" class="firstHeading">Gracia</h1>'+
      // '<div id="bodyContent">'+
      // '<p><b>Sala Razzmatazz</b> is a top place to go. ' +
      // '</p>'+
      // '<p>Sala Razzmatazz <a href="http://www.salarazzmatazz.com/">'+
      // 'http://www.salarazzmatazz.com/</a> '+
	  // '<img src="http://www.eventos-barcelona.com/images/stories/sala-razzmatazz-1.jpg" style="width:200px">'+
      // '</p>'+
      // '</div>'+
      // '</div>';

  // var infowindow = new google.maps.InfoWindow({
      // content: contentString,
      // maxWidth: 250
  // });

  var markerGracia = new google.maps.Marker({
      position: citymap['gracia'].center,
      map: map,
      title: 'Gracia'
  });
  google.maps.event.addListener(markerGracia, 'mouseover', function() {
	infowindowArr['gracia'].open(map,markerGracia);
	infowindowArr['tetuan'].close();
	infowindowArr['safa'].close();
	infowindowArr['port'].close();
	infowindowArr['gotic_born'].close();
	infowindowArr['poblenou'].close();
	infowindowArr['marina'].close();
	infowindowArr['arago'].close();
	infowindowArr['raval'].close();

  });

  var markerPort = new google.maps.Marker({
      position: citymap['port'].center,
      map: map,
      title: 'Port'
  });
  google.maps.event.addListener(markerPort, 'mouseover', function() {
    infowindowArr['port'].open(map,markerPort);
	infowindowArr['tetuan'].close();
	infowindowArr['safa'].close();
	infowindowArr['gracia'].close();
	infowindowArr['gotic_born'].close();
	infowindowArr['poblenou'].close();
	infowindowArr['marina'].close();
	infowindowArr['arago'].close();
	infowindowArr['raval'].close();
	
  });

   var markerGotic = new google.maps.Marker({
      position: citymap['gotic_born'].center,
      map: map,
      title: 'Barri Gòtic and Born'
  });
  google.maps.event.addListener(markerGotic, 'mouseover', function() {
    infowindowArr['gotic_born'].open(map,markerGotic);
	infowindowArr['tetuan'].close();
	infowindowArr['safa'].close();
	infowindowArr['port'].close();
	infowindowArr['gracia'].close();
	infowindowArr['poblenou'].close();
	infowindowArr['marina'].close();
	infowindowArr['arago'].close();
	infowindowArr['raval'].close();
	
  });
  
   var markerPoblenou = new google.maps.Marker({
      position: citymap['poblenou'].center,
      map: map,
      title: 'Poblenou'
  });
  google.maps.event.addListener(markerPoblenou, 'mouseover', function() {
    infowindowArr['poblenou'].open(map,markerPoblenou);
	infowindowArr['tetuan'].close();
	infowindowArr['safa'].close();
	infowindowArr['port'].close();
	infowindowArr['gotic_born'].close();
	infowindowArr['gracia'].close();
	infowindowArr['marina'].close();
	infowindowArr['arago'].close();
	infowindowArr['raval'].close();
	
  });
  
   var markerMarina = new google.maps.Marker({
      position: citymap['marina'].center,
      map: map,
      title: 'Marina'
  });
  google.maps.event.addListener(markerMarina, 'mouseover', function() {
    infowindowArr['marina'].open(map,markerMarina);
	infowindowArr['tetuan'].close();
	infowindowArr['safa'].close();
	infowindowArr['port'].close();
	infowindowArr['gotic_born'].close();
	infowindowArr['poblenou'].close();
	infowindowArr['gracia'].close();
	infowindowArr['arago'].close();
	infowindowArr['raval'].close();
	
  });
  
   var markerSagrada = new google.maps.Marker({
      position: citymap['safa'].center,
      map: map,
      title: 'Sagrada Familia'
  });
  google.maps.event.addListener(markerSagrada, 'mouseover', function() {
    infowindowArr['safa'].open(map,markerSagrada);
	infowindowArr['tetuan'].close();
	infowindowArr['gracia'].close();
	infowindowArr['port'].close();
	infowindowArr['gotic_born'].close();
	infowindowArr['poblenou'].close();
	infowindowArr['marina'].close();
	infowindowArr['arago'].close();
	infowindowArr['raval'].close();
	
  });
  
   var markerTetuan = new google.maps.Marker({
      position: citymap['tetuan'].center,
      map: map,
      title: 'Tetuan'
  });
  google.maps.event.addListener(markerTetuan, 'mouseover', function() {
    infowindowArr['tetuan'].open(map,markerTetuan);
	infowindowArr['gracia'].close();
	infowindowArr['safa'].close();
	infowindowArr['port'].close();
	infowindowArr['gotic_born'].close();
	infowindowArr['poblenou'].close();
	infowindowArr['marina'].close();
	infowindowArr['arago'].close();
	infowindowArr['raval'].close();
	
  });
  
   var markerArago = new google.maps.Marker({
      position: citymap['arago'].center,
      map: map,
      title: 'Passeig de Gràcia'
  });
  google.maps.event.addListener(markerArago, 'mouseover', function() {
    infowindowArr['arago'].open(map,markerArago);
	infowindowArr['tetuan'].close();
	infowindowArr['safa'].close();
	infowindowArr['port'].close();
	infowindowArr['gotic_born'].close();
	infowindowArr['poblenou'].close();
	infowindowArr['marina'].close();
	infowindowArr['gracia'].close();
	infowindowArr['raval'].close();
	
  });
  

   var markerRaval = new google.maps.Marker({
      position: citymap['raval'].center,
      map: map,
      title: 'Raval and Poble Sec'
  });
  google.maps.event.addListener(markerRaval, 'mouseover', function() {
    infowindowArr['raval'].open(map,markerRaval);
	infowindowArr['tetuan'].close();
	infowindowArr['safa'].close();
	infowindowArr['port'].close();
	infowindowArr['gotic_born'].close();
	infowindowArr['poblenou'].close();
	infowindowArr['marina'].close();
	infowindowArr['arago'].close();
	infowindowArr['gracia'].close();
	});
}


google.maps.event.addDomListener(window, 'load', initialize);
</script>
</head>
<body>
<?


?>
<div id="wrapper">
	<div id="menu-wrapper">
		<div id="menu" class="container">
			<ul>
				<?
			echo 	'
					<li><a href="javascript:cargarseccion(\'Homepage\')">Homepage</a></li>  
					<li><a href="javascript:cargarseccion(\'About\')">About</a></li>  
					<li><a href="javascript:cargarseccion(\'Trends\')">Trends</a></li> 
					<li><a href="javascript:cargarseccion(\'Contact\')">Contact</a></li>   	   
					';
				?>
			</ul>
		</div>
		<!-- end #menu --> 
	<div id="header-wrapper">
		<div id="header" class="container3">
			<div id="logo">
				<h1><a href="#">CityFlow</a></h1>
			</div>
		</div>
	</div>
	</div>
	<div id="page" class="container">
	
		</div>
	</div>
</div>
	
<div id="copyright" class="container2">
	
</div>
</body>
</html>
