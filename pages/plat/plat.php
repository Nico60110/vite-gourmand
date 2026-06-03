<?php
require '../../config/database.php';

$sql = 'SELECT * FROM plat';
$query = $pdo->prepare($sql);
$query->execute();
$plats = $query->fetchAll();

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../css/plat.css">
</head>
<body>
   <section class="plat-section">

   <h1>Gestion des plats</h1>

    <div class="plat-grid">

        <?php foreach($plats as $plat): ?>

            <div class="plat-card">

                <img src="../../images/plats/<?=$plat['photo']; ?>" alt="<?= $plat['nom']; ?>">

                <div class="plat-content">

                    <span class="type">
                        <?= ucfirst($plat['type']); ?>
                    </span>

                    <h2><?= $plat['nom']; ?></h2>

                    <div class="actions">

                        <a href="edit-plat.php?id=<?= $plat['idPlat']; ?>" class="btn">
                            Modifier
                        </a>

                        <a href="delete-plat.php?id=<?= $plat['idPlat']; ?>" class="btn btn-delete">
                            Supprimer
                        </a>

                    </div>

                </div>

            </div>

        <?php endforeach; ?>

    </div>

</section>
    
</body>
</html>