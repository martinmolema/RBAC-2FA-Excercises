export const USER_ROLE_student = 'student';
export const USER_ROLE_teacher = 'teacher';
export const USER_ROLE_medewerker_ICT_Support = 'medewerker ICT Support';
export const USER_ROLE_medewerker_ICT_AuthorisationManager = 'ICT AUthorisation Manager';
export const USER_ROLE_medewerker_marketing = 'medewerker marketing';
export const USER_ROLE_marketing_manager = 'marketing manager';
export const USER_ROLE_medewerker_HRM = 'medewerker HRM';


export type USER_ROLES =
    typeof USER_ROLE_student
    | typeof USER_ROLE_teacher
    | typeof USER_ROLE_medewerker_ICT_Support
    | typeof USER_ROLE_medewerker_ICT_AuthorisationManager
    | typeof USER_ROLE_medewerker_marketing
    | typeof USER_ROLE_marketing_manager
    | typeof USER_ROLE_medewerker_HRM
    ;
export type USER_TYPES = 'staff' | 'student';

export class DockerWebUser {
    username: string;
    password: string;
    naam: string;
    dn: string;
    role: USER_ROLES;
    type: USER_TYPES

    constructor(username: string, password: string, naam: string, dn: string, role: USER_ROLES, type: USER_TYPES) {
        this.username = username;
        this.password = password;
        this.naam = naam;
        this.dn = dn;
        this.role = role;
        this.type = type;
    }
}

export const USER_MARKETING_NORMAL = new DockerWebUser('dwillems', 'Test1234!', 'Daan Willems',
    'cn=Daan Willems,ou=Marketing,ou=Staff,dc=NHLStenden,dc=com', USER_ROLE_medewerker_marketing, 'staff'
);

export const USER_MARKETING_MANAGER = new DockerWebUser('yschipper', 'Test1234!', 'Yara Schipper',
    'cn=Yara Schipper,ou=Marketing,ou=Staff,dc=NHLStenden,dc=com', USER_ROLE_marketing_manager, 'staff'
);

export const USER_HRM = new DockerWebUser('kmulder', 'Test1234!', 'Kevin Mulder',
    'cn=Kevin Mulder,ou=HRM,ou=Staff,dc=NHLStenden,dc=com', USER_ROLE_medewerker_HRM, 'staff'
);

export const  USER_ICT = new DockerWebUser(
    'awillems', 'Test1234!', 'Anouk Willems', 'cn=Anouk Willems,ou=ICT Support,ou=Staff,dc=NHLStenden,dc=com',
    USER_ROLE_medewerker_ICT_Support, 'staff'
);
export const USER_ICT_AUTHMGR = new DockerWebUser(
    'jmeijer2', 'Test1234!', 'Jeroen Meijer', 'cn=Jeroen Meijer,ou=ICT Support,ou=Staff,dc=NHLStenden,dc=com',
    USER_ROLE_medewerker_ICT_AuthorisationManager, 'staff'
);

export const USER_TEACHER = new DockerWebUser('ddekker', 'Test1234!', 'Diana Dekker', 'cn=Diana Dekker,ou=Teachers,ou=Opleidingen,dc=NHLStenden,dc=com', USER_ROLE_teacher, 'staff')
export const USER_STUDENT = new DockerWebUser('cvandijk', 'Test1234!', 'Cas van Dijk', 'cn=Cas van Dijk,ou=Students,ou=Opleidingen,dc=NHLStenden,dc=com', USER_ROLE_student, 'student')

export const ALL_TEST_USERS = [
    USER_MARKETING_NORMAL, USER_ICT, USER_HRM, USER_TEACHER, USER_STUDENT, USER_MARKETING_MANAGER,USER_ICT_AUTHMGR
];


export type NavigationItem = {
    route: string;
    title: string;
};

export function GetRoutesInHeaderForWebsite(role: USER_ROLES): NavigationItem[] {
    console.log(`Getting routes in header for ${role}`);
    const navigationGradesTeacher: NavigationItem[] = [
        {route: 'new-list.php', title: 'Nieuwe cijferlijst'},
        {route: 'view-student.php', title: 'Bekijk student'},
        {route: 'approve-list.php', title: 'Lijsten goedkeuren'},
    ];

    const navigationGradesStudent: NavigationItem[] = [
        {route: 'my-grades.php', title: 'Cijfers'},
        {route: 'my-data.php', title: 'Mijn gegevens'},
    ];

    const navigationAdminNormal: NavigationItem[] = [
        {route: 'logging.php', title: 'Apache Logfiles'},
        {route: 'audittrail.php', title: 'Audit trail'},
        {route: 'attestation_users.php', title: 'Attestation - Gebruikers'},
        {route: 'attestation_roles.php', title: 'Attestation - Rollen'},
        {route: 'view-user.php', title: '🔍Zoeken...'},
    ];

    const navigationAdminAuthorisationManager: NavigationItem[] = [
        {route: 'logging.php', title: 'Apache Logfiles'},
        {route: 'audittrail.php', title: 'Audit trail'},
        {route: 'attestation_users.php', title: 'Attestation - Gebruikers'},
        {route: 'attestation_roles.php', title: 'Attestation - Rollen'},
        {route: 'manage_roles.php', title: 'Rollen'},
        {route: 'show-sods.php', title: 'Functiescheiding'},
        {route: 'AssignUserToRoleForm.php', title: 'Autorisatie aanvraag'},
        {route: 'view-user.php', title: '🔍Zoeken...'},
    ];

    const navigationMarketingNormal: NavigationItem[] = [
        {route: 'new-campaign.php', title: 'Nieuwe campagne'},
        {route: 'read-campaign.php', title: 'Bekijk campagne'},
    ];

    const navigationMarketingManager: NavigationItem[] = [
        {route: 'read-campaign.php', title: 'Bekijk campagne'},
        {route: 'approve-campaign.php', title: 'Campagne goedkeuren'},
        {route: 'delete-campaign.php', title: 'Verwijder campagne'},
    ];
    const navigationHRM: NavigationItem[] = [];


    const tables = new Array<USER_ROLES>();
    tables[USER_ROLE_student] = navigationGradesStudent;
    tables[USER_ROLE_marketing_manager] = navigationMarketingManager;
    tables[USER_ROLE_teacher] = navigationGradesTeacher;
    tables[USER_ROLE_medewerker_HRM] = navigationHRM;
    tables[USER_ROLE_medewerker_marketing] = navigationMarketingNormal;
    tables[USER_ROLE_medewerker_ICT_Support] = navigationAdminNormal;
    tables[USER_ROLE_medewerker_ICT_AuthorisationManager] = navigationAdminAuthorisationManager;

    return tables[role];
}