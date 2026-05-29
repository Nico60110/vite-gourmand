<?php

require '../config/database.php';

if($_SERVER["REQUEST_METHOD"] === "POST"){

    $firstname = $_POST['firstname'];

    $lastname = $_POST['lastname'];

    $email = $_POST['email'];

    $password = $_POST['password'];

    $confirmPassword = $_POST['confirm_password'];



    if(
    !preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{10,}$/',$password))
    {
        echo "Mot de passe non sécurisé";
    }
    elseif($password !== $confirmPassword){

    echo "Les mots de passe ne correspondent pas";

    }
    else {

        // HASH PASSWORD

        $hashedPassword = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        // ROLE PAR DEFAUT

        $idRole = 3;

        // VERIFICATION EMAIL

        $checkEmail = $pdo->prepare(
            "SELECT * FROM utilisateur WHERE email = ?"
        );

        $checkEmail->execute([$email]);

        $user = $checkEmail->fetch();
        

        // SI EMAIL EXISTE

        if($user){

            echo "Email déjà utilisé";

        } else {

            // INSERT SQL

            $sql = "INSERT INTO utilisateur
            (prenom, nom, email, motDePasse, idRole)
            VALUES (?, ?, ?, ?, ?)";

            $query = $pdo->prepare($sql);

            $query->execute([
                $firstname,
                $lastname,
                $email,
                $hashedPassword,
                $idRole
            ]);
            

            header("Location: login.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Inscription - Vite & Gourmand</title>
    <link rel="stylesheet" href="../css/inscription.css">

    <!-- GOOGLE FONT -->

    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600&family=Roboto:wght@400;500&display=swap"
        rel="stylesheet">


</head>

<body>
    

    <!-- =========================
         CONTAINER
    ========================= -->

    <main class="register-container">

        <!-- TOP -->

        <section class="register-top">

            <!-- IMAGE -->

            <div class="register-image">

                <img src="https://images.unsplash.com/photo-1547592180-85f173990554?q=80&w=1400"
                    alt="Plat gastronomique">

                <div class="overlay">

                    <h1>Créer mon compte</h1>

                    <p>
                        Créer votre compte pour commander vos
                        menus traiteur en quelques clics et suivre
                        vos commandes.
                    </p>

                </div>

            </div>

            <!-- FORM -->

            <form method="POST" class="form-container">

                <!-- INFOS -->

                <div>

                    <h2 class="section-title">
                        Informations personnelles
                    </h2>

                    <div class="form-grid">

                        <input type="text" name="firstname" placeholder="Prenom">

                        <input type="text"  name="lastname" placeholder="Nom">

                        <input type="email"  name="email" placeholder="Email" class="full-width">

                        <input type="tel"  name="phone" placeholder="Téléphone" class="full-width">

                        <input type="date" class="full-width">

                    </div>

                </div>

                <!-- SECURITY -->

                <div>

                    <h2 class="section-title">
                        Sécurité
                    </h2>

                    <div class="security-box">

                        <input type="password"  name="password" placeholder="Mot de passe">

                        <input type="password"  name="confirm_password" placeholder="Confirmer mot de passe">

                       <button type="submit" class="btn">
                            Créer mon compte →
                       </button>

                    </div>

                </div>

            </form>

        </section>

        <!-- FEATURES -->

        <section class="features">

            <!-- FEATURE -->

            <div class="feature">

                <div class="icon">👜</div>

                <div>

                    <h3>Commande rapide</h3>

                    <p>
                        Gagnez du temps avec vos informations
                        enregistrées
                    </p>

                </div>

            </div>

            <!-- FEATURE -->

            <div class="feature">

                <div class="icon">☑</div>

                <div>

                    <h3>Suivie de commande</h3>

                    <p>
                        Suivez l’état de vos commandes en temps réel
                        jusqu’à la livraison ou au retrait
                    </p>

                </div>

            </div>

            <!-- FEATURE -->

            <div class="feature">

                <div class="icon">♡</div>

                <div>

                    <h3>Historique</h3>

                    <p>
                        Retrouvez toutes vos commandes et
                        factures facilement
                    </p>

                </div>

            </div>

        </section>

    </main>

</body>

</html>