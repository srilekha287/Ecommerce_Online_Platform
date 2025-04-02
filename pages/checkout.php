

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        /* General Body and Layout */
body {
    font-family: 'Arial', sans-serif;
    background-color:rgb(241, 239, 239);
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
    box-shadow: 0 0px 20px rgba(0, 0, 0, 0.5);
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
}

input[type="text"],
input[type="email"],
input[type="number"],
input[type="tel"],
textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
}

button {
    width: 100%;
    padding: 20px;
    background-color: #2c3e50;
    border: none;
    color: white;
    font-size: 16px;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
}

button:hover {
    background-color:rgb(69, 98, 126);
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
<div class="container">
    <h2>Confirming Order</h2>
    <form method="POST" action="order_confirmation.php">
        <label for="name">Full Name:</label>
        <input type="text" name="name" id="name" required>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>

        <label for="address">Shipping Address:</label>
        <textarea name="address" id="address" required></textarea>

        <!-- <label for="card_number">Card Number:</label>
        <input type="number" name="card_number" id="card_number" required>

        <label for="expiry_date">Expiry Date:</label>
        <input type="text" name="expiry_date" id="expiry_date" placeholder="MM/YY" required>

        <label for="cvv">CVV:</label>
        <input type="number" name="cvv" id="cvv" required> -->

        <button type="submit">Place Order</button>
    </form>
</div>
</body>
</html>

