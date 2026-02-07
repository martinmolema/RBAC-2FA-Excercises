<?php

include_once 'ldap_constants.inc.php';
include_once 'ldap_support.inc.php';
include_once 'db.php';


const Permission_Use_Mail                   = 'Use_Mail';
const Permission_Admin_Panel                = 'AdminPanel';
const Permission_SharePoint_News            = 'SharePoint_News';
const Permission_SharePoint_All_Users       = 'SharePoint_Basic_Access';
const Permission_SharePoint_HRM             = 'SharePoint_HRM';
const Permission_SharePoint_StudentTools    = 'SharePoint_StudentTools';
const Permission_SharePoint_TeacherTools    = 'SharePoint_TeacherTools';
const Permission_Grades_BasicAccess         = 'Grades_Basic_Access';
const Permission_Grades_Create_Gradelists   = 'Grades_Create_Gradelists';
const Permission_Grades_Approve_Gradeslist  = 'Grades_Approve_Gradeslist';
const Permission_Grades_Read_Own_Grades     = 'Grades_Read_Own_Grades';
const Permission_Grades_Read_StudentDetails = 'Grades_Read_StudentDetails';
const Permission_Grades_Show_Self           = 'Grades_Show_Self';
const Permission_Marketing_Create_Campaign  = 'Marketing_Create_Campaign';
const Permission_Marketing_Read_Campaign    = 'Marketing_Read_Campaign';
const Permission_Marketing_Delete_Campaign  = 'Marketing_Delete_Campaign';
const Permission_Marketing_Update_Campaign  = 'Marketing_Update_Campaign';
const Permission_Marketing_Approve_Campaign = 'Marketing_Approve_Campaign';
const Permission_HRM_Manage_Employees       = 'HRM_Manage_Employees';

const Permission_AdminPanel_Attestation_Roles      = 'AdminPanel_Attestation_Roles';
const Permission_AdminPanel_Attestation_Users      = 'AdminPanel_Attestation_Users';
const Permission_AdminPanel_AddUserToRole          = 'AdminPanel_AddUserToRole';
const Permission_AdminPanel_RevokeUserFromRole     = 'AdminPanel_RevokeUserFromRole';
const Permission_AdminPanel_Manage_RolePermissions = 'AdminPanel_Manage_RolePermissions';


class RBACSupport
{

    private PDO $db;
    private LDAP\Connection $lnk;

    public string $username;
    public string $userDN;
    public array $groups;
    public array $permissions;

    public array $userInfoLDAP;

    public function __construct(string $userDN)
    {
        $this->db = ConnectDatabaseIAM();
        try {
            $this->lnk = ConnectAndCheckLDAP();
        } catch (Exception $ex) {
            die($ex->getMessage());
        }
        $userInfo           = GetUserDataFromDN($this->lnk, $userDN);
        $username           = $userInfo["uid"][0];
        $this->username     = $username;
        $this->userDN       = $userDN;
        $this->groups       = array();
        $this->permissions  = array();
        $this->userInfoLDAP = array();
    }

    /**
     * This function will convert the username to a DN, get the group memberships and then convert these in permissions
     * @return array | null
     */
    public function process(): bool
    {
        try {
            if ($this->getUserDN() && $this->getGroupsForUser()) {
                $this->getPermissions();
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            error_log($ex->getMessage());
        }
        return false;
    }

    public function echoPermissions(): void
    {
        echo "<pre>\n";
        foreach ($this->permissions as $permission) {
            echo $permission[7] . "\n";
        }
        echo "</pre>\n";
    }

    /**
     * @return bool
     */
    public function getUserDN(): bool
    {
        try {
            $userDN = GetUserDNFromUID($this->lnk, $this->username);
            if (!is_null($userDN)) {
                $this->userDN = $userDN;
                $this->getUserInformation();
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            error_log($ex->getMessage());
        }

        return false;
    }

    public function getUserInformation(): array|null
    {
        try {
            $ldapInfo = GetUserDataFromDN($this->lnk, $this->userDN);

            $result    = [];
            $nrOfItems = $ldapInfo['count'];
            for ($i = 0; $i < $nrOfItems; $i++) {
                $key          = $ldapInfo[$i];
                $value        = $ldapInfo[$key][0];
                $result[$key] = $value;
            }
            $result['dn'] = $ldapInfo['dn'];

            $this->userInfoLDAP = $result;
            return $this->userInfoLDAP;
        } catch (Exception $ex) {
            error_log($ex->getMessage());

        }
        return null;
    }

    /**
     * @return bool
     */
    public function getGroupsForUser(): bool
    {
        try {
            $this->groups = GetAllLDAPGroupMemberships($this->lnk, $this->userDN);
            return true;
        } catch (Exception $ex) {
            error_log($ex->getMessage());
        }
        return false;
    }

    /**
     * @return array
     */
    public function getPermissions(): array
    {

        if (count($this->groups) === 0) {
            return [];
        }

        $allGroupsForSQL = implode(", ",
            array_map(function ($g) {
                return "'$g'";
            }, $this->groups));

        $SQL = "SELECT * FROM vw_Role_Permissions WHERE dn in ({$allGroupsForSQL})";

        try {
            $stmt = $this->db->prepare($SQL);
            $stmt->execute();
            $this->permissions = [];
            foreach ($stmt as $row) {
                $this->permissions[$row["permission_code"]] = $row;
            }
        } catch (PDOException $ex) {
            echo "RBAC::getPermissions \n";
            echo $ex->getMessage();
        }

        return $this->permissions;
    }

    public function addPermissionsForRole(string $role): bool
    {
        $SQL  = "SELECT * FROM vw_Role_Permissions WHERE dn = :role_dn";
        $stmt = $this->db->prepare($SQL);
        $stmt->bindValue('role_dn', $role, PDO::PARAM_STR);
        try {
            $stmt->execute();
            foreach ($stmt as $row) {
                $this->permissions[$row["permission_code"]] = $row;
            }
        } catch (PDOException $ex) {
            echo $ex->getMessage();
            return false;
        }
        return true;

    }

    public function has(string $permission): bool
    {
        return array_key_exists($permission, $this->permissions);
    }

    public function hasOneOfThesePermissions(array $permission): bool {
        return count(array_intersect($permission, array_keys($this->permissions)) ) > 0;
    }

}