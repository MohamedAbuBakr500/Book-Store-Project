<?php 

include 'config.php';

if(isset($_POST['submit'])){
    $name = mysqli_real_escape_string($conn,$_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = mysqli_real_escape_string($conn, md5($_POST['password']));
    $cpass = mysqli_real_escape_string($conn, md5($_POST['cpassword']));
    $user_type = $_POST['user_type'];

    $query = "SELECT * FROM `users` WHERE email = '$email' AND password = '$pass'" ;

    $select_users = mysqli_query($conn, $query) or die('query failed');

    if(mysqli_num_rows($select_users) > 0){
        $message[] = 'User already exist!';
    }else{
        if($pass != $cpass){
            $message[] = 'Confirm password not matched!';
        }else{
            $insert = "INSERT INTO `users`(name,email,password,user_type) VALUES('$name','$email','$pass','$user_type')";
            mysqli_query($conn, $insert) or die("Query failed");
            $message[] = "registered successfully";
            header('location: login.php');
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="shortcut icon" href="images/icon_store.png" type="image/x-icon">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>


    
    <?php
        if(isset($message)){
            foreach($message as $message){
                echo '
                <div class="message">
                    <span>'.$message.'</span>
                    <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
                </div>
                ';
            }
        }
    
    ?>

    
    <div class="form-container">
        <form action="" method="post">
            <h3>register now</h3>
            <input type="text" name="name" placeholder="Enter you name" required class="box">
            <input type="email" name="email" placeholder="Enter you email" required class="box">
            <input type="password" name="password" placeholder="Enter you password" required class="box">
            <input type="password" name="cpassword" placeholder="confirm you password" required class="box">
            <select name="user_type" >
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
            <input type="submit" name="submit" value="register now" class="btn">
            <p>Already have an account? <a href="login.php">Log-in now</a></p>
            
        </form>
    </div>
</body>
</html>