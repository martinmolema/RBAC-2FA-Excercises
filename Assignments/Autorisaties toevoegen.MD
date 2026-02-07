# Uitbreiden van autorisaties

Het is natuurlijk mogelijk om het huidige autorisatie model aan te passen. In deze handleiding wordt besproken
hoe je eventueel zelf autorisaties kunt toevoegen.

## Huidige situatie

Het idee achter het werken met _Role Based Access Control_ en _Permissies_  is dat er pas permissies nodig zijn

1. je nieuwe functionaliteiten bouwt
2. je bestaande functionaliteiten nauwkeuriger wilt autoriseren

Zo zit er nu in het Admin-panel een aantal functies die controleren 'of je admin bent' of 'dat je rollen mag beheren'.
Er wordt bijvoorbeeld niet gecontroleerd bij het verwijderen van een permissie uit een rol of je wel een permissie
`verwijder permissie van rol` hebt, maar er wordt gecontroleerd op de permissie
`Permission_AdminPanel_Manage_RolePermissions`. Er worden dus een aantal functionaliteiten gegroepeerd rondom één
permissie. Als we kijk in de broncode ({ROOT}/volumes/src/websites/admin/intranet) dan zien we dat de volgende bestanden
/ functies leunen op de permissie `Permission_AdminPanel_Manage_RolePermissions`:

* manage_roles.php : de webpagina waarin je de rollen kunt kiezen
* add-permission-to-role.php: een PHP-script dat daadwerkelijk een permissie toevoegt aan een rol
* delete_permission.php: een PHP-script dat daadwerkelijk een permissie verwijdert bij een rol
* edit-role: de webpagina waarmee je één rol kunt aanpassen

Daarnaast is er een gedeelde component (`{ROOT}volumes/src/websites/shared/partials/header.php`) die het menu toont.Daar
in zit één regel die leunt op deze permissie. Omdat het om een GUI gaat is dat voor nu werkbaar.

```php
    $navigationAdmin = [
        ['route' => 'logging.php', 'permission' => Permission_Admin_Panel, 'title' => 'Apache Logfiles'],
        ['route' => 'attestation_users.php', 'permission' => Permission_AdminPanel_Attestation_Users, 'title' => 'Attestation - Gebruikers'],
        ['route' => 'attestation_roles.php', 'permission' => Permission_AdminPanel_Attestation_Roles, 'title' => 'Attestation - Rollen'],
        ['route' => 'manage_roles.php', 'permission' => Permission_AdminPanel_Manage_RolePermissions, 'title' => 'Rollen'],
        ['route' => 'view-user.php', 'permission' => Permission_Admin_Panel, 'title' => 'Zoeken...'],
        ['route' => 'AssignUserToRoleForm.php', 'permission' => Permission_AdminPanel_AddUserToRole, 'title' => 'Autorisatie aanvraag'],
    ];

```

Echter, het kan ingewikkeld worden. Stel je gaat je permissies fijnmaziger maken, dan kun je dus meer sturing geven aan
gedetailleerde handelingen zoals:

* het mogen koppelen van een rol aan een permissie
* het mogen ontkoppelen van permissie bij de rol
* het mogen inzien van de details van een rol
* ...

De vraag is dan echter: welke permissie gebruik je om in de GUI de juiste elementen aan te zetten. In dit geval is dat
eenvoudig omdat we twee schermen hebben waar je naartoe kunt navigeren:

1. het scherm met de keuzelijst van rollen
2. het scherm met de informatie over één rol

Omdat het tweede scherm in wezen altijd de start is van het inzien, koppelen en ontkoppelen van permissies is dit een
logische webpagina om te laten leunen op een permissie. Deze permissie heet dan bijvoorbeeld 'Kan rollen en permissies
managen'.
Of je uiteindelijk dus mag verwijderen en/of toevoegen kun je dus op basis van andere permissies laten doen.

Je moet je alleen afvragen of dergelijke fijnmazige permissies wel zin hebben: is er een goede reden om deze handelingen
individueel te onderscheiden? Zijn er processen die bijvoorbeeld alleen autorisaties uitgeven maar nooit intrekken?
Hier is geen harde regel voor te definiëren behalve: stem dit af met de proces- en data-eigenaren. Zij zullen weten
hoe data beschermd moet worden en welke processen/transacties dit betreft.

# Het daadwerkelijk toevoegen van nieuwe permissies

We gaan als voorbeeld nieuwe permissies invoeren om het (ont-) koppelen van een permissie aan een rol te kunnen
controleren. Hiervoor moeten we de volgende aanpassingen doen:

1. we moeten permissies in de database zetten
2. we moeten in de database de nieuwe permissie koppelen aan een applicatie
3. we moeten de permissie in onze broncode kunnen gebruiken
4. we moeten de permissie controleren in de juiste PHP-scripts (`add-permission-tol-role.php` en
   `delete_permission.php`)
5. we moeten zorgen dat de juiste rollen deze nieuwe permissies gekoppeld krijgen

Om bij de laatste te beginnen: dit kunnen we eenvoudig regelen via het admin-panel. Toch? Waarschijnlijk gaat dat niet
lukken omdat de huidige rol na het aanpassen van de broncode (stap 4) dus niet meer toegestaan is. Daarom is het beter
om deze stap in het initialisatiescript op te nemen (`{ROOT}/dbserver/CreateDatabase-and-seed.sql`).

## Permissies bekend maken in de database

Zoek in het bestand `{ROOT}/dbserver/CreateDatabase-and-seed.sql` naar onderstaande regel.

```SQL
INSERT INTO permissions (code, title, description, fk_idApplication)
```

Daar onder vind je de permissies die bij het aanmaken van de database worden aangemaakt. Voeg je eigen permissies toe.
Bijvoorbeeld:

```sql
 ('AdminPanel_AddPermissionToRole', 'Add Permission to role', '', @var_App_AdminPanel)
 ,
 ('AdminPanel_RemovePermissionFromRole', 'Remove Permission from role', '', @var_App_AdminPanel),
```

De  `@var_App_AdminPanel` bevat de _primary key_ van de Admin Panel applicatie zodat de juiste koppeling ontstaat.

**Activeren van het SQL-script**
Het nieuwe script moet nog wel uitgevoerd worden om te zorgen dat de nieuwe permissies ook daadwerkelijk in de database
komen. Normaal gesproken gebeurt dat één keer bij het bouwen van de Docker-containers. Er zijn daarom meerdere
manieren om dit te regelen

1. gebruik een IDE waarmee je kunt inloggen op de database van Docker en voer het script uit
2. vernietig de container voor de database en herbouw deze.

Het vernietigen van de database container kan op verschillende manieren: via de command line of via Docker Desktop. Zorg
in ieder geval dat het volume ook verwijdert wordt. Via de command line:

```bash
 docker compose down -v iam-example-db-server
 docker compose build iam-example-db-server
 docker compose up -d iam-example-db-server
```

Stap 1 en 2 zijn nu klaar. Je kunt in principe nu ook stap 5 uitvoeren: het koppelen van de permissie aan de juiste
rollen.
Dit kan door in te loggen op het Admin Panel en de rol te openen waar je deze permissies aan wilt koppelen. Gebruik
de `edit` button om de nieuwe permissies te koppelen.

Verderop in deze handleiding zullen we dit in het SQL-script aanpassen zodat het voortaan meteen goed geregeld is.

## Permissie in onze broncode kunnen gebruiken

Het is verstandig om de administratie van je permissies op één plek te regelen. In dit geval zitten alle permissies
als een constante in het bestand `{ROOT}/volumes/src/websites/shared/lib/RBACSupport.php`. Voeg hier voor elke permissie
die je hebt toegevoegd een nieuwe regel toe. Bijvoorbeeld:

```PHP
const Permission_AdminPanel_AddPermissionToRole = 'AdminPanel_AddPermissionToRole';
const Permission_AdminPanel_RemovePermissionFromRole = 'AdminPanel_RemovePermissionFromRole';
```

Zorg dat de unieke identificatie van de permissie (het veld `code`) precies hetzelfde is als de definitie van de
constanten in bovenstaande lijst.

Stap 3 is nu klaar: we hebben constantes die we in onze broncode kunnen gebruiken.

## Controles toevoegen met de nieuwe permissies

Er zijn twee scripts die we nu moeten aanpassen:

1. `add-permission-tol-role.php`
2. `delete_permission.php`

Je in het bestand `add-permission-tol-role.php` een regel die de controle uitvoert:

```PHP
if (!$rbac->has(Permission_AdminPanel_Manage_RolePermissions)) { ... }
```

Deze roept een methode (`has`) aan van de klasse `RBACSupport` die eerder aangemaakt en geinitialiseerd is met
gebruikersinformatie. Verander de permissie naar de nieuwe permissie die je hebt aangemaakt. Bijvoorbeeld:

```PHP
if (!$rbac->has(Permission_AdminPanel_AddPermissionToRole)) { ... }
```

Voer dezelfde wijziging door voor het `delete_permission.php` script.

## Koppelen van de nieuwe permissies aan rollen

Het is mogelijk om na stappen 1 en 2 via het Admin Panel de permissies toe te wijzen aan rollen. Heb je de permissies
al ingebouwd in de scripts dan zal dat niet meer lukken omdat nog niemand deze permissies heeft.

Om te zorgen dat bij herbouw van de containers deze nieuwe permissies ook meteen aan de juiste rol gekoppeld worden
is het beter om in het database build script een aanpassing te doen.

We maken daarom de volgende aanpassingen:

1. we vragen de primary key van de nieuwe permissies op
2. we voegen records in om de permissie te koppelen aan een rol

Zoek in het bestand `{ROOT}/dbserver/CreateDatabase-and-seed.sql` naar onderstaande regel.

```text
 -- collect all the permissions in variables for later use.
```

Na deze regel zie je heel veel `SELECT ... INTO` statements staan. Deze statements halen een waarde op uit de database
en slaan deze op in een MySQL variabele voor later gebruik. Voeg je eigen regels toe om de primary key van jouw nieuwe
permissies op te halen.

Bijvoorbeeld:

```sql
SELECT permissions.idPermission INTO @var_permission_Admin_AddPermissionToRole 
                                FROM permissions 
                                WHERE code = 'AdminPanel_AddPermissionToRole';
```

Let op dat je weer precies dezelfde `code` gebruikt als bij het `INSERT`-statement.

Nu we de Primary Key van deze permissie hebben kunnen we deze koppelen aan een rol. Ga op zoek naar regels die beginnen
met `INSERT INTO role_permissions` en voeg je eigen regel toe. Ook de rollen zitten opgeslagen in variabelen. Deze kun 
je eerder in het bestand terugvinden. Voor de rol van _Autorisatie Manager_ heet de variabele 
`@var_Role_Authorisation_Manager`.

Bijvoorbeeld:

```SQL
    INSERT INTO role_permissions(fk_idRole, fk_idPermission) 
    VALUES (@var_Role_Authorisation_Manager, @var_permission_Admin_AddPermissionToRole);
```

Voer het gehele database script opnieuw uit of herbouw de database container volledig. Zie de stappen bij het eerste 
onderdeel van dit stappen plan.

# Testen

Log nu in als een gebruiker met rol "ICT Support" of "Authorisation Manager" en kijk wat je nu wel/niet mag doen. Als 
gebruiker met rol "Authorisation Manager" zou je nog steeds rollen moeten kunnen wijzigen (permissie toevoegen of 
verwijderen).

# Conclusie

Wat je gemerkt hebt in deze procedure is dat je maar minimaal code hebt hoeven aanpassen om te zorgen dat de 
functionaliteit op de website op de juiste manier beschermd wordt. Dit komt vooral door de klasse `RBACSupport`. Deze
neemt veel werk uit handen op het gebied van

* wie is er ingelogd
* welke rollen heeft deze gebruiker
* tot welke unieke lijst met permissies leiden deze rollen
* controleren of een bepaalde permissie aanwezig is

Echter, we moeten wel zorgen dat bij elke pagina/functionaliteit deze klasse geinstantieerd wordt met de juiste gegevens.
In moderne "backend frameworks" zoals ASP.NET Code, Laravel/Lumen en Spring Boot kun je deze herhaling
van broncode vermijden. Je kunt dan gebruik maken van _Middleware_ die tussen de HTTP Request en het uitvoeren van de
code in de _Controller_ kijken of je wel toegang mag hebben.

Let wel op dat je die middleware zo ontwerpt en instelt dat je Login-endpoint niet ook verwacht dat je ingelogd bent....

Martin Molema
[Martin.Molema@nhlstenden.com](mailto:Martin.Molema@nhlstenden.com)
April 2025


# Referenties

* [Microsoft ASP.net Core Middleware]https://learn.microsoft.com/en-us/aspnet/core/fundamentals/middleware/?view=aspnetcore-9.0
* [Laravel Middleware]https://laravel.com/docs/12.x/middleware