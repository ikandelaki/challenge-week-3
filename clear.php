<?php

require "database.php";
$statement_delete = $pdo->prepare("DELETE FROM animes");
$statement_delete->execute();
header('Location: index.php');

?>