<?php

require '../../config/database.php';
require '../../config/auth-admin.php';

// =========================
// VERIFICATION ID
// =========================

if(!isset($_GET['id'])){

    header("Location: admin-menu.php");

    exit;
}

$idMenu = $_GET['id'];

// =========================
// RECUPERATION MENU
// =========================

$sql = "SELECT * FROM menu WHERE idMenu = ?";

$query = $pdo->prepare($sql);

$query->execute([$idMenu]);

$menu = $query->fetch();

$prixMin = $menu['nbPersonnesMin'] * $menu['prixParPersonne'];

if(!$menu){

    header("Location: admin-menu.php");

    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $sql = "DELETE FROM menu WHERE idMenu = ?";
    $query = $pdo->prepare($sql);
    $query->execute([$idMenu]);
    header("Location: admin-menu.php");

    exit;
}
?>


<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Supprimer un menu</title>

    <link rel="stylesheet" href="../../css/admin_client/admin_client2.css">
    <link rel="stylesheet" href="../../css/vite&gourmand.css">
    <script src="/vite_gourmand/js/navbar.js" defer></script>

</head>

<body>
    <?php require '../../includes/navbar.php';?>

    <main class="container">

        <h1 class="page-title">Supprimer un menu</h1>

        <div class="card">

            <h2><?= $menu['titre']; ?></h2>

            <p><?= $menu['description']; ?></p>

            <br>

            <p>
                <strong>Prix pour le nombre minimum :</strong>
                <?= $prixMin; ?> €
            </p>

            <p>
                <strong>Nombre minimum :</strong>
                <?= $menu['nbPersonnesMin']; ?> personnes
            </p>

            <br>

            <p>
                Êtes-vous sûr de vouloir supprimer ce menu ?
            </p>

            <form method="POST">

                <button type="submit" class="btn">
                    Supprimer définitivement
                </button>

            </form>

        </div>

    </main>

    <?php require '../../includes/footer.php'; ?>

</body>

</html>