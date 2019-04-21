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
 * Возвращаем время до полуночи в формате HH:MM
 * @return string
 */

/**
 * Получаем оставшееся время до полуночи в формате "HH:MM"
 * @return string
 */
function sec_remaining () : string
{
    $newdaysec = strtotime("tomorrow midnight") - time();
    $hours = floor($newdaysec/3600);
    $minutes = floor(($newdaysec % 3600)/60);
    return "$hours : $minutes";
}