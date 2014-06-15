<?php 
	define("ABSPATH", str_replace("wp-content/plugins/garees-twitter-stream", "", dirname(__FILE__)));
	
	//The inclusion of these files allows full use of all functions of wordpress
	require_once(ABSPATH.'wp-load.php');
	require_once(ABSPATH.'wp-admin/includes/admin.php');
	
?>
<html>
<head>
<?php
	echo '<link rel="stylesheet" id="garees-admin-css"  href="' . plugins_url('garee_admin_thickbox.css', __FILE__) . '" type="text/css" media="all" />'. "\n";
	echo '<link rel="stylesheet" id="admin-css"  href="' . admin_url() . 'css/global.css" type="text/css" media="all" />'. "\n";
	echo '<link rel="stylesheet" id="admin-css"  href="' . admin_url() . 'css/wp-admin.css" type="text/css" media="all" />'. "\n";
	?>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?libraries=geometry&sensor=false"></script>
<script type="text/javascript">

var geocoder;
var map;

function initialize() {
	geocoder = new google.maps.Geocoder();
	var latlng = new google.maps.LatLng(44.490, -78.649);
	var myOptions = {
		zoom: 8,
		center: latlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	}
	map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
}

function codeAddress() {
	var address = document.getElementById("address").value;
	geocoder.geocode( { 'address': address}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			map.setCenter(results[0].geometry.location);
			var marker = new google.maps.Marker({
				map: map, 
				position: results[0].geometry.location
			});
		} else {
			alert("Geocode was not successful for the following reason: " + status);
		}
	});
}

var circle;

function addCircle() {
	
	if (circle != null)
		removeCircle();
	
	var center = new google.maps.LatLng(44.490, -78.649);
	var radius = google.maps.geometry.spherical.computeDistanceBetween(map.getBounds().getSouthWest(),map.getCenter())/3;
	
	circle = new google.maps.Circle({
		center: map.getCenter(),
		//radius: 5000,
		radius: radius,
		editable: true
	});
	
	circle.setMap(map);
	
	google.maps.event.addListener(circle, 'center_changed', function() {
		var shortcode = 'geocode = "' +
			  +	circle.getCenter().lat() + ','
			  + circle.getCenter().lng() + ','
			  + calculateShortcodeRadiusFromMeter(circle.getRadius())
			  + getCheckedValue("units") + '"';	
		document.getElementById("shortcode").value = shortcode;
		document.getElementById("lattitude").value = circle.getCenter().lat();
		document.getElementById("longitude").value = circle.getCenter().lng();
		document.getElementById("radius").value = calculateShortcodeRadiusFromMeter(circle.getRadius());
    });
	google.maps.event.addListener(circle, 'radius_changed', function() {
		var shortcode = 'geocode = "' +
			  +	circle.getCenter().lat() + ','
			  + circle.getCenter().lng() + ','
			  + calculateShortcodeRadiusFromMeter(circle.getRadius())
			  + getCheckedValue("units") + '"';		
		document.getElementById("shortcode").value = shortcode; 
		document.getElementById("lattitude").value = circle.getCenter().lat();
		document.getElementById("longitude").value = circle.getCenter().lng();
		document.getElementById("radius").value = calculateShortcodeRadiusFromMeter(circle.getRadius());
    });
	var shortcode = 'geocode = "' +
		  +	circle.getCenter().lat() + ','
		  + circle.getCenter().lng() + ','
		  + calculateShortcodeRadiusFromMeter(circle.getRadius())
		  + getCheckedValue("units") + '"';	
	document.getElementById("shortcode").value = shortcode; 
	document.getElementById("lattitude").value = circle.getCenter().lat();
	document.getElementById("longitude").value = circle.getCenter().lng();
	document.getElementById("radius").value = calculateShortcodeRadiusFromMeter(circle.getRadius());
}
function removeCircle() {
	circle.setMap(null);
	circle = null;
	document.getElementById("shortcode").value = ""; 
	document.getElementById("lattitude").value = "";
	document.getElementById("longitude").value = "";
	document.getElementById("radius").value = "";
}
function updateCircle() {
	circle.setOptions({
		center: new google.maps.LatLng(document.getElementById("lattitude").value, document.getElementById("longitude").value),
		radius: calculateGoogleRadiusFromForm(document.getElementById("radius").value),
	});
}
function calculateShortcodeRadiusFromMeter(r) {
	if (getCheckedValue("units") == "km") {
		return r/1000;
	} else {
		return r/1609.344;
	}
}
function calculateGoogleRadiusFromForm(r) {
	if (getCheckedValue("units") == "km") {
		return r*1000;
	} else {
		return r*1609.344;
	}
}
function switchToKm() {
	document.getElementById("radius").value = document.getElementById("radius").value*1.609344;
var shortcode = 'geocode = "' +
		  +	circle.getCenter().lat() + ','
		  + circle.getCenter().lng() + ','
		  + calculateShortcodeRadiusFromMeter(circle.getRadius())
		  + getCheckedValue("units") + '"';	
	document.getElementById("shortcode").value = shortcode; 	
}

function switchToMi() {
	document.getElementById("radius").value = document.getElementById("radius").value/1.609344;
	var shortcode = 'geocode = "' +
		  +	circle.getCenter().lat() + ','
		  + circle.getCenter().lng() + ','
		  + calculateShortcodeRadiusFromMeter(circle.getRadius())
		  + getCheckedValue("units") + '"';	
	document.getElementById("shortcode").value = shortcode; 
}

// return the value of the radio button that is checked
// return an empty string if none are checked, or
// there are no radio buttons
function getCheckedValue(radioObjName) {
	radioObj = document.getElementsByName(radioObjName);
	var radioLength = radioObj.length;
	for(var i = 0; i < radioLength; i++) {
		if(radioObj[i].checked) {
			return radioObj[i].value;
		}
	}
	return "";
}
</script>
</head>
<body onload="initialize()">

<div id="gareeMain">
  <div style="position:absolute;top:0px;left:0px;width:200px;bottom:0px;padding:10px;">
  <p>Step 1: find place</p>
    <input id="address" type="textbox" value="enter place">
    <input type="button" value="find" onclick="codeAddress()">
    <p>Step 2: add circle</p>
    <input type="button" value="add circle" onclick="addCircle()">
    <input type="button" value="remove circle" onclick="removeCircle()">
    <p>Step 3: adjust circle by moving and resizing or entering values directly:</p>
    <input id="lattitude" type="textbox" value="" onblur="updateCircle()"> lattitude
    <input id="longitude" type="textbox" value="" onblur="updateCircle()"> longitude
    <input id="radius" type="textbox" value="" onblur="updateCircle()"> radius
    units: <input name="units" type="radio" value="km" checked onchange="switchToKm()"> kilometres <input name="units" type="radio" value="mi" onchange="switchToMi()"> miles
    <p>Step 4: copy shortcode-options</p>
    <textarea id="shortcode" cols="30" rows="4"></textarea>
  </div>
  <div id="map_canvas" style="position:absolute;left:220px;right:0px;top:0px;bottom:0px;"></div>
</div>

</body>
</html>