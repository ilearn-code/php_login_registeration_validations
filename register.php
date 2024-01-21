<?php
include "conn.php";
$response = ['success' => 0, 'errors' => []];

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    try {
        $validNameRegex = "/^[a-zA-Z]+(?:\s[a-zA-Z]+)*$/";

        if (isset($_POST["fname"]) && isset($_POST["lname"]) && isset($_POST['email'])) {
            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            $mobile = $_POST['mobile'];

            if (empty($fname)) {
                $response['errors']['fname'] = 'First name is required';
            } elseif (!preg_match($validNameRegex, $_POST['fname'])) {
                $response['errors']['fname'] = 'First Name is not a valid value';
            }

            if (empty($lname)) {
                $response['errors']['lname'] = 'Last Name is Required';
            } elseif (!preg_match($validNameRegex, $_POST['lname'])) {
                $response['errors']['lname'] = 'Last Name is not a valid value';
            }

            if (empty($email)) {
                $response['errors']['email'] = 'Email is Required';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $response['errors']['email'] = 'Enter a valid Email';
            } else {
                $stmt = $conn->prepare('SELECT email from test_table_reg where email=?');
                $stmt->bindParam(1, $email);
                $stmt->execute();
                $db_email = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($db_email) {
                    $response['errors']['email'] = 'User already registered';
                }
            }

            if (empty($password)) {
                $response['errors']['password'] = 'Password is required';
                if (empty($confirm_password)) {
                    $response['errors']['cpassword'] = 'Confirm Password is required';
                }
            } else {
                if ($password != $confirm_password) {
                    $response['errors']['password'] = 'Password and Confirm Password do not match.';
                } elseif (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $password)) {
                    $response['errors']['password'] = 'Password must be at least 8 characters long, containing one uppercase, one lowercase, one numeric value, and one special character.';
                }
            }

            if (empty($mobile)) {
                $response['errors']['mobile'] = 'Mobile Number is Required';
            } else {
                $mmobile = preg_replace('/[^0-9]/', '', $mobile);
                if (strlen($mmobile) !== 10) {
                    $response['errors']['mobile'] = 'Invalid Mobile Number';
                }
            }

            if (empty($response['errors'])) {
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $conn->prepare("INSERT INTO test_table_reg (fname, lname, email, password, mno) VALUES (?, ?, ?, ?, ?)");
                $stmt->bindParam(1, $fname);
                $stmt->bindParam(2, $lname);
                $stmt->bindParam(3, $email);
                $stmt->bindParam(4, $hashed_password);
                $stmt->bindParam(5, $mobile);

                if ($stmt->execute()) {
                    $response['success'] = 1;
                    $response['message'] = 'User Registered Successfully';
                } else {
                    $response['errors']['db_error'] = $stmt->errorInfo();
                    // Log the database insertion error for debugging
                    // error_log("Database Insertion Error: " . json_encode($stmt->errorInfo()));
                }
            }
        }
    } catch (PDOException $e) {
        $response['errors']['message'] = 'Error: ' . $e->getMessage();
        // Log the PDO exception for debugging
        // error_log("PDO Exception: " . $e->getMessage());
    }
} else {
    $response['errors']['message'] = 'Error: Invalid request method.';
}

echo json_encode($response);
?>
