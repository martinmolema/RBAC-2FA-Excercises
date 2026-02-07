<?php

include_once '../../shared/lib/RBACSupport.php';

enum Websites
{
  case WEBSITE_GRADES;
  case WEBSITE_SHAREPOINT;
  case WEBSITE_MARKETING;
  case WEBSITE_ADMIN;
  case WEBSITE_HRM;

}

function showheader(Websites $forWebsite, string $route, RBACSupport $rbac): string
{
  // $rbac->echoPermissions();
  $navigationGrades     = [
    ['route' => 'my-grades.php', 'permission' => Permission_Grades_Read_Own_Grades, 'title' => 'Cijfers'],
    ['route' => 'my-data.php', 'permission' => Permission_Grades_Show_Self, 'title' => 'Mijn gegevens'],
    ['route' => 'new-list.php', 'permission' => Permission_Grades_Create_Gradelists, 'title' => 'Nieuwe cijferlijst'],
    ['route' => 'view-student.php', 'permission' => Permission_Grades_Read_StudentDetails, 'title' => 'Bekijk student'],
    ['route' => 'approve-list.php', 'permission' => Permission_Grades_Approve_Gradeslist, 'title' => 'Lijsten goedkeuren'],
  ];
  $navigationSharePoint = [
    ['route' => 'my-data.php', 'permission' => Permission_SharePoint_All_Users, 'title' => 'Mijn gegevens'],
    ['route' => 'hrm.php', 'permission' => Permission_SharePoint_HRM, 'title' => 'Medewerkersportaal'],
    ['route' => 'students.php', 'permission' => Permission_SharePoint_StudentTools, 'title' => 'Studenten Portaal'],
    ['route' => 'teachers.php', 'permission' => Permission_SharePoint_TeacherTools, 'title' => 'Docenten Portaal'],
    ['route' => 'http://grades.rbac.docker/intranet', 'permission' => Permission_Grades_BasicAccess, 'title' => 'Cijfers'],
    ['route' => 'http://marketing.rbac.docker/intranet', 'permission' => Permission_Marketing_Read_Campaign, 'title' => 'Marketing'],
    ['route' => 'http://admin.rbac.docker/intranet', 'permission' => Permission_Admin_Panel, 'title' => 'Admin Panel'],
    ['route' => 'http://hrm.rbac.docker/intranet', 'permission' => Permission_HRM_Manage_Employees, 'title' => 'Beheer medewerkers'],
  ];

  $navigationAdmin     = [
    ['route' => 'logging.php', 'permission' => Permission_Admin_Panel, 'title' => 'Apache Logfiles'],
    ['route' => 'audittrail.php', 'permission' => Permission_Admin_Panel, 'title' => 'Audit trail'],
    ['route' => 'attestation_users.php', 'permission' => Permission_AdminPanel_Attestation_Users, 'title' => 'Attestation - Gebruikers'],
    ['route' => 'attestation_roles.php', 'permission' => Permission_AdminPanel_Attestation_Roles, 'title' => 'Attestation - Rollen'],
    ['route' => 'manage_roles.php', 'permission' => Permission_AdminPanel_Manage_RolePermissions, 'title' => 'Rollen'],
    ['route' => 'show-sods.php', 'permission' => Permission_AdminPanel_Manage_RolePermissions, 'title' => 'Functiescheiding'],
    ['route' => 'AssignUserToRoleForm.php', 'permission' => Permission_AdminPanel_AddUserToRole, 'title' => 'Autorisatie aanvraag'],
    ['route' => 'view-user.php', 'permission' => Permission_Admin_Panel, 'title' => '&#x1F50D;Zoeken...'],
  ];
  $navigationMarketing = [
    ['route' => 'new-campaign.php', 'permission' => Permission_Marketing_Create_Campaign, 'title' => 'Nieuwe campagne'],
    ['route' => 'read-campaign.php', 'permission' => Permission_Marketing_Read_Campaign, 'title' => 'Bekijk campagne'],
    ['route' => 'approve-campaign.php', 'permission' => Permission_Marketing_Approve_Campaign, 'title' => 'Campagne goedkeuren'],
    ['route' => 'delete-campaign.php', 'permission' => Permission_Marketing_Delete_Campaign, 'title' => 'Verwijder campagne'],
  ];

  $navigationHRM = [
  ];

  $navHTML = '';

  $useNavigationTable = [];
  $sitename           = '';

  switch ($forWebsite) {
    case Websites::WEBSITE_ADMIN:
      $useNavigationTable = $navigationAdmin;
      $sitename           = 'Admin Panel';
      break;
    case Websites::WEBSITE_SHAREPOINT:
      $useNavigationTable = $navigationSharePoint;
      $sitename           = 'Sharepoint | Intranet';
      break;
    case Websites::WEBSITE_GRADES:
      $useNavigationTable = $navigationGrades;
      $sitename           = 'Cijferadministratie';
      break;
    case Websites::WEBSITE_MARKETING:
      $useNavigationTable = $navigationMarketing;
      $sitename           = 'Marketing';
      break;
    case Websites::WEBSITE_HRM:
      $useNavigationTable = $navigationHRM;
      $sitename           = 'Human Resource Management';
      break;
  }

  $hasPermissions = false;

  foreach ($useNavigationTable as $nav) {
    if ($rbac->has($nav['permission'])) {
      $hasPermissions = true;
      $isActiveRoute  = $nav['route'] == $route;

      $title = $nav['title'];
      $route = $nav['route'];

      $html = "<a href='$route' aria-label='$title'" ;
      $html .= $isActiveRoute ? 'class="active" ' : '';
      $html .= '>';
      $html .= $nav['title'];
      if (str_contains($nav['route'], 'http://')) {
        $html .= '<span class="material-icons icon-small" >open_in_new</span>';
      }
      $html .= '</a>';

      $navHTML .= $html;
    }
  }
  $fullname  = $rbac->userInfoLDAP['cn'];
  $jpegPhoto = '';

  if (isset($rbac->userInfoLDAP['jpegphoto'])) {
    $jpegPhoto = base64_encode($rbac->userInfoLDAP['jpegphoto']);
  } else {
    $path = '/var/www/shared/partials/default-user.jpg';

    if (file_exists($path)) {
      $imageData = file_get_contents($path);
      $jpegPhoto = base64_encode($imageData);
    }
  }


  $has2fa = isset($_SESSION['2fa-checked']) ? "2FA" : "";

  $result = <<< EOF_HEADER
<section class="navigation-header" role="navigation">
    <header>
        <h1>
            <a href="http://sharepoint.rbac.docker/intranet"><span class="home">&#127968;</span></a>
            <a href="/intranet" aria-label="home">$sitename</a>
        </h1>
        <h2>Welkom $fullname</h2>
        
        <p class="right">
          <span class="logout"><a href="http://portal.rbac.docker/logout.php">Logout</a></span>
          <img src="data:image/jpeg;base64, $jpegPhoto" alt="Gebruikersafbeelding">
          <span class="has2fa-label">$has2fa</span>
        </p>
    </header>
EOF_HEADER;

  if ($hasPermissions) {
    $result .= <<< EOF_NAVIGATION
    <nav> $navHTML  </nav>
EOF_NAVIGATION;
  }
  $result .= <<< EOF_CLOSEHTML
    </section>
EOF_CLOSEHTML;
  return $result;
}

?>