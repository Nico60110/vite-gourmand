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

// =========================
// SI MENU INTROUVABLE
// =========================
if(!$menu){

    header("Location: menus.php");

    exit;
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
  
</head>

<body>

  <!-- HERO -->

  <section class="hero">

    <div class="hero-content">
      <h1>"<?= $menu['titre']; ?></h1>
      <p>"<?= $menu['description']; ?></p>
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

        <span class="badge">"<?= $menu['theme']; ?></span>

        <h2>"<?= $menu['titre']; ?></h2>

        <p>
          "<?= $menu['description']; ?>
        </p>

        <div class="details">

          <div>"<?= $menu['nbPersonnesMin']; ?></div>

          <div>💰 Prix : "<?= $menu['prixBase']; ?></div>

          <div>🥗 Régime : "<?= $menu['regime']; ?></div>

          <div>📦 Stock disponible : "<?= $menu['stock']; ?></div>

        </div>

        <!-- CONDITIONS -->

        <div class="conditions">

          <h3>Conditions importantes</h3>

          <p>
            "<?= $menu['conditions']; ?>
          </p>

        </div>

        <!-- ALLERGENES -->

        <div class="allergenes">

          <span>Gluten</span>
          <span>Fruits à coque</span>
          <span>Lactose</span>

        </div>

        <a href="#" class="btn">
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

        <div class="dish">

          <h4>Foie gras maison</h4>

          <p>Accompagné de chutney de figues.</p>

        </div>

        <div class="dish">

          <h4>Velouté de saison</h4>

          <p>Préparé avec légumes frais locaux.</p>

        </div>

      </div>

      <!-- PLATS -->

      <div class="category">

        <h3>Plats</h3>

        <div class="dish">

          <h4>Magret de canard</h4>

          <p>Sauce miel et pommes grenailles.</p>

        </div>

        <div class="dish">

          <h4>Saumon rôti</h4>

          <p>Légumes fondants et sauce citronnée.</p>

        </div>

      </div>

      <!-- DESSERTS -->

      <div class="category">

        <h3>Desserts</h3>

        <div class="dish">

          <h4>Fondant chocolat</h4>

          <p>Servi avec crème anglaise.</p>

        </div>

        <div class="dish">

          <h4>Tarte aux fruits</h4>

          <p>Fruits frais de saison.</p>

        </div>

      </div>

    </section>

  </main>

 

 

</body>
</html>