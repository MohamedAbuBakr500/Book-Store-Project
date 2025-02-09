<?php 

include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
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
    if(!$check_cart_stmt){
        die("ERROR: Prepare failed: $conn->error");
    }
    $check_cart_stmt->bind_param("si", $product_name, $user_id);
    if(!$check_cart_stmt->execute()){
        die("ERROR: Execute failed: $check_cart_stmt->error");
    }
    $check_cart_result = $check_cart_stmt->get_result();
    $check_cart_stmt->close();

    if($check_cart_result->num_rows > 0){
        $message[] = 'Already added to cart!';
    }else{
        $insert_cart_stmt = $conn->prepare("INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES(?, ?, ?, ?, ?)");
        if(!$insert_cart_stmt){
            die("ERROR: Prepare failed: $conn->error");
        }
        $insert_cart_stmt->bind_param("isiis", $user_id, $product_name,$product_price, $product_quantity, $product_image);
        if(!$insert_cart_stmt->execute()){
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
    <title>Shop</title>
    <!-- font awesome cdn link   -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <!-- custom css file link -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    
<?php include 'header.php' ;?>

<div class="heading">
    <h3>Our shop</h3>
    <p> <a href="home.php">Home</a> / Shop</p>
</div>


<section class="products">

    <h2 class="title">Latest products</h2>

    <div class="box-container">
        <?php
            $stmt = $conn->prepare("SELECT * FROM `products`");

            if(!$stmt){
                die('Prepare failed: ' . $stmt->error);
            }

            if(!$stmt->execute()){
                die('Execute failed: ' . $stmt->error);
            }
            $result = $stmt->get_result();

            if($result->num_rows > 0){
                while($fetch_products = $result->fetch_assoc()){
        ?>
        <form action="" method="post" class="box">
            <img class="image" src="uploaded_img/<?php echo htmlspecialchars($fetch_products['image']); ?>">
            <div class="name"><?php echo htmlspecialchars($fetch_products['name']); ?></div>
            <div class="price"><?php echo htmlspecialchars($fetch_products['price']); ?>/-</div>
            <input type="number" min="1" name="product_quantity" value="1" class="qty">
            <input type="hidden" name="product_name" value="<?php echo$fetch_products['name']; ?>">
            <input type="hidden" name="product_price" value="<?php echo$fetch_products['price']; ?>">
            <input type="hidden" name="product_image" value="<?php echo$fetch_products['image']; ?>">
            <input type="submit" name="add_to_cart" value="Add to Cart" class="btn">
        </form>
        <?php
                }
            }else{
                echo '<p class="empty"> No products added yet!</p>';
            }
        ?>
            
    </div>

</section>


<?php include 'footer.php'?>

<script src="js/script.js"></script>
</body>
</html>