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

    // Fetch the product details
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the product exists
    if (!$product) {
        echo "Product not found.";
        exit();
    }
}

// Handle the form submission for updating product details
if (isset($_POST['update_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $image = $_FILES['image']['name'];

    // If a new image is uploaded, move it to the images directory
    if ($image) {
        move_uploaded_file($_FILES['image']['tmp_name'], "../images/$image");
        $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, description = ?, image = ? WHERE id = ?");
        $stmt->execute([$name, $price, $description, $image, $product_id]);
    } else {
        // If no new image is uploaded, just update the other fields
        $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, description = ? WHERE id = ?");
        $stmt->execute([$name, $price, $description, $product_id]);
    }

    echo "Product updated successfully!";
    header("Location: manage_products.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <style>
        /* General Body and Layout */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        /* Container for the form */
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Headings */
        h2 {
            text-align: center;
            color: #333;
        }

        /* Form elements */
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
            font-size: 18px;
        }

        input[type="text"],
        input[type="number"],
        textarea,
        input[type="file"] {
            width: 97%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 18px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #2c3e50;
            border: none;
            color: white;
            font-weight: 500;
            font-size: 26px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            height: 30%;
        }

        button:hover {
            background-color:rgb(74, 105, 136);
        }

        /* Message styles */
        .message {
            text-align: center;
            color: green;
            font-size: 18px;
            margin-top: 20px;
        }

        .error-message {
            text-align: center;
            color: red;
            font-size: 18px;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <h2>Edit Product</h2>
    <form method="POST" enctype="multipart/form-data">
        <label for="name">Product Name:</label>
        <input type="text" name="name" id="name" value="<?= htmlspecialchars($product['name']); ?>" required>

        <label for="price">Price:</label>
        <input type="number" step="0.01" name="price" id="price" value="<?= htmlspecialchars($product['price']); ?>"
            required>

        <label for="description">Description:</label>
        <textarea name="description" id="description"
            required><?= htmlspecialchars($product['description']); ?></textarea>

        <label for="image">Image (leave blank to keep current image):</label>
        <input type="file" name="image" id="image">

        <button type="submit" name="update_product">Update Product</button>
    </form>
</body>

</html>