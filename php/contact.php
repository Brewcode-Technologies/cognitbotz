<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];

    // Capture all form fields
    $name = isset($_POST["name"]) ? trim($_POST["name"]) : "";
    $phone = isset($_POST["phone"]) ? trim($_POST["phone"]) : "";
    $email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
    $message = isset($_POST["textarea"]) ? trim($_POST["textarea"]) : "";

    // Validate required fields
    if (empty($name)) $errors[] = "Name is required";
    if (empty($phone)) $errors[] = "Phone number is required";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required";
    if (empty($message)) $errors[] = "Message is required";

    // Debug: Log received data
    error_log(json_encode($_POST));

    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => implode(", ", $errors)]);
        exit;
    }

    // Email content
    $recipient = "jakkawarr@gmail.com"; 
    $subject = "New Contact Form Submission from $name";
    $email_message = "Name: $name\nPhone: $phone\nEmail: $email\nMessage: $message\n";


    $headers = "From: no-reply@eduvisa.co\r\nReply-To: $email\r\n";

    if (mail($recipient, $subject, $email_message, $headers)) {
        http_response_code(200);
        echo json_encode(["status" => "success", "message" => "Thank you! Your message has been sent."]);
    } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Oops! Something went wrong."]);
    }
} else {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method Not Allowed"]);
}
?>