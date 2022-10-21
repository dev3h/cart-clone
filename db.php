<?php 
try {
    $host = 'localhost';
    $dbname = 'cart_products';
    $user = 'root';
    $pass = '';
    $conn = mysqli_connect($host, $user, $pass, $dbname);
} catch (\Throwable $e) {
    die('Error: ' . $e->getMessage());
}

?>