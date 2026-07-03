<?php

require '../../config/database.php';
require '../../config/mail.php';

session_start();

if (
    !isset($_SESSION['user']) ||
    $_SESSION['user']['idRole'] != 1
) {
    header('Location: ../../index.php');
    exit;
}

$erreur = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    if (
        !preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{10,}$/', $password)
    ) {

        $erreur = "Mot de passe non sécurisé.";

    } elseif ($password !== $confirmPassword) {

        $erreur = "Les mots de passe ne correspondent pas.";

    } else {

        $hashedPassword = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        // Employé
        $idRole = 2;

        $sql = "
        SELECT idUtilisateur
        FROM utilisateur
        WHERE email = ?
        ";

        $query = $pdo->prepare($sql);
        $query->execute([$email]);

        if ($query->fetch()) {

            $erreur = "Cet email est déjà utilisé.";

        } else {

            $sql = "
            INSERT INTO utilisateur
            (
                prenom,
                nom,
                email,
                motDePasse,
                idRole
            )
            VALUES (?, ?, ?, ?, ?)
            ";

            $query = $pdo->prepare($sql);

            $query->execute([
                $firstname,
                $lastname,
                $email,
                $hashedPassword,
                $idRole
            ]);

            $sujet = "Création de votre compte";

            $message = "
            <h2>Bonjour {$firstname},</h2>

            <p>
            Un compte employé vient d'être créé pour vous sur
            <strong>Vite & Gourmand</strong>.
            </p>

            <p>
            <strong>Identifiant :</strong> {$email}
            </p>

            <p>
            Pour des raisons de sécurité, votre mot de passe ne vous est pas communiqué par email.
            </p>

            <p>
            Merci de vous rapprocher de votre administrateur afin de le récupérer.
            </p>

            <br>

            <p>
            L'équipe <strong>Vite & Gourmand</strong>
            </p>
            ";

            envoyerMail(
                $email,
                $firstname,
                $sujet,
                $message
            );

            $success = "Le compte employé a été créé avec succès.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Création d'un employé</title>

    <link rel="stylesheet" href="../../css/inscription.css">
    <link rel="stylesheet" href="../../css/vite&gourmand.css">
    <script src="/vite_gourmand/js/navbar.js" defer></script>

</head>

<body>

<?php require '../../includes/navbar.php'; ?>



<main class="register-container">

    <h1 class="page-title">
    Création d'un compte employé
    </h1>

    <?php if($erreur): ?>

        <p style="color:red;">
            <?= $erreur ?>
        </p>

    <?php endif; ?>

    <?php if($success): ?>

        <p style="color:green;">
            <?= $success ?>
        </p>

    <?php endif; ?>

    <form method="POST" class="form-container">

        <div>

            <h2 class="section-title">
                Informations de l'employé
            </h2>

            <div class="form-grid">

                <input
                    type="text"
                    name="firstname"
                    placeholder="Prénom"
                    required>

                <input
                    type="text"
                    name="lastname"
                    placeholder="Nom"
                    required>

                <input
                    type="email"
                    name="email"
                    placeholder="Email"
                    class="full-width"
                    required>

            </div>

        </div>

        <div>

            <h2 class="section-title">
                Sécurité
            </h2>

            <div class="security-box">

                <input
                    type="password"
                    name="password"
                    placeholder="Mot de passe"
                    required>

                <input
                    type="password"
                    name="confirmPassword"
                    placeholder="Confirmer le mot de passe"
                    required>

                <button
                    type="submit"
                    class="btn">

                    Créer le compte

                </button>

            </div>

        </div>

    </form>

</main>

<?php require '../../includes/footer.php'; ?>

</body>
</html>