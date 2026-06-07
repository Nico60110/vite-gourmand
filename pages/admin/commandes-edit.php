<?php

require '../../config/database.php';

// =========================
// VERIFICATION ID
// =========================

if(!isset($_GET['id'])){

    header("Location: commandes.php");
    exit;
}

$idCommande = $_GET['id'];

// =========================
// RECUPERATION COMMANDE
// =========================

$sql = "
SELECT c.*, u.nom, u.prenom
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
// MODIFICATION
// =========================

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $statut = $_POST['statut'];
    $commentaire = $_POST['commentaire'];

    // Mise à jour du statut

    $sql = "
    UPDATE commande
    SET statut = ?
    WHERE idCommande = ?
    ";

    $query = $pdo->prepare($sql);

    $query->execute([
        $statut,
        $idCommande
    ]);

    // Historique

    $sql = "
    INSERT INTO historique_statut
    (
        statut,
        commentaire,
        idCommande
    )
    VALUES (?, ?, ?)
    ";

    $query = $pdo->prepare($sql);

    $query->execute([
        $statut,
        $commentaire,
        $idCommande
    ]);

    header("Location: commandes-detail.php?id=" . $idCommande);

    exit;
}

?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../css/admin/commandes-admin.css">
</head>
<body>

<main class="container">

    <h1 class="page-title">
        Modifier la commande
    </h1>

    <section class="card">

        <h2>
            Commande #<?= $commande['idCommande']; ?>
        </h2>

        <p>
            <strong>Client :</strong>
            <?= $commande['prenom']; ?>
            <?= $commande['nom']; ?>
        </p>

        <p>
            <strong>Date livraison :</strong>
            <?= $commande['dateLivraison']; ?>
        </p>

        <p>
            <strong>Prix total :</strong>
            <?= $commande['prixTotal']; ?> €
        </p>

    </section>

    <section class="card">

        <h2>Modifier le statut</h2>

        <form method="POST">

            <label for="statut">
                Nouveau statut
            </label>

            <select name="statut" id="statut">

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

            <br><br>

            <label for="commentaire">
                Commentaire
            </label>

            <textarea
                name="commentaire"
                id="commentaire"
                placeholder="Ajouter un commentaire"></textarea>

            <button type="submit" class="btn">
                Enregistrer les modifications
            </button>

        </form>

    </section>

</main>
    
</body>
</html>