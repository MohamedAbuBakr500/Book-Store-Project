<?php 

include 'config.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
    header('location: login.php');
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
    <title>Admin panel</title>
</head>
<body>
    
<?php include 'admin_header.php'; ?>


<!-- admin dashboard section starts -->
 <section class="dashboard">
    <h2 class="title">Dashboard</h2>
    <div class="box-container">

        <div class="box">
            <?php 
                $total_pendings = 0;
                $query = "SELECT total_price FROM `orders` WHERE payment_status = 'pending'";
                $select_pending = mysqli_query($conn, $query) or die('query failed');
                if(mysqli_num_rows($select_pending) > 0){
                    while($fetch_pending = mysqli_fetch_assoc($select_pending)){
                        $total_price = $fetch_pending['total_price'];
                        $total_pendings += $total_price;
                    };
                };
            ?>
            <h3><?php echo $total_pendings;?>/-</h3>
            <p>Total pendings</p>
        </div>

        
        <div class="box">
            <?php 
                $total_completed = 0;
                $query = "SELECT total_price FROM `orders` WHERE payment_status = 'completed'";
                $select_completed = mysqli_query($conn, $query) or die('query failed');
                if(mysqli_num_rows($select_completed) > 0){
                    while($fetch_completed = mysqli_fetch_assoc($select_completed)){
                        $total_price = $fetch_completed['total_price'];
                        $total_completed += $total_price;
                    };
                };
            ?>
            <h3><?php echo $total_completed;?>/-</h3>
            <p>Completed payments</p>
        </div>

        <div class="box">
            <?php
                $select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
                $number_of_orders = mysqli_num_rows($select_orders);
            ?>
            <h3><?php echo $number_of_orders;?></h3>
            <p>Orders placed</p>
        </div>

        

        <div class="box">
            <?php
                $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
                $number_of_products = mysqli_num_rows($select_products);
            ?>
            <h3><?php echo $number_of_products;?></h3>
            <p>Products added</p>
        </div>

        

        <div class="box">
            <?php
                $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE user_type = 'user'") or die('query failed');
                $number_of_users = mysqli_num_rows($select_users);
            ?>
            <h3><?php echo $number_of_users;?></h3>
            <p>Normal users</p>
        </div>
        

        <div class="box">
            <?php
                $select_admins = mysqli_query($conn, "SELECT * FROM `users` WHERE user_type = 'admin'") or die('query failed');
                $number_of_admins = mysqli_num_rows($select_admins);
            ?>
            <h3><?php echo $number_of_admins;?></h3>
            <p>Admin users</p>
        </div>

        

        <div class="box">
            <?php
                $select_account = mysqli_query($conn, "SELECT * FROM `users`") or die('query failed');
                $number_of_account = mysqli_num_rows($select_account);
            ?>
            <h3><?php echo $number_of_account;?></h3>
            <p>Total users</p>
        </div>
        

        <div class="box">
            <?php
                $select_messages = mysqli_query($conn, "SELECT * FROM `message`") or die('query failed');
                $number_of_messages = mysqli_num_rows($select_messages);
            ?>
            <h3><?php echo $number_of_messages;?></h3>
            <p>New messages</p>
        </div>

        

    </div>
 </section>
<!-- admin dashboard section ends -->

<script src="js/admin_script.js"></script>

</body>
</html>