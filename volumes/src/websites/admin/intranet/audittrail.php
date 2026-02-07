<?php
include_once '../../shared/lib/RBACSupport.php';
include_once '../../shared/partials/header.php';
include_once '../../shared/lib/db.php';
include_once '../../shared/lib/login-session.inc.php';

define('MAX_LINES_DISPLAYED_PER_LOGFILE', 20);

$rbac = checkLoginOrFail([Permission_Admin_Panel]);
check2faOrValidate();

$header = showheader(Websites::WEBSITE_ADMIN, basename(__FILE__), $rbac);

$db = ConnectDatabaseIAM();
$SQL = "SELECT * FROM audittrail ORDER BY timestamp DESC LIMIT " . MAX_LINES_DISPLAYED_PER_LOGFILE . ";";
$stmt = $db->prepare($SQL);
$stmt->execute();
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang='nl'>
<head>
    <meta charset='UTF-8'>
    <link rel='icon' type='image/png' href='../favicon.png'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Admin Panel | Audit Trail</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="css/header.css" rel="stylesheet">
    <link href="css/globals.css" rel="stylesheet">
    <link href="css/audittrail.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../favicon.png">
</head>
<body>
<main class="container-fluid">
    <article>
        <?= $header ?>
        <section class='audittrail'>
            <h1>Audit trail</h1>
            <table>
                <thead>
                <tr>
                    <th>category</th>
                    <th>code</th>
                    <th>level</th>
                    <th>username</th>
                    <th>timestamp</th>
                    <th>description</th>
                </tr>
                </thead>
                <?php foreach ($records as $record): ?>
                    <tr>
                        <td><?= $record['category'] ?></td>
                        <td><?= $record['code'] ?></td>
                        <td><?= $record['level'] ?></td>
                        <td><?= $record['username'] ?></td>
                        <td><?= $record['timestamp'] ?></td>
                        <td><?= $record['description'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </section>
    </article>
</main>
</body>
</html>
