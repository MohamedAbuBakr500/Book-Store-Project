<?php

include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location: login.php');
}

if (isset($_POST['add_to_cart'])) {

    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity'];

    // Sanitize user inputs to prevent XSS (Cross-Site Scripting)
    $product_name = htmlspecialchars($product_name, ENT_QUOTES, 'UTF-8');
    $product_price = htmlspecialchars($product_price, ENT_QUOTES, 'UTF-8');
    $product_image = htmlspecialchars($product_image, ENT_QUOTES, 'UTF-8');
    $product_quantity = intval($product_quantity); // Ensure quantity is an integer


    $check_cart_stmt = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
    if (!$check_cart_stmt) {
        die("ERROR: Prepare failed: $conn->error");
    }
    $check_cart_stmt->bind_param("si", $product_name, $user_id);
    if (!$check_cart_stmt->execute()) {
        die("ERROR: Execute failed: $check_cart_stmt->error");
    }
    $check_cart_result = $check_cart_stmt->get_result();
    $check_cart_stmt->close();

    if ($check_cart_result->num_rows > 0) {
        $message[] = 'Already added to cart!';
    } else {
        $insert_cart_stmt = $conn->prepare("INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES(?, ?, ?, ?, ?)");
        if (!$insert_cart_stmt) {
            die("ERROR: Prepare failed: $conn->error");
        }
        $insert_cart_stmt->bind_param("isiis", $user_id, $product_name, $product_price, $product_quantity, $product_image);
        if (!$insert_cart_stmt->execute()) {
            die("ERROR: Execute failed: $insert_cart_stmt->error");
        }
        $insert_cart_stmt->close();
        $message[] = 'Product added to cart!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search page</title>
    <!-- font awesome cdn link   -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <!-- custom css file link -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <?php include 'header.php'; ?>


    <div class="heading">
        <h3>Search page</h3>
        <p> <a href="home.php">Home</a> / Search</p>
    </div>

    <section class="search-form">
        <form action="" method="post">
            <input type="text" name="search" placeholder="search products..." class="box">
            <input type="submit" name="submit" value="search" class="btn">
        </form>
    </section>

    <section class="products" style="padding-top:0;">
        <div class="box-container">
            <?php

            if (isset($_POST['submit'])) {
                $search_item = $_POST['search'];

                // Sanitize search input for XSS prevention
                $search_item = htmlspecialchars($search_item, ENT_QUOTES, 'UTF-8');

                if (!empty($search_item)) { //Check if $search_item is not empty before querying the database

                    // Prepare the SELECT statement with LIKE for searching
                    $select_stmt = $conn->prepare("SELECT * FROM `products` WHERE name LIKE ?");

                    if (!$select_stmt) {
                        die("Prepare failed: " . $conn->error);
                    }

                    // Add wildcards (%) for partial matches
                    $search_term = "%" . $search_item . "%"; // Important for LIKE queries

                    // Bind the parameter
                    $select_stmt->bind_param("s", $search_term); // "s" indicates string

                    // Execute the query
                    if (!$select_stmt->execute()) {
                        die("Execute failed: " . $select_stmt->error);
                    }

                    $select_result = $select_stmt->get_result();

                    if ($select_result->num_rows > 0) {
                        while ($fetch_product = $select_result->fetch_assoc()) {
                            // ... (Your code to display product information) ...
            ?>
                            <form action="" method="post" class="box">
                                <img src="uploaded_img/<?php echo $fetch_product['image']; ?>" alt="" class="image">
                                <div class="name"><?php echo $fetch_product['name']; ?></div>
                                <div class="price">$<?php echo $fetch_product['price']; ?>/-</div>
                                <input type="number" class="qty" name="product_quantity" min="1" value="1">
                                <input type="hidden" name="product_name" value="<?php echo $fetch_product['name']; ?>">
                                <input type="hidden" name="product_price" value="<?php echo $fetch_product['price']; ?>">
                                <input type="hidden" name="product_image" value="<?php echo $fetch_product['image']; ?>">
                                <input type="submit" class="btn" value="add to cart" name="add_to_cart">
                            </form>
            <?php
                        }
                    } else {
                        echo '<p class="empty">no result found!</p>';
                    }
                    $select_stmt->close();
                } else {
                    echo '<p center class="empty" style="max-width: 30%; margin: auto;">search something!</p>'; // Correct placement of the message
                }
            }

            ?>
        </div>
    </section>

    <?php include 'footer.php' ?>

    <script src="js/script.js"></script>
</body>

</html>