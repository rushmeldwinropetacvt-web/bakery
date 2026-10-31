<?php
include 'auth.php';
include 'db.php';

$pastry_id = $_GET['pastry_id'];

$stmt = $pdo->prepare("SELECT * FROM pastries WHERE id = ?");
$stmt->execute([$pastry_id]);
$pastry = $stmt->fetch(PDO::FETCH_ASSOC);

if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $quantity = $_POST['quantity'];
    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO ingredients (pastry_id, name, quantity, added_by, last_updated_by) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$pastry_id, $name, $quantity, $user_id, $user_id]);

    header("Location: ingredients.php?pastry_id=" . $pastry_id);
    exit();
}

$sql = "SELECT ingredients.*, a.username AS added_name, u.username AS updated_name
        FROM ingredients
        LEFT JOIN users a ON ingredients.added_by = a.id
        LEFT JOIN users u ON ingredients.last_updated_by = u.id
        WHERE ingredients.pastry_id = ?
        ORDER BY ingredients.id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$pastry_id]);
$ingredients = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Ingredients</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <p>Logged in as <b><?php echo $_SESSION['username']; ?></b> | <a href="logout.php">Logout</a></p>

    <h1>Ingredients of <?php echo $pastry['name']; ?></h1>
    <a href="index.php">Back to Pastries</a>

    <form method="POST">
        <input type="text" name="name" placeholder="Ingredient Name" required>
        <input type="text" name="quantity" placeholder="Quantity" required>
        <button type="submit" name="add">Add Ingredient</button>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Quantity</th>
            <th>Added By</th>
            <th>Last Updated By</th>
            <th>Last Updated</th>
            <th>Action</th>
        </tr>
        <?php foreach ($ingredients as $row) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['quantity']; ?></td>
            <td><?php echo $row['added_name']; ?></td>
            <td><?php echo $row['updated_name']; ?></td>
            <td><?php echo $row['last_updated']; ?></td>
            <td>
                <a href="edit_ingredient.php?id=<?php echo $row['id']; ?>">Edit</a> |
                <a href="delete_ingredient.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Delete this ingredient?')">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
