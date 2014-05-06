#!/usr/bin/php
<?php
$stdin = fopen('php://STDIN', 'r');
while ($line = fgets($stdin)) {
    echo array_sum(explode(' ', trim($line))) . PHP_EOL;
}