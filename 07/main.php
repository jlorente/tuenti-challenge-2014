#!/usr/bin/php
<?php
/**
 * Solution for the Tuenti Challenge 07 - Yes we scan
 */
require_once __DIR__ . DIRECTORY_SEPARATOR . 'PhoneConnectionFinder.php';

$stdin = fopen('php://stdin', 'r');
$targetA = trim(fgets($stdin));
$targetB = trim(fgets($stdin));
fclose($stdin);

$finder = new PhoneConnectionFinder($targetA, $targetB);
$finder->searchLog(__DIR__ . DIRECTORY_SEPARATOR . 'phone_call.log');