<?php
session_start();
require '../../config/database.php';
require '../../classes/Menu.php';

if (!isset($_GET['id']) || !ctype_digit((string) $_GET['id']) || (int) $_GET['id'] <= 0) {
    header('Location: menus.php');
    exit;
}

$idMenu = (int) $_GET['id'];
$menuRepository = new Menu($pdo);
$menu = $menuRepository->getById($idMenu);

if (!$menu) {
    header('Location: menus.php');
    exit;
}

$prixMin = $menu['prixParPersonne'] * $menu['nbPersonnesMin'];
$plats = $menuRepository->getPlats($idMenu);
$entrees = [];
$platsPrincipaux = [];
$desserts = [];
$boissons = [];

foreach ($plats as $plat) {
    switch ($plat['typeMenu']) {
        case 'entree': $entrees[] = $plat; break;
        case 'plat': $platsPrincipaux[] = $plat; break;
        case 'dessert': $desserts[] = $plat; break;
        case 'boisson': $boissons[] = $plat; break;
    }
}
$allergenesParPlat = $menuRepository->getAllergenesParPlat($plats);
?>



<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title><?= htmlspecialchars($menu['titre']); ?></title>
    <link rel="stylesheet" href="../../css/menu-detail.css">
    <link rel="stylesheet" href="../../css/vite&gourmand.css">
    <script src="/js/navbar.js" defer></script>
    <script src="/js/plat-slider.js" defer></script>
  
</head>

<body>

  <?php require '../../includes/navbar.php';?>

  <!-- HERO -->

  <section class="hero">

    <div class="hero-content">
      <h1><?= htmlspecialchars($menu['titre']); ?></h1>
      <p><?= htmlspecialchars($menu['description']); ?></p>
    </div>

  </section>

  <!-- CONTAINER -->

  <main class="container">

    <!-- TOP -->

    <section class="top-section">

      <!-- IMAGE -->

      <div class="gallery plat-slider">

        <?php foreach($plats as $index => $plat): ?>

            <?php if(!empty($plat['photo'])): ?>

                <img
                    src="/images/plats/<?= htmlspecialchars($plat['photo']); ?>"
                    alt="<?= htmlspecialchars($plat['nom']); ?>"
                    class="plat-slide <?= $index === 0 ? 'active' : ''; ?>">

            <?php endif; ?>

          <?php endforeach; ?>

          <button type="button" id="prevPlat" class="slider-btn prev">←</button>
          <button type="button" id="nextPlat" class="slider-btn next">→</button>

      </div>

      <!-- INFO -->

      <div class="menu-info">

        <span class="badge"><?=htmlspecialchars($menu['theme']); ?></span>

        <h2><?= htmlspecialchars($menu['titre']); ?></h2>

        <p>
          <?= htmlspecialchars($menu['description']); ?>
        </p>

        <div class="details">

          <div>Nombre personnes min : <?= (int) $menu['nbPersonnesMin']; ?></div>

          <div>💰 Prix par personnes : <?= number_format((float)$menu['prixParPersonne'], 2, ',', ' '); ?> €</div>

          <div>💰 Prix pour le nombre de personnes minimum : <?= number_format((float)$prixMin, 2, ',', ' '); ?> €</div>

          <div>🥗 Régime : <?= htmlspecialchars($menu['regime']); ?></div>

          <div>📦 Capacité restante : <?= (int) $menu['stock']; ?> personne(s)</div>

        </div>

        <!-- CONDITIONS -->

        <div class="conditions">

          <h3>Conditions importantes</h3>

          <p>
            <?= htmlspecialchars($menu['conditions']); ?>
          </p>

        </div>

        <?php if ((int) $menu['stock'] >= (int) $menu['nbPersonnesMin']): ?>

            <a
                href="../commande/commande.php?id=<?= (int) $menu['idMenu']; ?>"
                class="btn"
            >
                Commander ce menu
            </a>

            <p>
              <strong>
                Pour commander ce menu vous devez bénéficier d'un compte.
              </strong>
            </p>

        <?php else: ?>

            <button
                type="button"
                class="btn"
                disabled
            >
                Rupture de stock
            </button>

            <p class="stock-warning">
                Ce menu nécessite au minimum
                <?= (int) $menu['nbPersonnesMin']; ?>
                personnes, mais il ne reste que
                <?= (int) $menu['stock']; ?>
                portion(s).
            </p>

        <?php endif; ?>

      </div>

    </section>

    <!-- COMPOSITION -->

    <section class="composition">

      <h2>Composition du menu</h2>

      <!-- ENTREES -->

      <div class="category">

        <h3>Entrées</h3>

          <?php foreach($entrees as $plat): ?>

          <div class="dish">
              <h4><?= htmlspecialchars($plat['nom']); ?></h4>
              <div class="allergenes">
                <?php
                $allergenes = $allergenesParPlat[$plat['idPlat']] ?? [];
                ?>

                <?php foreach($allergenes as $allergene): ?>

                    <span><?= htmlspecialchars($allergene); ?></span>

                <?php endforeach; ?>
              </div>
          </div>

          <?php endforeach; ?>

      </div>

      <!-- PLATS -->

      <div class="category">

        <h3>Plats</h3>

        <?php foreach($platsPrincipaux as $plat): ?>

          <div class="dish">
              <h4><?= htmlspecialchars($plat['nom']); ?></h4>
              <div class="allergenes">
               <?php
                $allergenes = $allergenesParPlat[$plat['idPlat']] ?? [];
                ?>

                <?php foreach($allergenes as $allergene): ?>

                    <span><?= htmlspecialchars($allergene); ?></span>

                 <?php endforeach; ?>
              </div>
          </div>

        <?php endforeach; ?>
      </div>

      <!-- DESSERTS -->

      <div class="category">

        <h3>Desserts</h3>

        <?php foreach($desserts as $plat): ?>

          <div class="dish">
              <h4><?= htmlspecialchars($plat['nom']); ?></h4>
               <div class="allergenes">
                <?php
                $allergenes = $allergenesParPlat[$plat['idPlat']] ?? [];
                ?>

                  <?php foreach($allergenes as $allergene): ?>

                      <span><?= htmlspecialchars($allergene); ?></span>

                  <?php endforeach; ?>
                </div>
          </div>

        <?php endforeach; ?>

      </div>

        <!-- BOISSONS -->

      <div class="category">

        <h3>Boissons</h3>

        <?php foreach($boissons as $plat): ?>

          <div class="dish">
              <h4><?= htmlspecialchars($plat['nom']); ?></h4>
               <div class="allergenes">
                <?php
                $allergenes = $allergenesParPlat[$plat['idPlat']] ?? [];
                ?>

                  <?php foreach($allergenes as $allergene): ?>

                      <span><?= htmlspecialchars($allergene); ?></span>

                  <?php endforeach; ?>
                </div>
          </div>

        <?php endforeach; ?>

      </div>

    </section>

  </main>

  <?php require '../../includes/footer.php'; ?>

 

 

</body>
</html>