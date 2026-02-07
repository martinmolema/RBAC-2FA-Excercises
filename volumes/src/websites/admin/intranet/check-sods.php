<?php

include_once '../../shared/lib/RBACSupport.php';
include_once '../../shared/partials/header.php';
include_once '../../shared/lib/db.php';
include_once '../../shared/lib/login-session.inc.php';

$rbac = checkLoginOrFail([Permission_AdminPanel_Manage_RolePermissions]);
check2faOrValidate();

// Get all SODs
$pdo     = ConnectDatabaseIAM();
$sodsSQL = "SELECT * FROM vw_SOD";
$stmt    = $pdo->prepare($sodsSQL);
$stmt->execute();
$sods = $stmt->fetchAll();
$pdo  = null;

// connect to LDAP to find all users.
$lnk = ConnectAndCheckLDAP();

$ldapRes = ldap_search($lnk, 'dc=NHLStenden,dc=com', "(objectClass=INetOrgPerson)", ['*'], 0, -1, -1, 0);
if ($ldapRes === false) {
  throw new Exception("Check SODs::Cannot execute LDAP query");
}

$usersWithSODViolations = [];

$results = ldap_get_entries($lnk, $ldapRes);

if ($results !== false && $results['count'] > 0) {

  $count = $results['count'];
  for ($i = 0; $i < $count; $i++) {
    // get one record from the result
    $record = $results[$i];
    if (isset($record['uid'])) {
      $uid = $record['uid'][0];

      // make an LDAP-support object for this user
      $rbac = new RBACSupport($uid);
      $rbac->process();

      foreach ($sods as $sod) {
        $id1 = $sod['permission1_code'];
        $id2 = $sod['permission2_code'];
        if ($rbac->has($id1) && $rbac->has($id2)) {
          $usersWithSODViolations[$uid]['user']         = $record;
          $usersWithSODViolations[$uid]['violations'][] = $sod;
        }
      }
    }
  }
}
?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Controleren Functiescheiding schendingen</title>
    <title>Admin Panel | Rollen</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="css/globals.css" rel="stylesheet">
    <link href="css/check-sod.css" rel="stylesheet">
    <link href="css/header.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../favicon.png">
</head>
<body>
<main class="container-fluid">

    <article>
      <?= showheader(Websites::WEBSITE_ADMIN, basename(__FILE__), $rbac) ?>
        <section>
            <header>
                <h2>Overzicht gebruikers met een schending van functiescheidingen.</h2>
            </header>
            <table>
                <thead>
                <tr>
                  <th>Gebruiker</th>
                  <th>Gebruiker DN</th>
                  <th>Functiescheiding</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($usersWithSODViolations as $uid => $user):
                  ?>
                    <tr>
                        <td><?= $uid ?></td>
                        <td><?= $user['user'][ 'dn'] ?></td>
                        <td>
                            <ul><?php foreach ($user['violations'] as $violation) {
                                echo "<li>" . $violation['permission1_title'] . "-" . $violation['permission2_title'] . "</li>";
                              } ?></ul>
                        </td>
                    </tr>
                <?php
                endforeach;
                ?>
                </tbody>
            </table>
        </section>
    </article>
</body>
</html>
