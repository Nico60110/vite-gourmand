<?php

$host = "mysql-vite-gourmand.alwaysdata.net";
$dbname = "vite-gourmand_vite_gourmand";
$user = "vite-gourmand_vite-gourmand_app";
$password = "rooney60110";

try {

    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $user,
        $password
    );

    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

} catch(PDOException $e){

    die("Erreur : " . $e->getMessage());
}