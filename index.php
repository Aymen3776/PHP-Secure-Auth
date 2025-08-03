<?php
require_once 'functions/Auth.php';
if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}
else {
    header('Location: profile.php');
    exit();
}
?>