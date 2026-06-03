<?php 
require '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nom = $_POST['nom'];
    $type = $_POST['type'];
    $photo = $_POST['photo'];

    $sql = 'INSERT INTO plat (nom, type, photo) VALUES (?, ?, ?)';
    $query = $pdo->prepare($sql);
    $query->execute([
        $nom, 
        $type, 
        $photo
    ]);

header("Location: plat.php");
}



?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../css/admin_plat-menu.css">
</head>
<body>
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

            <button type="submit" class="btn">
                Ajouter le plat
            </button>

            </form>
        </div>

    </main>
    
</body>
</html>