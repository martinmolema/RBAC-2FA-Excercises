# Opdracht 9 - Aanmaken nieuwe rol

Hier gaan we onderzoeken hoe we een nieuwe rol kunnen maken om deze in te zetten voor ons personeel.

## Introductie

Stel er is een nieuw team opgezet dat marketing campagnes moet monitoren op voortgang. We noemen dit team de
"Campagne Monitors". Het aanmaken van zo'n nieuw team moet kunnen zonder dat je een programmeur nodig hebt. Dit is één
van de voornaamste redenen om in je broncode niet de rol maar permissies te controleren. Je stelt nu een Functioneel
Beheerder in staat om dit zelfstandig te doen.

## Opdracht

Deze opdracht maakt gebruik van een instructie die opgenomen is in een
apart [bestand](./CreateNewGroupOfUniqueNames.MD).
Stappen 1 en 2 worden hier in detail beschreven.

1. Maak via LDAP een nieuwe "GroupOfUniqueNames" aan in de DN `ou=roles,dc=NHLStenden,dc=com`.
2. Gebruik de naam "Campagne Monitors" voor de CN.
3. Login bij het Admin Panel
4. Ga naar 'Rollen' en start de synchronisatie.
    1. Als het goed is wordt de nieuwe rol uit LDAP overgenomen in de database. Je kunt dit zien in de logs op het
       scherm

```text
Controleer rol: Authorisation Manager
Controleer rol: All Personell
Controleer rol: All Students
Controleer rol: All Teachers
Controleer rol: Grades Students
Controleer rol: Grades Teachers
Controleer rol: HRM
Controleer rol: ICT Support
Controleer rol: Marketing managers
Controleer rol: Marketing
Controleer rol: SharePoint Students
Controleer rol: SharePoint Teachers
Controleer rol: Students ADCSS
Controleer rol: Students HBO-ICT
Controleer rol: Teachers ADCSS
Controleer rol: Teachers HBO-ICT
Controleer rol: Campagne Monitors
- Nieuwe rol gedetecteerd : cn=campagne monitors,ou=roles,dc=nhlstenden,dc=com


Synchronisatie voltooid. Er zijn 1 nieuwe rollen aangemaakt in de permissie administratie
```

5. Ga terug naar de rollen
6. Zoek de regel in de tabel met 'Campagne Monitors' op en druk op de `Edit` knop om deze te wijzigen
7. Voeg de permissie 'Read a Marketing Campaign' toe

Nu gaan we een gebruiker autoriseren voor deze rol

1. Login bij het [Admin Panel](http://admin.rbac.docker/intranet)
2. Ga naar [Autorisatie Aanvraag](http://admin.rbac.docker/intranet/AssignUserToRoleForm.php)
3. Kies de nieuwe rol **'Campagne Monitors'** bij de rol
4. Kies een stafmedewerker (niet een medewerker die al bij de afdeling Marketing zit!) bijv. Sophie Bakker van HRM
5. Druk op Autoriseer

We gaan nu kijken of de nieuwe medewerker ook daadwerkelijk naar de Marketing Website mag

1. Ga naar de SharePoint [website](http://sharepoint.rbac.docker/)
2. Login als de gebruiker die je net geautoriseerd hebt
3. Zie dat er in de menu balk nu een button staat voor Marketing
4. Druk op de button Marketing
5. Login in bij de website voor Marketing (let op: als je al ingelogd was eerder moet je mogelijk daar eerst uitloggen).

Het blijkt dat we mogelijk nog niet de juiste autorisaties hebben. Je kunt dit controleren op het 
[Admin Panel](http://admin.rbac.docker/intranet/logging.php) bij de Apache Logfiles van "Marketing".

1. Waarom lukt het maar niet om in te loggen op de marketing website? Welke autorisatie mist er (zie de tabel bij
   opdracht 7 )
2. Ken de missende autorisaties toe aan de rol 'Campagne Monitors'
3. Probeer opnieuw in te loggen.

Nog steeds zal het niet lukken om in te loggen op de website van Marketing. Dit heeft te maken met de beveiliging op
applicatie niveau. Om gebruik te maken van de website moet je de rol 'Marketing' hebben. Het is dus niet genoeg om
allerlei Marketing-*permissies* te hebben. Je moet ook de rol *"Marketing"* hebben. Zie de flowchart in de beschrijving
[Authentication and Authorisation](../Documentation/Authentication%20and%20Autorisation.MD)

Dit is in de webserver voor de Marketing Website zo geconfigureerd:

```apacheconf
  Require ldap-group cn=Marketing,ou=roles,dc=NHLStenden,dc=com
```

We krijgen nu wel de vreemde situatie dat dus een HRM medewerker ook bij de afdeling Marketing lijkt te werken als je
naar de autorisaties kijkt. Door middel van de (bijvoorbeeld maandelijkse) attestation rapporten (zie Oefening 8) zou
dat duidelijk kunnen worden en wellicht zal een manager van HRM óf Marketing zich afvragen hoe dat zo gekomen is. We komen
daar in [oefening 11](./Oefening%2011.MD) op terug.


# Navigatie

Navigeer hier naar de volgende [opdracht](./Oefening%2010.MD) of de vorige [opdracht](./Oefening%2008.MD).

[Uitwerking](./Oplossingen-09.MD)
