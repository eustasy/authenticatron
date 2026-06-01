<?php

require_once __DIR__ . '/vendor/autoload.php';

$accountName = 'John Smith';
$issuer = 'Authenticatron Example Page';
if (! empty($_POST['secondfactor_secret'])) {
    $secret = $_POST['secondfactor_secret'];
} elseif (! empty($_GET['secret'])) {
    $secret = $_GET['secret'];
}

?>
<!DOCTYPE html>
<html lang="en">
<?php include __DIR__ . '/partials/head.html'; ?>
<body>
<?php
include __DIR__ . '/partials/header.html';
include __DIR__ . '/partials/index.html';
include __DIR__ . '/partials/footer.html';
?>
</body>
</html>
