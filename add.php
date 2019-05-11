<?php
require_once ('functions\helpers.php');
require_once ('functions\functions.php');
require_once ('data.php');
$categories = get_categories();
$lots = get_lots();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //Если функция validate_form_add () возвратит ошибки, то передаём их в отображение формы show_form_add ()
    if ($form_errors = validate_form_add()) {
        show_form_add ($form_errors);
    } else {
        add_lot();
    }
    } else {
    show_form_add();
}

$layout = include_template('layout.php', [
    'title' => 'Главная',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'content' =>  $content,
    'categories' => $categories,

]);
print ($layout);