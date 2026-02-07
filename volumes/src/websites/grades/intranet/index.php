<?php

include_once '../../shared/lib/RBACSupport.php';
include_once '../../shared/partials/header.php';
include_once '../../shared/lib/login-session.inc.php';

$rbac = checkLoginOrFail([Permission_Grades_BasicAccess]);

$roles = $rbac->groups;
$isStudent = in_array('cn=Grades Students,ou=roles,dc=NHLStenden,dc=com', $roles);
$isTeacher = in_array('cn=Grades Teachers,ou=roles,dc=NHLStenden,dc=com', $roles);

$suffix = "ou=opleidingen,ou=roles,dc=NHLStenden,dc=com";
$opleidingen = [];
foreach ($roles as $role) {
    if (str_ends_with($role, $suffix)) {
        preg_match('/cn=([^,]+)/', $role, $matches);
        if (!empty($matches[1])) {
            $opleidingen[] = $matches[1]; // Haal de CN-waarde op
        }
    }
}

$role = '';
if ($isStudent) {
  $role = 'Student';
}
if ($isTeacher) {
  $role = 'Teacher';
}

?>
<html lang="NL">
<head>
    <title>Cijferadministratie!</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="css/globals.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
    <link href="css/header.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../favicon.png">
</head>
<body>
<main class="container-fluid">

    <article>
      <?= showheader(Websites::WEBSITE_GRADES,basename(__FILE__), $rbac) ?>
        <section class="welcome" aria-label="Welcome section">
            <h1>Welkom bij de cijferadministratie</h1>
            <p aria-label="Welcome text">Je kunt hier navigeren naar verschillende onderdelen van de cijfer
                administratie, afhankelijk van
                je rol. Jouw rol is <span class="role" aria-label="role"> <?= $role ?></span>.
            </p>
            <p>
                Kijk in de navigatie balk hierboven om naar de verschillende onderdelen van de applicatie te gaan.
            </p>

        </section>
        <section>
            <p>Je bent lid van de volgende opleidingen:</p>
            <ul>
                <?php
                foreach ($opleidingen as $opleiding) {
                    echo '<li>' . $opleiding . '</li>';
                }
                ?>
            </ul>

        </section>
    </article>

</main>
</body>
</html>
