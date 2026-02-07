<?php
include_once '../../shared/lib/RBACSupport.php';
include_once '../../shared/partials/header.php';
include_once '../../shared/partials/my-ldap-info.php';
include_once '../../shared/lib/login-session.inc.php';

$rbac = checkLoginOrFail([Permission_SharePoint_HRM]);

$tiles = [
  ['icon' => '💼', 'title' => 'Declareren'],
  ['icon' => '📄', 'title' => 'Salarisstroken'],
  ['icon' => '💪', 'title' => 'Vitaliteit'],
  ['icon' => '🗓️', 'title' => 'Verlof aanvragen'],
  ['icon' => '📚', 'title' => 'Trainingen'],
  ['icon' => '📝', 'title' => 'Feedback geven'],
  ['icon' => '👤', 'title' => 'Persoonlijke gegevens'],
  ['icon' => '👥', 'title' => 'Teamoverzicht'],
  ['icon' => '📊', 'title' => 'Projecten'],
  ['icon' => '📁', 'title' => 'Documenten'],
  ['icon' => '🚀', 'title' => 'Onboarding'],
  ['icon' => '🏁', 'title' => 'Offboarding'],
  ['icon' => '🏢', 'title' => 'Organigram'],
  ['icon' => '📰', 'title' => 'Nieuws'],
  ['icon' => '🎉', 'title' => 'Evenementen'],
  ['icon' => '📖', 'title' => 'Medewerkers-gids'],
  ['icon' => '📜', 'title' => 'HR Beleid'],
  ['icon' => '🔒', 'title' => 'Veiligheid'],
  ['icon' => '💻', 'title' => 'IT Support'],
  ['icon' => '📞', 'title' => 'Contact HR'],
];

?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Human Resource Management</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="css/globals.css" rel="stylesheet">
    <link href="css/header.css" rel="stylesheet">
    <link href="css/hrm.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../favicon.png">
</head>
<body>
<article>
    <section>
      <?php
      echo showheader(Websites::WEBSITE_SHAREPOINT,basename(__FILE__), $rbac);
      ?>
    </section>
    <section>
        <header>
            <h3>Welkom bij Human Resource Management</h3>
        </header>
    </section>
    <section class="tiles">
        <div class="container">
          <?php foreach ($tiles as $tile): ?>
              <div class="tile" role="gridcell" aria-label="<?= $tile['title'] ?>">
                  <div class="icon"><?= htmlspecialchars($tile['icon']) ?></div>
                  <div class="tile-title"><?= htmlspecialchars($tile['title']) ?></div>
              </div>
          <?php endforeach; ?>
        </div>
    </section>

</article>
</body>
</html>
