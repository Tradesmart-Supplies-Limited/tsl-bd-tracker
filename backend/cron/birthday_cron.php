<?php
// --- Database Connection ---
$host = "localhost";
$user = "root";
$pass = "";
$db   = "tsl_bd_tracker"; // Change if needed

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Database connection error");

// --- Management Emails ---
$management = [
    "m.nakazwe@tradesmartzm.com",
    "andrew@tradesmartzm.com",
    "hr@tradesmartzm.com",
    "it.officer@tradesmartzm.com",
    "bupe@tradesmartzm.com"
];

// --- Helper: Send Email (disabled by default) ---
function sendEmail($to, $subject, $message, $headers = "")
{
    // Uncomment the next line when ready to send emails
    // mail($to, $subject, $message, $headers);
}

// --- 1. Reminders for birthdays in 3 days ---
$reminder_date = date('m-d', strtotime('+3 days'));
$reminder_log = "";

$stmt = $conn->prepare("SELECT member_name, email, dob FROM members WHERE DATE_FORMAT(dob, '%m-%d') = ?");
$stmt->bind_param("s", $reminder_date);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $subject = "Birthday Reminder: {$row['member_name']}'s birthday in 3 days";
        $message = "<h3>Birthday Reminder</h3>
            <p>{$row['member_name']} ({$row['email']}) has a birthday on {$row['dob']}.</p>";
        $headers = "MIME-Version: 1.0\r\nContent-type:text/html;charset=UTF-8\r\nFrom: no-reply@tradesmartzm.com\r\n";
        foreach ($management as $mgr_email) {
            sendEmail($mgr_email, $subject, $message, $headers);
        }
        $reminder_log .= "Reminder sent for {$row['member_name']} to management\n";
    }
} else {
    $reminder_log .= "No reminders sent (" . date('Y-m-d') . ")\n";
}
$stmt->close();

// --- 2. Birthday wishes for today ---
$today = date('m-d');
$wish_log = "";

$stmt = $conn->prepare("SELECT member_name, email, dob FROM members WHERE DATE_FORMAT(dob, '%m-%d') = ?");
$stmt->bind_param("s", $today);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $subject = "Happy Birthday, {$row['member_name']}!";
        $message = '
            <div style="font-family: Arial, sans-serif; background: #f8f9fa; padding: 20px; border-radius: 8px;">
                <h2 style="color: #007bff;">🎉 Happy Birthday!</h2>
                <p>Dear <strong>' . htmlspecialchars($row['member_name']) . '</strong>,</p>
                <p>Wishing you a wonderful birthday and a fantastic year ahead!</p>
                <hr>
                <p style="font-size: 12px; color: #888;">Best wishes,<br>Tradesmart Team</p>
            </div>
        ';
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8\r\n";
        $headers .= "From: no-reply@tradesmartzm.com\r\n";
        sendEmail($row['email'], $subject, $message, $headers);
        $wish_log .= "Birthday wish sent to {$row['member_name']} ({$row['email']})\n";
    }
} else {
    $wish_log .= "No birthday wishes sent (" . date('Y-m-d') . ")\n";
}
$stmt->close();

// --- 3. Send system logs to misc@tradesmartzm.com ---
$system_log = $reminder_log . $wish_log;
$subject = "Birthday Cron Job System Log";
$headers = "From: no-reply@tradesmartzm.com\r\n";
$message = nl2br($system_log);
sendEmail("misc@tradesmartzm.com", $subject, $message, $headers);

// --- Optionally, log to file ---
file_put_contents(__DIR__ . "/birthday_log.txt", $system_log, FILE_APPEND);

$conn->close();
