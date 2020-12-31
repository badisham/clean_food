<?php
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$db = "clean_food";
$conn = new mysqli($dbhost, $dbuser, $dbpass, $db) or die("Connect failed: %s\n" . $conn->error);

function CloseCon($conn)
{
    $conn->close();
}

session_start();


class Product
{
    public $id;
    public $name;
    public $img;
    public $genre;
    public $price;
    public $description;
    public $created_at;
}


class Restaurant
{
    public $id;
    public $name;
    public $img;
    public $genre;
    public $description;
    public $created_at;
}
