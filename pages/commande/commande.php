<?php
require '../../config/database.php';
/*session_start();

if(!isset($_SESSION['user'])){

    header("Location: ../auth/login.php");

    exit;
}*/

if(!isset($_GET['id'])){
    header('location:../menu/menus.php');
    exit;
}

$idMenu = $_GET['id'];

$sql = 'SELECT * FROM menu WHERE idMenu = ?';
$query = $pdo->prepare($sql);
$query->execute([$idMenu]);
$menu = $query->fetch();

if(!$menu){
    header("Location: ../menu/menus.php");
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $dateLivraison = $_POST['dateLivraison'];
    $heureLivraison = $_POST['heureLivraison'];
    $adresseLivraison = $_POST['adresseLivraison'];
    $nbPersonnes = $_POST['nbPersonnes'];

    $idUtilisateur = 1;

    $prixTotal = $menu['prixBase'];


    $sql = "INSERT INTO commande
    (
        dateLivraison,
        heureLivraison,
        adresseLivraison,
        nbPersonnes,
        prixTotal,
        idUtilisateur
    )
    VALUES (?, ?, ?, ?, ?, ?)";

    $query = $pdo->prepare($sql);
    $query->execute([
        $dateLivraison,
        $heureLivraison,
        $adresseLivraison,
        $nbPersonnes,
        $prixTotal,
        $idUtilisateur
    ]);

    $idCommande = $pdo->lastInsertId();


    $sql = "INSERT INTO commande_menu
    (
        quantite,
        prixUnitaire,
        idCommande,
        idMenu
    )
    VALUES (?, ?, ?, ?)";

    $query = $pdo->prepare($sql);
    $query->execute([
        1,
        $menu['prixBase'],
        $idCommande,
        $idMenu
    ]);

    $sql = "INSERT INTO historique_statut
    (
        statut,
        commentaire,
        idCommande
    )
    VALUES (?, ?, ?)";

    $query = $pdo->prepare($sql);
    $query->execute([
        'EN_ATTENTE',
        'Commande créée',
        $idCommande
    ]);

    header("Location: ../menu/menus.php");
    exit;
}


?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Commande - Vite & Gourmand</title>

    <!-- GOOGLE FONT -->

    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600&family=Roboto:wght@400;500&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="../../css/commande.css">

</head>

<body>

    <!-- HERO -->

    <section class="hero">

        <div>

            <h1>Commander un menu</h1>

            <p>
                Finalisez votre commande traiteur en quelques étapes
            </p>

        </div>

    </section>

    <!-- CONTAINER -->

    <main class="container">

        <form method="POST">

        <div class="order-layout">

            <!-- LEFT -->

            <div>

                <!-- CLIENT INFORMATIONS -->

                <section class="card">

                    <h2>Informations client</h2>

                    <div class="form-grid">

                        <input type="text" placeholder="Prénom">

                        <input type="text" placeholder="Nom">

                        <input type="email" placeholder="Adresse email">

                        <input type="tel" placeholder="Téléphone">

                        <input type="date" name="dateLivraison" required>

                        <input type="time" name="heureLivraison" required>

                        <input
                            type="text"
                            name="adresseLivraison"
                            placeholder="Adresse de livraison"
                            class="full-width"
                            required>

                        <textarea
                            placeholder="Informations complémentaires"
                            class="full-width"></textarea>

                    </div>

                </section>

                <!-- MENU -->

                <section class="card">

                    <h2>Menu sélectionné</h2>

                    <div class="selected-menu">

                        <img
                            src="https://images.unsplash.com/photo-1547592180-85f173990554?q=80&w=1400"
                            alt="Menu gastronomique">

                        <div>

                            <h3><?=$menu['titre'];?></h3>

                            <p>
                                <?=$menu['description'];?>
                            </p>

                            <span class="badge">
                               <?=$menu['nbPersonnesMin'];?> personnes minimum
                            </span>

                        </div>

                    </div>

                </section>

                <!-- ORDER DETAILS -->

                <section class="card">

                    <h2>Détails de la commande</h2>

                    <div class="form-grid">

                       <input
                            type="number"
                            name="nbPersonnes"
                            min="<?= $menu['nbPersonnesMin']; ?>"
                            required>
                        <select>

                            <option>
                                Choisir un régime
                            </option>

                            <option>
                                Classique
                            </option>

                            <option>
                                Végétarien
                            </option>

                            <option>
                                Vegan
                            </option>

                        </select>

                    </div>

                    <!-- CONDITIONS -->

                    <div class="conditions">

                        <h3>Conditions du menu</h3>

                        <p>
                            <?=$menu['conditions'];?>
                        </p>

                    </div>

                </section>

            </div>

            <!-- RIGHT -->

            <aside class="summary card">

                <h2>Résumé</h2>

                <div class="summary-item">

                    <span><?=$menu['titre'];?></span>

                    <span><?= $menu['prixBase']; ?> €</span>

                </div>

                <div class="summary-item">

                    <span>Livraison</span>

                    <span>15 €</span>

                </div>

                <div class="summary-item">

                    <span>Réduction</span>

                    <span>-25 €</span>

                </div>

                <div class="summary-item total">

                    <span>Total</span>

                    <span><?= $menu['prixBase']; ?> €</span>

                </div>

                <button type="submit" class="btn">
                     Valider la commande
                </button>

            </aside>

        </div>

        </form>

    </main>

</body>

</html>