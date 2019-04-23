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
function get_time_to_timer () : string
{
    $now = time();
    $time_is_running_out = strtotime("tomorrow midnight");
    $diff = $time_is_running_out - $now;
    return date('H:i', $diff);
}

/**
 * Проверяем остался ли час до закрытия лота
 * @return bool
 */
function last_hour () : bool
{
    $now = time();
    $time_is_running_out = strtotime("tomorrow midnight");
    $diff = $time_is_running_out - $now;
    if (date('H', $diff)>=1){
    return true;
    }
    return false;
}