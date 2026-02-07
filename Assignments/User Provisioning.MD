# User Provisioning

**User Provisioning** is het proces waarbij gebruikersaccounts en toegangsrechten automatisch of handmatig worden
toegekend, aangepast en verwijderd binnen IT-systemen. Het is een essentieel onderdeel van Identity & Access
Management (IAM), omdat het zorgt voor controle, efficiëntie en veiligheid binnen een organisatie.

Zie ook het boek "Identity & Access Management" is geschreven door Rob van der Staaij

## Wat is User Provisioning?

User Provisioning omvat alle activiteiten rondom het aanmaken, beheren en verwijderen van gebruikersaccounts. Dit proces
wordt vaak geautomatiseerd via provisioning-tools die gekoppeld zijn aan systemen zoals Active Directory, HRM-systemen
of andere applicaties.

## Doelen van User Provisioning

- **Efficiëntie**: Nieuwe medewerkers krijgen snel toegang tot benodigde systemen.
- **Beveiliging**: Alleen bevoegde personen krijgen toegang, en oude accounts worden tijdig gedeactiveerd.
- **Naleving**: Voldoen aan wet- en regelgeving (bijvoorbeeld AVG/GDPR).
- **Consistentie**: Toegangsrechten zijn gebaseerd op functieprofielen of rollen (role-based access control, RBAC).

Het systeem van RBAC en User Provisioning zijn in hoge mate afhankelijk van de noodzaak die past bij hoe een bedrijf
haar werk doet. Zo zal er in kleine organisaties met weinig verloop/verandering in personeel minder noodzaak zijn om 
het User Provisioning proces te automatiseren. Het is echter zeer onverstandig om geen zicht te hebben op hoe en waarom
gebruikers toegang krijgen tot digitale informatie(-systemen). 

Zo kan er ook onderscheid gemaakt worden tussen de organisatorische kant als de technische uitvoering van maatregelen. 
Er kan een procedure opgesteld worden samen met Personeelszaken over hoe bij instroom of doorstroom aanpassingen nodig
zijn in de toegang tot systemen. De technische uitvoering hiervan kan verspreid zijn over meerdere ICT-afdelingen of 
-partners. Denk daarbij aan SaaS providers (Microsoft 365, Salesforce) als ook aan functioneel beheerders van verschillende
_On Premises_ applicaties die autorisaties kunnen beheren. 

## Belangrijke onderdelen van User Provisioning

- **Onboarding**: Bij binnenkomst van een medewerker wordt automatisch een account aangemaakt met standaardrechten
  passend bij diens functie of afdeling.
- **Wijzigingenbeheer**: Wanneer iemand van functie verandert, worden de rechten aangepast (re-provisioning).
- **Offboarding**: Bij vertrek van een medewerker wordt het account gedeactiveerd of verwijderd.

Dit wordt soms ook wel "From hire to retire" genoemd: van inhuren tot ontslag / pensioen.

- **Self-service portals**: Gebruikers kunnen in sommige gevallen zelf bepaalde aanvragen doen voor toegang tot
  systemen, vaak onder toezicht of na goedkeuring.
- **Workflowmanagement**: Provisioning kan gekoppeld zijn aan goedkeuringsprocessen via workflows.

## Automatisering

Provisioning is steeds vaker geautomatiseerd. Een HR-systeem kan bijvoorbeeld fungeren als 'bron van waarheid'. Wanneer
daar een nieuwe medewerker wordt geregistreerd, triggert dat automatisch het aanmaken van accounts in andere systemen.
Dit vermindert handmatig werk én het risico op fouten.

## Uitdagingen

- **Datakwaliteit**: Slechte of incomplete data in bronsystemen leidt tot verkeerde provisioning.
- **Shadow IT**: Niet alle applicaties zijn aangesloten op provisioning-systemen.
- **Toegangscreep**: Medewerkers behouden rechten die ze niet meer nodig hebben.
- **Beveiliging en auditing**: Het is cruciaal om provisioningacties te loggen en te kunnen auditen.

## Best Practices

- Gebruik een **centrale identity store**.
- Implementeer **rolgebaseerde toegangsmodellen (RBAC)**.
- Zorg voor duidelijke **onboarding- en offboardingprocessen**.
- Automatiseer waar mogelijk, maar houd toezicht via **goedkeuringsworkflows**.
- Voer regelmatig **access reviews** en **recertificatie** uit.

Dit laatste wordt ook wel Attestation genoemd: het opnieuw vaststellen of de huidige situatie in de echte wereld nog
wel overeenkomt met de situatie in de systemen die het IAM-proces besturen. Veel kan geautomatiseerd worden op basis
van Role Based Access Control (RBAC) en informatie uit het HRM-systeem. Echter, er zullen ook extra autorisaties
aangevraagd zijn voor tijdelijke situaties,

# RBAC en User Provisioning

**Role-Based Access Control (RBAC)** is een methode om toegangsrechten toe te kennen op basis van de rol(len) die een
gebruiker vervult binnen een organisatie. In combinatie met **User Provisioning** zorgt RBAC voor een efficiënte en
veilige manier om toegang tot IT-systemen te beheren.

### Centrale rol van RBAC

In een goed ingerichte IAM-architectuur wordt RBAC **centraal beheerd**, vaak in combinatie met een HRM-systeem. De
HRM-administratie fungeert dan als ‘bron van waarheid’ waarin wordt vastgelegd welke medewerker welke functie of rol
vervult. Deze rol wordt vervolgens automatisch vertaald naar IT-rechten via User Provisioning.

Bijvoorbeeld:

- Een nieuwe medewerker met de functie *Financieel Medewerker* krijgt automatisch de bijbehorende rol.
- Die rol bepaalt vervolgens op systeemniveau welke applicaties en rechten worden toegekend.

### Koppeling met permissies per applicatie

Hoewel rollen centraal worden toegekend, is het belangrijk dat **de vertaling van rol naar permissies** binnen elke
applicatie plaatsvindt. Dit betekent dat:

- De provisioning-tool een gebruiker toewijst aan een rol.
- De applicatiebeheerder binnen die specifieke applicatie definieert wat die rol precies mag (bijv. lezen, schrijven,
  verwijderen).

Op deze manier wordt het mogelijk om:

- **Grip te houden op wie wat mag binnen elke applicatie.**
- **Flexibel om te gaan met verschillen tussen applicaties.**
- **Beveiligingsrisico’s te beperken** doordat rechten niet te ruim worden toegekend.

### Voordelen van deze aanpak

- **Schaalbaarheid**: Eén wijziging in de centrale rolconfiguratie werkt door in alle systemen.
- **Automatisering**: User Provisioning werkt op basis van rollen, waardoor nieuwe medewerkers automatisch de juiste
  rechten krijgen.
- **Beheersbaarheid**: Elke applicatie kan zelf bepalen wat een rol precies mag, wat maatwerk mogelijk maakt.
- **Compliance**: Duidelijke rolverdeling helpt bij audits en toegangscontroles.

### Samenvattend

RBAC en User Provisioning vormen samen een krachtige combinatie om toegangsbeheer efficiënt, veilig en controleerbaar te
maken. Door rollen centraal te beheren én per applicatie goed te koppelen aan permissies, ontstaat een helder en
betrouwbaar model van ‘wie mag wat’ binnen de organisatie.

## Verticale en Horizontale Autorisaties in Applicaties

### In relatie tot RBAC en User Provisioning

Binnen applicaties wordt autorisatie vaak onderverdeeld in **verticale** en **horizontale** autorisaties. Deze vormen
bepalen *wat* een gebruiker mag doen en *waarop* hij of zij dat mag doen. In combinatie met **Role-Based Access
Control (RBAC)** en **User Provisioning** zorgt dit voor een fijnmazige en goed beheersbare toegangsstructuur.

---

### Verticale autorisatie: *Wat mag een gebruiker doen?*

Verticale autorisatie gaat over **acties en functies** binnen een applicatie, zoals:

- Lezen
- Toevoegen
- Wijzigen
- Verwijderen
- Goedkeuren

In RBAC-termen zijn deze vaak direct gekoppeld aan **rollen**. Bijvoorbeeld:

- Een rol *Financieel Medewerker* mag boekingen invoeren (aanmaken).
- Een rol *Controller* mag ook boekingen goedkeuren (goedkeuren).

Bij **User Provisioning** worden gebruikers op basis van hun functie automatisch gekoppeld aan een rol, en krijgen ze zo
toegang tot deze verticale bevoegdheden in de applicatie.

---

### Horizontale autorisatie: *Op welk object of welke eenheid mag iemand die actie uitvoeren?*

Horizontale autorisatie beperkt **het bereik van de actie**, bijvoorbeeld:

- Alleen toegang tot de eigen afdeling of regio
- Alleen dossiers van eigen cliënten
- Alleen projecten waaraan iemand is toegewezen

Deze autorisatie wordt vaak **binnen de applicatie zelf ingericht**, op basis van aanvullende gebruikerskenmerken zoals:

- Afdelings-ID
- Locatie
- Project-ID’s

Horizontale autorisatie is meestal **niet direct via RBAC af te dwingen**, maar **werkt aanvullend** op de rol. De
provisioning-proces kan wel helpen door deze contextinformatie (zoals afdelingsnummer) mee te geven aan de applicatie,
zodat die de juiste grenzen kan trekken.

### Best practices

- **Beheer rollen centraal**, maar laat applicaties bepalen wat die rollen mogen doen (verticale autorisatie).
- **Geef context mee** (zoals afdelingscodes) tijdens provisioning zodat applicaties horizontale beperkingen kunnen
  toepassen.
- **Test regelmatig** of de combinatie van rol en context leidt tot de juiste toegangsrechten — niet te veel, niet te
  weinig.

Samengevat ziet dit er als volgt uit:

![User Provisioning - Infographic.png](./images/User%20Provisioning%20-%20Infographic.png)

## Relatie met de opdrachten

Kijk nog een terug naar de opdrachten en de websites. Een voorbeeld van horizontale autorisaties is dat je alleen je eigen 
gebruikersinformatie kan bekijken als student. Je mag als stafmedewerker of docent alleen je eigen HRM-dossier en autorisaties
bekijken. 

--- 
## Een voorbeeld uit de praktijk

Het geval van de bekende Nederlandse realityster **Barbie (Samantha de Jong)** is een bekend voorbeeld binnen de
zorg en informatiebeveiliging. In 2017 kwam naar buiten dat **meerdere medewerkers van het HagaZiekenhuis onrechtmatig
haar medische dossier hadden ingezien**, zonder dat zij een behandelrelatie met haar hadden. Dit leidde tot landelijke
ophef en boetes voor het ziekenhuis vanuit de Autoriteit Persoonsgegevens.

### Koppeling met Horizontale Autorisaties en Tijdigheid

Je kunt dit incident heel goed gebruiken om de noodzaak van **horizontale autorisaties** en **tijdige toegangsverlening
** te illustreren:

---

### Horizontale Autorisaties

Horizontale autorisatie bepaalt **op welk patiëntendossier** een medewerker toegang mag hebben. In dit geval hadden
medewerkers misschien wel de juiste *verticale* rechten (zoals het recht om dossiers in te zien), maar ontbraken *
*horizontale beperkingen**.

*Wat ging er mis?*

- Er was geen beperking op *welk* dossier een medewerker mocht bekijken.
- Iedereen met leesrechten kon in theorie elk dossier openen, ook dat van een BN’er zoals Barbie.
- Er werd niet gecontroleerd op een *behandelrelatie*, wat normaal een vereiste is.

*Wat had beter gekund?*

- Dossiers alleen toegankelijk maken voor het behandelteam (op basis van patiënt-ID of geplande zorgactiviteit).
- Logging en monitoring van toegang, gecombineerd met steekproeven en automatische signalering bij opvallende toegang.

---

### Tijdigheid van Toegang

Een andere les uit dit incident is het belang van **tijdelijke en situationele toegang**:

- Toegang tot patiëntgegevens zou **alleen actief moeten zijn zolang de zorgrelatie bestaat**.
- Voor acute situaties kan tijdelijke, gemotiveerde toegang ("break the glass") worden toegestaan, maar dat moet wel
  goed worden gelogd en achteraf beoordeeld.

*Voorbeeld van goede praktijk:*

- Een verpleegkundige op de IC heeft alleen toegang tot dossiers van patiënten op haar afdeling en tijdens haar dienst.
- Een arts van een andere afdeling moet expliciet toestemming vragen (via workflow) als hij tijdelijk toegang nodig
  heeft tot een specifiek dossier.

---

### Welke lessen kunnen we hier uit leren?

- Het verschil tussen **technisch kunnen** en **juridisch/moreel mogen**.
- Waarom **horizontale autorisaties** cruciaal zijn in sectoren met gevoelige informatie zoals de zorg.
- Hoe **user provisioning** en **rolbeheer** gekoppeld moeten zijn aan **contextuele factoren** zoals afdeling,
  behandelrelatie en diensttijden.

---

### Samenhang met RBAC en User Provisioning

| Onderdeel                   | Wordt geregeld via             | Voorbeeld                                        |
|-----------------------------|--------------------------------|--------------------------------------------------|
| **Roltoewijzing**           | RBAC + Provisioning            | "Financieel Medewerker" op basis van HR-gegevens |
| **Verticale autorisatie**   | In applicatie via rolkoppeling | Mogen boekingen invoeren of goedkeuren           |
| **Horizontale autorisatie** | In applicatie via filters      | Alleen toegang tot dossiers van eigen team       |


# Gebruik van User Provisioning en RBAC in de oefencasus 

Hier wordt meer informatie gegeven over hoe het User Provisionig proces geautomatiseerd is in ons voorbeeld van de 
fictieve hogeschool. 

Het User Provisioning proces is vormgegeven in een Python script dat elke 3 minuten de informatie uit het HRM-systeem
synchroniseert met de accounts en rollen in de LDAP-store. 

**Let op**: alle regels over hoe besluiten moeten worden genomen over de vertaling van informatie uit het HRM-systeem
naar informatie in de LDAP-store zijn gecodeerd in het Python-script. Normaal gesproken zou je deze besluiten 
configureerbaar maken. Denk aan het bepalen van de plaats in de LDAP-store waar een bepaald type account aangemaakt 
moet worden. 

Het proces ziet er als volgt uit. 

![User Provisioning - Processing.png](./images/User%20Provisioning%20-%20Processing.png)

##  Initieel laden van gegevens

In ons voorbeeld gaat het om het overbrengen van medewerkersinformatie naar de LDAP-administratie.

Na het bouwen van de verschillende containers moet er éénmalig een script gedraaid worden. Dit script zal de volgende
informatie éénmalig in de LDAP administratie zetten:

* Elke gebruiker krijgt een avatar-afbeelding (_jpegPhoto_)
* Er wordt gekeken naar de _Distinguished Name_ om te bepalen wat het _EmployeeType_ zal worden. (Zie de tabel verderop)
* Er wordt een willekeurige postcode gegeneerd (_Postalcode_)
* Er wordt een willekeurige telefoonnummer gegeneerd (_telephoneNumber_)
* Er wordt een willekeurig kamernummer gegeneerd (_Roomnumber_)
* Er wordt een willekeurig personeelsnummer gegenereerd (_EmployeeNumber_)
* De _organizationName_ krijgt altijd de waarde "NHL Stenden"

Let op: het attribuut 'organizationName' is meestal een alias voor het attribuut 'o'.

## Aanmaken van de lijst met medewerkers

De LDAP-store heeft lange tijd gediend als primair plek voor het aanmaken van de gebruikers. Er was eerder geen
HRM-systeem
waarmee User Provisioning gedaan kon worden. Daarom is er gekozen om bij het initieel opzetten van het ecosysteem van
Docker-containers en systemen om vanuit de LDAP-administratie éénmalig de lijst met medewerkers op te bouwen, in plaats
van andersom.

| Medewerker Attribuut | LDAP-attribuut   | Regels                                          |
|----------------------|------------------|-------------------------------------------------|
| personeelsnummer     | employeeNumber   |                                                 |
| voornaam             | givenName        |                                                 |
| achternaam           | sn               |                                                 |
| team                 | organizationName |                                                 |
| functie              | DN               | Op basis van de DN wordt de functie vastgesteld |
| telefoonnummer       | telephoneNumber  |                                                 |
| kamernummer          | roomNumber       |                                                 |
| medewerkerType       | employeeType     |                                                 |
| postcode             | postalCode       |                                                 |

## Mapping

Bij het overzetten wordt gekeken naar informatie over de medewerker om te bepalen hoe deze in de LDAP-administratie
aangemaakt of gewijzigd moet worden.

De volgende vertaaltabel wordt gebruikt.

| Medewerker Attribuut | LDAP-attribuut    | Regels                             |
|----------------------|-------------------|------------------------------------|
| idMedewerker         | <niet gebruikt>   |                                    |
| personeelsnummer     | employeeNumber    |                                    |
| voornaam             | givenName         |                                    |
| achternaam           | sn                |                                    |
| team                 | organizationName  |                                    |
| functie              |                   | zie verderop bij rollen en locatie |
| telefoonnummer       | telephoneNumber   |                                    |
| kamernummer          | roomNumber        |                                    |
| medewerkerType       | employeeType      |                                    |
| postcode             | postalCode        |                                    |
| last_sync            | \<niet gebruikt\> |                                    |

De volgende berekende attributen worden gebruikt:

| LDAP-attribuut | Berekening                                                 | 
|----------------|------------------------------------------------------------|
| userPassword   | Altijd "Test1234!"                                         |
| cn             | {Voornaam} + " " + {Achternaam }                           |
| uid            | first({voornaam}) + {Achternaam}                           |
| objectClass    | ["inetOrgPerson", "organizationalPerson", "person", "top"] | 

De plaats waar een gebruiker aangemaakt moet worden wordt bepaald op basis van de functie van de medewerker.
Onderstaande tabel laat deze locaties zien.

| Functie              | Locatie (DN)                                    | 
|----------------------|-------------------------------------------------|
| docent               | ou=Teachers,ou=Opleidingen,dc=NHLStenden,dc=com |
| medewerker ICT       | ou=ICT Support,ou=Staff,dc=NHLStenden,dc=com    |
| medewerker marketing | ou=Marketing,ou=Staff,dc=NHLStenden,dc=com      | 
| medewerker HRM       | ou=HRM,ou=Staff,dc=NHLStenden,dc=com            |

Afhankelijk van de functie worden ook nog extra rollen toegewezen in de LDAP. Dit betekent een lidmaatschap
van de genoemde groepen via het attribuut "UniqueMember" in een object van klasse "GroupOfUniqueNames".

| Functie              | Rollen (DN)                                          | 
|----------------------|------------------------------------------------------|
| docent               | cn=All Personell,ou=roles,dc=NHLStenden,dc=com       |       
| docent               | cn=All Teachers,ou=roles,dc=NHLStenden,dc=com        |        
| docent               | cn=SharePoint Teachers,ou=roles,dc=NHLStenden,dc=com | 
| docent               | cn=Grades Teachers,ou=roles,dc=NHLStenden,dc=com     |      
| medewerker ICT       | cn=All Personell,ou=roles,dc=NHLStenden,dc=com       |       
| medewerker ICT       | cn=ICT Support,ou=roles,dc=NHLStenden,dc=com         |          
| medewerker marketing | cn=All Personell,ou=roles,dc=NHLStenden,dc=com       |
| medewerker marketing | cn=Marketing,ou=roles,dc=NHLStenden,dc=com           |            
| medewerker HRM       | cn=All Personell,ou=roles,dc=NHLStenden,dc=com       |       
| medewerker HRM       | cn=HRM,ou=roles,dc=NHLStenden,dc=com                 |                 

Als een medewerker al voorkomt, dan zal de volgende informatie bijgewerkt worden:

* givenName
* sn
* telephoneNumber
* roomNumber
* postalCode
* employeeType

Daarnaast worden de standaard rollen hersteld.

## Gebruikers die niet meer bestaan

Als een gebruiker niet langer in de HRM-database aanwezig is, dan zal het wachtwoord leeg gemaakt worden zodat de
gebruiker niet meer kan inloggen.

# Bronvermeldingen en verwijzingen

* "Identity & Access Management, een introductie", Rob van der Staaij Nederlands, Paperback 9789090335834, 08 januari
  2021, 472 pagina's

* **Persbericht Autoriteit Persoonsgegevens (AP) – boete voor HagaZiekenhuis**
  > In juli 2019 legde de AP een **boete van €460.000** op aan het HagaZiekenhuis vanwege onvoldoende beveiliging van
  medische dossiers, naar aanleiding van het onrechtmatig inzien van het dossier van Samantha de Jong.  
  [Bron: Autoriteit Persoonsgegevens – 18 juli 2019](https://autoriteitpersoonsgegevens.nl/nl/nieuws/ap-legde-hagaziekenhuis-eerste-boete-op-voor-gebrekkige-dossierbeveiliging)

* **Nieuwsbericht NOS: “Te veel medewerkers bekeken dossier Barbie”**
  > In 2018 kwam aan het licht dat tientallen medewerkers zonder behandelrelatie inzage hadden in haar dossier, wat
  leidde tot landelijke media-aandacht en intern onderzoek.  
  [Bron: NOS – 6 juli 2018](https://nos.nl/artikel/2240599-te-veel-medewerkers-hagaziekenhuis-bekeken-medisch-dossier-barbie)

* **Wetgeving: Algemene Verordening Gegevensbescherming (AVG)**
  > Onder de AVG geldt het principe van *gegevensminimalisatie* en *doelbinding*. Alleen medewerkers met een functionele
  noodzaak mogen toegang hebben tot persoonsgegevens.  
  [Bron: AVG-artikelen 5 & 32 – EUR-Lex](https://eur-lex.europa.eu/legal-content/NL/TXT/?uri=CELEX%3A32016R0679)

* **Normen in de zorg: NEN 7510 – Informatiebeveiliging in de zorg**
  > Deze Nederlandse norm schrijft o.a. voor dat zorginstellingen toegangsrechten moeten beperken tot het minimum dat
  noodzakelijk is voor de uitvoering van taken.  
  [Bron: NEN 7510](https://www.nen.nl/nen-7510-1-2017-nl-259087)

* **Artikel Zorgvisie – "Barbie-incident leidde tot boete HagaZiekenhuis"**
  > Zorgvisie beschrijft het incident in de context van informatiebeveiliging en wat het betekent voor ziekenhuizen in
  Nederland.  
  [Bron: Zorgvisie – 18 juli 2019](https://www.zorgvisie.nl/ap-haga-ziekenhuis-kreeg-eerste-boete-voor-privacy-schending/)

---
