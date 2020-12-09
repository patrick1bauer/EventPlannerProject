<?php

error_reporting(E_ERROR | E_PARSE);
// Perform mysqli query
function query($query, $mysqli) {
  $result = mysqli_query($mysqli, $query);
  return $result;
}

// Get one from query
function getOne($query, $mysqli) {
  $result = query($query, $mysqli);
  $row = mysqli_fetch_row($result);
  return $row[0];
}

function navbar() {
  print <<<EOF
    <nav class="navbar navbar-default">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand" href="">EventPlannerProject</a>
      </div>
      <ul class="nav navbar-nav">
        <li><a href="home.php">Home</a></li>
        <li><a href="events.php">Events</a></li>
    EOF;
        if(!isset($_COOKIE['login'])) {
          print <<<EOF
            <li><a href="../login.php">Login</a></li>
            <li><a href="../register.php">Sign up</a></li>
          EOF;
        } else {
          $arr = json_decode(stripslashes($_COOKIE['login']), true);
          print <<<EOF
            <li><a href="requests.php">Requests</a></li>
            <li><a href="../interfaces/adminInterface.php">Admin Interface</a></li>
          EOF;
          if (arr['super_admin'] == 1) {
            print <<<EOF
              <li><a href="superadmin.php">Super Admin</a></li>
            EOF;
          }
        }
        print <<<EOF
        </ul>
      </div>
    </nav>
  EOF;
}

function resetPassword()
{
  print <<<EOF
    <a href="../reset-password.php" class="btn btn-warning">Reset Password</a>
  EOF;
}

function signOut()
{
  print <<<EOF
    <a href="../logout.php" class="btn btn-danger">Sign Out</a>
  EOF;
}

?>
