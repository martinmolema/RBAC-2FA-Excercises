<?php

include_once '../../shared/lib/RBACSupport.php';
include_once '../../shared/partials/header.php';
include_once './partials/fake-campaign-list.php';
include_once '../../shared/lib/login-session.inc.php';

$rbac = checkLoginOrFail([Permission_Marketing_Update_Campaign]);

$campaignListButtonCaption = 'Bewerken';
?>
<html lang="NL">
<head>
    <title>Marketing | Bewerk campagne</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="css/globals.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
    <link href="css/header.css" rel="stylesheet">
    <link href="css/partial.fake-campaigns.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../favicon.png">
</head>
<body>
<main class="container-fluid">

    <article>
      <?= showheader(Websites::WEBSITE_MARKETING,basename(__FILE__), $rbac) ?>
        <section class="welcome" aria-label="Welcome section">
            <h1>Campagne Bewerken</h1>
        </section>
      <?php
      displayCampaigns("Details", $rbac, Permission_Marketing_Read_Campaign);
      ?>

    </article>

</main>
</body>
</html>
