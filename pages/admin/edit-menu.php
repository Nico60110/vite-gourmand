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

if(!$menu){

    header("Location: admin-menu.php");

    exit;
}

// =========================
// UPDATE MENU
// =========================

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $titre = $_POST['titre'];

    $description = $_POST['description'];

    $theme = $_POST['theme'];

    $regime = $_POST['regime'];

    $nbPersonnesMin = $_POST['nbPersonnesMin'];

    $prixParPersonne = $_POST['prixParPersonne'];

    $stock = $_POST['stock'];

    $conditions = $_POST['conditions'];

    $sql = "UPDATE menu
            SET
                titre = ?,
                description = ?,
                theme = ?,
                regime = ?,
                nbPersonnesMin = ?,
                prixParPersonne = ?,
                stock = ?,
                conditions = ?
            WHERE idMenu = ?";

    $query = $pdo->prepare($sql);

    $query->execute([
        $titre,
        $description,
        $theme,
        $regime,
        $nbPersonnesMin,
        $prixParPersonne,
        $stock,
        $conditions,
        $idMenu
    ]);

    header("Location: admin-menu.php");

    exit;
}
?>





<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Modifier un menu</title>

    <link rel="stylesheet" href="../../css/admin_plat-menu.css">
    <link rel="stylesheet" href="../../css/vite&gourmand.css">
    <script src="/vite_gourmand/js/navbar.js" defer></script>

</head>

<body>
    <?php require '../../includes/navbar.php';?>

    <main class="container">

        <h1>Modifier un menu</h1>

        <div class="form-card">

            <form method="POST" class="form-grid">

                <input
                    type="text"
                    name="titre"
                    value="<?= $menu['titre']; ?>"
                    required
                >

                <textarea
                    name="description"
                ><?= $menu['description']; ?></textarea>

                <select name="theme">

                    <option value="Tradition"
                        <?= $menu['theme'] === 'Tradition' ? 'selected' : ''; ?>>
                        Tradition
                    </option>

                    <option value="Noël"
                        <?= $menu['theme'] === 'Noël' ? 'selected' : ''; ?>>
                        Noël
                    </option>

                    <option value="Vegetal"
                        <?= $menu['theme'] === 'Vegetal' ? 'selected' : ''; ?>>
                        Vegetal
                    </option>

                    <option value="Pizza"
                        <?= $menu['theme'] === 'Pizza' ? 'selected' : ''; ?>>
                        Pizza
                    </option>

                </select>

                <select name="regime">

                    <option value="Classique"
                        <?= $menu['regime'] === 'Classique' ? 'selected' : ''; ?>>
                        Classique
                    </option>

                    <option value="Vegan"
                        <?= $menu['regime'] === 'Vegan' ? 'selected' : ''; ?>>
                        Vegan
                    </option>

                </select>

                <input
                    type="number"
                    name="nbPersonnesMin"
                    value="<?= $menu['nbPersonnesMin']; ?>"
                    required
                >

                <input
                    type="number"
                    step="0.01"
                    name="prixParPersonne"
                    value="<?= $menu['prixParPersonne']; ?>"
                    required
                >

                <input
                    type="number"
                    name="stock"
                    value="<?= $menu['stock']; ?>"
                >

                <textarea
                    name="conditions"
                ><?= $menu['conditions']; ?></textarea>

                <button type="submit" class="btn">
                    Modifier le menu
                </button>

            </form>

        </div>

    </main>

</body>

<?php require '../../includes/footer.php'; ?>

</html>