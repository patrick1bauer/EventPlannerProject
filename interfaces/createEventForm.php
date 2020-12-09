<?php
// Initialize the session
session_start();

include '../events/listEvents.php';
include('../helper.php');

// Check if the user is logged in, if not then redirect them to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true)
{
	header("location: ../login.php");
	exit;
}

navbar();

// Get user's credentials
$arr = json_decode(stripslashes($_COOKIE['login']), true);
$myid = $arr['uid'];
$name = $arr['name'];
$super_admin = $arr['super_admin'];


?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Create an Event</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <style type="text/css">
            body{ font: 14px sans-serif; }
            .wrapper{ width: 350px; padding: 20px; }
        </style>
    </head>
	<body>
	
		<div class="container">
				<p>
					<div>
					<?php echo resetPassword() ?>
					<?php echo signOut() ?>
					</div>
				</p>
		</div>
		<div class="container">
			<h1>Create an Event</h1>
			<div class="form-group">
				<form action="../events/createEvent.php" method="post">
					Name: <input type="text" class="form-control" name="name" /><br><br>
					<?php
						echo ("<input type=\"hidden\" name=\"admin\" value='$myid'/>");
					?>
					State: <input type="text" class="form-control" name="state" /><br><br>

					City: <input type="text" class="form-control" name="city" /><br><br>

					Zip: <input type="text" class="form-control" name="zip" /><br><br>

					Street: <input type="text" class="form-control" name="street" /><br><br>

					Description: <input type="text" class="form-control" name="description" /><br><br>

					StartDate: <input type="datetime-local" class="form-control" name="startDate" /><br><br>

					EndDate: <input type="datetime-local" class="form-control" name="endDate" /><br><br>

					URL: <input type="url" class="form-control" name="url" /><br><br>
					<input type="submit"  class="btn btn-primary mb-2"/>
					<input type="button" value="Go Back"  class="btn btn-warning mb-2" onclick="location.href='adminInterface.php'" />
				</form>
			</div>
		</div>
	</body>
</html>