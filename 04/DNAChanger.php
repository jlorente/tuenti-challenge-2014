<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'DNAState.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'DNASafeStateIndex.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'DNAStackData.php';

/**
 * Class to search the transition path between two states.
 */
class DNAChanger
{
    /**
     * Dictionary.
     * Stores all the possible states.
     * 
     * @var array 
     */
    protected $states = [];
    
    /**
     * Number of nucleotides in the DNA string.
     * 
     * @var int 
     */
    protected $dnaLenght;
    
    /**
     *  
     * @var DNAState 
     */
    protected $initialState;
    
    /**
     * 
     * @var DNAState 
     */
    protected $endState;
    
    /**
     * @see DNASafeStateIndex
     * @var DNASafeStateIndex 
     */
    protected $statesIndex;
    
    /**
     * Stores the bests path length.
     * 
     * @var int 
     */
    protected $minPLength = null;
    
    /**
     * Inits the execution.
     * 
     * @param string $filePath
     */
    public function run($filePath)
    {
        $handler = fopen($filePath, 'r');
        $this->initialState = new DNAState(trim(fgets($handler)));
        $this->endState = new DNAState(trim(fgets($handler)));
        $this->dnaLength = strlen($this->initialState->getValue());
        
        $this->states[$this->initialState->getValue()] = $this->initialState;
        $this->states[$this->endState->getValue()] = $this->endState;
        
        $this->createIndex($handler);
        fclose($handler);
        
        $this->printChangePath();
    }
    
    /**
     * Creates DNASafeStateIndex to help in searching the transition path.
     * 
     * @param resource $handler
     */
    protected function createIndex($handler)
    {
        $this->statesIndex = new DNASafeStateIndex();
        while ($line = fgets($handler)) {
            $row = trim($line);
            if (isset($this->states[$row]) === false) {
                $this->states[$row] = new DNAState($row);
            }
            $state = $this->states[$row];
            $this->statesIndex->add($state);
        }
    }
    
    /**
     * Iterative algorithm to search the transition path implemented with a 
     * stack.
     * 
     * @return array
     */
    protected function getChangePath()
    {
        $data = new DNAStackData($this->initialState);
        $stack = new SplStack();
        $stack->push($data);
        
        while ($stack->isEmpty() !== true) {
            $cData = $stack->pop();
            $cState = $cData->state;  
            $cPathLength = $cData->pLength;
            $cMemory = $cData->memory;
            $cMemory[$cState->getValue()] = true;
            $cPath = $cData->path;
            $cPath[] = $cState;

            if (($this->minPLength === null || $this->minPLength > $cPathLength) 
                    && $cState !== $this->endState) {

                $possibleChanges = $this->getPossibleChanges($cState);
                foreach ($possibleChanges as $changeState) {
                    if (isset($cMemory[$changeState->getValue()]) === false) {
                        $stack->push(new DNAStackData(
                                $changeState,
                                $cMemory,
                                $cPath));
                    }
                }
            } else {
                if ($cState === $this->endState) {
                    $result = $cPath;
                    $this->minPLength = $cPathLength;
                }
            }
        }
        
        return $result;
    }
    
    /**
     * Given a DNAState gets all the possible changes by searching in the index.
     * 
     * @param DNAState $state
     * @return array
     */
    protected function getPossibleChanges(DNAState $state)
    {
        $aState = $state->getSplitedValue();
        $candidates = [];
        $pChanges = [];
        for ($i = 0; $i < $this->dnaLength; ++$i) {
            if ($this->statesIndex->exists($i, $aState[$i]) === true) {
                foreach ($this->statesIndex->get($i, $aState[$i]) as $cand) {
                    if ($cand !== $state) {
                        if (isset($candidates[$cand->getValue()]) === false) {
                            $candidates[$cand->getValue()] = 0;
                        }
                        ++$candidates[$cand->getValue()];
                        if ($candidates[$cand->getValue()] === $this->dnaLength - 1) {
                            $pChanges[] = $cand;
                        }
                    }
                }
            }
        }
        return $pChanges;
    }
    
    /**
     * Prints the transition path to the standard output.
     */
    protected function printChangePath()
    {
        $separator = '';
        $path = $this->getChangePath();
        foreach ($path as $node) {
            echo $separator . $node->getValue();
            $separator = '->';
        }
        echo PHP_EOL;
    }
}