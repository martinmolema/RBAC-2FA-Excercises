<?php
include_once '../../../shared/lib/RBACSupport.php';
include_once '../../../shared/lib/ldap_support.inc.php';
include_once '../../../shared/lib/login-session.inc.php';

$rbac = checkLoginOrFail([Permission_AdminPanel_RevokeUserFromRole]);
check2faOrValidate();

$lnk = ConnectAndCheckLDAP();

$userDN = $_POST["user"];
$roleDN = $_POST["role"];
try {
    RevokeUserFromRole($lnk, $roleDN, $userDN);
    $users = GetAllGroupMembersOfRole($lnk, $roleDN);

    usort($users, function ($a, $b) {
        $sn = strcmp(strtolower($a["sn"]), strtolower($b["sn"]));
        if ($sn == 0) {
            return strcmp(strtolower($a["givenName"]), strtolower($b["givenName"]));
        }
        return $sn;
    });

    echo json_encode($users);
} catch (Exception $e) {
    header($e->getMessage(), true, 409);
}