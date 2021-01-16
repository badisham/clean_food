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

date_default_timezone_set('Asia/Bangkok');
session_start();

function CheckNewByDate($date)
{
    return date('d-m-Y', strtotime("now")) < date("d-m-Y", strtotime($date . " +2 days")) ?
        '<div class="product_bubble product_bubble_left product_bubble_green d-flex flex-column align-items-center"><span>new</span></div>' : '';
}

function GetNextDay($input_day)
{
    // $name_days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

    $days = explode(",", $input_day);
    $index = 0;
    for ($i = 0; $i < 7; $i++) {
        $current_day = date('l', strtotime('now + ' . $index . ' days'));
        if (in_array(strtolower($current_day),  $days)) {
            break;
        }
        $index++;
    }
    if ($index == 0)
        return "วันนี้";
    return date("d/m/Y", strtotime('now + ' . $index . ' days'));;
}

function LimitText($text, $limit)
{
    return strlen($text) > $limit ? substr($text, 0, $limit) . '...' : $text;
}

function CalExpress($price, $amount)
{
    $max = 100;
    $min = 10;
    $price_per_unit = 3;

    $benefit = ($amount * $price_per_unit) + $min + ($price * 0.05);
    if ($benefit > 100) {
        $benefit = $max;
    }
    return $benefit;
}
class User
{
    public $id;
    public $username;
    public $first_name;
    public $last_name;
    public $email;
    public $tel;
    public $type;
}

class Product
{
    public $id;
    public $name;
    public $img;
    public $genre;
    public $price;
    public $description;
    public $created_at;
    public $day;

    // cart & order
    public $cart_id;
    public $amount;
    public $price_total;
    public $status;
}

class Order
{
    public $id;
    public $order_product_list_id;
    public $status;
    public $user;
    public $price;
    public $restaurant;
    public $user_address;
    public $created_at;
    public $payment_status;
    public $express_datetime;
}


class Restaurant
{
    public $id;
    public $name;
    public $img;
    public $genre;
    public $description;
    public $created_at;
    public $address;
}

class BankCard
{
    public $id;
    public $name;
    public $num;
    public $cvv;
    public $expire_date;
    public $type;
    public $cash;
}

class Address
{
    public $id;
    public $address;
    public $amphure;
    public $district;
    public $zip_code;
    public $user_id;
}

class Amphures
{
    public $id;
    public $code;
    public $name_th;
    public $name_en;
}
