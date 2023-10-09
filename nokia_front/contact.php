<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $message = $_POST["message"];

    // Process the form data here (e.g., send an email).
    // You can add your code to send emails or perform other actions.

    // Redirect back to the contact form page after processing.
    header("Location: contact.html?success=1");
    exit();
}
?>
