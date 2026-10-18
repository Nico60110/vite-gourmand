<?php

session_start();

require '../../config/database.php';
require '../../classes/Utilisateur.php';

if (!isset($_SESSION['user'])) {

    header('Location: ../auth/login.php');
    exit;
}

$idUtilisateur =
    (int) $_SESSION['user']['idUtilisateur'];

$utilisateurClass =
    new Utilisateur($pdo);

$utilisateur =
    $utilisateurClass->getById(
        $idUtilisateur
    );

if (!$utilisateur) {

    session_destroy();

    header('Location: ../auth/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $telephone = trim($_POST['telephone']);
    $adresse = trim($_POST['adresse']);
    $codePostal = trim($_POST['codePostal']);
    $ville = trim($_POST['ville']);
    $pays = trim($_POST['pays']);

    if (
        !filter_var(
            $email,
            FILTER_VALIDATE_EMAIL
        )
    ) {

        $erreur =
            "Adresse email invalide.";

    } elseif (
        $utilisateurClass
            ->emailUtiliseParAutre(
                $email,
                $idUtilisateur
            )
    ) {

        $erreur =
            "Cet email est déjà utilisé.";

    } else {

        $modification =
            $utilisateurClass->modifierProfil(
                $idUtilisateur,
                $nom,
                $prenom,
                $email,
                $telephone,
                $adresse,
                $codePostal,
                $ville,
                $pays
            );

        if ($modification) {

            $utilisateur =
                $utilisateurClass->getById(
                    $idUtilisateur
                );

            // Met à jour également la session
            $_SESSION['user'] =
                $utilisateur;

            $success =
                "Informations mises à jour.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Mon profil</title>

    <link rel="stylesheet" href="../../css/vite&gourmand.css">
    <link rel="stylesheet" href="../../css/admin_client/admin_client2.css">
    <script src="/js/navbar.js" defer></script>
    <script src="/js/profile.js" defer></script>

</head>

<body>

<?php require '../../includes/navbar.php'; ?>

<main class="container">

    <h1 class="page-title">Mon profil</h1>

    <?php if(isset($erreur)): ?>

        <p class="error">
            <?= htmlspecialchars($erreur); ?>
        </p>

    <?php endif; ?>


    <?php if(isset($success)): ?>

        <p class="success">
            <?= htmlspecialchars($success); ?>
        </p>

    <?php endif; ?>

    <form method="POST" class="card">

        <div>

            <label>Nom</label>

            <input
                type="text"
                name="nom"
                value="<?= htmlspecialchars($utilisateur['nom']); ?>"
                readonly
            >

        </div>

        <br>

        <div>
            <label>Prénom</label>

            <input
            type="text"
            name="prenom"
            value="<?= htmlspecialchars($utilisateur['prenom']); ?>"
            readonly
            >
        </div>

        <br>

        <div>

            <label>Email</label>

            <input
                type="email"
                name="email"
                value="<?= htmlspecialchars($utilisateur['email']); ?>"
                readonly
            >

        </div>

        <br>

        <div>
            <label>Téléphone</label>

            <input
                type="text"
                name="telephone"
                value="<?= htmlspecialchars($utilisateur['telephone']); ?>"
                readonly
            >
        </div>

        <br>

        <div>
            <label>Adresse</label>

            <input
                type="text"
                name="adresse"
                value="<?= htmlspecialchars($utilisateur['adresse']); ?>"
                readonly
            >
        </div>

        <br>

        <div>
            <label>Code postal</label>

            <input
                type="text"
                name="codePostal"
                value="<?= htmlspecialchars($utilisateur['codePostal']); ?>"
                readonly
            >
        </div>

        <br>

        <div>
            <label>Pays</label>

            <input
                type="text"
                name="pays"
                value="<?= htmlspecialchars($utilisateur['pays']); ?>"
                readonly
            >
        </div>

        <br>

        <div>
            <label>Ville</label>

            <input
                type="text"
                name="ville"
                value="<?= htmlspecialchars($utilisateur['ville']); ?>"
                readonly
            >
        </div>

        <br>

        <button type="button" id="btnModifier" class="btn">
            Modifier mes informations
        </button>

        <button
            type="submit"
            id="btnEnregistrer"
            class="btn"
            style="display:none;">
            Enregistrer
        </button>

    </form>

</main>

<?php require '../../includes/footer.php'; ?>

</body>
</html>