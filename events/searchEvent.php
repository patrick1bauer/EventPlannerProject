<?php

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
 else { // by default, show all events if no search parameters have been entered yet 
    $query = "SELECT * FROM `events`";
    $search_result = filterTable($query);
} 


// function to connect and execute the query
function filterTable($query)
{
	// credentials for connecting to the database
    $servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "dbms";
	
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	
	if (!$conn) {
	  die("Connection failed: " . mysqli_connect_error());
	}
	
    $filter_Result = mysqli_query($conn, $query) or die( mysqli_error($conn));
    return $filter_Result;
}

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
    </head>
    <body>
		<h2> Search event by start and end time</h2>
    <form action="searchEvent.php" method="post">
			
    <label>Start Date</label>
    <input type=date name="input_S_Date">
    <input type=time name="input_S_Time">
    <br></br>
    <label>End Date </label>
    <input type=date name="input_E_Date">
    <input type=time name="input_E_Time">
    <br></br>
    <input type="submit" name=search value="Show Events">
    <br></br>
	
	<h2> Or search currently ongoing events by city</h2>
	<input type=text name="input_City">
    <input type="submit" name=searchC value="Show Events">
	<br></br>
	
	
    <table>
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
        
    </body>
</html>
