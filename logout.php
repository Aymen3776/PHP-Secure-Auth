<?php
require_once 'functions/Auth.php';
logOut();
header('Location: login.php');
exit();
?>