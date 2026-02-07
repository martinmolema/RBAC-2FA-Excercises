<?php
include_once '../../shared/lib/RBACSupport.php';
include_once '../../shared/partials/header.php';
include_once '../../shared/lib/ldap_support.inc.php';
include_once '../../shared/lib/db.php';
include_once '../../shared/lib/login-session.inc.php';

$rbac = checkLoginOrFail([Permission_HRM_Manage_Employees]);

function formatDate($date)
{
    // Zet de datum om naar een DateTime-object
    $dateTime = new DateTime($date);
    $now = new DateTime();

    // Vandaag
    if ($dateTime->format('Y-m-d') === $now->format('Y-m-d')) {
        return $dateTime->format('H:i');  // Alleen tijd
    }

    // Maximaal een week oud
    $interval = $now->diff($dateTime);
    if ($interval->days <= 7) {
        return $dateTime->format('l H:i');  // Naam van de dag en tijd
    }

    // Zelfde jaar
    if ($dateTime->format('Y') === $now->format('Y')) {
        return $dateTime->format('m-d H:i');  // Maand, dag en tijd
    }

    // Anders volledige datum in Nederlands formaat
    return $dateTime->format('l j F Y H:i');  // Volledige datum met dagnaam volledig uitgeschreven
}

// index.php - Medewerkers lijst

$pdo = ConnectDatabaseHRM();
$medewerkers = $pdo->query("SELECT * FROM medewerkers ORDER BY achternaam, voornaam")->fetchAll(PDO::FETCH_ASSOC);
$lnk = ConnectAndCheckLDAP();

foreach ($medewerkers as $key => $medewerker) {
    try {
        $ldapInfo = SearchStaffByStaffNumber($lnk, $medewerker['personeelsnummer']);
        if ($ldapInfo) {
            $medewerkers[$key]['dn'] = preg_replace('/^cn=([^,]+),(.*?),dc=.*$/i', '$2', $ldapInfo['dn']);
        }
        else {
            $medewerkers[$key]['dn'] = "[To be determined]";
        }
    }
    catch (\Exception $e) {
        echo $e->getMessage();
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>HRM | Medewerkers Beheer</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="css/globals.css" rel="stylesheet">
    <link href="css/header.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../favicon.png">
</head>
<body>
<main class="container-fluid">
    <article>
        <?= showheader(Websites::WEBSITE_HRM, basename(__FILE__), $rbac) ?>
    </article>
    <section>
        <h2>Medewerkers</h2>
        <p>
            <a class="button" href="form.php">Nieuwe medewerker Toevoegen</a>
        </p>
        <table class="list">
            <thead>
            <tr>
                <th>ID</th>
                <th>Naam</th>
                <th>Voornaam</th>
                <th>Organisatie</th>
                <th>Telefoon</th>
                <th>Kamernummer</th>
                <th>Postcode</th>
                <th>Functie</th>
                <th>DN</th>
                <th>Update</th>
                <th>Acties</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($medewerkers as $medewerker): ?>
                <tr>
                    <td><?= $medewerker['personeelsnummer'] ?></td>
                    <td><?= htmlspecialchars($medewerker['achternaam']) ?></td>
                    <td><?= htmlspecialchars($medewerker['voornaam']) ?></td>
                    <td><?= htmlspecialchars($medewerker['team']) ?></td>
                    <td><?= htmlspecialchars($medewerker['telefoonnummer']) ?></td>
                    <td><?= htmlspecialchars($medewerker['kamernummer']) ?></td>
                    <td><?= htmlspecialchars($medewerker['postcode']) ?></td>
                    <td><?= htmlspecialchars($medewerker['functie']) ?></td>
                    <td class="align-right"><?= htmlspecialchars($medewerker['dn']) ?></td>
                    <td><?= htmlspecialchars(formatDate($medewerker['last_sync'])) ?></td>
                    <td>
                        <a class="button" href="form.php?id=<?= $medewerker['idMedewerker'] ?>">Bewerken</a>
                        <a class="button" href="delete.php?id=<?= $medewerker['idMedewerker'] ?>"
                           onclick="return confirm('Weet je het zeker?');">Verwijderen</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</main>
</body>
</html>
