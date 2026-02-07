# Opdracht 11 - Audit trails

Bij opdracht 11 zijn de volgende vragen geformuleerd.

1. Van welke handelingen zou jij zelf een audit trail bij willen houden als het gaat om security, IAM en dergelijke?
   Denk aan rollen, gebruikers, permissies en de relaties daar tussen.
2. De informatie over "wat is er gebeurd met een gebruiker" is behoorlijk verspreid over het systeem. Hoe zou je daar
   toch inzicht in kunnen krijgen? Welk gegeven verbind al deze verspreidde informatie?
3. Wat is het effect op de **gebruiker** die gekoppeld was aan de **medewerker** die verwijderd is?
4. Wat zijn mogelijke redenen dat de gebruiker van de verwijderde medewerker niet weggegooid wordt?
5. Wat zou een redelijke bewaartermijn kunnen zijn van accounts van vertrokken medewerkers?

## **Uitwerking vraag 1**

Je kunt het zo uitgebreid of simpel maken als je wilt. Let wel op welke informatie je in het audit-trail opneemt. Er kunnen
persoonsgegevens in het spel zijn die in het kader van de AVG goed afgeschermd moeten worden!

* Rollen
  * toewijzen en intrekken van rollen aan gebruikers
  * wijzigingen in de permissies van een rol
* gebruikers
  * aanmaken nieuwe gebruiker
  * verwijderen gebruiker
  * wachtwoord wijzigen
* foutieve login pogingen
* ophogen teller "aantal foute login pogingen"
* uitschakelen gebruiker (+reden waarom)
* ....

## **Uitwerking vraag 2**

Meestal zorg je dat óf het personeelsnummer óf een gebruikersnaam (uid) in elk audittrail zit. Zorg ook voor datum en tijd
zodat je een tijdslijn kunt opbouwen van wat er met een gebruiker gebeurd is.

## **Uitwerking vraag 3 + 4**

Vaak blijven zowel medewerkers als gebruikers langer in het systeem dan dat ze in dienst zijn bij de werkgever. Redenen
hiervoor kunnen zijn:

* het niet meteen willen weggooien van allerlei bestanden; een medewerker wil persoonlijke bestanden misschien nog archiveren
  of nog uitzoeken voor de andere collega's.
* afhandeling van salaris en pensioen
* Gebruikers die "per ongeluk" verwijderd zouden worden; stel dat je een procedurele fout maakt, dan wil je niet meteen
  alle bestanden, mail, cloud-opslag, licenties etc. definitief verwijderen. 

Daarom zijn er voor gebruikers die geen toegang meer moeten krijgen in verband met "uit dienst" geblokkeerd op zo'n manier
dat ze niet meer meedoen met User Provisioning (processen) en dergelijke. Soms wordt ook een "datum uit dienst + 30 dagen"
gehanteerd voordat zaken definitief worden opgeruimd.

## **Uitwerking vraag 5**

De bewaartermijn hangt heel erg af van de situatie. Gebruikers bestaan vaak in vele systemen en kunnen soms ook helemaal
niet echt verwijderd worden. In verband met wettelijke eisen of audits kan het nodig zijn om gebruikersaccounts nog jaren
te moeten bewaren.



