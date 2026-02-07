<?php

include_once '../../shared/lib/RBACSupport.php';
include_once '../../shared/partials/header.php';
include_once '../../shared/lib/db.php';
include_once '../../shared/lib/login-session.inc.php';

$rbac = checkLoginOrFail([Permission_HRM_Manage_Employees]);


$id = $_GET['id'] ?? null;
$medewerker = [
    'personeelsnummer' => '',
    'voornaam' => '',
    'achternaam' => '',
    'team' => '',
    'functie' => '',
    'telefoonnummer' => '',
    'kamernummer' => '',
    'postcode' => '',
];

if ($id) {
    $pdo = ConnectDatabaseHRM();
    $stmt = $pdo->prepare("SELECT * FROM medewerkers WHERE idMedewerker = ?");
    $stmt->execute([$id]);
    $medewerker = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($medewerker === false) {
        die("Onbekende gebruiker $id");
    }
}

$functions = [
    "medewerker marketing",
    "marketing manager",
    "medewerker ICT",
    "medewerker HRM",
    "docent",
];

// FIXME: This is not very secure; SQL-injection possible; clean user input
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    # FIXME: a lot of assumptions are done here. not very robust code....
    $functie = $_POST['functie'];
    $medewerkerType = [
        'docent' => 'Teacher',
        'medewerker HRM' => 'Staff',
        'medewerker marketing' => 'Staff',
        'marketing manager' => 'Staff',
        'medewerker ICT' => 'Staff',
    ][$functie];

    $data = [
        $_POST['voornaam'],
        $_POST['achternaam'],
        $_POST['team'],
        $_POST['functie'],
        $_POST['telefoonnummer'],
        $_POST['kamernummer'],
        $medewerkerType,
        $_POST['postcode'],
    ];
    if ($id) {
        $stmt = $pdo->prepare("UPDATE medewerkers 
                                    SET voornaam=?, 
                                        achternaam=?, 
                                        team=?, 
                                        functie=?,
                                        telefoonnummer=?,
                                        kamernummer=?,
                                        medewerkerType=?,
                                        postcode=?                                        
                                    WHERE idMedewerker=?");
        $stmt->execute([...$data, $id]);
    } else {
        $stmt2 = $pdo->prepare('SELECT max(personeelsnummer) + 1 as maxnr FROM medewerkers');
        $stmt2->execute();
        $record = $stmt2->fetch(PDO::FETCH_ASSOC);
        $newPersoneelsNr = $record['maxnr'];

        $stmt = $pdo->prepare("INSERT INTO medewerkers (personeelsnummer,
                                                voornaam,
                                                achternaam,
                                                team,
                                                functie,
                                                telefoonnummer,
                                                kamernummer,
                                                medewerkerType,
                                                postcode) VALUES (?, ?, ?, ?,?,?,?,?,?)");
        $stmt->execute([$newPersoneelsNr, ...$data]);
    }
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Medewerker <?= $id ? 'Bewerken' : 'Toevoegen' ?></title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
    <link href="css/globals.css" rel="stylesheet">
    <link href="css/header.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../favicon.png">
</head>
<body>
<main class="container-fluid">
    <article>
        <?= showheader(Websites::WEBSITE_HRM, basename(__FILE__), $rbac) ?>
    </article>
    <section>

        <h2>Medewerker <?= $id ? 'Bewerken' : 'Toevoegen' ?></h2>
        <form method="post">
            <label for="voornaam">Voornaam: </label>
            <input type="text" name="voornaam" id="voornaam" value="<?= htmlspecialchars($medewerker['voornaam']) ?>"
                   required><br>
            <label for="achternaam">Achternaam: </label>
            <input type="text" name="achternaam" id="achternaam"
                   value="<?= htmlspecialchars($medewerker['achternaam']) ?>"
                   required><br>
            <label for="telefoonnummer">Telefoon: </label>
            <input type="text" name="telefoonnummer" id="telefoonnummer"
                   value="<?= htmlspecialchars($medewerker['telefoonnummer']) ?>"
                   required><br>
            <label for="kamernummer">kamernummer: </label>
            <input type="text" name="kamernummer" id="kamernummer"
                   value="<?= htmlspecialchars($medewerker['kamernummer']) ?>"
                   required><br>
            <label for="postcode">postcode: </label>
            <input type="text" name="postcode" id="postcode" value="<?= htmlspecialchars($medewerker['postcode']) ?>"
                   required>

            <br>
            <label for="team">Team: </label>
            <select name="team" id="team" value="<?= htmlspecialchars($medewerker['team']) ?>" required>
                <option value="NHL Stenden">NHL Stenden</option>
            </select>
            <br>
            <label for="functie">Functie: </label>
            <select name="functie" id="functie" required>
                <option value="">[Kies een optie]</option>
                <?php foreach ($functions as $functie): ?>

                    <option
                            value="<?= $functie ?>"
                        <?= (htmlspecialchars($medewerker['functie']) === $functie) ? "selected" : "" ?>
                    ><?= $functie ?></option> >
                <?php endforeach; ?>
            </select>
            <br>
            <button type="submit">Opslaan</button>
        </form>
        <a href="index.php">Terug</a>
    </section>
</main>
</body>
</html>