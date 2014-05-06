<?php
require __DIR__ . DIRECTORY_SEPARATOR . 'F1Track.php';

/**
 * Class for creating F1 tracks.
 */
class F1TrackCreator {
    /**
     *
     * @var F1Track
     */
    protected $f1Track;

    /**
     * 0 for horizontal, 1 for vertical
     * @var int
     */
    protected $dir = 0;
    
    /**
     * Stores the current X position of the pointer
     * 
     * @var int 
     */
    protected $x = 0;
    
    /**
     * Stores the current Y position of the pointer
     * 
     * @var int 
     */
    protected $y = 0;
    
    /**
     * Vector for moving the pointer.
     * 
     * @var type 
     */
    protected $m = ['x' => 1, 'y' => 0];

    /**
     * 
     * @param string $filePath
     * @return F1Track
     */
    public function getF1Track($filePath) {
        $this->f1Track = new F1Track();
        $handler = fopen($filePath, 'r');
        while ($line = fgets($handler)) {
            $this->processRow(trim($line));
        }
        fclose($handler);
        return $this->f1Track;
    }

    /**
     * Process one row of the input.
     * 
     * @param string $row
     */
    protected function processRow($row) {
        $aRow = str_split($row);
        for ($i = 0, $t = count($aRow); $i < $t; ++$i) {
            $this->processElement($aRow[$i]);

            $this->y += $this->m['x'];
            $this->x += $this->m['y'];
        }
    }

    /**
     * Process one element of the input row.
     * 
     * @param char $el
     */
    protected function processElement($el) {
        $char = $el;
        if ($el === '-' && $this->dir === 1) {
            $char = '|';
        } elseif ($el === '\\' || $el === '/') {
            $this->turn($el);
        }

        $this->f1Track->add($this->x, $this->y, $char);
    }

    /**
     * Changes the movement vector and the direction based on the turn type.
     * 
     * @param char $type
     */
    protected function turn($type) {
        $this->dir ^= 1;
        $x = $this->m['x'];
        $y = $this->m['y'];
        $modifier = 1;
        if ($type === '/') {
            $modifier = -1;
        }
        $this->m['x'] = $y * $modifier;
        $this->m['y'] = $x * $modifier;
    }
}