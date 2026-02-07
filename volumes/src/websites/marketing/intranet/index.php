<?php

include_once '../../shared/lib/RBACSupport.php';
include_once '../../shared/partials/header.php';
include_once '../../shared/lib/login-session.inc.php';

$rbac = checkLoginOrFail([Permission_Marketing_Approve_Campaign, Permission_Marketing_Read_Campaign]);

?>
<html lang="NL">
<head>
    <title>Marketing</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="css/globals.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
    <link href="css/header.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../favicon.png">
</head>
<body>
<main class="container-fluid">

    <article>
      <?= showheader(Websites::WEBSITE_MARKETING, basename(__FILE__), $rbac) ?>
        <section class="welcome" aria-label="Welcome section">
            <h1>Welkom bij de Marketing Campagne Manager</h1>
            <p>
                Kijk in de navigatie balk hierboven om naar de verschillende applicaties te gaan.
            </p>

        </section>
    </article>

</main>
</body>
</html>
