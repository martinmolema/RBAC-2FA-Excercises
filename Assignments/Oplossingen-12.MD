# Opdracht 12 - Functiescheidingen

Bij deze opdracht zijn er geen vragen geformuleerd. We hebben echter wel gezien hoe het mechanisme van functiescheiding
kan helpen bij het veilig houden van de organisatie en de processen die door ICT worden ondersteund. Het grote voordeel
van een dergelijke administratie is tweeledig:

1. De organisatie moet nadenken hoe dergelijke functiescheidingen *precies* in elkaar steken. Ze worden dus
   geformaliseerd
2. De kennis over functiescheiding wordt in een vorm vastgelegd die door autorisatiesystemen gebruikt kan worden om
   controles uit te voeren, te rapporteren en te blokkeren.

Echter, wat we niet hebben gedaan is het rechtstreeks wijzigen van rollen in de Identity Provider (IdP). We hadden ook
prima in de LDAP-administratie een gebruiker toe kunnen voegen aan een rol die uiteindelijk leidt tot conflicten.

Ook hier dus weer een hele geldige reden om nooit rechtstreeks in de IdP autorisaties te manipuleren maar daar altijd
een proces / systeem omheen te gebruiken waarin alle kennis over autorisaties geborgd is.
