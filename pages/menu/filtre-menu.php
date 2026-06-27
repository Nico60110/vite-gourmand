<?php

require '../../config/database.php';

$prixMax = $_GET['prixMax'] ?? '';
$theme = $_GET['theme'] ?? '';
$regime = $_GET['regime'] ?? '';
$personnesMin = $_GET['personnesMin'] ?? '';


$sql = "SELECT * FROM menu WHERE 1=1";

$params = [];

if(!empty($prixMax)){

    $sql .= " AND (prixParPersonne * nbPersonnesMin) <= ?";

    $params[] = $prixMax;
}

if(!empty($theme)){

    $sql .= " AND theme = ?";

    $params[] = $theme;
}

if(!empty($regime)){

    $sql .= " AND regime = ?";

    $params[] = $regime;
}

if(!empty($personnesMin)){

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
            Minimum <?= $menu['nbPersonnesMin']; ?> personnes • <?= $prixMin; ?> €
        </div>

        <a
            href="menu-detail.php?id=<?= $menu['idMenu']; ?>"
            class="btn">

            Voir le détail

        </a>

    </div>

</div>

<?php endforeach; ?>