<?php
include "conn.php";
$response = [];

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    try {
        if (empty($_POST['email'])) {
            $response['error_email'] = 'Email is required';
        } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $response['error_email'] = 'Enter a Valid Email';
        } else {
            $email = $_POST['email'];
            $stmt = $conn->prepare("SELECT * FROM test_table_reg WHERE email = ?");
            $stmt->bindParam(1, $email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if($user) 
            {

                if (empty($_POST['password'])) {
                    $response['error_password'] = "Password is required";
                } else {
                    $password = $_POST['password'];
        
                   
        
                    if ($user && password_verify($password, $user['password'])) {
                        session_start();
                        $_SESSION['user_id'] = $user['id'];
                        $response['status'] = "success";
                    } else {
                        $response['error_password'] = "Incorrect Password";
                    }
            }
        }
            else
            {
                $response['error_email'] = 'User not Found';
            }


        }
        

       
        
    } catch (PDOException $e) {
        $response["Error"] = "Error: " . $e->getMessage();
    }

    echo json_encode($response); 
} else {
    $response["message"] = "Error: Invalid request method.";
    echo json_encode($response);
}
?>
