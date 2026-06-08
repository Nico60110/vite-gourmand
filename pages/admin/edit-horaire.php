<?php 
require '../../config/database.php';
require '../../config/auth-admin.php';

if(!isset($_GET['id'])){
    header('location:horaire.php');
    exit;
}

$idHoraire = $_GET['id'];

$sql = 'SELECT * FROM horaire WHERE idHoraire = ?';
$query = $pdo->prepare($sql);
$query->execute([$idHoraire]);
$horaire = $query->fetch();

if(!$horaire){
    header('location:horaire.php');
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $jour = $_POST['jour'];
    $heureOuverture = $_POST['heureOuverture'];
    $heureFermeture = $_POST['heureFermeture'];

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



?>



<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Modifier un horaire</title>

    <link rel="stylesheet" href="../../css/admin_plat-menu.css">

</head>

<body>

<main class="container">

    <h1>Modifier un horaire</h1>

    <div class="form-card">

        <form method="POST" class="form-grid">

            <select name="jour" required>

                <option value="Lundi"
                    <?= $horaire['jour'] === 'Lundi' ? 'selected' : ''; ?>>
                    Lundi
                </option>

                <option value="Mardi"
                    <?= $horaire['jour'] === 'Mardi' ? 'selected' : ''; ?>>
                    Mardi
                </option>

                <option value="Mercredi"
                    <?= $horaire['jour'] === 'Mercredi' ? 'selected' : ''; ?>>
                    Mercredi
                </option>

                <option value="Jeudi"
                    <?= $horaire['jour'] === 'Jeudi' ? 'selected' : ''; ?>>
                    Jeudi
                </option>

                <option value="Vendredi"
                    <?= $horaire['jour'] === 'Vendredi' ? 'selected' : ''; ?>>
                    Vendredi
                </option>

                <option value="Samedi"
                    <?= $horaire['jour'] === 'Samedi' ? 'selected' : ''; ?>>
                    Samedi
                </option>

                <option value="Dimanche"
                    <?= $horaire['jour'] === 'Dimanche' ? 'selected' : ''; ?>>
                    Dimanche
                </option>

            </select>

            <label>Heure d'ouverture</label>

            <input
                type="time"
                name="heureOuverture"
                value="<?= $horaire['heureOuverture']; ?>"
                required
            >

            <label>Heure de fermeture</label>

            <input
                type="time"
                name="heureFermeture"
                value="<?= $horaire['heureFermeture']; ?>"
                required
            >

            <button type="submit" class="btn">
                Modifier l'horaire
            </button>

        </form>

    </div>

</main>

</body>

</html>