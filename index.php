<?php
// Redirect to public/index.php
header('Location: public/index.php' . ($_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : ''));
exit;
?> 