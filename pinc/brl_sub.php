<?php
// filename: brl_sub.php
// gets BR location submission and adds it to data, return success message

date_default_timezone_set('UTC');

# get the varaibles and sanitize them
$name = preg_replace("/[^a-zA-Z\s]/", '', $_GET['brl_name'] );
$covered = preg_replace("/[^01]/", '', $_GET['brl_cover'] );
$capacity = preg_replace("/[^0-9]/", '', $_GET['brl_cap'] );
$lat = preg_replace("/[^0-9\.\-]/", '', $_GET['brl_lat'] );
$lng = preg_replace("/[^0-9\.\-]/", '', $_GET['brl_lng'] );

// check if the coordinates are within the bounds of lux (roughly)
if ( $lat < 49.56107 or $lat > 49.6554 or $lng < 6.06943 or $lng > 6.20316 ) {
	print('Your bike rack location is outside of Luxembourg City.');
	return;
}

// check that there is a name (everything else is optional)
if ( $name == '' ) {
	print('Please provide your name.');
	return;
}

// create new suggestion location
// create an object to manage the file/data system

include('data_handler.php');

if ( $dh->add_bike_rack(array("lat" => $lat, "lng" => $lng, "name" => $name, "cap" => $capacity, "cov" => $covered)) ) {
	print("Success!");
} else {
	print("Failure!");
}

?>
