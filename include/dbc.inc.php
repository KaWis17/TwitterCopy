<?php

$serverName = "x";
$dBUsername = "x";
$dBPassword = "x";
$dBName = "x";

$conn = mysqli_connect($serverName, $dBUsername, $dBPassword, $dBName);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

