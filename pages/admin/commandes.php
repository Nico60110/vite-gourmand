<?php

require '../../config/database.php';

$sql = "SELECT *
        FROM commande
        ORDER BY dateCommande DESC";

$query = $pdo->prepare($sql);
$query->execute();

$commandes = $query->fetchAll();

?>




<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin-commandes</title>
    <link rel="stylesheet" href="../../css/admin/commandes-admin.css">
</head>
<body>
    
<section class="container">

    

    <?php foreach($commandes as $commande): ?>

            <div class="card">

                <h2>Commande #<?= $commande['idCommande']; ?></h2>

                <p>
                    Date livraison :
                    <?= $commande['dateLivraison']; ?>
                </p>

                <p>
                    Nombre de personnes :
                    <?= $commande['nbPersonnes']; ?>
                </p>

                <p>
                    Prix total :
                    <?= $commande['prixTotal']; ?> €
                </p>

                <span class="statut">
                    <?= $commande['statut']; ?>
                </span>

                <br>

                <a href="commandes-detail.php?id=<?= $commande['idCommande']; ?>" class="btn">
                    Voir le détail
                </a>

            </div>

    <?php endforeach; ?>

    

</section>
    
</body>
</html>