<?php
require_once ('functions\helpers.php');
require_once ('functions\functions.php');
require_once ('data.php');

$content = include_template('index.php', [
        'categories' => $categories,
        'announ' => $announ,
]);

$layout = include_template('layout.php', [
        'is_auth' => $is_auth,
        'user_name' => $user_name,
        'content' =>  $content,
        'categories' => $categories,

]);
print ($layout);
