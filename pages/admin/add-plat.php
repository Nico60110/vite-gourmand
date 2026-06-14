<?php 
require '../../config/database.php';
require '../../config/auth-admin.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST'){

    $nom = $_POST['nom'];
    $type = $_POST['type'];
    $photo = $_POST['photo'];

    $sql = 'INSERT INTO plat (nom, type, photo)
            VALUES (?, ?, ?)';

    $query = $pdo->prepare($sql);

    $query->execute([
        $nom,
        $type,
        $photo
    ]);

    $idPlat = $pdo->lastInsertId();

    $allergenesSelectionnes = $_POST['allergenes'] ?? [];

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

$sql = "SELECT * FROM allergene";
$query = $pdo->prepare($sql);
$query->execute();

$allergenes = $query->fetchAll();



?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../css/admin_plat-menu.css">
    <link rel="stylesheet" href="../../css/vite&gourmand.css">
</head>
<body>
    <?php require '../../includes/navbar.php';?>
    <main class="container">
        <h1>Ajouter un plat</h1>

        <div class="form-card">
            <form method="POST" class="form-grid">

            <input type="text"
                name="nom"
                placeholder="Nom du plat"
                required>

            <select name="type" required>
                <option value="entree">Entrée</option>
                <option value="plat">Plat</option>
                <option value="dessert">Dessert</option>
            </select>

            <input type="text"
                name="photo"
                placeholder="URL de la photo">

            <h3>Allergènes</h3>

                <div class="allergenes-list">

                    <?php foreach($allergenes as $allergene): ?>

                        <label>
                            <input
                                type="checkbox"
                                name="allergenes[]"
                                value="<?= $allergene['idAllergene']; ?>"
                            >

                            <?= $allergene['nom']; ?>
                        </label>

                    <?php endforeach; ?>

                </div>

                <button type="submit" class="btn">
                    Ajouter le plat
                </button>

                </form>

                </div>

    </main>

    <?php require '../../includes/footer.php'; ?>
    
</body>
</html>