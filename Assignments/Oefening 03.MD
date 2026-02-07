# Opdracht 3 - Accounts gebruiken om in te loggen

In deze opdracht maken we weer gebruik van de informatie in de LDAP-server (via 
[https://localhost:8080](https://localhost:8080) of Apache Directory Studio).

## Instructie

Zoek nu een andere _student_ of _teacher_ op in deze lijst met accounts. De studenten vind je op

> `dc=NHLStenden,dc=com > ou=Opleidingen > ou=Students`

De docenten vind je op de locatie

> `dc=NHLStenden,dc=com > ou=Opleidingen > ou=Teachers`


Je kunt zien welke student of docent bij welke opleiding hoort in de LDAP-groepen op de locatie

> `dc=NHLStenden,dc=com > ou=opleidingen > ou=roles`

Bijvoorbeeld:

> `cn=Teachers ADCSS,ou=opleidingen,ou=roles,dc=NHLStenden,dc=com`


Tip

> Als je van gebruiker wilt wisselen, gebruik dan de `Logout` button. 


## Opdracht

1. Log in bij de website [http://grades.rbac.docker/](http://grades.rbac.docker/) met diverse rollen (student, teacher).
2. Merk op hoe de navigatiebalk steeds veranderd afhankelijk van wie er inlogt.
3. Maak een tabel met de mogelijke navigatie voor de verschillende gebruikers.

We gaan nu inloggen op [de cijferadministratie](http://grades.rbac.docker/) als student. Na inloggen krijg je een navigatiebalk met
locaties/applicaties waar je naar toe mag. We gaan nu proberen om een pagina te benaderen die niet in de navigatie zit
maar wel in de website beschikbaar is.

1. Probeer eens of je deze URL kunt openen: https://grades.rbac.docker/intranet/view-student.php.

Kijk eens in dit project in de map `{ROOT}volumes/src/websites/grades/intranet`. Hier zie je diverse bestanden staan
eindigend op de extensie `.php`. Elk bestand is een webpagina. Dus `my-data.php` kun je openen met:

[https://grades.rbac.docker/intranet/my-data.php](https://grades.rbac.docker/intranet/my-data.php)

Probeer verschillende van deze pagina's te openen en noteer het resultaat in een lijstje.

Als je nieuwsgierig bent kun je eens in de code kijken hoe de beveiliging geprogrammeerd is!

# Navigatie

Navigeer hier naar de volgende [opdracht](./Oefening%2004.MD) of de vorige [opdracht](./Oefening%2002.MD).

[Uitwerking](./Oplossingen-03.MD)