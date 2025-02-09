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
    <title>About</title>
    <!-- font awesome cdn link   -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <!-- custom css file link -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    
<?php include 'header.php' ;?>

<div class="heading">
    <h3>about us</h3>
    <p> <a href="home.php">Home</a> / about</p>
</div>

<section class="about">
    <div class="flex">
        <div class="image">
            <img src="images/about-img.jpg" alt="Image of the bookstore">
        </div>
        <div class="content">
            <h3>Why Choose Us?</h3>
            <p>At Bookly, we're more than just a place to buy books. We're a community of passionate readers, dedicated to providing you with an exceptional literary experience. We carefully curate our collection, ensuring a diverse selection of genres and authors to satisfy every taste.</p>
            <a href="contact.php" class="btn">Contact Us</a>
        </div>
    </div>
</section>

<section class="reviews">
    <h2 class="title">Clinet`s reviews</h2>

    <div class="box-container">

    <div class="box">
        <img src="images/pic-1.png" alt="Customer Avatar">
        <p>I was thoroughly impressed with the selection and service at this bookstore. They have a great variety of books, and the staff was incredibly helpful in finding exactly what I was looking for. I highly recommend it!</p>
        <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
        </div>
        <h3>John hern</h3>
    </div>

    <div class="box">
        <img src="images/pic-2.png" alt="Customer Avatar">
        <p>I was thoroughly impressed with the selection and service at this bookstore. They have a great variety of books, and the staff was incredibly helpful in finding exactly what I was looking for. I highly recommend it!</p>
        <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
        </div>
        <h3>Sarah Miller</h3>
    </div>

    <div class="box">
        <img src="images/pic-3.png" alt="Customer Avatar">
        <p>I was thoroughly impressed with the selection and service at this bookstore. They have a great variety of books, and the staff was incredibly helpful in finding exactly what I was looking for. I highly recommend it!</p>
        <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
        </div>
        <h3>Omar Ahmed</h3>
    </div>

    <div class="box">
        <img src="images/pic-4.png" alt="Customer Avatar">
        <p>I was thoroughly impressed with the selection and service at this bookstore. They have a great variety of books, and the staff was incredibly helpful in finding exactly what I was looking for. I highly recommend it!</p>
        <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
        </div>
        <h3>Malak Hade</h3>
    </div>

    <div class="box">
        <img src="images/pic-5.png" alt="Customer Avatar">
        <p>I was thoroughly impressed with the selection and service at this bookstore. They have a great variety of books, and the staff was incredibly helpful in finding exactly what I was looking for. I highly recommend it!</p>
        <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
        </div>
        <h3>Amr Gotama</h3>
    </div>

    <div class="box">
        <img src="images/pic-6.png" alt="Customer Avatar">
        <p>I was thoroughly impressed with the selection and service at this bookstore. They have a great variety of books, and the staff was incredibly helpful in finding exactly what I was looking for. I highly recommend it!</p>
        <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
        </div>
        <h3> Jody mohamed</h3>
    </div>

    </div>
</section>

<section class="authors">
    <h1 class="title">Great Authors</h1>
    <div class="box-container">
        <div class="box">
            <img src="images/author-1.jpg" alt="Author's Photo">
            <div class="share">
                <a href="#" class="fab fa-facebook-f"></a>
                <a href="#" class="fab fa-twitter"></a>
                <a href="#" class="fab fa-instagram"></a>
                <a href="#" class="fab fa-linkedin"></a>
            </div>
            <h3>Jane Austen</h3>
        </div>
        <div class="box">
            <img src="images/author-2.jpg" alt="Author's Photo">
            <div class="share">
                <a href="#" class="fab fa-facebook-f"></a>
                <a href="#" class="fab fa-twitter"></a>
                <a href="#" class="fab fa-instagram"></a>
                <a href="#" class="fab fa-linkedin"></a>
            </div>
            <h3>Stephen King</h3>
        </div>
        <div class="box">
            <img src="images/author-3.jpg" alt="Author's Photo">
            <div class="share">
                <a href="#" class="fab fa-facebook-f"></a>
                <a href="#" class="fab fa-twitter"></a>
                <a href="#" class="fab fa-instagram"></a>
                <a href="#" class="fab fa-linkedin"></a>
            </div>
            <h3>J.R.R. Tolkien</h3>
        </div>
    </div>
</section>


<?php include 'footer.php'?>

<script src="js/script.js"></script>
</body>
</html>