<?php
require_once ('functions\helpers.php');
require_once ('functions\functions.php');
require_once ('data.php');
$categories = get_categories();
$lot = get_lot_by_id(intval($_GET['id']));

if (is_null($lot) OR empty($_GET['id'])){
    http_response_code(404);
    $content = require_once ('pages\404.html');
    print $content;
}

    $content = include_template('lot.php', [
        'lot_name' => $lot['name'],
        'categories' => $lot['category_name'],
        'description' => $lot['description'],
        'price' => $lot['price'],
        'bet_step' => $lot['bet_step'],
        'img_url' => $lot['img_url'],
        'bet_sum' => $lot['bet_sum'] + $lot['price'],
        'end_time' => $lot['end_time'],
    ]);



$layout = include_template('layout.php', [
    'title' => $lot['name'],
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'content' =>  $content,
    'categories' => $categories,

]);
print ($layout);