<?php
require_once('boot.php');


if (!isset($_GET['id']) || empty($_GET['id'])) {
    print_error('Идентификатор лота не передан');

}
$lot = get_lot_by_id(intval($_GET['id']));

if (is_null($lot)) {
    print_error('Лот с этим идентификатором не найден');
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