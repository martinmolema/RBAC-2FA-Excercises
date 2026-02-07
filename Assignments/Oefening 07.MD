# Opdracht 7 - Inzicht in rollen - permissies

## Introductie

Als een centrale oplossing zoals LDAP of Active Directory wordt gebruikt om **rollen** te administreren, dan zal een
applicatie zelf moeten bepalen welke permissies er voor die applicatie gelden. Dit _kan_ ook in een centrale
oplossing maar in ons voorbeeld is dat geregeld in slechts één database.

Het admin panel stelt ons in staat om de permissies bij een rol in te zien.

## Opdracht

1. Navigeer naar [Admin Panel](http://admin.rbac.docker/intranet) en login met een gebruiker van ICT Support.
2. Gebruik de knop 'Rollen' om naar de administratie van de rollen te gaan. Er volgt een lijst met beschikbare rollen.
3. Klik op de knop 'Edit' achter de rol "All Students".

## Vragen

1. Welke permissies heeft deze rol momenteel?
2. Wat zegt dit over de permissies van één individuele student?
3. Login als een student bij [SharePoint](http://sharepoint.rbac.docker)
4. Ga naar de pagina "Mijn Gegevens" en vergelijk de lijst met Permissies met die in het admin panel

5. Log de student uit en login bij [SharePoint](http://sharepoint.rbac.docker) als een Marketing 
   Manager (zie  `cn=Marketing managers,ou=roles,dc=NHLStenden,dc=com`)
6. Ga naar de pagina met "Mijn Gegevens"
7. Ga in het Admin Panel terug naar de pagina met rollen en zoek de rol van Marketing Manager
8. Wat valt op qua permissies met de "Mijn gegevens" pagina van de gebruiker zelf?

# Navigatie

Navigeer hier naar de volgende [opdracht](./Oefening%2008.MD) of de vorige [opdracht](./Oefening%2006.MD).

[Uitwerking](./Oplossingen-07.MD)
