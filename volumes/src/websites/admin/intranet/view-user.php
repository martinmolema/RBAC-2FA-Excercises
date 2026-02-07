<?php
include_once '../../shared/lib/RBACSupport.php';
include_once '../../shared/partials/header.php';
include_once '../../shared/partials/my-ldap-info.php';
include_once '../../shared/lib/login-session.inc.php';

$rbac = checkLoginOrFail([Permission_Admin_Panel]);
check2faOrValidate();

$hasOtherUserData = isset($_GET['userid']);
$hasSearchString  = isset($_POST['search']);
$found            = false;
$lnk              = ConnectAndCheckLDAP();
$users            = [];

if ($hasOtherUserData) {
// FIXME: prevent hacking; do some sanitation!
    $user            = $_GET['userid'];
    $userDN          = GetUserDNFromUID($lnk, $user);
    $rbac_other_user = new RBACSupport($userDN);
    $found           = $rbac_other_user->process();

} else if ($hasSearchString) {
    $users = searchLDAP($lnk, $_POST['search']);
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Panel | Zoek gebruiker</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="css/view-user.css" rel="stylesheet">
    <link href="css/globals.css" rel="stylesheet">
    <link href="css/header.css" rel="stylesheet">
    <link href="css/other-user-data.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../favicon.png">
</head>
<body>
<article>

    <?php
    echo showheader(Websites::WEBSITE_ADMIN, basename(__FILE__), $rbac);
    ?>
    <fieldset>
        <legend>Zoeken</legend>
        <form action="view-user.php" method="POST">
            <label for="search">Username:</label>
            <input type="text" maxlength="24" name="search" id="search" required>
            <button type="submit">Zoek!</button>
        </form>
    </fieldset>
</article>
<article>
    <?php
    if ($hasOtherUserData && $found) {
        echo GenerateSectionForMyLdapInfoFromRBAC($rbac_other_user);
        echo GenerateSectionForMyLdapRoles($rbac_other_user);
        echo GenerateSectionForMyLdapPermissions($rbac_other_user);
    } elseif ($hasOtherUserData && !$found) {
        echo "<p class='error'>Niet gevonden</p>";
    }
    ?>
</article>
<article class="list">
    <table>
        <?php
        if (count($users) > 0) {
            echo "<thead><th>Username</th><th>CN</th><th>DN</th><th>Voornaam</th><th>Achternaam</th></thead>";
            foreach ($users as $user) {
                if (isset($user['givenname'])) {
                    echo "<tr>";
                    $username  = $user['uid'][0];
                    $cn        = $user['cn'][0];
                    $givenname = $user['givenname'][0];
                    $lastname  = $user['sn'][0];

                    $dn = substr($user['dn'], 0, strlen($user['dn']) - strlen(BASE_DN) - 1);

                    echo "<td class='username'><a href='?userid=$username'>$username</a></td>";
                    echo "<td class='cn'>$cn</td>";
                    echo "<td class='dn'>$dn</td>";
                    echo "<td class='givenname'>$givenname</td>";
                    echo "<td class='lastname'>$lastname</td>";
                    echo "</tr>";
                }
            }
        }

        ?>
    </table>
</article>
</body>
</html>
