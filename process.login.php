<?php
session_start();

$servername = "127.0.0.1:3307"; // Hostname of the database server
$username = "root"; // MySQL username (default is root)
$password = ""; // MySQL password (default is empty)
$database = "routeskin"; // Name of the database

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Perform server-side validation
    $errors = array();

    // Validate email
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    // Validate password
    if (empty($password)) {
        $errors[] = "Password is required";
    }

    // If there are no validation errors, proceed to verify user login
    if (empty($errors)) {
        // Verify user login
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                // Successful login
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_email'] = $row['email'];
                header("Location: dashboard.php"); // Redirect to dashboard or another page
                exit();
            } else {
                $errors[] = "Incorrect password";
            }
        } else {
            $errors[] = "User not found";
        }
    }

    // Display validation errors
    foreach ($errors as $error) {
        echo $error . "<br>";
    }
}

// Close connection
$conn->close();
?>
