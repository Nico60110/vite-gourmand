<?php

require '../../config/database.php';
require '../../config/auth-admin.php';

$idAvis = $_GET['id'];

$sql = "
UPDATE avis
SET valide = 1
WHERE idAvis = ?
";

$query = $pdo->prepare($sql);
$query->execute([$idAvis]);

header('Location: admin-avis.php');
exit;