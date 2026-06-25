<?php 
require '../../config/database.php';

// =========================
// VERIFICATION ID
// =========================
if(!isset($_GET['id'])){

    header("Location: menus.php");

    exit;
}

// =========================
// RECUPERATION ID
// =========================
$idMenu = $_GET['id'];

// =========================
// REQUETE SQL
// =========================
$sql = 'SELECT * FROM menu WHERE idMenu = ?';
$query = $pdo->prepare($sql);
$query->execute([$idMenu]);
$menu = $query->fetch();

$prixMin = $menu['prixParPersonne'] * $menu['nbPersonnesMin'];

// =========================
// SI MENU INTROUVABLE
// =========================
if(!$menu){

    header("Location: menus.php");

    exit;
}

$sql = 'SELECT p.*, mp.typeMenu
        FROM menu_plat mp
        INNER JOIN plat p
        ON mp.idPlat = p.idPlat
        WHERE mp.idMenu = ?';

$query = $pdo->prepare($sql);
$query->execute([$idMenu]);
$plats = $query->fetchAll();


$entrees = [];
$platsPrincipaux = [];
$desserts = [];

foreach($plats as $plat){

    if($plat['typeMenu'] === 'entree'){
        $entrees[] = $plat;
    }

    if($plat['typeMenu'] === 'plat'){
        $platsPrincipaux[] = $plat;
    }

    if($plat['typeMenu'] === 'dessert'){
        $desserts[] = $plat;
    }
}

$sql = "SELECT pa.idPlat, a.nom
        FROM plat_allergene pa
        INNER JOIN allergene a
        ON pa.idAllergene = a.idAllergene";

$query = $pdo->prepare($sql);
$query->execute();

$resultatsAllergenes = $query->fetchAll();

$allergenesParPlat = [];

foreach($resultatsAllergenes as $allergene){

    $allergenesParPlat[$allergene['idPlat']][] = $allergene['nom'];
}

?>



<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title><?= $menu['titre']; ?></title>
  <!-- GOOGLE FONT -->
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../css/menu-detail.css">
    <link rel="stylesheet" href="../../css/vite&gourmand.css">
    <script src="/vite_gourmand/js/navbar.js" defer></script>
  
</head>

<body>

  <?php require '../../includes/navbar.php';?>

  <!-- HERO -->

  <section class="hero">

    <div class="hero-content">
      <h1><?= $menu['titre']; ?></h1>
      <p><?= $menu['description']; ?></p>
    </div>

  </section>

  <!-- CONTAINER -->

  <main class="container">

    <!-- TOP -->

    <section class="top-section">

      <!-- IMAGE -->

      <div class="gallery">

        <img src="https://images.unsplash.com/photo-1547592180-85f173990554?q=80&w=1400" alt="Menu Gourmet">

      </div>

      <!-- INFO -->

      <div class="menu-info">

        <span class="badge"><?= $menu['theme']; ?></span>

        <h2><?= $menu['titre']; ?></h2>

        <p>
          <?= $menu['description']; ?>
        </p>

        <div class="details">

          <div>Nombre personnes min : <?= $menu['nbPersonnesMin']; ?></div>

          <div>💰 Prix par personnes : <?= $menu['prixParPersonne']; ?></div>

          <div>💰 Prix pour le nombre de personnes minimum : <?= number_format($prixMin, 2); ?></div>

          <div>🥗 Régime : <?= $menu['regime']; ?></div>

          <div>📦 Stock disponible : <?= $menu['stock']; ?></div>

        </div>

        <!-- CONDITIONS -->

        <div class="conditions">

          <h3>Conditions importantes</h3>

          <p>
            <?= $menu['conditions']; ?>
          </p>

        </div>

        <a href="../commande/commande.php?id=<?= $menu['idMenu']; ?>" class="btn">
          Commander ce menu
        </a>

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
              <h4><?= $plat['nom']; ?></h4>
              <div class="allergenes">
                <?php
                $allergenes = $allergenesParPlat[$plat['idPlat']] ?? [];
                ?>

                <?php foreach($allergenes as $allergene): ?>

                    <span><?= $allergene; ?></span>

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
              <h4><?= $plat['nom']; ?></h4>
              <div class="allergenes">
               <?php
                $allergenes = $allergenesParPlat[$plat['idPlat']] ?? [];
                ?>

                <?php foreach($allergenes as $allergene): ?>

                    <span><?= $allergene; ?></span>

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
              <h4><?= $plat['nom']; ?></h4>
               <div class="allergenes">
                <?php
                $allergenes = $allergenesParPlat[$plat['idPlat']] ?? [];
                ?>

                  <?php foreach($allergenes as $allergene): ?>

                      <span><?= $allergene; ?></span>

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