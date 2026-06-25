<?php 
require '../../config/database.php';
require '../../config/auth-admin.php';

if(!isset($_GET['id'])){

    header("Location: plat.php");

    exit;
}

$idPlat = $_GET['id'];


$sql = 'SELECT * FROM plat WHERE idPlat = ?';
$query = $pdo->prepare($sql);
$query->execute([$idPlat]);
$plat = $query->fetch();

$sql = "SELECT * FROM allergene";
$query = $pdo->prepare($sql);
$query->execute();

$allergenes = $query->fetchAll();

$sql = "SELECT idAllergene
        FROM plat_allergene
        WHERE idPlat = ?
        ";

$query = $pdo->prepare($sql);
$query->execute([$idPlat]);

$allergenesPlat = $query->fetchAll(PDO::FETCH_COLUMN);

if(!$plat){

    header("Location: plat.php");

    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nom = $_POST['nom'];
    $type = $_POST['type'];
    $photo = $_POST['photo'];
    $allergenesSelectionnes = $_POST['allergenes'] ?? [];


    $sql = 'UPDATE plat 
            SET
                nom = ?,
                type = ?,
                photo = ?
            WHERE
                idPlat = ?    
                ';
    
    $query = $pdo->prepare($sql);
    $query->execute([
        $nom,
        $type,
        $photo,
        $idPlat
    ]);

    $sql = "DELETE FROM plat_allergene
            WHERE idPlat = ?
            ";

$query = $pdo->prepare($sql);
$query->execute([$idPlat]);

foreach($allergenesSelectionnes as $idAllergene){

    $sql = "
    INSERT INTO plat_allergene
    (
        idPlat,
        idAllergene
    )
    VALUES
    (
        ?, ?
    )
    ";

    $query = $pdo->prepare($sql);

    $query->execute([
        $idPlat,
        $idAllergene
    ]);
}

    header("Location: plat.php");

    exit;
}


?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../css/admin_plat-menu.css">
    <link rel="stylesheet" href="../../css/vite&gourmand.css">
    <script src="/vite_gourmand/js/navbar.js" defer></script>
</head>
<body>

    <?php require '../../includes/navbar.php';?>

    <main class="container">
        <h1>Modifier un plat</h1>

        <div class="form-card">
    <form method="POST" class="form-grid">

                <input
                    type="text"
                    name="nom"
                    value="<?= $plat['nom']; ?>"
                    required
                >

                <select name="type">

                    <option value="entree"
                        <?= $plat['type'] === 'entree' ? 'selected' : ''; ?>>
                        Entrée
                    </option>

                    <option value="plat"
                        <?= $plat['type'] === 'plat' ? 'selected' : ''; ?>>
                        Plat principal
                    </option>

                    <option value="dessert"
                        <?= $plat['type'] === 'dessert' ? 'selected' : ''; ?>>
                        Dessert
                    </option>

                </select>

                <input
                    type="text"
                    name="photo"
                    value="<?= $plat['photo']; ?>"
                    placeholder="Nom du fichier image"
                >

                <?php if(!empty($plat['photo'])): ?>

                    <img
                    src="../../images/plats/<?= $plat['photo']; ?>"
                    alt="<?= $plat['nom']; ?>"
                    class="preview-image"
                >

                <?php endif; ?>

                <h3>Allergènes</h3>

                <?php foreach($allergenes as $allergene): ?>

                    <label>

                        <input
                            type="checkbox"
                            name="allergenes[]"
                            value="<?= $allergene['idAllergene']; ?>"

                            <?= in_array(
                                $allergene['idAllergene'],
                                $allergenesPlat
                            ) ? 'checked' : ''; ?>
                        >

                        <?= $allergene['nom']; ?>

                    </label>

                    <br>

                <?php endforeach; ?>

                <button type="submit" class="btn">
                    Modifier le plat
                </button>

            </form>
        </div>

    </main>

    <?php require '../../includes/footer.php'; ?>
    
</body>
</html>