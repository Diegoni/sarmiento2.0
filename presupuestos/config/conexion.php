<?php
header('Content-Type: text/html; charset=utf-8');
$db['hostname'] = 'localhost';
$db['username'] = 'root';
$db['password'] = '';
$db['database'] = 'sarmiento2.0';
$db['charset'] = 'utf8';

$conn = mysqli_connect($db['hostname'], $db['username'], $db['password'], $db['database']);
$conn->set_charset($db['charset']);
?>
