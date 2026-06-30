<?php

require '../../config/database.php';
require '../../config/mail.php';

if($_SERVER["REQUEST_METHOD"] === "POST"){

    $firstname = $_POST['firstname'];

    $lastname = $_POST['lastname'];

    $email = $_POST['email'];

    $password = $_POST['password'];

    $confirmPassword = $_POST['confirm_password'];

    $adresse = $_POST['adresse'];

    $codePostal = $_POST['code_postale'];

    $telephone = $_POST['phone'];

    $ville = $_POST['ville'];

    $pays = $_POST['pays'];



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

        $idRole = 2;

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
            (
                prenom,
                nom,
                email,
                motDePasse,
                telephone,
                adresse,
                codePostal,
                ville,
                pays,
                idRole
            )
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $query = $pdo->prepare($sql);

            $query->execute([
                $firstname,
                $lastname,
                $email,
                $hashedPassword,
                $telephone,
                $adresse,
                $codePostal,
                $ville,
                $pays,
                $idRole
            ]);

            $sujet = "Bienvenue chez Vite & Gourmand";

            $message = "
            <h2>Bienvenue {$firstname} !</h2>

            <p>
            Nous sommes ravis de vous accueillir sur <strong>Vite & Gourmand</strong>.
            </p>

            <p>
            Votre compte a été créé avec succès. Vous pouvez dès maintenant :
            </p>

            <ul>
                <li>Découvrir nos menus.</li>
                <li>Commander en ligne.</li>
                <li>Suivre vos commandes.</li>
                <li>Laisser un avis après vos prestations.</li>
            </ul>

            <p>
            Nous espérons avoir le plaisir de préparer vos prochains événements.
            </p>

            <p>
            À bientôt,<br>
            L'équipe <strong>Vite & Gourmand</strong>
            </p>
            ";

            envoyerMail(
                $email,
                $firstname,
                $sujet,
                $message
            );
            

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
    <link rel="stylesheet" href="../../css/inscription.css">

    <!-- GOOGLE FONT -->

    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600&family=Roboto:wght@400;500&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="../../css/vite&gourmand.css">
    <script src="/vite_gourmand/js/navbar.js" defer></script>


</head>

<body>

    <?php require '../../includes/navbar.php';?>
    

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

                        <input type="text" name="firstname" required placeholder="Prenom">

                        <input type="text"  name="lastname" required placeholder="Nom">

                        <input type="email"  name="email" required placeholder="Email" class="full-width">

                        <input type="text"  name="adresse" required placeholder="Adresse" class="full-width">

                        <input type="text"  name="code_postale" required placeholder="Code postale" class="full-width">

                        <input type="tel"  name="phone" required placeholder="Téléphone" class="full-width">

                        <input type="text"  name="ville" required placeholder="Ville" class="full-width">

                        <input type="text"  name="pays" required placeholder="Pays" class="full-width">

                        

                    </div>

                </div>

                <!-- SECURITY -->

                <div>

                    <h2 class="section-title">
                        Sécurité
                    </h2>

                    <div class="security-box">

                        <input type="password"  name="password" required placeholder="Mot de passe">

                        <input type="password"  name="confirm_password" required placeholder="Confirmer mot de passe">

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

    <?php require '../../includes/footer.php'; ?>

</body>

</html>