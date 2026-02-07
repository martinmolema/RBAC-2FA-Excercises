<?php
$campagnes = [
  ["id" => 1, "titel" => "Beleef de Campus Dag", "beschrijving" => "Kom langs en ervaar hoe een dag op onze campus eruitziet.", "datum" => "15 januari 2024"],
  ["id" => 2, "titel" => "Virtual Reality Studiekeuze Tour", "beschrijving" => "Ontdek je toekomstige opleiding vanuit je eigen woonkamer!", "datum" => "22 januari 2024"],
  ["id" => 3, "titel" => "Open Avond voor Werkende Professionals", "beschrijving" => "Speciaal voor werkenden die willen omscholen of bijscholen.", "datum" => "30 januari 2024"],
  ["id" => 4, "titel" => "Ouder- en Studentendag", "beschrijving" => "Leer samen met je ouders alles over jouw studiekeuze.", "datum" => "10 februari 2024"],
  ["id" => 5, "titel" => "Masterclass Toekomstgericht Onderwijs", "beschrijving" => "Vooruitstrevende workshops voor toekomstige studenten.", "datum" => "18 februari 2024"],
  ["id" => 6, "titel" => "Techniek Festival", "beschrijving" => "Ontdek de nieuwste snufjes en opleidingen in techniek.", "datum" => "25 februari 2024"],
  ["id" => 7, "titel" => "Proefstudeer Dagen", "beschrijving" => "Ervaar een dag als student in een opleiding naar keuze.", "datum" => "1 maart 2024"],
  ["id" => 8, "titel" => "Internationale Studenten Informatieavond", "beschrijving" => "Voor studenten uit het buitenland die hier willen studeren.", "datum" => "15 maart 2024"],
  ["id" => 9, "titel" => "Duurzaamheid en Onderwijs Expo", "beschrijving" => "Inspirerende sessies over duurzame keuzes in onderwijs.", "datum" => "22 maart 2024"],
  ["id" => 10, "titel" => "Docent voor een Dag", "beschrijving" => "Ervaar hoe het is om leraar te worden tijdens deze unieke dag.", "datum" => "30 maart 2024"]
];

// Functie om de campagnes weer te geven
function displayCampaigns(string $campaignListButtonCaption, RBACSupport $rbac, string $needsPermission): string
{
  global $campagnes;
  $output = <<<HTML
<section class="campaigns">
    <table>
        <caption>Onderwijs Marketing Campagnes</caption>
        <thead>
            <tr>
                <th>#</th>
                <th>Titel</th>
                <th>Beschrijving</th>
                <th>Datum</th>
            </tr>
        </thead>
        <tbody>
HTML;

  // Loop door de array van campagnes en genereer de tabelrijen
  foreach ($campagnes as $campagne) {
    $buttonHTML = '';
    if ($rbac->has($needsPermission)) {
      $buttonHTML .= "<button>{$campaignListButtonCaption}</button>";
    }
    $output .= <<<HTML
        <tr>
            <td>{$campagne['id']}</td>
            <td>{$campagne['titel']}</td>
            <td>{$campagne['beschrijving']}</td>
            <td>{$campagne['datum']}</td>
            <td>{$buttonHTML}</td>
        </tr>
HTML;
  }

  $output .= <<<HTML
        </tbody>
    </table>
</section>
HTML;

  echo $output;
  return true;
}

?>
