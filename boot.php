<?php
$is_auth = rand(0, 1);
$user_name = 'Andrey';

define('ROOT_DIR', __DIR__);

date_default_timezone_set("Europe/Moscow");
require_once ('functions\helpers.php');
require_once ('functions\functions.php');
$categories = get_categories();


