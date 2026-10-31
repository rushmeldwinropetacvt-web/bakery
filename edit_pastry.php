<?php
include 'auth.php';
include 'db.php';

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $user_id = $_SESSION['user_id'];

    $sql = "UPDATE pastries SET name = ?, price = ?, last_updated_by = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $price, $user_id, $id]);

    header("Location: index.php");
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM pastries WHERE id = ?");
$stmt->execute([$id]);
$pastry = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Pastry</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Edit Pastry</h1>

    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $pastry['id']; ?>">
        <input type="text" name="name" value="<?php echo $pastry['name']; ?>" required>
        <input type="number" step="0.01" name="price" value="<?php echo $pastry['price']; ?>" required>
        <button type="submit" name="update">Update</button>
    </form>

    <a href="index.php">Back</a>
</body>
</html>
