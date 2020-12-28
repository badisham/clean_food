<?php
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$db = "clean_food2";
$conn = new mysqli($dbhost, $dbuser, $dbpass, $db) or die("Connect failed: %s\n" . $conn->error);

function CloseCon($conn)
{
    $conn->close();
}
