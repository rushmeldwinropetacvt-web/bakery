<?php
include 'auth.php';
include 'db.php';

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $pastry_id = $_POST['pastry_id'];
    $name = $_POST['name'];
    $quantity = $_POST['quantity'];
    $user_id = $_SESSION['user_id'];

    $sql = "UPDATE ingredients SET name = ?, quantity = ?, last_updated_by = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $quantity, $user_id, $id]);

    header("Location: ingredients.php?pastry_id=" . $pastry_id);
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM ingredients WHERE id = ?");
$stmt->execute([$id]);
$ingredient = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Ingredient</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Edit Ingredient</h1>

    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $ingredient['id']; ?>">
        <input type="hidden" name="pastry_id" value="<?php echo $ingredient['pastry_id']; ?>">
        <input type="text" name="name" value="<?php echo $ingredient['name']; ?>" required>
        <input type="text" name="quantity" value="<?php echo $ingredient['quantity']; ?>" required>
        <button type="submit" name="update">Update</button>
    </form>

    <a href="ingredients.php?pastry_id=<?php echo $ingredient['pastry_id']; ?>">Back</a>
</body>
</html>
