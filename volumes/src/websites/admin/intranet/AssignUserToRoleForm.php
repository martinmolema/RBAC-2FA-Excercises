<?php

include_once '../../shared/lib/RBACSupport.php';
include_once '../../shared/partials/header.php';
include_once '../../shared/lib/ldap_support.inc.php';
include_once '../../shared/lib/login-session.inc.php';

$rbac = checkLoginOrFail([Permission_AdminPanel_AddUserToRole]);;
check2faOrValidate();

$lnk = ConnectAndCheckLDAP();
$users_staff['HRM'] = GetAllUsersInDN($lnk, 'ou=HRM,ou=Staff,dc=NHLStenden,dc=com');
$users_staff['Marketing'] = GetAllUsersInDN($lnk, 'ou=Marketing,ou=Staff,dc=NHLStenden,dc=com');
$users_staff['ICT Support'] = GetAllUsersInDN($lnk, 'ou=ICT Support,ou=Staff,dc=NHLStenden,dc=com');
$users_staff['Docenten'] = GetAllUsersInDN($lnk, 'ou=Teachers,ou=Opleidingen,dc=NHLStenden,dc=com');
$roles = GetAllRolesInDN($lnk, "ou=roles,dc=NHLStenden,dc=com");

$idRole = '';
if (isset($_GET['idRole'])) {
    // FIXME: sanitize!!!
    $idRole = $_GET['idRole'];
}

?>
<html lang="NL">
<head>
    <title>Admin Panel | Autorisatie aanvraag verwerken</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="css/globals.css" rel="stylesheet">
    <link href="css/AssignUserToRoleForm.css" rel="stylesheet">
    <link href="css/header.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../favicon.png">
    <script src="js/AssignRoleToUserForm.js" type="module"></script>
</head>
<body>
<main class="container-fluid">

    <article>
        <?= showheader(Websites::WEBSITE_ADMIN, basename(__FILE__), $rbac) ?>
    </article>
    <article class="form">
        <? $idRole ?>
        <fieldset>
            <legend>Nieuwe autorisatie aanvraag verwerken</legend>
            <form method="post" action="AssignRoleToUser.php">
                <div class="form-row">
                    <div class="form-column">
                        <label for="role">Rol:</label>
                        <select name="role" id="role" size="20">
                            <?php foreach ($roles as $role) : ?>
                                <option value="<?= $role['dn'] ?>" <?= ($idRole === $role['dn']) ? 'selected' : '' ?>><?= $role['cn'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-column">
                        <label for="user">Gebruiker:</label>
                        <select name="user" id="user" size="20">
                            <?php foreach ($users_staff as $key => $department): ?>
                                <optgroup label="<?= $key ?>">
                                    <?php foreach ($department as $user) : ?>
                                        <option value="<?= $user['dn'] ?>"><?= $user['sn'] . "," . $user['givenName'] ?></option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit">Autoriseer</button>
                </div>
            </form>
        </fieldset>
        <fieldset id="current-user-list">
            <legend>Huidige gebruikers in deze rol</legend>
            <table>
                <caption>Gebruikers in deze rol</caption>
                <thead>
                <tr>
                    <th>Achternaam</th>
                    <th>Voornaam</th>
                    <th>Revoke</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>

        </fieldset>
    </article>

</main>
</body>
</html>
