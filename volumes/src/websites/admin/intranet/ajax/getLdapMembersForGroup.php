<?php
include_once '../../../shared/lib/RBACSupport.php';
include_once '../../../shared/lib/ldap_support.inc.php';
include_once '../../../shared/lib/login-session.inc.php';

$rbac = checkLoginOrFail([Permission_Admin_Panel]);
check2faOrValidate();

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