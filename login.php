<?php
include "conn.php";
$response = ['success' => 0, 'errors' => []];

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    try {
        if (empty($_POST['email'])) {
            $response['errors']['email'] = 'Email is required';
        } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $response['errors']['email'] = 'Enter a Valid Email';
        } 

        if (empty($_POST['password'])) {
            $response['errors']['password'] = 'Password is required';
        }
        
        if (empty($response['errors'])) {
            $email = $_POST['email'];
            $stmt = $conn->prepare("SELECT * FROM test_table_reg WHERE email = ?");
            $stmt->bindParam(1, $email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                $password = $_POST['password'];
                if (password_verify($password, $user['password'])) {
                    session_start();
                    $_SESSION['user_id'] = $user['id'];
                    $response['success'] = 1;
                } else {
                    $response['errors']['password'] = 'Incorrect Password';
                }
            } else {
                $response['errors']['email'] = 'User not Found';
            }
        }
        
    } catch (PDOException $e) {
        $response['errors']['message'] = "Error: " . $e->getMessage();
    }

    echo json_encode($response);
    exit(); // Stop script execution after sending response
} else {
    $response['errors']['message'] = 'Error: Invalid request method.';
    echo json_encode($response);
    exit(); // Stop script execution after sending response
}
?>
