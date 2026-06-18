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

$statutsBloques = [
    'ACCEPTEE',
    'EN_PREPARATION',
    'EN_LIVRAISON',
    'TERMINEE',
    'ANNULEE'
];

if(in_array($commande['statut'], $statutsBloques)){

    header('Location: commande-client.php');
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $dateLivraison = $_POST['dateLivraison'];
    $heureLivraison = $_POST['heureLivraison'];
    $adresseLivraison = $_POST['adresseLivraison'];
    $nbPersonnes = $_POST['nbPersonnes'];

    $sql = "
    UPDATE commande
    SET
        dateLivraison = ?,
        heureLivraison = ?,
        adresseLivraison = ?,
        nbPersonnes = ?
    WHERE idCommande = ?
    ";

    $query = $pdo->prepare($sql);

    $query->execute([
        $dateLivraison,
        $heureLivraison,
        $adresseLivraison,
        $nbPersonnes,
        $idCommande
    ]);

    header('Location: commande-client.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Modifier une commande</title>

    <link rel="stylesheet" href="../../css/vite&gourmand.css">

    <link rel="stylesheet" href="../../css/admin/commandes-admin.css">

</head>

<body>

<?php require '../../includes/navbar.php'; ?>

<main class="container">

    <h1>Modifier ma commande</h1>

    <form method="POST">

        <div>

            <label>Date de livraison</label>

            <input
                type="date"
                name="dateLivraison"
                value="<?= $commande['dateLivraison']; ?>"
                required
            >

        </div>

        <br>

        <div>

            <label>Heure de livraison</label>

            <input
                type="time"
                name="heureLivraison"
                value="<?= $commande['heureLivraison']; ?>"
                required
            >

        </div>

        <br>

        <div>

            <label>Adresse de livraison</label>

            <input
                type="text"
                name="adresseLivraison"
                value="<?= htmlspecialchars($commande['adresseLivraison']); ?>"
                required
            >

        </div>

        <br>

        <div>

            <label>Nombre de personnes</label>

            <input
                type="number"
                name="nbPersonnes"
                value="<?= $commande['nbPersonnes']; ?>"
                min="1"
                required
            >

        </div>

        <br>

        <button type="submit" class="btn">
            Enregistrer les modifications
        </button>

        <a href="mes-commandes.php" class="btn">
            Retour
        </a>

    </form>

</main>

<?php require '../../includes/footer.php'; ?>

</body>
</html>