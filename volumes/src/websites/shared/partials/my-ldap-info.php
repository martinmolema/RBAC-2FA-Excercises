<?php

function GenerateSectionForMyLdapInfoFromRBAC(RBACSupport $rbac): string|null
{
   return    GenerateSectionForMyLdapInfo($rbac->userInfoLDAP);
}

function GenerateSectionForMyLdapInfo(array $userInfoLDAP): string|null
{

  $items = [
    "Distinguished Name" => $userInfoLDAP['dn'],
    "Volledige naam" => $userInfoLDAP['cn'],
    "Voornaam" => $userInfoLDAP['givenname'],
    "Achternaam" => $userInfoLDAP['sn'],
    "Username" => $userInfoLDAP['uid'],
    "Medewerkernummer" => $userInfoLDAP['employeenumber'],
    "Type medewerker" => $userInfoLDAP['employeetype'],
    "Organisatie" => $userInfoLDAP['o'],
    "Postcode" => $userInfoLDAP['postalcode'],
    "Kamernummer" => $userInfoLDAP['roomnumber'],
    "2FA Enabled" => isset($userInfoLDAP['labeleduri'])  ? "Ja": "Nee",
  ];

  $jpegPhoto = '';

  if (isset($userInfoLDAP['jpegphoto'])) {
    $jpegPhoto = base64_encode($userInfoLDAP['jpegphoto']);
  }
  else {
    $path = '/var/www/shared/partials/default-user.jpg';

    if (file_exists($path)) {
      $imageData = file_get_contents($path);
      $jpegPhoto = base64_encode($imageData);
    }
  }

  $result = '<section class="my-info"><table>';
  foreach ($items as $key => $item) {

    $classNameExtra = preg_replace('/\s+/', '', $key);;

    $result .= "<tr><td class='key {$classNameExtra}'>$key:</td><td class='value {$classNameExtra}'>$item</td></tr>";
  }
  $result .= "</table>";
  $result .= "<div><img src='data:image/jpeg;base64,$jpegPhoto' /></div>";
  $result .= "</section>";

  return $result;
}


function GenerateSectionForMyLdapRoles(RBACSupport $rbac): string|null
{
  $groups = implode("\n", array_map(function ($group) {
    $groupParts = explode(",", $group);
    $rolename   = explode("=", $groupParts[0])[1];
    return "<li>$rolename</li>";
  }, $rbac->groups));

  return <<< SECTION_MY_LDAP_ROLES
    <section class="ldap-groups">
    <header><h3>Rollen</h3></header>
        
        <ul>$groups</ul>
   </section>

SECTION_MY_LDAP_ROLES;
}


function GenerateSectionForMyLdapPermissions(RBACSupport $rbac): string|null
{
  $permissions = [...$rbac->permissions];
  usort($permissions, function ($a, $b) {
    $roleCompare =  strcmp($a['role'], $b['role']);
    if ($roleCompare !==0 ) { return $roleCompare; }
    return strcmp($a['application'], $b['application']);
  });
  $permissions_html = implode("\n", array_map(function ($permission) {
    $role = $permission['role'];
    $permissionName = $permission['permission'];
    $application = $permission['application'];

    return "<tr></tr><td>$role</td><td>$application</td><td>$permissionName</td></tr>\n";

  }, $permissions));

  return <<< SECTION_MY_LDAP_ROLES
    <section class="ldap-permissions">
        <h3>Permissies</h3>
        <table>
        <thead>
          <th>Rol</th>
          <th>Applicatie</th>
          <th>Permissie</th>
      </thead>
      <tbody>$permissions_html</tbody>      
      </table>
   </section>

SECTION_MY_LDAP_ROLES;
}
