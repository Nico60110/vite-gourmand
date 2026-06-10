<?php

require '../../config/database.php';
require '../../config/auth-admin.php';

// =========================
// FILTRES
// =========================

$statut = $_GET['statut'] ?? '';
$client = $_GET['client'] ?? '';

// =========================
// REQUETE
// =========================

$sql = "
SELECT c.*, u.nom, u.prenom
FROM commande c
INNER JOIN utilisateur u
ON c.idUtilisateur = u.idUtilisateur
WHERE 1=1
";

$params = [];

// Filtre statut

if(!empty($statut)){

    $sql .= " AND c.statut = ?";

    $params[] = $statut;
}

// Filtre client

if(!empty($client)){

    $sql .= " AND (
        u.nom LIKE ?
        OR u.prenom LIKE ?
    )";

    $params[] = "%$client%";
    $params[] = "%$client%";
}

// Tri

$sql .= " ORDER BY c.dateCommande DESC";

// Exécution

$query = $pdo->prepare($sql);

$query->execute($params);

$commandes = $query->fetchAll();

?>




<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin-commandes</title>
    <link rel="stylesheet" href="../../css/admin/commandes-admin.css">
    <link rel="stylesheet" href="../../css/vite&gourmand.css">
</head>
<body>
    <?php require '../../includes/navbar.php';?>
    
<section class="container">

    <form method="GET" class="filter-form">

        <select name="statut">

            <option value="">
                Tous les statuts
            </option>

            <option value="EN_ATTENTE">
                EN_ATTENTE
            </option>

            <option value="ACCEPTEE">
                ACCEPTEE
            </option>

            <option value="EN_PREPARATION">
                EN_PREPARATION
            </option>

            <option value="EN_LIVRAISON">
                EN_LIVRAISON
            </option>

            <option value="LIVREE">
                LIVREE
            </option>

            <option value="TERMINEE">
                TERMINEE
            </option>

            <option value="ANNULEE">
                ANNULEE
            </option>

        </select>

        <input
            type="text"
            name="client"
            placeholder="Nom du client">

        <button type="submit" class="btn">
            Filtrer
        </button>

    </form>

    <?php foreach($commandes as $commande): ?>

        <div class="card">

            <h2>
                Commande #<?= $commande['idCommande']; ?>
            </h2>

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

<?php require '../../includes/footer.php'; ?>
    
</body>
</html>