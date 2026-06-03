<?php

require '../../config/database.php';

// =========================
// VERIFICATION ID
// =========================

if(!isset($_GET['id'])){

    header("Location: menus.php");

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

if(!$menu){

    header("Location: menus.php");

    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $sql = "DELETE FROM menu WHERE idMenu = ?";
    $query = $pdo->prepare($sql);
    $query->execute([$idMenu]);
    header("Location: menus.php");

    exit;
}
?>


<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Supprimer un menu</title>

    <link rel="stylesheet" href="../../css/admin_plat-menu.css">

</head>

<body>

    <main class="container">

        <h1>Supprimer un menu</h1>

        <div class="form-card">

            <h2><?= $menu['titre']; ?></h2>

            <p><?= $menu['description']; ?></p>

            <br>

            <p>
                <strong>Prix :</strong>
                <?= $menu['prixBase']; ?> €
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

</body>

</html>