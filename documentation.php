<?php

require_once __DIR__ . '/vendor/autoload.php';
use eustasy\Authenticatron;

$issuer = 'Documentation Example';
// Only accept a string, so ?secret[]= can't cause a TypeError.
$secretFromQuery = ! empty($_GET['secret']) && is_string($_GET['secret']);
if ($secretFromQuery) {
    $secret = $_GET['secret'];
} else {
    $secret = Authenticatron::makeSecret();
}

if (! $secret) {
    $secret = 'AUTHENTICATRON23';
}

?>
<!DOCTYPE html>
<html lang="en">
<?php include __DIR__ . '/partials/head.html'; ?>
<body>
<?php
include __DIR__ . '/partials/header.html';
include __DIR__ . '/partials/documentation.html';
include __DIR__ . '/partials/footer.html';
?>
</body>
</html>
