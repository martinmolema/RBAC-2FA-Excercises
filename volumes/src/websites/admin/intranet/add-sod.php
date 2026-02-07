<?php
include_once '../../shared/lib/login-session.inc.php';
include_once '../../shared/lib/RBACSupport.php';
include_once '../../shared/partials/header.php';
include_once '../../shared/lib/db.php';

session_start();
check2faOrValidate();

$rbac = new RBACSupport($_SERVER["AUTHENTICATE_UID"]);
if (!$rbac->process()) {
  http_response_code(500);
  die('Could not connect to RBAC server.');
}
if (!$rbac->has(Permission_AdminPanel_Manage_RolePermissions)) {
  http_response_code(406);
  echo "manage-sod: missing correct permission\n";
  die();
}


$pdo  = ConnectDatabaseIAM();
$sql  = "SELECT * FROM application ORDER BY title";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$apps = $stmt->fetchAll();

$sql  = "SELECT * FROM `roles` ORDER BY `title` ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$roles = $stmt->fetchAll();

$hasPermissions = false;
$permissions    = [];

if (isset($_GET['id'])) {
  $idApplication = (int)$_GET['id'];
  $sql           = "SELECT * FROM permissions WHERE fk_idapplication = :idapplication";
  $stmt          = $pdo->prepare($sql);
  $stmt->bindValue(':idapplication', $idApplication, PDO::PARAM_INT);

  $stmt->execute();
  $permissions = $stmt->fetchAll();

  $hasPermissions = true;

}

?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="css/globals.css" rel="stylesheet">
    <link href="css/add-sod.css" rel="stylesheet">
    <link href="css/header.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../favicon.png">
    <title>Beheer Functiescheiding</title>
    <script src="js/manage-sod.js" type="module"></script>
</head>
<body>
<main class="container-fluid">

    <article>
      <?= showheader(Websites::WEBSITE_ADMIN, basename(__FILE__), $rbac) ?>
    </article>
    <article class="tables">
        <section>
            <fieldset>
                <legend>Applicaties</legend>
                <table>
                  <?php
                  foreach ($apps as $app):
                    ?>
                      <tr>
                          <td class="application" data-id="<?= $app['idApplication'] ?>"><a
                                      href="./add-sod.php?id=<?= $app['idApplication'] ?>"><?= $app['title'] ?></a>
                          </td>
                      </tr>
                  <?php endforeach; ?>

                </table>
            </fieldset>
        </section>
        <?php if(isset($idApplication)): ?>
        <form action="create-new-sod.php" method="post">
            <section>
                <fieldset>
                    <legend>Permissie 1</legend>
                    <select class="permissions" size="15" name="permission1">
                      <?php
                      foreach ($permissions as $permission):
                        ?>
                          <option class="permission"
                                  value="<?= $permission['idPermission'] ?>"><?= $permission['title'] ?></option>
                      <?php endforeach; ?>
                    </select>
                </fieldset>
            </section>
            <section>
                <fieldset>
                    <legend>Permissie 2</legend>
                    <select class="permissions" size="15" name="permission2">
                      <?php
                      foreach ($permissions as $permission):
                        ?>
                          <option class="permission"
                                  value="<?= $permission['idPermission'] ?>"><?= $permission['title'] ?></option>
                      <?php endforeach; ?>
                    </select>
                </fieldset>
            </section>
            <section>
                <label for="description">Beschrijving:</label>
                <input id="description" name="description" maxlength="200" minlength="10" type="text">

                <button type="submit">Opslaan</button>
            </section>
        </form>
        <?php endif ?>

    </article>
</main>
</body>
</html>
