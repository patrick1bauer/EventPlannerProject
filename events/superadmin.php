<?php
session_start();
include('../config.php');
include('../helper.php');


$mysqli = mysqli_connect($GLOBALS['database_host'], $GLOBALS['database_user'], $GLOBALS['database_pass'], $GLOBALS['database_name']);
// Check connection
if ($mysqli -> connect_errno) {
  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
  exit();
}



function print_table($result, $mysqli) {
  if ($result -> num_rows > 0) {
    print <<<EOF
      <table class="table table-striped table-hover table-responsive">
        <tr>
          <td>Admin</td>
          <td>Event Name</td>
          <td>Date</td>
          <td>State</td>
          <td>City</td>
          <td>Street</td>
          <td>Website</td>
          <td></td>
        </tr>
      EOF;
    while ($row = mysqli_fetch_assoc($result)) {
      $admin = $row['admin'];
      $user_name = getOne("SELECT name FROM user WHERE uid=$admin", $mysqli);
      $eid = $row['eid'];
      $event_name = $row['name'];
      $event_start = $row['startDate'];
      $event_end = $row['endDate'];
      $event_state = $row['state'];
      $event_city = $row['city'];
      $event_street = $row['street'];
      $event_url = $row['url'];
      print <<<EOF
      <tr>
        <td>$user_name</td>
        <td>$event_name</td>
        <td>$event_start - $event_end</td>
        <td>$event_state</td>
        <td>$event_city</td>
        <td>$event_street</td>
        <td>$event_url</td>
      </tr>
      EOF;
    }
    print <<<EOF
      </table>
    EOF;
  } else {
    print <<<EOF
    <div class="alert alert-info">
      <strong>No results.</strong>
    </div>
    EOF;
  }
}

/*
// Set user
$arr = array(
  "uid" => 1,
  "name" => 'David',
  "super_admin" => true
);
setcookie("login", json_encode($arr), time() + 3600);
*/

// Check if user is logged in.  If not, kick to home.
if(!isset($_COOKIE['login'])) {
  header("Location: home.html");
}

// Get user's credentials
$arr = json_decode(stripslashes($_COOKIE['login']), true);
$myid = $arr['uid'];
$name = $arr['name'];
$super_admin = $arr['super_admin'];

// If user is not logged in or is not a superadmin, kick to homepage.
if ($super_admin == 0) {
  header("Location: home.html");
}


print <<<EOF
<html>
<body>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
EOF;

// List events organized by a particular Admin
// Search bar
print <<<EOF
  <div class="page-header">
    <h1>Events By Admin</h1>
  </div>
  <form class="form-inline" action="superadmin.php" method="post">
      <input type="search" class="form-control" name="search_event_admin" placeholder='Search by name' />
      <button type="submit" class="btn btn-default">search</button>
  </form>
EOF;
if (isset($_POST['search_event_admin'])) {
  $_SESSION['search_event_admin'] = $_POST['search_event_admin'];
  $result = query("SELECT * FROM events WHERE admin=(SELECT uid FROM user WHERE name='". $_POST['search_event_admin'] ."')", $mysqli);
} else if (isset($_SESSION['search_event_admin'])) {
  $result = query("SELECT * FROM events WHERE admin=(SELECT uid FROM user WHERE name='". $_SESSION['search_event_admin'] ."')", $mysqli);
} else {
  $result = query("SELECT * FROM events", $mysqli);
}
// Events by Admin table
print_table($result, $mysqli);



// List events participated by a particular User
// Search bar
print <<<EOF
  <div class="page-header">
    <h1>Events Joined By User</h1>
  </div>
  <form class="form-inline" action="superadmin.php" method="post">
      <input type="search" class="form-control" name="events_joined_user" placeholder='Search by name' />
      <button type="submit" class="btn btn-default">search</button>
  </form>
EOF;


if (isset($_POST['events_joined_user'])) {
  $_SESSION['events_joined_user'] = $_POST['events_joined_user'];
  $result1 = query("SELECT E.eid as eid, E.name as name, E.admin as admin, E.state as state,
    E.city as city, E.zip as zip, E.street as street, E.description as description,
    E.startDate as startDate, E.endDate as endDate, E.url as url, U.uid as uid
    FROM events E, user U, eventsjoined EJ
    WHERE U.name='". $_SESSION['events_joined_user'] ."' && U.uid=EJ.uid && E.eid=EJ.eid", $mysqli);
} else if (isset($_SESSION['events_joined_user'])) {
  $result1 = query("SELECT E.eid as eid, E.name as name, E.admin as admin, E.state as state,
    E.city as city, E.zip as zip, E.street as street, E.description as description,
    E.startDate as startDate, E.endDate as endDate, E.url as url, U.uid as uid
    FROM events E, user U, eventsjoined EJ
    WHERE U.name='". $_SESSION['events_joined_user'] ."' && U.uid=EJ.uid && E.eid=EJ.eid", $mysqli);
} else {
  $result = query("SELECT * FROM events", $mysqli);
}

// Events user participated in table
print_table($result1, $mysqli);

print <<<EOF
</body>
</html>
EOF;

?>
