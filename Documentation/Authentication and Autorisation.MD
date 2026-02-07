# Authenticatie en autorisatie

Hier wordt kort uitgelegd hoe de gemaakte websites omgaan met Authenticatie (wie ben je) en Autorisatie (wat mag je).
Zie onderstaande diagram en de daar op volgende toelichting.

![Diagrammen - Authentication process.png](images/Diagrammen%20-%20Authentication%20process.png)

## 🟡 **Start**

De gebruiker navigeert naar een beveiligde website of pagina via een browser. Dit is het beginpunt van het proces.

---

## **Authenticatie**

**Doel:** Vaststellen **wie** de gebruiker is.

- De browser stuurt een verzoek naar de webserver.
- De webserver leidt dit verzoek door naar de website, waar authenticatie vereist is.
- De website controleert de identiteit van de gebruiker via een authenticatiemechanisme zoals **LDAP** (een centrale
  gebruikersdatabase).
- Als de authenticatie mislukt → gebruiker krijgt een **foutmelding**.
- Als de authenticatie slaagt → door naar autorisatie.

🟨 *Toelichting:*  
Authenticatie draait puur om **identiteit** — “Wie ben je?”

---

## **Website-autorisatie**

**Doel:** Controleren of de geauthenticeerde gebruiker **toegang mag hebben tot deze specifieke website**.

- De applicatie controleert of de gebruiker lid is van een bepaalde **LDAP-rol** die toegang geeft tot deze website.
- Geen toegang? → **Foutmelding**.
- Wel toegang? → Door naar paginacontrole.

🟨 *Toelichting:*  
Niet elke geauthenticeerde gebruiker mag elke applicatie gebruiken. Denk aan afgeschermde portals voor bepaalde
afdelingen.

---

## **Pagina-autorisatie**

**Doel:** Controleren of de gebruiker rechten heeft voor de **specifieke pagina of actie**.

- De gebruiker is nu bekend en mag deze website gebruiken.
- Nu wordt in de **applicatie-database** gecontroleerd of de gebruiker de juiste **permissies (rechten)** heeft om de
  gevraagde pagina of functie te gebruiken.
- Geen rechten? → **Foutmelding**.
- Wel rechten? → De gevraagde pagina wordt getoond.

🟨 *Toelichting:*  
Een gebruiker kan toegang hebben tot de site, maar niet tot alle onderdelen ervan. Hier wordt dat verder verfijnd.

---

## **Alles in orde**

Als alle controles (authenticatie, website-autorisatie én pagina-autorisatie) succesvol zijn, dan:

- Stuurt de webserver de inhoud van de gevraagde pagina terug naar de browser.
- De gebruiker ziet de pagina in de browser.

🟨 *Toelichting:*  
Pas nu weten we zeker dat de gebruiker **mag inloggen**, **mag werken met deze applicatie** én **deze specifieke pagina
mag gebruiken**.

---

## Samengevat

De flowchart beschrijft drie opeenvolgende beveiligingslagen:

1. **Authenticatie** (Wie ben je?)
2. **Website-autorisatie** (Mag jij deze applicatie gebruiken?)
3. **Pagina-autorisatie** (Mag jij dit specifieke onderdeel doen?)

Elk onderdeel fungeert als een beveiligingspoort. Faalt de gebruiker ergens, dan wordt de toegang geweigerd en volgt een
foutmelding.
