<?php 

include 'config.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
    header('location: login.php');
}


if (isset($_GET['delete'])) {
    // Get the user ID to delete
    $delete_id = $_GET['delete'];

    // Use a prepared statement to delete the user securely
    $stmt = $conn->prepare("DELETE FROM `users` WHERE id = ?");
    $stmt->bind_param("i", $delete_id); // Bind the user ID as an integer

    // Execute the query
    if ($stmt->execute()) {
        // Redirect after successful deletion
        header('Location: admin_users.php');
        exit();
    } else {
        // Handle errors, if any
        echo "Error deleting user: " . $stmt->error;
    }
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
    <title>users</title>
</head>
<body>
    
<?php include 'admin_header.php'; ?>

    <section class="users">
            <div class="box-container">
            <?php
                // Include your database connection
                include 'config.php';

                // Fetch all users securely using a prepared statement
                $stmt = $conn->prepare("SELECT id, name, email, user_type FROM `users`");
                $stmt->execute();
                $result = $stmt->get_result(); // Get the result set

                while ($fetch_users = $result->fetch_assoc()) {
            ?>
            <div class="box">
                <p>Username : <span> <?php echo htmlspecialchars($fetch_users['name']); ?> </span></p>
                <p>Email : <span> <?php echo htmlspecialchars($fetch_users['email']); ?> </span></p>
                <p>User Type : <span style="color:<?php if($fetch_users['user_type'] == 'admin'){ echo 'var(--orange)'; } ?>" > <?php echo htmlspecialchars($fetch_users['user_type']); ?> </span></p>
                <a href="admin_users.php?delete=<?php echo urlencode($fetch_users['id']); ?>" onclick="return confirm('Delete this user?')" class="delete-btn">delete</a>
            </div>
            <?php   
                }
            ?>


    </div>
</section>


<script src="js/admin_script.js"></script>

</body>
</html>