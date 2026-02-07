<?php
include_once 'lib/attestation-functions.inc.php';
include_once '../../shared/lib/RBACSupport.php';
include_once '../../shared/lib/db.php';
include_once '../../shared/lib/login-session.inc.php';

$rbac = checkLoginOrFail([Permission_AdminPanel_Attestation_Roles]);
check2faOrFail();

$pdo = ConnectDatabaseIAM();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

list($header, $report) = getRolePermissionCrossTable($pdo);

header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="role_permission_report.csv"');

$fp = fopen('php://temp', 'r+');
fputcsv($fp, $header);
foreach ($report as $row) {
  fputcsv($fp, $row);
}
rewind($fp);
fpassthru($fp);
fclose($fp);
