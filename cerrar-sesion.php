<?php

session_start();

$_SESSION = [];

echo "<script type='text/javascript'>
window.location = '/'
</script>";

exit;

?>