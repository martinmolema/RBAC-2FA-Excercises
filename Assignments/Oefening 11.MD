# Opdracht 11 - Audit trails

In deze oefening kijken we naar audit trail. Een audit trail is een verzameling historische informatie die helpt bij
het beantwoorden van de vraag "hoe heeft deze situatie kunnen ontstaan". Een voorbeeld van zo'n vraag is hoe iemand
aan bepaalde autorisaties is gekomen. Wie heeft bijvoorbeeld deze autorisatie doorgevoerd of aangevraagd? Wanneer is
deze autorisatie doorgevoerd?

# Introductie

In de oefening over [Attestation](./Oefening%2008.MD) hebben we gezien dat we de huidige situatie kunnen opvragen en
in een Excel-bestand kunnen opslaan voor verdere verwerking. Dat is een mooi mechanisme, maar het beantwoord alleen de
vraag "Wat is de huidige situatie" en niet "hoe is deze ontstaan".

Een audittrail kan bestaan uit informatie over verschillende systemen, of zelfs op papier vastgelegd zijn. Zo zal in de
systemen die binnen deze opdracht de informatie over geldige logins bijgehouden worden door de webserver (zie de pagina
voor *Apache Logfiles*) terwijl informatie over het toekennen of intrekken van autorisaties in de database wordt
bijgehouden.

In de opdrachten gaan we een aantal wijzigingen doorvoeren om te kijken hoe het audit trail hier op reageert.

* Het doorvoeren van een autorisatie wijziging bij een gebruiker
* Het opvoeren van een nieuwe medewerker
* Het wijzigen van een medewerker
* Het verwijderen van een medewerker
* Het wijzigen van de permissies bij een rol

# Opdracht

We gaan eerst een wijziging doorvoeren in de autorisaties van een gebruiker

1. Login op het Admin Panel als een gebruiker met de rol Autorisatie manager.
2. Navigeer naar *Autorisatie aanvraag*
3. Kies een rol; bijvoorbeeld "Marketing Managers"
4. Kies een (passende) gebruiker; in dit geval iemand uit de groep "Marketing"
5. Ken de autorisatie toe

We hebben nu een wijziging doorgevoerd. Dat gaan we op twee manier controleren:

1. Open de webpagina voor "Attestation - Gebruikers"
2. Zet in de filter voor "Marketing Manager" een "X" zodat alleen gebruikers met deze rol getoond worden.
3. Controleer of de gebruiker die je net hebt geautoriseerd voor deze rol, er ook daadwerkelijk bij staat.
4. Navigeer naar de webpagina "Audit Trail"
5. Kijk of je deze wijziging terug kunt in vinden.

De volgende stap is het opvoeren van een nieuwe gebruiker

1. Login op de website van HRM met een HRM gebruiker (handigste is om dit op een nieuw tabblad te doen in je browser)
2. Klik op de button "Nieuwe gebruiker".
3. Vul de gegevens in en druk op "Opslaan".
4. Zoek de gebruiker op in de lijst. Je ziet (zeer waarschijnlijk) bij de kolom "DN" staan "[To be determined]". Dit
   komt
   doordat er nog geen gebruiker gemaakt is. Wacht (maximaal 3 minuten) totdat hier een geldige 'DN' komt te staan.
5. Ga terug naar het Admin Panel en ververs de webpagina voor het Audit Trail
6. Zoek de nieuwe medewerker op.
7. Kijk bij de attestation - gebruikers weer of de gebruiker de juiste rol heeft gekregen. Je kunt filteren door de
   naam te selecteren in de eerste kolom.
8. Kijk in het project naar de map `{ROOT}volumes/src/user-provisioning` en zoek de naam die je net hebt opgevoerd.

We gaan nu een gebruiker wijzigen via het HRM portaal

1. Login op de website van HRM met een HRM gebruiker (handigste is om dit op een nieuw tabblad te doen in je browser)
2. Selecteer een gebruiker om te wijzigen en gebruik de knop "Bewerken" om naar het wijzig-scherm van deze gebruiker
   te gaan.
3. Wacht weer tot de wijzigingen door het *User Provisioning* proces zijn doorgevoerd.
4. Check dezelfde bronnen als bij het opvoeren van een nieuwe gebruiker (stappen 4 t/m 8)

Vervolgens gaan we een medewerker die we net hebben aangemaakt verwijderen. Dit zou typisch gebeuren als de medewerker
het bedrijf verlaat.

1. Zoek als HRM-medewerker de nieuw aangemaakte gebruiker op in de lijst en druk op de knop `Verwijderen`.
2. Wacht weer tot de wijzigingen door het *User Provisioning* proces zijn doorgevoerd.
3. Kijk in het project naar de map `{ROOT}volumes/src/user-provisioning` en zoek de naam die je net hebt opgevoerd.

# Vragen

1. Van welke handelingen zou jij zelf een audit trail bij willen houden als het gaat om security, IAM en dergelijke?
   Denk aan rollen, gebruikers, permissies en de relaties daar tussen.
2. De informatie over "wat is er gebeurd met een gebruiker" is behoorlijk verspreid over het systeem. Hoe zou je daar
   toch inzicht in kunnen krijgen? Welk gegeven verbind al deze verspreidde informatie?
3. Wat is het effect op de **gebruiker** die gekoppeld was aan de **medewerker** die verwijderd is?
4. Wat zijn mogelijke redenen dat de gebruiker van de verwijderde medewerker niet weggegooid wordt?
5. Wat zou een redelijke bewaartermijn kunnen zijn van accounts van vertrokken medewerkers?

Navigeer hier naar de volgende [opdracht](./Oefening%2012.MD) of de vorige [opdracht](./Oefening%2010.MD).

[Uitwerking](./Oplossingen-11.MD)
