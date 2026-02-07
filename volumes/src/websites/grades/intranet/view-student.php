<?php
include_once '../../shared/lib/RBACSupport.php';
include_once '../../shared/partials/header.php';
include_once '../../shared/partials/my-ldap-info.php';
include_once '../../shared/lib/login-session.inc.php';

$rbac = checkLoginOrFail([Permission_Grades_Read_StudentDetails]);

$searchResults  = [];
$studentDetails = null;


if (isset($_POST) && isset($_GET['search'])) {
  $studentToSearch = htmlspecialchars($_POST["studentName"],);;

  $lnk      = ConnectAndCheckLDAP();
  $students = SearchStudentByName($lnk, $studentToSearch);


  $nrOfItems = $students["count"];
  for ($i = 0; $i < $nrOfItems; $i++) {
    $student    = $students[$i];
    $oneStudent = [];
    $nrOfFields = $student["count"];

    for ($j = 0; $j < $nrOfFields; $j++) {
      $key   = $student[$j];
      $item  = $student[$key];
      $value = $item[0];

      $oneStudent[$key] = $value;
    }
    $searchResults[] = $oneStudent;
  }
} else if (isset($_GET['id'])) {
  $id = $_GET['id'];
  if (!is_string($id)) {

    http_response_code(406);
    die();
  }
  $userId         = htmlspecialchars($id);
  $lnk            = ConnectAndCheckLDAP();
  $studentDetails = SearchStudentByUID($lnk, $userId);
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cijferadministratie | Student gegevens</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="css/header.css" rel="stylesheet">
    <link href="css/globals.css" rel="stylesheet">
    <link href="css/view-student.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../favicon.png">
</head>
<body>
<article>
  <?php echo showheader(Websites::WEBSITE_GRADES, basename(__FILE__), $rbac) ?>
    <section class="search">
        <form method="post" action="view-student.php?search">
            <label>Studentname:
                <input name="studentName"
                       type="text" <?php if (isset($studentToSearch)) { ?> value="<?php echo $studentToSearch ?>" <?php } ?>>
            </label>
            <button type="submit">Search!</button>
        </form>
    </section>
  <?php if (count($searchResults) > 0) { ?>
      <section class="results">
          <table>
              <thead>
              <tr>
                  <th>CN</th>
                  <th>Username</th>
              </tr>
              </thead>
              <tbody>
              <?php foreach ($searchResults as $student) {
                $uid = $student['uid'];
                $cn  = $student['cn']; ?>
                  <tr>
                      <td><a href="/intranet/view-student.php?id=<?= $uid ?>"><?= $cn ?></a></td>
                      <td><?= $student['uid'] ?></td>
                  </tr>
              <?php } ?>
              </tbody>
              <tfoot>
              <tr>
                  <td colspan="2"><?= count($searchResults) ?> Studenten gevonden</td>
              </tr>
              </tfoot>
          </table>
      </section>
  <?php } ?>
  <?php
  if ($studentDetails !== null) {
    echo GenerateSectionForMyLdapInfo($studentDetails);
  }
  ?>
</article>
</body>
</html>
