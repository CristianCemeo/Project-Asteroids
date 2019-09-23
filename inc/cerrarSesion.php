<?php

session_name("asteroids");
session_start();
session_destroy();
header("Location: ../index.php");
exit();

?>