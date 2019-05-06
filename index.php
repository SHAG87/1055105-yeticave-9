<?php
date_default_timezone_set("Europe/Moscow");
require_once ('functions\helpers.php');
require_once ('functions\functions.php');
require_once ('data.php');

$categories = get_categories();
$lots = get_lots();

$content = include_template('index.php', [
        'categories' => $categories,
        'lots' => $lots,
]);

$layout = include_template('layout.php', [
        'title' => 'Главная',
        'is_auth' => $is_auth,
        'user_name' => $user_name,
        'content' =>  $content,
        'categories' => $categories,

]);
print ($layout);