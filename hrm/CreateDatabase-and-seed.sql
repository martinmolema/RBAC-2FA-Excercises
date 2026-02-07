-- Verwijder de database 'HRM' als die al bestaat (om schoon te beginnen)
DROP DATABASE IF EXISTS HRM;

-- Maak een nieuwe database genaamd 'HRM'
CREATE DATABASE HRM;

-- Selecteer de 'HRM'-database om hierna tabellen in aan te maken
USE HRM;

-- Verwijder de gebruiker 'admin' als die al bestaat (om conflicten te voorkomen)
DROP USER IF EXISTS 'admin'@'%';

-- Maak een nieuwe gebruiker 'admin' aan met het wachtwoord 'Test1234!'
-- De gebruiker kan vanaf elk IP-adres inloggen ('%')
CREATE USER 'admin'@'%' IDENTIFIED WITH mysql_native_password AS PASSWORD('Test1234!');

-- Verleen volledige rechten op de database 'HRM' aan de gebruiker 'admin'
GRANT ALL ON HRM.* TO 'admin'@'%';

-- Maak een tabel 'medewerkers' aan met informatie over personeelsleden
CREATE TABLE medewerkers
(
    idMedewerker     INT PRIMARY KEY NOT NULL AUTO_INCREMENT, -- Uniek ID, automatisch oplopend
    personeelsnummer INT UNIQUE,                              -- Uniek personeelsnummer
    voornaam         VARCHAR(80)     NOT NULL,                -- Voornaam medewerker
    achternaam       VARCHAR(80)     NOT NULL,                -- Achternaam medewerker
    team             VARCHAR(80)     NOT NULL,                -- Team waarin medewerker werkt
    functie          VARCHAR(80)     NOT NULL,                -- Functie van de medewerker
    telefoonnummer   VARCHAR(16)     NULL,                    -- Optioneel telefoonnummer
    kamernummer      VARCHAR(16)     NULL,                    -- Optioneel kamernummer
    medewerkerType   VARCHAR(15)     NULL,                    -- Optioneel type medewerker (bijv. vast, tijdelijk)
    postcode         VARCHAR(12)     NULL,                    -- Optionele postcode
    last_sync        DATETIME        NULL                     -- Tijdstip van laatste synchronisatie met een extern systeem
) COMMENT 'Medewerkers NHL Stenden';
