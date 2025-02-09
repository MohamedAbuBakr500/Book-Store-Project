<?php

include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location: login.php');
}

if (isset($_POST['update_cart'])) {
    $cart_id = $_POST['cart_id'];
    $cart_quantity = $_POST['cart_quantity'];

    // Sanitize the cart_quantity to ensure it's an integer
    $cart_quantity = intval($cart_quantity);

    // Prepare the UPDATE statement
    $update_stmt = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");

    if (!$update_stmt) {
        die("Prepare failed: " . $conn->error); // Handle prepare errors
    }

    // Bind the parameters. "ii" means both are integers.
    $update_stmt->bind_param("ii", $cart_quantity, $cart_id);

    // Execute the statement
    if (!$update_stmt->execute()) {
        die("Execute failed: " . $update_stmt->error); // Handle execute errors
    }

    $update_stmt->close();

    $message[] = 'cart quantity updated!';
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];

    // Prepare the DELETE statement
    $delete_stmt = $conn->prepare("DELETE FROM `cart` WHERE id = ?");

    if (!$delete_stmt) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind the parameter. "i" indicates an integer.
    $delete_stmt->bind_param("i", $delete_id);

    // Execute the statement
    if (!$delete_stmt->execute()) {
        die("Execute failed: " . $delete_stmt->error);
    }

    $delete_stmt->close();

    header('location:cart.php');
}

if (isset($_GET['delete_all'])) {

    // Prepare the DELETE statement for all items of a user
    $delete_all_stmt = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");

    if (!$delete_all_stmt) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind the parameter. "i" indicates an integer.
    $delete_all_stmt->bind_param("i", $user_id);

    // Execute the statement
    if (!$delete_all_stmt->execute()) {
        die("Execute failed: " . $delete_all_stmt->error);
    }

    $delete_all_stmt->close();

    header('location:cart.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <!-- font awesome cdn link   -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <!-- custom css file link -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <?php include 'header.php'; ?>


    <div class="heading">
        <h3>Shopping cart</h3>
        <p> <a href="home.php">Home</a> / Cart</p>
    </div>

    <section class="shopping-cart">
        <h2 class="title">Products added</h2>

        <div class="box-container">
            <?php
                $grand_total = 0;
                $select_stmt = $conn->prepare("SELECT * FROM `cart` WHERE $user_id = ?");
                if (!$select_stmt) {
                    die("Connection failed: " . $conn->connect_error);
                }
                $select_stmt->bind_param("i", $user_id);
                if (!$select_stmt->execute()) {
                    die("Execute failed: " . $select_stmt->error);
                }
                $result = $select_stmt->get_result();
                if ($result->num_rows > 0) {
                    while ($fetch_cart = $result->fetch_assoc()) {
            ?>
                        <div class="box">
                            <a href="cart.php?delete=<?php echo $fetch_cart['id']; ?>" class="fas fa-times" onclick="return confirm('delete this from cart?')"></a>
                            <img src="uploaded_img/<?php echo $fetch_cart['image']; ?>" alt="">
                            <div class="name"><?php echo htmlspecialchars($fetch_cart['name'], ENT_QUOTES, 'UTF-8'); ?></div>
                            <div class="price">$<?php echo htmlspecialchars($fetch_cart['price'], ENT_QUOTES, 'UTF-8'); ?>/-</div>
                            <form action="" method="post">
                                <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']?>">
                                <input type="number" min="1" name="cart_quantity" value="<?php echo $fetch_cart['quantity']?>">
                                <input type="submit" value="update" name="update_cart" class="option-btn">
                            </form>
                            <div class="sub-total">Aub total : <span>$<?php echo $sub_total = ($fetch_cart['quantity'] * $fetch_cart['price']); ?>/-</span></div>
                        </div>
            <?php
                $grand_total += $sub_total;
                    }
                } else {
                    echo '<p class="empty">your cart is empty</p>';
                }
                $select_stmt->close();
            ?>
        </div>

        <div style="margin-top:2rem; text-align:center;">
            <a href="cart.php?delete_all" class="delete-btn  <?php echo ($grand_total > 1)? '':'disabled';?>" onclick="return confirm('delete all from cart?')">Delete all</a>
        </div>

        <div class="cart-total">
            <p> Grand total : <span>$<?php echo $grand_total; ?>/-</span></p>
            <div class="flex">
                <a href="shop.php" class="option-btn">Continue shopping</a>
                <a href="checkout.php" class="btn <?php echo ($grand_total > 1)? '':'disabled';?>">Proceed to checkout</a>
            </div>
        </div>
    </section>

    <?php include 'footer.php' ?>

    <script src="js/script.js"></script>
</body>

</html>