<?php
/**
 * Represents a 8x8 board with life and dead cells.
 */
class Board {
    /**
     *
     * @var array 
     */
    protected $lifeCoords = [];
    
    /**
     *
     * @var array 
     */
    protected $activeAreas = [];

    /**
     * Marks a coordinate as life cell.
     * 
     * @param int $x
     * @param int $y
     */
    public function setLifeCell($x, $y) {
        $this->lifeCoords[$x][$y] = true;
        $this->addActiveArea($x, $y);
    }

    /**
     * Marks the area around the given coordinate as active to speed up following
     * transitions.
     * 
     * @param int $x
     * @param int $y
     */
    protected function addActiveArea($x, $y) {
        for ($i = 0; $i < 3; ++$i) {
            $xC = $x - 1 + $i;
            if ($xC >= 0) {
                for ($j = 0; $j < 3; ++$j) {
                    $yC = $y - 1 + $j;
                    if ($yC >= 0) {
                        $this->activeAreas[$xC][$yC] = true;
                    }
                }
            }
        }
    }

    /**
     * Checks if the given coordinate is a life cell.
     * 
     * @param int $x
     * @param int $y
     * @return bool
     */
    public function isLifeCell($x, $y) {
        return isset($this->lifeCoords[$x][$y]);
    }
    
    /**
     * 
     * @return array
     */
    public function getActiveAreas() {
        return $this->activeAreas;
    }

    /**
     * Gets the number of active neighbours.
     * 
     * @param int $x
     * @param int $y
     * @return int
     */
    public function getNeighboursNumber($x, $y) {
        $nCount = 0;
        for ($i = 0; $i < 3; ++$i) {
            $xC = $x - 1 + $i;
            if ($xC >= 0) {
                for ($j = 0; $j < 3; ++$j) {
                    $yC = $y - 1 + $j;
                    if ($yC >= 0) {
                        if (($xC !== $x || $yC !== $y) && isset($this->lifeCoords[$xC][$yC]) === true) {
                            ++$nCount;
                        }
                    }
                }
            }
        }
        return $nCount;
    }

    /**
     * Gets a string representation of the board life coordinates.
     * 
     * @return string
     */
    public function getStringRepresentation() {
        $r = '';
        foreach ($this->lifeCoords as $x => $col) {
            foreach ($col as $y => $unused) {
                $r .= '{' . $x . '-' . $y . '}';
            }
        }
        return $r;
    }
    
    /**
     * Prints the board on the standard output.
     */
    public function draw()
    {
        for ($i = 0; $i < 8; ++$i) {
            for ($j = 0; $j < 8; ++$j) {
                if (isset($this->lifeCoords[$i][$j]) === false) {
                    echo '-';
                } else {
                    echo 'X';
                }
            }
            echo PHP_EOL;
        }
        echo PHP_EOL;
    }
}