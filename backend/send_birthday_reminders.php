<?php
require dirname(__DIR__) . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Management emails
$management_emails = [
    "m.nakazwe@tradesmartzm.com",
    "andrew@tradesmartzm.com",
    "hr@ug.tradesmartzm.com",
    "it.officer@ug.tradesmartzm.com",
    "bupe@tradesmartzm.com"
];

// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db   = "tsl_bd_tracker";
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("DB connection failed");
}

$subject = "Upcoming Birthdays Reminder";
$body = "";

for ($days = 3; $days >= 1; $days--) {
    $target_date = date('m-d', strtotime("+$days days"));
    $sql = "SELECT member_name, email, dob FROM members WHERE DATE_FORMAT(dob, '%m-%d') = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $target_date);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $body .= "{$row['member_name']} ({$row['email']}) has a birthday in $days day" . ($days > 1 ? "s" : "") . " (" . date('F j', strtotime($row['dob'])) . ").\n";
    }
}

if ($body) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.tradesmartzm.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'YOUR_SMTP_USERNAME'; // <-- your SMTP username
        $mail->Password = 'YOUR_SMTP_PASSWORD'; // <-- your SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;
        $mail->setFrom('YOUR_FROM_ADDRESS', 'T.S.L Birthday Tracker');

        foreach ($management_emails as $to) {
            $mail->addAddress($to);
        }

        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        echo 'Reminder sent to management.';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    echo "No birthdays in the next 3 days.";
}
