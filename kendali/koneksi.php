<?php

$hostname = "localhost";
$username = "root";
$password = "";
$database_name = "db_spklp";

$db = new mysqli($hostname, $username, $password, $database_name);

if ($db->connect_error) {

	echo "koneksi gagal";
	die("erorrrrrrrrrrrrrrrrrr");
}

