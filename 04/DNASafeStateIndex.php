<?php

/**
 * Class for indexing the safe states by their nucleotide position in the dna 
 * string. 
 */
class DNASafeStateIndex {
    /**
     *
     * @var array
     */
    protected $data;

    /**
     * Adds a new state to the index.
     * 
     * @param DNAState $state
     */
    public function add(DNAState $state) {
        $aState = $state->getSplitedValue();
        for ($i = 0, $t = count($aState); $i < $t; ++$i) {
            $this->_add($i, $aState[$i], $state);
        }
    }

    /**
     * 
     * @param int $pos
     * @param char $nucleotide
     * @param DNAState $state
     */
    protected function _add($pos, $nucleotide, $state) {
        if ($this->exists($pos, $nucleotide) === false) {
            $this->data[$pos][$nucleotide] = [];
        }
        $this->data[$pos][$nucleotide][] = $state;
    }

    /**
     * 
     * @param int $pos
     * @param int $nucleotide
     * @return DNAState
     */
    public function get($pos, $nucleotide) {
        return $this->data[$pos][$nucleotide];
    }

    /**
     * 
     * @param int $pos
     * @param int $nucleotide
     * @return bool
     */
    public function exists($pos, $nucleotide) {
        return isset($this->data[$pos][$nucleotide]);
    }

}