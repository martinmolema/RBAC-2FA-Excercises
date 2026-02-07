<?php

include_once 'lib/attestation-functions.inc.php';
include_once '../../shared/lib/RBACSupport.php';
include_once '../../shared/lib/login-session.inc.php';

$rbac = checkLoginOrFail([Permission_AdminPanel_Attestation_Users]);
check2faOrFail();
if (!$rbac->has(Permission_AdminPanel_Attestation_Users)) {
    echo "Attestation users: Missing permissions\n";
    die();
}

[$header, $report] = collectAllUsersAndGroupMemberships();

$fp = fopen('php://temp', 'r+');
fputcsv($fp, $header);

// Add data rows
foreach ($report as $user_info) {
  fputcsv($fp, $user_info);
}

// Offer file as download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="role_user_report.csv"');

rewind($fp);
fpassthru($fp);
fclose($fp);
