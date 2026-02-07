# Opdracht 5 - Ongewenste toegangsrechten - Functiescheiding

Bij opdracht 5 horen de volgende vragen:

1. Wat zou een technische maatregel kunnen zijn om deze ongewenste situaties te voorkomen?
2. Wat zou een organisatorische maatregel kunnen zijn om deze ongewenste situatie te voorkomen?
3. Bij wie zou de verantwoordelijkheid moeten liggen om te zorgen dat dit niet kan gebeuren? Technisch of juist
   organisatorisch?

## **Uitwerking vraag 1**

Je kunt proberen om tijdens het wijzigen van rollen en het toewijzen van de permissies per rol dit te 
controleren. Je kunt dan controleren of er niet een rol gemaakt wordt die ongewenste permissies combineert. 

Maar het probleem kan net zo goed ontstaan bij het toewijzen van een rol aan een gebruiker. Je moet dan 
telkens controleren of de tegenstrijdige permissies niet per ongeluk toch toegestaan worden.

## **Uitwerking vraag 2**

Je kunt ook organisatorische maatregelen definiëren, maar dit vereist discipline. Bij het maken/wijzigen
van rollen en het toekennen van de rollen aan gebruikers zul je dan steeds met de hand zaken moeten controleren.
De vraag is of degene die dit werk verricht begrijpt hoe dat moet en wat de consequenties zijn bij het toestaan
van de ongewenste situatie. We komen hier in opdracht 8 (Attestation)  verder op terug.

## **Uitwerking vraag 3**

Hier zit een *addertje onder het gras*. Want wat is 'verantwoordelijk'? Natuurlijk moet degene die rollen bouwt
en toekent zijn werk goed doen. Als er gevraagd wordt om een autorisatie door te voeren die eigenlijk niet kan/mag,
dan moet deze niet uitgevoerd worden natuurlijk. Echter, als de procedure of inhoud niet duidelijk is (en dat
is zeker een risico bij complexe, _real-life_ situaties) dan is de manager of aanvrager uiteindelijk 'eind verantwoordelijk'.
Om dit beter inzichtelijk te krijgen gebruiken we ook wel het [RACI model](https://nl.wikipedia.org/wiki/RACI-model). 
Hierin wordt onderscheid gemaakt tussen 'Responsible' en 'Accountable'. De uitvoerende partij is 'Responsible'
(verantwoordelijk) en legt verantwoording af aan degene die 'Accountable' (Eindverwantwoordelijk) is.
