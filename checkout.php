<?php 

include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
    header('location: login.php');
}

if (isset($_POST['order_btn'])) {
    $name = $_POST['name'];
    $number = $_POST['number'];
    $email = $_POST['email'];
    $method = $_POST['method'];
    $flat = $_POST['flat'];
    $street = $_POST['street'];
    $city = $_POST['city'];
    $country = $_POST['country'];
    $pin_code = $_POST['pin_code'];
    $placed_on = date('d-M-Y');

    // Sanitize for XSS
    $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
    $method = htmlspecialchars($method, ENT_QUOTES, 'UTF-8');
    $flat = htmlspecialchars($flat, ENT_QUOTES, 'UTF-8');
    $street = htmlspecialchars($street, ENT_QUOTES, 'UTF-8');
    $city = htmlspecialchars($city, ENT_QUOTES, 'UTF-8');
    $country = htmlspecialchars($country, ENT_QUOTES, 'UTF-8');
    $pin_code = htmlspecialchars($pin_code, ENT_QUOTES, 'UTF-8');

    $address = 'flat no. ' . $flat . ', ' . $street . ', ' . $city . ', ' . $country . ' - ' . $pin_code;

    $cart_total = 0;
    $cart_products = array();

    // Prepared statement for cart retrieval
    $cart_stmt = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
    if (!$cart_stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $cart_stmt->bind_param("i", $user_id);
    if (!$cart_stmt->execute()) {
        die("Execute failed: " . $cart_stmt->error);
    }
    $cart_result = $cart_stmt->get_result();

    if ($cart_result->num_rows > 0) {
        while ($cart_item = $cart_result->fetch_assoc()) {
            $cart_products[] = htmlspecialchars($cart_item['name'], ENT_QUOTES, 'UTF-8') . ' (' . htmlspecialchars($cart_item['quantity'], ENT_QUOTES, 'UTF-8') . ') '; // Sanitize product name and quantity
            $sub_total = ($cart_item['price'] * $cart_item['quantity']);
            $cart_total += $sub_total;
        }
    }
    $cart_stmt->close();

    $total_products = implode(', ', $cart_products);

    // Prepared statement for order check
    $order_check_stmt = $conn->prepare("SELECT * FROM `orders` WHERE name = ? AND number = ? AND email = ? AND method = ? AND address = ? AND total_products = ? AND total_price = ?");
    if (!$order_check_stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $order_check_stmt->bind_param("ssssssd", $name, $number, $email, $method, $address, $total_products, $cart_total);
    if (!$order_check_stmt->execute()) {
        die("Execute failed: " . $order_check_stmt->error);
    }
    $order_check_result = $order_check_stmt->get_result();
    $order_check_stmt->close();

    if ($cart_total == 0) {
        $message[] = 'your cart is empty';
    } else {
        if ($order_check_result->num_rows > 0) {
            $message[] = 'order already placed!';
        } else {
            // Prepared statement for order insertion
            $insert_order_stmt = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            if (!$insert_order_stmt) {
                die("Prepare failed: " . $conn->error);
            }
            $insert_order_stmt->bind_param("issssssds", $user_id, $name, $number, $email, $method, $address, $total_products, $cart_total, $placed_on);
            if (!$insert_order_stmt->execute()) {
                die("Execute failed: " . $insert_order_stmt->error);
            }
            $insert_order_stmt->close();

            $message[] = 'order placed successfully!';

            // Prepared statement for cart deletion
            $delete_cart_stmt = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
            if (!$delete_cart_stmt) {
                die("Prepare failed: " . $conn->error);
            }
            $delete_cart_stmt->bind_param("i", $user_id);
            if (!$delete_cart_stmt->execute()) {
                die("Execute failed: " . $delete_cart_stmt->error);
            }
            $delete_cart_stmt->close();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <!-- font awesome cdn link   -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <!-- custom css file link -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    
<?php include 'header.php' ;?>


<div class="heading">
    <h3>Checkout</h3>
    <p> <a href="home.php">Home</a> / Checkout</p>
</div>

<section class="display-order">
    <?php
        $grand_total = 0;

        // Prepare the SELECT statement
        $select_stmt = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");

        if (!$select_stmt) {
            die("Prepare failed: " . $conn->error);
        }

        // Bind the user_id parameter
        $select_stmt->bind_param("i", $user_id); // "i" indicates integer

        // Execute the query
        if (!$select_stmt->execute()) {
            die("Execute failed: " . $select_stmt->error);
        }

        $select_result = $select_stmt->get_result();

        if ($select_result->num_rows > 0) {
            while ($fetch_cart = $select_result->fetch_assoc()) {
                $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
                $grand_total += $total_price;
                ?>
                <p>
                    <?php echo htmlspecialchars($fetch_cart['name'], ENT_QUOTES, 'UTF-8'); ?>
                    <span>(<?php  echo '$'. htmlspecialchars($fetch_cart['price'].'/-'.'.'.' x '. $fetch_cart['quantity']); ?>)</span>
                </p>
                <?php
            }
        } else {
            echo '<p class="empty">your cart is empty</p>';
        }

        $select_stmt->close(); // Close the statement
    ?>
    <div class="grand-total"> Grand total : <span>$<?php echo $grand_total;?>/- </span> </div>
</section>


<section class="checkout">
    <form action="" method="post">
        <h3>place your order</h3>
        <div class="flex">
            <div class="inputBox">
                <span>Your Name :</span>
                <input type="text" name="name" required placeholder="enter your name">
            </div>
            <div class="inputBox">
                <span>Your Number :</span>
                <input type="number" name="number" required placeholder="enter your number">
            </div>
            <div class="inputBox">
                <span>Your Email :</span>
                <input type="email" name="email" required placeholder="enter your email">
            </div>
            <div class="inputBox">
                <span>Payment Method :</span>
                <select name="method" id="">
                    <option value="cash on delivery">cash on delivery</option>
                    <option value="credit card">credit card</option>
                    <option value="paypal">paypal</option>
                    <option value="paytm">paytm</option>
                </select>
            </div>
            <div class="inputBox">
                <span>address line 01 :</span>
                <input type="number" min="0" name="flat" required placeholder="e.g. flat no.">
            </div>
            <div class="inputBox">
                <span>address line 02 :</span> <input type="text" name="street" required placeholder="e.g. street name">
            </div>
            <div class="inputBox">
                <span>City:</span>
                <input type="text" name="city" required placeholder="e.g. Cairo">
            </div>
            <div class="inputBox">
                <span>Governorate/Region:</span>
                <input type="text" name="governorate" required placeholder="e.g. Cairo Governorate">
            </div>
            <div class="inputBox">
                <span>Country:</span>
                <input type="text" name="country" required placeholder="e.g. Egypt">
            </div>
            <div class="inputBox">
                <span>pin Code:</span>
                <input type="number" min="0" name="pin_code" required placeholder="e.g. 11511">
            </div>
        </div>
        <input type="submit" value="order now" class="btn" name="order_btn">
    </form>
</section>


<?php include 'footer.php'?>

<script src="js/script.js"></script>
</body>
</html>