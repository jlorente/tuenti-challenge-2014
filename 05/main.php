#!/usr/bin/php
<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'GenerationLoopSearcher.php';

$lifeGame = new GenerationLoopSearcher('php://stdin');
$lifeGame->run();