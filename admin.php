<?php
session_start();
include_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_id = $_SESSION['user_id'];
    $date = $_POST['date'];
    $type = $_POST['type'];
    $quantity = $_POST['quantity'];

    $query = "INSERT INTO waste_records (admin_id, date, type, quantity) VALUES (:admin_id, :date, :type, :quantity)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':admin_id', $admin_id);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':type', $type);
    $stmt->bindParam(':quantity', $quantity);

    if ($stmt->execute()) {
        echo "<script>alert('Record added successfully!');</script>";
    } else {
        echo "<script>alert('Error adding record.');</script>";
    }
}

$query = "SELECT * FROM waste_records WHERE admin_id = :admin_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':admin_id', $_SESSION['user_id']);
$stmt->execute();
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Admin Dashboard</title>
    
</head>
<body>
    <div class="container mt-5">
        <h2>Admin Dashboard</h2>
        <form action="" method="post">
            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" class="form-control" id="date" name="date" required>
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Type</label>
                <input type="text" class="form-control" id="type" name="type" required>
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity (kg)</label>
                <input type="number" step="0.01" class="form-control" id="quantity" name="quantity" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Record</button>
        </form>

        <h3 class="mt-5">Waste Disposal Records</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Quantity (kg)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($records as $record): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($record['date']); ?></td>
                        <td><?php echo htmlspecialchars($record['type']); ?></td>
                        <td><?php echo htmlspecialchars($record['quantity']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
