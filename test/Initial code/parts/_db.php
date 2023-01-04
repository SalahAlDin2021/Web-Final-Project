<?php
session_start();
$host = '127.0.0.1';
$db = 'finalproject';
$user = 'root';
$passwd = '';
$cs = "mysql:host=$host;dbname=$db";
try {
	$pdo = new PDO($cs, $user, $passwd);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (\PDOException $e) {
	var_dump($e);
}
