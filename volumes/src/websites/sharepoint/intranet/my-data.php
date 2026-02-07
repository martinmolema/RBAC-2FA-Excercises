<?php
include_once '../../shared/lib/RBACSupport.php';
include_once '../../shared/partials/header.php';
include_once '../../shared/partials/my-ldap-info.php';
include_once '../../shared/lib/login-session.inc.php';

$rbac = checkLoginOrFail([Permission_SharePoint_All_Users]);

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Intranet | Mijn gegevens</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="css/globals.css" rel="stylesheet">
    <link href="css/header.css" rel="stylesheet">
    <link href="css/my-data.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../favicon.png">
</head>
<body>
<article>
  <?php
   echo showheader(Websites::WEBSITE_SHAREPOINT,basename(__FILE__), $rbac);
   echo GenerateSectionForMyLdapInfoFromRBAC($rbac);
   echo GenerateSectionForMyLdapRoles($rbac);
   echo GenerateSectionForMyLdapPermissions($rbac);
  ?>
</article>
</body>
</html>
