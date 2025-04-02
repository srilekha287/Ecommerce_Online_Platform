<?php
session_start();
include '../includes/db.php'; // Include the database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch the user's orders from the database
$stmt = $conn->prepare("SELECT o.id, o.product_id, o.quantity, p.name, p.price, p.image FROM orders o JOIN products p ON o.product_id = p.id WHERE o.user_id = ?");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <style>
        /* General Body Styling */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        /* Container Styling */
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0px 20px rgba(0, 0, 0, 0.5);
        }

        /* Heading Styling */
        h2 {
            text-align: center;
            font-size: 2em;
            color: #2c3e50;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Empty Orders Message */
        .no-orders {
            text-align: center;
            font-size: 1.2em;
            color: #7f8c8d;
            padding: 20px;
            background-color: #ecf0f1;
            border-radius: 8px;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        th {
            background-color: #2c3e50;
            color: #fff;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 0.9em;
            letter-spacing: 0.5px;
        }

        td {
            vertical-align: middle;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
            transition: background-color 0.3s ease;
        }

        /* Image Styling */
        td img {
            width: 60px;
            height: auto;
            border-radius: 5px;
            object-fit: cover;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Button Container */
        .buttons {
            margin-top: 30px;
            text-align: center;
        }

        /* Button Styling */
        .button {
            display: inline-block;
            padding: 12px 25px;
            background-color: #2c3e50;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1em;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .button:hover {
            background-color: #34495e;
            transform: translateY(-2px);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            table, th, td {
                font-size: 0.9em;
            }

            td img {
                width: 40px;
            }

            .button {
                padding: 10px 20px;
                font-size: 0.9em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>My Orders</h2>
        
        <?php if (empty($orders)): ?>
            <p class="no-orders">You have not placed any orders yet.</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>Product Image</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                </tr>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><img src="../images/<?= htmlspecialchars($order['image']); ?>" alt="<?= htmlspecialchars($order['name']); ?>"></td>
                        <td><?= htmlspecialchars($order['name']); ?></td>
                        <td>$<?= number_format($order['price'], 2); ?></td>
                        <td><?= htmlspecialchars($order['quantity']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
        
        <div class="buttons">
            <a href="../index.php" class="button">Back to Shop</a>
        </div>
    </div>
</body>
</html>