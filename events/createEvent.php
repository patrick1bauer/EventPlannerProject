<?php
// Close connection
$conn = null;
// Initialize the session
session_start();

include('../events/listEvents.php');
include('../helper.php');

// Check if the user is logged in, if not then redirect them to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true)
{
	header("location: http://www.eventplannerproject.com/EventPlannerProject/login.php");
	exit;
}

navbar();

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


// Get user's credentials
$arr = json_decode(stripslashes($_COOKIE['login']), true);
$myid = $arr['uid'];
$name = $arr['name'];
$super_admin = $arr['super_admin'];

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
	{
		
		echo("
		<!DOCTYPE html>
		<html>
			<head>
				<title>Search Events</title>
				<style>
					table,td
					{
						border: 1px solid grey;
					}
						th{
					background-color: grey;
					color: white;
					}
					tr:nth-child(even){
					background-color: #e0e0e0;
					color: black;
					}
			</style>
				<meta charset='UTF-8'>
				<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css'>
				<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js'></script>
				<style type='text/css'>
					body{ font: 14px sans-serif; }
					.wrapper{ width: 350px; padding: 20px; }
				</style>
			</head>
			<body>
				<div class='container'>
					<div class='jumbotron'>
						<h1 style='fontSize=21;'>Success, your event is in!</h1>
						<p>Return to the Admin Interface to continue.
						</p>
					</div>
					
					<div class='container'>
						<p>
							<div>
							<?php echo resetPassword() ?>
							<?php echo signOut() ?>
							</div>
						</p>
					</div>
				</div>
				<div class='container'>
					<button class='btn btn-primary mb-2' onclick=\"location.href='../interfaces/adminInterface.php'\" type='button'>Back to Admin Interface</button>
				</div>
			</body	
		</html>");
	
	

	}
	
	//"<br><button onclick=\"location.href='../interfaces/adminInterface.php'\" type=\"button\">Back to Admin Interface</button>");
}

?>
