<?php

require __DIR__ . '/../vendor/autoload.php';

$client = new MongoDB\Client("mongodb+srv://vitegourmand:rooney60110@vitegourmand.ttb6qru.mongodb.net/?appName=vitegourmand");

$dbMongo = $client->vite_gourmand;

$collectionStatistiques = $dbMongo->statistiques;