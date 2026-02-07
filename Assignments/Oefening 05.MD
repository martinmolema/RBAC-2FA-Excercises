# Opdracht 5 - Ongewenste toegangsrechten - Functiescheiding

In de vorige opdracht hebben we de toegangsrechten aangepast door rechten weg te halen. Het is natuurlijk ook mogelijk
om de rechten uit te breiden.

## Introductie

Er zijn twee manieren om toegangsrechten toe te wijzen:

1. De permissies bij een rol aanpassen
2. Een nieuwe rol toewijzen (want deze impliceert dat je permissies krijgt).

Als we nieuwe permissies toewijzen aan een rol, dan zullen alle gebruikers onmiddellijk deze toegangsrechten kunnen
gebruiken. Als we een nieuwe rol toewijzen aan een gebruiker dan gelden de permissies die bij die rol horen natuurlijk
alleen voor deze gebruiker.

In beide gevallen kan er iets misgaan: je kunt onbedoeld rechten krijgen tot handelingen die elkaar horen uit te
sluiten. Een veelgebruikt voorbeeld is dat je iets kunt bestellen, maar ook de bestelling kan goedkeuren. Dan kun
je mooi een dure auto bestellen én je zorgt ook dat deze betaald wordt.

Het scheiden van dergelijke functies noemen we dan ook "Functiescheiding" of (in het Engels) "Segregation Of Duty". Op
deze manier zorg je er voor dat minimaal twee mensen betrokken moeten zijn bij dergelijke handelingen.

In ons voorbeeld kunnen we dit laten zien door een gebruiker een rol toe te wijzen van Marketing Manager én gewone
Marketing medewerker. Een medewerker kan nu een campagne maken én goedkeuren zodat deze ook daadwerkelijk gaat starten.

## Opdracht

Login bij het [Admin Panel](http://admin.rbac.docker) en experimenteer eens met beide mechanismen. Verander de autorisaties
door onderstaande procedures te volgen.

**Rol toevoegen**

* Voeg via het scherm 'Autorisatie aanvraag' bij een gebruiker van *Marketing* de rol "Marketing Manager" toe.
* Login bij de Marketing portaal als deze medewerker en merk op wat je allemaal mag
* Trek de autorisatie weer in (rol verwijderen via 'Revoke button')
* Ververs de pagina van de Marketing portaal en zie de veranderingen

**Permissie toevoegen**

* In het Admin-panel navigeer je naar 'Rollen'. Zoek de rol 'Marketing' en gebruik de 'Edit' button om te navigeren
  naar de pagina om de permissies bij deze rol te wijzigen
* Voeg de permissie "Approve a marketing campaign" toe en druk op de button 'Toevoegen' om deze toe te voegen aan de
  rol "Marketing"
* Login bij de Marketing portaal als deze medewerker en merk op wat je allemaal mag
* Log uit bij deze gebruiker en sluit het tabblad
* Open een nieuw tabblad en login als andere gewone Marketing gebruiker
* Merk op wat ook deze gebruiker allemaal mag
* Ga terug naar het Admin panel en verwijder de permissie "Approve a marketing campaing" bij de rol "Marketing"

## Vragen

1. Wat zou een technische maatregel kunnen zijn om deze ongewenste situaties te voorkomen?
2. Wat zou een organisatorische maatregel kunnen zijn om deze ongewenste situatie te voorkomen?
3. Bij wie zou de verantwoordelijkheid moeten liggen om te zorgen dat dit niet kan gebeuren? Technisch of juist
  organisatorisch?

In de volgende opdracht gaan we kijken naar hoe dit proces ondersteund kan worden.

# Navigatie

Navigeer hier naar de volgende [opdracht](./Oefening%2006.MD) of de vorige [opdracht](./Oefening%2004.MD).

[Uitwerking](./Oplossingen-05.MD)
