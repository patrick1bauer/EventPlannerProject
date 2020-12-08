<?php
// Loads database config
include('../connection.php');

$name = $_POST['name'];
$admin = $_POST['admin'];
$state = $_POST['state'];
$city = $_POST['city'];
$zip = $_POST['zip'];
$street = $_POST['street'];
$description = $_POST['description'];
$startDate = $_POST['startDate'];
$endDate = $_POST['endDate'];
$url = $_POST['url'];

// Create connection
$conn = dbConnection();

// Insert into events table
$result = $conn->prepare("");
$insertEvent = $conn->prepare("INSERT INTO events (name, admin, state, city, zip, street, description, startDate, endDate, url) 
VALUES ('$name', '$admin', '$state', '$city', '$zip', '$street', '$description', '$startDate', '$endDate', '$url')");
$insertEvent->execute();

// Get the new ID of the events table entry
$eid = $conn->lastInsertId();

// Check if insertEvent was successful
if(!$insertEvent)
	echo('Error inserting event!');
else
{
	// Insert into the requesteventcreate table
	$insertEventRequest = $conn->prepare("INSERT INTO requesteventcreate (eid, uid) VALUES ('$eid', '$admin')");
	$insertEventRequest->execute();
	// Check if insert was successful
	if(!$insertEventRequest)
		echo('Error requesting to join event!');
	else
		echo("Successfully requested to create event!<br><button onclick=\"location.href='../interfaces/adminInterface.php'\" type=\"button\">Back to Admin Interface</button>");
}

// Close connection
$conn = null;
?>
