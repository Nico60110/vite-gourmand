<?php 
require '../../config/database.php';
require '../../config/auth-admin.php';

if(!isset($_GET['id'])){

    header("Location: plat.php");

    exit;
}

$idPlat = (int) $_GET['id'];
$erreur = '';


$sql = 'SELECT * FROM plat WHERE idPlat = ?';
$query = $pdo->prepare($sql);
$query->execute([$idPlat]);

$plat = $query->fetch();

if(!$plat){
    header("Location: plat.php");
    exit;
}

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
    $nom = trim($_POST['nom']);
    $type = trim($_POST['type']);
    $photo = trim($_POST['photo']);
    $allergenesSelectionnes = $_POST['allergenes'] ?? [];

     if(
        empty($nom) ||
        empty($type) ||
        empty($photo)
    ){
        $erreur = 'Tout les champs doivent être remplis';
    }else{
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
   
}


?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../css/admin_client/admin_client2.css">
    <link rel="stylesheet" href="../../css/vite&gourmand.css">
    <script src="/js/navbar.js" defer></script>
</head>
<body>

    <?php require '../../includes/navbar.php';?>

    <main class="container">

        <h1 class="page-title">Modifier un plat</h1>
        
         <?php if($erreur): ?>

            <p class="error">
                <?= htmlspecialchars($erreur) ?>
            </p>

        <?php endif; ?>

        <div class="card">

    <form method="POST" class="form-grid">

                <input
                    type="text"
                    name="nom"
                    value="<?= htmlspecialchars($plat['nom']); ?>"
                    required
                >

                <select name="type">

                    <option value="entree"
                        <?= htmlspecialchars($plat['type']) === 'entree' ? 'selected' : ''; ?>>
                        Entrée
                    </option>

                    <option value="plat"
                        <?= htmlspecialchars($plat['type']) === 'plat' ? 'selected' : ''; ?>>
                        Plat principal
                    </option>

                    <option value="dessert"
                        <?= htmlspecialchars($plat['type']) === 'dessert' ? 'selected' : ''; ?>>
                        Dessert
                    </option>

                    <option value="boisson"
                        <?= htmlspecialchars($plat['type']) === 'boisson' ? 'selected' : ''; ?>>
                        Boisson
                    </option>

                </select>

                <input
                    type="text"
                    name="photo"
                    value="<?= htmlspecialchars($plat['photo']); ?>"
                    placeholder="nom et extension de l'image"
                >

                <?php if(!empty($plat['photo'])): ?>

                    <img
                    src="../../images/plats/<?= htmlspecialchars($plat['photo']); ?>"
                    alt="<?= htmlspecialchars($plat['nom']); ?>"
                    class="preview-image"
                >

                <?php endif; ?>

                <br>

                <h3>Allergènes</h3>

                <?php foreach($allergenes as $allergene): ?>

                    <label>

                        <input
                            type="checkbox"
                            name="allergenes[]"
                            value="<?= (int) $allergene['idAllergene']; ?>"

                            <?= in_array(
                                $allergene['idAllergene'],
                                $allergenesPlat
                            ) ? 'checked' : ''; ?>
                        >

                        <?= htmlspecialchars($allergene['nom']); ?>

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