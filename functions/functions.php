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

/**
 * Получаем оставшееся время до закрытия лота в формате "HH:MM"
 * @return string
 */
function get_time_to_timer (string $end_time) : string
{
    $now = time();
    $time_is_running_out = strtotime($end_time);
    $diff = $time_is_running_out - $now;
    return date('H:i', $diff);
}

/**
 * Проверяем остался ли час до закрытия лота
 * @return bool
 */
function last_hour (string $end_time) : bool
{
    $now = time();
    $time_is_running_out = strtotime($end_time);
    $diff = $time_is_running_out - $now;
    return date('H', $diff) < 1;
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
    $sql = 'SELECT img_url, category_id, l.name, price, l.id, c.NAME FROM lots l '
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
        $sql = "SELECT img_url, l.name, category_id, description, price, bet_step, c.NAME FROM lots l JOIN categories c ON l.category_id = c.id WHERE l.id = '{$id}'";
        $result = mysqli_query(get_link(), $sql);
        $lots = mysqli_fetch_array($result, MYSQLI_ASSOC);
        return $lots;
    }
    header("Status: 404 Not Found");
}
