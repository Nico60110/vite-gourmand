<?php

require __DIR__ . '/../vendor/autoload.php';

$mongoUri = getenv('MONGO_URI') ?: 'mongodb://mongodb:27017';

$client = new MongoDB\Client($mongoUri);

$dbMongo = $client->vite_gourmand;

$collectionStatistiques = $dbMongo->statistiques;

