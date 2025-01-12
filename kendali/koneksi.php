<?php

//XAMPP
$hostname = "localhost";
$username = "root";
$password = "";
$database_name = "db_spklp";

// HOSTING WEB
// $hostname = "localhost";
// $username = "sraaonli_spk";
// $password = "spklahanpadi1234";
// $database_name = "sraaonli_spk";

$db = new mysqli($hostname, $username, $password, $database_name);

if ($db->connect_error) {
	echo "koneksi gagal";
	die("erorr");
}
