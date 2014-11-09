<?php 
//error_reporting(0);
require('dynamodb_access.php');
$cities = array("Newark", "New York", "Edison NJ", "Paterson NJ");
$location = array();
if (isset($_POST['submit'])) {
if (isset($_POST['keyword']) && $_POST['keyword'] != "") {
	$tweets = getTweetsForKeyword($_POST['keyword']);
	foreach($tweets as $value) {
		$tweet = urlencode($value['tweet']);
		$html_value = rawurlencode($value['location']);
		$url = "https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyDfby5dB1ozbr4SpcOupe68TNiw_1PTcX8&address=$html_value&sensor=false";
		$get_map = file_get_contents($url);
		$google_map = json_decode($get_map);
		$lat = $google_map->results[0]->geometry->location->lat;
		$long = $google_map->results[0]->geometry->location->lng;
		$location[] = array($tweet,$lat,$long);
	}
}

elseif (isset($_POST['keywordsList']) && $_POST['keywordsList'] != "")  {
	$tweets = getTweetsForKeyword($_POST['keywordsList']);
	foreach($tweets as $value) {
		$tweet = urlencode($value['tweet']);
		$html_value = rawurlencode($value['location']);
		$url = "https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyDfby5dB1ozbr4SpcOupe68TNiw_1PTcX8&address=$html_value&sensor=false";
		$get_map = file_get_contents($url);
		$google_map = json_decode($get_map);
		$lat = $google_map->results[0]->geometry->location->lat;
		$long = $google_map->results[0]->geometry->location->lng;
		$location[] = array($tweet,$lat,$long);
	}
}
}

?>
	<html>
		<head>
		<title>twittMap v1.0</title>
		<script type = "text/javascript" src = "http://maps.googleapis.com/maps/api/js?sensor=false"></script>
		<script type = "text/javascript" src = "js/map.js"></script>
		<link rel = "stylesheet" type = "text/css" href = "css/style.css" />
		<script type = "text/javascript">
			var array = JSON.parse('<?php echo json_encode($location);?>');
		</script>
		</head>

		<body onload = "initialize(array)">
			<div id = "header">
				<form action = "" method = "POST">
					<select name = "keywordsList">
						<option value = ""></option>
						<?php
						$keywords = getAllKeywords();
						foreach ($keywords as $value) {
							# code...
							var_dump($value['keyword']);
							echo '<option value="'.$value['keyword'].'">'.$value['keyword'].'</option>';
						}
						?>
					</select>
					<input type = "text" name = "keyword" /><br/>
					<input type = "submit" name = "submit" value = ">>" />
				</form> 
			</div>
			<div id = "map_canvas" style = "height:100%; width:100%">
			</div>
		</body>
	</html>
