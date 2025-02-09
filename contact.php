<?php 

include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
    header('location: login.php');
}



if (isset($_POST['send'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $number = $_POST['number'];
    $msg = $_POST['message'];

    // Sanitize inputs for XSS prevention
    $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
    $number = htmlspecialchars($number, ENT_QUOTES, 'UTF-8');
    $msg = htmlspecialchars($msg, ENT_QUOTES, 'UTF-8');

    // Prepare the SELECT statement
    $select_stmt = $conn->prepare("SELECT * FROM `message` WHERE name = ? AND email = ? AND number = ? AND message = ?");
    if (!$select_stmt) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind parameters
    $select_stmt->bind_param("ssss", $name, $email, $number, $msg);

    // Execute the SELECT statement
    if (!$select_stmt->execute()) {
        die("Execute failed: " . $select_stmt->error);
    }

    $select_result = $select_stmt->get_result();
    $select_stmt->close();


    if ($select_result->num_rows > 0) {
        $message[] = 'message sent already!';
    } else {
        // Prepare the INSERT statement
        $insert_stmt = $conn->prepare("INSERT INTO `message` (user_id, name, email, number, message) VALUES (?, ?, ?, ?, ?)");
        if (!$insert_stmt) {
            die("Prepare failed: " . $conn->error);
        }

        // Bind parameters
        $insert_stmt->bind_param("issss", $user_id, $name, $email, $number, $msg);

        // Execute the INSERT statement
        if (!$insert_stmt->execute()) {
            die("Execute failed: " . $insert_stmt->error);
        }
        $insert_stmt->close();

        $message[] = 'message sent successfully!';
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <!-- font awesome cdn link   -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <!-- custom css file link -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    
<?php include 'header.php' ;?>

<div class="heading">
    <h3>Contact us</h3>
    <p> <a href="home.php">Home</a> / Contact</p>
</div>

<section class="contact">
    <form action="" method="post">
        <h3>Say something!</h3>
        <input type="text" name="name" required placeholder="enter your name" class="box">
        <input type="email" name="email" required placeholder="enter your email" class="box">
        <input type="number" name="number" required placeholder="enter your number" class="box">
        <textarea name="message" class="box" placeholder="enter your message" id="" cols="30" rows="10"></textarea>
        <input type="submit" value="send message" name="send" class="btn">
    </form>
</section>

<?php include 'footer.php'?>

<script src="js/script.js"></script>
</body>
</html>