# Script to add random information to pre defined fields in the LDAP store.
# Run from Python Virtual Environment!
#
# Started from the script "slapd-load-entries.sh"


from ldap3 import Server, Connection, ALL, MODIFY_REPLACE
import random


# LDAP server verbindingsgegevens
ldap_server = "ldap://localhost"
ldap_user = "cn=admin,dc=NHLStenden,dc=com"
ldap_password = "test12345!"


# Functie om een willekeurig 6-cijferig medewerkersnummer te genereren
def generate_employee_number():
    return str(random.randint(100000, 999999))

# Functie om een geldig Nederlands mobiel telefoonnummer te genereren
def generate_telephone_number():
    return "+316" + str(random.randint(10000000, 99999999))

# Functie om de voornaam uit CN te halen
def extract_givenname(cn):
    return cn.split(' ')[0]

# Functie om het employeeType te bepalen op basis van DN
def determine_employee_type(dn):
    if 'ou=Teachers' in dn:
        return 'Teacher'
    elif 'ou=Staff' in dn:
        return 'Staff'
    elif 'ou=Students' in dn:
        return 'Student'
    else:
        return 'Unknown'

# Functie om een willekeurige postcode in Noord-Nederland te genereren
def generate_postal_code():
    # Postcodes in Noord-Nederland beginnen met 9
    return "9" + str(random.randint(1000, 9999)) + "AA"

# Functie om een willekeurig kamernummer te genereren
def generate_room_number():
    building = random.choice(['N', 'Z'])
    wing = random.choice(['A','B','C','D','E'])
    floor = random.randint(1, 5)
    room = random.randint(1, 150)
    return f"{building}-{wing}{floor}-{room:03}"

# Verbinden met de LDAP server
server = Server(ldap_server, get_info=ALL)
conn = Connection(server, ldap_user, ldap_password, auto_bind=True)

# Zoeken naar alle gebruikers in de LDAP directory
base_dn = "dc=NHLStenden,dc=com"
search_filter = "(objectClass=person)"
conn.search(base_dn, search_filter, attributes=['cn'])

# Itereren over elke gebruiker en hun informatie bijwerken
for entry in conn.entries:
    dn = entry.entry_dn
    cn = entry.cn.value
    givenname = extract_givenname(cn)
    employee_number = generate_employee_number()
    telephone_number = generate_telephone_number()
    employee_type = determine_employee_type(dn)
    postal_code = generate_postal_code()
    room_number = generate_room_number()

    if employee_type != "Unknown":

        # Voorbereiden van de wijzigingen
        modifications = {
            'employeeNumber': [(MODIFY_REPLACE, [employee_number])],
            'telephoneNumber': [(MODIFY_REPLACE, [telephone_number])],
            'organizationName': [(MODIFY_REPLACE, ['NHL Stenden'])],
            'givenName': [(MODIFY_REPLACE, [givenname])],
            'employeeType': [(MODIFY_REPLACE, [employee_type])],
            'postalCode': [(MODIFY_REPLACE, [postal_code])],
            'roomNumber': [(MODIFY_REPLACE, [room_number])]
        }

        # Toepassen van de wijzigingen op de gebruiker
        conn.modify(dn, modifications)

# Verbinding met de LDAP server sluiten
conn.unbind()

print("Alle gebruikers zijn bijgewerkt met nep informatie.")
