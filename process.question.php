<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    // Redirect to login page if not logged in
    header("Location: login.html");
    exit();
}

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection parameters
    $servername = "127.0.0.1:3307";
    $username = "root";
    $password = "";
    $dbname = "routeskin";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL statement to insert user responses
    $stmt = $conn->prepare("INSERT INTO user_responses ( skin_type, skin_concerns, breakouts, skincare_products, sunscreen_usage, skincare_allergies, routine_rating,user_email) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    // Bind parameters
    $stmt->bind_param("ssssssss", $skin_type, $skin_concerns, $breakouts, $skincare_products, $sunscreen_usage, $skincare_allergies, $routine_rating, $user_email);

    // Set parameters from form data
   
    $skin_type = $_POST['skin-type'];
    $skin_concerns = $_POST['skin-concerns'];
    $breakouts = $_POST['breakouts'];
    $skincare_products = implode(", ", (array)$_POST['skincare-product']);
    $sunscreen_usage = $_POST['sunscreen-usage'];
    $skincare_allergies = $_POST['skincare-allergies'];
    $routine_rating = $_POST['routine-rating'];
    $user_email = $_SESSION['email'];
    
    // Execute SQL statement
    if ($stmt->execute()) {
        echo "User response inserted successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    // Redirect to questions page if form data is not submitted
    header("Location: questions.html");
    exit();
}
?>
