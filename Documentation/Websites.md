# Menu structuur van de websites

Deze menubalk wordt gebruikt om navigatie te bieden voor vier verschillende websites: **Cijferadministratie**, *
*Sharepoint | Intranet**, **Marketing**, **HRM** en het **Admin Panel**. Afhankelijk van de website waarvoor de menubalk
wordt weergegeven, worden verschillende navigatieopties getoond.

## Websites

1. **Cijferadministratie**

- **Cijfers**: Toont je eigen cijfers.
- **Mijn gegevens**: Toont je persoonlijke gegevens.
- **Nieuwe cijferlijst**: Maakt een nieuwe cijferlijst aan.
- **Bekijk student**: Toont details van een student.
- **Lijsten goedkeuren**: Keurt cijferlijsten goed.

2. **Sharepoint | Intranet**

- **Mijn gegevens**: Toont je persoonlijke gegevens.
- **Human Resource Management**: Toegang tot HRM.
- **Cijfers**: Link naar de cijferadministratie.
- **Marketing**: Link naar de marketingwebsite.
- **Admin Panel**: Link naar het admin panel.

3. **Marketing**

- **Nieuwe campagne**: Maakt een nieuwe marketingcampagne aan.
- **Bekijk campagne**: Toont details van een campagne.
- **Campagne goedkeuren**: Keurt marketingcampagnes goed.
- **Verwijder campagne**: Verwijdert een marketingcampagne.

4. **Admin Panel**

- **Apache Logfiles**: Toont Apache logbestanden.
- **Attestation - Gebruikers**: Inzage in gebruikers en hun gekoppelde rollen.
- **Attestation - Rollen**: Inzage in rollen en permissies.
- **Rollen**: Beheert rollen.
- **Autorisatie aanvragen**: Het kunnen uitvoeren van een autorisatie aanvraag voor een gebruiker.

5. Human Resources Management (HRM)

- **Inzage in medewerkers**: op basis van een lijst kan een gebruiker bekeken worden
- **Aanpassen van gegevens van medewerkers**: een gebruiker kan gewijzigd worden zodat persoonsgegevens, maar ook
  functie aangepast kan worden

6. User Provisioning

Er is nog een zesde Docker Container. Deze voert in de achtergrond processen uit voor User Provisioning. We komen daar
in de oefeningen verder op terug.

## Navigatie

De menubalk wordt dynamisch gegenereerd op basis van de website en de gebruikersrechten. Alleen de opties waarvoor de
gebruiker de juiste permissies heeft, worden getoond. De actieve route wordt gemarkeerd om de huidige pagina aan te
geven.

## Welkomstbericht

Bovenaan de menubalk wordt een welkomstbericht weergegeven met de naam van de gebruiker en een gebruikersafbeelding. Er
is ook een link om uit te loggen.

