<?php 
try {
    $host = 'localhost';
    $dbname = 'test';
    $user = 'root';
    $pass = '';
    $DBH = mysqli_connect($host, $user, $pass, $dbname);
} catch (\Throwable $e) {
    die('Error: ' . $e->getMessage());
}

?>