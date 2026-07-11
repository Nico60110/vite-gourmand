<?php 
require '../../config/database.php';
require '../../config/auth-admin.php';

if(!isset($_GET['id'])){
    header('location:horaire.php');
    exit;
}

$idHoraire = (int) $_GET['id'];
$erreur = '';

$sql = 'SELECT * FROM horaire WHERE idHoraire = ?';
$query = $pdo->prepare($sql);
$query->execute([$idHoraire]);
$horaire = $query->fetch();

if(!$horaire){
    header('location:horaire.php');
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $jour = trim($_POST['jour']);
    $heureOuverture = trim($_POST['heureOuverture']);
    $heureFermeture = trim($_POST['heureFermeture']);

    if(
        empty($jour) ||
        empty($heureOuverture) ||
        empty($heureFermeture) 
        ){
            $erreur = 'Tous les champs doivent être remplis';
        }else{

            $sql = 'UPDATE horaire 
            SET jour = ?, heureOuverture = ?, heureFermeture = ?
            WHERE idHoraire = ?';
            $query = $pdo->prepare($sql);
            $query->execute([
                $jour,
                $heureOuverture,
                $heureFermeture,
                $idHoraire
            ]);

            header('location:horaire.php');
            exit;

                }
 
}



?>



<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Modifier un horaire</title>

    <link rel="stylesheet" href="../../css/admin_client/admin_client2.css">
    <link rel="stylesheet" href="../../css/vite&gourmand.css">
    <script src="/js/navbar.js" defer></script>

</head>

<body>
    <?php require '../../includes/navbar.php';?>

<main class="container">

    <h1 class="page-title">Modifier un horaire</h1>

     <?php if($erreur): ?>

        <p class="error">
            <?= htmlspecialchars($erreur) ?>
        </p>

    <?php endif; ?>

    <div class="card">

        <form method="POST" class="form-grid">

            <select name="jour" required>

                <option value="Lundi"
                    <?= htmlspecialchars($horaire['jour']) === 'Lundi' ? 'selected' : ''; ?>>
                    Lundi
                </option>

                <option value="Mardi"
                    <?= htmlspecialchars($horaire['jour']) === 'Mardi' ? 'selected' : ''; ?>>
                    Mardi
                </option>

                <option value="Mercredi"
                    <?= htmlspecialchars($horaire['jour']) === 'Mercredi' ? 'selected' : ''; ?>>
                    Mercredi
                </option>

                <option value="Jeudi"
                    <?= htmlspecialchars($horaire['jour']) === 'Jeudi' ? 'selected' : ''; ?>>
                    Jeudi
                </option>

                <option value="Vendredi"
                    <?= htmlspecialchars($horaire['jour']) === 'Vendredi' ? 'selected' : ''; ?>>
                    Vendredi
                </option>

                <option value="Samedi"
                    <?= htmlspecialchars($horaire['jour']) === 'Samedi' ? 'selected' : ''; ?>>
                    Samedi
                </option>

                <option value="Dimanche"
                    <?= htmlspecialchars($horaire['jour']) === 'Dimanche' ? 'selected' : ''; ?>>
                    Dimanche
                </option>

            </select>

            <label>Heure d'ouverture</label>

            <input
                type="time"
                name="heureOuverture"
                value="<?= htmlspecialchars($horaire['heureOuverture']); ?>"
                required
            >

            <label>Heure de fermeture</label>

            <input
                type="time"
                name="heureFermeture"
                value="<?= htmlspecialchars($horaire['heureFermeture']); ?>"
                required
            >

            <button type="submit" class="btn">
                Modifier l'horaire
            </button>

        </form>

    </div>

</main>

<?php require '../../includes/footer.php'; ?>

</body>

</html>