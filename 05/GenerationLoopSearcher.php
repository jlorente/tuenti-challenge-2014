<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Board.php';

/**
 * Class for searching the generation start loop and the period.
 * Based on the Life Game of Conway.
 */
class GenerationLoopSearcher {
    /**
     * 
     * @var array
     */
    protected $boardMemory = [];
    
    /**
     *
     * @var Board
     */
    protected $currentBoard;
    
    /**
     *
     * @var Board
     */
    protected $nextBoard;
    
    /**
     *
     * @var int
     */
    protected $generation = 0;

    /**
     * 
     * @param string $filePath
     */
    public function __construct($filePath) {
        $handler = fopen($filePath, 'r');
        $i = 0;
        $this->currentBoard = new Board();
        while ($line = fgets($handler)) {
            $row = trim($line);
            for ($j = 0; $j < 8; ++$j) {
                if ($row{$j} === 'X') {
                    $this->currentBoard->setLifeCell($i, $j);
                }
            }
            ++$i;
        }
    }

    /**
     * Inits the execution and prints the solution on the standard output.
     */
    public function run() {
        while (isset($this->boardMemory[$this->currentBoard->getStringRepresentation()]) === false) {
            $this->storeBoard();
            $this->next();
        }

        $generation = $this->boardMemory[$this->currentBoard->getStringRepresentation()];
        echo $generation . ' ' . ($this->generation - $generation);
    }

    /**
     * Get the following board by checking the current active areas.
     */
    protected function next() {
        $this->nextBoard = new Board();
        $activePoints = $this->currentBoard->getActiveAreas();
        foreach ($activePoints as $x => $yCoords) {
            foreach ($yCoords as $y => $unused) {
                $this->processPoint($x, $y);
            }
        }
        $this->currentBoard = $this->nextBoard;
        ++$this->generation;
        $this->nextBoard = null;
    }

    /**
     * Processes a coordinate and declares it as life by checking the 
     * neighbours number.
     * 
     * @param int $x
     * @param int $y
     */
    protected function processPoint($x, $y) {
        $neighbours = $this->currentBoard->getNeighboursNumber($x, $y);
        if ($neighbours === 3) {
            $this->nextBoard->setLifeCell($x, $y);
        } else {
            if ($this->currentBoard->isLifeCell($x, $y) === true 
                    && $neighbours == 2) {
                $this->nextBoard->setLifeCell($x, $y);
            }
        }
    }

    /**
     * Stores the current board representation in the memory.
     */
    public function storeBoard() {
        $this->boardMemory[$this->currentBoard->getStringRepresentation()] = $this->generation;
    }

}