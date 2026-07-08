<?php 
require '../../config/database.php';
require '../../config/auth-admin.php';

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $titre = trim($_POST['titre']);
    $description = trim($_POST['description']);
    $theme = trim($_POST['theme']);
    $regime = trim($_POST['regime']);
    $nbPersonnesMin = (int) $_POST['nbPersonnesMin'];
    $prixParPersonne = (float) $_POST['prixParPersonne'];
    $stock = (int) $_POST['stock'];
    $conditions = trim($_POST['conditions']);

    if (
    empty($titre) ||
    empty($description) ||
    empty($theme) ||
    empty($regime) ||
    empty($nbPersonnesMin) ||
    empty($prixParPersonne) ||
    empty($stock) ||
    empty($conditions)
    ) {
    $erreur = 'Tous les champs sont obligatoires';
    }else
        {

            $sql = "INSERT INTO menu
        (
            titre,
            description,
            theme,
            regime,
            nbPersonnesMin,
            prixParPersonne,
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
            $prixParPersonne,
            $stock,
            $conditions
        ]);

        header("Location: admin-menu.php");

        exit;

        }

   
}


?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter menu</title>
    <link rel="stylesheet" href="../../css/admin_client/admin_client2.css">
    <link rel="stylesheet" href="../../css/vite&gourmand.css">
    <script src="/vite_gourmand/js/navbar.js" defer></script>
</head>
<body>

    <?php require '../../includes/navbar.php';?>
    
<main class="container">

    <h1 class="page-title">Ajouter un menu</h1>

     <?php if($erreur): ?>

        <p class="error">
            <?= htmlspecialchars($erreur) ?>
        </p>

    <?php endif; ?>

    <div class="card">

        <form method="POST" class="form-grid">

            <input type="text" name="titre" placeholder="Titre" required>

            <textarea name="description" placeholder="Description" required></textarea>

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
                   name="prixParPersonne"
                   placeholder="Prix par personne"
                   required>

            <input type="number"
                   name="stock"
                   placeholder="Stock"
                   value="0"
                   required>

            <textarea name="conditions"
                      placeholder="Conditions"
                      required>
            </textarea>

            <button type="submit" class="btn">
                Ajouter le menu
            </button>

        </form>

    </div>

</main>

<?php require '../../includes/footer.php'; ?>

</body>

</html>