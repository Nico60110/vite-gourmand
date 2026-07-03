<?php
require '../../config/database.php';
require '../../config/auth-admin.php';

$sql = 'SELECT * FROM horaire';
$query = $pdo->prepare($sql);
$query->execute();
$horaires = $query->fetchAll();

?>



<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Gestion des horaires</title>

    <link rel="stylesheet" href="../../css/admin_client/admin_client2.css">
    <link rel="stylesheet" href="../../css/vite&gourmand.css">
    <script src="/vite_gourmand/js/navbar.js" defer></script>

</head>

<body>

<?php require '../../includes/navbar.php';?>

<main class="container">

    <h1 class="page-title">Gestion des horaires</h1>

    <div class="card">

        <?php foreach($horaires as $horaire): ?>

            <div class="horaire-item">

                <h3><?= $horaire['jour']; ?></h3>

                <p>

                    <?php

                    if(
                        $horaire['heureOuverture'] === '00:00:00'
                        &&
                        $horaire['heureFermeture'] === '00:00:00'
                    ){

                        echo "Fermé";

                    } else {

                        echo $horaire['heureOuverture']
                             . " - "
                             . $horaire['heureFermeture'];

                    }

                    ?>

                </p>

                <div class="actions">

                    <a
                        href="edit-horaire.php?id=<?= $horaire['idHoraire']; ?>"
                        class="btn"
                    >
                        Modifier
                    </a>

                    <a
                        href="delete-horaire.php?id=<?= $horaire['idHoraire']; ?>"
                        class="btn btn-delete"
                    >
                        Supprimer
                    </a>

                </div>

            </div>

            <hr>

        <?php endforeach; ?>

    </div>

</main>

<?php require '../../includes/footer.php'; ?>

</body>

</html>