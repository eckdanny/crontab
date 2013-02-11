<?php
/**
 *
 */

namespace Crontab\Units;

/**
 *
 */
class Hour extends Base
{
    protected $min = 0;
    protected $max = 23;
    protected $format = '%02d';

    public function __construct($expression)
    {
        parent::__construct($expression);
    }
}
