<?php
// filename: brs_sub.php
// gets BRS submission and adds it to data, return success message

date_default_timezone_set('UTC');

# get the varaibles and sanitize them
$name = preg_replace("/[^a-zA-Z\s]/", '', $_GET['brs_name'] );
$comment = preg_replace("/[^a-zA-Z0-9\.!?\-\s]/", '', $_GET['brs_comment'] );
$lat = preg_replace("/[^0-9\.\-]/", '', $_GET['brs_lat'] );
$lng = preg_replace("/[^0-9\.\-]/", '', $_GET['brs_lng'] );

// check if the coordinates are within the bounds of lux (roughly)
if ( $lat < 49.56107 or $lat > 49.6554 or $lng < 6.06943 or $lng > 6.20316 ) {
	print('Your suggested location is outside of Luxembourg City.');
	return;
}

// check that there is a name (comment is optional)
if ( $name == '' ) {
	print('Please provide your name.');
	return;
}

// create new suggestion location
// create an object to manage the file/data system

include('data_handler.php');

if ( $dh->add_suggestion(array("lat" => $lat, "lng" => $lng, "name" => $name, "com" => $comment)) ) {
	print("Success!");
} else {
	print("Failure!");
}

?>
