<?php

require __DIR__ . '/../vendor/autoload.php';

$client = new MongoDB\Client("mongodb://localhost:27017");

$dbMongo = $client->vite_gourmand;

$collectionStatistiques = $dbMongo->statistiques;