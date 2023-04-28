<?php
date_default_timezone_set('America/Santiago');

$_host = 'localhost';
$_user = 'root';
$_pass = '';
$_db = 'db_votaciones';
$_conn = mysqli_connect('p:' . $_host, $_user, $_pass, $_db);

if (!$_conn){
    echo 'Error: No se pudo conectar a MySQL.' . PHP_EOL;
    echo 'errno de depuración: ' . mysqli_connect_errno() . PHP_EOL;
    echo 'error de depuración: ' . mysqli_connect_error() . PHP_EOL;
    exit;
}
?>
