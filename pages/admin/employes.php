<?php

session_start();

require '../../config/database.php';

if (
    !isset($_SESSION['user']) ||
    $_SESSION['user']['idRole'] != 1
) {
    header("Location: ../../index.php");
    exit;
}

// ------------------------------
// Désactivation / Réactivation
// ------------------------------

if (isset($_GET['action']) && isset($_GET['id'])) {

    $id = (int) $_GET['id'];

    if ($_GET['action'] === "desactiver") {

        $sql = "
        UPDATE utilisateur
        SET actif = 0
        WHERE idUtilisateur = ?
        AND idRole = 2
        ";

        $query = $pdo->prepare($sql);
        $query->execute([$id]);
    }

    if ($_GET['action'] === "reactiver") {

        $sql = "
        UPDATE utilisateur
        SET actif = 1
        WHERE idUtilisateur = ?
        AND idRole = 2
        ";

        $query = $pdo->prepare($sql);
        $query->execute([$id]);
    }

    header("Location: employes.php");
    exit;
}

// ------------------------------
// Liste des employés
// ------------------------------

$sql = "
SELECT *
FROM utilisateur
WHERE idRole = 2
ORDER BY nom
";

$query = $pdo->prepare($sql);
$query->execute();

$employes = $query->fetchAll();

?>

<!DOCTYPE html>
<html lang="fr">

<head>

<meta charset="UTF-8">

<title>Gestion des employés</title>

<link rel="stylesheet" href="../../css/vite&gourmand.css">
<link rel="stylesheet" href="../../css/admin_plat-menu.css">
<link rel="stylesheet" href="../../css/table.css">


</head>

<body>

<?php require '../../includes/navbar.php'; ?>

<div class="container">

<h1 class="page-title">Gestion des employés</h1>

<table>

<tr>

    <th>Prénom</th>

    <th>Nom</th>

    <th>Email</th>

    <th>Statut</th>

    <th>Action</th>

</tr>

<?php foreach($employes as $employe): ?>

<tr>

    <td><?= htmlspecialchars($employe['prenom']); ?></td>

    <td><?= htmlspecialchars($employe['nom']); ?></td>

    <td><?= htmlspecialchars($employe['email']); ?></td>

    <td>

        <?php if($employe['actif']): ?>

            🟢 Actif

        <?php else: ?>

            🔴 Désactivé

        <?php endif; ?>

    </td>

    <td>

        <?php if($employe['actif']): ?>

            <a
                class="btn desactiver"
                href="?action=desactiver&id=<?= $employe['idUtilisateur']; ?>"
                onclick="return confirm('Désactiver cet employé ?')">

                Désactiver

            </a>

        <?php else: ?>

            <a
                class="btn reactiver"
                href="?action=reactiver&id=<?= $employe['idUtilisateur']; ?>">

                Réactiver

            </a>

        <?php endif; ?>

    </td>

</tr>

<?php endforeach; ?>

</table>

</div>

<?php require '../../includes/footer.php'; ?>

</body>
</html>