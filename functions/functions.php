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
 * @return array
 */
function add_lot ($new_lot)
{
        $link = get_link();
        $sql = "INSERT INTO lots (start_time , name, category_id, description, price, "
            . "bet_step, end_time, img-url, owner_id) VALUES "
            . "([NOW(), ?, ?, ?, ?, ?, ?, 2)";
        $stmt = db_get_prepare_stmt($link, $sql, [
            $new_lot['category'],
            $new_lot['message'],
            $new_lot['lot-rate'],
            $new_lot['lot-step'],
            $new_lot['lot-date'],
            $new_lot['lot-img'],
        ]);
        insert($stmt);
        return;
}

/**
 * Проверка достоверности данных, для формы добавления лота
 */
function validate_form_add ()
{
    //пустой массив, в него будем собирать ошибки
    $errors = [];

    /*добавляем сообщения об ошибке*/

    //Проверяем название лота. Функция empty мне не подошла, тк если я напишу вначале имени 0 она выдаст false
    if (strlen($_POST['lot-name']) ==  0) {
        $errors['lot-name'] = 'Необходимо ввести название Вашего Лота';
    }

    //Проверяем, выбрал ли пользователь категорию!
    if (empty($_POST['category'])) {
        $errors['category'] = 'Необходимо выбрать категорию';
    }

    // Проверяем написал ли пользователь описание
    if (strlen($_POST['message']) == 0) {
        $errors['message'] = 'Расскажите хоть что-то';
    }

    // Проверяем указал ли пользователь стоимость лота
    //if (!empty($_POST['lot-rate']) && (intval($_POST['lot-rate'])) <= 0) {
    if (strlen($_POST['lot-rate']) <= 0) {  //не уверен насчёт этой конструкции, но вроде тоже самое
        $errors['lot-rate'] = 'Введите число > 0';
    }

    //Проверяем указал ли пользователь минимальный шаг лота
    //if (!empty($_POST['lot-step']) && (intval($_POST['lot-step'])) <= 0) {
    if (strlen($_POST['lot-step']) <= 0) {
        $errors['lot-step'] = 'Введите число > 0';
    }

    //Проверяем корректность введёной даты
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
/*
    //!!!!!!Проверяем добавлен ли файл. Тут нужна помошь, с гитом и с самим добавлением файла
    if (isset($_FILES['file'])) {
        $fileName = $_FILES['file']['lot-img'];
        $filePath = __DIR__ . '/uploads/';
        $file_url = '/uploads/' . $fileName;
        move_uploaded_file($_FILES['file']['lot-img'], $filePath . $fileName);
    } else {
        $errors['lot-img'] = 'файл не добавлен';
    }
*/
    return $errors;  //если ниодной ошибки не было вернёт пустой массив

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
    $error = mysqli_error($link);
    $content = include_template('error.php', ['error' => $error]);
    print($content);
    die();
}