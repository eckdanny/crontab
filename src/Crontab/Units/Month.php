<?php
/**
 *
 */

namespace Crontab\Units;

/**
 *
 */
class Month extends Base
{
    protected $min = 1;
    protected $max = 12;
    protected $format = '%02d';

    private $map = array(
        1  => 'jan',
        2  => 'feb',
        3  => 'mar',
        4  => 'apr',
        5  => 'may',
        6  => 'jun',
        7  => 'jul',
        8  => 'aug',
        9  => 'sep',
        10 => 'oct',
        11 => 'nov',
        12 => 'dec'
    );

    public function __construct($expression)
    {
        foreach ($this->map as $monthNumber => $monthName) {
            $expression = preg_replace(
                '/' . $monthName . '/',
                $monthNumber,
                $expression
            );
        }
        parent::__construct($expression);
    }
}
