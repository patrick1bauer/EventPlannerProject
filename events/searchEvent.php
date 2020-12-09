<?php
ini_set('display_errors', '1');

if(isset($_POST['search']))
{
    $input_S_Date = $_POST['input_S_Date'];
    $input_E_Date = $_POST['input_E_Date'];
    // search in all table columns
    // using concat mysql function
    $query = "SELECT * FROM events WHERE startDate between '".$input_S_Date."' AND '".$input_E_Date."' ORDER BY startDate";
    $search_result = filterTable($query);
    
}
else if(isset($_POST['searchC']))
{
    $input_City = $_POST['input_City'];
	$current_Date = date("Y-m-d H:i:s"); // date("Y-m-d H:i:s") finds the current date and returns it in the MySQL DATETIME format

	// finds all entries that take place at the chosen city and then farther narrows it down by which ones are currently ongoing
    $query = "SELECT * 
			  FROM (SELECT * 
					FROM events 
					WHERE city = '".$input_City."'
					) AS Temp 
			  WHERE '".$current_Date."' between Temp.startDate AND Temp.endDate";

    $search_result = filterTable($query);
}
else
{
    // by default, show all events if no search parameters have been entered yet 
    $query = "SELECT * FROM `events`";
    $search_result = filterTable($query);
} 


// function to connect and execute the query
function filterTable($query)
{
	// credentials for connecting to the database
    $servername = "localhost";
	$username = "cop4710user";
	$password = "cop4710password";
	$dbname = "cop4710u_dbms";
	
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	
	if (!$conn) {
	  die("Connection failed: " . mysqli_connect_error());
	}
	
    $filter_Result = mysqli_query($conn, $query) or die( mysqli_error($conn));
    return $filter_Result;
}

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

// Get user's credentials
$arr = json_decode(stripslashes($_COOKIE['login']), true);
$myid = $arr['uid'];
$name = $arr['name'];
$super_admin = $arr['super_admin'];

?>

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
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <style type="text/css">
            body{ font: 14px sans-serif; }
            .wrapper{ width: 350px; padding: 20px; }
        </style>
    </head>
	<body>
		<div class="container">
			<div class="jumbotron">
				<h1>Welcome to the Search Interface!</h1>
				<p>Here you can...
					<ul>
						<li>Search event by start and end time.</li>
					</ul>
				</p>
			</div>
			
			<div class="container">
				<p>
					<div>
					<?php echo resetPassword() ?>
					<?php echo signOut() ?>
					</div>
				</p>
			</div>
		</div>
		<div class="container">
            <h2>Search event by start and end time</h2>
            <form action="searchEvent.php" method="post">
                    
            <label>Start Date</label>
            <input  class="form-control" type=date name="input_S_Date">
            <input  class="form-control" type=time name="input_S_Time">
            <br></br>
            <label>End Date </label>
            <input class="form-control"  type=date name="input_E_Date">
            <input class="form-control"  type=time name="input_E_Time">
            <br>
            <input  class="btn btn-primary mb-2" type="submit" name=search value="Show Events">
            <br></br>
            
            <h2> Or search currently ongoing events by city</h2>
            <input class="form-control"  type=text name="input_City"><br>
            <input  class="btn btn-primary mb-2" type="submit" name=searchC value="Show Events">
            <br></br>
            
            
            <table class="table">
            <tr>
                <th>Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>url</th>
            </tr>

            <!-- populate table from mysql database -->
            <?php while($row = mysqli_fetch_array($search_result)):?>
                <tr>
                    <td><?php echo $row['name'];?></td>
                    <td><?php echo $row['startDate'];?></td>
                    <td><?php echo $row['endDate'];?></td>
                    <td><?php echo $row['url'];?></td>
                </tr>
            <?php endwhile;?>
            </table>
            </form>
        </div>
    </body>
</html>
