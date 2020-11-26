<?php

$ini = parse_ini_file('php.ini');

global $database_host;
global $database_user;
global $database_pass;
global $database_name;

$database_host = $ini['database_host'];
$database_user = $ini['database_user'];
$database_pass = $ini['database_pass'];
$database_name = $ini['database_name'];

?>