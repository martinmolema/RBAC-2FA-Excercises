#!/bin/bash
# Run from Docker build

# Stel de domeinnaam in
echo "slapd slapd/domain string NHLStenden.com" | debconf-set-selections
echo "slapd shared/organization string NHL Stenden University of Applied Sciences" | debconf-set-selections

# Stel de backend in (MDB is aanbevolen)
echo "slapd slapd/backend select MDB" | debconf-set-selections

# Wachtwoord voor de admin-gebruiker
echo "slapd slapd/password1 password test12345!" | debconf-set-selections
echo "slapd slapd/password2 password test12345!" | debconf-set-selections

# Geen automatische databaseconfiguratie
echo "slapd slapd/no_configuration boolean false" | debconf-set-selections
