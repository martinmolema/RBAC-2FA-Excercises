<?php
ini_set('session.name', 'RBACSESSID');
ini_set('session.cookie_domain', '.rbac.docker');
ini_set('session.cookie_path', '/');
ini_set('session.cookie_secure', '0');   // lokaal
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Lax');

session_start();

/**
 * @param string $permission
 * @return RBACSupport
 */
function checkLoginOrFail(array $permissions): RBACSupport {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $url = urlencode( $scheme . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);

    if (!isset($_SESSION['valid'])) {
        header('Location: http://portal.rbac.docker?redirect=' . $url, true, 301);
        die();
    }
    $userDN = $_SESSION['dn'];
    $rbac = new RBACSupport($userDN);
    if (!$rbac->process()) {
        die('Could not connect to RBAC server.');
    }
    if (!$rbac->hasOneOfThesePermissions($permissions)) {
        echo "Not allowed to open this page\n";
        die();
    }
    return $rbac;
}

