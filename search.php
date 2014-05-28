<?php
session_start();
$list = json_decode($_SESSION['list']);
$term = $_POST['term'];
echo '<table class="table table-hover" id="sample-table-1">
						 <tbody>';
foreach ($list as $key => $value) {
	if($term != "") {
		if(stripos($value, $term) !== false){
	           
	           echo "<tr><td class='fList'>".$key."</td><tr>";
		}
	}
	else {
		echo "<tr><td class='fList'>".$key."</td><tr>";
	}
}
echo '</tbody></table>';


?>