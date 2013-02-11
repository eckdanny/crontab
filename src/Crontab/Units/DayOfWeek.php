<?php
/**
 *
 */

namespace Crontab\Units;

/**
 *
 */
class DayOfWeek extends Base
{
    protected $min = 0;
    protected $max = 6;
    protected $format = '%01d';

    private $map = array(
        0  => 'sun',
        1  => 'mon',
        2  => 'tue',
        3  => 'wed',
        4  => 'thu',
        5  => 'fri',
        6  => 'sat'
    );

    public function __construct($expression)
    {
        foreach ($this->map as $weekDayNumber => $weekDayName) {
            $expression = preg_replace(
                '/' . $weekDayName . '/',
                $weekDayNumber,
                $expression
            );
        }
        parent::__construct($expression);
    }
}
