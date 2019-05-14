<?php

/**
 * Форматирует сумм  + рубль
 * @param $value
 * @return string
 */
function format_sum ($value) : string
{
    $number = ceil($value);
    $price = number_format($number, 0, ',', " ") . " ₽";
    return $price;
}

/**
 * защита от xss конвертируем спец символы
 * @param string $string
 * @return string
 */
function esc (string $string) : string
{
    return htmlspecialchars($string);
}

/**
 * защита от xss конвертируем всё кроме букв
 * @param string $string
 * @return string
 */
function esc_strong (string $string) : string
{
    return htmlentities($string);
}


function get_time_in_hours_minutes(string $time) : array
{
    $diff_time = strtotime($time) - time();
    if ($diff_time <= 0) {
        return [0, 0];
    }
    return [floor($diff_time / 3600), floor($diff_time % 3600 / 60)];
}
/**
 * Функция определяющая остаток времени для закрытия лота
 * @param string $lot_time
 * @return string
 */
function get_time_to_timer(string $lot_time) : string
{
    list($hours, $minutes) = get_time_in_hours_minutes($lot_time);
    return sprintf("%02d:%02d", $hours, $minutes);
}
/**
 * Функция определяющая осталось ли времени меньше часа до закрытия лота
 * @param string $lot_time
 * @return bool
 */
function is_last_hour(string $lot_time) : bool
{
    list($hours, $minutes) = get_time_in_hours_minutes($lot_time);
    return $hours < 1;
}

/*
 *Соединяемся с БД
 */
function get_link ()
{
    $link = mysqli_connect("1055105-yeticave-9", "root", "","yeticave");
    mysqli_set_charset($link, "utf8");
    if ($link !== false){
        return $link;
    }
        print("Ошибка подключения: " .mysqli_connect_error());
        mysqli_close($link);

}

/*
 * Запрос на получение списка категорий
 */
function get_categories () : array
{
        $sql = "SELECT * FROM categories";
        $result = mysqli_query(get_link(), $sql);
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $categories;
}


/*
 * Запрос на получение списка лотов (убрал пока условия, чтобы главная страница отображалась полностью, для наглядности)
 */
function get_lots() : array
{
    $sql = 'SELECT end_time, img_url, category_id, l.name, price, l.id, c.NAME as category_name FROM lots l '
        . 'JOIN categories c ON l.category_id = c.id';
    $result = mysqli_query(get_link(), $sql);
    $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $lots;
}

/*
 * получаем данные лота по входящему значению id
 */
function get_lot_by_id(int $id)
{
    if (isset($id)) {
        $link = get_link();
        $sql = db_get_prepare_stmt($link, "SELECT end_time, img_url, l.name, category_id, b.bet_sum, description, price, bet_step, c.NAME as category_name FROM lots l "
        . "JOIN categories c ON l.category_id = c.id "
        . "LEFT JOIN bets b ON l.id = b.id "
        . "WHERE l.id =? ", [$id]);
        mysqli_stmt_execute($sql);
        $result = mysqli_stmt_get_result($sql);
        $lots = mysqli_fetch_array($result, MYSQLI_ASSOC);
        return $lots;
    }
    return [];
}

/**
 * При возникновении ошибки 404 отправляем  пользователя на страницу error.php
 * @param string $text
 */
function print_error (string $text)
{
    http_response_code(404);
    $content = include_template('error.php', ['error' => $text]);
    print($content);
    exit(1);
}

/**
 * Добавляем новый лот
 * @param $new_lot
 * @return int|null
 */
function add_lot ($new_lot)
{
        $link = get_link();
        $sql = "INSERT INTO lots (start_time , name, category_id, description, price, "
            . "bet_step, end_time, img_url, owner_id) VALUES "
            . "(NOW(), ?, ?, ?, ?, ?, ?, ?, 2)";
        $stmt = db_get_prepare_stmt($link, $sql, [
            $new_lot['lot-name'],
            $new_lot['category'],
            $new_lot['message'],
            $new_lot['lot-rate'],
            $new_lot['lot-step'],
            $new_lot['lot-date'],
            $new_lot['lot-img'],
        ]);

        return insert($stmt);
}

/**
 * Проверяет заполнены ли необходимые поля
 * @param array $required
 * @return array
 */
function check_in_data (array $required)
{
    $errors = [];
    $current_array = [];
    foreach ($required as $field) {
        if (isset($_POST[$field]) && !empty(trim($_POST[$field]))) {
            $current_array[$field] = trim($_POST[$field]);
        } else {
            $errors[$field] = 'Это поле необходимо заполнить';
        }
    }
    return [$errors, $current_array];
}

function check_value_more_then($faild_name, $value, &$errors)
{
    if (!isset($errors[$faild_name])) {
        if (!(intval($_POST[$faild_name]) > $value)) {
            $errors[$faild_name] = "Значение должно быть больше {$value}";
        }
    }
}


function check_date(string $key, array &$errors, array &$lot)
{
    if (isset($errors[$key])) {
        return;
    }

    if (!is_date_valid($lot[$key])) {
        $errors[$key] = 'Пожалуйста, введите дату в формате ГГГГ-ММ-ДД';
        return;
    }

    $lot_date = strtotime($lot[$key]);
    $now = strtotime('now');
    $diff = floor(($lot_date - $now) / 86400);
    if ($diff < 0) {
        $errors[$key] = 'Минимальная продолжительность обьявления - 1 день!';
    }
}

function validate_img(string $key, array &$errors)
{
    if (isset($_FILES[$key]) && isset($_FILES[$key]['name']) && !empty($_FILES[$key]['name'])) {
        $tmp_name = $_FILES[$key]['tmp_name'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $tmp_name);
        if ($file_type !== "image/png" AND $file_type !== "image/jpeg" AND $file_type !== "image/jpg") {
            $errors[$key] = 'Загрузите картинку в формате png, jpeg или jpg.';
        }
    } else {
        $errors[$key] = 'Это поле необходимо заполнить';
    }
}

/**
 * Проверка достоверности данных, для формы добавления лота
 * @param array $lot
 * @param array $errors
 */
function validate_form_add (array $lot, array &$errors)
{

    // Проверяем указал ли пользователь стоимость лота
    check_value_more_then('lot-rate', 0, $errors);


    //Проверяем указал ли пользователь минимальный шаг лота
    check_value_more_then('lot-step', 0, $errors);

    //Проверяем корректность введёной даты
    check_date('lot-date', $errors, $lot);

    //Validate image
    validate_img('lot-img', $errors);
}

/**
 * Меняет имя файла на набор уникальных символов
 * @param $key string ключ, имя поля с выбранным файлом
 * @param $file_dir string дирректория
 * @return string
 */
function change_filename($key, $file_dir)
{
    $tmp_name = $_FILES[$key]['tmp_name'];
    $path = $_FILES[$key]['name'];
    $filename = uniqid() . '.' . pathinfo($path, PATHINFO_EXTENSION);
    move_uploaded_file($tmp_name, ROOT_DIR . $file_dir . DIRECTORY_SEPARATOR . $filename);
    return $file_dir . DIRECTORY_SEPARATOR . $filename;
}

// Отображение формы Добавления ЛОТА
function show_form_add ($errors = []) {
    //Если переданы ошибки выводим их на экран
    if ($errors) {
        print 'Пожалуйста, исправьте ошибки в форме: <ul><li>';
        print implode('</li><li>', $errors);
        print '</li></ul>';
    }
}

/*
 *  Вставляем скуль запросы , возвращаем id
 */
function insert(mysqli_stmt $stmt): ?int
{
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_affected_rows($stmt);
    if ($result !== 0) {
        /*возвращаем id добавленной записи*/
        return mysqli_stmt_insert_id($stmt);
    }
    die('MYSQL error!');
}