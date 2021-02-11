<?php
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$db = "clean_food";


// $dbuser = "zazzifbv_clean-food";
// $dbpass = "1253";
// $db = "zazzifbv_clean-food";

$conn = new mysqli($dbhost, $dbuser, $dbpass, $db) or die("Connect failed: %s\n" . $conn->error);

mysqli_set_charset($conn, "utf8");
function CloseCon($conn)
{
    $conn->close();
}

date_default_timezone_set('Asia/Bangkok');
session_start();

// CONFIG
$admin_id = 1;
$admin_num_card = 1345975465842145;

function CheckNewByDate($date)
{
    return date('d-m-Y', strtotime("now")) < date("d-m-Y", strtotime($date . " +2 days")) ?
        '<div class="product_bubble product_bubble_left product_bubble_green d-flex flex-column align-items-center"><span>new</span></div>' : '';
}

function GetNextDay($input_day, $formatDate = false)
{
    $days = explode(",", $input_day);
    $index = 0;
    for ($i = 0; $i < 7; $i++) {
        $current_day = date('l', strtotime('now + ' . $index . ' days'));
        if (in_array(strtolower($current_day),  $days)) {
            break;
        }
        $index++;
    }
    if ($formatDate) {
        return date("Y/m/d", strtotime('now + ' . $index . ' days'));
    } else {
        if ($index == 0)
            return "วันนี้";
        return date("m/d/Y", strtotime('now + ' . $index . ' days'));
    }
}

function LimitText($text, $limit)
{
    return strlen($text) > $limit ? substr($text, 0, $limit) . '...' : $text;
}

function CalShipping($price, $amount)
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

$dayTH = ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'];
$monthTH = [null, 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
$monthTH_brev = [null, 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
function ThaiDatetime($datetime, $addDay = 0)
{   // 19 ธันวาคม 2556 เวลา 10:10:43

    $unix = new DateTime($datetime);
    $time = $unix->getTimestamp();
    $time = strtotime('+' . $addDay . ' day', $time);

    global $dayTH, $monthTH;
    $thai_date_return = date("j", $time);
    $thai_date_return .= " " . $monthTH[date("n", $time)];
    $thai_date_return .= " " . (date("Y", $time) + 543);
    $thai_date_return .= " เวลา " . date("H:i:s", $time);
    return $thai_date_return;
}
function ThaiDate($datetime, $addDay = 0)
{   // 19 ธันวาคม 2556 เวลา 10:10:43
    $unix = new DateTime($datetime);
    $time = $unix->getTimestamp();
    $time = strtotime('+' . $addDay . ' day', $time);
    global $dayTH, $monthTH;
    $thai_date_return = date("j", $time);
    $thai_date_return .= " " . $monthTH[date("n", $time)];
    $thai_date_return .= " " . (date("Y", $time) + 543);
    return $thai_date_return;
}

function SweetAlert($msg, $icon, $link = '')
{
    $func = $link != '' ? "SweetAlertOk('$msg', '$icon', '$link');" : "SweetAlert('$msg', '$icon');";
    echo "<script>setTimeout(() => { $func }, 100);</script>";
}

function Transfer($conn, $transfer_user_id, $transfer_num, $recieve_user_id, $recieve_num, $cash, $type, $link = '')
{
    $result = mysqli_query($conn, $sql = "SELECT cash FROM bank_card WHERE num = '$transfer_num'");
    if ($cash > mysqli_fetch_assoc($result)['cash']) {
        SweetAlert('จำนวนเงินไม่เพียงพอ', 'warning', $link);
        return false;
    }
    mysqli_query($conn, "INSERT INTO `transaction`(`transfer_user_id`, `recieve_user_id`, `cash`, `type`) VALUES ('$transfer_user_id','$recieve_user_id','$cash','$type')");
    $sql = "UPDATE bank_card SET cash = CASE num
        WHEN '$transfer_num' THEN cash - $cash
        WHEN '$recieve_num' THEN cash + $cash
        END
        WHERE num IN ('$transfer_num','$recieve_num')";
    $result = mysqli_query($conn, $sql);
    return $result ? true : false;
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

    public $restaurant_name;
    public $owner_name;
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
    public $payment_chanel;
    public $express_datetime;
    public $shipping;
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
    public $owner;
    public $disburse_price;
}
class Rider
{
    public $id;
    public $rider_card_id;
    public $card_num_id;
    public $card_id_img;
    public $user;
    public $working_order;
    public $overdue;
    public $overdue_since_datetime;
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
class District
{
    public $id;
    public $name_th;
    public $name_en;
    public $amphure_id;
}


class Notificate
{
    public $id;
    public $datetime;
    public $status;
    public $description;
    public $link;
}

class Transaction
{
    public $id;
    public $transfer_user_id;
    public $transfer_name;
    public $recieve_user_id;
    public $recieve_name;
    public $cash;
    public $type;
    public $created_at;
}
