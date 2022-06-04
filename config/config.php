<?php
define('DB_NAME', 'blog');
define('DB_USER', 'root');
define('DB_PASS', '');
define('HOST', 'localhost');

$options = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
);

$pdo = new PDO('mysql:host=' . HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS, $options);
