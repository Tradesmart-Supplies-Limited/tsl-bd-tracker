<?php
// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db   = "tsl_bd_tracker"; // Change to your actual DB name if different

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("DB error");

// Find today's birthdays
$today = date('m-d');
$sql = "SELECT member_name, email FROM members WHERE DATE_FORMAT(dob, '%m-%d') = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    file_put_contents(__DIR__ . "/birthday_log.txt", "SQL error (" . date('Y-m-d') . ")\n", FILE_APPEND);
    $conn->close();
    exit;
}
$stmt->bind_param("s", $today);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Log to file
        file_put_contents(
            __DIR__ . "/birthday_log.txt",
            "Happy Birthday {$row['member_name']} ({$row['email']}) on " . date('Y-m-d') . "\n",
            FILE_APPEND
        );

        // Email sending is disabled for now
        // $to = $row['email'];
        // $subject = "Happy Birthday!";
        // $message = "Dear {$row['member_name']},\n\nHappy Birthday from the team!\n\nBest wishes,\nYour Team";
        // $headers = "From: no-reply@yourdomain.com\r\n";
        // mail($to, $subject, $message, $headers);
    }
} else {
    file_put_contents(
        __DIR__ . "/birthday_log.txt",
        "No birthdays today (" . date('Y-m-d') . ")\n",
        FILE_APPEND
    );
}

$stmt->close();
$conn->close();
