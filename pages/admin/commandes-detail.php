<?php

require '../../config/database.php';
require '../../config/auth-admin.php';

// =========================
// VERIFICATION ID
// =========================

if(!isset($_GET['id'])){

    header("Location: commandes.php");
    exit;
}

$idCommande = (int) $_GET['id'];

// =========================
// COMMANDE + CLIENT
// =========================

$sql = "
SELECT c.*, u.nom, u.prenom, u.email, u.telephone
FROM commande c
INNER JOIN utilisateur u
ON c.idUtilisateur = u.idUtilisateur
WHERE c.idCommande = ?
";

$query = $pdo->prepare($sql);
$query->execute([$idCommande]);

$commande = $query->fetch();

if(!$commande){

    header("Location: commandes.php");
    exit;
}

// =========================
// MENUS COMMANDES
// =========================

$sql = "
    SELECT
        m.idMenu,
        m.titre,
        m.stock,
        m.nbPersonnesMin,
        m.prixParPersonne
    FROM commande_menu cm
    INNER JOIN menu m
        ON cm.idMenu = m.idMenu
    WHERE cm.idCommande = ?
";

$query = $pdo->prepare($sql);
$query->execute([$idCommande]);

$menus = $query->fetchAll();

// =========================
// HISTORIQUE DES STATUTS
// =========================

$sql = "
SELECT *
FROM historique_statut
WHERE idCommande = ?
ORDER BY dateStatut DESC
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
    <title>Commande détail</title>
    <link rel="stylesheet" href="../../css/admin_client/admin_client1.css">
    <link rel="stylesheet" href="../../css/vite&gourmand.css">
    <script src="/js/navbar.js" defer></script>
</head>
<body>
    <?php require '../../includes/navbar.php';?>

<main class="container">

    <h1 class="page-title">
        Détail de la commande
    </h1>

    <div class="detail-layout">

        <!-- INFORMATIONS COMMANDE -->

        <section class="card">

            <h2>Commande <?= (int) $commande['idCommande']; ?></h2>

            <p>
                <strong>Date commande :</strong>
               <?= htmlspecialchars($commande['dateCommande']); ?>
            </p>

            <p>
                <strong>Date livraison :</strong>
                <?= htmlspecialchars($commande['dateLivraison']); ?>
            </p>

            <p>
                <strong>Heure livraison :</strong>
                <?= htmlspecialchars($commande['heureLivraison']); ?>
            </p>

            <p>
                <strong>Adresse :</strong>
                <?= htmlspecialchars($commande['adresseLivraison']); ?>
            </p>

            <p>
                <strong>Nombre de personnes :</strong>
                <?= (int) $commande['nbPersonnes']; ?>
            </p>

            <p>
                <strong>Prix total :</strong>
                <?= number_format((float) $commande['prixTotal'], 2, ',', ' '); ?> €
            </p>

            <span class="statut">
                <?= htmlspecialchars($commande['statut']); ?>
            </span>

        </section>

        <!-- CLIENT -->

        <section class="card">

            <h2>Client</h2>

            <p>
                <strong>Nom :</strong>
                <?= htmlspecialchars($commande['nom']); ?>
            </p>

            <p>
                <strong>Prénom :</strong>
                <?= htmlspecialchars($commande['prenom']); ?>
            </p>

            <p>
                <strong>Email :</strong>
                <?= htmlspecialchars($commande['email']); ?>
            </p>

            <p>
                <strong>Téléphone :</strong>
                <?= htmlspecialchars($commande['telephone'] ?? ''); ?>
            </p>

        </section>

        <!-- MENU -->

        <section class="card">

            <h2>Menu commandé</h2>

            <?php foreach($menus as $menu): ?>

                <p><?= htmlspecialchars($menu['titre']); ?></p>

                <p>
                    <strong>Capacité restante :</strong>
                    <?= (int)$menu['stock']; ?> personne(s)
                </p>

                 <p>
                 <strong>Prix par personne :</strong>

                 <?= number_format((float) ($menu['prixParPersonne'] ?? 0),2,',',' '); ?>€
                </p>

                <p>
                    <strong>Minimum :</strong>
                    <?= (int)$menu['nbPersonnesMin']; ?> personne(s)
                </p>

            <?php endforeach; ?>

        </section>

        <!-- HISTORIQUE -->

        <section class="card">

            <h2>Historique des statuts</h2>

            <?php foreach($historique as $item): ?>


                <strong>
                    <?= htmlspecialchars($item['statut']); ?>
                </strong>

                <p>
                    <?= htmlspecialchars($item['dateStatut']); ?>
                </p>

                <p>
                    <?= htmlspecialchars($item['commentaire'] ?? ''); ?>
                </p>

            <?php endforeach; ?>

        </section>

        <a href="commandes-edit.php?id=<?= (int) $commande['idCommande']?>" class="btn">
            Modifier le statut
        </a>

    </div>
</main>

<?php require '../../includes/footer.php'; ?>

    
</body>
</html>