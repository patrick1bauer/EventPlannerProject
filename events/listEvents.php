<?php
// Loads database config
include('../connection.php');


// Returns a html table with a list of events belonging to the id entered
function getList($id){

	// Create connection
	$conn = dbConnection();

	// Get events belonging to the $id
	$result = $conn->prepare("");
	$listRequest = $conn->prepare("SELECT * FROM events WHERE admin='$id'");
	$listRequest->execute();

	// Check if insertEvent was successful
	if(!$listRequest)
		echo('Error listing events!');
	else
	{
		// Begin Table
		echo "<table id=\"ListEvents\" class='table'>
				<tr>
					<th>Title</th>
					<th>URL</th>
					<th style=\"display:none\">Active</th>
				</tr>";
		
		// Record current time for comparisons
		$date_now = new DateTime("now", new DateTimeZone('America/New_York'));
		
		// List out each result from query
		foreach ($listRequest as $row) {
			$startDate = new DateTime($row['startDate']);
			$endDate = new DateTime($row['endDate']);
			$active = false;
			if($date_now >= $startDate && $date_now < $endDate)
				$active = true;

			echo "<tr>";
			echo "<td>".$row['name']."</td>";
			echo "<td>".$row['url']."</td>";
			echo "<td style=\"display:none\">" . ($active ? 'true' : 'false') . "</td>";
			echo "</tr>";
		}

		echo "</table>";
	}

	// Close connection
	$conn = null;
}
?>
