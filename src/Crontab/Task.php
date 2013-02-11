<?php
/**
 *
 */

namespace Crontab;

/**
 *
 */
class Task
{
    /** @var [type] */
    private $user;

    /** @var [type] */
    private $command;

    /** @var [type] */
    private $minute;

    /** @var [type] */
    private $hour;

    /** @var [type] */
    private $dayOfMonth;

    /** @var [type] */
    private $month;

    /** @var [type] */
    private $dayOfWeek;

    /** @var [type] */
    private $schedule;

    public function __construct($line)
    {
        $this->parse($line);
    }

    private function parse($line)
    {
        $pattern = '/^([^\s]+)\s([^\s]+)\s([^\s]+)\s([^\s]+)\s([^\s]+)\s([^\s]+)\s(.+)$/';
        preg_match($pattern, $line, $params);

        $this->user = $params[6];
        $this->command = $params[7];

        $this->minute     = new Units\Minute($params[1]);
        $this->hour       = new Units\Hour($params[2]);
        $this->dayOfMonth = new Units\DayOfMonth($params[3]);
        $this->month      = new Units\Month($params[4]);
        $this->dayOfWeek  = new Units\DayOfWeek($params[5]);

        return $this;
    }

    public static function getTasks(array $lines)
    {
        $tasks = array();
        while ($line = array_shift($lines)) {
            $tasks[] = new Task($line);
        }
        return $tasks;
    }

    public function getSchedule(\DateTime $date)
    {
        $params = explode(' ', $date->format('i H d m w'));

        $minute     = $params[0];
        $hour       = $params[1];
        $dayOfMonth = $params[2];
        $month      = $params[3];
        $dayOfWeek  = $params[4];

        // Check the Month
        if (!in_array($month, $this->month->getFormatted())) {
            return array();
        }

        // Check the Day
        if (
               !(in_array($dayOfMonth, $this->dayOfMonth->getFormatted()))
            && !(in_array($dayOfWeek, $this->dayOfWeek->getFormatted()))
        ) {
            return array();
        }

        // Generate Permutations of remaining params (minute and hour)
        $combinations = arrayCombinations(
            array(
                $this->hour->getFormatted(),
                $this->minute->getFormatted()
            )
        );

        $times = array();

        foreach ($combinations as $combination) {
            $key = $combination[0] . ':' . $combination[1];

            $times[$key] = $this->user . ' ' . $this->command;
        }

        return $times;
    }
}
