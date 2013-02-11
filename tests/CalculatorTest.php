<?php

use Crontab\Calculator;

class CalculatorTest extends PHPUnit_Framework_TestCase
{
    public function testAddNumbers()
    {
        $calc = new Calculator;
        $this->assertEquals(4, $calc->add(2, 2));
        $this->assertEquals(5, $calc->add(1.5, 3.5));
        $this->assertEquals(5, $calc->add(4.999, 0.001));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testThrowsExceptionIfNonNumericArgs()
    {
        $calc = new Calculator;
        $calc->add('a', array());
    }
}
