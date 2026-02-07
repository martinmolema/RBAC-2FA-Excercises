<?php

include_once '../../shared/lib/RBACSupport.php';
include_once '../../shared/partials/header.php';
include_once '../../shared/lib/db.php';
include_once '../../shared/lib/login-session.inc.php';

$rbac = checkLoginOrFail([Permission_AdminPanel_Manage_RolePermissions]);
check2faOrValidate();

// set expires header
header('Expires: Thu, 1 Jan 1970 00:00:00 GMT');

// set cache-control header
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);

// set pragma header
header('Pragma: no-cache');

if (!is_numeric($_GET["id"])) {
  http_response_code(406);
  die('not acceptable');
}


$idRolePermission = (int)$_GET['id'];

$pdo = ConnectDatabaseIAM();

$sql  = "SELECT * FROM `vw_Role_Permissions` WHERE idRolePermission = :idPermission";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':idPermission', $idRolePermission, PDO::PARAM_INT);
$stmt->execute();
$records = $stmt->fetchAll();

if ($stmt->rowCount() == 0) {
  http_response_code(406);
  die();
}

$role           = $records[0];
$roleName       = $role['role'];
$permissionName = $role['permission'];
$idRole         = $role['idRole'];

$sql  = "DELETE FROM role_permissions WHERE idRolePermission = :idRole";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':idRole', $idRolePermission, PDO::PARAM_INT);
$stmt->execute();

if ($stmt->rowCount() !== 1) {
  http_response_code(404);
  die('not found');
}

LogAuditRecord("PERM", "02", "INFO", "Deleted permission [$permissionName] from role [$roleName]");


http_response_code(301);
header('Location: edit-role.php?id=' . $idRole);