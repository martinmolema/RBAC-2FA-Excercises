# Opdracht 8 - Attestation

Bij opdracht 8 zijn de volgende vragen geformuleerd:

1. Stel jij bent beheerder van deze school. Je kunt deze rapportages gebruiken om de organisatie beter te beveiligen.
   Aan wie ga je deze rapportages sturen?
2. Wie zou er in actie moeten komen bij afwijkingen?

## **Uitwerking vraag 1**

Het is verstandig om deze naar elke management laag te sturen, maar ook naar projectleiders die bijvoorbeeld tijdelijke
krachten inhuren of vaste medewerkers inzetten. Immers, door zo'n project krijg je vaak extra toegangsrechten om in het
project mee te kunnen draaien.

De manager van een afdeling of team kent de medewerkers het beste en weet welke rollen er nodig zijn.

Een goede doelgroep zijn ook Functioneel Beheerders. Dit zijn medewerkers die vaak autorisatiebeheer uitvoeren voor een
"informatievoorziening" (Zie het [BiSL-framework])(https://bislfoundation.nl/het-model/) voor Informatie Management.
Zij begrijpen vaak goed hoe het autorisatiemodel van de applicatie in elkaar steekt en kunnen daardoor samen met een
team manager vaak goed uitzoeken hoe het zit met toegekende autorisaties en de opbouw van de rollen en permissies.

Een andere doelgroep kunnen de data-eigenaren zijn. Dit zijn medewerkers die veel kennis hebben over de gegevens /
informatie in een organisatie, vaak georganiseerd rondom een bedrijfsproces, thema of project. Helaas zijn in de
kleinere
organisaties dergelijke mensen maar lastig te vinden en ligt dit vaak bij een proceseigenaar of zelfs de IT-afdeling.

## **Uitwerking vraag 2**

Het lijkt niet heel erg moeilijk om bij afwijkingen zaken recht te zetten. Echter, het kan erg ingewikkeld worden.
De technische afhandeling van een correctie (toewijzen of intrekken van een autorisatie) kan bijvoorbeeld bij een
functioneel beheerder of IT-afdeling belegd worden.

Maar het is lastiger om te bepalen "wat is fout?" en "wanneer is het fout?".

**Wanneer is het fout**

Eerst een korte toelichting op "wanneer is het fout?". Zie ook de uitwerking bij
[opdracht 6](./Oplossingen-06.MD#uitwerking-vraag-2): de wijzigingen worden al vroeg aangekondigd maar worden pas na het
verstrijken van zo'n datum (in dienst, uit dienst) actief. Daarnaast kan het zijn dat het niet mogelijk is om de
juiste autorisaties geautomatiseerd door te voeren omdat deze bijvoorbeeld niet in een bronsysteem zitten. Zo kan het
zijn dat een medewerker in een project gaat meedraaien, maar dat dit niet formeel in het HRM-systeem wordt vastgelegd.
Er zal dan dus een handmatige autorisatie aanvraag gedaan moeten worden.

**Wat is fout**

De definitie van "wat is goed" of "wat is fout" kan een erg complexe zijn. De tijdigheid speelt dus een rol, maar ook
is het soms lastig om de werkzaamheden te koppelen aan de autorisaties die daarvoor nodig zijn. Dit leidt in de praktijk
vaak tot onderstaand gedrag:

* Geef die nieuwe medewerker Karel maar dezelfde rechten als Peter; die deed dat werk ook en hij kon prima uit de voeten
  (met de autorisaties die hij gekregen had)
* Men durft autorisaties niet in te trekken omdat niet precies bekend is welke effecten dat dan heeft.
* Men vergeet handmatig aangevraagde autorisaties in te trekken als een medewerker van functie of team wisselt
* Vaak zijn er inwerkperiodes nodig: de opvolger van een medewerker (die naar een ander team gaat) moet nog kunnen
  leunen op de expertise van de die medewerker. En dan is het wel zo handig dat de medewerker wat langer zijn
  autorisaties houdt. Hierdoor is het makkelijk om het zicht te verliezen dat hiervoor nog wijzigingen nodig gaan
  zijn in de toekomst.