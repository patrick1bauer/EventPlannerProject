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
        <title>Login</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <style type="text/css">
            body{ font: 14px sans-serif; }
            .wrapper{ width: 350px; padding: 20px; }
        </style>
    </head>
	<body>
		<p>
			<a href="../reset-password.php" class="btn btn-warning">Reset Password</a>
			<a href="../logout.php" class="btn btn-danger">Sign Out</a>
		</p>
		<h1>Admin Interface</h1>
		<h1>Hi, <b><?php echo htmlspecialchars($_SESSION["name"]); ?></b>. Welcome to the Admin Interface!</h1>
		<p>Here you can...
			<ul>
				<li>View a list of the title and the URL of all the events you have organized.</li>
				<li>Toggle the display list to only display active events</li>
				<li>Create a new event</li>
			</ul>
		</p>

		<h2>Have an event?</h2>
		<button onclick="location.href='createEventForm.php'" type="button">Create an Event</button>

		<h2>List of Events You've Organized...</h2>
		<input 
			type='checkbox' 
			name='my_checkbox'
        	onclick="check(this);" >
		<label for='my_checkbox'>List only active events</label>
		<p></p>
		<?php
		// Get the list of the events belonging to user
		getList($myid);
		?>

	</body>

	<script>
		function check(cb)
		{
			var checked = cb.checked;

			var table = document.getElementById("ListEvents");
			var tr = table.getElementsByTagName("tr");

			// Loop through all table rows, and hide those who don't match the search query
			for (i = 1; i < tr.length; i++) {
				td = tr[i].getElementsByTagName("td")[2];
				if(!checked)
					tr[i].style.display = "";
				else if (td) 
				{
					var txtValue = td.textContent || td.innerText;
					if (txtValue.indexOf('true') > -1) {
						tr[i].style.display = "";
				} 
				else 
				{
					tr[i].style.display = "none";
				}
				}
			}
		}
	</script>
</html>