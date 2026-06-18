<?php

session_start();

require '../../config/database.php';

if(!isset($_GET['id'])){
    header('Location: commande-client.php');
    exit;
}

$idCommande = $_GET['id'];

$sql = "
SELECT *
FROM commande
WHERE idCommande = ?
AND idUtilisateur = ?
";

$query = $pdo->prepare($sql);
$query->execute([
    $idCommande,
    $_SESSION['user']['idUtilisateur']
]);

$commande = $query->fetch();

if(!$commande){
    header('Location: commande-client.php');
    exit;
}

if($commande['statut'] !== 'EN_ATTENTE'){
    header('Location: commande-client.php');
    exit;
}

$sql = "
UPDATE commande
SET statut = 'ANNULEE'
WHERE idCommande = ?
";

$query = $pdo->prepare($sql);
$query->execute([$idCommande]);

header('Location: commande-client.php');
exit;
?>


