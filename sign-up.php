<?php

require_once('boot.php');

$user = [];
$errors = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    list($errors, $user) = check_in_data(['email', 'password', 'name', 'message']);

    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Некорректный email';
    }

    if (!count($errors)) {
        $user_id = add_user($user);
        header("Location: pages/login.html");

    }

}

$content = include_template('sign-up.php', [
    'user' => $user,
    'errors' => $errors,
]);

$layout = include_template('layout.php', [
    'title' => 'Главная',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'content' => $content,
    'categories' => $categories,

]);
print ($layout);