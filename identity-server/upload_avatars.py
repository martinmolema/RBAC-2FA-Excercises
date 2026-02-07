# Script to upload avatars to the LDAP accounts.
# Run from Python Virtual Environment!
#
# Started from the script "slapd-load-entries.sh"

from ldap3 import Server, Connection, ALL, MODIFY_ADD
import base64
import os

# LDAP server en bind instellingen
LDAP_SERVER = "ldap://localhost"
BIND_DN = "cn=admin,dc=NHLStenden,dc=com"
BIND_PW = "test12345!"
BASE_DN = "dc=NHLStenden,dc=com"

# Directory waar de afbeeldingen zijn opgeslagen
IMAGE_DIR = "./avatars"

dn_list = [
"ou=Teachers,ou=Opleidingen,dc=NHLStenden,dc=com",
"ou=Staff,dc=NHLStenden,dc=com",
]


# Verbinden met de LDAP-server
server = Server(LDAP_SERVER, get_info=ALL)
conn = Connection(server, BIND_DN, BIND_PW, auto_bind=True)

for dn in dn_list:
    # Zoek alle InetOrgPerson accounts
    search_filter = "(objectClass=inetOrgPerson)"
    conn.search(dn, search_filter, attributes=['cn'])

    # Teller voor de afbeeldingen
    counter = 1

    # Loop door alle gevonden accounts
    for entry in conn.entries:
        dn = entry.entry_dn
        # Formatteer het volgnummer met drie cijfers
        image_number = f"{counter:03d}"
        image_file = os.path.join(IMAGE_DIR, f"{image_number}.jpeg")

        # Controleer of de afbeelding bestaat
        try:
            with open(image_file, "rb") as img_file:
                base64_image = base64.b64encode(img_file.read()).decode('utf-8')

            # Maak een LDIF wijziging voor de jpegPhoto attribuut
            mod_attrs = {
                'jpegPhoto': [(MODIFY_ADD, [base64.b64decode(base64_image)])]
            }

            # Voer de wijziging uit
            conn.modify(dn, mod_attrs)

            print(f"Afbeelding {image_file} geüpload naar {dn}")

        except FileNotFoundError:
            print(f"Afbeelding {image_file} niet gevonden, overslaan...")

        # Verhoog de teller
        counter += 1

        # Stop als we 200 afbeeldingen hebben geüpload
        if counter > 200:
            break

# Sluit de verbinding met de LDAP-server
conn.unbind()
