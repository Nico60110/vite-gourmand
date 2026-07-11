<?php

session_start();

require '../../config/database.php';

if(!isset($_SESSION['user'])){

    header('Location: ../auth/login.php');
    exit;
}

$idUtilisateur = (int) $_SESSION['user']['idUtilisateur'];

$sql = "
SELECT
    c.*,
    m.titre AS menuTitre
FROM commande c

INNER JOIN commande_menu cm
ON c.idCommande = cm.idCommande

INNER JOIN menu m
ON cm.idMenu = m.idMenu

WHERE c.idUtilisateur = ?

ORDER BY c.dateCommande DESC
";

$query = $pdo->prepare($sql);
$query->execute([$idUtilisateur]);

$commandes = $query->fetchAll();

?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes commandes</title>
    <link rel="stylesheet" href="../../css/vite&gourmand.css">
    <link rel="stylesheet" href="../../css/admin_client/admin_client1.css">
    <script src="/js/navbar.js" defer></script>

</head>

<body>

<?php require '../../includes/navbar.php'; ?>

<main class="container">

    <h1 class="page-title">Mes commandes</h1>

    <?php if(empty($commandes)): ?>

        <p>Aucune commande trouvée.</p>

    <?php endif; ?>

    <?php foreach($commandes as $commande): ?>

    <div class="card">

        <h3>
            Commande #<?= (int) $commande['idCommande']; ?>
        </h3>

        <p>
            Menu :
            <strong><?= htmlspecialchars($commande['menuTitre']); ?></strong>
        </p>

        <p>
            Nombre de personnes :
            <?= (int) $commande['nbPersonnes']; ?>
        </p>

        <p>
            Prêt de matériel :
            <?= (int) $commande['pretMateriel'] == 1 ? 'Oui' : 'Non'; ?>
        </p>

        <p>
            Prix total :
            <?= number_format((float) $commande['prixTotal'], 2, ',', ''); ?> €
        </p>

        <p>
            Date livraison :
            <?= htmlspecialchars($commande['dateLivraison']); ?>
            à
            <?= htmlspecialchars($commande['heureLivraison']); ?>
        </p>

        <p>
            Statut :
            <?= htmlspecialchars($commande['statut']); ?>
        </p>

        <?php if($commande['statut'] === 'EN_ATTENTE'): ?>

            <a
                href="update-commande.php?id=<?= (int) $commande['idCommande']; ?>"
                class="btn"
            >
                Modifier
            </a>

            <a
                href="cancel-commande.php?id=<?= (int) $commande['idCommande']; ?>"
                class="btn"
            >
                Annuler
            </a>

        <?php endif; ?>

        <?php if($commande['statut'] !== 'EN_ATTENTE'): ?>

            <a
                href="suivie-commande.php?id=<?= (int) $commande['idCommande']; ?>"
                class="btn">
                Suivre la commande
            </a>

        <?php endif; ?>

        <?php if($commande['statut'] === 'TERMINEE'): ?>

            <a
                href="avis.php?id=<?= (int) $commande['idCommande']; ?>"
                class="btn"
            >
                Donner un avis
            </a>

        <?php endif; ?>

    </div>

    <?php endforeach; ?>

</main>

<?php require '../../includes/footer.php'; ?>

</body>

</html>