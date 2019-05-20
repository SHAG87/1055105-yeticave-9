<?php

require_once('boot.php');
$title='Вход';
$get_user = $_POST;
$errors = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    list($errors, $user) = check_in_data(['email', 'password']);

    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Некорректный email';
    }

    else {
        $user_email = htmlspecialchars($get_user['email']);
        $user = get_user_by_email($user_email);
        if (!$user) {
            $errors['email'] = 'Пользователь с таким e-mail не найден';
        }
    }
    if(!count($errors)) {
        if (!password_verify($get_user['password'], $user['password'])) {
            $errors['password'] = 'Неверный email или пароль';
        }
        else {
            $_SESSION['user'] = $user;
            header('Location: index.php');
            die ();
        }
    }

}

$content = include_template('login.php', [
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