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

$idMenu = (int) $_GET['id'];
$erreur = '';

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

    $titre = trim($_POST['titre']);

    $description = trim($_POST['description']);

    $theme = trim($_POST['theme']);

    $regime = trim($_POST['regime']);

    $nbPersonnesMin = (int) $_POST['nbPersonnesMin'];

    $prixParPersonne = (float) $_POST['prixParPersonne'];

    $stock = (int) $_POST['stock'];

    $conditions = trim($_POST['conditions']);

    if (
    empty($titre) ||
    empty($description) ||
    empty($theme) ||
    empty($regime) ||
    empty($nbPersonnesMin) ||
    empty($prixParPersonne) ||
    empty($stock) ||
    empty($conditions)
    ) {

    $erreur = 'Tous les champs sont obligatoires';
    
    }else{
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
}
?>





<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Modifier un menu</title>

    <link rel="stylesheet" href="../../css/admin_client/admin_client2.css">
    <link rel="stylesheet" href="../../css/vite&gourmand.css">
    <script src="/js/navbar.js" defer></script>

</head>

<body>
    <?php require '../../includes/navbar.php';?>

    <main class="container">

        <h1 class="page-title">Modifier un menu</h1>

        <div class="card">

            <form method="POST" class="form-grid">

                <input
                    type="text"
                    name="titre"
                    value="<?= htmlspecialchars($menu['titre']); ?>"
                    required
                >

                <textarea
                    name="description">
                    <?= htmlspecialchars($menu['description']); ?></textarea>

                <select name="theme">

                    <option value="Tradition"
                        <?= htmlspecialchars($menu['theme']) === 'Tradition' ? 'selected' : ''; ?>>
                        Tradition
                    </option>

                    <option value="Noël"
                        <?= htmlspecialchars($menu['theme']) === 'Noël' ? 'selected' : ''; ?>>
                        Noël
                    </option>

                    <option value="Vegetal"
                        <?= htmlspecialchars($menu['theme']) === 'Vegetal' ? 'selected' : ''; ?>>
                        Vegetal
                    </option>

                    <option value="Pizza"
                        <?= htmlspecialchars($menu['theme']) === 'Pizza' ? 'selected' : ''; ?>>
                        Pizza
                    </option>

                    <option value="Enfant"
                        <?= htmlspecialchars($menu['theme']) === 'Enfant' ? 'selected' : ''; ?>>
                        Enfant
                    </option>

                </select>

                <select name="regime">

                    <option value="Classique"
                        <?= htmlspecialchars($menu['regime']) === 'Classique' ? 'selected' : ''; ?>>
                        Classique
                    </option>

                    <option value="Vegan"
                        <?= htmlspecialchars($menu['regime']) === 'Vegan' ? 'selected' : ''; ?>>
                        Vegan
                    </option>

                </select>

                <input
                    type="number"
                    name="nbPersonnesMin"
                    value="<?= (int) $menu['nbPersonnesMin']; ?>"
                    required
                >

                <input
                    type="number"
                    step="0.01"
                    name="prixParPersonne"
                    value="<?= (float) $menu['prixParPersonne']; ?>"
                    required
                >

                <input
                    type="number"
                    name="stock"
                    value="<?= (int) $menu['stock']; ?>"
                >

                <textarea
                    name="conditions"
                ><?= htmlspecialchars($menu['conditions']); ?></textarea>

                <button type="submit" class="btn">
                    Modifier le menu
                </button>

            </form>

        </div>

    </main>

</body>

<?php require '../../includes/footer.php'; ?>

</html>