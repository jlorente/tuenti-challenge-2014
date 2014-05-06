<?php
/**
 * Class to find terrorists connections
 */
class PhoneConnectionFinder {
    /**
     * Id of the terrorist A
     * 
     * @var string 
     */
    protected $targetAId;
    
    /**
     * Id of the terrorist B
     * 
     * @var string 
     */
    protected $targetBId;
    
    /**
     * Array to store persons Ids as keys and connection as value.
     * 
     * @var array 
     */
    protected $callsIndex = [];
    
    /**
     * Connections store.
     * 
     * @var array 
     */
    protected $connections = [];
    
    /**
     * Incremental connection identifier.
     * 
     * @var int 
     */
    protected $connectionAddress = 1;

    /**
     * Index of phone call where terrorists became contacts. 
     * 
     * @var int 
     */
    protected $foundAt = null;
    
    /**
     * 
     * @param string $targetAId
     * @param string $targetBId
     */
    public function __construct($targetAId, $targetBId) {
        $this->targetAId = $targetAId;
        $this->targetBId = $targetBId;
    }

    /**
     * Main method.
     * Searchs for connection between terrorists.
     * 
     * @param string $filePath
     */
    public function searchLog($filePath) {
        $handler = fopen($filePath, 'r');
        $i = 0;
        while ($line = fgets($handler)) {
            $row = trim($line);
            $ids = explode(' ', $row);
            $this->processConnection($ids[0], $ids[1]);

            if ($this->areTerroristsConnected() === true) {
                $this->foundAt = $i;
                break;
            }
            ++$i;
        }
        fclose($handler);
        
        $this->printResults();
    }

    /**
     * Algorithm to store a phone call between two persons and store connection 
     * groups.
     * 
     * @param string $subjectA Id of the subjectA
     * @param string $subjectB Id of the subjectB
     */
    protected function processConnection($subjectA, $subjectB) {
        /**
         * If both contacts exists in the index. References of the minority 
         * group connections are update with the value of the other connection
         * group.
         * ex:
         *  [ID] => &connId     [connId] => [IdOfOneRepresentingTheGroup]
         * 
         *  [4423] => ref1 -> 1     [1] => [4423]
         *  [4212] => ref1 -> 1     [2] => [7762]
         *  [4231] => ref1 -> 1     [3] => [4421]
         *  [7762] => ref2 -> 2
         *  [0982] => ref2 -> 2
         *  [4421] => ref3 -> 3
         *  [3515] => ref3 -> 3
         * 
         * If the next phone call is between 4212 and 4421 the process will
         * be the following:
         *  Connection groups are 1 and 3 ([4212] => 1 and [4421] => 3)
         *  No group has more elements than the other, so the second one is 
         *  selected to update (ConnectionId: 3)
         *  
         *  Pivots of the connection id [3] group are updated. Because all of 
         *  them are referenced, update is done in the whole group:
         *      Pivot is 4421 so update is -> $this->callsIndex[4421] = 1;
         *      Now $this->callsIndex[3315] === 1 too
         *  
         *  Pivots are stored in the connection id that persists. In this case 
         *  is 1.
         * 
         *  Now the table will look like this:
         * 
         *  [ID] => &connId     [connId] => [IdOfOneRepresentingTheGroup]
         * 
         *  [4423] => ref1 -> 1     [1] => [4423, 4421]
         *  [4212] => ref1 -> 1     [2] => [7762]
         *  [4231] => ref1 -> 1
         *  [7762] => ref2 -> 2
         *  [0982] => ref2 -> 2
         *  [4421] => ref3 -> 1
         *  [3515] => ref3 -> 1
         * 
         */
        if (isset($this->callsIndex[$subjectA]) && isset($this->callsIndex[$subjectB])) {
            if ($this->callsIndex[$subjectA] !== $this->callsIndex[$subjectB]) {
                $valueA = $this->callsIndex[$subjectA];
                $valueB = $this->callsIndex[$subjectB];
                $connA = count($this->connections[$valueA]);
                $connB = count($this->connections[$valueB]);

                if ($connA >= $connB) {
                    for ($i = 0; $i < $connB; ++$i) {
                        $s = $this->connections[$valueB][$i];
                        $this->callsIndex[$s] = $valueA;
                        $this->connections[$valueA][] = $s;
                    }
                    unset($this->connections[$valueB]);
                } else {
                    for ($i = 0; $i < $connA; ++$i) {
                        $s = $this->connections[$valueA][$i];
                        $this->callsIndex[$s] = $valueB;
                        $this->connections[$valueB][] = $s;
                    }
                    unset($this->connections[$valueA]);
                }
            }
        } 
        /**
         * If only one exists. The one that doens't exist is stored in the index 
         * and its value becomes a reference to the one that does exist.
         */
        elseif (isset($this->callsIndex[$subjectA])) {
            $this->callsIndex[$subjectB] = &$this->callsIndex[$subjectA];
        } elseif (isset($this->callsIndex[$subjectB])) {
            $this->callsIndex[$subjectA] = &$this->callsIndex[$subjectB];
        } 
        /**
         * If no one exists, a new connection is created and stored for further 
         * updates.
         */
        else {
            $value = $this->connectionAddress++;
            $this->callsIndex[$subjectA] = &$value;
            $this->callsIndex[$subjectB] = &$value;
            $this->connections[$value] = [$subjectA];
        }
    }

    /**
     * Checks if the terrorists are connected.
     * 
     * @return bool
     */
    protected function areTerroristsConnected() {
        return isset($this->callsIndex[$this->targetAId]) && isset($this->callsIndex[$this->targetBId]) && $this->callsIndex[$this->targetAId] === $this->callsIndex[$this->targetBId];
    }

    /**
     * Outputs the results of the process.
     */
    protected function printResults()
    {
        if ($this->foundAt !== null) {
            echo 'Connected at ' . $this->foundAt;
        } else {
            echo 'Not connected';
        }
        echo PHP_EOL;
    }
}