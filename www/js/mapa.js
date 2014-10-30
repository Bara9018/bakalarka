	var location1;
	var location2;
	var location_via;
	var time_from;
	var time_to;
	var time_total;
	
	var address1;
	var address2;
	var via;

	var latlng;
	var geocoder;
	var map;
	var directionsService = new google.maps.DirectionsService();
	var totalkm=0;
	var kmtoll=0;
	var kmfirstclass=0;
	var cena=0;
	var time;
	
	var line;
	
	var infowindow1;
	var infowindow2;
	
	var distance;
	var da = false;
	
	// finds the coordinates for the two locations and calls the showMap() function
	function init()
	{
		if (da === false)
		{
			var center = new google.maps.LatLng(48.67652,17.36396);
			
			var mapOptions = 
			{
				zoom: 10,
				center: center,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			};
			
			map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
		}
	}
	function initialize()
	{
		geocoder = new google.maps.Geocoder(); // creating a new geocode object
		
		// getting the two address values
		address1 = document.getElementById("address1").value;
		address2 = document.getElementById("address2").value;
		via = document.getElementById("address_via").value;
//		time_from = toDate(document.getElementById("time_from").value);
//		time_to = toDate(document.getElementById("time_to").value);
		location_via = "";

		// finding out the coordinates
		if (geocoder) 
		{
			geocoder.geocode( { 'address': address1}, function(results, status) 
			{
				if (status == google.maps.GeocoderStatus.OK) 
				{
					//location of first address (latitude + longitude)
					location1 = results[0].geometry.location;
					address1 = results[0].formatted_address;
					document.getElementById("address1").value = address1;
				} else 
				{
					alert("Prosím, skontrolujte zadanú adresu - odkial!");
				}
			});
			geocoder.geocode( { 'address': address2}, function(results, status) 
			{
				if (status == google.maps.GeocoderStatus.OK) 
				{
					//location of second address (latitude + longitude)
					location2 = results[0].geometry.location;
					address2 = results[0].formatted_address;
					document.getElementById("address2").value = address2;
					// calling the showMap() function to create and show the map 
					if (via == "") 
					{
						showMap();
					}
				}
				else 
				{
					alert("Prosím, skontrolujte zadanú adresu - kam!");
				}
			});

			if (via != "")
			{
				geocoder.geocode( { 'address': via}, function(results, status) 
				{
					if (status == google.maps.GeocoderStatus.OK) 
					{
						//location of via (latitude + longitude)
						location_via = results[0].geometry.location;
						via = results[0].formatted_address;
						document.getElementById("address_via").value = via;
						// calling the showMap() function to create and show the map 
						showMap();
					} else 
					{
						alert(status);
					}
				});
			}
		}
	}
	
	// creates and shows the map
	function showMap()
	{
		// center of the map (compute the mean value between the two locations)
		latlng = new google.maps.LatLng((location1.lat()+location2.lat())/2,(location1.lng()+location2.lng())/2);
		
		// get the map type value from the hidden field
		var maptype = "roadmap";

		var typeId = google.maps.MapTypeId.ROADMAP;
	
		
		// set map options
			// set zoom level
			// set center
			// map type
		var mapOptions = 
		{
			zoom: 1,
			center: latlng,
			mapTypeId: typeId
		};
		
		// create a new map object
			// set the div id where it will be shown
			// set the map options
		map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
		
		// event listener to update the map type
		
		// custom marker
		var rabbit = new google.maps.MarkerImage('parsek.png');
				
		// create the markers for the two locations		
		var marker1 = new google.maps.Marker({
			map: map, 
			position: location1,
			title: "Štart",
			icon: rabbit,
			draggable: true
		});
		
		var marker2 = new google.maps.Marker({
			map: map, 
			position: location2,
			title: "Ciel",
			icon: rabbit,
			draggable: true
		});
		
		if (via != "")
		{
			var iconOrange = new google.maps.MarkerImage('http://labs.google.com/ridefinder/images/mm_20_orange.png', new google.maps.Size(13,20));
	
			// create marker for via
			var marker_via = new google.maps.Marker({
				map: map, 
				position: location_via,
				icon: iconOrange,
				draggable: true
			});
			
			google.maps.event.addListener(marker_via, 'dragend', function() {
				location_via = marker_via.getPosition();
				drawRoutes(location1, location2, location_via);
			});
		}
		
		// create the text to be shown in the infowindows
		var text1 = '<div id="content">'+
				'<h1 id="firstHeading">Štart</h1>'+
				'<div id="bodyContent">'+
				'<p>Súradnice: '+location1+'</p>'+
				'<p>Adresa: '+address1+'</p>'+
				'</div>'+
				'</div>';
				
		var text2 = '<div id="content">'+
			'<h1 id="firstHeading">Ciel</h1>'+
			'<div id="bodyContent">'+
			'<p>Súradnice: '+location2+'</p>'+
			'<p>Adresa: '+address2+'</p>'+
			'</div>'+
			'</div>';
		
		// create info boxes for the two markers
		infowindow1 = new google.maps.InfoWindow({
			content: text1
		});
		infowindow2 = new google.maps.InfoWindow({
			content: text2
		});

		// add action events so the info windows will be shown when the marker is clicked
		google.maps.event.addListener(marker1, 'click', function() {
			infowindow1.open(map,marker1);
		});
		google.maps.event.addListener(marker2, 'click', function() {
			infowindow2.open(map,marker2);
		});
		
		// add action events for dragging the markers
		google.maps.event.addListener(marker1, 'dragend', function() {
			location1 = marker1.getPosition();
			drawRoutes(location1, location2, location_via);
		});
		
		google.maps.event.addListener(marker2, 'dragend', function() {
			location2 = marker2.getPosition();
			drawRoutes(location1, location2, location_via);
		});
		
		// initialize directions service
		directionsService = new google.maps.DirectionsService();
		directionsDisplay = new google.maps.DirectionsRenderer(
		{
			suppressMarkers: true,
			suppressInfoWindows: true
		});
		
		directionsDisplay.setMap(map);
		directionsDisplay.setPanel(document.getElementById("directionsPanel"));
		drawRoutes(location1, location2, location_via);
	}
	
	function drawRoutes(location1, location2, location_via)
	{
		// show new addresses
		
		geocoder = new google.maps.Geocoder(); // creating a new geocode object
		
		if (geocoder) 
		{
			geocoder.geocode({'latLng': location1}, function(results, status) 
			{
				if (status == google.maps.GeocoderStatus.OK) 
				{
					if (results[0]) 
					{
						address1 = results[0].formatted_address;
						document.getElementById("address1").value = address1;
					}
				} 
				else 
				{
					alert("Geocoder failed due to: " + status);
				}
			});
		}

		if (geocoder) 
		{
			geocoder.geocode({'latLng': location2}, function(results, status) 
			{
				if (status == google.maps.GeocoderStatus.OK) 
				{
					if (results[0]) 
					{
						address2 = results[0].formatted_address;
						document.getElementById("address2").value = address2;
						if (location_via == "")
						{
							continueShowRoute(location1, location2, location_via);
						}
					}
				} 
				else 
				{
					alert("Geocoder failed due to: " + status);
				}
			});
		}
		
		if (location_via != "")
		{
			if (geocoder) 
			{
				geocoder.geocode({'latLng': location_via}, function(results, status) 
				{
					if (status == google.maps.GeocoderStatus.OK) 
					{
						if (results[0]) 
						{
							via = results[0].formatted_address;
							document.getElementById("address_via").value = via;
							continueShowRoute(location1, location2, location_via);
						}
					} 
					else 
					{
						alert("chyba 2.");
					}
				});
			}
		}
	}
	
	function continueShowRoute(location1, location2, location_via)
	{
		// hide last line
		if (line)
		{
			line.setMap(null);
		}
	
		// show a line between the two points
		line = new google.maps.Polyline({
			map: map, 
			path: [location1, location2],
			strokeWeight: 7,
			strokeOpacity: 0.8,
			strokeColor: "#FFAA00"
		});
		
		// compute distance between the two points
		kmtoll = 0;
		kmfirstclass = 0;
		totalkm = 0;
		var R = 6371; 
		var dLat = toRad(location2.lat()-location1.lat());
		var dLon = toRad(location2.lng()-location1.lng());
		
		var dLat1 = toRad(location1.lat());
		var dLat2 = toRad(location2.lat());
		
		var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
				Math.cos(dLat1) * Math.cos(dLat1) * 
				Math.sin(dLon/2) * Math.sin(dLon/2); 
		var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
		var d = R * c;
		
		var travelmode = "driving";
		travel = google.maps.DirectionsTravelMode.DRIVING;
		if (location_via == "")
		{
			var request = {
			origin:location1, 
			destination:location2,
			travelMode: google.maps.DirectionsTravelMode.DRIVING
			};
			directionsService.route(request, function(response, status) {
			  if (status == google.maps.DirectionsStatus.OK) {
				directionsDisplay.setDirections(response);
				calculateRoute(response);
			  }
			});
			
			directionsService.route(request, function(response, status) 
			{
				if (status == google.maps.DirectionsStatus.OK) 
				{
					totalkm = response.routes[0].legs[0].distance.value/1000;
//					kmtoll = kmtoll;
					time = response.routes[0].legs[0].duration.value;
//					if(document.form.obojsmerne.checked == true){
//						totalkm = totalkm * 2;
//						kmtoll = kmtoll * 2;
//						kmfirstclass = kmfirstclass * 2;
//						time = time * 2;
//					}
					secondsToTime(time);
//					if(time_from > time_to) {
//						time_total = 1440 - time_from + time_to;
//					} else {
//						time_total = time_to - time_from;
//					}
//					cena = totalkm*0.85 + kmtoll*0.10 + kmfirstclass*0.08 + (time_total/60*5);
					document.getElementById("totalkm").innerHTML = Math.round(totalkm);
					document.getElementById("totalkm_hidden").innerHTML = Math.round(totalkm);
					document.getElementById("time_hidden").innerHTML = time;
//					document.getElementById("price_km").innerHTML = Math.round(totalkm*0.85);
//					document.getElementById("price_toll").innerHTML = Math.round(kmtoll*0.10);
//					document.getElementById("price_firstclass").innerHTML = Math.round(kmfirstclass*0.08);
//					document.getElementById("price_stojne").innerHTML = Math.round(time_total/60*5);
//					document.getElementById("price").innerHTML = Math.round(cena);
				}
				else
				{
					alert('error: ' + status);
				}
			});
			
			// update text in infowindows
			var text1 = '<div id="content">'+
					'<h1 id="firstHeading">Štart</h1>'+
					'<div id="bodyContent">'+
					'<p>Súradnice: '+location1+'</p>'+
					'<p>Adresa: '+address1+'</p>'+
					'</div>'+
					'</div>';
					
			var text2 = '<div id="content">'+
				'<h1 id="firstHeading">Ciel</h1>'+
				'<div id="bodyContent">'+
				'<p>Súradnice: '+location2+'</p>'+
				'<p>Adresa: '+address2+'</p>'+
				'</div>'+
				'</div>';
				
			infowindow1.setContent(text1);
			infowindow2.setContent(text2);
			
			share_link(address1, address2, "");
		} 
		else
		{
		// find and show route between the points through via
		var request = {
			origin:location1, 
			destination:location2,
			waypoints: [
			{
			  location: location_via,
			  stopover:false
			}],
			travelMode: travel
		};
		directionsService.route(request, function(response, status) {
			  if (status == google.maps.DirectionsStatus.OK) {
				directionsDisplay.setDirections(response);
				calculateRoute(response);
			  }
			});
			
			directionsService.route(request, function(response, status) 
			{
				if (status == google.maps.DirectionsStatus.OK) 
				{
					totalkm = response.routes[0].legs[0].distance.value/1000;
//					kmtoll = kmtoll;
					time = response.routes[0].legs[0].duration.value;
//					if(document.form.obojsmerne.checked == true){
//						totalkm = totalkm * 2;
//						kmtoll = kmtoll * 2;
//						kmfirstclass = kmfirstclass * 2;
//						time = time * 2;
//					}
					secondsToTime(time);
//					if(time_from > time_to) {
//						time_total = (1440 - time_from) + time_to;
//					} else {
//						time_total = time_to - time_from;
//					}
//					cena = totalkm*0.85 + kmtoll*0.10 + kmfirstclass*0.08 + (time_total/60*5);
					document.getElementById("totalkm").innerHTML = Math.round(totalkm);
					document.getElementById("totalkm_hidden").innerHTML = Math.round(totalkm);
					document.getElementById("time").innerHTML = time;
					document.getElementById("time_hidden").innerHTML = time;
//					document.getElementById("price_km").innerHTML = Math.round(totalkm*0.85);
//					document.getElementById("price_toll").innerHTML = Math.round(kmtoll*0.10);
//					document.getElementById("price_firstclass").innerHTML = Math.round(kmfirstclass*0.08);
//					document.getElementById("price_stojne").innerHTML = Math.round(time_total/60*5);
//					document.getElementById("price").innerHTML = Math.round(cena);
				}
				else
				{
					alert('Pri výpočte ceny nastala chyba: ' + status);
				}
			});
		
		var text1 = '<div id="content">'+
			'<h1 id="firstHeading">Štart</h1>'+
			'<div id="bodyContent">'+
			'<p>Súradnice: '+location1+'</p>'+
			'<p>Adresa: '+address1+'</p>'+
			'</div>'+
			'</div>';
			
		var text2 = '<div id="content">'+
			'<h1 id="firstHeading">Ciel</h1>'+
			'<div id="bodyContent">'+
			'<p>Súradnice: '+location2+'</p>'+
			'<p>Adresa: '+address2+'</p>'+
			'</div>'+
			'</div>';
			
		infowindow1.setContent(text1);
		infowindow2.setContent(text2);
		
		share_link(address1, address2, via);
	}
		
		da = true;
	}
	
	function secondsToTime(secs)
	{
		var hodiny = Math.floor(secs / (60 * 60));
	   
		var zvysok_minut = secs % (60 * 60);
		var minuty = Math.floor(zvysok_minut / 60);
		
		time = hodiny+" hod "+minuty+" min";
	}
	
	function toDate(date) {
		var now = new Date();
		var x;
		if(date == "hh:mm" || date == ""){
			alert("Nezadali ste čas státia a preto vypočítaná cena nebude správna!");
			x = 0;
		} else {
			now.setHours(date.substr(0,date.indexOf(":")));
			now.setMinutes(date.substr(date.indexOf(":")+1));
			now.setSeconds(0);
			x = now.getHours()*60 + now.getMinutes();
		  }
		return x;
	}
	
	function calculateRoute(directionResult) 
	{
		var myRoute = directionResult.routes[0].legs[0];
		for(var i = 0; i < myRoute.steps.length; i++) 
		{
			  var a = myRoute.steps[i].instructions;
			  var alower=a.toLowerCase();
			  var poc_toll;
			  if(poc_toll = alower.indexOf('spoplatnen')) 
			  {
				  if (poc_toll > -1) 
				  {
					kmtoll+=myRoute.steps[i].distance.value/1000;
				  }
			  }
			  poc_toll = alower.search(/cesta [0-9][0-9]/);
				  if (poc_toll > -1) 
				  {
					kmfirstclass+=myRoute.steps[i].distance.value/1000;
				  }
		}
	}
	
	function toRad(deg) 
	{
		return deg * Math.PI/180;
	}
	
	init();