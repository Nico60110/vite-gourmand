<?php
require '../../config/database.php';

$prixMax = isset($_GET['prixMax']) ? (float) $_GET['prixMax'] : 0;
$theme = $_GET['theme'] ?? '';
$regime = $_GET['regime'] ?? '';
$personnesMin = isset($_GET['personnesMin']) ? (int) $_GET['personnesMin'] : 0;


$sql = "SELECT * FROM menu WHERE 1=1";

$params = [];

if ($prixMax > 0) {
    $sql .= " AND (prixParPersonne * nbPersonnesMin) <= ?";
    $params[] = $prixMax;
}

if ($theme !== '') {
    $sql .= " AND theme = ?";
    $params[] = $theme;
}

if ($regime !== '') {
    $sql .= " AND regime = ?";
    $params[] = $regime;
}

if ($personnesMin > 0) {
    $sql .= " AND nbPersonnesMin <= ?";
    $params[] = $personnesMin;
}


$query = $pdo->prepare($sql);
$query->execute($params);

$menus = $query->fetchAll();

?>





<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menus</title>
    <link rel="stylesheet" href="../../css/menus.css">
    <link rel="stylesheet" href="../../css/filters.css">
    <link rel="stylesheet" href="../../css/vite&gourmand.css">
    <script src="/vite_gourmand/js/navbar.js" defer></script>
    <script src="/vite_gourmand/js/filtres.js" defer></script>
</head>

<body>
    <?php require '../../includes/navbar.php'; ?>

    <!-- HERO -->

    <section class="hero">

        <div>
            <h1>Nos Menus</h1>
            <p>Découvrez nos prestations gourmandes pour tous vos événements</p>
        </div>

    </section>

    <!-- FILTRES -->



    <form id="filtreForm" class="filters">

        <input type="number" name="prixMax" min="0" placeholder="Prix max" value="<?= htmlspecialchars($_GET['prixMax'] ?? ''); ?>">

        <select name="theme">

            <option value="">
                Theme
            </option>

            <option value="Noël">
                Noël
            </option>

            <option value="Pizza">
                Pizza
            </option>

            <option value="Tradition">
                Tradition
            </option>

            <option value="Vegetal">
                Vegetal
            </option>

        </select>

        <select name="regime">

            <option value="">
                regime
            </option>

            <option value="Vegan">
                Vegan
            </option>

            <option value="Classique">
                Classique
            </option>

        </select>

        <input type="number" name="personnesMin" min="1" placeholder="Nombre minimum">

    </form>

    <!-- MENUS -->

    <section class="menu-section">



        <!-- CARD -->

        <div class="menu-grid" id="listeMenus">

            <?php foreach ($menus as $menu): ?>
                <?php $prixMin = $menu['nbPersonnesMin'] * $menu['prixParPersonne'] ?>

                <div class="menu-card">

                    <img src="https://images.unsplash.com/photo-1547592180-85f173990554?q=80&w=1200" alt="Menu">

                    <div class="menu-content">

                        <span class="badge">
                            <?= htmlspecialchars($menu['theme']); ?>
                        </span>

                        <h3><?= htmlspecialchars($menu['titre']); ?></h3>

                        <p><?= htmlspecialchars($menu['description']); ?></p>

                        <div class="info">
                            Minimum <?= (int) $menu['nbPersonnesMin']; ?> personnes • <?= number_format((float)$prixMin, 2, ',', ' '); ?> €
                        </div>

                        <a href="menu-detail.php?id=<?= (int) $menu['idMenu']; ?>" class="btn">
                            Voir le détail
                        </a>


                    </div>

                </div>

            <?php endforeach; ?>
        </div>

    </section>

    <?php require '../../includes/footer.php'; ?>

</body>

</html>