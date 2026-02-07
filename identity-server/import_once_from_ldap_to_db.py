# Python script to import the staff/teacher accounts to the HRM Database
# This is run once (manually!) after installation when all docker containers are running.
# Started from the script "slapd-load-entries.sh"
#

import mysql.connector
from ldap3 import Server, Connection, SUBTREE

# ✅ LDAP instellingen
LDAP_SERVER = "ldap://identityserver"
LDAP_USER = "cn=admin,dc=NHLStenden,dc=com"
LDAP_PASSWORD = "test12345!"
SEARCH_BASE = "dc=NHLStenden,dc=com"

# ✅ MariaDB instellingen
DB_CONFIG = {
     "host": "iam-example-hrm-server",
     "port": 3306,
     "user": "admin",
     "password": "Test1234!",
     "database": "HRM"
 }

dn_naar_functie = {
    "cn=Marketing Employees,ou=roles,dc=NHLStenden,dc=com": "medewerker marketing",
    "cn=Marketing managers,ou=roles,dc=NHLStenden,dc=com": "marketing manager",
    "cn=All Teachers,ou=roles,dc=NHLStenden,dc=com": "docent",
    "cn=ICT Support,ou=roles,dc=NHLStenden,dc=com": "medewerker ICT",
    "cn=HRM,ou=roles,dc=NHLStenden,dc=com": "medewerker HRM"
}


def clear_table(cursor):
    """Leegt de medewerkers-tabel."""
    print("🗑️ Tabel leegmaken...")
    cursor.execute("DELETE FROM medewerkers;")
    print("✅ Tabel is geleegd.")

def get_medewerkers_in_group(ldapConnection, dn):
    ldapConnection.search(dn, "(objectClass=GroupOfUniqueNames)", search_scope=SUBTREE, attributes=["uniqueMember"])
    if ldapConnection.entries:
        group_entry = ldapConnection.entries[0]
        return group_entry.uniqueMember.values
    else:
        print(f"Geen users in {dn}")
        return []

def list_users(members):
    print("* Medewerkers in deze DN:")
    for medewerker_dn in members:
        print(f"- : {medewerker_dn}")


def getUserInfoFromLdap(connLdap, medewerker_dn, functie):
    connLdap.search(search_base=medewerker_dn,
                search_filter="(objectClass=inetOrgPerson)",
                search_scope='BASE',
                attributes=["*"])

    if connLdap.entries:
        medewerker = connLdap.entries[0]
        print(f"- found: {str(medewerker.cn)}")
        return {
            "cn": str(medewerker.cn),
            "postalCode": str(medewerker.postalCode),
            "telephoneNumber": str(medewerker.telephoneNumber),
            "givenName": str(medewerker.givenName),
            "sn": str(medewerker.sn),
            "employeeType": str(medewerker.employeeType),
            "employeeNumber": str(medewerker.employeeNumber),
            "roomNumber": str(medewerker.roomNumber),
            "organizationName": str(medewerker.organizationName),
            "functie": functie,
        }
    else:
        return None

def saveEmployeeToDatabase(connSQL, cursor, medewerker):
    sql = """
          INSERT INTO medewerkers (personeelsnummer, \
                                   voornaam, \
                                   achternaam, \
                                   team, \
                                   functie, \
                                   telefoonnummer, \
                                   kamernummer, \
                                   medewerkerType, \
                                   postcode)
          VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s); \
          """

    voornaam = medewerker.get("givenName")
    achternaam = medewerker.get("sn")
    functie = medewerker.get("functie")

    cursor.execute(sql, (medewerker.get("employeeNumber"),
                         voornaam,
                         achternaam,
                         medewerker.get("organizationName"),
                         functie,
                         medewerker.get("telephoneNumber"),
                         medewerker.get("roomNumber"),
                         medewerker.get("employeeType"),
                         medewerker.get("postalCode")
                         ))
    print(f"✅ Toegevoegd: {voornaam} {achternaam} als {functie}")

    connSQL.commit()


def process_members(connSQL, ldapConnection, cursor, dn):

    functie = getFunctionFromDN(dn)
    members = get_medewerkers_in_group(ldapConnection, dn)
    list_users(members)

    for medewerker_dn in members:
        print(f"- Ophalen medewerker: {medewerker_dn}")

        medewerker = getUserInfoFromLdap(ldapConnection, medewerker_dn, functie)
        if not  medewerker is None:
            saveEmployeeToDatabase(connSQL, cursor, medewerker)

def getFunctionFromDN(dn):
    # Extract functie uit DN
    for ou, functie_naam in dn_naar_functie.items():
        if ou in dn:
            return functie_naam

    print(f"⚠️ Geen functie gevonden voor {dn}, overslaan...")
    return None


def insert_into_mariadb():
    """Voegt medewerkers toe aan de MariaDB-database."""
    connSQL = mysql.connector.connect(**DB_CONFIG)
    cursor = connSQL.cursor()

    clear_table(cursor)

    server = Server(LDAP_SERVER)
    connLdap = Connection(server, LDAP_USER, LDAP_PASSWORD, auto_bind=True)

    for dn in dn_naar_functie:
        print(f"Zoeken naar medewerkers in [{dn}]")

        process_members(connSQL,connLdap, cursor, dn)

    cursor.close()
    connSQL.close()

if __name__ == "__main__":
    insert_into_mariadb()
