<?php
include_once '../../../shared/lib/login-session.inc.php';

$doc_root = $_SERVER["DOCUMENT_ROOT"];

require  $doc_root . '/vendor/autoload.php';

use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;


include_once '../../../shared/lib/RBACSupport.php';

$rbac = checkLoginOrFail([Permission_Admin_Panel]);;
$userDN = $rbac->userDN;
$userID = $rbac->username;
$google2fa = new Google2FA();

if (empty($user['labeledURI'])) {
    $secret = $google2fa->generateSecretKey();
    setUser2faToken($userDN, $secret);
}
else {
    die("2fa already setup");
}
$qrText = $google2fa->getQRCodeUrl('NHL Stenden/RBAC Demo', $userID, $secret);

$renderer = new ImageRenderer(new RendererStyle(200), new SvgImageBackEnd());
$writer = new Writer($renderer);
$qrCode = $writer->writeString($qrText);

$imgData = "data:image/svg+xml;base64," . base64_encode($qrCode);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Setup 2FA</title>
</head>
<body>

</body>
</html>
<h1>Instellen van de tweede factor authenticatie</h1>
<h2>Scan deze QR-code in Google Authenticator</h2>
<img src="<?= $imgData ?>" />
<p>Secret: <?= $secret ?> </p>
<form method="POST" action="verify-2fa.php">
    <input type="text" name="code" placeholder="2FA code" required>
    <button type="submit">Verifiëren</button>
</form>
