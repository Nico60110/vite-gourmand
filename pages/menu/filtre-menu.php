<?php

require '../../config/database.php';

$prixMax = isset($_GET['prixMax']) ? (float) $_GET['prixMax'] : 0;
$theme = isset($_GET['theme']) ? $_GET['theme'] : '';
$regime = isset($_GET['regime']) ? $_GET['regime'] : '';
$personnesMin = isset($_GET['personnesMin']) ? (int) $_GET['personnesMin'] : 0;


$sql = "SELECT * FROM menu WHERE 1=1";

$params = [];

if($prixMax > 0){

    $sql .= " AND (prixParPersonne * nbPersonnesMin) <= ?";

    $params[] = $prixMax;
}

if($theme !== ''){

    $sql .= " AND theme = ?";

    $params[] = $theme;
}

if($regime !== ''){

    $sql .= " AND regime = ?";

    $params[] = $regime;
}

if($personnesMin > 0){

    $sql .= " AND nbPersonnesMin <= ?";

    $params[] = $personnesMin;
}

$query = $pdo->prepare($sql);
$query->execute($params);

$menus = $query->fetchAll();

foreach($menus as $menu):

    $prixMin = $menu['nbPersonnesMin'] * $menu['prixParPersonne'];
?>

<div class="menu-card">

    <img
        src="https://images.unsplash.com/photo-1547592180-85f173990554?q=80&w=1200"
        alt="Menu">

    <div class="menu-content">

        <span class="badge">
            <?= htmlspecialchars($menu['theme']); ?>
        </span>

        <h2>
            <?= htmlspecialchars($menu['titre']); ?>
        </h2>

        <p>
            <?= htmlspecialchars($menu['description']); ?>
        </p>

        <div class="info">
            Minimum <?= (int) $menu['nbPersonnesMin']; ?> personnes • <?= number_format((float)$prixMin, 2, ',', ' '); ?> €
        </div>

        <a
            href="menu-detail.php?id=<?= (int) $menu['idMenu']; ?>"
            class="btn">

            Voir le détail

        </a>

    </div>

</div>

<?php endforeach; ?>