<?php
include_once '../../shared/lib/RBACSupport.php';
include_once '../../shared/partials/header.php';
include_once '../../shared/lib/login-session.inc.php';

define('MAX_LINES_DISPLAYED_PER_LOGFILE', 20);

$rbac = checkLoginOrFail([Permission_Admin_Panel]);
check2faOrValidate();

// Lijst met paden naar je Apache-errorlogbestanden
$logFiles = [
    ['file' => '/var/log/apache2/error.sharepoint.log', 'title' => 'SharePoint'],
    ['file' => '/var/log/apache2/error.grades.log', 'title' => 'Cijferadministratie'],
    ['file' => '/var/log/apache2/error.marketing.log', 'title' => 'Marketing'],
    ['file' => '/var/log/apache2/error.admin.log', 'title' => 'Admin'],
    ['file' => '/var/log/apache2/error.hrm.log', 'title' => 'HRM'],
];

// Lijst met reguliere expressies en bijbehorende kolomnummers
$regexPatterns = [
    [
        'regex' => '/\[(.*?)\]\s+\[(auth_basic:.*?)\]\s+(\[.*?\])\s+(\[.*?\])\s+([A-Za-z0-9]*):\s(.*)/',
        'columns' => [1, 2, 5, 6, -1] // Specificeer de groepnummers
    ],
    [
        'regex' => '/\[(.*?)\]\s+\[(authnz_ldap:.*?)\]\s+(\[.*?\])\s+(\[.*?\])\s+([A-Za-z0-9]*):\s(.*?):\s(.*?)\s+\[(.*?)\]\[(.*?)\]/m',
        'columns' => [1, 2, 5, 7, 9] // Specificeer de groepnummers
    ],
    [
        'regex' => '/\[(.*?)\]\s+\[(authz_core:.*?)\]\s+\[(.*?)\]\s+\[(.*?)\]\s+([A-Za-z0-9]*):(.*)/m',
        'columns' => [1, 2, 5, 6, -1] // Specificeer de groepnummers
    ]
];

// Vaste kolomnamen
$columnHeaders = ['Tijdstip', 'Module', 'Fout', 'Bericht', 'Extra info'];

function parseApacheTimestamp($date): DateTime|null
{
    $dateTime = DateTime::createFromFormat('D M d H:i:s.u Y', $date);
    return $dateTime ?: null;
}

// Functie om logregels te verwerken en gegevens te extraheren
function extractLogData($filePath, $patterns, $lines = 10)
{
    if (!file_exists($filePath)) {
        return ["Logbestand niet gevonden: $filePath"];
    }

    $file = new SplFileObject($filePath, 'r');

    $results = [];
    while ($file->valid()) {
        $line = $file->current();
        $file->next(); // Lees van voor naar achter

        foreach ($patterns as $pattern) {
            if (preg_match($pattern['regex'], $line, $matches)) {
                $result = [];
                foreach ($pattern['columns'] as $groupIndex) {
                    if ($groupIndex === 1) {
                        $dateTime = parseApacheTimestamp($matches[1]);
                        if ($dateTime !== null) {
                            $result[] = $dateTime->format('Y-m-d H:i:s');
                        } else {
                            $result[] = '? ';
                        }
                    } elseif ($groupIndex !== -1) {
                        $result[] = $matches[$groupIndex] ?? ''; // Waarden ophalen op basis van groepsnummers
                    } else {
                        $result[] = '';
                    }
                }
                $results[] = $result;
                if (count($results) > MAX_LINES_DISPLAYED_PER_LOGFILE) {
                    array_shift($results); // Verwijder het oudste item als er meer dan 10 zijn
                }
                break; // Stop bij de eerste match
            }
        }
    }

    return $results; // Keer om zodat de oudste regels bovenaan staan
}

// HTML-output genereren
echo <<< HTML_HEADER
<!DOCTYPE html>
<html lang='nl'>
<head>
    <meta charset='UTF-8'>
    <link rel='icon' type='image/png' href='../favicon.png'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Admin Panel | Apache Error Logs</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="css/header.css" rel="stylesheet">
    <link href="css/globals.css" rel="stylesheet">
    <link href="css/logging.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../favicon.png">

</head>
<body>
HTML_HEADER
;

$header = showheader(Websites::WEBSITE_ADMIN, basename(__FILE__), $rbac);

echo <<< HTML_BODY1
<main class="container-fluid">

  <article>
    $header
    <section class='logfiles'>
    <h1>Apache Error Logs</h1>
HTML_BODY1
;

# Now generate the list of logfiles and details

foreach ($logFiles as $logFile) {
    echo "<h2>Logbestand: {$logFile['title']}</h2>";
    $entries = extractLogData($logFile['file'], $regexPatterns);

    if (empty($entries)) {
        echo "<p class='error'>Geen data gevonden of bestand niet beschikbaar.</p>";
    } else {
        echo "<table><thead><tr>";
        foreach ($columnHeaders as $header) {
            echo "<th>" . htmlspecialchars($header) . "</th>";
        }
        echo "</tr></thead><tbody>";
        foreach ($entries as $entry) {
            echo "<tr>";
            foreach ($entry as $value) {
                echo "<td>" . htmlspecialchars($value) . "</td>";
            }
            echo "</tr>";
        }
        echo "</tbody></table>";
    }
}
?>
</section>

</body>
</html>
