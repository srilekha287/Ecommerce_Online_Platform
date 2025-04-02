<?php
include '../includes/db.php';
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Check if the product ID is set
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $cart_stmt = $conn->prepare("DELETE FROM cart WHERE product_id = ?");
    $cart_stmt->execute([$product_id]);
    // Delete the product from the database
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$product_id]);

    echo "Product deleted successfully!";
    header("Location: manage_products.php");
    exit();
} else {
    echo "No product ID specified.";
    exit();
}
?>