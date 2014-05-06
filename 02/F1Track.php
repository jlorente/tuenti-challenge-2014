<?php

/**
 * Represents a concrete F1 track
 */
class F1Track {
    protected $minX = 0;
    protected $minY = 0;
    protected $maxX = 0;
    protected $maxY = 0;
    protected $track;

    /**
     * 
     * @param int $x
     * @param int $y
     * @param char $data
     */
    public function add($x, $y, $data) {
        $this->checkX($x);
        $this->checkY($y);
        $this->track[$x][$y] = $data;
    }

    /**
     * Checks for minimum and maximum x position.
     * 
     * @param int $pos
     */
    protected function checkX($pos) {
        if ($pos < $this->minX) {
            $this->minX = $pos;
        } elseif ($pos > $this->maxX) {
            $this->maxX = $pos;
        }
    }

    /**
     * Checks for minimum and maximum y position.
     * 
     * @param type $pos
     */
    protected function checkY($pos) {
        if ($pos < $this->minY) {
            $this->minY = $pos;
        } elseif ($pos > $this->maxY) {
            $this->maxY = $pos;
        }
    }

    /**
     * Draws the F1 track on the standard output.
     */
    public function draw() {
        for ($i = $this->minX; $i <= $this->maxX; ++$i) {
            for ($j = $this->minY; $j <= $this->maxY; ++$j) {
                if (isset($this->track[$i][$j]) === true) {
                    echo $this->track[$i][$j];
                } else {
                    echo ' ';
                }
            }
            echo PHP_EOL;
        }
    }

}