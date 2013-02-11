<?php
/**
 *
 */

namespace Crontab\Units;

/**
 *
 */
class Minute extends Base
{
    protected $min = 0;
    protected $max = 59;
    protected $format = '%02d';

    public function __construct($expression)
    {
        parent::__construct($expression);
    }
}
