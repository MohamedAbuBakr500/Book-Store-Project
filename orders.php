<?php 

include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
    header('location: login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <!-- font awesome cdn link   -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <!-- custom css file link -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    
<?php include 'header.php' ;?>


<div class="heading">
    <h3>Placed orders</h3>
    <p> <a href="home.php">Home</a> / Orders</p>
</div>

<section class="placed-orders">
    <h2 class="title">Placed orders</h2>

    <div class="box-container">
        <?php 
            $order_stmt = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
            if(!$order_stmt){
                die("Prepare failed: " . $conn->error);
            }
            $order_stmt->bind_param('i', $user_id);
            if(!$order_stmt->execute()){
                die("Execute failed: " . $order_stmt->error);
            }
            $order_result = $order_stmt->get_result();
            if($order_result->num_rows > 0){
                while($fetch_orders = $order_result->fetch_assoc()){
        ?>
        <div class="box">
            <p>placed on: <span><?php echo htmlspecialchars($fetch_orders['placed_on'], ENT_QUOTES, 'UTF-8'); ?></span></p>
            <p>name: <span><?php echo htmlspecialchars($fetch_orders['name'], ENT_QUOTES, 'UTF-8'); ?></span></p>
            <p>number: <span><?php echo htmlspecialchars($fetch_orders['number'], ENT_QUOTES, 'UTF-8'); ?></span></p>
            <p>email: <span><?php echo htmlspecialchars($fetch_orders['email'], ENT_QUOTES, 'UTF-8'); ?></span></p>
            <p>address: <span><?php echo htmlspecialchars($fetch_orders['address'], ENT_QUOTES, 'UTF-8'); ?></span></p>
            <p>payment method: <span><?php echo htmlspecialchars($fetch_orders['method'], ENT_QUOTES, 'UTF-8'); ?></span></p>
            <p>your orders: <span><?php echo htmlspecialchars($fetch_orders['total_products'], ENT_QUOTES, 'UTF-8'); ?></span></p>
            <p>total price: <span>$<?php echo htmlspecialchars($fetch_orders['total_price'], ENT_QUOTES, 'UTF-8'); ?>/-</span></p>
            <p>payment status: <span style="color: <?php echo ($fetch_orders['payment_status'] == 'pending') ? 'red' : 'green'; ?>;"><?php echo htmlspecialchars($fetch_orders['payment_status'], ENT_QUOTES, 'UTF-8'); ?></span></p>
        </div>
        <?php
                }
            } else {
                echo '<p class="empty">no orders placed yet!</p>';
            }

            $order_stmt->close(); // Close the statement

        ?>   

    </div>
</section>

<?php include 'footer.php'?>

<script src="js/script.js"></script>
</body>
</html>