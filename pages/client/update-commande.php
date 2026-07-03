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
    'EN_ATTENTE_RETOUR_MATERIEL',
    'TERMINEE',
    'ANNULEE'
];

if(in_array($commande['statut'], $statutsBloques)){

    header('Location: commande-client.php');
    exit;
}

$sql = "SELECT * FROM materiel ORDER BY nom";
$query = $pdo->prepare($sql);
$query->execute();

$materiels = $query->fetchAll();

$sql = "
SELECT cm.*, m.nom
FROM commande_materiel cm

INNER JOIN materiel m
ON cm.idMateriel = m.idMateriel

WHERE cm.idCommande = ?
";

$query = $pdo->prepare($sql);
$query->execute([$idCommande]);
$materielsCommande = $query->fetchAll();

$quantitesCommande = [];

foreach($materielsCommande as $materiel){

    $quantitesCommande[$materiel['idMateriel']] = $materiel['quantite'];
}

$sql = "
SELECT m.*
FROM menu m

INNER JOIN commande_menu cm
ON m.idMenu = cm.idMenu

WHERE cm.idCommande = ?
";

$query = $pdo->prepare($sql);
$query->execute([$idCommande]);

$menu = $query->fetch();



if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $dateLivraison = $_POST['dateLivraison'];
    $heureLivraison = $_POST['heureLivraison'];
    $adresseLivraison = $_POST['adresseLivraison'];
    $nbPersonnes = $_POST['nbPersonnes'];


    if($nbPersonnes < $menu['nbPersonnesMin']){

        die("Nombre minimum de personnes non respecté.");
    }

    $prixMenu = $menu['prixParPersonne'] * $nbPersonnes;

    $reduction = 0;

    if($nbPersonnes >= ($menu['nbPersonnesMin'] + 5)){

        $reduction = $prixMenu * 0.10;
    }

    $sql = "SELECT ville FROM utilisateur WHERE idUtilisateur = ?";
    $query = $pdo->prepare($sql);
    $query->execute([$_SESSION['user']['idUtilisateur']]);

    $user = $query->fetch();

    if(strtolower($user['ville']) === 'bordeaux'){

    $prixLivraison = 0;

    }else{

        $prixLivraison = 5;
    }

    $prixTotal = $prixMenu - $reduction + $prixLivraison;



    $pretMateriel = 0;

    if(isset($_POST['materiel'])){

        foreach($_POST['materiel'] as $quantite){

            if($quantite > 0){

                $pretMateriel = 1;
                break;
            }
        }
    }


    $sql = "
    UPDATE commande
    SET
        dateLivraison = ?,
        heureLivraison = ?,
        adresseLivraison = ?,
        nbPersonnes = ?,
        prixMenu = ?,
        prixTotal = ?,
        pretMateriel = ?
    WHERE idCommande = ?
        ";

    $query = $pdo->prepare($sql);

    $query->execute([
        $dateLivraison,
        $heureLivraison,
        $adresseLivraison,
        $nbPersonnes,
        $prixMenu,
        $prixTotal,
        $pretMateriel,
        $idCommande
    ]);

    
    if(isset($_POST['materiel'])){

    $sql = "
    DELETE FROM commande_materiel
    WHERE idCommande = ?
    ";

    $query = $pdo->prepare($sql);
    $query->execute([$idCommande]);

    foreach($_POST['materiel'] as $idMateriel => $quantite){

        if($quantite > 0){

            $sql = "
            INSERT INTO commande_materiel
            (
                idCommande,
                idMateriel,
                quantite
            )
            VALUES (?, ?, ?)
            ";

            $query = $pdo->prepare($sql);

            $query->execute([
                $idCommande,
                $idMateriel,
                $quantite
            ]);
        }
    }
}
  

    

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

    <link rel="stylesheet" href="../../css/admin_client/admin_client1.css">

    <script src="/vite_gourmand/js/navbar.js" defer></script>

</head>

<body>

<?php require '../../includes/navbar.php'; ?>

<main class="container">

    <h1 class="page-title">Modifier ma commande</h1>

    <form method="POST" class="card">

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
                min="<?= $menu['nbPersonnesMin']; ?>"
                required
            >

        </div>

        <br>

        <h3>Matériels :</h3>

       <?php foreach($materiels as $materiel): ?>

        <div>

            <label>
                <?= htmlspecialchars($materiel['nom']); ?>
            </label>

            <input
                type="number"
                name="materiel[<?= $materiel['idMateriel']; ?>]"
                min="0"
                value="<?= $quantitesCommande[$materiel['idMateriel']] ?? 0; ?>"
            >
            

        </div>

        <br>

        <?php endforeach; ?>

        <br>

        <button type="submit" class="btn">
            Enregistrer les modifications
        </button>

        <a href="commande-client.php" class="btn">
            Retour
        </a>

    </form>

</main>

<?php require '../../includes/footer.php'; ?>

</body>
</html>