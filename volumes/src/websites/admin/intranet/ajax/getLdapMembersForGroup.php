<?php
include_once '../../../shared/lib/RBACSupport.php';
include_once '../../../shared/lib/ldap_support.inc.php';

$rbac = new RBACSupport($_SERVER["AUTHENTICATE_UID"]);
if (!$rbac->process()) {
    die('Could not connect to RBAC server.');
}
if (!$rbac->has(Permission_Admin_Panel)) {
    http_response_code(406);
    echo "Get LDAP Members: Missing permissions\n";
    die();
}

$lnk = ConnectAndCheckLDAP();

$DN = $_GET['dn'];

$users = GetAllGroupMembersOfRole($lnk, $DN);

usort($users, function ($a, $b) {
    $sn = strcmp(strtolower($a["sn"]), strtolower($b["sn"]));
    if ($sn == 0) {
        return strcmp(strtolower($a["givenName"]), strtolower($b["givenName"]));
    }
    return $sn;
});

echo json_encode($users);