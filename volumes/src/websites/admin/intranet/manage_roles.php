<?php

include_once '../../shared/lib/RBACSupport.php';
include_once '../../shared/partials/header.php';
include_once '../../shared/lib/db.php';
include_once '../../shared/lib/login-session.inc.php';

$rbac = checkLoginOrFail([Permission_AdminPanel_Manage_RolePermissions]);;
check2faOrValidate();

$pdo  = ConnectDatabaseIAM();
$sql  = "SELECT * FROM `roles` ORDER BY `title` ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$roles = $stmt->fetchAll();

?>
<html lang="NL">
<head>
    <title>Admin Panel | Rollen</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="css/globals.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
    <link href="css/manage_roles.css" rel="stylesheet">
    <link href="css/header.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../favicon.png">
</head>
<body>
<main class="container-fluid">

    <article>
      <?= showheader(Websites::WEBSITE_ADMIN, basename(__FILE__), $rbac) ?>
        <section class="roles">
            <p>
                <a class="button" href="restore-all-permissions.php"> Restore all permissions</a>
                <a class="button" href="sync-ldap-db.php"> Synchroniseer Rollen</a>
            </p>
            <table>
              <?php
              foreach ($roles as $role):
                ?>
                  <tr>
                      <td><?= $role['title'] ?></td>
                      <td><?= $role['description'] ?></td>
                      <td>
                          <a class="button" href="edit-role.php?id=<?= $role['idRole'] ?>">Edit</a>
                      </td>
                  </tr>

              <?php endforeach; ?>
            </table>
        </section>
    </article>
</main>
</body>
</html>