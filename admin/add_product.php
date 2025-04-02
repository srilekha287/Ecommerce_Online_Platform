<?php
include '../includes/db.php';
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$successMessage = ""; // Initialize the message variable

if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $image = $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], "../images/$image");

    $stmt = $conn->prepare("INSERT INTO products (name, price, description, image) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $price, $description, $image]);
    $successMessage = "Product added successfully!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #2c3e50;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 50%;
            margin: 80px auto 50px; /* Increased top margin to avoid overlap with message */
            background-color: #fff;
            padding: 30px;
            box-shadow: 0 0px 20px rgba(0, 0, 0, 0.4);
            border-radius: 8px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin: 10px 0 5px;
            font-weight: bold;
            color: #555;
        }

        input[type="text"], input[type="number"], textarea, input[type="file"] {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        textarea {
            resize: vertical;
            height: 100px;
        }

        button {
            background-color: #2c3e50;
            color: white;
            padding: 15px;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: rgb(68, 99, 129);
        }

        /* Message Styling */
        .message {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color:rgb(68, 99, 129) ; /* Green background for success */
            color: white;
            text-align: center;
            padding: 15px;
            font-size: 20px;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Close Button Styling */
        .close-btn {
            position: absolute;
            right: 40px;
            background: none;
            border: none;
            color: white;
            font-size: 34px;
            font-weight: bold;
            cursor: pointer;
            padding: 0;
            line-height: 1;
            transition: color 0.3s ease;
        }

        .close-btn:hover {
            color:rgb(245, 0, 0); /* Yellow on hover for visibility */
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }

        .back-link a {
            color: #2c3e50;
            text-decoration: none;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <?php if (!empty($successMessage)): ?>
        <div class="message">
            <?php echo $successMessage; ?>
            <button class="close-btn" onclick="this.parentElement.style.display='none'">×</button>
        </div>
    <?php endif; ?>

    <div class="container">
        <h2>Add Product</h2>
        <form method="POST" enctype="multipart/form-data">
            <label for="name">Product Name:</label>
            <input type="text" name="name" id="name" required>

            <label for="price">Price:</label>
            <input type="number" step="0.01" name="price" id="price" required>

            <label for="description">Description:</label>
            <textarea name="description" id="description" required></textarea>

            <label for="image">Image:</label>
            <input type="file" name="image" id="image" required>

            <button type="submit" name="add_product">Add Product</button>
        </form>

        <div class="back-link">
            <a href="manage_products.php">Back to Manage Products</a>
        </div>
    </div>
</body>
</html>