<?php

include_once '../../shared/lib/RBACSupport.php';
include_once '../../shared/partials/header.php';
include_once '../../shared/lib/ldap_support.inc.php';
include_once '../../shared/lib/db.php';
include_once '../../shared/lib/login-session.inc.php';

$rbac = checkLoginOrFail([Permission_AdminPanel_AddUserToRole]);;
$lnk = ConnectAndCheckLDAP();

if (isset($_POST['user']) && isset($_POST['role'])) {
  $user_dn = $_POST['user'];
  $role_dn = $_POST['role'];
} else {
  die('Incorrect parameters.');
}

$user = GetUserDataFromDN($lnk, $user_dn);
if ($user == null) {
  die('Incorrect parameters.');
}
$uid                 = $user['uid'][0];
$existingRBACForUser = new RBACSupport($uid);
$existingRBACForUser->process();

// now pretend the user has already gotten the role assigned so finding conflicting permissions is easier
if (!$existingRBACForUser->addPermissionsForRole($role_dn) ){
  die("Could not find role : $role_dn\n");
}

$pdo     = ConnectDatabaseIAM();
$sodsSQL = "SELECT * FROM vw_SOD";
$stmt    = $pdo->prepare($sodsSQL);
$stmt->execute();
$sods = $stmt->fetchAll();

foreach ($sods as $sod) {
    if (
            $existingRBACForUser->has($sod['permission1_code']) &&
            $existingRBACForUser->has($sod['permission2_code'])
    ) {
        die("Deze gebruiker kan deze rol niet krijgen vanwege conflicterende autorisaties: " .
          $sod['description']  . " => " .
          $sod['permission1_title']  .
          " - versus - "
          . $sod['permission2_title']);
    }
}

$result = AssignUserToRole($lnk, $role_dn, $user_dn);

if ($result) {

  $id_role_dn = urldecode($role_dn);

  header("location: AssignUserToRoleForm.php?idRole={$id_role_dn}", 301);
  die();
}

$error = ldap_error($lnk);
?>

<!doctype html>
<html lang="NL">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cannot authorise</title>
</head>
<body>
<p>Kan de autorisatie niet toewijzen!</p>
<p><?= $error ?></p>
</body>
</html>
