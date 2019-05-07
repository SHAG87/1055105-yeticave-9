<?php
require_once ('functions\helpers.php');
require_once ('functions\functions.php');
require_once ('data.php');
$categories = get_categories();
$lots = get_lots();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_lot = $_POST;
    $errors = [];
    $required_fields = [
        'lot-name' => 'Название Лота',
        'category' => 'Категория',
        'message' => 'Описание товара',
        'lot-rate' => 'Начальная цена',
        'lot-step' => 'Минимальная ставка',
        'lot-date' => 'Дата завершения'
    ];

    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] =
                'Поле не заполнено';
        }
    }

    if (empty($_POST['lot-name'])) {
        $errors['lot-name'] = 'Необходимо ввести название Вашего Лота';
    }

    if (empty($_POST['category'])) {
        $errors['category'] = 'Необходимо выбрать категорию';
    }

    if (empty($_POST['message'])) {
        $errors['message'] = 'Расскажите хоть что-то';
    }

    if (!empty($_POST['lot-rate']) && (intval($_POST['lot-rate'])) <= 0) {
        $errors['lot-rate'] = 'Введите число > 0';
    }
    if (!empty($_POST['lot-step']) && (intval($_POST['lot-step'])) <= 0) {
        $errors['lot-step'] = 'Введите число > 0';
    }
    if (!empty($_POST['lot-date'])) {
        if (is_date_valid($_POST['lot-date'])) {
            $lot_date = strtotime($_POST['lot-date']);
            $now = strtotime('now');
            $diff = floor(($lot_date - $now) / 86400);
            if ($diff < 0) {
                $errors['lot-date'] = 'Минимальная продолжительность обьявления - 1 день!';
            }
        } else {
            $errors['lot-date'] = 'Пожалуйста, введите дату в формате ГГГГ-ММ-ДД';
        }
    }

    if (count($errors)>0) {
        $content = include_template('add.php', [
            'categories' => $categories,
            'new_lot' => $new_lot,
            'errors' => $errors,
        ]);
    } else {
        $id = add_lot($new_lot['lot-name'], $new_lot['category'], $new_lot['message'], $new_lot['lot-rate'], $new_lot['lot-step'], $new_lot['lot-date']);
        //header("Location: lot.php?id=" . $id);
    }
}
$content = include_template('add.php', [
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