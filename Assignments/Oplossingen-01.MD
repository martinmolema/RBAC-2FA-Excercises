# Opdracht 1

Door in te loggen op de LDAP-administratie kan gekeken worden naar de structuur van de account administratie.
Het personeel van Marketing is geen onderwijzend personeel dus valt onder Staff/Marketing.

![opdracht 1 - 01.png](images/opdracht%201%20-%2001.png)

De aanname is dat je verwacht dat deze groep gebruikers inderdaad bij de applicatie voor Marketing website hoort. Dat
kun je alleen zeker weten door het aan de programmeur of een website beheer te vragen of in de documentatie te kijken.

In dit geval kun je het afleiden uit de configuratie van de webserver. Kijk op Github op de volgende locatie:

* [marketing.conf](../webserver/conf/marketing.conf) (webserver --> conf --> marketing.conf)

En zoek naar het onderstaande configuratie item:

```apacheconf
 Require ldap-group cn=Marketing,ou=roles,dc=NHLStenden,dc=com
```

Daar staat netjes dat het intranet alleen beschikbaar is voor de genoemde groep. 
