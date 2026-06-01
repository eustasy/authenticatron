<?php

$RandomBytes = function_exists('random_bytes');
$GD = extension_loaded('gd') && function_exists('gd_info');

?>
<!DOCTYPE html>
<html lang="en">
<?php include __DIR__ . '/partials/head.html'; ?>
<body>
<?php
include __DIR__ . '/partials/header.html';
include __DIR__ . '/partials/server.html';
include __DIR__ . '/partials/footer.html';
?>
</body>
</html>
