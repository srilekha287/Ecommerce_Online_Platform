<?php
session_start();
include '../includes/db.php'; // Include the database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle the form submission for placing the order
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Fetch the user's cart items
    $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if the cart is not empty
    if (!empty($cart_items)) {
        // Prepare statements for checking, updating, and inserting
        $check_stmt = $conn->prepare("SELECT quantity FROM orders WHERE user_id = ? AND product_id = ?");
        $update_stmt = $conn->prepare("UPDATE orders SET quantity = ? WHERE user_id = ? AND product_id = ?");
        $insert_stmt = $conn->prepare("INSERT INTO orders (user_id, product_id, quantity) VALUES (?, ?, ?)");

        // Loop through each cart item
        foreach ($cart_items as $item) {
            $product_id = $item['product_id'];
            $cart_quantity = $item['quantity'];

            // Check if the product already exists in orders for this user
            $check_stmt->execute([$user_id, $product_id]);
            $existing_order = $check_stmt->fetch(PDO::FETCH_ASSOC);

            if ($existing_order) {
                // Product exists, update the quantity
                $new_quantity = $existing_order['quantity'] + $cart_quantity;
                $update_stmt->execute([$new_quantity, $user_id, $product_id]);
            } else {
                // Product doesn't exist, insert a new row
                $insert_stmt->execute([$user_id, $product_id, $cart_quantity]);
            }
        }

        // Clear the cart after placing the order
        $clear_cart_stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
        $clear_cart_stmt->execute([$user_id]);

        // Redirect to the order confirmation page
        header("Location: order_confirmation.php?confirmed=true");
        exit();
    } else {
        echo "Your cart is empty.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
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
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0px 20px rgba(0, 0, 0, 0.5);
            text-align: center;
        }

        /* Heading Styling */
        h2 {
            font-size: 2.2em;
            color: #2c3e50;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Paragraph Styling */
        p {
            font-size: 1.2em;
            color: #7f8c8d;
            margin-bottom: 30px;
            line-height: 1.5;
        }

        /* Buttons Container */
        .buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }

        /* Button Styling */
        .button {
            display: inline-block;
            padding: 12px 25px;
            background-color: #28a745;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1em;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .button:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }

        /* Secondary Button (Back to Shop) */
        .button.secondary {
            background-color: #2c3e50;
        }

        .button.secondary:hover {
            background-color: #34495e;
        }

        /* Success Icon */
        .success-icon {
            font-size: 3em;
            color: #28a745;
            margin-bottom: 20px;
        }

        /* Error Message */
        .error-message {
            font-size: 1.2em;
            color: #e74c3c;
            margin-bottom: 20px;
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }

            h2 {
                font-size: 1.8em;
            }

            p {
                font-size: 1em;
            }

            .buttons {
                flex-direction: column;
                gap: 15px;
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
        <?php if (isset($_GET['confirmed']) && $_GET['confirmed'] === 'true'): ?>
            <div class="success-icon">✔</div>
            <h2>Order Confirmed!</h2>
            <p>Thank you for your order. Your purchase has been successfully placed.</p>
        <?php elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($cart_items)): ?>
            <p class="error-message">Your cart is empty.</p>
        <?php else: ?>
            <h2>Order Confirmation</h2>
            <p>Please wait while we process your order...</p>
        <?php endif; ?>

        <div class="buttons">
            <a href="../index.php" class="button secondary">Back to Shop</a>
            <?php if (isset($_GET['confirmed'])): ?>
                <a href="my_orders.php" class="button">My Orders</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>