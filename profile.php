<?php
session_start();

if (!isset($_SESSION['user_id'])) {
   
    header("Location: login.php");
    exit();
}

include "conn.php";

try {
    $user_id = $_SESSION['user_id'];
    
    $stmt = $conn->prepare("SELECT * FROM test_table_reg WHERE id = ?");
    $stmt->bindParam(1, $user_id);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>

</head>
<body>
    <div style="max-width: 600px; margin: 0 auto;">

        <img src="https://writestylesonline.com/wp-content/uploads/2016/08/Follow-These-Steps-for-a-Flawless-Professional-Profile-Picture.jpg" alt="Site Logo" style="width: 100%; max-height: 150px;">

        <h2>User Profile</h2>

        <?php if ($user): ?>
            <p><strong>First Name:</strong> <?php echo $user['fname']; ?></p>
            <p><strong>Last Name:</strong> <?php echo $user['lname']; ?></p>
            <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
            <p><strong>Mobile:</strong> <?php echo $user['mno']; ?></p>


            <a href="logout.php">Logout</a>
        <?php else: ?>
            <p>User not found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
