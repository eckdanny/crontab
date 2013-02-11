<?php
/**
 *
 */

namespace Crontab;

/**
 *
 */
class Schedule
{
    private $fh;
    private $date;

    private $lines = array();
    private $tasks = array();

    public $schedule;

    private function test()
    {
        $this->readFile()
             ->cleanLines()
             ->setTasks()
             ->setSchedule($this->date)
             ->sortSchedule();

        return $this->schedule;
    }

    private function sortSchedule()
    {
        $sorted = array();
        $str = '';
        for ($hour=0; $hour<24; $hour++) {
            for ($min=0; $min<60; $min++) {
                $str = sprintf('%02d:%02d', $hour, $min);
                if (in_array($str, array_keys($this->schedule))) {
                    $sorted[$str] = $this->schedule[$str];
                    unset ($this->schedule[$str]);
                }
            }
        }
        $this->schedule = $sorted;
        unset ($sorted);
    }

    private function setTasks()
    {
        $this->tasks = Task::getTasks($this->lines);
        unset ($this->lines);

        return $this;
    }

    private function setSchedule(\DateTime $date)
    {
        $schedule = array();
        foreach ($this->tasks as $task) {
            // @todo watch type here!
            $schedule = $task->getSchedule($date);
            foreach ($schedule as $time => $command) {
                $this->schedule[$time][] = $command;
            }
        }

        return $this;
    }

    public function __construct($ISODate = '', $fh = '/etc/crontab')
    {
        $this->setDate($ISODate)
             ->setFh($fh)
             ->readFile()
             ->cleanLines()
             ->setTasks()
             ->setSchedule($this->date)
             ->sortSchedule();
    }

    public function getSchedule()
    {
        return $this->schedule;
    }

    public function printSchedule()
    {
        print_r($this->schedule);
    }

    public function setFh($fh)
    {
        $this->fh = $fh;

        return $this;
    }

    public function setDate($ISODate)
    {
        if ('' == $ISODate) {
            $ISODate = date('Y-m-d H:i:s');
        }

        $format = 'Y-m-d H:i:s';
        if (!$date = \DateTime::createFromFormat($format, $ISODate)) {
            throw new Exception("Invalid Date!");
        }

        $this->date = $date;

        return $this;
    }

    /**
     * Returns array of non-empty lines from input file
     * @param  string $fh Input file absolute location
     * @return array      Array of file content by line
     */
    private function readFile()
    {
        if (!file_exists($this->fh)) {
            throw new Exception("File not found!");
        }

        $lines = array();

        if (!$lines = file($this->fh, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)) {
            throw new Exception("File could not be read!");
        }

        if (count($lines) == 0) {
            throw new Exception("No lines were read!");
        }

        $this->lines = $lines;

        return $this;
    }

    /**
     * Removes comments and invalid crontab lines
     * @return array Valid Crontab expressions
     */
    private function cleanLines()
    {
        $lines = $this->lines;
        $cleanLines = array();

        while ($line = array_shift($lines)) {
            if (preg_match('/[\d\*]/', $line[0])) {
                $cleanLines[] = trim($line);
            }
        }

        if (count($cleanLines) == 0) {
            throw new Exception("Empty array after cleaning!");
        }

        $this->lines = $cleanLines;

        return $this;
    }
}
