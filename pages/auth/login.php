<?php

session_start();

require '../../config/database.php';
$erreur = "";

if($_SERVER["REQUEST_METHOD"] === "POST"){

    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];

    if($email !== false){

        $sql = "SELECT * FROM utilisateur WHERE email = ? AND actif = 1";

        $query = $pdo->prepare($sql);
        $query->execute([$email]);

        $user = $query->fetch();

        if($user && password_verify($password, $user['motDePasse'])){

            session_regenerate_id(true);

            $_SESSION['user'] = $user;

            header("Location: ../../index.php");
            exit;
        }
    }

    $erreur = "Email ou mot de passe incorrect.";
}

?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Connexion - Vite & Gourmand</title>
    <link rel="stylesheet" href="../../css/login.css">
    <link rel="stylesheet" href="../../css/vite&gourmand.css">
    <script src="/js/navbar.js" defer></script>

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

                <?php if(!empty($erreur)): ?>
                    <p class="error">
                        <?= htmlspecialchars($erreur); ?>
                    </p>
                <?php endif; ?>

                <!-- EMAIL -->

                <div class="input-group">

                    <label for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Entrez votre email"
                        autocomplete="email"
                        required>
                </div>

                <!-- PASSWORD -->

                <div class="input-group">

                    <label for="password">Mot de passe</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Entrez votre mot de passe"
                        autocomplete="current-password"
                        required>

                </div>

                <a href="forgot-password.php" class="forgot-password">
                        Mot de passe oublié ?
                </a>

                

                <!-- BUTTON -->

                <button type="submit" class="btn">
                    Se connecter
                </button>

                <!-- REGISTER -->

                <div class="register-link">

                    Pas encore de compte ?
                    <a href="inscription.php">
                        Créer un compte
                    </a>

                </div>

            </form>

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