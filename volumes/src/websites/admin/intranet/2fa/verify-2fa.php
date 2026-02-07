<?php
include_once '../../../shared/lib/login-session.inc.php';

$doc_root = $_SERVER["DOCUMENT_ROOT"];
require  $doc_root . '/vendor/autoload.php';

use PragmaRX\Google2FA\Google2FA;
include_once '../../../shared/lib/RBACSupport.php';
include_once '../../../shared/lib/ldap_support.inc.php';


$rbac = checkLoginOrFail([Permission_Admin_Panel]);;
$userDN = $rbac->userDN;
$google2fa = new Google2FA();
$error = '';
$secret = getUser2faToken($userDN);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['code'] ?? '';
    try {
        if ($google2fa->verifyKey($secret, $code, 2)) {
            $_SESSION['2fa-checked'] = true;
            header("Location: /intranet");
            exit;
        } else {
            $error = "<h2 class='error'>Ongeldige code</h2>";
        }
    } catch (\PragmaRX\Google2FA\Exceptions\IncompatibleWithGoogleAuthenticatorException $e) {
        die('IncompatibleWithGoogleAuthenticatorException');
    } catch (\PragmaRX\Google2FA\Exceptions\InvalidCharactersException $e) {
        die('InvalidCharactersException');
    } catch (\PragmaRX\Google2FA\Exceptions\SecretKeyTooShortException $e) {
        die('SecretKeyTooShortException');
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Verify 2FA</title>
</head>
<body>

</body>
</html>
<h2>2FA Verificatie</h2>
<?= $error ?>
<form method="POST">
    <input type="text" name="code" placeholder="2FA code" required>
    <button type="submit">Verifiëren</button>
</form>
