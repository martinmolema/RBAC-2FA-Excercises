<?php
session_start();
$_SESSION['2fa-checked'] = false;

header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
header(401);
