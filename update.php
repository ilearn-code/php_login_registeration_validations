<?php
include("conn.php");
$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        session_start();

        if (isset($_POST['fname']) && isset($_POST['lname']) && isset($_POST['email']) && isset($_POST['mobile'])) {
            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            $email = $_POST['email'];
            $mobile = $_POST['mobile'];
            $id = $_SESSION['user_id'];  // Assuming you have a user ID stored in the session

            $stmt = $conn->prepare('UPDATE test_table_reg SET fname=?, lname=?, email=?, mno=? WHERE id=?');
            $stmt->bindParam(1, $fname);
            $stmt->bindParam(2, $lname);
            $stmt->bindParam(3, $email);
            $stmt->bindParam(4, $mobile);
            $stmt->bindParam(5, $id);

            if ($stmt->execute()) {
                $response = ['success' => 1, 'message' => 'User data updated'.$fname.$lname];
            } else {
                $response = ['success' => 0, 'message' => 'Data not updated: ' . implode(", ", $stmt->errorInfo())];
            }
        }
    } catch (PDOException $e) {
        $response = ['success' => 0, 'message' => $e->getMessage()];
    }
} else {
    $response = ['success' => 0, 'message' => 'Invalid Request'];
}

echo json_encode($response);
?>
