<?php
/**
 *
 */

namespace Crontab;

/**
 *
 */
class Calculator
{
    /**
     * [add description]
     * @param [type] $x [description]
     * @param [type] $y [description]
     */
    public function add($x, $y)
    {
        if ((!is_numeric($x)) || (!is_numeric($y))) {
            throw new \InvalidArgumentException(__FUNCTION__.' only accepts numeric input!');
        }
        return $x + $y;
    }
}
