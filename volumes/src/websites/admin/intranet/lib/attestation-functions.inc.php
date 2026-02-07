<?php

include_once '../../shared/lib/ldap_constants.inc.php';
include_once '../../shared/lib/ldap_support.inc.php';

function collectAllUsersAndGroupMemberships(): array
{
  $ldap_conn = ConnectAndCheckLDAP();
  $roles_dn  = "ou=roles,dc=NHLStenden,dc=com";
  $base_dn   = "dc=NHLStenden,dc=com";

// Fetch all groups
  $group_search = ldap_search($ldap_conn, $roles_dn, "(objectClass=groupOfUniqueNames)");
  $groups       = ldap_get_entries($ldap_conn, $group_search);

// Fetch all users
  $user_search = ldap_search($ldap_conn, $base_dn, "(objectClass=inetOrgPerson)");
  $users       = ldap_get_entries($ldap_conn, $user_search);

  $header = ['CN', 'SN', 'UID', 'DN']; //  "<tr><th>CN</th><th>SN</th><th>UID</th><th>Last DN Part</th>";
  foreach ($groups as $group) {
    if (isset($group['cn'][0])) {
      $header[] = $group['cn'][0];
    }
  }

// Prepare the report
  $report = [];
  foreach ($users as $user) {
    if (isset($user['cn'][0])) {
      $user_dn_parts = explode(',', $user['dn']);
      $nrOfParts = count($user_dn_parts);
      unset($user_dn_parts[$nrOfParts - 1]);
      unset($user_dn_parts[$nrOfParts - 2]);
      unset($user_dn_parts[0]);
      $user_dn_parts = array_reverse($user_dn_parts);
      $last_dn_part  = implode(' / ', $user_dn_parts);

      $user_info = [
        'CN' => $user['cn'][0],
        'SN' => $user['sn'][0],
        'UID' => $user['uid'][0] ?? '',
        'Last DN Part' => $last_dn_part
      ];

      // Check group membership
      foreach ($groups as $group) {
        if (isset($group['cn'][0])) {
          $group_name             = $group['cn'][0];
          $user_info[$group_name] = in_array($user['dn'], $group['uniquemember']) ? 'X' : '';
        }
      }

      $report[] = $user_info;
    }
  }

// Close the LDAP connection
  ldap_unbind($ldap_conn);

  return [$header,$report ];
}
function getRolePermissionCrossTable($pdo) {
  $sql = "SELECT GenerateRolePermissionCrossTable() AS query";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $query = $row['query'];

  $header = [];
  $report = [];

  $stmt = $pdo->prepare($query);
  $stmt->execute();

  for ($i = 0; $i < $stmt->columnCount(); $i++) {
    $col = $stmt->getColumnMeta($i);
    $header[] = $col['name'];
  }

  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $report[] = array_map(function($x) {
      $val = $x;
      if ($x === 0 ) {
        $val = '';
      }
      elseif ($x === 1) {
        $val = 'X';
      }
      return $val;
    }, $row);

  }

  return [$header, $report];
}