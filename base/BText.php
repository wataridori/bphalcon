<?php
/**
 * BText is a part of Base Phalcon.
 * @author tran.duc.thang
 */

namespace Wataridori\Bphalcon;
use \Phalcon\Text;

/**
 * BText provides some more functions to work with string.
 */

class BText
{
    /**
     * Convert from snake_case to Words
     * For example it_is_an_example => It is an example or It Is An Example
     * @param $str string the inputted word
     * @param $ucwords boolean the option to uppercase all the first character of each word or not
     * @return string the Words
     */
    public static function snakeToWords($str, $ucwords=true)
    {
        $str = str_replace('_', ' ', $str);
        return $ucwords ? ucwords($str) : ucfirst($str);
    }

    /**
     * Convert from CamelCase to Words
     * For example ItIsAnExample => It is an example or It Is An Example
     * @param $str string the inputted word
     * @return string the Words
     */
    public static function camelToWords($str)
    {
        return static::snakeToWords(static::camelToSnake($str));
    }

    /**
     * Convert a snake_case string to CamelCase
     * @param $str string
     * @return string
     */
    public static function snakeToCamel($str)
    {
        return Text::camelize($str);
    }

    /**
     * Convert a CamelCase string to snake_case
     * @param $str string
     * @return string
     */
    public static function camelToSnake($str)
    {
        return Text::uncamelize($str);
    }

    /**
     * Convert a snake_case string to lowerCamelCase
     * For example it_is_an_example => itIsAnExample
     * @param $str string
     * @return string
     */
    public static function snakeToLowerCamel($str)
    {
        $str = static::snakeToCamel($str);
        return strtolower($str[0]) . substr($str, 1);
    }
}