# Welkom

Welkom bij de repository voor de oefeningen rondom het thema Identity & Access Management (IAM). In deze handleiding
wordt uitgelegd hoe je de Docker Containers aan de praat kunt krijgen. Onderaan deze handleiding vind je veel
verwijzingen naar websites (**Referenties**), een verantwoording van het tot stand komen van de gegevens en afbeeldingen
en tips hoe je wél een veilige website kunt (laten) bouwen.

**Disclaimer**

De code in dit voorbeeld vormt geen goed voorbeeld voor het opzetten van een veilige website! De focus ligt voornamelijk
op het kunnen spelen met autorisaties op basis van een Role Based Access (RBAC) model met gebruik van permissies.

---

# Doelgroep

Deze repository is gericht op studenten die zich bezighouden met ICT-vraagstukken waar Identity & Access Management
relevant is. Dit kunnen zijn:

* Software Engineers die applicaties of websites bouwen waar IAM noodzakelijk is
* Bedrijfskundige IT'ers die applicaties (her) ontwerpen waarbij IAM meegenomen moet worden
* Associate degree studenten die zich bezighouden met Informatie Management of Cybersecurity richtingen

---

# Doelstelling

Het doel van deze repository is om een omgeving aan te bieden waarmee geoefend kan worden met diverse
[onderwerpen](./README.md/#onderwerpen) die relevant zijn binnen het gedachtengoed van IAM. Er is een ruime set aan
oefeningen beschikbaar met uitgebreide uitwerkingen, zodat de student zelf aan de slag kan en uitkomsten van de
verkenning van deze onderwerpen kan verifiëren.

Hoewel er enkele onderwerpen uitgewerkt zijn (zoals [User Provisioning](./Assignments/User%20Provisioning.MD)), zijn
deze er vooral op gericht om de oefeningen te kunnen maken. Lesmateriaal dat een docent of begeleider wil inzetten om
aan te sluiten bij deze onderwerpen is bewust niet opgenomen: het is aan de docent/begeleider om zelf te bepalen hoe
de student voorbereid wordt (of niet) op deze onderwerpen.

Het geheel aan oefeningen is daarmee ook niet bedoeld als toetsmiddel, maar vooral om zelf te ervaren hoe bepaalde
mechanismen binnen IAM werken, zonder te kunnen programmeren.

Doordat de focus vooral ligt op het ondersteunen van deze mechanismen, is er nog weinig aandacht aan keiharde
websecurity.
Daardoor zouden websites ook gebruikt kunnen worden om een penetratie test op uit te voeren. Het is maar een idee...

---

# Onderwerpen

De volgende onderwerpen kunnen geoefend worden met de websites en andere componenten in deze repository

* Inloggen via een Identity Provider (LDAP)
* Beheren van rollen:
    * Aanmaken van nieuwe rollen
    * koppelen van een rol aan een gebruiker (autorisatie aanvraag)
    * koppelen van permissies aan rollen
* Segregation of Duties (SOD, Functiescheiding)
    * beheren van conflicten
    * testen van conflicten
    * beschermen van ontstaan van conflicten (rollen, permissies)
* Human Resources Management
    * aanmaken nieuwe medewerkers
    * wijzigen medewerkers
* User Provisioning van HRM naar LDAP
    * synchronisatie (aanmaken, bijwerken, uitschakelen)
    * uid regels + uniek houden
    * automatische rol toewijzing o.b.v. functie
* Attestation / Recertification
    * Users gekoppeld aan rollen
    * Rollen gekoppeld aan permissies
    * Filtering en exporteren naar `.csv` voor verwerking buiten deze repo/websites
* Audit trails voor inzicht in events
    * inlog pogingen
    * wijzigingen in rollen/permissies/gebruikers
    * user provisioning proces log

Voor Software Engineers is er een handleiding om zelf nieuwe permissies toe te voegen.

---

# Eisen aan de werkplek

De volgende eisen worden gesteld aan de werkplek (jouw computer / laptop):

1. Een Command Line Interface zoals Powershell, Xterm, cmd.exe voor het uitvoeren van Docker commando's
2. Een werkende Docker installatie die gebruik kan maken van `docker compose`.
3. Een werkende recente JAVA versie.
    1. Voor Windows: OpenJDK te downloaden via [adoptium.net](https://adoptium.net/)
    2. Debian Linux: `sudo apt install openjdk-21-jre`
    3. MacOS:te installeren via [adoptium.net](https://adoptium.net/)
4. Geïnstalleerde versie van [Apache Directory Studie](https://directory.apache.org/studio/). Zie ook de
   [handleiding](./Install/InstallApacheDirectoryStudio.md).
5. Een recente Browser zoals Google Chrome, Brave, Firefox, Chromium, Vivaldi of Microsoft Edge. Lees voor gebruik
   van Microsoft Edge goed de benodigde aanpassingen in de installatie handleiding van deze repository.

Let op:
> Om met Docker te kunnen werken moet je zorgen dat je Administrator rechten hebt op je werkplek. Installatie kan
> mogelijk
> zonder deze rechten maar het lukt daarna niet om Docker daadwerkelijk te kunnen gebruiken.
>
> Docker draait op een virtualisatie laag. Die hangt af van software en hardware.
> * Advies is om op Windows het **Windows Subsystem voor Linux** (WSL) te gebruiken.
> * Je laptop moet Virtualisatie toestaan. Dit is te regelen in de BIOS van je computer.
>
> Kijk voor meer informatie op
> de [installatie handleiding](https://docs.docker.com/desktop/setup/install/windows-install/) van Docker!

Eventueel handig om te hebben:

* **Docker Desktop** voor het managen van de containers (maar dit kan ook volledig vanaf de command line).
* Een **PDF lezer** voor het geval je gegevens exporteert naar PDF
* Een **tekst editor** die broncode begrijpt zoals [Notepad++](https://notepad-plus-plus.org/) voor het bekijken van
  broncode  (dit kan ook gewoon in Kladblok, Vim, Emacs, nano of online bij Github! )
* **Database management** software om mee te kunnen kijken in de databases, zoals [dbeaver](https://dbeaver.io/)
  of [MySQL Workbench](https://www.mysql.com/products/workbench/)
* **Spreadsheet** software voor het importeren van sommige exportbestanden (in .csv-formaat) zoals Excel of
  [LibreOffice Calc](https://nl.libreoffice.org/ontdek/calc/).
* **Markdown** lezer. Alle documenten en beschrijvingen/uitwerkingen van de oefeningen zijn geschreven in Markdown. Als
  je deze bestanden op GitHub leest, dan is dat geen probleem. Wil je bestanden lokaal wilt kunnen bekijken in hun
  opgemaakte versie dan is er een andere [GitHub Repository](https://github.com/mundimark/awesome-markdown-editors) waar
  je kunt kiezen uit veel verschillende software die bij jou/jouw werkplek past. Eventueel kun je ook een complete IDE
  installeren om het project in zijn geheel beter te bekijken. Denk dan
  aan [Visual Studio Code](https://code.visualstudio.com/) of  [PHP Storm](https://www.jetbrains.com/phpstorm/).
  Eventueel kun je ook gewoon [Notepad++](https://notepad-plus-plus.org/)  installeren met
  de [Markdown Viewer ++](https://github.com/nea/MarkdownViewerPlusPlus)

## Problemen met Java & Apache Directory Studio

Mocht je tegen problemen aanlopen met de installatie van Java en het gebruik van Apache Directory Studio dan
is er alternatief. Er is een extra Docker container waarin een web gebaseerde LDAP-administratie website op
geinstalleerd is. Deze kun je vinden op [http://localhost:8080/](http://localhost:8080/).

De gebruikers interface is iets anders, maar lijkt in het gebruik sterk op de interface van Apache Directory
Studio. De login gegevens zijn identiek (zie bij de oefeningen en eventueel
de [installatie handleiding](./Install/README.md#account-gegevens-invoeren)).

Meer informatie over het gebruik kun je vinden op [phpLdapAdmin](https://github.com/leenooks/phpLDAPadmin). 

---

# Installatie

Een uitgebreide installatiehandleiding kun je [hier](./Install/README.md) vinden. Deze zorgt er voor dat je
daadwerkelijk aan de slag
kunt.

# Oefeningen

In de map [Assignments](./Assignments) vind je de bestanden om mee te oefenen en de uitwerkingen. Zie hiervoor de
[readme](./Assignments/README.MD). Eventueel verdiepende documentatie om de oefeningen uit te kunnen voeren is opgenomen
in de oefeningen
of er wordt verwezen naar andere bronnen, soms aanwezig in deze repository zelf.

---

# Verantwoording

Een toelichting op de gemaakte keuzes kun je vinden in de [verantwoording](./Documentation/Verantwoording.md).

---

# Authenticatie en Autorisatie

Elke website maakt gebruik van beveiliging. De gebruikte authenticatie en autorisatie flow
wordt [hier](./Documentation/Authentication%20and%20Autorisation.MD) beschreven. De vastlegging van de rollen en
permissies wordt [hier](./Documentation/Rollen-en-permissies.md) verder uitgelegd.

# De websites

Er is een [toelichting](./Documentation/Websites.md) beschikbaar over de werking van de websites.

---

# Automatische tests met Playwright

Er wordt gewerkt aan automatische tests. Deze worden vormgegeven met [Playwright](https://playwright.dev/). Deze zijn
te vinden in de map [tests](./tests/playwright).

Om deze tests te draaien moet in die map `npm install` gedraaid worden na installatie van Playwright en de headless
browsers (zie installatiehandleiding van [Playwright](https://playwright.dev/docs/intro)).

Daarna kunnen de tests gedraaid worden vanuit de map [tests](./tests/playwright):

```bash
npx playwright test --config=playwright.config.ts
npx playwright show-report
```

# Ambitie

Natuurlijk zijn er nog meer onderwerpen te implementeren. Deze staan voorlopig nog op de lijst met 'ambities':

* Twee factor authenticatie via TOTP met een Authenticator app, voor bijvoorbeeld de Admin Portal
* Verdere beveiliging van user input (sanitation).
* (Meer) automatische tests met Playwright: het automatisch testen van de oefeningen

# Colofon

Martin Molema, ing MSc

Docent bij NHL Stenden, opleidingen Bachelor HBO-ICT en Associate Degree Cyber Safety & Security.

[martin.molema@nhlstenden.com](mailto:martin.molema@nhlstenden.com)

Mei 2025