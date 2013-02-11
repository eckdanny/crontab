<?php
/**
 *
 */

namespace Crontab\Units;

/**
 *
 */
class DayOfMonth extends Base
{
    protected $min = 1;
    protected $max = 31;
    protected $format = '%02d';

    public function __construct($expression)
    {
        parent::__construct($expression);
    }
}
