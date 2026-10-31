<?php
include 'auth.php';
include 'db.php';

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT pastry_id FROM ingredients WHERE id = ?");
$stmt->execute([$id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$pastry_id = $row['pastry_id'];

$stmt = $pdo->prepare("DELETE FROM ingredients WHERE id = ?");
$stmt->execute([$id]);

header("Location: ingredients.php?pastry_id=" . $pastry_id);
exit();
?>
