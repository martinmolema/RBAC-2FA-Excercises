<?php
// delete.php - Verwijderen
include_once '../../shared/lib/db.php';
include_once '../../shared/lib/login-session.inc.php';

$rbac = checkLoginOrFail([Permission_HRM_Manage_Employees]);


$id = $_GET['id'] ?? null;

if ($id) {
  $pdo  = ConnectDatabaseHRM();
  $stmt = $pdo->prepare("DELETE FROM medewerkers WHERE idMedewerker = ?");
  $stmt->execute([$id]);
}
header('Location: index.php');
exit;
