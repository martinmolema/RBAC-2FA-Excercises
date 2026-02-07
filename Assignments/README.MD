# Oefeningen rondom het onderwerp Identity and Access Management en User Provisioning

Als de Docker containers draaien is het tijd om een aantal opdrachten en oefeningen uit te voeren. Deze worden in
dit document verder uitgewerkt. De oplossingen / uitwerkingen staan aan in een apart [document](Oplossingen.MD).

Als je met onderstaande opdrachten aan de slag gaat dan kun je onderstaande tabel gebruiken voor de initiële login
gegevens.

| URL                       | rol       | username   |
|---------------------------|-----------|------------|
| http://marketing.rbac.docker/  | gebruiker | `fbos`     | 
| http://grades.rbac.docker/     | student   | `edeboer`  |
| http://grades.rbac.docker/     | teacher   | `gwillems` |
| http://admin.rbac.docker/      | admin     | `tvisser`  |
| http://sharepoint.rbac.docker/ | student   | `edeboer`  |
| http://sharepoint.rbac.docker/ | teacher   | `gwillems` |
| http://hrm.rbac.docker/        | HRM       | `kmulder`  |

Er verschijnt een popup waar je moet inloggen. Het wachtwoord is altijd `Test1234!`.

Later zul je ook op een andere manier gaan inloggen. De instructies daarvoor volgen in de opdrachten.

Er is nog een extra website die als alternatief geldt voor de _Apache Directory Studio_:
de [PHP Ldap Admin](http://localhost:8080/). Deze geeft een vrij basic User Experience om met het beheer van identiteiten
om te gaat, maar het bespaart een installatie van Java en Apache Directory Studio. 

Je logt hier op in met de volgende gegevens:

* Login DN : cn=admin,dc=NHLStenden,dc=com
* Password : test12345!

## Opdrachten

De volgende opdrachten en oefeningen zijn opgenomen:

1. [In kaart brengen van beveiliging](./Oefening%2001.MD)
2. [Inzicht in account administratie](./Oefening%2002.MD)
3. [Accounts gebruiken om in te loggen](./Oefening%2003.MD)
4. [Handmatige aanpassing van toegangsrechten](./Oefening%2004.MD)
5. [Ongewenste toegangsrechten - Functiescheiding](./Oefening%2005.MD)
6. [Inzicht in rollen - gebruikers](./Oefening%2006.MD)
7. [Inzicht in rollen - permissies](./Oefening%2007.MD)
8. [Attestation](./Oefening%2008.MD)
9. [Aanmaken nieuwe rol](./Oefening%2009.MD)
10. [Automatische provisioning van gebruikers](./Oefening%2010.MD)
11. [Audit trails](./Oefening%2011.MD)
12. [Instellen van functiescheiding](./Oefening%2012.MD)

Je vindt de oefeningen in apart genummerde bestanden in deze map. In het bestand [Oplossingen](./Oplossingen.MD) vind
je de uitwerkingen van de oefeningen.

Als je klaar bent met de oefeningen dan kun je eventueel aan de slag met het zelf uitbreiden van de permissies in de
broncode. Zie hiervoor het bestand [Autorisaties toevoegen](./Autorisaties%20toevoegen.MD).

---

Martin Molema (martin.molema@nhlstenden.com)

NHL Stenden

Februari 2026