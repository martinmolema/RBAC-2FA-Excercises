# Configuratie Apache Directory Studio for LDAP

De _Identity Provider_ zorgt er voor dat we gebruikersaccounts op kunnen slaan, en ze kunnen gebruiken om in te loggen
bij bijvoorbeeld een website of computer.

Voor de oefeningen van deze repository gebruiken we LDAP: Lightweight Directory Access Protocol. De software staat al
geinstalleerd op de Docker Container. Het werken met deze software is echter nogal complex vanaf de command line.
Daarom gebruiken we liever een Desktop applicatie zoals de Apache Directory Studio. Hiermee maken we verbinding
met de LDAP-server in de Docker Container (*iam-example-identity-server*).

Installatie van de Apache Directory Studio doe je eenvoudig via deze
[pagina](https://directory.apache.org/studio/downloads.html). Een uitgebreide gebruikershandleiding
is [hier](https://directory.apache.org/studio/users-guide.html) beschikbaar.


## Problemen met Java & Apache Directory Studio

Mocht je tegen problemen aanlopen met de installatie van Java en het gebruik van Apache Directory Studio dan
is er alternatief. Er is een extra Docker container waarin een web gebaseerde LDAP-administratie website op
geinstalleerd is. Deze kun je vinden op [http://localhost:8080/](http://localhost:8080/).

De gebruikers interface is iets anders, maar lijkt in het gebruik sterk op de interface van Apache Directory
Studio. De login gegevens zijn identiek (zie bij de oefeningen)

---

# Meer informatie over LDAP

## Wat kun je met LDAP op Debian doen?

LDAP (Lightweight Directory Access Protocol) is ideaal voor:

- **Centrale gebruikersauthenticatie** (voor servers, applicaties, websites).
- **Opslaan van gebruikersgegevens** (naam, e-mail, wachtwoord, rollen).
- **Autorisatie** (rollen en groepen toewijzen).
- **Single Sign-On (SSO)**-achtige oplossingen in je netwerk.
- **Gebruikersbeheer integreren in websites** of backend-applicaties (bijv. via PHP, Python, Java).

---

## LDAP gebruiken met websites / webapps

Je kunt LDAP gebruiken als **authenticatiesysteem voor je website**:

- **PHP**: met `ldap_bind()`, `ldap_search()` etc.
- **Python**: via `ldap3` of `python-ldap`
- **Java**: via JNDI of Spring Security
- **CMS’en**: zoals WordPress, Drupal of Nextcloud kunnen via plugins LDAP-authenticatie gebruiken.
- **Webservers zoals Apache of Nginx**: kunnen LDAP authenticatie configureren via modules (zoals `mod_authnz_ldap` voor
  Apache).
