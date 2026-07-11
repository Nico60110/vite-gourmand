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
AND statut = 'TERMINEE'
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

// =========================
// VERIFICATION AVIS EXISTANT
// =========================

$sql = "
SELECT *
FROM avis
WHERE idCommande = ?
";

$query = $pdo->prepare($sql);

$query->execute([$idCommande]);

$avisExistant = $query->fetch();

if($avisExistant){

    $erreur = "Vous avez déjà laissé un avis pour cette commande.";
}

// =========================
// ENREGISTREMENT AVIS
// =========================

if($_SERVER['REQUEST_METHOD'] === 'POST' && !$avisExistant){

    $note = (int) $_POST['note'];
    $commentaire = trim($_POST['commentaire']);

    if($note < 1 || $note > 5){

        $erreur = "La note doit être comprise entre 1 et 5.";

    }else{

        $sql = "
        INSERT INTO avis
        (
            note,
            commentaire,
            valide,
            idCommande,
            idUtilisateur
        )
        VALUES
        (
            ?, ?, 0, ?, ?
        )
        ";

        $query = $pdo->prepare($sql);

        $query->execute([
            $note,
            $commentaire,
            $idCommande,
            $idUtilisateur
        ]);

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

    <title>Laisser un avis</title>

    <link rel="stylesheet" href="../../css/vite&gourmand.css">
    <link rel="stylesheet" href="../../css/admin_client/admin_client1.css">
    <script src="/js/navbar.js" defer></script>

</head>

<body>

<?php require '../../includes/navbar.php'; ?>

<main class="container">

    <h1 class="page-title">Laisser un avis</h1>

    <?php if(isset($erreur)): ?>

        <p class="error">
            <?= htmlspecialchars($erreur); ?>
        </p>

    <?php endif; ?>

    <?php if(!$avisExistant): ?>

        <form method="POST" class="card">

            <div>

                <label>Note</label>

                <select name="note" required>

                    <option value="">Choisir</option>

                    <option value="1">1 ⭐</option>
                    <option value="2">2 ⭐⭐</option>
                    <option value="3">3 ⭐⭐⭐</option>
                    <option value="4">4 ⭐⭐⭐⭐</option>
                    <option value="5">5 ⭐⭐⭐⭐⭐</option>

                </select>

            </div>

            <br>

            <div>

                <label>Commentaire</label>

                <textarea
                    name="commentaire"
                    rows="6"
                    placeholder="Votre avis..."
                    required
                ></textarea>

            </div>

            <br>

            <button type="submit" class="btn">
                Envoyer mon avis
            </button>

        </form>

    <?php endif; ?>

</main>

<?php require '../../includes/footer.php'; ?>

</body>
</html>