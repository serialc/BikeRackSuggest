<?php
// filename: brs_sub.php
// gets BRS submission and adds it to data, return success message

date_default_timezone_set('UTC');

# load the class and object $dh
include('data_handler.php');

print(json_encode($dh->get_suggestions()));

?>
