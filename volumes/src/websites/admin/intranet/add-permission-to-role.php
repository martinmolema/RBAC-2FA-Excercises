<?php

include_once '../../shared/lib/RBACSupport.php';
include_once '../../shared/partials/header.php';
include_once '../../shared/lib/db.php';
include_once '../../shared/lib/login-session.inc.php';

$rbac = checkLoginOrFail([Permission_AdminPanel_Manage_RolePermissions]);;
check2faOrValidate();

if (!is_numeric($_POST["idRole"]) || !is_numeric($_POST["idPermission"])) {
  http_response_code(406);
  die('not acceptable');
}

$idRole       = (int)$_POST["idRole"];
$idPermission = (int)$_POST["idPermission"];

$roleName       = getRoleById($idRole);
$permissionName = getPermissionById($idPermission);

$pdo = ConnectDatabaseIAM();

$sql  = "INSERT INTO role_permissions (fk_idPermission, fk_idRole) VALUES(:idPermission, :idRole)";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':idPermission', $idPermission, PDO::PARAM_INT);
$stmt->bindValue(':idRole', $idRole, PDO::PARAM_INT);

try {
  $stmt->execute();
  LogAuditRecord("PERM", "01", "INFO", "Permission [$permissionName] added to role [$roleName]");
} catch (PDOException $e) {
  if ($e->getCode() == 45000) {
    $sql = "SELECT * from vw_SOD WHERE id1 = :id OR id2 = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $idPermission, PDO::PARAM_INT);
    $stmt->execute();

    echo "<html><body>";
    echo 'Deze permissie mag niet samen in met een andere permissie in deze rol volgens functiescheiding!';
    echo "<ul>";

    $permissions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($permissions as $permission) {
      $description = $permission['description'];
      $name1       = $permission['permission1_title'];
      $name2       = $permission['permission2_title'];
      echo "<li>$description => $name1 &#x2194; $name2</li>";
    }
    echo "</ul></body></html>";


    LogAuditRecord("SOD", "01", "WARN", "Trying to add permission [$permissionName] to role [$roleName] but this will lead to conflicting permissions");

    die();

  }
}

// set expires header
header('Expires: Thu, 1 Jan 1970 00:00:00 GMT');

// set cache-control header
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);

// set pragma header
header('Pragma: no-cache');

http_response_code(301);
header('Location: edit-role.php?id=' . $idRole);
