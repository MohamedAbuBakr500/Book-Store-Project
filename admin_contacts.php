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
    $stmt = $conn->prepare("DELETE FROM `message` WHERE id = ?");
    $stmt->bind_param("i", $delete_id); // Bind the user ID as an integer

    // Execute the query
    if ($stmt->execute()) {
        // Redirect after successful deletion
        header('Location: admin_contacts.php');
        exit();
    } else {
        // Handle errors, if any
        echo "Error deleting message: " . $stmt->error;
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
    <title>Messages</title>
</head>
<body>
    
<?php include 'admin_header.php'; ?>

<section class="messages">
    <h2 class="title"> Messages</h2>
    <div class="box-container">
        <?php
            $stmt = $conn->prepare("SELECT id, name, email, number, message FROM `message`");
            $stmt->execute();
            $result = $stmt->get_result();

            if($result->num_rows > 0){
                while($fetch_message = $result->fetch_assoc()){
                    ?>
                    <div class="box">
                        <p>Name : <span><?php echo htmlspecialchars($fetch_message['name']); ?></span></p>
                        <p>Number : <span><?php echo htmlspecialchars($fetch_message['number']); ?></span></p>
                        <p>Email : <span><?php echo htmlspecialchars($fetch_message['email']); ?></span></p>
                        <p>Message : <span><?php echo htmlspecialchars($fetch_message['message']); ?></span></p>
                        <a href="admin_contacts.php?delete=<?php echo urlencode($fetch_message['id']); ?>" 
                           onclick="return confirm('Delete this message?')" 
                           class="delete-btn">Delete Message</a>
                    </div>
            <?php
                }
            } else {
                echo '<p class="empty">No messages found.</p>';
            }
            ?>
    </div>
</section>

<script src="js/admin_script.js"></script>

</body>
</html>