<?php

/**
 * Objects from this class are used to store nodes on the solution Stack.
 */
class DNAStackData {
    /**
     * Stores an array to remember which states have been visited.
     * 
     * @var array 
     */
    protected $memory;

    /**
     * Stores an ordered array that stores the path to the current state.
     * 
     * @var array 
     */
    protected $path;

    /**
     * Stores the path length.
     *  
     * @var int 
     */
    protected $pLength;

    /**
     * Current state
     *  
     * @var DNAState 
     */
    protected $state;

    /**
     * 
     * @param DNAState $state
     * @param array $memory
     * @param array $path
     */
    public function __construct(DNAState $state, array $memory = [], array $path = []) {
        $this->memory = $memory;
        $this->path = $path;
        $this->pLength = count($path);
        $this->state = $state;
    }

    /**
     * Magic method to get protected properties.
     * 
     * @param string $var
     * @return mixed
     * @throws ErrorException
     */
    public function __get($var) {
        if (isset($this->$var) === false) {
            throw new ErrorException();
        }
        return $this->$var;
    }

    /**
     * Magic method to check if protected properties exist.
     * 
     * @param string $var
     * @return bool
     */
    public function __isset($var) {
        return isset($this->$var);
    }

}