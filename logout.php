<?php
// Session sudah dimulai di routes/web.php
session_destroy();
header("Location: /login");
exit();
?>
