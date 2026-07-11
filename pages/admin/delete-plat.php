<?php
require '../../config/database.php';
require '../../config/auth-admin.php';

if(!isset($_GET['id'])){

    header("Location: plat.php");

    exit;
}

$idPlat = (int) $_GET['id'];

$sql = 'SELECT * FROM plat WHERE idPlat = ?';
$query = $pdo->prepare($sql);
$query->execute([$idPlat]);
$plat = $query->fetch();

if(!$plat){

    header("Location: plat.php");

    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    
    $sql = 'DELETE FROM plat WHERE idPlat = ?';
    $query = $pdo->prepare($sql);
    $query->execute([$idPlat]);
    header("Location: plat.php");
    exit;

}

?>




<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Supprimer un plat</title>

    <link rel="stylesheet" href="../../css/admin_client/admin_client2.css">
    <link rel="stylesheet" href="../../css/vite&gourmand.css">
    <script src="/js/navbar.js" defer></script>

</head>

<body>
    <?php require '../../includes/navbar.php';?>

    <main class="container">

        <h1 class="page-title">Supprimer un plat</h1>

        <div class="card">

            <h2><?= htmlspecialchars($plat['nom']); ?></h2>

            <p>
                <strong>Type :</strong>
                <?= htmlspecialchars(ucfirst($plat['type'])); ?>
            </p>

            <br>

            <?php if(!empty($plat['photo'])): ?>

                <img
                    src="../../images/plats/<?= htmlspecialchars($plat['photo']); ?>"
                    alt="<?= htmlspecialchars($plat['nom']); ?>"
                    class="preview-image"
                >

            <?php endif; ?>

            <br>

            <p>
                Êtes-vous sûr de vouloir supprimer ce plat ?
            </p>

            <br>

            <form method="POST">

                <button type="submit" class="btn btn-delete">
                    Supprimer définitivement
                </button>

            </form>

        </div>

    </main>

    <?php require '../../includes/footer.php'; ?>

</body>

</html>