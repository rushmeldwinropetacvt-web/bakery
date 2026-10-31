<?php
include 'auth.php';
include 'db.php';

if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO pastries (name, price, added_by, last_updated_by) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $price, $user_id, $user_id]);

    header("Location: index.php");
    exit();
}

$sql = "SELECT pastries.*, a.username AS added_name, u.username AS updated_name
        FROM pastries
        LEFT JOIN users a ON pastries.added_by = a.id
        LEFT JOIN users u ON pastries.last_updated_by = u.id
        ORDER BY pastries.id DESC";
$stmt = $pdo->query($sql);
$pastries = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pastries</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <p>Logged in as <b><?php echo $_SESSION['username']; ?></b> | <a href="logout.php">Logout</a></p>

    <h1>Pastries</h1>

    <form method="POST">
        <input type="text" name="name" placeholder="Pastry Name" required>
        <input type="number" step="0.01" name="price" placeholder="Price" required>
        <button type="submit" name="add">Add Pastry</button>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price</th>
            <th>Added By</th>
            <th>Last Updated By</th>
            <th>Last Updated</th>
            <th>Action</th>
        </tr>
        <?php foreach ($pastries as $row) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['price']; ?></td>
            <td><?php echo $row['added_name']; ?></td>
            <td><?php echo $row['updated_name']; ?></td>
            <td><?php echo $row['last_updated']; ?></td>
            <td>
                <a href="ingredients.php?pastry_id=<?php echo $row['id']; ?>">Ingredients</a> |
                <a href="edit_pastry.php?id=<?php echo $row['id']; ?>">Edit</a> |
                <a href="delete_pastry.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Delete this pastry?')">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
