# Opdracht 7 - Inzicht in rollen - permissies

In opdracht 7 zijn de volgende vragen geformuleerd:

1. Welke permissies heeft deze rol momenteel?
2. Wat zegt dit over de permissies van één individuele student?
3. Login als een student bij [SharePoint](http://sharepoint.rbac.docker)
4. Ga naar de pagina "Mijn Gegevens" en vergelijk de lijst met Permissies met die in het admin panel

5. Log de student uit, sluit het tabblad en login bij SharePoint als een Marketing Manager (zie
   `cn=Marketing managers,ou=roles,dc=NHLStenden,dc=com`)
6. Ga naar de pagina met "Mijn Gegevens"
7. Ga in het Admin Panel terug naar de pagina met rollen en zoek de rol van Marketing Manager
8. Wat valt op qua permissies met de "Mijn gegevens" pagina van de gebruiker zelf?

## **Uitwerking vraag 1**

Als het goed is zijn na installatie de permissies als volgt:

| Applicatie | Permissie                        |
|------------|----------------------------------|
| Grades     | Basic Access to Grades app       |
| Grades     | Student can read own grades      |
| Grades     | Show students own information    |
| Mail       | Use college e-mail               |
| SharePoint | Basic Access to SharePoint       |
| SharePoint | Read news on SharePoint/Intranet |
| SharePoint | Open student tools               |

Kort samengevat: de student kan cijfers inzien, eigen informatie opvragen, mail lezen en basiszaken regelen op
SharePoint.

## **Uitwerking vraag 2**

Als je als student dus niet aanvullende rollen hebt gekregen dan zijn dit echt de enige permissies die je bezit.

## **Uitwerking vraag 3 + 4**

Na het inloggen als student zie je onderstaande. Bijvoorbeeld voor student
`cn=Anne Kuipers,ou=Students,ou=Opleidingen,dc=NHLStenden,dc=com` met gebruikersnaam `akuipers` (uid).

| Rol          | Applicatie | Permissie                        | 
|--------------|------------|----------------------------------|
| All Students | Grades     | Basic Access to Grades app       | 
| All Students | Grades     | Student can read own grades      | 
| All Students | Grades     | Show students own information    | 
| All Students | Mail       | Use college e-mail               | 
| All Students | SharePoint | Basic Access to SharePoint       | 
| All Students | SharePoint | Read news on SharePoint/Intranet | 
| All Students | SharePoint | Open student tools               | 

Deze student heeft dus maar één rol die leidt tot de gevonden permissies uit vraag 1.

## **Uitwerking vraag 8**

Wat hier opvalt is dat de Marketing Managers dus meerdere rollen hebben. Daardoor zijn er dus meer permissies
beschikbaar. Wat verder opvalt is dat er bewust gekozen is om permissies niet dubbel op te voeren. Dus je moet
wel de rol van gewone Marketing medewerker hebben om überhaupt de Marketing website te mogen openen. Deze
leunt namelijk op de permissie `Basic Access to Marketing app`.

| Rol                  | Applicatie | Permissie                        |
|----------------------|------------|----------------------------------|
| All Personell        | Mail       | Use college e-mail               |
| Marketing            | Marketing  | Basic Access to Marketing app    |
| Marketing            | Marketing  | Create a new marketing campaign  |
| Marketing            | Marketing  | Update a marketing campaign      |
| Marketing management | Marketing  | Read a marketing campaign        |
| Marketing management | Marketing  | Delete a marketing campaign      |
| Marketing management | Marketing  | Approve a marketing campaign     |
| All Personell        | SharePoint | Basic Access to SharePoint       |
| All Personell        | SharePoint | Read news on SharePoint/Intranet |
| All Personell        | SharePoint | Go to Human Resource Management  |

