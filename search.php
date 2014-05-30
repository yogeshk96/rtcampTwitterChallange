<?php
session_start();
$list = json_decode($_SESSION['list']);
$term = $_POST['term'];
echo '<table class="table table-hover" id="sample-table-1">
						 <tbody>';
foreach ($list as $key => $value) {
	if($term != "") {
		if(stripos($value, $term) !== false){ //if match found
	           
	           echo "<tr class='fList'><td class='fListInner'>".$value."<div class='fListHidden' style='display:none;'>".$key."</div></td></tr>";
		}
	}
	else {
		if($i <= 10) { // restricting the search result to 10 if user erases the typed values

		echo "<tr class='fList'><td class='fListInner'>".$value."<div class='fListHidden' style='display:none;'>".$key."</div></td></tr>";
		$i++;
	    }
	}
}
echo '</tbody></table>';


?>