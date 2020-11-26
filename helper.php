<?php

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

?>
