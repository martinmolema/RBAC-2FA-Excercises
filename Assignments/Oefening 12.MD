# Oefening 12 - Instellen van Functiescheiding

## Introductie

Zoals uit [Oefening 5](./Oefening%2005.MD) al bleek is het mogelijk om onbedoelde autorisaties uit te delen. Als je niet
genoeg kennis of middelen hebt om te voorkomen dat je als beheerder een rol maakt met een niet-toegestane combinatie
van permissies, dan kun je de organisatie in de problemen brengen.

# Opdracht

## Deel 1 : functiescheidingsinformatie toevoegen

We gaan nu een functie scheiding controle invoeren in ons model. We doen dit door twee permissies in een controletabel
op te nemen. Deze behoren (in ons voorbeeld) bij één applicatie. Ze mogen dus nooit bij één gebruiker tegelijk aanwezig
zijn.

In dit geval gaan we voor de website van Marketing een beperking opleggen dat je niet een gemaakte campagne ook mag
goedkeuren. Dan kun je namelijk buiten het oog van het management bijvoorbeeld een vreemde campagne starten.

1. Log in als gebruiker van ICT Support met rechten "Autorisation manager"
   (`cn=Authorisation Manager,ou=roles,dc=NHLStenden,dc=com`)
2. Navigeer naar "Functiescheiding" via de menubalk. Je krijgt een leeg overzicht
3. Klik op de button 'Nieuw'. Je navigeert naar het invoerscherm
4. Eerst moeten we de applicatie waar de beperking over gaat kiezen. Kies voor `Marketing`. Het scherm ververst en je
   krijgt twee lijsten te zien met permissies.
5. Kies uit de eerste lijst de permissie 'Approve a marketing campaign'
6. Kies uit de tweede lijst de permissie 'Create a new marketing campaign'.
7. Voer daarna in het veld 'Beschrijving' een korte beschrijving in (minimaal 10 karakters). Bijvoorbeeld
   'Niet aanmaken en goedkeuren campagne'.
8. Druk daarna op `Opslaan`  om de gegevens vast te leggen.
9. Je keert terug naar de lijst en daar zou deze beperking nu zichtbaar moeten zijn.

![Opdracht 12-01.png](images/Opdracht%2012-01.png)

## Deel 2: testen van de beveiliging op functiescheidingen: rollen en permissies

We gaan nu proberen of deze beperking ook zijn werk doet: we proberen in één rol deze permissies te combineren.

10. Navigeer naar `Rollen` via het menu en zoek de rol *'Marketing medewerker'*.
11. Klik op `Edit` om de rol te bewerken. Je navigeert naar de pagina om een rol te bewerken.
12. Kies de Permissie `Approve a marketing campaing`.
13. Druk op de knop `Toevoegen`.

Er volgt nu een foutmelding.

```text
Deze permissie mag niet samen in met een andere permissie in deze rol volgens functiescheiding!
Niet aanmaken en goedkeuren campagne => Create a new marketing campaign ↔ Approve a marketing campaign
```

Je kunt de vorige stappen ook benaderen vanuit de *Marketing Managers*  en proberen om daar de rol 'Create new campaign'
aan toe te voegen. Ook dat zal niet lukken.

# Deel 3: testen van de beveiliging op functiescheidingen: rollen en gebruikers

Nu blijkt dat we tegen deze fout beschermd zijn is er nog de kans dat we een gebruiker zoveel autorisaties geven
dat deze uiteindelijk toch via een omweg een combinatie krijgt van deze twee permissies. We kunnen dat testen met de
volgende stappen.

14. Ga naar de pagina `Autorisatie aanvraag`.
15. Kies de rol `Marketing managers`.
16. Er worden nu drie gebruikers getoond die deze rol al hebben. Deze gebruikers kun je nu ook niet kiezen uit de lijst
    met gebruikers.
17. Kies een gebruiker uit de lijst "Marketing" die *niet* disabled is (bijvoorbeeld "Blok, Mees").
18. Klik op de button `Autoriseer`.

Als het goed is zal de autorisatie procedure controleren of deze gebruiker niet per ongeluk permissies krijgt die
conflicterend zijn. Dit blijkt ook te gebeuren want we krijgen onderstaande foutmelding.

```text
Deze gebruiker kan deze rol niet krijgen vanwege conflicterende autorisaties: niet aanmaken en goedkeuren 
   => Create a new marketing campaign - versus - Approve a marketing campaign
```

Keer handmatig terug naar de vorige pagina en ga verder met het volgende onderdeel hier onder.

## Deel 4: Functiescheiding met bestaande conflicten controleren

Het kan voorkomen dat je een functiescheiding toevoegt waarbij gebruikers de conflicterende permissies al blijken te 
hebben. Dit gaat als volgt:

19. Verwijder nu de beperking via `Functiescheiding` en de knop `Delete`.
20. Herhaal de stappen 10 t/m 13. _Dit lukt nu wel!_
21. Herhaal nu de stappen 2 t/m 9.

We zitten nu met de situatie dat er dus een rol is waar twee permissies in zitten die niet toegestaan zijn. We kunnen
nu controleren of er daadwerkelijk gebruikers zijn die deze niet toegestane combinatie ook daadwerkelijk heeft. Dit kan
via een attestation overzicht, maar dat is erg veel werk omdat je veel zaken moet combineren. Daarom is er voor deze
oefening een procedure die dat voor jou kan doen.

22. In het scherm voor `Functiescheiding` zit ook een knop `Controleer gebruikers`.
23. Druk op deze knop. Dit is een intensieve controle en het resultaat kan even duren.
24. Als het goed is zie je dat alle medewerkers van Marketing in de probleemhoek zitten: zij kunnen nu een campagne
    aanmaken en zelf ook goedkeuren!

# Navigatie

Navigeer hier naar de vorige [opdracht](./Oefening%2011.MD).

[Uitwerking](./Oplossingen-12.MD)
