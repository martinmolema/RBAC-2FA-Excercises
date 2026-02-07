<?php
include_once '../../shared/lib/RBACSupport.php';
include_once '../../shared/partials/header.php';
include_once '../../shared/partials/my-ldap-info.php';
include_once '../../shared/lib/login-session.inc.php';

$rbac = checkLoginOrFail([Permission_SharePoint_TeacherTools]);

$teacherActions = [
  ['title' => 'Lesroosters beheren', 'icon' => '📅'],
  ['title' => 'Toetsen maken', 'icon' => '✏️'],
  ['title' => 'Studenten beoordelen', 'icon' => '✅'],
  ['title' => 'Cijferadministratie', 'icon' => '📊'],
  ['title' => 'Communicatie met studenten', 'icon' => '💬'],
  ['title' => 'Beoordelen van opdrachten', 'icon' => '📄'],
  ['title' => 'Studievoortgang volgen', 'icon' => '📝'],
  ['title' => 'Vergaderingen plannen', 'icon' => '🗓️'],
  ['title' => 'Handleidingen uploaden', 'icon' => '📚'],
  ['title' => 'Lessen online geven', 'icon' => '🎥'],

];


?>
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Intranet | Docentenportaal</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="css/globals.css" rel="stylesheet">
  <link href="css/header.css" rel="stylesheet">
  <link href="css/teachers.css" rel="stylesheet">
  <link rel="icon" type="image/png" href="../favicon.png">
  <style>
  </style>
</head>
<body>
<article>
  <section>
    <?php
    echo showheader(Websites::WEBSITE_SHAREPOINT, basename(__FILE__), $rbac);
    ?>
  </section>
  <section class="tiles">
    <div class="container">
      <?php foreach ($teacherActions as $tool): ?>
        <div class="tile" aria-label="<?=$tool['title'] ?>" role="gridcell">
          <div class="icon"><?= htmlspecialchars($tool['icon'], ENT_QUOTES) ?></div>
          <div class="tile-title"><?= htmlspecialchars($tool['title'], ENT_QUOTES) ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
</article>
</body>
</html>