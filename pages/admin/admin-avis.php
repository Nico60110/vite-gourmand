<?php

require '../../config/database.php';
require '../../config/auth-admin.php';

$sql = "
SELECT
    a.*,
    u.nom,
    u.prenom
FROM avis a
INNER JOIN utilisateur u
ON a.idUtilisateur = u.idUtilisateur
ORDER BY a.dateAvis DESC
";

$query = $pdo->prepare($sql);
$query->execute();

$avis = $query->fetchAll();

?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Gestion des avis</title>

    <link rel="stylesheet" href="../../css/vite&gourmand.css">
    <link rel="stylesheet" href="../../css/admin/commandes-admin.css">

</head>

<body>

<?php require '../../includes/navbar.php'; ?>

<main class="container">

    <h1>Gestion des avis clients</h1>

    <?php if(empty($avis)): ?>

        <p>Aucun avis trouvé.</p>

    <?php endif; ?>

    <?php foreach($avis as $unAvis): ?>

        <div class="card">

            <h3>
                <?= htmlspecialchars($unAvis['prenom']); ?>
                <?= htmlspecialchars($unAvis['nom']); ?>
            </h3>

            <p>

                <strong>Note :</strong>

                <?php for($i = 0; $i < $unAvis['note']; $i++): ?>

                    ⭐

                <?php endfor; ?>

                (<?= $unAvis['note']; ?>/5)

            </p>

            <p>

                <strong>Commentaire :</strong>

                <br>

                <?= nl2br(htmlspecialchars($unAvis['commentaire'])); ?>

            </p>

            <p>

                <strong>Date :</strong>

                <?= $unAvis['dateAvis']; ?>

            </p>

            <p>

                <strong>Commande :</strong>

                #<?= $unAvis['idCommande']; ?>

            </p>

            <p>

                <strong>Statut :</strong>

                <?php if($unAvis['valide']): ?>

                    ✅ Validé

                <?php else: ?>

                    ⏳ En attente

                <?php endif; ?>

            </p>

            <?php if(!$unAvis['valide']): ?>

                <a
                    href="valide-avis.php?id=<?= $unAvis['idAvis']; ?>"
                    class="btn"
                >
                    Valider
                </a>

                <a
                    href="refuse-avis.php?id=<?= $unAvis['idAvis']; ?>"
                    class="btn"
                >
                    Refuser
                </a>

            <?php endif; ?>

        </div>

        <br>

    <?php endforeach; ?>

</main>

<?php require '../../includes/footer.php'; ?>

</body>

</html>