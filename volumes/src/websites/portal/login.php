<?php
include_once '../shared/lib/ldap_support.inc.php';
include_once '../shared/lib/login-session.inc.php';

try {
    $lnk = ConnectAndCheckLDAP();
} catch (Exception $e) {
    die('Cannot connect to identity server');
}
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $dn = GetUserDNFromUID($lnk, $username);
    } catch (Exception $e) {
        die("Cannot find user $username in LDAP");
    }

    try {
        if (@ldap_bind($lnk, $dn, $password)) {
            $userInfo = GetUserDataFromDN($lnk, $dn);
            session_start();
            $_SESSION['username'] = $username;
            $_SESSION['dn']       = $dn;
            $_SESSION['valid']    = true;
            $_SESSION['fullname'] = $userInfo['givenname'] . ' ' . $userInfo['sn'];

            if (isset($_POST['redirect'])) {
                $url = $_POST['redirect'];
                header('Location: ' . $url);
            } else {
                header('Location: http://sharepoint.rbac.docker/intranet');
            }


        } else {
            die('Onjuiste gebruikersnaam');
            error_log("Could not login user $username : wrong password");
            header(403);
        }
    }catch (Exception $e) {
        die('Onjuiste gebruikersnaam');
    }
}
