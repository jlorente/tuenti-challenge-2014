#!/usr/bin/php
<?php

class StudentSearch {
    protected $outputCounter = 0;
    protected $studentList = [];

    /**
     * Constructs a new StudentSearch object and indexes the students list in 
     * a dictionary.
     */
    public function __construct() {
        $file = fopen(__DIR__ . DIRECTORY_SEPARATOR . 'students', 'r');
        while ($line = fgets($file)) {
            $row = trim($line);
            $pos = strpos($row, ',');
            $name = substr($row, 0, $pos);
            $data = substr($row, $pos + 1);
            if (isset($this->studentList[$data]) === false) {
                $this->studentList[$data] = [];
            }
            $this->studentList[$data][] = $name;
        }
        fclose($file);
    }

    public function exec() {
        $stdin = fopen('php://stdin', 'r');
        $nCases = fgets($stdin);
        while (($line = fgets($stdin)) && $this->outputCounter < $nCases) {
            $this->processLine($line);
        }
    }

    /**
     * Process one line and gives the output result.
     * 
     * @param string $line
     */
    protected function processLine($line) {
        ++$this->outputCounter;
        $result = 'NONE';
        $row = trim($line);
        if (isset($this->studentList[$row])) {
            $this->orderStudents($row);
            $result = implode(',', $this->studentList[$row]);
        }
        echo 'Case #' . $this->outputCounter . ': ' . $result . PHP_EOL;
    }
    
    /**
     * Orders the students array lexicographicaly using a insertion sort algorithm.
     * 
     * @param string $data
     */
    protected function orderStudents($data)
    {
        for ($i = 0, $t = count($this->studentList[$data]); $i < $t; ++$i) {
            $pivot = $i;
            $value = $this->studentList[$data][$pivot];
            
            while ($pivot > 0 && $this->studentList[$data][$pivot - 1] > $value) {
                $this->studentList[$data][$pivot] = $this->studentList[$data][$pivot - 1];
                --$pivot;
            }
            $this->studentList[$data][$pivot] = $value;
        }
    }

}
$search = new StudentSearch();
$search->exec();