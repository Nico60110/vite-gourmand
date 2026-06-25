<?php 
session_start();

require '../../config/database.php';

if(!isset($_SESSION['user'])){

    header('Location: ../auth/login.php');
    exit;
}

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

$statutsAutorises = [
    'ACCEPTEE',
    'EN_PREPARATION',
    'EN_LIVRAISON',
    'LIVREE',
    'EN_ATTENTE_RETOUR_MATERIEL',
    'TERMINEE'
];

if(!in_array($commande['statut'], $statutsAutorises)){

    header('Location: commande-client.php');
    exit;
}

$sql = "
SELECT *
FROM historique_statut
WHERE idCommande = ?
ORDER BY dateStatut ASC
";

$query = $pdo->prepare($sql);
$query->execute([$idCommande]);

$historique = $query->fetchAll();



?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>suivie commande</title>
    <link rel="stylesheet" href="../../css/vite&gourmand.css">
    <link rel="stylesheet" href="../../css/admin/commandes-admin.css">
    <script src="/vite_gourmand/js/navbar.js" defer></script>
</head>
<body>

<?php require '../../includes/navbar.php'; ?>

<main class="container">
    <h2>Suivi de la commande</h2>

<?php foreach($historique as $ligne): ?>

    <div class="card">

        <strong>
            <?= $ligne['statut']; ?>
        </strong>

        <br>

        <?= date('d/m/Y à H:i', strtotime($ligne['dateStatut'])); ?>

    </div>

    <br>

<?php endforeach; ?>

</main>

<?php require '../../includes/footer.php'; ?>
    
</body>
</html>