<?php
session_start();
session_destroy();
setcookie("login");
setcookie("password");
header("Location:index.php");
