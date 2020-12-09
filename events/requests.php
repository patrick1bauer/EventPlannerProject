<?php
include('../config.php');
include('../helper.php');

session_start();

$mysqli = mysqli_connect($GLOBALS['database_host'], $GLOBALS['database_user'], $GLOBALS['database_pass'], $GLOBALS['database_name']);
// Check connection
if ($mysqli -> connect_errno) {
  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
  exit();
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
  header("Location: http://www.eventplannerproject.com/EventPlannerProject/events/home.php");
}



// Get user's credentials
$arr = json_decode(stripslashes($_COOKIE['login']), true);
$myid = $arr['uid'];
$name = $arr['name'];
$super_admin = $arr['super_admin'];


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

navbar();

// Alerts
if (isset($_GET['eventinvite']))
if ($_GET['eventinvite'] == "false") {
  print <<<EOF
  <div class="alert alert-success">
    <strong>Success!</strong> Event invitation rejected successfully.
  </div>
  EOF;
} else if ($_GET['eventinvite'] == "true") {
  print <<<EOF
  <div class="alert alert-success">
    <strong>Success!</strong> Event invitation accepted successfully.
  </div>
  EOF;
}
if (isset($_GET['eventcreate']))
if ($_GET['eventcreate'] == "true") {
  print <<<EOF
  <div class="alert alert-success">
    <strong>Success!</strong> Event creation accepted successfully.
  </div>
  EOF;
} else if ($_GET['eventcreate'] == "false") {
  print <<<EOF
  <div class="alert alert-success">
    <strong>Success!</strong> Event creation rejected successfully.
  </div>
  EOF;
}

// Event creation request block
// Check if user is super_admin. If so, display event creation requests
if ($super_admin) {
  // Get event creation requests and output them to a table
  $result = query("SELECT * FROM events E, requesteventcreate REC WHERE REC.eid=E.eid && E.approved=0", $mysqli);
  print <<<EOF
    <div class="page-header">
      <h1>Pending Requests</h1>
    </div>
    <h2>Event Creation</h2>
    <table class="table table-striped table-hover table-responsive">
      <tr>
        <td>User</td>
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
      <td>
      <form method="post" action='eventrequest.php'>
        <input type="submit" name="accept_event"
            class="btn btn-success" value="Accept"  />
        <input type="submit" name="reject_event"
            class="btn btn-danger" value="Reject"  />
        <input type="hidden" name="eid" value="$eid" />
      </form>
      </td>
    </tr>
    EOF;
  }
  print <<<EOF
    </table>
  EOF;
}

// Event invitation block
// Display the user's requests. If there are none, display no requests.
// Get the Event name and the user name who requested to join the event
$result = query("SELECT E.name as ename, E.startDate as estartDate, E.endDate as eendDate, E.eid as eeid, U.name as uname, U.uid as uuid FROM (SELECT * FROM events WHERE admin=1) as E, user U, requesteventjoin REJ WHERE E.eid=REJ.eid && U.uid=REJ.uid;", $mysqli);
if ($result) {
  print <<<EOF
    <h2>Event Attendance</h2>
    <table class="table table-striped table-hover table-responsive">
      <tr>
        <td>Event</td>
        <td>Event Date</td>
        <td>User</td>
        <td></td>
      </tr>
  EOF;

  while($row = mysqli_fetch_assoc($result)) {
    // Get Event
    $event_name = $row['ename'];
    $event_start = $row['estartDate'];
    $event_end = $row['eendDate'];
    $event_eid = $row['eeid'];
    $user_name = $row['uname'];
    $user_uid = $row['uuid'];
    print <<<EOF
    <tr>
      <td>$event_name</td>
      <td>$event_start - $event_end</td>
      <td>$user_name</td>
      <td>
      <form method="post" action='eventrequest.php'>
        <input type="submit" name="accept_rsvp"
            class="btn btn-success" value="Accept"  />
        <input type="submit" name="reject_rsvp"
            class="btn btn-danger" value="Reject"  />
        <input type="hidden" name="eid" value="$event_eid" />
        <input type="hidden" name="uid" value="$user_uid" />
      </form>
      </td>
    </tr>
    EOF;
  }
  print <<<EOF
    </table>
  EOF;
} else {
  print <<<EOF
    <p>No Requests At This Time</p>
  EOF;
}
print <<<EOF
</body>
</html>
EOF;


?>
