<?php

session_start();

require '../../config/database.php';
require '../../config/mongo.php';

if (
    !isset($_SESSION['user']) ||
    $_SESSION['user']['idRole'] != 1
) {
    header("Location: ../../index.php");
    exit;
}

/* ===========================
   MENUS MYSQL
=========================== */

$sql = "SELECT idMenu, titre FROM menu ORDER BY titre";
$query = $pdo->prepare($sql);
$query->execute();

$menus = $query->fetchAll();

/* ===========================
   FILTRES
=========================== */

$idMenu = isset($_GET['menu']) ? (int) $_GET['menu'] : 0;
$dateDebut = isset($_GET['dateDebut']) ? trim($_GET['dateDebut']) : "";
$dateFin = isset($_GET['dateFin']) ? trim($_GET['dateFin']) : "";

$filtre = [];

if ($idMenu > 0) {
    $filtre["idMenu"] = $idMenu;
}

if (!empty($idMenu)) {

    $filtre["idMenu"] = (int)$idMenu;
}

if (!empty($dateDebut) && !empty($dateFin)) {

    $debut = new MongoDB\BSON\UTCDateTime(
        strtotime($dateDebut . " 00:00:00") * 1000
    );

    $fin = new MongoDB\BSON\UTCDateTime(
        strtotime($dateFin . " 23:59:59") * 1000
    );

    $filtre["dateCommande"] = [
        '$gte' => $debut,
        '$lte' => $fin
    ];
}

/* ===========================
   AGREGATION MONGODB
=========================== */

$pipeline = [];

if(!empty($filtre)){

    $pipeline[] = [
        '$match' => $filtre
    ];
}

$pipeline[] = [
    '$group' => [

        '_id' => '$nomMenu',

        'nbCommandes' => [
            '$sum' => 1
        ],

        'chiffreAffaires' => [
            '$sum' => '$prix'
        ]
    ]
];

$pipeline[] = [
    '$sort' => [
        'nbCommandes' => -1
    ]
];

$resultats = $collectionStatistiques->aggregate($pipeline);

/* ===========================
   TABLEAUX POUR HTML
=========================== */

$statistiques = [];

$labels = [];
$nbCommandes = [];

foreach ($resultats as $ligne) {

    $statistiques[] = $ligne;

    $labels[] = $ligne->_id;
    $nbCommandes[] = $ligne->nbCommandes;
}



?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <title>Statistiques</title>

    <link rel="stylesheet" href="../../css/vite&gourmand.css">
    <link rel="stylesheet" href="../../css/admin_client/admin_client1.css">
    <link rel="stylesheet" href="../../css/filters.css">
    <link rel="stylesheet" href="../../css/table.css">
    <script src="/vite_gourmand/js/navbar.js" defer></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>

<?php require '../../includes/navbar.php'; ?>

<main class="container">

    <h1 class="page-title">
        Statistiques des commandes
    </h1>

    <!-- =====================
         FILTRES
    ====================== -->

    <form method="GET" class="filters">

        <select name="menu">

            <option value="">
                Tous les menus
            </option>

            <?php foreach($menus as $menu): ?>

                <option
                    value="<?= (int) $menu['idMenu']; ?>"
                    <?= ($idMenu == $menu['idMenu']) ? 'selected' : ''; ?>>

                    <?= htmlspecialchars($menu['titre']); ?>

                </option>

            <?php endforeach; ?>

        </select>

        <input
            type="date"
            name="dateDebut"
            value="<?= htmlspecialchars($dateDebut); ?>">

        <input
            type="date"
            name="dateFin"
            value="<?= htmlspecialchars($dateFin); ?>">

        <button type="submit" class="btn">

            Filtrer

        </button>

    </form>

    <br><br>

    <!-- =====================
         TABLEAU
    ====================== -->

    <table border="1" cellpadding="10">

        <thead>

            <tr>

                <th>Menu</th>

                <th>Nombre de commandes</th>

                <th>Chiffre d'affaires (€)</th>

            </tr>

        </thead>

        <tbody>

            <?php foreach($statistiques as $stat): ?>

                <tr>

                    <td>

                        <?= htmlspecialchars($stat->_id); ?>

                    </td>

                    <td>

                        <?= (int) $stat->nbCommandes; ?>

                    </td>

                    <td>

                        <?= number_format((float)$stat->chiffreAffaires,2,","," "); ?>

                    </td>

                </tr>

            <?php endforeach; ?>

        </tbody>

    </table>

    <br><br>

    <!-- =====================
         GRAPHIQUE
    ====================== -->
    <div class="graphique">
        <canvas id="graphique"></canvas>
    </div>
    

</main>

<?php require '../../includes/footer.php'; ?>

<script>

const labels = <?= json_encode($labels); ?>;
const nbCommandes = <?= json_encode($nbCommandes); ?>;

console.log(labels);
console.log(nbCommandes);

</script>

<script src="../../js/statistiques.js"></script>

</body>

</html>