<?php 
require '../../config/database.php';
require '../../config/auth-admin.php';

if(!isset($_GET['id'])){
    header('location:horaire.php');
    exit;
}

$idHoraire = $_GET['id'];

$sql = 'SELECT * FROM horaire WHERE idHoraire = ?';
$query = $pdo->prepare($sql);
$query->execute([$idHoraire]);
$horaire = $query->fetch();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $sql = 'DELETE FROM horaire WHERE idHoraire = ?';
    $query = $pdo->prepare($sql);
    $query->execute([$idHoraire]);

    header('location:horaire.php');
    exit;
}


?>



<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Supprimer un horaire</title>

    <link rel="stylesheet" href="../../css/admin_plat-menu.css">

</head>

<body>

<main class="container">

    <h1>Supprimer un horaire</h1>

    <div class="form-card">

        <h2><?= $horaire['jour']; ?></h2>

        <p>

            <?php if(
                $horaire['heureOuverture'] === '00:00:00'
                &&
                $horaire['heureFermeture'] === '00:00:00'
            ): ?>

                Fermé

            <?php else: ?>

                <?= $horaire['heureOuverture']; ?>
                -
                <?= $horaire['heureFermeture']; ?>

            <?php endif; ?>

        </p>

        <br>

        <p>
            Êtes-vous sûr de vouloir supprimer cet horaire ?
        </p>

        <br>

        <form method="POST">

            <button type="submit" class="btn btn-delete">
                Supprimer définitivement
            </button>

        </form>

    </div>

</main>

</body>

</html>