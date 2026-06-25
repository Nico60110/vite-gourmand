<?php

session_start();

require '../../config/database.php';

if($_SERVER["REQUEST_METHOD"] === "POST"){

    // =========================
    // RECUPERATION DONNEES
    // =========================

    $email = $_POST['email'];

    $password = $_POST['password'];

    // =========================
    // RECHERCHE UTILISATEUR
    // =========================

    $sql = "SELECT * FROM utilisateur WHERE email = ?";

    $query = $pdo->prepare($sql);

    $query->execute([$email]);

    $user = $query->fetch();

    // =========================
    // VERIFICATION UTILISATEUR
    // =========================

    if($user){

        // =========================
        // VERIFICATION PASSWORD
        // =========================

        if(password_verify($password, $user['motDePasse'])){

            // =========================
            // CREATION SESSION
            // =========================

            $_SESSION['user'] = $user;

            // =========================
            // REDIRECTION
            // =========================

            header("Location: ../../index.php");

            exit;

        } else {

            echo "Mot de passe incorrect";
        }

    } else {

        echo "Email introuvable";
    }
}

?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Connexion - Vite & Gourmand</title>

    <!-- GOOGLE FONT -->

    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600&family=Roboto:wght@400;500&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="../../css/login.css">
    <link rel="stylesheet" href="../../css/vite&gourmand.css">
    <script src="/vite_gourmand/js/navbar.js" defer></script>

</head>

<body>

    <?php require '../../includes/navbar.php';?>

    <!-- =========================
         CONTAINER
    ========================= -->

    <main class="login-container">

        <!-- LOGIN CARD -->

        <section class="login-card">

            <!-- IMAGE -->

            <div class="login-image">

                <img src="https://images.unsplash.com/photo-1547592180-85f173990554?q=80&w=1400"
                    alt="Plat gastronomique">

                <div class="overlay">

                    <h1>Connexion</h1>

                    <p>
                        Connectez-vous pour suivre vos commandes,
                        accéder à votre historique et commander
                        rapidement vos menus.
                    </p>

                </div>

            </div>

            <!-- FORM -->

            <form method="POST" class="login-form">

                <h2>Bienvenue</h2>

                <p class="subtitle">
                    Accédez à votre espace personnel Vite & Gourmand.
                </p>

                <!-- EMAIL -->

                <div class="input-group">

                    <label>Email</label>

                    <input type="email" name="email" placeholder="Entrez votre email">

                </div>

                <!-- PASSWORD -->

                <div class="input-group">

                    <label>Mot de passe</label>

                    <input type="password" name="password" placeholder="Entrez votre mot de passe">

                </div>

                <!-- OPTIONS -->

                <div class="options">

                    <label>
                        <input type="checkbox">
                        Se souvenir de moi
                    </label>

                    <a href="#" class="forgot-password">
                        Mot de passe oublié ?
                    </a>

                </div>

                <!-- BUTTON -->

                <button type="submit" class="btn">
                    Se connecter
                </button>

                <!-- REGISTER -->

                <div class="register-link">

                    Pas encore de compte ?
                    <a href="#">
                        Créer un compte
                    </a>

                </div>

            

        </section>

        <!-- FEATURES -->

        <section class="features">

            <!-- FEATURE -->

            <div class="feature">

                <div class="feature-icon">📦</div>

                <h3>Suivi rapide</h3>

                <p>
                    Consultez l’état de vos commandes
                    en temps réel.
                </p>

            </div>

            <!-- FEATURE -->

            <div class="feature">

                <div class="feature-icon">⭐</div>

                <h3>Vos avis</h3>

                <p>
                    Donnez votre avis après chaque
                    prestation réalisée.
                </p>

            </div>

            <!-- FEATURE -->

            <div class="feature">

                <div class="feature-icon">🍽</div>

                <h3>Menus premium</h3>

                <p>
                    Retrouvez l’ensemble de nos menus
                    gastronomiques.
                </p>

            </div>

        </section>

    </main>

    <?php require '../../includes/footer.php'; ?>

</body>

</html>