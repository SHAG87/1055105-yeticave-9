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

function print_error (string $text)
{
    http_response_code(404);
    $content = include_template('error.php', ['error' => $text]);
    print($content);
    exit(1);
}
