<?php 
require '../../config/database.php';
require '../../config/auth-admin.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $theme = $_POST['theme'];
    $regime = $_POST['regime'];
    $nbPersonnesMin = $_POST['nbPersonnesMin'];
    $prixBase = $_POST['prixBase'];
    $stock = $_POST['stock'];
    $conditions =$_POST['conditions'];

    $sql = "INSERT INTO menu
    (
        titre,
        description,
        theme,
        regime,
        nbPersonnesMin,
        prixBase,
        stock,
        conditions
    )
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $query = $pdo->prepare($sql);
    $query->execute([
        $titre,
        $description,
        $theme,
        $regime,
        $nbPersonnesMin,
        $prixBase,
        $stock,
        $conditions
    ]);

    header("Location: admin-menu.php");

    exit;
}


?>


<!DOCTYPE html>
<html lang="en">
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

    <h1>Ajouter un menu</h1>

    <div class="form-card">

        <form method="POST" class="form-grid">

            <input type="text" name="titre" placeholder="Titre" required>

            <textarea name="description" placeholder="Description"></textarea>

            <select name="theme">
                <option value="Classique">Classique</option>
                <option value="Noël">Noël</option>
                <option value="Vegan">Vegan</option>
                
            </select>

            <select name="regime">
                <option value="Classique">Classique</option>
                <option value="Vegan">Vegan</option>
                
            </select>

            <input type="number"
                   name="nbPersonnesMin"
                   placeholder="Nombre minimum de personnes"
                   required>

            <input type="number"
                   step="0.01"
                   name="prixBase"
                   placeholder="Prix de base"
                   required>

            <input type="number"
                   name="stock"
                   placeholder="Stock"
                   value="0">

            <textarea name="conditions"
                      placeholder="Conditions"></textarea>

            <button type="submit" class="btn">
                Ajouter le menu
            </button>

        </form>

    </div>

</main>

<?php require '../../includes/footer.php'; ?>

</body>

</html>