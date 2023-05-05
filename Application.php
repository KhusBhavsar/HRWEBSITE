<?php
// Set database credentials
$servername = "localhost:8111";
$username = "root";
$password = "Khush@151002";
$dbname = "hrwebsite";

// Create database connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check if connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = mysqli_real_escape_string($conn, $_POST["name"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $phone = mysqli_real_escape_string($conn, $_POST["phone"]);

    // Check if file was uploaded successfully
    if ($_FILES["file"]["error"] !== UPLOAD_ERR_OK) {
        die("Upload failed with error code " . $_FILES["file"]["error"]);
    }

    // Check if uploaded file is a PDF
    $file_type = mime_content_type($_FILES["file"]["tmp_name"]);
    if ($file_type !== "application/pdf") {
        die("File must be a PDF");
    }

    // Upload file
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["file"]["name"]);
    if (!move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        die("Failed to upload file");
    }

    // Insert data into database
    $pdf = file_get_contents($target_file);
    $pdf_encoded = base64_encode($pdf);
    $sql = "INSERT INTO hr (name, email, phone, pdf) VALUES ('$name', '$email', '$phone', '$pdf_encoded')";
    if (mysqli_query($conn, $sql)) {
        echo "Submission successful!";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

// Close database connection
mysqli_close($conn);
?>
