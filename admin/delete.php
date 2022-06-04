<?php
require '../config/config.php';

$statement = $pdo->prepare('DELETE FROM posts WHERE id=' . $_GET['id']);
$statement->execute();
header('Location: index.php');
