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

$idCommande = (int) $_GET['id'];
$idUtilisateur = (int) $_SESSION['user']['idUtilisateur'];

// =========================
// VERIFICATION COMMANDE
// =========================

$sql = "
SELECT *
FROM commande
WHERE idCommande = ?
AND idUtilisateur = ?
";

$query = $pdo->prepare($sql);

$query->execute([
    $idCommande,
    $idUtilisateur
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

if(in_array($commande['statut'], $statutsBloques, true)){

    header('Location: commande-client.php');
    exit;
}

// =========================
// MATERIEL
// =========================

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

// =========================
// MENU DE LA COMMANDE
// =========================

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

if(!$menu){

    header('Location: commande-client.php');
    exit;
}

// =========================
// TRAITEMENT FORMULAIRE
// =========================

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $dateLivraison = trim($_POST['dateLivraison']);
    $heureLivraison = trim($_POST['heureLivraison']);
    $adresseLivraison = trim($_POST['adresseLivraison']);
    $nbPersonnes = (int) $_POST['nbPersonnes'];

    if(
        empty($dateLivraison) ||
        empty($heureLivraison) ||
        empty($adresseLivraison)
    ){

        $erreur = "Tous les champs obligatoires doivent être remplis.";

    } elseif($nbPersonnes < $menu['nbPersonnesMin']){

        $erreur = "Nombre minimum de personnes non respecté.";

    } else {

        $prixMenu = $menu['prixParPersonne'] * $nbPersonnes;

        $reduction = 0;

        if($nbPersonnes >= ($menu['nbPersonnesMin'] + 5)){

            $reduction = $prixMenu * 0.10;
        }

        $sql = "SELECT ville FROM utilisateur WHERE idUtilisateur = ?";
        $query = $pdo->prepare($sql);
        $query->execute([$idUtilisateur]);

        $user = $query->fetch();

        if($user && strtolower($user['ville']) === 'bordeaux'){

            $prixLivraison = 0;

        } else {

            $prixLivraison = 5;
        }

        $prixTotal = $prixMenu - $reduction + $prixLivraison;

        $pretMateriel = 0;

        if(isset($_POST['materiel'])){

            foreach($_POST['materiel'] as $quantite){

                $quantite = (int) $quantite;

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
        AND idUtilisateur = ?
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
            $idCommande,
            $idUtilisateur
        ]);

        $sql = "
        DELETE FROM commande_materiel
        WHERE idCommande = ?
        ";

        $query = $pdo->prepare($sql);
        $query->execute([$idCommande]);

        if(isset($_POST['materiel'])){

            foreach($_POST['materiel'] as $idMateriel => $quantite){

                $idMateriel = (int) $idMateriel;
                $quantite = (int) $quantite;

                if($idMateriel > 0 && $quantite > 0){

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

    <script src="/js/navbar.js" defer></script>

</head>

<body>

<?php require '../../includes/navbar.php'; ?>

<main class="container">

    <h1 class="page-title">Modifier ma commande</h1>

    <?php if(isset($erreur)): ?>

        <p class="error">
            <?= htmlspecialchars($erreur); ?>
        </p>

    <?php endif; ?>

    <form method="POST" class="card">

        <div>

            <label>Date de livraison</label>

            <input
                type="date"
                name="dateLivraison"
                value="<?= htmlspecialchars($commande['dateLivraison']); ?>"
                required
            >

        </div>

        <br>

        <div>

            <label>Heure de livraison</label>

            <input
                type="time"
                name="heureLivraison"
                value="<?= htmlspecialchars($commande['heureLivraison']); ?>"
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
                value="<?= (int) $commande['nbPersonnes']; ?>"
                min="<?= (int) $menu['nbPersonnesMin']; ?>"
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
                value="<?= isset($quantitesCommande[$materiel['idMateriel']]) ? (int) $quantitesCommande[$materiel['idMateriel']] : 0; ?>"
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