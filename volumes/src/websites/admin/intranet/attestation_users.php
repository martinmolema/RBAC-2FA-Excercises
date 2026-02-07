<?php

include_once '../../shared/lib/RBACSupport.php';
include_once '../../shared/partials/header.php';
include_once 'lib/attestation-functions.inc.php';
include_once '../../shared/lib/login-session.inc.php';

$rbac = checkLoginOrFail([Permission_AdminPanel_Attestation_Users]);

function createAttestationTable()
{
    [$header, $report] = collectAllUsersAndGroupMemberships();

    usort($report, function ($a, $b) {
        return strcmp($a["CN"], $b["CN"]);
    });

    $headerHTML = implode('', array_map(function ($x) {
        return "<th><p class='caption'>$x</P</th>";
    }, $header));

// Output the report as a table
    echo "<table><thead><tr>$headerHTML</tr></thead>";

    foreach ($report as $user_info) {
        echo "<tr>";
        echo implode("", array_map(function ($x) {
            return "<td>$x</td>";
        }, $user_info));
        echo "</tr>";
    }
    echo "</table>";

}

?>
<html lang="NL">
<head>
    <title>Admin Panel | Attestation - Gebruikers</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="css/globals.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
    <link href="css/header.css" rel="stylesheet">
    <link href="css/attestation.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../favicon.png">
    <script src="js/attestation.js" type="module"></script>
</head>
<body>
<main class="container-fluid">

    <article>
        <?= showheader(Websites::WEBSITE_ADMIN, basename(__FILE__), $rbac) ?>
        <section class="report users header">
            <a class="button" href="download_attestation_user.php">Download</a>
        </section>
        <section class="report users results">
            <?php createAttestationTable(); ?>
        </section>
    </article>

</main>
</body>
</html>
