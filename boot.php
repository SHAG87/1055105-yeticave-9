<?php
$is_auth = 0;
$user_name = '';
session_start();
if(isset($_SESSION['user'])) {
    $user_name = $_SESSION['user']['name'];
    $is_auth = 1;
} else {
    $is_auth = 0;
    $user_name = '';
}

define('ROOT_DIR', __DIR__);

date_default_timezone_set("Europe/Moscow");
require_once ('functions\helpers.php');
require_once ('functions\functions.php');
$categories = get_categories();


