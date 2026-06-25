<?php

require '../../config/auth-admin.php';
require '../../config/database.php';

if(!isset($_GET['id'])){

    header("Location: commandes.php");
    exit;
}

$idCommande = $_GET['id'];

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

if($_SERVER["REQUEST_METHOD"] === "POST"){

    $statut = $_POST['statut'];

    $restitutionMateriel =
    isset($_POST['restitutionMateriel']) ? 1 : 0;

    if($commande['pretMateriel'] == 1 && $restitutionMateriel == 1){
        $statut = 'TERMINEE';
    }

    // Cas annulation
    if($statut === 'ANNULEE'){

       $modeContact = trim($_POST['modeContact'] ?? '');
       $motif = trim($_POST['motif'] ?? '');

        if(empty($modeContact) || empty($motif)){

            $erreur = "Le mode de contact et le motif sont obligatoires.";

        }else{

            // Mise à jour commande
            $sql = "
            UPDATE commande
            SET 
                statut = ?,
                restitutionMateriel = ?
            WHERE idCommande = ?
            ";

            $query = $pdo->prepare($sql);
            $query->execute([
                $statut, 
                $restitutionMateriel,
                $idCommande
            ]);

            // Historique
            $commentaire =
                "Mode de contact : " .
                $modeContact .
                " | Motif : " .
                $motif;

            $sql = "
            INSERT INTO historique_statut
            (
                statut,
                commentaire,
                idCommande
            )
            VALUES
            (
                ?, ?, ?
            )
            ";

            $query = $pdo->prepare($sql);

            $query->execute([
                'ANNULEE',
                $commentaire,
                $idCommande
            ]);

            header("Location: commandes-detail.php?id=" . $idCommande);
            exit;
        }

    }else{

        // Changement de statut normal

        $sql = "
        UPDATE commande
        SET statut = ?
        WHERE idCommande = ?
        ";

        $query = $pdo->prepare($sql);
        $query->execute([$statut, $idCommande]);

        $sql = "
        INSERT INTO historique_statut
        (
            statut,
            idCommande
        )
        VALUES
        (
            ?, ?
        )
        ";

        $query = $pdo->prepare($sql);
        $query->execute([
            $statut,
            $idCommande
        ]);

        header("Location: commandes-detail.php?id=" . $idCommande);
        exit;
    }
}
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../css/admin/commandes-admin.css">
    <link rel="stylesheet" href="../../css/vite&gourmand.css">
    <script src="/vite_gourmand/js/navbar.js" defer></script>
</head>
<body>
    <?php require '../../includes/navbar.php';?>

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

            <option value="EN_ATTENTE"
                <?= $commande['statut'] === 'EN_ATTENTE' ? 'selected' : ''; ?>>
                EN_ATTENTE
            </option>

            <option value="ACCEPTEE"
                <?= $commande['statut'] === 'ACCEPTEE' ? 'selected' : ''; ?>>
                ACCEPTEE
            </option>

            <option value="EN_PREPARATION"
                <?= $commande['statut'] === 'EN_PREPARATION' ? 'selected' : ''; ?>>
                EN_PREPARATION
            </option>

            <option value="EN_LIVRAISON"
                <?= $commande['statut'] === 'EN_LIVRAISON' ? 'selected' : ''; ?>>
                EN_LIVRAISON
            </option>

            <option value="LIVREE"
                <?= $commande['statut'] === 'LIVREE' ? 'selected' : ''; ?>>
                LIVREE
            </option>

            <option value="EN_ATTENTE_MATERIEL"
                <?= $commande['statut'] === 'EN_ATTENTE_MATERIEL' ? 'selected' : ''; ?>>
                EN_ATTENTE_MATERIEL
            </option>

            <option value="TERMINEE"
                <?= $commande['statut'] === 'TERMINEE' ? 'selected' : ''; ?>>
                TERMINEE
            </option>

            <option value="ANNULEE"
                <?= $commande['statut'] === 'ANNULEE' ? 'selected' : ''; ?>>
                ANNULEE
            </option>

        </select>

        <br><br>

        <h3>Informations d'annulation</h3>

        <p>
            À compléter uniquement si la commande est annulée.
        </p>

        <label for="modeContact">
            Mode de contact
        </label>

        <select name="modeContact" id="modeContact">

            <option value="">
                Choisir un mode de contact
            </option>

            <option value="GSM">
                GSM
            </option>

            <option value="EMAIL">
                Email
            </option>

        </select>

        <br><br>

        <label for="motif">
            Motif de l'annulation
        </label>

        <textarea
            name="motif"
            id="motif"
            placeholder="Expliquez la raison de l'annulation"></textarea>

        <br><br>

        <?php if(isset($erreur)): ?>

            <p class="error">
                <?= $erreur; ?>
            </p>

        <?php endif; ?>

        <?php if($commande['pretMateriel'] == 1): ?>

            <label>
                <input
                    type="checkbox"
                    name="restitutionMateriel"
                    value="1"
                    <?= $commande['restitutionMateriel'] ? 'checked' : ''; ?>
                >
                Matériel restitué
            </label>

            <br><br>

        <?php endif; ?>

        <button type="submit" class="btn">
            Enregistrer les modifications
        </button>

    </form>

</section>

</main>

<?php require '../../includes/footer.php'; ?>


    
</body>
</html>