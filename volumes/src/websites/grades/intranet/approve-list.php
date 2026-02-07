<?php
include_once '../../shared/lib/RBACSupport.php';
include_once '../../shared/partials/header.php';
include_once '../../shared/lib/login-session.inc.php';

$rbac = checkLoginOrFail([Permission_Grades_Create_Gradelists]);

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cijferadministratie | Goedkeuren cijferlijst</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="css/globals.css" rel="stylesheet">
    <link href="css/header.css" rel="stylesheet">
    <link href="css/approve-list.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../favicon.png">

</head>
<body>
<article>
  <?php echo showheader(Websites::WEBSITE_GRADES,basename(__FILE__), $rbac) ?>
    <section class="gradelist">
      <?php
      include_once './lib/subjects.php';
      // Functie om een willekeurige datum te genereren binnen de afgelopen maand
      function randomDatePastMonth()
      {
        $end_date   = date("Y-m-d");
        $start_date = date("Y-m-d", strtotime("-1 month"));
        $timestamp  = mt_rand(strtotime($start_date), strtotime($end_date));
        return date("Y-m-d", $timestamp);
      }

      // Genereer de lijst met vakken en examendatums
      $examens       = [];
      $gekozenVakken = [];
      for ($i = 0; $i < 10; $i++) {
        do {
          $vak = $vakken[array_rand($vakken)];
        } while (in_array($vak, $gekozenVakken));
        $gekozenVakken[] = $vak;
        $examendatum     = randomDatePastMonth();
        $examens[]       = [
          "vak" => $vak,
          "examendatum" => $examendatum
        ];
      }

      // Print de lijst met vakken en examendatums
      echo "<table>";
      echo "<caption>Bestaande lijsten</caption>";
      echo "<tr><th>Vak</th><th>Examendatum</th></tr>";
      foreach ($examens as $examen) {
        echo "<tr>";
        echo "<td>{$examen['vak']}</td>";
        echo "<td>{$examen['examendatum']}</td>";
        echo "</tr>";
      }
      echo "</table>";
      ?>
    </section>
</article>
</body>
</html>
