<?php

require 'config/database.php';

$sql = "
SELECT
    a.*,
    u.prenom,
    u.nom
FROM avis a
INNER JOIN utilisateur u
ON a.idUtilisateur = u.idUtilisateur
WHERE a.valide = 1
ORDER BY a.dateAvis DESC
LIMIT 6
";

$query = $pdo->prepare($sql);
$query->execute();

$avis = $query->fetchAll();

?>



<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Vite & Gourmand</title>

  <link rel="stylesheet" href="css/accueil.css">
  <link rel="stylesheet" href="css/vite&gourmand.css">
  <script src="/vite_gourmand/js/navbar.js" defer></script>
  <script src="/vite_gourmand/js/avis-slider.js" defer></script>
</head>
<body>

  <?php require 'includes/navbar.php'; ?>

  <!-- HERO -->
  <section class="hero">
    <div class="hero-content">
      

      <h1>Vite & Gourmand</h1>

      <div class="subtitle">
        traiteur • bordeaux depuis 25 ans
      </div>

      <p>
        Des menus <strong>faits maison</strong> avec des produits frais
        pour tous vos événements
      </p>

      <a href="pages/menu/Menus.php" class="btn">
        Découvrir nos menus →
      </a>

      
    </div>
  </section>

  <!-- FEATURES -->
  <section class="features">

    <div class="feature">
      <div class="icon-circle">🏅</div>
      <strong>25 ans</strong>
      <p>d’expérience</p>
    </div>

    <div class="feature">
      <div class="icon-circle">🍃</div>
      <strong>Produits</strong>
      <p>frais</p>
    </div>

    <div class="feature">
      <div class="icon-circle">👨‍🍳</div>
      <strong>Équipe</strong>
      <p>passionnée</p>
    </div>

    <div class="feature">
      <div class="icon-circle">🛡️</div>
      <strong>Engagement</strong>
      <p>qualité</p>
    </div>

  </section>

  <!-- TESTIMONIALS -->
  <section class="testimonials">

    <h2>Avis de nos clients</h2>

    <div class="avis-slider">

        <?php foreach($avis as $index => $unAvis): ?>

            <div class="card avis-card <?= $index === 0 ? 'active' : ''; ?>">

                <div class="stars">
                    <?php for($i = 0; $i < $unAvis['note']; $i++): ?>
                        ⭐
                    <?php endfor; ?>
                </div>

                <p><?= htmlspecialchars($unAvis['commentaire']); ?></p>

                <strong>
                    <?= htmlspecialchars($unAvis['prenom']); ?>
                    <?= strtoupper(substr($unAvis['nom'], 0, 1)); ?>.
                </strong>

            </div>

        <?php endforeach; ?>

        <div class="avis-buttons">
            <button type="button" id="prevAvis" class="btn">←</button>
            <button type="button" id="nextAvis" class="btn">→</button>
        </div>

    </div>

</section>

  <?php require 'includes/footer.php'; ?>

</body>
</html>