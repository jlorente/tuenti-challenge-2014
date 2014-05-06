#!/usr/bin/php
<?php
/**
 * Solution for the Tuenti Challenge 04 - Shape shifters
 */
require_once __DIR__ . DIRECTORY_SEPARATOR . 'DNAChanger.php';

$dnaChanger = new DNAChanger();
$dnaChanger->run('php://stdin');