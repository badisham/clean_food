
<?php
//connect db
// $serverName = "localhost"; //host name
// $userName = "root"; //user login db
// $userPassword = ""; //pass login db
// $dbName = "ning_project"; // name db 


$serverName = "localhost"; //host name
$userName = "zazzifbv_jaroonsinchai-fruit"; //user login db
$userPassword = "1253"; //pass login db
$dbName = "zazzifbv_jaroonsinchai-fruit"; // name db 

date_default_timezone_set('Asia/Bangkok'); // time zone เซตไว้เพื่อให้เวลาตรงกับเครื่องเรา

//connected
$conn = mysqli_connect($serverName, $userName, $userPassword, $dbName) or die('<u><b>ไม่สามารถเชื่อมต่อฐานข้อมูลได้!</b></u>');
//set utf8
mysqli_set_charset($conn, "utf8");
mysqli_query($conn, "SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");

?>