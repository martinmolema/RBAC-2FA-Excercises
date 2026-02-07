<?php

use Couchbase\Role;

include_once '../../shared/lib/RBACSupport.php';
include_once '../../shared/partials/header.php';
include_once '../../shared/lib/login-session.inc.php';

$rbac = checkLoginOrFail([Permission_Grades_Create_Gradelists]);

include "./lib/subjects.php";

$selectOptionsHTML = implode('', array_map(function ($x) {
  $vakcode = $x['code'];
  $name    = $x['name'];

  return "<option>$vakcode - $name</option> ";
}, $vakkenMetCodes));

$opleidingen = array_filter($rbac->groups, function($group){
    return str_contains($group, 'ou=opleidingen,ou=roles,dc=NHLStenden,dc=com');
});

$opleidingenHTML = implode('', array_map(function ($opleiding) {
  $opleidingParts = explode(',', $opleiding);
  $name =explode('=', $opleidingParts[0])[1];
  return "<option>$name</option> ";
}, $opleidingen));

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cijferadministratie | Nieuwe cijferlijst</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="css/globals.css" rel="stylesheet">
    <link href="css/header.css" rel="stylesheet">
    <link href="css/new-list.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../favicon.png">
</head>
<body>
<article>
  <?php echo showheader(Websites::WEBSITE_GRADES, basename(__FILE__), $rbac) ?>

    <section class="new-list">
        <form action="new-list.php" method="post">
            <label for="name">Naam van de lijst</label>
            <input type="text" id="name" name="name" required>

            <label for="date">Datum van de toets</label>
            <input type="date" id="date" name="date" required>

            <label for="description">Beschrijving</label>
            <textarea id="description" name="description" rows="4" required></textarea>

            <label for="opleiding">Opleiding</label>
            <select id="opleiding" name="opleiding" required>
              <?= $opleidingenHTML ?>
            </select>

            <label for="vakken">Vakcode</label>
            <select id="vakken" name="vakcode" required>
                <?= $selectOptionsHTML ?>
            </select>

            <button type="submit">Verzenden</button>
        </form>

    </section>
</article>
</body>
</html>
