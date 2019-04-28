<?php

require_once ('functions\helpers.php');
require_once ('functions\functions.php');
require_once ('data.php');

// Подключаем категории из БД
$link = mysqli_connect("1055105-yeticave-9", "root", "","yeticave");
mysqli_set_charset($link, "utf8");
if ($link == false){
    print("Ошибка подключения: " .mysqli_connect_error());
}
else {

//Запрос на получение списка категорий
    $sql = "SELECT * FROM categories";
    $result = mysqli_query($link, $sql);

    if ($result) {
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($link);
        print("Ошибка MySQL: " .$error);
    }
//Запрос на получение списка лотов (убрал пока условия, чтобы главная страница отображалась полностью, для наглядности)
    $sql_lots = 'SELECT img_url, category_id, l.name, price, c.NAME FROM lots l '
                .'JOIN categories c ON l.category_id = c.id';
    if ($res = mysqli_query($link, $sql_lots)) {
        $lots =mysqli_fetch_all($res, MYSQLI_ASSOC);
    }
}

$content = include_template('index.php', [
        'categories' => $categories,
        'lots' => $lots,
]);

$layout = include_template('layout.php', [
        'is_auth' => $is_auth,
        'user_name' => $user_name,
        'content' =>  $content,
        'categories' => $categories,

]);
print ($layout);