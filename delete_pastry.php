<?php
include 'auth.php';
include 'db.php';

$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM pastries WHERE id = ?");
$stmt->execute([$id]);

header("Location: index.php");
exit();
?>
