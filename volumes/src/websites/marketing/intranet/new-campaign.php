<?php

include_once '../../shared/lib/RBACSupport.php';
include_once '../../shared/partials/header.php';
include_once '../../shared/lib/login-session.inc.php';

$rbac = checkLoginOrFail([Permission_Marketing_Create_Campaign]);


?>
<html lang="NL">
<head>
    <title>Marketing | Nieuwe campagne</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="css/globals.css" rel="stylesheet">
  <link href="css/index.css" rel="stylesheet">
  <link href="css/header.css" rel="stylesheet">
  <link href="css/new-campaign.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../favicon.png">
</head>
<body>
<main class="container-fluid">

  <article>
    <?= showheader(Websites::WEBSITE_MARKETING,basename(__FILE__), $rbac) ?>
    <section class="welcome" aria-label="Welcome section">
      <h1>Nieuwe Campagne aanmaken</h1>
    </section>

      <section class="new-campaign">
          <form method="post" action="new-campaign.php">
              <label for="name">Naam van de campagne</label>
              <input type="text" id="name" name="name" required>

              <label for="date">Datum van het event</label>
              <input type="date" id="date" name="date" required>

              <label for="description">Beschrijving</label>
              <textarea id="description" name="description" rows="4" required></textarea>

              <label for="participants">Lijst met betrokkenen</label>
              <textarea id="participants" name="participants" rows="4" required></textarea>

              <label for="budget">Budget</label>
              <input type="number" id="budget" name="budget" required>

              <label for="platform">Marketingplatform</label>
              <select id="platform" name="platform" required>
                  <option value="social_media">Social Media</option>
                  <option value="email">E-mail</option>
                  <option value="website">Website</option>
                  <option value="print">Print</option>
              </select>

              <button type="submit">Verzenden</button>
          </form>

      </section>
  </article>

</main>
</body>
</html>
