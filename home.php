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
    <title>Home</title>
    <!-- font awesome cdn link   -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <!-- custom css file link -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    
<?php include 'header.php' ;?>

<section class="home">
    <div class="content">
        <h3>Hand Picked Books Delivered to Your Door.</h3>
        <p>Uncover your next literary adventure with our curated selection of hand-picked books. From captivating novels to insightful non-fiction, we bring the joy of reading right to your doorstep.</p>
        <a href="about.php" class="white-btn">Discover More</a>
    </div>
</section>

<section class="products">

    <h2 class="title">Latest products</h2>

    <div class="box-container">
        <?php
            $stmt = $conn->prepare("SELECT * FROM `products` LIMIT 6");

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
            <div class="name"><?php echo htmlspecialchars($fetch_products['image']); ?></div>
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

    <div class="load-more" style="margin-top: 2rem; text-align:center;">
        <a href="shop.php" class="option-btn">Load more</a>
    </div>
</section>

<section class="about">
    <div class="flex">
        <div class="image">
            <img src="images/about-img.jpg" alt="Image of the bookstore">
        </div>
        <div class="content">
            <h3>Our Story</h3>
            <p>We are an independent bookstore with a passion for books and community. We offer a wide selection of titles, from timeless classics to the latest releases, with a focus on quality and diversity. Our goal is to create a welcoming space where book lovers can meet, discover new stories, and share their passion.</p>
            <a href="about.php" class="btn">Read More</a>
        </div>
    </div>
</section>

<section class="home-contact">
    <div class="content">
        <h3>Have Any Questions?</h3>
        <p>Feel free to contact us with any questions about our books, events, or services. We'll be happy to answer your questions and help you find the perfect book for you.</p>
        <a href="contact.php" class="white-btn">Contact Us</a>
    </div>
</section>

<?php include 'footer.php'?>

<script src="js/script.js"></script>
</body>
</html>