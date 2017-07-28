<?php
$server = "localhost";
$user = "root";
$pass = "";

// Create connection
$connection = new mysqli($server, $user, $pass);
// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Create database
$sql = "CREATE DATABASE `test-app2`";

if ($connection->query($sql) === TRUE) {
    echo "<br/>Database created successfully";
} else {
    echo "<br/>Error creating database: " . $connection->error;
}

$connection->select_db("test-app2");

//create table
$sql = "CREATE TABLE IF NOT EXISTS `users2` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(48) NOT NULL,
  `surname` varchar(48) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `country` varchar(128) NOT NULL,
  `country_code` varchar(48) NOT NULL,
  `phone` varchar(48) NOT NULL,
  `creation_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
)";

if ($connection->query($sql) === TRUE) {
    echo "<br/>Table was created successfully";
} else {
    echo "<br/>Error creating table: " . $connection->error;
}

$connection->close();