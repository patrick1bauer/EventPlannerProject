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

$eid = $_POST['eid'];
$uid = $_POST['uid'];


// Event creation button functions
function accept_event($eid, $mysqli) {
  $result = query("UPDATE events SET approved=1 WHERE eid=$eid", $mysqli);
  if ($result)
    echo "Event Accepted";
  $result = query("DELETE FROM eventrequestcreate where eid=$eid", $mysqli);
  if ($result)
    echo "Event Request Deleted";
  header('location:requests.php?eventcreate=true');
}
function reject_event($eid, $mysqli) {
  $result = query("DELETE from eventrequestcreate where eid=$eid", $mysqli);
  if ($result)
    echo "Event Request Deleted";
  $result = query("DELETE FROM events where eid=$eid", $mysqli);
  if ($result)
    echo "Event Deleted";
  header('location:requests.php?eventcreate=false');
}

// Event RSVP button functions
function accept_rsvp($uid, $eid, $mysqli) {
  if ($uid && $eid) {
    echo "here";
    $result = query("INSERT INTO eventsjoined (eid, uid) VALUES ($eid, $uid)", $mysqli);
    if ($result) {
      $result = query("DELETE FROM requesteventjoin WHERE eid=$eid && uid=$uid", $mysqli);
      if ($result) {
        header('location:requests.php?eventcreate=true');
      } else {
        die();
      }
    }
  } else {
    die();
  }
}
function reject_rsvp($uid, $eid, $mysqli) {
  if ($uid && $eid) {
    $result = query("DELETE FROM requesteventjoin WHERE eid=$eid && uid=$uid", $mysqli);
    if ($result) {
      header('location:requests.php?eventinvite=false');
    } else {
      die();
    }
  } else {
    die();
  }
}
// Event Listeners
if (array_key_exists('accept_event', $_POST)) {
  accept_event($eid, $mysqli);
} else if (array_key_exists('reject_event', $_POST)) {
  reject_event($eid, $mysqli);
} else if (array_key_exists('accept_rsvp', $_POST)) {
  accept_rsvp($uid, $eid, $mysqli);
} else if (array_key_exists('reject_rsvp', $_POST)) {
  reject_rsvp($uid, $eid, $mysqli);
}



?>
