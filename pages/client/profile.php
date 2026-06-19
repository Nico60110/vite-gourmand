<?php

session_start();

require '../../config/database.php';

if(!isset($_SESSION['user'])){

    header('Location: ../auth/login.php');
    exit;
}

$idUtilisateur = $_SESSION['user']['idUtilisateur'];

$sql = "
SELECT *
FROM utilisateur
WHERE idUtilisateur = ?
";

$query = $pdo->prepare($sql);
$query->execute([$idUtilisateur]);

$utilisateur = $query->fetch();

if(!$utilisateur){

    session_destroy();

    header('Location: connexion.php');
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $telephone = trim($_POST['telephone']);
    $adresse = trim($_POST['adresse']);
    $codePostal = trim($_POST['codePostal']);
    $ville = trim($_POST['ville']);
    $pays = trim($_POST['pays']);

    $sql = "
        UPDATE utilisateur
        SET
            nom = ?,
            prenom = ?,
            email = ?,
            telephone = ?,
            adresse = ?,
            codePostal = ?,
            ville = ?,
            pays = ?
        WHERE idUtilisateur = ?
    ";

    $query = $pdo->prepare($sql);

    $query->execute([
        $nom,
        $prenom,
        $email,
        $telephone,
        $adresse,
        $codePostal,
        $ville,
        $pays,
        $idUtilisateur
    ]);

    $utilisateur['nom'] = $nom;
    $utilisateur['prenom'] = $prenom;
    $utilisateur['email'] = $email;
    $utilisateur['telephone'] = $telephone;
    $utilisateur['adresse'] = $adresse;
    $utilisateur['codePostal'] = $codePostal;
    $utilisateur['ville'] = $ville;
    $utilisateur['pays'] = $pays;

    $success = "Informations mises à jour.";
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Mon profil</title>

    <link rel="stylesheet" href="../../css/vite&gourmand.css">
    <link rel="stylesheet" href="../../css/admin_plat-menu.css">
     <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600&family=Roboto:wght@400;500&display=swap"
        rel="stylesheet">

</head>

<body>

<?php require '../../includes/navbar.php'; ?>

<main class="container">

    <h1 class="page-title">Mon profil</h1>

    <?php if(isset($success)): ?>

        <p>
            <?= $success; ?>
        </p>

    <?php endif; ?>

    <form method="POST">

        <div>

            <label>Nom</label>

            <input
                type="text"
                name="nom"
                value="<?= htmlspecialchars($utilisateur['nom']); ?>"
                required
            >

        </div>

        <br>

        <div>
            <label>Prénom</label>

            <input
            type="text"
            name="prenom"
            value="<?= htmlspecialchars($utilisateur['prenom']); ?>"
            required
            >
        </div>

        <br>

        <div>

            <label>Email</label>

            <input
                type="email"
                name="email"
                value="<?= htmlspecialchars($utilisateur['email']); ?>"
                required
            >

        </div>

        <br>

        <div>
            <label>Téléphone</label>

            <input
                type="text"
                name="telephone"
                value="<?= htmlspecialchars($utilisateur['telephone']); ?>"
            >
        </div>

        <br>

        <div>
            <label>Adresse</label>

            <input
                type="text"
                name="adresse"
                value="<?= htmlspecialchars($utilisateur['adresse']); ?>"
            >
        </div>

        <br>

        <div>
            <label>Code postal</label>

            <input
                type="text"
                name="codePostal"
                value="<?= htmlspecialchars($utilisateur['codePostal']); ?>"
            >
        </div>

        <br>

        <div>
            <label>Pays</label>

            <input
                type="text"
                name="pays"
                value="<?= htmlspecialchars($utilisateur['pays']); ?>"
            >
        </div>

        <br>

        <div>
            <label>Ville</label>

            <input
                type="text"
                name="ville"
                value="<?= htmlspecialchars($utilisateur['ville']); ?>"
            >
        </div>

        <br>

        <button type="submit" class="btn">
            Modifier mes informations
        </button>

    </form>

</main>

<?php require '../../includes/footer.php'; ?>

</body>
</html>