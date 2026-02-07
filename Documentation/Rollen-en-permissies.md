
# Uitleg over technische vastlegging van rollen en permissies

- Rollen worden beheerd in een LDAP-server als `groupOfUniqueNames`.
- Elke rol in LDAP heeft een corresponderende entry in de `roles` tabel via het veld `distinguishedName`.
- Dit zorgt ervoor dat de rollen consistent zijn tussen de LDAP-server en de database.

## Praktische uitleg

- **Rollenbeheer**: Rollen worden centraal beheerd in de LDAP-server. Elke rol in de LDAP-server heeft een unieke naam (
  distinguishedName) die overeenkomt met een record in de `roles` tabel van de database.
- **Permissiebeheer**: Permissies worden beheerd in de database. Elke permissie heeft een unieke code en beschrijving.
  Alleen een programmeur kan nieuwe permissies voorstellen als er nieuwe functionaliteiten geimplementeerd worden.
- **Koppeling van rollen en permissies**: De tabel `role_permissions` koppelt rollen aan permissies. Dit betekent dat je
  kunt specificeren welke permissies aan welke rollen zijn toegewezen.
- **Gebruik van de website**: Studenten kunnen via een website permissies aan rollen koppelen. Dit stelt hen in staat om
  de toegangsrechten te beheren zonder nieuwe permissies te hoeven aanmaken.

## Plaats van accounts

Accounts worden op een specifieke plaats opgeslagen in de LDAP-store. Hieronder volgen een aantal voorbeelden.

| Type account          | Distinguished Name LDAP                         | 
|-----------------------|-------------------------------------------------|
| Docenten              | ou=Teachers,ou=Opleidingen,dc=NHLStenden,dc=com |
| Studenten             | ou=Students,ou=Opleidingen,dc=NHLStenden,dc=com |
| Medewerkers ICT       | ou=ICT Support,ou=Staff,dc=NHLStenden,dc=com    |
| Medewerkers Marketing | ou=Marketing,ou=Staff,dc=NHLStenden,dc=com      |
| Medewerkers HRM       | ou=HRM,ou=Staff,dc=NHLStenden,dc=com            |
