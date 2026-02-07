--
-- Create and seed the IAM database
-- This database will hold the mapping between LDAP roles and application dependant permissions
--

-- First drop existing database if it exists, the create a new one
DROP DATABASE IF EXISTS IAM;
CREATE DATABASE IAM;

-- Start working in the new database
USE IAM;

-- Create new user 'student'; this should not exist... but let's delete it anyway
DROP USER IF EXISTS 'student'@'%';
CREATE USER 'student'@'%' IDENTIFIED WITH mysql_native_password AS PASSWORD('test1234');

-- Grant all permissions for the newly created student user.
GRANT ALL ON IAM.* TO 'student'@'%';

-- Create the audit trail table
CREATE OR REPLACE TABLE audittrail
(
    idAuditTrail INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
    category     VARCHAR(20)              NOT NULL,
    code         VARCHAR(15)              NOT NULL,
    level        VARCHAR(10)              NOT NULL,
    username     VARCHAR(25),
    description  VARCHAR(200),
    timestamp    DATETIME DEFAULT NOW()
) COMMENT 'Audit trail for authentication and permission changes';

-- Create a table to hold the roles. The 'distinguishedName' should match an existing LDAP-role
CREATE OR REPLACE TABLE roles
(
    idRole             INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    title              VARCHAR(30)  NOT NULL COMMENT 'Unique name for role',
    description        VARCHAR(200) NOT NULL,
    distinghuishedName VARCHAR(255) NOT NULL COMMENT 'Reference to existing group in LDAP tree'
) COMMENT 'Roles assignable to users';

-- make sure the title of roles is unique
CREATE UNIQUE INDEX UniqueRoleTitle ON roles (title);

-- Create a table to hold applications
CREATE TABLE application
(
    idApplication INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    title         VARCHAR(30)  NOT NULL COMMENT 'Unique name for application',
    description   VARCHAR(200) NOT NULL
) COMMENT 'Container for permissions';

CREATE UNIQUE INDEX UniqueApplicationTitle ON application (title);

-- Create a table to hold permissions. These permissions are strongly related to functionalities of each application.
CREATE TABLE permissions
(
    idPermission     INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    code             VARCHAR(80)  NOT NULL COMMENT 'Unique code to be used in source code',
    title            VARCHAR(50)  NOT NULL COMMENT 'More descriptive role for management',
    description      VARCHAR(200) NOT NULL COMMENT 'Description like goal etc',
    fk_idApplication INT UNSIGNED NOT NULL,
    CONSTRAINT FOREIGN KEY idPermissionApplication (fk_idApplication) REFERENCES application (idApplication) ON DELETE CASCADE ON UPDATE RESTRICT
) COMMENT 'Possible permissions to protect transactions';

-- create constraints
CREATE UNIQUE INDEX UniquePermissionCode ON permissions (code);
CREATE UNIQUE INDEX UniquePermissionTitle ON permissions (title);

-- create a table to link the roles to the permissions.
CREATE TABLE role_permissions
(
    idRolePermission INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    fk_idPermission  INT UNSIGNED NOT NULL,
    fk_idRole        INT UNSIGNED NOT NULL,
    CONSTRAINT FOREIGN KEY idPermission (fk_idPermission) REFERENCES permissions (idPermission) ON DELETE CASCADE ON UPDATE RESTRICT,
    CONSTRAINT FOREIGN KEY idRole (fk_idRole) REFERENCES roles (idRole) ON DELETE CASCADE ON UPDATE RESTRICT
) COMMENT 'Roles related to permissions';

CREATE UNIQUE INDEX UniqueRolePermission ON role_permissions (fk_idPermission, fk_idRole);



/***
  Segregation of Duties (SoD)
 */


CREATE OR REPLACE TABLE permission_conflicts
(
    idConflict INT UNSIGNED NOT NULL PRIMARY KEY  AUTO_INCREMENT,
    description VARCHAR(200) NOT NULL COMMENT 'Reason for conflicts',
    idPermissionA INT UNSIGNED NOT NULL,
    idPermissionB INT UNSIGNED NOT NULL,
    CONSTRAINT fk_permissionA FOREIGN KEY (idPermissionA) REFERENCES permissions (idPermission),
    CONSTRAINT fk_permissionB FOREIGN KEY (idPermissionB) REFERENCES permissions (idPermission),
    CONSTRAINT uc_permission_pair UNIQUE (idPermissionA, idPermissionB),
    CHECK (idPermissionA < idPermissionB)
) COMMENT 'Define permissions that conflict and should not be assigned to the same role or user';

DELIMITER $$
CREATE OR REPLACE TRIGGER trg_prevent_conflict
    BEFORE INSERT ON role_permissions
    FOR EACH ROW
BEGIN
    IF EXISTS (
        SELECT 1
        FROM role_permissions rp
                 JOIN permission_conflicts pc
                      ON (pc.idPermissionA = rp.fk_idPermission AND pc.idPermissionB = NEW.fk_idPermission)
                          OR (pc.idPermissionB = rp.fk_idPermission AND pc.idPermissionA = NEW.fk_idPermission)
        WHERE rp.fk_idRole = NEW.fk_idRole
    ) THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Conflicting permissions assigned to same role!';
    END IF;
END$$
DELIMITER ;

CREATE OR REPLACE VIEW vw_SOD AS
    SELECT sod.idConflict as id,
           app.title as applicationTitle,
           app.description as applicationDescription,
           sod.description as description,
           p1.idPermission as id1,
           p2.idPermission as id2,
           p1.code as permission1_code,
           p1.title as permission1_title,
           p2.code as permission2_code,
           p2.title as permission2_title
      FROM permission_conflicts sod
           JOIN permissions p1 on sod.idPermissionA = p1.idPermission
           JOIN permissions p2 on sod.idPermissionB = p2.idPermission
           JOIN application app on p1.fk_idApplication = app.idApplication
    ORDER BY app.title, p1.title, p2.title
;

-- now create a Stored Procedure to seed the database.
-- This is chosen so the PHP-scripts can call these scripts to easily restore the start situation in case of troubles.
DELIMITER $$

CREATE OR REPLACE PROCEDURE InitAllRolesAndPermissions()
BEGIN
    -- first insert all roles
    INSERT INTO roles (title, description, distinghuishedName)
    VALUES ('ICT Support', 'ICT Support', 'cn=ICT Support,ou=roles,dc=NHLStenden,dc=com'),
           ('Authorisation Manager', 'Authorisation Manager', 'cn=Authorisation Manager,ou=roles,dc=NHLStenden,dc=com'),
           ('All Personell', 'All Personell (Staff, teachers)', 'cn=All Personell,ou=roles,dc=NHLStenden,dc=com'),
           ('All Students', 'All Students ', 'cn=All Students,ou=roles,dc=NHLStenden,dc=com'),
           ('All Teachers', 'All Teachers', 'cn=All Teachers,ou=roles,dc=NHLStenden,dc=com'),

           ('Grades Students', 'Grades Students', 'cn=Grades Students,ou=roles,dc=NHLStenden,dc=com'),
           ('Grades Teachers', 'Grades Teachers', 'cn=Grades Teachers,ou=roles,dc=NHLStenden,dc=com'),

           ('SharePoint Students', 'SharePoint Students', 'cn=SharePoint Students,ou=roles,dc=NHLStenden,dc=com'),
           ('SharePoint Teachers', 'SharePoint Teachers', 'cn=SharePoint Teachers,ou=roles,dc=NHLStenden,dc=com'),

           ('Students ADCSS', 'Studenten aan de opleiding Ad Cyber Safety & Security', 'cn=Students ADCSS,ou=opleidingen,ou=roles,dc=NHLStenden,dc=com'),
           ('Students HBO-ICT', 'Studenten aan de opleiding HBO-ICT', 'cn=Students HBO-ICT,ou=opleidingen,ou=roles,dc=NHLStenden,dc=com'),
           ('Teachers ADCSS', 'Docenten aan de opleiding Ad Cyber Safety & Security', 'cn=Teachers ADCSS,ou=opleidingen,ou=roles,dc=NHLStenden,dc=com'),
           ('Teachers HBO-ICT', 'Docenten aan de opleiding HBO-ICT', 'cn=Teachers HBO-ICT,ou=opleidingen,ou=roles,dc=NHLStenden,dc=com'),

           ('Marketing', 'De afdeling Marketing & Corporate Communicatie', 'cn=Marketing,ou=roles,dc=NHLStenden,dc=com'),
           ('Marketing Medewerker', 'Gewone marketing medewerkers', 'cn=Marketing Employees,ou=roles,dc=NHLStenden,dc=com'),
           ('Marketing Management', 'Het managementteam van de afdeling Marketing', 'cn=Marketing managers,ou=roles,dc=NHLStenden,dc=com'),

           ('Human Resources Management', 'De afdeling Human Resource Management', 'cn=hrm,ou=roles,dc=NHLStenden,dc=com');

    -- create variables to hold all the Primary Keys of the roles for later use
    SELECT roles.idRole INTO @var_Role_admin FROM roles WHERE title = 'admin';
    SELECT roles.idRole INTO @var_Role_all_personell FROM roles WHERE title = 'All Personell';

    SELECT roles.idRole INTO @var_Role_all_students FROM roles WHERE title = 'All Students';
    SELECT roles.idRole INTO @var_Role_all_teachers FROM roles WHERE title = 'All Teachers';

    SELECT roles.idRole INTO @var_Role_ICT_Support FROM roles WHERE title = 'ICT Support';
    SELECT roles.idRole INTO @var_Role_Authorisation_Manager FROM roles WHERE title = 'Authorisation Manager';

    SELECT roles.idRole INTO @var_Role_Grades_Students FROM roles WHERE title = 'Grades Students';
    SELECT roles.idRole INTO @var_Role_Grades_Teachers FROM roles WHERE title = 'Grades Teachers';

    SELECT roles.idRole INTO @var_Role_Teachers_HBOICT FROM roles WHERE title = 'Teachers ADCSS';
    SELECT roles.idRole INTO @var_Role_Teachers_ADCSS FROM roles WHERE title = 'Teachers HBO-ICT';

    SELECT roles.idRole INTO @var_Role_Students_HBOICT FROM roles WHERE title = 'Teachers ADCSS';
    SELECT roles.idRole INTO @var_Role_Students_ADCSS FROM roles WHERE title = 'Teachers HBO-ICT';

    SELECT roles.idRole INTO @var_Role_SharePoint_Students FROM roles WHERE title = 'SharePoint Students';
    SELECT roles.idRole INTO @var_Role_SharePoint_Teachers FROM roles WHERE title = 'SharePoint Teachers';

    SELECT roles.idRole INTO @var_Role_Marketing FROM roles WHERE title = 'Marketing';
    SELECT roles.idRole INTO @var_Role_Marketing_Employees FROM roles WHERE title = 'Marketing Medewerker';
    SELECT roles.idRole INTO @var_Role_Marketing_Management FROM roles WHERE title = 'Marketing management';

    SELECT roles.idRole INTO @var_Role_HRM FROM roles WHERE title = 'human Resources Management';

    -- create applications
    INSERT INTO application (title, description)
    VALUES ('Admin Panel', ''),
           ('SharePoint', ''),
           ('Marketing', ''),
           ('Grades', ''),
           ('HRM', ''),
           ('Mail', '');

    -- collect all primary keys in variables for later use
    SELECT idApplication INTO @var_App_AdminPanel FROM application WHERE title = 'Admin Panel';
    SELECT idApplication INTO @var_App_SharePoint FROM application WHERE title = 'SharePoint';
    SELECT idApplication INTO @var_App_Marketing FROM application WHERE title = 'Marketing';
    SELECT idApplication INTO @var_App_Grades FROM application WHERE title = 'Grades';
    SELECT idApplication INTO @var_App_Mail FROM application WHERE title = 'Mail';
    SELECT idApplication INTO @var_App_HRM FROM application WHERE title = 'HRM';

    -- create all the permissions; link them to the application using the variables holding the PK of the application
    INSERT INTO permissions (code, title, description, fk_idApplication)
    VALUES ('SharePoint_Basic_Access', 'Basic Access to SharePoint', '', @var_App_SharePoint),
           ('Grades_Basic_Access', 'Basic Access to Grades app', '', @var_App_Grades),
           ('Marketing_Basic_Access', 'Basic Access to Marketing app', '', @var_App_Marketing),
           ('Use_Mail', 'Use college e-mail', '', @var_App_Mail),

           ('AdminPanel', 'Use Admin Panel', '', @var_App_AdminPanel),
           ('AdminPanel_Attestation_Roles', 'Attestation - Roles', '', @var_App_AdminPanel),
           ('AdminPanel_Attestation_Users', 'Attestation - Users', '', @var_App_AdminPanel),
           ('AdminPanel_AddUserToRole', 'Add user to role', '', @var_App_AdminPanel),
           ('AdminPanel_RevokeUserFromRole', 'Revoke user from role', '', @var_App_AdminPanel),
           ('AdminPanel_Manage_RolePermissions', 'Manage roles/permissions', '', @var_App_AdminPanel),

           ('SharePoint_News', 'Read news on SharePoint/Intranet', '', @var_App_SharePoint),
           ('SharePoint_HRM', 'Go to Human Resource Management', '', @var_App_SharePoint),
           ('SharePoint_StudentTools', 'Open student tools', '', @var_App_SharePoint),
           ('SharePoint_TeacherTools', 'Open teacher\'s tools', '', @var_App_SharePoint),

           ('Grades_Create_Gradelists', 'Create a new list of grades', '', @var_App_Grades),
           ('Grades_Approve_Gradeslist', 'Approve a list of grades', '', @var_App_Grades),
           ('Grades_Read_Own_Grades', 'Student can read own grades', '', @var_App_Grades),
           ('Grades_Read_StudentDetails', 'Get information on all students', '', @var_App_Grades),
           ('Grades_Show_Self', 'Show students own information', '', @var_App_Grades),

           ('Marketing_Create_Campaign', 'Create a new marketing campaign', '', @var_App_Marketing),
           ('Marketing_Read_Campaign', 'Read a marketing campaign', '', @var_App_Marketing),
           ('Marketing_Delete_Campaign', 'Delete a marketing campaign', '', @var_App_Marketing),
           ('Marketing_Update_Campaign', 'Update a marketing campaign', '', @var_App_Marketing),
           ('Marketing_Approve_Campaign', 'Approve a marketing campaign', '', @var_App_Marketing),

           ('HRM_Manage_Employees', 'Manage Employees', '', @var_App_HRM);

    -- collect all the permissions in variables for later use.
    SELECT permissions.idPermission INTO @var_permission_Use_Mail FROM permissions WHERE code = 'Use_Mail';

    -- Admins
    SELECT permissions.idPermission INTO @var_permission_Admin_Panel FROM permissions WHERE code = 'AdminPanel';
    SELECT permissions.idPermission
    INTO @var_permission_AdminPanel_Attestation_Roles
    FROM permissions
    WHERE code = 'AdminPanel_Attestation_Roles';
    SELECT permissions.idPermission
    INTO @var_permission_AdminPanel_Attestation_Users
    FROM permissions
    WHERE code = 'AdminPanel_Attestation_Users';
    SELECT permissions.idPermission
    INTO @var_permission_AdminPanel_AddUserToRole
    FROM permissions
    WHERE code = 'AdminPanel_AddUserToRole';
    SELECT permissions.idPermission
    INTO @var_permission_AdminPanel_RevokeUserFromRole
    FROM permissions
    WHERE code = 'AdminPanel_RevokeUserFromRole';
    SELECT permissions.idPermission
    INTO @var_permission_AdminPanel_Manage_RolePermissions
    FROM permissions
    WHERE code = 'AdminPanel_Manage_RolePermissions';

    -- SharePoint
    SELECT permissions.idPermission
    INTO @var_permission_SharePoint_Basic_Access
    FROM permissions
    WHERE code = 'SharePoint_Basic_Access';
    SELECT permissions.idPermission
    INTO @var_permission_SharePoint_News
    FROM permissions
    WHERE code = 'SharePoint_News';
    SELECT permissions.idPermission INTO @var_permission_SharePoint_HRM FROM permissions WHERE code = 'SharePoint_HRM';
    SELECT permissions.idPermission
    INTO @var_permission_SharePoint_StudentTools
    FROM permissions
    WHERE code = 'SharePoint_StudentTools';
    SELECT permissions.idPermission
    INTO @var_permission_SharePoint_TeacherTools
    FROM permissions
    WHERE code = 'SharePoint_TeacherTools';

    -- Grades
    SELECT permissions.idPermission
    INTO @var_permission_Grades_Basic_Access
    FROM permissions
    WHERE code = 'Grades_Basic_Access';
    SELECT permissions.idPermission
    INTO @var_permission_Grades_Create_Gradelists
    FROM permissions
    WHERE code = 'Grades_Create_Gradelists';
    SELECT permissions.idPermission
    INTO @var_permission_Grades_Approve_Gradeslist
    FROM permissions
    WHERE code = 'Grades_Approve_Gradeslist';
    SELECT permissions.idPermission
    INTO @var_permission_Grades_Read_Own_Grades
    FROM permissions
    WHERE code = 'Grades_Read_Own_Grades';
    SELECT permissions.idPermission
    INTO @var_permission_Grades_Read_StudentDetails
    FROM permissions
    WHERE code = 'Grades_Read_StudentDetails';
    SELECT permissions.idPermission
    INTO @var_permission_Grades_Show_Self
    FROM permissions
    WHERE code = 'Grades_Show_Self';

    -- Marketing
    SELECT permissions.idPermission
    INTO @var_permission_Marketing_Basic_Access
    FROM permissions
    WHERE code = 'Marketing_Basic_Access';
    SELECT permissions.idPermission
    INTO @var_permission_Marketing_Create_Campaign
    FROM permissions
    WHERE code = 'Marketing_Create_Campaign';
    SELECT permissions.idPermission
    INTO @var_permission_Marketing_Read_Campaign
    FROM permissions
    WHERE code = 'Marketing_Read_Campaign';
    SELECT permissions.idPermission
    INTO @var_permission_Marketing_Delete_Campaign
    FROM permissions
    WHERE code = 'Marketing_Delete_Campaign';
    SELECT permissions.idPermission
    INTO @var_permission_Marketing_Update_Campaign
    FROM permissions
    WHERE code = 'Marketing_Update_Campaign';
    SELECT permissions.idPermission
    INTO @var_permission_Marketing_Approve_Campaign
    FROM permissions
    WHERE code = 'Marketing_Approve_Campaign';

    -- HRM
    SELECT permissions.idPermission
    INTO @var_permission_HRM_Manage_Employees
    FROM permissions
    WHERE code = 'HRM_Manage_Employees';

    -- ------------------------------------------------------------------------------------------------------------
    -- now link the roles to the application permissions using the variables for PK of role and PK of permission.
    -- ------------------------------------------------------------------------------------------------------------
    INSERT INTO role_permissions(fk_idRole, fk_idPermission)
    VALUES (@var_Role_all_personell, @var_permission_SharePoint_Basic_Access);

    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES (@var_Role_all_personell, @var_permission_Use_Mail);

    INSERT INTO role_permissions(fk_idRole, fk_idPermission)
    VALUES (@var_Role_all_personell, @var_permission_SharePoint_HRM);

    INSERT INTO role_permissions(fk_idRole, fk_idPermission)
    VALUES (@var_Role_all_personell, @var_permission_SharePoint_News);

    -- Marketing
    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES (@var_Role_Marketing, @var_permission_Marketing_Basic_Access);

    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES (@var_Role_Marketing_Management, @var_permission_Marketing_Read_Campaign);
    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES (@var_Role_Marketing_Management, @var_permission_Marketing_Delete_Campaign);
    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES (@var_Role_Marketing_Management, @var_permission_Marketing_Approve_Campaign);

    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES (@var_Role_Marketing_Employees, @var_permission_Marketing_Create_Campaign);
    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES (@var_Role_Marketing_Employees, @var_permission_Marketing_Read_Campaign);
    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES (@var_Role_Marketing_Employees, @var_permission_Marketing_Update_Campaign);

    -- Grades
    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES (@var_Role_all_students, @var_permission_Use_Mail);
    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES (@var_Role_All_Students, @var_permission_Grades_Basic_Access);
    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES (@var_Role_All_Students, @var_permission_Grades_Read_Own_Grades);
    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES (@var_Role_All_Students, @var_permission_Grades_Show_Self);
    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES (@var_Role_All_Students, @var_permission_SharePoint_StudentTools);
    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES (@var_Role_All_Students, @var_permission_SharePoint_News);

    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES (@var_Role_All_Teachers, @var_permission_Grades_Basic_Access);
    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES (@var_Role_All_Teachers, @var_permission_SharePoint_TeacherTools);

    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES (@var_Role_Grades_Teachers, @var_permission_Grades_Create_Gradelists);
    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES (@var_Role_Grades_Teachers, @var_permission_Grades_Approve_Gradeslist);
    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES (@var_Role_Grades_Teachers, @var_permission_Grades_Read_StudentDetails);

    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES (@var_Role_ICT_Support, @var_permission_AdminPanel_Attestation_Roles);
    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES (@var_Role_ICT_Support, @var_permission_AdminPanel_Attestation_Users);

    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES (@var_Role_Authorisation_Manager, @var_permission_AdminPanel_AddUserToRole);
    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES (@var_Role_Authorisation_Manager, @var_permission_AdminPanel_RevokeUserFromRole);
    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES (@var_Role_Authorisation_Manager, @var_permission_AdminPanel_Manage_RolePermissions);

    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES (@var_Role_ICT_Support, @var_permission_SharePoint_Basic_Access);
    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES (@var_Role_ICT_Support, @var_permission_Admin_Panel);

    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES (@var_Role_All_Students, @var_permission_SharePoint_Basic_Access);

    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES (@var_Role_HRM, @var_permission_HRM_Manage_Employees);


END $$
DELIMITER ;

-- now call the newly created stored procedure to actually seed the database.
CALL InitAllRolesAndPermissions();

-- create a convenience view to use in a DB management program for insight in the mappings.
CREATE OR REPLACE VIEW vw_Role_Permissions AS
SELECT idRolePermission,
       idRole,
       a.title                  as application,
       roles.title              as role,
       roles.distinghuishedName as dn,
       idPermission,
       p.title                  as permission,
       p.code                   as permission_code
FROM roles
         JOIN role_permissions rp on roles.idRole = rp.fk_idRole
         JOIN permissions p on rp.fk_idPermission = p.idPermission
         JOIN application a on a.idApplication = p.fk_idApplication
;


/**
  Create a stored function to get all roles and permissions in a pivot table for use in the Admin Panel website.
  */

DELIMITER $$

CREATE OR REPLACE FUNCTION GenerateRolePermissionCrossTable()
    RETURNS TEXT
BEGIN
    DECLARE sql_query TEXT;
    DECLARE header TEXT;
    DECLARE result TEXT;

    SET @sql = NULL;
    SELECT GROUP_CONCAT(
                   DISTINCT
                   CONCAT(
                           'MAX(CASE WHEN r.title = ''',
                           r.title,
                           ''' THEN 1 ELSE 0 END) AS `',
                           r.title,
                           '`'
                   )
           )
    INTO @sql
    FROM roles r;

    SET sql_query = CONCAT('SELECT a.title as Application, p.title AS Permission, ', @sql, '
                           FROM permissions p
                           LEFT JOIN role_permissions rp ON p.idPermission = rp.fk_idPermission
                           LEFT JOIN roles r ON rp.fk_idRole = r.idRole
                           LEFT JOIN application a ON p.fk_idApplication = a.idApplication
                           GROUP BY a.title, p.title');

    RETURN sql_query;
END $$

-- Create a stored procedure to clear all the tables in the right order.
CREATE OR REPLACE PROCEDURE ClearAllRolesAndPermissions()
BEGIN
    DELETE FROM permission_conflicts;
    DELETE FROM application;
    DELETE FROM role_permissions;
    DELETE FROM permissions;
    DELETE FROM roles;
END $$

-- Create a stored procedure to easily reset all the roles and permissions, using other stored procedures.
CREATE OR REPLACE PROCEDURE ResetAllRolesAndPermissions()
BEGIN
    CALL ClearAllRolesAndPermissions();
    CALL InitAllRolesAndPermissions();
END $$


DELIMITER ;
