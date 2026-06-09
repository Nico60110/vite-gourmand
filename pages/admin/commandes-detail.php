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

$idCommande = $_GET['id'];

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
SELECT m.titre,
       cm.quantite,
       cm.prixUnitaire
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
    <title>Document</title>
    <link rel="stylesheet" href="../../css/admin/commandes-admin.css">
    <link rel="stylesheet" href="../../css/vite&gourmand.css">
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

            <h2>Commande <?= $commande['idCommande']; ?></h2>

            <p>
                <strong>Date commande :</strong>
               <?= $commande['dateCommande']; ?>
            </p>

            <p>
                <strong>Date livraison :</strong>
                <?= $commande['dateLivraison']; ?>
            </p>

            <p>
                <strong>Heure livraison :</strong>
                <?= $commande['heureLivraison']; ?>
            </p>

            <p>
                <strong>Adresse :</strong>
                <?= $commande['adresseLivraison']; ?>
            </p>

            <p>
                <strong>Nombre de personnes :</strong>
                <?= $commande['nbPersonnes']; ?>
            </p>

            <p>
                <strong>Prix total :</strong>
                <?= $commande['prixTotal']; ?> €
            </p>

            <span class="statut">
                <?= $commande['statut']; ?>
            </span>

        </section>

        <!-- CLIENT -->

        <section class="card">

            <h2>Client</h2>

            <p>
                <strong>Nom :</strong>
                <?= $commande['nom']; ?>
            </p>

            <p>
                <strong>Prénom :</strong>
                <?= $commande['prenom']; ?>
            </p>

            <p>
                <strong>Email :</strong>
                <?= $commande['email']; ?>
            </p>

            <p>
                <strong>Téléphone :</strong>
                <?= $commande['telephone']; ?>
            </p>

        </section>

        <!-- MENU -->

        <section class="card">

            <h2>Menu commandé</h2>

            <?php foreach($menus as $menu): ?>

                <p><?= $menu['titre']; ?></p>

                <p>Quantité : <?= $menu['quantite']; ?></p>

                <p>Prix : <?= $menu['prixUnitaire']; ?> €</p>

            <?php endforeach; ?>

        </section>

        <!-- HISTORIQUE -->

        <section class="card">

            <h2>Historique des statuts</h2>

            <?php foreach($historique as $item): ?>

                <div class="historique-item">

                    <strong>
                        <?= $item['statut']; ?>
                    </strong>

                    <p>
                        <?= $item['dateStatut']; ?>
                    </p>

                    <p>
                        <?= $item['commentaire']; ?>
                    </p>

                </div>

            <?php endforeach; ?>

        </section>

        <a href="commandes-edit.php?id=1" class="btn">
            Modifier le statut
        </a>

    </div>

    
</body>
</html>