<?php

require '../../config/database.php';
require '../../config/auth-admin.php';

$idAvis = (int) $_GET['id'];

$sql = "
DELETE FROM avis
WHERE idAvis = ?
";

$query = $pdo->prepare($sql);
$query->execute([$idAvis]);

header('Location: admin-avis.php');
exit;