<?php
/**
 *
 */

namespace Crontab\Units;

/**
 *
 */
abstract class Base
{
    /** @var [type] */
    protected $expression;

    /** @var [type] */
    protected $literals = array();

    /** @var [type] */
    protected $min;

    /** @var [type] */
    protected $max;

    /** @var [type] */
    protected $format = '';

    /** @var [type] */
    protected $list;

    /**
     * [__construct description]
     * @param string $expression [description]
     */
    public function __construct($expression = '')
    {
        if ($expression == '') {
            throw new Exception("Invalid expression!");
        }

        $this->setExpression($expression)
             ->convertAsterisksToRange()
             ->setList()
             ->parseList();
    }

    /**
     * [setExpression description]
     * @param [type] $expression [description]
     */
    protected function setExpression($expression)
    {
        $this->expression = $expression;

        return $this;
    }

    /**
     * Convert all asterisks in the expression to ranges
     * @return void
     */
    protected function convertAsterisksToRange()
    {
        $this->expression = preg_replace(
            '/\*/',
            $this->min . '-' . $this->max,
            $this->expression
        );

        return $this;
    }

    /**
     * [setList description]
     */
    protected function setList()
    {
        $this->list = explode(',', $this->expression);

        return $this;
    }

    /**
     * [parseList description]
     * @return [type] [description]
     */
    protected function parseList()
    {
        $literals = array();

        // while ($expression = array_shift($this->list)) {
        // while ($expression = array_pop($this->list)) {
        foreach ($this->list as $expression) {

            if ($this->isRange($expression)) {
                if ($this->isStepped($expression)) {
                    // Stepped Range
                    $pattern = '/^(\d+)-(\d+)\/(\d+)$/';
                    preg_match($pattern, $expression, $matches);
                    $min  = $matches[1];
                    $max  = $matches[2];
                    $step = $matches[3];
                    $range = range($min, $max);
                    for ($i=0, $N=count($range); $i<$N; $i++) {
                        if ($i%$step == 0) {
                            $literals[] = $range[$i];
                        }
                    }
                } else {
                    // Range
                    $pattern = '/^(\d+)-(\d+)$/';
                    preg_match($pattern, $expression, $matches);
                    $min  = $matches[1];
                    $max  = $matches[2];
                    $range = range($min, $max);
                    $literals = array_merge($literals, $range);
                }
            } else {
                // Single Value
                $literals[] = $expression;
            }
        }

        unset ($this->list);

        if (count($literals) > 1) {
            $literals = array_unique($literals, SORT_NUMERIC);
            sort($literals);
        }

        $this->literals = $literals;
        unset ($literals);
    }

    /**
     * [isRange description]
     * @param  [type]  $expression [description]
     * @return boolean             [description]
     */
    protected function isRange($expression)
    {
        // Falsey Test
        $isRange = preg_match('/-/', $expression);
        if ($isRange === false) {
            throw new Exception(__function__ . ' failed!');
        }
        switch ($isRange) {
            // A match was found
            case 1:
                return true;
            // A match was not found
            case 0:
                return false;
            // Something unexpected happened
            default:
                throw new Exception("An unknown error occured!");
        }
    }

    /**
     * [isStepped description]
     * @param  [type]  $expression [description]
     * @return boolean             [description]
     */
    protected function isStepped($expression)
    {
        $isStepped = preg_match('%/%', $expression);
        if ($isStepped === false) {
            throw new Exception(__function__ . ' failed!');
        }
        switch ($isStepped) {
            // A match was found
            case 1:
                return true;
            // A match was not found
            case 0:
                return false;
            // Something unexpected happened
            default:
                throw new Exception("An unknown error occured!");
        }
    }

    /**
     * [getFormatted description]
     * @return [type] [description]
     */
    public function getFormatted()
    {
        if ('' == $this->format) {
            throw new Exception("Invalid format definition!");
        }

        $formatted = array();

        foreach ($this->literals as $str) {
            $formatted[] = sprintf($this->format, $str);
        }

        return $formatted;
    }
}
