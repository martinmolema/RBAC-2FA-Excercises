# Opdracht 2 - Inzicht in account administratie

In deze opdracht gaan we in de account administratie kijken naar meer informatie. Deze is opgenomen in een zogenaamde
*LDAP-store*.

## Instructie

Start nu een LDAP-management tool zoals de _Apache Directory Studio_. Zie de
[installatiehandleiding](../Install/README.md#verbinding-maken-met-de-identity-server-ldap) voor instructies. Of login op de website http://localhost:8080 

* Login DN : cn=admin,dc=NHLStenden,dc=com
* Password : test12345!


## Opdracht

Navigeer in de _Directory Information Tree_ (**DIT**) naar onderstaande locatie:

>    `dc=NHLStenden,dc=com > ou=Opleidingen > ou=Teachers`

De zogenaamde **Distinguished Name** van deze locatie is

>    `ou=Teachers,ou=Opleidingen,dc=NHLStenden,dc=com`

Je ziet hier een aantal gebruikersaccounts. Zoek de account *'Guus Willems'* en klik hier op. Je krijgt de eigenschappen
te zien.

![opdracht 2 - 02 user details gwillems.png](./images/opdracht%202%20-%2002%20user%20details%20gwillems.png)


1. Welke eigenschap van het account heb je gebruikt om mee in te loggen?
2. Probeer eens in te loggen met andere accounts uit de lijst met accounts op verschillende plaatsen.


# Navigatie

Navigeer hier naar de volgende [opdracht](./Oefening%2003.MD) of de vorige [opdracht](./Oefening%2001.MD).

[Uitwerking](./Oplossingen-02.MD)