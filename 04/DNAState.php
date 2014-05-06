<?php

/**
 * 
 */
class DNAState {
    /**
     * Value of the DNAString
     * 
     * @var string 
     */
    protected $value;

    /**
     * 
     * @param string $value
     */
    public function __construct($value) {
        $this->value = $value;
    }

    /**
     * 
     * @return string
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * 
     * @return array
     */
    public function getSplitedValue() {
        return str_split($this->value);
    }

}