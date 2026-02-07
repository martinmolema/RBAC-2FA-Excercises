<?php
include_once '../../shared/lib/RBACSupport.php';
include_once '../../shared/partials/header.php';
include_once '../../shared/lib/login-session.inc.php';

$rbac = checkLoginOrFail([Permission_AdminPanel_Manage_RolePermissions]);
check2faOrFail();

$pdo = ConnectDatabaseIAM();

$sql  = "CALL ResetAllRolesAndPermissions();";
$stmt = $pdo->prepare($sql);
$stmt->execute();


// set expires header
header('Expires: Thu, 1 Jan 1970 00:00:00 GMT');

// set cache-control header
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);

// set pragma header
header('Pragma: no-cache');

http_response_code(301);
header('Location: manage_roles.php');