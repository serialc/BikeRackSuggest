<?php

class datah {
	
	# define private/public attributes here
	private $suggs = 'uninitialized';
	private $data_path = '../data/brs.json';

	# loads the data into an attribute
	public function __construct() {	
		# open data
		$data_json = file_get_contents($this->data_path);

		# convert from json string to php array
		$this->suggs = json_decode($data_json, true);
	}

	# get all the suggestions
	public function get_suggestions() {
		return $this->suggs['bike_rack_suggestions'];
	}

	# chack if a station at these coords already exists
	# if so append the name, and comments and save the data
	private function check_station_exists( $new_brs ) {
		
		# iterate through BRSs, note that we are modifying the original array
		foreach ( $this->suggs['bike_rack_suggestions'] as &$brs ) {
			# check if this suggestion matches with the new
			if ( $brs['lat'] == $new_brs['lat'] && $brs['lng'] == $new_brs['lng'] ) {
				# add the name and comment to the object attribute array
				array_push($brs['voters'], array( "name" => $new_brs['name'], "comment" => $new_brs['com'] ) );

				# update the server data
				$this->update_data_list();

				# return true to let the add_suggestion function know that we have already added the data
				return true;
			}
		}

		# did not find a suggestion at same location, must be new
		return false;
	}

	# update the data based on the object attribute $this->suggs
	private function update_data_list() {
		# open, write to file, close
		$fh = fopen($this->data_path, 'w');
		# check if there were problems writing to the file
		if ( !fwrite($fh, json_encode($this->suggs)) ) {
			print('Unable to write to disk');
		}

		fclose($fh);
	}

	# add a bike rack suggestion
	public function add_suggestion($new_brs) {

		# check if there is an existing station with the same lat/lng
		if ( $this->check_station_exists($new_brs) ) {
			# data update has been handled by the function
			return true;
		}

		# prepare the new data
		$brs_pckg = array(
			"lat" => $new_brs['lat'],
			"lng" => $new_brs['lng'],
			"voters" => array(
				array(
					"name" => $new_brs['name'],
					"comment" => $new_brs['com']
				)
			)
		);

		# update array and local variable, add the new suggestion to the list
		array_push($this->suggs['bike_rack_suggestions'], $brs_pckg);

		$this->update_data_list();

		return true;
	}
}

$dh = new datah();

?>
