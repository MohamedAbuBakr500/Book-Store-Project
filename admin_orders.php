<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
}

if(isset($_POST['update_order'])){

   $order_update_id = $_POST['order_id'];
   $update_payment = $_POST['update_payment'];
   mysqli_query($conn, "UPDATE `orders` SET payment_status = '$update_payment' WHERE id = '$order_update_id'") or die(mysqli_error($conn));
   $message[] = 'payment status has been updated!';

}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `orders` WHERE id = '$delete_id'") or die(mysqli_error($conn));
   header('location:admin_orders.php');
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="shortcut icon" href="images/icon_store.png" type="image/x-icon">
    <link rel="stylesheet" href="css/admin_style.css">
    <title>orders</title>
</head>
<body>
    
<?php include 'admin_header.php'; ?>

<section class="orders">
    <h2 class="title"> Placed orders</h2>
    <div class="box-container">
        <?php
            $select_orders = mysqli_query($conn,"SELECT * FROM `orders`") or die(mysqli_error($conn));
            if(mysqli_num_rows($select_orders) > 0){
                while($fetch_orders = mysqli_fetch_assoc($select_orders)){

        
        ?>
        <div class="box">
            <p> User id : <span><?php echo $fetch_orders['user_id']; ?></span></p>
            <p> Placed on : <span><?php echo $fetch_orders['placed_on']; ?></span></p>
            <p> Name : <span><?php echo $fetch_orders['name']; ?></span></p>
            <p> Number : <span><?php echo $fetch_orders['number']; ?></span></p>
            <p> Email : <span><?php echo $fetch_orders['email']; ?></span></p>
            <p> Address : <span><?php echo $fetch_orders['address']; ?></span></p>
            <p> Total products : <span><?php echo $fetch_orders['total_products']; ?></span></p>
            <p> Total price : <span>$<?php echo $fetch_orders['total_price']; ?>/-</span></p>
            <p> Payment method : <span><?php echo $fetch_orders['method']; ?></span></p>
            <form action="" method="post">
                <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">
                <select name="update_payment">
                    <option value="pending" selected disabled><?php echo $fetch_orders['payment_status']; ?></option>
                    <option value="pending">Pending</option>
                    <option value="completed">Completed</option>
                </select>
                <input type="submit" value="update" name="update_order" class="option-btn">
                <a href="admin_orders.php?delete=<?php echo $fetch_orders['id']; ?>" onclick="return confirm('delete this order?')" class="delete-btn">delete</a>
            </form>
        </div>
        <?php
                }
            }else{
                echo '<p class="empty">No orders placed yet!</p>';
            }
        ?>
    </div>
</section>

<script src="js/admin_script.js"></script>

</body>
</html>