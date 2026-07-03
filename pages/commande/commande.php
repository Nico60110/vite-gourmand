<?php
require '../../config/database.php';
require '../../config/mail.php';
require '../../config/mongo.php';

session_start();

if(!isset($_SESSION['user'])){

    header("Location: ../auth/login.php");

    exit;
}

if(!isset($_GET['id'])){
    header('location:../menu/menus.php');
    exit;
}

$idUtilisateur = $_SESSION['user']['idUtilisateur'];



$idMenu = $_GET['id'];

$sql = "SELECT * FROM materiel ORDER BY nom";
$query = $pdo->prepare($sql);
$query->execute();

$materiels = $query->fetchAll();

$sql = "SELECT * FROM utilisateur WHERE idUtilisateur = ? ";
$query = $pdo->prepare($sql);
$query->execute([$idUtilisateur]);
$utilisateur = $query->fetch();

$sql = 'SELECT * FROM menu WHERE idMenu = ?';
$query = $pdo->prepare($sql);
$query->execute([$idMenu]);
$menu = $query->fetch();

if(!$menu){
    header("Location: ../menu/menus.php");
    exit;
}

$prixMenu = $menu['prixParPersonne'] * $menu['nbPersonnesMin'];

$reduction = 0;

if(strtolower($utilisateur['ville']) === 'bordeaux'){
        $prixLivraison = 0;
    }
else{
        $prixLivraison = 5;
    }

$prixTotal = $prixMenu + $prixLivraison;

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $dateLivraison = $_POST['dateLivraison'];
    $heureLivraison = $_POST['heureLivraison'];
    $adresseLivraison = $_POST['adresseLivraison'];
    $nbPersonnes = $_POST['nbPersonnes'];

    $idUtilisateur = $_SESSION['user']['idUtilisateur'];

    $prixMenu = $menu['prixParPersonne'] * $nbPersonnes;

    $reduction = 0;

    if($nbPersonnes >= ($menu['nbPersonnesMin'] + 5)){

        $reduction = $prixMenu * 0.10;
    }

    if(strtolower($utilisateur['ville']) === 'bordeaux'){
        $prixLivraison = 0;
    }
    else{
        $prixLivraison = 5;
    }

    $prixTotal = $prixMenu - $reduction + $prixLivraison;

    $pretMateriel = 0;


    if(isset($_POST['materiel'])){

    foreach($_POST['materiel'] as $quantite){

        if($quantite > 0){

            $pretMateriel = 1;
            break;
        }
    }
}

    $sql = "INSERT INTO commande
    (
        dateLivraison,
        heureLivraison,
        adresseLivraison,
        nbPersonnes,
        prixTotal,
        pretMateriel,
        idUtilisateur
    )
    
    VALUES (?, ?, ?, ?, ?, ?, ?)";

    $query = $pdo->prepare($sql);
    $query->execute([
        $dateLivraison,
        $heureLivraison,
        $adresseLivraison,
        $nbPersonnes,
        $prixTotal,
        $pretMateriel,
        $idUtilisateur
    ]);

    $idCommande = $pdo->lastInsertId();

    if(isset($_POST['materiel'])){

    foreach($_POST['materiel'] as $idMateriel => $quantite){

        if($quantite > 0){

            $sql = "INSERT INTO commande_materiel
            (idCommande, idMateriel, quantite)
            VALUES (?, ?, ?)";

            $query = $pdo->prepare($sql);

            $query->execute([
                $idCommande,
                $idMateriel,
                $quantite
            ]);
            }
        }
    }

    


    $sql = "INSERT INTO commande_menu
    (
        idCommande,
        idMenu
    )
    VALUES (?, ?)";

    $query = $pdo->prepare($sql);
    $query->execute([
        $idCommande,
        $idMenu
    ]);

   
     $sql = "INSERT INTO historique_statut
    (
        statut,
        commentaire,
        idCommande
    )
    VALUES (?, ?, ?)";

    $query = $pdo->prepare($sql);
    $query->execute([
        'EN_ATTENTE',
        'Commande créée',
        $idCommande
    ]);

   $collectionStatistiques->insertOne([

    "idCommande" => (int)$idCommande,

    "idMenu" => (int)$idMenu,

    "nomMenu" => $menu['titre'],

    "prix" => (float)$prixTotal,

    "dateCommande" => new MongoDB\BSON\UTCDateTime(),

    "nbPersonnes" => (int)$nbPersonnes

    ]);

    $sujet = "Confirmation de votre commande";

    $message = "
    <h2>Bonjour {$utilisateur['prenom']},</h2>

    <p>Nous avons bien reçu votre commande.</p>

    <p>
        <strong>Numéro de commande :</strong> {$idCommande}<br>
        <strong>Date de livraison :</strong> {$dateLivraison}<br>
        <strong>Heure de livraison :</strong> {$heureLivraison}<br>
        <strong>Nombre de personnes :</strong> {$nbPersonnes}<br>
        <strong>Montant total :</strong> {$prixTotal} €
    </p>

    <p>
        Votre commande est actuellement <strong>EN ATTENTE</strong>.
    </p>

    <p>
        Merci pour votre confiance.<br>
        L'équipe <strong>Vite & Gourmand</strong>
    </p>
    ";

    envoyerMail(
        $utilisateur['email'],
        $utilisateur['prenom'],
        $sujet,
        $message
    );

    
    header("Location: ../menu/menus.php");
    exit;

    
}




?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Commande - Vite & Gourmand</title>
    
    <link rel="stylesheet" href="../../css/commande.css">
    <link rel="stylesheet" href="../../css/vite&gourmand.css">
    <script src="/vite_gourmand/js/navbar.js" defer></script>
    <script src="/vite_gourmand/js/prix-commande.js" defer></script>
    <script src="/vite_gourmand/js/commande.js" defer></script>

</head>


<script>

const prixParPersonne = <?= $menu['prixParPersonne']; ?>;
const nbMin = <?= $menu['nbPersonnesMin']; ?>;
const prixLivraison = <?= $prixLivraison; ?>;

</script>



<body>
    <?php require '../../includes/navbar.php';?>

    <!-- HERO -->

    <section class="hero">

        <div>

            <h1>Commander un menu</h1>

            <p>
                Finalisez votre commande traiteur en quelques étapes
            </p>

        </div>

    </section>

    <!-- CONTAINER -->

    <main class="container">

        <form method="POST">

        <div class="order-layout">

            <!-- LEFT -->

            <div>

                <!-- CLIENT INFORMATIONS -->

                <section class="card">

                    <h2>Informations client</h2>

                    <div class="form-grid">

                        <input type="text" value="<?= htmlspecialchars($utilisateur['prenom']); ?>" placeholder="Prénom">

                        <input type="text" value="<?= htmlspecialchars($utilisateur['nom']); ?>" placeholder="Nom">

                        <input type="email" value="<?= htmlspecialchars($utilisateur['email']); ?>" placeholder="Adresse email">

                        <input type="tel" value="<?= htmlspecialchars($utilisateur['telephone']); ?>" placeholder="Téléphone">

                        <input type="date" name="dateLivraison" required>

                        <input type="time" name="heureLivraison" required>

                        <input
                            type="text"
                            name="adresseLivraison"
                            placeholder="Adresse de livraison"
                            class="full-width"
                            required>

                        <textarea
                            placeholder="Informations complémentaires"
                            class="full-width"></textarea>

                    </div>

                </section>

                <!-- MENU -->

                <section class="card">

                    <h2>Menu sélectionné</h2>

                    <div class="selected-menu">

                        <img
                            src="https://images.unsplash.com/photo-1547592180-85f173990554?q=80&w=1400"
                            alt="Menu gastronomique">

                        <div>

                            <h3><?=$menu['titre'];?></h3>

                            <p>
                                <?=$menu['description'];?>
                            </p>

                            <span class="badge">
                               <?=$menu['nbPersonnesMin'];?> personnes minimum
                            </span>

                        </div>

                    </div>

                </section>

                <!-- ORDER DETAILS -->

                <section class="card">

                    <h2>Détails de la commande</h2>

                    <div class="form-grid">

                       <input
                            type="number"
                            name="nbPersonnes"
                            id="nbPersonnes"
                            placeholder="nombre personnes"
                            min="<?= $menu['nbPersonnesMin']; ?>"
                            required>

                    </div>

                    <div class="pret-materiel-check">
                        <label>
                            <input type="checkbox" id="pretMateriel">
                            Je souhaite un prêt de matériel
                        </label>
                    </div>

                    <div id="listeMateriel" class="materiel-list cache">

                        <h3>Prêt de matériel</h3>

                        <?php foreach($materiels as $materiel): ?>

                            <div class="materiel-item">

                                <label>
                                    <?= htmlspecialchars($materiel['nom']); ?>
                                </label>

                                <input
                                    type="number"
                                    name="materiel[<?= $materiel['idMateriel']; ?>]"
                                    min="0"
                                    value="0">

                            </div>

                        <?php endforeach; ?>

                    </div>
                    

                    <!-- CONDITIONS -->

                    <div class="conditions">

                        <h3>Conditions du menu</h3>

                        <p>
                            <?=$menu['conditions'];?>
                        </p>

                    </div>

                </section>

            </div>

            <!-- RIGHT -->

            <aside class="summary card">

                <h2>Résumé</h2>

                <div class="summary-item">
                    <span>Menu</span>
                    <span id="prixMenu"><?= number_format($prixMenu, 2); ?> €</span>
                </div>

                <div class="summary-item">
                    <span>Réduction</span>
                    <span id="reduction">-<?= number_format($reduction, 2); ?> €</span>
                </div>

                <div class="summary-item">
                    <span>Livraison</span>
                    <span id="livraison"><?= number_format($prixLivraison, 2); ?> €</span>
                </div>

                <div class="summary-item total">
                    <span>Total</span>
                    <span id="prixTotal"><?= number_format($prixTotal, 2); ?> €</span>
                </div>

                <button type="submit" class="btn">
                     Valider la commande
                </button>

            </aside>

        </div>

        </form>

    </main>

    <?php require '../../includes/footer.php'; ?>

</body>

</html>