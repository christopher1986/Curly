<?php

namespace Curly\Lang\Filter;

/**
 * The NumberFormatFilter will format a value according to the specified arguments.
 *
 * @author Chris Harris <c.harris@hotmail.com>
 * @version 1.0.0
 * @since 1.0.0
 */
class NumberFormatFilter
{
    /**
     * Returns a formatted number according to the specified arguments.
     *
     * <code>
     *     $number = 1068.5621|number_format(2, ',', '.');
     * </code>
     *
     * @param int|float $value the number to format.
     * @param int $decimals the number of decimal points.
     * @param string|null $decimalPoint the decimal point separator.
     * @param string|null $thousandSep the thousands separator.
     * @retunr float the formatted number.
     */
    public function filter($value, $decimals = 0, $decimalPoint = null, $thousandsSep = null)
    {
        $number = (is_numeric($value)) ? $value : 0.0;
        return number_format($number, $decimals, $decimalPoint, $thousandsSep);
    }
}
