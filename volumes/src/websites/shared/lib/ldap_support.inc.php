<?php

include_once 'audittrail.lib.php';
include_once 'ldap_constants.inc.php';
include_once 'db.php';

/** @file ldap_support.inc.php
 * Lots of documented LDAP function
 *
 * @author Martin Molema <martin.molema@nhlstenden.com>
 * @copyright 2022
 *
 * A number of basic functions to show students how to interact (read/modify/query) LDAP objects.
 */

/**
 * Makes a connection to the LDAP-server (using  <a href="https://www.php.net/manual/en/function.ldap-connect.php">ldap_connect</a>)
 * and binds the user and password (<a href="https://www.php.net/manual/en/function.ldap-bind.php">ldap_bind</a>).
 * On success a resource/link is returned for further use in other functions.
 *
 * @return LDAP\Connection
 * @throws Exception
 * @see LDAP_PASSWORD
 * @see LDAP_PORT
 * @see ldap_constants.inc.php
 * @see LDAP_ADMIN_CN   The DN of the user that will setup the connection
 */
function ConnectAndCheckLDAP(): LDAP\Connection
{

// connect to the service
    $lnk = ldap_connect(LDAP_HOST, LDAP_PORT);

// check connectivity
    if ($lnk === false) {
        throw(new Exception("Cannot connect to " . LDAP_HOST . ":" . LDAP_PORT));
    } else {
        // expect protocol version 3 to be the standard
        ldap_set_option($lnk, LDAP_OPT_PROTOCOL_VERSION, 3);

        // bind to the service using a username & password
        $bindres = ldap_bind($lnk, LDAP_ADMIN_CN, LDAP_PASSWORD);
        if ($bindres === false) {
            throw(new Exception("Cannot bind using user " . LDAP_ADMIN_CN));
        }
    }

    return $lnk;
}

/**
 * Adds a user to an existing groupOfUniqueNames
 * @param $lnk LDAP\Connection to the LDAP server
 * @param $groupDN complete Distinguished name (DN) of the group the user must be added to
 * @param $userDN complete Distinguished name (DN) of the user to be added
 * @throws Exception if the user cannot be added to the group, throw an exception
 */
function AddUserToGroup(LDAP\Connection $lnk, string $groupDN, string $userDN)
{
    $attributes = [GROUP_ATTR_NAME => $userDN];
    if (@ldap_mod_add($lnk, $groupDN, $attributes) === false) {
        $error = ldap_error($lnk);
        $errno = ldap_errno($lnk);
        throw new Exception($error, $errno);
    }
    $groupDN = preg_replace("/dc=NHLStenden,dc=com/", '', $groupDN);
    $userDN  = preg_replace("/dc=NHLStenden,dc=com/", '', $userDN);
    LogAuditRecord("USER", "01", "INFO", "Adding user to group [$groupDN] to [$userDN]");
}

/**
 * Creates a new user.
 *
 * @param $lnk LDAP\Connection the connection to the LDAP server
 * @param $newUserDN string the complete Distinguished name (DN) of the user to be created
 * @param $cn string The Canonical name of the new user
 * @param $sn string The surname ("lastname") of the new user
 * @param $uid string The UserID of the new user; must be unique!
 * @param $givenName string The given name ("first name") of the new user
 * @throws Exception If the user cannot be created an exception is thrown
 */
function CreateNewUser(LDAP\Connection $lnk, string $newUserDN, string $cn, string $sn, string $uid, string $givenName): bool
{

    // setup an array with all the attributes needed to add a new user.
    $fields = array();

    // first indicate what kind of object we want te create ("Objectclass"). Multivalue attribute!!
    $fields['objectClass'][] = "top";
    $fields['objectClass'][] = "inetOrgPerson";
    $fields['objectClass'][] = "person";
    $fields['objectClass'][] = "organizationalPerson";

    $fields['cn']        = $cn;
    $fields['sn']        = $sn;
    $fields['uid']       = $uid;
    $fields['givenName'] = $givenName;

    echo "De gebruiker wordt aangemaakt op $newUserDN \n";

    // Now do the actual adding of the object to the LDAP-service
    if (ldap_add($lnk, $newUserDN, $fields) === false) {
        $error = ldap_error($lnk);
        $errno = ldap_errno($lnk);
        throw new Exception($error, $errno);
    }

    LogAuditRecord("USER", "CREATE", "INFO", "Created new user [$newUserDN]");

    return true;
}// CreateNewUser

/**
 * Changes or adds a new password for an existing user. Requires the Crypt-SHA-256 to be available as a hashing function
 * @param $lnk LDAP\Connection  the connection to the LDAP server
 * @param $newUserDN string the complete Distinguished name (DN) of the user to be created
 * @param $newPassword string The new password to be set.
 * @return string the new encrypted password
 * @throws Exception
 */
function SetPassword(LDAP\Connection $lnk, string $newUserDN, string $newPassword): string
{
    if (CRYPT_SHA256 == 1) {
        $somesalt = uniqid(mt_rand(), true);

        /** Setup a new encrypted password using the Crypt function and the CRYPT-SHA-256 hash. See the URL below
         * notice how the crypt()-function has a salt starting with $5$ to indicate the SHA-256 hash
         *
         * https://www.php.net/manual/en/function.crypt.php
         *
         **/
        $encoded_newPassword = "{CRYPT}" . crypt($newPassword, '$5$' . $somesalt . '$');
    } else {
        throw new Exception("No encryption module for Crypt-SHA-256");
    }

    $entry = ['userPassword' => $encoded_newPassword];

    if (ldap_modify($lnk, $newUserDN, $entry) === false) {
        $error = ldap_error($lnk);
        $errno = ldap_errno($lnk);
        throw new Exception($error, $errno);
    }
    return $encoded_newPassword;
}// SetPassword

/**
 * Report information about a user
 * @param $lnk LDAP\Connection the connection to the LDAP server
 * @param $userDN string the DN of the user to be reported
 * @throws Exception Throws an exception if the DN cannot be found or leads to multiple items.
 */
function ReportUser(LDAP\Connection $lnk, string $userDN)
{
    // get the object from the database and check the values.
    $ldapRes = ldap_read($lnk, $userDN, "(ObjectClass=*)", array("*"));

    if ($ldapRes !== false) {
        $entries = ldap_get_entries($lnk, $ldapRes);
        /*
         * De entries die teruggeven worden hebben
         *  - óf een index met een getal om attribuut-namen terug te geven
         *  - óf een index met een string om de waarde(n) van een attribuut terug te geven.
         */

        if ($entries['count'] == 1) {
            // take the first entry and check the 'count'-attribute
            $entry    = $entries[0];
            $numAttrs = $entry['count'];

            // collect all the attribute names
            $attributesReturned = array();
            for ($i = 0; $i < $numAttrs; $i++) {
                $attr                      = strtolower($entry[$i]);
                $attributesReturned[$attr] = $attr;
            }//for each attribute number

            // Now get the attribute values
            $valuesNamed = array();
            foreach ($attributesReturned as $attributeName) {
                // check if a value is an Array or a single value
                if (is_array($entry[$attributeName])) {
                    $thisItem = $entry[$attributeName];

                    //remove the 'count'-attribute from the array and glue them together.
                    unset($entry[$attributeName]['count']);
                    $valuesNamed[$attributeName] = join("/", $entry[$attributeName]);
                } else {
                    $valuesNamed[$attributeName] = $entry[$attributeName];
                }
            }//for each attribute

            // Now show all the values
            foreach ($valuesNamed as $key => $value) {
                echo "{$key} = $value \n";
            }//for each value

        }// if exactly one item found (this must be!)
        else {
            throw new Exception("Cannot find the given DN ($userDN)");
        }
    }
}// ReportUser

/**
 * Gets all the groups in LDAP that has the given user's DN (DistinguishedName) as a UniqueMember. So this function
 * will get all the groups that the user is a memberOf.
 * @param $lnk LDAP\Connection
 * @param $userDN string
 * @return array
 * @throws Exception
 */
function GetAllLDAPGroupMemberships(LDAP\Connection $lnk, string $userDN): array
{
    // https://www.php.net/manual/en/function.ldap-search.php

    /**
     * Perform search in the BASE_DN from the LDAP-constants (@see ldap_constants.inc.php)
     */
    $ldapRes = ldap_search($lnk, BASE_DN, "(&(objectClass=*)(uniqueMember={$userDN}))", ['*'], 0, -1, -1, 0);
    if ($ldapRes === false) {
        throw new Exception("GetAllLDAPGroupMemberships::Cannot execute query");
    }
    // now actually read the found entries
    $results = ldap_get_entries($lnk, $ldapRes);
    $groups  = [];

    // cycle through the results. first check if there are results
    if ($results !== false && $results['count'] > 0) {
        $count = $results['count'];
        for ($i = 0; $i < $count; $i++) {
            // get one record from the result
            $record = $results[$i];

            // get the 'DN' and add it to the array of groups ($groups[] = ... will add a new value)
            $groups[] = $record['dn'];
        }
    }
    return $groups;
}//GetAllLDAPGroupMemberships

/**
 * Lookup the logged in user (by using the specified UID) in LDAP and return its DistinguishedName (DN)
 * @param $lnk LDAP\Connection the active link (connected & bound)
 * @param $uid string the UserID to lookup
 * @return mixed|null will return a string (DN) or null if not found
 * @throws Exception If search raises an error an exception is thrown.
 */
function GetUserDNFromUID(LDAP\Connection $lnk, string $uid): string|null
{
    // https://www.php.net/manual/en/function.ldap-search.php
    $ldapRes = ldap_search($lnk, BASE_DN, "(&(objectClass=INetOrgPerson)(uid={$uid}))", ['*'], 0, -1, -1, 0);
    if ($ldapRes === false) {
        throw new Exception("GetUserDNFromUID::Cannot execute query");
    }

    $results = ldap_get_entries($lnk, $ldapRes);

    if ($results !== false && $results['count'] == 1) {
        $record = $results[0];
        if (isset($record['dn'])) {
            return $record['dn'];
        } else {
            return null;
        }
    } else {
        return null;
    }
}// GetUserDNFromUID

function SearchStudentByName(LDAP\Connection $lnk, string $name): array
{
    // https://www.php.net/manual/en/function.ldap-search.php
    $ldapRes = ldap_search($lnk, 'ou=Students, ou=Opleidingen,dc=NHLStenden,dc=com', "(&(objectClass=INetOrgPerson)(cn=*{$name}*))", ['*'], 0, -1, -1, 0);
    if ($ldapRes === false) {
        throw new Exception("SearchStudentByName::Cannot execute query");
    }

    $results = ldap_get_entries($lnk, $ldapRes);

    return $results;
}// GetUserDNFromUID


function SearchStudentByUID(LDAP\Connection $lnk, string $username): array|null
{
    // https://www.php.net/manual/en/function.ldap-search.php
    $ldapRes = ldap_search($lnk, 'ou=Students, ou=Opleidingen,dc=NHLStenden,dc=com', "(&(objectClass=INetOrgPerson)(uid=*{$username}*))", ['*'], 0, -1, -1, 0);
    if ($ldapRes === false) {
        throw new Exception("SearchStudentByUID::Cannot execute query");
    }

    $results = ldap_get_entries($lnk, $ldapRes);
    if ($results !== false && $results['count'] == 1) {

        $student   = $results[0];
        $result    = [];
        $nrOfItems = $student['count'];
        for ($i = 0; $i < $nrOfItems; $i++) {
            $key          = $student[$i];
            $value        = $student[$key][0];
            $result[$key] = $value;
        }
        $result['dn'] = $student['dn'];

        return $result;
    }

    return null;
}// GetUserDNFromUID


function SearchStaffByStaffNumber(LDAP\Connection $lnk, string $employeeNumber): array|null
{
    // https://www.php.net/manual/en/function.ldap-search.php
    $ldapRes = ldap_search($lnk, 'dc=NHLStenden,dc=com', "(&(objectClass=INetOrgPerson)(employeeNumber=*{$employeeNumber}*))", ['*'], 0, -1, -1, 0);
    if ($ldapRes === false) {
        throw new Exception("SearchStaffByStaffNumber::Cannot execute query");
    }

    $results = ldap_get_entries($lnk, $ldapRes);
    if ($results !== false && $results['count'] == 1) {

        $employee  = $results[0];
        $result    = [];
        $nrOfItems = $employee['count'];
        for ($i = 0; $i < $nrOfItems; $i++) {
            $key          = $employee[$i];
            $value        = $employee[$key][0];
            $result[$key] = $value;
        }
        $result['dn'] = $employee['dn'];

        return $result;
    }

    return null;
}// GetUserDNFromUID


/**
 * @param \LDAP\Connection $lnk
 * @param string $dn
 * @return array|null
 */
function GetUserDataFromDN(LDAP\Connection $lnk, string $dn): array|null
{
    $filter     = "(objectClass=*)"; // Haal alle attributen op
    $attributes = []; // Laat leeg voor alle attributen, of specificeer attributen als array

    $search = ldap_read($lnk, $dn, $filter, $attributes);

    if (!$search) {
        error_log(ldap_error($lnk));
        return null;
    }

    $results = ldap_get_entries($lnk, $search);

    if ($results !== false && $results['count'] == 1) {
        return $results[0];
    } else {
        return null;
    }
}// GetUserDNFromUID

function GetAllUsersInDN(LDAP\Connection $lnk, string $dn): array|null
{
    $filter  = "(objectClass=InetOrgPerson)";
    $ldapRes = ldap_search($lnk, $dn, $filter, ['cn', 'uid', 'sn', 'givenName'], 0, -1, -1, 0);
    if ($ldapRes === false) {
        throw new Exception("SearchStudentByUID::Cannot execute query");
    }

    $gebruikersPerDn = [];

    $entries = ldap_get_entries($lnk, $ldapRes);
    if ($entries !== false && $entries['count'] > 0) {
        for ($i = 0; $i < $entries["count"]; $i++) {
            $entry             = $entries[$i];
            $gebruikersPerDn[] = [
                "cn" => $entry["cn"][0] ?? "",
                "uid" => $entry["uid"][0] ?? "",
                "sn" => $entry["sn"][0] ?? "",
                "givenName" => $entry["givenname"][0] ?? "",
                "dn" => $entry["dn"],
            ];
        }
    }

    usort($gebruikersPerDn, function ($a, $b) {
        $snCompare = strcmp(strtolower($a["sn"]), strtolower($b["sn"]));
        if ($snCompare === 0) {
            return strcmp(strtolower($a["givenName"]), strtolower($b["givenName"]));
        } else {
            return $snCompare;
        }
    });
    return $gebruikersPerDn;
}

function GetAllRolesInDN(LDAP\Connection $lnk, string $dn): array|null
{
    $filter  = "(objectClass=GroupOfUniqueNames)";
    $ldapRes = ldap_search($lnk, $dn, $filter, ['cn'], 0, -1, -1, 0);
    if ($ldapRes === false) {
        throw new Exception("SearchStudentByUID::Cannot execute query");
    }

    $roles = [];

    $entries = ldap_get_entries($lnk, $ldapRes);
    if ($entries !== false && $entries['count'] > 0) {
        for ($i = 0; $i < $entries["count"]; $i++) {
            $entry        = $entries[$i];
            $role         = $entry["cn"][0] ?? "";
            $roles[$role] = [
                "cn" => $entry["cn"][0] ?? "",
                "dn" => $entry["dn"],
            ];
        }
    }

    usort($roles, function ($a, $b) {
        return strcmp(strtolower($a["cn"]), strtolower($b["cn"]));
    });
    return $roles;
}

/**
 * @throws Exception
 */
function RevokeUserFromRole(LDAP\Connection $lnk, string $roleDN, string $userDN): bool
{
    $entry  = [
        "uniqueMember" => $userDN
    ];
    $result = @ldap_mod_del($lnk, $roleDN, $entry);
    if ($result) {
        $roleDN = preg_replace("/,dc=NHLStenden,dc=com/", '', $roleDN);
        $userDN = preg_replace("/,dc=NHLStenden,dc=com/", '', $userDN);

        LogAuditRecord("AUTHOR", "REVOKE", "WARN", "Revoked user [$userDN] role from [$roleDN]");
        return $result;

    }
    throw new Exception("Unable to revoke user from role. Er moet altijd één gebruiker in een rol zitten.");
}

function AssignUserToRole(LDAP\Connection $lnk, string $roleDN, string $userDN): bool
{
    $entry = ["uniqueMember" => [$userDN]];
    try {
        if (@ldap_mod_add($lnk, $roleDN, $entry)) {
            $roleDN = preg_replace("/,dc=NHLStenden,dc=com/", '', $roleDN);
            $userDN = preg_replace("/,dc=NHLStenden,dc=com/", '', $userDN);

            LogAuditRecord("AUTHOR", "ADD", "INFO", "Role [$roleDN] assigned to user [$userDN]");
            return true;
        }
    } catch (Exception $e) {
        error_log(ldap_error($lnk));
    }
    return false;
}

function GetAllGroupMembersOfRole(LDAP\Connection $lnk, string $groupDN): array
{
    $attributes = ["uniqueMember"];
    $result     = ldap_read($lnk, $groupDN, "(objectClass=groupOfUniqueNames)", $attributes);
    $entries    = ldap_get_entries($lnk, $result);

    $members = [];

    if ($entries["count"] > 0) {
        $members = $entries[0]["uniquemember"];
    } else {
        echo "Groep '$groupDN' niet gevonden.\n";
    }

    $result = [];

    for ($i = 0; $i < $members["count"]; $i++) {
        if ($members[$i] !== '') {
            $record = GetUserDataFromDN($lnk, $members[$i]);

            $result[] = [
                "dn" => $record["dn"],
                "sn" => $record["sn"][0] ?? '',
                "givenName" => $record["givenname"][0] ?? '',
            ];
        }
    }

    ldap_unbind($lnk);
    return $result;
}

const LDAP_FIELD_2FA_TOKEN = "labeleduri";


function userHas2faToken(string $userDN): bool
{
    $lnk      = ConnectAndCheckLDAP();
    $userInfo = GetUserDataFromDN($lnk, $userDN);
    return isset($userInfo[LDAP_FIELD_2FA_TOKEN]);
}

function getUser2faToken(string $userDN): string
{
    $lnk      = ConnectAndCheckLDAP();
    $userInfo = GetUserDataFromDN($lnk, $userDN);

    return $userInfo[LDAP_FIELD_2FA_TOKEN][0];
}


function setUser2faToken(string $userDN, string $secret): bool
{
    $lnk = ConnectAndCheckLDAP();

    $attributes = [LDAP_FIELD_2FA_TOKEN => $secret];
    if (@ldap_mod_add($lnk, $userDN, $attributes) === false) {
        $error = ldap_error($lnk);
        $errno = ldap_errno($lnk);
        throw new Exception($error, $errno);
    }
    return true;
}

/**
 * Checks if 2fa is enabled. if enabled then check if it was validated; if not, ask to enter a TOTP code
 * If 2fa was not enabled yet, goto setup 2fa page
 * @return void
 */
function check2faOrValidate(): void
{
    $userDN = $_SESSION['dn'];

    if (session_status() === PHP_SESSION_NONE) {
        die('no session');
    }

    if (userHas2faToken($userDN)) {
        if ($_SESSION['2fa-checked'] == false) {
            header("Location: 2fa/verify-2fa.php");
            exit;
        }
    } else {
        header("Location: 2fa/setup-2fa.php");
        exit;
    }

}

function check2faOrFail(): void
{
    $userDN = $_SESSION['dn'];

    if (userHas2faToken($userDN)) {
        if ($_SESSION['2fa-checked'] == false) {
            die("Need 2fa");
        }
    } else {
        die ("Need 2fa");
    }

}

function searchLDAP(LDAP\Connection $lnk, string $search): array
{

    $ldapSearchString = "(&
        (objectClass=InetOrgPerson)
        (
          |
          (uid=*$search*)
          (sn=*$search*)
          (givenname=*$search*)
        )
        )";

    $ldapRes = ldap_search($lnk, BASE_DN, $ldapSearchString, ['dn', 'cn','uid', 'sn','givenname'], 0, -1, -1, 0);
    if ($ldapRes === false) {
        throw new Exception("searchLDAP::Cannot execute query");
    }
    // now actually read the found entries
    $results = ldap_get_entries($lnk, $ldapRes);
    $users  = [];

    // cycle through the results. first check if there are results
    if ($results !== false && $results['count'] > 0) {
        $count = $results['count'];
        for ($i = 0; $i < $count; $i++) {
            // get one record from the result
            $record = $results[$i];

            // get the 'DN' and add it to the array of groups ($groups[] = ... will add a new value)
            $users[] = $record;
        }
    }
    return $users;
}
