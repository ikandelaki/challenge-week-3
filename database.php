<?php
// Connection with database
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=anime_list', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>