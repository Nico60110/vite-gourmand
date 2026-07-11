<?php 
require '../../config/database.php';
require '../../config/auth-admin.php';

$sql = "SELECT * FROM menu";
$query = $pdo->prepare($sql);
$query->execute();
$menus = $query->fetchAll();



?>





<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin menu</title>
    <link rel="stylesheet" href="../../css/menus.css">
    <link rel="stylesheet" href="../../css/vite&gourmand.css">
    <script src="/js/navbar.js" defer></script>
</head>
<body>

  <?php require '../../includes/navbar.php';?>

  <!-- HERO -->

  <section class="hero">

    <div>
      <h1>Nos Menus</h1>
      <p>Découvrez nos prestations gourmandes pour tous vos événements</p>
    </div>

  </section>

  

  <!-- MENUS -->

  <section class="menu-section">

    <div class="add-pages">

        <a href="add-menu.php" class="btn">
            + Ajouter un menu
        </a>

    </div>

      <!-- CARD -->

      <div class="menu-grid">

<?php foreach($menus as $menu): ?>

    <?php $prixMin = $menu['prixParPersonne'] * $menu['nbPersonnesMin'];?>

    <div class="menu-card">

        <img src="https://images.unsplash.com/photo-1547592180-85f173990554?q=80&w=1200" alt="Menu">

        <div class="menu-content">

            <span class="badge">
                <?= htmlspecialchars($menu['theme']); ?>
            </span>

            <h2><?= htmlspecialchars($menu['titre']); ?></h2>

            <p><?= htmlspecialchars($menu['description']); ?></p>

            <div class="info">
                Minimum <?= (int) $menu['nbPersonnesMin']; ?> personnes • <?= number_format((float)$prixMin, 2, ',', ' '); ?> €
            </div>

            
            <a href="edit-menu.php?id=<?= (int) $menu['idMenu']; ?>" class="btn">
                Modifier
            </a>

          <a href="delete.php?id=<?= (int) $menu['idMenu']; ?>" class="btn">
                Supprimer
          </a>

        </div>

    </div>

<?php endforeach; ?>

  </section>

  <?php require '../../includes/footer.php'; ?>
    
</body>
</html>