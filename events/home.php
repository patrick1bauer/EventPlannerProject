<?php
include('../helper.php');

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

    print <<<EOF
    <div class="container">
      <div class="jumbotron">
      <h1>EventPlannerProject</h1>
      <p>Browse events, create your own events, and join events on EventPlannerProject</p>
      </div>
    </div>
  <body>
</html>
EOF;
?>
