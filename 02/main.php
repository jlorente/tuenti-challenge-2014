#!/usr/bin/php
<?php
require __DIR__ . DIRECTORY_SEPARATOR . 'F1TrackCreator.php';

$trackCreator = new F1TrackCreator();
$trackCreator->getF1Track('php://stdin')->draw();