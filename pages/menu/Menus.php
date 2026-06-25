<?php 
require '../../config/database.php';

$prixMax = $_GET['prixMax'] ?? '';
$theme = $_GET['theme'] ?? '';
$regime = $_GET['regime'] ?? '';
$personnesMin = $_GET['personnesMin'] ?? '';

$sql = "SELECT * FROM menu WHERE 1=1";

$params = [];

if(!empty($prixMax)){

    $sql .= " AND prixParPersonne <= ?";

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

    $sql .= " AND nbPersonnesMin >= ?";

    $params[] = $personnesMin;
}



$query = $pdo->prepare($sql);
$query->execute($params);

$menus = $query->fetchAll();





?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../css/menus.css">
    <link rel="stylesheet" href="../../css/vite&gourmand.css">
    <script src="/vite_gourmand/js/navbar.js" defer></script>
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



  <form method="GET" class="filters">

    <input type="number" name="prixMax" placeholder="Prix max" value="<?= $_GET['prixMax'] ?? ''; ?>">

    <select name="theme">

        <option value="">
            Thème
        </option>

        <option value="Noël">
            Noël
        </option>

        <option value="Pizza">
            Pizza
        </option>

        <option value="Classique">
            Classique
        </option>

    </select>

    <select name="regime">

      <option value="">
          Régime
      </option>

      <option value="Vegan">
          Vegan
      </option>

      <option value="Classique">
          Classique
      </option>

    </select>

    <input type="number" name="personnesMin" placeholder="Nombre minimum">

    <input class="btn" type="submit" value="Valider">

</form>

  <!-- MENUS -->

  <section class="menu-section">

    

      <!-- CARD -->

      <div class="menu-grid">

<?php foreach($menus as $menu): ?>
    <?php $prixMin = $menu['nbPersonnesMin'] * $menu['prixParPersonne']?>

    <div class="menu-card">

        <img src="https://images.unsplash.com/photo-1547592180-85f173990554?q=80&w=1200" alt="Menu">

        <div class="menu-content">

            <span class="badge">
                <?= $menu['theme']; ?>
            </span>

            <h2><?= $menu['titre']; ?></h2>

            <p><?= $menu['description']; ?></p>

            <div class="info">
                Minimum <?= $menu['nbPersonnesMin']; ?> personnes • <?= $prixMin; ?> €
            </div>

            <a href="menu-detail.php?id=<?= $menu['idMenu']; ?>" class="btn">
                Voir le détail
            </a>
           

        </div>

    </div>

<?php endforeach; ?>

  </section>

  <?php require '../../includes/footer.php'; ?>
    
</body>
</html>