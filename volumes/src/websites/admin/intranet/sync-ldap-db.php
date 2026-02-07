<?php

include_once '../../shared/lib/RBACSupport.php';
include_once '../../shared/partials/header.php';
include_once '../../shared/lib/db.php';
include_once '../../shared/lib/login-session.inc.php';

$rbac = checkLoginOrFail([Permission_Admin_Panel]);
check2faOrValidate();

function DoSync()
{
// LDAP server configuratie
    $ldap_dn = "ou=roles,dc=NHLStenden,dc=com";

    try {
        // Verbinding maken met LDAP
        $ldap_conn = ConnectAndCheckLDAP();

        // Verbinding maken met MySQL via PDO
        $pdo = ConnectDatabaseIAM();

        // LDAP zoekopdracht uitvoeren
        $search = ldap_search($ldap_conn, $ldap_dn, "(objectClass=groupOfUniqueNames)");
        $entries = ldap_get_entries($ldap_conn, $search);

        // Haal alle rollen op
        $stmt = $pdo->prepare("SELECT distinghuishedName FROM roles ORDER BY distinghuishedName");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $allRoles = $stmt->fetchAll();

        $allDistinguishedNames = array_map(function ($entry) {
            return strtolower($entry['distinghuishedName']);
        }, $allRoles);

        $nrOfNewRoles = 0;
        foreach ($entries as $entry) {
            if (isset($entry['dn']) && isset($entry['cn'][0])) {
                $dn = strtolower($entry['dn']);

                $title = $entry['cn'][0];

                echo "Controleer rol: $title\n";

                if (!in_array($dn, $allDistinguishedNames)) {

                    echo "- Nieuwe rol gedetecteerd : $dn\n";

                    $nrOfNewRoles++;

                    // Voeg de rol toe aan de database
                    $description = "Rol voor $title";
                    $stmt_insert = $pdo->prepare("INSERT INTO roles (title, description, distinghuishedName) VALUES (:title, :description, :dn)");
                    $stmt_insert->execute([
                        ':title' => $title,
                        ':description' => $description,
                        ':dn' => $dn
                    ]);
                }
            }
        }

        // Sluit de verbindingen
        ldap_unbind($ldap_conn);
        $pdo = null;

        echo "\n\nSynchronisatie voltooid. Er zijn $nrOfNewRoles nieuwe rollen aangemaakt in de permissie administratie";
    } catch (Exception $e) {
        echo "Fout: " . $e->getMessage();
    }
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="css/globals.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
    <link href="css/manage_roles.css" rel="stylesheet">
    <link href="css/header.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../favicon.png">

    <title>Admin Panel | Synchroniseer rollen</title>
</head>
<body>

<main class="container-fluid">

    <article>
        <?= showheader(Websites::WEBSITE_ADMIN, basename(__FILE__), $rbac) ?>
        <section class="results">
          <pre><?php DoSync(); ?></pre>
        </section>
    </article>
</main>
</body>
</html>
