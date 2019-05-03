<?php
require_once ('functions\helpers.php');
require_once ('functions\functions.php');
require_once ('data.php');
$categories = get_categories();

if (isset($_GET['id']) && !empty($_GET['id']) && ($lot = get_lot_by_id($_GET['id']))){

    $content = include_template('lot.php', [
        'lot_name' => $lot['name'],
        'categories' => $lot['NAME'],
        'description' => $lot['description'],
        'price' => $lot['price'],
        'bet_step' => $lot['bet_step'],
        'img_url' => $lot['img_url'],
    ]);
}
else {
    http_response_code(404);
    $content = require_once ('pages\404.html');
}

$layout = include_template('layout.php', [
    'title' => $lot['name'],
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'content' =>  $content,
    'categories' => $categories,

]);
print ($layout);