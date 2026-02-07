# Opdracht 10 - Automatische provisioning van gebruikers

## Instructie

Het proces van "User Provisioning" zorgt voor het overbrengen van identiteitsinformatie van bronsystemen naar
doelsystemen.
In ons voorbeeld gaat het om het overbrengen van medewerkersinformatie naar de LDAP-administratie.

## Verdieping

Lees de achtergronden rondom automatische User Provisioning: [User Provisioning](./User%20Provisioning.MD).

Er is een achtergrond proces gemaakt dat elke 3 minuten een synchronisatie doet tussen de HRM-database en de LDAP-store.

## Opdracht

Voor deze opdracht moet je iets meer weten over hoe je kunt nagaan of een gebruiker lid is van een bepaalde groep. Deze 
groepen staan in de LDAP administratie en kun je inzien met de Apache Directory Studio of vergelijkbare software.
Hieronder vind je meer informatie:

De rollen zijn ondergebracht op de volgende locatie: `ou=roles,dc=NHLStenden,dc=com`

| Rol                                             | Distinguished Name                                     |
|-------------------------------------------------|--------------------------------------------------------|
| Medewerkers ICT Support met autorisatierechten  | cn=Authorisation Manager,ou=roles,dc=NHLStenden,dc=com | 
| Al het personeel                                | cn=All Personell,ou=roles,dc=NHLStenden,dc=com         | 
| Alle studenten                                  | cn=All Students,ou=roles,dc=NHLStenden,dc=com          | 
| Alle docenten                                   | cn=All Teachers,ou=roles,dc=NHLStenden,dc=com          | 
| Toegang tot de cijfer administratie als student | cn=Grades Students,ou=roles,dc=NHLStenden,dc=com       | 
| Toegang tot de cijfer administratie als docent  | cn=Grades Teachers,ou=roles,dc=NHLStenden,dc=com       | 
| Human Resource Management                       | cn=HRM,ou=roles,dc=NHLStenden,dc=com                   | 
| Medewerkers ICT Support                         | cn=ICT Support,ou=roles,dc=NHLStenden,dc=com           | 
| Medewerkers Marketing                           | cn=Marketing,ou=roles,dc=NHLStenden,dc=com             | 
| Managers van de afdeling Marketing              | cn=Marketing managers,ou=roles,dc=NHLStenden,dc=com    | 
| Toegang tot sharepoint voor studenten           | cn=SharePoint Students,ou=roles,dc=NHLStenden,dc=com   | 
| Toegang tot sharepoint voor docenten            | cn=SharePoint Teachers,ou=roles,dc=NHLStenden,dc=com   | 

1. Login op de HRM website
2. Kijk welke medewerkers er zijn. Wijzig de functie van een medewerker. Onthoudt even de gebruikersnaam.
3. Wacht op de synchronisatie. Je kunt dit volgen via een logfile in de map `{ROOT}/volumes/src/user-provisioning`.
4. Kijk in de LDAP-store naar de medewerker.
    1. staat de gebruiker nog op de juiste plek?
    2. heeft de gebruiker de juiste rechten (groepslidmaatschappen, zie tabel hier boven) ?
5. Kijk via het Admin-panel naar de eigenschappen van de gebruiker (via de button `Zoeken`).
6. Lukt het nu om in te loggen op de website die bij deze rol hoort?

# Navigatie

Navigeer hier naar de volgende [opdracht](./Oefening%2011.MD) of de vorige [opdracht](./Oefening%2009.MD).

[Uitwerking](./Oplossingen-10.MD)
